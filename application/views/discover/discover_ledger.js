


$(document).ready(function () {

    check_in_en_status_source_id();

    //Watch for Idea status change:
    $("#ln_type_source_id").change(function () {
        check_in_en_status_source_id();
    });

    //Load first page of links:
    ledger_load(link_filters, link_join_by, 1);

});


function check_in_en_status_source_id(){
    //Checks to see if the Idea/Player status filter should be visible
    //Would only make visible if Link type is Created Idea/Player

    //Hide both in/en status:
    $(".filter-statuses").addClass('hidden');

    //Show only if creating new in/en Link type:
    if($("#ln_type_source_id").val()==4250){
        $(".filter-in-status").removeClass('hidden');
    } else if($("#ln_type_source_id").val()==4251){
        $(".filter-en-status").removeClass('hidden');
    }
}


function ledger_load(link_filters, link_join_by, page_num){

    //Show spinner:
    $('#link_page_'+page_num).html('<div class="montserrat"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_echo_platform_message(12694) +  '</div>').hide().fadeIn();

    //Load report based on input fields:
    $.post("/ledger/ledger_load", {
        link_filters: link_filters,
        link_join_by: link_join_by,
        ln_content_search:ln_content_search,
        ln_content_replace:ln_content_replace,
        page_num: page_num,
    }, function (data) {
        if (!data.status) {
            //Show Error:
            $('#link_page_'+page_num).html('<span style="color:#FF0000;">Alert: '+ data.message +'</span>');
        } else {
            //Load Report:
            $('#link_page_'+page_num).html(data.message);
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

}
