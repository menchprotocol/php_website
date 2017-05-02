<?php 
//Make core data available on JS layer:
$user_data = $this->session->userdata('user');
echo '<script> var node = '.json_encode($node).'; </script>';
echo '<script> var child_count = '.count_links($node,'children').'; </script>'; // TODO To be removed!
echo '<script> var user_data = '.json_encode($user_data).'; </script>';


$sub_navigation = array(
		array(
				'icon' => '<span class="glyphicon glyphicon-arrow-left rotate45" aria-hidden="true"></span>',
				'count_key' => 'parents',
		),
		array(
				'icon' => '<span class="glyphicon glyphicon-arrow-right rotate45" aria-hidden="true"></span>',
				'count_key' => 'children',
		),
		array(
				'icon' => '@',
				'count_key' => 1,
		),
		array(
				'icon' => '&',
				'count_key' => 2,
		),
		array(
				'icon' => '#',
				'count_key' => 3,
		),
		array(
				'icon' => '?',
				'count_key' => 4,
		),
		array(
				'icon' => '!',
				'count_key' => 43,
		),
);


echo '<ul id="secondNav" class="nav nav-pills">';
echo '<li role="presentation" class="li_all active"><a href="javascript:nav2nd(\'all\')">All '.count($node).'</a></li>';
foreach($sub_navigation as $sn){
	$count = count_links($node,$sn['count_key']);
	echo '<li role="presentation" class="li_'.$sn['count_key'].( $count==0 ? ' disabled' : '').'"><a href="javascript:'.( $count==0 ? 'void(0)' : 'nav2nd('.( is_integer($sn['count_key']) ? $sn['count_key'] : '\''.$sn['count_key'].'\'').')').'">'.$sn['icon'].$count.'</a></li>';
}


//Custom module for user profiles when logged in:
if($node[0]['node_id']==$user_data['node_id']){
	echo '<li role="presentation" class="pull-right logout"><a href="/logout?from='.$node[0]['node_id'].'">Logout <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>';
}
echo '</ul>';


echo '<div class="list-group">';
	//Show current nodes:
	foreach($node as $key=>$value){
		echo echoNode($node,$key);
	}
	//To add new node:
	if($user_data['is_mod']){
		//An input to create a new node or link to an existing node:
		echo '<div class="list-group-item list_input">';
		echo '<form id="addnodeform"><input type="text" class="form-control autosearch" id="addnode" name="node_name" value="" placeholder="New..."></form>';
		echo '</div>';
	}
echo '</div>';
?>