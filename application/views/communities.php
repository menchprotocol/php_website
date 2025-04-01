<?php

$community_pills = '';

foreach(( isset($_GET['playerhandle']) && strlen($_GET['playerhandle']) ? $this->Source_cache->fetch(array('LOWER(playerhandle)' => strtolower($_GET['playerhandle']))) : $this->Source_cache->scissor_e(website_setting(0), 13207) ) as $e_item) {

    foreach($this->Mench_ledger->fetch(array(
        'linkup' => $e_item['playerid'],
        'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'linkvoid' => 0, //Not Void
    ), array('linkdown'), 0, 0, array('linknumber' => 'ASC', 'linkid' => 'DESC')) as $x) {

        $total_count = view__e_covers(12274, $x['playerid'], 0, false);

        if($total_count){

            $ui = '<div class="row justify-content">';
            foreach(view__e_covers(12274, $x['playerid'], 1, false) as $count=>$e) {
                $ui .= view__card_e(13207, $e, null);
            }
            $ui .= '</div>';

            $community_pills .= view__pill(12274, $x['playerid'], $total_count, array(
                'm__cover' => view__cover($x['playercover'], true),
                'm__title' => $x['playertext'],
                'm__message' => $x['linktext'],
                'm__handle' => $x['playerhandle'],
            ), $ui);

        }
    }
}


if(strlen($community_pills)){

    //Community
    echo '<h2 class="center">'.$e_item['playertext'].'</h2>';
    echo '<ul class="nav nav-tabs nav12274"></ul>';
    echo $community_pills;

} else {

    echo 'Community settings not yet setup for your website';

}

