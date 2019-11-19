/*
*
* Functions related to adding/removing
* intents via the Action Plan.
*
* */




$(document).ready(function () {


    $('#expand_intents .expand_all').click(function (e) {
        $(".list-is-children .blogs_sortable").each(function () {
            ms_toggle($(this).attr('in-link-id'), 1);
        });
    });

    //Load top/bottom intent searches:
    in_load_search(".intentadder-level-2-parent",1, 'q');
    in_load_search(".intentadder-level-2-child",0, 'w');

    //Expand selections:
    prep_search_pad();

    //Load Sortable:
    in_sort_load(in_loaded_id);

    //Watch the expand/close all buttons:
    $('#expand_intents .expand_all').click(function (e) {
        $(".list-is-children .blogs_sortable").each(function () {
            ms_toggle($(this).attr('in-link-id'), 1);
        });
    });
    $('#expand_intents .close_all').click(function (e) {
        $(".list-is-children .blogs_sortable").each(function () {
            ms_toggle($(this).attr('in-link-id'), 0);
        });
    });

});




function prep_search_pad(){

    //All level 2s:
    $('.intentadder-level-2-parent').focus(function() {
        $('.in_pad_top' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_top' ).addClass('hidden');
    });

    $('.intentadder-level-2-child').focus(function() {
        $('.in_pad_bottom' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_bottom' ).addClass('hidden');
    });

}

function in_load_search(focus_element, is_in_parent, shortcut) {

    //Loads the intent search bar only once for the add intent inputs
    if($(focus_element).hasClass('search-bar-loaded')){
        //Already loaded:
        return false;
    }


    //Not yet loaded, continue with loading it:
    $(focus_element).addClass('search-bar-loaded').on('autocomplete:selected', function (event, suggestion, dataset) {

        in_link_or_create($(this).attr('intent-id'), is_in_parent, suggestion.alg_obj_id);

    }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [shortcut]}, [{

        source: function (q, cb) {

            if($(focus_element).val().charAt(0)=='#'){
                cb([]);
                return;
            } else {
                algolia_index.search(q, {
                    filters: ( js_assigned_superpowers_en_ids.includes(10989 /* PEGASUS */) ? '' : '_tags:alg_is_published_featured AND') + ' alg_obj_is_in=1',
                    hitsPerPage: 7,
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            }

        },
        displayKey: function (suggestion) {
            return ""
        },
        templates: {
            suggestion: function (suggestion) {
                return echo_js_suggestion(suggestion);
            },
            header: function (data) {
                if (!($(focus_element).val().charAt(0)=='#') && !data.isEmpty) {
                    return '<a href="javascript:in_link_or_create(' + parseInt($(focus_element).attr('intent-id')) + ','+is_in_parent+',0)" class="suggestion"><span class="icon-block"><i class="fas fa-plus-circle add-plus"></i></span><b>' + data.query + '</b></a>';
                }
            },
            empty: function (data) {
                if($(focus_element).val().charAt(0)=='#'){
                    return '<a href="javascript:in_link_or_create(' + parseInt($(focus_element).attr('intent-id')) + ','+is_in_parent+',0)" class="suggestion"><span class="icon-block"><i class="fas fa-link"></i></span>Link to <b>' + data.query + '</b></a>';
                } else {
                    return '<a href="javascript:in_link_or_create(' + parseInt($(focus_element).attr('intent-id')) + ','+is_in_parent+',0)" class="suggestion"><span class="icon-block"><i class="fas fa-plus-circle add-plus"></i></span><b>' + data.query + '</b></a>';
                }
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return in_link_or_create($(this).attr('intent-id'), is_in_parent, 0);
        }
    });

}


function in_sort_save(in_id) {

    var s_element = "list-in-" + in_loaded_id + '-0';
    var s_draggable = ".blogs_sortable";
    var new_ln_orders = [];
    var sort_rank = 0;

    $("#" + s_element + " " + s_draggable).each(function () {
        //Fetch variables for this intent:
        var in_id = parseInt($(this).attr('intent-id'));
        var ln_id = parseInt($(this).attr('in-link-id'));

        sort_rank++;

        //Store in DB:
        new_ln_orders[sort_rank] = ln_id;
    });


    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0 && in_id) {
        //Update backend:
        $.post("/ideas/in_sort_save", {in_id: in_id, new_ln_orders: new_ln_orders}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert('ERROR: ' + data.message);
            }
        });
    }
}


function in_sort_load(in_id) {


    var element_key = null;
    var s_element = "list-in-" + in_loaded_id + '-0';
    var s_draggable = ".blogs_sortable";
    var theobject = document.getElementById(s_element);
    if (!theobject) {
        //due to duplicate intents belonging in this tree:
        //TODO Fix later to support duplicate intents
        return false;
    }

    var sort = Sortable.create(theobject, {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: s_draggable, // Specifies which items inside the element should be sortable
        handle: ".enable-sorting", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            in_sort_save(in_id);
        }
    });
}



function in_link_or_create(in_linked_id, is_parent, in_link_child_id) {

    /*
     *
     * Either creates a BLOG link between in_linked_id & in_link_child_id
     * OR will create a new intent based on input text and then link it
     * to in_linked_id (In this case in_link_child_id=0)
     *
     * */


    var sort_handler = ".blogs_sortable";
    var sort_list_id = "list-in-" + in_loaded_id + '-' + is_parent;
    var input_field = $('#addintent-c-' + in_linked_id + '-' + is_parent);
    var intent_name = input_field.val();


    if( intent_name.charAt(0)=='#'){
        if(isNaN(intent_name.substr(1))){
            alert('Error: Use numbers only. Example: #1234');
            return false;
        } else {
            //Update the references:
            in_link_child_id = parseInt(intent_name.substr(1));
            intent_name = in_link_child_id; //As if we were just linking
        }
    }




    //We either need the intent name (to create a new intent) or the in_link_child_id>0 to create a BLOG link:
    if (!in_link_child_id && intent_name.length < 1) {
        alert('Error: Enter something');
        input_field.focus();
        return false;
    }

    //Set processing status:
    add_to_list(sort_list_id, sort_handler, '<div id="tempLoader" class="list-group-item"><i class="far fa-yin-yang fa-spin"></i> Adding... </div>');

    //Update backend:
    $.post("/ideas/in_link_or_create", {
        in_linked_id: in_linked_id,
        is_parent:is_parent,
        in_outcome: intent_name,
        in_link_child_id: in_link_child_id
    }, function (data) {

        //Remove loader:
        $("#tempLoader").remove();

        if (data.status) {

            //Add new
            add_to_list(sort_list_id, sort_handler, data.in_child_html);

            //Reload sorting to enable sorting for the newly added intent:
            in_sort_load(in_linked_id);

            //Expand selections:
            prep_search_pad();

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //Show errors:
            alert('ERROR: ' + data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}