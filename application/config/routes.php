<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//General:
$route['translate_uri_dashes']      = FALSE;
$route['404_override']              = 'play/page_not_found';
$route['default_controller']        = "play/default_redirect";

$route['play']                      = "play/play_overview";
$route['read']                      = "read/read_overview";
$route['blog']                      = "blog/blog_overview";

$route['players']                   = "players/play_overview";
$route['exchange']                  = "exchange/read_overview";
$route['ideas']                     = "blog/blog_overview";

$route['players/(:num)']            = "players/play_modify/$1";
$route['ideas/(:num)']              = "ideas/blog_modify/$1";
$route['exchange/(:num)']           = "exchange/read_blog/$1";


//DEPRECATE SOON:
$route['actionplan']                = "read/actionplan/0";
$route['actionplan/delete']         = "read/actionplan_delete";
$route['actionplan/delete/(:num)']  = "read/actionplan_delete/$1";
$route['actionplan/next']           = "read/actionplan/next";
$route['actionplan/(:num)']         = "read/actionplan/$1";