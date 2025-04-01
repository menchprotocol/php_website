<?php

foreach($this->Menchledger->fetch(array(
    'linkvoid' => 0, //Not Void
    'linktype' => 33600, //Draft
    'linkup' => 26582,
), array('linkright')) as $i){

    //Determine if it's time to send this message:
    $time_starts = 0;
    foreach($this->Menchledger->fetch(array(
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkright' => $i['ideaid'],
        'linkup' => 43743, //Sending Starts
    )) as $time){
        $time_starts = strtotime($time['linktext']);
        break;
    }

    if($time_starts>0 && $time_starts>time()){
        //Still not time, go next:
        continue;
    }

    //Does it have an end time?
    $end_sending = 0;
    foreach($this->Menchledger->fetch(array(
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkright' => $i['ideaid'],
        'linkup' => 43744, //Sending Ends
    )) as $time){
        $end_sending = strtotime($time['linktext']);
        break;
    }

    //Now let's see who will receive this:
    $demo_only = false;
    $list_settings = list_settings($i['ideahashtag']);
    $total_sent = $this->Menchledger->send_idea_mass_dm($list_settings['query_string_filtered'], $i, $i['linkdomain'], true, $demo_only);

    echo view__idea_title($i).' Sent '.$total_sent.' Messages to '.count($list_settings['query_string_filtered']).' Members<hr />';

    //Mark this as complete?
    if(!$demo_only && (!$end_sending || $end_sending<time())){
        //Ready to be done:
        $this->Menchledger->update($i['linkid'], array(
            'linktype' => ( $total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */ ),
        ));
    }

}

