<?php

//Event Reminder App running once an hour to dispatch pending reminders
if (isset($_GET['chainid']) && isset($_GET['playerhandle']) && isset($_GET['hash']) && isset($_GET['time'])) {

    //This is a request to cancel, do so and redirect:
    if (view_hash($_GET['time'] . $_GET['playerhandle']) == $_GET['hash']) {
        foreach ($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___40986')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'chainid' => $_GET['chainid'],
            'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
        ), array('chainplayercreator'), 0) as $x) {

            //Show Header:
            foreach ($this->Ideas->read(array(
                'ideaid' => $x['chainidearight'],
            )) as $idea_from) {
                echo '<h1><a href="' . view_memory(42903, 33286) . $idea_from['ideahashtag'] . '">' . view_idea_title($idea_from, true) . '</a></h1>';
            }

            if (isset($_GET['submit'])) {

                //They have confirmed, remove:
                $this->Chains->update($x['chainid'], array(
                    'chainplayertype' => 42333, //RSVP No
                    'chainplayercreator' => $x['playerid'],
                ));
                //TODO Copy th is elsewhere

                //Notify and give option to go to starting point:
                foreach ($this->Ideas->read(array(
                    'ideaid' => $x['chainidealeft'],
                )) as $idea_go) {
                    echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Successfully cancelled event. You can continue to <a href="' . view_memory(42903, 33286) . $idea_go['ideahashtag'] . '">' . view_idea_title($idea_go, true) . '</a>.</div>';
                }

            } else {

                //Inform the user and give them option to confirm removal:
                echo '<p>You can submit this form if you wish to cancel your attendance:</p>';
                echo '<form action="" method="GET">';
                echo '<textarea class="form-control border no-padding" name="chaintext" data-lpignore="true" placeholder="Optional Note">' . (isset($_POST['list_emails']) ? $_POST['list_emails'] : '') . '</textarea><br /><br />';
                echo '<input type="submit" name="submit" class="btn" value="Cancel Event Attendance" />';
                echo '</form>';

            }

            //We're done:
            break;

        }
    }

} elseif (!$player_http_request || isset($_GET['cron'])) {

    $players___42216 = $this->config->item('players___42216'); //Event Reminder

    //Track successful idea dispatches:
    $idea_scanned = array();

    foreach ($this->Chains->read(array(
        'chainplayertype IN (' . join(',', $this->config->item('playerids___42252')) . ')' => null, //Plain Chain
        'chainplayerup IN (' . join(',', $this->config->item('playerids___42216')) . ')' => null, //Event Reminder
        'ideatype' => 30874, //Events
    ), array('chainidearight'), 0) as $i) {

        //Make sure not handled this idea with a different reminder:
        if (!in_array($i['ideaid'], $idea_scanned)) {

            $remind_status = 0; //  0=Pending  1=Success  -1=Failure

            //Fetch Start time for this idea:
            $time_starts = 0;
            foreach ($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
                'chainidearight' => $i['ideaid'],
                'chainplayerup' => 26556, //Time Starts
            )) as $time) {
                $time_starts = strtotime($time['chaintext']);
                break;
            }

            //Must be a future event:
            if ($time_starts > time()) {

                //Let's see if this future event is less than X seconds away:
                if (($time_starts - intval($players___42216[$i['chainplayerup']]['m__message'])) < time()) {

                    //End time?
                    $time_ends = $this->Chains->read(array(
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
                        'chainidearight' => $i['ideaid'],
                        'chainplayerup' => 26557, //Time Ends
                    ), array(), 1);

                    //Navigation?
                    $must_follow = array();
                    foreach ($this->Chains->read(array(
                        'chainplayertype' => 32235, //Navigation
                        'chainidearight' => $i['ideaid'],
                    )) as $follow) {
                        array_push($must_follow, $follow['chainplayerup']);
                    }

                    array_push($idea_scanned, $i['ideaid']);
                    $title = view_idea_title($i, true);
                    $total_sent = 0;

                    //The time is here! Send event reminders to those who successfully idea_discovered this:
                    foreach ($this->Chains->read(array(
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___40986')) . ')' => null, //SUCCESSFUL DISCOVERIES
                        'chainidealeft' => $i['ideaid'],
                    ), array('chainplayercreator'), 0) as $x) {

                        //Make sure this member qualified:
                        if (count($must_follow) > 0 && count($must_follow) != count($this->Chains->read(array(
                                'chainplayerdown' => $x['playerid'],
                                'chainplayerup IN (' . join(',', $must_follow) . ')' => null,
                                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                            )))) {
                            //User does not have all navigation items, skip for now:
                            continue;
                        }

                        $user_website = user_website($x['playerid']);
                        $subject = 'Reminder: ' . $title . ' Starts in ' . view_time_difference($time_starts);
                        $html_message = 'This is a friendly reminder about an upcoming event you signed up for:' .
                            "\n" .
                            "\n" . $i['ideatext'] .
                            "\n" . 'Start Time: ' . date("D M j G:i:s T", $time_starts) .
                            (count($time_ends) && strtotime($time_ends[0]['chaintext']) ? "\n" . 'End Time: ' . date("D M j G:i:s T", strtotime($time_ends[0]['chaintext'])) : '') .
                            "\n" . 'https://' . get_domain('m__message', $x['playerid'], $user_website) . view_memory(42903, 33286) . $i['ideahashtag'] .
                            "\n" .
                            "\n" . 'If you cannot attend this event please inform us by cancelling here:' .
                            "\n" . 'https://' . get_domain('m__message', $x['playerid'], $user_website) . view_app_chain(42216) . '?chainid=' . $x['chainid'] . '&playerhandle=' . $x['playerhandle'] . '&time=' . time() . '&hash=' . view_hash(eventreminder . phptime() . $x['playerhandle']);

                        //Send message:
                        $message = $this->Chains->message($x['playerid'], $subject, $html_message, array(
                            'chainidealeft' => $i['ideaid'],
                        ), $i['ideaid'], $user_website);

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
            //Already scanned this idea
            $remind_status = -1;
        }


        if ($remind_status < 0 || $remind_status > 0) {
            //We are done with this reminder request:
            $this->Chains->update($i['chainid'], array(
                'chainplayertype' => ($remind_status > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
                'chainplayercreator' => $player_session['playerid'],
            ));
        }


    }

    foreach ($this->Chains->read(array(
        'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'chainnumber >' => time(), //Future event
        'chainplayerup' => 26556, //Time Starts
        'ideatype' => 30874, //Events
    ), array('chainidearight'), 0) as $i) {

        //Determine if it's time to send this message:
        $time_starts = 0;
        foreach ($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainplayerup' => 26556, //Time Starts
        )) as $time) {
            $time_starts = strtotime($time['chaintext']);
            break;
        }

        if ($time_starts > 0 && $time_starts > time()) {
            //Still not time, go next:
            continue;
        }

        //Does it have an end time?
        $end_sending = 0;
        foreach ($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainplayerup' => 26557, //Time Ends
        )) as $time) {
            $end_sending = strtotime($time['chaintext']);
            break;
        }


        $children = $this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Sequence Down
            'chainidealeft' => $i['ideaid'],
        ), array('chainidearight'), 0, 0, array('chainnumber' => 'ASC'));


        //Now let's see who will receive this:
        $total_sent = 0;
        $idea_settings = idea_settings($i['ideahashtag']);
        $subject_line = view_idea_title($i, true);

        foreach ($idea_settings['query_string_filtered'] as $x) {

            if (count($this->Chains->read(array(
                'chainidealeft' => $i['ideaid'],
                'chainplayercreator' => $x['playerid'],
                'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            )))) {
                //Skip since they already idea_discovered this idea:
                continue;
            }

            $content_message = view_idea_chains($i, $x['playerid']);
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', $content_message);
            }


            //Append children as options:
            $html_message = '';
            foreach ($children as $down_or) {

                $discoveries = $this->Chains->read(array(
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    'chainplayercreator' => $x['playerid'],
                    'chainidealeft' => $down_or['ideaid'],
                ));
                //Has this user idea_discovered this idea or no?
                $html_message .= view_idea_title($down_or, true) . ":\n";
                $html_message .= 'https://' . get_domain('m__message', $x['playerid'], $i['chainplayerdomain']) . view_memory(42903, 33286) . $down_or['ideahashtag'] . (!count($discoveries) ? '?playerhandle=' . $x['playerhandle'] . '&time=' . time() . '&hash=' . view_hash(eventreminder . phptime() . $x['playerhandle']) : '') . "\n\n";

            }

            $message = $this->Chains->message($x['playerid'], $subject_line, $content_message . "\n" . trim($html_message), array(
                'chainidealeft' => $i['ideaid'],
            ), $i['ideaid'], $i['chainplayerdomain'], true);
            $total_sent += ($message['status'] ? 1 : 0);


        }

        //Mark this as complete?
        if (!$end_sending || $end_sending < time()) {
            //Ready to be done:
            $this->Chains->update($i['chainid'], array(
                'chainplayertype' => ($total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
                'chainplayercreator' => $player_session['playerid'],
            ));
        }

    }

} else {

    echo 'Nothing to see here';

}