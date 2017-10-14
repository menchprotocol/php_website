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
		
	} else if($('#'+object_key+' .pointer').hasClass('fa-caret-down')){
		//Close this specific item:
		$('#'+object_key+' .pointer').removeClass('fa-caret-down').addClass('fa-caret-right');
		$('.'+object_key).hide();
	}
}


$( document ).ready(function() {
    //Adjust #accordion after open/close to proper view point:
	$('#accordion').on('shown.bs.collapse', function (e) {
		if (typeof $('[name=' + e.target.id +']').offset() !== 'undefined') {
			$('html,body').animate({
				scrollTop: $('[name=' + e.target.id +']').offset().top - 40
			}, 150);			
		}
	});
});
</script>


<?php 
$next_cohort = filter_next_cohort($bootcamp['c__cohorts']);
?>


<?php if($next_cohort){ ?>


<h1><?= $bootcamp['c_objective'] ?></h1>

<div class="row">

	<div class="col-sm-4">
    	<?php if(strlen($bootcamp['b_video_url'])>0){ ?>
        	<div class="video-player"><?= echo_video($bootcamp['b_video_url']); ?></div>
        <?php } elseif(strlen($bootcamp['b_image_url'])>0){ ?>
        	<div class="video-player"><img src="<?= $bootcamp['b_image_url'] ?>" style="width:100%;" /></div>
        <?php } ?>
        
        <h3 style="margin:20px 0 10px; padding:0;">Bootcamp Snapshot:</h3>
        <ul style="list-style:decimal; margin-left:-18px;">
        	<li>Result Guaranteed. <a href=""><b>What?!</b></a></li>
        	<li>12 Weeks: <b>Oct 23 - Feb 28 2018</b></li>
        	<li>Your Commitment: <b>~9h/Week</b></li>
        	<!-- <li>1-on-1 Mentorship: <b>30m/Week</b></li>  -->
        	<li>Live Office Hours: <b>2h/Week</b></li>
        	<li>Questions Responded within <b>24h</b></li>
        </ul>
        
        <div style="padding:10px 0 30px; text-align:center;"><a href="javascript:alert('Currently in private Beta. Contact us to learn more.');" href2="/bootcamps/<?= $bootcamp['b_url_key'] ?>/enroll" class="btn btn-primary btn-round">Apply For <u><?= time_format($next_cohort['r_start_date'],1) ?></u> &nbsp;<i class="material-icons">keyboard_arrow_right</i></a></div>
        
    </div>
    
    <div class="col-sm-8">
    
    		<h3 style="margin-top:0; padding-top:0;">Overview</h3>
    		<div id="c_todo_overview"><?= $bootcamp['c_todo_overview'] ?></div>
    		
    		
    		<h3>Prerequisites</h3>
    		<div id="c_prerequisites"><?= ( strlen($bootcamp['c_prerequisites'])>0 ? $bootcamp['c_prerequisites'] : 'None' ) ?></div>
    		
    		
    		<h3>Curriculum</h3>
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
    		<p>This bootcamps offers the following 1-on-1 support:</p>
    		<ul>
    			<li>Live office hours: Mon-Fri 9a-5p</li>
    			<li>24 Hours Response time</li>
    			<li>24 Hours Response time</li>
    		</ul>
    		
    		
    		
    		
    		<h3>Instructors</h3>
    		<?php
            $admin_count = 0;
            foreach($bootcamp['b__admins'] as $admin){
                if($admin['ba_team_display']!=='t'){
                    continue;
                }
                if($admin_count>0){
                    echo '<hr />';
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
    		
    		
    		
    
    		<h3>Tuition & Dates</h3>
    		<p><i class="fa fa-calendar" aria-hidden="true"></i> <?= count($bootcamp['c__child_intents']) ?> Weeks</p>
            <p><?= echo_price($next_cohort['r_usd_price']); ?></p>
		
    </div>    
</div>


<div style="padding:20px 0 30px; text-align:center;"><a href="javascript:alert('Currently in private Beta. Contact us to learn more.');" href2="/bootcamps/<?= $bootcamp['b_url_key'] ?>/enroll" class="btn btn-primary btn-round">Apply For <u><?= time_format($next_cohort['r_start_date'],1) ?></u> &nbsp;<i class="material-icons">keyboard_arrow_right</i></a></div>

<?php } else { ?>
	<div class="alert alert-danger" role="alert"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Missing Live Cohort</span>We cannot render this landing page because its missing a live cohort.</div>
<?php } ?>

</div>
</div>


<div>
<div class="container">
	
<?php $this->load->view('front/shared/bootcamps_inlcude'); ?>
<br /><br />


