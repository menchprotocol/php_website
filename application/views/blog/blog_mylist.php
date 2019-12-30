<div class="container">

    <?php

    echo '<h1 class="yellow"><span class="icon-block-xlg"><i class="fas fa-circle yellow"></i></span>MY BLOGS</h1>';

    $session_en = superpower_assigned();
    $already_shown = array();


    if(!$session_en){

        echo '<div><a href="/signin" class="btn btn-play montserrat">SIGN IN/UP</a> to start blogging.</div>';

    } elseif(!superpower_assigned(10939)) {

        echo '<div><a href="/12867" class="btn btn-blog montserrat">START BLOGGING</a></div>';

    } else {

        $bookmark_ins = $this->READ_model->ln_fetch(array(
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_player_id' => 10573, //Blog Note Bookmarks
            'ln_parent_player_id' => $session_en['en_id'], //For this trainer
        ), array('in_child'), 0, 0, array('in_title' => 'ASC'));
        if(count($bookmark_ins)){

            echo '<div class="list-group">';
            foreach($bookmark_ins as $bookmark_in){

                //Add here so we don't show this again:
                array_push($already_shown, $bookmark_in['in_id']);

                echo echo_in_blog($bookmark_in);
            }
            echo '</div>';

        } else {

            //No bookmarks yet:
            echo '<div class="alert alert-warning">No blogs created yet</div>';

        }

        //Add Blog
        if(superpower_assigned(10939)){

            echo '<div style="margin-top: 10px;" class="'.superpower_active(10939).'"><a href="/blog/create" class="btn btn-blog"><i class="fas fa-plus"></i> BLOG</a></div>';

        } else {

            //They don't have the superpower, so redirect them to what they need to read to gain it:
            echo '<div style="margin-top: 10px;"><a href="/13008" class="btn btn-blog">START BLOGGING <i class="fas fa-angle-right"></i></a></div>';

        }


        /*
        $recent_ins = $this->READ_model->ln_fetch(array(
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_player_id' => 4993, //Trainer View Blog
            'ln_creator_player_id' => $session_en['en_id'], //For this trainer
        ), array('in_child'), 100);
        if(count($recent_ins)){

            $show_max = 10;

            echo '<h1 style="margin-top:30px;"><span class="icon-block-xlg"><i class="far fa-history"></i></span>RECENT</h1>';
            echo '<div class="list-group">';
            foreach($recent_ins as $recent_in){

                if(in_array($recent_in['in_id'], $already_shown)){
                    continue;
                }

                //Add here so we don't show this again:
                array_push($already_shown, $recent_in['in_id']);

                echo echo_in_blog($recent_in);

                if(count($already_shown) >= $show_max){
                    break;
                }

            }
            echo '</div>';

        }
        */

    }
    ?>
</div>