<?php

/*******************************
/*******************************
 * Fetch Data Components
 *******************************
 *******************************/

$entities_per_page = 100;
$udata = $this->session->userdata('user');

//Determine what type of entity is this? (Content, people or Organization)
$entity_type = entity_type($entity);

//This also gets passed to other pages via AJAX to be applied to u_echo() in ajax calls:
$can_edit           = ( $udata['u_id']==$entity['u_id'] || array_key_exists(1281, $udata['u__inbounds']));
$can_edit_outbound  = ( $entity['u_id']==2738 ? false : $can_edit );
$can_edit_inbound   = ( in_array($entity['u_id'], array(1278,1326,2750)) || (!array_key_exists(1281, $udata['u__inbounds']) && in_array($entity_type, array(1278,2750))) ? false : $can_edit );
$add_name           = ( in_array($entity['u_id'], array(1278,2750)) ? rtrim($entity['u_full_name'],'s') : 'Content' );
$add_id             = ( in_array($entity['u_id'], array(1278,2750)) ? $entity['u_id'] : 1326 /* Content */ );


$child_entities = $this->Db_model->ur_outbound_fetch(array(
    'ur_inbound_u_id' => $inbound_u_id,
    'ur_status' => 1, //Only active
), array('u__outbound_count'), $entities_per_page);


$enrollments = array();

$b_team_member = array();

$payments = $this->Db_model->t_fetch(array(
    't_inbound_u_id' => $inbound_u_id,
));

$messages = $this->Db_model->i_fetch(array(
    'i_status >=' => 0,
    'i_outbound_u_id' => $inbound_u_id, //Referenced content in messages
));


//Construct main menu
//should correspond to the manually written code below for each tab with the data fetched above
$tabs = array(
    'outbound' => array(
        'title' => 'Outs',
        'icon' => 'fas fa-sign-out-alt rotate90',
        'item_count' => $entity['u__outbound_count'],
        'always_access' => ( $entity_type!=1326 ),
    ),
    'urls' => array(
        'title' => 'URLs',
        'icon' => 'fas fa-link',
        'item_count' => count($entity['u__urls']),
        'always_access' => ( !in_array($entity['u_id'], array(2738,1278,1326,2750)) ),
    ),
    'inbound' => array(
        'title' => 'Ins',
        'icon' => 'fas fa-sign-in-alt',
        'item_count' => count($entity['u__inbounds']),
        'always_access' => ( $entity['u_id']!=2738 ),
    ),
    'subscriptions' => array(
        'title' => 'Subscriptions',
        'icon' => 'fas fa-comment-plus',
        'item_count' => count($enrollments),
        'always_access' => 0,
    ),
    'training' => array(
        'title' => 'Training',
        'icon' => 'fas fa-whistle',
        'item_count' => count($b_team_member),
        'always_access' => 0, //Only show if entity has trained any intents. Admins cannot modify this anyways...
    ),
    'messages' => array(
        'title' => 'Messages',
        'icon' => 'fas fa-comment-dots',
        'item_count' => count($messages),
        'always_access' => 0, //Only show if entity has added any messages. Admins cannot modify this anyways...
    ),
    'payments' => array(
        'title' => 'Payments',
        'icon' => 'fab fa-paypal',
        'item_count' => count($payments),
        'always_access' => 0, //Only show if entity has sent/received payments. Admins cannot modify this anyways...
    ),
);












/*******************************
/*******************************
 * Javascript Logic
 *******************************
 *******************************/
