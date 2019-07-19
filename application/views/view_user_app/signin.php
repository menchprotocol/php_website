<?php
$en_all_7369 = $this->config->item('en_all_7369');
$en_all_6225 = $this->config->item('en_all_6225');
$this_attempt = array(
    'ln_type_entity_id' => ( $referrer_in_id > 0 ? 7560 /* User Signin Intent Channel Choose */ : 7561 /* User Signin on Website */ ),
    'ln_miner_entity_id' => 1, //Shervin/Developer
    'ln_parent_intent_id' => $referrer_in_id,
    'ln_parent_entity_id' => $referrer_en_id,
);

$current_sign_in_attempt = array(); //Will try to find this...
$current_sign_in_attempts = $this->session->userdata('sign_in_attempts');
if(is_array($current_sign_in_attempts) && count($current_sign_in_attempts) > 0){
    //See if any of the current sign-in attempts match this:
    foreach($current_sign_in_attempts as $sign_in_attempt){
        $all_match = true;
        foreach(array('ln_parent_intent_id','ln_parent_entity_id') as $sign_in_attempt_field){
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
    $current_sign_in_attempt = $this->Links_model->ln_create($this_attempt);

    //Grow the array:
    array_push($current_sign_in_attempts, $current_sign_in_attempt);

    //Add this sign-in attempt to session:
    $this->session->set_userdata(array('sign_in_attempts' => $current_sign_in_attempts));

}
?>

<script>
    var referrer_in_id = <?= intval($referrer_in_id) ?>;
    var referrer_en_id = <?= intval($referrer_en_id) ?>;
    var referrer_url = '<?= @$_GET['url'] ?>';
    var channel_choice_messenger = {
        ln_type_entity_id: 7558, //User Signin with Messenger Choice
        ln_miner_entity_id: 1, //Shervin/Developer
        ln_parent_intent_id: <?= intval($referrer_in_id) ?>,
        ln_parent_entity_id: <?= intval($referrer_en_id) ?>,
        ln_parent_link_id: <?= $current_sign_in_attempt['ln_id'] ?>,
    };
    var channel_choice_website = {
        ln_type_entity_id: 7559, //User Signin with Website Choice
        ln_miner_entity_id: 1, //Shervin/Developer
        ln_parent_intent_id: <?= intval($referrer_in_id) ?>,
        ln_parent_entity_id: <?= intval($referrer_en_id) ?>,
        ln_parent_link_id: <?= $current_sign_in_attempt['ln_id'] ?>,
    };
</script>
<script src="/js/custom/signin-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>


<div class="landing-page-intro sign-in-page">

    <div class="signin-logo"><img src="/img/bp_128.png" /></div>

    <h1><?= $en_all_7369[4269]['m_name'] ?></h1>

    <?php
    if($referrer_in_id > 0){
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $referrer_in_id,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ));
        if(count($ins) > 0){
            echo '<p>To <a href="/'.( $referrer_en_id > 0 ? $referrer_en_id.'_' : '' ).$referrer_in_id.'">'.echo_in_outcome($ins[0]['in_outcome']).'</a></p>';
        }
    } elseif(isset($_GET['url']) && strlen($_GET['url']) > 0){
        echo '<p>To access <u>'.urldecode($_GET['url']).'</u></p>';
    }
    ?>

    <div class="login-content" style="margin-top:50px;">

        <!-- Step 1: Choose Channel -->
        <div id="step1" class="signup-steps hidden">
            <p style="padding-bottom:20px;">How would you like to connect to your Mench personal assistant?</p>
            <div class="form-group label-floating is-empty">
                <?php
                //Either 6192 AND or 6193 OR:
                foreach ($this->config->item('en_all_7555') as $en_id => $m) {
                    echo '<span class="radio" style="margin-right: 7px; margin-top: 0 !important;">
                        <label style="display: block; line-height: 120%; font-size: 1.3em; font-weight: 300;">
                            <input type="radio" name="platform_channels" value="' . $en_id . '" '.( $en_id==6196 /* Mench on Messenger */ ? ' checked="checked" ' : '' ).' />
                            <div style="display: block; line-height: 120%;"><b>' . $m['m_icon'] . ' ' . $m['m_name'] . '</b> '.$m['m_desc'].'</div>
                        </label>
                    </span>';
                }
                ?>
            </div>
            <div id="step1button" style="height:60px;"><a href="javascript:void(0)" onclick="choose_channel()" class="btn btn-primary pass btn-raised btn-round btn-next">Next <i class="fas fa-arrow-right"></i></a></div>
        </div>


        <!-- Step 2: Enter Email -->
        <div id="step2" class="signup-steps hidden">
            <span class="medium-header"><?= $en_all_6225[3288]['m_icon'].' '.$en_all_6225[3288]['m_name'] ?></span>
            <div class="form-group is-empty"><input type="email" id="input_email" class="form-control border"></div>
            <div id="email_errors" class="signin-error-box"></div>
            <span id="step2buttons">
                <a href="javascript:void(0)" onclick="goto_step(1)" class="btn btn-primary transparent pass btn-raised btn-round <?= ( $referrer_in_id > 0 ? '' : ' hidden ' ) ?>"><i class="fas fa-arrow-left"></i></a>
                <a href="javascript:void(0)" onclick="search_email()" id="email_check_next" class="btn btn-primary pass btn-raised btn-round btn-next">Next <i class="fas fa-arrow-right"></i></a>
            </span>
            <span style="padding-left:5px; font-size:1em !important;" class="<?= ( $referrer_in_id > 0 ? ' hidden ' : '' ) ?>">Or <a href="https://m.me/askmench" class="underdot" style="font-size:1em !important;">Signin on Messenger <i class="fas fa-arrow-right"></i></a></span>
        </div>


        <!-- Step 3: Enter Password -->
        <div id="step3" class="signup-steps hidden">

            <!-- To be updated to >0 IF email was found -->
            <input type="hidden" id="login_en_id" value="0" />

            <span class="medium-header"><?= $en_all_6225[3286]['m_icon'].' '.$en_all_6225[3286]['m_name'] ?> for <span class="focus_email"></span></span>
            <div class="form-group is-empty"><input type="password" id="input_password" class="form-control border"></div>
            <div id="password_errors" class="signin-error-box"></div>
            <span id="step3buttons">
                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="Go Back" onclick="goto_step(2)" class="btn btn-primary transparent pass btn-raised btn-round <?= ( $referrer_in_id > 0 ? '' : ' hidden ' ) ?>"><i class="fas fa-arrow-left"></i></a>
                <a href="javascript:void(0)" onclick="check_password()" id="password_check_next" class="btn btn-primary pass btn-raised btn-round btn-next">Sign In <i class="fas fa-arrow-right"></i></a>
            </span>

            <span style="padding-left:5px; font-size:1em !important;" class="<?= ( $referrer_in_id > 0 ? ' hidden ' : '' ) ?>">Or <a href="javascript:void(0)" onclick="email_forgot_password()" class="underdot" style="font-size:1em !important;">Forgot Password <i class="fas fa-arrow-right"></i></a></span>

        </div>


        <!-- Step 3: Check your email -->
        <div id="step4" class="signup-steps hidden">
            <p style="padding-top: 20px;">We emailed you instructions to sign-in to your Mench account.</p>
            <span class="medium-header" style="padding-top: 20px;"><?= $en_all_6225[3288]['m_icon'].' Check your email <span class="focus_email"></span> to continue' ?></span>
            <p style="padding-top: 20px;">You may close this window now.</p>
        </div>

    </div>
</div>