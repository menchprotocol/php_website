
$(document).ready(function () {

    //Update stats on load:
    update_basic_stats();

    //Continue updating basic stats every 1 second:
    setInterval(update_basic_stats, (1000));

});


//Update page count stats & refresh them visually once they change:
var update_basic_stats = function() {
    //your jQuery ajax code

    //Fetch latest stats:
    $.post("/admin/basic_stats_all", {}, function (data) {

        //Updated Intents?
        if(data.intents.extended_stats != $('#stats_intents_box .extended_stats').html()){
            $('#stats_intents_box .extended_stats').html(data.intents.extended_stats).fadeOut('fast').fadeIn('fast');
        }

        //Updated Entities?
        if(data.entities.extended_stats != $('#stats_entities_box .extended_stats').html()){
            $('#stats_entities_box .extended_stats').html(data.entities.extended_stats).fadeOut('fast').fadeIn('fast');
        }

        //Updated Links?
        if(data.links.extended_stats != $('#stats_links_box .extended_stats').html()){
            $('#stats_links_box .extended_stats').html(data.links.extended_stats).fadeOut('fast').fadeIn('fast');
        }

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();

    });

};



//Function that loads extra stats into view:
function load_extra_stats(object_id){

    //See state:
    var is_openning = $('#stats_' + object_id + '_box .load_stats_box').hasClass('hidden');

    //Toggle view every time:
    $('#stats_' + object_id + '_box .extra_stat_content').toggleClass('hidden');

    //Open or close?
    if(is_openning){

        //Show spinner:
        $('#stats_' + object_id + '_box .load_stats_box').removeClass('hidden').html('<div style="text-align: center;"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');

        //Save the rest of the content:
        $.post("/admin/extra_stats_" + object_id, {}, function (data) {

            //Load data:
            $('#stats_' + object_id + '_box .load_stats_box').html(data);

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

        });

    }
}