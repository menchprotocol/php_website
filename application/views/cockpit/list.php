
<ul class="nav nav-pills nav-pills-primary">
    <li class="<?= ( $object_name=='engagements' ? 'active' : '') ?>"><a href="/cockpit/browse/engagements"><i class="fa fa-eye" aria-hidden="true"></i> Engagements</a></li>
    <li class="<?= ( $object_name=='bootcamps' ? 'active' : '') ?>"><a href="/cockpit/browse/bootcamps"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamps</a></li>
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
        'e_type_id' => 'Choose Engagement Type',
        'e_initiator_u_id' => 'Initiator User ID',
        'e_recipient_u_id' => 'Recipient User ID',
        'e_b_id' => 'Bootcamp ID',
        'e_r_id' => 'Class ID',
        'e_c_id' => 'Intent ID',
        'e_fp_id' => 'FB Page ID',
    );

    $match_columns = array();
    foreach($engagement_filters as $key=>$value){
        if(isset($_GET[$key])){
            if(substr_count($_GET[$key],',')>0){
                //This is multiple IDs:
                $match_columns[$key.' IN ('.$_GET[$key].')'] = null;
            } elseif(intval($_GET[$key])>0) {
                $match_columns[$key] = intval($_GET[$key]);
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

} elseif($object_name=='bootcamps'){

    //A function to echo the Bootcamp rows:
    function echo_row($bootcamp,$counter){
        echo '<tr>';
        echo '<td>'.$counter.'</td>';
        echo '<td>'.$bootcamp['b_id'].'</td>';
        echo '<td>'.status_bible('b',$bootcamp['b_status'],1,'right').'</td>';
        echo '<td><a href="/console/'.$bootcamp['b_id'].'">'.$bootcamp['c_objective'].'</a></td>';
        echo '<td><a href="https://www.facebook.com/'.$bootcamp['fp_fb_id'].'">'.$bootcamp['fp_name'].'</a></td>';
        echo '<td><a href="/cockpit/browse/engagements?e_initiator_u_id='.$bootcamp['leaders'][0]['u_id'].'" title="User ID '.$bootcamp['leaders'][0]['u_id'].'">'.$bootcamp['leaders'][0]['u_fname'].' '.$bootcamp['leaders'][0]['u_lname'].'</a></td>';

        echo '<td>';
        if($bootcamp['student_funnel'][0]>0 || $bootcamp['student_funnel'][2]>0 || $bootcamp['student_funnel'][4]>0 || $bootcamp['student_funnel'][-1]>0){
            echo '<span data-toggle="tooltip" title="Started -> Completed -> Admitted (Rejected)">';
            echo $bootcamp['student_funnel'][0].' &raquo; '.$bootcamp['student_funnel'][2].' &raquo; <b>'.$bootcamp['student_funnel'][4].'</b> ('.$bootcamp['student_funnel'][-1].')';
            echo '</span>';
        }
        echo '</td>';

        echo '<td>'. ( count($bootcamp['engagements'])>0 ? '<a href="/cockpit/browse/engagements?e_b_id='.$bootcamp['b_id'].'">'.( count($bootcamp['engagements'])>=1000 ? '1000+' : count($bootcamp['engagements']) ).'</a> ('.time_format($bootcamp['engagements'][0]['e_timestamp'],1).')' : 'Never' ) .'</td>';
        echo '</tr>';
    }
    
    //User Bootcamps:    
    $bootcamps = $this->Db_model->b_fetch(array(
        'b.b_status >=' => 0,
    ),array('c','fp'),'b_status');

    //Did we find any?
    $meaningful_bootcamp_engagements = $this->config->item('meaningful_bootcamp_engagements');


    foreach($bootcamps as $key=>$mb){
        //Fetch Leader:
        $bootcamps[$key]['leaders'] = $this->Db_model->ba_fetch(array(
            'ba.ba_b_id' => $mb['b_id'],
            'ba.ba_status' => 3,
        ));

        //Fetch last activity:
        $bootcamps[$key]['engagements'] = $this->Db_model->e_fetch(array(
            'e_b_id' => $mb['b_id'],
            '(e_type_id IN ('.join(',',$meaningful_bootcamp_engagements).'))' => null,
        ),1000);

        $bootcamps[$key]['student_funnel'] = array(
            0 => count($this->Db_model->ru_fetch(array(
                'r.r_b_id'	       => $mb['b_id'],
                'ru.ru_status'     => 0,
            ))),
            2 => count($this->Db_model->ru_fetch(array(
                'r.r_b_id'	       => $mb['b_id'],
                'ru.ru_status'     => 2,
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
    
    $bootcamp_groups = array(
        'mench_team' => array(),
        'instructor' => array(),
    );
    $counter = 0;
    
    foreach($bootcamps as $bootcamp){
        $is_mench_team = false;
        foreach($bootcamp['leaders'] as $key=>$instructor){
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
    foreach($bootcamp_groups['mench_team'] as $bootcamp){
        $counter++;
        echo_row($bootcamp,$counter);
    }
    ?>
    </tbody>
    </table>
    <?php

} elseif($object_name=='classes'){


    $classes = $this->Db_model->r_fetch(array(
        'r_status IN (1,2,3)' => null, //Running Classes
    ),null,'ASC');

    //Include email model for certain communications:
    $start_times = $this->config->item('start_times');
    ?>
    <table class="table table-condensed table-striped left-table" style="font-size:0.8em; width:100%;">
    <thead>
    <tr>
        <th style="width:40px;">#</th>
        <th>&nbsp;</th>
        <th>Bootcamp</th>
        <th>Lead Instructor</th>
        <th>Class Start Time</th>
        <th>&nbsp;</th>
        <th>Class End Time</th>
        <th>Elapsed</th>
        <th>Progress</th>
        <th>Tuition</th>
        <th>Student Stats</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($classes as $key=>$class) {

        //Fetch Full Bootcamp:
        $bootcamps = $this->Db_model->b_fetch(array(
            'b.b_id' => $class['r_b_id'],
        ), array('c','fp'));

        if($class['r_status']>=2){
            //Fetch Bootcamp from Action Plan Copy:
            $bootcamps = fetch_action_plan_copy($class['r_b_id'],$class['r_id'],$bootcamps);
            $class = $bootcamps[0]['this_class'];
        }

        //Fetch Leader:
        $leaders = $this->Db_model->ba_fetch(array(
            'ba.ba_b_id' => $class['r_b_id'],
            'ba.ba_status' => 3,
        ));

        echo '<tr>';
        echo '<td>'.($key+1).'</td>';
        echo '<td>'.( $bootcamps[0]['b_fp_id']>0 ? '<a href="https://www.facebook.com/'.$bootcamps[0]['fp_fb_id'].'" target="_blank" data-toggle="tooltip" title="Bootcamp Facebook Page is '.$bootcamps[0]['fp_name'].'" data-placement="right" ><i class="fa fa-plug"></i></a>' : '').'</td>';
        echo '<td><a href="/console/'.$class['r_b_id'].'">'.$bootcamps[0]['c_objective'].'</a></td>';
        echo '<td>'.$leaders[0]['u_fname'].' '.$leaders[0]['u_lname'].'</a></td>';
        echo '<td><a href="/console/'.$class['r_b_id'].'/classes/'.$class['r_id'].'">'.time_format(strtotime($class['r_start_date'])+($class['r_start_time_mins']*60),0).'</a></td>';
        echo '<td>'.status_bible('r',$class['r_status'],true).'</td>';
        echo '<td>';
        if($class['r_cache__end_time']){
            echo time_format($class['r_cache__end_time'],0);
        }
        if($bootcamps[0]['b_status']<2){
            echo ' <i class="fa fa-exclamation-triangle" data-toggle="tooltip" title="Bootcamp Status is Drafting which will prevent the Class from being Launched" data-placement="bottom" style="color:#FF0000;"></i>';
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
        echo '<td>'.echo_price($class['r_usd_price'],false).'</td>';
        echo '<td>';

        if($class['r_status']>=2){
            //Show Graduation Funnel:
            $admitted = count($this->Db_model->ru_fetch(array(
                'r.r_id'	       => $class['r_id'],
                'ru.ru_status >='   => 4,
                'u.u_fb_id >'      => 0, //Activated is what really counts...
            )));
            $completed = count($this->Db_model->ru_fetch(array(
                'r.r_id'	       => $class['r_id'],
                'ru.ru_cache__current_milestone >'   => $class['r__total_milestones'],
                'u.u_fb_id >'      => 0, //Activated is what really counts...
            )));
            echo '<span data-toggle="tooltip" title="Completion Rate (Total Admitted Students who Activated Messenger)">';
            echo '<b>'.($admitted>0 ? round($completed/$admitted*100) : '0').'%</b> Completed ('.$admitted.')';
            echo '</span>';
        } else {
            //Show Admission Funnel:
            echo '<span data-toggle="tooltip" title="Started Application -> Completed Application -> Admitted/Max Seats (Rejected)">';
            $student_funnel = array(
                0 => count($this->Db_model->ru_fetch(array(
                    'r.r_id'	       => $class['r_id'],
                    'ru.ru_status'     => 0,
                ))),
                2 => count($this->Db_model->ru_fetch(array(
                    'r.r_id'	       => $class['r_id'],
                    'ru.ru_status'     => 2,
                ))),
                4 => count($this->Db_model->ru_fetch(array(
                    'r.r_id'	       => $class['r_id'],
                    'ru.ru_status'     => 4,
                ))),
                -1 => count($this->Db_model->ru_fetch(array(
                    'r.r_id'	       => $class['r_id'],
                    'ru.ru_status <'   => 0, //Anyone rejected/withdrew/dispelled
                ))),
            );
            echo $student_funnel[0].' &raquo; '.$student_funnel[2].' &raquo; <b style="color:'.( $student_funnel[4]>=$class['r_min_students'] ? '#00CC00' : '#FF0000' ).';" title="Minimum is '.$class['r_min_students'].'">'.$student_funnel[4].'</b>/'.$class['r_max_students'].' ('.$student_funnel[-1].')';
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

        //Fetch messages if activated MenchBot:
        unset($messages);
        unset($read_message);
        if($user['u_fb_id']>0){
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
        echo '<td>'.($key+1).'</td>';
        echo '<td>'.$user['u_id'].'</td>';
        echo '<td>'.status_bible('u',$user['u_status'],1,'right').'</td>';
        echo '<td><a href="/cockpit/browse/engagements?e_initiator_u_id='.$user['u_id'].'" title="View All Engagements">'.$user['u_fname'].' '.$user['u_lname'].'</a>'.( $user['u_unsubscribe_fb_id']>0 ? ' <i class="fa fa-exclamation-triangle" data-toggle="tooltip" title="User has Unsubscribed" data-placement="bottom" style="color:#FF0000;"></i>' : '' ).'</td>';


        echo '<td>';
            //Display Bootcamps:
            if(count($instructor_bootcamps)>0){
                $meaningful_bootcamp_engagements = $this->config->item('meaningful_bootcamp_engagements');

                foreach ($instructor_bootcamps as $counter=>$ib){
                    //Fetch last activity:
                    $bootcamp_building_engagements = $this->Db_model->e_fetch(array(
                        'e_initiator_u_id' => $user['u_id'],
                        'e_b_id' => $ib['b_id'],
                        '(e_type_id IN ('.join(',',$meaningful_bootcamp_engagements).'))' => null,
                    ));

                    echo '<div>'.($counter+1).') <a href="/console/'.$ib['b_id'].'">'.$ib['c_objective'].'</a> '.( isset($bootcamp_building_engagements[0]) ? time_format($bootcamp_building_engagements[0]['e_timestamp'],1) : '---' ).'/'.( count($bootcamp_building_engagements)>=100 ? '100+' : count($bootcamp_building_engagements) ).'</div>';
                }
            } else {
                echo '---';
            }
        echo '</td>';
        echo '<td>'.time_format($user['u_timestamp'],1).'</td>';
        echo '<td>'.( isset($messages[0]) && $user['u_fb_id']>0 ? '<a href="https://www.facebook.com/menchbot/inbox" target="_blank">'.( $messages[0]['e_type_id']==6 ? '<b style="color:#FF0000">Received</b>' : 'Sent' ).'</a> on' : '<a href="'.messenger_activation_url('381488558920384',$user['u_id']).'" style="color:#CCC;">Activation URL</a>' ).'</td>';
        echo '<td>'.( isset($messages[0]) && $user['u_fb_id']>0 ? time_format($messages[0]['e_timestamp'],1) : '' ).'</td>';
        echo '<td>'.( isset($read_message[0]) ? '<i class="fa fa-eye" aria-hidden="true"></i> '.time_format($read_message[0]['e_timestamp'],1) : '' ).'</td>';
        echo '<td>'.( isset($messages[0]) && $user['u_fb_id']>0 ? '<b>('.(count($messages)>=100 ? '100+' : count($messages)).')</b>' : '' ).'</td>';
        echo '<td>'.$user['u_timezone'].'</td>';
        echo '<td>';

            if(strlen($user['u_email'])>0){
                echo '<a href="mailto:'.$user['u_email'].'" title="Email '.$user['u_email'].'"><i class="fa fa-envelope" aria-hidden="true"></i></a>&nbsp;';
            }

            if(isset($_GET['pid']) && $user['u_fb_id']>0){
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