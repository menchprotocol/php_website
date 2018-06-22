

$(document).ready(function() {

    //Add new Task:
    $('#dir_handle').click(function (e) {
        new_intent($('#pid').val(),2);
    });


    //Load Algolia:
    $( "#addintent" ).on('autocomplete:selected', function(event, suggestion, dataset) {

        new_intent($('#pid').val(), 2, suggestion.c_id);

    }).autocomplete({ hint: false, keyboardShortcuts: ['a'] }, [{

        source: function(q, cb) {
            algolia_c_index.search(q, {
                hitsPerPage: 7,
                filters: '(c_level=2)' + ( parseInt($('#u_inbound_u_id').val())==1281 ? '' : ' AND (c_inbound_u_id=' + $('#u_id').val() + ' OR c_is_public=1)' ),
            }, function(error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        displayKey: function(suggestion) { return "" },
        templates: {
            suggestion: function(suggestion) {
                return '<span class="suggest-prefix"><i class="fas fa-check-square"></i></span> '+ suggestion._highlightResult.c_outcome.value;
            },
            header: function(data) {
                if(!data.isEmpty){
                    return '<a href="javascript:new_intent(\''+$('#pid').val()+'\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create Task </span> "'+data.query+'"</a>';
                }
            },
            empty: function(data) {
                return '<a href="javascript:new_intent(\''+$('#pid').val()+'\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create Task</span> "'+data.query+'"</a>';
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return new_intent($('#pid').val(),2);
        }
    });

});

