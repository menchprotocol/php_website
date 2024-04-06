<?php

//Sync All Adding followers:
$counter = 0;
foreach($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 7545,
    'x__following NOT IN (' . join(',', $this->config->item('n___43048')) . ')' => null, //No need to add these special ones... SourceNickname
), array('x__following'), 0) as $addition_sync){

    //Fetch everyone who has discovered this idea:
    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
        'x__previous' => $addition_sync['x__next'],
    ), array('x__player'), 0) as $dicovered) {

        //Does this user already following the source?
        if(count($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4251, //SOURCE LINKS
            'x__following' => $addition_sync['x__following'],
            'x__follower' => $dicovered['x__player'],
        )))){
            continue;
        }

        //Does this source need a data type to be added?
        $invalid_data = false;
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__following IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //Data Types
            'x__follower' => $addition_sync['x__following'],
        )) as $data_type) {
            $data_type_validate = data_type_validate($data_type['x__following'], $dicovered['x__message']);
            if (!$data_type_validate['status']) {
                //Yes and it's not the data type needed:
                $invalid_data = true;
            }
        }
        if($invalid_data){
            continue;
        }

        //All good to add:
        $counter++;

        foreach($this->I_model->fetch(array(
            'i__id' => $addition_sync['x__next'],
        )) as $x_n){
            echo $counter.') @'.$dicovered['e__handle'].' not following @'.$addition_sync['e__handle'].' even though discovered #'.$x_n['i__hashtag'].' with value "'.$dicovered['x__message'].'"<hr />';
        }

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