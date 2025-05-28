<?php

$community_pills = '';
$main_source_id = 0;
$main_source_name = '';

echo '<h1>'.$focus_e['sourcevalue'].'</h1>';

//Load Filters:
$groups_ids = array();
$groups_all = array();

foreach ($this->Chains->read(array(
    'chainsourceup' => $focus_e['sourceid'],
    'chainsourcetype' => 4230, //SOURCE FOLLOW
), array('chainsourcedown'), 0, 1, source_sort()) as $group) {
    array_push($groups_ids, intval($group['sourceid']));
    $groups_all[intval($group['sourceid'])] = $group;
    $main_source_name = $group['sourcevalue'];
    $main_source_id = intval($group['sourceid']);
}

//Load Main:
$content_ui = '';
$group_counts = array();
$content_ui .= '<div class="row justify-content group_content">';
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
            if(!isset($group_counts[$filter['chainsourceup']])){
                $group_counts[$filter['chainsourceup']] = 0;
            }
            $group_counts[$filter['chainsourceup']]++;
            $group_class .= ' group_'.$filter['chainsourceup'];
        }

        $content_ui .= source_view(6255, $us, $group_class);

    }
}
$content_ui .= '</div>';



echo '<ul class="nav nav-tabs nav12274">';
foreach ($this->Chains->read(array(
    'chainsourceup' => $focus_e['sourceid'],
    'chainsourcetype' => 4230, //SOURCE FOLLOW
), array('chainsourcedown'), 0, 0, source_sort()) as $group) {
    echo '<li class="nav-item thepill42256"><a class="nav-chain" href="javascript:void(0);" href="javascript:void(0);" onclick="load_group(' . $group['sourceid'] . ')">&nbsp;<span class="icon-block">'.view_cover($group['sourcecover']).'</span><span class="main__title">'.( isset($group_counts[$group['sourceid']]) ? $group_counts[$group['sourceid']] : 0 ).'</span><span class="main__title '.( $group['sourceid']==$main_source_id ? '' : ' hidden ' ).' grouptitle grouptitle_'.$group['sourceid'].'">&nbsp;'.trim(str_replace($main_source_name, '', $group['sourcevalue'])).'&nbsp;</span></a></li>';
}
echo '</ul>';

echo $content_ui;
?>


<script>

    var main_source_id = <?= $main_source_id ?>;
    function load_group(group_id){

        //Remove all filters:
        $('.grouptitle').addClass('hidden');
        $('.grouptitle_'+group_id).removeClass('hidden');

        if(main_source_id!=group_id){
            $('.main_group').addClass('hidden');
            $('.group_'+group_id).removeClass('hidden');
        } else {
            $('.main_group').removeClass('hidden');
        }
    }

</script>
