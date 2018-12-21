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
                in_tr_load(hash_parts[1]);
            } else if (hash_parts[0] == 'loadactionplans') {
                in_actionplans_load(hash_parts[1]);
            }
        }
    }


    //Watch the expand/close all buttons:
    $('#expand_intents .expand_all').click(function (e) {
        $(".list-is-children .is_level2_sortable").each(function () {
            ms_toggle($(this).attr('data-link-id'), 1);
        });
    });
    $('#expand_intents .close_all').click(function (e) {
        $(".list-is-children .is_level2_sortable").each(function () {
            ms_toggle($(this).attr('data-link-id'), 0);
        });
    });

    //Load Sortable for level 2:
    in_sort_load(in_focus_id, 2);


    $('input[type=radio][name=in_is_any]').change(function () {
        in_adjust_isany_ui();
    });

    $('#tr_status').change(function () {
        var tr_id = ($('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('intent-link-id')));
        in_adjust_link_ui(tr_id);
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
        fn___in_create_or_link(in_focus_id, 2);
    });


    //Load Algolia:
    $(".intentadder-level-2").on('autocomplete:selected', function (event, suggestion, dataset) {

        fn___in_create_or_link($(this).attr('intent-id'), 2, suggestion.in_id);

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
                var fancy_hours = fancy_time(suggestion);
                return '<span class="suggest-prefix"><i class="fas fa-hashtag"></i></span> ' + suggestion._highlightResult.in_outcome.value + (fancy_hours ? '<span class="search-info">' + (parseFloat(suggestion.in__count) > 1 ? ' <i class="fas fa-sitemap"></i> ' + suggestion.in__count : '') + ' <i class="fas fa-clock"></i> ' + fancy_hours + '</span>' : '');
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:fn___in_create_or_link(\'' + $(".intentadder-level-2").attr('intent-id') + '\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-hashtag"></i> ' + data.query + '</a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:fn___in_create_or_link(\'' + $(".intentadder-level-2").attr('intent-id') + '\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-hashtag"></i> ' + data.query + '</a>';
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return fn___in_create_or_link($(this).attr('intent-id'), 2);
        }
    });

    //Load level 3 sorting for this new level 2 intent:
    in_load_search_level3();

});


function in_adjust_isany_ui() {
    if ($('#in_is_any_0').is(':checked')) {
        //Unlock settings:
        $('.completion-settings').removeClass('hidden');
    } else {
        //Any is selected, lock the completion settings as its not allowed for ANY Branches:
        $('#c_require_notes_to_complete').prop('checked', false);
        $('#c_require_url_to_complete').prop('checked', false);
        $('.completion-settings').addClass('hidden');
    }
}


function in_adjust_link_ui(tr_id) {
    if (tr_id > 0) {
        //Yes show that section:
        $('#c_link_access').removeClass('hidden');

        //See which one needs to be checked:
        $('.notify_cr_delete').addClass('hidden');

        var selected_tr_status = parseInt($('#tr_status').find(":selected").val());
        if (selected_tr_status < 2) {
            $('.score_range_box').addClass('hidden');
            if (selected_tr_status < 0) {
                //About to delete? Notify them:
                $('.notify_cr_delete').removeClass('hidden');
            }
        } else {
            $('.score_range_box').removeClass('hidden');
        }

    } else {
        //No hide that section:
        $('#c_link_access').addClass('hidden');
    }
}

function in_load_search_level3() {

    $(".intentadder-level-3").on('autocomplete:selected', function (event, suggestion, dataset) {

        fn___in_create_or_link($(this).attr('intent-id'), 3, suggestion.in_id);

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
                var fancy_hours = fancy_time(suggestion);
                return '<span class="suggest-prefix"><i class="fas fa-hashtag"></i></span> ' + suggestion._highlightResult.in_outcome.value + (fancy_hours ? '<span class="search-info">' + (parseInt(suggestion.in__tree_in_count) > 1 ? ' <i class="' + (parseInt(suggestion.in_is_any) ? 'fas fa-code-merge' : 'fas fa-sitemap') + '"></i> ' + parseInt(suggestion.in__tree_in_count) : '') + ' <i class="fas fa-clock"></i> ' + fancy_hours + '</span>' : '');
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:fn___in_create_or_link(\'' + $(".intentadder-level-3").attr('intent-id') + '\',3)" class="suggestion"><span><i class="fas fa-plus-circle"></i></span> ' + data.query + '</a>';
                }
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return fn___in_create_or_link($(this).attr('intent-id'), 3);
        }
    });

}


