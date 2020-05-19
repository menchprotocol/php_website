
<div class="container">
    <?php

    $player_saved = $this->READ_model->fetch(array(
        'ln_profile_source_id' => $session_en['en_id'],
        'ln_type_source_id' => 12896, //SAVED
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    ), array('in_next'), 0, 0, array('ln_id' => 'DESC'));

    if(!count($player_saved)){

        echo '<div class="alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>No saved ideas yet.</div>';

    } else {

        echo '<div class="list-group no-side-padding">';
        foreach($player_saved as $priority => $in) {
            echo view_in_read($in, null, true);
        }
        echo '</div>';

    }

    ?>
</div>