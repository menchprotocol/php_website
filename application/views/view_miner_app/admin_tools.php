<?php


//Define all moderation functions:
$en_all_4737 = $this->config->item('en_all_4737'); // Intent Statuses
$en_all_6177 = $this->config->item('en_all_6177'); //Entity Statuses

$moderation_tools = array(
    '/miner_app/admin_tools/link_words_stats' => 'Link Words Stats',
    '/miner_app/admin_tools/in_replace_outcomes' => 'Intent Search/Replace Outcomes',
    '/miner_app/admin_tools/in_invalid_outcomes' => 'Intent Invalid Outcomes',
    '/miner_app/admin_tools/actionplan_debugger' => 'My Action Plan Debugger',
    '/miner_app/admin_tools/en_icon_search' => 'Entity Icon Search',
    '/miner_app/admin_tools/moderate_intent_notes' => 'Moderate Intent Notes',
    '/miner_app/admin_tools/identical_intent_outcomes' => 'Identical Intent Outcomes',
    '/miner_app/admin_tools/identical_entity_names' => 'Identical Entity Names',
    '/miner_app/admin_tools/or__children' => 'List OR Intents + Answers',
    '/miner_app/admin_tools/orphan_intents' => 'Orphan Intents',
    '/miner_app/admin_tools/orphan_entities' => 'Orphan Entities',
    '/miner_app/admin_tools/assessment_marks_list_all' => 'Completion Marks List All',
    '/miner_app/admin_tools/assessment_marks_birds_eye' => 'Completion Marks Birds Eye View',
    '/miner_app/admin_tools/compose_test_message' => 'Compose Test Message',
    '/miner_app/admin_tools/sync_in_verbs' => 'Sync Intent Verbs',
);

$cron_jobs = array(
    '/intents/cron__sync_common_base' => 'Sync Common Base Metadata',
    '/intents/cron__sync_extra_insights' => 'Sync Extra Insights Metadata',
    '/entities/cron__update_trust_score' => 'Update All Entity Trust Scores',
    '/links/cron__sync_algolia' => 'Sync Algolia Index [Limited calls!]',
    '/links/cron__sync_gephi' => 'Sync Gephi Graph Index',
    '/links/cron__clean_metadatas' => 'Clean Unused Metadata Variables',
);


$developer_tools = array(
    '/miner_app/platform_cache' => 'Platform PHP Cache',
    '/miner_app/my_session' => 'My Session Variables',
    '/miner_app/php_info' => 'Server PHP Info',
);



