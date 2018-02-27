<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model {
	
	//This model handles all DB calls from our local database.
	
	function __construct() {
		parent::__construct();
	}


    function fetch_avg_class_completion($r_id){
        return objectToArray($this->db->query("
SELECT AVG(ru.ru_cache__completion_rate) AS cr
FROM v5_class_students ru
JOIN v5_users u ON u.u_id = ru.ru_u_id
WHERE ru.ru_status >= 4
  AND ru_r_id = ".$r_id."
  AND u_fb_id > 0")->result());
    }

	/* ******************************
	 * Remix functions that fetch a bunch of existing data:
	 ****************************** */
	
	function remix_admissions($matching_criteria){

	    $admissions = $this->Db_model->ru_fetch($matching_criteria);


	    //Fetch more data for each enrollment:
	    foreach($admissions as $key=>$enrollment){
            //Fetch bootcamp:
            $bootcamps = $this->Db_model->remix_bootcamps(array(
                'b.b_id' => $enrollment['r_b_id'],
            ));
            if(count($bootcamps)<=0){
                $this->Db_model->e_create(array(
                    'e_message' => 'remix_admissions() had invalid [r_b_id]='.$enrollment['r_b_id'],
                    'e_json' => $matching_criteria,
                    'e_type_id' => 8, //Platform Error
                ));
                unset($admissions[$key]);
                continue;
            }

            //Fetch Class:
            $class = filter($bootcamps[0]['c__classes'],'r_id',$enrollment['ru_r_id']);
	        if(count($class)<=0){
                $this->Db_model->e_create(array(
                    'e_message' => 'remix_admissions() had invalid [r_id]='.$enrollment['ru_r_id'],
                    'e_json' => $matching_criteria,
                    'e_type_id' => 8, //Platform Error
                ));
                unset($admissions[$key]);
                continue;
	        }

	        //Merge in:
            $admissions[$key] = array_merge($admissions[$key] , $class);
	        $admissions[$key] = array_merge($admissions[$key] , $bootcamps[0]);
	        $admissions[$key]['ru__transactions'] = $this->Db_model->t_fetch(array(
	            't.t_ru_id' => $enrollment['ru_id'],
	        ));
	    }

	    return $admissions;
	}

    function remix_bootcamps($match_columns){
        //Missing anything?
        $this->db->select('*');
        $this->db->from('v5_bootcamps b');
        $this->db->join('v5_intents c', 'c.c_id = b.b_c_id');
        $this->db->join('v5_facebook_pages fp', 'fp.fp_id = b.b_fp_id','left');

        foreach($match_columns as $key=>$value){
            $this->db->where($key,$value);
        }
        $q = $this->db->get();
        $bootcamps = $q->result_array();

        //Now append more data:
        foreach($bootcamps as $key=>$c){

            //Bootcamp Messages:
            $bootcamps[$key]['c__messages'] = $this->Db_model->i_fetch(array(
                'i_status >' => 0,
                'i_c_id' => $c['c_id'],
            ));

            //Fetch team:
            $bootcamps[$key]['b__admins'] = $this->Db_model->ba_fetch(array(
                'ba.ba_b_id' => $c['b_id'],
                'ba.ba_status >=' => 0,
                'u.u_status >=' => 0,
            ));

            //Fetch Sub-intents:
            $bootcamps[$key]['c__milestone_secs'] = ( $c['b_sprint_unit']=='week' ? 7 : 1 )*24*3600;
            $bootcamps[$key]['c__active_intents'] = array();
            $bootcamps[$key]['c__task_count'] = 0;
            $bootcamps[$key]['c__milestone_units'] = 0; //Keep track of total milestone units:
            $bootcamps[$key]['c__estimated_hours'] = $bootcamps[$key]['c_time_estimate'];
            $bootcamps[$key]['c__message_tree_count'] = count($bootcamps[$key]['c__messages']);
            $bootcamps[$key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
                'cr.cr_inbound_id' => $c['c_id'],
                'cr.cr_status >=' => 0,
                'c.c_status >=' => 0,
            ));
            foreach($bootcamps[$key]['c__child_intents'] as $sprint_key=>$sprint_value){

                //Count Messages:
                $milestone_messages = $this->Db_model->i_fetch(array(
                    'i_status >' => 0,
                    'i_c_id' => $sprint_value['c_id'],
                ));

                //Assign messages:
                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__messages'] = $milestone_messages;

                //Addup message count:
                $bootcamps[$key]['c__message_tree_count'] += ( $sprint_value['c_status']==1 ? count($milestone_messages) : 0);
                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__message_tree_count'] = ( $sprint_value['c_status']==1 ? count($milestone_messages) : 0);

                //NOTE: Milestones do *not* have a time estimate, so no point in trying to addem up here...

                //Introduce sprint total time:
                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__estimated_hours'] = 0; //Because its always zero!

                //Fetch sprint tasks at level 3:
                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
                    'cr.cr_inbound_id' => $sprint_value['c_id'],
                    'cr.cr_status >=' => 0,
                    'c.c_status >=' => 0,
                ));

                //Is this an active Milestone?
                if($sprint_value['c_status']==1){
                    //Create task array:
                    $bootcamps[$key]['c__active_intents'][$sprint_value['c_id']] = array();

                    //Addup the timeline:
                    $bootcamps[$key]['c__milestone_units'] += $sprint_value['c_duration_multiplier'];
                }

                //Addup task values:
                foreach($bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'] as $task_key=>$task_value){

                    //Count Messages:
                    $task_messages = $this->Db_model->i_fetch(array(
                        'i_status >' => 0,
                        'i_c_id' => $task_value['c_id'],
                    ));

                    //Add messages:
                    $bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'][$task_key]['c__messages'] = $task_messages;

                    //Addup task estimated time for active tasks in active Milestones:
                    if($task_value['c_status']==1){

                        if($sprint_value['c_status']==1){
                            $bootcamps[$key]['c__estimated_hours'] += $task_value['c_time_estimate'];
                            $bootcamps[$key]['c__task_count']++;
                            //add to active tasks per milestone:
                            array_push($bootcamps[$key]['c__active_intents'][$sprint_value['c_id']],$task_value['c_id']);
                        }

                        //Addup Milestone Hours for its Active Tasks Regardless of Milestone status:
                        $bootcamps[$key]['c__child_intents'][$sprint_key]['c__estimated_hours'] += $task_value['c_time_estimate'];

                        //Increase message counts:
                        $bootcamps[$key]['c__message_tree_count'] += count($task_messages);
                        $bootcamps[$key]['c__child_intents'][$sprint_key]['c__message_tree_count'] += count($task_messages);

                    }

                    //Always show task message count regardless of status:
                    $bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'][$task_key]['c__message_tree_count'] = count($task_messages);
                }
            }

            //Fetch Classes last to leverage the currently gathered data for some other calculations inside the r_fetch() function:
            $bootcamps[$key]['c__classes'] = $this->r_fetch(array(
                'r.r_b_id' => $c['b_id'],
                'r.r_status >=' => 0,
            ) , $bootcamps[$key] /* Passing this would load extra variables for the class */ );

        }

        return $bootcamps;
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
	        if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
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
	            'e_json' => $insert_columns,
	            'e_type_id' => 8, //Platform Error
	        ));
	        return false;
	    } elseif(!isset($insert_columns['ru_u_id'])){
	        $this->Db_model->e_create(array(
	            'e_message' => 'ru_create() missing ru_u_id.',
	            'e_json' => $insert_columns,
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

        if(!$insert_columns['ru_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error ru_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }
	    
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

        if(!$insert_columns['us_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error us_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }
	    
	    return $insert_columns;
	}
	
	function t_create($insert_columns){
	    //TODO Add checks and protection
	    
	    //Lets now add:
	    $this->db->insert('v5_transactions', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['t_id'] = $this->db->insert_id();

        if(!$insert_columns['t_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error t_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }
	    
	    return $insert_columns;
	}
	
	function u_create($insert_columns){
		
		//Make sure required fields are here:
		if(!isset($insert_columns['u_fname'])){
			$this->Db_model->e_create(array(
			    'e_message' => 'u_create() missing u_fname.',
			    'e_json' => $insert_columns,
			    'e_type_id' => 8, //Platform Error
			));
			return false;
		}
		
		//Missing anything?
		if(!isset($insert_columns['u_timestamp'])){
			$insert_columns['u_timestamp'] = date("Y-m-d H:i:s");
		}
		if(!isset($insert_columns['u_url_key'])){
			$insert_columns['u_url_key'] = generate_url_key($insert_columns['u_fname']);
		}
		
		//Lets now add:
		$this->db->insert('v5_users', $insert_columns);

        //Fetch inserted id:
        $insert_columns['u_id'] = $this->db->insert_id();

        if(!$insert_columns['u_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error u_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }

		//Fetch to return:
        $users = $this->Db_model->u_fetch(array(
            'u_id' => $insert_columns['u_id'],
        ));
		
		return $users[0];
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
	
	
	function ba_fetch($match_columns,$fetch_extra=false){
	    //Fetch the admins of the bootcamps
	    $this->db->select('*');
	    $this->db->from('v5_users u');
        $this->db->join('v5_bootcamp_instructors ba', 'ba.ba_u_id = u.u_id');
        if($fetch_extra){
            //This is a HACK!
            $this->db->join('v5_bootcamps b', 'ba.ba_b_id = b.b_id');
            $this->db->join('v5_intents c', 'c.c_id = b.b_c_id');
        }
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
        $this->db->join('v5_facebook_pages fp', 'fp.fp_id = b.b_fp_id','left');
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


	    if(!$psid_sender_id){
	        //Ooops, this is not good:
            $this->Db_model->e_create(array(
                'e_message' => 'activate_bot() got called without psid_sender_id variable',
                'e_type_id' => 8, //Platform Error
            ));
            return 0;
        }
	    
	    //See who else might have this PSID id
	    $current_fb_users = $this->Db_model->u_fetch(array(
	        'u_fb_id' => $psid_sender_id,
	    ));
	    
	    if(!$ref){
	        
	        //This is validating to see if a sender is registered or not:
	        if(count($current_fb_users)==0){
	            
	            //This is a new user that needs to be registered!
	            //Call facebook messenger API and get user details
	            //https://developers.facebook.com/docs/messenger-platform/user-profile/
                $graph_fetch = $this->Facebook_model->fb_graph(4,'GET',$psid_sender_id);

	            if(!$graph_fetch['status']){
	                //This error has already been logged inside $this->Facebook_model->fb_graph()
	                //We cannot create this user:
	                return 0;
	            }

	            //We're cool!
                $fb_profile = $graph_fetch['e_json']['result'];

	            //Split locale into language and country
	            $locale = explode('_',$fb_profile['locale'],2);
	            
	            //Create user
	            $udata = $this->Db_model->u_create(array(
	                'u_fb_id' 			=> $psid_sender_id,
	                'u_fname' 			=> $fb_profile['first_name'],
                    'u_lname' 			=> $fb_profile['last_name'],
                    'u_url_key' 		=> generate_url_key($fb_profile['first_name'].$fb_profile['last_name']),
	                'u_timezone' 		=> $fb_profile['timezone'],
	                'u_image_url' 		=> $fb_profile['profile_pic'],
	                'u_gender'		 	=> strtolower(substr($fb_profile['gender'],0,1)),
	                'u_language' 		=> $locale[0],
	                'u_country_code' 	=> $locale[1],
	            ));
	            
	            //Non verified guest students:
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
	                'e_json' => $current_fb_users,
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
	    } elseif($matching_users[0]['u_fb_id']>0 && $matching_users[0]['u_fb_id']==$psid_sender_id){
	        //This is already an active user, we can return the ID:
	        return $ref_u_id;
	    } elseif($matching_users[0]['u_fb_id']>0){
	        
	        //Ooops, Mench user seems to be activated with a different Messenger account!
	        tree_message(923, 0, $botkey, $ref_u_id, 'REGULAR', 0, 0);
	        
	        //Log engagement:
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $ref_u_id,
	            'e_message' => 'Failed to activate user because Messenger account is already associated with another user.',
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
	                    'u_fb_id' => NULL, //Give it up since not active
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
	                'e_message' => 'Failed to activate user because Messenger account is already associated with another user.',
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
	    
	    //Fetch their profile from Facebook to update
        $graph_fetch = $this->Facebook_model->fb_graph(4,'GET',$psid_sender_id);

        if(!$graph_fetch['status']){
            //This error has already been logged inside $this->Facebook_model->fb_graph()
            //We cannot create this user:
            return 0;
        }

        //We're cool!
        $fb_profile = $graph_fetch['e_json']['result'];

	    //Split locale into language and country
	    $locale = explode('_',$fb_profile['locale'],2);
	    
	    //Do an Update for selected fields as linking:
	    $this->Db_model->u_update( $ref_u_id , array(
	        'u_fb_id'         => $psid_sender_id,
	        'u_image_url'     => ( strlen($matching_users[0]['u_image_url'])<5 ? $fb_profile['profile_pic'] : $matching_users[0]['u_image_url'] ),
	        'u_status'        => ( $matching_users[0]['u_status']==0 ? 1 : $matching_users[0]['u_status'] ), //Activate their profile as well
	        'u_timezone'      => $fb_profile['timezone'],
	        'u_gender'        => strtolower(substr($fb_profile['gender'],0,1)),
	        'u_language'      => ( $matching_users[0]['u_language']=='en' && !($matching_users[0]['u_language']==$locale[0]) ? $locale[0] : $matching_users[0]['u_language'] ),
            'u_country_code'  => $locale[1],
            'u_fname'         => $fb_profile['first_name'], //Update their original names with FB
            'u_lname'         => $fb_profile['last_name'], //Update their original names with FB
            'u_url_key'       => generate_url_key($fb_profile['first_name'].$fb_profile['last_name']),
	    ));
	    
	    //Log Activation Engagement:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $ref_u_id,
	        'e_json' => array(
	            'fb_profile' => $fb_profile,
	        ),
	        'e_type_id' => 31, //Messenger Activated
	    ));
	    
	    //Fetch this user to see if they are an admin or not!
	    $matching_users = $this->u_fetch(array(
	        'u_id' => $ref_u_id,
	    ));
	    
	    if(isset($matching_users[0])){
	        if($matching_users[0]['u_status']==2){
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



	
	/* ******************************
	 * i Messages
	 ****************************** */

    function i_fetch($match_columns, $limit=0){
        $this->db->select('*');
        $this->db->from('v5_messages i');
        $this->db->join('v5_intents c', 'i.i_c_id = c.c_id');
        $this->db->join('v5_users u', 'u.u_id = i.i_creator_id');
        foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
        }
        if($limit>0){
            $this->db->limit($limit);
        }
        $this->db->order_by('i_rank');
        $q = $this->db->get();
        return $q->result_array();
    }

    function sy_fetch($match_columns){
        $this->db->select('*');
        $this->db->from('v5_message_fb_sync sy');
        foreach($match_columns as $key=>$value){
            $this->db->where($key,$value);
        }
        $q = $this->db->get();
        return $q->result_array();
    }

    function sy_create($insert_columns){
        //Missing anything?
        if(!isset($insert_columns['sy_i_id'])){
            return false;
        } elseif(!isset($insert_columns['sy_fp_id'])){
            return false;
        } elseif(!isset($insert_columns['sy_fb_att_id'])){
            return false;
        }

        //Autocomplete required
        if(!isset($insert_columns['sy_timestamp'])){
            $insert_columns['sy_timestamp'] = date("Y-m-d H:i:s");
        }

        //Lets now add:
        $this->db->insert('v5_message_fb_sync', $insert_columns);

        //Fetch inserted id:
        $insert_columns['sy_id'] = $this->db->insert_id();

        if(!$insert_columns['sy_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error sy_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }

        return $insert_columns;
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

        if(!$insert_columns['i_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error i_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }
		
		return $insert_columns;
	}
	
	function i_update($i_id,$update_columns){
		$this->db->where('i_id', $i_id);
		$this->db->update('v5_messages', $update_columns);
		return $this->db->affected_rows();
	}


    /* ******************************
     * Facebook Pages/Admins
     ****************************** */

    function fp_fetch($match_columns){

        $this->db->select('*');
        $this->db->from('v5_facebook_pages fp');
        $this->db->join('v5_facebook_page_admins fs', 'fs.fs_fp_id = fp.fp_id', 'left');

        foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
        }
        $q = $this->db->get();
        return $q->result_array();
    }

    function fp_update($fp_id,$update_columns){
        $this->db->where('fp_id', $fp_id);
        $this->db->update('v5_facebook_pages', $update_columns);
        return $this->db->affected_rows();
    }

    function fs_update($fs_id,$update_columns){
        $this->db->where('fs_id', $fs_id);
        $this->db->update('v5_facebook_page_admins', $update_columns);
        return $this->db->affected_rows();
    }

    function fp_create($insert_columns){

        //Missing anything?
        if(!isset($insert_columns['fp_fb_id'])){
            return false;
        } elseif(!isset($insert_columns['fp_name'])){
            return false;
        } elseif(!isset($insert_columns['fp_status'])){ //Need status to know whatssup
            return false;
        }

        //Autocomplete required
        if(!isset($insert_columns['fp_timestamp'])){
            $insert_columns['fp_timestamp'] = date("Y-m-d H:i:s");
        }

        //Lets now add:
        $this->db->insert('v5_facebook_pages', $insert_columns);

        //Fetch inserted id:
        $insert_columns['fp_id'] = $this->db->insert_id();

        if(!$insert_columns['fp_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error fp_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
            return false;
        } else {
            return $insert_columns;
        }
    }

    function fs_create($insert_columns){

        //Missing anything?
        if(!isset($insert_columns['fs_access_token'])){
            return false;
        } elseif(!isset($insert_columns['fs_u_id'])){
            return false;
        } elseif(!isset($insert_columns['fs_fp_id'])){
            return false;
        }

        //Autocomplete required
        if(!isset($insert_columns['fs_timestamp'])){
            $insert_columns['fs_timestamp'] = date("Y-m-d H:i:s");
        }
        if(!isset($insert_columns['fs_status'])){
            $insert_columns['fs_status'] = 1; //Authorized
        }

        //Lets now add:
        $this->db->insert('v5_facebook_page_admins', $insert_columns);

        //Fetch inserted id:
        $insert_columns['fs_id'] = $this->db->insert_id();

        if(!$insert_columns['fs_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error fs_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
            return false;
        } else {
            return $insert_columns;
        }
    }

	
	/* ******************************
	 * Classes
	 ****************************** */
	
	function r_fetch($match_columns , $bootcamp=null /* Passing this would load extra variables for the class as well! */, $sorting='DESC' ){
	    
		//Missing anything?
		$this->db->select('r.*');
        $this->db->from('v5_classes r');
        $this->db->join('v5_class_students ru', 'ru.ru_r_id = r.r_id', 'left');
		foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
		}
		$this->db->group_by('r.r_id');
        $this->db->order_by('r.r_status','DESC'); //Most recent class at top
        $this->db->order_by('r.r_start_date',$sorting); //Most recent class at top
        $this->db->order_by('r.r_start_time_mins',$sorting); //Most recent class at top
		$q = $this->db->get();
		
		$runs = $q->result_array();
		foreach($runs as $key=>$class){

		    //Fetch admission count:
		    //NOTE: Anything added here with prefix "r__" will be removed on api_v1/r_create() when duplicating a class
            $runs[$key]['r__current_admissions'] = count($this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status >='	=> 2, //Anyone who is pending admission
            )));
            $runs[$key]['r__confirmed_admissions'] = count($this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status >='	=> 4, //Anyone who is admitted
            )));

            if($bootcamp){

                //Fetch financial data:
                $mench_pricing = $this->config->item('mench_pricing');

                //Figure out total instructor earnings:
                $this->db->select('SUM(t_total) as usd_transactions');
                $this->db->from('v5_transactions t');
                $this->db->join('v5_class_students ru' , 'ru.ru_id = t.t_ru_id');
                $this->db->where('ru_r_id',$class['r_id']);
                $this->db->where('t_status IN (-1,0,1)');
                $q = $this->db->get();
                $earnings = $q->row_array();
                //For now calculate operator and distributor for the instructor as they would be doing both:
                $runs[$key]['r__usd_earnings'] = ($earnings['usd_transactions'] * ($mench_pricing['share_distributor']+$mench_pricing['share_operator'])); //Calculates the total earnings for this class so far


                //Now calculate start time and end time for this class:
                $runs[$key]['r__class_start_time'] = strtotime($class['r_start_date']) + ( $class['r_start_time_mins'] * 60 );
                $runs[$key]['r__class_end_time'] = mktime(floor($class['r_start_time_mins']/60),intval(fmod($class['r_start_time_mins'],60)),0,date("n",strtotime($class['r_start_date'])),(date("j",strtotime($class['r_start_date']))+($bootcamp['c__milestone_units'] * $bootcamp['c__milestone_secs'] / 24 / 3600)),date("Y",strtotime($class['r_start_date'])));

                //We're in the middle of this class, let's find out where:
                $runs[$key]['r__milestones_due'] = array(); //Will hold all the dates for the milestone...
                //Figure out the totals:
                $runs[$key]['r__last_milestone_starts'] = 0;
                $runs[$key]['r__total_milestones'] = 0;
                foreach($bootcamp['c__child_intents'] as $intent) {
                    if($intent['c_status']>=1){
                        $runs[$key]['r__milestones_due'][$intent['cr_outbound_rank']] = $runs[$key]['r__class_start_time'] + ( ($intent['cr_outbound_rank'] + $intent['c_duration_multiplier'] - 1 ) * $bootcamp['c__milestone_secs']);
                        //Addup the totals:
                        $runs[$key]['r__last_milestone_starts'] = intval($intent['cr_outbound_rank']);
                        $runs[$key]['r__total_milestones'] += $intent['c_duration_multiplier'];
                    }
                }

                //We'd now need to calculate Due Date to determine where the class currently is!
                //There are 3 situations for $class_position: Class not started yet (0), On a particular Milestone (), or Class has ended (-1)
                if($runs[$key]['r__class_start_time']>=time()){
                    //Not yet started, so no leaderboard:
                    $runs[$key]['r__current_milestone'] = 0;
                } elseif($runs[$key]['r__class_end_time']<=time()){
                    //Class has ended
                    $runs[$key]['r__current_milestone'] = -1;
                } else {
                    //See which one of the milestones is being workined on now...
                    if(count($runs[$key]['r__milestones_due'])>0){
                        foreach($runs[$key]['r__milestones_due'] as $cr_outbound_rank=>$due_timestamp){
                            $past_due = ( $due_timestamp < time() );
                            if(!$past_due){
                                $runs[$key]['r__current_milestone'] = $cr_outbound_rank;
                                break;
                            }
                        }

                        if(!isset($runs[$key]['r__current_milestone'])){
                            //Did we find it? If not, report this:
                            $this->Db_model->e_create(array(
                                'e_message' => 'r_fetch() failed to set r__current_milestone variable ',
                                'e_json' => $class,
                                'e_type_id' => 8, //Platform Error
                                'e_b_id' => $class['r_b_id'],
                                'e_r_id' => $class['r_id'],
                            ));
                        }


                    } else {
                        //This will happen when there are no active milestones:
                        $runs[$key]['r__current_milestone'] = 0; //We count this as not started for now...
                    }
                }
            }
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
        $this->db->join('v5_classes r', 'r.r_id = ru.ru_r_id');
        $this->db->join('v5_users u', 'u.u_id = ru.ru_u_id');
        foreach($match_columns as $key=>$value){
            $this->db->where($key,$value);
        }

        //Order by completion rate:
        $this->db->order_by('ru.ru_cache__completion_rate','DESC');
        $this->db->order_by('u.u_fb_id','ASC');

        $q = $this->db->get();
        return $q->result_array();
    }


    function fetch_c_tree($c_id){
        //This function would fetch all the child c_id's for the input c_id
        $c_tree = array(intval($c_id));
        $child_intents = $this->Db_model->cr_outbound_fetch(array(
            'cr.cr_inbound_id' => $c_id,
            'cr.cr_status >=' => 0,
            'c.c_status >=' => 0,
        ));
        if(count($child_intents)>0){
            //Yes, it does, lets go through them recursively:
            foreach($child_intents as $c){
                //Do recursive
                $c_tree = array_merge($c_tree,$this->fetch_c_tree($c['c_id']));
            }
        }
        return $c_tree;
    }
	
	
	function c_fetch($match_columns, $outbound_levels=0, $join_objects=array()){
	    
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
	    if(count($intents)>0 && in_array('i',$join_objects)){
	        $intents[0]['c__messages'] = array('ss');
	        //Fetch Messages:
	        foreach($intents as $key=>$value){
	            $intents[$key]['c__messages'] = $this->Db_model->i_fetch(array(
	                'i_c_id' => $value['c_id'],
	                'i_status >' => 0, //Published in any form
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
	            ) , $join_objects );
	            
	            //need more depth?
	            if(count($intents[$key]['c__child_intents'])>0 && $outbound_levels>=2){
	                //Start the second level:
	                foreach($intents[$key]['c__child_intents'] as $key2=>$value2){
	                    $intents[$key]['c__child_intents'][$key2]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
	                        'cr.cr_inbound_id' => $value2['c_id'],
	                        'cr.cr_status >' => 0,
	                    ) , $join_objects );
	                }
	            }
	        } 
	    }
	    
	    //Return everything that was collected:
	    return $intents;
	}

	function b_fetch($match_columns,$c_fetch=false,$order_by='b_id'){
	    //Missing anything?
	    $this->db->select('*');
        $this->db->from('v5_bootcamps b');
        if($c_fetch){
            $this->db->join('v5_intents c', 'c.c_id = b.b_c_id');
        }
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by($order_by,'DESC');
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
	
	
	function cr_outbound_fetch($match_columns,$join_objects=array()){
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
		    if(in_array('i',$join_objects)){
		        //Fetch Messages:
		        foreach($return as $key=>$value){
		            $return[$key]['c__messages'] = $this->Db_model->i_fetch(array(
		                'i_c_id' => $value['c_id'],
		                'i_status >' => 0, //Published in any form
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
		if($table=='v5_intent_links'){
		    //This is a HACK :D
            $this->db->from('v5_intent_links cr');
            $this->db->join('v5_intents c', 'cr.cr_outbound_id = c.c_id');
        } else {
            $this->db->from($table);
        }
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

        if(!$insert_columns['r_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error r_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }

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

        if(!$insert_columns['b_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error b_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }
	    
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

        if(!$insert_columns['c_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error c_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }
		
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

        if(!$insert_columns['ba_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error ba_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_type_id' => 8, //Platform Error
            ));
        }
	    
	    return $insert_columns;
	}
	
	
	
	function ej_fetch($match_columns=array()){
        $this->db->select('*');
        $this->db->from('v5_engagement_blob ej');
        foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
        }
        $q = $this->db->get();
        return $q->result_array();
    }

    function snapshot_action_plan($b_id,$r_id){

	    //Saves a copy of the Action Plan for the Class to use it:
        $bootcamps = $this->Db_model->remix_bootcamps(array(
            'b.b_id' => $b_id,
        ));

        if(count($bootcamps)<1){
            return false;
        }

        //Remove unnecessary fields:
        unset($bootcamps[0]['b__admins']);
        unset($bootcamps[0]['c__classes']); //Our Class may not be in here!

        //Also Update Class end Date:
        //Fetch Class Details:
        $classes = $this->Db_model->r_fetch(array(
            'r_id' => $r_id,
        ), $bootcamps[0] );

        if(count($classes)<1){
            return false;
        }

        //Take snapshot of Class End Time:
        $this->Db_model->r_update( $r_id , array(
            'r_cache__end_time' => date("Y-m-d H:i:s",$classes[0]['r__class_end_time']),
        ));

        //Save Action Plan Copy:
        $this->Db_model->e_create(array(
            'e_json' => $bootcamps[0],
            'e_type_id' => 70, //Action Plan Snapshot
            'e_b_id' => $bootcamps[0]['b_id'],
            'e_c_id' => $bootcamps[0]['b_c_id'],
            'e_r_id' => $r_id,
        ));

        return true;

    }
	
	function e_fetch($match_columns=array(),$limit=100,$join_objects=array()){
	    $this->db->select('*');
	    $this->db->from('v5_engagements e');
	    $this->db->join('v5_engagement_types a', 'a.a_id=e.e_type_id');
	    $this->db->join('v5_users u', 'u.u_id=e.e_initiator_u_id','left');
        if(in_array('ej',$join_objects)){
            $this->db->join('v5_engagement_blob ej', 'ej.ej_e_id=e.e_id','left');
        }
        if(in_array('i',$join_objects)){
            $this->db->join('v5_messages i', 'i.i_id=e.e_i_id','left');
        }
        if(in_array('fp',$join_objects)){
            $this->db->join('v5_facebook_pages fp', 'fp.fp_id=e.e_fp_id','left');
        }
	    foreach($match_columns as $key=>$value){
	        if(!is_null($value)){
	            $this->db->where($key,$value);
	        } else {
	            $this->db->where($key);
	        }
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
	    if(!isset($link_data['e_message'])){
	        $link_data['e_message'] = null;
	    }
		
	    
		//Now check required fields:
		if(!isset($link_data['e_type_id']) || intval($link_data['e_type_id'])<=0){
		    //Log this error:
		    $this->Db_model->e_create(array(
                'e_initiator_u_id' => $link_data['e_initiator_u_id'],
                'e_message' => 'e_create() Function missing [e_type_id] variable.',
                'e_json' => $link_data,
                'e_type_id' => 8, //Platform Error
            ));
			return false;
		}

		//Do we have a json attachment for this engagement?
		$save_blob = null;
        if(isset($link_data['e_json']) && strlen(print_r($link_data['e_json'],true))>0){
            if(is_array($link_data['e_json']) && count($link_data['e_json'])>0){
                $save_blob = $link_data['e_json'];
                $link_data['e_has_blob'] = 't';
            }
        }

        //Remove e_json from here to keep v5_engagements small and lean
        unset($link_data['e_json']);
		
		//Lets log:
		$this->db->insert('v5_engagements', $link_data);

		//Fetch inserted id:
		$link_data['e_id'] = $this->db->insert_id();

		if($link_data['e_id']>0){

		    //Did we have a blob to save?
            if($save_blob){
                //Save this in a separate field:
                $this->db->insert('v5_engagement_blob', array(
                    'ej_e_id' => $link_data['e_id'],
                    'ej_e_blob' => serialize($save_blob),
                ));
            }

            //Notify relevant subscribers about this notification:
            $engagement_subscriptions = $this->config->item('engagement_subscriptions');
            $instructor_subscriptions = $this->config->item('instructor_subscriptions');
            $engagement_references = $this->config->item('engagement_references');

            //Email: The [33] Engagement ID corresponding to task completion is a email system for instructors to give them more context on certain activities

            //Do we have any instructor subscription:
            if(isset($link_data['e_b_id']) && $link_data['e_b_id']>0 && in_array($link_data['e_type_id'],$instructor_subscriptions)){

                //Just do this one:
                if(!isset($engagements[0])){
                    //Fetch Engagement Data:
                    $engagements = $this->Db_model->e_fetch(array(
                        'e_id' => $link_data['e_id']
                    ));
                }

                //Did we find it? We should have:
                if(isset($engagements[0])){

                    //Fetch all Bootcamp Instructors and Notify them:
                    $bootcamp_instructors = $this->Db_model->ba_fetch(array(
                        'ba.ba_b_id' => $link_data['e_b_id'],
                        'ba.ba_status >=' => 2, //co-instructors & lead instructor
                        'u.u_status >=' => 1, //Must be a user level 1 or higher
                        'u.u_fb_id >' => 0, //Activated messenger
                    ));

                    $subject = '⚠️ Notification: '.trim(strip_tags($engagements[0]['a_name'])).' by '.( isset($engagements[0]['u_fname']) ? $engagements[0]['u_fname'].' '.$engagements[0]['u_lname'] : 'System' );
                    $url = 'https://mench.co/console/'.$link_data['e_b_id'];

                    //Determine the body of this notification, as we prefer the custom notes on e_message, and if not, we'd go with a_desc
                    $body = ( strlen($link_data['e_message'])>0 ? trim(strip_tags($link_data['e_message'])) : trim(strip_tags($engagements[0]['a_desc'])) );

                    //Send notifications to current instructor
                    foreach($bootcamp_instructors as $bi){
                        if(in_array($link_data['e_type_id'],$instructor_subscriptions)){

                            //Mench notifications:
                            $this->Facebook_model->batch_messages( '381488558920384', $bi['u_fb_id'], array(echo_i(array(
                                'i_media_type' => 'text',
                                'i_message' => $subject."\n\n".$body."\n\n".$url,
                                'i_url' => $url,
                                'e_initiator_u_id' => 0, //System/MenchBot
                                'e_recipient_u_id' => $bi['u_id'],
                                'e_b_id' => $link_data['e_b_id'],
                                'e_r_id' => ( isset($link_data['e_r_id']) ? $link_data['e_r_id'] : 0 ),
                            ), $bi['u_fname'], true )));

                        }
                    }
                }
            }

            //Individual subscriptions:
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
                        $this->load->model('Email_model');
                        $this->Email_model->send_single_email($subscription['admin_emails'],$subject,$html_message);
                    }
                }
            }

        } else {

            //Log this query Error
            $this->Db_model->e_create(array(
                'e_message' => 'Query Error e_create() : '.$this->db->_error_message(),
                'e_json' => $link_data,
                'e_type_id' => 8, //Platform Error
            ));

            return false;
        }
		
		//Return:
		return $link_data;
	}
	

	
	function sync_algolia($c_id=null){
		
		boost_power();
		
		$website = $this->config->item('website');
		
		if(is_dev()){
		    return curl_html($website['url']."api_v1/algolia/".$c_id);
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
