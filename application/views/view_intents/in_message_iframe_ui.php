<?php

//Fetch Messages based on in_id:
$udata = $this->session->userdata('user');
$tr_content_max = $this->config->item('tr_content_max');
$en_ids_4485 = $this->config->item('en_ids_4485');
$en_all_4485 = $this->config->item('en_all_4485');

//Fetch all messages:
$messages = $this->Database_model->fn___tr_fetch(array(
    'tr_status >=' => 0, //New+
    'tr_type_en_id IN (' . join(',', $en_ids_4485) . ')' => null, //All Intent messages
    'tr_in_child_id' => $in_id,
), array(), 0, 0, array('tr_order' => 'ASC'));

?>


<script>
    //pass core variables to JS:
    var in_id = <?= $in_id ?>;
    var tr_content_max = <?= $tr_content_max ?>;
    var message_count = <?= count($messages) ?>;
    var focus_tr_type_en_id = <?= $en_ids_4485[0] ?>; //The message type that is the focus on-start.
</script>
<script src="/js/custom/messaging-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>



<!-- Message types navigation menu -->
<ul class="nav nav-tabs iphone-nav-tabs">
    <?php
    foreach ($en_all_4485 as $tr_type_en_id => $m) {
        echo '<li role="presentation" class="nav_' . $tr_type_en_id . ' active" data-toggle="tooltip" title="' . $m['m_desc'] . '" data-placement="bottom">';
        echo '<a href="#loadmessages-' . $in_id . '-' . $tr_type_en_id . '"> ' . $m['m_icon'] . ' ' . $m['m_name'] . ' </a>';
        echo '</li>';
    }
    ?>
</ul>


<div id="intent_messages<?= $in_id ?>">

    <?php

    //Count each message type:
    $counters = array();
    echo '<div id="message-sorting" class="list-group list-messages">';
    foreach ($messages as $tr) {

        echo fn___echo_in_message_manage(array_merge($tr, array(
            'tr_en_child_id' => $udata['en_id'],
        )));

        //Increase counter:
        if (isset($counters[$tr['tr_type_en_id']])) {
            $counters[$tr['tr_type_en_id']]++;
        } else {
            $counters[$tr['tr_type_en_id']] = 1;
        }

    }
    echo '</div>';

    //Show no-Message notifications for each message type:
    foreach ($en_all_4485 as $tr_type_en_id => $m) {
        if (!isset($counters[$tr_type_en_id])) {
            echo '<div class="ix-tip no-messages' . $in_id . '_' . $tr_type_en_id . ' all_msg msg_en_type_' . $tr_type_en_id . '"><i class="fas fa-exclamation-triangle"></i> No ' . $m['m_icon'] . ' ' . $m['m_name'] . ' added yet</div>';
        }
    }

    ?>
</div>

<div style="margin-top:-7px;">
    <?php
    echo '<div class="list-group list-messages">';
    echo '<div class="list-group-item">';

    echo '<div class="add-msg add-msg' . $in_id . '">';
    echo '<form class="box box' . $in_id . '" method="post" enctype="multipart/form-data">'; //Used for dropping files

    echo '<textarea onkeyup="fn___count_message()" class="form-control msg msgin algolia_search" style="min-height:80px; box-shadow: none; resize: none; margin-bottom: 0px;" id="tr_content' . $in_id . '" placeholder="Write Message, Drop a File or Paste URL"></textarea>';

    echo '<div id="tr_content_counter" style="margin:0 0 1px 0; font-size:0.8em;">';
    //File counter:
    echo '<span id="charNum' . $in_id . '">0</span>/' . $tr_content_max;

    ///firstname
    echo '<a href="javascript:fn___add_first_name();" class="textarea_buttons remove_loading" style="float:right;" data-toggle="tooltip" title="Replaced with master\'s First Name for a more personal message." data-placement="left"><i class="fas fa-fingerprint"></i> /firstname</a>';

    //Choose a file:
    echo '<div style="float:right; display:inline-block; margin-right:8px;" class="remove_loading"><input class="box__file inputfile" type="file" name="file" id="file" /><label class="textarea_buttons" for="file" data-toggle="tooltip" title="Upload Video, Audio, Images or PDFs up to ' . $this->config->item('file_size_max') . ' MB" data-placement="top"><i class="fal fa-cloud-upload"></i> Upload</label></div>';
    echo '</div>';


    //Fetch for all message types:
    foreach ($en_all_4485 as $tr_type_en_id => $m) {
        echo '<div class="iphone-add-btn all_msg msg_en_type_' . $tr_type_en_id . '"><a href="javascript:fn___message_create();" id="add_message_' . $tr_type_en_id . '_' . $in_id . '" data-toggle="tooltip" title="or hit CTRL+ENTER ;)" data-placement="right" class="btn btn-primary">ADD ' . $m['m_icon'] . ' ' . $m['m_name'] . '</a></div>';
    }

    echo '</form>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
    ?>
</div>
