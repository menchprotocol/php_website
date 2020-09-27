
<div class="container">
    <?php

    $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
    $e___12467 = $this->config->item('e___12467'); //MENCH COINS

    //IDEA TITLE
    echo '<h1 class="big-frame extra-big">' . view_i_title($i) . '</h1>';

    //IDEA MESSAGES
    echo '<div class="message-center" style="margin-bottom:89px;">';
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $i['i__id'],
    ), array(), 0, 0, array('x__sort' => 'ASC')) as $x) {
        echo $this->X_model->message_send( $x['x__message'] );
    }
    echo '</div>';




    //FEATURED IDEAS
    echo '<div class="row">';
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



    //SOCIAL FOOTER
    echo '<ul class="social-footer">';
    foreach($this->config->item('e___13894') as $e__id => $m) {
        echo '<li><a href="/x/go_url/'.$e__id.'" title="'.$m['m_title'].'">'.$m['m_icon'].'</a></li>';
    }
    echo '</ul>';

    ?>
</div>
