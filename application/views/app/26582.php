<?php

if(!$is_u_request || isset($_GET['cron'])){

    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 33600, //Drafting Link
        'x__up' => 26582,
    ), array('x__right'), 0) as $i){

        //Determine if it's time to send this message:
        $time_starts = 0;
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
            'x__right' => $i['i__id'],
            'x__up' => 26556, //Time Starts
        )) as $time){
            $time_starts = strtotime($time['x__message']);
            break;
        }

        if($time_starts>0 && $time_starts>time()){
            //Still not time, go next:
            continue;
        }

        //Does it have an end time?
        $end_sending = 0;
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
            'x__right' => $i['i__id'],
            'x__up' => 26557, //Time Ends
        )) as $time){
            $end_sending = strtotime($time['x__message']);
            break;
        }




        $children = $this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
            'x__left' => $i['i__id'],
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC'));


        $top_i__hashtag = '';
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 32426, //PINNED IDEA
            '(x__right = '.$i['i__id'].' OR x__left = '.$i['i__id'].')' => null,
            'x__left >' => 0,
            'x__right >' => 0,
        )) as $top_i){
            foreach($this->I_model->fetch(array(
                'i__id' => ( $top_i['x__right']==$i['i__id'] ? $top_i['x__left'] : $top_i['x__right'] ),
            )) as $sel_i){
                $top_i__hashtag = '/'.$sel_i['i__hashtag'];
                break;
            }
            if($top_i__hashtag){
                break;
            }
        }


        //Now let's see who will receive this:
        $total_sent = 0;
        $list_settings = list_settings($i['i__hashtag']);
        $subject_line = view_i_title($i, true);
        $content_message = view_i_links($i, true, true); //Hide the show more content if any
        if(!(substr($subject_line, 0, 1)=='#' && !substr_count($subject_line, ' '))){
            //Let's remove the first line since it's used in the title:
            $content_message = delete_all_between('<div class="line first_line">','</div>', $content_message);
        }

        foreach($list_settings['query_string'] as $x) {

            //Send to all of them IF NOT DISCOVERED
            if(!count($this->X_model->fetch(array(
                'x__left' => $i['i__id'],
                'x__creator' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){

                //Append children as options:
                $plain_message = '';
                foreach($children as $down_or){
                    //Has this user discovered this idea or no?
                    $plain_message .= '<div class="line ">'.view_i_title($down_or, true).':</div>';
                    $plain_message .= '<div class="line ">'.'https://'.get_domain('m__message', $x['e__id'], $i['x__website']).$top_i__hashtag.'/'.$down_or['i__hashtag'].'?e__handle='.$x['e__handle'].'&e__time='.time().'&e__hash='.view_e__hash(time().$x['e__handle']).'</div>'."\n";
                }

                $send_dm = $this->X_model->send_dm($x['e__id'], $subject_line, $content_message."\n\n".trim($plain_message), array(
                    'x__right' => $list_settings['list_config'][32426],
                    'x__left' => $i['i__id'],
                ), 0, $i['x__website'], true);
                $total_sent += ( $send_dm['status'] ? 1 : 0 );

            }
        }

        //Mark this as complete?
        if(!$end_sending || $end_sending<time()){
            //Ready to be done:
            $this->X_model->update($i['x__id'], array(
                'x__type' => ( $total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */ ),
            ));
        }

    }

} else {

    echo 'Nothing to see here...';

}