
<script src="/js/custom/mench-dashboard.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<?php
//Fetch & Display Intent Note Messages to explain links:
$en_all_7368 = $this->config->item('en_all_7368');
?>
<h1 style="text-align: center; margin:72px 0;"><?= $en_all_7368[7161]['m_name'] ?></h1>

<div class="row">
    <div id="stats_intents_box" class="col-md-6 bottom-spacing">
        <div class="large-stat"><a href="javascript:void(0);" onclick="load_extra_stats('intents')" class="yellow"><?= $en_all_7368[4535]['m_icon'] ?> <span class="extended_stats"><i class="fas fa-spinner fa-spin"></i></span> <span class="substitle"><?= $en_all_7368[4535]['m_name'] ?> <i class="extra_stat_content fal fa-plus-circle"></i><i class="extra_stat_content fal fa-minus-circle hidden"></i></span></a></div>
        <div class="load_stats_box extra_stat_content hidden"></div>
    </div>


    <div id="stats_entities_box" class="col-md-6 bottom-spacing">
        <div class="large-stat"><a href="javascript:void(0);" onclick="load_extra_stats('entities')" class="blue"><?= $en_all_7368[4536]['m_icon'] ?> <span class="extended_stats"><i class="fas fa-spinner fa-spin"></i></span> <span class="substitle"><?= $en_all_7368[4536]['m_name'] ?> <i class="extra_stat_content fal fa-plus-circle"></i><i class="extra_stat_content fal fa-minus-circle hidden"></i></span></a></div>
        <div class="load_stats_box extra_stat_content hidden"></div>
    </div>
</div>


<div class="row" style="margin:0; padding: 0;">
    <div id="stats_links_box" class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3" style="margin-bottom:75px;">
        <div class="large-stat"><a href="javascript:void(0);" onclick="load_extra_stats('links')"><?= $en_all_7368[6205]['m_icon'] ?> <span class="extended_stats"><i class="fas fa-spinner fa-spin"></i></span> <span class="substitle"><?= $en_all_7368[6205]['m_name'] ?> <i class="extra_stat_content fal fa-plus-circle"></i><i class="extra_stat_content fal fa-minus-circle hidden"></i></span></a></div>
        <div class="load_stats_box extra_stat_content hidden"></div>
    </div>
</div>