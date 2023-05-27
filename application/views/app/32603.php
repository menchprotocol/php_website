<?php

$found_idea = false;
$signed_idea = false;

foreach($this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) && $_GET['i__id'] > 0 ? $_GET['i__id'] : 0 ),
    'i__type' => 32603, //Sign Agreement
    'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
)) as $i_sign){

    $found_idea = true;

    if(isset($_POST['x_write']) && isset($_POST['x_email']) && isset($_POST['x_phone']) && isset($_POST['DigitalSignAgreement'])){

        //Process Signature to make sure it's all ok:
        if (strlen($_POST['x_write'])<5 || !substr_count($_POST['x_write'] , ' ')) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Enter Your First & Last Name</div>';
        } elseif (filter_var($_POST['x_email'], FILTER_VALIDATE_EMAIL)) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Enter a Valid Email Address</div>';
        } elseif (strlen(intval($_POST['x_phone']))<10) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Enter a Valid Phone Number</div>';
        } else {
            //Input validated, process signature:
            $signed_idea = true;
            echo '['.$_POST['DigitalSignAgreement'].']';
        }

    }


    if(!$signed_idea){
        //Allow user to sign:
        echo '<form method="post" action="">';
        echo '<h1 class="msg-frame sign_text">'.$i_sign['i__title'].'</h1>';
        echo view_i__cache($i_sign);
        echo view_sign($i_sign, @$_POST['x_write']);
        echo '<input type="text" class="border greybg main__title itemsetting sign_text" value="'.@$_POST['x_email'].'" placeholder="Email Address" id="x_email" />';
        echo '<input type="text" class="border greybg main__title itemsetting sign_text" value="'.@$_POST['x_phone'].'" placeholder="Phone Number" id="x_phone" />';
        echo '<input type="submit" class="btn btn-default" value="Sign Agreement">';
        echo '</form>';
    }
}


if(!$found_idea){
    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missign Agreement Idea ID</div>';
}