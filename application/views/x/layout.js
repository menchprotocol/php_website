

$(document).ready(function () {

    i_note_activate();

    autosize($('#x_reply'));

    //Watchout for file uplods:
    $('.boxUpload').find('input[type="file"]').change(function () {
        x_upload(droppedFiles, 'file');
    });

    //Should we auto start?
    if (isAdvancedUpload) {

        $('.boxUpload').addClass('has-advanced-upload');
        var droppedFiles = false;

        $('.boxboxUpload').on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
        })
        .on('dragover dragenter', function () {
            $('.minerUploader').addClass('is-working');
        })
        .on('dragleave dragend drop', function () {
            $('.minerUploader').removeClass('is-working');
        })
        .on('drop', function (e) {
            droppedFiles = e.originalEvent.dataTransfer.files;
            e.preventDefault();
            x_upload(droppedFiles, 'drop');
        });
    }

});


function go_12211(){
    //Attempts to go next if no submissions:
    if(focus_i__type==6683) {

        //TEXT RESPONSE:
        return x_reply();

    } else if (js_n___7712.includes(focus_i__type)){

        //SELECT ONE/SOME
        return x_select();

    } else if (focus_i__type==7637 && !$('.file_saving_result').html().length ) {

        //Must upload file first:
        alert('You must upload file before going next.');

    } else {

        //Go Next:
        window.location = '/x/x_next/' + focus_i__id;

    }
}



function set_13491(font_size_e__id){

    html_13491(font_size_e__id);

    //Save to profile:
    $.post("/e/e_update_radio", {
        parent_e__id: 13491,
        selected_e__id: font_size_e__id,
        enable_mulitiselect: 0,
        was_previously_selected: false,
    }, function (data) {
        if (!data.status) {
            alert(data.message);
        }
    });

}

function select_answer(i__id){

    //Allow answer to be saved/updated:
    var i__type = parseInt($('.list-answers').attr('i__type'));

    //Clear all if single selection:
    if(i__type == 6684){
        //Single Selection, clear all:
        $('.check-icon i').removeClass('fas').addClass('far');
    }

    //Is setected?
    if($('.x_select_'+i__id+' .fa-circle').hasClass('fas')){

        //Previously Selected, delete selection:
        if(i__type == 7231){
            //Multi Selection
            $('.x_select_'+i__id+' .check-icon i').removeClass('fas').addClass('far');
        }

    } else {

        //Previously Selected, delete selection:
        $('.x_select_'+i__id+' .check-icon i').removeClass('far').addClass('fas');

    }

}


function x_upload(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.boxUpload').hasClass('is-uploading')) {
        return false;
    }

    $('.file_saving_result').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="montserrat">UPLOADING...</span>');

    if (isAdvancedUpload) {

        var ajaxData = new FormData($('.boxUpload').get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $('.boxUpload').find('input[type="file"]').attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('i__id', focus_i__id);

        $.ajax({
            url: '/x/x_upload',
            type: $('.boxUpload').attr('method'),
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.boxUpload').removeClass('is-uploading');
            },
            success: function (data) {
                //Render new file:
                $('.file_saving_result').html(data.message);
                $('.go_next_upload').removeClass('hidden');
                lazy_load();
            },
            error: function (data) {
                //Show Error:
                $('.file_saving_result').html(data.responseText);
            }
        });
    } else {
        // ajax for legacy browsers
    }

}


function x_reply(){
    $.post("/x/x_reply", {
        i__id:focus_i__id,
        x_reply:$('#x_reply').val(),
    }, function (data) {
        if (data.status) {
            //Go to redirect message:
            window.location = '/x/x_next/'+focus_i__id;
        } else {
            //Show error:
            alert(data.message);
        }
    });
}

function x_select(){

    //Check
    var selection_i__id = [];
    $(".answer-item").each(function () {
        var selection_i__id_this = parseInt($(this).attr('selection_i__id'));
        if ($('.x_select_'+selection_i__id_this+' .fa-circle').hasClass('fas')) {
            selection_i__id.push(selection_i__id_this);
        }
    });

    //Show Loading:
    $.post("/x/x_select", {
        focus_i__id:focus_i__id,
        selection_i__id:selection_i__id
    }, function (data) {
        if (data.status) {
            //Go to redirect message:
            window.location = '/x/x_next/'+focus_i__id;
        } else {
            //Show error:
            alert(data.message);
        }
    });
}

