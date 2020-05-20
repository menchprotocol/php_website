<?php

$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION

//List Plugins:
echo '<div class="container">';

echo '<h1 style="padding-top:5px;"><span class="icon-block">'.view_source__icon($sources__11035[6287]['m_icon']).'</span>'.count($this->config->item('sources__6287')).' '.$sources__11035[6287]['m_name'].'</h1>';

echo '<div class="list-group">';
foreach($this->config->item('sources__6287') as $source__id => $m) {

    echo '<a href="/source/plugin/'.$source__id.'" class="list-group-item no-side-padding">';

    //SOURCE
    echo '<span class="icon-block">' . view_source__icon($m['m_icon']) . '</span>';
    echo '<b class="montserrat '.extract_icon_color($m['m_icon']).'">'.$m['m_name'].'</b>';
    echo ( strlen($m['m_desc']) ? '&nbsp;'.$m['m_desc'] : '' );


    //PROFILE
    echo '<div class="pull-right inline-block">';
    foreach($this->READ_model->fetch(array(
        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'read__down' => $source__id,
        'read__up !=' => 6287,
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
    ), array('source_profile')) as $source_profile){
        echo '<span class="icon-block-img en_child_icon_' . $source_profile['source__id'] . '" data-toggle="tooltip" title="' . $source_profile['source__title'] . (strlen($source_profile['read__message']) > 0 ? ' = ' . $source_profile['read__message'] : '') . '" data-placement="top">' . view_source__icon($source_profile['source__icon']) . '</span>&nbsp;';
    }
    echo '</div>';

    echo '</a>';
}
echo '</div>';
echo '</div>';