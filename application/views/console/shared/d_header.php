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
    <link href="/css/lib/jquery-ui.min.css" rel="stylesheet" />
    <link href="/css/console/material-dashboard.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
    <link href="/css/front/material-kit.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
    <link href="/css/front/styles.css?v=v<?= $website['version'] ?>" rel="stylesheet" />
    
    
    <!-- Core JS Files -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/showdown/1.7.2/showdown.min.js" type="text/javascript"></script>
	<script src="/js/console/jquery-3.1.0.min.js" type="text/javascript"></script>
	<script src="/js/lib/jquery-ui.min.js" type="text/javascript"></script>
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
	
		<nav class="navbar navbar-transparent navbar-absolute">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<span class="navbar-brand dashboard-logo">
						<a href="/console">
						<img src="/img/bp_48.png" style="margin-top:-11px !important;" />
						<span><?= $website['name'] ?></span>
						</a>
						<!-- <input type="text" placeholder="Search"> -->
					</span>
				</div>
				
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<!-- <li><a href="/console/support"><i class="fa fa-question-circle" aria-hidden="true"></i><span> Support</span></a></li> -->
						<li><a href="/console/account"><i class="fa fa-user-circle" aria-hidden="true"></i> Account</a></li>
						<li><a href="/process/logout"><i class="fa fa-power-off" aria-hidden="true"></i><span> Logout</span></a></li>
					</ul>
				</div>
			</div>
		</nav>
		
	    <div class="sidebar" id="mainsidebar" data-color="purple">
	    	<div class="sidebar-wrapper">
	    	
	    		<?php 
	    		if(isset($bootcamp)){
	    		    echo '<div class="left-li-title">'.status_bible('c',$bootcamp['c_status'],1).' '.$bootcamp['c_objective'].' <a href="/bootcamps/'.$bootcamp['c_url_key'].'" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Open Landing Page"><i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a></div>';
	    		}
	    		?>
	    		<ul class="nav">
	    		
	    			
	    			<?php /*
	    			<li>
                        <a data-toggle="collapse" href="#bootcampList" class="left-navi-title collapsed" aria-expanded="false">
                            <?= (isset($bootcamp) ? $bootcamp['c_objective'] : 'Select Bootcamp' ) ?>
                            <b class="caret"></b>
                        </a>
                        <div class="collapse" id="bootcampList" aria-expanded="false" style="height: 0px;">
                            <ul class="nav">
                           		<?php
                           		//Fetch all bootcamps for this user:
                           		$u_bootcamps = $this->Db_model->u_bootcamps(array(
                               		'ba.ba_u_id' => $udata['u_id'],
                               		'ba.ba_status >=' => 0,
                               		'c.c_status >=' => 0,
                               		'c.c_is_grandpa' => true, //Not sub challenges
                           		));
                           		
                           		foreach($u_bootcamps as $ub){
                           		    if(isset($bootcamp) && $ub['c_id']==$bootcamp['c_id']){
                           		        continue;
                           		    }
                           		    
                           		    echo '<li><a href="/console/'.$ub['c_id'].'" class="left-navi-title">'.$ub['c_objective'].'</a></li>';
                           		}
								
								echo '<li><a href="/marketplace/run/new"><i class="fa fa-plus"></i> New Bootcamp</a></li>';
								?>
                            </ul>
                        </div>
                    </li>
                    */?>
                    
            	<?php
            	if(isset($bootcamp)){
            	    
            	    echo '<li  class="li-sep '.( $_SERVER['REQUEST_URI'] == '/console/'.$bootcamp['c_id'] ? 'active' : '' ).'"><a href="/console/'.$bootcamp['c_id'].'"><i class="fa fa-tachometer" aria-hidden="true"></i><p>Dashboard</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['c_id'].'/curriculum')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'/curriculum">'.$this->lang->line('cr_icon').'<p>'.$this->lang->line('cr_name').'</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['c_id'].'/cohorts')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'/cohorts">'.$this->lang->line('r_icon').'<p>'.$this->lang->line('r_pname').'</p></a></li>';
                	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['c_id'].'/students')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'/students"><i class="fa fa-users" aria-hidden="true"></i><p>Students</p></a></li>';
                	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['c_id'].'/stream')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'/stream"><i class="material-icons">forum</i><p>Activity Stream</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['c_id'].'/settings')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['c_id'].'/settings"><i class="material-icons">settings</i><p>Settings</p></a></li>';
            	    
        		}
            	?>
            	</ul>
	    	</div>
		</div>


	    <div class="main-panel">
	        <div class="content dash">
	        
    	        <?php 
    	        if(isset($breadcrumb)){
    	            echo '<ol class="breadcrumb">';
    	            foreach($breadcrumb as $link){
    	                if($link['link']){
    	                    echo '<li><a href="'.$link['link'].'">'.$link['anchor'].'</a></li>';
    	                } else {
    	                    echo '<li>'.$link['anchor'].'</li>';
    	                }
    	            }
    	            echo '</ol>';
    	        }
    	        ?>
    	        
    	        
	            <div class="container-fluid">
	            <?php 
	            if(isset($message)){
	                echo $message;
	            }
	            $hm = $this->session->flashdata('hm');
	            if($hm){
	            	echo $hm;
	            }
	            ?>