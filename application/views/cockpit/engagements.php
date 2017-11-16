<script>
function show_log(e_json,e_id){
	console.log(e_json);
	$('#icon_'+e_id).css('color','#00CC00');
}
</script>

<style>
table, tr, td, th { text-align:left !important; font-size:14px; cursor:default !important; line-height:120% !important; }
th { font-weight:bold !important; }
td { padding:5px 0 !important; }
</style>
<table class="table table-condensed table-striped">
<thead>
	<tr>
		<th style="width:120px;">Time</th>
		<th style="width:120px;">Action</th>
		<th style="max-width:300px;">Message</th>
		<!-- <th style="width:50px;">Data</th> -->
		<th style="width:270px;">References</th>
		<th style="width:30px; text-align:center !important;">&nbsp;</th>
	</tr>
</thead>
<tbody>
<?php 
//Fetch reference types:
$engagement_references = $this->config->item('engagement_references');

//Fetch objects
foreach($engagements as $e){
    echo '<tr>';
        echo '<td><span aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Engagement #'.$e['e_id'].'" class="underdot">'.time_format($e['e_timestamp']).'</span></td>';
        echo '<td><span data-toggle="tooltip" title="'.$e['a_desc'].' (Type #'.$e['a_id'].')" aria-hidden="true" data-placement="right" class="underdot">'.$e['a_name'].'</span></td>';
        echo '<td>'.( strlen($e['e_message'])>0 ? format_e_message($e['e_message']) : '' ).( $e['e_cron_job']==0 ? '<div style="color:#008000;"><i class="fa fa-spinner fa-spin fa-3x fa-fw" style="font-size:14px;"></i> Pending Sync to Mench Cloud...</div>' : '' ).'</td>';
        //echo '<td>'.( strlen($e['e_json'])>0 ? '<a href="#" aria-hidden="true" data-toggle="tooltip" data-placement="left" style="color:#AAA;" title="Inspect Engagement Data" class="underdot"><i class="fa fa-search-plus" aria-hidden="true"></i></a>' : '' ).'</td>';
        echo '<td>';
            //Lets go through all references to see what is there:
            foreach($engagement_references as $engagement_field=>$er){
                if(intval($e[$engagement_field])>0){
                    //Yes we have a value here:
                    echo '<div>'.$er['name'].': '.object_link($er['object_code'], $e[$engagement_field], $e['e_b_id']).'</div>';
                }
            }
        echo '</td>';
        echo '<td style="text-align:center !important;">'.( strlen($e['e_json'])>0 ? '<script> e_json_'.$e['e_id'].' = '.$e['e_json'].'; </script><a href="javascript:show_log(e_json_'.$e['e_id'].','.$e['e_id'].');" data-toggle="tooltip" title="Load JSON data in the browser console to analyze further" aria-hidden="true" data-placement="left"><i class="fa fa-search-plus" id="icon_'.$e['e_id'].'" aria-hidden="true"></i></a>' : '' ).'</td>';
        echo '</tr>';
}
?>
</tbody>
</table>