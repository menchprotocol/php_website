<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* ******************************
 * Front
 ****************************** */

$route['default_controller'] 		= "front"; // index() Landing page
$route['terms'] 					= "front/terms";
$route['start'] 					= "front/instructors"; //TODO remove later
$route['launch'] 					= "front/instructors";
$route['contact'] 					= "front/contact";
$route['faq'] 					    = "front/faq"; //TODO Not in use?
$route['ses'] 						= "front/ses"; //Raw session logs
$route['login']						= "front/login"; //Bootcamp Operator login

/* ******************************
 * Student Semi-Private URLs
 ****************************** */
$route['application_status'] 	            = "my/applications"; //Deprecated on 2017-10-18, give it 3-4 months before removing
$route['ref/(:num)'] 	                    = "my/load_url/$1"; //For URL loading and embed video playbacks


/* ******************************
 * Console for Operators
 ****************************** */

//Admin Guides:
$route['console/help/status_bible'] 			       = "console/status_bible";

$route['console/account'] 						       = "console/account"; //Instructor account
$route['console/(:num)/actionplan'] 			       = "console/actionplan/$1";
$route['console/(:num)/students'] 				       = "console/students/$1";
$route['console/(:num)/team'] 				           = "console/team/$1";
$route['console/(:num)/classes/(:num)/scheduler']      = "console/scheduler/$1/$2"; //iFrame view
$route['console/(:num)/classes/(:num)'] 		       = "console/load_class/$1/$2";
$route['console/(:num)/classes'] 				       = "console/all_classes/$1";
$route['console/(:num)/raw'] 				           = "console/raw/$1"; //For dev purposes
$route['console/(:num)'] 			                   = "console/dashboard/$1";
$route['console'] 								       = "console/all_bootcamps";

//Affiliate Links:
$route['a/(:num)/(:num)/apply'] 	                   = "front/affiliate_click/$1/$2/1"; //Start of application funnel for Email, first & last name
$route['a/(:num)/(:num)'] 	                           = "front/affiliate_click/$1/$2/0"; //Start of application funnel for Email, first & last name


//Three steps of the signup process:
$route['bootcamps'] 				        = "front/bootcamps_browse"; //Browse page
$route['(:any)/apply/(:num)'] 	            = "front/bootcamp_apply/$1/$2"; //Start of application funnel for Email, first & last name
$route['(:any)/apply'] 	                    = "front/bootcamp_apply/$1"; //Start of application funnel for Email, first & last name
$route['(:any)/(:num)']	                    = "front/bootcamp_load/$1/$2"; //Load specific class in the bootcamp
$route['(:any)']	                        = "front/bootcamp_load/$1"; //Load specific bootcamp

