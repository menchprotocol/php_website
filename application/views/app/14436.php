<?php

//This page is loaded after member successfully authenticates via Auth0
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require 'vendor/autoload.php';
use Auth0\SDK\Auth0;
$auth0 = new Auth0([
    'domain' => 'mench.auth0.com',
    'client_id' => get_domain_setting(14881),
    'client_secret' => get_domain_setting(14882),
    'redirect_uri' => 'https://'.get_server('SERVER_NAME').'/-14564',
    'scope' => 'openid profile email',
]);

$auth0->login();