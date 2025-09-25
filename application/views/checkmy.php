<?php

//We must have $user_session['userid']
echo '<h2>My '.$focus_e['username'].'</h2>';

echo '<table class="table table-striped" style="border: 1px solid #000;">';

//Check this users @user:
$was_found = false;
foreach($this->Ideachains->read(array(
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
    'chainuserinput' => $focus_e['userid'],
), array('chainuseroutput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $user_session['userid'] */) as $user_output){
    foreach($this->Ideachains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        'chainuserinput' => $user_output['userid'],
        'chainuseroutput' => $user_session['userid'], //Since we are limiting the query to session user we could disable the $access_limit in the query before it
    ), array('chainuserinput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $user_session['userid'] */) as $user_data){
        $was_found = true;
        echo '<tr><td><span class="icon-block-sm">'.view_cover($user_data['usercover']).'</span>'.$user_data['username'].( strlen($user_data['chainvalue']) ? ':' : '' ).'</td><td>'.$user_data['chainvalue'].'</td></tr>';
    }
}
echo '</table>';


//Check this users @user:
foreach($this->Ideachains->read(array(
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
    'chainuserinput' => 1727532, //@checkmy
    'chainuseroutput' => $focus_e['userid'],
    'LENGTH(chainvalue) > 0' => null,
)) as $user_info){
    echo '<br /><div>'.preg_replace('/(http[s]{0,1}\:\/\/\S{4,})\s{0,}/ims', '<a href="$1" target="_blank" style="color:#0000FF">$1</a> ', nl2br($user_info['chainvalue'])).'</div>';
}



if(!$was_found){

    echo '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$focus_e['username'].' Not Found for '.$user_session['username'].'! Contact your admin to inquire further as you are not listed here.</div>';

} else {

    foreach($this->Ideachains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        'chainuserinput' => $user_output['userid'],
        'chainuseroutput' => $user_session['userid'], //Since we are limiting the query to session user we could disable the $access_limit in the query before it
    ), array('chainuserinput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $user_session['userid'] */) as $user_data){
        $was_found = true;
        echo '<tr><td><span class="icon-block-sm">'.view_cover($user_data['usercover']).'</span>'.$user_data['username'].':</td><td>'.$user_data['chainvalue'].'</td></tr>';
    }
    
}