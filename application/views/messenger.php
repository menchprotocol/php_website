<?php

boost_power();

foreach ($this->Chains->read(array(
    'chainhandletype' => 31835, //Mention
    'chainhandleinput' => 26582,
), array('chainhashtagoutput')) as $i) {

    //Make sure not completed before:
    if(count($this->Chains->read(array(
        'chainhandlecreator' => 26582,
        'chainhandletype IN (' . join(',', array(42275 /* Hashtag Trigerred */ , 31022 /* Hashtag Skipped */)) . ')' => null, //Active Writes
        'chainhashtaginput' => $i['hashtagid'],
    )))){
       //Already completed:
       continue;
    }

    //Determine if it's time to send this message:
    $time_starts = 0;
    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainhashtagoutput' => $i['hashtagid'],
        'chainhandleinput' => 43743, //Sending Starts
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
        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainhashtagoutput' => $i['hashtagid'],
        'chainhandleinput' => 43744, //Sending Ends
    )) as $time) {
        $end_sending = strtotime($time['chainvalue']);
        break;
    }

    //Now let's see who will receive this:
    $demo_only = false;
    $hashtag_settings = hashtag_settings($i['hashtagstring']);
    $total_sent = $this->Chains->broadcast($hashtag_settings['query_string_filtered'], $i, $i['chainhandledomain'], true, $demo_only);

    echo view_hashtag_title($i) . ' Sent ' . $total_sent . ' Messages to ' . count($hashtag_settings['query_string_filtered']) . ' Members<hr />';

    //Mark this as complete?
    if (!$demo_only && (!$end_sending || $end_sending < time())) {

        //Ready to be done:
        $this->Chains->hashtag_discovered(($total_sent > 0 ? 42275 /* Hashtag Trigerred */ : 31022 /* Hashtag Skipped */), 26582, 0, $i);

    }

}

