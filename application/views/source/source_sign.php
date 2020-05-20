<?php
$sources__4269 = $this->config->item('sources__4269');
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION

//See if we have a session assigned:
$referrer_idea__id = intval($this->session->userdata('sign_idea__id'));

$this_attempt = array(
    'read__type' => ( $referrer_idea__id > 0 ? 7560 /* User Signin Idea Channel Choose */ : 7561 /* User Signin on Website */ ),
    'read__left' => $referrer_idea__id,
);

$current_sign_idea_attempt = array(); //Will try to find this...
$current_sign_idea_attempts = $this->session->userdata('sign_idea_attempts');
if(is_array($current_sign_idea_attempts) && count($current_sign_idea_attempts) > 0){
    //See if any of the current sign-in attempts match this:
    foreach($current_sign_idea_attempts as $sign_idea_attempt){
        $all_match = true;
        foreach(array('read__left') as $sign_idea_attempt_field){
            if(intval($this_attempt[$sign_idea_attempt_field]) != intval($sign_idea_attempt[$sign_idea_attempt_field])){
                $all_match = false;
                break;
            }
        }
        if($all_match){
            //We found a match!
            $current_sign_idea_attempt = $sign_idea_attempt;
            break;
        }
    }
} else {
    $current_sign_idea_attempts = array();
}


//See what to do based on current matches:
if(count($current_sign_idea_attempt) == 0){

    //Log link:
    $current_sign_idea_attempt = $this->READ_model->create($this_attempt);

    //Grow the array:
    array_push($current_sign_idea_attempts, $current_sign_idea_attempt);

    //Add this sign-in attempt to session:
    $this->session->set_userdata(array('sign_idea_attempts' => $current_sign_idea_attempts));

}
?>

<script>
    var go_next_icon = '<?= $sources__11035[12211]['m_icon'] ?>';
    var referrer_idea__id = <?= intval($referrer_idea__id) ?>;
    var referrer_url = '<?= @$_GET['url'] ?>';
</script>
<script src="/application/views/source/source_sign.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>


<div class="container center-info">

    <div class="text-center"><img src="/img/mench.png" class="mench-spin mench-large" /></div>

    <h1 class="text-center"><?= $sources__11035[4269]['m_name'] ?> [FREE]</h1>

    <div class="login-content" style="margin-top:50px;">

        <!-- Step 2: Enter Email -->
        <div id="step2" class="signup-steps hidden">
            <span class="montserrat"><?= $sources__4269[3288]['m_icon'].' '.$sources__4269[3288]['m_name'] ?></span>
            <div class="form-group is-empty"><input type="email" id="input_email" <?= isset($_GET['input_email']) ? ' value="'.$_GET['input_email'].'" ' : '' ?> class="form-control border"></div>
            <div id="email_errors" class="read margin-top-down hideIfEmpty"></div>
            <span id="step2buttons">
                <a href="javascript:void(0)" onclick="search_email()" id="email_check_next" class="btn btn-source btn-raised btn-circle" title="<?= $sources__11035[12211]['m_name'] ?>"><?= $sources__11035[12211]['m_icon'] ?></a>
            </span>
        </div>





        <!-- Step 3: Enter password -->
        <div id="step3" class="signup-steps hidden">

            <!-- To be updated to >0 IF email was found -->
            <input type="hidden" id="sign_source__id" value="0" />

            <span class="montserrat"><?= $sources__4269[3286]['m_icon'].' '.$sources__4269[3286]['m_name'] ?></span>
            <div class="form-group is-empty"><input type="password" id="input_password" class="form-control border"></div>
            <div id="password_errors" class="read margin-top-down hideIfEmpty"></div>
            <span id="step3buttons">
                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" onclick="goto_step(2)" class="btn btn-source transparent btn-raised  btn-circle" title="<?= $sources__11035[12991]['m_name'] ?>"><?= $sources__11035[12991]['m_icon'] ?></a>
                <a href="javascript:void(0)" onclick="sign_check_password()" id="password_check_next" class="btn btn-source btn-raised  btn-circle" title="<?= $sources__11035[12211]['m_name'] ?>"><?= $sources__11035[12211]['m_icon'] ?></a>
            </span>

            <span style="padding-left:5px; font-size:0.9em !important;">OR <a href="javascript:void(0)" onclick="magicemail()" class="dounderline"><?= $sources__11035[11068]['m_name'] ?></a> <?= $sources__11035[11068]['m_icon'] ?></span>

        </div>





        <!-- Step 4: Create New Account -->
        <div id="step4" class="signup-steps hidden">

            <div class="read-topic montserrat"><span class="icon-block-sm"><i class="fas fa-user-plus"></i></span>NEW ACCOUNT</div>

            <!-- pre-set Email -->
            <div class="margin-top-down">
                <span><?= $sources__4269[3288]['m_icon'].' '.$sources__4269[3288]['m_name'] ?>:</span>
                <div><b><span class="focus_email"></span></b></div>
            </div>


            <!-- Full Name -->
            <div class="margin-top-down">
                <span><?= $sources__4269[13025]['m_icon'].' '.$sources__4269[13025]['m_name'] ?>:</span>
                <div class="form-group is-empty"><input type="text" placeholder="<?= $sources__4269[13025]['m_desc'] ?>" id="input_name" maxlength="<?= config_var(6197) ?>" class="form-control border doupper montserrat"></div>
            </div>

            <!-- New Password -->
            <div class="margin-top-down">
                <span><?= $sources__4269[3286]['m_icon'] ?> NEW PASSWORD:</span>
                <div class="form-group is-empty"><input type="password" id="new_password" class="form-control border"></div>
            </div>


            <!-- Signup Buttons -->
            <div id="new_account_errors" class="read margin-top-down hideIfEmpty"></div>
            <span id="step2buttons">
                <a href="javascript:void(0)" onclick="goto_step(2)" class="btn btn-source transparent btn-raised btn-circle" title="<?= $sources__11035[12991]['m_name'] ?>"><?= $sources__11035[12991]['m_icon'] ?></a>
                <a href="javascript:void(0)" onclick="add_account()" id="add_acount_next" class="btn btn-source btn-raised btn-circle" title="<?= $sources__11035[12211]['m_name'] ?>"><?= $sources__11035[12211]['m_icon'] ?></a>
            </span>

        </div>




        <!-- Step 5: Check your email -->
        <div id="step5" class="signup-steps hidden">
            <span style="padding-bottom: 10px;"><i class="fas fa-envelope-open"></i> <span class="focus_email"></span></span>
            <span class="medium-header magic_result"></span>
        </div>



    </div>
</div>