<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Source_cache extends CIdea_cache
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


    function activate_subscription($e__id, $link_domain = 0){


        //Remove from Anonymous:
        foreach($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_up IN (' . join(',', $this->config->item('n___32540')) . ')' => null, //Unsubscribers
            'link_down' => $e__id,
        )) as $unsubscriber_x){
            $this->Mench_ledger->update($unsubscriber_x['link_id'], array(), $e__id);
        }

        $session_data = $this->session->all_userdata();
        $this->session->set_userdata($session_data);


        //Add to Subscriber:
        $this->Mench_ledger->create(array(
            'link_up' => 4430, //Subscriber
            'link_type' => 4230,
            'link_player' => $e__id,
            'link_down' => $e__id,
            'link_domain' => $link_domain,
        ));



    }


    function activate_session($e, $update_session = false, $is_cookie = false){

        //PROFILE
        $session_data = array(
            'session_up' => $e,
            'session_up_ids' => array(),
            'session_superpowers_unlocked' => array(),
        );

        //Make sure they also belong to this website's members:
        $this->Source_cache->add_regular_e(website_setting(0), $e['e__id']);


        //Check & Adjust their subscription, IF needed:
        //Remove their subscribe:
        $resubscribed = 0;
        foreach($this->Mench_ledger->fetch(array(
            'link_up IN (' . join(',', $this->config->item('n___29648')) . ')' => null, //Unsubscribers
            'link_down' => $e['e__id'],
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        )) as $unsubscribe){
            $resubscribed += $this->Mench_ledger->update($unsubscribe['link_id'], array(), $e['e__id']);
        }
        if($resubscribed > 0){
            //Add Back to Subscribers:
            $this->Mench_ledger->create(array(
                'link_type' => 4230,
                'link_up' => 4430, //Active Member
                'link_player' => $e['e__id'],
                'link_down' => $e['e__id'],
            ));
        }



        if(!$update_session && !$is_cookie){
            //Create Cookie:
            $cookie_time = time();
            $cookie_val = $e['e__id'].'ABCEFG'.$cookie_time.'ABCEFG'.view__hash($e['e__id'].$cookie_time);
            setcookie('auth_cookie', $cookie_val, ($cookie_time + ( 86400 * view__memory(6404,14031))), "/");
        }



        //Fetch Platform Defaults:
        $platform_theme = array();
        foreach($this->Mench_ledger->fetch(array(
            'link_up IN (' . join(',', $this->config->item('n___14926')) . ')' => null, //Website Theme Items
            'link_down' => 6404, //Platform Default
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        ), array(), 0) as $x) {
            array_push($platform_theme, intval($x['link_up']));
        }

        //Fetch Website Defaults:
        $website_theme = array();
        foreach($this->Mench_ledger->fetch(array(
            'link_up IN (' . join(',', $this->config->item('n___14926')) . ')' => null, //Website Theme Items
            'link_down' => website_setting(0), //Website ID
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        ), array(), 0) as $x) {
            array_push($website_theme, intval($x['link_up']));
        }


        //Fetch User Defaults:
        $user_theme = array();
        foreach($this->Mench_ledger->fetch(array(
            'link_down' => $e['e__id'], //This follower source
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        ), array('link_up'), 0) as $e_up){

            //Push to followings IDs:
            array_push($session_data['session_up_ids'], intval($e_up['e__id']));

            //Website Theme Items?
            if(in_array($e_up['e__id'], $this->config->item('n___14926'))){
                array_push($user_theme, intval($e_up['e__id']));
            }

            //Superpower?
            if(in_array($e_up['e__id'], $this->config->item('n___10957'))){

                //It's unlocked!
                array_push($session_data['session_superpowers_unlocked'], intval($e_up['e__id']));
            }
        }


        //Determine Defaults if missing any of the CUSTOM UI
        foreach($this->config->item('e___13890') as $e__id => $m){

            //Set Default:
            $session_data['session_custom_ui_'.$e__id] = 0;

            //First try to find User Theme, if any:
            if(!$session_data['session_custom_ui_'.$e__id]){
                foreach($this->config->item('e___'.$e__id) as $e__id2 => $m2){
                    if(in_array($e__id2, $user_theme )){
                        $session_data['session_custom_ui_'.$e__id] = $e__id2;
                        break;
                    }
                }
            }

            //Then try to find Website Theme, if any:
            if(!$session_data['session_custom_ui_'.$e__id]){
                foreach($this->config->item('e___'.$e__id) as $e__id2 => $m2){
                    if(in_array($e__id2, $website_theme )){
                        $session_data['session_custom_ui_'.$e__id] = $e__id2;
                        break;
                    }
                }
            }


            //Finally try Platform Theme:
            if(!$session_data['session_custom_ui_'.$e__id]){
                //First try to find Website Default, if any:
                foreach($this->config->item('e___'.$e__id) as $e__id2 => $m2){
                    if(in_array($e__id2, $platform_theme )){
                        $session_data['session_custom_ui_'.$e__id] = $e__id2;
                        break;
                    }
                }
            }
        }


        //SESSION
        $this->session->set_userdata($session_data);


        //Resubscribe IF they are Permanently Unsubscribed:
        /*
        $unsubscribed_time = null;
        foreach($this->Mench_ledger->fetch(array(
            'link_up IN (' . join(',', $this->config->item('n___31057')) . ')' => null, //Permanently Unsubscribed
            'link_down' => $e['e__id'], //This follower source
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        ), array(), 0) as $unsubscribed){
            $unsubscribed_time = $unsubscribed['link_time'];
            $this->Mench_ledger->update($unsubscribed['link_id'], array(), $e['e__id']); //Resubscribe
        }
        if($unsubscribed_time){
            //Add to subscribed again:
            $this->Mench_ledger->create(array(
                'link_type' => 4230,
                'link_up' => 4430, //Active Member
                'link_player' => $e['e__id'],
                'link_down' => $e['e__id'],
            ));
            $this->session->set_flashdata('flash_message', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-user-check"></i></span>Welcome Back! You Have Been Re-Subscribed :)</div>');
        }
        */

        return $session_data;

    }


    function add_regular_e($link_up, $link_down, $link_text = null) {
        //Add if link not already there:
        if(!count($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_up' => $link_up,
            'link_down' => $link_down,
            'link_text' => $link_text,
        )))){
            $this->Mench_ledger->create(array(
                'link_player' => $link_down, //Belongs to this Member
                'link_type' => 4230,
                'link_text' => $link_text,
                'link_up' => $link_up,
                'link_down' => $link_down,
            ));
        }
    }

    function scissor_e($link_up, $sub_id){

        $all_results = $this->Mench_ledger->fetch(array(
            'link_up' => $link_up,
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        ), array('link_down'), 0, 0, sort__e());

        //Remove if not in the secondary group:
        foreach($all_results as $key => $primary_list){
            if(!count($this->Mench_ledger->fetch(array(
                'link_up' => $sub_id,
                'link_down' => $primary_list['e__id'],
                'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'link_void' => 0, //Not Void
            ), array(), 0))){
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }

    function scissor_i($link_up, $sub_id){

        $all_results = $this->Mench_ledger->fetch(array(
            'link_up' => $link_up,
            'link_type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'link_void' => 0, //Not Void
        ), array('link_right'), 0, 0, array('link_number' => 'ASC'));

        //Remove if not in the secondary group:
        foreach($all_results as $key => $primary_list){
            if(!count($this->Mench_ledger->fetch(array(
                'link_up' => $sub_id,
                'link_right' => $primary_list['i__id'],
                'link_type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'link_void' => 0, //Not Void
            )))){
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }



    function add_member($full_name, $email = null, $phone_number = null, $image_url = null, $link_domain = 0){

        //Set website if not set:
        if(!$link_domain){
            $link_domain = website_setting(0);
        }

        //All good, create new source:
        $new_private_users = in_array($link_domain, $this->config->item('n___44011'));
        $added_e = $this->Source_cache->create($full_name, 0, ( $image_url ? $image_url : random_cover(12279) ));
        if(!$added_e['status']){
            //We had an error, return it:
            return $added_e;
        } elseif($email && !filter_var($email, FILTER_VALIDATE_EMAIL)){
            return array(
                'status' => 0,
                'message' => 'Invalid Email',
            );
        } elseif($phone_number && (!intval($phone_number) || strlen($phone_number)<7)){
            return array(
                'status' => 0,
                'message' => 'Invalid Phone',
            );
        }

        //Add email?
        if($email){
            $this->Mench_ledger->create(array(
                'link_type' => 4230,
                'link_text' => trim(strtolower($email)),
                'link_up' => 3288, //Email
                'link_player' => $added_e['new_e']['e__id'],
                'link_down' => $added_e['new_e']['e__id'],
                'link_domain' => $link_domain,
            ));
        }

        //Add Number?
        if($phone_number){
            $this->Mench_ledger->create(array(
                'link_up' => 4783, //Phone
                'link_type' => 4230,
                'link_text' => $phone_number,
                'link_player' => $added_e['new_e']['e__id'],
                'link_down' => $added_e['new_e']['e__id'],
                'link_domain' => $link_domain,
            ));
        }

        if($email || $phone_number){

            $this->Source_cache->activate_subscription( $added_e['new_e']['e__id'], $link_domain );

        } else {

            //Add to anonymous:
            $this->Mench_ledger->create(array(
                'link_up' => 14938, //Guest Login
                'link_type' => 4230,
                'link_player' => $added_e['new_e']['e__id'],
                'link_down' => $added_e['new_e']['e__id'],
                'link_domain' => $link_domain,
            ));

            //Assign session key:
            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

        }


        //Add member to Domain Member Group(s):
        $this->Source_cache->add_regular_e($link_domain, $added_e['new_e']['e__id']);


        //Send Welcome Email if any:
        if($email){
            foreach($this->Mench_ledger->fetch(array(
                'link_void' => 0, //Not Void
                'link_type' => 33600, //Draft
                'link_up' => 14929, //Website Welcome Email Templates
            ), array('link_right'), 0) as $i){
                if(count($this->Mench_ledger->fetch(array(
                    'link_void' => 0, //Not Void
                    'link_type' => 33600, //Draft
                    'link_up' => $link_domain, //for Current website
                    'link_right' => $i['i__id'], //Is this the template?
                )))){
                    //Found the email template to send:
                    $total_sent = $this->Mench_ledger->send_i_mass_dm(array($added_e['new_e']), $i, $link_domain);
                    break; //Just the first template match
                }
            }
        }

        //Update Search Index:
        flag_for_search_indexing(12274,  $added_e['new_e']['e__id']);

        //Assign session & log login transaction:
        $this->Source_cache->activate_session($added_e['new_e']);


        //Return Member:
        return array(
            'status' => 1,
            'e' => $added_e['new_e'],
        );

    }

    function fetch($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array('e__id' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target sources:
        $this->db->select($select);
        $this->db->from('cache_sources');
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


        //Make sure user has access to each item:
        if($select=='*' && 0){
            foreach($results as $key => $value){
                if(!access_level_e(null, $value['e__id'], $value)){
                    unset($results[$key]); //Remove this option
                }
            }
        }

        return $results;

    }

    function fetch_recursive($link_type, $e__id, $include_any_e = array(), $exclude_all_e= array(), $hard_level = 3, $hard_limit = 100, $s__level = 0){

        $flat_items = array();
        $s__level++;

        if(in_array($link_type, $this->config->item('n___42276'))){

            //Up Source Link Groups:
            $order_columns = array('link_type = \'41011\' DESC' => null, 'link_number' => 'ASC', 'link_time' => 'DESC');
            $joins_objects = array('link_up');
            $query_filters = array(
                'link_down' => $e__id,
                'link_type IN (' . join(',', $this->config->item('n___'.$link_type)) . ')' => null, //SOURCE LINKS
                'link_void' => 0, //Not Void
            );

        } elseif(in_array($link_type, $this->config->item('n___42377'))){

            //Down Source Link Groups:
            $order_columns = array('link_type = \'41011\' DESC' => null, 'link_number' => 'ASC', 'link_time' => 'DESC');
            $joins_objects = array('link_down');
            $query_filters = array(
                'link_up' => $e__id,
                'link_type IN (' . join(',', $this->config->item('n___'.$link_type)) . ')' => null, //SOURCE LINKS
                'link_void' => 0, //Not Void
            );

        } else {

            return false;

        }


        foreach($this->Mench_ledger->fetch($query_filters, $joins_objects, 0, 0, $order_columns) as $e_down) {

            //Filter Sources, if needed:
            $qualified_e = true;
            if(count($include_any_e) && !count($this->Mench_ledger->fetch(array(
                    'link_up IN (' . join(',', $include_any_e) . ')' => null,
                    'link_down' => $e_down['e__id'],
                    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'link_void' => 0, //Not Void
                )))){
                //Must include all sources, skip:
                $qualified_e = false;
            }
            if(count($exclude_all_e) && count($this->Mench_ledger->fetch(array(
                    'link_up IN (' . join(',', $exclude_all_e) . ')' => null,
                    'link_down' => $e_down['e__id'],
                    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'link_void' => 0, //Not Void
                )))){
                //Must Exclude If Has ALL sources, skip:
                $qualified_e = false;
            }


            //Is this a new matching source?
            if($qualified_e && !isset($flat_items[$e_down['e__id']])){
                $e_down['s__level'] = $s__level;
                $e_down['s__count'] = count($flat_items)+1;
                $flat_items[$e_down['e__id']] = $e_down;
            }

            //Do we have more followers?
            if($s__level>=$hard_level || count($flat_items)>=$hard_limit){
                break;
            }

            foreach($this->Source_cache->fetch_recursive($link_type, $e_down['e__id'], $include_any_e, $exclude_all_e, $hard_level, $hard_limit, $s__level) as $e_recursive_down){
                if(!isset($flat_items[$e_recursive_down['e__id']])){
                    $e_recursive_down['s__count'] = count($flat_items)+1;
                    $flat_items[$e_recursive_down['e__id']] = $e_recursive_down;
                }
            }
        }

        return $flat_items;
    }

    function update($id, $update_columns, $external_sync = false)
    {
        if (count($update_columns)==0) {
            return false;
        }
        //Update:
        $this->db->where('e__id', intval($id));
        $this->db->update('cache_sources', $update_columns);
        $affected_rows = $this->db->affected_rows();
        if($affected_rows && $external_sync){
            //Sync algolia:
            flag_for_search_indexing(12274, intval($id));
        }
        return $affected_rows;
    }


    function radio_set($e_up_bucket_id, $set_e_down_id, $link_player)
    {

        /*
         * Treats an source follower group as a drop down menu where:
         *
         *  $e_up_bucket_id is the followings of the drop down
         *  $link_player is the member source ID that one of the followers of $e_up_bucket_id should be assigned (like a drop down)
         *  $set_e_down_id is the new value to be assigned, which could also be null (meaning just delete all current values)
         *
         * This function is helpful to manage things like Member communication levels
         *
         * */


        //Fetch all the follower sources for $e_up_bucket_id and make sure they match $set_e_down_id
        $followers = $this->config->item('n___' . $e_up_bucket_id);
        if ($e_up_bucket_id < 1) {
            return false;
        } elseif (!$followers) {
            return false;
        } elseif ($set_e_down_id > 0 && !in_array($set_e_down_id, $followers)) {
            return false;
        }

        //First delete existing following/follower transactions for this drop down:
        $previously_assigned = ($set_e_down_id < 1);
        $x_update_id = 0;
        foreach($this->Mench_ledger->fetch(array(
            'link_down' => $link_player,
            'link_up IN (' . join(',', $followers) . ')' => null, //Current followers
            'link_void' => 0, //Not Void
        ), array(), view__memory(6404,11064)) as $x) {

            if (!$previously_assigned && $x['link_up']==$set_e_down_id) {
                $previously_assigned = true;
            } else {
                //Delete assignment:
                $x_update_id = $x['link_id'];

                //Do not log update transaction here as we would log it further below:
                $this->Mench_ledger->update($x['link_id'], array(), $link_player);
            }

        }


        //Make sure $set_e_down_id belongs to followings if set (Could be null which means delete all)
        if (!$previously_assigned) {
            //Let's go ahead and add desired source as parent:
            $this->Mench_ledger->create(array(
                'link_player' => $link_player,
                'link_down' => $link_player,
                'link_up' => $set_e_down_id,
                'link_type' => 4230,
            ));
        }

    }

    function remove_duplicate_links($e__id){

        //A function that scans source followings links and removes duplicates

        $current_up = array();
        $duplicates_removed = 0;

        //Check followings to see if there are duplicates:
        foreach($this->Mench_ledger->fetch(array(
            'link_down' => $e__id,
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        ), array('link_up'), 0, 0, array('link_up' => 'ASC', 'link_id' => 'ASC')) as $x) {

            //Does this match any in the list so far?
            $duplicate_found = false;
            foreach($current_up as $up){
                if($up['link_up']==$x['link_up'] && $up['link_type']==$x['link_type'] && $up['link_text']==$x['link_text']){
                    $duplicate_found = true;
                    break;
                }
            }

            if($duplicate_found){
                //Remove it:
                $duplicates_removed++;
                $this->Mench_ledger->update($x['link_id'], array(), $x['link_player']); //Duplicate Link Removed
            } else {
                //Add it to main list:
                array_push($current_up, array(
                    'link_up' => $x['link_up'],
                    'link_type' => $x['link_type'],
                    'link_text' => $x['link_text'],
                ));
            }

        }

        return $duplicates_removed;

    }


    function remove($e__id, $link_player = 0, $migrate_s__id = 0){

        if($e__id<1){
            return 0;
        }

        //Fetch all SOURCE LINKS:
        $x_adjusted = 0;

        if($migrate_s__id){

            //Migrate Transactions:
            $this->db->query("UPDATE mench_ledger SET link_up=".$migrate_s__id." WHERE link_up=".$e__id.";");
            $affected_link_up = $this->db->affected_rows();
            $x_adjusted += $affected_link_up;
            $this->db->query("UPDATE mench_ledger SET link_down=".$migrate_s__id." WHERE link_down=".$e__id.";");
            $affected_link_down = $this->db->affected_rows();
            $x_adjusted += $affected_link_down;
            $this->db->query("UPDATE mench_ledger SET link_player=".$migrate_s__id." WHERE link_player=".$e__id.";");
            $affected_link_player = $this->db->affected_rows();
            $x_adjusted += $affected_link_player;
            $this->db->query("UPDATE mench_ledger SET link_type=".$migrate_s__id." WHERE link_type=".$e__id.";");
            $affected_link_type = $this->db->affected_rows();
            $x_adjusted += $affected_link_type;
            $this->db->query("UPDATE mench_ledger SET link_domain=".$migrate_s__id." WHERE link_domain=".$e__id.";");
            $affected_link_domain = $this->db->affected_rows();
            $x_adjusted += $affected_link_domain;

            //Clean Duplicates:
            $duplicates_removed = $this->Source_cache->remove_duplicate_links($migrate_s__id);
            $x_adjusted += $duplicates_removed;

        } else {

            //REMOVE TRANSACTIONS
            foreach($this->Mench_ledger->fetch(array(
                'link_void' => 0, //Not Void
                '(link_down = ' . $e__id . ' OR link_up = ' . $e__id . ' OR link_player = ' . $e__id . ')' => null,
            ), array(), 0) as $adjust_tr){
                //Delete this transaction:
                $x_adjusted += $this->Mench_ledger->update($adjust_tr['link_id'], array(), $link_player);
            }

        }

        return $x_adjusted;
    }


    function mass_update($e__id, $action_e__id, $action_command1, $action_command2, $link_player)
    {

        //Alert: Has a twin function called i_mass_update()

        boost_power();

        $action_command1 = trim($action_command1);
        $action_command2 = trim($action_command2);


        if(!in_array($action_e__id, $this->config->item('n___4997'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif(in_array($action_e__id, array(5981, 5982, 11956, 13441)) && !view__valid_handle_e($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @SourceHandle',
            );

        } elseif(in_array($action_e__id, array(11956)) && !view__valid_handle_e($action_command2)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @SourceHandle',
            );

        }


        //Basic input validation done, let's continue
        $applied_success = 0; //To be populated

        //Fetch all followers:
        $followers = $this->Mench_ledger->fetch(array(
            'link_up' => $e__id,
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        ), array('link_down'), 0);


        //Process request:
        foreach($followers as $x) {

            //Logic here must match items in e_mass_actions config variable

            //Take command-specific action:
            if ($action_e__id==4998) { //Add Prefix String

                $this->Source_cache->update($x['e__id'], array(
                    'e__title' => $action_command1 . $x['e__title'],
                ), true, $link_player);

                $applied_success++;

            } elseif ($action_e__id==4999) { //Add Postfix String

                $this->Source_cache->update($x['e__id'], array(
                    'e__title' => $x['e__title'] . $action_command1,
                ), true, $link_player);

                $applied_success++;

            } elseif (in_array($action_e__id, array(5981, 5982, 11956, 13441)) && view__valid_handle_e($action_command1)) { //Add/Delete/Migrate followings source

                //What member searched for:
                foreach($this->Source_cache->fetch(array(
                    'LOWER(e__handle)' => strtolower(view__valid_handle_e($action_command1)),
                )) as $e){

                    //See if follower source has searched followings source:
                    $down_up_e = $this->Mench_ledger->fetch(array(
                        'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'link_down' => $x['e__id'], //This follower source
                        'link_up' => $e['e__id'],
                        'link_void' => 0, //Not Void
                    ));

                    if((in_array($action_e__id, array(5981, 13441)) && count($down_up_e)==0)){

                        $add_fields = array(
                            'link_player' => $link_player,
                            'link_type' => 4230,
                            'link_down' => $x['e__id'], //This follower source
                            'link_up' => $e['e__id'],
                        );

                        if($action_e__id==13441){
                            //Copy message only if moving:
                            $add_fields['link_text'] = $x['link_text'];
                        }

                        //Following Member Addition
                        $this->Mench_ledger->create($add_fields);

                        $applied_success++;

                        if($action_e__id==13441){
                            //Since we're migrating we should remove from here:
                            $this->Mench_ledger->update($x['link_id'], array(), $link_player);
                        }

                    } elseif(in_array($action_e__id, array(5982, 11956)) && count($down_up_e) > 0){

                        if($action_e__id==5982){

                            //Following Member Removal
                            foreach($down_up_e as $delete_tr){
                                $this->Mench_ledger->update($delete_tr['link_id'], array(), $link_player);
                                $applied_success++;
                            }

                        } elseif($action_e__id==11956 && view__valid_handle_e($action_command2)) {

                            foreach($this->Source_cache->fetch(array(
                                'LOWER(e__handle)' => strtolower(view__valid_handle_e($action_command2)),
                            )) as $e){
                                //Add as a followings because it meets the condition
                                $this->Mench_ledger->create(array(
                                    'link_player' => $link_player,
                                    'link_type' => 4230,
                                    'link_down' => $x['e__id'], //This follower source
                                    'link_up' => $e['e__id'],
                                ));
                                $applied_success++;
                            }
                        }
                    }
                }

            } elseif ($action_e__id==5943) { //Member Mass Update Member Cover

                $this->Source_cache->update($x['e__id'], array(
                    'e__cover' => $action_command1,
                ), true, $link_player);

                $applied_success++;

            } elseif ($action_e__id==12318 && !strlen($x['e__cover'])) { //Member Mass Update Member Cover

                $this->Source_cache->update($x['e__id'], array(
                    'e__cover' => $action_command1,
                ), true, $link_player);

                $applied_success++;

            } elseif ($action_e__id==5000 && substr_count(strtolower($x['e__title']), strtolower($action_command1)) > 0) { //Replace Member Matching Name

                $this->Source_cache->update($x['e__id'], array(
                    'e__title' => str_ireplace($action_command1, $action_command2, $x['e__title']),
                ), true, $link_player);

                $applied_success++;

            } elseif ($action_e__id==10625 && substr_count($x['e__cover'], $action_command1) > 0) { //Replace Member Matching Cover

                $this->Source_cache->update($x['e__id'], array(
                    'e__cover' => str_replace($action_command1, $action_command2, $x['e__cover']),
                ), true, $link_player);

                $applied_success++;

            } elseif ($action_e__id==5001 && substr_count($x['link_text'], $action_command1) > 0) { //Replace Transaction Matching String

                $new_message = str_replace($action_command1, $action_command2, $x['link_text']);

                $this->Mench_ledger->update($x['link_id'], array(
                    'link_text' => $new_message,
                ), $link_player);

                $applied_success++;

            } elseif ($action_e__id==26093) { //Replace Transaction Matching String

                $this->Mench_ledger->update($x['link_id'], array(
                    'link_text' => $action_command1,
                ), $link_player);

                $applied_success++;

            } elseif ($action_e__id==42804 && ($action_command1=='*' || $x['link_type']==$action_command1) && in_array($action_command2, $this->config->item('n___32292') /* Source Link Types */)) { //Update Matching Interaction Type

                $this->Mench_ledger->update($x['link_id'], array(
                    'link_type' => $action_command2,
                ), $link_player);
                $applied_success++;

            }
        }

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($followers) . ' sources updated',
        );

    }


    function create($e__title, $link_player = 0, $e__cover = null){

        return false;

        //Validate Title
        $validate_e__title = validate_e__title($e__title);
        if(!$validate_e__title['status']){
            return $validate_e__title;
        }

        //Log transaction new source:
        $player_e = superpower_unlocked();
        $creator = ($link_player > 0 ? $link_player : ( $player_e ? $player_e['e__id'] : 0));
        if (!$creator) {
            return array(
                'status' => 1,
                'message' => 'Missing Creator Player',
            );
        }

        //Create New Source:
        $x = $this->Mench_ledger->create(array(
            'link_player' => $creator,
            'link_text' => $validate_e__title['e__title_clean'],
            'link_type' => 4251, //New Source Created
        ));

        if(!isset($x['link_id'])){

            //Ooopsi, something went wrong!
            $this->Mench_ledger->create(array(
                'link_type' => 44179, //Triggered
                'link_up' => 4246, //Platform Bug Reports
                'link_down' => $creator,
                'link_text' => 'create() failed to create a new source',
                'link_player' => $creator,
            ));

            return array(
                'status' => 1,
                'message' => 'Error trying to create Player',
            );
        }

        //Add to cache:
        $this->db->insert('cacheplayers', array(
            'playerid' => $x['link_id'],
            'playerhandle' => generate_handle(12274, $validate_e__title['e__title_clean']),
            'playercover' => $e__cover,
            'playertext' => $validate_e__title['e__title_clean'],
        ));

        //Update Search Index:
        flag_for_search_indexing(12274, $x['link_id']);

        //Fetch to return the complete source data:
        $es = $this->Source_cache->fetch(array(
            'e__id' => $x['link_id'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'new_e' => $es[0],
        );

    }

}