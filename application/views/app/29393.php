<?php

echo '<div class="sidebar hidden"><div style="padding:5px;"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Redirecting...</div></div>';

//Log link if not there:
if(isset($_GET['e__id']) &&
    isset($_GET['member_id']) &&
    count($this->X_model->fetch(array(
        'x__up' => 4430, //MEMBERS
        'x__down' => $_GET['member_id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
    ))) && count($this->X_model->fetch(array(
        'x__up' => 29393,
        'x__down' => $_GET['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
    ))) && !count($this->X_model->fetch(array(
        'x__up' => $_GET['e__id'],
        'x__down' => $_GET['member_id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
    )))){
    //Add source link:
    $this->X_model->create(array(
        'x__type' => e_x__type(),
        'x__source' => $_GET['member_id'],
        'x__up' => $_GET['e__id'],
        'x__down' => $_GET['member_id'],
    ));
}

//Redirect to next step:
js_redirect((isset($_GET['i__id']) ? intval($_GET['i__id']) : ''), 13);