?>
<script>

    function ur_unlink(ur_id){

        var u_level1_name = $('.top_entity .u_full_name').text();
        var u_level2_name = $('.ur_'+ur_id+' .u_full_name').text();
        var direction = ( parseInt($('.ur_'+ur_id).attr('is-inbound'))==1 ? 'inbound' : 'outbound' );
        var counter_class = '.li-'+direction+'-count';

        //Confirm that they want to do this:
        var r = confirm("Unlink ["+u_level2_name+"] from ["+u_level1_name+"]?");
        if (!(r == true)) {
            return false;
        }

        //Show loader:
        $('.ur_'+ur_id).html('<img src="/img/round_load.gif" class="loader" style="width:24px !important; height:24px !important;" /> Unlinking...').hide().fadeIn();

        //Save the rest of the content:
        $.post("/entities/unlink_entities", {

            ur_id:ur_id,

        } , function(data) {

            //OK, what happened?
            if(data.status){

                //Update UI to confirm with user:
                $('.ur_'+ur_id).fadeOut();

                //Update counter:
                $(counter_class).text((parseInt($(counter_class+':first').text())-1));

            } else {
                //There was an error, show to user:
                $('.ur_'+ur_id).html('<b style="color:#FF0000 !important;">Error: '+data.message+'</b>');
            }

        });
    }

    $(document).ready(function () {

        if (!window.location.hash) {
            //Mark the first non-hidden item as active:
            var focus = $('#topnav li:not(.hidden,.add-new):first');
            focus.addClass('active');
            $('#tab'+focus.attr('item-id')).addClass('active');
        }

        //Detect any possible hashes that controll the menu?
        if (window.location.hash) {
            focus_hash(window.location.hash);
        }

        //Watch for Reference adding:
        $('#add_url_input').keydown(function (event) {
            if ((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey) {
                add_new_url();
                event.preventDefault();
                return false;
            }
        });





        $("#new-outbound .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {

            add_u_link(suggestion.u_id);

        }).autocomplete({hint: false, keyboardShortcuts: ['a']}, [{

            source: function (q, cb) {
                algolia_u_index.search(q, {
                    hitsPerPage: 7,
                    tagFilters:[<?= ( in_array($entity['u_id'], array(1278,2750,1326)) ? "'donotshow'" /* Disable search suggest */ : "'u1326'" /* Content */ ) ?>]
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            templates: {
                suggestion: function (suggestion) {
                    //If clicked, would trigger the autocomplete:selected above which will trigger the add_u_link() function
                    return '<span><i class="fas fa-at"></i></span> ' + suggestion.u_full_name;
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:add_u_link(0,<?= $add_id ?>)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as <?= $add_name ?>]</a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:add_u_link(0,<?= $add_id ?>)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create</span> <i class="fas fa-at"></i> ' + data.query + ' [as <?= $add_name ?>]</a>';
                },
            }
        }]).keypress(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if ((code == 13) || (e.ctrlKey && code == 13)) {
                add_u_link(0,<?= $add_id ?>);
                return true;
            }
        });



        $("#new-inbound .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {
            add_u_link(suggestion.u_id);
        }).autocomplete({hint: false, keyboardShortcuts: ['a']}, [{
            source: function (q, cb) {
                algolia_u_index.search(q, {
                    hitsPerPage: 7,
                    tagFilters:[['u2750','u1278','u2738']] //Only search People & Organizations
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            templates: {
                suggestion: function (suggestion) {
                    //If clicked, would trigger the autocomplete:selected above which will trigger the add_u_link() function
                    return '<span><i class="fas fa-at"></i></span> ' + suggestion.u_full_name;
                },

                <?php if($entity_type==1326){ //Suggest both People AND Organizations as new inbound entities: ?>
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:add_u_link(0,1278)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as People]</a><a href="javascript:add_u_link(0,2750)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as Organization]</a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:add_u_link(0,1278)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as People]</a><a href="javascript:add_u_link(0,2750)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as Organization]</a>';
                },
                <?php } ?>
            }
        }]);

    });

    //Adds OR links authors and content for entities
    function add_u_link(new_u_id,secondary_parent_u_id=0) {

        //if new_u_id>0 it means we're linking to an existing entity, in which case new_u_input should be null
        //If new_u_id=0 it means we are creating a new entity and then linking it, in which case new_u_input is required

        //It's either inbound or outbound:
        var is_inbound = ( $('#nav_inbound').hasClass('active') ? 1 : 0 );

        if(is_inbound){
            var input = $('#new-inbound .new-input');
            var btn = $('#new-inbound .new-btn');
            var list_id = 'list-inbound';
            var counter_class = '.li-inbound-count';
        } else {
            var input = $('#new-outbound .new-input');
            var btn = $('#new-outbound .new-btn');
            var list_id = 'list-outbound';
            var counter_class = '.li-outbound-count';
        }


        var new_u_input = null;
        if (new_u_id==0) {
            new_u_input = input.val();
            if(new_u_input.length<1){
                alert('ERROR: Missing entity name or URL, try again');
                input.focus();
                return false;
            }
        }


        //Adjust UI to indicating loading...
        var current_href = btn.attr('href');
        input.prop('disabled', true); //Empty input
        btn.attr('href', 'javascript:void(0);').html('<i class="fas fa-spinner fa-spin"></i>');


        //Add via Ajax:
        $.post("/entities/link_entities", {

            u_id:<?= $entity['u_id'] ?>,
            new_u_id: new_u_id,
            new_u_input: new_u_input,
            is_inbound:is_inbound,
            can_edit:<?= ( $can_edit ? 1 : 0 ) ?>,
            secondary_parent_u_id:secondary_parent_u_id,

        }, function (data) {

            //Release lock:
            input.prop('disabled', false);
            btn.attr('href', current_href).html('ADD');

            if (data.status) {

                $(counter_class).text((parseInt($(counter_class+':first').text())+1));

                //Empty input to make it ready for next URL:
                input.val('').focus();

                //Add new object to list:
                add_to_list(list_id, '.u-item', data.new_u);

                //Tooltips:
                $('[data-toggle="tooltip"]').tooltip();

            } else {
                //We had an error:
                alert('Error: ' + data.message);
            }

        });
    }




    function x_cover_set(x_id) {
        //Set loader:
        $('#x_' + x_id + ' .add-cover').addClass('hidden').after('<span class="badge badge-secondary grey cover-load"><i class="fas fa-spinner fa-spin"></i></span>');

        //Add cover photo:
        $.post("/urls/set_cover", {
            x_id: x_id,
            can_edit:<?= ( $can_edit ? 1 : 0 ) ?>,
        }, function (data) {

            if (data.status) {

                $('.current-cover').remove(); //Remove Current cover icon
                $('#entity-box .profile-icon2').remove();
                $('#x_' + x_id + ' .cover-load').html(data.message);

                //Tooltips:
                $('[data-toggle="tooltip"]').tooltip();

            } else {
                //We had an error:
                alert('Error: ' + data.message);
                $('#x_' + x_id + ' .cover-load').remove();
                $('#x_' + x_id + ' .add-cover').removeClass('hidden')
            }

        });

    }


    function add_new_url() {

        if ($('#add_url_input').val().length < 1) {
            //Empty field!
            alert('Error: Input field is empty. Paste a URL and then click "Add"');
            $('#add_url_input').focus();
            return false;
        }

        //Let's try adding:
        $('#add_url_input').prop('disabled', true); //Empty input
        $('#add_url_btn').attr('href', 'javascript:void(0);').html('<i class="fas fa-spinner fa-spin"></i>');


        $.post("/urls/add_url", {

            x_outbound_u_id: <?= $entity['u_id'] ?>,
            x_url: $('#add_url_input').val(),
            can_edit:<?= ( $can_edit ? 1 : 0 ) ?>,

        }, function (data) {

            //Release lock:
            $('#add_url_input').prop('disabled', false);
            $('#add_url_btn').attr('href', 'javascript:add_new_url();').html('ADD');
            $('.no-b-div-1').remove(); //This MIGHT be there if there was no URLs previously

            if (data.status) {

                //Empty input to make it ready for next URL:
                $('#add_url_input').val('');

                //Add new object to list:
                add_to_list('list-urls', '.url-item', data.new_x);

                //Increase counter:
                $('.li-urls-count').text((parseInt($('.li-urls-count').text())+1));

                //Tooltips:
                $('[data-toggle="tooltip"]').tooltip();

                //Do we need to set this as the cover photo?
                if(data.set_cover_x_id>0){
                    setTimeout(function () {
                        x_cover_set(data.set_cover_x_id);
                    }, 377);
                }

            } else {
                //We had an error:
                alert('Error: ' + data.message);
            }

        });

    }

    function x_delete(x_id) {

        var r = confirm("Delete Reference?");
        if (r == false) {
            return false;
        }

        //Show loader to delete:
        $('#x_' + x_id).html('<img src="/img/round_load.gif" class="loader" /> Deleting... ');

        //Delete"
        $.post("/urls/delete_url", {

            x_id: x_id,
            can_edit:<?= ( $can_edit ? 1 : 0 ) ?>,

        }, function (data) {

            if (data.status) {

                $('#x_' + x_id).html(data.message);

                //Decrease counter:
                $('.li-urls-count').text((parseInt($('.li-urls-count').text())-1));

                //Remove the who bar:
                setTimeout(function () {
                    $('#x_' + x_id).fadeOut();
                }, 377);

            } else {
                //We had an error:
                $('#x_' + x_id).html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> Error: ' + data.message + '</span>');
            }

        });

    }

    function entity_load_more(page) {

        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();

        $.post("/entities/entity_load_more/<?= $inbound_u_id ?>/<?= $entities_per_page ?>/" + page, {
            can_edit:<?= ( $can_edit ? 1 : 0 ) ?>,
        }, function (data) {

            $('.load-more').remove();

            //Update UI to confirm with user:
            $(data).insertBefore('#new-outbound');

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();
        });

    }

    function add_section(section){
        $('#nav_add_'+section).remove();
        $('#tab'+section).removeClass('hidden');
        $('#nav_'+section).removeClass('hidden');
        $('#nav_'+section+' a').trigger( "click" );

        //Adjust main counter:
        var new_counter = parseInt($('.add-group').attr('add-counter'))-1;
        if(new_counter==0){
            //All items added, remove adder icon:
            $('.add-group').remove();
        } else {
            //Reduce counter:
            $('.add-group').attr('add-counter', new_counter);
        }

        //Focus on the input field:
        $('#tab'+section+' .form-control').focus();
    }
