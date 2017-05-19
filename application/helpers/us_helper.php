<?php


function is_production(){
	return ( $_SERVER['SERVER_NAME']=='us.foundation');
}

function version_salt(){
	//This variable ensures that the CSS/JS files are being updated upon each launch
	//Also appended a timestamp To prevent static file cashing for local development
	//TODO Implemenet in sesseion when user logs in and logout if not matched!
	return 'v0.57'.( auth(1) ? '.'.substr(time(),7) : '' );
}

function boost_power(){
	ini_set('memory_limit','2048M');
	ini_set('max_execution_time', 300);
}

function user_login($user_email,$user_pass){
	
	$CI =& get_instance();
	
	if(!isset($user_email) || !filter_var($user_email, FILTER_VALIDATE_EMAIL)){
		//Invalid email:
		return array(
			'status' => 0,
			'message' => 'Invalid email address.',
		);
	} elseif(!isset($user_pass) || strlen($user_pass)<2){
		//Invalid password:
		return array(
			'status' => 0,
			'message' => 'Invalid password.',
		);
	} else {
		
		//Fetch user nodes with this email:
		//TODO We can wire this in Agolia for faster string search!
		$matching_users = $CI->Us_model->search_node($user_email,24);
		
		if(count($matching_users)<1){
			//We could not find this email linked to the email node
			return array(
				'status' => 0,
				'message' => 'Email "'.$user_email.'" not found.',
			);
		}
		
		//Now fetch entire user node:
		$user_node = $CI->Us_model->fetch_node($matching_users[0]['node_id']);
			
		if($user_node[0]['grandpa_id']!=1){
			//We could not find this email linked to the email node
			//This should technically never happen!
			return array(
				'status' => 0,
				'message' => 'Email not associated to a valid user.',
			);
		}
		
		//Now lets see if this user has a login password and if it matches the entered password
		$has_password = false;
		foreach($user_node as $link){
			if($link['parent_id']==44){
				//TODO: We should prevent duplicate password relations to be created
				//Yes they have a password link attached to the user node!
				$has_password = true;
				
				//Does it match login form entry?
				$matched_password = ($link['value']==sha1($user_pass));
				
				break;
			}
		}
		
		
		if(!$has_password){
			//We could not find this password linked to anyone!
			return array(
				'status' => 0,
				'message' => 'A login password has not been assigned to your account.',
			);
		} elseif(!$matched_password){
			//Invalid
			return array(
				'status' => 0,
					'message' => 'Invalid password for "'.$user_email.'".',
			);
		} else {
			
			//Good to go!
			//Assign some extra variables to return array:
			$user_node[0]['timestamp'] = time();
			
			//Detect if this user is a moderator, IF they belong to Moderators node:
			$user_node[0]['is_mod'] = ( $user_node[0]['parent_id']==18 ? 1 : 0 );
			
			//Log Login history
			//TODO: Enable later. Disabled due to required UI adjustmens!
			/*
			 $new_link = $CI->Us_model->insert_link(array(
			 'us_id' => $matching_users[0]['node_id'],
			 'node_id' => $matching_users[0]['node_id'],
			 'parent_id' => 61, //The login history node
			 'grandpa_id' => 43, //System
			 'action_type' => 4, //For linking
			 ));
			*/
			
			return array(
				'status' => 1,
				'message' => 'Successfully authenticated user.',
				'link' => $user_node[0],
			);
		}
	}
}

function grandparents(){
	//A Javascript version of this function is in main.js
	return array(
			1  => array(
					'name' => 'Entities',
					'sign' => '@',
					'node_id' => 1,
			),
			3  => array(
					'name' => 'Intents',
					'sign' => '#',
					'node_id' => 3,
			),
			4  => array(
					'name' => 'Questions',
					'sign' => '?',
			),
			43 => array(
					'name' => 'Metadata',
					'sign' => '!',
			),
	);
}


function status_descriptions($status_id){
	//translates numerical status fields to descriptive meanings
	if($status_id==-2){
		return array(
				'valid' => 1,
				'name' => 'Deleted',
				'description' => 'When content does not follow community guidelines.',
		);
	} elseif($status_id==-1){
		return array(
				'valid' => 1,
				'name' => 'Updated',
				'description' => 'When a new update replaces this update.',
		);
	} elseif($status_id==0){
		return array(
				'valid' => 1,
				'name' => 'Pending',
				'description' => 'The initial status updates have when submitted by guest users.',
		);
	} elseif($status_id==1){
		return array(
				'valid' => 1,
				'name' => 'Active',
				'description' => 'Active node links with content association.',
		);
	} else {
		//This should never happen!
		return array(
				'valid' => 0,
				'name' => 'Unknown!',
				'description' => 'Error: '.$status_id.' is an unknown status ID.',
		);
	}
}


