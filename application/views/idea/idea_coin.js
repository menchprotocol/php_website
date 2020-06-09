/*
*
* Functions related to modifying ideas
* and managing IDEA NOTES.
*
* */


var match_search_loaded = 0; //Keeps track of when we load the match search

$(document).ready(function () {

    //Load search for mass update function:
    load_editor();

    //Lookout for textinput updates
    view_input_text_update_start();

    //Put focus on messages if no message:
    if(!$('#idea_notes_list_4231 .note_sortable').length){
        $('#read__message4231').focus();
    }

    autosize($('.text__4736_'+idea_loaded_id));

    $('#expand_ideas .expand_all').click(function (e) {
        $(".next_ideas .ideas_sortable").each(function () {
            ms_toggle($(this).attr('read__id'), 1);
        });
    });

    //Activate Source-Only Inputs:

    $(".source-mapper").each(function () {
        source_source_only_search($(this).attr('note_type_id'));
    });

    //Load top/bottom idea searches:
    idea_load_search(".IdeaAddPrevious",1, 'q', 'link_in');
    idea_load_search(".ideaadder-level-2-child",0, 'w', 'link_in');

    //Expand selections:
    prep_search_pad();

    //Load Sortable:
    idea_sort_load(idea_loaded_id);

    //Watch the expand/close all buttons:
    $('#expand_ideas .expand_all').click(function (e) {
        $(".next_ideas .ideas_sortable").each(function () {
            ms_toggle($(this).attr('read__id'), 1);
        });
    });
    $('#expand_ideas .close_all').click(function (e) {
        $(".next_ideas .ideas_sortable").each(function () {
            ms_toggle($(this).attr('read__id'), 0);
        });
    });


    //Loop through all new idea inboxes:
    $(".new-note").each(function () {

        var note_type_id = parseInt($(this).attr('note-type-id'));

        //Initiate @ search for all idea text areas:
        idea_note_source_search($(this));

        autosize($(this));

        //Activate sorting:
        idea_note_sort_load(note_type_id);

        var showFiles = function (files) {
            if(typeof files[0] !== 'undefined'){
                $('.box' + note_type_id).find('label').text(files.length > 1 ? ($('.box' + note_type_id).find('input[type="file"]').attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
            }
        };

        $('.box' + note_type_id).find('input[type="file"]').on('drop', function (e) {
            droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
            showFiles(droppedFiles);
        });

        $('.box' + note_type_id).find('input[type="file"]').on('change', function (e) {
            showFiles(e.target.files);
        });

        //Watch for message creation:
        $('#read__message' + note_type_id).keydown(function (e) {
            if (e.ctrlKey && e.keyCode == 13) {
                idea_note_add_text(note_type_id);
            }
        });

        //Watchout for file uplods:
        $('.box' + note_type_id).find('input[type="file"]').change(function () {
            idea_note_add_file(droppedFiles, 'file', note_type_id);
        });


        //Should we auto start?
        if (isAdvancedUpload) {

            $('.box' + note_type_id).addClass('has-advanced-upload');
            var droppedFiles = false;

            $('.box' + note_type_id).on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
            })
                .on('dragover dragenter', function () {
                    $('.add_notes_' + note_type_id).addClass('is-working');
                })
                .on('dragleave dragend drop', function () {
                    $('.add_notes_' + note_type_id).removeClass('is-working');
                })
                .on('drop', function (e) {
                    droppedFiles = e.originalEvent.dataTransfer.files;
                    e.preventDefault();
                    idea_note_add_file(droppedFiles, 'drop', note_type_id);
                });
        }

    });

});


function source_only_unlink(read__id, note_type_id) {

    var r = confirm("Remove this source?");
    if (r == true) {
        $.post("/source/source_only_unlink", {

            idea__id: idea_loaded_id,
            read__id: read__id,

        }, function (data) {
            if (data.status) {

                idea_note_counter(note_type_id, -1);
                $(".tr_" + read__id).fadeOut();
                setTimeout(function () {
                    $(".tr_" + read__id).remove();
                }, 610);

            } else {
                //We had an error:
                alert(data.message);
            }
        });
    }

}

