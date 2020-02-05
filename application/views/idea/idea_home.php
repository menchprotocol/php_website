
<script src="/application/views/idea/idea_home.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<script src="/application/views/idea/idea_shared.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
    $en_all_6201 = $this->config->item('en_all_6201'); //IDEA TABLE

    if(!$session_en){

        echo '<div style="padding:10px 0;"><a href="/sign?url=/idea" class="btn btn-idea montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start ideating.</div>';

    } else {



        echo '<div class="add-idea-toggle">';

        //List current ideas:
        $player_ideas = $this->READ_model->ln_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 10573, //Idea Note Bookmarks
            'ln_parent_play_id' => $session_en['en_id'], //For this trainer
        ), array('in_child'), 0, 0, array('in_title' => 'ASC'));
        if(count($player_ideas)){

            echo '<div class="list-group">';
            foreach($player_ideas as $bookmark_in){
                echo echo_in_idea($bookmark_in);
            }
            echo '</div>';

        } else {

            //No bookmarks yet:
            echo '<div class="alert alert-warning">No ideas created yet. Tap'.$add_idea_btn.' to add your first idea.</div>';

        }


        //Add New Idea:
        echo '<a href="javascript:void(0);" onclick="idea_create_initiate()" class="btn btn-idea btn-five icon-block-lg" style="padding-top:10px;">'.$en_all_11035[12214]['m_icon'].' '.$en_all_11035[12214]['m_name'].'</a>';

        echo '</div>';





        //Add Idea Title:
        echo '<div class="add-idea-toggle hidden">';

        echo '<h1 class="idea"><span class="icon-block">' . $en_all_11035[12214]['m_icon'] . '</span>'.$en_all_11035[12214]['m_name'].'</h1>';

        if(superpower_assigned(10939)) {

            echo '<textarea id="newIdeaTitle" class="form-control algolia_search" placeholder="'.$en_all_6201[4736]['m_name'].'"></textarea>';

            echo '<div class="ideaCreateStatusUpdate montserrat" style="padding-bottom: 20px;"></div>';

            echo '<div class="ideaCreationController"><a href="javascript:void(0);" onclick="idea_create()" class="btn btn-idea btn-five icon-block-lg">SAVE</a> or <a href="javascript:void(0);" onclick="$(\'.add-idea-toggle\').toggleClass(\'hidden\');"><u>Cancel</u></a></div>';

        } else {

            //Introduce Super Power:
            $superpower = 10939; //IDEA PEN TO START
            $en_all_10957 = $this->config->item('en_all_10957'); //PLAY SUPERPOWERS
            echo '<div style="padding:10px 0;"><p>Unlock the superpowers of '.$en_all_10957[$superpower]['m_icon'].' '.$en_all_10957[$superpower]['m_name'].' to '.$en_all_10957[$superpower]['m_desc'].'</p></div>';

            //Link to it on the website:
            $en_all_10876 = $this->config->item('en_all_10876'); //MENCH WEBSITE
            echo '<div style="padding:10px 0;"><a href="'.$en_all_10876[$superpower]['m_desc'].'" class="btn btn-idea montserrat">GET STARTED <i class="fad fa-step-forward"></i></a></div>';

        }

        echo '</div>';


    }
    ?>
</div>