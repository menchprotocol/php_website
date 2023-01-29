<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){

    echo 'Missing source ID (Append ?e__id=SOURCE_ID in URL)';

} else {

    //Fetch followings URLs:
    $following = $this->X_model->fetch(array(
        'x__type IN (' . join(',', $this->config->item('n___4537')) . ')' => null, //SOURCE LINK URLS
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        'x__down' => $_GET['e__id'],
    ), array('x__up'), 0, 0, array('e__title' => 'DESC'));


    echo '<div class="center-info">';
    echo '<div class="text-center platform-large">'.get_domain('m__cover').'</div>';
    echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
    echo '</div>';


    if(!count($following)){

        js_php_redirect(home_url(), 1);

    } else {

        js_php_redirect($following[0]['x__message'], 1);

    }

}
