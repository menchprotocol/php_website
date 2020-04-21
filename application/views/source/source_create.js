
function create_process(){

    //Validate Inputs:

    console.log({
        in_loaded_id:in_loaded_id,
        source_name: $('#source_name').val(),
        source_url: $('#source_url').val(),
        source_en_12769: parseInt($('.dropi_12769_0_0.active').attr('new-en-id')),
        source_en_3000: parseInt($('.dropi_3000_0_0.active').attr('new-en-id')),
    });


    return false;

    $.post("/source/create_process", {}, function (data) {
        if (data.status) {

        } else {

            //Reset to default:

        }
    });

}

function preview_update_dropdown(element_id, new_en_id){

    new_en_id = parseInt(new_en_id);
    var current_selected = parseInt($('.dropi_'+element_id+'_0_0.active').attr('new-en-id'));

    //Changing Idea Status?
    if(element_id==12769){
        if(new_en_id==3000){
            //Show Content Details:
            $('.content_type_only').removeClass('hidden');
        } else {
            //Hide Content Details:
            $('.content_type_only').addClass('hidden');
        }
    }

    //Update UI:
    var data_object = eval('js_en_all_'+element_id);
    $('.dropd_'+element_id+'_0_0 .btn').html('<span class="icon-block">'+data_object[new_en_id]['m_icon']+'</span>' + data_object[new_en_id]['m_name']);
    $('.dropd_'+element_id+'_0_0 .dropi_' + element_id +'_0_0').removeClass('active');
    $('.dropd_'+element_id+'_0_0 .optiond_' + new_en_id+'_0_0').addClass('active');
    $('.dropd_'+element_id+'_0_0').attr('selected-val' , new_en_id);

}
