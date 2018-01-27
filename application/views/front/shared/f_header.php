<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$uadmission = $this->session->userdata('uadmission');
$website = $this->config->item('website');
$url_part_1 = $this->uri->segment(1);
?><!doctype html>
<html lang="en">
<head>
    <!--

    WELCOME TO MENCH SOURCE CODE!

    INTERESTED IN HELPING US BUILD THE FUTURE OF EDUCATION?

    YOU CAN WORK WITH US AS AN INDEPENDENT CONTRACTOR FROM ANYWHERE IN THE WORLD.

    AND IF YOU ACHIEVE THE MENCH STATUS, YOU CAN ALSO APPLY TO WORK AS A CORE TEAM MEMBER.

    -->
	<meta charset="utf-8" />
	<meta name="robots" content="noindex">
	<link rel="icon" type="image/png" href="/img/bp_16.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?= $website['name'].( isset($title) ? ' | '.$title : '' ) ?></title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	
	<?php $this->load->view('front/shared/header_resources' ); ?>

	<script src="/js/front/global.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
	
	<?php /* if(isset($udata['u_email'])){ ?>
	    <script> zE( function () { zE.identify({name: '<?= $udata['u_fname'] ?> <?= $udata['u_lname'] ?>', email: '<?= $udata['u_email'] ?>'}); }); </script>
	<?php } */ ?>
	
	<?php if(isset($r_fb_pixel_id) && strlen($r_fb_pixel_id)>1){ echo echo_facebook_pixel($r_fb_pixel_id); } ?>
	
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
                    if(isset($udata['u_id'])){
                        echo '<li id="isloggedin"><a href="/console">Console <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></li>';
                    } elseif(isset($uadmission['u_id'])){
                        echo '<li id="isloggedin"><a href="/my/actionplan">Student Hub <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></li>';
                    } elseif(!($url_part_1=='login')) {
                        //Logged Out!
    				    echo '<li><a href="/login"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a></li>';
    				} else {
                        //This is the login page, show the Launch Button:
                        //echo '<li><a href="/launch"><i class="fa fa-rocket" aria-hidden="true"></i> Launch a Bootcamp</a></li>';
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
    $this->load->view($landing_page , ( isset($lp_variables) ? $lp_variables : null ) );
    
} else {
	//Regular content page:
	echo '<div class="main main-raised main-plain">';
	echo '<div class="container body-container">';
	
	$hm = $this->session->flashdata('hm');
	if($hm){
	    echo $hm;
	}
}

if(isset($message)){
    echo $message;
}
?>