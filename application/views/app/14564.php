<?php

//This page is loaded after member successfully authenticates via Auth0
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


require 'vendor/autoload.php';
$auth0 = new Auth0\SDK\Auth0([
    'domain' => 'mench.auth0.com',
    'client_id' => get_domain_setting(14881),
    'client_secret' => get_domain_setting(14882),
    'redirect_uri' => 'https://'.get_server('SERVER_NAME').'/-14564',
    'scope' => 'openid profile email',
]);


/*
 * Nice, they are logged in via Auth0,
 * so we can either find them or
 * create a new source for them.
 *
 * Example Array from Auth0:
 *
    [sub] => google-oauth2|113404464005949570684
    [given_name] => Shervin
    [family_name] => Enayati
    [nickname] => shervin
    [name] => Shervin Enayati
    [picture] => https://lh3.googleusercontent.com/a-/AOh14GgjN8FLUHXttd_f6mweyBimBIs3AO4aTeQjL-wu7g
    [locale] => en
    [updated_at] => 2020-04-09T05:47:24.031Z
    [email] => shervin@mench.com
    [email_verified] => 1
 *
 * */

$userInfo = $auth0->getUser();
$sign_i__id = intval($this->session->userdata('login_i__id'));
$redirect_url = $this->session->userdata('redirect_url');


if($userInfo && isset($userInfo['email'])){

    //We have their email already?
    $member_emails = $this->X_model->fetch(array(
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //Source Links
        'x__up' => 3288, //Email
        'x__message' => $userInfo['email'],
    ), array('x__down'));

    //Can we determine the type?
    $signin_method = 0;
    foreach($this->config->item('e___14436') as $e__id => $m) {
        if(substr_count(strtolower($userInfo['sub']), strtolower($m['m__title']))){
            $signin_method = $e__id;
            break;
        }
    }

    $this->X_model->create(array(
        'x__type' => 14436, //Social Sign in
        'x__source' => ( count($member_emails) ? $member_emails[0]['e__id'] : 0 ),
        'x__up' => $signin_method,
        'x__metadata' => array(
            'auth0_getUser' => $userInfo,
        ),
    ));

    if(count($member_emails)){

        //Activate Session:
        $this->E_model->activate_session($member_emails[0], true);
        header('Location: ' . ($sign_i__id > 0 ? '/x/x_start/'.$sign_i__id : ( $redirect_url ? $redirect_url : home_url() )));

    } else {

        header('Location: /app/auth0_create/'.$sign_i__id.'?name='.urlencode($userInfo['name']).'&email='.urlencode($userInfo['email']).'&image_url='.urlencode($userInfo['picture']).'&url='.urlencode($redirect_url));

    }


} else {

    //Log this error:
    $this->X_model->create(array(
        'x__type' => 4246, //Platform Bug Reports
        'x__message' => 'APP @14564 Failed to fetch data from server',
        'x__metadata' => array(
            'auth0_getUser' => $userInfo,
        ),
    ));
    header('Location: ' . ( $sign_i__id ? '/'.$sign_i__id : ( $redirect_url ? $redirect_url : home_url() ) ));

}