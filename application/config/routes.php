<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['translate_uri_dashes']      = FALSE;
$route['404_override']              = 'source/source_404';

$route['default_controller']        = "read/read_coin";

$route['(:num)']                    = "read/read_coin/$1";
$route['source/(:num)']             = "source/source_coin/$1";
$route['tree/(:num)']               = "tree/tree_coin/$1";
