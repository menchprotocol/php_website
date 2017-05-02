<?php


function is_production(){
	return ( $_SERVER['SERVER_NAME']=='us.foundation');
}

function version_salt(){
	//This variable ensures that the CSS/JS files are being updated upon each launch
	//Also appended a timestamp To prevent static file cashing for local development
	//TODO Implemenet in sesseion when user logs in and logout if not matched!
	return 'v0.49'.( is_production() ? '' : '.'.substr(time(),4) );
}

function parents(){
	//A Javascript version of this function is in main.js
	return array(
		1  => array(
			'name' => 'Us',
			'sign' => '@',
			'node_id' => 1,
		),
		2  => array(
			'name' => 'Sources',
			'sign' => '&',
			'node_id' => 2,
		),
		3  => array(
			'name' => 'Goals',
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
				'name' => 'Deleted',
				'description' => 'When content does not follow community guidelines.',
		);
	} elseif($status_id==-1){
		return array(
				'name' => 'Updated',
				'description' => 'When a new update replaces this update.',
		);
	} elseif($status_id==0){
		return array(
				'name' => 'Pending',
				'description' => 'The initial status updates have when submitted by guest users.',
		);
	} elseif($status_id==1){
		return array(
				'name' => 'Primary',
				'description' => 'The top link for the given node.',
		);
	} elseif($status_id==2){
		return array(
				'name' => 'Active',
				'description' => 'Active node links with content association.',
		);
	} elseif($status_id==3){
		return array(
				'name' => 'Active',
				'description' => 'Naked node link without content association.',
		);
	} else {
		//This should never happen!
		return array(
				'name' => 'Unknown!',
				'description' => 'Error: '.$status_id.' is an unknown status ID.',
		);
	}
}


