
<script src="/js/custom/platform-home.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<?php
//Fetch & Display Intent Note Messages to explain links:
$en_all_2738 = $this->config->item('en_all_2738');
$en_all_4534 = $this->config->item('en_all_4534');
?>
<h1 style="text-align: center; margin-top: 50px;"><?= $en_all_2738[4488]['m_name']; ?></h1>
<p style="text-align: center; margin-top: 20px; font-size:1.5em !important;"><?= $en_all_2738[4488]['m_desc'] ?></p>


<div class="row stat-row" style="margin-bottom:75px;">

    <div id="stats_intents_box" class="col-lg-4 bottom-spacing col-lg-offset-2">
        <a href="javascript:void(0);" onclick="load_extra_stats('intents')" class="large-stat yellow" style="font-weight:bold;"><?= $en_all_4534[4535]['m_icon'] ?> <span class="extended_stats"><i class="fas fa-spinner fa-spin"></i></span> <span class="substitle"><?= $en_all_4534[4535]['m_name'] ?> <i class="extra_stat_content fal fa-plus-circle"></i><i class="extra_stat_content fal fa-minus-circle hidden"></i></span></a>
        <div class="load_stats_box extra_stat_content hidden"></div>
    </div>

    <div id="stats_entities_box" class="col-lg-4 bottom-spacing">
        <a href="javascript:void(0);" onclick="load_extra_stats('entities')" class="large-stat blue" style="font-weight:bold;"><?= $en_all_4534[4536]['m_icon'] ?> <span class="extended_stats"><i class="fas fa-spinner fa-spin"></i></span> <span class="substitle"><?= $en_all_4534[4536]['m_name'] ?> <i class="extra_stat_content fal fa-plus-circle"></i><i class="extra_stat_content fal fa-minus-circle hidden"></i></span></a>
        <div class="load_stats_box extra_stat_content hidden"></div>
    </div>

</div>


<div class="row stat-row" style="margin-bottom:75px;">

    <div id="stats_links_box" class="col-lg-4 col-lg-offset-4">
        <a href="javascript:void(0);" onclick="load_extra_stats('links')" class="large-stat" style="font-weight:bold;"><?= $en_all_4534[6205]['m_icon'] ?> <span class="extended_stats"><i class="fas fa-spinner fa-spin"></i></span> <span class="substitle"><?= $en_all_4534[6205]['m_name'] ?> <i class="extra_stat_content fal fa-plus-circle"></i><i class="extra_stat_content fal fa-minus-circle hidden"></i></span></a>
        <div class="load_stats_box extra_stat_content hidden"></div>
    </div>

</div>