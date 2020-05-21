<?php

$sources__6287 = $this->config->item('sources__6287'); //MENCH PLUGIN
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION

echo '<div class="container">';

    //Plugins, Header & Source Link:
    echo '<h1 style="padding-top:5px;"><a href="/source/plugin"><span class="icon-block">'.view_source__icon($sources__11035[6287]['m_icon']).'</span></a><a href="/source/'.$plugin_source__id.'"><span class="icon-block">'.view_source__icon($sources__6287[$plugin_source__id]['m_icon']).'</span>'.$sources__6287[$plugin_source__id]['m_name'].'</a></h1>';

    //Optional Description:
    if(strlen($sources__6287[$plugin_source__id]['m_desc']) > 0){
        echo '<p>'.$sources__6287[$plugin_source__id]['m_desc'].'</p>';
    }

    //Load Plugin:
    $this->load->view('source/plugin/'.$plugin_source__id.'/index', array(
        'plugin_source__id' => $plugin_source__id,
        'session_source' => $session_source,
        'is_player_request' => $is_player_request,
    ));

echo '</div>';
