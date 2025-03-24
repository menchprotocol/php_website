<?php

foreach($this->Mench_ledger->fetch(array(
    'link_void' => 0, //Not Void
    'link_type' => 33600, //Draft
    'link_up' => 26582,
), array('link_right')) as $i){

    //Determine if it's time to send this message:
    $time_starts = 0;
    foreach($this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'link_right' => $i['i__id'],
        'link_up' => 43743, //Sending Starts
    )) as $time){
        $time_starts = strtotime($time['link_text']);
        break;
    }

    if($time_starts>0 && $time_starts>time()){
        //Still not time, go next:
        continue;
    }

    //Does it have an end time?
    $end_sending = 0;
    foreach($this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'link_right' => $i['i__id'],
        'link_up' => 43744, //Sending Ends
    )) as $time){
        $end_sending = strtotime($time['link_text']);
        break;
    }

    //Now let's see who will receive this:
    $demo_only = false;
    $list_settings = list_settings($i['i__hashtag']);
    $total_sent = $this->Mench_ledger->send_i_mass_dm($list_settings['query_string_filtered'], $i, $i['link_domain'], true, $demo_only);

    echo view__i_title($i).' Sent '.$total_sent.' Messages to '.count($list_settings['query_string_filtered']).' Members<hr />';

    //Mark this as complete?
    if(!$demo_only && (!$end_sending || $end_sending<time())){
        //Ready to be done:
        $this->Mench_ledger->update($i['link_id'], array(
            'link_type' => ( $total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */ ),
        ));
    }

}

