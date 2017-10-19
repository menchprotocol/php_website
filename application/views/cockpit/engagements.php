
<style>
table, tr, td, th { text-align:left !important; font-size:14px; cursor:default !important; line-height:120% !important; }
th { font-weight:bold !important; }
td { padding:5px 0 !important; }
</style>
<table class="table table-condensed table-striped">
<thead>
	<tr>
		<th style="width:120px;">Time</th>
		<th style="width:120px;">Initiator</th>
		<th style="width:120px;">Action</th>
		<th style="max-width:300px;">Message</th>
		<!-- <th style="width:50px;">Data</th>  -->
		<th style="width:110px;">Applied To</th>
		<th style="width:140px;">Bootcamp <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="If Set, this means that the bootcamp team can also view this engagement in their Activity Timeline"></i></th>
	</tr>
</thead>
<tbody>
<?php 
//Fetch objects
$core_objects = $this->config->item('core_objects');
foreach($engagements as $e){
    echo '<tr>';
        echo '<td><span aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Engagement #'.$e['e_id'].'" class="underdot">'.time_format($e['e_timestamp']).'</span></td>';
        echo '<td>'.( $e['e_creator_id']>0 ? $e['u_fname'].' '.$e['u_lname'] : 'System' ).'</td>';
        echo '<td><span data-toggle="tooltip" title="'.$e['a_desc'].' (Type #'.$e['a_id'].')" aria-hidden="true" data-placement="right" class="underdot">'.$e['a_name'].'</span></td>';
        echo '<td>'.( strlen($e['e_message'])>0 ? format_e_message($e['e_message']) : '' ).'</td>';
        //echo '<td>'.( strlen($e['e_json'])>0 ? '<a href="#" aria-hidden="true" data-toggle="tooltip" data-placement="left" style="color:#AAA;" title="Inspect Engagement Data" class="underdot"><i class="fa fa-search-plus" aria-hidden="true"></i></a>' : '' ).'</td>';
        echo '<td>';
            if($e['e_object_id']>0 || strlen($e['a_object_code'])>0){
                //Is there a specific title to fetch?
                echo $core_objects[$e['a_object_code']]['o_name'].' #'.$e['e_object_id'];
            }
        echo '</td>';
        echo '<td>'.( $e['e_b_id']>0 ? '<a href="/console/'.$e['b_id'].'">'.$e['c_objective'].'</a>' : '' ).'</td>';
    echo '</tr>';
}
?>
</tbody>
</table>