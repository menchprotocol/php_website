/*
*
* Functions related to modifying ideas
* and managing IDEA NOTES.
*
* */


var match_search_loaded = 0; //Keeps track of when we load the match search

$(document).ready(function () {

    i_note_activate();

    //Load search for mass update function:
    load_editor();

    //Lookout for textinput updates
    x_set_text_start();

    //Put focus on messages if no message:
    if(!$('#i_notes_list_4231 .note_sortable').length){
        $('#x__message4231').focus();
    }

    autosize($('.text__4736_'+i_loaded_id));

    //Activate Source-Only Inputs:
    $(".e-only").each(function () {
        e_e_only_search($(this).attr('note_type_id'));
    });

    //Load top/bottom idea searches:
    i_load_search(".IdeaAddPrevious",1, 'q', 'link_in');
    i_load_search(".ideaadder-level-2-child",0, 'w', 'link_in');

    //Expand selections:
    prep_search_pad();

    //Load Sortable:
    i_sort_load(i_loaded_id);

});


function e_only_unlink(x__id, note_type_id) {

    var r = confirm("Remove this source?");
    if (r == true) {
        $.post("/e/e_only_unlink", {

            i__id: i_loaded_id,
            x__id: x__id,

        }, function (data) {
            if (data.status) {

                i_note_counter(note_type_id, -1);
                $(".tr_" + x__id).fadeOut();
                setTimeout(function () {
                    $(".tr_" + x__id).remove();
                }, 610);

            } else {
                //We had an error:
                alert(data.message);
            }
        });
    }

}

function e_only_add(e_existing_id, note_type_id) {


    //if e_existing_id>0 it means we're linking to an existing source, in which case e_new_string should be null
    //If e_existing_id=0 it means we are creating a new source and then linking it, in which case e_new_string is required

    var e_new_string = null;
    var input = $('.e-i-'+note_type_id+' .add-input');
    var list_id = 'add-e-'+note_type_id;

    if (e_existing_id == 0) {

        e_new_string = input.val();
        if (e_new_string.length < 1) {
            alert('Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }

    //Add via Ajax:
    input.prop('disabled', true);
    $.post("/e/e_only_add", {

        i__id: i_loaded_id,
        note_type_id: note_type_id,
        e_existing_id: e_existing_id,
        e_new_string: e_new_string,

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);

        if (data.status) {

            i_note_counter(note_type_id, +1);

            //Raw input to make it discovers for next URL:
            input.focus();

            //Add new object to list:
            add_to_list(list_id, '.en-item', data.e_new_echo);

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert(data.message);
        }

    });

}

function e_e_only_search(note_type_id) {

    if(!js_pl_id){
        return false;
    }

    var element_focus = ".e-i-"+note_type_id;

    var base_creator_url = '/e/create/'+i_loaded_id+'/?content_title=';

    $(element_focus + ' .add-input').focus(function() {
        $(element_focus + ' .algolia_pad_search' ).removeClass('hidden');
    }).focusout(function() {
        $(element_focus + ' .algolia_pad_search' ).addClass('hidden');
    }).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            e_only_add(0, note_type_id);
            return true;
        }
    });

    if(parseInt(js_e___6404[12678]['m_desc'])){

        $(element_focus + ' .add-input').on('autocomplete:selected', function (event, suggestion, dataset) {

            e_only_add(suggestion.object__id, note_type_id);

        }).autocomplete({hint: false, minLength: 1}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'object__type=4536',
                    hitsPerPage: 10,
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
                    //If clicked, would trigger the autocomplete:selected above which will trigger the e__add() function
                    return view_search_result(suggestion);
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:void(0);" onclick="e_only_add(0, '+note_type_id+');" class="suggestion montserrat"><span class="icon-block"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:void(0);" onclick="e_only_add(0, '+note_type_id+');" class="suggestion montserrat"><span class="icon-block"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                },
            }
        }]);
    }
}

