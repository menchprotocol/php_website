<?php

$terms_i__id = view_memory(6404,14373);

//MESSAGES
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $terms_i__id,
), array(), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
    echo $this->X_model->message_view( $x['x__message'], true);
}

//1 Level of Next Ideas:
foreach($is_next = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $terms_i__id,
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
