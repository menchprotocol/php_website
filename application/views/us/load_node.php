<?php 

//Define some JS variables:
$user_data = $this->session->userdata('user');
echo '<input type="hidden" id="children_count" value="'.count($child_data).'">';
echo '<input type="hidden" id="node_id" value="'.$node[0]['node_id'].'">';
echo '<input type="hidden" id="parent_id" value="'.$node[0]['parent_id'].'">';
echo '<input type="hidden" id="is_moderator" value="'.$user_data['is_mod'].'">';


$last_handler = null; //This prevents duplicate printing of parent names
foreach($node as $key=>$value){
	$status = status_descriptions($value['status']);
	$link_content = '<span class="glyphicon glyphicon-time" aria-hidden="true"></span> '.format_timestamp($value['timestamp']).'';
	$edit_lock_type = ($value['parent_id']==44 ? '!OwnerEditOnly' : null); //TODO: Move this to a node and nodeLogic. Ask Shervin.
	//This is used for links that don't have a value
	//but we add a custom value to "value_alt" to make the UI pretty
	$value_field = ( strlen($value['value'])>0 ? $value['value'] : @$value['value_alt'] );
	echo '<div class="row node_details" id="link'.$value['id'].'" data-link-index="'.$key.'" edit-mode="0" new-parent-id="0" data-link-id="'.$value['id'].'">';
	echo '<div class="col-sm-12 handler">';
	if($last_handler!==$value['parent_id'] || 1){
		//TODO consider grouping parent handlers for a cleaner UI. Don't forget edit mode.
		echo '<p class="node_top_node"><a href="/'.$value['parent_id'].'?from='.$node[0]['node_id'].'">'.$value['parent_name'].'</a></p>';
		if($key>0){
			$last_handler=$value['parent_id'];
		}
	}
	echo '</div>';
	echo '<div class="col-sm-12 value">';
	echo ( $key==0 ? '<h1 class="node_h1">'.$value_field.'</h1>' : '<p class="node_h1">'.$value_field.'</p>')/*.'('.$value['index'].')'*/;
	echo '<div class="hover node_stats"><div>';
	//echo '<span><a href="/'.$value['us_id'].'?from=creator">'.$value['us_name'].'</a></span>';
	echo '<span><em title="Link id.'.$value['id'].' added on '.substr($value['timestamp'],0,19).' UTC by @'.clean($value['us_name'],1).'. '.( $edit_lock_type ? 'Locked by '.$edit_lock_type : 'Click to modify' ).'." data-toggle="tooltip" data-placement="bottom">';
			
		if(!$user_data['is_mod']){
			echo $link_content;
		} elseif($edit_lock_type){
			echo $link_content.' '.$edit_lock_type;
		} else {
			echo '<a href="javascript:edit_link('.$key.','.$value['id'].')" class="edit_link">'.$link_content.'</a>';
		}
		
	echo '</em></span>';
	echo '</div></div>';
	echo '</div>';
	echo '</div>';
}


echo '<div id="sortconf"></div>';
echo '<ul class="list-group node_list" id="sortableChild">';
if(count($child_data)>0){
	foreach ($child_data as $key=>$value){
		$p_name = ( in_array($value['index'],array(1,4)) ? $value['parent_name']: $value['title']);
		// Removed for now: <span class="glyphicon glyphicon glyphicon-sort sort-handle" aria-hidden="true"></span> 
		echo '<li class="list-group-item child-node" node-id="'.$value['node_id'].'"><a href="/'.$value['node_id'].'?from='.$node[0]['node_id'].'"><span class="badge">'.($value['child_count']>0?$value['child_count']:'').' <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>'.$p_name.' <span class="link_count">'.( preg_replace("/[^a-zA-Z0-9]+/", "", $value['value'])==preg_replace("/[^a-zA-Z0-9]+/", "", strip_tags($p_name)) ? '' : $value['value'] ).' <span class="glyphicon glyphicon-link" aria-hidden="true"></span>'.$value['links_count'].'</span></a></li>';
	}
}
if($user_data['is_mod']){
	echo '<li class="list-group-item list_input">
		<form id="addnodeform">
		<input type="hidden" name="parent_id" id="parent_node_id" value="'.$node[0]['node_id'].'" />
		<input type="text" class="form-control autosearch" required="required" id="addnode" name="node_name" value="" placeholder="New node...">
		</form>
	</li>';
}

echo '</ul>';



//Custom module for user profiles:
if($node[0]['node_id']==1 && auth(1)){
	//This is the users' account page:
	user_nav($node[0]['node_id']);
}

?>