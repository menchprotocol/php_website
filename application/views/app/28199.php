<?php

$filters = array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4983, //References
    'x__up' => 28199,
);

if(isset($_GET['i__id']) && intval($_GET['i__id'])>0){
    $filters['x__right'] = intval($_GET['i__id']);
    $buffer_time = 0;
} else {
    //Give it some extra time in case they are in Paypal making the payment
    $buffer_time = 180;
}

$links_deleted = 0;
//Go through all expire seconds ideas:
foreach($this->X_model->fetch($filters, array('x__right'), 0) as $expires){

    //echo '<hr /><div><a href="/~'.$expires['i__id'].'">'.$expires['i__title'].'</a></div>';

    //Now go through everyone who discovered this selection:
    $counter = 0;
    foreach($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansions
        'x__left' => $expires['i__id'],
    ), array('x__creator'), 0) as $x_progress){

        //Now see if the answer is completed:
        $answer_completed = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //Discoveries
            'x__left' => $x_progress['x__right'],
            'x__creator' => $x_progress['e__id'],
        ));
        $seconds_left = intval( intval( $expires['x__message']) + $buffer_time - (time() - strtotime($x_progress['x__time'])));

        if(!count($answer_completed) && $seconds_left <= 0){

            //Answer not yet completed and no time left, delete response:
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___12227')) . ')' => null,
                'x__left' => $expires['i__id'],
                'x__creator' => $x_progress['e__id'],
            ), array(), 0) as $delete){

                /*
                $this->X_model->update($delete['x__id'], array(
                    'x__access' => 6173, //Transaction Deleted
                ), $member_e['e__id'], 29085); //Time Expired
                */
                $links_deleted++;

            }

        }

        //Now see if they have responded and completed the answer to this question:
        $counter++;
        echo '<div style="padding-left: 21px;">'.$counter.') <a href="/@'.$x_progress['e__id'].'">'.$x_progress['e__title'].'</a>: '.$x_progress['x__time'].' ? '.$x_progress['x__message'].' / <a href="/-12722?x__id=' . $x_progress['x__id'] . '">'.$x_progress['x__id'].' / Answer: '.count($answer_completed).'</a> '.( !count($answer_completed) ? ( $seconds_left <= 0 ? ' DELETE ' : '['.$seconds_left.'] SEcs left' ) : '' ).' ('.intval( $expires['x__message']) .'+'. $buffer_time .'-'. time() .'-'. strtotime($x_progress['x__time'] ).' = '.$seconds_left.')</div>';

    }

}

echo '<div style="text-align: center">'.$links_deleted.' expired ideas removed.</div>';

if(isset($filters['x__right'])){
    //We were deleting a single item, redirect back:
    js_redirect('/'.$_GET['top_i__id'].'/'.$filters['x__right'], 0);
}