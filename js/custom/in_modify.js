/*
*
* Functions related to modifying intents
* and managing intent notes.
*
* */

var match_search_loaded = 0; //Keeps track of when we load the match search

$(document).ready(function () {

    //Watch for intent status change:
    $("#in_status_entity_id").change(function () {

        //Should we show intent archiving warning?
        if(parseInt(this.value) == 6182 /* Intent Removed */){
            $('.notify_in_remove').removeClass('hidden');
        } else {
            $('.notify_in_remove').addClass('hidden');
        }
    });

    autosize($('#new_blog_title'));

    $('#new_blog_title').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (e.ctrlKey && code == 13) {
            in_save_title();
        } else if (code == 13) {
            e.preventDefault();
        }
    }).focus(function() {
        //Clear default title
        if ($('#new_blog_title').val().toUpperCase() == js_en_all_6201[4736]['m_name']) {
            $('#new_blog_title').val('');
            $('#blog_title_save').addClass('hidden');
        }
    });



    //Lookout for intent link type changes:
    $('#ln_type_entity_id, #ln_status_entity_id').change(function () {
        in_adjust_link_ui();
    });

});

function show_save_button(){

    //Detect changes in blog title to show the save button:
    if($('#new_blog_title').val() == $('#current_blog_title').val() || $('#new_blog_title').val().length < 1 || $('#new_blog_title').val().toUpperCase() == js_en_all_6201[4736]['m_name']){
        //Nothing changed, so nothing to save:
        $('#blog_title_save').addClass('hidden');
    } else {
        //Something changed, show save button:
        $('#blog_title_save').removeClass('hidden');

        in_outcome_counter();
    }
}


function in_update_dropdown(element_id, new_en_id){

    /*
    *
    * WARNING:
    *
    * element_id Must be listed as children of:
    *
    * MEMORY CACHE @4527
    * JS MEMORY CACHE @11054
    *
    *
    * */

    var current_selected = parseInt($('.dropd_'+element_id).hasClass('active').attr('new-en-id'));
    console.log(current_selected);
    if(current_selected==parseInt(new_en_id)){
        //Nothing changed:
        return false;
    }

    //Are we deleting a status?
    var is_delete = (element_id==4737 && !(new_en_id in js_en_all_7356));

    if(is_delete){
        //Seems to be deleting, confirm:
        var r = confirm("Are you sure you want to archive this blog?");
        if (!(r == true)) {
            return false;
        }
    }

    //Show Loading...
    var data_object = eval('js_en_all_'+element_id);
    $('.dropd_'+element_id+' .btn').html('<b class="montserrat"><i class="far fa-yin-yang fa-spin"></i> SAVING...</b>');

    $.post("/blog/in_update_dropdown", {
        in_id: in_loaded_id,
        element_id: element_id,
        new_en_id: new_en_id,
    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.dropd_'+element_id+' .btn').html('<span class="icon-block">'+data_object[new_en_id]['m_icon']+'</span>' + data_object[new_en_id]['m_name']);
            $('.dropd_'+element_id+' .dropdown-menu').removeClass('active');
            $('.dropd_'+element_id+' .optiond_' + new_en_id).addClass('active');

            if(is_delete){
                //Go to main blog page:
                window.location = '/blog';
            }

        } else {

            //Reset to default:
            $('.dropd_'+element_id+' .btn').html('<span class="icon-block">'+data_object[current_selected]['m_icon']+'</span>' + data_object[current_selected]['m_name']);

            //Show error:
            alert('ERROR: ' + data.message);

        }
    });
}



function in_save_title(){
    //Fetch Intent Data to load modify widget:
    $('.title_update_status').html('<b class="montserrat"><i class="far fa-yin-yang fa-spin"></i> SAVING...</b>').hide().fadeIn();


    $.post("/blog/in_save_title", {
        in_id: in_loaded_id,
        in_outcome: $('#new_blog_title').val(),
    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.title_update_status').html(data.message);

            setTimeout(function () {
                $('#current_blog_title, #new_blog_title').val(data.in_cleaned_outcome);
                $('#blog_title_save').addClass('hidden');
                $('.title_update_status').html('');
            }, 1597);

        } else {
            //Show error:
            $('.title_update_status').html('<b class="montserrat ispink">ERROR: '+data.message+'</b>').hide().fadeIn();

        }
    });
}

