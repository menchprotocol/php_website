function check_in_en_status_entity_id(){
    //Checks to see if the Intent/Entity status filter should be visible
    //Would only make visible if Link type is Created Intent/Entity

    //Hide both in/en status:
    $(".filter-statuses").addClass('hidden');

    //Show only if creating new in/en Link type:
    if($("#ln_type_entity_id").val()==4250){
        $(".filter-in-status").removeClass('hidden');
    } else if($("#ln_type_entity_id").val()==4251){
        $(".filter-en-status").removeClass('hidden');
    }
}




function link_connections(ln_id,load_main) {

    //Show loading instead of button:
    $('.link_connections_link_'+ln_id).html('<span><i class="fas fa-spinner fa-spin"></i> Loading...</span>').hide().fadeIn();

    //Fetch Connections UI
    $.post("/links/link_connections", {
        ln_id: ln_id,
        load_main:load_main,
    }, function (data) {

        //Remove Link:
        $('.link_connections_link_'+ln_id).remove();

        if (!data.status) {

            //Opppsi, show the error:
            alert('Error Loading Intent: ' + data.message);

        } else {

            //Load content:
            $('.link_connections_content_'+ln_id).html(data.ln_connections_ui).hide().fadeIn();

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

        }
    });
}


$(document).ready(function () {

    check_in_en_status_entity_id();

    //Watch for intent status change:
    $("#ln_type_entity_id").change(function () {
        check_in_en_status_entity_id();
    });


    //Show spinner:
    $('#link_list').html('<span><i class="fas fa-spinner fa-spin"></i> Loading...</span>').hide().fadeIn();

    //Load report based on input fields:
    $.post("/links/load_link_list", {
        link_filters: link_filters,
        link_join_by: link_join_by
    }, function (data) {
        if (!data.status) {
            //Show Error:
            $('#link_list').html('<span style="color:#FF0000;">Error: '+ data.message +'</span>');
        } else {
            //Load Report:
            $('#link_list').html(data.message);
            $('[data-toggle="tooltip"]').tooltip();
        }
    });


});