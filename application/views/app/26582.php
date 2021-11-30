<?php

foreach(array('i__id','e__id','exclude_e','include_e') as $input){
    if(!isset($_GET[$input])){
        $_GET[$input] = '';
    }
}

//Show Titles:
if(strlen($_GET['i__id'])){
    $is = $this->I_model->fetch(array(
        'i__id IN (' . $_GET['i__id'] . ')' => null,
        'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    ));
    if(count($is)){
        echo '<h2><a href="/i/i_go/'.$is[0]['i__id'].'"><span class="icon-block-img">'.view_cover(12273,$is[0]['i__cover']).'</span> '.$is[0]['i__title'].'</a></h2>';
    }
}
if(strlen($_GET['e__id'])){
    $es = $this->E_model->fetch(array(
        'e__id IN (' . $_GET['e__id'] . ')' => null,
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($es)){
        echo '<h2><a href="/@'.$es[0]['e__id'].'"><span class="icon-block-img">'.view_cover(12274,$es[0]['i__cover']).'</span> '.$es[0]['e__title'].'</a></h2>';
    }
}

$message_list = message_list($_GET['i__id'], $_GET['e__id'], $_GET['exclude_e'], $_GET['include_e']);
$e___6287 = $this->config->item('e___6287'); //APP



echo '<div style="padding: 10px;"><a href="javascript:void(0);" onclick="$(\'.filter_box\').toggleClass(\'hidden\')"><i class="fad fa-filter"></i> Toggle Filters</a> | <a href="/-13790?i__id='.$_GET['i__id'].'&e__id='.$_GET['e__id'].'&include_e='.$_GET['include_e'].'&exclude_e='.$_GET['exclude_e'].'">'.$e___6287[13790]['m__cover'].' '.$e___6287[13790]['m__title'].'</a></div>';

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

echo '<td><span class="mini-header">Excludes Profile Source:</span><input type="text" name="exclude_e" placeholder="id1,id2" value="' . $_GET['exclude_e'] . '" class="form-control border"></td>';

echo '</tr><tr>';

echo '<td class="standard-bg"><input type="submit" class="btn btn-default" value="Apply Filters" /></td>';
echo '<td class="standard-bg">&nbsp;</td>';

echo '</tr></table>';

echo '</form>';


echo '<div style="padding: 10px"><a href="javascript:void(0);" onclick="$(\'.subscriber_data\').toggleClass(\'hidden\');"><i class="fad fa-search-plus"></i> '.$message_list['unique_users_count'].' Unique Recipients = '.$message_list['email_count'].' Emails + '.$message_list['phone_count'].' SMS</a></div>';

echo '<textarea class="mono-space subscriber_data hidden" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 10px;">'.$message_list['full_list'].'</textarea>';
echo '<textarea class="mono-space subscriber_data hidden" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 10px;">'.$message_list['email_list'].'</textarea>';


echo '<div style="padding: 0 10px 13px;">';
echo '<div style="padding: 10px 0;"><input type="text" class="form-control white-border" id="message_subject" placeholder="Subject" onkeyup="countChar()" value="'.( isset($_GET['message_subject']) ? $_GET['message_subject'] : '' ).'" /></div>';
echo '<textarea class="form-control white-border" id="message_text" placeholder="Body" style="height:147px" onkeyup="countChar()">'.( isset($_GET['message_text']) ? $_GET['message_text'] : '' ).'</textarea>';
echo '<div id="charNum"></div>';
echo '<div style="padding:10px 0;">Note: You can send this message as an SMS if all 3 conditions are met: (a) Message is shorter than '.view_memory(6404,27891).' Characters AND (b) Message excludes new lines AND (c) Message excludes Emojis. If any of these conditions is not met, Message will be sent as email only, and an SMS notification to check their email.</div>';
echo '</div>';

echo '<div id="send_message_btn" style="padding: 10px;"><a class="btn btn-default" href="javascript:void(0);"  onclick="send_message();">Send Message to '.$message_list['unique_users_count'].' <i class="fas fa-arrow-right"></i></a></div>';

echo '<div id="message_result"></div>';

echo '<div></div>';

?>

<script type="text/javascript">

    $(document).ready(function(){
        countChar();
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