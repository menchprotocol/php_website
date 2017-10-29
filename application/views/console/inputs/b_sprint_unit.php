<div class="title"><h4><i class="fa fa-hourglass-end" aria-hidden="true"></i> Action Plan Rhythms</h4></div>
<ul>
	<li>Each Action Plan can be due either Daily or Weekly.</li>
	<li>Rhythm x Action Plans = Duration (Example: Weekly x 5 Action Plans = 35 Days)</li>
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