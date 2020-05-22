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


//READ
$route['default_controller']        = "read/read_coin"; //Home pate
$route['(:num)']                    = "read/read_coin/$1"; //Read Idea
$route['r']                         = "read/index"; //Read Home
$route['s']                         = "read/saved"; //Read Saved Ideas
$route['j(:num)']                   = "read/start_reading/$1"; //Join & start on Idea
$route['x']                         = "read/interactions"; //Read Interactions history
$route['404_override']              = 'source/source_404'; //Page not found, etc...


//SOURCE
$route['@s(:num)']                  = "source/sign/$1"; //Sign & go to idea
$route['@s']                        = "source/sign"; //Signin
$route['@o']                        = "source/signout"; //Signout
$route['@p(:num)']                  = "source/plugin/$1"; //Specific Plugin
$route['@p']                        = "source/plugin"; //Plugin Home
$route['@(:num)']                   = "source/source_coin/$1"; //Specific source
$route['@']                         = "source/index"; //Source Home


//IDEATE
$route['i']                         = "idea/index"; //Idea Home page
$route['g(:num)']                   = "idea/go/$1"; //Smart logic to redirect to either IDEATE or READ mode
$route['i(:num)']                   = "idea/idea_coin/$1"; //Load idea in ideate mode