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


$route['default_controller']        = "read/read_coin"; //Home pate
$route['(:num)']                    = "read/read_coin/$1"; //Read Idea
$route['x']                         = "read/interactions"; //Read Interactions history
$route['404_override']              = 'source/source_404'; //Page not found, etc...
$route['@(:num)']                   = "source/source_coin/$1"; //Specific source
$route['@']                         = "source/index"; //Source Home
$route['~']                         = "idea/index"; //Idea home
$route['~(:num)']                   = "idea/idea_coin/$1"; //Modify idea