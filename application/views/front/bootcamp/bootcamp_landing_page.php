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
          <div class="panel panel-border panel-default">
            <div class="panel-heading" role="tab">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOverview" aria-expanded="true" aria-controls="collapseOverview">
                    <h4 class="panel-title">
                    Overview
                    <i class="material-icons">keyboard_arrow_down</i>
                    </h4>
                </a>
            </div>
            <div id="collapseOverview" class="panel-collapse collapse in">
              <div class="panel-body">
                <p id="c_todo_overview"><?= $c['c_todo_overview'] ?></p>
              </div>
            </div>
          </div>
          <?php } ?>
          
          
          <div class="panel panel-border panel-default">
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
          
          
          <div class="panel panel-border panel-default">
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
                    echo '<h4 class="userheader"><img src="'.$admins['u_image_url'].'" /> '.$admins['u_fname'].' '.$admins['u_lname'].'<span>'.$admins['u_current_city'].'</span></h4>';
                    echo '<p id="u_tangible_experience">'.$admins['u_tangible_experience'].'</p>';
                }
                ?>
              </div>
            </div>
          </div>
          
          
          <div class="panel panel-border panel-default">
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
                Syllabus here...
              </div>
            </div>
          </div>
          
          
          <div class="panel panel-border panel-default">
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
          
          
          
          
          <div class="panel panel-border panel-default">
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
    var showdowns = ["c_additional_goals","c_prerequisites","c_todo_overview","u_tangible_experience"];
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


