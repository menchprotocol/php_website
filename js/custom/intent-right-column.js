/*
*
* Functions related to modifying intents
* and managing intent notes.
*
* */

var match_search_loaded = 0; //Keeps track of when we load the match search

$(document).ready(function () {

    if (is_compact) {

        //Remove all columns
        $('.cols').removeClass('col-xs-1').removeClass('col-xs-2').removeClass('col-xs-3').removeClass('col-xs-4').removeClass('col-xs-5').removeClass('col-xs-6').removeClass('col-xs-7').removeClass('col-xs-8').removeClass('col-xs-9').removeClass('col-xs-10').removeClass('col-xs-11');

        //Add largest column:
        $('.cols').addClass('col-xs-12');
        $('.fixed-box').addClass('release-fixture');
        $('.dash').css('margin-bottom', '0px'); //For iframe to show better

    } else {

        //Adjust height of the messaging windows:
        $('.grey-box').css('height', (parseInt($(window).height()) - 190) + 'px');
        $('.grey-box').css('max-height', (parseInt($(window).height()) - 190) + 'px');

        $('.ajax-frame').css('height', (parseInt($(window).height()) - 225) + 'px');
        $('.ajax-frame').css('max-height', (parseInt($(window).height()) - 225) + 'px');

        //Make editing frames Sticky for scrolling longer lists
        $(".main-panel").scroll(function () {
            var top_position = $(this).scrollTop();
            clearTimeout($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function () {
                $("#modifybox, #load_messaging_frame, #load_action_plan_frame").css('top', (top_position - 0)); //PX also set in style.css for initial load
            }, 34));
        });

    }


    //Watch for intent status change:
    $("#in_status_entity_id").change(function () {

        //Should we show the recursive button? Only if the status changes from the original one...
        if( parseInt($('#in_status_entity_id').attr('original-status'))==parseInt(this.value)){
            $('.apply-recursive').addClass('hidden');
            $('#apply_recursively').prop('checked', false);
        } else {
            $('.apply-recursive').removeClass('hidden');
        }

        //Should we show intent archiving warning?
        if(parseInt(this.value) < 0){
            $('.notify_in_remove').removeClass('hidden');
        } else {
            $('.notify_in_remove').addClass('hidden');
        }
    });


    //Lookout for intent link type changes:
    $('input[type=radio][name=ln_type_entity_id], #ln_status_entity_id').change(function () {
        in_adjust_link_ui();
    });


    //Lookout for intent type changes
    $('input[type=radio][name=in_6676_type]').change(function () {
        in_load_type(this.value);
    });


    //Do we need to auto load anything?
    if (window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if (hash_parts.length >= 2) {
            //Fetch level if available:
            if (hash_parts[0] == 'intentnotes') {
                in_messages_iframe(hash_parts[1]);
            } else if (hash_parts[0] == 'loadmodify') {
                in_modify_load(hash_parts[1], hash_parts[2]);
            } else if (hash_parts[0] == 'actionplanusers') {
                in_action_plan_users(hash_parts[1]);
            }
        }
    }



});


//This also has an equal PHP function echo_time_hours() which we want to make sure has more/less the same logic:
function echo_js_hours(in_completion_seconds) {

    in_completion_seconds = parseInt(in_completion_seconds);
    if (in_completion_seconds < 1) {
        return '0';
    } else if (in_completion_seconds < 60) {
        return in_completion_seconds + "s";
    } else if (in_completion_seconds < 3600) {
        //Show this in minutes:
        return Math.round((in_completion_seconds / 60)) + "m";
    } else {
        //Show in rounded hours:
        return Math.round((in_completion_seconds / 3600)) + "h";
    }
}


function in_load_type(in_6676_type) {
    $('.show-all-types').addClass('hidden');
    $('.show-for-'+in_6676_type).removeClass('hidden');
}

