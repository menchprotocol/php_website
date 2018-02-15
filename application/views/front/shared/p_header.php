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

    <script src="/js/front/global.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>

	<?php if(isset($r_fb_pixel_id) && strlen($r_fb_pixel_id)>1){ echo echo_facebook_pixel($r_fb_pixel_id,(isset($purchase_value) ? $purchase_value : 0)); } ?>
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