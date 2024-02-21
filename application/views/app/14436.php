<?php

$client_id = website_setting(14881);
$client_secret = website_setting(14882);

use Auth0\SDK\Auth0;

if($client_id && $client_secret){

    //This page is loaded after member successfully authenticates via Auth0
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    require 'vendor/autoload.php';

    $auth0 = new Auth0([
        'domain' => 'mench.auth0.com',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => 'https://'.get_server('SERVER_NAME').view_app_link(14564),
        'scope' => 'openid profile email',
    ]);

    $auth0->login();

} else {

    js_php_redirect(view_memory(42903,14565), 13);

}



