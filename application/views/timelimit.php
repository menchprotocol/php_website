<?php

$filters = array(
    'chainusertype IN (' . join(',', $this->config->item('userids___42252')) . ')' => null, //Plain Chain
    'chainuserinput' => 28199,
);

//Give it some extra time in case they are in Paypal making the payment
$buffer_time = 300;

if (isset($_GET['posthashtag']) && strlen($_GET['posthashtag'])) {
    foreach ($this->Posts->read(array(
        'LOWER(posthashtag)' => strtolower($_GET['posthashtag']),
    )) as $i) {
        $filters['chainpostoutput'] = $i['postid'];
        $buffer_time = 0;
    }
}

$chains_deleted = 0;
$counter = 0;

//Go through all expire seconds posts:
foreach ($this->Chains->read($filters, array('chainpostoutput'), 0) as $expires) {

    //Now go through everyone who post discovered this selection:
    foreach ($this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___7704')) . ')' => null, //Discovery Expansions
        'chainpostinput' => $expires['postid'],
    ), array('chainusercreator'), 0) as $x_progress) {

        //Now see if the answer is completed:
        $answer_completed = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'chainpostinput' => $x_progress['chainpostoutput'],
            'chainusercreator' => $x_progress['userid'],
        ));
        $seconds_left = intval(intval($expires['chainvalue']) + $buffer_time - (time() - strtotime($x_progress['chaintime'])));

        if (!count($answer_completed) && intval($expires['chainvalue']) > 0 && $seconds_left <= 0) {

            //Answer not yet completed and no time left, delete response:
            $deleted = false;
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                'chainpostinput' => $expires['postid'],
                'chainusercreator' => $x_progress['userid'],
            ), array(), 0) as $delete) {

                $deleted = true;
                $this->Chains->delete($delete['chainid'], $user_session['userid']); //Time Expired

            }

            if ($deleted) {
                $chains_deleted++;
                echo '<div style="padding-left: 21px;">' . $chains_deleted . ') <a href="' . view_memory(42903, 42902) . $x_progress['userhandle'] . '">' . $x_progress['username'] . '</a>: ' . $x_progress['chaintime'] . ' ? ' . $x_progress['chainvalue'] . ' / <a href="' . view_app_chain(12722) . '?chainid=' . $x_progress['chainid'] . '">' . $x_progress['chainid'] . ' / Answer: ' . count($answer_completed) . '</a> ' . (!count($answer_completed) ? ($seconds_left <= 0 ? ' DELETE ' : '[' . $seconds_left . '] SEcs left') : '') . ' (' . intval($expires['chainvalue']) . '+' . $buffer_time . '-' . time() . '-' . strtotime($x_progress['chaintime']) . ' = ' . $seconds_left . ')</div>';
            }


        }

        //Now see if they have responded and completed the answer to this question:
        $counter++;

    }

}

echo '<div style="text-align: center">' . $chains_deleted . '/' . $counter . ' posts expired.</div>';

if (isset($filters['chainpostoutput'])) {
    foreach ($this->Posts->read(array('postid' => $filters['chainpostoutput'])) as $i) {
        //We were deleting a single item, redirect back:
        js_php_redirect(timelimit . view_memory(42903, 33286) . $i['posthashtag'], 0);
    }
}