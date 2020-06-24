
<script src="/application/views/discover/home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
    <?php

    $session_source = superpower_assigned();
    $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
    $player_discovery_ids = array();


    if($session_source){

        //MY DISCOVERIES
        $player_discoveries = $this->DISCOVER_model->fetch(array(
            'x__player' => $session_source['e__id'],
            'x__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //MY DISCOVERIES
            'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        ), array('x__left'), 0, 0, array('x__sort' => 'ASC'));

        echo ( count($player_discoveries) > 1 ? '<script> $(document).ready(function () {discover_sort_load()}); </script>' : '<style> .discover-sorter {display:none !important;} </style>' ); //Need 2 or more to sort

        if(count($player_discoveries)){

            echo '<div class="discover-topic" style="margin-top: 34px;"><span class="icon-block">'.$sources__11035[12969]['m_icon'].'</span>'.$sources__11035[12969]['m_name'].'</div>';


            echo '<div class="clear-discovery-list">';
            echo '<div id="home_discoveries" class="cover-list" style="padding-top:21px; padding-left:34px;">';
            foreach($player_discoveries as $idea) {
                array_push($player_discovery_ids, $idea['i__id']);
                echo view_i_cover($idea, true);
            }
            echo '</div>';
            echo '</div>';

            echo '<div class="doclear">&nbsp;</div>';


            //DISCOVER DELETE ALL (ACCESSIBLE VIA MAIN MENU)
            echo '<div class="clear-discovery-list hidden margin-top-down">';
            echo '<div class="alert alert-danger" role="alert">';
            echo '<span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span><b class="discover montserrat">DELETE ALL DISCOVERIES?</b>';
            echo '<br /><span class="icon-block">&nbsp;</span>Action cannot be undone.';
            echo '</div>';
            echo '<p style="margin-top:20px;"><a href="javascript:void(0);" onclick="discover_clear_all()" class="btn btn-discover"><i class="far fa-trash-alt"></i> DELETE ALL</a> or <a href="javascript:void(0)" onclick="$(\'.clear-discovery-list\').toggleClass(\'hidden\')" style="text-decoration: underline;">Cancel</a></p>';
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

            echo '<div class="discover-topic" style="margin-top: 34px;"><span class="icon-block">'.$sources__11035[12896]['m_icon'].'</span>'.$sources__11035[12896]['m_name'].'</div>';

            echo '<div class="list-group no-side-padding">';
            foreach($player_saved as $priority => $idea) {
                echo view_i_discover($idea, null, true);
            }
            echo '</div>';

        }

    } else {

        //Not logged in, show description:
        $ideas = $this->MAP_model->fetch(array(
            'i__id' => config_var(13405),
        ));

        //IDEA TITLE
        echo '<h1 class="block-one" style="padding-top: 21px;"><span class="icon-block top-icon">'.view_x_icon_legend( false , 0 ).'</span><span class="title-block-lg">' . view_i_title($ideas[0]) . '</span></h1>';

        //IDEA MESSAGES
        echo '<div style="margin-bottom:34px;">';
        foreach($this->DISCOVER_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => config_var(13405),
        ), array(), 0, 0, array('x__sort' => 'ASC')) as $message_discover) {
            echo $this->DISCOVER_model->message_send( $message_discover['x__message'] );
        }
        echo '</div>';

    }




    //FEATURED
    $featured_ideas = $this->DISCOVER_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => config_var(13405),
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

    echo '<div class="discover-topic" style="margin-top: 34px;"><span class="icon-block">'.$sources__11035[13216]['m_icon'].'</span>'.$sources__11035[13216]['m_name'].'</div>';
    echo '<div class="cover-list" style="padding:13px 0 33px 33px;">';
    foreach($featured_ideas as $key => $featured_idea){
        if(!in_array($featured_idea['i__id'], $player_discovery_ids)){
            //Show only if not in discovering list:
            echo view_i_cover($featured_idea, false);
        }
    }
    echo '</div>';

    ?>
</div>
