

function add_to_actionplan(){
    $('#added_to_actionplan').html('<span><i class="fas fa-spinner fa-spin"></i></span> Adding...');
    $.post("/user_app/actionplan_intention_add", {in_id: in_focus_id}, function (data) {
        //Show them success:
        $('#added_to_actionplan').html(data.message);

        //Redirect them:
        window.location = data.add_redirect;
    });
}



$(document).ready(function () {

    //Lookout for intent link type changes:
    $('.tag-manager-overview-link').click(function () {
        //Only log engagement if opening:
        if($(this).hasClass('collapsed')){

            var section_en_id = parseInt($(this).attr('section-en-id'));

            //Log this section:
            js_ln_create({
                ln_miner_entity_id: session_en_id, //If we have a user we log here
                ln_type_entity_id: 7611, //Intent User Engage
                ln_parent_entity_id: section_en_id, //The section this user engaged with
                ln_parent_intent_id: in_focus_id,
                ln_child_intent_id: 0, //Since they just opened the heading, not a sub-section of Steps Overview
                ln_order: '7611_' + section_en_id + '_' + in_focus_id, //The section for this intent
            });
        }
    });


    $('.tag-manager-steps-review').click(function () {
        //Only log engagement if opening:
        if($(this).attr('aria-expanded')=='false'){

            var section_en_id = 7613; //Steps Overview
            var child_in_id = parseInt($(this).attr('intent-id'));

            //Log this section:
            js_ln_create({
                ln_miner_entity_id: session_en_id, //If we have a user we log here
                ln_type_entity_id: 7611, //Intent User Engage
                ln_parent_entity_id: section_en_id, //The section this user engaged with
                ln_parent_intent_id: in_focus_id,
                ln_child_intent_id: child_in_id,
                ln_order: section_en_id + '_' + child_in_id + '__' + in_focus_id,
            });
        }
    });

    $('.tag-manager-expert-full-list').click(function () {
        //Only log engagement if opening:
        var section_en_id = 7616; //Intent Engage Experts Full List

        //Log this section:
        js_ln_create({
            ln_miner_entity_id: session_en_id, //If we have a user we log here
            ln_type_entity_id: 7611, //Intent User Engage
            ln_parent_entity_id: 7614, //Expert Overview
            ln_child_entity_id: section_en_id, //The section this user engaged with
            ln_parent_intent_id: in_focus_id,
            ln_order: section_en_id + '__' + in_focus_id,
        });
    });

    $('.tag-manager-expert-sources').click(function () {

        //Only log engagement if opening:
        var section_en_id = parseInt($(this).attr('source-type-en-id')); //Determine the source type

        //Log this section:
        js_ln_create({
            ln_miner_entity_id: session_en_id, //If we have a user we log here
            ln_type_entity_id: 7611, //Intent User Engage
            ln_parent_entity_id: 7614, //Expert Overview
            ln_child_entity_id: section_en_id, //The section this user engaged with
            ln_parent_intent_id: in_focus_id,
            ln_order: section_en_id + '__' + in_focus_id,
        });
    });


});
