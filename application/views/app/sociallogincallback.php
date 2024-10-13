<?php

$has_idea = isset($_GET['i__hashtag']) && $_GET['i__hashtag'];

if($has_idea && isset($_GET['name']) && isset($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){

    //New account to be created:
    $player_result = $this->Sources->add_member(urldecode($_GET['name']), urldecode($_GET['email']), null, null, website_setting(0));
    if(!$player_result['status']) {
        $this->Ledger->write(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'auth0_callback() Failed to create new member: '.$player_result['message'],
        ));
        echo 'ERROR Creating New Account! Admin is notified';
    } else {
        js_php_redirect(new_player_redirect($player_result['e']['e__id'], $_GET['i__hashtag']), 13);
    }

} else {


    //This page is loaded after member successfully authenticates via Auth0
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");


    require 'vendor/autoload.php';
    $auth0 = new Auth0\SDK\Auth0([
        'domain' => 'mench.auth0.com',
        'client_id' => website_setting(14881),
        'client_secret' => website_setting(14882),
        'redirect_uri' => 'https://'.get_server('SERVER_NAME').view_app_link(14564),
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
    $login_i__hashtag = $this->session->userdata('login_i__hashtag');
    $redirect_url = $this->session->userdata('redirect_url');
    $sign_is = array();


    if($userInfo && isset($userInfo['email'])){

        //We have their email already?
        $player_emails = $this->Ledger->read(array(
                    'x__type IN (' . njoin(32292) . ')' => null, //SOURCE LINKS
            'x__following' => 3288, //Email
            'x__message' => $userInfo['email'],
        ), array('x__follower'));

        //Can we determine the type?
        $signin_method = 0;
        foreach($this->config->item('e___14436') as $e__id => $m) {
            if(substr_count(strtolower($userInfo['sub']), strtolower($m['m__title']))){
                $signin_method = $e__id;
                break;
            }
        }

        $this->Ledger->write(array(
            'x__type' => 14436, //Social Sign in
            'x__player' => ( count($player_emails) ? $player_emails[0]['e__id'] : 0 ),
            'x__following' => $signin_method,
            'x__metadata' => array(
                'auth0_getUser' => $userInfo,
            ),
        ));

        if($has_idea){
            $redirect_url = new_player_redirect($player_emails[0]['e__id'], $_GET['i__hashtag']);
        } else {
            $redirect_url = ( $login_i__hashtag ? $login_i__hashtag.'/'.view_memory(6404,4235) : ( $redirect_url ? $redirect_url : home_url() ));
        }

        if(count($player_emails)){

            //Activate Session:
            $this->Sources->activate_session($player_emails[0], true);
            js_php_redirect($redirect_url, 13);

        } else {

            js_php_redirect(view_app_link(14564).view_memory(42903,33286).$login_i__hashtag.'&name='.urlencode($userInfo['name']).'&email='.urlencode($userInfo['email']).'&image_url='.urlencode($userInfo['picture']).'&url='.urlencode($redirect_url), 13);

        }

    } else {

        if(strlen($userInfo)) {
            //Log this error:
            $this->Ledger->write(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'APP @14564 Failed to fetch data from server',
                'x__metadata' => array(
                    'auth0_getUser' => $userInfo,
                ),
            ));
        }

        js_php_redirect(( $login_i__hashtag ? view_memory(42903,33286).$login_i__hashtag : ( $redirect_url ? $redirect_url : home_url() ) ), 13);

    }

}