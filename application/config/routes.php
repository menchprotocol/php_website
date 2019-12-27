<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['translate_uri_dashes']      = FALSE;

//PLAY
$route['404_override']              = 'play/play_404';
$route['signin/(:num)']             = "play/signin/$1";
$route['signin']                    = "play/signin";
$route['play']                      = "play/play_leaderboard";
$route['play/(:num)']               = "play/play_modify/$1";

//READ
$route['default_controller']        = "read/read_home";
$route['read']                      = "read/read_mylist";
$route['read/next']                 = "read/read_next";
$route['read/(:num)']               = "read/read_add/$1";
$route['read/history']              = "read/read_history";
$route['(:num)']                    = "read/read_echo/$1";
$route['(:num)/next']               = "read/read_next/$1";

//BLOG
$route['blog']                      = "blog/blog_list";
$route['blog/create']               = "blog/blog_create";
$route['blog/stats']                = "blog/blog_stats";
$route['blog/(:num)']               = "blog/blog_modify/$1";
