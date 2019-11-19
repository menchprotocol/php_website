<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//General:
$route['translate_uri_dashes']      = FALSE;
$route['404_override']              = 'players/page_not_found';
$route['default_controller']        = "players/default_redirect";

$route['players']                   = "players/play_overview";
$route['exchange']                  = "exchange/exchange_overview";
$route['ideas']                     = "ideas/ideas_overview";

$route['players/(:num)']            = "players/play_modify/$1";
$route['ideas/(:num)']              = "ideas/idea_modify/$1";
$route['exchange/(:num)']           = "exchange/read_idea/$1";


//DEPRECATE SOON:
$route['actionplan']                = "exchange/actionplan/0";
$route['actionplan/delete']         = "exchange/actionplan_delete";
$route['actionplan/delete/(:num)']  = "exchange/actionplan_delete/$1";
$route['actionplan/next']           = "exchange/actionplan/next";
$route['actionplan/(:num)']         = "exchange/actionplan/$1";