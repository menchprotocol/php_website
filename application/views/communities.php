<?php

$community_pills = '';

foreach ((isset($_GET['sourcehandle']) && strlen($_GET['sourcehandle']) ? $this->Sources->read(array('LOWER(sourcehandle)' => strtolower($_GET['sourcehandle']))) : $this->Sources->scissor(website_setting(0), 13207)) as $source_item) {

    foreach ($this->Chains->read(array(
        'chainsourceup' => $source_item['sourceid'],
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
    ), array('chainsourcedown'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC')) as $x) {

        $total_count = sources_query(42373, $x['sourceid'], 0, false);

        if ($total_count) {

            $ui = '<div class="row justify-content">';
            foreach (sources_query(42373, $x['sourceid'], 1, false) as $count => $e) {
                $ui .= source_view(13207, $e, null);
            }
            $ui .= '</div>';

            $community_pills .= view_pill(12274, $x['sourceid'], $total_count, array(
                'm__cover' => view_cover($x['sourcecover'], true),
                'm__title' => $x['sourcetext'],
                'm__message' => $x['chainvalue'],
                'm__handle' => $x['sourcehandle'],
            ), $ui);

        }
    }
}


if (strlen($community_pills)) {

    //Community
    echo '<h2 class="center">' . $source_item['sourcetext'] . '</h2>';
    echo '<ul class="nav nav-tabs nav12274"></ul>';
    echo $community_pills;

} else {

    echo 'Community settings not yet setup for your website';

}

