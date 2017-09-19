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
$route['contact'] 					= "front/contact";
$route['features'] 					= "front/features";
$route['pricing'] 					= "front/pricing";
$route['aboutus'] 					= "front/aboutus";
$route['ses'] 						= "front/ses"; //Raw session logs
$route['login']						= "front/login";

//Challenges PUBLIC:
$route['challenge/(:any)/join']		= "front/challenge_join/$1"; //Checkout
$route['challenge/(:any)'] 			= "front/challenge_landing_page/$1"; //Landing Page
$route['challenge'] 				= "front/challenge_browse";



/* ******************************
 * Marketplace ADMIN-ONLY
 ****************************** */

//Admin Guides:
$route['guides/status_bible'] 						= "marketplace/status_bible";
$route['guides/showdown_markup'] 					= "marketplace/showdown_markup";

//Users & Authentication:
$route['login_process'] 							= "marketplace/login_process";
$route['logout'] 									= "marketplace/logout";
$route['user/(:any)/edit'] 							= "marketplace/user_edit/$1"; //Admin Only
$route['user/(:any)'] 								= "marketplace/user_view/$1"; //PUBLIC & HYBRID

//Runs:
$route['marketplace/(:num)/run/(:num)'] 			= "marketplace/run_dashboard/$1/$2";
$route['marketplace/(:num)/run/(:num)/leaderboard']	= "marketplace/run_leaderboard/$1/$2";
$route['marketplace/(:num)/run/(:num)/activity'] 	= "marketplace/run_activity/$1/$2";
$route['marketplace/(:num)/run/(:num)/settings'] 	= "marketplace/run_settings/$1/$2";
$route['marketplace/(:num)/run/new'] 				= "marketplace/run_settings/$1";

//Challenges:
$route['marketplace/(:num)/(:num)'] 				= "marketplace/challenge_framework/$1/$2";
$route['marketplace/(:num)'] 						= "marketplace/challenge_framework/$1";
$route['marketplace/new'] 							= "marketplace/challenge_create";
$route['marketplace'] 								= "marketplace/challenge_marketplace";

