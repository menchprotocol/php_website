<div class="title"><h4><i class="fa fa-hourglass-end" aria-hidden="true"></i> Milestone Submission Frequency <span id="hb_600" class="help_button" intent-id="600"></span></h4></div>
<div class="help_body maxout" id="content_600"></div>
<?php 
$sprint_units = $this->config->item('sprint_units');
foreach($sprint_units as $key=>$sprint_unit){
    echo '<div class="radio">
	<label>
		<input type="radio" name="b_sprint_unit" value="'.$key.'" '.( isset($b_sprint_unit) && $b_sprint_unit==$key ? 'checked="true"' : '' ).' />
		'.$sprint_unit['name'].'<i style="font-weight:normal; font-style:normal;">: '.$sprint_unit['desc'].'</i>
	</label>
	</div>';
}
?>