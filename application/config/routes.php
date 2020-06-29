<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['translate_uri_dashes']      = FALSE;

/*
 *
 * The main routing logic of Mench
 * Changes below must also reflect
 * within function current_mench()
 *
 * */

//Home
$route['default_controller']        = "discover/index"; //Home page
$route['~']                         = "map/index"; //Idea home

//COINS
$route['(:num)']                    = "discover/x_coin/$1";
$route['@(:num)']                   = "source/e_coin/$1";
$route['~(:num)']                   = "map/i_coin/$1";

//OTHER
$route['x']                         = "discover/x_list"; //Interactions
$route['404_override']              = 'source/e_404'; //Page not found, etc...