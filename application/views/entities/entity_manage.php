<?php

/*******************************
/*******************************
 * Fetch Data Components
 *******************************
 *******************************/

$entity_per_page = $this->config->item('entity_per_page');
$udata = $this->session->userdata('user');
$message_max = $this->config->item('message_max');

//Determine what type of entity is this? (Content, people or Organization)
$entity_type = entity_type($entity);

//This also gets passed to other pages via AJAX to be applied to u_echo() in ajax calls:
$can_edit           = ( $udata['u_id']==$entity['u_id'] || array_key_exists(1281, $udata['u__inbounds']));
$add_name           = ( in_array($entity['u_id'], array(1278,2750)) ? rtrim($entity['u_full_name'],'s') : 'Content' );
$add_id             = ( in_array($entity['u_id'], array(1278,2750)) ? $entity['u_id'] : 1326 /* Content */ );


//Fetch other data:
$child_entities = $this->Db_model->ur_outbound_fetch(array(
    'ur_inbound_u_id' => $inbound_u_id,
    'ur_status' => 1, //Only active
), array('u__outbound_count'), $entity_per_page);
$payments = $this->Db_model->t_fetch(array(
    't_inbound_u_id' => $inbound_u_id,
));
$enrollments = array();
$b_team_member = array();







/*******************************
/*******************************
 * Javascript Logic
 *******************************
 *******************************/
