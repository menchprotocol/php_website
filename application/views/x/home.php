
<div class="container">
    <?php

    $is = $this->I_model->fetch(array(
        'i__id' => config_var(12137),
    ));

    //IDEA TITLE
    echo '<h1>' . view_i_title($is[0]) . '</h1>';

    //IDEA MESSAGES
    echo '<div class="big-cover">';
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => config_var(12137),
    ), array(), 0, 0, array('x__sort' => 'ASC')) as $x) {
        echo $this->X_model->message_send( $x['x__message'] );
    }
    echo '</div>';


    //FEATURED IDEAS
    $featured_i = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => config_var(12137),
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

    echo '<div class="list-group cover-list space-left">';
    foreach($featured_i as $key => $x){
        //Show only if not in discovering list:
        echo view_i_cover(6255, $x, false);
    }
    echo '</div>';

    ?>
</div>
