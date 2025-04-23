<?php

//Calculates the weekly coins issued:
$players___11035 = $this->config->item('players___11035'); //Encyclopedia
$last_x_days = 7;

$chaintime_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$chaintime_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$chaintime_start = date("Y-m-d H:i:s", $chaintime_start_timestamp);
$chaintime_end = date("Y-m-d H:i:s", $chaintime_end_timestamp);

//Email Body
$html_message = '<div class="line">Here is what happened in the past '.$last_x_days.' day'.search($last_x_days).':</div><br />';
$subject = 'Report for the Week of '.date("M jS", $chaintime_start_timestamp);

foreach($this->config->item('players___31770') as $chainplayertype => $m) {

    $unique = count_link_groups($chainplayertype, null, $chaintime_end);
    $this_week = count_link_groups($chainplayertype, $chaintime_start, $chaintime_end);
    if(!$unique){
        continue;
    }
    $percent = ($unique / ( $unique - $this_week ) * 100) - 100;
    $growth = number_format($percent, ($percent < 10 ? 1 : 0));
    $growth = ( $growth >= 0 ? '+' : '-' ).$growth.'%';

    //Add to UI:
    $html_message .= '<div class="line"><span style="display:inline-block; width: 34px; text-align: right;">'.$growth.'</span><span style="width:34px !important; display: inline-block; text-align: center;">'.$m['m__cover'].'</span>'.number_format($unique, 0).' '.$m['m__title'].'</div>';

}


//Decide what to do with this?
if($player_http_request && !isset($_GET['email_trigger'])){

    echo '<div style="font-weight: bold; padding: 0 0 13px 0;">'.$subject.'</div>';
    echo $html_message;
    echo '<div style="padding: 21px 0;"><a href="'.view_app_link(12114).'?email_trigger=1">Email Me This Report</a></div>';

} else {


    $subscriber_filters = array(
        'chainplayerup' => 12114,
        'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            );

    //Should we limit the scope?
    if($player_http_request){
        $subscriber_filters['chainplayerdown'] = $player_session['playerid'];
    }


    $email_recipients = 0;
    //Send email to all subscribers:
    foreach($this->Links->read($subscriber_filters, array('chainplayerdown')) as $subscribed_u){

        $this->Links->message($subscribed_u['playerid'], $subject, $html_message);
        $email_recipients++;

    }

    echo 'Report sent to '.$email_recipients.' Member'.search($email_recipients);

}