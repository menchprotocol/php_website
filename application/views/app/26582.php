<?php

if(!$is_u_request || isset($_GET['cron'])){

    //Look for messages to process, if any:
    foreach($this->X_model->fetch(array(
        'x__access' => 6175, //Pending
        'x__type' => 26582, //Send Instant Message
        'x__time <=' => date('Y-m-d H:i:s'), //Time to send it
    )) as $send_message){

        //Mark as sending so other cron job does not pick this up:
        $this->X_model->update($send_message['x__id'], array(
            'x__access' => 6176, //Published
        ));

        $x__metadata = unserialize($send_message['x__metadata']);

        //Determine Recipients:
        $message_list = message_list($x__metadata['i__id'], $x__metadata['e__id'], $x__metadata['exclude_e'], $x__metadata['include_e'], $x__metadata['exclude_i'], $x__metadata['include_i']);

        //Loop through all contacts and send messages:
        $stats = array(
            'target' => count($message_list['unique_users_id']),
            'unique' => 0,
            'phone_count' => 0,
            'error_count' => 0,
            'email_count' => 0,
        );

        foreach($message_list['unique_users_id'] as $send_e__id){

            $results = $this->X_model->send_dm($send_e__id, $x__metadata['message_subject'], $x__metadata['message_text'], array('x__reference' => $send_message['x__id']), 0, $send_message['x__website']);

            if($results['status']){
                $stats['unique']++;
                $stats['email_count'] += $results['email_count'];
                $stats['phone_count'] += $results['phone_count'];
            } else {
                $stats['error_count']++;
            }
        }

        //Save final results:
        $this->X_model->update($send_message['x__id'], array(
            'x__metadata' => array(
                'stats' => $stats,
                'all_recipients' => $message_list['unique_users_id'],
            ),
        ));

        //Show result:
        echo $send_message['x__id'].' sent '.$stats['unique'].' messages: '.$stats['email_count'].' Emails & '.$stats['phone_count'].' SMS';

    }

} else {

    //Show status of current messages:

    foreach(array('i__id','e__id','exclude_e','include_e','exclude_i','include_i') as $input){
        if(!isset($_GET[$input])){
            $_GET[$input] = '';
        }
    }

    //Show Titles:
    if(strlen($_GET['i__id'])){
        foreach($this->I_model->fetch(array(
            'i__id IN (' . $_GET['i__id'] . ')' => null,
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        )) as $i){
            echo '<h2><a href="/~'.$i['i__id'].'">'.$i['i__title'].'</a></h2>';
        }
    }
    if(strlen($_GET['e__id'])){
        foreach($this->E_model->fetch(array(
            'e__id IN (' . $_GET['e__id'] . ')' => null,
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        )) as $e){
            echo '<h2><a href="/@'.$e['e__id'].'"><span class="icon-block-img">'.view_cover($e['e__cover'], true).'</span> '.$e['e__title'].'</a></h2>';
        }
    }

    $message_list = message_list($_GET['i__id'], $_GET['e__id'], $_GET['exclude_e'], $_GET['include_e'], $_GET['exclude_i'], $_GET['include_i']);
    $e___6287 = $this->config->item('e___6287'); //APP
    $e___6186 = $this->config->item('e___6186'); //Transaction Status

    $twilio_setup = website_setting(30859) && website_setting(30860) && website_setting(27673);


    echo '<div style="padding: 10px;"><a href="javascript:void(0);" onclick="$(\'.filter_box\').toggleClass(\'hidden\')"><i class="fad fa-filter"></i> Toggle Filters</a> | <a href="/-13790?i__id='.$_GET['i__id'].'&e__id='.$_GET['e__id'].'&include_e='.$_GET['include_e'].'&exclude_e='.$_GET['exclude_e'].'&include_i='.$_GET['include_i'].'&exclude_i='.$_GET['exclude_i'].'">'.$e___6287[13790]['m__cover'].' '.$e___6287[13790]['m__title'].'</a> | '.( $twilio_setup ? '<span><i class="fas fa-check-circle"></i> Twilio Activated</span>' : '<span style="color:#FF0000;"><i class="fas fa-times-circle"></i> Twilio SMS is Pending Setup</span>' ).'</div>';

    echo '<form action="" method="GET" class="filter_box hidden" style="padding: 10px">';
    echo '<table class="table table-sm maxout filter_table"><tr>';

    //ANY IDEA
    echo '<td><div>';
    echo '<span class="mini-header">Discovered Idea(s):</span>';
    echo '<input type="text" name="i__id" placeholder="id1,id2" value="' . $_GET['i__id'] . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">Belongs to Source(s):</span><input type="text" name="e__id" placeholder="id1,id2" value="' . $_GET['e__id'] . '" class="form-control border"></td>';

    echo '</tr><tr>';

    echo '<td><div>';
    echo '<span class="mini-header">Includes Source(s):</span>';
    echo '<input type="text" name="include_e" placeholder="id1,id2" value="' . $_GET['include_e'] . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">Excludes Source(s):</span><input type="text" name="exclude_e" placeholder="id1,id2" value="' . $_GET['exclude_e'] . '" class="form-control border"></td>';

    echo '</tr><tr>';

    echo '<td><div>';
    echo '<span class="mini-header">Discovered Idea(s):</span>';
    echo '<input type="text" name="include_i" placeholder="id1,id2" value="' . $_GET['include_i'] . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">Undiscovered Idea(s):</span><input type="text" name="exclude_i" placeholder="id1,id2" value="' . $_GET['exclude_i'] . '" class="form-control border"></td>';

    echo '</tr><tr>';

    echo '<td class="standard-bg"><input type="submit" class="btn btn-default" value="Apply" /></td>';
    echo '<td class="standard-bg">&nbsp;</td>';

    echo '</tr></table>';

    echo '</form>';


    echo '<div style="padding: 10px"><a href="javascript:void(0);" onclick="$(\'.subscriber_data\').toggleClass(\'hidden\');"><i class="fad fa-search-plus"></i> '.$message_list['unique_users_count'].' Unique Recipients = '.$message_list['email_count'].' Emails + '.$message_list['phone_count'].' SMS</a></div>';

    echo '<textarea class="mono-space subscriber_data hidden" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 21px;">'.$message_list['full_list'].'</textarea>';
    echo '<textarea class="mono-space subscriber_data hidden" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 21px;">'.$message_list['email_list'].'</textarea>';


    echo '<div style="padding: 0 10px 13px;">';
    echo '<div style="padding: 10px 0;"><input type="text" class="form-control white-border" id="message_subject" placeholder="Subject" onkeyup="countChar()" value="'.( isset($_GET['message_subject']) ? $_GET['message_subject'] : '' ).'" /></div>';



    echo '<div style="border:1px solid #000000; padding:8px; border-radius: 21px;">';
    echo '<p>'.view_shuffle_message(29749).' '.$member_e['e__title'].' '.view_shuffle_message(29750).'</p>';
    echo '<textarea class="form-control" id="message_text" placeholder="Body" style="height:147px" onkeyup="countChar()">'.( isset($_GET['message_text']) ? $_GET['message_text'] : '' ).'</textarea>';
    echo '<p>'.view_shuffle_message(12691).'</p>';
    echo '<p>'.get_domain('m__title', $member_e['e__id']).'</p>';
    echo '</div>';

    echo '<div id="msgNum"></div>';


    echo '<input type="datetime-local" id="message_time" value="'.date('Y-m-d\TH:i', (time()+3600)).'" style="border:1px solid #000000; padding:8px; border-radius: 21px; margin-top:21px;">';

    echo '</div>';


    echo '<div id="schedule_message_btn" style="padding: 10px;"><a class="btn btn-default" href="javascript:void(0);"  onclick="schedule_message();">Schedule for '.$message_list['unique_users_count'].' Members <i class="fas fa-arrow-right"></i></a></div>';

    echo '<div id="message_result"></div>';

    echo '<div></div>';

    //Past message Sent:
    echo '<h2>Scheduled Messages</h2>';

    echo '<table class="table table-condensed table-striped">';
    $displayed = false;
    foreach($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //Active
        'x__type' => 26582, //Instant Messages
        'x__website' => website_setting(0),
    ), array('x__creator')) as $fetched_e){

        $displayed = true;
        //Count Emails & Messages from Ledger:
        $email_success = $this->X_model->fetch(array(
            'x__type' => 29399,
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__reference' => $fetched_e['x__id'],
        ), array(), 0, 0, array(), 'COUNT(x__id) as totals');
        $sms_success = $this->X_model->fetch(array(
            'x__type' => 27676,
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__reference' => $fetched_e['x__id'],
        ), array(), 0, 0, array(), 'COUNT(x__id) as totals');
        $sms_fail = $this->X_model->fetch(array(
            'x__type' => 27678,
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__reference' => $fetched_e['x__id'],
        ), array(), 0, 0, array(), 'COUNT(x__id) as totals');


        $x__metadata = unserialize($fetched_e['x__metadata']);
        echo '<tr class="semail'.$fetched_e['x__id'].'">';
        echo '<td><a href="/-4341?x__id='.$fetched_e['x__id'].'">'.$fetched_e['x__id'].'</a> <a href="javascript:x_schedule_delete('.$fetched_e['x__id'].')">x</a></td>';
        echo '<td>'.$e___6186[$fetched_e['x__access']]['m__cover'].'</td>';
        echo '<td>'. substr($fetched_e['x__time'], 0, 19).'<br />Domain: <a href="/@'.$fetched_e['x__website'].'">@'.$fetched_e['x__website'].'</a></td>';
        echo '<td><a href="/@'.$fetched_e['x__creator'].'">'. $fetched_e['e__title'].'</a></td>';
        echo '<td>'.@intval($x__metadata['stats']['target']).'<br />Targets</td>';
        echo '<td><a href="/-12722?x__id='.$fetched_e['x__id'].'">'.@intval($x__metadata['stats']['unique']).'<br />Uniques</a></td>';
        echo '<td>'.$email_success[0]['totals'].'/'.@intval($x__metadata['stats']['email_count']).'<br />Emails</td>';
        echo '<td>'.$sms_success[0]['totals'].'/'.@intval($x__metadata['stats']['phone_count']).'<br />SMS'.( $sms_fail[0]['totals']>0 ? '<br />'.$sms_fail[0]['totals'].' FAILED' : '' ).'</td>';
        echo '</tr>';

        echo '<tr class="semail'.$fetched_e['x__id'].'"></tr>';


        echo '<tr class="semail'.$fetched_e['x__id'].'"><td colspan="8">'.nl2br($fetched_e['x__message']).( isset($x__metadata['message_text']) ? '<hr />'.nl2br($x__metadata['message_text']) : '' ).'</td></tr>';

    }
    if(!$displayed){
        echo '<p>Nothing yet...</p>';
    }
    echo '</table>';

    ?>

    <script type="text/javascript">

        $(document).ready(function(){
            countChar();
            set_autosize($('#message_text'));
        });

        function countChar() {
            $('#msgNum').html(( $('#message_subject').val().length + $('#message_text').val().length + 2 /* For the [: ] that connects the subject to body in SMS */ )+'/<?= view_memory(6404,27891) ?> Characters (Subject + Body)');
        }

        var is_processing = false;
        function schedule_message(){

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

            $.post("/x/x_schedule_message", {
                i__id: '<?= $_GET['i__id'] ?>',
                e__id: '<?= $_GET['e__id'] ?>',
                exclude_e: '<?= $_GET['exclude_e'] ?>',
                include_e: '<?= $_GET['include_e'] ?>',
                exclude_i: '<?= $_GET['exclude_i'] ?>',
                include_i: '<?= $_GET['include_i'] ?>',
                message_subject: $('#message_subject').val(),
                message_text: $('#message_text').val(),
                message_time: $('#message_time').val(),
            }, function (data) {

                if (data.status) {
                    //Hide button:
                    $('#schedule_message_btn').addClass('hidden');
                    $('#message_result').html(data.message);
                } else {
                    //Show error:
                    is_processing = false; //Allow resubmissions
                    $('#message_result').html('ERROR: '+data.message);
                }

            });

        }

        function x_schedule_delete(x__id){
            var r = confirm("Remove Email "+x__id+"?");
            if (r==true) {
                $.post("/x/x_schedule_delete", {
                    x__id: x__id,
                }, function (data) {
                    $('.semail'+x__id).remove();
                });
            }
        }

    </script>
    <?php

}