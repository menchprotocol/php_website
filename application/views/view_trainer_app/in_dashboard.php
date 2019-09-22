
<div class="maxout">

    <?php

    $already_shown = array();


    echo '<h5 class="badge badge-h"><i class="far fa-bookmark"></i> My Bookmarks</h5>';

    $bookmark_ins = $this->Links_model->ln_fetch(array(
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_entity_id' => 10573, //Intent Note Bookmarks
        'ln_parent_entity_id' => $session_en['en_id'], //For this trainer
    ), array('in_child'), 0, 0, array('in_outcome' => 'ASC'));
    if(count($bookmark_ins)){

        echo '<div class="list-group grey-list">';
        foreach($bookmark_ins as $bookmark_in){

            //Add here so we don't show this again:
            array_push($already_shown, $bookmark_in['in_id']);

            echo echo_in_dashboard($bookmark_in);
        }
        echo '</div>';

    } else {

        //No bookmarks yet:
        $en_all_4527 = $this->config->item('en_all_4527'); //Platform Cache
        $en_all_4485 = $this->config->item('en_all_4485'); //Intent Notes
        echo '<div class="alert alert-warning" style="margin: 0"><div style="margin-bottom: 10px;"><i class="fas fa-exclamation-triangle"></i> You have not bookmarked any intention yet. You can bookmark an intention by navigating to it and then:</div>'.$en_all_4527[4485]['m_icon'] .' '. $en_all_4527[4485]['m_name'].' <i class="fas fa-chevron-right" style="margin: 0 7px;"></i> '.$en_all_4485[10573]['m_icon'] .' '. $en_all_4485[10573]['m_name'].' <i class="fas fa-chevron-right" style="margin: 0 7px;"></i> Add "<b>@'.$session_en['en_id'].'</b>" (Your Entity ID)</div>';

    }






    echo '<h5 class="badge badge-h" style="margin-top: 30px;"><i class="far fa-history"></i> My Recents</h5>';

    $recent_ins = $this->Links_model->ln_fetch(array(
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        'in_level_entity_id IN (' . join(',', $this->config->item('en_ids_7767')) . ')' => null, //Intent Trainable
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_entity_id' => 4993, //Trainer View Intent
        'ln_creator_entity_id' => $session_en['en_id'], //For this trainer
    ), array('in_child'), 100);
    if(count($recent_ins)){

        $show_max = 10;

        echo '<div class="list-group grey-list">';
        foreach($recent_ins as $recent_in){

            if(in_array($recent_in['in_id'], $already_shown)){
                continue;
            }

            //Add here so we don't show this again:
            array_push($already_shown, $recent_in['in_id']);

            echo echo_in_dashboard($recent_in);

            if(count($already_shown) >= $show_max){
                break;
            }

        }
        echo '</div>';

    } else {

        echo '<div class="alert alert-warning" style="margin: 0"><i class="fas fa-exclamation-triangle"></i> You have not viewed any intentions yet</div>';

    }
    ?>

</div>