</script>



<div class="row">
    <div class="col-sm-6">


<?php
/*******************************
/*******************************
 * Entity & Components
 *******************************
 *******************************/


//Top/main entity
echo '<div id="entity-box" class="list-group">';
echo echo_u($entity, 1, $can_edit);
echo '</div>';



//Menu
echo '<ul id="topnav" class="nav nav-pills nav-pills-secondary">';

    //Go through all tabs and see wassup:
    $needs_adding = array();
    foreach ($tabs as $key => $tab) {

        echo '<li id="nav_'.$key.'" class="'.( $tab['item_count']>0 ? '' : 'hidden' ).'" item-id="'.$key.'"><a href="#'.$key.'"><i class="'.$tab['icon'].'"></i> <span class="li-'.$key.'-count">'.$tab['item_count'].'</span> '.$tab['title'].'</a></li>';

        if($tab['item_count']==0 && $tab['always_access']){
            //Add this so we can show it in the "add new section" drop down list:
            $needs_adding[$key] = $tab;
        }
    }

    if(count($needs_adding)>0){
        //Show an option to add:
        echo '<div class="btn-group add-group" style="margin:-5px 0 0 5px;" add-counter="'.count($needs_adding).'">
  <button type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: none; border: 0; font-size: 0.8em;">
    <i class="fas fa-plus-circle"></i> 
  </button>
  <ul class="dropdown-menu dropdown-menu-secondary">';

        foreach ($needs_adding as $key => $tab) {
            echo '<li id="nav_add_'.$key.'" class="add-new"><a href="#'.$key.'" onclick="add_section(\''.$key.'\');"><i class="'.$tab['icon'].'"></i> '.$tab['title'].'</a></li>';
        }
        echo '</ul></div>';
    }

