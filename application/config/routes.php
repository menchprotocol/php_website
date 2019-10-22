<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//General:
$route['404_override']              = 'play/page_not_found';
$route['translate_uri_dashes']      = FALSE;
$route['default_controller']        = "play/launching_soon";


$route['play/(:num)']               = "play/play_modify/$1"; //Set to: play_view
$route['blog/(:num)']               = "blog/blog_modify/$1";
$route['read/(:num)']               = "read/blog/$1";
$route['(:num)']                    = "read/blog/$1"; //OLD URL STRUCTURE - DEPRECATE SOON...

$route['sign']                      = "play/sign";
$route['play']                      = "play/overview";
$route['read']                      = "read/overview";
$route['blog']                      = "blog/overview";
$route['blog/demo']                 = "blog/demo";

$route['read/next']                 = "read/read_history_overview";
$route['read/history']              = "read/read_history_overview";
$route['read/history/(:num)']       = "read/read_history_item";


$route['completion_rates']          = "blog/in_completion_rates";

//Users:
$route['(:num)_(:num)/sign']      = "play/sign/$2/$1";
$route['(:num)/sign']             = "play/sign/$1";
$route['resetpassword/(:num)']      = "play/sign_reset_password_ui/$1";
$route['magiclogin/(:num)']         = "play/singin_magic_link_login/$1";

$route['signout']                   = "play/signout";
$route['actionplan']                = "read/actionplan/0";
$route['actionplan/delete']         = "read/actionplan_delete";
$route['actionplan/delete/(:num)']  = "read/actionplan_delete/$1";
$route['actionplan/next']           = "read/actionplan/next";
$route['actionplan/(:num)']         = "read/actionplan/$1";
