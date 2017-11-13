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
	
	<?php if(isset($udata['u_email'])){ ?>
	<!-- Zendesk Autofill -->
    <script>
    zE( function () { zE.identify({name: '<?= $udata['u_fname'] ?> <?= $udata['u_lname'] ?>', email: '<?= $udata['u_email'] ?>'}); });
	</script>
	<?php } ?>
	
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
    				//echo '<li><a href="/bootcamps"><i class="fa fa-search" aria-hidden="true"></i> Browse</a></li>';
    				if(isset($udata['u_id'])){
    					echo '<li id="isloggedin"><a href="/console">'.$this->lang->line('m_name').' <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a></li>';
    				} else {
    				    echo '<li><a href="/launch"><i class="fa fa-rocket" aria-hidden="true"></i> Launch Bootcamp</a></li>';
    				    echo '<li><a href="/login"><i class="fa fa-sign-in" aria-hidden="true"></i> Instructor Login</a></li>';
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