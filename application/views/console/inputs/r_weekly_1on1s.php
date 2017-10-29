<div class="title"><h4><i class="fa fa-handshake-o" aria-hidden="true"></i> 1-on-1 Mentorship Level</h4></div>
<ul>
	<li>Recommended for difficult-to-execute bootcamps to help students 1-on-1.</li>
	<li>Use a Calendar app to manually setup weekly meetings with each student.</li>
	<li>Use a video chat app like Skype, Zoom or Hangouts to conduct meetings.</li>
</ul>
<select class="form-control input-mini border" id="r_weekly_1on1s" style="width:300px;">
<option value="">Select Mentorship Level</option>
<?php 
$weekly_1on1s_options = $this->config->item('r_weekly_1on1s_options');
foreach($weekly_1on1s_options as $time){
    echo '<option value="'.$time.'" '.( isset($r_weekly_1on1s) && $r_weekly_1on1s==$time ? 'selected="selected"' : '' ).'>'.echo_hours($time).' per student per '.(isset($b_sprint_unit)?$b_sprint_unit:null).'</option>';
}
?>
</select>