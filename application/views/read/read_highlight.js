


$(document).ready(function () {

    //Watch for READ LIST removal click:
    $('.highlight_remove_item').on('click', function(e) {

        var in_id = $(this).attr('in-id');
        var r = confirm("Remove ["+$('.text__4736_'+in_id).text()+"] from your highlights?");
        if (r == true) {
            //Save changes:
            $.post("/read/highlight_remove_item", { js_pl_id:js_pl_id ,in_id:in_id }, function (data) {
                //Update UI to confirm with user:
                if (!data.status) {

                    //There was some sort of an error returned!
                    alert(data.message);

                } else {

                    //REMOVE BOOKMARK from UI:
                    $('#ap_in_'+in_id).fadeOut();

                    setTimeout(function () {

                        //Delete from body:
                        $('#ap_in_'+in_id).remove();

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