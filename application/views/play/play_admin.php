<?php

echo '<div class="container">';

//Define all moderation functions:
$en_all_4737 = $this->config->item('en_all_4737'); // Idea Statuses
$en_all_6177 = $this->config->item('en_all_6177'); //Player Statuses
$en_all_4463 = $this->config->item('en_all_4463'); //GLOSSARY

$moderation_tools = array(

    //Moderator Tools
    '/play/play_admin/link_coins_words_stats' => 'Coin Stats',
    '/play/play_admin/orphan_ideas' => 'Orphan Ideas',
    '/play/play_admin/orphan_players' => 'Orphan Players',
    '/play/play_admin/in_replace_outcomes' => 'Idea Search/Replace Titles',
    '/play/play_admin/in_invalid_outcomes' => 'Idea Invalid Titles',
    '/play/play_admin/identical_idea_outcomes' => 'Identical Idea Titles',
    '/play/play_admin/identical_player_names' => 'Identical Player Names',
    '/play/play_admin/actionplan_debugger' => 'My READING LIST Debugger',
    '/play/play_admin/en_icon_search' => 'Player Icon Search',
    '/play/play_admin/sync_player_links' => 'Player Sync Link Types',
    '/play/play_admin/or__children' => 'List OR Ideas + Answers',
    '/play/play_admin/assessment_marks_list_all' => 'Completion Marks List All',
    '/play/play_admin/assessment_marks_birds_eye' => 'Completion Marks Birds Eye View',
    '/play/play_admin/compose_test_message' => 'Compose Test Message',
    '/play/play_admin/random_player_avatar' => 'Random User Icons',

    //Hope to get zero:
    '/play/play_admin/sync_play_idea_statuses' => 'Analyze & Fix Play & Idea Statuses',
    '/play/play_admin/analyze_play' => 'Analyze & Fix Player Links',
    '/play/play_admin/in_crossovers' => 'Analyze & Fix Idea Crossover Parent/Children',
    '/play/play_admin/analyze_idea_authors' => 'Analyze & Fix Idea Authors',
);

$cron_jobs = array(
    '/idea/cron__sync_common_base' => 'Sync Common Base Metadata',
    '/idea/cron__sync_extra_insights' => 'Sync Extra Insights Metadata',
    '/read/cron__sync_algolia' => 'Sync Algolia Index [Limited calls!]',
    '/read/cron__sync_gephi' => 'Sync Gephi Graph Index',
    '/read/cron__clean_metadatas' => 'Clean Unused Metadata Variables',
);


$developer_tools = array(
    '/play/platform_cache' => 'Platform PHP Cache',
    '/play/my_session' => 'My Session Variables',
    '/play/php_info' => 'Server PHP Info',
);



