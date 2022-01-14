/*
*
* Functions related to modifying ideas
* and managing IDEA NOTES.
*
* */


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
    e_e_only_search_7551();


    //Load top/bottom idea searches:
    i_load_search(11019);
    i_load_search(13542);

    //Load Sortable:
    x_sort_load(13542);

});
