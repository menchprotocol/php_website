<?php

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in routes.php</div>';
echo '<textarea class="mono-space" readonly style="background-color:#FFFFFF; color:#000000 !important; padding:5px; font-size:0.65em; height:377px; width: 100%; border-radius: 21px;">';

echo '<?php';
echo 'defined(\'BASEPATH\') or exit(\'No direct script access allowed\');'."\n"."\n";
echo '$route[\'translate_uri_dashes\'] = FALSE;'."\n";
echo '$route[\'default_controller\'] = "app/index"; //Redirects to default app'."\n";
echo '$route[\'404_override\'] = \'app/load\'; //Page not found'."\n";
echo "\n";

//Custom Apps:
foreach($this->X_model->fetch(array(
    'x__up' => 6287, //Apps
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), array('x__down'), 0) as $app) {
    echo '$route[\''.$app['e__handle'].'\'] = "app/load/'.$app['e__id'].'"; //'.$app['e__title']."\n";
}

echo "\n";


//Fixed Application Logic:
echo '$route[\'@(:any)\']                       = "e/e_layout/$1"; //Source';
echo '$route[\'~(:any)@(:any)\']                = "i/i_layout/$1/$2"; //Append Source (To be deprecated soon & merged into mass apply function)';
echo '$route[\'~(:any)\']                       = "i/i_layout/$1"; //Ideate';
echo '$route[\'(:any)/(:any)/@(:any)\']         = "x/x_layout/$1/$2/$3"; //Discovery Started';
echo '$route[\'(:any)/(:any)\']                 = "x/x_layout/$1/$2/0"; //Discovery Started';
echo '$route[\'(:any)\']                        = "x/x_layout/0/$1/0"; //Discovery Preview';


