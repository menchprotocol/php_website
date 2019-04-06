<?php

//Define all moderation functions:
$fixed_fields = $this->config->item('fixed_fields');


$moderation_tools = array(
    '/admin/tools/identical_intent_outcomes' => 'Identical Intent Outcomes',
    '/admin/tools/identical_entity_names' => 'Identical Entity Names',
    '/admin/tools/orphan_intents' => 'Orphan Intents',
    '/admin/tools/orphan_entities' => 'Orphan Entities',
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

    echo '<h1>Admin Tools</h1>';

    echo '<h3>Moderation Tools</h3>';
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
    echo '<ul class="breadcrumb maxout" style="margin-bottom: 10px;"><li><a href="/links">Links</a></li><li><a href="/admin/">Moderator Tools</a></li></ul>';

    if($action=='orphan_intents') {

        echo '<h1>Orphan Intents</h1>';

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

        echo '<h1>Orphan Entities</h1>';

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

        echo '<h1>Identical Intent Outcomes</h1>';

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

        echo '<h1>Identical Entity Names</h1>';

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

    } elseif($action=='compose_test_message') {

        echo '<h1>Test Compose Message</h1>';

        if(isset($_POST['test_message'])){

            $msg_validation = $this->Chat_model->dispatch_validate_message($_POST['test_message'], ( intval($_POST['recipient_en']) ? array('en_id' => $_POST['recipient_en']) : array() ), $_POST['fb_messenger_format']);

            if($_POST['fb_messenger_format'] || !$msg_validation['status']){
                echo_json(array(
                    'analyze' => extract_message_references($_POST['test_message']),
                    'results' => $msg_validation,
                ));
            } else {
                //HTML:
                echo '<div><a href="/admin/tools/compose_test_message"> &laquo; Back to Message Compose</a></div><hr />';
                echo $msg_validation['output_messages'][0]['message_body'];
            }

        } else {

            //UI to compose a test message:
            echo '<form method="POST" action="">';

            echo '<div class="mini-header">Message:</div>';
            echo '<textarea name="test_message" style="width:400px; height: 200px;"></textarea><br />';

            echo '<div class="mini-header">Recipient Entity ID:</div>';
            echo '<input type="number" name="recipient_en" value="1"><br />';

            echo '<div class="mini-header">Format Is Messenger:</div>';
            echo '<input type="number" name="fb_messenger_format" value="0"><br /><br />';


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