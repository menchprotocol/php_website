<?php

echo '<div class="container">';

    //Plugins, Header & Source Link:
    //echo '<h1 class="'.extract_icon_color($source['en_icon']).' pull-left inline-block" style="padding-top:5px;"><span class="icon-block en_ui_icon_'.$source['en_id'].'">'.echo_en_icon($source['en_icon']).'</span><span class="icon-block en_status_source_id_' . $source['en_id'] . ( $is_published ? ' hidden ' : '' ).'"><span data-toggle="tooltip" data-placement="bottom" title="'.$en_all_6177[$source['en_status_source_id']]['m_name'].': '.$en_all_6177[$source['en_status_source_id']]['m_desc'].'">' . $en_all_6177[$source['en_status_source_id']]['m_icon'] . '</span></span><span class="en_name_full_'.$source['en_id'].'">'.$source['en_name'].'</span></h1>';

    echo 'HEAD';

    //Load Plugin:
    $this->load->view('source/plugin/'.$plugin_en_id.'/index', array(
        'plugin_en_id' => $plugin_en_id,
        'session_en' => $session_en,
    ));

echo '</div>';