//This also has an equal PHP function echo_time_hours() which we want to make sure has more/less the same logic:
function in_update_time(in_completion_seconds) {

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



function in_control_engagement_level() {

    //*
    //Fetch intent READ ID:
    var ln_id = parseInt($('#modifybox').attr('intent-tr-id'));

    if (!$('#modifybox').hasClass('hidden') && ln_id > 0) {

        //Yes show that section:
        $('.in-has-tr').removeClass('hidden');

        //What's the selected intent status?
        if (parseInt($('#ln_status_entity_id').find(":selected").val()) == 6173 /* Link Removed */) {
            //About to delete? Notify them:
            $('.notify_unlink_in').removeClass('hidden');
        } else {
            $('.notify_unlink_in').addClass('hidden');
        }

        //What's the intent link type?
        if (parseInt($('#ln_type_entity_id').find(":selected").val()) == 4229 /* Conditional Step */) {
            //Conditional Step Links is checked:
            $('.score_range_box').removeClass('hidden');
            $('.score_points').addClass('hidden');
        } else {
            //This should be a Required steo
            //Any is selected, lock the completion settings as its not allowed for ANY Branches:
            $('.score_range_box').addClass('hidden');
            $('.score_points').removeClass('hidden');
        }

    } else {
        //Main intent, no link, so hide entire section:
        $('.in-has-tr').addClass('hidden');
    }
}



function in_adjust_link_ui() {

    //Fetch intent READ ID:
    var ln_id = parseInt($('#modifybox').attr('intent-tr-id'));

    if (!$('#modifybox').hasClass('hidden') && ln_id > 0) {

        //Yes show that section:
        $('.in-has-tr').removeClass('hidden');

        //What's the selected intent status?
        if (parseInt($('#ln_status_entity_id').find(":selected").val()) == 6173 /* Link Removed */) {
            //About to delete? Notify them:
            $('.notify_unlink_in').removeClass('hidden');
        } else {
            $('.notify_unlink_in').addClass('hidden');
        }

        //What's the intent link type?
        if (parseInt($('#ln_type_entity_id').find(":selected").val()) == 4229 /* Conditional Step */) {
            //Conditional Step Links is checked:
            $('.score_range_box').removeClass('hidden');
            $('.score_points').addClass('hidden');
        } else {
            //This should be a Required steo
            //Any is selected, lock the completion settings as its not allowed for ANY Branches:
            $('.score_range_box').addClass('hidden');
            $('.score_points').removeClass('hidden');
        }

    } else {
        //Main intent, no link, so hide entire section:
        $('.in-has-tr').addClass('hidden');
    }

}


function in_outcome_counter() {
    var len = $('#new_blog_title').val().length;
    if (len > js_en_all_6404[11071]['m_desc']) {
        $('#charNameNum').addClass('overload').text(len);
    } else {
        $('#charNameNum').removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_en_all_6404[11071]['m_desc'] * js_en_all_6404[12088]['m_desc'] )){
        $('.title_counter').removeClass('hidden');
    } else {
        $('.title_counter').addClass('hidden');
    }
}



