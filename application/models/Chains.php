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
        if (!isset($add_fields['chainsourcetype']) || !in_array($add_fields['chainsourcetype'], $this->config->item('sourceids___4593'))) {
            log_error('Chains->create() failed to create because of invalid Chain type @' . $add_fields['chainsourcetype'], array(
                'chainsourcecreator' => $add_fields['chainsourcecreator'],
                'chainsourcedown' => $add_fields['chainsourcetype'],
            ));
            return false;
        }

        //Set some defaults:
        if (!isset($add_fields['chainsourcecreator']) || intval($add_fields['chainsourcecreator']) < 1) {
            $add_fields['chainsourcecreator'] = 14068; //GUEST MEMBER
        }

        //Set some defaults:
        if (!isset($add_fields['chainvalue'])) {
            $add_fields['chainvalue'] = null;
        } elseif (is_array($add_fields['chainvalue'])) {
            $add_fields['chainvalue'] = serialize($add_fields['chainvalue']);
        }

        //Is this an observation chain that should replace an older observation, if any:
        if($update_observed && in_array($add_fields['chainsourcetype'], $this->config->item('sourceids___1308453'))){
            $read_fields = $add_fields;
            if(isset($read_fields['chainvalue'])){
                unset($read_fields['chainvalue']);
            }
            foreach ($this->Chains->read($read_fields, array(), 1) as $last_observation) {
                //Update the previous observed chain:
                return $this->Chains->update($last_observation['chainid'], $add_fields);
            }
        }

        //Set some zero defaults if not set:
        foreach (array('chainidearight', 'chainidealeft', 'chainsourcedown', 'chainsourceup', 'chainkey') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Append Domain:
        if (!isset($add_fields['chainsourcedomain']) || $add_fields['chainsourcedomain'] < 1) {
            $add_fields['chainsourcedomain'] = website_setting(0, $add_fields['chainsourcecreator']);
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
                'chainsourcecreator' => $add_fields['chainsourcecreator'],
                'chainsourcedown' => $add_fields['chainsourcecreator'],
            ));
            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['chainsourceup'] > 0) {
                update_algolia(12274, $add_fields['chainsourceup']);
            }

            if ($add_fields['chainsourcedown'] > 0) {
                update_algolia(12274, $add_fields['chainsourcedown']);
            }

            if ($add_fields['chainidealeft'] > 0) {
                update_algolia(12273, $add_fields['chainidealeft']);
            }

            if ($add_fields['chainidearight'] > 0) {
                update_algolia(12273, $add_fields['chainidearight']);
            }
        }


        //See if this Chain type has any followers that are essentially subscribed to it:
        $tr_watchers = $this->Sources->tree(42381, $add_fields['chainsourcetype'], $this->config->item('sourceids___30820'), array(), 1);
        if (is_array($tr_watchers) && count($tr_watchers)) {

            //yes, start drafting email to be sent to them
            $u_name = 'Unknown';
            if ($add_fields['chainsourcecreator'] > 0) {
                //Fetch member details:
                $add_e = $this->Sources->read(array(
                    'sourceid' => $add_fields['chainsourcecreator'],
                ));
                if (count($add_e)) {
                    $u_name = $add_e[0]['sourcevalue'];
                }
            }


            //Email Subject:
            $sources___4593 = $this->config->item('sources___4593'); //Chain Types
            $subject = $u_name . ' ' . $sources___4593[$add_fields['chainsourcetype']]['m__title'];

            //Compose email body, start with Chain content:
            $html_message = (strlen($add_fields['chainvalue']) > 0 ? $add_fields['chainvalue'] : '') . "\n";


            //Append Chain object Chains:
            foreach ($this->config->item('sources___4341') as $sourceid => $m) {

                if (in_array(6202, $m['m__following'])) {

                    //IDEA
                    foreach ($this->Ideas->read(array('ideaid' => $add_fields[$m['m__handle']])) as $this_i) {
                        $html_message .= $m['m__title'] . ': ' . view_idea_title($this_i, true) . ':' . "\n" . $this->config->item('base_url') . view_memory(42903, 33286) . $this_i['ideahashtag'] . "\n\n";
                    }

                } elseif (in_array(6160, $m['m__following'])) {

                    //SOURCE
                    foreach ($this->Sources->read(array('sourceid' => $add_fields[$m['m__handle']])) as $this_e) {
                        $html_message .= $m['m__title'] . ': ' . $this_e['sourcevalue'] . "\n" . $this->config->item('base_url') . view_memory(42903, 42902) . $this_e['sourcehandle'] . "\n\n";
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
                if ($tr_watcher['sourceid'] != $add_fields['chainsourcecreator']) {
                    $this->Chains->message($tr_watcher['sourceid'], $subject, $html_message, array(
                        'chainidearight' => $add_fields['chainidearight'],
                        'chainidealeft' => $add_fields['chainidealeft'],
                        'chainsourcedown' => $add_fields['chainsourcedown'],
                        'chainsourceup' => $add_fields['chainsourceup'],
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

        //IDEA JOIN?
        $idea_join = false;
        if (in_array('chainidealeft', $joins_objects)) {
            $idea_join = true;
            $this->db->join('cacheideas', 'chainidealeft=ideaid', 'left');
        } elseif (in_array('chainidearight', $joins_objects)) {
            $idea_join = true;
            $this->db->join('cacheideas', 'chainidearight=ideaid', 'left');
        } elseif (in_array('chainideaid', $joins_objects)) {
            $idea_join = true;
            $this->db->join('cacheideas', 'chainid=ideaid', 'left');
        }

        //PLAYER JOIN?
        $source_join = false;
        if (in_array('chainsourceup', $joins_objects)) {
            $source_join = true;
            $this->db->join('cachesources', 'chainsourceup=sourceid', 'left');
        } elseif (in_array('chainsourcedown', $joins_objects)) {
            $source_join = true;
            $this->db->join('cachesources', 'chainsourcedown=sourceid', 'left');
        } elseif (in_array('chainsourcetype', $joins_objects)) {
            $source_join = true;
            $this->db->join('cachesources', 'chainsourcetype=sourceid', 'left');
        } elseif (in_array('chainsourcecreator', $joins_objects)) {
            $source_join = true;
            $this->db->join('cachesources', 'chainsourcecreator=sourceid', 'left');
        } elseif (in_array('chainsourcedomain', $joins_objects)) {
            $source_join = true;
            $this->db->join('cachesources', 'chainsourcedomain=sourceid', 'left');
        } elseif (in_array('chainsourceid', $joins_objects)) {
            $source_join = true;
            $this->db->join('cachesources', 'chainid=sourceid', 'left');
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
        if($idea_join){
            $this->db->where('ideaid >', 0);
        }
        if($source_join){
            $this->db->where('sourceid >', 0);
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
            if (array_intersect(array('chainidealeft', 'chainidearight'), $joins_objects)) {
                //Idea results:
                foreach ($results as $key => $value) {
                    if (!idea_access(null, $value['ideaid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            } elseif (array_intersect(array('chainsourceup', 'chainsourcedown'), $joins_objects)) {
                //Source results:
                foreach ($results as $key => $value) {
                    if (!source_access(null, $value['sourceid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            }
        }

        return $results;

    }


    function update($chainid, $update_columns, $chainsourcecreator = 0)
    {

        //Fetch Chain before updating:
        foreach ($this->Chains->read(array(
            'chainid' => $chainid,
        )) as $old_x) {

            if (!isset($update_columns['chainsourcecreator'])) {
                //Fetch session source:
                $update_columns['chainsourcecreator'] = ($chainsourcecreator > 0 ? $chainsourcecreator : $old_x['chainsourcecreator'] );
            }

            //Make sure something changed:
            $something_changed = false;
            foreach(array('chainsourcetype','chainsourceup','chainsourcedown','chainidealeft','chainidearight','chainkey','chainvalue','chainvoid') as $must_change){
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


    function delete($chainid, $chainsourcecreator = 0)
    {

        //Validate $chainid
        foreach ($this->Chains->read(array(
            'chainid' => $chainid,
        )) as $old_x) {

            //Set default source:
            if (!$chainsourcecreator) {
                //Fetch session source:
                $source_session = source_session();
                $chainsourcecreator = ($source_session ? $source_session['sourceid'] : ($old_x['chainsourcecreator'] > 0 ? $old_x['chainsourcecreator'] : 14068 /* Guest Member */));
            }

            //Determine VOID chain type in 1 of the 4 chain groups:
            if (in_array($old_x['chainsourcetype'], $this->config->item('sourceids___31777'))) {
                //Discovery
                $chainsourcetype = 44397;
            } elseif (in_array($old_x['chainsourcetype'], $this->config->item('sourceids___4486'))) {
                //Ideation
                $chainsourcetype = 44396;
            } elseif (in_array($old_x['chainsourcetype'], $this->config->item('sourceids___13550'))) {
                //Contribution
                $chainsourcetype = 44399; //TODO Adjust this
            } elseif (in_array($old_x['chainsourcetype'], $this->config->item('sourceids___32292'))) {
                //Sourcing
                $chainsourcetype = 44399; //TODO Adjust this
            } else {
                return 0; //Should not happen
            }

            $new_x = $this->Chains->create(array(
                'chainsourcecreator' => $chainsourcecreator,
                'chainsourcetype' => $chainsourcetype,
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


    function select($focus__id, $o__id, $element_id, $source_createid, $migratehandle, $chainid = 0)
    {

        //Authenticate Member:
        $migratehandle = trim(substr($migratehandle, 0, 1) == '@' ? trim(substr($migratehandle, 1)) : $migratehandle);
        $migratehandle = trim(substr($migratehandle, 0, 1) == '#' ? trim(substr($migratehandle, 1)) : $migratehandle);
        $source_session = source_session();
        if (!$source_session) {
            return array(
                'status' => 0,
                'message' => blocked_reasoning(),
            );
        } elseif (intval($o__id) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Target ID',
            );
        } elseif (intval($element_id) < 1 || !count($this->config->item('sourceids___' . $element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Variable ID [' . $element_id . ']',
            );
        } elseif (intval($source_createid) < 1 || !in_array($source_createid, $this->config->item('sourceids___' . $element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            );
        }


        //See if anything is being deleted:
        $auto_open_idea_modal = 0;
        $delete_redirect = null;
        $delete_element = null;
        $chains_removed = -1;
        $status = 0;
        $delete_redirect = '';
        $delete_element = '';

        if ($element_id == 4486 && $chainid > 0) {

            //IDEA CHAIN TYPE
            $status = $this->Chains->update($chainid, array(
                'chainsourcecreator' => $source_session['sourceid'],
                'chainsourcetype' => $source_createid,
            ));

        } elseif ($element_id == 13550 && $chainid > 0) {

            //SOURCE CHAIN TYPE
            $status = $this->Chains->update($chainid, array(
                'chainsourcetype' => $source_createid,
                'chainsourcecreator' => $source_session['sourceid'],
            ));

        } elseif ($element_id == 32292 && $chainid > 0) {

            //SOURCE/SOURCE CHAIN
            $status = $this->Chains->update($chainid, array(
                'chainsourcetype' => $source_createid,
                'chainsourcecreator' => $source_session['sourceid'],
            ));

        } elseif (0 && $element_id == 42795 && $o__id > 0 && $source_createid && $source_session) {

            if (!$chainid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Chains->read(array(
                    'chainsourceup' => $o__id,
                    'chainsourcedown' => $source_session['sourceid'],
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42795')) . ')' => null, //Follow
                ), array(), 1) as $found_x) {
                    $chainid = $found_x['chainid'];
                }
            }

            //Follow
            if ($chainid > 0) {
                //Updating reaction:
                if (in_array($source_createid, $this->config->item('sourceids___42850'))) {
                    //Unsubscribe
                    $status = $this->Chains->delete($chainid, $source_session['sourceid']); //Media Removed
                } else {
                    $status = $this->Chains->update($chainid, array(
                        'chainsourcetype' => $source_createid,
                        'chainsourcecreator' => $source_session['sourceid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Chains->create(array(
                    'chainsourcecreator' => $source_session['sourceid'],
                    'chainsourceup' => $o__id,
                    'chainsourcedown' => $source_session['sourceid'],
                    'chainsourcetype' => $source_createid,
                )));
            }

        } elseif ($element_id == 42260 && $o__id > 0 && $source_createid && $source_session) {

            //Check if current value?
            if (!$chainid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Chains->read(array(
                    'chainsourceup' => $source_session['sourceid'],
                    'chainidearight' => $o__id,
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42260')) . ')' => null, //Reactions
                ), array(), 1) as $found_x) {
                    $chainid = $found_x['chainid'];
                }
            }

            //Reactions...
            if ($chainid > 0) {
                if (in_array($source_createid, $this->config->item('sourceids___42850'))) {
                    $status = $this->Chains->delete($chainid, $source_session['sourceid']); //Removed
                } else {
                    //Updating reaction:
                    $status = $this->Chains->update($chainid, array(
                        'chainsourcetype' => $source_createid,
                        'chainsourcecreator' => $source_session['sourceid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Chains->create(array(
                    'chainsourcecreator' => $source_session['sourceid'],
                    'chainsourceup' => $source_session['sourceid'],
                    'chainidearight' => $o__id,
                    'chainsourcetype' => $source_createid,
                )));
            }

        } elseif ($element_id == 4737) {

            //Source Reference
            $status = $this->Ideas->update($o__id, array(
                'ideatype' => $source_createid,
            ), $source_session['sourceid']);

            //See if we need to popup the idea edit modal here:

            $sources___42179 = $this->config->item('sources___42179'); //Dynamic Input Fields
            foreach (array_intersect($this->config->item('sourceids___' . $source_createid), $this->config->item('sourceids___42179')) as $dynamic_sourceid) {

                $superpowers_required = array_intersect($this->config->item('sourceids___10957'), $sources___42179[$dynamic_sourceid]['m__following']);
                if (count($superpowers_required) && !source_session(end($superpowers_required))) {
                    continue;
                }

                //Let's determine the data type:
                $data_types = array_intersect($sources___42179[$dynamic_sourceid]['m__following'], $this->config->item('sourceids___4592'));

                //ASSUME that we found 1 match as expected:
                foreach ($data_types as $data_type_this) {
                    $data_type = $data_type_this;
                    break;
                }
                $is_required = in_array($dynamic_sourceid, $this->config->item('sourceids___28239')); //Required Settings

                if (!$is_required) {
                    //We are only interested in what is required
                    continue;
                }

                //See if we are missing value:
                if (in_array($data_type, $this->config->item('sourceids___42188'))) {

                    //Single or Multiple Choice:
                    $already_responded = count($this->Chains->read(array(
                        'chainsourceup IN (' . join(',', $this->config->item('sourceids___' . $dynamic_sourceid)) . ')' => null, //All possible answers
                        'chainidearight' => $o__id,
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___33602')) . ')' => null, //Idea/Source Chains Active
                    )));

                } else {

                    $already_responded = count($this->Chains->read(array(
                        'chainsourceup' => $dynamic_sourceid,
                        'chainidearight' => $o__id,
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___33602')) . ')' => null, //Idea/Source Chains Active
                    )));

                }

                if (!$already_responded) {
                    //We are missing a required response, auto open modal:
                    $auto_open_idea_modal = 1;
                }

            }

        }

        return array(
            'status' => intval($status) && ($chains_removed < 0 || $chains_removed > 0),
            'message' => 'Delete status [' . $status . '] with ' . $chains_removed . ' Chains removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
            'auto_open_idea_modal' => $auto_open_idea_modal,
        );

    }

    function message($sourceid, $subject, $html_message, $x_data = array(), $template_ideaid = 0, $chainsourcedomain = 0, $log_tr = true, $demo_only = false)
    {

        $sms_subscriber = false;

        //Bypass notifications?
        if (!count($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42256')) . ')' => null, //Writes
            'chainsourceup' => 31779, //Mandatory Emails
            'chainidearight' => $template_ideaid,
        )))) {

            $notification_levels = $this->Chains->read(array(
                'chainsourceup IN (' . join(',', $this->config->item('sourceids___30820')) . ')' => null, //Active Subscriber
                'chainsourcedown' => $sourceid,
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            ));
            if (!count($notification_levels)) {
                return array(
                    'status' => 0,
                    'message' => 'User is not an active subscriber',
                );
            }
            $sms_subscriber = in_array($notification_levels[0]['chainsourceup'], $this->config->item('sourceids___28915'));
        }

        //Make sure not recently contacted:
        /*
         * Did not work with subscription notifications which could happen back to back...
         *
        $minutes_limit = 60;
        foreach($this->Chains->read(array(
            'chainsourcetype' => 29399,
            'chainsourcecreator' => $sourceid,
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
            'phone_count' => 0,
        );


        //Send Emails:
        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            'chainsourceup' => 3288, //Email
            'chainsourcedown' => $sourceid,
        )) as $source_data) {

            if (!filter_var($source_data['chainvalue'], FILTER_VALIDATE_EMAIL)) {
                $this->Chains->delete($source_data['chainid'], $sourceid);
                continue;
            }

            array_push($stats['email_addresses'], $source_data['chainvalue']);

        }

        if (count($stats['email_addresses']) > 0) {
            //Send email:
            dispatch_email($stats['email_addresses'], $subject, $html_message, $sourceid, $x_data, $template_ideaid, $chainsourcedomain, $log_tr, $demo_only);
        }


        //Should we send SMS?
        $twilio_account_sid = website_setting(30859);
        $twilio_auth_token = website_setting(30860);
        $twilio_from_number = website_setting(27673);
        if ($sms_subscriber && $twilio_account_sid && $twilio_auth_token && $twilio_from_number) {

            //Yes, generate message
            $sms_message = get_domain('m__title', $sourceid, $chainsourcedomain) . ' Emailed [' . $subject . '] to ' . join(' & ', $stats['email_addresses']) . ' (Also Check Spam)';

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n", " ", $sms_message);

            //Send SMS
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                'chainsourceup' => 4783, //Phone
                'chainsourcedown' => $sourceid,
            )) as $source_data) {

                foreach (explode('|||', wordwrap($sms_message, view_memory(6404, 27891), "|||")) as $single_message) {

                    $sms_sent = dispatch_sms($source_data['chainvalue'], $single_message, $sourceid, $x_data, $template_ideaid, $chainsourcedomain, $log_tr, $demo_only);

                    if (!$sms_sent) {
                        //bad number, remove it:
                        $this->Chains->delete($source_data['chainid'], $sourceid);
                    }

                }

                $stats['phone_count']++;

            }

        }

        return array(
            'status' => ($stats['phone_count'] > 0 || count($stats['email_addresses']) > 0 ? 1 : 0),
            'email_count' => count($stats['email_addresses']),
            'phone_count' => $stats['phone_count'],
            'message' => 'Message sent',
        );

    }


    function broadcast($list_of_sourceid, $i, $chainsourcedomain = 0, $ensure_unidea_discovered = true, $demo_only = false)
    {

        $total_sent = 0;
        $chainsourcedomain = ($chainsourcedomain > 0 ? $chainsourcedomain : (isset($i['chainsourcedomain']) ? $i['chainsourcedomain'] : 0));
        $subject_line = view_idea_title($i, true);
        $wacth_repeat_handles = array();

        foreach ($list_of_sourceid as $count => $x) {

            if (in_array($x['sourcehandle'], $wacth_repeat_handles)) {
                //This should not happen! Report bug:
                log_error('Chains->broadcast() Detected duplicate Source Handle Bug: ' . $x['sourcehandle'], array(
                    'chainsourcedown' => $x['sourceid'],
                ));
                break; //Stop sending more messages!
            }

            //Map this handle:
            array_push($wacth_repeat_handles, $x['sourcehandle']);


            if (!isset($x['sourceid'])) {
                //Invalid input for sending:
                log_error('Chains->broadcast() Invalid Source', array(
                    'chainsourcecreator' => $x['sourceid'],
                    'chainsourcedown' => 26582, //Messener
                ));
                continue;
                } elseif ($ensure_unidea_discovered && count($this->Chains->read(array(
                    'chainidealeft' => $i['ideaid'],
                    'chainsourcecreator' => $x['sourceid'],
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31777')) . ')' => null, //DISCOVERIES
                )))) {
                //Already idea_discovered:
                continue;
            }


            $content_message = view_idea_value($i, $x['sourceid'], true); //Hide the show more content if any
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', $content_message);
            }

            //Append children as options:
            $html_message = '';
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42267')) . ')' => null, //Sequence Down
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC')) as $down_or) {
                //Has this user idea_discovered this idea or no?
                $html_message .= '<div class="line">' . view_idea_title($down_or, true) . ':</div>';
                $html_message .= '<div class="line">' . 'https://' . get_domain('m__message', $x['sourceid'], $chainsourcedomain) . view_memory(42903, 33286) . $down_or['ideahashtag'] . (idea_is_startable($down_or) ? '/' . view_memory(6404, 4235) : '') . '?sourcehandle=' . $x['sourcehandle'] . '&time=' . time() . '&hash=' . view_hash(time() . $x['sourcehandle']) . '</div>';
            }

            //Where to place the next step?
            if (substr_count($content_message, 'chain_here') == 1) {
                //We have direction to place the next step somewhere specific:
                $content_message = str_replace('chain_here', $html_message, $content_message);
            } else {
                $content_message = $content_message . $html_message;
            }

            $message = $this->Chains->message($x['sourceid'], $subject_line, $content_message, array(
                'chainidealeft' => $i['ideaid'],
            ), $i['ideaid'], $chainsourcedomain, true, $demo_only);

            //Mark as idea_discovered:
            if ($message['status'] && !$demo_only) {
                $this->Chains->idea_discovered(43142, $x['sourceid'], 0, $i);
                $total_sent++;
            }

        }

        return $total_sent;
    }


    function previousidea($sourceid, $target_ideahashtag, $focus_ideaid, $loop_breaker_ids = array())
    {

        //echo 'Previous:'.$sourceid.'/'.$target_ideahashtag.'/'.$focus_ideaid;

        if (count($loop_breaker_ids) > 0 && in_array($focus_ideaid, $loop_breaker_ids)) {
            return array();
        }
        array_push($loop_breaker_ids, intval($focus_ideaid));

        //Fetch followings:
        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42268')) . ')' => null, //Active Sequence Up
            'chainidearight' => $focus_ideaid,
        ), array('chainidealeft')) as $idea_previous) {

            //Validate Selection:
            $input__selection = in_array($idea_previous['ideatype'], $this->config->item('sourceids___7712'));
            $is_selected = count($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___7704')) . ')' => null, //Discovery Expansion
                'chainidealeft' => $idea_previous['ideaid'],
                'chainidearight' => $focus_ideaid,
                'chainsourcecreator' => $sourceid,
            )));

            if ($sourceid > 0 && !$is_selected && $input__selection) {
                continue;
            }

            //Did we find it?
            if ($idea_previous['ideahashtag'] == $target_ideahashtag) {
                return array($idea_previous);
            }

            //Keep looking further up:
            $website_finder = $this->Chains->previousidea($sourceid, $target_ideahashtag, $idea_previous['ideaid'], $loop_breaker_ids);
            if (count($website_finder)) {
                array_push($website_finder, $idea_previous);
                return $website_finder;
            }
        }

        //Did not find any followings:
        return array();

    }


    function previousidea_discovered($focus_ideaid, $chainsourcecreator, $loop_breaker_ids = array())
    {

        /*
         *
         * Returns hashtag if idea_discovered upwards
         *
         * */

        if (count($loop_breaker_ids) > 0 && in_array($focus_ideaid, $loop_breaker_ids)) {
            return false;
        }
        array_push($loop_breaker_ids, intval($focus_ideaid));

        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42268')) . ')' => null, //Active Sequence Up
            'chainidearight' => $focus_ideaid,
        ), array('chainidealeft')) as $prev_i) {

            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'chainsourcecreator' => $chainsourcecreator,
                'chainidealeft' => $prev_i['ideaid'],
            ), array('chainidearight')) as $x) {
                return $x['ideahashtag'];
            }

            return $this->Chains->previousideaidea_discovered($prev_i['ideaid'], $chainsourcecreator, $loop_breaker_ids);
        }

        //Did not find!
        return false;

    }


    function idea_next($sourceid, $target_ideahashtag, $i, $find_after_ideaid = 0, $search_up = true, $target_completed = false, $loop_breaker_ids = array())
    {

        /*
        foreach ($this->Ideas->read(array(
            'LOWER(ideahashtag)' => strtolower($target_ideahashtag),
        )) as $i_new) {
            $i = $i_new;
        }
        */

        if (count($loop_breaker_ids) > 0 && in_array($i['ideaid'], $loop_breaker_ids)) {
            return null;
        }
        array_push($loop_breaker_ids, intval($i['ideaid']));

        $input__selection = in_array($i['ideatype'], $this->config->item('sourceids___7712'));
        $found_trigger = null;

        foreach ($this->Chains->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42267')) . ')' => null, //Active Sequence Down
        ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_ideaid && !$found_trigger) {
                if ($next_i['ideaid'] == $find_after_ideaid) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            $is_selected = count($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___7704')) . ')' => null, //Discovery Expansion
                'chainidealeft' => $i['ideaid'],
                'chainidearight' => $next_i['ideaid'],
                'chainsourcecreator' => $sourceid,
            )));
            if ($input__selection && !$is_selected) {
                continue;
            }


            //Return this if everything is completed, or if this is incomplete:
            if ($target_completed || !count($this->Chains->read(array(
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    'chainsourcecreator' => $sourceid,
                    'chainidealeft' => $next_i['ideaid'],
                )))) {
                return $next_i['ideahashtag'];
            }

            //Keep looking deeper:
            $next__url = $this->Chains->idea_next($sourceid, $target_ideahashtag, $next_i, 0, false, $target_completed, $loop_breaker_ids);
            if ($next__url) {
                return $next__url;
            }

        }


        if ($search_up && $target_ideahashtag != $i['ideahashtag']) {
            //Check Previous/Up
            $current_previous = $i['ideaid'];
            foreach (array_reverse($this->Chains->previousidea($sourceid, $target_ideahashtag, $i['ideaid'])) as $p_i) {
                //Find the next siblings:
                $next__url = $this->Chains->idea_next($sourceid, $target_ideahashtag, $p_i, $current_previous, false, $target_completed);
                if ($next__url) {
                    return $next__url;
                }
                $current_previous = $p_i['ideaid'];
            }
        }

        //Nothing found:
        return null;

    }


    function idea_discovered($chainsourcetype, $chainsourcecreator, $target_ideaid = 0, $i, $source_submitted_data = array(), $x_data = array())
    {

        if (!$chainsourcecreator || !in_array($chainsourcetype, $this->config->item('sourceids___31777' /* DISCOVERIES */))) {
            return log_error('idea_discovered() Invalid chainsourcetype @' . $chainsourcetype . ' missing in @31777 OR Missing $chainsourcecreator', array(
                'chainsourcedown' => $chainsourcecreator,
                'chainsourcecreator' => $chainsourcecreator,
            ));
        }

        //Do we need to save text/upload ?
        $input__selection = in_array($i['ideatype'], $this->config->item('sourceids___7712'));
        $input__upload = in_array($i['ideatype'], $this->config->item('sourceids___43004'));
        $input__text = in_array($i['ideatype'], $this->config->item('sourceids___43002')) || in_array($i['ideatype'], $this->config->item('sourceids___43003'));
        $is_required = count($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainsourceup' => 28239, //Required
        )));


        if ($input__upload || $input__text) {

            if (!isset($source_submitted_data['idea_createtext'])) {
                $source_submitted_data['idea_createtext'] = null;
            }
            if (!isset($source_submitted_data['uploaded_media'])) {
                $source_submitted_data['uploaded_media'] = array();
            }

            //Must add a new idea, but first let's validate the input:
            if ($i['ideatype'] == 31794 && strlen($source_submitted_data['idea_createtext']) && !is_numeric($source_submitted_data['idea_createtext'])) {
                //Number Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Number',
                );
            } elseif ($i['ideatype'] == 42915 && strlen($source_submitted_data['idea_createtext']) && !filter_var($source_submitted_data['idea_createtext'], FILTER_VALIDATE_URL)) {
                //Chain Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid URL',
                );
            } elseif ($i['ideatype'] == 30350 && strlen($source_submitted_data['idea_createtext']) && !strtotime($source_submitted_data['idea_createtext'])) {
                //Date Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Date',
                );
            }

            //Find most recent answers by this user:
            $source_private_replies = $this->Chains->read(array(
                'chainsourcetype' => 33532, //Private Reply
                'chainidealeft' => $i['ideaid'],
                'chainsourcecreator' => $chainsourcecreator,
            ), array('chainidearight'), 0, 1, array('chainid' => 'DESC'));


            //All validated, lets create the new idea:
            if (strlen($source_submitted_data['idea_createtext']) || count($source_submitted_data['uploaded_media'])) {

                if (count($source_private_replies)) {

                    //Update existing response if different:
                    if ($source_submitted_data['idea_createtext'] != $source_private_replies[0]['ideavalue']) {

                        $this->Ideas->update($source_private_replies[0]['ideaid'], array(
                            'ideavalue' => $source_submitted_data['idea_createtext'],
                        ), $chainsourcecreator);

                    }

                    $this_ideaid = $source_private_replies[0]['ideaid'];

                } else {

                    //Create a new idea:
                    $idea_new = $this->Ideas->create(array(
                        'ideavalue' => $source_submitted_data['idea_createtext'],
                        'ideatype' => 6677,
                    ), $chainsourcecreator);

                    $this_ideaid = $idea_new['idea_create']['ideaid'];

                    //Chain to this idea:
                    $this->Chains->create(array(
                        'chainsourcetype' => 33532, //Private Reply
                        'chainsourcecreator' => $chainsourcecreator,
                        'chainidealeft' => $i['ideaid'],
                        'chainidearight' => $idea_new['idea_create']['ideaid'],
                    ));

                }

                //Process Media:
                $media_stats = process_media($this_ideaid, $source_submitted_data['uploaded_media']);

            } elseif (count($source_private_replies)) {

                if ($is_required) {
                    return array(
                        'status' => 0,
                        'message' => 'Resposne is required',
                    );
                } else {
                    //Delete Chains
                    $chains_removed = $this->Ideas->delete($source_private_replies[0]['ideaid'], $chainsourcecreator);
                }

            }

        }

        $x_data['chainsourcecreator'] = $chainsourcecreator;
        $x_data['chainsourcetype'] = $chainsourcetype;
        $x_data['chainidealeft'] = $i['ideaid']; //Always add Idea to chainidealeft

        //Add chain right only if we have a target idea we are navigating to
        if ($target_ideaid > 0 && (!isset($x_data['chainidearight']) || !intval($x_data['chainidearight']))) {
            $x_data['chainidearight'] = $target_ideaid;
        }

        if (!isset($x_data['chainvalue'])) {
            $x_data['chainvalue'] = null;
        }

        $es_creator = $this->Sources->read(array(
            'sourceid' => $chainsourcecreator,
        ));

        //Make sure not duplicate:
        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'chainidealeft' => (isset($x_data['chainidealeft']) ? $x_data['chainidealeft'] : 0),
            'chainidearight' => (isset($x_data['chainidearight']) ? $x_data['chainidearight'] : 0),
            'chainsourcecreator' => $chainsourcecreator,
            'chainvalue' => $x_data['chainvalue'],
        )) as $already_idea_discovered) {

            //Update:
            $this->Chains->update($already_idea_discovered['chainid'], $x_data);

            //Already idea_discovered!
            return array(
                'status' => 1,
                'message' => 'Already idea_discovered',
                'new_x' => $already_idea_discovered,
            );
        }

        //Add new Chain:
        $domain_url = get_domain('m__message', $chainsourcecreator);

        //Create Chain:
        $new_x = $this->Chains->create($x_data);

        //Auto Complete OR Answers:
        if ($input__selection) {
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___7704')) . ')' => null, //Discovery Expansion
                'chainsourcecreator' => $x_data['chainsourcecreator'],
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0) as $next_i) {

                if (in_array($next_i['ideatype'], $this->config->item('sourceids___43039'))) {
                    continue;
                }

                $has_children = count($this->Chains->read(array(
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42267')) . ')' => null, //IDEA CHAINS
                    'chainidealeft' => $next_i['ideaid'],
                ), array('chainidearight'), 0, 0));

                if (!$has_children) {
                    //Mark as complete:
                    $this->Chains->idea_discovered(idea_type_discovery($next_i), $x_data['chainsourcecreator'], $target_ideaid, $next_i, $x_data);
                }
            }
        }

        if ($x_data['chainsourcecreator'] && in_array($x_data['chainsourcetype'], $this->config->item('sourceids___40986'))) {

            //Discovery Triggers?
            $clone_urls = '';
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___32275')) . ')' => null, //DISCOVERY TRIGGERS
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC')) as $clone_i) {

                if ($clone_i['chainsourcetype'] == 32247) {

                    //Discovery Clone
                    $new_title = $es_creator[0]['sourcevalue'] . ' ' . $clone_i['ideavalue'];
                    $result = $this->Ideas->copy($clone_i['ideaid'], 0, $x_data['chainsourcecreator'], null, $new_title);
                    if ($result['status']) {

                        //Add as watcher:
                        $this->Chains->create(array(
                            'chainsourcetype' => 10573, //WATCHERS
                            'chainsourcecreator' => $x_data['chainsourcecreator'],
                            'chainsourceup' => $x_data['chainsourcecreator'],
                            'chainidearight' => $result['idea_createid'],
                        ));

                        //New chain:
                        $clone_urls .= $new_title . ':' . "\n" . 'https://' . get_domain('m__message', $x_data['chainsourcecreator']) . view_memory(42903, 33286) . $result['idea_createhashtag'] . "\n\n";
                    }

                } elseif ($clone_i['chainsourcetype'] == 32304) {

                    //Discovery Forget: Remove all Discoveries made by this user:
                    foreach ($this->Chains->read(array(
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31777')) . ')' => null, //DISCOVERIES
                        'chainidealeft' => $i['ideaid'],
                        'chainsourcecreator' => $x_data['chainsourcecreator'],
                    )) as $remove_x) {
                        $this->Chains->delete($remove_x['chainid'], $x_data['chainsourcecreator']);
                    }

                }

            }

            if (strlen($clone_urls)) {
                //Send DM with all the new clone idea URLs:
                $clone_urls = $clone_urls . 'You have been added as a subscriber so you will be notified when anyone start using your chain.';
                $idea_title = view_idea_title($i, true);
                $this->Chains->message($x_data['chainsourcecreator'], $idea_title, $clone_urls);
                //Also DM all watchers of the idea:
                foreach ($this->Chains->read(array(
                    'chainsourcetype' => 10573, //WATCHERS
                    'chainidearight' => $i['ideaid'],
                ), array(), 0) as $watcher) {
                    $this->Chains->message($watcher['chainsourceup'], $idea_title, $clone_urls);
                }
            }


            //ADD PROFILE?
            foreach ($this->Chains->read(array(
                'chainsourcetype' => 7545, //Following Add
                'chainidearight' => $i['ideaid'],
            ), array('chainsourceup')) as $this_tag) {

                //Check if special profile add?
                if (in_array($this_tag['chainsourceup'], $this->config->item('sourceids___43048'))) {

                    //Special Addition:

                    if ($this_tag['chainsourceup'] == 6197 && strlen(trim($x_data['chainvalue'])) >= 2) {

                        //Update Source Title:
                        $this->Sources->update($x_data['chainsourcecreator'], array(
                            'sourcevalue' => $x_data['chainvalue'],
                        ), $x_data['chainsourcecreator']);

                        //Update live session as well:
                        $es_creator[0]['sourcevalue'] = $x_data['chainvalue'];
                        $this->Sources->activate($es_creator[0], true);

                    } elseif ($this_tag['chainsourceup'] == 6198 && isset($media_stats['media_sourcecover']) && filter_var($media_stats['media_sourcecover'], FILTER_VALIDATE_URL)) {

                        //Update Source Cover:
                        //Update profile picture for current user:
                        $this->Sources->update($chainsourcecreator, array(
                            'sourcecover' => $media_stats['media_sourcecover'],
                        ), $chainsourcecreator);

                        //Update live session as well:
                        $es_creator[0]['sourcecover'] = $media_stats['media_sourcecover'];
                        $this->Sources->activate($es_creator[0], true);

                    }

                } else {

                    //Assign tag if following/follower Chain NOT previously assigned:
                    $append_source = append_source($this_tag['chainsourceup'], $x_data['chainsourcecreator'], (isset($source_submitted_data['idea_createtext']) ? $source_submitted_data['idea_createtext'] : null), $i['ideaid']);

                    //See if Session needs to be updated:
                    $source_session = source_session();
                    if ($source_session && $source_session['sourceid']==$x_data['chainsourcecreator'] && $append_source) {
                        $this->Sources->activate($source_session, true);
                    }

                }
            }


            //REMOVE PROFILE?
            foreach ($this->Chains->read(array(
                'chainsourcetype' => 26599, //Following Remove
                'chainidearight' => $i['ideaid'],
            )) as $this_tag) {

                //Remove Following IF previously assigned:
                foreach ($this->Chains->read(array(
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                    'chainsourceup' => $this_tag['chainsourceup'], //CERTIFICATES saved here
                    'chainsourcedown' => $x_data['chainsourcecreator'],
                )) as $existing_x) {

                    $this->Chains->delete($existing_x['chainid'], $x_data['chainsourcecreator']);

                    //See if Session needs to be updated:
                    if ($source_session && $source_session['sourceid'] == $x_data['chainsourcecreator']) {
                        //Yes, update session:
                        $this->Sources->activate($es_creator[0], true);
                    }
                }
            }


            //Notify watchers IF any:
            $watchers = $this->Chains->read(array(
                'chainsourcetype' => 10573, //WATCHERS
                'chainidearight' => $i['ideaid'],
            ), array(), 0);
            if (count($watchers)) {

                $es_discoverer = $this->Sources->read(array(
                    'sourceid' => $x_data['chainsourcecreator'],
                ));
                if (count($es_discoverer)) {

                    //Fetch Discoverer contact:
                    $discoverer_contact = '';
                    foreach ($this->config->item('sources___34541') as $chainsourcetype => $m) {
                        foreach ($this->Chains->read(array(
                            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                            'chainsourcedown' => $x_data['chainsourcecreator'],
                            'chainsourceup' => $chainsourcetype,
                            'LENGTH(chainvalue)>0' => null,
                        )) as $x_progress) {
                            $discoverer_contact .= $m['m__title'] . ':' . "\n" . $x_progress['chainvalue'] . "\n\n";
                        }
                    }

                    //Notify Idea Watchers
                    $sent_watchers = array();
                    foreach ($watchers as $watcher) {
                        if (!in_array(intval($watcher['chainsourceup']), $sent_watchers)) {
                            array_push($sent_watchers, intval($watcher['chainsourceup']));

                            $this->Chains->message($watcher['chainsourceup'], $es_discoverer[0]['sourcevalue'] . ' idea_discovered: ' . view_idea_title($i, true),
                                //Message Body:
                                view_idea_title($i, true) . ':' . "\n" . 'https://' . $domain_url . view_memory(42903, 33286) . $i['ideahashtag'] . "\n\n" .
                                (strlen($x_data['chainvalue']) ? $x_data['chainvalue'] . "\n\n" : '') .
                                $es_discoverer[0]['sourcevalue'] . ':' . "\n" . 'https://' . $domain_url . view_memory(42903, 42902) . $es_discoverer[0]['sourcehandle'] . "\n\n" .
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


    function history($i, $sourceid, $current_level = 0)
    {

        unset($i['ideaexternal']);
        unset($i['ideacache']);
        unset($i['chainsourcetype']);
        unset($i['chainsourceup']);
        unset($i['chainsourcedown']);
        unset($i['chainkey']);
        unset($i['chainsourcedomain']);
        unset($i['chainvoid']);
        unset($i['chainsourcecreator']);
        unset($i['chainidealeft']);
        unset($i['chainidearight']);
        unset($i['chainid']);
        unset($i['chainvalue']);

        $input__selection = in_array($i['ideatype'], $this->config->item('sourceids___7712'));
        $input__text = in_array($i['ideatype'], $this->config->item('sourceids___43002'));
        $i['uploaded_media'] = array();
        $i['user_idea_discovered'] = array();
        $i['user_written_response'] = array();
        $i['current_level'] = $current_level;
        $i['idea_next'] = array();
        $current_level++;

        //Append media if any:
        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42294')) . ')' => null, //Media
            'chainidearight' => $i['ideaid'],
        ), array('chainsourceup'), 0, 0, array('chainkey' => 'ASC')) as $media) {

            //Get metadata:
            foreach ($this->Chains->read(array(
                'chainsourceup IN (' . join(',', $this->config->item('sourceids___44393')) . ')' => null, //Media JSON
                'chainsourcedown' => $media['sourceid'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            ), array('chainsourceup'), 0) as $source_group) {
                if (strlen($source_group['chainvalue'])) {
                    $media[$source_group['sourcehandle']] = $source_group['chainvalue'];
                }
            }

            unset($media['chaintime']);
            unset($media['chainsourceup']);
            unset($media['chainsourcedown']);
            unset($media['chainkey']);
            unset($media['chainsourcedomain']);
            unset($media['chainvoid']);
            unset($media['chainsourcecreator']);
            unset($media['chainidealeft']);
            unset($media['chainidearight']);
            unset($media['chainid']);
            unset($media['chainvalue']);
            unset($media['sourceid']);
            unset($media['sourcevalue']);
            unset($media['sourcehandle']);
            unset($media['sourceexternal']);
            array_push($i['uploaded_media'], $media);
        }

        //Append Discovery if any:
        foreach ($this->Chains->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainsourcecreator' => $sourceid,
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 1) as $x) {

            unset($x['chainsourcetype']);
            unset($x['chainsourceup']);
            unset($x['chainsourcedown']);
            unset($x['chainkey']);
            unset($x['chainsourcedomain']);
            unset($x['chainvoid']);
            unset($x['chainidealeft']);
            unset($x['chainidearight']);
            unset($x['chainsourcecreator']);
            unset($x['chainvalue']);
            unset($x['chainid']);

            $i['user_idea_discovered'] = $x;

            if ($input__text) {
                //Since it has been idea_discovered and its a text input, lots fetch the written response:
                foreach ($this->Chains->read(array(
                    'chainsourcetype' => 33532, //Private Reply
                    'chainidealeft' => $i['ideaid'],
                    'chainsourcecreator' => $sourceid,
                ), array('chainidearight'), 0, 1, array('chainid' => 'DESC')) as $response) {
                    $i['user_written_response'] = $response;
                }
            }
        }


        if ($i['user_idea_discovered']) {
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42267')) . ')' => null, //Active Sequence Down
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC')) as $next_i) {
                array_push($i['idea_next'], $this->Chains->history($next_i, $sourceid, $current_level));
            }
        }


        return $i;

    }

    function historyidea_discovered($i, $sourceid, $current_level = 0)
    {

        $input__selection = in_array($i['ideatype'], $this->config->item('sourceids___7712'));
        $input__text = in_array($i['ideatype'], $this->config->item('sourceids___43002'));
        $i['current_level'] = $current_level;
        $i['idea_next'] = array();
        $i['user_idea_discovered'] = array();
        $i['user_written_response'] = array();
        $current_level++;

        //Append Discovery if any:
        foreach ($this->Chains->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainsourcecreator' => $sourceid,
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 1) as $x) {
            $i['user_idea_discovered'] = $x;
        }

        if ($input__text) {
            foreach ($this->Chains->read(array(
                'chainsourcetype' => 33532, //Private Reply
                'chainidealeft' => $i['ideaid'],
                'chainsourcecreator' => $sourceid,
            ), array('chainidearight'), 0, 1, array('chainid' => 'DESC')) as $response) {
                $i['user_written_response'] = $response;
            }
        }


        if ($i['user_idea_discovered']) {
            foreach (($input__selection ? $this->Chains->read(array(
                'chainsourcetype' => 7712, //Input Choice
                'chainsourcecreator' => $sourceid,
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight')) : $this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42267')) . ')' => null, //Active Sequence Down
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC'))) as $next_i) {
                array_push($i['idea_next'], $this->Chains->historyidea_discovered($next_i, $sourceid, $current_level));
            }
        }


        return $i;

    }

    function flat($i, $current_level = 0, $previous_input__selection = false)
    {

        $i['current_level'] = $current_level;
        $input__selection = in_array($i['ideatype'], $this->config->item('sourceids___7712'));
        $single_choice = in_array($i['ideatype'], $this->config->item('sourceids___33331'));
        $is_required = count($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainsourceup' => 28239, //Required
        )));
        $total_next = $this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42267')) . ')' => null, //Active Sequence Down
            'chainidealeft' => $i['ideaid'],
        ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC'), '*', null, false);

        $min_steps = ($input__selection ? ($is_required ? 1 : 0) : count($total_next)); //Can be improved later...
        $max_steps = ($input__selection ? ($single_choice ? 1 : count($total_next)) : count($total_next));
        $i['idea_list_config'] = idea_list_config($i['ideaid'], false);
        $i['stats'] = array(
            'max_level' => $current_level,
            'all_steps' => 1,
            'min_steps' => $min_steps,
            'max_steps' => $max_steps,
            'min_choices' => (!$previous_input__selection && $input__selection && count($total_next) ? 1 : 0),
            'max_choices' => ($input__selection && count($total_next) ? 1 : 0),
        );

        $i['idea_next'] = array();
        $current_level++;

        //Append Total Discoveries if any:
        $sub_counter = $this->Chains->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 0, 0, array(), 'COUNT(chainid) as totals');
        $i['idea_count_discovery'] = $sub_counter[0]['totals'];


        foreach ($total_next as $next_i) {

            $result_i = $this->Chains->flat($next_i, $current_level, ($previous_input__selection ? $previous_input__selection : $input__selection));
            array_push($i['idea_next'], $result_i);

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

        if($current_level==0){
            $i['stats']['min_steps']++;
            $i['stats']['max_steps']++;
        }

        return $i;

    }


    function progress($sourceid, $i, $current_level = 0, $loop_breaker_ids = array())
    {

        if (count($loop_breaker_ids) > 0 && in_array($i['ideaid'], $loop_breaker_ids)) {
            return false;
        }

        $copy = $this->Ideas->ids($i, 'AND');
        if (!isset($copy['recursive_idea_ids']) || !count($copy['recursive_idea_ids'])) {
            return false;
        }

        $current_level++;
        array_push($loop_breaker_ids, intval($i['ideaid']));

        //Count completed:
        $list_idea_discovered = array();
        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'chainsourcecreator' => $sourceid, //Belongs to this Member
            'chainidealeft IN (' . join(',', $copy['recursive_idea_ids']) . ')' => null,
        ), array('chainidealeft'), 0) as $completed) {
            if (!in_array($completed['ideahashtag'], $list_idea_discovered)) {
                array_push($list_idea_discovered, $completed['ideahashtag']);
            }
        }


        //Calculate common steps and expansion steps recursively for this u:
        $metadata_this = array(
            'fixed_total' => count($copy['recursive_idea_ids']),
            'list_total' => $copy['recursive_idea_ids'],
            'fixed_idea_discovered' => count($list_idea_discovered),
            'list_idea_discovered' => $list_idea_discovered,
        );

        //Now let's check possible expansions:
        if (count($copy['recursive_idea_ids'])) {
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___7704')) . ')' => null, //Discovery Expansion
                'chainsourcecreator' => $sourceid, //Belongs to this Member
                'chainidealeft IN (' . join(',', $copy['recursive_idea_ids']) . ')' => null,
            ), array('chainidearight')) as $expansion_in) {

                //Fetch recursive:
                $progress = $this->Chains->progress($sourceid, $expansion_in, $current_level, $loop_breaker_ids);

                if (!$progress && !count($this->Chains->read(array(
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                        'chainsourcecreator' => $sourceid, //Belongs to this Member
                        'chainidealeft' => $expansion_in['ideaid'],
                    )))) {
                    $progress = array(
                        'fixed_total' => 1,
                        'list_total' => array($expansion_in['ideaid']),
                        'fixed_idea_discovered' => 0,
                        'list_idea_discovered' => array(),
                    );
                }

                //Addup completion stats for this:
                $metadata_this['fixed_total'] += $progress['fixed_total'];
                $metadata_this['fixed_idea_discovered'] += $progress['fixed_idea_discovered'];

                if ($progress['list_total'] && count($progress['list_total'])) {
                    foreach ($progress['list_total'] as $tree_id) {
                        if (!in_array($tree_id, $metadata_this['list_total'])) {
                            array_push($metadata_this['list_total'], $tree_id);
                        }
                    }
                }

                if ($progress['list_idea_discovered'] && count($progress['list_idea_discovered'])) {
                    foreach ($progress['list_idea_discovered'] as $tree_id) {
                        if (!in_array($tree_id, $metadata_this['list_idea_discovered'])) {
                            array_push($metadata_this['list_idea_discovered'], $tree_id);
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
                $metadata_this['fixed_completed_percentage'] = intval(floor($metadata_this['fixed_idea_discovered'] / $metadata_this['fixed_total'] * 100));
            }


        }

        //Return results:
        return $metadata_this;

    }


}