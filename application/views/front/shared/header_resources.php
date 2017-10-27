<?php 
//Attempt to fetch session variables:
$website = $this->config->item('website');
?>

<!-- START SHARED RESOURCES -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato|Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Titillium+Web:700" />

<!-- CSS -->
<link href="/css/lib/bootstrap.min.css" rel="stylesheet" />
<link href="/css/lib/animate.css" rel="stylesheet" />
<link href="/css/lib/jquery-ui.min.css" rel="stylesheet" />
<link href="/css/console/material-dashboard.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
<link href="/css/front/material-kit.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
<link href="/css/front/styles.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
<link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css" rel="stylesheet">
<link href="//cdn.quilljs.com/1.3.3/quill.snow.css" rel="stylesheet">

<!-- Core JS Files -->
<script src="/js/console/jquery-3.1.0.min.js" type="text/javascript"></script>
<script src="/js/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/js/console/bootstrap.min.js" type="text/javascript"></script>
<script src="/js/console/material.min.js" type="text/javascript"></script>
<script src="/js/console/material-dashboard.js" type="text/javascript"></script>
<script src="/js/lib/jquery.countdownTimer.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script src="//cdn.quilljs.com/1.3.3/quill.min.js"></script>
<!-- END SHARED RESOURCES -->