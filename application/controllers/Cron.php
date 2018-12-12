<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        //Example: /usr/bin/php /home/ubuntu/mench-web-app/index.php cron save_profile_pic
    }


    //Cache of cron jobs as of now [keep in sync when updating cron file]
    //* * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron message_file_save
    //* * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron message_fb_sync_attachments
    //*/5 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron message_drip
    //*/6 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron save_profile_pic
    //31 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron intent_sync
    //45 * * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron master_reminder_complete_task
    //30 2 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron algolia_sync b 0
    //30 4 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron algolia_sync u 0
    //30 3 * * * /usr/bin/php /home/ubuntu/mench-web-app/index.php cron e_score_recursive


    function treecache(){

        /*
         * This function prepares a PHP-friendly text to be copies to treecache.php
         * (which is auto loaded) to provide a cache image of some entities in
         * the tree for faster application processing.
         *
         * */

        //First first all entities that have Cache in PHP Config @4527 as their parent:
        $config_ens = $this->Db_model->tr_fetch(array(
            'tr_status >=' => 0,
            'tr_en_child_id >' => 0,
            'tr_en_parent_id' => 4527,
        ), array('en_child'), 0);

        foreach($config_ens as $en){

            //Now fetch all its children:
            $children = $this->Db_model->tr_fetch(array(
                'tr_status >=' => 2,
                'en_status >=' => 2,
                'tr_en_parent_id' => $en['tr_en_child_id'],
            ), array('en_child'), 0, 0, array('en_id' => 'ASC'));


            $child_ids = array();
            foreach($children as $child){
                array_push($child_ids , $child['en_id']);
            }

            echo '<br />//'.$en['en_name'].':<br />';
            echo '$config[\'en_ids_'.$en['tr_en_child_id'].'\'] = array('.join(', ',$child_ids).');<br />';
            echo '$config[\'en_all_'.$en['tr_en_child_id'].'\'] = array(<br />';
            foreach($children as $child){

                echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['en_id'].' => array(<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'en_icon\' => \''.htmlentities($child['en_icon']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'en_name\' => \''.$child['en_name'].'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'tr_content\' => \''.str_replace('\'','\\\'',$child['tr_content']).'\',<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

            }
            echo ');<br />';
        }
    }


    function show_missing_us()
    {
        $q = $this->db->query("SELECT DISTINCT(tr_en_child_id) as p_id FROM tb_entity_links ur WHERE NOT EXISTS (
   SELECT 1
   FROM   tb_entities u
   WHERE  en_id = tr_en_child_id
   );");

        $results = $q->result_array();

        echo_json($results);
    }

    function intent_sync($in_id = 7240, $update_c_table = 1)
    {
        //Cron Settings: 31 * * * *
        //Syncs intents with latest caching data:
        $sync = $this->Db_model->in_recursive_fetch($in_id, true, $update_c_table);
        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            //Now redirect;
            header('Location: ' . $_GET['redirect']);
        } else {
            //Show json:
            echo_json($sync);
        }
    }


    //I cannot update algolia from my local server so if is_dev() is true I will call mench.com/cron/algolia_sync to sync my local change using a live end-point:
    function algolia_sync($obj, $obj_id = 0)
    {
        echo_json($this->Db_model->algolia_sync($obj, $obj_id));
    }


    function list_duplicate_ins()
    {

        $q = $this->db->query('select in1.* from table_intents in1 where (select count(*) from table_intents in2 where in2.in_outcome = in1.in_outcome) > 1 ORDER BY in1.in_outcome ASC');
        $duplicates = $q->result_array();


        $prev_title = null;
        foreach ($duplicates as $in) {
            if ($prev_title != $in['in_outcome']) {
                echo '<hr />';
                $prev_title = $in['in_outcome'];
            }

            echo '<a href="/intents/' . $in['in_id'] . '">#' . $in['in_id'] . '</a> ' . $in['in_outcome'] . '<br />';
        }
    }

    function list_duplicate_ens()
    {

        $q = $this->db->query('select en1.* from tb_entities en1 where (select count(*) from tb_entities en2 where en2.en_name = en1.en_name) > 1 ORDER BY en1.en_name ASC');
        $duplicates = $q->result_array();

        $prev_title = null;
        foreach ($duplicates as $u) {
            if ($prev_title != $u['en_name']) {
                echo '<hr />';
                $prev_title = $u['en_name'];
            }

            echo '<a href="/entities/' . $u['en_id'] . '">#' . $u['en_id'] . '</a> ' . $u['en_name'] . '<br />';
        }
    }


    function e_score_recursive($u = array())
    {

        //Updates en_trust_score based on number/value of connections to other intents/entities
        //Cron Settings: 2 * * * 30

        //Define weights:
        $score_weights = array(
            'u__childrens' => 0, //Child entities are just containers, no score on the link

            'tr_en_child_id' => 1, //Engagement initiator
            'tr_en_credit_id' => 1, //Engagement recipient

            'x_parent_en_id' => 5, //URL Creator
            'x_en_id' => 8, //URL Referenced to them

            'tr_en_parent_id' => 13, //Subscriptions
        );

        //Fetch child entities:
        $entities = $this->Old_model->ur_children_fetch(array(
            'tr_en_parent_id' => (count($u) > 0 ? $u['en_id'] : $this->config->item('primary_en_id')),
            'tr_status >=' => 0, //Pending or Active
            'en_status >=' => 0, //Pending or Active
        ));

        //Recursively loops through child entities:
        $score = 0;
        foreach ($entities as $$en) {
            //Addup all child sores:
            $score += $this->e_score_recursive($$en);
        }

        //Anything to update?
        if (count($u) > 0) {

            //Update this row:
            $score += count($entities) * $score_weights['u__childrens'];

            $score += count($this->Db_model->tr_fetch(array(
                    'tr_en_child_id' => $u['en_id'],
                ), array(), 5000)) * $score_weights['tr_en_child_id'];
            $score += count($this->Db_model->tr_fetch(array(
                    'tr_en_credit_id' => $u['en_id'],
                ), array(), 5000)) * $score_weights['tr_en_credit_id'];

            $score += count($this->Old_model->x_fetch(array(
                    'x_status >' => -2,
                    'x_en_id' => $u['en_id'],
                ))) * $score_weights['x_en_id'];
            $score += count($this->Old_model->x_fetch(array(
                    'x_status >' => -2,
                    'x_parent_en_id' => $u['en_id'],
                ))) * $score_weights['x_parent_en_id'];

            $score += count($this->Db_model->w_fetch(array(
                    'tr_en_parent_id' => $u['en_id'],
                ))) * $score_weights['tr_en_parent_id'];

            //Update the score:
            $this->Db_model->en_update($u['en_id'], array(
                'en_trust_score' => $score,
            ));

            //return the score:
            return $score;

        }
    }


    function message_drip()
    {

        exit; //Logic needs updating

        //Cron Settings: */5 * * * *

        //Fetch pending drips
        $e_pending = $this->Db_model->tr_fetch(array(
            'tr_status' => 0, //Pending work
            'tr_en_type_id' => 4281, //Scheduled Drip
            'tr_timestamp <=' => date("Y-m-d H:i:s"), //Message is due
            //Some standard checks to make sure, these should all be true:
            'tr_en_child_id >' => 0,
            'tr_in_child_id >' => 0,
        ), array(), 200);


        //Lock item so other Cron jobs don't pick this up:
        $this->Db_model->tr_status_processing($e_pending);


        $drip_sent = 0;
        foreach ($e_pending as $tr_content) {

            //Fetch user data:
            $trs = $this->Db_model->w_fetch(array());

            if (count($trs) > 0) {

                //Prepare variables:
                $json_data = unserialize($tr_content['tr_metadata']);

                //Send this message:
                $this->Chat_model->send_message(array(
                    array_merge($json_data['i'], array(
                        'tr_en_child_id' => $trs[0]['en_id'],
                        'tr_in_child_id' => $json_data['i']['tr_in_child_id'],
                    )),
                ));

                //Update Engagement:
                $this->Db_model->tr_update($tr_content['tr_id'], array(
                    'tr_status' => 2, //Publish
                ));

                //Increase counter:
                $drip_sent++;
            }
        }

        //Echo message for cron job:
        echo $drip_sent . ' Drip messages sent';

    }

    function save_profile_pic()
    {


        $max_per_batch = 20; //Max number of scans per run

        $e_pending = $this->Db_model->tr_fetch(array(
            'tr_status' => 0, //Pending
            'tr_en_type_id' => 4299, //Save media file to Mench cloud
        ), array(), $max_per_batch);


        //Lock item so other Cron jobs don't pick this up:
        $this->Db_model->tr_status_processing($e_pending);


        $counter = 0;
        foreach ($e_pending as $u) {

            //Check URL and validate:
            $error_message = null;
            $curl = curl_html($u['tr_content'], true);

            if (!$curl) {
                $error_message = 'Invalid URL (start with http:// or https://)';
            } elseif ($curl['tr_en_type_id'] != 4260) {
                $error_message = 'URL [Type ' . $curl['tr_en_type_id'] . '] is not a valid image URL';
            }

            if (!$error_message) {

                //Save the file to S3
                $new_file_url = save_file($u['tr_content'], $u);

                if (!$new_file_url) {
                    $error_message = 'Failed to upload the file to Mench CDN';
                }

                //Check to make sure this is not a Generic FB URL:
                //TODO This function needs updating as its unable to fetch all generic/blank user icons that it sometimes sends us...
                foreach (array(
                             'ecd274930db69ba4b2d9137949026300',
                             '5bf2d884209d168608b02f3d0850210d',
                             'b3575aa3d0a67fb7d7a076198b442b93',
                             'e35cf96f814f6509d8a202efbda18d3c',
                             '5d2524cb2bdd09422832fa2d25399049',
                             '164c8275278f05c770418258313fb4f4',
                             '',
                         ) as $generic_url) {
                    if (substr_count($new_file_url, $generic_url) > 0) {
                        //This is the hashkey for the Facebook Generic User icon:
                        $error_message = 'This is the user generic icon on Facebook';
                        break;
                    }
                }

                if (!$error_message) {

                    //Replace cover photo only if this user has no cover photo set:
                    if (strlen($u['en_icon'])<1) {

                        //Update Cover ID:
                        $this->Db_model->en_update($u['en_id'], array(
                            'en_icon' => '<img class="profile-icon" src="' . $new_file_url . '" />',
                        ), true);

                    }
                }
            }

            //Update engagement:
            $this->Db_model->tr_update($u['tr_id'], array(
                'tr_status' => 2, //Publish
            ));

        }

        echo_json($e_pending);
    }

    function message_file_save()
    {

        //Cron Settings: * * * * *

        /*
         * This cron job looks for all engagements with Facebook attachments
         * that are pending upload (i.e. tr_status=0) and uploads their
         * attachments to amazon S3 and then changes status to Published
         *
         */

        $max_per_batch = 10; //Max number of scans per run

        $e_pending = $this->Db_model->tr_fetch(array(
            'tr_status' => 0, //Pending file upload to S3
            'tr_en_type_id IN (4277,4280)' => null, //Sent/Received messages
        ), array(), $max_per_batch);


        //Lock item so other Cron jobs don't pick this up:
        $this->Db_model->tr_status_processing($e_pending);


        $counter = 0;
        foreach ($e_pending as $ep) {

            //Prepare variables:
            $json_data = unserialize($ep['tr_metadata']);

            //Loop through entries:
            if (is_array($json_data) && isset($json_data['entry']) && count($json_data['entry']) > 0) {
                foreach ($json_data['entry'] as $entry) {
                    //loop though the messages:
                    foreach ($entry['messaging'] as $im) {
                        //This should only be a message
                        if (isset($im['message'])) {
                            //This should be here
                            if (isset($im['message']['attachments'])) {
                                //We should have attachments:
                                foreach ($im['message']['attachments'] as $att) {
                                    //This one too! It should be one of these:
                                    if (in_array($att['type'], array('image', 'audio', 'video', 'file'))) {

                                        //Store to local DB:
                                        $new_file_url = save_file($att['payload']['url'], $json_data);

                                        //Update engagement data:
                                        $this->Db_model->tr_update($ep['tr_id'], array(
                                            'tr_content' => $new_file_url,
                                            'tr_en_type_id' => detect_tr_en_type_id($new_file_url),
                                            'tr_status' => 2, //Publish
                                        ));

                                        //Increase counter:
                                        $counter++;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                //This should not happen, report:
                $this->Db_model->tr_create(array(
                    'tr_content' => 'cron/bot_save_files() fetched tr_metadata() that was missing its [entry] value',
                    'tr_metadata' => $json_data,
                    'tr_en_type_id' => 4246, //System Error
                ));
            }

            if ($counter >= $max_per_batch) {
                break; //done for now
            }
        }
        //Echo message for cron job:
        echo $counter . ' Incoming Messenger file' . ($counter == 1 ? '' : 's') . ' saved to Mench cloud.';
    }

    function message_fb_sync_attachments()
    {

        exit; //Logic needs updating

        //Cron Settings: * * * * *

        /*
         * This cron job looks for all requests to sync Message attachments
         * with Facebook, gets them done and marks the engagement as done
         *
         */

        $success_count = 0; //Track success
        $max_per_batch = 5; //Max number of syncs per cron run
        $tr_metadata = array();
        $x_types = echo_status('x_type', null);

        $pending_urls = $this->Old_model->x_fetch(array(
            'x_type >=' => 2,
            'x_type <=' => 5, //These are the file URLs that need syncing
            'x_fb_att_id' => 0, //Pending Facebook Sync
        ), array(), array(), $max_per_batch);


        if (count($pending_urls) > 0) {
            foreach ($pending_urls as $x) {

                $payload = array(
                    'message' => array(
                        'attachment' => array(
                            'type' => $x_types[$x['x_type']]['s_fb_key'],
                            'payload' => array(
                                'is_reusable' => true,
                                'url' => $x['x_url'],
                            ),
                        ),
                    )
                );

                //Attempt to save this:
                $result = $this->Chat_model->fb_graph('POST', '/me/message_attachments', $payload);
                $db_result = false;

                if ($result['status'] && isset($result['tr_metadata']['result']['attachment_id'])) {
                    //Save attachment to DB:
                    $db_result = $this->Db_model->x_update($x['x_id'], array(
                        'x_fb_att_id' => $result['tr_metadata']['result']['attachment_id'],
                    ));
                }

                //Did it go well?
                if ($db_result > 0) {
                    $success_count++;
                } else {

                    //Log error:
                    $this->Db_model->tr_create(array(
                        'tr_content' => 'message_fb_sync_attachments() Failed to sync attachment using Facebook API',
                        'tr_metadata' => array(
                            'payload' => $payload,
                            'result' => $result,
                        ),
                        'tr_en_type_id' => 4246, //Platform Error
                    ));

                    //Disable future attempts:
                    $this->Db_model->x_update($x['x_id'], array(
                        'x_fb_att_id' => -1, //No more checks on this guy
                    ));
                }


                //Save stats either way:
                array_push($tr_metadata, array(
                    'payload' => $payload,
                    'fb_result' => $result,
                ));

            }
        }

        //Echo message for cron job:
        echo_json(array(
            'status' => ($success_count == count($pending_urls) && $success_count > 0 ? 1 : 0),
            'message' => $success_count . '/' . count($pending_urls) . ' Message' . echo__s(count($pending_urls)) . ' successfully synced their attachment with Facebook',
            'tr_metadata' => $tr_metadata,
        ));

    }


    function fix_people_missing_parent()
    {
        //TODO Run on more time later, should return nothing... Then delete this...
        $fetch_us = $this->Db_model->en_fetch(array(
            'u_fb_psid >' => 0,
        ));
        foreach ($fetch_us as $u) {
            if (!filter_array($u['en__parents'], 'en_id', 1278)) {
                //Add parent:
                echo '<a href="/entities/' . $u['en_id'] . '">' . $u['en_name'] . '</a><br />';
                $ur1 = $this->Db_model->tr_create(array(
                    'tr_en_child_id' => $u['en_id'],
                    'tr_en_parent_id' => 1278,
                ));
            }
        }


    }

    function master_reminder_complete_task()
    {

        exit; //Needs optimization

        //Will ask the master why they are stuck and try to get them to engage with the material
        //TODO implement social features to maybe connect to other masters at the same level
        //The primary function that would pro-actively communicate the subscription to the user
        //If $tr_id is provided it would step forward a specific subscription
        //If both $tr_id and $en_id are present, it would auto register the user in an idle subscription if they are not part of it yet, and if they are, it would step them forward.

        $bot_settings = array(
            'max_per_run' => 10, //How many subscriptions to server per run (Might include duplicate tr_en_parent_id's that will be excluded)
            'reminder_frequency_min' => 1440, //Every 24 hours
        );

        //Run even minute by the cron job and determines which users to talk to...
        //Fetch all active subscriptions:
        $user_ids_served = array(); //We use this to ensure we're only service one subscription per user
        $active_ws = $this->Db_model->w_fetch(array(
            'tr_status' => 1,
            'en_status >=' => 0,
            'in_status >=' => 2,
            'w_last_heard >=' => date("Y-m-d H:i:s", (time() + ($bot_settings['reminder_frequency_min'] * 60))),
        ), array('in', 'en'), array(
            'w_last_heard' => 'ASC', //Fetch users who have not been served the longest, so we can pay attention to them...
        ), $bot_settings['max_per_run']);

        foreach ($active_ws as $w) {

            if (in_array(intval($w['en_id']), $user_ids_served)) {
                //Skip this as we do not want to handle two subscriptions from the same user:
                continue;
            }

            //Add this user to the queue:
            array_push($user_ids_served, intval($w['en_id']));

            //See where this user is in their subscription:
            $trs_next = $this->Db_model->k_next_fetch($w['tr_id']);

            if (!$trs_next) {
                //Should not happen, bug already reported:
                return false;
            }

            //Update the serving timestamp:
            $this->Db_model->w_update($w['tr_id'], array(
                'w_last_heard' => date("Y-m-d H:i:s"),
            ));


            //Give them next step again:
            $this->Chat_model->k_next_fetch($w['tr_id']);

            //$trs_next[0]['in_outcome']
        }


        exit;

        //Cron Settings: 45 * * * *
        //Send reminders to masters to complete their intent:

        $trs = $this->Db_model->w_fetch(array());

        //Define the logic of these reminders
        $reminder_index = array(
            array(
                'time_elapsed' => 0.90,
                'progress_below' => 0.99,
                'reminder_in_id' => 3139,
            ),
            array(
                'time_elapsed' => 0.75,
                'progress_below' => 0.50,
                'reminder_in_id' => 3138,
            ),
            array(
                'time_elapsed' => 0.50,
                'progress_below' => 0.25,
                'reminder_in_id' => 3137,
            ),
            array(
                'time_elapsed' => 0.20,
                'progress_below' => 0.10,
                'reminder_in_id' => 3136,
            ),
            array(
                'time_elapsed' => 0.05,
                'progress_below' => 0.01,
                'reminder_in_id' => 3358,
            ),
        );

        $stats = array();
        foreach ($trs as $subscription) {

            //See what % of the class time has elapsed?
            //TODO calculate $elapsed_class_percentage

            foreach ($reminder_index as $logic) {
                if ($elapsed_class_percentage >= $logic['time_elapsed']) {

                    if ($subscription['w__progress'] < $logic['progress_below']) {

                        //See if we have reminded them already about this:
                        $reminders_sent = $this->Db_model->tr_fetch(array(
                            'tr_en_type_id IN (4280,4276)' => null, //Email or Message sent
                            'tr_en_child_id' => $subscription['en_id'],
                            'tr_in_child_id' => $logic['reminder_in_id'],
                        ));

                        if (count($reminders_sent) == 0) {

                            //Nope, send this message out:
                            $this->Chat_model->compose_messages(array(
                                'tr_en_credit_id' => 0, //System
                                'tr_en_child_id' => $subscription['en_id'],
                                'tr_in_child_id' => $logic['reminder_in_id'],
                            ));

                            //Show in stats:
                            array_push($stats, $subscription['en_name'] . ' done ' . round($subscription['w__progress'] * 100) . '% (less than target ' . round($logic['progress_below'] * 100) . '%) where class is ' . round($elapsed_class_percentage * 100) . '% complete and got reminded via in_id ' . $logic['reminder_in_id']);
                        }
                    }

                    //Do not go further down the reminder types:
                    break;
                }
            }
        }

        echo_json($stats);
    }

}