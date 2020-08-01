
<div class="container">
    <?php

    $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION

    $is = $this->I_model->fetch(array(
        'i__id' => config_var(12137),
    ));

    //IDEA TITLE
    echo '<h1 class="block-one"><span class="icon-block top-icon thin-top">'.view_icon_i_x( 0 ).'</span><span class="title-block-lg">' . view_i_title($is[0]) . '</span></h1>';

    //IDEA MESSAGES
    echo '<div style="margin-bottom:34px;">';
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

    echo '<div class="headline" style="margin-top: 34px;"><span class="icon-block">'.$e___11035[12137]['m_icon'].'</span>'.$e___11035[12137]['m_title'].'</div>';
    echo '<div class="list-group cover-list space-left">';
    foreach($featured_i as $key => $x){
        //Show only if not in discovering list:
        echo view_i_cover($x, false);
    }
    echo '</div>';




    ?>
</div>
