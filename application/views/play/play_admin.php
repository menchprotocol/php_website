<?php

echo '<div class="container">';

//Define all moderation functions:
$en_all_4737 = $this->config->item('en_all_4737'); // Blog Statuses
$en_all_6177 = $this->config->item('en_all_6177'); //Player Statuses

$moderation_tools = array(
    '/play/play_admin/link_coins_words_stats' => 'Read Coin/Word Stats',
    '/play/play_admin/in_replace_outcomes' => 'Blog Search/Replace Titles',
    '/play/play_admin/in_invalid_outcomes' => 'Blog Invalid Titles',
    '/play/play_admin/actionplan_debugger' => 'My 🔴 READING LIST Debugger',
    '/play/play_admin/en_icon_search' => 'Player Icon Search',
    '/play/play_admin/moderate_blog_notes' => 'Moderate Blog Notes',
    '/play/play_admin/identical_blog_outcomes' => 'Identical Blog Titles',
    '/play/play_admin/identical_player_names' => 'Identical Player Names',
    '/play/play_admin/or__children' => 'List OR Blogs + Answers',
    '/play/play_admin/orphan_blogs' => 'Orphan Blogs',
    '/play/play_admin/orphan_players' => 'Orphan Players',
    '/play/play_admin/assessment_marks_list_all' => 'Completion Marks List All',
    '/play/play_admin/assessment_marks_birds_eye' => 'Completion Marks Birds Eye View',
    '/play/play_admin/compose_test_message' => 'Compose Test Message',
    '/play/play_admin/random_user_icon' => 'Random User Icons',
);

