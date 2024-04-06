<?php

//Sync All Adding followers:
$counter = 0;
foreach($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 7545,
    'x__following NOT IN (' . join(',', $this->config->item('n___43048')) . ')' => null, //No need to add these special ones... SourceNickname
), array('x__following'), 0) as $addition_sync){

    $append_source = false;

    //Fetch everyone who has discovered this idea:
    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
        'x__previous' => $addition_sync['x__next'],
    ), array('x__player'), 0) as $dicovered) {

        //lets append this source:
        $append_source = append_source($addition_sync['x__following'], $dicovered['x__player'], $dicovered['x__message'], $addition_sync['x__next']);

        $counter++;
        foreach($this->I_model->fetch(array(
            'i__id' => $addition_sync['x__next'],
        )) as $x_n){
            echo $counter.') @'.$dicovered['e__handle'].' not following @'.$addition_sync['e__handle'].' even though discovered #'.$x_n['i__hashtag'].' with value "'.$dicovered['x__message'].'" ['.intval($append_source).']<hr />';
        }

    }

    if($append_source){
        break;
    }
}