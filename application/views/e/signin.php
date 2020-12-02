<?php
$e___4269 = $this->config->item('e___4269');
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$e___10876 = $this->config->item('e___10876'); //Mench Website

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
    var go_next_icon = '<?= $e___11035[12211]['m__icon'] ?>';
    var sign_i__id = <?= $sign_i__id ?>;
    var referrer_url = '<?= @$_GET['url'] ?>';
</script>
<script src="/application/views/e/signin.js?v=<?= view_memory(6404,11060) ?>"
        type="text/javascript"></script>


<div class="container center-info">

    <div class="text-center"><img src="/img/mench.png" class="mench-spin mench-large" /></div>

    <?php
    echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
    echo '<p style="margin-top:13px; text-align: center;">'.$e___11035[4269]['m__message'].'</p>';
    ?>

    <div class="login-content" style="margin-top:41px;">

        <!-- Step 1: Enter Email -->
        <div id="step2" class="signup-steps hidden">
            <span class="montserrat" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[3288]['m__icon'].'</span>'.$e___4269[3288]['m__title'] ?></span>
            <div class="form-group"><input type="email" id="input_email" <?= isset($_GET['input_email']) ? ' value="'.$_GET['input_email'].'" ' : '' ?> class="form-control border white-border white-border"></div>
            <div id="email_errors" class="discover margin-top-down hideIfEmpty"></div>
            <span id="step2buttons">
                <?php
                //Back only if coming from an idea:
                if($sign_i__id > 0){
                    $sign_i = $this->I_model->fetch(array(
                        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                        'i__id' => $sign_i__id,
                    ));
                    if(count($sign_i)){
                        echo '<a href="/'.$sign_i__id.'" class="controller-nav round-btn pull-left">'.$e___11035[12991]['m__icon'].'</a>';
                    }
                }
                ?>
                <a href="javascript:void(0)" onclick="search_email()" id="email_check_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[12211]['m__title'] ?>"><?= $e___11035[12211]['m__icon'] ?></a>

            </span>
        </div>





        <!-- Step 2: Enter password (IF FOUND) -->
        <div id="step3" class="signup-steps hidden">

            <!-- To be updated to >0 IF email was found -->
            <input type="hidden" id="sign_e__id" value="0" />

            <span class="montserrat" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[3286]['m__icon'].'</span>'.$e___4269[3286]['m__title'] ?></span>
            <div class="form-group"><input type="password" id="input_password" class="form-control border white-border"></div>
            <div id="password_errors" class="discover margin-top-down hideIfEmpty"></div>

            <div class="doclear">&nbsp;</div>
            
            <div id="step3buttons">
                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" onclick="goto_step(2)" class="controller-nav round-btn pull-left" title="<?= $e___11035[12991]['m__title'] ?>"><?= $e___11035[12991]['m__icon'] ?></a>
                <a href="javascript:void(0)" onclick="e_signin_password()" id="password_check_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[12211]['m__title'] ?>"><?= $e___11035[12211]['m__icon'] ?></a>
            </div>

            <div style="padding-top:13px;">Forgot Password? Try <a href="javascript:void(0)" onclick="e_magic_email()" style="text-decoration: underline;font-weight: bold;"><?= $e___11035[11068]['m__title'] ?></a> <?= $e___11035[11068]['m__icon'] ?></div>

            <div class="doclear">&nbsp;</div>

        </div>





        <!-- Step 3: Create New Account (IF NOT FOUND) -->
        <div id="step4" class="signup-steps hidden">


            <!-- Welcome Message -->
            <div class="margin-top-down">
                <?= sprintf($e___4269[14044]['m__message'], view_coins_e(12274, 4430, 0, false)); ?>
            </div>


            <!-- pre-set Email -->
            <div class="margin-top-down">
                <div class="montserrat"><span class="icon-block"><?= $e___4269[14026]['m__icon'] ?></span><?= $e___4269[14026]['m__title'] ?></div>
                <div style="padding:8px 0;"><span class="icon-block">&nbsp;</span><span class="focus_email"></span></div>
                <div><span class="icon-block">&nbsp;</span><?= $e___11035[4755]['m__icon'] .' Emails are '. $e___11035[4755]['m__title'] ?></div>
                <div><span class="icon-block">&nbsp;</span><?= $e___11035[4755]['m__message'] ?></div>
            </div>


            <!-- Full Name -->
            <div class="margin-top-down">
                <span class="montserrat" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[13025]['m__icon'].'</span>'.$e___4269[13025]['m__title'] ?></span>
                <div class="form-group"><input type="text" placeholder="<?= $e___4269[13025]['m__message'] ?>" id="input_name" maxlength="<?= view_memory(6404,6197) ?>" class="form-control border doupper montserrat white-border"></div>
            </div>


            <!-- New Password -->
            <div class="margin-top-down">
                <span class="montserrat" style="padding-bottom: 3px; display:block;"><span class="icon-block"><?= $e___4269[14027]['m__icon'] ?></span><?= $e___4269[14027]['m__title'] ?></span>
                <div class="form-group"><input type="password" id="new_password" class="form-control border white-border"></div>
            </div>


            <!-- Terms of Service -->
            <div class="margin-top-down">
                <span class="montserrat" style="padding-bottom: 3px; display:block;"><span class="icon-block"></span>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms_accepted">
                    <label class="form-check-label inline-block" for="terms_accepted">Accept Our <?= $e___4269[14373]['m__title'] ?></label>
                    <a href="<?= $e___10876[14373]['m__message'] ?>" target="_blank"><i class="fas fa-external-link"></i></a>
                </div>
            </div>


            <!-- Signup Buttons -->
            <div id="new_account_errors" class="discover margin-top-down hideIfEmpty"></div>
            <span id="step2buttons">
                <a href="javascript:void(0)" onclick="goto_step(2)" class="controller-nav round-btn pull-left" title="<?= $e___11035[12991]['m__title'] ?>"><?= $e___11035[12991]['m__icon'] ?></a>
                <a href="javascript:void(0)" onclick="add_account()" id="add_acount_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[12211]['m__title'] ?>"><?= $e___11035[12211]['m__icon'] ?></a>
            </span>

        </div>




        <!-- Step 5: Check your email -->
        <div id="step5" class="signup-steps hidden">
            <div style="padding-bottom: 10px;"><span class="icon-block"><i class="fas fa-envelope-open"></i></span><span class="focus_email"></span></div>
            <span class="medium-header magic_result"></span>
        </div>



    </div>

</div>