<?php 
//Make core data available on JS layer:
$user_data = $this->session->userdata('user');
echo '<script> var node = '.json_encode($node).'; </script>';
echo '<script> var child_count = '.count($child_data).'; </script>';
echo '<script> var user_data = '.json_encode($user_data).'; </script>';

//For public access:
echo '<style> .node_details:hover{ background-color:transparent; } </style>'; //Simple design


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
	
	
	if(isset($user_data['id'])){
		echo '<div class="hover node_stats"><div>';
		//echo '<span><a href="/'.$value['us_id'].'?from=creator">'.$value['us_name'].'</a></span>';
		echo '<span><em title="Link id.'.$value['id'].' added on '.substr($value['timestamp'],0,19).' UTC by @'.clean($value['us_name'],1).'.'.( $edit_lock_type ? ' Locked by '.$edit_lock_type.'.' : ( $user_data['is_mod'] ? 'Click to modify.' : '') ).'" data-toggle="tooltip" data-placement="bottom">';
				
		if(!$user_data['is_mod']){
			echo $link_content;
		} elseif($edit_lock_type){
			echo $link_content.' '.$edit_lock_type;
		} else {
			echo '<a href="javascript:edit_link('.$key.','.$value['id'].')" class="edit_link">'.$link_content.'</a>';
		}
			
		echo '</em></span>';
		echo '</div></div>';
	}
	
	echo '</div>';
	echo '</div>';
}





echo '<div id="sortconf"></div>';
echo '<ul class="list-group node_list" id="sortableChild">';
if(count($child_data)>0){
	foreach ($child_data as $value){
		echo print_child($value,$node[0],$user_data);
	}
}
if($user_data['is_mod']){
	echo '<li class="list-group-item list_input">
		<form id="addnodeform">
		<input type="text" class="form-control autosearch" id="addnode" name="node_name" value="" placeholder="New node...">
		</form>
	</li>';
}

echo '</ul>';




//Custom module for user profiles:
if($node[0]['node_id']==$user_data['node_id']){
	//This is the users' account page:
	echo '<div class="list-group node_list">';
	echo '<a href="/logout?from='.$node[0]['node_id'].'" class="list-group-item context-menu-one"><span class="badge"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></span>Logout '.$user_data['title'].'</a>';
	echo '</div>';
}
?>