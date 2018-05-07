<?php
/**
 * Created by PhpStorm.
 * User: shervinenayati
 * Date: 2018-04-13
 * Time: 9:39 AM
 */

//A function to echo the Bootcamp rows:
function echo_row($b,$counter){

    echo '<tr>';
    echo '<td>'.$counter.'</td>';
    echo '<td>'.$b['b_id'].'</td>';
    echo '<td>'.status_bible('b',$b['b_status'],1,'right').'</td>';
    echo '<td>'.( $b['b_old_format'] ? '<i class="fas fa-lock" style="color:#FF0000;" title="OLD FORMAT"></i> ' : '' ).'<a href="/console/'.$b['b_id'].'">'.$b['c_outcome'].'</a></td>';

    echo '<td><a href="https://www.facebook.com/'.$b['fp_fb_id'].'">'.$b['fp_name'].'</a></td>';
    echo '<td>'.( $b['b_difficulty_level']>=1 ? status_bible('df',$b['b_difficulty_level'],1,'top') : '' ).'</td>';

    echo '<td>'.( isset($b['leaders'][0]) ? '<a href="/cockpit/browse/engagements?e_u_id='.$b['leaders'][0]['u_id'].'" title="User ID '.$b['leaders'][0]['u_id'].'">'.$b['leaders'][0]['u_full_name'].'</a>' : '' ).'</td>';

    //Pricing:
    echo '<td>'.echo_price($b,1).'</td>';
    echo '<td>'.echo_price($b,2).'</td>';
    echo '<td>'.echo_price($b,3).'</td>';


    echo '<td>';
    if($b['student_funnel'][0]>0 || $b['student_funnel'][4]>0){
        echo '<span data-toggle="tooltip" title="Initiated Admission -> Completed Admission">';
        echo $b['student_funnel'][0].' &raquo; <b>'.$b['student_funnel'][4].'</b>';
        echo '</span>';
    }
    echo '</td>';

    echo '<td>';
    echo ( count($b['engagements'])>0 ? '<a href="/cockpit/browse/engagements?e_b_id='.$b['b_id'].'">'.( count($b['engagements'])>=1000 ? '1000+' : count($b['engagements']) ).'</a> ('.time_format($b['engagements'][0]['e_timestamp'],1).')' : 'Never' );


    echo '</td>';
    echo '</tr>';
}

//User Bootcamps:
$bs = $this->Db_model->b_fetch(array(
    'b.b_status >=' => 2,
),array('c','fp'),'b_status');

//Did we find any?
$meaningful_b_engagements = $this->config->item('meaningful_b_engagements');


foreach($bs as $key=>$mb){
    //Fetch Leader:
    $bs[$key]['leaders'] = $this->Db_model->ba_fetch(array(
        'ba.ba_b_id' => $mb['b_id'],
        'ba.ba_status' => 3,
    ));

    //Fetch last activity:
    $bs[$key]['engagements'] = $this->Db_model->e_fetch(array(
        'e_b_id' => $mb['b_id'],
        '(e_inbound_c_id IN ('.join(',',$meaningful_b_engagements).'))' => null,
    ),1000);

    $bs[$key]['student_funnel'] = array(
        0 => count($this->Db_model->ru_fetch(array(
            'ru.ru_b_id'	   => $mb['b_id'],
            'ru.ru_status'     => 0,
        ))),
        4 => count($this->Db_model->ru_fetch(array(
            'ru.ru_b_id'	   => $mb['b_id'],
            'ru.ru_status'     => 4,
        ))),
    );
}

?>

<table class="table table-condensed table-striped left-table" style="font-size:0.8em;">
    <thead>
    <tr style="background-color:#333; color:#fff; font-weight:bold;">
        <th style="width:40px;">#</th>
        <th style="width:40px;">ID</th>
        <th>&nbsp;</th>
        <th>Bootcamp</th>
        <th><i class="fas fa-plug"></i> Facebook Page</th>
        <th>&nbsp;</th>
        <th>Lead Instructor</th>
        <th colspan="3" style="width: 300px;">Pricing</th>
        <th>Admission Funnel</th>
        <th>Activity (Last)</th>
    </tr>
    </thead>
    <tbody>
    <?php

    $b_groups = array(
        'mench_team' => array(),
        'instructor' => array(),
    );
    $counter = 0;

    foreach($bs as $b){
        $is_mench_team = false;
        foreach($b['leaders'] as $key=>$instructor){
            if(in_array($instructor['u_inbound_u_id'], array(1281))){
                $is_mench_team = true;
                break;
            }
        }
        //Group based on who is the admin:
        array_push($b_groups[( $is_mench_team ? 'mench_team' : 'instructor' )],$b);
    }

    foreach($b_groups['instructor'] as $b){
        $counter++;
        echo_row($b,$counter);
    }
    ?>
    </tbody>

    <thead>
    <tr style="background-color:#333; color:#fff; font-weight:bold;">
        <th style="width:40px;">#</th>
        <th style="width:40px;">ID</th>
        <th>&nbsp;</th>
        <th>Bootcamp</th>
        <th><i class="fas fa-plug"></i> Facebook Page</th>
        <th>&nbsp;</th>
        <th>Lead Instructor</th>
        <th colspan="3" style="width:300px;">Pricing</th>
        <th>Admission Funnel</th>
        <th>Activity (Last)</th>
    </tr>
    </thead>

    <tbody>
    <?php
    foreach($b_groups['mench_team'] as $b){
        $counter++;
        echo_row($b,$counter);
    }
    ?>
    </tbody>
</table>