<?php

//Event Reminder App running once an hour to dispatch pending reminders
if(isset($_GET['x__id']) && isset($_GET['e__handle']) && isset($_GET['e__hash'])){

    //This is a request to cancel, do so and redirect:
    if(view_e__hash($_GET['e__handle'])==$_GET['e__hash']){
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___40986')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'x__id' => $_GET['x__id'],
            'LOWER(e__handle)' => strtolower($_GET['e__handle']),
        ), array('x__creator'), 0) as $x){

            //Show Header:
            foreach($this->I_model->fetch(array(
                'i__id' => $x['x__right'],
            )) as $i_from){
                echo '<h1><a href="/'.$i_from['i__hashtag'].'"><u>' . view_i_title($i_from, true) . '</u></a></h1>';
            }

            if(isset($_GET['submit'])){

                //They have confirmed, remove:
                $this->X_model->update($x['x__id'], array(
                    'x__type' => 42333, //RSVP No
                ), $x['e__id'], 42251 /* Member Skipped Event */);

                //Notify and give option to go to starting point:
                foreach($this->I_model->fetch(array(
                    'i__id' => $x['x__left'],
                )) as $i_go){
                    echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully cancelled event. You can continue to <a href="/'.$i_go['i__hashtag'].'">'.view_i_title($i_go, true).'</a>.</div>';
                }

            } else {

                //Inform the user and give them option to confirm removal:
                echo '<p>You can submit this form if you wish to cancel your attendance:</p>';
                echo '<form action="" method="GET">';
                echo '<textarea class="form-control text-edit border no-padding" name="x__message" data-lpignore="true" placeholder="Optional Note...">'.( isset($_POST['list_emails']) ? $_POST['list_emails'] : '' ).'</textarea><br /><br />';
                echo '<input type="submit" name="submit" class="btn btn-6255" value="Cancel Event Attendance" />';
                echo '</form>';

            }

            //We're done:
            break;

        }
    }

} elseif (!$is_u_request || isset($_GET['cron'])) {

    $e___42216 = $this->config->item('e___42216'); //Event Reminder

    //Track successful idea dispatches:
    $i_scanned = array();

    foreach ($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
        'x__up IN (' . join(',', $this->config->item('n___42216')) . ')' => null, //Event Reminder
        'i__type' => 30874, //Events
        'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ), array('x__right'), 0) as $i) {

        //Make sure not handled this idea with a different reminder:
        if(!in_array($i['i__id'], $i_scanned)){

            $remind_status = 0; //  0=Pending  1=Success  -1=Failure

            //Fetch Start time for this idea:
            $time_starts = 0;
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42256')) . ')' => null, //Authored
                'x__right' => $i['i__id'],
                'x__up' => 26556, //Time Starts
            )) as $time){
                $time_starts = strtotime($time['x__message']);
                break;
            }

            //Must be a future event:
            if($time_starts>time()){

                //Let's see if this future event is less than X seconds away:
                if(($time_starts - intval($e___42216[$i['x__up']]['m__message'])) < time()){

                    //End time?
                    $time_ends = $this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___42256')) . ')' => null, //Authored
                        'x__right' => $i['i__id'],
                        'x__up' => 26557, //Time Ends
                    ), array(), 1);

                    //Navigation?
                    $must_follow = array();
                    foreach($this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type' => 32235, //Navigation
                        'x__right' => $i['i__id'],
                    )) as $follow){
                        array_push($must_follow, $follow['x__up']);
                    }

                    array_push($i_scanned, $i['i__id']);
                    $title = view_i_title($i, true);
                    $total_sent = 0;

                    //The time is here! Send event reminders to those who successfully discovered this:
                    foreach($this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___40986')) . ')' => null, //SUCCESSFUL DISCOVERIES
                        'x__left' => $i['i__id'],
                    ), array('x__creator'), 0) as $x){

                        //Make sure this member qualified:
                        if(count($must_follow)>0 && count($must_follow)!=count($this->X_model->fetch(array(
                                'x__down' => $x['e__id'],
                                'x__up IN (' . join(',', $must_follow) . ')' => null,
                                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            )))){
                            //User does not have all navigation items, skip for now:
                            continue;
                        }

                        $user_website = user_website($x['e__id']);
                        $subject = 'Reminder: '.$title.' Starts in '.view_time_difference($time_starts);
                        $plain_message = 'This is a friendly reminder about an upcoming event you signed up for:'.
                            "\n".
                            "\n".$i['i__message'].
                            "\n".'Start Time: '.date("D M j G:i:s T", $time_starts).
                            ( count($time_ends) && strtotime($time_ends[0]['x__message']) ? "\n".'End Time: '.date("D M j G:i:s T", strtotime($time_ends[0]['x__message'])) : '' ).
                            "\n".'https://'.get_domain('m__message', $x['e__id'], $user_website).'/'.$i['i__hashtag'].
                            "\n".
                            "\n".'If you cannot attend this event please inform us by cancelling here:'.
                            "\n".'https://'.get_domain('m__message', $x['e__id'], $user_website).view_app_link(42216).'?x__id='.$x['x__id'].'&e__handle='.$x['e__handle'].'&e__hash='.view_e__hash($x['e__handle']);

                        //Send message:
                        $send_dm = $this->X_model->send_dm($x['e__id'], $subject, $plain_message, array(
                            'x__left' => $i['i__id'],
                        ), 0, $user_website);

                        $total_sent += ( $send_dm['status'] ? 1 : 0 );

                    }

                    $remind_status = ( $total_sent>0 ? 1 : -1 );

                } else {
                    //Reminder time has not yet come, do nothing and wait until it arrives...
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
            $this->X_model->update($i['x__id'], array(
                'x__type' => ($remind_status>0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
            ));
        }



    }

    foreach ($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___42256')) . ')' => null, //Authored
        'x__weight >' => time(), //Future event
        'x__up' => 26556, //Time Starts
        'i__type' => 30874, //Events
        'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ), array('x__right'), 0) as $i) {

        //Determine if it's time to send this message:
        $time_starts = 0;
        foreach ($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42256')) . ')' => null, //Authored
            'x__right' => $i['i__id'],
            'x__up' => 26556, //Time Starts
        )) as $time) {
            $time_starts = strtotime($time['x__message']);
            break;
        }

        if ($time_starts > 0 && $time_starts > time()) {
            //Still not time, go next:
            continue;
        }

        //Does it have an end time?
        $end_sending = 0;
        foreach ($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42256')) . ')' => null, //Authored
            'x__right' => $i['i__id'],
            'x__up' => 26557, //Time Ends
        )) as $time) {
            $end_sending = strtotime($time['x__message']);
            break;
        }


        $children = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $i['i__id'],
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC'));


        $top_i__hashtag = '';
        foreach ($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 32426, //PINNED IDEA
            '(x__right = ' . $i['i__id'] . ' OR x__left = ' . $i['i__id'] . ')' => null,
            'x__left >' => 0,
            'x__right >' => 0,
        )) as $top_i) {
            foreach ($this->I_model->fetch(array(
                'i__id' => ($top_i['x__right'] == $i['i__id'] ? $top_i['x__left'] : $top_i['x__right']),
            )) as $sel_i) {
                $top_i__hashtag = '/' . $sel_i['i__hashtag'];
                break;
            }
            if ($top_i__hashtag) {
                break;
            }
        }


        //Now let's see who will receive this:
        $total_sent = 0;
        $list_settings = list_settings($i['i__hashtag']);
        $subject_line = view_i_title($i, true);
        $content_message = view_i_links($i);
        if (!(substr($subject_line, 0, 1) == '#' && !substr_count($subject_line, ' '))) {
            //Let's remove the first line since it's used in the title:
            $content_message = delete_all_between('<div class="line first_line">', '</div>', $content_message);
        }

        foreach ($list_settings['query_string'] as $x) {

            //Send to everyone who has not received an email yet:
            if (!count($this->X_model->fetch(array(
                'x__left' => $i['i__id'],
                'x__creator' => $x['e__id'],
                'x__type' => 29399, //Idea Email
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))) {

                //Append children as options:
                $plain_message = '';
                foreach ($children as $down_or) {

                    $discoveries = $this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__creator' => $x['e__id'],
                        'x__left' => $down_or['i__id'],
                    ));
                    //Has this user discovered this idea or no?
                    $plain_message .= view_i_title($down_or, true) . ":\n";
                    $plain_message .= 'https://' . get_domain('m__message', $x['e__id'], $i['x__website']) . $top_i__hashtag . '/' . $down_or['i__hashtag'] . (!count($discoveries) ? '?e__handle=' . $x['e__handle'] . '&e__hash=' . view_e__hash($x['e__handle']) : '') . "\n\n";

                }

                $send_dm = $this->X_model->send_dm($x['e__id'], $subject_line, $content_message . "\n\n" . trim($plain_message), array(
                    'x__right' => $list_settings['list_config'][32426],
                    'x__left' => $i['i__id'],
                ), 0, $i['x__website'], true);
                $total_sent += ($send_dm['status'] ? 1 : 0);

            }
        }

        //Mark this as complete?
        if (!$end_sending || $end_sending < time()) {
            //Ready to be done:
            $this->X_model->update($i['x__id'], array(
                'x__type' => ($total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
            ));
        }

    }

} else {

    echo 'Nothing to see here...';

}