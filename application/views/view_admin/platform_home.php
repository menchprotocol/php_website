<script type="text/javascript">

    $(document).ready(function () {

        //Update stats on load:
        update_basic_stats();

        //Continue updating basic stats every 5 seconds:
        setInterval(update_basic_stats, (5000));

    });




    //Update page count stats & refresh them visually once they change:
    var update_basic_stats = function() {
        //your jQuery ajax code

        //Fetch latest stats:
        $.post("/admin/load_basic_stats", {}, function (data) {

            //Updated Intents?
            if(data.intents.extended_stats1 != $('#stats_intents_box .extended_stats1').html()){
                $('#stats_intents_box .extended_stats1').html(data.intents.extended_stats1).fadeOut().fadeIn();
            }
            if(data.intents.extended_stats2 != $('#stats_intents_box .extended_stats2').html()){
                $('#stats_intents_box .extended_stats2').html(data.intents.extended_stats2).fadeOut().fadeIn();
            }

            //Updated Entities?
            if(data.entities.extended_stats1 != $('#stats_entities_box .extended_stats1').html()){
                $('#stats_entities_box .extended_stats1').html(data.entities.extended_stats1).fadeOut().fadeIn();
            }
            if(data.entities.extended_stats2 != $('#stats_entities_box .extended_stats2').html()){
                $('#stats_entities_box .extended_stats2').html(data.entities.extended_stats2).fadeOut().fadeIn();
            }

            //Updated Links?
            if(data.links.extended_stats1 != $('#stats_links_box .extended_stats1').html()){
                $('#stats_links_box .extended_stats1').html(data.links.extended_stats1).fadeOut().fadeIn();
            }
            if(data.links.extended_stats2 != $('#stats_links_box .extended_stats2').html()){
                $('#stats_links_box .extended_stats2').html(data.links.extended_stats2).fadeOut().fadeIn();
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
            $.post("/admin/load_extra_stats/" + object_id, {}, function (data) {

                //Load data:
                $('#stats_' + object_id + '_box .load_stats_box').html(data);

                //Reload Tooltip again:
                $('[data-toggle="tooltip"]').tooltip();

            });

        }
    }

</script>


<?php
//Fetch & Display Intent Note Messages to explain links:
$en_all_2738 = $this->config->item('en_all_2738');
$en_all_4534 = $this->config->item('en_all_4534');

?>
<h1 style="text-align: center; margin-top: 50px;"><?= $en_all_2738[4488]['m_name']; ?></h1>
<p style="text-align: center; margin-top: 20px; font-size:1.5em !important;"><?= $en_all_2738[4488]['m_desc'] ?></p>


<div class="row stat-row" style="margin-bottom:75px;">

    <div id="stats_intents_box" class="col-lg-4 bottom-spacing col-lg-offset-2">
        <a href="javascript:void(0);" onclick="load_extra_stats('intents')" class="large-stat yellow" style="font-weight:bold;"><?= $en_all_4534[4535]['m_icon'] ?> <span class="extended_stats1 extra_stat_content"><i class="fas fa-spinner fa-spin"></i></span><span class="extended_stats2 extra_stat_content hidden"></span> <span class="substitle"><?= $en_all_4534[4535]['m_name'] ?> <i class="extra_stat_content fal fa-plus-circle"></i><i class="extra_stat_content fal fa-minus-circle hidden"></i></span></a>
        <div class="load_stats_box extra_stat_content hidden"></div>
    </div>

    <div id="stats_entities_box" class="col-lg-4 bottom-spacing">
        <a href="javascript:void(0);" onclick="load_extra_stats('entities')" class="large-stat blue" style="font-weight:bold;"><?= $en_all_4534[4536]['m_icon'] ?> <span class="extended_stats1 extra_stat_content"><i class="fas fa-spinner fa-spin"></i></span><span class="extended_stats2 extra_stat_content hidden"></span> <span class="substitle"><?= $en_all_4534[4536]['m_name'] ?> <i class="extra_stat_content fal fa-plus-circle"></i><i class="extra_stat_content fal fa-minus-circle hidden"></i></span></a>
        <div class="load_stats_box extra_stat_content hidden"></div>
    </div>

</div>


<div class="row stat-row" style="margin-bottom:75px;">

    <div id="stats_links_box" class="col-lg-4 col-lg-offset-4">
        <a href="javascript:void(0);" onclick="load_extra_stats('links')" class="large-stat" style="font-weight:bold;"><?= $en_all_4534[6205]['m_icon'] ?> <span class="extended_stats1 extra_stat_content"><i class="fas fa-spinner fa-spin"></i></span><span class="extended_stats2 extra_stat_content hidden"></span> <span class="substitle"><?= $en_all_4534[6205]['m_name'] ?> <i class="extra_stat_content fal fa-plus-circle"></i><i class="extra_stat_content fal fa-minus-circle hidden"></i></span></a>
        <div class="load_stats_box extra_stat_content hidden"></div>
    </div>

</div>