<?php

$found_i = false;
$signed_i = false;

die('needs updating for name and links');

if(isset($_GET['i__hashtag'])){

    foreach($this->I_model->fetch(array(
        'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
        'i__type' => 32603, //Sign Agreement
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    )) as $i_sign){

        $found_i = true;

        if(isset($_POST) && count($_POST)){

            //Process Signature to make sure it's all ok:
            if (strlen($_POST['x_write'])<5 || !substr_count($_POST['x_write'] , ' ')) {
                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Enter Your First & Last Name</div>';
            } elseif (!isset($_POST['DigitalSignAgreement']) || !intval($_POST['DigitalSignAgreement'])) {
                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>You must agree to be legally bound by this document.</div>';
            } elseif (!filter_var($_POST['x_email'], FILTER_VALIDATE_EMAIL)) {
                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Enter a valid Email Address</div>';
            } elseif (strlen(intval($_POST['x_phone']))<10) {
                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Enter a valid Phone Number</div>';
            } else {

                //Input validated, process signature:
                $signed_i = true;
                $phone = intval($_POST['x_phone']);
                $email = trim($_POST['x_email']);

                //See if we can find this user with their email:
                $map_users = $this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__following' => 3288, //Email
                    'LOWER(x__message)' => $email.'',
                ), array('x__follower'));
                if(!count($map_users)){
                    $map_users = $this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__following' => 4783, //Phone
                        'x__message' => $phone.'',
                    ), array('x__follower'));
                }

                //Still missing user?
                if(!count($map_users)){
                    //TODO will get full notification and login! needs to be adjusted...

                    //$player_result = $this->E_model->add_member($_POST['x_write'], $email, $phone, null, 0);
                    if($player_result['status']) {
                        $map_users[0] = $player_result['e'];
                    }
                }

                foreach($map_users as $map_user){
                    //Sign agreement:
                    $this->X_model->mark_complete(33614, $map_user['e__id'], $i_sign['i__id'], $i_sign, array(
                        'x__message' => $_POST['x_write'],
                    ));

                    //Log transaction:
                    $this->X_model->create(array(
                        'x__creator' => $map_user['e__id'],
                        'x__type' => 32603,
                        'x__previous' => $i_sign['i__id'],
                    ));
                }

                echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle zq6255"></i></span> Waver signed for "'.$_POST['x_write'].'".<br />Show your ID at the door to enter'.( isset($map_user['e__id']) ? '<a href="'.view_memory(42903,42902).$map_user['e__handle'].'" style="text-decoration: none;">.</a>' : '.' ).'</div>';

            }

        }


        if(!$signed_i){

            //Allow users to sign:
            echo view_i__links($i_sign);

            echo '<form method="POST" action="">';
            echo view_sign($i_sign, ( isset($_POST['x_write']) ? $_POST['x_write'] : '' ));
            echo '<br /><h4>Email Address:</h4>';
            echo '<input type="email" class="border greybg main__title itemsetting" style="width:289px !important; margin:0 5px;" value="'.( isset($_POST['x_email']) ? $_POST['x_email'] : '' ).'" placeholder="" name="x_email" />';
            echo '<br /><h4>Phone Number:</h4>';
            echo '<input type="text" class="border greybg main__title itemsetting" style="width:289px !important; margin:0 5px;" value="'.( isset($_POST['x_phone']) ? $_POST['x_phone'] : '' ).'" placeholder="" name="x_phone" />';
            echo '<br /><input type="submit" class="btn btn-default" value="Sign Agreement">';
            echo '</form>';

        }
    }
}


if(!$found_i){
    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missign Agreement Idea ID</div>';
}