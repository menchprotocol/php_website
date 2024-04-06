<?php

//Sync All Adding followers:
$counter = 0;
foreach($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 7545,
), array('x__following','x__next'), 0) as $addition_sync){

    //Fetch everyone who has discovered this idea:
    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
        'x__previous' => $addition_sync['x__following'],
    ), array('x__player'), 0) as $dicovered) {

        //Does this user already following the source?
        if(!count($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__following' => $addition_sync['x__following'],
            'x__follower' => $dicovered['x__player'],
        )))){

            $counter++;

            echo $counter.') @'.$dicovered['e__handle'].' not following @'.$addition_sync['e__handle'].' even though discovered #'.$addition_sync['i__hashtag'].'<hr />';

            /*
            //No, lets sync it:
            $this->X_model->create(array(
                'x__type' => 4251, //Creator
                'x__message' => $dicovered['x__message'],
                'x__player' => $dicovered['x__player'],
                'x__following' => $addition_sync['x__following'],
                'x__follower' => $dicovered['x__player'],
            ));

            $this->X_model->create(array(
                'x__type' => 43047, //Sync Add Follower
                'x__message' => $dicovered['x__message'],
                'x__player' => $dicovered['x__player'],
                'x__following' => $addition_sync['x__following'],
                'x__follower' => $dicovered['x__player'],
                'x__previous' => $addition_sync['i__id'],
            ));

            */
        }
    }
}