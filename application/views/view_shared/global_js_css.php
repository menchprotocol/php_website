<!-- START GLOBAL STATIC RESOURCES -->
<link rel="stylesheet" type="text/css"
      href="//fonts.googleapis.com/css?family=Lato|Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Titillium+Web:700"/>
<link href="/css/lib/fa/fontawesome.css?v=5.7.1" rel="stylesheet"/>
<link href="/css/lib/fa/brands.css?v=5.7.1" rel="stylesheet"/>
<link href="/css/lib/fa/solid.css?v=5.7.1" rel="stylesheet"/>
<link href="/css/lib/fa/regular.css?v=5.7.1" rel="stylesheet"/>
<link href="/css/lib/fa/light.css?v=5.7.1" rel="stylesheet"/>

<!-- CSS -->
<link href="/css/lib/bootstrap.min.css" rel="stylesheet"/>
<link href="/css/lib/jquery-ui.min.css" rel="stylesheet"/>
<link href="/css/lib/simplebar.css" rel="stylesheet"/>
<link href="/css/lib/material-dashboard.css" rel="stylesheet"/>
<link href="/css/lib/material-kit.css" rel="stylesheet"/>
<link href="/css/styles.css?v=v<?= $this->config->item('app_version') ?>" rel="stylesheet"/>


<!-- Core JS File/Variables -->
<script src="/js/lib/jquery-3.1.0.min.js" type="text/javascript"></script>
<script src="/js/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/js/lib/bootstrap.min.js" type="text/javascript"></script>
<script src="/js/lib/material.min.js" type="text/javascript"></script>
<script src="/js/lib/material-dashboard.js" type="text/javascript"></script>
<script src="/js/lib/simplebar.js"></script>
<script src="/js/lib/jquery.textcomplete.min.js"></script>
<script src="/js/lib/autocomplete.jquery.min.js"></script>
<script src="/js/lib/algoliasearch.min.js"></script>
<script src="/js/lib/sortable.min.js" type="text/javascript"></script>
<script src="/js/custom/global-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>

<script>
    <?php
    //Translate key variables into JS variables to have them available throughout all JS functions:
    echo ' var is_compact = (is_mobile() || $(window).width() < 767); '; //Manages UI view based on browse width (For example removed fixed right column for mobile)
    echo ' var object_js_statuses = ' . json_encode($this->config->item('fixed_fields')) . '; ';
    echo ' var in_outcome_max = ' . $this->config->item('in_outcome_max') . '; ';
    echo ' var tr_content_max_length = ' . $this->config->item('tr_content_max_length') . '; ';
    echo ' var en_name_max_length = ' . $this->config->item('en_name_max_length') . '; ';
    ?>
</script>
<!-- END GLOBAL STATIC RESOURCES -->