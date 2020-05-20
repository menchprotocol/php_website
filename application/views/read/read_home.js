
function read_sort_save() {

    var sort_rank = 0;
    var new_read_order = [];
    $("#home_reads .home_sort").each(function () {
        var link_id = parseInt($(this).attr('sort-link-id'));
        if(link_id > 0){
            sort_rank++;
            new_read_order[sort_rank] = link_id;
        }
    });

    //Update Reads order:
    if(sort_rank > 0){
        $.post("/read/read_sort_save", {js_pl_id: js_pl_id, new_read_order: new_read_order}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}


function read_clear_all(){

    $('.clear-reads-list').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">REMOVING ALL DISCOVR COINS...</b>');

    //Redirect:
    window.location = clear_read_url;

}




$(document).ready(function () {

    //Watch for Reads removal click:
    $('.read_remove_item').on('click', function(e) {

        var idea__id = $(this).attr('idea__id');
        var r = confirm("Remove ["+$('.text__4736_'+idea__id).text()+"] from your Reads?");
        if (r == true) {
            //Save changes:
            $.post("/read/read_remove_item", { js_pl_id:js_pl_id ,idea__id:idea__id }, function (data) {
                //Update UI to confirm with user:
                if (!data.status) {

                    //There was some sort of an error returned!
                    alert(data.message);

                } else {

                    //REMOVE BOOKMARK from UI:
                    $('#ap_in_'+idea__id).fadeOut();

                    setTimeout(function () {

                        //Delete from body:
                        $('#ap_in_'+idea__id).remove();

                        //Re-sort:
                        setTimeout(function () {
                            read_sort_save();
                        }, 89);

                    }, 233);
                }
            });
        }

        return false;

    });

});

function read_sort_load(){
    //Load sorter:
    var sort = Sortable.create(document.getElementById('home_reads'), {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".home_sort", // Specifies which items inside the element should be sortable
        handle: ".fa-bars", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            read_sort_save();
        }
    });
}

