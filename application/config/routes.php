<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['translate_uri_dashes']      = FALSE;

//SOURCE
$route['404_override']              = 'source/source_404';
$route['sign/(:num)']               = "source/sign/$1";
$route['sign']                      = "source/sign";
$route['source']                      = "source/source_home";
$route['source/(:num)']               = "source/source_coin/$1";

//READ
$route['default_controller']        = "read/read_coin";
$route['read']                      = "read/read_home";
$route['read/next']                 = "read/read_next";
$route['read/(:num)']               = "read/read_add/$1";
$route['ledger']                    = "read/read_ledger";
$route['(:num)']                    = "read/read_coin/$1";
$route['(:num)/next']               = "read/read_next/$1";

//Note
$route['note']                      = "note/note_home";
$route['note/create']               = "note/in_create";
$route['note/(:num)']               = "note/note_coin/$1";
