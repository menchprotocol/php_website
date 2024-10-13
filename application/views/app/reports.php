<?php

//Calculates the weekly coins issued:
$e___11035 = $this->config->item('e___11035'); //Encyclopedia
$last_x_days = 7;

$x__time_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$x__time_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$x__time_start = date("Y-m-d H:i:s", $x__time_start_timestamp);
$x__time_end = date("Y-m-d H:i:s", $x__time_end_timestamp);

//Email Body
$html_message = '<div class="line">Here is what happened in the last '.$last_x_days.' day'.view__s($last_x_days).':</div>';

foreach($this->config->item('e___42263') as $x__type => $m) {

    $unique = count_link_groups($x__type, null, $x__time_end);
    $this_week = count_link_groups($x__type, $x__time_start, $x__time_end);
    if(!$unique){
        continue;
    }
    $growth = format_percentage(($unique / ( $unique - $this_week ) * 100) - 100);
    $growth = ( $growth >= 0 ? '+' : '-' ).$growth.'%';

    //Add to UI:
    $html_message .= '<div class="line"><span style="display:inline-block; width: 55px; text-align: right;">'.$growth.'</span><span style="width:34px !important; display: inline-block; text-align: center;">'.$m['m__cover'].'</span>'.view_number($unique).' '.$m['m__title'].'</div>';

    //Primary Coin?
    if(in_array($x__type, $this->config->item('n___6404'))){
        $subject = $m['m__title'].' '.$growth.' for the Week of '.date("M jS", $x__time_start_timestamp);
    }

}


//Decide what to do with this?
if($player_http_request && !isset($_GET['email_trigger'])){

    echo '<div style="font-weight: bold; padding: 0 0 13px 0;">'.$subject.'</div>';
    echo $html_message;
    echo '<div style="padding: 21px 0;"><a href="'.view_app_link(12114).'?email_trigger=1">Email Me This Report</a></div>';

} else {


    $subscriber_filters = array(
        'x__following' => 12114,
        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            );

    //Should we limit the scope?
    if($player_http_request){
        $subscriber_filters['x__follower'] = $player_e['e__id'];
    }


    $email_recipients = 0;
    //Send email to all subscribers:
    foreach($this->X_model->fetch($subscriber_filters, array('x__follower')) as $subscribed_u){

        $this->X_model->send_dm($subscribed_u['e__id'], $subject, $html_message);
        $email_recipients++;

    }

    echo 'Report sent to '.$email_recipients.' Member'.view__s($email_recipients);

}