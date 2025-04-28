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
        if (!isset($add_fields['chainplayertype']) || !in_array($add_fields['chainplayertype'], $this->config->item('playerids___4593'))) {
            log_error('Chains->create() failed to create because of invalid Chain type @' . $add_fields['chainplayertype'], array(
                'chainplayercreator' => $add_fields['chainplayercreator'],
                'chainplayerdown' => $add_fields['chainplayertype'],
            ));
            return false;
        }

        //Set some defaults:
        if (!isset($add_fields['chainplayercreator']) || intval($add_fields['chainplayercreator']) < 1) {
            $add_fields['chainplayercreator'] = 14068; //GUEST MEMBER
        }

        //Set some defaults:
        if (!isset($add_fields['chaintext'])) {
            $add_fields['chaintext'] = null;
        } elseif (is_array($add_fields['chaintext'])) {
            $add_fields['chaintext'] = serialize($add_fields['chaintext']);
        }

        //Is this an observation chain that should replace an older observation, if any:
        if($update_observed && in_array($add_fields['chainplayertype'], $this->config->item('playerids___1308453'))){
            $read_fields = $add_fields;
            if(isset($read_fields['chaintext'])){
                unset($read_fields['chaintext']);
            }
            foreach ($this->Chains->read($read_fields, array(), 1) as $last_observation) {
                //Update the previous observed chain:
                return $this->Chains->update($last_observation['chainid'], $add_fields);
            }
        }

        //Set some zero defaults if not set:
        foreach (array('chainidearight', 'chainidealeft', 'chainplayerdown', 'chainplayerup', 'chainnumber') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Append Domain:
        if (!isset($add_fields['chainplayerdomain']) || $add_fields['chainplayerdomain'] < 1) {
            $add_fields['chainplayerdomain'] = website_setting(0, $add_fields['chainplayercreator']);
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
                'chainplayercreator' => $add_fields['chainplayercreator'],
                'chainplayerdown' => $add_fields['chainplayercreator'],
            ));
            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['chainplayerup'] > 0) {
                update_algolia(12274, $add_fields['chainplayerup']);
            }

            if ($add_fields['chainplayerdown'] > 0) {
                update_algolia(12274, $add_fields['chainplayerdown']);
            }

            if ($add_fields['chainidealeft'] > 0) {
                update_algolia(12273, $add_fields['chainidealeft']);
            }

            if ($add_fields['chainidearight'] > 0) {
                update_algolia(12273, $add_fields['chainidearight']);
            }
        }


        //See if this Chain type has any followers that are essentially subscribed to it:
        $tr_watchers = $this->Players->tree(42381, $add_fields['chainplayertype'], $this->config->item('playerids___30820'), array(), 1);
        if (is_array($tr_watchers) && count($tr_watchers)) {

            //yes, start drafting email to be sent to them
            $u_name = 'Unknown';
            if ($add_fields['chainplayercreator'] > 0) {
                //Fetch member details:
                $add_e = $this->Players->read(array(
                    'playerid' => $add_fields['chainplayercreator'],
                ));
                if (count($add_e)) {
                    $u_name = $add_e[0]['playertext'];
                }
            }


            //Email Subject:
            $players___4593 = $this->config->item('players___4593'); //Chain Types
            $subject = $u_name . ' ' . $players___4593[$add_fields['chainplayertype']]['m__title'];

            //Compose email body, start with Chain content:
            $html_message = (strlen($add_fields['chaintext']) > 0 ? $add_fields['chaintext'] : '') . "\n";


            //Append Chain object Chains:
            foreach ($this->config->item('players___4341') as $playerid => $m) {

                if (in_array(6202, $m['m__following'])) {

                    //IDEA
                    foreach ($this->Ideas->read(array('ideaid' => $add_fields[$m['m__handle']])) as $this_i) {
                        $html_message .= $m['m__title'] . ': ' . view_idea_title($this_i, true) . ':' . "\n" . $this->config->item('base_url') . view_memory(42903, 33286) . $this_i['ideahashtag'] . "\n\n";
                    }

                } elseif (in_array(6160, $m['m__following'])) {

                    //SOURCE
                    foreach ($this->Players->read(array('playerid' => $add_fields[$m['m__handle']])) as $this_e) {
                        $html_message .= $m['m__title'] . ': ' . $this_e['playertext'] . "\n" . $this->config->item('base_url') . view_memory(42903, 42902) . $this_e['playerhandle'] . "\n\n";
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
                if ($tr_watcher['playerid'] != $add_fields['chainplayercreator']) {
                    $this->Chains->message($tr_watcher['playerid'], $subject, $html_message, array(
                        'chainidearight' => $add_fields['chainidearight'],
                        'chainidealeft' => $add_fields['chainidealeft'],
                        'chainplayerdown' => $add_fields['chainplayerdown'],
                        'chainplayerup' => $add_fields['chainplayerup'],
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
        $player_join = false;
        if (in_array('chainplayerup', $joins_objects)) {
            $player_join = true;
            $this->db->join('cacheplayers', 'chainplayerup=playerid', 'left');
        } elseif (in_array('chainplayerdown', $joins_objects)) {
            $player_join = true;
            $this->db->join('cacheplayers', 'chainplayerdown=playerid', 'left');
        } elseif (in_array('chainplayertype', $joins_objects)) {
            $player_join = true;
            $this->db->join('cacheplayers', 'chainplayertype=playerid', 'left');
        } elseif (in_array('chainplayercreator', $joins_objects)) {
            $player_join = true;
            $this->db->join('cacheplayers', 'chainplayercreator=playerid', 'left');
        } elseif (in_array('chainplayerdomain', $joins_objects)) {
            $player_join = true;
            $this->db->join('cacheplayers', 'chainplayerdomain=playerid', 'left');
        } elseif (in_array('chainplayerid', $joins_objects)) {
            $player_join = true;
            $this->db->join('cacheplayers', 'chainid=playerid', 'left');
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
        if($player_join){
            $this->db->where('playerid >', 0);
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
            } elseif (array_intersect(array('chainplayerup', 'chainplayerdown'), $joins_objects)) {
                //Player results:
                foreach ($results as $key => $value) {
                    if (!player_access(null, $value['playerid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            }
        }

        return $results;

    }


    function update($chainid, $update_columns, $chainplayercreator = 0)
    {

        //Fetch Chain before updating:
        foreach ($this->Chains->read(array(
            'chainid' => $chainid,
        )) as $old_x) {

            if (!isset($update_columns['chainplayercreator'])) {
                //Fetch session player:
                $update_columns['chainplayercreator'] = ($chainplayercreator > 0 ? $chainplayercreator : $old_x['chainplayercreator'] );
            }

            //Make sure something changed:
            $something_changed = false;
            foreach(array('chainplayertype','chainplayerup','chainplayerdown','chainidealeft','chainidearight','chainnumber','chaintext','chainvoid') as $must_change){
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


    function delete($chainid, $chainplayercreator = 0)
    {

        //Validate $chainid
        foreach ($this->Chains->read(array(
            'chainid' => $chainid,
        )) as $old_x) {

            //Set default player:
            if (!$chainplayercreator) {
                //Fetch session player:
                $player_session = player_session();
                $chainplayercreator = ($player_session ? $player_session['playerid'] : ($old_x['chainplayercreator'] > 0 ? $old_x['chainplayercreator'] : 14068 /* Guest Member */));
            }

            //Determine VOID chain type in 1 of the 4 chain groups:
            if (in_array($old_x['chainplayertype'], $this->config->item('playerids___31777'))) {
                //Discovery
                $chainplayertype = 44397;
            } elseif (in_array($old_x['chainplayertype'], $this->config->item('playerids___4486'))) {
                //Ideation
                $chainplayertype = 44396;
            } elseif (in_array($old_x['chainplayertype'], $this->config->item('playerids___13550'))) {
                //Contribution
                $chainplayertype = 44399; //TODO Adjust this
            } elseif (in_array($old_x['chainplayertype'], $this->config->item('playerids___32292'))) {
                //Sourcing
                $chainplayertype = 44399; //TODO Adjust this
            } else {
                return 0; //Should not happen
            }

            $new_x = $this->Chains->create(array(
                'chainplayercreator' => $chainplayercreator,
                'chainplayertype' => $chainplayertype,
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


    function select($focus__id, $o__id, $element_id, $player_createid, $migratehandle, $chainid = 0)
    {

        //Authenticate Member:
        $migratehandle = trim(substr($migratehandle, 0, 1) == '@' ? trim(substr($migratehandle, 1)) : $migratehandle);
        $migratehandle = trim(substr($migratehandle, 0, 1) == '#' ? trim(substr($migratehandle, 1)) : $migratehandle);
        $player_session = player_session();
        if (!$player_session) {
            return array(
                'status' => 0,
                'message' => blocked_reasoning(),
            );
        } elseif (intval($o__id) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Target ID',
            );
        } elseif (intval($element_id) < 1 || !count($this->config->item('playerids___' . $element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Variable ID [' . $element_id . ']',
            );
        } elseif (intval($player_createid) < 1 || !in_array($player_createid, $this->config->item('playerids___' . $element_id))) {
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
                'chainplayercreator' => $player_session['playerid'],
                'chainplayertype' => $player_createid,
            ));

        } elseif ($element_id == 13550 && $chainid > 0) {

            //SOURCE CHAIN TYPE
            $status = $this->Chains->update($chainid, array(
                'chainplayertype' => $player_createid,
                'chainplayercreator' => $player_session['playerid'],
            ));

        } elseif ($element_id == 32292 && $chainid > 0) {

            //SOURCE/SOURCE CHAIN
            $status = $this->Chains->update($chainid, array(
                'chainplayertype' => $player_createid,
                'chainplayercreator' => $player_session['playerid'],
            ));

        } elseif (0 && $element_id == 42795 && $o__id > 0 && $player_createid && $player_session) {

            if (!$chainid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Chains->read(array(
                    'chainplayerup' => $o__id,
                    'chainplayerdown' => $player_session['playerid'],
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___42795')) . ')' => null, //Follow
                ), array(), 1) as $found_x) {
                    $chainid = $found_x['chainid'];
                }
            }

            //Follow
            if ($chainid > 0) {
                //Updating reaction:
                if (in_array($player_createid, $this->config->item('playerids___42850'))) {
                    //Unsubscribe
                    $status = $this->Chains->delete($chainid, $player_session['playerid']); //Media Removed
                } else {
                    $status = $this->Chains->update($chainid, array(
                        'chainplayertype' => $player_createid,
                        'chainplayercreator' => $player_session['playerid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Chains->create(array(
                    'chainplayercreator' => $player_session['playerid'],
                    'chainplayerup' => $o__id,
                    'chainplayerdown' => $player_session['playerid'],
                    'chainplayertype' => $player_createid,
                )));
            }

        } elseif ($element_id == 42260 && $o__id > 0 && $player_createid && $player_session) {

            //Check if current value?
            if (!$chainid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Chains->read(array(
                    'chainplayerup' => $player_session['playerid'],
                    'chainidearight' => $o__id,
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___42260')) . ')' => null, //Reactions
                ), array(), 1) as $found_x) {
                    $chainid = $found_x['chainid'];
                }
            }

            //Reactions...
            if ($chainid > 0) {
                if (in_array($player_createid, $this->config->item('playerids___42850'))) {
                    $status = $this->Chains->delete($chainid, $player_session['playerid']); //Removed
                } else {
                    //Updating reaction:
                    $status = $this->Chains->update($chainid, array(
                        'chainplayertype' => $player_createid,
                        'chainplayercreator' => $player_session['playerid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Chains->create(array(
                    'chainplayercreator' => $player_session['playerid'],
                    'chainplayerup' => $player_session['playerid'],
                    'chainidearight' => $o__id,
                    'chainplayertype' => $player_createid,
                )));
            }

        } elseif ($element_id == 4737) {

            //Player Reference
            $status = $this->Ideas->update($o__id, array(
                'ideatype' => $player_createid,
            ), $player_session['playerid']);

            //See if we need to popup the idea edit modal here:

            $players___42179 = $this->config->item('players___42179'); //Dynamic Input Fields
            foreach (array_intersect($this->config->item('playerids___' . $player_createid), $this->config->item('playerids___42179')) as $dynamic_playerid) {

                $superpowers_required = array_intersect($this->config->item('playerids___10957'), $players___42179[$dynamic_playerid]['m__following']);
                if (count($superpowers_required) && !player_session(end($superpowers_required))) {
                    continue;
                }

                //Let's determine the data type:
                $data_types = array_intersect($players___42179[$dynamic_playerid]['m__following'], $this->config->item('playerids___4592'));

                //ASSUME that we found 1 match as expected:
                foreach ($data_types as $data_type_this) {
                    $data_type = $data_type_this;
                    break;
                }
                $is_required = in_array($dynamic_playerid, $this->config->item('playerids___28239')); //Required Settings

                if (!$is_required) {
                    //We are only interested in what is required
                    continue;
                }

                //See if we are missing value:
                if (in_array($data_type, $this->config->item('playerids___42188'))) {

                    //Single or Multiple Choice:
                    $already_responded = count($this->Chains->read(array(
                        'chainplayerup IN (' . join(',', $this->config->item('playerids___' . $dynamic_playerid)) . ')' => null, //All possible answers
                        'chainidearight' => $o__id,
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Chains Active
                    )));

                } else {

                    $already_responded = count($this->Chains->read(array(
                        'chainplayerup' => $dynamic_playerid,
                        'chainidearight' => $o__id,
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Chains Active
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

    function message($playerid, $subject, $html_message, $x_data = array(), $template_ideaid = 0, $chainplayerdomain = 0, $log_tr = true, $demo_only = false)
    {

        $sms_subscriber = false;

        //Bypass notifications?
        if (!count($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42256')) . ')' => null, //Writes
            'chainplayerup' => 31779, //Mandatory Emails
            'chainidearight' => $template_ideaid,
        )))) {

            $notification_levels = $this->Chains->read(array(
                'chainplayerup IN (' . join(',', $this->config->item('playerids___30820')) . ')' => null, //Active Subscriber
                'chainplayerdown' => $playerid,
                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
            ));
            if (!count($notification_levels)) {
                return array(
                    'status' => 0,
                    'message' => 'User is not an active subscriber',
                );
            }
            $sms_subscriber = in_array($notification_levels[0]['chainplayerup'], $this->config->item('playerids___28915'));
        }

        //Make sure not recently contacted:
        /*
         * Did not work with subscription notifications which could happen back to back...
         *
        $minutes_limit = 60;
        foreach($this->Chains->read(array(
            'chainplayertype' => 29399,
            'chainplayercreator' => $playerid,
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
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
            'chainplayerup' => 3288, //Email
            'chainplayerdown' => $playerid,
        )) as $player_data) {

            if (!filter_var($player_data['chaintext'], FILTER_VALIDATE_EMAIL)) {
                $this->Chains->delete($player_data['chainid'], $playerid);
                continue;
            }

            array_push($stats['email_addresses'], $player_data['chaintext']);

        }

        if (count($stats['email_addresses']) > 0) {
            //Send email:
            dispatch_email($stats['email_addresses'], $subject, $html_message, $playerid, $x_data, $template_ideaid, $chainplayerdomain, $log_tr, $demo_only);
        }


        //Should we send SMS?
        $twilio_account_sid = website_setting(30859);
        $twilio_auth_token = website_setting(30860);
        $twilio_from_number = website_setting(27673);
        if ($sms_subscriber && $twilio_account_sid && $twilio_auth_token && $twilio_from_number) {

            //Yes, generate message
            $sms_message = get_domain('m__title', $playerid, $chainplayerdomain) . ' Emailed [' . $subject . '] to ' . join(' & ', $stats['email_addresses']) . ' (Also Check Spam)';

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n", " ", $sms_message);

            //Send SMS
            foreach ($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                'chainplayerup' => 4783, //Phone
                'chainplayerdown' => $playerid,
            )) as $player_data) {

                foreach (explode('|||', wordwrap($sms_message, view_memory(6404, 27891), "|||")) as $single_message) {

                    $sms_sent = dispatch_sms($player_data['chaintext'], $single_message, $playerid, $x_data, $template_ideaid, $chainplayerdomain, $log_tr, $demo_only);

                    if (!$sms_sent) {
                        //bad number, remove it:
                        $this->Chains->delete($player_data['chainid'], $playerid);
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


    function broadcast($list_of_playerid, $i, $chainplayerdomain = 0, $ensure_unidea_discovered = true, $demo_only = false)
    {

        $total_sent = 0;
        $chainplayerdomain = ($chainplayerdomain > 0 ? $chainplayerdomain : (isset($i['chainplayerdomain']) ? $i['chainplayerdomain'] : 0));
        $subject_line = view_idea_title($i, true);
        $wacth_repeat_handles = array();

        foreach ($list_of_playerid as $count => $x) {

            if (in_array($x['playerhandle'], $wacth_repeat_handles)) {
                //This should not happen! Report bug:
                log_error('Chains->broadcast() Detected duplicate Player Handle Bug: ' . $x['playerhandle'], array(
                    'chainplayerdown' => $x['playerid'],
                ));
                break; //Stop sending more messages!
            }

            //Map this handle:
            array_push($wacth_repeat_handles, $x['playerhandle']);


            if (!isset($x['playerid'])) {
                //Invalid input for sending:
                log_error('Chains->broadcast() Invalid Player', array(
                    'chainplayercreator' => $x['playerid'],
                    'chainplayerdown' => 26582, //Messener
                ));
                continue;
                } elseif ($ensure_unidea_discovered && count($this->Chains->read(array(
                    'chainidealeft' => $i['ideaid'],
                    'chainplayercreator' => $x['playerid'],
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___31777')) . ')' => null, //DISCOVERIES
                )))) {
                //Already idea_discovered:
                continue;
            }


            $content_message = view_idea_chains($i, $x['playerid'], true); //Hide the show more content if any
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', $content_message);
            }

            //Append children as options:
            $html_message = '';
            foreach ($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Sequence Down
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0, 0, array('chainnumber' => 'ASC')) as $down_or) {
                //Has this user idea_discovered this idea or no?
                $html_message .= '<div class="line">' . view_idea_title($down_or, true) . ':</div>';
                $html_message .= '<div class="line">' . 'https://' . get_domain('m__message', $x['playerid'], $chainplayerdomain) . view_memory(42903, 33286) . $down_or['ideahashtag'] . (idea_is_startable($down_or) ? '/' . view_memory(6404, 4235) : '') . '?playerhandle=' . $x['playerhandle'] . '&time=' . time() . '&hash=' . view_hash(time() . $x['playerhandle']) . '</div>';
            }

            //Where to place the next step?
            if (substr_count($content_message, 'chain_here') == 1) {
                //We have direction to place the next step somewhere specific:
                $content_message = str_replace('chain_here', $html_message, $content_message);
            } else {
                $content_message = $content_message . $html_message;
            }

            $message = $this->Chains->message($x['playerid'], $subject_line, $content_message, array(
                'chainidealeft' => $i['ideaid'],
            ), $i['ideaid'], $chainplayerdomain, true, $demo_only);

            //Mark as idea_discovered:
            if ($message['status'] && !$demo_only) {
                $this->Chains->idea_discovered(43142, $x['playerid'], 0, $i);
                $total_sent++;
            }

        }

        return $total_sent;
    }


    function previousidea($playerid, $target_ideahashtag, $focus_ideaid, $loop_breaker_ids = array())
    {

        //echo 'Previous:'.$playerid.'/'.$target_ideahashtag.'/'.$focus_ideaid;

        if (count($loop_breaker_ids) > 0 && in_array($focus_ideaid, $loop_breaker_ids)) {
            return array();
        }
        array_push($loop_breaker_ids, intval($focus_ideaid));

        //Fetch followings:
        foreach ($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //Active Sequence Up
            'chainidearight' => $focus_ideaid,
        ), array('chainidealeft')) as $idea_previous) {

            //Validate Selection:
            $input__selection = in_array($idea_previous['ideatype'], $this->config->item('playerids___7712'));
            $is_selected = count($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'chainidealeft' => $idea_previous['ideaid'],
                'chainidearight' => $focus_ideaid,
                'chainplayercreator' => $playerid,
            )));

            if ($playerid > 0 && !$is_selected && $input__selection) {
                continue;
            }

            //Did we find it?
            if ($idea_previous['ideahashtag'] == $target_ideahashtag) {
                return array($idea_previous);
            }

            //Keep looking further up:
            $website_finder = $this->Chains->previousidea($playerid, $target_ideahashtag, $idea_previous['ideaid'], $loop_breaker_ids);
            if (count($website_finder)) {
                array_push($website_finder, $idea_previous);
                return $website_finder;
            }
        }

        //Did not find any followings:
        return array();

    }


    function previousidea_discovered($focus_ideaid, $chainplayercreator, $loop_breaker_ids = array())
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
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //Active Sequence Up
            'chainidearight' => $focus_ideaid,
        ), array('chainidealeft')) as $prev_i) {

            foreach ($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'chainplayercreator' => $chainplayercreator,
                'chainidealeft' => $prev_i['ideaid'],
            ), array('chainidearight')) as $x) {
                return $x['ideahashtag'];
            }

            return $this->Chains->previousideaidea_discovered($prev_i['ideaid'], $chainplayercreator, $loop_breaker_ids);
        }

        //Did not find!
        return false;

    }


    function idea_next($playerid, $target_ideahashtag, $i, $find_after_ideaid = 0, $search_up = true, $target_completed = false, $loop_breaker_ids = array())
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

        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $found_trigger = null;

        foreach ($this->Chains->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
        ), array('chainidearight'), 0, 0, array('chainnumber' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_ideaid && !$found_trigger) {
                if ($next_i['ideaid'] == $find_after_ideaid) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            $is_selected = count($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'chainidealeft' => $i['ideaid'],
                'chainidearight' => $next_i['ideaid'],
                'chainplayercreator' => $playerid,
            )));
            if ($input__selection && !$is_selected) {
                continue;
            }


            //Return this if everything is completed, or if this is incomplete:
            if ($target_completed || !count($this->Chains->read(array(
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    'chainplayercreator' => $playerid,
                    'chainidealeft' => $next_i['ideaid'],
                )))) {
                return $next_i['ideahashtag'];
            }

            //Keep looking deeper:
            $next__url = $this->Chains->idea_next($playerid, $target_ideahashtag, $next_i, 0, false, $target_completed, $loop_breaker_ids);
            if ($next__url) {
                return $next__url;
            }

        }


        if ($search_up && $target_ideahashtag != $i['ideahashtag']) {
            //Check Previous/Up
            $current_previous = $i['ideaid'];
            foreach (array_reverse($this->Chains->previousidea($playerid, $target_ideahashtag, $i['ideaid'])) as $p_i) {
                //Find the next siblings:
                $next__url = $this->Chains->idea_next($playerid, $target_ideahashtag, $p_i, $current_previous, false, $target_completed);
                if ($next__url) {
                    return $next__url;
                }
                $current_previous = $p_i['ideaid'];
            }
        }

        //Nothing found:
        return null;

    }


    function idea_discovered($chainplayertype, $chainplayercreator, $target_ideaid = 0, $i, $player_submitted_data = array(), $x_data = array())
    {

        if (!$chainplayercreator || !in_array($chainplayertype, $this->config->item('playerids___31777' /* DISCOVERIES */))) {
            return log_error('idea_discovered() Invalid chainplayertype @' . $chainplayertype . ' missing in @31777 OR Missing $chainplayercreator', array(
                'chainplayerdown' => $chainplayercreator,
                'chainplayercreator' => $chainplayercreator,
            ));
        }

        //Do we need to save text/upload ?
        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $input__upload = in_array($i['ideatype'], $this->config->item('playerids___43004'));
        $input__text = in_array($i['ideatype'], $this->config->item('playerids___43002')) || in_array($i['ideatype'], $this->config->item('playerids___43003'));
        $is_required = count($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainplayerup' => 28239, //Required
        )));


        if ($input__upload || $input__text) {

            if (!isset($player_submitted_data['idea_createtext'])) {
                $player_submitted_data['idea_createtext'] = null;
            }
            if (!isset($player_submitted_data['uploaded_media'])) {
                $player_submitted_data['uploaded_media'] = array();
            }

            //Must add a new idea, but first let's validate the input:
            if ($i['ideatype'] == 31794 && strlen($player_submitted_data['idea_createtext']) && !is_numeric($player_submitted_data['idea_createtext'])) {
                //Number Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Number',
                );
            } elseif ($i['ideatype'] == 42915 && strlen($player_submitted_data['idea_createtext']) && !filter_var($player_submitted_data['idea_createtext'], FILTER_VALIDATE_URL)) {
                //Chain Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid URL',
                );
            } elseif ($i['ideatype'] == 30350 && strlen($player_submitted_data['idea_createtext']) && !strtotime($player_submitted_data['idea_createtext'])) {
                //Date Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Date',
                );
            }

            //Find most recent answers by this user:
            $player_private_replies = $this->Chains->read(array(
                'chainplayertype' => 33532, //Private Reply
                'chainidealeft' => $i['ideaid'],
                'chainplayercreator' => $chainplayercreator,
            ), array('chainidearight'), 0, 1, array('chainid' => 'DESC'));


            //All validated, lets create the new idea:
            if (strlen($player_submitted_data['idea_createtext']) || count($player_submitted_data['uploaded_media'])) {

                if (count($player_private_replies)) {

                    //Update existing response if different:
                    if ($player_submitted_data['idea_createtext'] != $player_private_replies[0]['ideatext']) {

                        $this->Ideas->update($player_private_replies[0]['ideaid'], array(
                            'ideatext' => $player_submitted_data['idea_createtext'],
                        ), $chainplayercreator);

                    }

                    $this_ideaid = $player_private_replies[0]['ideaid'];

                } else {

                    //Create a new idea:
                    $idea_new = $this->Ideas->create(array(
                        'ideatext' => $player_submitted_data['idea_createtext'],
                        'ideatype' => 6677,
                    ), $chainplayercreator);

                    $this_ideaid = $idea_new['idea_create']['ideaid'];

                    //Chain to this idea:
                    $this->Chains->create(array(
                        'chainplayertype' => 33532, //Private Reply
                        'chainplayercreator' => $chainplayercreator,
                        'chainidealeft' => $i['ideaid'],
                        'chainidearight' => $idea_new['idea_create']['ideaid'],
                    ));

                }

                //Process Media:
                $media_stats = process_media($this_ideaid, $player_submitted_data['uploaded_media']);

            } elseif (count($player_private_replies)) {

                if ($is_required) {
                    return array(
                        'status' => 0,
                        'message' => 'Resposne is required',
                    );
                } else {
                    //Delete Chains
                    $chains_removed = $this->Ideas->delete($player_private_replies[0]['ideaid'], $chainplayercreator);
                }

            }

        }

        $x_data['chainplayercreator'] = $chainplayercreator;
        $x_data['chainplayertype'] = $chainplayertype;
        $x_data['chainidealeft'] = $i['ideaid']; //Always add Idea to chainidealeft

        //Add chain right only if we have a target idea we are navigating to
        if ($target_ideaid > 0 && (!isset($x_data['chainidearight']) || !intval($x_data['chainidearight']))) {
            $x_data['chainidearight'] = $target_ideaid;
        }

        if (!isset($x_data['chaintext'])) {
            $x_data['chaintext'] = null;
        }

        $es_creator = $this->Players->read(array(
            'playerid' => $chainplayercreator,
        ));

        //Make sure not duplicate:
        foreach ($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'chainidealeft' => (isset($x_data['chainidealeft']) ? $x_data['chainidealeft'] : 0),
            'chainidearight' => (isset($x_data['chainidearight']) ? $x_data['chainidearight'] : 0),
            'chainplayercreator' => $chainplayercreator,
            'chaintext' => $x_data['chaintext'],
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
        $domain_url = get_domain('m__message', $chainplayercreator);

        //Create Chain:
        $new_x = $this->Chains->create($x_data);

        //Auto Complete OR Answers:
        if ($input__selection) {
            foreach ($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'chainplayercreator' => $x_data['chainplayercreator'],
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0) as $next_i) {

                if (in_array($next_i['ideatype'], $this->config->item('playerids___43039'))) {
                    continue;
                }

                $has_children = count($this->Chains->read(array(
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //IDEA CHAINS
                    'chainidealeft' => $next_i['ideaid'],
                ), array('chainidearight'), 0, 0));

                if (!$has_children) {
                    //Mark as complete:
                    $this->Chains->idea_discovered(idea_type_discovery($next_i), $x_data['chainplayercreator'], $target_ideaid, $next_i, $x_data);
                }
            }
        }

        if ($x_data['chainplayercreator'] && in_array($x_data['chainplayertype'], $this->config->item('playerids___40986'))) {

            //Discovery Triggers?
            $clone_urls = '';
            foreach ($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___32275')) . ')' => null, //DISCOVERY TRIGGERS
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0, 0, array('chainnumber' => 'ASC')) as $clone_i) {

                if ($clone_i['chainplayertype'] == 32247) {

                    //Discovery Clone
                    $new_title = $es_creator[0]['playertext'] . ' ' . $clone_i['ideatext'];
                    $result = $this->Ideas->copy($clone_i['ideaid'], 0, $x_data['chainplayercreator'], null, $new_title);
                    if ($result['status']) {

                        //Add as watcher:
                        $this->Chains->create(array(
                            'chainplayertype' => 10573, //WATCHERS
                            'chainplayercreator' => $x_data['chainplayercreator'],
                            'chainplayerup' => $x_data['chainplayercreator'],
                            'chainidearight' => $result['idea_createid'],
                        ));

                        //New chain:
                        $clone_urls .= $new_title . ':' . "\n" . 'https://' . get_domain('m__message', $x_data['chainplayercreator']) . view_memory(42903, 33286) . $result['idea_createhashtag'] . "\n\n";
                    }

                } elseif ($clone_i['chainplayertype'] == 32304) {

                    //Discovery Forget: Remove all Discoveries made by this user:
                    foreach ($this->Chains->read(array(
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___31777')) . ')' => null, //DISCOVERIES
                        'chainidealeft' => $i['ideaid'],
                        'chainplayercreator' => $x_data['chainplayercreator'],
                    )) as $remove_x) {
                        $this->Chains->delete($remove_x['chainid'], $x_data['chainplayercreator']);
                    }

                }

            }

            if (strlen($clone_urls)) {
                //Send DM with all the new clone idea URLs:
                $clone_urls = $clone_urls . 'You have been added as a subscriber so you will be notified when anyone start using your chain.';
                $idea_title = view_idea_title($i, true);
                $this->Chains->message($x_data['chainplayercreator'], $idea_title, $clone_urls);
                //Also DM all watchers of the idea:
                foreach ($this->Chains->read(array(
                    'chainplayertype' => 10573, //WATCHERS
                    'chainidearight' => $i['ideaid'],
                ), array(), 0) as $watcher) {
                    $this->Chains->message($watcher['chainplayerup'], $idea_title, $clone_urls);
                }
            }


            //ADD PROFILE?
            foreach ($this->Chains->read(array(
                'chainplayertype' => 7545, //Following Add
                'chainidearight' => $i['ideaid'],
            ), array('chainplayerup')) as $this_tag) {

                //Check if special profile add?
                if (in_array($this_tag['chainplayerup'], $this->config->item('playerids___43048'))) {

                    //Special Addition:

                    if ($this_tag['chainplayerup'] == 6197 && strlen(trim($x_data['chaintext'])) >= 2) {

                        //Update Player Title:
                        $this->Players->update($x_data['chainplayercreator'], array(
                            'playertext' => $x_data['chaintext'],
                        ), $x_data['chainplayercreator']);

                        //Update live session as well:
                        $es_creator[0]['playertext'] = $x_data['chaintext'];
                        $this->Players->activate($es_creator[0], true);

                    } elseif ($this_tag['chainplayerup'] == 6198 && isset($media_stats['media_playercover']) && filter_var($media_stats['media_playercover'], FILTER_VALIDATE_URL)) {

                        //Update Player Cover:
                        //Update profile picture for current user:
                        $this->Players->update($chainplayercreator, array(
                            'playercover' => $media_stats['media_playercover'],
                        ), $chainplayercreator);

                        //Update live session as well:
                        $es_creator[0]['playercover'] = $media_stats['media_playercover'];
                        $this->Players->activate($es_creator[0], true);

                    }

                } else {

                    //Assign tag if following/follower Chain NOT previously assigned:
                    $append_player = append_player($this_tag['chainplayerup'], $x_data['chainplayercreator'], (isset($player_submitted_data['idea_createtext']) ? $player_submitted_data['idea_createtext'] : null), $i['ideaid']);

                    //See if Session needs to be updated:
                    $player_session = player_session();
                    if ($player_session && $player_session['playerid']==$x_data['chainplayercreator'] && $append_player) {
                        $this->Players->activate($player_session, true);
                    }

                }
            }


            //REMOVE PROFILE?
            foreach ($this->Chains->read(array(
                'chainplayertype' => 26599, //Following Remove
                'chainidearight' => $i['ideaid'],
            )) as $this_tag) {

                //Remove Following IF previously assigned:
                foreach ($this->Chains->read(array(
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                    'chainplayerup' => $this_tag['chainplayerup'], //CERTIFICATES saved here
                    'chainplayerdown' => $x_data['chainplayercreator'],
                )) as $existing_x) {

                    $this->Chains->delete($existing_x['chainid'], $x_data['chainplayercreator']);

                    //See if Session needs to be updated:
                    if ($player_session && $player_session['playerid'] == $x_data['chainplayercreator']) {
                        //Yes, update session:
                        $this->Players->activate($es_creator[0], true);
                    }
                }
            }


            //Notify watchers IF any:
            $watchers = $this->Chains->read(array(
                'chainplayertype' => 10573, //WATCHERS
                'chainidearight' => $i['ideaid'],
            ), array(), 0);
            if (count($watchers)) {

                $es_discoverer = $this->Players->read(array(
                    'playerid' => $x_data['chainplayercreator'],
                ));
                if (count($es_discoverer)) {

                    //Fetch Discoverer contact:
                    $discoverer_contact = '';
                    foreach ($this->config->item('players___34541') as $chainplayertype => $m) {
                        foreach ($this->Chains->read(array(
                            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                            'chainplayerdown' => $x_data['chainplayercreator'],
                            'chainplayerup' => $chainplayertype,
                            'LENGTH(chaintext)>0' => null,
                        )) as $x_progress) {
                            $discoverer_contact .= $m['m__title'] . ':' . "\n" . $x_progress['chaintext'] . "\n\n";
                        }
                    }

                    //Notify Idea Watchers
                    $sent_watchers = array();
                    foreach ($watchers as $watcher) {
                        if (!in_array(intval($watcher['chainplayerup']), $sent_watchers)) {
                            array_push($sent_watchers, intval($watcher['chainplayerup']));

                            $this->Chains->message($watcher['chainplayerup'], $es_discoverer[0]['playertext'] . ' idea_discovered: ' . view_idea_title($i, true),
                                //Message Body:
                                view_idea_title($i, true) . ':' . "\n" . 'https://' . $domain_url . view_memory(42903, 33286) . $i['ideahashtag'] . "\n\n" .
                                (strlen($x_data['chaintext']) ? $x_data['chaintext'] . "\n\n" : '') .
                                $es_discoverer[0]['playertext'] . ':' . "\n" . 'https://' . $domain_url . view_memory(42903, 42902) . $es_discoverer[0]['playerhandle'] . "\n\n" .
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


    function history($i, $playerid, $current_level = 0)
    {

        unset($i['ideaexternal']);
        unset($i['ideacache']);
        unset($i['chainplayertype']);
        unset($i['chainplayerup']);
        unset($i['chainplayerdown']);
        unset($i['chainnumber']);
        unset($i['chainplayerdomain']);
        unset($i['chainvoid']);
        unset($i['chainplayercreator']);
        unset($i['chainidealeft']);
        unset($i['chainidearight']);
        unset($i['chainid']);
        unset($i['chaintext']);

        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $input__text = in_array($i['ideatype'], $this->config->item('playerids___43002'));
        $i['uploaded_media'] = array();
        $i['user_idea_discovered'] = array();
        $i['user_written_response'] = array();
        $i['current_level'] = $current_level;
        $i['idea_next'] = array();
        $current_level++;

        //Append media if any:
        foreach ($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42294')) . ')' => null, //Media
            'chainidearight' => $i['ideaid'],
        ), array('chainplayerup'), 0, 0, array('chainnumber' => 'ASC')) as $media) {

            //Get metadata:
            foreach ($this->Chains->read(array(
                'chainplayerup IN (' . join(',', $this->config->item('playerids___44393')) . ')' => null, //Media JSON
                'chainplayerdown' => $media['playerid'],
                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
            ), array('chainplayerup'), 0) as $player_group) {
                if (strlen($player_group['chaintext'])) {
                    $media[$player_group['playerhandle']] = $player_group['chaintext'];
                }
            }

            unset($media['chaintime']);
            unset($media['chainplayerup']);
            unset($media['chainplayerdown']);
            unset($media['chainnumber']);
            unset($media['chainplayerdomain']);
            unset($media['chainvoid']);
            unset($media['chainplayercreator']);
            unset($media['chainidealeft']);
            unset($media['chainidearight']);
            unset($media['chainid']);
            unset($media['chaintext']);
            unset($media['playerid']);
            unset($media['playertext']);
            unset($media['playerhandle']);
            unset($media['playerexternal']);
            array_push($i['uploaded_media'], $media);
        }

        //Append Discovery if any:
        foreach ($this->Chains->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainplayercreator' => $playerid,
            'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 1) as $x) {

            unset($x['chainplayertype']);
            unset($x['chainplayerup']);
            unset($x['chainplayerdown']);
            unset($x['chainnumber']);
            unset($x['chainplayerdomain']);
            unset($x['chainvoid']);
            unset($x['chainidealeft']);
            unset($x['chainidearight']);
            unset($x['chainplayercreator']);
            unset($x['chaintext']);
            unset($x['chainid']);

            $i['user_idea_discovered'] = $x;

            if ($input__text) {
                //Since it has been idea_discovered and its a text input, lots fetch the written response:
                foreach ($this->Chains->read(array(
                    'chainplayertype' => 33532, //Private Reply
                    'chainidealeft' => $i['ideaid'],
                    'chainplayercreator' => $playerid,
                ), array('chainidearight'), 0, 1, array('chainid' => 'DESC')) as $response) {
                    $i['user_written_response'] = $response;
                }
            }
        }


        if ($i['user_idea_discovered']) {
            foreach ($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0, 0, array('chainnumber' => 'ASC')) as $next_i) {
                array_push($i['idea_next'], $this->Chains->history($next_i, $playerid, $current_level));
            }
        }


        return $i;

    }

    function historyidea_discovered($i, $playerid, $current_level = 0)
    {

        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $input__text = in_array($i['ideatype'], $this->config->item('playerids___43002'));
        $i['current_level'] = $current_level;
        $i['idea_next'] = array();
        $i['user_idea_discovered'] = array();
        $i['user_written_response'] = array();
        $current_level++;

        //Append Discovery if any:
        foreach ($this->Chains->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainplayercreator' => $playerid,
            'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 1) as $x) {
            $i['user_idea_discovered'] = $x;
        }

        if ($input__text) {
            foreach ($this->Chains->read(array(
                'chainplayertype' => 33532, //Private Reply
                'chainidealeft' => $i['ideaid'],
                'chainplayercreator' => $playerid,
            ), array('chainidearight'), 0, 1, array('chainid' => 'DESC')) as $response) {
                $i['user_written_response'] = $response;
            }
        }


        if ($i['user_idea_discovered']) {
            foreach (($input__selection ? $this->Chains->read(array(
                'chainplayertype' => 7712, //Input Choice
                'chainplayercreator' => $playerid,
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight')) : $this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
                'chainidealeft' => $i['ideaid'],
            ), array('chainidearight'), 0, 0, array('chainnumber' => 'ASC'))) as $next_i) {
                array_push($i['idea_next'], $this->Chains->historyidea_discovered($next_i, $playerid, $current_level));
            }
        }


        return $i;

    }

    function flat($i, $current_level = 0, $previous_input__selection = false)
    {

        $i['current_level'] = $current_level;
        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $single_choice = in_array($i['ideatype'], $this->config->item('playerids___33331'));
        $is_required = count($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainplayerup' => 28239, //Required
        )));
        $total_next = $this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'chainidealeft' => $i['ideaid'],
        ), array('chainidearight'), 0, 0, array('chainnumber' => 'ASC'), '*', null, false);

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

        //Append Total Discoveries if any:
        $sub_counter = $this->Chains->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
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

        $current_level++;

        return $i;

    }


    function progress($playerid, $i, $current_level = 0, $loop_breaker_ids = array())
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
            'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'chainplayercreator' => $playerid, //Belongs to this Member
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
                'chainplayertype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'chainplayercreator' => $playerid, //Belongs to this Member
                'chainidealeft IN (' . join(',', $copy['recursive_idea_ids']) . ')' => null,
            ), array('chainidearight')) as $expansion_in) {

                //Fetch recursive:
                $progress = $this->Chains->progress($playerid, $expansion_in, $current_level, $loop_breaker_ids);

                if (!$progress && !count($this->Chains->read(array(
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                        'chainplayercreator' => $playerid, //Belongs to this Member
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