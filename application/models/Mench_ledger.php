<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mench_ledger extends CIdea_cache
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
        if (!isset($add_fields['link_player']) || intval($add_fields['link_player']) < 1) {
            $add_fields['link_player'] = 14068; //GUEST MEMBER
        }

        //Only require transaction type:
        if (detect_missing_columns($add_fields, array('link_type'), $add_fields['link_player'])) {
            return false;
        }

        if(!in_array($add_fields['link_type'], $this->config->item('n___4593'))){
            $this->Mench_ledger->create(array(
                'link_text' => 'x->create() failed to create because of invalid transaction type @'.$add_fields['link_type'],
                'link_type' => 4246, //Platform Bug Reports
                'link_player' => $add_fields['link_player'],
                'link_metadata' => $add_fields,
            ));
            return false;
        }

        //Clean metadata is provided:
        if (isset($add_fields['link_metadata']) && is_array($add_fields['link_metadata'])) {
            $add_fields['link_metadata'] = serialize($add_fields['link_metadata']);
        } else {
            $add_fields['link_metadata'] = null;
        }

        //Set some defaults:
        if (!isset($add_fields['link_text'])) {
            $add_fields['link_text'] = null;
        } else {
            $add_fields['link_text'] = $add_fields['link_text'];
        }

        //Set some defaults:
        if (!isset($add_fields['link_domain']) || $add_fields['link_domain']<1) {
            $add_fields['link_domain'] = website_setting(0, $add_fields['link_player']);
        }


        if (!isset($add_fields['link_time']) || is_null($add_fields['link_time'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $add_fields['link_time'] = $d->format("Y-m-d H:i:s.u");
        }

        if (!isset($add_fields['link_privacy'])|| is_null($add_fields['link_privacy'])) {
            $add_fields['link_privacy'] = 6176; //Transaction Published
        }

        //Set some zero defaults if not set:
        foreach(array('link_right', 'link_left', 'link_down', 'link_up', 'link_reference', 'link_number') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Lets log:
        $this->db->insert('mench_ledger', $add_fields);


        //Fetch inserted id:
        $add_fields['link_id'] = $this->db->insert_id();


        //All good huh?
        if ($add_fields['link_id'] < 1) {

            //This should not happen:
            $this->Mench_ledger->create(array(
                'link_type' => 4246, //Platform Bug Reports
                'link_player' => $add_fields['link_player'],
                'link_text' => 'create() Failed to create',
                'link_metadata' => array(
                    'input' => $add_fields,
                ),
            ));

            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['link_up'] > 0) {
                flag_for_search_indexing(12274, $add_fields['link_up']);
            }

            if ($add_fields['link_down'] > 0) {
                flag_for_search_indexing(12274, $add_fields['link_down']);
            }

            if ($add_fields['link_left'] > 0) {
                flag_for_search_indexing(12273, $add_fields['link_left']);
            }

            if ($add_fields['link_right'] > 0) {
                flag_for_search_indexing(12273, $add_fields['link_right']);
            }
        }


        //See if this transaction type has any followers that are essentially subscribed to it:
        $tr_watchers = $this->Source_cache->fetch_recursive(42381, $add_fields['link_type'], $this->config->item('n___30820'), array(), 1);
        if(is_array($tr_watchers) && count($tr_watchers)){

            //yes, start drafting email to be sent to them
            $u_name = 'Unknown';
            if($add_fields['link_player'] > 0){
                //Fetch member details:
                $add_e = $this->Source_cache->fetch(array(
                    'e__id' => $add_fields['link_player'],
                ));
                if(count($add_e)){
                    $u_name = $add_e[0]['e__title'];
                }
            }


            //Email Subject:
            $e___4593 = $this->config->item('e___4593'); //Transaction Types
            $subject = 'Notification: '  . $u_name . ' ' . $e___4593[$add_fields['link_type']]['m__title'];

            //Compose email body, start with transaction content:
            $html_message = ( strlen($add_fields['link_text']) > 0 ? $add_fields['link_text'] : '') . "\n";

            $e___32088 = $this->config->item('e___32088'); //Platform Variables

            //Append transaction object transactions:
            foreach($this->config->item('e___4341') as $e__id => $m) {

                if (in_array(6202 , $m['m__following'])) {

                    //IDEA
                    foreach($this->Idea_cache->fetch(array( 'i__id' => $add_fields[$e___32088[$e__id]['m__message']] )) as $this_i){
                        $html_message .= $m['m__title'] . ': '.view__i_title($this_i, true).':'."\n".$this->config->item('base_url').view__memory(42903,33286) . $this_i['i__hashtag']."\n\n";
                    }

                } elseif (in_array(6160 , $m['m__following'])) {

                    //SOURCE
                    foreach($this->Source_cache->fetch(array( 'e__id' => $add_fields[$e___32088[$e__id]['m__message']] )) as $this_e){
                        $html_message .= $m['m__title'] . ': '.$this_e['e__title']."\n".$this->config->item('base_url').view__memory(42903,42902). $this_e['e__handle'] . "\n\n";
                    }

                } elseif (in_array(4367 , $m['m__following'])) {

                    //DISCOVERY
                    $html_message .= $m['m__title'] . ':'."\n".$this->config->item('base_url').view__app_link(12722).'?link_id=' . $add_fields[$e___32088[$e__id]['m__message']]."\n\n";

                }

            }

            //Finally append DISCOVERY ID:
            $html_message .= 'TRANSACTION: #'.$add_fields['link_id']."\n".$this->config->item('base_url').view__app_link(12722).'?link_id=' . $add_fields['link_id']."\n\n";

            //Send to all Watchers:
            foreach($tr_watchers as $tr_watcher) {
                //Do not inform the member who just took the action:
                if($tr_watcher['e__id']!=$add_fields['link_player']){
                    $this->Mench_ledger->send_dm($tr_watcher['e__id'], $subject, $html_message, array(
                        'link_reference' => $add_fields['link_id'], //Save transaction
                        'link_right' => $add_fields['link_right'],
                        'link_left' => $add_fields['link_left'],
                        'link_down' => $add_fields['link_down'],
                        'link_up' => $add_fields['link_up'],
                    ));
                }
            }
        }

        //Return:
        return $add_fields;

    }

    function fetch($query_filters = array(), $joins_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('link_id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('mench_ledger');

        //IDEA JOIN?
        if (in_array('link_left', $joins_objects)) {
            $this->db->join('cache_ideas', 'link_left=i__id','left');
        } elseif (in_array('link_right', $joins_objects)) {
            $this->db->join('cache_ideas', 'link_right=i__id','left');
        }

        //SOURCE JOIN?
        if (in_array('link_up', $joins_objects)) {
            $this->db->join('cache_sources', 'link_up=e__id','left');
        } elseif (in_array('link_down', $joins_objects)) {
            $this->db->join('cache_sources', 'link_down=e__id','left');
        } elseif (in_array('link_type', $joins_objects)) {
            $this->db->join('cache_sources', 'link_type=e__id','left');
        } elseif (in_array('link_player', $joins_objects)) {
            $this->db->join('cache_sources', 'link_player=e__id','left');
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
            if(array_intersect(array('link_left','link_right'), $joins_objects)){
                //Idea results:
                $player_e = superpower_unlocked();
                foreach($results as $key => $value){
                    if(!access_level_i(null, $value['i__id'], $value)){
                        unset($results[$key]); //Remove this option
                    }
                }
            } elseif(array_intersect(array('link_up','link_down'), $joins_objects)){
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

    function update($id, $update_columns, $link_player = 0, $link_type = 0, $link_text = '')
    {

        $id = intval($id);
        if (count($update_columns)==0) {
            return false;
        } elseif ($link_type>0 && !in_array($link_type, $this->config->item('n___4593'))) {
            $this->Mench_ledger->create(array(
                'link_text' => 'x->update() failed to update because of invalid transaction type @'.$link_type,
                'link_type' => 4246, //Platform Bug Reports
                'link_player' => $link_player,
                'link_metadata' => $update_columns,
            ));
            return false;
        }

        //Fetch transaction before updating:
        $before_data = $this->Mench_ledger->fetch(array(
            'link_id' => $id,
        ));

        //Update metadata if needed:
        if(isset($update_columns['link_metadata']) && is_array($update_columns['link_metadata'])){
            //Merge this update into existing metadata:
            if(strlen($before_data[0]['link_metadata'])){

                //We have something, merge:
                $link_metadata = unserialize($before_data[0]['link_metadata']);
                $merged_array = array_merge($link_metadata, $update_columns['link_metadata']);
                $update_columns['link_metadata'] = serialize($merged_array);

            } else {
                //We have nothing, insert entire thing:
                $update_columns['link_metadata'] = serialize($update_columns['link_metadata']);
            }
        }

        //Set content to null if defined as empty:
        if(isset($update_columns['link_text']) && !strlen($update_columns['link_text'])){
            $update_columns['link_text'] = null;
        }

        //Update:
        $this->db->where('link_id', $id);
        $this->db->update('mench_ledger', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Log changes if successful:
        if ($affected_rows > 0 && $link_player > 0 && $link_type > 0) {

            if(strlen($link_text)==0){
                //Log modification transaction for every field changed:
                foreach($update_columns as $key => $value) {

                    if($before_data[0][$key]==$value){
                        continue;
                    }

                    //Now determine what type is this:
                    if($key=='link_privacy'){

                        $e___6186 = $this->config->item('e___6186'); //Interaction Privacy
                        $link_text .= view__db_field($key) . ' updated from [' . $e___6186[$before_data[0][$key]]['m__title'] . '] to [' . $e___6186[$value]['m__title'] . ']'."\n";

                    } elseif($key=='link_type'){

                        $e___4593 = $this->config->item('e___4593'); //Transaction Types
                        $link_text .= view__db_field($key) . ' updated from [' . $e___4593[$before_data[0][$key]]['m__title'] . '] to [' . $e___4593[$value]['m__title'] . ']'."\n";

                    } elseif(in_array($key, array('link_up', 'link_down'))) {

                        //Fetch new/old source names:
                        $befores = $this->Source_cache->fetch(array(
                            'e__id' => $before_data[0][$key],
                        ));
                        $after_e = $this->Source_cache->fetch(array(
                            'e__id' => $value,
                        ));

                        $link_text .= view__db_field($key) . ' updated from [' . $befores[0]['e__title'] . '] to [' . $after_e[0]['e__title'] . ']' . "\n";

                    } elseif(in_array($key, array('link_left', 'link_right'))) {

                        //Fetch new/old Idea outcomes:
                        $before_i = $this->Idea_cache->fetch(array(
                            'i__id' => $before_data[0][$key],
                        ));
                        $after_i = $this->Idea_cache->fetch(array(
                            'i__id' => $value,
                        ));

                        $link_text .= view__db_field($key) . ' updated from [' . $before_i[0]['i__message'] . '] to [' . $after_i[0]['i__message'] . ']' . "\n";

                    } elseif(in_array($key, array('link_text', 'link_number'))){

                        $link_text .= view__db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']'."\n";

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

            if(strlen($link_text) > 0 && count($fields_changed) > 0){
                //Value has changed, log transaction:
                $this->Mench_ledger->create(array(
                    'link_reference' => $id, //Transaction Reference
                    'link_player' => $link_player,
                    'link_type' => $link_type,
                    'link_text' => $link_text,
                    'link_metadata' => array(
                        'link_id' => $id,
                        'fields_changed' => $fields_changed,
                    ),
                    //Copy old values:
                    'link_up' => $before_data[0]['link_up'],
                    'link_down'  => $before_data[0]['link_down'],
                    'link_left' => $before_data[0]['link_left'],
                    'link_right'  => $before_data[0]['link_right'],
                ));
            }
        }

        return $affected_rows;
    }


    function x_update_instant_select($focus__id, $o__id, $element_id, $new_e__id, $migrate_s__handle, $link_id = 0) {


        //Authenticate Member:
        $migrate_s__handle = trim( substr($migrate_s__handle, 0, 1)=='@' ? trim(substr($migrate_s__handle, 1)) :  $migrate_s__handle);
        $migrate_s__handle = trim( substr($migrate_s__handle, 0, 1)=='#' ? trim(substr($migrate_s__handle, 1)) :  $migrate_s__handle);
        $player_e = superpower_unlocked();
        if (!$player_e) {
            return array(
                'status' => 0,
                'message' => view__unauthorized_message(),
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

        if($element_id==4486 && $link_id > 0){

            //IDEA LINK TYPE
            $status = $this->Mench_ledger->update($link_id, array(
                'link_type' => $new_e__id,
            ), $player_e['e__id'], 13962);

        } elseif($element_id==13550 && $link_id > 0){

            //SOURCE LINK TYPE
            $status = $this->Mench_ledger->update($link_id, array(
                'link_type' => $new_e__id,
            ), $player_e['e__id'], 28799);

        } elseif($element_id==32292 && $link_id > 0){

            //SOURCE/SOURCE LINK
            $status = $this->Mench_ledger->update($link_id, array(
                'link_type' => $new_e__id,
            ), $player_e['e__id'], 28799);


        } elseif($element_id==42795 && $o__id > 0 && $new_e__id && $player_e){

            if(!$link_id){
                //Double check database as it may be updating newly selected value:
                foreach($this->Mench_ledger->fetch(array(
                    'link_up' => $o__id,
                    'link_down' => $player_e['e__id'],
                    'link_type IN (' . join(',', $this->config->item('n___42795')) . ')' => null, //Follow
                    'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1) as $found_x){
                    $link_id = $found_x['link_id'];
                }
            }

            //Follow
            if($link_id > 0){
                //Updating reaction:
                if(in_array($new_e__id, $this->config->item('n___42850'))){
                    //Unsubscribe
                    $status = $this->Mench_ledger->update($link_id, array(
                        'link_privacy' => 6173, //Transaction Removed
                    ), $player_e['e__id'], 10673); //Media Removed
                } else {
                    $status = $this->Mench_ledger->update($link_id, array(
                        'link_type' => $new_e__id,
                    ), $player_e['e__id'], 42796);
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Mench_ledger->create(array(
                    'link_player' => $player_e['e__id'],
                    'link_up' => $o__id,
                    'link_down' => $player_e['e__id'],
                    'link_type' => $new_e__id,
                )));
            }

        } elseif($element_id==42260 && $o__id > 0 && $new_e__id && $player_e){

            //Check if current value?
            if(!$link_id){
                //Double check database as it may be updating newly selected value:
                foreach($this->Mench_ledger->fetch(array(
                    'link_up' => $player_e['e__id'],
                    'link_right' => $o__id,
                    'link_type IN (' . join(',', $this->config->item('n___42260')) . ')' => null, //Reactions
                    'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1) as $found_x){
                    $link_id = $found_x['link_id'];
                }
            }

            //Reactions...
            if($link_id > 0){
                if(in_array($new_e__id, $this->config->item('n___42850'))){
                    $status = $this->Mench_ledger->update($link_id, array(
                        'link_privacy' => 6173, //Transaction Removed
                    ), $player_e['e__id'], 10673); //Media Removed
                } else {
                    //Updating reaction:
                    $status = $this->Mench_ledger->update($link_id, array(
                        'link_type' => $new_e__id,
                    ), $player_e['e__id'], 42794);
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Mench_ledger->create(array(
                    'link_player' => $player_e['e__id'],
                    'link_up' => $player_e['e__id'],
                    'link_right' => $o__id,
                    'link_type' => $new_e__id,
                )));
            }

        } elseif($element_id==6177){

            //SOURCE ACCESS

            //Delete?
            if(!in_array($new_e__id, $this->config->item('n___7358'))){

                //Validate migration handle, if any:
                $migrate_s__id = 0;
                if(strlen($migrate_s__handle)>0){
                    $valid_handle = $this->Source_cache->fetch(array(
                        'LOWER(e__handle)' => strtolower($migrate_s__handle),
                        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                    ));
                    if(!count($valid_handle)){
                        return array(
                            'status' => 0,
                            'message' => '@'.$migrate_s__handle.' is an Invalid Source Handle!',
                        );
                    } elseif($valid_handle[0]['e__id']==$o__id){
                        return array(
                            'status' => 0,
                            'message' => 'You cannot migrate this source to itself! Choose a different source to migrate.',
                        );
                    }
                    $migrate_s__id = $valid_handle[0]['e__id'];
                }

                //Determine what to do after deleted:
                if($o__id==$focus__id){

                    //Find Published Followings:
                    foreach($this->Mench_ledger->fetch(array(
                        'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'link_privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                        'link_down' => $o__id,
                    ), array('link_up'), 1, 0, array('e__title' => 'DESC')) as $up_e) {
                        $deletion_redirect = view__memory(42903,42902).$up_e['e__handle'];
                    }

                    //If still not found, go to main page if no followings found:
                    if(!$deletion_redirect){
                        foreach($this->Source_cache->fetch(array('e__id' => $o__id)) as $e2){
                            $deletion_redirect = view__memory(42903,42902).e2['e__handle'];
                        }
                    }

                } else {

                    //Just delete from UI using JS:
                    $delete_element = '.s__12274_' . $o__id;

                }

                //Delete all transactions:
                $links_removed = $this->Source_cache->remove($o__id, $player_e['e__id'], $migrate_s__id);

            }

            //Update:
            $status = $this->Source_cache->update($o__id, array(
                'e__privacy' => $new_e__id,
            ), true, $player_e['e__id']);

            //Update Search Index:
            flag_for_search_indexing(12274,  $o__id);

        } elseif($element_id==31004){

            //IDEA ACCESS

            //Delete?
            if(!in_array($new_e__id, $this->config->item('n___31871'))){

                $migrate_s__id = 0;
                if(strlen($migrate_s__handle)>0){
                    $valid_hashtag = $this->Idea_cache->fetch(array(
                        'LOWER(i__hashtag)' => strtolower($migrate_s__handle),
                        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                    ));
                    if(!count($valid_hashtag)){
                        return array(
                            'status' => 0,
                            'message' => '#'.$migrate_s__handle.' is an Invalid Idea hashtag!',
                        );
                    } elseif($valid_hashtag[0]['i__id']==$o__id){
                        return array(
                            'status' => 0,
                            'message' => 'You cannot migrate this idea to itself! Choose a different idea to migrate.',
                        );
                    }
                    $migrate_s__id = $valid_hashtag[0]['i__id'];
                }

                //Determine what to do after deleted:
                if($o__id==$focus__id){

                    //Find Published Followings:
                    foreach($this->Mench_ledger->fetch(array(
                        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                        'link_type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //IDEA LINKS
                        'link_right' => $o__id,
                    ), array('link_left'), 1) as $previous_i) {
                        $deletion_redirect = view__memory(42903,33286).$previous_i['i__hashtag'];
                    }

                    //If not found, find active followings:
                    if(!$deletion_redirect){
                        foreach($this->Mench_ledger->fetch(array(
                            'link_privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                            'link_type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //IDEA LINKS
                            'link_right' => $o__id,
                        ), array('link_left'), 1) as $previous_i) {
                            $deletion_redirect = view__memory(42903,33286).$previous_i['i__hashtag'];
                        }
                    }

                    //If still not found, go to main page if no followings found:
                    if(!$deletion_redirect){
                        foreach($this->Idea_cache->fetch(array(
                            'i__id' => $o__id,
                        )) as $i){
                            $deletion_redirect = view__memory(42903,33286).$i['i__hashtag'];
                        }
                    }

                } else {

                    //Just delete from UI using JS:
                    $delete_element = '.s__12273_' . $o__id;

                }

                //Delete all transactions:
                $links_removed = $this->Idea_cache->remove($o__id , $player_e['e__id'], $migrate_s__id);

            }

            //Update Idea:
            $status = $this->Idea_cache->update($o__id, array(
                'i__privacy' => $new_e__id,
            ), true, $player_e['e__id']);

            //Update Search Index:
            flag_for_search_indexing(12273,  $o__id);

        } elseif($element_id==4737){

            //Source Reference
            $status = $this->Idea_cache->update($o__id, array(
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
                    $already_responded = count($this->Mench_ledger->fetch(array(
                        'link_up IN (' . join(',', $this->config->item('n___'.$dynamic_e__id)) . ')' => null, //All possible answers
                        'link_right' => $o__id,
                        'link_type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    )));

                } else {

                    $already_responded = count($this->Mench_ledger->fetch(array(
                        'link_up' => $dynamic_e__id,
                        'link_right' => $o__id,
                        'link_type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
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
    function send_dm($e__id, $subject, $html_message, $x_data = array(), $template_i__id = 0, $link_domain = 0, $log_tr = true, $demo_only = false)
    {

        $sms_subscriber = false;

        //Bypass notifications?
        if(!count($this->Mench_ledger->fetch(array(
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'link_type IN (' . join(',', $this->config->item('n___42256')) . ')' => null, //Writes
            'link_up' => 31779, //Mandatory Emails
            'link_right' => $template_i__id,
        )))){

            $notification_levels = $this->Mench_ledger->fetch(array(
                'link_up IN (' . join(',', $this->config->item('n___30820')) . ')' => null, //Active Subscriber
                'link_down' => $e__id,
                'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ));
            if (!count($notification_levels)) {
                return array(
                    'status' => 0,
                    'message' => 'User is not an active subscriber',
                );
            }
            $sms_subscriber = in_array($notification_levels[0]['link_up'], $this->config->item('n___28915'));
        }

        //Make sure not recently contacted:
        /*
         * Did not work with subscription notifications which could happen back to back...
         *
        $minutes_limit = 60;
        foreach($this->Mench_ledger->fetch(array(
            'link_type' => 29399,
            'link_player' => $e__id,
            'link_time >=' => date("Y-m-d H:i:s", strtotime('-'.$minutes_limit.' minutes')),
        )) as $recent_email){

            //Log Report:
            $this->Mench_ledger->create(array(
                'link_type' => 4246, //Platform Bug Reports
                'link_player' => $e__id,
                'link_up' => 29399,
                'link_text' => 'User was recently contacted less than '.$minutes_limit.' minutes ago.',
            ));

            return array(
                'status' => 0,
                'message' => 'User has been recently contacted',
            );

        }
        */

        $stats = array(
            'email_addresses' => array(),
            'phone_count' => 0,
        );


        //Send Emails:
        foreach($this->Mench_ledger->fetch(array(
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_up' => 3288, //Email
            'link_down' => $e__id,
        )) as $e_data){

            if(!filter_var($e_data['link_text'], FILTER_VALIDATE_EMAIL)){
                $this->Mench_ledger->update($e_data['link_id'], array(
                    'link_privacy' => 6173, //Transaction Deleted
                ), $e__id, 27890 /* Website Archive */);
                continue;
            }

            array_push($stats['email_addresses'], $e_data['link_text']);

        }

        if(count($stats['email_addresses']) > 0){
            //Send email:
            dispatch_email($stats['email_addresses'], $subject, $html_message, $e__id, $x_data, $template_i__id, $link_domain, $log_tr, $demo_only);
        }



        //Should we send SMS?
        $twilio_account_sid = website_setting(30859);
        $twilio_auth_token = website_setting(30860);
        $twilio_from_number = website_setting(27673);
        if($sms_subscriber && $twilio_account_sid && $twilio_auth_token && $twilio_from_number){

            //Yes, generate message
            $sms_message  = get_domain('m__title', $e__id, $link_domain).' Emailed ['.$subject.'] to '.join(' & ',$stats['email_addresses']).' (Also Check Spam)';

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n"," ",$sms_message);

            //Send SMS
            foreach($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'link_up' => 4783, //Phone
                'link_down' => $e__id,
            )) as $e_data){

                foreach(explode('|||',wordwrap($sms_message, view__memory(6404,27891), "|||")) as $single_message){

                    $sms_sent = dispatch_sms($e_data['link_text'], $single_message, $e__id, $x_data, $template_i__id, $link_domain, $log_tr, $demo_only);

                    if(!$sms_sent){
                        //bad number, remove it:
                        $this->Mench_ledger->update($e_data['link_id'], array(
                            'link_privacy' => 6173, //Transaction Deleted
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



    function send_i_mass_dm($list_of_e__id, $i, $link_domain = 0, $ensure_undiscovered = true, $demo_only = false){

        $total_sent = 0;
        $link_domain = ( $link_domain>0 ? $link_domain : ( isset($i['link_domain']) ? $i['link_domain'] : 0 ) );
        $subject_line = view__i_title($i, true);
        $wacth_repeat_handles = array();

        foreach($list_of_e__id as $count => $x) {

            if(in_array($x['e__handle'], $wacth_repeat_handles)){
                //This should not happen! Report bug:
                $this->Mench_ledger->create(array(
                    'link_type' => 4246, //Platform Bug Reports
                    'link_text' => 'send_i_mass_dm() Detected duplicate Source Handle Bug: '.$x['e__handle'],
                    'link_metadata' => array(
                        'list_of_e__id' => $list_of_e__id,
                        'i' => $i,
                        'link_domain' => $link_domain,
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
                $this->Mench_ledger->create(array(
                    'link_type' => 4246, //Platform Bug Reports
                    'link_text' => 'send_i_mass_dm() Invalid user row',
                    'link_metadata' => array(
                        '$i' => $i,
                        '$list_of_e__id' => $list_of_e__id,
                        '$x' => $x,
                    ),
                ));
                continue;
            } elseif($ensure_undiscovered && count($this->Mench_ledger->fetch(array(
                'link_left' => $i['i__id'],
                'link_player' => $x['e__id'],
                'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
                //Already discovered:
                continue;
            }


            $content_message = view__i__links($i, $x['e__id'], true); //Hide the show more content if any
            if(!(substr($subject_line, 0, 1)=='#' && !substr_count($subject_line, ' '))){
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">','</div>', $content_message);
            }

            //Append children as options:
            $html_message = '';
            foreach($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
                'link_left' => $i['i__id'],
            ), array('link_right'), 0, 0, array('link_number' => 'ASC')) as $down_or){
                //Has this user discovered this idea or no?
                $html_message .= '<div class="line">'.view__i_title($down_or, true).':</div>';
                $html_message .= '<div class="line">'.'https://'.get_domain('m__message', $x['e__id'], $link_domain).view__memory(42903,33286).$down_or['i__hashtag'].( i_startable($down_or) ? '/'.view__memory(6404,4235) : '' ).'?e__handle='.$x['e__handle'].'&e__time='.time().'&e__hash='.view__hash(time().$x['e__handle']).'</div>';
            }

            //Where to place the next step?
            if(substr_count($content_message, 'link_here')==1){
                //We have direction to place the next step somewhere specific:
                $content_message = str_replace('link_here', $html_message, $content_message);
            } else {
                $content_message = $content_message . $html_message;
            }

            $send_dm = $this->Mench_ledger->send_dm($x['e__id'], $subject_line, $content_message, array(
                'link_left' => $i['i__id'],
            ), $i['i__id'], $link_domain, true, $demo_only);

            //Mark as discovered:
            if($send_dm['status'] && !$demo_only){
                $this->Mench_ledger->mark_complete(43142, $x['e__id'], 0, $i);
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
        foreach($this->Mench_ledger->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'link_type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //Active Sequence Up
            'link_right' => $focus_i__id,
        ), array('link_left')) as $i_previous) {

            //Validate Selection:
            $input__selection = in_array($i_previous['i__type'], $this->config->item('n___7712'));
            $is_selected = count($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'link_left' => $i_previous['i__id'],
                'link_right' => $focus_i__id,
                'link_player' => $e__id,
            )));

            if($e__id>0 && !$is_selected && $input__selection){
                continue;
            }

            //Did we find it?
            if($i_previous['i__hashtag']==$target_i__hashtag){
                return array($i_previous);
            }

            //Keep looking further up:
            $website_finder = $this->Mench_ledger->find_previous($e__id, $target_i__hashtag, $i_previous['i__id'], $loop_breaker_ids);
            if(count($website_finder)){
                array_push($website_finder, $i_previous);
                return $website_finder;
            }
        }

        //Did not find any followings:
        return array();

    }




    function find_previous_discovered($focus_i__id, $link_player, $loop_breaker_ids = array()){

        /*
         *
         * Returns hashtag if discovered upwards
         *
         * */

        if(count($loop_breaker_ids)>0 && in_array($focus_i__id, $loop_breaker_ids)){
            return false;
        }
        array_push($loop_breaker_ids, intval($focus_i__id));

        foreach($this->Mench_ledger->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'link_type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //Active Sequence Up
            'link_right' => $focus_i__id,
        ), array('link_left')) as $prev_i){

            foreach($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'link_player' => $link_player,
                'link_left' => $prev_i['i__id'],
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ), array('link_right')) as $x){
                return $x['i__hashtag'];
            }

            return $this->Mench_ledger->find_previous_discovered($prev_i['i__id'], $link_player, $loop_breaker_ids);
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

        foreach ($this->Mench_ledger->fetch(array(
            'link_left' => $i['i__id'],
            'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('link_right'), 0, 0, array('link_number' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_i__id && !$found_trigger) {
                if ($next_i['i__id']==$find_after_i__id) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            $is_selected = count($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'link_left' => $i['i__id'],
                'link_right' => $next_i['i__id'],
                'link_player' => $e__id,
            )));
            if($input__selection && !$is_selected){
                continue;
            }


            //Return this if everything is completed, or if this is incomplete:
            if($target_completed || !count($this->Mench_ledger->fetch(array(
                    'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'link_player' => $e__id,
                    'link_left' => $next_i['i__id'],
                )))){
                return $next_i['i__hashtag'];
            }

            //Keep looking deeper:
            $next__url = $this->Mench_ledger->find_next($e__id, $target_i__hashtag, $next_i, 0, false, $target_completed, $loop_breaker_ids);
            if ($next__url) {
                return $next__url;
            }

        }


        if ($search_up && $target_i__hashtag!=$i['i__hashtag']) {
            //Check Previous/Up
            $current_previous = $i['i__id'];
            foreach (array_reverse($this->Mench_ledger->find_previous($e__id, $target_i__hashtag, $i['i__id'])) as $p_i) {
                //Find the next siblings:
                $next__url = $this->Mench_ledger->find_next($e__id, $target_i__hashtag, $p_i, $current_previous, false, $target_completed);
                if ($next__url) {
                    return $next__url;
                }
                $current_previous = $p_i['i__id'];
            }
        }

        //Nothing found:
        return null;

    }





    function mark_complete($link_type, $link_player, $target_i__id = 0, $i, $focus_i_data = array(), $x_data = array()) {

        if(!$link_player || !in_array($link_type, $this->config->item('n___31777'))){
            $this->Mench_ledger->create(array(
                'link_player' => $link_player,
                'link_type' => 4246, //Platform Bug Reports
                'link_text' => 'mark_complete() Invalid link_type @'.$link_type.' missing in @31777 OR Missing $link_player',
                'link_metadata' => array(
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
            $x_responses = $this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'link_type' => 33532, //Share Idea
                'link_left' => $i['i__id'],
                'link_player' => $link_player,
            ), array('link_right'), 0, 1, array('link_id' => 'DESC'));


            //All validated, lets create the new idea:
            if(strlen($focus_i_data['i__text']) || count($focus_i_data['uploaded_media'])){

                if(count($x_responses)){

                    //Update existing response if different:
                    if($focus_i_data['i__text']!=$x_responses[0]['i__message']){
                        $view_sync_links = view__sync_links($focus_i_data['i__text'], true, $x_responses[0]['i__id']);
                    }
                    $this_i__id = $x_responses[0]['i__id'];

                } else {

                    //Create a new response:
                    $i_new = $this->Idea_cache->create(array(
                        'i__message' => $focus_i_data['i__text'],
                        'i__type' => 6677, //Statement
                        'i__privacy' => 42625, //Private
                    ), $link_player);

                    $this_i__id = $i_new['i__id'];

                    //Link to this idea:
                    $this->Mench_ledger->create(array(
                        'link_type' => 33532, //REPLY
                        'link_player' => $link_player,
                        'link_left' => $i['i__id'],
                        'link_right' => $i_new['i__id'],
                    ));

                }

                //Process Media:
                $media_stats = process_media($this_i__id, $focus_i_data['uploaded_media']);

            } elseif (count($x_responses)){

                //Delete Links
                $links_removed = $this->Idea_cache->remove($x_responses[0]['i__id'] , $link_player);

                //Delete Idea:
                $this->Idea_cache->update($x_responses[0]['i__id'], array(
                    'i__privacy' => 6182, //Deleted Idea
                ), true, $link_player);

            }

        }

        $x_data['link_player'] = $link_player;
        $x_data['link_type'] = $link_type;
        $x_data['link_left'] = $i['i__id'];

        //Always add Idea to link_left
        if($target_i__id>0 && (!isset($x_data['link_right']) || !intval($x_data['link_right']))){
            $x_data['link_right'] = $target_i__id;
        }

        if (!isset($x_data['link_text'])) {
            $x_data['link_text'] = null;
        }

        $es_creator = $this->Source_cache->fetch(array(
            'e__id' => $link_player,
        ));

        //Make sure not duplicate:
        foreach($this->Mench_ledger->fetch(array(
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            //'link_type NOT IN (' . join(',', $this->config->item('n___31776')) . ')' => null, //Unremovable Discoveries
            'link_left' => ( isset($x_data['link_left']) ? $x_data['link_left'] : 0 ),
            'link_right' => ( isset($x_data['link_right']) ? $x_data['link_right'] : 0 ),
            'link_player' => $link_player,
            'link_text' => $x_data['link_text'],
        )) as $already_discovered){
            //Already discovered! Return this:
            return array(
                'status' => 1,
                'message' => 'Already Discovered',
                'new_x' => $already_discovered,
            );
        }

        //Add new transaction:
        $domain_url = get_domain('m__message', $link_player);
        $new_x = $this->Mench_ledger->create($x_data);

        //Auto Complete OR Answers:
        if($input__selection){
            foreach($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'link_player' => $x_data['link_player'],
                'link_left' => $i['i__id'],
            ), array('link_right'), 0) as $next_i){
                if(!in_array($next_i['i__type'], $this->config->item('n___43039')) && !count($this->Mench_ledger->fetch(array(
                        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'i__privacy IN (' . join(',', $this->config->item('n___42948')) . ')' => null, //Public Ideas
                        'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //IDEA LINKS
                        'link_left' => $next_i['i__id'],
                    ), array('link_right'), 0, 0))){
                    //Mark as complete:
                    $this->Mench_ledger->mark_complete(i__discovery_link($next_i), $x_data['link_player'], $target_i__id, $next_i, $x_data);
                }
            }
        }

        if ($x_data['link_player'] && in_array($x_data['link_type'], $this->config->item('n___40986'))) {

            //Discovery Triggers?
            $clone_urls = '';
            foreach($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'link_type IN (' . join(',', $this->config->item('n___32275')) . ')' => null, //DISCOVERY TRIGGERS
                'link_left' => $i['i__id'],
            ), array('link_right'), 0, 0, array('link_number' => 'ASC')) as $clone_i){

                if($clone_i['link_type']==32247){

                    //Discovery Clone
                    $new_title = $es_creator[0]['e__title'].' '.$clone_i['i__message'];
                    $result = $this->Idea_cache->recursive_clone($clone_i['i__id'], 0, $x_data['link_player'], null, $new_title);
                    if($result['status']){

                        //Add as watcher:
                        $this->Mench_ledger->create(array(
                            'link_type' => 10573, //WATCHERS
                            'link_player' => $x_data['link_player'],
                            'link_up' => $x_data['link_player'],
                            'link_right' => $result['new_i__id'],
                        ));

                        //New link:
                        $clone_urls .= $new_title.':'."\n".'https://'.get_domain('m__message', $x_data['link_player']).view__memory(42903,33286).$result['new_i__hashtag']."\n\n";
                    }

                } elseif($clone_i['link_type']==32304){

                    //Discovery Forget: Remove all Discoveries made by this user:
                    foreach($this->Mench_ledger->fetch(array(
                        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'link_type IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //EXPANDED DISCOVERIES
                        'link_left' => $i['i__id'],
                        'link_player' => $x_data['link_player'],
                    )) as $remove_x){
                        $this->Mench_ledger->update($remove_x['link_id'], array(
                            'link_privacy' => 6173, //Remove this discovery
                        ), $x_data['link_player'], 29431 /* Play Auto Removed */);
                    }

                }

            }

            if(strlen($clone_urls)){
                //Send DM with all the new clone idea URLs:
                $clone_urls = $clone_urls.'You have been added as a subscriber so you will be notified when anyone start using your link.';
                $i_title = view__i_title($i, true);
                $this->Mench_ledger->send_dm($x_data['link_player'], $i_title , $clone_urls);
                //Also DM all watchers of the idea:
                foreach($this->Mench_ledger->fetch(array(
                    'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'link_type' => 10573, //WATCHERS
                    'link_right' => $i['i__id'],
                ), array(), 0) as $watcher){
                    $this->Mench_ledger->send_dm($watcher['link_up'], $i_title, $clone_urls);
                }
            }



            //ADD PROFILE?
            foreach($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type' => 7545, //Following Add
                'link_right' => $i['i__id'],
            ), array('link_up')) as $this_tag){

                //Check if special profile add?
                if(in_array($this_tag['link_up'], $this->config->item('n___43048'))){

                    //Special Addition:

                    if($this_tag['link_up']==6197 && strlen(trim($x_data['link_text']))>=2){

                        //Update Source Title:
                        $this->Source_cache->update($x_data['link_player'], array(
                            'e__title' => $x_data['link_text'],
                        ), true, $x_data['link_player']);

                        //Update live session as well:
                        $es_creator[0]['e__title'] = $x_data['link_text'];
                        $this->Source_cache->activate_session($es_creator[0], true);

                    } elseif($this_tag['link_up']==6198 && isset($media_stats['media_e__cover']) && filter_var($media_stats['media_e__cover'], FILTER_VALIDATE_URL)){

                        //Update Source Cover:
                        //Update profile picture for current user:
                        $this->Source_cache->update($link_player, array(
                            'e__cover' => $media_stats['media_e__cover'],
                        ), true, $link_player);

                        //Update live session as well:
                        $es_creator[0]['e__cover'] = $media_stats['media_e__cover'];
                        $this->Source_cache->activate_session($es_creator[0], true);

                    }

                } else {

                    //Assign tag if following/follower transaction NOT previously assigned:
                    $append_source = append_source($this_tag['link_up'], $x_data['link_player'], ( isset($focus_i_data['i__text']) ? $focus_i_data['i__text'] : null ), $i['i__id']);

                    //See if Session needs to be updated:
                    $player_e = superpower_unlocked();
                    if($player_e && $player_e['e__id']==$x_data['link_player'] && $append_source){
                        $this->Source_cache->activate_session($es_creator[0], true);
                    }

                }
            }


            //REMOVE PROFILE?
            foreach($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type' => 26599, //Following Remove
                'link_right' => $i['i__id'],
            )) as $this_tag){

                //Remove Following IF previously assigned:
                foreach($this->Mench_ledger->fetch(array(
                    'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'link_up' => $this_tag['link_up'], //CERTIFICATES saved here
                    'link_down' => $x_data['link_player'],
                )) as $existing_x){

                    $this->Mench_ledger->update($existing_x['link_id'], array(
                        'link_privacy' => 6173,
                    ), $x_data['link_player'], 5982 /* Following Removed */);

                    //See if Session needs to be updated:
                    if($player_e && $player_e['e__id']==$x_data['link_player']){
                        //Yes, update session:
                        $this->Source_cache->activate_session($es_creator[0], true);
                    }
                }
            }


            //Notify watchers IF any:
            $watchers = $this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type' => 10573, //WATCHERS
                'link_right' => $i['i__id'],
            ), array(), 0);
            if(count($watchers)){

                $es_discoverer = $this->Source_cache->fetch(array(
                    'e__id' => $x_data['link_player'],
                ));
                if(count($es_discoverer)){

                    //Fetch Discoverer contact:
                    $discoverer_contact = '';
                    foreach($this->config->item('e___34541') as $link_type => $m) {
                        foreach($this->Mench_ledger->fetch(array(
                            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'link_down' => $x_data['link_player'],
                            'link_up' => $link_type,
                            'LENGTH(link_text)>0' => null,
                        )) as $x_progress){
                            $discoverer_contact .= $m['m__title'].':'."\n".$x_progress['link_text']."\n\n";
                        }
                    }

                    //Notify Idea Watchers
                    $sent_watchers = array();
                    foreach($watchers as $watcher){
                        if(!in_array(intval($watcher['link_up']), $sent_watchers)){
                            array_push($sent_watchers, intval($watcher['link_up']));

                            $this->Mench_ledger->send_dm($watcher['link_up'], $es_discoverer[0]['e__title'].' Discovered: '.view__i_title($i, true),
                                //Message Body:
                                view__i_title($i, true).':'."\n".'https://'.$domain_url.view__memory(42903,33286).$i['i__hashtag']."\n\n".
                                ( strlen($x_data['link_text']) ? $x_data['link_text']."\n\n" : '' ).
                                $es_discoverer[0]['e__title'].':'."\n".'https://'.$domain_url.view__memory(42903,42902).$es_discoverer[0]['e__handle']."\n\n".
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


    function tree_full_history($i, $e__id, $i__level = 0){

        unset($i['i__weight']);
        unset($i['i__external']);
        unset($i['i__privacy']);
        unset($i['i__cache']);
        unset($i['link_type']);
        unset($i['link_up']);
        unset($i['link_down']);
        unset($i['link_number']);
        unset($i['link_metadata']);
        unset($i['link_privacy']);
        unset($i['link_domain']);
        unset($i['link_void']);
        unset($i['link_player']);
        unset($i['link_left']);
        unset($i['link_right']);
        unset($i['link_id']);
        unset($i['link_text']);
        unset($i['link_reference']);

        $input__selection = in_array($i['i__type'], $this->config->item('n___7712'));
        $input__text = in_array($i['i__type'], $this->config->item('n___43002'));
        $i['uploaded_media'] = array();
        $i['user_discovered'] = array();
        $i['user_written_response'] = array();
        $i['i__level'] = $i__level;
        $i['i__next'] = array();
        $i__level++;
        
        //Append media if any:
        foreach($this->Mench_ledger->fetch(array(
            'link_type IN (' . join(',', $this->config->item('n___42294')) . ')' => null, //Media
            'link_right' => $i['i__id'],
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
        ), array('link_up'), 0, 0, array('link_number' => 'ASC')) as $media){

            //Get metadata:
            foreach($this->Mench_ledger->fetch(array(
                'link_up IN (' . join(',', $this->config->item('n___44393')) . ')' => null, //Media JSON
                'link_down' => $media['e__id'],
                'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'link_privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ), array('link_up'), 0) as $e_group) {
                if(strlen($e_group['link_text'])){
                    $media[$e_group['e__handle']] = $e_group['link_text'];
                }
            }

            unset($media['link_time']);
            unset($media['link_up']);
            unset($media['link_down']);
            unset($media['link_number']);
            unset($media['link_metadata']);
            unset($media['link_privacy']);
            unset($media['link_domain']);
            unset($media['link_void']);
            unset($media['link_player']);
            unset($media['link_left']);
            unset($media['link_right']);
            unset($media['link_id']);
            unset($media['link_reference']);
            unset($media['link_text']);
            unset($media['e__id']);
            unset($media['e__title']);
            unset($media['e__handle']);
            unset($media['e__privacy']);
            unset($media['e__weight']);
            unset($media['e__external']);
            unset($media['e__cache']);
            array_push($i['uploaded_media'], $media);
        }

        //Append Discovery if any:
        foreach($this->Mench_ledger->fetch(array(
            'link_left' => $i['i__id'],
            'link_player' => $e__id,
            'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 1) as $x){

            unset($x['link_type']);
            unset($x['link_up']);
            unset($x['link_down']);
            unset($x['link_number']);
            unset($x['link_metadata']);
            unset($x['link_privacy']);
            unset($x['link_domain']);
            unset($x['link_void']);
            unset($x['link_reference']);
            unset($x['link_left']);
            unset($x['link_right']);
            unset($x['link_player']);
            unset($x['link_text']);
            unset($x['link_id']);

            $i['user_discovered'] = $x;

            if($input__text){
                //Since it has been discovered and its a text input, lots fetch the written response:
                foreach($this->Mench_ledger->fetch(array(
                    'link_privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                    'link_type' => 33532, //Share Idea
                    'link_left' => $i['i__id'],
                    'link_player' => $e__id,
                    'LENGTH(i__message) > 0' => null,
                ), array('link_right'), 0, 1, array('link_id' => 'DESC')) as $response){
                    $i['user_written_response'] = $response;
                }
            }
        }


        if($i['user_discovered']){
            foreach($this->Mench_ledger->fetch(array(
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
                'link_left' => $i['i__id'],
            ), array('link_right'), 0, 0, array('link_number' => 'ASC')) as $next_i){
                array_push($i['i__next'], $this->Mench_ledger->tree_full_history($next_i, $e__id, $i__level));
            }
        }


        return $i;

    }

    function tree_discovered_history($i, $e__id, $i__level = 0){

        $input__selection = in_array($i['i__type'], $this->config->item('n___7712'));
        $input__text = in_array($i['i__type'], $this->config->item('n___43002'));
        $i['i__level'] = $i__level;
        $i['i__next'] = array();
        $i['user_discovered'] = array();
        $i['user_written_response'] = array();
        $i__level++;

        //Append Discovery if any:
        foreach($this->Mench_ledger->fetch(array(
            'link_left' => $i['i__id'],
            'link_player' => $e__id,
            'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 1) as $x){
            $i['user_discovered'] = $x;
        }

        if($input__text){
            foreach($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'link_type' => 33532, //Share Idea
                'link_left' => $i['i__id'],
                'link_player' => $e__id,
                'LENGTH(i__message) > 0' => null,
            ), array('link_right'), 0, 1, array('link_id' => 'DESC')) as $response){
                $i['user_written_response'] = $response;
            }
        }


        if($i['user_discovered']){
            foreach(( $input__selection ? $this->Mench_ledger->fetch(array(
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type' => 7712, //Input Choice
                'link_player' => $e__id,
                'link_left' => $i['i__id'],
            ), array('link_right')) : $this->Mench_ledger->fetch(array(
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
                'link_left' => $i['i__id'],
            ), array('link_right'), 0, 0, array('link_number' => 'ASC')) ) as $next_i){
                array_push($i['i__next'], $this->Mench_ledger->tree_discovered_history($next_i, $e__id, $i__level));
            }
        }


        return $i;

    }

    function tree_doc($i, $i__level = 0){

        $i['i__level'] = $i__level;
        $i__level++;
        $input__selection = in_array($i['i__type'], $this->config->item('n___7712'));
        $single_choice = in_array($i['i__type'], $this->config->item('n___33331'));
        $is_required = count($this->Mench_ledger->fetch(array(
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
            'link_right' => $i['i__id'],
            'link_up' => 28239, //Required
        )));
        $total_next = $this->Mench_ledger->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'link_left' => $i['i__id'],
        ), array('link_right'), 0, 0, array('link_number' => 'ASC'));

        $i['stats'] = array(
            'max_level' => $i__level,
            'max_steps' => ( $input__selection ? ( $single_choice ? 1 : count($total_next) ) : count($total_next) ),
            'min_steps' => ( $input__selection ? ( $is_required ? 1 : 0 ) : count($total_next) ), //Can be improved later...
            'or_steps' => ( $input__selection && count($total_next) ? 1 : 0 ),
        );
        $i['i__next'] = array();

        //Append Total Discoveries if any:
        $sub_counter = $this->Mench_ledger->fetch(array(
            'link_left' => $i['i__id'],
            'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0, 0, array(), 'COUNT(link_id) as totals');
        $i['i__count_discovery'] = $sub_counter[0]['totals'];



        foreach($total_next as $next_i){

            $result_i = $this->Mench_ledger->tree_doc($next_i, $i__level);
            array_push($i['i__next'], $result_i);

            if($result_i['stats']['max_level']>$i['stats']['max_level']){
                $i['stats']['max_level'] = $result_i['stats']['max_level'];
            }

            $i['stats']['max_steps'] += $result_i['stats']['max_steps'];
            if(!$input__selection || $is_required){
                $i['stats']['min_steps'] += $result_i['stats']['min_steps'];
            }
            $i['stats']['or_steps'] += $result_i['stats']['or_steps'];

        }

        return $i;

    }


    function tree_progress($e__id, $i, $i__level = 0, $loop_breaker_ids = array())
    {

        if(count($loop_breaker_ids)>0 && in_array($i['i__id'], $loop_breaker_ids)){
            return false;
        }

        $recursive_down_ids = $this->Idea_cache->recursive_down_ids($i, 'AND');
        if(!isset($recursive_down_ids['recursive_i_ids']) || !count($recursive_down_ids['recursive_i_ids'])){
            return false;
        }

        $i__level++;
        array_push($loop_breaker_ids, intval($i['i__id']));

        //Count completed:
        $list_discovered = array();
        foreach($this->Mench_ledger->fetch(array(
            'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'link_player' => $e__id, //Belongs to this Member
            'link_left IN (' . join(',', $recursive_down_ids['recursive_i_ids'] ) . ')' => null,
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('link_left'), 0) as $completed){
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
            foreach($this->Mench_ledger->fetch(array(
                'link_type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'link_player' => $e__id, //Belongs to this Member
                'link_left IN (' . join(',', $recursive_down_ids['recursive_i_ids'] ) . ')' => null,
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ), array('link_right')) as $expansion_in) {

                //Fetch recursive:
                $tree_progress = $this->Mench_ledger->tree_progress($e__id, $expansion_in, $i__level, $loop_breaker_ids);

                if(!$tree_progress && !count($this->Mench_ledger->fetch(array(
                        'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'link_player' => $e__id, //Belongs to this Member
                        'link_left' => $expansion_in['i__id'],
                        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
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

        if($i__level==1){

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
        return count($this->Mench_ledger->fetch(array(
            'link_left = link_right' => NULL,
            'LOWER(i__hashtag)' => strtolower($i__hashtag),
            'link_player' => $e__id,
            'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array('link_right')));
    }




}