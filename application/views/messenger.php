<?php

boost_power();

foreach ($this->Chains->read(array(
    'chainsourcetype' => 31835, //Mention
    'chainsourceup' => 26582,
), array('chainidearight')) as $i) {

    //Make sure not completed before:
    if(count($this->Chains->read(array(
        'chainsourcecreator' => 26582,
        'chainsourcetype IN (' . join(',', array(42275 /* Idea Trigerred */ , 31022 /* Idea Skipped */)) . ')' => null, //Active Writes
        'chainidealeft' => $i['ideaid'],
    )))){
       //Already completed:
       continue;
    }

    //Determine if it's time to send this message:
    $time_starts = 0;
    foreach ($this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
        'chainidearight' => $i['ideaid'],
        'chainsourceup' => 43743, //Sending Starts
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
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
        'chainidearight' => $i['ideaid'],
        'chainsourceup' => 43744, //Sending Ends
    )) as $time) {
        $end_sending = strtotime($time['chainvalue']);
        break;
    }

    //Now let's see who will receive this:
    $demo_only = false;
    $idea_settings = idea_settings($i['ideahashtag']);
    $total_sent = $this->Chains->broadcast($idea_settings['query_string_filtered'], $i, $i['chainsourcedomain'], true, $demo_only);

    echo view_idea_title($i) . ' Sent ' . $total_sent . ' Messages to ' . count($idea_settings['query_string_filtered']) . ' Members<hr />';

    //Mark this as complete?
    if (!$demo_only && (!$end_sending || $end_sending < time())) {

        //Ready to be done:
        $this->Chains->idea_discovered(($total_sent > 0 ? 42275 /* Idea Trigerred */ : 31022 /* Idea Skipped */), 26582, 0, $i);

    }

}

