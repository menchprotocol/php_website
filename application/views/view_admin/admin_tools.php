<?php

//Define all moderation functions:
$fixed_fields = $this->config->item('fixed_fields');

$moderation_tools = array(
    '/admin/tools/moderate_intent_notes' => 'Moderate Intent Notes',
    '/admin/tools/identical_intent_outcomes' => 'Identical Intent Outcomes',
    '/admin/tools/identical_entity_names' => 'Identical Entity Names',
    '/admin/tools/orphan_intents' => 'Orphan Intents',
    '/admin/tools/orphan_entities' => 'Orphan Entities',
    '/admin/tools/assessment_marks_list_all' => 'Response Weights List All',
    '/admin/tools/assessment_marks_birds_eye' => 'Response Weights Birds Eye View',
    '/admin/tools/compose_test_message' => 'Compose Test Message',
);

$cron_jobs = array(
    '/intents/cron__sync_common_base' => 'Sync Common Base Metadata',
    '/intents/cron__sync_extra_insights' => 'Sync Extra Insights Metadata',
    '/intents/cron__clean_metadatas' => 'Clean Unused Metadata Variables',
    '/entities/cron__update_trust_score' => 'Update All Entity Trust Scores',
    '/links/cron__sync_algolia' => 'Sync Algolia Index [Limited calls!]',
    '/links/cron__sync_gephi' => 'Sync Gephi Graph Index',
);


$developer_tools = array(
    '/admin/platform_cache' => 'Platform PHP Cache',
    '/admin/my_session' => 'My Session Variables',
    '/admin/php_info' => 'Server PHP Info',
);



