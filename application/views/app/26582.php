<?php


//Fetch Sources who started or were blocked:
$subs = '';
$emails = '';
$total_subs = 0;
$already_added = array();
$all_recipients = array();
$sms_limit = view_memory(6404,27891);

if(isset($_GET['i__id']) && strlen($_GET['i__id'])){
    $is = $this->I_model->fetch(array(
        'i__id IN (' . $_GET['i__id'] . ')' => null,
        'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    ));
    if(count($is)){
        echo '<h2><a href="/i/i_go/'.$is[0]['i__id'].'"><u>'.$is[0]['i__title'].'</u></a></h2>';
    }
}

if(isset($_GET['e__id']) && strlen($_GET['e__id'])){
    $es = $this->E_model->fetch(array(
        'e__id IN (' . $_GET['e__id'] . ')' => null,
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($es)){
        echo '<h2><a href="/@'.$es[0]['e__id'].'"><u>'.$es[0]['e__title'].'</u></a></h2>';
    }
}

$query = array();

if(isset($_GET['i__id']) && strlen($_GET['i__id'])){
    $query = array_merge($query, $this->X_model->fetch(array(
        'x__type IN (' . join(',', $this->config->item('n___26582')) . ')' => null, //Send Instant Message
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        'x__left IN (' . $_GET['i__id'] . ')' => null, //PUBLIC
    ), array('x__source'), 0, 0, array('x__id' => 'DESC')));
}

if(isset($_GET['e__id']) && strlen($_GET['e__id'])){
    $query = array_merge($query, $this->X_model->fetch(array(
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        'x__up IN (' . $_GET['e__id'] . ')' => null,
    ), array('x__down'), 0, 0, array('x__id' => 'DESC')));
}

$unsubscribed = 0;
$email_count = 0;
$phone_count = 0;
$phone_array = array();
$email_array = array();

foreach($query as $subscriber){

    //Make sure not already added AND not unsubscribed:
    if(in_array($subscriber['e__id'], $already_added)){
        continue;
    }
    if (count($this->X_model->fetch(array(
        'x__up' => 26583, //Unsubscribed
        'x__down' => $subscriber['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    )))) {
        $unsubscribed++;
        continue;
    }

    //Any exclusions?
    if(isset($_GET['exclude_e']) && strlen($_GET['exclude_e']) && count($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__up IN (' . $_GET['exclude_e'] . ')' => null,
            'x__down' => $subscriber['e__id'],
        )))){
        continue;
    }

    if(isset($_GET['include_e']) && strlen($_GET['include_e']) && !count($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__up IN (' . $_GET['include_e'] . ')' => null,
            'x__down' => $subscriber['e__id'],
        )))){
        continue;
    }

    array_push($already_added, $subscriber['e__id']);

    //Fetch email & phone:
    $e_emails = $this->X_model->fetch(array(
        'x__up' => 3288, //Email
        'x__down' => $subscriber['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    ));
    $e_email = ( count($e_emails) && filter_var($e_emails[0]['x__message'], FILTER_VALIDATE_EMAIL) ? $e_emails[0]['x__message'] : false );
    $e_phones = $this->X_model->fetch(array(
        'x__up' => 4783, //Phone
        'x__down' => $subscriber['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    ));
    //$e_phone = ( count($e_phones) && strlen(preg_replace('/[^0-9]/', '', $e_phones[0]['x__message']))>=10 ? preg_replace('/[^0-9]/', '', $e_phones[0]['x__message']) : false );
    $e_phone = ( count($e_phones) && strlen($e_phones[0]['x__message'])>=10 ? $e_phones[0]['x__message'] : false );

    /*
    if(!isset($_GET['phone']) || ($_GET['phone']==1 && $e_phone) || ($_GET['phone']==0 && !$e_phone)){
        //Add to sub list:
        $total_subs++;
        $subs .= one_two_explode('',' ', $subscriber['e__title'])."\t".$e_email."\t".$e_phone."\n";
        $emails .= ( strlen($emails) ? ", " : '' ).$e_email;
    }
    */

    if(!$e_email && !$e_phone){
        //No way to reach them:
        continue;
    }

    $total_subs++;
    if($e_email){
        $email_count++;
        $emails .= ( strlen($emails) ? ", " : '' ).$e_email;
    }
    if($e_phone){
        $phone_count++;
    }

    $first_name = one_two_explode('',' ', $subscriber['e__title']);
    array_push($all_recipients,  intval($subscriber['e__id']));

    $subs .= $first_name."\t".$e_email."\t".$e_phone."\n";

}

echo '<form action="" method="GET">';

echo '<table class="table table-sm maxout"><tr>';

//ANY IDEA
echo '<td><div style="padding-right:5px;">';
echo '<span class="mini-header">Ideas:</span>';
echo '<input type="text" name="i__id" placeholder="idea1id,idea2id" value="' . ((isset($_GET['i__id'])) ? $_GET['i__id'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">Sources:</span><input type="text" name="e__id" value="' . ((isset($_GET['e__id'])) ? $_GET['e__id'] : '') . '" class="form-control border"></td>';

echo '</tr><tr>';

echo '<td><div style="padding-right:5px;">';
echo '<span class="mini-header">Includes Profile:</span>';
echo '<input type="text" name="include_e" placeholder="idea1id,idea2id" value="' . ((isset($_GET['include_e'])) ? $_GET['include_e'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">Excludes Profile:</span><input type="text" name="exclude_e" value="' . ((isset($_GET['exclude_e'])) ? $_GET['exclude_e'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';

echo '<input type="submit" class="btn btn-6255" value="Apply Filters" />';

echo '&nbsp;&nbsp;<a href="javascript:void(0);" onclick="$(\'.subscriber_data\').toggleClass(\'hidden\');">'.$total_subs.' Unique Recipients = '.$email_count.' Emails + '.$phone_count.' SMS</a>';

echo '</form>';


echo '<textarea class="mono-space subscriber_data hidden" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 10px;">'.$subs.'</textarea>';
echo '<textarea class="mono-space subscriber_data hidden" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 10px;">'.$emails.'</textarea>';


echo '<div style="padding: 55px 10px 13px;">';
echo '<div style="padding: 10px 0;"><input type="text" class="form-control white-border" id="message_subject" onkeyup="countChar()" value="'.( isset($_GET['message_subject']) ? $_GET['message_subject'] : '' ).'" /></div>';
echo '<textarea class="form-control white-border" id="message_text" onkeyup="countChar()">'.( isset($_GET['message_text']) ? $_GET['message_text'] : '' ).'</textarea>';
echo '</div>';
echo '<div id="charNum"></div>';

echo '<div id="send_message_btn"><a class="btn btn-6255" href="javascript:void(0);" onclick="send_message();">Send Message to '.$total_subs.' <i class="fas fa-arrow-right"></i></a></div>';

echo '<div id="message_result"></div>';

echo '<div></div>';

?>

<script type="text/javascript">

    $(document).ready(function () {
        countChar();
    });

    function countChar() {
        $('#charNum').html(( $('#message_subject').val().length + $('#message_text').val().length + 2 /* For the [: ] that connects the subject to body in SMS */ )+'/<?= $sms_limit ?> Characters (Subject + Text)');
    }

    <?= ' var all_recipients = ' . json_encode($all_recipients) . ';' ?>

    var is_processing = false;
    function send_message(){

        if(is_processing){
            alert('currently processing... be patient :)');
            return false;
        }

        is_processing = true;

        //Make sure there is a message:
        if(!$('#message_text').val().length){
            alert('You must enter a message before sending...');
            return false;
        }

        $('#message_result').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span> Sending Messages...');

        $.post("/x/x_send_message", {
            all_recipients:all_recipients,
            message_subject: $('#message_subject').val(),
            message_text: $('#message_text').val(),
        }, function (data) {

            if (data.status) {
                //Hide button:
                $('#send_message_btn').addClass('hidden');
                $('#message_result').html(data.message);
            } else {
                //Show error:
                is_processing = false; //Allow resubmissions
                $('#message_result').html('ERROR: '+data.message);
            }

        });

    }

</script>