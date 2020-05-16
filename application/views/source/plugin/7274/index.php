<?php

//List CronJobs command:
$longest_time = 0;
$longest_id = 0;
$cron_jobs = $this->LEDGER_model->ln_fetch(array(
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
    'ln_profile_source_id' => 7274,
), array('en_portfolio'), config_var(11064), 0, array('ln_content' => 'ASC'));
foreach($cron_jobs as $cron_job){
    if(strlen($cron_job['ln_content']) > $longest_time){
        $longest_time = strlen($cron_job['ln_content']);
    }
    if(strlen($cron_job['en_id']) > $longest_id){
        $longest_id = strlen($cron_job['en_id']);
    }
}

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in crontab -e</div>';
echo '<textarea style="background-color:#FFFFFF; padding:20px; font-family: monospace; font-size:0.8em; height:377px; width: 100%; border-radius: 10px;">';
echo '# PLUGINS WITH CRON JOBS:'."\n"."\n";
foreach($cron_jobs as $cron_job){
    echo str_pad($cron_job['ln_content'], $longest_time, " ", STR_PAD_RIGHT).' '.config_var(7274).' '.str_pad($cron_job['en_id'], $longest_id, " ", STR_PAD_RIGHT).' #'.$cron_job['en_name']."\n";
}
echo '</textarea>';