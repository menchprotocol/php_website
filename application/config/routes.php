<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']      = FALSE;
$route['default_controller']        = "app/index";          //Redirects to default app
$route['404_override']              = 'app/load';           //Page not found
$route['-(:num)']                   = "app/load/$1";        //App
$route['@(:num)']                   = "e/e_layout/$1";      //Source
$route['~(:num)']                   = "i/i_layout/$1";      //Summarize
$route['(:num)']                    = "x/x_layout/0/$1";    //Discovery Preview
$route['(:num)/(:num)']             = "x/x_layout/$1/$2";   //Discovery Started