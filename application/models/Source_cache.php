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


    function activate_subscription($e__id, $LinkDomain = 0){


        //Remove from Anonymous:
        foreach($this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkUp IN (' . join(',', $this->config->item('n___32540')) . ')' => null, //Unsubscribers
            'LinkDown' => $e__id,
        )) as $unsubscriber_x){
            $this->Mench_ledger->update($unsubscriber_x['LinkId'], array(
                'LinkPrivacy' => 6173,
            ), $e__id, 10673 /* IDEA NOTES Unpublished */);
        }

        $session_data = $this->session->all_userdata();
        $this->session->set_userdata($session_data);


        //Add to Subscriber:
        $this->Mench_ledger->create(array(
            'LinkUp' => 4430, //Subscriber
            'LinkType' => 4251,
            'LinkPlayer' => $e__id,
            'LinkDown' => $e__id,
            'LinkDomain' => $LinkDomain,
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
            'LinkUp IN (' . join(',', $this->config->item('n___29648')) . ')' => null, //Unsubscribers
            'LinkDown' => $e['e__id'],
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        )) as $unsubscribe){
            $resubscribed += $this->Mench_ledger->update($unsubscribe['LinkId'], array(
                'LinkPrivacy' => 6173, //Transaction Removed
            ), $e['e__id'], 31064 /* Login Resubscribe */);
        }
        if($resubscribed > 0){
            //Add Back to Subscribers:
            $this->Mench_ledger->create(array(
                'LinkType' => 4251,
                'LinkUp' => 4430, //Active Member
                'LinkPlayer' => $e['e__id'],
                'LinkDown' => $e['e__id'],
            ));
        }



        if(!$update_session){

            if(!$is_cookie){

                //Create Cookie:
                $cookie_time = time();
                $cookie_val = $e['e__id'].'ABCEFG'.$cookie_time.'ABCEFG'.view__hash($e['e__id'].$cookie_time);
                setcookie('auth_cookie', $cookie_val, ($cookie_time + ( 86400 * view__memory(6404,14031))), "/");

            }

            $this->Mench_ledger->create(array(
                'LinkPlayer' => $e['e__id'],
                'LinkType' => ( $is_cookie ? 14032 /* COOKIE SIGN */ : 7564 /* MEMBER SIGN */ ),
            ));

        }




        //Fetch Platform Defaults:
        $platform_theme = array();
        foreach($this->Mench_ledger->fetch(array(
            'LinkUp IN (' . join(',', $this->config->item('n___14926')) . ')' => null, //Website Theme Items
            'LinkDown' => 6404, //Platform Default
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0) as $x) {
            array_push($platform_theme, intval($x['LinkUp']));
        }

        //Fetch Website Defaults:
        $website_theme = array();
        foreach($this->Mench_ledger->fetch(array(
            'LinkUp IN (' . join(',', $this->config->item('n___14926')) . ')' => null, //Website Theme Items
            'LinkDown' => website_setting(0), //Website ID
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0) as $x) {
            array_push($website_theme, intval($x['LinkUp']));
        }


        //Fetch User Defaults:
        $user_theme = array();
        foreach($this->Mench_ledger->fetch(array(
            'LinkDown' => $e['e__id'], //This follower source
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('LinkUp'), 0) as $e_up){

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
            'LinkUp IN (' . join(',', $this->config->item('n___31057')) . ')' => null, //Permanently Unsubscribed
            'LinkDown' => $e['e__id'], //This follower source
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0) as $unsubscribed){
            $unsubscribed_time = $unsubscribed['LinkTime'];
            $this->Mench_ledger->update($unsubscribed['LinkId'], array(
                'LinkPrivacy' => 6173,
            ), $e['e__id'], 31064); //Resubscribe
        }
        if($unsubscribed_time){
            //Add to subscribed again:
            $this->Mench_ledger->create(array(
                'LinkType' => 4251,
                'LinkUp' => 4430, //Active Member
                'LinkPlayer' => $e['e__id'],
                'LinkDown' => $e['e__id'],
            ));
            $this->session->set_flashdata('flash_message', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-user-check"></i></span>Welcome Back! You Have Been Re-Subscribed :)</div>');
        }
        */

        return $session_data;

    }


    function add_regular_e($LinkUp, $LinkDown, $LinkText = null) {
        //Add if link not already there:
        if(!count($this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkUp' => $LinkUp,
            'LinkDown' => $LinkDown,
            'LinkText' => $LinkText,
        )))){
            $this->Mench_ledger->create(array(
                'LinkPlayer' => $LinkDown, //Belongs to this Member
                'LinkType' => 4251,
                'LinkText' => $LinkText,
                'LinkUp' => $LinkUp,
                'LinkDown' => $LinkDown,
            ));
        }
    }

    function scissor_e($LinkUp, $sub_id){

        $all_results = $this->Mench_ledger->fetch(array(
            'LinkUp' => $LinkUp,
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
        ), array('LinkDown'), 0, 0, sort__e());

        //Remove if not in the secondary group:
        foreach($all_results as $key => $primary_list){
            if(!count($this->Mench_ledger->fetch(array(
                'LinkUp' => $sub_id,
                'LinkDown' => $primary_list['e__id'],
                'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC/OWNER
            ), array(), 0))){
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }

    function scissor_i($LinkUp, $sub_id){

        $all_results = $this->Mench_ledger->fetch(array(
            'LinkUp' => $LinkUp,
            'LinkType IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('LinkRight'), 0, 0, array('LinkNumber' => 'ASC'));

        //Remove if not in the secondary group:
        foreach($all_results as $key => $primary_list){
            if(!count($this->Mench_ledger->fetch(array(
                'LinkUp' => $sub_id,
                'LinkRight' => $primary_list['i__id'],
                'LinkType IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }



    function add_member($full_name, $email = null, $phone_number = null, $image_url = null, $LinkDomain = 0){

        //Set website if not set:
        if(!$LinkDomain){
            $LinkDomain = website_setting(0);
        }

        //All good, create new source:
        $new_private_users = in_array($LinkDomain, $this->config->item('n___44011'));
        $added_e = $this->Source_cache->verify_create($full_name, 0, ( $image_url ? $image_url : random_cover(12279) ), false, ( $new_private_users ? 4755 : 6181 ));
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
                'LinkType' => 4251,
                'LinkText' => trim(strtolower($email)),
                'LinkUp' => 3288, //Email
                'LinkPlayer' => $added_e['new_e']['e__id'],
                'LinkDown' => $added_e['new_e']['e__id'],
                'LinkDomain' => $LinkDomain,
            ));
        }

        //Add Number?
        if($phone_number){
            $this->Mench_ledger->create(array(
                'LinkUp' => 4783, //Phone
                'LinkType' => 4251,
                'LinkText' => $phone_number,
                'LinkPlayer' => $added_e['new_e']['e__id'],
                'LinkDown' => $added_e['new_e']['e__id'],
                'LinkDomain' => $LinkDomain,
            ));
        }

        if($email || $phone_number){

            $this->Source_cache->activate_subscription( $added_e['new_e']['e__id'], $LinkDomain );

        } else {

            //Add to anonymous:
            $this->Mench_ledger->create(array(
                'LinkUp' => 14938, //Guest
                'LinkType' => 4251,
                'LinkPlayer' => $added_e['new_e']['e__id'],
                'LinkDown' => $added_e['new_e']['e__id'],
                'LinkDomain' => $LinkDomain,
            ));

            //Assign session key:
            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

        }


        //Add member to Domain Member Group(s):
        $this->Source_cache->add_regular_e($LinkDomain, $added_e['new_e']['e__id']);


        //Send Welcome Email if any:
        if($email){
            foreach($this->Mench_ledger->fetch(array(
                'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'LinkType' => 33600, //Draft
                'LinkUp' => 14929, //Website Welcome Email Templates
            ), array('LinkRight'), 0) as $i){
                if(count($this->Mench_ledger->fetch(array(
                    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'LinkType' => 33600, //Draft
                    'LinkUp' => $LinkDomain, //for Current website
                    'LinkRight' => $i['i__id'], //Is this the template?
                )))){
                    //Found the email template to send:
                    $total_sent = $this->Mench_ledger->send_i_mass_dm(array($added_e['new_e']), $i, $LinkDomain);
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


    function create($add_fields, $LinkPlayer = 14068, $skip_creator_link = false)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($add_fields, array('e__title'), $LinkPlayer)) {
            return false;
        }

        if (!isset($add_fields['e__privacy']) || intval($add_fields['e__privacy']) < 1) {
            $add_fields['e__privacy'] = 6181; //PUBLIC SOURCE
        }

        //Generate Handle:
        $add_fields['e__handle'] = generate_handle(12274, $add_fields['e__title']);

        //Lets now add:
        $this->db->insert('cache_sources', $add_fields);

        //Fetch inserted id:
        if (!isset($add_fields['e__id'])) {
            $add_fields['e__id'] = $this->db->insert_id();
        }

        if ($add_fields['e__id'] > 0) {

            //Log transaction new source:
            $creator = ($LinkPlayer > 0 ? $LinkPlayer : $add_fields['e__id']);
            if(!$skip_creator_link && $creator!=$add_fields['e__id'] && !count($this->Mench_ledger->fetch(array(
                    'LinkUp' => $creator,
                    'LinkDown' => $add_fields['e__id'],
                    'LinkType' => 4251, //New Source Created
                    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                $this->Mench_ledger->create(array(
                    'LinkPlayer' => $creator,
                    'LinkUp' => $creator,
                    'LinkDown' => $add_fields['e__id'],
                    'LinkType' => 4251, //New Source Created
                ));
            }


            //Log transaction new Idea hashtag:
            $this->Mench_ledger->create(array(
                'LinkPlayer' => $LinkPlayer,
                'LinkRight' => $add_fields['e__id'],
                'LinkText' => $add_fields['e__handle'],
                'LinkType' => 42169, //Source Generated Handle
            ));

            //Fetch to return the complete source data:
            $es = $this->Source_cache->fetch(array(
                'e__id' => $add_fields['e__id'],
            ));

            //Update Search Index:
            flag_for_search_indexing(12274, $add_fields['e__id']);

            return $es[0];

        } else {

            //Ooopsi, something went wrong!
            $this->Mench_ledger->create(array(
                'LinkUp' => $LinkPlayer,
                'LinkText' => 'create() failed to create a new source',
                'LinkType' => 4246, //Platform Bug Reports
                'LinkPlayer' => $LinkPlayer,
                'LinkMetadata' => $add_fields,
            ));
            return false;

        }
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

    function fetch_recursive($LinkType, $e__id, $include_any_e = array(), $exclude_all_e= array(), $hard_level = 3, $hard_limit = 100, $s__level = 0){

        $flat_items = array();
        $s__level++;

        if(in_array($LinkType, $this->config->item('n___42276'))){

            //Up Source Link Groups:
            $order_columns = array('LinkType = \'41011\' DESC' => null, 'LinkNumber' => 'ASC', 'LinkTime' => 'DESC');
            $joins_objects = array('LinkUp');
            $query_filters = array(
                'LinkDown' => $e__id,
                'LinkType IN (' . join(',', $this->config->item('n___'.$LinkType)) . ')' => null, //SOURCE LINKS
                'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            );

        } elseif(in_array($LinkType, $this->config->item('n___42377'))){

            //Down Source Link Groups:
            $order_columns = array('LinkType = \'41011\' DESC' => null, 'LinkNumber' => 'ASC', 'LinkTime' => 'DESC');
            $joins_objects = array('LinkDown');
            $query_filters = array(
                'LinkUp' => $e__id,
                'LinkType IN (' . join(',', $this->config->item('n___'.$LinkType)) . ')' => null, //SOURCE LINKS
                'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            );

        } else {

            return false;

        }


        foreach($this->Mench_ledger->fetch($query_filters, $joins_objects, 0, 0, $order_columns) as $e_down) {

            //Filter Sources, if needed:
            $qualified_e = true;
            if(count($include_any_e) && !count($this->Mench_ledger->fetch(array(
                    'LinkUp IN (' . join(',', $include_any_e) . ')' => null,
                    'LinkDown' => $e_down['e__id'],
                    'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                //Must include all sources, skip:
                $qualified_e = false;
            }
            if(count($exclude_all_e) && count($this->Mench_ledger->fetch(array(
                    'LinkUp IN (' . join(',', $exclude_all_e) . ')' => null,
                    'LinkDown' => $e_down['e__id'],
                    'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
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

            foreach($this->Source_cache->fetch_recursive($LinkType, $e_down['e__id'], $include_any_e, $exclude_all_e, $hard_level, $hard_limit, $s__level) as $e_recursive_down){
                if(!isset($flat_items[$e_recursive_down['e__id']])){
                    $e_recursive_down['s__count'] = count($flat_items)+1;
                    $flat_items[$e_recursive_down['e__id']] = $e_recursive_down;
                }
            }
        }

        return $flat_items;
    }

    function update($id, $update_columns, $external_sync = false, $LinkPlayer = 0, $LinkType = 0)
    {

        $id = intval($id);
        if (count($update_columns)==0) {
            return false;
        }

        //Fetch current source filed values so we can compare later on after we've updated it:
        if($LinkPlayer > 0){
            $before_data = $this->Source_cache->fetch(array('e__id' => $id));
        }

        //Update:
        $this->db->where('e__id', $id);
        $this->db->update('cache_sources', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $LinkPlayer > 0) {

            if($external_sync){
                //Sync algolia:
                flag_for_search_indexing(12274, $id);
            }

            //Log modification transaction for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key]==$value){
                    //Nothing changed:
                    continue;
                }

                if($LinkType){

                    $LinkText = update_description($before_data[0][$key], $value);

                } elseif($key=='e__handle') {

                    $LinkType = 41983; //Source Handle Update
                    $LinkText = update_description($before_data[0][$key], $value);

                } elseif($key=='e__title') {

                    $LinkType = 10646; //Source Title Update
                    $LinkText = update_description($before_data[0][$key], $value);

                } elseif($key=='e__privacy') {

                    $LinkType = 10654; //Source Privacy Updated
                    $e___6177 = $this->config->item('e___6177'); //Source Privacy
                    $LinkText = view__db_field($key) . ' updated from [' . $e___6177[$before_data[0][$key]]['m__title'] . '] to [' . $e___6177[$value]['m__title'] . ']';

                } elseif($key=='e__cover') {

                    $LinkType = 10653; //Member Updated Cover
                    $LinkText = view__db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }

                //Value has changed, log transaction:
                $this->Mench_ledger->create(array(
                    'LinkPlayer' => ($LinkPlayer > 0 ? $LinkPlayer : $id),
                    'LinkType' => $LinkType,
                    'LinkDown' => $id,
                    'LinkText' => $LinkText,
                    'LinkMetadata' => array(
                        'e__id' => $id,
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ),
                ));

            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->Mench_ledger->create(array(
                'LinkDown' => $id,
                'LinkType' => 4246, //Platform Bug Reports
                'LinkPlayer' => $LinkPlayer,
                'LinkText' => 'update() Failed to update',
                'LinkMetadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }


    function radio_set($e_up_bucket_id, $set_e_down_id, $LinkPlayer)
    {

        /*
         * Treats an source follower group as a drop down menu where:
         *
         *  $e_up_bucket_id is the followings of the drop down
         *  $LinkPlayer is the member source ID that one of the followers of $e_up_bucket_id should be assigned (like a drop down)
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
            'LinkDown' => $LinkPlayer,
            'LinkUp IN (' . join(',', $followers) . ')' => null, //Current followers
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), view__memory(6404,11064)) as $x) {

            if (!$previously_assigned && $x['LinkUp']==$set_e_down_id) {
                $previously_assigned = true;
            } else {
                //Delete assignment:
                $x_update_id = $x['LinkId'];

                //Do not log update transaction here as we would log it further below:
                $this->Mench_ledger->update($x['LinkId'], array(
                    'LinkPrivacy' => 6173, //Transaction Deleted
                ), $LinkPlayer, 6224 /* Member Account Updated */);
            }

        }


        //Make sure $set_e_down_id belongs to followings if set (Could be null which means delete all)
        if (!$previously_assigned) {
            //Let's go ahead and add desired source as parent:
            $this->Mench_ledger->create(array(
                'LinkPlayer' => $LinkPlayer,
                'LinkDown' => $LinkPlayer,
                'LinkUp' => $set_e_down_id,
                'LinkType' => 4251,
                'LinkReference' => $x_update_id,
            ));
        }

    }

    function remove_duplicate_links($e__id){

        //A function that scans source followings links and removes duplicates

        $current_up = array();
        $duplicates_removed = 0;

        //Check followings to see if there are duplicates:
        foreach($this->Mench_ledger->fetch(array(
            'LinkDown' => $e__id,
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('LinkUp'), 0, 0, array('LinkUp' => 'ASC', 'LinkId' => 'ASC')) as $x) {

            //Does this match any in the list so far?
            $duplicate_found = false;
            foreach($current_up as $up){
                if($up['LinkUp']==$x['LinkUp'] && $up['LinkType']==$x['LinkType'] && $up['LinkPrivacy']==$x['LinkPrivacy'] && $up['LinkText']==$x['LinkText']){
                    $duplicate_found = true;
                    break;
                }
            }

            if($duplicate_found){
                //Remove it:
                $duplicates_removed++;
                $this->Mench_ledger->update($x['LinkId'], array(
                    'LinkPrivacy' => 6173,
                ), $x['LinkPlayer'], 29331); //Duplicate Link Removed
            } else {
                //Add it to main list:
                array_push($current_up, array(
                    'LinkUp' => $x['LinkUp'],
                    'LinkType' => $x['LinkType'],
                    'LinkPrivacy' => $x['LinkPrivacy'],
                    'LinkText' => $x['LinkText'],
                ));
            }

        }

        return $duplicates_removed;

    }


    function remove($e__id, $LinkPlayer = 0, $migrate_s__id = 0){

        if($e__id<1){
            return 0;
        }

        //Fetch all SOURCE LINKS:
        $x_adjusted = 0;

        if($migrate_s__id){

            //Migrate Transactions:
            $this->db->query("UPDATE mench_ledger SET LinkUp=".$migrate_s__id." WHERE LinkUp=".$e__id.";");
            $affected_LinkUp = $this->db->affected_rows();
            $x_adjusted += $affected_LinkUp;
            $this->db->query("UPDATE mench_ledger SET LinkDown=".$migrate_s__id." WHERE LinkDown=".$e__id.";");
            $affected_LinkDown = $this->db->affected_rows();
            $x_adjusted += $affected_LinkDown;
            $this->db->query("UPDATE mench_ledger SET LinkPlayer=".$migrate_s__id." WHERE LinkPlayer=".$e__id.";");
            $affected_LinkPlayer = $this->db->affected_rows();
            $x_adjusted += $affected_LinkPlayer;
            $this->db->query("UPDATE mench_ledger SET LinkType=".$migrate_s__id." WHERE LinkType=".$e__id.";");
            $affected_LinkType = $this->db->affected_rows();
            $x_adjusted += $affected_LinkType;
            $this->db->query("UPDATE mench_ledger SET LinkPrivacy=".$migrate_s__id." WHERE LinkPrivacy=".$e__id.";");
            $affected_LinkPrivacy = $this->db->affected_rows();
            $x_adjusted += $affected_LinkPrivacy;
            $this->db->query("UPDATE mench_ledger SET LinkDomain=".$migrate_s__id." WHERE LinkDomain=".$e__id.";");
            $affected_LinkDomain = $this->db->affected_rows();
            $x_adjusted += $affected_LinkDomain;

            //Clean Duplicates:
            $duplicates_removed = $this->Source_cache->remove_duplicate_links($migrate_s__id);
            $x_adjusted += $duplicates_removed;

            $player_e = superpower_unlocked();
            $this->Mench_ledger->create(array(
                'LinkPlayer' => ($LinkPlayer > 0 ? $LinkPlayer : $player_e['e__id'] ),
                'LinkType' => 31784,
                'LinkDown' => $migrate_s__id,
                'LinkMetadata' => array(
                    'migrated_links' => array(
                        'LinkUp' => $affected_LinkUp,
                        'LinkDown' => $affected_LinkDown,
                        'LinkPlayer' => $affected_LinkPlayer,
                        'LinkType' => $affected_LinkType,
                        'LinkPrivacy' => $affected_LinkPrivacy,
                        'LinkDomain' => $affected_LinkDomain,
                    ),
                    'old_sources_id' => $e__id,
                    'duplicates_removed' => $duplicates_removed,
                ),
            ));

        } else {

            //REMOVE TRANSACTIONS
            foreach($this->Mench_ledger->fetch(array(
                'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'LinkType !=' => 10673, //Member Transaction Unpublished
                '(LinkDown = ' . $e__id . ' OR LinkUp = ' . $e__id . ' OR LinkPlayer = ' . $e__id . ')' => null,
            ), array(), 0) as $adjust_tr){
                //Delete this transaction:
                $x_adjusted += $this->Mench_ledger->update($adjust_tr['LinkId'], array(
                    'LinkPrivacy' => 6173, //Transaction Deleted
                ), $LinkPlayer, 10673 /* Member Transaction Unpublished */);
            }

        }

        return $x_adjusted;
    }


    function mass_update($e__id, $action_e__id, $action_command1, $action_command2, $LinkPlayer)
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
            'LinkUp' => $e__id,
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('LinkDown'), 0);


        //Process request:
        foreach($followers as $x) {

            //Logic here must match items in e_mass_actions config variable

            //Take command-specific action:
            if ($action_e__id==4998) { //Add Prefix String

                $this->Source_cache->update($x['e__id'], array(
                    'e__title' => $action_command1 . $x['e__title'],
                ), true, $LinkPlayer);

                $applied_success++;

            } elseif ($action_e__id==4999) { //Add Postfix String

                $this->Source_cache->update($x['e__id'], array(
                    'e__title' => $x['e__title'] . $action_command1,
                ), true, $LinkPlayer);

                $applied_success++;

            } elseif (in_array($action_e__id, array(5981, 5982, 11956, 13441)) && view__valid_handle_e($action_command1)) { //Add/Delete/Migrate followings source

                //What member searched for:
                foreach($this->Source_cache->fetch(array(
                    'LOWER(e__handle)' => strtolower(view__valid_handle_e($action_command1)),
                )) as $e){

                    //See if follower source has searched followings source:
                    $down_up_e = $this->Mench_ledger->fetch(array(
                        'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'LinkDown' => $x['e__id'], //This follower source
                        'LinkUp' => $e['e__id'],
                        'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    ));

                    if((in_array($action_e__id, array(5981, 13441)) && count($down_up_e)==0)){

                        $add_fields = array(
                            'LinkPlayer' => $LinkPlayer,
                            'LinkType' => 4251,
                            'LinkDown' => $x['e__id'], //This follower source
                            'LinkUp' => $e['e__id'],
                        );

                        if($action_e__id==13441){
                            //Copy message only if moving:
                            $add_fields['LinkText'] = $x['LinkText'];
                        }

                        //Following Member Addition
                        $this->Mench_ledger->create($add_fields);

                        $applied_success++;

                        if($action_e__id==13441){
                            //Since we're migrating we should remove from here:
                            $this->Mench_ledger->update($x['LinkId'], array(
                                'LinkPrivacy' => 6173, //Transaction Deleted
                            ), $LinkPlayer, 10673 /* Member Transaction Unpublished  */);
                        }

                    } elseif(in_array($action_e__id, array(5982, 11956)) && count($down_up_e) > 0){

                        if($action_e__id==5982){

                            //Following Member Removal
                            foreach($down_up_e as $delete_tr){
                                $this->Mench_ledger->update($delete_tr['LinkId'], array(
                                    'LinkPrivacy' => 6173, //Transaction Deleted
                                ), $LinkPlayer, 10673 /* Member Transaction Unpublished  */);
                                $applied_success++;
                            }

                        } elseif($action_e__id==11956 && view__valid_handle_e($action_command2)) {

                            foreach($this->Source_cache->fetch(array(
                                'LOWER(e__handle)' => strtolower(view__valid_handle_e($action_command2)),
                            )) as $e){
                                //Add as a followings because it meets the condition
                                $this->Mench_ledger->create(array(
                                    'LinkPlayer' => $LinkPlayer,
                                    'LinkType' => 4251,
                                    'LinkDown' => $x['e__id'], //This follower source
                                    'LinkUp' => $e['e__id'],
                                ));
                                $applied_success++;
                            }
                        }
                    }
                }

            } elseif ($action_e__id==5943) { //Member Mass Update Member Cover

                $this->Source_cache->update($x['e__id'], array(
                    'e__cover' => $action_command1,
                ), true, $LinkPlayer);

                $applied_success++;

            } elseif ($action_e__id==12318 && !strlen($x['e__cover'])) { //Member Mass Update Member Cover

                $this->Source_cache->update($x['e__id'], array(
                    'e__cover' => $action_command1,
                ), true, $LinkPlayer);

                $applied_success++;

            } elseif ($action_e__id==5000 && substr_count(strtolower($x['e__title']), strtolower($action_command1)) > 0) { //Replace Member Matching Name

                $this->Source_cache->update($x['e__id'], array(
                    'e__title' => str_ireplace($action_command1, $action_command2, $x['e__title']),
                ), true, $LinkPlayer);

                $applied_success++;

            } elseif ($action_e__id==10625 && substr_count($x['e__cover'], $action_command1) > 0) { //Replace Member Matching Cover

                $this->Source_cache->update($x['e__id'], array(
                    'e__cover' => str_replace($action_command1, $action_command2, $x['e__cover']),
                ), true, $LinkPlayer);

                $applied_success++;

            } elseif ($action_e__id==5001 && substr_count($x['LinkText'], $action_command1) > 0) { //Replace Transaction Matching String

                $new_message = str_replace($action_command1, $action_command2, $x['LinkText']);

                $this->Mench_ledger->update($x['LinkId'], array(
                    'LinkText' => $new_message,
                ), $LinkPlayer, 10657 /* SOURCE LINK CONTENT UPDATE  */);

                $applied_success++;

            } elseif ($action_e__id==26093) { //Replace Transaction Matching String

                $this->Mench_ledger->update($x['LinkId'], array(
                    'LinkText' => $action_command1,
                ), $LinkPlayer, 10657 /* SOURCE LINK CONTENT UPDATE  */);

                $applied_success++;

            } elseif ($action_e__id==5003 && ($action_command1=='*' || $x['e__privacy']==$action_command1) && in_array($action_command2, $this->config->item('n___6177'))) {

                //Being deleted? Remove as well if that's the case:
                if(!in_array($action_command2, $this->config->item('n___7358'))){
                    $links_removed = $this->Source_cache->remove($x['e__id'], $LinkPlayer);
                }

                //Update Matching Member Status:
                $this->Source_cache->update($x['e__id'], array(
                    'e__privacy' => $action_command2,
                ), true, $LinkPlayer);

                $applied_success++;

            } elseif ($action_e__id==5865 && ($action_command1=='*' || $x['LinkPrivacy']==$action_command1) && in_array($action_command2, $this->config->item('n___6186') /* Interaction Privacy */)) { //Update Matching Interaction Privacy

                $this->Mench_ledger->update($x['LinkId'], array(
                    'LinkPrivacy' => $action_command2,
                ), $LinkPlayer, ( in_array($action_command2, $this->config->item('n___7360') /* ACTIVE */) ? 10656 /* Member Transaction Updated Status */ : 10673 /* Member Transaction Unpublished */ ));

                $applied_success++;

            } elseif ($action_e__id==42804 && ($action_command1=='*' || $x['LinkType']==$action_command1) && in_array($action_command2, $this->config->item('n___32292') /* Source Link Types */)) { //Update Matching Interaction Type

                $this->Mench_ledger->update($x['LinkId'], array(
                    'LinkType' => $action_command2,
                ), $LinkPlayer, 42805);
                $applied_success++;

            }
        }

        //Log mass source edit transaction:
        $this->Mench_ledger->create(array(
            'LinkPlayer' => $LinkPlayer,
            'LinkType' => $action_e__id,
            'LinkDown' => $e__id,
            'LinkMetadata' => array(
                'payload' => $_POST,
                'e_total' => count($followers),
                'e_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($followers) . ' sources updated',
        );

    }


    function verify_create($e__title, $LinkPlayer = 0, $e__cover = null, $skip_creator_link = false, $e__privacy = 6181){

        //Validate Title
        $validate_e__title = validate_e__title($e__title);
        if(!$validate_e__title['status']){
            return $validate_e__title;
        }

        //Create
        $focus_e = $this->Source_cache->create(array(
            'e__title' => $validate_e__title['e__title_clean'],
            'e__cover' => $e__cover,
            'e__privacy' => $e__privacy,
        ), $LinkPlayer, $skip_creator_link);

        //Return success:
        return array(
            'status' => 1,
            'new_e' => $focus_e,
        );

    }

}