
function actionplan_sort_save() {

    var sort_rank = 0;
    var new_actionplan_order = [];
    $("#actionplan_steps .actionplan_sort").each(function () {
        var link_id = parseInt($(this).attr('sort-link-id'));
        if(link_id > 0){
            sort_rank++;
            new_actionplan_order[sort_rank] = link_id;
        }
    });

    //Update READING LIST order:
    if(sort_rank > 0){
        $.post("/read/actionplan_sort_save", {js_pl_id: js_pl_id, new_actionplan_order: new_actionplan_order}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert('Note: ' + data.message);
            }
        });
    }
}

function clear_all_reads(){

    $('.clear-reading-list').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">REMOVING ALL READS...</b>');

    //Redirect:
    window.location = clear_read_url;

}




$(document).ready(function () {

    //Watch for READING LIST removal click:
    $('.actionplan_remove').on('click', function(e) {

        var in_id = $(this).attr('in-id');
        var r = confirm("Remove ["+$('.text__4736_'+in_id).text()+"] from your reading list?");
        if (r == true) {
            //Save changes:
            $.post("/read/actionplan_stop_save", { js_pl_id:js_pl_id ,in_id:in_id }, function (data) {
                //Update UI to confirm with user:
                if (!data.status) {

                    //There was some sort of an error returned!
                    alert('Note: ' + data.message);

                } else {

                    //REMOVE BOOKMARK from UI:
                    $('#ap_in_'+in_id).fadeOut();

                    setTimeout(function () {

                        //Remove from body:
                        $('#ap_in_'+in_id).remove();

                        //Re-sort:
                        setTimeout(function () {
                            actionplan_sort_save();
                        }, 89);

                    }, 233);
                }
            });
        }

        return false;

    });

});

function load_read_sort(){
    //Load sorter:
    var sort = Sortable.create(document.getElementById('actionplan_steps'), {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".actionplan_sort", // Specifies which items inside the element should be sortable
        handle: ".fa-bars", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            actionplan_sort_save();
        }
    });
}

