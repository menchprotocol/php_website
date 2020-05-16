<?php

//Calculates the weekly coins issued:
$last_week_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-7, date("Y"));
$last_week_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$last_week_start = date("Y-m-d H:i:s", $last_week_start_timestamp);
$last_week_end = date("Y-m-d H:i:s", $last_week_end_timestamp);

//IDEA
$in_coins_new_last_week = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
    'ln_profile_source_id >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    'ln_timestamp >=' => $last_week_start,
    'ln_timestamp <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$in_coins_last_week = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
    'ln_profile_source_id >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    'ln_timestamp <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$in_coins_growth_rate = format_percentage(($in_coins_last_week[0]['totals'] / ( $in_coins_last_week[0]['totals'] - $in_coins_new_last_week[0]['totals'] ) * 100) - 100);


//READ
$read_coins_new_last_week = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
    'ln_timestamp >=' => $last_week_start,
    'ln_timestamp <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$read_coins_last_week = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
    'ln_timestamp <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$read_coins_growth_rate = format_percentage(( $read_coins_last_week[0]['totals'] / ( $read_coins_last_week[0]['totals'] - $read_coins_new_last_week[0]['totals'] ) * 100)-100);



//SOURCE
$en_coins_new_last_week = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
    'ln_timestamp >=' => $last_week_start,
    'ln_timestamp <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$en_coins_last_week = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
    'ln_timestamp <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$en_coins_growth_rate = format_percentage( ($en_coins_last_week[0]['totals'] / ( $en_coins_last_week[0]['totals'] - $en_coins_new_last_week[0]['totals'] ) * 100)-100);


//ledger
$ledger_transactions_new_last_week = $this->LEDGER_model->ln_fetch(array(
    'ln_timestamp >=' => $last_week_start,
    'ln_timestamp <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$ledger_transactions_last_week = $this->LEDGER_model->ln_fetch(array(
    'ln_timestamp <=' => $last_week_end,
), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
$ledger_transactions_growth_rate = format_percentage(($ledger_transactions_last_week[0]['totals'] / ( $ledger_transactions_last_week[0]['totals'] - $ledger_transactions_new_last_week[0]['totals'] ) * 100)-100);



//Email Subject
$subject = 'MENCH 🟡 IDEAS '.( $in_coins_growth_rate > 0 ? '+' : ( $in_coins_growth_rate < 0 ? '-' : '' ) ).$in_coins_growth_rate.'% for the week of '.date("M jS", $last_week_start_timestamp);

//Email Body
$html_message = '<br />';
$html_message .= '<div>Growth report from '.date("l F jS G:i:s", $last_week_start_timestamp).' to '.date("l F jS G:i:s", $last_week_end_timestamp).' '.config_var(11079).':</div>';
$html_message .= '<br />';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🟡</b><b style="min-width:55px; display: inline-block;">'.( $in_coins_growth_rate >= 0 ? '+' : '-' ).$in_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($in_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.echo_number($in_coins_last_week[0]['totals']).'</span>)</span><a href="https://mench.com/idea" target="_blank" style="color: #ffc500; font-weight:bold; text-decoration:none;">IDEA &raquo;</a></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔴</b><b style="min-width:55px; display: inline-block;">'.( $read_coins_growth_rate >= 0 ? '+' : '-' ).$read_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($read_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.echo_number($read_coins_last_week[0]['totals']).'</span>)</span><a href="https://mench.com" target="_blank" style="color: #FC1B44; font-weight:bold; text-decoration:none;">READ &raquo;</a></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔵</b><b style="min-width:55px; display: inline-block;">'.( $en_coins_growth_rate >= 0 ? '+' : '-' ).$en_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($en_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.echo_number($en_coins_last_week[0]['totals']).'</span>)</span><a href="https://mench.com/source" target="_blank" style="color: #007AFD; font-weight:bold; text-decoration:none;">SOURCE &raquo;</a></div>';

$html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">📖</b><b style="min-width:55px; display: inline-block;">'.( $ledger_transactions_growth_rate >= 0 ? '+' : '-' ).$ledger_transactions_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($ledger_transactions_last_week[0]['totals'], 0).' Transactions" style="border-bottom:1px dotted #999999;">'.echo_number($ledger_transactions_last_week[0]['totals']).'</span>)</span><a href="https://mench.com/ledger" target="_blank" style="color: #000000; font-weight:bold; text-decoration:none;">LEDGER &raquo;</a></div>';


$html_message .= '<br />';
$html_message .= '<div>'.echo_platform_message(12691).'</div>';
$html_message .= '<div>MENCH</div>';

$subscriber_filters = array(
    'ln_profile_source_id' => 12114,
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
);

//Should we limit the scope?
if($is_player_request){
    $subscriber_filters['ln_portfolio_source_id'] = $this->session_en['en_id'];
}


$email_recipients = 0;
//Send email to all subscribers:
foreach($this->LEDGER_model->ln_fetch($subscriber_filters, array('en_portfolio')) as $subscribed_player){
    //Try fetching subscribers email:
    foreach($this->LEDGER_model->ln_fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
        'ln_profile_source_id' => 3288, //Mench Email
        'ln_portfolio_source_id' => $subscribed_player['en_id'],
    )) as $en_email){
        if(filter_var($en_email['ln_content'], FILTER_VALIDATE_EMAIL)){
            //Send Email
            $this->COMMUNICATION_model->send_email(array($en_email['ln_content']), $subject, '<div>Hi '.one_two_explode('',' ',$subscribed_player['en_name']).' 👋</div>'.$html_message);
            $email_recipients++;
        }
    }
}

echo 'Emailed Growth Reports to '.$email_recipients.' Players';