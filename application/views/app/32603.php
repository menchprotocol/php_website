<?php

$found_i = false;
$signed_i = false;

foreach($this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) && $_GET['i__id'] > 0 ? $_GET['i__id'] : 0 ),
    'i__type' => 32603, //Sign Agreement
    'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
)) as $i_sign){

    $found_i = true;

    if(isset($_POST) && count($_POST)){

        //Process Signature to make sure it's all ok:
        if (strlen($_POST['x_write'])<5 || !substr_count($_POST['x_write'] , ' ')) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Enter Your First & Last Name</div>';
        } elseif (!isset($_POST['DigitalSignAgreement']) || !intval($_POST['DigitalSignAgreement'])) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>You must agree to be legally bound by this document.</div>';
        } elseif (!filter_var($_POST['x_email'], FILTER_VALIDATE_EMAIL)) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Enter a valid Email Address</div>';
        } elseif (strlen(intval($_POST['x_phone']))<10) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Enter a valid Phone Number</div>';
        } else {

            //Input validated, process signature:
            $signed_i = true;
            $phone = intval($_POST['x_phone']);
            $email = trim($_POST['x_email']);

            //See if we can find this user with their email:
            $map_users = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__up' => 3288, //Email
                'LOWER(x__message)' => $email.'',
            ), array('x__down'));
            if(!count($map_users)){
                $map_users = $this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__up' => 4783, //Phone
                    'x__message' => $phone.'',
                ), array('x__down'));
            }

            //Still missing user?
            if(!count($map_users)){
                $member_result = $this->E_model->add_member($_POST['x_write'], $email, $phone, null, 0, true);
                if($member_result['status']) {
                    $map_users[0] = $member_result['e'];
                }
            }

            foreach($map_users as $map_user){
                //Sign agreement:
                $this->X_model->mark_complete($i_sign['i__id'], $i_sign, array(
                    'x__type' => 33614,
                    'x__creator' => $map_user['e__id'],
                    'x__message' => $_POST['x_write'],
                ));

                //Log transaction:
                $this->X_model->create(array(
                    'x__creator' => $map_user['e__id'],
                    'x__type' => 32603,
                    'x__left' => $i_sign['i__id'],
                ));
            }

            echo '<div class="msg alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle zq6255"></i></span> Waver signed for "'.$_POST['x_write'].'".<br />Show your ID at the door to enter'.( isset($map_user['e__id']) ? '<a href="/@'.$map_user['e__id'].'" style="text-decoration: none;">.</a>' : '.' ).'</div>';

        }

    }


    if(!$signed_i){

        //Allow users to sign:
        echo view_i__message($i_sign);

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


if(!$found_i){
    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missign Agreement Idea ID</div>';
}