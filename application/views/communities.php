<?php

$community_pills = '';

foreach ((isset($_GET['handlestring']) && strlen($_GET['handlestring']) ? $this->Handles->read(array('LOWER(handlestring)' => strtolower($_GET['handlestring']))) : $this->Handles->scissor(website_setting(0), 13207)) as $handle_item) {

    foreach ($this->Chains->read(array(
        'chainhandleinput' => $handle_item['handleid'],
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    ), array('chainhandleoutput'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC')) as $x) {

        $total_count = handles_query(42373, $x['handleid'], 0, false);

        if ($total_count) {

            $ui = '<div class="row justify-content">';
            foreach (handles_query(42373, $x['handleid'], 1, false) as $count => $e) {
                $ui .= handle_view(13207, $e, null);
            }
            $ui .= '</div>';

            $community_pills .= view_pill(12274, $x['handleid'], $total_count, array(
                'm__cover' => view_cover($x['handlecover'], true),
                'm__title' => $x['handlevalue'],
                'm__message' => $x['chainvalue'],
                'm__handle' => $x['handlestring'],
            ), $ui);

        }
    }
}


if (strlen($community_pills)) {

    //Community
    echo '<h2 class="center">' . $handle_item['handlevalue'] . '</h2>';
    echo '<ul class="nav nav-tabs nav12274"></ul>';
    echo $community_pills;

} else {

    echo 'Community settings not yet setup for your website';

}

