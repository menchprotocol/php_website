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
	<title>Mench<?= ( isset($title) ? ' | '.$title : '' ) ?></title>

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
	<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
	<script src="/js/front/global.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
	<script type="text/javascript"> var u_status = <?= intval($udata['u_status']) ?>; </script>
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
					<span class="navbar-brand dashboard-logo"><a href="/marketplace"><img src="/img/bp_48.png" /><span><?= $website['name'] ?></span><i>v<?= $website['version'] ?></i></a></span>
				</div>
				
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<?php /*
						<li><a href="/contact"><i class="fa fa-question-circle" aria-hidden="true"></i> HELP</a></li>
						<li><a href="/user/<?= $udata['u_url_key'] ?>"><i class="fa fa-user" aria-hidden="true"></i> <?= $this->lang->line('my_profile') ?></a></li>
						*/ ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<i class="material-icons">view_carousel</i> Guides
								<b class="caret"></b>
							<div class="ripple-container"></div></a>
							<ul class="dropdown-menu dropdown-with-icons">
								<li><a href="/guides/showdown_markup">Markup Syntax</a></li>
								<li><a href="/guides/status_bible">Status Bible</a></li>
							</ul>
						</li>
						<li id="isloggedin"><a href="/logout"><i class="fa fa-power-off" aria-hidden="true"></i> <?= $this->lang->line('logout') ?></a></li>
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
	            <ul class="nav">
	            	<?php
	            	if(isset($challenge) && $challenge){
	            		
	            		echo '<li'.( '/marketplace/'.$challenge['c_id']==$_SERVER['REQUEST_URI'] ? ' class="active"' : '' ).'><a href="/marketplace/'.$challenge['c_id'].'"><i class="fa fa-tachometer" aria-hidden="true"></i><p>Dashboard</p></a></li>';
	            		
	            		echo '<li'.( '/marketplace/'.$challenge['c_id'].'/sprints'==$_SERVER['REQUEST_URI'] ? ' class="active"' : '' ).'><a href="/marketplace/'.$challenge['c_id'].'/sprints"><i class="fa fa-list-ul" aria-hidden="true"></i><p>Weekly Sprints</p></a></li>';
	            		
	            		echo '<li'.( '/marketplace/'.$challenge['c_id'].'/users'==$_SERVER['REQUEST_URI'] ? ' class="active"' : '' ).'><a href="/marketplace/'.$challenge['c_id'].'/users"><i class="fa fa-users" aria-hidden="true"></i><p>Users</p></a></li>';
	            		
	            		
	            		echo '<li'.( '/marketplace/'.$challenge['c_id'].'/activity'==$_SERVER['REQUEST_URI'] ? ' class="active"' : '' ).'><a href="/marketplace/'.$challenge['c_id'].'/activity"><i class="fa fa-history" aria-hidden="true"></i><p>Activity</p></a></li>';
	            		
	            		echo '<li'.( '/marketplace/'.$challenge['c_id'].'/settings'==$_SERVER['REQUEST_URI'] ? ' class="active"' : '' ).'><a href="/marketplace/'.$challenge['c_id'].'/settings"><i class="fa fa-cog" aria-hidden="true"></i><p>Settings</p></a></li>';
	            		
	            		echo '<li><a href="/bootcamp/'.$challenge['c_url_key'].'" target="_blank"><i class="fa fa-bullhorn" aria-hidden="true"></i><p>Landing Page <i class="fa fa-external-link" aria-hidden="true"></i></p></a></li>';
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