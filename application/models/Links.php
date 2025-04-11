<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Links extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $external_sync = false, $update_observed = true)
    {

        //Required field:
        if (!isset($add_fields['linkplayertype']) || !in_array($add_fields['linkplayertype'], $this->config->item('playerids___4593'))) {
            log_error('Links->create() failed to create because of invalid Link type @' . $add_fields['linkplayertype'], array(
                'linkplayercreator' => $add_fields['linkplayercreator'],
                'linkplayerdown' => $add_fields['linkplayertype'],
            ));
            return false;
        }

        //Set some defaults:
        if (!isset($add_fields['linkplayercreator']) || intval($add_fields['linkplayercreator']) < 1) {
            $add_fields['linkplayercreator'] = 14068; //GUEST MEMBER
        }

        //Set some defaults:
        if (!isset($add_fields['linktext'])) {
            $add_fields['linktext'] = null;
        } elseif (is_array($add_fields['linktext'])) {
            $add_fields['linktext'] = serialize($add_fields['linktext']);
        }

        //Is this an observation link that should replace an older observation, if any:
        if($update_observed && in_array($add_fields['linkplayertype'], $this->config->item('playerids___1308453'))){
            $read_fields = $add_fields;
            if(isset($read_fields['linktext'])){
                unset($read_fields['linktext']);
            }
            foreach ($this->Links->read($read_fields, array(), 1) as $last_observation) {
                //Update the previous observed link:
                return $this->Links->update($last_observation['linkid'], $add_fields);
            }
        }

        //Set some zero defaults if not set:
        foreach (array('linkidearight', 'linkidealeft', 'linkplayerdown', 'linkplayerup', 'linknumber') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Append Domain:
        if (!isset($add_fields['linkplayerdomain']) || $add_fields['linkplayerdomain'] < 1) {
            $add_fields['linkplayerdomain'] = website_setting(0, $add_fields['linkplayercreator']);
        }

        //Append time:
        if (!isset($add_fields['linktime']) || is_null($add_fields['linktime'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $add_fields['linktime'] = $d->format("Y-m-d H:i:s");
        }

        //Let's log, Always auto generated:
        $insert_link_id = ( isset($add_fields['linkid']) ? $add_fields['linkid'] : 0 );
        $this->db->insert('menchledger', $add_fields);

        //Fetch inserted id:
        $add_fields['linkid'] = ( $insert_link_id>0 ? $insert_link_id : $this->db->insert_id() );

        //All good?
        if ($add_fields['linkid'] < 1) {
            log_error('Links->create() Failed to create', array(
                'linkplayercreator' => $add_fields['linkplayercreator'],
                'linkplayerdown' => $add_fields['linkplayercreator'],
            ));
            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['linkplayerup'] > 0) {
                update_algolia(12274, $add_fields['linkplayerup']);
            }

            if ($add_fields['linkplayerdown'] > 0) {
                update_algolia(12274, $add_fields['linkplayerdown']);
            }

            if ($add_fields['linkidealeft'] > 0) {
                update_algolia(12273, $add_fields['linkidealeft']);
            }

            if ($add_fields['linkidearight'] > 0) {
                update_algolia(12273, $add_fields['linkidearight']);
            }
        }


        //See if this Link type has any followers that are essentially subscribed to it:
        $tr_watchers = $this->Players->tree(42381, $add_fields['linkplayertype'], $this->config->item('playerids___30820'), array(), 1);
        if (is_array($tr_watchers) && count($tr_watchers)) {

            //yes, start drafting email to be sent to them
            $u_name = 'Unknown';
            if ($add_fields['linkplayercreator'] > 0) {
                //Fetch member details:
                $add_e = $this->Players->read(array(
                    'playerid' => $add_fields['linkplayercreator'],
                ));
                if (count($add_e)) {
                    $u_name = $add_e[0]['playertext'];
                }
            }


            //Email Subject:
            $players___4593 = $this->config->item('players___4593'); //Link Types
            $subject = $u_name . ' ' . $players___4593[$add_fields['linkplayertype']]['m__title'];

            //Compose email body, start with Link content:
            $html_message = (strlen($add_fields['linktext']) > 0 ? $add_fields['linktext'] : '') . "\n";


            //Append Link object Links:
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
                    $html_message .= $m['m__title'] . ':' . "\n" . $this->config->item('base_url') . view_app_link(12722) . '?linkid=' . $add_fields[$m['m__handle']] . "\n\n";

                }

            }

            //Finally append DISCOVERY ID:
            $html_message .= 'Link: #' . $add_fields['linkid'] . "\n" . $this->config->item('base_url') . view_app_link(12722) . '?linkid=' . $add_fields['linkid'] . "\n\n";

            //Message Watchers:
            foreach ($tr_watchers as $tr_watcher) {
                //Do not inform the member who just took the action:
                if ($tr_watcher['playerid'] != $add_fields['linkplayercreator']) {
                    $this->Links->message($tr_watcher['playerid'], $subject, $html_message, array(
                        'linkidearight' => $add_fields['linkidearight'],
                        'linkidealeft' => $add_fields['linkidealeft'],
                        'linkplayerdown' => $add_fields['linkplayerdown'],
                        'linkplayerup' => $add_fields['linkplayerup'],
                    ));
                }
            }
        }

        //Return:
        return $add_fields;

    }


    function read($query_filters = array(), $joins_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('linkid' => 'DESC'), $select = '*', $group_by = null, $access_limit = true)
    {

        $this->db->select($select);
        $this->db->from('menchledger');

        //IDEA JOIN?
        $idea_join = false;
        if (in_array('linkidealeft', $joins_objects)) {
            $idea_join = true;
            $this->db->join('nodeideas', 'linkidealeft=ideaid', 'left');
        } elseif (in_array('linkidearight', $joins_objects)) {
            $idea_join = true;
            $this->db->join('nodeideas', 'linkidearight=ideaid', 'left');
        } elseif (in_array('linkideaid', $joins_objects)) {
            $idea_join = true;
            $this->db->join('nodeideas', 'linkid=ideaid', 'left');
        }

        //PLAYER JOIN?
        $player_join = false;
        if (in_array('linkplayerup', $joins_objects)) {
            $player_join = true;
            $this->db->join('nodeplayers', 'linkplayerup=playerid', 'left');
        } elseif (in_array('linkplayerdown', $joins_objects)) {
            $player_join = true;
            $this->db->join('nodeplayers', 'linkplayerdown=playerid', 'left');
        } elseif (in_array('linkplayertype', $joins_objects)) {
            $player_join = true;
            $this->db->join('nodeplayers', 'linkplayertype=playerid', 'left');
        } elseif (in_array('linkplayercreator', $joins_objects)) {
            $player_join = true;
            $this->db->join('nodeplayers', 'linkplayercreator=playerid', 'left');
        } elseif (in_array('linkplayerdomain', $joins_objects)) {
            $player_join = true;
            $this->db->join('nodeplayers', 'linkplayerdomain=playerid', 'left');
        } elseif (in_array('linkplayerid', $joins_objects)) {
            $player_join = true;
            $this->db->join('nodeplayers', 'linkid=playerid', 'left');
        }

        $link_void_found = false;
        foreach ($query_filters as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
            if (substr_count($key, 'linkvoid')) {
                $link_void_found = true;
            }
        }
        if (!$link_void_found) {
            //Auto add:
            $this->db->where('linkvoid', 0); //Not Void
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
            if (array_intersect(array('linkidealeft', 'linkidearight'), $joins_objects)) {
                //Idea results:
                foreach ($results as $key => $value) {
                    if (!idea_access(null, $value['ideaid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            } elseif (array_intersect(array('linkplayerup', 'linkplayerdown'), $joins_objects)) {
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


    function update($linkid, $update_columns, $linkplayercreator = 0)
    {

        //Fetch Link before updating:
        foreach ($this->Links->read(array(
            'linkid' => $linkid,
        )) as $old_x) {

            if (!isset($update_columns['linkplayercreator'])) {
                //Fetch session player:
                $update_columns['linkplayercreator'] = ($linkplayercreator > 0 ? $linkplayercreator : $old_x['linkplayercreator'] );
            }

            //Make sure something changed:
            $something_changed = false;
            foreach(array('linkplayertype','linkplayerup','linkplayerdown','linkidealeft','linkidearight','linknumber','linktext','linkvoid') as $must_change){
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


            //Create New Link
            $new_x = $this->Links->create($update_columns, true, false);

            if ($new_x['linkid'] > 0) {
                //Void Old Link:
                $this->db->query("UPDATE menchledger SET linkvoid = " . $new_x['linkid'] . " WHERE linkid = " . $linkid . ";");
                return $this->db->affected_rows();
            }

        }

        //Invalid link:
        return 0;

    }


    function delete($linkid, $linkplayercreator = 0)
    {

        //Validate $linkid
        foreach ($this->Links->read(array(
            'linkid' => $linkid,
        )) as $old_x) {

            //Set default player:
            if (!$linkplayercreator) {
                //Fetch session player:
                $player_session = player_session();
                $linkplayercreator = ($player_session ? $player_session['playerid'] : ($old_x['linkplayercreator'] > 0 ? $old_x['linkplayercreator'] : 14068 /* Guest Member */));
            }

            //Determine VOID link type in 1 of the 4 link groups:
            if (in_array($old_x['linkplayertype'], $this->config->item('playerids___31777'))) {
                //Discovery
                $linkplayertype = 44397;
            } elseif (in_array($old_x['linkplayertype'], $this->config->item('playerids___4486'))) {
                //Ideation
                $linkplayertype = 44396;
            } elseif (in_array($old_x['linkplayertype'], $this->config->item('playerids___13550'))) {
                //Contribution
                $linkplayertype = 44399; //TODO Adjust this
            } elseif (in_array($old_x['linkplayertype'], $this->config->item('playerids___32292'))) {
                //Sourcing
                $linkplayertype = 44399; //TODO Adjust this
            } else {
                return 0; //Should not happen
            }

            $new_x = $this->Links->create(array(
                'linkplayercreator' => $linkplayercreator,
                'linkplayertype' => $linkplayertype,
                'linkvoid' => $linkid, //We insert as void since this is a void link only
            ));

            if (!isset($new_x['linkid'])) {
                return 0; //Should not happen
            }

            //Void this Link:
            $this->db->query("UPDATE menchledger SET linkvoid = " . $new_x['linkid'] . " WHERE linkid = " . $linkid . ";");
            return $this->db->affected_rows();

        }

        //Invalid link:
        return 0;

    }

    function readalt($query_filters = array(), $joins_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('link_id' => 'DESC'), $select = '*', $group_by = null)
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


    function select($focus__id, $o__id, $element_id, $player_createid, $migratehandle, $linkid = 0)
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
        $links_removed = -1;
        $status = 0;
        $delete_redirect = '';
        $delete_element = '';

        if ($element_id == 4486 && $linkid > 0) {

            //IDEA LINK TYPE
            $status = $this->Links->update($linkid, array(
                'linkplayercreator' => $player_session['playerid'],
                'linkplayertype' => $player_createid,
            ));

        } elseif ($element_id == 13550 && $linkid > 0) {

            //SOURCE LINK TYPE
            $status = $this->Links->update($linkid, array(
                'linkplayertype' => $player_createid,
                'linkplayercreator' => $player_session['playerid'],
            ));

        } elseif ($element_id == 32292 && $linkid > 0) {

            //SOURCE/SOURCE LINK
            $status = $this->Links->update($linkid, array(
                'linkplayertype' => $player_createid,
                'linkplayercreator' => $player_session['playerid'],
            ));

        } elseif (0 && $element_id == 42795 && $o__id > 0 && $player_createid && $player_session) {

            if (!$linkid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Links->read(array(
                    'linkplayerup' => $o__id,
                    'linkplayerdown' => $player_session['playerid'],
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___42795')) . ')' => null, //Follow
                ), array(), 1) as $found_x) {
                    $linkid = $found_x['linkid'];
                }
            }

            //Follow
            if ($linkid > 0) {
                //Updating reaction:
                if (in_array($player_createid, $this->config->item('playerids___42850'))) {
                    //Unsubscribe
                    $status = $this->Links->delete($linkid, $player_session['playerid']); //Media Removed
                } else {
                    $status = $this->Links->update($linkid, array(
                        'linkplayertype' => $player_createid,
                        'linkplayercreator' => $player_session['playerid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Links->create(array(
                    'linkplayercreator' => $player_session['playerid'],
                    'linkplayerup' => $o__id,
                    'linkplayerdown' => $player_session['playerid'],
                    'linkplayertype' => $player_createid,
                )));
            }

        } elseif ($element_id == 42260 && $o__id > 0 && $player_createid && $player_session) {

            //Check if current value?
            if (!$linkid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Links->read(array(
                    'linkplayerup' => $player_session['playerid'],
                    'linkidearight' => $o__id,
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___42260')) . ')' => null, //Reactions
                ), array(), 1) as $found_x) {
                    $linkid = $found_x['linkid'];
                }
            }

            //Reactions...
            if ($linkid > 0) {
                if (in_array($player_createid, $this->config->item('playerids___42850'))) {
                    $status = $this->Links->delete($linkid, $player_session['playerid']); //Removed
                } else {
                    //Updating reaction:
                    $status = $this->Links->update($linkid, array(
                        'linkplayertype' => $player_createid,
                        'linkplayercreator' => $player_session['playerid'],
                    ));
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Links->create(array(
                    'linkplayercreator' => $player_session['playerid'],
                    'linkplayerup' => $player_session['playerid'],
                    'linkidearight' => $o__id,
                    'linkplayertype' => $player_createid,
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
                    $already_responded = count($this->Links->read(array(
                        'linkplayerup IN (' . join(',', $this->config->item('playerids___' . $dynamic_playerid)) . ')' => null, //All possible answers
                        'linkidearight' => $o__id,
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                    )));

                } else {

                    $already_responded = count($this->Links->read(array(
                        'linkplayerup' => $dynamic_playerid,
                        'linkidearight' => $o__id,
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                    )));

                }

                if (!$already_responded) {
                    //We are missing a required response, auto open modal:
                    $auto_open_idea_modal = 1;
                }

            }

        }

        return array(
            'status' => intval($status) && ($links_removed < 0 || $links_removed > 0),
            'message' => 'Delete status [' . $status . '] with ' . $links_removed . ' Links removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
            'auto_open_idea_modal' => $auto_open_idea_modal,
        );

    }

    function message($playerid, $subject, $html_message, $x_data = array(), $template_ideaid = 0, $linkplayerdomain = 0, $log_tr = true, $demo_only = false)
    {

        $sms_subscriber = false;

        //Bypass notifications?
        if (!count($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42256')) . ')' => null, //Writes
            'linkplayerup' => 31779, //Mandatory Emails
            'linkidearight' => $template_ideaid,
        )))) {

            $notification_levels = $this->Links->read(array(
                'linkplayerup IN (' . join(',', $this->config->item('playerids___30820')) . ')' => null, //Active Subscriber
                'linkplayerdown' => $playerid,
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ));
            if (!count($notification_levels)) {
                return array(
                    'status' => 0,
                    'message' => 'User is not an active subscriber',
                );
            }
            $sms_subscriber = in_array($notification_levels[0]['linkplayerup'], $this->config->item('playerids___28915'));
        }

        //Make sure not recently contacted:
        /*
         * Did not work with subscription notifications which could happen back to back...
         *
        $minutes_limit = 60;
        foreach($this->Links->read(array(
            'linkplayertype' => 29399,
            'linkplayercreator' => $playerid,
            'linktime >=' => date("Y-m-d H:i:s", strtotime('-'.$minutes_limit.' minutes')),
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
        foreach ($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            'linkplayerup' => 3288, //Email
            'linkplayerdown' => $playerid,
        )) as $player_data) {

            if (!filter_var($player_data['linktext'], FILTER_VALIDATE_EMAIL)) {
                $this->Links->delete($player_data['linkid'], $playerid);
                continue;
            }

            array_push($stats['email_addresses'], $player_data['linktext']);

        }

        if (count($stats['email_addresses']) > 0) {
            //Send email:
            dispatch_email($stats['email_addresses'], $subject, $html_message, $playerid, $x_data, $template_ideaid, $linkplayerdomain, $log_tr, $demo_only);
        }


        //Should we send SMS?
        $twilio_account_sid = website_setting(30859);
        $twilio_auth_token = website_setting(30860);
        $twilio_from_number = website_setting(27673);
        if ($sms_subscriber && $twilio_account_sid && $twilio_auth_token && $twilio_from_number) {

            //Yes, generate message
            $sms_message = get_domain('m__title', $playerid, $linkplayerdomain) . ' Emailed [' . $subject . '] to ' . join(' & ', $stats['email_addresses']) . ' (Also Check Spam)';

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n", " ", $sms_message);

            //Send SMS
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                'linkplayerup' => 4783, //Phone
                'linkplayerdown' => $playerid,
            )) as $player_data) {

                foreach (explode('|||', wordwrap($sms_message, view_memory(6404, 27891), "|||")) as $single_message) {

                    $sms_sent = dispatch_sms($player_data['linktext'], $single_message, $playerid, $x_data, $template_ideaid, $linkplayerdomain, $log_tr, $demo_only);

                    if (!$sms_sent) {
                        //bad number, remove it:
                        $this->Links->delete($player_data['linkid'], $playerid);
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


    function broadcast($list_of_playerid, $i, $linkplayerdomain = 0, $ensure_unidea_discovered = true, $demo_only = false)
    {

        $total_sent = 0;
        $linkplayerdomain = ($linkplayerdomain > 0 ? $linkplayerdomain : (isset($i['linkplayerdomain']) ? $i['linkplayerdomain'] : 0));
        $subject_line = view_idea_title($i, true);
        $wacth_repeat_handles = array();

        foreach ($list_of_playerid as $count => $x) {

            if (in_array($x['playerhandle'], $wacth_repeat_handles)) {
                //This should not happen! Report bug:
                log_error('Links->broadcast() Detected duplicate Player Handle Bug: ' . $x['playerhandle'], array(
                    'linkplayerdown' => $x['playerid'],
                ));
                break; //Stop sending more messages!
            }

            //Map this handle:
            array_push($wacth_repeat_handles, $x['playerhandle']);


            if (!isset($x['playerid'])) {
                //Invalid input for sending:
                log_error('Links->broadcast() Invalid Player', array(
                    'linkplayercreator' => $x['playerid'],
                    'linkplayerdown' => 26582, //Messener
                ));
                continue;
            } elseif ($ensure_unidea_discovered && count($this->Links->read(array(
                    'linkidealeft' => $i['ideaid'],
                    'linkplayercreator' => $x['playerid'],
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                )))) {
                //Already idea_discovered:
                continue;
            }


            $content_message = view_idea_links($i, $x['playerid'], true); //Hide the show more content if any
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', $content_message);
            }

            //Append children as options:
            $html_message = '';
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Sequence Down
                'linkidealeft' => $i['ideaid'],
            ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC')) as $down_or) {
                //Has this user idea_discovered this idea or no?
                $html_message .= '<div class="line">' . view_idea_title($down_or, true) . ':</div>';
                $html_message .= '<div class="line">' . 'https://' . get_domain('m__message', $x['playerid'], $linkplayerdomain) . view_memory(42903, 33286) . $down_or['ideahashtag'] . (idea_is_startable($down_or) ? '/' . view_memory(6404, 4235) : '') . '?playerhandle=' . $x['playerhandle'] . '&time=' . time() . '&hash=' . view_hash(time() . $x['playerhandle']) . '</div>';
            }

            //Where to place the next step?
            if (substr_count($content_message, 'link_here') == 1) {
                //We have direction to place the next step somewhere specific:
                $content_message = str_replace('link_here', $html_message, $content_message);
            } else {
                $content_message = $content_message . $html_message;
            }

            $message = $this->Links->message($x['playerid'], $subject_line, $content_message, array(
                'linkidealeft' => $i['ideaid'],
            ), $i['ideaid'], $linkplayerdomain, true, $demo_only);

            //Mark as idea_discovered:
            if ($message['status'] && !$demo_only) {
                $this->Links->idea_discovered(43142, $x['playerid'], 0, $i);
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
        foreach ($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //Active Sequence Up
            'linkidearight' => $focus_ideaid,
        ), array('linkidealeft')) as $idea_previous) {

            //Validate Selection:
            $input__selection = in_array($idea_previous['ideatype'], $this->config->item('playerids___7712'));
            $is_selected = count($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'linkidealeft' => $idea_previous['ideaid'],
                'linkidearight' => $focus_ideaid,
                'linkplayercreator' => $playerid,
            )));

            if ($playerid > 0 && !$is_selected && $input__selection) {
                continue;
            }

            //Did we find it?
            if ($idea_previous['ideahashtag'] == $target_ideahashtag) {
                return array($idea_previous);
            }

            //Keep looking further up:
            $website_finder = $this->Links->previousidea($playerid, $target_ideahashtag, $idea_previous['ideaid'], $loop_breaker_ids);
            if (count($website_finder)) {
                array_push($website_finder, $idea_previous);
                return $website_finder;
            }
        }

        //Did not find any followings:
        return array();

    }


    function previousidea_discovered($focus_ideaid, $linkplayercreator, $loop_breaker_ids = array())
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

        foreach ($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //Active Sequence Up
            'linkidearight' => $focus_ideaid,
        ), array('linkidealeft')) as $prev_i) {

            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'linkplayercreator' => $linkplayercreator,
                'linkidealeft' => $prev_i['ideaid'],
            ), array('linkidearight')) as $x) {
                return $x['ideahashtag'];
            }

            return $this->Links->previousideaidea_discovered($prev_i['ideaid'], $linkplayercreator, $loop_breaker_ids);
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

        foreach ($this->Links->read(array(
            'linkidealeft' => $i['ideaid'],
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
        ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_ideaid && !$found_trigger) {
                if ($next_i['ideaid'] == $find_after_ideaid) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            $is_selected = count($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'linkidealeft' => $i['ideaid'],
                'linkidearight' => $next_i['ideaid'],
                'linkplayercreator' => $playerid,
            )));
            if ($input__selection && !$is_selected) {
                continue;
            }


            //Return this if everything is completed, or if this is incomplete:
            if ($target_completed || !count($this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    'linkplayercreator' => $playerid,
                    'linkidealeft' => $next_i['ideaid'],
                )))) {
                return $next_i['ideahashtag'];
            }

            //Keep looking deeper:
            $next__url = $this->Links->idea_next($playerid, $target_ideahashtag, $next_i, 0, false, $target_completed, $loop_breaker_ids);
            if ($next__url) {
                return $next__url;
            }

        }


        if ($search_up && $target_ideahashtag != $i['ideahashtag']) {
            //Check Previous/Up
            $current_previous = $i['ideaid'];
            foreach (array_reverse($this->Links->previousidea($playerid, $target_ideahashtag, $i['ideaid'])) as $p_i) {
                //Find the next siblings:
                $next__url = $this->Links->idea_next($playerid, $target_ideahashtag, $p_i, $current_previous, false, $target_completed);
                if ($next__url) {
                    return $next__url;
                }
                $current_previous = $p_i['ideaid'];
            }
        }

        //Nothing found:
        return null;

    }


    function idea_discovered($linkplayertype, $linkplayercreator, $target_ideaid = 0, $i, $player_submitted_data = array(), $x_data = array())
    {

        if (!$linkplayercreator || !in_array($linkplayertype, $this->config->item('playerids___31777' /* DISCOVERIES */))) {
            return log_error('idea_discovered() Invalid linkplayertype @' . $linkplayertype . ' missing in @31777 OR Missing $linkplayercreator', array(
                'linkplayerdown' => $linkplayercreator,
                'linkplayercreator' => $linkplayercreator,
            ));
        }

        //Do we need to save text/upload ?
        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $input__upload = in_array($i['ideatype'], $this->config->item('playerids___43004'));
        $input__text = in_array($i['ideatype'], $this->config->item('playerids___43002')) || in_array($i['ideatype'], $this->config->item('playerids___43003'));
        $is_required = count($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $i['ideaid'],
            'linkplayerup' => 28239, //Required
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
                //Link Input
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
            $player_private_replies = $this->Links->read(array(
                'linkplayertype' => 33532, //Private Reply
                'linkidealeft' => $i['ideaid'],
                'linkplayercreator' => $linkplayercreator,
            ), array('linkidearight'), 0, 1, array('linkid' => 'DESC'));


            //All validated, lets create the new idea:
            if (strlen($player_submitted_data['idea_createtext']) || count($player_submitted_data['uploaded_media'])) {

                if (count($player_private_replies)) {

                    //Update existing response if different:
                    if ($player_submitted_data['idea_createtext'] != $player_private_replies[0]['ideatext']) {

                        $this->Ideas->update($player_private_replies[0]['ideaid'], array(
                            'ideatext' => $player_submitted_data['idea_createtext'],
                        ), $linkplayercreator);

                    }

                    $this_ideaid = $player_private_replies[0]['ideaid'];

                } else {

                    //Create a new idea:
                    $idea_new = $this->Ideas->create(array(
                        'ideatext' => $player_submitted_data['idea_createtext'],
                        'ideatype' => 6677,
                    ), $linkplayercreator);

                    $this_ideaid = $idea_new['idea_create']['ideaid'];

                    //Link to this idea:
                    $this->Links->create(array(
                        'linkplayertype' => 33532, //Private Reply
                        'linkplayercreator' => $linkplayercreator,
                        'linkidealeft' => $i['ideaid'],
                        'linkidearight' => $idea_new['idea_create']['ideaid'],
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
                    //Delete Links
                    $links_removed = $this->Ideas->delete($player_private_replies[0]['ideaid'], $linkplayercreator);
                }

            }

        }

        $x_data['linkplayercreator'] = $linkplayercreator;
        $x_data['linkplayertype'] = $linkplayertype;
        $x_data['linkidealeft'] = $i['ideaid']; //Always add Idea to linkidealeft

        //Add link right only if we have a target idea we are navigating to
        if ($target_ideaid > 0 && (!isset($x_data['linkidearight']) || !intval($x_data['linkidearight']))) {
            $x_data['linkidearight'] = $target_ideaid;
        }

        if (!isset($x_data['linktext'])) {
            $x_data['linktext'] = null;
        }

        $es_creator = $this->Players->read(array(
            'playerid' => $linkplayercreator,
        ));

        //Make sure not duplicate:
        foreach ($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkidealeft' => (isset($x_data['linkidealeft']) ? $x_data['linkidealeft'] : 0),
            'linkidearight' => (isset($x_data['linkidearight']) ? $x_data['linkidearight'] : 0),
            'linkplayercreator' => $linkplayercreator,
            'linktext' => $x_data['linktext'],
        )) as $already_idea_discovered) {

            //Update:
            $this->Links->update($already_idea_discovered['linkid'], $x_data);

            //Already idea_discovered!
            return array(
                'status' => 1,
                'message' => 'Already idea_discovered',
                'new_x' => $already_idea_discovered,
            );
        }

        //Add new Link:
        $domain_url = get_domain('m__message', $linkplayercreator);

        //Create Link:
        $new_x = $this->Links->create($x_data);

        //Auto Complete OR Answers:
        if ($input__selection) {
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'linkplayercreator' => $x_data['linkplayercreator'],
                'linkidealeft' => $i['ideaid'],
            ), array('linkidearight'), 0) as $next_i) {

                if (in_array($next_i['ideatype'], $this->config->item('playerids___43039'))) {
                    continue;
                }

                $has_children = count($this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //IDEA LINKS
                    'linkidealeft' => $next_i['ideaid'],
                ), array('linkidearight'), 0, 0));

                if (!$has_children) {
                    //Mark as complete:
                    $this->Links->idea_discovered(idea_type_discovery($next_i), $x_data['linkplayercreator'], $target_ideaid, $next_i, $x_data);
                }
            }
        }

        if ($x_data['linkplayercreator'] && in_array($x_data['linkplayertype'], $this->config->item('playerids___40986'))) {

            //Discovery Triggers?
            $clone_urls = '';
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___32275')) . ')' => null, //DISCOVERY TRIGGERS
                'linkidealeft' => $i['ideaid'],
            ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC')) as $clone_i) {

                if ($clone_i['linkplayertype'] == 32247) {

                    //Discovery Clone
                    $new_title = $es_creator[0]['playertext'] . ' ' . $clone_i['ideatext'];
                    $result = $this->Ideas->copy($clone_i['ideaid'], 0, $x_data['linkplayercreator'], null, $new_title);
                    if ($result['status']) {

                        //Add as watcher:
                        $this->Links->create(array(
                            'linkplayertype' => 10573, //WATCHERS
                            'linkplayercreator' => $x_data['linkplayercreator'],
                            'linkplayerup' => $x_data['linkplayercreator'],
                            'linkidearight' => $result['idea_createid'],
                        ));

                        //New link:
                        $clone_urls .= $new_title . ':' . "\n" . 'https://' . get_domain('m__message', $x_data['linkplayercreator']) . view_memory(42903, 33286) . $result['idea_createhashtag'] . "\n\n";
                    }

                } elseif ($clone_i['linkplayertype'] == 32304) {

                    //Discovery Forget: Remove all Discoveries made by this user:
                    foreach ($this->Links->read(array(
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___31777')) . ')' => null, //DISCOVERIES
                        'linkidealeft' => $i['ideaid'],
                        'linkplayercreator' => $x_data['linkplayercreator'],
                    )) as $remove_x) {
                        $this->Links->delete($remove_x['linkid'], $x_data['linkplayercreator']);
                    }

                }

            }

            if (strlen($clone_urls)) {
                //Send DM with all the new clone idea URLs:
                $clone_urls = $clone_urls . 'You have been added as a subscriber so you will be notified when anyone start using your link.';
                $idea_title = view_idea_title($i, true);
                $this->Links->message($x_data['linkplayercreator'], $idea_title, $clone_urls);
                //Also DM all watchers of the idea:
                foreach ($this->Links->read(array(
                    'linkplayertype' => 10573, //WATCHERS
                    'linkidearight' => $i['ideaid'],
                ), array(), 0) as $watcher) {
                    $this->Links->message($watcher['linkplayerup'], $idea_title, $clone_urls);
                }
            }


            //ADD PROFILE?
            foreach ($this->Links->read(array(
                'linkplayertype' => 7545, //Following Add
                'linkidearight' => $i['ideaid'],
            ), array('linkplayerup')) as $this_tag) {

                //Check if special profile add?
                if (in_array($this_tag['linkplayerup'], $this->config->item('playerids___43048'))) {

                    //Special Addition:

                    if ($this_tag['linkplayerup'] == 6197 && strlen(trim($x_data['linktext'])) >= 2) {

                        //Update Player Title:
                        $this->Players->update($x_data['linkplayercreator'], array(
                            'playertext' => $x_data['linktext'],
                        ), $x_data['linkplayercreator']);

                        //Update live session as well:
                        $es_creator[0]['playertext'] = $x_data['linktext'];
                        $this->Players->activate($es_creator[0], true);

                    } elseif ($this_tag['linkplayerup'] == 6198 && isset($media_stats['media_playercover']) && filter_var($media_stats['media_playercover'], FILTER_VALIDATE_URL)) {

                        //Update Player Cover:
                        //Update profile picture for current user:
                        $this->Players->update($linkplayercreator, array(
                            'playercover' => $media_stats['media_playercover'],
                        ), $linkplayercreator);

                        //Update live session as well:
                        $es_creator[0]['playercover'] = $media_stats['media_playercover'];
                        $this->Players->activate($es_creator[0], true);

                    }

                } else {

                    //Assign tag if following/follower Link NOT previously assigned:
                    $append_player = append_player($this_tag['linkplayerup'], $x_data['linkplayercreator'], (isset($player_submitted_data['idea_createtext']) ? $player_submitted_data['idea_createtext'] : null), $i['ideaid']);

                    //See if Session needs to be updated:
                    $player_session = player_session();
                    if ($player_session && $player_session['playerid']==$x_data['linkplayercreator'] && $append_player) {
                        $this->Players->activate($player_session, true);
                    }

                }
            }


            //REMOVE PROFILE?
            foreach ($this->Links->read(array(
                'linkplayertype' => 26599, //Following Remove
                'linkidearight' => $i['ideaid'],
            )) as $this_tag) {

                //Remove Following IF previously assigned:
                foreach ($this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                    'linkplayerup' => $this_tag['linkplayerup'], //CERTIFICATES saved here
                    'linkplayerdown' => $x_data['linkplayercreator'],
                )) as $existing_x) {

                    $this->Links->delete($existing_x['linkid'], $x_data['linkplayercreator']);

                    //See if Session needs to be updated:
                    if ($player_session && $player_session['playerid'] == $x_data['linkplayercreator']) {
                        //Yes, update session:
                        $this->Players->activate($es_creator[0], true);
                    }
                }
            }


            //Notify watchers IF any:
            $watchers = $this->Links->read(array(
                'linkplayertype' => 10573, //WATCHERS
                'linkidearight' => $i['ideaid'],
            ), array(), 0);
            if (count($watchers)) {

                $es_discoverer = $this->Players->read(array(
                    'playerid' => $x_data['linkplayercreator'],
                ));
                if (count($es_discoverer)) {

                    //Fetch Discoverer contact:
                    $discoverer_contact = '';
                    foreach ($this->config->item('players___34541') as $linkplayertype => $m) {
                        foreach ($this->Links->read(array(
                            'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                            'linkplayerdown' => $x_data['linkplayercreator'],
                            'linkplayerup' => $linkplayertype,
                            'LENGTH(linktext)>0' => null,
                        )) as $x_progress) {
                            $discoverer_contact .= $m['m__title'] . ':' . "\n" . $x_progress['linktext'] . "\n\n";
                        }
                    }

                    //Notify Idea Watchers
                    $sent_watchers = array();
                    foreach ($watchers as $watcher) {
                        if (!in_array(intval($watcher['linkplayerup']), $sent_watchers)) {
                            array_push($sent_watchers, intval($watcher['linkplayerup']));

                            $this->Links->message($watcher['linkplayerup'], $es_discoverer[0]['playertext'] . ' idea_discovered: ' . view_idea_title($i, true),
                                //Message Body:
                                view_idea_title($i, true) . ':' . "\n" . 'https://' . $domain_url . view_memory(42903, 33286) . $i['ideahashtag'] . "\n\n" .
                                (strlen($x_data['linktext']) ? $x_data['linktext'] . "\n\n" : '') .
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


    function history($i, $playerid, $idea_level = 0)
    {

        unset($i['ideaexternal']);
        unset($i['ideacache']);
        unset($i['linkplayertype']);
        unset($i['linkplayerup']);
        unset($i['linkplayerdown']);
        unset($i['linknumber']);
        unset($i['linkplayerdomain']);
        unset($i['linkvoid']);
        unset($i['linkplayercreator']);
        unset($i['linkidealeft']);
        unset($i['linkidearight']);
        unset($i['linkid']);
        unset($i['linktext']);

        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $input__text = in_array($i['ideatype'], $this->config->item('playerids___43002'));
        $i['uploaded_media'] = array();
        $i['user_idea_discovered'] = array();
        $i['user_written_response'] = array();
        $i['idea_level'] = $idea_level;
        $i['idea_next'] = array();
        $idea_level++;

        //Append media if any:
        foreach ($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42294')) . ')' => null, //Media
            'linkidearight' => $i['ideaid'],
        ), array('linkplayerup'), 0, 0, array('linknumber' => 'ASC')) as $media) {

            //Get metadata:
            foreach ($this->Links->read(array(
                'linkplayerup IN (' . join(',', $this->config->item('playerids___44393')) . ')' => null, //Media JSON
                'linkplayerdown' => $media['playerid'],
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ), array('linkplayerup'), 0) as $player_group) {
                if (strlen($player_group['linktext'])) {
                    $media[$player_group['playerhandle']] = $player_group['linktext'];
                }
            }

            unset($media['linktime']);
            unset($media['linkplayerup']);
            unset($media['linkplayerdown']);
            unset($media['linknumber']);
            unset($media['linkplayerdomain']);
            unset($media['linkvoid']);
            unset($media['linkplayercreator']);
            unset($media['linkidealeft']);
            unset($media['linkidearight']);
            unset($media['linkid']);
            unset($media['linktext']);
            unset($media['playerid']);
            unset($media['playertext']);
            unset($media['playerhandle']);
            unset($media['playerexternal']);
            array_push($i['uploaded_media'], $media);
        }

        //Append Discovery if any:
        foreach ($this->Links->read(array(
            'linkidealeft' => $i['ideaid'],
            'linkplayercreator' => $playerid,
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 1) as $x) {

            unset($x['linkplayertype']);
            unset($x['linkplayerup']);
            unset($x['linkplayerdown']);
            unset($x['linknumber']);
            unset($x['linkplayerdomain']);
            unset($x['linkvoid']);
            unset($x['linkidealeft']);
            unset($x['linkidearight']);
            unset($x['linkplayercreator']);
            unset($x['linktext']);
            unset($x['linkid']);

            $i['user_idea_discovered'] = $x;

            if ($input__text) {
                //Since it has been idea_discovered and its a text input, lots fetch the written response:
                foreach ($this->Links->read(array(
                    'linkplayertype' => 33532, //Private Reply
                    'linkidealeft' => $i['ideaid'],
                    'linkplayercreator' => $playerid,
                ), array('linkidearight'), 0, 1, array('linkid' => 'DESC')) as $response) {
                    $i['user_written_response'] = $response;
                }
            }
        }


        if ($i['user_idea_discovered']) {
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
                'linkidealeft' => $i['ideaid'],
            ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC')) as $next_i) {
                array_push($i['idea_next'], $this->Links->history($next_i, $playerid, $idea_level));
            }
        }


        return $i;

    }

    function historyidea_discovered($i, $playerid, $idea_level = 0)
    {

        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $input__text = in_array($i['ideatype'], $this->config->item('playerids___43002'));
        $i['idea_level'] = $idea_level;
        $i['idea_next'] = array();
        $i['user_idea_discovered'] = array();
        $i['user_written_response'] = array();
        $idea_level++;

        //Append Discovery if any:
        foreach ($this->Links->read(array(
            'linkidealeft' => $i['ideaid'],
            'linkplayercreator' => $playerid,
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 1) as $x) {
            $i['user_idea_discovered'] = $x;
        }

        if ($input__text) {
            foreach ($this->Links->read(array(
                'linkplayertype' => 33532, //Private Reply
                'linkidealeft' => $i['ideaid'],
                'linkplayercreator' => $playerid,
            ), array('linkidearight'), 0, 1, array('linkid' => 'DESC')) as $response) {
                $i['user_written_response'] = $response;
            }
        }


        if ($i['user_idea_discovered']) {
            foreach (($input__selection ? $this->Links->read(array(
                'linkplayertype' => 7712, //Input Choice
                'linkplayercreator' => $playerid,
                'linkidealeft' => $i['ideaid'],
            ), array('linkidearight')) : $this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
                'linkidealeft' => $i['ideaid'],
            ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC'))) as $next_i) {
                array_push($i['idea_next'], $this->Links->historyidea_discovered($next_i, $playerid, $idea_level));
            }
        }


        return $i;

    }

    function flat($i, $idea_level = 0)
    {

        $i['idea_level'] = $idea_level;
        $idea_level++;
        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $single_choice = in_array($i['ideatype'], $this->config->item('playerids___33331'));
        $is_required = count($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $i['ideaid'],
            'linkplayerup' => 28239, //Required
        )));
        $total_next = $this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'linkidealeft' => $i['ideaid'],
        ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC'), '*', null, false);

        $i['idea_list_config'] = idea_list_config($i['ideaid'], false);
        $i['stats'] = array(
            'max_level' => $idea_level,
            'max_steps' => ($input__selection ? ($single_choice ? 1 : count($total_next)) : count($total_next)),
            'min_steps' => ($input__selection ? ($is_required ? 1 : 0) : count($total_next)), //Can be improved later...
            'or_steps' => ($input__selection && count($total_next) ? 1 : 0),
        );
        $i['idea_next'] = array();

        //Append Total Discoveries if any:
        $sub_counter = $this->Links->read(array(
            'linkidealeft' => $i['ideaid'],
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 0, 0, array(), 'COUNT(linkid) as totals');
        $i['idea_count_discovery'] = $sub_counter[0]['totals'];


        foreach ($total_next as $next_i) {

            $result_i = $this->Links->flat($next_i, $idea_level);
            array_push($i['idea_next'], $result_i);

            if ($result_i['stats']['max_level'] > $i['stats']['max_level']) {
                $i['stats']['max_level'] = $result_i['stats']['max_level'];
            }

            $i['stats']['max_steps'] += $result_i['stats']['max_steps'];
            if (!$input__selection || $is_required) {
                $i['stats']['min_steps'] += $result_i['stats']['min_steps'];
            }
            $i['stats']['or_steps'] += $result_i['stats']['or_steps'];

        }

        return $i;

    }


    function progress($playerid, $i, $idea_level = 0, $loop_breaker_ids = array())
    {

        if (count($loop_breaker_ids) > 0 && in_array($i['ideaid'], $loop_breaker_ids)) {
            return false;
        }

        $copy = $this->Ideas->ids($i, 'AND');
        if (!isset($copy['recursive_idea_ids']) || !count($copy['recursive_idea_ids'])) {
            return false;
        }

        $idea_level++;
        array_push($loop_breaker_ids, intval($i['ideaid']));

        //Count completed:
        $list_idea_discovered = array();
        foreach ($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkplayercreator' => $playerid, //Belongs to this Member
            'linkidealeft IN (' . join(',', $copy['recursive_idea_ids']) . ')' => null,
        ), array('linkidealeft'), 0) as $completed) {
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
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'linkplayercreator' => $playerid, //Belongs to this Member
                'linkidealeft IN (' . join(',', $copy['recursive_idea_ids']) . ')' => null,
            ), array('linkidearight')) as $expansion_in) {

                //Fetch recursive:
                $progress = $this->Links->progress($playerid, $expansion_in, $idea_level, $loop_breaker_ids);

                if (!$progress && !count($this->Links->read(array(
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                        'linkplayercreator' => $playerid, //Belongs to this Member
                        'linkidealeft' => $expansion_in['ideaid'],
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

        if ($idea_level == 1) {

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