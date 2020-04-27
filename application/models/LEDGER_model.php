<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class LEDGER_model extends CI_Model
{

    /*
     *
     * Functions that CRUD data from
     * the Mench ledger
     *
     * */

    function __construct()
    {
        parent::__construct();
    }

    function ln_create($insert_columns, $external_sync = false)
    {

        //Set some defaults:
        if (!isset($insert_columns['ln_creator_source_id']) || intval($insert_columns['ln_creator_source_id']) < 1) {
            $insert_columns['ln_creator_source_id'] = 0;
        }

        //Only require link type:
        if (detect_missing_columns($insert_columns, array('ln_type_source_id'), $insert_columns['ln_creator_source_id'])) {
            return false;
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

        if (!isset($insert_columns['ln_status_source_id'])|| is_null($insert_columns['ln_status_source_id'])) {
            $insert_columns['ln_status_source_id'] = 6176; //Link Published
        }

        //Set some zero defaults if not set:
        foreach (array('ln_next_idea_id', 'ln_previous_idea_id', 'ln_portfolio_source_id', 'ln_profile_source_id', 'ln_parent_transaction_id', 'ln_external_id', 'ln_order') as $dz) {
            if (!isset($insert_columns[$dz])) {
                $insert_columns[$dz] = 0;
            }
        }

        //Lets log:
        $this->db->insert('mench_ledger', $insert_columns);


        //Fetch inserted id:
        $insert_columns['ln_id'] = $this->db->insert_id();


        //All good huh?
        if ($insert_columns['ln_id'] < 1) {

            //This should not happen:
            $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => $insert_columns['ln_creator_source_id'],
                'ln_content' => 'ln_create() Failed to create',
                'ln_metadata' => array(
                    'input' => $insert_columns,
                ),
            ));

            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($insert_columns['ln_profile_source_id'] > 0) {
                update_algolia('en', $insert_columns['ln_profile_source_id']);
            }

            if ($insert_columns['ln_portfolio_source_id'] > 0) {
                update_algolia('en', $insert_columns['ln_portfolio_source_id']);
            }

            if ($insert_columns['ln_previous_idea_id'] > 0) {
                update_algolia('in', $insert_columns['ln_previous_idea_id']);
            }

            if ($insert_columns['ln_next_idea_id'] > 0) {
                update_algolia('in', $insert_columns['ln_next_idea_id']);
            }
        }


        //SOURCE SYNC Status
        if(in_array($insert_columns['ln_type_source_id'] , $this->config->item('en_ids_12401'))){
            if($insert_columns['ln_portfolio_source_id'] > 0){
                $en_id = $insert_columns['ln_portfolio_source_id'];
            } elseif($insert_columns['ln_profile_source_id'] > 0){
                $en_id = $insert_columns['ln_profile_source_id'];
            }
            $this->SOURCE_model->en_match_ln_status($insert_columns['ln_creator_source_id'], array(
                'en_id' => $en_id,
            ));
        }

        //IDEA SYNC Status
        if(in_array($insert_columns['ln_type_source_id'] , $this->config->item('en_ids_12400'))){
            if($insert_columns['ln_next_idea_id'] > 0){
                $in_id = $insert_columns['ln_next_idea_id'];
            } elseif($insert_columns['ln_previous_idea_id'] > 0){
                $in_id = $insert_columns['ln_previous_idea_id'];
            }
            $this->IDEA_model->in_match_ln_status($insert_columns['ln_creator_source_id'], array(
                'in_id' => $in_id,
            ));
        }

        //Do we need to check for entity tagging after discover success?
        if(in_array($insert_columns['ln_type_source_id'] , $this->config->item('en_ids_6255')) && in_array($insert_columns['ln_status_source_id'] , $this->config->item('en_ids_7359')) && $insert_columns['ln_previous_idea_id'] > 0 && $insert_columns['ln_creator_source_id'] > 0){

            //See what this is:
            $detected_ln_type = ln_detect_type($insert_columns['ln_content']);

            if ($detected_ln_type['status']) {

                //Any sources to append to profile?
                foreach($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_type_source_id' => 7545, //ENTITY TAGGING
                    'ln_next_idea_id' => $insert_columns['ln_previous_idea_id'],
                    'ln_profile_source_id >' => 0, //Entity to be tagged for this Idea
                )) as $ln_tag){

                    //Generate stats:
                    $links_added = 0;
                    $links_edited = 0;
                    $links_deleted = 0;


                    //Assign tag if parent/child link NOT previously assigned:
                    $existing_links = $this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                        'ln_profile_source_id' => $ln_tag['ln_profile_source_id'],
                        'ln_portfolio_source_id' => $insert_columns['ln_creator_source_id'],
                    ));

                    if(count($existing_links)){

                        //Link previously exists, see if content value is the same:
                        if($existing_links[0]['ln_content'] == $insert_columns['ln_content'] && $existing_links[0]['ln_type_source_id'] == $detected_ln_type['ln_type_source_id']){

                            //Everything is the same, nothing to do here:
                            continue;

                        } else {

                            $links_edited++;

                            //Content value has changed, update the link:
                            $this->LEDGER_model->ln_update($existing_links[0]['ln_id'], array(
                                'ln_content' => $insert_columns['ln_content'],
                            ), $insert_columns['ln_creator_source_id'], 10657 /* Player Link Updated Content  */);

                            //Also, did the link type change based on the content change?
                            if($existing_links[0]['ln_type_source_id'] != $detected_ln_type['ln_type_source_id']){
                                $this->LEDGER_model->ln_update($existing_links[0]['ln_id'], array(
                                    'ln_type_source_id' => $detected_ln_type['ln_type_source_id'],
                                ), $insert_columns['ln_creator_source_id'], 10659 /* Player Link Updated Type */);
                            }

                        }

                    } else {

                        //See if we need to delete single selectable links:
                        foreach($this->config->item('en_ids_6204') as $single_select_en_id){
                            $single_selectable = $this->config->item('en_ids_'.$single_select_en_id);
                            if(is_array($single_selectable) && count($single_selectable) && in_array($ln_tag['ln_profile_source_id'], $single_selectable)){
                                //Delete other siblings, if any:
                                foreach($this->LEDGER_model->ln_fetch(array(
                                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                                    'ln_profile_source_id IN (' . join(',', $single_selectable) . ')' => null,
                                    'ln_profile_source_id !=' => $ln_tag['ln_profile_source_id'],
                                    'ln_portfolio_source_id' => $insert_columns['ln_creator_source_id'],
                                )) as $single_selectable_siblings_preset){
                                    $links_deleted += $this->LEDGER_model->ln_update($single_selectable_siblings_preset['ln_id'], array(
                                        'ln_status_source_id' => 6173, //Link Deleted
                                    ), $insert_columns['ln_creator_source_id'], 10673 /* Player Link Unlinked */);
                                }
                            }
                        }

                        //Create link:
                        $links_added++;
                        $this->LEDGER_model->ln_create(array(
                            'ln_type_source_id' => $detected_ln_type['ln_type_source_id'],
                            'ln_content' => $insert_columns['ln_content'],
                            'ln_creator_source_id' => $insert_columns['ln_creator_source_id'],
                            'ln_profile_source_id' => $ln_tag['ln_profile_source_id'],
                            'ln_portfolio_source_id' => $insert_columns['ln_creator_source_id'],
                        ));

                    }

                    //Track Tag:
                    $this->LEDGER_model->ln_create(array(
                        'ln_type_source_id' => 12197, //Tag Player
                        'ln_creator_source_id' => $insert_columns['ln_creator_source_id'],
                        'ln_profile_source_id' => $ln_tag['ln_profile_source_id'],
                        'ln_portfolio_source_id' => $insert_columns['ln_creator_source_id'],
                        'ln_previous_idea_id' => $insert_columns['ln_previous_idea_id'],
                        'ln_content' => $links_added.' added, '.$links_edited.' edited & '.$links_deleted.' deleted with new content ['.$insert_columns['ln_content'].']',
                    ));

                    if($links_added>0 || $links_edited>0 || $links_deleted>0){
                        //See if Session needs to be updated:
                        $session_en = superpower_assigned();
                        if($session_en && $session_en['en_id']==$insert_columns['ln_creator_source_id']){
                            //Yes, update session:
                            $this->SOURCE_model->en_activate_session($session_en, true);
                        }
                    }
                }
            }
        }


        //See if this link type has any subscribers:
        if(in_array($insert_columns['ln_type_source_id'] , $this->config->item('en_ids_5967')) && $insert_columns['ln_type_source_id']!=5967 /* Email Sent causes endless loop */ && !is_dev_environment()){

            //Try to fetch subscribers:
            $en_all_5967 = $this->config->item('en_all_5967'); //Include subscription details
            $sub_emails = array();
            $sub_en_ids = array();
            foreach(explode(',', one_two_explode('&var_en_subscriber_ids=','', $en_all_5967[$insert_columns['ln_type_source_id']]['m_desc'])) as $subscriber_en_id){

                //Do not inform the user who just took the action:
                if($subscriber_en_id==$insert_columns['ln_creator_source_id']){
                    continue;
                }

                //Try fetching subscribers email:
                foreach($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                    'ln_profile_source_id' => 3288, //Mench Email
                    'ln_portfolio_source_id' => $subscriber_en_id,
                ), array('en_portfolio')) as $en_email){
                    if(filter_var($en_email['ln_content'], FILTER_VALIDATE_EMAIL)){
                        //All good, add to list:
                        array_push($sub_en_ids , $en_email['en_id']);
                        array_push($sub_emails , $en_email['ln_content']);
                    }
                }
            }


            //Did we find any subscribers?
            if(count($sub_en_ids) > 0){

                //yes, start drafting email to be sent to them...

                if($insert_columns['ln_creator_source_id'] > 0){

                    //Fetch player details:
                    $player_ens = $this->SOURCE_model->en_fetch(array(
                        'en_id' => $insert_columns['ln_creator_source_id'],
                    ));

                    $player_name = $player_ens[0]['en_name'];

                } else {

                    //No player:
                    $player_name = 'MENCH';

                }


                //Email Subject:
                $subject = 'Notification: '  . $player_name . ' ' . $en_all_5967[$insert_columns['ln_type_source_id']]['m_name'];

                //Compose email body, start with link content:
                $html_message = '<div>' . ( strlen($insert_columns['ln_content']) > 0 ? $insert_columns['ln_content'] : '<i>No link content</i>') . '</div><br />';

                $en_all_6232 = $this->config->item('en_all_6232'); //PLATFORM VARIABLES

                //Append link object links:
                foreach ($this->config->item('en_all_11081') as $en_id => $m) {

                    if (!intval($insert_columns[$en_all_6232[$en_id]['m_desc']])) {
                        continue;
                    }

                    if (in_array(6202 , $m['m_parents'])) {

                        //IDEA
                        $ins = $this->IDEA_model->in_fetch(array( 'in_id' => $insert_columns[$en_all_6232[$en_id]['m_desc']] ));
                        $html_message .= '<div>' . $m['m_name'] . ': <a href="https://mench.com/idea/go/' . $ins[0]['in_id'] . '" target="_parent">#'.$ins[0]['in_id'].' '.$ins[0]['in_title'].'</a></div>';

                    } elseif (in_array(6160 , $m['m_parents'])) {

                        //SOURCE
                        $ens = $this->SOURCE_model->en_fetch(array( 'en_id' => $insert_columns[$en_all_6232[$en_id]['m_desc']] ));
                        $html_message .= '<div>' . $m['m_name'] . ': <a href="https://mench.com/source/' . $ens[0]['en_id'] . '" target="_parent">@'.$ens[0]['en_id'].' '.$ens[0]['en_name'].'</a></div>';

                    } elseif (in_array(4367 , $m['m_parents'])) {

                        //DISCOVER
                        $html_message .= '<div>' . $m['m_name'] . ' ID: <a href="https://mench.com/plugin/12722?ln_id=' . $insert_columns[$en_all_6232[$en_id]['m_desc']] . '" target="_parent">'.$insert_columns[$en_all_6232[$en_id]['m_desc']].'</a></div>';

                    }

                }

                //Finally append DISCOVER ID:
                $html_message .= '<div>TRANSACTION ID: <a href="https://mench.com/plugin/12722?ln_id=' . $insert_columns['ln_id'] . '">' . $insert_columns['ln_id'] . '</a></div>';

                //Inform how to change settings:
                $html_message .= '<div style="color: #DDDDDD; font-size:0.9em; margin-top:20px;">Manage your email notifications via <a href="https://mench.com/source/5967" target="_blank">@5967</a></div>';

                //Send email:
                $dispatched_email = $this->COMMUNICATION_model->comm_email_send($sub_emails, $subject, $html_message);

                //Log emails sent:
                foreach($sub_en_ids as $to_en_id){
                    $this->LEDGER_model->ln_create(array(
                        'ln_type_source_id' => 5967, //Link Carbon Copy Email
                        'ln_creator_source_id' => $to_en_id, //Sent to this user
                        'ln_metadata' => $dispatched_email, //Save a copy of email
                        'ln_parent_transaction_id' => $insert_columns['ln_id'], //Save link

                        //Import potential Idea/source connections from link:
                        'ln_next_idea_id' => $insert_columns['ln_next_idea_id'],
                        'ln_previous_idea_id' => $insert_columns['ln_previous_idea_id'],
                        'ln_portfolio_source_id' => $insert_columns['ln_portfolio_source_id'],
                        'ln_profile_source_id' => $insert_columns['ln_profile_source_id'],
                    ));
                }
            }
        }





        //See if this is a Link Idea Subscription Types?
        $related_ins = array();
        if($insert_columns['ln_next_idea_id'] > 0){
            array_push($related_ins, $insert_columns['ln_next_idea_id']);
        }
        if($insert_columns['ln_previous_idea_id'] > 0){
            array_push($related_ins, $insert_columns['ln_previous_idea_id']);
        }


        //Return:
        return $insert_columns;

    }

    function ln_fetch($match_columns = array(), $join_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('ln_id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('mench_ledger');

        //Any Idea joins?
        if (in_array('in_previous', $join_objects)) {
            $this->db->join('mench_idea', 'ln_previous_idea_id=in_id','left');
        } elseif (in_array('in_next', $join_objects)) {
            $this->db->join('mench_idea', 'ln_next_idea_id=in_id','left');
        }

        //Any source joins?
        if (in_array('en_profile', $join_objects)) {
            $this->db->join('mench_source', 'ln_profile_source_id=en_id','left');
        } elseif (in_array('en_portfolio', $join_objects)) {
            $this->db->join('mench_source', 'ln_portfolio_source_id=en_id','left');
        } elseif (in_array('en_type', $join_objects)) {
            $this->db->join('mench_source', 'ln_type_source_id=en_id','left');
        } elseif (in_array('en_creator', $join_objects)) {
            $this->db->join('mench_source', 'ln_creator_source_id=en_id','left');
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

    function ln_update($id, $update_columns, $ln_creator_source_id = 0, $ln_type_source_id = 0, $ln_content = '')
    {

        if (count($update_columns) == 0) {
            return false;
        } elseif ($ln_type_source_id>0 && !in_array($ln_type_source_id, $this->config->item('en_ids_4593'))) {
            return false;
        }

        if($ln_creator_source_id > 0){
            //Fetch link before updating:
            $before_data = $this->LEDGER_model->ln_fetch(array(
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
        $this->db->update('mench_ledger', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Log changes if successful:
        if ($affected_rows > 0 && $ln_creator_source_id > 0 && $ln_type_source_id > 0) {

            if(strlen($ln_content) == 0){
                if(in_array($ln_type_source_id, $this->config->item('en_ids_10593') /* Statement */)){

                    //Since it's a statement we want to determine the change in content:
                    if($before_data[0]['ln_content']!=$update_columns['ln_content']){
                        $ln_content .= update_description($before_data[0]['ln_content'], $update_columns['ln_content']);
                    }

                } else {

                    //Log modification link for every field changed:
                    foreach ($update_columns as $key => $value) {
                        if($before_data[0][$key]==$value){
                            continue;
                        }

                        //Now determine what type is this:
                        if($key=='ln_status_source_id'){

                            $en_all_6186 = $this->config->item('en_all_6186'); //Transaction Status
                            $ln_content .= echo_db_field($key) . ' updated from [' . $en_all_6186[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_6186[$value]['m_name'] . ']'."\n";

                        } elseif($key=='ln_type_source_id'){

                            $en_all_4593 = $this->config->item('en_all_4593'); //Link Types
                            $ln_content .= echo_db_field($key) . ' updated from [' . $en_all_4593[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_4593[$value]['m_name'] . ']'."\n";

                        } elseif(in_array($key, array('ln_profile_source_id', 'ln_portfolio_source_id'))) {

                            //Fetch new/old source names:
                            $before_ens = $this->SOURCE_model->en_fetch(array(
                                'en_id' => $before_data[0][$key],
                            ));
                            $after_ens = $this->SOURCE_model->en_fetch(array(
                                'en_id' => $value,
                            ));

                            $ln_content .= echo_db_field($key) . ' updated from [' . $before_ens[0]['en_name'] . '] to [' . $after_ens[0]['en_name'] . ']' . "\n";

                        } elseif(in_array($key, array('ln_previous_idea_id', 'ln_next_idea_id'))) {

                            //Fetch new/old Idea outcomes:
                            $before_ins = $this->IDEA_model->in_fetch(array(
                                'in_id' => $before_data[0][$key],
                            ));
                            $after_ins = $this->IDEA_model->in_fetch(array(
                                'in_id' => $value,
                            ));

                            $ln_content .= echo_db_field($key) . ' updated from [' . $before_ins[0]['in_title'] . '] to [' . $after_ins[0]['in_title'] . ']' . "\n";

                        } elseif(in_array($key, array('ln_content', 'ln_order'))){

                            $ln_content .= echo_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']'."\n";

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
                $this->LEDGER_model->ln_create(array(
                    'ln_parent_transaction_id' => $id, //Link Reference
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_type_source_id' => $ln_type_source_id,
                    'ln_content' => $ln_content,
                    'ln_metadata' => array(
                        'ln_id' => $id,
                        'fields_changed' => $fields_changed,
                    ),
                    //Copy old values for parent/child idea/source links:
                    'ln_profile_source_id' => $before_data[0]['ln_profile_source_id'],
                    'ln_portfolio_source_id'  => $before_data[0]['ln_portfolio_source_id'],
                    'ln_previous_idea_id' => $before_data[0]['ln_previous_idea_id'],
                    'ln_next_idea_id'  => $before_data[0]['ln_next_idea_id'],
                ));
            }
        }

        return $affected_rows;
    }

    function ln_max_order($match_columns)
    {

        //Counts the current highest order value
        $this->db->select('MAX(ln_order) as largest_order');
        $this->db->from('mench_ledger');
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

}