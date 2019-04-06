



//This also has an equal PHP function echo_time_hours() which we want to make sure has more/less the same logic:
function echo_js_hours(in_seconds_cost) {

    in_seconds_cost = parseInt(in_seconds_cost);
    if (in_seconds_cost < 1) {
        return '0';
    } else if (in_seconds_cost < 3600) {
        //Show this in minutes:
        return Math.round((in_seconds_cost / 60)) + "m";
    } else {
        //Show in rounded hours:
        return Math.round((in_seconds_cost / 3600)) + "h";
    }
}

function in_cost_overview(seconds, in_id){
    return echo_js_hours(seconds) + ( parseFloat($('.t_estimate_' + in_id + ':first').attr('intent-usd')) > 0 ? '$' : '' );
}

function activate_expansion(){
    //Activate expansion for intent level 2 items that are not already expanded
    $('.is_level2_sortable').each(function () {
        if(!$(this).hasClass('is_expanded')){

            $(this).addClass('is_expanded').on('click', function(e) {

                if (e.target !== this){
                    if(jQuery.inArray("click_expand", e.target.classList) == -1){
                        return;
                    }
                }

                //Expand children:
                ms_toggle(parseInt($(this).attr('in-tr-id')), -1);
            });

        }
    });
}

$(document).ready(function () {

    if (is_compact) {

        //Adjust columns:
        $('.cols').removeClass('col-xs-6').addClass('col-sm-6');
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
                $("#modifybox, #load_messaging_frame").css('top', (top_position - 0)); //PX also set in style.css for initial load
            }, 34));
        });

    }




    //Activate expansion:
    activate_expansion();

    //Load top/bottom intent searches:
    in_load_search(".intentadder-level-2-top",    1, 2);
    in_load_search(".intentadder-level-2-bottom", 0, 2);


    //Expand level 3 search results:
    $('.new-in3-input .algolia_search').focus(function() {
        $('.in_pad_' + $(this).attr('intent-id') ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_' + $(this).attr('intent-id') ).addClass('hidden');
    });

    $('.intentadder-level-2-top').focus(function() {
        $('.in_pad_top' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_top' ).addClass('hidden');
    });

    $('.intentadder-level-2-bottom').focus(function() {
        $('.in_pad_bottom' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_bottom' ).addClass('hidden');
    });


    //Watch the expand/close all buttons:
    $('#expand_intents .expand_all').click(function (e) {
        $(".list-is-children .is_level2_sortable").each(function () {
            ms_toggle($(this).attr('in-tr-id'), 1);
        });
    });
    $('#expand_intents .close_all').click(function (e) {
        $(".list-is-children .is_level2_sortable").each(function () {
            ms_toggle($(this).attr('in-tr-id'), 0);
        });
    });

    //Load Sortable for level 2:
    in_sort_load(in_focus_id, 2);


    //Watch for intent status change:
    $("#in_status").change(function () {

        //Should we show the recursive button? Only if the status changes from the original one...
        if( parseInt($('#in_status').attr('original-status'))==parseInt(this.value)){
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

    //Lookout for intent link related changes:
    $('input[type=radio][name=ln_type_entity_id], #ln_status').change(function () {
        in_adjust_link_ui();
    });

    //Look for AND/OR changes:
    $('input[type=radio][name=in_type]').change(function () {
        in_adjust_isany_ui();
    });


    //Activate sorting for level 3 intents:
    if ($('.step-group').length) {

        $(".step-group").each(function () {

            var in_id = parseInt($(this).attr('intent-id'));

            //Load sorting for level 3 intents:
            in_sort_load(in_id, 3);

            //Load time:
            $('.t_estimate_' + in_id).text(in_cost_overview($('.t_estimate_' + in_id + ':first').attr('tree-max-seconds'), in_id));

        });

        if ($('.is_level3_sortable').length) {
            //Goo through all Steps:
            $(".is_level3_sortable").each(function () {
                var in_id = $(this).attr('intent-id');
                if (in_id) {
                    //Load time:
                    $('.t_estimate_' + in_id).text(in_cost_overview($('.t_estimate_' + in_id + ':first').attr('tree-max-seconds'), in_id));
                }
            });
        }
    }





    //Do we need to auto load anything?
    if (window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if (hash_parts.length >= 2) {
            //Fetch level if available:
            if (hash_parts[0] == 'intentmessages') {
                in_messages_iframe(hash_parts[1]);
            } else if (hash_parts[0] == 'loadmodify') {
                in_modify_load(hash_parts[1], hash_parts[2]);
            } else if (hash_parts[0] == 'loadinactionplans') {
                in_actionplans(hash_parts[1]);
            }
        }
    }


});


function in_actionplans(in_id){
    if(parseInt($('.actionplans_in_'+in_id).attr('ap-count')) < 1){
        alert('Intent not added to any Action Plans yet');
        return false;
    }
}


function in_adjust_isany_ui() {
    if ($('#in_type_0').is(':checked')) {
        //Unlock settings:
        $('#in_requirement_entity_id').prop('disabled', false);
    } else {
        //Any is selected, lock the completion settings as its not allowed for ANY Branches:
        $('#in_requirement_entity_id').val(6087).prop('disabled', 'disabled');
    }
}


function in_adjust_link_ui() {

    //Fetch intent link ID:
    var ln_id = parseInt($('#modifybox').attr('intent-tr-id'));

    if (!$('#modifybox').hasClass('hidden') && ln_id > 0) {

        //Yes show that section:
        $('.in-has-tr').removeClass('hidden');
        $('.in-no-tr').addClass('hidden');

        //What's the selected intent status?
        if (parseInt($('#ln_status').find(":selected").val()) < 0) {
            //About to delete? Notify them:
            $('.notify_unlink_in').removeClass('hidden');
        } else {
            $('.notify_unlink_in').addClass('hidden');
        }

        //What's the intent link type?
        if ($('#ln_type_entity_id_4229').is(':checked')) {
            //Conditional link is checked:
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


function in_load_search(focus_element, is_in_parent, next_in_level) {

    //Loads the intent search bar only once for the add intent inputs
    if($(focus_element).hasClass('search-bar-loaded')){
        //Already loaded:
        return false;
    }

    //Not yet loaded, continue with loading it:
    $(focus_element).addClass('search-bar-loaded').on('autocomplete:selected', function (event, suggestion, dataset) {

        in_link_or_create($(this).attr('intent-id'), is_in_parent, next_in_level, suggestion.alg_obj_id);

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_index.search(q, {
                filters: 'alg_obj_is_in=1',
                hitsPerPage: 7,
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
                return echo_js_suggestion(suggestion, 0);
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:in_link_or_create(' + parseInt($(focus_element).attr('intent-id')) + ','+is_in_parent+','+next_in_level+')" class="suggestion"><span><i class="fal fa-plus-circle add-plus"></i></span> <b>' + data.query + '</b></a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:in_link_or_create(' + parseInt($(focus_element).attr('intent-id')) + ','+is_in_parent+','+next_in_level+')" class="suggestion"><span><i class="fal fa-plus-circle add-plus"></i></span> <b>' + data.query + '</b></a>';
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return in_link_or_create($(this).attr('intent-id'), is_in_parent, next_in_level);
        }
    });

}


function in_sort_save(in_id, level) {

    if (level == 2) {
        var s_element = "list-in-" + in_focus_id + '-0';
        var s_draggable = ".is_level2_sortable";
    } else if (level == 3) {
        var s_element = "list-cr-" + $('.intent_line_' + in_id).attr('in-tr-id');
        var s_draggable = ".is_level3_sortable";
    } else {
        //Should not happen!
        return false;
    }

    //Fetch new sort:
    var new_ln_orders = [];
    var sort_rank = 0;

    $("#" + s_element + " " + s_draggable).each(function () {
        //Make sure this is NOT the dummy drag in box
        if (!$(this).hasClass('dropin-box')) {

            //Fetch variables for this intent:
            var in_id = parseInt($(this).attr('intent-id'));
            var ln_id = parseInt($(this).attr('in-tr-id'));

            sort_rank++;

            //Store in DB:
            new_ln_orders[sort_rank] = ln_id;
        }
    });


    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0 && in_id) {
        //Update backend:
        $.post("/intents/in_sort_save", {in_id: in_id, new_ln_orders: new_ln_orders}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert('ERROR: ' + data.message);
            }
        });
    }
}


function in_sort_load(in_id, level) {

    if (level == 2) {
        var element_key = null;
        var s_element = "list-in-" + in_focus_id + '-0';
        var s_draggable = ".is_level2_sortable";
    } else if (level == 3) {
        var element_key = '.intent_line_' + in_id;
        var s_element = "list-cr-" + $(element_key).attr('in-tr-id');
        var s_draggable = ".is_level3_sortable";
    } else {
        //Invalid level, should not happen!
        return false;
    }

    var theobject = document.getElementById(s_element);

    if (!theobject) {
        //Likely due to duplicate intents belonging in this tree!

        //Show general error:
        $('#in_children_errors').html("<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Error: Detected duplicate intents! Fix & refresh.</div>");

        //Show specific error:
        if (element_key) {
            $("<div class=\"act-error\"><i class=\"fas fa-exclamation-triangle\"></i> Note: Duplicate intent detected</div>").prependTo(element_key);
        }

        return false;
    }

    var settings = {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: s_draggable, // Specifies which items inside the element should be sortable
        handle: ".enable-sorting", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            in_sort_save(in_id, level);
        }
    };


    //Enable moving level 3 intents between level 2 intents:
    if (level == "3") {

        settings['group'] = "steplists";
        settings['ghostClass'] = "drop-step-here";
        settings['onAdd'] = function (evt) {

            //Define variables:
            var inputs = {
                ln_id: parseInt(evt.item.attributes['in-tr-id'].nodeValue),
                in_id: parseInt(evt.item.attributes['intent-id'].nodeValue),
                from_in_id: parseInt(evt.from.attributes['intent-id'].value),
                to_in_id: parseInt(evt.to.attributes['intent-id'].value),
            };

            //Update:
            $.post("/intents/in_migrate", inputs, function (data) {
                //Update sorts in both lists:
                if (!data.status) {

                    //There was some sort of an error returned!
                    alert('ERROR: ' + data.message);

                } else {

                    //All good as expected!
                    //Moved the parent pointer:
                    $('.intent_line_' + inputs.in_id).attr('parent-intent-id', inputs.to_in_id);

                    //Determine core variables for hour move calculations:
                    var step_hours = parseFloat($('.t_estimate_' + inputs.in_id + ':first').attr('tree-max-seconds'));
                    var intent_count = parseInt($('.children-counter-' + inputs.in_id + ':first').text());

                    if (!(step_hours == 0)) {
                        //Remove from old one:
                        var from_hours_new = parseFloat($('.t_estimate_' + inputs.from_in_id + ':first').attr('tree-max-seconds')) - step_hours;
                        $('.t_estimate_' + inputs.from_in_id).attr('tree-max-seconds', from_hours_new).text(echo_js_hours(from_hours_new));
                        $('.children-counter-' + inputs.from_in_id).text(parseInt($('.children-counter-' + inputs.from_in_id + ':first').text()) - intent_count);

                        //Add to new:
                        var to_hours_new = parseFloat($('.t_estimate_' + inputs.to_in_id + ':first').attr('tree-max-seconds')) + step_hours;
                        $('.t_estimate_' + inputs.to_in_id).attr('tree-max-seconds', to_hours_new).text(echo_js_hours(to_hours_new));
                        $('.children-counter-' + inputs.to_in_id).text(parseInt($('.children-counter-' + inputs.to_in_id + ':first').text()) + intent_count);
                    }

                    //Update sorting for both lists:
                    in_sort_save(inputs.from_in_id, 3);
                    in_sort_save(inputs.to_in_id, 3);

                }
            });

        };
    }

    var sort = Sortable.create(theobject, settings);
}


function in_messages_iframe(in_id) {

    //Start loading:
    $('.fixed-box').addClass('hidden');
    $('.frame-loader').addClass('hidden');
    $('#load_messaging_frame').removeClass('hidden').hide().fadeIn();
    //Set title:
    $('#tr_title').html('<i class="fas fa-comment-plus"></i> ' + $('.in_outcome_' + in_id + ':first').text());

    //Load content via a URL:
    $('.ajax-frame').attr('src', '/intents/in_messages_iframe/' + in_id).removeClass('hidden').css('margin-top', '0');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();
}

function adjust_js_ui(in_id, level, new_hours, intent_deficit_count=0, apply_to_tree=0, skip_intent_adjustments=0, usd_cost=0) {

    intent_deficit_count = parseInt(intent_deficit_count);
    var in_seconds_cost = parseFloat($('.t_estimate_' + in_id + ':first').attr('intent-seconds'));
    var in__tree_seconds = parseFloat($('.t_estimate_' + in_id + ':first').attr('tree-max-seconds'));
    var in_deficit_seconds = new_hours - (skip_intent_adjustments ? 0 : (apply_to_tree ? in__tree_seconds : in_seconds_cost));

    //Adjust same level hours:
    if (!skip_intent_adjustments) {
        var in_new__tree_seconds = in__tree_seconds + in_deficit_seconds;
        $('.t_estimate_' + in_id)
            .attr('tree-max-seconds', in_new__tree_seconds)
            .attr('intent-usd', usd_cost)
            .text(in_cost_overview(in_new__tree_seconds, in_id));

        if (!apply_to_tree) {
            $('.t_estimate_' + in_id).attr('intent-seconds', new_hours).text(in_cost_overview(in_new__tree_seconds, in_id));
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
        var in_parent__tree_seconds = parseFloat($('.t_estimate_' + in_parent_id + ':first').attr('tree-max-seconds'));
        var in_new_parent__tree_seconds = in_parent__tree_seconds + in_deficit_seconds;

        if (!(intent_deficit_count == 0)) {
            $('.children-counter-' + in_parent_id).text(parseInt($('.children-counter-' + in_parent_id + ':first').text()) + intent_deficit_count);
        }

        //Update Hours (Either level 1 or 2):
        $('.t_estimate_' + in_parent_id)
            .attr('tree-max-seconds', in_new_parent__tree_seconds)
            .text(echo_js_hours(in_new_parent__tree_seconds));


        if (level == 3) {
            //Adjust top level intent as well:
            var in_top_level = parseInt($('.intent_line_' + in_parent_id).attr('parent-intent-id'));
            var in_primary__tree_seconds = parseFloat($('.t_estimate_' + in_top_level + ':first').attr('tree-max-seconds'));
            var in_new__tree_seconds = in_primary__tree_seconds + in_deficit_seconds;

            if (!(intent_deficit_count == 0)) {
                $('.children-counter-' + in_top_level).text(parseInt($('.children-counter-' + in_top_level + ':first').text()) + intent_deficit_count);
            }

            //Update Hours:
            $('.t_estimate_' + in_top_level)
                .attr('tree-max-seconds', in_new__tree_seconds)
                .text(echo_js_hours(in_new__tree_seconds));
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
            $('#in_outcome').val(data.in.in_outcome);
            $('#in_type_' + data.in.in_type).prop("checked", true);
            $('#in_dollar_cost').val(data.in.in_dollar_cost);
            $('#in_seconds_cost').val(data.in.in_seconds_cost);
            $('#in_requirement_entity_id').val(data.in.in_requirement_entity_id);
            $('.tr_in_link_title').text('');

            $('#in_status').val(data.in.in_status).attr('original-status', data.in.in_status); //Set the status before it gets changed by miners

            //Status locked intent?
            if(jQuery.inArray(in_id, in_status_locked) !== -1){

                //Yes, lock status:
                $('#in_status').prop('disabled', true);

                //Show publish lock:
                $('.in_status_lock').removeClass('hidden');


            } else {

                //Nope, unlock status:
                $('#in_status').prop('disabled', false);

                //Hide publish lock:
                $('.in_status_lock').addClass('hidden');

            }

            //Load intent link data if available:
            if (ln_id > 0) {

                //Always load:
                $("#ln_status").val(data.ln.ln_status);
                $('#tr__conditional_score_min').val(data.ln.ln_metadata.tr__conditional_score_min);
                $('#tr__conditional_score_max').val(data.ln.ln_metadata.tr__conditional_score_max);
                $('#tr__assessment_points').val(data.ln.ln_metadata.tr__assessment_points);

                //Link editing adjustments:
                $('#tr_in_link_update').val(data.ln.in_outcome);
                $('.tr_in_link_title').text(( $('.intent_line_' + in_id).hasClass('parent-intent') ? 'Child' : 'Parent' ));

                //Is this a conditional link? If so, load the min/max range:
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
            in_adjust_isany_ui();
            in_adjust_link_ui();

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

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
    var tr_in_focus_ids = [ in_focus_id ];
    $(".level2_in").each(function () {
        tr_in_focus_ids.push(parseInt($(this).attr('intent-id')));
    });

    //Prepare data to be modified for this intent:
    var in_id = parseInt($('#modifybox').attr('intent-id'));
    var modify_data = {
        in_id: in_id,
        level: parseInt($('#modifybox').attr('level')),
        in_outcome: $('#in_outcome').val(),
        in_status: parseInt($('#in_status').val()),
        in_seconds_cost: ( $('#in_seconds_cost').val().length > 0 ? parseInt($('#in_seconds_cost').val()) : 0 ),
        in_requirement_entity_id: parseInt($('#in_requirement_entity_id').val()),
        in_dollar_cost: ( $('#in_dollar_cost').val().length > 0 ? parseFloat($('#in_dollar_cost').val()) : 0 ),
        in_type: parseInt($('input[name=in_type]:checked').val()),
        apply_recursively: (document.getElementById('apply_recursively').checked ? 1 : 0),
        is_parent: ( $('.intent_line_' + in_id).hasClass('parent-intent') ? 1 : 0 ),
        //Link variables:
        ln_id: parseInt($('#modifybox').attr('intent-tr-id')), //Will be zero for Level 1 intent!
        tr_in_focus_ids: tr_in_focus_ids,
        ln_type_entity_id: null,
        tr_in_link_update: null,
        tr__conditional_score_min: null,
        tr__conditional_score_max: null,
        tr__assessment_points: null,
    };

    //Do we have the intent Link?
    if (modify_data['ln_id'] > 0) {

        modify_data['ln_status'] = parseInt($('#ln_status').val());
        modify_data['ln_type_entity_id'] = parseInt($('input[name=ln_type_entity_id]:checked').val());
        modify_data['tr_in_link_update'] = $('#tr_in_link_update').val();

        if(modify_data['ln_type_entity_id'] == 4229){ //Conditional Intent Link
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

                    //Adjust completion cost:
                    adjust_js_ui(modify_data['in_id'], modify_data['level'], 0, data.in__tree_in_active_count, 1);

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

                    $('.ln_status_' + modify_data['ln_id']).html('<span class="ln_status_val" data-toggle="tooltip" data-placement="right" title="'+ object_js_statuses['ln_status'][modify_data['ln_status']]["s_name"] + ': '+ object_js_statuses['ln_status'][modify_data['ln_status']]["s_desc"] + '">'+ object_js_statuses['ln_status'][modify_data['ln_status']]["s_icon"] +'</span>');

                    //Update Assessment
                    $(".in_assessment_" + modify_data['ln_id']).html(( modify_data['ln_type_entity_id']==4228 ? ( modify_data['tr__assessment_points'] != 0 ? ( modify_data['tr__assessment_points'] > 0 ? '+' : '' ) + modify_data['tr__assessment_points'] : '' ) : modify_data['tr__conditional_score_min'] + ( modify_data['tr__conditional_score_min']==modify_data['tr__conditional_score_max'] ? '' : '-' + modify_data['tr__conditional_score_max'] ) + '%' ));

                }


                //Update UI components:

                //Did the outcome change?
                if(data.formatted_in_outcome){
                    //yes, update it:
                    $(".in_outcome_" + modify_data['in_id']).html(data.formatted_in_outcome);

                    //Set title:
                    $('.edit-header').html('<i class="fas fa-cog"></i> ' + modify_data['in_outcome']);

                    //Also update possible child icons:
                    $('.in_icon_child_' + modify_data['in_id']).attr('data-original-title', modify_data['in_outcome']);
                }



                //Always update 3x Intent icons:
                $('.in_type_' + modify_data['in_id']).html('<span class="in_type_val" data-toggle="tooltip" data-placement="right" title="'+ object_js_statuses['in_type'][modify_data['in_type']]["s_name"] + ': '+ object_js_statuses['in_type'][modify_data['in_type']]["s_desc"] + '">'+ object_js_statuses['in_type'][modify_data['in_type']]["s_icon"] +'</span>');

                //Also update possible child icons:
                $('.in_icon_child_' + modify_data['in_id']).html(object_js_statuses['in_type'][modify_data['in_type']]["s_icon"]);


                $('.in_status_' + modify_data['in_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ object_js_statuses['in_status'][modify_data['in_status']]["s_name"] + ': '+ object_js_statuses['in_status'][modify_data['in_status']]["s_desc"] + '">'+ object_js_statuses['in_status'][modify_data['in_status']]["s_icon"] +'</span>');

                $('.in_completion_' + modify_data['in_id']).html(( modify_data['in_requirement_entity_id'] == 6087 ? '' : en_all_4331[modify_data['in_requirement_entity_id']]["m_name"] ));

                //Update UI to confirm with user:
                $('.save_intent_changes').html(data.message).hide().fadeIn();

                //Adjust completion cost:
                adjust_js_ui(modify_data['in_id'], modify_data['level'], modify_data['in_seconds_cost'], 0, 0, 0, modify_data['in_dollar_cost']); //intent-usd

            }

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

            //What's the final action?
            setTimeout(function () {
                if (modify_data['apply_recursively'] && data.status_update_children > 0) {
                    //Refresh page soon to show new status for children:
                    window.location = "/intents/" + in_focus_id;
                } else {
                    $('.save_intent_changes').html(' ');
                }
            }, 1597);

        }
    });

}


function in_link_or_create(in_parent_id, is_parent, next_level, in_link_child_id=0) {

    /*
     *
     * Either creates an intent link between in_parent_id & in_link_child_id
     * OR will create a new intent based on input text and then link it
     * to in_parent_id (In this case in_link_child_id=0)
     *
     * */

    if (next_level == 2) {
        var sort_handler = ".is_level2_sortable";
        var sort_list_id = "list-in-" + in_focus_id + '-' + is_parent;
        var input_field = $('#addintent-c-' + in_parent_id + '-' + is_parent);
    } else if (next_level == 3) {
        var sort_handler = ".is_level3_sortable";
        var sort_list_id = "list-cr-" + $('.intent_line_' + in_parent_id).attr('in-tr-id');
        var input_field = $('.intentadder-id-' + in_parent_id);
    } else {
        //This should not happen:
        alert('Invalid next_level value [' + next_level + ']');
        return false;
    }


    var intent_name = input_field.val();

    //We either need the intent name (to create a new intent) or the in_link_child_id>0 to create an intent link:
    if (!in_link_child_id && intent_name.length < 1) {
        alert('Error: Missing Intent for level ['+next_level+']. Try Again...' + '.intentadder-id-' + in_parent_id);
        input_field.focus();
        return false;
    }

    //Set processing status:
    add_to_list(sort_list_id, sort_handler, '<div id="temp' + next_level + '" class="list-group-item"><i class="fas fa-spinner fa-spin"></i> Adding... </div>');

    //Update backend:
    $.post("/intents/in_link_or_create", {
        in_parent_id: in_parent_id,
        is_parent:is_parent,
        in_outcome: intent_name,
        next_level: next_level,
        in_link_child_id: in_link_child_id
    }, function (data) {

        //Remove loader:
        $("#temp" + next_level).remove();

        if (data.status) {

            //Add new
            add_to_list(sort_list_id, sort_handler, data.in_child_html);

            //Reload sorting to enable sorting for the newly added intent:
            in_sort_load(in_parent_id, next_level);

            if (next_level == 2) {

                if(!is_parent){
                    //Adjust the Step count:
                    in_sort_save(0, 2);
                }

                //Reload sorting to enable sorting for the newly added intent:
                in_sort_load(data.in_child_id, 3);

                //Load search again:
                in_load_search(".intentadder-id-"+data.in_child_id, 0, 3);

            } else if(!is_parent) {

                //Adjust Intent Level 3 sorting:
                in_sort_save(in_parent_id, next_level);

            }

            //Activate expansion:
            activate_expansion();

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Adjust time:
            adjust_js_ui(data.in_child_id, next_level, data.in__tree_max_seconds, data.in__tree_in_active_count, 0, 1);

        } else {
            //Show errors:
            alert('ERROR: ' + data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}