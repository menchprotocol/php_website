<?php 
$sprint_units = $this->config->item('sprint_units');
$start_times = $this->config->item('start_times');
//$sprint_units[$bootcamp['b_sprint_unit']]['name']
$next_cohort = filter_next_cohort($bootcamp['c__cohorts']);
//Calculate office hours:
$office_hours = unserialize($next_cohort['r_live_office_hours']);
$days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
$office_hours_ui = array();
$total_hours = 0;
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
                $total_hours += hourformat($period[1]) - hourformat($period[0]);
            }
        }
        if($string){
            $string .= ' PST';
            array_push($office_hours_ui,$string);
        }
    }
}
?>


<script>
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
        dateAndTime : "<?php echo date('Y/m/d' , time_format($next_cohort['r_start_date'],3,-1)); ?> 23:59:59",
		size : "lg",
		regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
      		regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
	});
});
</script>




<?php if($next_cohort){ ?>

<h1 style="margin-bottom:30px;"><?= $bootcamp['c_objective'] ?></h1>

<div class="row" id="landing_page">

	<div class="col-md-4">
    	<?php if(strlen($bootcamp['b_video_url'])>0){ ?>
        	<div class="video-player"><?= echo_video($bootcamp['b_video_url']); ?></div>
        <?php } ?>
        
        
        <div id="sidebar">
        	
        	<h3 style="margin-top:20px;">Bootcamp Snapshot</h3>
        	
            <ul style="list-style:none; margin-left:0; padding:5px 10px; background-color:#EFEFEF; border-radius:5px;">
            	<li>Duration: <b><?= count($bootcamp['c__child_intents']) ?> <?= ucwords($bootcamp['b_sprint_unit']).( count($bootcamp['c__child_intents'])==1 ? '' : 's') ?></b></li>
            	<li>Tuition: <b><?= echo_price($next_cohort['r_usd_price']).( $next_cohort['r_usd_price']>0 ? '*' : '' ); ?></b><?= ( $next_cohort['r_usd_price']>0 ? ' ($'.round($next_cohort['r_usd_price']/count($bootcamp['c__child_intents'])).'/'.ucwords($bootcamp['b_sprint_unit']).')' : '') ?></li>
            	<li>Dates: <b><?= time_format($next_cohort['r_start_date'],1) ?> - <?= time_format($next_cohort['r_start_date'],1,(calculate_duration($bootcamp)-1)) ?></b></li>
            	<li>Commitment: <b><?= echo_hours(round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents']))) ?>/<?= ucwords($bootcamp['b_sprint_unit']) ?></b></li>
            	<?php if($next_cohort['r_weekly_1on1s']>0){ ?>
            	<li>Mentorship: <b><?= echo_hours($next_cohort['r_weekly_1on1s']) ?>/<?= ucwords($bootcamp['b_sprint_unit']) ?></b></li>
            	<?php } ?>
            	
            	<?php if($total_hours>0){ ?>
            	<li>Office Hours: <b><?= echo_hours($total_hours) ?>/Week</b></li>
            	<?php } ?>
            	
            	<?php if($next_cohort['r_max_students']>0){ ?>
            	<li>Maximum Capacity: <b><?= $next_cohort['r_max_students'] ?> Seats</b></li>
            	<?php } ?>
            </ul>
            
            <?php if($next_cohort['r_usd_price']>0){ ?>
            <p style="padding:0 0 0 5px; font-size:0.9em; line-height:120%;"><b>*</b> All Bootcamps include our signature <a href="https://support.mench.co/hc/en-us/articles/115002080031"><b>Tuition Guarantee &raquo;</b></a></p>
            <?php } ?>
            
            <div style="padding:10px 0 30px; text-align:center;">
            	<a href="/bootcamps/<?= $bootcamp['b_url_key'] ?>/<?= $next_cohort['r_id'] ?>/apply" class="btn btn-primary btn-round">Reserve Seat For <u><?= time_format($next_cohort['r_start_date'],4) ?></u> &nbsp;<i class="material-icons">keyboard_arrow_right</i></a>
            	<div>Admission Ends in <span id="reg1"></span></div>
            </div>
        </div>
        
    </div>
    
    <div class="col-md-8">
    
    		<h3 style="margin-top:0; padding-top:0;">Overview</h3>
    		<div id="c_todo_overview"><?= $bootcamp['c_todo_overview'] ?></div>
    		
    		
    		<h3>Prerequisites</h3>
    		<div id="r_prerequisites"><?= ( strlen($next_cohort['r_prerequisites'])>0 ? $next_cohort['r_prerequisites'] : 'None' ) ?></div>
    		
    		
    		<h3>Milestones</h3>
    		<p>The tasks for this <?= count($bootcamp['c__child_intents']) ?> <?= $bootcamp['b_sprint_unit'] ?> bootcamp are estimated to take about <?= echo_time($bootcamp['c__estimated_hours']) ?>to complete, or about <b><?= echo_hours(round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents']))) ?>/<?= ucwords($bootcamp['b_sprint_unit']) ?></b>:</p>
    		<div id="c_goals_list">
    		<?php 
    		$action_plan_item = 0;
            foreach($bootcamp['c__child_intents'] as $sprint){
                $action_plan_item++;
                echo '<div id="c_'.$sprint['c_id'].'">';
                    echo '<h4><a href="javascript:toggleview(\'c_'.$sprint['c_id'].'\');"><i class="pointer fa fa-caret-right" aria-hidden="true"></i> '.ucwords($bootcamp['b_sprint_unit']).' '.$sprint['cr_outbound_rank'].': '.$sprint['c_objective'].'</a></h4>';
                    echo '<div class="toggleview c_'.$sprint['c_id'].'" style="display:none;">';
                        echo $sprint['c_todo_overview'];
                        echo '<div class="title-sub">';
                            if(count($sprint['c__child_intents'])>0){
                                echo '<i class="fa fa-check-square" aria-hidden="true"></i>'.count($sprint['c__child_intents']).' Task'.(count($sprint['c__child_intents'])==1?'':'s').' &nbsp;';
                            }
                            if($sprint['c__estimated_hours']>0){
                                echo str_replace('title-sub','',echo_time($sprint['c__estimated_hours'],1)).' &nbsp;';
                            }
                            echo '<i class="fa fa-calendar" aria-hidden="true"></i> Due '.time_format($next_cohort['r_start_date'],2,(calculate_duration($bootcamp,$action_plan_item)-1)).' 11:59pm PST';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
            ?>
    		</div>
    		
    		<h3>1-on-1 Support</h3>
    		<?php
    		if($next_cohort['r_weekly_1on1s']>0){
    		    echo '<h4><i class="fa fa-handshake-o" aria-hidden="true"></i> 1-on-1 Mentorship</h4>';
    		    echo '<p>You will receive <b>'.echo_hours($next_cohort['r_weekly_1on1s']).'/'.ucwords($bootcamp['b_sprint_unit']).'</b> of 1-on-1 mentorship over a live video chat.</p>';
    		    echo '<hr />';
    		}
    		if(count($office_hours_ui)>0 || $total_hours>0){
    		    echo '<h4><i class="fa fa-podcast" aria-hidden="true"></i> Live Office Hours</h4>';
    		    echo '<p>You can access <b>'.echo_hours($total_hours).'/Week</b> of live office hours during these timeslots:</p>';
    		    echo '<ul style="list-style:none; margin-left:-30px;">';
    		    foreach($office_hours_ui as $oa_ui){
    		        echo '<li>'.$oa_ui.'</li>';
    		    }
    		    echo '</ul>';
    		    if(strlen($next_cohort['r_closed_dates'])>0){
    		        echo '<p>Closed on '.$next_cohort['r_closed_dates'].'</p>';
    		    }
    		    echo '<hr />';
    		}
    		?>
    		
    		<h4><i class="fa fa-comments" aria-hidden="true"></i> Chat Response Time</h4>
    		<p>This bootcamp offers chat response times of <b>Under <?= echo_hours($next_cohort['r_response_time_hours']) ?></b> to all your inquiries. You can ask <b>unlimited questions</b> from the instructor team.</p>
    		<hr />
    		

    		

    		
    		
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
    		
    		
    		
    		
    		
    		
    		<?php if($bootcamp['b_id']==1){ ?>
    		<h3>Student Testimonials</h3>
    		<ul style="margin-left:-15px;">
    			<li>"This bootcamp format is very motivating. I'm enjoying the process and I like the email updates as well. The content is well organized. Great job!" <b>- Trisch Loren</b></li>
    			<li>"Thanks so much for lighting a fire under my butt with this bootcamp. I've been talking about doing this course for about five years. I'm so excited to finally be doing it!!!" <b>- Linda Salazar</b></li>
    			<li>"I can't believe how much I've gotten done in your bootcamp. I thought for sure I'd run out of steam after 4 or 6 weeks, but you've actually created an environment that's giving me more and more energy every week you put forward a new challenge. Amazing. So much gratitude to you." <b>- Donna Barker</b></li>
    		</ul>
    		<p>You can <a href="https://support.mench.co/hc/en-us/articles/115002079731">Read More Testimonials</a> from our first bootcamp.</p>
    		<?php } ?>
    
    
    
    

    		<h3>Admission</h3>
    		
    		
    		<h4><i class="fa fa-calendar" aria-hidden="true"></i> Timeline</h4>
    		<ul style="list-style:none; margin-left:-30px;">
    			<li>Admission Ends <b><?= time_format($next_cohort['r_start_date'],2,-1) ?> 11:59pm PST</b> (End in <span id="reg2"></span>)</li>
    			<li>Bootcamp Starts <b><?= time_format($next_cohort['r_start_date'],2).' '.$start_times[$next_cohort['r_start_time_mins']] ?> PST</b></li>
    			<li>Bootcamp Duration is <b><?= count($bootcamp['c__child_intents']) ?> <?= ucwords($bootcamp['b_sprint_unit']).((count($bootcamp['c__child_intents'])==1?'':'s')) ?></b></li>
    			<li>Bootcamp Ends <b><?= time_format($next_cohort['r_start_date'],2,(calculate_duration($bootcamp)-1)) ?> 11:59pm PST</b></li>
    		</ul>
    		<hr />
    		
    		
    		
    		<?php if(strlen($next_cohort['r_completion_prizes'])>0){ 
    		    $plural_prize = ( substr_count($next_cohort['r_completion_prizes'],'<li>')==1 ? '' : 's' ); ?>
    		<h4><i class="fa fa-gift" aria-hidden="true"></i> Completion Prize<?= $plural_prize ?></h4>
    		<p>Completion Prize<?= $plural_prize ?> will be awarded to students who complete all <?= count($bootcamp['c__child_intents']) ?> milestones before the bootcamp end date on <?= time_format($next_cohort['r_start_date'],2,(calculate_duration($bootcamp)-1)) ?> 11:59pm PST.</p>
    		<div id="r_completion_prizes"><?= $next_cohort['r_completion_prizes'] ?></div>
    		<hr />
    		<?php } ?>
    		
    		
    		
    		<?php if($next_cohort['r_usd_price']>0){ ?>
    		<h4><i class="fa fa-shield" aria-hidden="true"></i> Refund Policy</h4>
    		<p>This bootcamp offers a <b><?= ucwords($next_cohort['r_cancellation_policy']); ?></b> refund policy:</p>
    		<?php 
    		$full_days = calculate_refund(calculate_duration($bootcamp),'full',$next_cohort['r_cancellation_policy']);
    		$prorated_days = calculate_refund(calculate_duration($bootcamp),'prorated',$next_cohort['r_cancellation_policy']);
    		//Display cancellation terms:
    		echo '<ul style="list-style:none; margin-left:-30px;">';
    		echo '<li>Full Refund: '.( $full_days>0 ? 'Before <b>'.time_format($next_cohort['r_start_date'],1,($full_days-1)).' 11:59pm PST</b>' : '<b>None</b> After Admission' ).'</li>';
    		echo '<li>Pro-Rated Refund: '.( $prorated_days>0 ? 'Before <b>'.time_format($next_cohort['r_start_date'],1,($prorated_days-1)).' 11:59pm PST</b>' : '<b>None</b> After Admission' ).'</li>';
    		echo '</ul>';
    		?>
    		<p>You will always receive a full refund if your admission application was not approved by the instructor. Learn more about our <a href="https://support.mench.co/hc/en-us/articles/115002095952">Refund Policies</a>.</p>
    		<hr />
    		<?php } ?>
    		
    		
    		
    		
    		
    		<h4><i class="fa fa-usd" aria-hidden="true"></i> Tuition</h4>
    		<?php if($next_cohort['r_usd_price']>0){ ?>
    		<p>One-time payment of <b><?= echo_price($next_cohort['r_usd_price']); ?></b> with our <a href="https://support.mench.co/hc/en-us/articles/115002080031">Tuition Guarantee</a>. In other words you pay $<?= round($next_cohort['r_usd_price']/count($bootcamp['c__child_intents'])); ?>/<?= ucwords($bootcamp['b_sprint_unit']) ?> so <?= $leader_fname ?> can provide you with everything you need to <b><?= $bootcamp['c_objective'] ?></b> in <?= count($bootcamp['c__child_intents']) ?> <?= $bootcamp['b_sprint_unit'].(count($bootcamp['c__child_intents'])==1?'':'s') ?>.</p>
    		<?php } else { ?>
    		<p>This bootcamp is <b>FREE</b>.</p>
    		<?php } ?>
    		<p>Ready to unleash your full potential?</p>
    		
    		
    		
    		
    		
    		
    		
    		
    </div>    
</div>


<div style="padding:20px 0 30px; text-align:center;">
	<a href="/bootcamps/<?= $bootcamp['b_url_key'] ?>/<?= $next_cohort['r_id'] ?>/apply" class="btn btn-primary btn-round">Reserve Seat For <u><?= time_format($next_cohort['r_start_date'],4) ?></u> &nbsp;<i class="material-icons">keyboard_arrow_right</i></a>
	<div>Admission Ends in <span id="reg3"></span></div>
</div>


<?php } else { ?>
	<div class="alert alert-danger" role="alert"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Missing Live Cohort</span>We cannot render this landing page because its missing a live cohort.</div>
<?php } ?>

</div>
</div>


<div>
<div class="container">
	
<?php $this->load->view('front/shared/bootcamps_inlcude'); ?>
<br /><br />


