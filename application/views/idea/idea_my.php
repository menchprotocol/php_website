
<script src="/application/views/idea/idea_my.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
    $en_all_6201 = $this->config->item('en_all_6201'); //IDEA TABLE

    if(!$session_en){

        echo '<div style="padding:10px 0;"><a href="/signin?url=/idea" class="btn btn-idea montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start ideating.</div>';

    } else {

        //LEFT
        //echo '<div class="pull-left">'.echo_menu(12343, 'btn-idea').'</div>';

        $add_idea_btn = '<a href="javascript:void(0);" onclick="$(\'.add-idea-toggle\').toggleClass(\'hidden\');$(\'#newIdeaTitle\').focus();" class="btn btn-idea btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12214]['m_name'].'">'.$en_all_11035[12214]['m_icon'].'</a>';

        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH

        echo '<div class="add-idea-toggle">';

        //RIGHT
        echo '<div class="pull-right inline-block side-margin">';

            //Idea History
            echo '<a href="/oil?ln_type_play_id='.join(',', $this->config->item('en_ids_12273')).'&ln_status_play_id='.join(',', $this->config->item('en_ids_7359')).'&ln_owner_play_id='.$session_en['en_id'].'" class="btn btn-idea btn-five icon-block-lg '.superpower_active(10964).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[11999]['m_name'].'">'.$en_all_11035[11999]['m_icon'].'</a>';

            //Create Idea:
            echo $add_idea_btn;


        echo '</div>';

        //LEFT
        echo '<h1 class="pull-left inline-block idea"><span class="icon-block-xlg">' . $en_all_2738[4535]['m_icon'] . '</span>'.$en_all_2738[4535]['m_name'].'</h1>';

        echo '<div class="doclear">&nbsp;</div>';


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

        echo '</div>';



        //Add Idea Title:
        echo '<div class="add-idea-toggle hidden">';

        echo '<h1 class="idea"><span class="icon-block-xlg">' . $en_all_11035[12214]['m_icon'] . '</span>'.$en_all_11035[12214]['m_name'].'</h1>';

        if(superpower_assigned(10939)) {

            echo '<textarea id="newIdeaTitle" class="form-control" placeholder="'.$en_all_6201[4736]['m_name'].'"></textarea>';

            echo '<div class="ideaCreateStatusUpdate montserrat" style="padding-bottom: 20px;"></div>';

            echo '<div class="ideaCreationController"><a href="javascript:void(0);" onclick="idea_create()" class="btn btn-idea btn-five icon-block-lg">'.$en_all_11035[12214]['m_name'].'</a> or <a href="javascript:void(0);" onclick="$(\'.add-idea-toggle\').toggleClass(\'hidden\');"><u>Cancel</u></a></div>';

        } else {

            $start_ins = $this->IDEA_model->in_fetch(array(
                'in_id' => config_var(10939),
            ));

            echo '<div style="padding:10px 0;"><p>Before creating your first idea, we invite you to read "<a href="/'.$start_ins[0]['in_id'].'" class="montserrat">'.$start_ins[0]['in_title'].'</a>" to get started.</p></div>';
            echo '<div style="padding:10px 0;"><a href="/'.$start_ins[0]['in_id'].'" class="btn btn-idea montserrat">GET STARTED <i class="fad fa-step-forward"></i></a></div>';

        }

        echo '</div>';


    }
    ?>
</div>