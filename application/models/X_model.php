<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class X_model extends CI_Model
{

    /*
     *
     * Member related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function create($add_fields, $external_sync = false)
    {

        //Set some defaults:
        if (!isset($add_fields['x__player']) || intval($add_fields['x__player']) < 1) {
            $add_fields['x__player'] = 14068; //GUEST MEMBER
        }

        //Only require transaction type:
        if (detect_missing_columns($add_fields, array('x__type'), $add_fields['x__player'])) {
            return false;
        }

        if(!in_array($add_fields['x__type'], $this->config->item('n___4593'))){
            $this->X_model->create(array(
                'x__message' => 'x->create() failed to create because of invalid transaction type @'.$add_fields['x__type'],
                'x__type' => 4246, //Platform Bug Reports
                'x__player' => $add_fields['x__player'],
                'x__metadata' => $add_fields,
            ));
            return false;
        }

        //Clean metadata is provided:
        if (isset($add_fields['x__metadata']) && is_array($add_fields['x__metadata'])) {
            $add_fields['x__metadata'] = serialize($add_fields['x__metadata']);
        } else {
            $add_fields['x__metadata'] = null;
        }

        //Set some defaults:
        if (!isset($add_fields['x__message'])) {
            $add_fields['x__message'] = null;
        } else {
            $add_fields['x__message'] = $add_fields['x__message'];
        }

        //Set some defaults:
        if (!isset($add_fields['x__website']) || $add_fields['x__website']<1) {
            $add_fields['x__website'] = website_setting(0, $add_fields['x__player']);
        }


        if (!isset($add_fields['x__time']) || is_null($add_fields['x__time'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $add_fields['x__time'] = $d->format("Y-m-d H:i:s.u");
        }

        if (!isset($add_fields['x__privacy'])|| is_null($add_fields['x__privacy'])) {
            $add_fields['x__privacy'] = 6176; //Transaction Published
        }

        //Set some zero defaults if not set:
        foreach(array('x__next', 'x__previous', 'x__follower', 'x__following', 'x__reference', 'x__weight') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Lets log:
        $this->db->insert('mench_ledger', $add_fields);


        //Fetch inserted id:
        $add_fields['x__id'] = $this->db->insert_id();


        //All good huh?
        if ($add_fields['x__id'] < 1) {

            //This should not happen:
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__player' => $add_fields['x__player'],
                'x__message' => 'create() Failed to create',
                'x__metadata' => array(
                    'input' => $add_fields,
                ),
            ));

            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['x__following'] > 0) {
                flag_for_search_indexing(12274, $add_fields['x__following']);
            }

            if ($add_fields['x__follower'] > 0) {
                flag_for_search_indexing(12274, $add_fields['x__follower']);
            }

            if ($add_fields['x__previous'] > 0) {
                flag_for_search_indexing(12273, $add_fields['x__previous']);
            }

            if ($add_fields['x__next'] > 0) {
                flag_for_search_indexing(12273, $add_fields['x__next']);
            }
        }


        //See if this transaction type has any followers that are essentially subscribed to it:
        $tr_watchers = $this->E_model->fetch_recursive(42381, $add_fields['x__type'], $this->config->item('n___30820'), array(), 1);
        if(is_array($tr_watchers) && count($tr_watchers)){

            //yes, start drafting email to be sent to them
            $u_name = 'Unknown';
            if($add_fields['x__player'] > 0){
                //Fetch member details:
                $add_e = $this->E_model->fetch(array(
                    'e__id' => $add_fields['x__player'],
                ));
                if(count($add_e)){
                    $u_name = $add_e[0]['e__title'];
                }
            }


            //Email Subject:
            $e___4593 = $this->config->item('e___4593'); //Transaction Types
            $subject = 'Notification: '  . $u_name . ' ' . $e___4593[$add_fields['x__type']]['m__title'];

            //Compose email body, start with transaction content:
            $html_message = ( strlen($add_fields['x__message']) > 0 ? $add_fields['x__message'] : '') . "\n";

            $e___32088 = $this->config->item('e___32088'); //Platform Variables

            //Append transaction object transactions:
            foreach($this->config->item('e___4341') as $e__id => $m) {

                if (in_array(6202 , $m['m__following'])) {

                    //IDEA
                    foreach($this->I_model->fetch(array( 'i__id' => $add_fields[$e___32088[$e__id]['m__message']] )) as $this_i){
                        $html_message .= $m['m__title'] . ': '.view_i_title($this_i, true).':'."\n".$this->config->item('base_url').view_memory(42903,33286) . $this_i['i__hashtag']."\n\n";
                    }

                } elseif (in_array(6160 , $m['m__following'])) {

                    //SOURCE
                    foreach($this->E_model->fetch(array( 'e__id' => $add_fields[$e___32088[$e__id]['m__message']] )) as $this_e){
                        $html_message .= $m['m__title'] . ': '.$this_e['e__title']."\n".$this->config->item('base_url').view_memory(42903,42902). $this_e['e__handle'] . "\n\n";
                    }

                } elseif (in_array(4367 , $m['m__following'])) {

                    //DISCOVERY
                    $html_message .= $m['m__title'] . ':'."\n".$this->config->item('base_url').view_app_link(12722).'?x__id=' . $add_fields[$e___32088[$e__id]['m__message']]."\n\n";

                }

            }

            //Finally append DISCOVERY ID:
            $html_message .= 'TRANSACTION: #'.$add_fields['x__id']."\n".$this->config->item('base_url').view_app_link(12722).'?x__id=' . $add_fields['x__id']."\n\n";

            //Send to all Watchers:
            foreach($tr_watchers as $tr_watcher) {
                //Do not inform the member who just took the action:
                if($tr_watcher['e__id']!=$add_fields['x__player']){
                    $this->X_model->send_dm($tr_watcher['e__id'], $subject, $html_message, array(
                        'x__reference' => $add_fields['x__id'], //Save transaction
                        'x__next' => $add_fields['x__next'],
                        'x__previous' => $add_fields['x__previous'],
                        'x__follower' => $add_fields['x__follower'],
                        'x__following' => $add_fields['x__following'],
                    ));
                }
            }
        }

        //Return:
        return $add_fields;

    }

    function fetch($query_filters = array(), $joins_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('x__id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('mench_ledger');

        //IDEA JOIN?
        if (in_array('x__previous', $joins_objects)) {
            $this->db->join('mench_ideas', 'x__previous=i__id','left');
        } elseif (in_array('x__next', $joins_objects)) {
            $this->db->join('mench_ideas', 'x__next=i__id','left');
        }

        //SOURCE JOIN?
        if (in_array('x__following', $joins_objects)) {
            $this->db->join('mench_sources', 'x__following=e__id','left');
        } elseif (in_array('x__follower', $joins_objects)) {
            $this->db->join('mench_sources', 'x__follower=e__id','left');
        } elseif (in_array('x__type', $joins_objects)) {
            $this->db->join('mench_sources', 'x__type=e__id','left');
        } elseif (in_array('x__player', $joins_objects)) {
            $this->db->join('mench_sources', 'x__player=e__id','left');
        }

        foreach($query_filters as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        foreach($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }

        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        $results = $q->result_array();


        //Verify Access to each item:
        if($select=='*' && isset($_SERVER['SERVER_NAME'])){
            if(array_intersect(array('x__previous','x__next'), $joins_objects)){
                //Idea results:
                $player_e = superpower_unlocked();
                foreach($results as $key => $value){
                    if(!access_level_i(null, $value['i__id'], $value)){
                        unset($results[$key]); //Remove this option
                    }
                }
            } elseif(array_intersect(array('x__following','x__follower'), $joins_objects)){
                //Source results:
                foreach($results as $key => $value){
                    if(!access_level_e(null, $value['e__id'], $value)){
                        unset($results[$key]); //Remove this option
                    }
                }
            }
        }

        return $results;

    }

    function update($id, $update_columns, $x__player = 0, $x__type = 0, $x__message = '')
    {

        $id = intval($id);
        if (count($update_columns)==0) {
            return false;
        } elseif ($x__type>0 && !in_array($x__type, $this->config->item('n___4593'))) {
            $this->X_model->create(array(
                'x__message' => 'x->update() failed to update because of invalid transaction type @'.$x__type,
                'x__type' => 4246, //Platform Bug Reports
                'x__player' => $x__player,
                'x__metadata' => $update_columns,
            ));
            return false;
        }

        //Fetch transaction before updating:
        $before_data = $this->X_model->fetch(array(
            'x__id' => $id,
        ));

        //Update metadata if needed:
        if(isset($update_columns['x__metadata']) && is_array($update_columns['x__metadata'])){
            //Merge this update into existing metadata:
            if(strlen($before_data[0]['x__metadata'])){

                //We have something, merge:
                $x__metadata = unserialize($before_data[0]['x__metadata']);
                $merged_array = array_merge($x__metadata, $update_columns['x__metadata']);
                $update_columns['x__metadata'] = serialize($merged_array);

            } else {
                //We have nothing, insert entire thing:
                $update_columns['x__metadata'] = serialize($update_columns['x__metadata']);
            }
        }

        //Set content to null if defined as empty:
        if(isset($update_columns['x__message']) && !strlen($update_columns['x__message'])){
            $update_columns['x__message'] = null;
        }

        //Update:
        $this->db->where('x__id', $id);
        $this->db->update('mench_ledger', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Log changes if successful:
        if ($affected_rows > 0 && $x__player > 0 && $x__type > 0) {

            if(strlen($x__message)==0){
                //Log modification transaction for every field changed:
                foreach($update_columns as $key => $value) {

                    if($before_data[0][$key]==$value){
                        continue;
                    }

                    //Now determine what type is this:
                    if($key=='x__privacy'){

                        $e___6186 = $this->config->item('e___6186'); //Interaction Privacy
                        $x__message .= view_db_field($key) . ' updated from [' . $e___6186[$before_data[0][$key]]['m__title'] . '] to [' . $e___6186[$value]['m__title'] . ']'."\n";

                    } elseif($key=='x__type'){

                        $e___4593 = $this->config->item('e___4593'); //Transaction Types
                        $x__message .= view_db_field($key) . ' updated from [' . $e___4593[$before_data[0][$key]]['m__title'] . '] to [' . $e___4593[$value]['m__title'] . ']'."\n";

                    } elseif(in_array($key, array('x__following', 'x__follower'))) {

                        //Fetch new/old source names:
                        $befores = $this->E_model->fetch(array(
                            'e__id' => $before_data[0][$key],
                        ));
                        $after_e = $this->E_model->fetch(array(
                            'e__id' => $value,
                        ));

                        $x__message .= view_db_field($key) . ' updated from [' . $befores[0]['e__title'] . '] to [' . $after_e[0]['e__title'] . ']' . "\n";

                    } elseif(in_array($key, array('x__previous', 'x__next'))) {

                        //Fetch new/old Idea outcomes:
                        $before_i = $this->I_model->fetch(array(
                            'i__id' => $before_data[0][$key],
                        ));
                        $after_i = $this->I_model->fetch(array(
                            'i__id' => $value,
                        ));

                        $x__message .= view_db_field($key) . ' updated from [' . $before_i[0]['i__message'] . '] to [' . $after_i[0]['i__message'] . ']' . "\n";

                    } elseif(in_array($key, array('x__message', 'x__weight'))){

                        $x__message .= view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']'."\n";

                    } else {

                        //Should not log updates since not specifically programmed:
                        continue;

                    }
                }
            }

            //Determine fields that have changed:
            $fields_changed = array();
            foreach($update_columns as $key => $value) {
                if($before_data[0][$key]!=$value){
                    array_push($fields_changed, array(
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ));
                }
            }

            if(strlen($x__message) > 0 && count($fields_changed) > 0){
                //Value has changed, log transaction:
                $this->X_model->create(array(
                    'x__reference' => $id, //Transaction Reference
                    'x__player' => $x__player,
                    'x__type' => $x__type,
                    'x__message' => $x__message,
                    'x__metadata' => array(
                        'x__id' => $id,
                        'fields_changed' => $fields_changed,
                    ),
                    //Copy old values:
                    'x__following' => $before_data[0]['x__following'],
                    'x__follower'  => $before_data[0]['x__follower'],
                    'x__previous' => $before_data[0]['x__previous'],
                    'x__next'  => $before_data[0]['x__next'],
                ));
            }
        }

        return $affected_rows;
    }


    function x_update_instant_select($focus__id, $o__id, $element_id, $new_e__id, $migrate_s__handle, $x__id = 0) {


        //Authenticate Member:
        $migrate_s__handle = ( substr($migrate_s__handle, 0, 1)=='@' ? trim(substr($migrate_s__handle, 1)) :  $migrate_s__handle);
        $player_e = superpower_unlocked();
        if (!$player_e) {
            return array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            );
        } elseif (intval($o__id) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Target ID',
            );
        } elseif (intval($element_id) < 1 || !count($this->config->item('n___'.$element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Variable ID ['.$element_id.']',
            );
        } elseif (intval($new_e__id) < 1 || !in_array($new_e__id, $this->config->item('n___'.$element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            );
        }


        //See if anything is being deleted:
        $auto_open_i_editor_modal = 0;
        $deletion_redirect = null;
        $delete_element = null;
        $links_removed = -1;
        $status = 0;

        if($element_id==4486 && $x__id > 0){

            //IDEA LINK TYPE
            $status = $this->X_model->update($x__id, array(
                'x__type' => $new_e__id,
            ), $player_e['e__id'], 13962);

        } elseif($element_id==13550 && $x__id > 0){

            //SOURCE LINK TYPE
            $status = $this->X_model->update($x__id, array(
                'x__type' => $new_e__id,
            ), $player_e['e__id'], 28799);

        } elseif($element_id==32292 && $x__id > 0){

            //SOURCE/SOURCE LINK
            $status = $this->X_model->update($x__id, array(
                'x__type' => $new_e__id,
            ), $player_e['e__id'], 28799);


        } elseif($element_id==42795 && $o__id > 0 && $new_e__id && $player_e){

            if(!$x__id){
                //Double check database as it may be updating newly selected value:
                foreach($this->X_model->fetch(array(
                    'x__following' => $o__id,
                    'x__follower' => $player_e['e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___42795')) . ')' => null, //Follow
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1) as $found_x){
                    $x__id = $found_x['x__id'];
                }
            }

            //Follow
            if($x__id > 0){
                //Updating reaction:
                if(in_array($new_e__id, $this->config->item('n___42850'))){
                    //Unsubscribe
                    $status = $this->X_model->update($x__id, array(
                        'x__privacy' => 6173, //Transaction Removed
                    ), $player_e['e__id'], 10673); //Media Removed
                } else {
                    $status = $this->X_model->update($x__id, array(
                        'x__type' => $new_e__id,
                    ), $player_e['e__id'], 42796);
                }
            } else {
                //Inserting new reaction:
                $status = count($this->X_model->create(array(
                    'x__player' => $player_e['e__id'],
                    'x__following' => $o__id,
                    'x__follower' => $player_e['e__id'],
                    'x__type' => $new_e__id,
                )));
            }

        } elseif($element_id==42260 && $o__id > 0 && $new_e__id && $player_e){

            //Check if current value?
            if(!$x__id){
                //Double check database as it may be updating newly selected value:
                foreach($this->X_model->fetch(array(
                    'x__following' => $player_e['e__id'],
                    'x__next' => $o__id,
                    'x__type IN (' . join(',', $this->config->item('n___42260')) . ')' => null, //Reactions
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1) as $found_x){
                    $x__id = $found_x['x__id'];
                }
            }

            //Reactions...
            if($x__id > 0){
                if(in_array($new_e__id, $this->config->item('n___42850'))){
                    $status = $this->X_model->update($x__id, array(
                        'x__privacy' => 6173, //Transaction Removed
                    ), $player_e['e__id'], 10673); //Media Removed
                } else {
                    //Updating reaction:
                    $status = $this->X_model->update($x__id, array(
                        'x__type' => $new_e__id,
                    ), $player_e['e__id'], 42794);
                }
            } else {
                //Inserting new reaction:
                $status = count($this->X_model->create(array(
                    'x__player' => $player_e['e__id'],
                    'x__following' => $player_e['e__id'],
                    'x__next' => $o__id,
                    'x__type' => $new_e__id,
                )));
            }

        } elseif($element_id==6177){

            //SOURCE ACCESS

            //Delete?
            if(!in_array($new_e__id, $this->config->item('n___7358'))){

                //Determine what to do after deleted:
                if($o__id==$focus__id){

                    //Find Published Followings:
                    foreach($this->X_model->fetch(array(
                        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                        'x__follower' => $o__id,
                    ), array('x__following'), 1, 0, array('e__title' => 'DESC')) as $up_e) {
                        $deletion_redirect = view_memory(42903,42902).$up_e['e__handle'];
                    }

                    //If still not found, go to main page if no followings found:
                    if(!$deletion_redirect){
                        foreach($this->E_model->fetch(array('e__id' => $o__id)) as $e2){
                            $deletion_redirect = view_memory(42903,42902).e2['e__handle'];
                        }
                    }

                } else {

                    //Just delete from UI using JS:
                    $delete_element = '.s__12274_' . $o__id;

                }

                //Delete all transactions:
                $links_removed = $this->E_model->remove($o__id, $player_e['e__id'], $migrate_s__handle);

            }

            //Update:
            if(strlen($migrate_s__handle)<2 || count($this->E_model->fetch(array(
                    'LOWER(e__handle)' => strtolower($migrate_s__handle),
                    'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                )))){
                $status = $this->E_model->update($o__id, array(
                    'e__privacy' => $new_e__id,
                ), true, $player_e['e__id']);
            }

            //Update Search Index:
            flag_for_search_indexing(12274,  $o__id);

        } elseif($element_id==31004){

            //IDEA ACCESS

            //Delete?
            if(!in_array($new_e__id, $this->config->item('n___31871'))){

                //Determine what to do after deleted:
                if($o__id==$focus__id){

                    //Find Published Followings:
                    foreach($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //IDEA LINKS
                        'x__next' => $o__id,
                    ), array('x__previous'), 1) as $previous_i) {
                        $deletion_redirect = view_memory(42903,33286).$previous_i['i__hashtag'];
                    }

                    //If not found, find active followings:
                    if(!$deletion_redirect){
                        foreach($this->X_model->fetch(array(
                            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                            'x__type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //IDEA LINKS
                            'x__next' => $o__id,
                        ), array('x__previous'), 1) as $previous_i) {
                            $deletion_redirect = view_memory(42903,33286).$previous_i['i__hashtag'];
                        }
                    }

                    //If still not found, go to main page if no followings found:
                    if(!$deletion_redirect){
                        foreach($this->I_model->fetch(array(
                            'i__id' => $o__id,
                        )) as $i){
                            $deletion_redirect = view_memory(42903,33286).$i['i__hashtag'];
                        }
                    }

                } else {

                    //Just delete from UI using JS:
                    $delete_element = '.s__12273_' . $o__id;

                }

                //Delete all transactions:
                $links_removed = $this->I_model->remove($o__id , $player_e['e__id'], $migrate_s__handle);

            }

            //Update Idea:
            $status = $this->I_model->update($o__id, array(
                'i__privacy' => $new_e__id,
            ), true, $player_e['e__id']);

            //Update Search Index:
            flag_for_search_indexing(12273,  $o__id);

        } elseif($element_id==4737){

            //IDEA TYPE
            $status = $this->I_model->update($o__id, array(
                'i__type' => $new_e__id,
            ), true, $player_e['e__id']);

            //See if we need to popup the idea edit modal here:

            $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields
            foreach(array_intersect($this->config->item('n___'.$new_e__id), $this->config->item('n___42179')) as $dynamic_e__id){

                $superpowers_required = array_intersect($this->config->item('n___10957'), $e___42179[$dynamic_e__id]['m__following']);
                if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                    continue;
                }

                //Let's determine the data type:
                $data_types = array_intersect($e___42179[$dynamic_e__id]['m__following'], $this->config->item('n___4592'));

                //ASSUME that we found 1 match as expected:
                foreach($data_types as $data_type_this){
                    $data_type = $data_type_this;
                    break;
                }
                $is_required = in_array($dynamic_e__id, $this->config->item('n___28239')); //Required Settings
                
                if(!$is_required){
                    //We are only interested in what is required
                    continue;
                }
                
                //See if we are missing value:
                if(in_array($data_type, $this->config->item('n___42188'))){

                    //Single or Multiple Choice:
                    $already_responded = count($this->X_model->fetch(array(
                        'x__following IN (' . join(',', $this->config->item('n___'.$dynamic_e__id)) . ')' => null, //All possible answers
                        'x__next' => $o__id,
                        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    )));

                } else {

                    $already_responded = count($this->X_model->fetch(array(
                        'x__following' => $dynamic_e__id,
                        'x__next' => $o__id,
                        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    )));

                }

                if(!$already_responded){
                    //We are missing a required response, auto open modal:
                    $auto_open_i_editor_modal = 1;
                }

            }

        }

        return array(
            'status' => intval($status) && ($links_removed<0 || $links_removed>0),
            'message' => 'Delete status ['.$status.'] with '.$links_removed.' Links removed',
            'deletion_redirect' => $deletion_redirect,
            'delete_element' => $delete_element,
            'auto_open_i_editor_modal' => $auto_open_i_editor_modal,
        );

    }
    function send_dm($e__id, $subject, $html_message, $x_data = array(), $template_i__id = 0, $x__website = 0, $log_tr = true, $demo_only = false)
    {

        $sms_subscriber = false;
        $bypass_notifications = count($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42256')) . ')' => null, //Writes
            'x__following' => 31779, //Mandatory Emails
            'x__next' => $template_i__id,
        )));

        if(!$bypass_notifications){
            $notification_levels = $this->X_model->fetch(array(
                'x__following IN (' . join(',', $this->config->item('n___30820')) . ')' => null, //Active Subscriber
                'x__follower' => $e__id,
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ));
            if (!count($notification_levels)) {
                return array(
                    'status' => 0,
                    'message' => 'User is not an active subscriber',
                );
            }
            $sms_subscriber = in_array($notification_levels[0]['x__following'], $this->config->item('n___28915'));
        }

        $stats = array(
            'email_addresses' => array(),
            'phone_count' => 0,
        );


        //Send Emails:
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__following' => 3288, //Email
            'x__follower' => $e__id,
        )) as $e_data){

            if(!filter_var($e_data['x__message'], FILTER_VALIDATE_EMAIL)){
                $this->X_model->update($e_data['x__id'], array(
                    'x__privacy' => 6173, //Transaction Deleted
                ), $e__id, 27890 /* Website Archive */);
                continue;
            }

            array_push($stats['email_addresses'], $e_data['x__message']);

        }

        if(count($stats['email_addresses']) > 0){
            //Send email:
            dispatch_email($stats['email_addresses'], $subject, $html_message, $e__id, $x_data, $template_i__id, $x__website, $log_tr, $demo_only);
        }



        //Should we send SMS?
        $twilio_account_sid = website_setting(30859);
        $twilio_auth_token = website_setting(30860);
        $twilio_from_number = website_setting(27673);
        if($sms_subscriber && $twilio_account_sid && $twilio_auth_token && $twilio_from_number){

            //Yes, generate message
            $sms_message  = 'Update: We emailed ['.$subject.'] to '.join(' & ',$stats['email_addresses']).' (May end-up in Spam)';

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n"," ",$sms_message);

            //Send SMS
            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__following' => 4783, //Phone
                'x__follower' => $e__id,
            )) as $e_data){

                foreach(explode('|||',wordwrap($sms_message, view_memory(6404,27891), "|||")) as $single_message){

                    $sms_sent = dispatch_sms($e_data['x__message'], $single_message, $e__id, $x_data, $template_i__id, $x__website, $log_tr, $demo_only);

                    if(!$sms_sent){
                        //bad number, remove it:
                        $this->X_model->update($e_data['x__id'], array(
                            'x__privacy' => 6173, //Transaction Deleted
                        ), $e__id, 27890 /* Website Archive */);
                    }

                }

                $stats['phone_count']++;

            }

        }

        return array(
            'status' => ( $stats['phone_count']>0 || count($stats['email_addresses'])>0 ? 1 : 0 ),
            'email_count' => count($stats['email_addresses']),
            'phone_count' => $stats['phone_count'],
            'message' => 'Message sent',
        );

    }



    function send_i_mass_dm($list_of_e__id, $i, $x__website = 0, $ensure_undiscovered = true, $demo_only = false){

        $total_sent = 0;
        $x__website = ( $x__website>0 ? $x__website : ( isset($i['x__website']) ? $i['x__website'] : 0 ) );
        $subject_line = view_i_title($i, true);
        $wacth_repeat_handles = array();

        foreach($list_of_e__id as $count => $x) {

            if(in_array($x['e__handle'], $wacth_repeat_handles)){
                //This should not happen! Report bug:
                $this->X_model->create(array(
                    'x__type' => 4246, //Platform Bug Reports
                    'x__message' => 'send_i_mass_dm() Detected duplicate Source Handle Bug: '.$x['e__handle'],
                    'x__metadata' => array(
                        'list_of_e__id' => $list_of_e__id,
                        'i' => $i,
                        'x__website' => $x__website,
                        'ensure_undiscovered' => $ensure_undiscovered,
                        'demo_only' => $demo_only,
                        'count' => $count,
                        'x' => $x,
                        'subject_line' => $subject_line,
                    ),
                ));
                break; //Stop sending more messages!
            }

            //Map this handle:
            array_push($wacth_repeat_handles, $x['e__handle']);


            if(!isset($x['e__id'])){
                //Invalid input for sending:
                $this->X_model->create(array(
                    'x__type' => 4246, //Platform Bug Reports
                    'x__message' => 'send_i_mass_dm() Invalid user row',
                    'x__metadata' => array(
                        '$i' => $i,
                        '$list_of_e__id' => $list_of_e__id,
                        '$x' => $x,
                    ),
                ));
                continue;
            } elseif($ensure_undiscovered && count($this->X_model->fetch(array(
                'x__previous' => $i['i__id'],
                'x__player' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
                //Already discovered:
                continue;
            }


            $content_message = view_i__links($i, $x['e__id'], true); //Hide the show more content if any
            if(!(substr($subject_line, 0, 1)=='#' && !substr_count($subject_line, ' '))){
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">','</div>', $content_message);
            }

            //Append children as options:
            $html_message = '';
            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
                'x__previous' => $i['i__id'],
            ), array('x__next'), 0, 0, array('x__weight' => 'ASC')) as $down_or){
                //Has this user discovered this idea or no?
                $html_message .= '<div class="line">'.view_i_title($down_or, true).':</div>';
                $html_message .= '<div class="line">'.'https://'.get_domain('m__message', $x['e__id'], $x__website).view_memory(42903,33286).$down_or['i__hashtag'].( i_startable($down_or) ? '/'.view_memory(6404,4235) : '' ).'?e__handle='.$x['e__handle'].'&e__time='.time().'&e__hash='.view__hash(time().$x['e__handle']).'</div>';
            }

            //Where to place the next step?
            if(substr_count($content_message, 'link_here')==1){
                //We have direction to place the next step somewhere specific:
                $content_message = str_replace('link_here', $html_message, $content_message);
            } else {
                $content_message = $content_message . $html_message;
            }

            $send_dm = $this->X_model->send_dm($x['e__id'], $subject_line, $content_message, array(
                'x__previous' => $i['i__id'],
            ), $i['i__id'], $x__website, true, $demo_only);

            //Mark as discovered:
            if($send_dm['status'] && !$demo_only){
                $this->X_model->mark_complete(43142, $x['e__id'], 0, $i);
                $total_sent ++;
            }

        }

        return $total_sent;
    }


    function find_previous($e__id, $target_i__hashtag, $focus_i__id, $loop_breaker_ids = array())
    {

        //echo 'Previous:'.$e__id.'/'.$target_i__hashtag.'/'.$focus_i__id;

        if(count($loop_breaker_ids)>0 && in_array($focus_i__id, $loop_breaker_ids)){
            return array();
        }
        array_push($loop_breaker_ids, intval($focus_i__id));

        //Fetch followings:
        foreach($this->X_model->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //Active Sequence Up
            'x__next' => $focus_i__id,
        ), array('x__previous')) as $i_previous) {

            //Validate Selection:
            $input__selection = in_array($i_previous['i__type'], $this->config->item('n___7712'));
            $is_selected = count($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'x__previous' => $i_previous['i__id'],
                'x__next' => $focus_i__id,
                'x__player' => $e__id,
            )));

            if($e__id>0 && !$is_selected && $input__selection){
                continue;
            }

            //Did we find it?
            if($i_previous['i__hashtag']==$target_i__hashtag){
                return array($i_previous);
            }

            //Keep looking further up:
            $website_finder = $this->X_model->find_previous($e__id, $target_i__hashtag, $i_previous['i__id'], $loop_breaker_ids);
            if(count($website_finder)){
                array_push($website_finder, $i_previous);
                return $website_finder;
            }
        }

        //Did not find any followings:
        return array();

    }




    function find_previous_discovered($focus_i__id, $x__player, $loop_breaker_ids = array()){

        /*
         *
         * Returns hashtag if discovered upwards
         *
         * */

        if(count($loop_breaker_ids)>0 && in_array($focus_i__id, $loop_breaker_ids)){
            return false;
        }
        array_push($loop_breaker_ids, intval($focus_i__id));

        foreach($this->X_model->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //Active Sequence Up
            'x__next' => $focus_i__id,
        ), array('x__previous')) as $prev_i){

            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__player' => $x__player,
                'x__previous' => $prev_i['i__id'],
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ), array('x__next')) as $x){
                return $x['i__hashtag'];
            }

            return $this->X_model->find_previous_discovered($prev_i['i__id'], $x__player, $loop_breaker_ids);
        }

        //Did not find!
        return false;

    }







    function find_next($e__id, $target_i__hashtag, $i, $find_after_i__id = 0, $search_up = true, $target_completed = false, $loop_breaker_ids = array())
    {

        if(count($loop_breaker_ids)>0 && in_array($i['i__id'], $loop_breaker_ids)){
            return null;
        }
        array_push($loop_breaker_ids, intval($i['i__id']));

        $input__selection = in_array($i['i__type'], $this->config->item('n___7712'));
        $found_trigger = null;

        foreach ($this->X_model->fetch(array(
            'x__previous' => $i['i__id'],
            'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('x__next'), 0, 0, array('x__weight' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_i__id && !$found_trigger) {
                if ($next_i['i__id']==$find_after_i__id) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            $is_selected = count($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'x__previous' => $i['i__id'],
                'x__next' => $next_i['i__id'],
                'x__player' => $e__id,
            )));
            if($input__selection && !$is_selected){
                continue;
            }


            //Return this if everything is completed, or if this is incomplete:
            if($target_completed || !count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__player' => $e__id,
                    'x__previous' => $next_i['i__id'],
                )))){
                return $next_i['i__hashtag'];
            }

            //Keep looking deeper:
            $next_i__hashtag = $this->X_model->find_next($e__id, $target_i__hashtag, $next_i, 0, false, $target_completed, $loop_breaker_ids);
            if ($next_i__hashtag) {
                return $next_i__hashtag;
            }

        }

        //echo 'Next:'.$e__id.'/'.$target_i__hashtag.'/'.$i['i__hashtag'];

        if ($search_up && $target_i__hashtag!=$i['i__hashtag']) {
            //Check Previous/Up
            $current_previous = $i['i__id'];
            foreach (array_reverse($this->X_model->find_previous($e__id, $target_i__hashtag, $i['i__id'])) as $p_i) {
                //Find the next siblings:
                $next_i__hashtag = $this->X_model->find_next($e__id, $target_i__hashtag, $p_i, $current_previous, false, $target_completed);
                if ($next_i__hashtag) {
                    return $next_i__hashtag;
                }
                $current_previous = $p_i['i__id'];
            }
        }

        //Nothing found:
        return null;

    }





    function mark_complete($x__type, $x__player, $target_i__id = 0, $i, $focus_i_data = array(), $x_data = array()) {

        if(!$x__player || !in_array($x__type, $this->config->item('n___31777'))){
            $this->X_model->create(array(
                'x__player' => $x__player,
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'mark_complete() Invalid x__type @'.$x__type.' missing in @31777 OR Missing $x__player',
                'x__metadata' => array(
                    '$target_i__id' => $target_i__id,
                    '$i' => $i,
                    '$x_data' => $x_data,
                ),
            ));
            return array(
                'status' => 0,
                'message' => 'Invalid Date',
            );
        }

        //Do we need to save text/upload ?
        $input__selection = in_array($i['i__type'], $this->config->item('n___7712'));
        $input__upload = in_array($i['i__type'], $this->config->item('n___43004'));
        $input__text = in_array($i['i__type'], $this->config->item('n___43002')) || in_array($i['i__type'], $this->config->item('n___43003'));



        if($input__upload || $input__text){

            if(!isset($focus_i_data['i__text'])){
                $focus_i_data['i__text'] = null;
            }
            if(!isset($focus_i_data['uploaded_media'])){
                $focus_i_data['uploaded_media'] = array();
            }


            //Must add a new idea, but first let's validate the input:
            if($i['i__type']==31794 && strlen($focus_i_data['i__text']) && !is_numeric($focus_i_data['i__text'])){
                //Number Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Number',
                );
            } elseif($i['i__type']==42915 && strlen($focus_i_data['i__text']) && !filter_var($focus_i_data['i__text'], FILTER_VALIDATE_URL)){
                //Link Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid URL',
                );
            } elseif($i['i__type']==30350 && strlen($focus_i_data['i__text']) && !strtotime($focus_i_data['i__text'])){
                //Date Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Date',
                );
            }

            //Find previous answers by this user:
            $x_responses = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type' => 33532, //Share Idea
                'x__previous' => $i['i__id'],
                'x__player' => $x__player,
            ), array('x__next'), 0, 1, array('x__id' => 'DESC'));


            //All validated, lets make the new idea:
            if(strlen($focus_i_data['i__text']) || count($focus_i_data['uploaded_media'])){


                if(count($x_responses)){

                    //Update existing response if different:
                    if($focus_i_data['i__text']!=$x_responses[0]['i__message']){
                        $view_sync_links = view_sync_links($focus_i_data['i__text'], true, $x_responses[0]['i__id']);
                    }
                    $this_i__id = $x_responses[0]['i__id'];

                } else {

                    //Create a new response:
                    $i_new = $this->I_model->create(array(
                        'i__message' => $focus_i_data['i__text'],
                        'i__type' => 6677, //Statement
                        'i__privacy' => 42625, //Private
                    ), $x__player);

                    $this_i__id = $i_new['i__id'];

                    //Link to this idea:
                    $this->X_model->create(array(
                        'x__type' => 33532, //REPLY
                        'x__player' => $x__player,
                        'x__previous' => $i['i__id'],
                        'x__next' => $i_new['i__id'],
                    ));

                }

                //Process Media:
                $media_stats = process_media($this_i__id, $focus_i_data['uploaded_media']);

            } elseif (count($x_responses)){

                //Delete Links
                $this->I_model->remove($x_responses[0]['i__id'] , $x__player);

                //Delete Idea:
                $this->I_model->update($x_responses[0]['i__id'], array(
                    'i__privacy' => 6182, //Deleted Idea
                ), true, $x__player);

            }

        }

        $x_data['x__player'] = $x__player;
        $x_data['x__type'] = $x__type;
        $x_data['x__previous'] = $i['i__id'];

        //Always add Idea to x__previous
        if($target_i__id>0 && (!isset($x_data['x__next']) || !intval($x_data['x__next']))){
            $x_data['x__next'] = $target_i__id;
        }

        if (!isset($x_data['x__message'])) {
            $x_data['x__message'] = null;
        }

        $es_creator = $this->E_model->fetch(array(
            'e__id' => $x__player,
        ));

        //Make sure not duplicate:
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            //'x__type NOT IN (' . join(',', $this->config->item('n___31776')) . ')' => null, //Unremovable Discoveries
            'x__previous' => ( isset($x_data['x__previous']) ? $x_data['x__previous'] : 0 ),
            'x__next' => ( isset($x_data['x__next']) ? $x_data['x__next'] : 0 ),
            'x__player' => $x__player,
            'x__message' => $x_data['x__message'],
        )) as $already_discovered){
            //Already discovered! Return this:
            return array(
                'status' => 1,
                'message' => 'Already Discovered',
                'new_x' => $already_discovered,
            );
        }

        //Add new transaction:
        $domain_url = get_domain('m__message', $x__player);
        $new_x = $this->X_model->create($x_data);

        //Auto Complete OR Answers:
        if($input__selection){
            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'x__player' => $x_data['x__player'],
                'x__previous' => $i['i__id'],
            ), array('x__next'), 0) as $next_i){
                if(!in_array($next_i['i__type'], $this->config->item('n___43039')) && !count($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'i__privacy IN (' . join(',', $this->config->item('n___42948')) . ')' => null, //Public Ideas
                        'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //IDEA LINKS
                        'x__previous' => $next_i['i__id'],
                    ), array('x__next'), 0, 0))){
                    //Mark as complete:
                    $this->X_model->mark_complete(i__discovery_link($next_i), $x_data['x__player'], $target_i__id, $next_i, $x_data);
                }
            }
        }

        if ($x_data['x__player'] && in_array($x_data['x__type'], $this->config->item('n___40986'))) {

            //Discovery Triggers?
            $clone_urls = '';
            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___32275')) . ')' => null, //DISCOVERY TRIGGERS
                'x__previous' => $i['i__id'],
            ), array('x__next'), 0, 0, array('x__weight' => 'ASC')) as $clone_i){

                if($clone_i['x__type']==32247){

                    //Discovery Clone
                    $new_title = $es_creator[0]['e__title'].' '.$clone_i['i__message'];
                    $result = $this->I_model->recursive_clone($clone_i['i__id'], 0, $x_data['x__player'], null, $new_title);
                    if($result['status']){

                        //Add as watcher:
                        $this->X_model->create(array(
                            'x__type' => 10573, //WATCHERS
                            'x__player' => $x_data['x__player'],
                            'x__following' => $x_data['x__player'],
                            'x__next' => $result['new_i__id'],
                        ));

                        //New link:
                        $clone_urls .= $new_title.':'."\n".'https://'.get_domain('m__message', $x_data['x__player']).view_memory(42903,33286).$result['new_i__hashtag']."\n\n";
                    }

                } elseif($clone_i['x__type']==32304){

                    //Discovery Forget: Remove all Discoveries made by this user:
                    foreach($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //EXPANDED DISCOVERIES
                        'x__previous' => $i['i__id'],
                        'x__player' => $x_data['x__player'],
                    )) as $remove_x){
                        $this->X_model->update($remove_x['x__id'], array(
                            'x__privacy' => 6173, //Remove this discovery
                        ), $x_data['x__player'], 29431 /* Play Auto Removed */);
                    }

                }

            }

            if(strlen($clone_urls)){
                //Send DM with all the new clone idea URLs:
                $clone_urls = $clone_urls.'You have been added as a subscriber so you will be notified when anyone start using your link.';
                $i_title = view_i_title($i, true);
                $this->X_model->send_dm($x_data['x__player'], $i_title , $clone_urls);
                //Also DM all watchers of the idea:
                foreach($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type' => 10573, //WATCHERS
                    'x__next' => $i['i__id'],
                ), array(), 0) as $watcher){
                    $this->X_model->send_dm($watcher['x__following'], $i_title, $clone_urls);
                }
            }



            //ADD PROFILE?
            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Following Add
                'x__next' => $i['i__id'],
            ), array('x__following')) as $x_tag){

                //Check if special profile add?
                if(in_array($x_tag['x__following'], $this->config->item('n___43048'))){

                    //Special Addition:

                    if($x_tag['x__following']==6197 && strlen(trim($x_data['x__message']))>=2){

                        //Update Source Title:
                        $this->E_model->update($x_data['x__player'], array(
                            'e__title' => $x_data['x__message'],
                        ), true, $x_data['x__player']);

                        //Update live session as well:
                        $es_creator[0]['e__title'] = $x_data['x__message'];
                        $this->E_model->activate_session($es_creator[0], true);

                    } elseif($x_tag['x__following']==6198 && filter_var($media_stats['media_e__cover'], FILTER_VALIDATE_URL)){

                        //Update Source Cover:
                        //Update profile picture for current user:
                        $this->E_model->update($x__player, array(
                            'e__cover' => $media_stats['media_e__cover'],
                        ), true, $x__player);

                        //Update live session as well:
                        $es_creator[0]['e__cover'] = $media_stats['media_e__cover'];
                        $this->E_model->activate_session($es_creator[0], true);

                    }

                } else {

                    //Assign tag if following/follower transaction NOT previously assigned:
                    $append_source = append_source($x_tag['x__following'], $x_data['x__player'], ( isset($focus_i_data['i__text']) ? $focus_i_data['i__text'] : null ), $i['i__id']);

                    //See if Session needs to be updated:
                    $player_e = superpower_unlocked();
                    if($player_e && $player_e['e__id']==$x_data['x__player'] && $append_source){
                        $this->E_model->activate_session($es_creator[0], true);
                    }

                }
            }


            //REMOVE PROFILE?
            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 26599, //Following Remove
                'x__next' => $i['i__id'],
            )) as $x_tag){

                //Remove Following IF previously assigned:
                foreach($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__following' => $x_tag['x__following'], //CERTIFICATES saved here
                    'x__follower' => $x_data['x__player'],
                )) as $existing_x){

                    $this->X_model->update($existing_x['x__id'], array(
                        'x__privacy' => 6173,
                    ), $x_data['x__player'], 12197 /* Following Removed */);

                    //See if Session needs to be updated:
                    if($player_e && $player_e['e__id']==$x_data['x__player']){
                        //Yes, update session:
                        $this->E_model->activate_session($es_creator[0], true);
                    }
                }
            }


            //Notify watchers IF any:
            $watchers = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 10573, //WATCHERS
                'x__next' => $i['i__id'],
            ), array(), 0);
            if(count($watchers)){

                $es_discoverer = $this->E_model->fetch(array(
                    'e__id' => $x_data['x__player'],
                ));
                if(count($es_discoverer)){

                    //Fetch Discoverer contact:
                    $discoverer_contact = '';
                    foreach($this->config->item('e___34541') as $x__type => $m) {
                        foreach($this->X_model->fetch(array(
                            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__follower' => $x_data['x__player'],
                            'x__following' => $x__type,
                            'LENGTH(x__message)>0' => null,
                        )) as $x_progress){
                            $discoverer_contact .= $m['m__title'].':'."\n".$x_progress['x__message']."\n\n";
                        }
                    }

                    //Notify Idea Watchers
                    $sent_watchers = array();
                    foreach($watchers as $watcher){
                        if(!in_array(intval($watcher['x__following']), $sent_watchers)){
                            array_push($sent_watchers, intval($watcher['x__following']));

                            $this->X_model->send_dm($watcher['x__following'], $es_discoverer[0]['e__title'].' Discovered: '.view_i_title($i, true),
                                //Message Body:
                                view_i_title($i, true).':'."\n".'https://'.$domain_url.view_memory(42903,33286).$i['i__hashtag']."\n\n".
                                ( strlen($x_data['x__message']) ? $x_data['x__message']."\n\n" : '' ).
                                $es_discoverer[0]['e__title'].':'."\n".'https://'.$domain_url.view_memory(42903,42902).$es_discoverer[0]['e__handle']."\n\n".
                                $discoverer_contact
                            );
                        }
                    }
                }
            }
        }

        return array(
            'status' => 1,
            'message' => 'Marked as Complete',
            'new_x' => $new_x,
        );

    }



    function tree_progress($e__id, $i, $current_level = 0, $loop_breaker_ids = array())
    {

        if(count($loop_breaker_ids)>0 && in_array($i['i__id'], $loop_breaker_ids)){
            return false;
        }

        $recursive_down_ids = $this->I_model->recursive_down_ids($i, 'AND');
        if(!isset($recursive_down_ids['recursive_i_ids']) || !count($recursive_down_ids['recursive_i_ids'])){
            return false;
        }

        $current_level++;
        array_push($loop_breaker_ids, intval($i['i__id']));

        //Count completed:
        $list_discovered = array();
        foreach($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__player' => $e__id, //Belongs to this Member
            'x__previous IN (' . join(',', $recursive_down_ids['recursive_i_ids'] ) . ')' => null,
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('x__previous'), 0) as $completed){
            if(!in_array($completed['i__hashtag'], $list_discovered)){
                array_push($list_discovered, $completed['i__hashtag']);
            }
        }


        //Calculate common steps and expansion steps recursively for this u:
        $metadata_this = array(
            'fixed_total' => count($recursive_down_ids['recursive_i_ids']),
            'list_total' => $recursive_down_ids['recursive_i_ids'],
            'fixed_discovered' => count($list_discovered),
            'list_discovered' => $list_discovered,
        );

        //Now let's check possible expansions:
        if(count($recursive_down_ids['recursive_i_ids'])){
            foreach($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'x__player' => $e__id, //Belongs to this Member
                'x__previous IN (' . join(',', $recursive_down_ids['recursive_i_ids'] ) . ')' => null,
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ), array('x__next')) as $expansion_in) {

                //Fetch recursive:
                $tree_progress = $this->X_model->tree_progress($e__id, $expansion_in, $current_level, $loop_breaker_ids);

                if(!$tree_progress && !count($this->X_model->fetch(array(
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__player' => $e__id, //Belongs to this Member
                        'x__previous' => $expansion_in['i__id'],
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                    $tree_progress = array(
                        'fixed_total' => 1,
                        'list_total' => array($expansion_in['i__id']),
                        'fixed_discovered' => 0,
                        'list_discovered' => array(),
                    );
                }

                //Addup completion stats for this:
                $metadata_this['fixed_total'] += $tree_progress['fixed_total'];
                $metadata_this['fixed_discovered'] += $tree_progress['fixed_discovered'];

                if($tree_progress['list_total'] && count($tree_progress['list_total'])){
                    foreach($tree_progress['list_total'] as $tree_id){
                        if(!in_array($tree_id, $metadata_this['list_total'])){
                            array_push($metadata_this['list_total'], $tree_id);
                        }
                    }
                }

                if($tree_progress['list_discovered'] && count($tree_progress['list_discovered'])){
                    foreach($tree_progress['list_discovered'] as $tree_id){
                        if(!in_array($tree_id, $metadata_this['list_discovered'])){
                            array_push($metadata_this['list_discovered'], $tree_id);
                        }
                    }
                }
            }
        }

        if($current_level==1){

            /*
             *
             * Completing an discoveries depends on two factors:
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
            $metadata_this['fixed_completed_percentage'] = 0;

            //Calculate completion rate based on estimated time cost:
            if($metadata_this['fixed_total'] > 0){
                $metadata_this['fixed_completed_percentage'] = intval(floor( $metadata_this['fixed_discovered'] / $metadata_this['fixed_total'] * 100 ));
            }


        }

        //Return results:
        return $metadata_this;

    }


    function i_has_started($e__id, $i__hashtag){
        return count($this->X_model->fetch(array(
            'x__previous = x__next' => NULL,
            'LOWER(i__hashtag)' => strtolower($i__hashtag),
            'x__player' => $e__id,
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array('x__next')));
    }




}