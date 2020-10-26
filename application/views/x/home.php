
<div class="container">
    <?php

    $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
    $e___12467 = $this->config->item('e___12467'); //MENCH COINS


    //HACK: Group certain terms to make UI look nicer:
    foreach(array('on the', 'of GIANTS') as $term){
        $i['i__title'] = str_replace($term,'<span class="inline-block">'.$term.'</span>',$i['i__title']);
    }

    //IDEA TITLE
    echo '<h1 class="big-frame extra-big">' . $i['i__title'] . '</h1>';


    //MESSAGES
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $i['i__id'],
    ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {
        echo $this->X_model->message_send( $x['x__message'], true);
    }


    //FEATURED IDEAS
    echo '<div class="row" style="margin-top: 89px;">';
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => $i['i__id'],
    ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $key => $x){
        //Show only if not in discovering list:
        echo view_i_cover(6255, $x, false, null, false);
    }
    echo '</div>';



    //SOCIAL FOOTER
    echo '<ul class="social-footer">';
    foreach($this->config->item('e___13894') as $e__id => $m) {
        echo '<li><a href="/x/go_url/'.$e__id.'" title="'.$m['m__title'].'">'.$m['m__icon'].'</a></li>';
    }
    echo '</ul>';

    ?>
</div>
