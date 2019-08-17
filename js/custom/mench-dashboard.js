
//In milli seconds:
var fadeout_frequency = 1000;
var fadeout_speed = 21;
var refresh_stat_counts = true;
var updating_basic_stats = false;
var js_days_ago;
var js_user_group;

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



function load_leaderboard(){

    //Show loader:
    $('#body_inject').html('<tr><td colspan="10"><div style="text-align: center;"><i class="fas fa-yin-yang fa-spin"></i> ' + echo_ying_yang() + '</div></td></tr>');

    //Fetch latest stats:
    $.post("/miner_app/load_leaderboard/"+js_user_group+"/"+js_days_ago, {}, function (data) {

        $('#body_inject').html(data);

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function leaderboard_filter_user_type(en_id){
    $('.user-type-filter').removeClass('btn-primary');
    $('.setting-en-'+en_id).addClass('btn-primary');
    js_user_group = en_id;
    load_leaderboard();
}

function leaderboard_filter_time_frame(days_ago,en_id){
    $('.time-frame-filter').removeClass('btn-primary');
    $('.setting-en-'+en_id).addClass('btn-primary');
    js_days_ago = days_ago;
    load_leaderboard();
}


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
        $('#stats_' + object_id + '_box .load_stats_box').removeClass('hidden').html('<div style="text-align: center;"><i class="fas fa-yin-yang fa-spin"></i> ' + echo_ying_yang() + '</div>');

        //Save the rest of the content:
        $.post("/miner_app/extra_stats_" + object_id, {}, function (data) {

            if(object_id=='links'){
                //Reset:
                js_days_ago = 7; //Last 7 Days
                js_user_group = 1308; //Miners

                //Load leaderboard:
                load_leaderboard();
            }

            //Load data:
            $('#stats_' + object_id + '_box .load_stats_box').html(data);

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

            //Re-Enable again:
            refresh_stat_counts = true;

        });

    }
}