<?php

foreach($this->Mench_ledger->fetch(array(
    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'LinkType' => 33600, //Draft
    'LinkUp' => 26582,
), array('LinkRight')) as $i){

    //Determine if it's time to send this message:
    $time_starts = 0;
    foreach($this->Mench_ledger->fetch(array(
        'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'LinkType IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'LinkRight' => $i['i__id'],
        'LinkUp' => 43743, //Sending Starts
    )) as $time){
        $time_starts = strtotime($time['LinkText']);
        break;
    }

    if($time_starts>0 && $time_starts>time()){
        //Still not time, go next:
        continue;
    }

    //Does it have an end time?
    $end_sending = 0;
    foreach($this->Mench_ledger->fetch(array(
        'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'LinkType IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'LinkRight' => $i['i__id'],
        'LinkUp' => 43744, //Sending Ends
    )) as $time){
        $end_sending = strtotime($time['LinkText']);
        break;
    }

    //Now let's see who will receive this:
    $demo_only = false;
    $list_settings = list_settings($i['i__hashtag']);
    $total_sent = $this->Mench_ledger->send_i_mass_dm($list_settings['query_string_filtered'], $i, $i['LinkDomain'], true, $demo_only);

    echo view__i_title($i).' Sent '.$total_sent.' Messages to '.count($list_settings['query_string_filtered']).' Members<hr />';

    //Mark this as complete?
    if(!$demo_only && (!$end_sending || $end_sending<time())){
        //Ready to be done:
        $this->Mench_ledger->update($i['LinkId'], array(
            'LinkType' => ( $total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */ ),
        ));
    }

}

