<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Old_model extends CI_Model
{

    //This model handles all DB calls from our local database.

    function __construct()
    {
        parent::__construct();
    }



    function en_match_metadata($key, $value)
    {

        //Uses the en_metadata variable in config to determine if the current item has a valid entity parent or not.
        $en_user_metadata = $this->config->item('en_user_metadata');

        //Is this a timezone? We might need some adjustments if so...
        if ($key == 'en_timezones') {
            $valid_halfs = array(-4, -3, 3, 4, 9); //These are timezones with half values so far
            $decimal = fmod(doubleval($value), 1);
            if (!($decimal == 0)) {
                $whole = intval(str_replace('.' . $decimal, '', $value));
                if (in_array(intval($whole), $valid_halfs)) {
                    $value = $whole + ($whole < 0 ? -0.5 : +0.5);
                } else {
                    $value = round(doubleval($value));
                }
            }
        }

        if (isset($en_user_metadata[$key][strtolower($value)])) {
            //Found it, return entity ID:
            return $en_user_metadata[$key][strtolower($value)];
        } else {
            //Ooops, this value did not exist! Notify the admin so we can look into this:
            $this->Database_model->tr_create(array(
                'tr_content' => 'en_match_metadata() failed to find cached variable [' . $key . ']=[' . $value . ']. Look into the cron/en_metadata() function and update this accordingly.',
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }
    }




    function u_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array('u__e_score' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target entities:
        $this->db->select($select);
        $this->db->from('tb_entities u');
        $this->db->join('tb_entity_urls x', 'x.x_id = u_cover_x_id', 'left'); //Fetch the cover photo if >0
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

        return $res;
    }


    function x_fetch($match_columns, $join_objects = array(), $order_columns = array(), $limit = 0)
    {
        //Fetch the target entities:
        $this->db->select('*');
        $this->db->from('tb_entity_urls x');
        if (in_array('en', $join_objects)) {
            $this->db->join('tb_entities u', 'u_id=x.x_u_id', 'left');
        }
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }

        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }

        if ($limit > 0) {
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $res = $q->result_array();

        return $res;
    }


    function ur_children_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $select = '*', $group_by = null, $order_columns = array(
        'u__e_score' => 'DESC',
    ))
    {

        //Missing anything?
        $this->db->select($select);
        $this->db->from('tb_entities u');
        $this->db->join('tb_entity_links ur', 'ur_child_u_id = u_id');
        $this->db->join('tb_entity_urls x', 'x.x_id = u_cover_x_id', 'left'); //Fetch the cover photo if >0
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

        return $res;
    }


    function cr_children_fetch($match_columns, $join_objects = array())
    {

        //Missing anything?
        $this->db->select('*');
        $this->db->from('tb_intents c');
        $this->db->join('tb_intent_links cr', 'cr_child_c_id = c_id');
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        $this->db->order_by('cr_child_rank', 'ASC');
        $q = $this->db->get();
        $return = $q->result_array();

        //Return the package:
        return $return;
    }

    function cr_parents_fetch($match_columns, $join_objects = array())
    {
        //Missing anything?
        $this->db->select('*');
        $this->db->from('tb_intents c');
        $this->db->join('tb_intent_links cr', 'cr_parent_c_id = c_id');
        foreach ($match_columns as $key => $value) {
            $this->db->where($key, $value);
        }
        $q = $this->db->get();
        $return = $q->result_array();

        return $return;
    }


    function c_fetch($match_columns, $fetch_child_levels = 0, $join_objects = array(), $order_columns = array(), $limit = 0, $limit_offset = 0, $select = '*', $group_by = null)
    {

        //The basic fetcher for intents
        $this->db->select($select);
        $this->db->from('tb_intents c');
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
        $intents = $q->result_array();

        //Return everything that was collected:
        return $intents;
    }



    function i_fetch($match_columns, $limit = 0, $join_objects = array(), $order_columns = array(
        'tr_order' => 'ASC',
    ))
    {

        $this->db->select('*');
        $this->db->from('tb_intent_messages i');
        $this->db->join('tb_intents c', 'i_c_id = c_id');
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }

        foreach ($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }

        $this->db->order_by('i_rank');
        $q = $this->db->get();
        return $q->result_array();
    }



    function w_fetch($match_columns, $join_objects = array(), $order_columns = array('w_c_rank' => 'ASC'), $limit = 0)
    {
        //Fetch the target gems:
        $this->db->select('*');
        $this->db->from('tb_actionplans w');

        if (in_array('in', $join_objects)) {
            $this->db->join('tb_intents', 'w_c_id = c_id');
        }

        if (in_array('en', $join_objects)) {
            $this->db->join('tb_entities u', 'w_child_u_id = u_id');
            if (in_array('u_x', $join_objects)) {
                $this->db->join('tb_entity_urls x', 'x.x_id = u_cover_x_id', 'left'); //Fetch the cover photo if >0
            }
        }
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        $q = $this->db->get();
        $results = $q->result_array();

        //Return everything that was collected:
        return $results;
    }




    function k_fetch($match_columns, $join_objects = array(), $order_columns = array(), $limit = 0, $select = '*', $group_by = null)
    {
        //Fetch the target gems:
        $this->db->select($select);
        $this->db->from('tb_actionplan_links k');


        if (in_array('w', $join_objects)) {
            //Also join with subscription row:
            $this->db->join('tb_actionplans w', 'w_id = k_w_id');

            if (in_array('w_c', $join_objects)) {
                //Also join with subscription row:
                $this->db->join('tb_intents c', 'c_id = w_c_id');
            }
            if (in_array('w_u', $join_objects)) {
                //Also add subscriber and their profile picture:
                $this->db->join('tb_entities u', 'u_id = w_child_u_id');
                $this->db->join('tb_entity_urls x', 'x.x_id = u.u_cover_x_id', 'left'); //Fetch the cover photo if >0
            }
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

        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }

        if ($limit > 0) {
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $results = $q->result_array();

        //Return everything that was collected:
        return $results;
    }



}