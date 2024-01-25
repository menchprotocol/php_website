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


    function create($add_fields, $x__creator = 14068)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($add_fields, array('i__message'), $x__creator)) {
            return false;
        }

        if(!isset($add_fields['i__type']) || !in_array($add_fields['i__type'], $this->config->item('n___4737'))){
            //Statement is the default idea type:
            $add_fields['i__type'] = 6677;
        }

        //Auto generate a Hashtag if needed:
        if(!isset($add_fields['i__hashtag'])){
            $add_fields['i__hashtag'] = random_string(8);
        }

        //Lets now add:
        $this->db->insert('table__i', $add_fields);

        //Fetch inserted id:
        if (!isset($add_fields['i__id'])) {
            $add_fields['i__id'] = $this->db->insert_id();
        }

        if ($add_fields['i__id'] > 0) {

            //Log transaction new Idea:
            $this->X_model->create(array(
                'x__creator' => $x__creator,
                'x__up' => $x__creator,
                'x__right' => $add_fields['i__id'],
                'x__type' => 4250, //New Idea Created
            ));

            //Log transaction new Idea hashtag:
            $this->X_model->create(array(
                'x__creator' => $x__creator,
                'x__right' => $add_fields['i__id'],
                'x__message' => $add_fields['i__hashtag'],
                'x__type' => 42168, //Idea Generated Hashtag
            ));

            //Sync messages:
            $view_sync_links = view_sync_links($add_fields['i__message'], true, $add_fields['i__id']);

            //Fetch to return the complete source data:
            $is = $this->I_model->fetch(array(
                'i__id' => $add_fields['i__id'],
            ));

            //Update Search Index:
            flag_for_search_indexing(12273, $add_fields['i__id']);

            return $is[0];

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

                } elseif($key=='i__hashtag') {

                    $x__type = 41982; //Idea updated Handle
                    $x__message = update_description($before_data[0][$key], $value);

                } elseif($key=='i__message') {

                    $x__type = 10644; //Idea updated Outcome
                    $x__message = update_description($before_data[0][$key], $value);

                } elseif($key=='i__privacy'){

                    $x__type = 41997; //Idea Access Updated
                    $e___31004 = $this->config->item('e___31004'); //Idea Access
                    $x__message = view_db_field($key) . ' updated from [' . $e___31004[$before_data[0][$key]]['m__title'] . '] to [' . $e___31004[$value]['m__title'] . ']';
                    $x__up = $value;
                    $x__down = $before_data[0][$key];

                } elseif($key=='i__type'){

                    $x__type = 10648; //Idea updated Status
                    $e___4737 = $this->config->item('e___4737'); //Idea Types
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
                flag_for_search_indexing(12273, $id);
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
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ));

            if(count($is)){
                //Migrate Transactions:
                foreach($this->X_model->fetch(array( //Idea Transactions
                    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'x__type !=' => 13579, //Idea Transaction Unpublished
                    '(x__right = '.$i__id.' OR x__left = '.$i__id.')' => null,
                ), array(), 0) as $x){

                    //Make sure not duplicate, if so, delete:
                    $update_filter = array();
                    $filters = array(
                        'x__id !=' => $x['x__id'],
                        'x__privacy' => $x['x__privacy'],
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
                            'x__privacy' => 6173,
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
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type !=' => 13579, //Idea Transaction Unpublished
                '(x__right = '.$i__id.' OR x__left = '.$i__id.')' => null,
            ), array(), 0) as $x){
                //Delete this transaction:
                $x_adjusted += $this->X_model->update($x['x__id'], array(
                    'x__privacy' => 6173, //Transaction Deleted
                ), $x__creator, 13579 /* Idea Transaction Unpublished */);
            }

        }



        //Return transactions deleted:
        return $x_adjusted;
    }






    function duplicate($i, $copy_to__id, $x__creator)
    {

        $i_new = $this->I_model->create(array(
            'i__message' => $i['i__message'],
            'i__type' => $i['i__type'],
        ), $x__creator);

        //Copy related transactions:
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
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
                    'x__privacy' => $x['x__privacy'],
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

    function create_or_link($focus_card, $x__type, $i__message, $x__creator, $focus_id, $link_i__id = 0)
    {

        /*
         *
         * The main idea creation function that would create
         * appropriate transactions and return the idea view.
         *
         * Either creates an IDEA transaction between $focus_id & $link_i__id
         * (IF $link_i__id>0) OR will create a new idea with outcome $i__message
         * and transaction it to $focus_id (In this case $link_i__id will be 0)
         *
         * p.s. Inputs have previously been validated via ideas/i__add() function
         *
         * */

        //Valid Idea Addition?
        if(!in_array($focus_card, $this->config->item('n___12761')) || (!in_array($x__type, $this->config->item('n___11020')) && !in_array($x__type, $this->config->item('n___42261'))) || $focus_id < 1){
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'create_or_link(): Invalid Data',
                'x__metadata' => array(
                    '$focus_card' => $focus_card,
                    '$x__type' => $x__type,
                    '$i__message' => $i__message,
                    '$x__creator' => $x__creator,
                    '$focus_id' => $focus_id,
                    '$link_i__id' => $link_i__id,
                ),
            ));
            return array(
                'status' => 0,
                'message' => 'Invalid Data for @'.$focus_card.' & @'.$x__type,
            );
        }

        $is_upwards = in_array($x__type, $this->config->item('n___14686'));
        $focus_is_i = $focus_card==12273;
        $focus_is_e = $focus_card==12274;
        $adding_an_i = ($focus_is_i && in_array($x__type, $this->config->item('n___11020'))) || ($focus_is_e && in_array($x__type, $this->config->item('n___42261')));
        //Validate Original idea
        if($focus_is_i){

            if ($focus_id > 0 && $link_i__id==$focus_id) {
                //Make sure none of the followings are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add idea to itself.',
                );
            }
            $focus_i = $this->I_model->fetch(array(
                'i__id' => intval($focus_id),
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
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
                'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
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
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ));
            if (count($link_i) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Link Idea',
                );
            }

            //Determine which is followings Idea, and which is follower
            if($focus_is_i){

                //Must be adding idea to idea as PREVIOUS or NEXT

                //Duplicate Check:
                if (count($this->X_model->fetch(array(
                        'x__left' => ( $is_upwards ? $link_i[0]['i__id'] : $focus_i[0]['i__id'] ),
                        'x__right' => ( $is_upwards ? $focus_i[0]['i__id'] : $link_i[0]['i__id'] ),
                        'x__type IN (' . join(',', $this->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                        'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    ))) > 0) {
                    return array(
                        'status' => 0,
                        'message' => 'Idea is already linked here.',
                    );
                }

                //Tree Check if Next
                if($x__type==12273 && count($this->X_model->find_previous(0, $link_i[0]['i__hashtag'], $focus_i[0]['i__id']))){
                    return array(
                        'status' => 0,
                        'message' => 'Idea already added as previous so it cannot be added as next',
                    );
                } elseif($x__type==11019 && count($this->X_model->find_previous(0, $focus_i[0]['i__hashtag'], $link_i[0]['i__id']))){
                    return array(
                        'status' => 0,
                        'message' => 'Idea already added as next so it cannot be added as previous',
                    );
                }

            } else {

                //Must be adding Idea to Source as References

                //Duplicate Check:
                if(count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
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
            $validate_i__message = validate_i__message($i__message);
            if(!$validate_i__message['status']){
                //We had an error, return it:
                return $validate_i__message;
            }

            //Create new Idea:
            $i_new = $this->I_model->create(array(
                'i__message' => $i__message,
                'i__type' => 6677, //New Default Ideas
            ), $x__creator);

        }


        //Additional sources to be added? Start with creator
        $e_appended = array($x__creator);

        //Add if not added as the follower:
        if(!count($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
            'x__up' => $x__creator,
            'x__right' => $i_new['i__id'],
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        )))){
            $this->X_model->create(array(
                'x__type' => 4983, //Co-Author
                'x__creator' => $x__creator,
                'x__up' => $x__creator,
                'x__right' => $i_new['i__id'],
            ));
        }


        //Also append all pinned followers:
        foreach($this->X_model->fetch(array(
            'x__down' => $x__creator,
            'x__type' => 41011, //PINNED FOLLOWER
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0) as $x_pinned) {
            if(!in_array($x_pinned['x__up'], $e_appended) && !count($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
                    'x__up' => $x_pinned['x__up'],
                    'x__right' => $i_new['i__id'],
                    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                )))){
                $this->X_model->create(array(
                    'x__type' => 4983, //Co-Author
                    'x__creator' => $x__creator,
                    'x__up' => $x_pinned['x__up'],
                    'x__right' => $i_new['i__id'],
                ));
                array_push($e_appended, $x_pinned['x__up']);
            }
        }

        //Create Idea Transaction:
        $new_i_html = null;

        if($focus_is_i){

            //Adding PREVIOUS or NEXT Idea from Idea
            $relation = $this->X_model->create(array(
                'x__creator' => $x__creator,
                'x__type' => ( !$is_upwards && count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__left' => $focus_id,
                ), array(), 1)) ? 33344 : 4228 ), //Drafting vs Sequenced idea
                ( $is_upwards ? 'x__right' : 'x__left' ) => $focus_id,
                ( $is_upwards ? 'x__left' : 'x__right' ) => $i_new['i__id'],
                'x__weight' => 0,
            ), true);

            //Fetch and return full data to be properly shown on the UI
            $new_i = $this->X_model->fetch(array(
                ( $is_upwards ? 'x__right' : 'x__left' ) => $focus_id,
                ( $is_upwards ? 'x__left' : 'x__right' ) => $i_new['i__id'],
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ), array(($is_upwards ? 'x__left' : 'x__right')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation

            $new_i_html = view_card_i($x__type, 0, ( $is_upwards ? null : $focus_i[0] ), $new_i[0]);

        } else {

            if(!in_array($focus_e[0]['e__id'], $e_appended) && !count($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
                    'x__up' => $focus_e[0]['e__id'],
                    'x__right' => $i_new['i__id'],
                    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                )))){
                $this->X_model->create(array(
                    'x__type' => 4983, //Co-Author
                    'x__creator' => $x__creator,
                    'x__up' => $focus_e[0]['e__id'],
                    'x__right' => $i_new['i__id'],
                ));
                array_push($e_appended, $focus_e[0]['e__id']);
            }

            //Fetch Complete References:
            $new_i = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
                'x__up' => $focus_e[0]['e__id'],
                'x__right' => $i_new['i__id'],
            ), array('x__right'));

            $new_i_html = view_card_i($x__type, 0, null, $new_i[0], $focus_e[0]);

        }

        //Return result:
        return array(
            'status' => 1,
            'new_i__hashtag' => $i_new['i__hashtag'],
            'new_i__id' => $i_new['i__id'],
            'new_i_html' => $new_i_html,
        );

    }



    function recursive_down_ids($i, $scope, $loop_breaker_ids = array()){

        /*
         *
         * $fetch can be either:
         * - ALL includes both AND and OR ideas
         * - AND ideas only
         * - OR ideas only
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

        $recursive_i_ids = array();
        array_push($loop_breaker_ids, intval($i['i__id']));

        foreach($this->X_model->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'x__left' => $i['i__id'],
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $next_i){

            if(!in_array(intval($next_i['i__id']), $recursive_i_ids)){
                if(!($scope=='OR' && !$is_or_i)){
                    //We add it at all times unless scope is OR and node is not OR
                    array_push($recursive_i_ids, intval($next_i['i__id']));
                }
            }

            //Add to current array if we found anything:
            $recursive_down_ids = $this->I_model->recursive_down_ids($next_i, $scope, $loop_breaker_ids);
            if(isset($recursive_down_ids['recursive_i_ids'])){
                foreach($recursive_down_ids['recursive_i_ids'] as $recursive_i_id){
                    if(!in_array($recursive_i_id, $recursive_i_ids)){
                        array_push($recursive_i_ids, $recursive_i_id);
                    }
                }
            }


        }

        return array(
            'recursive_i_ids' => array_unique($recursive_i_ids),
        );

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
                'new_i__hashtag' => '',
            );
        }

        $i_new = $this->I_model->create(array(
            'i__message' => ( $clone_title ? $clone_title : "Copy Of ".$this_i[0]['i__message'] ),
            'i__type' => $this_i[0]['i__type'],
        ), $x__creator);

        //Always Link Sources:
        $filters = array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___41302')) . ')' => null, //Clone Idea Source Links
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
                'x__privacy' => $x['x__privacy'],
            ));
        }


        //Always Link Followings:
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___41301')) . ')' => null, //Duplicate Links
            'x__right' => $i__id,
        ), array('x__left'), 0) as $x){
            $this->X_model->create(array(
                'x__creator' => $x__creator,
                'x__type' => $x['x__type'],
                'x__right' => $i_new['i__id'],
                'x__left' => $x['i__id'],
                'x__message' => $x['x__message'],
                'x__weight' => $x['x__weight'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__privacy' => $x['x__privacy'],
            ));
        }


        //Fetch followers:
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___41301')) . ')' => null, //Duplicate Links
            'x__left' => $i__id,
        ), array('x__right'), 0) as $x){

            if($do_recursive && !count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $i__id,
                    'x__up' => 42208, //No-Clone Idea
                )))){
                //Clone Followers Recursively:
                $this->I_model->recursive_clone($x['i__id'], $do_recursive, $x__creator, $this_i[0]);
            } else {
                //Link Followers:
                $this->X_model->create(array(
                    'x__creator' => $x__creator,
                    'x__type' => $x['x__type'],
                    'x__left' => $i_new['i__id'],
                    'x__right' => $x['i__id'],
                    'x__message' => $x['x__message'],
                    'x__weight' => $x['x__weight'],
                    'x__reference' => $x['x__reference'],
                    'x__metadata' => $x['x__metadata'],
                    'x__privacy' => $x['x__privacy'],
                ));
            }
        }

        return array(
            'status' => 1,
            'new_i__id' => $i_new['i__id'],
            'new_i__hashtag' => $i_new['i__hashtag'],
        );

    }




    function recursive_starting_points($i__id, $current_level = 0, $loop_breaker_ids = array()){

        /*
         *
         * Returns integer if $first_discovery>0 or array otherwise
         *
         * */

        if(count($loop_breaker_ids)>0 && in_array($i__id, $loop_breaker_ids)){
            return array();
        }
        array_push($loop_breaker_ids, intval($i__id));

        $recursive_i_ids = array();
        $current_level++;

        foreach($this->X_model->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //Active Sequence Up
            'x__right' => $i__id,
        ), array('x__left')) as $prev_i){


            $is_start = count($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $prev_i['i__id'],
                'x__up' => 4235,
            )));

            if($is_start){
                array_push($recursive_i_ids, intval($prev_i['i__id']));
                continue;
            }


            $recursive_is = $this->I_model->recursive_starting_points($prev_i['i__id'], $current_level, $loop_breaker_ids);

            //Add to current array if we found anything:
            if(count($recursive_is) > 0){
                $recursive_i_ids = array_merge($recursive_i_ids, $recursive_is);
            }
        }

        if($current_level==1){
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

        } elseif(in_array($action_e__id , array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087)) && !view_valid_handle_e($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @SourceHandle',
            );

        } elseif(in_array($action_e__id , array(12611,12612,27240,28801)) && !view_valid_handle_i($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Idea. Format must be: #IdeaHashtag',
            );

        }



        //Basic input validation done, let's continue


        //Fetch all followers:
        $applied_success = 0; //To be populated

        $is_next = $this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'x__left' => $i__id,
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC'));


        //Process request:
        foreach($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if(in_array($action_e__id , array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087)) && view_valid_handle_e($action_command1)){

                //Check if it has this item:
                foreach($this->E_model->fetch(array(
                    'LOWER(e__handle)' => strtolower(view_valid_handle_e($action_command1)),
                )) as $e){

                    $i_has_e = $this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'x__right' => $next_i['i__id'],
                        'x__up' => $e['e__id'],
                    ));

                    if(in_array($action_e__id , array(12591,27080,27985,27082,27084,27086)) && !count($i_has_e)){

                        $e_mapper = array(
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
                            'x__up' => $e['e__id'],
                            'x__type' => $e_mapper[$action_e__id],
                            'x__right' => $next_i['i__id'],
                            'x__message' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif(in_array($action_e__id , array(12592,27081,27986,27083,27085,27087)) && count($i_has_e)){

                        //Has and must be deleted:
                        $this->X_model->update($i_has_e[0]['x__id'], array(
                            'x__privacy' => 6173,
                        ), $x__creator, 10673 /* IDEA NOTES Unpublished */);

                        $applied_success++;

                    }
                }

            } elseif(in_array($action_e__id , array(12611,12612,27240,28801)) && view_valid_handle_i($action_command1)){

                foreach($this->I_model->fetch(array(
                    'LOWER(i__hashtag)' => strtolower(view_valid_handle_i($action_command1)),
                )) as $i){

                    if($action_e__id==27240){

                        //Copy
                        $status = $this->I_model->duplicate($next_i, $i['i__id'], $x__creator);

                        if($status['status']){
                            //Add Source since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->X_model->fetch(array(
                            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            'x__type IN (' . join(',', $this->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                            'x__left' => $i['i__id'],
                            'x__right' => $next_i['i__id'],
                        ), array(), 0);


                        //See how to adjust:
                        if(in_array($action_e__id, array(12611, 28801)) && !count($is_previous)){

                            //Link
                            $status = $this->I_model->create_or_link(12273, 11019, '', $x__creator, $next_i['i__id'], $i['i__id']);

                            if($status['status']){

                                if($action_e__id==28801){
                                    //Also remove old link:
                                    $this->X_model->update($next_i['x__id'], array(
                                        'x__privacy' => 6173, //Transaction Deleted
                                    ), $x__creator, 10673 /* Member Transaction Unpublished  */);
                                }

                                //Add Source since not there:
                                $applied_success++;
                            }
                        }


                        if($action_e__id==12612 && count($is_previous)){
                            //Unlink
                            $this->X_model->update($is_previous[0]['x__id'], array(
                                'x__privacy' => 6173,
                            ), $x__creator, 13579 /* IDEA NOTES Unpublished */);

                            $applied_success++;
                        }


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