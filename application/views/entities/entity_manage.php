<?php
$udata = $this->session->userdata('user');
$can_edit = ($udata['u_id']==$entity['u_id'] || $udata['u_inbound_u_id']==1281);

if(!$inbound_u_id){

    //Show help tip for Entities:
    echo '<div class="help_body below_h maxout" id="content_6776"></div>';

    //Simply show the top level entities:
    echo '<div id="list-entities" class="list-group maxout grey-list">';
    foreach($child_entities as $u){
        echo echo_u($u);
    }
    echo '</div>';

} else {


    ?>
    <script>

        $(document).ready(function () {
            //Detect any possible hashes that controll the menu?
            if (window.location.hash) {
                focus_hash(window.location.hash);
            } else {
                //Mark the first item as active:
                $('#topnav li').first().addClass('active');
                $('.tab-pane').first().addClass('active');
            }

            //Watch for Reference adding:
            $('#add_url_input').keydown(function (event) {
                if ((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey) {
                    add_new_url();
                    event.preventDefault();
                    return false;
                }
            });


            $("#add_inbound_input").on('autocomplete:selected', function (event, suggestion, dataset) {

                add_u_link(suggestion.u_id, 1);

            }).autocomplete({hint: false, keyboardShortcuts: ['a']}, [{

                source: function (q, cb) {
                    algolia_u_index.search(q, {
                        hitsPerPage: 7,
                        filters: '(u_inbound_u_id=1280 OR u_inbound_u_id=1279 OR u_inbound_u_id=1307 OR u_inbound_u_id=1281 OR u_inbound_u_id=1308 OR u_inbound_u_id=1304 OR u_inbound_u_id=1282)',
                    }, function (error, content) {
                        if (error) {
                            cb([]);
                            return;
                        }
                        cb(content.hits, content);
                    });
                },
                displayKey: function (suggestion) {
                    return ""
                },
                templates: {
                    suggestion: function (suggestion) {
                        //If clicked, would trigger the autocomplete:selected above which will trigger the add_u_link() function
                        return '<span><i class="fas fa-at"></i></span> ' + suggestion.u_full_name + ' (' + suggestion.u_inbound_name + ')';
                    },
                    header: function (data) {
                        if (!data.isEmpty) {
                            return '<a href="javascript:add_u_link(0,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create</span> "' + data.query + '"' + ' (Referenced Auhtors)</a>';
                        }
                    },
                    empty: function (data) {
                        return '<a href="javascript:add_u_link(0,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create</span> "' + data.query + '"' + ' (Referenced Auhtors)</a>';
                    },
                }
            }]).keypress(function (e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                if ((code == 13) || (e.ctrlKey && code == 13)) {
                    add_u_link(0, 1);
                    return true;
                }
            });
        });

        //Adds OR links authors and content for entities
        function add_u_link(new_u_id, is_inbound) {

            //if new_u_id>0 it means we're linking to an existing entity, in which case new_u_full_name should be null
            //If new_u_id=0 it means we are creating a new entity and then linking it, in which case new_u_full_name is required

            var new_u_full_name = null;
            if (new_u_id==0) {
                new_u_full_name = $("#add_inbound_input").val();
                if(new_u_full_name.length<1){
                    alert('ERROR: Missing entity name, try again');
                    return false;
                }
            }

            if(is_inbound){
                var input = $('#add_inbound_input');
                var btn = $('#add_inbound_btn');
                var list_id = 'list-inbound';
            } else {
                var input = $('#add_outbound_input');
                var btn = $('#add_outbound_btn');
                var list_id = 'list-outbound';
            }


            //Adjust UI to indicating loading...
            var current_href = btn.attr('href');
            input.prop('disabled', true); //Empty input
            btn.attr('href', 'javascript:void(0);').html('<i class="fas fa-spinner fa-spin"></i>');


            //Add via Ajax:
            $.post("/entities/link_entities", {

                u_id:<?= $entity['u_id'] ?>,
                new_u_id: new_u_id,
                new_u_full_name: new_u_full_name,
                is_inbound:is_inbound,

            }, function (data) {

                //Release lock:
                input.prop('disabled', false);
                btn.attr('href', current_href).html('ADD');

                if (data.status) {

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
            $('#x_' + x_id + ' .add-cover').addClass('hidden').after('<span class="badge badge-primary grey cover-load"><i class="fas fa-spinner fa-spin"></i></span>');

            //Add cover photo:
            $.post("/urls/set_cover", {x_id: x_id}, function (data) {

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

                    //Tooltips:
                    $('[data-toggle="tooltip"]').tooltip();

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

            }, function (data) {

                if (data.status) {

                    $('#x_' + x_id).html(data.message);

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

            //Show spinner:
            $('.load-more').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();

            $.post("/entities/entity_load_more/<?= $inbound_u_id ?>/<?= $entities_per_page ?>/" + page, {}, function (data) {
                $('.load-more').remove();
                //Update UI to confirm with user:
                $('#list-entities').append(data);

                //Tooltips:
                $('[data-toggle="tooltip"]').tooltip();
            });

        }
    </script>


    <?php

//Show info box for this item:
    echo '<div id="entity-box" class="list-group maxout">';
    echo '<div id="u_' . $entity['u_id'] . '" entity-id="' . $entity['u_id'] . '" class="list-group-item">';

//Right content:
    echo '<span class="pull-right">';
    echo echo_score($entity['u__e_score']);

    if ($can_edit) {
        echo '<a class="badge badge-primary" href="/entities/' . $entity['u_id'] . '/modify" style="margin:-2px 3px 0 0;"><i class="fas fa-cog"></i></a>';
    }

    if(isset($entity['u__outbound_count']) && $entity['u__outbound_count']>0){
        echo '<span class="badge badge-primary grey" data-toggle="tooltip" data-placement="left" title="" data-original-title="Entity tree contains '.$entity['u__outbound_count'].' child entities. See list below." style="width:40px;"><span class="btn-counter">'.echo_big_num($entity['u__outbound_count']).'</span><i class="fas fa-chevron-right"></i></span>';
    }

    echo '</span>';

//Regular section:
    echo echo_cover($entity, 'profile-icon2');
    echo '<b id="u_title">' . $entity['u_full_name'] . '</b>';
    echo ' <span class="obj-id">@' . $entity['u_id'] . '</span>';

//Do they have any social profiles in their link?
    echo echo_social_profiles($this->Db_model->x_social_fetch($entity['u_id']));

//Check last engagement ONLY IF admin:
    if ($can_edit) {

        //Check last engagement:
        $last_eng = $this->Db_model->e_fetch(array(
            '(e_inbound_u_id=' . $entity['u_id'] . ')' => null,
        ), 1);

        if (count($last_eng) > 0) {
            echo ' &nbsp;<a href="/cockpit/browse/engagements?e_u_id=' . $entity['u_id'] . '" style="display: inline-block;" data-toggle="tooltip" data-placement="right" title="Last engaged ' . echo_diff_time($last_eng[0]['e_timestamp']) . ' ago. Click to see all engagements"><i class="fas fa-exchange rotate45"></i> <b>' . echo_diff_time($last_eng[0]['e_timestamp']) . ' &raquo;</b></a>';
        }
    }

    echo '<div>' . $entity['u_bio'] . '</div>';

    echo '</div>';
    echo '</div>';


//Start fetching related objects:
    $enrollments = $this->Db_model->ru_fetch(array(
        'ru_outbound_u_id' => $inbound_u_id,
        'ru_status >=' => 4, //Enrolled
    ), array(
        'ru.ru_id' => 'DESC',
    ), array('b'));

    $b_team_member = $this->Db_model->ba_fetch(array(
        'ba.ba_outbound_u_id' => $inbound_u_id,
    ), array('b'));

    $payments = $this->Db_model->t_fetch(array(
        't_inbound_u_id' => $inbound_u_id,
    ));



    $inbound_us = $this->Db_model->ur_inbound_fetch(array(
        'ur_outbound_u_id' => $inbound_u_id,
    ));

    $outbound_us = $this->Db_model->ur_outbound_fetch(array(
        'ur_inbound_u_id' => $inbound_u_id,
    ));



    $show_tabs = array(
        'list' => (count($child_entities) > 0 || $entity['u_id'] == 1326 ? '<li id="nav_list"><a href="#list"><i class="fas fa-at"></i> List</a></li>' : false),
        'urls' => ( !in_array($entity['u_inbound_u_id'], array(0, 1278)) ? '<li id="nav_urls"><a href="#urls"><i class="fas fa-link"></i> URLs</a></li>' : false),
        'inbound' => '<li id="nav_inbound"><a href="#inbound"><i class="fas fa-sign-in-alt rotate90"></i> In</a></li>',
        'outbound' => '<li id="nav_outbound"><a href="#outbound"><i class="fas fa-sign-out-alt rotate90"></i> Out</a></li>',
        'intents' => '<li id="nav_intents"><a href="#intents"><i class="fas fa-hashtag"></i> Intents</a></li>',
        'training' => ( count($b_team_member)>0 ? '<li id="nav_training"><a href="#training"><i class="fas fa-whistle"></i> training</a></li>' : false),
        'messages' => ( $entity['u_inbound_u_id'] == 1326 ? '<li id="nav_messages"><a href="#messages"><i class="fas fa-comment-dots"></i> Messages</a></li>' : false),
        'payments' => (count($payments) > 0 && auth(array(1281), 0, 0, $entity['u_id']) ? '<li id="nav_payments"><a href="#payments"><i class="fab fa-paypal"></i> Payments</a></li>' : false),

    );
    ?>


    <ul id="topnav" class="nav nav-pills nav-pills-primary">
        <?php
        //FYI First List item would get the "active" CSS Class appended with JS in code above

        //Go through all tabs and see wassup:
        foreach ($show_tabs as $key => $tab_header) {
            //Will echo list item IF exists:
            echo $tab_header;
        }
        ?>
    </ul>

    <?php

    echo '<div class="tab-content tab-space">';
    foreach ($show_tabs as $item => $is_available) {

        if (!$is_available) {
            continue;
        }

        if ($item == 'list') {

            echo '<div class="tab-pane" id="tablist">';

            echo '<div id="list-entities" class="list-group maxout grey-list">';
            foreach ($child_entities as $u) {
                echo echo_u($u);
            }

            if ($entity['u__outbound_count'] > count($child_entities)) {
                echo_next_u(1, $entities_per_page, $entity['u__outbound_count']);
            }

            if ($can_edit && $entity['u_id'] == 1326) {
                ?>
                <script>

                    function add_source_by_url() {

                        if ($('#url_for_source').val().length < 1) {
                            //Empty field!
                            alert('Error: Input field is empty. Paste a URL and then click "Add"');
                            $('#url_for_source').focus();
                            return false;
                        }

                        //Let's try adding:
                        $('#url_for_source').prop('disabled', true); //Empty input
                        $('#add_source_url_btn').attr('href', 'javascript:void(0);').html('<i class="fas fa-spinner fa-spin"></i>');

                        $.post("/urls/add_url", {

                            x_outbound_u_id: <?= $entity['u_id'] ?>, //We will create a new entity for this URL
                            x_url: $('#url_for_source').val(),

                        }, function (data) {

                            //Release lock:
                            $('#url_for_source').prop('disabled', false);
                            $('#add_source_url_btn').attr('href', 'javascript:add_source_by_url();').html('ADD');

                            if (data.status) {

                                //Empty input to make it ready for next URL:
                                $('#url_for_source').val('');

                                //Add new object to list:
                                add_to_list('list-entities', '.u-item', data.new_u);

                                //Tooltips:
                                $('[data-toggle="tooltip"]').tooltip();

                            } else {
                                //We had an error:
                                alert('Error: ' + data.message);
                            }

                        });

                    }

                    //Watch for Ctrl+Enter add
                    $(document).ready(function () {
                        $('#url_for_source').keydown(function (event) {
                            if ((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey) {
                                add_source_by_url();
                                event.preventDefault();
                                return false;
                            }
                        });
                    });

                </script>

                <div class="list-group-item list_input grey-input">
                    <div class="input-group">
                        <div class="form-group is-empty"><input type="url" class="form-control" id="url_for_source"
                                                                placeholder="Paste URL here..."></div>
                        <span class="input-group-addon">
                            <a class="badge badge-primary" id="add_source_url_btn"
                               href="javascript:add_source_by_url();">ADD</a>
                        </span>
                    </div>
                </div>

                <?php
            } elseif ($can_edit && $entity['u_id'] == 1278 && 0) {
                ?>
                <script>
                    function add_person_by_email() {

                    }
                </script>
                <div class="list-group-item list_input grey-input">
                    <div class="input-group">
                        <div class="form-group is-empty"><input type="email" class="form-control" id="email_for_user"
                                                                placeholder="newuser@email.com"></div>
                        <span class="input-group-addon">
                            <a class="badge badge-primary" onclick="add_person()" href="javascript:void(0);">ADD</a>
                        </span>
                    </div>
                </div>
                <?php
            }

            echo '</div>';
            echo '</div>';

        } elseif ($item == 'urls') {

            echo '<div class="tab-pane" id="taburls">'; //Tab content starts

            //Fetch all the URLs for this Entity:
            $urls = $this->Db_model->x_fetch(array(
                'x_status >' => 0,
                'x_outbound_u_id' => $entity['u_id'],
            ), array(), array(
                'x_id' => 'ASC'
            ));

            //URL List:
            echo '<div id="list-urls" class="list-group maxout grey-list">';

            if (count($urls) > 0) {
                foreach ($urls as $x) {
                    echo echo_x($entity, $x);
                }
            } else {
                echo '<div class="list-group-item alert alert-info no-b-div-1" style="padding: 15px 10px;"><i class="fas fa-exclamation-triangle" style="margin:0 8px 0 2px;"></i> No URLs added yet</div>';
            }


            //Add new Reference:
            if ($can_edit) {
                echo '<div class="list-group-item list_input grey-input">
                    <div class="input-group">
                        <div class="form-group is-empty"><input type="url" class="form-control" id="add_url_input" placeholder="Paste URL here..."></div>
                        <span class="input-group-addon">
                            <a class="badge badge-primary" id="add_url_btn" href="javascript:add_new_url();">ADD</a>
                        </span>
                    </div>
                </div>';
            }


            echo '</div>';


            echo '</div>'; //Tab content ends
        } elseif ($item == 'training') {

            echo '<div class="tab-pane" id="tabtraining">';
            echo '<div id="list-training" class="list-group maxout grey-list">';
            foreach ($b_team_member as $ba) {
                echo_ba($ba);
            }
            echo '</div>';
            echo '</div>';

        } elseif ($item == 'intents') {

            echo '<div class="tab-pane" id="tabintents">';
            echo '<div id="list-intents" class="list-group maxout grey-list">';
            foreach ($enrollments as $ru) {
                echo_ru($ru);
            }
            echo '</div>';
            echo '</div>';

        } elseif ($item == 'payments') {

            echo '<div class="tab-pane" id="tabpayments">';
            echo '<div id="list-payments" class="list-group maxout grey-list">';
            foreach ($payments as $t) {
                echo_t($t);
            }
            echo '</div>';
            echo '</div>';

        } elseif ($item == 'inbound') {

            echo '<div class="tab-pane" id="tabinbound">';
            echo '<div id="list-inbound" class="list-group maxout grey-list">';


            if (count($inbound_us)>0) {
                foreach ($inbound_us as $ur) {
                    echo echo_u($ur);
                }
            } else {
                echo '<div class="list-group-item alert alert-info no-b-div-1" style="padding: 15px 10px;"><i class="fas fa-exclamation-triangle" style="margin:0 8px 0 2px;"></i> No inbound entities linked yet</div>';
            }

            //Input to add new inbounds:
            if ($can_edit) {
                echo '<div class="list-group-item list_input grey-input">
                                    <div class="input-group">
                                        <div class="form-group is-empty"><input type="text" class="form-control" id="add_inbound_input" placeholder="Add Inbound..."></div>
                                        <span class="input-group-addon">
                                            <a class="badge badge-primary" id="add_inbound_btn" href="javascript:add_u_link(0, 1);">ADD</a>
                                        </span>
                                    </div>
                                </div>';
            }

            echo '</div>';
            echo '</div>';

        } elseif ($item == 'outbound') {

            echo '<div class="tab-pane" id="taboutbound">';
            echo '<div id="list-outbound" class="list-group maxout grey-list">';

            if (count($outbound_us)>0) {
                foreach ($outbound_us as $ur) {
                    echo echo_u($ur);
                }
            } else {
                echo '<div class="list-group-item alert alert-info no-b-div-1" style="padding: 15px 10px;"><i class="fas fa-exclamation-triangle" style="margin:0 8px 0 2px;"></i> No outbound entities linked yet</div>';
            }




            echo '</div>';
            echo '</div>';

        } elseif ($item == 'intents') {

            echo '<div class="tab-pane" id="tabintents">';
            echo '<div id="list-intents" class="list-group maxout grey-list">';

            echo '<div>Upcoming feature...</div>';

            echo '</div>';
            echo '</div>';

        } elseif ($item == 'messages') {

            //Fetch the current messages that have referenced this content:

            echo '<div class="tab-pane" id="tabmessages">';
            echo '<div id="list-messages" class="list-group maxout grey-list">';

            echo 'Messages go here...';
            echo '</div>';
            echo '</div>';

        }
    }
    echo '</div>';

echo '</div>';
}
?>