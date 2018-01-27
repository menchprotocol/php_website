<?php 
$sprint_units = $this->config->item('sprint_units');
$start_times = $this->config->item('start_times');
//Calculate office hours:
$office_hours = unserialize($focus_class['r_live_office_hours']);
$days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
$office_hours_ui = array();


$total_office_hours = 0;
if(isset($office_hours) && is_array($office_hours)){
    foreach ($office_hours as $key=>$oa){
        $string = null;
        if(isset($oa['periods']) && count($oa['periods'])>0){
            //Yes we have somehours for this day:
            foreach($oa['periods'] as $period){
                if(!$string){
                    $string = '<span style="width:100px; display:inline-block;font-weight:bold;">'.$days[$key].'</span>';
                } else {
                    $string .= ' & ';
                }
                $string .= $period[0].' - '.$period[1];
                
                //Calculate hours for this period:
                $total_office_hours += hourformat($period[1]) - hourformat($period[0]);
            }
        }
        if($string){
            $string .= ' PST';
            array_push($office_hours_ui,$string);
        }
    }
}


//See if this bootcamp has multiple active Classes:
$available_classes = 0;
$class_selection = '<h4 id="available_classes"><i class="fa fa-calendar" aria-hidden="true"></i> Available Classes</h4>';
$class_selection .= '<div id="class_list" class="list-group" style="max-width:none !important;">';
$bootcamp['c__classes'] = array_reverse($bootcamp['c__classes']);

foreach($bootcamp['c__classes'] as $class){
    if($class['r_status']==1 && !date_is_past($class['r_start_date'])){
        $available_classes++;
        if($class['r_id']==$focus_class['r_id']){
            $class_selection .= '<li class="list-group-item" style="background-color:#f5f5f5;">';
        } else {
            $class_selection .= '<a href="/'.$bootcamp['b_url_key'].'/'.$class['r_id'].'" class="list-group-item">';
            $class_selection .= '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
        }
        
        
        $class_selection .= '<i class="fa fa-calendar" aria-hidden="true"></i> <b>'.time_format($class['r_start_date'],2).'</b> &nbsp; ';
        $class_selection .= '<i class="fa fa-usd" aria-hidden="true"></i> '.(strlen($class['r_usd_price'])>0 ? number_format($class['r_usd_price']) : 'FREE' ).' &nbsp; ';
        if($class['r_max_students']>0){
            $class_selection .= '<i class="fa fa-user" aria-hidden="true"></i> '.$class['r_max_students'].' Seats';
            if($class['r__current_admissions']>=$class['r_max_students']){
                //Class is full:
                $class_selection .= ' <span style="color:#FF0000;">(FULL, '.($class['r__current_admissions']-$class['r_max_students']).' in Waiting List)</span>';
            } elseif(($class['r__current_admissions']/$class['r_max_students'])>0.66){
                //Running low on space:
                $class_selection .= ' <span style="color:#FF0000;">('.($class['r_max_students']-$class['r__current_admissions']).' Remaining)</span>';
            }
            $class_selection .= ' &nbsp; ';
        }
        if($class['r_id']==$focus_class['r_id']){
            $class_selection .= '<span class="label label-default" style="background-color:#fedd16; color:#000;">CURRENTLY VIEWING</span>';
        }
        $class_selection .= ($class['r_id']==$focus_class['r_id'] ? '</li>' : '</a>' );
        
        //Do not show more than the next 7 Classes:
        if($available_classes>=7){
            break;
        }
    }
}
$class_selection .= '</div>';
$class_selection .= '<hr />';
?>

<style>
    .msg { font-size:18px !important; font-weight:300 !important;}
    .msg a { max-width: none; }
</style>
<script>

function choose_r(){
	//Flash border color:
	$('html,body').animate({
		scrollTop: $('#available_classes').offset().top - 65
	}, 150);
}

function toggleview(object_key){
	if($('#'+object_key+' .pointer').hasClass('fa-caret-right')){
		//Opening an item!
		//Make sure all other items are closed:
		$('.pointer').removeClass('fa-caret-down').addClass('fa-caret-right');
		$('.toggleview').hide();
		//Now show this item:
		$('#'+object_key+' .pointer').removeClass('fa-caret-right').addClass('fa-caret-down');
		$('.'+object_key).fadeIn();
		//Now adjust screen view port:
		$('html,body').animate({
			scrollTop: $('#'+object_key).offset().top - 65
		}, 150);
		
	} else if($('#'+object_key+' .pointer').hasClass('fa-caret-down')){
		//Close this specific item:
		$('#'+object_key+' .pointer').removeClass('fa-caret-down').addClass('fa-caret-right');
		$('.'+object_key).hide();
	}
}

