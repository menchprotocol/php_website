<?php

//Fetch some needed variables:
$fb_settings = $this->config->item('fb_settings');
$url_part_1 = $this->uri->segment(1);

?>

<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            xfbml            : true,
            version          : '<?= $fb_settings['default_graph_version'] ?>'
        });
    };

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-customerchat"
     <?= ( is_numeric($url_part_1) ? ' ref="'.$url_part_1.'" ' : '' ) ?>
     attribution=setup_tool
     greeting_dialog_display="hide"
     page_id="<?= $fb_settings['page_id'] ?>"
     theme_color="#070707"
     logged_in_greeting="Hi! How can I help you land your dream job?"
     logged_out_greeting="Hi! How can I help you land your dream job?">
</div>