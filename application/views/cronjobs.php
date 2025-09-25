<?php

//List CronJobs command:
$longest_time = 0;
$longest_id = 0;
$cron_jobs = $this->Ideachains->read(array(
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
    'chainuserinput' => 7274,
), array('chainuseroutput'), view_memory(6404,11064), 0, array('chainkey' => 'ASC'));
foreach($cron_jobs as $cron_job){
    if(strlen($cron_job['chainvalue']) > $longest_time){
        $longest_time = strlen($cron_job['chainvalue']);
    }
    if(strlen($cron_job['userid']) > $longest_id){
        $longest_id = strlen($cron_job['userid']);
    }
}

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in crontab -e</div>';
echo '<textarea class="mono-space" readonly style="background-color: #FFFFFF; color:#000000 !important; padding:5px; font-size:0.65em; height:377px; width: 100%; border-radius: 0px;">';
echo '# APPS WITH CRON JOBS:'."\n"."\n";
foreach($cron_jobs as $cron_job){
    if(strlen($cron_job['chainvalue'])){
        echo str_pad($cron_job['chainvalue'], $longest_time, " ", STR_PAD_RIGHT) . ' /usr/bin/php /var/www/platform/index.php controller load '.str_pad($cron_job['userid'], $longest_id, " ", STR_PAD_RIGHT).' #'.$cron_job['username']."\n";
    }
}
echo '</textarea>';