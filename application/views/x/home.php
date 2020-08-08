
<div class="container">
    <?php

    $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION

    //IDEA TITLE
    echo '<h1 class="block-one"><span class="icon-block thin-top"><i class="fas fa-circle idea"></i></span><span class="title-block-lg">' . view_i_title($i) . '</span></h1>';

    //IDEA MESSAGES
    echo '<div style="margin-bottom:34px;">';
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $i['i__id'],
    ), array(), 0, 0, array('x__sort' => 'ASC')) as $x) {
        echo $this->X_model->message_send( $x['x__message'] );
    }
    echo '</div>';




    //FEATURED IDEAS
    echo '<div class="headline" style="margin-top: 34px;"><span class="icon-block">'.$e___11035[12137]['m_icon'].'</span>'.$e___11035[12137]['m_title'].'</div>';
    echo '<div>';
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => $i['i__id'],
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $key => $x){
        //Show only if not in discovering list:
        echo view_i_cover(6255, $x, false);
    }
    echo '</div>';



    ?>
</div>
