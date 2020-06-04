/*
*
* Functions related to modifying ideas
* and managing IDEA NOTES.
*
* */

function idea_notes_counter(note_type_id, adjustment_count){
    var current_count = parseInt($('.en-type-counter-'+note_type_id).text());
    var new_count = current_count + adjustment_count;
    $('.en-type-counter-'+note_type_id).text(new_count);
}

function source_only_unlink(read__id, note_type_id) {

    var r = confirm("Remove this source?");
    if (r == true) {
        $.post("/source/source_only_unlink", {

            idea__id: idea_loaded_id,
            read__id: read__id,

        }, function (data) {
            if (data.status) {

                idea_notes_counter(note_type_id, -1);
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

            idea_notes_counter(note_type_id, +1);

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
        idea_message_inline_source_search($(this));

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
                idea_add_note_text(note_type_id);
            }
        });

        //Watchout for file uplods:
        $('.box' + note_type_id).find('input[type="file"]').change(function () {
            idea_add_note_file(droppedFiles, 'file', note_type_id);
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
                    idea_add_note_file(droppedFiles, 'drop', note_type_id);
                });
        }

    });

});

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
                    idea_notes_counter(11020, -1);
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






/*
*
* IDEA NOTES
*
* */

function idea_notes_insert_string(note_type_id, add_string) {
    $('#read__message' + note_type_id).insertAtCaret(add_string);
    idea_notes_count_new(note_type_id);
}


//Count text area characters:
function idea_notes_count_new(note_type_id) {

    //Update count:
    var len = $('#read__message' + note_type_id).val().length;
    if (len > js_sources__6404[4485]['m_desc']) {
        $('#charNum' + note_type_id).addClass('overload').text(len);
    } else {
        $('#charNum' + note_type_id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_sources__6404[4485]['m_desc'] * js_sources__6404[12088]['m_desc'] )){
        $('#ideaNoteNewCount' + note_type_id).removeClass('hidden');
    } else {
        $('#ideaNoteNewCount' + note_type_id).addClass('hidden');
    }

}




function idea_edit_notes_count(read__id) {
    //See if this is a valid text message editing:
    if (!($('#charEditingNum' + read__id).length)) {
        return false;
    }
    //Update count:
    var len = $('#message_body_' + read__id).val().length;
    if (len > js_sources__6404[4485]['m_desc']) {
        $('#charEditingNum' + read__id).addClass('overload').text(len);
    } else {
        $('#charEditingNum' + read__id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_sources__6404[4485]['m_desc'] * js_sources__6404[12088]['m_desc'] )){
        $('#ideaNoteCount' + read__id).removeClass('hidden');
    } else {
        $('#ideaNoteCount' + read__id).addClass('hidden');
    }
}


function create_expert_source(source_title){
    alert('Title: '+source_title);
}


function idea_message_inline_source_search(obj) {

    if(parseInt(js_sources__6404[12678]['m_desc'])){
        obj.textcomplete([
            {
                match: /(^|\s)@(\w*(?:\s*\w*))$/,
                search: function (query, callback) {
                    algolia_index.search(query, {
                        hitsPerPage: 7,
                        filters: 'object__type=4536',
                    })
                        .then(function searchSuccess(content) {
                            if (content.query === query) {
                                callback(content.hits);
                            }
                        })
                        .catch(function searchFailure(err) {
                            console.error(err);
                        });
                },
                template: function (suggestion) {

                    // Returns the highlighted version of the name attribute
                    var return_suggestion = '<div style="padding: 3px 0;">';
                    if(!$('.dropdown-menu .textcomplete-item .add-new').length){

                        console.log(obj.attr('id'));
                        document.getElementById(obj.attr('id')).addEventListener('keyup', e => {
                            console.log('Caret at: ', e.target.selectionStart)
                        });
                        var source_title_parts = obj.val().toUpperCase().split("@");
                        if(source_title_parts[1].length >= 2){
                            return_suggestion += '<a href="javascript:void(0);" onclick="create_expert_source(\'' + source_title_parts[1] + '\')" class="add-new"><span class="icon-block"><i class="fas fa-plus-circle source"></i></span><span class="montserrat source">' + source_title_parts[1] + '</span></a><br />';
                        }
                    }
                    return_suggestion += view_search_result(suggestion) + '</div>';
                    return return_suggestion;

                },
                replace: function (suggestion) {
                    return ( obj.val().substr(0, 1)=='@' ? '' : ' ' ) + '@' + suggestion.object__id + ' ';
                }
            },
        ]);
    }
}



