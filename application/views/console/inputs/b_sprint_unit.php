<div class="title"><h4><i class="fa fa-hourglass-end" aria-hidden="true"></i> Bootcamp Deadline Frequency</h4></div>
<ul>
	<li>Each <b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b> can be due either Daily or Weekly.</li>
	<li>Action Plans x Frequency = Bootcamp Duration<br />i.e. 5 Action Plans x Weekly Frequency = 35 Days</li>
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