//We'll also have all the JS here to have them close to each other...

$(document).ready(function () {

    if (is_compact) {

        //Adjust columns:
        $('.cols').removeClass('col-xs-6').addClass('col-sm-6');
        $('.fixed-box').addClass('release-fixture');
        $('.dash').css('margin-bottom', '0px'); //For iframe to show better

    } else {

        //Adjust height of the messaging windows:
        $('.grey-box-w').css('height', (parseInt($(window).height()) - 190) + 'px');
        $('.grey-box').css('max-height', (parseInt($(window).height()) - 190) + 'px');

        $('.ajax-frame').css('height', (parseInt($(window).height()) - 225) + 'px');
        $('.ajax-frame').css('max-height', (parseInt($(window).height()) - 225) + 'px');

        //Make editing frames Sticky for scrolling longer lists
        $(".main-panel").scroll(function () {
            var top_position = $(this).scrollTop();
            clearTimeout($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function () {
                $(".fixed-box").css('top', (top_position - 0)); //PX also set in style.css for initial load
            }, 34));
        });

    }

    if (window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if (hash_parts.length >= 2) {
            //Fetch level if available:
            if (hash_parts[0] == 'wactionplan') {
                load_w_actionplan(hash_parts[1], hash_parts[2]);
            } else if (hash_parts[0] == 'wtrs') {
                fn___load_en_ledger(hash_parts[1], hash_parts[2]);
            }
        }
    }


});

function frame_loader(tr_id, en_id, hide_intent=false) {

    //Start loading:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $('#load_w_frame, .frame-loader').removeClass('hidden');

    //Construct title:
    var w_entity = null;
    if (en_id > 0 && $('.en_name_' + en_id + ':first').length) {
        w_entity = $('.en_name_' + en_id + ':first').text();
    }

    var w_intent = null;
    if (tr_id > 0 && $('.w_intent_' + tr_id).length) {
        w_intent = (w_entity ? ' / ' : '');
        w_intent = w_intent + $('.w_intent_' + tr_id).text();
    }

    return (w_entity ? w_entity : '') + (w_intent && !hide_intent ? w_intent : '');

}




function load_w_actionplan(tr_id, en_id) {

    tr_id = parseInt(tr_id);
    en_id = parseInt(en_id);
    var frame_title = frame_loader(tr_id, en_id);
    $('#w_title').html('<i class="fas fa-flag"></i> ' + frame_title);

    //Add via Ajax:
    $.post("/ledger/load_w_actionplan", {tr_id: tr_id}, function (data) {
        if (data.status) {

            //Load content:
            $('.frame-loader').addClass('hidden');
            $('.ajax-frame').attr('src', data.url).removeClass('hidden').css('margin-top', '-25px');

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert('Error Loading Action Plan Data: ' + data.message);
        }
    });
}