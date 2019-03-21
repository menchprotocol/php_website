
/*
*
* Javascript function to manage Intent Notes
*
* */


function fn___add_first_name() {
    $('#tr_content' + in_id).insertAtCaret('/firstname ');
    fn___count_message();
}


//Count text area characters:
function fn___count_message() {
    //Update count:
    var len = $('#tr_content' + in_id).val().length;
    if (len > tr_content_max_length) {
        $('#charNum' + in_id).addClass('overload').text(len);
    } else {
        $('#charNum' + in_id).removeClass('overload').text(len);
    }
}

function fn___changeMessageEditing(tr_id) {
    //See if this is a valid text message editing:
    if (!($('#charNumEditing' + tr_id).length)) {
        return false;
    }
    //Update count:
    var len = $('#message_body_' + tr_id).val().length;
    if (len > tr_content_max_length) {
        $('#charNumEditing' + tr_id).addClass('overload').text(len);
    } else {
        $('#charNumEditing' + tr_id).removeClass('overload').text(len);
    }
}

var $input = $('.box' + in_id).find('input[type="file"]'),
    $label = $('.box' + in_id).find('label'),
    showFiles = function (files) {
        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
    };

//...

$input.on('drop', function (e) {
    droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
    showFiles(droppedFiles);
});

//...

$input.on('change', function (e) {
    showFiles(e.target.files);
});


function fn___message_load_type(tr_type_entity_id) {

    //Change Nav header:
    $('.iphone-nav-tabs li').removeClass('active');
    $('.nav_' + tr_type_entity_id).addClass('active');

    //Change the global message type variable:
    focus_tr_type_entity_id = tr_type_entity_id;

    //Adjust UI for Messages:
    $('.all_msg').addClass('hidden');
    $('.msg_en_type_' + tr_type_entity_id).removeClass('hidden');

    //Load sorting:
    fn___message_tr_order_load();

}


function fn___initiate_search() {

    //Loadup algolia if not already:
    fn___load_js_algolia();

    $('.msgin').textcomplete([
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


$(document).ready(function () {

    $(".messages-counter-" + in_id, window.parent.document).text(metadata_count);

    fn___initiate_search();

    //Load Nice sort for messages body
    new SimpleBar(document.getElementById('intent_messages' + in_id), {
        // option1: value1,
        // option2: value2
    });

    //Watch for message creation:
    $('#tr_content' + in_id).keydown(function (e) {
        if (e.ctrlKey && e.keyCode == 13) {
            fn___message_create();
        }
    });

    //Have we loaded a different message type?
    if (window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if (hash_parts.length == 3) {
            //Seems right, lets assign:
            focus_tr_type_entity_id = hash_parts[2];
        }
    }

    //Function to control clicks on Message type header:
    fn___message_load_type(focus_tr_type_entity_id);


    $('.iphone-nav-tabs a').click(function (e) {
        //Detect new tab:
        var parts = $(this).attr('href').split("-");

        //Load new message body into view:
        fn___message_load_type(parts[2]);
    });


    //Watchout for file uplods:
    $('.box' + in_id).find('input[type="file"]').change(function () {
        fn___in_new_message_from_attachment(droppedFiles, 'file');
    });


    //Should we auto start?
    if (isAdvancedUpload) {

        $('.box' + in_id).addClass('has-advanced-upload');
        var droppedFiles = false;

        $('.box' + in_id).on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
        })
            .on('dragover dragenter', function () {
                $('.add-msg' + in_id).addClass('is-working');
            })
            .on('dragleave dragend drop', function () {
                $('.add-msg' + in_id).removeClass('is-working');
            })
            .on('drop', function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                e.preventDefault();
                fn___in_new_message_from_attachment(droppedFiles, 'drop');
            });
    }
});


function fn___message_tr_order_apply(tr_type_entity_id) {

    var new_tr_orders = [];
    var sort_rank = 0;
    var this_tr_id = 0;

    $("#message-sorting>div.msg_en_type_" + tr_type_entity_id).each(function () {
        this_tr_id = parseInt($(this).attr('tr-id'));
        if (this_tr_id > 0) {
            sort_rank++;
            new_tr_orders[sort_rank] = this_tr_id;
        }
    });

    //Update backend:
    $.post("/ledger/fn___tr_order_update", {new_tr_orders: new_tr_orders}, function (data) {
        //Only show message if there was an error:
        if (!data.status) {
            //Show error:
            alert('ERROR: ' + data.message);
        }
    });

}

