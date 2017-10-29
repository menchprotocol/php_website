<div class="title"><h4><i class="fa fa-comments" aria-hidden="true"></i> Chat Response Time</h4></div>
<ul>
	<li>Student communication is done on Facebook Messenger using <a href="#" data-toggle="modal" data-target="#MenchBotModal"><i class="fa fa-commenting" aria-hidden="true"></i> <u>MenchBot</u></a>.</li>
	<li>You are required to respond to 100% of incoming student messages.</li>
	<li>You get to choose how fast you commit to responding to messages.</li>
</ul>
<select class="form-control input-mini border" id="r_response_time_hours">
<option value="">Select Responsiveness</option>
<?php 
$r_response_options = $this->config->item('r_response_options');
foreach($r_response_options as $time){
    echo '<option value="'.$time.'" '.( isset($r_response_time_hours) && $r_response_time_hours==$time ? 'selected="selected"' : '' ).'>Under '.echo_hours($time).'</option>';
}
?>
</select>