function action_type_descriptions($action_type_id){
	//translates numerical status fields to descriptive meanings
	if($action_type_id==-4){
		return array(
				'valid' => 1,
				'name' => 'Nuclear',
				'description' => 'Delete node and all direct child nodes.',
		);
	} elseif($action_type_id==-3){
		return array(
				'valid' => 1,
				'name' => 'Delete & Move',
				'description' => 'Delete a node and mode all child nodes to a different node.',
		);
	} elseif($action_type_id==-2){
		return array(
				'valid' => 1,
				'name' => 'Delete Node & Links',
				'description' => 'Delete childless node and all links.',
		);
	} elseif($action_type_id==-1){
		return array(
				'valid' => 1,
				'name' => 'Delete Link',
				'description' => 'Delete node link.',
		);
	} elseif($action_type_id==1){
		return array(
				'valid' => 1,
				'name' => 'Added',
				'description' => 'Created a new link from scratch.',
		);
	} elseif($action_type_id==2){
		return array(
				'valid' => 1,
				'name' => 'Updated',
				'description' => 'Updated the content or parent of the link.',
		);
	} elseif($action_type_id==3){
		return array(
				'valid' => 1,
				'name' => 'Sorted',
				'description' => 'Re-sorted child nodes.',
		);
	} elseif($action_type_id==4){
		return array(
				'valid' => 1,
				'name' => 'Linked',
				'description' => 'Linked two existing nodes to each other.',
		);
	} else {
		//This should never happen!
		return array(
				'valid' => 0,
				'name' => 'Unknown!',
				'description' => 'Error: '.$action_type_id.' is unknown.',
		);
	}
}

function generate_algolia_obj($node_id,$algolia_id=0){
	
	if(!is_production()){ return false; }
	
	$CI =& get_instance();
	
	//Fetch parents:
	$node = $CI->Us_model->fetch_node($node_id);
	//Grandpa Signs:
	$grandparents = grandparents(); //Everything at level 1
	
	//CLeanup and prep for search indexing:
	foreach($node as $i=>$link){
		if($i==0){
			//This is the primary link!
			//Search for grandpas_child_id, which is the node One level below the Grandpa:
			$grandpas_child_id = $CI->Us_model->fetch_grandpas_child($link['node_id']);
			//Lets append some core info:
			$node_search_object = array(
					'node_id' => $link['node_id'],
					'grandpa_id' => $link['grandpa_id'],
					'grandpa_sign' => $grandparents[$link['grandpa_id']]['sign'],
					'grandpas_child_id' => $grandpas_child_id,
					'parent_id' => $link['parent_id'],
					'value' => $link['value'],
					'links_blob' => '',
			);
			if($algolia_id>0){
				$node_search_object['objectID'] = $algolia_id; //This would update
			}
		} elseif(strlen($link['value'])>0){
			//This is a secondary link with a value attached to it
			//Lets add this to the links blob
			$node_search_object['links_blob'] .= strip_tags($link['value']).' ';
		}
	}
	return $node_search_object;
}

function count_links($node,$type){
	$child_count = 0;
	foreach($node as $value){
		if($type=='children' && $node[0]['node_id']!==$value['node_id']){
			$child_count++;
		} elseif($type=='parents' && $node[0]['node_id']==$value['node_id']){
			$child_count++;
		} elseif(is_integer($type)){
			//Lets see parent or child:
			if($value['node_id']==$node[0]['node_id'] && $type==$value['grandpa_id']){
				$child_count++;
			} elseif($value['node_id']!==$node[0]['node_id'] && $type==$value['parents'][0]['grandpa_id']){
				$child_count++;
			}
		}
	}
	return $child_count;
}

function echo_html($status,$message){
	if($status){
		echo '<span class="success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> '.$message.'</span>';
	} else {
		echo '<div><span class="danger"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> '.$message.'</span></div>';
	}
	return $status;
}

function format_timestamp($t){
	
	
	$time = time() - strtotime(substr($t,0,19)); // to get the time since that moment
	$time = ($time<1)? 1 : $time;
	$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hr',
			60 => 'min',
			1 => 'sec'
	);
	
	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	}
}