function in_adjust_link_ui() {

    //Fetch intent link ID:
    var ln_id = parseInt($('#modifybox').attr('intent-tr-id'));

    if (!$('#modifybox').hasClass('hidden') && ln_id > 0) {

        //Yes show that section:
        $('.in-has-tr').removeClass('hidden');
        $('.in-no-tr').addClass('hidden');

        //What's the selected intent status?
        if (parseInt($('#ln_status_entity_id').find(":selected").val()) < 0) {
            //About to delete? Notify them:
            $('.notify_unlink_in').removeClass('hidden');
        } else {
            $('.notify_unlink_in').addClass('hidden');
        }

        //What's the intent link type?
        if ($('#ln_type_entity_id_4229').is(':checked')) {
            //Conditional Step Links is checked:
            $('.score_range_box').removeClass('hidden');
            $('.score_points').addClass('hidden');
        } else {
            //Any is selected, lock the completion settings as its not allowed for ANY Branches:
            $('.score_range_box').addClass('hidden');
            $('.score_points').removeClass('hidden');
        }

    } else {
        //Main intent, no link, so hide entire section:
        $('.in-has-tr').addClass('hidden');
        $('.in-no-tr').removeClass('hidden');
    }
}


function in_messages_iframe(in_id) {

    //Set opacity:
    remove_all_highlights();
    $(".highlight_in_"+in_id).addClass('in_highlight');

    //Start loading:
    $('.frame-loader').removeClass('hidden');
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $('#load_messaging_frame').removeClass('hidden').hide().fadeIn();

    //Set title:
    $('#load_messaging_frame .badge-h-max').html('<i class="fas fa-comment-plus"></i> ' + $('.in_outcome_' + in_id + ':first').text());

    //Load content via a URL:
    $('.ajax-frame').attr('src', '/intents/in_messages_iframe/' + in_id).css('margin-top', '0');
    $('.ajax-frame').on("load", function () {
        $('.frame-loader').addClass('hidden');
        $('.ajax-frame').removeClass('hidden').contents().find('#ln_content' + in_id).focus();
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function in_action_plan_users(in_id) {

    //Start loading:
    $('.fixed-box').addClass('hidden');
    $('#load_action_plan_frame').removeClass('hidden').hide().fadeIn();

    //Set title:
    $('#load_action_plan_frame .badge-h-max').html('<i class="fas fa-walking"></i> ' + $('.in_outcome_' + in_id + ':first').text());

    //Set opacity:
    remove_all_highlights();
    $(".highlight_in_"+in_id).addClass('in_highlight');

    //Show Loading Icon:
    $('#ap_matching_users').html('<span><i class="fas fa-spinner fa-spin"></i> Loading...</span>').hide().fadeIn();

    //Load Matching Users:
    $.post("/intents/in_action_plan_users", {
        in_filters:( typeof js_in_filters !== 'undefined' ? js_in_filters : [] ),
        in_focus_id: in_focus_id,
        in_id: in_id
    }, function (data) {
        if (!data.status) {

            //Hide Box:
            $('.fixed-box').addClass('hidden');

            //Opppsi, show the error:
            alert('Error: ' + data.message);

        } else {

            //Load content:
            $('#ap_matching_users').html(data.message).hide().fadeIn();

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        }
    });

}


function adjust_js_ui(in_id, level, new_hours, intent_deficit_count, apply_to_tree, skip_intent_adjustments) {

    intent_deficit_count = parseInt(intent_deficit_count);
    var in_completion_seconds = parseFloat($('.t_estimate_' + in_id + ':first').attr('intent-seconds'));
    var in__metadata_seconds = parseFloat($('.t_estimate_' + in_id + ':first').attr('tree-max-seconds'));
    var in_deficit_seconds = new_hours - (skip_intent_adjustments ? 0 : (apply_to_tree ? in__metadata_seconds : in_completion_seconds));

    //Adjust same level hours:
    if (!skip_intent_adjustments) {
        var in_new__metadata_seconds = in__metadata_seconds + in_deficit_seconds;
        $('.t_estimate_' + in_id)
            .attr('tree-max-seconds', in_new__metadata_seconds)
            .text(echo_js_hours(in_new__metadata_seconds));

        if (!apply_to_tree) {
            $('.t_estimate_' + in_id).attr('intent-seconds', new_hours).text(echo_js_hours(in_new__metadata_seconds));
        }
    }


    //Adjust parent counters, if any:
    if (!(intent_deficit_count == 0)) {
        //See how many parents we have:
        $('.inb-counter').each(function () {
            $(this).text(parseInt($(this).text()) + intent_deficit_count);
        });
    }

    if (level >= 2) {

        //Adjust the parent level hours:
        var in_parent_id = parseInt($('.intent_line_' + in_id).attr('parent-intent-id'));
        var in_parent__metadata_seconds = parseFloat($('.t_estimate_' + in_parent_id + ':first').attr('tree-max-seconds'));
        var in_new_parent__metadata_seconds = in_parent__metadata_seconds + in_deficit_seconds;

        if (!(intent_deficit_count == 0)) {
            $('.children-counter-' + in_parent_id).text(parseInt($('.children-counter-' + in_parent_id + ':first').text()) + intent_deficit_count);
        }

        //Update Hours (Either level 1 or 2):
        $('.t_estimate_' + in_parent_id)
            .attr('tree-max-seconds', in_new_parent__metadata_seconds)
            .text(echo_js_hours(in_new_parent__metadata_seconds));


        if (level == 3) {
            //Adjust top level intent as well:
            var in_top_level = parseInt($('.intent_line_' + in_parent_id).attr('parent-intent-id'));
            var in_primary__metadata_seconds = parseFloat($('.t_estimate_' + in_top_level + ':first').attr('tree-max-seconds'));
            var in_new__metadata_seconds = in_primary__metadata_seconds + in_deficit_seconds;

            if (!(intent_deficit_count == 0)) {
                $('.children-counter-' + in_top_level).text(parseInt($('.children-counter-' + in_top_level + ':first').text()) + intent_deficit_count);
            }

            //Update Hours:
            $('.t_estimate_' + in_top_level)
                .attr('tree-max-seconds', in_new__metadata_seconds)
                .text(echo_js_hours(in_new__metadata_seconds));
        }
    }
}


function in_outcome_counter() {
    var len = $('#in_outcome').val().length;
    if (len > in_outcome_max) {
        $('#charNameNum').addClass('overload').text(len);
    } else {
        $('#charNameNum').removeClass('overload').text(len);
    }
}



function in_modify_load(in_id, ln_id) {

    //Indicate Loading:
    $('#modifybox .grey-box .loadcontent').addClass('hidden');
    $('#modifybox .grey-box .loadbox').removeClass('hidden');
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();
    $('#modifybox').attr('intent-tr-id', 0).attr('intent-id', 0).attr('level', 0);
    $('.apply-recursive').addClass('hidden');
    $('#apply_recursively').prop('checked', false);
    $('.save_intent_changes').html(' ');

    //Reset & set new opacity:
    remove_all_highlights();
    $(".highlight_in_"+in_id).addClass('in_highlight');

    //Reset parent editing button:
    $('.modify_parent_in').addClass('hidden');

    //Set title:
    $('.edit-header').html('<i class="fas fa-cog"></i> ' + $('.in_outcome_' + in_id + ':first').text());

    //Fetch Intent Data to load modify widget:
    $.post("/intents/in_load_data", {
        in_id: in_id,
        ln_id: ln_id,
        is_parent: ( $('.intent_line_' + in_id).hasClass('parent-intent') ? 1 : 0 ),
    }, function (data) {
        if (!data.status) {

            //Opppsi, show the error:
            alert('Error Loading Intent: ' + data.message);

        } else {

            //All good, let's load the data into the Modify Widget...

            //Update variables:
            var level = (ln_id == 0 ? 1 : parseInt($('.in__tr_' + ln_id).attr('intent-level'))); //Either 1, 2 or 3
            $('#modifybox').attr('intent-tr-id', ln_id);
            $('#modifybox').attr('intent-id', in_id);
            $('#modifybox').attr('level', level);

            //Load inputs:
            $('#in_completion_seconds').val(data.in.in_completion_seconds);
            $('.tr_in_link_title').text('');
            $('#in_status_entity_id').val(data.in.in_status_entity_id).attr('original-status', data.in.in_status_entity_id); //Set the status before it gets changed by miners


            //Load intent link data if available:
            if (ln_id > 0) {

                //Always load:
                $("#ln_status_entity_id").val(data.ln.ln_status_entity_id);
                $('#tr__conditional_score_min').val(data.ln.ln_metadata.tr__conditional_score_min);
                $('#tr__conditional_score_max').val(data.ln.ln_metadata.tr__conditional_score_max);
                $('#tr__assessment_points').val(data.ln.ln_metadata.tr__assessment_points);

                //Link editing adjustments:
                $('#tr_in_link_update').val(data.ln.in_outcome);
                $('.tr_in_link_title').text(( $('.intent_line_' + in_id).hasClass('parent-intent') ? 'Child' : 'Parent' ));

                //Is this a Conditional Step Link? If so, load the min/max range:
                if (data.ln.ln_type_entity_id == 4229) {
                    //Yes, load the data (which must be there):
                    $('#ln_type_entity_id_4229').prop("checked", true);
                } else {
                    //Fixed link:
                    $('#ln_type_entity_id_4228').prop("checked", true);
                }
            }

            //Make the frame visible:
            $('.notify_in_remove, .notify_unlink_in').addClass('hidden'); //Hide potential previous notices
            $('#modifybox .grey-box .loadcontent').removeClass('hidden');
            $('#modifybox .grey-box .loadbox').addClass('hidden');

            //Run UI Updating functions after we've removed the hidden class from #modifybox:
            in_outcome_counter();
            in_adjust_link_ui();

            var in_6676_type = js_in_is_or(data.in.in_type_entity_id, true);
            $('#in_'+in_6676_type+'_type').val(data.in.in_type_entity_id); //Set drop down to intent sub-type
            $('input[type=radio][name=in_6676_type]').prop('checked', false);
            $('#parent__type_'+in_6676_type).prop('checked', true);
            in_load_type(in_6676_type);

            //Update intent outcome and set focus:
            $('#in_outcome').val(data.in.in_outcome).focus();

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();



            var in_is_system_locked = ( in_system_lock.indexOf(parseInt(data.in.in_id)) !== -1);

            //Status locked intent?
            if(in_is_system_locked){
                $('#in_status_entity_id').prop('disabled', true);
                $('.in_status_entity_id_lock').removeClass('hidden');
            } else {
                $('#in_status_entity_id').prop('disabled', false);
                $('.in_status_entity_id_lock').addClass('hidden');
            }

            //See if we need to lock the intent type editor:
            if(data.in_action_plan_count > 0 || in_is_system_locked){
                //Yes, we should lock it:
                $('input[type=radio][name=in_6676_type], input[type=radio][name=ln_type_entity_id], #in_6192_type, #in_6193_type').attr('disabled', true);
            } else {
                //No Progression made, so we can keep it unlocked:
                $('input[type=radio][name=in_6676_type], input[type=radio][name=ln_type_entity_id], #in_6192_type, #in_6193_type').attr('disabled', false);
            }

            //We might need to scroll if mobile:
            if (is_compact) {
                $('.main-panel').animate({
                    scrollTop: 9999
                }, 150);
            }
        }
    });
}

function in_modify_save() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('intent-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Prepare top-level intents (in case we move an intent here):
    var in_id = parseInt($('#modifybox').attr('intent-id'));
    var top_level_ins = [ in_id ];
    $(".level2_in").each(function () {
        top_level_ins.push(parseInt($(this).attr('intent-id')));
    });


    //Prepare data to be modified for this intent:
    var modify_data = {
        in_id: in_id,
        level: parseInt($('#modifybox').attr('level')),
        in_outcome: $('#in_outcome').val(),
        in_status_entity_id: parseInt($('#in_status_entity_id').val()),
        in_completion_seconds: ( $('#in_completion_seconds').val().length > 0 ? parseInt($('#in_completion_seconds').val()) : 0 ),
        apply_recursively: (document.getElementById('apply_recursively').checked ? 1 : 0),
        is_parent: ( $('.intent_line_' + in_id).hasClass('parent-intent') ? 1 : 0 ),

        //Intent Types:
        in_6676_type:parseInt($('input[name=in_6676_type]:checked').val()), //Main AND/OR Type
        in_6192_type:parseInt($('#in_6192_type').val()), //AND Types IF AND was selected
        in_6193_type:parseInt($('#in_6193_type').val()), //OR Types IF OR was selected

        //Link variables:
        ln_id: parseInt($('#modifybox').attr('intent-tr-id')), //Will be zero for Level 1 intent!
        top_level_ins: top_level_ins,
        ln_type_entity_id: null,
        tr_in_link_update: null,
        tr__conditional_score_min: null,
        tr__conditional_score_max: null,
        tr__assessment_points: null,
    };


    //Do we have the intent Link?
    if (modify_data['ln_id'] > 0) {

        modify_data['ln_status_entity_id'] = parseInt($('#ln_status_entity_id').val());
        modify_data['ln_type_entity_id'] = parseInt($('input[name=ln_type_entity_id]:checked').val());
        modify_data['tr_in_link_update'] = $('#tr_in_link_update').val();

        if(modify_data['ln_type_entity_id'] == 4229){
            //Conditional Step Links
            //Condition score range:
            modify_data['tr__conditional_score_min'] = $('#tr__conditional_score_min').val();
            modify_data['tr__conditional_score_max'] = $('#tr__conditional_score_max').val();
        } else if(modify_data['ln_type_entity_id'] == 4228){
            //Fixed link awarded points:
            modify_data['tr__assessment_points'] = $('#tr__assessment_points').val();
        }
    }

    //Show spinner:
    $('.save_intent_changes').html('<span><i class="fas fa-spinner fa-spin"></i> Saving...</span>').hide().fadeIn();


    //Save the rest of the content:
    $.post("/intents/in_modify_save", modify_data, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_intent_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Has the intent/intent-link been removed? Either way, we need to hide this row:
            if (data.remove_from_ui) {

                //Intent has been either removed OR unlinked:
                if (data.remove_redirect_url) {

                    //move up 1 level as this was the focus intent:
                    window.location = data.remove_redirect_url;

                } else {

                    //Remove Hash:
                    window.location.hash = '#';

                    //Reset opacity:
                    remove_all_highlights();

                    //Adjust completion cost:
                    adjust_js_ui(modify_data['in_id'], modify_data['level'], 0, 0, 1, 0);

                    //Remove from UI:
                    $('.in__tr_' + modify_data['ln_id']).html('<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>');

                    //Hide the editor & saving results:
                    $('.in__tr_' + modify_data['ln_id']).fadeOut();

                    //Disappear in a while:
                    setTimeout(function () {

                        //Hide the editor & saving results:
                        $('.in__tr_' + modify_data['ln_id']).remove();

                        //Hide editing box:
                        $('#modifybox').addClass('hidden');

                        //Resort all Steps to illustrate changes on UI:
                        in_sort_save(parseInt($('.intent_line_' + modify_data['in_id']).attr('parent-intent-id')), modify_data['level']);

                    }, 610);

                }

            } else {

                //Intent has not been updated:

                //Did the Link update?
                if (modify_data['ln_id'] > 0) {

                    $('.ln_type_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ en_all_4486[modify_data['ln_type_entity_id']]["m_name"] + ': '+ en_all_4486[modify_data['ln_type_entity_id']]["m_desc"] + '">'+ en_all_4486[modify_data['ln_type_entity_id']]["m_icon"] +'</span>');

                    $('.ln_status_entity_id_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_6186[modify_data['ln_status_entity_id']]["m_name"] + ': '+ js_en_all_6186[modify_data['ln_status_entity_id']]["m_desc"] + '">'+ js_en_all_6186[modify_data['ln_status_entity_id']]["m_icon"] +'</span>');

                    //Update Assessment
                    $(".in_assessment_" + modify_data['ln_id']).html(( modify_data['ln_type_entity_id']==4228 ? ( modify_data['tr__assessment_points'] != 0 ? ( modify_data['tr__assessment_points'] > 0 ? '+' : '' ) + modify_data['tr__assessment_points'] : '' ) : modify_data['tr__conditional_score_min'] + ( modify_data['tr__conditional_score_min']==modify_data['tr__conditional_score_max'] ? '' : '-' + modify_data['tr__conditional_score_max'] ) + '%' ));

                }


                //Update UI components...

                //Always update 3x Intent icons...

                //AND/OR Icon which is the main type:
                $('.in_parent_type_' + modify_data['in_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ en_all_6676[modify_data['in_6676_type']]["m_name"] + ': '+ en_all_6676[modify_data['in_6676_type']]["m_desc"] + '">'+ en_all_6676[modify_data['in_6676_type']]["m_icon"] +'</span>');

                //Also update secondary intent icon:
                var in__type = ( modify_data['in_6676_type']==6193 ? en_all_6193 : en_all_6192 ); //Not sure how to do variable in variable for Javascript, so here we are...
                var in__slct = ( modify_data['in_6676_type']==6193 ? modify_data['in_6193_type'] : modify_data['in_6192_type'] );
                $('.in_type_entity_id_' + modify_data['in_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ in__type[in__slct]["m_name"] + ': '+ in__type[in__slct]["m_desc"] + '">'+ in__type[in__slct]["m_icon"] +'</span>');


                //Also update possible child icons:
                $('.in_icon_child_' + modify_data['in_id']).html(en_all_6676[modify_data['in_6676_type']]["m_icon"]);

                $('.in_status_entity_id_' + modify_data['in_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_4737[modify_data['in_status_entity_id']]["m_name"] + ': '+ js_en_all_4737[modify_data['in_status_entity_id']]["m_desc"] + '">'+ js_en_all_4737[modify_data['in_status_entity_id']]["m_icon"] +'</span>');


                //Update UI to confirm with user:
                $('.save_intent_changes').html(data.message).hide().fadeIn();


                //Adjust completion cost:
                adjust_js_ui(modify_data['in_id'], modify_data['level'], modify_data['in_completion_seconds'], 0, 0, 0);


                //Did the outcome change?
                if(data.formatted_in_outcome){
                    //yes, update it:
                    $(".in_outcome_" + modify_data['in_id']).html(data.formatted_in_outcome);

                    //Set title:
                    $('.edit-header').html('<i class="fas fa-cog"></i> ' + modify_data['in_outcome']);

                    //Also update possible child icons:
                    $('.in_icon_child_' + modify_data['in_id']).attr('data-original-title', modify_data['in_outcome']);
                }


                //Should we try to check unlockable completions?
                if(data.ins_unlocked_completions_count > 0){
                    //We did complete/unlock some intents, inform miner and refresh:
                    alert('Publishing this intent has just unlocked '+data.steps_unlocked_completions_count+' steps across '+data.ins_unlocked_completions_count+' intents. Page will be refreshed to reflect changes.');
                    window.location = "/intents/" + in_focus_id;
                }

            }

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();


            //What's the final action?
            if (modify_data['apply_recursively'] && data.recursive_update_count > 0) {
                //Refresh page soon to show new status for children:
                window.location = "/intents/" + in_focus_id;
            } else {
                //Clear times:
                setTimeout(function () {
                    $('.save_intent_changes').html(' ');
                }, 1597);
            }
        }
    });

}
