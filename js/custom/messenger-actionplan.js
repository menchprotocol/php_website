
function add_to_actionplan(in_id){
    //Adds a new or existing intention to the student's Action Plan:
    alert(in_id+' has been added to your action plan.');
}


function actionplan_sort_save() {

    var sort_rank = 0;
    var new_actionplan_order = [];
    $("#actionplan_intents .actionplan_sort").each(function () {
        var link_id = parseInt($(this).attr('sort-link-id'));
        if(link_id > 0){
            sort_rank++;
            new_actionplan_order[sort_rank] = link_id;
            $(".results-ln-" + link_id).html(ordinal_suffix_of(sort_rank));
        }
    });

    //Update Action Plan order:
    if(sort_rank > 0){
        $.post("/messenger/actionplan_sort_save", {en_miner_id: en_miner_id, new_actionplan_order: new_actionplan_order}, function (data) {
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

    //Uncheck all buttons:
    $("input[name='stop_type']").prop("checked", false);

    //Find intent ID:
    var in_id = $(this).attr('in-id');

    //Set intent ID:
    $('#stop_in_id').val(in_id);

    //Update modal title:
    $('.stop-title').text($('.in-title-' + in_id).text());

    //Open Modal, confirm the removal and ask why they are removing?
    $('#markCompleteModal').modal('show');

    return false;

});

function apply_stop(){

    //Check intent ID:
    var in_id = parseInt($('#stop_in_id').val());

    //Check stop method:
    var stop_method_id = parseInt($("input[name='stop_type']:checked").val());
    var stop_feedback = $("#stop_feedback").val();

    if(!in_id || in_id < 1){
        //Should not happen!
        return alert('Unknown intent');
    }

    if(!stop_method_id || stop_method_id < 1){
        return alert('Select a stop reason to continue');
    }

    //All good! Close Modal box:
    $('#markCompleteModal').modal('hide');

    //Save changes:
    $.post("/messenger/actionplan_completion_save", {en_miner_id: en_miner_id, in_id: in_id, stop_method_id:stop_method_id, stop_feedback:stop_feedback}, function (data) {
        //Update UI to confirm with user:
        if (!data.status) {

            //There was some sort of an error returned!
            alert('ERROR: ' + data.message);

        } else {

            //Remove intent from UI:
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


//Load sorter:
var sort = Sortable.create(document.getElementById('actionplan_intents'), {
    animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
    draggable: ".actionplan_sort", // Specifies which items inside the element should be sortable
    handle: ".actionplan_sort", // Restricts sort start click/touch to the specified element
    onUpdate: function (evt/**Event*/) {
        actionplan_sort_save();
    }
});


function confirm_skip(en_id, in_id) {
    //Make a AJAX Call to see how many steps would be skipped if we were to continue:
    $.post("/messenger/actionplan_skip_step/"+ en_id+"/"+in_id+"/0", {}, function (data) {
        var r = confirm("Are you sure you want to skip "+data.step_count+" steps to " + $('.primary-title').text() + "?");
        if (r == true) {
            //Redirect to skip:
            window.location = "/messenger/actionplan_skip_step/"+ en_id+"/"+in_id+"/1";
        }
    });
}

