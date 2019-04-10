<?php

//Define all moderation functions:
$fixed_fields = $this->config->item('fixed_fields');

$moderation_tools = array(
    '/admin/tools/identical_intent_outcomes' => 'Identical Intent Outcomes',
    '/admin/tools/identical_entity_names' => 'Identical Entity Names',
    '/admin/tools/orphan_intents' => 'Orphan Intents',
    '/admin/tools/orphan_entities' => 'Orphan Entities',
    '/admin/tools/assessment_marks_list_all' => 'Assessment Marks List All',
    '/admin/tools/assessment_marks_birds_eye' => 'Assessment Marks Birds Eye View',
    '/admin/tools/compose_test_message' => 'Compose Test Message',
);

$cron_jobs = array(
    '/intents/cron__update_metadata' => 'Sync Intents Metadata',
    '/entities/cron__update_trust_score' => 'Update All Entity Trust Scores',
    '/links/cron__sync_algolia' => 'Sync Algolia Index [Limited calls!]',
    '/links/cron__sync_gephi' => 'Sync Gephi Graph Index',
);


$developer_tools = array(
    '/admin/matrix_cache' => 'Matrix PHP Cache',
    '/admin/my_session' => 'My Session Variables',
    '/admin/php_info' => 'Server PHP Info',
);





if(!$action) {

    echo '<h1>Moderator Tools</h1>';

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

} else {

    //Show back button:
    echo '<ul class="breadcrumb maxout" style="margin-bottom: 10px;"><li><a href="/admin">Moderator Tools</a></li></ul>';

    if($action=='orphan_intents') {

        echo '<h1>'.$moderation_tools['/admin/tools/orphan_intents'].'</h1>';

        $orphan_ins = $this->Database_model->in_fetch(array(
            ' NOT EXISTS (SELECT 1 FROM table_links WHERE in_id=ln_child_intent_id AND ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ') AND ln_status>=0) ' => null,
            'in_status >=' => 0,
            'in_id !=' => $this->config->item('in_mission_id'),
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
                    $links_removed = $this->Matrix_model->in_unlink($orphan_in['in_id'] , $session_en['en_id']);

                    //Remove intent:
                    $this->Database_model->in_update($orphan_in['in_id'], array( 'in_status' => -1 ), true, $session_en['en_id']);

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

        echo '<h1>'.$moderation_tools['/admin/tools/orphan_entities'].'</h1>';

        $orphan_ens = $this->Database_model->en_fetch(array(
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
                    $links_removed = $this->Matrix_model->en_unlink($orphan_en['en_id'], $session_en['en_id']);

                    //Remove entity:
                    $this->Database_model->en_update($orphan_en['en_id'], array( 'en_status' => -1 ), true, $session_en['en_id']);

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

        echo '<h1>'.$moderation_tools['/admin/tools/identical_intent_outcomes'].'</h1>';

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

        echo '<h1>'.$moderation_tools['/admin/tools/identical_entity_names'].'</h1>';

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


    } elseif($action=='assessment_marks_list_all') {

        echo '<h1>'.$moderation_tools['/admin/tools/assessment_marks_list_all'].'</h1>';
        echo '<p>Below are all the fixed intent links that award/subtract assessment marks.</p>';
        echo '<table class="table table-condensed table-striped maxout" style="text-align: left;">';

        echo '<tr style="font-weight: bold;">';
        echo '<td>&nbsp;</td>';
        echo '<td>Mark</td>';
        echo '<td>&nbsp;</td>';
        echo '<td style="text-align: left;">Link Relation</td>';
        echo '</tr>';

        $counter = 0;
        foreach ($this->Database_model->ln_fetch(array(
            'ln_status >=' => 0,
            'in_status >=' => 0,
            'ln_type_entity_id' => 4228, //Intent Note Messages
            'LENGTH(ln_metadata) > 0' => null,
        ), array('in_child'), 0, 0) as $in_ln) {
            //Echo HTML format of this message:
            $metadata = unserialize($in_ln['ln_metadata']);
            $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
            if($tr__assessment_points!=0){

                //Fetch parent intent:
                $parent_ins = $this->Database_model->in_fetch(array(
                    'in_id' => $in_ln['ln_parent_intent_id'],
                ));

                $counter++;
                echo '<tr>';
                echo '<td>'.$counter.'</td>';
                echo '<td style="font-weight: bold; font-size: 1.3em;">'.echo_assessment_mark($in_ln).'</td>';
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
        $_GET['min_status']     = ( isset($_GET['min_status']) && intval($_GET['min_status']) > 0 ? $_GET['min_status'] : 0 );

        echo '<form method="GET" action="">';

        echo '<h1>'.$moderation_tools['/admin/tools/assessment_marks_birds_eye'].'</h1>
                <div class="score_range_box">
                <div class="form-group label-floating is-empty"
                     style="max-width:550px; margin:1px 0 10px; display: inline-block;">
                    <div class="input-group border">
                        <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">Start at #</span>
                        <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="starting_in" id="starting_in" value="'.$_GET['starting_in'].'" class="form-control">
                        <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc;"> and go </span>
                        <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="depth_levels" id="depth_levels" value="'.$_GET['depth_levels'].'" class="form-control">
                        <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc; border-right:0px solid #FFF;"> levels deep with min. status: </span>
                        <input style="padding-left:3px; min-width:56px;" type="number" min="-1" max="2" step="1" name="min_status" id="min_status" value="'.$_GET['min_status'].'" class="form-control">
                    </div>
                </div>
                <input type="submit" class="btn btn-primary btn-sm" value="Go" style="display: inline-block; margin-top: -41px;" />
            </div>';

        echo '</form>';

        //Load the report via Ajax here on page load:
        echo '<div id="assessment_marks_reports"></div>';
        echo '<script>

$(document).ready(function () {
    //Show spinner:
    $(\'#assessment_marks_reports\').html(\'<span><i class="fas fa-spinner fa-spin"></i> Loading...</span>\').hide().fadeIn();
    //Load report based on input fields:
    $.post("/intents/assessment_marks_reports", {
        starting_in: parseInt($(\'#starting_in\').val()),
        depth_levels: parseInt($(\'#depth_levels\').val()),
        min_status: parseInt($(\'#min_status\').val()),
    }, function (data) {
        if (!data.status) {
            //Show Error:
            $(\'#assessment_marks_reports\').html(\'<span style="color:#FF0000;">Error: \'+ data.message +\'</span>\');
        } else {
            //Load Report:
            $(\'#assessment_marks_reports\').html(data.message);
            $(\'[data-toggle="tooltip"]\').tooltip();
        }
    });
});

</script>';


    } elseif($action=='compose_test_message') {

        echo '<h1>'.$moderation_tools['/admin/tools/compose_test_message'].'</h1>';

        if(isset($_POST['test_message'])){


            if(intval($_POST['fb_messenger_format']) && intval($_POST['recipient_en'])){

                //Send to Facebook Messenger:
                $msg_validation = $this->Chat_model->dispatch_message(
                    $_POST['test_message'],
                    array('en_id' => intval($_POST['recipient_en'])),
                    true
                );

            } else {

                $msg_validation = $this->Chat_model->dispatch_validate_message($_POST['test_message'], ( intval($_POST['recipient_en']) ? array('en_id' => $_POST['recipient_en']) : array() ), $_POST['fb_messenger_format']);

            }

            //Show results:
            echo '<div><a href="/admin/tools/compose_test_message"> &laquo; Back to Message Compose</a></div><hr />';
            print_r($msg_validation);

        } else {

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

}

echo '<br /><br /><br /><br />';

?>