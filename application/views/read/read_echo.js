


$(document).ready(function () {

    //Lookout for blog link type changes:
    $('.js-ln-create-overview-link').click(function () {
        //Only log engagement if opening:
        if($(this).hasClass('collapsed')){

            var section_en_id = parseInt($(this).attr('section-en-id'));

            //Log this section:
            js_ln_create({
                ln_creator_play_id: js_pl_id, //If we have a user we log here
                ln_type_play_id: 7611, //Blog User Engage
                ln_parent_play_id: section_en_id, //The section this user engaged with
                ln_parent_blog_id: in_loaded_id,
                ln_child_blog_id: 0, //Since they just opened the heading, not a sub-section of Reads Overview
                ln_order: '7611_' + section_en_id + '_' + in_loaded_id, //The section for this blog
            });
        }
    });


    $('.js-ln-create-steps-review').click(function () {
        //Only log engagement if opening:
        if($(this).attr('aria-expanded')=='false'){

            var section_en_id = 7613; //Reads Overview
            var child_in_id = parseInt($(this).attr('blog-id'));

            //Log this section:
            js_ln_create({
                ln_creator_play_id: js_pl_id, //If we have a user we log here
                ln_type_play_id: 7611, //Blog User Engage
                ln_parent_play_id: section_en_id, //The section this user engaged with
                ln_parent_blog_id: in_loaded_id,
                ln_child_blog_id: child_in_id,
                ln_order: section_en_id + '_' + child_in_id + '__' + in_loaded_id,
            });
        }
    });

    $('.js-ln-create-expert-full-list').click(function () {
        //Only log engagement if opening:
        var section_en_id = 7616; //Blog Engage Experts Full List

        //Log this section:
        js_ln_create({
            ln_creator_play_id: js_pl_id, //If we have a user we log here
            ln_type_play_id: 7611, //Blog User Engage
            ln_parent_play_id: 7614, //Expert Overview
            ln_child_play_id: section_en_id, //The section this user engaged with
            ln_parent_blog_id: in_loaded_id,
            ln_order: section_en_id + '__' + in_loaded_id,
        });
    });

    $('.js-ln-create-expert-sources').click(function () {

        //Only log engagement if opening:
        var section_en_id = parseInt($(this).attr('source-type-en-id')); //Determine the source type

        //Log this section:
        js_ln_create({
            ln_creator_play_id: js_pl_id, //If we have a user we log here
            ln_type_play_id: 7611, //Blog User Engage
            ln_parent_play_id: 7614, //Expert Overview
            ln_child_play_id: section_en_id, //The section this user engaged with
            ln_parent_blog_id: in_loaded_id,
            ln_order: section_en_id + '__' + in_loaded_id,
        });
    });


});


function select_answer(in_id){

    //Allow answer to be saved/updated:
    var in_type_play_id = parseInt($('.list-answers').attr('in_type_play_id'));
    var current_status = parseInt($('.ln_answer_'+in_id).attr('is-selected'));

    //Clear all if single selection:
    if(in_type_play_id == 6684){
        //Single Selection, clear all:
        $('.answer-item').attr('is-selected', 0);
        $('.check-icon i').removeClass('fas fa-check-circle').addClass('far fa-circle');
    }

    if(current_status==1){

        //Already Selected, remove selection:
        if(in_type_play_id == 7231){
            //Multi Selection
            $('.ln_answer_'+in_id).attr('is-selected', 0);
            $('.ln_answer_'+in_id+' .check-icon i').removeClass('fas fa-check-square').addClass('far fa-square');
        }

    } else if(current_status==0){

        //Already Selected, remove selection:
        $('.ln_answer_'+in_id).attr('is-selected', 1);
        if(in_type_play_id == 6684){
            //Single Selection
            $('.ln_answer_'+in_id+' .check-icon i').removeClass('far fa-circle').addClass('fas fa-check-circle');
        } else if(in_type_play_id == 7231){
            //Multi Selection
            $('.ln_answer_'+in_id+' .check-icon i').removeClass('far fa-square').addClass('fas fa-check-square');
        }

    }

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
                window.location = '/' + data.next_in_id;
            }, 1597);
        } else {
            $('.result-update').html('<span class="icon-block"><i class="fas fa-exclamation-triangle ispink"></i></span><span class="montserrat ispink">ERROR: '+data.message+'</span>');
        }
    });
}


function blog_skip(en_id, in_id) {
    //Make a AJAX Call to see how many steps would be skipped if we were to continue:
    $.post("/read/actionplan_skip_preview/"+ en_id+"/"+in_id, {}, function (data) {

        var r = confirm(data.skip_step_preview);

        if (r == true) {
            //If confirmed, will skip those steps:
            window.location = "/read/actionplan_skip_apply/"+ en_id+"/"+in_id;
        }
    });
}