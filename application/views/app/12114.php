<?php

//Calculates the weekly coins issued:
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$last_x_days = 7;

$x__time_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$x__time_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$x__time_start = date("Y-m-d H:i:s", $x__time_start_timestamp);
$x__time_end = date("Y-m-d H:i:s", $x__time_end_timestamp);

//Email Body
$plain_message = 'Here is what happened in the last '.$last_x_days.' day'.view__s($last_x_days).':'."\n";

foreach($this->config->item('e___14874') as $x__type => $m) {

    $unique = count_interactions($x__type, null, $x__time_end);
    $this_week = count_interactions($x__type, $x__time_start, $x__time_end);
    $growth = format_percentage(($unique / ( $unique - $this_week ) * 100) - 100);
    $growth = ( $growth >= 0 ? '+' : '-' ).$growth.'%';

    //Add to UI:
    $plain_message .= "\n".$m['m__title'].' <span title="$unique='.$unique.' && $this_week='.$this_week.'">'.$growth.'</span>';

    //Primary Coin?
    if(in_array($x__type, $this->config->item('n___13776'))){
        $subject = $m['m__title'].' '.$growth.' for the Week of '.date("M jS", $x__time_start_timestamp);
    }

}


//Decide what to do with this?
if($is_u_request && !isset($_GET['email_trigger'])){

    echo '<div style="font-weight: bold; padding: 0 0 13px 0;">'.$subject.'</div>';
    echo nl2br($plain_message);
    echo '<div style="padding: 21px 0;"><a href="'.view_app_link(12114).'?email_trigger=1">Email Me This Report</a></div>';

} else {


    $subscriber_filters = array(
        'x__up' => 12114,
        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    );

    //Should we limit the scope?
    if($is_u_request){
        $subscriber_filters['x__down'] = $member_e['e__id'];
    }


    $email_recipients = 0;
    //Send email to all subscribers:
    foreach($this->X_model->fetch($subscriber_filters, array('x__down')) as $subscribed_u){

        $this->X_model->send_dm($subscribed_u['e__id'], $subject, $plain_message);
        $email_recipients++;

    }

    echo 'Report sent to '.$email_recipients.' Member'.view__s($email_recipients);

}