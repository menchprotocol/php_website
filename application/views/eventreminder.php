<?php

//Event Reminder App running once an hour to dispatch pending reminders
if (isset($_GET['chainid']) && isset($_GET['handlelogin']) && isset($_GET['hash']) && isset($_GET['time'])) {

    //This is a request to cancel, do so and redirect:
    if (view_hash($_GET['time'] . $_GET['handlelogin']) == $_GET['hash']) {
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___40986')) . ')' => null, //DISCOVERIES
            'chainid' => $_GET['chainid'],
            'LOWER(handleterm)' => strtolower($_GET['handlelogin']),
        ), array('chainhandlecreator'), 0) as $x) {

            //Show Header:
            foreach ($this->Hashtags->read(array(
                'hashtagid' => $x['chainhashtagoutput'],
            )) as $hashtag_from) {
                echo '<h1><a href="' . view_memory(42903, 33286) . $hashtag_from['hashtagterm'] . '">' . view_hashtag_title($hashtag_from, true) . '</a></h1>';
            }

            if (isset($_GET['submit'])) {

                //They have confirmed, remove:
                $this->Chains->update($x['chainid'], array(
                    'chainhandletype' => 42333, //RSVP No
                    'chainhandlecreator' => $x['handleid'],
                ));
                //TODO Copy th is elsewhere

                //Notify and give option to go to starting point:
                foreach ($this->Hashtags->read(array(
                    'hashtagid' => $x['chainhashtaginput'],
                )) as $hashtag_go) {
                    echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Successfully cancelled event. You can continue to <a href="' . view_memory(42903, 33286) . $hashtag_go['hashtagterm'] . '">' . view_hashtag_title($hashtag_go, true) . '</a>.</div>';
                }

            } else {

                //Inform the user and give them option to confirm removal:
                echo '<p>You can submit this form if you wish to cancel your attendance:</p>';
                echo '<form action="" method="GET">';
                echo '<textarea class="form-control border no-padding" name="chainvalue" data-lpignore="true" placeholder="Optional Note">' . (isset($_POST['list_emails']) ? $_POST['list_emails'] : '') . '</textarea><br /><br />';
                echo '<input type="submit" name="submit" class="btn" value="Cancel Event Attendance" />';
                echo '</form>';

            }

            //We're done:
            break;

        }
    }

} elseif (!$handle_http_request || isset($_GET['cron'])) {

    $handles___42216 = $this->config->item('handles___42216'); //Event Reminder

    //Track successful hashtag dispatches:
    $hashtag_scanned = array();

    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___42252')) . ')' => null, //Plain Chain
        'chainhandleinput IN (' . join(',', $this->config->item('handleids___42216')) . ')' => null, //Event Reminder
        'hashtagtype' => 30874, //Events
    ), array('chainhashtagoutput'), 0) as $i) {

        //Make sure not handled this hashtag with a different reminder:
        if (!in_array($i['hashtagid'], $hashtag_scanned)) {

            $remind_status = 0; //  0=Pending  1=Success  -1=Failure

            //Fetch Start time for this hashtag:
            $time_starts = 0;
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $i['hashtagid'],
                'chainhandleinput' => 26556, //Time Starts
            )) as $time) {
                $time_starts = strtotime($time['chainvalue']);
                break;
            }

            //Must be a future event:
            if ($time_starts > time()) {

                //Let's see if this future event is less than X seconds away:
                if (($time_starts - intval($handles___42216[$i['chainhandleinput']]['m__message'])) < time()) {

                    //End time?
                    $time_ends = $this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                        'chainhashtagoutput' => $i['hashtagid'],
                        'chainhandleinput' => 26557, //Time Ends
                    ), array(), 1);

                    array_push($hashtag_scanned, $i['hashtagid']);
                    $title = view_hashtag_title($i, true);
                    $total_sent = 0;

                    //The time is here! Send event reminders to those who successfully hashtag_discovered this:
                    foreach ($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___40986')) . ')' => null, //DISCOVERIES
                        'chainhashtaginput' => $i['hashtagid'],
                    ), array('chainhandlecreator'), 0) as $x) {

                        $user_website = user_website($x['handleid']);
                        $subject = 'Reminder: ' . $title . ' Starts in ' . view_time_difference($time_starts);
                        $html_message = 'This is a friendly reminder about an upcoming event you signed up for:' .
                            "\n" .
                            "\n" . $i['hashtagtext'] .
                            "\n" . 'Start Time: ' . date("D M j G:i:s T", $time_starts) .
                            (count($time_ends) && strtotime($time_ends[0]['chainvalue']) ? "\n" . 'End Time: ' . date("D M j G:i:s T", strtotime($time_ends[0]['chainvalue'])) : '') .
                            "\n" . 'https://' . get_domain('m__message', $x['handleid'], $user_website) . view_memory(42903, 33286) . $i['hashtagterm'] .
                            "\n" .
                            "\n" . 'If you cannot attend this event please inform us by cancelling here:' .
                            "\n" . 'https://' . get_domain('m__message', $x['handleid'], $user_website) . view_app_chain(42216) . '?chainid=' . $x['chainid'] . '&handlelogin=' . $x['handleterm'] . '&time=' . time() . '&hash=' . view_hash(time() . $x['handleterm']);

                        //Send message:
                        $message = $this->Chains->message($x['handleid'], $subject, $html_message, array(
                            'chainhashtaginput' => $i['hashtagid'],
                        ), $i['hashtagid'], $user_website);

                        $total_sent += ($message['status'] ? 1 : 0);

                    }

                    $remind_status = ($total_sent > 0 ? 1 : -1);

                } else {
                    //Reminder time has not yet come, do nothing and wait until it arrives
                }

            } else {
                //Start time has already passed or missing, we cannot send reminders!
                $remind_status = -1;
            }

        } else {
            //Already scanned this hashtag
            $remind_status = -1;
        }


        if ($remind_status < 0 || $remind_status > 0) {
            //We are done with this reminder request:
            $this->Chains->update($i['chainid'], array(
                'chainhandletype' => ($remind_status > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
                'chainhandlecreator' => $handle_session['handleid'],
            ));
        }


    }

    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainkey >' => time(), //Future event
        'chainhandleinput' => 26556, //Time Starts
        'hashtagtype' => 30874, //Events
    ), array('chainhashtagoutput'), 0) as $i) {

        //Determine if it's time to send this message:
        $time_starts = 0;
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandleinput' => 26556, //Time Starts
        )) as $time) {
            $time_starts = strtotime($time['chainvalue']);
            break;
        }

        if ($time_starts > 0 && $time_starts > time()) {
            //Still not time, go next:
            continue;
        }

        //Does it have an end time?
        $end_sending = 0;
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandleinput' => 26557, //Time Ends
        )) as $time) {
            $end_sending = strtotime($time['chainvalue']);
            break;
        }


        $children = $this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
            'chainhashtaginput' => $i['hashtagid'],
        ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC'));


        //Now let's see who will receive this:
        $total_sent = 0;
        $hashtag_settings = hashtag_settings($i['hashtagterm']);
        $subject_line = view_hashtag_title($i, true);

        foreach ($hashtag_settings['query_string_filtered'] as $x) {

            if (count($this->Chains->read(array(
                'chainhashtaginput' => $i['hashtagid'],
                'chainhandlecreator' => $x['handleid'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            )))) {
                //Skip since they already hashtag_discovered this hashtag:
                continue;
            }

            $content_message = view_hashtag_value($i, $x['handleid']);
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', str_replace('  ',' ',$content_message));
            }


            //Append children as options:
            $html_message = '';
            foreach ($children as $down_or) {

                $discoveries = $this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                    'chainhandlecreator' => $x['handleid'],
                    'chainhashtaginput' => $down_or['hashtagid'],
                ));
                //Has this user hashtag_discovered this hashtag or no?
                $html_message .= view_hashtag_title($down_or, true) . ":\n";
                $html_message .= 'https://' . get_domain('m__message', $x['handleid'], $i['chainhandledomain']) . view_memory(42903, 33286) . $down_or['hashtagterm'] . (!count($discoveries) ? '?handlelogin=' . $x['handleterm'] . '&time=' . time() . '&hash=' . view_hash(time() . $x['handleterm']) : '') . "\n\n";

            }

            $message = $this->Chains->message($x['handleid'], $subject_line, $content_message . "\n" . trim($html_message), array(
                'chainhashtaginput' => $i['hashtagid'],
            ), $i['hashtagid'], $i['chainhandledomain'], true);
            $total_sent += ($message['status'] ? 1 : 0);


        }

        //Mark this as complete?
        if (!$end_sending || $end_sending < time()) {
            //Ready to be done:
            $this->Chains->update($i['chainid'], array(
                'chainhandletype' => ($total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
                'chainhandlecreator' => $handle_session['handleid'],
            ));
        }

    }

} else {

    echo 'Nothing to see here';

}