<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['404_override'] = 'other/404_page_not_found';
$route['translate_uri_dashes'] = FALSE;

/* ******************************
 * Front
 ****************************** */


$route['default_controller'] = "custom"; // Home page
$route['ses'] = "custom/ses"; //Raw session logs
$route['jobs'] = "custom/jobs";
$route['info'] = "custom/info"; //PHP Info
$route['login'] = "entities/login_ui"; //Bootcamp Operator login
$route['logout'] = "entities/logout"; //Logout from entites

//Matrix:
$route['entities/(:num)'] = "entities/entity_manage/$1";
$route['entities'] = "entities/entity_manage/" . $this->config->item('en_primary_id');
$route['intents/(:num)'] = "intents/fn___in_miner_ui/$1";
$route['intents'] = "intents/fn___in_miner_ui/" . $this->config->item('in_primary_id');

//Intent Landing Page:
$route['(:num)'] = "intents/fn___in_public_ui/$1"; //Public intent browser