function redirect_message($url,$message){
	//For message handling across the platform.
	$CI =& get_instance();
	$CI->session->set_flashdata('hm', $message);
	header("Location: ".$url);
	die();
}

function load_algolia($index_name='nodes'){
	require_once('application/libraries/algoliasearch.php');
	$client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
	return $client->initIndex($index_name);
}

function auth($donot_redirect=false){
	$CI =& get_instance();
	$user_data = $CI->session->userdata('user');	
	if($donot_redirect){
		return (isset($user_data['id']));
	} elseif(!isset($user_data['id'])){
		$node_id = $CI->uri->segment(1);
		redirect_message('/login'.( intval($node_id)>0 ? '?from='.intval($node_id) : '' ),'<div class="alert alert-danger" role="alert">Login to access this page.</div>');
	}
}

function auth_admin($donot_redirect=false){
	$CI =& get_instance();
	$user_data = $CI->session->userdata('user');
	$node_id = $CI->uri->segment(1);
	
	if($donot_redirect){
		return (isset($user_data['is_mod']) && $user_data['is_mod']);
	} elseif(!isset($user_data['is_mod']) || !$user_data['is_mod']){
		redirect_message('/login'.( intval($node_id)>0 ? '?from='.intval($node_id) : '' ),'<div class="alert alert-danger" role="alert">Login as moderator to access this page.</div>');
	}
}





function one_two_explode($one,$two,$content){
	if(substr_count($content, $one)<1){
		return NULL;
	}
	$temp = explode($one,$content,2);
	$temp = explode($two,$temp[1],2);
	return trim($temp[0]);
}




