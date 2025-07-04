<?php


$start_time = date("Y-m-d H:i:s");
$memory_text = '';
$memory_text .= "<?php\n\n";
$memory_text .= '//UPDATED: ' . $start_time . "\n\n";
$memory_text .= 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');' . "\n\n";
$routes_text = $memory_text;

$pinned_down = array();
$pinned_up = array();
$total_nodes = 0;
$biggest_handle_count = 0;
$biggest_handle_handle = '';


//CONFIG VARS
foreach ($this->Chains->read(array(
    'chainhandleinput' => 4527,
    'chainhandletype' => 4230,
), array('chainhandleoutput'), 0, 0, array('handleid' => 'ASC')) as $en) {

    //Now fetch all its followers:
    $down__e = $this->Chains->read(array(
        'chainhandleinput' => $en['chainhandleoutput'],
        'chainhandletype' => 4230,
    ), array('chainhandleoutput'), 0, 0, handle_sort());


    $total_nodes += (1 + count($down__e));
    if (count($down__e) > $biggest_handle_count) {
        $biggest_handle_count = count($down__e);
        $biggest_handle_handle = '@' . $en['handlehandle'];
    }

    //Generate raw IDs:
    $down_ids = array();
    $down_titles = array();
    foreach ($down__e as $follower) {
        if ($follower['handleid'] > 0) {
            array_push($down_ids, $follower['handleid']);
            array_push($down_titles, $follower['handlevalue']);
        }
    }


    $prefix_common_words = prefix_common_words($down_titles); //Clean Titles
    $memory_text .= "\n" . '//' . $en['handlevalue'] . ':' . "\n";
    $memory_text .= '$config[\'handleids___' . $en['chainhandleoutput'] . '\'] = array(' . join(',', $down_ids) . ');' . "\n";
    $memory_text .= '$config[\'handles___' . $en['chainhandleoutput'] . '\'] = array(' . (strlen($prefix_common_words) ? ' //$prefix_common_words Removed = "' . trim($prefix_common_words) . '"' : '') . "\n";
    foreach ($down__e as $follower) {

        if ($follower['handleid'] < 1) {
            continue;
        }

        //Does this have any Pins?
        foreach ($this->Chains->read(array(
            'chainhandleinput' => $follower['handleid'],
            'chainhandletype' => 41011, //PINNED FOLLOWER
        ), array(), 0) as $x_pinned) {
            if (!isset($pinned_down[$follower['handleid']])) {
                $pinned_down[$follower['handleid']] = array($x_pinned['chainhandleoutput']);
            } elseif (!in_array($x_pinned['chainhandleoutput'], $pinned_down[$follower['handleid']])) {
                array_push($pinned_down[$follower['handleid']], $x_pinned['chainhandleoutput']);
            }
        }

        if ($follower['chainhandletype'] == 41011) {
            if (!isset($pinned_up[$follower['handleid']])) {
                $pinned_up[$follower['handleid']] = array($en['handleid']);
            } elseif (!in_array($en['handleid'], $pinned_up[$follower['handleid']])) {
                array_push($pinned_up[$follower['handleid']], $en['handleid']);
            }
        }

        //Fetch all followings for this follower:
        $down_up_ids = array(); //To be populated soon
        foreach ($this->Chains->read(array(
            'chainhandleoutput' => $follower['handleid'],
            'chainhandletype' => 4230,
        ), array('chainhandleinput'), 0) as $cp_en) {
            array_push($down_up_ids, intval($cp_en['handleid']));
        }

        $memory_text .= '     ' . $follower['handleid'] . ' => array(' . "\n";
        $memory_text .= '        \'m__handle\' => \'' . $follower['handlehandle'] . '\',' . "\n";
        $memory_text .= '        \'m__title\' => \'' . (str_replace('\'', '\\\'', str_replace($prefix_common_words, '', $follower['handlevalue']))) . '\',' . "\n";
        $memory_text .= '        \'m__message\' => \'' . (str_replace('\'', '\\\'', $follower['chainvalue'])) . '\',' . "\n";
        $memory_text .= '        \'m__cover\' => \'' . str_replace('\'', '\\\'', view_cover($follower['handlecover'])) . '\',' . "\n";
        $memory_text .= '        \'m__following\' => array(' . join(',', $down_up_ids) . '),' . "\n";
        $memory_text .= '     ),' . "\n";

    }
    $memory_text .= ');' . "\n";

}


