<?php


$start_time = date("Y-m-d H:i:s");
$memory_text = '';
$memory_text .= "<?php\n\n";
$memory_text .= '//UPDATED: '.$start_time."\n\n";
$memory_text .= 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'."\n\n";
$routes_text = $memory_text;


if($memory_detected){

    //EASY:
    $n___33337 = $this->config->item('n___33337');
    $e___42921 = $this->config->item('e___42921');

} else {

    //Define Manually (Might need to be updated)
    $n___33337 = array(42897, 42849, 42791, 42659, 4251, 42581, 42580, 42579, 42570, 42567, 42554, 42518, 42516, 42440, 42427, 42335, 41011, 32489, 32486, 4230);

}

$pinned_down = array();
$pinned_up = array();
$total_nodes = 0;
$biggest_source_count = 0;
$biggest_source_handle = '';


//CONFIG VARS
foreach($this->Mench_ledger->fetch(array(
    'link_up' => 4527,
    'link_void' => 0, //Not Void
    'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
), array('link_down'), 0, 0, array('e__id' => 'ASC')) as $en){

    //Now fetch all its followers:
    $down__e = $this->Mench_ledger->fetch(array(
        'link_up' => $en['link_down'],
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
        ), array('link_down'), 0, 0, sort__e());


    $total_nodes += (1 + count($down__e));
    if(count($down__e)>$biggest_source_count){
        $biggest_source_count = count($down__e);
        $biggest_source_handle = '@'.$en['e__handle'];
    }

    //Generate raw IDs:
    $down_ids = array();
    $down_titles = array();
    foreach($down__e as $follower){
        array_push($down_ids , $follower['e__id']);
        array_push($down_titles , $follower['e__title']);
    }


    $prefix_common_words = prefix_common_words($down_titles); //Clean Titles
    $memory_text .= "\n".'//'.$en['e__title'].':'."\n";
    $memory_text .= '$config[\'n___'.$en['link_down'].'\'] = array('.join(',',$down_ids).');'."\n";
    $memory_text .= '$config[\'e___'.$en['link_down'].'\'] = array('.( strlen($prefix_common_words) ? ' //$prefix_common_words Removed = "'.trim($prefix_common_words).'"' : '' )."\n";
    foreach($down__e as $follower){

        //Does this have any Pins?
        foreach($this->Mench_ledger->fetch(array(
            'link_up' => $follower['e__id'],
            'link_type' => 41011, //PINNED FOLLOWER
            'link_void' => 0, //Not Void
        ), array(), 0) as $x_pinned) {
            if(!isset($pinned_down[$follower['e__id']])){
                $pinned_down[$follower['e__id']] = array($x_pinned['link_down']);
            } elseif(!in_array($x_pinned['link_down'], $pinned_down[$follower['e__id']])) {
                array_push($pinned_down[$follower['e__id']], $x_pinned['link_down']);
            }
        }

        if($follower['link_type']==41011){
            if(!isset($pinned_up[$follower['e__id']])){
                $pinned_up[$follower['e__id']] = array($en['e__id']);
            } elseif(!in_array($en['e__id'], $pinned_up[$follower['e__id']])) {
                array_push($pinned_up[$follower['e__id']], $en['e__id']);
            }
        }

        //Fetch all followings for this follower:
        $down_up_ids = array(); //To be populated soon
        foreach($this->Mench_ledger->fetch(array(
            'link_down' => $follower['e__id'],
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
                ), array('link_up'), 0) as $cp_en){
            array_push($down_up_ids, intval($cp_en['e__id']));
        }

        $memory_text .= '     '.$follower['e__id'].' => array('."\n";
        $memory_text .= '        \'m__handle\' => \''.$follower['e__handle'].'\','."\n";
        $memory_text .= '        \'m__title\' => \''.(str_replace('\'','\\\'',str_replace($prefix_common_words,'',$follower['e__title']) )).'\','."\n";
        $memory_text .= '        \'m__message\' => \''.(str_replace('\'','\\\'',$follower['link_text'])).'\','."\n";
        $memory_text .= '        \'m__cover\' => \''.str_replace('\'','\\\'',view__cover($follower['e__cover'])).'\','."\n";
        $memory_text .= '        \'m__following\' => array('.join(',',$down_up_ids).'),'."\n";
        $memory_text .= '     ),'."\n";

    }
    $memory_text .= ');'."\n";

}




