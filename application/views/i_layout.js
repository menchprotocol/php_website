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

    e_load_search(4983);

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

        var x__type = $(this).attr('x__type');

        //Toggle save button based on changed:
        if(i_note_poweredit_has_changed(x__type)){
            $('.save_button_'+x__type).removeClass('hidden');
        } else {
            $('.save_button_'+x__type).addClass('hidden');
        }

        var code = (e.keyCode ? e.keyCode : e.which);
        if (e.ctrlKey && code== 13) {
            i_note_poweredit_save(x__type);
        }

    });


    //Activate Source-Only Inputs:
    $(".e-only-7551").each(function () {
        e_e_only_search_7551($(this).attr('x__type'));
    });


    //Load top/bottom idea searches:
    i_load_search(11019);
    i_load_search(13542);

    //Load Sortable:
    x_sort_load(13542);

});







function i_note_poweredit_save(x__type){

    //Only save if something changed:
    if(!i_note_poweredit_has_changed(x__type)){
        //Just revert to preview mode:
        loadtab(14418, 14420); //Load Preview tab
        return false;
    }

    var input_textarea = '.input_note_'+x__type;
    $('.power-editor-' + x__type+', .tab-data-'+ x__type).addClass('dynamic_saving');
    $('.save_notes_' + x__type).html('<i class="far fa-yin-yang fa-spin"></i>').attr('href', '#');

    $.post("/i/i_note_poweredit_save", {
        i__id: current_id(),
        x__type: x__type,
        field_value: $(input_textarea).val().trim()
    }, function (data) {

        $('.power-editor-' + x__type+', .tab-data-'+ x__type).removeClass('dynamic_saving');
        $('.save_notes_' + x__type).attr('href', 'javascript:i_note_poweredit_save('+x__type+');');

        //Update raw text input:
        var new_text = data.input_clean.trim();
        $(input_textarea).val(new_text + ' ');
        $('#current_text_'+x__type).text(new_text);
        autosize.update($(input_textarea));
        $(input_textarea).focus();

        if (!data.status) {

            $('.save_notes_' + x__type).html(js_e___11035[14422]['m__cover'] + ' ' + js_e___11035[14422]['m__title']);

            //Show Errors:
            $(".note_error_"+x__type).html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Message not saved because:<br />'+data.message);

        } else {

            //Show update success icon:
            $('.save_notes_' + x__type).html(js_e___11035[14424]['m__cover']);

            //Reset errors:
            $(".note_error_"+x__type).html('');

            //Update DISCOVERY:
            $('.editor_preview_'+x__type).html(data.message);

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Hide Save BUtton:
            $('.save_button_'+x__type).addClass('hidden');

            //Load Images:
            lazy_load();

            if(x__type==4231 && new_text.length>0){
                loadtab(14418, 14420); //Load Preview tab
            }

            watch_for_coin_cover_clicks();

            setTimeout(function () {
                $(input_textarea).focus();
                $('.save_notes_' + x__type).html(js_e___11035[14422]['m__cover'] + ' ' + js_e___11035[14422]['m__title']);
            }, 987);

        }
    });
}



function e_e_only_search_7551(x__type) {

    if(!js_pl_id){
        return false;
    }

    var element_focus = ".e-i-"+x__type;

    var base_creator_url = '/e/create/'+current_id()+'/?content_title=';

    $(element_focus + ' .add-input').keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return e_add_only_7551(x__type, 0);
        }
    });


    if(parseInt(js_e___6404[12678]['m__message'])){

        $(element_focus + ' .add-input').keyup(function () {

            //Clear if no input:
            if(!$(this).val().length){
                $('.e-i-'+x__type+' .algolia_pad_search').html('');
            }

        }).autocomplete({hint: false, autoselect: false, minLength: 1}, [{

            source: function (q, cb) {

                $('.e-i-'+x__type+' .algolia_pad_search').html('');

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
                    $('.e-i-'+x__type+' .algolia_pad_search').append(view_s_js_coin(7551, suggestion, x__type));
                    return false;
                },
                header: function (data) {
                    return false;
                },
                empty: function (data) {
                    return false;
                },
            }
        }]);
    }
}

