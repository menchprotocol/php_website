
<script src="/application/views/blog/blog_my.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
    $en_all_6201 = $this->config->item('en_all_6201'); //BLOG TABLE

    if(!$session_en){

        echo '<div style="padding:10px 0;"><a href="/signin?url=/blog" class="btn btn-blog montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start blogging.</div>';

    } else {

        //LEFT
        //echo '<div class="pull-left">'.echo_menu(12343, 'btn-blog').'</div>';

        $add_blog_btn = '<a href="javascript:void(0);" onclick="$(\'.add-blog-toggle\').toggleClass(\'hidden\');$(\'#newBlogTitle\').focus();" class="btn btn-blog btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12214]['m_name'].'">'.$en_all_11035[12214]['m_icon'].'</a>';

        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH

        echo '<div class="add-blog-toggle">';
        echo '<h1 class="pull-left inline-block blog"><span class="icon-block-xlg">' . $en_all_2738[4535]['m_icon'] . '</span>'.$en_all_2738[4535]['m_name'].'</h1>';


        //RIGHT
        echo '<div class="pull-right inline-block side-margin">';

            //Blog History
            echo '<a href="/ledger?ln_type_play_id='.join(',', $this->config->item('en_ids_12273')).'&ln_status_play_id='.join(',', $this->config->item('en_ids_7359')).'&ln_owner_play_id='.$session_en['en_id'].'" class="btn btn-blog btn-five icon-block-lg '.superpower_active(10964).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12215]['m_name'].'">'.$en_all_11035[12215]['m_icon'].'</a>';

            //Create Blog:
            echo $add_blog_btn;


        echo '</div>';
        echo '<div class="doclear">&nbsp;</div>';


        //List current blogs:
        $player_blogs = $this->READ_model->ln_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 10573, //Blog Note Bookmarks
            'ln_parent_play_id' => $session_en['en_id'], //For this trainer
        ), array('in_child'), 0, 0, array('in_title' => 'ASC'));
        if(count($player_blogs)){

            echo '<div class="list-group">';
            foreach($player_blogs as $bookmark_in){
                echo echo_in_blog($bookmark_in);
            }
            echo '</div>';

        } else {

            //No bookmarks yet:
            echo '<div class="alert alert-warning">No blogs created yet. Tap'.$add_blog_btn.' to add your first blog.</div>';

        }

        echo '</div>';



        //Add Blog Title:
        echo '<div class="add-blog-toggle hidden">';

        echo '<h1 class="blog"><span class="icon-block-xlg">' . $en_all_11035[12214]['m_icon'] . '</span>'.$en_all_11035[12214]['m_name'].'</h1>';

        if(superpower_assigned(10939)) {

            echo '<textarea id="newBlogTitle" class="form-control" placeholder="'.$en_all_6201[4736]['m_name'].'"></textarea>';

            echo '<div class="blogCreateStatusUpdate montserrat" style="padding-bottom: 20px;"></div>';

            echo '<div class="blogCreationController"><a href="javascript:void(0);" onclick="blog_create()" class="btn btn-blog btn-five icon-block-lg">'.$en_all_11035[12214]['m_name'].'</a> or <a href="javascript:void(0);" onclick="$(\'.add-blog-toggle\').toggleClass(\'hidden\');"><u>Cancel</u></a></div>';

        } else {

            $start_ins = $this->BLOG_model->in_fetch(array(
                'in_id' => config_var(10939),
            ));

            echo '<div style="padding:10px 0;"><p>Before creating your first blog, we invite you to read "<a href="/'.$start_ins[0]['in_id'].'" class="montserrat">'.$start_ins[0]['in_title'].'</a>" to get started.</p></div>';
            echo '<div style="padding:10px 0;"><a href="/'.$start_ins[0]['in_id'].'" class="btn btn-blog montserrat">GET STARTED <i class="fad fa-step-forward"></i></a></div>';

        }

        echo '</div>';


    }
    ?>
</div>