//Append all App Handlers for quick checking:
$memory_text .= "\n" . "\n";
foreach ($this->Chains->read(array(
    'chainhandleinput' => 42043, //Handle Cache
    'chainhandletype' => 4230,
), array('chainhandleoutput'), 0) as $handle) {

    $memory_text .= '$config[\'handlhandles___' . $handle['handleid'] . '\'] = array(' . "\n";
    foreach ($this->Chains->read(array(
        'chainhandleinput' => $handle['handleid'],
        'chainhandletype' => 4230,
    ), array('chainhandleoutput'), 0) as $app) {
        $memory_text .= '     \'' . strtolower($app['handlehandle']) . '\' => ' . $app['handleid'] . ',' . "\n";
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
    'chainhandleinput' => 6287, //Apps
    'chainhandletype' => 4230,
), array('chainhandleoutput'), 0, 0, array('handlevalue' => 'ASC')) as $app) {

    if (!$memory_detected) {
        $special_routes = false;
        foreach ($this->Chains->read(array(
            'chainhandletype' => 4230,
            'chainhandleinput' => 42921,
            'chainhandleoutput' => $app['handleid'], //Required
        )) as $route) {
            if (strlen($route['chainvalue']) > 0) {
                $special_routes = $route['chainvalue'];
            }
        }
    } else {
        $handles___42921 = $this->config->item('handles___42921');
        $special_routes = (in_array($app['handleid'], $this->config->item('handleids___42921')) && isset($handles___42921[$app['handleid']]['m__message']) && strlen($handles___42921[$app['handleid']]['m__message']) ? $handles___42921[$app['handleid']]['m__message'] : false);
    }


    if (count($this->Chains->read(array(
        'chainhandletype' => 4230,
        'chainhandleinput' => 44330,
        'chainhandleoutput' => $app['handleid'], //Required
    )))) {
        //Handle AND Hahstag Input
        $routes_text .= '$route[\'(?i)' . $app['handlehandle'] . '/([a-zA-Z0-9]+)@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['handleid'] . '/$2/$1' . '";' . "\n";
        $routes_text .= '$route[\'(?i)' . $app['handlehandle'] . '/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['handleid'] . '/0/$1' . '";' . "\n"; //Should give error
        $routes_text .= '$route[\'(?i)' . $app['handlehandle'] . '/@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['handleid'] . '/$1/0' . '";' . "\n"; //Should give error
    } elseif (count($this->Chains->read(array(
        'chainhandletype' => 4230,
        'chainhandleinput' => 42905,
        'chainhandleoutput' => $app['handleid'], //Required
    )))) {
        //Handle Input
        if ($special_routes) {
            $special_route_text .= '$route[\'' . $special_routes . '\'] = "controller/load/' . $app['handleid'] . '/$1' . '";' . "\n";
        } else {
            $routes_text .= '$route[\'(?i)' . $app['handlehandle'] . '/@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['handleid'] . '/$1' . '";' . "\n";
        }
    } elseif (count($this->Chains->read(array(
        'chainhandletype' => 4230,
        'chainhandleinput' => 42911,
        'chainhandleoutput' => $app['handleid'], //Required
    )))) {
        //Hahstag Input
        if ($special_routes) {
            $special_route_text .= '$route[\'' . $special_routes . '\'] = "controller/load/' . $app['handleid'] . '/0/$1' . '";' . "\n";
        } else {
            $routes_text .= '$route[\'(?i)' . $app['handlehandle'] . '/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['handleid'] . '/0/$1' . '";' . "\n";
        }
    } elseif (count($this->Chains->read(array(
        'chainhandletype' => 4230,
        'chainhandleinput' => 44329,
        'chainhandleoutput' => $app['handleid'], //Required
    )))) {
        //Discoveries Input
        if ($special_routes) {
            $special_route_text .= '$route[\'' . $special_routes . '\'] = "controller/load/' . $app['handleid'] . '/0/$2/$1' . '";' . "\n";
        } else {
            $routes_text .= '$route[\'(?i)' . $app['handlehandle'] . '/([a-zA-Z0-9]+)/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['handleid'] . '/0/$2/$1' . '";' . "\n";
        }
    }

    //Always Have no Input option:
    if (!$special_routes) {
        $routes_text .= '$route[\'(?i)' . $app['handlehandle'] . '\'] = "controller/load/' . $app['handleid'] . '";' . "\n";
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

echo '<div class="margin-top-down"><div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Cached ' . $total_nodes . ' Handles (' . $biggest_handle_handle . ' had ' . $biggest_handle_count . ') & removed ' . ($memory_detected ? reset_cache($chainhandlecreator) : 'NONE') . '.</div><div></div></div>';

//Show:
echo '<div>' . $memory_location . ':</div>';
echo '<textarea class="mono-space table_frame">' . $memory_text . '</textarea>';

echo '<div>' . $routes_location . ':</div>';
echo '<textarea class="mono-space table_frame">' . $routes_text . '</textarea>';