<?php

echo '<div style="margin-bottom:13px;">Copy/Paste the following code in routes.php</div>';
echo '<textarea class="mono-space" readonly style="background-color: #FFFFFF; color:#000000 !important; padding:5px; font-size:0.65em; height:377px; width: 100%; border-radius: 0px;">';

echo '<?php'."\n\n";
echo 'defined(\'BASEPATH\') or exit(\'No direct script access allowed\');'."\n"."\n";
echo '$route[\'translate_uri_dashes\'] = FALSE;'."\n";
echo '$route[\'default_controller\'] = "app/index"; //Redirects to default app'."\n";
echo '$route[\'404_override\'] = \'app/load\'; //Page not found'."\n";
echo "\n";


foreach($this->X_model->fetch(array(
    'x__following' => 6287, //Apps
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), array('x__follower'), 0, 0, array('e__title' => 'ASC')) as $app) {

    $special_routing = in_array($app['e__id'], $this->config->item('n___42921'));
    $e_require = in_array($app['e__id'], $this->config->item('n___42921'));
    $e_support = in_array($app['e__id'], $this->config->item('n___42921'));
    $i_require = in_array($app['e__id'], $this->config->item('n___42921'));
    $i_support = in_array($app['e__id'], $this->config->item('n___42921'));

    echo '$route[\''.( $special_routing && strlen($app['x__message']) ? $app['x__message'] : '(?i)'.$app['e__handle'] ).'\'] = "app/load/'.$app['e__id'].'";'."\n";
}


echo '//APPS:'."\n\n";
echo "\n\n";


echo '//Special Routing:'."\n\n";
echo "\n\n";



//Fixed Application Logic:
echo '$route[\'@([a-zA-Z0-9]+)\']           = "app/e_layout/$1"; //Source
$route[\'([a-zA-Z0-9]+)/([a-zA-Z0-9]+)\']   = "app/x_layout/$1/$2"; //Discovery
$route[\'([a-zA-Z0-9]+)\']                  = "app/i_layout/$1"; //Ideation
$route[\'~([a-zA-Z0-9]+)\']                  = "app/i_layout/$1"; //Ideation
';

echo '</textarea>';
