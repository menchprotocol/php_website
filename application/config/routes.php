<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']      = FALSE;

$route['404_override']              = 'source/source_404';
$route['plugin/(:num)']             = "plugin/plugin_load/$1";

$route['default_controller']        = "discover/discover_coin";
$route['(:num)']                    = "discover/discover_coin/$1";

$route['source/(:num)']             = "source/en_coin/$1";

$route['idea/(:num)']               = "idea/in_coin/$1";
