<?php

if(!isset($_GET['e__handle']) || !strlen($_GET['e__handle'])){

    echo 'Missing e__handle';

} else {


    echo '<div class="center-info">';
    echo '<div class="text-center platform-large">'.get_domain('m__cover').'</div>';
    echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
    echo '</div>';


    //Fetch followings URLs:
    $url_found = false;
    foreach($this->E_model->fetch(array(
        'LOWER(e__handle)' => strtolower($_GET['e__handle']),
    )) as $e){
        foreach($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            'x__down' => $e['e__id'],
            'LENGTH(x__message)>0' => null,
        ), array('x__up'), 0, 0, array('e__title' => 'DESC')) as $f_url){
            if(filter_var($f_url['x__message'], FILTER_VALIDATE_URL)){
                $url_found = true;
                js_php_redirect($f_url['x__message'], 1);
                break;
            }
        }
    }


    if(!$url_found){
        js_php_redirect(home_url(), 1);
    }

}
