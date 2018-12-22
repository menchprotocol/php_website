<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['404_override'] = 'other/404_page_not_found';
$route['translate_uri_dashes'] = FALSE;

$route['default_controller'] = "custom";
$route['ses'] = "custom/ses";
$route['info'] = "custom/info";
$route['login'] = "entities/en_login_ui";
$route['logout'] = "entities/logout";

//Matrix UI:
$route['entities/(:num)'] = "entities/en_miner_ui/$1";
$route['entities'] = "entities/en_miner_ui/" . $this->config->item('en_primary_id');
$route['intents/(:num)'] = "intents/fn___in_miner_ui/$1";
$route['intents'] = "intents/fn___in_miner_ui/" . $this->config->item('in_primary_id');

//Intent Landing Page:
$route['(:num)'] = "intents/fn___in_public_ui/$1"; //Public intent browser

