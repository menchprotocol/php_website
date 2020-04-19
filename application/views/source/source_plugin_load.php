<?php

$en_all_6287 = $this->config->item('en_all_6287'); //MENCH PLUGIN
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION


echo '<div class="container">';

    //Plugins, Header & Source Link:
    echo '<h1 style="padding-top:5px;"><a href="/plugin"><span class="icon-block">'.echo_en_icon($en_all_11035[6287]['m_icon']).'</span></a><a href="/source/'.$plugin_en_id.'"><span class="icon-block">'.echo_en_icon($en_all_6287[$plugin_en_id]['m_icon']).'</span>'.$en_all_6287[$plugin_en_id]['m_name'].'</a></h1>';


    //Optional Description:
    if(strlen($en_all_6287[$plugin_en_id]['m_desc']) > 0){
        echo '<p>'.$en_all_6287[$plugin_en_id]['m_desc'].'</p>';
    }

    //Load Plugin:
    $this->load->view('source/plugin/'.$plugin_en_id.'/index', array(
        'plugin_en_id' => $plugin_en_id,
        'session_en' => $session_en,
    ));

echo '</div>';
