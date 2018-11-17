<?php

//Fetch Messages based on c_id:
$message_max = $this->config->item('message_max');
$i_statuses = echo_status('i_status', null);
$i_desc = echo_status('i_status');
$udata = $this->session->userdata('user');
$i_messages = $this->Db_model->i_fetch(array(
    'i_c_id' => $c_id,
    'i_status >=' => 0, //Not deleted
), 0, array('x'));

//Fetch intent details:
$intents = $this->Db_model->c_fetch(array(
    'c.c_id' => $c_id,
));

if(!isset($intents[0])){
    //This should never happen:
    die('Invalid input id.');
}
?>

<script>
    //Set core variables:
    var c_id = <?= $c_id ?>;
    var max_length = <?= $message_max ?>;
    var message_count = <?= count($i_messages) ?>;
</script>
<script src="/js/custom/messaging-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>


<ul class="nav nav-tabs iphone-nav-tabs">
    <li role="presentation" class="nav_1 active" data-toggle="tooltip" title="<?= $i_desc[1]['s_desc'] ?>" data-placement="bottom"><a href="#messages-<?= $c_id ?>-1"><?= echo_status('i_status',1, false, null) ?></a></li>
    <li role="presentation" class="nav_2" data-toggle="tooltip" title="<?= $i_desc[2]['s_desc'] ?>" data-placement="bottom"><a href="#messages-<?= $c_id ?>-2"><?= echo_status('i_status',2, false, null) ?></a></li>
    <li role="presentation" class="nav_3" data-toggle="tooltip" title="<?= $i_desc[3]['s_desc'] ?>" data-placement="bottom"><a href="#messages-<?= $c_id ?>-3"><?= echo_status('i_status',3, false, null) ?></a></li>
</ul>

<input type="hidden" id="i_status_focus" value="1" />

<div id="intent_messages<?= $c_id ?>">

    <?php

    $message_count_1 = 0;
    $message_count_2 = 0;
    $message_count_3 = 0;

    if(count($i_messages)>0){
        echo '<div id="message-sorting'.$c_id.'" class="list-group list-messages">';
        foreach($i_messages as $i){
            echo echo_message(array_merge($i, array(
                'e_child_u_id'=>$udata['u_id'],
            )));
            //Increase counter:
            ${'message_count_'.$i['i_status']}++;
        }
        echo '</div>';
    } else {
        //Now show empty shell
        echo '<div id="message-sorting'.$c_id.'" class="list-group list-messages"></div>';
    }

    //Show no Message errors:
    if($message_count_1==0){
        echo '<div class="ix-tip no-messages'.$c_id.'_1 all_msg msg_1"><i class="fas fa-exclamation-triangle"></i> No '.echo_status('i_status',1, false, null).' Messages added yet</div>';
    }
    if($message_count_2==0){
        echo '<div class="ix-tip no-messages'.$c_id.'_2 all_msg msg_2 hidden"><i class="fas fa-exclamation-triangle"></i> No '.echo_status('i_status',2, false, null).' Messages added yet</div>';
    }
    if($message_count_3==0){
        echo '<div class="ix-tip no-messages'.$c_id.'_3 all_msg msg_3 hidden"><i class="fas fa-exclamation-triangle"></i> No '.echo_status('i_status',3, false, null).' Messages added yet</div>';
    }

    ?>
</div>

<div style="margin-top:-7px;">
    <?php
    echo '<div class="list-group list-messages">';
    echo '<div class="list-group-item">';

    echo '<div class="add-msg add-msg'.$c_id.'">';
    echo '<form class="box box'.$c_id.'" method="post" enctype="multipart/form-data">'; //Used for dropping files

    echo '<textarea onkeyup="changeMessage()" class="form-control msg msgin algolia_search" style="min-height:80px; box-shadow: none; resize: none; margin-bottom: 0px;" id="i_message'.$c_id.'" placeholder="Write Message, Drop a File or Paste URL"></textarea>';

    echo '<div id="i_message_counter" style="margin:0 0 1px 0; font-size:0.8em;">';
    //File counter:
    echo '<span id="charNum'.$c_id.'">0</span>/'.$message_max;

    ///firstname
    echo '<a href="javascript:add_first_name();" class="textarea_buttons remove_loading" style="float:right;" data-toggle="tooltip" title="Replaced with student\'s First Name for a more personal message." data-placement="left"><i class="fas fa-fingerprint"></i> /firstname</a>';

    //Choose a file:
    $file_limit_mb = $this->config->item('file_limit_mb');
    echo '<div style="float:right; display:inline-block; margin-right:8px;" class="remove_loading"><input class="box__file inputfile" type="file" name="file" id="file" /><label class="textarea_buttons" for="file" data-toggle="tooltip" title="Upload Video, Audio, Images or PDFs up to '.$file_limit_mb.' MB." data-placement="top"><i class="fas fa-image"></i> Upload File</label></div>';
    echo '</div>';


    echo '<div class="iphone-add-btn all_msg msg_1"><a href="javascript:msg_create();" id="add_message_1_'.$c_id.'" data-toggle="tooltip" title="or hit CTRL+ENTER ;)" data-placement="top" class="btn btn-primary">ADD '.echo_status('i_status',1, false, null).' &nbsp; MESSAGE</a></div>';
    echo '<div class="iphone-add-btn all_msg msg_2 hidden"><a href="javascript:msg_create();" id="add_message_2_'.$c_id.'" data-toggle="tooltip" title="or hit CTRL+ENTER ;)" data-placement="top" class="btn btn-primary">ADD '.echo_status('i_status',2, false, null).' &nbsp; MESSAGE</a></div>';
    echo '<div class="iphone-add-btn all_msg msg_3 hidden"><a href="javascript:msg_create();" id="add_message_3_'.$c_id.'" data-toggle="tooltip" title="or hit CTRL+ENTER ;)" data-placement="top" class="btn btn-primary">ADD '.echo_status('i_status',3, false, null).' &nbsp; MESSAGE</a></div>';



    echo '</form>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
    ?>
</div>
