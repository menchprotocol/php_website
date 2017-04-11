<?php 

//Custom module for user profiles:
$user_data = $this->session->userdata('user');
if($node[0]['node_id']==$user_data['node_id']){
	//This is the users' account page:
	echo '<p style="text-align:right;"><a href="/logout">Logout <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a> </p>';
}


$last_handler = null; //Prevent duplicate printing of parent names
foreach($node as $key=>$value){
	$status = status_descriptions($value['status']);
	//This is used for links that don't have a value
	//but we add a custom value to "value_alt" to make the UI pretty
	$value_field = ( strlen($value['value'])>0 ? $value['value'] : @$value['value_alt'] );
	echo '<div class="row node_details" id="link'.$value['id'].'">';
	echo '<div class="col-sm-3 handler">';
	if($last_handler!==$value['parent_id'] || 1){
		//TODO consider grouping parent handlers for a cleaner UI. Don't forget edit mode.
		echo '<a href="/'.$value['parent_id'].'?from='.$node[0]['node_id'].'">'.$value['parent_name'].'</a>';
		if($key>0){
			$last_handler=$value['parent_id'];
		}
	}
	echo '</div>';
	echo '<div class="col-sm-9 value">';
	echo ( $key==0 ? '<h1>'.$value_field.'</h1>' : '<p>'.$value_field.'</p>')/*.'('.$value['index'].')'*/;
	echo '<div class="hover"><div>';
	//echo '<span><span class="glyphicon glyphicon-link" aria-hidden="true"></span> '.$value['id'].'</span>';
	echo '<span><a href="/'.$value['us_id'].'?from=creator">'.$value['us_name'].'</a></span>';
	echo '<span><em title="'.substr($value['timestamp'],0,19).' UTC" data-toggle="tooltip"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> '.format_timestamp($value['timestamp']).'</em></span>';
	echo '<span><em title="'.$status['description'].'" data-toggle="tooltip"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> '.$status['name'].'</em></span>';
	echo '<span><em title="Edit or delete this link." data-toggle="tooltip"><a href="javascript:edit_link('.$key.','.$value['id'].')" class="edit_link"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> '.$value['id'].'</a></em></span>';
	echo '</div></div>';
	echo '</div>';
	echo '</div>';
}


echo '<div class="list-group" style="margin-top:20px" id="sortableChild">';
if(count($child_data)>0){
	foreach ($child_data as $key=>$value){
		echo '<a href="/'.$value['node_id'].'?from='.$node[0]['node_id'].'" class="list-group-item context-menu-one"><span class="badge">'.($value['child_count']>0?$value['child_count']:'').' <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>'.( in_array($value['index'],array(1,4)) ? $value['parent_name']: $value['title']).' <span class="link_count"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>'.$value['links_count'].'</span></a>';
	}
}
echo '<div class="list-group-item list_input">
		<form id="addnodeform">
		<input type="hidden" name="parent_id" id="parent_node_id" value="'.$node[0]['node_id'].'" />
		<input type="text" class="form-control autosearch" required="required" id="addnode" name="node_name" value="" placeholder="New node...">
		</form>
	</div>';

echo '</div>';


?>