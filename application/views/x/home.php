
<script src="/application/views/x/home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
    <?php

    $session_e = superpower_assigned();
    $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
    $my_x_ids = array();


    if($session_e){

        //MY DISCOVERIES
        $my_x = $this->X_model->fetch(array(
            'x__miner' => $session_e['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array('x__left'), 0, 0, array('x__sort' => 'ASC'));

        echo ( count($my_x) > 1 ? '<script> $(document).ready(function () {x_sort_load(6132)}); </script>' : '<style> .x-sorter {display:none !important;} </style>' ); //Need 2 or more to sort

        if(count($my_x)){

            echo '<div class="headline" style="margin-top: 34px;"><span class="icon-block">'.$e___11035[12969]['m_icon'].'</span>'.$e___11035[12969]['m_name'].'</div>';


            //DISCOVER DELETE ALL (ACCESSIBLE VIA MAIN MENU)
            echo '<div class="'.( !isset($_GET['reset']) ? 'hidden' : '' ).' margin-top-down">';
            echo '<div class="alert alert-danger" role="alert">';
            echo '<span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span><b class="discover montserrat">DELETE ALL DISCOVERIES?</b>';
            echo '<br /><span class="icon-block">&nbsp;</span>Action cannot be undone.';
            echo '</div>';
            echo '<p style="margin-top:20px;"><a href="javascript:void(0);" onclick="reset_6415()" class="btn btn-x"><i class="far fa-trash-alt"></i> DELETE ALL</a> or <a href="/" style="text-decoration: underline;">Cancel</a></p>';
            echo '</div>';

            echo '<div class="doclear">&nbsp;</div>';




            echo '<div id="i_covers" class="cover-list">';
            foreach($my_x as $x) {
                array_push($my_x_ids, $x['i__id']);
                echo view_i_cover($x, true);
            }
            echo '</div>';

            echo '<div class="doclear">&nbsp;</div>';


        }



        //Saved
        $miner_saved = $this->X_model->fetch(array(
            'x__up' => $session_e['e__id'],
            'x__type' => 12896, //SAVED
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array('x__right'), 0, 0, array('x__id' => 'DESC'));

        if(count($miner_saved)){

            echo '<div class="headline" style="margin-top: 34px;"><span class="icon-block">'.$e___11035[13510]['m_icon'].'</span>'.$e___11035[13510]['m_name'].'</div>';

            echo '<div class="list-group no-side-padding">';
            foreach($miner_saved as $priority => $x) {
                echo view_i_x($x, null, true);
            }
            echo '</div>';

        }

    } else {

        //Not logged in, show description:
        $is = $this->I_model->fetch(array(
            'i__id' => config_var(13427),
        ));

        //IDEA TITLE
        echo '<h1 class="block-one"><span class="icon-block top-icon">'.view_i_x_icon( 0 ).'</span><span class="title-block-lg">' . view_i_title($is[0]) . '</span></h1>';

        //IDEA MESSAGES
        echo '<div style="margin-bottom:34px;">';
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => config_var(13427),
        ), array(), 0, 0, array('x__sort' => 'ASC')) as $x) {
            echo $this->X_model->message_send( $x['x__message'] );
        }
        echo '</div>';

    }



    //FEATURED IDEAS
    $featured_is = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => config_var(13427),
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

    echo '<div class="headline" style="margin-top: 34px;"><span class="icon-block">'.$e___11035[13427]['m_icon'].'</span>'.$e___11035[13427]['m_name'].'</div>';
    echo '<div class="cover-list">';
    foreach($featured_is as $key => $x){
        if(!in_array($x['i__id'], $my_x_ids)){
            //Show only if not in discovering list:
            echo view_i_cover($x, false);
        }
    }
    echo '</div>';


    ?>
</div>
