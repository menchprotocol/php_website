




$(document).ready(function () {

    //Watch for Discovery removal click:
    $('.x_remove').on('click', function(e) {

        var i__id = $(this).attr('i__id');
        var r = confirm("Remove "+$('.text__4736_'+i__id).text()+"?");
        if (r == true) {
            //Save changes:
            $.post("/discover/x_remove", { js_pl_id:js_pl_id ,i__id:i__id }, function (data) {
                //Update UI to confirm with user:
                if (!data.status) {

                    //There was some sort of an error returned!
                    alert(data.message);

                } else {

                    //REMOVE BOOKMARK from UI:
                    $('#ap_idea_'+i__id).fadeOut();

                    setTimeout(function () {

                        //Delete from body:
                        $('#ap_idea_'+i__id).remove();

                        //Re-sort:
                        setTimeout(function () {
                            x_sort();
                        }, 89);

                    }, 233);
                }
            });
        }

        return false;

    });

});




function x_sort() {

    var sort_rank = 0;
    var new_read_order = [];
    $("#home_reads .home_sort").each(function () {
        var link_id = parseInt($(this).attr('sort-link-id'));
        if(link_id > 0){
            sort_rank++;
            new_read_order[sort_rank] = link_id;
        }
    });

    //Update order:
    if(sort_rank > 0){
        $.post("/discover/x_sort", {js_pl_id: js_pl_id, new_read_order: new_read_order}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }

}


function read_clear_all(){

    $('.clear-reads-list').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">REMOVING ALL...</b>');

    //Redirect:
    window.location = '/discover/x_clear_coins';

}


function read_sort_load(){
    //Load sorter:
    var sort = Sortable.create(document.getElementById('home_reads'), {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".home_sort", // Specifies which items inside the element should be sortable
        handle: ".fa-bars", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            x_sort();
        }
    });
}
