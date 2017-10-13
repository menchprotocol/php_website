
<style>
table, tr, td, th { text-align:left !important; font-size:14px; cursor:default !important; line-height:120% !important; }
th { font-weight:bold !important; }
td { padding:5px 0 !important; }
</style>
<table class="table table-condensed table-striped">
<thead>
	<tr>
		<th style="width:70px;">Time</th>
		<th style="width:80px;">Initiator</th>
		<th style="width:80px;">Type</th>
		<th style="max-width:300px;">Message</th>
		<th>Object</th>
		<th>Bootcamp</th>
		<th style="width:50px;">Data</th>
	</tr>
</thead>
<tbody>
<?php 
//Fetch objects
$object_names = $this->config->item('object_names');

foreach($engagements as $e){
    echo '<tr>';
        echo '<td><span aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Engagement #'.$e['e_id'].'">'.time_format($e['e_timestamp']).'</span></td>';
        echo '<td>'.( $e['e_creator_id']>0 ? $e['u_fname'].' '.$e['u_lname'] : 'System' ).'</td>';
        echo '<td>'.$e['a_name'].( strlen($e['a_desc'])>0 ? ' <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="'.$e['a_desc'].'"></i>' : '' ).( strlen($e['a_learn_more_url'])>0 ? ' <a href="'.$e['a_learn_more_url'].'" target="_blank"><i class="fa fa-external-link" aria-hidden="true" data-toggle="tooltip" title="Learn More (New Window)"></i></a>' : '' ).'</td>';
        echo '<td>'.( strlen($e['e_message'])>0 ? nl2br($e['e_message']) : '' ).'</td>';
        echo '<td>'.( $e['e_object_id']>0 || strlen($e['a_object_code'])>0 ? $object_names[$e['a_object_code']].' #'.$e['e_object_id'] : '' ).'</td>';
        echo '<td>'.( $e['e_b_id']>0 ? $e['e_b_id'] : '' ).'</td>';
        echo '<td>'.( strlen($e['e_json'])>0 ? '<a href="alert();">Inspect</a>' : '' ).'</td>';
    echo '</tr>';
}
?>
</tbody>
</table>