?>
<script>

    function initiate_outbound_search(){
        $("#new-outbound .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {

            ur_add(suggestion.u_id,0,0);

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
                    //If clicked, would trigger the autocomplete:selected above which will trigger the ur_add() function
                    return '<span><i class="fas fa-at"></i></span> ' + suggestion.u_full_name;
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:ur_add(0,<?= $add_id ?>,0)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as <?= $add_name ?>]</a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:ur_add(0,<?= $add_id ?>,0)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create</span> <i class="fas fa-at"></i> ' + data.query + ' [as <?= $add_name ?>]</a>';
                },
            }
        }]).keypress(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if ((code == 13) || (e.ctrlKey && code == 13)) {
                ur_add(0,<?= $add_id ?>);
                return true;
            }
        });
    }


    var u_status_filter = -1; //No filter, show all!

    $(document).ready(function () {

        if(is_mobile()){

            //Adjust columns:
            $('.cols').removeClass('col-xs-6').addClass('col-sm-6');
            $('.grey-box').addClass('phone-2nd');

        } else {

            //Adjust height of the messaging windows:
            $('.grey-box').css('max-height', (parseInt($( window ).height())-120)+'px');

            //Make editing frames Sticky for scrolling longer lists
            $(".main-panel").scroll(function() {
                var top_position = $(this).scrollTop();
                clearTimeout($.data(this, 'scrollTimer'));
                $.data(this, 'scrollTimer', setTimeout(function() {
                    $(".grey-box").css('top',(top_position-0)); //PX also set in style.css for initial load
                }, 34));
            });
        }


        //Do we need to auto load anything?
        if(window.location.hash) {
            var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
            var hash_parts = hash.split("-");
            if(hash_parts.length>=2){
                //Fetch level if available:
                if(hash_parts[0]=='messages'){
                    load_u_messages(hash_parts[1]);
                } else if(hash_parts[0]=='modify'){
                    load_u_modify(hash_parts[1],hash_parts[2]);
                } else if(hash_parts[0]=='status'){
                    //Update status:
                    filter_u_status(hash_parts[1]);
                }
            }
        }




        //Watch for URL adding:
        $('#add_url_input').keydown(function (event) {
            if ((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey) {
                x_add();
                event.preventDefault();
                return false;
            }
        });


        //Loadup various search bars:
        initiate_outbound_search();



        $("#new-inbound .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {
            ur_add(suggestion.u_id,0,1);
        }).autocomplete({hint: false, keyboardShortcuts: ['a']}, [{
            source: function (q, cb) {
                algolia_u_index.search(q, {
                    hitsPerPage: 7,
                    //tagFilters:[['u2750','u1278','u2738','u3000']] //Only search People & Organizations & Entity Types
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
                    //If clicked, would trigger the autocomplete:selected above which will trigger the ur_add() function
                    return '<span><i class="fas fa-at"></i></span> ' + suggestion.u_full_name;
                },

                <?php if($entity_type==1326){ //Suggest both People AND Organizations as new inbound entities: ?>
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:ur_add(0,1278,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as People]</a><a href="javascript:ur_add(0,2750,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as Organization]</a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:ur_add(0,1278,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as People]</a><a href="javascript:ur_add(0,2750,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as Organization]</a>';
                },
                <?php } ?>
            }
        }]);



    });

    //Adds OR links authors and content for entities
    function ur_add(new_u_id,secondary_parent_u_id=0, is_inbound) {

        //if new_u_id>0 it means we're linking to an existing entity, in which case new_u_input should be null
        //If new_u_id=0 it means we are creating a new entity and then linking it, in which case new_u_input is required

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


    function update_u_status(u_id, new_u_status){

        //Indicate loading:
        $('.u-status-bar-'+u_id).html('<img src="/img/round_load.gif" class="loader" />');

        //Will update the status of u_id to new status
        $.post("/entities/update_u_status", {

            u_id:u_id,
            new_u_status: new_u_status,

        }, function (data) {
            if (data.status) {

                //Show data:
                $('.u-status-bar-'+u_id).html(data.message);

                //Adjust counters:
                $('.count-u-status-'+new_u_status).text((parseInt($('.count-u-status-'+new_u_status).text())+1));
                $('.count-u-status-'+data.old_status).text((parseInt($('.count-u-status-'+data.old_status).text())-1));
                //TODO maybe the new counter element does not exist! Handle this case later...

                if(u_status_filter>=0 && !(new_u_status==u_status_filter)){
                    //We have the filter on and it does not match the new status, so hide this:
                    setTimeout(function () {
                        $('#u_'+u_id).fadeOut();
                    }, 377);
                } else {
                    //Update status:
                    $('#u_'+u_id).attr('entity-status',new_u_status);
                }

                //Tooltips:
                $('[data-toggle="tooltip"]').tooltip();

            } else {
                //We had an error:
                $('.u-status-bar-'+u_id).html('<span style="color:#FF0000;">Error: '+data.message+'</span>');
            }
        });


    }

    function filter_u_status(new_val){
        if(new_val==-1 || new_val==0 || new_val==1 || new_val==2) {
            //Remove active class:
            $('.u-status-filter').removeClass('btn-secondary');
            //We do have a filter:
            u_status_filter = parseInt(new_val);
            $('.u-status-'+new_val).addClass('btn-secondary');
            entity_load_more(0,1);
        } else {
            alert('Invalid new status');
            return false;
        }
    }



    //Count text area characters:
    function changeBio() {
        var len = $('#u_bio').val().length;
        if (len > <?= $message_max ?>) {
            $('#charNum').addClass('overload').text(len);
        } else {
            $('#charNum').removeClass('overload').text(len);
        }
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


    function x_add() {

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
            $('#add_url_btn').attr('href', 'javascript:x_add();').html('ADD');
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

    function entity_load_more(page,load_new_filter=0) {

        if(load_new_filter){
            //Replace load more with spinner:
            var append_div = $('#new-outbound').html();
            $('#list-outbound').html('<span class="load-more"><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
        } else {
            //Replace load more with spinner:
            $('.load-more').html('<span class="load-more"><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
        }

        $.post("/entities/entity_load_more", {
            can_edit:<?= ( $can_edit ? 1 : 0 ) ?>,
            page:page,
            inbound_u_id:<?= $inbound_u_id ?>,
            u_status_filter:u_status_filter,
        }, function(data) {

            //Appending to existing content:
            $('.load-more').remove();

            if(load_new_filter){
                $('#list-outbound').html( data + '<div id="new-outbound" class="list-group-item list_input grey-input">'+append_div+'</div>' ).hide().fadeIn();
                //Reset search engine:
                initiate_outbound_search();
            } else {
                //Update UI to confirm with user:
                $(data).insertBefore('#new-outbound');
            }

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();
        });

    }

    function ur_unlink(){

        var ur_id = ( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('entity-link-id')) );
        var u_level1_name = $('.top_entity .u_full_name').text();
        var u_level2_name = $('.ur_'+ur_id+' .u_full_name').text();
        var direction = ( parseInt($('.ur_'+ur_id).attr('is-inbound'))==1 ? 'inbound' : 'outbound' );
        var counter_class = '.li-'+direction+'-count';
        var current_status = parseInt($('.ur_'+ur_id).attr('entity-status'));

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
                $('.count-u-status-'+current_status).text((parseInt($('.count-u-status-'+current_status).text())-1));

            } else {
                //There was an error, show to user:
                $('.ur_'+ur_id).html('<b style="color:#FF0000 !important;">Error: '+data.message+'</b>');
            }

        });
    }

    function u_delete(){
        var r = confirm("Are you sure you want to PERMANENTLY delete this entity and all its associated URLs, Messages, etc...?");
        if (!(r == true)) {
            return false;
        }
        var u_id = ( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('entity-id')) );
        window.location = "/entities/hard_delete/"+u_id;
    }


    function load_u_modify(u_id, ur_id){

        //Make sure inputs are valid:
        if(!$('.u__'+u_id).length){
            alert('Entity not found');
            return false;
        }

        //Update variables:
        $('.save_entity_changes').html('');
        $('#modifybox').attr('entity-link-id',ur_id);
        $('#modifybox').attr('entity-id',u_id);

        $('#u_full_name').val($(".u_full_name_"+u_id+":first").text());
        $('#u_bio').val($(".u__"+u_id+":first").attr('entity-bio'));
        changeBio();

        //Update password reset UI:
        $('#u_email').val($(".u__"+u_id+":first").attr('entity-email'));
        $('.changepass').hide();
        $('.changepass_a').show().text(( parseInt($(".u__"+u_id+":first").attr('has-password')) && $(".u__"+u_id+":first").attr('entity-email').length>0 ? 'Update Login Credentials' : 'Setup Login Credentials'));

        //Only show unlink button if not level 1
        if(parseInt(ur_id)>0){
            $('.unlink-entity').removeClass('hidden');
        } else {
            $('.unlink-entity').addClass('hidden');
        }

        //Make the frame visible:
        $('.grey-box').addClass('hidden');
        $("#modifybox").removeClass('hidden').hide().fadeIn();
    }


    function save_u_modify(){

        //Validate that we have all we need:
        if($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('entity-id'))) {
            //Oops, this should not happen!
            return false;
        }

        //Prepare data to be modified for this intent:
        var modify_data = {
            u_id:parseInt($('#modifybox').attr('entity-id')),
            u_full_name:$('#u_full_name').val(),
            u_bio:$('#u_bio').val(),
            u_email:$('#u_email').val(),
            u_password_new:$('#u_password_new').val(),
        };

        //Show spinner:
        $('.save_entity_changes').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();


        $.post("/entities/u_save_settings", modify_data, function(data) {

            if(data.status){

                //Update variables:
                $(".u_full_name_"+modify_data['u_id']).text(modify_data['u_full_name']);
                $(".u__"+modify_data['u_id']).attr('entity-bio', modify_data['u_bio']);
                $(".u__"+modify_data['u_id']).attr('entity-email', modify_data['u_email']);
                $(".u__"+modify_data['u_id']).attr('has-password', ( modify_data['u_password_new'].length>0 ? 1 : 0 ));
                if($('.u_bio_'+modify_data['u_id']).length){
                    //This is the top entity that's loaded, simply update:
                    $(".u_bio_"+modify_data['u_id']).text(modify_data['u_bio']);
                } else {
                    //This is a level 2 item, let's update the UI accordingly:
                    if(modify_data['u_bio'].length>0){
                        $(".u_full_name_"+modify_data['u_id']).addClass('has-desc').attr('data-toggle', 'tooltip').attr('data-original-title', modify_data['u_bio']);
                    } else {
                        $(".u_full_name_"+modify_data['u_id']).removeClass('has-desc').attr('data-toggle', '').attr('data-original-title', '');
                    }
                }

                //Reload Tooltip again:
                $('[data-toggle="tooltip"]').tooltip();

                //Update UI to confirm with user:
                $('.save_entity_changes').html(data.message).hide().fadeIn();

                //Disapper in a while:
                setTimeout(function() {
                    //Hide the editor & saving results:
                    $('.save_entity_changes').hide();
                }, 1000);

            } else {
                //Ooops there was an error!
                $('.save_entity_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> '+data.message+'</span>').hide().fadeIn();
            }

        });

    }

    function load_u_messages(u_id){

        //Make the frame visible:
        $('.grey-box').addClass('hidden');
        $("#message-frame").removeClass('hidden').hide().fadeIn().attr('entity-id',u_id);
        $("#message-frame h4").text($(".u_full_name_"+u_id+":first").text());

        var handler = $( "#loaded-messages" );

        //Show tem loader:
        handler.html('<div style="text-align:center; padding:10px 0 50px;"><img src="/img/round_load.gif" class="loader" /></div>');

        //Load the frame:
        $.post("/entities/load_messages", { u_id:u_id }, function(data) {
            //Empty Inputs Fields if success:
            handler.html(data);

            //Show inner tooltips:
            $('[data-toggle="tooltip"]').tooltip();
        });

    }

