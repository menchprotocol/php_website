
/*
*
* Javascript function to manage Intent Notes
*
* */


function in_note_insert_string(focus_ln_type_entity_id, add_string) {
    $('#ln_content' + focus_ln_type_entity_id).insertAtCaret(add_string);
    in_new_note_count(focus_ln_type_entity_id);
}


//Count text area characters:
function in_new_note_count(focus_ln_type_entity_id) {

    //Update count:
    var len = $('#ln_content' + focus_ln_type_entity_id).val().length;
    if (len > js_en_all_6404[11073]['m_desc']) {
        $('#charNum' + focus_ln_type_entity_id).addClass('overload').text(len);
    } else {
        $('#charNum' + focus_ln_type_entity_id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_en_all_6404[11073]['m_desc'] * 0.80 )){
        $('#blogNoteNewCount' + focus_ln_type_entity_id).removeClass('hidden');
    } else {
        $('#blogNoteNewCount' + focus_ln_type_entity_id).addClass('hidden');
    }
}

function in_edit_note_count(ln_id) {
    //See if this is a valid text message editing:
    if (!($('#charEditingNum' + ln_id).length)) {
        return false;
    }
    //Update count:
    var len = $('#message_body_' + ln_id).val().length;
    if (len > js_en_all_6404[11073]['m_desc']) {
        $('#charEditingNum' + ln_id).addClass('overload').text(len);
    } else {
        $('#charEditingNum' + ln_id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_en_all_6404[11073]['m_desc'] * 0.80 )){
        $('#blogNoteCount' + ln_id).removeClass('hidden');
    } else {
        $('#blogNoteCount' + ln_id).addClass('hidden');
    }
}


