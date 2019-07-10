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
$route['login']                 = "user_app/user_login";
$route['logout']                = "user_app/logout";
$route['myaccount']             = "user_app/myaccount";
$route['actionplan']            = "user_app/actionplan/0";
$route['actionplan/(:num)']     = "user_app/actionplan/$1";

$route['dashboard']             = "miner_app/dashboard";