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
    'redirect_uri' => 'https://mench.com/e/auth0_callback',
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
print_r($userInfo);
die();
?>

<form id="accountCreation" action="/e/e_signin_create" method="post">
    <?php
    foreach(array(
        'sign_i__id' => '',
        'input_email' => '',
        'input_name' => '',
        'new_password' => '',
        'input_email' => '',
        'input_email' => '',

    ) as $key => $value){
        echo '<input type="hidden" name="'.$key.'" value="'.htmlentities($value).'">';
    }
    ?>
</form>
<script type="text/javascript">
    document.getElementById('myForm').submit();
</script>

<?php
if($userInfo){

    //We have their email already?
    $user_emails = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //Source Links
        'x__up' => 3288, //Mench Email
        'x__message' => $userInfo['email'],
    ), array('x__down'));


    if(!count($user_emails)){

        //Create new user:
        $user_emails[0] = $this->E_model->verify_create($userInfo['name'], 0, 6181, random_avatar());

        //Link to Auth0 Key Source
        $this->X_model->ln_create(array(
            'ln_parent_source_id' => 4430, //Mench User
            'ln_type_source_id' => 4230, //Raw link
            'ln_creator_source_id' => $user_emails[0]['en_id'],
            'ln_child_source_id' => $user_emails[0]['en_id'],
        ));

        $this->X_model->ln_create(array(
            'ln_type_source_id' => 4230, //Raw link
            'ln_parent_source_id' => 1278, //People
            'ln_creator_source_id' => $user_emails[0]['en_id'],
            'ln_child_source_id' => $user_emails[0]['en_id'],
        ));

        $this->X_model->ln_create(array(
            'ln_type_source_id' => 4255, //Text link
            'ln_content' => $userInfo['email'],
            'ln_parent_source_id' => 3288, //Email
            'ln_creator_source_id' => $user_emails[0]['en_id'],
            'ln_child_source_id' => $user_emails[0]['en_id'],
        ));

    }

    //Activate Session:
    $this->E_model->activate_session($user_emails[0], true);

}

//Still Here? Go home:
header('Location: /');
die();