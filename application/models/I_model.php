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


    function create($add_fields, $x__creator = 0)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($add_fields, array('i__title', 'i__type'), $x__creator)) {
            return false;
        }

        //Lets now add:
        $this->db->insert('table__i', $add_fields);

        //Fetch inserted id:
        if (!isset($add_fields['i__id'])) {
            $add_fields['i__id'] = $this->db->insert_id();
        }

        if ($add_fields['i__id'] > 0) {

            if ($x__creator > 0) {

                //Log transaction new Idea:
                $this->X_model->create(array(
                    'x__creator' => $x__creator,
                    'x__right' => $add_fields['i__id'],
                    'x__message' => $add_fields['i__title'],
                    'x__type' => 4250, //New Idea Created
                ));

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
                'x__creator' => $x__creator,
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


    function update($id, $update_columns, $external_sync = false, $x__creator = 0, $x__type = 0)
    {

        $id = intval($id);
        if (count($update_columns)==0) {
            return false;
        }

        //Fetch current Idea filed values so we can compare later on after we've updated it:
        if($x__creator > 0){
            $before_data = $this->I_model->fetch(array('i__id' => $id));
        }

        //Update:
        $this->db->where('i__id', $id);
        $this->db->update('table__i', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $x__creator > 0) {

            //Unlike source modification, we require a member source ID to log the modification transaction:
            //Log modification transaction for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key]==$value){
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

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }

                //Value has changed, log transaction:
                $this->X_model->create(array(
                    'x__creator' => $x__creator,
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
                'x__creator' => $x__creator,
                'x__message' => 'update() Failed to update',
                'x__metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function remove($i__id, $x__creator = 0, $migrate_s__id = 0){

        $x_adjusted = 0;
        if($migrate_s__id > 0){

            //Validate this migration ID:
            $is = $this->I_model->fetch(array(
                'i__id' => $migrate_s__id,
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ));

            if(count($is)){
                //Migrate Transactions:
                foreach($this->X_model->fetch(array( //Idea Transactions
                    'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'x__type !=' => 13579, //Idea Transaction Unpublished
                    '(x__right = '.$i__id.' OR x__left = '.$i__id.')' => null,
                ), array(), 0) as $x){

                    //Make sure not duplicate, if so, delete:
                    $update_filter = array();
                    $filters = array(
                        'x__id !=' => $x['x__id'],
                        'x__access' => $x['x__access'],
                        'x__type' => $x['x__type'],
                        'x__reference' => $x['x__reference'],
                        //'LOWER(x__message)' => strtolower($x['x__message']),

                        'x__creator' => $x['x__creator'],
                        'x__up' => $x['x__up'],
                        'x__down' => $x['x__down'],
                    );
                    if($x['x__right']==$i__id){
                        $filters['x__right'] = $migrate_s__id;
                        $update_filter['x__right'] = $migrate_s__id;
                    }
                    if($x['x__left']==$i__id){
                        $filters['x__left'] = $migrate_s__id;
                        $update_filter['x__left'] = $migrate_s__id;
                    }
                    if(0 && count($this->X_model->fetch($filters))){

                        //There is a duplicate of this, no point to migrate! Just Remove:
                        $this->X_model->update($x['x__id'], array(
                            'x__access' => 6173,
                        ), $x__creator, 26785 /* Idea Link Migrated */);

                    } else {

                        //Always merge for now
                        $x_adjusted += $this->X_model->update($x['x__id'], $update_filter, $x__creator, 26785 /* Idea Link Migrated */);

                    }

                }
            }

        } else {

            //REMOVE TRANSACTIONS
            foreach($this->X_model->fetch(array( //Idea Transactions
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type !=' => 13579, //Idea Transaction Unpublished
                '(x__right = '.$i__id.' OR x__left = '.$i__id.')' => null,
            ), array(), 0) as $x){
                //Delete this transaction:
                $x_adjusted += $this->X_model->update($x['x__id'], array(
                    'x__access' => 6173, //Transaction Deleted
                ), $x__creator, 13579 /* Idea Transaction Unpublished */);
            }

        }



        //Return transactions deleted:
        return $x_adjusted;
    }






    function duplicate($i, $copy_to__id, $x__creator)
    {

        $i_new = $this->I_model->create(array(
            'i__title' => $i['i__title'],
            'i__type' => $i['i__type'],
        ), $x__creator);

        //Copy related transactions:
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___27240')) . ')' => null, //COPY Transactions
            '(x__right='.$i['i__id'].' OR x__left='.$i['i__id'].')' => null,
        ), array(), 0) as $x){

            //Duplicate transaction, with new idea
            if(!count($this->X_model->fetch(array(
                'x__type' => $x['x__type'],
                'x__metadata' => $x['x__metadata'],
                'x__message' => $x['x__message'],
                'x__up' => $x['x__up'],
                'x__down' => $x['x__down'],
                'x__left' => ( $i['i__id']==$x['x__left'] ? $i_new['i__id'] : $x['x__left'] ),
                'x__right' => ( $i['i__id']==$x['x__right'] ? $i_new['i__id'] : $x['x__right'] ),
            )))){
                $this->X_model->create(array(
                    //Copy:
                    'x__type' => $x['x__type'],
                    'x__access' => $x['x__access'],
                    'x__weight' => $x['x__weight'],
                    'x__message' => $x['x__message'],
                    'x__metadata' => $x['x__metadata'],
                    'x__up' => $x['x__up'],
                    'x__down' => $x['x__down'],
                    'x__reference' => $x['x__reference'],
                    //Change:
                    'x__creator' => $x__creator,
                    'x__left' => ( $i['i__id']==$x['x__left'] ? $i_new['i__id'] : $x['x__left'] ),
                    'x__right' => ( $i['i__id']==$x['x__right'] ? $i_new['i__id'] : $x['x__right'] ),
                ));
            }

        }

        return $this->I_model->create_or_link(12273, 11019, '', $x__creator, $i_new['i__id'], $copy_to__id);

    }

    function create_or_link($focus_card, $x__type, $i__title, $x__creator, $focus_id, $link_i__id = 0)
    {

        /*
         *
         * The main idea creation function that would create
         * appropriate transactions and return the idea view.
         *
         * Either creates an IDEA transaction between $focus_id & $link_i__id
         * (IF $link_i__id>0) OR will create a new idea with outcome $i__title
         * and transaction it to $focus_id (In this case $link_i__id will be 0)
         *
         * p.s. Inputs have previously been validated via ideas/i__add() function
         *
         * */

        //Valid Idea Addition?
        if(!in_array($focus_card, $this->config->item('n___12761')) || !in_array($x__type, $this->config->item('n___11020')) || $focus_id < 1){
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'create_or_link(): Invalid Data',
                'x__metadata' => array(
                    '$focus_card' => $focus_card,
                    '$x__type' => $x__type,
                    '$i__title' => $i__title,
                    '$x__creator' => $x__creator,
                    '$focus_id' => $focus_id,
                    '$link_i__id' => $link_i__id,
                ),
            ));
            return array(
                'status' => 0,
                'message' => 'Invalid Data',
            );
        }

        $is_upwards = in_array($x__type, $this->config->item('n___14686'));
        $focus_is_idea = $focus_card==12273;
        $adding_an_idea = in_array($x__type, $this->config->item('n___11020'));
        //Validate Original idea
        if($focus_is_idea){

            if ($focus_id > 0 && $link_i__id==$focus_id) {
                //Make sure none of the followings are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add idea to itself.',
                );
            }
            $focus_i = $this->I_model->fetch(array(
                'i__id' => intval($focus_id),
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ));
            if (count($focus_i) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Focus Idea',
                );
            }

        } else {

            //Were at a Source trying to add an Idea:
            $focus_e = $this->E_model->fetch(array(
                'e__id' => intval($focus_id),
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
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

            //Fetch more details on the follower idea we're about to transaction:
            $link_i = $this->I_model->fetch(array(
                'i__id' => $link_i__id,
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ));
            if (count($link_i) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Link Idea',
                );
            }

            //Determine which is followings Idea, and which is follower
            if($focus_is_idea){

                //Must be adding PREVIOUS or NEXT

                //Duplicate Check:
                if (count($this->X_model->fetch(array(
                        'x__left' => ( $is_upwards ? $link_i[0]['i__id'] : $focus_i[0]['i__id'] ),
                        'x__right' => ( $is_upwards ? $focus_i[0]['i__id'] : $link_i[0]['i__id'] ),
                        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
                        'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    ))) > 0) {
                    return array(
                        'status' => 0,
                        'message' => 'Idea is already linked here.',
                    );
                }

                //Tree Check if Next
                if($x__type==12273 && count($this->X_model->find_previous(0, $link_i[0]['i__id'], $focus_i[0]['i__id']))){
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

            } else {

                //Must be adding Idea to Source as References

                //Duplicate Check:
                if(count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__up' => $focus_e[0]['e__id'],
                    'x__right' => $link_i[0]['i__id'],
                )))){
                    return array(
                        'status' => 0,
                        'message' => 'Idea already referenced to this source',
                    );
                }

            }

            //All good so far, continue with adding:
            $i_new = $link_i[0];

        } else {

            //We are NOT adding an existing Idea, but instead, we're creating a new Idea

            //Validate Idea Outcome:
            $i__validate_title = i__validate_title($i__title);
            if(!$i__validate_title['status']){
                //We had an error, return it:
                return $i__validate_title;
            }

            //Create new Idea:
            $i_new = $this->I_model->create(array(
                'i__title' => $i__validate_title['i_clean_title'],
                'i__type' => 6677, //New Default Ideas
            ), $x__creator);

        }


        //Additional sources to be added? Start with creator...
        $sources_appended = array($x__creator);
        $this->X_model->create(array(
            'x__type' => 4983, //IDEA SOURCES
            'x__creator' => $x__creator,
            'x__up' => $x__creator,
            'x__right' => $i_new['i__id'],
        ));
        //Also append all pinned followers:
        foreach($this->X_model->fetch(array(
            'x__down' => $x__creator,
            'x__type' => 41011, //PINNED FOLLOWER
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0) as $x_pinned) {
            if(!in_array($x_pinned['x__up'], $sources_appended)){
                $this->X_model->create(array(
                    'x__type' => 4983, //IDEA SOURCES
                    'x__creator' => $x__creator,
                    'x__up' => $x_pinned['x__up'],
                    'x__right' => $i_new['i__id'],
                ));
                array_push($sources_appended, $x_pinned['x__up']);
            }
        }

        //Create Idea Transaction:
        $new_i_html = null;

        if($focus_is_idea){

            //Adding PREVIOUS or NEXT Idea from Idea
            $relation = $this->X_model->create(array(
                'x__creator' => $x__creator,
                'x__type' => 4228, //Idea Transaction Regular read
                ( $is_upwards ? 'x__right' : 'x__left' ) => $focus_id,
                ( $is_upwards ? 'x__left' : 'x__right' ) => $i_new['i__id'],
                'x__weight' => 0,
            ), true);

            //Fetch and return full data to be properly shown on the UI
            $new_i = $this->X_model->fetch(array(
                ( $is_upwards ? 'x__right' : 'x__left' ) => $focus_id,
                ( $is_upwards ? 'x__left' : 'x__right' ) => $i_new['i__id'],
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ), array(($is_upwards ? 'x__left' : 'x__right')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation

            $new_i_html = view_card_i($x__type, 0, ( $is_upwards ? null : $focus_i[0] ), $new_i[0]);

        } else {

            if(!in_array($focus_e[0]['e__id'], $sources_appended)){
                $this->X_model->create(array(
                    'x__type' => 4983, //IDEA SOURCES
                    'x__creator' => $x__creator,
                    'x__up' => $focus_e[0]['e__id'],
                    'x__right' => $i_new['i__id'],
                ));
                array_push($sources_appended, $focus_e[0]['e__id']);
            }

            //Fetch Complete References:
            $new_i = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 4983, //IDEA SOURCES
                'x__up' => $focus_e[0]['e__id'],
                'x__right' => $i_new['i__id'],
            ), array('x__right'));

            $new_i_html = view_card_i($x__type, 0, null, $new_i[0], $focus_e[0]);

        }

        //Return result:
        return array(
            'status' => 1,
            'new_i__id' => $i_new['i__id'],
            'new_i_html' => $new_i_html,
        );

    }



    function recursive_down_ids($i, $scope, $current_level = 0, $loop_breaker_ids = array()){

        /*
         *
         * $fetch can be either:
         * - ALL any and all nodes in the tree
         * - AND only AND nodes that are similar for all
         * - OR only what is different for all
         * */

        if(!($scope=='ALL' || $scope=='AND' || $scope=='OR')){
            return false;
        }

        if(count($loop_breaker_ids)>0 && in_array($i['i__id'], $loop_breaker_ids)){
            return array();
        }

        $is_or_i = in_array($i['i__type'], $this->config->item('n___7712'));
        if($scope=='AND' && $is_or_i){
            //OR IDEA:
            return array();
        }

        $current_level++;
        $recursive_i_ids = array();
        array_push($loop_breaker_ids, intval($i['i__id']));

        foreach($this->X_model->fetch(array(
            'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //Must See Idea Links
            'x__left' => $i['i__id'],
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $next_i){

            if(!in_array(intval($next_i['i__id']), $recursive_i_ids)){
                if(!($scope=='OR' && !$is_or_i)){
                    //We add it at all times unless scope is OR and node is not OR
                    array_push($recursive_i_ids, intval($next_i['i__id']));
                }
            }

            //Add to current array if we found anything:
            foreach($this->I_model->recursive_down_ids($next_i, $scope, $current_level, $loop_breaker_ids) as $recursive_i_id){
                if(!in_array($recursive_i_id, $recursive_i_ids)){
                    array_push($recursive_i_ids, $recursive_i_id);
                }
            }
        }

        return array_unique($recursive_i_ids);

    }

    function recursive_clone($i__id, $do_recursive, $x__creator, $previous_i = null, $clone_title = null) {


        //Create Clone -or- Link & move-on?
        //Validate Idea:
        $this_i = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));
        if (count($this_i) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea ID',
                'new_i__id' => 0,
            );
        }


        $i_new = $this->I_model->create(array(
            'i__title' => ( $clone_title ? $clone_title : $this_i[0]['i__title']." Copy" ),
            'i__type' => $this_i[0]['i__type'],
        ), $x__creator);


        //Clone Messages:
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $i__id,
        ), array(), 0, 0, array('x__weight' => 'ASC')) as $x) {
            $this->X_model->create(array(
                'x__creator' => $x__creator,
                'x__type' => $x['x__type'],
                'x__right' => $i_new['i__id'],
                'x__left' => $x['x__left'],
                'x__up' => $x['x__up'],
                'x__down' => $x['x__down'],
                'x__message' => $x['x__message'],
                'x__weight' => $x['x__weight'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__access' => $x['x__access'],
            ));
        }

        //Always Link Sources:
        $filters = array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //Idea/Source Links Active
            'x__right' => $i__id,
        );

        foreach($this->X_model->fetch($filters, array(), 0) as $x){
            $this->X_model->create(array(
                'x__creator' => $x__creator,
                'x__type' => $x['x__type'],
                'x__right' => $i_new['i__id'],
                'x__up' => $x['x__up'],
                'x__down' => $x['x__down'],
                'x__left' => $x['x__left'],
                'x__message' => $x['x__message'],
                'x__weight' => $x['x__weight'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__access' => $x['x__access'],
            ));
        }


        //Always Link Followings:
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
            'x__right' => $i__id,
        ), array(), 0) as $x){
            $this->X_model->create(array(
                'x__creator' => $x__creator,
                'x__type' => $x['x__type'],
                'x__right' => $i_new['i__id'],
                'x__left' => $x['x__left'],
                'x__message' => $x['x__message'],
                'x__weight' => $x['x__weight'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__access' => $x['x__access'],
            ));
        }


        //Fetch followers:
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
            'x__left' => $i__id,
        ), array(), 0) as $x){
            if($do_recursive){
                //Clone Followers Recursively:
                $this->I_model->recursive_clone($x['x__right'], $do_recursive, $x__creator, $this_i[0]);
            } else {
                //Link Followers:
                $this->X_model->create(array(
                    'x__creator' => $x__creator,
                    'x__type' => $x['x__type'],
                    'x__left' => $i_new['i__id'],
                    'x__right' => $x['x__right'],
                    'x__message' => $x['x__message'],
                    'x__weight' => $x['x__weight'],
                    'x__reference' => $x['x__reference'],
                    'x__metadata' => $x['x__metadata'],
                    'x__access' => $x['x__access'],
                ));
            }
        }

        return array(
            'status' => 1,
            'new_i__id' => $i_new['i__id'],
        );

    }




    function recursive_up_ids($i__id, $first_discovery = 0, $current_level = 0, $loop_breaker_ids = array()){

        /*
         *
         * Returns integer if $first_discovery>0 or array otherwise
         *
         * */

        if(count($loop_breaker_ids)>0 && in_array($i__id, $loop_breaker_ids)){
            return ( $first_discovery>0 ? 0 : array() );
        }
        array_push($loop_breaker_ids, intval($i__id));

        $recursive_i_ids = array();
        $current_level++;

        foreach($this->X_model->fetch(array(
            'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
            'x__right' => $i__id,
        ), array('x__left')) as $prev_i){

            if($first_discovery > 0){
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__creator' => $first_discovery,
                    'x__left' => $prev_i['i__id'],
                    'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
                ), array('x__right')) as $x){
                    return $x['x__right'];
                }
            }

            array_push($recursive_i_ids, intval($prev_i['i__id']));

            $recursive_is = $this->I_model->recursive_up_ids($prev_i['i__id'], $first_discovery, $current_level, $loop_breaker_ids);

            //Add to current array if we found anything:
            if(!$first_discovery && count($recursive_is) > 0){
                $recursive_i_ids = array_merge($recursive_i_ids, $recursive_is);
            }
        }

        if($first_discovery) {
            return 0;
        } elseif($current_level==1){
            return array_unique($recursive_i_ids);
        } else {
            return $recursive_i_ids;
        }

    }




    function mass_update($i__id, $action_e__id, $action_command1, $action_command2, $x__creator)
    {

        //Alert: Has a twin function called e_mass_update()

        boost_power();

        if(!in_array($action_e__id, $this->config->item('n___12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif(in_array($action_e__id , array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087)) && !is_valid_e_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Title',
            );

        } elseif(in_array($action_e__id , array(12611,12612,27240,28801)) && !is_valid_i_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Idea. Format must be: #123 Idea Title',
            );

        }



        //Basic input validation done, let's continue...


        //Fetch all followers:
        $applied_success = 0; //To be populated...

        $is_next = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $i__id,
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC'));


        //Process request:
        foreach($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if(in_array($action_e__id , array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087))){

                //Check if it has this item:
                $e__up_id = intval(one_two_explode('@',' ',$action_command1));
                $i_has_e = $this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $next_i['i__id'],
                    'x__up' => $e__up_id,
                ));

                if(in_array($action_e__id , array(12591,27080,27985,27082,27084,27086)) && !count($i_has_e)){

                    $source_mapper = array(
                        12591 => 4983,  //Sources
                        27080 => 13865, //Following Includes Any
                        27985 => 27984, //Following Includes All
                        27082 => 26600, //Following Excludes All
                        27084 => 7545,  //Following Add
                        27086 => 26599, //Following Remove
                    );

                    //Missing & Must be Added:
                    $this->X_model->create(array(
                        'x__creator' => $x__creator,
                        'x__up' => $e__up_id,
                        'x__type' => $source_mapper[$action_e__id],
                        'x__right' => $next_i['i__id'],
                        'x__message' => trim($action_command2),
                    ), true);

                    $applied_success++;

                } elseif(in_array($action_e__id , array(12592,27081,27986,27083,27085,27087)) && count($i_has_e)){

                    //Has and must be deleted:
                    $this->X_model->update($i_has_e[0]['x__id'], array(
                        'x__access' => 6173,
                    ), $x__creator, 10673 /* IDEA NOTES Unpublished */);

                    $applied_success++;

                }

            } elseif(in_array($action_e__id , array(12611,12612,27240,28801))){

                //Check if it hs this item:
                $focus_id = intval(one_two_explode('#',' ',$action_command1));

                if($action_e__id==27240){

                    //Copy
                    $status = $this->I_model->duplicate($next_i, $focus_id, $x__creator);

                    if($status['status']){
                        //Add Source since not there:
                        $applied_success++;
                    }

                } else {

                    $is_previous = $this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
                        'x__left' => $focus_id,
                        'x__right' => $next_i['i__id'],
                    ), array(), 0);


                    //See how to adjust:
                    if(in_array($action_e__id, array(12611, 28801)) && !count($is_previous)){

                        //Link
                        $status = $this->I_model->create_or_link(12273, 11019, '', $x__creator, $next_i['i__id'], $focus_id);

                        if($status['status']){

                            if($action_e__id==28801){
                                //Also remove old link:
                                $this->X_model->update($next_i['x__id'], array(
                                    'x__access' => 6173, //Transaction Deleted
                                ), $x__creator, 10673 /* Member Transaction Unpublished  */);
                            }

                            //Add Source since not there:
                            $applied_success++;
                        }
                    }


                    if($action_e__id==12612 && count($is_previous)){
                        //Unlink
                        $this->X_model->update($is_previous[0]['x__id'], array(
                            'x__access' => 6173,
                        ), $x__creator, 13579 /* IDEA NOTES Unpublished */);

                        $applied_success++;
                    }


                }
            }
        }


        //Log mass source edit transaction:
        $this->X_model->create(array(
            'x__creator' => $x__creator,
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



}