function in_modify_load(in_id, ln_id) {

    //Indicate Loading:
    $('#modifybox .grey-box .loadcontent').addClass('hidden');
    $('#modifybox .grey-box .loadbox').removeClass('hidden');
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();
    $('#modifybox').attr('intent-tr-id', 0).attr('intent-id', 0);
    $('.apply-recursive').addClass('hidden');
    $('.save_intent_changes').html(' ');

    //Reset & set new opacity:
    remove_all_highlights();
    $(".highlight_in_"+in_id).addClass('in_highlight');


    //Set title:
    $('.edit-header').html('<i class="fas fa-cog"></i> ' + $('.in_outcome_' + in_id + ':first').text());

    //Fetch Intent Data to load modify widget:
    $.post("/blog/in_load_data", {
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
            $('#modifybox').attr('intent-tr-id', ln_id);
            $('#modifybox').attr('intent-id', in_id);

            //Load inputs:
            $('#in_completion_seconds').val(data.in.in_completion_seconds);
            $('.tr_in_link_title').text('');
            $('#in_status_entity_id').val(data.in.in_status_entity_id).attr('original-status', data.in.in_status_entity_id); //Set the status before it gets changed by trainers
            //Load intent link data if available:
            if (ln_id > 0) {

                //Always load:
                $("#ln_status_entity_id").val(data.ln.ln_status_entity_id);
                $('#tr__conditional_score_min').val(data.ln.ln_metadata.tr__conditional_score_min);
                $('#tr__conditional_score_max').val(data.ln.ln_metadata.tr__conditional_score_max);
                $('#tr__assessment_points').val(data.ln.ln_metadata.tr__assessment_points);
                $('#ln_type_entity_id').val(data.ln.ln_type_entity_id);

                //Link editing adjustments:
                $('.tr_in_link_title').text(( $('.intent_line_' + in_id).hasClass('parent-intent') ? 'Child' : 'Parent' ));
            }

            //Make the frame visible:
            $('.notify_in_remove, .notify_unlink_in').addClass('hidden'); //Hide potential previous notices
            $('#modifybox .grey-box .loadcontent').removeClass('hidden');
            $('#modifybox .grey-box .loadbox').addClass('hidden');

            //Run UI Updating functions after we've removed the hidden class from #modifybox:
            in_outcome_counter();
            in_adjust_link_ui();

            $('#in_completion_method_entity_id').val(data.in.in_completion_method_entity_id); //Set intent type

            //Update intent outcome and set focus:
            $('#in_outcome').val(data.in.in_outcome).focus();

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();


            //Status locked intent?
            $('#in_status_entity_id').prop('disabled', false);
            $('.in_status_entity_id_lock').addClass('hidden');

        }
    });
}

function in_unlink(in_id, ln_id){
    var r = confirm("Unlink ["+$('.in_outcome_'+in_id).text()+"]?");
    if (r == true) {

        //Fetch Intent Data to load modify widget:
        $.post("/blog/in_unlink", {
            in_id: in_id,
            ln_id: ln_id,
        }, function (data) {
            if (data.status) {
                in_ui_remove(in_id,ln_id);
            }
        });
    }
}

function in_ui_remove(in_id,ln_id){

    //Fetch parent intent before removing element from DOM:
    var parent_in_id = parseInt($('.intent_line_' + in_id).attr('parent-intent-id'));

    //Reset opacity:
    remove_all_highlights();

    //Remove from UI:
    $('.in__tr_' + ln_id).html('<span style="color:#070707;"><i class="fas fa-trash-alt"></i></span>');

    //Hide the editor & saving results:
    $('.in__tr_' + ln_id).fadeOut();

    //Disappear in a while:
    setTimeout(function () {

        //Hide the editor & saving results:
        $('.in__tr_' + ln_id).remove();

        //Hide editing box:
        $('#modifybox').addClass('hidden');

        //Re-sort sibling intents:
        in_sort_save(parent_in_id);

    }, 610);

}

