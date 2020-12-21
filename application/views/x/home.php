
<div class="container">
    <?php

    //IDEA TITLE
    echo '<h1>' . $i['i__title'] . '</h1>';


    //MESSAGES
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $i['i__id'],
    ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {
        echo $this->X_model->message_view( $x['x__message'], true);
    }


    //FEATURED IDEAS
    echo view_i_featured();


    //Info Boxes:
    echo view_info_box(14340);

    ?>
</div>
