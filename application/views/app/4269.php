<?php

$sign_i__id = ( isset($_GET['i__id']) && $_GET['i__id'] > 0 ? $_GET['i__id'] : 0 );
$next_url = ( isset($_GET['url']) ? urldecode($_GET['url']) : ($sign_i__id > 0 ? '/' . $sign_i__id : home_url()) );

//Check to see if they are previously logged in?
if(superpower_unlocked()) {

    //Lead user and above, go to console:
    js_redirect($next_url, 13);

} elseif(isset($_COOKIE['mench_login'])){

    //Authenticate Cookie:
    $cookie_parts = explode('ABCEFG',$_COOKIE['mench_login']);

    $es = $this->E_model->fetch(array(
        'e__id' => $cookie_parts[0],
    ));
    $u_emails = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__up' => 3288, //Mench Email
        'x__down' => $cookie_parts[0],
    ));

    if(count($es) && count($u_emails) && $cookie_parts[2]==md5($cookie_parts[0].$u_emails[0]['x__message'].$cookie_parts[1].$this->config->item('cred_password_salt'))){

        //Assign session & log transaction:
        $this->E_model->activate_session($es[0], false, true);

    } else {

        //Cookie was invalid
        cookie_delete();

    }

    js_redirect($next_url, 13);

} else {

    if($sign_i__id){
        //Assign Session variable so we can detect upon social login:
        $session_data = $this->session->all_userdata();
        $session_data['login_i__id'] = $_GET['i__id'];
        $this->session->set_userdata($session_data);
    }


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
        var go_next_icon = '<?= $e___11035[12211]['m__icon'] ?>';
        var sign_i__id = <?= $sign_i__id ?>;
        var referrer_url = '<?= @$_GET['url'] ?>';
    </script>
    <script src="/application/views/app/4269.js?v=<?= view_memory(6404,11060) ?>"
            type="text/javascript"></script>


    <div class="center-info">

        <div class="text-center"><img src="/img/mench.png" class="mench-spin mench-large" /></div>

        <div class="login-content" style="margin-top:41px;">

            <!-- Step 1: Enter Email -->
            <div id="step2" class="signup-steps hidden">

                <?php


                //Back only if coming from an idea:
                $intro_message = $e___4269[7561]['m__message']; //Assume No Idea
                if ($sign_i__id > 0) {
                    $sign_i = $this->I_model->fetch(array(
                        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                        'i__id' => $sign_i__id,
                    ));
                    if (count($sign_i)) {
                        $intro_message = str_replace('%s','<br /><a href="/' . $sign_i__id . '"><u>'.$sign_i[0]['i__title'].'</u></a>', $e___4269[7560]['m__message']);
                    }
                }



                echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
                echo '<p style="margin-top:13px; text-align: center; padding-bottom: 34px;">'.$intro_message.'</p>';



                //SOCIAL BUTTON:
                echo '<div class="full-width-btn center top-margin"><a href="/app/14436" class="btn btn-large btn-default">';
                echo $e___11035[14436]['m__title'];
                foreach($this->config->item('e___14436') as $e__id => $m) {
                    echo '&nbsp;&nbsp;'.$m['m__icon'];
                }
                echo '</a></div>';
                echo '<div class="mid-text-line"><span>OR</span></div>';
                ?>

                <span class="css__title" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[3288]['m__icon'].'</span>'.$e___4269[3288]['m__title'] ?></span>
                <div class="form-group"><input type="email" id="input_email" <?= isset($_GET['input_email']) ? ' value="'.$_GET['input_email'].'" ' : '' ?> class="form-control border white-border white-border"></div>
                <div id="email_errors" class="discover margin-top-down hideIfEmpty"></div>
                <span id="step2buttons" class="<?= isset($_GET['input_email']) ? '' : ' hidden ' ?>" >
                    <a href="javascript:void(0)" onclick="search_email()" id="email_check_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[12211]['m__title'] ?>"><?= $e___11035[12211]['m__icon'] ?></a>
                <div class="doclear">&nbsp;</div>



            </span>

            </div>





            <!-- Step 3: Enter password (IF FOUND) -->
            <div id="step3" class="signup-steps hidden">

                <!-- To be updated to >0 IF email was found -->
                <input type="hidden" id="sign_e__id" value="0" />

                <span class="css__title" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[3286]['m__icon'].'</span>'.$e___4269[3286]['m__title'] ?></span>
                <div class="form-group"><input type="password" id="input_password" class="form-control border white-border"></div>
                <div id="password_errors" class="discover margin-top-down hideIfEmpty"></div>

                <div class="doclear">&nbsp;</div>

                <div id="step3buttons">
                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" onclick="goto_step(2)" class="controller-nav round-btn pull-left" title="<?= $e___11035[12991]['m__title'] ?>"><?= $e___11035[12991]['m__icon'] ?></a>
                    <a href="javascript:void(0)" onclick="e_signin_password()" id="password_check_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[12211]['m__title'] ?>"><?= $e___11035[12211]['m__icon'] ?></a>
                </div>

                <div class="doclear">&nbsp;</div>
                <div style="padding-top:13px;">No password? Try <a href="javascript:void(0)" onclick="e_magic_email()" style="text-decoration:none;font-weight: bold;"><?= '<u>'.$e___11035[11068]['m__title'].'</u> '.$e___11035[11068]['m__icon'] ?></a></div>

            </div>





            <!-- Step 4: Create New Account (IF NOT FOUND) -->
            <div id="step4" class="signup-steps hidden">

                <!-- pre-set Email -->
                <div class="margin-top-down">
                    <div class="css__title"><span class="icon-block"><?= $e___4269[14026]['m__icon'] ?></span><?= $e___4269[14026]['m__title'] ?></div>
                    <div style="padding:8px 0;"><span class="icon-block">&nbsp;</span><span class="focus_email"></span></div>
                </div>


                <!-- Full Name -->
                <div class="margin-top-down">
                    <span class="css__title" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[13025]['m__icon'].'</span>'.$e___4269[13025]['m__title'] ?></span>
                    <div class="form-group"><input type="text" placeholder="<?= $e___4269[13025]['m__message'] ?>" id="input_name" maxlength="<?= view_memory(6404,6197) ?>" class="form-control border doupper css__title white-border"></div>
                </div>


                <!-- New Password -->
                <div class="margin-top-down">
                    <span class="css__title" style="padding-bottom: 3px; display:block;"><span class="icon-block"><?= $e___4269[14027]['m__icon'] ?></span><?= $e___4269[14027]['m__title'] ?></span>
                    <div class="form-group"><input type="password" id="new_password" class="form-control border white-border"></div>
                </div>


                <!-- Signup Buttons -->
                <div id="new_account_errors" class="discover margin-top-down hideIfEmpty"></div>
                <span>
                    <a href="javascript:void(0)" onclick="goto_step(2)" class="controller-nav round-btn pull-left" title="<?= $e___11035[12991]['m__title'] ?>"><?= $e___11035[12991]['m__icon'] ?></a>
                    <a href="javascript:void(0)" onclick="add_account()" id="add_acount_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[12211]['m__title'] ?>"><?= $e___11035[12211]['m__icon'] ?></a>
                    <div class="doclear">&nbsp;</div>
                </span>

            </div>


            <!-- Step 5: Check your email -->
            <div id="step5" class="signup-steps hidden">
                <div style="padding-bottom: 10px;"><span class="icon-block"><i class="fas fa-envelope-open"></i></span><span class="focus_email"></span></div>
                <span class="medium-header magic_result"></span>
            </div>


        </div>
    </div>

    <?php

}