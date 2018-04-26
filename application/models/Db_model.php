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
JOIN v5_entities u ON u.u_id = ru.ru_u_id
WHERE ru.ru_status >= 4
  AND ru_r_id = ".$r_id)->result());
    }

	/* ******************************
	 * Remix functions that fetch a bunch of existing data:
	 ****************************** */
	
	function remix_admissions($matching_criteria,$order_columns=array(
        'ru.ru_id' => 'DESC',
    )){

	    $admissions = $this->Db_model->ru_fetch($matching_criteria,$order_columns);

	    //Fetch more data for each admission:
	    foreach($admissions as $key=>$admission){

            //Fetch Bootcamp:
            $bs = $this->Db_model->remix_bs(array(
                'b.b_id' => $admission['ru_b_id'],
            ));

            if(count($bs)<=0){
                $this->Db_model->e_create(array(
                    'e_text_value' => 'remix_admissions() had invalid [ru_b_id]='.$admission['ru_b_id'],
                    'e_json' => $matching_criteria,
                    'e_inbound_c_id' => 8, //Platform Error
                ));
                unset($admissions[$key]);
                continue;
            } else {
                //Merge in:
                $admissions[$key] = array_merge($admissions[$key] , $bs[0]);
            }

            //Fetch Class:
            if($admission['ru_r_id']>0){
                $classes = $this->Db_model->r_fetch(array(
                    'r.r_id' => $admission['ru_r_id'],
                    'r.r_status >=' => 1,
                ));
                if(count($classes)<1){
                    $this->Db_model->e_create(array(
                        'e_text_value' => 'remix_admissions() had invalid [r_id]='.$admission['ru_r_id'],
                        'e_json' => $matching_criteria,
                        'e_inbound_c_id' => 8, //Platform Error
                    ));
                    unset($admissions[$key]);
                    continue;
                }

                //Merge in:
                $admissions[$key] = array_merge($admissions[$key] , $classes[0]);
            }
	    }

	    return $admissions;
	}

    function remix_bs($match_columns, $join_objects=array()){

        //Missing anything?
        $this->db->select('*');
        $this->db->from('v5_bootcamps b');
        $this->db->join('v5_intents c', 'c.c_id = b.b_c_id');
        if(count($join_objects)==0 || in_array('fp',$join_objects)){
            $this->db->join('v5_facebook_pages fp', 'fp.fp_id = b.b_fp_id','left');
        }

        foreach($match_columns as $key=>$value){
            $this->db->where($key,$value);
        }
        $q = $this->db->get();
        $bs = $q->result_array();

        //Now append more data:
        foreach($bs as $key=>$c){

            //Bootcamp Messages:
            if(count($join_objects)==0 || in_array('i',$join_objects)){
                $bs[$key]['c__messages'] = $this->Db_model->i_fetch(array(
                    'i_status >' => 0,
                    'i_c_id' => $c['c_id'],
                ));
                $bs[$key]['c__message_tree_count'] = count($bs[$key]['c__messages']);
            }

            // "ihm" is to find the header image of the Bootcamp
            if(count($join_objects)>0 && in_array('ihm',$join_objects)){

                $b_messages = $this->Db_model->i_fetch(array(
                    'i_status >=' => 1,
                    'i_c_id' => $c['c_id'],
                ));
                $bs[$key]['c__header_media'] = null;

                foreach ($b_messages as $i){
                    if(in_array($i['i_media_type'],array('image'))){

                        $bs[$key]['c__header_media'] = echo_i($i);
                        break;

                    } elseif($i['i_media_type']=='text' && strlen($i['i_url'])>0){

                        //Attempt to find the image for the cover photo:
                        $content_image = detect_embed_media($i['i_url'],$i['i_url'],true);

                        //Did we find a valid image?
                        if($content_image){
                            $bs[$key]['c__header_media'] = $content_image;
                            break;
                        }

                    }
                }

                //Did we find an image?
                if(!$bs[$key]['c__header_media']){
                    $bs[$key]['c__header_media'] = echo_i(array(
                        'i_media_type' => 'image',
                        'i_url' => '/img/bg.jpg',
                    ));
                }
            }

            //Fetch team:
            if(count($join_objects)==0 || in_array('ba',$join_objects)){
                $bs[$key]['b__admins'] = $this->Db_model->ba_fetch(array(
                    'ba.ba_b_id' => $c['b_id'],
                    'ba.ba_status >=' => 0,
                    'u.u_status >=' => 0,
                ));
            }

            //Fetch Sub-intents:
            $bs[$key]['c__active_intents'] = array();
            $bs[$key]['c__child_count'] = 0;
            $bs[$key]['c__child_child_count'] = 0;
            $bs[$key]['c__estimated_hours'] = $bs[$key]['c_time_estimate'];
            $bs[$key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
                'cr.cr_inbound_c_id' => $c['c_id'],
                'cr.cr_status >=' => 0,
                'c.c_status >=' => 0,
            ));
            $bs[$key]['b__week_count'] = ( $c['b_is_parent'] ? count($bs[$key]['c__child_intents']) : 1 );


            foreach($bs[$key]['c__child_intents'] as $intent_key=>$intent){

                //Count Messages:
                if(count($join_objects)==0 || in_array('i',$join_objects)){
                    $intent_messages = $this->Db_model->i_fetch(array(
                        'i_status >' => 0,
                        'i_c_id' => $intent['c_id'],
                    ));
                    $bs[$key]['c__child_intents'][$intent_key]['c__message_tree_count'] = 0;
                    $bs[$key]['c__message_tree_count'] += count($intent_messages);
                    $bs[$key]['c__child_intents'][$intent_key]['c__message_tree_count'] += count($intent_messages);
                    $bs[$key]['c__child_intents'][$intent_key]['c__messages'] = $intent_messages;
                }

                if($intent['c_status']>=1){
                    //Start by adding up the time:
                    $bs[$key]['c__estimated_hours'] += $intent['c_time_estimate'];
                    $bs[$key]['c__child_intents'][$intent_key]['c__estimated_hours'] = $intent['c_time_estimate'];
                    $bs[$key]['c__child_count']++;
                } else {
                    $bs[$key]['c__child_intents'][$intent_key]['c__estimated_hours'] = 0;
                }

                //Fetch sprint Steps at level 3:
                $bs[$key]['c__child_intents'][$intent_key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
                    'cr.cr_inbound_c_id' => $intent['c_id'],
                    'cr.cr_status >=' => 0,
                    'c.c_status >=' => 1,
                ));

                //Create Step array:
                $bs[$key]['c__active_intents'][$intent['c_id']] = array();

                //Addup Step values:
                foreach($bs[$key]['c__child_intents'][$intent_key]['c__child_intents'] as $step_key=>$step){

                    if(count($join_objects)==0 || in_array('i',$join_objects)){
                        //Count Messages:
                        $step_messages = $this->Db_model->i_fetch(array(
                            'i_status >' => 0,
                            'i_c_id' => $step['c_id'],
                        ));

                        //Add messages:
                        $bs[$key]['c__child_intents'][$intent_key]['c__child_intents'][$step_key]['c__messages'] = $step_messages;
                        //Always show Step message count regardless of status:
                        $bs[$key]['c__child_intents'][$intent_key]['c__child_intents'][$step_key]['c__message_tree_count'] = count($step_messages);
                        //Increase message counts:
                        $bs[$key]['c__message_tree_count'] += count($step_messages);
                        $bs[$key]['c__child_intents'][$intent_key]['c__message_tree_count'] += count($step_messages);
                    }

                    //Addup Task Hours for its Active Steps Regardless status:
                    $bs[$key]['c__child_intents'][$intent_key]['c__estimated_hours'] += $step['c_time_estimate'];


                    if($intent['c_status']>=1 && $step['c_status']>=1) {

                        //Addup Step estimated time for active Steps in active Tasks:
                        $bs[$key]['c__estimated_hours'] += $step['c_time_estimate'];

                        $bs[$key]['c__child_child_count']++;

                        //add to active Steps per Task:
                        array_push($bs[$key]['c__active_intents'][$intent['c_id']], $step['c_id']);

                    }
                }
            }
        }

        return $bs;
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
	    $this->db->from('v5_leads il');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by('il_student_count DESC');
	    $q = $this->db->get();
	    return $q->result_array();
	}
	
	function il_overview_fetch(){
	    //Fetches an overview of Udemy Community
	    $this->db->select('COUNT(il_id) as total_instructors, SUM(il_course_count) as total_courses, SUM(il_student_count) as total_students, SUM(il_review_count) as total_reviews, il_udemy_category');
	    $this->db->from('v5_leads il');
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
	    $this->db->from('v5_entities u');
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
	    if(!isset($insert_columns['ru_b_id'])){
	        $this->Db_model->e_create(array(
	            'e_text_value' => 'ru_create() missing ru_b_id.',
	            'e_json' => $insert_columns,
	            'e_inbound_c_id' => 8, //Platform Error
	        ));
	        return false;
	    } elseif(!isset($insert_columns['ru_u_id'])){
	        $this->Db_model->e_create(array(
	            'e_text_value' => 'ru_create() missing ru_u_id.',
	            'e_json' => $insert_columns,
	            'e_inbound_c_id' => 8, //Platform Error
	        ));
	        return false;
	    }
	    
	    //Lets now add:
	    $this->db->insert('v5_class_students', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['ru_id'] = $this->db->insert_id();

        if(!$insert_columns['ru_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error ru_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
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
                'e_text_value' => 'Query Error t_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
            ));
        }
	    
	    return $insert_columns;
	}
	
	function u_create($insert_columns){
		
		//Make sure required fields are here:
		if(!isset($insert_columns['u_fname'])){
			$this->Db_model->e_create(array(
			    'e_text_value' => 'u_create() missing u_fname.',
			    'e_json' => $insert_columns,
			    'e_inbound_c_id' => 8, //Platform Error
			));
			return false;
		}
		
		//Lets now add:
		$this->db->insert('v5_entities', $insert_columns);

        //Fetch inserted id:
        $insert_columns['u_id'] = $this->db->insert_id();

        if(!$insert_columns['u_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error u_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
            ));

            return false;

        } else {

            //All good, send Welcome to Mench Message:
            /*
            $this->Comm_model->foundation_message(array(
                'e_inbound_u_id' => 0,
                'e_outbound_u_id' => $insert_columns['u_id'],
                'e_outbound_u_id' => 5980, //Welcome to Mench
                'depth' => 0,
            ));
            */

            //Fetch to return:
            $users = $this->Db_model->u_fetch(array(
                'u_id' => $insert_columns['u_id'],
            ));

            return $users[0];
        }
	}
	
	function u_update($user_id,$update_columns){
	    //Update first
	    $this->db->where('u_id', $user_id);
	    $this->db->update('v5_entities', $update_columns);
	    //Return new row:
	    $users = $this->u_fetch(array(
	        'u_id' => $user_id
	    ));
	    return $users[0];
	}
	
	
	function ba_fetch($match_columns,$fetch_extra=false){
	    //Fetch the admins of the Bootcamps
	    $this->db->select('*');
	    $this->db->from('v5_entities u');
        $this->db->join('v5_bootcamp_team ba', 'ba.ba_u_id = u.u_id');
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
	
	
	function instructor_bs($match_columns){
	    $this->db->select('*');
	    $this->db->from('v5_intents c');
	    $this->db->join('v5_bootcamps b', 'b.b_c_id = c.c_id');
	    $this->db->join('v5_bootcamp_team ba', 'ba.ba_b_id = b.b_id');
        $this->db->join('v5_facebook_pages fp', 'fp.fp_id = b.b_fp_id','left');
	    $this->db->order_by('b.b_id', 'ASC');
	    foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $q = $this->db->get();
	    return $q->result_array();
	}



	
	/* ******************************
	 * i Messages
	 ****************************** */

    function i_fetch($match_columns, $limit=0){
        $this->db->select('*');
        $this->db->from('v5_messages i');
        $this->db->join('v5_intents c', 'i.i_c_id = c.c_id');
        $this->db->join('v5_entities u', 'u.u_id = i.i_creator_id');
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

        //Lets now add:
        $this->db->insert('v5_message_fb_sync', $insert_columns);

        //Fetch inserted id:
        $insert_columns['sy_id'] = $this->db->insert_id();

        if(!$insert_columns['sy_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error sy_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
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
		
		//Lets now add:
		$this->db->insert('v5_messages', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['i_id'] = $this->db->insert_id();

        if(!$insert_columns['i_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error i_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
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

    function fp_fetch($match_columns,$join_objects=array(),$order_columns=array('fs_timestamp'=>'DESC')){

        $this->db->select('*');
        $this->db->from('v5_facebook_pages fp');
        $this->db->join('v5_facebook_page_admins fs', 'fs.fs_fp_id = fp.fp_id', 'left');

        if(in_array('u',$join_objects)){
            $this->db->join('v5_entities u', 'u.u_id = fs.fs_u_id');
        }

        foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
        }

        foreach($order_columns as $key=>$value){
            $this->db->order_by($key,$value);
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

        //Lets now add:
        $this->db->insert('v5_facebook_pages', $insert_columns);

        //Fetch inserted id:
        $insert_columns['fp_id'] = $this->db->insert_id();

        if(!$insert_columns['fp_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error fp_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
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

        //Lets now add:
        $this->db->insert('v5_facebook_page_admins', $insert_columns);

        //Fetch inserted id:
        $insert_columns['fs_id'] = $this->db->insert_id();

        if(!$insert_columns['fs_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error fs_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
            ));
            return false;
        } else {
            return $insert_columns;
        }
    }

	
	/* ******************************
	 * Classes
	 ****************************** */

	function ru_finalize($ru_id){

	    //This function finalizes the admission for FREE Bootcamps or when the user pays:
        $admissions = $this->Db_model->remix_admissions(array(
            'ru.ru_id'	=> $ru_id,
        ));

        if(count($admissions)==1){

            $admissions_updated = 1;

            //Update student's payment status:
            $this->Db_model->ru_update( $ru_id , array(
                'ru_status' => 4,
            ));

            //Inform the Student:
            $this->Comm_model->foundation_message(array(
                'e_inbound_u_id' => 0,
                'e_outbound_u_id' => $admissions[0]['u_id'],
                'e_outbound_u_id' => 2698,
                'depth' => 0,
                'e_b_id' => $admissions[0]['ru_b_id'],
                'e_r_id' => $admissions[0]['ru_r_id'],
            ), true);


            //Log Engagement
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $admissions[0]['u_id'],
                'e_text_value' => ($admissions[0]['ru_final_price']>0 ? 'Received $'.$admissions[0]['ru_final_price'].' USD via PayPal.' : 'Student Joined FREE Bootcamp' ),
                'e_json' => $_POST,
                'e_inbound_c_id' => 30,
                'e_b_id' => $admissions[0]['ru_b_id'],
                'e_r_id' => $admissions[0]['ru_r_id'],
            ));

            if($admissions[0]['b_is_parent']){
                //This is a Parent Bootcamp, so we also need to update the Child admissions:
                $child_admissions = $this->Db_model->ru_fetch(array(
                    'ru_parent_ru_id' => $ru_id,
                ));

                foreach($child_admissions as $ru){
                    $admissions_updated++;
                    $this->Db_model->ru_update( $ru['ru_id'] , array(
                        'ru_status' => 4,
                    ));
                }
            }

            return $admissions_updated;
        }

        //Log Error:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $admissions[0]['u_id'],
            'e_text_value' => 'ru_finalize() failed to update admission for ru_id=['.$ru_id.']',
            'e_inbound_c_id' => 8,
            'e_b_id' => $admissions[0]['ru_b_id'],
            'e_r_id' => $admissions[0]['ru_r_id'],
        ));

        return 0;

    }

	function r_sync($b_id){

	    //First determine all dates we'd need:
        $class_settings = $this->config->item('class_settings');

        $dates_needed = array();
        $monday_start = ( date("w")==1 ? 2 : 1 );
        for($i=$monday_start;$i<=($class_settings['create_weeks_ahead']-1+$monday_start);$i++){
            array_push($dates_needed , date("Y-m-d",(strtotime($i.' mondays from now')+(12*3600)  /* For GMT/timezone adjustments */ )) );
        }

        //Let's see which Classes we have already?
        $classes = $this->Db_model->r_fetch(array(
            'r_b_id' => $b_id,
            'r_status >=' => 1,
            'r_start_date IN (\''.join('\',\'',$dates_needed).'\')' => null,
        ));

        if(count($classes)>0){
            //Remove these Classes from what we need:
            foreach($classes as $class){
                unset($dates_needed[array_search($class['r_start_date'], $dates_needed)]);
            }
        }

        //Do we have any dates left to create?
        if(count($dates_needed)>0){
            //Yes, start creating these Classes:
            foreach($dates_needed as $r_start_date){
                $this->Db_model->r_create(array(
                    'r_b_id' => $b_id,
                    'r_start_date' => $r_start_date,
                    'r_status' => 1, //Open for Admission
                ));
            }
        }

        return count($dates_needed);
    }
	
	function r_fetch($match_columns , $b=null /* Passing this would load extra variables for the class as well! */, $sorting='DESC', $limit=0, $join_objects=array() ){

        if(in_array('ru',$join_objects)){
            $this->db->select('r.*, COUNT(ru_id) as total_admissions');
        } elseif(in_array('b',$join_objects)){
            $this->db->select('*');
        } else {
            $this->db->select('r.*');
        }

        $this->db->from('v5_classes r');

        if(in_array('ru',$join_objects)){
            $this->db->join('v5_class_students ru', 'r.r_id = ru.ru_r_id');
            $this->db->where('ru.ru_status >=',4); //Always assume active students (!)
        }
        if(in_array('b',$join_objects)){
            $this->db->join('v5_bootcamps b', 'r.r_b_id = b.b_id');
            $this->db->join('v5_intents c', 'b.b_c_id = c.c_id');
        }

		foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
		}

        $this->db->order_by('r.r_start_date',$sorting); //Most recent class at top

        if(in_array('ru',$join_objects)){
            $this->db->order_by('total_admissions','DESC'); //Most recent class at top
            $this->db->group_by('r.r_id');
        }


        if($limit>0){
            $this->db->limit($limit);
        }

		$q = $this->db->get();
		
		$runs = $q->result_array();
		foreach($runs as $key=>$class){

            //Now calculate start time and end time for this class:
            $runs[$key]['r__class_start_time'] = strtotime($class['r_start_date']); //Starts at Midnight same date
            $runs[$key]['r__class_end_time'] = $runs[$key]['r__class_start_time'] + (7 * 24 * 3600) - (60); //Ends Sunday 11:59PM

            if(in_array('ru',$join_objects)){
                $runs[$key]['r__current_admissions'] = $class['total_admissions'];
            } else {
                $runs[$key]['r__current_admissions'] = count($this->Db_model->ru_fetch(array(
                    'ru_r_id' => $class['r_id'],
                    'ru_status >=' => 4,
                )));
            }

            $runs[$key]['r__total_tasks'] = 0;
            if(isset($b['c__child_intents']) && count($b['c__child_intents'])>0){
                foreach($b['c__child_intents'] as $intent) {
                    if($intent['c_status']>=1){
                        //Addup the totals:
                        $runs[$key]['r__total_tasks']++;
                    }
                }
            }

		}
		
		return $runs;
	}
	
	
	
	
	/* ******************************
	 * Bootcamps
	 ****************************** */

    function ru_fetch($match_columns,$order_columns=array(
        'ru.ru_cache__completion_rate' => 'DESC',
        'u.u_cache__fp_psid' => 'ASC',
    )){

        $this->db->select('*');
        $this->db->from('v5_class_students ru');
        $this->db->join('v5_classes r', 'r.r_id = ru.ru_r_id','left');
        $this->db->join('v5_entities u', 'u.u_id = ru.ru_u_id');

        foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
        }

        //Order by completion rate:
        if(count($order_columns)>0){
            foreach($order_columns as $key=>$value){
                $this->db->order_by($key,$value);
            }
        }

        $q = $this->db->get();
        return $q->result_array();
    }


    function fetch_c_tree($c_id){
        //This function would fetch all the child c_id's for the input c_id
        $c_tree = array(intval($c_id));
        $child_intents = $this->Db_model->cr_outbound_fetch(array(
            'cr.cr_inbound_c_id' => $c_id,
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
	        $intents[0]['c__messages'] = array();
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
	                'cr.cr_inbound_c_id' => $value['c_id'],
	                'cr.cr_status >' => 0,
	            ) , $join_objects );
	            
	            //need more depth?
	            if(count($intents[$key]['c__child_intents'])>0 && $outbound_levels>=2){
	                //Start the second level:
	                foreach($intents[$key]['c__child_intents'] as $key2=>$value2){
	                    $intents[$key]['c__child_intents'][$key2]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
	                        'cr.cr_inbound_c_id' => $value2['c_id'],
	                        'cr.cr_status >' => 0,
	                    ) , $join_objects );
	                }
	            }
	        } 
	    }
	    
	    //Return everything that was collected:
	    return $intents;
	}

	function b_fetch($match_columns,$join_objects=array(),$order_by='b_id'){
	    //Missing anything?
	    $this->db->select('*');
        $this->db->from('v5_bootcamps b');
        if(in_array('c',$join_objects)){
            $this->db->join('v5_intents c', 'c.c_id = b.b_c_id');
        }
        if(in_array('fp',$join_objects)){
            $this->db->join('v5_facebook_pages fp', 'fp.fp_id = b.b_fp_id','left');
        }
        if(in_array('ba',$join_objects)){
            $this->db->join('v5_bootcamp_team ba', 'ba.ba_b_id = b.b_id','left');
            $this->db->join('v5_entities u', 'u.u_id = ba.ba_u_id','left');
            $this->db->where('ba_status',3); //Lead instructor
        }
        foreach($match_columns as $key=>$value){
	        $this->db->where($key,$value);
	    }
	    $this->db->order_by($order_by,'DESC');
	    $q = $this->db->get();
        return $q->result_array();
	}

	
	function cr_outbound_fetch($match_columns,$join_objects=array()){
		//Missing anything?
		$this->db->select('*');
		$this->db->from('v5_intents c');
		$this->db->join('v5_intent_links cr', 'cr.cr_outbound_c_id = c.c_id');
        if(in_array('ru',$join_objects)){
            $this->db->join('v5_class_students ru', 'ru.ru_b_id = cr.cr_outbound_b_id');
            $this->db->where('cr_outbound_b_id >',0);
        }
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->order_by('cr.cr_outbound_rank','ASC');
		$q = $this->db->get();
		$return = $q->result_array();
		
		//We had anything?
		if(count($return)>0){

            foreach($return as $key=>$value){

                if(in_array('i',$join_objects)){
                    //Fetch Messages:
                    $return[$key]['c__messages'] = $this->Db_model->i_fetch(array(
                        'i_c_id' => $value['c_id'],
                        'i_status >' => 0, //Published in any form
                    ));
                }

                //Is this a Bootcamp link?
                if($value['cr_outbound_b_id']>0){
                    $bs = $this->Db_model->b_fetch(array(
                        'b_id' => $value['cr_outbound_b_id'],
                    ));
                    $return[$key] = array_merge($return[$key] , $bs[0]);
                }
            }
		}
		
		//Return the package:
		return $return;
	}
	
	function cr_inbound_fetch($match_columns,$join_objects=array()){
		//Missing anything?
		$this->db->select('*');
		$this->db->from('v5_intents c');
		$this->db->join('v5_intent_links cr', 'cr.cr_inbound_c_id = c.c_id');
        if(in_array('b',$join_objects)){
            $this->db->join('v5_bootcamps b', 'b.b_c_id = cr.cr_inbound_c_id');
        }
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
            $this->db->join('v5_intents c', 'cr.cr_outbound_c_id = c.c_id');
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
		if(!isset($insert_columns['cr_outbound_c_id'])){
			return false;
		} elseif(!isset($insert_columns['cr_inbound_u_id'])){
			return false;
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
	    $this->db->update('v5_leads', $update_columns);
	    return $this->db->affected_rows();
	}
	function il_create($insert_columns){
	    $this->db->insert('v5_leads', $insert_columns);
	    $insert_columns['il_id'] = $this->db->insert_id();    
	    return $insert_columns;
	}	
	
	function r_create($insert_columns){
	    $this->db->insert('v5_classes', $insert_columns);
	    $insert_columns['r_id'] = $this->db->insert_id();

        if(!$insert_columns['r_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error r_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
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
	    
	    //Lets now add:
	    $this->db->insert('v5_bootcamps', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['b_id'] = $this->db->insert_id();

        if(!$insert_columns['b_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error b_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
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
		
		//Lets now add:
		$this->db->insert('v5_intents', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['c_id'] = ( isset($insert_columns['c_id']) ? $insert_columns['c_id'] : $this->db->insert_id() );

        if(!$insert_columns['c_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error c_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
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
	    
	    //Lets now add:
	    $this->db->insert('v5_bootcamp_team', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['ba_id'] = $this->db->insert_id();

        if(!$insert_columns['ba_id']){
            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error ba_create() : '.$this->db->_error_message(),
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
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
        $bs = $this->Db_model->remix_bs(array(
            'b.b_id' => $b_id,
        ));

        if(count($bs)<1){
            return false;
        }

        //Remove unnecessary fields:
        unset($bs[0]['b__admins']);

        //Also Update Class end Date:
        //Fetch Class Details:
        $classes = $this->Db_model->r_fetch(array(
            'r_id' => $r_id,
        ), $bs[0] );

        if(count($classes)<1){
            return false;
        }

        //Save Action Plan Copy:
        $this->Db_model->e_create(array(
            'e_json' => $bs[0],
            'e_inbound_c_id' => 70, //Action Plan Snapshot
            'e_b_id' => $bs[0]['b_id'],
            'e_outbound_u_id' => $bs[0]['b_c_id'],
            'e_r_id' => $r_id,
        ));

        return true;

    }
	
	function e_fetch($match_columns=array(),$limit=100,$join_objects=array(),$replace_key=null){
	    $this->db->select('*');
	    $this->db->from('v5_engagements e');
        $this->db->join('v5_intents c', 'c.c_id=e.e_inbound_c_id');
	    $this->db->join('v5_entities u', 'u.u_id=e.e_inbound_u_id','left');
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
	    $res = $q->result_array();

	    //Do we need to replace the array key?
	    if($replace_key && count($res)>0 && isset($res[0][$replace_key])){
	        //We need to replace the array key with a specific field for faster data accessing later on using array_key_exists()
            foreach($res as $key=>$val){
                unset($res[$key]);
                if(!isset($res[$val[$replace_key]])){
                    $res[$val[$replace_key]] = $val;
                } else {
                    //This should not happen, log this error:
                    $this->Db_model->e_create(array(
                        'e_text_value' => 'e_fetch() was asked to replace array key with ['.$replace_key.'] and found a duplicate key value ['.$val[$replace_key].']',
                        'e_json' => $val,
                        'e_inbound_c_id' => 8, //Platform Error
                    ));
                }
            }
        }

        //Return results:
        return $res;
	}
	
	
	
	function e_create($link_data){
	    
	    //Sort out the optional fields first:
	    if(!isset($link_data['e_inbound_u_id'])){
	        //Try to fetch user ID from session:
	        $user_data = $this->session->userdata('user');
	        if(isset($user_data['u_id']) && intval($user_data['u_id'])>0){
	            $link_data['e_inbound_u_id'] = $user_data['u_id'];
	        } else {
	            //Have no user:
	            $link_data['e_inbound_u_id'] = 0;
	        }
	    }
		
	    
		//Now check required fields:
		if(!isset($link_data['e_inbound_c_id']) || intval($link_data['e_inbound_c_id'])<=0){
		    //Log this error:
		    $this->Db_model->e_create(array(
                'e_inbound_u_id' => $link_data['e_inbound_u_id'],
                'e_text_value' => 'e_create() Function missing [e_inbound_c_id] variable.',
                'e_json' => $link_data,
                'e_inbound_c_id' => 8, //Platform Error
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

            //Email: The [33] Engagement ID corresponding to Step completion is a email system for instructors to give them more context on certain activities

            //Do we have any instructor subscription:
            if(isset($link_data['e_b_id']) && $link_data['e_b_id']>0 && in_array($link_data['e_inbound_c_id'],$instructor_subscriptions)){

                //Just do this one:
                if(!isset($engagements[0])){
                    //Fetch Engagement Data:
                    $engagements = $this->Db_model->e_fetch(array(
                        'e_id' => $link_data['e_id']
                    ));
                }

                //Did we find it? We should have:
                if(isset($engagements[0])){

                    //Fetch all Goal Instructors and Notify them:
                    $b_instructors = $this->Db_model->ba_fetch(array(
                        'ba.ba_b_id' => $link_data['e_b_id'],
                        'ba.ba_status >=' => 2, //co-instructors & lead instructor
                        'u.u_status >=' => 1, //Must be a user level 1 or higher
                    ));

                    $subject = '⚠️ Notification: '.trim(strip_tags($engagements[0]['c_objective'])).' by '.( isset($engagements[0]['u_fname']) ? $engagements[0]['u_fname'].' '.$engagements[0]['u_lname'] : 'System' );
                    $url = 'https://mench.com/console/'.$link_data['e_b_id'];

                    $body = trim(strip_tags($link_data['e_text_value']));

                    //Send notifications to current instructor
                    foreach($b_instructors as $bi){
                        if(in_array($link_data['e_inbound_c_id'],$instructor_subscriptions)){

                            //Mench notifications:
                            $this->Comm_model->send_message(array(
                                array(
                                    'i_media_type' => 'text',
                                    'i_message' => $subject."\n\n".$body."\n\n".$url,
                                    'i_url' => $url,
                                    'e_inbound_u_id' => 0, //System
                                    'e_outbound_u_id' => $bi['u_id'],
                                    'e_b_id' => $link_data['e_b_id'],
                                    'e_r_id' => ( isset($link_data['e_r_id']) ? $link_data['e_r_id'] : 0 ),
                                ),
                            ));

                        }
                    }
                }
            }

            //Individual subscriptions:
            foreach($engagement_subscriptions as $subscription){

                if(in_array($link_data['e_inbound_c_id'],$subscription['subscription']) || in_array(0,$subscription['subscription'])){

                    //Just do this one:
                    if(!isset($engagements[0])){
                        //Fetch Engagement Data:
                        $engagements = $this->Db_model->e_fetch(array(
                            'e_id' => $link_data['e_id']
                        ));
                    }

                    //Did we find it? We should have:
                    if(isset($engagements[0])){

                        $subject = 'Notification: '.trim(strip_tags($engagements[0]['c_objective'])).' - '.( isset($engagements[0]['u_fname']) ? $engagements[0]['u_fname'].' '.$engagements[0]['u_lname'] : 'System' );

                        //Compose email:
                        $html_message = null; //Start
                        $html_message .= '<div>Hi Mench Admin,</div><br />';

                        //Lets go through all references to see what is there:
                        foreach($engagement_references as $engagement_field=>$er){
                            if(intval($engagements[0][$engagement_field])>0){
                                //Yes we have a value here:
                                $html_message .= '<div>'.$er['name'].': '.object_link($er['object_code'], $engagements[0][$engagement_field], $engagements[0]['e_b_id']).'</div>';
                            }
                        }

                        if(strlen($engagements[0]['e_text_value'])>0){
                            $html_message .= '<div>Message:<br />'.format_e_text_value($engagements[0]['e_text_value']).'</div>';
                        }
                        $html_message .= '<br />';
                        $html_message .= '<div>Cheers,</div>';
                        $html_message .= '<div>Mench Engagement Watcher</div>';
                        $html_message .= '<div style="font-size:0.8em;">Engagement <a href="https://mench.com/api_v1/ej_list/'.$engagements[0]['e_id'].'">#'.$engagements[0]['e_id'].'</a></div>';

                        //Send email:
                        $this->Comm_model->send_email($subscription['admin_emails'], $subject, $html_message);
                    }
                }
            }

        } else {

            //Log this query Error
            $this->Db_model->e_create(array(
                'e_text_value' => 'Query Error e_create() : '.$this->db->_error_message(),
                'e_json' => $link_data,
                'e_inbound_c_id' => 8, //Platform Error
            ));

            return false;
        }
		
		//Return:
		return $link_data;
	}



    function algolia_sync($obj,$obj_id=0){

	    //Define the support objects indexed on algolia:
        $website = $this->config->item('website');
        $obj_id = intval($obj_id);

        $algolia_indexes = array(
            'c' => 'alg_intents',
            'b' => 'alg_bootcamps',
            'u' => 'alg_users',
        );
        $algolia_local_tables = array(
            'c' => 'v5_intents',
            'b' => 'v5_bootcamps',
            'u' => 'v5_entities',
        );

	    if(!array_key_exists($obj,$algolia_indexes)){
            return array(
                'status' => 0,
                'message' => 'Invalid object ['.$obj.']',
            );
        }

        boost_power();







        if(is_dev()){
            //Do a call on live:
            return json_decode(curl_html($website['url']."cron/algolia_sync/".$obj."/".$obj_id));
        }

        //Include PHP library:
        require_once('application/libraries/algoliasearch.php');
        $client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
        $index = $client->initIndex($algolia_indexes[$obj]);


        if(!$obj_id){
            //Clear this index before re-creating it from scratch:
            $index->clearIndex();

            //Reset the local algolia IDs for this:
            $this->db->query("UPDATE ".$algolia_local_tables[$obj]." SET ".$obj."_algolia_id=0 WHERE ".$obj."_algolia_id>0");
        }

        //Prepare universal query limits:
        if($obj_id){
            $limits[$obj.'_id'] = $obj_id;
        } else {
            $limits[$obj.'_status >='] = 0; //None deleted items (we assume this means the same thing for all objects)
        }

        //Fetch item(s) for updates:
        if($obj=='b'){
            $items = $this->Db_model->b_fetch($limits,array('c','ba'));
        } elseif($obj=='c'){
            $items = $this->Db_model->c_fetch($limits);
        } elseif($obj=='u'){
            $items = $this->Db_model->u_fetch($limits);
        }

        //Go through selection and update:
        if(count($items)==0) {
            return array(
                'status' => 0,
                'message' => 'No items found for [' . $obj . '] with id [' . $obj_id . ']',
            );
        }

        $return_items = array();
        foreach($items as $item){

            unset($new_item);
            $new_item = array();

            //Is this already indexed?
            if($item[$obj.'_algolia_id']>0){
                $new_item['objectID'] = $item[$obj.'_algolia_id'];
            }

            if($obj=='b'){

                //Object specific:
                $new_item['b_id'] = intval($item['b_id']); //rquired for all objects
                $new_item['b_status'] = intval($item['b_status']);
                $new_item['b_is_parent'] = intval($item['b_is_parent']);
                $new_item['b_old_format'] = intval($item['b_old_format']);

                //Standard algolia terms:
                $new_item['alg_name'] = $item['c_objective'];
                $new_item['alg_url'] = '/'.$item['b_url_key'];
                $new_item['alg_owner_id'] = intval($item['u_id']);
                $new_item['alg_keywords'] = '';

                if(strlen($item['b_prerequisites'])>0){
                    $new_item['alg_keywords'] .= join(' ',json_decode($item['b_prerequisites'])).' ';
                }

                if(strlen($item['b_transformations'])>0){
                    $new_item['alg_keywords'] .= join(' ',json_decode($item['b_transformations'])).' ';
                }

                //Fetch all text messages for description:
                $i_messages = $this->Db_model->i_fetch(array(
                    'i_c_id' => $item['b_c_id'],
                    'i_media_type' => 'text', //Only type that we can index in search
                    'i_status >' => 0, //Published in any form
                ));
                if(count($i_messages)>0){
                    foreach($i_messages as $i){
                        $new_item['alg_keywords'] .= $i['i_message'].' ';
                    }
                }

                //Fetch all child intent titles:
                $c_intents = $this->Db_model->cr_outbound_fetch(array(
                    'cr.cr_inbound_c_id' => $item['b_c_id'],
                    'cr.cr_status >=' => 0,
                    'c.c_status >=' => 0,
                ));
                if(count($c_intents)>0){
                    foreach($c_intents as $c){
                        $new_item['alg_keywords'] .= $c['c_objective'].' ';
                    }
                }

            } elseif($obj=='c'){



            } elseif($obj=='u') {



            }

            //Clean the keywords:
            $new_item['alg_keywords'] = trim(strip_tags($new_item['alg_keywords']));

            //Add to main array
            array_push( $return_items , $new_item);

        }



        //Now let's see what to do:
        if($obj_id){

            //We should have fetched a single item only, meaning $items[0] is what we care about...

            if($items[0][$obj.'_status']>=0){

                if(intval($items[0][$obj.'_algolia_id'])>0){

                    //Update existing index:
                    $obj_add_message = $index->saveObjects($return_items);

                } else {

                    //Create new index:
                    $obj_add_message = $index->addObjects($return_items);

                    //Now update local database with the objectIDs:
                    if(isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs'])>0){
                        foreach($obj_add_message['objectIDs'] as $key=>$algolia_id){
                            $this->db->query("UPDATE ".$algolia_local_tables[$obj]." SET ".$obj."_algolia_id=".$algolia_id." WHERE ".$obj."_id=".$return_items[$key][$obj.'_id']);
                        }
                    }

                }

            } elseif(intval($items[0][$obj.'_algolia_id'])>0) {

                //item has been deleted locally but its still indexed on Algolia

                //Delete from algolia:
                $index->deleteObject($items[0][$obj.'_algolia_id']);

                //also set its algolia_id to 0 locally:
                $this->db->query("UPDATE ".$algolia_local_tables[$obj]." SET ".$obj."_algolia_id=0 WHERE ".$obj."_id=".$obj_id);

                return array(
                    'status' => 1,
                    'message' => 'item deleted',
                );

            }

        } else {

            //Mass update request
            //All remote items have been deleted from algolia index and local algolia_ids have been set to zero
            //we're ready to create new items and update local:
            $obj_add_message = $index->addObjects($return_items);

            //Now update database with the objectIDs:
            if(isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs'])>0){
                foreach($obj_add_message['objectIDs'] as $key=>$algolia_id){

                    $this->db->query("UPDATE ".$algolia_local_tables[$obj]." SET ".$obj."_algolia_id=".$algolia_id." WHERE ".$obj."_id=".$return_items[$key][$obj.'_id']);

                }
            }

        }

        //Return results:
        return array(
            'status' => ( count($obj_add_message['objectIDs'])>0 ? 1 : 0 ),
            'message' => count($obj_add_message['objectIDs']).' items updated',
        );

    }

}
