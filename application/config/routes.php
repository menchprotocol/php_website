<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//General:
$route['404_override']          = 'other/404_page_not_found';
$route['translate_uri_dashes']  = FALSE;
$route['default_controller']    = "intents";

//Miner Intents/Entities:
$route['entities/(:num)']       = "entities/en_miner_ui/$1";
$route['entities']              = "entities/en_miner_ui/0";
$route['intents/(:num)']        = "intents/fn___in_miner_ui/$1";
$route['intents']               = "intents/fn___in_miner_ui/0";

//Public Intent Landing Page:
$route['(:num)']                = "intents/fn___in_public_ui/$1";

//Student/Miner login/logout:
$route['login']                 = "entities/en_login_ui";
$route['logout']                = "entities/logout";