echo '</ul>';

echo '<div class="tab-content tab-space">';

    echo '<div class="tab-pane '.( !$tabs['outbound']['item_count'] ? 'hidden' : '' ).'" id="taboutbound">';
    echo '<div id="list-outbound" class="list-group grey-list">';

        foreach ($child_entities as $u) {
            echo echo_u($u, 2, $can_edit_outbound);
        }

        if ($entity['u__outbound_count'] > count($child_entities)) {
            echo_next_u(1, $entities_per_page, $entity['u__outbound_count']);
        }

        //Input to add new inbounds:
        if ($can_edit_outbound) {
            echo '<div id="new-outbound" class="list-group-item list_input grey-input">
                    <div class="input-group">
                        <div class="form-group is-empty"><input type="text" class="form-control new-input" placeholder="Add '.$add_name.' by Name/URL"></div>
                        <span class="input-group-addon">
                            <a class="badge badge-secondary new-btn" href="javascript:add_u_link(0,'.$add_id.');">ADD</a>
                        </span>
                    </div>
                </div>';
        }

    echo '</div>';
    echo '</div>';










    echo '<div class="tab-pane  '.( !$tabs['inbound']['item_count'] ? 'hidden' : '' ).'" id="tabinbound">';
    echo '<div id="list-inbound" class="list-group  grey-list">';


    foreach ($entity['u__inbounds'] as $ur) {
        echo echo_u($ur, 2, $can_edit_inbound, true);
    }

    //Input to add new inbounds:
    if($can_edit_inbound) {
        echo '<div id="new-inbound" class="list-group-item list_input grey-input">
            <div class="input-group">
                <div class="form-group is-empty"><input type="text" class="form-control new-input" placeholder="Search People & Organizations to Link..."></div>
                <span class="input-group-addon">
                    <a class="badge badge-secondary new-btn" href="javascript:void(0);" onclick="alert(\'Note: Either choose an option from the suggestion menu to continue\')">ADD</a>
                </span>
            </div>
        </div>';
    }
    echo '</div>';
    echo '</div>';









    echo '<div class="tab-pane  '.( !$tabs['urls']['item_count'] ? 'hidden' : '' ).'" id="taburls">'; //Tab content starts
    echo '<div id="list-urls" class="list-group  grey-list">';
    foreach ($entity['u__urls'] as $x) {
        echo echo_x($entity, $x);
    }

    //Add new Reference:
    if ($can_edit) {
        echo '<div class="list-group-item list_input grey-input">
            <div class="input-group">
                <div class="form-group is-empty"><input type="url" class="form-control" id="add_url_input" placeholder="Paste URL here..."></div>
                <span class="input-group-addon">
                    <a class="badge badge-secondary" id="add_url_btn" href="javascript:add_new_url();">ADD</a>
                </span>
            </div>
        </div>';
    }
    echo '</div>';
    echo '</div>'; //Tab content ends













    echo '<div class="tab-pane  '.( !$tabs['training']['item_count'] ? 'hidden' : '' ).'" id="tabtraining">';
    echo '<div id="list-training" class="list-group  grey-list">';
    foreach ($b_team_member as $ba) {

    }
    echo '</div>';
    echo '</div>';
















    echo '<div class="tab-pane  '.( !$tabs['subscriptions']['item_count'] ? 'hidden' : '' ).'" id="tabsubscriptions">';
    echo '<div id="list-intents" class="list-group  grey-list">';
    foreach ($enrollments as $ru) {

    }
    echo '</div>';
    echo '</div>';















    echo '<div class="tab-pane  '.( !$tabs['payments']['item_count'] ? 'hidden' : '' ).'" id="tabpayments">';
    echo '<div id="list-payments" class="list-group  grey-list">';
    foreach ($payments as $t) {
        echo_t($t);
    }
    echo '</div>';
    echo '</div>';












    //Fetch the current messages that have referenced this content:
    echo '<div class="tab-pane  '.( !$tabs['messages']['item_count'] ? 'hidden' : '' ).'" id="tabmessages">';
    echo '<div id="list-messages" class="list-group  grey-list">';
    foreach($messages as $i){
        echo echo_i($i,$entity['u_full_name']);
    }
    echo '</div>';
    echo '</div>';









echo '</div>';
echo '</div>';
?>

  </div>

  <div class="col-sm-6">
  </div>

</div>