


function expert_add(en_existing_id) {

    //if en_existing_id>0 it means we're linking to an existing source, in which case en_new_string should be null
    //If en_existing_id=0 it means we are creating a new source and then linking it, in which case en_new_string is required

    if (is_parent) {
        var input = $('#new-parent .add-input');
        var list_id = 'list-parent';
        var counter_class = '.counter-11030';
    } else {
        var input = $('#new-children .add-input');
        var list_id = 'list-children';
        var counter_class = '.counter-11029';
    }

    var en_new_string = null;
    if (en_existing_id == 0) {

        en_new_string = input.val();
        if (en_new_string.length < 1) {
            alert('Alert: Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }


    //Add via Ajax:
    $.post("/source/en_add_or_link", {

        en_id: en_focus_id,
        en_existing_id: en_existing_id,
        en_new_string: en_new_string,
        is_parent: (is_parent ? 1 : 0),

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);

        if (data.status) {

            //Raw input to make it discovery for next URL:
            input.focus();

            //Add new object to list:
            add_to_list(list_id, '.en-item', data.en_new_echo);

            //Adjust counters:
            $(counter_class).text((parseInt($(counter_class + ':first').text()) + 1));
            $('.count-en-status-' + data.en_new_status).text((parseInt($('.count-en-status-' + data.en_new_status).text()) + 1));

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert('Alert: ' + data.message);
        }

    });

}

function expert_search() {

    var element_focus = ".source-map-"+note_type_id;
    var base_creator_url = '/source/create/'+in_loaded_id+'/?content_title=';

    $(element_focus + ' .add-input').focus(function() {
        $(element_focus + ' .algolia_pad_search' ).removeClass('hidden');
    }).focusout(function() {
        $(element_focus + ' .algolia_pad_search' ).addClass('hidden');
    }).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            if(note_type_id==4983){
                window.location = base_creator_url+encodeURIComponent($(this).val());
            } else {
                in_notes_source_only_add(0, note_type_id);
            }
            return true;
        }
    });

    if(parseInt(js_en_all_6404[12678]['m_desc'])){

        //Define filters:
        var extra_filters = '';
        if(!js_session_superpowers_assigned.includes(10967)) {
            if(note_type_id==4983){
                extra_filters = ' AND ( _tags:alg_source_' + js_en_ids_3000.join(' OR _tags:alg_source_') + ') ';
            } else if(note_type_id==10573){
                extra_filters = ' AND ( _tags:alg_source_' + js_en_ids_10573.join(' OR _tags:alg_source_') + ') ';
            }
        }


        $(element_focus + ' .add-input').on('autocomplete:selected', function (event, suggestion, dataset) {

            in_notes_source_only_add(suggestion.alg_obj_id, note_type_id);

        }).autocomplete({hint: false, minLength: 1}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'alg_obj_type_id=4536' + extra_filters,
                    hitsPerPage: 7,
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            templates: {
                suggestion: function (suggestion) {
                    //If clicked, would trigger the autocomplete:selected above which will trigger the en_add_or_link() function
                    return echo_search_result(suggestion);
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="'+base_creator_url+encodeURIComponent(data.query.toUpperCase())+'" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                    }
                },
                empty: function (data) {
                    return '<a href="'+base_creator_url+encodeURIComponent(data.query.toUpperCase())+'" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                },
            }
        }]);
    }
}


$(document).ready(function () {

    //en_ln_type_preview_load();

    $('#ln_content').focus();

    $('#addContent .add-input').focus(function() {
        $('#addContent .algolia_pad_search' ).removeClass('hidden');
    }).focusout(function() {
        $('#addContent .algolia_pad_search' ).addClass('hidden');
    }).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            add_expert(0);
            return true;
        }
    });

    //Auto load Expert Search:
    if(parseInt(js_en_all_6404[12678]['m_desc'])){

        $('#addContent .add-input').on('autocomplete:selected', function (event, suggestion, dataset) {

            add_expert(suggestion.alg_obj_id);

        }).autocomplete({hint: false, minLength: 1}, [{
            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'alg_obj_type_id=4536 AND _tags:alg_source_3084', //Industry Experts Only
                    hitsPerPage: 7,
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            templates: {
                suggestion: function (suggestion) {
                    //If clicked, would trigger the autocomplete:selected above which will trigger the en_add_or_link() function
                    return echo_search_result(suggestion);
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                },
            }
        }]);
    }




});



function create_process(){

    //Validate Inputs:

    console.log({
        in_loaded_id:in_loaded_id,
        content_title: $('#content_title').val(),
        ln_content: $('#ln_content').val(),
        source_en_3000: parseInt($('.dropi_3000_0_0.active').attr('new-en-id')),
    });


    return false;

    $.post("/source/create_process", {}, function (data) {
        if (data.status) {

        } else {

            //Reset to default:

        }
    });

}

function preview_update_dropdown(element_id, new_en_id){

    //Update UI:
    new_en_id = parseInt(new_en_id);
    var current_selected = parseInt($('.dropi_'+element_id+'_0_0.active').attr('new-en-id'));
    if(current_selected == new_en_id){
        //Nothing changed:
        return false;
    }
    var data_object = eval('js_en_all_'+element_id);
    $('.dropd_'+element_id+'_0_0 .btn').html('<span class="icon-block">'+data_object[new_en_id]['m_icon']+'</span>' + data_object[new_en_id]['m_name']);
    $('.dropd_'+element_id+'_0_0 .dropi_' + element_id +'_0_0').removeClass('active');
    $('.dropd_'+element_id+'_0_0 .optiond_' + new_en_id+'_0_0').addClass('active');
    $('.dropd_'+element_id+'_0_0').attr('selected-val' , new_en_id);

}
