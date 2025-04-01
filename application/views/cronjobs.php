<?php

//List CronJobs command:
$longest_time = 0;
$longest_id = 0;
$cron_jobs = $this->Menchledger->fetch(array(
    'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'linkvoid' => 0, //Not Void
    'linkup' => 7274,
), array('linkdown'), view__memory(6404,11064), 0, array('linknumber' => 'ASC'));
foreach($cron_jobs as $cron_job){
    if(strlen($cron_job['linktext']) > $longest_time){
        $longest_time = strlen($cron_job['linktext']);
    }
    if(strlen($cron_job['playerid']) > $longest_id){
        $longest_id = strlen($cron_job['playerid']);
    }
}

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in crontab -e</div>';
echo '<textarea class="mono-space" readonly style="background-color: #FFFFFF; color:#000000 !important; padding:5px; font-size:0.65em; height:377px; width: 100%; border-radius: 0px;">';
echo '# APPS WITH CRON JOBS:'."\n"."\n";
foreach($cron_jobs as $cron_job){
    if(strlen($cron_job['linktext'])){
        echo str_pad($cron_job['linktext'], $longest_time, " ", STR_PAD_RIGHT) . ' cronjobs.php' .view__memory(6404,7274).' '.str_pad($cron_job['playerid'], $longest_id, " ", STR_PAD_RIGHT).' #'.$cron_job['playertext']."\n";
    }
}
echo '</textarea>';