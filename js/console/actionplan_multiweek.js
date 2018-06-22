
$(document).ready(function() {

    //Load Algolia:
    $( "#addintent" ).on('autocomplete:selected', function(event, suggestion, dataset) {

        link_b(suggestion.b_id);

    }).autocomplete({ hint: false, keyboardShortcuts: ['a'] }, [{

        source: function(q, cb) {
            algolia_b_index.search(q, {
                hitsPerPage: 7,
                filters: '(c_level=0) AND (b_old_format=0) AND (b_status>=2)' + ( parseInt($('#u_inbound_u_id').val())==1281 ? '' : ' AND (b_status=3 OR b_inbound_u_id=' + $('#u_id').val() + ')' ),
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
                return '<span class="suggest-prefix"><i class="fas fa-cube"></i></span> '+ suggestion._highlightResult.c_b_outcome.value;
            },
        }
    }]);


    //Load Algolia:
    $( ".tasknewintent" ).on('autocomplete:selected', function(event, suggestion, dataset) {

        new_intent($(this).attr('intent-id'), 2, suggestion.c_id);

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
                    return '<a href="javascript:new_intent(\''+$(this).attr('intent-id')+'\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create Task </span> "'+data.query+'"</a>';
                }
            },
            empty: function(data) {
                return '<a href="javascript:new_intent(\''+$(this).attr('intent-id')+'\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create Task</span> "'+data.query+'"</a>';
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return new_intent($(this).attr('intent-id'),2);
        }
    });

});


function delete_b(b_id,cr_id){

    var r = confirm("Confirm removing ["+$.trim($('#title_'+cr_id).text())+"] form the Action Plan:");
    if (r == true) {

        //Show delete loader:
        $('#cr_'+cr_id).addClass('hidden').after('<div id="temp_b_'+cr_id+'" class="list-group-item load-grey"><img src="/img/round_load.gif" class="loader" /> Deleting... </div>');

        //Update backend:
        $.post("/api_v1/delete_b_link", {
            current_b_id:$('#b_id').val(),
            delete_b_id:b_id,
            delete_cr_id:cr_id
        }, function(data) {

            //Update UI to confirm with user:
            $( "#temp_b_"+cr_id ).remove();



            if(data.status){

                //Show fadeout effect:
                $('#cr_'+cr_id).removeClass('hidden').html(data.message);

                //Close edit box:
                $('#modifybox').addClass('hidden');

                setTimeout(function(){

                    $('#cr_'+cr_id).remove();

                    //Adjust sorting:
                    c_sort($('#pid').val(), 2);

                    //Need to adjust hours? Likely...
                    if(data.deleted_hours>0){
                        //Expected... Subtract hours from current total
                        var current_b_hours = parseFloat($('.hours_level_1').attr('current-hours')) - data.deleted_hours;
                        $('.hours_level_1').attr('current-hours',current_b_hours).text(echo_hours(current_b_hours));
                    }

                }, 1597);

            } else {

                $('#cr_'+cr_id).show();

                alert('ERROR: '+data.message);
            }
        });
    }

    return false;
}






function link_b(new_b_id){

    //Set initial variables:
    var input_field = $('#addintent');
    var sort_list_id = "list-outbound";
    var sort_handler = ".is_sortable";

    //Set processing status:
    add_to_list(sort_list_id,sort_handler,'<div id="temp_b_'+new_b_id+'" class="list-group-item load-grey"><img src="/img/round_load.gif" class="loader" /> Adding... </div>');

    //Empty Input:
    input_field.focus().val('');

    //Update backend:
    $.post("/api_v1/link_b", {
        current_b_id:$('#b_id').val(),
        new_b_id:new_b_id
    }, function(data) {

        //Update UI to confirm with user:
        $( "#temp_b_"+new_b_id ).remove();

        if(data.status){

            //All good, add this new Bootcamp to UI:
            add_to_list(sort_list_id,sort_handler,data.html);

            //Need to adjust hours? Likely...
            if(data.new_hours>0){
                //Expected... Addup these hours to the total
                var current_b_hours = parseFloat($('.hours_level_1').attr('current-hours'));
                current_b_hours += data.new_hours;
                //Update UI:
                $('.hours_level_1').attr('current-hours',current_b_hours).text(echo_hours(current_b_hours));
            }

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            alert('ERROR: '+data.message);
        }
    });

    //Prevent form submission:
    event.preventDefault();
    return false;
}
