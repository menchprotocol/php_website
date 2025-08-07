<?php

//We must have $handle_session['handleid']
echo '<h2>My '.$focus_e['handlename'].'</h2>';

echo '<table class="table table-striped" style="border: 1px solid #000;">';

//Check this users @handle:
$was_found = false;
foreach($this->Chains->read(array(
    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    'chainhandleinput' => $focus_e['handleid'],
), array('chainhandleoutput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $handle_session['handleid'] */) as $handle_output){
    foreach($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        'chainhandleinput' => $handle_output['handleid'],
        'chainhandleoutput' => $handle_session['handleid'], //Since we are limiting the query to session user we could disable the $access_limit in the query before it
    ), array('chainhandleinput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $handle_session['handleid'] */) as $handle_data){
        $was_found = true;
        echo '<tr><td><span class="icon-block-sm">'.view_cover($handle_data['handlecover']).'</span>'.$handle_data['handlename'].':</td><td>'.$handle_data['chainvalue'].'</td></tr>';
    }
}
echo '</table>';


//Check this users @handle:
foreach($this->Chains->read(array(
    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    'chainhandleinput' => 1727532, //@checkmy
    'chainhandleoutput' => $focus_e['handleid'],
    'LENGTH(chainvalue) > 0' => null,
)) as $handle_info){
    echo '<br /><div>'.preg_replace('/(http[s]{0,1}\:\/\/\S{4,})\s{0,}/ims', '<a href="$1" target="_blank" style="color:#0000FF">$1</a> ', nl2br($handle_info['chainvalue'])).'</div>';
}



if(!$was_found){
    echo '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$focus_e['handlename'].' Not Found for '.$handle_session['handlename'].'! Contact your admin to inquire further as you are not listed here.</div>';
}