function in_sort_save(in_id, level) {

    if (level == 2) {
        var s_element = "list-c-" + in_focus_id;
        var s_draggable = ".is_level2_sortable";
    } else if (level == 3) {
        var s_element = "list-cr-" + $('.intent_line_' + in_id).attr('data-link-id');
        var s_draggable = ".is_level3_sortable";
    } else {
        //Should not happen!
        return false;
    }

    //Fetch new sort:
    var new_tr_orders = [];
    var sort_rank = 0;
    var is_properly_sorted = true; //Assume good unless proven otherwise


    $("#" + s_element + " " + s_draggable).each(function () {
        //Make sure this is NOT the dummy drag in box
        if (!$(this).hasClass('dropin-box')) {

            //Fetch variables for this intent:
            var in_id = parseInt($(this).attr('intent-id'));
            var tr_id = parseInt($(this).attr('data-link-id'));

            sort_rank++;

            //Store in DB:
            new_tr_orders[sort_rank] = tr_id;

            //Is the Child rank correct? Check DB value:
            var db_rank = parseInt($('.in_outcome_' + in_id).attr('children-rank'));

            if (level == 2 && !(db_rank == sort_rank) && !in_id) {
                is_properly_sorted = false;
                console.log('Intent #' + in_id + ' detected out of sync.');
            }

            //Update sort handler:
            $("#cr_" + tr_id + " .inline-level-" + level).html('#' + sort_rank);
        }
    });


    if (level == 2 && !is_properly_sorted && !in_id) {
        //Sorting issue detected on Task load:
        in_id = parseInt(in_focus_id);
    }

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
        var s_element = "list-c-" + in_focus_id;
        var s_draggable = ".is_level2_sortable";
    } else if (level == 3) {
        var element_key = '.intent_line_' + in_id;
        var s_element = "list-cr-" + $(element_key).attr('data-link-id');
        var s_draggable = ".is_level3_sortable";
    } else {
        //Should not happen!
        return false;
    }

    var theobject = document.getElementById(s_element);

    if (!theobject) {
        //Likely due to duplicate intents belonging in this tree!

        //Show general error:
        $('#outs_error').html("<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Error: Detected duplicate intents! Fix & refresh.</div>");

        //Show specific error:
        if (element_key) {
            $("<div class=\"act-error\"><i class=\"fas fa-exclamation-triangle\"></i> Error: Duplicate intent! Only keep 1 & refresh.</div>").prependTo(element_key);
        }

        return false;
    }

    var settings = {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: s_draggable, // Specifies which items inside the element should be sortable
        handle: ".fa-bars", // Restricts sort start click/touch to the specified element
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
                tr_id: evt.item.attributes[1].nodeValue,
                in_id: evt.item.attributes[2].nodeValue,
                from_in_id: evt.from.attributes[2].value,
                to_in_id: evt.to.attributes[2].value,
            };
            //Update:
            $.post("/intents/c_move_c", inputs, function (data) {
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


function in_tr_load(in_id) {
    //Start loading:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $('#load_w_frame, .frame-loader').removeClass('hidden').hide().fadeIn();

    //Set title:
    $('#w_title').html('<i class="fas fa-atlas"></i> ' + $('.in_outcome_' + in_id + ':first').text());

    //Load content via a URL:
    $('.frame-loader').addClass('hidden');
    $('.ajax-frame').attr('src', '/intents/in_tr_load/' + in_id).removeClass('hidden').css('margin-top', '0');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();
}

function in_actionplans_load(in_id) {
    //Start loading:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $('#load_w_frame, .frame-loader').removeClass('hidden').hide().fadeIn();
    //Set title:
    $('#w_title').html('<i class="fas fa-flag"></i> ' + $('.in_outcome_' + in_id + ':first').text());

    //Load content via a URL:
    $('.frame-loader').addClass('hidden');
    $('.ajax-frame').attr('src', '/intents/in_actionplans_load/' + in_id).removeClass('hidden').css('margin-top', '0');

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
            var in_primary_id = parseInt($('.intent_line_' + in_parent_id).attr('parent-intent-id'));
            var in_primary__tree_seconds = parseFloat($('.t_estimate_' + in_primary_id + ':first').attr('tree-max-seconds'));
            var in_new__tree_seconds = in_primary__tree_seconds + in_deficit_seconds;

            if (!(intent_deficit_count == 0)) {
                $('.children-counter-' + in_primary_id).text(parseInt($('.children-counter-' + in_primary_id + ':first').text()) + intent_deficit_count);
            }

            if (!(in_deficit_seconds == 0)) {
                //Update Hours:
                $('.t_estimate_' + in_primary_id)
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

    //Make sure inputs are valid:
    if (!$('.t_estimate_' + in_id + ':first').length) {
        return false;
    }

    var level = (tr_id == 0 ? 1 : parseInt($('#cr_' + tr_id).attr('intent-level'))); //Either 1, 2 or 3

    //Update variables:
    $('#modifybox').attr('intent-link-id', tr_id);
    $('#modifybox').attr('intent-id', in_id);
    $('#modifybox').attr('level', level);

    //Set variables:
    var in_seconds = parseFloat($('.t_estimate_' + in_id + ':first').attr('intent-seconds'));
    var in__tree_seconds = $('.t_estimate_' + in_id + ':first').attr('tree-max-seconds');

    $('#in_outcome').val($(".in_outcome_" + in_id + ":first").text());
    in_outcome_counter();

    $('#in_status').val($('.in_outcome_' + in_id).attr('in_status'));
    $('#c_points').val($('.in_outcome_' + in_id).attr('c_points'));
    $('#c_trigger_statements').val($('.in_outcome_' + in_id).attr('c_trigger_statements'));
    $('#in_seconds').val(in_seconds);
    $('#c_cost_estimate').val(parseFloat($('.in_outcome_' + in_id).attr('c_cost_estimate')));

    //Load intent links if any:
    if (tr_id > 0) {
        $("#tr_status").val($('#cr_' + tr_id).attr('tr_status')); //Drop down
        $('#cr_condition_min').val($('#cr_' + tr_id).attr('cr_condition_min'));
        $('#cr_condition_max').val($('#cr_' + tr_id).attr('cr_condition_max'));
    }

    //Adjust Radio buttons:
    $("input[name=in_is_any][value='" + $('.in_outcome_' + in_id).attr('in_is_any') + "']").prop("checked", true);

    //Adjust checkboxes:
    document.getElementById("c_require_url_to_complete").checked = parseInt($('.in_outcome_' + in_id).attr('c_require_url_to_complete'));
    document.getElementById("c_require_notes_to_complete").checked = parseInt($('.in_outcome_' + in_id).attr('c_require_notes_to_complete'));
    document.getElementById("apply_recurively").checked = false; //Always remove this so the user can choose

    //Run UI Updating functions:
    in_adjust_isany_ui();
    in_adjust_link_ui(tr_id); //We must run this all the time

    //Are the tree hours greater than the intent hours?
    if (in__tree_seconds > in_seconds) {
        //Yes, show remaining tree hours:
        $('#child-hours').html('<i class="fas fa-clock"></i> ' + echo_js_hours(in__tree_seconds - in_seconds) + ' in <i class="fas fa-sitemap"></i> sub-tree');
    } else {
        //Nope, clear this field:
        $('#child-hours').html('');
    }

    //Only show unlink button if not level 1
    if (level == 1) {
        $('.unlink-intent').addClass('hidden');
    } else {
        $('.unlink-intent').removeClass('hidden');
    }


    //Make the frame visible:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();

    //Reload Tooltip again:
    $('[data-toggle="tooltip"]').tooltip();

    //We might need to scroll:
    if (is_compact) {
        $('.main-panel').animate({
            scrollTop: 9999
        }, 150);
    }

}

function c_save_modify() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('intent-id'))) {
        //Oops, this should not happen!
        return false;
    }


    //Prepare data to be modified for this intent:
    var modify_data = {
        in_id: parseInt($('#modifybox').attr('intent-id')),
        tr_id: parseInt($('#modifybox').attr('intent-link-id')), //Will be zero for Level 1 intent!
        level: parseInt($('#modifybox').attr('level')),
        in_outcome: $('#in_outcome').val(),
        in_status: parseInt($('#in_status').val()),
        in_seconds: parseInt($('#in_seconds').val()),
        c_cost_estimate: parseFloat($('#c_cost_estimate').val()),
        c_require_url_to_complete: (document.getElementById('c_require_url_to_complete').checked ? 1 : 0),
        c_require_notes_to_complete: (document.getElementById('c_require_notes_to_complete').checked ? 1 : 0),
        in_is_any: parseInt($('input[name=in_is_any]:checked').val()),
        apply_recurively: (document.getElementById('apply_recurively').checked ? 1 : 0),
        c_points: parseInt($('#c_points').val()),
        c_trigger_statements: $('#c_trigger_statements').val().replace(/\"/g, ""), //Remove double quotes
    };

    if (modify_data['tr_id'] > 0) {
        var original_tr_status = parseInt($('#cr_' + modify_data['tr_id']).attr('tr_status'));
        modify_data['tr_status'] = $('#tr_status').val();
    }

    //Take a snapshot of the current status:
    var original_in_status = parseInt($('.in_outcome_' + modify_data['in_id']).attr('in_status'));

    //Show spinner:
    $('.save_intent_changes').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/intents/c_save_settings", modify_data, function (data) {

        if (data.status) {

            //Update variables:
            $(".in_outcome_" + modify_data['in_id']).html(modify_data['in_outcome']);
            $('.in_outcome_' + modify_data['in_id']).attr('c_require_url_to_complete', modify_data['c_require_url_to_complete']);
            $('.in_outcome_' + modify_data['in_id']).attr('c_require_notes_to_complete', modify_data['c_require_notes_to_complete']);
            $('.in_outcome_' + modify_data['in_id']).attr('in_is_any', modify_data['in_is_any']);
            $('.in_outcome_' + modify_data['in_id']).attr('c_cost_estimate', modify_data['c_cost_estimate']);

            $('.in_outcome_' + modify_data['in_id']).attr('in_status', modify_data['in_status']);
            $('.in_outcome_' + modify_data['in_id']).attr('c_points', modify_data['c_points']);
            $('.in_outcome_' + modify_data['in_id']).attr('c_trigger_statements', modify_data['c_trigger_statements']);


            //has intent link status updated? If so update the UI:
            if (modify_data['tr_id'] > 0 && original_tr_status != modify_data['tr_status']) {
                //Update link status:
                $('#cr_' + modify_data['tr_id']).attr('tr_status', modify_data['tr_status']);
                //Update status:
                $('.tr_status_' + modify_data['tr_id']).html(data.status_cr_ui);
            }

            //has intent status updated? If so update the UI:
            if (original_in_status != modify_data['in_status']) {
                //Update status:
                $('.in_status_' + modify_data['in_id']).html(data.status_c_ui);
            }


            //Has the intent/intent-link been archived? Either way, we need to hide this row:
            if ((modify_data['tr_id'] > 0 && original_tr_status > 0 && modify_data['tr_status'] < 0) || (original_in_status > 0 && modify_data['in_status'] < 0)) {
                //We're archiving this...
                if (modify_data['level'] == 1) {
                    //move up as this item has been removed!
                    window.location = "/intents/" + ($('.intent_line_' + modify_data['in_id']).attr('parent-intent-id'));
                } else {
                    //hide removed item:
                    //Adjust hours:
                    adjust_js_ui(modify_data['in_id'], modify_data['level'], 0, data.in__tree_in_count, 1);

                    //Remove from UI:
                    $('#cr_' + modify_data['tr_id']).html('<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>');

                    //Disapper in a while:
                    //Hide the editor & saving results:
                    $('#cr_' + modify_data['tr_id']).fadeOut();

                    setTimeout(function () {

                        //Hide the editor & saving results:
                        $('#cr_' + modify_data['tr_id']).remove();

                        //Hide editing box:
                        $('#modifybox').addClass('hidden');

                        //Resort all Tasks to illustrate changes on UI:
                        in_sort_save(parseInt($('.intent_line_' + modify_data['in_id']).attr('parent-intent-id')), modify_data['level']);

                    }, 377);
                }
            }

            //Adjust UI Icons:
            if (modify_data['in_is_any']) {
                $('.in_is_any_icon' + modify_data['in_id']).addClass('fa-code-merge').removeClass('fa-sitemap');
            } else {
                $('.in_is_any_icon' + modify_data['in_id']).removeClass('fa-code-merge').addClass('fa-sitemap');
            }

            //Update trigger statements:
            if ($('.c_trigger_statements_' + modify_data['in_id']).length) {
                //This is the top intent that's loaded, update expanded trigger UI:
                $(".c_trigger_statements_" + modify_data['in_id']).html(nl2br(modify_data['c_trigger_statements']));
            } else {
                //This is a level 2+ intent, let's update the tooltip UI:
                if (modify_data['c_trigger_statements'].length > 0) {
                    $(".in_outcome_" + modify_data['in_id']).addClass('has-desc').attr('data-toggle', 'tooltip').attr('data-original-title', modify_data['c_trigger_statements']);
                } else {
                    $(".in_outcome_" + modify_data['in_id']).removeClass('has-desc').attr('data-toggle', '').attr('data-original-title', '');
                }
            }


            //Update other UI elements:
            $(".ui_c_points_" + modify_data['in_id']).html((modify_data['c_points'] > 0 ? '<i class="fas fa-weight" style="margin-right: 2px;"></i>' + modify_data['c_points'] : ''));
            $(".ui_c_require_notes_to_complete_" + modify_data['in_id']).html((modify_data['c_require_notes_to_complete'] > 0 ? '<i class="fas fa-pencil"></i>' : ''));
            $(".ui_c_require_url_to_complete_" + modify_data['in_id']).html((modify_data['c_require_url_to_complete'] > 0 ? '<i class="fas fa-link"></i>' : ''));
            $(".ui_c_cost_estimate_" + modify_data['in_id']).html((modify_data['c_cost_estimate'] > 0 ? '<i class="fas fa-usd-circle" style="margin-right: 2px;"></i>' + modify_data['c_cost_estimate'] : ''));


            //Adjust hours if needed:
            adjust_js_ui(modify_data['in_id'], modify_data['level'], modify_data['in_seconds']);

            //Update UI to confirm with user:
            $('.save_intent_changes').html(data.message).hide().fadeIn();

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

            //What's the final action?
            setTimeout(function () {
                if (modify_data['apply_recurively'] && data.children_updated > 0) {
                    //Refresh page soon to show new status for children:
                    window.location = "/intents/" + in_focus_id;
                } else {
                    //Hide the editor & saving results:
                    $('.save_intent_changes').hide();
                }
            }, 610);

        } else {
            //Ooops there was an error!
            $('.save_intent_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();
        }
    });

}


function fn___in_create_or_link(in_parent_id, next_level, in_link_child_id=0) {

    /*
     *
     * Either creates an intent link between in_parent_id & in_link_child_id
     * OR will create a new intent based on input text and then link it
     * to in_parent_id (In this case in_link_child_id=0)
     *
     * */

    if (next_level == 2) {
        var sort_handler = ".is_level2_sortable";
        var sort_list_id = "list-c-" + in_focus_id;
        var input_field = $('#addintent-c-' + in_parent_id);
    } else if (next_level == 3) {
        var sort_handler = ".is_level3_sortable";
        var sort_list_id = "list-cr-" + $('.intent_line_' + in_parent_id).attr('data-link-id');
        var input_field = $('#addintent-cr-' + $('.intent_line_' + in_parent_id).attr('data-link-id'));
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
    add_to_list(sort_list_id, sort_handler, '<div id="temp' + next_level + '" class="list-group-item"><img src="/img/round_load.gif" class="loader" /> Adding... </div>');

    //Update backend:
    $.post("/intents/fn___in_create_or_link", {
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
            adjust_js_ui(data.in_child_id, next_level, data.in__tree_max_seconds, data.in__tree_in_count, 0, 1);

        } else {
            //Show errors:
            alert('ERROR: ' + data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}