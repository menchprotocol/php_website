<?php

//List CronJobs command:
$longest_time = 0;
$longest_id = 0;
$cron_jobs = $this->READ_model->fetch(array(
    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
    'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
    'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
    'read__up' => 7274,
), array('read__down'), config_var(11064), 0, array('read__message' => 'ASC'));
foreach($cron_jobs as $cron_job){
    if(strlen($cron_job['read__message']) > $longest_time){
        $longest_time = strlen($cron_job['read__message']);
    }
    if(strlen($cron_job['source__id']) > $longest_id){
        $longest_id = strlen($cron_job['source__id']);
    }
}

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in crontab -e</div>';
echo '<textarea style="background-color:#FFFFFF; padding:20px; font-family: monospace; font-size:0.8em; height:377px; width: 100%; border-radius: 10px;">';
echo '# PLUGINS WITH CRON JOBS:'."\n"."\n";
foreach($cron_jobs as $cron_job){
    echo str_pad($cron_job['read__message'], $longest_time, " ", STR_PAD_RIGHT).' '.config_var(7274).' '.str_pad($cron_job['source__id'], $longest_id, " ", STR_PAD_RIGHT).' #'.$cron_job['source__title']."\n";
}
echo '</textarea>';