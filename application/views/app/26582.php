<?php

if(!$is_u_request || isset($_GET['cron'])){

    foreach($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 33600, //Drafting Link
        'x__up' => 26582,
    ), array('x__right'), 0) as $drafting_message){

        //Determine if it's time to send this message:
        $start_sending = 0;
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__right' => $drafting_message['i__id'],
            'x__up' => 26556, //Time Starts
        )) as $time){
            $start_sending = strtotime($time['x__message']);
            break;
        }

        if(!$start_sending || $start_sending>time()){
            //Still not time, go next:
            $this->X_model->update($drafting_message['x__id'], array(
                'x__type' => 31840, //Disagree
            ));
            continue;
        }

        //Does it have an end time?
        $end_sending = 0;
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__right' => $drafting_message['i__id'],
            'x__up' => 26557, //Time Ends
        )) as $time){
            $end_sending = strtotime($time['x__message']);
            break;
        }


        $plain_message = '';
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $drafting_message['i__id'],
        ), array(), 0, 0, array('x__weight' => 'ASC')) as $count => $x) {
            $plain_message .= $x['x__message']."\n\n";
            //$plain_message .= $this->X_model->message_view($x['x__message']);
        }
        $children = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $drafting_message['i__id'],
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC'));


        //Now let's see who will receive this:
        $list_settings = list_settings($drafting_message['i__id']);
        foreach($list_settings['query_string'] as $x) {
            //Send to all of them IF NOT SENT
            if(!count($this->X_model->fetch(array(
                'x__left' => $drafting_message['i__id'],
                'x__creator' => $x['e__id'],
                'x__type' => 40956, //Idea Email
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){

                //Append children as options:
                $addon_links = '';
                foreach($children as $down_or){
                    $addon_links .= $down_or['i__title'].":\n";
                    $addon_links .= 'https://'.get_domain('m__message', 0, $drafting_message['x__website']).'/'.$down_or['i__id']."\n\n"; //TODO Add user specific info to this link
                }

                $this->X_model->send_dm($x['e__id'], $drafting_message['i__title'], $plain_message.$addon_links, array(
                    'x__right' => $list_settings['list_config'][32426],
                    'x__left' => $drafting_message['i__id'],
                ), 0, 0, true);

            }
        }

        //Mark this as complete?
        if(!$end_sending || $end_sending<time()){
            //Ready to be done:
            $this->X_model->update($drafting_message['x__id'], array(
                'x__type' => 32264, //Agree
            ));
        }

    }




    //Look for messages to process, if any:
    foreach($this->X_model->fetch(array(
        'x__access' => 6175, //Pending
        'x__type' => 26582, //Send Instant Message
        'x__time <=' => date('Y-m-d H:i:s'), //Time to send it
    )) as $send_message){

        //Mark as sending so other cron job does not pick this up:
        $this->X_model->update($send_message['x__id'], array(
            'x__access' => 6176, //Published
        ));

        $x__metadata = unserialize($send_message['x__metadata']);

        //Determine Recipients:
        $contact_details = message_list($x__metadata['i__id'], $x__metadata['e__id'], $x__metadata['exclude_e'], $x__metadata['include_e'], $x__metadata['exclude_i'], $x__metadata['include_i']);

        //Loop through all contacts and send messages:
        $stats = array(
            'target' => count($contact_details['unique_users_id']),
            'unique' => 0,
            'phone_count' => 0,
            'error_count' => 0,
            'email_count' => 0,
        );

        foreach($contact_details['unique_users_id'] as $send_e__id){

            $results = $this->X_model->send_dm($send_e__id, $x__metadata['message_subject'], $x__metadata['message_text'], array('x__reference' => $send_message['x__id']), 0, $send_message['x__website']);

            if($results['status']){
                $stats['unique']++;
                $stats['email_count'] += $results['email_count'];
                $stats['phone_count'] += $results['phone_count'];
            } else {
                $stats['error_count']++;
            }
        }

        //Save final results:
        $this->X_model->update($send_message['x__id'], array(
            'x__metadata' => array(
                'stats' => $stats,
                'all_recipients' => $contact_details['unique_users_id'],
            ),
        ));

        //Show result:
        echo $send_message['x__id'].' sent '.$stats['unique'].' messages: '.$stats['email_count'].' Emails & '.$stats['phone_count'].' SMS';

    }

} else {

    echo 'Nothing to see here...';

}