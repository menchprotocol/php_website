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

$route['default_controller'] 	= "front"; // index() Landing page
$route['terms'] 				= "front/terms";
$route['contact'] 				= "front/contact";
$route['features'] 				= "front/features";
$route['pricing'] 				= "front/pricing";
$route['aboutus'] 				= "front/aboutus";
$route['ses'] 					= "front/ses"; //Raw session logs


/* ******************************
 * Dashboard
 ****************************** */

//Users & Authentication:
$route['user/login'] 								= "dashboard/login";
$route['user/logout'] 								= "dashboard/logout";
$route['user/missing_access'] 						= "dashboard/missing_access";
$route['user/thankyou'] 							= "dashboard/thankyou"; //Message only
$route['user/(:any)/edit'] 							= "dashboard/user_edit/$1"; //Admin Only
$route['user/(:any)'] 								= "dashboard/user_view/$1"; //PUBLIC & HYBRID

//Challenges ADMIN-ONLY:
$route['dashboard/(:any)/settings'] 				= "dashboard/challenge_settings/$1";
$route['dashboard/(:any)/library/(:num)']			= "dashboard/challenge_library/$1/$2"; //Oh mama!
$route['dashboard/(:any)/library']					= "dashboard/challenge_library/$1"; //Redirect

//Challenges PUBLIC:
$route['challenge/(:any)/join'] 					= "dashboard/challenge_join/$1"; //$$$
$route['challenge/(:any)'] 							= "dashboard/challenge_landing_page/$1"; //Landing Page

//Challenges HYBRID:
$route['dashboard'] 								= "dashboard/challenge_list/admin"; //HYBRID
$route['challenge'] 								= "dashboard/challenge_list/user"; //HYBRID

//Runs ADMIN-ONLY:
$route['dashboard/(:any)/run'] 						= "dashboard/run_list/$1";
$route['dashboard/(:any)/run/(:num)'] 				= "dashboard/run_dashboard/$1/$2";
$route['dashboard/(:any)/run/(:num)/leaderboard']	= "dashboard/run_leaderboard/$1/$2";
$route['dashboard/(:any)/run/(:num)/stream'] 		= "dashboard/run_stream/$1/$2";
$route['dashboard/(:any)/run/(:num)/settings'] 		= "dashboard/run_settings/$1/$2";






