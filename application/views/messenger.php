<?php

foreach ($this->Chains->read(array(
    'LOWER(posthashtag)' => strtolower(trim($_GET['posthashtag'])),
), array('chainpostinput')) as $i) {

    echo $i['postmessageraw']."<hr />";

    //Make sure not messaged before:
    if(count($this->Chains->read(array(
        'chainusercreator' => 26582,
        'chainusertype IN (' . join(',', array(1309378 /* Post Trigerred */ , 31022 /* Post Skipped */)) . ')' => null, //Active Writes
        'chainpostinput' => $i['postid'],
    )))){
       //Already completed:
       continue;
    }

    /*
    //Determine if it's time to send this message:
    $time_starts = 0;
    foreach ($this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainpostinput' => $i['postid'],
        'chainuserinput' => 43743, //Sending Starts
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
        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainpostinput' => $i['postid'],
        'chainuserinput' => 43744, //Sending Ends
    )) as $time) {
        $end_sending = strtotime($time['chainvalue']);
        break;
    }
    */

    //Now let's see who will receive this:
    $demo_only = false;
    $post_settings = post_settings($i['posthashtag']);
    $total_sent = $this->Chains->broadcast($post_settings['query_string_filtered'], $i, $i['chainuserdomain'], true, $demo_only);

    echo view_post_title($i) . ' Sent ' . $total_sent . ' Messages to ' . count($post_settings['query_string_filtered']) . ' Members<hr />';

    //Mark this as complete?
    if (!$demo_only && (!$end_sending || $end_sending < time())) {

        //Ready to be done:
        $this->Chains->post_discovered(($total_sent > 0 ? 1309378 /* Post Trigerred */ : 31022 /* Post Skipped */), 26582, 0, $i);

    }

}