$( document ).ready(function() {
	$("#reg1, #reg2, #reg3").countdowntimer({
		startDate : "<?php echo date('Y/m/d H:i:s'); ?>",
        dateAndTime : "<?php echo date('Y/m/d' , time_format($focus_class['r_start_date'],3,-1)); ?> 23:59:59",
		size : "lg",
		regexpMatchFormat: "([0-9]{1,3}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
      		regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
	});
});

</script>


<?php if($focus_class){ ?>

<h1 style="margin-bottom:30px;"><?= $bootcamp['c_objective'] ?></h1>

<div class="row" id="landing_page">

	<div class="col-md-4">
        <div id="sidebar">
        	
        	<h3 style="margin-top:0;">Bootcamp Snapshot</h3>
        	
            <ul style="list-style:none; margin-left:0; padding:5px 10px; background-color:#EFEFEF; border-radius:5px;">
            	<li>Duration: <b><?= $bootcamp['c__milestone_units'] ?> <?= ucwords($bootcamp['b_sprint_unit']).( $bootcamp['c__milestone_units']==1 ? '' : 's') ?></b></li>
            	<li>Dates: <b><?= time_format($focus_class['r_start_date'],1) ?> - <?= time_format($focus_class['r_start_date'],1,(calculate_duration($bootcamp))) ?></b></li>
            	<li>Commitment: <b><?= echo_hours(round($bootcamp['c__estimated_hours']/$bootcamp['c__milestone_units'], 1)) ?> Per <?= ucwords($bootcamp['b_sprint_unit']) ?></b></li>
            	
            	<?php if(strlen($focus_class['r_meeting_frequency'])>0 && !($focus_class['r_meeting_frequency']=="0")){ ?>
            	<li>Mentorship: <b><?= echo_mentorship($focus_class['r_meeting_frequency'],$focus_class['r_meeting_duration']) ?></b></li>
            	<?php } ?>
            	
            	<?php if($total_office_hours>0){ ?>
            	<li>Group Calls: <b><?= echo_hours($total_office_hours) ?> Per Week</b></li>
            	<?php } ?>

                <li>Tuition: <b><?= echo_price($focus_class['r_usd_price']).( $focus_class['r_usd_price']>0 ? ' <span style="font-weight:300; font-size: 0.9em;">(<a href="https://support.mench.co/hc/en-us/articles/115002080031">Mench Guarantee</a>)</span>' : '' ); ?></b></li>
            	
            	<?php if($focus_class['r_max_students']>0){ ?>
            		<li>Availability: <b><?= $focus_class['r_max_students'] ?> Seats</b>
                        <?php
                        if($focus_class['r__current_admissions']>=$focus_class['r_max_students']){
                            //Class is full:
                            echo ' <div style="color:#FF0000;">(FULL, '.($focus_class['r__current_admissions']-$focus_class['r_max_students']).' in Waiting List)</div>';
                        } elseif(($focus_class['r__current_admissions']/$focus_class['r_max_students'])>0.66){
                            //Running low on space:
                            echo ' <span style="color:#FF0000;">('.($focus_class['r_max_students']-$focus_class['r__current_admissions']).' Remaining)</span>';
                        }
                        ?>
                    </li>
            	<?php } ?>

            </ul>
            
            <div style="padding:10px 0 30px; text-align:center;">
                <div class="btn btn-primary btn-round countdown"><span id="reg1"></span></div>
            	<a href="/<?= $bootcamp['b_url_key'] ?>/apply/<?= $focus_class['r_id'] ?>" class="btn btn-primary btn-round"><?= ( $focus_class['r_max_students']>0 ? ($focus_class['r__current_admissions']>=$focus_class['r_max_students'] ? 'Join Waiting List for' : 'Reserve Seat for') : 'Join' ) ?> <u><?= time_format($focus_class['r_start_date'],4) ?></u> &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a>
            	<?= ( $available_classes>1 ? '<div>or <a href="javascript:choose_r();"><u>Choose Another Class</u></a></div>' : '' ) ?>
            </div>
        </div>
    </div>
    
    
    <div class="col-md-8">
    
        <?php
        foreach($bootcamp['c__messages'] as $i){
            if($i['i_status']==1){
                //Publish to Landing Page!
                echo echo_i($i);
            }
        }
        ?>


        <h3>Skills You Will Gain</h3>
        <div id="b_transformations"><?= ( strlen($bootcamp['b_transformations'])>0 ? '<ol><li>'.join('</li><li>',json_decode($bootcamp['b_transformations'])).'</li></ol>' : 'Not Set Yet' ) ?></div>

        <h3>Target Audience</h3>
        <div id="b_target_audience"><?= ( strlen($bootcamp['b_target_audience'])>0 ? '<ol><li>'.join('</li><li>',json_decode($bootcamp['b_target_audience'])).'</li></ol>' : 'Not Set Yet' ) ?></div>

        <h3>Prerequisites</h3>
        <?php $pre_req_array = prep_prerequisites($bootcamp); ?>
        <div id="b_prerequisites"><?= ( count($pre_req_array)>0 /* Should always be true! */ ? '<ol><li>'.join('</li><li>',$pre_req_array).'</li></ol>' : 'None' ) ?></div>


        <h3>Action Plan</h3>
        <div id="c_goals_list">
        <?php
        foreach($bootcamp['c__child_intents'] as $sprint){
            if($sprint['c_status']<1){
                continue;
            }
            $ending_unit = $sprint['cr_outbound_rank']+$sprint['c_duration_multiplier']-1;
            echo '<div id="c_'.$sprint['c_id'].'">';
            echo '<h4><a href="javascript:toggleview(\'c_'.$sprint['c_id'].'\');" style="font-weight: normal;"><i class="pointer fa fa-caret-right" aria-hidden="true"></i> '.ucwords($bootcamp['b_sprint_unit']).' '.$sprint['cr_outbound_rank'].($sprint['c_duration_multiplier']>1 ? '-'.$ending_unit : '' ).': '.$sprint['c_objective'].'</a></h4>';
                echo '<div class="toggleview c_'.$sprint['c_id'].'" style="display:none;">';

                    //Display all Active Tasks:
                    if(count($sprint['c__child_intents'])>0){
                        echo '<ul>';
                        foreach($sprint['c__child_intents'] as $task){
                            if($task['c_status']<1){
                                continue; //Not published yet
                            }
                            echo '<li>'.$task['c_objective'].'</li>';
                        }
                        echo '</ul>';
                    }

                    echo '<div class="title-sub">';
                    if($sprint['c__estimated_hours']>0){
                        echo str_replace('title-sub','',echo_time($sprint['c__estimated_hours'],1)).' &nbsp; ';
                    }
                    echo '<i class="fa fa-calendar" aria-hidden="true"></i> Complete By '.time_format($focus_class['r_start_date'],2,calculate_duration($bootcamp,$ending_unit)).' '.$start_times[$focus_class['r_start_time_mins']].' PST';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
        ?>
        </div>
    		
    		
    		
    		
    		<h3>1-on-1 Support</h3>
    		<?php
    		if(strlen($focus_class['r_meeting_frequency'])>0 && !($focus_class['r_meeting_frequency']=="0")){
    		    echo '<h4><i class="fa fa-handshake-o" aria-hidden="true"></i> 1-on-1 Mentorship</h4>';
    		    echo '<p>You will receive a total of <b>'.gross_mentorship($focus_class['r_meeting_frequency'],$focus_class['r_meeting_duration'],$bootcamp['b_sprint_unit'],$bootcamp['c__milestone_units']).'</b> of 1-on-1 mentorship ('.echo_mentorship($focus_class['r_meeting_frequency'],$focus_class['r_meeting_duration']).') during the '.$bootcamp['c__milestone_units'].' '.$bootcamp['b_sprint_unit'].($bootcamp['c__milestone_units']==1?'':'s').' of this bootcamp.</p>';
    		    echo '<hr />';
    		}

    		
    		if(count($office_hours_ui)>0 || $total_office_hours>0){
    		    echo '<h4><i class="fa fa-podcast" aria-hidden="true"></i> Weekly Group Calls</h4>';
    		    echo '<p>You can access <b>'.echo_hours($total_office_hours).' Per Week</b> of live group support during these time-slots:</p>';
    		    echo '<ul style="list-style:none; margin-left:-20px;">';
    		    foreach($office_hours_ui as $oa_ui){
    		        echo '<li>'.$oa_ui.'</li>';
    		    }
    		    echo '</ul>';
    		    if(strlen($focus_class['r_closed_dates'])>0){
    		        echo '<p>Close Dates: '.$focus_class['r_closed_dates'].'</p>';
    		    }
    		    echo '<hr />';
    		}
    		?>


    		
    		
    		<?php if(strlen($focus_class['r_response_time_hours'])>0){ ?>
    		<h4><i class="fa fa-comments" aria-hidden="true"></i> Chat Response Time</h4>
    		<p>This bootcamp offers live chat with response times of <b>Under <?= echo_hours($focus_class['r_response_time_hours']) ?></b> to all your inquiries. You can ask <b>unlimited questions</b> from the instructor team.</p>
    		<hr />
    		<?php } ?>

    		
    		
    		<h3>Instructors</h3>
    		<?php
            $admin_count = 0;
            $leader_fname = '';
            foreach($bootcamp['b__admins'] as $admin){
                if($admin['ba_team_display']!=='t'){
                    continue;
                }
                if($admin_count>0){
                    echo '<hr />';
                }
                
                if($admin['ba_status']==3){
                    $leader_fname = $admin['u_fname'];
                }
                echo '<h4 class="userheader"><img src="'.$admin['u_image_url'].'" /> '.$admin['u_fname'].' '.$admin['u_lname'].'<span><img src="/img/flags/'.strtolower($admin['u_country_code']).'.png" class="flag" style="margin-top:-4px;" /> '.$admin['u_current_city'].'</span></h4>';
                echo '<p id="u_bio">'.$admin['u_bio'].'</p>';
                
                //Any languages other than English?
                if(strlen($admin['u_language'])>0 && $admin['u_language']!=='en'){
                    $all_languages = $this->config->item('languages');
                    //They know more than enligh!
                    $langs = explode(',',$admin['u_language']);
                    echo '<i class="fa fa-language ic-lrg" aria-hidden="true"></i>Fluent in ';
                    $count = 0;
                    foreach($langs as $lang){
                        if($count>0){
                            echo ', ';
                        }
                        echo $all_languages[$lang];
                        $count++;
                    }
                }
                
                //Public profiles:
                echo '<div class="public-profiles" style="margin-top:10px;">';
                if(strlen($admin['u_website_url'])>0){
                    echo '<a href="'.$admin['u_website_url'].'" data-toggle="tooltip" title="Visit Website" target="_blank"><i class="fa fa-chrome" aria-hidden="true"></i></a>';
                }
                $u_social_account = $this->config->item('u_social_account');
                foreach($u_social_account as $sa_key=>$sa){
                    if(strlen($admin[$sa_key])>0){
                        echo '<a href="'.$sa['sa_prefix'].$admin[$sa_key].$sa['sa_postfix'].'" data-toggle="tooltip" title="'.$sa['sa_name'].'" target="_blank">'.$sa['sa_icon'].'</a>';
                    }
                }
                echo '</div>';
                
                $admin_count++;
            }
            ?>
    
    
    

    		<h3>Admission</h3>
    		
    		<?php 
    		if($available_classes>1){
    		    echo $class_selection;
    		}
    		?>
    		
    		<h4><i class="fa fa-clock-o" aria-hidden="true"></i> Timeline</h4>
    		<ul style="list-style:none; margin-left:-30px;">
    			<li>Admission Ends <b><?= time_format($focus_class['r_start_date'],2,-1) ?> 11:59pm PST</b> (Ends in <span id="reg2"></span>)</li>
    			<li>Class Starts <b><?= time_format($focus_class['r_start_date'],2).' '.$start_times[$focus_class['r_start_time_mins']] ?> PST</b></li>
    			<li>Class Duration is <b><?= $bootcamp['c__milestone_units'] ?> <?= ucwords($bootcamp['b_sprint_unit']).(($bootcamp['c__milestone_units']==1?'':'s')) ?></b></li>
    			<li>Class Ends <b><?= time_format($focus_class['r_start_date'],2,(calculate_duration($bootcamp))).' '.$start_times[$focus_class['r_start_time_mins']] ?> PST</b></li>
    		</ul>
    		<hr />
    		
    		
    		
    		<?php if(strlen($bootcamp['b_completion_prizes'])>0){
    		    $plural_prize = ( json_decode($bootcamp['b_completion_prizes'])==1 ? '' : 's' ); ?>
    		<h4><i class="fa fa-gift" aria-hidden="true"></i> Completion Prize<?= $plural_prize ?></h4>
    		<div id="r_completion_prizes"><?= '<ol><li>'.join('</li><li>',json_decode($bootcamp['b_completion_prizes'])).'</li></ol>' ?></div>
    		<p>Awarded for completing all milestones by the end time of <?= time_format($focus_class['r_start_date'],2,(calculate_duration($bootcamp))).' '.$start_times[$focus_class['r_start_time_mins']] ?> PST.</p>
    		<hr />
    		<?php } ?>
    		
    		
    		
    		<?php if($focus_class['r_usd_price']>0){ ?>
    		<h4><i class="fa fa-shield" aria-hidden="true"></i> Refund Policy</h4>
    		<p>This bootcamp offers a <b><?= ucwords($focus_class['r_cancellation_policy']); ?></b> refund policy:</p>
    		<?php 
    		$full_days = calculate_refund(calculate_duration($bootcamp),'full',$focus_class['r_cancellation_policy']);
    		$prorated_days = calculate_refund(calculate_duration($bootcamp),'prorated',$focus_class['r_cancellation_policy']);
    		//Display cancellation terms:
    		echo '<ul style="list-style:none; margin-left:-30px;">';
    		echo '<li>Full Refund: '.( $full_days>0 ? 'Before <b>'.time_format($focus_class['r_start_date'],1,($full_days-1)).' '.$start_times[$focus_class['r_start_time_mins']].' PST</b>' : '<b>None</b> After Admission' ).'</li>';
    		echo '<li>Pro-Rated Refund: '.( $prorated_days>0 ? 'Before <b>'.time_format($focus_class['r_start_date'],1,($prorated_days-1)).' '.$start_times[$focus_class['r_start_time_mins']].' PST</b>' : '<b>None</b> After Admission' ).'</li>';
    		echo '</ul>';
    		?>
    		<p>You will always receive a full refund if your admission application was not approved by the instructor. Learn more about our <a href="https://support.mench.co/hc/en-us/articles/115002095952">Refund Policies</a>.</p>
    		<hr />
    		<?php } ?>
    		
    		
    		
    		
    		
    		<h4><i class="fa fa-usd" aria-hidden="true"></i> Tuition</h4>
    		<?php if($focus_class['r_usd_price']>0){ ?>
    		<p>One-time payment of <b><?= echo_price($focus_class['r_usd_price']); ?></b> (Inlcudes <a href="https://support.mench.co/hc/en-us/articles/115002080031">Mench Guarantee</a>) so <?= $leader_fname ?> can provide you with everything you need to <b><?= $bootcamp['c_objective'] ?></b> in <?= $bootcamp['c__milestone_units'] ?> <?= $bootcamp['b_sprint_unit'].($bootcamp['c__milestone_units']==1?'':'s') ?>.</p>
    		<?php } else { ?>
    		<p>This bootcamp is <b>FREE</b>.</p>
    		<?php } ?>
            <hr />
            <p>Ready to unleash your full potential?</p>
            <p>Class admission ends in:</p>







    		
    </div>
</div>


<div style="padding:20px 0 30px; text-align:center;">
	<div class="btn btn-primary btn-round countdown"><span id="reg3"></span></div>
    <br />
    <a href="/<?= $bootcamp['b_url_key'] ?>/apply/<?= $focus_class['r_id'] ?>" class="btn btn-primary btn-round"><?= ( $focus_class['r_max_students']>0 ? ($focus_class['r__current_admissions']>=$focus_class['r_max_students'] ? 'Join Waiting List for' : 'Reserve Seat for') : 'Apply to Join' ) ?> <u><?= time_format($focus_class['r_start_date'],4) ?></u> &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	<?= ( $available_classes>1 ? '<div>or <a href="javascript:choose_r();"><u>Choose Another Class</u></a></div>' : '' ) ?>
</div>


<?php } else { ?>
	<div class="alert alert-danger" role="alert"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Missing Live Class</span>We cannot render this landing page because its missing a live class.</div>
<?php } ?>

</div>
</div>


<div>
<div class="container">
	
<?php $this->load->view('front/shared/bootcamps_inlcude'); ?>
<br /><br />


