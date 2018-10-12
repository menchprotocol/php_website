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

//TODO Remove all following:
$route['console/(:num)/actionplan'] 		= "console/actionplan/$1";
$route['console/(:num)/settings'] 			= "console/settings/$1";
$route['console/(:num)/classes'] 			= "console/classes/$1";
$route['console/(:num)/raw'] 				= "console/raw/$1"; //For dev purposes
$route['console/(:num)'] 			        = "console/dashboard/$1";
$route['console'] 							= "console/bootcamps";
$route['login']						        = "front/login"; //Bootcamp Operator login
$route['logout']				            = "entities/logout"; //Logout from entites

//Trainer interface:
$route['entities/(:num)'] 			        = "entities/entity_manage/$1";
$route['entities'] 			                = "entities/entity_manage";
$route['intents/(:num)'] 			        = "intents/intent_manage/$1";
$route['intents'] 			                = "intents/intent_manage"; //Published intents

//Front facing:
$route['(:num)']	                        = "intents/intent_public/$1"; //Public intent browser

