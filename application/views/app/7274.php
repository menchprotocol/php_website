<?php

//List CronJobs command:
$longest_time = 0;
$longest_id = 0;
$cron_jobs = $this->X_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    'x__up' => 7274,
), array('x__down'), view_memory(6404,11064), 0, array('x__message' => 'ASC'));
foreach($cron_jobs as $cron_job){
    if(strlen($cron_job['x__message']) > $longest_time){
        $longest_time = strlen($cron_job['x__message']);
    }
    if(strlen($cron_job['e__id']) > $longest_id){
        $longest_id = strlen($cron_job['e__id']);
    }
}

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in crontab -e</div>';
echo '<textarea class="mono-space" readonly style="background-color:#FFFFFF; color:#222222 !important; padding:5px; font-size:0.65em; height:377px; width: 100%; border-radius: 8px;">';
echo '# APPS WITH CRON JOBS:'."\n"."\n";
foreach($cron_jobs as $cron_job){
    echo str_pad($cron_job['x__message'], $longest_time, " ", STR_PAD_RIGHT).' '.view_memory(6404,7274).' '.str_pad($cron_job['e__id'], $longest_id, " ", STR_PAD_RIGHT).' #'.$cron_job['e__title']."\n";
}
echo '</textarea>';