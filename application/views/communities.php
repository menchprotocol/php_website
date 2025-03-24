<?php

$community_pills = '';

foreach(( isset($_GET['e__handle']) && strlen($_GET['e__handle']) ? $this->Source_cache->fetch(array('LOWER(e__handle)' => strtolower($_GET['e__handle']))) : $this->Source_cache->scissor_e(website_setting(0), 13207) ) as $e_item) {

    foreach($this->Mench_ledger->fetch(array(
        'link_up' => $e_item['e__id'],
        'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'link_void' => 0, //Not Void
    ), array('link_down'), 0, 0, array('link_number' => 'ASC', 'link_id' => 'DESC')) as $x) {

        $total_count = view__e_covers(12274, $x['e__id'], 0, false);

        if($total_count){

            $ui = '<div class="row justify-content">';
            foreach(view__e_covers(12274, $x['e__id'], 1, false) as $count=>$e) {
                $ui .= view__card_e(13207, $e, null);
            }
            $ui .= '</div>';

            $community_pills .= view__pill(12274, $x['e__id'], $total_count, array(
                'm__cover' => view__cover($x['e__cover'], true),
                'm__title' => $x['e__title'],
                'm__message' => $x['link_text'],
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