function x_preview(){
    if(parseInt($('.dropi_4737_'+i_loaded_id+'_0.active').attr('new-en-id')) in js_e___7355){
        //Idea is public, go to preview:
        window.location = '/' + i_loaded_id;
    } else {
        //Inform them that they cannot discover yet:
        alert('You must publish idea before discovering it.');
    }
}

function i_unlink(i__id, x__id, is_parent){
    var i__title = $('.text__4736_'+i__id).text();
    if(!i__title.length){
        i__title = $('.text__4736_'+i__id).val();
    }
    var r = confirm("Unlink ["+i__title+"]?");
    if (r == true) {

        //Fetch Idea Data to load modify widget:
        $.post("/i/i_unlink", {
            i__id: i__id,
            x__id: x__id,
        }, function (data) {
            if (data.status) {
                i_ui_delete(i__id,x__id);
                if(!is_parent){
                    i_note_counter(11020, -1);
                }
            }
        });
    }
}

function i_ui_delete(i__id,x__id){

    //Delete from UI:
    $('.i__tr_' + x__id).html('<span style="color:#000000;"><i class="fas fa-trash-alt"></i></span>');

    //Hide the editor & saving results:
    $('.i__tr_' + x__id).fadeOut();

    //Disappear in a while:
    setTimeout(function () {

        //Hide the editor & saving results:
        $('.i__tr_' + x__id).remove();

        //Hide editing box:
        $('#modifybox').addClass('hidden');

    }, 610);

}

function prep_search_pad(){

    //All level 2s:
    $('.IdeaAddPrevious').focus(function() {
        $('.i_pad_top' ).removeClass('hidden');
    }).focusout(function() {
        $('.i_pad_top' ).addClass('hidden');
    });

    $('.ideaadder-level-2-child').focus(function() {
        $('.i_pad_bottom' ).removeClass('hidden');
    }).focusout(function() {
        $('.i_pad_bottom' ).addClass('hidden');
    });

}

