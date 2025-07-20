<?php

$filters = array(
    'chainhandletype IN (' . join(',', $this->config->item('handleids___42252')) . ')' => null, //Plain Chain
    'chainhandleinput' => 28199,
);

//Give it some extra time in case they are in Paypal making the payment
$buffer_time = 300;

if (isset($_GET['hashtagterm']) && strlen($_GET['hashtagterm'])) {
    foreach ($this->Hashtags->read(array(
        'LOWER(hashtagterm)' => strtolower($_GET['hashtagterm']),
    )) as $i) {
        $filters['chainhashtagoutput'] = $i['hashtagid'];
        $buffer_time = 0;
    }
}

$chains_deleted = 0;
$counter = 0;

//Go through all expire seconds hashtags:
foreach ($this->Chains->read($filters, array('chainhashtagoutput'), 0) as $expires) {

    //Now go through everyone who hashtag_discovered this selection:
    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___7704')) . ')' => null, //Discovery Expansions
        'chainhashtaginput' => $expires['hashtagid'],
    ), array('chainhandlecreator'), 0) as $x_progress) {

        //Now see if the answer is completed:
        $answer_completed = $this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhashtaginput' => $x_progress['chainhashtagoutput'],
            'chainhandlecreator' => $x_progress['handleid'],
        ));
        $seconds_left = intval(intval($expires['chainvalue']) + $buffer_time - (time() - strtotime($x_progress['chaintime'])));

        if (!count($answer_completed) && intval($expires['chainvalue']) > 0 && $seconds_left <= 0) {

            //Answer not yet completed and no time left, delete response:
            $deleted = false;
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                'chainhashtaginput' => $expires['hashtagid'],
                'chainhandlecreator' => $x_progress['handleid'],
            ), array(), 0) as $delete) {

                $deleted = true;
                $this->Chains->delete($delete['chainid'], $handle_session['handleid']); //Time Expired

            }

            if ($deleted) {
                $chains_deleted++;
                echo '<div style="padding-left: 21px;">' . $chains_deleted . ') <a href="' . view_memory(42903, 42902) . $x_progress['handleterm'] . '">' . $x_progress['handlename'] . '</a>: ' . $x_progress['chaintime'] . ' ? ' . $x_progress['chainvalue'] . ' / <a href="' . view_app_chain(12722) . '?chainid=' . $x_progress['chainid'] . '">' . $x_progress['chainid'] . ' / Answer: ' . count($answer_completed) . '</a> ' . (!count($answer_completed) ? ($seconds_left <= 0 ? ' DELETE ' : '[' . $seconds_left . '] SEcs left') : '') . ' (' . intval($expires['chainvalue']) . '+' . $buffer_time . '-' . time() . '-' . strtotime($x_progress['chaintime']) . ' = ' . $seconds_left . ')</div>';
            }


        }

        //Now see if they have responded and completed the answer to this question:
        $counter++;

    }

}

echo '<div style="text-align: center">' . $chains_deleted . '/' . $counter . ' hashtags expired.</div>';

if (isset($filters['chainhashtagoutput'])) {
    foreach ($this->Hashtags->read(array('hashtagid' => $filters['chainhashtagoutput'])) as $i) {
        //We were deleting a single item, redirect back:
        js_php_redirect(timelimit . view_memory(42903, 33286) . $i['hashtagterm'], 0);
    }
}