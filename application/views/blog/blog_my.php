<div class="container">

    <?php

    echo '<h1 class="yellow"><span class="icon-block-xlg"><i class="fas fa-circle yellow"></i></span>MY BLOGS</h1>';

    $session_en = superpower_assigned();

    if(!$session_en){

        echo '<div style="padding:10px 0;"><a href="/signin" class="btn btn-play montserrat">SIGN IN/UP</a> to start blogging.</div>';

    } else {

        //List current blogs:
        $bookmark_ins = $this->READ_model->ln_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 10573, //Blog Note Bookmarks
            'ln_parent_play_id' => $session_en['en_id'], //For this trainer
        ), array('in_child'), 0, 0, array('in_title' => 'ASC'));
        if(count($bookmark_ins)){

            echo '<div class="list-group">';
            foreach($bookmark_ins as $bookmark_in){
                echo echo_in_blog($bookmark_in);
            }
            echo '</div>';

        } else {

            //No bookmarks yet:
            echo '<div class="alert alert-warning">No blogs created yet</div>';

        }

        if(superpower_assigned(10939)) {

            echo '<div style="padding:10px 0;" class="'.superpower_active(10939).'"><a href="/blog/create" class="btn btn-blog"><i class="fas fa-plus"></i> NEW BLOG</a></div>';

        } else {

            echo '<div style="padding:10px 0;"><a href="/'.config_var(10939).'" class="btn btn-blog montserrat">START BLOGGING <i class="fas fa-angle-right"></i></a></div>';

        }
    }
    ?>
</div>