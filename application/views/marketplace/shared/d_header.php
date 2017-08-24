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
						<li id="isloggedin"><a href="#" id="logoutbutton"><i class="fa fa-power-off" aria-hidden="true"></i> <?= $this->lang->line('logout') ?></a></li>
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
	            	if(isset($challenge) && $challenge && 0){
            			//Challenge Nav:
	            		echo '<li'.( '/marketplace/'.$challenge['c_id']==$_SERVER['REQUEST_URI'] ? ' class="active"' : '' ).'><a href="/marketplace/'.$challenge['c_id'].'">'.$this->lang->line('c_icon').'<p>'.$this->lang->line('c_name').'</p></a></li>';
	            		
            			?>
            			
            			
            			<li>
	                        <a data-toggle="collapse" href="#pagesExamples" class="collapsed" aria-expanded="false">
	                            <?= ( isset($run) ? run_icon($run['r_version']).'<p> '.time_format($run['r_kickoff_time'],true).' '.$this->lang->line('r_name') : $this->lang->line('r_icon').'<p> '.$this->lang->line('r_pname') ) ?>
	                                <b class="caret"></b>
	                            </p>
	                        </a>
	                        <div class="collapse" id="pagesExamples" aria-expanded="false" style="height: 0px;">
	                            <ul class="nav">
	                           		<?php
	                           		//Display Challenges, which MUST be here, if any!
									if(count($challenge['runs'])>0){
										foreach($challenge['runs'] as $r){
											//if(isset($run) && $run['r_version']==$r['r_version']){continue;}
											echo '<li class="'.( isset($run) && $run['r_version']==$r['r_version'] ? 'active' : '' ).'"><a href="/marketplace/'.$challenge['c_id'].'/run/'.$r['r_version'].'">'.run_icon($r['r_version']).' '.time_format($r['r_kickoff_time'],true).' '.status_bible('r',$r['r_status']).'</a></li>';
										}
									} else {
										echo '<li class="li-notify"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '.$this->lang->line('r_none_message').'</li>';
									}
									
									if(can_modify('c',$challenge['c_id'])){
										echo '<li class="'.( '/marketplace/'.$challenge['c_id'].'/new'==$_SERVER['REQUEST_URI'] ? 'active' : '' ).'"><a href="/marketplace/'.$challenge['c_id'].'/run/new"><i class="fa fa-plus"></i> '.$this->lang->line('new').' '.$this->lang->line('r_name').'</a></li>';
									}
									?>
	                            </ul>
	                        </div>
	                    </li>
	                   
	                    
	                    
	                    <?php
	                    //Run Nav:
						if(isset($run) && $run){
							//Run:
							echo '<li class="submenu '.( '/marketplace/'.$challenge['c_id'].'/run/'.$run['r_version']==$_SERVER['REQUEST_URI'] ? 'active' : '' ).'"><a href="/marketplace/'.$challenge['c_id'].'/run/'.$run['r_version'].'">'.'<p>'.$this->lang->line('r_d_name').' <i class="fa fa-chevron-right" aria-hidden="true"></i></p>'.'</a></li>';
							//Run Sub-menu
							echo '<li class="submenu '.( '/marketplace/'.$challenge['c_id'].'/run/'.$run['r_version'].'/activity'==$_SERVER['REQUEST_URI'] ? 'active' : '' ).'"><a href="/marketplace/'.$challenge['c_id'].'/run/'.$run['r_version'].'/activity'.'">'.'<p>'.$this->lang->line('r_a_name').' <i class="fa fa-chevron-right" aria-hidden="true"></i></p>'.'</a></li>';
							echo '<li class="submenu '.( '/marketplace/'.$challenge['c_id'].'/run/'.$run['r_version'].'/leaderboard'==$_SERVER['REQUEST_URI'] ? 'active' : '' ).'"><a href="/marketplace/'.$challenge['c_id'].'/run/'.$run['r_version'].'/leaderboard'.'">'.'<p>'.$this->lang->line('r_l_name').' <i class="fa fa-chevron-right" aria-hidden="true"></i></p>'.'</a></li>';
							echo '<li class="submenu '.( '/marketplace/'.$challenge['c_id'].'/run/'.$run['r_version'].'/settings'==$_SERVER['REQUEST_URI'] ? 'active' : '' ).'"><a href="/marketplace/'.$challenge['c_id'].'/run/'.$run['r_version'].'/settings'.'">'.'<p>'.$this->lang->line('r_s_name').' <i class="fa fa-chevron-right" aria-hidden="true"></i></p>'.'</a></li>';
						}
            		} else {
            			echo '<p style="padding:15px;">v0.20 Navigation Goes Here...</p>';
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