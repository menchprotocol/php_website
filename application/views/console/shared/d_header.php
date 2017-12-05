<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$website = $this->config->item('website');
$mench_bots = $this->config->item('mench_bots');
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="/img/bp_16.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	<title>Mench<?= ( isset($title) ? ' | '.$title : '' ) ?></title>

	<?php $this->load->view('front/shared/header_resources' ); ?>
    <script src="//cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
        
    <script src="/js/front/global.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
    <script src="/js/console/console.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
    
    <!-- Zendesk Autofill -->
    <?php /* <script> zE( function () { zE.identify({name: '<?= $udata['u_fname'] ?> <?= $udata['u_lname'] ?>', email: '<?= $udata['u_email'] ?>'}); }); </script> */?>

</head>




<body>

<?php
//Start the filtering array for unread notifications:
$unread_notification_filters = array(
    'e_recipient_u_id' => $udata['u_id'], //The instructor received these messages
    'e_type_id' => "7", //Outbound messages towards instructors
    'e_fb_page_id' => "1169880823142908", //For the instructor Bot
);

//Fetch their last read engagement
$last_read = $this->Db_model->e_fetch(array(
    'e_initiator_u_id' => $udata['u_id'], //The reading of the message was initiated by student
    'e_type_id' => "1", //Message read
    'e_fb_page_id' => "1169880823142908", //For the instructor Bot
),1); //We only need the lates one!

//Did we have any? If so, append that to the filter:
if(count($last_read)>0){
    $unread_notification_filters['e_timestamp >'] = $last_read[0]['e_timestamp'];
}

//See how many unread notifications we have:
//TODO launch for all instructors...
$unread_notifications = array();
if($udata['u_id']==1){
    $unread_notifications = $this->Db_model->e_fetch($unread_notification_filters);
}

//Facebook chat in console ONLY if activated already:
if(isset($udata['u_fb_i_id']) && $udata['u_fb_i_id']>0){
    echo echo_chat('1169880823142908',count($unread_notifications));
}

//Show them if >0
if(count($unread_notifications)>0){
    echo '<div id="msgnotif"><i class="fa fa-bell" aria-hidden="true"></i> '.count($unread_notifications).' New</div>';
}
?>

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
						<img src="/img/white-logo.png" />
						<span style="text-transform:none;" class="bg-glow">Bootcamps</span>
						</a>
						<!-- <input type="text" placeholder="Search"> -->
					</span>
				</div>
				
				<?php if(strlen($udata['u_fb_i_id'])>4){ ?>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<?php /* <li data-toggle="tooltip" data-placement="bottom" title="View FAQs & best-practices to better manage your bootcamps. Link opens in a new window."><a href="https://support.mench.co/hc/en-us" target="_blank"><i class="fa fa-lightbulb-o" aria-hidden="true"></i><span> Instructors Hub</i></span></a></li> */ ?>
						<li><a href="/console/account"><i class="fa fa-user-circle" aria-hidden="true"></i> My Account</a></li>
					</ul>
				</div>
				<?php } ?>
				
			</div>
		</nav>
		
	    <div class="sidebar" id="mainsidebar" data-color="purple">
	    	<div class="sidebar-wrapper">
	    	`
	    		<?php 
	    		if(isset($bootcamp)){
	    		    echo '<div class="left-li-title">'.$bootcamp['c_objective'].' <a href="/'.$bootcamp['b_url_key'].'" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Open Landing Page" class="landing_page_url"><i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></a></div>';
	    		}
	    		?>
	    		<ul class="nav">
                    
            	<?php
            	if(isset($bootcamp)){
            	    
            	    $sprint_units = $this->config->item('sprint_units'); 

            	    echo '<li class="li-sep '.( in_array($_SERVER['REQUEST_URI'],array('/console/'.$bootcamp['b_id'],'/console/'.$bootcamp['b_id'].'/')) ? 'active' : '' ).'"><a href="/console/'.$bootcamp['b_id'].'"><i class="fa fa-tachometer" aria-hidden="true"></i><p>Dashboard</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['b_id'].'/actionplan')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['b_id'].'/actionplan"><i class="fa fa-list-ol" aria-hidden="true"></i><p>Action Plan</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['b_id'].'/classes')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['b_id'].'/classes"><i class="fa fa-calendar" aria-hidden="true"></i><p>Classes</p></a></li>';
                	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['b_id'].'/students')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['b_id'].'/students"><i class="fa fa-users" aria-hidden="true"></i><p>Students</p></a></li>';
                	    
            	    //echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['b_id'].'/stream')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['b_id'].'/stream"><i class="material-icons">forum</i><p>Activity Stream</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$bootcamp['b_id'].'/settings')>0 ? ' class="active"' : '' ).'><a href="/console/'.$bootcamp['b_id'].'/settings"><i class="fa fa-cog" aria-hidden="true"></i><p>Settings &nbsp;'.status_bible('b',$bootcamp['b_status'],1,'top').'</p></a></li>';
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