<div class="title"><h4><i class="fa fa-hourglass-end" aria-hidden="true"></i> Milestone Submission Frequency</h4></div>
<ul>

	<li><b><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b> is a group of <b style="display:inline-block;"><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b> that help students accomplish the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamp Objective</b>.</li>
	<li>Students should complete each <b style="display:inline-block;"><i class="fa fa-flag" aria-hidden="true"></i> Milestone</b> either Daily or Weekly.</li>
	<li>Bootcamp Duration = # of Milestones x Frequency<br />Example: 5 Milestones x Weekly = 35 Days</li>
</ul>
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