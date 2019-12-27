<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class READ_model extends CI_Model
{

    /*
     *
     * Player related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }

    function ln_update($id, $update_columns, $ln_creator_player_id = 0, $ln_type_player_id = 0, $ln_content = '')
    {

        if (count($update_columns) == 0) {
            return false;
        } elseif ($ln_type_player_id>0 && !in_array($ln_type_player_id, $this->config->item('en_ids_4593'))) {
            return false;
        }

        if($ln_creator_player_id > 0){
            //Fetch link before updating:
            $before_data = $this->READ_model->ln_fetch(array(
                'ln_id' => $id,
            ));
        }

        //Update metadata if needed:
        if(isset($update_columns['ln_metadata']) && is_array($update_columns['ln_metadata'])){
            $update_columns['ln_metadata'] = serialize($update_columns['ln_metadata']);
        }

        //Set content to null if defined as empty:
        if(isset($update_columns['ln_content']) && !strlen($update_columns['ln_content'])){
            $update_columns['ln_content'] = null;
        }

        //Update:
        $this->db->where('ln_id', $id);
        $this->db->update('table_read', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Log changes if successful:
        if ($affected_rows > 0 && $ln_creator_player_id > 0 && $ln_type_player_id > 0) {

            if(strlen($ln_content) == 0){
                if(in_array($ln_type_player_id, $this->config->item('en_ids_10593') /* Statement */)){

                    //Since it's a statement we want to determine the change in content:
                    if($before_data[0]['ln_content']!=$update_columns['ln_content']){
                        $ln_content .= word_change_calculator($before_data[0]['ln_content'], $update_columns['ln_content']);
                    }

                } else {

                    //Log modification link for every field changed:
                    foreach ($update_columns as $key => $value) {
                        if($before_data[0][$key]==$value){
                            continue;
                        }

                        //Now determine what type is this:
                        if($key=='ln_status_player_id'){

                            $en_all_6186 = $this->config->item('en_all_6186'); //Link Statuses
                            $ln_content .= echo_clean_db_name($key) . ' iterated from [' . $en_all_6186[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_6186[$value]['m_name'] . ']'."\n";

                        } elseif($key=='ln_type_player_id'){

                            $en_all_4593 = $this->config->item('en_all_4593'); //Link Types
                            $ln_content .= echo_clean_db_name($key) . ' iterated from [' . $en_all_4593[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_4593[$value]['m_name'] . ']'."\n";

                        } elseif(in_array($key, array('ln_parent_player_id', 'ln_child_player_id'))) {

                            //Fetch new/old player names:
                            $before_ens = $this->PLAY_model->en_fetch(array(
                                'en_id' => $before_data[0][$key],
                            ));
                            $after_ens = $this->PLAY_model->en_fetch(array(
                                'en_id' => $value,
                            ));

                            $ln_content .= echo_clean_db_name($key) . ' iterated from [' . $before_ens[0]['en_name'] . '] to [' . $after_ens[0]['en_name'] . ']' . "\n";

                        } elseif(in_array($key, array('ln_parent_blog_id', 'ln_child_blog_id'))) {

                            //Fetch new/old blog outcomes:
                            $before_ins = $this->BLOG_model->in_fetch(array(
                                'in_id' => $before_data[0][$key],
                            ));
                            $after_ins = $this->BLOG_model->in_fetch(array(
                                'in_id' => $value,
                            ));

                            $ln_content .= echo_clean_db_name($key) . ' iterated from [' . $before_ins[0]['in_title'] . '] to [' . $after_ins[0]['in_title'] . ']' . "\n";

                        } elseif(in_array($key, array('ln_content', 'ln_order'))){

                            $ln_content .= echo_clean_db_name($key) . ' iterated from [' . $before_data[0][$key] . '] to [' . $value . ']'."\n";

                        } else {

                            //Should not log updates since not specifically programmed:
                            continue;

                        }
                    }
                }
            }

            //Determine fields that have changed:
            $fields_changed = array();
            foreach ($update_columns as $key => $value) {
                if($before_data[0][$key]!=$value){
                    array_push($fields_changed, array(
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ));
                }
            }

            if(strlen($ln_content) > 0 && count($fields_changed) > 0){
                //Value has changed, log link:
                $this->READ_model->ln_create(array(
                    'ln_parent_read_id' => $id, //Link Reference
                    'ln_creator_player_id' => $ln_creator_player_id,
                    'ln_type_player_id' => $ln_type_player_id,
                    'ln_content' => $ln_content,
                    'ln_metadata' => array(
                        'ln_id' => $id,
                        'fields_changed' => $fields_changed,
                    ),
                    //Copy old values for parent/child blog/player links:
                    'ln_parent_player_id' => $before_data[0]['ln_parent_player_id'],
                    'ln_child_player_id'  => $before_data[0]['ln_child_player_id'],
                    'ln_parent_blog_id' => $before_data[0]['ln_parent_blog_id'],
                    'ln_child_blog_id'  => $before_data[0]['ln_child_blog_id'],
                ));
            }
        }

        return $affected_rows;
    }

    function ln_fetch($match_columns = array(), $join_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('ln_id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('table_read');

        //Any blog joins?
        if (in_array('in_parent', $join_objects)) {
            $this->db->join('table_blog', 'ln_parent_blog_id=in_id','left');
        } elseif (in_array('in_child', $join_objects)) {
            $this->db->join('table_blog', 'ln_child_blog_id=in_id','left');
        }

        //Any player joins?
        if (in_array('en_parent', $join_objects)) {
            $this->db->join('table_play', 'ln_parent_player_id=en_id','left');
        } elseif (in_array('en_child', $join_objects)) {
            $this->db->join('table_play', 'ln_child_player_id=en_id','left');
        } elseif (in_array('en_type', $join_objects)) {
            $this->db->join('table_play', 'ln_type_player_id=en_id','left');
        } elseif (in_array('en_creator', $join_objects)) {
            $this->db->join('table_play', 'ln_creator_player_id=en_id','left');
        }

        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        foreach ($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }

        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        return $q->result_array();
    }


    function ln_create($insert_columns, $external_sync = false)
    {

        //Set some defaults:
        if (!isset($insert_columns['ln_creator_player_id']) || intval($insert_columns['ln_creator_player_id']) < 1) {
            $insert_columns['ln_creator_player_id'] = 0;
        }

        //Only require link type:
        if (detect_missing_columns($insert_columns, array('ln_type_player_id'), $insert_columns['ln_creator_player_id'])) {
            return false;
        }

        //Unset un-allowed columns to be manually added:
        if (isset($insert_columns['ln_words'])) {
            unset($insert_columns['ln_words']);
        }

        //Clean metadata is provided:
        if (isset($insert_columns['ln_metadata']) && is_array($insert_columns['ln_metadata'])) {
            $insert_columns['ln_metadata'] = serialize($insert_columns['ln_metadata']);
        } else {
            $insert_columns['ln_metadata'] = null;
        }

        //Set some defaults:
        if (!isset($insert_columns['ln_content'])) {
            $insert_columns['ln_content'] = null;
        }


        if (!isset($insert_columns['ln_timestamp']) || is_null($insert_columns['ln_timestamp'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $insert_columns['ln_timestamp'] = $d->format("Y-m-d H:i:s.u");
        }

        if (!isset($insert_columns['ln_status_player_id'])|| is_null($insert_columns['ln_status_player_id'])) {
            $insert_columns['ln_status_player_id'] = 6176; //Link Published
        }

        //Set some zero defaults if not set:
        foreach (array('ln_child_blog_id', 'ln_parent_blog_id', 'ln_child_player_id', 'ln_parent_player_id', 'ln_parent_read_id', 'ln_external_id', 'ln_order') as $dz) {
            if (!isset($insert_columns[$dz])) {
                $insert_columns[$dz] = 0;
            }
        }


        //Determine word weight
        $insert_columns['ln_words'] = ln_type_word_count($insert_columns);

        //Lets log:
        $this->db->insert('table_read', $insert_columns);


        //Fetch inserted id:
        $insert_columns['ln_id'] = $this->db->insert_id();


        //All good huh?
        if ($insert_columns['ln_id'] < 1) {

            //This should not happen:
            $this->READ_model->ln_create(array(
                'ln_type_player_id' => 4246, //Platform Bug Reports
                'ln_creator_player_id' => $insert_columns['ln_creator_player_id'],
                'ln_content' => 'ln_create() Failed to create',
                'ln_metadata' => array(
                    'input' => $insert_columns,
                ),
            ));

            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($insert_columns['ln_parent_player_id'] > 0) {
                $algolia_sync = update_algolia('en', $insert_columns['ln_parent_player_id']);
            }

            if ($insert_columns['ln_child_player_id'] > 0) {
                $algolia_sync = update_algolia('en', $insert_columns['ln_child_player_id']);
            }

            if ($insert_columns['ln_parent_blog_id'] > 0) {
                $algolia_sync = update_algolia('in', $insert_columns['ln_parent_blog_id']);
            }

            if ($insert_columns['ln_child_blog_id'] > 0) {
                $algolia_sync = update_algolia('in', $insert_columns['ln_child_blog_id']);
            }
        }



        //See if this link type has any subscribers:
        if(in_array($insert_columns['ln_type_player_id'] , $this->config->item('en_ids_5967')) && $insert_columns['ln_type_player_id']!=5967 /* Email Sent causes endless loop */ && !is_dev_environment()){

            //Try to fetch subscribers:
            $en_all_5967 = $this->config->item('en_all_5967'); //Include subscription details
            $sub_emails = array();
            $sub_en_ids = array();
            foreach(explode(',', one_two_explode('&var_en_subscriber_ids=','', $en_all_5967[$insert_columns['ln_type_player_id']]['m_desc'])) as $subscriber_en_id){

                //Do not email the trainer themselves, as already they know about their own engagement:
                if($insert_columns['ln_type_player_id']==4246 /* Always report bugs */ || $subscriber_en_id != $insert_columns['ln_creator_player_id']){

                    //Try fetching subscribers email:
                    foreach($this->READ_model->ln_fetch(array(
                        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'en_status_player_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
                        'ln_type_player_id' => 4255, //Linked Players Text (Email is text)
                        'ln_parent_player_id' => 3288, //Mench Email
                        'ln_child_player_id' => $subscriber_en_id,
                    ), array('en_child')) as $en_email){
                        if(filter_var($en_email['ln_content'], FILTER_VALIDATE_EMAIL)){
                            //All good, add to list:
                            array_push($sub_en_ids , $en_email['en_id']);
                            array_push($sub_emails , $en_email['ln_content']);
                        }
                    }
                }
            }


            //Did we find any subscribers?
            if(count($sub_en_ids) > 0){

                //yes, start drafting email to be sent to them...

                if($insert_columns['ln_creator_player_id'] > 0){

                    //Fetch trainer details:
                    $trainer_ens = $this->PLAY_model->en_fetch(array(
                        'en_id' => $insert_columns['ln_creator_player_id'],
                    ));

                    $trainer_name = $trainer_ens[0]['en_name'];

                } else {

                    //No trainer:
                    $trainer_name = 'MENCH';

                }


                //Email Subject:
                $subject = 'Notification: '  . $trainer_name . ' ' . $en_all_5967[$insert_columns['ln_type_player_id']]['m_name'];

                //Compose email body, start with link content:
                $html_message = '<div>' . ( strlen($insert_columns['ln_content']) > 0 ? $insert_columns['ln_content'] : '<i>No link content</i>') . '</div><br />';

                $en_all_6232 = $this->config->item('en_all_6232'); //PLATFORM VARIABLES

                //Append link object links:
                foreach ($this->config->item('en_all_11081') as $en_id => $m) {

                    if (!intval($insert_columns[$en_all_6232[$en_id]['m_desc']])) {
                        continue;
                    }

                    if (in_array(6202 , $m['m_parents'])) {

                        //BLOG
                        $ins = $this->BLOG_model->in_fetch(array( 'in_id' => $insert_columns[$en_all_6232[$en_id]['m_desc']] ));
                        $html_message .= '<div>' . $m['m_name'] . ': <a href="https://mench.com/blog/' . $ins[0]['in_id'] . '" target="_parent">#'.$ins[0]['in_id'].' '.$ins[0]['in_title'].'</a></div>';

                    } elseif (in_array(6160 , $m['m_parents'])) {

                        //PLAY
                        $ens = $this->PLAY_model->en_fetch(array( 'en_id' => $insert_columns[$en_all_6232[$en_id]['m_desc']] ));
                        $html_message .= '<div>' . $m['m_name'] . ': <a href="https://mench.com/play/' . $ens[0]['en_id'] . '" target="_parent">@'.$ens[0]['en_id'].' '.$ens[0]['en_name'].'</a></div>';

                    } elseif (in_array(4367 , $m['m_parents'])) {

                        //READ
                        $html_message .= '<div>' . $m['m_name'] . ' ID: <a href="https://mench.com/read/view_json/' . $insert_columns[$en_all_6232[$en_id]['m_desc']] . '" target="_parent">'.$insert_columns[$en_all_6232[$en_id]['m_desc']].'</a></div>';

                    }

                }

                //Finally append READ ID:
                $html_message .= '<div>READ ID: <a href="https://mench.com/read/view_json/' . $insert_columns['ln_id'] . '" target="_blank">' . $insert_columns['ln_id'] . '</a></div>';

                //Inform how to change settings:
                $html_message .= '<div style="color: #DDDDDD; font-size:0.9em; margin-top:20px;">Manage your email notifications via <a href="https://mench.com/play/5967" target="_blank">@5967</a></div>';

                //Send email:
                $dispatched_email = $this->READ_model->dispatch_emails($sub_emails, $subject, $html_message);

                //Log emails sent:
                foreach($sub_en_ids as $to_en_id){
                    $this->READ_model->ln_create(array(
                        'ln_type_player_id' => 5967, //Link Carbon Copy Email
                        'ln_creator_player_id' => $to_en_id, //Sent to this user
                        'ln_metadata' => $dispatched_email, //Save a copy of email
                        'ln_parent_read_id' => $insert_columns['ln_id'], //Save link

                        //Import potential blog/player connections from link:
                        'ln_child_blog_id' => $insert_columns['ln_child_blog_id'],
                        'ln_parent_blog_id' => $insert_columns['ln_parent_blog_id'],
                        'ln_child_player_id' => $insert_columns['ln_child_player_id'],
                        'ln_parent_player_id' => $insert_columns['ln_parent_player_id'],
                    ));
                }

            }

        }





        //See if this is a Link Blog Subscription Types?
        $related_blogs = array();
        if($insert_columns['ln_child_blog_id'] > 0){
            array_push($related_blogs, $insert_columns['ln_child_blog_id']);
        }
        if($insert_columns['ln_parent_blog_id'] > 0){
            array_push($related_blogs, $insert_columns['ln_parent_blog_id']);
        }


        //Return:
        return $insert_columns;

    }

    function ln_max_order($match_columns)
    {

        //Counts the current highest order value
        $this->db->select('MAX(ln_order) as largest_order');
        $this->db->from('table_read');
        foreach ($match_columns as $key => $value) {
            $this->db->where($key, $value);
        }
        $q = $this->db->get();
        $stats = $q->row_array();
        if (count($stats) > 0) {
            return intval($stats['largest_order']);
        } else {
            //Nothing found:
            return 0;
        }
    }







    function read__completion_auto_complete($en_id, $in, $unlock_link_type_en_id){

        /*
         *
         * TODO: DEPRECATE and replace with read_echo()
         *
         * A function that marks a BLOG as complete IF
         * the blog has nothing of substance to be
         * further communicated/done by the user.
         *
         * $unlock_link_type_en_id Indicates the type of Unlocking that is about to happen
         *
         * */

        //Send messages, if any:
        foreach ($this->READ_model->ln_fetch(array(
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_player_id' => 4231, //Blog Note Messages
            'ln_child_blog_id' => $in['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC')) as $message_ln) {
            $this->READ_model->dispatch_message(
                $message_ln['ln_content'],
                array('en_id' => $en_id),
                true
            );
        }

        //Ok, now we can mark it as complete:
        $this->READ_model->ln_create(array(
            'ln_type_player_id' => ( in_is_unlockable($in) ? $unlock_link_type_en_id : 4559 /* User Read Read Messages */ ),
            'ln_creator_player_id' => $en_id,
            'ln_parent_blog_id' => $in['in_id'],
            'ln_status_player_id' => 6176, //Link Published
        ));


        //Process on-complete automations:
        $this->READ_model->read__completion_checks($en_id, $in, true);


        //All good:
        return true;
    }


    function read_next_find($en_id, $in){

        /*
         *
         * Searches within a user 🔴 READING LIST to find
         * first incomplete step.
         *
         * */

        $in_metadata = unserialize($in['in_metadata']);

        //Make sure of no terminations first:
        $check_termination_answers = array();

        if(count($in_metadata['in__metadata_expansion_steps']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($in_metadata['in__metadata_expansion_steps']));
        }
        if(count($in_metadata['in__metadata_expansion_conditional']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($in_metadata['in__metadata_expansion_conditional']));
        }
        if(count($check_termination_answers) > 0 && count($this->READ_model->ln_fetch(array(
                'ln_type_player_id' => 7492,
                'ln_creator_player_id' => $en_id, //Belongs to this User
                'ln_parent_blog_id IN (' . join(',' , $check_termination_answers) . ')' => null, //All possible answers that might terminate...
                'ln_status_player_id' => 6176, //Link Published
            ))) > 0){
            return -1;
        }



        foreach(array_flatten($in_metadata['in__metadata_common_steps']) as $common_step_in_id){

            //Is this an expansion step?
            $is_expansion = isset($in_metadata['in__metadata_expansion_steps'][$common_step_in_id]);
            $is_condition = isset($in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]);

            //Is this completed?
            $completed_steps = $this->READ_model->ln_fetch(array(
                'ln_type_player_id IN (' . join(',' , $this->config->item('en_ids_6146')) . ')' => null, //🔴 READING LIST Progression Reads
                'ln_creator_player_id' => $en_id, //Belongs to this User
                'ln_parent_blog_id' => $common_step_in_id,
                'ln_status_player_id' => 6176, //Link Published
            ),  ( $is_expansion ? array('in_child') : array() ));

            //Have they completed this?
            if(count($completed_steps) == 0){

                //Not completed yet, this is the next step:
                return $common_step_in_id;

            } elseif($is_expansion){

                //Completed step that has OR expansions, check recursively to see if next step within here:
                $found_in_id = $this->READ_model->read_next_find($en_id, $completed_steps[0]);

                if($found_in_id != 0){
                    return $found_in_id;
                }

            } elseif($is_condition){

                //See which path they got unlocked, if any:
                $unlocked_conditions = $this->READ_model->ln_fetch(array(
                    'ln_type_player_id' => 6140, //🔴 READING LIST Conditional Read Unlocked
                    'ln_creator_player_id' => $en_id, //Belongs to this User
                    'ln_parent_blog_id' => $common_step_in_id,
                    'ln_child_blog_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]) . ')' => null,
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                ), array('in_child'));

                if(count($unlocked_conditions) > 0){

                    //Completed step that has OR expansions, check recursively to see if next step within here:
                    $found_in_id = $this->READ_model->read_next_find($en_id, $unlocked_conditions[0]);

                    if($found_in_id != 0){
                        return $found_in_id;
                    }
                }
            }
        }


        //Nothing found!
        return 0;

    }

    function read_next_go($en_id, $advance_step, $send_title_message = false)
    {

        /*
         *
         * Searches for the next 🔴 READING LIST step
         * and advance it IF $advance_step = TRUE
         *
         * */

        $user_blogs = $this->READ_model->ln_fetch(array(
            'ln_creator_player_id' => $en_id,
            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

        if(count($user_blogs) == 0){

            if($advance_step){

                $this->READ_model->dispatch_message(
                    'You have no blogs in your reading list yet.',
                    array('en_id' => $en_id),
                    true
                );

                //READ RECOMMENDATIONS
                $this->READ_model->dispatch_message(
                    echo_random_message('read_recommendation'),
                    array('en_id' => $en_id),
                    true
                );

            }

            //No 🔴 READING LISTs found!
            return 0;

        }


        //Loop through 🔴 READING LIST blogs and see what's next:
        foreach($user_blogs as $user_blog){

            //Find first incomplete step for this 🔴 READING LIST blog:
            $next_in_id = $this->READ_model->read_next_find($en_id, $user_blog);

            if($next_in_id < 0){

                //We need to terminate this:
                $this->READ_model->read_delete($en_id, $user_blog['in_id'], 7757); //MENCH REMOVED BOOKMARK
                break;

            } elseif($next_in_id > 0){

                //We found the next incomplete step, return:
                break;

            }
        }

        if($advance_step && $next_in_id >= 0 /* NOT If it was terminated... */){

            //Did we find a next step?
            if($next_in_id > 0){

                if($send_title_message){

                    //Fetch and append the title to be more informative:

                    //Yes, we do have a next step, fetch it and give user more details:
                    $next_step_ins = $this->BLOG_model->in_fetch(array(
                        'in_id' => $next_in_id,
                    ));

                    $this->READ_model->dispatch_message(
                        echo_random_message('next_blog_is') . $next_step_ins[0]['in_title'],
                        array('en_id' => $en_id),
                        true
                    );

                }

                //Yes, communicate it:
                $this->READ_model->read_echo($next_in_id, array('en_id' => $en_id), true);

            } else {

                //Inform user that they are now complete with all steps:
                $this->READ_model->dispatch_message(
                    'You completed reading your entire 🔴 READING LIST',
                    array('en_id' => $en_id),
                    true
                );

                //READ RECOMMENDATIONS
                $this->READ_model->dispatch_message(
                    echo_random_message('read_recommendation'),
                    array('en_id' => $en_id),
                    true
                );

            }
        }

        //Return next step blog or false:
        return intval($next_in_id);

    }

    function read_skip_initiate($en_id, $in_id, $push_message = true){

        //Fetch this blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ));
        if(count($ins) < 1){
            $this->READ_model->ln_create(array(
                'ln_child_blog_id' => $in_id,
                'ln_content' => 'step_skip_initiate() did not locate the published blog',
                'ln_type_player_id' => 4246, //Platform Bug Reports
                'ln_creator_player_id' => $en_id,
            ));
            return false;
        }

        $metadata = unserialize($ins[0]['in_metadata']);
        if (isset($metadata['in__metadata_max_steps']) && $metadata['in__metadata_max_steps'] >= 3) {
            $skip_message = 'Are you sure you want to skip all '.$metadata['in__metadata_max_steps'].' steps?';
        } else {
            $skip_message = 'Are you sure you want to skip?';
        }


        if(!$push_message){

            //Just return the message for HTML format:
            return $skip_message;

        } else {

            //Send over messenger:
            $this->READ_model->dispatch_message(
                $skip_message,
                array('en_id' => $en_id),
                true,
                array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Yes',
                        'payload' => 'SKIP-ACTIONPLAN_skip-confirm_'.$in_id, //Confirm and skip
                    ),
                    array(
                        'content_type' => 'text',
                        'title' => 'No',
                        'payload' => 'SKIP-ACTIONPLAN_skip-cancel_'.$in_id, //Cancel skipping
                    ),
                )
            );

        }
    }

    function read_skip_apply($en_id, $in_id)
    {

        //Fetch blog common steps:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ));
        if(count($ins) < 1){
            $this->READ_model->ln_create(array(
                'ln_content' => 'step_skip_apply() failed to locate published blog',
                'ln_type_player_id' => 4246, //Platform Bug Reports
                'ln_creator_player_id' => $en_id,
                'ln_parent_blog_id' => $in_id,
            ));
            return 0;
        }


        $in_metadata = unserialize( $ins[0]['in_metadata'] );

        if(!isset($in_metadata['in__metadata_common_steps'])){
            $this->READ_model->ln_create(array(
                'ln_content' => 'step_skip_apply() failed to locate metadata common steps',
                'ln_type_player_id' => 4246, //Platform Bug Reports
                'ln_creator_player_id' => $en_id,
                'ln_parent_blog_id' => $in_id,
            ));
            return 0;
        }

        //Fetch common base and expansion paths from blog metadata:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Add 🔴 READING LIST Skipped Read Progression Links:
        foreach($flat_common_steps as $common_in_id){

            //Fetch current progression links, if any:
            $read_progress = $this->READ_model->ln_fetch(array(
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Reads Completed
                'ln_creator_player_id' => $en_id,
                'ln_parent_blog_id' => $common_in_id,
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ));

            //Add skip link:
            $new_progression_link = $this->READ_model->ln_create(array(
                'ln_type_player_id' => 6143, //🔴 READING LIST Skipped Read
                'ln_creator_player_id' => $en_id,
                'ln_parent_blog_id' => $common_in_id,
                'ln_status_player_id' => 6176, //Link Published
            ));

            //Archive current progression links:
            foreach($read_progress as $ln){
                $this->READ_model->ln_update($ln['ln_id'], array(
                    'ln_parent_read_id' => $new_progression_link['ln_id'],
                    'ln_status_player_id' => 6173, //Link Removed
                ));
            }

        }

        //Process on-complete automations:
        $this->READ_model->read__completion_checks($en_id, $ins[0], true);

        //Return number of skipped steps:
        return count($flat_common_steps);

    }

    function read_focus($en_id){

        /*
         *
         * A function that goes through the 🔴 READING LIST
         * and finds the top-priority that the user
         * is currently working on.
         *
         * */

        $top_priority_in = false;
        foreach($this->READ_model->ln_fetch(array(
            'ln_creator_player_id' => $en_id,
            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC')) as $actionplan_in){

            //See progress rate so far:
            $completion_rate = $this->READ_model->read__completion_progress($en_id, $actionplan_in);

            if($completion_rate['completion_percentage'] < 100){
                //This is the top priority now:
                $top_priority_in = $actionplan_in;
                break;
            }

        }

        if(!$top_priority_in){
            return false;
        }

        //Return what's found:
        return array(
            'in' => $top_priority_in,
            'completion_rate' => $completion_rate,
        );

    }

    function read_delete($en_id, $in_id, $stop_method_id, $stop_feedback = null){


        if(!in_array($stop_method_id, $this->config->item('en_ids_6150') /* 🔴 READING LIST Blog Completed */)){
            return array(
                'status' => 0,
                'message' => 'Invalid stop method',
            );
        }

        //Validate blog to be removed:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
        ));
        if (count($ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid blog',
            );
        }

        //Go ahead and remove from 🔴 READING LIST:
        $user_blogs = $this->READ_model->ln_fetch(array(
            'ln_creator_player_id' => $en_id,
            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_parent_blog_id' => $in_id,
        ));
        if(count($user_blogs) < 1){
            return array(
                'status' => 0,
                'message' => 'Could not locate 🔴 READING LIST',
            );
        }


        //Remove Bookmark:
        foreach($user_blogs as $ln){
            $this->READ_model->ln_update($ln['ln_id'], array(
                'ln_content' => $stop_feedback,
                'ln_status_player_id' => 6173, //ARCHIVED
            ), $en_id, $stop_method_id);
        }


        //Communicate with user:
        $this->READ_model->dispatch_message(
            'I have removed the blog '.$ins[0]['in_title'].' from your Bookmarks.',
            array('en_id' => $en_id),
            true,
            array(
                array(
                    'content_type' => 'text',
                    'title' => 'Next',
                    'payload' => 'GONEXT_',
                )
            )
        );

        return array(
            'status' => 1,
            'message' => 'Success',
        );

    }

    function read_add($en_id, $in_id, $recommender_in_id = 0){

        //Validate Blog ID:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ));
        if (count($ins) != 1) {
            return false;
        }


        //Make sure not already added to this User's 🔴 READING LIST:
        if(!count($this->READ_model->ln_fetch(array(
                'ln_creator_player_id' => $en_id,
                'ln_parent_blog_id' => $in_id,
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            )))){

            //Not added to their reading list so far, let's go ahead and add it:
            $in_rank = 1;
            $actionplan = $this->READ_model->ln_create(array(
                'ln_type_player_id' => ( $recommender_in_id > 0 ? 7495 /* User Blog Recommended */ : 4235 /* User Blog Set */ ),
                'ln_creator_player_id' => $en_id, //Belongs to this User
                'ln_parent_blog_id' => $ins[0]['in_id'], //The Blog they are adding
                'ln_child_blog_id' => $recommender_in_id, //Store the recommended blog
                'ln_order' => $in_rank, //Always place at the top of their reading list
            ));


            //Move other blogs down in the reading list:
            foreach($this->READ_model->ln_fetch(array(
                'ln_id !=' => $actionplan['ln_id'], //Not the newly added blog
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_creator_player_id' => $en_id, //Belongs to this User
            ), array(''), 0, 0, array('ln_order' => 'ASC')) as $current_blogs){

                //Increase rank:
                $in_rank++;

                //Update order:
                $this->READ_model->ln_update($current_blogs['ln_id'], array(
                    'ln_order' => $in_rank,
                ), $en_id, 10681 /* Blogs Ordered Automatically  */);
            }

        }

        return true;

    }




    function read__completion_recursive_up($en_id, $in, $is_bottom_level = true){

        /*
         *
         * Function Player:
         *
         * https://mench.com/play/6410
         *
         * */


        //Let's see how many steps get unlocked:
        $unlock_steps_messages = array();


        //First let's make sure this entire blog tree completed by the user:
        $completion_rate = $this->READ_model->read__completion_progress($en_id, $in);


        if($completion_rate['completion_percentage'] < 100){
            //Not completed, so can't go further up:
            return array();
        }


        //Look at Conditional Reads ONLY at this level:
        $in_metadata = unserialize($in['in_metadata']);
        if(isset($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) && count($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) > 0){

            //Make sure previous link unlocks have NOT happened before:
            $existing_expansions = $this->READ_model->ln_fetch(array(
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_player_id' => 6140, //🔴 READING LIST Conditional Read Unlocked
                'ln_creator_player_id' => $en_id,
                'ln_parent_blog_id' => $in['in_id'],
                'ln_child_blog_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ));
            if(count($existing_expansions) > 0){

                //Oh we do have an expansion that already happened! So skip this:
                /*
                 * This was being triggered but I am not sure if its normal or not!
                 * For now will comment out so no errors are logged
                 * TODO: See if you can make sense of this section. The question is
                 * if we would ever try to process a conditional step twice? If it
                 * happens, is it an error or not, and should simply be ignored?
                 *
                $this->READ_model->ln_create(array(
                    'ln_parent_blog_id' => $in['in_id'],
                    'ln_child_blog_id' => $existing_expansions[0]['ln_child_blog_id'],
                    'ln_content' => 'completion_recursive_up() detected duplicate Label Expansion entries',
                    'ln_type_player_id' => 4246, //Platform Bug Reports
                    'ln_creator_player_id' => $en_id,
                ));
                */

                return array();

            }


            //Yes, Let's calculate user's score for this tree:
            $user_marks = $this->READ_model->read__completion_marks($en_id, $in);





            //Detect potential conditional steps to be Unlocked:
            $found_match = 0;
            $locked_links = $this->READ_model->ln_fetch(array(
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_player_id' => 4229, //Blog Link Locked Read
                'ln_parent_blog_id' => $in['in_id'],
                'ln_child_blog_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ), array('in_child'), 0, 0);


            foreach ($locked_links as $locked_link) {

                //See if it unlocks any of these ranges defined in the metadata:
                $ln_metadata = unserialize($locked_link['ln_metadata']);

                //Defines ranges:
                if(!isset($ln_metadata['tr__conditional_score_min'])){
                    $ln_metadata['tr__conditional_score_min'] = 0;
                }
                if(!isset($ln_metadata['tr__conditional_score_max'])){
                    $ln_metadata['tr__conditional_score_max'] = 0;
                }


                if($user_marks['steps_answered_score']>=$ln_metadata['tr__conditional_score_min'] && $user_marks['steps_answered_score']<=$ln_metadata['tr__conditional_score_max']){

                    //Found a match:
                    $found_match++;

                    //It did match here! Log and notify user!
                    $message = 'You completed the step to '.echo_in_title($in['in_title'], true).'. ';
                    $message .= 'The result:';
                    $message .= "\n";
                    $message .= "\n==================";
                    $message .= "\n" . echo_in_title($locked_link['in_title'], true);
                    $message .= "\n==================";


                    //Communicate message to user:
                    array_push($unlock_steps_messages, array(
                        'ln_content' => $message,
                    ));

                    //Unlock 🔴 READING LIST:
                    $this->READ_model->ln_create(array(
                        'ln_status_player_id' => 6176, //Link Published
                        'ln_type_player_id' => 6140, //🔴 READING LIST Conditional Read Unlocked
                        'ln_creator_player_id' => $en_id,
                        'ln_parent_blog_id' => $in['in_id'],
                        'ln_child_blog_id' => $locked_link['in_id'],
                        'ln_content' => $message,
                        'ln_metadata' => array(
                            'completion_rate' => $completion_rate,
                            'user_marks' => $user_marks,
                            'condition_ranges' => $locked_links,
                        ),
                    ));

                    //See if we also need to mark the child as complete:
                    $this->READ_model->read__completion_auto_complete($en_id, $locked_link, 6997 /* User Read Score Unlock */);

                }
            }

            //We must have exactly 1 match by now:
            if($found_match != 1){
                $this->READ_model->ln_create(array(
                    'ln_content' => 'completion_recursive_up() found ['.$found_match.'] routing logic matches!',
                    'ln_type_player_id' => 4246, //Platform Bug Reports
                    'ln_creator_player_id' => $en_id,
                    'ln_parent_blog_id' => $in['in_id'],
                    'ln_metadata' => array(
                        'completion_rate' => $completion_rate,
                        'user_marks' => $user_marks,
                        'conditional_ranges' => $locked_links,
                    ),
                ));
            }

        }


        //Now go up since we know there are more levels...
        if($is_bottom_level){

            //Fetch user blogs:
            $player_read_ids = $this->READ_model->read_ids($en_id);

            //Fetch all parents trees for this blog
            $recursive_parents = $this->BLOG_model->in_fetch_recursive_public_parents($in['in_id']);

            //Prevent duplicate processes even if on multiple parent trees:
            $parents_checked = array();

            //Go through parents trees and detect intersects with user blogs. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
            foreach ($recursive_parents as $grand_parent_ids) {

                //Does this parent and its grandparents have an intersection with the user blogs?
                if(!array_intersect($grand_parent_ids, $player_read_ids)){
                    //Parent tree is NOT part of their 🔴 READING LIST:
                    continue;
                }

                //Let's go through until we hit their intersection
                foreach($grand_parent_ids as $p_id){

                    //Make sure not duplicated:
                    if(in_array($p_id , $parents_checked)){
                        continue;
                    }

                    array_push($parents_checked, $p_id);

                    //Fetch parent blog:
                    $parent_ins = $this->BLOG_model->in_fetch(array(
                        'in_id' => $p_id,
                        'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
                    ));

                    //Now see if this child completion resulted in a full parent completion:
                    if(count($parent_ins) > 0){

                        //Fetch parent completion:
                        $unlock_steps_messages_recursive = $this->READ_model->read__completion_recursive_up($en_id, $parent_ins[0], false);

                        //What did we find?
                        if(count($unlock_steps_messages_recursive) > 0){
                            $unlock_steps_messages = array_merge($unlock_steps_messages, $unlock_steps_messages_recursive);
                        }
                    }

                    //Terminate if we reached the 🔴 READING LIST blog level:
                    if(in_array($p_id , $player_read_ids)){
                        break;
                    }
                }
            }
        }


        return $unlock_steps_messages;
    }

    function read__completion_checks($en_id, $in, $send_message){


        /*
         *
         * There are certain processes we need to run and messages
         * we need to compile every time an 🔴 READING LIST step/blog
         * is marked as complete. This function handles that workflow
         * with the following inputs:
         *
         * - $in_id The blog that was marked as complete
         * - $en_id The player who marked it as complete
         * - $send_message IF TRUE would send messages to $en_id and IF FASLE would return raw messages
         *
         * */


        //Try to unlock steps:
        $unlock_steps_messages = $this->READ_model->read__completion_recursive_up($en_id, $in);


        //Return all the messages:
        if($send_message){

            //Send message to user:
            foreach($unlock_steps_messages as $message){
                $this->READ_model->dispatch_message(
                    $message['ln_content'],
                    array('en_id' => $en_id),
                    true
                );
            }

            //Return the number of messages sent:
            return count($unlock_steps_messages);

        } else {

            //Return messages array:
            return $unlock_steps_messages;

        }
    }


    function read__unlock_recursive_up($in_id, $is_bottom_level = true)
    {
        /*
         *
         * Checks the completed users at each step that's recursively up
         *
         * */

        foreach($this->READ_model->ln_fetch(array(
            'in_type_player_id IN (' . join(',', $this->config->item('en_ids_7309')) . ')' => null, //🔴 READING LIST Read Locked
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public

            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_player_id' => 4228, //Blog Link Regular Read
            'ln_child_blog_id' => $in_id,
        ), array('in_parent')) as $in_locked_parent){

        }
    }

    function read__unlock_locked_step($en_id, $in){

        /*
         * A function that starts from a locked blog and checks:
         *
         * 1. List users who have completed ALL/ANY (Depending on AND/OR Lock) of its children
         * 2. If > 0, then goes up recursively to see if these completions unlock other completions
         *
         * */

        if(!in_is_unlockable($in)){
            return array(
                'status' => 0,
                'message' => 'Not a valid locked blog type and status',
            );
        }


        $in__children = $this->READ_model->ln_fetch(array(
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            'ln_type_player_id' => 4228, //Blog Link Regular Read
            'ln_parent_blog_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        if(count($in__children) < 1){
            return array(
                'status' => 0,
                'message' => 'Blog has no child blogs',
            );
        }



        /*
         *
         * Now we need to determine blog completion method.
         *
         * It's one of these two cases:
         *
         * AND Blogs are completed when all their children are completed
         *
         * OR Blogs are completed when a single child is completed
         *
         * */
        $requires_all_children = ( $in['in_type_player_id'] == 6914 /* AND Lock, meaning all children are needed */ );

        //Generate list of users who have completed it:
        $qualified_completed_users = array();

        //Go through children and see how many completed:
        foreach($in__children as $count => $child_in){

            //Fetch users who completed this:
            if($count==0){

                //Always add all the first users to the full list:
                $qualified_completed_users = $this->READ_model->ln_fetch(array(
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ PROGRESS
                    'ln_parent_blog_id' => $child_in['in_id'],
                ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

                if($requires_all_children && count($qualified_completed_users)==0){
                    //No users found that would meet all children requirements:
                    break;
                }

            } else {

                //2nd iteration onwards, by now we must have a base:
                if($requires_all_children){

                    //Update list of qualified users:
                    $qualified_completed_users = $this->READ_model->ln_fetch(array(
                        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ PROGRESS
                        'ln_parent_blog_id' => $child_in['in_id'],
                    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

                }

            }
        }

        if(count($qualified_completed_users) > 0){
            return array(
                'status' => 0,
                'message' => 'No users found to have completed',
            );
        }





    }


    function read_echo($in_id, $recipient_en, $push_message = false){

        /*
         * Function to read a Blog, it's messages,
         * and necessary inputs to complete it.
         *
         */


        //Fetch/Validate blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ));
        if (count($ins) < 1) {
            $this->READ_model->ln_create(array(
                'ln_type_player_id' => 4246, //Platform Bug Reports
                'ln_creator_player_id' => ( isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0 ),
                'ln_content' => 'step_echo() invalid blog ID',
                'ln_parent_blog_id' => $in_id,
            ));
            echo_message('Invalid Blog ID', true, $recipient_en, $push_message);
            return false;
        }


        //Validate Recipient, if specified:
        if(!isset($recipient_en['en_id'])){
            if($push_message){

                //We cannot have a guest user on Messenger:
                $this->READ_model->ln_create(array(
                    'ln_type_player_id' => 4246, //Platform Bug Reports
                    'ln_content' => 'read_echo() found guest user on Messenger',
                    'ln_parent_blog_id' => $in_id,
                ));
                return false;

            } else {

                //Guest on the web:
                $recipient_en['en_id'] = 0;
                $recipient_en['en_name'] = 'Stranger';

            }
        } elseif(!isset($recipient_en['en_name'])){

            //Fetch name:
            $ens = $this->PLAY_model->en_fetch(array(
                'en_id' => $recipient_en['en_id'],
                'en_status_player_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
            ));

            if(count($ens)){
                $recipient_en = $ens[0];
            } else {
                $this->READ_model->ln_create(array(
                    'ln_type_player_id' => 4246, //Platform Bug Reports
                    'ln_content' => 'read_echo() could not locate player',
                    'ln_parent_blog_id' => $in_id,
                ));
                return false;
            }

        }


        /*
         *
         * Display Title & Messages
         *
         */
        if(!$push_message){
            echo '<h1>' . echo_in_title($ins[0]['in_title']) . '</h1>';
        } else {
            $this->READ_model->dispatch_message(
                'You\'re reading: '.$ins[0]['in_title'],
                $recipient_en,
                $push_message
            );
        }

        $in__messages = $this->READ_model->ln_fetch(array(
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_player_id' => 4231, //Blog Note Messages
            'ln_child_blog_id' => $ins[0]['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));
        foreach ($in__messages as $message_ln) {
            echo $this->READ_model->dispatch_message(
                $message_ln['ln_content'],
                $recipient_en,
                $push_message
            );
        }



        $in_reading_list = false;
        if($recipient_en['en_id'] > 0){

            //Fetch entire reading list:
            $player_read_ids = $this->READ_model->read_ids($recipient_en['en_id']);

            if(in_array($ins[0]['in_id'], $player_read_ids)){
                $in_reading_list = true;
            } else {
                //Fetch all parents trees for this blog
                $recursive_parents = $this->BLOG_model->in_fetch_recursive_public_parents($ins[0]['in_id']);

                //Go through parents trees and detect intersects with user blogs. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
                foreach ($recursive_parents as $grand_parent_ids) {

                    //Does this parent and its grandparents have an intersection with the user blogs?
                    if (array_intersect($grand_parent_ids, $player_read_ids)) {
                        //Blog is part of their 🔴 READING LIST:
                        $in_reading_list = true;
                        break;
                    }
                }
            }
        }


        /*
         *
         * Determine next Read
         *
         */
        if(!$in_reading_list){

            //Give option to add to reading list:
            if($push_message){
                $this->READ_model->dispatch_message(
                    'Interested to read ' . $ins[0]['in_title'] . '?',
                    $recipient_en,
                    $push_message,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Start Reading',
                            'payload' => 'SUBSCRIBE-CONFIRM_' . $ins[0]['in_id'],
                        ),
                        array(
                            'content_type' => 'text',
                            'title' => 'Cancel',
                            'payload' => 'SUBSCRIBE-REJECT',
                        ),
                    ),
                    array(
                        'ln_child_blog_id' => $ins[0]['in_id'],
                    )
                );
            } elseif(!isset($_GET['autoexpand'])) {
                echo '<div style="padding-bottom:40px;" class="inline-block"><a class="btn btn-read" href="/read/'.$ins[0]['in_id'].'">START READING <i class="fas fa-angle-right"></i></a></div>';
            }

            return true;
        }



        /*
         * Already in player's reading list...
         *
         */



        /*
         *
         * List Conditional Links that are
         * already unlocked (HTML ONLY)
         *
         * */
        if(!$push_message){

            $unlocked_steps = $this->READ_model->ln_fetch(array(
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
                'ln_type_player_id' => 6140, //User Read Link Unlocked
                'ln_creator_player_id' => $recipient_en['en_id'],
                'ln_parent_blog_id' => $ins[0]['in_id'],
            ), array('in_child'), 0);

            //Did we have any steps unlocked?
            if(count($unlocked_steps) > 0){
                //Yes! Show them:
                echo '<div class="list-group" style="margin:0 0 0 0;">';
                foreach($unlocked_steps as $unlocked_step){
                    //Add HTML step to UI:
                    echo echo_actionplan_step_child($recipient_en['en_id'], $unlocked_step, true);
                }
                echo '</div>';
            }

        }


        //Fetch Children:
        $in__children = $this->READ_model->ln_fetch(array(
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            'ln_type_player_id' => 4228, //Blog Link Regular Read
            'ln_parent_blog_id' => $ins[0]['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));


        //Fetch progress history:
        $read_progress = $this->READ_model->ln_fetch(array(
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ PROGRESS
            'ln_creator_player_id' => $recipient_en['en_id'],
            'ln_parent_blog_id' => $ins[0]['in_id'],
        ));

        /*
        $read_skipped = $this->READ_model->ln_fetch(array(
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_player_id' => 6143, //READ SKIPPED
            'ln_creator_player_id' => $recipient_en['en_id'],
            'ln_parent_blog_id' => $ins[0]['in_id'],
        ));
        */

        //Define communication variables:
        $next_step_quick_replies = array();



        if (in_array($ins[0]['in_type_player_id'], $this->config->item('en_ids_7712'))){

            //SELECT ANSWER

            //Has no children:
            if(!count($in__children)){

                //Mark this as complete since there is no child to choose from:
                if(!count($this->READ_model->ln_fetch(array(
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_player_id' => 12119, //READ ANSWER MISSING
                    'ln_creator_player_id' => $recipient_en['en_id'],
                    'ln_parent_blog_id' => $ins[0]['in_id'],
                )))){
                    $this->READ_model->ln_create(array(
                        'ln_type_player_id' => 12119, //READ ANSWER MISSING
                        'ln_creator_player_id' => $recipient_en['en_id'],
                        'ln_parent_blog_id' => $ins[0]['in_id'],
                    ));
                }

                echo_in_next($ins[0]['in_id'], $recipient_en, $push_message);
                return true;

            } else {


                //Have they already selected answers? If so, show them their selection and focus on navigation:
                $previously_answered = $this->READ_model->ln_fetch(array(
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7704')) . ')' => null, //SUCCESS ANSWER
                    'ln_parent_blog_id' => $ins[0]['in_id'],
                    'ln_creator_player_id' => $recipient_en['en_id'],
                ), array('in_child'));


                if(count($previously_answered) > 0 && !$push_message){

                    echo '<div class="selected_before">';

                        //List answers:
                        echo_in_list($ins[0]['in_id'], $previously_answered, $recipient_en, $push_message, 'PREVIOUSLY SELECTED');

                        //Allow to edit:
                        echo ' <a href="javascript:void(0);" onclick="$(\'.selected_before\').toggleClass(\'hidden\');"><i class="fas fa-comment"></i><b> EDIT ANSWER</b></a>';

                    echo '</div>';

                }


                if($push_message){

                    $msg_quick_reply = array();

                    if ($ins[0]['in_type_player_id'] == 6684) {

                        //SELECT ONE
                        $quick_replies_allowed = ( count($in__children) <= config_var(12124) );
                        $message_content = 'Select one option to continue:'."\n\n";

                    } elseif ($ins[0]['in_type_player_id'] == 7231) {

                        //SELECT SOME
                        $quick_replies_allowed = ( count($in__children)==1 );
                        $message_content = 'Select one or more options to continue:'."\n\n";

                    }

                } else {

                    echo '<div class="selected_before '.( count($previously_answered)>0 ? 'hidden' : '' ).'">';

                    //HTML:
                    if ($ins[0]['in_type_player_id'] == 6684) {

                        echo '<div class="montserrat" style="margin-top:30px;"><span class="icon-block"><i class="fas fa-check"></i></span>SELECT ONE OPTION:</div>';

                    } elseif ($ins[0]['in_type_player_id'] == 7231) {

                        echo '<div class="montserrat" style="margin-top:30px;"><span class="icon-block"><i class="fas fa-tasks"></i></span>SELECT ONE OR MORE OPTIONS:</div>';

                    }

                    //Open for list to be printed:
                    echo '<div class="list-group list-answers" in_type_player_id="'.$ins[0]['in_type_player_id'].'">';

                }


                //List children to choose from:
                foreach ($in__children as $key => $child_in) {

                    //Has this been previously selected?
                    $previously_selected = count($this->READ_model->ln_fetch(array(
                        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7704')) . ')' => null, //SUCCESS ANSWER
                        'ln_parent_blog_id' => $ins[0]['in_id'],
                        'ln_child_blog_id' => $child_in['in_id'],
                        'ln_creator_player_id' => $recipient_en['en_id'],
                    )));

                    if ($push_message) {

                        $message_content .= ($key+1).'. '.echo_in_title($child_in['in_title'], $push_message).( $previously_selected ? ' [Previously Selected]' : '' )."\n";

                        if($quick_replies_allowed){
                            array_push($msg_quick_reply, array(
                                'content_type' => 'text',
                                'title' => 'NEXT',
                                'payload' => 'GONEXT_'.$child_in['in_id'],
                            ));
                        }

                    } else {

                        //echo '<a href="/read/actionplan_answer_question/6157/' . $recipient_en['en_id'] . '/' . $ins[0]['in_id'] . '/' . md5($this->config->item('cred_password_salt') . $child_in['in_id'] . $ins[0]['in_id'] . $recipient_en['en_id']) . '/' . $child_in['in_id'] . '" class="list-group-item itemread">';
                        echo '<a href="javascript:void(0);" onclick="select_answer('.$child_in['ln_id'].')" is-selected="'.( $previously_selected ? 1 : 0 ).'" ln_id="'.$child_in['ln_id'].'" class="ln_answer_'.$child_in['ln_id'].' answer-item list-group-item itemread">';

                        echo '<table class="table table-sm" style="background-color: transparent !important;"><tr>';

                        if ($ins[0]['in_type_player_id'] == 6684) {

                            echo '<td class="icon-block check-icon"><i class="'.( $previously_selected ? 'fas fa-check-circle' : 'far fa-circle' ).'"></i></td>';

                        } elseif ($ins[0]['in_type_player_id'] == 7231) {

                            echo '<td class="icon-block check-icon"><i class="'.( $previously_selected ? 'fas fa-check-square' : 'far fa-square' ).'"></i></td>';

                        }

                        echo '<td style="width: 100%;">';
                        echo '<b class="montserrat blog-url" style="margin-left:0;">'.echo_in_title($child_in['in_title'], false).'</b>';
                        echo '</td>';

                        echo '<td class="featured-frame">' . echo_in_thumbnail($child_in['in_id']) . '</td>';

                        echo '</tr></table>';
                        echo '</a>';

                    }
                }


                if ($push_message) {

                    if(!$quick_replies_allowed){

                        if ($ins[0]['in_type_player_id'] == 6684) {

                            $message_content .= "\n\n".'Reply with a number between 1 - '.count($in__children).' to continue.';

                        } elseif ($ins[0]['in_type_player_id'] == 7231) {

                            $message_content .= "\n\n".'Reply with one or more numbers between 1 - '.count($in__children).' to continue (add space between). For example, to select the first option reply "1", or to select the first & ';

                            if(count($in__children) >= 3){
                                $message_content .= 'third option reply "1 3"';
                            } else {
                                $message_content .= 'second option reply "1 2"';
                            }
                        }
                    }

                    $this->READ_model->dispatch_message(
                        $message_content,
                        $recipient_en,
                        $push_message,
                        $msg_quick_reply
                    );

                } else {

                    //Button to submit selection:
                    echo '<div><a class="btn btn-read" href="javascript:void(0)" onclick="read_save_answer()">'.( count($previously_answered)>0 ? 'UPDATE' : 'SAVE' ).' & CONTINUE <i class="fas fa-angle-right"></i></a> <span class="result-update"></span></div>';

                    //Close list:
                    echo '</div>';
                    echo '</div>';

                }
            }








            //Prep variables:
            $too_many_children = ( $push_message && count($in__children) > 10);


            if($push_message){

                /*
                 *
                 * Let's see if we need to cleanup the OR answer
                 * index by merging the answer response quick replies
                 * (See Github Issue 2234 for more details)
                 *
                 * */

                $answer_referencing = array(); //Start with nothing...
                foreach ($in__messages as $message_ln) {
                    //Let's see if we can find a reference:
                    for ($num = 1; $num <= 10; $num++) {
                        if(substr_count($message_ln['ln_content'] , $num.'. ')==1 || substr_count($message_ln['ln_content'] , $num.".\n")==1){
                            //Make sure we have have the previous number:
                            if($num==1 || in_array(($num-1),$answer_referencing)){
                                array_push($answer_referencing, $num);
                            }
                        }
                    }
                }

            } else {

                //See if we need to move the message inside the HTML:
                $max_html_answers = 30;
                $inline_answers = array();

                for ($num = 1; $num <= $max_html_answers; $num++) {
                    foreach ($in__messages as $index => $message_ln) {

                        $valid_num = null;
                        if(substr_count($message_ln['ln_content'] , $num.'. ')==1){
                            $valid_num = $num.'. ';
                        } elseif(substr_count($message_ln['ln_content'] , $num.".\n")==1){
                            $valid_num = $num.".\n";
                        }

                        if($valid_num){
                            $inline_answers[$num] = one_two_explode($valid_num , "\n", $message_ln['ln_content']);
                            $in__messages[$index]['ln_content'] = trim(str_replace($valid_num.$inline_answers[$num], '', $message_ln['ln_content']));
                            break;
                        }
                    }
                    //Terminate if not found:
                    if(!isset($inline_answers[$num])){
                        break;
                    }
                }

                echo '<div class="list-group" style="margin-top:10px;">';

            }

            //List OR child answers:
            foreach ($in__children as $key => $child_in) {

                //Is this selected?
                $was_selected       = ( count($read_progress) && $read_progress[0]['ln_child_blog_id']==$child_in['in_id'] );

                //Fetch history if selected:
                if($was_selected){
                    $child_progression_steps = $this->READ_model->ln_fetch(array(
                        'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Reads Completed
                        'ln_creator_player_id' => $recipient_en['en_id'],
                        'ln_parent_blog_id' => $read_progress[0]['ln_child_blog_id'],
                        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    ));
                }


                if($push_message){

                    if(!in_array(($key+1), $answer_referencing)){
                        echo "\n\n" . ($key+1).'. '.echo_in_title($child_in['in_title'], true);
                    }

                    //Add answer options to Quick Reply:
                    if(!$too_many_children){
                        array_push($next_step_quick_replies, array(
                            'content_type' => 'text',
                            'title' => ($key+1),
                            'payload' => 'ANSWERQUESTION_' . $progression_type_player_id . '_' . $ins[0]['in_id'] . '_' . $child_in['in_id'],
                        ));
                    }

                } else {

                    if(!count($read_progress)){

                        //Need to select answer:
                        echo '<a href="/read/actionplan_answer_question/6157/' . $recipient_en['en_id'] . '/' . $ins[0]['in_id'] . '/' . md5($this->config->item('cred_password_salt') . $child_in['in_id'] . $ins[0]['in_id'] . $recipient_en['en_id']) . '/' . $child_in['in_id'] . '" class="list-group-item itemread lightgreybg">';

                    } elseif($was_selected){

                        //This was selected:
                        echo '<a href="/'.$child_in['in_id'] . '" class="list-group-item itemread lightgreybg">';

                    } else {

                        //This was NOT selected and nothing else has been selected yet:
                        echo '<span class="list-group-item itemread" style="text-decoration: line-through;">';

                    }


                    if($was_selected){

                        //Selected Icon:
                        echo '<i class="fas fa-check-circle"></i> ';

                    } else {

                        //Not selected icon:
                        echo '<i class="far fa-circle"></i> ';

                    }


                    //Add to answer list:
                    $potential_number = trim(str_replace('.','', echo_in_title($child_in['in_title'], true)));
                    if(is_numeric($potential_number) && intval($potential_number)>0 && intval($potential_number)<=$max_html_answers && isset($inline_answers[intval($potential_number)])){
                        echo $inline_answers[intval($potential_number)];
                    } else {
                        echo echo_in_title($child_in['in_title']);
                    }

                }


                //HTML?
                if(!$push_message){

                    if($was_selected) {
                        //Status Icon:
                        echo '&nbsp;' . echo_en_cache('en_all_6186' /* Link Statuses */, (count($child_progression_steps) > 0 ? $child_progression_steps[0]['ln_status_player_id'] : 6175 /* Link Drafting */), false, null);
                    }

                    //Close tags:
                    if(!count($read_progress) || $was_selected){

                        echo '</a>';

                    } else {

                        echo '</span>';

                    }
                }
            }

            if(!$push_message){
                echo '</div>';
            } else {
                if($too_many_children) {
                    //Give instructions on how to select path:
                    //echo "\n\n" . 'Choose your answers by replying a number 1-12';
                }
            }


        } elseif (in_array($ins[0]['in_type_player_id'], $this->config->item('en_ids_7309'))) {


            if(count($read_progress)){

                $progression_type_player_id = $read_progress[0]['ln_status_player_id'];

            } else {

                //Find the paths to unlock:
                $unlock_paths = $this->BLOG_model->in_unlock_paths($ins[0]);

                //Set completion method:
                if(count($unlock_paths) > 0){

                    //Yes we have a path:
                    $progression_type_player_id = 7486; //User Read Children Unlock

                    if($push_message){
                        echo 'Here are the blogs I recommend adding to your 🔴 READING LIST to move forward:';
                    } else {
                        echo '<div class="list-group" style="margin-top:10px;">';
                    }

                    //List Unlock paths:
                    foreach ($unlock_paths as $key => $child_in) {

                        $child_progression_steps = $this->READ_model->ln_fetch(array(
                            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Reads Completed
                            'ln_creator_player_id' => $recipient_en['en_id'],
                            'ln_parent_blog_id' => $child_in['in_id'],
                            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        ));

                        $is_completed = ( count($child_progression_steps) > 0 && in_array($child_progression_steps[0]['ln_status_player_id'], $this->config->item('en_ids_7359')));
                        $is_next = ( count($next_step_quick_replies)==0 && !$is_completed );

                        if(!$push_message){

                            //Add HTML step to UI:
                            echo echo_actionplan_step_child($recipient_en['en_id'], $child_in);

                        } else {

                            //Add simple message:
                            echo "\n\n" . ($key + 1) . '. ' . echo_in_title($child_in['in_title'], $push_message);
                            echo ( $is_completed ? ' [COMPLETED]' : '' );

                        }

                        //Add Call to Action:
                        if($is_next){
                            //This is the next step:
                            array_push($next_step_quick_replies, array(
                                'content_type' => 'text',
                                'title' => 'Next',
                                'payload' => 'ADD_RECOMMENDED_' . $ins[0]['in_id']. '_' . $child_in['in_id'],
                            ));
                        }

                    }

                    if(!$push_message){
                        echo '</div>';
                    }

                } else {

                    //No path found:
                    $progression_type_player_id = 7492; //User Read Dead End

                }
            }


            //MEET REQUIREMENTS
            if($ins[0]['in_type_player_id']==6907){

                //MEET ONE REQUIREMENT

            } elseif($ins[0]['in_type_player_id']==6914) {

                //MEET ALL REQUIREMENTS

            }


            //List Requirements:
            if(count($in__children) > 0){

                if ($push_message) {

                } else {

                    //List children to choose from:
                    echo '<div class="list-group" style="margin-top:30px;">';
                    foreach ($in__children as $child_in) {
                        echo echo_in_read($child_in);
                    }
                    echo '</div>';

                }

            } else {

                //No children, show the next button:
                echo_in_next($ins[0]['in_id'], $recipient_en, $push_message);

            }


            //List ways to unlock requirements. if not completed already:
            if(!count($read_progress)){

            }


        } else {

            if ($ins[0]['in_type_player_id'] == 6677) {

                //READ ONLY

                //Log progress since there is nothing to do here:
                if(!count($read_progress)){

                    //Log progress link:
                    $this->READ_model->ln_create(array(
                        'ln_type_player_id' => 4559, //READ MESSAGES
                        'ln_creator_player_id' => $recipient_en['en_id'],
                        'ln_parent_blog_id' => $ins[0]['in_id'],
                    ));

                    //Process on-complete automations:
                    $this->READ_model->read__completion_checks($recipient_en['en_id'], $ins[0], false);

                }

                //Always show the next list:
                echo_in_list($ins[0]['in_id'], $in__children, $recipient_en, $push_message, 'UP NEXT');

            } elseif ($ins[0]['in_type_player_id'] == 6683) {

                //TEXT REPLY
                if($push_message){

                } else {
                    echo '<textarea id="user_new_content" class="border" placeholder="" style="height:66px; width: 100%; padding: 5px;"></textarea>';
                    echo '<span class="saving_result"></span>';
                    echo '<p><a class="btn btn-blog" href="javascript:void(0);" onsubmit="">Save & Continue</a></p>';
                }

            } elseif (in_array($ins[0]['in_type_player_id'], $this->config->item('en_ids_7751'))) {

                //FILE UPLOAD
                if($push_message){

                } else {

                    echo '<p>Upload a file to continue.</p>';
                    echo '<span class="saving_result"></span>';
                    echo '<input class=" inputfile" type="file" id="user_new_content" /><label class=" btn btn-blog" for="file" data-toggle="tooltip" title="Upload files up to ' . config_var(11063) . ' MB" data-placement="top">Upload File</label>';

                }

            } else {

                //UNKNOWN BLOG TYPE
                echo $this->READ_model->dispatch_message(
                    'Error: Unknown Blog Type',
                    $recipient_en,
                    $push_message
                );

                $this->READ_model->ln_create(array(
                    'ln_type_player_id' => 4246, //Platform Bug Reports
                    'ln_creator_player_id' => $recipient_en['en_id'],
                    'ln_content' => 'step_echo() unknown blog type',
                    'ln_parent_blog_id' => $in_id,
                ));

            }
        }




        //SKIP?
        if(0) {
            //Give option to skip:
            if($push_message){

                //Give option to skip User Blog:
                array_push($next_step_quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Skip',
                    'payload' => 'SKIP-ACTIONPLAN_skip-initiate_' . $ins[0]['in_id'],
                ));

            } else {

                echo '<div style="font-size: 0.7em; margin-top: 10px;">Or <a href="javascript:void(0);" onclick="blog_skip(' . $recipient_en['en_id'] . ', ' . $ins[0]['in_id'] . ')"><u>Skip</u></a>.</div>';

            }
        }
    }

    function read__completion_marks($en_id, $in, $top_level = true)
    {

        //Fetch/validate 🔴 READING LIST Common Reads:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){

            //Should not happen, log error:
            $this->READ_model->ln_create(array(
                'ln_content' => 'completion_marks() Detected user 🔴 READING LIST without in__metadata_common_steps value!',
                'ln_type_player_id' => 4246, //Platform Bug Reports
                'ln_creator_player_id' => $en_id,
                'ln_parent_blog_id' => $in['in_id'],
            ));

            return 0;
        }

        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            //Generic assessment marks stats:
            'steps_question_count' => 0, //The parent blog
            'steps_marks_min' => 0,
            'steps_marks_max' => 0,

            //User answer stats:
            'steps_answered_count' => 0, //How many they have answered so far
            'steps_answered_marks' => 0, //Indicates completion score

            //Calculated at the end:
            'steps_answered_score' => 0, //Used to determine which label to be unlocked...
        );


        //Fetch expansion steps recursively, if any:
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0){

            //We need expansion steps (OR Blogs) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_in_ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($in_metadata['in__metadata_expansion_steps'] as $question_in_id => $answers_in_ids ){

                //Calculate local min/max marks:
                array_push($question_in_ids, $question_in_id);
                $metadata_this['steps_question_count'] += 1;
                $local_min = null;
                $local_max = null;

                //Calculate min/max points for this based on answers:
                foreach($this->READ_model->ln_fetch(array(
                    'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_player_id' => 4228, //Blog Link Regular Read
                    'ln_parent_blog_id' => $question_in_id,
                    'ln_child_blog_id IN (' . join(',', $answers_in_ids) . ')' => null, //Limit to cached answers
                ), array('in_child')) as $in_answer){

                    //Extract Link Metadata:
                    $possible_answer_metadata = unserialize($in_answer['ln_metadata']);

                    //Assign to this question:
                    $answer_marks_index[$in_answer['in_id']] = ( isset($possible_answer_metadata['tr__assessment_points']) ? intval($possible_answer_metadata['tr__assessment_points']) : 0 );

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$in_answer['in_id']] < $local_min){
                        $local_min = $answer_marks_index[$in_answer['in_id']];
                    }
                    if(is_null($local_max) || $answer_marks_index[$in_answer['in_id']] > $local_max){
                        $local_max = $answer_marks_index[$in_answer['in_id']];
                    }
                }

                //Did we have any marks for this question?
                if(!is_null($local_min)){
                    $metadata_this['steps_marks_min'] += $local_min;
                }
                if(!is_null($local_max)){
                    $metadata_this['steps_marks_max'] += $local_max;
                }
            }



            //Now let's check user answers to see what they have done:
            foreach($this->READ_model->ln_fetch(array(
                'ln_creator_player_id' => $en_id, //Belongs to this User
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Reads Completed
                'ln_parent_blog_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            )) as $expansion_in) {

                //Addup data for this blog:
                $metadata_this['steps_answered_count'] += 1;

                //Calculate if answered:
                if(in_array($expansion_in['ln_type_player_id'], $this->config->item('en_ids_7704') /* User Read Answered Successfully */)){

                    //Fetch blog data:
                    $ins = $this->BLOG_model->in_fetch(array(
                        'in_id' => $expansion_in['ln_child_blog_id'],
                        'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
                    ));

                    if(count($ins) > 0){
                        //Fetch recursive:
                        $recursive_stats = $this->READ_model->read__completion_marks($en_id, array_merge($expansion_in, $ins[0]), false);
                        $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];

                        $this_answer_marks = $answer_marks_index[$expansion_in['ln_child_blog_id']];
                        $metadata_this['steps_answered_marks'] += $this_answer_marks + $recursive_stats['steps_answered_marks'];

                    }
                }
            }
        }


        if($top_level && $metadata_this['steps_answered_count'] > 0){
            //See assessment summary:
            $metadata_this['steps_answered_score'] = floor( ($metadata_this['steps_answered_marks'] - $metadata_this['steps_marks_min']) / ( $metadata_this['steps_marks_max'] - $metadata_this['steps_marks_min'] ) * 100 );
        }


        //Return results:
        return $metadata_this;

    }

    function read__completion_progress($en_id, $in, $top_level = true)
    {

        if(!isset($in['in_metadata'])){
            return false;
        }

        //Fetch/validate 🔴 READING LIST Common Reads:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){
            //Since it's not there yet we assume the blog it self only!
            $in_metadata['in__metadata_common_steps'] = array($in['in_id']);
        }

        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Count totals:
        $common_totals = $this->BLOG_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_read_time) as total_seconds');

        //Count completed for user:
        $common_completed = $this->READ_model->ln_fetch(array(
            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Reads Completed
            'ln_creator_player_id' => $en_id, //Belongs to this User
            'ln_parent_blog_id IN (' . join(',', $flat_common_steps ) . ')' => null,
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ), array('in_parent'), 0, 0, array(), 'COUNT(in_id) as completed_steps, SUM(in_read_time) as completed_seconds');

        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_steps']),
            'steps_completed' => intval($common_completed[0]['completed_steps']),
            'seconds_total' => intval($common_totals[0]['total_seconds']),
            'seconds_completed' => intval($common_completed[0]['completed_seconds']),
        );


        //Expansion Reads Recursive
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0){

            //Now let's check user answers to see what they have done:
            foreach($this->READ_model->ln_fetch(array(
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7704') ) . ')' => null, //User Read Answered Successfully
                'ln_creator_player_id' => $en_id, //Belongs to this User
                'ln_parent_blog_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_child_blog_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_steps'])) . ')' => null,
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            ), array('in_child')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->READ_model->read__completion_progress($en_id, $expansion_in, false);

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
                $metadata_this['seconds_total'] += $recursive_stats['seconds_total'];
                $metadata_this['seconds_completed'] += $recursive_stats['seconds_completed'];
            }
        }


        //Expansion steps Recursive
        if(isset($in_metadata['in__metadata_expansion_conditional']) && count($in_metadata['in__metadata_expansion_conditional']) > 0){

            //Now let's check if user has unlocked any Miletones:
            foreach($this->READ_model->ln_fetch(array(
                'ln_type_player_id' => 6140, //🔴 READING LIST Conditional Read Unlocked
                'ln_creator_player_id' => $en_id, //Belongs to this User
                'ln_parent_blog_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_child_blog_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_conditional'])) . ')' => null,
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            ), array('in_child')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->READ_model->read__completion_progress($en_id, $expansion_in, false);

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
                $metadata_this['seconds_total'] += $recursive_stats['seconds_total'];
                $metadata_this['seconds_completed'] += $recursive_stats['seconds_completed'];

            }
        }


        if($top_level){

            /*
             *
             * Completing an 🔴 READING LIST depends on two factors:
             *
             * 1) number of steps (some may have 0 time estimate)
             * 2) estimated seconds (usual ly accurate)
             *
             * To increase the accurate of our completion % function,
             * We would also assign a default time to the average step
             * so we can calculate more accurately even if none of the
             * steps have an estimated time.
             *
             * */

            //Set default seconds per step:
            $step_default_seconds = 60;

            //Calculate completion rate based on estimated time cost:
            $metadata_this['completion_percentage'] = intval(floor( ($metadata_this['seconds_completed']+($step_default_seconds*$metadata_this['steps_completed'])) / ($metadata_this['seconds_total']+($step_default_seconds*$metadata_this['steps_total'])) * 100 ));

        }

        //Return results:
        return $metadata_this;

    }


    function read_ids($en_id){
        //Simply returns all the blog IDs for a user's 🔴 READING LIST:
        $player_read_ids = array();
        foreach($this->READ_model->ln_fetch(array(
            'ln_creator_player_id' => $en_id,
            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ), array('in_parent'), 0) as $user_in){
            array_push($player_read_ids, intval($user_in['in_id']));
        }
        return $player_read_ids;
    }






    function dispatch_message($input_message, $recipient_en = array(), $push_message = false, $quick_replies = array(), $message_in_id = 0)
    {

        /*
         *
         * The primary function that constructs messages based on the following inputs:
         *
         *
         * - $input_message:        The message text which may include player
         *                          references like "@123" or commands like
         *                          "/firstname". This may NOT include direct
         *                          URLs as they must be first turned into an
         *                          player and then referenced within a message.
         *
         *
         * - $recipient_en:         The player object that this message is supposed
         *                          to be delivered to. May be an empty array for
         *                          when we want to show these messages to guests,
         *                          and it may contain the full player object or it
         *                          may only contain the player ID, which enables this
         *                          function to fetch further information from that
         *                          player as required based on its other parameters.
         *
         *
         * - $push_message:         If TRUE this function will prepare a message to be
         *                          delivered to use using either Messenger or Chrome. If FALSE, it
         *                          would prepare a message for immediate HTML view. The HTML
         *                          format will consider if a Trainer is logged in or not,
         *                          which will alter the HTML format.
         *
         *
         * - $quick_replies:        Only supported if $push_message = TRUE, and
         *                          will append an array of quick replies that will give
         *                          Users an easy way to tap and select their next step.
         *
         * */

        //This could happen with random messages
        if(strlen($input_message) < 1){
            return false;
        }

        //Validate message:
        $msg_dispatching = $this->READ_model->dispatch_validate_message($input_message, $recipient_en, $push_message, $quick_replies, 0, $message_in_id, false);


        //Did we have ane error in message validation?
        if (!$msg_dispatching['status'] || !isset($msg_dispatching['output_messages'])) {

            //Log Error Link:
            $this->READ_model->ln_create(array(
                'ln_type_player_id' => 4246, //Platform Bug Reports
                'ln_creator_player_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                'ln_content' => 'dispatch_validate_message() returned error [' . $msg_dispatching['message'] . '] for input message [' . $input_message . ']',
                'ln_metadata' => array(
                    'input_message' => $input_message,
                    'recipient_en' => $recipient_en,
                    'push_message' => $push_message,
                    'quick_replies' => $quick_replies,
                    'message_in_id' => $message_in_id
                ),
            ));

            return false;
        }

        //Message validation passed...
        $html_message_body = '';

        //Log message sent link:
        foreach ($msg_dispatching['output_messages'] as $output_message) {

            //Dispatch message based on format:
            if ($push_message) {

                if($msg_dispatching['user_chat_channel']==6196 /* Mench on Messenger */){

                    //Attempt to dispatch message via Facebook Graph API:
                    $fb_graph_process = $this->READ_model->facebook_graph('POST', '/me/messages', $output_message['message_body']);

                    //Did we have an Error from the Facebook API side?
                    if (!$fb_graph_process['status']) {

                        //Ooopsi, we did! Log error Transcation:
                        $this->READ_model->ln_create(array(
                            'ln_type_player_id' => 4246, //Platform Bug Reports
                            'ln_creator_player_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                            'ln_content' => 'dispatch_message() failed to send message via Facebook Graph API. See Metadata log for more details.',
                            'ln_metadata' => array(
                                'input_message' => $input_message,
                                'output_message' => $output_message['message_body'],
                                'fb_graph_process' => $fb_graph_process,
                            ),
                        ));

                        //Terminate function:
                        return false;

                    }

                } else {

                    $fb_graph_process = null; //No Facebook call made

                }

            } else {

                //HTML Format, add to message variable that will be returned at the end:
                $html_message_body .= $output_message['message_body'];

                $fb_graph_process = null; //No Facebook call made

            }

            //Log successful Link for message delivery:
            if(isset($recipient_en['en_id']) && $push_message){
                $this->READ_model->ln_create(array(
                    'ln_content' => $msg_dispatching['input_message'],
                    'ln_type_player_id' => $output_message['message_type_en_id'],
                    'ln_creator_player_id' => $recipient_en['en_id'],
                    'ln_parent_player_id' => $msg_dispatching['ln_parent_player_id'], //Might be set if message had a referenced player
                    'ln_metadata' => array(
                        'input_message' => $input_message,
                        'output_message' => $output_message,
                        'fb_graph_process' => $fb_graph_process,
                    ),
                ));
            }

        }

        //If we're here it's all good:
        return ( $push_message ? true : $html_message_body );

    }


    function dispatch_validate_message($input_message, $recipient_en = array(), $push_message = false, $quick_replies = array(), $message_type_en_id = 0, $message_in_id = 0, $strict_validation = true)
    {

        /*
         *
         * This function is used to validate Blog Notes.
         *
         * See dispatch_message() for more information on input variables.
         *
         * */


        //Try to fetch session if recipient not provided:
        if(!isset($recipient_en['en_id'])){
            $recipient_en = superpower_assigned();
        }

        $is_being_modified = ( $message_type_en_id > 0 ); //IF $message_type_en_id > 0 means we're adding/editing and need to do extra checks
        $input_message = trim($input_message);

        //Start with basic input validation:
        if (strlen($input_message) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Message Content',
            );
        } elseif ($strict_validation && strlen($input_message) > config_var(11073)) {
            return array(
                'status' => 0,
                'message' => 'Message is longer than the allowed ' . config_var(11073) . ' characters',
            );
        } elseif (!preg_match('//u', $input_message)) {
            return array(
                'status' => 0,
                'message' => 'Message must be UTF8',
            );
        } elseif ($push_message && !isset($recipient_en['en_id'])) {
            return array(
                'status' => 0,
                'message' => 'Facebook Messenger Format requires a recipient player ID to construct a message',
            );
        } elseif (count($quick_replies) > 0 && !$push_message) {
            /*
             * TODO Enable later on...
            return array(
                'status' => 0,
                'message' => 'Quick Replies are only supported for PUSH messages',
            );
            */
        } elseif ($message_type_en_id > 0 && !in_array($message_type_en_id, $this->config->item('en_ids_4485'))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Message type ID',
            );
        }


        /*
         *
         * Let's do a generic message reference validation
         * that does not consider $message_type_en_id if passed
         *
         * */
        $string_references = extract_references($input_message);

        if($strict_validation){
            //Check only in strict mode:
            if (count($string_references['ref_urls']) > 1) {

                return array(
                    'status' => 0,
                    'message' => 'You can reference a maximum of 1 URL per message',
                );

            } elseif (count($string_references['ref_players']) > 1) {

                return array(
                    'status' => 0,
                    'message' => 'Message can include a maximum of 1 player reference',
                );

            } elseif (!$push_message && count($string_references['ref_blogs']) > 1) {

                return array(
                    'status' => 0,
                    'message' => 'Message can include a maximum of 1 blog reference',
                );

            } elseif (!$push_message && count($string_references['ref_players']) > 0 && count($string_references['ref_urls']) > 0) {

                return array(
                    'status' => 0,
                    'message' => 'You can either reference an player OR a URL, as URLs are transformed to players',
                );

            } elseif (count($string_references['ref_commands']) > 0) {

                if(count($string_references['ref_commands']) != count(array_unique($string_references['ref_commands']))){

                    return array(
                        'status' => 0,
                        'message' => 'Each /command can only be used once per message',
                    );

                } elseif(in_array('/link:',$string_references['ref_commands']) && count($quick_replies) > 0){

                    return array(
                        'status' => 0,
                        'message' => 'You cannot combine the /link command with quick replies',
                    );

                }

            }
        }



        /*
         *
         * $message_type_en_id Validation
         * only in strict mode!
         *
         * */
        if($strict_validation && $message_type_en_id > 0){

            //See if this message type has specific input requirements:
            $en_all_4485 = $this->config->item('en_all_4485');

            //Now check for blog referencing settings:
            if(!in_array(4985 , $en_all_4485[$message_type_en_id]['m_parents']) && count($string_references['ref_blogs']) > 0){

                return array(
                    'status' => 0,
                    'message' => $en_all_4485[$message_type_en_id]['m_name'].' do not support blog referencing.',
                );

            }

            //Now check for player referencing settings:
            if(!in_array(4986 , $en_all_4485[$message_type_en_id]['m_parents']) && !in_array(7551 , $en_all_4485[$message_type_en_id]['m_parents']) && count($string_references['ref_players']) > 0){

                return array(
                    'status' => 0,
                    'message' => $en_all_4485[$message_type_en_id]['m_name'].' do not support player referencing.',
                );

            } elseif(in_array(7551 , $en_all_4485[$message_type_en_id]['m_parents']) && count($string_references['ref_players']) != 1){

                return array(
                    'status' => 0,
                    'message' => $en_all_4485[$message_type_en_id]['m_name'].' require an player reference.',
                );

            }

        }







        /*
         *
         * Fetch more details on recipient player if needed
         *
         * */

        if(isset($recipient_en['en_id']) && in_array('/firstname', $string_references['ref_commands']) && !isset($recipient_en['en_name'])){

            //Fetch full player data:
            $ens = $this->PLAY_model->en_fetch(array(
                'en_id' => $recipient_en['en_id'],
                'en_status_player_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
            ));

            if (count($ens) < 1) {
                //Ooops, invalid player ID provided
                return array(
                    'status' => 0,
                    'message' => 'Invalid Player ID provided',
                );

            } else {
                //Assign data:
                $recipient_en = $ens[0];
            }

        }





        //See if we have a valid way to connect to them if push:
        if ($push_message) {

            $user_messenger = $this->READ_model->ln_fetch(array(
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                'ln_parent_player_id' => 6196, //Mench Messenger
                'ln_child_player_id' => $recipient_en['en_id'],
                'ln_external_id >' => 0,
            ));

            //Messenger has a higher priority than email, is the user connected?
            if(count($user_messenger) > 0) {

                $user_chat_channel = 6196; //Mench on Messenger

            } else {

                //See if they have an email:
                $user_emails = $this->READ_model->ln_fetch(array(
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_child_player_id' => $recipient_en['en_id'],
                    'ln_type_player_id' => 4255, //Linked Players Text (Email is text)
                    'ln_parent_player_id' => 3288, //Mench Email
                ));

                if(count($user_emails) > 0){

                    $user_chat_channel = 12103; //Web+Email

                } else {

                    //No way to communicate with user:
                    return array(
                        'status' => 0,
                        'message' => 'User @' . $recipient_en['en_id'] . ' has not connected to a Mench platform yet',
                    );

                }
            }

        } else {

            //No communication channel since the message is NOT being pushed:
            $user_chat_channel = 0;

        }


        /*
         *
         * Fetch notification level IF $push_message = TRUE
         *
         * */

        if ($push_message && $user_chat_channel==6196 /* Mench on Messenger */) {


            $en_all_11058 = $this->config->item('en_all_11058');

            //Fetch recipient notification type:
            $lns_comm_level = $this->READ_model->ln_fetch(array(
                'ln_parent_player_id IN (' . join(',', $this->config->item('en_ids_4454')) . ')' => null,
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                'ln_child_player_id' => $recipient_en['en_id'],
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ));

            //Start validating communication settings we fetched to ensure everything is A-OK:
            if (count($lns_comm_level) < 1) {

                return array(
                    'status' => 0,
                    'message' => 'User is missing their Messenger Notification Level',
                );

            } elseif (count($lns_comm_level) > 1) {

                //This should find exactly one result
                return array(
                    'status' => 0,
                    'message' => 'User has more than 1 Notification Level parent player relation',
                );

            } elseif (!array_key_exists($lns_comm_level[0]['ln_parent_player_id'], $en_all_11058)) {

                return array(
                    'status' => 0,
                    'message' => 'Fetched unknown Notification Level [' . $lns_comm_level[0]['ln_parent_player_id'] . ']',
                );

            } else {

                //All good, Set notification type:
                $notification_type = $en_all_11058[$lns_comm_level[0]['ln_parent_player_id']]['m_desc'];

            }
        }


        /*
         *
         * Transform URLs into Player + Links
         *
         * */
        if ($strict_validation && count($string_references['ref_urls']) > 0) {

            //BUGGY
            //No player linked, but we have a URL that we should turn into an player if not already:
            $url_player = $this->PLAY_model->en_sync_url($string_references['ref_urls'][0], ( isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0 ), ( isset($recipient_en['en_id']) ? array($recipient_en['en_id']) : array() ));

            //Did we have an error?
            if (!$url_player['status'] || !isset($url_player['en_url']['en_id']) || intval($url_player['en_url']['en_id']) < 1) {
                return $url_player;
            }

            //Transform this URL into an player IF it was found/created:
            if(intval($url_player['en_url']['en_id']) > 0){

                $string_references['ref_players'][0] = intval($url_player['en_url']['en_id']);

                //Replace the URL with this new @player in message.
                //This is the only valid modification we can do to $input_message before storing it in the DB:
                $input_message = str_replace($string_references['ref_urls'][0], '@' . $string_references['ref_players'][0], $input_message);

                //Remove URL:
                unset($string_references['ref_urls'][0]);

            }

        }


        /*
         *
         * Process Commands
         *
         * */

        //Start building the Output message body based on format:
        $output_body_message = ( $push_message ? $input_message : htmlentities($input_message) );

        if (in_array('/firstname', $string_references['ref_commands'])) {

            //We sometimes may need to set a default recipient player name IF /firstname command used without any recipient player passed:
            if (!isset($recipient_en['en_name'])) {
                //This is a guest User, so use the default:
                $recipient_en['en_name'] = 'Stranger';
            }

            //Replace name with command:
            $output_body_message = str_replace('/firstname', one_two_explode('', ' ', $recipient_en['en_name']), $output_body_message);

        }


        //Determine if we have a button link:
        $fb_button_title = null;
        $fb_button_url = null;
        if (in_array('/link:', $string_references['ref_commands'])) {

            //Validate /link format:
            $link_anchor = trim(one_two_explode('/link:', ':http', $output_body_message));
            $link_url = 'http' . one_two_explode(':http', ' ', $output_body_message);

            if (strlen($link_anchor) < 1 || !filter_var($link_url, FILTER_VALIDATE_URL)) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid link command',
                );
            }

            //Make adjustments:
            if ($push_message) {

                //Update variables to later include in message:
                $fb_button_title = $link_anchor;
                $fb_button_url = $link_url;

                //Remove command from input message:
                $output_body_message = str_replace('/link:' . $link_anchor . ':' . $link_url, '', $output_body_message);

            } else {

                //Replace in HTML message:
                $output_body_message = str_replace('/link:' . $link_anchor . ':' . $link_url, '<a href="' . $link_url . '" target="_blank">' . $link_anchor . '</a>', $output_body_message);

            }

        }




        /*
         *
         * Referenced Player
         *
         * */

        //Will contain media from referenced player:
        $fb_media_attachments = array();

        //We assume this message has text, unless its only content is an player reference like "@123"
        $has_text = true;

        //Where is this request being made from? Public landing pages will have some restrictions on what they displat:
        $is_landing_page = is_numeric(str_replace('_','', $this->uri->segment(1)));
        $is_user_message = ($is_landing_page || $this->uri->segment(1)=='read');

        if (count($string_references['ref_players']) > 0) {

            //We have a reference within this message, let's fetch it to better understand it:
            $ens = $this->PLAY_model->en_fetch(array(
                'en_id' => $string_references['ref_players'][0], //Note: We will only have a single reference per message
            ));

            if (count($ens) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The referenced player @' . $string_references['ref_players'][0] . ' not found',
                );
            }

            //Direct Media URLs supported:
            $en_all_11059 = $this->config->item('en_all_11059');
            $en_all_6177 = $this->config->item('en_all_6177');

            //We send Media in their original format IF $push_message = TRUE, which means we need to convert link types:
            if ($push_message) {
                //Converts Player Link Types to their corresponding User Message Sent Link Types:
                $master_media_sent_conv = array(
                    4258 => 4553, //video
                    4259 => 4554, //audio
                    4260 => 4555, //image
                    4261 => 4556, //file
                );
            }

            //See if this player has any parent links to be shown in this appendix
            $parents_media_shown = 0;
            $player_appendix = null;

            //Determine what type of Media this reference has:
            if(!($this->uri->segment(1)=='play' && $this->uri->segment(2)==$string_references['ref_players'][0])){

                foreach ($this->READ_model->ln_fetch(array(
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Player-to-Player URL
                    'ln_child_player_id' => $string_references['ref_players'][0], //This child player
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'en_status_player_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
                ), array('en_parent'), 0) as $parent_en) {

                    $is_media = array_key_exists($parent_en['ln_type_player_id'], $en_all_11059);

                    //Any Type of URL: Generic, Embed, Video, Audio, Image & File

                    if ($parent_en['ln_type_player_id'] == 4257) {

                        $parents_media_shown++;

                        //Embed URL
                        if ($push_message) {
                            //Show simple URL:
                            $ln_content = $parent_en['ln_content'];
                        } else {
                            //Show HTML Embed Code:
                            $ln_content = '<div class="player-appendix">' . echo_url_embed($parent_en['ln_content']) . '</div>';
                        }

                        if ($push_message) {

                            //Generic URL:
                            array_push($fb_media_attachments, array(
                                'ln_type_player_id' => 4552, //Text Message Sent
                                'ln_content' => $ln_content,
                                'fb_att_id' => 0,
                                'fb_att_type' => null,
                            ));

                        } else {

                            //HTML Format, append content to current output message:
                            $player_appendix .= $ln_content;

                        }

                    } elseif ($push_message && $is_media) {

                        //Raw media file: Audio, Video, Image OR File...
                        $parents_media_shown++;

                        //Search for Facebook Attachment ID IF $push_message = TRUE
                        $fb_att_id = 0;
                        if ($push_message && strlen($parent_en['ln_metadata']) > 0) {
                            //We might have a Facebook Attachment ID saved in Metadata, check to see:
                            $metadata = unserialize($parent_en['ln_metadata']);
                            if (isset($metadata['fb_att_id']) && intval($metadata['fb_att_id']) > 0) {
                                //Yes we do, use this for faster media attachments:
                                $fb_att_id = intval($metadata['fb_att_id']);
                            }
                        }

                        //Push raw file to Media Array:
                        array_push($fb_media_attachments, array(
                            'ln_type_player_id' => $master_media_sent_conv[$parent_en['ln_type_player_id']],
                            'ln_content' => ($fb_att_id > 0 ? null : $parent_en['ln_content']),
                            'fb_att_id' => $fb_att_id,
                            'fb_att_type' => $en_all_11059[$parent_en['ln_type_player_id']]['m_desc'],
                        ));

                    } elseif($push_message && $parent_en['ln_type_player_id'] == 4256){

                        //Generic URL:
                        array_push($fb_media_attachments, array(
                            'ln_type_player_id' => 4552, //Text Message Sent
                            'ln_content' => $parent_en['ln_content'],
                            'fb_att_id' => 0,
                            'fb_att_type' => null,
                        ));

                    } elseif(!$push_message){

                        if($is_media){
                            $parents_media_shown++;
                        }

                        //HTML Format, append content to current output message:
                        $player_appendix .= '<div class="player-appendix">' . echo_url_type($parent_en['ln_content'], $parent_en['ln_type_player_id']) . '</div>';

                    }
                }
            }

            //Determine if we have text:
            $has_text = !(trim($output_body_message) == '@' . $string_references['ref_players'][0]);


            //Append any appendix generated:
            if($player_appendix){
                $output_body_message .= $player_appendix;
            }



            //Adjust
            if (!$push_message) {

                /*
                 *
                 * HTML Message format, which will
                 * include a link to the Player for quick access
                 * to more information about that player:=.
                 *
                 * */

                if($is_user_message){

                    $player_name_replacement = ( $has_text ? '<span class="player-name">'.$ens[0]['en_name'].'</span>' : '' );
                    $output_body_message = str_replace('@' . $string_references['ref_players'][0], $player_name_replacement, $output_body_message);

                } else {

                    //Show player link with status:
                    $output_body_message = str_replace('@' . $string_references['ref_players'][0], '<span class="'.( $parents_media_shown > 0 ? superpower_active(10983) : '' ).'">'.( !in_array($ens[0]['en_status_player_id'], $this->config->item('en_ids_7357')) ? '<span class="icon-block">'.$en_all_6177[$ens[0]['en_status_player_id']]['m_icon'].'</span>' : '' ).'<span class="icon-block">'.echo_en_icon($ens[0]['en_icon']).'</span><a class="montserrat doupper '.extract_icon_color($ens[0]['en_icon']).'" href="/play/' . $ens[0]['en_id'] . '">' . $ens[0]['en_name']  . '</a></span>', $output_body_message);

                }

            } else {

                //Just replace with the player name, which ensure we're always have a text in our message even if $has_text = FALSE
                $player_name_replacement = ( $has_text ? $ens[0]['en_name'] : '' );
                $output_body_message = str_replace('@' . $string_references['ref_players'][0], $player_name_replacement, $output_body_message);

            }
        }

        //Do we have a BLOG up-vote?
        if (!$push_message && count($string_references['ref_blogs']) > 0 && $message_in_id > 0) {

            $referenced_ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $string_references['ref_blogs'][0], //Note: We will only have a single reference per message
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            ));
            if (count($referenced_ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The referenced parent blog #' . $string_references['ref_blogs'][0] . ' not found',
                );
            }


            if(isset($string_references['ref_players'][0])){

                //Fetch the referenced blog:
                $upvote_child_ins = $this->BLOG_model->in_fetch(array(
                    'in_id' => $message_in_id,
                    'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
                ));
                if (count($upvote_child_ins) < 1) {
                    return array(
                        'status' => 0,
                        'message' => 'The referenced child blog #' . $message_in_id . ' not found',
                    );
                }

                //Check up-voting restrictions:
                if($is_being_modified){

                    //Player reference must be either the trainer themselves or an expert source:
                    $session_en = superpower_assigned();
                    if($string_references['ref_players'][0] != $session_en['en_id']){

                        //Reference is not the logged-in trainer, let's check to make sure it's an expert source
                        if(!count($this->READ_model->ln_fetch(array(
                            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                            'ln_child_player_id' => $string_references['ref_players'][0],
                            'ln_parent_player_id IN ('.join(',' , $this->config->item('en_ids_4983')).')' => null,
                            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                        )))){
                            return array(
                                'status' => 0,
                                'message' => 'Invalid Player Reference. See @4983 for a list of valid player references.',
                            );
                        }
                    }
                }


                //Note that currently blog references are not displayed on the landing page (Only Messages are) OR messenger format

                //Remove blog reference from anywhere in the message:
                $output_body_message = trim(str_replace('#' . $referenced_ins[0]['in_id'], '', $output_body_message));


                //Add Blog up-vote to beginning:
                $output_body_message = '<div style="margin-bottom:5px;" class="'.superpower_active(10984).'"><span class="icon-block"><i class="far fa-thumbs-up ispink"></i></span><a href="/blog/' . $referenced_ins[0]['in_id'] . '" target="_parent" class="montserrat">' . echo_in_title($referenced_ins[0]['in_title'], false) . '</a></div>' . $output_body_message;

            } else {

                //Blog referencing without an player referencing, show simply the blog:

                //Remove blog reference from anywhere in the message:
                $output_body_message = trim(str_replace('#' . $referenced_ins[0]['in_id'], '', $output_body_message));

                //Add Blog up-vote to beginning:
                $output_body_message = '<div style="margin-bottom:5px; border-bottom: 1px solid #E5E5E5; padding-bottom:10px;"><a href="/blog/' . $referenced_ins[0]['in_id'] . '" target="_parent">' . echo_in_title($referenced_ins[0]['in_title'], false) . '</a></div>' . $output_body_message;

            }





        }




        /*
         *
         * Construct Message based on current data
         *
         * $output_messages will determines the type & content of the
         * message(s) that will to be sent. We might need to send
         * multiple messages IF $push_message = TRUE and the
         * text message has a referenced player with a one or more
         * media file (Like video, image, file or audio).
         *
         * The format of this will be array( $ln_child_player_id => $ln_content )
         * to define both message and it's type.
         *
         * See all sent message types here: https://mench.com/play/4280
         *
         * */
        $output_messages = array();

        if ($push_message && $user_chat_channel==6196 /* Mench on Messenger */) {


            //Do we have a text message?
            if ($has_text || $fb_button_title) {

                if ($fb_button_title) {

                    //We have a fixed button to append to this message:
                    $fb_message = array(
                        'attachment' => array(
                            'type' => 'template',
                            'payload' => array(
                                'template_type' => 'button',
                                'text' => $output_body_message,
                                'buttons' => array(
                                    array(
                                        'type' => 'web_url',
                                        'url' => $fb_button_url,
                                        'title' => $fb_button_title,
                                        'webview_height_ratio' => 'tall',
                                        'webview_share_button' => 'hide',
                                        'messenger_extensions' => true,
                                    ),
                                ),
                            ),
                        ),
                        'metadata' => 'system_logged', //Prevents duplicate Link logs
                    );

                } elseif ($has_text) {

                    //No button, just text:
                    $fb_message = array(
                        'text' => $output_body_message,
                        'metadata' => 'system_logged', //Prevents duplicate Link logs
                    );

                    if(count($quick_replies) > 0){
                        $fb_message['quick_replies'] = $quick_replies;
                    }

                }

                //Add to output message:
                array_push($output_messages, array(
                    'message_type_en_id' => ( isset($fb_message['quick_replies']) && count($fb_message['quick_replies']) > 0 ? 6563 : 4552 ), //Text OR Quick Reply Message Sent
                    'message_body' => array(
                        'recipient' => array(
                            'id' => $user_messenger[0]['ln_external_id'],
                        ),
                        'message' => $fb_message,
                        'notification_type' => $notification_type,
                        'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION',
                    ),
                ));

            }


            if (!$has_text && count($quick_replies) > 0) {

                //This is an error:
                $this->READ_model->ln_create(array(
                    'ln_content' => 'dispatch_validate_message() was given quick replies without a text message',
                    'ln_metadata' => array(
                        'input_message' => $input_message,
                        'push_message' => $push_message,
                        'quick_replies' => $quick_replies,
                    ),
                    'ln_type_player_id' => 4246, //Platform Bug Reports
                    'ln_creator_player_id' => $recipient_en['en_id'],
                    'ln_parent_player_id' => $message_type_en_id,
                    'ln_child_blog_id' => $message_in_id,
                ));

            }


            if (count($fb_media_attachments) > 0) {

                //We do have additional messages...
                //TODO Maybe add another message to give User some context on these?

                //Append messages:
                foreach ($fb_media_attachments as $fb_media_attachment) {

                    //See what type of attachment (if any) this is:
                    if (!$fb_media_attachment['fb_att_type']) {

                        //This is a text message, not an attachment:
                        $fb_message = array(
                            'text' => $fb_media_attachment['ln_content'],
                            'metadata' => 'system_logged', //Prevents duplicate Link logs
                        );

                    } elseif ($fb_media_attachment['fb_att_id'] > 0) {

                        //Saved Attachment that can be served instantly:
                        $fb_message = array(
                            'attachment' => array(
                                'type' => $fb_media_attachment['fb_att_type'],
                                'payload' => array(
                                    'attachment_id' => $fb_media_attachment['fb_att_id'],
                                ),
                            ),
                            'metadata' => 'system_logged', //Prevents duplicate Link logs
                        );

                    } else {

                        //Attachment that needs to be uploaded via URL which will take a few seconds:
                        $fb_message = array(
                            'attachment' => array(
                                'type' => $fb_media_attachment['fb_att_type'],
                                'payload' => array(
                                    'url' => $fb_media_attachment['ln_content'],
                                    'is_reusable' => true,
                                ),
                            ),
                            'metadata' => 'system_logged', //Prevents duplicate Link logs
                        );

                    }

                    //Add to output message:
                    array_push($output_messages, array(
                        'message_type_en_id' => $fb_media_attachment['ln_type_player_id'],
                        'message_body' => array(
                            'recipient' => array(
                                'id' => $user_messenger[0]['ln_external_id'],
                            ),
                            'message' => $fb_message,
                            'notification_type' => $notification_type,
                            'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION',
                        ),
                    ));

                }
            }

        } else {


            //Always returns a single (sometimes long) HTML message:
            array_push($output_messages, array(
                'message_type_en_id' => 4570, //User Received Email Message
                'message_body' => '<div class="i_content"><div class="msg">' . nl2br($output_body_message) . '</div></div>',
            ));

        }


        //Return results:
        return array(
            'status' => 1,
            'input_message' => trim($input_message),
            'output_messages' => $output_messages,
            'user_chat_channel' => $user_chat_channel,
            'ln_parent_player_id' => (count($string_references['ref_players']) > 0 ? $string_references['ref_players'][0] : 0),
            'ln_parent_blog_id' => (count($string_references['ref_blogs']) > 0 ? $string_references['ref_blogs'][0] : 0),
        );

    }



    function facebook_graph($action, $graph_url, $payload = array())
    {

        //Do some initial checks
        if (!in_array($action, array('GET', 'POST', 'DELETE'))) {

            //Only 4 valid types of $action
            return array(
                'status' => 0,
                'message' => '$action [' . $action . '] is invalid',
            );

        }

        //Fetch access token and settings:
        $cred_facebook = $this->config->item('cred_facebook');

        $access_token_payload = array(
            'access_token' => $cred_facebook['mench_access_token']
        );

        if ($action == 'GET' && count($payload) > 0) {
            //Add $payload to GET variables:
            $access_token_payload = array_merge($payload, $access_token_payload);
            $payload = array();
        }

        $graph_url = 'https://graph.facebook.com/' . config_var(11077) . $graph_url;
        $counter = 0;
        foreach ($access_token_payload as $key => $val) {
            $graph_url = $graph_url . ($counter == 0 ? '?' : '&') . $key . '=' . $val;
            $counter++;
        }

        //Make the graph call:
        $ch = curl_init($graph_url);

        //Base setting:
        $ch_setting = array(
            CURLOPT_CUSTOMREQUEST => $action,
            CURLOPT_RETURNTRANSFER => TRUE,
        );

        if (count($payload) > 0) {
            $ch_setting[CURLOPT_HTTPHEADER] = array('Content-Type: application/json; charset=utf-8');
            $ch_setting[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        //Apply settings:
        curl_setopt_array($ch, $ch_setting);

        //Process results and produce ln_metadata
        $result = objectToArray(json_decode(curl_exec($ch)));
        $ln_metadata = array(
            'action' => $action,
            'payload' => $payload,
            'url' => $graph_url,
            'result' => $result,
        );

        //Did we have any issues?
        if (!$result) {

            //Failed to fetch this profile:
            $message_error = 'READ_model->facebook_graph() failed to ' . $action . ' ' . $graph_url;
            $this->READ_model->ln_create(array(
                'ln_content' => $message_error,
                'ln_type_player_id' => 4246, //Platform Bug Reports
                'ln_metadata' => $ln_metadata,
            ));

            //There was an issue accessing this on FB
            return array(
                'status' => 0,
                'message' => $message_error,
                'ln_metadata' => $ln_metadata,
            );

        } else {

            //All seems good, return:
            return array(
                'status' => 1,
                'message' => 'Success',
                'ln_metadata' => $ln_metadata,
            );

        }
    }


    function digest_received_payload($en, $quick_reply_payload)
    {

        /*
         *
         * With the assumption that chat platforms like Messenger,
         * Slack and Telegram all offer a mechanism to manage a reference
         * field other than the actual message itself (Facebook calls
         * this the Reference key or Metadata), this function will
         * process that metadata string from incoming messages sent to Mench
         * by its Users and take appropriate action.
         *
         * Inputs:
         *
         * - $en:                   The User who made the request
         *
         * - $quick_reply_payload:  The payload string attached to the chat message
         *
         *
         * */


        if (strlen($quick_reply_payload) < 1) {

            //Should never happen!
            return array(
                'status' => 0,
                'message' => 'Missing quick reply payload',
            );

        } elseif (substr_count($quick_reply_payload, 'UNSUBSCRIBE_') == 1) {

            $action_unsubscribe = one_two_explode('UNSUBSCRIBE_', '', $quick_reply_payload);

            if ($action_unsubscribe == 'CANCEL') {

                //User seems to have changed their mind, confirm with them:
                $this->READ_model->dispatch_message(
                    'Awesome, I am excited to continue our work together.',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            } elseif ($action_unsubscribe == 'ALL') {

                //User wants to completely unsubscribe from Mench:
                $removed_blogs = 0;
                foreach ($this->READ_model->ln_fetch(array(
                    'ln_creator_player_id' => $en['en_id'],
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                )) as $ln) {
                    $removed_blogs++;
                    $this->READ_model->ln_update($ln['ln_id'], array(
                        'ln_status_player_id' => 6173, //Link Removed
                    ), $en['en_id'], 6155 /* User Blog Cancelled */);
                }

                //TODO DELETE THEIR ACCOUNT HERE

                //Let them know about these changes:
                $this->READ_model->dispatch_message(
                    'Confirmed, I removed ' . $removed_blogs . ' blog' . echo__s($removed_blogs) . ' from your 🔴 READING LIST. This is the final message you will receive from me unless you message me again. I hope you take good care of yourself 😘',
                    $en,
                    true
                );

            } elseif (is_numeric($action_unsubscribe)) {

                //User wants to Remove a specific 🔴 READING LIST, validate it:
                $user_blogs = $this->READ_model->ln_fetch(array(
                    'ln_creator_player_id' => $en['en_id'],
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_parent_blog_id' => $action_unsubscribe,
                ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

                //All good?
                if (count($user_blogs) < 1) {
                    return array(
                        'status' => 0,
                        'message' => 'UNSUBSCRIBE_ Failed to skip a BLOG from the master 🔴 READING LIST',
                    );
                }

                //Update status for this single 🔴 READING LIST:
                $this->READ_model->ln_update($user_blogs[0]['ln_id'], array(
                    'ln_status_player_id' => 6173, //Link Removed
                ), $en['en_id'], 6155 /* User Blog Cancelled */);

                //Re-sort remaining 🔴 READING LIST blogs:
                foreach($this->READ_model->ln_fetch(array(
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                    'ln_creator_player_id' => $en['en_id'], //Belongs to this User
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                ), array(), 0, 0, array('ln_order' => 'ASC')) as $count => $ln){
                    $this->READ_model->ln_update($ln['ln_id'], array(
                        'ln_order' => ($count+1),
                    ), $en['en_id'], 10681 /* Blogs Ordered Automatically */);
                }

                //Show success message to user:
                $this->READ_model->dispatch_message(
                    'I have successfully removed [' . $user_blogs[0]['in_title'] . '] from your 🔴 READING LIST.',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            }

        } elseif ($quick_reply_payload == 'SUBSCRIBE-REJECT') {

            //They rejected the offer... Acknowledge and give response:
            $this->READ_model->dispatch_message(
                'Ok, so how can I help you move forward?',
                $en,
                true
            );

            //READ RECOMMENDATIONS
            $this->READ_model->dispatch_message(
                echo_random_message('read_recommendation'),
                $en,
                true
            );

        } elseif (is_numeric($quick_reply_payload)) {

            //Validate Blog:
            $in_id = intval($quick_reply_payload);
            $ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $in_id,
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            ));
            if (count($ins) < 1) {

                //Confirm if they are interested to subscribe to this blog:
                $this->READ_model->dispatch_message(
                    '❌ Error: I cannot add this blog to your 🔴 READING LIST because its not yet published.',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        ),
                    )
                );

                return array(
                    'status' => 0,
                    'message' => 'Failed to validate starting-point blog',
                );
            }

            //Confirm if they are interested to subscribe to this blog:
            $this->READ_model->dispatch_message(
                'Hi 👋 are you interested to ' . $ins[0]['in_title'] . '?',
                $en,
                true,
                array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Yes', //Yes, Learn More
                        'payload' => 'SUBSCRIBE-CONFIRM_' . $ins[0]['in_id'], //'SUBSCRIBE-INITIATE_' . $ins[0]['in_id']
                    ),
                    array(
                        'content_type' => 'text',
                        'title' => 'Cancel',
                        'payload' => 'SUBSCRIBE-REJECT',
                    ),
                ),
                array(
                    'ln_child_blog_id' => $ins[0]['in_id'],
                )
            );

        } elseif ($quick_reply_payload=='NOTINTERESTED') {

            //Affirm and educate:
            $this->READ_model->dispatch_message(
                'Got it. '.echo_random_message('command_me'),
                $en,
                true
            //Do not give next option and listen for their blog command...
            );

        } elseif (substr_count($quick_reply_payload, 'SUBSCRIBE-INITIATE_') == 1) {

            //User has confirmed their desire to subscribe to a BLOG:
            $in_id = intval(one_two_explode('SUBSCRIBE-INITIATE_', '', $quick_reply_payload));

            //Initiating a BLOG 🔴 READING LIST:
            $ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $in_id,
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            ));

            if (count($ins) != 1) {
                return array(
                    'status' => 0,
                    'message' => 'SUBSCRIBE-INITIATE_ Failed to locate published blog',
                );
            }

            //Make sure blog has not already been added to user 🔴 READING LIST:
            if (count($this->READ_model->ln_fetch(array(
                    'ln_creator_player_id' => $en['en_id'],
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_parent_blog_id' => $ins[0]['in_id'],
                ))) > 0) {

                //Let User know that they have already subscribed to this blog:
                $this->READ_model->dispatch_message(
                    'The blog [' . $ins[0]['in_title'] . '] has already been added to your 🔴 READING LIST. /link:🔴 READING LIST:https://mench.com/' . $ins[0]['in_id'],
                    $en,
                    true
                );

                //Give them option to go next:
                $this->READ_model->dispatch_message(
                    'Say "Next" to continue...',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            } else {

                //Do final confirmation by giving User more context on this blog before adding to their 🔴 READING LIST...

                //See if we have an overview:
                $overview_message = '';
                $step_info = echo_tree_steps($ins[0], true);
                $source_info = echo_tree_experts($ins[0], true);

                if($source_info || $step_info){
                    $overview_message .= 'Here is an overview:' . "\n\n" . $step_info . $source_info;
                }

                $overview_message .= 'Should I add this blog to your 🔴 READING LIST?';

                //Send message for final confirmation with the overview of how long/difficult it would be to accomplish this blog:
                $this->READ_model->dispatch_message(
                    $overview_message,
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Start Reading',
                            'payload' => 'SUBSCRIBE-CONFIRM_' . $ins[0]['in_id'],
                        ),
                        array(
                            'content_type' => 'text',
                            'title' => 'Cancel',
                            'payload' => 'SUBSCRIBE-REJECT',
                        ),
                    )
                );

                //Log as 🔴 READING LIST Considered:
                $this->READ_model->ln_create(array(
                    'ln_creator_player_id' => $en['en_id'],
                    'ln_type_player_id' => 6149, //🔴 READING LIST Blog Considered
                    'ln_parent_blog_id' => $ins[0]['in_id'],
                    'ln_content' => $overview_message, //A copy of their message
                ));

            }

        } elseif (substr_count($quick_reply_payload, 'GONEXT_') == 1) {

            $next_in_id = 0;
            $in_id = intval(one_two_explode('GONEXT_', '', $quick_reply_payload));

            if($in_id > 0){
                $ins = $this->BLOG_model->in_fetch(array(
                    'in_id' => $in_id,
                ));
                $next_in_id = $this->READ_model->read_next_find($en['en_id'], $ins[0]);
            }

            if($next_in_id > 0){
                //Yes, communicate it:
                $this->READ_model->read_echo($next_in_id, $en, true);
            } else {
                //Fetch and communicate next blog:
                $this->READ_model->read_next_go($en['en_id'], true, true);
            }

        } elseif (substr_count($quick_reply_payload, 'ADD_RECOMMENDED_') == 1) {

            $in_ids = explode('_', one_two_explode('ADD_RECOMMENDED_', '', $quick_reply_payload));
            $recommender_in_id = $in_ids[0];
            $recommended_in_id = $in_ids[1];

            //Add this item to the tio of the 🔴 READING LIST:
            $this->READ_model->read_add($en['en_id'], $recommended_in_id, $recommender_in_id);

        } elseif (substr_count($quick_reply_payload, 'SUBSCRIBE-CONFIRM_') == 1) {

            //User has requested to add this blog to their 🔴 READING LIST:
            $in_id = intval(one_two_explode('SUBSCRIBE-CONFIRM_', '', $quick_reply_payload));

            //Add to 🔴 READING LIST:
            $this->READ_model->read_add($en['en_id'], $in_id);

        } elseif (substr_count($quick_reply_payload, 'SKIP-ACTIONPLAN_') == 1) {

            //Extract variables from REF:
            $input_parts = explode('_', one_two_explode('SKIP-ACTIONPLAN_', '', $quick_reply_payload));
            $skip_action = trim($input_parts[0]); //It would be initial set to DRAFTING and then would change to REMOVED if skip was cancelled, PUBLISHED if skip was confirmed.
            $in_id = intval($input_parts[1]); //Blog to Skip

            //Validate inputs:
            if ($in_id < 1) {
                return array(
                    'status' => 0,
                    'message' => 'SKIP-ACTIONPLAN_ received invalid blog ID',
                );
            }


            //Was this initiating?
            if ($skip_action == 'skip-initiate') {

                //User has indicated they want to skip this tree and move on to the next item in-line:
                //Lets confirm the implications of this SKIP to ensure they are aware:
                $this->READ_model->read_skip_initiate($en['en_id'], $in_id);

            } else {

                //They have either confirmed or cancelled the skip:
                if ($skip_action == 'skip-cancel') {

                    //user changed their mind and does not want to skip anymore
                    $message = 'I\'m glad you changed your mind! Let\'s continue...';

                } elseif ($skip_action == 'skip-confirm') {

                    //Actually skip and see if we've finished this 🔴 READING LIST:
                    $this->READ_model->read_skip_apply($en['en_id'], $in_id);

                    //Confirm the skip:
                    $message = 'Got it! I successfully skipped selected steps';

                }

                //Inform User of Skip status:
                $this->READ_model->dispatch_message(
                    $message,
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

                //Communicate next step:
                $this->READ_model->read_next_go($en['en_id'], true, true);

            }

        } elseif (substr_count($quick_reply_payload, 'ANSWERQUESTION_') == 1) {

            /*
             *
             * When the user answers a quick reply question.
             *
             * */

            //Extract variables:
            $quickreply_parts = explode('_', one_two_explode('ANSWERQUESTION_', '', $quick_reply_payload));
            $progression_type_player_id = intval($quickreply_parts[0]);
            $question_in_id = intval($quickreply_parts[1]);
            $answer_in_id = intval($quickreply_parts[2]);

            if($question_in_id < 1 || $answer_in_id < 1 || $progression_type_player_id < 1){
                return array(
                    'status' => 0,
                    'message' => 'ANSWERQUESTION_ missing core variables ['.$question_in_id.'] & ['.$answer_in_id.'] & ['.$progression_type_player_id.']',
                );
            }


            //Validate Answer Blog:
            $answer_ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $answer_in_id,
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            ));
            $question_ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $question_in_id,
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            ));
            if(count($answer_ins) < 1 || count($question_ins) < 1){
                return array(
                    'status' => 0,
                    'message' => 'ANSWERQUESTION_ was unable to locate published question/answer',
                );
            }


            //We should already have a link for question, so let's find and update it:
            $pending_answer_links = $this->READ_model->ln_fetch(array(
                'ln_creator_player_id' => $en['en_id'],
                'ln_type_player_id' => $progression_type_player_id,
                'ln_parent_blog_id' => $question_in_id,
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            ));
            if(count($pending_answer_links) > 0){

                //All good, let's save the answer:
                $published_answer = false;
                foreach($pending_answer_links as $ln){
                    //We just found a pending answer, so mark it as published while saving the answer:
                    $this->READ_model->ln_update($ln['ln_id'], array(
                        'ln_child_blog_id' => $answer_in_id, //Save answer
                        'ln_status_player_id' => 6176, //Link Published
                        'ln_timestamp' => date("Y-m-d H:i:s"),
                    ));

                    //Update status:
                    $published_answer = true;

                    break;
                }

                //Did we publish anything?
                if($published_answer){

                    //See if we also need to mark the answer as complete:
                    $this->READ_model->read__completion_auto_complete($en['en_id'], $answer_ins[0], 7485 /* User Read Answer Unlock */);


                    //Find/Advance to the next step:
                    $this->READ_model->read_next_go($en['en_id'], true, true);

                }

            } else {

                return array(
                    'status' => 0,
                    'message' => 'ANSWERQUESTION_ was unable to locate the pending answer link',
                );

            }

        } else {

            //Unknown quick reply!
            return array(
                'status' => 0,
                'message' => 'Unknown quick reply command!',
            );

        }

        //If here it was all good, return success:
        return array(
            'status' => 1,
            'message' => 'Success',
        );

    }


    function digest_received_text($en, $fb_received_message)
    {

        /*
         *
         * Will process the chat message only in the absence of a chat metadata
         * otherwise the digest_received_payload() will process the message since we
         * know that the medata would have more precise instructions on what
         * needs to be done for the User response.
         *
         * This involves string analysis and matching terms to a blogs, players
         * and known commands that will help us understand the User and
         * hopefully provide them with the information they need, right now.
         *
         * We'd eventually need to migrate the search engine to an NLP platform
         * Like dialogflow.com (By Google) or wit.ai (By Facebook) to improve
         * our ability to detect correlations specifically for blogs.
         *
         * */

        if (!$fb_received_message) {
            return false;
        }


        /*
         *
         * Ok, now attempt to understand User's message blog.
         * We would do a very basic work pattern match to see what
         * we can understand from their message, and we would expand
         * upon this section as we improve our NLP technology.
         *
         *
         * */

        $fb_received_message = trim(strtolower($fb_received_message));

        if (in_array($fb_received_message, array('stats', 'stat', 'statistics'))) {

            $user_blogs = $this->READ_model->ln_fetch(array(
                'ln_creator_player_id' => $en['en_id'],
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

            if(count($user_blogs)==0){

                //Set message:
                $message = 'I can\'t show you any stats because you don\'t have any blogs added to your 🔴 READING LIST yet.';

                //No 🔴 READING LIST blogs!
                $this->READ_model->dispatch_message(
                    $message,
                    $en,
                    true
                );

                //READ RECOMMENDATIONS
                $this->READ_model->dispatch_message(
                    echo_random_message('read_recommendation'),
                    $en,
                    true
                );


            } else {

                //Start composing a message for their stats:
                $message = '🔴 READING LIST STATS:';

                //Show them a list of their 🔴 READING LIST and completion stats:
                foreach($user_blogs as $user_blog){
                    //Completion Percentage so far:
                    $completion_rate = $this->READ_model->read__completion_progress($en['en_id'], $user_blog);
                    $message .= "\n\n" . $completion_rate['completion_percentage'].'% ['.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' step'.echo__s($completion_rate['steps_total']).'] '.echo_in_title($user_blog['in_title']);
                }

                //Dispatch Message:
                $this->READ_model->dispatch_message(
                    $message,
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            }

            //Log command trigger:
            $this->READ_model->ln_create(array(
                'ln_creator_player_id' => $en['en_id'],
                'ln_type_player_id' => 6556, //User Commanded Stats
                'ln_content' => $message,
            ));

        } elseif (in_array($fb_received_message, array('next', 'continue', 'go'))) {

            //Give them the next step of their 🔴 READING LIST:
            $next_in_id = $this->READ_model->read_next_go($en['en_id'], true, true);

            //Log command trigger:
            $this->READ_model->ln_create(array(
                'ln_creator_player_id' => $en['en_id'],
                'ln_type_player_id' => 6559, //User Commanded Next
                'ln_parent_blog_id' => $next_in_id,
            ));

        } elseif ($fb_received_message == 'skip') {

            //Find the next blog in the 🔴 READING LIST to skip:
            $next_in_id = $this->READ_model->read_next_go($en['en_id'], false);

            if($next_in_id > 0){

                //Initiate skip request:
                $this->READ_model->read_skip_initiate($en['en_id'], $next_in_id);

            } else {

                $this->READ_model->dispatch_message(
                    'I could not find any blogs in your 🔴 READING LIST to skip.',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            }

            //Log command trigger:
            $this->READ_model->ln_create(array(
                'ln_creator_player_id' => $en['en_id'],
                'ln_type_player_id' => 6560, //User Commanded Skip
                'ln_parent_blog_id' => $next_in_id,
            ));

        } elseif (includes_any($fb_received_message, array('unsubscribe', 'stop', 'quit', 'resign', 'exit', 'cancel', 'abort'))) {

            //List their 🔴 READING LIST blogs and let user choose which one to unsubscribe:
            $user_blogs = $this->READ_model->ln_fetch(array(
                'ln_creator_player_id' => $en['en_id'],
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            ), array('in_parent'), 10 /* Max quick replies allowed */, 0, array('ln_order' => 'ASC'));


            //Do they have anything in their 🔴 READING LIST?
            if (count($user_blogs) > 0) {

                //Give them options to remove specific 🔴 READING LISTs:
                $quick_replies = array();
                $message = 'Choose one of the following options:';
                $increment = 1;

                foreach ($user_blogs as $counter => $in) {
                    //Construct unsubscribe confirmation body:
                    $message .= "\n\n" . ($counter + $increment) . '. Stop ' . $in['in_title'];
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => ($counter + $increment),
                        'payload' => 'UNSUBSCRIBE_' . $in['in_id'],
                    ));
                }

                if (count($user_blogs) >= 2) {
                    //Give option to skip all and unsubscribe:
                    $increment++;
                    $message .= "\n\n" . ($counter + $increment) . '. Remove all blogs and unsubscribe';
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => ($counter + $increment),
                        'payload' => 'UNSUBSCRIBE_ALL',
                    ));
                }

                //Alwyas give cancel option:
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Cancel',
                    'payload' => 'UNSUBSCRIBE_CANCEL',
                ));

            } else {

                $message = 'Just to confirm, do you want to unsubscribe and stop all future communications with me and unsubscribe?';
                $quick_replies = array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Yes, Unsubscribe',
                        'payload' => 'UNSUBSCRIBE_ALL',
                    ),
                    array(
                        'content_type' => 'text',
                        'title' => 'No, Stay Friends',
                        'payload' => 'UNSUBSCRIBE_CANCEL',
                    ),
                );

            }

            //Send out message and let them confirm:
            $this->READ_model->dispatch_message(
                $message,
                $en,
                true,
                $quick_replies
            );

            //Log command trigger:
            $this->READ_model->ln_create(array(
                'ln_creator_player_id' => $en['en_id'],
                'ln_type_player_id' => 6578, //User Text Commanded Stop
                'ln_content' => $message,
                'ln_metadata' => $quick_replies,
            ));

        } elseif (substr($fb_received_message, 0, 11) == 'Search for ' || substr($fb_received_message, 0, 6) == 'learn ') {


            if(substr($fb_received_message, 0, 6) == 'learn '){
                //learn
                $master_command = trim(substr(trim($fb_received_message), 6));
            } else {
                //Search for
                $master_command = trim(substr(trim($fb_received_message), 11));
            }


            //Make sure algolia is enabled:
            if (!intval(config_var(11062))) {
                $this->READ_model->dispatch_message(
                    'Currently I cannot search for any blogs. Try again later.',
                    $en,
                    true
                );
                return false;
            }


            $search_index = load_algolia('alg_index');
            $res = $search_index->search($master_command, [
                'hitsPerPage' => 6, //Max results
                'filters' => 'alg_obj_is_in=1 AND _tags:alg_is_published_featured',
            ]);
            $search_results = $res['hits'];



            //Show options for the User to add to their 🔴 READING LIST:
            $new_blog_count = 0;
            $quick_replies = array();

            foreach ($search_results as $alg) {

                //Fetch metadata:
                $ins = $this->BLOG_model->in_fetch(array(
                    'in_id' => $alg['alg_obj_id'],
                    'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
                ));
                if(count($ins) < 1){
                    continue;
                }

                //Make sure not already in 🔴 READING LIST:
                if(count($this->READ_model->ln_fetch(array(
                        'ln_creator_player_id' => $en['en_id'],
                        'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'ln_parent_blog_id' => $alg['alg_obj_id'],
                    ))) > 0){
                    continue;
                }

                $new_blog_count++;

                if($new_blog_count==1){
                    $message = 'I found these blogs for "'.$master_command.'":';
                }

                //List Blog:
                $time_range = echo_time_range($ins[0]);
                $message .= "\n\n" . $new_blog_count . '. ' . $ins[0]['in_title'] . ( $time_range ? ' in ' . strip_tags($time_range) : '' );
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => $new_blog_count,
                    'payload' => 'SUBSCRIBE-CONFIRM_' . $ins[0]['in_id'], //'SUBSCRIBE-INITIATE_' . $ins[0]['in_id']
                ));
            }


            //Log blog search:
            $this->READ_model->ln_create(array(
                'ln_content' => ( $new_blog_count > 0 ? $message : 'Found ' . $new_blog_count . ' blog' . echo__s($new_blog_count) . ' matching [' . $master_command . ']' ),
                'ln_metadata' => array(
                    'app_enable_algolia' => intval(config_var(11062)),
                    'new_blog_count' => $new_blog_count,
                    'input_data' => $master_command,
                    'output' => $search_results,
                ),
                'ln_creator_player_id' => $en['en_id'], //user who searched
                'ln_type_player_id' => 4275, //User Text Command Search for
            ));


            if($new_blog_count > 0){

                //Give them a "None of the above" option:
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Cancel',
                    'payload' => 'SUBSCRIBE-REJECT',
                ));

                //return what we found to the user to decide:
                $this->READ_model->dispatch_message(
                    $message,
                    $en,
                    true,
                    $quick_replies
                );

            } else {

                //Respond to user:
                $this->READ_model->dispatch_message(
                    'I did not find any blogs to "' . $master_command . '", but I have made a note of this and will let you know as soon as I am trained on this.',
                    $en,
                    true
                );

                //READ RECOMMENDATIONS
                $this->READ_model->dispatch_message(
                    echo_random_message('read_recommendation'),
                    $en,
                    true
                );

            }

        } else {


            /*
             *
             * Ok, if we're here it means we didn't really understand what
             * the User's blog was within their message.
             * So let's run through a few more options before letting them
             * know that we did not understand them...
             *
             * */


            //Quick Reply Manual Response...
            //We could not match the user command to any other command...
            //Now try to fetch the last quick reply that the user received from us:
            $last_quick_replies = $this->READ_model->ln_fetch(array(
                'ln_creator_player_id' => $en['en_id'],
                'ln_type_player_id' => 6563, //User Received Quick Reply
            ), array(), 1);

            if(count($last_quick_replies) > 0){

                //We did find a recent quick reply!
                $ln_metadata = unserialize($last_quick_replies[0]['ln_metadata']);

                if(isset($ln_metadata['output_message']['message_body']['message']['quick_replies'])){

                    //Go through them:
                    foreach($ln_metadata['output_message']['message_body']['message']['quick_replies'] as $quick_reply){

                        //let's see if their text matches any of the quick reply options:
                        if(substr($fb_received_message, 0, strlen($quick_reply['title'])) == strtolower($quick_reply['title'])){

                            //Yes! We found a match, trigger the payload:
                            $quick_reply_results = $this->READ_model->digest_received_payload($en, $quick_reply['payload']);

                            if(!$quick_reply_results['status']){

                                //There was an error, inform Trainer:
                                $this->READ_model->ln_create(array(
                                    'ln_content' => 'digest_received_payload() for custom response ['.$fb_received_message.'] returned error ['.$quick_reply_results['message'].']',
                                    'ln_metadata' => $ln_metadata,
                                    'ln_type_player_id' => 4246, //Platform Bug Reports
                                    'ln_creator_player_id' => $en['en_id'],
                                    'ln_parent_read_id' => $last_quick_replies[0]['ln_id'],
                                ));

                            } else {

                                //All good, log link:
                                $this->READ_model->ln_create(array(
                                    'ln_creator_player_id' => $en['en_id'],
                                    'ln_type_player_id' => 4460, //User Sent Answer
                                    'ln_parent_read_id' => $last_quick_replies[0]['ln_id'],
                                    'ln_content' => $fb_received_message,
                                ));

                                //We resolved it:
                                return true;

                            }
                        }
                    }
                }
            }




            //Let's check to see if a Mench Trainer has not started a manual conversation with them via Facebook Inbox Chat:
            if (count($this->READ_model->ln_fetch(array(
                    'ln_order' => 1, //A HACK to identify messages sent from us via Facebook Page Inbox
                    'ln_creator_player_id' => $en['en_id'],
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4280')) . ')' => null, //User Received Messages with Messenger
                    'ln_timestamp >=' => date("Y-m-d H:i:s", (time() - (1800))), //Messages sent from us less than 30 minutes ago
                ), array(), 1)) > 0) {

                //Yes, this user is talking to an Trainer so do not interrupt their conversation:
                return false;

            }


            //We don't know what they are talking about!


            //Inform User of Mench's one-way communication limitation & that Mench did not understand their message:
            $this->READ_model->dispatch_message(
                echo_random_message('one_way_only'),
                $en,
                true
            );

            //Log link:
            $this->READ_model->ln_create(array(
                'ln_creator_player_id' => $en['en_id'], //User who initiated this message
                'ln_content' => $fb_received_message,
                'ln_type_player_id' => 4287, //Log Unrecognizable Message Received
            ));

            //Call to Action: Does this user have any 🔴 READING LISTs?
            $next_in_id = $this->READ_model->read_next_go($en['en_id'], false);

            if($next_in_id > 0){

                //Inform User of Mench's one-way communication limitation & that Mench did not understand their message:
                $this->READ_model->dispatch_message(
                    'You can continue with your 🔴 READING LIST by saying "Next"',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            } else {

                //READ RECOMMENDATIONS
                $this->READ_model->dispatch_message(
                    echo_random_message('read_recommendation'),
                    $en,
                    true
                );

            }
        }
    }



    function dispatch_emails($to_array, $subject, $html_message)
    {

        /*
         *
         * Send an email via our Amazon server
         *
         * */

        if (is_dev_environment()) {
            return false; //We cannot send emails on Dev server
        }

        //Loadup amazon SES:
        require_once('application/libraries/aws/aws-autoloader.php');
        $this->CLIENT = new Aws\Ses\SesClient([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => $this->config->item('cred_aws'),
        ]);

        return $this->CLIENT->sendEmail(array(
            // Source is required
            'Source' => 'support@mench.com',
            // Destination is required
            'Destination' => array(
                'ToAddresses' => $to_array,
                'CcAddresses' => array(),
                'BccAddresses' => array(),
            ),
            // Message is required
            'Message' => array(
                // Subject is required
                'Subject' => array(
                    // Data is required
                    'Data' => $subject,
                    'Charset' => 'UTF-8',
                ),
                // Body is required
                'Body' => array(
                    'Text' => array(
                        // Data is required
                        'Data' => strip_tags($html_message),
                        'Charset' => 'UTF-8',
                    ),
                    'Html' => array(
                        // Data is required
                        'Data' => $html_message,
                        'Charset' => 'UTF-8',
                    ),
                ),
            ),
            'ReplyToAddresses' => array('support@mench.com'),
            'ReturnPath' => 'support@mench.com',
        ));
    }


}