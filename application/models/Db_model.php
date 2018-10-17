<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model {
	
	//This model handles all DB calls from our local database.
	
	function __construct() {
		parent::__construct();
	}


	function remix_enrollments($matching_criteria,$order_columns=array('ru.ru_id' => 'DESC')){

	    $enrollments = $this->Db_model->ru_fetch($matching_criteria,$order_columns);

	    //Fetch more data for each enrollment:
	    foreach($enrollments as $key=>$enrollment){


	    }

	    return $enrollments;
	}


    function w_fetch($match_columns){
        //Fetch the target gems:
        $this->db->select('*');
        $this->db->from('v5_subscriptions w');
        foreach($match_columns as $key=>$value){
            $this->db->where($key,$value);
        }
        $q = $this->db->get();
        return $q->result_array();
    }

    function w_update($id,$update_columns){
        //Update first
        $this->db->where('w_id', $id);
        $this->db->update('v5_subscriptions', $update_columns);
        return $this->db->affected_rows();
    }

    function k_update($id,$update_columns){
        //Update first
        $this->db->where('k_id', $id);
        $this->db->update('v5_subscription_intents', $update_columns);
        return $this->db->affected_rows();
    }

    function k_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('k_w_id','k_cr_id','k_time_estimate'))){
            return false;
        }

        if(!isset($insert_columns['k_timestamp'])){
            $insert_columns['k_timestamp'] = date("Y-m-d H:i:s");
        }

        //Lets now add:
        $this->db->insert('v5_subscription_intents', $insert_columns);

        //Fetch inserted id:
        $insert_columns['k_id'] = $this->db->insert_id();

        return $insert_columns;
    }

    function c_new($c_id, $c_outcome, $link_c_id, $next_level, $inbound_u_id){

	    if(intval($c_id)<=0){
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            );
        } elseif(strlen($c_outcome)<=0){
            return array(
                'status' => 0,
                'message' => 'Missing Intent Outcome',
            );
        }

        $link_c_id = intval($link_c_id);

        //Validate Original intent:
        $inbound_intents = $this->Db_model->c_fetch(array(
            'c.c_id' => intval($c_id),
        ), 1);
        if(count($inbound_intents)<=0){
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            );
        }

        if(!$link_c_id){

            //Set default new hours:
            $default_new_hours = 0.05; //3 min default
            $recursive_query = array(
                'c__tree_outputs' => 1,
                'c__tree_max_hours' => $default_new_hours,
            );

            //Create intent:
            $new_c = $this->Db_model->c_create(array(
                'c_inbound_u_id' => $inbound_u_id,
                'c_outcome' => trim($c_outcome),
                'c_time_estimate' => $default_new_hours,
                'c__tree_outputs' => 1, //Default
                'c__tree_inputs' => 0,
                'c__tree_max_hours' => $default_new_hours,
            ));

            //Log Engagement for New Intent:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $inbound_u_id,
                'e_text_value' => 'Intent ['.$new_c['c_outcome'].'] created',
                'e_json' => array(
                    'input' => $_POST,
                    'before' => null,
                    'after' => $new_c,
                ),
                'e_inbound_c_id' => 20, //New Intent
                'e_outbound_c_id' => $new_c['c_id'],
            ));

        } else {

            $new_cs = $this->Db_model->c_fetch(array(
                'c_id' => $link_c_id,
                'c.c_status >' => 0,
            ), ( 3 - $next_level ));
            if(count($new_cs)<=0){
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Intent ID',
                );
            }
            $new_c = $new_cs[0];


            //Make sure none of the parents are the same:
            if($new_c['c_id']==$c_id){
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "'.$new_c['c_outcome'].'" as its own child.',
                );
            } else {
                //check for all parents:
                $parent_tree = $this->Db_model->c_recursive_fetch($c_id);
                if(in_array($new_c['c_id'],$parent_tree['c_flat'])){
                    return array(
                        'status' => 0,
                        'message' => 'You cannot add "'.$new_c['c_outcome'].'" as its own grandchild.',
                    );
                }
            }

            //Make sure this is not a duplicate level 2 intent:
            if($next_level==2){
                foreach($inbound_intents[0]['c__child_intents'] as $current_c){
                    if($current_c['c_id']==$link_c_id){
                        //Ooops, this is already added in Level 2, cannot add again:
                        return array(
                            'status' => 0,
                            'message' => '['.$new_c['c_outcome'].'] is already added as outbound intent.',
                        );
                    }
                }
            }

            //Remove orphan status if that was the case before:
            if(intval($new_c['c__is_orphan'])){
                $this->Db_model->c_update( $new_c['c_id'] , array(
                    'c__is_orphan' => 0,
                ));
            }

            //Prepare recursive update:
            $recursive_query = array(
                'c__tree_outputs' => $new_c['c__tree_outputs'],
                'c__tree_inputs' => $new_c['c__tree_inputs'],
                'c__tree_max_hours' => number_format($new_c['c__tree_max_hours'],3),
                'c__tree_messages' => $new_c['c__tree_messages'],
            );
        }


        //Create Link:
        $relation = $this->Db_model->cr_create(array(
            'cr_inbound_u_id' => $inbound_u_id,
            'cr_inbound_c_id'  => intval($c_id),
            'cr_outbound_c_id' => $new_c['c_id'],
            'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
                    'cr_status >=' => 1,
                    'c_status >' => 0,
                    'cr_inbound_c_id' => intval($c_id),
                )),
        ));

        //Update tree count from parent and above:
        $updated_recursively = $this->Db_model->c_update_tree($c_id, $recursive_query);

        //Log Engagement for new link:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $inbound_u_id,
            'e_text_value' => 'Linked intent ['.$new_c['c_outcome'].'] as outbound of intent ['.$inbound_intents[0]['c_outcome'].']',
            'e_json' => array(
                'input' => $_POST,
                'before' => null,
                'after' => $relation,
                'recursive_query' => $recursive_query,
                'updated_recursively' => $updated_recursively,
            ),
            'e_inbound_c_id' => 23, //New Intent Link
            'e_cr_id' => $relation['cr_id'],
        ));

        $relations = $this->Db_model->cr_outbound_fetch(array(
            'cr.cr_id' => $relation['cr_id'],
        ));

        //Return result:
        return array(
            'status' => 1,
            'c_id' => $new_c['c_id'],
            'c__tree_max_hours' => $new_c['c__tree_max_hours'],
            'adjusted_c_count' => ( $new_c['c__tree_outputs'] + $new_c['c__tree_inputs'] ),
            'html' => echo_c(array_merge($new_c,$relations[0]),$next_level,intval($c_id)),
        );
    }

    function w_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('outbound_u_id','w_c_id'))){
            return false;
        }

        if(!isset($insert_columns['w_timestamp'])){
            $insert_columns['w_timestamp'] = date("Y-m-d H:i:s");
        }
        if(!isset($insert_columns['w_status'])){
            $insert_columns['w_status'] = 1;
        }
        if(!isset($insert_columns['w_inbound_u_id'])){
            $insert_columns['w_inbound_u_id'] = 0; //No coach
        }
        if(!isset($insert_columns['w_start_time'])){
            $insert_columns['w_start_time'] = null;
        }
        if(!isset($insert_columns['w_end_goal'])){
            $insert_columns['w_end_goal'] = null;
        }
        if(!isset($insert_columns['w_weekly_commitment'])){
            $insert_columns['w_weekly_commitment'] = null;
        }
        if(!isset($insert_columns['w_notification_type'])){
            $insert_columns['w_notification_type'] = 1; //Regular
        }

        //Lets now add:
        $this->db->insert('v5_subscriptions', $insert_columns);

        //Fetch inserted id:
        $insert_columns['w_id'] = $this->db->insert_id();

        if($insert_columns['w_id']>0){

            //Now let's create a cache of the Action Plan for this subscription:
            $tree = $this->Db_model->c_recursive_fetch($insert_columns['w_c_id'], true, 0, 0, null, $insert_columns['w_id'] /* Triggers intent caching for this subscription */ );

            if(count($tree['cr_flat'])>0){

                $intent = end($tree['tree_top']);

                //All good with the subscription intent caching, inform user:
                $this->Comm_model->send_message(array(
                    array(
                        'e_inbound_u_id' => 2738, //Initiated by PA
                        'e_outbound_u_id' => $insert_columns['outbound_u_id'],
                        'e_outbound_c_id' => $insert_columns['w_c_id'],
                        'i_message' => 'I have successfully subscribed you to ['.$intent['c_outcome'].']',
                    ),
                ));

                //Update total hours:
                $this->Comm_model->w_update( $insert_columns['w_id'], array(
                    'w_time_estimate' => $tree['c1__tree_max_hours'],
                ));

            } else {

                //This should not happen, inform user and log error:
                $this->Comm_model->send_message(array(
                    array(
                        'e_inbound_u_id' => 2738, //Initiated by PA
                        'e_outbound_u_id' => $insert_columns['outbound_u_id'],
                        'e_outbound_c_id' => $insert_columns['w_c_id'],
                        'i_message' => 'Subscription failed',
                    ),
                ));

            }

            return $insert_columns;

        } else {
            return false;
        }
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
	
	function fetch_il_overview(){
	    //Fetches an overview of Udemy Community
	    $this->db->select('COUNT(il_id) as total_coaches, SUM(il_course_count) as total_courses, SUM(il_student_count) as total_students, SUM(il_review_count) as total_reviews, il_udemy_category');
	    $this->db->from('v5_leads il');
	    $this->db->where('il_udemy_user_id>0');
	    $this->db->where('il_student_count>0'); //Need for Engagement Rate
	    $this->db->group_by('il_udemy_category');
	    $this->db->order_by('total_coaches DESC');
	    $q = $this->db->get();
	    return $q->result_array();
	}

	
	/* ******************************
	 * Users
	 ****************************** */
	
	function u_fetch($match_columns, $join_objects=array(), $limit_row=0, $limit_offset=0, $order_columns=array(
        'u__e_score' => 'DESC',
    )){
	    //Fetch the target entities:
	    $this->db->select('*');
	    $this->db->from('v5_entities u');
	    $this->db->join('v5_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0
	    foreach($match_columns as $key=>$value){
	        if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
	    }

        if($limit_row>0){
            $this->db->limit($limit_row,$limit_offset);
        }
        foreach($order_columns as $key=>$value){
            $this->db->order_by($key,$value);
        }

	    $q = $this->db->get();
	    $res = $q->result_array();


	    //Now fetch inbounds:
        foreach($res as $key=>$val){

            if(in_array('u__outbound_count',$join_objects)){
                //Fetch the messages for this entity:
                $res[$key]['u__outbound_count'] = count($this->Db_model->ur_outbound_fetch(array(
                    'ur_inbound_u_id' => $val['u_id'],
                    'ur_status >=' => 0, //Pending or Active
                    'u_status >=' => 0, //Pending or Active
                )));
            }


            if(in_array('u__urls',$join_objects)){
                //Fetch the messages for this entity:
                $res[$key]['u__urls'] = $this->Db_model->x_fetch(array(
                    'x_status >' => 0,
                    'x_outbound_u_id' => $val['u_id'],
                ), array(), array(
                    'x_type' => 'ASC'
                ));
            }

            if(in_array('u__subscriptions',$join_objects)){
                //Fetch the messages for this entity:
                $res[$key]['u__subscriptions'] = $this->Db_model->w_fetch(array(
                    'w_outbound_u_id' => $val['u_id'],
                    'w_status' => 1, //Active subscriptions
                ));
            }


            //Fetch the messages for this entity:
            $res[$key]['u__inbounds'] = array();
            if(!in_array('skip_u__inbounds',$join_objects)){
                $inbounds = $this->Db_model->ur_inbound_fetch(array(
                    'ur_outbound_u_id' => $val['u_id'],
                    'ur_status >=' => 0, //Pending or Active
                    'u_status >=' => 0, //Pending or Active
                ));
                foreach($inbounds as $ur){
                    $res[$key]['u__inbounds'][$ur['u_id']] = $ur;
                }
            }
        }

        return $res;
	}
	
	function ru_update($id,$update_columns){
	    //Update first
	    $this->db->where('ru_id', $id);
	    $this->db->update('v5_class_students', $update_columns);
	    return $this->db->affected_rows();
	}
	
	function ru_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('ru_b_id','ru_outbound_u_id','ru_status'))){
            return false;
        }

        if(!isset($insert_columns['ru_timestamp'])){
            $insert_columns['ru_timestamp'] = date("Y-m-d H:i:s");
        }
        if(!isset($insert_columns['ru_r_id'])){
            $insert_columns['ru_r_id'] = 0;
        }
        if(!isset($insert_columns['ru_assessment_result'])){
            $insert_columns['ru_assessment_result'] = -1;
        }
	    
	    //Lets now add:
	    $this->db->insert('v5_class_students', $insert_columns);
	    
	    //Fetch inserted id:
	    $insert_columns['ru_id'] = $this->db->insert_id();
	    
	    return $insert_columns;
	}
	

	function t_create($insert_columns){

	    //TODO Add checks and protection
        if(!isset($insert_columns['t_timestamp'])){
            $insert_columns['t_timestamp'] = date("Y-m-d H:i:s");
        }
	    
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

    function c_hard_delete($c_id){

        if(intval($c_id)<0){
            return array(
                'status' => 0,
                'message' => 'Missing input ID',
            );
        }

        //Validate user exists:
        $intents = $this->Db_model->c_fetch(array(
            'c_id' => $c_id,
        ));

        if(!(count($intents)==1)){
            return array(
                'status' => 0,
                'message' => 'Intent Not Found in DB',
            );
        }

        //Check transactions:
        $subscriptions = $this->Db_model->w_fetch(array(
            'w_c_id' => $c_id,
            'w_status >=' => 0,
        ));
        if(count($subscriptions)>0){
            return array(
                'status' => 0,
                'message' => 'Cannot delete because there are '.count($subscriptions).' active subscriptions',
                'subscriptions' => $subscriptions,
                'c' => $intents[0],
            );
        }

        $delete_stats = array();

        //Start removal process by deleting engagements:
        $this->db->query("DELETE FROM v5_engagements WHERE e_inbound_c_id=".$c_id." OR e_outbound_c_id=".$c_id);
        $delete_stats['v5_engagements'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM v5_messages WHERE i_outbound_c_id=".$c_id);
        $delete_stats['v5_messages'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM v5_subscriptions WHERE w_c_id=".$c_id);
        $delete_stats['v5_subscriptions'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM v5_intents WHERE c_id=".$c_id);
        $delete_stats['v5_intents'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM v5_intent_links WHERE (cr_inbound_c_id=".$c_id." OR cr_outbound_c_id=".$c_id.")");
        $delete_stats['v5_intent_links'] = $this->db->affected_rows();

        return array(
            'status' => 1,
            'stats' => $delete_stats,
            'c' => $intents[0],
        );

    }

    function u_hard_delete($u_id){

        if(intval($u_id)<0){
            return array(
                'status' => 0,
                'message' => 'Missing input $u_id',
            );
        }

        //Validate user exists:
        $users = $this->Db_model->u_fetch(array(
            'u_id' => $u_id,
        ));

        if(!(count($users)==1)){
            return array(
                'status' => 0,
                'message' => 'User Not Found in DB',
            );
        } elseif(array_key_exists(1281, $users[0]['u__inbounds']) ){
            return array(
                'status' => 0,
                'message' => 'Cannot delete Admin',
                'user' => $users[0],
            );
        }

        //Check transactions:
        $transactions = $this->Db_model->t_fetch(array(
            't_inbound_u_id' => $u_id,
        ));
        if(count($transactions)>0){
            return array(
                'status' => 0,
                'message' => 'Cannot delete because user has transactions',
                'user' => $users[0],
            );
        }


        //Check subscriptions:
        $subscriptions = $this->Db_model->w_fetch(array(
            '(w_inbound_u_id='.$u_id.' OR w_outbound_u_id='.$u_id.')' => null,
            'w_status >=' => 0,
        ));
        if(count($subscriptions)>0){
            return array(
                'status' => 0,
                'message' => 'Cannot delete because there are '.count($subscriptions).' active subscriptions',
                'subscriptions' => $subscriptions,
                'u' => $users[0],
            );
        }

        $delete_stats = array();

        //Start removal process by deleting engagements:
        $this->db->query("DELETE FROM v5_engagements WHERE e_inbound_u_id=".$u_id." OR e_outbound_u_id=".$u_id);
        $delete_stats['v5_engagements'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM v5_messages WHERE i_outbound_u_id=".$u_id);
        $delete_stats['v5_messages'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM v5_subscriptions WHERE w_outbound_u_id=".$u_id);
        $delete_stats['v5_subscriptions'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM v5_urls WHERE x_outbound_u_id=".$u_id);
        $delete_stats['v5_urls'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM v5_entities WHERE u_id=".$u_id);
        $delete_stats['v5_entities'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM v5_entity_links WHERE (ur_inbound_u_id=".$u_id." OR ur_outbound_u_id=".$u_id.")");
        $delete_stats['v5_entity_links'] = $this->db->affected_rows();

        return array(
            'status' => 1,
            'stats' => $delete_stats,
            'user' => $users[0],
        );

    }

	function u_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('u_full_name'))){
            return false;
        }

        //Name cannot be longer than this:
        if(strlen($insert_columns['u_full_name'])>250){
            //Trim this:
            $insert_columns['u_full_name'] = substr($insert_columns['u_full_name'],0,247).'...';
        }

        if(!isset($insert_columns['u_timestamp'])){
            $insert_columns['u_timestamp'] = date("Y-m-d H:i:s");
        }

        if(!isset($insert_columns['u_status'])){
            $insert_columns['u_status'] = 1;
        }
		
		//Lets now add:
		$this->db->insert('v5_entities', $insert_columns);

        //Fetch inserted id:
        $insert_columns['u_id'] = $this->db->insert_id();

        if($insert_columns['u_id']>0){

            //Fetch to return full data:
            $users = $this->Db_model->u_fetch(array(
                'u_id' => $insert_columns['u_id'],
            ));

            return $users[0];

        } else {
            return false;
        }
	}
	
	function u_update($id,$update_columns){
	    //Update first
	    $this->db->where('u_id', $id);
	    $this->db->update('v5_entities', $update_columns);

	    //Return new row:
	    $users = $this->u_fetch(array(
	        'u_id' => $id
	    ));

	    //Update Algolia:
        $this->Db_model->algolia_sync('u',$id);

	    return $users[0];
	}



	
	/* ******************************
	 * i Messages
	 ****************************** */

    function i_fetch($match_columns, $limit=0, $join_objects=array(), $order_columns=array(
        'i_rank' => 'ASC',
    )){

        $this->db->select('*');
        $this->db->from('v5_messages i');
        $this->db->join('v5_intents c', 'i.i_outbound_c_id = c.c_id');
        $this->db->join('v5_entities u', 'u.u_id = i.i_inbound_u_id');
        if(in_array('x',$join_objects)){
            $this->db->join('v5_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0
        }
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

        foreach($order_columns as $key=>$value){
            $this->db->order_by($key,$value);
        }

        $this->db->order_by('i_rank');
        $q = $this->db->get();
        return $q->result_array();
    }


	function i_create($insert_columns){

        //Need either entity or intent:
        if(!isset($insert_columns['i_outbound_c_id'])){
            $this->Db_model->e_create(array(
                'e_text_value' => 'A new message requires either an Entity or Intent to be referenced to',
                'e_json' => $insert_columns,
                'e_inbound_c_id' => 8, //Platform Error
            ));
            return false;
        }

        //Other required fields:
        if(missing_required_db_fields($insert_columns,array('i_message','i_inbound_u_id'))){
            return false;
        }

        if(!isset($insert_columns['i_timestamp'])){
            $insert_columns['i_timestamp'] = date("Y-m-d H:i:s");
        }
        if(!isset($insert_columns['i_status'])){
            $insert_columns['i_status'] = 1;
        }
        if(!isset($insert_columns['i_rank'])){
            $insert_columns['i_rank'] = 1;
        }

        if(!isset($insert_columns['i_outbound_u_id'])){
            //Describes an entity:
            $insert_columns['i_outbound_u_id'] = 0;
        }
        if(!isset($insert_columns['i_outbound_c_id'])){
            //Describes an entity:
            $insert_columns['i_outbound_c_id'] = 0;
        }


		//Lets now add:
		$this->db->insert('v5_messages', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['i_id'] = $this->db->insert_id();
		
		return $insert_columns;
	}
	
	function i_update($id,$update_columns){
		$this->db->where('i_id', $id);
		$this->db->update('v5_messages', $update_columns);
		return $this->db->affected_rows();
	}

    function i_replicate($u_id,$c_messages,$c_id){
        //This function strips and copies all $c_messages to $c_id recorded as $u_id
        $newly_created_messages = array();

        foreach($c_messages as $i){

            if($i['i_status']<=0){
                continue; //Only do active messages, should not happen...
            }

            $new_i = array();
            foreach($i as $key=>$value){
                //Is this a message field?
                if(substr($key,0,2)=='i_' && !in_array($key,array('i_id','i_inbound_u_id','i_outbound_c_id','i_timestamp','i_rank'))){
                    //Yes, move over:
                    $new_i[$key] = $value;
                }
            }

            //Replace creator & c_id
            $new_i['i_inbound_u_id'] = $u_id;
            $new_i['i_outbound_c_id'] = $c_id;
            $new_i['i_rank'] = 1 + $this->Db_model->max_value('v5_messages','i_rank', array(
                    'i_status' => $new_i['i_status'],
                    'i_outbound_c_id' => $c_id,
                ));

            //Create:
            $i_create = $this->Db_model->i_create($new_i);

            //Append to total stats:
            array_push($newly_created_messages,$i_create);
        }

        return $newly_created_messages;

    }

    /* ******************************
     * Facebook Pages/Admins
     ****************************** */

    function fp_fetch($match_columns,$join_objects=array(),$order_columns=array()){

        $this->db->select('*');
        $this->db->from('v5_facebook_pages fp');

        if(in_array('fs',$join_objects)){
            $this->db->join('v5_facebook_page_admins fs', 'fs.fs_fp_id = fp.fp_id', 'left');
            if(count($order_columns)==0){
                $order_columns = array('fs_timestamp'=>'DESC');
            }
        }
        if(in_array('u',$join_objects)){
            $this->db->join('v5_entities u', 'u.u_id = fs.fs_inbound_u_id');
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



	
	
	/* ******************************
	 * Bootcamps
	 ****************************** */

    function ru_fetch($match_columns,$order_columns=array(
        'ru.ru_cache__completion_rate' => 'DESC',
        'u.u_cache__fp_psid' => 'ASC',
    ), $join_objects=array()){

        $this->db->select('*');
        $this->db->from('v5_class_students ru');
        $this->db->join('v5_entities u', 'u.u_id = ru.ru_outbound_u_id');
        $this->db->join('v5_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0

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




    function c_fetch($match_columns, $outbound_levels=0, $join_objects=array(), $order_columns=array(), $limit=0){

        //The basic fetcher for intents
        $this->db->select('*');
        $this->db->from('v5_intents c');
        if(in_array('u',$join_objects)){
            $this->db->join('v5_entities u', 'u.u_id = c.c_inbound_u_id');
        }
        foreach($match_columns as $key=>$value){
            $this->db->where($key,$value);
        }
        if(count($order_columns)>0){
            foreach($order_columns as $key=>$value){
                $this->db->order_by($key,$value);
            }
        }
        if($limit>0){
            $this->db->limit($limit);
        }
        $q = $this->db->get();
        $intents = $q->result_array();

        foreach($intents as $key=>$value){

            if(in_array('i',$join_objects)){
                $intents[$key]['c__messages'] = $this->Db_model->i_fetch(array(
                    'i_outbound_c_id' => $value['c_id'],
                    'i_status >=' => 0, //Published in any form
                ));
            }

            if(in_array('c__inbounds',$join_objects)){
                $intents[$key]['c__inbounds'] = $this->Db_model->cr_inbound_fetch(array(
                    'cr.cr_outbound_c_id' => $value['c_id'],
                    'cr.cr_status >=' => 1,
                ) , $join_objects);
            }

            if($outbound_levels>=1){

                //Do the first level:
                $intents[$key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
                    'cr.cr_inbound_c_id' => $value['c_id'],
                    'cr.cr_status >=' => 1,
                    'c.c_status >=' => 1,
                ) , $join_objects );


                //need more depth?
                if($outbound_levels>=2){
                    //Start the second level:
                    foreach($intents[$key]['c__child_intents'] as $key2=>$value2){
                        $intents[$key]['c__child_intents'][$key2]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
                            'cr.cr_inbound_c_id' => $value2['c_id'],
                            'cr.cr_status >=' => 1,
                            'c.c_status >=' => 1,
                        ) , $join_objects );
                    }
                }
            }
        }

        //Return everything that was collected:
        return $intents;
    }

	
	function cr_outbound_fetch($match_columns,$join_objects=array()){
		//Missing anything?
		$this->db->select('*');
		$this->db->from('v5_intents c');
		$this->db->join('v5_intent_links cr', 'cr.cr_outbound_c_id = c.c_id');
		foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
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
                        'i_outbound_c_id' => $value['c_id'],
                        'i_status >=' => 0, //Published in any form
                    ));
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
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
        $return = $q->result_array();

        if(in_array('c__child_intents',$join_objects)){
            foreach($return as $key=>$value){
                //Fetch Messages:
                $return[$key]['c__child_intents'] = $this->Db_model->cr_outbound_fetch(array(
                    'cr.cr_inbound_c_id' => $value['c_id'],
                    'cr.cr_status >=' => 0,
                    'c.c_status >' => 0,
                ));
            }
        }

        return $return;
	}
	
	
	
	function cr_update($id,$update_columns,$column='cr_id'){
		$this->db->where($column, $id);
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

        if(missing_required_db_fields($insert_columns,array('cr_outbound_c_id','cr_inbound_c_id','cr_inbound_u_id'))){
            return false;
        }

        if(!isset($insert_columns['cr_timestamp'])){
            $insert_columns['cr_timestamp'] = date("Y-m-d H:i:s");
        }

        if(!isset($insert_columns['cr_status'])){
            $insert_columns['cr_status'] = 1;
        }
        if(!isset($insert_columns['cr_outbound_rank'])){
            $insert_columns['cr_outbound_rank'] = 1;
        }

        //Lets now add:
        $this->db->insert('v5_intent_links', $insert_columns);

        //Fetch inserted id:
        $insert_columns['cr_id'] = $this->db->insert_id();

        return $insert_columns;
    }


    function ur_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('ur_outbound_u_id','ur_inbound_u_id'))){
            return false;
        }

        if(!isset($insert_columns['ur_timestamp'])){
            $insert_columns['ur_timestamp'] = date("Y-m-d H:i:s");
        }

        if(!isset($insert_columns['ur_status'])){
            $insert_columns['ur_status'] = 1; //Live link
        }

        //Lets now add:
        $this->db->insert('v5_entity_links', $insert_columns);

        //Fetch inserted id:
        $insert_columns['ur_id'] = $this->db->insert_id();

        return $insert_columns;
    }

    function ur_update($id,$update_columns){
        //Update first
        $this->db->where('ur_id', $id);
        $this->db->update('v5_entity_links', $update_columns);
        return $this->db->affected_rows();
    }

    function ur_delete($id){
        //Update status:
        $this->Db_model->ur_update($id, array(
            'ur_status' => -1,
        ));
        return $this->db->affected_rows();
    }


    function x_sync($x_url,$x_outbound_u_id,$cad_edit,$accept_existing_url=false) {

        //Auth user and check required variables:
        $udata = auth(array(1308,1280));
        $x_url = trim($x_url);

        if(!$udata){
            return array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            );
        } elseif(!isset($x_outbound_u_id)){
            return array(
                'status' => 0,
                'message' => 'Missing Outbound Entity ID',
            );
        } elseif(!isset($cad_edit)){
            return array(
                'status' => 0,
                'message' => 'Missing Editing Permission',
            );
        } elseif(!isset($x_url) || strlen($x_url)<1){
            return array(
                'status' => 0,
                'message' => 'Missing URL',
            );
        } elseif(!filter_var($x_url, FILTER_VALIDATE_URL)){
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        }

        //Validate parent entity:
        $outbound_us = $this->Db_model->u_fetch(array(
            'u_id' => $x_outbound_u_id,
        ));

        //Make sure this URL does not exist:
        $dup_urls = $this->Db_model->x_fetch(array(
            'x_status >' => -2,
            '(x_url LIKE \''.$x_url.'\' OR x_clean_url LIKE \''.$x_url.'\')' => null,
        ), array('u'));

        //Call URL to validate it further:
        $curl = curl_html($x_url, true);

        if(!$curl){
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        } elseif(count($dup_urls)>0){

            if($accept_existing_url){
                //Return the object as this is expected:
                return array(
                    'status' => 1,
                    'message' => 'Found existing URL',
                    'is_existing' => 1,
                    'curl' => $curl,
                    'u' => array_merge($outbound_us[0],$dup_urls[0]),
                );
            } elseif($dup_urls[0]['u_id']==$x_outbound_u_id){
                return array(
                    'status' => 0,
                    'message' => 'This URL has already been added!',
                );
            } else {
                return array(
                    'status' => 0,
                    'message' => 'URL is already being used by [' . $dup_urls[0]['u_full_name'] . ']. URLs cannot belong to multiple entities.',
                );
            }
        } elseif($curl['url_is_broken']) {
            return array(
                'status' => 0,
                'message' => 'URL seems broken with http code [' . $curl['httpcode'] . ']',
            );
        } elseif(count($outbound_us)<1) {
            return array(
                'status' => 0,
                'message' => 'Invalid Outbound Entity ID ['.$x_outbound_u_id.']',
            );
        }


        if($x_outbound_u_id==1326){ //Content

            //We need to create a new entity and add this URL below it:
            $x_types = echo_status('x_type', null);
            $u_full_name = null;
            $url_code = substr(md5(( $curl['clean_url'] ? $curl['clean_url'] : $curl['input_url'] )),0,8);

            if(strlen($curl['page_title'])>0){

                //Make sure this is not a duplicate name:
                $dup_name_us = $this->Db_model->u_fetch(array(
                    'u_status >=' => 0,
                    'u_full_name' => $curl['page_title'],
                ));

                if(count($dup_name_us)>0){
                    //Yes, we did find a duplicate name! Change this slightly:
                    $u_full_name = $curl['page_title'].' '.$url_code;
                } else {
                    //No duplicate detected, all good to go:
                    $u_full_name = $curl['page_title'];
                }

            } else {
                $u_full_name = $x_types[$curl['x_type']]['s_name'].' '.$url_code;
            }


            $new_content = $this->Db_model->u_create(array(
                'u_full_name' => $u_full_name,
            ));

            //Log Engagement new entity:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_outbound_u_id' => $new_content['u_id'],
                'e_inbound_c_id' => 6971, //Entity Created
            ));

            //Place this new entity in $x_outbound_u_id [Content]
            $ur1 = $this->Db_model->ur_create(array(
                'ur_outbound_u_id' => $new_content['u_id'],
                'ur_inbound_u_id' => $x_outbound_u_id,
            ));

            //Log Engagement new entity link:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_ur_id' => $ur1['ur_id'],
                'e_inbound_c_id' => 7291, //Entity Link Create
            ));

        } else {
            $new_content = $outbound_us[0];
            $ur1 = array();
        }


        //All good, Save URL:
        $new_x = $this->Db_model->x_create(array(
            'x_inbound_u_id' => $udata['u_id'],
            'x_outbound_u_id' => $new_content['u_id'],
            'x_url' => $x_url,
            'x_http_code' => $curl['httpcode'],
            'x_clean_url' => ($curl['clean_url'] ? $curl['clean_url'] : $x_url),
            'x_type' => $curl['x_type'],
            'x_status' => ( $curl['url_is_broken'] ? 1 : 2 ),
        ));

        if(!isset($new_x['x_id']) || $new_x['x_id']<1){
            return array(
                'status' => 0,
                'message' => 'There was an issue creating the URL',
            );
        }

        //Log Engagements:
        $this->Db_model->e_create(array(
            'e_json' => $curl,
            'e_inbound_c_id' => 6911, //URL Detected Live
            'e_inbound_u_id' => $udata['u_id'],
            'e_outbound_u_id' => $new_content['u_id'],
            'e_x_id' => $new_x['x_id'],
        ));
        $this->Db_model->e_create(array(
            'e_json' => $new_x,
            'e_inbound_c_id' => 6910, //URL Added
            'e_inbound_u_id' => $udata['u_id'],
            'e_outbound_u_id' => $new_content['u_id'],
            'e_x_id' => $new_x['x_id'],
        ));


        //Is this a image for an entity without a cover letter? If so, set this as the default:
        $set_cover_x_id = ( !$outbound_us[0]['u_cover_x_id'] && $new_x['x_type']==4 /* Image file */ ? $new_x['x_id'] : 0 );


        //Update Algolia:
        $this->Db_model->algolia_sync('u',$new_content['u_id']);


        if($x_outbound_u_id==1326){

            //Return entity object:
            return array(
                'status' => 1,
                'message' => 'Success',
                'curl' => $curl,
                'u' => array_merge($new_content,$ur1),
                'set_cover_x_id' => $set_cover_x_id,
                'new_u' => ( $accept_existing_url ? null : echo_u(array_merge($new_content,$ur1), 2, $cad_edit) ),
            );

        } else {

            //Return URL object:
            return array(
                'status' => 1,
                'message' => 'Success',
                'curl' => $curl,
                'u' => $outbound_us[0],
                'set_cover_x_id' => $set_cover_x_id,
                'new_x' => echo_x($outbound_us[0], $new_x),
            );

        }
    }




    function ur_outbound_fetch($match_columns, $join_objects=array(), $limit=0, $limit_offset=0, $select='*', $group_by=null, $order_columns=array(
        'u.u__e_score' => 'DESC',
    )){

        //Missing anything?
        $this->db->select($select);
        $this->db->from('v5_entities u');
        $this->db->join('v5_entity_links ur', 'ur.ur_outbound_u_id = u.u_id');
        $this->db->join('v5_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0
        foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
        }

        if($group_by){
            $this->db->group_by($group_by);
        }
        foreach($order_columns as $key=>$value){
            $this->db->order_by($key,$value);
        }

        if($limit>0){
            $this->db->limit($limit,$limit_offset);
        }

        $q = $this->db->get();
        $res = $q->result_array();


        if(in_array('u__outbound_count',$join_objects)){
            foreach($res as $key=>$val){
                //Fetch the messages for this entity:
                $res[$key]['u__outbound_count'] = count($this->Db_model->ur_outbound_fetch(array(
                    'ur_inbound_u_id' => $val['u_id'],
                    'ur_status >=' => 0, //Pending or Active
                    'u_status >=' => 0, //Pending or Active
                )));
            }
        }

        if(in_array('u__inbounds',$join_objects)){
            foreach($res as $key=>$val){
                //Fetch the messages for this entity:
                $res[$key]['u__inbounds'] = array();
                $inbounds = $this->Db_model->ur_inbound_fetch(array(
                    'ur_outbound_u_id' => $val['u_id'],
                    'ur_status >=' => 0, //Pending or Active
                    'u_status >=' => 0, //Pending or Active
                ));

                foreach($inbounds as $ur){
                    $res[$key]['u__inbounds'][$ur['u_id']] = $ur;
                }

            }
        }

        return $res;
    }

    function ur_inbound_fetch($match_columns, $join_objects=array()){
        //Missing anything?
        $this->db->select('*');
        $this->db->from('v5_entities u');
        $this->db->join('v5_entity_links ur', 'ur.ur_inbound_u_id = u.u_id');
        $this->db->join('v5_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0
        foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
        }
        $this->db->order_by('u.u__e_score','DESC');
        $q = $this->db->get();
        return $q->result_array();
    }





    function c_replicate($u_id,$intent,$c_id){

        if($intent['c_status']<0){
            return array();
        }

        $new_c = array();
        foreach($intent as $key=>$value){
            //Is this a message field?
            if(!(substr($key,0,3)=='c__') && substr($key,0,2)=='c_' && !in_array($key,array('c_id','c_timestamp','c_inbound_u_id'))){
                //Yes, move over:
                $new_c[$key] = $value;
            }
        }

        //Append creator:
        $new_c['c_inbound_u_id'] = $u_id;

        //Create intent:
        $new_c = $this->Db_model->c_create($new_c);

        //Create Link:
        $intent_relation = $this->Db_model->cr_create(array(
            'cr_inbound_u_id' => $u_id,
            'cr_inbound_c_id'  => $c_id,
            'cr_outbound_c_id' => $new_c['c_id'],
            'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
                    'cr_status >=' => 1,
                    'c_status >' => 0,
                    'cr_inbound_c_id' => $c_id,
                )),
        ));

        //Return everything:
        $new_cs = $this->Db_model->cr_outbound_fetch(array(
            'cr.cr_id' => $intent_relation['cr_id'],
        ));

        return $new_cs[0];

    }



	function c_update($id,$update_columns){
	    $this->db->where('c_id', $id);
	    $this->db->update('v5_intents', $update_columns);

        //Update Algolia:
        $this->Db_model->algolia_sync('c',$id);

	    return $this->db->affected_rows();
	}
	


	
	function e_update($id,$update_columns){
	    $this->db->where('e_id', $id);
	    $this->db->update('v5_engagements', $update_columns);
	    return $this->db->affected_rows();
	}
	



	function c_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('c_outcome','c_inbound_u_id'))){
            return false;
        }

        if(!isset($insert_columns['c_timestamp'])){
            $insert_columns['c_timestamp'] = date("Y-m-d H:i:s");
        }
        if(!isset($insert_columns['c_status'])){
            $insert_columns['c_status'] = 1;
        }
		
		//Lets now add:
		$this->db->insert('v5_intents', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['c_id'] = ( isset($insert_columns['c_id']) ? $insert_columns['c_id'] : $this->db->insert_id() );

        //Update Algolia:
        $this->Db_model->algolia_sync('c',$insert_columns['c_id']);
		
		return $insert_columns;
	}
	
	
	/* ******************************
	 * Other
	 ****************************** */

	function x_social_fetch($u_id){

	    $social_urls = $this->config->item('social_urls');

        $return_array = array();
	    foreach($social_urls as $key=>$fa_icon){

            $urls = $this->Db_model->x_fetch(array(
                'x_outbound_u_id' => $u_id,
                'x_status >' => 0,
                '(x_url LIKE \'%'.$key.'%\' OR x_clean_url LIKE \'%'.$key.'%\')' => null,
            ));

            foreach($urls as $url){
                array_push($return_array , array(
                    'url' => $url['x_url'],
                    'fa_icon' => $fa_icon,
                ));
            }
        }

	    return $return_array;

    }

    function x_fetch($match_columns, $join_objects=array(), $order_columns=array(), $limit=0){
        //Fetch the target entities:
        $this->db->select('*');
        $this->db->from('v5_urls x');
        if(in_array('u',$join_objects)){
            $this->db->join('v5_entities u', 'u.u_id=x.x_outbound_u_id','left');
        }
        foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
        }

        if(count($order_columns)>0){
            foreach($order_columns as $key=>$value){
                $this->db->order_by($key,$value);
            }
        }

        if($limit>0){
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $res = $q->result_array();

        return $res;
    }

    function x_update($id,$update_columns){
        $this->db->where('x_id', $id);
        $this->db->update('v5_urls', $update_columns);
        return $this->db->affected_rows();
    }

    function x_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('x_url','x_clean_url','x_type','x_inbound_u_id','x_outbound_u_id'))){
            return false;
        } elseif(!filter_var($insert_columns['x_url'], FILTER_VALIDATE_URL)){
            return false;
        } elseif(!filter_var($insert_columns['x_clean_url'], FILTER_VALIDATE_URL)){
            return false;
        }

        //Check to see if this URL exists, if so, return that:
        $urls = $this->Db_model->x_fetch(array(
            '(x_url LIKE \''.$insert_columns['x_url'].'\' OR x_url LIKE \''.$insert_columns['x_clean_url'].'\')' => null,
        ));

        if(count($urls)>0){

            if($insert_columns['x_outbound_u_id']==$urls[0]['x_outbound_u_id']){

                //For same object, we're all good, return this URL:
                return $urls[0];

            } else {

                //Save this engagement as we have an issue here...
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $insert_columns['x_inbound_u_id'],
                    'e_outbound_u_id' => $insert_columns['x_outbound_u_id'],
                    'e_inbound_c_id' => 8, //System error
                    'e_text_value' => 'x_create() found a duplicate URL ID ['.$urls[0]['x_id'].']',
                    'e_json' => $insert_columns,
                    'e_x_id' => $urls[0]['x_id'],
                ));

                return false;
            }
        }

        if(!isset($insert_columns['x_timestamp'])){
            $insert_columns['x_timestamp'] = date("Y-m-d H:i:s");
        }

        if(!isset($insert_columns['x_check_timestamp'])){
            $insert_columns['x_check_timestamp'] = date("Y-m-d H:i:s");
        }

        if(!isset($insert_columns['x_status'])){
            $insert_columns['x_status'] = 1; //Live URL
        }

        if(!isset($insert_columns['x_http_code'])){
            $insert_columns['x_http_code'] = 200; //As the URL was just added
        }


        //Lets now add:
        $this->db->insert('v5_urls', $insert_columns);

        //Fetch inserted id:
        $insert_columns['x_id'] = $this->db->insert_id();

        return $insert_columns;
    }



	function e_fetch($match_columns=array(), $limit=100, $join_objects=array(), $replace_key=null, $order_columns=array(
        'e.e_id' => 'DESC',
    )){
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
	
	
	
	function e_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('e_inbound_c_id'))){
            return false;
        }
	    
	    //Try to auto detect user:
	    if(!isset($insert_columns['e_inbound_u_id'])){
	        //Try to fetch user ID from session:
	        $user_data = $this->session->userdata('user');
	        if(isset($user_data['u_id']) && intval($user_data['u_id'])>0){
	            $insert_columns['e_inbound_u_id'] = $user_data['u_id'];
	        } else {
	            //Have no user:
	            $insert_columns['e_inbound_u_id'] = 0;
	        }
	    }


        //Do we have a json attachment for this engagement?
        $insert_columns['e_has_blob'] = 'f';
        $save_blob = null;
        if(isset($insert_columns['e_json']) && strlen(print_r($insert_columns['e_json'],true))>0){
            if(is_array($insert_columns['e_json']) && count($insert_columns['e_json'])>0){
                $save_blob = $insert_columns['e_json'];
                $insert_columns['e_has_blob'] = 't';
            }
        }
        //Remove e_json from here to keep v5_engagements small and lean
        unset($insert_columns['e_json']);


        //Set some defaults:
        if(!isset($insert_columns['e_timestamp'])){
            $insert_columns['e_timestamp'] = date("Y-m-d H:i:s");
        }
        if(!isset($insert_columns['e_text_value'])){
            $insert_columns['e_text_value'] = null;
        }
        if(!isset($insert_columns['e_status'])){
            $insert_columns['e_status'] = -1; //Auto approved
        }
        if(!isset($insert_columns['e_ur_id'])){
            $insert_columns['e_ur_id'] = 0;
        }


        //Set some zero defaults if not set:
        foreach(array('e_outbound_c_id','e_outbound_u_id','e_inbound_u_id','e_cr_id','e_i_id','e_x_id') as $dz){
            if(!isset($insert_columns[$dz]) || intval($insert_columns[$dz])<1){
                $insert_columns[$dz] = 0;
            }
        }

		//Lets log:
		$this->db->insert('v5_engagements', $insert_columns);


		//Fetch inserted id:
		$insert_columns['e_id'] = $this->db->insert_id();


		if($insert_columns['e_id']>0){

		    //Did we have a blob to save?
            if($save_blob){
                //Save this in a separate field:
                $this->db->insert('v5_engagement_blob', array(
                    'ej_e_id' => $insert_columns['e_id'],
                    'ej_e_blob' => serialize($save_blob),
                ));
            }

            //Notify relevant subscribers about this notification:
            $engagement_subscriptions = $this->config->item('engagement_subscriptions');
            $engagement_references = $this->config->item('engagement_references');


            //Individual subscriptions:
            foreach($engagement_subscriptions as $subscription){

                if(in_array($insert_columns['e_inbound_c_id'],$subscription['subscription']) || in_array(0,$subscription['subscription'])){

                    //Just do this one:
                    if(!isset($engagements[0])){
                        //Fetch Engagement Data:
                        $engagements = $this->Db_model->e_fetch(array(
                            'e_id' => $insert_columns['e_id']
                        ));
                    }

                    //Did we find it? We should have:
                    if(isset($engagements[0])){

                        $subject = 'Notification: '.trim(strip_tags($engagements[0]['c_outcome'])).' - '.( isset($engagements[0]['u_full_name']) ? $engagements[0]['u_full_name'] : 'System' );

                        //Compose email:
                        $html_message = null; //Start
                        $html_message .= '<div>Hi Mench Admin,</div><br />';

                        //Lets go through all references to see what is there:
                        foreach($engagement_references as $engagement_field=>$er){
                            if(intval($engagements[0][$engagement_field])>0){
                                //Yes we have a value here:
                                $html_message .= '<div>'.$er['name'].': '.echo_object($er['object_code'], $engagements[0][$engagement_field]).'</div>';
                            }
                        }

                        if(strlen($engagements[0]['e_text_value'])>0){
                            $html_message .= '<div>Message:<br />'.format_e_text_value($engagements[0]['e_text_value']).'</div>';
                        }
                        $html_message .= '<br />';
                        $html_message .= '<div>Cheers,</div>';
                        $html_message .= '<div>Mench Engagement Watcher</div>';
                        $html_message .= '<div style="font-size:0.8em;">Engagement <a href="https://mench.com/cockpit/ej_list/'.$engagements[0]['e_id'].'">#'.$engagements[0]['e_id'].'</a></div>';

                        //Send email:
                        $this->Comm_model->send_email($subscription['admin_emails'], $subject, $html_message);
                    }
                }
            }

        }
		
		//Return:
		return $insert_columns;
	}


	function c_update_tree($c_id, $c_update_columns=array(), $fetch_outbound=0){

	    //Will fetch the recursive tree and update
        $tree = $this->Db_model->c_recursive_fetch($c_id, $fetch_outbound);

        if(count($c_update_columns)==0 || count($tree['c_flat'])==0){
            return false;
        }

        //Found results, update them relative to their current value:
        $c_relative_update = 'UPDATE "v5_intents" SET';
        $update_columns = 0;
        foreach($c_update_columns as $key=>$value){
            if(doubleval($value)==0){
                continue; //No adjustment needed
            }
            if($update_columns>0){
                $c_relative_update .= ',';
            }
            $c_relative_update .= ' '.$key.' = '.$key.' + ('.$value.')';
            $update_columns++;
        }
        //Close the query:
        $c_relative_update .= ' WHERE "c_id" = '; //$c_id to be inserted later...

        if($update_columns==0){
            return 0;
        }

        //Run Query for all intents:
        $affected_rows = 0;
        foreach($tree['c_flat'] as $c_this_id){
            $this->db->query($c_relative_update.$c_this_id.';');
            $affected_rows += $this->db->affected_rows();
        }
        return $affected_rows;
    }

	function c_recursive_fetch($c_id, $fetch_outbound=0, $db_update=0, $cr_id=0, $recursive_children=null, $k_w_id=0){

	    //Get core data:
        $immediate_children = array(
            'c1__tree_inputs' => 0,
            'c1__tree_outputs' => 0,
            'c1__tree_max_hours' => 0,
            'c1__this_messages' => 0,
            'c1__tree_messages' => 0,
            'db_updated' => 0,
            'db_queries' => array(),
            'c_flat' => array(),
            'cr_flat' => array(),
            'tree_top' => array(),
        );

        if(!$recursive_children){
            $recursive_children = $immediate_children;
        }

        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        if($fetch_outbound){
            $child_cs = $this->Db_model->cr_outbound_fetch(array(
                'cr.cr_inbound_c_id' => $c_id,
                'cr.cr_status >=' => 0,
                'c.c_status >' => 0,
            ));
        } else {
            $child_cs = $this->Db_model->cr_inbound_fetch(array(
                'cr.cr_outbound_c_id' => $c_id,
                'cr.cr_status >=' => 0,
                'c.c_status >' => 0,
            ));
        }


        if(count($child_cs)>0){
            foreach($child_cs as $c){
                if(in_array($c['c_id'],$recursive_children['c_flat'])){

                    //Ooooops, this has an error as it would result in an infinite loop:
                    return false;

                } else {

                    //Fetch children for this intent, if any:
                    $granchildren = $this->Db_model->c_recursive_fetch($c['c_id'], $fetch_outbound, $db_update, $c['cr_id'], $immediate_children, $k_w_id);

                    if(!$granchildren){
                        //There was an infinity break
                        return false;
                    }

                    //Addup children if any:
                    $immediate_children['c1__tree_inputs'] += $granchildren['c1__tree_inputs'];
                    $immediate_children['c1__tree_outputs'] += $granchildren['c1__tree_outputs'];
                    $immediate_children['c1__tree_max_hours'] += $granchildren['c1__tree_max_hours'];
                    if($db_update){
                        $immediate_children['c1__tree_messages'] += $granchildren['c1__tree_messages'];
                        $immediate_children['db_updated'] += $granchildren['db_updated'];
                        if(!empty($granchildren['db_queries'])){
                            array_push($immediate_children['db_queries'],$granchildren['db_queries']);
                        }
                    }

                    array_push($immediate_children['cr_flat'],$granchildren['cr_flat']);
                    array_push($immediate_children['c_flat'],$granchildren['c_flat']);
                    array_push($immediate_children['tree_top'],$granchildren['tree_top']);
                }
            }
        }


        //Fetch & add this item itself:
        if($cr_id){
            if($fetch_outbound){
                $cs = $this->Db_model->cr_outbound_fetch(array(
                    'cr.cr_id' => $cr_id,
                ));
            } else {
                $cs = $this->Db_model->cr_inbound_fetch(array(
                    'cr.cr_id' => $cr_id,
                ));
            }
        } else {
            //This is the very first item that
            $cs = $this->Db_model->c_fetch(array(
                'c.c_id' => $c_id,
            ));
        }

        if(count($cs)>0){

            if(intval($cs[0]['c_require_url_to_complete']) || intval($cs[0]['c_require_notes_to_complete'])){
                $immediate_children['c1__tree_outputs'] += 1;
            } else {
                $immediate_children['c1__tree_inputs'] += 1;
            }
            $immediate_children['c1__tree_max_hours'] += $cs[0]['c_time_estimate'];

            //Set the data for this intent:
            $cs[0]['c1__tree_inputs'] = $immediate_children['c1__tree_inputs'];
            $cs[0]['c1__tree_outputs'] = $immediate_children['c1__tree_outputs'];
            $cs[0]['c1__tree_max_hours'] = $immediate_children['c1__tree_max_hours'];

            //Count messages only if DB updating:
            if($db_update){
                $cs[0]['c1__this_messages'] = count($this->Db_model->i_fetch(array(
                    'i_status >=' => 0,
                    'i_outbound_c_id' => $c_id,
                )));
                $immediate_children['c1__tree_messages'] += $cs[0]['c1__this_messages'];
                $cs[0]['c1__tree_messages'] = $immediate_children['c1__tree_messages'];
            }

            if(isset($cs[0]['cr_id'])){
                array_push($immediate_children['cr_flat'],intval($cs[0]['cr_id']));
                if($k_w_id>0){
                    //Add this to the cache:
                    $this->Db_model->k_create(array(
                        'k_w_id' => $k_w_id,
                        'k_cr_id' => $cs[0]['cr_id'],
                        'k_time_estimate' => doubleval($cs[0]['c_time_estimate']),
                    ));
                }
            }
            array_push($immediate_children['c_flat'],intval($c_id));
            array_push($immediate_children['tree_top'],$cs[0]);

            //Update DB only if any single field is not synced:
            if($db_update && !(
                number_format($cs[0]['c1__tree_max_hours'],3)==number_format($cs[0]['c__tree_max_hours'],3) &&
                $cs[0]['c1__tree_inputs']==$cs[0]['c__tree_inputs'] &&
                $cs[0]['c1__tree_outputs']==$cs[0]['c__tree_outputs'] &&
                $cs[0]['c1__this_messages']==$cs[0]['c__this_messages'] &&
                $cs[0]['c1__tree_messages']==$cs[0]['c__tree_messages'] &&
                intval($cs[0]['c__is_orphan'])==0
                )){

                //Something was not up to date, let's update:
                $this->Db_model->c_update( $c_id , array(
                    'c__tree_max_hours' => number_format($cs[0]['c1__tree_max_hours'],3),
                    'c__tree_inputs' => $cs[0]['c1__tree_inputs'],
                    'c__tree_outputs' => $cs[0]['c1__tree_outputs'],
                    'c__this_messages' => $cs[0]['c1__this_messages'],
                    'c__tree_messages' => $cs[0]['c1__tree_messages'],
                    'c__is_orphan' => 0, //It cannot be orphan since its part of the main tree
                ));

                $immediate_children['db_updated']++;

                array_push($immediate_children['db_queries'],'['.$c_id.'] Hours:'.number_format($cs[0]['c__tree_max_hours'],3).'=>'.number_format($cs[0]['c1__tree_max_hours'],3).' / Inputs:'.$cs[0]['c__tree_inputs'].'=>'.$cs[0]['c1__tree_inputs'].' / Outputs:'.$cs[0]['c__tree_outputs'].'=>'.$cs[0]['c1__tree_outputs'].' / Message:'.$cs[0]['c__this_messages'].'=>'.$cs[0]['c1__this_messages'].' / Tree Message:'.$cs[0]['c__tree_messages'].'=>'.$cs[0]['c1__tree_messages'].' / Orphan:'.intval($cs[0]['c__is_orphan']).'=>0 ('.$cs[0]['c_outcome'].')');

            }
        }

        //Flatten intent ID array:
        $result = array();
        array_walk_recursive($immediate_children['c_flat'],function($v, $k) use (&$result){ $result[] = $v; });
        $immediate_children['c_flat'] = $result;

        $result = array();
        array_walk_recursive($immediate_children['cr_flat'],function($v, $k) use (&$result){ $result[] = $v; });
        $immediate_children['cr_flat'] = $result;



        //Return data:
        return $immediate_children;
    }


    function algolia_sync($obj,$obj_id=0){

	    //Define the support objects indexed on algolia:
        $website = $this->config->item('website');
        $obj_id = intval($obj_id);

        $alg_indexes = array(
            'c' => 'alg_intents',
            'u' => 'alg_entities',
        );
        $algolia_local_tables = array(
            'c' => 'v5_intents',
            'u' => 'v5_entities',
        );

	    if(!array_key_exists($obj,$alg_indexes)){
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
        $index = $client->initIndex($alg_indexes[$obj]);


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
        if($obj=='c'){
            $items = $this->Db_model->c_fetch($limits);
        } elseif($obj=='u'){
            $items = $this->Db_model->u_fetch($limits);
            $inbound_names = array(); //To cache names of parents
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

            if($obj=='u') {

                $new_item['u_id'] = intval($item['u_id']); //rquired for all objects
                $new_item['u__e_score'] = intval($item['u__e_score']);
                $new_item['u_full_name'] = $item['u_full_name'];
                $new_item['u_keywords'] = $item['u_bio'];
                $new_item['_tags'] = array();


                //Tags map parent relation:
                if(count($item['u__inbounds'])>0){
                    //Loop through the Tags:
                    foreach($item['u__inbounds'] as $u_id=>$u){
                        array_push($new_item['_tags'],'u'.$u_id);
                    }
                } else {
                    //No parents!
                    array_push($new_item['_tags'],'noparent');
                }


                //Add Entity as tag of Entity itself for search management:
                if($item['u_id']==2738){
                    array_push($new_item['_tags'],'u2738');
                }

                //Append additional information:
                $urls = $this->Db_model->x_fetch(array(
                    'x_status >' => 0,
                    'x_outbound_u_id' => $item['u_id'],
                ));
                foreach($urls as $x){
                    //Add main URL:
                    $new_item['u_keywords'] .= ' '.$x['x_url'];

                    //Add Clean URL only if different from main:
                    if(!($x['x_url']==$x['x_clean_url'])){
                        $new_item['u_keywords'] .= ' '.$x['x_clean_url'];
                    }
                }

                if(strlen($item['u_email'])>0){
                    $new_item['u_keywords'] .= ' '.$item['u_email'];
                }

                //Clean keywords
                $new_item['u_keywords'] = trim(strip_tags($new_item['u_keywords']));

            } elseif($obj=='c'){

                $new_item['c_id'] = intval($item['c_id']);
                $new_item['c_outcome'] = $item['c_outcome'];
                $new_item['c_is_any'] = intval($item['c_is_any']);
                $new_item['c_keywords'] = ( strlen($item['c_trigger_statements'])>0 ? join(' ',json_decode($item['c_trigger_statements'])) : '' );

                $new_item['c__tree_max_hours'] = number_format($item['c__tree_max_hours'],3);
                $new_item['c__tree_inputs'] = intval($item['c__tree_inputs']);
                $new_item['c__tree_outputs'] = intval($item['c__tree_outputs']);
                $new_item['c__tree_messages'] = intval($item['c__tree_messages']);

                //Fetch all Messages:
                $messages = $this->Db_model->i_fetch(array(
                    'i_status >=' => 0,
                    'i_outbound_c_id' => $item['c_id'],
                ));
                foreach($messages as $i){
                    //Add main URL:
                    $new_item['c_keywords'] .= ' '.$i['i_message'];
                }

                //Clean keywords
                $new_item['c__this_messages'] = count($messages);
                $new_item['c_keywords'] = trim(strip_tags($new_item['c_keywords']));

                //Append parent intents:
                $new_item['_tags'] = array();
                $child_cs = $this->Db_model->cr_inbound_fetch(array(
                    'cr.cr_outbound_c_id' => $item['c_id'],
                    'cr.cr_status >=' => 1,
                    'c.c_status >=' => 1,
                ));

                if(count($child_cs)>0){
                    //Loop through the Tags:
                    foreach($child_cs as $c){
                        array_push($new_item['_tags'],'c'.$c['c_id']);
                    }
                } else {
                    //No parents!
                    array_push($new_item['_tags'],'noparent');
                }
            }

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
            'status' => ( isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs'])>0 ? 1 : 0 ),
            'message' => ( isset($obj_add_message['objectIDs']) ? count($obj_add_message['objectIDs']) : 0 ).' items updated',
        );

    }

}
