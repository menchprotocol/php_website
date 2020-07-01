<?php

//Calculates the weekly coins issued:
$e___13355 = $this->config->item('e___13355'); //MENCH OBJECTS

$last_week_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-7, date("Y"));
$last_week_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$last_week_start = date("Y-m-d H:i:s", $last_week_start_timestamp);
$last_week_end = date("Y-m-d H:i:s", $last_week_end_timestamp);

//IDEA
$i_coins_new_last_week = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12273')) . ')' => null, //IDEA COIN
    '(x__up > 0 OR x__down > 0)' => null, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    'x__time >=' => $last_week_start,
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$i_coins_last_week = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12273')) . ')' => null, //IDEA COIN
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
    'x__type IN (' . join(',', $this->config->item('n___12274')) . ')' => null, //SOURCE COIN
    'x__time >=' => $last_week_start,
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$e_coins_last_week = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12274')) . ')' => null, //SOURCE COIN
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$e_coins_growth_rate = format_percentage( ($e_coins_last_week[0]['totals'] / ( $e_coins_last_week[0]['totals'] - $e_coins_new_last_week[0]['totals'] ) * 100)-100);


$interactions_x_new_last_week = $this->X_model->fetch(array(
    'x__time >=' => $last_week_start,
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$interactions_x_last_week = $this->X_model->fetch(array(
    'x__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(x__id) as totals');
$interactions_x_growth_rate = format_percentage(($interactions_x_last_week[0]['totals'] / ( $interactions_x_last_week[0]['totals'] - $interactions_x_new_last_week[0]['totals'] ) * 100)-100);



//Email Subject
$subject = '🟡 '.$e___13355[12273]['m_name'].' '.( $i_coins_growth_rate > 0 ? '+' : ( $i_coins_growth_rate < 0 ? '-' : '' ) ).$i_coins_growth_rate.'% for the Week of '.date("M jS", $last_week_start_timestamp);

//Email Body
$html_message = '<br />';
$html_message .= '<div>Growth Report for '.date("F jS", $last_week_start_timestamp).'-'.date("jS", $last_week_end_timestamp).':</div>';
$html_message .= '<br />';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔵</b><b style="min-width:55px; display: inline-block;">'.( $e_coins_growth_rate >= 0 ? '+' : '-' ).$e_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($e_coins_last_week[0]['totals'], 0).'" style="border-bottom:1px dotted #999999;">'.view_number($e_coins_last_week[0]['totals']).'</span>)</span><a href="'.$this->config->item('base_url').'/@" target="_blank" style="color: #0000FF; font-weight:bold; text-decoration:none;">'.$e___13355[12274]['m_name'].' &raquo;</a></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🟡</b><b style="min-width:55px; display: inline-block;">'.( $i_coins_growth_rate >= 0 ? '+' : '-' ).$i_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($i_coins_last_week[0]['totals'], 0).'" style="border-bottom:1px dotted #999999;">'.view_number($i_coins_last_week[0]['totals']).'</span>)</span><a href="'.$this->config->item('base_url').'/~" target="_blank" style="color: #ffd500; font-weight:bold; text-decoration:none;">'.$e___13355[12273]['m_name'].' &raquo;</a></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔴</b><b style="min-width:55px; display: inline-block;">'.( $x_coins_growth_rate >= 0 ? '+' : '-' ).$x_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($x_coins_last_week[0]['totals'], 0).'" style="border-bottom:1px dotted #999999;">'.view_number($x_coins_last_week[0]['totals']).'</span>)</span><a href="'.$this->config->item('base_url').'" target="_blank" style="color: #FF0000; font-weight:bold; text-decoration:none;">'.$e___13355[6255]['m_name'].' &raquo;</a></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">📖</b><b style="min-width:55px; display: inline-block;">'.( $interactions_x_growth_rate >= 0 ? '+' : '-' ).$interactions_x_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($interactions_x_last_week[0]['totals'], 0).'" style="border-bottom:1px dotted #999999;">'.view_number($interactions_x_last_week[0]['totals']).'</span>)</span><a href="'.$this->config->item('base_url').'/x" target="_blank" style="color: #000000; font-weight:bold; text-decoration:none;">'.$e___13355[13362]['m_name'].' &raquo;</a></div>';


$html_message .= '<br />';
$html_message .= '<div>'.view_platform_message(12691).'</div>';
$html_message .= '<div>MENCH</div>';

$subscriber_filters = array(
    'x__up' => 12114,
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
);

//Should we limit the scope?
if($is_member_request){
    $session_e = superpower_assigned();
    $subscriber_filters['x__down'] = $session_e['e__id'];
}


$email_recipients = 0;
//Send email to all subscribers:
foreach($this->X_model->fetch($subscriber_filters, array('x__down')) as $subscribed_member){
    //Try fetching subscribers email:
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__up' => 3288, //Mench Email
        'x__down' => $subscribed_member['e__id'],
    )) as $e_email){
        if(filter_var($e_email['x__message'], FILTER_VALIDATE_EMAIL)){
            //Send Email
            $this->X_model->email_sent(array($e_email['x__message']), $subject, '<div>Hi '.one_two_explode('',' ',$subscribed_member['e__title']).' 👋</div>'.$html_message);
            $email_recipients++;
        }
    }
}

echo 'Emailed Growth Reports to '.$email_recipients.' Players';