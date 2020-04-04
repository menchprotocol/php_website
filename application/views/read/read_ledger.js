


$(document).ready(function () {

    check_in_en_status_source_id();

    //Watch for Blog status change:
    $("#ln_type_source_id").change(function () {
        check_in_en_status_source_id();
    });

    //Load first page of links:
    load_ledger(link_filters, link_join_by, 1);

});


function check_in_en_status_source_id(){
    //Checks to see if the Blog/Player status filter should be visible
    //Would only make visible if Link type is Created Blog/Player

    //Hide both in/en status:
    $(".filter-statuses").addClass('hidden');

    //Show only if creating new in/en Link type:
    if($("#ln_type_source_id").val()==4250){
        $(".filter-in-status").removeClass('hidden');
    } else if($("#ln_type_source_id").val()==4251){
        $(".filter-en-status").removeClass('hidden');
    }
}



function load_ledger(link_filters, link_join_by, page_num){
    //Show spinner:
    $('#link_page_'+page_num).html('<div style="margin:20px 0 100px 0;"><i class="far fa-yin-yang fa-spin"></i> ' + echo_loading_notify() +  '</div>').hide().fadeIn();

    //Load report based on input fields:
    $.post("/read/load_ledger", {
        link_filters: link_filters,
        link_join_by: link_join_by,
        ln_content_search:ln_content_search,
        ln_content_replace:ln_content_replace,
        page_num: page_num,
    }, function (data) {
        if (!data.status) {
            //Show Error:
            $('#link_page_'+page_num).html('<span style="color:#FF0000;">Note: '+ data.message +'</span>');
        } else {
            //Load Report:
            $('#link_page_'+page_num).html(data.message);
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
}


function load_link_connections(ln_id,load_main) {

    //Show loading instead of button:
    $('.link_connections_link_'+ln_id).html('<span><i class="far fa-yin-yang fa-spin"></i> ' + echo_loading_notify() +  '</span>').hide().fadeIn();

    //Fetch Connections UI
    $.post("/read/load_link_connections", {
        ln_id: ln_id,
        load_main:load_main,
    }, function (data) {

        //Remove Link:
        $('.link_connections_link_'+ln_id).remove();

        if (!data.status) {

            //Opppsi, show the error:
            alert('Error Loading Links: ' + data.message);

        } else {

            //Load content:
            $('.link_connections_content_'+ln_id).html(data.ln_connections_ui).hide().fadeIn();

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

        }
    });
}
