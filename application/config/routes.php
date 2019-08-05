<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//General:
$route['404_override']          = 'user_app/page_not_found';
$route['translate_uri_dashes']  = FALSE;
$route['default_controller']    = "intents";

//Miner Intents/Entities:
$route['entities/(:num)']       = "entities/en_miner_ui/$1";
$route['entities']              = "entities/en_miner_ui/0";
$route['intents/(:num)']        = "intents/in_miner_ui/$1";
$route['intents']               = "intents/in_miner_ui/0";

$route['(:num)_(:num)']         = "intents/in_public_ui/$2/$1"; //Public Intent Landing Page
$route['(:num)']                = "intents/in_public_ui/$1"; //Public Intent Landing Page
$route['start']                 = "intents/in_public_ui/10430"; //For companies to get started

//Users:
$route['signin']                = "user_app/signin";
$route['(:num)_(:num)/signin']  = "user_app/signin/$2/$1";
$route['(:num)/signin']         = "user_app/signin/$1";
$route['resetpassword/(:num)']  = "user_app/signin_reset_password/$1";
$route['magiclogin/(:num)']     = "user_app/signin_magiclogin/$1";

$route['signout']                = "user_app/signout";
$route['myaccount']             = "user_app/myaccount";
$route['actionplan']            = "user_app/actionplan/0";
$route['actionplan/(:num)']     = "user_app/actionplan/$1";

$route['dashboard']             = "miner_app/dashboard";