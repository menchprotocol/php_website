<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']          = FALSE;
$route['default_controller']            = "app/index"; //Redirects to default app
$route['404_override']                  = 'app/load'; //Page not found
$route['-(:num)']                       = "app/load/$1"; //App
$route['@(:num)']                       = "e/e_layout/$1"; //Source
$route['~(:num)@(:num)']                = "i/i_layout/$1/$2"; //Append Source (To be deprecated soon & merged into mass apply function)
$route['~(:num)']                       = "i/i_layout/$1"; //Ideate
$route['(:num)/(:num)/(:num)/(:any)']   = "x/x_layout/$1/$2/$3/$4"; //Discovery Started
$route['(:num)/(:num)/(:any)']          = "x/x_layout/0/$1/$2/$3"; //Discovery Started
$route['(:num)/(:num)']                 = "x/x_layout/$1/$2/0/0"; //Discovery Started
$route['(:num)']                        = "x/x_layout/0/$1/0/0"; //Discovery Preview