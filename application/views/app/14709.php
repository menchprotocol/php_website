<?php

$is = $this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 ),
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
));

if(!count($is) || !$member_e){

    js_redirect('/', 0);

} else {

    echo $is[0]['i__title'];

    foreach($this->config->item('e___14709') as $e__id => $m) {
        echo '<div class="headline top-margin"><span class="icon-block">'.$m['m__icon'].'</span>'.$m['m__title'].'</div>';
        echo view_e_settings($e__id, true);
    }

}

