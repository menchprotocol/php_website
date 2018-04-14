<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$uadmission = $this->session->userdata('uadmission');
$website = $this->config->item('website');
$uri_segment_1 = $this->uri->segment(1);
$uri_segment_2 = $this->uri->segment(2);
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

    <!-- Require Facebook Chat for Logged-in Instructors -->
    <div class="fb-customerchat" minimized="true" greeting_dialog_display="hide" <?= ( $udata['u_cache__fp_psid']>0 ? '' : ' ref="'.$this->Comm_model->fb_activation_url($udata['u_id'],4,true).'" ' ) ?> theme_color="#3C4858" page_id="381488558920384"></div>

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
                        <table style="width: 100%; border:0; padding:0; margin:0;">
                            <tr>
                                <td style="width:40px;"><a href="/console"><img src="/img/bp_128.png" /></a></td>
                                <td><?php if($udata['u_id']==1){ ?> <input type="text" id="console_search" placeholder="Search Bootcamps..."> <?php } ?></td>
                            </tr>
                        </table>
					</span>
				</div>

                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-main navbar-right">

                        <li <?= ( $uri_segment_1=='console' && ( !$uri_segment_2 || intval($uri_segment_2)>0 ) ? 'class="active"' : '' ) ?> data-toggle="tooltip" data-placement="bottom" title="Manage and create Bootcamps"><a href="/console<?= ( isset($b) && $b['b_is_parent'] ? '#multiweek' : '' ) ?>"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamps</a></li>

                        <li <?= ( $uri_segment_1=='console' && $uri_segment_2=='account' ? 'class="active"' : '' ) ?> data-toggle="tooltip" data-placement="bottom" title="Manage profile, set your Paypal email for weekly payouts and see payment history"><a href="/console/account"><i class="fa fa-user-circle" aria-hidden="true"></i> Account</a></li>

                        <?php if($udata['u_status']==3){ ?>

                            <li <?= ( $uri_segment_1=='cockpit' ? 'class="active"' : '' ) ?> data-toggle="tooltip" data-placement="bottom" title="Tools to overview and manage the Mench platform"><a href="/cockpit/browse/engagements"><i class="fa fa-bandcamp" aria-hidden="true"></i> Cockpit</a></li>

                        <?php } ?>

                        <?php
                        //NOTE: For some reason we NEED the next <li> otherwise the page orientation breaks!
                        if(isset($uadmission) && count($uadmission)>0){ ?>
                            <li data-toggle="tooltip" data-placement="bottom" title="Access your Action Plan as a student (not instructor!) and complete your tasks"><a href="/my/actionplan"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Student Portal</a></li>
                        <?php } ?>

                    </ul>
                </div>
				
			</div>
		</nav>
		
	    <div class="sidebar" id="mainsidebar">
	    	<div class="sidebar-wrapper">

	    		<?php

                echo '<div class="left-li-title">';
	    		if(isset($b)){
	    		    echo ($b['b_old_format'] ? '<i class="fa fa-lock" style="margin-right:3px; color:#FF0000;" data-toggle="tooltip" data-placement="bottom" title="This Bootcamp was created with an older version of Mench. You can import the Action Plan into a new Weekly Bootcamp." aria-hidden="true"></i>' : '<i class="fa '.( $b['b_is_parent'] ? 'fa-folder-open' : 'fa-dot-circle-o' ).'" style="margin-right:3px;" aria-hidden="true"></i>').'<span class="c_objective_'.$b['b_c_id'].'">'.$b['c_objective'].'</span>';
	    		} elseif($uri_segment_1=='cockpit'){
                    echo '<i class="fa fa-bandcamp" style="margin-right:3px;" aria-hidden="true"></i> Cockpit';
                }
	    		echo '</div>';



                echo '<ul class="nav navbar-main" style="margin-top: 0;">';
            	if(isset($b)){

            	    echo '<li class="li-sep '.( in_array($_SERVER['REQUEST_URI'],array('/console/'.$b['b_id'],'/console/'.$b['b_id'].'/')) ? 'active' : '' ).'"><a href="/console/'.$b['b_id'].'"><i class="fa fa-tachometer" aria-hidden="true"></i><p>Dashboard</p></a></li>';
            	    
            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$b['b_id'].'/actionplan')>0 ? ' class="active"' : '' ).'><a href="/console/'.$b['b_id'].'/actionplan"><i class="fa fa-list-ol" aria-hidden="true"></i><p>Action Plan</p></a></li>';

                    if(!$b['b_is_parent']){
                        echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$b['b_id'].'/classes')>0 ? ' class="active"' : '' ).'><a href="/console/'.$b['b_id'].'/classes"><i class="fa fa-users" aria-hidden="true"></i><p>Classes</p></a></li>';
                    }


            	    echo '<li'.( substr_count($_SERVER['REQUEST_URI'],'/console/'.$b['b_id'].'/settings')>0 ? ' class="active"' : '' ).'><a href="/console/'.$b['b_id'].'/settings"><i class="fa fa-cog" aria-hidden="true"></i><p>Settings</p></a></li>';

            	    //Is it connected to a Facebook Page?
                    if($b['b_fp_id']>0 && ( !($b['b_fp_id']==4) || $udata['u_status']==3 )){

                        if(!$b['b_is_parent']){
                            //Facebook Chat Inbox:
                            echo '<li><a data-toggle="tooltip" data-placement="top" title="Chat with Students who Purchased Premium support using Facebook Page Inbox" href="/api_v1/fp_redirect/'.$b['b_fp_id'].'/'.md5($b['b_fp_id'].'pageLinkHash000').'" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i><p>Chat Inbox &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';
                        }

                        //Landing Page
                        echo '<li><a class="landing_page_url" href="/'.$b['b_url_key'].'" target="_blank"><i class="fa fa-bullhorn" aria-hidden="true"></i><p>Landing Page &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';

                    }
                } elseif($uri_segment_1=='cockpit'){

            	    //The the Cockpit Menu for the Mench team:
                    echo '<li class="li-sep '.( $uri_segment_2=='browse' ? 'active' : '' ).'"><a href="/cockpit/browse/engagements"><i class="fa fa-sitemap" aria-hidden="true"></i><p>Browse</p></a></li>';

                    echo '<li class="li-sep '.( $uri_segment_2=='udemy' ? 'active' : '' ).'"><a href="/cockpit/udemy"><i class="fa fa-address-book" aria-hidden="true"></i><p>Udemy Community</p></a></li>';

                    echo '<li class="li-sep '.( $uri_segment_2=='statusbible' ? 'active' : '' ).'"><a href="/cockpit/statusbible"><i class="fa fa-certificate" aria-hidden="true"></i><p>Status Bible</p></a></li>';


                    //External Tools:
                    echo '<li><a href="https://github.com/menchco/mench-web-app/milestones?direction=asc&sort=due_date&state=open" target="_blank"><i class="fa fa-github" aria-hidden="true"></i><p>Team Milestones &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';

                    echo '<li><a href="https://www.facebook.com/menchbot/inbox" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i><p>Facebook Chat &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';

                    echo '<li><a href="https://support.mench.com/chat/agent" target="_blank"><i class="fa fa-commenting" aria-hidden="true"></i><p>Zendesk Chat &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';

                    echo '<li><a href="https://mench.zendesk.com/agent/dashboard" target="_blank"><i class="fa fa-ticket" aria-hidden="true"></i><p>Zendesk Tickets &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';

                    echo '<li><a href="https://mench.zendesk.com/knowledge/lists" target="_blank"><i class="fa fa-book" aria-hidden="true"></i><p>Zendesk Guides &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';


                    echo '<li><a href="https://app.hubspot.com/sales" target="_blank"><i class="fa fa-handshake-o" aria-hidden="true"></i><p>HubSpot CRM &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';

                    echo '<li><a href="https://app.redash.io/mench/" target="_blank"><i class="fa fa-bar-chart" aria-hidden="true"></i><p>SQL DB Stats &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';

                    //echo '<li><a href="https://mench.foundation/wp-login.php" target="_blank"><i class="fa fa-wordpress" aria-hidden="true"></i><p>Mench Blog &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';

                    //echo '<li><a href="https://www.youtube.com/channel/UCOH64HiAIfJlz73tTSI8n-g" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i><p>YouTube Channel &nbsp;<i class="fa fa-external-link-square" aria-hidden="true"></i></p></a></li>';

                }
                echo '</ul>';

            	?>


	    	</div>
		</div>


	    <div class="main-panel">
	        <div class="content dash" style="padding-bottom: 50px !important; <?= ( isset($b) && substr_count($_SERVER['REQUEST_URI'],'/console/'.$b['b_id'].'/actionplan')>0 ? 'min-height: inherit !important;' : '' ) ?>">
	        
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