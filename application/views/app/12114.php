<?php

//Flip:
$counter = 0;
$member_e = superpower_unlocked();
foreach($this->config->item('e___14870') as $e__id => $m) {

    //Hosted Domains:

    foreach($this->config->item('e___14925') as $setting_e__id => $setting_m) {

        //Setting
        foreach($this->X_model->fetch(array(
            'x__up' => $e__id,
            'x__down' => $setting_e__id,
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0, 0) as $x){

            $counter++;


            $this->X_model->update($x['x__id'], array(
                'x__status' => 6173, //Transaction Deleted
            ), $member_e['e__id'], 10673);

        }

    }
}

echo $counter.' Total<br />';

//Calculates the weekly coins issued:
$e___14874 = $this->config->item('e___14874'); //COINS
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$last_x_days = 7;

$x__time_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-$last_x_days, date("Y"));
$x__time_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

$x__time_start = date("Y-m-d H:i:s", $x__time_start_timestamp);
$x__time_end = date("Y-m-d H:i:s", $x__time_end_timestamp);

//Email Body
$html_message = '<br />';
$html_message .= '<div>In the last '.$last_x_days.' day'.view__s($last_x_days).' '.$e___11035[14874]['m__title'].' grew:</div>';
$html_message .= '<br />';

$html_message .= '<div style="padding-bottom:10px;"><span style="min-width:125px; display: inline-block;">&nbsp;</span><span style="min-width:62px; display: inline-block;">&nbsp;</span><span style="text-decoration:none;"><span style="min-width:62px; display: inline-block;">New</span>Total</span></div>';

foreach($this->config->item('e___14874') as $x__type => $m) {

    //Calculate Growth Rate:
    if(substr_count($m['m__cover'], '6255')>0){
        $icon = '🔴';
    } elseif(substr_count($m['m__cover'], '12273')>0){
        $icon = '🟡';
    } elseif(substr_count($m['m__cover'], '12274')>0){
        $icon = '🔵';
    }

    $unique = count_unique_coins($x__type, null, $x__time_end);
    $this_week = count_unique_coins($x__type, $x__time_start, $x__time_end);
    $growth = format_percentage(($unique / ( $unique - $this_week ) * 100) - 100);
    $growth = ( $growth >= 0 ? '+' : '-' ).$growth.'%';

    //Add to UI:
    $html_message .= '<div style="padding-bottom:10px; text-decoration:none;"><span style="min-width:125px; display: inline-block;">'.$icon.' '.$m['m__title'].'</span><span style="min-width:62px; display: inline-block;">'.$growth.'</span><span style="min-width:62px; display: inline-block;">'.number_format($this_week, 0).'</span>'.number_format($unique, 0).'</div>';

    //Primary Coin?
    if(in_array($x__type, $this->config->item('n___13776'))){
        $subject = $icon.' '.$m['m__title'].' '.$growth.' for the Week of '.date("M jS", $x__time_start_timestamp);
    }

}

$html_message .= '<br />';
$html_message .= '<div>'.view_shuffle_message(12691).'</div>';
$html_message .= '<div>'.get_domain('m__title').'</div>';





//Decide what to do with this?
if($is_u_request && !isset($_GET['send_email'])){

    echo '<div style="font-weight: bold; padding: 0 0 13px 0;">'.$subject.'</div>';
    echo $html_message;
    echo '<div style="padding: 21px 0;"><a href="/-12114?send_email=1">Email Me This Report</a></div>';

} else {


    $subscriber_filters = array(
        'x__up' => 12114,
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
    );

    //Should we limit the scope?
    if($is_u_request){
        $member_e = superpower_unlocked();
        $subscriber_filters['x__down'] = $member_e['e__id'];
    }


    $email_recipients = 0;
    //Send email to all subscribers:
    foreach($this->X_model->fetch($subscriber_filters, array('x__down')) as $subscribed_u){
        //Try fetching subscribers email:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
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

    echo 'Emailed Reports to '.$email_recipients.' Member'.view__s($email_recipients);

}