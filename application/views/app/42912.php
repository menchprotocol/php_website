<?php


//Various Ledger cleanup functions


if(isset($_GET['action']) && $_GET['action']==42913){

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
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
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

} else {

    //SHow list of actions:
    foreach($this->config->item('e___4593') /* DISCOVERY Types */ as $e__id => $m){
        //Echo drop down:
        echo '<div><a href="'.view_app_link(4269).'?action='.$e__id.'"><span class="icon-block">'.$m['m__cover']. '</span><u>'.$m['m__title']. '</a></div>';
    }


}
