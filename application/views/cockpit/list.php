<?php 

function echo_row($bootcamp,$counter){
    //Calculate their progress:
    $CI =& get_instance();
    $launch_status = calculate_bootcamp_status($bootcamp);
    //Fetch last activity:
    $engagements = $CI->Db_model->e_fetch(array(
        'e_b_id' => $bootcamp['b_id'],
    ),250);
    
    echo '<tr>';
    echo '<td>'.$counter.'</td>';
    echo '<td>'.$bootcamp['b_id'].'</td>';
    echo '<td>'.status_bible('b',$bootcamp['b_status'],1,'right').'</td>';
    echo '<td><a href="/console/'.$bootcamp['b_id'].'">'.$bootcamp['c_objective'].'</a></td>';
    echo '<td><a href="/console/'.$bootcamp['b_id'].'/actionplan">'.$bootcamp['c__milestone_units'].' '.ucwords($bootcamp['b_sprint_unit']).( $bootcamp['c__milestone_units']==1 ? '' : 's' ).'</a></td>';
    echo '<td>'.$launch_status['progress'].'%</td>';
    echo '<td><a href="/console/'.$bootcamp['b_id'].'/classes">'.count($bootcamp['c__classes']).'</a></td>';
    echo '<td>'.$bootcamp['c__message_tree_count'].'</td>';
    echo '<td>';
    foreach($bootcamp['b__admins'] as $key=>$instructor){
        
        //Fetch more details:
        if(strlen($instructor['u_fb_id'])>4){
            $messages = $CI->Db_model->e_fetch(array(
                '(e_initiator_u_id='.$instructor['u_id'].' OR e_recipient_u_id='.$instructor['u_id'].')' => null,
                '(e_type_id=6 OR e_type_id=7)' => null,
            ));
        }
        
        echo '<div><a href="/cockpit/engagements?e_initiator_u_id='.$instructor['u_id'].'" title="User ID '.$instructor['u_id'].'">'.$instructor['u_fname'].' '.$instructor['u_lname'].'</a> '.( strlen($instructor['u_fb_id'])>4 ? '<i class="fa fa-commenting" aria-hidden="true"></i> '.intval(count($messages)) : messenger_activation_url('381488558920384',$instructor['u_id']) ).'</div>';
    }
    echo '</td>';
    echo '<td>'. ( count($engagements)>0 ? time_format($engagements[0]['e_timestamp'],1) : 'Never' ) .'</td>';
    echo '<td><a href="/cockpit/engagements?e_b_id='.$bootcamp['b_id'].'">'.( count($engagements)>=100 ? '100+' : count($engagements) ).'</a></td>';
    
    echo '</tr>';
}


