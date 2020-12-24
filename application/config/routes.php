<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']      = FALSE;
$route['default_controller']        = "app/home";           //Home
$route['404_override']              = 'app/error';          //Page not found
$route['app/(:num)']                = "app/load/$1";        //Load App
$route['@(:num)']                   = "e/e_layout/$1";      //Source
$route['~(:num)']                   = "i/i_layout/$1";      //Publish Idea
$route['(:num)']                    = "x/x_layout/0/$1";    //Discover Idea
$route['(:num)/(:num)']             = "x/x_layout/$1/$2";   //My Discoveries