<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$uadmission = $this->session->userdata('uadmission');
$website = $this->config->item('website');
?><!doctype html>
<html lang="en">
<head>
    <!--

    WELCOME TO MENCH SOURCE CODE!

    INTERESTED IN HELPING US BUILD THE FUTURE OF EDUCATION?

    SEND US YOUR RESUME TO SUPPORT@MENCH.COM

    -->
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="/img/bp_16.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	<title>Mench<?= ( isset($title) ? ' | '.$title : '' ) ?></title>

    <link href="/css/lib/devices.min.css" rel="stylesheet" />
    <link href="/css/lib/jquery.mCustomScrollbar.min.css" rel="stylesheet" />
	<?php $this->load->view('front/shared/header_resources' ); ?>

    <script src="/js/lib/autocomplete.jquery.min.js"></script>
    <script src="/js/lib/algoliasearch.min.js"></script>
    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
    <script src="/js/front/global.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
    <script src="/js/console/console.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>

</head>




<body id="console_body">

    <!-- Messenger Chat Widget: -->
    <div class="fb-customerchat" minimized="true" page_id="381488558920384"></div>

	<div class="wrapper" id="console">
		<div class="navbar navbar-transparent navbar-absolute">
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
						<img src="/img/bp_128.png" />
						<span style="text-transform:none;" class="bg-glow">Projects</span>
						</a>
						<!-- <input type="text" placeholder="Search"> -->
					</span>
				</div>
				
				<?php if($udata['u_cache__fp_psid']>0){ ?>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<?php /* <li data-toggle="tooltip" data-placement="bottom" title="View FAQs & best-practices to better manage your Projects. Link opens in a new window."><a href="https://support.mench.co/hc/en-us" target="_blank"><i class="fa fa-lightbulb-o" aria-hidden="true"></i><span> Instructors Hub</span></a></li> */ ?>
                        <li><a href="/console/account"><?= (strlen($udata['u_image_url'])>4 ? '<img src="'.$udata['u_image_url'].'" class="profile-icon" />' : '<i class="fa fa-user-circle" aria-hidden="true"></i>') ?> My Account</a></li>
                        <?php if(isset($uadmission) && count($uadmission)>0){ ?>
                            <li data-toggle="tooltip" data-placement="bottom" title="You are seeing this because you are a Project student. Use this to access your Action Plan on a web-based portal, which replicates the Mench Personal Assistant."><a href="/my/actionplan"><span> Student Hub <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></span></a></li>
                        <?php } ?>
					</ul>
				</div>
				<?php } ?>
				
			</div>
		</div>
		
	    <div class="sidebar" id="mainsidebar" data-color="purple">
	    	<div class="sidebar-wrapper">
	    		<?php 
	    		if(isset($b)){
	    		    echo '<div class="left-li-title"><i class="fa fa-dot-circle-o" style="margin-right:3px;" aria-hidden="true"></i><a href="/'.$b['b_url_key'].'" class="landing_page_url" id="top-left-title" data-toggle="tooltip" data-placement="bottom" title="Visit Landing Page">'.$b['c_objective'].'</a></div>';
	    		}
	    		?>
	    		<ul class="nav" style="margin-top: 0;">
                    
            	<?php
            	if(isset($b)){

            	    echo '<li class="li-sep '.( in_array($_SERVER['REQUEST_URI'],array('/console/'.$b['b_id'],'/console/'.$b['b_id'].'/')) ? 'active' : '' ).'"><a href="/console/'.$b['b_id'].'"><i class="fa fa-tachometer" aria-hidden="true"></i><p>Dashboard</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$b['b_id'].'/actionplan')>0 ? ' class="active"' : '' ).'><a href="/console/'.$b['b_id'].'/actionplan"><i class="fa fa-list-ol" aria-hidden="true"></i><p>Action Plan</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$b['b_id'].'/classes')>0 ? ' class="active"' : '' ).'><a href="/console/'.$b['b_id'].'/classes"><i class="fa fa-calendar" aria-hidden="true"></i><p>Classes</p></a></li>';

            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$b['b_id'].'/settings')>0 ? ' class="active"' : '' ).'><a href="/console/'.$b['b_id'].'/settings"><i class="fa fa-cog" aria-hidden="true"></i><p>Settings</p></a></li>';

            	    //Is it connected to a Facebook Page?
                    if($b['b_fp_id']>0 && ( !($b['b_fp_id']==4) || $udata['u_status']==3 )){
                        //Fetch page details:
                        echo '<li><a data-toggle="tooltip" data-placement="top" title="Access your student chat using your Facebook Page Inbox" href="/api_v1/page_redirect/'.$b['b_fp_id'].'/'.md5($b['b_fp_id'].'pageLinkHash000').'" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i><p>Chat Inbox &nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></p></a></li>';
                    }

        		}
            	?>
            	</ul>
	    	</div>
		</div>


	    <div class="main-panel">
	        <div class="content dash" style="<?= ( isset($b) && substr_count($_SERVER['REQUEST_URI'],'/console/'.$b['b_id'].'/actionplan')>0 ? 'min-height: inherit !important;' : '' ) ?>">
	        
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