<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']      = FALSE;
$route['404_override']              = 'plugin/plugin_load';

$route['default_controller']        = "read/read_coin";

$route['(:num)']                    = "read/read_coin/$1";
$route['source/(:num)']             = "source/source_coin/$1";
$route['idea/(:num)']               = "idea/idea_coin/$1";
$route['plugin/(:num)']             = "plugin/plugin_load/$1";
