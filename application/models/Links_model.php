<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Links_model extends CI_Model
{

    /*
     *
     * Entity related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }

    function ln_update($id, $update_columns, $ln_creator_entity_id = 0, $ln_type_entity_id = 4242 /* Link Updated */)
    {

        if (count($update_columns) == 0) {
            return false;
        } elseif (!in_array($ln_type_entity_id, $this->config->item('en_ids_4593'))) {
            return false;
        }

        if($ln_creator_entity_id > 0){
            //Fetch link before updating:
            $before_data = $this->Links_model->ln_fetch(array(
                'ln_id' => $id,
            ));
        }

        //Update metadata if needed:
        if(isset($update_columns['ln_metadata']) && is_array($update_columns['ln_metadata'])){
            $update_columns['ln_metadata'] = serialize($update_columns['ln_metadata']);
        }

        //Update:
        $this->db->where('ln_id', $id);
        $this->db->update('table_links', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Log changes if successful:

        if ($affected_rows > 0) {

            if($ln_creator_entity_id > 0){

                $en_all_6186 = $this->config->item('en_all_6186'); //Link Statuses

                //Log modification link for every field changed:
                foreach ($update_columns as $key => $value) {

                    //Has this value changed compared to what we initially had in DB?
                    if ( !($before_data[0][$key] == $value) && in_array($key, array('ln_status_entity_id', 'ln_content', 'ln_order', 'ln_parent_entity_id', 'ln_child_entity_id', 'ln_parent_intent_id', 'ln_child_intent_id', 'ln_metadata', 'ln_type_entity_id'))) {

                        //Value has changed, log link:
                        $this->Links_model->ln_create(array(
                            'ln_parent_link_id' => $id, //Link Reference
                            'ln_creator_entity_id' => $ln_creator_entity_id,
                            'ln_type_entity_id' => $ln_type_entity_id,
                            'ln_content' => echo_clean_db_name($key) . ' changed from "' . ( $key=='ln_status_entity_id' ? $en_all_6186[$before_data[0][$key]]['m_name']  : $before_data[0][$key] ) . '" to "' . ( $key=='ln_status_entity_id' ? $en_all_6186[$value]['m_name']  : $value ) . '"',
                            'ln_metadata' => array(
                                'ln_id' => $id,
                                'field' => $key,
                                'before' => $before_data[0][$key],
                                'after' => $value,
                            ),
                            //Copy old values for parent/child intent/entity links:
                            'ln_parent_entity_id' => $before_data[0]['ln_parent_entity_id'],
                            'ln_child_entity_id'  => $before_data[0]['ln_child_entity_id'],
                            'ln_parent_intent_id' => $before_data[0]['ln_parent_intent_id'],
                            'ln_child_intent_id'  => $before_data[0]['ln_child_intent_id'],
                        ));

                    }
                }
            }

        } else {

            //This should not happen BUT was happening ALOT!
            //TODO Re-enable later and see why it keeps happening...
            /*
            $this->Links_model->ln_create(array(
                'ln_parent_link_id' => $id, //Link Reference
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_content' => 'ln_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));
            */

        }
        return $affected_rows;
    }

    function ln_fetch($match_columns = array(), $join_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('ln_id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('table_links');

        //Any intent joins?
        if (in_array('in_parent', $join_objects)) {
            $this->db->join('table_intents', 'ln_parent_intent_id=in_id','left');
        } elseif (in_array('in_child', $join_objects)) {
            $this->db->join('table_intents', 'ln_child_intent_id=in_id','left');
        }

        //Any entity joins?
        if (in_array('en_parent', $join_objects)) {
            $this->db->join('table_entities', 'ln_parent_entity_id=en_id','left');
        } elseif (in_array('en_child', $join_objects)) {
            $this->db->join('table_entities', 'ln_child_entity_id=en_id','left');
        } elseif (in_array('ln_type', $join_objects)) {
            $this->db->join('table_entities', 'ln_type_entity_id=en_id','left');
        } elseif (in_array('ln_creator', $join_objects)) {
            $this->db->join('table_entities', 'ln_creator_entity_id=en_id','left');
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
        if (!isset($insert_columns['ln_creator_entity_id']) || intval($insert_columns['ln_creator_entity_id']) < 1) {
            $insert_columns['ln_creator_entity_id'] = 0;
        }

        //Only require link type:
        if (detect_missing_columns($insert_columns, array('ln_type_entity_id'), $insert_columns['ln_creator_entity_id'])) {
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

        if (!isset($insert_columns['ln_status_entity_id'])|| is_null($insert_columns['ln_status_entity_id'])) {
            $insert_columns['ln_status_entity_id'] = 6176; //Link Published
        }

        //Set some zero defaults if not set:
        foreach (array('ln_child_intent_id', 'ln_parent_intent_id', 'ln_child_entity_id', 'ln_parent_entity_id', 'ln_parent_link_id', 'ln_external_id', 'ln_order') as $dz) {
            if (!isset($insert_columns[$dz])) {
                $insert_columns[$dz] = 0;
            }
        }


        //Determine word weight
        $insert_columns['ln_words'] = ln_type_word_count($insert_columns);

        //Lets log:
        $this->db->insert('table_links', $insert_columns);


        //Fetch inserted id:
        $insert_columns['ln_id'] = $this->db->insert_id();


        //All good huh?
        if ($insert_columns['ln_id'] < 1) {

            //This should not happen:
            $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $insert_columns['ln_creator_entity_id'],
                'ln_content' => 'ln_create() Failed to create',
                'ln_metadata' => array(
                    'input' => $insert_columns,
                ),
            ));

            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($insert_columns['ln_parent_entity_id'] > 0) {
                $algolia_sync = update_algolia('en', $insert_columns['ln_parent_entity_id']);
            }

            if ($insert_columns['ln_child_entity_id'] > 0) {
                $algolia_sync = update_algolia('en', $insert_columns['ln_child_entity_id']);
            }

            if ($insert_columns['ln_parent_intent_id'] > 0) {
                $algolia_sync = update_algolia('in', $insert_columns['ln_parent_intent_id']);
            }

            if ($insert_columns['ln_child_intent_id'] > 0) {
                $algolia_sync = update_algolia('in', $insert_columns['ln_child_intent_id']);
            }
        }



        //See if this link type has any subscribers:
        if(in_array($insert_columns['ln_type_entity_id'] , $this->config->item('en_ids_5967')) && $insert_columns['ln_type_entity_id']!=5967 /* Email Sent causes endless loop */ && !is_dev_environment()){

            //Try to fetch subscribers:
            $en_all_5967 = $this->config->item('en_all_5967'); //Include subscription details
            $sub_emails = array();
            $sub_en_ids = array();
            foreach(explode(',', one_two_explode('&var_en_subscriber_ids=','', $en_all_5967[$insert_columns['ln_type_entity_id']]['m_desc'])) as $subscriber_en_id){

                //Do not email the miner themselves, as already they know about their own engagement:
                if($insert_columns['ln_type_entity_id']==4246 /* Always report bugs */ || $subscriber_en_id != $insert_columns['ln_creator_entity_id']){

                    //Try fetching subscribers email:
                    foreach($this->Links_model->ln_fetch(array(
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
                        'ln_type_entity_id' => 4255, //Linked Entities Text (Email is text)
                        'ln_parent_entity_id' => 3288, //Mench Email
                        'ln_child_entity_id' => $subscriber_en_id,
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

                if($insert_columns['ln_creator_entity_id'] > 0){

                    //Fetch miner details:
                    $miner_ens = $this->Entities_model->en_fetch(array(
                        'en_id' => $insert_columns['ln_creator_entity_id'],
                    ));

                    $miner_name = $miner_ens[0]['en_name'];

                } else {

                    //No miner:
                    $miner_name = $this->config->item('system_name');

                }


                //Email Subject:
                $subject = 'Notification: '  . $miner_name . ' ' . $en_all_5967[$insert_columns['ln_type_entity_id']]['m_name'];

                //Compose email body, start with link content:
                $html_message = '<div>' . ( strlen($insert_columns['ln_content']) > 0 ? $insert_columns['ln_content'] : '<i>No link content</i>') . '</div><br />';

                //Append link object links:
                foreach ($this->config->item('tr_object_links') as $ln_field => $obj_type) {
                    if (intval($insert_columns[$ln_field]) > 0) {

                        if ($obj_type == 'in') {

                            //Fetch Intent:
                            $ins = $this->Intents_model->in_fetch(array(
                                'in_id' => $insert_columns[$ln_field],
                            ));
                            $html_message .= '<div>' . echo_clean_db_name($ln_field) . ': <a href="https://mench.com/intents/' . $ins[0]['in_id'] . '" target="_parent">#'.$ins[0]['in_id'].' '.$ins[0]['in_outcome'].'</a></div>';

                        } elseif ($obj_type == 'en') {

                            //Fetch entity:
                            $ens = $this->Entities_model->en_fetch(array(
                                'en_id' => $insert_columns[$ln_field],
                            ));
                            $html_message .= '<div>' . echo_clean_db_name($ln_field) . ': <a href="https://mench.com/entities/' . $ens[0]['en_id'] . '" target="_parent">@'.$ens[0]['en_id'].' '.$ens[0]['en_name'].'</a></div>';

                        } elseif ($obj_type == 'ln') {

                            //Include link:
                            $html_message .= '<div>' . echo_clean_db_name($ln_field) . ' ID: <a href="https://mench.com/links?ln_id=' . $insert_columns[$ln_field] . '" target="_parent">'.$insert_columns[$ln_field].'</a></div>';

                        }
                    }
                }

                //Finally append link ID:
                $html_message .= '<div>Link ID: <a href="https://mench.com/links?ln_id=' . $insert_columns['ln_id'] . '" target="_blank">' . $insert_columns['ln_id'] . '</a></div>';

                //Inform how to change settings:
                $html_message .= '<div style="color: #AAAAAA; font-size:0.9em; margin-top:20px;">Manage your email notifications via <a href="https://mench.com/entities/5967" target="_blank">@5967</a></div>';

                //Send email:
                $dispatched_email = $this->Communication_model->dispatch_emails($sub_emails, $subject, $html_message);

                //Log emails sent:
                foreach($sub_en_ids as $to_en_id){
                    $this->Links_model->ln_create(array(
                        'ln_type_entity_id' => 5967, //Link Carbon Copy Email
                        'ln_creator_entity_id' => $to_en_id, //Sent to this user
                        'ln_metadata' => $dispatched_email, //Save a copy of email
                        'ln_parent_link_id' => $insert_columns['ln_id'], //Save link

                        //Import potential intent/entity connections from link:
                        'ln_child_intent_id' => $insert_columns['ln_child_intent_id'],
                        'ln_parent_intent_id' => $insert_columns['ln_parent_intent_id'],
                        'ln_child_entity_id' => $insert_columns['ln_child_entity_id'],
                        'ln_parent_entity_id' => $insert_columns['ln_parent_entity_id'],
                    ));
                }

            }

        }




        //See if this is a Link Intent Subscription Types?
        $related_intents = array();
        if($insert_columns['ln_child_intent_id'] > 0){
            array_push($related_intents, $insert_columns['ln_child_intent_id']);
        }
        if($insert_columns['ln_parent_intent_id'] > 0){
            array_push($related_intents, $insert_columns['ln_parent_intent_id']);
        }
        if(count($related_intents) > 0 && in_array($insert_columns['ln_status_entity_id'] , $this->config->item('en_ids_7359') /* Link Statuses Public */) && in_array($insert_columns['ln_type_entity_id'] , $this->config->item('en_ids_7703')) && $insert_columns['ln_type_entity_id']!=7702 /* User Intent Subscription Update */ && !is_dev_environment()){

        }


        //Return:
        return $insert_columns;

    }

    function ln_max_order($match_columns)
    {

        //Counts the current highest order value
        $this->db->select('MAX(ln_order) as largest_order');
        $this->db->from('table_links');
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