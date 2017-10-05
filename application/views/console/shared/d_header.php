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
	<?php $this->load->view('console/shared/header_resources' ); ?>
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