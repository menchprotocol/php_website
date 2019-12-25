


$(document).ready(function () {

    //Lookout for blog link type changes:
    $('.js-ln-create-overview-link').click(function () {
        //Only log engagement if opening:
        if($(this).hasClass('collapsed')){

            var section_en_id = parseInt($(this).attr('section-en-id'));

            //Log this section:
            js_ln_create({
                ln_creator_player_id: js_pl_id, //If we have a user we log here
                ln_type_player_id: 7611, //Blog User Engage
                ln_parent_player_id: section_en_id, //The section this user engaged with
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
                ln_creator_player_id: js_pl_id, //If we have a user we log here
                ln_type_player_id: 7611, //Blog User Engage
                ln_parent_player_id: section_en_id, //The section this user engaged with
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
            ln_creator_player_id: js_pl_id, //If we have a user we log here
            ln_type_player_id: 7611, //Blog User Engage
            ln_parent_player_id: 7614, //Expert Overview
            ln_child_player_id: section_en_id, //The section this user engaged with
            ln_parent_blog_id: in_loaded_id,
            ln_order: section_en_id + '__' + in_loaded_id,
        });
    });

    $('.js-ln-create-expert-sources').click(function () {

        //Only log engagement if opening:
        var section_en_id = parseInt($(this).attr('source-type-en-id')); //Determine the source type

        //Log this section:
        js_ln_create({
            ln_creator_player_id: js_pl_id, //If we have a user we log here
            ln_type_player_id: 7611, //Blog User Engage
            ln_parent_player_id: 7614, //Expert Overview
            ln_child_player_id: section_en_id, //The section this user engaged with
            ln_parent_blog_id: in_loaded_id,
            ln_order: section_en_id + '__' + in_loaded_id,
        });
    });


});





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