if(!$action) {

    $en_all_2738 = $this->config->item('en_all_2738');
    echo '<h1>'.$en_all_2738[6287]['m_icon'].' '.$en_all_2738[6287]['m_name'].'</h1>';

    echo '<div class="list-group actionplan_list grey_list maxout">';
    foreach ($moderation_tools as $tool_key => $tool_name) {
        echo '<a href="' . $tool_key . '" class="list-group-item">';
        echo '<span class="pull-right">';
        echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-right"></i></span>';
        echo '</span>';
        echo '<span style="color:#222; font-weight:500; font-size:1.2em;">'.$tool_name.'</span>';
        echo '</a>';
    }
    echo '</div>';


    echo '<h3>Developer Tools</h3>';
    echo '<div class="list-group actionplan_list grey_list maxout">';
    foreach ($developer_tools as $tool_key => $tool_name) {
        echo '<a href="' . $tool_key . '" target="_blank" class="list-group-item">';
        echo '<span class="pull-right">';
        echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-external-link"></i></span>';
        echo '</span>';
        echo '<span style="color:#222; font-weight:500; font-size:1.2em;">'.$tool_name.'</span>';
        echo '</a>';

    }
    echo '</div>';



    echo '<h3>Automated Cron Jobs</h3>';
    echo '<div class="list-group actionplan_list grey_list maxout">';
    foreach ($cron_jobs as $tool_key => $tool_name) {
        echo '<a href="' . $tool_key . '" target="_blank" class="list-group-item">';
        echo '<span class="pull-right">';
        echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-external-link"></i></span>';
        echo '</span>';
        echo '<span style="color:#222; font-weight:500; font-size:1.2em;">'.$tool_name.'</span>';
        echo '</a>';

    }
    echo '</div>';

} elseif($action=='moderate_intent_notes'){

    //Fetch pending notes:
    $pendin_in_notes = $this->Links_model->ln_fetch(array(
        'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete
        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
    ), array('in_child'), $this->config->item('items_per_page'), 0, array('ln_id' => 'ASC'));

    echo '<div class="row">';
    echo '<div class="col-xs-7 cols">';
    echo '<ul class="breadcrumb"><li><a href="/admin">Admin Tools</a></li><li><b>'.$moderation_tools['/admin/tools/'.$action].'</b></li></ul>';
    //List intents and allow to modify and manage intent notes:
    if(count($pendin_in_notes) > 0){
        foreach($pendin_in_notes as $pendin_in_note){
            echo echo_in($pendin_in_note, 0);
        }
    } else {
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> No Pending Intent Notes at this time</div>';
    }

    echo '</div>';
    echo '<div class="col-xs-5 cols">';
    $this->load->view('view_intents/in_right_column');
    echo '</div>';
    echo '</div>';


} elseif($action=='orphan_intents') {

    echo '<ul class="breadcrumb"><li><a href="/admin">Admin Tools</a></li><li><b>'.$moderation_tools['/admin/tools/'.$action].'</b></li></ul>';

    $orphan_ins = $this->Intents_model->in_fetch(array(
        ' NOT EXISTS (SELECT 1 FROM table_links WHERE in_id=ln_child_intent_id AND ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ') AND ln_status>=0) ' => null,
        'in_status >=' => 0,
        'in_id !=' => $this->config->item('in_mission_id'), //Mission does not have parents
        'in_id NOT IN (' . join(',', $this->config->item('in_status_locked')) . ')' => null,
    ));

    if(count($orphan_ins) > 0){

        //List orphans:
        foreach ($orphan_ins as $count => $orphan_in) {

            //Show intent:
            echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['in_status'][$orphan_in['in_status']]['s_name'].': '.$fixed_fields['in_status'][$orphan_in['in_status']]['s_desc'].'">' . $fixed_fields['in_status'][$orphan_in['in_status']]['s_icon'] . '</span> <a href="/intents/'.$orphan_in['in_id'].'"><b>'.$orphan_in['in_outcome'].'</b></a>';

            //Do we need to remove?
            if($command1=='remove_all'){

                //Remove intent links:
                $links_removed = $this->Intents_model->in_unlink($orphan_in['in_id'] , $session_en['en_id']);

                //Remove intent:
                $this->Intents_model->in_update($orphan_in['in_id'], array( 'in_status' => -1 ), true, $session_en['en_id']);

                //Show confirmation:
                echo ' [Intent + '.$links_removed.' links Removed]';

            }

            //Done showing the intent:
            echo '</div>';
        }

        //Show option to remove all:
        if($command1!='remove_all'){
            echo '<br />';
            echo '<a class="remove-all" href="javascript:void(0);" onclick="$(\'.remove-all\').toggleClass(\'hidden\')">Remove All</a>';
            echo '<div class="remove-all hidden maxout"><b style="color: #FF0000;">WARNING</b>: All intents and all their links will be removed. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing intent.<br /><br /></div>';
            echo '<a class="remove-all hidden maxout" href="/admin/tools/orphan_intents/remove_all" onclick="">Confirm: <b>Remove All</b> &raquo;</a>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No orphans found!</div>';
    }

} elseif($action=='orphan_entities') {

    echo '<ul class="breadcrumb"><li><a href="/admin">Admin Tools</a></li><li><b>'.$moderation_tools['/admin/tools/'.$action].'</b></li></ul>';

    $orphan_ens = $this->Entities_model->en_fetch(array(
        ' NOT EXISTS (SELECT 1 FROM table_links WHERE en_id=ln_child_entity_id AND ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ') AND ln_status>=0) ' => null,
        'en_status >=' => 0,
        'en_id !=' => $this->config->item('en_top_focus_id'),
    ), array('skip_en__parents'));

    if(count($orphan_ens) > 0){

        //List orphans:
        foreach ($orphan_ens  as $count => $orphan_en) {

            //Show entity:
            echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['en_status'][$orphan_en['en_status']]['s_name'].': '.$fixed_fields['en_status'][$orphan_en['en_status']]['s_desc'].'">' . $fixed_fields['en_status'][$orphan_en['en_status']]['s_icon'] . '</span> <a href="/entities/'.$orphan_en['en_id'].'"><b>'.$orphan_en['en_name'].'</b></a>';

            //Do we need to remove?
            if($command1=='remove_all'){

                //Remove links:
                $links_removed = $this->Entities_model->en_unlink($orphan_en['en_id'], $session_en['en_id']);

                //Remove entity:
                $this->Entities_model->en_update($orphan_en['en_id'], array( 'en_status' => -1 ), true, $session_en['en_id']);

                //Show confirmation:
                echo ' [Entity + '.$links_removed.' links Removed]';

            }

            echo '</div>';

        }

        //Show option to remove all:
        if($command1!='remove_all'){
            echo '<br />';
            echo '<a class="remove-all" href="javascript:void(0);" onclick="$(\'.remove-all\').toggleClass(\'hidden\')">Remove All</a>';
            echo '<div class="remove-all hidden maxout"><b style="color: #FF0000;">WARNING</b>: All entities and all their links will be removed. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing entity.<br /><br /></div>';
            echo '<a class="remove-all hidden maxout" href="/admin/tools/orphan_entities/remove_all" onclick="">Confirm: <b>Remove All</b> &raquo;</a>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No orphans found!</div>';
    }

} elseif($action=='identical_intent_outcomes') {

    echo '<ul class="breadcrumb"><li><a href="/admin">Admin Tools</a></li><li><b>'.$moderation_tools['/admin/tools/'.$action].'</b></li></ul>';

    //Do a query to detect intents with the exact same title:
    $q = $this->db->query('select in1.* from table_intents in1 where (select count(*) from table_intents in2 where in2.in_outcome = in1.in_outcome) > 1 ORDER BY in1.in_outcome ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        foreach ($duplicates as $in) {
            if ($prev_title != $in['in_outcome']) {
                echo '<hr />';
                $prev_title = $in['in_outcome'];
            }

            echo '<div><span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['in_status'][$in['in_status']]['s_name'].': '.$fixed_fields['in_status'][$in['in_status']]['s_desc'].'">' . $fixed_fields['in_status'][$in['in_status']]['s_icon'] . '</span> <a href="/intents/' . $in['in_id'] . '"><b>' . $in['in_outcome'] . '</b></a> #' . $in['in_id'] . '</div>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No duplicates found!</div>';
    }

} elseif($action=='identical_entity_names') {

    echo '<ul class="breadcrumb"><li><a href="/admin">Admin Tools</a></li><li><b>'.$moderation_tools['/admin/tools/'.$action].'</b></li></ul>';

    $q = $this->db->query('select en1.* from table_entities en1 where (select count(*) from table_entities en2 where en2.en_name = en1.en_name) > 1 ORDER BY en1.en_name ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        foreach ($duplicates as $en) {

            if ($prev_title != $en['en_name']) {
                echo '<hr />';
                $prev_title = $en['en_name'];
            }

            echo '<span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['en_status'][$en['en_status']]['s_name'].': '.$fixed_fields['en_status'][$en['en_status']]['s_desc'].'">' . $fixed_fields['en_status'][$en['en_status']]['s_icon'] . '</span> <a href="/entities/' . $en['en_id'] . '"><b>' . $en['en_name'] . '</b></a> @' . $en['en_id'] . '<br />';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No duplicates found!</div>';
    }


} elseif($action=='reset_all_points') {


    die('Locked via code base');

    boost_power();

    //Hidden function to reset points:
    $all_link_types = $this->Links_model->ln_fetch(array('ln_status >=' => 0), array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(ln_type_entity_id) as trs_count, en_name, en_icon, ln_type_entity_id', 'ln_type_entity_id, en_name, en_icon');


    $total_updated = 0;
    foreach ($all_link_types as $count => $ln) {

        $points = fetch_points($ln['ln_type_entity_id']);

        //Update all links with out-of-date points:
        $this->db->query("UPDATE table_links SET ln_points = ".$points." WHERE ln_points != ".$points." AND ln_type_entity_id = " . $ln['ln_type_entity_id']);

        //Count how many updates:
        $total_updated += $this->db->affected_rows();

    }

    echo $total_updated.' links updated with new points';


} elseif($action=='assessment_marks_list_all') {


    echo '<ul class="breadcrumb"><li><a href="/admin">Admin Tools</a></li><li><b>'.$moderation_tools['/admin/tools/'.$action].'</b></li></ul>';

    echo '<p>Below are all the Conditional Milestone Links:</p>';
    echo '<table class="table table-condensed table-striped maxout" style="text-align: left;">';

    $en_all_6410 = $CI->config->item('en_all_6410');

    echo '<tr style="font-weight: bold;">';
    echo '<td colspan="4" style="text-align: left;">'.$en_all_6410[6402]['m_icon'].' '.$en_all_6410[6402]['m_name'].'</td>';
    echo '</tr>';
    $counter = 0;
    foreach ($this->Links_model->ln_fetch(array(
        'ln_status >=' => 0,
        'in_status >=' => 0,
        'ln_type_entity_id' => 4229,
        'LENGTH(ln_metadata) > 0' => null,
    ), array('in_child'), 0, 0) as $in_ln) {
        //Echo HTML format of this message:
        $metadata = unserialize($in_ln['ln_metadata']);
        $mark = echo_assessment_mark($in_ln);
        if($mark){

            //Fetch parent intent:
            $parent_ins = $this->Intents_model->in_fetch(array(
                'in_id' => $in_ln['ln_parent_intent_id'],
            ));

            $counter++;
            echo '<tr>';
            echo '<td style="width: 50px;">'.$counter.'</td>';
            echo '<td style="font-weight: bold; font-size: 1.3em; width: 100px;">'.echo_assessment_mark($in_ln).'</td>';
            echo '<td>'.$fixed_fields['ln_status'][$in_ln['ln_status']]['s_icon'].'</td>';
            echo '<td style="text-align: left;">';
            echo '<div>';
            echo '<span style="width:25px; display:inline-block; text-align:center;">'.$fixed_fields['in_status'][$parent_ins[0]['in_status']]['s_icon'].'</span>';
            echo '<a href="/intents/'.$parent_ins[0]['in_id'].'">'.$parent_ins[0]['in_outcome'].'</a>';
            echo '</div>';

            echo '<div>';
            echo '<span style="width:25px; display:inline-block; text-align:center;">'.$fixed_fields['in_status'][$in_ln['in_status']]['s_icon'].'</span>';
            echo '<a href="/intents/'.$in_ln['in_id'].'">'.$in_ln['in_outcome'].'</a>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';

        }
    }

    echo '</table>';



    echo '<p>Below are all the fixed step links that award/subtract Response Weights:</p>';
    echo '<table class="table table-condensed table-striped maxout" style="text-align: left;">';

    echo '<tr style="font-weight: bold;">';
    echo '<td colspan="4" style="text-align: left;">Response Weights</td>';
    echo '</tr>';

    $counter = 0;
    foreach ($this->Links_model->ln_fetch(array(
        'ln_status >=' => 0,
        'in_status >=' => 0,
        'ln_type_entity_id' => 4228,
        'LENGTH(ln_metadata) > 0' => null,
    ), array('in_child'), 0, 0) as $in_ln) {
        //Echo HTML format of this message:
        $metadata = unserialize($in_ln['ln_metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        if($tr__assessment_points!=0){

            //Fetch parent intent:
            $parent_ins = $this->Intents_model->in_fetch(array(
                'in_id' => $in_ln['ln_parent_intent_id'],
            ));


            //Update Response Weights if outside of range (Handy if in_response_weights values are reduced)
            /*
            if($tr__assessment_points > 1){
                //Set to 1:
                update_metadata('ln', $in_ln['ln_id'], array(
                    'tr__assessment_points' => 1,
                ));
            } elseif($tr__assessment_points < 0){
                update_metadata('ln', $in_ln['ln_id'], array(
                    'tr__assessment_points' => 0,
                ));
            }
            */


            $counter++;
            echo '<tr>';
            echo '<td style="width: 50px;">'.$counter.'</td>';
            echo '<td style="font-weight: bold; font-size: 1.3em; width: 100px;">'.echo_assessment_mark($in_ln).'</td>';
            echo '<td>'.$fixed_fields['ln_status'][$in_ln['ln_status']]['s_icon'].'</td>';
            echo '<td style="text-align: left;">';
                echo '<div>';
                echo '<span style="width:25px; display:inline-block; text-align:center;">'.$fixed_fields['in_status'][$parent_ins[0]['in_status']]['s_icon'].'</span>';
                echo '<a href="/intents/'.$parent_ins[0]['in_id'].'">'.$parent_ins[0]['in_outcome'].'</a>';
                echo '</div>';

                echo '<div>';
                echo '<span style="width:25px; display:inline-block; text-align:center;">'.$fixed_fields['in_status'][$in_ln['in_status']]['s_icon'].'</span>';
                echo '<a href="/intents/'.$in_ln['in_id'].'">'.$in_ln['in_outcome'].'</a>';
                echo '</div>';
            echo '</td>';
            echo '</tr>';

        }
    }

    echo '</table>';


} elseif($action=='assessment_marks_birds_eye') {

    //Give an overview of the point links in a hierchial format to enable moderators to overview:
    $_GET['starting_in']    = ( isset($_GET['starting_in']) && intval($_GET['starting_in']) > 0 ? $_GET['starting_in'] : $this->config->item('in_miner_start') );
    $_GET['depth_levels']   = ( isset($_GET['depth_levels']) && intval($_GET['depth_levels']) > 0 ? $_GET['depth_levels'] : 3 );
    $_GET['status_min']     = ( isset($_GET['status_min']) && intval($_GET['status_min']) > 0 ? $_GET['status_min'] : 0 );

    echo '<ul class="breadcrumb"><li><a href="/admin">Admin Tools</a></li><li><b>'.$moderation_tools['/admin/tools/'.$action].'</b></li></ul>';


    echo '<form method="GET" action="">';

    echo '<div class="score_range_box">
            <div class="form-group label-floating is-empty"
                 style="max-width:550px; margin:1px 0 10px; display: inline-block;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">Start at #</span>
                    <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="starting_in" id="starting_in" value="'.$_GET['starting_in'].'" class="form-control">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc;"> and go </span>
                    <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="depth_levels" id="depth_levels" value="'.$_GET['depth_levels'].'" class="form-control">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc; border-right:0px solid #FFF;"> levels deep with min. status: </span>
                    <input style="padding-left:3px; min-width:56px;" type="number" min="-1" max="2" step="1" name="status_min" id="status_min" value="'.$_GET['status_min'].'" class="form-control">
                </div>
            </div>
            <input type="submit" class="btn btn-primary btn-sm" value="Go" style="display: inline-block; margin-top: -41px;" />
        </div>';

    echo '</form>';

    //Load the report via Ajax here on page load:
    echo '<div id="in_report_conditional_milestones"></div>';
    echo '<script>

$(document).ready(function () {
//Show spinner:
$(\'#in_report_conditional_milestones\').html(\'<span><i class="fas fa-spinner fa-spin"></i> Loading...</span>\').hide().fadeIn();
//Load report based on input fields:
$.post("/intents/in_report_conditional_milestones", {
    starting_in: parseInt($(\'#starting_in\').val()),
    depth_levels: parseInt($(\'#depth_levels\').val()),
    status_min: parseInt($(\'#status_min\').val()),
}, function (data) {
    if (!data.status) {
        //Show Error:
        $(\'#in_report_conditional_milestones\').html(\'<span style="color:#FF0000;">Error: \'+ data.message +\'</span>\');
    } else {
        //Load Report:
        $(\'#in_report_conditional_milestones\').html(data.message);
        $(\'[data-toggle="tooltip"]\').tooltip();
    }
});
});

</script>';


} elseif($action=='compose_test_message') {


    if(isset($_POST['test_message'])){

        echo '<ul class="breadcrumb"><li><a href="/admin">Admin Tools</a></li><li><a href="/admin/tools/'.$action.'">'.$moderation_tools['/admin/tools/'.$action].'</a></li><li><b>Review Message</b></li></ul>';

        if(intval($_POST['fb_messenger_format']) && intval($_POST['recipient_en'])){

            //Send to Facebook Messenger:
            $msg_validation = $this->Communication_model->dispatch_message(
                $_POST['test_message'],
                array('en_id' => intval($_POST['recipient_en'])),
                true
            );

        } elseif(intval($_POST['recipient_en']) > 0) {

            $msg_validation = $this->Communication_model->dispatch_validate_message($_POST['test_message'], array('en_id' => $_POST['recipient_en']), $_POST['fb_messenger_format']);

        } else {

            echo 'Missing recipient';

        }

        //Show results:
        print_r($msg_validation);

    } else {

        echo '<ul class="breadcrumb"><li><a href="/admin">Admin Tools</a></li><li><b>'.$moderation_tools['/admin/tools/'.$action].'</b></li></ul>';

        //UI to compose a test message:
        echo '<form method="POST" action="">';

        echo '<div class="mini-header">Message:</div>';
        echo '<textarea name="test_message" style="width:400px; height: 200px;"></textarea><br />';

        echo '<div class="mini-header">Recipient Entity ID:</div>';
        echo '<input type="number" name="recipient_en" value="1"><br />';

        echo '<div class="mini-header">Format Is Messenger:</div>';
        echo '<input type="number" name="fb_messenger_format" value="1"><br /><br />';


        echo '<input type="submit" class="btn btn-primary" value="Compose Test Message">';
        echo '</form>';

    }

} else {

    //Oooooopsi, unknown:
    echo '<h1>Unknown Function</h1>';
    echo 'Not sure how you landed here!';

}


echo '<br /><br /><br /><br />';

?>