<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']      = FALSE;

//Home
$route['default_controller']        = "x/index"; //Home page
$route['browse']                    = "x/browse";
$route['browse/(:num)']             = "x/browse/$1";
$route['@']                         = "e/index"; //Source home
$route['signin']                    = "e/signin"; //Sign
$route['signin/(:num)']             = "e/signin/$1"; //Sign
$route['account']                   = "e/e_account";
$route['terms']                     = "x/terms";

//COINS
$route['(:num)']                    = "x/x_layout/$1";
$route['@(:num)']                   = "e/e_layout/$1";
$route['~(:num)']                   = "i/i_layout/$1";

//OTHER
$route['app']                       = "e/app";
$route['app/(:num)']                = "e/app/$1";
$route['ledger']                    = "x/x_list"; //Transactions
$route['404_override']              = 'e/e_404'; //Page not found, etc...