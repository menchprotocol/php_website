<?php

$community_pills = '';

foreach ((isset($_GET['userhandle']) && strlen($_GET['userhandle']) ? $this->Users->read(array('LOWER(userhandle)' => strtolower($_GET['userhandle']))) : $this->Users->scissor(website_setting(0), 13207)) as $user_item) {

    foreach ($this->Chains->read(array(
        'chainuserinput' => $user_item['userid'],
        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
    ), array('chainuseroutput'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC')) as $x) {

        $total_count = users_query(42373, $x['userid'], 0, false);

        if ($total_count) {

            $ui = '<div class="row justify-content">';
            foreach (users_query(42373, $x['userid'], 1, false) as $count => $e) {
                $ui .= user_view(13207, $e, null);
            }
            $ui .= '</div>';

            $community_pills .= view_pill(12274, $x['userid'], $total_count, array(
                'm__cover' => view_cover($x['usercover'], true),
                'm__name' => $x['username'],
                'm__message' => $x['chainvalue'],
                'm__handle' => $x['userhandle'],
            ), $ui);

        }
    }
}


sasdasd
if (strlen($community_pills)) {

    //Community of users:
    echo '<h2 class="center">' . $user_item['username'] . '</h2>';
    echo '<ul class="nav nav-tabs nav12274"></ul>';
    echo $community_pills;

} else {

    echo 'Community settings not yet setup for your website';

}

