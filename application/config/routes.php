<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']      = FALSE;
$route['default_controller']        = "app/index";          //Redirects to default app
$route['404_override']              = 'app/load';           //Page not found
$route['-(:num)']                   = "app/load/$1";        //App
$route['@(:num)']                   = "e/e_layout/$1";      //Source
$route['~(:num)']                   = "i/i_layout/$1";      //Summarize
$route['(:num)-(:num)-(:num)']      = "app/load/29393?i__id=$1&e__id=$2&member_id=$3";   //Add Source & Go to Idea
$route['(:num)/(:num)']             = "x/x_layout/$1/$2";   //Discovery Started
$route['(:num)']                    = "x/x_layout/0/$1";    //Discovery Preview
