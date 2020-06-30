

$(document).ready(function () {

    i_note_activate();

    autosize($('#x_respond'));

    //Watchout for file uplods:
    $('.boxUpload').find('input[type="file"]').change(function () {
        x_upload(droppedFiles, 'file');
    });

    //Move main discovers, if any:
    $('.focus_discoveries_top').html($('.focus_discoveries_bottom').html());

    //Should we auto start?
    if (isAdvancedUpload) {

        $('.boxUpload').addClass('has-advanced-upload');
        var droppedFiles = false;

        $('.boxboxUpload').on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
        })
        .on('dragover dragenter', function () {
            $('.playerUploader').addClass('is-working');
        })
        .on('dragleave dragend drop', function () {
            $('.playerUploader').removeClass('is-working');
        })
        .on('drop', function (e) {
            droppedFiles = e.originalEvent.dataTransfer.files;
            e.preventDefault();
            x_upload(droppedFiles, 'drop');
        });
    }

});


function select_answer(i__id){

    //Allow answer to be saved/updated:
    var i__type = parseInt($('.list-answers').attr('i__type'));
    var current_status = parseInt($('.x_answer_'+i__id).attr('is-selected'));

    //Clear all if single selection:
    if(i__type == 6684){
        //Single Selection, clear all:
        $('.answer-item').attr('is-selected', 0);
        $('.check-icon i').removeClass('fas').addClass('far');
    }

    if(current_status==1){

        //Previously Selected, delete selection:
        if(i__type == 7231){
            //Multi Selection
            $('.x_answer_'+i__id).attr('is-selected', 0);
            $('.x_answer_'+i__id+' .check-icon i').removeClass('fas').addClass('far');
        }

    } else if(current_status==0){

        //Previously Selected, delete selection:
        $('.x_answer_'+i__id).attr('is-selected', 1);
        $('.x_answer_'+i__id+' .check-icon i').removeClass('far').addClass('fas');

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
        ajaxData.append('i__id', i_loaded_id);

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


function x_respond(){
    //Show Loading:
    $('.text_saving_result').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="montserrat">SAVING...</span>');
    $.post("/x/x_respond", {
        i__id:i_loaded_id,
        x_respond:$('#x_respond').val(),
    }, function (data) {
        if (data.status) {
            $('.text_saving_result').html('<span class="icon-block"><i class="fas fa-check-circle"></i></span><span class="montserrat">'+data.message+'</span>');
            setTimeout(function () {
                //Go to redirect message:
                window.location = '/x/x_next/'+i_loaded_id;
            }, 987);
        } else {
            $('.text_saving_result').html('<span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span><span class="discover montserrat">'+data.message+'</span>');
        }
    });
}

function x_answer(){

    //Check
    var answered_ideas = [];
    $(".answer-item").each(function () {
        if ($(this).attr('is-selected')=='1') {
            answered_ideas.push(parseInt($(this).attr('answered_ideas')));
        }
    });

    //Show Loading:
    $('.result-update').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="montserrat">SAVING...</span>');
    $.post("/x/x_answer", {
        i_loaded_id:i_loaded_id,
        answered_ideas:answered_ideas
    }, function (data) {
        if (data.status) {
            $('.result-update').html('<span class="icon-block"><i class="fas fa-check-circle"></i></span><span class="montserrat">'+data.message+'</span>');
            setTimeout(function () {
                //Go to redirect message:
                window.location = '/x/x_next/'+i_loaded_id;
            }, 987);
        } else {
            $('.result-update').html('<span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span><span class="discover montserrat">'+data.message+'</span>');
        }
    });
}

