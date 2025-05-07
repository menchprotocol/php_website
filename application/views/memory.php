<?php


$start_time = date("Y-m-d H:i:s");
$memory_text = '';
$memory_text .= "<?php\n\n";
$memory_text .= '//UPDATED: ' . $start_time . "\n\n";
$memory_text .= 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');' . "\n\n";
$routes_text = $memory_text;
$sourceids___33337 = ( $memory_detected ? $this->config->item('sourceids___33337') : array(42897, 42849, 42791, 42659, 4251, 42581, 42580, 42579, 42570, 42567, 42554, 42518, 42516, 42440, 42427, 42335, 41011, 32489, 32486, 4230) );


$pinned_down = array();
$pinned_up = array();
$total_nodes = 0;
$biggest_source_count = 0;
$biggest_source_handle = '';


//CONFIG VARS
foreach ($this->Chains->read(array(
    'chainsourceup' => 4527,
    'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
), array('chainsourcedown'), 0, 0, array('sourceid' => 'ASC')) as $en) {

    //Now fetch all its followers:
    $down__e = $this->Chains->read(array(
        'chainsourceup' => $en['chainsourcedown'],
        'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
    ), array('chainsourcedown'), 0, 0, source_sort());


    $total_nodes += (1 + count($down__e));
    if (count($down__e) > $biggest_source_count) {
        $biggest_source_count = count($down__e);
        $biggest_source_handle = '@' . $en['sourcehandle'];
    }

    //Generate raw IDs:
    $down_ids = array();
    $down_titles = array();
    foreach ($down__e as $follower) {
        if ($follower['sourceid'] > 0) {
            array_push($down_ids, $follower['sourceid']);
            array_push($down_titles, $follower['sourcevalue']);
        }
    }


    $prefix_common_words = prefix_common_words($down_titles); //Clean Titles
    $memory_text .= "\n" . '//' . $en['sourcevalue'] . ':' . "\n";
    $memory_text .= '$config[\'sourceids___' . $en['chainsourcedown'] . '\'] = array(' . join(',', $down_ids) . ');' . "\n";
    $memory_text .= '$config[\'sources___' . $en['chainsourcedown'] . '\'] = array(' . (strlen($prefix_common_words) ? ' //$prefix_common_words Removed = "' . trim($prefix_common_words) . '"' : '') . "\n";
    foreach ($down__e as $follower) {

        if ($follower['sourceid'] < 1) {
            continue;
        }

        //Does this have any Pins?
        foreach ($this->Chains->read(array(
            'chainsourceup' => $follower['sourceid'],
            'chainsourcetype' => 41011, //PINNED FOLLOWER
        ), array(), 0) as $x_pinned) {
            if (!isset($pinned_down[$follower['sourceid']])) {
                $pinned_down[$follower['sourceid']] = array($x_pinned['chainsourcedown']);
            } elseif (!in_array($x_pinned['chainsourcedown'], $pinned_down[$follower['sourceid']])) {
                array_push($pinned_down[$follower['sourceid']], $x_pinned['chainsourcedown']);
            }
        }

        if ($follower['chainsourcetype'] == 41011) {
            if (!isset($pinned_up[$follower['sourceid']])) {
                $pinned_up[$follower['sourceid']] = array($en['sourceid']);
            } elseif (!in_array($en['sourceid'], $pinned_up[$follower['sourceid']])) {
                array_push($pinned_up[$follower['sourceid']], $en['sourceid']);
            }
        }

        //Fetch all followings for this follower:
        $down_up_ids = array(); //To be populated soon
        foreach ($this->Chains->read(array(
            'chainsourcedown' => $follower['sourceid'],
            'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
        ), array('chainsourceup'), 0) as $cp_en) {
            array_push($down_up_ids, intval($cp_en['sourceid']));
        }

        $memory_text .= '     ' . $follower['sourceid'] . ' => array(' . "\n";
        $memory_text .= '        \'m__handle\' => \'' . $follower['sourcehandle'] . '\',' . "\n";
        $memory_text .= '        \'m__title\' => \'' . (str_replace('\'', '\\\'', str_replace($prefix_common_words, '', $follower['sourcevalue']))) . '\',' . "\n";
        $memory_text .= '        \'m__message\' => \'' . (str_replace('\'', '\\\'', $follower['chainvalue'])) . '\',' . "\n";
        $memory_text .= '        \'m__cover\' => \'' . str_replace('\'', '\\\'', view_cover($follower['sourcecover'])) . '\',' . "\n";
        $memory_text .= '        \'m__following\' => array(' . join(',', $down_up_ids) . '),' . "\n";
        $memory_text .= '     ),' . "\n";

    }
    $memory_text .= ');' . "\n";

}


//Append all App Handlers for quick checking:
$memory_text .= "\n" . "\n";
foreach ($this->Chains->read(array(
    'chainsourceup' => 42043, //Handle Cache
    'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
), array('chainsourcedown'), 0) as $handle) {

    $memory_text .= '$config[\'handlsources___' . $handle['sourceid'] . '\'] = array(' . "\n";
    foreach ($this->Chains->read(array(
        'chainsourceup' => $handle['sourceid'],
        'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
    ), array('chainsourcedown'), 0) as $app) {
        $memory_text .= '     \'' . strtolower($app['sourcehandle']) . '\' => ' . $app['sourceid'] . ',' . "\n";
    }
    $memory_text .= ');' . "\n";
}