function fn___message_tr_order_load() {

    var inner_content = null;

    var sort_msg = Sortable.create( document.getElementById("message-sorting") , {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        handle: ".message-sorting", // Restricts sort start click/touch to the specified element
        draggable: ".is_level2_sortable", // Specifies which items inside the element should be sortable
        onUpdate: function (evt/**Event*/) {
            //Apply new sort:
            fn___message_tr_order_apply(focus_tr_type_entity_id);
        },
        //The next two functions resolve a Bug with sorting iframes like YouTube embeds while also making the UI more informative
        onChoose: function (evt/**Event*/) {
            //See if this is a YouTube or Vimeo iFrame that needs to be temporarily removed:
            var tr_id = $(evt.item).attr('tr-id');
            if ($('#ul-nav-' + tr_id).find('.video-sorting').length !== 0) {
                inner_content = $('#msgbody_' + tr_id).html();
                $('#msgbody_' + tr_id).css('height', $('#msgbody_' + tr_id).height()).html('<i class="fas fa-sort"></i> Drag up/down to sort video');
            } else {
                inner_content = null;
            }
        },
        onEnd: function (evt/**Event*/) {
            if (inner_content) {
                var tr_id = $(evt.item).attr('tr-id');
                $('#msgbody_' + tr_id).html(inner_content);
            }
        }
    });

}

function fn___message_modify_start(tr_id, initial_tr_type_entity_id) {

    //Start editing:
    $("#ul-nav-" + tr_id).addClass('in-editing');
    $("#ul-nav-" + tr_id + " .edit-off").addClass('hidden');
    $("#ul-nav-" + tr_id + " .edit-on").removeClass('hidden');
    $("#ul-nav-" + tr_id + ">div").css('width', '100%');
    $("#ul-nav-" + tr_id + " textarea").focus();

    //Initiate search:
    fn___initiate_search();

    //Try to initiate the editor, which only applies to text messages:
    fn___changeMessageEditing(tr_id);

    //Watch typing:
    $(document).keyup(function (e) {
        //Watch for action keys:
        if (e.ctrlKey && e.keyCode === 13) {
            fn___in_message_modify(tr_id, initial_tr_type_entity_id);
        } else if (e.keyCode === 27) {
            fn___message_modify_cancel(tr_id);
        }
    });
}

function fn___message_modify_cancel(tr_id, success=0) {
    //Revert editing:
    $("#ul-nav-" + tr_id).removeClass('in-editing');
    $("#ul-nav-" + tr_id + " .edit-off").removeClass('hidden');
    $("#ul-nav-" + tr_id + " .edit-on").addClass('hidden');
    $("#ul-nav-" + tr_id + ">div").css('width', 'inherit');
}

