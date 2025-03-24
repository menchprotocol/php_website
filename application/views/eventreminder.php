<?php

//Event Reminder App running once an hour to dispatch pending reminders
if(isset($_GET['link_id']) && isset($_GET['e__handle']) && isset($_GET['e__hash']) && isset($_GET['e__time'])){

    //This is a request to cancel, do so and redirect:
    if(view__hash($_GET['e__time'].$_GET['e__handle'])==$_GET['e__hash']){
        foreach($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___40986')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'link_id' => $_GET['link_id'],
            'LOWER(e__handle)' => strtolower($_GET['e__handle']),
        ), array('link_player'), 0) as $x){

            //Show Header:
            foreach($this->Idea_cache->fetch(array(
                'i__id' => $x['link_right'],
            )) as $i_from){
                echo '<h1><a href="'.view__memory(42903,33286).$i_from['i__hashtag'].'"><u>' . view__i_title($i_from, true) . '</u></a></h1>';
            }

            if(isset($_GET['submit'])){

                //They have confirmed, remove:
                $this->Mench_ledger->update($x['link_id'], array(
                    'link_type' => 42333, //RSVP No
                ), $x['e__id']);
                //TODO Copy th is elsewhere

                //Notify and give option to go to starting point:
                foreach($this->Idea_cache->fetch(array(
                    'i__id' => $x['link_left'],
                )) as $i_go){
                    echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Successfully cancelled event. You can continue to <a href="'.view__memory(42903,33286).$i_go['i__hashtag'].'">'.view__i_title($i_go, true).'</a>.</div>';
                }

            } else {

                //Inform the user and give them option to confirm removal:
                echo '<p>You can submit this form if you wish to cancel your attendance:</p>';
                echo '<form action="" method="GET">';
                echo '<textarea class="form-control border no-padding" name="link_text" data-lpignore="true" placeholder="Optional Note">'.( isset($_POST['list_emails']) ? $_POST['list_emails'] : '' ).'</textarea><br /><br />';
                echo '<input type="submit" name="submit" class="btn" value="Cancel Event Attendance" />';
                echo '</form>';

            }

            //We're done:
            break;

        }
    }

} elseif (!$player_http_request || isset($_GET['cron'])) {

    $e___42216 = $this->config->item('e___42216'); //Event Reminder

    //Track successful idea dispatches:
    $i_scanned = array();

    foreach ($this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
        'link_up IN (' . join(',', $this->config->item('n___42216')) . ')' => null, //Event Reminder
        'i__type' => 30874, //Events
    ), array('link_right'), 0) as $i) {

        //Make sure not handled this idea with a different reminder:
        if(!in_array($i['i__id'], $i_scanned)){

            $remind_status = 0; //  0=Pending  1=Success  -1=Failure

            //Fetch Start time for this idea:
            $time_starts = 0;
            foreach($this->Mench_ledger->fetch(array(
                'link_void' => 0, //Not Void
                'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
                'link_right' => $i['i__id'],
                'link_up' => 26556, //Time Starts
            )) as $time){
                $time_starts = strtotime($time['link_text']);
                break;
            }

            //Must be a future event:
            if($time_starts>time()){

                //Let's see if this future event is less than X seconds away:
                if(($time_starts - intval($e___42216[$i['link_up']]['m__message'])) < time()){

                    //End time?
                    $time_ends = $this->Mench_ledger->fetch(array(
                        'link_void' => 0, //Not Void
                        'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
                        'link_right' => $i['i__id'],
                        'link_up' => 26557, //Time Ends
                    ), array(), 1);

                    //Navigation?
                    $must_follow = array();
                    foreach($this->Mench_ledger->fetch(array(
                        'link_void' => 0, //Not Void
                        'link_type' => 32235, //Navigation
                        'link_right' => $i['i__id'],
                    )) as $follow){
                        array_push($must_follow, $follow['link_up']);
                    }

                    array_push($i_scanned, $i['i__id']);
                    $title = view__i_title($i, true);
                    $total_sent = 0;

                    //The time is here! Send event reminders to those who successfully discovered this:
                    foreach($this->Mench_ledger->fetch(array(
                        'link_void' => 0, //Not Void
                        'link_type IN (' . join(',', $this->config->item('n___40986')) . ')' => null, //SUCCESSFUL DISCOVERIES
                        'link_left' => $i['i__id'],
                    ), array('link_player'), 0) as $x){

                        //Make sure this member qualified:
                        if(count($must_follow)>0 && count($must_follow)!=count($this->Mench_ledger->fetch(array(
                                'link_down' => $x['e__id'],
                                'link_up IN (' . join(',', $must_follow) . ')' => null,
                                'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'link_void' => 0, //Not Void
                            )))){
                            //User does not have all navigation items, skip for now:
                            continue;
                        }

                        $user_website = user_website($x['e__id']);
                        $subject = 'Reminder: '.$title.' Starts in '.view__time_difference($time_starts);
                        $html_message = 'This is a friendly reminder about an upcoming event you signed up for:'.
                            "\n".
                            "\n".$i['i__message'].
                            "\n".'Start Time: '.date("D M j G:i:s T", $time_starts).
                            ( count($time_ends) && strtotime($time_ends[0]['link_text']) ? "\n".'End Time: '.date("D M j G:i:s T", strtotime($time_ends[0]['link_text'])) : '' ).
                            "\n".'https://'.get_domain('m__message', $x['e__id'], $user_website).view__memory(42903,33286).$i['i__hashtag'].
                            "\n".
                            "\n".'If you cannot attend this event please inform us by cancelling here:'.
                            "\n".'https://'.get_domain('m__message', $x['e__id'], $user_website).view__app_link(42216).'?link_id='.$x['link_id'].'&e__handle='.$x['e__handle'].'&e__time='.time().'&e__hash='.view__hash(eventreminder . phptime() . $x['e__handle']);

                        //Send message:
                        $send_dm = $this->Mench_ledger->send_dm($x['e__id'], $subject, $html_message, array(
                            'link_left' => $i['i__id'],
                        ), $i['i__id'], $user_website);

                        $total_sent += ( $send_dm['status'] ? 1 : 0 );

                    }

                    $remind_status = ( $total_sent>0 ? 1 : -1 );

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


        if($remind_status<0 || $remind_status>0){
            //We are done with this reminder request:
            $this->Mench_ledger->update($i['link_id'], array(
                'link_type' => ($remind_status>0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
            ));
        }



    }

    foreach ($this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'link_number >' => time(), //Future event
        'link_up' => 26556, //Time Starts
        'i__type' => 30874, //Events
    ), array('link_right'), 0) as $i) {

        //Determine if it's time to send this message:
        $time_starts = 0;
        foreach ($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
            'link_right' => $i['i__id'],
            'link_up' => 26556, //Time Starts
        )) as $time) {
            $time_starts = strtotime($time['link_text']);
            break;
        }

        if ($time_starts > 0 && $time_starts > time()) {
            //Still not time, go next:
            continue;
        }

        //Does it have an end time?
        $end_sending = 0;
        foreach ($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
            'link_right' => $i['i__id'],
            'link_up' => 26557, //Time Ends
        )) as $time) {
            $end_sending = strtotime($time['link_text']);
            break;
        }


        $children = $this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
            'link_left' => $i['i__id'],
        ), array('link_right'), 0, 0, array('link_number' => 'ASC'));


        //Now let's see who will receive this:
        $total_sent = 0;
        $list_settings = list_settings($i['i__hashtag']);
        $subject_line = view__i_title($i, true);

        foreach ($list_settings['query_string_filtered'] as $x) {

            if (count($this->Mench_ledger->fetch(array(
                'link_left' => $i['i__id'],
                'link_player' => $x['e__id'],
                'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'link_void' => 0, //Not Void
            )))) {
                //Skip since they already discovered this idea:
                continue;
            }

            $content_message = view__i__links($i, $x['e__id']);
            if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
                //Let's remove the first line since it's used in the title:
                $content_message = delete_all_between('<div class="line first_line">', '</div>', $content_message);
            }


            //Append children as options:
            $html_message = '';
            foreach ($children as $down_or) {

                $discoveries = $this->Mench_ledger->fetch(array(
                    'link_void' => 0, //Not Void
                    'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'link_player' => $x['e__id'],
                    'link_left' => $down_or['i__id'],
                ));
                //Has this user discovered this idea or no?
                $html_message .= view__i_title($down_or, true) . ":\n";
                $html_message .= 'https://' . get_domain('m__message', $x['e__id'], $i['link_domain']) . view__memory(42903,33286) . $down_or['i__hashtag'] . (!count($discoveries) ? '?e__handle=' . $x['e__handle'] . '&e__time='.time().'&e__hash=' . view__hash(eventreminder . phptime() . $x['e__handle']) : '') . "\n\n";

            }

            $send_dm = $this->Mench_ledger->send_dm($x['e__id'], $subject_line, $content_message . "\n" . trim($html_message), array(
                'link_left' => $i['i__id'],
            ), $i['i__id'], $i['link_domain'], true);
            $total_sent += ($send_dm['status'] ? 1 : 0);


        }

        //Mark this as complete?
        if (!$end_sending || $end_sending < time()) {
            //Ready to be done:
            $this->Mench_ledger->update($i['link_id'], array(
                'link_type' => ($total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
            ));
        }

    }

} else {

    echo 'Nothing to see here';

}