function idea_note_sort_apply(note_type_id) {

    var new_read__sorts = [];
    var sort_rank = 0;
    var this_read__id = 0;

    $(".msg_source_type_" + note_type_id).each(function () {
        this_read__id = parseInt($(this).attr('read__id'));
        if (this_read__id > 0) {
            sort_rank++;
            new_read__sorts[sort_rank] = this_read__id;
        }
    });

    //Update backend if any:
    if(sort_rank > 0){
        $.post("/idea/idea_note_sort", {new_read__sorts: new_read__sorts}, function (data) {
            //Only show message if there was an error:
            if (!data.status) {
                //Show error:
                alert(data.message);
            }
        });
    }
}

function idea_note_sort_load(note_type_id) {

    var inner_content = null;

    var sort_msg = Sortable.create( document.getElementById("idea_notes_list_" + note_type_id) , {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        handle: ".idea_note_sorting", // Restricts sort start click/touch to the specified element
        draggable: ".note_sortable", // Specifies which items inside the element should be sortable
        onUpdate: function (evt/**Event*/) {
            //Apply new sort:
            idea_note_sort_apply(note_type_id);
        },
        //The next two functions resolve a Bug with sorting iframes like YouTube embeds while also making the UI more informative
        onChoose: function (evt/**Event*/) {
            //See if this is a YouTube or Vimeo iFrame that needs to be temporarily deleted:
            var read__id = $(evt.item).attr('read__id');
            if ($('#ul-nav-' + read__id).find('.video-sorting').length !== 0) {
                inner_content = $('#msgbody_' + read__id).html();
                $('#msgbody_' + read__id).css('height', $('#msgbody_' + read__id).height()).html('<i class="fas fa-bars"></i> SORT VIDEO');
            } else {
                inner_content = null;
            }
        },
        onEnd: function (evt/**Event*/) {
            if (inner_content) {
                var read__id = $(evt.item).attr('read__id');
                $('#msgbody_' + read__id).html(inner_content);
            }
        }
    });

}

function idea_notes_modify_start(read__id) {

    //Start editing:
    $("#ul-nav-" + read__id).addClass('in-editing');
    $("#ul-nav-" + read__id + " .edit-off").addClass('hidden');
    $("#ul-nav-" + read__id + " .edit-on").removeClass('hidden');
    $("#ul-nav-" + read__id + ">div").css('width', '100%');

    //Set focus to end of text:
    var textinput = $("#ul-nav-" + read__id + " textarea");
    var data = textinput.val();
    textinput.focus().val('').val(data);
    autosize(textinput); //Adjust height

    //Initiate search:
    idea_message_inline_source_search(textinput);

    //Try to initiate the editor, which only applies to text messages:
    idea_edit_notes_count(read__id);

}

function idea_notes_modify_cancel(read__id) {
    //Revert editing:
    $("#ul-nav-" + read__id).removeClass('in-editing');
    $("#ul-nav-" + read__id + " .edit-off").removeClass('hidden');
    $("#ul-nav-" + read__id + " .edit-on").addClass('hidden');
    $("#ul-nav-" + read__id + ">div").css('width', 'inherit');
}

