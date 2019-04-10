
function add_to_actionplan(in_id){
    //Adds a new or existing intention to the student's Action Plan:
    alert(in_id+' has been added to your action plan.');
}


function actionplan_sort_save() {

    var sort_rank = 0;
    var new_actionplan_order = [];
    $("#actionplan_intents .actionplan_sort").each(function () {
        var link_id = parseInt($(this).attr('link-id'));
        if(link_id > 0){
            sort_rank++;
            new_actionplan_order[sort_rank] = link_id;
            $(".results-ln-" + link_id).html(ordinal_suffix_of(sort_rank));
        }
    });

    //Update Action Plan order:
    $.post("/messenger/actionplan_sort_save", {en_miner_id: en_miner_id, new_actionplan_order: new_actionplan_order}, function (data) {
        //Update UI to confirm with user:
        if (!data.status) {
            //There was some sort of an error returned!
            alert('ERROR: ' + data.message);
        }
    });

}

//Watch for Action Plan removal click:
$('.actionplan_remove').on('click', function(e) {

    //Open Modal, confirm the removal and ask why they are removing?
    alert('ok');
    return false;

});


//Load sorter:
var sort = Sortable.create(document.getElementById('actionplan_intents'), {
    animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
    draggable: ".actionplan_sort", // Specifies which items inside the element should be sortable
    handle: ".actionplan_sort", // Restricts sort start click/touch to the specified element
    onUpdate: function (evt/**Event*/) {
        actionplan_sort_save();
    }
});


function confirm_skip(ln_id) {
    //Make a AJAX Call to see how many steps would be skipped if we were to continue:
    $.post("/messenger/actionplan_skip_step/"+ ln_id+"/0", { ln_id: ln_id }, function (data) {
        var r = confirm("Are you sure you want to skip "+data.step_count+" steps to " + $('.primary-title').text() + "?");
        if (r == true) {
            //Redirect to skip:
            window.location = "/messenger/actionplan_skip_step/" + ln_id+"/1";
        }
    });
}

