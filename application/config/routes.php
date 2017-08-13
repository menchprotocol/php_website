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

//Standalone Pages:
$route['default_controller'] 				= "challenges"; //Home page / main landing page
$route['terms'] 							= "challenges/terms"; //Terms page
$route['contact'] 							= "challenges/contact"; //Contact Us page

//Challenges:
$route['launch'] 							= "challenges/launch"; //Seller landing page & value prop pitch
$route['dashboard'] 						= "challenges/dashboard"; //Sellers: challenges, stats, what needs att.
$route['challenges'] 						= "challenges/public_list"; //Both: List of challenges with filters
$route['challenges/start'] 					= "challenges/modify/overview"; //Sellers: Create new challenge
$route['challenges/(:any)/modify/(:any)'] 	= "challenges/modify/$2/$1"; //Sellers: Challenge Creation Wizard w/ 5-7 Steps
$route['challenges/(:any)/activity'] 		= "challenges/activity/$1"; //Sellers: See activity stream of a challenge
$route['challenges/(:any)'] 				= "challenges/view/$1"; //Both: View challenge, macro stats, leaderboard, file upload

//Users:
$route['account'] 							= "users/account"; //Both: Edit personal account
$route['login_auth'] 						= "users/login_auth"; //Both: Login via Facebook
$route['logout'] 							= "users/logout"; //Both: Logout
$route['users'] 							= "users/browse"; //Sellers: See list of their users & filter (Admins see all)
$route['ses'] 								= "users/ses";
$route['users/(:any)'] 						= "users/load/$1"; //Both: Load specific user and see their stats



