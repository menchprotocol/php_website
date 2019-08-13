
//In milli seconds:
var fadeout_frequency = 1000;
var fadeout_speed = 21;
var refresh_stat_counts = true;
var updating_basic_stats = false;


$(document).ready(function () {

    //Update stats on load:
    update_basic_stats();

    //Continue updating basic stats:
    setInterval(update_basic_stats, fadeout_frequency);

});


//Update page count stats & refresh them visually once they change:
var update_basic_stats = function() {
    //your jQuery ajax code

    if(!refresh_stat_counts || updating_basic_stats){
        return false;
    }

    //Now we're updating:
    updating_basic_stats = true;

    //Fetch latest stats:
    $.post("/miner_app/basic_stats_all", {}, function (data) {

        //Updated Intents?
        if(data.intents.extended_stats != $('#stats_intents_box .extended_stats').html()){
            $('#stats_intents_box .extended_stats').html(data.intents.extended_stats).fadeOut(fadeout_speed).fadeIn(fadeout_speed);
        }

        //Updated Entities?
        if(data.entities.extended_stats != $('#stats_entities_box .extended_stats').html()){
            $('#stats_entities_box .extended_stats').html(data.entities.extended_stats).fadeOut(fadeout_speed).fadeIn(fadeout_speed);
        }

        //Updated Links?
        if(data.links.extended_stats != $('#stats_links_box .extended_stats').html()){
            $('#stats_links_box .extended_stats').html(data.links.extended_stats).fadeOut(fadeout_speed).fadeIn(fadeout_speed);
        }

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();

        //We're done updating:
        updating_basic_stats = false;

    });

};



//Function that loads extra stats into view:
function load_extra_stats(object_id){

    //See state:
    var is_openning = $('#stats_' + object_id + '_box .load_stats_box').hasClass('hidden');

    //Disable for now:
    refresh_stat_counts = false;

    //Toggle view every time:
    $('#stats_' + object_id + '_box .extra_stat_content').toggleClass('hidden');

    //Open or close?
    if(is_openning){

        //Show spinner:
        $('#stats_' + object_id + '_box .load_stats_box').removeClass('hidden').html('<div style="text-align: center;"><i class="fas fa-yin-yang fa-spin"></i> Crunching the latest data, just for you. Hang tight...</div>');

        //Save the rest of the content:
        $.post("/miner_app/extra_stats_" + object_id, {}, function (data) {

            //Load data:
            $('#stats_' + object_id + '_box .load_stats_box').html(data);

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

            //Re-Enable again:
            refresh_stat_counts = true;

        });

    }
}