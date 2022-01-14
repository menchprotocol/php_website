

//Define file upload variables:
var upload_control = $(".inputfile");
var $input = $('.drag-box').find('input[type="file"]'),
    $label = $('.drag-box').find('label'),
    showFiles = function (files) {
        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
    };

$(document).ready(function () {

    //Source Loader:
    var portfolio_count = parseInt($('.new-list-11029').attr('current-count'));
    if(portfolio_count>0 && portfolio_count<parseInt(js_e___6404[13005]['m__message'])){
        e_sort_load(11029);
    }

    set_autosize($('.texttype__lg.text__6197_'+current_id()));

    $("#input__6197").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
        }
    }).click(function(event) {
        event.preventDefault();
    });


    //Load search for mass update function:
    load_editor();
    x_type_preview_load();

    //SEARCH
    e_load_search(11030);
    e_load_search(11029);


});