//Append all App Handlers for quick checking:
$memory_text .= "\n"."\n";
foreach($this->Mench_ledger->fetch(array(
    'link_up' => 42043, //Handle Cache
    'link_void' => 0, //Not Void
    'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
), array('link_down'), 0) as $handle){

    $memory_text .= '$config[\'handle___'.$handle['e__id'].'\'] = array('."\n";
    foreach($this->Mench_ledger->fetch(array(
        'link_up' => $handle['e__id'],
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
        ), array('link_down'), 0) as $app){
        $memory_text .= '     \''.strtolower($app['e__handle']).'\' => '.$app['e__id'].','."\n";
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

foreach($this->Mench_ledger->fetch(array(
    'link_up' => 6287, //Apps
    'link_type IN (' . join(',', ( $memory_detected ? $this->config->item('n___32292') : $n___33337 )) . ')' => null, //SOURCE LINKS
    'link_void' => 0, //Not Void
), array('link_down'), 0, 0, array('e__title' => 'ASC')) as $app) {

    if(!$memory_detected){
        $special_routes = false;
        foreach($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
            'link_up' => 42921,
            'link_down' => $app['e__id'], //Required
        )) as $route){
            if(strlen($route['link_text'])>0){
                $special_routes = $route['link_text'];
            }
        }
    } else {
        $special_routes = ( in_array($app['e__id'], $this->config->item('n___42921')) && isset($e___42921[$app['e__id']]['m__message']) && strlen($e___42921[$app['e__id']]['m__message']) ? $e___42921[$app['e__id']]['m__message'] : false );
    }


    if(count($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
            'link_up' => 44330,
            'link_down' => $app['e__id'], //Required
        )))){
        //Source AND Idea Input
        $routes_text .= '$route[\'(?i)'.$app['e__handle'].'/([a-zA-Z0-9]+)@([a-zA-Z0-9]+)\'] = "app/load/'.$app['e__id'].'/$2/$1'.'";'."\n";
        $routes_text .= '$route[\'(?i)'.$app['e__handle'].'/([a-zA-Z0-9]+)\'] = "app/load/'.$app['e__id'].'/0/$1'.'";'."\n"; //Should give error
        $routes_text .= '$route[\'(?i)'.$app['e__handle'].'/@([a-zA-Z0-9]+)\'] = "app/load/'.$app['e__id'].'/$1/0'.'";'."\n"; //Should give error
    } elseif(count($this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
        'link_up' => 42905,
        'link_down' => $app['e__id'], //Required
    )))){
        //Source Input
        if($special_routes){
            $special_route_text .= '$route[\''.$special_routes.'\'] = "app/load/'.$app['e__id'].'/$1'.'";'."\n";
        } else {
            $routes_text .= '$route[\'(?i)'.$app['e__handle'].'/@([a-zA-Z0-9]+)\'] = "app/load/'.$app['e__id'].'/$1'.'";'."\n";
        }
    } elseif(count($this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
        'link_up' => 42911,
        'link_down' => $app['e__id'], //Required
    )))){
        //Idea Input
        if($special_routes){
            $special_route_text .= '$route[\''.$special_routes.'\'] = "app/load/'.$app['e__id'].'/0/$1'.'";'."\n";
        } else {
            $routes_text .= '$route[\'(?i)'.$app['e__handle'].'/([a-zA-Z0-9]+)\'] = "app/load/'.$app['e__id'].'/0/$1'.'";'."\n";
        }
    } elseif(count($this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
        'link_up' => 44329,
        'link_down' => $app['e__id'], //Required
    )))){
        //Discoveries Input
        if($special_routes){
            $special_route_text .= '$route[\''.$special_routes.'\'] = "app/load/'.$app['e__id'].'/0/$2/$1'.'";'."\n";
        } else {
            $routes_text .= '$route[\'(?i)'.$app['e__handle'].'/([a-zA-Z0-9]+)/([a-zA-Z0-9]+)\'] = "app/load/'.$app['e__id'].'/0/$2/$1'.'";'."\n";
        }
    }


    //Always Have no Input option:
    if(!$special_routes){
        $routes_text .= '$route[\'(?i)'.$app['e__handle'].'\'] = "app/load/'.$app['e__id'].'";'."\n";
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


echo '<div class="margin-top-down"><div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Cached '.$total_nodes.' Sources ('.$biggest_source_handle.' had '.$biggest_source_count.') & removed '.( $memory_detected ? reset_cache($link_player) : 'NONE' ).'.</div><div></div></div>';


//Show:
echo '<div>'.$memory_location.':</div>';
echo '<textarea class="mono-space table_frame">'.$memory_text.'</textarea>';

echo '<div>'.$routes_location.':</div>';
echo '<textarea class="mono-space table_frame">'.$routes_text.'</textarea>';