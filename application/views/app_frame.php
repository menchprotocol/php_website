<?php


echo '<div class="container">';



if($memory_detected && !in_array($app_e__id, $this->config->item('n___14597'))){

    $e___6287 = $this->config->item('e___6287'); //MENCH APP
    //echo '<span class="icon-block">'.view_e__icon($e___6287[$app_e__id]['m__icon']).'</span>';
    echo '<h1 class="'.extract_icon_color($e___6287[$app_e__id]['m__icon']).'">' . $e___6287[$app_e__id]['m__title'] . '</h1>';
    if(strlen($e___6287[$app_e__id]['m__message']) > 0){
        echo '<p class="msg">'.$e___6287[$app_e__id]['m__message'].'</p>';
    }

}


//Load App:
$this->load->view('app/'.$app_e__id, array(
    'app_e__id' => $app_e__id,
    'user_e' => $user_e,
    'is_u_request' => $is_u_request,
    'memory_detected' => $memory_detected,
));

echo '</div>';