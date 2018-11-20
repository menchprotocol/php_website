<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model {
	
	//This model handles all DB calls from our local database.
	
	function __construct() {
		parent::__construct();
	}


    function w_update($id,$update_columns){
        //Update first
        $this->db->where('w_id', $id);
        $this->db->update('tb_actionplans', $update_columns);
        return $this->db->affected_rows();
    }

    function k_update($id,$update_columns){
        //Update first
        $this->db->where('k_id', $id);
        $this->db->update('tb_actionplan_links', $update_columns);
        return $this->db->affected_rows();
    }



    function k_next_fetch($w_id, $min_k_rank=0){

	    //Two things need to be fetched:
        $last_working_on_any = $this->Db_model->k_fetch(array(
            'w_id' => $w_id,
            'w_status' => 1, //Active subscriptions
            'c_status >=' => 2,
            'k_rank >' => $min_k_rank,
            //The first case is for OR intents that a child is not yet selected, and the second part is for regular incompleted items:
            '(k_status IN (1,-2) AND c_is_any=1)' => null, //Not completed or not yet started
        ), array('w','cr','cr_c_child'), array(
            'k_rank' => 'DESC',
        ), 1);

        //We did not find it? Ok fetch the first one and replace:
        $first_pending_all = $this->Db_model->k_fetch(array(
            'w_id' => $w_id,
            'w_status' => 1, //Active subscriptions
            'c_status >=' => 2,
            'k_rank >' => $min_k_rank,
            //The first case is for OR intents that a child is not yet selected, and the second part is for regular incompleted items:
            'k_status IN (0,-2)' => null, //Not completed or not yet started
        ), array('w','cr','cr_c_child'), array(
            'k_rank' => 'ASC', //Items are cached in order ;)
        ), 1);

        if(isset($first_pending_all[0]) && (!isset($last_working_on_any[0]) || $first_pending_all[0]['k_rank']<$last_working_on_any[0]['k_rank'])){
            return $first_pending_all;
        } elseif(isset($last_working_on_any[0])){
            return $last_working_on_any;
        } else {
            //Neither case was found!
            return false;
        }
    }



    function k_status_update($k_id, $new_k_status){

	    //Marks a single subscription intent as complete:
        $this->Db_model->k_update($k_id, array(
            'k_last_updated' => date("Y-m-d H:i:s"),
            'k_status' => $new_k_status, //Working On...
        ));

        if($new_k_status==2){

            //It's complete!
            //Fetch full $k object
            $ks = $this->Db_model->k_fetch(array(
                'k_id' => $k_id,
            ), array('w','cr'));
            if(count($ks)==0){
                return false;
            }

            //Dispatch all on-complete messages of $c_id
            $messages = $this->Db_model->i_fetch(array(
                'i_c_id' => $ks[0]['cr_child_c_id'],
                'i_status' => 3, //On complete messages
            ));
            if(count($messages)>0){
                $send_messages = array();
                foreach($messages as $i){
                    array_push($send_messages, array_merge($i , array(
                        'e_w_id' => $ks[0]['w_id'],
                        'e_parent_u_id' => 2738, //Initiated by PA
                        'e_child_u_id' => $ks[0]['w_child_u_id'],
                        'i_c_id' => $i['i_c_id'],
                    )));
                }
                //Sendout messages:
                $this->Comm_model->send_message($send_messages);
            }

            //TODO Update w__progress at this point based on intent data

            //TODO implement drip

            /*
            //This function will search and schedule all drip messages of $c_id
            $messages = $this->Db_model->i_fetch(array(
                'i_c_id' => $c_id,
                'i_status' => 2, //Drip messages
            ));

            if(count($messages)>0){
                $start_time = time();
                //TODO Adjust $drip_intervals = (class_ends($bs[0], $focus_class)-$start_time) / (count($drip_messages)+1);
                $drip_time = $start_time;
                foreach($messages as $i){
                    $drip_time += $drip_intervals;
                    $this->Db_model->e_create(array(
                        'e_parent_u_id' => 0, //System
                        'e_child_u_id' => $ks[0]['u_id'],
                        'e_timestamp' => date("Y-m-d H:i:s" , $drip_time ), //Used by Cron Job to fetch this Drip when due
                        'e_json' => array(
                            'created_time' => date("Y-m-d H:i:s" , $start_time ),
                            'drip_time' => date("Y-m-d H:i:s" , $drip_time ),
                            'i_drip_count' => count($drip_messages),
                            'i' => $i, //The actual message that would be sent
                        ),
                        'e_parent_c_id' => 52, //Pending Drip e_parent_c_id=52
                        'e_status' => 0, //Pending for the Drip Cron
                        'e_i_id' => $i['i_id'],
                        'e_child_c_id' => $i['i_c_id'],
                    ));
                }
            }
            */
        }
    }


    function k_skip_recursive_down($w_id, $c_id, $k_id, $update_db=true){

        //User has requested to skip an intent starting from:
        $dwn_tree = $this->Db_model->k_recursive_fetch($w_id, $c_id, true);
        $skip_ks = array_merge(array(intval($k_id)), $dwn_tree['k_flat']);

        //Now see how many should we actually skip based on current status:
        $skippable_ks = $this->Db_model->k_fetch(array(
            'k_status IN ('.join(',', $this->config->item('k_status_incomplete')).')' => null, //incomplete
            'k_id IN ('.join(',',$skip_ks).')' => null,
        ), ( $update_db ? array() : array('cr','cr_c_child') ), array('k_rank'=>'ASC'));

        if($update_db){

            //Now start skipping:
            foreach($skippable_ks as $k){
                $this->Db_model->k_status_update($k['k_id'], -1); //skip
            }

            //There is a chance that the subscription might be now completed due to this skipping, lets check:
            /*
            $ks = $this->Db_model->k_fetch(array(
                'k_id' => $k_id,
            ), array('w','cr','cr_c_parent'));
            if(count($ks)>0){
                $this->Db_model->k_complete_recursive_up($ks[0],$ks[0],-1);
            }
            */

        }

        //Returned intents:
        return $skippable_ks;

    }


    function k_choose_or($w_id, $cr_parent_c_id, $c_id){
        //$c_id is the chosen path for the options of $cr_parent_c_id
        //When a user chooses an answer to an ANY intent, this function would mark that answer as complete while marking all siblings as SKIPPED
        $chosen_path = $this->Db_model->k_fetch(array(
            'k_w_id' => $w_id,
            'cr_parent_c_id' => $cr_parent_c_id, //Fetch children of parent intent which are the siblings of current intent
            'cr_child_c_id' => $c_id, //The answer
            'c_status >=' => 2,
        ), array('w','cr','cr_c_parent'));

        if(count($chosen_path)==1){

            //Also fetch children to see if we requires any notes/url to mark as complete:
            $path_requirements = $this->Db_model->k_fetch(array(
                'k_w_id' => $w_id,
                'cr_parent_c_id' => $cr_parent_c_id, //Fetch children of parent intent which are the siblings of current intent
                'cr_child_c_id' => $c_id, //The answer
                'c_status >=' => 2,
            ), array('w','cr','cr_c_child'));

            if(count($path_requirements)==1){
                //Determine status:
                $force_working_on = ( (intval($path_requirements[0]['c_require_notes_to_complete']) || intval($path_requirements[0]['c_require_url_to_complete'])) ? 1 : null );

                //Now mark intent as complete (and this will SKIP all siblings) and move on:
                $this->Db_model->k_complete_recursive_up($chosen_path[0], $chosen_path[0], $force_working_on);

                //Successful:
                return true;
            } else {
                return false;
            }

        } else {
            //Oooopsi, we could not find it! Log error and return false:
            $this->Db_model->e_create(array(
                'e_value' => 'Unable to locate OR selection for this subscription',
                'e_parent_c_id' => 8, //System error
                'e_child_c_id' => $c_id,
                'e_w_id' => $w_id,
            ));

            return false;
        }
    }

    function k_complete_recursive_up($cr, $w, $force_k_status=null){

        //Check if parent of this item is not started, because if not, we need to mark that as Working On:
        $parent_ks = $this->Db_model->k_fetch(array(
            'k_w_id' => $w['w_id'],
            'k_status' => 0, //skip intents that are not stared or working on...
            'cr_child_c_id' => $cr['cr_parent_c_id'],
        ), array('cr'));
        if(count($parent_ks)==1){
            //Update status (It might not work if it was working on AND new k_status=1)
            $this->Db_model->k_status_update($parent_ks[0]['k_id'], 1);
        }

	    //See if current intent children are complete...
        //We'll assume complete unless proven otherwise:
        $down_is_complete = true;
        $total_skipped = 0;
        //Is this an OR branch? Because if it is, we need to skip its siblings:
        if(intval($cr['c_is_any'])){
            //Skip all eligible siblings, if any:
            //$cr['cr_child_c_id'] is the chosen path that we're trying to find its siblings for the parent $cr['cr_parent_c_id']

            //First search for other options that need to be skipped because of this selection:
            $none_chosen_paths = $this->Db_model->k_fetch(array(
                'k_w_id' => $w['w_id'],
                'cr_parent_c_id' => $cr['cr_parent_c_id'], //Fetch children of parent intent which are the siblings of current intent
                'cr_child_c_id !=' => $cr['cr_child_c_id'], //NOT The answer (we need its siblings)
                'c_status >=' => 2,
                'k_status IN (0,1)' => null,
            ), array('w','cr','cr_c_child'));

            //This is the none chosen answers, if any:
            foreach($none_chosen_paths as $k){
                //Skip this intent:
                $total_skipped += $this->Db_model->k_skip_recursive_down($w['w_id'], $k['c_id'], $k['k_id']);
            }
        }


        if(!$force_k_status){
            //Regardless of Branch type, we need all children to be complete if we are to mark this as complete...
            //If not, we will mark is as working on...
            //So lets fetch the down tree and see Whatssup:
            $dwn_tree = $this->Db_model->k_recursive_fetch($w['w_id'], $cr['cr_child_c_id'], true);

            //Does it have OUTs?
            if(count($dwn_tree['k_flat'])>0){
                //We do have down, let's check their status:
                $dwn_incomplete_ks = $this->Db_model->k_fetch(array(
                    'k_status IN ('.join(',', $this->config->item('k_status_incomplete')).')' => null, //incomplete
                    'k_id IN ('.join(',',$dwn_tree['k_flat']).')' => null, //All OUT links
                ), array('cr'));
                if(count($dwn_incomplete_ks)>0){
                    //We do have some incomplete children, so this is not complete:
                    $down_is_complete = false;
                }
            }
        }


        //Ok now define the new status here:
        $new_k_status = ( !is_null($force_k_status) ? $force_k_status : ( $down_is_complete ? 2 : 1 ) );

        //Update this intent:
        $this->Db_model->k_status_update($cr['k_id'], $new_k_status);


        //We are done with this branch if the status is any of the following:
        if(in_array($new_k_status, array(3,2,-1))){

            //Since down tree is now complete, see if up tree needs completion as well:
            //Fetch all parents:
            $up_tree = $this->Db_model->k_recursive_fetch($w['w_id'], $cr['cr_child_c_id'], false);
            
            //Track completion for all top parents, because if they are all complete, the Subscription might be complete:
            $w_might_be_complete = true;

            //Now loop through each level and see whatssup:
            foreach($up_tree['k_flat'] as $parent_k_id){

                //Fetch details to see whatssup:
                $parent_ks = $this->Db_model->k_fetch(array(
                    'k_id' => $parent_k_id,
                    'k_w_id' => $w['w_id'],
                    'c_status >=' => 2,
                    'k_status <' => 2, //Not completed in any way
                ), array('cr','cr_c_child'));
                
                if(count($parent_ks)==1){

                    //We did find an incomplete parent, let's see if its now completed:
                    //Assume complete unless proven otherwise:
                    $is_complete = true;

                    //Any intents would always be complete since we already marked one of its children as complete!
                    //If it's an ALL intent, we need to check to make sure all children are complete:
                    if(intval($parent_ks[0]['c_is_any'])){
                        //We need a single immediate child to be complete:
                        $complete_child_cs = $this->Db_model->k_fetch(array(
                            'k_w_id' => $w['w_id'],
                            'k_status NOT IN ('.join(',', $this->config->item('k_status_incomplete')).')' => null, //complete
                            'cr_parent_c_id' => $parent_ks[0]['cr_child_c_id'],
                        ), array('cr'));
                        if(count($complete_child_cs)==0){
                            $is_complete = false;
                        }
                    } else {
                        //We need all immediate children to be complete (i.e. No incomplete)
                        $incomplete_child_cs = $this->Db_model->k_fetch(array(
                            'k_w_id' => $w['w_id'],
                            'k_status IN ('.join(',', $this->config->item('k_status_incomplete')).')' => null, //incomplete
                            'cr_parent_c_id' => $parent_ks[0]['cr_child_c_id'],
                        ), array('cr'));
                        if(count($incomplete_child_cs)>0){
                            $is_complete = false;
                        }
                    }

                    if($is_complete){
                        //Update this:
                        $this->Db_model->k_status_update($parent_ks[0]['k_id'], ( !is_null($force_k_status) ? $force_k_status : 2 ));
                    } elseif($parent_ks[0]['k_status']==0) {
                        //Status is not started, let's set to started:
                        $this->Db_model->k_status_update($parent_ks[0]['k_id'], 1); //Started
                        //So subscription cannot be complete:
                        $w_might_be_complete = false;
                    } else {
                        //So subscription cannot be complete:
                        $w_might_be_complete = false;
                    }
                }
            }

            if($w_might_be_complete){
                //There is a chance that entire subscription might be complete
                //To determine if the subscription is complete we need to look at the top level siblings...
                //What kind of an intent (AND node or OR node) is this subscription w_c_id?
                $cs = $this->Db_model->w_fetch(array(
                    'w_id' => $w['w_id'],
                ), array('c'));

                if(count($cs)==0){
                    return false;
                }

                //Assume true unless otherwise:
                $w_is_complete = true;

                if($cs[0]['c_is_any']){
                    //We need a single one to be completed:
                    $complete_child_cs = $this->Db_model->k_fetch(array(
                        'k_w_id' => $cs[0]['w_id'],
                        'cr_parent_c_id' => $cs[0]['w_c_id'],
                        'k_status NOT IN ('.join(',', $this->config->item('k_status_incomplete')).')' => null, //complete
                    ), array('cr'));
                    if(count($complete_child_cs)==0){
                        $w_is_complete = false;
                    }
                } else {
                    //We need all to be completed:
                    $incomplete_child_cs = $this->Db_model->k_fetch(array(
                        'k_w_id' => $cs[0]['w_id'],
                        'cr_parent_c_id' => $cs[0]['w_c_id'],
                        'k_status IN ('.join(',', $this->config->item('k_status_incomplete')).')' => null, //incomplete
                    ), array('cr'));
                    if(count($incomplete_child_cs)>0){
                        $w_is_complete = false;
                    }
                }

                if($w_is_complete){

                    //We do this check as a hack to a bug that was running this piece of code 10 times!
                    $validate_subscription = $this->Db_model->w_fetch(array(
                        'w_id' => $cs[0]['w_id'], //Other than this one...
                        'w_status <' => 2, //Not Completed subscriptions
                    ));

                    if(count($validate_subscription)==1){

                        //What subscription number is this?
                        $completed_ws = $this->Db_model->w_fetch(array(
                            'w_id !=' => $cs[0]['w_id'], //Other than this one...
                            'w_parent_u_id' => $cs[0]['w_child_u_id'],
                            'w_status >=' => 2, //Completed subscriptions
                        ));

                        //Inform user that they are now complete with all tasks:
                        $this->Comm_model->send_message(array(
                            array(
                                'e_parent_u_id' => 2738, //Initiated by PA
                                'e_child_u_id' => $cs[0]['w_child_u_id'],
                                'e_child_c_id' => $cs[0]['w_c_id'],
                                'e_w_id' => $cs[0]['w_id'],
                                'i_message' => 'Congratulations for completing your '.echo_ordinal((count($completed_ws)+1)).' Subscription 🎉 Over time I will keep sharing new insights (based on my new training data) that could help you to '.$cs[0]['c_outcome'].' 🙌 You can, at any time, stop updates on your subscriptions by saying "quit".',
                            ),
                            array(
                                'e_parent_u_id' => 2738, //Initiated by PA
                                'e_child_u_id' => $cs[0]['w_child_u_id'],
                                'e_child_c_id' => $cs[0]['w_c_id'],
                                'e_w_id' => $cs[0]['w_id'],
                                'i_message' => 'How else can I help you '.$this->lang->line('platform_intent').'? '.echo_pa_lets(),
                            ),
                        ));

                        //Log subscription completion engagement:
                        $this->Db_model->e_create(array(
                            'e_parent_u_id' => $cs[0]['w_child_u_id'],
                            'e_child_c_id' => $cs[0]['w_c_id'],
                            'e_w_id' => $cs[0]['w_id'],
                            'e_parent_c_id' => 7490, //Subscription Completed
                        ));

                        //The entire subscription is now complete!
                        $this->Db_model->w_update( $cs[0]['w_id'], array(
                            'w_status' => 2, //Subscription is now complete
                            //TODO Maybe change to status 3 directly if the nature of the intent is not verifiable
                        ));

                    }
                }
            }
        }
    }



    function k_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('k_w_id','k_cr_id'))){
            return false;
        }

        if(!isset($insert_columns['k_timestamp'])){
            $insert_columns['k_timestamp'] = date("Y-m-d H:i:s");
        }

        if(!isset($insert_columns['k_rank'])){
            //Determine the highest rank for this subscription:
            $insert_columns['k_rank'] = 1 + $this->Db_model->max_value('tb_actionplan_links','k_rank', array(
                'k_w_id' => $insert_columns['k_w_id'],
            ));
        }



        if(!isset($insert_columns['k_cr_child_rank'])){
            $insert_columns['k_cr_child_rank'] = 0;
        }



        //Lets now add:
        $this->db->insert('tb_actionplan_links', $insert_columns);

        //Fetch inserted id:
        $insert_columns['k_id'] = $this->db->insert_id();

        return $insert_columns;
    }

    function c_new($c_id, $c_outcome, $link_c_id, $next_level, $parent_u_id){

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
        $parent_intents = $this->Db_model->c_fetch(array(
            'c.c_id' => intval($c_id),
        ), 1);
        if(count($parent_intents)<=0){
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            );
        }

        if(!$link_c_id){

            //We are NOT linking to an existing intent, but instead, we're creating a new intent:
            //Set default new hours:

            $default_new_hours = 0; //0 min default

            $recursive_query = array(
                'c__tree_max_hours' => $default_new_hours,
                'c__tree_all_count' => 1, //We just added one
            );

            //Create intent:
            $new_c = $this->Db_model->c_create(array(
                'c_parent_u_id' => $parent_u_id,
                'c_outcome' => trim($c_outcome),
                'c_time_estimate' => $default_new_hours,
                'c__tree_all_count' => 1, //We just added one
                'c__tree_max_hours' => $default_new_hours,
            ));

            //Log Engagement for New Intent:
            $this->Db_model->e_create(array(
                'e_parent_u_id' => $parent_u_id,
                'e_value' => 'Intent ['.$new_c['c_outcome'].'] created',
                'e_json' => array(
                    'input' => $_POST,
                    'before' => null,
                    'after' => $new_c,
                ),
                'e_parent_c_id' => 20, //New Intent
                'e_child_c_id' => $new_c['c_id'],
            ));

        } else {

            //We are linking to $link_c_id, lets make sure it exists:
            $new_cs = $this->Db_model->c_fetch(array(
                'c_id' => $link_c_id,
                'c_status >=' => 0,
            ), ( 3 - $next_level ));

            if(count($new_cs)<=0){
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Intent ID',
                );
            }
            $new_c = $new_cs[0];


            //check for all parents:
            $parent_tree = $this->Db_model->c_recursive_fetch($c_id);
            if(in_array($new_c['c_id'],$parent_tree['c_flat'])){
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "'.$new_c['c_outcome'].'" as its own grandchild.',
                );
            }


            //Make sure this is not a duplicate intent for its parent:
            $dup_links = $this->Db_model->cr_children_fetch(array(
                'cr_parent_c_id'  => intval($c_id),
                'cr_child_c_id' => $new_c['c_id'],
            ));
            if(count($dup_links)>0){
                //What is the status? If achived, we can bring back to life!
                if($dup_links[0]['cr_status']<0){
                    //Yes, we can bring back to life!
                    //TODO update old link here?
                } else {
                    //Ooops, this is a duplicate!
                    return array(
                        'status' => 0,
                        'message' => '['.$new_c['c_outcome'].'] is already linked here.',
                    );
                    //TODO maybe trigger a notice to admin on how to not add duplicates!
                }
            } elseif($new_c['c_id']==$c_id){
                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "'.$new_c['c_outcome'].'" as its own child.',
                );
            }

            //Remove orphan status if that was the case before:
            if(intval($new_c['c__is_orphan'])){
                $this->Db_model->c_update( $new_c['c_id'] , array(
                    'c__is_orphan' => 0,
                ));
            }

            //Prepare recursive update:
            $recursive_query = array(
                'c__tree_all_count' => $new_c['c__tree_all_count'],
                'c__tree_max_hours' => number_format($new_c['c__tree_max_hours'],3),
                'c__tree_messages' => $new_c['c__tree_messages'],
            );
        }


        //Create Link:
        $relation = $this->Db_model->cr_create(array(
            'cr_parent_u_id' => $parent_u_id,
            'cr_parent_c_id'  => intval($c_id),
            'cr_child_c_id' => $new_c['c_id'],
            'cr_child_rank' => 1 + $this->Db_model->max_value('tb_intent_links','cr_child_rank', array(
                    'cr_status >=' => 1,
                    'c_status >=' => 0,
                    'cr_parent_c_id' => intval($c_id),
                )),
        ));

        //Update tree count from parent and above:
        $updated_recursively = $this->Db_model->c_update_tree($c_id, $recursive_query);





        //Log Engagement for new link:
        $this->Db_model->e_create(array(
            'e_parent_u_id' => $parent_u_id,
            'e_value' => 'Linked intent ['.$new_c['c_outcome'].'] as child of intent ['.$parent_intents[0]['c_outcome'].']',
            'e_json' => array(
                'input' => $_POST,
                'before' => null,
                'after' => $relation,
                'recursive_query' => $recursive_query,
                'updated_recursively' => $updated_recursively,
            ),
            'e_parent_c_id' => 23, //New Intent Link
            'e_cr_id' => $relation['cr_id'],
        ));

        $relations = $this->Db_model->cr_children_fetch(array(
            'cr.cr_id' => $relation['cr_id'],
        ));

        //Return result:
        return array(
            'status' => 1,
            'c_id' => $new_c['c_id'],
            'c__tree_max_hours' => $new_c['c__tree_max_hours'],
            'adjusted_c_count' => intval($new_c['c__tree_all_count']),
            'html' => echo_c(array_merge($new_c,$relations[0]),$next_level,intval($c_id)),
        );
    }

    function w_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('w_child_u_id','w_c_id'))){
            return false;
        }

        if(!isset($insert_columns['w_timestamp'])){
            $insert_columns['w_timestamp'] = date("Y-m-d H:i:s");
        }
        if(!isset($insert_columns['w_status'])){
            $insert_columns['w_status'] = 1;
        }
        if(!isset($insert_columns['w_parent_u_id'])){
            $insert_columns['w_parent_u_id'] = 0; //No coach assigned
        }

        if(!isset($insert_columns['w_c_rank'])){
            //Place this new action plan after the last one the user currently has:
            $insert_columns['w_c_rank'] = 1 + $this->Db_model->max_value('tb_actionplans','w_c_rank', array(
                'w_status >=' => 1, //Anything they are working on...
                'w_child_u_id' => $insert_columns['w_child_u_id'],
            )); //No coach assigned
        }

        //Lets now add:
        $this->db->insert('tb_actionplans', $insert_columns);

        //Fetch inserted id:
        $insert_columns['w_id'] = $this->db->insert_id();

        if($insert_columns['w_id']>0){

            //Now let's create a cache of the Action Plan for this subscription:
            $tree = $this->Db_model->c_recursive_fetch($insert_columns['w_c_id'], true, false, $insert_columns['w_id']);

            if(count($tree['cr_flat'])>0){

                $intent = end($tree['tree_top']);

            } else {

                //This would happen if the user subscribes to an intent without any children...
                //This should not happen, inform user and log error:
                $this->Comm_model->send_message(array(
                    array(
                        'e_parent_u_id' => 2738, //Initiated by PA
                        'e_child_u_id' => $insert_columns['w_child_u_id'],
                        'e_child_c_id' => $insert_columns['w_c_id'],
                        'i_message' => 'Subscription failed',
                    ),
                ));

            }

            //Log subscription engagement:
            $this->Db_model->e_create(array(
                'e_parent_u_id' => $insert_columns['w_child_u_id'],
                'e_child_u_id' => $insert_columns['w_child_u_id'],
                'e_json' => $insert_columns,
                'e_parent_c_id' => 7465, //Subscribed
                'e_w_id' => $insert_columns['w_id'],
                'e_child_c_id' => $insert_columns['w_c_id'],
            ));

            //Return results:
            return $insert_columns;

        } else {
            return false;
        }
    }


	
	/* ******************************
	 * Users
	 ****************************** */
	
	function u_fetch($match_columns, $join_objects=array(), $limit_row=0, $limit_offset=0, $order_columns=array(
        'u__e_score' => 'DESC',
    )){
	    //Fetch the target entities:
	    $this->db->select('*');
	    $this->db->from('tb_entities u');
	    $this->db->join('tb_entity_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0
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


	    //Now fetch parents:
        foreach($res as $key=>$val){

            if(in_array('u__children_count',$join_objects)){
                //Fetch the messages for this entity:
                $res[$key]['u__children_count'] = count($this->Db_model->ur_children_fetch(array(
                    'ur_parent_u_id' => $val['u_id'],
                    'ur_status >=' => 0, //Pending or Active
                    'u_status >=' => 0, //Pending or Active
                )));
            }


            if(in_array('u__urls',$join_objects)){
                //Fetch the messages for this entity:
                $res[$key]['u__urls'] = $this->Db_model->x_fetch(array(
                    'x_status >' => -2,
                    'x_u_id' => $val['u_id'],
                ), array(), array(
                    'x_type' => 'ASC'
                ));
            }

            if(in_array('u__ws',$join_objects)){
                //Fetch the subscriptions for this entity:
                $res[$key]['u__ws'] = $this->Db_model->w_fetch(array(
                    'w_child_u_id' => $val['u_id'],
                    'w_status IN (1,2)' => null, //Active subscriptions (Passive ones have a more targetted distribution)
                ), array('c'), array(
                    'w_last_heard' => 'ASC'
                ));
            }


            //Fetch the messages for this entity:
            $res[$key]['u__parents'] = array();
            if(!in_array('skip_u__parents',$join_objects)){
                $parents = $this->Db_model->ur_parent_fetch(array(
                    'ur_child_u_id' => $val['u_id'],
                    'ur_status >=' => 0, //Pending or Active
                    'u_status >=' => 0, //Pending or Active
                ));
                foreach($parents as $ur){
                    $res[$key]['u__parents'][$ur['u_id']] = $ur;
                }
            }
        }

        return $res;
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

        //Check subscriptions:
        $ws = $this->Db_model->w_fetch(array(
            'w_c_id' => $c_id,
            'w_status >=' => 0,
        ));
        if(count($ws)>0){
            return array(
                'status' => 0,
                'message' => 'Cannot delete because there are '.count($ws).' subscriptions',
                'ws' => $ws,
                'c' => $intents[0],
            );
        }

        $archive_stats = array();

        //Start removal process by deleting engagements:
        $this->db->query("DELETE FROM tb_engagements WHERE e_parent_c_id=".$c_id." OR e_child_c_id=".$c_id);
        $archive_stats['tb_engagements'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_intent_messages WHERE i_c_id=".$c_id);
        $archive_stats['tb_intent_messages'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_actionplans WHERE w_c_id=".$c_id);
        $archive_stats['tb_actionplans'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_intents WHERE c_id=".$c_id);
        $archive_stats['tb_intents'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_intent_links WHERE (cr_parent_c_id=".$c_id." OR cr_child_c_id=".$c_id.")");
        $archive_stats['tb_intent_links'] = $this->db->affected_rows();

        return array(
            'status' => 1,
            'stats' => $archive_stats,
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
        } elseif(array_key_exists(1281, $users[0]['u__parents']) ){
            return array(
                'status' => 0,
                'message' => 'Cannot delete Admin',
                'user' => $users[0],
            );
        }


        //Check subscriptions:
        $ws = $this->Db_model->w_fetch(array(
            '(w_parent_u_id='.$u_id.' OR w_child_u_id='.$u_id.')' => null,
            'w_status >=' => 0,
        ));
        if(count($ws)>0){
            return array(
                'status' => 0,
                'message' => 'Cannot delete because there are '.count($ws).' active subscriptions',
                'ws' => $ws,
                'u' => $users[0],
            );
        }

        $archive_stats = array();

        //Start removal process by deleting engagements:
        $this->db->query("DELETE FROM tb_engagements WHERE e_parent_u_id=".$u_id." OR e_child_u_id=".$u_id);
        $archive_stats['tb_engagements'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_intent_messages WHERE i_u_id=".$u_id);
        $archive_stats['tb_intent_messages'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_actionplans WHERE w_child_u_id=".$u_id);
        $archive_stats['tb_actionplans'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_entity_urls WHERE x_u_id=".$u_id);
        $archive_stats['tb_entity_urls'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_entities WHERE u_id=".$u_id);
        $archive_stats['tb_entities'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_entity_links WHERE (ur_parent_u_id=".$u_id." OR ur_child_u_id=".$u_id.")");
        $archive_stats['tb_entity_links'] = $this->db->affected_rows();

        return array(
            'status' => 1,
            'stats' => $archive_stats,
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
		$this->db->insert('tb_entities', $insert_columns);

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
	    $this->db->update('tb_entities', $update_columns);

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
        $this->db->from('tb_intent_messages i');
        $this->db->join('tb_intents c', 'i.i_c_id = c.c_id');
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
        if(!isset($insert_columns['i_c_id'])){
            $this->Db_model->e_create(array(
                'e_value' => 'A new message requires either an Entity or Intent to be referenced to',
                'e_json' => $insert_columns,
                'e_parent_c_id' => 8, //Platform Error
            ));
            return false;
        }

        //Other required fields:
        if(missing_required_db_fields($insert_columns,array('i_message'))){
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

        if(!isset($insert_columns['i_u_id'])){
            //Describes an entity:
            $insert_columns['i_u_id'] = 0;
        }
        if(!isset($insert_columns['i_c_id'])){
            //Describes an entity:
            $insert_columns['i_c_id'] = 0;
        }


		//Lets now add:
		$this->db->insert('tb_intent_messages', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['i_id'] = $this->db->insert_id();
		
		return $insert_columns;
	}
	
	function i_update($id,$update_columns){
		$this->db->where('i_id', $id);
		$this->db->update('tb_intent_messages', $update_columns);
		return $this->db->affected_rows();
	}





    function k_fetch($match_columns, $join_objects=array(), $order_columns=array(), $limit=0, $select='*', $group_by=null){
        //Fetch the target gems:
        $this->db->select($select);
        $this->db->from('tb_actionplan_links k');

        if(in_array('cr',$join_objects)){

            $this->db->join('tb_intent_links cr', 'k.k_cr_id = cr.cr_id');

            if(in_array('cr_c_child',$join_objects)){
                //Also join with subscription row:
                $this->db->join('tb_intents c', 'c.c_id = cr.cr_child_c_id');
            } elseif(in_array('cr_c_parent',$join_objects)){
                //Also join with subscription row:
                $this->db->join('tb_intents c', 'c.c_id = cr.cr_parent_c_id');
            }
        }

        if(in_array('w',$join_objects)){
            //Also join with subscription row:
            $this->db->join('tb_actionplans w', 'w.w_id = k.k_w_id');

            if(in_array('w_c',$join_objects)){
                //Also join with subscription row:
                $this->db->join('tb_intents c', 'c.c_id = w.w_c_id');
            }
            if(in_array('w_u',$join_objects)){
                //Also add subscriber and their profile picture:
                $this->db->join('tb_entities u', 'u.u_id = w.w_child_u_id');
                $this->db->join('tb_entity_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0
            }
        }

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

        if(count($order_columns)>0){
            foreach($order_columns as $key=>$value){
                $this->db->order_by($key,$value);
            }
        } elseif(in_array('cr_c_child',$join_objects)){
            //Intent links are cached upon subscription and its important to keep the same order:
            $this->db->order_by('k_cr_child_rank','ASC');
        }

        if($limit>0){
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $results = $q->result_array();

        //Return everything that was collected:
        return $results;
    }

    function w_fetch($match_columns, $join_objects=array(), $order_columns=array('w_c_rank'=>'ASC'), $limit=0){
        //Fetch the target gems:
        $this->db->select('*');
        $this->db->from('tb_actionplans w');
        if(in_array('c',$join_objects)){
            $this->db->join('tb_intents c', 'w.w_c_id = c.c_id');
        }
        if(in_array('u',$join_objects)){
            $this->db->join('tb_entities u', 'w.w_child_u_id = u.u_id');
            if(in_array('u_x',$join_objects)){
                $this->db->join('tb_entity_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0
            }
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
        $results = $q->result_array();

        if(in_array('w_stats',$join_objects)){
            //We need to append subscription stats:
            foreach($results as $key=>$value){
                //Count related items:
                $results[$key]['w_stats'] = array(
                    //Fetch intent engagements cached per subscription:
                    'k_count_undone' => count($this->Db_model->k_fetch(array(
                        'k_w_id' => $value['w_id'],
                        'k_status IN ('.join(',', $this->config->item('k_status_incomplete')).')' => null, //incomplete
                    ))),
                    'k_count_done' => count($this->Db_model->k_fetch(array(
                        'k_w_id' => $value['w_id'],
                        'k_status NOT IN ('.join(',', $this->config->item('k_status_incomplete')).')' => null, //complete
                    ))),
                    //fetch all user engagements:
                    'e_all_count' => count($this->Db_model->e_fetch(array(
                        '(e_child_u_id='.$value['w_child_u_id'].' OR e_parent_u_id='.$value['w_child_u_id'].')' => null,
                        '(e_parent_c_id NOT IN ('.join(',', $this->config->item('exclude_es')).'))' => null,
                    ), $this->config->item('max_counter'))),
                );
            }
        }


        //Return everything that was collected:
        return $results;
    }




    function c_fetch($match_columns, $children_levels=0, $join_objects=array(), $order_columns=array(), $limit=0){

        //The basic fetcher for intents
        $this->db->select('*');
        $this->db->from('tb_intents c');
        if(in_array('u',$join_objects)){
            $this->db->join('tb_entities u', 'u.u_id = c.c_parent_u_id');
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

            if(in_array('c__messages',$join_objects)){
                $intents[$key]['c__messages'] = $this->Db_model->i_fetch(array(
                    'i_c_id' => $value['c_id'],
                    'i_status >=' => 0, //Published in any form
                ));
            }

            if(in_array('c__parents',$join_objects)){
                $intents[$key]['c__parents'] = $this->Db_model->cr_parent_fetch(array(
                    'cr.cr_child_c_id' => $value['c_id'],
                    'cr.cr_status >=' => 1,
                ) , $join_objects);
            }

            if($children_levels>=1){

                //Do the first level:
                $intents[$key]['c__child_intents'] = $this->Db_model->cr_children_fetch(array(
                    'cr.cr_parent_c_id' => $value['c_id'],
                    'cr.cr_status >=' => 1,
                    'c.c_status >=' => 0,
                ) , $join_objects );


                //need more depth?
                if($children_levels>=2){
                    //Start the second level:
                    foreach($intents[$key]['c__child_intents'] as $key2=>$value2){
                        $intents[$key]['c__child_intents'][$key2]['c__child_intents'] = $this->Db_model->cr_children_fetch(array(
                            'cr.cr_parent_c_id' => $value2['c_id'],
                            'cr.cr_status >=' => 1,
                            'c.c_status >=' => 0,
                        ) , $join_objects );
                    }
                }
            }
        }

        //Return everything that was collected:
        return $intents;
    }

	
	function cr_children_fetch($match_columns,$join_objects=array()){

		//Missing anything?
		$this->db->select('*');
		$this->db->from('tb_intents c');
		$this->db->join('tb_intent_links cr', 'cr.cr_child_c_id = c.c_id');
		foreach($match_columns as $key=>$value){
            if(!is_null($value)){
                $this->db->where($key,$value);
            } else {
                $this->db->where($key);
            }
		}
		$this->db->order_by('cr.cr_child_rank','ASC');
		$q = $this->db->get();
		$return = $q->result_array();
		
		//We had anything?
		if(count($join_objects)>0){
            foreach($return as $key=>$value){
                if(in_array('c__messages',$join_objects)){
                    //Fetch Messages:
                    $return[$key]['c__messages'] = $this->Db_model->i_fetch(array(
                        'i_c_id' => $value['c_id'],
                        'i_status >=' => 0, //Published in any form
                    ));
                }
            }
		}
		
		//Return the package:
		return $return;
	}
	
	function cr_parent_fetch($match_columns,$join_objects=array()){
		//Missing anything?
		$this->db->select('*');
		$this->db->from('tb_intents c');
		$this->db->join('tb_intent_links cr', 'cr.cr_parent_c_id = c.c_id');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
        $return = $q->result_array();

        if(count($join_objects)>0){
            foreach($return as $key=>$value){

                if(in_array('c__child_intents',$join_objects)){
                    //Fetch children:
                    $return[$key]['c__child_intents'] = $this->Db_model->cr_children_fetch(array(
                        'cr.cr_parent_c_id' => $value['c_id'],
                        'cr.cr_status >=' => 0,
                        'c.c_status >=' => 0,
                    ));
                }

                if(in_array('c__messages',$join_objects)){
                    //Fetch Messages:
                    $return[$key]['c__messages'] = $this->Db_model->i_fetch(array(
                        'i_c_id' => $value['c_id'],
                        'i_status >=' => 0, //Published in any form
                    ));
                }
            }
        }

        return $return;
	}
	
	
	
	function cr_update($id,$update_columns,$column='cr_id'){
		$this->db->where($column, $id);
		$this->db->update('tb_intent_links', $update_columns);
		return $this->db->affected_rows();
	}
	
	
	function max_value($table,$column,$match_columns){
		$this->db->select('MAX('.$column.') as largest');
		if($table=='tb_intent_links'){
		    //This is a HACK :D
            $this->db->from('tb_intent_links cr');
            $this->db->join('tb_intents c', 'cr.cr_child_c_id = c.c_id');
        } else {
            $this->db->from($table);
        }
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
		$stats = $q->row_array();
		if(count($stats)>0){
            return intval($stats['largest']);
        } else {
		    //Nothing found:
            return 0;
        }
	}



    function cr_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('cr_child_c_id','cr_parent_c_id','cr_parent_u_id'))){
            return false;
        }

        if(!isset($insert_columns['cr_timestamp'])){
            $insert_columns['cr_timestamp'] = date("Y-m-d H:i:s");
        }

        if(!isset($insert_columns['cr_status'])){
            $insert_columns['cr_status'] = 1;
        }
        if(!isset($insert_columns['cr_child_rank'])){
            $insert_columns['cr_child_rank'] = 1;
        }

        //Lets now add:
        $this->db->insert('tb_intent_links', $insert_columns);

        //Fetch inserted id:
        $insert_columns['cr_id'] = $this->db->insert_id();

        return $insert_columns;
    }


    function ur_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('ur_child_u_id','ur_parent_u_id'))){
            return false;
        }

        if(!isset($insert_columns['ur_timestamp'])){
            $insert_columns['ur_timestamp'] = date("Y-m-d H:i:s");
        }

        if(!isset($insert_columns['ur_notes']) || strlen(trim($insert_columns['ur_notes']))<1){
            $insert_columns['ur_notes'] = null;
        }

        if(!isset($insert_columns['ur_status'])){
            $insert_columns['ur_status'] = 1; //Live link
        }

        //Lets now add:
        $this->db->insert('tb_entity_links', $insert_columns);

        //Fetch inserted id:
        $insert_columns['ur_id'] = $this->db->insert_id();

        return $insert_columns;
    }

    function ur_update($id,$update_columns){
        //Update first
        $this->db->where('ur_id', $id);
        $this->db->update('tb_entity_links', $update_columns);
        return $this->db->affected_rows();
    }

    function ur_archive($id){
        //Update status:
        $this->Db_model->ur_update($id, array(
            'ur_status' => -1,
        ));
        return $this->db->affected_rows();
    }


    function x_sync($x_url,$x_u_id,$cad_edit,$accept_existing_url=false) {

        //Auth user and check required variables:
        $udata = auth(array(1308));
        $x_url = trim($x_url);

        if(!$udata){
            return array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            );
        } elseif(!isset($x_u_id)){
            return array(
                'status' => 0,
                'message' => 'Missing Child Entity ID',
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
        $children_us = $this->Db_model->u_fetch(array(
            'u_id' => $x_u_id,
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
                    'u' => array_merge($children_us[0],$dup_urls[0]),
                );
            } elseif($dup_urls[0]['u_id']==$x_u_id){
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
        } elseif(count($children_us)<1) {
            return array(
                'status' => 0,
                'message' => 'Invalid Child Entity ID ['.$x_u_id.']',
            );
        }


        if($x_u_id==1326){ //Content

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
                'e_parent_u_id' => $udata['u_id'],
                'e_child_u_id' => $new_content['u_id'],
                'e_parent_c_id' => 6971, //Entity Created
            ));

            //Place this new entity in $x_u_id [Content]
            $ur1 = $this->Db_model->ur_create(array(
                'ur_child_u_id' => $new_content['u_id'],
                'ur_parent_u_id' => $x_u_id,
            ));

            //Log Engagement new entity link:
            $this->Db_model->e_create(array(
                'e_parent_u_id' => $udata['u_id'],
                'e_ur_id' => $ur1['ur_id'],
                'e_parent_c_id' => 7291, //Entity Link Create
            ));

        } else {
            $new_content = $children_us[0];
            $ur1 = array();
        }


        //All good, Save URL:
        $new_x = $this->Db_model->x_create(array(
            'x_parent_u_id' => $udata['u_id'],
            'x_u_id' => $new_content['u_id'],
            'x_url' => $x_url,
            'x_http_code' => $curl['httpcode'],
            'x_clean_url' => ($curl['clean_url'] ? $curl['clean_url'] : $x_url),
            'x_type' => $curl['x_type'],
            'x_status' => ( $curl['url_is_broken'] ? -1 : 1 ), //Either Published or Seems Broken
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
            'e_parent_c_id' => 6911, //URL Detected Live
            'e_parent_u_id' => $udata['u_id'],
            'e_child_u_id' => $new_content['u_id'],
            'e_x_id' => $new_x['x_id'],
        ));
        $this->Db_model->e_create(array(
            'e_json' => $new_x,
            'e_parent_c_id' => 6910, //URL Added
            'e_parent_u_id' => $udata['u_id'],
            'e_child_u_id' => $new_content['u_id'],
            'e_x_id' => $new_x['x_id'],
        ));


        //Is this a image for an entity without a cover letter? If so, set this as the default:
        $set_cover_x_id = ( !$children_us[0]['u_cover_x_id'] && $new_x['x_type']==4 /* Image file */ ? $new_x['x_id'] : 0 );


        //Update Algolia:
        $this->Db_model->algolia_sync('u',$new_content['u_id']);


        if($x_u_id==1326){

            //Return entity object:
            return array(
                'status' => 1,
                'message' => 'Success',
                'curl' => $curl,
                'u' => array_merge($new_content,$ur1),
                'set_cover_x_id' => $set_cover_x_id,
                'new_u' => ( $accept_existing_url ? null : echo_u(array_merge($new_content,$ur1), 2) ),
            );

        } else {

            //Return URL object:
            return array(
                'status' => 1,
                'message' => 'Success',
                'curl' => $curl,
                'u' => $children_us[0],
                'set_cover_x_id' => $set_cover_x_id,
                'new_x' => echo_x($children_us[0], $new_x),
            );

        }
    }




    function ur_children_fetch($match_columns, $join_objects=array(), $limit=0, $limit_offset=0, $select='*', $group_by=null, $order_columns=array(
        'u.u__e_score' => 'DESC',
    )){

        //Missing anything?
        $this->db->select($select);
        $this->db->from('tb_entities u');
        $this->db->join('tb_entity_links ur', 'ur.ur_child_u_id = u.u_id');
        $this->db->join('tb_entity_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0
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


        if(in_array('u__children_count',$join_objects)){
            foreach($res as $key=>$val){
                //Fetch the messages for this entity:
                $res[$key]['u__children_count'] = count($this->Db_model->ur_children_fetch(array(
                    'ur_parent_u_id' => $val['u_id'],
                    'ur_status >=' => 0, //Pending or Active
                    'u_status >=' => 0, //Pending or Active
                )));
            }
        }

        if(in_array('u__parents',$join_objects)){
            foreach($res as $key=>$val){
                //Fetch the messages for this entity:
                $res[$key]['u__parents'] = array();
                $parents = $this->Db_model->ur_parent_fetch(array(
                    'ur_child_u_id' => $val['u_id'],
                    'ur_status >=' => 0, //Pending or Active
                    'u_status >=' => 0, //Pending or Active
                ));

                foreach($parents as $ur){
                    $res[$key]['u__parents'][$ur['u_id']] = $ur;
                }

            }
        }

        return $res;
    }

    function ur_parent_fetch($match_columns, $join_objects=array()){
        //Missing anything?
        $this->db->select('*');
        $this->db->from('tb_entities u');
        $this->db->join('tb_entity_links ur', 'ur.ur_parent_u_id = u.u_id');
        $this->db->join('tb_entity_urls x', 'x.x_id = u.u_cover_x_id','left'); //Fetch the cover photo if >0
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







	function c_update($id,$update_columns){
	    $this->db->where('c_id', $id);
	    $this->db->update('tb_intents', $update_columns);

        //Update Algolia:
        $this->Db_model->algolia_sync('c',$id);

	    return $this->db->affected_rows();
	}
	


	
	function e_update($id,$update_columns){
	    $this->db->where('e_id', $id);
	    $this->db->update('tb_engagements', $update_columns);
	    return $this->db->affected_rows();
	}
	



	function c_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('c_outcome','c_parent_u_id'))){
            return false;
        }

        if(!isset($insert_columns['c_timestamp'])){
            $insert_columns['c_timestamp'] = date("Y-m-d H:i:s");
        }
        if(!isset($insert_columns['c_status'])){
            $insert_columns['c_status'] = 1;
        }
		
		//Lets now add:
		$this->db->insert('tb_intents', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['c_id'] = ( isset($insert_columns['c_id']) ? $insert_columns['c_id'] : $this->db->insert_id() );

        //Update Algolia:
        $this->Db_model->algolia_sync('c',$insert_columns['c_id']);
		
		return $insert_columns;
	}
	
	
	/* ******************************
	 * Other
	 ****************************** */

    function x_fetch($match_columns, $join_objects=array(), $order_columns=array(), $limit=0){
        //Fetch the target entities:
        $this->db->select('*');
        $this->db->from('tb_entity_urls x');
        if(in_array('u',$join_objects)){
            $this->db->join('tb_entities u', 'u.u_id=x.x_u_id','left');
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
        $this->db->update('tb_entity_urls', $update_columns);
        return $this->db->affected_rows();
    }

    function x_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('x_url','x_clean_url','x_type','x_parent_u_id','x_u_id','x_status'))){
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

            if($insert_columns['x_u_id']==$urls[0]['x_u_id']){

                //For same object, we're all good, return this URL:
                return $urls[0];

            } else {

                //Save this engagement as we have an issue here...
                $this->Db_model->e_create(array(
                    'e_parent_u_id' => $insert_columns['x_parent_u_id'],
                    'e_child_u_id' => $insert_columns['x_u_id'],
                    'e_parent_c_id' => 8, //System error
                    'e_value' => 'x_create() found a duplicate URL ID ['.$urls[0]['x_id'].']',
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

        if(!isset($insert_columns['x_http_code'])){
            $insert_columns['x_http_code'] = 200; //As the URL was just added
        }


        //Lets now add:
        $this->db->insert('tb_entity_urls', $insert_columns);

        //Fetch inserted id:
        $insert_columns['x_id'] = $this->db->insert_id();

        return $insert_columns;
    }



	function e_fetch($match_columns=array(), $limit=100, $join_objects=array(), $replace_key=null, $order_columns=array(
        'e.e_timestamp' => 'DESC',
    ), $select='*', $group_by=null){
	    $this->db->select($select);
	    $this->db->from('tb_engagements e');
	    if(!$group_by){
            $this->db->join('tb_intents c', 'c.c_id=e.e_parent_c_id');
            $this->db->join('tb_entities u', 'u.u_id=e.e_parent_u_id','left');
        }
        if(in_array('ej',$join_objects)){
            $this->db->join('tb_engagement_blobs ej', 'ej.ej_e_id=e.e_id','left');
        }
        if(in_array('c__messages',$join_objects)){
            $this->db->join('tb_intent_messages i', 'i.i_id=e.e_i_id','left');
        }
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
                        'e_value' => 'e_fetch() was asked to replace array key with ['.$replace_key.'] and found a duplicate key value ['.$val[$replace_key].']',
                        'e_json' => $val,
                        'e_parent_c_id' => 8, //Platform Error
                    ));
                }
            }
        }

        //Return results:
        return $res;
	}
	
	
	
	function e_create($insert_columns){

        if(missing_required_db_fields($insert_columns,array('e_parent_c_id'))){
            return false;
        }
	    
	    //Try to auto detect user:
	    if(!isset($insert_columns['e_parent_u_id'])){
	        //Try to fetch entity ID from user session:
	        $user_data = $this->session->userdata('user');
	        if(isset($user_data['u_id']) && intval($user_data['u_id'])>0){
	            $insert_columns['e_parent_u_id'] = $user_data['u_id'];
	        } else {
	            //Have no user:
	            $insert_columns['e_parent_u_id'] = 0;
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
        //Remove e_json from here to keep tb_engagements small and lean
        unset($insert_columns['e_json']);


        //Set some defaults:
        if(!isset($insert_columns['e_value'])){
            $insert_columns['e_value'] = null;
        }
        if(!isset($insert_columns['e_timestamp'])){
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d",($t - floor($t)) * 1000000);
            $d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
            $insert_columns['e_timestamp'] = $d->format("Y-m-d H:i:s.u");
        }
        if(!isset($insert_columns['e_status'])){
            $insert_columns['e_status'] = 2; //Auto Published
        }


        //Set some zero defaults if not set:
        foreach(array('e_child_c_id','e_child_u_id','e_parent_u_id','e_cr_id','e_i_id','e_x_id','e_ur_id') as $dz){
            if(!isset($insert_columns[$dz]) || intval($insert_columns[$dz])<1){
                $insert_columns[$dz] = 0;
            }
        }

		//Lets log:
		$this->db->insert('tb_engagements', $insert_columns);

		//Fetch inserted id:
		$insert_columns['e_id'] = $this->db->insert_id();


		if($insert_columns['e_id']>0){

		    //Did we have a blob to save?
            if($save_blob){
                //Save this in a separate field:
                $this->db->insert('tb_engagement_blobs', array(
                    'ej_e_id' => $insert_columns['e_id'],
                    'ej_e_blob' => serialize($save_blob),
                ));
            }

            //Individual subscriptions:
            foreach($this->config->item('notify_admins') as $admin_u_id=>$subscription){

                //Do not notify about own actions:
                if(intval($insert_columns['e_parent_u_id'])==$admin_u_id){
                    continue;
                }

                if(in_array($insert_columns['e_parent_c_id'],$subscription['subscription']) || in_array(0,$subscription['subscription'])){

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

                        if(strlen($engagements[0]['e_value'])>0){
                            $html_message .= '<div>'.format_e_value($engagements[0]['e_value']).'</div><br />';
                        }

                        //Lets go through all references to see what is there:
                        foreach($this->config->item('engagement_references') as $engagement_field=>$er){
                            if(intval($engagements[0][$engagement_field])>0){
                                //Yes we have a value here:
                                $html_message .= '<div>'.$er['name'].': '.echo_object($er['object_code'], $engagements[0][$engagement_field], $engagement_field, null).'</div>';
                            }
                        }

                        //Append ID:
                        $html_message .= '<div>Engagement ID: <a href="https://mench.com/adminpanel/ej_list/'.$engagements[0]['e_id'].'">#'.$engagements[0]['e_id'].'</a></div>';

                        //Send email:
                        $this->Comm_model->send_email($subscription['admin_emails'], $subject, $html_message);
                    }
                }
            }

        }
		
		//Return:
		return $insert_columns;
	}


	function c_update_tree($c_id, $c_update_columns=array(), $fetch_children=0){

	    //Will fetch the recursive tree and update
        $tree = $this->Db_model->c_recursive_fetch($c_id, $fetch_children);

        if(count($c_update_columns)==0 || count($tree['c_flat'])==0){
            return false;
        }

        //Found results, update them relative to their current value:
        $c_relative_update = 'UPDATE "tb_intents" SET';
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

    function c_recursive_fetch($c_id, $fetch_children=false, $update_c_table=false, $w_id=0, $parent_c=array(), $recursive_children=null){

        //Get core data:
        $immediate_children = array(
            'c1__tree_all_count' => 0,
            'c1__this_messages' => 0,
            'c1__tree_messages' => 0,

            'c1__tree_min_hours' => 0,
            'c1__tree_max_hours' => 0,
            'c1__tree_min_cost' => 0,
            'c1__tree_max_cost' => 0,

            'c1__tree_experts' => array(), //Expert references across all contributions
            'c1__tree_trainers' => array(), //Trainer references considering intent messages
            'c1__tree_contents' => array(), //Content types entity references on messages

            'db_updated' => 0,
            'db_queries' => array(),
            'c_flat' => array(),
            'cr_flat' => array(),
            'tree_top' => array(),
        );

        if(!$recursive_children){
            $recursive_children = $immediate_children;
        }

        //Fetch & add this item itself:
        if(isset($parent_c['cr_id'])){

            if($fetch_children){
                $cs = $this->Db_model->cr_children_fetch(array(
                    'cr.cr_id' => $parent_c['cr_id'],
                ), ( $update_c_table ? array('c__messages') : array() ));
            } else {
                $cs = $this->Db_model->cr_parent_fetch(array(
                    'cr.cr_id' => $parent_c['cr_id'],
                ), ( $update_c_table ? array('c__messages') : array() ));
            }

        } else {

            //This is the very first item that
            $cs = $this->Db_model->c_fetch(array(
                'c.c_id' => $c_id,
            ), 0, ( $update_c_table ? array('c__messages') : array() ));

        }


        //We should have found an item by now:
        if(count($cs)<1){
            return false;
        }


        //Always add intent to tree:
        array_push($immediate_children['c_flat'],intval($c_id));


        //Add the link relations before we start recursion so we can have the Tree in up-custom order:
        if(isset($cs[0]['cr_id'])){

            //Add intent link:
            array_push($immediate_children['cr_flat'],intval($cs[0]['cr_id']));

            //Are we caching an Action Plan?
            if($w_id>0){
                //Yes we are, create a cache of this link for this Action Plan:
                $this->Db_model->k_create(array(
                    'k_w_id' => $w_id,
                    'k_cr_id' => $cs[0]['cr_id'],
                    'k_cr_child_rank' => $cs[0]['cr_child_rank'],
                ));
            }

        }

        //Terminate at OR branches for Action Plan caching (when $w_id>0)
        if($w_id>0 && $cs[0]['c_is_any']){
            //return false;
        }

        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        if($fetch_children){
            $child_cs = $this->Db_model->cr_children_fetch(array(
                'cr.cr_parent_c_id' => $c_id,
                'cr.cr_status >=' => 0,
                'c.c_status >=' => 0,
            ));
        } else {
            $child_cs = $this->Db_model->cr_parent_fetch(array(
                'cr.cr_child_c_id' => $c_id,
                'cr.cr_status >=' => 0,
                'c.c_status >=' => 0,
            ));
        }


        if(count($child_cs)>0){

            //We need to determine this based on the tree AND/OR logic:
            $local_values = array(
                'c___tree_min_hours' => null,
                'c___tree_max_hours' => null,
                'c___tree_min_cost' => null,
                'c___tree_max_cost' => null,
            );

            foreach($child_cs as $c){

                if(in_array($c['c_id'],$recursive_children['c_flat'])){

                    //Ooooops, this has an error as it would result in an infinite loop:
                    return false;

                } else {

                    //Fetch children for this intent, if any:
                    $granchildren = $this->Db_model->c_recursive_fetch($c['c_id'], $fetch_children, $update_c_table, $w_id, $c, $immediate_children);

                    if(!$granchildren){
                        //There was an infinity break
                        return false;
                    }

                    //Addup children if any:
                    $immediate_children['c1__tree_all_count'] += $granchildren['c1__tree_all_count'];

                    if($cs[0]['c_is_any']){
                        //OR Branch, figure out the logic:
                        if($granchildren['c1__tree_min_hours']<$local_values['c___tree_min_hours'] || is_null($local_values['c___tree_min_hours'])){
                            $local_values['c___tree_min_hours'] = $granchildren['c1__tree_min_hours'];
                        }
                        if($granchildren['c1__tree_max_hours']>$local_values['c___tree_max_hours'] || is_null($local_values['c___tree_max_hours'])){
                            $local_values['c___tree_max_hours'] = $granchildren['c1__tree_max_hours'];
                        }
                        if($granchildren['c1__tree_min_cost']<$local_values['c___tree_min_cost'] || is_null($local_values['c___tree_min_cost'])){
                            $local_values['c___tree_min_cost'] = $granchildren['c1__tree_min_cost'];
                        }
                        if($granchildren['c1__tree_max_cost']>$local_values['c___tree_max_cost'] || is_null($local_values['c___tree_max_cost'])){
                            $local_values['c___tree_max_cost'] = $granchildren['c1__tree_max_cost'];
                        }
                    } else {
                        //AND Branch, add them all up:
                        $local_values['c___tree_min_hours'] += number_format($granchildren['c1__tree_min_hours'],4);
                        $local_values['c___tree_max_hours'] += number_format($granchildren['c1__tree_max_hours'],4);
                        $local_values['c___tree_min_cost']  += number_format($granchildren['c1__tree_min_cost'],4);
                        $local_values['c___tree_max_cost']  += number_format($granchildren['c1__tree_max_cost'],4);
                    }


                    if($update_c_table){

                        //Update DB requested:
                        $immediate_children['c1__tree_messages'] += $granchildren['c1__tree_messages'];
                        $immediate_children['db_updated'] += $granchildren['db_updated'];
                        if(!empty($granchildren['db_queries'])){
                            array_push($immediate_children['db_queries'],$granchildren['db_queries']);
                        }

                        //Addup unique experts:
                        foreach($granchildren['c1__tree_experts'] as $u_id=>$tex){
                            //Is this a new expert?
                            if(!isset($immediate_children['c1__tree_experts'][$u_id])){
                                //Yes, add them to the list:
                                $immediate_children['c1__tree_experts'][$u_id] = $tex;
                            }
                        }

                        //Addup unique trainers:
                        foreach($granchildren['c1__tree_trainers'] as $u_id=>$tet){
                            //Is this a new expert?
                            if(!isset($immediate_children['c1__tree_trainers'][$u_id])){
                                //Yes, add them to the list:
                                $immediate_children['c1__tree_trainers'][$u_id] = $tet;
                            }
                        }

                        //Addup content types:
                        foreach($granchildren['c1__tree_contents'] as $type_u_id=>$current_us){
                            foreach($current_us as $u_id=>$u_obj){
                                if(!isset($immediate_children['c1__tree_contents'][$type_u_id][$u_id])){
                                    //Yes, add them to the list:
                                    $immediate_children['c1__tree_contents'][$type_u_id][$u_id] = $u_obj;
                                }
                            }
                        }

                    }

                    array_push($immediate_children['cr_flat'],$granchildren['cr_flat']);
                    array_push($immediate_children['c_flat'],$granchildren['c_flat']);
                    array_push($immediate_children['tree_top'],$granchildren['tree_top']);
                }
            }

            //Addup the totals from this tree:
            $immediate_children['c1__tree_min_hours'] += $local_values['c___tree_min_hours'];
            $immediate_children['c1__tree_max_hours'] += $local_values['c___tree_max_hours'];
            $immediate_children['c1__tree_min_cost']  += $local_values['c___tree_min_cost'];
            $immediate_children['c1__tree_max_cost']  += $local_values['c___tree_max_cost'];
        }





        $immediate_children['c1__tree_all_count']++;
        $immediate_children['c1__tree_min_hours'] += number_format($cs[0]['c_time_estimate'],4);
        $immediate_children['c1__tree_max_hours'] += number_format($cs[0]['c_time_estimate'],4);
        $immediate_children['c1__tree_min_cost']  += number_format($cs[0]['c_cost_estimate'],4);
        $immediate_children['c1__tree_max_cost']  += number_format($cs[0]['c_cost_estimate'],4);

        //Set the data for this intent:
        $cs[0]['c1__tree_all_count'] = $immediate_children['c1__tree_all_count'];
        $cs[0]['c1__tree_min_hours'] = $immediate_children['c1__tree_min_hours'];
        $cs[0]['c1__tree_max_hours'] = $immediate_children['c1__tree_max_hours'];
        $cs[0]['c1__tree_min_cost']  = $immediate_children['c1__tree_min_cost'];
        $cs[0]['c1__tree_max_cost']  = $immediate_children['c1__tree_max_cost'];


        //Count messages only if DB updating:
        if($update_c_table){

            $cs[0]['c1__tree_experts']   = array();
            $cs[0]['c1__tree_trainers']  = array();
            $cs[0]['c1__tree_contents']  = array();

            //Count messages:
            $cs[0]['c1__this_messages'] = count($this->Db_model->i_fetch(array(
                'i_status >=' => 0,
                'i_c_id' => $c_id,
            )));
            $immediate_children['c1__tree_messages'] += $cs[0]['c1__this_messages'];
            $cs[0]['c1__tree_messages'] = $immediate_children['c1__tree_messages'];


            //See who's involved:
            $parent_ids = array();
            foreach($cs[0]['c__messages'] as $i){

                //Who are the parent authors of this message?


                if(!in_array($i['i_parent_u_id'],$parent_ids)){
                    array_push($parent_ids, $i['i_parent_u_id']);
                }


                //Check the author of this message (The trainer) in the trainer array:
                if(!isset($cs[0]['c1__tree_trainers'][$i['i_parent_u_id']])){
                    //Add the entire message which would also hold the trainer details:
                    $cs[0]['c1__tree_trainers'][$i['i_parent_u_id']] = u_essentials($i);
                }
                //How about the parent of this one?
                if(!isset($immediate_children['c1__tree_trainers'][$i['i_parent_u_id']])){
                    //Yes, add them to the list:
                    $immediate_children['c1__tree_trainers'][$i['i_parent_u_id']] = u_essentials($i);
                }


                //Does this message have any entity references?
                if($i['i_u_id']>0){


                    //Add the reference it self:
                    if(!in_array($i['i_u_id'],$parent_ids)){
                        array_push($parent_ids, $i['i_u_id']);
                    }

                    //Yes! Let's see if any of the parents/creators are industry experts:
                    $us_fetch = $this->Db_model->u_fetch(array(
                        'u_id' => $i['i_u_id'],
                    ));

                    if(isset($us_fetch[0]) && count($us_fetch[0]['u__parents'])>0){
                        //We found it, let's loop through the parents and aggregate their IDs for a single search:
                        foreach($us_fetch[0]['u__parents'] as $parent_u){

                            //Is this a particular content type?
                            if(array_key_exists($parent_u['u_id'], $this->config->item('content_types'))){
                                //yes! Add it to the list if it does not already exist:
                                if(!isset($cs[0]['c1__tree_contents'][$parent_u['u_id']][$us_fetch[0]['u_id']])){
                                    $cs[0]['c1__tree_contents'][$parent_u['u_id']][$us_fetch[0]['u_id']] = u_essentials($us_fetch[0]);
                                }

                                //How about the parent tree?
                                if(!isset($immediate_children['c1__tree_contents'][$parent_u['u_id']][$us_fetch[0]['u_id']])){
                                    $immediate_children['c1__tree_contents'][$parent_u['u_id']][$us_fetch[0]['u_id']] = u_essentials($us_fetch[0]);
                                }
                            }

                            if(!in_array($parent_u['u_id'],$parent_ids)){
                                array_push($parent_ids, $parent_u['u_id']);
                            }
                        }
                    }
                }
            }

            //Who was involved in content patternization?
            if(count($parent_ids)>0){

                //Lets make a query search to see how many of those involved are industry experts:
                $ixs = $this->Db_model->ur_children_fetch(array(
                    'ur_parent_u_id' => 3084, //Industry expert entity
                    'ur_child_u_id IN ('.join(',', $parent_ids).')' => null,
                    'ur_status >=' => 0, //Pending review or higher
                    'u_status >=' => 0, //Pending review or higher
                ), array(), 0, 0, 'u_id, u_full_name, u_intro_message, u__e_score, x_url');

                //Put unique IDs in array key for faster searching:
                foreach($ixs as $ixsu){
                    if(!isset($cs[0]['c1__tree_experts'][$ixsu['u_id']])){
                        $cs[0]['c1__tree_experts'][$ixsu['u_id']] = $ixsu;
                    }
                }
            }


            //Did we find any new industry experts?
            if(count($cs[0]['c1__tree_experts'])>0){

                //Yes, lets add them uniquely to the mother array assuming they are not already there:
                foreach($cs[0]['c1__tree_experts'] as $new_ixs){
                    //Is this a new expert?
                    if(!isset($immediate_children['c1__tree_experts'][$new_ixs['u_id']])){
                        //Yes, add them to the list:
                        $immediate_children['c1__tree_experts'][$new_ixs['u_id']] = $new_ixs;
                    }
                }
            }
        }

        array_push($immediate_children['tree_top'],$cs[0]);



        if($update_c_table){

            //Assign aggregates:
            $cs[0]['c1__tree_experts'] = $immediate_children['c1__tree_experts'];
            $cs[0]['c1__tree_trainers'] = $immediate_children['c1__tree_trainers'];
            $cs[0]['c1__tree_contents'] = $immediate_children['c1__tree_contents'];

            //Start sorting:
            if(is_array($cs[0]['c1__tree_experts']) && count($cs[0]['c1__tree_experts'])>0){
                usort($cs[0]['c1__tree_experts'], 'sortByScore');
            }
            if(is_array($cs[0]['c1__tree_trainers']) && count($cs[0]['c1__tree_trainers'])>0){
                usort($cs[0]['c1__tree_trainers'], 'sortByScore');
            }
            foreach($cs[0]['c1__tree_contents'] as $type_u_id=>$current_us){
                if(isset($cs[0]['c1__tree_contents'][$type_u_id]) && count($cs[0]['c1__tree_contents'][$type_u_id])>0){
                    usort($cs[0]['c1__tree_contents'][$type_u_id], 'sortByScore');
                }
            }

            //Update DB only if any single field is not synced:
            if(!(
                number_format($cs[0]['c1__tree_min_hours'],3)==number_format($cs[0]['c__tree_min_hours'],3) &&
                number_format($cs[0]['c1__tree_max_hours'],3)==number_format($cs[0]['c__tree_max_hours'],3) &&
                number_format($cs[0]['c1__tree_min_cost'],2)==number_format($cs[0]['c__tree_min_cost'],2) &&
                number_format($cs[0]['c1__tree_max_cost'],2)==number_format($cs[0]['c__tree_max_cost'],2) &&
                ((!$cs[0]['c__tree_experts'] && count($cs[0]['c1__tree_experts'])<1) || (serialize($cs[0]['c1__tree_experts'])==$cs[0]['c__tree_experts'])) &&
                ((!$cs[0]['c__tree_trainers'] && count($cs[0]['c1__tree_trainers'])<1) || (serialize($cs[0]['c1__tree_trainers'])==$cs[0]['c__tree_trainers'])) &&
                ((!$cs[0]['c__tree_contents'] && count($cs[0]['c1__tree_contents'])<1) || (serialize($cs[0]['c1__tree_contents'])==$cs[0]['c__tree_contents'])) &&
                $cs[0]['c1__tree_all_count']==$cs[0]['c__tree_all_count'] &&
                $cs[0]['c1__this_messages']==$cs[0]['c__this_messages'] &&
                $cs[0]['c1__tree_messages']==$cs[0]['c__tree_messages'] &&
                intval($cs[0]['c__is_orphan'])==0
            )){

                //Something was not up to date, let's update:
                $this->Db_model->c_update( $c_id , array(
                    'c__tree_min_hours' => number_format($cs[0]['c1__tree_min_hours'],3),
                    'c__tree_max_hours' => number_format($cs[0]['c1__tree_max_hours'],3),
                    'c__tree_min_cost'  => number_format($cs[0]['c1__tree_min_cost'],2),
                    'c__tree_max_cost'  => number_format($cs[0]['c1__tree_max_cost'],2),
                    'c__tree_all_count' => $cs[0]['c1__tree_all_count'],
                    'c__this_messages' => $cs[0]['c1__this_messages'],
                    'c__tree_messages' => $cs[0]['c1__tree_messages'],
                    'c__tree_experts' => ( count($cs[0]['c1__tree_experts'])>0 ? serialize($cs[0]['c1__tree_experts']) : null ),
                    'c__tree_trainers' => ( count($cs[0]['c1__tree_trainers'])>0 ? serialize($cs[0]['c1__tree_trainers']) : null ),
                    'c__tree_contents' => ( count($cs[0]['c1__tree_contents'])>0 ? serialize($cs[0]['c1__tree_contents']) : null ),
                    'c__is_orphan' => 0, //It cannot be orphan since its part of the main tree
                ));

                $immediate_children['db_updated']++;

                array_push($immediate_children['db_queries'],'['.$c_id.'] Hours:'.number_format($cs[0]['c__tree_max_hours'],3).'=>'.number_format($cs[0]['c1__tree_max_hours'],3).' / All Count:'.$cs[0]['c__tree_all_count'].'=>'.$cs[0]['c1__tree_all_count'].' / Message:'.$cs[0]['c__this_messages'].'=>'.$cs[0]['c1__this_messages'].' / Tree Message:'.$cs[0]['c__tree_messages'].'=>'.$cs[0]['c1__tree_messages'].' / Orphan:'.intval($cs[0]['c__is_orphan']).'=>0 ('.$cs[0]['c_outcome'].')');

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


    function k_recursive_fetch($w_id, $c_id, $fetch_children, $parent_c=array(), $recursive_children=null){

        //Get core data:
        $immediate_children = array(
            'c_flat' => array(),
            'cr_flat' => array(),
            'k_flat' => array(),
        );

        if(!$recursive_children && !isset($parent_c['cr_id'])){
            //First item:
            $recursive_children = $immediate_children;
            $cs = $this->Db_model->c_fetch(array(
                'c_id' => $c_id,
            ));

        } else {
            //Recursive item:
            $cs = $this->Db_model->k_fetch(array(
                'k_w_id' => $w_id,
                'k_cr_id' => $parent_c['cr_id'],
            ), array('cr', ($fetch_children ? 'cr_c_child' : 'cr_c_parent')));
        }

        //We should have found an item by now:
        if(count($cs)<1){
            return false;
        }


        //Add the link relations before we start recursion so we can have the Tree in up-custom order:
        array_push($immediate_children['c_flat'],intval($c_id));
        if(isset($cs[0]['cr_id'])){
            array_push($immediate_children['cr_flat'],intval($cs[0]['cr_id']));
            array_push($immediate_children['k_flat'],intval($cs[0]['k_id']));
        }


        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        $child_cs = $this->Db_model->k_fetch(array(
            'k_w_id' => $w_id,
            'c_status >=' => 2,
            ( $fetch_children ? 'cr_parent_c_id' : 'cr_child_c_id' ) => $c_id,
        ), array('cr',( $fetch_children ? 'cr_c_child' : 'cr_c_parent' )));


        if(count($child_cs)>0){
            foreach($child_cs as $c){

                //Fetch children for this intent, if any:
                $granchildren = $this->Db_model->k_recursive_fetch($w_id, $c['c_id'], $fetch_children, $c, $immediate_children);

                //return $granchildren;

                if(!$granchildren){
                    //There was an infinity break
                    return false;
                }

                //Addup values:
                array_push($immediate_children['cr_flat'],$granchildren['cr_flat']);
                array_push($immediate_children['k_flat'],$granchildren['k_flat']);
                array_push($immediate_children['c_flat'],$granchildren['c_flat']);
            }
        }

        //Flatten intent ID array:
        $result = array();
        array_walk_recursive($immediate_children['c_flat'],function($v, $k) use (&$result){ $result[] = $v; });
        $immediate_children['c_flat'] = $result;

        $result = array();
        array_walk_recursive($immediate_children['cr_flat'],function($v, $k) use (&$result){ $result[] = $v; });
        $immediate_children['cr_flat'] = $result;

        $result = array();
        array_walk_recursive($immediate_children['k_flat'],function($v, $k) use (&$result){ $result[] = $v; });
        $immediate_children['k_flat'] = $result;

        //Return data:
        return $immediate_children;
    }


    function algolia_sync($obj,$obj_id=0){

	    //Define the support objects indexed on algolia:
        $obj_id = intval($obj_id);

        $alg_indexes = array(
            'c' => 'alg_intents',
            'u' => 'alg_entities',
        );
        $algolia_local_tables = array(
            'c' => 'tb_intents',
            'u' => 'tb_entities',
        );

	    if(!array_key_exists($obj,$alg_indexes)){
            return array(
                'status' => 0,
                'message' => 'Invalid object ['.$obj.']',
            );
        }

        boost_power();


        if(is_dev()){
            //Do a call on live as this does not work on local:
            return json_decode(curl_html("https://mench.com/cron/algolia_sync/".$obj."/".$obj_id));
        }

        //Load algolia
        $search_index = load_php_algolia($alg_indexes[$obj]);

        if(!$obj_id){
            //Clear this index before re-creating it from scratch:
            $search_index->clearIndex();

            //Reset the local algolia IDs for this:
            $this->db->query("UPDATE ".$algolia_local_tables[$obj]." SET ".$obj."_algolia_id=0 WHERE ".$obj."_algolia_id>0");
        }

        //Prepare universal query limits:
        if($obj_id){
            $limits[$obj.'_id'] = $obj_id;
        } else {
            $limits[$obj.'_status >='] = 0; //None Archived items (we assume this means the same thing for all objects)
        }

        //Fetch item(s) for updates:
        if($obj=='c'){
            $items = $this->Db_model->c_fetch($limits);
        } elseif($obj=='u'){
            $items = $this->Db_model->u_fetch($limits);
            $parent_names = array(); //To cache names of parents
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
                $new_item['u_id'] = intval($item['u_id']); //rquired for all objects
                $new_item['u__e_score'] = intval($item['u__e_score']);
                $new_item['u_status'] = intval($item['u_status']);
                $new_item['u_full_name'] = $item['u_full_name'];
                $new_item['u_keywords'] = $item['u_intro_message'];
                $new_item['_tags'] = array();

                //Tags map parent relation:
                if(count($item['u__parents'])>0){
                    //Loop through parent entities:
                    foreach($item['u__parents'] as $u_id=>$u){
                        array_push($new_item['_tags'],'u'.$u_id);
                        if(!in_array($u_id, array(2738,1278,1326,3089,2750))){
                            $new_item['u_keywords'] .= ' '.$u['u_full_name'];
                        }
                    }
                } else {
                    //No parent entities
                    array_push($new_item['_tags'],'noparent');
                }


                //Add Entity as tag of Entity itself for search management:
                if($item['u_id']==2738){
                    array_push($new_item['_tags'],'u2738');
                }

                //Append additional information:
                $urls = $this->Db_model->x_fetch(array(
                    'x_status >' => -2,
                    'x_u_id' => $item['u_id'],
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
                $new_item['c_keywords'] = trim($item['c_trigger_statements']);
                $new_item['c_status'] = intval($item['c_status']);

                $new_item['c__tree_max_mins'] = intval($item['c__tree_max_hours']*60);
                $new_item['c__tree_min_mins'] = intval($item['c__tree_min_hours']*60);
                $new_item['c__tree_all_count'] = intval($item['c__tree_all_count']);
                $new_item['c__tree_messages'] = intval($item['c__tree_messages']);

                //Append parent intents:
                $new_item['_tags'] = array();

                $child_cs = $this->Db_model->cr_parent_fetch(array(
                    'cr.cr_child_c_id' => $item['c_id'],
                    'cr.cr_status >=' => 1,
                    'c.c_status >=' => 0,
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
                    $obj_add_message = $search_index->saveObjects($return_items);

                } else {

                    //Create new index:
                    $obj_add_message = $search_index->addObjects($return_items);

                    //Now update local database with the objectIDs:
                    if(isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs'])>0){
                        foreach($obj_add_message['objectIDs'] as $key=>$algolia_id){
                            $this->db->query("UPDATE ".$algolia_local_tables[$obj]." SET ".$obj."_algolia_id=".$algolia_id." WHERE ".$obj."_id=".$return_items[$key][$obj.'_id']);
                        }
                    }

                }

            } elseif(intval($items[0][$obj.'_algolia_id'])>0) {

                //item has been Archived locally but its still indexed on Algolia

                //Remove from algolia:
                $search_index->deleteObject($items[0][$obj.'_algolia_id']);

                //also set its algolia_id to 0 locally:
                $this->db->query("UPDATE ".$algolia_local_tables[$obj]." SET ".$obj."_algolia_id=0 WHERE ".$obj."_id=".$obj_id);

                return array(
                    'status' => 1,
                    'message' => 'Item Archived',
                );

            }

        } else {

            //Mass update request
            //All remote items have been Archived from algolia index and local algolia_ids have been set to zero
            //we're ready to create new items and update local:
            $obj_add_message = $search_index->addObjects($return_items);

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
