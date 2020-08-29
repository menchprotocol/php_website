<?php

//Calculates the weekly coins issued:
$e___12467 = $this->config->item('e___12467'); //MENCH COINS
$last_x_days = 7;

$x__time_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$x__time_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$x__time_start = date("Y-m-d H:i:s", $x__time_start_timestamp);
$x__time_end = date("Y-m-d H:i:s", $x__time_end_timestamp);

//Email Body
$html_message = '<br />';
$html_message .= '<div>In the last '.$last_x_days.' day'.view__s($last_x_days).' we grew:</div>';
$html_message .= '<br />';

foreach($this->config->item('e___12467') as $x__type => $m) {

    //Calculate Growth Rate:
    $icon = extract_icon_color($m['m_icon'], true);
    $unique = count_unique_coins($x__type, null, $x__time_end);
    $growth = format_percentage(($unique / ( $unique - count_unique_coins($x__type, $x__time_start, $x__time_end) ) * 100) - 100);
    $growth = ( $growth >= 0 ? '+' : '-' ).$growth.'%';

    //Add to UI:
    $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:34px; text-align: center; display: inline-block;">'.$icon.'</b><b style="min-width:55px; display: inline-block;">'.$growth.'</b><span style="text-decoration:none;">TO <b>'.number_format($unique, 0).'</b> '.$m['m_title'].'</span></div>';

    //Primary Coin?
    if(in_array($x__type, $this->config->item('n___13776'))){
        $subject = $icon.' '.$m['m_title'].' '.$growth.' for the Week of '.date("M jS", $x__time_start_timestamp);
    }
}

$html_message .= '<br />';
$html_message .= '<div>'.view_12687(12691).'</div>';
$html_message .= '<div>MENCH</div>';





//Decide what to do with this?
if($is_u_request && !isset($_GET['send_email'])){

    echo '<div style="font-weight: bold; padding: 0 0 13px 0;">'.$subject.'</div>';
    echo $html_message;
    echo '<div style="padding: 21px 0;"><a href="/e/plugin/12114?send_email=1">Email Me This Report</a></div>';

} else {


    $subscriber_filters = array(
        'x__up' => 12114,
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
    );

    //Should we limit the scope?
    if($is_u_request){
        $user_e = superpower_assigned();
        $subscriber_filters['x__down'] = $user_e['e__id'];
    }


    $email_recipients = 0;
    //Send email to all subscribers:
    foreach($this->X_model->fetch($subscriber_filters, array('x__down')) as $subscribed_u){
        //Try fetching subscribers email:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Mench Email
            'x__down' => $subscribed_u['e__id'],
        )) as $e_email){
            if(filter_var($e_email['x__message'], FILTER_VALIDATE_EMAIL)){

                $this->X_model->email_sent(array($e_email['x__message']), $subject, '<div>Hi '.one_two_explode('',' ',$subscribed_u['e__title']).' 👋</div>'.$html_message);

                //Send & Log Email
                $invite_x = $this->X_model->create(array(
                    'x__type' => 12114,
                    'x__source' => $subscribed_u['e__id'],
                ));

                $email_recipients++;

                break;
            }
        }
    }

    echo 'Emailed Reports to '.$email_recipients.' User'.view__s($email_recipients);

}