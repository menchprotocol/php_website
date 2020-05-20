
<script src="/application/views/idea/idea_home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
    <?php

    //IDEA BOOKMARKS
    echo '<div id="myIdeas" class="list-group">';
    foreach($this->READ_model->fetch(array(
        'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'read__type' => 10573, //IDEA NOTES Bookmarks
        'read__up' => $session_en['source__id'], //For this player
    ), array('idea_next'), 0, 0, array('idea__weight' => 'DESC')) as $idea){
        echo view_idea($idea, 0, false, true);
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
