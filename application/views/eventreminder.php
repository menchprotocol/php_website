<?php

//Event Reminder App running once an hour to dispatch pending reminders
if (isset($_GET['chainid']) && isset($_GET['userlogin']) && isset($_GET['hash']) && isset($_GET['time'])) {

    //This is a request to cancel, do so and redirect:
    if (view_hash($_GET['time'] . $_GET['userlogin']) == $_GET['hash']) {
        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___40986')) . ')' => null, //DISCOVERIES
            'chainid' => $_GET['chainid'],
            'LOWER(userhandle)' => strtolower($_GET['userlogin']),
        ), array('chainusercreator'), 0) as $x) {

            //Show Header:
            foreach ($this->Posts->read(array(
                'postid' => $x['chainpostoutput'],
            )) as $post_from) {
                echo '<h1><a href="' . view_memory(42903, 33286) . $post_from['posthashtag'] . '">' . view_post_title($post_from, true) . '</a></h1>';
            }

            if (isset($_GET['submit'])) {

                //They have confirmed, remove:
                $this->Chains->update($x['chainid'], array(
                    'chainusertype' => 42333, //RSVP No
                    'chainusercreator' => $x['userid'],
                ));
                //TODO Copy th is elsewhere

                //Notify and give option to go to starting point:
                foreach ($this->Posts->read(array(
                    'postid' => $x['chainpostinput'],
                )) as $post_go) {
                    echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Successfully cancelled event. You can continue to <a href="' . view_memory(42903, 33286) . $post_go['posthashtag'] . '">' . view_post_title($post_go, true) . '</a>.</div>';
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

} elseif (!$user_http_request || isset($_GET['cron'])) {

    $users___42216 = $this->config->item('users___42216'); //Event Reminder

    //Track successful post dispatches:
    $post_scanned = array();

    foreach ($this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___42252')) . ')' => null, //Plain Chain
        'chainuserinput IN (' . join(',', $this->config->item('userids___42216')) . ')' => null, //Event Reminder
    ), array('chainpostinput'), 0) as $i) {

        //Make sure not userd this post with a different reminder:
        if (!in_array($i['postid'], $post_scanned)) {

            $remind_status = 0; //  0=Pending  1=Success  -1=Failure

            //Fetch Start time for this post:
            $time_starts = 0;
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $i['postid'],
                'chainuserinput' => 26556, //Time Starts
            )) as $time) {
                $time_starts = strtotime($time['chainvalue']);
                break;
            }

            //Must be a future event:
            if ($time_starts > time()) {

                //Let's see if this future event is less than X seconds away:
                if (($time_starts - intval($users___42216[$i['chainuserinput']]['m__message'])) < time()) {

                    //End time?
                    $time_ends = $this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostinput' => $i['postid'],
                        'chainuserinput' => 26557, //Time Ends
                    ), array(), 1);

                    array_push($post_scanned, $i['postid']);
                    $title = view_post_title($i, true);
                    $total_sent = 0;

                    //The time is here! Send event reminders to those who successfully post discovered this:
                    foreach ($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___40986')) . ')' => null, //DISCOVERIES
                        'chainpostinput' => $i['postid'],
                    ), array('chainusercreator'), 0) as $x) {

                        $user_website = user_website($x['userid']);
                        $subject = 'Reminder: ' . $title . ' Starts in ' . view_time_difference($time_starts);
                        $html_message = 'This is a friendly reminder about an upcoming event you signed up for:' .
                            "\n" .
                            "\n" . $i['postmessage'] .
                            "\n" . 'Start Time: ' . date("D M j G:i:s T", $time_starts) .
                            (count($time_ends) && strtotime($time_ends[0]['chainvalue']) ? "\n" . 'End Time: ' . date("D M j G:i:s T", strtotime($time_ends[0]['chainvalue'])) : '') .
                            "\n" . 'https://' . get_domain('m__message', $x['userid'], $user_website) . view_memory(42903, 33286) . $i['posthashtag'] .
                            "\n" .
                            "\n" . 'If you cannot attend this event please inform us by cancelling here:' .
                            "\n" . 'https://' . get_domain('m__message', $x['userid'], $user_website) . view_app_chain(42216) . '?chainid=' . $x['chainid'] . '&userlogin=' . $x['userhandle'] . '&time=' . time() . '&hash=' . view_hash(time() . $x['userhandle']);

                        //Send message:
                        $message = $this->Chains->message($x['userid'], $subject, $html_message, array(
                            'chainpostinput' => $i['postid'],
                        ), $i['postid'], $user_website);

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
            //Already scanned this post
            $remind_status = -1;
        }


        if ($remind_status < 0 || $remind_status > 0) {
            //We are done with this reminder request:
            $this->Chains->update($i['chainid'], array(
                'chainusertype' => ($remind_status > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
                'chainusercreator' => $user_session['userid'],
            ));
        }


    }

    foreach ($this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainkey >' => time(), //Future event
        'chainuserinput' => 26556, //Time Starts
    ), array('chainpostinput'), 0) as $i) {

        //Determine if it's time to send this message:
        $time_starts = 0;
        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostinput' => $i['postid'],
            'chainuserinput' => 26556, //Time Starts
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
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostinput' => $i['postid'],
            'chainuserinput' => 26557, //Time Ends
        )) as $time) {
            $end_sending = strtotime($time['chainvalue']);
            break;
        }


        $children = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
            'chainpostinput' => $i['postid'],
        ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC'));


        //Now let's see who will receive this:
        $total_sent = 0;
        $post_settings = post_settings($i['posthashtag']);
        $subject_line = view_post_title($i, true);

        foreach ($post_settings['query_string_filtered'] as $x) {

            if (count($this->Chains->read(array(
                'chainpostinput' => $i['postid'],
                'chainusercreator' => $x['userid'],
                'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            )))) {
                //Skip since they already post discovered this post:
                continue;
            }

            $content_message = view_post_value($i, $x['userid']);
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', str_replace('  ',' ',$content_message));
            }


            //Append children as options:
            $html_message = '';
            foreach ($children as $down_or) {

                $discoveries = $this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                    'chainusercreator' => $x['userid'],
                    'chainpostinput' => $down_or['postid'],
                ));
                //Has this user post discovered this post or no?
                $html_message .= view_post_title($down_or, true) . ":\n";
                $html_message .= 'https://' . get_domain('m__message', $x['userid'], $i['chainuserdomain']) . view_memory(42903, 33286) . $down_or['posthashtag'] . (!count($discoveries) ? '?userlogin=' . $x['userhandle'] . '&time=' . time() . '&hash=' . view_hash(time() . $x['userhandle']) : '') . "\n\n";

            }

            $message = $this->Chains->message($x['userid'], $subject_line, $content_message . "\n" . trim($html_message), array(
                'chainpostinput' => $i['postid'],
            ), $i['postid'], $i['chainuserdomain'], true);
            $total_sent += ($message['status'] ? 1 : 0);


        }

        //Mark this as complete?
        if (!$end_sending || $end_sending < time()) {
            //Ready to be done:
            $this->Chains->update($i['chainid'], array(
                'chainusertype' => ($total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
                'chainusercreator' => $user_session['userid'],
            ));
        }

    }

} else {

    echo 'Nothing to see here';

}