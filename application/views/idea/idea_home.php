
<script src="/application/views/idea/idea_home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //NAVIGATION
    echo '<div class="read-topic show-min"><span class="icon-block">'.$en_all_11035[4535]['m_icon'].'</span>'.$en_all_11035[4535]['m_name'].'</div>';

    if(!superpower_assigned(10939)) {

        //START PUBLISHING
        $en_all_10876 = $this->config->item('en_all_10876'); //MENCH WEBSITE
        echo '<div class="alert alert-warning"><span class="icon-block">&nbsp;</span><a href="'.$en_all_10876[10939]['m_desc'].'" class="underline">Get Started &raquo;</a></div>';

    } else {

        //IDEA BOOKMARKS
        echo '<div id="myIdeas" class="list-group">';
        foreach($this->LEDGER_model->ln_fetch(array(
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id' => 10573, //IDEA NOTES Bookmarks
            'ln_profile_source_id' => $session_en['en_id'], //For this player
        ), array('in_next'), 0, 0, array('in_weight' => 'DESC')) as $bookmark_in){
            echo echo_in($bookmark_in, 0, false, true);
        }

        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH

        echo '<div class="list-group-item list-adder itemidea">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control form-control-thick montserrat algolia_search dotransparent add-input"
                           maxlength="' . config_var(4736) . '"
                           id="newIdeaTitle"
                           placeholder="NEW TITLE">
                </div><div class="algolia_pad_search hidden"></div></div>';

        echo '</div>';

    }
    ?>
</div>
