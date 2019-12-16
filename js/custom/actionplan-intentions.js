
function actionplan_sort_save() {

    var sort_rank = 0;
    var new_actionplan_order = [];
    $("#actionplan_steps .actionplan_sort", window.parent.document).each(function () {
        var link_id = parseInt($(this).attr('sort-link-id'));
        if(link_id > 0){
            sort_rank++;
            new_actionplan_order[sort_rank] = link_id;
        }
    });

    //Update Action Plan order:
    if(sort_rank > 0){
        $.post("/read/actionplan_sort_save", {en_creator_id: en_creator_id, new_actionplan_order: new_actionplan_order}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert('ERROR: ' + data.message);
            }
        });
    }
}

//Watch for Action Plan removal click:
$('.actionplan_remove').on('click', function(e) {

    var in_id = $(this, window.parent.document).attr('in-id');
    var r = confirm("Remove ["+$('.in-title-'+in_id, window.parent.document).val()+"] from reading list?");
    if (r == true) {
        //Save changes:
        $.post("/read/actionplan_stop_save", { en_creator_id:en_creator_id ,in_id:in_id }, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {

                //There was some sort of an error returned!
                alert('ERROR: ' + data.message);

            } else {

                //REMOVE BOOKMARK from UI:
                $('#ap_in_'+in_id, window.parent.document).fadeOut();

                setTimeout(function () {

                    //Remove from body:
                    $('#ap_in_'+in_id, window.parent.document).remove();

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


//Load sorter:
var sort = Sortable.create(document.getElementById('actionplan_steps'), {
    animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
    draggable: ".actionplan_sort", // Specifies which items inside the element should be sortable
    handle: ".actionplan_sort", // Restricts sort start click/touch to the specified element
    onUpdate: function (evt/**Event*/) {
        actionplan_sort_save();
    }
});
