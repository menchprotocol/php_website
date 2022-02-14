<?php

//Go through all expire seconds ideas:
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
    'i__type IN (' . join(',', $this->config->item('n___7712')) . ')' => null, //Select Next
    'x__type' => 4983, //References
    'x__up' => 28199,
), array('x__right')) as $expires){

    echo '<hr /><div><a href="/~'.$expires['i__id'].'">'.$expires['i__title'].'</a> ('.$expires['x__message'].')</div>';

    //Now go through everyone who discovered this selection:
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
        'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //Discovery Expansions
        'x__left' => $expires['i__id'],
    ), array('x__source')) as $x_progress){

        //Now see if the answer is completed:
        $answered = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //Discoveries
            'x__left' => $x_progress['x__right'],
            'x__source' => $x_progress['e__id'],
        ));
        $seconds_left = $expires['x__message'] - (strtotime($x_progress['x__time'])-time());

        //Now see if they have responded and completed the answer to this question:
        echo '<div style="padding-left: 21px;"><a href="/@'.$x_progress['e__id'].'">'.$x_progress['e__title'].'</a>: '.$x_progress['x__time'].' ? '.$x_progress['x__message'].' / <a href="/-12722?x__id=' . $x_progress['x__id'] . '">'.$x_progress['x__id'].' / Answer: '.count($answered).'</a> '.( !count($answered) ? ( !$seconds_left ? ' DELETE ' : '['.$seconds_left.'] SEcs left' ) : '' ).'</div>';

    }

}