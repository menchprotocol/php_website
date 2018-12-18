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
     * - table_ledger
     *
     * Think of this as the most internal layer
     * input/output processor for our platform.
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function w_update($id, $update_columns)
    {
        $this->db->where('tr_id', $id);
        $this->db->update('tb_actionplans', $update_columns);
        return $this->db->affected_rows();
    }


    function tr_status_update($tr_id, $new_tr_status)
    {

        //Marks a single Action Plan intent as complete:
        $this->Database_model->tr_update($tr_id, array(
            'k_last_updated' => date("Y-m-d H:i:s"),
            'tr_status' => $new_tr_status, //Working On...
        ));

        if ($new_tr_status == 2) {

            //It's complete!
            //Fetch full $k object
            $trs = $this->Database_model->tr_fetch(array(
                'tr_id' => $tr_id,
            ), array('w', 'cr'));
            if (count($trs) == 0) {
                return false;
            }

            //Dispatch all on-complete messages of $in_id
            $messages = $this->Database_model->i_fetch(array(
                'tr_in_child_id' => $trs[0]['tr_in_child_id'],
                'tr_status' => 3, //On complete messages
            ));
            if (count($messages) > 0) {
                $prepared_messages = array();
                foreach ($messages as $i) {
                    array_push($prepared_messages, array_merge($i, array(
                        'tr_tr_parent_id' => $trs[0]['tr_id'],
                        'tr_en_child_id' => $trs[0]['tr_en_parent_id'],
                        'tr_in_child_id' => $i['tr_in_child_id'],
                    )));
                }
                //Sendout messages:
                $this->Chat_model->dispatch_message($prepared_messages);
            }

            //TODO Update Action Plan progress (In tr_metadata) at this point
            //TODO implement drip 'tr_en_type_id' => 4281, //Pending Drip
        }
    }


    function k_skip_recursive_down($tr_id, $update_db = true)
    {
        //TODO Readjust the removal of $tr_id, $in_id variables
        //User has requested to skip an intent starting from:
        $dwn_tree = $this->Database_model->k_recursive_fetch($tr_id, $in_id, true);
        $skip_ks = array_merge(array(intval($tr_id)), $dwn_tree['k_flat']);

        //Now see how many should we actually skip based on current status:
        $skippable_ks = $this->Database_model->tr_fetch(array(
            'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
            'tr_id IN (' . join(',', $skip_ks) . ')' => null,
        ), ($update_db ? array() : array('cr', 'cr_c_child')), 0, 0, array('tr_order' => 'ASC'));

        if ($update_db) {

            //Now start skipping:
            foreach ($skippable_ks as $k) {
                $this->Database_model->tr_status_update($k['tr_id'], -1); //skip
            }

            //There is a chance that the Action Plan might be now completed due to this skipping, lets check:
            /*
            $trs = $this->Database_model->tr_fetch(array(
                'tr_id' => $tr_id,
            ), array('w','cr','cr_c_parent'));
            if(count($trs)>0){
                $this->Matrix_model->in_actionplan_complete_up($trs[0],$trs[0],-1);
            }
            */

        }

        //Returned intents:
        return $skippable_ks;

    }


    function k_choose_or($tr_id, $tr_in_parent_id, $in_id)
    {
        //$in_id is the chosen path for the options of $tr_in_parent_id
        //When a user chooses an answer to an ANY intent, this function would mark that answer as complete while marking all siblings as SKIPPED
        $chosen_path = $this->Database_model->tr_fetch(array(
            'tr_tr_parent_id' => $tr_id,
            'tr_in_parent_id' => $tr_in_parent_id, //Fetch children of parent intent which are the siblings of current intent
            'tr_in_child_id' => $in_id, //The answer
            'in_status >=' => 2,
        ), array('w', 'cr', 'cr_c_parent'));

        if (count($chosen_path) == 1) {

            //Also fetch children to see if we requires any notes/url to mark as complete:
            $path_requirements = $this->Database_model->tr_fetch(array(
                'tr_tr_parent_id' => $tr_id,
                'tr_in_parent_id' => $tr_in_parent_id, //Fetch children of parent intent which are the siblings of current intent
                'tr_in_child_id' => $in_id, //The answer
                'in_status >=' => 2,
            ), array('w', 'cr', 'cr_c_child'));

            if (count($path_requirements) == 1) {
                //Determine status:
                $force_working_on = ((intval($path_requirements[0]['c_require_notes_to_complete']) || intval($path_requirements[0]['c_require_url_to_complete'])) ? 1 : null);

                //Now mark intent as complete (and this will SKIP all siblings) and move on:
                $this->Matrix_model->in_actionplan_complete_up($chosen_path[0], $chosen_path[0], $force_working_on);

                //Successful:
                return true;
            } else {
                return false;
            }

        } else {
            //Oooopsi, we could not find it! Log error and return false:
            $this->Database_model->tr_create(array(
                'tr_content' => 'Unable to locate OR selection for this Action Plan',
                'tr_en_type_id' => 4246, //Platform Error
                'tr_in_child_id' => $in_id,
                'tr_tr_parent_id' => $tr_id,
            ));

            return false;
        }
    }


    function k_create($insert_columns)
    {


        if (!isset($insert_columns['k_timestamp'])) {
            $insert_columns['k_timestamp'] = date("Y-m-d H:i:s");
        }

        if (!isset($insert_columns['tr_order'])) {
            //Determine the highest rank for this Action Plan:
            $insert_columns['tr_order'] = 1 + $this->Database_model->tr_max_order(array(
                    'tr_tr_parent_id' => $insert_columns['tr_tr_parent_id'],
                ));
        }


        if (!isset($insert_columns['tr_order'])) {
            $insert_columns['tr_order'] = 0;
        }


        //Lets now add:
        $this->db->insert('tb_actionplan_links', $insert_columns);

        //Fetch inserted id:
        $insert_columns['tr_id'] = $this->db->insert_id();

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

                //Assume none:
                $res[$key]['en__child_count'] = 0;

                //Do a child count:
                $child_trs = $this->Database_model->tr_fetch(array(
                    'tr_en_parent_id' => $val['en_id'],
                    'tr_en_child_id >' => 0, //Any type of children is accepted
                    'tr_status >=' => 0, //New+
                    'en_status >=' => 0, //New+
                ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

                if (count($child_trs) > 0) {
                    $res[$key]['en__child_count'] = intval($child_trs[0]['en__child_count']);
                }

            }

            //This will fetch Children up to a maximum of $this->config->item('en_per_page')
            if (in_array('en__children', $join_objects)) {

                $res[$key]['en__children'] = $this->Database_model->tr_fetch(array(
                    'tr_en_parent_id' => $val['en_id'],
                    'tr_en_child_id >' => 0, //Any type of children is accepted
                    'tr_status >=' => 0, //New+
                    'en_status >=' => 0, //New+
                ), array('en_child'), $this->config->item('en_per_page'), 0, array('en_trust_score' => 'DESC'));

                //TODO maybe consider en__grandchildren someday and add to UI?

            }


            if (in_array('en__actionplans', $join_objects)) {

                //Search & Append this Master's Action Plans:
                $res[$key]['en__actionplans'] = $this->Database_model->tr_fetch(array(
                    'tr_en_parent_id' => $val['en_id'],
                    'tr_en_type_id' => 4235, //Action Plan Intent Add
                    'tr_in_parent_id' => 0, //Top-level Action Plan intents only...
                    'tr_status >=' => 0, //New+
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

            }


            //Always fetch entity parents unless explicitly requested not to:
            if (in_array('skip_en__parents', $join_objects)) {

                $res[$key]['en__parents'] = array();

            } else {

                //Fetch parents by default:
                $res[$key]['en__parents'] = $this->Database_model->tr_fetch(array(
                    'tr_en_parent_id >' => 0, //Also has a parent assigned of any transaction type
                    'tr_en_child_id' => $val['en_id'], //This child entity
                    'tr_status >=' => 0, //New+
                    'en_status >=' => 0, //New+
                ), array('en_parent'), 0, 0, array('en_trust_score' => 'DESC'));

                //Do we also want the parents of parnets? This can be helpful in some cases...
                if (in_array('en__grandparents', $join_objects)) {

                    foreach ($res[$key]['en__parents'] as $key => $value) {

                        //Append grandparents:
                        $res[$key]['en__parents'][$key]['en__grandparents'] = $this->Database_model->tr_fetch(array(
                            'tr_en_parent_id >' => 0, //Also has a parent assigned of any transaction type
                            'tr_en_child_id' => $value['en_id'], //This child entity
                            'tr_status >=' => 0, //New+
                            'en_status >=' => 0, //New+
                        ), array('en_parent'), 0, 0, array('en_trust_score' => 'DESC'));

                    }

                }

            }
        }

        return $res;
    }


    function en_create($insert_columns, $external_sync = false, $tr_en_credit_id = 0)
    {

        //What is required to create a new intent?
        if (fn___detect_missing_columns($insert_columns, array('en_status', 'en_name'))) {
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

                //Log transaction new entity:
                $this->Database_model->tr_create(array(
                    'tr_en_credit_id' => ($tr_en_credit_id > 0 ? $tr_en_credit_id : $insert_columns['en_id']),
                    'tr_en_child_id' => $insert_columns['en_id'],
                    'tr_en_type_id' => 4251, //New Entity Created
                ));

                //Update Algolia:
                $this->Database_model->fn___algolia_sync('en', $insert_columns['en_id']);

                //Fetch to return the complete entity data:
                $entities = $this->Database_model->en_fetch(array(
                    'en_id' => $insert_columns['en_id'],
                ));

                return $entities[0];

            } else {

                //Return provided inputs plus the new entity ID:
                return $insert_columns;

            }

        } else {

            //Ooopsi, something went wrong!
            //TODO Log Bug report transaction
            return false;

        }
    }

    function tr_status_processing($e_items)
    {
        foreach ($e_items as $e) {
            if ($e['tr_id'] > 0 && $e['tr_status'] == 0) {
                $this->Database_model->tr_update($e['tr_id'], array(
                    'tr_status' => 1, //Working on... (So other cron jobs do not pickup this item again)
                ));
            }
        }
    }


    function fn___tr_create($insert_columns)
    {

        //Need either entity or intent:
        if (!isset($insert_columns['tr_in_child_id'])) {
            $this->Database_model->tr_create(array(
                'tr_content' => 'A new message requires either an Entity or Intent to be referenced to',
                'tr_metadata' => $insert_columns,
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }

        //Other required fields:
        if (fn___detect_missing_columns($insert_columns, array('tr_content'))) {
            return false;
        }

        if (!isset($insert_columns['tr_status'])) {
            $insert_columns['tr_status'] = 1;
        }
        if (!isset($insert_columns['tr_order'])) {
            $insert_columns['tr_order'] = 1;
        }

        if (!isset($insert_columns['tr_en_parent_id'])) {
            //Describes an entity:
            $insert_columns['tr_en_parent_id'] = 0;
        }
        if (!isset($insert_columns['tr_in_child_id'])) {
            //Describes an entity:
            $insert_columns['tr_in_child_id'] = 0;
        }


        //Lets now add:
        $this->db->insert('tb_intent_messages', $insert_columns);

        //Fetch inserted id:
        $insert_columns['tr_id'] = $this->db->insert_id();

        return $insert_columns;
    }


    function in_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for intents
        $this->db->select($select);
        $this->db->from('table_intents');

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

        foreach ($intents as $key => $value) {

            //Should we append intent messages?
            if (in_array('in__messages', $join_objects)) {
                $intents[$key]['in__messages'] = $this->Database_model->tr_fetch(array(
                    'tr_status >=' => 0, //New+
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent messages
                    'tr_in_child_id' => $value['in_id'],
                ), array(), 0, 0, array('tr_order' => 'ASC'));
            }

            //Should we fetch all parent intentions?
            if (in_array('in__parents', $join_objects)) {

                $intents[$key]['in__parents'] = $this->Database_model->tr_fetch(array(
                    'tr_status >=' => 0, //New+
                    'in_status >=' => 0, //New+
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_in_child_id' => $value['in_id'],
                ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

            }

            //Have we been asked to append any children/granchildren to this query?
            if (in_array('in__children', $join_objects) || in_array('in__grandchildren', $join_objects)) {

                //Fetch immediate children:
                $intents[$key]['in__children'] = $this->Database_model->tr_fetch(array(
                    'tr_status >=' => 0, //New+
                    'in_status >=' => 0, //New+
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_in_parent_id' => $value['in_id'],
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC')); //Child intents must be ordered


                if (in_array('in__grandchildren', $join_objects) && count($intents[$key]['in__children']) > 0) {
                    //Fetch second-level grandchildren intents:
                    foreach ($intents[$key]['in__children'] as $key2 => $value2) {

                        $intents[$key]['in__children'][$key2]['in__grandchildren'] = $this->Database_model->tr_fetch(array(
                            'tr_status >=' => 0, //New+
                            'in_status >=' => 0, //New+
                            'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                            'tr_in_parent_id' => $value2['in_id'],
                        ), array('in_child'), 0, 0, array('tr_order' => 'ASC')); //Child intents must be ordered

                    }
                }
            }
        }

        //Return everything that was collected:
        return $intents;
    }


    function tr_max_order($match_columns)
    {

        //Counts the current highest order value
        $this->db->select('MAX(tr_order) as largest_order');
        $this->db->from('table_ledger');
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


    function tr_parent_fetch($match_columns, $join_objects = array())
    {
        //Missing anything?
        $this->db->select('*');
        $this->db->from('tb_entities u');
        $this->db->join('tb_entity_links ur', 'tr_en_parent_id = en_id');
        $this->db->join('tb_entity_urls x', 'x.x_id = u_cover_x_id', 'left'); //Fetch the cover photo if >0
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        $this->db->order_by('en_trust_score', 'DESC');
        $q = $this->db->get();
        return $q->result_array();
    }


    function en_update($id, $update_columns, $external_sync = false, $tr_en_credit_id = 0)
    {

        /*
         *
         * $external_sync helps log a transaction for the new entity that is about to
         * be created but we yet dont have its entity ID to use in $tr_en_credit_id!
         *
         * */

        if (count($update_columns) == 0) {
            return false;
        }

        if ($external_sync) {
            //Fetch current entity filed values so we can compare later on after we've updated it:
            $before_data = $this->Database_model->en_fetch(array('en_id' => $id));

            //Make sure this was a valid id:
            if (!(count($before_data) == 1)) {
                return false;
            }
        }

        //Cleanup metadata if needed:
        $update_columns['en_metadata'] = (isset($update_columns['en_metadata']) ? serialize($update_columns['en_metadata']) : null);

        //Update:
        $this->db->where('en_id', $id);
        $this->db->update('table_entities', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows && $external_sync) {

            //Log modification transaction for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('en_metadata', 'en_trust_score'))) {

                    //Value has changed, log engagement:
                    $this->Database_model->tr_create(array(
                        'tr_en_credit_id' => ($tr_en_credit_id > 0 ? $tr_en_credit_id : $id),
                        'tr_en_type_id' => ($key == 'en_status' && intval($value) < 0 ? 4253 /* Removed */ : 4263 /* Attribute Modified */),
                        'tr_en_child_id' => $id,
                        'tr_content' => 'Entity ' . ucwords(str_replace('_', ' ', str_replace('en_', '', $key))) . ' modified from [' . $before_data[0][$key] . '] to [' . $value . ']',
                        'tr_metadata' => array(
                            'en_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));

                }

            }

            //Sync algolia:
            $this->Database_model->fn___algolia_sync('en', $id);

        }

        return $affected_rows;
    }


    function in_update($id, $update_columns, $tr_en_credit_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        if ($tr_en_credit_id > 0) {
            //Fetch current intent filed values so we can compare later on after we've updated it:
            $before_data = $this->Database_model->in_fetch(array('in_id' => $id));

            //Make sure this was a valid id:
            if (!(count($before_data) == 1)) {
                return false;
            }
        }

        //Cleanup metadata if needed:
        $update_columns['in_metadata'] = (isset($update_columns['in_metadata']) ? serialize($update_columns['in_metadata']) : null);

        //Update:
        $this->db->where('in_id', $id);
        $this->db->update('table_intents', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows && $tr_en_credit_id) {

            //Note that unlike entity modification, we require a creditor entity ID to log the modification transaction:
            //Log modification transaction for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('in_metadata'))) {

                    //Value has changed, log engagement:
                    $this->Database_model->tr_create(array(
                        'tr_en_credit_id' => $tr_en_credit_id,
                        'tr_en_type_id' => ($key == 'in_status' && intval($value) < 0 ? 4252 /* Removed */ : 4264 /* Attribute Modified */),
                        'tr_in_child_id' => $id,
                        'tr_content' => 'Intent ' . ucwords(str_replace('_', ' ', str_replace('in_', '', $key))) . ' modified from [' . $before_data[0][$key] . '] to [' . $value . ']',
                        'tr_metadata' => array(
                            'in_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));

                }

            }

            //Sync algolia:
            $this->Database_model->fn___algolia_sync('in', $id);

        }

        return $affected_rows;
    }


    function tr_update($id, $update_columns, $tr_en_credit_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        if ($tr_en_credit_id > 0) {
            //Fetch transaction before updating:
            $before_data = $this->Database_model->tr_fetch(array(
                'tr_id' => $id,
            ));

            //Make sure this was a valid id:
            if (!(count($before_data) == 1)) {
                return false;
            }
        }

        //Cleanup if needed:
        $update_columns['tr_metadata'] = (isset($update_columns['tr_metadata']) ? serialize($update_columns['tr_metadata']) : null);

        //Update:
        $this->db->where('tr_id', $id);
        $this->db->update('table_ledger', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Log changes if successful:
        if ($affected_rows && $tr_en_credit_id) {

            //Log modification transaction for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (in_array($key, array('tr_status', 'tr_content', 'tr_order', 'tr_en_parent_id', 'tr_en_child_id', 'tr_in_parent_id', 'tr_in_child_id')) && !($before_data[0][$key] == $value)) {

                    //Value has changed, log engagement:
                    $this->Database_model->tr_create(array(
                        'tr_tr_parent_id' => $id, //Parent Transaction ID
                        'tr_en_credit_id' => $tr_en_credit_id,
                        'tr_en_type_id' => ($key == 'tr_status' && in_array(intval($value), array(-1, -3)) ? 4241 /* Removed */ : 4242 /* Attribute Modified */),
                        'tr_content' => 'Transaction ' . ucwords(str_replace('_', ' ', str_replace('tr_', '', $key))) . ' modified from [' . $before_data[0][$key] . '] to [' . $value . ']',
                        'tr_metadata' => array(
                            'tr_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));
                }
            }
        }

        return $affected_rows;
    }


    function in_create($insert_columns, $external_sync = false)
    {

        //What is required to create a new intent?
        if (fn___detect_missing_columns($insert_columns, array('in_status', 'in_outcome'))) {
            return false;
        }

        if (isset($insert_columns['in_metadata']) && count($insert_columns['in_metadata']) > 0) {
            $insert_columns['in_metadata'] = serialize($insert_columns['in_metadata']);
        } else {
            $insert_columns['in_metadata'] = null;
        }

        //Lets now add:
        $this->db->insert('table_intents', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['in_id'])) {
            $insert_columns['in_id'] = $this->db->insert_id();
        }

        if ($external_sync) {
            //Update Algolia:
            $this->Database_model->fn___algolia_sync('in', $insert_columns['in_id']);
        }

        return $insert_columns;
    }


    /* ******************************
     * Other
     ****************************** */


    function x_update($id, $update_columns)
    {
        $this->db->where('x_id', $id);
        $this->db->update('tb_entity_urls', $update_columns);
        return $this->db->affected_rows();
    }


    function tr_fetch($match_columns = array(), $join_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('tr_timestamp' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('table_ledger');

        if (in_array('in_parent', $join_objects)) {
            $this->db->join('table_intents', 'tr_in_parent_id=in_id');
        } elseif (in_array('in_child', $join_objects)) {
            $this->db->join('table_intents', 'tr_in_child_id=in_id');
        }

        if (in_array('en_parent', $join_objects)) {
            $this->db->join('table_entities', 'tr_en_parent_id=en_id');
        } elseif (in_array('en_child', $join_objects)) {
            $this->db->join('table_entities', 'tr_en_child_id=en_id');
        } elseif (in_array('en_type', $join_objects)) {
            $this->db->join('table_entities', 'tr_en_type_id=en_id');
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


    function en_search_match($en_parent_id, $value)
    {

        //Is this a timezone? We need to adjust the timezone according to our limited timezone entities
        if ($en_parent_id == 3289) {
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


        //Search and see if we can find $value in the transaction content:
        $matching_entities = $this->Database_model->tr_fetch(array(
            'tr_en_parent_id' => $en_parent_id,
            'tr_en_child_id > ' => 0,
            'tr_content' => $value,
            'tr_status >=' => 0, //Pending or Active
        ), array(), 0);


        if (count($matching_entities) == 1) {

            //Bingo, return result:
            return intval($matching_entities[0]['tr_en_child_id']);

        } else {

            //Ooooopsi, this value did not exist! Notify the admin so we can look into this:
            $this->Database_model->tr_create(array(
                'tr_content' => 'en_search_match() found [' . count($matching_entities) . '] results as the children of en_id=[' . $en_parent_id . '] that had the value of [' . $value . '].',
                'tr_en_type_id' => 4246, //Platform Error
                'tr_en_child_id' => $en_parent_id,
            ));

            return 0;
        }
    }

    function tr_create($insert_columns, $external_sync = false)
    {

        if (fn___detect_missing_columns($insert_columns, array('tr_en_type_id'))) {
            return false;
        }


        //Unset un-allowed columns to be manually added:
        if (isset($insert_columns['tr_content_int'])) {
            unset($insert_columns['tr_content_int']);
        }
        if (isset($insert_columns['tr_coins'])) {
            unset($insert_columns['tr_coins']);
        }
        if (isset($insert_columns['tr_metadata'])) {
            $insert_columns['tr_metadata'] = serialize($insert_columns['tr_metadata']);
        } else {
            $insert_columns['tr_metadata'] = null;
        }


        //Try to auto detect user:
        if (!isset($insert_columns['tr_en_credit_id'])) {
            //Attempt to fetch creator ID from session:
            $entity_data = $this->session->userdata('user');
            if (isset($entity_data['en_id']) && intval($entity_data['en_id']) > 0) {
                $insert_columns['tr_en_credit_id'] = $entity_data['en_id'];
            } else {
                //Do not issue credit to anyone:
                $insert_columns['tr_en_credit_id'] = 0;
            }
        }

        //Set some defaults:
        if (!isset($insert_columns['tr_content'])) {
            $insert_columns['tr_content'] = null;
            $insert_columns['tr_content_int'] = 0;
        } elseif (is_int($insert_columns['tr_content']) && intval($insert_columns['tr_content']) > 0) {
            //Store integer separately for faster query access later on:
            $insert_columns['tr_content_int'] = intval($insert_columns['tr_content']);
        }

        if (!isset($insert_columns['tr_timestamp'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $insert_columns['tr_timestamp'] = $d->format("Y-m-d H:i:s.u");
        }

        if (!isset($insert_columns['tr_status'])) {
            $insert_columns['tr_status'] = 2; //Auto Published
        }

        //Set some zero defaults if not set:
        foreach (array('tr_in_child_id', 'tr_in_parent_id', 'tr_en_child_id', 'tr_en_parent_id', 'tr_tr_parent_id') as $dz) {
            if (!isset($insert_columns[$dz])) {
                $insert_columns[$dz] = 0;
            }
        }


        //Do we need to adjust coins?
        /*
        $award_coins = $this->Database_model->tr_fetch(array(
            'tr_en_type_id' => 4319, //Number Link
            'tr_en_parent_id' => 4374, //Transaction Coins
            'tr_en_child_id' => $insert_columns['tr_en_type_id'], //This type of transaction
            'tr_status >=' => 2, //Must be published+
            'en_status >=' => 2, //Must be published+
        ), array('en_child'), 1);
        if (count($award_coins) > 0) {
            //Yes, we have to issue coins:
            $insert_columns['tr_coins'] = doubleval($award_coins[0]['tr_content']);
        }
        */

        //Lets log:
        $this->db->insert('table_ledger', $insert_columns);

        //Fetch inserted id:
        $insert_columns['tr_id'] = $this->db->insert_id();

        //All good huh?
        if ($insert_columns['tr_id'] < 1) {
            return false;
        }


        //Now we might need to cache if this is a Video, Audio, Image or File URL:
        if (in_array($insert_columns['tr_en_type_id'], array(4258, 4259, 4260, 4261))) {
            $this->Database_model->tr_create(array(
                'tr_status' => 0, //New
                'tr_en_type_id' => 4299, //Media Uploaded
                'tr_tr_parent_id' => $insert_columns['tr_id'],
                //Replicate remaining fields:
                'tr_en_credit_id' => $insert_columns['tr_en_credit_id'],
                'tr_en_parent_id' => $insert_columns['tr_en_parent_id'],
                'tr_en_child_id' => $insert_columns['tr_en_child_id'],
                'tr_in_parent_id' => $insert_columns['tr_in_parent_id'],
                'tr_in_child_id' => $insert_columns['tr_in_child_id'],
                'tr_content' => $insert_columns['tr_content'],
            ));
        }


        //Sync algolia?
        if ($external_sync) {

            if ($insert_columns['tr_en_parent_id'] > 0) {
                $this->Database_model->fn___algolia_sync('en', $insert_columns['tr_en_parent_id']);
            }

            if ($insert_columns['tr_en_child_id'] > 0) {
                $this->Database_model->fn___algolia_sync('en', $insert_columns['tr_en_child_id']);
            }

            if ($insert_columns['tr_in_parent_id'] > 0) {
                $this->Database_model->fn___algolia_sync('in', $insert_columns['tr_in_parent_id']);
            }

            if ($insert_columns['tr_in_child_id'] > 0) {
                $this->Database_model->fn___algolia_sync('in', $insert_columns['tr_in_child_id']);
            }

        }

        //Notify subscribers for this event
        //TODO update to new system
        if (0) {

            foreach ($this->config->item('notify_admins') as $admin_en_id => $actionplan) {

                //Do not notify about own actions:
                if (intval($insert_columns['tr_en_credit_id']) == $admin_en_id) {
                    continue;
                }

                if (in_array($insert_columns['tr_en_type_id'], $actionplan['admin_notify'])) {

                    //Just do this one:
                    if (!isset($engagements[0])) {
                        //Fetch Engagement Data:
                        $engagements = $this->Database_model->tr_fetch(array(
                            'tr_id' => $insert_columns['tr_id']
                        ));
                    }

                    //Did we find it? We should have:
                    if (isset($engagements[0])) {

                        $subject = 'Notification: ' . trim(strip_tags($engagements[0]['in_outcome'])) . ' - ' . (isset($engagements[0]['en_name']) ? $engagements[0]['en_name'] : 'System');

                        //Compose email:
                        $html_message = null; //Start

                        if (strlen($engagements[0]['tr_content']) > 0) {
                            $html_message .= '<div>' . fn___echo_link(nl2br($engagements[0]['tr_content'])) . '</div><br />';
                        }

                        //Lets go through all references to see what is there:
                        foreach ($this->config->item('ledger_filters') as $engagement_field => $er) {
                            if (intval($engagements[0][$engagement_field]) > 0) {
                                //Yes we have a value here:
                                $html_message .= '<div>' . $er['name'] . ': ' . echo_object($er['object_code'], $engagements[0][$engagement_field], $engagement_field, null) . '</div>';
                            }
                        }

                        //Append ID:
                        $html_message .= '<div>Engagement ID: <a href="https://mench.com/ledger/fn___tr_print/' . $engagements[0]['tr_id'] . '">#' . $engagements[0]['tr_id'] . '</a></div>';

                        //Send email:
                        //$this->Chat_model->fn___dispatch_email($actionplan['admin_emails'], $subject, $html_message);

                        //TODO Send messenger

                    }
                }
            }
        }


        //Return:
        return $insert_columns;
    }


    function metadata_tree_update($obj_type, $focus_obj_id, $metadata_new = array(), $direction_is_downward = 0)
    {

        /*
         *
         * Keeps intent/entity metadata field in-sync when adjustments are made to tree items
         *
         * */

        //Currently only supports intents:
        if (count($metadata_new) == 0) {
            return false;
        }


        if (in_array($obj_type, array('in'))) {

            //Fetch tree that needs adjustment:
            $tree = $this->Database_model->in_recursive_fetch($focus_obj_id, $direction_is_downward);

            if (count($tree['in_flat_tree']) == 0) {
                return false;
            }

            //Now fetch them all:
            $objects = $this->Database_model->in_fetch(array(
                'in_id IN (' . join(',', $tree['in_flat_tree']) . ')' => null,
            ));

        } elseif (in_array($obj_type, array('en'))) {

            //TODO add entity support

        }

        //Apply relative changes to all objects:
        $affected_rows = 0;
        foreach ($objects as $obj) {
            //Make a relative adjustment compared to what is currently there:
            $affected_rows += $this->Database_model->metadata_update($obj_type, $obj, $metadata_new, false);
        }

        //Return total affected rows:
        return $affected_rows;

    }


    function metadata_update($obj_type, $obj, $new_fields, $absolute_adjustment = true)
    {

        /*
         *
         * Enables the easy manipulation of the text metadata field which holds cache data for developers
         *
         *   $obj_type is either in or en
         *   $field is the array key within the metadata
         *
         * */

        if (!in_array($obj_type, array('in', 'en')) || !isset($obj[$obj_type . '_metadata']) || count($new_fields) < 1) {
            return false;
        }

        //Prepare metadata:
        $metadata = unserialize($obj[$obj_type . '_metadata']);

        //Go through all the new fields and see if they differ from current metadata fields:
        foreach ($new_fields as $metadata_key => $metadata_value) {
            if (!$absolute_adjustment) {
                //We need to do a relative adjustment:
                $metadata[$metadata_key] = (isset($metadata[$metadata_key]) ? $metadata[$metadata_key] : 0) + $metadata_value;
            } else {

                //We are doing an absolute adjustment if needed:
                if (is_null($metadata_value)) {
                    //User asked to remove this value:
                    unset($metadata[$metadata_key]);
                } elseif (!isset($metadata[$metadata_key]) || $metadata[$metadata_key] !== $metadata_value) {
                    //Value has changed, adjust:
                    $metadata[$metadata_key] = $metadata_value;
                }

            }
        }

        //Now update DB without logging any transactions as this is considered a back-end update:
        if ($obj_type == 'in') {

            $affected_rows = $this->Database_model->in_update($obj['in_id'], array(
                'in_metadata' => $metadata,
            ));

        } elseif ($obj_type == 'en') {

            $affected_rows = $this->Database_model->en_update($obj['en_id'], array(
                'en_metadata' => $metadata,
            ));

        }

        //Should be all good:
        return $affected_rows;

    }


    function in_recursive_fetch($in_id, $direction_is_downward = false, $update_db_table = false, $actionplan_tr_id = 0, $parent_c = array(), $recursive_children = null)
    {

        //Get core data:
        $immediate_children = array(
            '___tree_all_count' => 0,
            '___messages_count' => 0,
            '___messages_tree_count' => 0,

            '___tree_min_seconds' => 0,
            '___tree_max_seconds' => 0,
            '___tree_min_cost' => 0,
            '___tree_max_cost' => 0,

            '___tree_experts' => array(), //Expert references across all contributions
            '___tree_miners' => array(), //miner references considering intent messages
            '___tree_contents' => array(), //Content types entity references on messages

            'metadatas_updated' => 0, //Keeps count of database metadata fields that were not in sync with the latest version of the cahced data
            'db_queries' => array(), //Useful for debugging to see what changed at each metadatas_updated request

            'in_tree' => array(), //Fetches the intent tree with its full 2-dimensional & hierarchical beauty
            'in_flat_tree' => array(), //Puts all the tree's intent IDs in a flat array, useful for quick processing
            'in_tr_flat_tree' => array(), //Puts all the tree's intent transaction (intent link) IDs in a flat array, useful for quick processing
        );

        if (!$recursive_children) {
            $recursive_children = $immediate_children;
        }

        //Fetch & add this item itself:
        if (isset($parent_c['tr_id'])) {

            if ($direction_is_downward) {
                $intents = $this->Old_model->cr_children_fetch(array(
                    'tr_id' => $parent_c['tr_id'],
                ), ($update_db_table ? array('in__messages') : array()));
            } else {
                $intents = $this->Old_model->cr_parents_fetch(array(
                    'tr_id' => $parent_c['tr_id'],
                ), ($update_db_table ? array('in__messages') : array()));
            }

        } else {

            //This is the very first item that
            $intents = $this->Database_model->in_fetch(array(
                'in_id' => $in_id,
            ), ($update_db_table ? array('in__messages') : array()));

        }


        //We should have found an item by now:
        if (count($intents) < 1) {
            return false;
        }


        //Always add intent to tree:
        array_push($immediate_children['in_flat_tree'], intval($in_id));


        //Add the link relations before we start recursion so we can have the Tree in up-custom order:
        if (isset($intents[0]['tr_id'])) {

            //Add intent link:
            array_push($immediate_children['in_tr_flat_tree'], intval($intents[0]['tr_id']));

            //Are we caching an Action Plan?
            if ($actionplan_tr_id > 0) {
                //Yes we are, create a cache of this link for this Action Plan:
                $this->Database_model->k_create(array(
                    'tr_tr_parent_id' => $actionplan_tr_id,
                    'k_cr_id' => $intents[0]['tr_id'],
                    'tr_order' => $intents[0]['tr_order'],
                ));
            }

        }

        //TODO Terminate at OR branches for Action Plan caching (when $actionplan_tr_id>0)

        if ($actionplan_tr_id > 0 && $intents[0]['in_is_any']) {
            //return false;
        }

        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        if ($direction_is_downward) {
            $child_cs = $this->Old_model->cr_children_fetch(array(
                'tr_in_parent_id' => $in_id,
                'tr_status' => 1,
                'in_status >=' => 0,
            ));
        } else {
            $child_cs = $this->Old_model->cr_parents_fetch(array(
                'tr_in_child_id' => $in_id,
                'tr_status' => 1,
                'in_status >=' => 0,
            ));
        }


        if (count($child_cs) > 0) {

            //We need to determine this based on the tree AND/OR logic:
            $local_values = array(
                'in___tree_min_seconds' => null,
                'in___tree_max_seconds' => null,
                'in___tree_min_cost' => null,
                'in___tree_max_cost' => null,
            );

            foreach ($child_cs as $c) {

                if (in_array($c['in_id'], $recursive_children['in_flat_tree'])) {

                    //Ooooops, this has an error as it would result in an infinite loop:
                    return false;

                } else {

                    //Fetch children for this intent, if any:
                    $granchildren = $this->Database_model->in_recursive_fetch($c['in_id'], $direction_is_downward, $update_db_table, $actionplan_tr_id, $c, $immediate_children);

                    if (!$granchildren) {
                        //There was an infinity break
                        return false;
                    }

                    //Addup children if any:
                    $immediate_children['___tree_all_count'] += $granchildren['___tree_all_count'];

                    if ($intents[0]['in_is_any']) {
                        //OR Branch, figure out the logic:
                        if ($granchildren['___tree_min_seconds'] < $local_values['in___tree_min_seconds'] || is_null($local_values['in___tree_min_seconds'])) {
                            $local_values['in___tree_min_seconds'] = $granchildren['___tree_min_seconds'];
                        }
                        if ($granchildren['___tree_max_seconds'] > $local_values['in___tree_max_seconds'] || is_null($local_values['in___tree_max_seconds'])) {
                            $local_values['in___tree_max_seconds'] = $granchildren['___tree_max_seconds'];
                        }
                        if ($granchildren['___tree_min_cost'] < $local_values['in___tree_min_cost'] || is_null($local_values['in___tree_min_cost'])) {
                            $local_values['in___tree_min_cost'] = $granchildren['___tree_min_cost'];
                        }
                        if ($granchildren['___tree_max_cost'] > $local_values['in___tree_max_cost'] || is_null($local_values['in___tree_max_cost'])) {
                            $local_values['in___tree_max_cost'] = $granchildren['___tree_max_cost'];
                        }
                    } else {
                        //AND Branch, add them all up:
                        $local_values['in___tree_min_seconds'] += intval($granchildren['___tree_min_seconds']);
                        $local_values['in___tree_max_seconds'] += intval($granchildren['___tree_max_seconds']);
                        $local_values['in___tree_min_cost'] += number_format($granchildren['___tree_min_cost'], 2);
                        $local_values['in___tree_max_cost'] += number_format($granchildren['___tree_max_cost'], 2);
                    }


                    if ($update_db_table) {

                        //Update DB requested:
                        $immediate_children['___messages_tree_count'] += $granchildren['___messages_tree_count'];
                        $immediate_children['metadatas_updated'] += $granchildren['metadatas_updated'];
                        if (!empty($granchildren['db_queries'])) {
                            array_push($immediate_children['db_queries'], $granchildren['db_queries']);
                        }

                        //Addup unique experts:
                        foreach ($granchildren['___tree_experts'] as $en_id => $tex) {
                            //Is this a new expert?
                            if (!isset($immediate_children['___tree_experts'][$en_id])) {
                                //Yes, add them to the list:
                                $immediate_children['___tree_experts'][$en_id] = $tex;
                            }
                        }

                        //Addup unique miners:
                        foreach ($granchildren['___tree_miners'] as $en_id => $tet) {
                            //Is this a new expert?
                            if (!isset($immediate_children['___tree_miners'][$en_id])) {
                                //Yes, add them to the list:
                                $immediate_children['___tree_miners'][$en_id] = $tet;
                            }
                        }

                        //Addup content types:
                        foreach ($granchildren['___tree_contents'] as $type_en_id => $current_us) {
                            foreach ($current_us as $en_id => $u_obj) {
                                if (!isset($immediate_children['___tree_contents'][$type_en_id][$en_id])) {
                                    //Yes, add them to the list:
                                    $immediate_children['___tree_contents'][$type_en_id][$en_id] = $u_obj;
                                }
                            }
                        }

                    }

                    array_push($immediate_children['in_tr_flat_tree'], $granchildren['in_tr_flat_tree']);
                    array_push($immediate_children['in_flat_tree'], $granchildren['in_flat_tree']);
                    array_push($immediate_children['in_tree'], $granchildren['in_tree']);
                }
            }

            //Addup the totals from this tree:
            $immediate_children['___tree_min_seconds'] += $local_values['in___tree_min_seconds'];
            $immediate_children['___tree_max_seconds'] += $local_values['in___tree_max_seconds'];
            $immediate_children['___tree_min_cost'] += $local_values['in___tree_min_cost'];
            $immediate_children['___tree_max_cost'] += $local_values['in___tree_max_cost'];
        }


        $immediate_children['___tree_all_count']++;
        $immediate_children['___tree_min_seconds'] += intval($intents[0]['in_seconds']);
        $immediate_children['___tree_max_seconds'] += intval($intents[0]['in_seconds']);
        $immediate_children['___tree_min_cost'] += number_format($intents[0]['c_cost_estimate'], 2);
        $immediate_children['___tree_max_cost'] += number_format($intents[0]['c_cost_estimate'], 2);

        //Set the data for this intent:
        $intents[0]['___tree_all_count'] = $immediate_children['___tree_all_count'];
        $intents[0]['___tree_min_seconds'] = $immediate_children['___tree_min_seconds'];
        $intents[0]['___tree_max_seconds'] = $immediate_children['___tree_max_seconds'];
        $intents[0]['___tree_min_cost'] = $immediate_children['___tree_min_cost'];
        $intents[0]['___tree_max_cost'] = $immediate_children['___tree_max_cost'];


        //Count messages only if DB updating:
        if ($update_db_table) {

            $intents[0]['___tree_experts'] = array();
            $intents[0]['___tree_miners'] = array();
            $intents[0]['___tree_contents'] = array();

            //Count messages:
            $intents[0]['___messages_count'] = count($this->Database_model->i_fetch(array(
                'tr_status >=' => 0,
                'tr_in_child_id' => $in_id,
            )));
            $immediate_children['___messages_tree_count'] += $intents[0]['___messages_count'];
            $intents[0]['___messages_tree_count'] = $immediate_children['___messages_tree_count'];


            //See who's involved:
            $parent_ids = array();
            foreach ($intents[0]['in__messages'] as $i) {

                //Who are the parent authors of this message?


                if (!in_array($i['tr_en_credit_id'], $parent_ids)) {
                    array_push($parent_ids, $i['tr_en_credit_id']);
                }


                //Check the author of this message (The miner) in the miner array:
                if (!isset($intents[0]['___tree_miners'][$i['tr_en_credit_id']])) {
                    //Add the entire message which would also hold the miner details:
                    $intents[0]['___tree_miners'][$i['tr_en_credit_id']] = fn___en_essentials($i);
                }
                //How about the parent of this one?
                if (!isset($immediate_children['___tree_miners'][$i['tr_en_credit_id']])) {
                    //Yes, add them to the list:
                    $immediate_children['___tree_miners'][$i['tr_en_credit_id']] = fn___en_essentials($i);
                }


                //Does this message have any entity references?
                if ($i['tr_en_parent_id'] > 0) {


                    //Add the reference it self:
                    if (!in_array($i['tr_en_parent_id'], $parent_ids)) {
                        array_push($parent_ids, $i['tr_en_parent_id']);
                    }

                    //Yes! Let's see if any of the parents/creators are industry experts:
                    $us_fetch = $this->Database_model->en_fetch(array(
                        'en_id' => $i['tr_en_parent_id'],
                    ));

                    if (isset($us_fetch[0]) && count($us_fetch[0]['en__parents']) > 0) {
                        //We found it, let's loop through the parents and aggregate their IDs for a single search:
                        foreach ($us_fetch[0]['en__parents'] as $parent_u) {

                            //Is this a particular content type?
                            if (in_array($parent_u['en_id'], $this->config->item('en_ids_3000'))) {
                                //yes! Add it to the list if it does not already exist:
                                if (!isset($intents[0]['___tree_contents'][$parent_u['en_id']][$us_fetch[0]['en_id']])) {
                                    $intents[0]['___tree_contents'][$parent_u['en_id']][$us_fetch[0]['en_id']] = fn___en_essentials($us_fetch[0]);
                                }

                                //How about the parent tree?
                                if (!isset($immediate_children['___tree_contents'][$parent_u['en_id']][$us_fetch[0]['en_id']])) {
                                    $immediate_children['___tree_contents'][$parent_u['en_id']][$us_fetch[0]['en_id']] = fn___en_essentials($us_fetch[0]);
                                }
                            }

                            if (!in_array($parent_u['en_id'], $parent_ids)) {
                                array_push($parent_ids, $parent_u['en_id']);
                            }
                        }
                    }
                }
            }

            //Who was involved in content patternization?
            if (count($parent_ids) > 0) {

                //Lets make a query search to see how many of those involved are industry experts:
                $ixs = $this->Old_model->ur_children_fetch(array(
                    'tr_en_parent_id' => 3084, //Industry expert entity
                    'tr_en_child_id IN (' . join(',', $parent_ids) . ')' => null,
                    'tr_status >=' => 0, //Pending review or higher
                    'en_status >=' => 0, //Pending review or higher
                ), array(), 0, 0, 'en_id, en_name, en_trust_score, x_url');

                //Put unique IDs in array key for faster searching:
                foreach ($ixs as $ixsu) {
                    if (!isset($intents[0]['___tree_experts'][$ixsu['en_id']])) {
                        $intents[0]['___tree_experts'][$ixsu['en_id']] = $ixsu;
                    }
                }
            }


            //Did we find any new industry experts?
            if (count($intents[0]['___tree_experts']) > 0) {

                //Yes, lets add them uniquely to the mother array assuming they are not already there:
                foreach ($intents[0]['___tree_experts'] as $new_ixs) {
                    //Is this a new expert?
                    if (!isset($immediate_children['___tree_experts'][$new_ixs['en_id']])) {
                        //Yes, add them to the list:
                        $immediate_children['___tree_experts'][$new_ixs['en_id']] = $new_ixs;
                    }
                }
            }
        }

        array_push($immediate_children['in_tree'], $intents[0]);


        if ($update_db_table) {

            //Assign aggregates:
            $intents[0]['___tree_experts'] = $immediate_children['___tree_experts'];
            $intents[0]['___tree_miners'] = $immediate_children['___tree_miners'];
            $intents[0]['___tree_contents'] = $immediate_children['___tree_contents'];

            //Start sorting:
            if (is_array($intents[0]['___tree_experts']) && count($intents[0]['___tree_experts']) > 0) {
                usort($intents[0]['___tree_experts'], 'fn___sortByScore');
            }
            if (is_array($intents[0]['___tree_miners']) && count($intents[0]['___tree_miners']) > 0) {
                usort($intents[0]['___tree_miners'], 'fn___sortByScore');
            }
            foreach ($intents[0]['___tree_contents'] as $type_en_id => $current_us) {
                if (isset($intents[0]['___tree_contents'][$type_en_id]) && count($intents[0]['___tree_contents'][$type_en_id]) > 0) {
                    usort($intents[0]['___tree_contents'][$type_en_id], 'fn___sortByScore');
                }
            }

            //Update DB only if any single field is not synced:
            if (!(
                intval($intents[0]['___tree_min_seconds']) == intval($intents[0]['in__tree_min_seconds']) &&
                intval($intents[0]['___tree_max_seconds']) == intval($intents[0]['in__tree_max_seconds']) &&
                number_format($intents[0]['___tree_min_cost'], 2) == number_format($intents[0]['in__tree_min_cost'], 2) &&
                number_format($intents[0]['___tree_max_cost'], 2) == number_format($intents[0]['in__tree_max_cost'], 2) &&
                ((!$intents[0]['in__tree_experts'] && count($intents[0]['___tree_experts']) < 1) || (serialize($intents[0]['___tree_experts']) == $intents[0]['in__tree_experts'])) &&
                ((!$intents[0]['in__tree_miners'] && count($intents[0]['___tree_miners']) < 1) || (serialize($intents[0]['___tree_miners']) == $intents[0]['in__tree_miners'])) &&
                ((!$intents[0]['in__tree_contents'] && count($intents[0]['___tree_contents']) < 1) || (serialize($intents[0]['___tree_contents']) == $intents[0]['in__tree_contents'])) &&
                $intents[0]['___tree_all_count'] == $intents[0]['in__tree_in_count'] &&
                $intents[0]['___messages_count'] == $intents[0]['in__message_count'] &&
                $intents[0]['___messages_tree_count'] == $intents[0]['in__message_tree_count']
            )) {

                //Something was not up to date, let's update:
                if ($this->Database_model->metadata_update('in', $intents[0], array(
                    'in__tree_min_seconds' => intval($intents[0]['___tree_min_seconds']),
                    'in__tree_max_seconds' => intval($intents[0]['___tree_max_seconds']),
                    'in__tree_min_cost' => number_format($intents[0]['___tree_min_cost'], 2),
                    'in__tree_max_cost' => number_format($intents[0]['___tree_max_cost'], 2),
                    'in__tree_in_count' => $intents[0]['___tree_all_count'],
                    'in__message_count' => $intents[0]['___messages_count'],
                    'in__message_tree_count' => $intents[0]['___messages_tree_count'],
                    'in__tree_experts' => $intents[0]['___tree_experts'],
                    'in__tree_miners' => $intents[0]['___tree_miners'],
                    'in__tree_contents' => $intents[0]['___tree_contents'],
                ))) {
                    //Yes update was successful:
                    $immediate_children['metadatas_updated']++;
                }


                array_push($immediate_children['db_queries'], '[' . $in_id . '] Seconds:' . intval($intents[0]['in__tree_max_seconds']) . '=>' . intval($intents[0]['___tree_max_seconds']) . ' / All Count:' . $intents[0]['in__tree_in_count'] . '=>' . $intents[0]['___tree_all_count'] . ' / Message:' . $intents[0]['in__message_count'] . '=>' . $intents[0]['___messages_count'] . ' / Tree Message:' . $intents[0]['in__message_tree_count'] . '=>' . $intents[0]['___messages_tree_count'] . ' (' . $intents[0]['in_outcome'] . ')');

            }
        }


        //Flatten intent ID array:
        $result = array();
        array_walk_recursive($immediate_children['in_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['in_flat_tree'] = $result;

        $result = array();
        array_walk_recursive($immediate_children['in_tr_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['in_tr_flat_tree'] = $result;


        //Return data:
        return $immediate_children;
    }


    function k_recursive_fetch($tr_id, $in_id, $direction_is_downward, $parent_c = array(), $recursive_children = null)
    {

        //Get core data:
        $immediate_children = array(
            'in_flat_tree' => array(),
            'in_tr_flat_tree' => array(),
            'k_flat' => array(),
        );

        if (!$recursive_children && !isset($parent_c['tr_id'])) {
            //First item:
            $recursive_children = $immediate_children;
            $intents = $this->Database_model->in_fetch(array(
                'in_id' => $in_id,
            ));

        } else {
            //Recursive item:
            $intents = $this->Database_model->tr_fetch(array(
                'tr_tr_parent_id' => $tr_id,
                'k_cr_id' => $parent_c['tr_id'],
            ), array('cr', ($direction_is_downward ? 'cr_c_child' : 'cr_c_parent')));
        }

        //We should have found an item by now:
        if (count($intents) < 1) {
            return false;
        }


        //Add the link relations before we start recursion so we can have the Tree in up-custom order:
        array_push($immediate_children['in_flat_tree'], intval($in_id));
        if (isset($intents[0]['tr_id'])) {
            array_push($immediate_children['in_tr_flat_tree'], intval($intents[0]['tr_id']));
            array_push($immediate_children['k_flat'], intval($intents[0]['tr_id']));
        }


        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        $child_cs = $this->Database_model->tr_fetch(array(
            'tr_tr_parent_id' => $tr_id,
            'in_status >=' => 2,
            ($direction_is_downward ? 'tr_in_parent_id' : 'tr_in_child_id') => $in_id,
        ), array('cr', ($direction_is_downward ? 'cr_c_child' : 'cr_c_parent')));


        if (count($child_cs) > 0) {
            foreach ($child_cs as $c) {

                //Fetch children for this intent, if any:
                $granchildren = $this->Database_model->k_recursive_fetch($tr_id, $c['in_id'], $direction_is_downward, $c, $immediate_children);

                //return $granchildren;

                if (!$granchildren) {
                    //There was an infinity break
                    return false;
                }

                //Addup values:
                array_push($immediate_children['in_tr_flat_tree'], $granchildren['in_tr_flat_tree']);
                array_push($immediate_children['k_flat'], $granchildren['k_flat']);
                array_push($immediate_children['in_flat_tree'], $granchildren['in_flat_tree']);
            }
        }

        //Flatten intent ID array:
        $result = array();
        array_walk_recursive($immediate_children['in_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['in_flat_tree'] = $result;

        $result = array();
        array_walk_recursive($immediate_children['in_tr_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['in_tr_flat_tree'] = $result;

        $result = array();
        array_walk_recursive($immediate_children['k_flat'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['k_flat'] = $result;

        //Return data:
        return $immediate_children;
    }


    function fn___algolia_sync($obj_type, $focus_obj_id = 0)
    {

        /*
         *
         * Syncs intents/entities with Algolia Index
         *
         * */

        if (!$this->config->item('enable_algolia')) {
            //Algolia is disabled, so avoid syncing:
            return array(
                'status' => 0,
                'message' => 'Algolia disabled via config.php',
            );
        }

        //Define the support objects indexed on algolia:
        $focus_obj_id = intval($focus_obj_id);

        //Names of Algolia indexes for each data type:
        $alg_indexes = array(
            'in' => 'alg_intents',
            'en' => 'alg_entities',
        );

        if (!array_key_exists($obj_type, $alg_indexes)) {
            return array(
                'status' => 0,
                'message' => 'Invalid object [' . $obj_type . ']',
            );
        }

        if (fn___is_dev()) {
            //Do a call on live as this does not work on local due to security limitations:
            return json_decode(fn___curl_html("https://mench.com/cron/fn___algolia_sync/" . $obj_type . "/" . $focus_obj_id));
        }

        //Load algolia
        $search_index = fn___load_php_algolia($alg_indexes[$obj_type]);

        //Boost processing power:
        fn___boost_power();

        //Prepare query limits:
        if ($focus_obj_id) {
            $limits[$obj_type . '_id'] = $focus_obj_id;
        } else {
            $limits[$obj_type . '_status >='] = 0; //New+ to be indexed in Search
        }

        //Fetch item(s) for updates including their parents:
        if ($obj_type == 'in') {
            $db_objects = $this->Database_model->in_fetch($limits, array('in__parents', 'in__messages'));
        } elseif ($obj_type == 'en') {
            $db_objects = $this->Database_model->en_fetch($limits, array('en__parents'));
        }

        //Go through selection and update:
        if (count($db_objects) == 0) {
            return array(
                'status' => 0,
                'message' => 'No items found for [' . $obj_type . ']',
            );
        }

        //Is this a Mass Update? If so, we need to do some adjustments:
        if (!$focus_obj_id) {
            //Yes it is! We need to update the entire index, so clear it first:
            $search_index->clearIndex();
        }

        //Build the index:
        $alg_objects = array();
        foreach ($db_objects as $db_obj) {

            //Prepare variables:
            unset($alg_obj);
            $alg_obj = array();
            $metadata = null;

            if (strlen($db_obj[$obj_type . '_metadata']) > 0) {

                //We have a metadata, so we might have the Algolia ID stored as well:
                $metadata = unserialize($db_obj[$obj_type . '_metadata']);
                if (isset($metadata[$obj_type . '_algolia_id']) && $metadata[$obj_type . '_algolia_id'] > 0) {

                    //Yes, we have the Algolia ID! Now let's see what to do:
                    if (!$focus_obj_id) {
                        //Also clear all metadata algolia ID's that have been cached:
                        $this->Database_model->metadata_update($obj_type, $db_obj, array(
                            $obj_type.'_algolia_id' => null, //Since this object has been removed!
                        ));
                    } else {
                        //This is a focused request and we have metadata that might include the Algolia ID:
                        $alg_obj['objectID'] = $metadata[$obj_type . '_algolia_id'];
                    }
                }
            }

            //Now build the index depending on the object type:
            if ($obj_type == 'en') {

                //Add basic entity details:
                $alg_obj['en_id'] = intval($db_obj['en_id']);
                $alg_obj['en_status'] = intval($db_obj['en_status']);
                $alg_obj['en_icon'] = $db_obj['en_icon'];
                $alg_obj['en_name'] = $db_obj['en_name'];
                $alg_obj['en_trust_score'] = intval($db_obj['en_trust_score']);

                //Add parent data:
                $alg_obj['en_parent_content'] = '';
                $alg_obj['_tags'] = array();
                foreach ($db_obj['en__parents'] as $tr) {

                    //Save the ID of parent for search filtering options if needed:
                    array_push($alg_obj['_tags'], 'en' . $tr['en_id']);

                    //Also index the content value if any:
                    if (strlen($tr['tr_content']) > 0) {
                        $alg_obj['en_parent_content'] .= $tr['tr_content'] . ' ';
                    }

                }

                //Clean keywords
                $alg_obj['en_parent_content'] = trim(strip_tags($alg_obj['en_parent_content']));

            } elseif ($obj_type == 'in') {

                //Add basic intent details:
                $alg_obj['in_id'] = intval($db_obj['in_id']);
                $alg_obj['in_status'] = intval($db_obj['in_status']);
                $alg_obj['in_outcome'] = $db_obj['in_outcome'];
                $alg_obj['in_alternatives'] = $db_obj['in_alternatives'];
                $alg_obj['in_is_any'] = intval($db_obj['in_is_any']);

                //Append some of the intent Metadata for better contextual searching:
                $alg_obj['in__tree_max_secs'] = ($metadata && isset($metadata['in__tree_max_seconds']) ? intval($metadata['in__tree_max_seconds']) : 0);
                $alg_obj['in__tree_min_secs'] = ($metadata && isset($metadata['in__tree_min_secs']) ? intval($metadata['in__tree_min_secs']) : 0);
                $alg_obj['in__tree_in_count'] = ($metadata && isset($metadata['in__tree_in_count']) ? intval($metadata['in__tree_in_count']) : 0);
                $alg_obj['in__message_tree_count'] = ($metadata && isset($metadata['in__message_tree_count']) ? intval($metadata['in__message_tree_count']) : 0);

                //Append parent intents IDs:
                $alg_obj['_tags'] = array();
                foreach ($db_obj['in__parents'] as $tr) {
                    //Save the ID of parent for search filtering options if needed:
                    array_push($alg_obj['_tags'], 'in' . $tr['in_id']);
                }

                //Append intent messages:
                $alg_obj['in_messages'] = '';
                foreach ($db_obj['in__messages'] as $tr) {
                    //Include Messages as well which will be configured with a lower search weight relative to the intent outcome:
                    $alg_obj['in_messages'] .= ' ' . $tr['tr_content'];
                }

            }

            //Add to main array
            array_push($alg_objects, $alg_obj);

        }


        //Now let's see what to do with the index (Update, Create or delete)
        if ($focus_obj_id) {

            //We should have fetched a single item only, meaning $alg_objects[0] is what we are focused on...

            //What's the status? Is it active or should it be removed?
            if ($db_objects[0][$obj_type . '_status'] >= 0) {

                if (isset($alg_objects[0]['objectID'])) {

                    //Update existing index:
                    $alg_sync_message = $search_index->saveObjects($alg_objects);

                } else {

                    //We do not have an index to an Algolia object locally, so create a new index:
                    $alg_sync_message = $search_index->addObjects($alg_objects);


                    //Now update local database with the new objectIDs:
                    if (isset($alg_sync_message['objectIDs']) && count($alg_sync_message['objectIDs']) > 0) {
                        foreach ($alg_sync_message['objectIDs'] as $key => $algolia_id) {
                            $this->Database_model->metadata_update($obj_type, $db_objects[0], array(
                                $obj_type.'_algolia_id' => $algolia_id, //The newly created algolia object
                            ));
                        }
                    }

                }

                //Return results:
                return array(
                    'status' => 1,
                    'message' => 'Object Added',
                );

            } else {

                if(isset($alg_objects[0]['objectID'])){

                    //Object is removed locally but still indexed remotely on Algolia, so let's remove it from Algolia:

                    //Remove from algolia:
                    $search_index->deleteObject($alg_objects[0]['objectID']);

                    //also set its algolia_id to 0 locally:
                    $this->Database_model->metadata_update($obj_type, $db_objects[0], array(
                        $obj_type.'_algolia_id' => null, //Since this item has been removed!
                    ));

                } else {
                    //Nothing to do here since we don't have the Algolia object locally!
                }

                //Return results:
                return array(
                    'status' => 1,
                    'message' => 'Object Removed',
                );

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

            $alg_sync_message = $search_index->addObjects($alg_objects);

            //Now update database with the objectIDs:
            if (isset($alg_sync_message['objectIDs']) && count($alg_sync_message['objectIDs']) > 0) {
                foreach ($alg_sync_message['objectIDs'] as $key => $algolia_id) {
                    $this->Database_model->metadata_update($obj_type, $db_objects[$key] /* Not sure if this works! TODO Test this to ensure, or else use $alg_objects[$key][$obj_type . '_id'] to re-fetch data */, array(
                        $obj_type.'_algolia_id' => $algolia_id,
                    ));
                }
            }

        }

        //Return results:
        return array(
            'status' => (isset($alg_sync_message['objectIDs']) && count($alg_sync_message['objectIDs']) > 0 ? 1 : 0),
            'message' => (isset($alg_sync_message['objectIDs']) ? count($alg_sync_message['objectIDs']) : 0) . ' objects updated on Algolia',
        );

    }

}
