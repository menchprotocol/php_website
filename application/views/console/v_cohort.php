<h1><?= time_format($run['r_start_date'],1) ?> Cohort Settings</h1>

<br />
<input type="hidden" id="r_id" value="<?= $run['r_id'] ?>" />

<div class="title"><h4>Student Commitment Level</h4></div>
<select id="r_pace_id" style="margin-top:9px;">
	<?php
	$r_pace_options = $this->config->item('r_pace_options');
	foreach($r_pace_options as $pace_id=>$pace){
	    if($pace_id<1){
	        continue;
	    }
	    echo '<option value="'.$pace_id.'" '.($run['r_pace_id']==$pace_id ? 'selected="selected"' : '').'>'.$pace['p_name'].': '.$pace['p_hours'].'</option>';
	}
	?>
</select><br /><br />


<div class="title"><h4>Starting Week (Monday)</h4></div>
<div class="form-group label-floating is-empty">
    <input type="text" id="r_start_date" value="<?= date("m/d/Y" , strtotime($run['r_start_date']) ) ?>" style="width:233px;" class="form-control" />
    <span class="material-input"></span>
</div>

<div class="title"><h4>Ending Week (Sunday)</h4></div>
<?php 
$outbound = $this->Db_model->cr_outbound_fetch(array(
    'cr.cr_inbound_id' => $bootcamp['c_id'],
    'cr.cr_status >=' => 0,
));
echo '<p style="padding-left:25px;"><b>'.count($outbound).' Weeks Later</b> based on <a href="/console/'.$bootcamp['c_id'].'/content">weekly sprints</a>.<br />You can modify sprints before publishing this cohort.</p>';
?>


<div class="title"><h4>Enrollment Price</h4></div>
<div class="input-group">
  <span class="input-group-addon addon-lean">USD $</span>
  <input type="number" min="0" step="0.01" style="width:100px; margin-bottom:-5px;" id="r_usd_price" value="<?= $run['r_usd_price'] ?>" class="form-control" />
</div>


<div class="title"><h4>Minimum Students <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Define the minimum number of students required to kick-start this cohort."></i></h4></div>
<div class="input-group">
  <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_min_students" value="<?= $run['r_min_students'] ?>" class="form-control" />
</div>

<div class="title"><h4>Maximum Students <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Define the maximum number of students that can enroll before cohort is full. 0 means no maximum."></i></h4></div>
<div class="input-group">
  <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_max_students" value="<?= $run['r_max_students'] ?>" class="form-control" />
</div>            
    


<div class="title"><h4>Closes Dates <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Type in the dates that the bootcamp mentors would not be available in plain text format."></i></h4></div>
<div class="form-group label-floating is-empty">
    <textarea class="form-control text-edit" rows="2" id="r_closed_dates"><?= $run['r_closed_dates'] ?></textarea>
</div>


<div class="title"><h4>Status</h4></div>
<?php echo_status_dropdown('r','r_status',$run['r_status']); ?>
          


<div class="row" style="clear:both;">
  <div class="col-xs-6"><a href="javascript:save_r();" class="btn btn-primary">Save</a> <span id="save_r_results"></span></div>
  <div class="col-xs-6 action-right">
  </div>
</div>
