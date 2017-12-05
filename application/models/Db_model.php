<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model {
	
	//This model handles all DB calls from our local database.
	
	function __construct() {
		parent::__construct();
	}
	
	
	/* ******************************
	 * Remix functions that fetch a bunch of existing data:
	 ****************************** */
	
	function remix_admissions($matching_criteria){
	    $admissions = $this->Db_model->ru_fetch($matching_criteria);
	    
	    //Fetch more data for each enrollment:
	    foreach($admissions as $key=>$enrollment){
	        $classes = $this->Db_model->r_fetch(array(
	            'r.r_id' => $enrollment['ru_r_id'],
	        ));
	        if(count($classes)<=0){
	            return false;
	        }
	        //Merge in:
	        $admissions[$key] = array_merge($admissions[$key] , $classes[0]);
	        
	        
	        //Fetch bootcamp:
	        $bootcamps = $this->Db_model->c_full_fetch(array(
	            'b.b_id' => $classes[0]['r_b_id'],
	        ));
	        if(count($bootcamps)<=0){
	            return false;
	        }
	        //Merge in:
	        $admissions[$key] = array_merge($admissions[$key] , $bootcamps[0]);
	        
	        //Fetch transactions:
	        $admissions[$key]['ru__transactions'] = $this->Db_model->t_fetch(array(
	            't.t_ru_id' => $enrollment['ru_id'],
	        ));
	    }
	    
	    return $admissions;
	}
	
	
	function t_fetch($match_columns){
	    //Fetch the target gems:
	    $this->db->select('*');
	    $this->db->from('v5_transactions t');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	
	function il_fetch($match_columns){
	    //Fetch the target gems:
	    $this->db->select('*');
	    $this->db->from('v5_scraped_leads il');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by('il_student_count DESC');
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	function il_overview_fetch(){
	    //Fetches an overview of Udemy Instructors
	    $this->db->select('COUNT(il_id) as total_instructors, SUM(il_course_count) as total_courses, SUM(il_student_count) as total_students, SUM(il_review_count) as total_reviews, il_udemy_category');
	    $this->db->from('v5_scraped_leads il');
	    $this->db->where('il_udemy_user_id>0');
	    $this->db->where('il_student_count>0'); //Need for Engagement Rate
	    $this->db->group_by('il_udemy_category');
	    $this->db->order_by('total_instructors DESC');
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	
	/* ******************************
	 * Users
	 ****************************** */
	
	function u_fetch($match_columns){
	    //Fetch the target gems:
	    $this->db->select('*');
	    $this->db->from('v5_users u');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by('u_status','DESC');
	    $this->db->order_by('u_id','DESC');
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	function ru_update($ru_id,$update_columns){
	    //Update first
	    $this->db->where('ru_id', $ru_id);
	    $this->db->update('v5_class_students', $update_columns);
	    return $this->db->affected_rows();
	}
	
	function ru_create($insert_columns){
	    //Make sure required fields are here:
	    if(!isset($insert_columns['ru_r_id'])){
	        $this->Db_model->e_create(array(
	            'e_message' => 'ru_create() missing ru_r_id.',
	            'e_json' => json_encode($insert_columns),
	            'e_type_id' => 8, //Platform Error
	        ));
	        return false;
	    } elseif(!isset($insert_columns['ru_u_id'])){
	        $this->Db_model->e_create(array(
	            'e_message' => 'ru_create() missing ru_u_id.',
	            'e_json' => json_encode($insert_columns),
	            'e_type_id' => 8, //Platform Error
	        ));
	        return false;
	    }
	    
	    //Missing anything?
	    if(!isset($insert_columns['ru_timestamp'])){
	        $insert_columns['ru_timestamp'] = date("Y-m-d H:i:s");
	    }
	    
	    //Lets now add:
	    $this->db->insert('v5_class_students', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['ru_id'] = $this->db->insert_id();
	    
	    return $insert_columns;
	}
	
	function us_create($insert_columns){
	    
	    //TODO Do some checks here
	    
	    //Missing anything?
	    if(!isset($insert_columns['us_timestamp'])){
	        $insert_columns['us_timestamp'] = date("Y-m-d H:i:s");
	    }
	    
	    //Lets now add:
	    $this->db->insert('v5_user_submissions', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['us_id'] = $this->db->insert_id();
	    
	    return $insert_columns;
	}
	
	function t_create($insert_columns){
	    //TODO Add checks and protection
	    
	    //Lets now add:
	    $this->db->insert('v5_transactions', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['t_id'] = $this->db->insert_id();
	    
	    return $insert_columns;
	}
	
	function u_create($insert_columns){
		
		//Make sure required fields are here:
		if(!isset($insert_columns['u_fname'])){
			$this->Db_model->e_create(array(
			    'e_message' => 'u_create() missing u_fname.',
			    'e_json' => json_encode($insert_columns),
			    'e_type_id' => 8, //Platform Error
			));
			return false;
		} elseif(!isset($insert_columns['u_lname'])){
		    $this->Db_model->e_create(array(
		        'e_message' => 'u_create() missing u_lname.',
		        'e_json' => json_encode($insert_columns),
		        'e_type_id' => 8, //Platform Error
		    ));
			return false;
		}
		
		//Missing anything?
		if(!isset($insert_columns['u_timestamp'])){
			$insert_columns['u_timestamp'] = date("Y-m-d H:i:s");
		}
		if(!isset($insert_columns['u_url_key'])){
			$insert_columns['u_url_key'] = preg_replace("/[^A-Za-z0-9]/", '', $insert_columns['u_fname'].$insert_columns['u_lname']);
		}
		
		
		//Check u_url_key to be unique, and if not, add a number and increment:
		$original_u_url_key = $insert_columns['u_url_key'];
		$is_duplicate = true;
		$increment = 0;
		while($is_duplicate){
			$matching_users = $this->u_fetch(array(
					'u_url_key' => $insert_columns['u_url_key'],
			));
			
			if(count($matching_users)==0){
				//Yes!
				$is_duplicate = false;
				break;
			} else {
				//This is a duplicate:
				$increment++;
				$insert_columns['u_url_key'] = $original_u_url_key.$increment;
			}
		}
		
		//Lets now add:
		$this->db->insert('v5_users', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['u_id'] = $this->db->insert_id();
		
		return $insert_columns;
	}
	
	function u_update($user_id,$update_columns){
	    //Update first
	    $this->db->where('u_id', $user_id);
	    $this->db->update('v5_users', $update_columns);
	    //Return new row:
	    $users = $this->u_fetch(array(
	        'u_id' => $user_id
	    ));
	    return $users[0];
	}
	
	
	function ba_fetch($match_columns){
	    //Fetch the admins of the bootcamps
	    $this->db->select('*');
	    $this->db->from('v5_users u');
	    $this->db->join('v5_bootcamp_instructors ba', 'ba.ba_u_id = u.u_id');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by('ba.ba_status','DESC');
	    $this->db->order_by('ba.ba_team_display','DESC');
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	
	function u_bootcamps($match_columns){
	    $this->db->select('*');
	    $this->db->from('v5_intents c');
	    $this->db->join('v5_bootcamps b', 'b.b_c_id = c.c_id');
	    $this->db->join('v5_bootcamp_instructors ba', 'ba.ba_b_id = b.b_id');
	    $this->db->order_by('b.b_status', 'DESC');
	    $this->db->order_by('c.c_objective', 'ASC');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	

	
	function activate_bot($botkey, $psid_sender_id, $ref=null){
	    
	    //Import some variables:
	    $mench_bots = $this->config->item('mench_bots');
	    $bot_activation_salt = $this->config->item('bot_activation_salt');
	    $db_field = $mench_bots[$botkey]['u_db'];
	    
	    //See who else might have this PSID id
	    $current_fb_users = $this->Db_model->u_fetch(array(
	        $db_field => $psid_sender_id,
	        'u_status >=' => '0',
	    ));
	    
	    if(!$ref){
	        
	        //This is validating to see if a sender is registered or not:
	        if(count($current_fb_users)==0){
	            
	            //This is a new user that needs to be registered!
	            //Call facebook messenger API and get user details
	            //https://developers.facebook.com/docs/messenger-platform/user-profile/
	            $fb_profile = $this->Facebook_model->fetch_profile($botkey,$psid_sender_id);
	            
	            if(!$fb_profile){
	                //This error has already been logged!
	                //We cannot create this user:
	                return 0;
	            }
	            
	            //Split locale into language and country
	            $locale = explode('_',$fb_profile['locale'],2);
	            
	            //Create user
	            $udata = $this->Db_model->u_create(array(
	                $db_field 			=> $psid_sender_id,
	                'u_fname' 			=> $fb_profile['first_name'],
	                'u_lname' 			=> $fb_profile['last_name'],
	                'u_timezone' 		=> $fb_profile['timezone'],
	                'u_image_url' 		=> $fb_profile['profile_pic'],
	                'u_gender'		 	=> strtolower(substr($fb_profile['gender'],0,1)),
	                'u_language' 		=> $locale[0],
	                'u_country_code' 	=> $locale[1],
	            ));
	            
	            //For students:
	            tree_message(921, 0, $botkey, $udata['u_id'], 'REGULAR', 0, 0);
	            
	            //Return the newly created user ID:
	            return $udata['u_id'];
	            
	        } elseif(count($current_fb_users)==1){
	            
	            //Yes, we found a single match!
	            return $current_fb_users[0]['u_id'];
	            
	        } else {
	            
	            //Inconsistent data:
	            $this->Db_model->e_create(array(
	                'e_message' => 'activate_bot() Found multiple users for Facebook ID ['.$psid_sender_id.'].',
	                'e_json' => json_encode($current_fb_users),
	                'e_type_id' => 8, //Platform Error
	            ));
	            
	            //We dont know which user this is:
	            return 0;
	            
	        }
	    }
	    
	    //First make sure this is a valid encrypted ref ID
	    $ref_u_id = 0;
	    if(substr_count($ref,'msgact_')==1){
	        $parts = explode('_',$ref);
	        if($parts[2]==substr(md5($parts[1].$bot_activation_salt),0,8)){
	            $ref_u_id = intval($parts[1]); //This is the matches user id
	        }
	    }
	    //Didn't find a valid user id?
	    if(!$ref_u_id){
	        return 0;
	    }
	    
	    
	    //Fetch this account and see whatssup:
	    $matching_users = $this->Db_model->u_fetch(array(
	        'u_id' => $ref_u_id,
	    ));
	    
	    if(count($matching_users)<1){
	        //Invalid user ID
	        return 0;
	    } elseif(strlen($matching_users[0][$db_field])>4 && $matching_users[0][$db_field]==$psid_sender_id){
	        //This is already an active user, we can return the ID:
	        return $ref_u_id;
	    } elseif(strlen($matching_users[0][$db_field])>4){
	        
	        //Ooops, Mench user seems to be activated with a different Messenger account!
	        tree_message(923, 0, $botkey, $ref_u_id, 'REGULAR', 0, 0);
	        
	        //Log engagement:
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $ref_u_id,
	            'e_message' => 'MenchBot failed to activate user because Mench account is already activated with another Messenger account.',
	            'e_json' => json_encode($json_data),
	            'e_type_id' => 9, //Support Needing Graceful Errors
	        ));
	        
	        //Return false:
	        return 0;
	    }
	    
	    
	    
	    if(count($current_fb_users)>0){
	        
	        //Some other users have this already, lets see who else has this:
	        $cleared_count = 0;
	        foreach($current_fb_users as $current_fb_user){
	            if($current_fb_user['u_status']<=0 && strlen($current_fb_user['u_email'])<5){
	                //We can de-activate & merge this account:
	                $this->Db_model->u_update( $current_fb_users[0]['u_id'] , array(
	                    $db_field => 0, //Give it up since not active
	                    'u_status' => -2, //Merged account
	                ));
	                
	                //This was cleared:
	                $cleared_count++;
	            }
	        }
	        
	        //Did we clear all?
	        if(!($cleared_count==count($current_fb_users))){
	            //We could not clear the entitlement of 1 or more of the users...
	            //This FB user is assigned to a different mench account, so we cannot activate them!
	            tree_message(924, 0, $botkey, $ref_u_id, 'REGULAR', 0, 0);
	            
	            //Log engagement:
	            $this->Db_model->e_create(array(
	                'e_initiator_u_id' => $ref_u_id,
	                'e_message' => 'Facebook Webhook failed to activate user because Messenger account is associated with another Mench account.',
	                'e_json' => json_encode($json_data),
	                'e_type_id' => 9, //Support Needing Graceful Errors
	                'e_recipient_u_id' => $current_fb_users[0]['u_id'],
	            ));
	            
	            //Return false!
	            return 0;
	        }
	    }
	    
	    
	    
	    
	    //We are ready to activate!
	    /* *************************************
	     * Messenger Activation
	     * *************************************
	     */
	    
	    //Fetch their profile from Facebook:
	    $fb_profile = $this->Facebook_model->fetch_profile($botkey,$psid_sender_id);
	    
	    //Split locale into language and country
	    $locale = explode('_',$fb_profile['locale'],2);
	    
	    //Do a Smart Update for select fields as linking:
	    $this->Db_model->u_update( $ref_u_id , array(
	        $db_field         => $psid_sender_id,
	        'u_image_url'     => ( strlen($matching_users[0]['u_image_url'])<5 ? $fb_profile['profile_pic'] : $matching_users[0]['u_image_url'] ),
	        'u_status'        => ( $matching_users[0]['u_status']==0 ? 1 : $matching_users[0]['u_status'] ), //Activate their profile as well
	        'u_timezone'      => $fb_profile['timezone'],
	        'u_gender'        => strtolower(substr($fb_profile['gender'],0,1)),
	        'u_language'      => ( $matching_users[0]['u_language']=='en' && !($matching_users[0]['u_language']==$locale[0]) ? $locale[0] : $matching_users[0]['u_language'] ),
	        'u_country_code'  => $locale[1],
	    ));
	    
	    //Log Activation Engagement:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $ref_u_id,
	        'e_json' => json_encode(array(
	            'fb_webhook' => $json_data,
	            'fb_profile' => $fb_profile,
	        )),
	        'e_type_id' => 31, //Messenger Activated
	    ));
	    
	    //Fetch this user to see if they are an admin or not!
	    $matching_users = $this->u_fetch(array(
	        'u_id' => $ref_u_id,
	    ));
	    
	    if(isset($matching_users[0])){
	        if($matching_users[0]['u_status']>=2){
	            //Lead instructors:
	            tree_message(918, 0, $botkey, $ref_u_id, 'REGULAR', 0, 0);
	        } else {
	            //For students:
	            tree_message(926, 0, $botkey, $ref_u_id, 'REGULAR', 0, 0);
	        }
	    }
	}
	
	
	
	/* ******************************
	 * us User Submissions
	 ****************************** */
	
	function us_fetch($match_columns){
	    $this->db->select('*');
	    $this->db->from('v5_user_submissions');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $q = $this->db->get();
	    $res = $q->result_array();
	    
	    //Put intent ID as key for easy accessing:
	    foreach($res as $key=>$val){
	        unset($res[$key]);
	        $res[$val['us_c_id']] = $val;
	    }
	    
	    return $res;
	}
	
	
	function us_fetch_fancy($match_columns){
	    $this->db->select('*');
	    $this->db->from('v5_user_submissions us');
	    $this->db->join('v5_users u', 'u.u_id = us.us_student_id');
	    $this->db->join('v5_intents c', 'c.c_id = us.us_c_id');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by('us.us_student_id');
	    $this->db->order_by('us.us_id','DESC');
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	/* ******************************
	 * i Messages
	 ****************************** */
	
	function i_fetch($match_columns){
		$this->db->select('*');
		$this->db->from('v5_messages i');
		$this->db->join('v5_intents c', 'i.i_c_id = c.c_id');
		$this->db->join('v5_users u', 'u.u_id = i.i_creator_id');
		$this->db->where('c.c_status >=',0); //Not yet implemented
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->order_by('i_rank');
		$q = $this->db->get();
		return $q->result_array();
	}
	
	function i_create($insert_columns){
		//Missing anything?
		if(!isset($insert_columns['i_c_id'])){
			return false;
		} elseif(!isset($insert_columns['i_message'])){
			return false;
		}
		
		//Autocomplete required
		if(!isset($insert_columns['i_timestamp'])){
			$insert_columns['i_timestamp'] = date("Y-m-d H:i:s");
		}
		
		//Lets now add:
		$this->db->insert('v5_messages', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['i_id'] = $this->db->insert_id();
		
		return $insert_columns;
	}
	
	function i_update($i_id,$update_columns){
		$this->db->where('i_id', $i_id);
		$this->db->update('v5_messages', $update_columns);
		return $this->db->affected_rows();
	}
	
	
	
	
	/* ******************************
	 * Classes
	 ****************************** */
	
	function r_fetch($match_columns){
	    
		//Missing anything?
		$this->db->select('r.*');
		$this->db->from('v5_classes r');
		$this->db->join('v5_class_students ru', 'ru.ru_r_id = r.r_id', 'left');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->group_by('r.r_id');
		$this->db->order_by('r.r_start_date','ASC');
		$q = $this->db->get();
		
		$runs = $q->result_array();
		foreach($runs as $key=>$value){
		    //Fetch admission count:
		    //TODO NOTE: Anything you add here, make sure to remove from controller/function: api_v1/r_create() when duplicating a class
		    $runs[$key]['r__current_admissions'] = count($this->Db_model->ru_fetch(array(
		        'ru.ru_r_id'	    => $value['r_id'],
		        'ru.ru_status >'	=> 0, //Anyone who has paid anything
		    )));
		}
		
		return $runs;
	}
	
	

	function c_fb_fetch($fb_psid){
	    //Fetch user's active bootcamps
	    $this->db->select('c.*');
	    
	    $this->db->from('v5_class_students ru');
	    $this->db->join('v5_classes r', 'r.r_id = ru.ru_r_id');
	    $this->db->join('v5_users u', 'u.u_id = ru.ru_u_id');
	    $this->db->join('v5_bootcamps b', 'b.b_id = r.r_b_id');
	    $this->db->join('v5_intents c', 'c.c_id = b.b_c_id');
	    
	    $this->db->where('u.u_fb_id',$fb_psid);
	    $this->db->where('ru.ru_status >=',0);
	    $this->db->where('r.r_status >=',1);
	    $this->db->where('u.u_status >=',0);
	    $this->db->where('b.b_status >=',2);
	    $this->db->where('c.c_status >=',1);
	    
	    $this->db->order_by('r.r_start_date','ASC');
	    $q = $this->db->get();
	    
	    return $q->result_array();
	}
	
	
	
	
	/* ******************************
	 * Bootcamps
	 ****************************** */
	
	function ru_fetch($match_columns){
		$this->db->select('*');
		$this->db->from('v5_class_students ru');
		$this->db->join('v5_users u', 'u.u_id = ru.ru_u_id');
		$this->db->join('v5_classes r', 'r.r_id = ru.ru_r_id');
		foreach($match_columns as $key=>$value){
		    $this->db->where($key,$value);
		}
		$q = $this->db->get();
		return $q->result_array();
	}
	
	
	function c_fetch($match_columns, $outbound_levels=0, $append_obj=array()){
	    
	    //Always deal with ints here:
	    $outbound_levels = intval($outbound_levels);
	    
	    //The basic fetcher for intents
	    //Missing anything?
	    $this->db->select('*');
	    $this->db->from('v5_intents c');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $q = $this->db->get();
	    $intents = $q->result_array();
	    
	    
	    //We had anything?
	    if(count($intents)>0 && in_array('i',$append_obj)){
	        $intents[0]['c__messages'] = array('ss');
	        //Fetch Messages:
	        foreach($intents as $key=>$value){
	            $intents[$key]['c__messages'] = $this->Db_model->i_fetch(array(
	                'i_c_id' => $value['c_id'],
	                'i_status >=' => 0, //Published in any form. This may need more logic
	                'i_status <' => 4, //But not private notes if any
	            ));
	        }
	    }
	    
	    if(count($intents)>0 && $outbound_levels>=1){
	        //Lets append the outbound intents:
	        //Can't wrap my head around recursive, will do dummy way for now:
	        foreach($intents as $key=>$value){
	            
	            //Do the first level:
	            $intents[$key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
	                'cr.cr_inbound_id' => $value['c_id'],
	                'cr.cr_status >' => 0,
	            ) , $append_obj );
	            
	            //need more depth?
	            if(count($intents[$key]['c__child_intents'])>0 && $outbound_levels>=2){
	                //Start the second level:
	                foreach($intents[$key]['c__child_intents'] as $key2=>$value2){
	                    $intents[$key]['c__child_intents'][$key2]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
	                        'cr.cr_inbound_id' => $value2['c_id'],
	                        'cr.cr_status >' => 0,
	                    ) , $append_obj );
	                }
	            }
	        } 
	    }
	    
	    //Return everything that was collected:
	    return $intents;
	}
	
	
	
	function c_full_fetch($match_columns){
	    //Missing anything?
	    $this->db->select('*');
	    $this->db->from('v5_bootcamps b');
	    $this->db->join('v5_intents c', 'c.c_id = b.b_c_id');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $q = $this->db->get();
	    $bootcamps = $q->result_array();
	    
	    //Now append more data:
	    foreach($bootcamps as $key=>$c){
	        //Start estimating hours calculation:
	        $bootcamps[$key]['c__estimated_hours'] = $bootcamps[$key]['c_time_estimate'];
	        
	        
	        //Bootcamp Messages:
	        $bootcamp_messages = $this->Db_model->i_fetch(array(
	            'i_status >=' => 0,
	            'i_status <' => 4, //But not private notes if any
	            'i_c_id' => $c['c_id'],
	        ));
	        
	        //Fetch Sub-intents:
	        $bootcamps[$key]['c__task_count'] = 0;
	        $bootcamps[$key]['c__message_tree_count'] = count($bootcamp_messages);
	        $bootcamps[$key]['c__messages'] = $bootcamp_messages;
	        $bootcamps[$key]['c__break_milestones'] = 0; //Keep track of total break milestones
	        $bootcamps[$key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
	            'cr.cr_inbound_id' => $c['c_id'],
	            'cr.cr_status >=' => 0,
	        ));
	        
	        foreach($bootcamps[$key]['c__child_intents'] as $sprint_key=>$sprint_value){
	            //Addup sprint estimated time:
	            $bootcamps[$key]['c__estimated_hours'] += $sprint_value['c_time_estimate'];
	            //Introduce sprint total time:
	            $bootcamps[$key]['c__child_intents'][$sprint_key]['c__estimated_hours'] = $sprint_value['c_time_estimate'];
	            //Is this a break milestone?
	            if($sprint_value['c_is_last']=='t'){
	                $bootcamps[$key]['c__break_milestones']++;
	            }
	            //Fetch sprint tasks at level 3:
	            $bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
	                'cr.cr_inbound_id' => $sprint_value['c_id'],
	                'cr.cr_status >=' => 0,
	            ));
	            
	            
	            //Count Messages:
	            $milestone_messages = $this->Db_model->i_fetch(array(
	                'i_status >=' => 0,
	                'i_status <' => 4, //But not private notes if any
	                'i_c_id' => $sprint_value['c_id'],
	            ));
	            $bootcamps[$key]['c__message_tree_count'] += count($milestone_messages);
	            $bootcamps[$key]['c__child_intents'][$sprint_key]['c__message_tree_count'] = count($milestone_messages);
	            $bootcamps[$key]['c__child_intents'][$sprint_key]['c__messages'] = $milestone_messages;
	            
	            //Addup task values:
	            foreach($bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'] as $task_key=>$task_value){
	                //Addup task estimated time:
	                $bootcamps[$key]['c__estimated_hours'] += $task_value['c_time_estimate'];
	                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__estimated_hours'] += $task_value['c_time_estimate'];
	                $bootcamps[$key]['c__task_count']++;
	                
	                //Count Messages:
	                $task_messages = $this->Db_model->i_fetch(array(
	                    'i_status >=' => 0,
	                    'i_status <' => 4, //But not private notes if any
	                    'i_c_id' => $task_value['c_id'],
	                ));
	                $bootcamps[$key]['c__message_tree_count'] += count($task_messages);
	                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__message_tree_count'] += count($task_messages);
	                //The following two are identicals, just there for consistency:
	                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'][$task_key]['c__message_tree_count'] = count($task_messages);
	                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'][$task_key]['c__messages'] = $task_messages;
	            }
	        }
	        
	        //Fetch Classes:
	        $bootcamps[$key]['c__classes'] = $this->r_fetch(array(
	            'r.r_b_id' => $c['b_id'],
	            'r.r_status >=' => 0,
	        ));
	        
	        //Fetch admins:
	        $bootcamps[$key]['b__admins'] = $this->Db_model->ba_fetch(array(
	            'ba.ba_b_id' => $c['b_id'],
	            'ba.ba_status >=' => 0,
	            'u.u_status >=' => 0,
	        ));
	    }
	    
	    return $bootcamps;
	}
	
	function b_fetch($match_columns){
	    //Missing anything?
	    $this->db->select('*');
	    $this->db->from('v5_bootcamps b');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by('b_id','DESC');
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	
	function a_fetch(){
	    //Format in special way, used in engagements page:
	    $this->db->select('*');
	    $this->db->from('v5_engagement_types a');
	    $this->db->order_by('a_name');
	    $q = $this->db->get();
	    $types = $q->result_array();
	    $return_array = array();
	    foreach($types as $a){
	        $return_array[$a['a_id']] = $a['a_name'];
	    }
	    return $return_array;
	}
	
	function c_plain_fetch($match_columns){
		//Missing anything?
		$this->db->select('c.*');
		$this->db->from('v5_intents c');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
		return $q->row_array();
	}
	
	
	function cr_outbound_fetch($match_columns,$append_obj=array()){
		//Missing anything?
		$this->db->select('*');
		$this->db->from('v5_intents c');
		$this->db->join('v5_intent_links cr', 'cr.cr_outbound_id = c.c_id');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->order_by('cr.cr_outbound_rank','ASC');
		$q = $this->db->get();
		$return = $q->result_array();
		
		//We had anything?
		if(count($return)>0){
		    if(in_array('i',$append_obj)){
		        //Fetch Messages:
		        foreach($return as $key=>$value){
		            $return[$key]['c__messages'] = $this->Db_model->i_fetch(array(
		                'i_c_id' => $value['c_id'],
		                'i_status >=' => 0, //Published in any form. This may need more logic
		                'i_status <' => 4, //But not private notes if any
		            ));
		        }
		    }
		}
		
		//Return the package:
		return $return;
	}
	
	function cr_inbound_fetch($match_columns){
		//Missing anything?
		$this->db->select('*');
		$this->db->from('v5_intents c');
		$this->db->join('v5_intent_links cr', 'cr.cr_inbound_id = c.c_id');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
		return $q->result_array();
	}
	
	
	
	function cr_update($cr_id,$update_columns,$column='cr_id'){
		$this->db->where($column, $cr_id);
		$this->db->update('v5_intent_links', $update_columns);
		return $this->db->affected_rows();
	}
	
	
	function max_value($table,$column,$match_columns){
		$this->db->select('MAX('.$column.') as largest');
		$this->db->from($table);
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
		$stats = $q->row_array();
		return intval($stats['largest']);
	}
	
	function cr_create($insert_columns){
		
		//Missing anything?
		if(!isset($insert_columns['cr_outbound_id']) || !isset($insert_columns['cr_outbound_rank'])){
			return false;
		} elseif(!isset($insert_columns['cr_creator_id'])){
			return false;
		}
		
		//Autocomplete required
		if(!isset($insert_columns['cr_timestamp'])){
			$insert_columns['cr_timestamp'] = date("Y-m-d H:i:s");
		}
		if(!isset($insert_columns['cr_status'])){
			$insert_columns['cr_status'] = 1; //Live link
		}
		
		//Lets now add:
		$this->db->insert('v5_intent_links', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['cr_id'] = $this->db->insert_id();
		
		return $insert_columns;
	}
	
	function c_update($c_id,$update_columns){
	    $this->db->where('c_id', $c_id);
	    $this->db->update('v5_intents', $update_columns);
	    return $this->db->affected_rows();
	}
	
	function r_update($r_id,$update_columns){
	    $this->db->where('r_id', $r_id);
	    $this->db->update('v5_classes', $update_columns);
	    return $this->db->affected_rows();
	}
	
	function b_update($b_id,$update_columns){
	    $this->db->where('b_id', $b_id);
	    $this->db->update('v5_bootcamps', $update_columns);
	    return $this->db->affected_rows();
	}
	
	function e_update($e_id,$update_columns){
	    $this->db->where('e_id', $e_id);
	    $this->db->update('v5_engagements', $update_columns);
	    return $this->db->affected_rows();
	}
	
	//Leads:
	function il_update($il_id,$update_columns){
	    $this->db->where('il_id', $il_id);
	    $this->db->update('v5_scraped_leads', $update_columns);
	    return $this->db->affected_rows();
	}
	function il_create($insert_columns){
	    $this->db->insert('v5_scraped_leads', $insert_columns);
	    $insert_columns['il_id'] = $this->db->insert_id();    
	    return $insert_columns;
	}	
	
	function r_create($insert_columns){
	    $this->db->insert('v5_classes', $insert_columns);
	    $insert_columns['r_id'] = $this->db->insert_id();
	    return $insert_columns;
	}
	
	function b_create($insert_columns){
	    
	    if(!isset($insert_columns['b_c_id'])){
	        return false;
	    } elseif(!isset($insert_columns['b_creator_id'])){
	        return false;
	    } elseif(!isset($insert_columns['b_url_key'])){
	        return false;
	    }
	    
	    //Missing anything?
	    if(!isset($insert_columns['b_timestamp'])){
	        $insert_columns['b_timestamp'] = date("Y-m-d H:i:s");
	    }
	    if(!isset($insert_columns['b_status'])){
	        $insert_columns['b_status'] = 0; //On Hold by default
	    }
	    if(!isset($insert_columns['b_algolia_id'])){
	        $insert_columns['b_algolia_id'] = 0;
	    }
	    
	    //Lets now add:
	    $this->db->insert('v5_bootcamps', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['b_id'] = $this->db->insert_id();
	    
	    return $insert_columns;
	}
	
	function c_create($insert_columns){
	    
	    if(!isset($insert_columns['c_objective'])){
	        return false;
	    } elseif(!isset($insert_columns['c_creator_id'])){
	        return false;
	    }
		
		//Missing anything?
		if(!isset($insert_columns['c_timestamp'])){
			$insert_columns['c_timestamp'] = date("Y-m-d H:i:s");
		}
		if(!isset($insert_columns['c_status'])){
			$insert_columns['c_status'] = 1; //Live by default
		}
		if(!isset($insert_columns['c_algolia_id'])){
		    $insert_columns['c_algolia_id'] = 0;
		}
		
		//Lets now add:
		$this->db->insert('v5_intents', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['c_id'] = $this->db->insert_id();
		
		return $insert_columns;
	}
	
	
	/* ******************************
	 * Other
	 ****************************** */
	
	function ba_create($insert_columns){
	    
	    //TODO Do better check on required fields:
	    if(!isset($insert_columns['ba_u_id'])){
	        return false;
	    } elseif(!isset($insert_columns['ba_b_id'])){
	        return false;
	    } elseif(!isset($insert_columns['ba_creator_id'])){
	        return false;
	    } elseif(!isset($insert_columns['ba_status'])){
	        return false;
	    } elseif(!isset($insert_columns['ba_team_display'])){
	        return false;
	    }
	    
	    //Missing anything?
	    if(!isset($insert_columns['ba_timestamp'])){
	        $insert_columns['ba_timestamp'] = date("Y-m-d H:i:s");
	    }
	    
	    //Lets now add:
	    $this->db->insert('v5_bootcamp_instructors', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['ba_id'] = $this->db->insert_id();
	    
	    return $insert_columns;
	}
	
	
	
	
	
	function e_fetch($match_columns=array(),$limit=100){
	    $this->db->select('*');
	    $this->db->from('v5_engagements e');
	    $this->db->join('v5_engagement_types a', 'a.a_id=e.e_type_id');
	    $this->db->join('v5_users u', 'u.u_id=e.e_initiator_u_id','left');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by('e.e_id','DESC');
	    if($limit>0){
	        $this->db->limit($limit);
	    }
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	
	
	function e_create($link_data){
	    
	    //Sort out the optional fields first:
	    if(!isset($link_data['e_initiator_u_id'])){
	        //Try to fetch user ID from session:
	        $user_data = $this->session->userdata('user');
	        if(isset($user_data['u_id']) && intval($user_data['u_id'])>0){
	            $link_data['e_initiator_u_id'] = $user_data['u_id'];
	        } else {
	            //Have no user:
	            $link_data['e_initiator_u_id'] = 0;
	        }
	    }
	    if(!isset($link_data['e_timestamp'])){
	        $link_data['e_timestamp'] = date("Y-m-d H:i:s");
	    }
	    if(!isset($link_data['e_json'])){
	        $link_data['e_json'] = null;
	    }
	    if(!isset($link_data['e_message'])){
	        $link_data['e_message'] = null;
	    }
		
	    
		//Now check required fields:
		if(!isset($link_data['e_type_id'])){
		    //Log this error:
		    $this->Db_model->e_create(array(
		        'e_initiator_u_id' => $link_data['e_initiator_u_id'],
		        'e_message' => 'e_create() Function missing [e_type_id] variable.',
		        'e_json' => json_encode($link_data),
		        'e_type_id' => 8, //Platform Error
		    ));
			return false;
		}
		
		//Lets log:
		$this->db->insert('v5_engagements', $link_data);

		//Fetch inserted id:
		$link_data['e_id'] = $this->db->insert_id();
		
		
		//Do we need to notify the admin about this engagement?
		if($link_data['e_id']>0 && $link_data['e_type_id']>0){
		    
		    //load model:
		    $this->load->model('Email_model');
		    
		    //Detect matches:
		    $engagement_subscriptions = $this->config->item('engagement_subscriptions');
		    $engagement_references = $this->config->item('engagement_references');
		    
		    foreach($engagement_subscriptions as $subscription){
		        if(in_array($link_data['e_type_id'],$subscription['subscription']) || in_array(0,$subscription['subscription'])){
		            
		            //Just do this one:
		            if(!isset($engagements[0])){
		                //Fetch Engagement Data:
		                $engagements = $this->Db_model->e_fetch(array(
		                    'e_id' => $link_data['e_id']
		                ));
		            }
		            
		            //Did we find it? We should have:
		            if(isset($engagements[0])){
		                $subject = 'Notification: '.trim(strip_tags($engagements[0]['a_name'])).' - '.( isset($engagements[0]['u_fname']) ? $engagements[0]['u_fname'].' '.$engagements[0]['u_lname'] : 'System' );
		                
		                //Compose email:
		                $html_message = null; //Start
		                $html_message .= '<div>Hi Mench Admin,</div><br />';
		                $html_message .= '<div>'.$engagements[0]['a_desc'].':</div><br />';
		                
		                
		                //Lets go through all references to see what is there:
		                foreach($engagement_references as $engagement_field=>$er){
		                    if(intval($engagements[0][$engagement_field])>0){
		                        //Yes we have a value here:
		                        $html_message .= '<div>'.$er['name'].': '.object_link($er['object_code'], $engagements[0][$engagement_field], $engagements[0]['e_b_id']).'</div>';
		                    }
		                }
		                
		                if(strlen($engagements[0]['e_message'])>0){
		                    $html_message .= '<div>Message:<br />'.format_e_message($engagements[0]['e_message']).'</div>';
		                }
		                $html_message .= '<br />';
		                $html_message .= '<div>Cheers,</div>';
		                $html_message .= '<div>Mench Engagement Watcher</div>';
		                $html_message .= '<div style="font-size:0.8em;">Engagement ID '.$engagements[0]['e_id'].'</div>';
		                
		                //Send email:
		                $this->Email_model->send_single_email($subscription['admin_emails'],$subject,$html_message);
		            }
		        }
		    }
		}
		
		
		
		//Boya!
		return $link_data;
	}
	

	
	function sync_algolia($c_id=null){
		
		boost_power();
		
		$website = $this->config->item('website');
		
		if(is_dev()){
		    return file_get_contents($website['url']."api_v1/algolia/".$c_id);
		}
		
		//Include PHP library:
		require_once('application/libraries/algoliasearch.php');
		$client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
		$index = $client->initIndex('bootcamps');
		
		//Fetch all nodes:
		$limits = array(
				'c.c_status >=' => 0,
		);
		if($c_id){
			$limits['c_id'] = $c_id;
		} else {
			$index->clearIndex();
		}
		$intents = $this->c_fetch($limits);
		
		if(count($intents)<=0){
			//Nothing found here!
			return false;
		}
		
		//Buildup this array to save to search index
		$return = array();
		foreach($intents as $intent){
			//Adjust Algolia ID:
			if(isset($intent['c_algolia_id']) && intval($intent['c_algolia_id'])>0){
				$intent['objectID'] = intval($intent['c_algolia_id']);
			}
			unset($intent['c_algolia_id']);
			
			//Add to main array
			array_push( $return , $intent);
		}
		
		//$obj = json_decode(json_encode($return), FALSE);
		//print_r($return);
		
		if($c_id){
			
			if($intent['c_status']>=0){
				
				if(isset($intent['c_algolia_id']) && intval($intent['c_algolia_id'])>0){
					//Update existing
					$obj_add_message = $index->saveObjects($return);
				} else {
					//Create new ones
					$obj_add_message = $index->addObjects($return);
					
					//Now update database with the objectIDs:
					if(isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs'])>0){
						foreach($obj_add_message['objectIDs'] as $key=>$algolia_id){
							$this->Db_model->c_update( $return[$key]['c_id'] , array(
									'c_algolia_id' => $algolia_id,
							));
						}
					}
				}
				
			} elseif(isset($intent['c_algolia_id']) && intval($intent['c_algolia_id'])>0) {
				//Delete object:
				$index->deleteObject($intent['c_algolia_id']);
			}
			
		} else {
			//Create new ones
			$obj_add_message = $index->addObjects($return);
			
			//Now update database with the objectIDs:
			if(isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs'])>0){
				foreach($obj_add_message['objectIDs'] as $key=>$algolia_id){
					$this->Db_model->c_update( $return[$key]['c_id'] , array(
							'c_algolia_id' => $algolia_id,
					));
				}
			}
		}			
		
		
		return array(
				'c_id' => $c_id,
				'intents' => $intents,
				'output' => $obj_add_message['objectIDs'],
		);
	}
}