function echoNode($node,$key){
	
	$CI =& get_instance();
	$user_data = $CI->session->userdata('user');
	
	//Loop through parent nodes to apply any settings:
	//$status = status_descriptions($node[$key]['status']);
	$return_string = '';
	$is_parent = ($node[0]['node_id']==$node[$key]['node_id']);
	$is_direct = ($node[$key]['ui_parent_rank']==1);	
	
	
	if($is_parent){
		//Parent nodes:
		$href = '/'.$node[$key]['parents'][0]['node_id'].'?from='.$node[0]['node_id']; // SELF: $node[$key]['parents'][0]['node_id']==$node[0]['node_id']
		$anchor = $node[$key]['parents'][0]['value'];
		$direct_anchor = ( $is_direct ? 'DIRECT ' : '').'IN <span class="glyphicon glyphicon-arrow-right rotate45" aria-hidden="true"></span>';
	} else {
		//Child nodes:
		$href = '/'.$node[$key]['node_id'].'?from='.$node[0]['node_id'];
		$anchor = $node[$key]['parents'][0]['value'];
		$direct_anchor = ( $is_direct ? 'DIRECT ' : '').'OUT <span class="glyphicon glyphicon-arrow-up rotate45" aria-hidden="true"></span>';
	}
	
	
	//Go through the UI setting and extract details based on custom-coded nodes:
	$ui_setting = array(
		'template_matched' => 0,
		'auto_open' => 0,
		'value_template' => null,
		'followup_content' => null,
		'node_description' => null,
	);
	
	//First from direct parents:
	if($key>0){
		foreach($node[$key]['parents'] as $k=>$p){
			
			//Custom Node-driven logical block:
			if($p['parent_id']==63 && substr_count($p['value'],'{value}')>0){
				
				//This belogs to the templating node:
				if(substr($p['value'],0,8)=='__eval__'){
					//This needs a PHP evaluation call to attempt to call the function and fill-in {value}
					eval("\$ui_setting['value_template'] = ".str_replace('__eval__','',str_replace('{value}','"'.$node[$key]['value'].'"',$p['value'])).";");
				} else {
					$ui_setting['value_template'] = str_replace('{value}',$node[$key]['value'],$p['value']);
				}
				
				$ui_setting['template_matched']= 1;
				
				if($p['node_id']==237){
					//This is a YouTube embed, lets see if we can find start/end times.
					//This feels like a super-hack! We'll figure out how to make it work...
					$start_time = 0;
					$end_time = 0;
					foreach($node as $p2){
						if($node[0]['node_id']==$p2['node_id'] && intval($p2['value'])>0){
							//This belogs to the templating node:
							if($p2['parent_id']==73){
								$start_time = $p2['value'];
							} elseif($p2['parent_id']==74){
								$end_time = $p2['value'];
							}
						}
					}
					if($start_time>0 || $end_time>0){
						$ui_setting['value_template'] = str_replace( $node[$key]['value'] , $node[$key]['value'].'?start='.$start_time.'&end='.$end_time , $ui_setting['value_template']);
					}
				}
				
			} elseif($p['parent_id']==237){
				//This is a YouTube embed, lets see if we can find start/end times.
				//This feels like a super-hack! We'll figure out how to make it work...
				$start_time = 0;
				$end_time = 0;
				
				foreach($node[$key]['parents'] as $p2){
					if($p2['parent_id']==73){
						$start_time = $p2['value'];
					} elseif($p2['parent_id']==74){
						$end_time = $p2['value'];
					}
				}
				
				if($start_time>0 || $end_time>0){
					//Go one more level deep!
					$parent_node = $CI->Us_model->fetch_node($p['parent_id'], 'fetch_parents');
					
					foreach($parent_node as $p3){
						if($p3['parent_id']==63 && substr_count($p3['value'],'{value}')>0){
							//This is special content fetched from 2 levels deep only for YouTube videos for now:
							$ui_setting['followup_content'] = '<div class="followupContent">'.str_replace('{value}',$p['value'].'?start='.$start_time.'&end='.$end_time,$p3['value']).'</div>';
						}
					}
				}
			} elseif($p['parent_id']==45){
				//This is a description, which we can append:
				$ui_setting['node_description']= $p['value'];
			} elseif($p['parent_id']==463){
				$ui_setting['auto_open'] = 1;
			}
		}
	}
	
	
	//Now go through main templates, assuming this is a child node:
	if($key>0 && !$ui_setting['template_matched'] && !$is_parent){
		//Try searching in the main parent:
		foreach($node as $p){
			if($node[0]['node_id']==$p['node_id'] && $p['parent_id']==63 && substr_count($p['value'],'{value}')>0){
				//This belogs to the templating node:
				$ui_setting['value_template'] = str_replace('{value}',$node[$key]['value'],$p['value']);
				$ui_setting['template_matched']= 1;
			}
		}
	}
	
	
	//Start the display:
	$return_string .= '<div class="list-group-item  '.( $key==0 ? 'is_top' : 'node_details child-node').' '.($is_parent?'is_parents':'is_children').' is_'.$node[$key]['parents'][0]['grandpa_id'].'" id="link'.$node[$key]['id'].'" data-link-index="'.$key.'" is-direct="'.( $is_direct? 1 : 0 ).'" edit-mode="0" new-parent-id="0" data-link-id="'.$node[$key]['id'].'" node-id="'.$node[$key]['node_id'].'">';
	
	$return_string .= 
	'<h4 class="list-group-item-heading handler node_top_node '.( $key==0 ? ' '.($is_parent?'is_parents':'is_children').' is_'.$node[$key]['parents'][0]['grandpa_id'].' node_details' : '').'">'.
	
		'<a href="'.$href.'" class="expA"><span class="boldbadge badge '.( !$is_parent? 'pink-bg' : 'blue-bg').( $node[$key]['link_count']<=1 ? '-light' : '' ).'" aria-hidden="true" title="'.( $is_direct ? 'DIRECT links define Gem origin & fabric.' : 'Regular links for association.' ).'" data-toggle="tooltip">'.$direct_anchor.'</span></a>'.
		
		'<a href="javascript:toggleValue('.$node[$key]['id'].');" class="'.( $key==0 ? 'parentTopLink' : 'parentLink '.( $ui_setting['auto_open'] ? 'zoom-out' : 'zoom-in' )).'">'.
			
		( $key==0 ? '' : '<span class="glyphicon gh'.$node[$key]['id'].' glyphicon-triangle-'.( $ui_setting['auto_open'] ? 'bottom' : 'right' ).'" aria-hidden="true"></span>' ).
		
				//Toggle handle:
				$node[$key]['parents'][0]['sign'] . '<span id="tl'.$node[$key]['id'].'">'.$anchor.'</span>'.
																
				( $ui_setting['node_description'] ? ' <span class="glyphicon glyphicon-info-sign grey hastt" aria-hidden="true" title="'.strip_tags($ui_setting['node_description']).'" data-toggle="tooltip"></span>' : '').
								
				( $node[$key]['status']<1 ? ' <span class="hastt grey" title="Pending Gem Collector Approval" data-toggle="tooltip"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true" style="color:#FF0000;"></span></span>' : '' ).
				
				' <span class="grey hastt" title="'.( $node[$key]['link_count']==1 ? 'This Gem is Single! Follow and add more Gems :)' : $node[$key]['link_count'].' Gems at next step.').'" data-toggle="tooltip" aria-hidden="true"><span class="glyphicon glyphicon-link"></span>'.$node[$key]['link_count'].'</span>'.
				
				
				//TODO '<span class="grey hastt" style="padding-left:5px;" title="54 User Message Reads and 156 Foundation Clicks (Community Engagement)" data-toggle="tooltip"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> 210</span>'.
				
				(count($node[$key]['parents'])>19 ? ' <span class="red">!'.count($node[$key]['parents']).'IN</span>' : '' ).' <span class="sortconf"></span>'.
				
		'</a>'.
	'</h4>';
	
	$return_string .= '<div id="linkval'.$node[$key]['id'].'" class="link-details value '.( $key==0 ? 'is_top' : '').'" style="display:'.( $ui_setting['auto_open'] ?'block':'none').';">';
	$return_string .= '<'.( $key==0 ? 'h1' : 'p').' class="list-group-item-text node_h1 '.( $key==0 ? 'is_top' : '').'">';
	
	
	//Did we find any template matches? If not, just display:W
	if(!$ui_setting['template_matched']){
		$return_string .= nl2br($node[$key]['value']);
	} else {
		$return_string .= $ui_setting['value_template'];
	}
	
	//This is only used for special nodes for now:
	$return_string .= $ui_setting['followup_content'];
	
	$return_string .= '</'.( $key==0 ? 'h1' : 'p').'>';
	$return_string .= '<div class="list-group-item-text hover node_stats"><div>';
	//TODO $return_string .= '<span title="Revision history to browse previous versions." data-toggle="tooltip" class="hastt"><a href="alert(\'Version Tracking Under Development\')"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> 5</a></span>';
	$return_string .= '<span title="Unique Gem ID, assigned per each revision." data-toggle="tooltip" class="hastt"><img src="/img/gem/diamond_16.png" width="16" class="light" />'.$node[$key]['id'].'</span>';
	$return_string .= '<span title="@'.$node[$key]['us_name'].' is the Gem Collector." data-toggle="tooltip"><a href="/'.$node[$key]['us_id'].'">@'.$node[$key]['us_name'].'</a></span>';
	$return_string .= '<span title="Gem collected at '.substr($node[$key]['timestamp'],0,19).' UTC timezone." data-toggle="tooltip" class="hastt"><span class="glyphicon glyphicon-time" aria-hidden="true"></span>'.format_timestamp($node[$key]['timestamp']).'</span>';
	
	
	if(auth_admin(1)){
		$return_string .= '<span title="Knowledge improves one step at a time, so does this line. Make a contribution and earn points." data-toggle="tooltip"><a href="javascript:edit_link('.$key.','.$node[$key]['id'].')" class="edit_link" title="Link ID '.$node[$key]['id'].'"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Improve</a></span>';		
		/* TODO Implement later
		if(!$is_direct){
			$return_string .= '<span><a href="javascript:edit_link('.$key.','.$node[$key]['id'].')" class="edit_link" aria-hidden="true" title="Inverse the direction of the link" data-toggle="tooltip"><span class="glyphicon glyphicon-sort rotate45" aria-hidden="true"></span>Inverse</a></span>';
		}
		*/
	} else {
		$return_string .= '<span title="Request admin access to start collecting Gems." data-toggle="tooltip" class="hastt"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Limited Access</span>';
	}
	$return_string .= '</div></div>';
	$return_string .= '</div>';
	$return_string .= '</div>';
	
	//Return:
	return $return_string;
}



function echoFetchNode($parent_id,$node_id,$regular=1){
	$CI =& get_instance();
	//Load $node_id with parent $parent_id
	$node = $CI->Us_model->fetch_full_node(($regular ? $parent_id : $node_id));
	$match_key = 0; //We need to find this based on $node_id
	foreach($node as $key=>$value){
		if($value['node_id']==$node_id && $value['parent_id']==$parent_id){
			$match_key = $key;
			break;
		}
	}
	if($match_key>0){
		//Should always be greater than zero:
		return echoNode($node,$match_key);
	} else {
		//TODO Sould never happen, put alarm system in place, so 
		//     in case it does we get auto notified...
		echo_html(0,'Refresh to see Node.');
	}
}
