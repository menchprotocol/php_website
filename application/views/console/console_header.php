<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$uenrollment = $this->session->userdata('uenrollment');
$website = $this->config->item('website');
$fb_settings = $this->config->item('fb_settings');
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

    <script src="/js/lib/jquery.textcomplete.min.js"></script>
    <script src="/js/lib/autocomplete.jquery.min.js"></script>
    <script src="/js/lib/algoliasearch.min.js"></script>

    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
    <script src="/js/front/global.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>
    <script src="/js/console/console.js?v=v<?= $website['version'] ?>" type="text/javascript"></script>

</head>


<body id="console_body">


    <?php if(!isset($_GET['skip_header'])){ ?>
    <!-- Show Facebook Chat -->
    <div class="fb-customerchat" minimized="true" greeting_dialog_display="hide" <?= ( $udata['u_cache__fp_psid']>0 ? '' : ' ref="'.$this->Comm_model->fb_activation_url($udata['u_id'],true).'" ' ) ?> theme_color="#3C4858" page_id="<?= $fb_settings['page_id'] ?>"</div>
    <?php } ?>

	<div class="wrapper" id="console">

        <?php if(!isset($_GET['skip_header'])){ ?>
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
                                <td style="width:40px;"><img src="/img/bp_128.png" /></td>
                                <td><input type="text" id="console_search" data-lpignore="true" placeholder="Search..."></td>
                            </tr>
                        </table>
					</span>
				</div>

                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-main navbar-right">

                        <li <?= ( $uri_segment_1=='intents' ? 'class="active"' : '' ) ?>><a href="/intents"><i class="fas fa-hashtag"></i> Intents</a></li>
                        <li <?= ( $uri_segment_1=='entities' ? 'class="active"' : '' ) ?>><a href="/entities"><i class="fas fa-at"></i> Entities</a></li>


                        <li class="extra-toggle"><a href="javascript:void(0);" onclick="$('.extra-toggle').toggle();">&nbsp; <i class="fas fa-ellipsis-h"></i> &nbsp;</a></li>
                        <?php if(isset($uenrollment) && count($uenrollment)>0){ ?>
                            <li class="extra-toggle" style="display: none;"><a href="/my/actionplan"><span class="icon-left"><i class="fas fa-user-graduate"></i></span> Student</a></li>
                        <?php } ?>

                        <?php if(array_key_exists(1281, $udata['u__inbounds'])){ ?>
                            <li class="extra-toggle" style="display: none;"><a href="/cockpit/browse/engagements"><span class="icon-left"><i class="fas fa-user-shield"></i></span> Admin</a></li>
                        <?php } ?>

                        <li class="extra-toggle" style="display: none;"><a href="/entities/<?= $udata['u_id'] ?>"><span class="icon-left"><i class="fas fa-user-circle"></i></span> Account</a></li>
                        <li class="extra-toggle" style="display: none;"><a href="/logout"><span class="icon-left"><i class="fas fa-power-off"></i></span> Logout</a></li>
                    </ul>
                </div>

			</div>
		</nav>
        <?php } ?>




        <?php if($uri_segment_1=='cockpit' && array_key_exists(1281, $udata['u__inbounds'])){ ?>
        <div class="sidebar" id="mainsidebar">
        <div class="sidebar-wrapper">

            <?php
            //Side menu header:
            echo '<div class="left-li-title">';
            echo '<i class="fas fa-user-shield" style="margin-right:3px;"></i> Admin Hub';
            echo '</div>';


            echo '<ul class="nav navbar-main" style="margin-top:7px;">';
                //The the Cockpit Menu for the Mench team:
                echo '<li class="li-sep '.( $uri_segment_2=='browse' ? 'active' : '' ).'"><a href="/cockpit/browse/engagements"><i class="fas fa-search"></i><p>Browse</p></a></li>';

                echo '<li class="li-sep '.( $uri_segment_2=='udemy' ? 'active' : '' ).'"><a href="/cockpit/udemy"><i class="fas fa-address-book"></i><p>Udemy Community</p></a></li>';

                echo '<li class="li-sep '.( $uri_segment_2=='statusbible' ? 'active' : '' ).'"><a href="/cockpit/statusbible"><i class="fas fa-sliders-h"></i><p>Object Statuses</p></a></li>';


                //External Tools:
                echo '<li><a href="https://github.com/menchco/mench-web-app/milestones?direction=asc&sort=due_date&state=open" target="_blank"><i class="fab fa-github"></i><p>Team Milestones &nbsp;<i class="fas fa-external-link-square"></i></p></a></li>';

                echo '<li><a href="https://www.facebook.com/askmench/inbox" target="_blank"><i class="fab fa-facebook-messenger"></i><p>Facebook Chat &nbsp;<i class="fas fa-external-link-square"></i></p></a></li>';

                echo '<li><a href="https://support.mench.com/chat/agent" target="_blank"><i class="fas fa-comment-dots"></i><p>Zendesk Chat &nbsp;<i class="fas fa-external-link-square"></i></p></a></li>';

                echo '<li><a href="https://mench.zendesk.com/agent/dashboard" target="_blank"><i class="fas fa-ticket"></i><p>Zendesk Tickets &nbsp;<i class="fas fa-external-link-square"></i></p></a></li>';

                echo '<li><a href="https://mench.zendesk.com/knowledge/lists" target="_blank"><i class="fas fa-book"></i><p>Zendesk Guides &nbsp;<i class="fas fa-external-link-square"></i></p></a></li>';


                echo '<li><a href="https://app.hubspot.com/sales" target="_blank"><i class="fab fa-hubspot"></i><p>HubSpot CRM &nbsp;<i class="fas fa-external-link-square"></i></p></a></li>';

                echo '<li><a href="https://app.redash.io/mench/" target="_blank"><i class="fas fa-database"></i><p>SQL DB Stats &nbsp;<i class="fas fa-external-link-square"></i></p></a></li>';

                echo '<li><a href="https://mench.foundation/wp-login.php" target="_blank"><i class="fab fa-wordpress"></i><p>Mench Blog &nbsp;<i class="fas fa-external-link-square"></i></p></a></li>';

                echo '<li><a href="https://www.youtube.com/channel/UCOH64HiAIfJlz73tTSI8n-g" target="_blank"><i class="fab fa-youtube"></i><p>YouTube Channel &nbsp;<i class="fas fa-external-link-square"></i></p></a></li>';

            echo '</ul>';
            ?>

        </div>
    </div>
        <div class="main-panel">
        <?php } else { ?>
        <div class="main-panel no-side">
        <?php } ?>



            <div class="content dash" style="padding-bottom: 50px !important; <?= ( isset($b) && substr_count($_SERVER['REQUEST_URI'],'/console/'.$b['b_id'].'/actionplan')>0 ? 'min-height: inherit !important;' : '' ) ?>">
	        
    	        <?php 
    	        if(isset($breadcrumb) && count($breadcrumb)>0){
                    echo '<ol class="breadcrumb '.( isset($breadcrumb_css) ? $breadcrumb_css : '' ).'">';
                    foreach($breadcrumb as $link){
                        if($link['link']){
                            echo '<li><a href="'.$link['link'].'">'.$link['anchor'].'</a></li>';
                        } else {
                            echo '<li>'.$link['anchor'].'</li>';
                        }
                    }
                    echo '</ol>';
    	        } elseif(!isset($_GET['skip_header'])) {
    	            echo '<div style="padding:1px 0;">&nbsp;</div>'; //Give some space in the absent of the breadcrumb
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