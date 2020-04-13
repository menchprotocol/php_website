
<script src="/application/views/note/note_bookmarks.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<script src="/application/views/note/note_shared.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH  NAVIGATION
    $en_all_2738 = $this->config->item('en_all_2738'); //MENCH

    if(!$session_en){

        echo '<div style="padding:10px 0;"><a href="/source/sign?url=/note" class="btn btn-note montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start noteting.</div>';

    } else {

        //Add New Note:
        $superpower = 10939; //NOTE PEN TO START

        if(superpower_assigned($superpower)) {

            echo '<div id="myNotes" class="list-group">';

            //List current notes:
            foreach($this->READ_model->ln_fetch(array(
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Note Status Active
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id' => 10573, //Note Pads Bookmarks
                'ln_parent_source_id' => $session_en['en_id'], //For this trainer
            ), array('in_child'), 0, 0, array('in_weight' => 'DESC')) as $bookmark_in){
                echo echo_in($bookmark_in, 0, false, true);
            }

            echo '<div class="list-group-item itemnote '.superpower_active($superpower).'" style="padding:5px 0;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean" style="margin-top: 6px;"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent"
                           maxlength="' . config_var(11071) . '"
                           id="newNoteTitle"
                           style="margin-bottom: 0; padding: 5px 0;"
                           placeholder="NEW NOTE">
                </div><div class="algolia_pad_search hidden in_pad_new_in"></div></div>';

            echo '</div>';

        } else {

            //Introduce Super Power:
            $en_all_10957 = $this->config->item('en_all_10957'); //PLAYER SUPERPOWERS
            echo '<div style="padding:10px 0;"><p>Unlock the superpowers of '.$en_all_10957[$superpower]['m_icon'].' <span class="montserrat doupper '.extract_icon_color($en_all_10957[$superpower]['m_icon']).'">'.$en_all_10957[$superpower]['m_name'].'</span> to '.$en_all_10957[$superpower]['m_desc'].'</p></div>';

            //Link to it on the website:
            $en_all_10876 = $this->config->item('en_all_10876'); //MENCH WEBSITE
            echo '<div style="padding:10px 0;"><a href="'.$en_all_10876[$superpower]['m_desc'].'" class="btn btn-note montserrat">GET STARTED <i class="fad fa-step-forward"></i></a></div>';

        }
    }
    ?>
</div>