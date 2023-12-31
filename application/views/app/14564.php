<?php

if(isset($_GET['i__hashtag'])){

    //New account to be created:
    $member_result = $this->E_model->add_member(urldecode($_GET['name']), urldecode($_GET['email']), null, null, website_setting(0)); //, urldecode($_GET['image_url'])
    if(!$member_result['status']) {
        $this->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'auth0_callback() Failed to create new member: '.$member_result['message'],
        ));
        echo 'ERROR Creating New Account! Admin is notified...';
    } else {
        js_php_redirect(new_member_redirect($member_result['e']['e__id'], $_GET['i__hashtag']), 13);
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
        $member_emails = $this->X_model->fetch(array(
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
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
            'x__creator' => ( count($member_emails) ? $member_emails[0]['e__id'] : 0 ),
            'x__up' => $signin_method,
            'x__metadata' => array(
                'auth0_getUser' => $userInfo,
            ),
        ));

        if(count($member_emails)){

            //Activate Session:
            $this->E_model->activate_session($member_emails[0], true);
            js_php_redirect(( $login_i__hashtag ? '/x/x_start/'.$login_i__hashtag : ( $redirect_url ? $redirect_url : home_url() )), 13);

        } else {

            js_php_redirect(view_app_link(14564).'?i__hashtag='.$login_i__hashtag.'&name='.urlencode($userInfo['name']).'&email='.urlencode($userInfo['email']).'&image_url='.urlencode($userInfo['picture']).'&url='.urlencode($redirect_url), 13);

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

        js_php_redirect(( $login_i__hashtag ? '/'.$login_i__hashtag : ( $redirect_url ? $redirect_url : home_url() ) ), 13);

    }

}