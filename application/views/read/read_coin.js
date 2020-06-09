

$(document).ready(function () {

    idea_note_activate();

    autosize($('#read_text_answer'));

    //Watchout for file uplods:
    $('.boxUpload').find('input[type="file"]').change(function () {
        read_file_upload(droppedFiles, 'file');
    });

    //Move main reads, if any:
    $('.focus_reads_top').html($('.focus_reads_bottom').html());

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
                read_file_upload(droppedFiles, 'drop');
            });
    }


});



function select_answer(idea__id){

    //Allow answer to be saved/updated:
    var idea__type = parseInt($('.list-answers').attr('idea__type'));
    var current_status = parseInt($('.read_answer_'+idea__id).attr('is-selected'));

    //Clear all if single selection:
    if(idea__type == 6684){
        //Single Selection, clear all:
        $('.answer-item').attr('is-selected', 0);
        $('.check-icon i').removeClass('fas').addClass('far');
    }

    if(current_status==1){

        //Previously Selected, delete selection:
        if(idea__type == 7231){
            //Multi Selection
            $('.read_answer_'+idea__id).attr('is-selected', 0);
            $('.read_answer_'+idea__id+' .check-icon i').removeClass('fas').addClass('far');
        }

    } else if(current_status==0){

        //Previously Selected, delete selection:
        $('.read_answer_'+idea__id).attr('is-selected', 1);
        $('.read_answer_'+idea__id+' .check-icon i').removeClass('far').addClass('fas');

    }

}


function read_file_upload(droppedFiles, uploadType) {

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
        ajaxData.append('idea__id', idea_loaded_id);

        $.ajax({
            url: '/read/read_file_upload',
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


function read_text_answer(){
    //Show Loading:
    $('.text_saving_result').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="montserrat">SAVING...</span>');
    $.post("/read/read_text_answer", {
        idea__id:idea_loaded_id,
        read_text_answer:$('#read_text_answer').val(),
    }, function (data) {
        if (data.status) {
            $('.text_saving_result').html('<span class="icon-block"><i class="fas fa-check-circle"></i></span><span class="montserrat">'+data.message+'</span>');
            setTimeout(function () {
                //Go to redirect message:
                window.location = '/read/next/'+idea_loaded_id;
            }, 987);
        } else {
            $('.text_saving_result').html('<span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span><span class="read montserrat">'+data.message+'</span>');
        }
    });
}

function read_answer(){

    //Check
    var answered_ideas = [];
    $(".answer-item").each(function () {
        if ($(this).attr('is-selected')=='1') {
            answered_ideas.push(parseInt($(this).attr('answered_ideas')));
        }
    });

    //Show Loading:
    $('.result-update').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="montserrat">SAVING...</span>');
    $.post("/read/read_answer", {
        idea_loaded_id:idea_loaded_id,
        answered_ideas:answered_ideas
    }, function (data) {
        if (data.status) {
            $('.result-update').html('<span class="icon-block"><i class="fas fa-check-circle"></i></span><span class="montserrat">'+data.message+'</span>');
            setTimeout(function () {
                //Go to redirect message:
                window.location = '/read/next/'+idea_loaded_id;
            }, 987);
        } else {
            $('.result-update').html('<span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span><span class="read montserrat">'+data.message+'</span>');
        }
    });
}

