
<script src="/application/views/blog/blog_home.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<script src="/application/views/blog/blog_shared.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
    $en_all_2738 = $this->config->item('en_all_2738'); //MENCH

    if(!$session_en){

        echo '<div style="padding:10px 0;"><a href="/sign?url=/blog" class="btn btn-blog montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start blogting.</div>';

    } else {

        //Add New Blog:
        $superpower = 10939; //BLOG PEN TO START

        if(superpower_assigned($superpower)) {

            echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[10573]['m_icon'].'</span>'.$en_all_11035[10573]['m_name'].'</div>';

            echo '<div id="myBlogs" class="list-group">';

            //List current blogs:
            foreach($this->READ_model->ln_fetch(array(
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id' => 10573, //Blog Note Bookmarks
                'ln_parent_play_id' => $session_en['en_id'], //For this trainer
            ), array('in_child'), 0, 0, array('ln_id' => 'ASC')) as $bookmark_in){
                echo echo_in($bookmark_in, 0, false, true);
            }

            echo '<div class="list-group-item itemblog '.superpower_active($superpower).'" style="padding:5px 0;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean" style="margin-top: 6px;"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent"
                           maxlength="' . config_var(11071) . '"
                           id="newBlogTitle"
                           style="margin-bottom: 0; padding: 5px 0;"
                           placeholder="NEW BLOG TITLE">
                </div><div class="algolia_pad_search hidden in_pad_new_blog"></div></div>';

            echo '</div>';

        } else {

            //Introduce Super Power:
            $en_all_10957 = $this->config->item('en_all_10957'); //PLAY SUPERPOWERS
            echo '<div style="padding:10px 0;"><p>Unlock the superpowers of '.$en_all_10957[$superpower]['m_icon'].' <span class="montserrat doupper '.extract_icon_color($en_all_10957[$superpower]['m_icon']).'">'.$en_all_10957[$superpower]['m_name'].'</span> to '.$en_all_10957[$superpower]['m_desc'].'</p></div>';

            //Link to it on the website:
            $en_all_10876 = $this->config->item('en_all_10876'); //MENCH WEBSITE
            echo '<div style="padding:10px 0;"><a href="'.$en_all_10876[$superpower]['m_desc'].'" class="btn btn-blog montserrat">GET STARTED <i class="fad fa-step-forward"></i></a></div>';

        }
    }
    ?>
</div>