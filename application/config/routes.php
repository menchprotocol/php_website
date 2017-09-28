<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* ******************************
 * Front
 ****************************** */

$route['default_controller'] 		= "front"; // index() Landing page
$route['terms'] 					= "front/terms";
$route['start'] 					= "front/start_bootcamp";
$route['contact'] 					= "front/contact";
$route['faq'] 					    = "front/faq";
$route['ses'] 						= "front/ses"; //Raw session logs
$route['login']						= "front/login"; //Bootcamp Operator login

$route['bootcamps/(:any)/enroll'] 	= "front/bootcamp_enroll/$1";
$route['bootcamps/(:any)']	        = "front/bootcamp_load/$1";
$route['bootcamps'] 				= "front/bootcamps_browse";


/* ******************************
 * Console for Operators
 ****************************** */

//Admin Guides:
$route['console/help/status_bible'] 			= "console/status_bible";
$route['console/help/showdown_markup'] 			= "console/showdown_markup";

$route['console/account'] 						= "console/v_account";
$route['console/(:num)/content/(:num)'] 		= "console/v_content/$1/$2";
$route['console/(:num)/content'] 				= "console/v_content/$1";
$route['console/(:num)/community'] 				= "console/v_community/$1";
$route['console/(:num)/timeline'] 				= "console/v_timeline/$1";
$route['console/(:num)/cohorts/(:num)'] 		= "console/v_cohort/$1/$2";
$route['console/(:num)/cohorts'] 				= "console/v_all_cohorts/$1";
$route['console/(:num)'] 			            = "console/v_dashboard/$1";
$route['console'] 								= "console/v_all_bootcamps";


