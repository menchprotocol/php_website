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
    foreach($this->I_model->fetch(array(
        ), 0) as $i_fix){

        $view_sync_links = view_sync_links($i_fix['i__message'], true, $i_fix['i__id']);

        /*
        echo '<a href="'.view_memory(42903,33286).$i_fix['i__hashtag'].'">#'.$i_fix['i__hashtag'].'</a><br />';
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
                //view_sync_links($starting_message, true, $i_fix['i__id']);
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
        foreach($this->E_model->fetch(array(
            'LOWER(e__handle)' => strtolower($_GET['e__handle']),
        )) as $e_append){
            $completed = 0;
            foreach($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__previous' => $is[0]['i__id'],
            ), array(), 0) as $x){
                if(!count($this->X_model->fetch(array(
                    'x__following' => $e_append['e__id'],
                    'x__follower' => $x['x__player'],
                    'x__message' => $x['x__message'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        )))){
                    //Increment source link:
                    $completed++;
                    $this->X_model->create(array(
                        'x__player' => ($player_e ? $player_e['e__id'] : $x['x__player']),
                        'x__following' => $e_append['e__id'],
                        'x__follower' => $x['x__player'],
                        'x__message' => $x['x__message'],
                        'x__type' => 4251,
                    ));
                }
            }

            $flash_message = '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span> '.$completed.' sources who played this idea added to @'.$e_append['e__handle'].'</div>';
        }
    }

} elseif(isset($_GET['action']) && $_GET['action']=='link_update') {

    $count = 0;
    foreach($this->X_model->fetch(array(
            'x__type' => 42243,
    ), array('x__previous'), 0) as $prev_i){
        if($prev_i['i__privacy']!=42626 || $prev_i['x__type']!=4228){
            $count++;
            $this->X_model->update($prev_i['x__id'], array(
                'x__type' => 4228,
            ));
            $this->I_model->update($prev_i['i__id'], array(
                'i__privacy' => 42626,
            ));
        }
        echo '<div>'.view_i_title($prev_i).' @'.$prev_i['i__privacy'].'</div>';
    }
    echo $count;

} else {

    //SHow list of actions:
    echo 'Enter GET action value to process...';

}