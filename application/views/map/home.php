
<script src="/application/views/map/home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
    <?php

    $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION

    //MY IDEAS
    echo '<div class="discover-topic"><span class="icon-block">'.$sources__11035[10573]['m_icon'].'</span>'.$sources__11035[10573]['m_name'].'</div>';

    echo '<div id="myIdeas" class="list-group">';
    foreach($this->DISCOVER_model->fetch(array(
        'i__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'x__type' => 10573, //MY IDEAS
        'x__up' => $session_source['e__id'], //For this player
    ), array('x__right'), 0, 0, array('i__weight' => 'DESC')) as $idea){
        echo view_i($idea, 0, false, true);
    }

    $sources__2738 = $this->config->item('sources__2738'); //MENCH

    echo '<div class="list-group-item list-adder itemidea">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$sources__2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control form-control-thick montserrat algolia_search dotransparent add-input"
                           maxlength="' . config_var(4736) . '"
                           id="newIdeaTitle"
                           placeholder="NEW IDEA">
                </div><div class="algolia_pad_search hidden"></div></div>';

    echo '</div>';

    ?>
</div>
