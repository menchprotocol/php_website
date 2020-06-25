
<script src="/application/views/map/home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
    <?php

    $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
    $sources__2738 = $this->config->item('sources__2738'); //MENCH


    $player_maps = $this->DISCOVER_model->fetch(array(
        'i__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'x__type' => 10573, //MY IDEAS
        'x__up' => $session_source['e__id'], //For this player
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

    echo ( count($player_maps) > 1 ? '<script> $(document).ready(function () {x_sort_load(13412)}); </script>' : '<style> .discover-sorter {display:none !important;} </style>' ); //Need 2 or more to sort


    if(count($player_maps) > 0){
        //MY IDEAS
        echo '<div class="discover-topic"><span class="icon-block">'.$sources__11035[10573]['m_icon'].'</span>'.$sources__11035[10573]['m_name'].'</div>';
        echo '<div id="idea_covers" class="cover-list">';
        foreach($player_maps as $idea){
            echo view_i_cover($idea, true, false);
        }
        echo '</div>';
        echo '<div class="doclear" style="padding-bottom: 21px;">&nbsp;</div>';
    }



    //ADD IDEA MAPS
    echo '<div class="discover-topic"><span class="icon-block">'.$sources__11035[13416]['m_icon'].'</span>'.$sources__11035[13416]['m_name'].'</div>';
    echo '<div class="list-group">';
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
