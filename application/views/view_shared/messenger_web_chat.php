<?php
$session_en = $this->session->userdata('user');
$fb_settings = $this->config->item('fb_settings');
?>
<script>
    //Facebook SDK for JavaScript:
    window.fbAsyncInit = function () {
        FB.init({
            appId: '<?= $fb_settings['app_id'] ?>',
            autoLogAppEvents: true,
            xfbml: true,
            version: 'v3.2' //Updated 2018-11-11
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

</script>

<div class="fb-customerchat" minimized="true"
     ref="<?= $this->config->item('in_home_page') ?>" <?= ($session_en ? 'logged_in_greeting="' . fn___one_two_explode('', ' ', $session_en['en_name']) . ', how can I supercharge your tech career?"' : '') ?>
     logged_out_greeting="Hi 👋 How can we help you?" greeting_dialog_display="hide" theme_color="#2f2739"
     page_id="<?= $fb_settings['page_id'] ?>"></div>

<div class="app-version hide-mini <?= fn___echo_advance() ?>">v<?= $this->config->item('app_version') ?></div>