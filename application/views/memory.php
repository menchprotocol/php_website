<?php


$start_time = date("Y-m-d H:i:s");
$memory_text = '';
$memory_text .= "<?php\n\n";
$memory_text .= '//UPDATED: '.$start_time."\n\n";
$memory_text .= 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'."\n\n";
$routes_text = $memory_text;


if($memory_detected){

    //EASY:
    $playerids___33337 = $this->config->item('playerids___33337');
    $players___42921 = $this->config->item('players___42921');

} else {

    //Define Manually (Might need to be updated)
    $playerids___33337 = array(42897, 42849, 42791, 42659, 4251, 42581, 42580, 42579, 42570, 42567, 42554, 42518, 42516, 42440, 42427, 42335, 41011, 32489, 32486, 4230);

}

$pinned_down = array();
$pinned_up = array();
$total_nodes = 0;
$biggest_player_count = 0;
$biggest_player_handle = '';


//CONFIG VARS
foreach($this->Menchledger->fetch(array(
    'linkup' => 4527,
    'linkvoid' => 0, //Not Void
    'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
), array('linkdown'), 0, 0, array('playerid' => 'ASC')) as $en){

    //Now fetch all its followers:
    $down__e = $this->Menchledger->fetch(array(
        'linkup' => $en['linkdown'],
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
        ), array('linkdown'), 0, 0, sort__player());

    if(!count($down__e)){
        continue;
    }


    $total_nodes += (1 + count($down__e));
    if(count($down__e)>$biggest_player_count){
        $biggest_player_count = count($down__e);
        $biggest_player_handle = '@'.$en['playerhandle'];
    }

    //Generate raw IDs:
    $down_ids = array();
    $down_titles = array();
    foreach($down__e as $follower){
        if($follower['playerid']>0){
            array_push($down_ids , $follower['playerid']);
            array_push($down_titles , $follower['playertext']);
        }
    }


    $prefix_common_words = prefix_common_words($down_titles); //Clean Titles
    $memory_text .= "\n".'//'.$en['playertext'].':'."\n";
    $memory_text .= '$config[\'playerids___'.$en['linkdown'].'\'] = array('.join(',',$down_ids).');'."\n";
    $memory_text .= '$config[\'players___'.$en['linkdown'].'\'] = array('.( strlen($prefix_common_words) ? ' //$prefix_common_words Removed = "'.trim($prefix_common_words).'"' : '' )."\n";
    foreach($down__e as $follower){

        if($follower['playerid']<1){
            continue;
        }

        //Does this have any Pins?
        foreach($this->Menchledger->fetch(array(
            'linkup' => $follower['playerid'],
            'linktype' => 41011, //PINNED FOLLOWER
            'linkvoid' => 0, //Not Void
        ), array(), 0) as $x_pinned) {
            if(!isset($pinned_down[$follower['playerid']])){
                $pinned_down[$follower['playerid']] = array($x_pinned['linkdown']);
            } elseif(!in_array($x_pinned['linkdown'], $pinned_down[$follower['playerid']])) {
                array_push($pinned_down[$follower['playerid']], $x_pinned['linkdown']);
            }
        }

        if($follower['linktype']==41011){
            if(!isset($pinned_up[$follower['playerid']])){
                $pinned_up[$follower['playerid']] = array($en['playerid']);
            } elseif(!in_array($en['playerid'], $pinned_up[$follower['playerid']])) {
                array_push($pinned_up[$follower['playerid']], $en['playerid']);
            }
        }

        //Fetch all followings for this follower:
        $down_up_ids = array(); //To be populated soon
        foreach($this->Menchledger->fetch(array(
            'linkdown' => $follower['playerid'],
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
                ), array('linkup'), 0) as $cp_en){
            array_push($down_up_ids, intval($cp_en['playerid']));
        }

        $memory_text .= '     '.$follower['playerid'].' => array('."\n";
        $memory_text .= '        \'m__handle\' => \''.$follower['playerhandle'].'\','."\n";
        $memory_text .= '        \'m__title\' => \''.(str_replace('\'','\\\'',str_replace($prefix_common_words,'',$follower['playertext']) )).'\','."\n";
        $memory_text .= '        \'m__message\' => \''.(str_replace('\'','\\\'',$follower['linktext'])).'\','."\n";
        $memory_text .= '        \'m__cover\' => \''.str_replace('\'','\\\'',view__cover($follower['playercover'])).'\','."\n";
        $memory_text .= '        \'m__following\' => array('.join(',',$down_up_ids).'),'."\n";
        $memory_text .= '     ),'."\n";

    }
    $memory_text .= ');'."\n";

}




//Append all App Handlers for quick checking:
$memory_text .= "\n"."\n";
foreach($this->Menchledger->fetch(array(
    'linkup' => 42043, //Handle Cache
    'linkvoid' => 0, //Not Void
    'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
), array('linkdown'), 0) as $handle){

    $memory_text .= '$config[\'handlplayers___'.$handle['playerid'].'\'] = array('."\n";
    foreach($this->Menchledger->fetch(array(
        'linkup' => $handle['playerid'],
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
        ), array('linkdown'), 0) as $app){
        $memory_text .= '     \''.strtolower($app['playerhandle']).'\' => '.$app['playerid'].','."\n";
    }
    $memory_text .= ');'."\n";
}




//Append Pinned Links:
$memory_text .= "\n"."\n";
$memory_text .= '$config[\'pinned_down\'] = array('."\n";
foreach($pinned_down as $key => $value){
    $memory_text .= '     '.$key.' => array('.join(',',$value).'),'."\n";
}
$memory_text .= ');'."\n";
$memory_text .= '$config[\'pinned_up\'] = array('."\n";
foreach($pinned_up as $key => $value){
    $memory_text .= '     '.$key.' => array('.join(',',$value).'),'."\n";
}
$memory_text .= ');'."\n";




