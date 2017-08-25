<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$website = $this->config->item('website');
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="/img/bp_16.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?= $website['name'].( isset($title) ? ' | '.$title : '' ) ?></title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

	<!-- Fonts/Icons -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato|Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Titillium+Web:700" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

	<!-- CSS -->
    <link href="/css/lib/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/lib/animate.css" rel="stylesheet" />
    <link href="/css/front/material-kit.css?v=v<?= $website['version'] ?>" rel="stylesheet"/>
    <link href="/css/front/styles.css?v=v<?= $website['version'] ?>" rel="stylesheet"/>
    
    <!-- JS -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/showdown/1.7.2/showdown.min.js" type="text/javascript"></script>
	<script src="/js/lib/jquery.min.js" type="text/javascript"></script>
	<script src="/js/lib/bootstrap.min.js" type="text/javascript"></script>
	<script src="/js/lib/material.min.js"></script>	
	<script src="/js/lib/moment.min.js"></script>
	<script src="/js/lib/jasny-bootstrap.min.js"></script>
	<script src="/js/lib/morphext.min.js"></script>
	<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
	<script src="/js/front/material-kit.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
	<script src="/js/front/global.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
	<script type="text/javascript"> var u_status = <?= @intval($udata['u_status']) ?>; </script>
</head>

<body class="landing-page">
    <nav class="navbar navbar-warning navbar-fixed-top navbar-color-on-scroll <?= ( isset($landing_page) ? 'navbar-transparent': 'no-adj') ?>">
    	<div class="container">
        	<!-- Brand and toggle get grouped for better mobile display -->
        	<div class="navbar-header">
        		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example">
            		<span class="sr-only">Toggle navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
        		</button>
        		<a class="navbar-brand" href="/"><img src="/img/bp_48.png" /><span><?= $website['name'] ?></span></a>
        	</div>

        	<div class="collapse navbar-collapse">
        		<ul class="nav navbar-nav navbar-right">
    				<?php
    				//<li><a href="/features">Features</a></li>
    				//<li><a href="/pricing">Pricing</a></li>
    				if(isset($udata['u_id'])){
    					echo '<li id="isloggedin"><a href="/marketplace">'.$this->lang->line('m_name').' <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></li>';
    					echo '<li><a href="#" id="logoutbutton">'.$this->lang->line('logout').' <i class="fa fa-power-off" aria-hidden="true"></i></a></li>';
    				} else {
    					echo '<li><a href="https://mench.typeform.com/to/nh4s2u">'.$this->lang->line('signup').' <i class="fa fa-sign-in"></i></a></li>';
    				}
    				?>
        		</ul>
        	</div>
    	</div>
    </nav>
    
<?php
//Any landing pages?
if(isset($landing_page)){
	//Yes, load the page:
	$this->load->view($landing_page);
	
	//Load landing page containers:
	echo '<div class="main main-raised">';
	echo '<div class="container body-container">';
} else {
	//Regular content page:
	echo '<div class="main main-raised main-plain">';
	echo '<div class="container body-container">';
}
?>