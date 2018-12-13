<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['404_override'] = 'other/404_page_not_found';
$route['translate_uri_dashes'] = FALSE;

/* ******************************
 * Front
 ****************************** */

$route['default_controller'] = "custom"; // Home page
$route['train'] = "custom/train";
$route['terms'] = "custom/terms";
$route['ses'] = "custom/ses"; //Raw session logs
$route['jobs'] = "custom/jobs";
$route['info'] = "custom/info"; //PHP Info
$route['login'] = "custom/login"; //Bootcamp Operator login
$route['logout'] = "entities/logout"; //Logout from entites

//miner interface:
$route['entities/(:num)'] = "entities/entity_manage/$1";
$route['entities'] = "entities/entity_manage/" . $this->config->item('en_primary_id');
$route['intents/(:num)'] = "intents/intent_manage/$1";
$route['intents'] = "intents/intent_manage/" . $this->config->item('in_primary_id');

//Front facing landing page for intents:
$route['(:num)'] = "intents/intent_public/$1"; //Public intent browser

