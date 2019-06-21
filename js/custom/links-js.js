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