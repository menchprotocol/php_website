<?php


//Various Ledger cleanup functions
echo @$_GET['playerhandle'];


if(isset($_GET['action']) && $_GET['action']=='idea_messages'){

    //Sync Ideas & Players
    $stats = array(
        'cached_ideas' => 0,
        'active_ideas' => 0,
        'old_links_removed' => 0,
        'old_links_kept' => 0,
        'new_links_added' => 0,
        'missing_creation' => 0,
    );

    $edited = 0;
    $edited_players = 0;
    foreach($this->Cacheideas->fetch(array(
    ), 0) as $idea_fix){

        $view_sync_links = view__sync_links($idea_fix['ideatext'], true, $idea_fix['ideaid']);

        /*
        echo '<a href="'.view__memory(42903,33286).$idea_fix['ideahashtag'].'">#'.$idea_fix['ideahashtag'].'</a><br />';
        echo nl2br(htmlentities($idea_fix['ideatext'])).'<br />';

        if(count($view_sync_links['replace_from'])){

            //Show all:
            $starting_message = $idea_fix['ideatext'];

            foreach($view_sync_links['replace_from'] as $index=>$val){
                $edited_players++;
                if(substr_count($starting_message, $view_sync_links['replace_from'][$index].' ')){
                    $starting_message = str_replace($view_sync_links['replace_from'][$index].' ',$view_sync_links['replace_to'][$index].' ',$starting_message);
                    echo '<div>['.$view_sync_links['replace_from'][$index].' ] Replaced to ['.$view_sync_links['replace_to'][$index].' ]</div>';
                } else {
                    $starting_message = str_replace($view_sync_links['replace_from'][$index],$view_sync_links['replace_to'][$index],$starting_message);
                    echo '<div>['.$view_sync_links['replace_from'][$index].'] Replaced to ['.$view_sync_links['replace_to'][$index].']</div>';
                }
            }

            if($starting_message!=$idea_fix['ideatext']){
                //view__sync_links($starting_message, true, $idea_fix['ideaid']);
                $edited++;
            }

        }
        $stats['old_links_removed'] += $view_sync_links['sync_stats']['old_links_removed'];
        $stats['old_links_kept'] += $view_sync_links['sync_stats']['old_links_kept'];
        $stats['new_links_added'] += $view_sync_links['sync_stats']['new_links_added'];

        */
    }

    echo '<hr />Edited ['.$edited.']['.$edited_players.']<br />';


} elseif(isset($_GET['action']) && $_GET['action']=='import_discovery') {


    //Import Discoveries?
    $flash_message = '';
    if(isset($_GET['playerhandle'])){
        foreach($this->Cacheplayers->fetch(array(
            'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
        )) as $player_append){
            $completed = 0;
            foreach($this->Menchledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'linkleft' => $is[0]['ideaid'],
            ), array(), 0) as $x){
                if(!count($this->Menchledger->fetch(array(
                    'linkup' => $player_append['playerid'],
                    'linkdown' => $x['linkplayer'],
                    'linktext' => $x['linktext'],
                    'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
                    )))){
                    //Increment Player link:
                    $completed++;
                    $this->Menchledger->create(array(
                        'linkplayer' => ($player_e ? $player_e['playerid'] : $x['linkplayer']),
                        'linkup' => $player_append['playerid'],
                        'linkdown' => $x['linkplayer'],
                        'linktext' => $x['linktext'],
                        'linktype' => 4230,
                    ));
                }
            }

            $flash_message = '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span> '.$completed.' Players who played this idea added to @'.$player_append['playerhandle'].'</div>';
        }
    }

} else {

    //SHow list of actions:
    echo 'Enter GET action value to process...';

}