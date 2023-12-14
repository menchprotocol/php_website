<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']          = FALSE;
$route['default_controller']            = "app/index"; //Redirects to default app
$route['404_override']                  = 'app/load'; //Page not found

//Custom Apps:
$route['stats'] = "app/load/33292";
$route['routes'] = "app/load/42006";
//$route['stats'] = "app/load/".$app_id;

//$route['-(:any)']                       = "app/load/$1";
$route['@(:any)']                       = "e/e_layout/$1"; //Source
$route['~(:any)@(:any)']                = "i/i_layout/$1/$2"; //Append Source (To be deprecated soon & merged into mass apply function)
$route['~(:any)']                       = "i/i_layout/$1"; //Ideate
$route['(:any)/(:any)/@(:any)']         = "x/x_layout/$1/$2/$3"; //Discovery Started
$route['(:any)/(:any)']                 = "x/x_layout/$1/$2/0"; //Discovery Started
$route['(:any)']                        = "x/x_layout/0/$1/0"; //Discovery Preview
