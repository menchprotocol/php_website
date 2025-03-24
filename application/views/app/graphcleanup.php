<?php


//Various Ledger cleanup functions
echo @$_GET['e__handle'];


if(isset($_GET['action']) && $_GET['action']=='i_messages'){

    //Sync Ideas & Sources
    $stats = array(
        'cached_ideas' => 0,
        'active_ideas' => 0,
        'old_links_removed' => 0,
        'old_links_kept' => 0,
        'new_links_added' => 0,
        'missing_creation' => 0,
    );

    $edited = 0;
    $edited_sources = 0;
    foreach($this->Idea_cache->fetch(array(
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ), 0) as $i_fix){

        $view_sync_links = view__sync_links($i_fix['i__message'], true, $i_fix['i__id']);

        /*
        echo '<a href="'.view__memory(42903,33286).$i_fix['i__hashtag'].'">#'.$i_fix['i__hashtag'].'</a><br />';
        echo nl2br(htmlentities($i_fix['i__message'])).'<br />';

        if(count($view_sync_links['replace_from'])){

            //Show all:
            $starting_message = $i_fix['i__message'];

            foreach($view_sync_links['replace_from'] as $index=>$val){
                $edited_sources++;
                if(substr_count($starting_message, $view_sync_links['replace_from'][$index].' ')){
                    $starting_message = str_replace($view_sync_links['replace_from'][$index].' ',$view_sync_links['replace_to'][$index].' ',$starting_message);
                    echo '<div>['.$view_sync_links['replace_from'][$index].' ] Replaced to ['.$view_sync_links['replace_to'][$index].' ]</div>';
                } else {
                    $starting_message = str_replace($view_sync_links['replace_from'][$index],$view_sync_links['replace_to'][$index],$starting_message);
                    echo '<div>['.$view_sync_links['replace_from'][$index].'] Replaced to ['.$view_sync_links['replace_to'][$index].']</div>';
                }
            }

            if($starting_message!=$i_fix['i__message']){
                //view__sync_links($starting_message, true, $i_fix['i__id']);
                $edited++;
            }

        }
        $stats['old_links_removed'] += $view_sync_links['sync_stats']['old_links_removed'];
        $stats['old_links_kept'] += $view_sync_links['sync_stats']['old_links_kept'];
        $stats['new_links_added'] += $view_sync_links['sync_stats']['new_links_added'];

        */
    }

    echo '<hr />Edited ['.$edited.']['.$edited_sources.']<br />';


} elseif(isset($_GET['action']) && $_GET['action']=='import_discovery') {


    //Import Discoveries?
    $flash_message = '';
    if(isset($_GET['e__handle'])){
        foreach($this->Source_cache->fetch(array(
            'LOWER(e__handle)' => strtolower($_GET['e__handle']),
        )) as $e_append){
            $completed = 0;
            foreach($this->Mench_ledger->fetch(array(
                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'link_left' => $is[0]['i__id'],
            ), array(), 0) as $x){
                if(!count($this->Mench_ledger->fetch(array(
                    'link_up' => $e_append['e__id'],
                    'link_down' => $x['link_player'],
                    'link_text' => $x['link_text'],
                    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                    //Increment source link:
                    $completed++;
                    $this->Mench_ledger->create(array(
                        'link_player' => ($player_e ? $player_e['e__id'] : $x['link_player']),
                        'link_up' => $e_append['e__id'],
                        'link_down' => $x['link_player'],
                        'link_text' => $x['link_text'],
                        'link_type' => 4251,
                    ));
                }
            }

            $flash_message = '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span> '.$completed.' sources who played this idea added to @'.$e_append['e__handle'].'</div>';
        }
    }

} elseif(isset($_GET['action']) && $_GET['action']=='link_update') {

    $count = 0;
    foreach($this->Mench_ledger->fetch(array(
        //'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'link_type' => 42243,
    ), array('link_left'), 0) as $prev_i){
        if($prev_i['i__privacy']!=42626 || $prev_i['link_type']!=4228){
            $count++;
            $this->Mench_ledger->update($prev_i['link_id'], array(
                'link_type' => 4228,
            ));
            $this->Idea_cache->update($prev_i['i__id'], array(
                'i__privacy' => 42626,
            ));
        }
        echo '<div>'.view__i_title($prev_i).' @'.$prev_i['i__privacy'].'</div>';
    }
    echo $count;

} else {

    //SHow list of actions:
    echo 'Enter GET action value to process...';

}