<?php

$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

//List Plugins:
echo '<div class="container">';

echo '<h1 style="padding-top:5px;"><span class="icon-block">'.echo_en_icon($en_all_11035[6287]['m_icon']).'</span>'.count($this->config->item('en_all_6287')).' '.$en_all_11035[6287]['m_name'].'</h1>';

echo '<div class="list-group">';
foreach($this->config->item('en_all_6287') as $en_id => $m) {

    echo '<a href="/plugin/'.$en_id.'" class="list-group-item no-side-padding">';

    //SOURCE
    echo '<span class="icon-block">' . echo_en_icon($m['m_icon']) . '</span>';
    echo '<b class="montserrat '.extract_icon_color($m['m_icon']).'">'.$m['m_name'].'</b>';
    echo ( strlen($m['m_desc']) ? '&nbsp;'.$m['m_desc'] : '' );


    //PROFILE
    echo '<div class="pull-right inline-block">';
    foreach($this->TRANSACTION_model->fetch(array(
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
        'ln_portfolio_source_id' => $en_id,
        'ln_profile_source_id !=' => 6287,
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
    ), array('en_profile')) as $en_profile){
        echo '<span class="icon-block-img en_child_icon_' . $en_profile['en_id'] . '" data-toggle="tooltip" title="' . $en_profile['en_name'] . (strlen($en_profile['ln_content']) > 0 ? ' = ' . $en_profile['ln_content'] : '') . '" data-placement="top">' . echo_en_icon($en_profile['en_icon']) . '</span>&nbsp;';
    }
    echo '</div>';

    echo '</a>';
}
echo '</div>';
echo '</div>';