</script>



<div class="row">
    <div class="col-xs-6 cols">


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




//URLs
if(!in_array($entity['u_id'], array(2738,1278,1326,2750))){
    echo '<h5 class="badge badge-secondary"><i class="fas fa-link"></i> <span class="li-urls-count">'.count($entity['u__urls']).'</span> URLs</h5>';
    echo '<div id="list-urls" class="list-group  grey-list">';
    foreach ($entity['u__urls'] as $x) {
        echo echo_x($entity, $x);
    }

    //Add new Reference:
    if ($can_edit) {
        echo '<div class="list-group-item list_input grey-input">
            <div class="input-group">
                <div class="form-group is-empty"><input type="url" class="form-control" data-lpignore="true" id="add_url_input" placeholder="Paste URL here..."></div>
                <span class="input-group-addon">
                    <a class="badge badge-secondary" id="add_url_btn" href="javascript:x_add();">ADD</a>
                </span>
            </div>
        </div>';
    }
    echo '</div>';
}



//Inbounds
if($entity['u_id']!=2738){
    echo '<h5 class="badge badge-secondary"><i class="fas fa-sign-in-alt"></i> <span class="li-inbound-count">'.count($entity['u__inbounds']).'</span> Ins</h5>';
    echo '<div id="list-inbound" class="list-group  grey-list">';
    foreach ($entity['u__inbounds'] as $ur) {
        echo echo_u($ur, 2, $can_edit, true);
    }
    //Input to add new inbounds:
    if($can_edit) {
        echo '<div id="new-inbound" class="list-group-item list_input grey-input">
            <div class="input-group">
                <div class="form-group is-empty"><input type="text" class="form-control new-input" data-lpignore="true" placeholder="Add Entity..."></div>
                <span class="input-group-addon">
                    <a class="badge badge-secondary new-btn" href="javascript:void(0);" onclick="alert(\'Note: Either choose an option from the suggestion menu to continue\')">ADD</a>
                </span>
            </div>
        </div>';
    }
    echo '</div>';
}


