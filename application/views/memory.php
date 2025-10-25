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
$biggest_user_count = 0;
$biggest_user_user = '';


//CONFIG VARS
foreach ($this->Chains->read(array(
    'chainuserinput' => 4527,
    'chainusertype' => 4230,
), array('chainuseroutput'), 0, 0, array('userid' => 'ASC')) as $en) {

    //Now fetch all its followers:
    $down__e = $this->Chains->read(array(
        'chainuserinput' => $en['chainuseroutput'],
        'chainusertype' => 4230,
    ), array('chainuseroutput'), 0, 0, user_sort());


    $total_nodes += (1 + count($down__e));
    if (count($down__e) > $biggest_user_count) {
        $biggest_user_count = count($down__e);
        $biggest_user_user = '@' . $en['userhandle'];
    }

    //Generate raw IDs:
    $down_ids = array();
    $down_titles = array();
    foreach ($down__e as $follower) {
        if ($follower['userid'] > 0) {
            array_push($down_ids, $follower['userid']);
            array_push($down_titles, $follower['username']);
        }
    }


    $prefix_common_words = prefix_common_words($down_titles); //Clean Titles
    $memory_text .= "\n" . '//' . $en['username'] . ':' . "\n";
    $memory_text .= '$config[\'userids___' . $en['chainuseroutput'] . '\'] = array(' . join(',', $down_ids) . ');' . "\n";
    $memory_text .= '$config[\'users___' . $en['chainuseroutput'] . '\'] = array(' . (strlen($prefix_common_words) ? ' //$prefix_common_words Removed = "' . trim($prefix_common_words) . '"' : '') . "\n";
    foreach ($down__e as $follower) {

        if ($follower['userid'] < 1) {
            continue;
        }

        //Does this have any Pins?
        foreach ($this->Chains->read(array(
            'chainuserinput' => $follower['userid'],
            'chainusertype' => 41011, //PINNED FOLLOWER
        ), array(), 0) as $x_pinned) {
            if (!isset($pinned_down[$follower['userid']])) {
                $pinned_down[$follower['userid']] = array($x_pinned['chainuseroutput']);
            } elseif (!in_array($x_pinned['chainuseroutput'], $pinned_down[$follower['userid']])) {
                array_push($pinned_down[$follower['userid']], $x_pinned['chainuseroutput']);
            }
        }

        if ($follower['chainusertype'] == 41011) {
            if (!isset($pinned_up[$follower['userid']])) {
                $pinned_up[$follower['userid']] = array($en['userid']);
            } elseif (!in_array($en['userid'], $pinned_up[$follower['userid']])) {
                array_push($pinned_up[$follower['userid']], $en['userid']);
            }
        }

        //Fetch all followings for this follower:
        $down_up_ids = array(); //To be populated soon
        foreach ($this->Chains->read(array(
            'chainuseroutput' => $follower['userid'],
            'chainusertype' => 4230,
        ), array('chainuserinput'), 0) as $cp_en) {
            array_push($down_up_ids, intval($cp_en['userid']));
        }

        $memory_text .= '     ' . $follower['userid'] . ' => array(' . "\n";
        $memory_text .= '        \'m__handle\' => \'' . $follower['userhandle'] . '\',' . "\n";
        $memory_text .= '        \'m__name\' => \'' . (str_replace('\'', '\\\'', str_replace($prefix_common_words, '', $follower['username']))) . '\',' . "\n";
        $memory_text .= '        \'m__cover\' => \'' . str_replace('\'', '\\\'', view_cover($follower['usercover'])) . '\',' . "\n";
        $memory_text .= '        \'m__message\' => \'' . (str_replace('\'', '\\\'', ( strlen($follower['chainvalue']) ? $follower['chainvalue'] : $follower['userbio'] ))) . '\',' . "\n";
        $memory_text .= '        \'m__following\' => array(' . join(',', $down_up_ids) . '),' . "\n";
        $memory_text .= '     ),' . "\n";

    }
    $memory_text .= ');' . "\n";

}


