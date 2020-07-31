<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']      = FALSE;

//Home
$route['default_controller']        = "x/index"; //Home page
$route['@']                         = "e/index"; //Source home

//COINS
$route['(:num)']                    = "x/x_coin/$1";
$route['@(:num)']                   = "e/e_coin/$1";
$route['~(:num)']                   = "i/i_coin/$1";

//OTHER
$route['ledger']                    = "x/x_list"; //Transactions
$route['404_override']              = 'e/e_404'; //Page not found, etc...