<?php

//List CronJobs command:
$longest_time = 0;
$longest_id = 0;
$cron_jobs = $this->Mench_ledger->fetch(array(
    'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    'LinkUp' => 7274,
), array('LinkDown'), view__memory(6404,11064), 0, array('LinkNumber' => 'ASC'));
foreach($cron_jobs as $cron_job){
    if(strlen($cron_job['LinkText']) > $longest_time){
        $longest_time = strlen($cron_job['LinkText']);
    }
    if(strlen($cron_job['e__id']) > $longest_id){
        $longest_id = strlen($cron_job['e__id']);
    }
}

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in crontab -e</div>';
echo '<textarea class="mono-space" readonly style="background-color: #FFFFFF; color:#000000 !important; padding:5px; font-size:0.65em; height:377px; width: 100%; border-radius: 0px;">';
echo '# APPS WITH CRON JOBS:'."\n"."\n";
foreach($cron_jobs as $cron_job){
    if(strlen($cron_job['LinkText'])){
        echo str_pad($cron_job['LinkText'], $longest_time, " ", STR_PAD_RIGHT).' '.view__memory(6404,7274).' '.str_pad($cron_job['e__id'], $longest_id, " ", STR_PAD_RIGHT).' #'.$cron_job['e__title']."\n";
    }
}
echo '</textarea>';