<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['404_override'] = 'front/error';
$route['translate_uri_dashes'] = FALSE;

/* ******************************
 * Front
 ****************************** */

$route['default_controller'] 		= "front"; // index() Landing page
$route['terms'] 					= "front/terms";
$route['launch'] 				    = "front/launch";
$route['contact'] 					= "front/contact";
$route['faq'] 					    = "front/faq"; //TODO Not in use?
$route['ses'] 						= "front/ses"; //Raw session logs
$route['info'] 						= "front/info"; //PHP Info
$route['login']						= "front/login"; //Bootcamp Operator login
$route['logout']				    = "entities/logout"; //Logout from entites


/* ******************************
 * Student Semi-Private URLs
 ****************************** */
$route['application_status'] 	            = "my/applications"; //Deprecated on 2017-10-18, give it 3-4 months before removing
$route['ref/(:num)'] 	                    = "my/load_url/$1"; //For URL loading and embed video playbacks
$route['webview_video/(:num)'] 	            = "my/webview_video/$1";


/* ******************************
 * Console for Operators
 ****************************** */

$route['console/(:num)/actionplan'] 			       = "console/actionplan/$1";
$route['console/(:num)/settings'] 				       = "console/settings/$1";
$route['console/(:num)/classes/(:num)/scheduler']      = "console/scheduler/$1/$2"; //iFrame view
$route['console/(:num)/classes/(:num)'] 		       = "console/load_class/$1/$2";
$route['console/(:num)/classes'] 				       = "console/classes/$1";
$route['console/(:num)/raw'] 				           = "console/raw/$1"; //For dev purposes
$route['console/(:num)'] 			                   = "console/dashboard/$1";
$route['console'] 								       = "console/bootcamps";

$route['entities/(:num)/modify'] 			           = "entities/entity_edit/$1";
$route['entities/(:num)'] 			                   = "entities/entity_browse/$1";
$route['entities'] 			                           = "entities/entity_browse";

//$route['intents/(:num)']	                            = "front/index/$1"; //Landing Page with specific c_id as Parent Focus

$route['tasks/(:num)'] 			                       = "intents/intents_list/$1";
$route['tasks'] 			                           = "intents/intents_list/0";

//Affiliate Links:
//$route['a/(:num)/(:num)/enroll'] 	                   = "front/affiliate_click/$1/$2/1"; //Start of application funnel for Email, first & last name
//$route['a/(:num)/(:num)'] 	                           = "front/affiliate_click/$1/$2/0"; //Start of application funnel for Email, first & last name


//Checkout process:
$route['(:any)/enroll'] 	                = "my/checkout_start/$1"; //Start application
$route['(:any)/assessment/(:num)'] 	            = "my/update_assessment/$1/$2"; //If required, take the assessment. checkout URL would be emailed to you
$route['(:any)/assessment'] 	                    = "my/checkout_assessment/$1"; //If required, take the assessment. checkout URL would be emailed to you
$route['(:any)/checkout'] 	                = "my/checkout_complete/$1"; //Review Prereqs. & choose support package
$route['(:any)/pay'] 	                    = "my/checkout_pay/$1"; //Pay for coaching
$route['(:any)']	                        = "front/landing_page/$1"; //Landing page

