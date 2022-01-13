


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



function e_reset_discoveries(e__id){
    //Confirm First:
    var r = confirm("DANGER WARNING!!! You are about to delete your ENTIRE discovery history. This action cannot be undone and you will lose all your discovery coins.");
    if (r == true) {
        $('.e_reset_discoveries').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="css__title">REMOVING ALL...</b>');

        //Redirect:
        window.location = '/x/e_reset_discoveries/'+e__id;
    } else {
        return false;
    }
}




function e_x_form_lock(){
    $('#x__message').prop("disabled", true);

    $('.btn-save').addClass('grey').attr('href', '#').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Uploading');

}

function e_x_form_unlock(result){

    //What was the result?
    if (!result.status) {
        alert(result.message);
    }

    //Unlock either way:
    $('#x__message').prop("disabled", false);

    $('.btn-save').removeClass('grey').attr('href', 'javascript:x_message_save();').html('Save');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();

    //Replace the upload form to reset:
    upload_control.replaceWith( upload_control = upload_control.clone( true ) );
}


function e_sort_save(x__type) {

    var new_x__spectrums = [];
    var sort_rank = 0;

    $("#list-in-"+x__type+" .coinface-12274").each(function () {
        //Fetch variables for this idea:
        var e__id = parseInt($(this).attr('e__id'));
        var x__id = parseInt($(this).attr('x__id'));

        sort_rank++;

        //Store in DB:
        new_x__spectrums[sort_rank] = x__id;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0) {
        //Update backend:
        $.post("/e/e_sort_save", {e__id: current_id(), x__type:x__type, new_x__spectrums: new_x__spectrums}, function (data) {
            //Update UI to confirm with member:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}

function e_sort_reset(){
    var r = confirm("Reset all Portfolio Source orders & sort alphabetically?");
    if (r == true) {
        $('.sort_reset').html('<i class="far fa-yin-yang fa-spin"></i>');

        //Update via call:
        $.post("/e/e_sort_reset", {
            e__id: current_id()
        }, function (data) {

            if (!data.status) {

                //Ooops there was an error!
                alert(data.message);

            } else {

                //Refresh page:
                window.location = '/@' + current_id();

            }
        });
    }
}

function e_sort_load(x__type) {

    if(!js_n___13911.includes(x__type)){
        //Does not support sorting:
        return false;
    }

    var element_key = null;
    var theobject = document.getElementById("list-in-"+x__type);
    if (!theobject) {
        //due to duplicate ideas belonging in this idea:
        return false;
    }


    //Show sort icon:
    $('.sort_e, .sort_reset').removeClass('hidden');

    var sort = Sortable.create(theobject, {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".coinface-12274", // Specifies which items inside the element should be sortable
        handle: ".sort_e", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            e_sort_save(x__type);
        }
    });
}
