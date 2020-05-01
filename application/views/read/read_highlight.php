<div class="container">
    <?php

    //DISPLAY HIGHLIGHTS
    $en_all_11035 = $this->config->item('en_all_11035'); //SOURCE
    echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[12896]['m_icon'].'</span>'.$en_all_11035[12896]['m_name'].'</div>';

    echo '<div id="actionplan_steps" class="list-group no-side-padding">';
    foreach($this->LEDGER_model->ln_fetch(array(
        'ln_creator_source_id' => $session_en['en_id'],
        'ln_type_source_id' => 12896, //HIGHLIGHTS
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    ), array('in_previous'), 0, 0, array('ln_id' => 'DESC')) as $priority => $in) {
        echo echo_in_read($in, false, null, null, true);
    }
    echo '</div>';

    ?>
</div>