function in_modify_save() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('intent-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Prepare BLOGS (in case we move a BLOG here):
    var in_id = parseInt($('#modifybox').attr('intent-id'));
    var top_level_ins = [ in_id ];
    $(".level2_in").each(function () {
        top_level_ins.push(parseInt($(this).attr('intent-id')));
    });


    //Prepare data to be modified for this intent:
    var modify_data = {
        in_id: in_id,
        in_outcome: $('#in_outcome').val(),
        in_status_entity_id: parseInt($('#in_status_entity_id').val()),
        in_completion_method_entity_id: parseInt($('#in_completion_method_entity_id').val()),
        in_completion_seconds: ( $('#in_completion_seconds').val().length > 0 ? parseInt($('#in_completion_seconds').val()) : 0 ),
        is_parent: ( $('.intent_line_' + in_id).hasClass('parent-intent') ? 1 : 0 ),

        //Link variables:
        ln_id: parseInt($('#modifybox').attr('intent-tr-id')), //Will be zero for Level 1 intent!
        top_level_ins: top_level_ins,
        ln_type_entity_id: null,
        tr__conditional_score_min: null,
        tr__conditional_score_max: null,
        tr__assessment_points: null,
    };


    //Do we have the intent Link?
    if (modify_data['ln_id'] > 0) {

        modify_data['ln_status_entity_id'] = parseInt($('#ln_status_entity_id').val());
        modify_data['ln_type_entity_id'] = parseInt($('#ln_type_entity_id').val());

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
    $('.save_intent_changes').html('<span><i class="far fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();



    //Save the rest of the content:
    $.post("/blog/in_modify_save", modify_data, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_intent_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Has the intent/intent-link been removed? Either way, we need to hide this row:
            if (data.remove_from_ui) {

                //Remove from UI:
                in_ui_remove(modify_data['in_id'], modify_data['ln_id']);

            } else {

                //Intent has not been updated:

                //Did the Link update?
                if (modify_data['ln_id'] > 0) {

                    $('.ln_type_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_4486[modify_data['ln_type_entity_id']]['m_name'] + ': '+ js_en_all_4486[modify_data['ln_type_entity_id']]['m_desc'] + '">'+ js_en_all_4486[modify_data['ln_type_entity_id']]['m_icon'] +'</span>');

                    $('.ln_status_entity_id_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_6186[modify_data['ln_status_entity_id']]['m_name'] + ': '+ js_en_all_6186[modify_data['ln_status_entity_id']]['m_desc'] + '">'+ js_en_all_6186[modify_data['ln_status_entity_id']]['m_icon'] +'</span>');

                    //Update Assessment
                    $(".in_assessment_" + modify_data['ln_id']).html(( modify_data['ln_type_entity_id']==4228 ? ( modify_data['tr__assessment_points'] != 0 ? ( modify_data['tr__assessment_points'] > 0 ? '+' : '' ) + modify_data['tr__assessment_points'] : '' ) : modify_data['tr__conditional_score_min'] + ( modify_data['tr__conditional_score_min']==modify_data['tr__conditional_score_max'] ? '' : '-' + modify_data['tr__conditional_score_max'] ) + '%' ));

                }


                //Update UI components...

                //Always update 3x Intent icons...

                $('.in_parent_type_' + modify_data['in_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_7585[modify_data['in_completion_method_entity_id']]['m_name'] + ': '+ js_en_all_7585[modify_data['in_completion_method_entity_id']]['m_desc'] + '">'+ js_en_all_7585[modify_data['in_completion_method_entity_id']]['m_icon'] +'</span>');

                //Also update possible child icons:
                $('.in_child_icon_' + modify_data['in_id']).html(js_en_all_7585[modify_data['in_completion_method_entity_id']]['m_icon']);


                $('.in_status_entity_id_' + modify_data['in_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_4737[modify_data['in_status_entity_id']]['m_name'] + ': '+ js_en_all_4737[modify_data['in_status_entity_id']]['m_desc'] + '">'+ js_en_all_4737[modify_data['in_status_entity_id']]['m_icon'] +'</span>');


                //Update UI to confirm with user:
                $('.save_intent_changes').html(data.message).hide().fadeIn();


                //Did the outcome change?
                if(data.formatted_in_outcome){
                    //yes, update it:
                    $(".in_outcome_" + modify_data['in_id']).html(data.formatted_in_outcome);

                    //Set title:
                    $('.edit-header').html('<i class="fas fa-cog"></i> ' + modify_data['in_outcome']);

                    //Also update possible child icons:
                    $('.in_child_icon_' + modify_data['in_id']).attr('data-original-title', modify_data['in_outcome']);
                }


                //Should we try to check unlockable completions?
                if(data.ins_unlocked_completions_count > 0){
                    //We did complete/unlock some intents, inform trainer and refresh:
                    alert('Publishing this intent has just unlocked '+data.steps_unlocked_completions_count+' steps across '+data.ins_unlocked_completions_count+' intents. Page will be refreshed to reflect changes.');
                    window.location = "/blog/" + in_loaded_id;
                }

            }

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();


            //Clear times:
            setTimeout(function () {
                $('.save_intent_changes').html(' ');
            }, 1597);
        }
    });

}
