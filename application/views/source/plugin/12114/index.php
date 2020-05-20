<?php

//Calculates the weekly coins issued:
$sources__12467 = $this->config->item('sources__12467'); //MENCH COINS

$last_week_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-7, date("Y"));
$last_week_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$last_week_start = date("Y-m-d H:i:s", $last_week_start_timestamp);
$last_week_end = date("Y-m-d H:i:s", $last_week_end_timestamp);

//IDEA
$idea_coins_new_last_week = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
    'read__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    'read__time >=' => $last_week_start,
    'read__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$idea_coins_last_week = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
    'read__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    'read__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$idea_coins_growth_rate = format_percentage(($idea_coins_last_week[0]['totals'] / ( $idea_coins_last_week[0]['totals'] - $idea_coins_new_last_week[0]['totals'] ) * 100) - 100);


//READ
$read_coins_new_last_week = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null, //READ COIN
    'read__time >=' => $last_week_start,
    'read__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$read_coins_last_week = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null, //READ COIN
    'read__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$read_coins_growth_rate = format_percentage(( $read_coins_last_week[0]['totals'] / ( $read_coins_last_week[0]['totals'] - $read_coins_new_last_week[0]['totals'] ) * 100)-100);



//SOURCE
$source_coins_new_last_week = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12274')) . ')' => null, //SOURCE COIN
    'read__time >=' => $last_week_start,
    'read__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$source_coins_last_week = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12274')) . ')' => null, //SOURCE COIN
    'read__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$source_coins_growth_rate = format_percentage( ($source_coins_last_week[0]['totals'] / ( $source_coins_last_week[0]['totals'] - $source_coins_new_last_week[0]['totals'] ) * 100)-100);


//interactions
$interactions_reads_new_last_week = $this->READ_model->fetch(array(
    'read__time >=' => $last_week_start,
    'read__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$interactions_reads_last_week = $this->READ_model->fetch(array(
    'read__time <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(read__id) as totals');
$interactions_reads_growth_rate = format_percentage(($interactions_reads_last_week[0]['totals'] / ( $interactions_reads_last_week[0]['totals'] - $interactions_reads_new_last_week[0]['totals'] ) * 100)-100);



//Email Subject
$subject = 'MENCH 🟡 IDEAS '.( $idea_coins_growth_rate > 0 ? '+' : ( $idea_coins_growth_rate < 0 ? '-' : '' ) ).$idea_coins_growth_rate.'% for the week of '.date("M jS", $last_week_start_timestamp);

//Email Body
$html_message = '<br />';
$html_message .= '<div>Growth report from '.date("l F jS G:i:s", $last_week_start_timestamp).' to '.date("l F jS G:i:s", $last_week_end_timestamp).' '.config_var(11079).':</div>';
$html_message .= '<br />';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🟡</b><b style="min-width:55px; display: inline-block;">'.( $idea_coins_growth_rate >= 0 ? '+' : '-' ).$idea_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($idea_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.view_number($idea_coins_last_week[0]['totals']).'</span>)</span><a href="'.$this->config->item('base_url').'idea" target="_blank" style="color: #ffc500; font-weight:bold; text-decoration:none;">'.$sources__12467[12273]['m_name'].' &raquo;</a></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔴</b><b style="min-width:55px; display: inline-block;">'.( $read_coins_growth_rate >= 0 ? '+' : '-' ).$read_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($read_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.view_number($read_coins_last_week[0]['totals']).'</span>)</span><a href="'.$this->config->item('base_url').'" target="_blank" style="color: #FC1B44; font-weight:bold; text-decoration:none;">'.$sources__12467[6255]['m_name'].' &raquo;</a></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔵</b><b style="min-width:55px; display: inline-block;">'.( $source_coins_growth_rate >= 0 ? '+' : '-' ).$source_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($source_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.view_number($source_coins_last_week[0]['totals']).'</span>)</span><a href="'.$this->config->item('base_url').'source" target="_blank" style="color: #007AFD; font-weight:bold; text-decoration:none;">'.$sources__12467[12274]['m_name'].' &raquo;</a></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">📖</b><b style="min-width:55px; display: inline-block;">'.( $interactions_reads_growth_rate >= 0 ? '+' : '-' ).$interactions_reads_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($interactions_reads_last_week[0]['totals'], 0).' Reads" style="border-bottom:1px dotted #999999;">'.view_number($interactions_reads_last_week[0]['totals']).'</span>)</span><a href="'.$this->config->item('base_url').'read/interactions" target="_blank" style="color: #000000; font-weight:bold; text-decoration:none;">INTERACTIONS &raquo;</a></div>';


$html_message .= '<br />';
$html_message .= '<div>'.view_platform_message(12691).'</div>';
$html_message .= '<div>MENCH</div>';

$subscriber_filters = array(
    'read__up' => 12114,
    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
);

//Should we limit the scope?
if($is_player_request){
    $session_en = superpower_assigned();
    $subscriber_filters['read__down'] = $session_en['source__id'];
}


$email_recipients = 0;
//Send email to all subscribers:
foreach($this->READ_model->fetch($subscriber_filters, array('source_portfolio')) as $subscribed_player){
    //Try fetching subscribers email:
    foreach($this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'read__up' => 3288, //Mench Email
        'read__down' => $subscribed_player['source__id'],
    )) as $source_email){
        if(filter_var($source_email['read__message'], FILTER_VALIDATE_EMAIL)){
            //Send Email
            $this->READ_model->send_email(array($source_email['read__message']), $subject, '<div>Hi '.one_two_explode('',' ',$subscribed_player['source__title']).' 👋</div>'.$html_message);
            $email_recipients++;
        }
    }
}

echo 'Emailed Growth Reports to '.$email_recipients.' Players';