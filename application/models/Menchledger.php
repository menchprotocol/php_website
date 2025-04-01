<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menchledger extends CIdea_cache
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
        if (!isset($add_fields['linkplayer']) || intval($add_fields['linkplayer']) < 1) {
            $add_fields['linkplayer'] = 14068; //GUEST MEMBER
        }

        //Only require transaction type:
        if (detect_missing_columns($add_fields, array('linktype'), $add_fields['linkplayer'])) {
            return false;
        }

        if (!in_array($add_fields['linktype'], $this->config->item('playerids___4593'))) {
            $this->Menchledger->create(array(
                'linktype' => 44179, //Triggered
                'linkup' => 4246, //Platform Bug Reports
                'linkdown' => $add_fields['linktype'],
                'linktext' => 'x->create() failed to create because of invalid transaction type @' . $add_fields['linktype'],
                'linkplayer' => $add_fields['linkplayer'],
            ));
            return false;
        }

        //Set some defaults:
        if (!isset($add_fields['linktext'])) {
            $add_fields['linktext'] = null;
        } elseif (is_array($add_fields['linktext'])) {
            $add_fields['linktext'] = serialize($add_fields['linktext']);
        }

        //Set some defaults:
        if (!isset($add_fields['linkdomain']) || $add_fields['linkdomain'] < 1) {
            $add_fields['linkdomain'] = website_setting(0, $add_fields['linkplayer']);
        }

        if (!isset($add_fields['linktime']) || is_null($add_fields['linktime'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $add_fields['linktime'] = $d->format("Y-m-d H:i:s");
        }

        //Set some zero defaults if not set:
        foreach (array('linkright', 'linkleft', 'linkdown', 'linkup', 'linknumber') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Lets log:
        unset($add_fields['linkid']);
        $this->db->insert('menchledger', $add_fields);

        //Fetch inserted id:
        $add_fields['linkid'] = $this->db->insert_id();

        //All good huh?
        if ($add_fields['linkid'] < 1) {

            //This should not happen:
            $this->Menchledger->create(array(
                'linktype' => 44179, //Triggered
                'linkup' => 4246, //Platform Bug Reports
                'linkdown' => $add_fields['linkplayer'],
                'linkplayer' => $add_fields['linkplayer'],
                'linktext' => 'create() Failed to create',
            ));

            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['linkup'] > 0) {
                flag_for_search_indexing(12274, $add_fields['linkup']);
            }

            if ($add_fields['linkdown'] > 0) {
                flag_for_search_indexing(12274, $add_fields['linkdown']);
            }

            if ($add_fields['linkleft'] > 0) {
                flag_for_search_indexing(12273, $add_fields['linkleft']);
            }

            if ($add_fields['linkright'] > 0) {
                flag_for_search_indexing(12273, $add_fields['linkright']);
            }
        }


        //See if this transaction type has any followers that are essentially subscribed to it:
        $tr_watchers = $this->Cacheplayers->fetch_recursive(42381, $add_fields['linktype'], $this->config->item('playerids___30820'), array(), 1);
        if (is_array($tr_watchers) && count($tr_watchers)) {

            //yes, start drafting email to be sent to them
            $u_name = 'Unknown';
            if ($add_fields['linkplayer'] > 0) {
                //Fetch member details:
                $add_e = $this->Cacheplayers->fetch(array(
                    'playerid' => $add_fields['linkplayer'],
                ));
                if (count($add_e)) {
                    $u_name = $add_e[0]['playertext'];
                }
            }


            //Email Subject:
            $players___4593 = $this->config->item('players___4593'); //Transaction Types
            $subject = 'Notification: ' . $u_name . ' ' . $players___4593[$add_fields['linktype']]['m__title'];

            //Compose email body, start with transaction content:
            $html_message = (strlen($add_fields['linktext']) > 0 ? $add_fields['linktext'] : '') . "\n";

            $players___32088 = $this->config->item('players___32088'); //Platform Variables

            //Append transaction object transactions:
            foreach ($this->config->item('players___4341') as $playerid => $m) {

                if (in_array(6202, $m['m__following'])) {

                    //IDEA
                    foreach ($this->Cacheideas->fetch(array('ideaid' => $add_fields[$players___32088[$playerid]['m__message']])) as $this_i) {
                        $html_message .= $m['m__title'] . ': ' . view__idea_title($this_i, true) . ':' . "\n" . $this->config->item('base_url') . view__memory(42903, 33286) . $this_i['ideahashtag'] . "\n\n";
                    }

                } elseif (in_array(6160, $m['m__following'])) {

                    //SOURCE
                    foreach ($this->Cacheplayers->fetch(array('playerid' => $add_fields[$players___32088[$playerid]['m__message']])) as $this_e) {
                        $html_message .= $m['m__title'] . ': ' . $this_e['playertext'] . "\n" . $this->config->item('base_url') . view__memory(42903, 42902) . $this_e['playerhandle'] . "\n\n";
                    }

                } elseif (in_array(4367, $m['m__following'])) {

                    //DISCOVERY
                    $html_message .= $m['m__title'] . ':' . "\n" . $this->config->item('base_url') . view__app_link(12722) . '?linkid=' . $add_fields[$players___32088[$playerid]['m__message']] . "\n\n";

                }

            }

            //Finally append DISCOVERY ID:
            $html_message .= 'TRANSACTION: #' . $add_fields['linkid'] . "\n" . $this->config->item('base_url') . view__app_link(12722) . '?linkid=' . $add_fields['linkid'] . "\n\n";

            //Send to all Watchers:
            foreach ($tr_watchers as $tr_watcher) {
                //Do not inform the member who just took the action:
                if ($tr_watcher['playerid'] != $add_fields['linkplayer']) {
                    $this->Menchledger->send_dm($tr_watcher['playerid'], $subject, $html_message, array(
                        'linkright' => $add_fields['linkright'],
                        'linkleft' => $add_fields['linkleft'],
                        'linkdown' => $add_fields['linkdown'],
                        'linkup' => $add_fields['linkup'],
                        // Save $add_fields['linkid'] ?
                    ));
                }
            }
        }

        //Return:
        return $add_fields;

    }


    function fetch($query_filters = array(), $joins_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('linkid' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('menchledger');

        //IDEA JOIN?
        if (in_array('linkleft', $joins_objects)) {
            $this->db->join('cacheideas', 'linkleft=ideaid', 'left');
        } elseif (in_array('linkright', $joins_objects)) {
            $this->db->join('cacheideas', 'linkright=ideaid', 'left');
        }

        //SOURCE JOIN?
        if (in_array('linkup', $joins_objects)) {
            $this->db->join('cacheplayers', 'linkup=playerid', 'left');
        } elseif (in_array('linkdown', $joins_objects)) {
            $this->db->join('cacheplayers', 'linkdown=playerid', 'left');
        } elseif (in_array('linktype', $joins_objects)) {
            $this->db->join('cacheplayers', 'linktype=playerid', 'left');
        } elseif (in_array('linkplayer', $joins_objects)) {
            $this->db->join('cacheplayers', 'linkplayer=playerid', 'left');
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
                break;
            }
        }
        if (!$link_void_found) {
            //Auto add:
            $this->db->where('linkvoid', 0); //Not Void
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
        if ($select == '*' && isset($_SERVER['SERVER_NAME'])) {
            if (array_intersect(array('linkleft', 'linkright'), $joins_objects)) {
                //Idea results:
                $player_e = superpower_unlocked();
                foreach ($results as $key => $value) {
                    if (!access_level_i(null, $value['ideaid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            } elseif (array_intersect(array('linkup', 'linkdown'), $joins_objects)) {
                //Player results:
                foreach ($results as $key => $value) {
                    if (!access_level_player(null, $value['playerid'], $value)) {
                        unset($results[$key]); //Remove this option
                    }
                }
            }
        }

        return $results;

    }


    function update($id, $update_columns, $linkplayer = 0)
    {

        //Fetch transaction before updating:
        $before_data = $this->Menchledger->fetch(array(
            'linkid' => $id,
        ));
        if (!count($before_data)) {
            //Invalid link:
            return 0;
        }

        if (!$linkplayer) {
            //Fetch session player:
            $player_e = superpower_unlocked();
            $linkplayer = ($player_e ? $player_e['playerid'] : ($before_data[0]['linkplayer'] > 0 ? $before_data[0]['linkplayer'] : 14068 /* Guest Member */));
        }

        if (!count($update_columns)) {

            //Trying to VOID

            //Determine if we are removing a discovery, idea or Player:
            if (in_array($before_data[0]['linktype'], $this->config->item('playerids___6255')) && $before_data[0]['linktype'] != 44397) {
                $linktype = 44397; //Undiscovered Discovery
            } elseif ($before_data[0]['linkleft'] > 0 || $before_data[0]['linkright'] > 0) {
                $linktype = 44396; //Unpublished Idea
            } else {
                //It must be a Player then:
                $linktype = 44399; //Player Removed
            }

            //Create new Link:
            $update_columns = array(
                'linkplayer' => $linkplayer,
                'linktype' => $linktype,
            );

        } else {

            //Just set player:
            $update_columns['linkplayer'] = $linkplayer;

        }

        //We are updating something:
        $x = $this->Menchledger->create(array_merge($before_data[0], $update_columns));

        if (isset($x['linkid']) && $x['linkid'] > 0) {
            //Void Old Link:
            $this->db->where('linkid', intval($id));
            $this->db->update('menchledger', array(
                'linkvoid' => $x['linkid'],
            ));
            return $this->db->affected_rows();
        }

        return 0;
    }


    function x_update_instant_select($focus__id, $o__id, $element_id, $new_playerid, $migrate_s__handle, $linkid = 0)
    {

        //Authenticate Member:
        $migrate_s__handle = trim(substr($migrate_s__handle, 0, 1) == '@' ? trim(substr($migrate_s__handle, 1)) : $migrate_s__handle);
        $migrate_s__handle = trim(substr($migrate_s__handle, 0, 1) == '#' ? trim(substr($migrate_s__handle, 1)) : $migrate_s__handle);
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
        } elseif (intval($element_id) < 1 || !count($this->config->item('playerids___' . $element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Variable ID [' . $element_id . ']',
            );
        } elseif (intval($new_playerid) < 1 || !in_array($new_playerid, $this->config->item('playerids___' . $element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            );
        }


        //See if anything is being deleted:
        $auto_open_idea_editor_modal = 0;
        $deletion_redirect = null;
        $delete_element = null;
        $links_removed = -1;
        $status = 0;

        if ($element_id == 4486 && $linkid > 0) {

            //IDEA LINK TYPE
            $status = $this->Menchledger->update($linkid, array(
                'linktype' => $new_playerid,
            ), $player_e['playerid']);

        } elseif ($element_id == 13550 && $linkid > 0) {

            //SOURCE LINK TYPE
            $status = $this->Menchledger->update($linkid, array(
                'linktype' => $new_playerid,
            ), $player_e['playerid']);

        } elseif ($element_id == 32292 && $linkid > 0) {

            //SOURCE/SOURCE LINK
            $status = $this->Menchledger->update($linkid, array(
                'linktype' => $new_playerid,
            ), $player_e['playerid']);

        } elseif ($element_id == 42795 && $o__id > 0 && $new_playerid && $player_e) {

            if (!$linkid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Menchledger->fetch(array(
                    'linkup' => $o__id,
                    'linkdown' => $player_e['playerid'],
                    'linktype IN (' . join(',', $this->config->item('playerids___42795')) . ')' => null, //Follow
                ), array(), 1) as $found_x) {
                    $linkid = $found_x['linkid'];
                }
            }

            //Follow
            if ($linkid > 0) {
                //Updating reaction:
                if (in_array($new_playerid, $this->config->item('playerids___42850'))) {
                    //Unsubscribe
                    $status = $this->Menchledger->update($linkid, array(), $player_e['playerid']); //Media Removed
                } else {
                    $status = $this->Menchledger->update($linkid, array(
                        'linktype' => $new_playerid,
                    ), $player_e['playerid']);
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Menchledger->create(array(
                    'linkplayer' => $player_e['playerid'],
                    'linkup' => $o__id,
                    'linkdown' => $player_e['playerid'],
                    'linktype' => $new_playerid,
                )));
            }

        } elseif ($element_id == 42260 && $o__id > 0 && $new_playerid && $player_e) {

            //Check if current value?
            if (!$linkid) {
                //Double check database as it may be updating newly selected value:
                foreach ($this->Menchledger->fetch(array(
                    'linkup' => $player_e['playerid'],
                    'linkright' => $o__id,
                    'linktype IN (' . join(',', $this->config->item('playerids___42260')) . ')' => null, //Reactions
                ), array(), 1) as $found_x) {
                    $linkid = $found_x['linkid'];
                }
            }

            //Reactions...
            if ($linkid > 0) {
                if (in_array($new_playerid, $this->config->item('playerids___42850'))) {
                    $status = $this->Menchledger->update($linkid, array(), $player_e['playerid']); //Removed
                } else {
                    //Updating reaction:
                    $status = $this->Menchledger->update($linkid, array(
                        'linktype' => $new_playerid,
                    ), $player_e['playerid']);
                }
            } else {
                //Inserting new reaction:
                $status = count($this->Menchledger->create(array(
                    'linkplayer' => $player_e['playerid'],
                    'linkup' => $player_e['playerid'],
                    'linkright' => $o__id,
                    'linktype' => $new_playerid,
                )));
            }

        } elseif ($element_id == 4737) {

            //Player Reference
            $status = $this->Cacheideas->update($o__id, array(
                'ideatype' => $new_playerid,
            ), true, $player_e['playerid']);

            //See if we need to popup the idea edit modal here:

            $players___42179 = $this->config->item('players___42179'); //Dynamic Input Fields
            foreach (array_intersect($this->config->item('playerids___' . $new_playerid), $this->config->item('playerids___42179')) as $dynamic_playerid) {

                $superpowers_required = array_intersect($this->config->item('playerids___10957'), $players___42179[$dynamic_playerid]['m__following']);
                if (count($superpowers_required) && !superpower_unlocked(end($superpowers_required))) {
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
                    $already_responded = count($this->Menchledger->fetch(array(
                        'linkup IN (' . join(',', $this->config->item('playerids___' . $dynamic_playerid)) . ')' => null, //All possible answers
                        'linkright' => $o__id,
                        'linktype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                    )));

                } else {

                    $already_responded = count($this->Menchledger->fetch(array(
                        'linkup' => $dynamic_playerid,
                        'linkright' => $o__id,
                        'linktype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                    )));

                }

                if (!$already_responded) {
                    //We are missing a required response, auto open modal:
                    $auto_open_idea_editor_modal = 1;
                }

            }

        }

        return array(
            'status' => intval($status) && ($links_removed < 0 || $links_removed > 0),
            'message' => 'Delete status [' . $status . '] with ' . $links_removed . ' Links removed',
            'deletion_redirect' => $deletion_redirect,
            'delete_element' => $delete_element,
            'auto_open_idea_editor_modal' => $auto_open_idea_editor_modal,
        );

    }

    function send_dm($playerid, $subject, $html_message, $x_data = array(), $template_ideaid = 0, $linkdomain = 0, $log_tr = true, $demo_only = false)
    {

        $sms_subscriber = false;

        //Bypass notifications?
        if (!count($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___42256')) . ')' => null, //Writes
            'linkup' => 31779, //Mandatory Emails
            'linkright' => $template_ideaid,
        )))) {

            $notification_levels = $this->Menchledger->fetch(array(
                'linkup IN (' . join(',', $this->config->item('playerids___30820')) . ')' => null, //Active Subscriber
                'linkdown' => $playerid,
                'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
            ));
            if (!count($notification_levels)) {
                return array(
                    'status' => 0,
                    'message' => 'User is not an active subscriber',
                );
            }
            $sms_subscriber = in_array($notification_levels[0]['linkup'], $this->config->item('playerids___28915'));
        }

        //Make sure not recently contacted:
        /*
         * Did not work with subscription notifications which could happen back to back...
         *
        $minutes_limit = 60;
        foreach($this->Menchledger->fetch(array(
            'linktype' => 29399,
            'linkplayer' => $playerid,
            'linktime >=' => date("Y-m-d H:i:s", strtotime('-'.$minutes_limit.' minutes')),
        )) as $recent_email){

            //Log Report:
            $this->Menchledger->create(array(
                'linktype' => 44179, //Triggered
                'linkup' => 4246, //Platform Bug Reports
                'linkdown' => 29399,
                'linkplayer' => $playerid,
                'linktext' => 'User was recently contacted less than '.$minutes_limit.' minutes ago.',
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
        foreach ($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
            'linkup' => 3288, //Email
            'linkdown' => $playerid,
        )) as $player_data) {

            if (!filter_var($player_data['linktext'], FILTER_VALIDATE_EMAIL)) {
                $this->Menchledger->update($player_data['linkid'], array(), $playerid);
                continue;
            }

            array_push($stats['email_addresses'], $player_data['linktext']);

        }

        if (count($stats['email_addresses']) > 0) {
            //Send email:
            dispatch_email($stats['email_addresses'], $subject, $html_message, $playerid, $x_data, $template_ideaid, $linkdomain, $log_tr, $demo_only);
        }


        //Should we send SMS?
        $twilio_account_sid = website_setting(30859);
        $twilio_auth_token = website_setting(30860);
        $twilio_from_number = website_setting(27673);
        if ($sms_subscriber && $twilio_account_sid && $twilio_auth_token && $twilio_from_number) {

            //Yes, generate message
            $sms_message = get_domain('m__title', $playerid, $linkdomain) . ' Emailed [' . $subject . '] to ' . join(' & ', $stats['email_addresses']) . ' (Also Check Spam)';

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n", " ", $sms_message);

            //Send SMS
            foreach ($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
                'linkup' => 4783, //Phone
                'linkdown' => $playerid,
            )) as $player_data) {

                foreach (explode('|||', wordwrap($sms_message, view__memory(6404, 27891), "|||")) as $single_message) {

                    $sms_sent = dispatch_sms($player_data['linktext'], $single_message, $playerid, $x_data, $template_ideaid, $linkdomain, $log_tr, $demo_only);

                    if (!$sms_sent) {
                        //bad number, remove it:
                        $this->Menchledger->update($player_data['linkid'], array(), $playerid);
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


    function send_idea_mass_dm($list_of_playerid, $i, $linkdomain = 0, $ensure_undiscovered = true, $demo_only = false)
    {

        $total_sent = 0;
        $linkdomain = ($linkdomain > 0 ? $linkdomain : (isset($i['linkdomain']) ? $i['linkdomain'] : 0));
        $subject_line = view__idea_title($i, true);
        $wacth_repeat_handles = array();

        foreach ($list_of_playerid as $count => $x) {

            if (in_array($x['playerhandle'], $wacth_repeat_handles)) {
                //This should not happen! Report bug:
                $this->Menchledger->create(array(
                    'linktype' => 44179, //Triggered
                    'linkup' => 4246, //Platform Bug Reports
                    'linkdown' => $x['playerid'],
                    'linktext' => 'send_idea_mass_dm() Detected duplicate Player Handle Bug: ' . $x['playerhandle'],
                ));
                break; //Stop sending more messages!
            }

            //Map this handle:
            array_push($wacth_repeat_handles, $x['playerhandle']);


            if (!isset($x['playerid'])) {
                //Invalid input for sending:
                $this->Menchledger->create(array(
                    'linktype' => 44179, //Triggered
                    'linkup' => 4246, //Platform Bug Reports
                    'linkdown' => 26582, //Messener
                    'linktext' => 'send_idea_mass_dm() Invalid user row',
                ));
                continue;
            } elseif ($ensure_undiscovered && count($this->Menchledger->fetch(array(
                    'linkleft' => $i['ideaid'],
                    'linkplayer' => $x['playerid'],
                    'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                )))) {
                //Already discovered:
                continue;
            }


            $content_message = view__idea_links($i, $x['playerid'], true); //Hide the show more content if any
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', $content_message);
            }

            //Append children as options:
            $html_message = '';
            foreach ($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Sequence Down
                'linkleft' => $i['ideaid'],
            ), array('linkright'), 0, 0, array('linknumber' => 'ASC')) as $down_or) {
                //Has this user discovered this idea or no?
                $html_message .= '<div class="line">' . view__idea_title($down_or, true) . ':</div>';
                $html_message .= '<div class="line">' . 'https://' . get_domain('m__message', $x['playerid'], $linkdomain) . view__memory(42903, 33286) . $down_or['ideahashtag'] . (i_startable($down_or) ? '/' . view__memory(6404, 4235) : '') . '?playerhandle=' . $x['playerhandle'] . '&time=' . time() . '&hash=' . view__hash(time() . $x['playerhandle']) . '</div>';
            }

            //Where to place the next step?
            if (substr_count($content_message, 'link_here') == 1) {
                //We have direction to place the next step somewhere specific:
                $content_message = str_replace('link_here', $html_message, $content_message);
            } else {
                $content_message = $content_message . $html_message;
            }

            $send_dm = $this->Menchledger->send_dm($x['playerid'], $subject_line, $content_message, array(
                'linkleft' => $i['ideaid'],
            ), $i['ideaid'], $linkdomain, true, $demo_only);

            //Mark as discovered:
            if ($send_dm['status'] && !$demo_only) {
                $this->Menchledger->mark_complete(43142, $x['playerid'], 0, $i);
                $total_sent++;
            }

        }

        return $total_sent;
    }


    function find_previous($playerid, $target_ideahashtag, $focus_ideaid, $loop_breaker_ids = array())
    {

        //echo 'Previous:'.$playerid.'/'.$target_ideahashtag.'/'.$focus_ideaid;

        if (count($loop_breaker_ids) > 0 && in_array($focus_ideaid, $loop_breaker_ids)) {
            return array();
        }
        array_push($loop_breaker_ids, intval($focus_ideaid));

        //Fetch followings:
        foreach ($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //Active Sequence Up
            'linkright' => $focus_ideaid,
        ), array('linkleft')) as $idea_previous) {

            //Validate Selection:
            $input__selection = in_array($idea_previous['ideatype'], $this->config->item('playerids___7712'));
            $is_selected = count($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'linkleft' => $idea_previous['ideaid'],
                'linkright' => $focus_ideaid,
                'linkplayer' => $playerid,
            )));

            if ($playerid > 0 && !$is_selected && $input__selection) {
                continue;
            }

            //Did we find it?
            if ($idea_previous['ideahashtag'] == $target_ideahashtag) {
                return array($idea_previous);
            }

            //Keep looking further up:
            $website_finder = $this->Menchledger->find_previous($playerid, $target_ideahashtag, $idea_previous['ideaid'], $loop_breaker_ids);
            if (count($website_finder)) {
                array_push($website_finder, $idea_previous);
                return $website_finder;
            }
        }

        //Did not find any followings:
        return array();

    }


    function find_previous_discovered($focus_ideaid, $linkplayer, $loop_breaker_ids = array())
    {

        /*
         *
         * Returns hashtag if discovered upwards
         *
         * */

        if (count($loop_breaker_ids) > 0 && in_array($focus_ideaid, $loop_breaker_ids)) {
            return false;
        }
        array_push($loop_breaker_ids, intval($focus_ideaid));

        foreach ($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //Active Sequence Up
            'linkright' => $focus_ideaid,
        ), array('linkleft')) as $prev_i) {

            foreach ($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'linkplayer' => $linkplayer,
                'linkleft' => $prev_i['ideaid'],
            ), array('linkright')) as $x) {
                return $x['ideahashtag'];
            }

            return $this->Menchledger->find_previous_discovered($prev_i['ideaid'], $linkplayer, $loop_breaker_ids);
        }

        //Did not find!
        return false;

    }


    function find_next($playerid, $target_ideahashtag, $i, $find_after_ideaid = 0, $search_up = true, $target_completed = false, $loop_breaker_ids = array())
    {

        if (count($loop_breaker_ids) > 0 && in_array($i['ideaid'], $loop_breaker_ids)) {
            return null;
        }
        array_push($loop_breaker_ids, intval($i['ideaid']));

        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $found_trigger = null;

        foreach ($this->Menchledger->fetch(array(
            'linkleft' => $i['ideaid'],
            'linktype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
        ), array('linkright'), 0, 0, array('linknumber' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_ideaid && !$found_trigger) {
                if ($next_i['ideaid'] == $find_after_ideaid) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            $is_selected = count($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'linkleft' => $i['ideaid'],
                'linkright' => $next_i['ideaid'],
                'linkplayer' => $playerid,
            )));
            if ($input__selection && !$is_selected) {
                continue;
            }


            //Return this if everything is completed, or if this is incomplete:
            if ($target_completed || !count($this->Menchledger->fetch(array(
                    'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    'linkplayer' => $playerid,
                    'linkleft' => $next_i['ideaid'],
                )))) {
                return $next_i['ideahashtag'];
            }

            //Keep looking deeper:
            $next__url = $this->Menchledger->find_next($playerid, $target_ideahashtag, $next_i, 0, false, $target_completed, $loop_breaker_ids);
            if ($next__url) {
                return $next__url;
            }

        }


        if ($search_up && $target_ideahashtag != $i['ideahashtag']) {
            //Check Previous/Up
            $current_previous = $i['ideaid'];
            foreach (array_reverse($this->Menchledger->find_previous($playerid, $target_ideahashtag, $i['ideaid'])) as $p_i) {
                //Find the next siblings:
                $next__url = $this->Menchledger->find_next($playerid, $target_ideahashtag, $p_i, $current_previous, false, $target_completed);
                if ($next__url) {
                    return $next__url;
                }
                $current_previous = $p_i['ideaid'];
            }
        }

        //Nothing found:
        return null;

    }


    function mark_complete($linktype, $linkplayer, $target_ideaid = 0, $i, $focus_idea_data = array(), $x_data = array())
    {

        if (!$linkplayer || !in_array($linktype, $this->config->item('playerids___31777' /* DISCOVERIES */))) {
            $this->Menchledger->create(array(
                'linktype' => 44179, //Triggered
                'linkup' => 4246, //Platform Bug Reports8
                'linkdown' => $linkplayer,
                'linkplayer' => $linkplayer,
                'linktext' => 'mark_complete() Invalid linktype @' . $linktype . ' missing in @31777 OR Missing $linkplayer',
            ));
            return array(
                'status' => 0,
                'message' => 'Invalid Date',
            );
        }

        //Do we need to save text/upload ?
        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $input__upload = in_array($i['ideatype'], $this->config->item('playerids___43004'));
        $input__text = in_array($i['ideatype'], $this->config->item('playerids___43002')) || in_array($i['ideatype'], $this->config->item('playerids___43003'));
        $is_required = count($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkright' => $i['ideaid'],
            'linkup' => 28239, //Required
        )));


        if ($input__upload || $input__text) {

            if (!isset($focus_idea_data['new_ideatext'])) {
                $focus_idea_data['new_ideatext'] = null;
            }
            if (!isset($focus_idea_data['uploaded_media'])) {
                $focus_idea_data['uploaded_media'] = array();
            }


            //Must add a new idea, but first let's validate the input:
            if ($i['ideatype'] == 31794 && strlen($focus_idea_data['new_ideatext']) && !is_numeric($focus_idea_data['new_ideatext'])) {
                //Number Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Number',
                );
            } elseif ($i['ideatype'] == 42915 && strlen($focus_idea_data['new_ideatext']) && !filter_var($focus_idea_data['new_ideatext'], FILTER_VALIDATE_URL)) {
                //Link Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid URL',
                );
            } elseif ($i['ideatype'] == 30350 && strlen($focus_idea_data['new_ideatext']) && !strtotime($focus_idea_data['new_ideatext'])) {
                //Date Input
                return array(
                    'status' => 0,
                    'message' => 'Invalid Date',
                );
            }

            //Find most recent answers by this user:
            $x_responses = $this->Menchledger->fetch(array(
                'linktype' => 33532, //Private Reply
                'linkleft' => $i['ideaid'],
                'linkplayer' => $linkplayer,
            ), array('linkright'), 0, 1, array('linkid' => 'DESC'));


            //All validated, lets create the new idea:
            if (strlen($focus_idea_data['new_ideatext']) || count($focus_idea_data['uploaded_media'])) {

                if (count($x_responses)) {

                    //Update existing response if different:
                    if ($focus_idea_data['new_ideatext'] != $x_responses[0]['ideatext']) {
                        $view_sync_links = view__sync_links($focus_idea_data['new_ideatext'], true, $x_responses[0]['ideaid']);
                    }
                    $this_ideaid = $x_responses[0]['ideaid'];

                } else {

                    //Create a new response:
                    $idea_new = $this->Cacheideas->create(array(
                        'ideatext' => $focus_idea_data['new_ideatext'],
                    ), $linkplayer);

                    $this_ideaid = $idea_new['ideaid'];

                    //Link to this idea:
                    $this->Menchledger->create(array(
                        'linktype' => 33532, //Private Reply
                        'linkplayer' => $linkplayer,
                        'linkleft' => $i['ideaid'],
                        'linkright' => $idea_new['ideaid'],
                    ));

                }

                //Process Media:
                $media_stats = process_media($this_ideaid, $focus_idea_data['uploaded_media']);

            } elseif (count($x_responses)) {

                if ($is_required) {
                    return array(
                        'status' => 0,
                        'message' => 'Resposne is required',
                    );
                } else {
                    //Delete Links
                    $links_removed = $this->Cacheideas->remove($x_responses[0]['ideaid'], $linkplayer);
                }

            }

        }

        $x_data['linkplayer'] = $linkplayer;
        $x_data['linktype'] = $linktype;
        $x_data['linkleft'] = $i['ideaid']; //Always add Idea to linkleft

        //Add link right only if we have a target idea we are navigating to
        if ($target_ideaid > 0 && (!isset($x_data['linkright']) || !intval($x_data['linkright']))) {
            $x_data['linkright'] = $target_ideaid;
        }

        if (!isset($x_data['linktext'])) {
            $x_data['linktext'] = null;
        }

        $es_creator = $this->Cacheplayers->fetch(array(
            'playerid' => $linkplayer,
        ));

        //Make sure not duplicate:
        foreach ($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkleft' => (isset($x_data['linkleft']) ? $x_data['linkleft'] : 0),
            'linkright' => (isset($x_data['linkright']) ? $x_data['linkright'] : 0),
            'linkplayer' => $linkplayer,
            'linktext' => $x_data['linktext'],
        )) as $already_discovered) {
            //Already discovered!
            return array(
                'status' => 1,
                'message' => 'Already Discovered',
                'new_x' => $already_discovered,
            );
        }

        //Add new transaction:
        $domain_url = get_domain('m__message', $linkplayer);
        $new_x = $this->Menchledger->create($x_data);

        //Auto Complete OR Answers:
        if ($input__selection) {
            foreach ($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'linkplayer' => $x_data['linkplayer'],
                'linkleft' => $i['ideaid'],
            ), array('linkright'), 0) as $next_i) {
                if (!in_array($next_i['ideatype'], $this->config->item('playerids___43039')) && !count($this->Menchledger->fetch(array(
                        'linktype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //IDEA LINKS
                        'linkleft' => $next_i['ideaid'],
                    ), array('linkright'), 0, 0))) {
                    //Mark as complete:
                    $this->Menchledger->mark_complete(idea_discovery_link($next_i), $x_data['linkplayer'], $target_ideaid, $next_i, $x_data);
                }
            }
        }

        if ($x_data['linkplayer'] && in_array($x_data['linktype'], $this->config->item('playerids___40986'))) {

            //Discovery Triggers?
            $clone_urls = '';
            foreach ($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___32275')) . ')' => null, //DISCOVERY TRIGGERS
                'linkleft' => $i['ideaid'],
            ), array('linkright'), 0, 0, array('linknumber' => 'ASC')) as $clone_i) {

                if ($clone_i['linktype'] == 32247) {

                    //Discovery Clone
                    $new_title = $es_creator[0]['playertext'] . ' ' . $clone_i['ideatext'];
                    $result = $this->Cacheideas->recursive_clone($clone_i['ideaid'], 0, $x_data['linkplayer'], null, $new_title);
                    if ($result['status']) {

                        //Add as watcher:
                        $this->Menchledger->create(array(
                            'linktype' => 10573, //WATCHERS
                            'linkplayer' => $x_data['linkplayer'],
                            'linkup' => $x_data['linkplayer'],
                            'linkright' => $result['new_ideaid'],
                        ));

                        //New link:
                        $clone_urls .= $new_title . ':' . "\n" . 'https://' . get_domain('m__message', $x_data['linkplayer']) . view__memory(42903, 33286) . $result['new_ideahashtag'] . "\n\n";
                    }

                } elseif ($clone_i['linktype'] == 32304) {

                    //Discovery Forget: Remove all Discoveries made by this user:
                    foreach ($this->Menchledger->fetch(array(
                        'linktype IN (' . join(',', $this->config->item('playerids___31777')) . ')' => null, //DISCOVERIES
                        'linkleft' => $i['ideaid'],
                        'linkplayer' => $x_data['linkplayer'],
                    )) as $remove_x) {
                        $this->Menchledger->update($remove_x['linkid'], array(), $x_data['linkplayer']);
                    }

                }

            }

            if (strlen($clone_urls)) {
                //Send DM with all the new clone idea URLs:
                $clone_urls = $clone_urls . 'You have been added as a subscriber so you will be notified when anyone start using your link.';
                $idea_title = view__idea_title($i, true);
                $this->Menchledger->send_dm($x_data['linkplayer'], $idea_title, $clone_urls);
                //Also DM all watchers of the idea:
                foreach ($this->Menchledger->fetch(array(
                    'linktype' => 10573, //WATCHERS
                    'linkright' => $i['ideaid'],
                ), array(), 0) as $watcher) {
                    $this->Menchledger->send_dm($watcher['linkup'], $idea_title, $clone_urls);
                }
            }


            //ADD PROFILE?
            foreach ($this->Menchledger->fetch(array(
                'linktype' => 7545, //Following Add
                'linkright' => $i['ideaid'],
            ), array('linkup')) as $this_tag) {

                //Check if special profile add?
                if (in_array($this_tag['linkup'], $this->config->item('playerids___43048'))) {

                    //Special Addition:

                    if ($this_tag['linkup'] == 6197 && strlen(trim($x_data['linktext'])) >= 2) {

                        //Update Player Title:
                        $this->Cacheplayers->update($x_data['linkplayer'], array(
                            'playertext' => $x_data['linktext'],
                        ), true, $x_data['linkplayer']);

                        //Update live session as well:
                        $es_creator[0]['playertext'] = $x_data['linktext'];
                        $this->Cacheplayers->activate_session($es_creator[0], true);

                    } elseif ($this_tag['linkup'] == 6198 && isset($media_stats['media_playercover']) && filter_var($media_stats['media_playercover'], FILTER_VALIDATE_URL)) {

                        //Update Player Cover:
                        //Update profile picture for current user:
                        $this->Cacheplayers->update($linkplayer, array(
                            'playercover' => $media_stats['media_playercover'],
                        ), true, $linkplayer);

                        //Update live session as well:
                        $es_creator[0]['playercover'] = $media_stats['media_playercover'];
                        $this->Cacheplayers->activate_session($es_creator[0], true);

                    }

                } else {

                    //Assign tag if following/follower transaction NOT previously assigned:
                    $append_player = append_player($this_tag['linkup'], $x_data['linkplayer'], (isset($focus_idea_data['new_ideatext']) ? $focus_idea_data['new_ideatext'] : null), $i['ideaid']);

                    //See if Session needs to be updated:
                    $player_e = superpower_unlocked();
                    if ($player_e && $player_e['playerid'] == $x_data['linkplayer'] && $append_player) {
                        $this->Cacheplayers->activate_session($es_creator[0], true);
                    }

                }
            }


            //REMOVE PROFILE?
            foreach ($this->Menchledger->fetch(array(
                'linktype' => 26599, //Following Remove
                'linkright' => $i['ideaid'],
            )) as $this_tag) {

                //Remove Following IF previously assigned:
                foreach ($this->Menchledger->fetch(array(
                    'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
                    'linkup' => $this_tag['linkup'], //CERTIFICATES saved here
                    'linkdown' => $x_data['linkplayer'],
                )) as $existing_x) {

                    $this->Menchledger->update($existing_x['linkid'], array(), $x_data['linkplayer']);

                    //See if Session needs to be updated:
                    if ($player_e && $player_e['playerid'] == $x_data['linkplayer']) {
                        //Yes, update session:
                        $this->Cacheplayers->activate_session($es_creator[0], true);
                    }
                }
            }


            //Notify watchers IF any:
            $watchers = $this->Menchledger->fetch(array(
                'linktype' => 10573, //WATCHERS
                'linkright' => $i['ideaid'],
            ), array(), 0);
            if (count($watchers)) {

                $es_discoverer = $this->Cacheplayers->fetch(array(
                    'playerid' => $x_data['linkplayer'],
                ));
                if (count($es_discoverer)) {

                    //Fetch Discoverer contact:
                    $discoverer_contact = '';
                    foreach ($this->config->item('players___34541') as $linktype => $m) {
                        foreach ($this->Menchledger->fetch(array(
                            'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
                            'linkdown' => $x_data['linkplayer'],
                            'linkup' => $linktype,
                            'LENGTH(linktext)>0' => null,
                        )) as $x_progress) {
                            $discoverer_contact .= $m['m__title'] . ':' . "\n" . $x_progress['linktext'] . "\n\n";
                        }
                    }

                    //Notify Idea Watchers
                    $sent_watchers = array();
                    foreach ($watchers as $watcher) {
                        if (!in_array(intval($watcher['linkup']), $sent_watchers)) {
                            array_push($sent_watchers, intval($watcher['linkup']));

                            $this->Menchledger->send_dm($watcher['linkup'], $es_discoverer[0]['playertext'] . ' Discovered: ' . view__idea_title($i, true),
                                //Message Body:
                                view__idea_title($i, true) . ':' . "\n" . 'https://' . $domain_url . view__memory(42903, 33286) . $i['ideahashtag'] . "\n\n" .
                                (strlen($x_data['linktext']) ? $x_data['linktext'] . "\n\n" : '') .
                                $es_discoverer[0]['playertext'] . ':' . "\n" . 'https://' . $domain_url . view__memory(42903, 42902) . $es_discoverer[0]['playerhandle'] . "\n\n" .
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


    function tree_full_history($i, $playerid, $idea_level = 0)
    {

        unset($i['ideaexternal']);
        unset($i['ideacache']);
        unset($i['linktype']);
        unset($i['linkup']);
        unset($i['linkdown']);
        unset($i['linknumber']);
        unset($i['linkdomain']);
        unset($i['linkvoid']);
        unset($i['linkplayer']);
        unset($i['linkleft']);
        unset($i['linkright']);
        unset($i['linkid']);
        unset($i['linktext']);

        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $input__text = in_array($i['ideatype'], $this->config->item('playerids___43002'));
        $i['uploaded_media'] = array();
        $i['user_discovered'] = array();
        $i['user_written_response'] = array();
        $i['idea_level'] = $idea_level;
        $i['idea_next'] = array();
        $idea_level++;

        //Append media if any:
        foreach ($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___42294')) . ')' => null, //Media
            'linkright' => $i['ideaid'],
        ), array('linkup'), 0, 0, array('linknumber' => 'ASC')) as $media) {

            //Get metadata:
            foreach ($this->Menchledger->fetch(array(
                'linkup IN (' . join(',', $this->config->item('playerids___44393')) . ')' => null, //Media JSON
                'linkdown' => $media['playerid'],
                'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
            ), array('linkup'), 0) as $player_group) {
                if (strlen($player_group['linktext'])) {
                    $media[$player_group['playerhandle']] = $player_group['linktext'];
                }
            }

            unset($media['linktime']);
            unset($media['linkup']);
            unset($media['linkdown']);
            unset($media['linknumber']);
            unset($media['linkdomain']);
            unset($media['linkvoid']);
            unset($media['linkplayer']);
            unset($media['linkleft']);
            unset($media['linkright']);
            unset($media['linkid']);
            unset($media['linktext']);
            unset($media['playerid']);
            unset($media['playertext']);
            unset($media['playerhandle']);
            unset($media['playerexternal']);
            array_push($i['uploaded_media'], $media);
        }

        //Append Discovery if any:
        foreach ($this->Menchledger->fetch(array(
            'linkleft' => $i['ideaid'],
            'linkplayer' => $playerid,
            'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 1) as $x) {

            unset($x['linktype']);
            unset($x['linkup']);
            unset($x['linkdown']);
            unset($x['linknumber']);
            unset($x['linkdomain']);
            unset($x['linkvoid']);
            unset($x['linkleft']);
            unset($x['linkright']);
            unset($x['linkplayer']);
            unset($x['linktext']);
            unset($x['linkid']);

            $i['user_discovered'] = $x;

            if ($input__text) {
                //Since it has been discovered and its a text input, lots fetch the written response:
                foreach ($this->Menchledger->fetch(array(
                    'linktype' => 33532, //Private Reply
                    'linkleft' => $i['ideaid'],
                    'linkplayer' => $playerid,
                ), array('linkright'), 0, 1, array('linkid' => 'DESC')) as $response) {
                    $i['user_written_response'] = $response;
                }
            }
        }


        if ($i['user_discovered']) {
            foreach ($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
                'linkleft' => $i['ideaid'],
            ), array('linkright'), 0, 0, array('linknumber' => 'ASC')) as $next_i) {
                array_push($i['idea_next'], $this->Menchledger->tree_full_history($next_i, $playerid, $idea_level));
            }
        }


        return $i;

    }

    function tree_discovered_history($i, $playerid, $idea_level = 0)
    {

        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $input__text = in_array($i['ideatype'], $this->config->item('playerids___43002'));
        $i['idea_level'] = $idea_level;
        $i['idea_next'] = array();
        $i['user_discovered'] = array();
        $i['user_written_response'] = array();
        $idea_level++;

        //Append Discovery if any:
        foreach ($this->Menchledger->fetch(array(
            'linkleft' => $i['ideaid'],
            'linkplayer' => $playerid,
            'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 1) as $x) {
            $i['user_discovered'] = $x;
        }

        if ($input__text) {
            foreach ($this->Menchledger->fetch(array(
                'linktype' => 33532, //Private Reply
                'linkleft' => $i['ideaid'],
                'linkplayer' => $playerid,
            ), array('linkright'), 0, 1, array('linkid' => 'DESC')) as $response) {
                $i['user_written_response'] = $response;
            }
        }


        if ($i['user_discovered']) {
            foreach (($input__selection ? $this->Menchledger->fetch(array(
                'linktype' => 7712, //Input Choice
                'linkplayer' => $playerid,
                'linkleft' => $i['ideaid'],
            ), array('linkright')) : $this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
                'linkleft' => $i['ideaid'],
            ), array('linkright'), 0, 0, array('linknumber' => 'ASC'))) as $next_i) {
                array_push($i['idea_next'], $this->Menchledger->tree_discovered_history($next_i, $playerid, $idea_level));
            }
        }


        return $i;

    }

    function tree_doc($i, $idea_level = 0)
    {

        $i['idea_level'] = $idea_level;
        $idea_level++;
        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        $single_choice = in_array($i['ideatype'], $this->config->item('playerids___33331'));
        $is_required = count($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkright' => $i['ideaid'],
            'linkup' => 28239, //Required
        )));
        $total_next = $this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'linkleft' => $i['ideaid'],
        ), array('linkright'), 0, 0, array('linknumber' => 'ASC'));

        $i['stats'] = array(
            'max_level' => $idea_level,
            'max_steps' => ($input__selection ? ($single_choice ? 1 : count($total_next)) : count($total_next)),
            'min_steps' => ($input__selection ? ($is_required ? 1 : 0) : count($total_next)), //Can be improved later...
            'or_steps' => ($input__selection && count($total_next) ? 1 : 0),
        );
        $i['idea_next'] = array();

        //Append Total Discoveries if any:
        $sub_counter = $this->Menchledger->fetch(array(
            'linkleft' => $i['ideaid'],
            'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 0, 0, array(), 'COUNT(linkid) as totals');
        $i['idea_count_discovery'] = $sub_counter[0]['totals'];


        foreach ($total_next as $next_i) {

            $result_i = $this->Menchledger->tree_doc($next_i, $idea_level);
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


    function tree_progress($playerid, $i, $idea_level = 0, $loop_breaker_ids = array())
    {

        if (count($loop_breaker_ids) > 0 && in_array($i['ideaid'], $loop_breaker_ids)) {
            return false;
        }

        $recursive_down_ids = $this->Cacheideas->recursive_down_ids($i, 'AND');
        if (!isset($recursive_down_ids['recursive_idea_ids']) || !count($recursive_down_ids['recursive_idea_ids'])) {
            return false;
        }

        $idea_level++;
        array_push($loop_breaker_ids, intval($i['ideaid']));

        //Count completed:
        $list_discovered = array();
        foreach ($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkplayer' => $playerid, //Belongs to this Member
            'linkleft IN (' . join(',', $recursive_down_ids['recursive_idea_ids']) . ')' => null,
        ), array('linkleft'), 0) as $completed) {
            if (!in_array($completed['ideahashtag'], $list_discovered)) {
                array_push($list_discovered, $completed['ideahashtag']);
            }
        }


        //Calculate common steps and expansion steps recursively for this u:
        $metadata_this = array(
            'fixed_total' => count($recursive_down_ids['recursive_idea_ids']),
            'list_total' => $recursive_down_ids['recursive_idea_ids'],
            'fixed_discovered' => count($list_discovered),
            'list_discovered' => $list_discovered,
        );

        //Now let's check possible expansions:
        if (count($recursive_down_ids['recursive_idea_ids'])) {
            foreach ($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansion
                'linkplayer' => $playerid, //Belongs to this Member
                'linkleft IN (' . join(',', $recursive_down_ids['recursive_idea_ids']) . ')' => null,
            ), array('linkright')) as $expansion_in) {

                //Fetch recursive:
                $tree_progress = $this->Menchledger->tree_progress($playerid, $expansion_in, $idea_level, $loop_breaker_ids);

                if (!$tree_progress && !count($this->Menchledger->fetch(array(
                        'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                        'linkplayer' => $playerid, //Belongs to this Member
                        'linkleft' => $expansion_in['ideaid'],
                    )))) {
                    $tree_progress = array(
                        'fixed_total' => 1,
                        'list_total' => array($expansion_in['ideaid']),
                        'fixed_discovered' => 0,
                        'list_discovered' => array(),
                    );
                }

                //Addup completion stats for this:
                $metadata_this['fixed_total'] += $tree_progress['fixed_total'];
                $metadata_this['fixed_discovered'] += $tree_progress['fixed_discovered'];

                if ($tree_progress['list_total'] && count($tree_progress['list_total'])) {
                    foreach ($tree_progress['list_total'] as $tree_id) {
                        if (!in_array($tree_id, $metadata_this['list_total'])) {
                            array_push($metadata_this['list_total'], $tree_id);
                        }
                    }
                }

                if ($tree_progress['list_discovered'] && count($tree_progress['list_discovered'])) {
                    foreach ($tree_progress['list_discovered'] as $tree_id) {
                        if (!in_array($tree_id, $metadata_this['list_discovered'])) {
                            array_push($metadata_this['list_discovered'], $tree_id);
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
                $metadata_this['fixed_completed_percentage'] = intval(floor($metadata_this['fixed_discovered'] / $metadata_this['fixed_total'] * 100));
            }


        }

        //Return results:
        return $metadata_this;

    }


    function i_has_started($playerid, $ideahashtag)
    {
        return count($this->Menchledger->fetch(array(
            'linkleft = linkright' => NULL,
            'LOWER(ideahashtag)' => strtolower($ideahashtag),
            'linkplayer' => $playerid,
            'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array('linkright')));
    }


}