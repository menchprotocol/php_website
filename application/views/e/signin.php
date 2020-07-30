<?php
$e___4269 = $this->config->item('e___4269');
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION

$this_attempt = array(
    'x__type' => ( $sign_i__id > 0 ? 7560 /* User Signin Idea Channel Choose */ : 7561 /* User Signin on Website */ ),
    'x__left' => $sign_i__id,
);

$current_sign_i_attempt = array(); //Will try to find this...
$current_sign_i_attempts = $this->session->userdata('sign_i_attempts');
if(is_array($current_sign_i_attempts) && count($current_sign_i_attempts) > 0){
    //See if any of the current sign-in attempts match this:
    foreach($current_sign_i_attempts as $sign_i_attempt){
        $all_match = true;
        foreach(array('x__left') as $sign_i_attempt_field){
            if(intval($this_attempt[$sign_i_attempt_field]) != intval($sign_i_attempt[$sign_i_attempt_field])){
                $all_match = false;
                break;
            }
        }
        if($all_match){
            //We found a match!
            $current_sign_i_attempt = $sign_i_attempt;
            break;
        }
    }
} else {
    $current_sign_i_attempts = array();
}


//See what to do based on current matches:
if(count($current_sign_i_attempt) == 0){

    //Log transaction:
    $current_sign_i_attempt = $this->X_model->create($this_attempt);

    //Grow the array:
    array_push($current_sign_i_attempts, $current_sign_i_attempt);

    //Add this sign-in attempt to session:
    $this->session->set_userdata(array('sign_i_attempts' => $current_sign_i_attempts));

}
?>

<script>
    var go_next_icon = '<?= $e___11035[12211]['m_icon'] ?>';
    var sign_i__id = <?= $sign_i__id ?>;
    var referrer_url = '<?= @$_GET['url'] ?>';
</script>
<script src="/application/views/e/signin.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>


<div class="container center-info">

    <div class="text-center"><img src="/img/mench.png" class="mench-spin mench-large" /></div>


    <h1 class="text-center"><?= $e___11035[4269]['m_title'] ?></h1>

    <?php
    if($sign_i__id > 0){

        $sign_i = $this->I_model->fetch(array(
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'i__id' => $sign_i__id,
        ));

        if(count($sign_i)){
            echo '<p style="margin-top:13px;">To <a href="/'.$sign_i__id.'" class="montserrat">'.$sign_i[0]['i__title'].'</a> for Free.</p>';
        }

    }

    echo '<p style="margin-top:13px;">'.view_12687(4269).'</p>';
    ?>


    <div class="login-content" style="margin-top:34px;">

        <!-- Step 2: Enter Email -->
        <div id="step2" class="signup-steps hidden">
            <span class="montserrat" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[3288]['m_icon'].'</span>'.$e___4269[3288]['m_title'] ?></span>
            <div class="form-group is-empty"><input type="email" id="input_email" <?= isset($_GET['input_email']) ? ' value="'.$_GET['input_email'].'" ' : '' ?> class="form-control border"></div>
            <div id="email_errors" class="discover margin-top-down hideIfEmpty"></div>
            <span id="step2buttons">
                <a href="javascript:void(0)" onclick="search_email()" id="email_check_next" class="btn btn-e btn-raised" title="<?= $e___11035[12211]['m_title'] ?>"><?= $e___11035[12211]['m_icon'] ?></a>
            </span>
        </div>





        <!-- Step 3: Enter password -->
        <div id="step3" class="signup-steps hidden">

            <!-- To be updated to >0 IF email was found -->
            <input type="hidden" id="sign_e__id" value="0" />

            <span class="montserrat" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[3286]['m_icon'].'</span>'.$e___4269[3286]['m_title'] ?></span>
            <div class="form-group is-empty"><input type="password" id="input_password" class="form-control border"></div>
            <div id="password_errors" class="discover margin-top-down hideIfEmpty"></div>
            <span id="step3buttons">
                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" onclick="goto_step(2)" class="btn btn-e transparent btn-raised " title="<?= $e___11035[12991]['m_title'] ?>"><?= $e___11035[12991]['m_icon'] ?></a>
                <a href="javascript:void(0)" onclick="e_signin_password()" id="password_check_next" class="btn btn-e btn-raised " title="<?= $e___11035[12211]['m_title'] ?>"><?= $e___11035[12211]['m_icon'] ?></a>
            </span>

            <span style="padding-left:5px; font-size:0.9em !important;">OR <a href="javascript:void(0)" onclick="e_magic_email()" class="dounderline"><?= $e___11035[11068]['m_title'] ?></a> <?= $e___11035[11068]['m_icon'] ?></span>

        </div>





        <!-- Step 4: Create New Account -->
        <div id="step4" class="signup-steps hidden">

            <!-- pre-set Email -->
            <div class="margin-top-down">
                <span class="montserrat" style="padding-bottom: 3px; display:block;"><span class="icon-block"><i class="fas fa-user-plus"></i></span>NEW ACCOUNT:</span>
                <div><span class="icon-block">&nbsp;</span><span class="focus_email"></span></div>
            </div>


            <!-- Full Name -->
            <div class="margin-top-down">
                <span class="montserrat" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.str_replace(' source','',$e___4269[13025]['m_icon']).'</span>'.$e___4269[13025]['m_title'] ?>:</span>
                <div class="form-group is-empty"><input type="text" placeholder="<?= $e___4269[13025]['m_desc'] ?>" id="input_name" maxlength="<?= config_var(6197) ?>" class="form-control border doupper montserrat"></div>
            </div>

            <!-- New Password -->
            <div class="margin-top-down">
                <span class="montserrat" style="padding-bottom: 3px; display:block;"><span class="icon-block"><?= $e___4269[3286]['m_icon'] ?></span>NEW PASSWORD:</span>
                <div class="form-group is-empty"><input type="password" id="new_password" class="form-control border"></div>
            </div>


            <!-- Signup Buttons -->
            <div id="new_account_errors" class="discover margin-top-down hideIfEmpty"></div>
            <span id="step2buttons">
                <a href="javascript:void(0)" onclick="goto_step(2)" class="btn btn-e transparent btn-raised" title="<?= $e___11035[12991]['m_title'] ?>"><?= $e___11035[12991]['m_icon'] ?></a>
                <a href="javascript:void(0)" onclick="add_account()" id="add_acount_next" class="btn btn-e btn-raised" title="<?= $e___11035[12211]['m_title'] ?>"><?= $e___11035[12211]['m_icon'] ?></a>
            </span>

        </div>




        <!-- Step 5: Check your email -->
        <div id="step5" class="signup-steps hidden">
            <span style="padding-bottom: 10px;"><i class="fas fa-envelope-open"></i> <span class="focus_email"></span></span>
            <span class="medium-header magic_result"></span>
        </div>



    </div>
</div>