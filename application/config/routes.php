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
$route['default_controller']        = "x/index"; //Home page
$route['~']                         = "i/index"; //Idea home
$route['@']                         = "e/index"; //Source home

//COINS
$route['(:num)']                    = "x/x_coin/$1";
$route['@(:num)']                   = "e/e_coin/$1";
$route['~(:num)']                   = "i/i_coin/$1";

//OTHER
$route['x']                         = "x/x_list"; //Interactions
$route['404_override']              = 'e/e_404'; //Page not found, etc...