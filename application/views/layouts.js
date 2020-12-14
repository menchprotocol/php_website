

function i_set_dropdown(element_id, new_e__id, i__id, x__id, show_full_name){

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

    alert('it is: '+focus_i__id);

    var current_selected = parseInt($('.dropi_'+element_id+'_'+i__id+'_'+x__id+'.active').attr('new-en-id'));
    new_e__id = parseInt(new_e__id);
    if(current_selected == new_e__id){
        //Nothing changed:
        return false;
    }

    //Changing Idea Status?
    if(element_id==4737){

        var is_i_active = (new_e__id in js_e___7356);
        var is_i_public = (new_e__id in js_e___7355);


        //Deleting?
        if(!is_i_active){
            //Seems to be deleting, confirm:
            var r = confirm("Are you sure you want to delete this idea and unlink it from all other ideas?");
            if (r == false) {
                return false;
            }
        }


        //Discoveries Setting:
        if(is_i_public){

            //Enable Discoveries:
            $('.i-x').removeClass('hidden');

        } else {

            //Disable Discoveries:
            $('.i-x').addClass('hidden');

        }

    }



    //Is Status Public?



    //Show Loading...
    var data_object = eval('js_e___'+element_id);
    $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">'+ ( show_full_name ? 'SAVING...' : '' ) +'</b>');

    $.post("/i/i_set_dropdown", {

        i__id: i__id,
        x__id: x__id,
        focus_i__id:focus_i__id,
        element_id: element_id,
        new_e__id: new_e__id

    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .btn').html('<span class="icon-block">'+data_object[new_e__id]['m__icon']+'</span>' + ( show_full_name ? data_object[new_e__id]['m__title'] : '' ));

            $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .dropi_' + element_id +'_'+i__id+ '_' + x__id).removeClass('active');
            $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .optiond_' + new_e__id+'_'+i__id+ '_' + x__id).addClass('active');

            $('.dropd_'+element_id+'_'+i__id+'_'+x__id).attr('selected-val' , new_e__id);

            //Update micro icons, if any: (Idea status has it)
            $('.this_i__icon_'+i__id+'>span').html(data.new_i__icon);

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
                $('.cover_x_'+x__id+' .x_marks').addClass('hidden');
                $('.cover_x_'+x__id+' .account_' + new_e__id).removeClass('hidden');
            }

        } else {

            //Reset to default:
            $('.dropd_'+element_id+'_'+i__id+'_'+x__id+' .btn').html('<span class="icon-block">'+data_object[current_selected]['m__icon']+'</span>' + ( show_full_name ? data_object[current_selected]['m__title'] : '' ));

            //Show error:
            alert(data.message);

        }
    });
}
