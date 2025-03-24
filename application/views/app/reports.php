<?php

//Calculates the weekly coins issued:
$e___11035 = $this->config->item('e___11035'); //Encyclopedia
$last_x_days = 7;

$LinkTime_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$LinkTime_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$LinkTime_start = date("Y-m-d H:i:s", $LinkTime_start_timestamp);
$LinkTime_end = date("Y-m-d H:i:s", $LinkTime_end_timestamp);

//Email Body
$html_message = '<div class="line">Here is what happened in the last '.$last_x_days.' day'.view__s($last_x_days).':</div>';

foreach($this->config->item('e___42263') as $LinkType => $m) {

    $unique = count_link_groups($LinkType, null, $LinkTime_end);
    $this_week = count_link_groups($LinkType, $LinkTime_start, $LinkTime_end);
    if(!$unique){
        continue;
    }
    $growth = format_percentage(($unique / ( $unique - $this_week ) * 100) - 100);
    $growth = ( $growth >= 0 ? '+' : '-' ).$growth.'%';

    //Add to UI:
    $html_message .= '<div class="line"><span style="display:inline-block; width: 55px; text-align: right;">'.$growth.'</span><span style="width:34px !important; display: inline-block; text-align: center;">'.$m['m__cover'].'</span>'.view__number($unique).' '.$m['m__title'].'</div>';

    //Primary Coin?
    if(in_array($LinkType, $this->config->item('n___6404'))){
        $subject = $m['m__title'].' '.$growth.' for the Week of '.date("M jS", $LinkTime_start_timestamp);
    }

}


//Decide what to do with this?
if($player_http_request && !isset($_GET['email_trigger'])){

    echo '<div style="font-weight: bold; padding: 0 0 13px 0;">'.$subject.'</div>';
    echo $html_message;
    echo '<div style="padding: 21px 0;"><a href="'.view__app_link(12114).'?email_trigger=1">Email Me This Report</a></div>';

} else {


    $subscriber_filters = array(
        'LinkUp' => 12114,
        'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    );

    //Should we limit the scope?
    if($player_http_request){
        $subscriber_filters['LinkDown'] = $player_e['e__id'];
    }


    $email_recipients = 0;
    //Send email to all subscribers:
    foreach($this->Mench_ledger->fetch($subscriber_filters, array('LinkDown')) as $subscribed_u){

        $this->Mench_ledger->send_dm($subscribed_u['e__id'], $subject, $html_message);
        $email_recipients++;

    }

    echo 'Report sent to '.$email_recipients.' Member'.view__s($email_recipients);

}