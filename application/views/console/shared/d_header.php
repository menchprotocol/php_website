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
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	<title>Mench<?= ( isset($title) ? ' | '.$title : '' ) ?></title>

    <!-- Fonts/Icons -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato|Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Titillium+Web:700" />
	
	<!-- CSS -->
    <link href="/css/lib/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/lib/animate.css" rel="stylesheet" />
    <link href="/css/console/material-dashboard.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
    <link href="/css/front/material-kit.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
    <link href="/css/front/styles.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
    
    
    <!-- Core JS Files -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/showdown/1.7.2/showdown.min.js" type="text/javascript"></script>
	<script src="/js/console/jquery-3.1.0.min.js" type="text/javascript"></script>
	<script src="/js/console/bootstrap.min.js" type="text/javascript"></script>
	<script src="/js/console/material.min.js" type="text/javascript"></script>
	<script src="/js/console/material-dashboard.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
	<script src="/js/front/global.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
	<script type="text/javascript"> var u_status = <?= intval($udata['u_status']) ?>; </script>
</head>
<body>

	<div class="wrapper" id="console">
	
		<nav class="navbar navbar-transparent navbar-absolute" style="background-color:#8d8d8b !important;">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<span class="navbar-brand dashboard-logo"><a href="/console"><img src="/img/bp_48.png" /><span><?= $website['name'] ?></span></a></span>
				</div>
				
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="/console/account"><i class="fa fa-user" aria-hidden="true"></i> <?= $this->lang->line('my_profile') ?></a></li>
						<!-- <li><a href="/console/support"><i class="fa fa-life-ring" aria-hidden="true"></i> Support</a></li> -->
						<li><a href="/process/logout"><i class="fa fa-power-off" aria-hidden="true"></i> <?= $this->lang->line('logout') ?></a></li>
					</ul>
					<?php /*
					<form class="navbar-form navbar-left" role="search">
						<div class="form-group  is-empty">
                        	<input type="text" class="form-control" placeholder="Search">
                        	<span class="material-input"></span>
						</div>
                    </form>
                    */ ?>
				</div>
			</div>
		</nav>
		
	    <div class="sidebar" id="mainsidebar" data-color="purple">
	    	<div class="sidebar-wrapper">
            	<?php
            	if(isset($bootcamp) && $bootcamp){
            	    
            	    echo '<h1 class="c_objective" style="margin:15px 10px 10px 15px;">'.echo_title($bootcamp['c_objective']).'</h1>';
            	    echo '<input type="hidden" id="c_id" value="'.$this->uri->segment(2, 0).'" />';
            	    
            	    echo '<ul class="nav">';
            	    echo '<li'.( $_SERVER['REQUEST_URI'] == '/console/'.$bootcamp['c_id'] ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'"><i class="fa fa-tachometer" aria-hidden="true"></i><p>Dashboard</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['c_id'].'/content')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'/content" data-toggle="tooltip" title="'.$this->lang->line('cr_desc').'">'.$this->lang->line('cr_icon').'<p>'.$this->lang->line('cr_name').'</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['c_id'].'/cohorts')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'/cohorts">'.$this->lang->line('r_icon').'<p>'.$this->lang->line('r_pname').'</p></a></li>';
                	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['c_id'].'/community')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'/community"><i class="fa fa-users" aria-hidden="true"></i><p>Community</p></a></li>';
                	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['c_id'].'/timeline')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'/timeline"><i class="material-icons">timeline</i><p>Timeline</p></a></li>';
                	    
                	    echo '<li><a href="/bootcamps/'.$bootcamp['c_url_key'].'" target="_blank"><i class="fa fa-bullhorn" aria-hidden="true"></i><p>Landing Page &nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></p></a></li>';
            		echo '</ul>';
        		} else {
        		    //This enables the collapsed menu to show:
        		    echo '<ul class="nav"></ul>';
        		}
            	?>
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