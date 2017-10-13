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

<div class="alert alert-danger" role="alert"><span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Private Beta</span>We're currently in private beta. This is a sample bootcamp for prototyping and customer discovery. It's not meant for real-world enrollment just yet. We would be launching our next bootcamp around mid-October.</div>


<div class="row">

	<div class="col-sm-4">
    	<?php if(strlen($bootcamp['b_video_url'])>0){ ?>
        	<div class="video-player"><?= echo_video($bootcamp['b_video_url']); ?></div>
        <?php } elseif(strlen($bootcamp['b_image_url'])>0){ ?>
        	<div class="video-player"><img src="<?= $bootcamp['b_image_url'] ?>" style="width:100%;" /></div>
        <?php } ?>
    </div>
    
    
    <div class="col-sm-8">
    
		<h2 class="title" style="line-height:130%; margin-bottom:15px;"><?= $bootcamp['c_objective'] ?></h2>
		
		
			<p id="c_todo_overview"><?= $bootcamp['c_todo_overview'] ?></p>
        	<div id="acordeon">
                <div class="panel-group" id="accordion">
                
    
              
                  <div class="panel panel-border panel-default" name="collapsePrerequisites">
                    <div class="panel-heading" role="tab">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePrerequisites" aria-controls="collapsePrerequisites">
                            <h4 class="panel-title">
                            Requirements
                            <i class="material-icons">keyboard_arrow_down</i>
                            </h4>
                        </a>
                    </div>
                    <div id="collapsePrerequisites" class="panel-collapse collapse">
                      <div class="panel-body">
                        <p id="c_prerequisites"><?= ( strlen($bootcamp['c_prerequisites'])>0 ? $bootcamp['c_prerequisites'] : 'None' ) ?></p>
                      </div>
                    </div>
                  </div>
                  
                  
                  
                  
                  <div class="panel panel-border panel-default" name="collapseWeeklySprints">
                    <div class="panel-heading" role="tab">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseWeeklySprints" aria-controls="collapseWeeklySprints">
                            <h4 class="panel-title">
                            Curriculum
                            <i class="material-icons">keyboard_arrow_down</i>
                            </h4>
                        </a>
                    </div>
                    <div id="collapseWeeklySprints" class="panel-collapse collapse">
                      <div class="panel-body">
                        <?php 
                        foreach($bootcamp['c__child_intents'] as $sprint){
                            echo '<div id="c_'.$sprint['c_id'].'">';
                            echo '<h4><a href="javascript:toggleview(\'c_'.$sprint['c_id'].'\');"><i class="pointer fa fa-caret-right" aria-hidden="true"></i> Week '.$sprint['cr_outbound_rank'].': '.$sprint['c_objective'].' '.echo_time($sprint['c__estimated_hours'],1).'</a></h4>';
                                echo '<div class="toggleview c_'.$sprint['c_id'].'" style="display:none;">'.$sprint['c_todo_overview'].'</div>';
                            echo '</div>';
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  
                  
                  
                  
                  
                  <div class="panel panel-border panel-default" name="collapseMentors">
                    <div class="panel-heading" role="tab">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseMentors" aria-controls="collapseMentors">
                            <h4 class="panel-title">
                            Experts
                            <i class="material-icons">keyboard_arrow_down</i>
                            </h4>
                        </a>
                    </div>
                    <div id="collapseMentors" class="panel-collapse collapse">
                      <div class="panel-body">
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
                      </div>
                    </div>
                  </div>
                  
                  
                  
                  
                  
                  <div class="panel panel-border panel-default" name="collapseTimetable">
                    <div class="panel-heading" role="tab">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTimetable" aria-controls="collapseTimetable">
                            <h4 class="panel-title">
                            Tuition & Dates
                            <i class="material-icons">keyboard_arrow_down</i>
                            </h4>
                        </a>
                    </div>
                    <div id="collapseTimetable" class="panel-collapse collapse">
                      <div class="panel-body">
                        <p><i class="fa fa-calendar" aria-hidden="true"></i> <?= count($bootcamp['c__child_intents']) ?> Weeks</p>
                        <p><?= echo_price($next_cohort['r_usd_price']); ?></p>
                      </div>
                    </div>
                  </div>
                  
                  
                  
                  
                  <?php /*
                  <div class="panel panel-border panel-default" name="collapseFAQ">
                    <div class="panel-heading" role="tab">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFAQ" aria-controls="collapseFAQ">
                            <h4 class="panel-title">
                            FAQ
                            <i class="material-icons">keyboard_arrow_down</i>
                            </h4>
                        </a>
                    </div>
                    <div id="collapseFAQ" class="panel-collapse collapse">
                      <div class="panel-body">
                        FAQ
                      </div>
                    </div>
                  </div>
                  */?>
              
              
            </div>
            </div><!--  end acordeon -->
            
            <div style="padding:5px 0 30px; text-align:center;"><a href="javascript:alert('Currently in private Beta. Contact us to learn more.');" href2="/bootcamps/<?= $bootcamp['b_url_key'] ?>/enroll" class="btn btn-primary btn-round">Apply For <u><?= time_format($next_cohort['r_start_date'],1) ?></u> &nbsp;<i class="material-icons">keyboard_arrow_right</i></a></div>
		
		
    </div>
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