if(!$action) {

    $en_all_11035 = $this->config->item('en_all_11035');
    echo '<h1>'.$en_all_11035[6287]['m_icon'].' '.$en_all_11035[6287]['m_name'].' <a href="/play/6287" style="font-size: 0.5em; color: #AAAAAA;" title="'.$en_all_11035[6287]['m_name'].' player controlling this tool" data-toggle="tooltip" data-placement="right">@6287</a></h1>';

    echo '<div class="list-group maxout">';
    foreach ($moderation_tools as $tool_key => $tool_name) {
        echo '<a href="' . $tool_key . '" class="list-group-item">';
        echo '<span class="pull-right">';
        echo '<span class="badge badge-primary fr-bgd"><i class="fad fa-step-forward"></i></span>';
        echo '</span>';
        echo '<b class="montserrat">'.$tool_name.'</b>';
        echo '</a>';
    }
    echo '</div>';


    echo '<h1>Developer Tools</h1>';
    echo '<div class="list-group maxout">';
    foreach ($developer_tools as $tool_key => $tool_name) {
        echo '<a href="' . $tool_key . '" target="_blank" class="list-group-item">';
        echo '<span class="pull-right">';
        echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-external-link"></i></span>';
        echo '</span>';
        echo '<b class="montserrat">'.$tool_name.'</b>';
        echo '</a>';

    }
    echo '</div>';



    echo '<h1>Automated Cron Jobs</h1>';
    echo '<div class="list-group maxout">';
    foreach ($cron_jobs as $tool_key => $tool_name) {
        echo '<a href="' . $tool_key . '" target="_blank" class="list-group-item">';
        echo '<span class="pull-right">';
        echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-external-link"></i></span>';
        echo '</span>';
        echo '<b class="montserrat">'.$tool_name.'</b>';
        echo '</a>';

    }
    echo '</div>';

} elseif($action=='link_coins_words_stats') {



    //Show breadcrumb:
    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';


    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';

    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;">Transaction Type</td>';
    echo '<td style="text-align: left;">Coins</td>';
    echo '</tr>';


    //Count them all:
    $en_all_12140 = $this->config->item('en_all_12140');

    $full_coins = $this->READ_model->ln_fetch(array(
        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12141')) . ')' => null, //Full
        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array(), 0, 0, array(), 'COUNT(ln_id) as total_transactions');
    echo '<tr class="panel-title down-border" style="font-weight: bold;">';
    echo '<td style="text-align: left;" class="montserrat doupper">'.$en_all_12140[12141]['m_icon'].' '.$en_all_12140[12141]['m_name'].'</td>';
    echo '<td style="text-align: left;">'.number_format($full_coins[0]['total_transactions'], 0).'</td>';
    echo '</tr>';


    //Add some empty space:
    echo '<tr class="panel-title down-border"><td style="text-align: left;" colspan="4">&nbsp;</td></tr>';

    //Show each link type:
    foreach ($this->READ_model->ln_fetch(array(
        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12141')) . ')' => null, //Full
        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array('en_type'), 0, 0, array('total_transactions' => 'DESC'), 'COUNT(ln_id) as total_transactions, en_name, en_icon, en_id, ln_type_play_id', 'en_id, en_name, en_icon, ln_type_play_id') as $ln) {

        //Determine which weight group this belongs to:
        $direction = filter_cache_group($ln['en_id'], 2738);

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;"><span class="icon-block">'.$ln['en_icon'].'</span><a href="/play/'.$ln['en_id'].'" class="montserrat doupper">'.$ln['en_name'].'</a></td>';
        echo '<td style="text-align: left;"><span class="icon-block">'.$direction['m_icon'].'</span>'.number_format($ln['total_transactions'], 0).'</td>';
        echo '</tr>';

    }

    echo '</table>';

} elseif($action=='random_player_avatar'){

    //Show breadcrumb:
    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    if(isset($_GET['update_user_icons'])){

        $base_filters = array(
            'ln_parent_play_id' => 1278, //people
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
        );

        if(!isset($_GET['force'])) {
            $base_filters['(LENGTH(en_icon) < 1 OR en_icon IS NULL)'] = null;
        }

        $updated = 0;
        foreach($this->READ_model->ln_fetch($base_filters, array('en_child'), 0) as $mench_user){
            $updated += $this->PLAY_model->en_update($mench_user['en_id'], array(
                'en_icon' => random_player_avatar(),
            ));
        }
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> '.$updated.' User profiles updated with new random animal icons</div>';
    }

    for($i=0;$i<750;$i++){
        echo '<span class="icon-block">'.random_player_avatar().'</span>';
    }

} elseif($action=='analyze_idea_authors') {

    $stats = array(
        'ideas' => 0,
        'author_missing' => 0,
        'author_missing_fixed' => 0,
        'creator_missing' => 0,
        'author_duplicate' => 0,
    );

    //FInd and remove duplicate authors:
    foreach($this->IDEA_model->in_fetch() as $in) {

        $stats['ideas']++;

        $is_archived = !in_array($in['in_status_play_id'], $this->config->item('en_ids_7356'));

        //Scan authors:
        $idea_players = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 4983,
            'ln_child_idea_id' => $in['in_id'],
        ));
        $idea_creators = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 4250, //New Idea Created
            'ln_child_idea_id' => $in['in_id'],
        ));

        if(!count($idea_creators)){
            $stats['creator_missing']++;
        }

        if(!count($idea_players)){

            $stats['author_missing']++;

            if(count($idea_creators)){
                $this->READ_model->ln_create(array(
                    'ln_type_play_id' => 4983,
                    'ln_owner_play_id' => $idea_creators[0]['ln_owner_play_id'],
                    'ln_parent_play_id' => $idea_creators[0]['ln_owner_play_id'],
                    'ln_content' => '@'.$idea_creators[0]['ln_owner_play_id'],
                    'ln_child_idea_id' => $in['in_id'],
                ));
                $stats['author_missing_fixed']++;
            }

        } elseif(count($idea_players) >= 2){

            //See if duplicates:
            $found_duplicate = false;
            $authors = array();
            foreach($idea_players as $idea_player){
                if(!in_array($idea_player['ln_parent_play_id'], $authors)){
                    array_push($authors, $idea_player['ln_parent_play_id']);
                } else {
                    $found_duplicate = true;
                    break;
                }
            }

            if($found_duplicate){
                $stats['author_duplicate']++;
            }
        }
    }

    echo nl2br(print_r($stats, true));

} elseif($action=='analyze_play') {

    $stats = array(
        'play' => 0,
        'player' => 0,
        'ledger' => 0,
        'ledger_not_player_count' => 0,
        'player_not_ledger_count' => 0,
        'player_not_ledger_list' => array(),
    );

    foreach($this->PLAY_model->en_fetch() as $en) {

        $stats['play']++;

        $is_player = count($this->READ_model->ln_fetch(array(
            'ln_parent_play_id' => 4430, //Mench User
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
            'ln_child_play_id' => $en['en_id'],
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array(), 1));
        $is_ledger = count($this->READ_model->ln_fetch(array(
            'ln_owner_play_id' => $en['en_id'],
        ), array(), 1));

        if($is_player){
            $stats['player']++;
        }
        if($is_ledger){
            $stats['ledger']++;
        }
        if($is_player && !$is_ledger){
            $stats['player_not_ledger_count']++;
            array_push($stats['player_not_ledger_list'], $en);
        }
        if($is_ledger && !$is_player){
            $stats['ledger_not_player_count']++;
            $this->READ_model->ln_create(array(
                'ln_type_play_id' => 4230, //Raw link
                'ln_parent_play_id' => 4430, //Mench User
                'ln_owner_play_id' => $en['en_id'],
                'ln_child_play_id' => $en['en_id'],
            ));
        }

    }

    echo nl2br(print_r($stats, true));

} elseif($action=='orphan_ideas') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    $orphan_ins = $this->IDEA_model->in_fetch(array(
        ' NOT EXISTS (SELECT 1 FROM table_read WHERE in_id=ln_child_idea_id AND ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ') AND ln_status_play_id IN ('.join(',', $this->config->item('en_ids_7360')) /* Link Statuses Active */.')) ' => null,
        'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
        'in_id !=' => config_var(12156), //Not the Starting Idea
    ));

    if(count($orphan_ins) > 0){

        //List orphans:
        foreach ($orphan_ins as $count => $orphan_in) {

            //Show idea:
            echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$orphan_in['in_status_play_id']]['m_name'].': '.$en_all_4737[$orphan_in['in_status_play_id']]['m_desc'].'">' . $en_all_4737[$orphan_in['in_status_play_id']]['m_icon'] . '</span> <a href="/idea/'.$orphan_in['in_id'].'"><b>'.$orphan_in['in_title'].'</b></a>';

            //Do we need to remove?
            if($command1=='remove_all'){

                //Remove idea links:
                $links_removed = $this->IDEA_model->in_unlink($orphan_in['in_id'] , $session_en['en_id']);

                //Remove idea:
                $this->IDEA_model->in_update($orphan_in['in_id'], array(
                    'in_status_play_id' => 6182, /* Idea Removed */
                ), true, $session_en['en_id']);

                //Show confirmation:
                echo ' [Idea + '.$links_removed.' links Removed]';

            }

            //Done showing the idea:
            echo '</div>';
        }

        //Show option to remove all:
        if($command1!='remove_all'){
            echo '<br />';
            echo '<a class="remove-all" href="javascript:void(0);" onclick="$(\'.remove-all\').toggleClass(\'hidden\')">Remove All</a>';
            echo '<div class="remove-all hidden maxout"><b style="color: #FF0000;">WARNING</b>: All ideas and all their links will be removed. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing idea.<br /><br /></div>';
            echo '<a class="remove-all hidden maxout" href="/play/play_admin/orphan_ideas/remove_all" onclick="">Confirm: <b>Remove All</b> &raquo;</a>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No orphans found!</div>';
    }

} elseif($action=='sync_player_links') {

    $scanned = 0;
    $skipped = 0;
    $fixed = 0;
    foreach($this->READ_model->ln_fetch(array(
        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
    ), array(), 0) as $player_link){

        if(filter_var($player_link['ln_content'], FILTER_VALIDATE_URL)){
            //SKIP URLS:
            $skipped++;
            continue;
        }

        $scanned++;
        $detected_ln_type = ln_detect_type($player_link['ln_content']);
        if ($detected_ln_type['status']){
            if(!($detected_ln_type['ln_type_play_id'] == $player_link['ln_type_play_id'])){
                $fixed++;
                $this->READ_model->ln_update($player_link['ln_id'], array(
                    'ln_type_play_id' => $detected_ln_type['ln_type_play_id'],
                ));
            }
        } else {
            echo 'ERROR for Link ID '.$player_link['ln_id'].': '.$detected_ln_type['message'].'<hr />';
        }

    }

    echo $fixed.'/'.$scanned.' Links Fixed & '.$skipped.' Skipped.';

} elseif($action=='orphan_players') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    $orphan_ens = $this->PLAY_model->en_fetch(array(
        ' NOT EXISTS (SELECT 1 FROM table_read WHERE en_id=ln_child_play_id AND ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ') AND ln_status_play_id IN ('.join(',', $this->config->item('en_ids_7360')) /* Link Statuses Active */.')) ' => null,
        'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
    ));

    if(count($orphan_ens) > 0){

        //List orphans:
        foreach ($orphan_ens  as $count => $orphan_en) {

            //Show player:
            echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$orphan_en['en_status_play_id']]['m_name'].': '.$en_all_6177[$orphan_en['en_status_play_id']]['m_desc'].'">' . $en_all_6177[$orphan_en['en_status_play_id']]['m_icon'] . '</span> <a href="/play/'.$orphan_en['en_id'].'"><b>'.$orphan_en['en_name'].'</b></a>';

            //Do we need to remove?
            if($command1=='remove_all'){

                //Remove links:
                $links_removed = $this->PLAY_model->en_unlink($orphan_en['en_id'], $session_en['en_id']);

                //Remove player:
                $this->PLAY_model->en_update($orphan_en['en_id'], array(
                    'en_status_play_id' => 6178, /* Player Removed */
                ), true, $session_en['en_id']);

                //Show confirmation:
                echo ' [Player + '.$links_removed.' links Removed]';

            }

            echo '</div>';

        }

        //Show option to remove all:
        if($command1!='remove_all'){
            echo '<br />';
            echo '<a class="remove-all" href="javascript:void(0);" onclick="$(\'.remove-all\').toggleClass(\'hidden\')">Remove All</a>';
            echo '<div class="remove-all hidden maxout"><b style="color: #FF0000;">WARNING</b>: All players and all their links will be removed. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing player.<br /><br /></div>';
            echo '<a class="remove-all hidden maxout" href="/play/play_admin/orphan_players/remove_all" onclick="">Confirm: <b>Remove All</b> &raquo;</a>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No orphans found!</div>';
    }
    

} elseif($action=='en_icon_search') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    //UI to compose a test message:
    echo '<form method="GET" action="">';

    echo '<div class="mini-header">Search For:</div>';
    echo '<input type="text" class="form-control border maxout" name="search_for" value="'.@$_GET['search_for'].'"><br />';
    echo '<input type="submit" class="btn btn-idea" value="Search">';


    if(isset($_GET['search_for']) && strlen($_GET['search_for'])>0){

        $matching_results = $this->PLAY_model->en_fetch(array(
            'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
            'LOWER(en_icon) LIKE \'%'.strtolower($_GET['search_for']).'%\'' => null,
        ));

        //List the matching search:
        echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.count($matching_results).' Results found</td>';
        echo '</tr>';


        if(count($matching_results) > 0){

            echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
            echo '<td style="text-align: left;">#</td>';
            echo '<td style="text-align: left;">Matching Search</td>';
            echo '</tr>';
            $replaced = 0;

            foreach($matching_results as $count=>$en){

                if(isset($_GET['do_replace']) && isset($_GET['replace_with'])){
                    $replaced += $this->PLAY_model->en_update($en['en_id'], array(
                        'en_icon' => str_ireplace($_GET['search_for'], $_GET['replace_with'], $en['en_icon']),
                    ), false, $session_en['en_id']);

                }

                echo '<tr class="panel-title down-border">';
                echo '<td style="text-align: left;">'.($count+1).'</td>';
                echo '<td style="text-align: left;">'.echo_en_cache('en_all_6177' /* Player Statuses */, $en['en_status_play_id'], true, 'right').' <span class="icon-block">'.echo_en_icon($en['en_icon']).'</span><a href="/play/'.$en['en_id'].'">'.$en['en_name'].'</a></td>';
                echo '</tr>';

            }

            if($replaced > 0){
                echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Updated icons for '.$replaced.' players.</div>';
            }
        }

        echo '</table>';


        echo '<div class="mini-header">Replace With:</div>';
        echo '<input type="text" class="form-control border maxout" name="replace_with" value="'.@$_GET['replace_with'].'"><br />';
        echo '<input type="submit" name="do_replace" class="btn btn-idea" value="Replace">';
    }


    echo '</form>';

} elseif($action=='actionplan_debugger') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    //List this users 🔴 READING LIST ideas so they can choose:
    echo '<div>Choose one of your 🔴 READING LIST ideas to debug:</div><br />';

    $player_reads = $this->READ_model->ln_fetch(array(
        'ln_owner_play_id' => $session_en['en_id'],
        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Idea Set
        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Statuses Public
    ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

    foreach ($player_reads as $priority => $ln) {
        echo '<div>'.($priority+1).') <a href="/read/debug/' . $ln['in_id'] . '">' . echo_in_title($ln['in_title']) . '</a></div>';
    }

} elseif($action=='in_crossovers') {

    $active_ins = $this->IDEA_model->in_fetch(array(
        'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
    ), ( isset($_GET['limit']) ? $_GET['limit'] : 0 ));
    $found = 0;
    foreach($active_ins as $count=>$in){

        $recursive_children = $this->IDEA_model->in_recursive_child_ids($in['in_id'], false);
        if(count($recursive_children) > 0){
            $recursive_parents = $this->IDEA_model->in_fetch_recursive_parents($in['in_id']);
            foreach ($recursive_parents as $grand_parent_ids) {
                $crossovers = array_intersect($recursive_children, $grand_parent_ids);
                if(count($crossovers) > 0){
                    //Ooooopsi, this should not happen:
                    echo $in['in_titile'].' Has Parent/Child crossover for #'.join(' & #', $crossovers).'<hr />';
                    $found++;
                }
            }
        }
    }

    echo 'Found '.$found.' Crossovers.';

} elseif($action=='in_invalid_outcomes') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    $active_ins = $this->IDEA_model->in_fetch(array(
        'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
    ));

    //Give an overview:
    echo '<p>When the validation criteria change within the in_titlevalidate() function, this page lists all the ideas that no longer have a valid outcome.</p>';


    //List the matching search:
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


    echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
    echo '<td style="text-align: left;">#</td>';
    echo '<td style="text-align: left;">Invalid Outcome</td>';
    echo '</tr>';

    $invalid_outcomes = 0;
    foreach($active_ins as $count=>$in){

        $in_titlevalidation = $this->IDEA_model->in_titlevalidate($in['in_title']);

        if(!$in_titlevalidation['status']){

            $invalid_outcomes++;

            //Update idea:
            echo '<tr class="panel-title down-border">';
            echo '<td style="text-align: left;">'.$invalid_outcomes.'</td>';
            echo '<td style="text-align: left;">'.echo_en_cache('en_all_4737' /* Idea Statuses */, $in['in_status_play_id'], true, 'right').' <a href="/idea/'.$in['in_id'].'">'.echo_in_title($in['in_title']).'</a></td>';
            echo '</tr>';

        }

    }
    echo '</table>';

} elseif($action=='in_replace_outcomes') {


    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    //UI to compose a test message:
    echo '<form method="GET" action="">';

    echo '<div class="mini-header">Search For:</div>';
    echo '<input type="text" class="form-control border maxout" name="search_for" value="'.@$_GET['search_for'].'"><br />';


    $search_for_is_set = (isset($_GET['search_for']) && strlen($_GET['search_for'])>0);
    $replace_with_is_set = ((isset($_GET['replace_with']) && strlen($_GET['replace_with'])>0) || (isset($_GET['append_text']) && strlen($_GET['append_text'])>0));
    $qualifying_replacements = 0;
    $completed_replacements = 0;
    $replace_with_is_confirmed = false;

    if($search_for_is_set){

        $matching_results = $this->IDEA_model->in_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
            'LOWER(in_title) LIKE \'%'.strtolower($_GET['search_for']).'%\'' => null,
        ));

        //List the matching search:
        echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


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
                    $append_text = @$_GET['append_text'];
                    $new_outcome = str_replace($_GET['search_for'],$_GET['replace_with'],$in['in_title']).$append_text;
                    $in_titlevalidation = $this->IDEA_model->in_titlevalidate($new_outcome);

                    if($in_titlevalidation['status']){
                        $qualifying_replacements++;
                    }
                }

                if($replace_with_is_confirmed && $in_titlevalidation['status']){
                    //Update idea:
                    $this->IDEA_model->in_update($in['in_id'], array(
                        'in_title' => $in_titlevalidation['in_cleaned_outcome'],
                    ), true, $session_en['en_id']);
                }

                echo '<tr class="panel-title down-border">';
                echo '<td style="text-align: left;">'.($count+1).'</td>';
                echo '<td style="text-align: left;">'.echo_en_cache('en_all_4737' /* Idea Statuses */, $in['in_status_play_id'], true, 'right').' <a href="/idea/'.$in['in_id'].'">'.$in['in_title'].'</a></td>';

                if($replace_with_is_set){

                    echo '<td style="text-align: left;">'.$new_outcome.'</td>';
                    echo '<td style="text-align: left;">'.( !$in_titlevalidation['status'] ? ' <i class="fas fa-exclamation-triangle"></i> Error: '.$in_titlevalidation['message'] : ( $replace_with_is_confirmed && $in_titlevalidation['status'] ? '<i class="fas fa-check-circle"></i> Outcome Updated' : '') ).'</td>';
                } else {
                    //Show parents now:
                    echo '<td style="text-align: left;">';


                    //Loop through parents:
                    $en_all_7585 = $this->config->item('en_all_7585'); // Idea Subtypes
                    foreach ($this->READ_model->ln_fetch(array(
                        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                        'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
                        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
                        'ln_child_idea_id' => $in['in_id'],
                    ), array('in_parent')) as $in_parent) {
                        echo '<span class="in_child_icon_' . $in_parent['in_id'] . '"><a href="/idea/' . $in_parent['in_id'] . '" data-toggle="tooltip" title="' . $in_parent['in_title'] . '" data-placement="bottom">' . $en_all_7585[$in_parent['in_type_play_id']]['m_icon'] . '</a> &nbsp;</span>';
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

        //now give option to replace with:
        echo '<div class="mini-header">Append Text:</div>';
        echo '<input type="text" class="form-control border maxout" name="append_text" value="'.@$_GET['append_text'].'"><br />';
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


    echo '<input type="submit" class="btn btn-idea" value="Go">';
    echo '</form>';


} elseif($action=='identical_idea_outcomes') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    //Do a query to detect Ideas with the exact same title:
    $q = $this->db->query('select in1.* from table_idea in1 where (select count(*) from table_idea in2 where in2.in_title = in1.in_title AND in2.in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')) > 1 AND in1.in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ') ORDER BY in1.in_title ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        foreach ($duplicates as $in) {
            if ($prev_title != $in['in_title']) {
                echo '<hr />';
                $prev_title = $in['in_title'];
            }

            echo '<div><span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$in['in_status_play_id']]['m_name'].': '.$en_all_4737[$in['in_status_play_id']]['m_desc'].'">' . $en_all_4737[$in['in_status_play_id']]['m_icon'] . '</span> <a href="/idea/' . $in['in_id'] . '"><b>' . $in['in_title'] . '</b></a> #' . $in['in_id'] . '</div>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No duplicates found!</div>';
    }

} elseif($action=='identical_player_names') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    $q = $this->db->query('select en1.* from table_play en1 where (select count(*) from table_play en2 where en2.en_name = en1.en_name AND en2.en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')) > 1 AND en1.en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ') ORDER BY en1.en_name ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        foreach ($duplicates as $en) {

            if ($prev_title != $en['en_name']) {
                echo '<hr />';
                $prev_title = $en['en_name'];
            }

            echo '<span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$en['en_status_play_id']]['m_name'].': '.$en_all_6177[$en['en_status_play_id']]['m_desc'].'">' . $en_all_6177[$en['en_status_play_id']]['m_icon'] . '</span> <a href="/play/' . $en['en_id'] . '"><b>' . $en['en_name'] . '</b></a> @' . $en['en_id'] . '<br />';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No duplicates found!</div>';
    }

} elseif($action=='sync_play_idea_statuses') {

    //Sync ALL and echo results:
    echo 'IDAE: '.nl2br(print_r($this->IDEA_model->in_sync_creation($session_en['en_id']), true)).'<hr />';
    echo 'PLAY: '.nl2br(print_r($this->PLAY_model->en_sync_creation($session_en['en_id']), true)).'<hr />';

} elseif($action=='fix_read_coins') {

    exit; //May need to be validated later...]
    $total_updated = 0;
    $total_added = 0;
    $total_rows = $this->READ_model->ln_fetch(array(
        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
    ), array('in_parent'), 0, 0, array( 'ln_id' => 'ASC' ));

    foreach ($total_rows as $ln) {

        //Anything set here would be updated:
        $update_columns = array();

        if($ln['ln_child_idea_id'] > 0 && $ln['ln_type_play_id'] == 6157){ //ONE ANSWER

            //Create separate answer:
            $total_added++;
            $this->READ_model->ln_create(array(
                'ln_type_play_id' => 12336,
                'ln_owner_play_id' => $ln['ln_owner_play_id'],
                'ln_parent_idea_id' => $ln['ln_parent_idea_id'],
                'ln_child_idea_id' => $ln['ln_child_idea_id'],
                'ln_parent_read_id' => $ln['ln_id'],
            ));

            //Move answer away:
            $update_columns['ln_child_idea_id'] = 0;

        }

        if(count($update_columns)){
            $total_updated += $this->READ_model->ln_update($ln['ln_id'], $update_columns);
        }

    }

    echo 'From '.count($total_rows).' total: '.$total_added.' Added & '.$total_updated.' Updated.';

} elseif($action=='or__children') {

    echo '<br /><p>Active <a href="/play/6914">Idea Answer Types</a> are listed below.</p><br />';

    $all_steps = 0;
    $all_children = 0;
    $updated = 0;

    foreach ($this->IDEA_model->in_fetch(array(
        'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
        'in_type_play_id IN (' . join(',', $this->config->item('en_ids_7712')) . ')' => null,
    ), 0, 0, array('in_id' => 'DESC')) as $count => $in) {

        echo '<div>'.($count+1).') '.echo_en_cache('en_all_4737' /* Idea Statuses */, $in['in_status_play_id']).' '.echo_en_cache('en_all_6193' /* OR Ideas */, $in['in_type_play_id']).' <b><a href="https://mench.com/idea/'.$in['in_id'].'">'.echo_in_title($in['in_title']).'</a></b></div>';

        echo '<ul>';
        //Fetch all children for this OR:
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
            'ln_type_play_id' => 4228, //Idea Link Regular Read
            'ln_parent_idea_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $child_or){

            $user_steps = $this->READ_model->ln_fetch(array(
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
                'ln_parent_idea_id' => $child_or['in_id'],
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ), array(), 0);
            $all_steps += count($user_steps);

            $all_children++;
            echo '<li>'.echo_en_cache('en_all_6186' /* Link Statuses */, $child_or['ln_status_play_id']).' '.echo_en_cache('en_all_4737' /* Idea Statuses */, $child_or['in_status_play_id']).' '.echo_en_cache('en_all_7585', $child_or['in_type_play_id']).' <a href="https://mench.com/idea/'.$child_or['in_id'].'" '.( $qualified_update ? '' : 'style="color:#FF0000;"' ).'>'.echo_in_title($child_or['in_title']).'</a>'.( count($user_steps) > 0 ? ' / Steps: '.count($user_steps) : '' ).'</li>';
        }
        echo '</ul>';
        echo '<hr />';
    }

    echo 'All Steps Taken: '.$all_steps.( $updated > 0 ? ' ('.$updated.' updated)' : '' ).' across '.$all_children.' answers';

} elseif($action=='assessment_marks_list_all') {


    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    echo '<p>Below are all the Conditional Step Links:</p>';
    echo '<table class="table table-sm table-striped maxout" style="text-align: left;">';

    $en_all_6103 = $this->config->item('en_all_6103'); //Link Metadata
    $en_all_6186 = $this->config->item('en_all_6186'); //Link Statuses

    echo '<tr style="font-weight: bold;">';
    echo '<td colspan="4" style="text-align: left;">'.$en_all_6103[6402]['m_icon'].' '.$en_all_6103[6402]['m_name'].'</td>';
    echo '</tr>';
    $counter = 0;
    $total_count = 0;
    foreach ($this->READ_model->ln_fetch(array(
        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
        'ln_type_play_id' => 4229, //Idea Link Locked Read
        'LENGTH(ln_metadata) > 0' => null,
    ), array('in_child'), 0, 0) as $in_ln) {
        //Echo HTML format of this message:
        $metadata = unserialize($in_ln['ln_metadata']);
        $mark = echo_in_marks($in_ln);
        if($mark){

            //Fetch parent Idea:
            $parent_ins = $this->IDEA_model->in_fetch(array(
                'in_id' => $in_ln['ln_parent_idea_id'],
            ));

            $counter++;
            echo '<tr>';
            echo '<td style="width: 50px;">'.$counter.'</td>';
            echo '<td style="font-weight: bold; font-size: 1.3em; width: 100px;">'.echo_in_marks($in_ln).'</td>';
            echo '<td>'.$en_all_6186[$in_ln['ln_status_play_id']]['m_icon'].'</td>';
            echo '<td style="text-align: left;">';

            echo '<div>';
            echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$parent_ins[0]['in_status_play_id']]['m_icon'].'</span>';
            echo '<a href="/idea/'.$parent_ins[0]['in_id'].'">'.$parent_ins[0]['in_title'].'</a>';
            echo '</div>';

            echo '<div>';
            echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$in_ln['in_status_play_id']]['m_icon'].'</span>';
            echo '<a href="/idea/'.$in_ln['in_id'].'">'.$in_ln['in_title'].' [child]</a>';
            echo '</div>';

            if(count($this->READ_model->ln_fetch(array(
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
                    'in_type_play_id NOT IN (6907,6914)' => null, //NOT AND/OR Lock
                    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
                    'ln_child_idea_id' => $in_ln['in_id'],
                ), array('in_parent'))) > 1 || $in_ln['in_type_play_id'] != 6677){

                echo '<div>';
                echo 'NOT COOL';
                echo '</div>';

            } else {

                //Update user progression link type:
                $user_steps = $this->READ_model->ln_fetch(array(
                    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
                    'ln_parent_idea_id' => $in_ln['in_id'],
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
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
        echo '<table class="table table-sm table-striped maxout" style="text-align: left;">';

        echo '<tr style="font-weight: bold;">';
        echo '<td colspan="4" style="text-align: left;">Completion Marks</td>';
        echo '</tr>';

        $counter = 0;
        foreach ($this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Statuses Active
            'ln_type_play_id' => 4228, //Idea Link Regular Read
            'LENGTH(ln_metadata) > 0' => null,
        ), array('in_child'), 0, 0) as $in_ln) {
            //Echo HTML format of this message:
            $metadata = unserialize($in_ln['ln_metadata']);
            $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
            if($tr__assessment_points!=0){

                //Fetch parent Idea:
                $parent_ins = $this->IDEA_model->in_fetch(array(
                    'in_id' => $in_ln['ln_parent_idea_id'],
                ));

                $counter++;
                echo '<tr>';
                echo '<td style="width: 50px;">'.$counter.'</td>';
                echo '<td style="font-weight: bold; font-size: 1.3em; width: 100px;">'.echo_in_marks($in_ln).'</td>';
                echo '<td>'.$en_all_6186[$in_ln['ln_status_play_id']]['m_icon'].'</td>';
                echo '<td style="text-align: left;">';
                echo '<div>';
                echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$parent_ins[0]['in_status_play_id']]['m_icon'].'</span>';
                echo '<a href="/idea/'.$parent_ins[0]['in_id'].'">'.$parent_ins[0]['in_title'].'</a>';
                echo '</div>';

                echo '<div>';
                echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$in_ln['in_status_play_id']]['m_icon'].'</span>';
                echo '<a href="/idea/'.$in_ln['in_id'].'">'.$in_ln['in_title'].'</a>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';

            }
        }

        echo '</table>';
    }

} elseif($action=='assessment_marks_birds_eye') {

    //Give an overview of the point links in a hierchial format to enable trainers to overview:
    $_GET['depth_levels']   = ( isset($_GET['depth_levels']) && intval($_GET['depth_levels']) > 0 ? $_GET['depth_levels'] : 3 );

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';


    echo '<form method="GET" action="">';

    echo '<div class="score_range_box">
            <div class="form-group label-floating is-empty"
                 style="max-width:550px; margin:1px 0 10px; display: inline-block;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#000000; font-weight: 300;">Start at #</span>
                    <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="starting_in" id="starting_in" value="'.$_GET['starting_in'].'" class="form-control">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#000000; font-weight: 300; border-left: 1px solid #AAAAAA;"> and go </span>
                    <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="depth_levels" id="depth_levels" value="'.$_GET['depth_levels'].'" class="form-control">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#000000; font-weight: 300; border-left: 1px solid #AAAAAA; border-right:0px solid #FFF;"> levels deep.</span>
                </div>
            </div>
            <input type="submit" class="btn btn-idea" value="Go" style="display: inline-block; margin-top: -41px;" />
        </div>';

    echo '</form>';

    //Load the report via Ajax here on page load:
    echo '<div id="in_report_conditional_steps"></div>';
    echo '<script>

$(document).ready(function () {
//Show spinner:
$(\'#in_report_conditional_steps\').html(\'<span><i class="far fa-yin-yang fa-spin"></i> \' + echo_loading_notify() +  \'</span>\').hide().fadeIn();
//Load report based on input fields:
$.post("/idea/in_report_conditional_steps", {
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

        echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><a href="/play/play_admin/'.$action.'">'.$moderation_tools['/play/play_admin/'.$action].'</a></li><li><b>Review Message</b></li></ul>';

        if(intval($_POST['push_message']) && intval($_POST['recipient_en'])){

            //Send to Facebook Messenger:
            $msg_validation = $this->READ_model->dispatch_message(
                $_POST['test_message'],
                array('en_id' => intval($_POST['recipient_en'])),
                true
            );

        } elseif(intval($_POST['recipient_en']) > 0) {

            $msg_validation = $this->READ_model->dispatch_validate_message($_POST['test_message'], array('en_id' => $_POST['recipient_en']), $_POST['push_message']);

        } else {

            echo 'Missing recipient';

        }

        //Show results:
        print_r($msg_validation);

    } else {

        echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

        //UI to compose a test message:
        echo '<form method="POST" action="" class="maxout">';

        echo '<div class="mini-header">Message:</div>';
        echo '<textarea name="test_message" class="form-control border" style="width:400px; height: 200px;"></textarea><br />';

        echo '<div class="mini-header">Recipient Player ID:</div>';
        echo '<input type="number" class="form-control border" name="recipient_en" value="1"><br />';

        echo '<div class="mini-header">Format Is Messenger:</div>';
        echo '<input type="number" class="form-control border" name="push_message" value="1"><br /><br />';


        echo '<input type="submit" class="btn btn-idea" value="Compose Test Message">';
        echo '</form>';

    }

} else {

    //Oooooopsi, unknown:
    echo '<h1>Unknown Function</h1>';
    echo 'Not sure how you landed here!';

}


echo '<br /><br /><br /><br />';

echo '</div>';

?>