if($object_name=='bootcamps'){
    
    //User Bootcamps:    
    $bootcamps = $this->Db_model->b_fetch(array(
        'b.b_status >=' => 0,
    ));
    
    //Did we find any?
    foreach($bootcamps as $key=>$mb){
        //Fetch full bootcamp:
        $this_full = $this->Db_model->c_full_fetch(array(
            'b.b_id' => $mb['b_id'],
        ));
        $bootcamps[$key] = $this_full[0];
    }
    
    ?>
    <table class="table table-condensed table-striped left-table" style="font-size:0.8em;">
    <thead>
    	<tr>
     		<th style="width:40px;">#</th>
    		<th style="width:40px;">ID</th>
    		<th style="width:30px;">&nbsp;</th>
    		<th>Bootcamp Outcome</th>
    		<th style="width:100px;"><i class="fa fa-flag" aria-hidden="true"></i></th>
    		<th style="width:40px;"><i class="fa fa-tasks" aria-hidden="true"></i></th>
    		<th style="width:40px;"><i class="fa fa-calendar" aria-hidden="true"></i></th>
    		<th style="width:40px;"><i class="fa fa-commenting" aria-hidden="true"></i></th>
    		<th>Instructor(s)</th>
    		<th>Last Activity</th>
    		<th>Engagements</th>
    	</tr>
    </thead>
    <tbody>
    <?php
    
    $bootcamp_groups = array(
        'mench_team' => array(),
        'instructor' => array(),
    );
    $counter = 0;
    
    foreach($bootcamps as $bootcamp){
        $is_mench_team = false;
        foreach($bootcamp['b__admins'] as $key=>$instructor){
            if($instructor['u_status']>=3){
                $is_mench_team = true;
                break;
            }
        }
        
        //Group based on who is the admin:
        array_push($bootcamp_groups[( $is_mench_team ? 'mench_team' : 'instructor' )],$bootcamp);
    }
    
    
    foreach($bootcamp_groups['instructor'] as $bootcamp){
        $counter++;
        echo_row($bootcamp,$counter);
    }
    ?>
    </tbody>
    
    <thead>
    	<tr>
    		<th style="width:40px;">#</th>
    		<th style="width:40px;">ID</th>
    		<th style="width:30px;">&nbsp;</th>
    		<th>Bootcamp Outcome</th>
    		<th style="width:100px;"><i class="fa fa-flag" aria-hidden="true"></i></th>
    		<th style="width:40px;"><i class="fa fa-tasks" aria-hidden="true"></i></th>
    		<th style="width:40px;"><i class="fa fa-calendar" aria-hidden="true"></i></th>
    		<th style="width:40px;"><i class="fa fa-commenting" aria-hidden="true"></i></th>
    		<th>Mench Team Member</th>
    		<th>Last Activity</th>
    		<th>Engagements</th>
    	</tr>
    </thead>
    
    <tbody>
    <?php
    foreach($bootcamp_groups['mench_team'] as $bootcamp){
        $counter++;
        echo_row($bootcamp,$counter);
    }
    ?>
    </tbody>
    </table>
    <?php
    
} elseif($object_name=='users'){

    //TODO Define Instructors we'd be focused on:
    $qualified_instructors = array();
    
    $users = $this->Db_model->u_fetch(array(
        'u_status >=' => 2,
    ));
    ?>

    <script>
        function dispatch_message(u_id,pid){
            $('#dispatch_message_'+u_id).html('Sending...').hide().fadeIn();
            //Save the rest of the content:
            $.post("/api_v1/dispatch_message", {
                u_id:u_id,
                pid:pid,
            } , function(data) {

                if(data.status){
                    //Update UI to confirm with user:
                    $('#dispatch_message_'+u_id).html('<i class="fa fa-check-circle" aria-hidden="true"></i>').hide().fadeIn();
                } else {
                    alert('ERROR: '+data.message);
                    $('#dispatch_message_'+u_id).html('ERROR').hide().fadeIn();
                }

            });
        }
    </script>
    <table class="table table-condensed table-striped left-table" style="font-size:0.8em; width:100%;">
    <thead>
    	<tr>
    		<th style="width:40px;">ID</th>
    		<th style="width:30px;">&nbsp;</th>
            <th>Instructor</th>
            <th>Bootcamps</th>
            <th>Joined</th>
            <th colspan="4"><i class="fa fa-commenting" aria-hidden="true"></i> Latest Message / <i class="fa fa-eye" aria-hidden="true"></i> Read (Total)</th>
            <th>Timezone</th>
            <th>Actions</th>
    	</tr>
    </thead>
    <tbody>
    <?php
    foreach($users as $user){

        //Fetch messages if activated MenchBot:
        unset($messages);
        unset($read_message);
        if(strlen($user['u_fb_id'])>4){
            $messages = $this->Db_model->e_fetch(array(
                '(e_initiator_u_id='.$user['u_id'].' OR e_recipient_u_id='.$user['u_id'].')' => null,
                '(e_type_id IN (6,7))' => null,
            ));
            $read_message = $this->Db_model->e_fetch(array(
                '(e_initiator_u_id='.$user['u_id'].' OR e_recipient_u_id='.$user['u_id'].')' => null,
                'e_type_id' => 1,
            ),1);
        }


        //Fetch Bootcamps:
        $instructor_bootcamps = $this->Db_model->ba_fetch(array(
            'ba.ba_u_id' => $user['u_id'],
            'ba.ba_status >=' => 0,
            'b.b_status >=' => 0,
        ) , true /*To Fetch more details*/ );
        
        echo '<tr>';
        echo '<td>'.$user['u_id'].'</td>';
        echo '<td>'.status_bible('u',$user['u_status'],1,'right').'</td>';
        echo '<td><a href="/cockpit/engagements?e_initiator_u_id='.$user['u_id'].'" title="View All Engagements">'.$user['u_fname'].' '.$user['u_lname'].'</a></td>';
        echo '<td>';
            //Display Bootcamps:
            if(count($instructor_bootcamps)>0){
                foreach ($instructor_bootcamps as $counter=>$ib){
                    //Fetch last activity:
                    $bootcamp_building_engagements = $this->Db_model->e_fetch(array(
                        'e_initiator_u_id' => $user['u_id'],
                        'e_b_id' => $ib['b_id'],
                        '(e_type_id IN (15,17,37,18,14,16,13,20,21,23,22,19,34,35,39,36,38,43,44))' => null,
                    ));

                    echo '<div>'.($counter+1).') <a href="/console/'.$ib['b_id'].'">'.$ib['c_objective'].'</a> '.( isset($bootcamp_building_engagements[0]) ? time_format($bootcamp_building_engagements[0]['e_timestamp'],1) : '---' ).'/'.( count($bootcamp_building_engagements)>=100 ? '100+' : count($bootcamp_building_engagements) ).'</div>';
                }
            } else {
                echo '---';
            }
        echo '</td>';
        echo '<td>'.time_format($user['u_timestamp'],1).'</td>';
        echo '<td>'.( isset($messages[0]) && strlen($user['u_fb_id'])>4 ? '<a href="https://www.facebook.com/menchbot/inbox" target="_blank">'.( $messages[0]['e_type_id']==6 ? '<b style="color:#FF0000">Received</b>' : 'Sent' ).'</a> on' : '<a href="'.messenger_activation_url('381488558920384',$user['u_id']).'" style="color:#CCC;">Activation URL</a>' ).'</td>';
        echo '<td>'.( isset($messages[0]) && strlen($user['u_fb_id'])>4 ? time_format($messages[0]['e_timestamp'],1) : '' ).'</td>';
        echo '<td>'.( isset($read_message[0]) ? '<i class="fa fa-eye" aria-hidden="true"></i> '.time_format($read_message[0]['e_timestamp'],1) : '' ).'</td>';
        echo '<td>'.( isset($messages[0]) && strlen($user['u_fb_id'])>4 ? '<b>('.(count($messages)>=100 ? '100+' : count($messages)).')</b>' : '' ).'</td>';
        echo '<td>'.$user['u_timezone'].'</td>';
        echo '<td>';
            if(isset($_GET['pid']) && strlen($user['u_fb_id'])>4 && $user['u_status']>=2){
                //Lets check their history:
                $sent_messages = $this->Db_model->e_fetch(array(
                    'e_type_id' => 7,
                    'e_recipient_u_id' => $user['u_id'],
                    'e_c_id' => intval($_GET['pid']),
                ),1);
                if(count($sent_messages)>0){
                    //Already sent!
                    echo '<i class="fa fa-check-circle" aria-hidden="true"></i>';
                } else {
                    //Show send button:
                    echo '<span id="dispatch_message_'.$user['u_id'].'"><a href="javascript:dispatch_message('.$user['u_id'].','.$_GET['pid'].')">Send</a></span>';
                }
            }
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    
}
?>