
<script src="/application/views/discover/home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
    <?php

    $session_source = superpower_assigned();
    $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
    $player_read_ids = array();


    if($session_source){

        //MY DISCOVERIES
        $player_reads = $this->DISCOVER_model->fetch(array(
            'x__player' => $session_source['e__id'],
            'x__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //MY DISCOVERIES
            'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        ), array('x__left'), 0, 0, array('x__sort' => 'ASC'));

        echo ( count($player_reads) > 1 ? '<script> $(document).ready(function () {read_sort_load()}); </script>' : '<style> .read-sorter {display:none !important;} </style>' ); //Need 2 or more to sort

        if(count($player_reads)){

            echo '<div class="read-topic" style="margin-top: 34px;"><span class="icon-block">'.$sources__11035[12969]['m_icon'].'</span>'.$sources__11035[12969]['m_name'].'</div>';


            echo '<div class="clear-reads-list">';
            echo '<div id="home_reads" class="cover-list" style="padding-top:21px; padding-left:34px;">';
            foreach($player_reads as $idea) {
                array_push($player_read_ids, $idea['i__id']);
                echo view_idea_cover($idea, true);
            }
            echo '</div>';
            echo '</div>';

            echo '<div class="doclear">&nbsp;</div>';


            //DISCOVER DELETE ALL (ACCESSIBLE VIA MAIN MENU)
            echo '<div class="clear-reads-list hidden margin-top-down">';
            echo '<div class="alert alert-danger" role="alert">';
            echo '<span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span><b class="read montserrat">DELETE ALL DISCOVERIES?</b>';
            echo '<br /><span class="icon-block">&nbsp;</span>Action cannot be undone.';
            echo '</div>';
            echo '<p style="margin-top:20px;"><a href="javascript:void(0);" onclick="read_clear_all()" class="btn btn-read"><i class="far fa-trash-alt"></i> DELETE ALL</a> or <a href="javascript:void(0)" onclick="$(\'.clear-reads-list\').toggleClass(\'hidden\')" style="text-decoration: underline;">Cancel</a></p>';
            echo '</div>';

            echo '<div class="doclear">&nbsp;</div>';


        }



        //Saved
        $player_saved = $this->DISCOVER_model->fetch(array(
            'x__up' => $session_source['e__id'],
            'x__type' => 12896, //SAVED
            'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        ), array('x__right'), 0, 0, array('x__id' => 'DESC'));

        if(count($player_saved)){

            echo '<div class="read-topic" style="margin-top: 34px;"><span class="icon-block">'.$sources__11035[12896]['m_icon'].'</span>'.$sources__11035[12896]['m_name'].'</div>';

            echo '<div class="list-group no-side-padding">';
            foreach($player_saved as $priority => $idea) {
                echo view_idea_read($idea, null, true);
            }
            echo '</div>';

        }

    } else {

        //Not logged in, show description:
        $ideas = $this->MAP_model->fetch(array(
            'i__id' => $this->config->item('featured_i__id'),
        ));

        //IDEA TITLE
        echo '<h1 class="block-one" style="padding-top: 21px;"><span class="icon-block top-icon">'.view_read_icon_legend( false , 0 ).'</span><span class="title-block-lg">' . view_i__title($ideas[0]) . '</span></h1>';

        //IDEA MESSAGES
        echo '<div style="margin-bottom:34px;">';
        foreach($this->DISCOVER_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $this->config->item('featured_i__id'),
        ), array(), 0, 0, array('x__sort' => 'ASC')) as $message_read) {
            echo $this->DISCOVER_model->message_send( $message_read['x__message'] );
        }
        echo '</div>';

    }




    //FEATURED
    $featured_ideas = $this->DISCOVER_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => $this->config->item('featured_i__id'),
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

    echo '<div class="read-topic" style="margin-top: 34px;"><span class="icon-block">'.$sources__11035[13216]['m_icon'].'</span>'.$sources__11035[13216]['m_name'].'</div>';
    echo '<div class="cover-list" style="padding:13px 0 33px 33px;">';
    foreach($featured_ideas as $key => $featured_idea){
        if(!in_array($featured_idea['i__id'], $player_read_ids)){
            //Show only if not in reading list:
            echo view_idea_cover($featured_idea, false);
        }
    }
    echo '</div>';

    ?>
</div>
