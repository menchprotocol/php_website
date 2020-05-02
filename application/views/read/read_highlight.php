
<script src="/application/views/read/read_highlight.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">
    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //NAVIGATION
    echo '<div class="read-topic show-min"><span class="icon-block">'.$en_all_11035[12896]['m_icon'].'</span>'.$en_all_11035[12896]['m_name'].'</div>';

    $player_highlights = $this->LEDGER_model->ln_fetch(array(
        'ln_profile_source_id' => $session_en['en_id'],
        'ln_type_source_id' => 12896, //HIGHLIGHTS
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    ), array('in_previous'), 0, 0, array('ln_id' => 'DESC'));

    if(!count($player_highlights)){

        echo '<div class="alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>No highlights yet.</div>';

    } else {

        echo '<div class="list-group no-side-padding">';
        foreach($player_highlights as $priority => $in) {
            echo echo_in_read($in, false, null, null, true);
        }
        echo '</div>';

    }

    ?>
</div>