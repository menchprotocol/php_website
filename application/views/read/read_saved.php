
<div class="container">
    <?php

    $player_saved = $this->READ_model->fetch(array(
        'read__up' => $session_en['source__id'],
        'read__type' => 12896, //SAVED
        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    ), array('idea_next'), 0, 0, array('read__id' => 'DESC'));

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