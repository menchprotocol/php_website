<?php

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in routes.php</div>';
echo '<textarea class="mono-space" readonly style="background-color:#FFFFFF; color:#000000 !important; padding:5px; font-size:0.65em; height:377px; width: 100%; border-radius: 21px;">';

echo 'defined(\'BASEPATH\') or exit(\'No direct script access allowed\');'."\n"."\n";
echo '$route[\'translate_uri_dashes\'] = FALSE;'."\n";
echo '$route[\'default_controller\'] = "app/index"; //Redirects to default app'."\n";
echo '$route[\'404_override\'] = \'app/load\'; //Page not found'."\n";
echo "\n";

foreach($this->X_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    'x__up' => 6287, //Apps
), array('x__down'), 0, 0, array('e__title' => 'ASC')) as $cron_job){
    echo '$route[\''.$cron_job['e__handle'].'\'] = "app/load/'.$cron_job['e__id'].'"; //App'."\n";
}

echo '</textarea>';

/*

$route['~(:any)@(:any)'] = "i/i_layout/$1/$2"; //Append Source (To be deprecated soon & merged into mass apply function)
$route['~(:any)'] = "i/i_layout/$1"; //Ideate
$route['(:any)/(:any)/(:any)/(:any)'] = "x/x_layout/$1/$2/$3/$4"; //Discovery Started
$route['(:any)/(:any)/(:any)'] = "x/x_layout/0/$1/$2/$3"; //Discovery Started
$route['(:any)/(:any)'] = "x/x_layout/$1/$2/0/0"; //Discovery Started
$route['(:any)'] = "x/x_layout/0/$1/0/0"; //Discovery Preview

*/