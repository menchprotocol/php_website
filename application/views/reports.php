<?php

//Calculates the weekly coins issued:
$e___11035 = $this->config->item('e___11035'); //Encyclopedia
$last_x_days = 7;

$linktime_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$linktime_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$linktime_start = date("Y-m-d H:i:s", $linktime_start_timestamp);
$linktime_end = date("Y-m-d H:i:s", $linktime_end_timestamp);

//Email Body
$html_message = '<div class="line">Here is what happened in the last '.$last_x_days.' day'.view__s($last_x_days).':</div>';

foreach($this->config->item('e___42263') as $linktype => $m) {

    $unique = count_link_groups($linktype, null, $linktime_end);
    $this_week = count_link_groups($linktype, $linktime_start, $linktime_end);
    if(!$unique){
        continue;
    }
    $growth = format_percentage(($unique / ( $unique - $this_week ) * 100) - 100);
    $growth = ( $growth >= 0 ? '+' : '-' ).$growth.'%';

    //Add to UI:
    $html_message .= '<div class="line"><span style="display:inline-block; width: 55px; text-align: right;">'.$growth.'</span><span style="width:34px !important; display: inline-block; text-align: center;">'.$m['m__cover'].'</span>'.view__number($unique).' '.$m['m__title'].'</div>';

    //Primary Coin?
    if(in_array($linktype, $this->config->item('n___6404'))){
        $subject = $m['m__title'].' '.$growth.' for the Week of '.date("M jS", $linktime_start_timestamp);
    }

}


//Decide what to do with this?
if($player_http_request && !isset($_GET['email_trigger'])){

    echo '<div style="font-weight: bold; padding: 0 0 13px 0;">'.$subject.'</div>';
    echo $html_message;
    echo '<div style="padding: 21px 0;"><a href="'.view__app_link(12114).'?email_trigger=1">Email Me This Report</a></div>';

} else {


    $subscriber_filters = array(
        'linkup' => 12114,
        'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'linkvoid' => 0, //Not Void
        );

    //Should we limit the scope?
    if($player_http_request){
        $subscriber_filters['linkdown'] = $player_e['playerid'];
    }


    $email_recipients = 0;
    //Send email to all subscribers:
    foreach($this->Menchledger->fetch($subscriber_filters, array('linkdown')) as $subscribed_u){

        $this->Menchledger->send_dm($subscribed_u['playerid'], $subject, $html_message);
        $email_recipients++;

    }

    echo 'Report sent to '.$email_recipients.' Member'.view__s($email_recipients);

}