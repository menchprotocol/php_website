<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comm_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function fb_graph($action, $url, $payload = array())
    {

        //Do some initial checks
        if (!in_array($action, array('GET', 'POST', 'DELETE'))) {

            //Only 4 valid types of $action
            return array(
                'status' => 0,
                'message' => '$action [' . $action . '] is invalid',
            );

        }


        //Start building GET URL:
        if (array_key_exists('access_token', $payload)) {

            //This this access token:
            $access_token_payload = array(
                'access_token' => $payload['access_token'],
            );
            //Remove it just in case:
            unset($payload['access_token']);

        } else {
            //Apply the Page Access Token:
            $fb_settings = $this->config->item('fb_settings');
            $access_token_payload = array(
                'access_token' => $fb_settings['mench_access_token']
            );
        }

        if ($action == 'GET' && count($payload) > 0) {
            //Add $payload to GET variables:
            $access_token_payload = array_merge($access_token_payload, $payload);
            $payload = array();
        }

        $url = 'https://graph.facebook.com/v2.6' . $url;
        $counter = 0;
        foreach ($access_token_payload as $key => $val) {
            $url = $url . ($counter == 0 ? '?' : '&') . $key . '=' . $val;
            $counter++;
        }

        //Make the graph call:
        $ch = curl_init($url);

        //Base setting:
        $ch_setting = array(
            CURLOPT_CUSTOMREQUEST => $action,
            CURLOPT_RETURNTRANSFER => TRUE,
        );

        if (count($payload) > 0) {
            $ch_setting[CURLOPT_HTTPHEADER] = array('Content-Type: application/json; charset=utf-8');
            $ch_setting[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        //Apply settings:
        curl_setopt_array($ch, $ch_setting);

        //Process results and produce tr_metadata
        $result = objectToArray(json_decode(curl_exec($ch)));
        $tr_metadata = array(
            'action' => $action,
            'payload' => $payload,
            'url' => $url,
            'result' => $result,
        );

        //Did we have any issues?
        if (!$result) {

            //Failed to fetch this profile:
            $error_message = 'Comm_model->fb_graph() failed to ' . $action . ' ' . $url;
            $this->Db_model->tr_create(array(
                'tr_content' => $error_message,
                'tr_en_type_id' => 4246, //Platform Error
                'tr_metadata' => $tr_metadata,
            ));

            //There was an issue accessing this on FB
            return array(
                'status' => 0,
                'message' => $error_message,
                'tr_metadata' => $tr_metadata,
            );

        } else {

            //All seems good, return:
            return array(
                'status' => 1,
                'message' => 'Success',
                'tr_metadata' => $tr_metadata,
            );

        }
    }


    function fb_ref_process($u, $fb_ref)
    {

        if (!$fb_ref || strlen($fb_ref) < 1) {

            return false;

        } elseif (substr_count($fb_ref, 'APSKIP_') == 1) {

            $unsub_value = one_two_explode('APSKIP_', '', $fb_ref);

            if ($unsub_value == 'CANCEL') {

                //User changed their mind, confirm:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Awesome, I am excited to continue helping you to ' . $this->config->item('primary_in_name') . '. ' . echo_pa_lets(),
                    ),
                ));

            } elseif ($unsub_value == 'ALL') {

                //User wants completely out...

                //Skip everything from their Action Plan
                $this->db->query("UPDATE tb_actionplans SET tr_status=-1 WHERE tr_status>=0 AND tr_en_parent_id=" . $u['en_id']);
                $intents_skipped = $this->db->affected_rows();

                //Update User communication level to Unsubscribe:
                $this->Db_model->en_radio_set(4454, 4455, $u['en_id'], $u['en_id']);

                //Let them know:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Confirmed, I skipped all ' . $intents_skipped . ' intent' . echo__s($intents_skipped) . ' in your Action Plan. This is the final message you will receive from me unless you message me. Take care of your self and I hope to talk to you soon 😘',
                    ),
                ));

            } elseif (intval($unsub_value) > 0) {

                //User wants to skip a specific intent from their Action Plan, validate it:
                $trs = $this->Db_model->w_fetch(array(
                    'tr_id' => intval($unsub_value),
                    'tr_status >=' => 0,
                ), array('in'));

                //All good?
                if (count($trs) == 1) {

                    //Update status for this single subscription:
                    $this->db->query("UPDATE tb_actionplans SET tr_status=-1 WHERE tr_id=" . intval($unsub_value));

                    //Show success message to user:
                    $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'I have successfully skipped the intention to ' . $trs[0]['in_outcome'] . '. Say "Unsubscribe" if you wish to stop all future communications. ' . echo_pa_lets(),
                        ),
                    ));

                } else {

                    //let them know we had error:
                    $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'Unable to process your request as I could not locate your subscription. Please try again.',
                        ),
                    ));

                    //Log error engagement:
                    $this->Db_model->tr_create(array(
                        'tr_en_credit_id' => $u['en_id'],
                        'tr_content' => 'Failed to skip an intent from the master Action Plan',
                        'tr_en_type_id' => 4246, //System error
                        'tr_tr_parent_id' => intval($unsub_value),
                    ));

                }
            }

        } elseif (substr_count($fb_ref, 'ACTIVATE_') == 1) {

            if ($fb_ref == 'ACTIVATE_YES') {

                //Update User communication level to Receive Silent Push Notifications:
                $this->Db_model->en_radio_set(4454, 4457, $u['en_id'], $u['en_id']);

                //Inform them:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Sweet, you account is now activated but you are not subscribed to any intents yet. ' . echo_pa_lets(),
                    ),
                ));

            } elseif ($fb_ref == 'ACTIVATE_NO') {

                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Ok, your account will remain unsubscribed. If you changed your mind, ' . echo_pa_lets(),
                    ),
                ));

            }

        } elseif (substr_count($fb_ref, 'ACTIONPLANADD10_') == 1) {

            //Validate this intent:
            $in_id = intval(one_two_explode('ACTIONPLANADD10_', '', $fb_ref));

            if ($in_id == 0) {

                //They rejected the offer... Acknowledge and give response:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Ok, so how can I help you ' . $this->config->item('primary_in_name') . '? ' . echo_pa_lets(),
                    ),
                ));

            } else {

                $fetch_cs = $this->Db_model->in_fetch(array(
                    'in_id' => $in_id,
                ));

                //Any issues?
                if (count($fetch_cs) < 1) {

                    //Ooops we could not find that C:
                    $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'I was unable to locate intent #' . $in_id . ' [' . $fb_ref . ']',
                        ),
                    ));

                } elseif ($fetch_cs[0]['in_status'] < 2) {

                    //Ooops C is no longer active:
                    $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'I was unable to subscribe you to ' . $fetch_cs[0]['in_outcome'] . ' as its not published',
                        ),
                    ));

                } else {

                    //Confirm if they are interested for this intention:
                    $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_in_child_id' => $fetch_cs[0]['in_id'],
                            'tr_content' => 'Hello hello 👋 are you interested to ' . $fetch_cs[0]['in_outcome'] . '?',
                            'quick_replies' => array(
                                array(
                                    'content_type' => 'text',
                                    'title' => 'Yes, Learn More',
                                    'payload' => 'ACTIONPLANADD20_' . $fetch_cs[0]['in_id'],
                                ),
                                array(
                                    'content_type' => 'text',
                                    'title' => 'No',
                                    'payload' => 'ACTIONPLANADD10_0',
                                ),
                            ),
                        ),
                    ));

                }
            }

        } elseif (substr_count($fb_ref, 'ACTIONPLANADD20_') == 1) {

            //Initiating an intent Subscription:
            $tr_in_child_id = intval(one_two_explode('ACTIONPLANADD20_', '', $fb_ref));
            $fetch_cs = $this->Db_model->in_fetch(array(
                'in_id' => $tr_in_child_id,
                'in_status >=' => 2,
            ));
            if (count($fetch_cs) == 1) {

                //Intent seems good...
                //See if this intent belong to any of these subscriptions:
                $trs = $this->Db_model->tr_fetch(array(
                    'tr_en_parent_id' => $u['en_id'], //All subscriptions belonging to this user
                    'tr_status >=' => 0, //Any type of past subscription
                    '(tr_in_parent_id=' . $tr_in_child_id . ' OR tr_in_child_id=' . $tr_in_child_id . ')' => null,
                ), array('cr', 'w', 'w_c'));

                if (count($trs) > 0) {

                    //Let the user know that this is a duplicate:
                    $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_in_child_id' => $fetch_cs[0]['in_id'],
                            'tr_tr_parent_id' => $trs[0]['tr_tr_parent_id'],
                            'tr_content' => ($trs[0]['in_id'] == $tr_in_child_id ? 'You have already subscribed to ' . $fetch_cs[0]['in_outcome'] . '. We have been working on it together since ' . echo_time($trs[0]['w_timestamp'], 2) . '. /open_actionplan' : 'Your subscription to ' . $trs[0]['in_outcome'] . ' already covers the intention to ' . $fetch_cs[0]['in_outcome'] . ', so I will not create a duplicate subscription. /open_actionplan'),
                        ),
                    ));

                } else {

                    //Now we need to confirm if they really want to subscribe to this...

                    //Fetch all the messages for this intent:
                    $tree = $this->Db_model->in_recursive_fetch($tr_in_child_id, true, false);

                    //Show messages for this intent:
                    $trs = $this->Db_model->i_fetch(array(
                        'tr_in_child_id' => $tr_in_child_id,
                        'tr_status >=' => 0, //Published in any form
                    ));

                    foreach ($trs as $i) {
                        $this->Comm_model->send_message(array(
                            array_merge($i, array(
                                'tr_en_child_id' => $u['en_id'],
                            )),
                        ));
                    }

                    //Send message for final confirmation:
                    $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_in_child_id' => $tr_in_child_id,
                            'tr_content' => 'Here is an overview:' . "\n\n" .
                                echo_intent_overview($fetch_cs[0], 1) .
                                echo_contents($fetch_cs[0], 1) .
                                echo_experts($fetch_cs[0], 1) .
                                echo_completion_estimate($fetch_cs[0], 1) .
                                echo_costs($fetch_cs[0], 1) .
                                "\n" . 'Are you ready to ' . $fetch_cs[0]['in_outcome'] . '?',
                            'quick_replies' => array(
                                array(
                                    'content_type' => 'text',
                                    'title' => 'Yes, Subscribe',
                                    'payload' => 'ACTIONPLANADD99_' . $tr_in_child_id,
                                ),
                                array(
                                    'content_type' => 'text',
                                    'title' => 'No',
                                    'payload' => 'ACTIONPLANADD10_0',
                                ),
                                //TODO Maybe Show a "7 Extra Notes" if Drip messages available?
                            ),
                        ),
                    ));

                }
            }

        } elseif (substr_count($fb_ref, 'ACTIONPLANADD99_') == 1) {

            $tr_in_child_id = intval(one_two_explode('ACTIONPLANADD99_', '', $fb_ref));
            //Validate Intent ID:
            $fetch_cs = $this->Db_model->in_fetch(array(
                'in_id' => $tr_in_child_id,
                'in_status >=' => 2,
            ));

            if (count($fetch_cs) == 1) {

                //Add to intent to user's action plan and create a cache of all intent links:
                $w = $this->Db_model->w_create(array(
                    'tr_in_child_id' => $tr_in_child_id,
                    'tr_en_parent_id' => $u['en_id'],
                ));

                //Was this added successfully?
                if (isset($w['tr_id']) && $w['tr_id'] > 0) {

                    //Confirm with them that we're now ready:
                    $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_in_child_id' => $tr_in_child_id,
                            'tr_tr_parent_id' => $w['tr_id'],
                            'tr_content' => 'Success! I have added the intention to ' . $fetch_cs[0]['in_outcome'] . ' to your Action Plan 🙌 /open_actionplan',
                        ),
                    ));

                    //Initiate first message for action plan tree:
                    $this->Comm_model->compose_messages(array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_in_child_id' => $tr_in_child_id,
                        'tr_tr_parent_id' => $w['tr_id'],
                    ), true);

                }
            }

        } elseif (substr_count($fb_ref, 'KCONFIRMEDSKIP_') == 1 || substr_count($fb_ref, 'SKIPREQUEST_') == 1 || substr_count($fb_ref, 'KCANCELSKIP_') == 1) {

            if (substr_count($fb_ref, 'SKIPREQUEST_') == 1) {
                $handler = 'SKIPREQUEST_';
            } elseif (substr_count($fb_ref, 'KCANCELSKIP_') == 1) {
                $handler = 'KCANCELSKIP_';
            } elseif (substr_count($fb_ref, 'KCONFIRMEDSKIP_') == 1) {
                $handler = 'KCONFIRMEDSKIP_';
            }

            //Extract varibales from REF:
            $input_parts = explode('_', one_two_explode($handler, '', $fb_ref));
            $tr_status = intval($input_parts[0]); //It would be $tr_status=1 initial (working on) and then would change to either -1 IF skip was cancelled or 2 IF skip was confirmed.
            $tr_id = intval($input_parts[1]);

            //Validate inputs:
            if (!in_array($tr_status, array(-1, 1, 2)) || $tr_id < 1) {
                //Log Unknown error:
                return $this->Db_model->tr_create(array(
                    'tr_content' => 'fb_ref_process() failed to fetch proper data for a skip request with reference value [' . $fb_ref . ']',
                    'tr_en_type_id' => 4246, //Platform Error
                    'tr_metadata' => $u,
                    'tr_tr_parent_id' => $tr_id,
                ));
            }


            //Was this initiating?
            if ($tr_status == 1) {

                //User has indicated they want to skip this tree and move on to the next item in-line:
                //Lets confirm the implications of this SKIP to ensure they are aware:

                //See how many children would be skipped if they decide to do so:
                $would_be_skipped = $this->Db_model->k_skip_recursive_down($tr_id, false);
                $would_be_skipped_count = count($would_be_skipped);

                if ($would_be_skipped_count == 0) {

                    //Nothing found to skip! This should not happen, log error:
                    $this->Db_model->tr_create(array(
                        'tr_content' => 'fb_ref_process() did not find anything to skip for [' . $fb_ref . ']',
                        'tr_en_type_id' => 4246, //Platform Error
                        'tr_tr_parent_id' => $tr_id,
                        'tr_metadata' => $u,
                    ));

                    //Inform user:
                    return $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'I did not find anything to skip!',
                        ),
                    ));

                }


                //Log transaction for skip request:
                $new_tr = $this->Db_model->tr_create(array(
                    'tr_en_credit_id' => $u['en_id'], //user who searched
                    'tr_en_type_id' => 4284, //Skip Intent
                    'tr_tr_parent_id' => $tr_id, //The parent transaction that points to this intent in the Masters Action Plan
                    'tr_status' => 1, //Working on... not yet decided to skip or not as they need to see the consequences before making an informed decision. Will be updated to -1 or 2 based on their response...
                    'tr_metadata' => array(
                        'would_be_skipped' => $would_be_skipped,
                        'ref' => $fb_ref,
                    ),
                ));


                //Construct the message to give more details on skipping:
                $tr_content = 'You are about to skip these ' . $would_be_skipped_count . ' insight' . echo__s($would_be_skipped_count) . ':';
                foreach ($would_be_skipped as $counter => $k_c) {
                    if (strlen($tr_content) < ($this->config->item('fb_max_message') - 200)) {
                        //We have enough room to add more:
                        $tr_content .= "\n\n" . ($counter + 1) . '/ ' . $k_c['in_outcome'];
                    } else {
                        //We cannot add any more, indicate truncating:
                        $remainder = $would_be_skipped_count - $counter;
                        $tr_content .= "\n\n" . 'And ' . $remainder . ' more insight' . echo__s($remainder) . '!';
                        break;
                    }
                }

                //Recommend against it:
                $tr_content .= "\n\n" . 'I would not recommend skipping unless you feel comfortable learning these concepts on your own.';

                //Send them the message:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => $tr_content,
                        'quick_replies' => array(
                            array(
                                'content_type' => 'text',
                                'title' => 'Skip ' . $would_be_skipped_count . ' insight' . echo__s($would_be_skipped_count) . ' 🚫',
                                'payload' => 'SKIPREQUEST_2_' . $new_tr['tr_id'], //Confirm and skip
                            ),
                            array(
                                'content_type' => 'text',
                                'title' => 'Continue ▶️',
                                'payload' => 'SKIPREQUEST_-1_' . $new_tr['tr_id'], //Cancel skipping
                            ),
                        ),
                    ),
                ));

            } else {


                //They have either confirmed or cancelled the skip:
                if ($tr_status == -1) {

                    //user changed their mind and does not want to skip anymore
                    $tr_content = 'I am happy you changed your mind! Let\'s continue...';

                    //Reset ranking to find the next real item:
                    $tr_order = 0;

                } elseif ($tr_status == 2) {

                    //Actually skip and see if we've finished this Action Plan:
                    $skippable_ks = $this->Db_model->k_skip_recursive_down($tr_id);

                    //Confirm the skip:
                    $tr_content = 'Confirmed, I marked this section as skipped. You can always re-visit these insights in your Action Plan and complete them at any time. /open_actionplan';

                }

                //Send message:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_tr_parent_id' => $tr_id,
                        'tr_content' => $tr_content,
                    ),
                ));

                //Update transaction status accordingly:
                $this->Db_model->tr_update($tr_id, array(
                    'tr_status' => $tr_status,
                ), $u['en_id']);

                //Find the next item to navigate them to:
                $trs_next = $this->Db_model->k_next_fetch($tr_id, $tr_order);
                if ($trs_next) {
                    //Now move on to communicate the next step.
                    $this->Comm_model->compose_messages(array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_in_child_id' => $trs_next[0]['in_id'],
                        'tr_tr_parent_id' => $tr_id,
                    ));
                }

            }

        } elseif (substr_count($fb_ref, 'MARKCOMPLETE_') == 1) {

            //Master consumed AND tree content, and is ready to move on to next intent...
            $input_parts = explode('_', one_two_explode('MARKCOMPLETE_', '', $fb_ref));
            $tr_id = intval($input_parts[0]);
            $tr_id = intval($input_parts[1]);
            $tr_order = intval($input_parts[2]);
            if ($tr_id > 0 && $tr_id > 0 && $tr_order > 0) {

                //Fetch child intent first to check requirements:
                $k_children = $this->Db_model->tr_fetch(array(
                    'tr_id' => $tr_id,
                    'tr_id' => $tr_id,
                ), array('w', 'cr', 'cr_c_child'));

                //Do we need any additional information?
                $requirement_notes = echo_c_requirements($k_children[0], true);
                if ($requirement_notes) {

                    //yes do, let them know that they can only complete via the Action Plan:
                    $this->Comm_model->send_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_in_child_id' => $k_children[0]['in_id'],
                            'tr_tr_parent_id' => $tr_id,
                            'tr_content' => $requirement_notes,
                        ),
                    ));

                } else {

                    //Fetch parent intent to mark as complete:
                    $k_parents = $this->Db_model->tr_fetch(array(
                        'tr_id' => $tr_id,
                        'tr_id' => $tr_id,
                    ), array('w', 'cr', 'cr_c_parent'));

                    //No requirements, Update this intent and move on:
                    $this->Db_model->k_complete_recursive_up($k_parents[0], $k_parents[0]);

                    //Go to next item:
                    $trs_next = $this->Db_model->k_next_fetch($tr_id);
                    if ($trs_next) {
                        //Now move on to communicate the next step.
                        $this->Comm_model->compose_messages(array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_in_child_id' => $trs_next[0]['in_id'],
                            'tr_tr_parent_id' => $tr_id,
                        ));
                    }
                }
            }

        } elseif (substr_count($fb_ref, 'CHOOSEOR_') == 1) {

            //Master has responded to a multiple-choice OR tree
            $input_parts = explode('_', one_two_explode('CHOOSEOR_', '', $fb_ref));
            $tr_id = intval($input_parts[0]);
            $tr_in_parent_id = intval($input_parts[1]);
            $in_id = intval($input_parts[2]);
            $tr_order = intval($input_parts[3]);

            if (!($tr_id > 0 && $tr_in_parent_id > 0 && $in_id > 0 && $tr_order > 0)) {
                //Log Unknown error:
                $this->Db_model->tr_create(array(
                    'tr_content' => 'fb_ref_process() failed to fetch proper data for CHOOSEOR_ request with reference value [' . $fb_ref . ']',
                    'tr_en_type_id' => 4246, //Platform Error
                    'tr_metadata' => $u,
                    'tr_tr_parent_id' => $tr_id,
                    'tr_in_child_id' => $in_id,
                ));
                return false;
            }

            //Confirm answer received:
            $this->Comm_model->send_message(array(
                array(
                    'tr_en_child_id' => $u['en_id'],
                    'tr_in_child_id' => $in_id,
                    'tr_tr_parent_id' => $tr_id,
                    'tr_content' => echo_pa_saved(),
                ),
            ));

            //Now save answer:
            if ($this->Db_model->k_choose_or($tr_id, $tr_in_parent_id, $in_id)) {
                //Find the next item to navigate them to:
                $trs_next = $this->Db_model->k_next_fetch($tr_id, $tr_order);
                if ($trs_next) {
                    //Now move on to communicate the next step.
                    $this->Comm_model->compose_messages(array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_in_child_id' => $trs_next[0]['in_id'],
                        'tr_tr_parent_id' => $tr_id,
                    ));
                }
            }

        }
    }

    function fb_message_process($u, $fb_message_received)
    {

        if (!$fb_message_received) {
            return false;
        }

        $c_target_outcome = null;
        if ($fb_message_received) {
            $fb_message_received = trim(strtolower($fb_message_received));
            if (substr_count($fb_message_received, 'lets ') > 0) {
                $c_target_outcome = one_two_explode('lets ', '', $fb_message_received);
            } elseif (substr_count($fb_message_received, 'let’s ') > 0) {
                $c_target_outcome = one_two_explode('let’s ', '', $fb_message_received);
            } elseif (substr_count($fb_message_received, 'let\'s ') > 0) {
                $c_target_outcome = one_two_explode('let\'s ', '', $fb_message_received);
            } elseif (substr($fb_message_received, -1) == '?') {
                //Them seem to be asking a question, lets treat this as a command:
                $c_target_outcome = str_replace('?', '', $fb_message_received);
            }
        }


        //Check if this user is already un-subscribed:
        $is_unsubscribed = $this->Db_model->tr_fetch(array(
            'tr_en_child_id' => $u['en_id'],
            'tr_en_parent_id' => 4455, //Unsubscribed
            'tr_status >=' => 0,
        ));


        if (includes_any($fb_message_received, array('unsubscribe', 'quit', 'skip'))) {

            if (count($is_unsubscribed) > 0) {
                //User is already unsubscribed, let them know:
                return $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'You are already unsubscribed from Mench and will no longer receive any communication from us. To subscribe again, ' . echo_pa_lets(),
                    ),
                ));
            }

            //List their Action Plan intents:
            $actionplans = $this->Db_model->tr_fetch(array(
                'tr_en_type_id' => 4235, //Intents added to the action plan
                'tr_en_parent_id' => $u['en_id'], //Belongs to this user
                'tr_status IN (0,1,2)' => null, //Actively working on
                //These indicate that this is a top-level intent in the Action Plan:
                'tr_in_parent_id' => 0,
                'tr_tr_parent_id' => 0,
            ), array('in_child'), 100, 0, array('tr_order' => 'ASC'));


            //Do they have anything in their Action Plan?
            if (count($actionplans) > 0) {

                $quick_replies = array();
                $tr_content = 'Choose the intention you like to skip:';
                $increment = 1;

                foreach ($actionplans as $counter => $li) {
                    //Construct unsubscribe confirmation body:
                    $tr_content .= "\n\n" . '/' . ($counter + $increment) . ' Skip ' . $li['in_outcome'];
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => '/' . ($counter + $increment),
                        'payload' => 'APSKIP_' . $li['in_id'],
                    ));
                }

                if (count($actionplans) >= 2) {
                    //Give option to skip all and unsubscribe:
                    $increment++;
                    $tr_content .= "\n\n" . '/' . ($counter + $increment) . ' Skip all intentions and unsubscribe';
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => '/' . ($counter + $increment),
                        'payload' => 'APSKIP_ALL',
                    ));
                }

                //Alwyas give none option:
                $increment++;
                $tr_content .= "\n\n" . '/' . ($counter + $increment) . ' Cancel skipping and continue';
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => '/' . ($counter + $increment),
                    'payload' => 'APSKIP_CANCEL',
                ));

                //Send out message and let them confirm:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => $tr_content,
                        'quick_replies' => $quick_replies,
                    ),
                ));

            } else {

                //They do not have anything in their Action Plan, so we assume they just want to Unsubscribe and stop all future communications:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Got it, just to confirm, you want to unsubscribe and stop all future communications with me?',
                        'quick_replies' => array(
                            array(
                                'content_type' => 'text',
                                'title' => 'Yes, Unsubscribe',
                                'payload' => 'APSKIP_ALL',
                            ),
                            array(
                                'content_type' => 'text',
                                'title' => 'No, Stay Friends',
                                'payload' => 'APSKIP_CANCEL',
                            ),
                        ),
                    ),
                ));

            }

        } elseif ($fb_message_received && count($is_unsubscribed) > 0) {

            //We got a message from an unsubscribed user, let them know:
            return $this->Comm_model->send_message(array(
                array(
                    'tr_en_child_id' => $u['en_id'],
                    'tr_content' => 'You are currently unsubscribed. Would you like me to re-activate your account?',
                    'quick_replies' => array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Yes, Re-Activate',
                            'payload' => 'ACTIVATE_YES',
                        ),
                        array(
                            'content_type' => 'text',
                            'title' => 'Stay Unsubscribed',
                            'payload' => 'ACTIVATE_NO',
                        ),
                    ),
                ),
            ));

        } elseif ($c_target_outcome) {

            //Do a search to see what we find...
            $search_index = load_php_algolia('alg_intents');
            $res = $search_index->search($c_target_outcome, [
                'hitsPerPage' => 6,
                'filters' => 'in_status>=2', //Search published intents
            ]);

            //Log intent search:
            $this->Db_model->tr_create(array(
                'tr_content' => 'Found ' . $res['nbHits'] . ' intent' . echo__s($res['nbHits']) . ' matching "' . $c_target_outcome . '"',
                'tr_metadata' => array(
                    'input_data' => $c_target_outcome,
                    'output' => $res,
                ),
                'tr_en_credit_id' => $u['en_id'], //user who searched
                'tr_en_type_id' => 4275, //Search for New Intent Subscription
            ));

            //Check to see if we have a single result without any children:
            if ($res['nbHits'] == 1 && $res['hits'][0]['in__tree_in_count'] == 1) {

                //Yes, just send the messages of this intent as the response:


            } elseif ($res['nbHits'] > 0) {

                //Show options for them to subscribe to:
                $quick_replies = array();
                $tr_content = 'I found these intents:';

                foreach ($res['hits'] as $count => $hit) {
                    $tr_content .= "\n\n" . ($count + 1) . '/ ' . $hit['in_outcome'] . ' in ' . strip_tags(echo_hours_range($hit));
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => ($count + 1) . '/',
                        'payload' => 'ACTIONPLANADD20_' . $hit['in_id'],
                    ));
                }

                //Give them a none option:
                $tr_content .= "\n\n" . ($count + 2) . '/ None of the above';
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => ($count + 2) . '/',
                    'payload' => 'ACTIONPLANADD10_0',
                ));

                //return what we found to the master to decide:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => $tr_content,
                        'quick_replies' => $quick_replies,
                    ),
                ));

            } else {

                //Respond to user:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Got it! I have made a note on empowering you to "' . $c_target_outcome . '". I will let you know as soon as I am trained on this. Is there anything else I can help you with right now?',
                    ),
                ));

                //Create new intent in the suggestion bucket:
                //$this->Db_model->in_combo_create(000, $c_target_outcome, 0, 2, $u['en_id']);

            }


            //See if an admin has sent this user a message in the last hour via the Facebook Inbox UI:
        } elseif (count($this->Db_model->tr_fetch(array(
                'tr_timestamp >=' => date("Y-m-d H:i:s", (time() - (1800))), //Messages sent from us less than 30 minutes ago
                'tr_en_type_id' => 4280, //Messages sent from us
                'tr_en_credit_id' => 4148, //We log Facebook Inbox UI messages sent with this entity ID
            ), array(), 1)) == 0) {

            //Fetch their currently working on subscriptions:
            $actionplans = $this->Db_model->w_fetch(array(
                'tr_en_parent_id' => $u['en_id'],
                'tr_status' => 1, //Working on...
            ));

            if (count($actionplans) == 0) {

                //There is nothing in their Action plan that they are working on!

                //Log transaction:
                $this->Db_model->tr_create(array(
                    'tr_content' => $fb_message_received,
                    'tr_en_type_id' => 4287, //Log Unrecognizable Message Received
                    'tr_en_credit_id' => $u['en_id'], //User who initiated this message
                ));

                //Recommend to subscribe to our default intent:
                $this->Comm_model->fb_ref_process($u, 'ACTIONPLANADD10_' . $this->config->item('in_primary_id'));

            } elseif (in_array($fb_message_received, array('yes', 'yeah', 'ya', 'ok', 'continue', 'ok continue', 'ok continue ▶️', '▶️', 'ok continue', 'go', 'yass', 'yas', 'yea', 'yup', 'next', 'yes, learn more'))) {

                //Accepting an offer...

            } elseif (in_array($fb_message_received, array('skip', 'skip it'))) {


            } elseif (in_array($fb_message_received, array('help', 'support', 'f1', 'sos'))) {

                //Ask the user if they like to be connected to a human
                //IF yes, create a ATTENTION NEEDED engagement that would notify admin so admin can start a manual conversation

            } elseif (in_array($fb_message_received, array('learn', 'learn more', 'explain', 'explain more'))) {


            } elseif (in_array($fb_message_received, array('no', 'nope', 'nah', 'cancel', 'stop'))) {
                //Rejecting an offer...

            } elseif (substr($fb_message_received, 0, 1) == '/' || is_int($fb_message_received)) {
                //Likely an OR response with a specific number in mind...

            } else {

                //We don't know what this message means!
                //TODO Optimize for multiple subscriptions, this one only deals with the first randomly selected one...

                //Log transaction:
                $this->Db_model->tr_create(array(
                    'tr_content' => $fb_message_received,
                    'tr_en_type_id' => 4287, //Log Unrecognizable Message Received
                    'tr_en_credit_id' => $u['en_id'], //User who initiated this message
                    'tr_tr_parent_id' => $actionplans[0]['tr_id'],
                ));

                //Notify the user that we don't understand:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => echo_pa_oneway(),
                    ),
                ));

                //Remind user of their next step, if any:
                $trs_next = $this->Db_model->k_next_fetch($actionplans[0]['tr_id']);
                if ($trs_next) {
                    $this->Comm_model->compose_messages(array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_in_child_id' => $trs_next[0]['in_id'],
                        'tr_tr_parent_id' => $actionplans[0]['tr_id'],
                    ));
                }

            }
        }
    }


    function fb_identify_activate($fp_psid)
    {

        /*
         *
         * Function will detect the entity (user) ID for all FB webhook calls
         *
         */

        if ($fp_psid < 1) {
            //Ooops, this is not good:
            $this->Db_model->tr_create(array(
                'tr_content' => 'fb_identify_activate() got called without $fp_psid variable',
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }

        //Try finding user references... Is this psid already registered?
        //We either have the user in DB or we'll register them now:
        $fetch_us = $this->Db_model->en_fetch(array(
            'u_fb_psid' => $fp_psid,
        ), array('skip_en__parents'));


        if (count($fetch_us) > 0) {
            //User found:
            return $fetch_us[0];
        }


        //This is a new user that needs to be registered!
        //Call facebook messenger API and get user profile
        $graph_fetch = $this->Comm_model->fb_graph('GET', '/' . $fp_psid, array());


        //Did we find the profile from FB?
        if (!$graph_fetch['status'] || !isset($graph_fetch['tr_metadata']['result']['first_name']) || strlen($graph_fetch['tr_metadata']['result']['first_name']) < 1) {

            //No profile!
            //This happens when user has signed uo to messenger with their phone number or for any reason that Facebook does not provide profile details
            $en = $this->Db_model->en_create(array(
                'en_name' => 'Candidate ' . rand(100000000, 999999999),
            ), true);

            //Inform the user:
            $this->Comm_model->send_message(array(
                array(
                    'tr_en_child_id' => $en['en_id'],
                    'tr_content' => 'Hi stranger! Let\'s get started by completing your profile information by opening the My Account tab in the menu below.',
                ),
            ));

        } else {

            //We did find the profile, move ahead:
            $fb_profile = $graph_fetch['tr_metadata']['result'];

            //Create user
            $en = $this->Db_model->en_create(array(
                'en_name' => $fb_profile['first_name'] . ' ' . $fb_profile['last_name'],
            ), true);

            //Split locale into language and country
            $locale = explode('_', $fb_profile['locale'], 2);

            //Try to match Facebook profile data to internal entities and create links for the ones we find:
            foreach (array(
                         $this->Db_model->en_search_match(3289, $fb_profile['timezone']), //Timezone
                         $this->Db_model->en_search_match(3290, strtolower(substr($fb_profile['gender'], 0, 1))), //Gender either m/f
                         $this->Db_model->en_search_match(3287, strtolower($locale[0])), //Language
                         $this->Db_model->en_search_match(3089, strtolower($locale[1])), //Country
                     ) as $tr_en_parent_id) {
                //Did we find this item?
                if ($tr_en_parent_id > 0) {
                    $this->Db_model->tr_create(array(
                        'tr_en_type_id' => 4230, //Naked link
                        'tr_en_credit_id' => $en['en_id'],
                        'tr_en_parent_id' => $tr_en_parent_id,
                        'tr_en_child_id' => $en['en_id'],
                    ));
                }
            }

        }

        //Now create messenger related fields:
        if ($fp_psid > 0) {

            //Store their messenger ID:
            $this->Db_model->tr_create(array(
                'tr_en_type_id' => 4319, //Number Link
                'tr_en_credit_id' => $en['en_id'],
                'tr_en_parent_id' => 4451, //Mench Personal Assistant on Messenger
                'tr_en_child_id' => $en['en_id'],
                'tr_content' => $fp_psid,
            ));

            //Add them to masters group:
            $this->Db_model->tr_create(array(
                'tr_en_type_id' => 4230, //Naked link
                'tr_en_credit_id' => $en['en_id'],
                'tr_en_parent_id' => 4430, //Mench Master
                'tr_en_child_id' => $en['en_id'],
            ));

            //Subscription Level:
            $this->Db_model->tr_create(array(
                'tr_en_type_id' => 4230, //Naked link
                'tr_en_credit_id' => $en['en_id'],
                'tr_en_parent_id' => 4456, //Receive Regular Notifications (This is the starting point that the Master can change later on...)
                'tr_en_child_id' => $en['en_id'],
            ));
        }


        //Assign people group as we know this is who they are:
        $ur1 = $this->Db_model->tr_create(array(
            'tr_en_child_id' => $u['en_id'],
            'tr_en_parent_id' => 1278,
        ));

        //Log new user engagement:
        $this->Db_model->tr_create(array(
            'tr_en_credit_id' => $u['en_id'],
            'tr_en_type_id' => 4265, //User Joined
            'tr_metadata' => $u,
        ));

        //Save picture locally:
        $this->Db_model->tr_create(array(
            'tr_en_credit_id' => $u['en_id'],
            'tr_content' => $fb_profile['profile_pic'], //Image to be saved
            'tr_status' => 0, //Pending upload
            'tr_en_type_id' => 4299, //Save media file to Mench cloud
        ));

        //Return user object:
        return $u;

    }


    function send_message($trs)
    {

        if (count($trs) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing input messages',
            );
        }

        //Prepare needed variables:
        $failed_count = 0;
        $en_convert_4454 = $this->config->item('en_convert_4454');
        $master_cache = array(); //Caches communication settings for duplicate Masters in $trs messages!


        //Let's run through all input messages:
        foreach ($trs as $tr) {

            //Make sure we have the Master ID that we need to send this message to:
            if (!isset($tr['tr_en_child_id']) || !isset($tr['tr_content']) || strlen($tr['tr_content'])<1) {

                //This should never happen! Log error:
                $failed_count++;

                $this->Db_model->tr_create(array(
                    'tr_metadata' => $tr,
                    'tr_en_type_id' => 4246, //Platform error
                    'tr_content' => 'send_message() failed to send message as it was missing tr_en_child_id/tr_content',
                    'tr_tr_parent_id' => $tr['tr_id'],
                ));

                continue;

            }


            //Did we already fetch this Master's communication settings in a previous $trs message?
            if(isset($master_cache[$tr['tr_en_child_id']])){

                //We already have this user in cache, no need to fetch/validate communication settings as it has already been done:
                $trs_fb_psid = $master_cache[$tr['tr_en_child_id']]['trs_fb_psid'];
                $trs_comm_level = $master_cache[$tr['tr_en_child_id']]['trs_comm_level'];

            } else {

                /*
                 *
                 * Now let's grab this user's communication preferences including their Messenger PSID and communication level
                 *
                 * This is a great example of how to learn more about a user with a pre-determined view-point
                 * (In this case to learn more about their communication preferences)
                 *
                 * */

                $trs_fb_psid = $this->Db_model->tr_fetch(array(
                    'tr_en_parent_id' => 4451, //Mench Personal Assistant on Messenger (tr_content will have the Facebook PSID we need to communicate with them)
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_status >=' => 2,
                ), array('en_child')); //Also fetch user details as we need their name....

                $trs_comm_level = $this->Db_model->tr_fetch(array(
                    'tr_en_parent_id IN (' . join(', ', $this->config->item('en_ids_4454') ) . ')' => null, //Mench Notification Levels (Make sure they are not unsubscribed!)
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_status >=' => 2,
                ));

                //Validate the communication settings to make sure everything is as expected:
                $error_message = null;

                if (!(count($trs_fb_psid) == 1)) {

                    //Log error, should not happen!
                    $error_message = 'send_message() failed to fetch Master relation to Mench Personal Assistant on Messenger.';

                } elseif (!(count($trs_comm_level) == 1)) {

                    //Log error, should not happen!
                    $error_message = 'send_message() failed to fetch Master relation to any Mench Notification Level.';

                } elseif($trs_comm_level[0]['tr_en_parent_id'] == 4455){

                    //This Master is unsubscribed, so we cannot contact them!
                    $error_message = 'send_message() attempted to send a message to an unsubscribed Master which is not allowed.';

                } elseif(intval($trs_fb_psid[0]['tr_content'])<1){

                    //The Mench Personal Assistant on Messenger relation is not storing an integer (Facebook PSID) as expected!
                    $error_message = 'send_message() was unable to locate the Messenger PSID for this Master.';

                } elseif(!array_key_exists($trs_comm_level[0]['tr_en_parent_id'], $en_convert_4454)){

                    //This is an unknown communication level (should never happen!):
                    $error_message = 'send_message() fetched an unknown ['.$trs_comm_level[0]['tr_en_parent_id'].'] Mench Notification Level!';

                }

                //Did we find an error?
                if($error_message){

                    $failed_count++;
                    $this->Db_model->tr_create(array(
                        'tr_en_child_id' => $tr['tr_en_child_id'],
                        'tr_en_type_id' => 4246, //Platform error
                        'tr_metadata' => array(
                            'trs_fb_psid' => $trs_fb_psid,
                            'trs_comm_level' => $trs_comm_level,
                            'tr' => $tr,
                        ),
                        'tr_content' => $error_message,
                        'tr_tr_parent_id' => $tr['tr_id'],
                    ));
                    continue;

                } else {

                    //Add this to user cache in case this user is repeated multiple times within $trs messages:
                    $master_cache[$tr['tr_en_child_id']] = array(
                        'trs_fb_psid' => $trs_fb_psid,
                        'trs_comm_level' => $trs_comm_level,
                    );

                }

            }


            //Prepare Payload:
            $payload = array(
                'recipient' => array( 'id' => $trs_fb_psid[0]['tr_content'] ),
                'message' => echo_i($tr, $trs_fb_psid[0]['en_name'], true),
                'notification_type' => $en_convert_4454[$trs_comm_level[0]['tr_en_parent_id']], //Appropriate notification level
                'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION', //We are always educating users without promoting anything! Learn more at: https://developers.facebook.com/docs/messenger-platform/send-messages#messaging_types
            );

            //Send message via Facebook Graph API:
            $process = $this->Comm_model->fb_graph('POST', '/me/messages', $payload);


            //How did it go?
            if ($process['status']) {

                //Log Successful Transaction:
                $this->Db_model->tr_create(array(
                    'tr_en_type_id' => 4280, //Message Sent
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_tr_parent_id' => $tr['tr_id'],
                    'tr_content' => $tr['tr_content'],
                    'tr_metadata' => array(
                        'input_message' => $tr,
                        'payload' => $payload,
                        'results' => $process,
                    ),
                    //Store some optional fields if available:
                    'tr_en_credit_id' => (isset($tr['tr_en_credit_id']) ? $tr['tr_en_credit_id'] : 0),
                    'tr_en_parent_id' => (isset($tr['tr_en_parent_id']) ? $tr['tr_en_parent_id'] : 0),
                    'tr_in_child_id'  => (isset($tr['tr_in_child_id'])  ? $tr['tr_in_child_id']  : 0),
                    'tr_in_parent_id' => (isset($tr['tr_in_parent_id']) ? $tr['tr_in_parent_id'] : 0),
                ));

            } else {

                //Oooopsi, something went wrong from the Facebook side:
                $failed_count++;

                //Log error:
                $this->Db_model->tr_create(array(
                    'tr_en_type_id' => 4246, //Platform error
                    'tr_metadata' => $tr,
                    'tr_content' => 'send_message() encountered a Facebook Graph error when trying to send a message to a Master.',
                    'tr_tr_parent_id' => $tr['tr_id'],
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_metadata' => array(
                        'input_message' => $tr,
                        'payload' => $payload,
                        'results' => $process,
                    ),
                ));

            }

        }


        //Return results:
        if ($failed_count > 0) {

            return array(
                'status' => 0,
                'message' => 'Failed to send ' . $failed_count . '/' . count($trs) . ' message' . echo__s(count($trs)),
            );

        } else {

            return array(
                'status' => 1,
                'message' => 'Successfully sent ' . count($trs) . ' message' . echo__s(count($trs)),
            );

        }
    }

    function compose_messages($e, $skip_messages = false)
    {

        //Validate key components that are required:
        $error_message = null;
        if (count($e) < 1) {
            $error_message = 'Missing $e';
        } elseif (!isset($e['tr_in_child_id']) || $e['tr_in_child_id'] < 1) {
            $error_message = 'Missing tr_in_child_id';
        } elseif (!isset($e['tr_en_child_id']) || $e['tr_en_child_id'] < 1) {
            $error_message = 'Missing  tr_en_child_id';
        }

        if (!$error_message) {

            //Fetch intent and its messages with an appropriate depth
            $intents = $this->Db_model->in_fetch(array(
                'in_id' => $e['tr_in_child_id'],
            ), array('in__active_messages')); //Supports up to 2 levels deep for now...

            //Check to see if we have any other errors:
            if (!isset($intents[0])) {
                $error_message = 'Invalid Intent ID [' . $e['tr_in_child_id'] . ']';
            } else {
                //Check the required notes as we'll use this later:
                $requirement_notes = echo_c_requirements($intents[0], true);
            }
        }

        //Did we catch any errors?
        if ($error_message) {
            //Log error:
            $this->Db_model->tr_create(array(
                'tr_content' => 'compose_messages() error: ' . $error_message,
                'tr_en_type_id' => 4246, //Platform Error
                'tr_metadata' => $e,
                'tr_en_child_id' => $e['tr_en_child_id'],
                'tr_in_child_id' => $e['tr_in_child_id'],
                'tr_en_credit_id' => $e['tr_en_credit_id'],
            ));

            //Return error:
            return array(
                'status' => 0,
                'message' => $error_message,
            );
        }


        //Let's start adding-up the instant messages:
        $instant_messages = array();

        //Give some context on the current intent:
        if (isset($e['tr_tr_parent_id']) && $e['tr_tr_parent_id'] > 0) {

            //Lets see how many child intents there are
            $k_outs = $this->Db_model->tr_fetch(array(
                'tr_id' => $e['tr_tr_parent_id'],
                'tr_status IN (0,1)' => null, //Active subscriptions only
                'tr_in_parent_id' => $e['tr_in_child_id'],
                //We are fetching with any tr_status just to see what is available/possible from here
            ), array('w', 'cr', 'cr_c_child'));

            if (count($k_outs) > 0 && !($k_outs[0]['tr_in_child_id'] == $e['tr_in_child_id'])) {
                //Only confirm the intention if its not the top-level action plan intention:
                array_push($instant_messages, array(
                    'tr_en_child_id' => $e['tr_en_child_id'],
                    'tr_in_child_id' => $e['tr_in_child_id'],
                    'tr_tr_parent_id' => $e['tr_tr_parent_id'],
                    'tr_content' => 'Let’s ' . $intents[0]['in_outcome'] . '.',
                ));
            }

        }


        //Append main object messages:
        if (!$skip_messages && isset($intents[0]['in__active_messages']) && count($intents[0]['in__active_messages']) > 0) {
            //We have messages for the very first level!
            foreach ($intents[0]['in__active_messages'] as $key => $i) {
                if ($i['tr_status'] == 1) {
                    //Add message to instant stream:
                    array_push($instant_messages, array_merge($e, $i));
                }
            }
        }


        //Do we have a subscription, if so, we need to add a next step message:
        if ($requirement_notes) {

            //URL or a written response is required, let them know that they should complete using the Action Plan:
            array_push($instant_messages, array(
                'tr_en_child_id' => $e['tr_en_child_id'],
                'tr_in_child_id' => $e['tr_in_child_id'],
                'tr_tr_parent_id' => $e['tr_tr_parent_id'],
                'tr_content' => $requirement_notes,
            ));

        } elseif (isset($e['tr_tr_parent_id']) && $e['tr_tr_parent_id'] > 0) {

            $tr_content = null;
            $quick_replies = array();

            //Nothing is required to mark as complete, which means we can move forward with this:
            //How many children do we have for this intent?
            if (count($k_outs) <= 1) {

                //We have 0-1 child intents! If zero, let's see what the next step:
                if (count($k_outs) == 0) {
                    //Let's try to find the next item in tree:
                    $k_outs = $this->Db_model->k_next_fetch($e['tr_tr_parent_id']);
                }

                //Do we have a next intent?
                if (count($k_outs) > 0 && !($k_outs[0]['in_id'] == $intents[0]['in_id'])) {

                    //Give option to move on:
                    $tr_content .= 'The next step to ' . $intents[0]['in_outcome'] . ' is to ' . $k_outs[0]['in_outcome'] . '.';
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Ok Continue ▶️',
                        'payload' => 'MARKCOMPLETE_' . $e['tr_tr_parent_id'] . '_' . $k_outs[0]['tr_id'] . '_' . $k_outs[0]['tr_order'], //Here are are using MARKCOMPLETE_ also for OR branches with a single option... Maybe we need to change this later?! For now it feels ok to do so...
                    ));

                }

            } else {

                //We have multiple children that are pending completion...
                //Is it ALL or ANY?
                if (intval($intents[0]['in_is_any'])) {

                    //Note that ANY nodes cannot require a written response or a URL
                    //User needs to choose one of the following:
                    $tr_content .= 'Choose one of these ' . count($k_outs) . ' options to ' . $intents[0]['in_outcome'] . ':';
                    foreach ($k_outs as $counter => $k) {
                        if ($counter == 10) {
                            break; //Quick reply accepts 11 options max!
                            //We know that the $tr_content length cannot surpass the limit defined by fb_max_message variable!
                        }
                        $tr_content .= "\n\n" . ($counter + 1) . '/ ' . $k['in_outcome'];
                        array_push($quick_replies, array(
                            'content_type' => 'text',
                            'title' => '/' . ($counter + 1),
                            'payload' => 'CHOOSEOR_' . $e['tr_tr_parent_id'] . '_' . $e['tr_in_child_id'] . '_' . $k['in_id'] . '_' . $k['tr_order'],
                        ));
                    }

                } else {

                    //User needs to complete all children, and we'd recommend the first item as their next step:
                    $tr_content .= 'There are ' . count($k_outs) . ' steps to ' . $intents[0]['in_outcome'] . ':';
                    foreach ($k_outs as $counter => $k) {

                        if ($counter == 0) {
                            array_push($quick_replies, array(
                                'content_type' => 'text',
                                'title' => 'Start Step 1 ▶️',
                                'payload' => 'MARKCOMPLETE_' . $e['tr_tr_parent_id'] . '_' . $k['tr_id'] . '_' . $k['tr_order'],
                            ));
                        }

                        //make sure message is within range:
                        if (strlen($tr_content) < ($this->config->item('fb_max_message') - 200)) {
                            //Add message:
                            $tr_content .= "\n\n" . 'Step ' . ($counter + 1) . ': ' . $k['in_outcome'];
                        } else {
                            //We cannot add any more, indicate truncating:
                            $remainder = count($k_outs) - $counter;
                            $tr_content .= "\n\n" . 'And ' . $remainder . ' more step' . echo__s($remainder) . '!';
                            break;
                        }
                    }

                }


                //As long as $e['tr_in_child_id'] is NOT equal to tr_in_child_id, then we will have a k_out relation so we can give the option to skip:
                $k_ins = $this->Db_model->tr_fetch(array(
                    'tr_id' => $e['tr_tr_parent_id'],
                    'tr_status IN (0,1)' => null, //Active subscriptions only
                    'tr_in_child_id' => $e['tr_in_child_id'],
                ), array('w', 'cr', 'cr_c_child'));


                if (count($k_ins) > 0) {
                    //Give option to skip if NOT the top-level intent:
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Skip',
                        'payload' => 'SKIPREQUEST_1_' . $k_ins[0]['tr_id'],
                    ));
                }
            }

            //Append next-step message:
            array_push($instant_messages, array(
                'tr_en_child_id' => $e['tr_en_child_id'],
                'tr_in_child_id' => $e['tr_in_child_id'],
                'tr_tr_parent_id' => $e['tr_tr_parent_id'],
                'tr_content' => $tr_content,
                'quick_replies' => $quick_replies,
            ));

        }


        //Anything to be sent instantly?
        if (count($instant_messages) < 1) {
            //Nothing to be sent
            return array(
                'status' => 0,
                'message' => 'No messages to be sent',
            );
        }

        //All good, attempt to Dispatch all messages, their engagements have already been logged:
        return $this->Comm_model->send_message($instant_messages);

    }


    function send_email($to_array, $subject, $html_message, $e_var_create = array(), $reply_to = null)
    {

        /*
         *
         * DEPRECATED!
         *
         * We used to support sending emails but recently we've just
         * focused on Messenger as the only medium of communication!
         *
         * */

        return true;

        if (is_dev()) {
            return true;
        }

        //Loadup amazon SES:
        require_once('application/libraries/aws/aws-autoloader.php');
        $this->CLIENT = new Aws\Ses\SesClient([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => $this->config->item('aws_credentials'),
        ]);

        if (!$reply_to) {
            //Set default:
            $reply_to = 'support@mench.com';
        }

        //Log transaction once:
        if (count($e_var_create) > 0) {
            $this->Db_model->tr_create($e_var_create);
        }

        return $this->CLIENT->sendEmail(array(
            // Source is required
            'Source' => 'support@mench.com',
            // Destination is required
            'Destination' => array(
                'ToAddresses' => $to_array,
                'CcAddresses' => array(),
                'BccAddresses' => array(),
            ),
            // Message is required
            'Message' => array(
                // Subject is required
                'Subject' => array(
                    // Data is required
                    'Data' => $subject,
                    'Charset' => 'UTF-8',
                ),
                // Body is required
                'Body' => array(
                    'Text' => array(
                        // Data is required
                        'Data' => strip_tags($html_message),
                        'Charset' => 'UTF-8',
                    ),
                    'Html' => array(
                        // Data is required
                        'Data' => $html_message,
                        'Charset' => 'UTF-8',
                    ),
                ),
            ),
            'ReplyToAddresses' => array($reply_to),
            'ReturnPath' => 'support@mench.com',
        ));

    }

}