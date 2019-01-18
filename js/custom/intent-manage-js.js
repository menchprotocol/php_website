//This also has an equal PHP function fn___echo_time_hours() which we want to make sure has more/less the same logic:
function echo_js_hours(in_seconds) {
    in_seconds = parseInt(in_seconds);
    if (in_seconds < 1) {
        return '0';
    } else if (in_seconds < 3600) {
        //Show this in minutes:
        return Math.round((in_seconds / 60)) + "m";
    } else {
        //Show in rounded hours:
        return Math.round((in_seconds / 3600)) + "h";
    }
}

$(document).ready(function () {

    if (is_compact) {

        //Adjust columns:
        $('.cols').removeClass('col-xs-6').addClass('col-sm-6');
        $('.fixed-box').addClass('release-fixture');

    } else {

        //Adjust height of the messaging windows:
        $('.grey-box').css('max-height', (parseInt($(window).height()) - 130) + 'px');

        //Make editing frames Sticky for scrolling longer lists
        $(".main-panel").scroll(function () {
            var top_position = $(this).scrollTop();
            clearTimeout($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function () {
                $("#modifybox").css('top', (top_position - 0)); //PX also set in style.css for initial load
            }, 34));
        });
    }


    //Do we need to auto load anything?
    if (window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if (hash_parts.length >= 2) {
            //Fetch level if available:
            if (hash_parts[0] == 'loadmessages') {
                in_messages_load(hash_parts[1]);
            } else if (hash_parts[0] == 'loadmodify') {
                in_modify_load(hash_parts[1], hash_parts[2]);
            } else if (hash_parts[0] == 'loadlinks') {
                in_tr_load(hash_parts[1],hash_parts[2],hash_parts[3]);
            }
        }
    }


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
        if(parseInt(this.value) < 0){
            $('.notify_in_remove').removeClass('hidden');
        } else {
            $('.notify_in_remove').addClass('hidden');
        }
    });

    $('input[type=radio][name=in_is_any]').change(function () {
        in_adjust_isany_ui();
    });

    //Lookout for intent link related changes:
    $('input[type=radio][name=tr_en_type_id], #tr_status').change(function () {
        in_adjust_link_ui();
    });


    //Activate sorting for level 3 intents:
    if ($('.step-group').length) {

        $(".step-group").each(function () {

            var in_id = parseInt($(this).attr('intent-id'));

            //Load sorting for level 3 intents:
            in_sort_load(in_id, 3);

            //Load time:
            $('.t_estimate_' + in_id).text(echo_js_hours($('.t_estimate_' + in_id + ':first').attr('tree-max-seconds')));

        });

        if ($('.is_level3_sortable').length) {
            //Goo through all Steps:
            $(".is_level3_sortable").each(function () {
                var in_id = $(this).attr('intent-id');
                if (in_id) {
                    //Load time:
                    $('.t_estimate_' + in_id).text(echo_js_hours($('.t_estimate_' + in_id + ':first').attr('tree-max-seconds')));
                }
            });
        }
    }


    $("#add_in_btn").click(function () {
        //miner clicked on the add new intent button at level 2:
        fn___in_link_or_create(in_focus_id, 2);
    });


    //Load Algolia:
    $(".intentadder-level-2").on('autocomplete:selected', function (event, suggestion, dataset) {

        fn___in_link_or_create($(this).attr('intent-id'), 2, suggestion.in_id);

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_c_index.search(q, {
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
                return echo_js_suggestion('in',suggestion);
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:fn___in_link_or_create(\'' + $(".intentadder-level-2").attr('intent-id') + '\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-hashtag"></i> ' + data.query + '</a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:fn___in_link_or_create(\'' + $(".intentadder-level-2").attr('intent-id') + '\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-hashtag"></i> ' + data.query + '</a>';
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return fn___in_link_or_create($(this).attr('intent-id'), 2);
        }
    });

    //Load level 3 sorting for this new level 2 intent:
    in_load_search_level3();

});


function in_adjust_isany_ui() {
    if ($('#in_is_any_0').is(':checked')) {
        //Unlock settings:
        $('.and-settings').removeClass('hidden');
        $('.or-settings').addClass('hidden');
    } else {
        //Any is selected, lock the completion settings as its not allowed for ANY Branches:
        $('.in_input_requirements').prop('checked', false);
        $('.and-settings').addClass('hidden');
        $('.or-settings').removeClass('hidden');
    }
}