//Append all App Userrs for quick checking:
$memory_text .= "\n" . "\n";
foreach ($this->Chains->read(array(
    'chainuserinput' => 42043, //User Cache
    'chainusertype' => 4230,
), array('chainuseroutput'), 0) as $user) {

    $memory_text .= '$config[\'handlusers___' . $user['userid'] . '\'] = array(' . "\n";
    foreach ($this->Chains->read(array(
        'chainuserinput' => $user['userid'],
        'chainusertype' => 4230,
    ), array('chainuseroutput'), 0) as $app) {
        $memory_text .= '     \'' . strtolower($app['userhandle']) . '\' => ' . $app['userid'] . ',' . "\n";
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
    'chainuserinput' => 6287, //Apps
    'chainusertype' => 4230,
), array('chainuseroutput'), 0, 0, array('username' => 'ASC')) as $app) {

    if (!$memory_detected) {
        $special_routes = false;
        foreach ($this->Chains->read(array(
            'chainusertype' => 4230,
            'chainuserinput' => 42921,
            'chainuseroutput' => $app['userid'], //Required
        )) as $route) {
            if (strlen($route['chainvalue']) > 0) {
                $special_routes = $route['chainvalue'];
            }
        }
    } else {
        $users___42921 = $this->config->item('users___42921');
        $special_routes = (in_array($app['userid'], $this->config->item('userids___42921')) && isset($users___42921[$app['userid']]['m__message']) && strlen($users___42921[$app['userid']]['m__message']) ? $users___42921[$app['userid']]['m__message'] : false);
    }


    if (count($this->Chains->read(array(
        'chainusertype' => 4230,
        'chainuserinput' => 44330,
        'chainuseroutput' => $app['userid'], //Required
    )))) {
        //User AND Post Input
        $routes_text .= '$route[\'(?i)' . $app['userhandle'] . '/([a-zA-Z0-9]+)@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['userid'] . '/$2/$1' . '";' . "\n";
        $routes_text .= '$route[\'(?i)' . $app['userhandle'] . '/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['userid'] . '/0/$1' . '";' . "\n"; //Should give error
        $routes_text .= '$route[\'(?i)' . $app['userhandle'] . '/@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['userid'] . '/$1/0' . '";' . "\n"; //Should give error
    } elseif (count($this->Chains->read(array(
        'chainusertype' => 4230,
        'chainuserinput' => 3460085,
        'chainuseroutput' => $app['userid'], //Required
    )))) {
        //User OR Post Input
        $routes_text .= '$route[\'(?i)' . $app['userhandle'] . '/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['userid'] . '/0/$1' . '";' . "\n"; //Should give error
        $routes_text .= '$route[\'(?i)' . $app['userhandle'] . '/@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['userid'] . '/$1/0' . '";' . "\n"; //Should give error
    } elseif (count($this->Chains->read(array(
        'chainusertype' => 4230,
        'chainuserinput' => 42905,
        'chainuseroutput' => $app['userid'], //Required
    )))) {
        //User Input
        if ($special_routes) {
            $special_route_text .= '$route[\'' . $special_routes . '\'] = "controller/load/' . $app['userid'] . '/$1' . '";' . "\n";
        } else {
            $routes_text .= '$route[\'(?i)' . $app['userhandle'] . '/@([a-zA-Z0-9]+)\'] = "controller/load/' . $app['userid'] . '/$1' . '";' . "\n";
        }
    } elseif (count($this->Chains->read(array(
        'chainusertype' => 4230,
        'chainuserinput' => 42911,
        'chainuseroutput' => $app['userid'], //Required
    )))) {
        //Post Input
        if ($special_routes) {
            $special_route_text .= '$route[\'' . $special_routes . '\'] = "controller/load/' . $app['userid'] . '/0/$1' . '";' . "\n";
        } else {
            $routes_text .= '$route[\'(?i)' . $app['userhandle'] . '/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['userid'] . '/0/$1' . '";' . "\n";
        }
    } elseif (count($this->Chains->read(array(
        'chainusertype' => 4230,
        'chainuserinput' => 44329,
        'chainuseroutput' => $app['userid'], //Required
    )))) {
        //Discoveries Input
        if ($special_routes) {
            $special_route_text .= '$route[\'' . $special_routes . '\'] = "controller/load/' . $app['userid'] . '/0/$2/$1' . '";' . "\n";
        } else {
            $routes_text .= '$route[\'(?i)' . $app['userhandle'] . '/([a-zA-Z0-9]+)/([a-zA-Z0-9]+)\'] = "controller/load/' . $app['userid'] . '/0/$2/$1' . '";' . "\n";
        }
    }

    //Always Have no Input option:
    if (!$special_routes) {
        $routes_text .= '$route[\'(?i)' . $app['userhandle'] . '\'] = "controller/load/' . $app['userid'] . '";' . "\n";
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

echo '<div class="margin-top-down"><div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Cached ' . $total_nodes . ' Users (' . $biggest_user_user . ' had ' . $biggest_user_count . ') & removed ' . ($memory_detected ? reset_cache($chainusercreator) : 'NONE') . '.</div><div></div></div>';

//Show:
echo '<div>' . $memory_location . ':</div>';
echo '<textarea class="mono-space table_frame">' . $memory_text . '</textarea>';

echo '<div>' . $routes_location . ':</div>';
echo '<textarea class="mono-space table_frame">' . $routes_text . '</textarea>';