function source_only_add(source_existing_id, note_type_id) {


    //if source_existing_id>0 it means we're linking to an existing source, in which case source_new_string should be null
    //If source_existing_id=0 it means we are creating a new source and then linking it, in which case source_new_string is required

    var source_new_string = null;
    var input = $('.source-map-'+note_type_id+' .add-input');
    var list_id = 'add-source-'+note_type_id;

    if (source_existing_id == 0) {

        source_new_string = input.val();
        if (source_new_string.length < 1) {
            alert('Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }

    //Add via Ajax:
    input.prop('disabled', true);
    $.post("/source/source_only_add", {

        idea__id: idea_loaded_id,
        note_type_id: note_type_id,
        source_existing_id: source_existing_id,
        source_new_string: source_new_string,

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);

        if (data.status) {

            idea_note_counter(note_type_id, +1);

            //Raw input to make it reads for next URL:
            input.focus();

            //Add new object to list:
            add_to_list(list_id, '.en-item', data.source_new_echo);

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert(data.message);
        }

    });

}

function source_source_only_search(note_type_id) {

    if(!js_pl_id){
        return false;
    }

    var element_focus = ".source-map-"+note_type_id;

    var base_creator_url = '/source/create/'+idea_loaded_id+'/?content_title=';

    $(element_focus + ' .add-input').focus(function() {
        $(element_focus + ' .algolia_pad_search' ).removeClass('hidden');
    }).focusout(function() {
        $(element_focus + ' .algolia_pad_search' ).addClass('hidden');
    }).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            source_only_add(0, note_type_id);
            return true;
        }
    });

    if(parseInt(js_sources__6404[12678]['m_desc'])){

        //Define filters:
        var extra_filters = '';
        if(note_type_id==4983){
            extra_filters = ' AND ( _tags:alg_source_'+js_pl_id+' OR _tags:alg_source_' + js_sources_id_4983.join(' OR _tags:alg_source_') + ') ';
        } else if(note_type_id==10573){
            extra_filters = ' AND ( _tags:alg_source_'+js_pl_id+' OR _tags:alg_source_' + js_sources_id_10573.join(' OR _tags:alg_source_') + ') ';
        }

        $(element_focus + ' .add-input').on('autocomplete:selected', function (event, suggestion, dataset) {

            source_only_add(suggestion.object__id, note_type_id);

        }).autocomplete({hint: false, minLength: 1}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'object__type=4536' + extra_filters,
                    hitsPerPage: 7,
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
                    //If clicked, would trigger the autocomplete:selected above which will trigger the source__add() function
                    return view_search_result(suggestion);
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:void(0);" onclick="source_only_add(0, '+note_type_id+');" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:void(0);" onclick="source_only_add(0, '+note_type_id+');" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                },
            }
        }]);
    }
}

function read_preview(){
    if(parseInt($('.dropi_4737_'+idea_loaded_id+'_0.active').attr('new-en-id')) in js_sources__7355){
        //Idea is public, go to preview:
        window.location = '/' + idea_loaded_id;
    } else {
        //Inform them that they cannot read yet:
        alert('You must publish idea before reading it.');
    }
}

function idea_unlink(idea__id, read__id, is_parent){
    var idea__title = $('.text__4736_'+idea__id).text();
    if(!idea__title.length){
        idea__title = $('.text__4736_'+idea__id).val();
    }
    var r = confirm("Unlink ["+idea__title+"]?");
    if (r == true) {

        //Fetch Idea Data to load modify widget:
        $.post("/idea/idea_unlink", {
            idea__id: idea__id,
            read__id: read__id,
        }, function (data) {
            if (data.status) {
                idea_ui_delete(idea__id,read__id);
                if(!is_parent){
                    idea_note_counter(11020, -1);
                }
            }
        });
    }
}

function idea_ui_delete(idea__id,read__id){

    //Delete from UI:
    $('.idea__tr_' + read__id).html('<span style="color:#000000;"><i class="fas fa-trash-alt"></i></span>');

    //Hide the editor & saving results:
    $('.idea__tr_' + read__id).fadeOut();

    //Disappear in a while:
    setTimeout(function () {

        //Hide the editor & saving results:
        $('.idea__tr_' + read__id).remove();

        //Hide editing box:
        $('#modifybox').addClass('hidden');

    }, 610);

}

function prep_search_pad(){

    //All level 2s:
    $('.IdeaAddPrevious').focus(function() {
        $('.idea_pad_top' ).removeClass('hidden');
    }).focusout(function() {
        $('.idea_pad_top' ).addClass('hidden');
    });

    $('.ideaadder-level-2-child').focus(function() {
        $('.idea_pad_bottom' ).removeClass('hidden');
    }).focusout(function() {
        $('.idea_pad_bottom' ).addClass('hidden');
    });

}

