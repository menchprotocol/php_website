<?php

$community_pills = '';

//Load Filters:
$groups_ids = array();
$groups_all = array();

foreach ($this->Chains->read(array(
    'chainsourceup' => $focus_e['sourceid'],
    'chainsourcetype' => 4230, //SOURCE FOLLOW
), array('chainsourcedown'), 0, 1, source_sort()) as $group) {
    array_push($groups_ids, intval($group['sourceid']));
    $groups_all[intval($group['sourceid'])] = $group;
}

//Load Main:
$content_ui = '';
$group_counts = array();
$content_ui .= '<div class="group_content">';
foreach ($this->Chains->read(array(
    'chainsourceup' => $focus_e['sourceid'],
    'chainsourcetype' => 4230, //SOURCE FOLLOW
), array('chainsourcedown'), 1, 0, source_sort()) as $group_main) {

    foreach ($this->Chains->read(array(
        'chainsourceup' => $group_main['sourceid'],
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___33337')) . ')' => null, //SOURCE CHAINS
    ), array('chainsourcedown'), 0, 0, source_sort()) as $us) {

        if(!isset($group_counts[$group_main['sourceid']])){
            $group_counts[$group_main['sourceid']] = 0;
        }
        $group_counts[$group_main['sourceid']]++;

        //See which filters belong to this member:
        $group_class = 'main_group';
        foreach ($this->Chains->read(array(
            'chainsourceup IN (' . join(',', $groups_ids) . ')' => null,
            'chainsourcedown' => $us['sourceid'],
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___33337')) . ')' => null, //SOURCE CHAINS
        ), array(), 0) as $filter) {
            if(!isset($group_counts[$filter['sourceid']])){
                $group_counts[$filter['sourceid']] = 0;
            }
            $group_counts[$filter['sourceid']]++;
            $group_class .= ' group_'.$filter['sourceid'];
        }

        $content_ui .= '<div class="'.$group_class.'">';
        $content_ui .= source_view(6255, $us);
        $content_ui .= '</div>';

    }
}
$content_ui .= '</div>';



echo '<ul class="nav nav-tabs nav12274">';
foreach ($this->Chains->read(array(
    'chainsourceup' => $focus_e['sourceid'],
    'chainsourcetype' => 4230, //SOURCE FOLLOW
), array('chainsourcedown'), 0, 0, source_sort()) as $group) {
    echo '<li class="nav-item thepill42256"><a class="nav-chain" href="javascript:void(0);" href="javascript:void(0);" onclick="$(\'.html_message_' . $group['sourceid'] . '\').toggleClass(\'hidden\');">&nbsp;<span class="icon-block">'.view_cover($group['sourcecover']).'</span><span class="main__title"></span><span class="main__title hidden xtypetitle_'.$group['sourceid'].'">&nbsp;'.$group_counts[$group['sourceid']].'&nbsp;'.$group['sourcevalue'].'&nbsp;</span></a></li>';
}
echo '</ul>';