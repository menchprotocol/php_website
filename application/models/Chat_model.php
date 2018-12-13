<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat_model extends CI_Model
{

    /*
     *
     * This model contains all chat related functions
     * to interact with various chat platforms
     * (currently we only support Facebook)
     * and interpret incoming messages while dispatching
     * outgoing messages via various channels.
     *
     * Think of this as the most external layer
     * input/output processor for our platform.
     *
     * */

    function __construct()
    {
        parent::__construct();
    }

    function facebook_graph($action, $url, $payload = array())
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
            $message_error = 'Chat_model->facebook_graph() failed to ' . $action . ' ' . $url;
            $this->Database_model->tr_create(array(
                'tr_content' => $message_error,
                'tr_en_type_id' => 4246, //Platform Error
                'tr_metadata' => $tr_metadata,
            ));

            //There was an issue accessing this on FB
            return array(
                'status' => 0,
                'message' => $message_error,
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


    function digest_metadata($u, $fb_ref)
    {

        /*
         *
         * With the assumption that chat platforms like Messenger,
         * Slack and Telegram offer a mechanism to manage a metadata
         * field other than the actual message itself (Facebook calls
         * this the Reference key or Metadata), this function will
         * process that metadata string and take appropriate action
         * based on the value of the metadata.
         *
         * */

        if (!$fb_ref || strlen($fb_ref) < 1) {

            return false;

        } elseif (substr_count($fb_ref, 'APSKIP_') == 1) {

            $unsub_value = one_two_explode('APSKIP_', '', $fb_ref);

            if ($unsub_value == 'CANCEL') {

                //User changed their mind, confirm:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Awesome, I am excited to continue helping you to ' . $this->config->item('in_primary_name') . '. ' . echo_pa_lets(),
                    ),
                ));

            } elseif ($unsub_value == 'ALL') {

                //User wants completely out...

                //Skip everything from their Action Plan
                $this->db->query("UPDATE tb_actionplans SET tr_status=-1 WHERE tr_status>=0 AND tr_en_parent_id=" . $u['en_id']);
                $intents_skipped = $this->db->affected_rows();

                //Update User communication level to Unsubscribe:
                $this->Database_model->en_radio_set(4454, 4455, $u['en_id'], $u['en_id']);

                //Let them know:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Confirmed, I skipped all ' . $intents_skipped . ' intent' . echo__s($intents_skipped) . ' in your Action Plan. This is the final message you will receive from me unless you message me. Take care of your self and I hope to talk to you soon 😘',
                    ),
                ));

            } elseif (intval($unsub_value) > 0) {

                //User wants to skip a specific intent from their Action Plan, validate it:
                $trs = $this->Database_model->w_fetch(array(
                    'tr_id' => intval($unsub_value),
                    'tr_status >=' => 0,
                ), array('in'));

                //All good?
                if (count($trs) == 1) {

                    //Update status for this single subscription:
                    $this->db->query("UPDATE tb_actionplans SET tr_status=-1 WHERE tr_id=" . intval($unsub_value));

                    //Show success message to user:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'I have successfully skipped the intention to ' . $trs[0]['in_outcome'] . '. Say "Unsubscribe" if you wish to stop all future communications. ' . echo_pa_lets(),
                        ),
                    ));

                } else {

                    //let them know we had error:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'Unable to process your request as I could not locate your subscription. Please try again.',
                        ),
                    ));

                    //Log error engagement:
                    $this->Database_model->tr_create(array(
                        'tr_en_credit_id' => $u['en_id'],
                        'tr_content' => 'Failed to skip an intent from the master Action Plan',
                        'tr_en_type_id' => 4246, //Platform Error
                        'tr_tr_parent_id' => intval($unsub_value),
                    ));

                }
            }

        } elseif (substr_count($fb_ref, 'ACTIVATE_') == 1) {

            if ($fb_ref == 'ACTIVATE_YES') {

                //Update User communication level to Receive Silent Push Notifications:
                $this->Database_model->en_radio_set(4454, 4457, $u['en_id'], $u['en_id']);

                //Inform them:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Sweet, you account is now activated but you are not subscribed to any intents yet. ' . echo_pa_lets(),
                    ),
                ));

            } elseif ($fb_ref == 'ACTIVATE_NO') {

                $this->Chat_model->dispatch_message(array(
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
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Ok, so how can I help you ' . $this->config->item('in_primary_name') . '? ' . echo_pa_lets(),
                    ),
                ));

            } else {

                $fetch_cs = $this->Database_model->in_fetch(array(
                    'in_id' => $in_id,
                ));

                //Any issues?
                if (count($fetch_cs) < 1) {

                    //Ooops we could not find that C:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'I was unable to locate intent #' . $in_id . ' [' . $fb_ref . ']',
                        ),
                    ));

                } elseif ($fetch_cs[0]['in_status'] < 2) {

                    //Ooops C is no longer active:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'I was unable to subscribe you to ' . $fetch_cs[0]['in_outcome'] . ' as its not published',
                        ),
                    ));

                } else {

                    //Confirm if they are interested for this intention:
                    $this->Chat_model->dispatch_message(array(
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
            $fetch_cs = $this->Database_model->in_fetch(array(
                'in_id' => $tr_in_child_id,
                'in_status >=' => 2,
            ));
            if (count($fetch_cs) == 1) {

                //Intent seems good...
                //See if this intent belong to any of these subscriptions:
                $trs = $this->Database_model->tr_fetch(array(
                    'tr_en_parent_id' => $u['en_id'], //All subscriptions belonging to this user
                    'tr_status >=' => 0, //Any type of past subscription
                    '(tr_in_parent_id=' . $tr_in_child_id . ' OR tr_in_child_id=' . $tr_in_child_id . ')' => null,
                ), array('cr', 'w', 'w_c'));

                if (count($trs) > 0) {

                    //Let the user know that this is a duplicate:
                    $this->Chat_model->dispatch_message(array(
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
                    $tree = $this->Database_model->in_recursive_fetch($tr_in_child_id, true, false);

                    //Show messages for this intent:
                    $trs = $this->Database_model->i_fetch(array(
                        'tr_in_child_id' => $tr_in_child_id,
                        'tr_status >=' => 0, //Published in any form
                    ));

                    foreach ($trs as $i) {
                        $this->Chat_model->dispatch_message(array(
                            array_merge($i, array(
                                'tr_en_child_id' => $u['en_id'],
                            )),
                        ));
                    }

                    //Send message for final confirmation:
                    $this->Chat_model->dispatch_message(array(
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
            $fetch_cs = $this->Database_model->in_fetch(array(
                'in_id' => $tr_in_child_id,
                'in_status >=' => 2,
            ));

            if (count($fetch_cs) == 1) {

                //Add to intent to user's action plan and create a cache of all intent links:
                $w = $this->Database_model->w_create(array(
                    'tr_in_child_id' => $tr_in_child_id,
                    'tr_en_parent_id' => $u['en_id'],
                ));

                //Was this added successfully?
                if (isset($w['tr_id']) && $w['tr_id'] > 0) {

                    //Confirm with them that we're now ready:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_in_child_id' => $tr_in_child_id,
                            'tr_tr_parent_id' => $w['tr_id'],
                            'tr_content' => 'Success! I have added the intention to ' . $fetch_cs[0]['in_outcome'] . ' to your Action Plan 🙌 /open_actionplan',
                        ),
                    ));

                    //Initiate first message for action plan tree:
                    $this->Matrix_model->compose_messages(array(
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
                return $this->Database_model->tr_create(array(
                    'tr_content' => 'digest_metadata() failed to fetch proper data for a skip request with reference value [' . $fb_ref . ']',
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
                $would_be_skipped = $this->Database_model->k_skip_recursive_down($tr_id, false);
                $would_be_skipped_count = count($would_be_skipped);

                if ($would_be_skipped_count == 0) {

                    //Nothing found to skip! This should not happen, log error:
                    $this->Database_model->tr_create(array(
                        'tr_content' => 'digest_metadata() did not find anything to skip for [' . $fb_ref . ']',
                        'tr_en_type_id' => 4246, //Platform Error
                        'tr_tr_parent_id' => $tr_id,
                        'tr_metadata' => $u,
                    ));

                    //Inform user:
                    return $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_content' => 'I did not find anything to skip!',
                        ),
                    ));

                }


                //Log transaction for skip request:
                $new_tr = $this->Database_model->tr_create(array(
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
                $this->Chat_model->dispatch_message(array(
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
                    $skippable_ks = $this->Database_model->k_skip_recursive_down($tr_id);

                    //Confirm the skip:
                    $tr_content = 'Confirmed, I marked this section as skipped. You can always re-visit these insights in your Action Plan and complete them at any time. /open_actionplan';

                }

                //Send message:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_tr_parent_id' => $tr_id,
                        'tr_content' => $tr_content,
                    ),
                ));

                //Update transaction status accordingly:
                $this->Database_model->tr_update($tr_id, array(
                    'tr_status' => $tr_status,
                ), $u['en_id']);

                //Find the next item to navigate them to:
                $trs_next = $this->Database_model->k_next_fetch($tr_id, $tr_order);
                if ($trs_next) {
                    //Now move on to communicate the next step.
                    $this->Matrix_model->compose_messages(array(
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
                $k_children = $this->Database_model->tr_fetch(array(
                    'tr_id' => $tr_id,
                    'tr_id' => $tr_id,
                ), array('w', 'cr', 'cr_c_child'));


                //Does this intent have any requirements to be marked as complete?
                $message_in_requirements = $this->Matrix_model->in_completion_requirements($k_children[0], true);

                if ($message_in_requirements) {

                    //yes do, let them know that they can only complete via the Action Plan:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $u['en_id'],
                            'tr_in_child_id' => $k_children[0]['in_id'],
                            'tr_tr_parent_id' => $tr_id,
                            'tr_content' => $message_in_requirements,
                        ),
                    ));

                } else {

                    //The intent did not have any requirements to be marked as complete!
                    //Fetch parent intent to mark as complete:
                    $k_parents = $this->Database_model->tr_fetch(array(
                        'tr_id' => $tr_id,
                        'tr_id' => $tr_id,
                    ), array('w', 'cr', 'cr_c_parent'));

                    //No requirements, Update this intent and move on:
                    $this->Database_model->k_complete_recursive_up($k_parents[0], $k_parents[0]);

                    //Go to next item:
                    $trs_next = $this->Database_model->k_next_fetch($tr_id);
                    if ($trs_next) {
                        //Now move on to communicate the next step.
                        $this->Matrix_model->compose_messages(array(
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
                $this->Database_model->tr_create(array(
                    'tr_content' => 'digest_metadata() failed to fetch proper data for CHOOSEOR_ request with reference value [' . $fb_ref . ']',
                    'tr_en_type_id' => 4246, //Platform Error
                    'tr_metadata' => $u,
                    'tr_tr_parent_id' => $tr_id,
                    'tr_in_child_id' => $in_id,
                ));
                return false;
            }

            //Confirm answer received:
            $this->Chat_model->dispatch_message(array(
                array(
                    'tr_en_child_id' => $u['en_id'],
                    'tr_in_child_id' => $in_id,
                    'tr_tr_parent_id' => $tr_id,
                    'tr_content' => echo_pa_saved(),
                ),
            ));

            //Now save answer:
            if ($this->Database_model->k_choose_or($tr_id, $tr_in_parent_id, $in_id)) {
                //Find the next item to navigate them to:
                $trs_next = $this->Database_model->k_next_fetch($tr_id, $tr_order);
                if ($trs_next) {
                    //Now move on to communicate the next step.
                    $this->Matrix_model->compose_messages(array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_in_child_id' => $trs_next[0]['in_id'],
                        'tr_tr_parent_id' => $tr_id,
                    ));
                }
            }

        }
    }

    function digest_message($u, $fb_message_received)
    {

        /*
         *
         * Will process the chat message only in the absence of a chat metadata
         * (otherwise the digest_message() will process the message since
         * we know that the medata would have more precise instructions on what
         * needs to be done for the Master response)
         *
         * This involves string analysis and matching terms to a intents, entities
         * and known commands that will help us understand the Master and
         * hopefully provide them with the information they need, right now.
         *
         * We'd eventually need to migrate the search engine to an NLP platform
         * Like dialogflow.com (By Google) or wit.ai (By Facebook) to improve
         * our ability to detect correlations specifically for intents.
         *
         * */

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
        $is_unsubscribed = $this->Database_model->tr_fetch(array(
            'tr_en_child_id' => $u['en_id'],
            'tr_en_parent_id' => 4455, //Unsubscribed
            'tr_status >=' => 0,
        ));


        if (includes_any($fb_message_received, array('unsubscribe', 'quit', 'skip'))) {

            if (count($is_unsubscribed) > 0) {
                //User is already unsubscribed, let them know:
                return $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'You are already unsubscribed from Mench and will no longer receive any communication from us. To subscribe again, ' . echo_pa_lets(),
                    ),
                ));
            }

            //List their Action Plan intents:
            $actionplans = $this->Database_model->tr_fetch(array(
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
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => $tr_content,
                        'quick_replies' => $quick_replies,
                    ),
                ));

            } else {

                //They do not have anything in their Action Plan, so we assume they just want to Unsubscribe and stop all future communications:
                $this->Chat_model->dispatch_message(array(
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
            return $this->Chat_model->dispatch_message(array(
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
            $this->Database_model->tr_create(array(
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
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => $tr_content,
                        'quick_replies' => $quick_replies,
                    ),
                ));

            } else {

                //Respond to user:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => 'Got it! I have made a note on empowering you to "' . $c_target_outcome . '". I will let you know as soon as I am trained on this. Is there anything else I can help you with right now?',
                    ),
                ));

                //Create new intent in the suggestion bucket:
                //$this->Database_model->in_combo_create(000, $c_target_outcome, 0, 2, $u['en_id']);

            }


            //See if an admin has sent this user a message in the last hour via the Facebook Inbox UI:
        } elseif (count($this->Database_model->tr_fetch(array(
                'tr_timestamp >=' => date("Y-m-d H:i:s", (time() - (1800))), //Messages sent from us less than 30 minutes ago
                'tr_en_type_id' => 4280, //Messages sent from us
                'tr_en_credit_id' => 4148, //We log Facebook Inbox UI messages sent with this entity ID
            ), array(), 1)) == 0) {

            //Fetch their currently working on subscriptions:
            $actionplans = $this->Database_model->w_fetch(array(
                'tr_en_parent_id' => $u['en_id'],
                'tr_status' => 1, //Working on...
            ));

            if (count($actionplans) == 0) {

                //There is nothing in their Action plan that they are working on!

                //Log transaction:
                $this->Database_model->tr_create(array(
                    'tr_content' => $fb_message_received,
                    'tr_en_type_id' => 4287, //Log Unrecognizable Message Received
                    'tr_en_credit_id' => $u['en_id'], //User who initiated this message
                ));

                //Recommend to subscribe to our default intent:
                $this->Chat_model->digest_metadata($u, 'ACTIONPLANADD10_' . $this->config->item('in_primary_id'));

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
                $this->Database_model->tr_create(array(
                    'tr_content' => $fb_message_received,
                    'tr_en_type_id' => 4287, //Log Unrecognizable Message Received
                    'tr_en_credit_id' => $u['en_id'], //User who initiated this message
                    'tr_tr_parent_id' => $actionplans[0]['tr_id'],
                ));

                //Notify the user that we don't understand:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_content' => echo_pa_oneway(),
                    ),
                ));

                //Remind user of their next step, if any:
                $trs_next = $this->Database_model->k_next_fetch($actionplans[0]['tr_id']);
                if ($trs_next) {
                    $this->Matrix_model->compose_messages(array(
                        'tr_en_child_id' => $u['en_id'],
                        'tr_in_child_id' => $trs_next[0]['in_id'],
                        'tr_tr_parent_id' => $actionplans[0]['tr_id'],
                    ));
                }

            }
        }
    }


    function dispatch_message($trs)
    {


        /*
         * Will attempt to send a message to the Master using the
         * messaging platform of their choice based on the Entity
         * relations we find for the given master for each message
         * that is passed on in $trs
         *
         * */

        if (count($trs) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing input messages',
            );
        }

        //Prepare needed variables:
        $failed_count = 0; //Keeps track of failed $trs that we could not send
        $en_convert_4454 = $this->config->item('en_convert_4454'); //Translates our settings to Facebook Notification Settings
        $master_cache = array(); //Simple caching mechanism for communication settings to prevent fetching data multiple times...


        //Let's run through all input messages:
        foreach ($trs as $tr) {

            //Make sure we have the Master ID that we need to send this message to:
            if (!isset($tr['tr_en_child_id']) || !isset($tr['tr_content']) || strlen($tr['tr_content']) < 1) {

                //This should never happen! Log error:
                $failed_count++;

                $this->Database_model->tr_create(array(
                    'tr_metadata' => $tr,
                    'tr_en_type_id' => 4246, //Platform error
                    'tr_content' => 'dispatch_message() failed to send message as it was missing tr_en_child_id/tr_content',
                    'tr_tr_parent_id' => (isset($tr['tr_id']) ? $tr['tr_id'] : 0),
                ));

                continue;

            }


            //Did we already fetch this Master's communication settings in a previous $trs message?
            if (isset($master_cache[$tr['tr_en_child_id']])) {

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

                //Mench Personal Assistant on Messenger:
                $trs_fb_psid = $this->Database_model->tr_fetch(array(
                    'tr_en_parent_id' => 4451,
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_status >=' => 2,
                ), array('en_child')); //Also fetch user details as we need their name....
                //Note: tr_content will have the Facebook PSID we need to communicate with them
                //Note: If we find multiple of these, we'd only consider the first one: $trs_fb_psid[0]


                //Mench Notification Levels:
                $trs_comm_level = $this->Database_model->tr_fetch(array(
                    'tr_en_parent_id IN (' . join(',', $this->config->item('en_ids_4454')) . ')' => null,
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_status >=' => 2,
                ));
                //Note: This should find exactly one result as it belongs to Master Radio Entity @4461



                //Start validating communication settings we fetched to ensure everything is A-OK:
                $message_error = null;

                if (count($trs_fb_psid) < 1) {

                    //Log error, should not happen!
                    $message_error = 'dispatch_message() failed to fetch Master relation to Mench Personal Assistant on Messenger.';

                } elseif (!(count($trs_comm_level) == 1)) {

                    //Log error, should not happen! Since this is part of
                    $message_error = 'dispatch_message() failed to fetch Master relation to any Mench Notification Level.';

                } elseif ($trs_comm_level[0]['tr_en_parent_id'] == 4455) {

                    //This Master is unsubscribed, so we cannot contact them!
                    $message_error = 'dispatch_message() attempted to send a message to an unsubscribed Master which is not allowed.';

                } elseif (intval($trs_fb_psid[0]['tr_content']) < 1) {

                    //The Mench Personal Assistant on Messenger relation is not storing an integer (Facebook PSID) as expected!
                    $message_error = 'dispatch_message() was unable to locate the Messenger PSID for this Master.';

                } elseif (!array_key_exists($trs_comm_level[0]['tr_en_parent_id'], $en_convert_4454)) {

                    //This is an unknown communication level (should never happen!):
                    $message_error = 'dispatch_message() fetched an unknown [' . $trs_comm_level[0]['tr_en_parent_id'] . '] Mench Notification Level!';

                }



                //Did we find an error?
                if ($message_error) {

                    $failed_count++;
                    $this->Database_model->tr_create(array(
                        'tr_en_child_id' => $tr['tr_en_child_id'],
                        'tr_en_type_id' => 4246, //Platform error
                        'tr_metadata' => array(
                            'trs_fb_psid' => $trs_fb_psid,
                            'trs_comm_level' => $trs_comm_level,
                            'tr' => $tr,
                        ),
                        'tr_content' => $message_error,
                        'tr_tr_parent_id' => (isset($tr['tr_id']) ? $tr['tr_id'] : 0),
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
                'recipient' => array('id' => $trs_fb_psid[0]['tr_content']),
                'message' => echo_i($tr, $trs_fb_psid[0]['en_name'], true),
                'notification_type' => $en_convert_4454[$trs_comm_level[0]['tr_en_parent_id']], //Appropriate notification level
                'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION', //We are always educating users without promoting anything! Learn more at: https://developers.facebook.com/docs/messenger-platform/send-messages#messaging_types
            );

            //Send message via Facebook Graph API:
            $process = $this->Chat_model->facebook_graph('POST', '/me/messages', $payload);


            //How did it go?
            if ($process['status']) {

                //Log Successful Transaction:
                $this->Database_model->tr_create(array(
                    'tr_en_type_id' => 4280, //Message Sent
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_content' => $tr['tr_content'],
                    'tr_metadata' => array(
                        'input_message' => $tr,
                        'payload' => $payload,
                        'results' => $process,
                    ),
                    //Store some optional fields if available:
                    'tr_en_credit_id' => (isset($tr['tr_en_credit_id']) ? $tr['tr_en_credit_id'] : 0),
                    'tr_en_parent_id' => (isset($tr['tr_en_parent_id']) ? $tr['tr_en_parent_id'] : 0),
                    'tr_in_parent_id' => (isset($tr['tr_in_parent_id']) ? $tr['tr_in_parent_id'] : 0),
                    'tr_in_child_id' => (isset($tr['tr_in_child_id']) ? $tr['tr_in_child_id'] : 0),
                    'tr_tr_parent_id' => (isset($tr['tr_id']) ? $tr['tr_id'] : 0),
                ));

            } else {

                //Oooopsi, something went wrong from the Facebook side:
                $failed_count++;

                //Log error:
                $this->Database_model->tr_create(array(
                    'tr_en_type_id' => 4246, //Platform error
                    'tr_metadata' => $tr,
                    'tr_content' => 'dispatch_message() encountered a Facebook Graph error when trying to send a message to a Master.',
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



    function dispatch_email($to_array, $subject, $html_message, $tr_create = array(), $reply_to = null)
    {

        /*
         *
         * DEPRECATED for now!
         *
         * We used to support sending emails but since Dec 2018 we've
         * focused on Messenger as the only medium of communication.
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
        if (count($tr_create) > 0) {
            $this->Database_model->tr_create($tr_create);
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