$cron_jobs = array(
    '/blog/cron__sync_common_base' => 'Sync Common Base Metadata',
    '/blog/cron__sync_extra_insights' => 'Sync Extra Insights Metadata',
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
    echo '<h1>'.$en_all_11035[6287]['m_icon'].' '.$en_all_11035[6287]['m_name'].' <a href="/play/6287" style="font-size: 0.5em; color: #999;" title="'.$en_all_11035[6287]['m_name'].' player controlling this tool" data-toggle="tooltip" data-placement="right">@6287</a></h1>';

    echo '<div class="list-group maxout">';
    foreach ($moderation_tools as $tool_key => $tool_name) {
        echo '<a href="' . $tool_key . '" class="list-group-item">';
        echo '<span class="pull-right">';
        echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-right"></i></span>';
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


    if(isset($_GET['resetall'])){

        boost_power();
        $this->db->query("UPDATE table_read SET ln_words=0, ln_coins=0;");
        echo '<div class="alert alert-warning">All word/coin counts were set to zero.</div>';

    } elseif(isset($_GET['updateall']) || isset($_GET['updatesome'])){

        //Go through all the links and update their words:
        boost_power();
        $updated = 0;
        foreach($this->READ_model->ln_fetch(( isset($_GET['updateall']) ? array(
            'ln_id >' => 0, //All
        ) : array(
            'ln_type_player_id IN (' . $_GET['updatesome'] . ')' => null,
        )), array(), 0) as $ln){
            $this->READ_model->ln_update($ln['ln_id'], array(
                'ln_words' => ln_type_word_rate($ln),
                'ln_coins' => ln_type_coin_rate($ln),
            ));
            $updated++;
        }
        echo '<div class="alert alert-warning">'.$updated.' links updated with new coin/word counts.</div>';

    }


    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';

    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;">Group</td>';
    echo '<td style="text-align: left;">Links</td>';
    echo '<td style="text-align: left;">%</td>';
    echo '<td style="text-align: left;">Words</td>';
    echo '<td style="text-align: left;">%</td>';
    echo '<td style="text-align: left;">Words/Link</td>';
    echo '</tr>';


    //Count them all:
    $all_stats = $this->READ_model->ln_fetch(array(
        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array(), 0, 0, array(), 'COUNT(ln_id) as total_links, SUM(ABS(ln_words)) as total_words, SUM(ABS(ln_coins)) as total_coins');


    echo '<tr class="panel-title down-border" style="font-weight: bold;">';
    echo '<td style="text-align: left;">Total</td>';
    echo '<td style="text-align: left;">'.number_format($all_stats[0]['total_links'], 0).'</td>';
    echo '<td style="text-align: left;">'.number_format(round($all_stats[0]['total_words']), 0).'</td>';
    echo '<td style="text-align: left;">'.number_format(round($all_stats[0]['total_coins']), 0).'</td>';
    echo '</tr>';

    //Add some empty space:
    echo '<tr class="panel-title down-border"><td style="text-align: left;" colspan="6">&nbsp;</td></tr>';

    //Now do a high level stats:
    foreach (array('ln_coins =', 'ln_coins >', 'ln_coins <') as $words_setting) {

        $words_stats = $this->READ_model->ln_fetch(array(
            $words_setting => 0,
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_links, SUM(ln_words) as total_words, SUM(ln_coins) as total_coins');

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$words_setting.' 0</td>';
        echo '<td style="text-align: left;">'.number_format($words_stats[0]['total_links'], 0).'</td>';
        echo '<td style="text-align: left;">'.number_format(round($words_stats[0]['total_words']), 0).'</td>';
        echo '<td style="text-align: left;">'.number_format(round($words_stats[0]['total_coins']), 0).'</td>';
        echo '</tr>';

    }


    $en_all_10591 = $this->config->item('en_all_10591');

    //Add some empty space:
    echo '<tr class="panel-title down-border"><td style="text-align: left;" colspan="6">&nbsp;</td></tr>';

    //Show each link type:
    foreach ($this->READ_model->ln_fetch(array(
        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
    ), array('en_type'), 0, 0, array('total_coins' => 'DESC'), 'COUNT(ln_id) as total_links, SUM(ln_words) as total_words, SUM(ln_coins) as total_coins, en_name, en_icon, en_id', 'en_id, en_name, en_icon, ln_type_player_id') as $ln) {

        //Determine which weight group this belongs to:
        $coin_rate = filter_cache_group($ln['en_id'], 12140);

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;"><span class="icon-block">'.$ln['en_icon'].'</span> <a href="/play/'.$ln['en_id'].'">'.$ln['en_name'].'</a></td>';
        echo '<td style="text-align: left;">'.number_format($ln['total_links'], 0).'</td>';
        echo '<td style="text-align: left;"><span class="icon-block">'.$en_all_10591[ln_type_direction_en_id($ln)]['m_icon'].'</span>'.number_format(round($ln['total_words']), 0).'</td>';
        echo '<td style="text-align: left;"><span class="icon-block">'.$coin_rate['m_icon'].'</span>'.number_format(round($ln['total_coins']), 0).'</td>';
        echo '</tr>';

    }

    echo '</table>';

} elseif($action=='random_user_icon'){

    //Show breadcrumb:
    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    if(isset($_GET['update_user_icons'])){

        $base_filters = array(
            'ln_parent_player_id' => 1278, //people
            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_player_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
        );

        if(!isset($_GET['force'])) {
            $base_filters['(LENGTH(en_icon) < 1 OR en_icon IS NULL)'] = null;
        }

        $updated = 0;
        foreach($this->READ_model->ln_fetch($base_filters, array('en_child'), 0) as $mench_user){
            $updated += $this->PLAY_model->en_update($mench_user['en_id'], array(
                'en_icon' => random_user_icon(),
            ));
        }
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> '.$updated.' User profiles updated with random animal icons</div>';
    }

    for($i=0;$i<750;$i++){
        if(fmod($i, 30)==0 && $i>1){
            echo '<br />';
        }
        echo '<span class="icon-block">'.random_user_icon().'</span>';
    }

} elseif($action=='moderate_blog_notes'){



    //Fetch pending notes:
    $pendin_in_notes = $this->READ_model->ln_fetch(array(
        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
        'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Blog Notes
    ), array('in_child'), config_var(11064), 0, array('ln_id' => 'ASC'));

    echo '<div class="row">';
    echo '<div class="col-sm-6">';
    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';
    //List blogs and allow to modify and manage blog notes:
    if(count($pendin_in_notes) > 0){
        foreach($pendin_in_notes as $pendin_in_note){
            echo echo_in_read($pendin_in_note);
        }
    } else {
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> No Pending Blog Notes at this time</div>';
    }

    echo '</div>';
    echo '<div class="col-sm-6">';

    //Maybe give option to edit blog here?

    echo '</div>';
    echo '</div>';


} elseif($action=='orphan_blogs') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    $orphan_ins = $this->BLOG_model->in_fetch(array(
        ' NOT EXISTS (SELECT 1 FROM table_read WHERE in_id=ln_child_blog_id AND ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4486')) . ') AND ln_status_player_id IN ('.join(',', $this->config->item('en_ids_7360')) /* Link Statuses Active */.')) ' => null,
        'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
    ));

    if(count($orphan_ins) > 0){

        //List orphans:
        foreach ($orphan_ins as $count => $orphan_in) {

            //Show blog:
            echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$orphan_in['in_status_player_id']]['m_name'].': '.$en_all_4737[$orphan_in['in_status_player_id']]['m_desc'].'">' . $en_all_4737[$orphan_in['in_status_player_id']]['m_icon'] . '</span> <a href="/blog/'.$orphan_in['in_id'].'"><b>'.$orphan_in['in_title'].'</b></a>';

            //Do we need to remove?
            if($command1=='remove_all'){

                //Remove blog links:
                $links_removed = $this->BLOG_model->in_unlink($orphan_in['in_id'] , $session_en['en_id']);

                //Remove blog:
                $this->BLOG_model->in_update($orphan_in['in_id'], array(
                    'in_status_player_id' => 6182, /* Blog Removed */
                ), true, $session_en['en_id']);

                //Show confirmation:
                echo ' [Blog + '.$links_removed.' links Removed]';

            }

            //Done showing the blog:
            echo '</div>';
        }

        //Show option to remove all:
        if($command1!='remove_all'){
            echo '<br />';
            echo '<a class="remove-all" href="javascript:void(0);" onclick="$(\'.remove-all\').toggleClass(\'hidden\')">Remove All</a>';
            echo '<div class="remove-all hidden maxout"><b style="color: #FF0000;">WARNING</b>: All blogs and all their links will be removed. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing blog.<br /><br /></div>';
            echo '<a class="remove-all hidden maxout" href="/play/play_admin/orphan_blogs/remove_all" onclick="">Confirm: <b>Remove All</b> &raquo;</a>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No orphans found!</div>';
    }

} elseif($action=='orphan_players') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    $orphan_ens = $this->PLAY_model->en_fetch(array(
        ' NOT EXISTS (SELECT 1 FROM table_read WHERE en_id=ln_child_player_id AND ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4592')) . ') AND ln_status_player_id IN ('.join(',', $this->config->item('en_ids_7360')) /* Link Statuses Active */.')) ' => null,
        'en_status_player_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
    ));

    if(count($orphan_ens) > 0){

        //List orphans:
        foreach ($orphan_ens  as $count => $orphan_en) {

            //Show player:
            echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$orphan_en['en_status_player_id']]['m_name'].': '.$en_all_6177[$orphan_en['en_status_player_id']]['m_desc'].'">' . $en_all_6177[$orphan_en['en_status_player_id']]['m_icon'] . '</span> <a href="/play/'.$orphan_en['en_id'].'"><b>'.$orphan_en['en_name'].'</b></a>';

            //Do we need to remove?
            if($command1=='remove_all'){

                //Remove links:
                $links_removed = $this->PLAY_model->en_unlink($orphan_en['en_id'], $session_en['en_id']);

                //Remove player:
                $this->PLAY_model->en_update($orphan_en['en_id'], array(
                    'en_status_player_id' => 6178, /* Player Removed */
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
    echo '<input type="submit" class="btn btn-blog" value="Search">';


    if(isset($_GET['search_for']) && strlen($_GET['search_for'])>0){

        $matching_results = $this->PLAY_model->en_fetch(array(
            'en_status_player_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
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
                echo '<td style="text-align: left;">'.echo_en_cache('en_all_6177' /* Player Statuses */, $en['en_status_player_id'], true, 'right').' <span class="icon-block">'.echo_en_icon($en['en_icon']).'</span><a href="/play/'.$en['en_id'].'">'.$en['en_name'].'</a></td>';
                echo '</tr>';

            }

            if($replaced > 0){
                echo '<div class="alert alert-success"><i class="fas fa-exclamation"></i> Updated icons for '.$replaced.' players.</div>';
            }
        }

        echo '</table>';


        echo '<div class="mini-header">Replace With:</div>';
        echo '<input type="text" class="form-control border maxout" name="replace_with" value="'.@$_GET['replace_with'].'"><br />';
        echo '<input type="submit" name="do_replace" class="btn btn-blog" value="Replace">';
    }


    echo '</form>';

} elseif($action=='actionplan_debugger') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    //List this users 🔴 READING LIST blogs so they can choose:
    echo '<div>Choose one of your 🔴 READING LIST blogs to debug:</div><br />';

    $user_blogs = $this->READ_model->ln_fetch(array(
        'ln_creator_player_id' => $session_en['en_id'],
        'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
    ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

    foreach ($user_blogs as $priority => $ln) {
        echo '<div>'.($priority+1).') <a href="/read/debug/' . $ln['in_id'] . '">' . echo_in_title($ln['in_title']) . '</a></div>';
    }

} elseif($action=='in_invalid_outcomes') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    $active_ins = $this->BLOG_model->in_fetch(array(
        'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
    ));

    //Give an overview:
    echo '<p>When the validation criteria change within the in_title_validate() function, this page lists all the blogs that no longer have a valid outcome.</p>';


    //List the matching search:
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


    echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
    echo '<td style="text-align: left;">#</td>';
    echo '<td style="text-align: left;">Invalid Outcome</td>';
    echo '</tr>';

    $invalid_outcomes = 0;
    foreach($active_ins as $count=>$in){

        $in_title_validation = $this->BLOG_model->in_title_validate($in['in_title']);

        if(!$in_title_validation['status']){

            $invalid_outcomes++;

            //Update blog:
            echo '<tr class="panel-title down-border">';
            echo '<td style="text-align: left;">'.$invalid_outcomes.'</td>';
            echo '<td style="text-align: left;">'.echo_en_cache('en_all_4737' /* Blog Statuses */, $in['in_status_player_id'], true, 'right').' <a href="/blog/'.$in['in_id'].'">'.echo_in_title($in['in_title']).'</a></td>';
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

        $matching_results = $this->BLOG_model->in_fetch(array(
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
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
                    $in_title_validation = $this->BLOG_model->in_title_validate($new_outcome);

                    if($in_title_validation['status']){
                        $qualifying_replacements++;
                    }
                }

                if($replace_with_is_confirmed && $in_title_validation['status']){
                    //Update blog:
                    $this->BLOG_model->in_update($in['in_id'], array(
                        'in_title' => $in_title_validation['in_cleaned_outcome'],
                    ), true, $session_en['en_id']);
                }

                echo '<tr class="panel-title down-border">';
                echo '<td style="text-align: left;">'.($count+1).'</td>';
                echo '<td style="text-align: left;">'.echo_en_cache('en_all_4737' /* Blog Statuses */, $in['in_status_player_id'], true, 'right').' <a href="/blog/'.$in['in_id'].'">'.$in['in_title'].'</a></td>';

                if($replace_with_is_set){

                    echo '<td style="text-align: left;">'.$new_outcome.'</td>';
                    echo '<td style="text-align: left;">'.( !$in_title_validation['status'] ? ' <i class="fas fa-exclamation-triangle"></i> Error: '.$in_title_validation['message'] : ( $replace_with_is_confirmed && $in_title_validation['status'] ? '<i class="fas fa-check-circle"></i> Outcome Updated' : '') ).'</td>';
                } else {
                    //Show parents now:
                    echo '<td style="text-align: left;">';


                    //Loop through parents:
                    $en_all_7585 = $this->config->item('en_all_7585'); // Blog Subtypes
                    foreach ($this->READ_model->ln_fetch(array(
                        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                        'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
                        'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                        'ln_child_blog_id' => $in['in_id'],
                    ), array('in_parent')) as $in_parent) {
                        echo '<span class="in_child_icon_' . $in_parent['in_id'] . '"><a href="/blog/' . $in_parent['in_id'] . '" data-toggle="tooltip" title="' . $in_parent['in_title'] . '" data-placement="bottom">' . $en_all_7585[$in_parent['in_type_player_id']]['m_icon'] . '</a> &nbsp;</span>';
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


    echo '<input type="submit" class="btn btn-blog" value="Go">';
    echo '</form>';


} elseif($action=='identical_blog_outcomes') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    //Do a query to detect blogs with the exact same title:
    $q = $this->db->query('select in1.* from table_blog in1 where (select count(*) from table_blog in2 where in2.in_title = in1.in_title AND in2.in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')) > 1 AND in1.in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ') ORDER BY in1.in_title ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        foreach ($duplicates as $in) {
            if ($prev_title != $in['in_title']) {
                echo '<hr />';
                $prev_title = $in['in_title'];
            }

            echo '<div><span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$in['in_status_player_id']]['m_name'].': '.$en_all_4737[$in['in_status_player_id']]['m_desc'].'">' . $en_all_4737[$in['in_status_player_id']]['m_icon'] . '</span> <a href="/blog/' . $in['in_id'] . '"><b>' . $in['in_title'] . '</b></a> #' . $in['in_id'] . '</div>';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No duplicates found!</div>';
    }

} elseif($action=='identical_player_names') {

    echo '<ul class="breadcrumb"><li><a href="/play/play_admin">Trainer Tools</a></li><li><b>'.$moderation_tools['/play/play_admin/'.$action].'</b></li></ul>';

    $q = $this->db->query('select en1.* from table_play en1 where (select count(*) from table_play en2 where en2.en_name = en1.en_name AND en2.en_status_player_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')) > 1 AND en1.en_status_player_id IN (' . join(',', $this->config->item('en_ids_7358')) . ') ORDER BY en1.en_name ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        foreach ($duplicates as $en) {

            if ($prev_title != $en['en_name']) {
                echo '<hr />';
                $prev_title = $en['en_name'];
            }

            echo '<span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$en['en_status_player_id']]['m_name'].': '.$en_all_6177[$en['en_status_player_id']]['m_desc'].'">' . $en_all_6177[$en['en_status_player_id']]['m_icon'] . '</span> <a href="/play/' . $en['en_id'] . '"><b>' . $en['en_name'] . '</b></a> @' . $en['en_id'] . '<br />';
        }

    } else {
        echo '<div class="alert alert-success maxout"><i class="fas fa-check-circle"></i> No duplicates found!</div>';
    }

} elseif($action=='or__children') {

    echo '<br /><p>Active <a href="/play/6914">Blog Answer Types</a> are listed below.</p><br />';

    $all_steps = 0;
    $all_children = 0;
    $updated = 0;
    $new_ln_type_player_id = 7485; //User Read Answer Unlock

    foreach ($this->BLOG_model->in_fetch(array(
        'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
        'in_type_player_id IN (' . join(',', $this->config->item('en_ids_7712')) . ')' => null,
    ), 0, 0, array('in_id' => 'DESC')) as $count => $in) {

        echo '<div>'.($count+1).') '.echo_en_cache('en_all_4737' /* Blog Statuses */, $in['in_status_player_id']).' '.echo_en_cache('en_all_6193' /* OR Blogs */, $in['in_type_player_id']).' <b><a href="https://mench.com/blog/'.$in['in_id'].'">'.echo_in_title($in['in_title']).'</a></b></div>';

        echo '<ul>';
        //Fetch all children for this OR:
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            'ln_type_player_id' => 4228, //Blog Link Regular Read
            'ln_parent_blog_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $child_or){

            $user_steps = $this->READ_model->ln_fetch(array(
                'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ PROGRESS
                'ln_parent_blog_id' => $child_or['in_id'],
                'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ), array(), 0);
            $all_steps += count($user_steps);

            $all_children++;
            echo '<li>'.echo_en_cache('en_all_6186' /* Link Statuses */, $child_or['ln_status_player_id']).' '.echo_en_cache('en_all_4737' /* Blog Statuses */, $child_or['in_status_player_id']).' '.echo_en_cache('en_all_7585', $child_or['in_type_player_id']).' <a href="https://mench.com/blog/'.$child_or['in_id'].'" '.( $qualified_update ? '' : 'style="color:#FF0000;"' ).'>'.echo_in_title($child_or['in_title']).'</a>'.( count($user_steps) > 0 ? ' / Steps: '.count($user_steps) : '' ).'</li>';
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
        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
        'ln_type_player_id' => 4229, //Blog Link Locked Read
        'LENGTH(ln_metadata) > 0' => null,
    ), array('in_child'), 0, 0) as $in_ln) {
        //Echo HTML format of this message:
        $metadata = unserialize($in_ln['ln_metadata']);
        $mark = echo_in_marks($in_ln);
        if($mark){

            //Fetch parent blog:
            $parent_ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $in_ln['ln_parent_blog_id'],
            ));

            $counter++;
            echo '<tr>';
            echo '<td style="width: 50px;">'.$counter.'</td>';
            echo '<td style="font-weight: bold; font-size: 1.3em; width: 100px;">'.echo_in_marks($in_ln).'</td>';
            echo '<td>'.$en_all_6186[$in_ln['ln_status_player_id']]['m_icon'].'</td>';
            echo '<td style="text-align: left;">';

            echo '<div>';
            echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$parent_ins[0]['in_status_player_id']]['m_icon'].'</span>';
            echo '<a href="/blog/'.$parent_ins[0]['in_id'].'">'.$parent_ins[0]['in_title'].'</a>';
            echo '</div>';

            echo '<div>';
            echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$in_ln['in_status_player_id']]['m_icon'].'</span>';
            echo '<a href="/blog/'.$in_ln['in_id'].'">'.$in_ln['in_title'].' [child]</a>';
            echo '</div>';

            if(count($this->READ_model->ln_fetch(array(
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
                    'in_type_player_id NOT IN (6907,6914)' => null, //NOT AND/OR Lock
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                    'ln_child_blog_id' => $in_ln['in_id'],
                ), array('in_parent'))) > 1 || $in_ln['in_type_player_id'] != 6677){

                echo '<div>';
                echo 'NOT COOL';
                echo '</div>';

            } else {

                //Update user progression link type:
                $user_steps = $this->READ_model->ln_fetch(array(
                    'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ PROGRESS
                    'ln_parent_blog_id' => $in_ln['in_id'],
                    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
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
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            'ln_type_player_id' => 4228, //Blog Link Regular Read
            'LENGTH(ln_metadata) > 0' => null,
        ), array('in_child'), 0, 0) as $in_ln) {
            //Echo HTML format of this message:
            $metadata = unserialize($in_ln['ln_metadata']);
            $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
            if($tr__assessment_points!=0){

                //Fetch parent blog:
                $parent_ins = $this->BLOG_model->in_fetch(array(
                    'in_id' => $in_ln['ln_parent_blog_id'],
                ));

                $counter++;
                echo '<tr>';
                echo '<td style="width: 50px;">'.$counter.'</td>';
                echo '<td style="font-weight: bold; font-size: 1.3em; width: 100px;">'.echo_in_marks($in_ln).'</td>';
                echo '<td>'.$en_all_6186[$in_ln['ln_status_player_id']]['m_icon'].'</td>';
                echo '<td style="text-align: left;">';
                echo '<div>';
                echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$parent_ins[0]['in_status_player_id']]['m_icon'].'</span>';
                echo '<a href="/blog/'.$parent_ins[0]['in_id'].'">'.$parent_ins[0]['in_title'].'</a>';
                echo '</div>';

                echo '<div>';
                echo '<span style="width:25px; display:inline-block; text-align:center;">'.$en_all_4737[$in_ln['in_status_player_id']]['m_icon'].'</span>';
                echo '<a href="/blog/'.$in_ln['in_id'].'">'.$in_ln['in_title'].'</a>';
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
                    <span class="input-group-addon addon-lean addon-grey" style="color:#070707; font-weight: 300;">Start at #</span>
                    <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="starting_in" id="starting_in" value="'.$_GET['starting_in'].'" class="form-control">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#070707; font-weight: 300; border-left: 1px solid #ccc;"> and go </span>
                    <input style="padding-left:3px; min-width:56px;" type="number" min="1" step="1" name="depth_levels" id="depth_levels" value="'.$_GET['depth_levels'].'" class="form-control">
                    <span class="input-group-addon addon-lean addon-grey" style="color:#070707; font-weight: 300; border-left: 1px solid #ccc; border-right:0px solid #FFF;"> levels deep.</span>
                </div>
            </div>
            <input type="submit" class="btn btn-blog" value="Go" style="display: inline-block; margin-top: -41px;" />
        </div>';

    echo '</form>';

    //Load the report via Ajax here on page load:
    echo '<div id="in_report_conditional_steps"></div>';
    echo '<script>

$(document).ready(function () {
//Show spinner:
$(\'#in_report_conditional_steps\').html(\'<span><i class="far fa-yin-yang fa-spin"></i> \' + echo_loading_notify() +  \'</span>\').hide().fadeIn();
//Load report based on input fields:
$.post("/blog/in_report_conditional_steps", {
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


        echo '<input type="submit" class="btn btn-blog" value="Compose Test Message">';
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