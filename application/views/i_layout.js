/*
*
* Functions related to modifying ideas
* and managing IDEA NOTES.
*
* */


var match_search_loaded = 0; //Keeps track of when we load the match search

$(document).ready(function () {

    i_note_activate();

    //Load search for mass update function:
    load_editor();

    $('.editor_preview.editor_preview_4231').click(function (e) {

        if(!click_has_class($(e.target), '.ignore-click')){
            loadtab(14418, 14468);//Load Write Tab
        }

        //Watch for click to reverse to preview:
        $('html').click(function(e2) {
            if(!click_has_class($(e2.target), '.input_note_4231, .editor_preview_4231, .indifferent')){
                revert_poweredit();
            }
        });

    });

    //Alert for unsaved changes:
    window.onbeforeunload = function(event) {
        if(i_note_poweredit_has_changed(4231)){
            return "you have unsaved changes. Are you sure you want to navigate away?";
        }
    };

    //Look for power editor updates:
    $('.x_set_class_text').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            x_set_text(this);
            e.preventDefault();
        }
    }).change(function() {
        x_set_text(this);
    });


    $('.power_editor').on('change keyup paste', function(e) {

        var note_type_id = $(this).attr('note_type_id');

        //Toggle save button based on changed:
        if(i_note_poweredit_has_changed(note_type_id)){
            $('.save_button_'+note_type_id).removeClass('hidden');
        } else {
            $('.save_button_'+note_type_id).addClass('hidden');
        }

        var code = (e.keyCode ? e.keyCode : e.which);
        if (e.ctrlKey && code== 13) {
            i_note_poweredit_save(note_type_id);
        }

    });

    //Put focus on messages if no message:
    if(!$('#i_notes_list_4231 .note_sortable').length){
        $('.input_note_4231').focus();
    }


    //Activate Source-Only Inputs:
    $(".e-only-7551").each(function () {
        e_e_only_search_7551($(this).attr('note_type_id'));
    });


    //Load top/bottom idea searches:
    i_load_search(11019, $('#focus_i__id').val());
    i_load_search(13542, $('#focus_i__id').val());

    //Load Sortable:
    x_sort_load(13542);

});







function i_note_poweredit_save(note_type_id){

    //Only save if something changed:
    if(!i_note_poweredit_has_changed(note_type_id)){
        //Just revert to preview mode:
        loadtab(14418, 14420); //Load Preview tab
        return false;
    }

    var input_textarea = '.input_note_'+note_type_id;
    $('.power-editor-' + note_type_id+', .tab-data-'+ note_type_id).addClass('dynamic_saving');
    $('.save_notes_' + note_type_id).html('<i class="far fa-yin-yang fa-spin"></i>').attr('href', '#');

    $.post("/i/i_note_poweredit_save", {
        i__id: $('#focus_i__id').val(),
        note_type_id: note_type_id,
        field_value: $(input_textarea).val().trim()
    }, function (data) {

        $('.power-editor-' + note_type_id+', .tab-data-'+ note_type_id).removeClass('dynamic_saving');
        $('.save_notes_' + note_type_id).attr('href', 'javascript:i_note_poweredit_save('+note_type_id+');');

        //Update raw text input:
        var new_text = data.input_clean.trim();
        $(input_textarea).val(new_text + ' ');
        $('#current_text_'+note_type_id).text(new_text);
        autosize.update($(input_textarea));
        $(input_textarea).focus();

        if (!data.status) {

            $('.save_notes_' + note_type_id).html(js_e___11035[14422]['m__icon'] + ' ' + js_e___11035[14422]['m__title']);

            //Show Errors:
            $(".note_error_"+note_type_id).html('<span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span> Message not saved because:<br />'+data.message);

        } else {

            //Show update success icon:
            $('.save_notes_' + note_type_id).html(js_e___11035[14424]['m__icon']);

            //Reset errors:
            $(".note_error_"+note_type_id).html('');

            //Update READ:
            $('.editor_preview_'+note_type_id).html(data.message);

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Hide Save BUtton:
            $('.save_button_'+note_type_id).addClass('hidden');

            //Load Images:
            lazy_load();

            if(note_type_id==4231 && new_text.length>0){
                loadtab(14418, 14420); //Load Preview tab
            }

            watch_for_note_e_clicks();

            setTimeout(function () {
                $(input_textarea).focus();
                $('.save_notes_' + note_type_id).html(js_e___11035[14422]['m__icon'] + ' ' + js_e___11035[14422]['m__title']);
            }, 987);

        }
    });
}



function e_add_only_7551(e_existing_id, note_type_id) {


    //if e_existing_id>0 it means we're adding an existing source, in which case e_new_string should be null
    //If e_existing_id=0 it means we are creating a new source and then adding it, in which case e_new_string is required

    var e_new_string = null;
    var input = $('.e-i-'+note_type_id+' .add-input');
    var list_id = 'add-e-'+note_type_id;

    if (e_existing_id == 0) {

        e_new_string = input.val();
        if (e_new_string.length < 1) {
            alert('Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }

    //Add via Ajax:
    $.post("/e/e_add_only_7551", {

        i__id: $('#focus_i__id').val(),
        note_type_id: note_type_id,
        e_existing_id: e_existing_id,
        e_new_string: e_new_string,

    }, function (data) {

        if (data.status) {

            i_note_counter(note_type_id, +1);

            //Raw input to make it ready for next URL:
            input.focus();

            //Add new object to list:
            add_to_list(list_id, '.en-item', data.e_new_echo, false);

        } else {
            //We had an error:
            alert(data.message);
        }

    });

    return true;

}

function e_e_only_search_7551(note_type_id) {

    if(!js_pl_id){
        return false;
    }

    var element_focus = ".e-i-"+note_type_id;

    var base_creator_url = '/e/create/'+$('#focus_i__id').val()+'/?content_title=';

    $(element_focus + ' .add-input').focus(function() {
        $(element_focus + ' .algolia_pad_search' ).removeClass('hidden');
    }).focusout(function() {
        $(element_focus + ' .algolia_pad_search' ).addClass('hidden');
    }).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return e_add_only_7551(0, note_type_id);
        }
    });

    if(parseInt(js_e___6404[12678]['m__message'])){

        $(element_focus + ' .add-input').on('autocomplete:selected', function (event, suggestion, dataset) {

            e_add_only_7551(suggestion.s__id, note_type_id);

        }).autocomplete({hint: false, minLength: 1}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 's__type=12274',
                    hitsPerPage: 21,
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
                    //If clicked, would trigger the autocomplete:selected above which will trigger the e__add() function
                    return view_s_js(suggestion);
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:void(0);" onclick="e_add_only_7551(0, '+note_type_id+');" class="suggestion css__title"><span class="icon-block"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">Create "' + data.query.toUpperCase() + '"</b></a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:void(0);" onclick="e_add_only_7551(0, '+note_type_id+');" class="suggestion css__title"><span class="icon-block"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                },
            }
        }]);
    }
}

