<?php

$community_pills = '';
$main_user_id = 0;

echo '<h1>'.$focus_e['username'].'</h1>';

//Load Filters:
$groups_ids = array();
$groups_all = array();

foreach ($this->Chains->read(array(
    'chainuserinput' => $focus_e['userid'],
    'chainusertype' => 4230, //USER FOLLOW
), array('chainuseroutput'), 0, 1, array('chainvalue' => 'ASC', 'chainid' => 'DESC')) as $group) {
    array_push($groups_ids, intval($group['userid']));
    $groups_all[intval($group['userid'])] = $group;
}

$full_group_ids = array();
foreach ($this->Chains->read(array(
    'chainuserinput' => $focus_e['userid'],
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
), array(), 0, 0, array('chainvalue' => 'ASC', 'chainid' => 'DESC')) as $group) {
    array_push($full_group_ids, intval($group['chainuseroutput']));
}

//Load Main:
$content_ui = '';
$group_counts = array();
$content_ui .= '<div class="row justify-content group_content">';
foreach ($this->Chains->read(array(
    'chainuserinput' => $focus_e['userid'],
    'chainusertype' => 4230, //USER FOLLOW
), array('chainuseroutput'), 1, 0, user_sort()) as $group_main) {

    $main_user_id = intval($group_main['userid']);

    foreach ($this->Chains->read(array(
        'chainuserinput' => $group_main['userid'],
        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
    ), array('chainuseroutput'), 0, 0, array('chainvalue' => 'ASC', 'chainid' => 'DESC')) as $us) {

        if(!isset($group_counts[$group_main['userid']])){
            $group_counts[$group_main['userid']] = array();
        }
        if(!in_array($us['userid'], $group_counts[$group_main['userid']])){
            array_push($group_counts[$group_main['userid']], $us['userid']);
        }

        //See which filters belong to this member:
        $group_class = 'main_group';
        foreach ($this->Chains->read(array(
            'chainuserinput IN (' . join(',', $groups_ids) . ')' => null,
            'chainuseroutput' => $us['userid'],
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array(), 0) as $filter) {
            if(!isset($group_counts[$filter['chainuserinput']])){
                $group_counts[$filter['chainuserinput']] = array();
            }
            if(!in_array($us['userid'], $group_counts[$filter['chainuserinput']])){
                array_push($group_counts[$filter['chainuserinput']], $us['userid']);

            }
            $group_class .= ' group_'.$filter['chainuserinput'];
        }

        $extra_value = '';
        foreach ($this->Chains->read(array(
            'chainuserinput IN (' . join(',', $full_group_ids) . ')' => null,
            'chainuseroutput' => $us['userid'],
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            'LENGTH(chainvalue) > 0' => null,
        ), array(), 0, 0, user_sort()) as $group) {
            $extra_value .= '<div class="grey extra_descs hidden extra_desc_'.$group['chainuserinput'].'">'.$group['chainvalue'].'</div>';
        }

        $content_ui .= user_view(1637076, $us, $group_class, $extra_value);

    }
}
$content_ui .= '</div>';




echo '<ul class="nav nav-tabs nav12274" style="display: flex !important; justify-content: space-evenly;">';
foreach ($this->Chains->read(array(
    'chainuserinput' => $focus_e['userid'],
    'chainusertype' => 4230, //USER FOLLOW
), array('chainuseroutput'), 0, 0, user_sort()) as $group) {
    if(!isset($group_counts[$group['userid']]) || !count($group_counts[$group['userid']])){
        continue;
    }
    echo '<li class="nav-item nav-chain '.( $group['userid']==$main_user_id ? ' active ' : '' ).' navgroup_'.$group['userid'].'"><a class="nav-chain" href="javascript:void(0);" href="javascript:void(0);" onclick="load_group(' . $group['userid'] . ')">&nbsp;<span class="icon-block">'.view_cover($group['usercover']).'</span><span class="main__title">'.( isset($group_counts[$group['userid']]) && count($group_counts[$group['userid']])>0 ? count($group_counts[$group['userid']]) : '' ).'</span><span class="main__title '.( $group['userid']==$main_user_id ? '' : ' hidden ' ).' grouptitle grouptitle_'.$group['userid'].'">&nbsp;'.trim(str_replace($focus_e['username'], '', $group['username'])).'&nbsp;</span></a></li>';
}
echo '</ul>';

echo $content_ui;
?>


<script>

    $(document).ready(function () {
        load_group(2102137);
    });

    var main_user_id = <?= $main_user_id ?>;
    function load_group(group_id){

        //Remove all filters:
        $('.grouptitle').addClass('hidden');
        $('.grouptitle_'+group_id).removeClass('hidden');
        $('.extra_descs').addClass('hidden');
        $('.extra_desc_'+group_id).removeClass('hidden');
        $('.nav-item').removeClass('active');
        $('.navgroup_'+group_id).addClass('active');


        if(main_user_id!=group_id){
            $('.main_group').addClass('hidden');
            $('.group_'+group_id).removeClass('hidden');
        } else {
            $('.main_group').removeClass('hidden');
        }
    }

</script>
