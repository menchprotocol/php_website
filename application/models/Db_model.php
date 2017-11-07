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
	        $cohorts = $this->Db_model->r_fetch(array(
	            'r.r_id' => $enrollment['ru_r_id'],
	        ));
	        if(count($cohorts)<=0){
	            return false;
	        }
	        //Merge in:
	        $admissions[$key] = array_merge($admissions[$key] , $cohorts[0]);
	        //Fetch bootcamp:
	        $bootcamps = $this->Db_model->c_full_fetch(array(
	            'b.b_id' => $cohorts[0]['r_b_id'],
	        ));
	        if(count($bootcamps)<=0){
	            return false;
	        }
	        //Merge in:
	        $admissions[$key] = array_merge($admissions[$key] , $bootcamps[0]);
	    }
	    
	    return $admissions;
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
		$q = $this->db->get();
		return $q->result_array();
	}
	
	function ru_update($ru_id,$update_columns){
	    //Update first
	    $this->db->where('ru_id', $ru_id);
	    $this->db->update('v5_cohort_students', $update_columns);
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
	    $this->db->insert('v5_cohort_students', $insert_columns);
	    
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
	
	
	function c_admins($b_id){
	    //Fetch the admins of the bootcamps
	    $this->db->select('*');
	    $this->db->from('v5_users u');
	    $this->db->join('v5_bootcamp_admins ba', 'ba.ba_u_id = u.u_id');
	    $this->db->where('ba.ba_status >=',0);
	    $this->db->where('ba.ba_b_id',$b_id);
	    $this->db->where('u.u_status >=',0);
	    $this->db->order_by('ba.ba_status','DESC');
	    $this->db->order_by('ba.ba_team_display','DESC');
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	
	function u_bootcamps($match_columns){
	    $this->db->select('*');
	    $this->db->from('v5_intents c');
	    $this->db->join('v5_bootcamps b', 'b.b_c_id = c.c_id');
	    $this->db->join('v5_bootcamp_admins ba', 'ba.ba_b_id = b.b_id');
	    $this->db->order_by('b.b_status', 'DESC');
	    $this->db->order_by('c.c_objective', 'ASC');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	
	function u_fb_search($u_fb_id){
		//A function to check or create a new user using FB id:
		
		//Search for current users:
		$matching_users = $this->u_fetch(array(
				'u_fb_id' => $u_fb_id,
		));
		
		$u_id = null;
		
		//What did we find?
		if(count($matching_users) == 1){
			
			//Yes, we found them!
			$u_id = $matching_users[0]['u_id'];
			
		} elseif(count($matching_users) <= 0){
			
			//This is a new user that needs to be registered!
			$u_id = $this->u_fb_create($u_fb_id);
			
		} else {
			//Inconsistent data:
		    $this->Db_model->e_create(array(
		        'e_message' => 'u_fb_search() Found multiple users for Facebook ID ['.$u_fb_id.'].',
		        'e_json' => json_encode($matching_users),
		        'e_type_id' => 8, //Platform Error
		    ));
			return 0;
		}
		
		
		if($u_id){
			//We found it!
			return $u_id;
		} else {
			//Ooops, some error!
		    $this->Db_model->e_create(array(
		        'e_message' => 'u_fb_search() Failed to create/fetch user using their Facebook ID ['.$u_fb_id.'].',
		        'e_type_id' => 8, //Platform Error
		    ));
			return 0;
		}
	}
	
	function u_fb_fetch($full_name){		
		//Fetch the user using their full name
		$this->db->select('u_fb_id, LOWER(CONCAT(u_fname," ",u_lname)) AS full_name');
		$this->db->from('v5_users u');
		$this->db->where('full_name',trim(strtolower($full_name)));
		$this->db->where('u_status >',0);
		$q = $this->db->get();
		$matching_users = $q->result_array();
		
		if(count($matching_users)>0){
			
			if(count($matching_users)>=2){
				//Multiple users found!
			    $this->Db_model->e_create(array(
			        'e_message' => 'u_fb_fetch() Found '.count($matching_users).' users with a full name of ['.$full_name.']. First result returned.',
			        'e_json' => json_encode($matching_users),
			        'e_type_id' => 8, //Platform Error
			    ));
			}
			
			//Return first result:
			return $matching_users[0]['u_fb_id'];
		}
		
		//Ooops, nothing found!
		return null;
	}
	
	
	function u_fb_create($u_fb_id){
		
		//Call facebook messenger API and get user details
		//https://developers.facebook.com/docs/messenger-platform/user-profile/
		$fb_profile = $this->Facebook_model->fetch_profile($u_fb_id);
		
		if(!isset($fb_profile['first_name'])){
			//There was an issue accessing this on FB
			return false;
		}
		
		//Do we already have this person?
		$matching_users = $this->u_fetch(array(
				'u_fb_id' => $u_fb_id,
		));
		
		//Is this user in our system already?
		if(count($matching_users)>0){
			if(count($matching_users)>=2){
			    $this->Db_model->e_create(array(
			        'e_message' => 'u_fb_create() Found multiple users for Facebook ID ['.$u_fb_id.'].',
			        'e_json' => json_encode($matching_users),
			        'e_type_id' => 8, //Platform Error
			    ));
			}
			
			//Yes, just assume the user is the first result:
			return $matching_users[0]['u_id'];
		}
		
		//Split locale into language and country
		$locale = explode('_',$fb_profile['locale'],2);
		
		//Create user
		$udata = $this->u_create(array(
				'u_fb_id' 			=> $u_fb_id,
				'u_fname' 			=> $fb_profile['first_name'],
				'u_lname' 			=> $fb_profile['last_name'],
				'u_timezone' 		=> $fb_profile['timezone'],
				'u_image_url' 		=> $fb_profile['profile_pic'],
				'u_gender'		 	=> strtolower(substr($fb_profile['gender'],0,1)),
				'u_language' 		=> $locale[0],
				'u_country_code' 	=> $locale[1],
		));
		
		return $udata['u_id'];
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
		$this->db->select('i.*');
		$this->db->from('v5_media i');
		$this->db->join('v5_intents c', 'i.i_c_id = c.c_id');
		$this->db->where('c.c_status >=',0);
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->order_by('i_dispatch_minutes');
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
		if(!isset($insert_columns['i_status'])){
			$insert_columns['i_status'] = 0; //Drafting...
		}
		
		//Lets now add:
		$this->db->insert('v5_media', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['i_id'] = $this->db->insert_id();
		
		return $insert_columns;
	}
	
	function i_update($i_id,$update_columns){
		$this->db->where('i_id', $i_id);
		$this->db->update('v5_media', $update_columns);
		return $this->db->affected_rows();
	}
	
	/* ******************************
	 * Cohorts
	 ****************************** */
	
	function r_fetch($match_columns){
	    
		//Missing anything?
		$this->db->select('r.*');
		$this->db->from('v5_cohorts r');
		$this->db->join('v5_cohort_students ru', 'ru.ru_r_id = r.r_id', 'left');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->group_by('r.r_id');
		$this->db->order_by('r.r_start_date','ASC');
		$q = $this->db->get();
		
		$runs = $q->result_array();
		foreach($runs as $key=>$value){
		    //Fetch admission count:
		    //TODO NOTE: Anything you add here, make sure to remove from controller/function: Process/cohort_create() when duplicating a cohort
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
	    
	    $this->db->from('v5_cohort_students ru');
	    $this->db->join('v5_cohorts r', 'r.r_id = ru.ru_r_id');
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
		$this->db->from('v5_cohort_students ru');
		$this->db->join('v5_users u', 'u.u_id = ru.ru_u_id');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
		return $q->result_array();
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
	        
	        
	        
	        //Fetch Sub-Goals:
	        $bootcamps[$key]['c__task_count'] = 0;
	        $bootcamps[$key]['c__tip_count'] = 0;
	        $bootcamps[$key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
	            'cr.cr_inbound_id' => $c['c_id'],
	            'cr.cr_status >=' => 0,
	        ));
	        
	        foreach($bootcamps[$key]['c__child_intents'] as $sprint_key=>$sprint_value){
	            //Addup sprint estimated time:
	            $bootcamps[$key]['c__estimated_hours'] += $sprint_value['c_time_estimate'];
	            //Introduce sprint total time:
	            $bootcamps[$key]['c__child_intents'][$sprint_key]['c__estimated_hours'] = $sprint_value['c_time_estimate'];
	            
	            //Fetch sprint tasks at level 3:
	            $bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
	                'cr.cr_inbound_id' => $sprint_value['c_id'],
	                'cr.cr_status >=' => 0,
	            ));
	            
	            
	            //Count tips:
	            $sprint_tips = count($this->Db_model->i_fetch(array(
	                'i_status >=' => 0,
	                'i_c_id' => $sprint_value['c_id'],
	            )));
	            $bootcamps[$key]['c__tip_count'] += $sprint_tips;
	            $bootcamps[$key]['c__child_intents'][$sprint_key]['c__tip_count'] = $sprint_tips;
	            
	            //Addup task values:
	            foreach($bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'] as $task_key=>$task_value){
	                //Addup task estimated time:
	                $bootcamps[$key]['c__estimated_hours'] += $task_value['c_time_estimate'];
	                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__estimated_hours'] += $task_value['c_time_estimate'];
	                $bootcamps[$key]['c__task_count']++;
	                
	                //Count tips:
	                $task_tips = count($this->Db_model->i_fetch(array(
	                    'i_status >=' => 0,
	                    'i_c_id' => $task_value['c_id'],
	                )));
	                $bootcamps[$key]['c__tip_count'] += $task_tips;
	                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__tip_count'] += $task_tips;
	                $bootcamps[$key]['c__child_intents'][$sprint_key]['c__child_intents'][$task_key]['c__tip_count'] = $task_tips;
	            }
	        }
	        
	        //Fetch cohorts:
	        $bootcamps[$key]['c__cohorts'] = $this->r_fetch(array(
	            'r.r_b_id' => $c['b_id'],
	            'r.r_status >=' => 0,
	        ));
	        
	        //Fetch admins:
	        $bootcamps[$key]['b__admins'] =  $this->Db_model->c_admins($c['b_id']);
	    }
	    
	    return $bootcamps;
	}
	
	function c_fetch($match_columns){
	    //Missing anything?
	    $this->db->select('*');
	    $this->db->from('v5_intents c');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	function b_fetch($match_columns){
	    //Missing anything?
	    $this->db->select('*');
	    $this->db->from('v5_bootcamps b');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $q = $this->db->get();
	    return $q->result_array();
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
	
	
	function cr_outbound_fetch($match_columns){
		//Missing anything?
		$this->db->select('*');
		$this->db->from('v5_intents c');
		$this->db->join('v5_intent_links cr', 'cr.cr_outbound_id = c.c_id');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->order_by('cr.cr_outbound_rank','ASC');
		$q = $this->db->get();
		return $q->result_array();
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
	    $this->db->update('v5_cohorts', $update_columns);
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
	    $this->db->update('v5_instructor_leads', $update_columns);
	    return $this->db->affected_rows();
	}
	function il_create($insert_columns){
	    $this->db->insert('v5_instructor_leads', $insert_columns);
	    $insert_columns['il_id'] = $this->db->insert_id();    
	    return $insert_columns;
	}
	function il_fetch($match_columns=array()){
	    $this->db->select('*');
	    $this->db->from('v5_instructor_leads il');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by('il_id','ASC');
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	
	function r_create($insert_columns){
	    $this->db->insert('v5_cohorts', $insert_columns);
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
	    $this->db->insert('v5_bootcamp_admins', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['ba_id'] = $this->db->insert_id();
	    
	    return $insert_columns;
	}
	
	
	
	
	
	function e_fetch($match_columns=array()){
	    $this->db->select('*');
	    $this->db->from('v5_engagements e');
	    $this->db->join('v5_engagement_types a', 'a.a_id=e.e_type_id');
	    $this->db->join('v5_users u', 'u.u_id=e.e_creator_id','left');
	    $this->db->join('v5_bootcamps b', 'b.b_id=e.e_b_id','left');
	    $this->db->join('v5_intents c', 'c.c_id=b.b_c_id','left');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by('e.e_id','DESC');
	    $this->db->limit(100);
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	
	
	function e_create($link_data){
	    
	    //Sort out the optional fields first:
	    if(!isset($link_data['e_creator_id'])){
	        //Try to fetch user ID from session:
	        $user_data = $this->session->userdata('user');
	        if(isset($user_data['u_id']) && intval($user_data['u_id'])>0){
	            $link_data['e_creator_id'] = $user_data['u_id'];
	        } else {
	            //Have no user:
	            $link_data['e_creator_id'] = 0;
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
	    if(!isset($link_data['e_object_id'])){
	        $link_data['e_object_id'] = 0;
	    }
		
	    
		//Now check required fields:
		if(!isset($link_data['e_type_id'])){
		    //Log this error:
		    $this->Db_model->e_create(array(
		        'e_creator_id' => $link_data['e_creator_id'],
		        'e_message' => 'e_create() Function missing [e_type_id] variable.',
		        'e_json' => json_encode($link_data),
		        'e_type_id' => 8, //Platform Error
		        'e_object_id' => $link_data['e_object_id'], //Maybe there!
		    ));
			return false;
		}
		
		//Lets log:
		$this->db->insert('v5_engagements', $link_data);

		//Fetch inserted id:
		$link_data['e_id'] = $this->db->insert_id();
		
		
		//Do we need to notify the admin about this engagement?
		if($link_data['e_id']>0 && in_array($link_data['e_type_id'],array(33,6))){
		    
		    //Fetch Engagement Data:
		    $engagements = $this->Db_model->e_fetch(array(
		        'e_id' => $link_data['e_id']
		    ));
		    if(isset($engagements[0])){
		        $by = (isset($engagements[0]['u_fname']) ? $engagements[0]['u_fname'].' '.$engagements[0]['u_lname'] : 'System');
		        $subject = 'New '.trim(strip_tags($engagements[0]['a_name'])).' by '.$by;
		        //Compose email:
		        $html_message = null; //Start
		        $html_message .= '<div>Hi Mench Admin,</div><br />';
		        $html_message .= '<div>'.$engagements[0]['a_desc'].':</div><br />';
		        
		        $html_message .= '<div>Initiator: '.$by.'</div>';
		        if(intval($engagements[0]['e_object_id'])>0){
		            $html_message .= '<div>Applied To: '.object_link($engagements[0]['a_object_code'],$engagements[0]['e_object_id'],$engagements[0]['e_b_id']).'</div>';
		        }
		        $html_message .= '<div>Content: '.$engagements[0]['e_message'].'</div>';
		        $html_message .= '<br />';
		        $html_message .= '<div>Cheers,</div>';
		        $html_message .= '<div>MenchBot</div>';
		        $this->load->model('Email_model');
		        $this->Email_model->send_single_email(array('miguel@mench.co'),$subject,$html_message);
		    }
		}
		
		//Boya!
		return $link_data;
	}
	

	
	function sync_algolia($c_id=null){
		
		boost_power();
		
		$website = $this->config->item('website');
		
		if(is_dev()){
		    return file_get_contents($website['url']."process/algolia/".$c_id);
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
