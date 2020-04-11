<?php

//This page is loaded after user successfully authenticates via Auth0
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


require 'vendor/autoload.php';

$auth0 = new Auth0\SDK\Auth0([
    'domain' => 'mench.auth0.com',
    'client_id' => 'ExW9bFiMnJX21vogqcbKCLn08djYWnsi',
    'client_secret' => $this->config->item('cred_auth0_client_secret'),
    'redirect_uri' => 'https://mench.com/source/auth0',
    'scope' => 'openid profile email',
]);

if($auth0){
    //Login validated:
    $userInfo = $auth0->getUser();
}



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


/*
if($userInfo){

    //We have their email already?
    $user_emails = $this->READ_model->ln_fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        'ln_content' => $userInfo['email'],
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
        'ln_parent_source_id' => 3288, //Mench Email
    ), array('en_child'));


    if(!count($user_emails)){

        //Create new user:
        $user_emails[0] = $this->SOURCE_model->en_verify_create($userInfo['name'], 0, 6181, random_source_avatar());

        //Link to Auth0 Key Source

        $this->READ_model->ln_create(array(
            'ln_parent_source_id' => 4430, //Mench User
            'ln_type_source_id' => 4230, //Raw link
            'ln_creator_source_id' => $user_emails[0]['en_id'],
            'ln_child_source_id' => $user_emails[0]['en_id'],
        ));

        $this->READ_model->ln_create(array(
            'ln_type_source_id' => 4230, //Raw link
            'ln_parent_source_id' => 1278, //People
            'ln_creator_source_id' => $user_emails[0]['en_id'],
            'ln_child_source_id' => $user_emails[0]['en_id'],
        ));

        $this->READ_model->ln_create(array(
            'ln_type_source_id' => 4255, //Text link
            'ln_content' => $userInfo['email'],
            'ln_parent_source_id' => 3288, //Email
            'ln_creator_source_id' => $user_emails[0]['en_id'],
            'ln_child_source_id' => $user_emails[0]['en_id'],
        ));

    }

    //Activate Session:
    $this->SOURCE_model->en_activate_session($user_emails[0]);

}
*/

//Still Here? Go home:
header('Location: /');
die();