function i_sort_save(i__id) {

    var new_x__sorts = [];
    var sort_rank = 0;

    $("#list-in-" + i_loaded_id + "-0 .i_sortable").each(function () {
        //Fetch variables for this idea:
        var i__id = parseInt($(this).attr('i-id'));
        var x__id = parseInt($(this).attr('x__id'));

        sort_rank++;

        //Store in DB:
        new_x__sorts[sort_rank] = x__id;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0 && i__id) {
        //Update backend:
        $.post("/i/i_sort_save", {i__id: i__id, new_x__sorts: new_x__sorts}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}

function i_sort_load(i__id) {


    var element_key = null;
    var theobject = document.getElementById("list-in-" + i_loaded_id + "-0");
    if (!theobject) {
        //due to duplicate ideas belonging in this idea:
        return false;
    }

    var sort = Sortable.create(theobject, {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".i_sortable", // Specifies which items inside the element should be sortable
        handle: ".i-sort-handle", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            i_sort_save(i__id);
        }
    });
}

function i_add(i_linked_id, is_parent, i_link_child_id) {

    /*
     *
     * Either creates an IDEA link between i_linked_id & i_link_child_id
     * OR will create a new idea based on input text and then link it
     * to i_linked_id (In this case i_link_child_id=0)
     *
     * */


    var sort_handler = ".i_sortable";
    var sort_list_id = "list-in-" + i_loaded_id + '-' + is_parent;
    var input_field = $('#addi-c-' + i_linked_id + '-' + is_parent);
    var i__title = input_field.val();


    if( i__title.charAt(0)=='#'){
        if(isNaN(i__title.substr(1))){
            alert('Use numbers only. Example: #1234');
            return false;
        } else {
            //Update the references:
            i_link_child_id = parseInt(i__title.substr(1));
            i__title = i_link_child_id; //As if we were just linking
        }
    }



    //We either need the idea name (to create a new idea) or the i_link_child_id>0 to create an IDEA link:
    if (!i_link_child_id && i__title.length < 1) {
        alert('Enter something');
        input_field.focus();
        return false;
    }


    //Set processing status:
    add_to_list(sort_list_id, sort_handler, '<div id="tempLoader" class="list-group-item montserrat no-side-padding"><span class="icon-block"><i class="fas fa-yin-yang fa-spin idea"></i></span>' + js_view_platform_message(12695) +  '</div>');


    //Update backend:
    $.post("/i/i_add", {
        i_linked_id: i_linked_id,
        is_parent:is_parent,
        i__title: i__title,
        i_link_child_id: i_link_child_id
    }, function (data) {

        //Delete loader:
        $("#tempLoader").remove();

        if (data.status) {

            if(!is_parent){
                //Only children have a counter:
                i_note_counter(11020, +1);
            }


            //Add new
            add_to_list(sort_list_id, sort_handler, data.next_i_html);

            //Reload sorting to enable sorting for the newly added idea:
            i_sort_load(i_linked_id);

            //Lookout for textinput updates
            x_set_text_start();

            //Expand selections:
            prep_search_pad();

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //Show errors:
            alert(data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}

function i_set_dropdown(element_id, new_e__id, i__id, x__id, show_full_name){

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

    var current_selected = parseInt($('.dropi_'+element_id+'_'+i__id+'_'+x__id+'.active').attr('new-en-id'));
    new_e__id = parseInt(new_e__id);
    if(current_selected == new_e__id){
        //Nothing changed:
        return false;
    }

    //Changing Idea Status?
    if(element_id==4737){

        var is_i_active = (new_e__id in js_e___7356);
        var is_i_public = (new_e__id in js_e___7355);


        //Deleting?
        if(!is_i_active){
            //Seems to be deleting, confirm:
            var r = confirm("Delete this idea AND unlink all its links to other ideas?");
            if (r == false) {
                return false;
            }
        }


        //Discoveries Setting:
        if(is_i_public){

            //Enable Discoveries:
            $('.i-x').removeClass('hidden');

        } else {

            //Disable Discoveries:
            $('.i-x').addClass('hidden');

        }

    }



    //Is Status Public?



    //Show Loading...
    var data_object = eval('js_e___'+element_id);
    $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">'+ ( show_full_name ? '<span class="show-max">SAVING...</span>' : '' ) +'</b>');

    $.post("/i/i_set_dropdown", {

        i__id: i__id,
        x__id: x__id,
        i_loaded_id:i_loaded_id,
        element_id: element_id,
        new_e__id: new_e__id

    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .btn').html('<span class="icon-block">'+data_object[new_e__id]['m_icon']+'</span><span class="show-max">' + ( show_full_name ? data_object[new_e__id]['m_name'] : '' ) + '</span>');
            $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .dropi_' + element_id +'_'+i__id+ '_' + x__id).removeClass('active');
            $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .optiond_' + new_e__id+'_'+i__id+ '_' + x__id).addClass('active');

            $('.dropd_'+element_id+'_'+i__id+'_'+x__id).attr('selected-val' , new_e__id);

            if( data.deletion_redirect && data.deletion_redirect.length > 0 ){
                //Go to main idea page:
                window.location = data.deletion_redirect;
            } else if( data.delete_element && data.delete_element.length > 0 ){
                //Go to main idea page:
                setTimeout(function () {
                    //Restore background:
                    $( data.delete_element ).fadeOut();

                    setTimeout(function () {
                        //Restore background:
                        $( data.delete_element ).remove();
                    }, 55);

                }, 377);
            }

            if(element_id==4486){
                $('.i__tr_'+x__id+' .link_marks').addClass('hidden');
                $('.i__tr_'+x__id+' .settings_' + new_e__id).removeClass('hidden');
            }

        } else {

            //Reset to default:
            $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .btn').html('<span class="icon-block">'+data_object[current_selected]['m_icon']+'</span>' + ( show_full_name ? data_object[current_selected]['m_name'] : '' ));

            //Show error:
            alert(data.message);

        }
    });
}
