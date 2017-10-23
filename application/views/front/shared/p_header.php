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
	
	<?php $this->load->view('front/shared/header_resources' ); ?>
	
	<script>
	//Google Analytics:
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-92774608-1', 'auto');
	ga('send', 'pageview');
	</script>
	
</head>

<body id="funnel">

<div class="main main-raised">
<div class="container body-container">

<?php
if(isset($hm) && $hm){
    echo $hm;
} else {
    $hm = $this->session->flashdata('hm');
    if($hm){
        echo $hm;
    }
}

?>