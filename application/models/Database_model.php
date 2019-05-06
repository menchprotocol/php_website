<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Database_model extends CI_Model
{

    /*
     *
     * This model does basic CRUD (Create, Read,
     * Update & Delete) operations on Mench's
     * three main tables:
     *
     * - table_entities
     * - table_intents
     * - table_links
     *
     * Think of this as the most internal layer
     * input/output processor for our platform.
     *
     * Also updated content with Algolia which
     * is our third-party search engine.
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function en_create($insert_columns, $external_sync = false, $ln_miner_entity_id = 0)
    {

        //What is required to create a new intent?
        if (detect_missing_columns($insert_columns, array('en_status', 'en_name'))) {
            return false;
        }

        if (isset($insert_columns['en_metadata'])) {
            $insert_columns['en_metadata'] = serialize($insert_columns['en_metadata']);
        } else {
            $insert_columns['en_metadata'] = null;
        }

        if (!isset($insert_columns['en_trust_score'])) {
            //Will be later calculated via a cron job:
            $insert_columns['en_trust_score'] = 0;
        }

        //Lets now add:
        $this->db->insert('table_entities', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['en_id'])) {
            $insert_columns['en_id'] = $this->db->insert_id();
        }

        if ($insert_columns['en_id'] > 0) {

            if ($external_sync) {

                //Update Algolia:
                $algolia_sync = $this->Database_model->update_algolia('en', $insert_columns['en_id']);

                //Log link new entity:
                $this->Database_model->ln_create(array(
                    'ln_miner_entity_id' => ($ln_miner_entity_id > 0 ? $ln_miner_entity_id : $insert_columns['en_id']),
                    'ln_child_entity_id' => $insert_columns['en_id'],
                    'ln_type_entity_id' => 4251, //New Entity Created
                    'ln_metadata' => array(
                        'algolia_sync' => $algolia_sync,
                    ),
                ));

                //Fetch to return the complete entity data:
                $ens = $this->Database_model->en_fetch(array(
                    'en_id' => $insert_columns['en_id'],
                ));

                return $ens[0];

            } else {

                //Return provided inputs plus the new entity ID:
                return $insert_columns;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->Database_model->ln_create(array(
                'ln_parent_entity_id' => $ln_miner_entity_id,
                'ln_content' => 'en_create() failed to create a new entity',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function in_create($insert_columns, $external_sync = false, $ln_miner_entity_id = 0)
    {

        //What is required to create a new intent?
        if (detect_missing_columns($insert_columns, array('in_outcome', 'in_verb_entity_id'))) {
            return false;
        }

        if (isset($insert_columns['in_metadata']) && count($insert_columns['in_metadata']) > 0) {
            $insert_columns['in_metadata'] = serialize($insert_columns['in_metadata']);
        } else {
            $insert_columns['in_metadata'] = null;
        }

        if (!isset($insert_columns['in_requirement_entity_id'])) {
            $insert_columns['in_requirement_entity_id'] = 6087; //No Response Required
        }

        if (!isset($insert_columns['in_status'])) {
            $insert_columns['in_status'] = 0; //New Intent
        }

        //Lets now add:
        $this->db->insert('table_intents', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['in_id'])) {
            $insert_columns['in_id'] = $this->db->insert_id();
        }

        if ($insert_columns['in_id'] > 0) {

            if ($external_sync) {

                //Update Algolia:
                $algolia_sync = $this->Database_model->update_algolia('in', $insert_columns['in_id']);

                //Log link new entity:
                $this->Database_model->ln_create(array(
                    'ln_miner_entity_id' => $ln_miner_entity_id,
                    'ln_child_intent_id' => $insert_columns['in_id'],
                    'ln_type_entity_id' => 4250, //New Intent Created
                    'ln_metadata' => array(
                        'algolia_sync' => $algolia_sync,
                    ),
                ));

                //Fetch to return the complete entity data:
                $ins = $this->Database_model->in_fetch(array(
                    'in_id' => $insert_columns['in_id'],
                ));

                return $ins[0];

            } else {

                //Return provided inputs plus the new entity ID:
                return $insert_columns;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->Database_model->ln_create(array(
                'ln_parent_entity_id' => $ln_miner_entity_id,
                'ln_content' => 'in_create() failed to create a new intent',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function ln_create($insert_columns, $external_sync = false)
    {

        if (detect_missing_columns($insert_columns, array('ln_type_entity_id', 'ln_miner_entity_id'))) {
            return false;
        } elseif(intval($insert_columns['ln_miner_entity_id']) < 1){
            return false;
        }

        //Unset un-allowed columns to be manually added:
        if (isset($insert_columns['ln_points'])) {
            unset($insert_columns['ln_points']);
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

        if (!isset($insert_columns['ln_status'])|| is_null($insert_columns['ln_status'])) {
            $insert_columns['ln_status'] = 2; //Published
        }

        //Set some zero defaults if not set:
        foreach (array('ln_child_intent_id', 'ln_parent_intent_id', 'ln_child_entity_id', 'ln_parent_entity_id', 'ln_parent_link_id') as $dz) {
            if (!isset($insert_columns[$dz])) {
                $insert_columns[$dz] = 0;
            }
        }


        //Points for each Link Type:
        $en_all_4595 = $this->config->item('en_all_4595');

        //Does this link type award points?
        if(in_array($insert_columns['ln_type_entity_id'], $en_all_4595) && doubleval($en_all_4595[$insert_columns['ln_type_entity_id']]['m_desc']) != 0){
            //Yes, issue points:
            $insert_columns['ln_points'] = doubleval($en_all_4595[$insert_columns['ln_type_entity_id']]['m_desc']);
        }

        //Lets log:
        $this->db->insert('table_links', $insert_columns);

        //Fetch inserted id:
        $insert_columns['ln_id'] = $this->db->insert_id();

        //All good huh?
        if ($insert_columns['ln_id'] < 1) {

            //This should not happen:
            $this->Database_model->ln_create(array(
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'ln_create() Failed to create',
                'ln_metadata' => array(
                    'input' => $insert_columns,
                ),
            ));

            return false;

        } elseif($insert_columns['ln_miner_entity_id'] < 1){

            //This should not happen:
            $this->Database_model->ln_create(array(
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'ln_create() missing miner',
                'ln_metadata' => array(
                    'input' => $insert_columns,
                ),
            ));

            return false;

        }

        //Sync algolia?
        if ($external_sync) {
            if ($insert_columns['ln_parent_entity_id'] > 0) {
                $algolia_sync = $this->Database_model->update_algolia('en', $insert_columns['ln_parent_entity_id']);
            }

            if ($insert_columns['ln_child_entity_id'] > 0) {
                $algolia_sync = $this->Database_model->update_algolia('en', $insert_columns['ln_child_entity_id']);
            }

            if ($insert_columns['ln_parent_intent_id'] > 0) {
                $algolia_sync = $this->Database_model->update_algolia('in', $insert_columns['ln_parent_intent_id']);
            }

            if ($insert_columns['ln_child_intent_id'] > 0) {
                $algolia_sync = $this->Database_model->update_algolia('in', $insert_columns['ln_child_intent_id']);
            }
        }



        //See if this link type has any subscribers:
        if(in_array($insert_columns['ln_type_entity_id'] , $this->config->item('en_ids_5966')) && $insert_columns['ln_type_entity_id']!=5967 /* Email Sent causes endless loop */ && !is_dev()){

            //Try to fetch subscribers:
            $en_all_5966 = $this->config->item('en_all_5966'); //Include subscription details
            $sub_emails = array();
            $sub_en_ids = array();
            foreach(explode(',', one_two_explode('&var_en_subscriber_ids=','', $en_all_5966[$insert_columns['ln_type_entity_id']]['m_desc'])) as $subscriber_en_id){

                //Do not email the miner themselves, as already they know:
                if($insert_columns['ln_type_entity_id']==4246 /* Always report bugs */ || $subscriber_en_id != $insert_columns['ln_miner_entity_id']){

                    //Try fetching subscribers email:
                    foreach($this->Database_model->ln_fetch(array(
                        'ln_status' => 2, //Published
                        'en_status' => 2, //Published
                        'ln_type_entity_id' => 4255, //Linked Entities Text (Email is text)
                        'ln_parent_entity_id' => 3288, //Email Address
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

                //Fetch miner details:
                $miner_ens = $this->Database_model->en_fetch(array(
                    'en_id' => $insert_columns['ln_miner_entity_id'],
                ));

                //Email Subject:
                $subject = 'Notification: '  . $miner_ens[0]['en_name'] . ' ' . $en_all_5966[$insert_columns['ln_type_entity_id']]['m_name'];

                //Compose email body, start with link content:
                $html_message = '<div>' . ( strlen($insert_columns['ln_content']) > 0 ? $insert_columns['ln_content'] : '<i>No link content</i>') . '</div><br />';

                //Append link object links:
                foreach ($this->config->item('tr_object_links') as $ln_field => $obj_type) {
                   if (intval($insert_columns[$ln_field]) > 0) {

                       if ($obj_type == 'in') {

                           //Fetch Intent:
                           $ins = $this->Database_model->in_fetch(array(
                               'in_id' => $insert_columns[$ln_field],
                           ));
                           $html_message .= '<div>' . echo_clean_db_name($ln_field) . ': <a href="https://mench.com/intents/' . $ins[0]['in_id'] . '" target="_parent">#'.$ins[0]['in_id'].' '.$ins[0]['in_outcome'].'</a></div>';

                       } elseif ($obj_type == 'en') {

                           //Fetch entity:
                           $ens = $this->Database_model->en_fetch(array(
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
                $html_message .= '<div style="color: #AAAAAA; font-size:0.9em; margin-top:20px;">Manage your email notifications via <a href="https://mench.com/entities/5966" target="_blank">@5966</a></div>';

                //Send email:
                $this->Communication_model->dispatch_email($sub_emails, $sub_en_ids, $subject, $html_message);

            }

        }

        //Return:
        return $insert_columns;

    }


    function en_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array('en_trust_score' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target entities:
        $this->db->select($select);
        $this->db->from('table_entities');
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
        $res = $q->result_array();


        //Now fetch parents:
        foreach ($res as $key => $val) {

            //This will Count ALL the children:
            if (in_array('en__child_count', $join_objects)) {

                //ACount children:
                $res[$key]['en__child_count'] = $this->Platform_model->en_child_count($val['en_id']);

            }

            //This will fetch Children up to a maximum of $this->config->item('items_per_page')
            if (in_array('en__children', $join_objects)) {

                $res[$key]['en__children'] = $this->Database_model->ln_fetch(array(
                    'ln_parent_entity_id' => $val['en_id'],
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'ln_status >=' => 0, //New+
                    'en_status >=' => 0, //New+
                ), array('en_child'), $this->config->item('items_per_page'), 0, array('ln_order' => 'ASC', 'en_trust_score' => 'DESC'));

            }


            //Always fetch entity parents unless explicitly requested not to:
            if (in_array('skip_en__parents', $join_objects)) {

                $res[$key]['en__parents'] = array();

            } else {

                //Fetch parents by default:
                $res[$key]['en__parents'] = $this->Database_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'ln_child_entity_id' => $val['en_id'], //This child entity
                    'ln_status >=' => 0, //New+
                    'en_status >=' => 0, //New+
                ), array('en_parent'), 0, 0, array('en_trust_score' => 'DESC'));

            }
        }

        return $res;
    }

    function in_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for intents
        $this->db->select($select);
        $this->db->from('table_intents');

        if (in_array('in_verb_entity_id', $join_objects)) {
            $this->db->join('table_entities', 'in_verb_entity_id=en_id', 'left');
        }

        foreach ($match_columns as $key => $value) {
            $this->db->where($key, $value);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }
        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        $ins = $q->result_array();

        foreach ($ins as $key => $value) {

            //Should we append Intent Notes?
            if (in_array('in__messages', $join_objects)) {
                $ins[$key]['in__messages'] = $this->Database_model->ln_fetch(array(
                    'ln_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                    'ln_child_intent_id' => $value['in_id'],
                ), array(), 0, 0, array('ln_order' => 'ASC'));
            }

            //Should we fetch all parent intentions?
            if (in_array('in__parents', $join_objects)) {

                $ins[$key]['in__parents'] = $this->Database_model->ln_fetch(array(
                    'ln_status >=' => 0, //New+
                    'in_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_child_intent_id' => $value['in_id'],
                ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

            }

            //Have we been asked to append any children/granchildren to this query?
            if (in_array('in__children', $join_objects) || in_array('in__grandchildren', $join_objects)) {

                //Fetch immediate children:
                $ins[$key]['in__children'] = $this->Database_model->ln_fetch(array(
                    'ln_status >=' => 0, //New+
                    'in_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_parent_intent_id' => $value['in_id'],
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC')); //Child intents must be ordered


                if (in_array('in__grandchildren', $join_objects) && count($ins[$key]['in__children']) > 0) {
                    //Fetch second-level grandchildren intents:
                    foreach ($ins[$key]['in__children'] as $key2 => $value2) {

                        $ins[$key]['in__children'][$key2]['in__grandchildren'] = $this->Database_model->ln_fetch(array(
                            'ln_status >=' => 0, //New+
                            'in_status >=' => 0, //New+
                            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                            'ln_parent_intent_id' => $value2['in_id'],
                        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')); //Child intents must be ordered

                    }
                }
            }
        }

        //Return everything that was collected:
        return $ins;
    }

    function ln_fetch($match_columns, $join_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('ln_id' => 'DESC'), $select = '*', $group_by = null)
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
        } elseif (in_array('en_type', $join_objects)) {
            $this->db->join('table_entities', 'ln_type_entity_id=en_id','left');
        } elseif (in_array('en_miner', $join_objects)) {
            $this->db->join('table_entities', 'ln_miner_entity_id=en_id','left');
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


    function en_update($id, $update_columns, $external_sync = false, $ln_miner_entity_id = 0)
    {

        /*
         *
         * $external_sync helps log a link for the new entity that is about to
         * be created but we yet dont have its entity ID to use in $ln_miner_entity_id!
         *
         * */

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current entity filed values so we can compare later on after we've updated it:
        if($external_sync){
            $before_data = $this->Database_model->en_fetch(array('en_id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['en_metadata']) && is_array($update_columns['en_metadata'])){
            $update_columns['en_metadata'] = serialize($update_columns['en_metadata']);
        }


        //Update:
        $this->db->where('en_id', $id);
        $this->db->update('table_entities', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $external_sync) {

            $fixed_fields = $this->config->item('fixed_fields');

            //Log modification link for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('en_metadata', 'en_trust_score'))) {



                    //Value has changed, log link:
                    $this->Database_model->ln_create(array(
                        'ln_miner_entity_id' => ($ln_miner_entity_id > 0 ? $ln_miner_entity_id : $id),
                        'ln_type_entity_id' => 4263, //Entity Attribute Modified
                        'ln_child_entity_id' => $id,
                        'ln_content' => echo_clean_db_name($key) . ' changed from "' . ( $key=='en_status' ? $fixed_fields['en_status'][$before_data[0][$key]]['s_name'] : $before_data[0][$key] ) . '" to "' . ( $key=='en_status' ? $fixed_fields['en_status'][$value]['s_name'] : $value ) . '"',
                        'ln_metadata' => array(
                            'en_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));


                }

            }

            //Sync algolia:
            $algolia_sync = $this->Database_model->update_algolia('en', $id);

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->Database_model->ln_create(array(
                'ln_child_entity_id' => $id,
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'en_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function in_update($id, $update_columns, $external_sync = false, $ln_miner_entity_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current intent filed values so we can compare later on after we've updated it:
        if($external_sync){
            $before_data = $this->Database_model->in_fetch(array('in_id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['in_metadata']) && is_array($update_columns['in_metadata'])) {
            $update_columns['in_metadata'] = serialize($update_columns['in_metadata']);
        }

        //Update:
        $this->db->where('in_id', $id);
        $this->db->update('table_intents', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $external_sync) {

            $fixed_fields = $this->config->item('fixed_fields');

            //Note that unlike entity modification, we require a miner entity ID to log the modification link:
            //Log modification link for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('in_metadata'))) {

                    //Value has changed, log link:
                    $this->Database_model->ln_create(array(
                        'ln_miner_entity_id' => $ln_miner_entity_id,
                        'ln_type_entity_id' => 4264, //Intent Attribute Modified
                        'ln_child_intent_id' => $id,
                        'ln_content' => echo_clean_db_name($key) . ' changed from "' . ( in_array($key , array('in_type','in_status')) ? $fixed_fields[$key][$before_data[0][$key]]['s_name']  : $before_data[0][$key] ) . '" to "' . ( in_array($key , array('in_type','in_status')) ? $fixed_fields[$key][$value]['s_name'] : $value ) . '"',
                        'ln_metadata' => array(
                            'in_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));

                }

            }

            //Sync algolia:
            $this->Database_model->update_algolia('in', $id);

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->Database_model->ln_create(array(
                'ln_child_intent_id' => $id,
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'in_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function ln_update($id, $update_columns, $ln_miner_entity_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        if($ln_miner_entity_id > 0){
            //Fetch link before updating:
            $before_data = $this->Database_model->ln_fetch(array(
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

            if($ln_miner_entity_id > 0){

                $fixed_fields = $this->config->item('fixed_fields');

                //Log modification link for every field changed:
                foreach ($update_columns as $key => $value) {

                    //Has this value changed compared to what we initially had in DB?
                    if ( !($before_data[0][$key] == $value) && in_array($key, array('ln_status', 'ln_content', 'ln_order', 'ln_parent_entity_id', 'ln_child_entity_id', 'ln_parent_intent_id', 'ln_child_intent_id', 'ln_metadata', 'ln_type_entity_id'))) {

                        //Value has changed, log link:
                        $this->Database_model->ln_create(array(
                            'ln_parent_link_id' => $id, //Link Reference
                            'ln_miner_entity_id' => $ln_miner_entity_id,
                            'ln_type_entity_id' => 4242, //Link Attribute Modified
                            'ln_content' => echo_clean_db_name($key) . ' changed from "' . ( $key=='ln_status' ? $fixed_fields['ln_status'][$before_data[0][$key]]['s_name']  : $before_data[0][$key] ) . '" to "' . ( $key=='ln_status' ? $fixed_fields['ln_status'][$value]['s_name']  : $value ) . '"',
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
            $this->Database_model->ln_create(array(
                'ln_parent_link_id' => $id, //Link Reference
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'ln_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));
            */

        }
        return $affected_rows;
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


    function update_algolia($input_obj_type = null, $input_obj_id = 0, $return_row_only = false)
    {

        /*
         *
         * Syncs intents/entities with Algolia Index
         *
         * */

        $valid_objects = array('en','in');

        if (!$this->config->item('app_enable_algolia')) {
            //Algolia is disabled, so avoid syncing:
            return array(
                'status' => 0,
                'message' => 'Algolia disabled',
            );
        } elseif($input_obj_type && !in_array($input_obj_type , $valid_objects)){
            return array(
                'status' => 0,
                'message' => 'Object type is invalid',
            );
        } elseif(($input_obj_type && !$input_obj_id) || ($input_obj_id && !$input_obj_type)){
            return array(
                'status' => 0,
                'message' => 'Must define both object type and ID',
            );
        }

        //Define the support objects indexed on algolia:
        $fixed_fields = $this->config->item('fixed_fields'); //Needed for intent Icon
        $input_obj_id = intval($input_obj_id);
        $limits = array();


        if (!$return_row_only) {

            if(is_dev()){
                //Do a call on live as this does not work on local due to security limitations:
                return json_decode(@file_get_contents("https://mench.com/links/cron__sync_algolia/" . ( $input_obj_type ? $input_obj_type . '/' . $input_obj_id : '' )));
            }

            //Load Algolia Index
            $search_index = load_php_algolia('alg_index');
        }


        //Which objects are we fetching?
        if ($input_obj_type) {

            //We'll only fetch a specific type:
            $fetch_objects = array($input_obj_type);

        } else {

            //Do both intents and entities:
            $fetch_objects = $valid_objects;

            if (!$return_row_only) {
                //We need to update the entire index, so let's truncate it first:
                $search_index->clearIndex();

                //Boost processing power:
                boost_power();
            }
        }


        $all_export_rows = array();
        $all_db_rows = array();
        $synced_count = 0;
        foreach($fetch_objects as $loop_obj){

            //Remove any limits:
            unset($limits);

            //Fetch item(s) for updates including their parents:
            if ($loop_obj == 'in') {

                if($input_obj_id){
                    $limits['in_id'] = $input_obj_id;
                } else {
                    $limits['in_status >='] = 0; //New+
                }

                $db_rows['in'] = $this->Database_model->in_fetch($limits, array('in__messages'));

            } elseif ($loop_obj == 'en') {

                if($input_obj_id){
                    $limits['en_id'] = $input_obj_id;
                } else {
                    $limits['en_status >='] = 0; //New+
                }

                $db_rows['en'] = $this->Database_model->en_fetch($limits, array('en__parents'));

            }




            //Build the index:
            foreach ($db_rows[$loop_obj] as $db_row) {

                //Prepare variables:
                unset($export_row);
                $export_row = array();


                //Attempt to fetch Algolia object ID from object Metadata:
                if($input_obj_type){

                    if (strlen($db_row[$loop_obj . '_metadata']) > 0) {

                        //We have a metadata, so we might have the Algolia ID stored. Let's check:
                        $metadata = unserialize($db_row[$loop_obj . '_metadata']);
                        if (isset($metadata[$loop_obj . '__algolia_id']) && intval($metadata[$loop_obj . '__algolia_id']) > 0) {
                            //We found it! Let's just update existing algolia record
                            $export_row['objectID'] = intval($metadata[$loop_obj . '__algolia_id']);
                        }

                    }

                } else {

                    //Clear possible metadata algolia ID's that have been cached:
                    $this->Database_model->update_metadata($loop_obj, $db_row[$loop_obj.'_id'], array(
                        $loop_obj . '__algolia_id' => null, //Since all objects have been mass removed!
                    ));

                }

                //To hold parent intents/entities:
                $export_row['_tags'] = array();

                //Now build object-specific index:
                if ($loop_obj == 'en') {

                    //Count published children:
                    $published_child_count = $this->Database_model->ln_fetch(array(
                        'ln_parent_entity_id' => $db_row['en_id'],
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                        'ln_status' => 2, //Published
                        'en_status' => 2, //Published
                    ), array('en_child'), 0, 0, array(), 'COUNT(ln_id) AS published_child_count');

                    $export_row['alg_obj_is_in'] = 0;
                    $export_row['alg_obj_id'] = intval($db_row['en_id']);
                    $export_row['alg_obj_weight'] = $db_row['en_trust_score'];
                    $export_row['alg_obj_published_children'] = $published_child_count[0]['published_child_count'];
                    $export_row['alg_obj_status'] = intval($db_row['en_status']);
                    $export_row['alg_obj_icon'] = ( strlen($db_row['en_icon']) > 0 ? $db_row['en_icon'] : '<i class="fas fa-at grey-at"></i>' );
                    $export_row['alg_obj_name'] = $db_row['en_name'];
                    $export_row['alg_obj_postfix'] = ''; //Entities have no post-fix at this time

                    //Add keywords:
                    $export_row['alg_obj_keywords'] = '';
                    foreach ($db_row['en__parents'] as $ln) {

                        //Always add to tags:
                        array_push($export_row['_tags'], 'tag_en_parent_' . $ln['en_id']);

                        //Add content to keywords if any:
                        if (strlen($ln['ln_content']) > 0) {
                            $export_row['alg_obj_keywords'] .= $ln['ln_content'] . ' ';
                        }
                    }
                    $export_row['alg_obj_keywords'] = trim(strip_tags($export_row['alg_obj_keywords']));

                } elseif ($loop_obj == 'in') {

                    //See if this tree has a time-range:
                    $time_range = echo_time_range($db_row, true, true);
                    $metadata = unserialize($db_row['in_metadata']);

                    $export_row['alg_obj_is_in'] = 1;
                    $export_row['alg_obj_id'] = intval($db_row['in_id']);
                    $export_row['alg_obj_weight'] = ( isset($metadata['in__metadata_max_seconds']) ? $metadata['in__metadata_max_seconds'] : 0 );
                    $export_row['alg_obj_published_children'] = ( isset($metadata['in__metadata_max_steps']) ? $metadata['in__metadata_max_steps'] : 0 );
                    $export_row['alg_obj_status'] = intval($db_row['in_status']);
                    $export_row['alg_obj_icon'] = $fixed_fields['in_type'][$db_row['in_type']]['s_icon']; //Entity type icon
                    $export_row['alg_obj_name'] = $db_row['in_outcome'];
                    $export_row['alg_obj_postfix'] =  ( $time_range ? '<span class="alg-postfix"><i class="fal fa-clock"></i>' . $time_range . '</span>' : '');

                    //Add parent/child tags: (No use for now, so will remove this) (If wanted to include again, add "in__parents" to intent query)
                    /*
                    foreach ($db_row['in__parents'] as $ln) {
                        //Always add to tags:
                        array_push($export_row['_tags'], 'tag_in_parent_' . $ln['in_id']);
                    }
                    */

                    //Add keywords:
                    $export_row['alg_obj_keywords'] = '';
                    foreach ($db_row['in__messages'] as $ln) {
                        $export_row['alg_obj_keywords'] .= $ln['ln_content'] . ' ';
                    }
                    $export_row['alg_obj_keywords'] = trim(strip_tags($export_row['alg_obj_keywords']));

                }

                //Add to main array
                array_push($all_export_rows, $export_row);
                array_push($all_db_rows, $db_row);

            }
        }

        //Did we find anything?
        if(count($all_export_rows) < 1){

            return false;

        } elseif($return_row_only){

            if($input_obj_id > 0){
                //We  have a specific item we're looking for...
                return $all_export_rows[0];
            } else {
                return $all_export_rows;
            }

        }

        //Now let's see what to do with the index (Update, Create or delete)
        if ($input_obj_type) {

            //We should have fetched a single item only, meaning $all_export_rows[0] is what we are focused on...

            //What's the status? Is it active or should it be removed?
            if ($all_db_rows[0][$input_obj_type . '_status'] >= 0) {

                if (isset($all_export_rows[0]['objectID'])) {

                    //Update existing index:
                    $algolia_results = $search_index->saveObjects($all_export_rows);

                } else {

                    //We do not have an index to an Algolia object locally, so create a new index:
                    $algolia_results = $search_index->addObjects($all_export_rows);

                    //Now update local database with the new objectIDs:
                    if (isset($algolia_results['objectIDs']) && count($algolia_results['objectIDs']) == 1 ) {
                        foreach ($algolia_results['objectIDs'] as $key => $algolia_id) {
                            $this->Database_model->update_metadata($input_obj_type, $all_db_rows[$key][$input_obj_type.'_id'], array(
                                $input_obj_type . '__algolia_id' => $algolia_id, //The newly created algolia object
                            ));
                        }
                    }

                }

                $synced_count += 1;

            } else {

                if (isset($all_export_rows[0]['objectID'])) {

                    //Object is removed locally but still indexed remotely on Algolia, so let's remove it from Algolia:

                    //Remove from algolia:
                    $algolia_results = $search_index->deleteObject($all_export_rows[0]['objectID']);

                    //also set its algolia_id to 0 locally:
                    $this->Database_model->update_metadata($input_obj_type, $all_db_rows[0][$input_obj_type.'_id'], array(
                        $input_obj_type . '__algolia_id' => null, //Since this item has been removed!
                    ));

                    $synced_count += 1;

                } else {
                    //Nothing to do here since we don't have the Algolia object locally!
                }

            }

        } else {



            /*
             *
             * This is a mass update request.
             *
             * All remote objects have already been removed from the Algolia
             * index & metadata algolia_ids have all been set to zero!
             *
             * We're ready to create new items and update local
             *
             * */

            $algolia_results = $search_index->addObjects($all_export_rows);

            //Now update database with the objectIDs:
            if (isset($algolia_results['objectIDs']) && count($algolia_results['objectIDs']) == count($all_db_rows) ) {

                foreach ($algolia_results['objectIDs'] as $key => $algolia_id) {

                    $this_obj = ( isset($all_db_rows[$key]['in_id']) ? 'in' : 'en');

                    $this->Database_model->update_metadata($this_obj, $all_db_rows[$key][$this_obj.'_id'], array(
                        $this_obj . '__algolia_id' => intval($algolia_id),
                    ));
                }

            }

            $synced_count += count($algolia_results['objectIDs']);

        }
        
        



        //Return results:
        return array(
            'status' => ( $synced_count > 0 ? 1 : 0),
            'message' => $synced_count . ' objects sync with Algolia',
        );

    }



    function update_metadata($obj_type, $obj_id, $new_fields)
    {

        /*
         *
         * Enables the easy manipulation of the text metadata field which holds cache data for developers
         *
         * $obj_type:               Either in, en or tr
         *
         * $obj:                    The Entity, Intent or Link itself.
         *                          We're looking for the $obj ID and METADATA
         *
         * $new_fields:             The new array of metadata fields to be Set,
         *                          Updated or Removed (If set to null)
         *
         * */

        if (!in_array($obj_type, array('in', 'en', 'ln')) || $obj_id < 1 || count($new_fields) < 1) {
            return false;
        }

        //Fetch metadata for this object:
        if ($obj_type == 'in') {

            $db_objects = $this->Database_model->in_fetch(array(
                $obj_type . '_id' => $obj_id,
            ));

        } elseif ($obj_type == 'en') {

            $db_objects = $this->Database_model->en_fetch(array(
                $obj_type . '_id' => $obj_id,
            ));

        } elseif ($obj_type == 'ln') {

            $db_objects = $this->Database_model->ln_fetch(array(
                $obj_type . '_id' => $obj_id,
            ));

        }

        if (count($db_objects) < 1) {
            return false;
        }


        //Prepare newly fetched metadata:
        if (strlen($db_objects[0][$obj_type . '_metadata']) > 0) {
            $metadata = unserialize($db_objects[0][$obj_type . '_metadata']);
        } else {
            $metadata = array();
        }

        //Go through all the new fields and see if they differ from current metadata fields:
        foreach ($new_fields as $metadata_key => $metadata_value) {
            //We are doing an absolute adjustment if needed:
            if (is_null($metadata_value) && isset($metadata[$metadata_key])) {

                //User asked to remove this value:
                unset($metadata[$metadata_key]);

            } elseif (!is_null($metadata_value) && (!isset($metadata[$metadata_key]) || $metadata[$metadata_key] != $metadata_value)) {

                //Value has changed, adjust:
                $metadata[$metadata_key] = $metadata_value;

            }
        }

        //Now update DB without logging any links as this is considered a back-end update:
        if ($obj_type == 'in') {

            $affected_rows = $this->Database_model->in_update($obj_id, array(
                'in_metadata' => $metadata,
            ));

        } elseif ($obj_type == 'en') {

            $affected_rows = $this->Database_model->en_update($obj_id, array(
                'en_metadata' => $metadata,
            ));

        } elseif ($obj_type == 'ln') {

            $affected_rows = $this->Database_model->ln_update($obj_id, array(
                'ln_metadata' => $metadata,
            ));

        }

        //Should be all good:
        return $affected_rows;

    }


}