function idea_note_modify(read__id, note_type_id) {

    //Show loader:
    $("#ul-nav-" + read__id + " .edit-updates").html('<div><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></div>');

    //Revert View:
    idea_notes_modify_cancel(read__id);


    var modify_data = {
        read__id: parseInt(read__id),
        message_read__status: parseInt($("#message_status_" + read__id).val()),
        idea__id: parseInt(idea_loaded_id),
        read__message: $("#ul-nav-" + read__id + " textarea").val(),
    };

    //Update message:
    $.post("/idea/idea_note_modify", modify_data, function (data) {

        if (data.status) {

            //Did we delete this message?
            if(data.delete_from_ui){

                idea_notes_counter(note_type_id, -1);

                //Yes, message was deleted, adjust accordingly:
                $("#ul-nav-" + read__id).html('<div>' + data.message + '</div>');

                //Disapper in a while:
                setTimeout(function ()
                {
                    $("#ul-nav-" + read__id).fadeOut();

                    setTimeout(function () {

                        //Delete first:
                        $("#ul-nav-" + read__id).remove();

                        //Adjust sort for this message type:
                        idea_note_sort_apply(note_type_id);

                    }, 610);
                }, 610);

            } else {

                //IDEA NOTE EDITED...

                //Update text message:
                $("#ul-nav-" + read__id + " .text_message").html(data.message);

                //Update message status:
                $("#ul-nav-" + read__id + " .message_status").html(data.message_new_status_icon);

                //Show success here
                $("#ul-nav-" + read__id + " .edit-updates").html('<b>' + data.success_icon + '</b>');

                lazy_load();

            }

        } else {
            //Oops, some sort of an error, lets
            $("#ul-nav-" + read__id + " .edit-updates").html('<b class="read montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Disapper in a while:
        setTimeout(function () {
            $("#ul-nav-" + read__id + " .edit-updates>b").fadeOut();
        }, 4181);
    });
}



function idea_message_form_lock(note_type_id) {
    $('.save_notes_' + note_type_id).html('<span class="icon-block-lg"><i class="far fa-yin-yang fa-spin"></i></span>').attr('href', '#');
    $('.add_notes_' + note_type_id).addClass('is-working');
    $('#read__message' + note_type_id).prop("disabled", true);
    $('.remove_loading').hide();
}


function idea_message_form_unlock(result, note_type_id) {

    //Update UI to unlock:
    $('.save_notes_' + note_type_id).html('<i class="fas fa-plus"></i>').attr('href', 'javascript:idea_add_note_text('+note_type_id+');');
    $('.add_notes_' + note_type_id).removeClass('is-working');
    $("#read__message" + note_type_id).prop("disabled", false).focus();
    $('.remove_loading').fadeIn();

    //What was the result?
    if (result.status) {

        //Append data:
        $(result.message).insertBefore( ".add_notes_" + note_type_id );

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Load Images:
        lazy_load();

        //Hide any errors:
        setTimeout(function () {
            $(".note_error_"+note_type_id).fadeOut();
        }, 4181);

    } else {

        $(".note_error_"+note_type_id).html('<span class="read">'+result.message+'</span>');

    }
}

function idea_add_note_file(droppedFiles, uploadType, note_type_id) {

    //Prevent multiple concurrent uploads:
    if ($('.box' + note_type_id).hasClass('is-uploading')) {
        return false;
    }

    if (isAdvancedUpload) {

        //Lock message:
        idea_message_form_lock(note_type_id);

        var ajaxData = new FormData($('.box' + note_type_id).get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $('.box' + note_type_id).find('input[type="file"]').attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('idea__id', idea_loaded_id);
        ajaxData.append('note_type_id', note_type_id);

        $.ajax({
            url: '/idea/idea_add_note_file',
            type: $('.box' + note_type_id).attr('method'),
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.box' + note_type_id).removeClass('is-uploading');
            },
            success: function (data) {

                idea_notes_counter(note_type_id, +1);
                idea_message_form_unlock(data, note_type_id);

                //Adjust icon again:
                $('.file_label_' + note_type_id).html('<span class="icon-block"><i class="far fa-paperclip"></i></span>');

            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                idea_message_form_unlock(result, note_type_id);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}

function idea_add_note_text(note_type_id) {

    //Lock message:
    idea_message_form_lock(note_type_id);

    //Update backend:
    $.post("/idea/idea_add_note_text", {

        idea__id: idea_loaded_id, //Synonymous
        read__message: $('#read__message' + note_type_id).val(),
        note_type_id: note_type_id,

    }, function (data) {

        //Raw Inputs Fields if success:
        if (data.status) {

            //Reset input field:
            $("#read__message" + note_type_id).val("");
            idea_notes_count_new(note_type_id);
            idea_notes_counter(note_type_id, +1);

        }

        //Unlock field:
        idea_message_form_unlock(data, note_type_id);

    });

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
                idea_notes_counter(11020, +1);
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
