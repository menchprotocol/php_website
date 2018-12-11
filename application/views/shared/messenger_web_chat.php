<?php
$udata = $this->session->userdata('user');
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
     ref="ACTIONPLANADD10_<?= $this->config->item('in_primary_id') ?>" <?= ($udata ? 'logged_in_greeting="' . one_two_explode('', ' ', $udata['en_name']) . ', how can we help you grow?"' : '') ?>
     logged_out_greeting="Hi 👋 How can we help you?" greeting_dialog_display="hide" theme_color="#2f2739"
     page_id="<?= $fb_settings['page_id'] ?>"></div>