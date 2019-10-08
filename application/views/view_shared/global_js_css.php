


<!-- START GLOBAL STATIC RESOURCES -->
<link rel="stylesheet" type="text/css"
      href="//fonts.googleapis.com/css?family=Lato|Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Titillium+Web:700"/>
<link href="/css/lib/fa/fontawesome.css?v=5.8.1" rel="stylesheet"/>
<link href="/css/lib/fa/brands.css?v=5.8.1" rel="stylesheet"/>
<link href="/css/lib/fa/solid.css?v=5.8.1" rel="stylesheet"/>
<link href="/css/lib/fa/regular.css?v=5.8.1" rel="stylesheet"/>
<link href="/css/lib/fa/light.css?v=5.8.1" rel="stylesheet"/>

<!-- CSS -->
<link href="/css/lib/bootstrap.min.css?v=1" rel="stylesheet"/>
<link href="/css/lib/jquery-ui.min.css?v=1" rel="stylesheet"/>
<link href="/css/lib/simplebar.css" rel="stylesheet"/>
<link href="/css/lib/material-dashboard.css" rel="stylesheet"/>
<link href="/css/lib/material-kit.css" rel="stylesheet"/>


<!-- Core JS File/Variables -->
<script src="/js/lib/jquery-3.1.0.min.js" type="text/javascript"></script>
<script src="/js/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/js/lib/bootstrap.min.js" type="text/javascript"></script>
<script src="/js/lib/material.min.js" type="text/javascript"></script>
<script src="/js/lib/material-dashboard.js" type="text/javascript"></script>
<script src="/js/lib/simplebar.js"></script>


<script>
    <?php
    $session_en = $this->session->userdata('user');


    //Translate key variables into JS variables to have them available throughout all JS functions:
    echo ' var is_compact = (is_mobile() || $(window).width() < 767); '; //Manages UI view based on browse width (For example removed fixed right column for mobile)

    //Passon object status Fields:
    echo ' var js_en_all_4737 = ' . json_encode($this->config->item('en_all_4737')) . '; '; // Intent Statuses
    echo ' var js_en_all_6177 = ' . json_encode($this->config->item('en_all_6177')) . '; '; // Entity Statuses
    echo ' var js_en_all_6186 = ' . json_encode($this->config->item('en_all_6186')) . '; '; // Link Statuses

    //Input Limitations:
    echo ' var in_outcome_max_length = ' . $this->config->item('in_outcome_max_length') . '; ';
    echo ' var ln_content_max_length = ' . $this->config->item('ln_content_max_length') . '; ';
    echo ' var en_name_max_length = ' . $this->config->item('en_name_max_length') . '; ';
    echo ' var random_loading_message = ' . json_encode(echo_random_message('ying_yang', true)) . '; ';
    echo ' var random_saving_message = ' . json_encode(echo_random_message('saving_notify', true)) . '; ';
    echo ' var js_advance_view_enabled = ' . ( $this->session->userdata('advance_view_enabled') ? 1 : 0 ) . '; ';
    echo ' var js_pl_id = ' . ( isset($session_en['en_id']) ? $session_en['en_id'] : 0 ) . '; ';

    ?>
</script>
<!-- END GLOBAL STATIC RESOURCES -->