function idea_sort_save(idea__id) {

    var new_read__sorts = [];
    var sort_rank = 0;

    $("#list-in-" + idea_loaded_id + "-0 .ideas_sortable").each(function () {
        //Fetch variables for this idea:
        var idea__id = parseInt($(this).attr('idea-id'));
        var read__id = parseInt($(this).attr('read__id'));

        sort_rank++;

        //Store in DB:
        new_read__sorts[sort_rank] = read__id;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0 && idea__id) {
        //Update backend:
        $.post("/idea/idea_sort_save", {idea__id: idea__id, new_read__sorts: new_read__sorts}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}

function idea_sort_load(idea__id) {


    var element_key = null;
    var theobject = document.getElementById("list-in-" + idea_loaded_id + "-0");
    if (!theobject) {
        //due to duplicate ideas belonging in this idea:
        return false;
    }

    var sort = Sortable.create(theobject, {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".ideas_sortable", // Specifies which items inside the element should be sortable
        handle: ".idea-sort-handle", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            idea_sort_save(idea__id);
        }
    });
}

function idea_add(idea_linked_id, is_parent, idea_link_child_id) {

    /*
     *
     * Either creates an IDEA link between idea_linked_id & idea_link_child_id
     * OR will create a new idea based on input text and then link it
     * to idea_linked_id (In this case idea_link_child_id=0)
     *
     * */


    var sort_handler = ".ideas_sortable";
    var sort_list_id = "list-in-" + idea_loaded_id + '-' + is_parent;
    var input_field = $('#addidea-c-' + idea_linked_id + '-' + is_parent);
    var idea__title = input_field.val();


    if( idea__title.charAt(0)=='#'){
        if(isNaN(idea__title.substr(1))){
            alert('Use numbers only. Example: #1234');
            return false;
        } else {
            //Update the references:
            idea_link_child_id = parseInt(idea__title.substr(1));
            idea__title = idea_link_child_id; //As if we were just linking
        }
    }



    //We either need the idea name (to create a new idea) or the idea_link_child_id>0 to create an IDEA link:
    if (!idea_link_child_id && idea__title.length < 1) {
        alert('Enter something');
        input_field.focus();
        return false;
    }


    //Set processing status:
    add_to_list(sort_list_id, sort_handler, '<div id="tempLoader" class="list-group-item montserrat"><span class="icon-block"><i class="fas fa-yin-yang fa-spin idea"></i></span>' + js_view_platform_message(12695) +  '</div>');


    //Update backend:
    $.post("/idea/idea_add", {
        idea_linked_id: idea_linked_id,
        is_parent:is_parent,
        idea__title: idea__title,
        idea_link_child_id: idea_link_child_id
    }, function (data) {

        //Delete loader:
        $("#tempLoader").remove();

        if (data.status) {

            if(!is_parent){
                //Only children have a counter:
                idea_note_counter(11020, +1);
            }


            //Add new
            add_to_list(sort_list_id, sort_handler, data.idea_next_html);

            //Reload sorting to enable sorting for the newly added idea:
            idea_sort_load(idea_linked_id);

            //Lookout for textinput updates
            view_input_text_update_start();

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

function idea_update_dropdown(element_id, new_source__id, idea__id, read__id, show_full_name){

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

    var current_selected = parseInt($('.dropi_'+element_id+'_'+idea__id+'_'+read__id+'.active').attr('new-en-id'));
    new_source__id = parseInt(new_source__id);
    if(current_selected == new_source__id){
        //Nothing changed:
        return false;
    }

    //Changing Idea Status?
    if(element_id==4737){

        var is_idea_active = (new_source__id in js_sources__7356);
        var is_idea_public = (new_source__id in js_sources__7355);


        //Deleting?
        if(!is_idea_active){
            //Seems to be deleting, confirm:
            var r = confirm("Delete this idea AND unlink all its links to other ideas?");
            if (r == false) {
                return false;
            }
        }


        //Reads Setting:
        if(is_idea_public){

            //Enable Reads:
            $('.idea-read').removeClass('hidden');

        } else {

            //Disable Reads:
            $('.idea-read').addClass('hidden');

        }

    }



    //Is Status Public?



    //Show Loading...
    var data_object = eval('js_sources__'+element_id);
    $('.dropd_'+element_id+'_'+idea__id+'_'+read__id+' .btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">'+ ( show_full_name ? '<span class="show-max">SAVING...</span>' : '' ) +'</b>');

    $.post("/idea/idea_update_dropdown", {

        idea__id: idea__id,
        read__id: read__id,
        idea_loaded_id:idea_loaded_id,
        element_id: element_id,
        new_source__id: new_source__id

    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.dropd_'+element_id+'_'+idea__id+'_'+read__id+' .btn').html('<span class="icon-block">'+data_object[new_source__id]['m_icon']+'</span><span class="show-max">' + ( show_full_name ? data_object[new_source__id]['m_name'] : '' ) + '</span>');
            $('.dropd_'+element_id+'_'+idea__id+'_'+read__id+' .dropi_' + element_id +'_'+idea__id+ '_' + read__id).removeClass('active');
            $('.dropd_'+element_id+'_'+idea__id+'_'+read__id+' .optiond_' + new_source__id+'_'+idea__id+ '_' + read__id).addClass('active');

            $('.dropd_'+element_id+'_'+idea__id+'_'+read__id).attr('selected-val' , new_source__id);

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
                $('.idea__tr_'+read__id+' .link_marks').addClass('hidden');
                $('.idea__tr_'+read__id+' .settings_' + new_source__id).removeClass('hidden');
            }

        } else {

            //Reset to default:
            $('.dropd_'+element_id+'_'+idea__id+'_'+read__id+' .btn').html('<span class="icon-block">'+data_object[current_selected]['m_icon']+'</span>' + ( show_full_name ? data_object[current_selected]['m_name'] : '' ));

            //Show error:
            alert(data.message);

        }
    });
}
