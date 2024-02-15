<?php

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in routes.php</div>';
echo '<textarea class="mono-space" readonly style="background-color: #FFFFFF; color:#000000 !important; padding:5px; font-size:0.65em; height:377px; width: 100%; border-radius: 0px;">';

echo '<?php'."\n\n";
echo 'defined(\'BASEPATH\') or exit(\'No direct script access allowed\');'."\n"."\n";
echo '$route[\'translate_uri_dashes\'] = FALSE;'."\n";
echo '$route[\'default_controller\'] = "view/index"; //Redirects to default app'."\n";
echo '$route[\'404_override\'] = \'view/app_load\'; //Page not found'."\n";
echo "\n";

//Custom Apps:
foreach($this->X_model->fetch(array(
    'x__following' => 6287, //Apps
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), array('x__follower'), 0) as $app) {
    echo '$route[\''.$app['e__handle'].'\'] = "view/app_load/'.$app['e__id'].'";'."\n";
}

echo "\n\n";


//Fixed Application Logic:
echo '$route[\'@([a-zA-Z0-9]+)\'] = "view/e_layout/$1"; //Source'."\n";
echo '$route[\'~([a-zA-Z0-9]+)\'] = "view/i_layout/$1"; //Ideate'."\n";
echo '$route[\'([a-zA-Z0-9]+)/start\'] = "view/x_layout/0/$1"; //Discovery Sequence'."\n";
echo '$route[\'([a-zA-Z0-9]+)/([a-zA-Z0-9]+)\'] = "view/x_layout/$1/$2"; //Discovery Sequence'."\n";
echo '$route[\'([a-zA-Z0-9]+)\'] = "view/x_layout/0/$1/0"; //Discovery Single'."\n";

echo '</textarea>';
