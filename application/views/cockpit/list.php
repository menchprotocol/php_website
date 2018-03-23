
<ul class="nav nav-pills nav-pills-primary">
    <li class="<?= ( $object_name=='engagements' ? 'active' : '') ?>"><a href="/cockpit/browse/engagements"><i class="fa fa-eye" aria-hidden="true"></i> Engagements</a></li>
    <li class="<?= ( $object_name=='projects' ? 'active' : '') ?>"><a href="/cockpit/browse/projects"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamps</a></li>
    <li class="<?= ( $object_name=='classes' ? 'active' : '') ?>"><a href="/cockpit/browse/classes"><i class="fa fa-calendar" aria-hidden="true"></i> Classes</a></li>
    <li class="<?= ( $object_name=='users' ? 'active' : '') ?>"><a href="/cockpit/browse/users"><i class="fa fa-user" aria-hidden="true"></i> Users</a></li>
</ul>
<hr />

<?php

if($object_name=='engagements'){

    //Define engagement filters:
    $engagement_references = $this->config->item('engagement_references');
    $e_type_id = $this->Db_model->a_fetch();
    $engagement_filters = array(
        'e_type_id' => 'All Engagements',
        'e_id' => 'Engagement ID',
        'e_u_id' => 'User ID',
        'e_b_id' => 'Bootcamp ID',
        'e_r_id' => 'Class ID',
        'e_c_id' => 'Node ID',
        'e_fp_id' => 'FB Page ID',
    );

    $match_columns = array();
    foreach($engagement_filters as $key=>$value){
        if(isset($_GET[$key])){
            if($key=='e_u_id'){
                //We need to look for both inititors and recipients:
                if(substr_count($_GET[$key],',')>0){
                    //This is multiple IDs:
                    $match_columns['(e_recipient_u_id IN ('.$_GET[$key].') OR e_initiator_u_id IN ('.$_GET[$key].'))'] = null;
                } elseif(intval($_GET[$key])>0) {
                    $match_columns['(e_recipient_u_id = '.$_GET[$key].' OR e_initiator_u_id = '.$_GET[$key].')'] = null;
                }
            } else {
                if(substr_count($_GET[$key],',')>0){
                    //This is multiple IDs:
                    $match_columns[$key.' IN ('.$_GET[$key].')'] = null;
                } elseif(intval($_GET[$key])>0) {
                    $match_columns[$key] = intval($_GET[$key]);
                }
            }
        }
    }

    //Fetch engagements with possible filters:
    $engagements = $this->Db_model->e_fetch($match_columns,100);

    ?>

    <style>
        table, tr, td, th { text-align:left !important; font-size:14px; cursor:default !important; line-height:120% !important; }
        th { font-weight:bold !important; }
        td { padding:5px 0 !important; }
    </style>

    <?php
//Display filters:
    echo '<form action="" method="GET">';
    echo '<table class="table table-condensed"><tr>';
    foreach($engagement_filters as $key=>$value){
        echo '<td><div style="padding-right:5px;">';
        if(isset(${$key})){ //We have a list to show:
            echo '<select name="'.$key.'" class="border" style="width:160px;">';
            echo '<option value="0">'.$value.'</option>';
            foreach(${$key} as $key2=>$value2){
                echo '<option value="'.$key2.'" '.((isset($_GET[$key]) && intval($_GET[$key])==$key2)?'selected="selected"':'').'>'.$value2.'</option>';
            }
            echo '</select>';
        } else {
            //show text input
            echo '<input type="text" name="'.$key.'" placeholder="'.$value.'" value="'.((isset($_GET[$key]))?$_GET[$key]:'').'" class="form-control border">';
        }
        echo '</div></td>';
    }
    echo '<td><input type="submit" class="btn btn-sm btn-primary" value="Apply" /></td>';
    echo '</tr></table>';
    echo '</form>';
    ?>

    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th style="width:120px;">Time</th>
            <th style="width:120px;">Action</th>
            <th><div style="padding-left:10px;">Message</div></th>
            <th style="width:300px;">References</th>
            <th style="width:30px; text-align:center !important;">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php
        //Fetch objects
        foreach($engagements as $e){
            echo '<tr>';
            echo '<td><span aria-hidden="true" data-toggle="tooltip" data-placement="right" title="'.date("Y-m-d H:i:s",strtotime($e['e_timestamp'])).' Engagement #'.$e['e_id'].'" class="underdot">'.time_format($e['e_timestamp']).'</span></td>';
            echo '<td><span data-toggle="tooltip" title="'.$e['a_desc'].' (Type #'.$e['a_id'].')" aria-hidden="true" data-placement="right" class="underdot">'.$e['a_name'].'</span></td>';

            //Do we have a message?
            if(strlen($e['e_message'])>0){
                $e['e_message'] = format_e_message($e['e_message']);
            } elseif($e['e_i_id']>0){
                //Fetch message conent:
                $matching_messages = $this->Db_model->i_fetch(array(
                    'i_id' => $e['e_i_id'],
                ));
                if(count($matching_messages)>0){
                    $e['e_message'] = echo_i($matching_messages[0]);
                }
            }

            echo '<td><div style="max-width:300px; padding-left:10px;">'.$e['e_message'].( in_array($e['e_cron_job'],array(0,-2)) ? '<div style="color:#008000;"><i class="fa fa-spinner fa-spin fa-3x fa-fw" style="font-size:14px;"></i> Processing...</div>' : '' ).'</div></td>';
            echo '<td>';

            //Lets go through all references to see what is there:
            foreach($engagement_references as $engagement_field=>$er){
                if(intval($e[$engagement_field])>0){
                    //Yes we have a value here:
                    echo '<div>'.$er['name'].': '.object_link($er['object_code'], $e[$engagement_field], $e['e_b_id']).'</div>';
                } elseif(intval($e[$engagement_field])>0) {
                    echo '<div>'.$er['name'].': #'.$e[$engagement_field].'</div>';
                }
            }

            echo '</td>';
            echo '<td style="text-align:center !important;">'.( $e['e_has_blob']=='t' ? '<a href="/api_v1/blob/'.$e['e_id'].'" target="_blank" data-toggle="tooltip" title="Analyze Engagement JSON Blob in a new window" aria-hidden="true" data-placement="left"><i class="fa fa-search-plus" id="icon_'.$e['e_id'].'" aria-hidden="true"></i></a>' : '' ).'</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
    <?php

} elseif($object_name=='projects'){


    //A function to echo the Bootcamp rows:
    function echo_row($b,$counter){
        echo '<tr>';
        echo '<td>'.$counter.'</td>';
        echo '<td>'.$b['b_id'].'</td>';
        echo '<td>'.status_bible('b',$b['b_status'],1,'right').'</td>';
        echo '<td>'.( $b['b_old_format'] ? '<i class="fa fa-exclamation-triangle" title="OLD FORMAT" aria-hidden="true"></i> ' : '' ).'<a href="/console/'.$b['b_id'].'">'.$b['c_objective'].'</a></td>';
        echo '<td><a href="https://www.facebook.com/'.$b['fp_fb_id'].'">'.$b['fp_name'].'</a></td>';
        echo '<td><a href="/cockpit/browse/engagements?e_u_id='.$b['leaders'][0]['u_id'].'" title="User ID '.$b['leaders'][0]['u_id'].'">'.$b['leaders'][0]['u_fname'].' '.$b['leaders'][0]['u_lname'].'</a></td>';

        echo '<td>';
        if($b['student_funnel'][0]>0 || $b['student_funnel'][4]>0 || $b['student_funnel'][-1]>0){
            echo '<span data-toggle="tooltip" title="Started -> Completed -> Admitted (Rejected)">';
            echo $b['student_funnel'][0].' &raquo; <b>'.$b['student_funnel'][4].'</b> ('.$b['student_funnel'][-1].')';
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
            '(e_type_id IN ('.join(',',$meaningful_b_engagements).'))' => null,
        ),1000);

        $bs[$key]['student_funnel'] = array(
            0 => count($this->Db_model->ru_fetch(array(
                'r.r_b_id'	       => $mb['b_id'],
                'ru.ru_status'     => 0,
            ))),
            4 => count($this->Db_model->ru_fetch(array(
                'r.r_b_id'	       => $mb['b_id'],
                'ru.ru_status'     => 4,
            ))),
            -1 => count($this->Db_model->ru_fetch(array(
                'r.r_b_id'	       => $mb['b_id'],
                'ru.ru_status <'   => 0, //Anyone rejected/withdrew/dispelled
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
            <th><i class="fa fa-plug"></i> Facebook Page</th>
    		<th>Lead Instructor</th>
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
            if($instructor['u_status']>=3){
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
            <th><i class="fa fa-plug"></i> Facebook Page</th>
            <th>Lead Instructor</th>
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
    <?php

} elseif($object_name=='classes'){

    $classes = $this->Db_model->r_fetch(array(
        'r_status >=' => 1, //Running Classes
    ),null,'ASC');



    ?>
    <table class="table table-condensed table-striped left-table" style="font-size:0.8em; width:100%;">
    <thead>
    <tr>
        <th style="width:40px;">#</th>
        <th>Bootcamp</th>
        <th>Lead Instructor</th>
        <th>Class Start Time</th>
        <th>Class End Time</th>
        <th>Elapsed</th>
        <th>Progress</th>
        <th>Price</th>
        <th colspan="4">Performance Stats</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($classes as $key=>$class) {

        //Fetch Full Bootcamp:
        $bs = $this->Db_model->b_fetch(array(
            'b.b_id' => $class['r_b_id'],
        ), array('c','fp'));

        if($class['r_status']>=2){
            //Fetch Bootcamp from Action Plan Copy:
            $bs = fetch_action_plan_copy($class['r_b_id'],$class['r_id'],$bs,array('b_fp_id'));
            $class = $bs[0]['this_class'];
        } else {
            if($class['r__current_admissions']==0){
                //This class has no stats, skip it:
                continue;
            }
        }



        //Fetch Leader:
        $leaders = $this->Db_model->ba_fetch(array(
            'ba.ba_b_id' => $class['r_b_id'],
            'ba.ba_status' => 3,
        ));

        echo '<tr>';
        echo '<td>'.($key+1).'</td>';

        echo '<td><a href="/console/'.$class['r_b_id'].'">'.$bs[0]['c_objective'].'</a></td>';
        echo '<td><a href="/cockpit/browse/engagements?e_u_id='.$leaders[0]['u_id'].'">'.$leaders[0]['u_fname'].' '.$leaders[0]['u_lname'].'</a></td>';
        echo '<td><a href="/console/'.$class['r_b_id'].'/classes/'.$class['r_id'].'">'.time_format(strtotime($class['r_start_date']),0).'</a></td>';
        echo '<td>';
        if($class['r_cache__end_time']){
            echo time_format($class['r_cache__end_time'],0);
        }
        echo '</td>';
        echo '<td><span data-toggle="tooltip" title="% of Class Elapsed Time">';
        if($class['r_status']==3){
            echo '100%';
        } elseif($class['r_status']==2){
            echo round((time()-$class['r__class_start_time'])/($class['r__class_end_time']-$class['r__class_start_time'])*100).'%';
        }
        echo '</span></td>';
        echo '<td>';
        if($class['r_status']>=2){
            //Query average completion rate for Activated students:
            $average_completion = $this->Db_model->fetch_avg_class_completion($class['r_id']);
            echo '<span data-toggle="tooltip" title="Average completion rate of all class students combined">'.round($average_completion[0]['cr']*100).'%</span>';
        }
        echo '</td>';
        echo '<td>'.echo_price($bs[0]).'</td>';


        echo '<td>'.( $bs[0]['b_fp_id']>0 ? '<a href="https://www.facebook.com/'.$bs[0]['fp_fb_id'].'" target="_blank" data-toggle="tooltip" title="Bootcamp Facebook Page is '.$bs[0]['fp_name'].'" data-placement="right" ><i class="fa fa-plug"></i></a>' : '<i class="fa fa-exclamation-triangle redalert" data-toggle="tooltip" title="Bootcamp not connected to a Facebook Page yet" data-placement="right"></i>').'</td>';



        echo '<td class="'.( $bs[0]['b_status']<2 ? 'redalert' : '' ).'">';
        if($class['r_status']<2){
            echo status_bible('b',$bs[0]['b_status'],1,'right');
        }
        echo '</td>';


        echo '<td>'.status_bible('r',$class['r_status'],true).'</td>';
        echo '<td>';

        if($class['r_status']>=2){

            //Show Graduation Funnel:
            $completed = count($this->Db_model->ru_fetch(array(
                'r.r_id'	                        => $class['r_id'],
                'ru.ru_cache__current_task >'  => $class['r__total_tasks'],
            )));

            echo '<span data-toggle="tooltip" title="Completion Rate (Total Admitted Students who Activated Messenger)">';
            echo '<b>'.($class['r__current_admissions']>0 ? round($completed/$class['r__current_admissions']*100) : '0').'%</b> Completed ('.$class['r__current_admissions'].')';
            echo '</span>';

        } else {

            //Show Funnel:
           $pending_completion = count($this->Db_model->ru_fetch(array(
                'ru_r_id' => $class['r_id'],
                'ru_status' => 0,
            )));

            $guided_admissions = count($this->Db_model->ru_fetch(array(
                'ru_r_id' => $class['r_id'],
                'ru_status >=' => 4,
                'ru_package_num >=' => 2, //2 or 3
            )));

            echo '<span data-toggle="tooltip" title="Pending &raquo; Joined Student &raquo; Guided-Seats/Max-Guided">';
            echo $pending_completion.' &raquo; '.$class['r__current_admissions'].'  &raquo; <b>'.$guided_admissions.'</b>/'.$bs[0]['b_p2_max_seats'];
            echo '</span>';

        }
        echo '</td>';

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

} elseif($object_name=='users'){


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
        function dispatch_message(u_id,pid,depth){
            $('#dispatch_message_'+u_id).html('Sending...').hide().fadeIn();
            //Save the rest of the content:
            $.post("/api_v1/dispatch_message", {
                u_id:u_id,
                depth:depth,
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
                '(e_initiator_u_id='.$user['u_id'].' OR e_recipient_u_id='.$user['u_id'].')' => null,
                '(e_type_id IN (6,7))' => null,
            ));
            $read_message = $this->Db_model->e_fetch(array(
                '(e_initiator_u_id='.$user['u_id'].' OR e_recipient_u_id='.$user['u_id'].')' => null,
                'e_type_id' => 1,
            ),1);
        }


        //Fetch Bootcamps:
        $instructor_projects = $this->Db_model->ba_fetch(array(
            'ba.ba_u_id' => $user['u_id'],
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
            if(count($instructor_projects)>0){
                $meaningful_b_engagements = $this->config->item('meaningful_b_engagements');

                foreach ($instructor_projects as $counter=>$ib){
                    //Fetch last activity:
                    $b_building_engagements = $this->Db_model->e_fetch(array(
                        'e_initiator_u_id' => $user['u_id'],
                        'e_b_id' => $ib['b_id'],
                        '(e_type_id IN ('.join(',',$meaningful_b_engagements).'))' => null,
                    ));

                    echo '<div>'.($counter+1).') <a href="/console/'.$ib['b_id'].'">'.$ib['c_objective'].'</a> '.( isset($b_building_engagements[0]) ? time_format($b_building_engagements[0]['e_timestamp'],1) : '---' ).'/'.( count($b_building_engagements)>=100 ? '100+' : count($b_building_engagements) ).'</div>';
                }
            } else {
                echo '---';
            }
        echo '</td>';
        echo '<td>'.time_format($user['u_timestamp'],1).'</td>';
        echo '<td>'.( isset($messages[0]) && $user['u_cache__fp_psid']>0 ? ( $messages[0]['e_type_id']==6 ? '<b style="color:#FF0000">Received</b>' : 'Sent' ).' on' : 'Not Activated' ).'</td>';
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
                    'e_type_id' => 7,
                    'e_recipient_u_id' => $user['u_id'],
                    'e_c_id' => intval($_GET['pid']),
                ),1);
                if(count($sent_messages)>0){
                    //Already sent!
                    echo '<i class="fa fa-check-circle" aria-hidden="true"></i> ';
                }

                //Show send button:
                echo '&nbsp;<span id="dispatch_message_'.$user['u_id'].'"><a href="javascript:dispatch_message('.$user['u_id'].','.$_GET['pid'].','.( isset($_GET['depth']) ? intval($_GET['depth']) : 0 ).')">'.(count($sent_messages)>0 ? 'Resend' : 'Send').'</a></span>';
            }
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    
}
?>