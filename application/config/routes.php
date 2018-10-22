<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['404_override'] = 'front/error';
$route['translate_uri_dashes'] = FALSE;

/* ******************************
 * Front
 ****************************** */

$route['default_controller'] 		= "front"; // Home page
$route['train'] 				    = "front/train";
$route['terms'] 					= "front/terms";
$route['ses'] 						= "front/ses"; //Raw session logs
$route['info'] 						= "front/info"; //PHP Info
$route['login']					    = "front/login"; //Bootcamp Operator login
$route['logout']				    = "entities/logout"; //Logout from entites

//Trainer interface:
$route['entities/(:num)'] 			        = "entities/entity_manage/$1";
$route['entities'] 			                = "entities/entity_manage/1326"; //Default
$route['intents/(:num)'] 			        = "intents/intent_manage/$1";
$route['intents'] 			                = "intents/intent_manage/6623"; //Default

//Front facing landing page for intents:
$route['(:num)']	                        = "intents/intent_public/$1"; //Public intent browser

