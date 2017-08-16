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
	<title><?= ( isset($title) ? $title : $website['name'] ) ?></title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="/css/lib/bootstrap.min.css" rel="stylesheet" />

    <!--  Material Dashboard CSS    -->
    <link href="/css/dashboard/material-dashboard.css?v=<?= version_salt() ?>" rel="stylesheet"/>

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato|Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Titillium+Web:700" />
</head>
    
    <!-- Custom CSS -->
    <link href="/css/challenges/material-kit.css?v=<?= version_salt() ?>" rel="stylesheet"/>
    <link href="/css/challenges/styles.css?v=<?= version_salt() ?>" rel="stylesheet"/>
    
    
    <!--   Core JS Files   -->
	<script src="/js/dashboard/jquery-3.1.0.min.js" type="text/javascript"></script>
	<script src="/js/dashboard/bootstrap.min.js" type="text/javascript"></script>
	<script src="/js/dashboard/material.min.js" type="text/javascript"></script>

	<!-- Material Dashboard javascript methods -->
	<script src="/js/dashboard/material-dashboard.js"></script>
	
	<!-- Custom JS file -->
	<script src="/js/challenges/global.js?v=<?= version_salt() ?>" type="text/javascript"></script>

<body>

	<div class="wrapper">
	    <div class="sidebar" data-color="purple">

			<!--
		        Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

		        Tip 2: you can also add an image using data-image tag
		    -->

			<div class="logo" style="z-index:999999999;">
				<div class="btn-group bootstrap-select show-tick">
					<button type="button" class="btn dropdown-toggle bs-placeholder form-control" data-toggle="dropdown" role="button" title="Choose City" aria-expanded="false">
						<span class="filter-option pull-left"><i class="fa fa-rocket"></i> &nbsp;Choose Challenge</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span><div class="ripple-container"><div class="ripple ripple-on ripple-out" style="left: 453px; top: 2887px; background-color: rgb(60, 72, 88); transform: scale(26.7188);"></div><div class="ripple ripple-on ripple-out" style="left: 459px; top: 2883px; background-color: rgb(60, 72, 88); transform: scale(26.7188);"></div><div class="ripple ripple-on ripple-out" style="left: 457px; top: 2887px; background-color: rgb(60, 72, 88); transform: scale(26.7188);"></div></div></button>
					
					<div class="dropdown-menu open" role="combobox" style="max-height: 273px; overflow: hidden;">
						<ul class="dropdown-menu inner" role="listbox" aria-expanded="false" style="max-height: 273px; overflow-y: auto;">
							<li data-original-index="2"><a href="/challenge/load/4445" tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text"><i class="fa fa-plus-circle"></i>&nbsp; New Challenge </span></a></li>
							<li data-original-index="1"><a href="/challenge/load/4445" tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text"><i class="fa fa-rocket"></i> Create Online Course </span></a></li>
							<li data-original-index="2"><a href="/challenge/load/4445" tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text"><i class="fa fa-rocket"></i> X 2 </span></a></li>
						</ul>
					</div>		
				</div>
			</div>
			
			<div class="logo" style="z-index:999999988;">
				<div class="btn-group bootstrap-select show-tick">
					<button type="button" class="btn dropdown-toggle bs-placeholder form-control" data-toggle="dropdown" role="button" title="Choose City" aria-expanded="false">
						<span class="filter-option pull-left"><i class="fa fa-code-fork" style="margin: 0 2px 0 4px;"></i> &nbsp;Choose Run</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span><div class="ripple-container"><div class="ripple ripple-on ripple-out" style="left: 453px; top: 2887px; background-color: rgb(60, 72, 88); transform: scale(26.7188);"></div><div class="ripple ripple-on ripple-out" style="left: 459px; top: 2883px; background-color: rgb(60, 72, 88); transform: scale(26.7188);"></div><div class="ripple ripple-on ripple-out" style="left: 457px; top: 2887px; background-color: rgb(60, 72, 88); transform: scale(26.7188);"></div></div></button>
					
					<div class="dropdown-menu open" role="combobox" style="max-height: 273px; overflow: hidden;">
						<ul class="dropdown-menu inner" role="listbox" aria-expanded="false" style="max-height: 273px; overflow-y: auto;">
							<li data-original-index="2"><a href="/challenge/load/4445" tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text"><i class="fa fa-plus-circle"></i>&nbsp; New Run </span></a></li>
							<li data-original-index="2"><a href="/challenge/load/4445" tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text"><i class="fa fa-code-fork"></i> #2 Pending Date </span></a></li>
							<li data-original-index="1"><a href="/challenge/load/4445" tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text"><i class="fa fa-code-fork"></i> #1 Aug 12 2017 </span></a></li>
						</ul>
					</div>		
				</div>
			</div>

	    	<div class="sidebar-wrapper">
	            <ul class="nav">
	                <li class="active"><a href="/dashboard"><i class="material-icons">dashboard</i><p>Dashboard</p></a></li>
	                <li><a href="/dashboard/leaderboard"><i class="material-icons">people</i><p>Leaderboard</p></a></li>
	                <li><a href="/dashboard/activity"><i class="material-icons">visibility</i><p>Activity <span class="notification">5</span></p></a></li>
	                <li><a href="/dashboard/settings"><i class="fa fa-rocket"></i><p>Challenge Settings</p></a></li>
	                <li><a href="/dashboard/settings"><i class="fa fa-code-fork"></i><p>Run Settings</p></a></li>
	            </ul>
	    	</div>
		</div>

	    <div class="main-panel">
	    	
			
			
			
			
			<nav class="navbar navbar-transparent navbar-absolute" style="background-color:#fedd16 !important;">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<span class="navbar-brand dashboard-logo"><img src="/img/bp_48.png" /><span>mench</span></span>
					</div>
					
					<div class="collapse navbar-collapse">
						<ul class="nav navbar-nav navbar-right">
							<li><a href="/account"><i class="fa fa-user" aria-hidden="true"></i> MY PROFILE</a></li>
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

	        <div class="content dash">
	            <div class="container-fluid">