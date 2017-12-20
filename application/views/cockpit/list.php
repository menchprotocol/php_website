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
    echo '<td><a href="/console/'.$bootcamp['b_id'].'/actionplan">'.count($bootcamp['c__child_intents']).' '.ucwords($bootcamp['b_sprint_unit']).( count($bootcamp['c__child_intents'])==1 ? '' : 's' ).'</a></td>';
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
    
    $users = $this->Db_model->u_fetch(array(
        'u_status >=' => 0,
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
                }

            });
        }
    </script>
    <table class="table table-condensed table-striped left-table" style="font-size:0.8em; width:100%;">
    <thead>
    	<tr>
    		<th style="width:40px;">ID</th>
    		<th style="width:30px;">&nbsp;</th>
    		<th>User</th>
    		<th><i class="fa fa-commenting" aria-hidden="true"></i></th>
    		<th>Joined</th>
            <th>Engagements</th>
            <th>Timezone</th>
            <th>Actions</th>
    	</tr>
    </thead>
    <tbody>
    <?php
    foreach($users as $user){
        
        //Fetch last activity:
        $engagements = $this->Db_model->e_fetch(array(
            'e_initiator_u_id' => $user['u_id'],
        ));
        
        if(strlen($user['u_fb_id'])>4){
            $messages = $this->Db_model->e_fetch(array(
                '(e_initiator_u_id='.$user['u_id'].' OR e_recipient_u_id='.$user['u_id'].')' => null,
                '(e_type_id=6 OR e_type_id=7)' => null,
            ));
        }
        
        echo '<tr>';
        echo '<td>'.$user['u_id'].'</td>';
        echo '<td>'.status_bible('u',$user['u_status'],1,'right').'</td>';
        echo '<td>'.$user['u_fname'].' '.$user['u_lname'].'</td>';
        echo '<td>'.( strlen($user['u_fb_id'])>4 ? intval(count($messages)) : messenger_activation_url('381488558920384',$user['u_id']) ).'</td>';
        echo '<td>'.time_format($user['u_timestamp'],1).'</td>';
        echo '<td><a href="/cockpit/engagements?e_recipient_u_id='.$user['u_id'].'">Recipient &raquo;</a> | <a href="/cockpit/engagements?e_initiator_u_id='.$user['u_id'].'">'.( count($engagements)>=100 ? '100+' : count($engagements) ).' Initiated &raquo;</a></td>';
        echo '<td>'.$user['u_timezone'].'</td>';
        echo '<td>'.( isset($_GET['pid']) && strlen($user['u_fb_id'])>4 ? '<span id="dispatch_message_'.$user['u_id'].'"><a href="javascript:dispatch_message('.$user['u_id'].','.$_GET['pid'].')">Send</a></span>' : '' ).'</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    
}
?>