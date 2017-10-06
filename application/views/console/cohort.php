<?php 
$outbound = $this->Db_model->cr_outbound_fetch(array(
    'cr.cr_inbound_id' => $bootcamp['c_id'],
    'cr.cr_status >=' => 0,
));
?>
<input type="hidden" id="r_id" value="<?= $cohort['r_id'] ?>" />
<input type="hidden" id="week_count" value="<?= count($outbound) ?>" />



<div id="acordeon">
    <div class="panel-group" id="accordion">
    
  
      <div class="panel panel-border panel-default" name="collapseTiming">
        <div class="panel-heading" role="tab">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTiming" aria-expanded="false" aria-controls="collapseTiming">
                <h4 class="panel-title">
                Timeline
                <i class="material-icons">keyboard_arrow_down</i>
                </h4>
            </a>
        </div>
        <div id="collapseTiming" class="panel-collapse collapse"> <!-- collapse in -->
          <div class="panel-body">
          		
          		
          		<div class="title"><h4>Cohort Start Week</h4></div>
          		<p>The bootcamp kick-off week:</p>
                <div class="form-group label-floating is-empty">
                    <input type="text" id="r_start_date" value="<?= date("m/d/Y" , strtotime($cohort['r_start_date']) ) ?>" style="width:233px;" class="form-control" />
                    <span class="material-input"></span>
                </div>
                
                
                
                <hr />
                <div class="row">
                	<div class="col-sm-3">Registration Starts:</div>
                    <div class="col-sm-3"><b>June 5th 8:00P</b></div>
                    <div class="col-sm-6">As soon as Bootcamp AND Cohort status are live. Once students start registering, you will review applications, conduct interview to validate qualifications and accept students one by one. If rejected, students will receive a full refund.</div>
                </div>
                <hr />
                <div class="row">
                	<div class="col-sm-3">Registration Ends:</div>
                    <div class="col-sm-3"><b><span id="time_register_end">June 5th 8:00P</span></b></div>
                    <div class="col-sm-6">Students who plan to join must have paid in full by this time. This is when the registration for the next cohort starts.</div>
                </div>
                <hr />
                <div class="row">
                	<div class="col-sm-3">Bootcamp Starts:</div>
                    <div class="col-sm-3"><b><span id="time_cohort_start">June 5th 8:00P</span></b></div>
                    <div class="col-sm-6">Interested students must have paid in full by this time. During the bootcamp students work on the weekly sprints as defined in the <a href="/console/<?= $bootcamp['c_id'] ?>/curriculum">curriculum</a>.</div>
                </div>
                <hr />
                <div class="row">
                	<div class="col-sm-3">Bootcamp Ends:</div>
                    <div class="col-sm-3"><b><span id="time_cohort_end">June 5th 8:00P</span></b></div>
                    <div class="col-sm-6">This has been calculated based on the <?= count($outbound) ?> weekly sprints defined in the <a href="/console/<?= $bootcamp['c_id'] ?>/curriculum">curriculum</a>.</div>
                </div>
                
          </div>
        </div>
      </div>
      
      
      
      
      
      
      <div class="panel panel-border panel-default" name="collapseMentorship">
        <div class="panel-heading" role="tab">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseMentorship" aria-expanded="false" aria-controls="collapseMentorship">
                <h4 class="panel-title">
                Mentor Commitments
                <i class="material-icons">keyboard_arrow_down</i>
                </h4>
            </a>
        </div>
        <div id="collapseMentorship" class="panel-collapse collapse">
          <div class="panel-body">
				
				
				<div class="title"><h4>Office Hours</h4></div>
				<p>Office hours enable students to contact you or your team online (usually using live video chat) and get immediate support for their bootcamp related questions. A few notes:</p>
				<ul>
					<li>Office hours are set in GMT -8:00 Pacific Standard Time. We will adjust this based on each student's time zone.</li>
					<li>Single click on the schedule to drop a time box, and then drag and expand it to represent your hours.</li>
				</ul>
				<iframe id="weekschedule" src="/console/<?= $bootcamp['c_id'] ?>/cohorts/<?= $cohort['r_id'] ?>/scheduler" scrolling="no" class="scheduler-iframe"></iframe>
          
          
          		<div class="title"><h4>Office Contact Method</h4></div>
            	<p>Let students know how then can contact you during office hours:</p>
                <div class="form-group label-floating is-empty">
                    <textarea class="form-control text-edit" rows="2" placeholder="Skype ID, Google Hangout link, Zoom video confrence url, etc..." id="r_office_hour_instructions"><?= $cohort['r_office_hour_instructions'] ?></textarea>
                </div>
                
          		
          		<div class="title"><h4>Office Closed Dates</h4></div>
            	<p>Define the dates that you would not provide office hours between <span id=""></span>, like holidays. Enter the closer dates in plain text format:</p>
                <div class="form-group label-floating is-empty">
                    <textarea class="form-control text-edit" rows="2" id="r_closed_dates"><?= $cohort['r_closed_dates'] ?></textarea>
                </div>
                
          </div>
        </div>
      </div>
      
      
      
      
      
      
      
      <div class="panel panel-border panel-default" name="collapseEnrollment">
        <div class="panel-heading" role="tab">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEnrollment" aria-expanded="false" aria-controls="collapseEnrollment">
                <h4 class="panel-title">
                Enrollment
                <i class="material-icons">keyboard_arrow_down</i>
                </h4>
            </a>
        </div>
        <div id="collapseEnrollment" class="panel-collapse collapse">
          <div class="panel-body">
          
          
            	<div class="title"><h4>Cohort Status</h4></div>
       		 	<?php echo_status_dropdown('r','r_status',$cohort['r_status']); ?>
        		<br />
        		
        		
        		<div class="title"><h4>Enrollment Price</h4></div>
                <div class="input-group">
                  <span class="input-group-addon addon-lean">USD $</span>
                  <input type="number" min="0" step="0.01" style="width:100px; margin-bottom:-5px;" id="r_usd_price" value="<?= $cohort['r_usd_price'] ?>" class="form-control" />
                </div>
                
                
                <div class="title"><h4>Minimum Students</h4></div>
                <p>Define the minimum number of students required to be registered to kick-start this cohort. If this number is not met, all existing registrants would be refunded and the cohort would not be started.</p>
                <div class="input-group">
                  <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_min_students" value="<?= $cohort['r_min_students'] ?>" class="form-control" />
                </div>
                
                <div class="title"><h4>Maximum Students</h4></div>
                <p>Define the maximum number of students that can enroll before cohort is full. 0 means no maximum.</p>
                <div class="input-group">
                  <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_max_students" value="<?= $cohort['r_max_students'] ?>" class="form-control" />
                </div>
                
                
                
          </div>
        </div>
      </div>


  
</div>
</div><!--  end acordeon -->
        


<table width="100%"><tr><td class="save-td"><a href="javascript:save_r();" class="btn btn-primary">Save</a></td><td><span id="save_r_results"></span></td></tr></table>
          
