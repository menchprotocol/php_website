


$(document).ready(function () {

    //Watch for Discovery removal click:
    $('.discover_remove').on('click', function(e) {

        var i__id = $(this).attr('i__id');
        var r = confirm("Remove "+$('.text__4736_'+i__id).text()+"?");
        if (r == true) {
            //Save changes:
            $.post("/discover/discover_remove", { js_pl_id:js_pl_id ,i__id:i__id }, function (data) {
                //Update UI to confirm with user:
                if (!data.status) {

                    //There was some sort of an error returned!
                    alert(data.message);

                } else {

                    //REMOVE BOOKMARK from UI:
                    $('#ap_i_'+i__id).fadeOut();

                    setTimeout(function () {

                        //Delete from body:
                        $('#ap_i_'+i__id).remove();

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



function discover_sort_load(){
    //Load sorter:
    var sort = Sortable.create(document.getElementById('home_discoveries'), {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".home_sort", // Specifies which items inside the element should be sortable
        handle: ".discover-sorter", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            discover_sort();
        }
    });
}


function discover_sort() {

    var sort_rank = 0;
    var new_x_order = [];
    $("#home_discoveries .home_sort").each(function () {
        var link_id = parseInt($(this).attr('sort-link-id'));
        if(link_id > 0){
            sort_rank++;
            new_x_order[sort_rank] = link_id;
        }
    });

    //Update order:
    if(sort_rank > 0){
        $.post("/discover/discover_sort", {js_pl_id: js_pl_id, new_x_order: new_x_order}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }

}


function discover_clear_all(){

    $('.clear-discovery-list').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">REMOVING ALL...</b>');

    //Redirect:
    window.location = '/discover/x_clear_coins';

}
