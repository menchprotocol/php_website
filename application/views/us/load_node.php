<?php 

//Define main navigation:
$sub_navigation = array(
		1 => array(
				'icon' => '@',
				'count_key' => 1,
				'count' => 0,
		),
		3 => array(
				'icon' => '#',
				'count_key' => 3,
				'count' => 0,
		),
		//Arbitrary Key IDs for INs/OUTs:
		44 => array(
				'icon' => ' INs<span class="glyphicon glyphicon-arrow-right blue rotate45" aria-hidden="true"></span>',
				'count_key' => 'parents',
				'append_class' => 'blue',
				'count' => 0,
		),
		45 => array(
				'icon' => null, //To be added later (see below) once we have the count!
				'count_key' => 'children',
				'append_class' => 'pink',
				'count' => 0,
		),
);


//Count stuff to create navigation:
foreach($node as $value){
	if($node[0]['node_id']!==$value['node_id']){
		$sub_navigation[45]['count']++;
		$sub_navigation[$value['grandpa_id']]['count']++;
	} else {
		if(isset($value['parents'][0]) && is_array($value['parents'][0])){
			$sub_navigation[$value['parents'][0]['grandpa_id']]['count']++;
			$sub_navigation[44]['count']++;
		} else {
			//Ooops, this is wierd error!
			log_error('#'.$value['id'].' missing [parents] sub array().', $node);
		}
	}
}

//Append OUT count:
$sub_navigation[45]['icon'] = ' OUTs<span class="glyphicon glyphicon-arrow-up '.($sub_navigation[45]['count']>0?'pink':'grey').' rotate45" aria-hidden="true"></span>';

//Make core data available on JS layer:
$user_data = $this->session->userdata('user');
echo '<script> var node = '.json_encode($node).'; </script>';
echo '<script> var child_count = '.$sub_navigation[45]['count'].'; </script>'; // TODO To be removed by moving entire object into JS layer
echo '<script> var user_data = '.json_encode($user_data).'; </script>';

//The 2nd level navigation:
echo '<ul id="secondNav" class="nav nav-pills">';
echo '<li role="presentation" class="li_all active"><a href="javascript:nav2nd(\'all\')">'.count($node).' Total</a></li>';
foreach($sub_navigation as $sn){
	echo '<li role="presentation" class="li_'.$sn['count_key'].( $sn['count']==0 ? ' disabled' : '').'"><a href="javascript:'.( $sn['count']==0 ? 'void(0)' : 'nav2nd('.( is_integer($sn['count_key']) ? $sn['count_key'] : '\''.$sn['count_key'].'\'').')').'" '.( isset($sn['append_class']) && $sn['count']>0? ' class="'.$sn['append_class'].'"' : '').'>'.$sn['count'].$sn['icon'].'</a></li>';
}
//Custom module for user profiles when logged in:
if($node[0]['node_id']==$user_data['node_id']){
	echo '<li role="presentation" class="pull-right logout"><a href="/logout">Logout <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>';
}
echo '</ul>';




echo '<div class="list-group lgmain">';
	//Show current nodes:
	foreach($node as $key=>$value){
		echo echoNode($node,$key);
	}
	//To add new node:
	if(auth_admin(1)){
		//An input to create a new node or link to an existing node:
		echo '<div class="list-group-item list_input">';
		echo '<form id="addnodeform"><input type="text" class="form-control autosearch" id="addnode" name="node_name" value="" placeholder="+ Add"></form>';
		echo '</div>';
	}
echo '</div>';
?>