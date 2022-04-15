<?php

if(!isset($_GET['i__id'])){

    echo 'Enter i__id to get started.';

} else {

    $is = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
    ));

    if(!count($is)){
        echo 'Invalid i__id';
    } else {

        echo '<div class="container-center">';

        //IDEA TITLE
        echo '<h1>' . $is[0]['i__title'] . '</h1>';

        //MESSAGES
        foreach ($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $_GET['i__id'],
        ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
            echo $this->X_model->message_view($x['x__message'], true);
        }
        echo '<br /><br />';

        //1 Level of Next Ideas:
        foreach ($is_next = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $_GET['i__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $i) {

            echo '<h3 style="margin:13px 0; border-bottom: 1px solid #000;"><a href="javascript:void(0);" onclick="$(\'.i_msg_'.$i['i__id'].'\').toggleClass(\'hidden\');" class="inner-content doblock">' . $i['i__title'] . '</a></h3>';

            //MESSAGES
            echo '<div class="i_msg_'.$i['i__id'].' hidden" style="padding:5px 0 34px 41px;">';
            foreach ($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
                'x__type' => 4231, //IDEA NOTES Messages
                'x__right' => $i['i__id'],
            ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
                echo $this->X_model->message_view($x['x__message'], true);
            }
            echo '</div>';

        }

        echo '</div>';

    }
}
