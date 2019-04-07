
function add_to_actionplan(in_id){
    //Adds a new or existing intention to the student's Action Plan:
    alert(in_id+' has been added to your action plan.');
}



function actionplan_sort_ui() {
    var sort_rank = 0;
    $(".actionplan_sort").each(function () {
        sort_rank++;
        var link_id = parseInt($(this).attr('link-id'));
        $(".results-ln-" + link_id).html(ordinal_suffix_of(sort_rank)+' Priority');
    });
}

function actionplan_sort_save() {

    var sort_rank = 0;
    var new_actionplan_order = [];
    $("#actionplan_intents .actionplan_sort").each(function () {
        var link_id = parseInt($(this).attr('link-id'));
        if(link_id > 0){
            sort_rank++;
            new_actionplan_order[sort_rank] = link_id;
            $(".results-ln-" + link_id).html(ordinal_suffix_of(sort_rank)+' Priority');
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

$(document).ready(function () {
    actionplan_sort_ui();
});


//Load sorter:
var sort = Sortable.create(document.getElementById('actionplan_intents'), {
    animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
    draggable: ".actionplan_sort", // Specifies which items inside the element should be sortable
    handle: ".fa-bars", // Restricts sort start click/touch to the specified element
    onUpdate: function (evt/**Event*/) {
        actionplan_sort_save();
    }
});

//Load search:
load_js_algolia();

//Not yet loaded, continue with loading it:
$('.actionplanadder').focus(function() {
    $('#new-actionplan .algolia_search_pad' ).removeClass('hidden');
}).focusout(function() {
    $('#new-actionplan .algolia_search_pad' ).addClass('hidden');
}).on('autocomplete:selected', function (event, suggestion, dataset) {

    add_to_actionplan(suggestion.alg_obj_id);

}).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

    source: function (q, cb) {
        algolia_index.search(q, {
            filters: 'alg_obj_is_in=1 AND alg_obj_status=2 AND alg_obj_published_children>=7', //Published intents with 7+ published children
            hitsPerPage: 7,
        }, function (error, content) {
            if (error) {
                cb([]);
                return;
            }
            cb(content.hits, content);
        });
    },
    displayKey: function (suggestion) {
        return ""
    },
    templates: {
        suggestion: function (suggestion) {
            return echo_js_suggestion(suggestion, 0);
        },
        header: function (data) {

        },
        empty: function (data) {
            return '<div class="not-found" style="font-size:2em !important; margin: 10px 0;"><i class="fas fa-exclamation-triangle"></i> No intents found</div>';
        },
    }
}]);






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

