<?php

$website_terms = $this->E_model->scissor_i(website_setting(0), 14373); //Website Terms
if(!count($website_terms)){
    //Default terms:
    $website_terms = $this->E_model->scissor_i(6404, 14373); //Default Terms
}

foreach($website_terms as $i_item) {

    //MESSAGES
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $i_item['i__id'],
    ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
        echo $this->X_model->message_view( $x['x__message'], true);
    }

    //Next level Ideas:
    foreach($is_next = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => $i_item['i__id'],
    ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $i){

        echo '<h2>'.$i['i__title'].'</h2>';

        //MESSAGES
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $i['i__id'],
        ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
            echo $this->X_model->message_view( $x['x__message'], true);
        }

    }
}


