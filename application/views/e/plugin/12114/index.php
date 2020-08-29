<?php

//Calculates the weekly coins issued:
$e___12467 = $this->config->item('e___12467'); //MENCH COINS
$last_x_days = 7;

$last_week_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$last_week_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$last_week_start = date("Y-m-d H:i:s", $last_week_start_timestamp);
$last_week_end = date("Y-m-d H:i:s", $last_week_end_timestamp);

//IDEA
$i_coins_new_last_week = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
    '(x__up > 0 OR x__down > 0)' => null, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    'x__time >=' => $last_week_start,
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$i_coins_last_week = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
    '(x__up > 0 OR x__down > 0)' => null, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$i_coins_growth_rate = format_percentage(($i_coins_last_week[0]['totals'] / ( $i_coins_last_week[0]['totals'] - $i_coins_new_last_week[0]['totals'] ) * 100) - 100);


//DISCOVER
$x_coins_new_last_week = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
    'x__time >=' => $last_week_start,
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$x_coins_last_week = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$x_coins_growth_rate = format_percentage(( $x_coins_last_week[0]['totals'] / ( $x_coins_last_week[0]['totals'] - $x_coins_new_last_week[0]['totals'] ) * 100)-100);



//SOURCE
$e_coins_new_last_week = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___13548')) . ')' => null, //UNIQUE SOURCES
    'x__time >=' => $last_week_start,
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$e_coins_last_week = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___13548')) . ')' => null, //UNIQUE SOURCES
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$e_coins_growth_rate = format_percentage( ($e_coins_last_week[0]['totals'] / ( $e_coins_last_week[0]['totals'] - $e_coins_new_last_week[0]['totals'] ) * 100)-100);


//Email Subject
$subject = extract_icon_color($e___12467[6255]['m_icon'], true).' '.$e___12467[6255]['m_title'].' +'.$x_coins_growth_rate.'% for the Week of '.date("M jS", $last_week_start_timestamp);

//Email Body
$html_message = '<br />';
$html_message .= '<div>In the last '.$last_x_days.' day'.view__s($last_x_days).' we grew:</div>';
$html_message .= '<br />';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:34px; text-align: center; display: inline-block;">'.extract_icon_color($e___12467[12274]['m_icon'], true).'</b><b style="min-width:55px; display: inline-block;">'.( $e_coins_growth_rate >= 0 ? '+' : '-' ).$e_coins_growth_rate.'%</b><span style="text-decoration:none;">TO '.view_number($e_coins_last_week[0]['totals']).' '.$e___12467[12274]['m_title'].'</span></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:34px; text-align: center; display: inline-block;">'.extract_icon_color($e___12467[12273]['m_icon'], true).'</b><b style="min-width:55px; display: inline-block;">'.( $i_coins_growth_rate >= 0 ? '+' : '-' ).$i_coins_growth_rate.'%</b><span style="text-decoration:none;">TO '.view_number($i_coins_last_week[0]['totals']).' '.$e___12467[12273]['m_title'].'</span></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:34px; text-align: center; display: inline-block;">'.extract_icon_color($e___12467[6255]['m_icon'], true).'</b><b style="min-width:55px; display: inline-block;">'.( $x_coins_growth_rate >= 0 ? '+' : '-' ).$x_coins_growth_rate.'%</b><span style="text-decoration:none;">TO '.view_number($x_coins_last_week[0]['totals']).' '.$e___12467[6255]['m_title'].'</span></div>';




$html_message .= '<br />';
$html_message .= '<div>'.view_12687(12691).'</div>';
$html_message .= '<div>MENCH</div>';

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

echo 'Emailed Growth Reports to '.$email_recipients.' Users';