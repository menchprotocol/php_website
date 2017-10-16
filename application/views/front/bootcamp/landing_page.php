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
</script>


<?php 
$next_cohort = filter_next_cohort($bootcamp['c__cohorts']);
//Calculate office hours:
$office_hours = unserialize($next_cohort['r_live_office_hours']);
$days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
$office_hours_ui = array();
$total_hours = 0;
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

/*
 * 'r_start_date' => date("Y-m-d",strtotime($_POST['r_start_date'])),
	        
	        'r_cancellation_policy' => $_POST['r_cancellation_policy'],
	        'r_closed_dates' => $_POST['r_closed_dates'],
	        */
?>


<?php if($next_cohort){ ?>

<h1 style="margin-bottom:30px;"><?= $bootcamp['c_objective'] ?></h1>

<div class="row" id="landing_page">

	<div class="col-md-4">
    	<?php if(strlen($bootcamp['b_video_url'])>0){ ?>
        	<div class="video-player"><?= echo_video($bootcamp['b_video_url']); ?></div>
        <?php } elseif(strlen($bootcamp['b_image_url'])>0){ ?>
        	<div class="video-player"><img src="<?= $bootcamp['b_image_url'] ?>" style="width:100%;" /></div>
        <?php } ?>
        
        
        <div id="sidebar">
        	<h3 style="margin-top:20px;">Bootcamp Snapshot</h3>
        	
            <ul style="list-style:none; margin-left:0; padding:5px 10px; background-color:#EFEFEF; border-radius:5px;">
            	<li>Duration: <b><?= count($bootcamp['c__child_intents']) ?> Week<?= count($bootcamp['c__child_intents'])==1?'':'s' ?></b></li>
            	<li>Tuition: <b><?= echo_price($next_cohort['r_usd_price']); ?></b> ($<?= round($next_cohort['r_usd_price']/count($bootcamp['c__child_intents'])); ?>/Week)</li>
            	<li data-toggle="tooltip" title="If you did the work and did not Create and Launch an Online Course by 14 Jan 2018, you will receive a full account credit.">Promise: <a href="https://support.mench.co/hc/en-us/articles/115002080031"><u><b>Result Guarantee &raquo;</b></u></a></li>
            	<li>Dates: <b><?= time_format($next_cohort['r_start_date'],1) ?> - <?= time_format($next_cohort['r_start_date'],1,(count($bootcamp['c__child_intents'])*7)) ?></b></li>
            	<li>Average Homework: <b><?= round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents'])) ?>h/Week</b></li>
            	<?php if($next_cohort['r_weekly_1on1s']>0){ ?>
            	<li>1-on-1 Mentorship: <b><?= echo_hours($next_cohort['r_weekly_1on1s']) ?>/Week</b></li>
            	<?php } ?>
            	<?php if($total_hours>0){ ?>
            	<li>Live Office Hours: <b><?= echo_hours($total_hours) ?>/Week</b></li>
            	<?php } ?>
            </ul>
            
            <div style="padding:10px 0 30px; text-align:center;"><a href="/bootcamps/<?= $bootcamp['b_url_key'] ?>/<?= $next_cohort['r_id'] ?>/apply" class="btn btn-primary btn-round">Apply For <u><?= time_format($next_cohort['r_start_date'],1) ?></u> &nbsp;<i class="material-icons">keyboard_arrow_right</i></a></div>
        </div>
        
    </div>
    
    <div class="col-md-8">
    
    		<h3 style="margin-top:0; padding-top:0;">Overview</h3>
    		<div id="c_todo_overview"><?= $bootcamp['c_todo_overview'] ?></div>
    		
    		
    		<h3>Prerequisites</h3>
    		<div id="c_prerequisites"><?= ( strlen($bootcamp['c_prerequisites'])>0 ? $bootcamp['c_prerequisites'] : 'None' ) ?></div>
    		
    		
    		<h3>Curriculum</h3>
    		<p>This <?= count($bootcamp['c__child_intents']) ?> week bootcamp has <?= echo_time($bootcamp['c__estimated_hours']) ?>of homework, which is an average of <b><?= round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents'])) ?>h/Week</b>:</p>
    		<div id="c_curriculum">
    		<?php 
            foreach($bootcamp['c__child_intents'] as $sprint){
                echo '<div id="c_'.$sprint['c_id'].'">';
                echo '<h4><a href="javascript:toggleview(\'c_'.$sprint['c_id'].'\');"><i class="pointer fa fa-caret-right" aria-hidden="true"></i> Week '.$sprint['cr_outbound_rank'].': '.$sprint['c_objective'].' '.echo_time($sprint['c__estimated_hours'],1).'</a></h4>';
                    echo '<div class="toggleview c_'.$sprint['c_id'].'" style="display:none;">'.$sprint['c_todo_overview'].'</div>';
                echo '</div>';
            }
            ?>
    		</div>
    		
    		<h3>1-on-1 Support</h3>
    		<?php
    		if($next_cohort['r_weekly_1on1s']>0){
    		    echo '<h4>'.echo_hours($next_cohort['r_weekly_1on1s']).'/Week of 1-on-1 Mentorship</h4>';
    		    echo '<p>You will receive <b>'.echo_hours($next_cohort['r_weekly_1on1s']).'/week</b> of 1-on-1 mentorship over a live video chat.</p>';
    		    echo '<hr />';
    		}
    		if(count($office_hours_ui)>0 || $total_hours>0){
    		    echo '<h4>'.echo_hours($total_hours).'/Week of Live Office Hours</h4>';
    		    echo '<p>You can connect with instructors live during these weekly timeslots:</p>';
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
    		
    		<h4>Under <?= echo_hours($next_cohort['r_response_time_hours']) ?> Response Time</h4>
    		<p>This bootcamp offers responses times of under <b><?= echo_hours($next_cohort['r_response_time_hours']) ?></b> to all your inquiries. You can ask <b>unlimited questions</b> from the instructors.</p>
    		
    		
    		
    		
    		
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
                echo '<p id="u_tangible_experience">'.$admin['u_tangible_experience'].'</p>';
                echo '<p id="u_bio">'.$admin['u_bio'].'</p>';
                
                //Any languages other than English?
                if(strlen($admin['u_language'])>0 && $admin['u_language']!=='en'){
                    $all_languages = $this->config->item('languages');
                    //They know more than enligh!
                    $langs = explode(',',$admin['u_language']);
                    echo '<i class="fa fa-language ic-lrg" aria-hidden="true"></i>';
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
    		
    		
    		
    		<h3>Student Testimonials</h3>
    		<ul style="margin-left:-15px;">
    			<li>"This bootcamp format is very motivating. I'm enjoying the process and I like the email updates as well. The content is well organized. Great job!" <b>- Trisch Loren</b></li>
    			<li>"Thanks so much for lighting a fire under my butt with this bootcamp. I've been talking about doing this course for about five years. I'm so excited to finally be doing it!!!" <b>- Linda Salazar</b></li>
    			<li>"I can't believe how much I've gotten done in your bootcamp. I thought for sure I'd run out of steam after 4 or 6 weeks, but you've actually created an environment that's giving me more and more energy every week you put forward a new challenge. Amazing. So much gratitude to you." <b>- Donna Barker</b></li>
    		</ul>
    		<p>You can <a href="https://support.mench.co/hc/en-us/articles/115002079731">Read More Testimonials</a> from our first bootcamp.</p>
    
    		<h3>Enrollment</h3>
    		
    		
    		<h4>Timeline</h4>
    		<ul style="list-style:none; margin-left:-30px;">
    			<li>Registration Ends <b><?= time_format($next_cohort['r_start_date'],2,-1) ?> 11:59pm PST</b></li>
    			<li>Bootcamp Starts <b><?= time_format($next_cohort['r_start_date'],2) ?></b></li>
    			<li>Bootcamp Duration is <b><?= count($bootcamp['c__child_intents']) ?> Week<?= (count($bootcamp['c__child_intents'])==1?'':'s') ?></b></li>
    			<li>Bootcamp Ends <b><?= time_format($next_cohort['r_start_date'],2,(count($bootcamp['c__child_intents'])*7)) ?></b></li>
    		</ul>
    		<hr />
    		
    		<h4>Cancellation Policy: <?= ucwords($next_cohort['r_cancellation_policy']); ?></h4>
    		<?php 
    		$cancellation_policies = $this->config->item('cancellation_policies');
    		echo '<ul style="list-style:none; margin-left:-30px;">';
    		echo '<li>Full Refund Before <b>'.time_format($next_cohort['r_start_date'],1,9).' 11:59pm PST</b></li>';
    		echo '<li>Pro-Rated Refund Before <b>'.time_format($next_cohort['r_start_date'],1,51).' 11:59pm PST</b></li>';
    		//foreach($cancellation_policies[$next_cohort['r_cancellation_policy']] as $policy){
    		    //echo '<li>'.$policy.'</li>';
    		//}
    		echo '</ul>';
    		?>
    		<p>Learn more about our <a href="https://support.mench.co/hc/en-us/articles/115002095952">Bootcamp Cancellation Policies</a>.</p>
    		<hr />
    		
    		
    		<h4>Tuition</h4>
    		<p>One-time payment of <b><?= echo_price($next_cohort['r_usd_price']); ?></b> with our <a href="https://support.mench.co/hc/en-us/articles/115002080031" data-toggle="tooltip" title="If you did the work and did not Create and Launch an Online Course by 14 Jan 2018, you will receive a full account credit.">Result Guarantee Promise</a>. In other words you pay $<?= round($next_cohort['r_usd_price']/count($bootcamp['c__child_intents'])); ?>/Week so <?= $leader_fname ?> can provide you with everything you need to <b><?= $bootcamp['c_objective'] ?></b> in <?= count($bootcamp['c__child_intents']) ?> week<?= (count($bootcamp['c__child_intents'])==1?'':'s') ?>.</p>
    		<p>Ready to unleash your potential?</p>
    		
    		
    		
    </div>    
</div>


<div style="padding:20px 0 30px; text-align:center;"><a href="/bootcamps/<?= $bootcamp['b_url_key'] ?>/<?= $next_cohort['r_id'] ?>/apply" class="btn btn-primary btn-round">Apply For <u><?= time_format($next_cohort['r_start_date'],1) ?></u> &nbsp;<i class="material-icons">keyboard_arrow_right</i></a></div>

<?php } else { ?>
	<div class="alert alert-danger" role="alert"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Missing Live Cohort</span>We cannot render this landing page because its missing a live cohort.</div>
<?php } ?>

</div>
</div>


<div>
<div class="container">
	
<?php $this->load->view('front/shared/bootcamps_inlcude'); ?>
<br /><br />


