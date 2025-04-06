<?php

//Calculates the weekly coins issued:
$players___11035 = $this->config->item('players___11035'); //Encyclopedia
$last_x_days = 7;

$linktime_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$linktime_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$linktime_start = date("Y-m-d H:i:s", $linktime_start_timestamp);
$linktime_end = date("Y-m-d H:i:s", $linktime_end_timestamp);

//Email Body
$html_message = '<div class="line">Here is what happened in the last '.$last_x_days.' day'.view_s($last_x_days).':</div>';

foreach($this->config->item('players___42263') as $linkplayertype => $m) {

    $unique = count_link_groups($linkplayertype, null, $linktime_end);
    $this_week = count_link_groups($linkplayertype, $linktime_start, $linktime_end);
    if(!$unique){
        continue;
    }
    $percent = ($unique / ( $unique - $this_week ) * 100) - 100;
    $growth = number_format($percent, ($percent < 10 ? 1 : 0));
    $growth = ( $growth >= 0 ? '+' : '-' ).$growth.'%';

    //Add to UI:
    $html_message .= '<div class="line"><span style="display:inline-block; width: 55px; text-align: right;">'.$growth.'</span><span style="width:34px !important; display: inline-block; text-align: center;">'.$m['m__cover'].'</span>'.view_number($unique).' '.$m['m__title'].'</div>';

    //Primary Coin?
    if(in_array($linkplayertype, $this->config->item('playerids___6404'))){
        $subject = $m['m__title'].' '.$growth.' for the Week of '.date("M jS", $linktime_start_timestamp);
    }

}


//Decide what to do with this?
if($player_http_request && !isset($_GET['email_trigger'])){

    echo '<div style="font-weight: bold; padding: 0 0 13px 0;">'.$subject.'</div>';
    echo $html_message;
    echo '<div style="padding: 21px 0;"><a href="'.view_app_link(12114).'?email_trigger=1">Email Me This Report</a></div>';

} else {


    $subscriber_filters = array(
        'linkplayerup' => 12114,
        'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            );

    //Should we limit the scope?
    if($player_http_request){
        $subscriber_filters['linkplayerdown'] = $player_active['playerid'];
    }


    $email_recipients = 0;
    //Send email to all subscribers:
    foreach($this->Links->read($subscriber_filters, array('linkplayerdown')) as $subscribed_u){

        $this->Links->message($subscribed_u['playerid'], $subject, $html_message);
        $email_recipients++;

    }

    echo 'Report sent to '.$email_recipients.' Member'.view_s($email_recipients);

}