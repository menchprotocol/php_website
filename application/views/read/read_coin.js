

$(document).ready(function () {




    autosize($('#read_text_answer'));


    //Lookout for idea link type changes:
    $('.js-ln-create-overview-link').click(function () {
        //Only log engagement if opening:
        if($(this).hasClass('collapsed')){

            var section_en_id = parseInt($(this).attr('section-en-id'));

            //Log this section:
            js_ln_create({
                ln_creator_source_id: js_pl_id, //If we have a user we log here
                ln_type_source_id: 7611, //Idea User Engage
                ln_parent_source_id: section_en_id, //The section this user engaged with
                ln_previous_idea_id: in_loaded_id,
                ln_next_idea_id: 0, //Since they just opened the heading, not a sub-section of Reads Overview
                ln_order: '7611_' + section_en_id + '_' + in_loaded_id, //The section for this idea
            });
        }
    });


    $('.js-ln-create-steps-review').click(function () {
        //Only log engagement if opening:
        if($(this).attr('aria-expanded')=='false'){

            var section_en_id = 7613; //Reads Overview
            var child_in_id = parseInt($(this).attr('idea-id'));

            //Log this section:
            js_ln_create({
                ln_creator_source_id: js_pl_id, //If we have a user we log here
                ln_type_source_id: 7611, //Idea User Engage
                ln_parent_source_id: section_en_id, //The section this user engaged with
                ln_previous_idea_id: in_loaded_id,
                ln_next_idea_id: child_in_id,
                ln_order: section_en_id + '_' + child_in_id + '__' + in_loaded_id,
            });
        }
    });

    $('.js-ln-create-expert-full-list').click(function () {
        //Only log engagement if opening:
        var section_en_id = 7616; //Idea Engage Experts Full List

        //Log this section:
        js_ln_create({
            ln_creator_source_id: js_pl_id, //If we have a user we log here
            ln_type_source_id: 7611, //Idea User Engage
            ln_parent_source_id: 7614, //Expert Overview
            ln_child_source_id: section_en_id, //The section this user engaged with
            ln_previous_idea_id: in_loaded_id,
            ln_order: section_en_id + '__' + in_loaded_id,
        });
    });

    $('.js-ln-create-expert-sources').click(function () {

        //Only log engagement if opening:
        var section_en_id = parseInt($(this).attr('source-type-en-id')); //Determine the source type

        //Log this section:
        js_ln_create({
            ln_creator_source_id: js_pl_id, //If we have a user we log here
            ln_type_source_id: 7611, //Idea User Engage
            ln_parent_source_id: 7614, //Expert Overview
            ln_child_source_id: section_en_id, //The section this user engaged with
            ln_previous_idea_id: in_loaded_id,
            ln_order: section_en_id + '__' + in_loaded_id,
        });
    });



    //Watchout for file uplods:
    $('.boxUpload').find('input[type="file"]').change(function () {
        read_file_upload(droppedFiles, 'file');
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
                $('.readerUploader').addClass('is-working');
            })
            .on('dragleave dragend drop', function () {
                $('.readerUploader').removeClass('is-working');
            })
            .on('drop', function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                e.preventDefault();
                read_file_upload(droppedFiles, 'drop');
            });
    }


});


function select_answer(in_id){

    //Allow answer to be saved/updated:
    var in_type_source_id = parseInt($('.list-answers').attr('in_type_source_id'));
    var current_status = parseInt($('.ln_answer_'+in_id).attr('is-selected'));

    //Clear all if single selection:
    if(in_type_source_id == 6684){
        //Single Selection, clear all:
        $('.answer-item').attr('is-selected', 0);
        $('.check-icon i').removeClass('fas').addClass('far');
    }

    if(current_status==1){

        //Already Selected, remove selection:
        if(in_type_source_id == 7231){
            //Multi Selection
            $('.ln_answer_'+in_id).attr('is-selected', 0);
            $('.ln_answer_'+in_id+' .check-icon i').removeClass('fas').addClass('far');
        }

    } else if(current_status==0){

        //Already Selected, remove selection:
        $('.ln_answer_'+in_id).attr('is-selected', 1);
        $('.ln_answer_'+in_id+' .check-icon i').removeClass('far').addClass('fas');

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
        ajaxData.append('in_id', in_loaded_id);

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
        in_id:in_loaded_id,
        read_text_answer:$('#read_text_answer').val(),
    }, function (data) {
        if (data.status) {
            $('.text_saving_result').html('<span class="icon-block"><i class="fas fa-check-circle"></i></span><span class="montserrat">'+data.message+'</span>');
            setTimeout(function () {
                //Go to redirect message:
                window.location = '/read/next/'+in_loaded_id;
            }, 987);
        } else {
            $('.text_saving_result').html('<span class="icon-block"><i class="fad fa-exclamation-triangle read"></i></span><span class="montserrat read">Alert: '+data.message+'</span>');
        }
    });
}

function read_answer(){

    //Check
    var answered_ins = [];
    $(".answer-item").each(function () {
        if ($(this).attr('is-selected')=='1') {
            answered_ins.push(parseInt($(this).attr('answered_ins')));
        }
    });

    //Show Loading:
    $('.result-update').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="montserrat">SAVING...</span>');
    $.post("/read/read_answer", {
        in_loaded_id:in_loaded_id,
        answered_ins:answered_ins
    }, function (data) {
        if (data.status) {
            $('.result-update').html('<span class="icon-block"><i class="fas fa-check-circle"></i></span><span class="montserrat">'+data.message+'</span>');
            setTimeout(function () {
                //Go to redirect message:
                window.location = '/read/next/'+in_loaded_id;
            }, 987);
        } else {
            $('.result-update').html('<span class="icon-block"><i class="fad fa-exclamation-triangle read"></i></span><span class="montserrat read">Alert: '+data.message+'</span>');
        }
    });
}


function in_skip(en_id, in_id) {
    //Make a AJAX Call to see how many steps would be skipped if we were to continue:
    $.post("/read/actionplan_skip_preview/"+ en_id+"/"+in_id, {}, function (data) {

        var r = confirm(data.skip_step_preview);

        if (r == true) {
            //If confirmed, will skip those steps:
            window.location = "/read/actionplan_skip_apply/"+ en_id+"/"+in_id;
        }
    });
}