<?php

boost_power();

foreach ($this->Links->read(array(
    'chainplayertype' => 33600, //Draft
    'chainplayerup' => 26582,
), array('chainidearight')) as $i) {

    //Determine if it's time to send this message:
    $time_starts = 0;
    foreach ($this->Links->read(array(
        'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'chainidearight' => $i['ideaid'],
        'chainplayerup' => 43743, //Sending Starts
    )) as $time) {
        $time_starts = strtotime($time['chaintext']);
        break;
    }

    if ($time_starts > 0 && $time_starts > time()) {
        //Still not time, go next:
        continue;
    }

    //Does it have an end time?
    $end_sending = 0;
    foreach ($this->Links->read(array(
        'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'chainidearight' => $i['ideaid'],
        'chainplayerup' => 43744, //Sending Ends
    )) as $time) {
        $end_sending = strtotime($time['chaintext']);
        break;
    }

    //Now let's see who will receive this:
    $demo_only = false;
    $idea_settings = idea_settings($i['ideahashtag']);
    $total_sent = $this->Links->broadcast($idea_settings['query_string_filtered'], $i, $i['chainplayerdomain'], true, $demo_only);

    echo view_idea_title($i) . ' Sent ' . $total_sent . ' Messages to ' . count($idea_settings['query_string_filtered']) . ' Members<hr />';

    //Mark this as complete?
    if (!$demo_only && (!$end_sending || $end_sending < time())) {
        //Ready to be done:
        $this->Links->update($i['chainid'], array(
            'chainplayertype' => ($total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
            'chainplayercreator' => 26582, //Messenger
        ));
    }

}

