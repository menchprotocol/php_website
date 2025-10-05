<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chains extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $external_sync = false, $update_observed = true)
    {

        //Required field:
        if (!isset($add_fields['chainusertype']) || !in_array($add_fields['chainusertype'], $this->config->item('userids___4593'))) {
            log_error('Chains->create() failed to create because of invalid Chain type @' . $add_fields['chainusertype'], array(
                'chainusercreator' => $add_fields['chainusercreator'],
                'chainuseroutput' => $add_fields['chainusertype'],
            ));
            return false;
        }

        //Set some defaults:
        if (!isset($add_fields['chainusercreator']) || intval($add_fields['chainusercreator']) < 1) {
            $user_session = user_session();
            $add_fields['chainusercreator'] = ( $user_session ? $user_session['userid'] : 14068 ); //GUEST MEMBER
        }

        //Set some defaults:
        if (!isset($add_fields['chainvalue'])) {
            $add_fields['chainvalue'] = null;
        } elseif (is_array($add_fields['chainvalue'])) {
            $add_fields['chainvalue'] = serialize($add_fields['chainvalue']);
        }

        //Set some zero defaults if not set:
        foreach (array('chainpostoutput', 'chainpostinput', 'chainuseroutput', 'chainuserinput', 'chainkey') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Append Domain:
        if (!isset($add_fields['chainuserdomain']) || $add_fields['chainuserdomain'] < 1) {
            $add_fields['chainuserdomain'] = website_setting(0, $add_fields['chainusercreator']);
        }

        //Append time:
        if (!isset($add_fields['chaintime']) || is_null($add_fields['chaintime'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $add_fields['chaintime'] = $d->format("Y-m-d H:i:s");
        }

        //Is this an observation chain that should replace an older observation, if any:
        if($update_observed && in_array($add_fields['chainusertype'], $this->config->item('userids___1308453'))){

            $read_fields = $add_fields;

            if(isset($read_fields['chainid'])){
                unset($read_fields['chainid']);
            }
            if(isset($read_fields['chaintime'])){
                unset($read_fields['chaintime']);
            }
            if(isset($read_fields['chainkey'])){
                unset($read_fields['chainkey']);
            }
            if(isset($read_fields['chainprevious'])){
                unset($read_fields['chainprevious']);
            }
            if(isset($read_fields['chainhash'])){
                unset($read_fields['chainhash']);
            }
            if(isset($read_fields['chainvoid'])){
                unset($read_fields['chainvoid']);
            }
            if(isset($read_fields['chainuserdomain'])){
                unset($read_fields['chainuserdomain']);
            }
            if(isset($read_fields['chainvalue'])){
                unset($read_fields['chainvalue']);
            }

            foreach ($this->Chains->read($read_fields, array(), 1) as $last_observation) {
                //Update the previous observed chain:
                return $this->Chains->update($last_observation['chainid'], $add_fields);
            }
        }

        //Let's log, Always auto generated:
        $insert_chain_id = ( isset($add_fields['chainid']) ? $add_fields['chainid'] : 0 );
        $add_fields['chainprevious'] = chainprevious();
        $add_fields['chainhash'] = chainhash($add_fields);
        $this->db->insert('ideachains', $add_fields);

        //Fetch inserted id:
        $add_fields['chainid'] = ( $insert_chain_id>0 ? $insert_chain_id : $this->db->insert_id() );

        //All good?
        if ($add_fields['chainid'] < 1) {
            log_error('Chains->create() Failed to create', array(
                'chainusercreator' => $add_fields['chainusercreator'],
                'chainuseroutput' => $add_fields['chainusercreator'],
            ));
            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['chainuserinput'] > 0) {
                update_algolia(12274, $add_fields['chainuserinput']);
            }
            if ($add_fields['chainuseroutput'] > 0) {
                update_algolia(12274, $add_fields['chainuseroutput']);
            }
            if ($add_fields['chainpostinput'] > 0) {
                update_algolia(12273, $add_fields['chainpostinput']);
            }
            if ($add_fields['chainpostoutput'] > 0) {
                update_algolia(12273, $add_fields['chainpostoutput']);
            }
        }


        //See if this Chain type has any followers that are essentially subscribed to it:
        $tr_watchers = $this->Users->tree(42381, $add_fields['chainusertype'], $this->config->item('userids___30820'), array(), 1);
        if (is_array($tr_watchers) && count($tr_watchers)) {

            //yes, start drafting email to be sent to them
            $u_name = 'Unknown';
            if ($add_fields['chainusercreator'] > 0) {
                //Fetch member details:
                $add_e = $this->Users->read(array(
                    'userid' => $add_fields['chainusercreator'],
                ));
                if (count($add_e)) {
                    $u_name = $add_e[0]['username'];
                }
            }

            //Email Subject:
            $users___4593 = $this->config->item('users___4593'); //Chain Types
            $subject = $u_name . ' ' . $users___4593[$add_fields['chainusertype']]['m__title'];

            //Compose email body, start with Chain content:
            $html_message = (strlen($add_fields['chainvalue']) > 0 ? $add_fields['chainvalue'] : '') . "\n";


            //Append Chain object Chains:
            foreach ($this->config->item('users___4341') as $userid => $m) {

                if (in_array(6202, $m['m__following'])) {

                    //POST
                    foreach ($this->Posts->read(array('postid' => $add_fields[$m['m__user']])) as $this_i) {
                        $html_message .= $m['m__title'] . ': ' . view_post_title($this_i, true) . ':' . "\n" . $this->config->item('base_url') . view_memory(42903, 33286) . $this_i['posthashtag'] . "\n\n";
                    }

                } elseif (in_array(6160, $m['m__following'])) {

                    //USER
                    foreach ($this->Users->read(array('userid' => $add_fields[$m['m__user']])) as $this_e) {
                        $html_message .= $m['m__title'] . ': ' . $this_e['username'] . "\n" . $this->config->item('base_url') . view_memory(42903, 42902) . $this_e['userhandle'] . "\n\n";
                    }

                } elseif (in_array(4367, $m['m__following'])) {

                    //DISCOVERY
                    $html_message .= $m['m__title'] . ':' . "\n" . $this->config->item('base_url') . view_app_chain(12722) . '?chainid=' . $add_fields[$m['m__user']] . "\n\n";

                }

            }

            //Finally append DISCOVERY ID:
            $html_message .= 'Chain: #' . $add_fields['chainid'] . "\n" . $this->config->item('base_url') . view_app_chain(12722) . '?chainid=' . $add_fields['chainid'] . "\n\n";

            //Message Watchers:
            foreach ($tr_watchers as $tr_watcher) {
                //Do not inform the member who just took the action:
                if ($tr_watcher['userid'] != $add_fields['chainusercreator']) {
                    $this->Chains->message($tr_watcher['userid'], $subject, $html_message, array(
                        'chainpostoutput' => $add_fields['chainpostoutput'],
                        'chainpostinput' => $add_fields['chainpostinput'],
                        'chainuseroutput' => $add_fields['chainuseroutput'],
                        'chainuserinput' => $add_fields['chainuserinput'],
                    ));
                }
            }
        }

        //Return:
        return $add_fields;

    }


    function read($query_filters = array(), $joins_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('chainid' => 'DESC'), $select = '*', $group_by = null, $access_limit = true)
    {

        if(!is_array($joins_objects)){
            $joins_objects = array();
        }

        $this->db->select($select);
        $this->db->from('ideachains');

        //POST JOIN?
        $post_join = false;
        if (in_array('chainpostinput', $joins_objects)) {
            $post_join = true;
            $this->db->join('posts', 'chainpostinput=postid', 'left');
        } elseif (in_array('chainpostoutput', $joins_objects)) {
            $post_join = true;
            $this->db->join('posts', 'chainpostoutput=postid', 'left');
        } elseif (in_array('chainpostid', $joins_objects)) {
            $post_join = true;
            $this->db->join('posts', 'chainid=postid', 'left');
        }

        //PLAYER JOIN?
        $user_join = false;
        if (in_array('chainuserinput', $joins_objects)) {
            $user_join = true;
            $this->db->join('users', 'chainuserinput=userid', 'left');
        } elseif (in_array('chainuseroutput', $joins_objects)) {
            $user_join = true;
            $this->db->join('users', 'chainuseroutput=userid', 'left');
        } elseif (in_array('chainusertype', $joins_objects)) {
            $user_join = true;
            $this->db->join('users', 'chainusertype=userid', 'left');
        } elseif (in_array('chainusercreator', $joins_objects)) {
            $user_join = true;
            $this->db->join('users', 'chainusercreator=userid', 'left');
        } elseif (in_array('chainuserdomain', $joins_objects)) {
            $user_join = true;
            $this->db->join('users', 'chainuserdomain=userid', 'left');
        } elseif (in_array('chainuserid', $joins_objects)) {
            $user_join = true;
            $this->db->join('users', 'chainid=userid', 'left');
        }

        $void_found = false;
        foreach ($query_filters as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
            if (substr_count($key, 'chainvoid')) {
                $void_found = true;
            }
        }
        if (!$void_found) {
            //Auto add:
            $this->db->where('chainvoid', 0); //Not Void
        }
        if($post_join){
            $this->db->where('postid >', 0);
        }
        if($user_join){
            $this->db->where('userid >', 0);
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
        $results = $q->result_array();


        //Verify Access to each item:
        if ($access_limit && $select == '*' && isset($_SERVER['SERVER_NAME'])) {
            if (array_intersect(array('chainpostinput', 'chainpostoutput'), $joins_objects)) {
                //Post results:
                foreach ($results as $key => $value) {
                    if (!post_access(null, $value['postid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            } elseif (array_intersect(array('chainuserinput', 'chainuseroutput'), $joins_objects)) {
                //User results:
                foreach ($results as $key => $value) {
                    if (!user_access(null, $value['userid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            }
        }

        return $results;

    }


    function update($chainid, $update_columns, $chainusercreator = 0)
    {

        //Fetch Chain before updating:
        foreach ($this->Chains->read(array(
            'chainid' => $chainid,
        )) as $old_x) {

            //Make sure something changed:
            $something_changed = false;
            foreach(array('chainusertype','chainuserinput','chainuseroutput','chainpostinput','chainpostoutput','chainkey','chainvalue','chainvoid') as $must_change){

                $this_changed = isset($update_columns[$must_change]) && $old_x[$must_change]!=$update_columns[$must_change];

                if(!isset($update_columns[$must_change])){
                    $update_columns[$must_change] = $old_x[$must_change];
                }
                if($this_changed){
                    $something_changed = true;
                }
            }
            if(!$something_changed){
                return 0; //Nothing changed
            }


            //Create New Chain
            if (!isset($update_columns['chainusercreator'])) {
                //Fetch session user:
                $update_columns['chainusercreator'] = ($chainusercreator > 0 ? $chainusercreator : $old_x['chainusercreator'] );
            }
            $update_columns['chaintime'] = date("Y-m-d H:i:s"); //Always update time
            $new_x = $this->Chains->create($update_columns, true, false);


            if ($new_x['chainid'] > 0) {
                //Void Old Chain:
                $this->db->query("UPDATE ideachains SET chainvoid = " . $new_x['chainid'] . " WHERE chainid = " . $chainid . ";");
                return $this->db->affected_rows();
            }

            log_error('Chains->update() failed to create new chain', $update_columns);
            return 0;

        }

        //Invalid chain:
        log_error('Chains->update() did not find chain id '.$chainid, $update_columns);
        return 0;

    }


    function delete($chainid, $chainusercreator = 0)
    {

        //Validate $chainid
        foreach ($this->Chains->read(array(
            'chainid' => $chainid,
        )) as $old_x) {

            //Set default user:
            if (!$chainusercreator) {
                //Fetch session user:
                $user_session = user_session();
                $chainusercreator = ($user_session ? $user_session['userid'] : ($old_x['chainusercreator'] > 0 ? $old_x['chainusercreator'] : 14068 /* Guest Member */));
            }

            $new_x = $this->Chains->create(array(
                'chainusercreator' => $chainusercreator,
                'chainusertype' => 44395, //CHAIN VOID
                'chainvoid' => $chainid, //We insert as void since this is a void chain only
            ));

            if (!isset($new_x['chainid'])) {
                return 0; //Should not happen
            }

            //Void this Chain:
            $this->db->query("UPDATE ideachains SET chainvoid = " . $new_x['chainid'] . " WHERE chainid = " . $chainid . ";");
            return $this->db->affected_rows();

        }

        //Invalid chain:
        return 0;

    }

    function read2($query_filters = array(), $joins_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('chain_id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->dbalt = $this->load->database('snapshot', TRUE);
        $this->dbalt->select($select);
        $this->dbalt->from('mench_ledger');

        foreach ($query_filters as $key => $value) {
            if (!is_null($value)) {
                $this->dbalt->where($key, $value);
            } else {
                $this->dbalt->where($key);
            }
        }

        if ($group_by) {
            $this->dbalt->group_by($group_by);
        }

        foreach ($order_columns as $key => $value) {
            $this->dbalt->order_by($key, $value);
        }

        if ($limit > 0) {
            $this->dbalt->limit($limit, $limit_offset);
        }
        $q = $this->dbalt->get();
        return $q->result_array();

    }


    function select($focus__id, $o__id, $element_id, $user_createid, $migrateuser, $chainid = 0)
    {

        //Authenticate Member:
        $migrateuser = trim(substr($migrateuser, 0, 1) == '@' ? trim(substr($migrateuser, 1)) : $migrateuser);
        $migrateuser = trim(substr($migrateuser, 0, 1) == '#' ? trim(substr($migrateuser, 1)) : $migrateuser);
        $user_session = user_session();
        if (!$user_session) {
            return array(
                'status' => 0,
                'message' => blocked_reasoning(),
            );
        } elseif (intval($o__id) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Target ID',
            );
        } elseif (intval($element_id) < 1 || !count($this->config->item('userids___' . $element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Variable ID [' . $element_id . ']',
            );
        } elseif (intval($user_createid) < 1 || !in_array($user_createid, $this->config->item('userids___' . $element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            );
        }


        //See if anything is being deleted:
        $auto_open_post_modal = 0;
        $delete_redirect = null;
        $delete_element = null;
        $chains_removed = -1;
        $status = 0;
        $delete_redirect = '';
        $delete_element = '';

        if ($element_id == 4486 && $chainid > 0) {

            //POST CHAIN TYPE
            $status = $this->Chains->update($chainid, array(
                'chainusercreator' => $user_session['userid'],
                'chainusertype' => $user_createid,
            ));

        } elseif ($element_id == 13550 && $chainid > 0) {

            //USER CHAIN TYPE
            $status = $this->Chains->update($chainid, array(
                'chainusertype' => $user_createid,
                'chainusercreator' => $user_session['userid'],
            ));

        } elseif ($element_id == 32292 && $chainid > 0) {

            //USER/USER CHAIN
            $status = $this->Chains->update($chainid, array(
                'chainusertype' => $user_createid,
                'chainusercreator' => $user_session['userid'],
            ));

        } elseif (0 && $element_id == 42795 && $o__id > 0 && $user_createid && $user_session) {

            if (!$chainid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Chains->read(array(
                    'chainuserinput' => $o__id,
                    'chainuseroutput' => $user_session['userid'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___42795')) . ')' => null, //Follow
                ), array(), 1) as $found_x) {
                    $chainid = $found_x['chainid'];
                }
            }

            //Follow
            if ($chainid > 0) {
                //Updating reaction:
                if (in_array($user_createid, $this->config->item('userids___42850'))) {
                    //Unsubscribe
                    $status = $this->Chains->delete($chainid, $user_session['userid']); //Media Removed
                } else {
                    $status = $this->Chains->update($chainid, array(
                        'chainusertype' => $user_createid,
                        'chainusercreator' => $user_session['userid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Chains->create(array(
                    'chainusercreator' => $user_session['userid'],
                    'chainuserinput' => $o__id,
                    'chainuseroutput' => $user_session['userid'],
                    'chainusertype' => $user_createid,
                )));
            }

        } elseif ($element_id == 42260 && $o__id > 0 && $user_createid && $user_session) {

            //Check if current value?
            if (!$chainid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Chains->read(array(
                    'chainuserinput' => $user_session['userid'],
                    'chainpostoutput' => $o__id,
                    'chainusertype IN (' . join(',', $this->config->item('userids___42260')) . ')' => null, //Reactions
                ), array(), 1) as $found_x) {
                    $chainid = $found_x['chainid'];
                }
            }

            //Reactions...
            if ($chainid > 0) {
                if (in_array($user_createid, $this->config->item('userids___42850'))) {
                    $status = $this->Chains->delete($chainid, $user_session['userid']); //Removed
                } else {
                    //Updating reaction:
                    $status = $this->Chains->update($chainid, array(
                        'chainusertype' => $user_createid,
                        'chainusercreator' => $user_session['userid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Chains->create(array(
                    'chainusercreator' => $user_session['userid'],
                    'chainuserinput' => $user_session['userid'],
                    'chainpostoutput' => $o__id,
                    'chainusertype' => $user_createid,
                )));
            }

        }

        return array(
            'status' => intval($status) && ($chains_removed < 0 || $chains_removed > 0),
            'message' => 'Delete status [' . $status . '] with ' . $chains_removed . ' Chains removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
            'auto_open_post_modal' => $auto_open_post_modal,
        );

    }

    function message($userid, $subject, $html_message, $x_data = array(), $template_postid = 0, $chainuserdomain = 0, $log_tr = true, $demo_only = false)
    {

        $sms_subscriber = false;

        //Bypass notifications?
        if (!count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Writes
            'chainuserinput' => 31779, //Mandatory Emails
            'chainpostoutput' => $template_postid,
        )))) {

            $notification_levels = $this->Chains->read(array(
                'chainuserinput IN (' . join(',', $this->config->item('userids___30820')) . ')' => null, //Active Subscriber
                'chainuseroutput' => $userid,
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ));
            if (!count($notification_levels)) {
                return array(
                    'status' => 0,
                    'message' => 'User is not an active subscriber',
                );
            }
            $sms_subscriber = in_array($notification_levels[0]['chainuserinput'], $this->config->item('userids___28915'));
        }

        //Make sure not recently contacted:
        /*
         * Did not work with subscription notifications which could happen back to back...
         *
        $minutes_limit = 60;
        foreach($this->Chains->read(array(
            'chainusertype' => 29399,
            'chainusercreator' => $userid,
            'chaintime >=' => date("Y-m-d H:i:s", strtotime('-'.$minutes_limit.' minutes')),
        )) as $recent_email){
            return array(
                'status' => 0,
                'message' => 'User has been recently contacted',
            );
        }
        */

        $stats = array(
            'email_addresses' => array(),
            'sms_numbers' => array(),
            'phone_count' => 0,
        );


        //Send Emails:
        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            'chainuserinput' => 3288, //Email
            'chainuseroutput' => $userid,
        )) as $user_data) {

            if (!filter_var($user_data['chainvalue'], FILTER_VALIDATE_EMAIL)) {
                $this->Chains->delete($user_data['chainid'], $userid);
                continue;
            }

            if(!in_array($user_data['chainvalue'],$stats['email_addresses'])){
                array_push($stats['email_addresses'], $user_data['chainvalue']);
            }
        }

        if (count($stats['email_addresses']) > 0) {
            //Send email:
            dispatch_email($stats['email_addresses'], $subject, $html_message, $userid, $x_data, $template_postid, $chainuserdomain, $log_tr, $demo_only);
        }


        //Should we send SMS?
        $twilio_account_sid = website_setting(30859);
        $twilio_auth_token = website_setting(30860);
        $twilio_from_number = website_setting(27673);
        if ($sms_subscriber && $twilio_account_sid && $twilio_auth_token && $twilio_from_number) {

            //Yes, generate message
            $sms_message = get_domain('m__title', $userid, $chainuserdomain) . ' Emailed [' . $subject . '] to ' . join(' & ', $stats['email_addresses']) . ' (Also Check Spam)';

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n", " ", $sms_message);

            //Send SMS
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                'chainuserinput' => 4783, //Phone
                'chainuseroutput' => $userid,
            )) as $user_data) {

                $clean_number = preg_replace('/[^0-9.]+/', '', $user_data['chainvalue']);

                foreach (explode('|||', wordwrap($sms_message, view_memory(6404, 27891), "|||")) as $single_message) {
                    if(!in_array($clean_number,$stats['sms_numbers'])){
                        $stats['phone_count']++;
                        array_push($stats['sms_numbers'], $clean_number);
                        $sms_sent = dispatch_sms($clean_number, $single_message, $userid, $x_data, $template_postid, $chainuserdomain, $log_tr, $demo_only);
                        if (!$sms_sent) {
                            //bad number, remove it:
                            $this->Chains->delete($user_data['chainid'], $userid);
                        }
                    }
                }
            }
        }

        return array(
            'status' => ($stats['phone_count'] > 0 || count($stats['email_addresses']) > 0 ? 1 : 0),
            'email_count' => count($stats['email_addresses']),
            'phone_count' => $stats['phone_count'],
            'message' => 'Message sent',
        );

    }


    function broadcast($list_of_userid, $i, $chainuserdomain = 0, $ensure_discovered = true, $demo_only = false)
    {

        $total_sent = 0;
        $chainuserdomain = ($chainuserdomain > 0 ? $chainuserdomain : (isset($i['chainuserdomain']) ? $i['chainuserdomain'] : 0));
        $subject_line = view_post_title($i, true);
        $wacth_repeat_users = array();

        foreach ($list_of_userid as $count => $x) {

            if (in_array($x['userhandle'], $wacth_repeat_users)) {
                //This should not happen! Report bug:
                log_error('Chains->broadcast() Detected duplicate User User Bug: ' . $x['userhandle'], array(
                    'chainuseroutput' => $x['userid'],
                ));
                break; //Stop sending more messages!
            }

            //Map this user:
            array_push($wacth_repeat_users, $x['userhandle']);
            $user_hash = '?userlogin=' . $x['userhandle'] . '&time=' . time() . '&hash=' . view_hash(time() . $x['userhandle']);


            if (!isset($x['userid'])) {
                //Invalid input for sending:
                log_error('Chains->broadcast() Invalid User', array(
                    'chainusercreator' => $x['userid'],
                    'chainuseroutput' => 26582, //Messener
                ));
                continue;
                } elseif ($ensure_discovered && count($this->Chains->read(array(
                    'chainpostinput' => $i['postid'],
                    'chainusercreator' => $x['userid'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                )))) {
                //Already post discovered:
                continue;
            }


            $content_message = view_post_value($i, $x['userid'], true); //Hide the show more content if any
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', str_replace('  ',' ',$content_message));
            }

            //Personalize Source references:
            if($x['userid']>0){

                foreach ($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___13550')) . ')' => null, //Mentions
                    'chainpostoutput' => $i['postid'],
                ), array('chainuserinput'), 0) as $down_or) {

                    //See if this user has any of this:
                    foreach ($this->Chains->read(array(
                        'chainuserinput' => $down_or['userid'],
                        'chainuseroutput' => $x['userid'],
                        'LENGTH(chainvalue) > 0' => null,
                        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                    ), array(), 1) as $personalized) {
                        if(substr_count($content_message, '>@'.$down_or['userhandle'])){
                            $content_message = str_replace('>@'.$down_or['userhandle'], '>', $content_message);//.$personalized['chainvalue']
                        } else {
                            //$content_message = $content_message . $personalized['chainvalue'];
                        }
                    }
                }
            }


            //Append children as options:
            $html_message = '';
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
                'chainpostinput' => $i['postid'],
            ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC')) as $down_or) {
                $append_link = 'https://' . get_domain('m__message', $x['userid'], $chainuserdomain) . view_memory(42903, 33286) . $down_or['posthashtag'] . (post_is_startable($down_or) ? '/' . view_memory(6404, 4235) : '') . $user_hash;
                //Has this user post discovered this post or no?
                $html_message .= '<div class="line">' . view_post_title($down_or, true) . ':</div>';
                $html_message .= '<div class="line"><a href="'.$append_link.'">' . $append_link . '</div>';
            }

            //Where to place the next step?
            if (substr_count($content_message, 'link_here') == 1) {
                //We have direction to place the next step somewhere specific:
                $content_message = str_replace('link_here', $html_message, $content_message);
            } else {
                $content_message = $content_message . $html_message;
            }


            //Where to place the next step?
            if (substr_count($content_message, '?user_hash')) {
                //We have direction to place the next step somewhere specific:
                $content_message = str_replace('?user_hash', $user_hash, $content_message);
            }


            $message = $this->Chains->message($x['userid'], $subject_line, $content_message, array(
                'chainpostinput' => $i['postid'],
            ), $i['postid'], $chainuserdomain, true, $demo_only);

            if ($message['status'] && !$demo_only) {
                $total_sent++;
            }

        }

        return $total_sent;
    }


    function previouspost($userid, $target_posthashtag, $focus_postid, $loop_breaker_ids = array())
    {

        //echo 'Previous:'.$userid.'/'.$target_posthashtag.'/'.$focus_postid;

        if (count($loop_breaker_ids) > 0 && in_array($focus_postid, $loop_breaker_ids)) {
            return array();
        }
        array_push($loop_breaker_ids, intval($focus_postid));

        //Fetch followings:
        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
            'chainpostoutput' => $focus_postid,
        ), array('chainpostinput')) as $post_previous) {

            //Validate Selection:
            $input__selection = $this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostoutput' => $post_previous['postid'],
                'chainuserinput IN (' . join(',', $this->config->item('userids___7712')) . ')' => null,
            ));

            if ($userid > 0 && !count($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___7704')) . ')' => null, //Discovery Expansion
                    'chainpostinput' => $post_previous['postid'],
                    'chainpostoutput' => $focus_postid,
                    'chainusercreator' => $userid,
                ))) && $input__selection) {
                continue;
            }

            //Did we find it?
            if ($post_previous['posthashtag'] == $target_posthashtag) {
                return array($post_previous);
            }

            //Keep looking further up:
            $website_finder = $this->Chains->previouspost($userid, $target_posthashtag, $post_previous['postid'], $loop_breaker_ids);
            if (count($website_finder)) {
                array_push($website_finder, $post_previous);
                return $website_finder;
            }
        }

        //Did not find any followings:
        return array();

    }
    


    function next_posts($userid, $target_posthashtag, $i=false, $find_after_postid = 0, $search_up = true, $target_completed = false, $loop_breaker_ids = array())
    {

        if(!$i){
            foreach ($this->Posts->read(array(
                'LOWER(posthashtag)' => strtolower($target_posthashtag),
            )) as $i_new) {
                $i = $i_new;
            }
        }

        if (count($loop_breaker_ids) > 0 && in_array($i['postid'], $loop_breaker_ids)) {
            return null;
        }
        array_push($loop_breaker_ids, intval($i['postid']));

        $input__selection = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___7712')) . ')' => null,
        ));
        $found_trigger = null;



        foreach ($this->Chains->read(array(
            'chainpostinput' => $i['postid'],
            'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
        ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_postid && !$found_trigger) {
                if ($next_i['postid'] == $find_after_postid) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            if ($input__selection && !count($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___7704')) . ')' => null, //Discovery Expansion
                    'chainpostinput' => $i['postid'],
                    'chainpostoutput' => $next_i['postid'],
                    'chainusercreator' => $userid,
                )))) {
                continue;
            }

            //Return this if everything is completed, or if this is incomplete:
            if ($target_completed || !count($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                    'chainusercreator' => $userid,
                    'chainpostinput' => $next_i['postid'],
                )))) {
                return $next_i['posthashtag'];
            }

            //Keep looking deeper:
            $next__url = $this->Chains->next_posts($userid, $target_posthashtag, $next_i, $find_after_postid, false, $target_completed, $loop_breaker_ids);
            if ($next__url) {
                return $next__url;
            }

        }


        if (0 && $search_up && $target_posthashtag != $i['posthashtag']) {
            //Check Previous/Up
            $current_previous = $i['postid'];
            foreach (array_reverse($this->Chains->previouspost($userid, $target_posthashtag, $i['postid'])) as $p_i) {
                //Find the next siblings:
                if($p_i['postid']==$i['postid']) {
                    continue;
                }
                $next__url = $this->Chains->next_posts($userid, $target_posthashtag, $p_i, $current_previous, false, $target_completed);
                if ($next__url) {
                    return $next__url;
                }
                $current_previous = $p_i['postid'];
            }
        }

        //Nothing found:
        return null;

    }


    function post_discovered($chainusertype, $chainusercreator, $target_postid = 0, $i, $user_submitted_data = array(), $x_data = array())
    {

        if (!$chainusercreator || !in_array($chainusertype, $this->config->item('userids___31777' /* DISCOVERIES */))) {
            return log_error('Invalid chainusertype @' . $chainusertype . ' missing in @31777 OR Missing $chainusercreator', array(
                'chainuseroutput' => $chainusercreator,
                'chainusercreator' => $chainusercreator,
            ));
        }

        //Do we need to save text/upload ?
        $user_session = user_session();
        $input__selection = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___7712')) . ')' => null,
        )));
        $input__upload = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___43004')) . ')' => null,
        )));
        $input__text = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', array_merge($this->config->item('userids___43002'), $this->config->item('userids___43003'))) . ')' => null,
        )));
        $is_required = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput' => 28239, //Required
        )));


        if ($input__upload || $input__text) {

            if (!isset($user_submitted_data['post_createtext'])) {
                $user_submitted_data['post_createtext'] = null;
            }

            //Must add a new post, but first let's validate the input:
            if (count($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostoutput' => $i['postid'],
                'chainuserinput' => 31794,
            ))) && strlen($user_submitted_data['post_createtext']) && !is_numeric($user_submitted_data['post_createtext'])) {
                //Number Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Number',
                );
            } elseif (count($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                    'chainpostoutput' => $i['postid'],
                    'chainuserinput' => 42915,
                ))) && strlen($user_submitted_data['post_createtext']) && !filter_var($user_submitted_data['post_createtext'], FILTER_VALIDATE_URL)) {
                //Chain Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid URL',
                );
            } elseif (count($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostoutput' => $i['postid'],
                'chainuserinput' => 30350,
            ))) && strlen($user_submitted_data['post_createtext']) && !strtotime($user_submitted_data['post_createtext'])) {
                //Date Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Date',
                );
            }

            //Find most recent answers by this user:
            $user_private_replies = $this->Chains->read(array(
                'chainusertype' => 4228,
                'chainpostoutput' => $i['postid'],
                'chainusercreator' => $chainusercreator,
            ), array('chainpostinput'), 0, 1, array('chainid' => 'DESC'));


            //All validated, lets create the new post:
            if (strlen($user_submitted_data['post_createtext'])) {

                if (count($user_private_replies)) {

                    //Update existing response if different:
                    if ($user_submitted_data['post_createtext'] != $user_private_replies[0]['posttext']) {

                        $this->Posts->update($user_private_replies[0]['postid'], array(
                            'posttext' => $user_submitted_data['post_createtext'],
                        ), $chainusercreator);

                    }

                    $this_postid = $user_private_replies[0]['postid'];

                } else {

                    //Create a new post:
                    $post_new = $this->Posts->create(array(
                        'posttext' => $user_submitted_data['post_createtext'],
                    ), $chainusercreator);

                    $this_postid = $post_new['post_create']['postid'];

                    //Chain to this post:
                    $this->Chains->create(array(
                        'chainusertype' => 4228,
                        'chainusercreator' => $chainusercreator,
                        'chainpostoutput' => $i['postid'],
                        'chainpostinput' => $post_new['post_create']['postid'],
                    ));

                }

            } elseif (count($user_private_replies)) {

                if ($is_required) {
                    return array(
                        'status' => 0,
                        'message' => 'Resposne is required',
                    );
                } else {
                    //Delete Chains
                    $chains_removed = $this->Posts->delete($user_private_replies[0]['postid'], $chainusercreator);
                }

            }

        }

        $x_data['chainusercreator'] = $chainusercreator;
        $x_data['chainuserinput'] = $chainusercreator;
        $x_data['chainusertype'] = $chainusertype;
        $x_data['chainpostinput'] = $i['postid']; //Always add Post to chainpostinput

        //Add chain right only if we have a target post we are navigating to
        if ($target_postid > 0 && (!isset($x_data['chainpostoutput']) || !intval($x_data['chainpostoutput']))) {
            $x_data['chainpostoutput'] = $target_postid;
        }

        if (!isset($x_data['chainvalue'])) {
            $x_data['chainvalue'] = null;
        }

        $es_creator = $this->Users->read(array(
            'userid' => $chainusercreator,
        ));

        //Make sure not duplicate:
        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'chainpostinput' => (isset($x_data['chainpostinput']) ? $x_data['chainpostinput'] : 0),
            'chainpostoutput' => (isset($x_data['chainpostoutput']) ? $x_data['chainpostoutput'] : 0),
            'chainusercreator' => $chainusercreator,
            'chainvalue' => $x_data['chainvalue'],
        )) as $already_discovered) {

            //Update:
            $this->Chains->update($already_discovered['chainid'], $x_data);

            //Already post discovered!
            return array(
                'status' => 1,
                'message' => 'Already discovered',
                'new_x' => $already_discovered,
            );
        }

        //Add new Chain:
        $domain_url = get_domain('m__message', $chainusercreator);

        //Create Chain:
        $new_x = $this->Chains->create($x_data);

        //Auto Complete OR Answers:
        if ($input__selection) {
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___7704')) . ')' => null, //Discovery Expansion
                'chainusercreator' => $x_data['chainusercreator'],
                'chainpostinput' => $i['postid'],
            ), array('chainpostoutput'), 0) as $next_i) {

                if (count($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                    'chainpostoutput' => $next_i['postid'],
                    'chainuserinput IN (' . join(',', $this->config->item('userids___43039')) . ')' => null,
                )))) {
                    continue;
                }

                $has_children = count($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
                    'chainpostinput' => $next_i['postid'],
                ), array('chainpostoutput'), 0, 0));

                if (!$has_children) {
                    //Mark as complete:
                    $this->Chains->post_discovered(4559, $x_data['chainusercreator'], $target_postid, $next_i, $x_data);
                }
            }
        }

        if ($x_data['chainusercreator'] && in_array($x_data['chainusertype'], $this->config->item('userids___40986'))) {

            //Discovery Triggers?
            $clone_urls = '';
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___32275')) . ')' => null, //DISCOVERY TRIGGERS
                'chainpostinput' => $i['postid'],
            ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC')) as $clone_i) {

                if ($clone_i['chainusertype'] == 32247) {

                    //Discovery Clone
                    $new_title = $es_creator[0]['username'] . ' ' . $clone_i['posttext'];
                    $result = $this->Posts->copy($clone_i['postid'], 0, $x_data['chainusercreator'], null, $new_title);
                    if ($result['status']) {

                        //Add as watcher:
                        $this->Chains->create(array(
                            'chainusertype' => 10573, //WATCHERS
                            'chainusercreator' => $x_data['chainusercreator'],
                            'chainuserinput' => $x_data['chainusercreator'],
                            'chainpostoutput' => $result['post_createid'],
                        ));

                        //New chain:
                        $clone_urls .= $new_title . ':' . "\n" . 'https://' . get_domain('m__message', $x_data['chainusercreator']) . view_memory(42903, 33286) . $result['post_createpost'] . "\n\n";
                    }

                } elseif ($clone_i['chainusertype'] == 32304) {

                    //Discovery Forget: Remove all Discoveries made by this user:
                    foreach ($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                        'chainpostinput' => $i['postid'],
                        'chainusercreator' => $x_data['chainusercreator'],
                    )) as $remove_x) {
                        $this->Chains->delete($remove_x['chainid'], $x_data['chainusercreator']);
                    }

                }

            }

            if (strlen($clone_urls)) {
                //Send DM with all the new clone post URLs:
                $clone_urls = $clone_urls . 'You have been added as a subscriber so you will be notified when anyone start using your chain.';
                $post_title = view_post_title($i, true);
                $this->Chains->message($x_data['chainusercreator'], $post_title, $clone_urls);
                //Also DM all watchers of the post:
                foreach ($this->Chains->read(array(
                    'chainusertype' => 10573, //WATCHERS
                    'chainpostoutput' => $i['postid'],
                ), array(), 0) as $watcher) {
                    $this->Chains->message($watcher['chainuserinput'], $post_title, $clone_urls);
                }
            }


            //ADD PROFILE?
            foreach ($this->Chains->read(array(
                'chainusertype' => 7545, //Following Add
                'chainpostoutput' => $i['postid'],
            ), array('chainuserinput')) as $this_tag) {

                //Check if special profile add?
                if (in_array($this_tag['chainuserinput'], $this->config->item('userids___43048'))) {

                    //Special Addition:

                    if ($this_tag['chainuserinput'] == 6197 && strlen(trim($x_data['chainvalue'])) >= 2) {

                        //Update User Title:
                        $this->Users->update($x_data['chainusercreator'], array(
                            'username' => $x_data['chainvalue'],
                        ), $x_data['chainusercreator']);

                        //Update live session as well:
                        $es_creator[0]['username'] = $x_data['chainvalue'];
                        $this->Users->activate($es_creator[0], true);

                    }

                } else {

                    //Assign tag if following/follower Chain NOT previously assigned:
                    $append_user = append_user($this_tag['chainuserinput'], $x_data['chainusercreator'], (isset($user_submitted_data['post_createtext']) ? $user_submitted_data['post_createtext'] : null), $i['postid']);

                    //See if Session needs to be updated:
                    if ($user_session && $user_session['userid']==$x_data['chainusercreator'] && $append_user) {
                        $this->Users->activate($user_session, true);
                    }

                }
            }


            //REMOVE PROFILE?
            foreach ($this->Chains->read(array(
                'chainusertype' => 26599, //Following Remove
                'chainpostoutput' => $i['postid'],
            )) as $this_tag) {

                //Remove Following IF previously assigned:
                foreach ($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                    'chainuserinput' => $this_tag['chainuserinput'], //CERTIFICATES saved here
                    'chainuseroutput' => $x_data['chainusercreator'],
                )) as $existing_x) {

                    $this->Chains->delete($existing_x['chainid'], $x_data['chainusercreator']);

                    //See if Session needs to be updated:
                    if ($user_session && $user_session['userid'] == $x_data['chainusercreator']) {
                        //Yes, update session:
                        $this->Users->activate($es_creator[0], true);
                    }
                }
            }


            //Notify watchers IF any:
            $watchers = $this->Chains->read(array(
                'chainusertype' => 10573, //WATCHERS
                'chainpostoutput' => $i['postid'],
            ), array(), 0);
            if (count($watchers)) {

                $es_discoverer = $this->Users->read(array(
                    'userid' => $x_data['chainusercreator'],
                ));
                if (count($es_discoverer)) {

                    //Fetch Discoverer contact:
                    $discoverer_contact = '';
                    foreach ($this->config->item('users___34541') as $chainusertype => $m) {
                        foreach ($this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                            'chainuseroutput' => $x_data['chainusercreator'],
                            'chainuserinput' => $chainusertype,
                            'LENGTH(chainvalue)>0' => null,
                        )) as $x_progress) {
                            $discoverer_contact .= $m['m__title'] . ':' . "\n" . $x_progress['chainvalue'] . "\n\n";
                        }
                    }

                    //Notify Post Watchers
                    $sent_watchers = array();
                    foreach ($watchers as $watcher) {
                        if (!in_array(intval($watcher['chainuserinput']), $sent_watchers)) {
                            array_push($sent_watchers, intval($watcher['chainuserinput']));

                            $this->Chains->message($watcher['chainuserinput'], $es_discoverer[0]['username'] . ' post_discovered: ' . view_post_title($i, true),
                                //Message Body:
                                view_post_title($i, true) . ':' . "\n" . 'https://' . $domain_url . view_memory(42903, 33286) . $i['posthashtag'] . "\n\n" .
                                (strlen($x_data['chainvalue']) ? $x_data['chainvalue'] . "\n\n" : '') .
                                $es_discoverer[0]['username'] . ':' . "\n" . 'https://' . $domain_url . view_memory(42903, 42902) . $es_discoverer[0]['userhandle'] . "\n\n" .
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


    function history($i, $userid, $current_level = 0)
    {

        unset($i['postexternal']);
        unset($i['postedit']);
        unset($i['chainusertype']);
        unset($i['chainuserinput']);
        unset($i['chainuseroutput']);
        unset($i['chainkey']);
        unset($i['chainuserdomain']);
        unset($i['chainvoid']);
        unset($i['chainusercreator']);
        unset($i['chainpostinput']);
        unset($i['chainpostoutput']);
        unset($i['chainid']);
        unset($i['chainvalue']);

        $input__selection = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___7712')) . ')' => null,
        )));
        $input__text = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___43002')) . ')' => null,
        )));
        $i['user_discovered'] = array();
        $i['user_written_response'] = array();
        $i['current_level'] = $current_level;
        $i['next_posts'] = array();
        $current_level++;

        //TODO Append media

        //Append Discovery if any:
        foreach ($this->Chains->read(array(
            'chainpostinput' => $i['postid'],
            'chainusercreator' => $userid,
            'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
        ), array(), 1) as $x) {

            unset($x['chainusertype']);
            unset($x['chainuserinput']);
            unset($x['chainuseroutput']);
            unset($x['chainkey']);
            unset($x['chainuserdomain']);
            unset($x['chainvoid']);
            unset($x['chainpostinput']);
            unset($x['chainpostoutput']);
            unset($x['chainusercreator']);
            unset($x['chainvalue']);
            unset($x['chainid']);

            $i['user_discovered'] = $x;

            if ($input__text) {
                //Since it has been post discovered and its a text input, lots fetch the written response:
                foreach ($this->Chains->read(array(
                    'chainusertype' => 4228,
                    'chainpostoutput' => $i['postid'],
                    'chainusercreator' => $userid,
                ), array('chainpostinput'), 0, 1, array('chainid' => 'DESC')) as $response) {
                    $i['user_written_response'] = $response;
                }
            }
        }


        if ($i['user_discovered']) {
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
                'chainpostinput' => $i['postid'],
            ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC')) as $next_i) {
                array_push($i['next_posts'], $this->Chains->history($next_i, $userid, $current_level));
            }
        }


        return $i;

    }

    function history_discovered($i, $userid, $current_level = 0)
    {

        $input__selection = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___7712')) . ')' => null,
        )));
        $input__text = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___43002')) . ')' => null,
        )));
        $i['current_level'] = $current_level;
        $i['next_posts'] = array();
        $i['user_discovered'] = array();
        $i['user_written_response'] = array();
        $current_level++;

        //Append Discovery if any:
        foreach ($this->Chains->read(array(
            'chainpostinput' => $i['postid'],
            'chainusercreator' => $userid,
            'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
        ), array(), 1) as $x) {
            $i['user_discovered'] = $x;
        }

        if ($input__text) {
            foreach ($this->Chains->read(array(
                'chainusertype' => 4228,
                'chainpostoutput' => $i['postid'],
                'chainusercreator' => $userid,
            ), array('chainpostinput'), 0, 1, array('chainid' => 'DESC')) as $response) {
                $i['user_written_response'] = $response;
            }
        }


        if ($i['user_discovered']) {
            foreach (($input__selection ? $this->Chains->read(array(
                'chainusertype' => 7712, //Input Choice
                'chainusercreator' => $userid,
                'chainpostinput' => $i['postid'],
            ), array('chainpostoutput')) : $this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
                'chainpostinput' => $i['postid'],
            ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC'))) as $next_i) {
                array_push($i['next_posts'], $this->Chains->history_discovered($next_i, $userid, $current_level));
            }
        }


        return $i;

    }

    function flat_tree($i, $current_level = 0, $previous_input__selection = false)
    {

        $total_next = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
            'chainpostinput' => $i['postid'],
        ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false);
        $input__selection = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___7712')) . ')' => null,
        )));
        $single_choice = count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___33331')) . ')' => null,
        )));

        if(isset($_GET['skip_config'])) {
            unset($i['postexternal']);
            unset($i['postweight']);
            unset($i['postedit']);
            if(isset($i['chainid'])){
                unset($i['chainuserdomain']);
                unset($i['chainusercreator']);
                unset($i['chainusertype']);
                unset($i['chainuserinput']);
                unset($i['chainuseroutput']);
                unset($i['chainpostinput']);
                unset($i['chainpostoutput']);
                unset($i['chainkey']);
                unset($i['chainvalue']);
                unset($i['chainvoid']);
                unset($i['chainprevious']);
                unset($i['chainhash']);
            }
        } else {

            $i['current_level'] = $current_level;
            $is_required = count($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostoutput' => $i['postid'],
                'chainuserinput' => 28239, //Required
            )));

            $min_steps = ($input__selection ? ($is_required ? 1 : 0) : count($total_next)); //Can be improved later...
            $max_steps = ($input__selection ? ($single_choice ? 1 : count($total_next)) : count($total_next));
            $i['post_list_config'] = post_list_config($i['postid'], false);
            $i['stats'] = array(
                'max_level' => $current_level,
                'all_steps' => 1,
                'min_steps' => $min_steps,
                'max_steps' => $max_steps,
                'min_choices' => (!$previous_input__selection && $input__selection && count($total_next) ? 1 : 0),
                'max_choices' => ($input__selection && count($total_next) ? 1 : 0),
            );
        }

        $i['next_posts'] = array();
        $current_level++;

        //Append Total Discoveries if any:
        if(!isset($_GET['skip_config'])) {
            $sub_counter = $this->Chains->read(array(
                'chainpostinput' => $i['postid'],
                'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');
            $i['post_count_discovery'] = $sub_counter[0]['totals'];
        }


        foreach ($total_next as $next_i) {

            $result_i = $this->Chains->flat_tree($next_i, $current_level, ($previous_input__selection ? $previous_input__selection : $input__selection));

            array_push($i['next_posts'], $result_i);

            if(!isset($_GET['skip_config'])) {
                $i['stats']['all_steps'] += $result_i['stats']['all_steps'];
                $i['stats']['max_steps'] += $result_i['stats']['max_steps'];
                $i['stats']['min_choices'] += $result_i['stats']['min_choices'];
                $i['stats']['max_choices'] += $result_i['stats']['max_choices'];

                if ($result_i['stats']['max_level'] > $i['stats']['max_level']) {
                    $i['stats']['max_level'] = $result_i['stats']['max_level'];
                }
                if (!$input__selection || $is_required) {
                    $i['stats']['min_steps'] += $result_i['stats']['min_steps'];
                }
            }

        }

        return $i;

    }


    function progress($userid, $i, $current_level = 0, $loop_breaker_ids = array())
    {

        if (count($loop_breaker_ids) > 0 && in_array($i['postid'], $loop_breaker_ids)) {
            return false;
        }

        $copy = $this->Posts->ids($i, 'AND');
        if (!isset($copy['recursive_post_ids']) || !count($copy['recursive_post_ids'])) {
            return false;
        }

        $current_level++;
        array_push($loop_breaker_ids, intval($i['postid']));

        //Count completed:
        $list_discovered = array();
        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'chainusercreator' => $userid, //Belongs to this Member
            'chainpostinput IN (' . join(',', $copy['recursive_post_ids']) . ')' => null,
        ), array('chainpostinput'), 0) as $completed) {
            if (!in_array($completed['posthashtag'], $list_discovered)) {
                array_push($list_discovered, $completed['posthashtag']);
            }
        }


        //Calculate common steps and expansion steps recursively for this u:
        $metadata_this = array(
            'fixed_total' => count($copy['recursive_post_ids']),
            'list_total' => $copy['recursive_post_ids'],
            'fixed_discovered' => count($list_discovered),
            'list_discovered' => $list_discovered,
        );

        //Now let's check possible expansions:
        if (count($copy['recursive_post_ids'])) {
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___7704')) . ')' => null, //Discovery Expansion
                'chainusercreator' => $userid, //Belongs to this Member
                'chainpostinput IN (' . join(',', $copy['recursive_post_ids']) . ')' => null,
            ), array('chainpostoutput')) as $expansion_in) {

                //Fetch recursive:
                $progress = $this->Chains->progress($userid, $expansion_in, $current_level, $loop_breaker_ids);

                if (!$progress && !count($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                        'chainusercreator' => $userid, //Belongs to this Member
                        'chainpostinput' => $expansion_in['postid'],
                    )))) {
                    $progress = array(
                        'fixed_total' => 1,
                        'list_total' => array($expansion_in['postid']),
                        'fixed_discovered' => 0,
                        'list_discovered' => array(),
                    );
                }

                //Addup completion stats for this:
                $metadata_this['fixed_total'] += $progress['fixed_total'];
                $metadata_this['fixed_discovered'] += $progress['fixed_discovered'];

                if ($progress['list_total'] && count($progress['list_total'])) {
                    foreach ($progress['list_total'] as $tree_id) {
                        if (!in_array($tree_id, $metadata_this['list_total'])) {
                            array_push($metadata_this['list_total'], $tree_id);
                        }
                    }
                }

                if ($progress['list_discovered'] && count($progress['list_discovered'])) {
                    foreach ($progress['list_discovered'] as $tree_id) {
                        if (!in_array($tree_id, $metadata_this['list_discovered'])) {
                            array_push($metadata_this['list_discovered'], $tree_id);
                        }
                    }
                }
            }
        }

        if ($current_level == 1) {

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
            if ($metadata_this['fixed_total'] > 0) {
                $metadata_this['fixed_completed_percentage'] = intval(floor($metadata_this['fixed_discovered'] / $metadata_this['fixed_total'] * 100));
            }


        }

        //Return results:
        return $metadata_this;

    }


}