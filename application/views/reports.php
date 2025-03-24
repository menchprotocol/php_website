<?php

//Calculates the weekly coins issued:
$e___11035 = $this->config->item('e___11035'); //Encyclopedia
$last_x_days = 7;

$link_time_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$link_time_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$link_time_start = date("Y-m-d H:i:s", $link_time_start_timestamp);
$link_time_end = date("Y-m-d H:i:s", $link_time_end_timestamp);

//Email Body
$html_message = '<div class="line">Here is what happened in the last '.$last_x_days.' day'.view__s($last_x_days).':</div>';

foreach($this->config->item('e___42263') as $link_type => $m) {

    $unique = count_link_groups($link_type, null, $link_time_end);
    $this_week = count_link_groups($link_type, $link_time_start, $link_time_end);
    if(!$unique){
        continue;
    }
    $growth = format_percentage(($unique / ( $unique - $this_week ) * 100) - 100);
    $growth = ( $growth >= 0 ? '+' : '-' ).$growth.'%';

    //Add to UI:
    $html_message .= '<div class="line"><span style="display:inline-block; width: 55px; text-align: right;">'.$growth.'</span><span style="width:34px !important; display: inline-block; text-align: center;">'.$m['m__cover'].'</span>'.view__number($unique).' '.$m['m__title'].'</div>';

    //Primary Coin?
    if(in_array($link_type, $this->config->item('n___6404'))){
        $subject = $m['m__title'].' '.$growth.' for the Week of '.date("M jS", $link_time_start_timestamp);
    }

}


//Decide what to do with this?
if($player_http_request && !isset($_GET['email_trigger'])){

    echo '<div style="font-weight: bold; padding: 0 0 13px 0;">'.$subject.'</div>';
    echo $html_message;
    echo '<div style="padding: 21px 0;"><a href="'.view__app_link(12114).'?email_trigger=1">Email Me This Report</a></div>';

} else {


    $subscriber_filters = array(
        'link_up' => 12114,
        'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'link_void' => 0, //Not Void
        );

    //Should we limit the scope?
    if($player_http_request){
        $subscriber_filters['link_down'] = $player_e['e__id'];
    }


    $email_recipients = 0;
    //Send email to all subscribers:
    foreach($this->Mench_ledger->fetch($subscriber_filters, array('link_down')) as $subscribed_u){

        $this->Mench_ledger->send_dm($subscribed_u['e__id'], $subject, $html_message);
        $email_recipients++;

    }

    echo 'Report sent to '.$email_recipients.' Member'.view__s($email_recipients);

}