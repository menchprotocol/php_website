<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']          = FALSE;
$route['default_controller']            = "app/index"; //Redirects to default app
$route['404_override']                  = 'app/load'; //Page not found

//Custom Apps:
$route['stats'] = "app/load/33292";
$route['routes'] = "app/load/42006";
$route['cache'] = "app/load/4527";

$route['@([a-zA-Z0-9]+)'] = "e/e_layout/$1"; //Source
$route['~([a-zA-Z0-9]+)'] = "i/i_layout/$1"; //Ideate
$route['([a-zA-Z0-9]+)/([a-zA-Z0-9]+)'] = "x/x_layout/$1/$2"; //Discovery Sequence
$route['([a-zA-Z0-9]+)'] = "x/x_layout/0/$1/0"; //Discovery Single
