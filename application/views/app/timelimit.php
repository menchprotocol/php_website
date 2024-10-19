<?php

$filters = array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
    'x__following' => 28199,
);

//Give it some extra time in case they are in Paypal making the payment
$buffer_time = 300;

if(isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag'])){
    foreach($this->I_model->fetch(array(
        'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
    )) as $i){
        $filters['x__next'] = $i['i__id'];
        $buffer_time = 0;
    }
}

$links_deleted = 0;
$counter = 0;

//Go through all expire seconds ideas:
foreach($this->X_model->fetch($filters, array('x__next'), 0) as $expires){

    //Now go through everyone who discovered this selection:
    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansions
        'x__previous' => $expires['i__id'],
    ), array('x__player'), 0) as $x_progress){

        //Now see if the answer is completed:
        $answer_completed = $this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'x__previous' => $x_progress['x__next'],
            'x__player' => $x_progress['e__id'],
        ));
        $seconds_left = intval( intval( $expires['x__message']) + $buffer_time - (time() - strtotime($x_progress['x__time'])));

        if(!count($answer_completed) && intval( $expires['x__message'])>0 && $seconds_left <= 0){

            //Answer not yet completed and no time left, delete response:
            $deleted = false;
            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'x__previous' => $expires['i__id'],
                'x__player' => $x_progress['e__id'],
            ), array(), 0) as $delete){

                $deleted = true;
                $this->X_model->update($delete['x__id'], array(
                    'x__privacy' => 6173, //Transaction Deleted
                ), $player_e['e__id'], 29085); //Time Expired

            }

            if($deleted){
                $links_deleted++;
                echo '<div style="padding-left: 21px;">'.$links_deleted.') <a href="'.view_memory(42903,42902).$x_progress['e__handle'].'">'.$x_progress['e__title'].'</a>: '.$x_progress['x__time'].' ? '.$x_progress['x__message'].' / <a href="'.view_app_link(12722).'?x__id=' . $x_progress['x__id'] . '">'.$x_progress['x__id'].' / Answer: '.count($answer_completed).'</a> '.( !count($answer_completed) ? ( $seconds_left <= 0 ? ' DELETE ' : '['.$seconds_left.'] SEcs left' ) : '' ).' ('.intval( $expires['x__message']) .'+'. $buffer_time .'-'. time() .'-'. strtotime($x_progress['x__time'] ).' = '.$seconds_left.')</div>';
            }


        }

        //Now see if they have responded and completed the answer to this question:
        $counter++;

    }

}

echo '<div style="text-align: center">'.$links_deleted.'/'.$counter.' ideas expired.</div>';

if(isset($filters['x__next'])){
    foreach($this->I_model->fetch(array('i__id' => $filters['x__next'])) as $i){
        //We were deleting a single item, redirect back:
        js_php_redirect(view_memory(42903,33286).$i['i__hashtag'], 0);
    }
}