//Outbounds
echo '<table width="100%" style="margin-top:10px;"><tr>';
echo '<td style="width: 100px;"><h5 class="badge badge-secondary"><i class="fas fa-sign-out-alt rotate90"></i> <span class="li-outbound-count">'.$entity['u__outbound_count'].'</span> Outs</h5></td>';
echo '<td style="text-align: right;"><div class="btn-group btn-group-sm" style="margin-top:-5px;" role="group">';

    //Fetch current count for each status from DB:
    $counts = $this->Db_model->ur_outbound_fetch(array(
        'ur_inbound_u_id' => $inbound_u_id,
        'ur_status' => 1, //Only active
        'u_status >=' => 0,
    ), array(), 0, 0, 'COUNT(u_id) as u_counts, u_status', 'u_status', array(
        'u.u_status' => 'ASC',
    ));

    //Only show filtering UI if we find entities with multiple statuses
    if(count($counts)>0 && $counts[0]['u_counts']<$entity['u__outbound_count']){

        //Load status definitions:
        $status_index = $this->config->item('object_statuses');

        //Show fixed All button:
        echo '<a href="javascript:void(0)" onclick="filter_u_status(-1)" class="btn btn-default btn-secondary u-status-filter u-status--1" data-toggle="tooltip" data-placement="top" title="View all entities"><i class="fas fa-at"></i> All [<span class="li-outbound-count">'.$entity['u__outbound_count'].'</span>]</a>';

        //Show each specific filter based on DB counts:
        foreach($counts as $c_c){
            $st = $status_index['u'][$c_c['u_status']];
            echo '<a href="#status-'.$c_c['u_status'].'" onclick="filter_u_status('.$c_c['u_status'].')" class="btn btn-default u-status-filter u-status-'.$c_c['u_status'].'" data-toggle="tooltip" data-placement="top" title="'.$st['s_desc'].'"><i class="'.$st['s_icon'].'"></i> '.$st['s_name'].' [<span class="count-u-status-'.$c_c['u_status'].'">'.$c_c['u_counts'].'</span>]</a>';
        }

    }

    echo '</div></td>';
