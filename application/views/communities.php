<?php

$community_pills = '';

foreach ((isset($_GET['playerhandle']) && strlen($_GET['playerhandle']) ? $this->Players->read(array('LOWER(playerhandle)' => strtolower($_GET['playerhandle']))) : $this->Players->scissor(website_setting(0), 13207)) as $player_item) {

    foreach ($this->Links->read(array(
        'linkplayerup' => $player_item['playerid'],
        'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
    ), array('linkplayerdown'), 0, 0, array('linknumber' => 'ASC', 'linkid' => 'DESC')) as $x) {

        $total_count = players_query(42373, $x['playerid'], 0, false);

        if ($total_count) {

            $ui = '<div class="row justify-content">';
            foreach (players_query(42373, $x['playerid'], 1, false) as $count => $e) {
                $ui .= player_view(13207, $e, null);
            }
            $ui .= '</div>';

            $community_pills .= view_pill(12274, $x['playerid'], $total_count, array(
                'm__cover' => view_cover($x['playercover'], true),
                'm__title' => $x['playertext'],
                'm__message' => $x['linktext'],
                'm__handle' => $x['playerhandle'],
            ), $ui);

        }
    }
}


if (strlen($community_pills)) {

    //Community
    echo '<h2 class="center">' . $player_item['playertext'] . '</h2>';
    echo '<ul class="nav nav-tabs nav12274"></ul>';
    echo $community_pills;

} else {

    echo 'Community settings not yet setup for your website';

}

