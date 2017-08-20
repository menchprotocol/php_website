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
    <link href="/css/marketplace/material-dashboard.css?v=<?= version_salt() ?>" rel="stylesheet" />
    <link href="/css/front/material-kit.css?v=<?= version_salt() ?>" rel="stylesheet" />
    <link href="/css/front/styles.css?v=<?= version_salt() ?>" rel="stylesheet" />
    
    
    <!-- Core JS Files -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/showdown/1.7.2/showdown.min.js" type="text/javascript"></script>
	<script src="/js/marketplace/jquery-3.1.0.min.js" type="text/javascript"></script>
	<script src="/js/marketplace/bootstrap.min.js" type="text/javascript"></script>
	<script src="/js/marketplace/material.min.js" type="text/javascript"></script>
	<script src="/js/marketplace/material-dashboard.js" type="text/javascript"></script>
	<script src="/js/front/global.js?v=<?= version_salt() ?>" type="text/javascript"></script>
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
						<li><a href="/contact"><i class="fa fa-question-circle" aria-hidden="true"></i> HELP</a></li>
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
	            <ul class="nav" style="margin-top:33px;">
	            	<?php
	            	if(isset($challenge) && $challenge){
            			//Challenge Nav:
            			echo dash_li('/marketplace/'.$challenge['c_url_key'] , '<i class="fa fa-rocket"></i><p>OVERVIEW</p>');
            			echo dash_li('/marketplace/'.$challenge['c_url_key'].'/library/'.$challenge['c_id'] , '<i class="fa fa-book"></i><p>LIBRARY</p>');
            			echo dash_li('/marketplace/'.$challenge['c_url_key'].'/editcopy' , '<i class="fa fa-pencil"></i><p>EDIT COPY</p>');
            		}
	            	?>
	            </ul>
	            
	            
	            
	            <?php if(isset($run) && $run){ ?>
					<div class="logo" style="z-index:999999988;">
						<div class="btn-group bootstrap-select show-tick">
							<button type="button" class="btn dropdown-toggle bs-placeholder form-control" data-toggle="dropdown" role="button" title="Choose City" aria-expanded="false">
								<span class="filter-option pull-left"><i class="fa fa-code-fork" style="margin: 0 2px 0 4px;"></i> &nbsp;<?= isset($run) ? 'RUN #'.$run['r_version'] : 'RUNS' ?></span>&nbsp;<span class="bs-caret"><span class="caret"></span></span><div class="ripple-container"><div class="ripple ripple-on ripple-out" style="left: 453px; top: 2887px; background-color: rgb(60, 72, 88); transform: scale(26.7188);"></div><div class="ripple ripple-on ripple-out" style="left: 459px; top: 2883px; background-color: rgb(60, 72, 88); transform: scale(26.7188);"></div><div class="ripple ripple-on ripple-out" style="left: 457px; top: 2887px; background-color: rgb(60, 72, 88); transform: scale(26.7188);"></div></div></button>
							
							<div class="dropdown-menu open" role="combobox" style="max-height: 273px; overflow: hidden;">
								<ul class="dropdown-menu inner" role="listbox" aria-expanded="false" style="max-height: 273px; overflow-y: auto;">
									<li><a href="/marketplace/<?= $challenge['c_url_key'] ?>/new" tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text"><i class="fa fa-plus"></i> NEW RUN</span></a></li>
									<?php
									//Display Challenges, which MUST be here, if any!
									if(count($challenge['runs'])>0){
										foreach($challenge['runs'] as $r){
											echo '<li><a href="/marketplace/'.$challenge['c_url_key'].'/'.$r['r_version'].'" tabindex="0" class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text"><i class="fa fa-code-fork"></i> RUN #'.$r['r_version'].'</span></a></li>';
										}
									} else {
										echo '<li class="li-notify"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No Runs created, yet.</li>';
									}
									?>
								</ul>
							</div>
						</div>
					</div>
					
					<?php 
					//Run Nav:
					echo '<ul class="nav" style="margin-top:0;">';
					echo dash_li('/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'] , '<i class="material-icons">dashboard</i><p>DASHBOARD</p>');
					echo dash_li('/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'].'/leaderboard' , '<i class="material-icons">people</i><p>LEADERBOARD</p>');
					echo dash_li('/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'].'/timeline' , '<i class="fa fa-calendar"></i><p>TIMELINE</p>');
					echo dash_li('/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'].'/settings' , '<i class="fa fa-cog"></i><p>SETTINGS</p>');
					echo '</ul>';
					?>
					
				<?php } ?>
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