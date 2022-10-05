<?php

foreach(array('i__id','e__id','exclude_e','include_e','continue_x__id') as $input){
    if(!isset($_GET[$input])){
        $_GET[$input] = '';
    }
}

//Show Titles:
if(strlen($_GET['i__id'])){
    foreach($this->I_model->fetch(array(
        'i__id IN (' . $_GET['i__id'] . ')' => null,
        'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    )) as $i){
        echo '<h2><a href="/i/i_go/'.$i['i__id'].'"><span class="icon-block-img">'.view_cover(12273,$i['i__cover']).'</span> '.$i['i__title'].'</a></h2>';
    }
}
if(strlen($_GET['e__id'])){
    foreach($this->E_model->fetch(array(
        'e__id IN (' . $_GET['e__id'] . ')' => null,
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    )) as $e){
        echo '<h2><a href="/@'.$e['e__id'].'"><span class="icon-block-img">'.view_cover(12274,$e['e__cover']).'</span> '.$e['e__title'].'</a></h2>';
    }
}

$message_list = message_list($_GET['i__id'], $_GET['e__id'], $_GET['exclude_e'], $_GET['include_e'], $_GET['continue_x__id']);
$e___6287 = $this->config->item('e___6287'); //APP



echo '<div style="padding: 10px;"><a href="javascript:void(0);" onclick="$(\'.filter_box\').toggleClass(\'hidden\')"><i class="fad fa-filter"></i> Toggle Filters</a> | <a href="/-13790?i__id='.$_GET['i__id'].'&e__id='.$_GET['e__id'].'&include_e='.$_GET['include_e'].'&exclude_e='.$_GET['exclude_e'].'&continue_x__id='.$_GET['continue_x__id'].'">'.$e___6287[13790]['m__cover'].' '.$e___6287[13790]['m__title'].'</a></div>';

echo '<form action="" method="GET" class="filter_box hidden" style="padding: 10px">';
echo '<table class="table table-sm maxout filter_table"><tr>';

//ANY IDEA
echo '<td><div style="padding-right:5px;">';
echo '<span class="mini-header">Discovered Idea(s):</span>';
echo '<input type="text" name="i__id" placeholder="id1,id2" value="' . $_GET['i__id'] . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">Belongs to Source(s):</span><input type="text" name="e__id" placeholder="id1,id2" value="' . $_GET['e__id'] . '" class="form-control border"></td>';

echo '</tr><tr>';

echo '<td><div style="padding-right:5px;">';
echo '<span class="mini-header">Includes Profile Source:</span>';
echo '<input type="text" name="include_e" placeholder="id1,id2" value="' . $_GET['include_e'] . '" class="form-control border">';
echo '</div></td>';

echo '<td><div style="padding-right:5px;">';
echo '<span class="mini-header">Continue X ID:</span>';
echo '<input type="text" name="continue_x__id" placeholder="id1" value="' . $_GET['continue_x__id'] . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">Excludes Profile Source:</span><input type="text" name="exclude_e" placeholder="id1,id2" value="' . $_GET['exclude_e'] . '" class="form-control border"></td>';

echo '</tr><tr>';

echo '<td class="standard-bg"><input type="submit" class="btn btn-default" value="Apply" /></td>';
echo '<td class="standard-bg">&nbsp;</td>';

echo '</tr></table>';

echo '</form>';


echo '<div style="padding: 10px"><a href="javascript:void(0);" onclick="$(\'.subscriber_data\').toggleClass(\'hidden\');"><i class="fad fa-search-plus"></i> '.$message_list['unique_users_count'].' Unique Recipients = '.$message_list['email_count'].' Emails + '.$message_list['phone_count'].' SMS</a></div>';

echo '<textarea class="mono-space subscriber_data hidden" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 0;">'.$message_list['full_list'].'</textarea>';
echo '<textarea class="mono-space subscriber_data hidden" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 0;">'.$message_list['email_list'].'</textarea>';


echo '<div style="padding: 0 10px 13px;">';
echo '<div style="padding: 10px 0;"><input type="text" class="form-control white-border" id="message_subject" placeholder="Subject" onkeyup="countChar()" value="'.( isset($_GET['message_subject']) ? $_GET['message_subject'] : '' ).'" /></div>';



echo '<div style="border:3px solid #000000; padding:8px; border-radius: 0;">';
echo '<p>'.view_shuffle_message(29749).' '.$member_e['e__title'].' '.view_shuffle_message(29750).'</p>';
echo '<textarea class="form-control" id="message_text" placeholder="Body" style="height:147px" onkeyup="countChar()">'.( isset($_GET['message_text']) ? $_GET['message_text'] : '' ).'</textarea>';
echo '<p>'.view_shuffle_message(12691).'</p>';
echo '<p>'.get_domain('m__title', $member_e['e__id']).'</p>';
echo '</div>';

echo '<div id="charNum"></div>';


echo '</div>';

echo '<input type="datetime-local" id="meeting-time" value="'.date('Y-m-d\TH:i').'">';

echo '<div id="send_message_btn" style="padding: 10px;"><a class="btn btn-default" href="javascript:void(0);"  onclick="send_message();">Schedule Message for '.$message_list['unique_users_count'].' Members <i class="fas fa-arrow-right"></i></a></div>';

echo '<div id="message_result"></div>';

echo '<div></div>';

//Past message Sent:
echo '<h2>Past Messages</h2>';

echo '<table class="table table-condensed table-striped">';
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 26582, //Instant Messages
), array('x__source'), 0, 0) as $fetched_e){

    //Count Emails & Messages from Ledger:
    $email_success = $this->X_model->fetch(array(
        'x__type' => 29399,
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__reference' => $fetched_e['x__id'],
    ), array(), 0, 0, array(), 'COUNT(x__id) as totals');
    $sms_success = $this->X_model->fetch(array(
        'x__type' => 27676,
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__reference' => $fetched_e['x__id'],
    ), array(), 0, 0, array(), 'COUNT(x__id) as totals');
    $sms_fail = $this->X_model->fetch(array(
        'x__type' => 27678,
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__reference' => $fetched_e['x__id'],
    ), array(), 0, 0, array(), 'COUNT(x__id) as totals');


    $x__metadata = unserialize($fetched_e['x__metadata']);
    echo '<tr>';
    echo '<td><a href="/-4341?x__id='.$fetched_e['x__id'].'">'.$fetched_e['x__id'].'</a></td>';
    echo '<td>'. $fetched_e['x__time'].'</td>';
    echo '<td><a href="/@'.$fetched_e['x__source'].'">'. $fetched_e['e__title'].'</a></td>';
    echo '<td>'.( isset($x__metadata['stats']['target']) ? $x__metadata['stats']['target'] : 0 ).'<br />Targets</td>';
    echo '<td><a href="/-12722?x__id='.$fetched_e['x__id'].'">'.$x__metadata['stats']['unique'].'<br />Uniques</a></td>';
    echo '<td>'.$email_success[0]['totals'].'/'.$x__metadata['stats']['email_count'].'<br />Emails'.( isset($x__metadata['stats']['target']) && $x__metadata['stats']['target']>$email_success[0]['totals'] ? '<div><a href="/-26582?continue_x__id='.$fetched_e['x__id'].'">Resume</a></div>' : '' ).'</td>';
    echo '<td>'.$sms_success[0]['totals'].'/'.$x__metadata['stats']['phone_count'].'<br />SMS'.( $sms_fail[0]['totals']>0 ? '<br />'.$sms_fail[0]['totals'].' FAILED' : '' ).'</td>';
    echo '</tr>';

    echo '<tr></tr>';

    echo '<tr>';
    echo '<td colspan="7">'.nl2br($fetched_e['x__message']).( isset($x__metadata['message_text']) && !substr_count($fetched_e['x__message'], $x__metadata['message_text']) ? '<hr />'.nl2br($x__metadata['message_text']) : '' ).'</td>';
    echo '</tr>';

}
echo '</table>';

?>

<script type="text/javascript">

    $(document).ready(function(){
        countChar();
        set_autosize($('#message_text'));
    });

    function countChar() {
        $('#charNum').html(( $('#message_subject').val().length + $('#message_text').val().length + 2 /* For the [: ] that connects the subject to body in SMS */ )+'/<?= view_memory(6404,27891) ?> Characters (Subject + Body)');
    }

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
            i__id: '<?= $_GET['i__id'] ?>',
            e__id: '<?= $_GET['e__id'] ?>',
            exclude_e: '<?= $_GET['exclude_e'] ?>',
            include_e: '<?= $_GET['include_e'] ?>',
            continue_x__id: '<?= $_GET['continue_x__id'] ?>',
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