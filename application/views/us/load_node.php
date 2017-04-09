<?php 

//Custom module for user profiles:
$user_data = $this->session->userdata('user');
if($node[0]['node_id']==$user_data['node_id']){
	//This is the users' account page:
	echo '<p style="text-align:right;"><a href="/logout">Logout <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a> </p>';
}

$parents = parents();
$last_handler = null;
foreach($node as $key=>$value){
	$status = status_descriptions($value['status']);
	echo '<div class="row node_details">';
		echo '<div class="col-sm-3 handler">';
			if($last_handler!==$value['parent_id']){
				echo '<a href="/'.$value['parent_id'].'">'.$value['parent_name'].'</a>';
				if($key>0){
					$last_handler=$value['parent_id'];
				}
			}
		echo '</div>';
		echo '<div class="col-sm-9 value">';
		echo ( $key==0 ? '<h1>'.$value['value'].'</h1>' : $value['value'] )/*.'('.$value['index'].')'*/;
			echo '<div class="hover"><div>';
				echo '<span><a href="/'.$value['us_id'].'">'.$value['us_name'].'</a></span>';
				echo '<span><em title="'.substr($value['timestamp'],0,19).' UTC" data-toggle="tooltip"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> '.format_timestamp($value['timestamp']).'</em></span>';
				echo '<span><em title="'.$status['description'].'" data-toggle="tooltip"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> '.$status['name'].'</em></span>';
				echo '<span><a href="#"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> '.$value['id'].'</a></span>';
				echo '</div></div>';
		echo '</div>';
	echo '</div>';
}



echo '<div class="list-group" style="margin-top:20px" id="sortableChild">';
if(count($child_data)>0){
	foreach ($child_data as $key=>$value){
		echo '<a href="/'.$value['node_id'].'" class="list-group-item context-menu-one"><span class="badge">'.($value['child_count']>0?$value['child_count']:'').' <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>'.( in_array($value['index'],array(1,4)) ? $value['parent_name']: $value['title']).' <span class="link_count"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>'.$value['links_count']./*' ('.$value['index'].')'.*/'</span></a>';
	}
}
echo '</div>';

//print_r($child_data);
?>