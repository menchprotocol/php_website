<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$challenges = $this->session->userdata('challenges');
$website = $this->config->item('website');
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="/img/bp_16.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	<title>Mench | <?= ( isset($title) ? $title : $website['name'] ) ?></title>

    <!-- Fonts/Icons -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato|Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Titillium+Web:700" />
	
	<!-- CSS -->
    <link href="/css/lib/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/lib/animate.css" rel="stylesheet" />
    <link href="/css/marketplace/material-dashboard.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
    <link href="/css/front/material-kit.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
    <link href="/css/front/styles.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
    
    
    <!-- Core JS Files -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/showdown/1.7.2/showdown.min.js" type="text/javascript"></script>
	<script src="/js/marketplace/jquery-3.1.0.min.js" type="text/javascript"></script>
	<script src="/js/marketplace/bootstrap.min.js" type="text/javascript"></script>
	<script src="/js/marketplace/material.min.js" type="text/javascript"></script>
	<script src="/js/marketplace/material-dashboard.js" type="text/javascript"></script>
	<script src="/js/front/global.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
</head>
<body>

	<div class="wrapper" id="marketplace">
	
		<nav class="navbar navbar-transparent navbar-absolute" style="background-color:#8d8d8b !important;">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<span class="navbar-brand dashboard-logo"><a href="/marketplace"><img src="/img/bp_48.png" /><span>mench</span></a></span>
				</div>
				
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<?php // <li><a href="/contact"><i class="fa fa-question-circle" aria-hidden="true"></i> HELP</a></li> ?>
						<li><a href="/user/<?= $udata['u_url_key'] ?>/edit"><i class="fa fa-user" aria-hidden="true"></i> MY PROFILE</a></li>
						<li id="isloggedin"><a href="#" id="logoutbutton"><i class="fa fa-power-off" aria-hidden="true"></i> LOGOUT</a></li>
					</ul>
					<?php /*
					<form class="navbar-form navbar-right" role="search">
						<div class="form-group  is-empty">
                        	<input type="text" class="form-control" placeholder="Search">
                        	<span class="material-input"></span>
						</div>
						<button type="submit" class="btn btn-white btn-round btn-just-icon">
							<i class="material-icons">search</i><div class="ripple-container"></div>
						</button>
                    </form>
                    */ ?>
				</div>
			</div>
		</nav>
		
	    <div class="sidebar" id="mainsidebar" data-color="purple">
	    	<div class="sidebar-wrapper">
	            <ul class="nav" style="margin:33px 0;">
	            	<?php
	            	if(isset($challenge) && $challenge){
            			//Challenge Nav:
            			echo dash_li('/marketplace/'.$challenge['c_url_key'] , '<i class="fa fa-rocket"></i><p>OVERVIEW</p>');
            			echo dash_li('/marketplace/'.$challenge['c_url_key'].'/syllabus/'.$challenge['c_id'] , '<i class="fa fa-book"></i><p>SYLLABUS</p>');
            			?>
            			
            			<li>
	                        <a data-toggle="collapse" href="#pagesExamples" class="collapsed" aria-expanded="false">
	                            <?= ( isset($run) ? run_ver($run['r_version']).'<p> 17/Aug/2017' : '<i class="fa fa-code-fork"></i><p> RUNS' ) ?>
	                                <b class="caret"></b>
	                            </p>
	                        </a>
	                        <div class="collapse" id="pagesExamples" aria-expanded="false" style="height: 0px;">
	                            <ul class="nav">
	                           		<?php
									//Display Challenges, which MUST be here, if any!
									if(count($challenge['runs'])>0){
										foreach($challenge['runs'] as $r){
											echo '<li class="'.( isset($run) && $run['r_version']==$r['r_version'] ? 'active' : '' ).'"><a href="/marketplace/'.$challenge['c_url_key'].'/'.$r['r_version'].'">'.run_ver($r['r_version']).' 17/Aug/2017</a></li>';
										}
									} else {
										echo '<li class="li-notify"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No Runs created, yet.</li>';
									}
									
									if(1){
										echo '<li><a href="/marketplace/'.$challenge['c_url_key'].'/'.$r['r_version'].'"><i class="fa fa-plus"></i> NEW RUN</a></li>';
									}
									?>
	                            </ul>
	                        </div>
	                    </li>
	                    
	                    <?php
						//Run Nav:
						if(isset($run) && $run){
							echo dash_li('/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'] , '<i class="material-icons">dashboard</i><p>DASHBOARD</p>');
							echo dash_li('/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'].'/leaderboard' , '<i class="material-icons">people</i><p>LEADERBOARD</p>');
							echo dash_li('/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'].'/timeline' , '<i class="fa fa-calendar"></i><p>TIMELINE</p>');
							echo dash_li('/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'].'/settings' , '<i class="fa fa-cog"></i><p>SETTINGS</p>');
						}
						
            		}
	            	?>
	            </ul>
	            
	            
	            
			            
	    	</div>
		</div>


	    <div class="main-panel">
	        <div class="content dash">
	            <div class="container-fluid">
	            
	            <?php 
	            $hm = $this->session->flashdata('hm');
	            if($hm){
	            	echo $hm;
	            }
	            ?>