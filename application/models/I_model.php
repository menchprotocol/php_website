<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class I_model extends CI_Model
{

    /*
     *
     * Idea related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function create($add_fields, $x__source = 0)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($add_fields, array('i__title', 'i__type'), $x__source)) {
            return false;
        }

        if(!isset($add_fields['i__duration']) || $add_fields['i__duration'] < view_memory(6404,12427)){
            $add_fields['i__duration'] = view_memory(6404,12176);
        }

        //Lets now add:
        $this->db->insert('table__i', $add_fields);

        //Fetch inserted id:
        if (!isset($add_fields['i__id'])) {
            $add_fields['i__id'] = $this->db->insert_id();
        }

        if ($add_fields['i__id'] > 0) {

            if ($x__source > 0) {

                //Log transaction new Idea:
                $this->X_model->create(array(
                    'x__source' => $x__source,
                    'x__right' => $add_fields['i__id'],
                    'x__message' => $add_fields['i__title'],
                    'x__type' => 4250, //New Idea Created
                ));

                //Also add as source:
                $this->X_model->create(array(
                    'x__source' => $x__source,
                    'x__up' => $x__source,
                    'x__type' => 4983, //IDEA SOURCES
                    'x__right' => $add_fields['i__id'],
                ), true);

                //Fetch to return the complete source data:
                $is = $this->I_model->fetch(array(
                    'i__id' => $add_fields['i__id'],
                ));

                //Update Algolia:
                update_algolia(12273, $add_fields['i__id']);

                return $is[0];

            } else {

                //Return provided inputs plus the new source ID:
                return $add_fields;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->X_model->create(array(
                'x__message' => 'i->create() failed to create a new idea',
                'x__type' => 4246, //Platform Bug Reports
                'x__source' => $x__source,
                'x__metadata' => $add_fields,
            ));
            return false;

        }
    }

    function fetch($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Ideas
        $this->db->select($select);
        $this->db->from('table__i');

        foreach($query_filters as $key => $value) {
            $this->db->where($key, $value);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }
        if (count($order_columns) > 0) {
            foreach($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        return $q->result_array();
    }

    function update($id, $update_columns, $external_sync = false, $x__source = 0, $x__type = 0)
    {

        $id = intval($id);
        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current Idea filed values so we can compare later on after we've updated it:
        if($x__source > 0){
            $before_data = $this->I_model->fetch(array('i__id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['i__metadata']) && is_array($update_columns['i__metadata'])) {
            $update_columns['i__metadata'] = serialize($update_columns['i__metadata']);
        }

        //Update:
        $this->db->where('i__id', $id);
        $this->db->update('table__i', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $x__source > 0) {

            //Unlike source modification, we require a member source ID to log the modification transaction:
            //Log modification transaction for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //Assume no SOURCE LINKS unless specifically defined:
                $x__down = 0;
                $x__up = 0;


                if($x__type) {

                    $x__message = update_description($before_data[0][$key], $value);

                } elseif($key=='i__title') {

                    $x__type = 10644; //Idea updated Outcome
                    $x__message = update_description($before_data[0][$key], $value);

                } elseif($key=='i__type'){

                    $x__type = 10648; //Idea updated Status
                    $e___4737 = $this->config->item('e___4737'); //Idea Status
                    $x__message = view_db_field($key) . ' updated from [' . $e___4737[$before_data[0][$key]]['m__title'] . '] to [' . $e___4737[$value]['m__title'] . ']';
                    $x__up = $value;
                    $x__down = $before_data[0][$key];

                } elseif($key=='i__duration') {

                    $x__type = 10650; //Idea updated Completion Time
                    $x__message = view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } elseif($key=='i__cover') {

                    $x__type = 14962; //Idea updated Cover
                    $x__message = view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }

                //Value has changed, log transaction:
                $this->X_model->create(array(
                    'x__source' => $x__source,
                    'x__type' => $x__type,
                    'x__right' => $id,
                    'x__down' => $x__down,
                    'x__up' => $x__up,
                    'x__message' => $x__message,
                    'x__metadata' => array(
                        'i__id' => $id,
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ),
                ));

            }

            if($external_sync){
                //Sync algolia:
                update_algolia(12273, $id);
            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->X_model->create(array(
                'x__right' => $id,
                'x__type' => 4246, //Platform Bug Reports
                'x__source' => $x__source,
                'x__message' => 'update() Failed to update',
                'x__metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function remove($i__id, $x__source = 0, $migrate_i__id = 0){

        $x_adjusted = 0;
        if($migrate_i__id > 0){

            //Validate this migration ID:
            $is = $this->I_model->fetch(array(
                'i__id' => $migrate_i__id,
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));

            if(count($is)){
                //Migrate Transactions:
                foreach($this->X_model->fetch(array( //Idea Transactions
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'x__type !=' => 13579, //Idea Transaction Unpublished
                    '(x__right = '.$i__id.' OR x__left = '.$i__id.')' => null,
                ), array(), 0) as $x){

                    //Migrate this transaction:
                    if($x['x__right']==$i__id){
                        $x_adjusted += $this->X_model->update($x['x__id'], array(
                            'x__right' => $migrate_i__id,
                        ), $x__source, 26785 /* Idea Link Migrated */);
                    }

                    if($x['x__left']==$i__id){
                        $x_adjusted += $this->X_model->update($x['x__id'], array(
                            'x__left' => $migrate_i__id,
                        ), $x__source, 26785 /* Idea Link Migrated */);
                    }

                }
            }

        } else {

            //REMOVE TRANSACTIONS
            foreach($this->X_model->fetch(array( //Idea Transactions
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type !=' => 13579, //Idea Transaction Unpublished
                '(x__right = '.$i__id.' OR x__left = '.$i__id.')' => null,
            ), array(), 0) as $x){
                //Delete this transaction:
                $x_adjusted += $this->X_model->update($x['x__id'], array(
                    'x__status' => 6173, //Transaction Deleted
                ), $x__source, 13579 /* Idea Transaction Unpublished */);
            }

        }



        //Return transactions deleted:
        return $x_adjusted;
    }





    function match_x_status($x__source, $query = array()){

        //STATS
        $stats = array(
            'x__type' => 4250, //Idea Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );
        $status_converter = status_converter(4737);

        foreach($this->I_model->fetch($query) as $i){

            $stats['scanned']++;

            //Find creation transaction:
            $x = $this->X_model->fetch(array(
                'x__type' => $stats['x__type'],
                'x__right' => $i['i__id'],
            ));

            if(!count($x)){

                $stats['missing_creation_fix']++;

                $this->X_model->create(array(
                    'x__source' => $x__source,
                    'x__right' => $i['i__id'],
                    'x__message' => $i['i__title'],
                    'x__type' => $stats['x__type'],
                    'x__status' => $status_converter[$i['i__type']],
                ));

            } elseif($x[0]['x__status'] != $status_converter[$i['i__type']]){

                $stats['status_sync']++;
                $this->X_model->update($x[0]['x__id'], array(
                    'x__status' => $status_converter[$i['i__type']],
                ));

            }

        }

        return $stats;
    }

    function top_startable($i){

        $top_startable = array();


        //Return the first top startable idea:
        $previous_is = $this->X_model->fetch(array(
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__right' => $i['i__id'],
        ), array('x__left'), 0);
        if(!count($previous_is)){
            //No parent, so no change to find startable
            return $top_startable;
        }


        //Try to find a startable parent idea:
        foreach($previous_is as $previous_i) {
            if(i_is_startable($previous_i)){
                array_push($top_startable, $previous_i);
            }
        }
        if(count($top_startable)){
            //Bingo:
            return $top_startable;
        }


        //Recursively go up and try to find startable idea:
        foreach($previous_is as $previous_i) {
            $top_startable_recursive = $this->I_model->top_startable($previous_i);
            if(count($top_startable_recursive)){
                $top_startable = array_merge($top_startable, $top_startable_recursive);
            }
        }

        return $top_startable;

    }

    function duplicate($i, $copy_to__id, $x__source)
    {

        $i_new = $this->I_model->create(array(
            'i__title' => 'Copy Of '.$i['i__title'],
            'i__type' => $i['i__type'],
            'i__cover' => $i['i__cover'],
        ), $x__source);

        //Copy related transactions:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___27240')) . ')' => null, //COPY Transactions
            '(x__right='.$i['i__id'].' OR x__left='.$i['i__id'].')' => null,
        ), array(), 0) as $x){

            //Duplicate transaction, with new idea
            if(!count($this->X_model->fetch(array(
                'x__type' => $x['x__type'],
                'x__metadata' => $x['x__metadata'],
                'x__left' => ( $x['x__left']==$i['i__id'] ? $i_new['i__id'] : $x['x__left'] ),
                'x__right' => ( $x['x__right']==$i['i__id'] ? $i_new['i__id'] : $x['x__right'] ),
                'x__up' => $x['x__up'],
                'x__down' => $x['x__down'],
            )))){
                $this->X_model->create(array(
                    //Copy:
                    'x__type' => $x['x__type'],
                    'x__status' => $x['x__status'],
                    'x__spectrum' => $x['x__spectrum'],
                    'x__message' => $x['x__message'],
                    'x__metadata' => $x['x__metadata'],
                    'x__up' => $x['x__up'],
                    'x__down' => $x['x__down'],
                    //Might change:
                    'x__left' => ( $x['x__left']==$i['i__id'] ? $i_new['i__id'] : $x['x__left'] ),
                    'x__right' => ( $x['x__right']==$i['i__id'] ? $i_new['i__id'] : $x['x__right'] ),
                    'x__reference' => ( $x['x__reference']>0 ? $x['x__reference'] : $x['x__id'] ), //TODO validate implications for this
                    //Always Change:
                    'x__source' => $x__source,
                ));
            }

        }

        return $this->I_model->create_or_link(11019, '', $x__source, $i_new['i__id'], $copy_to__id);

    }

    function create_or_link($x__type, $i__title, $x__source, $focus__id, $link_i__id = 0)
    {

        /*
         *
         * The main idea creation function that would create
         * appropriate transactions and return the idea view.
         *
         * Either creates an IDEA transaction between $focus__id & $link_i__id
         * (IF $link_i__id>0) OR will create a new idea with outcome $i__title
         * and transaction it to $focus__id (In this case $link_i__id will be 0)
         *
         * p.s. Inputs have previously been validated via ideas/i__add() function
         *
         * */

        //Valid Idea Addition?
        if(!in_array($x__type, $this->config->item('n___14685'))){
            return array(
                'status' => 0,
                'message' => 'Invalid Idea Creation Method',
            );
        } elseif($focus__id < 1){
            return array(
                'status' => 0,
                'message' => 'Missing Focus ID',
            );
        }

        $is_upwards = in_array($x__type, $this->config->item('n___14686'));

        //Validate Original idea
        if(in_array($x__type, $this->config->item('n___11020'))){

            if ($focus__id > 0 && $link_i__id==$focus__id) {
                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add idea to itself.',
                );
            }

            $focus_i = $this->I_model->fetch(array(
                'i__id' => intval($focus__id),
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));

            if (count($focus_i) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Focus Idea',
                );
            }

        } else {

            //Must be a Source:
            $focus_e = $this->E_model->fetch(array(
                'e__id' => intval($focus__id),
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));

            if (count($focus_e) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Focus Source',
                );
            }

        }


        //Linking to Existing or Creating New?
        if ($link_i__id > 0) {

            //Linking to $link_i__id (NOT creating any new ideas)

            //Fetch more details on the child idea we're about to transaction:
            $link_i = $this->I_model->fetch(array(
                'i__id' => $link_i__id,
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));
            if (count($link_i) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Link Idea',
                );
            }

            //Determine which is parent Idea, and which is child
            if(in_array($x__type, $this->config->item('n___11020'))){

                //PREVIOUS or NEXT

                //Duplicate Check:
                if (count($this->X_model->fetch(array(
                        'x__left' => ( $is_upwards ? $link_i[0]['i__id'] : $focus_i[0]['i__id'] ),
                        'x__right' => ( $is_upwards ? $focus_i[0]['i__id'] : $link_i[0]['i__id'] ),
                        'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    ))) > 0) {
                    return array(
                        'status' => 0,
                        'message' => 'Idea is already linked here.',
                    );
                }

                //Tree Check if Next
                if($x__type==13542 && count($this->X_model->find_previous(0, $link_i[0]['i__id'], $focus_i[0]['i__id']))){
                    return array(
                        'status' => 0,
                        'message' => 'Idea already added as previous so it cannot be added as next',
                    );
                } elseif($x__type==11019 && count($this->X_model->find_previous(0, $focus_i[0]['i__id'], $link_i[0]['i__id']))){
                    return array(
                        'status' => 0,
                        'message' => 'Idea already added as next so it cannot be added as previous',
                    );
                }

            } elseif($x__type==13550){

                //References

                //Duplicate Check:
                if(count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                    'x__up' => $focus_e[0]['e__id'],
                    'x__right' => $link_i[0]['i__id'],
                )))){
                    return array(
                        'status' => 0,
                        'message' => 'Idea already referenced to this source',
                    );
                }

            } elseif($x__type==10573){

                //My Ideas

                //Duplicate Check:
                if(count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type' => 10573, //STARRED
                    'x__up' => $focus_e[0]['e__id'],
                    'x__right' => $link_i[0]['i__id'],
                )))){
                    return array(
                        'status' => 0,
                        'message' => 'Idea already in My Ideas',
                    );
                }

                //Only themselves:
                if ($x__source!=$focus_e[0]['e__id']) {
                    return array(
                        'status' => 0,
                        'message' => 'You can only add My Ideas for yourself, not other members.',
                    );
                }

            }

            //All good so far, continue with adding:
            $i_new = $link_i[0];

        } else {

            //We are NOT adding an existing Idea, but instead, we're creating a new Idea

            //Validate Idea Outcome:
            $i__title_validation = i__title_validate($i__title);
            if(!$i__title_validation['status']){
                //We had an error, return it:
                return $i__title_validation;
            }


            //Create new Idea:
            $i_new = $this->I_model->create(array(
                'i__title' => $i__title_validation['i_clean_title'],
                'i__type' => 6677, //New Default Ideas
            ), $x__source);

        }


        //Create Idea Transaction:
        $new_i_html = null;


        if(in_array($x__type, $this->config->item('n___11020'))){

            //PREVIOUS or NEXT
            $relation = $this->X_model->create(array(
                'x__source' => $x__source,
                'x__type' => 4228, //Idea Transaction Regular read
                ( $is_upwards ? 'x__right' : 'x__left' ) => $focus__id,
                ( $is_upwards ? 'x__left' : 'x__right' ) => $i_new['i__id'],
                'x__spectrum' => 1 + $this->X_model->max_spectrum(array(
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                        'x__left' => ( $is_upwards ? $i_new['i__id'] : $focus__id ),
                    )),
            ), true);

            //Fetch and return full data to be properly shown on the UI
            $new_i = $this->X_model->fetch(array(
                ( $is_upwards ? 'x__right' : 'x__left' ) => $focus__id,
                ( $is_upwards ? 'x__left' : 'x__right' ) => $i_new['i__id'],
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ), array(($is_upwards ? 'x__left' : 'x__right')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation

            $new_i_html = view_i($x__type, 0, ( $is_upwards ? null : $focus_i[0] ), $new_i[0], true);

        } elseif($x__type == 13550){

            //Add References
            $this->X_model->create(array(
                'x__type' => 4983, //IDEA SOURCES
                'x__source' => $x__source,
                'x__up' => $focus_e[0]['e__id'],
                'x__right' => $i_new['i__id'],
            ));

            //Fetch Complete References:
            $new_i = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 4983, //IDEA SOURCES
                'x__up' => $focus_e[0]['e__id'],
                'x__right' => $i_new['i__id'],
            ), array('x__right'));

            $new_i_html = view_i($x__type, 0, null, $new_i[0], true, null, $focus_e[0]);

        } elseif($x__type == 10573){

            //My Ideas

            //Add to top of my ideas:
            $this->X_model->create(array(
                'x__type' => 10573, //STARRED
                'x__source' => $x__source,
                'x__up' => $focus_e[0]['e__id'],
                'x__right' => $i_new['i__id'],
                'x__spectrum' => 1 + $this->X_model->max_spectrum(array(
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'x__type' => 10573, //STARRED
                        'x__up' => $focus_e[0]['e__id'],
                    )),
            ), true);

            $new_i = $this->X_model->fetch(array(
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 10573, //STARRED
                'x__up' => $focus_e[0]['e__id'],
                'x__right' => $i_new['i__id'],
            ), array('x__right'));

            $new_i_html = view_i($x__type, 0, null, $new_i[0], true, null, $focus_e[0]);

        }

        //Return result:
        return array(
            'status' => 1,
            'new_i_html' => $new_i_html,
        );

    }



    function recursive_child_ids($i__id, $first_level = true){

        $recursive_i_ids = array();

        foreach($this->X_model->fetch(array(
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i__id,
        ), array('x__right')) as $next_i){

            array_push($recursive_i_ids, intval($next_i['i__id']));

            $recursive_is = $this->I_model->recursive_child_ids($next_i['i__id'], false);

            //Add to current array if we found anything:
            if(count($recursive_is) > 0){
                $recursive_i_ids = array_merge($recursive_i_ids, $recursive_is);
            }

        }

        if($first_level){
            return array_unique($recursive_i_ids);
        } else {
            return $recursive_i_ids;
        }

    }

    function recursive_parent_ids($i__id, $first_level = true){

        $recursive_i_ids = array();

        foreach($this->X_model->fetch(array(
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__right' => $i__id,
        ), array('x__left')) as $next_i){

            array_push($recursive_i_ids, intval($next_i['i__id']));

            $recursive_is = $this->I_model->recursive_parent_ids($next_i['i__id'], false);

            //Add to current array if we found anything:
            if(count($recursive_is) > 0){
                $recursive_i_ids = array_merge($recursive_i_ids, $recursive_is);
            }
        }

        if($first_level){
            return array_unique($recursive_i_ids);
        } else {
            return $recursive_i_ids;
        }
    }



    function metadata_common_base($focus_in){

        //Set variables:
        $is_first_in = ( !isset($focus_in['x__id']) ); //First idea does not have a transaction, just the idea
        $select_12883 = in_array($focus_in['i__type'] , $this->config->item('n___12883')); //IDEA TYPE SELECT ONE
        $select_12884 = in_array($focus_in['i__type'] , $this->config->item('n___12884')); //IDEA TYPE SELECT SOME
        $select_14862 = in_array($focus_in['i__type'] , $this->config->item('n___14862')); //IDEA TYPE SELECT ANY or NONE
        $children_one = array(); //To be populated only if $focus_in is select one
        $children_some = array(); //To be populated only if $focus_in is select some
        $conditional_x = array(); //To be populated only for Conditional Ideas
        $metadata_this = array(
            'p___6168' => array(), //The idea structure that would be shared with all members regardless of their quick replies (OR Idea Answers)
            'p___6228' => array(), //Ideas that may exist as a transaction to expand read by answering OR ideas
            'p___12885' => array(), //Ideas that allows members to select one or more
            'p___6283' => array(), //Ideas that may exist as a transaction to expand read via Conditional Idea transactions
        );

        //Fetch children:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $focus_in['i__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $next_i){

            //Determine action based on parent idea type:
            if(in_array($next_i['x__type'], $this->config->item('n___12842'))){

                //Conditional Idea Transaction:
                array_push($conditional_x, intval($next_i['i__id']));

            } elseif($select_12883){

                //OR parent Idea with Fixed Idea Transaction:
                array_push($children_one, intval($next_i['i__id']));

            } elseif($select_12884 || $select_14862){

                //OR parent Idea with Fixed Idea Transaction:
                array_push($children_some, intval($next_i['i__id']));

            } else {

                //AND parent Idea with Fixed Idea Transaction:
                array_push($metadata_this['p___6168'], intval($next_i['i__id']));

                //Go recursively down:
                $child_recursion = $this->I_model->metadata_common_base($next_i);


                //Aggregate recursion data:
                if(count($child_recursion['p___6168']) > 0){
                    array_push($metadata_this['p___6168'], $child_recursion['p___6168']);
                }

                //Merge expansion steps:
                if(count($child_recursion['p___6228']) > 0){
                    foreach($child_recursion['p___6228'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['p___6228'])){
                            $metadata_this['p___6228'][$key] = $value;
                        }
                    }
                }
                if(count($child_recursion['p___12885']) > 0){
                    foreach($child_recursion['p___12885'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['p___12885'])){
                            $metadata_this['p___12885'][$key] = $value;
                        }
                    }
                }
                if(count($child_recursion['p___6283']) > 0){
                    foreach($child_recursion['p___6283'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['p___6283'])){
                            $metadata_this['p___6283'][$key] = $value;
                        }
                    }
                }
            }
        }


        //Was this an OR branch that needs it's children added to the array?
        if($select_12883 && count($children_one) > 0){
            $metadata_this['p___6228'][$focus_in['i__id']] = $children_one;
        }
        if(($select_12884 || $select_14862) && count($children_some) > 0){
            $metadata_this['p___12885'][$focus_in['i__id']] = $children_some;
        }
        if(count($conditional_x) > 0){
            $metadata_this['p___6283'][$focus_in['i__id']] = $conditional_x;
        }


        //Save common base:
        if($is_first_in){

            //Make sure to add main idea to common idea:
            if(count($metadata_this['p___6168']) > 0){
                $metadata_this['p___6168'] = array_merge( array(intval($focus_in['i__id'])) , array($metadata_this['p___6168']));
            } else {
                $metadata_this['p___6168'] = array(intval($focus_in['i__id']));
            }

            update_metadata(12273, $focus_in['i__id'], array(
                'i___6168' => $metadata_this['p___6168'],
                'i___6228' => $metadata_this['p___6228'],
                'i___12885' => $metadata_this['p___12885'],
                'i___6283' => $metadata_this['p___6283'],
            ));

        }

        //Return results:
        return $metadata_this;

    }

    function mass_update($i__id, $action_e__id, $action_command1, $action_command2, $x__source)
    {

        //Alert: Has a twin function called e_mass_update()

        boost_power();

        if(!in_array($action_e__id, $this->config->item('n___12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif(in_array($action_e__id , array(12591,12592,27080,27081,27082,27083,27084,27085,27086,27087)) && !is_valid_e_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Title',
            );

        } elseif(in_array($action_e__id , array(12611,12612,27240)) && !is_valid_i_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Idea. Format must be: #123 Idea Title',
            );

        }



        //Basic input validation done, let's continue...


        //Fetch all children:
        $applied_success = 0; //To be populated...

        $is_next = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $i__id,
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));


        //Process request:
        foreach($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if(in_array($action_e__id , array(12591,12592,27080,27081,27082,27083,27084,27085,27086,27087))){

                //Check if it has this item:
                $e__profile_id = intval(one_two_explode('@',' ',$action_command1));
                $i_has_e = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                    'x__right' => $next_i['i__id'],
                    'x__up' => $e__profile_id,
                ));

                if(in_array($action_e__id , array(12591,27080,27082,27084,27086)) && !count($i_has_e)){

                    $source_mapper = array(
                        12591 => 4983, //Sources
                        27080 => 13865, //Profile Includes Any
                        27082 => 26600, //Profile Excludes All
                        27084 => 7545, //Profile Add
                        27086 => 26599, //Profile Remove
                    );

                    //Missing & Must be Added:
                    $this->X_model->create(array(
                        'x__source' => $x__source,
                        'x__up' => $e__profile_id,
                        'x__type' => $source_mapper[$action_e__id],
                        'x__right' => $next_i['i__id'],
                    ), true);

                    $applied_success++;

                } elseif(in_array($action_e__id , array(12592,27081,27083,27085,27087)) && count($i_has_e)){

                    //Has and must be deleted:
                    $this->X_model->update($i_has_e[0]['x__id'], array(
                        'x__status' => 6173,
                    ), $x__source, 10673 /* IDEA NOTES Unpublished */);

                    $applied_success++;

                }

            } elseif(in_array($action_e__id , array(12611,12612,27240))){

                //Check if it hs this item:
                $focus__id = intval(one_two_explode('#',' ',$action_command1));

                if($action_e__id==27240){

                    //Copy
                    $status = $this->I_model->duplicate($next_i, $focus__id, $x__source);

                    if($status['status']){
                        //Add Source since not there:
                        $applied_success++;
                    }

                } else {

                    $is_previous = $this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                        'x__left' => $focus__id,
                        'x__right' => $next_i['i__id'],
                    ), array(), 0);

                    //See how to adjust:
                    if($action_e__id==12611 && !count($is_previous)){

                        //Link
                        $status = $this->I_model->create_or_link(11019, '', $x__source, $next_i['i__id'], $focus__id);

                        if($status['status']){
                            //Add Source since not there:
                            $applied_success++;
                        }


                    } elseif($action_e__id==12612 && count($is_previous)){

                        //Unlink
                        $this->X_model->update($is_previous[0]['x__id'], array(
                            'x__status' => 6173,
                        ), $x__source, 13579 /* IDEA NOTES Unpublished */);

                        $applied_success++;

                    }

                }
            }
        }


        //Log mass source edit transaction:
        $this->X_model->create(array(
            'x__source' => $x__source,
            'x__type' => $action_e__id,
            'x__right' => $i__id,
            'x__metadata' => array(
                'payload' => $_POST,
                'i_total' => count($is_next),
                'i_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($is_next) . ' ideas updated',
        );

    }


    function weight($i__id)
    {

        /*
         *
         * Addup weights recursively
         *
         * */


        $total_child_weights = 0;

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $i__id,
        ), array('x__right'), 0, 0, array(), 'i__id, i__spectrum') as $next_i){
            $total_child_weights += $next_i['i__spectrum'] + $this->I_model->weight($next_i['i__id']);
        }

        //Update This Level:
        if($total_child_weights > 0){
            $this->db->query("UPDATE table__i SET i__spectrum=i__spectrum+".$total_child_weights." WHERE i__id=".$i__id.";");
        }

        //Return data:
        return $total_child_weights;

    }



    function metadata_e_insights($i)
    {

        /*
         *
         * Generates Idea Tree Insights like
         * min/max ideas, time & referenced
         * expert sources/channels.
         *
         * */

        $metadata_this = array(
            'p___6169' => 1,
            'p___6170' => 1,
            'p___6161' => $i['i__duration'],
            'p___6162' => $i['i__duration'],
            'p___13207' => array(), //Leaderboard Sources
            'p___ids' => array($i['i__id']), //Keeps Track of the IDs scanned here
        );


        //AGGREGATE IDEA SOURCES
        foreach($this->X_model->fetch(array(
            //Already for for x__up & x__down
            'x__up >' => 0,
            'x__right' => $i['i__id'],
            'x__type IN (' . join(',', $this->config->item('n___13550')).')' => null, //SOURCE IDEAS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array('x__up'), 0) as $fetched_e) {

            $e_metadata_leaderboard = $this->E_model->metadata_leaderboard($fetched_e);

            foreach($e_metadata_leaderboard['p___13207'] as $e__id) {
                if (!in_array($e__id, $metadata_this['p___13207'])) {
                    array_push($metadata_this['p___13207'], intval($e__id));
                }
            }

            //MEMBERS:
            if (!in_array($fetched_e['x__source'], $metadata_this['p___13207'])) {
                array_push($metadata_this['p___13207'], intval($fetched_e['x__source']));
            }
        }


        $metadata_local = array(
            'localp___6169'=> null,
            'localp___6170'=> null,
            'localp___6161'=> null,
            'localp___6162'=> null,
        );

        //NEXT IDEAS
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i['i__id'],
        ), array('x__right'), 0) as $is_next){

            //Members
            if (!in_array($is_next['x__source'], $metadata_this['p___13207'])) {
                array_push($metadata_this['p___13207'], intval($is_next['x__source']));
            }

            //RECURSION
            $metadata_recursion = $this->I_model->metadata_e_insights($is_next);


            //CONDITIONAL OR SELECT ONE
            if(in_array($is_next['x__type'], $this->config->item('n___12842')) || in_array($i['i__type'], $this->config->item('n___12883'))){

                //ONE

                //MIN
                if(is_null($metadata_local['localp___6169']) || $metadata_recursion['p___6169'] < $metadata_local['localp___6169']){
                    $metadata_local['localp___6169'] = $metadata_recursion['p___6169'];
                }
                if(is_null($metadata_local['localp___6161']) || $metadata_recursion['p___6161'] < $metadata_local['localp___6161']){
                    $metadata_local['localp___6161'] = $metadata_recursion['p___6161'];
                }

                //MAX
                if(is_null($metadata_local['localp___6170']) || $metadata_recursion['p___6170'] > $metadata_local['localp___6170']){
                    $metadata_local['localp___6170'] = $metadata_recursion['p___6170'];
                }
                if(is_null($metadata_local['localp___6162']) || $metadata_recursion['p___6162'] > $metadata_local['localp___6162']){
                    $metadata_local['localp___6162'] = $metadata_recursion['p___6162'];
                }

            } elseif(in_array($i['i__type'], $this->config->item('n___12884'))){

                //SELECT SOME

                //MIN
                if(is_null($metadata_local['localp___6169']) || $metadata_recursion['p___6169'] < $metadata_local['localp___6169']){
                    $metadata_local['localp___6169'] = $metadata_recursion['p___6169'];
                }
                if(is_null($metadata_local['localp___6161']) || $metadata_recursion['p___6161'] < $metadata_local['localp___6161']){
                    $metadata_local['localp___6161'] = $metadata_recursion['p___6161'];
                }

                //MAX
                $metadata_this['p___6170'] += intval($metadata_recursion['p___6170']);
                $metadata_this['p___6162'] += intval($metadata_recursion['p___6162']);

            } elseif(in_array($i['i__type'], $this->config->item('n___14862'))){

                //SELECT ANY OR NONE

                //MIN: They can select none
                $metadata_local['localp___6169'] = 0;
                $metadata_local['localp___6161'] = 0;

                //MAX
                $metadata_this['p___6170'] += intval($metadata_recursion['p___6170']);
                $metadata_this['p___6162'] += intval($metadata_recursion['p___6162']);

            } else {

                //ALL

                //MIN
                $metadata_this['p___6169'] += intval($metadata_recursion['p___6169']);
                $metadata_this['p___6161'] += intval($metadata_recursion['p___6161']);

                //MAX
                $metadata_this['p___6170'] += intval($metadata_recursion['p___6170']);
                $metadata_this['p___6162'] += intval($metadata_recursion['p___6162']);

            }


            //LEADERBOARD SOURCES
            foreach($metadata_recursion['p___13207'] as $e__id) {
                if (!in_array($e__id, $metadata_this['p___13207'])) {
                    array_push($metadata_this['p___13207'], intval($e__id));
                }
            }


            //AGGREGATE IDS
            foreach($metadata_recursion['p___ids'] as $i__id) {
                if (!in_array(intval($i__id), $metadata_this['p___ids'])) {
                    array_push($metadata_this['p___ids'], intval($i__id));
                }
            }
        }


        //ADD LOCAL MIN/MAX
        if(!is_null($metadata_local['localp___6169'])){
            $metadata_this['p___6169'] += intval($metadata_local['localp___6169']);
        }
        if(!is_null($metadata_local['localp___6170'])){
            $metadata_this['p___6170'] += intval($metadata_local['localp___6170']);
        }
        if(!is_null($metadata_local['localp___6161'])){
            $metadata_this['p___6161'] += intval($metadata_local['localp___6161']);
        }
        if(!is_null($metadata_local['localp___6162'])){
            $metadata_this['p___6162'] += intval($metadata_local['localp___6162']);
        }

        //Save to DB
        update_metadata(12273, $i['i__id'], array(
            'i___6169' => intval($metadata_this['p___6169']),
            'i___6170' => intval($metadata_this['p___6170']),
            'i___6161' => intval($metadata_this['p___6161']),
            'i___6162' => intval($metadata_this['p___6162']),
            'i___13207' => $metadata_this['p___13207'], //LEADERBOARD Sources
        ));

        //Return data:
        return $metadata_this;

    }



    function unlock_paths($i)
    {
        /*
         *
         * Finds the pathways, if any, on how to unlock $i
         *
         * */


        //Validate this locked idea:
        if(!i_unlockable($i)){
            return array();
        }

        $child_unlock_paths = array();


        //read 1: Is there an OR parent that we can simply answer and unlock?
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__right' => $i['i__id'],
            'i__type IN (' . join(',', $this->config->item('n___7712')) . ')' => null,
        ), array('x__left'), 0) as $i_or_parent){
            if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $i_or_parent['i__id'])) {
                array_push($child_unlock_paths, $i_or_parent);
            }
        }


        //read 2: Are there any locked transaction parents that the member might be able to unlock?
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12842')) . ')' => null, //IDEA LINKS ONE-WAY
            'x__right' => $i['i__id'],
        ), array('x__left'), 0) as $i_locked_parent){
            if(i_unlockable($i_locked_parent)){
                //Need to check recursively:
                foreach($this->I_model->unlock_paths($i_locked_parent) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $locked_path['i__id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }
            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $i_locked_parent['i__id'])) {
                array_push($child_unlock_paths, $i_locked_parent);
            }
        }


        //Return if we have options for step 1 OR step 2:
        if(count($child_unlock_paths) > 0){
            //Return OR parents for unlocking this idea:
            return $child_unlock_paths;
        }


        //read 3: We don't have any OR parents, let's see how we can complete all children to meet the requirements:
        $is_next = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $i['i__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));
        if(count($is_next) < 1){
            //No children, no path:
            return array();
        }

        //Go through children to see if any/all can be completed:
        foreach($is_next as $next_i){
            if(i_unlockable($next_i)){

                //Need to check recursively:
                foreach($this->I_model->unlock_paths($next_i) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $locked_path['i__id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }

            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $next_i['i__id'])) {

                //Not locked, so this can be completed:
                array_push($child_unlock_paths, $next_i);

            }
        }
        return $child_unlock_paths;

    }

}