//Append Pinned Chains:
$memory_text .= "\n" . "\n";
$memory_text .= '$config[\'pinned_down\'] = array(' . "\n";
foreach ($pinned_down as $key => $value) {
    $memory_text .= '     ' . $key . ' => array(' . join(',', $value) . '),' . "\n";
}
$memory_text .= ');' . "\n";
$memory_text .= '$config[\'pinned_up\'] = array(' . "\n";
foreach ($pinned_up as $key => $value) {
    $memory_text .= '     ' . $key . ' => array(' . join(',', $value) . '),' . "\n";
}
$memory_text .= ');' . "\n";


$memory_text .= "\n" . "\n";
$memory_text .= '$config[\'cache_time\'] = \'' . time() . '\';' . "\n";

$save_time = date("Y-m-d H:i:s");

//Save Memory:
$memory_location = "application/config/mench_memory.php";
$memory_file = fopen($memory_location, "w+") or die("Unable to open file: " . $memory_location);
fwrite($memory_file, $memory_text);
fclose($memory_file);


//Now generate Routes file:
$routes_text .= '$route[\'translate_uri_dashes\'] = FALSE;' . "\n";
$routes_text .= '$route[\'default_controller\'] = "controller/index"; //Home' . "\n";
$routes_text .= '$route[\'404_override\'] = "controller/load"; //Error' . "\n";
$routes_text .= "\n";

$special_route_text = '';
$routes_text .= '//APPS:' . "\n\n";

foreach ($this->Chains->read(array(
    'chainsourceup' => 6287, //Apps
    'chainsourcetype IN (' . join(',', ($memory_detected ? $this->config->item('sourceids___13548') : $sourceids___33337)) . ')' => null, //SOURCE CHAINS
), array('chainsourcedown'), 0, 0, array('sourcevalue' => 'ASC')) as $app) {

    if (!$memory_detected) {
        $special_routes = false;
        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
            'chainsourceup' => 42921,
            'chainsourcedown' => $app['sourceid'], //Required
        )) as $route) {
            if (strlen($route['chainvalue']) > 0) {
                $special_routes = $route['chainvalue'];
            }
        }
    } else {
        $sources___42921 = $this->config->item('sources___42921');
        $special_routes = (in_array($app['sourceid'], $this->config->item('sourceids___42921')) && isset($sources___42921[$app['sourceid']]['m__message']) && strlen($sources___42921[$app['sourceid']]['m__message']) ? $sources___42921[$app['sourceid']]['m__message'] : false);
    }


    if (count($this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
        'chainsourceup' => 44330,
        'chainsourcedown' => $app['sourceid'], //Required
    )))) {
        //Source AND Idea Input
        $routes_text .= '$route[\'(?i)' . $app['sourcehandle'] . '/([a-zA-Z0-9]+)@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['sourceid'] . '/$2/$1' . '";' . "\n";
        $routes_text .= '$route[\'(?i)' . $app['sourcehandle'] . '/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['sourceid'] . '/0/$1' . '";' . "\n"; //Should give error
        $routes_text .= '$route[\'(?i)' . $app['sourcehandle'] . '/@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['sourceid'] . '/$1/0' . '";' . "\n"; //Should give error
    } elseif (count($this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
        'chainsourceup' => 42905,
        'chainsourcedown' => $app['sourceid'], //Required
    )))) {
        //Source Input
        if ($special_routes) {
            $special_route_text .= '$route[\'' . $special_routes . '\'] = "controller/load/' . $app['sourceid'] . '/$1' . '";' . "\n";
        } else {
            $routes_text .= '$route[\'(?i)' . $app['sourcehandle'] . '/@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['sourceid'] . '/$1' . '";' . "\n";
        }
    } elseif (count($this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
        'chainsourceup' => 42911,
        'chainsourcedown' => $app['sourceid'], //Required
    )))) {
        //Idea Input
        if ($special_routes) {
            $special_route_text .= '$route[\'' . $special_routes . '\'] = "controller/load/' . $app['sourceid'] . '/0/$1' . '";' . "\n";
        } else {
            $routes_text .= '$route[\'(?i)' . $app['sourcehandle'] . '/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['sourceid'] . '/0/$1' . '";' . "\n";
        }
    } elseif (count($this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $sourceids___33337) . ')' => null, //SOURCE CHAINS
        'chainsourceup' => 44329,
        'chainsourcedown' => $app['sourceid'], //Required
    )))) {
        //Discoveries Input
        if ($special_routes) {
            $special_route_text .= '$route[\'' . $special_routes . '\'] = "controller/load/' . $app['sourceid'] . '/0/$2/$1' . '";' . "\n";
        } else {
            $routes_text .= '$route[\'(?i)' . $app['sourcehandle'] . '/([a-zA-Z0-9]+)/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['sourceid'] . '/0/$2/$1' . '";' . "\n";
        }
    }

    //Always Have no Input option:
    if (!$special_routes) {
        $routes_text .= '$route[\'(?i)' . $app['sourcehandle'] . '\'] = "controller/load/' . $app['sourceid'] . '";' . "\n";
    }

}

$routes_text .= "\n\n";
$routes_text .= '//Special Routing:' . "\n\n";
$routes_text .= $special_route_text;

//Save Routes:
$routes_location = "application/config/routes.php";
$routes_file = fopen($routes_location, "w+") or die("Unable to open file: " . $routes_location);
fwrite($routes_file, $routes_text);
fclose($routes_file);

echo '<div class="margin-top-down"><div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Cached ' . $total_nodes . ' Sources (' . $biggest_source_handle . ' had ' . $biggest_source_count . ') & removed ' . ($memory_detected ? reset_cache($chainsourcecreator) : 'NONE') . '.</div><div></div></div>';

//Show:
echo '<div>' . $memory_location . ':</div>';
echo '<textarea class="mono-space table_frame">' . $memory_text . '</textarea>';

echo '<div>' . $routes_location . ':</div>';
echo '<textarea class="mono-space table_frame">' . $routes_text . '</textarea>';