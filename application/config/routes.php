<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//General:
$route['translate_uri_dashes']      = FALSE;
$route['404_override']              = 'play/page_not_found';
$route['default_controller']        = "read/read_overview";
$route['read']                      = "read/actionplan";
$route['blog']                      = "blog/blog_overview";
$route['play']                      = "play/play_overview";
$route['signin/(:num)']             = "play/signin/$1";
$route['signin']                    = "play/signin";

$route['play/(:num)']               = "play/play_modify/$1";
$route['blog/(:num)']               = "blog/blog_modify/$1";
$route['(:num)/next']               = "read/next/$1";
$route['(:num)']                    = "read/read_blog/$1";


//DEPRECATE SOON:
$route['actionplan']                = "read/actionplan/0";
$route['actionplan/delete']         = "read/actionplan_delete";
$route['actionplan/delete/(:num)']  = "read/actionplan_delete/$1";
$route['actionplan/next']           = "read/actionplan/next";
$route['actionplan/(:num)']         = "read/actionplan/$1";