function in_message_inline_en_search() {

    //Loadup algolia if not already:
    load_js_algolia();

    $('.note-textarea').textcomplete([
        {
            match: /(^|\s)@(\w*(?:\s*\w*))$/,
            search: function (query, callback) {
                algolia_index.search(query, {
                    hitsPerPage: 5,
                    filters: 'alg_obj_is_in=0',
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
            template: function (hit) {
                // Returns the highlighted version of the name attribute
                return '<span class="inline34">@' + hit.alg_obj_id + '</span> ' + hit._highlightResult.alg_obj_name.value;
            },
            replace: function (hit) {
                return ' @' + hit.alg_obj_id + ' ';
            }
        },
        {
            match: /(^|\s)#(\w*(?:\s*\w*))$/,
            search: function (query, callback) {
                algolia_index.search(query, {
                    hitsPerPage: 5,
                    filters: 'alg_obj_is_in=1',
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
            template: function (hit) {
                // Returns the highlighted version of the name attribute
                return '<span class="inline34">#' + hit.alg_obj_id + '</span> ' + hit._highlightResult.alg_obj_name.value;
            },
            replace: function (hit) {
                return ' #' + hit.alg_obj_id + ' ';
            }
        },
    ]);
}

//Watch typing:
$(document).keyup(function (e) {
    //Watch for action keys:
    if (e.keyCode === 27) {
        modify_cancel();
    }
});


$(document).ready(function () {

    //Initiate @ search for all note text areas:
    in_message_inline_en_search();

    //Loop through all new note inboxes:
    $(".new-note").each(function () {

        var focus_ln_type_entity_id = parseInt($(this).attr('note-type-id'));

        autosize($(this));

        //Activate sorting:
        in_notes_sort_load(focus_ln_type_entity_id);

        var showFiles = function (files) {
            $('.box' + focus_ln_type_entity_id).find('label').text(files.length > 1 ? ($('.box' + focus_ln_type_entity_id).find('input[type="file"]').attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
        };

        $('.box' + focus_ln_type_entity_id).find('input[type="file"]').on('drop', function (e) {
            droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
            showFiles(droppedFiles);
        });

        $('.box' + focus_ln_type_entity_id).find('input[type="file"]').on('change', function (e) {
            showFiles(e.target.files);
        });

        //Watch for message creation:
        $('#ln_content' + focus_ln_type_entity_id).keydown(function (e) {
            if (e.ctrlKey && e.keyCode == 13) {
                in_note_add();
            }
        });

        //Watchout for file uplods:
        $('.box' + focus_ln_type_entity_id).find('input[type="file"]').change(function () {
            in_message_from_attachment(droppedFiles, 'file', focus_ln_type_entity_id);
        });


        //Should we auto start?
        if (isAdvancedUpload) {

            $('.box' + focus_ln_type_entity_id).addClass('has-advanced-upload');
            var droppedFiles = false;

            $('.box' + focus_ln_type_entity_id).on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
            })
                .on('dragover dragenter', function () {
                    $('.add_note_' + focus_ln_type_entity_id).addClass('is-working');
                })
                .on('dragleave dragend drop', function () {
                    $('.add_note_' + focus_ln_type_entity_id).removeClass('is-working');
                })
                .on('drop', function (e) {
                    droppedFiles = e.originalEvent.dataTransfer.files;
                    e.preventDefault();
                    in_message_from_attachment(droppedFiles, 'drop', focus_ln_type_entity_id);
                });
        }

    });
});


function in_notes_sort_apply(focus_ln_type_entity_id) {

    var new_ln_orders = [];
    var sort_rank = 0;
    var this_ln_id = 0;

    $("#in_notes_list_"+focus_ln_type_entity_id+">div.msg_en_type_" + focus_ln_type_entity_id).each(function () {
        this_ln_id = parseInt($(this).attr('tr-id'));
        if (this_ln_id > 0) {
            sort_rank++;
            new_ln_orders[sort_rank] = this_ln_id;
        }
    });

    //Update backend if any:
    if(sort_rank > 0){
        $.post("/blog/in_notes_sort", {new_ln_orders: new_ln_orders}, function (data) {
            //Only show message if there was an error:
            if (!data.status) {
                //Show error:
                alert('ERROR: ' + data.message);
            }
        });
    }
}

function in_notes_sort_load(focus_ln_type_entity_id) {

    var inner_content = null;

    var sort_msg = Sortable.create( document.getElementById("in_notes_list_" + focus_ln_type_entity_id) , {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        handle: ".blog_note_sorting", // Restricts sort start click/touch to the specified element
        draggable: ".blogs_sortable", // Specifies which items inside the element should be sortable
        onUpdate: function (evt/**Event*/) {
            //Apply new sort:
            in_notes_sort_apply(focus_ln_type_entity_id);
        },
        //The next two functions resolve a Bug with sorting iframes like YouTube embeds while also making the UI more informative
        onChoose: function (evt/**Event*/) {
            //See if this is a YouTube or Vimeo iFrame that needs to be temporarily removed:
            var ln_id = $(evt.item).attr('tr-id');
            if ($('#ul-nav-' + ln_id).find('.video-sorting').length !== 0) {
                inner_content = $('#msgbody_' + ln_id).html();
                $('#msgbody_' + ln_id).css('height', $('#msgbody_' + ln_id).height()).html('<i class="fas fa-sort"></i> Drag up/down to sort video');
            } else {
                inner_content = null;
            }
        },
        onEnd: function (evt/**Event*/) {
            if (inner_content) {
                var ln_id = $(evt.item).attr('tr-id');
                $('#msgbody_' + ln_id).html(inner_content);
            }
        }
    });

}

function in_note_modify_start(ln_id, focus_ln_type_entity_id) {

    //Start editing:
    $("#ul-nav-" + ln_id).addClass('in-editing');
    $("#ul-nav-" + ln_id + " .edit-off").addClass('hidden');
    $("#ul-nav-" + ln_id + " .edit-on").removeClass('hidden');
    $("#ul-nav-" + ln_id + ">div").css('width', '100%');

    //Set focus to end of text:
    var textinput = $("#ul-nav-" + ln_id + " textarea");
    var data = textinput.val();
    textinput.focus().val('').val(data);
    autosize(textinput); //Adjust height

    //Initiate search:
    in_message_inline_en_search();

    //Try to initiate the editor, which only applies to text messages:
    in_edit_note_count(ln_id);

    //Watch typing:
    $(document).keyup(function (e) {
        //Watch for action keys:
        if (e.ctrlKey && e.keyCode == 13) {
            in_note_modify_save(ln_id, focus_ln_type_entity_id);
        }
    });
}

function in_note_modify_cancel(ln_id, success=0) {
    //Revert editing:
    $("#ul-nav-" + ln_id).removeClass('in-editing');
    $("#ul-nav-" + ln_id + " .edit-off").removeClass('hidden');
    $("#ul-nav-" + ln_id + " .edit-on").addClass('hidden');
    $("#ul-nav-" + ln_id + ">div").css('width', 'inherit');
}

function in_note_modify_save(ln_id, focus_ln_type_entity_id) {

    //Show loader:
    $("#ul-nav-" + ln_id + " .edit-updates").html('<div><i class="far fa-yin-yang fa-spin"></i></div>');

    //Revert View:
    in_note_modify_cancel(ln_id, 1);


    var modify_data = {
        ln_id: parseInt(ln_id),
        message_ln_status_entity_id: parseInt($("#message_status_" + ln_id).val()),
        in_id: parseInt(in_loaded_id),
        ln_content: $("#ul-nav-" + ln_id + " textarea").val(),
    };

    //Update message:
    $.post("/blog/in_note_modify_save", modify_data, function (data) {

        if (data.status) {

            //Did we remove this message?
            if(data.remove_from_ui){

                //Yes, message was removed, adjust accordingly:
                $("#ul-nav-" + ln_id).html('<div>' + data.message + '</div>');

                //Disapper in a while:
                setTimeout(function ()
                {
                    $("#ul-nav-" + ln_id).fadeOut();

                    setTimeout(function () {

                        //Remove first:
                        $("#ul-nav-" + ln_id).remove();

                        //Adjust sort for this message type:
                        in_notes_sort_apply(focus_ln_type_entity_id);

                    }, 610);
                }, 610);

            } else {

                //Nope, message was just edited...

                //Update text message:
                $("#ul-nav-" + ln_id + " .text_message").html(data.message);

                //Update message status:
                $("#ul-nav-" + ln_id + " .message_status").html(data.message_new_status_icon);

                //Show success here
                $("#ul-nav-" + ln_id + " .edit-updates").html('<b>' + data.success_icon + '</b>');

            }

        } else {
            //Oops, some sort of an error, lets
            $("#ul-nav-" + ln_id + " .edit-updates").html('<b style="color:#FF0000 !important; line-height: 110% !important;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</b>');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Disapper in a while:
        setTimeout(function () {
            $("#ul-nav-" + ln_id + " .edit-updates>b").fadeOut();
        }, 4181);
    });
}



function in_message_form_lock(focus_ln_type_entity_id) {
    $('.save_note_' + focus_ln_type_entity_id).html('<span class="icon-block-lg"><i class="far fa-yin-yang fa-spin"></i></span>').attr('href', '#');
    $('.add_note_' + focus_ln_type_entity_id).addClass('is-working');
    $('#ln_content' + focus_ln_type_entity_id).prop("disabled", true);
    $('.remove_loading').hide();
}


function in_message_form_unlock(result, focus_ln_type_entity_id) {

    //Update UI to unlock:
    $('.save_note_' + focus_ln_type_entity_id).html('SAVE').attr('href', 'javascript:in_note_add('+focus_ln_type_entity_id+');');
    $('.add_note_' + focus_ln_type_entity_id).removeClass('is-working');
    $("#ln_content" + focus_ln_type_entity_id).prop("disabled", false).focus();
    $('.remove_loading').fadeIn();


    //Remove possible "No message" info box:
    if ($('.missing_note_' + focus_ln_type_entity_id).length) {
        $('.missing_note_' + focus_ln_type_entity_id).hide();
    }

    //What was the result?
    if (result.status) {

        //Append data:
        $("#in_notes_list_"+focus_ln_type_entity_id).append(result.message);

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Hide any errors:
        setTimeout(function () {
            $(".note_error_"+focus_ln_type_entity_id).fadeOut();
        }, 4181);

    } else {

        $(".note_error_"+focus_ln_type_entity_id).html(result.message);

    }
}

function in_message_from_attachment(droppedFiles, uploadType, focus_ln_type_entity_id) {

    //Prevent multiple concurrent uploads:
    if ($('.box' + focus_ln_type_entity_id).hasClass('is-uploading')) {
        return false;
    }

    if (isAdvancedUpload) {

        //Lock message:
        in_message_form_lock(focus_ln_type_entity_id);

        var ajaxData = new FormData($('.box' + focus_ln_type_entity_id).get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $('.box' + focus_ln_type_entity_id).find('input[type="file"]').attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('in_id', in_loaded_id);
        ajaxData.append('focus_ln_type_entity_id', focus_ln_type_entity_id);

        $.ajax({
            url: '/blog/in_message_from_attachment',
            type: $('.box' + focus_ln_type_entity_id).attr('method'),
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.box' + focus_ln_type_entity_id).removeClass('is-uploading');
            },
            success: function (data) {

                in_message_form_unlock(data, focus_ln_type_entity_id);

            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                in_message_form_unlock(result, focus_ln_type_entity_id);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}

function in_note_add(focus_ln_type_entity_id) {

    if ($('#ln_content' + focus_ln_type_entity_id).val().length == 0) {
        alert('ERROR: Enter a message');
        return false;
    }

    //Lock message:
    in_message_form_lock(focus_ln_type_entity_id);

    //Update backend:
    $.post("/blog/in_new_message_from_text", {

        in_id: in_loaded_id, //Synonymous
        ln_content: $('#ln_content' + focus_ln_type_entity_id).val(),
        focus_ln_type_entity_id: focus_ln_type_entity_id,

    }, function (data) {

        //Raw Inputs Fields if success:
        if (data.status) {

            //Reset input field:
            $("#ln_content" + focus_ln_type_entity_id).val("");
            in_new_note_count(focus_ln_type_entity_id);

        }

        //Unlock field:
        in_message_form_unlock(data, focus_ln_type_entity_id);

    });
}