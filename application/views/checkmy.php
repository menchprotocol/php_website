<?php

//We must have $handle_session['handleid']

echo '<h1>'.$handle_session['handlename'].' '.$focus_e['handlename'].'</h1>';


//Check this users @handle:
$was_found = false;
foreach($this->Chains->read(array(
    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    'chainhandleinput' => $handle_session['handleid'],
), array('chainhandleoutput'), 0, 0, array('chainkey' => 'ASC')) as $handle_output){

    echo '<div>$handle_output: '.$handle_output['handlename'].( strlen($handle_output['chainvalue'])>0 ? ': <b class="main__title">'.$handle_output['chainvalue'].'</b>' : '' ).'</div>';

    foreach($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        'chainhandleinput' => $handle_output['handleid'],
        'chainhandleoutput' => $handle_session['handleid'],
    )) as $handle_data){
        $was_found = true;
        echo '<div>$handle_data: '.$handle_data['handlename'].( strlen($handle_data['chainvalue'])>0 ? ': <b class="main__title">'.$handle_data['chainvalue'].'</b>' : '' ).'</div>';
    }
}


if(!$was_found){
    echo '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$focus_e['handlename'].' Not Found for '.$handle_session['handlename'].'! Contact the admin to inquire further as you are not listed here.</div>';
}