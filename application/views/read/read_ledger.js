


$(document).ready(function () {

    check_in_source__status();

    //Watch for Idea status change:
    $("#read__type").change(function () {
        check_in_source__status();
    });

    //Load first page of links:
    ledger_load(link_filters, link_joined_by, 1);

});


function check_in_source__status(){
    //Checks to see if the Idea/Player status filter should be visible
    //Would only make visible if Link type is Created Idea/Player

    //Hide both in/en status:
    $(".filter-statuses").addClass('hidden');

    //Show only if creating new in/en Link type:
    if($("#read__type").val()==4250){
        $(".filter-in-status").removeClass('hidden');
    } else if($("#read__type").val()==4251){
        $(".filter-en-status").removeClass('hidden');
    }
}


function ledger_load(link_filters, link_joined_by, page_num){

    //Show spinner:
    $('#link_page_'+page_num).html('<div class="montserrat"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_platform_message(12694) +  '</div>').hide().fadeIn();

    //Load report based on input fields:
    $.post("/read/ledger_load", {
        link_filters: link_filters,
        link_joined_by: link_joined_by,
        read__message_search:read__message_search,
        read__message_replace:read__message_replace,
        page_num: page_num,
    }, function (data) {
        if (!data.status) {
            //Show Error:
            $('#link_page_'+page_num).html('<span class="read">'+ data.message +'</span>');
        } else {
            //Load Report:
            $('#link_page_'+page_num).html(data.message);
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

}