if(!$action) {

    $en_all_7368 = $this->config->item('en_all_7368');
    echo '<h1>'.$en_all_7368[6287]['m_icon'].' '.$en_all_7368[6287]['m_name'].' <a href="/entities/6287" style="font-size: 0.5em; color: #999;" title="'.$en_all_7368[6287]['m_name'].' entity controlling this tool" data-toggle="tooltip" data-placement="right">@6287</a></h1>';

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

} elseif($action=='link_words_stats') {



    //Show breadcrumb:
    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';


    if(isset($_GET['resetall'])){

        boost_power();
        $this->db->query("UPDATE table_links SET ln_words=0;");
        echo '<div class="alert alert-warning">All link counts reset to zero.</div>';

    } elseif(isset($_GET['updateall']) || isset($_GET['updatesome'])){

        //Go through all the links and update their words:
        boost_power();
        $updated = 0;
        foreach($this->Links_model->ln_fetch(( isset($_GET['updateall']) ? array(
            'ln_words' => 0,
        ) : array(
            'ln_type_entity_id IN (' . $_GET['updatesome'] . ')' => null,
        )), array(), 0) as $ln){
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_words' => ln_type_word_count($ln),
            ));
            $updated++;
        }
        echo '<div class="alert alert-warning">'.$updated.' links updated with new word counts.</div>';

    }


    echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';

    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;">Group</td>';
    echo '<td style="text-align: left;">Links</td>';
    echo '<td style="text-align: left;">%</td>';
    echo '<td style="text-align: left;">Words</td>';
    echo '<td style="text-align: left;">%</td>';
    echo '<td style="text-align: left;">Words/Link</td>';
    echo '</tr>';


    //Count them all:
    $all_stats = $this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array(), 0, 0, array(), 'COUNT(ln_id) as total_links, SUM(ABS(ln_words)) as total_words');


    echo '<tr class="panel-title down-border" style="font-weight: bold;">';
    echo '<td style="text-align: left;">Total</td>';
    echo '<td style="text-align: left;">'.number_format($all_stats[0]['total_links'], 0).'</td>';
    echo '<td style="text-align: left;">100%</td>';
    echo '<td style="text-align: left;">'.number_format(round($all_stats[0]['total_words']), 0).'</td>';
    echo '<td style="text-align: left;">100%</td>';
    echo '<td style="text-align: left;">'.number_format(($all_stats[0]['total_words']/$all_stats[0]['total_links']), 2).'</td>';
    echo '</tr>';

    //Add some empty space:
    echo '<tr class="panel-title down-border"><td style="text-align: left;" colspan="6">&nbsp;</td></tr>';


    //Now do a high level stats:
    foreach (array('ln_words =', 'ln_words >', 'ln_words <') as $words_setting) {

        $words_stats = $this->Links_model->ln_fetch(array(
            $words_setting => 0,
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_links, SUM(ln_words) as total_words');

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$words_setting.' 0</td>';
        echo '<td style="text-align: left;">'.number_format($words_stats[0]['total_links'], 0).'</td>';
        echo '<td style="text-align: left;">'.number_format($words_stats[0]['total_links']/$all_stats[0]['total_links']*100, 2).'%</td>';
        echo '<td style="text-align: left;">'.number_format(round($words_stats[0]['total_words']), 0).'</td>';
        echo '<td style="text-align: left;">'.( $all_stats[0]['total_words']>0 ? number_format($words_stats[0]['total_words']/$all_stats[0]['total_words']*100, 2) : '0.00' ).'%</td>';
        echo '<td style="text-align: left;">'.( $words_stats[0]['total_links']>0 ? number_format(($words_stats[0]['total_words']/$words_stats[0]['total_links']), 2) : '0.00' ).'</td>';
        echo '</tr>';

    }


    $en_all_10592 = $this->config->item('en_all_10592');
    $en_all_10591 = $this->config->item('en_all_10591');

    //Add some empty space:
    echo '<tr class="panel-title down-border"><td style="text-align: left;" colspan="6">&nbsp;</td></tr>';

    //Show each link type:
    foreach ($this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array('ln_type'), 0, 0, array('total_words' => 'DESC'), 'COUNT(ln_id) as total_links, SUM(ln_words) as total_words, en_name, en_icon, en_id', 'en_id, en_name, en_icon') as $ln) {

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;"><span class="icon-block">'.$ln['en_icon'].'</span> <a href="/entities/'.$ln['en_id'].'">'.$ln['en_name'].'</a></td>';
        echo '<td style="text-align: left;">'.number_format($ln['total_links'], 0).'</td>';
        echo '<td style="text-align: left;">'.number_format($ln['total_links']/$all_stats[0]['total_links']*100, 2).'%</td>';
        echo '<td style="text-align: left;"><span class="icon-block">'.( in_array($ln['en_id'], $this->config->item('en_ids_10589')) ? $en_all_10591[10589]['m_icon'] : $en_all_10591[10590]['m_icon'] ).'</span>'.number_format(round($ln['total_words']), 0).'</td>';
        echo '<td style="text-align: left;">'.number_format($ln['total_words']/$all_stats[0]['total_words']*100, 2).'%</td>';
        echo '<td style="text-align: left;"><span class="icon-block">'.( in_array($ln['en_id'], $this->config->item('en_ids_10596')) ? $en_all_10592[10596]['m_icon'] : ( in_array($ln['en_id'], $this->config->item('en_ids_10593')) ? $en_all_10592[10593]['m_icon'] : $en_all_10592[10594]['m_icon'] )).'</span>'.( $ln['total_links']>0 ? number_format(($ln['total_words']/$ln['total_links']), 2) : '0.00' ).'</td>';
        echo '</tr>';

    }

    echo '</table>';


} elseif($action=='moderate_intent_notes'){

    //Fetch pending notes:
    $pendin_in_notes = $this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
    ), array('in_child'), $this->config->item('items_per_page'), 0, array('ln_id' => 'ASC'));

    echo '<div class="row">';
    echo '<div class="'.$this->config->item('css_column_1').'">';
    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';
    //List intents and allow to modify and manage intent notes:
    if(count($pendin_in_notes) > 0){
        foreach($pendin_in_notes as $pendin_in_note){
            echo echo_in($pendin_in_note, 0);
        }
    } else {
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> No Pending Intent Notes at this time</div>';
    }

    echo '</div>';
    echo '<div class="'.$this->config->item('css_column_2').'">';
    $this->load->view('view_miner_app/in_right_column');
    echo '</div>';
    echo '</div>';


} elseif($action=='orphan_intents') {

    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    $orphan_ins = $this->Intents_model->in_fetch(array(
        ' NOT EXISTS (SELECT 1 FROM table_links WHERE in_id=ln_child_intent_id AND ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ') AND ln_status_entity_id IN ('.join(',', $this->config->item('en_ids_7360')) /* Link Statuses Active */.')) ' => null,
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        'in_id !=' => $this->config->item('in_mission_id'), //Mission does not have parents
        'in_id NOT IN (' . join(',', $this->config->item('in_system_lock')) . ')' => null,
    ));

    if(count($orphan_ins) > 0){

        //List orphans:
        foreach ($orphan_ins as $count => $orphan_in) {

            //Show intent:
            echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$orphan_in['in_status_entity_id']]['m_name'].': '.$en_all_4737[$orphan_in['in_status_entity_id']]['m_desc'].'">' . $en_all_4737[$orphan_in['in_status_entity_id']]['m_icon'] . '</span> <a href="/intents/'.$orphan_in['in_id'].'"><b>'.$orphan_in['in_outcome'].'</b></a>';

            //Do we need to remove?
            if($command1=='remove_all'){

                //Remove intent links:
                $links_removed = $this->Intents_model->in_unlink($orphan_in['in_id'] , $session_en['en_id']);

                //Remove intent:
                $this->Intents_model->in_update($orphan_in['in_id'], array(
                    'in_status_entity_id' => 6182, /* Intent Removed */
                ), true, $session_en['en_id']);

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
            echo '<a class="remove-all hidden maxout" href="/miner_app/admin_tools/orphan_intents/remove_all" onclick="">Confirm: <b>Remove All</b> &raquo;</a>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No orphans found!</div>';
    }

} elseif($action=='orphan_entities') {

    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    $orphan_ens = $this->Entities_model->en_fetch(array(
        ' NOT EXISTS (SELECT 1 FROM table_links WHERE en_id=ln_child_entity_id AND ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ') AND ln_status_entity_id IN ('.join(',', $this->config->item('en_ids_7360')) /* Link Statuses Active */.')) ' => null,
        'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
    ), array('skip_en__parents'));

    if(count($orphan_ens) > 0){

        //List orphans:
        foreach ($orphan_ens  as $count => $orphan_en) {

            //Show entity:
            echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$orphan_en['en_status_entity_id']]['m_name'].': '.$en_all_6177[$orphan_en['en_status_entity_id']]['m_desc'].'">' . $en_all_6177[$orphan_en['en_status_entity_id']]['m_icon'] . '</span> <a href="/entities/'.$orphan_en['en_id'].'"><b>'.$orphan_en['en_name'].'</b></a>';

            //Do we need to remove?
            if($command1=='remove_all'){

                //Remove links:
                $links_removed = $this->Entities_model->en_unlink($orphan_en['en_id'], $session_en['en_id']);

                //Remove entity:
                $this->Entities_model->en_update($orphan_en['en_id'], array(
                    'en_status_entity_id' => 6178, /* Entity Removed */
                ), true, $session_en['en_id']);

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
            echo '<a class="remove-all hidden maxout" href="/miner_app/admin_tools/orphan_entities/remove_all" onclick="">Confirm: <b>Remove All</b> &raquo;</a>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No orphans found!</div>';
    }
    

} elseif($action=='en_icon_search') {

    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    //UI to compose a test message:
    echo '<form method="GET" action="">';

    echo '<div class="mini-header">Search For:</div>';
    echo '<input type="text" class="form-control border maxout" name="search_for" value="'.@$_GET['search_for'].'"><br />';


    if(isset($_GET['search_for']) && strlen($_GET['search_for'])>0){

        $matching_results = $this->Entities_model->en_fetch(array(
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
            'LOWER(en_icon) LIKE \'%'.strtolower($_GET['search_for']).'%\'' => null,
        ));

        //List the matching search:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';


        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.count($matching_results).' Results found</td>';
        echo '</tr>';


        if(count($matching_results) > 0){

            echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
            echo '<td style="text-align: left;">#</td>';
            echo '<td style="text-align: left;">Matching Search</td>';
            echo '</tr>';

            foreach($matching_results as $count=>$en){

                echo '<tr class="panel-title down-border">';
                echo '<td style="text-align: left;">'.($count+1).'</td>';
                echo '<td style="text-align: left;">'.echo_en_cache('en_all_6177' /* Entity Statuses */, $en['en_status_entity_id'], true, 'right').' <span class="icon-block">'.echo_en_icon($en).'</span><a href="/entities/'.$en['en_id'].'">'.$en['en_name'].'</a></td>';
                echo '</tr>';

            }
        }

        echo '</table>';
    }


    echo '<input type="submit" class="btn btn-primary" value="Search">';
    echo '</form>';

} elseif($action=='actionplan_debugger') {

    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    //List this users Action Plan intents so they can choose:
    echo '<div>Choose one of your action plan intentions to debug:</div><br />';

    $user_intents = $this->Links_model->ln_fetch(array(
        'ln_creator_entity_id' => $session_en['en_id'],
        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
    ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

    foreach ($user_intents as $priority => $ln) {
        echo '<div>'.($priority+1).') <a href="/messenger/debug/' . $ln['in_id'] . '">' . echo_in_outcome($ln['in_outcome']) . '</a></div>';
    }

} elseif($action=='in_invalid_outcomes') {

    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    $active_ins = $this->Intents_model->in_fetch(array(
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
    ));

    //Give an overview:
    echo '<p>When the validation criteria change within the in_validate_outcome() function, this page lists all the intents that no longer have a valid outcome.</p>';


    //List the matching search:
    echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';


    echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
    echo '<td style="text-align: left;">#</td>';
    echo '<td style="text-align: left;">Invalid Outcome</td>';
    echo '</tr>';

    $invalid_outcomes = 0;
    foreach($active_ins as $count=>$in){

        $in_outcome_validation = $this->Intents_model->in_validate_outcome($in['in_outcome'], $in['in_scope_entity_id']);

        if(!$in_outcome_validation['status']){

            $invalid_outcomes++;

            //Update intent:
            echo '<tr class="panel-title down-border">';
            echo '<td style="text-align: left;">'.$invalid_outcomes.'</td>';
            echo '<td style="text-align: left;">'.echo_en_cache('en_all_4737' /* Intent Statuses */, $in['in_status_entity_id'], true, 'right').' <a href="/intents/'.$in['in_id'].'">'.echo_in_outcome($in['in_outcome']).'</a></td>';
            echo '</tr>';

        }

    }
    echo '</table>';

} elseif($action=='in_replace_outcomes') {


    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    //UI to compose a test message:
    echo '<form method="GET" action="">';

    echo '<div class="mini-header">Search For:</div>';
    echo '<input type="text" class="form-control border maxout" name="search_for" value="'.@$_GET['search_for'].'"><br />';


    $search_for_is_set = (isset($_GET['search_for']) && strlen($_GET['search_for'])>0);
    $replace_with_is_set = (isset($_GET['replace_with']) && strlen($_GET['replace_with'])>0);
    $qualifying_replacements = 0;
    $completed_replacements = 0;
    $replace_with_is_confirmed = false;

    if($search_for_is_set){

        $matching_results = $this->Intents_model->in_fetch(array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            'LOWER(in_outcome) LIKE \'%'.strtolower($_GET['search_for']).'%\'' => null,
        ));

        //List the matching search:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';


        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="4">'.count($matching_results).' Results found</td>';
        echo '</tr>';


        if(count($matching_results) < 1){

            $replace_with_is_set = false;
            unset($_GET['confirm_statement']);
            unset($_GET['replace_with']);

        } else {

            $confirmation_keyword = 'Replace '.count($matching_results);
            $replace_with_is_confirmed = (isset($_GET['confirm_statement']) && strtolower($_GET['confirm_statement'])==strtolower($confirmation_keyword));

            echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
            echo '<td style="text-align: left;">#</td>';
            echo '<td style="text-align: left;">Matching Search</td>';
            echo '<td style="text-align: left;">'.( $replace_with_is_set ? 'Replacement' : '' ).'</td>';
            echo '<td style="text-align: left;">&nbsp;</td>';
            echo '</tr>';

            foreach($matching_results as $count=>$in){

                if($replace_with_is_set){
                    //Do replacement:
                    $new_outcome = str_replace($_GET['search_for'],$_GET['replace_with'],$in['in_outcome']);
                    $in_outcome_validation = $this->Intents_model->in_validate_outcome($new_outcome, $in['in_scope_entity_id']);

                    if($in_outcome_validation['status']){
                        $qualifying_replacements++;
                    }
                }

                if($replace_with_is_confirmed && $in_outcome_validation['status']){
                    //Update intent:
                    $this->Intents_model->in_update($in['in_id'], array(
                        'in_outcome' => $in_outcome_validation['in_cleaned_outcome'],
                        'in_verb_entity_id' => $in_outcome_validation['detected_in_verb_entity_id'],
                    ), true, $session_en['en_id']);
                }

                echo '<tr class="panel-title down-border">';
                echo '<td style="text-align: left;">'.($count+1).'</td>';
                echo '<td style="text-align: left;">'.echo_en_cache('en_all_4737' /* Intent Statuses */, $in['in_status_entity_id'], true, 'right').' <a href="/intents/'.$in['in_id'].'">'.$in['in_outcome'].'</a></td>';

                if($replace_with_is_set){

                    echo '<td style="text-align: left;">'.$new_outcome.'</td>';
                    echo '<td style="text-align: left;">'.( !$in_outcome_validation['status'] ? ' <i class="fas fa-exclamation-triangle"></i> Error: '.$in_outcome_validation['message'] : ( $replace_with_is_confirmed && $in_outcome_validation['status'] ? '<i class="fas fa-check-circle"></i> Outcome Updated' : '') ).'</td>';
                } else {
                    //Show parents now:
                    echo '<td style="text-align: left;">';


                    //Loop through parents:
                    $en_all_7585 = $this->config->item('en_all_7585'); // Intent Subtypes
                    foreach ($this->Links_model->ln_fetch(array(
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                        'ln_child_intent_id' => $in['in_id'],
                    ), array('in_parent')) as $in_parent) {
                        echo '<span class="in_child_icon_' . $in_parent['in_id'] . '"><a href="/intents/' . $in_parent['in_id'] . '" data-toggle="tooltip" title="' . $in_parent['in_outcome'] . '" data-placement="bottom">' . $en_all_7585[$in_parent['in_subtype_entity_id']]['m_icon'] . '</a> &nbsp;</span>';
                    }

                    echo '</td>';
                    echo '<td style="text-align: left;"></td>';
                }


                echo '</tr>';

            }
        }

        echo '</table>';
    }


    if($search_for_is_set && count($matching_results) > 0 && !$completed_replacements){
        //now give option to replace with:
        echo '<div class="mini-header">Replace With:</div>';
        echo '<input type="text" class="form-control border maxout" name="replace_with" value="'.@$_GET['replace_with'].'"><br />';
    }

    if($replace_with_is_set && !$completed_replacements){
        if($qualifying_replacements==count($matching_results) /*No Errors*/){
            //now give option to replace with:
            echo '<div class="mini-header">Confirm Replacement by Typing "'.$confirmation_keyword.'":</div>';
            echo '<input type="text" class="form-control border maxout" name="confirm_statement" value="'. @$_GET['confirm_statement'] .'"><br />';
        } else {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Fix errors above to then apply search/replace</div>';
        }
    }


    echo '<input type="submit" class="btn btn-primary" value="Go">';
    echo '</form>';


} elseif($action=='sync_in_verbs') {

    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    //Would ensure intents have synced statuses:
    $count = 0;
    $fixed = 0;
    foreach($this->Intents_model->in_fetch() as $in){

        $count++;

        //Validate Intent Outcome:
        $in_outcome_validation = $this->Intents_model->in_validate_outcome($in['in_outcome'], $in['in_scope_entity_id']);
        if(!$in_outcome_validation['status']){

            echo '<div>Outcome validation error: '.$in_outcome_validation['message'].' (<a href="/intents/'.$in['in_id'].'">'.$in['in_outcome'].'</a>)</div>';

        } elseif($in_outcome_validation['detected_in_verb_entity_id'] != $in['in_verb_entity_id']) {

            //Not a match, fix it:
            $fixed++;
            $this->Intents_model->in_update($in['in_id'], array(
                'in_verb_entity_id' => $in_outcome_validation['detected_in_verb_entity_id'],
            ), true, $session_en['en_id']);

        }

    }

    echo '<div>'.$fixed.'/'.$count.' Intent verbs fixed</div>';

} elseif($action=='identical_intent_outcomes') {

    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    //Do a query to detect intents with the exact same title:
    $q = $this->db->query('select in1.* from table_intents in1 where (select count(*) from table_intents in2 where in2.in_outcome = in1.in_outcome AND in2.in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')) > 1 AND in1.in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ') ORDER BY in1.in_outcome ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        foreach ($duplicates as $in) {
            if ($prev_title != $in['in_outcome']) {
                echo '<hr />';
                $prev_title = $in['in_outcome'];
            }

            echo '<div><span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$in['in_status_entity_id']]['m_name'].': '.$en_all_4737[$in['in_status_entity_id']]['m_desc'].'">' . $en_all_4737[$in['in_status_entity_id']]['m_icon'] . '</span> <a href="/intents/' . $in['in_id'] . '"><b>' . $in['in_outcome'] . '</b></a> #' . $in['in_id'] . '</div>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No duplicates found!</div>';
    }

} elseif($action=='identical_entity_names') {

    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    $q = $this->db->query('select en1.* from table_entities en1 where (select count(*) from table_entities en2 where en2.en_name = en1.en_name AND en2.en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')) > 1 AND en1.en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ') ORDER BY en1.en_name ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        foreach ($duplicates as $en) {

            if ($prev_title != $en['en_name']) {
                echo '<hr />';
                $prev_title = $en['en_name'];
            }

            echo '<span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$en['en_status_entity_id']]['m_name'].': '.$en_all_6177[$en['en_status_entity_id']]['m_desc'].'">' . $en_all_6177[$en['en_status_entity_id']]['m_icon'] . '</span> <a href="/entities/' . $en['en_id'] . '"><b>' . $en['en_name'] . '</b></a> @' . $en['en_id'] . '<br />';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No duplicates found!</div>';
    }

} elseif($action=='or__children') {

    echo '<br /><p>Active <a href="/entities/6914">Intent Answer Types</a> are listed below.</p><br />';

    $all_steps = 0;
    $all_children = 0;
    $updated = 0;
    $new_ln_type_entity_id = 7485; //User Step Answer Unlock

    foreach ($this->Intents_model->in_fetch(array(
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        'in_subtype_entity_id IN (' . join(',', $this->config->item('en_ids_7712')) . ')' => null,
    ), array(), 0, 0, array('in_id' => 'DESC')) as $count => $in) {

        echo '<div>'.($count+1).') '.echo_en_cache('en_all_4737' /* Intent Statuses */, $in['in_status_entity_id']).' '.echo_en_cache('en_all_6193' /* OR Intents */, $in['in_subtype_entity_id']).' <b><a href="https://mench.com/intents/'.$in['in_id'].'">'.echo_in_outcome($in['in_outcome']).'</a></b></div>';

        echo '<ul>';
        //Fetch all children for this OR:
        foreach($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $child_or){

            $qualified_update = ( $child_or['in_subtype_entity_id']==6677 /* Intent Read-Only */ && in_array($child_or['in_scope_entity_id'], $this->config->item('en_ids_7582')) /* Intent Scopes Get Started */ );

            //Count completions:
            if($qualified_update){

                $user_steps = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
                    'ln_parent_intent_id' => $child_or['in_id'],
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                ), array(), 0);
                $all_steps += count($user_steps);

            } else {
                $user_steps = array();
            }

            $all_children++;
            echo '<li>'.echo_en_cache('en_all_6186' /* Link Statuses */, $child_or['ln_status_entity_id']).' '.echo_en_cache('en_all_4737' /* Intent Statuses */, $child_or['in_status_entity_id']).' '.echo_en_cache('en_all_7585', $child_or['in_subtype_entity_id']).' <a href="https://mench.com/intents/'.$child_or['in_id'].'" '.( $qualified_update ? '' : 'style="color:#FF0000;"' ).'>'.echo_in_outcome($child_or['in_outcome']).'</a>'.( count($user_steps) > 0 ? ' / Steps: '.count($user_steps) : '' ).'</li>';
        }
        echo '</ul>';
        echo '<hr />';
    }

    echo 'All Steps Taken: '.$all_steps.( $updated > 0 ? ' ('.$updated.' updated)' : '' ).' across '.$all_children.' answers';

} elseif($action=='assessment_marks_list_all') {


    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

    echo '<p>Below are all the Conditional Step Links:</p>';
    echo '<table class="table table-condensed table-striped maxout" style="text-align: left;">';

    $en_all_6103 = $this->config->item('en_all_6103'); //Link Metadata
    $en_all_6186 = $this->config->item('en_all_6186'); //Link Statuses

    echo '<tr style="font-weight: bold;">';
    echo '<td colspan="4" style="text-align: left;">'.$en_all_6103[6402]['m_icon'].' '.$en_all_6103[6402]['m_name'].'</td>';
    echo '</tr>';
    $counter = 0;
    $total_count = 0;
    foreach ($this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        'ln_type_entity_id' => 4229, //Intent Link Locked Step
        'LENGTH(ln_metadata) > 0' => null,
    ), array('in_child'), 0, 0) as $in_ln) {
        //Echo HTML format of this message:
        $metadata = unserialize($in_ln['ln_metadata']);
        $mark = echo_in_marks($in_ln);
        if($mark){

            //Fetch parent intent:
            $parent_ins = $this->Intents_model->in_fetch(array(
                'in_id' => $in_ln['ln_parent_intent_id'],
            ));

            $counter++;
            echo '<tr>';
            echo '<td style="width: 50px;">'.$counter.'</td>';
            echo '<td style="font-weight: bold; font-size: 1.3em; width: 100px;">'.echo_in_marks($in_ln).'</td>';
            echo '<td>'.$en_all_6186[$in_ln['ln_status_entity_id']]['m_icon'].'</td>';
            echo '<td style="text-align: left;">';

            echo '<div>';
            echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$parent_ins[0]['in_status_entity_id']]['m_icon'].'</span>';
            echo '<a href="/intents/'.$parent_ins[0]['in_id'].'">'.$parent_ins[0]['in_outcome'].'</a>';
            echo '</div>';

            echo '<div>';
            echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$in_ln['in_status_entity_id']]['m_icon'].'</span>';
            echo '<a href="/intents/'.$in_ln['in_id'].'">'.$in_ln['in_outcome'].' [child]</a>';
            echo '</div>';

            if(count($this->Links_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
                    'in_subtype_entity_id NOT IN (6907,6914)' => null, //NOT AND/OR Lock
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_child_intent_id' => $in_ln['in_id'],
                ), array('in_parent'))) > 1 || $in_ln['in_subtype_entity_id'] != 6677){

                echo '<div>';
                echo 'NOT COOL';
                echo '</div>';

            } else {

                //Update user progression link type:
                $user_steps = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
                    'ln_parent_intent_id' => $in_ln['in_id'],
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                ), array(), 0);

                $updated = 0;

                echo '<div>Total Steps: '.count($user_steps).'</div>';
                $total_count += count($user_steps);

            }

            echo '</td>';
            echo '</tr>';

        }
    }

    echo '</table>';

    echo 'TOTALS: '.$total_count;

    if(1){
        echo '<p>Below are all the fixed step links that award/subtract Completion Marks:</p>';
        echo '<table class="table table-condensed table-striped maxout" style="text-align: left;">';

        echo '<tr style="font-weight: bold;">';
        echo '<td colspan="4" style="text-align: left;">Completion Marks</td>';
        echo '</tr>';

        $counter = 0;
        foreach ($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
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


                //Update Completion Marks if outside of range (Handy if in_completion_marks values are reduced)
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
                echo '<td style="font-weight: bold; font-size: 1.3em; width: 100px;">'.echo_in_marks($in_ln).'</td>';
                echo '<td>'.$en_all_6186[$in_ln['ln_status_entity_id']]['m_icon'].'</td>';
                echo '<td style="text-align: left;">';
                echo '<div>';
                echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$parent_ins[0]['in_status_entity_id']]['m_icon'].'</span>';
                echo '<a href="/intents/'.$parent_ins[0]['in_id'].'">'.$parent_ins[0]['in_outcome'].'</a>';
                echo '</div>';

                echo '<div>';
                echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$in_ln['in_status_entity_id']]['m_icon'].'</span>';
                echo '<a href="/intents/'.$in_ln['in_id'].'">'.$in_ln['in_outcome'].'</a>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';

            }
        }

        echo '</table>';
    }

} elseif($action=='assessment_marks_birds_eye') {

    //Give an overview of the point links in a hierchial format to enable miners to overview:
    $_GET['starting_in']    = ( isset($_GET['starting_in']) && intval($_GET['starting_in']) > 0 ? $_GET['starting_in'] : $this->session->userdata('user_default_intent') );
    $_GET['depth_levels']   = ( isset($_GET['depth_levels']) && intval($_GET['depth_levels']) > 0 ? $_GET['depth_levels'] : 3 );

    echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';


    echo '<form method="GET" action="">';

    echo '<div class="score_range_box">
            <div class="form-group label-floating is-empty"
                 style="max-width:550px; margin:1px 0 10px; display: inline-block;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">Start at #</span>
                    <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="starting_in" id="starting_in" value="'.$_GET['starting_in'].'" class="form-control">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc;"> and go </span>
                    <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="depth_levels" id="depth_levels" value="'.$_GET['depth_levels'].'" class="form-control">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc; border-right:0px solid #FFF;"> levels deep.</span>
                </div>
            </div>
            <input type="submit" class="btn btn-primary btn-sm" value="Go" style="display: inline-block; margin-top: -41px;" />
        </div>';

    echo '</form>';

    //Load the report via Ajax here on page load:
    echo '<div id="in_report_conditional_steps"></div>';
    echo '<script>

$(document).ready(function () {
//Show spinner:
$(\'#in_report_conditional_steps\').html(\'<span><i class="fas fa-yin-yang fa-spin"></i> \' + echo_ying_yang() +  \'</span>\').hide().fadeIn();
//Load report based on input fields:
$.post("/intents/in_report_conditional_steps", {
    starting_in: parseInt($(\'#starting_in\').val()),
    depth_levels: parseInt($(\'#depth_levels\').val()),
}, function (data) {
    if (!data.status) {
        //Show Error:
        $(\'#in_report_conditional_steps\').html(\'<span style="color:#FF0000;">Error: \'+ data.message +\'</span>\');
    } else {
        //Load Report:
        $(\'#in_report_conditional_steps\').html(data.message);
        $(\'[data-toggle="tooltip"]\').tooltip();
    }
});
});

</script>';


} elseif($action=='compose_test_message') {


    if(isset($_POST['test_message'])){

        echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><a href="/miner_app/admin_tools/'.$action.'">'.$moderation_tools['/miner_app/admin_tools/'.$action].'</a></li><li><b>Review Message</b></li></ul>';

        if(intval($_POST['push_message']) && intval($_POST['recipient_en'])){

            //Send to Facebook Messenger:
            $msg_validation = $this->Communication_model->dispatch_message(
                $_POST['test_message'],
                array('en_id' => intval($_POST['recipient_en'])),
                true
            );

        } elseif(intval($_POST['recipient_en']) > 0) {

            $msg_validation = $this->Communication_model->dispatch_validate_message($_POST['test_message'], array('en_id' => $_POST['recipient_en']), $_POST['push_message']);

        } else {

            echo 'Missing recipient';

        }

        //Show results:
        print_r($msg_validation);

    } else {

        echo '<ul class="breadcrumb"><li><a href="/miner_app/admin_tools">Admin Tools</a></li><li><b>'.$moderation_tools['/miner_app/admin_tools/'.$action].'</b></li></ul>';

        //UI to compose a test message:
        echo '<form method="POST" action="" class="maxout">';

        echo '<div class="mini-header">Message:</div>';
        echo '<textarea name="test_message" class="form-control border" style="width:400px; height: 200px;"></textarea><br />';

        echo '<div class="mini-header">Recipient Entity ID:</div>';
        echo '<input type="number" class="form-control border" name="recipient_en" value="1"><br />';

        echo '<div class="mini-header">Format Is Messenger:</div>';
        echo '<input type="number" class="form-control border" name="push_message" value="1"><br /><br />';


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