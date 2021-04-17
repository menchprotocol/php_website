<?php


$client_id = get_domain_setting(14881);
$client_secret = get_domain_setting(14882);
$server_name = get_server('SERVER_NAME');

use Auth0\SDK\Auth0;

//Destroys Session
session_delete();


if($client_id && $client_secret && $server_name){



    /*

    echo '<div class="center-info">';
    echo '<div class="text-center platform-large">'.get_domain('m__cover').'</div>';
    echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
    echo '</div>';

    js_redirect('/', 1597);
    */

    $auth0 = new Auth0([
        'domain' => 'mench.auth0.com',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => 'https://'.$server_name.'/-14564',
        'scope' => 'openid profile email',
    ]);

    $auth0->logout();

    header('Location: ' . sprintf('http://%s/v2/logout?client_id=%s&returnTo=%s', 'mench.auth0.com', $client_id, 'https://'.$server_name));


} else {

    js_redirect('/', 13);

}










?>