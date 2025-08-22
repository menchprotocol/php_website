<?php

$community_pills = '';
$main_handle_id = 0;

echo '<h1>'.$focus_e['handlename'].'</h1>';
echo '<img src="https://res.cloudinary.com/menchcloud/image/upload/v1755829760/gx8bun1gwibjmfh3kvqz.jpg" style="max-width: 100%;" alt="Discotique 25 Camp Map" />';

//Load Filters:
$groups_ids = array();
$groups_all = array();

foreach ($this->Chains->read(array(
    'chainhandleinput' => $focus_e['handleid'],
    'chainhandletype' => 4230, //HANDLE FOLLOW
), array('chainhandleoutput'), 0, 1, array('chainvalue' => 'ASC', 'chainid' => 'DESC')) as $group) {
    array_push($groups_ids, intval($group['handleid']));
    $groups_all[intval($group['handleid'])] = $group;
}

$full_group_ids = array();
foreach ($this->Chains->read(array(
    'chainhandleinput' => $focus_e['handleid'],
    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
), array(), 0, 0, array('chainvalue' => 'ASC', 'chainid' => 'DESC')) as $group) {
    array_push($full_group_ids, intval($group['chainhandleoutput']));
}

//Load Main:
$content_ui = '';
$group_counts = array();
$content_ui .= '<div class="row justify-content group_content">';
foreach ($this->Chains->read(array(
    'chainhandleinput' => $focus_e['handleid'],
    'chainhandletype' => 4230, //HANDLE FOLLOW
), array('chainhandleoutput'), 1, 0, handle_sort()) as $group_main) {

    $main_handle_id = intval($group_main['handleid']);

    foreach ($this->Chains->read(array(
        'chainhandleinput' => $group_main['handleid'],
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    ), array('chainhandleoutput'), 0, 0, array('chainvalue' => 'ASC', 'chainid' => 'DESC')) as $us) {

        if(!isset($group_counts[$group_main['handleid']])){
            $group_counts[$group_main['handleid']] = array();
        }
        if(!in_array($us['handleid'], $group_counts[$group_main['handleid']])){
            array_push($group_counts[$group_main['handleid']], $us['handleid']);
        }

        //See which filters belong to this member:
        $group_class = 'main_group';
        foreach ($this->Chains->read(array(
            'chainhandleinput IN (' . join(',', $groups_ids) . ')' => null,
            'chainhandleoutput' => $us['handleid'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array(), 0) as $filter) {
            if(!isset($group_counts[$filter['chainhandleinput']])){
                $group_counts[$filter['chainhandleinput']] = array();
            }
            if(!in_array($us['handleid'], $group_counts[$filter['chainhandleinput']])){
                array_push($group_counts[$filter['chainhandleinput']], $us['handleid']);

            }
            $group_class .= ' group_'.$filter['chainhandleinput'];
        }

        $extra_value = '';
        foreach ($this->Chains->read(array(
            'chainhandleinput IN (' . join(',', $full_group_ids) . ')' => null,
            'chainhandleoutput' => $us['handleid'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            'LENGTH(chainvalue) > 0' => null,
        ), array(), 0, 0, handle_sort()) as $group) {
            $extra_value .= '<div class="grey extra_descs hidden extra_desc_'.$group['chainhandleinput'].'">'.$group['chainvalue'].'</div>';
        }

        $content_ui .= handle_view(1637076, $us, $group_class, $extra_value);

    }
}
$content_ui .= '</div>';




echo '<ul class="nav nav-tabs nav12274" style="display: flex !important; justify-content: space-evenly;">';
foreach ($this->Chains->read(array(
    'chainhandleinput' => $focus_e['handleid'],
    'chainhandletype' => 4230, //HANDLE FOLLOW
), array('chainhandleoutput'), 0, 0, handle_sort()) as $group) {
    if(!isset($group_counts[$group['handleid']]) || !count($group_counts[$group['handleid']])){
        continue;
    }
    echo '<li class="nav-item nav-chain '.( $group['handleid']==$main_handle_id ? ' active ' : '' ).' navgroup_'.$group['handleid'].'"><a class="nav-chain" href="javascript:void(0);" href="javascript:void(0);" onclick="load_group(' . $group['handleid'] . ')">&nbsp;<span class="icon-block">'.view_cover($group['handlecover']).'</span><span class="main__title">'.( isset($group_counts[$group['handleid']]) && count($group_counts[$group['handleid']])>0 ? count($group_counts[$group['handleid']]) : '' ).'</span><span class="main__title '.( $group['handleid']==$main_handle_id ? '' : ' hidden ' ).' grouptitle grouptitle_'.$group['handleid'].'">&nbsp;'.trim(str_replace($focus_e['handlename'], '', $group['handlename'])).'&nbsp;</span></a></li>';
}
echo '</ul>';

echo $content_ui;
?>


<script>

    $(document).ready(function () {
        load_group(2102137);
    });

    var main_handle_id = <?= $main_handle_id ?>;
    function load_group(group_id){

        //Remove all filters:
        $('.grouptitle').addClass('hidden');
        $('.grouptitle_'+group_id).removeClass('hidden');
        $('.extra_descs').addClass('hidden');
        $('.extra_desc_'+group_id).removeClass('hidden');
        $('.nav-item').removeClass('active');
        $('.navgroup_'+group_id).addClass('active');


        if(main_handle_id!=group_id){
            $('.main_group').addClass('hidden');
            $('.group_'+group_id).removeClass('hidden');
        } else {
            $('.main_group').removeClass('hidden');
        }
    }

</script>
