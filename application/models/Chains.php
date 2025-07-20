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
        if (!isset($add_fields['chainhandletype']) || ($add_fields['chainhandletype']!=44395 && !in_array($add_fields['chainhandletype'], $this->config->item('handleids___4593')))) {
            log_error('Chains->create() failed to create because of invalid Chain type @' . $add_fields['chainhandletype'], array(
                'chainhandlecreator' => $add_fields['chainhandlecreator'],
                'chainhandleoutput' => $add_fields['chainhandletype'],
            ));
            return false;
        }

        //Set some defaults:
        if (!isset($add_fields['chainhandlecreator']) || intval($add_fields['chainhandlecreator']) < 1) {
            $add_fields['chainhandlecreator'] = 14068; //GUEST MEMBER
        }

        //Set some defaults:
        if (!isset($add_fields['chainvalue'])) {
            $add_fields['chainvalue'] = null;
        } elseif (is_array($add_fields['chainvalue'])) {
            $add_fields['chainvalue'] = serialize($add_fields['chainvalue']);
        }

        //Set some zero defaults if not set:
        foreach (array('chainhashtagoutput', 'chainhashtaginput', 'chainhandleoutput', 'chainhandleinput', 'chainkey') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Is this an observation chain that should replace an older observation, if any:
        if($update_observed && in_array($add_fields['chainhandletype'], $this->config->item('handleids___1308453'))){
            $read_fields = $add_fields;
            if(isset($read_fields['chainvalue'])){
                unset($read_fields['chainvalue']);
            }
            foreach ($this->Chains->read($read_fields, array(), 1) as $last_observation) {
                //Update the previous observed chain:
                return $this->Chains->update($last_observation['chainid'], $add_fields);
            }
        }

        //Append Domain:
        if (!isset($add_fields['chainhandledomain']) || $add_fields['chainhandledomain'] < 1) {
            $add_fields['chainhandledomain'] = website_setting(0, $add_fields['chainhandlecreator']);
        }

        //Append time:
        if (!isset($add_fields['chaintime']) || is_null($add_fields['chaintime'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $add_fields['chaintime'] = $d->format("Y-m-d H:i:s");
        }

        //Let's log, Always auto generated:
        $insert_chain_id = ( isset($add_fields['chainid']) ? $add_fields['chainid'] : 0 );
        $add_fields['chainprevious'] = chainprevious();
        $add_fields['chainhash'] = chainhash($add_fields);
        $this->db->insert('ideachain', $add_fields);

        //Fetch inserted id:
        $add_fields['chainid'] = ( $insert_chain_id>0 ? $insert_chain_id : $this->db->insert_id() );

        //All good?
        if ($add_fields['chainid'] < 1) {
            log_error('Chains->create() Failed to create', array(
                'chainhandlecreator' => $add_fields['chainhandlecreator'],
                'chainhandleoutput' => $add_fields['chainhandlecreator'],
            ));
            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['chainhandleinput'] > 0) {
                update_algolia(12274, $add_fields['chainhandleinput']);
            }
            if ($add_fields['chainhandleoutput'] > 0) {
                update_algolia(12274, $add_fields['chainhandleoutput']);
            }
            if ($add_fields['chainhashtaginput'] > 0) {
                update_algolia(12273, $add_fields['chainhashtaginput']);
            }
            if ($add_fields['chainhashtagoutput'] > 0) {
                update_algolia(12273, $add_fields['chainhashtagoutput']);
            }
        }


        //See if this Chain type has any followers that are essentially subscribed to it:
        $tr_watchers = $this->Handles->tree(42381, $add_fields['chainhandletype'], $this->config->item('handleids___30820'), array(), 1);
        if (is_array($tr_watchers) && count($tr_watchers)) {

            //yes, start drafting email to be sent to them
            $u_name = 'Unknown';
            if ($add_fields['chainhandlecreator'] > 0) {
                //Fetch member details:
                $add_e = $this->Handles->read(array(
                    'handleid' => $add_fields['chainhandlecreator'],
                ));
                if (count($add_e)) {
                    $u_name = $add_e[0]['handlevalue'];
                }
            }

            //Email Subject:
            $handles___4593 = $this->config->item('handles___4593'); //Chain Types
            $subject = $u_name . ' ' . $handles___4593[$add_fields['chainhandletype']]['m__title'];

            //Compose email body, start with Chain content:
            $html_message = (strlen($add_fields['chainvalue']) > 0 ? $add_fields['chainvalue'] : '') . "\n";


            //Append Chain object Chains:
            foreach ($this->config->item('handles___4341') as $handleid => $m) {

                if (in_array(6202, $m['m__following'])) {

                    //HASHTAG
                    foreach ($this->Hashtags->read(array('hashtagid' => $add_fields[$m['m__handle']])) as $this_i) {
                        $html_message .= $m['m__title'] . ': ' . view_hashtag_title($this_i, true) . ':' . "\n" . $this->config->item('base_url') . view_memory(42903, 33286) . $this_i['hashtagstring'] . "\n\n";
                    }

                } elseif (in_array(6160, $m['m__following'])) {

                    //HANDLE
                    foreach ($this->Handles->read(array('handleid' => $add_fields[$m['m__handle']])) as $this_e) {
                        $html_message .= $m['m__title'] . ': ' . $this_e['handlevalue'] . "\n" . $this->config->item('base_url') . view_memory(42903, 42902) . $this_e['handlestring'] . "\n\n";
                    }

                } elseif (in_array(4367, $m['m__following'])) {

                    //DISCOVERY
                    $html_message .= $m['m__title'] . ':' . "\n" . $this->config->item('base_url') . view_app_chain(12722) . '?chainid=' . $add_fields[$m['m__handle']] . "\n\n";

                }

            }

            //Finally append DISCOVERY ID:
            $html_message .= 'Chain: #' . $add_fields['chainid'] . "\n" . $this->config->item('base_url') . view_app_chain(12722) . '?chainid=' . $add_fields['chainid'] . "\n\n";

            //Message Watchers:
            foreach ($tr_watchers as $tr_watcher) {
                //Do not inform the member who just took the action:
                if ($tr_watcher['handleid'] != $add_fields['chainhandlecreator']) {
                    $this->Chains->message($tr_watcher['handleid'], $subject, $html_message, array(
                        'chainhashtagoutput' => $add_fields['chainhashtagoutput'],
                        'chainhashtaginput' => $add_fields['chainhashtaginput'],
                        'chainhandleoutput' => $add_fields['chainhandleoutput'],
                        'chainhandleinput' => $add_fields['chainhandleinput'],
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
        $this->db->from('ideachain');

        //HASHTAG JOIN?
        $hashtag_join = false;
        if (in_array('chainhashtaginput', $joins_objects)) {
            $hashtag_join = true;
            $this->db->join('ideachainhashtags', 'chainhashtaginput=hashtagid', 'left');
        } elseif (in_array('chainhashtagoutput', $joins_objects)) {
            $hashtag_join = true;
            $this->db->join('ideachainhashtags', 'chainhashtagoutput=hashtagid', 'left');
        } elseif (in_array('chainhashtagid', $joins_objects)) {
            $hashtag_join = true;
            $this->db->join('ideachainhashtags', 'chainid=hashtagid', 'left');
        }

        //PLAYER JOIN?
        $handle_join = false;
        if (in_array('chainhandleinput', $joins_objects)) {
            $handle_join = true;
            $this->db->join('ideachainhandles', 'chainhandleinput=handleid', 'left');
        } elseif (in_array('chainhandleoutput', $joins_objects)) {
            $handle_join = true;
            $this->db->join('ideachainhandles', 'chainhandleoutput=handleid', 'left');
        } elseif (in_array('chainhandletype', $joins_objects)) {
            $handle_join = true;
            $this->db->join('ideachainhandles', 'chainhandletype=handleid', 'left');
        } elseif (in_array('chainhandlecreator', $joins_objects)) {
            $handle_join = true;
            $this->db->join('ideachainhandles', 'chainhandlecreator=handleid', 'left');
        } elseif (in_array('chainhandledomain', $joins_objects)) {
            $handle_join = true;
            $this->db->join('ideachainhandles', 'chainhandledomain=handleid', 'left');
        } elseif (in_array('chainhandleid', $joins_objects)) {
            $handle_join = true;
            $this->db->join('ideachainhandles', 'chainid=handleid', 'left');
        }

        $chain_void_found = false;
        foreach ($query_filters as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
            if (substr_count($key, 'chainvoid')) {
                $chain_void_found = true;
            }
        }
        if (!$chain_void_found) {
            //Auto add:
            $this->db->where('chainvoid', 0); //Not Void
        }
        if($hashtag_join){
            $this->db->where('hashtagid >', 0);
        }
        if($handle_join){
            $this->db->where('handleid >', 0);
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
            if (array_intersect(array('chainhashtaginput', 'chainhashtagoutput'), $joins_objects)) {
                //Hashtag results:
                foreach ($results as $key => $value) {
                    if (!hashtag_access(null, $value['hashtagid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            } elseif (array_intersect(array('chainhandleinput', 'chainhandleoutput'), $joins_objects)) {
                //Handle results:
                foreach ($results as $key => $value) {
                    if (!handle_access(null, $value['handleid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            }
        }

        return $results;

    }


    function update($chainid, $update_columns, $chainhandlecreator = 0)
    {

        //Fetch Chain before updating:
        foreach ($this->Chains->read(array(
            'chainid' => $chainid,
        )) as $old_x) {

            if (!isset($update_columns['chainhandlecreator'])) {
                //Fetch session handle:
                $update_columns['chainhandlecreator'] = ($chainhandlecreator > 0 ? $chainhandlecreator : $old_x['chainhandlecreator'] );
            }

            //Make sure something changed:
            $something_changed = false;
            foreach(array('chainhandletype','chainhandleinput','chainhandleoutput','chainhashtaginput','chainhashtagoutput','chainkey','chainvalue','chainvoid') as $must_change){
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
            $new_x = $this->Chains->create($update_columns, true, false);

            if ($new_x['chainid'] > 0) {
                //Void Old Chain:
                $this->db->query("UPDATE ideachain SET chainvoid = " . $new_x['chainid'] . " WHERE chainid = " . $chainid . ";");
                return $this->db->affected_rows();
            }

        }

        //Invalid chain:
        return 0;

    }


    function delete($chainid, $chainhandlecreator = 0)
    {

        //Validate $chainid
        foreach ($this->Chains->read(array(
            'chainid' => $chainid,
        )) as $old_x) {

            //Set default handle:
            if (!$chainhandlecreator) {
                //Fetch session handle:
                $handle_session = handle_session();
                $chainhandlecreator = ($handle_session ? $handle_session['handleid'] : ($old_x['chainhandlecreator'] > 0 ? $old_x['chainhandlecreator'] : 14068 /* Guest Member */));
            }

            $new_x = $this->Chains->create(array(
                'chainhandlecreator' => $chainhandlecreator,
                'chainhandletype' => 44395, //CHAIN VOID
                'chainvoid' => $chainid, //We insert as void since this is a void chain only
            ));

            if (!isset($new_x['chainid'])) {
                return 0; //Should not happen
            }

            //Void this Chain:
            $this->db->query("UPDATE ideachain SET chainvoid = " . $new_x['chainid'] . " WHERE chainid = " . $chainid . ";");
            return $this->db->affected_rows();

        }

        //Invalid chain:
        return 0;

    }

    function readalt($query_filters = array(), $joins_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('chain_id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->dbalt = $this->load->database('alt', TRUE);
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


    function select($focus__id, $o__id, $element_id, $handle_createid, $migratehandle, $chainid = 0)
    {

        //Authenticate Member:
        $migratehandle = trim(substr($migratehandle, 0, 1) == '@' ? trim(substr($migratehandle, 1)) : $migratehandle);
        $migratehandle = trim(substr($migratehandle, 0, 1) == '#' ? trim(substr($migratehandle, 1)) : $migratehandle);
        $handle_session = handle_session();
        if (!$handle_session) {
            return array(
                'status' => 0,
                'message' => blocked_reasoning(),
            );
        } elseif (intval($o__id) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Target ID',
            );
        } elseif (intval($element_id) < 1 || !count($this->config->item('handleids___' . $element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Variable ID [' . $element_id . ']',
            );
        } elseif (intval($handle_createid) < 1 || !in_array($handle_createid, $this->config->item('handleids___' . $element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            );
        }


        //See if anything is being deleted:
        $auto_open_hashtag_modal = 0;
        $delete_redirect = null;
        $delete_element = null;
        $chains_removed = -1;
        $status = 0;
        $delete_redirect = '';
        $delete_element = '';

        if ($element_id == 4486 && $chainid > 0) {

            //HASHTAG CHAIN TYPE
            $status = $this->Chains->update($chainid, array(
                'chainhandlecreator' => $handle_session['handleid'],
                'chainhandletype' => $handle_createid,
            ));

        } elseif ($element_id == 13550 && $chainid > 0) {

            //HANDLE CHAIN TYPE
            $status = $this->Chains->update($chainid, array(
                'chainhandletype' => $handle_createid,
                'chainhandlecreator' => $handle_session['handleid'],
            ));

        } elseif ($element_id == 32292 && $chainid > 0) {

            //HANDLE/HANDLE CHAIN
            $status = $this->Chains->update($chainid, array(
                'chainhandletype' => $handle_createid,
                'chainhandlecreator' => $handle_session['handleid'],
            ));

        } elseif (0 && $element_id == 42795 && $o__id > 0 && $handle_createid && $handle_session) {

            if (!$chainid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Chains->read(array(
                    'chainhandleinput' => $o__id,
                    'chainhandleoutput' => $handle_session['handleid'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___42795')) . ')' => null, //Follow
                ), array(), 1) as $found_x) {
                    $chainid = $found_x['chainid'];
                }
            }

            //Follow
            if ($chainid > 0) {
                //Updating reaction:
                if (in_array($handle_createid, $this->config->item('handleids___42850'))) {
                    //Unsubscribe
                    $status = $this->Chains->delete($chainid, $handle_session['handleid']); //Media Removed
                } else {
                    $status = $this->Chains->update($chainid, array(
                        'chainhandletype' => $handle_createid,
                        'chainhandlecreator' => $handle_session['handleid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Chains->create(array(
                    'chainhandlecreator' => $handle_session['handleid'],
                    'chainhandleinput' => $o__id,
                    'chainhandleoutput' => $handle_session['handleid'],
                    'chainhandletype' => $handle_createid,
                )));
            }

        } elseif ($element_id == 42260 && $o__id > 0 && $handle_createid && $handle_session) {

            //Check if current value?
            if (!$chainid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Chains->read(array(
                    'chainhandleinput' => $handle_session['handleid'],
                    'chainhashtagoutput' => $o__id,
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___42260')) . ')' => null, //Reactions
                ), array(), 1) as $found_x) {
                    $chainid = $found_x['chainid'];
                }
            }

            //Reactions...
            if ($chainid > 0) {
                if (in_array($handle_createid, $this->config->item('handleids___42850'))) {
                    $status = $this->Chains->delete($chainid, $handle_session['handleid']); //Removed
                } else {
                    //Updating reaction:
                    $status = $this->Chains->update($chainid, array(
                        'chainhandletype' => $handle_createid,
                        'chainhandlecreator' => $handle_session['handleid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Chains->create(array(
                    'chainhandlecreator' => $handle_session['handleid'],
                    'chainhandleinput' => $handle_session['handleid'],
                    'chainhashtagoutput' => $o__id,
                    'chainhandletype' => $handle_createid,
                )));
            }

        } elseif ($element_id == 4737) {

            //Hashtag Type
            $status = $this->Hashtags->update($o__id, array(
                'hashtagtype' => $handle_createid,
            ), $handle_session['handleid']);

            //See if we need to popup the hashtag edit modal here:

            $handles___42179 = $this->config->item('handles___42179'); //Dynamic Input Fields
            foreach (array_intersect($this->config->item('handleids___' . $handle_createid), $this->config->item('handleids___42179')) as $dynamic_handleid) {

                $superpowers_required = array_intersect($this->config->item('handleids___10957'), $handles___42179[$dynamic_handleid]['m__following']);
                if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
                    continue;
                }

                //Let's determine the data type:
                $data_types = array_intersect($handles___42179[$dynamic_handleid]['m__following'], $this->config->item('handleids___4592'));

                //ASSUME that we found 1 match as expected:
                foreach ($data_types as $data_type_this) {
                    $data_type = $data_type_this;
                    break;
                }
                $is_required = in_array($dynamic_handleid, $this->config->item('handleids___28239')); //Required Settings

                if (!$is_required) {
                    //We are only interested in what is required
                    continue;
                }

                //See if we are missing value:
                if (in_array($data_type, $this->config->item('handleids___42188'))) {

                    //Single or Multiple Choice:
                    $already_responded = count($this->Chains->read(array(
                        'chainhandleinput IN (' . join(',', $this->config->item('handleids___' . $dynamic_handleid)) . ')' => null, //All possible answers
                        'chainhashtagoutput' => $o__id,
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
                    )));

                } else {

                    $already_responded = count($this->Chains->read(array(
                        'chainhandleinput' => $dynamic_handleid,
                        'chainhashtagoutput' => $o__id,
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
                    )));

                }

                if (!$already_responded) {
                    //We are missing a required response, auto open modal:
                    $auto_open_hashtag_modal = 1;
                }

            }

        }

        return array(
            'status' => intval($status) && ($chains_removed < 0 || $chains_removed > 0),
            'message' => 'Delete status [' . $status . '] with ' . $chains_removed . ' Chains removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
            'auto_open_hashtag_modal' => $auto_open_hashtag_modal,
        );

    }

    function message($handleid, $subject, $html_message, $x_data = array(), $template_hashtagid = 0, $chainhandledomain = 0, $log_tr = true, $demo_only = false)
    {

        $sms_subscriber = false;

        //Bypass notifications?
        if (!count($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42256')) . ')' => null, //Writes
            'chainhandleinput' => 31779, //Mandatory Emails
            'chainhashtagoutput' => $template_hashtagid,
        )))) {

            $notification_levels = $this->Chains->read(array(
                'chainhandleinput IN (' . join(',', $this->config->item('handleids___30820')) . ')' => null, //Active Subscriber
                'chainhandleoutput' => $handleid,
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ));
            if (!count($notification_levels)) {
                return array(
                    'status' => 0,
                    'message' => 'User is not an active subscriber',
                );
            }
            $sms_subscriber = in_array($notification_levels[0]['chainhandleinput'], $this->config->item('handleids___28915'));
        }

        //Make sure not recently contacted:
        /*
         * Did not work with subscription notifications which could happen back to back...
         *
        $minutes_limit = 60;
        foreach($this->Chains->read(array(
            'chainhandletype' => 29399,
            'chainhandlecreator' => $handleid,
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
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            'chainhandleinput' => 3288, //Email
            'chainhandleoutput' => $handleid,
        )) as $handle_data) {

            if (!filter_var($handle_data['chainvalue'], FILTER_VALIDATE_EMAIL)) {
                $this->Chains->delete($handle_data['chainid'], $handleid);
                continue;
            }

            if(!in_array($handle_data['chainvalue'],$stats['email_addresses'])){
                array_push($stats['email_addresses'], $handle_data['chainvalue']);
            }
        }

        if (count($stats['email_addresses']) > 0) {
            //Send email:
            dispatch_email($stats['email_addresses'], $subject, $html_message, $handleid, $x_data, $template_hashtagid, $chainhandledomain, $log_tr, $demo_only);
        }


        //Should we send SMS?
        $twilio_account_sid = website_setting(30859);
        $twilio_auth_token = website_setting(30860);
        $twilio_from_number = website_setting(27673);
        if ($sms_subscriber && $twilio_account_sid && $twilio_auth_token && $twilio_from_number) {

            //Yes, generate message
            $sms_message = get_domain('m__title', $handleid, $chainhandledomain) . ' Emailed [' . $subject . '] to ' . join(' & ', $stats['email_addresses']) . ' (Also Check Spam)';

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n", " ", $sms_message);

            //Send SMS
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                'chainhandleinput' => 4783, //Phone
                'chainhandleoutput' => $handleid,
            )) as $handle_data) {

                $clean_number = preg_replace('/[^0-9.]+/', '', $handle_data['chainvalue']);

                foreach (explode('|||', wordwrap($sms_message, view_memory(6404, 27891), "|||")) as $single_message) {
                    if(!in_array($clean_number,$stats['sms_numbers'])){
                        $stats['phone_count']++;
                        array_push($stats['sms_numbers'], $clean_number);
                        $sms_sent = dispatch_sms($clean_number, $single_message, $handleid, $x_data, $template_hashtagid, $chainhandledomain, $log_tr, $demo_only);
                        if (!$sms_sent) {
                            //bad number, remove it:
                            $this->Chains->delete($handle_data['chainid'], $handleid);
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


    function broadcast($list_of_handleid, $i, $chainhandledomain = 0, $ensure_unhashtag_discovered = true, $demo_only = false)
    {

        $total_sent = 0;
        $chainhandledomain = ($chainhandledomain > 0 ? $chainhandledomain : (isset($i['chainhandledomain']) ? $i['chainhandledomain'] : 0));
        $subject_line = view_hashtag_title($i, true);
        $wacth_repeat_handles = array();

        foreach ($list_of_handleid as $count => $x) {

            if (in_array($x['handlestring'], $wacth_repeat_handles)) {
                //This should not happen! Report bug:
                log_error('Chains->broadcast() Detected duplicate Handle Handle Bug: ' . $x['handlestring'], array(
                    'chainhandleoutput' => $x['handleid'],
                ));
                break; //Stop sending more messages!
            }

            //Map this handle:
            array_push($wacth_repeat_handles, $x['handlestring']);


            if (!isset($x['handleid'])) {
                //Invalid input for sending:
                log_error('Chains->broadcast() Invalid Handle', array(
                    'chainhandlecreator' => $x['handleid'],
                    'chainhandleoutput' => 26582, //Messener
                ));
                continue;
                } elseif ($ensure_unhashtag_discovered && count($this->Chains->read(array(
                    'chainhashtaginput' => $i['hashtagid'],
                    'chainhandlecreator' => $x['handleid'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                )))) {
                //Already hashtag_discovered:
                continue;
            }


            $content_message = view_hashtag_value($i, $x['handleid'], true); //Hide the show more content if any
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', $content_message);
            }

            //Append children as options:
            $html_message = '';
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                'chainhashtaginput' => $i['hashtagid'],
            ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC')) as $down_or) {
                //Has this user hashtag_discovered this hashtag or no?
                $html_message .= '<div class="line">' . view_hashtag_title($down_or, true) . ':</div>';
                $html_message .= '<div class="line">' . 'https://' . get_domain('m__message', $x['handleid'], $chainhandledomain) . view_memory(42903, 33286) . $down_or['hashtagstring'] . (hashtag_is_startable($down_or) ? '/' . view_memory(6404, 4235) : '') . '?handlestring=' . $x['handlestring'] . '&time=' . time() . '&hash=' . view_hash(time() . $x['handlestring']) . '</div>';
            }

            //Where to place the next step?
            if (substr_count($content_message, 'link_here') == 1) {
                //We have direction to place the next step somewhere specific:
                $content_message = str_replace('link_here', $html_message, $content_message);
            } else {
                $content_message = $content_message . $html_message;
            }

            $message = $this->Chains->message($x['handleid'], $subject_line, $content_message, array(
                'chainhashtaginput' => $i['hashtagid'],
            ), $i['hashtagid'], $chainhandledomain, true, $demo_only);

            if ($message['status'] && !$demo_only) {
                $total_sent++;
            }

        }

        return $total_sent;
    }


    function previoushashtag($handleid, $target_hashtagstring, $focus_hashtagid, $loop_breaker_ids = array())
    {

        //echo 'Previous:'.$handleid.'/'.$target_hashtagstring.'/'.$focus_hashtagid;

        if (count($loop_breaker_ids) > 0 && in_array($focus_hashtagid, $loop_breaker_ids)) {
            return array();
        }
        array_push($loop_breaker_ids, intval($focus_hashtagid));

        //Fetch followings:
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
            'chainhashtagoutput' => $focus_hashtagid,
        ), array('chainhashtaginput')) as $hashtag_previous) {

            //Validate Selection:
            $input__selection = in_array($hashtag_previous['hashtagtype'], $this->config->item('handleids___7712'));
            $is_selected = count($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___7704')) . ')' => null, //Discovery Expansion
                'chainhashtaginput' => $hashtag_previous['hashtagid'],
                'chainhashtagoutput' => $focus_hashtagid,
                'chainhandlecreator' => $handleid,
            )));

            if ($handleid > 0 && !$is_selected && $input__selection) {
                continue;
            }

            //Did we find it?
            if ($hashtag_previous['hashtagstring'] == $target_hashtagstring) {
                return array($hashtag_previous);
            }

            //Keep looking further up:
            $website_finder = $this->Chains->previoushashtag($handleid, $target_hashtagstring, $hashtag_previous['hashtagid'], $loop_breaker_ids);
            if (count($website_finder)) {
                array_push($website_finder, $hashtag_previous);
                return $website_finder;
            }
        }

        //Did not find any followings:
        return array();

    }


    function previoushashtag_discovered($focus_hashtagid, $chainhandlecreator, $loop_breaker_ids = array())
    {

        /*
         *
         * Returns hashtag if hashtag_discovered upwards
         *
         * */

        if (count($loop_breaker_ids) > 0 && in_array($focus_hashtagid, $loop_breaker_ids)) {
            return false;
        }
        array_push($loop_breaker_ids, intval($focus_hashtagid));

        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
            'chainhashtagoutput' => $focus_hashtagid,
        ), array('chainhashtaginput')) as $prev_i) {

            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                'chainhandlecreator' => $chainhandlecreator,
                'chainhashtaginput' => $prev_i['hashtagid'],
            ), array('chainhashtagoutput')) as $x) {
                return $x['hashtagstring'];
            }

            return $this->Chains->previoushashtagstring_discovered($prev_i['hashtagid'], $chainhandlecreator, $loop_breaker_ids);
        }

        //Did not find!
        return false;

    }


    function next_hashtags($handleid, $target_hashtagstring, $i, $find_after_hashtagid = 0, $search_up = true, $target_completed = false, $loop_breaker_ids = array())
    {

        /*
        foreach ($this->Hashtags->read(array(
            'LOWER(hashtagstring)' => strtolower($target_hashtagstring),
        )) as $i_new) {
            $i = $i_new;
        }
        */

        if (count($loop_breaker_ids) > 0 && in_array($i['hashtagid'], $loop_breaker_ids)) {
            return null;
        }
        array_push($loop_breaker_ids, intval($i['hashtagid']));

        $input__selection = in_array($i['hashtagtype'], $this->config->item('handleids___7712'));
        $found_trigger = null;

        foreach ($this->Chains->read(array(
            'chainhashtaginput' => $i['hashtagid'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
        ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_hashtagid && !$found_trigger) {
                if ($next_i['hashtagid'] == $find_after_hashtagid) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            $is_selected = count($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___7704')) . ')' => null, //Discovery Expansion
                'chainhashtaginput' => $i['hashtagid'],
                'chainhashtagoutput' => $next_i['hashtagid'],
                'chainhandlecreator' => $handleid,
            )));
            if ($input__selection && !$is_selected) {
                continue;
            }


            //Return this if everything is completed, or if this is incomplete:
            if ($target_completed || !count($this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                    'chainhandlecreator' => $handleid,
                    'chainhashtaginput' => $next_i['hashtagid'],
                )))) {
                return $next_i['hashtagstring'];
            }

            //Keep looking deeper:
            $next__url = $this->Chains->next_hashtags($handleid, $target_hashtagstring, $next_i, 0, false, $target_completed, $loop_breaker_ids);
            if ($next__url) {
                return $next__url;
            }

        }


        if ($search_up && $target_hashtagstring != $i['hashtagstring']) {
            //Check Previous/Up
            $current_previous = $i['hashtagid'];
            foreach (array_reverse($this->Chains->previoushashtag($handleid, $target_hashtagstring, $i['hashtagid'])) as $p_i) {
                //Find the next siblings:
                $next__url = $this->Chains->next_hashtags($handleid, $target_hashtagstring, $p_i, $current_previous, false, $target_completed);
                if ($next__url) {
                    return $next__url;
                }
                $current_previous = $p_i['hashtagid'];
            }
        }

        //Nothing found:
        return null;

    }


    function hashtag_discovered($chainhandletype, $chainhandlecreator, $target_hashtagid = 0, $i, $handle_submitted_data = array(), $x_data = array())
    {

        if (!$chainhandlecreator || !in_array($chainhandletype, $this->config->item('handleids___31777' /* DISCOVERIES */))) {
            return log_error('hashtag_discovered() Invalid chainhandletype @' . $chainhandletype . ' missing in @31777 OR Missing $chainhandlecreator', array(
                'chainhandleoutput' => $chainhandlecreator,
                'chainhandlecreator' => $chainhandlecreator,
            ));
        }

        //Do we need to save text/upload ?
        $handle_session = handle_session();
        $input__selection = in_array($i['hashtagtype'], $this->config->item('handleids___7712'));
        $input__upload = in_array($i['hashtagtype'], $this->config->item('handleids___43004'));
        $input__text = in_array($i['hashtagtype'], $this->config->item('handleids___43002')) || in_array($i['hashtagtype'], $this->config->item('handleids___43003'));
        $is_required = count($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandleinput' => 28239, //Required
        )));


        if ($input__upload || $input__text) {

            if (!isset($handle_submitted_data['hashtag_createtext'])) {
                $handle_submitted_data['hashtag_createtext'] = null;
            }

            //Must add a new hashtag, but first let's validate the input:
            if ($i['hashtagtype'] == 31794 && strlen($handle_submitted_data['hashtag_createtext']) && !is_numeric($handle_submitted_data['hashtag_createtext'])) {
                //Number Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Number',
                );
            } elseif ($i['hashtagtype'] == 42915 && strlen($handle_submitted_data['hashtag_createtext']) && !filter_var($handle_submitted_data['hashtag_createtext'], FILTER_VALIDATE_URL)) {
                //Chain Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid URL',
                );
            } elseif ($i['hashtagtype'] == 30350 && strlen($handle_submitted_data['hashtag_createtext']) && !strtotime($handle_submitted_data['hashtag_createtext'])) {
                //Date Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Date',
                );
            }

            //Find most recent answers by this user:
            $handle_private_replies = $this->Chains->read(array(
                'chainhandletype' => 4228,
                'chainhashtagoutput' => $i['hashtagid'],
                'chainhandlecreator' => $chainhandlecreator,
            ), array('chainhashtaginput'), 0, 1, array('chainid' => 'DESC'));


            //All validated, lets create the new hashtag:
            if (strlen($handle_submitted_data['hashtag_createtext'])) {

                if (count($handle_private_replies)) {

                    //Update existing response if different:
                    if ($handle_submitted_data['hashtag_createtext'] != $handle_private_replies[0]['hashtagvalue']) {

                        $this->Hashtags->update($handle_private_replies[0]['hashtagid'], array(
                            'hashtagvalue' => $handle_submitted_data['hashtag_createtext'],
                        ), $chainhandlecreator);

                    }

                    $this_hashtagid = $handle_private_replies[0]['hashtagid'];

                } else {

                    //Create a new hashtag:
                    $hashtag_new = $this->Hashtags->create(array(
                        'hashtagvalue' => $handle_submitted_data['hashtag_createtext'],
                        'hashtagtype' => 6677,
                    ), $chainhandlecreator);

                    $this_hashtagid = $hashtag_new['hashtag_create']['hashtagid'];

                    //Chain to this hashtag:
                    $this->Chains->create(array(
                        'chainhandletype' => 4228,
                        'chainhandlecreator' => $chainhandlecreator,
                        'chainhashtagoutput' => $i['hashtagid'],
                        'chainhashtaginput' => $hashtag_new['hashtag_create']['hashtagid'],
                    ));

                }

            } elseif (count($handle_private_replies)) {

                if ($is_required) {
                    return array(
                        'status' => 0,
                        'message' => 'Resposne is required',
                    );
                } else {
                    //Delete Chains
                    $chains_removed = $this->Hashtags->delete($handle_private_replies[0]['hashtagid'], $chainhandlecreator);
                }

            }

        }

        $x_data['chainhandlecreator'] = $chainhandlecreator;
        $x_data['chainhandleinput'] = $chainhandlecreator;
        $x_data['chainhandletype'] = $chainhandletype;
        $x_data['chainhashtaginput'] = $i['hashtagid']; //Always add Hashtag to chainhashtaginput

        //Add chain right only if we have a target hashtag we are navigating to
        if ($target_hashtagid > 0 && (!isset($x_data['chainhashtagoutput']) || !intval($x_data['chainhashtagoutput']))) {
            $x_data['chainhashtagoutput'] = $target_hashtagid;
        }

        if (!isset($x_data['chainvalue'])) {
            $x_data['chainvalue'] = null;
        }

        $es_creator = $this->Handles->read(array(
            'handleid' => $chainhandlecreator,
        ));

        //Make sure not duplicate:
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhashtaginput' => (isset($x_data['chainhashtaginput']) ? $x_data['chainhashtaginput'] : 0),
            'chainhashtagoutput' => (isset($x_data['chainhashtagoutput']) ? $x_data['chainhashtagoutput'] : 0),
            'chainhandlecreator' => $chainhandlecreator,
            'chainvalue' => $x_data['chainvalue'],
        )) as $already_hashtag_discovered) {

            //Update:
            $this->Chains->update($already_hashtag_discovered['chainid'], $x_data);

            //Already hashtag_discovered!
            return array(
                'status' => 1,
                'message' => 'Already hashtag_discovered',
                'new_x' => $already_hashtag_discovered,
            );
        }

        //Add new Chain:
        $domain_url = get_domain('m__message', $chainhandlecreator);

        //Create Chain:
        $new_x = $this->Chains->create($x_data);

        //Auto Complete OR Answers:
        if ($input__selection) {
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___7704')) . ')' => null, //Discovery Expansion
                'chainhandlecreator' => $x_data['chainhandlecreator'],
                'chainhashtaginput' => $i['hashtagid'],
            ), array('chainhashtagoutput'), 0) as $next_i) {

                if (in_array($next_i['hashtagtype'], $this->config->item('handleids___43039'))) {
                    continue;
                }

                $has_children = count($this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                    'chainhashtaginput' => $next_i['hashtagid'],
                ), array('chainhashtagoutput'), 0, 0));

                if (!$has_children) {
                    //Mark as complete:
                    $this->Chains->hashtag_discovered(hashtag_type_discovery($next_i), $x_data['chainhandlecreator'], $target_hashtagid, $next_i, $x_data);
                }
            }
        }

        if ($x_data['chainhandlecreator'] && in_array($x_data['chainhandletype'], $this->config->item('handleids___40986'))) {

            //Discovery Triggers?
            $clone_urls = '';
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___32275')) . ')' => null, //DISCOVERY TRIGGERS
                'chainhashtaginput' => $i['hashtagid'],
            ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC')) as $clone_i) {

                if ($clone_i['chainhandletype'] == 32247) {

                    //Discovery Clone
                    $new_title = $es_creator[0]['handlevalue'] . ' ' . $clone_i['hashtagvalue'];
                    $result = $this->Hashtags->copy($clone_i['hashtagid'], 0, $x_data['chainhandlecreator'], null, $new_title);
                    if ($result['status']) {

                        //Add as watcher:
                        $this->Chains->create(array(
                            'chainhandletype' => 10573, //WATCHERS
                            'chainhandlecreator' => $x_data['chainhandlecreator'],
                            'chainhandleinput' => $x_data['chainhandlecreator'],
                            'chainhashtagoutput' => $result['hashtag_createid'],
                        ));

                        //New chain:
                        $clone_urls .= $new_title . ':' . "\n" . 'https://' . get_domain('m__message', $x_data['chainhandlecreator']) . view_memory(42903, 33286) . $result['hashtag_createhashtag'] . "\n\n";
                    }

                } elseif ($clone_i['chainhandletype'] == 32304) {

                    //Discovery Forget: Remove all Discoveries made by this user:
                    foreach ($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                        'chainhashtaginput' => $i['hashtagid'],
                        'chainhandlecreator' => $x_data['chainhandlecreator'],
                    )) as $remove_x) {
                        $this->Chains->delete($remove_x['chainid'], $x_data['chainhandlecreator']);
                    }

                }

            }

            if (strlen($clone_urls)) {
                //Send DM with all the new clone hashtag URLs:
                $clone_urls = $clone_urls . 'You have been added as a subscriber so you will be notified when anyone start using your chain.';
                $hashtag_title = view_hashtag_title($i, true);
                $this->Chains->message($x_data['chainhandlecreator'], $hashtag_title, $clone_urls);
                //Also DM all watchers of the hashtag:
                foreach ($this->Chains->read(array(
                    'chainhandletype' => 10573, //WATCHERS
                    'chainhashtagoutput' => $i['hashtagid'],
                ), array(), 0) as $watcher) {
                    $this->Chains->message($watcher['chainhandleinput'], $hashtag_title, $clone_urls);
                }
            }


            //ADD PROFILE?
            foreach ($this->Chains->read(array(
                'chainhandletype' => 7545, //Following Add
                'chainhashtagoutput' => $i['hashtagid'],
            ), array('chainhandleinput')) as $this_tag) {

                //Check if special profile add?
                if (in_array($this_tag['chainhandleinput'], $this->config->item('handleids___43048'))) {

                    //Special Addition:

                    if ($this_tag['chainhandleinput'] == 6197 && strlen(trim($x_data['chainvalue'])) >= 2) {

                        //Update Handle Title:
                        $this->Handles->update($x_data['chainhandlecreator'], array(
                            'handlevalue' => $x_data['chainvalue'],
                        ), $x_data['chainhandlecreator']);

                        //Update live session as well:
                        $es_creator[0]['handlevalue'] = $x_data['chainvalue'];
                        $this->Handles->activate($es_creator[0], true);

                    }

                } else {

                    //Assign tag if following/follower Chain NOT previously assigned:
                    $append_handle = append_handle($this_tag['chainhandleinput'], $x_data['chainhandlecreator'], (isset($handle_submitted_data['hashtag_createtext']) ? $handle_submitted_data['hashtag_createtext'] : null), $i['hashtagid']);

                    //See if Session needs to be updated:
                    if ($handle_session && $handle_session['handleid']==$x_data['chainhandlecreator'] && $append_handle) {
                        $this->Handles->activate($handle_session, true);
                    }

                }
            }


            //REMOVE PROFILE?
            foreach ($this->Chains->read(array(
                'chainhandletype' => 26599, //Following Remove
                'chainhashtagoutput' => $i['hashtagid'],
            )) as $this_tag) {

                //Remove Following IF previously assigned:
                foreach ($this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                    'chainhandleinput' => $this_tag['chainhandleinput'], //CERTIFICATES saved here
                    'chainhandleoutput' => $x_data['chainhandlecreator'],
                )) as $existing_x) {

                    $this->Chains->delete($existing_x['chainid'], $x_data['chainhandlecreator']);

                    //See if Session needs to be updated:
                    if ($handle_session && $handle_session['handleid'] == $x_data['chainhandlecreator']) {
                        //Yes, update session:
                        $this->Handles->activate($es_creator[0], true);
                    }
                }
            }


            //Notify watchers IF any:
            $watchers = $this->Chains->read(array(
                'chainhandletype' => 10573, //WATCHERS
                'chainhashtagoutput' => $i['hashtagid'],
            ), array(), 0);
            if (count($watchers)) {

                $es_discoverer = $this->Handles->read(array(
                    'handleid' => $x_data['chainhandlecreator'],
                ));
                if (count($es_discoverer)) {

                    //Fetch Discoverer contact:
                    $discoverer_contact = '';
                    foreach ($this->config->item('handles___34541') as $chainhandletype => $m) {
                        foreach ($this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                            'chainhandleoutput' => $x_data['chainhandlecreator'],
                            'chainhandleinput' => $chainhandletype,
                            'LENGTH(chainvalue)>0' => null,
                        )) as $x_progress) {
                            $discoverer_contact .= $m['m__title'] . ':' . "\n" . $x_progress['chainvalue'] . "\n\n";
                        }
                    }

                    //Notify Hashtag Watchers
                    $sent_watchers = array();
                    foreach ($watchers as $watcher) {
                        if (!in_array(intval($watcher['chainhandleinput']), $sent_watchers)) {
                            array_push($sent_watchers, intval($watcher['chainhandleinput']));

                            $this->Chains->message($watcher['chainhandleinput'], $es_discoverer[0]['handlevalue'] . ' hashtag_discovered: ' . view_hashtag_title($i, true),
                                //Message Body:
                                view_hashtag_title($i, true) . ':' . "\n" . 'https://' . $domain_url . view_memory(42903, 33286) . $i['hashtagstring'] . "\n\n" .
                                (strlen($x_data['chainvalue']) ? $x_data['chainvalue'] . "\n\n" : '') .
                                $es_discoverer[0]['handlevalue'] . ':' . "\n" . 'https://' . $domain_url . view_memory(42903, 42902) . $es_discoverer[0]['handlestring'] . "\n\n" .
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


    function history($i, $handleid, $current_level = 0)
    {

        unset($i['hashtagexternal']);
        unset($i['hashtagread']);
        unset($i['chainhandletype']);
        unset($i['chainhandleinput']);
        unset($i['chainhandleoutput']);
        unset($i['chainkey']);
        unset($i['chainhandledomain']);
        unset($i['chainvoid']);
        unset($i['chainhandlecreator']);
        unset($i['chainhashtaginput']);
        unset($i['chainhashtagoutput']);
        unset($i['chainid']);
        unset($i['chainvalue']);

        $input__selection = in_array($i['hashtagtype'], $this->config->item('handleids___7712'));
        $input__text = in_array($i['hashtagtype'], $this->config->item('handleids___43002'));
        $i['user_hashtag_discovered'] = array();
        $i['user_written_response'] = array();
        $i['current_level'] = $current_level;
        $i['next_hashtags'] = array();
        $current_level++;

        //TODO Append media

        //Append Discovery if any:
        foreach ($this->Chains->read(array(
            'chainhashtaginput' => $i['hashtagid'],
            'chainhandlecreator' => $handleid,
            'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
        ), array(), 1) as $x) {

            unset($x['chainhandletype']);
            unset($x['chainhandleinput']);
            unset($x['chainhandleoutput']);
            unset($x['chainkey']);
            unset($x['chainhandledomain']);
            unset($x['chainvoid']);
            unset($x['chainhashtaginput']);
            unset($x['chainhashtagoutput']);
            unset($x['chainhandlecreator']);
            unset($x['chainvalue']);
            unset($x['chainid']);

            $i['user_hashtag_discovered'] = $x;

            if ($input__text) {
                //Since it has been hashtag_discovered and its a text input, lots fetch the written response:
                foreach ($this->Chains->read(array(
                    'chainhandletype' => 4228,
                    'chainhashtagoutput' => $i['hashtagid'],
                    'chainhandlecreator' => $handleid,
                ), array('chainhashtaginput'), 0, 1, array('chainid' => 'DESC')) as $response) {
                    $i['user_written_response'] = $response;
                }
            }
        }


        if ($i['user_hashtag_discovered']) {
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                'chainhashtaginput' => $i['hashtagid'],
            ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC')) as $next_i) {
                array_push($i['next_hashtags'], $this->Chains->history($next_i, $handleid, $current_level));
            }
        }


        return $i;

    }

    function historyhashtag_discovered($i, $handleid, $current_level = 0)
    {

        $input__selection = in_array($i['hashtagtype'], $this->config->item('handleids___7712'));
        $input__text = in_array($i['hashtagtype'], $this->config->item('handleids___43002'));
        $i['current_level'] = $current_level;
        $i['next_hashtags'] = array();
        $i['user_hashtag_discovered'] = array();
        $i['user_written_response'] = array();
        $current_level++;

        //Append Discovery if any:
        foreach ($this->Chains->read(array(
            'chainhashtaginput' => $i['hashtagid'],
            'chainhandlecreator' => $handleid,
            'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
        ), array(), 1) as $x) {
            $i['user_hashtag_discovered'] = $x;
        }

        if ($input__text) {
            foreach ($this->Chains->read(array(
                'chainhandletype' => 4228,
                'chainhashtagoutput' => $i['hashtagid'],
                'chainhandlecreator' => $handleid,
            ), array('chainhashtaginput'), 0, 1, array('chainid' => 'DESC')) as $response) {
                $i['user_written_response'] = $response;
            }
        }


        if ($i['user_hashtag_discovered']) {
            foreach (($input__selection ? $this->Chains->read(array(
                'chainhandletype' => 7712, //Input Choice
                'chainhandlecreator' => $handleid,
                'chainhashtaginput' => $i['hashtagid'],
            ), array('chainhashtagoutput')) : $this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                'chainhashtaginput' => $i['hashtagid'],
            ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC'))) as $next_i) {
                array_push($i['next_hashtags'], $this->Chains->historyhashtag_discovered($next_i, $handleid, $current_level));
            }
        }


        return $i;

    }

    function flat_tree($i, $current_level = 0, $previous_input__selection = false)
    {

        $total_next = $this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
            'chainhashtaginput' => $i['hashtagid'],
        ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false);
        $input__selection = in_array($i['hashtagtype'], $this->config->item('handleids___7712'));
        $single_choice = in_array($i['hashtagtype'], $this->config->item('handleids___33331'));

        if(isset($_GET['skip_config'])) {
            unset($i['hashtagexternal']);
            unset($i['hashtagweight']);
            unset($i['hashtagread']);
            unset($i['hashtagtype']);
            if(isset($i['chainid'])){
                unset($i['chainhandledomain']);
                unset($i['chainhandlecreator']);
                unset($i['chainhandletype']);
                unset($i['chainhandleinput']);
                unset($i['chainhandleoutput']);
                unset($i['chainhashtaginput']);
                unset($i['chainhashtagoutput']);
                unset($i['chainkey']);
                unset($i['chainvalue']);
                unset($i['chainvoid']);
                unset($i['chainprevious']);
                unset($i['chainhash']);
            }
        } else {
            $i['current_level'] = $current_level;
            $is_required = count($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $i['hashtagid'],
                'chainhandleinput' => 28239, //Required
            )));

            $min_steps = ($input__selection ? ($is_required ? 1 : 0) : count($total_next)); //Can be improved later...
            $max_steps = ($input__selection ? ($single_choice ? 1 : count($total_next)) : count($total_next));
            $i['hashtag_list_config'] = hashtag_list_config($i['hashtagid'], false);
            $i['stats'] = array(
                'max_level' => $current_level,
                'all_steps' => 1,
                'min_steps' => $min_steps,
                'max_steps' => $max_steps,
                'min_choices' => (!$previous_input__selection && $input__selection && count($total_next) ? 1 : 0),
                'max_choices' => ($input__selection && count($total_next) ? 1 : 0),
            );
        }

        $i['next_hashtags'] = array();
        $current_level++;

        //Append Total Discoveries if any:
        if(!isset($_GET['skip_config'])) {
            $sub_counter = $this->Chains->read(array(
                'chainhashtaginput' => $i['hashtagid'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');
            $i['hashtag_count_discovery'] = $sub_counter[0]['totals'];
        }


        foreach ($total_next as $next_i) {

            $result_i = $this->Chains->flat_tree($next_i, $current_level, ($previous_input__selection ? $previous_input__selection : $input__selection));
            array_push($i['next_hashtags'], $result_i);

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


    function progress($handleid, $i, $current_level = 0, $loop_breaker_ids = array())
    {

        if (count($loop_breaker_ids) > 0 && in_array($i['hashtagid'], $loop_breaker_ids)) {
            return false;
        }

        $copy = $this->Hashtags->ids($i, 'AND');
        if (!isset($copy['recursive_hashtag_ids']) || !count($copy['recursive_hashtag_ids'])) {
            return false;
        }

        $current_level++;
        array_push($loop_breaker_ids, intval($i['hashtagid']));

        //Count completed:
        $list_hashtag_discovered = array();
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhandlecreator' => $handleid, //Belongs to this Member
            'chainhashtaginput IN (' . join(',', $copy['recursive_hashtag_ids']) . ')' => null,
        ), array('chainhashtaginput'), 0) as $completed) {
            if (!in_array($completed['hashtagstring'], $list_hashtag_discovered)) {
                array_push($list_hashtag_discovered, $completed['hashtagstring']);
            }
        }


        //Calculate common steps and expansion steps recursively for this u:
        $metadata_this = array(
            'fixed_total' => count($copy['recursive_hashtag_ids']),
            'list_total' => $copy['recursive_hashtag_ids'],
            'fixed_hashtag_discovered' => count($list_hashtag_discovered),
            'list_hashtag_discovered' => $list_hashtag_discovered,
        );

        //Now let's check possible expansions:
        if (count($copy['recursive_hashtag_ids'])) {
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___7704')) . ')' => null, //Discovery Expansion
                'chainhandlecreator' => $handleid, //Belongs to this Member
                'chainhashtaginput IN (' . join(',', $copy['recursive_hashtag_ids']) . ')' => null,
            ), array('chainhashtagoutput')) as $expansion_in) {

                //Fetch recursive:
                $progress = $this->Chains->progress($handleid, $expansion_in, $current_level, $loop_breaker_ids);

                if (!$progress && !count($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                        'chainhandlecreator' => $handleid, //Belongs to this Member
                        'chainhashtaginput' => $expansion_in['hashtagid'],
                    )))) {
                    $progress = array(
                        'fixed_total' => 1,
                        'list_total' => array($expansion_in['hashtagid']),
                        'fixed_hashtag_discovered' => 0,
                        'list_hashtag_discovered' => array(),
                    );
                }

                //Addup completion stats for this:
                $metadata_this['fixed_total'] += $progress['fixed_total'];
                $metadata_this['fixed_hashtag_discovered'] += $progress['fixed_hashtag_discovered'];

                if ($progress['list_total'] && count($progress['list_total'])) {
                    foreach ($progress['list_total'] as $tree_id) {
                        if (!in_array($tree_id, $metadata_this['list_total'])) {
                            array_push($metadata_this['list_total'], $tree_id);
                        }
                    }
                }

                if ($progress['list_hashtag_discovered'] && count($progress['list_hashtag_discovered'])) {
                    foreach ($progress['list_hashtag_discovered'] as $tree_id) {
                        if (!in_array($tree_id, $metadata_this['list_hashtag_discovered'])) {
                            array_push($metadata_this['list_hashtag_discovered'], $tree_id);
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
                $metadata_this['fixed_completed_percentage'] = intval(floor($metadata_this['fixed_hashtag_discovered'] / $metadata_this['fixed_total'] * 100));
            }


        }

        //Return results:
        return $metadata_this;

    }


}