echo '</tr></table>';



echo '<div id="list-outbound" class="list-group grey-list">';

foreach($child_entities as $u){
    echo echo_u($u, 2, $can_edit);
}
if($entity['u__outbound_count'] > count($child_entities)) {
    echo_next_u(1, $entity_per_page, $entity['u__outbound_count']);
}

//Input to add new inbounds:
if($can_edit){
    echo '<div id="new-outbound" class="list-group-item list_input grey-input">
        <div class="input-group">
            <div class="form-group is-empty"><input type="text" class="form-control new-input" data-lpignore="true" placeholder="Add '.$add_name.' by Name/URL"></div>
            <span class="input-group-addon">
                <a class="badge badge-secondary new-btn" href="javascript:ur_add(0,'.$add_id.', 0);">ADD</a>
            </span>
        </div>
    </div>';
}

echo '</div>';



//Only show if data exists (users cannot modify this anyways)
if(count($enrollments)>0){
    echo '<h5 class="badge badge-secondary"><i class="fas fa-comment-plus"></i> <span class="li-subscriptions-count">'.count($enrollments).'</span> Subscriptions</h5>';
    echo '<div id="list-intents" class="list-group  grey-list">';
    foreach ($enrollments as $ru) {

    }
    echo '</div>';
}



//Only show if data exists (users cannot modify this anyways)
if(count($b_team_member)>0){
    echo '<h5 class="badge badge-secondary"><i class="fas fa-whistle"></i> <span class="li-training-count">'.count($b_team_member).'</span> Training</h5>';
    echo '<div id="list-training" class="list-group  grey-list">';
    foreach ($b_team_member as $ba) {

    }
    echo '</div>';
}