$memory_text .= "\n"."\n";
$memory_text .= '$config[\'cache_time\'] = \''.time().'\';'."\n";

$save_time = date("Y-m-d H:i:s");

//Save Memory:
$memory_location = "application/config/mench_memory.php";
$memory_file = fopen($memory_location, "w+") or die("Unable to open file: ".$memory_location);
fwrite($memory_file, $memory_text);
fclose($memory_file);


//Now generate Routes file:
$routes_text .= '$route[\'translate_uri_dashes\'] = FALSE;'."\n";
$routes_text .= '$route[\'default_controller\'] = "app/index"; //Home'."\n";
$routes_text .= '$route[\'404_override\'] = "app/load"; //Error'."\n";
$routes_text .= "\n";

$special_route_text = '';
$routes_text .= '//APPS:'."\n\n";

foreach($this->Menchledger->fetch(array(
    'linkup' => 6287, //Apps
    'linktype IN (' . join(',', ( $memory_detected ? $this->config->item('playerids___32292') : $playerids___33337 )) . ')' => null, //SOURCE LINKS
    'linkvoid' => 0, //Not Void
), array('linkdown'), 0, 0, array('playertext' => 'ASC')) as $app) {

    if(!$memory_detected){
        $special_routes = false;
        foreach($this->Menchledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
            'linkup' => 42921,
            'linkdown' => $app['playerid'], //Required
        )) as $route){
            if(strlen($route['linktext'])>0){
                $special_routes = $route['linktext'];
            }
        }
    } else {
        $special_routes = ( in_array($app['playerid'], $this->config->item('playerids___42921')) && isset($players___42921[$app['playerid']]['m__message']) && strlen($players___42921[$app['playerid']]['m__message']) ? $players___42921[$app['playerid']]['m__message'] : false );
    }


    if(count($this->Menchledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
            'linkup' => 44330,
            'linkdown' => $app['playerid'], //Required
        )))){
        //Player AND Idea Input
        $routes_text .= '$route[\'(?i)'.$app['playerhandle'].'/([a-zA-Z0-9]+)@([a-zA-Z0-9]+)\'] = "app/load/'.$app['playerid'].'/$2/$1'.'";'."\n";
        $routes_text .= '$route[\'(?i)'.$app['playerhandle'].'/([a-zA-Z0-9]+)\'] = "app/load/'.$app['playerid'].'/0/$1'.'";'."\n"; //Should give error
        $routes_text .= '$route[\'(?i)'.$app['playerhandle'].'/@([a-zA-Z0-9]+)\'] = "app/load/'.$app['playerid'].'/$1/0'.'";'."\n"; //Should give error
    } elseif(count($this->Menchledger->fetch(array(
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
        'linkup' => 42905,
        'linkdown' => $app['playerid'], //Required
    )))){
        //Player Input
        if($special_routes){
            $special_route_text .= '$route[\''.$special_routes.'\'] = "app/load/'.$app['playerid'].'/$1'.'";'."\n";
        } else {
            $routes_text .= '$route[\'(?i)'.$app['playerhandle'].'/@([a-zA-Z0-9]+)\'] = "app/load/'.$app['playerid'].'/$1'.'";'."\n";
        }
    } elseif(count($this->Menchledger->fetch(array(
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
        'linkup' => 42911,
        'linkdown' => $app['playerid'], //Required
    )))){
        //Idea Input
        if($special_routes){
            $special_route_text .= '$route[\''.$special_routes.'\'] = "app/load/'.$app['playerid'].'/0/$1'.'";'."\n";
        } else {
            $routes_text .= '$route[\'(?i)'.$app['playerhandle'].'/([a-zA-Z0-9]+)\'] = "app/load/'.$app['playerid'].'/0/$1'.'";'."\n";
        }
    } elseif(count($this->Menchledger->fetch(array(
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $playerids___33337) . ')' => null, //SOURCE LINKS
        'linkup' => 44329,
        'linkdown' => $app['playerid'], //Required
    )))){
        //Discoveries Input
        if($special_routes){
            $special_route_text .= '$route[\''.$special_routes.'\'] = "app/load/'.$app['playerid'].'/0/$2/$1'.'";'."\n";
        } else {
            $routes_text .= '$route[\'(?i)'.$app['playerhandle'].'/([a-zA-Z0-9]+)/([a-zA-Z0-9]+)\'] = "app/load/'.$app['playerid'].'/0/$2/$1'.'";'."\n";
        }
    }

    //Always Have no Input option:
    if(!$special_routes){
        $routes_text .= '$route[\'(?i)'.$app['playerhandle'].'\'] = "app/load/'.$app['playerid'].'";'."\n";
    }

}

$routes_text .= "\n\n";
$routes_text .= '//Special Routing:'."\n\n";
$routes_text .= $special_route_text;

//Save Routes:
$routes_location = "application/config/routes.php";
$routes_file = fopen($routes_location, "w+") or die("Unable to open file: ".$routes_location);
fwrite($routes_file, $routes_text);
fclose($routes_file);


echo '<div class="margin-top-down"><div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Cached '.$total_nodes.' Players ('.$biggest_player_handle.' had '.$biggest_player_count.') & removed '.( $memory_detected ? reset_cache($linkplayer) : 'NONE' ).'.</div><div></div></div>';


//Show:
echo '<div>'.$memory_location.':</div>';
echo '<textarea class="mono-space table_frame">'.$memory_text.'</textarea>';

echo '<div>'.$routes_location.':</div>';
echo '<textarea class="mono-space table_frame">'.$routes_text.'</textarea>';