<?php
require 'vendor/autoload.php';
use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => 'mench.auth0.com',
    'client_id' => 'ExW9bFiMnJX21vogqcbKCLn08djYWnsi',
    'client_secret' => $this->config->item('cred_auth0_client_secret'),
    'redirect_uri' => 'https://mench.com/source/auth0',
    'scope' => 'openid profile email',
]);
$auth0->login();
?>