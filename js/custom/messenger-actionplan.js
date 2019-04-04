
function add_to_actionplan(in_id){
    //Adds a new or existing intention to the student's Action Plan:

}

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
            filters: 'alg_obj_is_in=1 AND alg_obj_status=2',
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
            if (!data.isEmpty) {
                return '<a href="javascript:add_to_actionplan(0)" class="suggestion"><span><i class="fal fa-plus-circle add-plus"></i> Suggest </span> <b>' + data.query + '</b></a>';
            }
        },
        empty: function (data) {
            return '<a href="javascript:add_to_actionplan(0)" class="suggestion"><span><i class="fal fa-plus-circle add-plus"></i> Suggest </span> <b>' + data.query + '</b></a>';
        },
    }
}]).keypress(function (e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if ((code == 13) || (e.ctrlKey && code == 13)) {
        return add_to_actionplan(0);
    }
});



function confirm_skip(tr_id) {
    //Make a AJAX Call to see how many steps would be skipped if we were to continue:
    $.post("/messenger/actionplan_skip_step/"+ tr_id+"/0", { tr_id: tr_id }, function (data) {
        var r = confirm("Are you sure you want to skip "+data.step_count+" steps to " + $('.primary-title').text() + "?");
        if (r == true) {
            //Redirect to skip:
            window.location = "/messenger/actionplan_skip_step/" + tr_id+"/1";
        }
    });
}