function action_type_descriptions($action_type_id){
	//translates numerical status fields to descriptive meanings
	if($action_type_id==-4){
		return array(
				'name' => 'Nuclear',
				'description' => 'Delete node and all child nodes.',
		);
	} elseif($action_type_id==-3){
		return array(
				'name' => 'Delete & Move',
				'description' => 'Delete a node and mode all child nodes to a different node.',
		);
	} elseif($action_type_id==-2){
		return array(
				'name' => 'Delete Node & Links',
				'description' => 'Delete childless node and all links.',
		);
	} elseif($action_type_id==-1){
		return array(
				'name' => 'Delete Link',
				'description' => 'Delete node link.',
		);
	} elseif($action_type_id==0){
		return array(
			'name' => 'Pending',
			'description' => 'Added, but pending moderation.',
		);
	} elseif($action_type_id==1){
		return array(
			'name' => 'Added',
			'description' => 'Created a new link from scratch.',
		);
	} elseif($action_type_id==2){
		return array(
			'name' => 'Updated',
			'description' => 'Updated the content or parent of the link.',
		);
	} elseif($action_type_id==3){
		return array(
			'name' => 'Sorted',
			'description' => 'Re-sorted child nodes.',
		);
	} elseif($action_type_id==4){
		return array(
			'name' => 'Linked',
			'description' => 'Linked two existing nodes to each other.',
		);
	} else {
		//This should never happen!
		return array(
			'name' => 'Unknown!',
			'description' => 'Error: '.$action_type_id.' is unknown.',
		);
	}
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

function next_node_id(){
	//Find the current largest node id and increments it by 1:
	$CI =& get_instance();
	$largest_node_id = $CI->Us_model->largest_node_id();
	$largest_node_id++;
	return $largest_node_id;
}

function alphanum($string){
	return preg_replace("/[^a-zA-Z0-9]+/", "", strip_tags($string));
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
	$timestamp = strtotime(substr($t,0,19));
	$format = ( date("Y",$timestamp)==date("Y") ? "j M" : "j M Y");
	return date($format,$timestamp);
}

function clean($string,$noblank=false){
	//return str_replace(" ", ($noblank?'':"<span class='sp'> </span>"), $string);
	return str_replace(" ", ($noblank?'':" "), $string);
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

function admin_error($message){
	//TODO: Email $message to admin for review.
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
		return $user_data['is_mod'];
	} elseif(!$user_data['is_mod']){
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
	$status = status_descriptions($node[$key]['status']);
	$is_parent = ($node[0]['node_id']==$node[$key]['node_id']);
	$edit_lock_type = ($node[$key]['parent_id']==44 ? '!OwnerEditOnly' : null); //TODO: Move this to a node and nodeLogic. Ask Shervin.
	$return_string = '';
	
	
	
	if($is_parent){
		//Parent nodes:
		$href = ( $node[$key]['parents'][0]['node_id']==$node[0]['node_id']? null : '/'.$node[$key]['parents'][0]['node_id'].'?from='.$node[0]['node_id'] );
		$anchor = $node[$key]['parents'][0]['sign'].$node[$key]['parents'][0]['value'];
		$direct_anchor = '<span class="glyphicon glyphicon-arrow-left rotate45" aria-hidden="true"></span> '.$node[$key]['link_count'];
	} else {
		//Child nodes:
		$href = '/'.$node[$key]['node_id'].'?from='.$node[0]['node_id'];
		$anchor = $node[$key]['parents'][0]['sign'].$node[$key]['parents'][0]['value'];
		$direct_anchor = $node[$key]['link_count'].' <span class="glyphicon glyphicon-arrow-right rotate45" aria-hidden="true"></span>';
	}
	
	
	//Start the display:
	$return_string .= '<div class="list-group-item  '.( $key==0 ? 'is_top' : 'node_details child-node').' '.($is_parent?'is_parents':'is_children').' is_'.$node[$key]['parents'][0]['grandpa_id'].'" id="link'.$node[$key]['id'].'" data-link-index="'.$key.'" edit-mode="0" new-parent-id="0" data-link-id="'.$node[$key]['id'].'" node-id="'.$node[$key]['node_id'].'">';
	
	$return_string .= '<h4 class="list-group-item-heading handler node_top_node '.( $key==0 ? ' '.($is_parent?'is_parents':'is_children').' is_'.$node[$key]['parents'][0]['grandpa_id'].' node_details' : '').'">'.( $href ? '<a href="'.$href.'"><span class="badge">'.$direct_anchor.'</span></a>' : '<span class="badge grey-bg">'.$direct_anchor.'</span>').'<a href="javascript:toggleValue('.$node[$key]['id'].');" class="parentLink">'.$anchor.( $key>0 ? '' : ' <span class="glyphicon glyphicon-bookmark grey hastt" aria-hidden="true" title="The primary node" data-toggle="tooltip"></span>').' <span class="sortconf"></span></a></h4>';
	
	$return_string .= '<div id="linkval'.$node[$key]['id'].'" class="link-details value '.( $key==0 ? 'is_top' : '').'">';
	$return_string .= '<'.( $key==0 ? 'h1' : 'p').' class="list-group-item-text node_h1 '.( $key==0 ? 'is_top' : '').'">';
	//Search for display logic:
	$matched = 0;
	$value_template = null;
	$followup_content = null;
	//First from direct parents:
	if($key>0){
		foreach($node[$key]['parents'] as $k=>$p){
			
			//Custom Node-driven logical block:
			if($p['parent_id']==63 && substr_count($p['value'],'{value}')>0){
				
				//This belogs to the templating node:
				if(substr($p['value'],0,8)=='__eval__'){
					//This needs a PHP evaluation call to attempt to call the function and fill-in {value}
					eval("\$value_template = ".str_replace('__eval__','',str_replace('{value}','"'.$node[$key]['value'].'"',$p['value'])).";");
				} else {
					$value_template = str_replace('{value}',$node[$key]['value'],$p['value']);
				}
				
				$matched = 1;
				
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
						$value_template = str_replace( $node[$key]['value'] , $node[$key]['value'].'?start='.$start_time.'&end='.$end_time , $value_template );
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
							$followup_content = '<div class="followupContent">'.str_replace('{value}',$p['value'].'?start='.$start_time.'&end='.$end_time,$p3['value']).'</div>';
						}
					}
				}
			}
		}
	}
	
	
	//Now go through main templates, assuming this is a child node:
	if($key>0 && !$matched && !$is_parent){
		//Try searching in the main parent:
		foreach($node as $p){
			if($node[0]['node_id']==$p['node_id'] && $p['parent_id']==63 && substr_count($p['value'],'{value}')>0){
				//This belogs to the templating node:
				$value_template = str_replace('{value}',$node[$key]['value'],$p['value']);
				$matched = 1;
			}
		}
	}
	
	
	//Did we find any template matches? If not, just display:
	
	if(!$matched){
		$return_string .= $node[$key]['value'];
	} else {
		$return_string .= $value_template;
	}
	
	//This is only used for special nodes for now:
	$return_string .= $followup_content;
	
	$return_string .= '</'.( $key==0 ? 'h1' : 'p').'>';
	$return_string .= '<div class="list-group-item-text hover node_stats"><div>';
	$return_string .= '<span title="'.substr($node[$key]['timestamp'],0,19).' UTC" data-toggle="tooltip"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> '.format_timestamp($node[$key]['timestamp']).'</span>';
	$return_string .= '<span><a href="/'.$node[$key]['us_id'].'">@'.$node[$key]['us_name'].'</a></span>';
	
	if($user_data['is_mod']){
		$return_string .= '<span><a href="javascript:edit_link('.$key.','.$node[$key]['id'].')" class="edit_link" title="Link ID '.$node[$key]['id'].'"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Edit</a></span>';
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


function http_404($message){
	header("HTTP/1.1 404 ".$message);
	die();
}

function valid_hashtag($text){
	//TODO expand upon this, set hashtag policy, check first letter, etc...
	return (ctype_alnum($text));
}

function all_ses_data(){
	$CI =& get_instance();
	return $CI->session->all_userdata();
}







function prep_metadata_for_edit($data){
	//TODO: implement
	//This function translates the original data into an editable mode:
	$return_array = array();
	foreach($data as $d){
		if($d['hide_from_ui']=='t'){
			//Skip this guy:
			continue;
		}
		
		//What do HTML inputs take for editing?
		if($d['type_id']==3){
			//Date
			$return_array[$d['clean_name']] = date('Y-m-d' , $d['value_int']);
		} elseif($d['type_id']==2){
			//Date/Time
			$return_array[$d['clean_name']] = date('Y-m-d\TH:i:s' , $d['value_int']);
		} elseif(strlen($d['value_string'])>0){
			//Any other string field
			//Need to cleanup the single quote:
			$return_array[$d['clean_name']] = str_replace('\'','&apos;',$d['value_string']);
		} else {
			//This is an integer
			$return_array[$d['clean_name']] = $d['value_int'];
		}
	}
	return $return_array;
}


function data_validate_cleanup($type_id,$value){
	//TODO: implement
	/*
	$CI =& get_instance();
	$value = trim($value);
	
	if(strlen($value)<=0){
		//Nothing has been passed!
		if($type_id==5){
			//If a checkbox is false, it would return null, so lets return false:
			return 0;
		} else {
			return null;
		}
	}
	
	if($type_id==1){
		//External ID
		return ( intval($value)>0 ? intval($value) : null );
	} elseif($type_id==5){
		//checkbox, which is never NULL
		return ( strtolower($value)=='on' || intval($value) ? 1 : 0 );
	} elseif($type_id==9){
		//Phone number:
		$phone_number = preg_replace('/\D/', '', $value);
		return ( strlen($phone_number)>=4 ? $phone_number : null );
	} elseif($type_id==2 || $type_id==3){
		//Date/Time && Date
		return ( strtotime($value) ? strtotime($value) : null );
	} elseif($type_id==11 || $type_id==8){
		//Number and dollar, both MAY have decimal values
		return floatval($value);
	} elseif($type_id==6){
		//Email address:
		return ( filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : null );
	} elseif($type_id==10){
		//URL
		return ( filter_var($value, FILTER_VALIDATE_URL) ? $value : null );
	} elseif($type_id==13){
		//Pattern reference ID
		$validate_pattern = $CI->Patterns_model->fetch_pattern_from_id(intval($value));
		return ( $validate_pattern['id'] ? intval($value) : null );
	} elseif($type_id==4 || $type_id==12){
		//Text & Text Area
		return $value;
	} elseif($type_id==7){
		//Pick list
		//TODO: Validate with database possible inputs to ensure it matches!
		return $value;
	} elseif($type_id==14){
		//TODO: Users, to be deleted soon
		return intval($value);
	} else {
		//Unknown?!
		return null;
	}
	*/
}
