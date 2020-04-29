<?php
$en_all_4269 = $this->config->item('en_all_4269');
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

//See if we have a session assigned:
$referrer_in_id = intval($this->session->userdata('sign_in_id'));

$this_attempt = array(
    'ln_type_source_id' => ( $referrer_in_id > 0 ? 7560 /* User Signin Idea Channel Choose */ : 7561 /* User Signin on Website */ ),
    'ln_previous_idea_id' => $referrer_in_id,
);

$current_sign_in_attempt = array(); //Will try to find this...
$current_sign_in_attempts = $this->session->userdata('sign_in_attempts');
if(is_array($current_sign_in_attempts) && count($current_sign_in_attempts) > 0){
    //See if any of the current sign-in attempts match this:
    foreach($current_sign_in_attempts as $sign_in_attempt){
        $all_match = true;
        foreach(array('ln_previous_idea_id') as $sign_in_attempt_field){
            if(intval($this_attempt[$sign_in_attempt_field]) != intval($sign_in_attempt[$sign_in_attempt_field])){
                $all_match = false;
                break;
            }
        }
        if($all_match){
            //We found a match!
            $current_sign_in_attempt = $sign_in_attempt;
            break;
        }
    }
} else {
    $current_sign_in_attempts = array();
}


//See what to do based on current matches:
if(count($current_sign_in_attempt) == 0){

    //Log link:
    $current_sign_in_attempt = $this->LEDGER_model->ln_create($this_attempt);

    //Grow the array:
    array_push($current_sign_in_attempts, $current_sign_in_attempt);

    //Add this sign-in attempt to session:
    $this->session->set_userdata(array('sign_in_attempts' => $current_sign_in_attempts));

}
?>

<script>
    var referrer_in_id = <?= intval($referrer_in_id) ?>;
    var referrer_url = '<?= @$_GET['url'] ?>';
</script>
<script src="/application/views/source/source_sign.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>


<div class="container center-info">

    <div class="text-center"><img src="/img/mench.png" class="mench-spin mench-large" /></div>

    <h1 class="text-center"><?= $en_all_11035[4269]['m_name'] ?> [FREE]</h1>

    <div class="login-content" style="margin-top:50px;">

        <!-- Step 2: Enter Email -->
        <div id="step2" class="signup-steps hidden">
            <span class="montserrat"><?= $en_all_4269[3288]['m_icon'].' '.$en_all_4269[3288]['m_name'] ?></span>
            <div class="form-group is-empty"><input type="email" id="input_email" <?= isset($_GET['input_email']) ? ' value="'.$_GET['input_email'].'" ' : '' ?> class="form-control border"></div>
            <div id="email_errors" class="discover margin-top-down hideIfEmpty"></div>
            <span id="step2buttons">
                <a href="javascript:void(0)" onclick="search_email()" id="email_check_next" class="btn btn-source btn-raised btn-circle"><i class="fas fa-step-forward"></i></a>
            </span>
        </div>





        <!-- Step 3: Enter password -->
        <div id="step3" class="signup-steps hidden">

            <!-- To be updated to >0 IF email was found -->
            <input type="hidden" id="login_en_id" value="0" />

            <span class="montserrat"><?= $en_all_4269[3286]['m_icon'].' '.$en_all_4269[3286]['m_name'] ?></span>
            <div class="form-group is-empty"><input type="password" id="input_password" class="form-control border"></div>
            <div id="password_errors" class="discover margin-top-down hideIfEmpty"></div>
            <span id="step3buttons">
                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="Go Back" onclick="goto_step(2)" class="btn btn-source transparent btn-raised  btn-circle"><i class="fas fa-step-backward"></i></a>
                <a href="javascript:void(0)" onclick="singin_check_password()" id="password_check_next" class="btn btn-source btn-raised  btn-circle"><i class="fas fa-step-forward"></i></a>
            </span>

            <span style="padding-left:5px; font-size:0.9em !important;">OR <a href="javascript:void(0)" onclick="magicemail()" class="dounderline"><?= $en_all_11035[11068]['m_name'] ?></a> <?= $en_all_11035[11068]['m_icon'] ?></span>

        </div>







        <!-- Step 4: Create New Account -->
        <div id="step4" class="signup-steps hidden">

            <div class="discover-topic montserrat"><span class="icon-block-sm"><i class="fas fa-user-plus"></i></span>NEW ACCOUNT</div>

            <!-- pre-set Email -->
            <div class="margin-top-down">
                <span><?= $en_all_4269[3288]['m_icon'].' '.$en_all_4269[3288]['m_name'] ?>:</span>
                <div><b><span class="focus_email"></span></b></div>
            </div>


            <!-- Full Name -->
            <div class="margin-top-down">
                <span><?= $en_all_4269[6197]['m_icon'].' '.$en_all_4269[6197]['m_name'] ?>:</span>
                <div class="form-group is-empty"><input type="text" placeholder="<?= $en_all_4269[6197]['m_desc'] ?>" id="input_name" maxlength="<?= config_var(6197) ?>" class="form-control border"></div>
            </div>

            <!-- New Password -->
            <div class="margin-top-down">
                <span><?= $en_all_4269[3286]['m_icon'] ?> NEW PASSWORD:</span>
                <div class="form-group is-empty"><input type="password" id="new_password" class="form-control border"></div>
            </div>


            <!-- Signup Buttons -->
            <div id="new_account_errors" class="discover margin-top-down hideIfEmpty"></div>
            <span id="step2buttons">
                <a href="javascript:void(0)" onclick="goto_step(2)" class="btn btn-source transparent btn-raised btn-circle"><i class="fas fa-step-backward"></i></a>
                <a href="javascript:void(0)" onclick="add_account()" id="add_acount_next" class="btn btn-source btn-raised btn-circle"><i class="fas fa-step-forward"></i></a>
            </span>

        </div>




        <!-- Step 5: Check your email -->
        <div id="step5" class="signup-steps hidden">
            <span><i class="fas fa-envelope-open"></i> <span class="focus_email"></span></span>
            <span class="medium-header magic_result"></span>
        </div>



    </div>
</div>