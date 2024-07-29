<?php

foreach($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 33600, //Draft
    'x__following' => 26582,
), array('x__next'), 0) as $i){

    //Determine if it's time to send this message:
    $time_starts = 0;
    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'x__next' => $i['i__id'],
        'x__following' => 26556, //Time Starts
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
        'x__type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'x__next' => $i['i__id'],
        'x__following' => 26557, //Time Ends
    )) as $time){
        $end_sending = strtotime($time['x__message']);
        break;
    }

    //Now let's see who will receive this:
    $list_settings = list_settings($i['i__hashtag']);
    $total_sent = $this->X_model->send_i_mass_dm($list_settings['query_string'], $i, $i['x__website'], true);


    $this->X_model->create(array(
        'x__type' => 4246, //Platform Bug Reports
        'x__message' => 'test bugzzz sent '.$total_sent.' ['.view_i_title($i).' Sent '.$total_sent.' Messages to '.count($list_settings['query_string']).' Members.] '.print_r($list_settings, true),
    ));

    echo view_i_title($i).' Sent '.$total_sent.' Messages to '.count($list_settings['query_string']).' Members<hr />';

    //Mark this as complete?
    if(!$end_sending || $end_sending<time()){
        //Ready to be done:
        $this->X_model->update($i['x__id'], array(
            'x__type' => ( $total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */ ),
        ));
    }

}