function fn___in_message_modify(tr_id, initial_tr_type_entity_id) {

    //Show loader:
    $("#ul-nav-" + tr_id + " .edit-updates").html('<div><i class="fas fa-spinner fa-spin"></i></div>');

    //Revert View:
    fn___message_modify_cancel(tr_id, 1);

    //Detect new status, and a potential change:
    var new_message_tr_status = $("#message_tr_status_" + tr_id).val();

    //Update message:
    $.post("/intents/fn___in_message_modify", {

        tr_id: tr_id,
        tr_content: $("#ul-nav-" + tr_id + " textarea").val(),
        initial_tr_type_entity_id: initial_tr_type_entity_id,
        new_message_tr_status: new_message_tr_status,
        in_id: in_id,

    }, function (data) {

        if (data.status) {

            //Saving successful...

            //Did we remove this message?
            if(new_message_tr_status < 0){

                //Yes, message was removed, adjust accordingly:
                $("#ul-nav-" + tr_id).html('<div>' + data.message + '</div>');

                //Adjust counter by one:
                metadata_count--;
                $(".messages-counter-" + in_id, window.parent.document).text(metadata_count);

                //Disapper in a while:
                setTimeout(function ()
                {
                    $("#ul-nav-" + tr_id).fadeOut();

                    setTimeout(function () {

                        //Remove first:
                        $("#ul-nav-" + tr_id).remove();

                        //Adjust sort for this message type:
                        fn___message_tr_order_apply(focus_tr_type_entity_id);

                    }, 610);
                }, 610);

            } else {

                //Nope, message was just edited...

                //Update text message:
                $("#ul-nav-" + tr_id + " .text_message").html(data.message);

                //Update message status:
                $("#ul-nav-" + tr_id + " .message_status").html(data.message_new_status_icon);

                //Show success here
                $("#ul-nav-" + tr_id + " .edit-updates").html('<b>' + data.success_icon + '</b>');

            }

        } else {
            //Oops, some sort of an error, lets
            $("#ul-nav-" + tr_id + " .edit-updates").html('<b style="color:#FF0000 !important; line-height: 110% !important;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</b>');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Disapper in a while:
        setTimeout(function () {
            $("#ul-nav-" + tr_id + " .edit-updates>b").fadeOut();
        }, 5000);
    });
}


var button_value = null;

function fn___message_form_lock() {
    button_value = $('#add_message_' + focus_tr_type_entity_id + '_' + in_id).html();
    $('#add_message_' + focus_tr_type_entity_id + '_' + in_id).html('<span><i class="fas fa-spinner fa-spin"></i></span>');
    $('#add_message_' + focus_tr_type_entity_id + '_' + in_id).attr('href', '#');

    $('.add-msg' + in_id).addClass('is-working');
    $('#tr_content' + in_id).prop("disabled", true);
    $('.remove_loading').hide();
}


function fn___message_form_unlock(result) {

    //Update UI to unlock:
    $('.add-msg' + in_id).removeClass('is-working');
    $('.remove_loading').fadeIn();

    $('#add_message_' + focus_tr_type_entity_id + '_' + in_id).html(button_value);
    $('#add_message_' + focus_tr_type_entity_id + '_' + in_id).attr('href', 'javascript:fn___message_create();');

    //Remove possible "No message" info box:
    if ($('.no-messages' + in_id + '_' + focus_tr_type_entity_id).length) {
        $('.no-messages' + in_id + '_' + focus_tr_type_entity_id).hide();
    }

    //Reset Focus:
    $("#tr_content" + in_id).prop("disabled", false).focus();

    //What was the result?
    if (result.status) {

        //Append data:
        $("#message-sorting").append(result.message);

        //Resort/Re-adjust:
        fn___message_load_type(focus_tr_type_entity_id);

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Hide any errors:
        setTimeout(function () {
            $(".i_error").fadeOut();
        }, 3000);

    } else {

        alert('ERROR: ' + result.message);

    }
}

function fn___in_new_message_from_attachment(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.box' + in_id).hasClass('is-uploading')) {
        return false;
    }

    if (isAdvancedUpload) {

        //Lock message:
        fn___message_form_lock();

        var ajaxData = new FormData($('.box' + in_id).get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $input.attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('focus_tr_type_entity_id', focus_tr_type_entity_id);
        ajaxData.append('in_id', in_id);

        $.ajax({
            url: '/intents/fn___in_new_message_from_attachment',
            type: $('.box' + in_id).attr('method'),
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.box' + in_id).removeClass('is-uploading');
            },
            success: function (data) {
                fn___message_form_unlock(data);

                //Adjust Action Plan counter by one:
                metadata_count++;
                $(".messages-counter-" + in_id, window.parent.document).text(metadata_count);
            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                fn___message_form_unlock(result);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}

function fn___message_create() {

    if ($('#tr_content' + in_id).val().length == 0) {
        alert('ERROR: Enter a message');
        return false;
    }

    //Lock message:
    fn___message_form_lock();

    //Update backend:
    $.post("/intents/fn___in_new_message_from_text", {

        in_id: in_id, //Synonymous
        tr_content: $('#tr_content' + in_id).val(),
        focus_tr_type_entity_id: focus_tr_type_entity_id,

    }, function (data) {

        //Raw Inputs Fields if success:
        if (data.status) {

            //Adjust counter by one:
            metadata_count++;
            $(".messages-counter-" + in_id, window.parent.document).text(metadata_count);

            //Reset input field:
            $("#tr_content" + in_id).val("");
            fn___count_message();

        }

        //Unlock field:
        fn___message_form_unlock(data);

    });
}