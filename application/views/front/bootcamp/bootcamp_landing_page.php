<div class="alert alert-danger" role="alert" style="margin-top:30px; border-radius:3px;"><span style="font-weight:bold;display:block; padding-bottom:7px;">WARNING:</span>We're currently in private Beta, and the following bootcamps are created as a sample set for our customer discovery process. They are not meant for real-world enrollment.</div>

<div class="row">
    <div class="col-md-4 col-sm-4">
       <div class="video-player"><?= echo_video($c['c_video_url']); ?></div>
    </div>
    <div class="col-md-8 col-sm-8">
		<h2 class="title" style="line-height:130%; margin-bottom:15px;"><?= echo_title($c['c_objective']) ?></h2>
		<p id="c_additional_goals"><?= $c['c_additional_goals'] ?></p>
		<div id="acordeon">
            <div class="panel-group" id="accordion">
            
          
          <?php if(strlen($c['c_todo_overview'])>0){ ?>
          <div class="panel panel-border panel-default" name="collapseOverview">
            <div class="panel-heading" role="tab">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOverview" aria-expanded="false" aria-controls="collapseOverview">
                    <h4 class="panel-title">
                    Overview
                    <i class="material-icons">keyboard_arrow_down</i>
                    </h4>
                </a>
            </div>
            <div id="collapseOverview" class="panel-collapse collapse"> <!-- collapse in -->
              <div class="panel-body">
                <p id="c_todo_overview"><?= $c['c_todo_overview'] ?></p>
              </div>
            </div>
          </div>
          <?php } ?>
          
          
          <div class="panel panel-border panel-default" name="collapsePrerequisites">
            <div class="panel-heading" role="tab">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePrerequisites" aria-controls="collapsePrerequisites">
                    <h4 class="panel-title">
                    Prerequisites
                    <i class="material-icons">keyboard_arrow_down</i>
                    </h4>
                </a>
            </div>
            <div id="collapsePrerequisites" class="panel-collapse collapse">
              <div class="panel-body">
                <p id="c_prerequisites"><?= ( strlen($c['c_prerequisites'])>0 ? $c['c_prerequisites'] : 'None' ) ?></p>
              </div>
            </div>
          </div>
          
          
          <div class="panel panel-border panel-default" name="collapseMentors">
            <div class="panel-heading" role="tab">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseMentors" aria-controls="collapseMentors">
                    <h4 class="panel-title">
                    Mentors
                    <i class="material-icons">keyboard_arrow_down</i>
                    </h4>
                </a>
            </div>
            <div id="collapseMentors" class="panel-collapse collapse">
              <div class="panel-body">
                <?php
                foreach($c['c__cohorts'][0]['r__admins'] as $count2=>$admins){
                    echo '<h4 class="userheader"><img src="'.$admins['u_image_url'].'" /> '.$admins['u_fname'].' '.$admins['u_lname'].'<span><img src="/img/flags/'.strtolower($admins['u_country_code']).'.png" class="flag" style="margin-top:-4px;" /> '.$admins['u_current_city'].'</span></h4>';
                    echo '<p id="u_tangible_experience">'.$admins['u_tangible_experience'].'</p>';
                    echo '<p id="u_bio">'.$admins['u_bio'].'</p>';
                    
                    //Any languages other than English?
                    if(strlen($admins['u_language'])>0 && $admins['u_language']!=='en'){
                        $all_languages = $this->config->item('languages');
                        //They know more than enligh!
                        $langs = explode(',',$admins['u_language']);
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
                    if(strlen($admins['u_website_url'])>0){
                        echo '<a href="'.$admins['u_website_url'].'" data-toggle="tooltip" title="Visit Website" target="_blank"><i class="fa fa-chrome" aria-hidden="true"></i></a>';
                    }
                    $u_social_account = $this->config->item('u_social_account');
                    foreach($u_social_account as $sa_key=>$sa){
                        if(strlen($admins[$sa_key])>0){
                            echo '<a href="'.$sa['sa_prefix'].$admins[$sa_key].$sa['sa_postfix'].'" data-toggle="tooltip" title="'.$sa['sa_name'].'" target="_blank">'.$sa['sa_icon'].'</a>';
                        }
                    }
                    echo '</div>';
                }
                ?>
              </div>
            </div>
          </div>
          
          
          <div class="panel panel-border panel-default" name="collapseOutline">
            <div class="panel-heading" role="tab">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOutline" aria-controls="collapseOutline">
                    <h4 class="panel-title">
                    Bootcamp Outline
                    <i class="material-icons">keyboard_arrow_down</i>
                    </h4>
                </a>
            </div>
            <div id="collapseOutline" class="panel-collapse collapse">
              <div class="panel-body">
                Bootcamp Outline here...
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
                <p><?= '<span '.( $c['c__cohorts'][0]['r_end_time'] ? 'data-toggle="tooltip" class="underdot" title="Ends '.time_format($c['c__cohorts'][0]['r_end_time'],1).(strlen($c['c__cohorts'][0]['r_closed_dates'])>0?' excluding '.$c['c__cohorts'][0]['r_closed_dates']:'').'"' : '' ).'>Starts <b>'.time_format($c['c__cohorts'][0]['r_start_time'],1).'</b></span>' ?></p>
                <p><?= echo_pace($c) ?></p>
                <p><?= echo_price($c['c__cohorts'][0]['r_usd_price']); ?></p>
              </div>
            </div>
          </div>
          
          
          
          
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

        </div>
        </div><!--  end acordeon -->

        <div class="row pick-size" style="margin:40px 0;">
            <div class="col-md-6 col-sm-6">
                
            </div>
            <div class="col-md-6 col-sm-6">
                <a href="javascript:alert('This takes user to final enrollment confirmation page with stripe payment to finalize checkout.');" href2="/<?= $c['c_url_key'] ?>/enroll" class="btn btn-primary btn-round pull-right">Enroll <u><?= time_format($c['c__cohorts'][0]['r_start_time'],1) ?></u> &nbsp;<i class="material-icons">keyboard_arrow_right</i></a>
            </div>
        </div>
    </div>
</div>


<script>
$( document ).ready(function() {
    var showdowns = ["c_additional_goals","c_prerequisites","c_todo_overview","u_tangible_experience","u_bio"];
    var arrayLength = showdowns.length;
    for (var i = 0; i < arrayLength; i++) {
    	update_showdown($('#'+showdowns[i]),$('#'+showdowns[i]).html());
    }
});
</script>







</div>
</div>


<div>
<div class="container">
	
<?php $this->load->view('front/shared/all_bootcamps'); ?>
<br /><br />