function in_adjust_link_ui() {

    //Fetch intent link ID:
    var tr_id = parseInt($('#modifybox').attr('intent-tr-id'));

    if (!$('#modifybox').hasClass('hidden') && tr_id > 0) {

        //Yes show that section:
        $('.in-has-tr').removeClass('hidden');
        $('.in-no-tr').addClass('hidden');

        //What's the selected intent status?
        if (parseInt($('#tr_status').find(":selected").val()) < 0) {
            //About to delete? Notify them:
            $('.notify_in_unlink').removeClass('hidden');
        } else {
            $('.notify_in_unlink').addClass('hidden');
        }

        //What's the intent link type?
        if ($('#tr_en_type_id_4229').is(':checked')) {
            //Conditional link is checked:
            $('.score_range_box').removeClass('hidden');
        } else {
            //Any is selected, lock the completion settings as its not allowed for ANY Branches:
            $('.score_range_box').addClass('hidden');
        }

    } else {
        //Main intent, no link, so hide entire section:
        $('.in-has-tr').addClass('hidden');
        $('.in-no-tr').removeClass('hidden');
    }
}

function in_load_search_level3() {

    $(".intentadder-level-3").on('autocomplete:selected', function (event, suggestion, dataset) {

        fn___in_link_or_create($(this).attr('intent-id'), 3, suggestion.in_id);

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_c_index.search(q, {
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
                return echo_js_suggestion('in',suggestion);
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:fn___in_link_or_create(\'' + $(".intentadder-level-3").attr('intent-id') + '\',3)" class="suggestion"><span><i class="fas fa-plus-circle"></i></span> ' + data.query + '</a>';
                }
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return fn___in_link_or_create($(this).attr('intent-id'), 3);
        }
    });

}


function in_sort_save(in_id, level) {

    if (level == 2) {
        var s_element = "list-in-" + in_focus_id;
        var s_draggable = ".is_level2_sortable";
    } else if (level == 3) {
        var s_element = "list-cr-" + $('.intent_line_' + in_id).attr('in-tr-id');
        var s_draggable = ".is_level3_sortable";
    } else {
        //Should not happen!
        return false;
    }

    //Fetch new sort:
    var new_tr_orders = [];
    var sort_rank = 0;

    $("#" + s_element + " " + s_draggable).each(function () {
        //Make sure this is NOT the dummy drag in box
        if (!$(this).hasClass('dropin-box')) {

            //Fetch variables for this intent:
            var in_id = parseInt($(this).attr('intent-id'));
            var tr_id = parseInt($(this).attr('in-tr-id'));

            sort_rank++;

            //Store in DB:
            new_tr_orders[sort_rank] = tr_id;

            //Update sort handler:
            $(".intent_line_" + in_id + " .inline-level-" + level).html('#' + sort_rank);
        }
    });


    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0 && in_id) {
        //Update backend:
        $.post("/intents/in_sort_save", {in_id: in_id, new_tr_orders: new_tr_orders}, function (data) {
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
        var s_element = "list-in-" + in_focus_id;
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
            $("<div class=\"act-error\"><i class=\"fas fa-exclamation-triangle\"></i> Error: Duplicate intent! Only keep 1 & refresh.</div>").prependTo(element_key);
        }

        return false;
    }

    var settings = {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: s_draggable, // Specifies which items inside the element should be sortable
        handle: ".double-sort", // Restricts sort start click/touch to the specified element
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
                tr_id: parseInt(evt.item.attributes['in-tr-id'].nodeValue),
                in_id: parseInt(evt.item.attributes['intent-id'].nodeValue),
                from_in_id: parseInt(evt.from.attributes['intent-id'].value),
                to_in_id: parseInt(evt.to.attributes['intent-id'].value),
            };

            //Update:
            $.post("/intents/fn___in_migrate", inputs, function (data) {
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


function in_messages_load(in_id) {
    //Start loading:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $('#load_w_frame, .frame-loader').removeClass('hidden').hide().fadeIn();
    //Set title:
    $('#w_title').html('<i class="fas fa-comment-dots"></i> ' + $('.in_outcome_' + in_id + ':first').text());

    //Load content via a URL:
    $('.frame-loader').addClass('hidden');
    $('.ajax-frame').attr('src', '/intents/in_messages_load/' + in_id).removeClass('hidden').css('margin-top', '0');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();
}


function in_tr_load(in_id, tr_id, tr_en_type_id) {
    //Start loading:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $('#load_w_frame, .frame-loader').removeClass('hidden').hide().fadeIn();

    //Set title:
    $('#w_title').html('<i class="fas fa-atlas"></i> ' + $('.in_outcome_' + in_id + ':first').text());

    //Load content via a URL:
    $('.frame-loader').addClass('hidden');
    $('.ajax-frame').attr('src', '/intents/in_tr_load/' + in_id + '/' + tr_id + '/' + tr_en_type_id).removeClass('hidden').css('margin-top', '0');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();
}

function adjust_js_ui(in_id, level, new_hours, intent_deficit_count=0, apply_to_tree=0, skip_intent_adjustments=0) {

    intent_deficit_count = parseInt(intent_deficit_count);
    var in_seconds = parseFloat($('.t_estimate_' + in_id + ':first').attr('intent-seconds'));
    var in__tree_seconds = parseFloat($('.t_estimate_' + in_id + ':first').attr('tree-max-seconds'));
    var in_deficit_seconds = new_hours - (skip_intent_adjustments ? 0 : (apply_to_tree ? in__tree_seconds : in_seconds));

    if (in_deficit_seconds == 0 && intent_deficit_count == 0) {
        //Nothing changed, so we need to do nothing either!
        return false;
    }

    //Adjust same level hours:
    if (!skip_intent_adjustments) {
        var in_new__tree_seconds = in__tree_seconds + in_deficit_seconds;
        $('.t_estimate_' + in_id)
            .attr('tree-max-seconds', in_new__tree_seconds)
            .text(echo_js_hours(in_new__tree_seconds));

        if (!apply_to_tree) {
            $('.t_estimate_' + in_id).attr('intent-seconds', new_hours).text(echo_js_hours(in_new__tree_seconds));
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

        if (!(in_deficit_seconds == 0)) {
            //Update Hours (Either level 1 or 2):
            $('.t_estimate_' + in_parent_id)
                .attr('tree-max-seconds', in_new_parent__tree_seconds)
                .text(echo_js_hours(in_new_parent__tree_seconds));
        }

        if (level == 3) {
            //Adjust top level intent as well:
            var in_tactic_id = parseInt($('.intent_line_' + in_parent_id).attr('parent-intent-id'));
            var in_primary__tree_seconds = parseFloat($('.t_estimate_' + in_tactic_id + ':first').attr('tree-max-seconds'));
            var in_new__tree_seconds = in_primary__tree_seconds + in_deficit_seconds;

            if (!(intent_deficit_count == 0)) {
                $('.children-counter-' + in_tactic_id).text(parseInt($('.children-counter-' + in_tactic_id + ':first').text()) + intent_deficit_count);
            }

            if (!(in_deficit_seconds == 0)) {
                //Update Hours:
                $('.t_estimate_' + in_tactic_id)
                    .attr('tree-max-seconds', in_new__tree_seconds)
                    .text(echo_js_hours(in_new__tree_seconds));
            }
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


function in_modify_load(in_id, tr_id) {

    //Indicate Loading:
    $('#modifybox .grey-box .loadcontent').addClass('hidden');
    $('#modifybox .grey-box .loadbox').removeClass('hidden');
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();
    $('#modifybox').attr('intent-tr-id', 0).attr('intent-id', 0).attr('level', 0);

    //Fetch Intent Data to load modify widget:
    $.post("/intents/fn___in_load_data", {in_id: in_id, tr_id: tr_id}, function (data) {
        if (!data.status) {

            //Opppsi, show the error:
            alert('Error Loading Intent: ' + data.message);

        } else {

            //All good, let's load the data into the Modify Widget...

            //Update variables:
            var level = (tr_id == 0 ? 1 : parseInt($('.in__tr_' + tr_id).attr('intent-level'))); //Either 1, 2 or 3
            $('#modifybox').attr('intent-tr-id', tr_id);
            $('#modifybox').attr('intent-id', in_id);
            $('#modifybox').attr('level', level);

            //Load inputs:
            $('#in_outcome').val(data.in.in_outcome);
            $('#in_alternatives').val(data.in.in_alternatives);
            $('#in_is_any_' + data.in.in_is_any).prop("checked", true);
            $('.in_input_requirements').removeAttr('checked'); //Uncheck all
            if (data.in_req_ens.length > 0) { //Check current ones:
                for (var i = 0; i < data.in_req_ens.length; i++) {
                    $('#require__' + data.in_req_ens[i]).attr('checked', 'checked');
                }
            }
            $('#in_status').val(data.in.in_status);
            $('#apply_recursively').removeAttr('checked'); //Always remove
            $('#in_usd').val(data.in.in_usd);
            $('#in_seconds').val(data.in.in_seconds);
            $('#in_webhook').val(data.in.in_webhook);
            $('#in_points').val(data.in.in_points);
            $('#in_points').val(data.in.in_points);
            $('.save_intent_changes').html(data.in.in___last_updated); //Load Last Updated box

            //Load intent link data if available:
            if (tr_id > 0) {

                //Always load:
                $("#tr_status").val(data.tr.tr_status);
                $('#tr__conditional_score_min').val(data.tr.tr_metadata.tr__conditional_score_min);
                $('#tr__conditional_score_max').val(data.tr.tr_metadata.tr__conditional_score_max);

                //Is this a conditional link? If so, load the min/max range:
                if (data.tr.tr_en_type_id == 4229) {
                    //Yes, load the data (which must be there):
                    $('#tr_en_type_id_4229').prop("checked", true);
                } else {
                    //Fixed link:
                    $('#tr_en_type_id_4228').prop("checked", true);
                }

                $('.tr-last-updated').html(data.tr.tr___last_updated); //Load Last Updated box
            }

            //Make the frame visible:
            $('.notify_in_remove, .notify_in_unlink').addClass('hidden'); //Hide potential previous notices
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

    //Prepare data to be modified for this intent:
    var modify_data = {
        in_id: parseInt($('#modifybox').attr('intent-id')),
        tr_id: parseInt($('#modifybox').attr('intent-tr-id')), //Will be zero for Level 1 intent!
        level: parseInt($('#modifybox').attr('level')),
        in_outcome: $('#in_outcome').val(),
        in_status: parseInt($('#in_status').val()),
        in_seconds: parseInt($('#in_seconds').val()),
        in_usd: parseFloat($('#in_usd').val()),
        in_is_any: parseInt($('input[name=in_is_any]:checked').val()),
        apply_recursively: (document.getElementById('apply_recursively').checked ? 1 : 0),
        in_points: parseInt($('#in_points').val()),
        in_alternatives: $('#in_alternatives').val().replace(/\"/g, ""), //Remove double quotes
        in_webhook: $('#in_webhook').val(),
        input_requirements: [], //Remove double quotes
        tr__conditional_score_min: null, //Default
        tr__conditional_score_max: null, //Default
    };

    //Append requirements
    if ( !modify_data['in_is_any'] ) {
        $('.in_input_requirements').each(function (i, obj) {
            if (obj.checked) {
                modify_data['input_requirements'].push(parseInt($(this).attr('req-en-id')));
            }
        });
    }

    //Do we have the intent link Transaction?
    if (modify_data['tr_id'] > 0) {

        //TODO implement:
        var original_in_tr_type = parseInt($('.in__tr_' + modify_data['tr_id']).attr('in-tr-type'));

        modify_data['tr_status'] = parseInt($('#tr_status').val());
        modify_data['tr_en_type_id'] = parseInt($('input[name=tr_en_type_id]:checked').val());

        if(modify_data['tr_en_type_id'] == 4229){ //Conditional Intent Link
            //Fetch condition range:
            modify_data['tr__conditional_score_min'] = $('#tr__conditional_score_min').val();
            modify_data['tr__conditional_score_max'] = $('#tr__conditional_score_max').val();
        }
    }

    //Show spinner:
    $('.save_intent_changes').html('<span><i class="fas fa-spinner fa-spin"></i> Saving...</span>').hide().fadeIn();


    //Save the rest of the content:
    $.post("/intents/in_save_settings", modify_data, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_intent_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Has the intent/intent-link been archived? Either way, we need to hide this row:
            if (data.remove_in_from_ui) {

                //Intent has been either removed OR unlinked:
                if (modify_data['level'] == 1) {

                    //move up 1 level as this was the focus intent:
                    window.location = "/intents/" + ($('.intent_line_' + modify_data['in_id']).attr('parent-intent-id'));

                } else {

                    //Remove Hash:
                    window.location.hash = '#';

                    //Adjust hours:
                    adjust_js_ui(modify_data['in_id'], modify_data['level'], 0, data.in__tree_in_active_count, 1);

                    //Remove from UI:
                    $('.in__tr_' + modify_data['tr_id']).html('<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>');

                    //Hide the editor & saving results:
                    $('.in__tr_' + modify_data['tr_id']).fadeOut();

                    //Disappear in a while:
                    setTimeout(function () {

                        //Hide the editor & saving results:
                        $('.in__tr_' + modify_data['tr_id']).remove();

                        //Hide editing box:
                        $('#modifybox').addClass('hidden');

                        //Resort all Tasks to illustrate changes on UI:
                        in_sort_save(parseInt($('.intent_line_' + modify_data['in_id']).attr('parent-intent-id')), modify_data['level']);

                    }, 377);

                }

            } else {

                //Intent has not been updated:

                //Did the Transaction update?
                if (modify_data['tr_id'] > 0) {

                    //Did we update it?
                    if(data.tr_new_id > 0){
                        //Update classes with new transaction link:
                        $('.in_tr_type_' + modify_data['tr_id']).addClass('in_tr_type_' + data.tr_new_id).removeClass('in_tr_type_' + modify_data['tr_id']);
                        $('.tr_status_' + modify_data['tr_id']).addClass('tr_status_' + data.tr_new_id).removeClass('tr_status_' + modify_data['tr_id']);
                        $('.in__tr_' + modify_data['tr_id']).addClass('in__tr_' + data.tr_new_id).removeClass('in__tr_' + modify_data['tr_id']);

                        //Update Transaction ID:
                        modify_data['tr_id'] = data.tr_new_id;
                        $('#modifybox').attr('intent-tr-id', data.tr_new_id);

                        //Update Hash:
                        window.location.hash = '#loadmodify-'+modify_data['in_id']+'-'+modify_data['tr_id'];
                    }

                    $('.in_tr_type_' + modify_data['tr_id']).html('<span class="in_tr_type_val" data-toggle="tooltip" data-placement="right" title="'+ en_all_4486[modify_data['tr_en_type_id']]["m_name"] + ': '+ en_all_4486[modify_data['tr_en_type_id']]["m_desc"] + '">'+ en_all_4486[modify_data['tr_en_type_id']]["m_icon"] +'</span>');

                    $('.tr_status_' + modify_data['tr_id']).html('<span class="tr_status_val" data-toggle="tooltip" data-placement="right" title="'+ object_js_statuses['tr_status'][modify_data['tr_status']]["s_name"] + ': '+ object_js_statuses['tr_status'][modify_data['tr_status']]["s_desc"] + '">'+ object_js_statuses['tr_status'][modify_data['tr_status']]["s_icon"] +'</span>');

                    //Update transaction time:
                    $('.tr-last-updated').html(data.tr___last_updated).hide().fadeIn(); //Load Last Updated box
                }


                //Update UI components:
                $(".in_outcome_" + modify_data['in_id']).html(modify_data['in_outcome']);


                //Always update 2x Intent icons:
                $('.in_is_any_' + modify_data['in_id']).html('<span class="in_is_any_val" data-toggle="tooltip" data-placement="right" title="'+ object_js_statuses['in_is_any'][modify_data['in_is_any']]["s_name"] + ': '+ object_js_statuses['in_is_any'][modify_data['in_is_any']]["s_desc"] + '">'+ object_js_statuses['in_is_any'][modify_data['in_is_any']]["s_icon"] +'</span>');

                $('.in_status_' + modify_data['in_id']).html('<span class="in_status_val" data-toggle="tooltip" data-placement="right" title="'+ object_js_statuses['in_status'][modify_data['in_status']]["s_name"] + ': '+ object_js_statuses['in_status'][modify_data['in_status']]["s_desc"] + '">'+ object_js_statuses['in_status'][modify_data['in_status']]["s_icon"] +'</span>');



                //Update UI to confirm with user:
                $('.save_intent_changes').html(data.in___last_updated).hide().fadeIn();

                //Update range:
                $('.range_is_' + modify_data['in_id']).html(( modify_data['tr__conditional_score_min'] == null ? '' : modify_data['tr__conditional_score_min'] + '-' + modify_data['tr__conditional_score_max'] + '%' ));


                if(!modify_data['input_requirements'].length){
                    $('.input_requirements_' + modify_data['in_id']).html('');
                } else {
                    //Generate Icons:
                    var input_requirements_icons = '';

                    for (var i = 0; i < modify_data['input_requirements'].length; i++) {
                        //Append this icon:
                        input_requirements_icons += ' <span data-toggle="tooltip" data-placement="top" title="Accepts '+ en_all_4331[modify_data['input_requirements'][i]]["m_name"] + ' to be marked as complete">'+ en_all_4331[modify_data['input_requirements'][i]]["m_icon"] +'</span>';
                    }

                    $('.input_requirements_' + modify_data['in_id']).html(input_requirements_icons);
                }


                //Update trigger statements:
                if ($('.in_alternatives_' + modify_data['in_id']).length) {

                    //This is the top intent that's loaded, update expanded trigger UI:
                    $(".in_alternatives_" + modify_data['in_id']).html(nl2br(modify_data['in_alternatives']));

                } else {

                    //This is a level 2+ intent, let's update the tooltip UI:
                    if (modify_data['in_alternatives'].length > 0) {
                        $(".in_outcome_" + modify_data['in_id']).addClass('has-desc').attr('data-toggle', 'tooltip').attr('data-original-title', modify_data['in_alternatives']);
                    } else {
                        $(".in_outcome_" + modify_data['in_id']).removeClass('has-desc').attr('data-toggle', '').attr('data-original-title', '');
                    }

                }


                //Update other UI elements:
                $(".ui_in_points_" + modify_data['in_id']).html(modify_data['in_points']);
                $(".ui_in_usd_" + modify_data['in_id']).html((modify_data['in_usd'] > 0 ? '<i class="fal fa-usd-circle" data-toggle="tooltip" title="$'+ modify_data['in_usd'] +' USD in product purchase recommendations" data-placement="top"></i>' : ''));
                $(".in_setwebhook_" + modify_data['in_id']).html((modify_data['in_webhook'].length > 0 ? '<i class="fal fa-cloud-upload" data-toggle="tooltip" title="'+ modify_data['in_webhook'] +'" data-placement="top"></i>' : ''));


                //Adjust hours if needed:
                adjust_js_ui(modify_data['in_id'], modify_data['level'], modify_data['in_seconds']);

            }

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

            //What's the final action?
            setTimeout(function () {
                if (modify_data['apply_recursively'] && data.status_update_children > 0) {
                    //Refresh page soon to show new status for children:
                    window.location = "/intents/" + in_focus_id;
                }
            }, 610);

        }
    });

}


function fn___in_link_or_create(in_parent_id, next_level, in_link_child_id=0) {

    /*
     *
     * Either creates an intent link between in_parent_id & in_link_child_id
     * OR will create a new intent based on input text and then link it
     * to in_parent_id (In this case in_link_child_id=0)
     *
     * */

    if (next_level == 2) {
        var sort_handler = ".is_level2_sortable";
        var sort_list_id = "list-in-" + in_focus_id;
        var input_field = $('#addintent-c-' + in_parent_id);
    } else if (next_level == 3) {
        var sort_handler = ".is_level3_sortable";
        var sort_list_id = "list-cr-" + $('.intent_line_' + in_parent_id).attr('in-tr-id');
        var input_field = $('#addintent-cr-' + $('.intent_line_' + in_parent_id).attr('in-tr-id'));
    } else {
        //Ooooopsi, this should not happen:
        alert('Invalid next_level value [' + next_level + ']');
        return false;
    }


    var intent_name = input_field.val();

    //We either need the intent name (to create a new intent) or the in_link_child_id>0 to create an intent link:
    if (!in_link_child_id && intent_name.length < 1) {
        alert('Error: Missing Intent. Try Again...');
        input_field.focus();
        return false;
    }

    //Set processing status:
    add_to_list(sort_list_id, sort_handler, '<div id="temp' + next_level + '" class="list-group-item"><i class="fas fa-spinner fa-spin"></i> Adding... </div>');

    //Update backend:
    $.post("/intents/fn___in_link_or_create", {
        in_parent_id: in_parent_id,
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

            //Remove potential grey class:
            $('.tree-badge-' + in_parent_id).removeClass('grey');

            if (next_level == 2) {

                //Adjust the Task count:
                in_sort_save(0, 2);

                //Reload sorting to enable sorting for the newly added intent:
                in_sort_load(data.in_child_id, 3);

                //Load search again:
                in_load_search_level3();

            } else {

                //Adjust Intent Level 3 sorting:
                in_sort_save(in_parent_id, next_level);

            }

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