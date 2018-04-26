<?php
/**
 * Created by PhpStorm.
 * User: shervinenayati
 * Date: 2018-04-13
 * Time: 9:39 AM
 */

$engagement_filters = array(
    'r_id' => 'Class ID',
    'pid' => 'Broadcast Message ID',
);

echo '<form action="" method="GET">';
echo '<table class="table table-condensed"><tr>';
foreach($engagement_filters as $key=>$value){
    echo '<td><div style="padding-right:5px;">';
    echo '<input type="text" name="'.$key.'" placeholder="'.$value.'" value="'.((isset($_GET[$key]))?$_GET[$key]:'').'" class="form-control border">';
    echo '</div></td>';
}
echo '<td><input type="submit" class="btn btn-sm btn-primary" value="Apply" /></td>';
echo '</tr></table>';
echo '</form>';



//TODO Define Instructors we'd be focused on:
$qualified_instructors = array();

if(isset($_GET['r_id']) && intval($_GET['r_id'])>0){
    $users = $this->Db_model->ru_fetch(array(
        'ru_r_id' => $_GET['r_id'],
        'ru_status' => 4,
    ));
} else {
    $users = $this->Db_model->u_fetch(array(
        'u_status >=' => 2,
    ));
}

?>

    <script>
        function i_test(u_id,pid,depth){
            $('#i_test_'+u_id).html('Sending...').hide().fadeIn();
            //Save the rest of the content:
            $.post("/api_v1/i_test", {
                u_id:u_id,
                depth:depth,
                pid:pid,
            } , function(data) {

                if(data.status){
                    //Update UI to confirm with user:
                    $('#i_test_'+u_id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>').hide().fadeIn();
                } else {
                    alert('ERROR: '+data.message);
                    $('#i_test_'+u_id).html('ERROR').hide().fadeIn();
                }

            });
        }
    </script>
    <table class="table table-condensed table-striped left-table" style="font-size:0.8em; width:100%;">
        <thead>
        <tr>
            <th style="width:40px;">#</th>
            <th style="width:40px;">ID</th>
            <th style="width:30px;">&nbsp;</th>
            <th>User</th>
            <th>Bootcamps</th>
            <th>Joined</th>
            <th colspan="4"><i class="fa fa-commenting" aria-hidden="true"></i> Latest Message / <i class="fa fa-eye" aria-hidden="true"></i> Read (Total)</th>
            <th>Timezone</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
<?php
foreach($users as $key=>$user){

    //Fetch messages if activated Messenger:
    unset($messages);
    unset($read_message);
    if($user['u_cache__fp_psid']>0){
        $messages = $this->Db_model->e_fetch(array(
            '(e_inbound_u_id='.$user['u_id'].' OR e_outbound_u_id='.$user['u_id'].')' => null,
            '(e_inbound_c_id IN (6,7))' => null,
        ));
        $read_message = $this->Db_model->e_fetch(array(
            '(e_inbound_u_id='.$user['u_id'].' OR e_outbound_u_id='.$user['u_id'].')' => null,
            'e_inbound_c_id' => 1,
        ),1);
    }


    //Fetch Bootcamps:
    $instructor_bs = $this->Db_model->ba_fetch(array(
        'ba.ba_outbound_u_id' => $user['u_id'],
        'ba.ba_status >=' => 0,
        'b.b_status >=' => 2,
    ) , true /*To Fetch more details*/ );

    echo '<tr>';
    echo '<td>'.($key+1).'</td>';
    echo '<td>'.$user['u_id'].'</td>';
    echo '<td>'.status_bible('u',$user['u_status'],1,'right').'</td>';
    echo '<td><a href="/cockpit/browse/engagements?e_u_id='.$user['u_id'].'" title="View All Engagements">'.$user['u_fname'].' '.$user['u_lname'].'</a>'.( $user['u_unsubscribe_fb_id']>0 ? ' <i class="fa fa-exclamation-triangle" data-toggle="tooltip" title="User has Unsubscribed" data-placement="bottom" style="color:#FF0000;"></i>' : '' ).'</td>';


    echo '<td>';
    //Display Bootcamps:
    if(count($instructor_bs)>0){
        $meaningful_b_engagements = $this->config->item('meaningful_b_engagements');

        foreach ($instructor_bs as $counter=>$ib){
            //Fetch last activity:
            $b_building_engagements = $this->Db_model->e_fetch(array(
                'e_inbound_u_id' => $user['u_id'],
                'e_b_id' => $ib['b_id'],
                '(e_inbound_c_id IN ('.join(',',$meaningful_b_engagements).'))' => null,
            ));

            echo '<div>'.($counter+1).') <a href="/console/'.$ib['b_id'].'">'.$ib['c_outcome'].'</a> '.( isset($b_building_engagements[0]) ? time_format($b_building_engagements[0]['e_timestamp'],1) : '---' ).'/'.( count($b_building_engagements)>=100 ? '100+' : count($b_building_engagements) ).'</div>';
        }
    } else {
        echo '---';
    }
    echo '</td>';
    echo '<td>'.time_format($user['u_timestamp'],1).'</td>';
    echo '<td>'.( isset($messages[0]) && $user['u_cache__fp_psid']>0 ? ( $messages[0]['e_inbound_c_id']==6 ? '<b style="color:#FF0000">Received</b>' : 'Sent' ).' on' : 'Not Activated' ).'</td>';
    echo '<td>'.( isset($messages[0]) && $user['u_cache__fp_psid']>0 ? time_format($messages[0]['e_timestamp'],1) : '' ).'</td>';
    echo '<td>'.( isset($read_message[0]) ? '<i class="fa fa-eye" aria-hidden="true"></i> '.time_format($read_message[0]['e_timestamp'],1) : '' ).'</td>';
    echo '<td>'.( isset($messages[0]) && $user['u_cache__fp_psid']>0 ? '<b>('.(count($messages)>=100 ? '100+' : count($messages)).')</b>' : '' ).'</td>';
    echo '<td>'.$user['u_timezone'].'</td>';
    echo '<td>';

    if(strlen($user['u_email'])>0){
        echo '<a href="mailto:'.$user['u_email'].'" title="Email '.$user['u_email'].'"><i class="fa fa-envelope" aria-hidden="true"></i></a>&nbsp;';
    }

    if(isset($_GET['pid']) && $user['u_cache__fp_psid']>0){
        //Lets check their history:
        $sent_messages = $this->Db_model->e_fetch(array(
            'e_inbound_c_id' => 7,
            'e_outbound_u_id' => $user['u_id'],
            'e_outbound_u_id' => intval($_GET['pid']),
        ),1);
        if(count($sent_messages)>0){
            //Already sent!
            echo '<i class="fa fa-check-circle" aria-hidden="true"></i> ';
        }

        //Show send button:
        echo '&nbsp;<span id="i_test_'.$user['u_id'].'"><a href="javascript:i_test('.$user['u_id'].','.$_GET['pid'].','.( isset($_GET['depth']) ? intval($_GET['depth']) : 0 ).')">'.(count($sent_messages)>0 ? 'Resend' : 'Send').'</a></span>';
    }
    echo '</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';