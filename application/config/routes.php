<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']          = FALSE;
$route['default_controller']            = "app/index"; //Redirects to default app
$route['404_override']                  = 'app/load'; //Page not found

//Custom Apps:
$route['stats'] = "app/load/33292";
$route['routes'] = "app/load/42006";
$route['cache'] = "app/load/4527";


//$route['-(:any)']                             = "app/load/$1";
$route['@(:alphanum)']                          = "e/e_layout/$1"; //Source
$route['~(:alphanum)@(:alphanum)']              = "i/i_layout/$1/$2"; //Append Source (To be deprecated soon & merged into mass apply function)
$route['~(:alphanum)']                          = "i/i_layout/$1"; //Ideate
$route['(:alphanum)/(:alphanum)/@(:alphanum)']  = "x/x_layout/$1/$2/$3"; //Discovery Started
$route['(:alphanum)/(:alphanum)']               = "x/x_layout/$1/$2/0"; //Discovery Started
$route['(:segment)']                           = "x/x_layout/0/$1/0"; //Discovery Preview
//$route['(:any)']                                = "x/x_layout/0/$1/0"; //Discovery Preview
