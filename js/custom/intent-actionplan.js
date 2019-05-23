/*
*
* Functions related to adding/removing
* intents via the Action Plan.
*
* */

function prep_expansion(in_id){

    //All level 2s:
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

    //Expand level 3 search results:
    $('.new-in3-input .algolia_search').focus(function() {
        $('.in_pad_' + in_id ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_' + in_id ).addClass('hidden');
    });

}

$(document).ready(function () {

    //Activate expansion:
    activate_expansion();

    //Load top/bottom intent searches:
    in_load_search(".intentadder-level-2-top",    1, 2);
    in_load_search(".intentadder-level-2-bottom", 0, 2);

    //Expand selections:
    prep_expansion($(this).attr('intent-id'));

    //Load Sortable for level 2:
    in_sort_load(in_focus_id, 2);

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




});


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




function in_load_search(focus_element, is_in_parent, next_in_level) {

    //Loads the intent search bar only once for the add intent inputs
    if($(focus_element).hasClass('search-bar-loaded')){
        //Already loaded:
        return false;
    }

    //Not yet loaded, continue with loading it:
    $(focus_element).addClass('search-bar-loaded').on('autocomplete:selected', function (event, suggestion, dataset) {

        in_link_or_create($(this).attr('intent-id'), is_in_parent, next_in_level, suggestion.alg_obj_id);

    }).autocomplete({hint: false, minLength: 2, keyboardShortcuts: ['a']}, [{

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

            //Activate expansion:
            activate_expansion();

            if (next_level == 2) {

                if(!is_parent){
                    //Adjust the Step count:
                    in_sort_save(0, 2);
                }

                //Reload sorting to enable sorting for the newly added intent:
                in_sort_load(data.in_child_id, 3);

                //Load search again:
                in_load_search(".intentadder-id-"+data.in_child_id, 0, 3);

                //Expand selections:
                prep_expansion(data.in_child_id);

            } else if(!is_parent) {

                //Adjust Intent Level 3 sorting:
                in_sort_save(in_parent_id, next_level);

            }

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Adjust time:
            adjust_js_ui(data.in_child_id, next_level, 0, 0, 0, 1);

        } else {
            //Show errors:
            alert('ERROR: ' + data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}