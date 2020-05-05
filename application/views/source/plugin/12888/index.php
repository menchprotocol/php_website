<?php

if(!isset($_GET['en_id']) || !intval($_GET['en_id'])){

    echo 'Missing source ID (Append ?en_id=SOURCE_ID in URL)';

    //Add here for now:
    echo '<table>';
    foreach($this->LEDGER_model->ln_fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        'ln_type_source_id' => 4257, //EMBED
    ), array('en_portfolio')) as $en_embed){

        //Find Parent Video:
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            'ln_portfolio_source_id' => $en['en_id'],
        ), array('en_profile'), 0, 0, array('en_weight' => 'DESC')) as ){

        }

        echo '<tr><td><a href="">'..'</a></td><td></td></tr>';
    }
    echo '</table>';

} else {

    //Fetch Source:
    $ens = $this->SOURCE_model->en_fetch(array(
        'en_id' => intval($_GET['en_id']),
    ));
    if(count($ens) > 0){

        //unserialize metadata if needed:
        echo_json($this->SOURCE_model->en_metadat_experts($ens[0]));

    } else {
        echo 'Source @'.intval($_GET['en_id']).' not found!';
    }
}

