<?php

foreach ($this->Menchledger->fetch(array(
    'linkplayertype' => 33600, //Draft
    'linkplayerup' => 26582,
), array('linkidearight')) as $i) {

    //Determine if it's time to send this message:
    $time_starts = 0;
    foreach ($this->Menchledger->fetch(array(
        'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkidearight' => $i['ideaid'],
        'linkplayerup' => 43743, //Sending Starts
    )) as $time) {
        $time_starts = strtotime($time['linktext']);
        break;
    }

    if ($time_starts > 0 && $time_starts > time()) {
        //Still not time, go next:
        continue;
    }

    //Does it have an end time?
    $end_sending = 0;
    foreach ($this->Menchledger->fetch(array(
        'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkidearight' => $i['ideaid'],
        'linkplayerup' => 43744, //Sending Ends
    )) as $time) {
        $end_sending = strtotime($time['linktext']);
        break;
    }

    //Now let's see who will receive this:
    $demo_only = false;
    $idea_settings = idea_settings($i['ideahashtag']);
    $total_sent = $this->Menchledger->send_idea_mass_dm($idea_settings['query_string_filtered'], $i, $i['linkplayerdomain'], true, $demo_only);

    echo view_idea_title($i) . ' Sent ' . $total_sent . ' Messages to ' . count($idea_settings['query_string_filtered']) . ' Members<hr />';

    //Mark this as complete?
    if (!$demo_only && (!$end_sending || $end_sending < time())) {
        //Ready to be done:
        $this->Menchledger->update($i['linkid'], array(
            'linkplayertype' => ($total_sent > 0 ? 42292 /* Like Thumbs Up */ : 31840 /* Dislike Thumbs Down */),
            'linkplayercreator' => 26582, //Messenger
        ));
    }

}

