<?php

$community_pills = '';

foreach(( isset($_GET['e__handle']) && strlen($_GET['e__handle']) ? $this->Sources->read(array('LOWER(e__handle)' => strtolower($_GET['e__handle']))) : $this->Sources->scissor_e(website_setting(0), 13207) ) as $e_item) {

    foreach($this->Ledger->read(array(
        'x__following' => $e_item['e__id'],
        'x__type IN (' . njoin(32292) . ')' => null, //SOURCE LINKS
        ), array('x__follower'), 0, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC')) as $x) {

        $total_count = view_e_covers(12274, $x['e__id'], 0, false);

        if($total_count){

            $ui = '<div class="row justify-content">';
            foreach(view_e_covers(12274, $x['e__id'], 1, false) as $count=>$e) {
                $ui .= view_card_e(13207, $e, null);
            }
            $ui .= '</div>';

            $community_pills .= view_pill(12274, $x['e__id'], $total_count, array(
                'm__cover' => view_cover($x['e__cover'], true),
                'm__title' => $x['e__title'],
                'm__message' => $x['x__message'],
                'm__handle' => $x['e__handle'],
            ), $ui);

        }
    }
}


if(strlen($community_pills)){

    //Community
    echo '<h2 class="center">'.$e_item['e__title'].'</h2>';
    echo '<ul class="nav nav-tabs nav12274"></ul>';
    echo $community_pills;

} else {

    echo 'Community settings not yet setup for your website';

}

