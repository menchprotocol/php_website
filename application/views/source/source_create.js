


function preview_update_dropdown(element_id, new_en_id){

    /*
    *
    * WARNING:
    *
    * element_id Must be listed as children of:
    *
    * MEMORY CACHE @4527
    * JS MEMORY CACHE @11054
    *
    *
    * */

    var current_selected = parseInt($('.dropi_'+element_id+'_'+in_id+'_'+ln_id+'.active').attr('new-en-id'));
    new_en_id = parseInt(new_en_id);
    if(current_selected == new_en_id){
        //Nothing changed:
        return false;
    }

    //Changing Idea Status?
    if(element_id==4737){

        var is_in_active = (new_en_id in js_en_all_7356);
        var is_in_public = (new_en_id in js_en_all_7355);


        //Deleting?
        if(!is_in_active){
            //Seems to be deleting, confirm:
            var r = confirm("Delete this idea AND unlink all its links to other ideas?");
            if (r == false) {
                return false;
            }
        }


        //Discovery Setting:
        if(is_in_public){

            //Enable Discovery:
            $('.idea-discover').removeClass('hidden');

        } else {

            //Disable Discovery:
            $('.idea-discover').addClass('hidden');

        }

    }



    //Is Status Public?



    //Show Loading...
    var data_object = eval('js_en_all_'+element_id);
    $('.dropd_'+element_id+'_'+in_id+'_'+ln_id+' .btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">'+ ( show_full_name ? 'SAVING...' : '' ) +'</b>');

    $.post("/idea/in_update_dropdown", {

        in_id: in_id,
        ln_id: ln_id,
        in_loaded_id:in_loaded_id,
        element_id: element_id,
        new_en_id: new_en_id

    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.dropd_'+element_id+'_'+in_id+'_'+ln_id+' .btn').html('<span class="icon-block">'+data_object[new_en_id]['m_icon']+'</span>' + ( show_full_name ? data_object[new_en_id]['m_name'] : '' ));
            $('.dropd_'+element_id+'_'+in_id+'_'+ln_id+' .dropi_' + element_id +'_'+in_id+ '_' + ln_id).removeClass('active');
            $('.dropd_'+element_id+'_'+in_id+'_'+ln_id+' .optiond_' + new_en_id+'_'+in_id+ '_' + ln_id).addClass('active');

            $('.dropd_'+element_id+'_'+in_id+'_'+ln_id).attr('selected-val' , new_en_id);

            if( data.deletion_redirect && data.deletion_redirect.length > 0 ){
                //Go to main idea page:
                window.location = data.deletion_redirect;
            } else if( data.delete_element && data.delete_element.length > 0 ){
                //Go to main idea page:
                setTimeout(function () {
                    //Restore background:
                    $( data.delete_element ).fadeOut();

                    setTimeout(function () {
                        //Restore background:
                        $( data.delete_element ).remove();
                    }, 55);

                }, 377);
            }

            if(element_id==4486){
                $('.in__tr_'+ln_id+' .link_marks').addClass('hidden');
                $('.in__tr_'+ln_id+' .settings_' + new_en_id).removeClass('hidden');
            }

        } else {

            //Reset to default:
            $('.dropd_'+element_id+'_'+in_id+'_'+ln_id+' .btn').html('<span class="icon-block">'+data_object[current_selected]['m_icon']+'</span>' + ( show_full_name ? data_object[current_selected]['m_name'] : '' ));

            //Show error:
            alert('Alert: ' + data.message);

        }
    });
}
