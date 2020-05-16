<?php

//List CronJobs command:
echo '<div style="margin-bottom: 34px;">Copy/Paste the following code in crontab -e</div>';

echo '<textarea style="background-color:#FFFFFF; padding:20px; font-family: monospace; font-size:0.7em; border-radius: 10px;">';
echo '# PLUGINS WITH CRON JOBS:'."\n"."\n";
foreach($this->LEDGER_model->ln_fetch(array(
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
    'ln_profile_source_id' => 7274,
), array('en_portfolio'), config_var(11064), 0, array('ln_content' => 'ASC')) as $cron_job){
    echo $cron_job['ln_content'].' '.config_var(7274).' '.$cron_job['en_id'].' # '.$cron_job['en_name']."\n";
}
echo '</textarea>';