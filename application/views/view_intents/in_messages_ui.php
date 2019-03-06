<?php

//Fetch Messages based on in_id:
$session_en = $this->session->userdata('user');
$tr_content_max = $this->config->item('tr_content_max');
$en_ids_4485 = $this->config->item('en_ids_4485');
$en_all_4485 = $this->config->item('en_all_4485');

//Fetch all messages:
$metadatas = $this->Database_model->fn___tr_fetch(array(
    'tr_status >=' => 0, //New+
    'tr_type_entity_id IN (' . join(',', $en_ids_4485) . ')' => null, //All Intent messages
    'tr_child_intent_id' => $in_id,
), array(), 0, 0, array('tr_order' => 'ASC'));


//To be populated:
$counters = array();
$metadata_body_ui = '';
foreach ($metadatas as $tr) {

    $metadata_body_ui .= fn___echo_in_message_manage(array_merge($tr, array(
        'tr_child_entity_id' => $session_en['en_id'],
    )));

    //Increase counter:
    if (isset($counters[$tr['tr_type_entity_id']])) {
        $counters[$tr['tr_type_entity_id']]++;
    } else {
        $counters[$tr['tr_type_entity_id']] = 1;
    }

}

?>


<script>
    //pass core variables to JS:
    var in_id = <?= $in_id ?>;
    var tr_content_max = <?= $tr_content_max ?>;
    var metadata_count = <?= count($metadatas) ?>;
    var focus_tr_type_entity_id = <?= $en_ids_4485[0] ?>; //The message type that is the focus on-start.
</script>
<script src="/js/custom/messaging-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>



<!-- Message types navigation menu -->
<ul class="nav nav-tabs iphone-nav-tabs">
    <?php
    foreach ($en_all_4485 as $tr_type_entity_id => $m) {
        echo '<li role="presentation" class="nav_' . $tr_type_entity_id . ' active">';
        echo '<a href="#intentmessages-' . $in_id . '-'.$tr_type_entity_id.'"> ' . $m['m_icon'] . ' ' . $m['m_name'] . ' [<span class="mtd_count_'.$in_id.'_'.$tr_type_entity_id.'">'.( isset($counters[$tr_type_entity_id]) ? $counters[$tr_type_entity_id] : 0 ).'</span>] </a>';
        echo '</li>';
    }
    ?>
</ul>


<div id="intent_messages<?= $in_id ?>">

    <?php

    //Show no-Message notifications for each message type:
    foreach ($en_all_4485 as $tr_type_entity_id => $m) {


        echo '<div class="all_msg msg_en_type_' . $tr_type_entity_id . ' sorting-enabled">';


        //Learn more option:
        echo '<i class="fas fa-info-circle"></i> <span data-toggle="tooltip" title="'.$m['m_desc'].'" data-placement="bottom" class="underdot">Usage notes</span> &nbsp;';


        //Does it support sorting?
        if(in_array(4603, $en_all_4485[$tr_type_entity_id]['m_parents'])){
            echo '<i class="fas fa-exchange rotate90"></i> <span data-toggle="tooltip" class="underdot" title="Messages are delivered in order so you can can sort them as needed" data-placement="bottom">Sortable</span> &nbsp;';
        }

        //Does it support switching?
        if(in_array(4742, $en_all_4485[$tr_type_entity_id]['m_parents'])){
            echo '<i class="fas fa-exchange"></i> <span data-toggle="tooltip" class="underdot" title="You can change message type with other messages that are also switchable" data-placement="bottom">Switchable</span> &nbsp;';
        }

        //Does it support entity referencing?
        if(in_array(4986, $en_all_4485[$tr_type_entity_id]['m_parents'])){
            echo '<i class="fas fa-at"></i> <span data-toggle="tooltip" class="underdot" title="You can reference up to 1 entity using the @ sign" data-placement="bottom">Entity Reference</span> &nbsp;';
        }

        //Does it require intent voting?
        if(in_array(4985, $en_all_4485[$tr_type_entity_id]['m_parents'])){
            echo '<i class="fas fa-hashtag"></i> <span data-toggle="tooltip" class="underdot" title="You can reference up to 1 parent intent using the # sign" data-placement="bottom">Intent Reference</span> &nbsp;';
        }


        //See if this message type has specific input requirements:
        $en_all_4485 = $this->config->item('en_all_4485');
        $completion_requirements = array_intersect($en_all_4485[$tr_type_entity_id]['m_parents'], $this->config->item('en_ids_4331'));
        if(count($completion_requirements) == 1){
            $en_id = array_shift($completion_requirements);
            $en_all_4331 = $this->config->item('en_all_4331');
            echo '<i class="fas fa-asterisk"></i> <span data-toggle="tooltip" title="You can only add '.$en_all_4331[$en_id]['m_name'].' messages" data-placement="bottom" class="underdot">Requires '.$en_all_4331[$en_id]['m_name'].'</span>';
        } else {
            //No Requirements:
            echo '<i class="fas fa-asterisk"></i> <span data-toggle="tooltip" title="You can add any message type including text, URLs, videos, audios, etc..." data-placement="bottom" class="underdot">Supports all formats</span>';
        }

        echo '</div>';


        if (!isset($counters[$tr_type_entity_id])) {
            echo '<div class="ix-tip no-messages' . $in_id . '_' . $tr_type_entity_id . ' all_msg msg_en_type_' . $tr_type_entity_id . '"><i class="fas fa-exclamation-triangle"></i> No ' . strtolower($m['m_name']) . ' added yet</div>';
        }
    }

    //Count each message type:
    echo '<div id="message-sorting" class="list-group list-messages">';
    echo $metadata_body_ui;
    echo '</div>';

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
    foreach ($en_all_4485 as $tr_type_entity_id => $m) {
        echo '<div class="iphone-add-btn all_msg msg_en_type_' . $tr_type_entity_id . '"><a href="javascript:fn___message_create();" id="add_message_' . $tr_type_entity_id . '_' . $in_id . '" data-toggle="tooltip" title="or hit CTRL+ENTER ;)" data-placement="right" class="btn btn-primary">ADD '.$m['m_icon'].' ' . rtrim($m['m_name'], 's') . '</a></div>';
    }

    echo '</form>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
    ?>
</div>
