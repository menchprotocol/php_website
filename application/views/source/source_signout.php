<?php
require 'vendor/autoload.php';
$auth0 = new Auth0\SDK\Auth0([
    'domain' => 'mench.auth0.com',
    'client_id' => 'ExW9bFiMnJX21vogqcbKCLn08djYWnsi',
    'client_secret' => $this->config->item('cred_auth0_client_secret'),
    'redirect_uri' => 'https://mench.com/source/auth0',
    'scope' => 'openid profile email',
]);

$return_to = 'https://mench.com';
$auth0->logout();
$logout_url = sprintf('http://%s/v2/logout?client_id=%s&returnTo=%s', 'mench.auth0.com', 'ExW9bFiMnJX21vogqcbKCLn08djYWnsi', $return_to);
$this->session->sess_destroy();

header('Location: ' . $logout_url);
die();

?>