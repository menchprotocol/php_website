<?php

$is = $this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 ),
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
));

if(!count($is) || !$member_e){

    js_redirect('/', 13);

} else {

    echo $is[0]['i__title'];

    echo view_e_settings(14709, false);

}