//Only show if data exists (users cannot modify this anyways)
if(count($payments)>0){
    echo '<h5 class="badge badge-secondary"><i class="fab fa-paypal"></i> <span class="li-payments-count">'.count($payments).'</span> Payments</h5>';
    echo '<div id="list-payments" class="list-group  grey-list">';
    foreach ($payments as $t) {
        echo_t($t);
    }
    echo '</div>';
}

?>

</div>

    <div class="col-xs-6 cols">


      <div id="modifybox" class="grey-box hidden" entity-id="0" entity-link-id="0">

          <div style="text-align:right; font-size: 22px; margin: -5px 0 -20px 0;"><a href="javascript:void(0)" onclick="$('#modifybox').addClass('hidden')"><i class="fas fa-times"></i></a></div>


          <div class="title" style="margin-bottom:0; padding-bottom:0;"><h4><i class="fas fa-fingerprint"></i> Name</h4></div>
          <input type="text" id="u_full_name" value="" data-lpignore="true" placeholder="Name" class="form-control border">


          <div class="title" style="margin-top:15px;"><h4><i class="fas fa-file-alt"></i> Overview [<span style="margin:0 0 10px 0; font-size:0.8em;"><span id="charNum">0</span>/<?= $message_max ?></span>]</h4></div>
          <textarea class="form-control text-edit border msg" id="u_bio" style="height:85px; background-color:#FFFFFF !important;" onkeyup="changeBio()"></textarea>


          <!-- Password credential management -->
          <div style="margin-top:15px; display:<?= (array_key_exists(1281, $udata['u__inbounds']) ? 'block' : 'none') ?>;"><a href="javascript:$('.changepass').toggle();" class="changepass changepass_a" data-toggle="tooltip" title="Manage email/password that enables this entity to login" data-placement="top" style="text-decoration:none;"></a></div>
          <div class="changepass" style="display:none;">
              <div class="title" style="margin-top:15px;"><h4><i class="fas fa-envelope"></i> Email</h4></div>
              <input type="email" id="u_email" data-lpignore="true" style="max-width:260px;" value="" data-lpignore="true" class="form-control border">
          </div>
          <div class="changepass" style="display:none;">
              <div class="title" style="margin-top:15px;"><h4><i class="fas fa-asterisk"></i> Set New Password</h4></div>
              <div class="form-group label-floating is-empty">
                  <input type="password" id="u_password_new" style="max-width:260px;" autocomplete="new-password" data-lpignore="true" class="form-control border">
                  <span class="material-input"></span>
              </div>
          </div>


          <table width="100%" style="margin-top:10px;">
              <tr>
                  <td class="save-td"><a href="javascript:save_u_modify();" class="btn btn-secondary">Save</a></td>
                  <td><span class="save_entity_changes"></span></td>
                  <td style="width:100px; text-align:right;">

                      <div class="unlink-entity"><a href="javascript:ur_unlink();" data-toggle="tooltip" title="Only remove entity link while NOT deleting the entity itself" data-placement="left" style="text-decoration:none;"><i class="fas fa-unlink"></i> Unlink</a></div>

                      <?php if(array_key_exists(1281, $udata['u__inbounds'])){ ?>
                          <div><a href="javascript:u_delete();" data-toggle="tooltip" title="Delete entity AND remove all its URLs, messages & references" data-placement="left" style="text-decoration:none;"><i class="fas fa-trash-alt"></i> Delete</a></div>
                      <?php } ?>

                  </td>
              </tr>
          </table>
      </div>



      <div id="message-frame" class="grey-box hidden" entity-id="">

          <div style="text-align:right; font-size: 22px; margin: -5px 0 -20px 0;"><a href="javascript:void(0)" onclick="$('#message-frame').addClass('hidden')"><i class="fas fa-times"></i></a></div>
          <h4 style="width:92%; line-height: 110%;"></h4>

          <div id="loaded-messages" style="margin-top:20px;"></div>
      </div>


  </div>

</div>