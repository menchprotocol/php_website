<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjust extends CI_Controller {

    //This controller is for functions that do mass adjustments on the DB

    function __construct() {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function b_missing_admins(){
        $bs = $this->Db_model->b_fetch(array(),array('c'));

        foreach($bs as $b){

        }
        echo count($bs);
    }

    function i($limit=10,$smallest_i_id=0){

        echo '<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="/img/bp_16.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Links Analysis</title>
	<meta content=\'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\' name=\'viewport\' />
<script src="/js/console/jquery-3.1.0.min.js" type="text/javascript"></script>
</head>
<body style="padding:20px;">';


        $content = $this->Db_model->i_fetch(array(
            'i_id >' => $smallest_i_id, //Published in any form
            'i_status >' => 0, //Published in any form
            'i_media_type' => 'text',
            'LENGTH(i_url)>0' => null, //Entire Bootcamp Action Plan
        ), $limit, array(
            'i_id' => 'ASC',
        ));

        foreach($content as $key=>$i){

            $curl = curl_html($i['i_url'],true);
            $url_parts = parse_url(( $curl['last_url']==$i['i_url'] ? $i['i_url'] : $curl['last_url'] ));
            $i_domain = strtolower(str_replace('www.','',$url_parts['host']));
            $i_url_title = one_two_explode('>','',one_two_explode('<title','</title',$curl['body']));

            //Update Message:
            $this->Db_model->i_update( $i['i_id'] , array(
                'i_http_code' => $curl['httpcode'],
                'i_final_url' => ( $curl['last_url']==$i['i_url'] ? null : $curl['last_url'] ),
                'i_domain' => $i_domain,
                'i_url_title' => $i_url_title,
            ));

            echo '<div>#'.($key+1).' ID '.$i['i_id'].' <a href="'.$i['i_url'].'" target="_blank">'.$i['i_url'].'</a>'.( $curl['last_url']==$i['i_url'] ? '' : ' ====> <a href="'.$curl['last_url'].'" target="_blank">'.$curl['last_url'].'</a>').' ('.$i_domain.') ('.$i_url_title.')</div>';
            echo '<div>Code ['.$curl['httpcode'].'] <a href="javascript:$(\'#i'.$i['i_id'].'\').toggle();">Toggle Body</a> '.$curl['header'].'</div>';
            echo '<div id="i'.$i['i_id'].'" style="display:none; border:1px solid #000; padding:10px; margin:10px; background-color:#EFEFEF; font-size:10px; font-family:monospace;">'.htmlentities($curl['body']).'</div>';
            echo '<br /><hr /><br />';

        }

        echo '</body></html>';

    }


    function resync_class_actionplans_and_message_instructors(){

        //First delete old caches:
        $this->db->query("DELETE FROM v5_engagements WHERE e_inbound_c_id=70");

        //Now re-create for active & completed Classes:
        $classes = $this->Db_model->r_fetch(array(
            'r.r_status >=' => 2,
        ));

        foreach($classes as $r){
            $this->Db_model->snapshot_action_plan($r['r_b_id'],$r['r_id']);
        }

        echo 'Updated for '.count($classes).' Classes';

    }


    function delete_users(){
        $users = $this->Db_model->u_fetch(array(
            'u_status <' => 0,
        ));

        $delete = array(
            'error' => array(),
            'success' => array(),
        );
        foreach($users as $u){
            $del = $this->Db_model->u_delete($u['u_id']);
            array_push($delete[( $del['status'] ? 'success' : 'error' )], $del);
        }

        echo_json($delete);
    }

    function ru_b_id(){

        $admissions = $this->Db_model->ru_fetch(array(
            'ru_r_id > 0' 	    => null,
            'ru_b_id' 	        => 0,
        ));

        $counter = 0;
        foreach($admissions as $admission){

            //Fetch Bootcamp ID:
            $classes = $this->Db_model->r_fetch(array(
                'r_id' => $admission['ru_r_id'],
            ));

            if(count($classes)==1){
                $this->Db_model->ru_update( $admission['ru_id'] , array(
                    'ru_b_id' => $classes[0]['r_b_id'],
                ));
                $counter++;
            }
        }

        echo $counter;
    }

    function mass_enroll($from_r_id,$to_b_id,$to_r_id){

        //Admits all Free students from $from_r_id to $to_r_id
        $admissions = $this->Db_model->ru_fetch(array(
            'ru_r_id' 	        => $from_r_id,
            'ru.ru_status >='   => 4, //Initiated or higher as long as bootcamp is running!
            'ru.ru_p1_price'	=> 0, //Free DIY
        ));

        foreach($admissions as $admission){

            //Admit them to the second Class:
            $this->Db_model->ru_create(array(
                'ru_r_id' 	        => $to_r_id,
                'ru_outbound_u_id' 	        => $admission['u_id'],
                'ru_status'         => 4,
                'ru_fp_id'          => $admission['ru_fp_id'],
                'ru_fp_psid'        => $admission['ru_fp_psid'],
            ));

            //Let them know about this Admission:
            $this->Comm_model->foundation_message(array(
                'e_outbound_u_id' => $admission['u_id'],
                'e_outbound_c_id' => 5995,
                'depth' => 0,
                'e_b_id' => $to_b_id,
                'e_r_id' => $to_r_id,
            ));
        }

        echo count($admissions).' Students moved from Class ['.$from_r_id.'] to Class ['.$to_r_id.']';

    }

    function sync_student_progress(){

        //Go through all admissions for running classes and updates the student positions in those classes:
        $classes = $this->Db_model->r_fetch(array(
            'r.r_status >=' => 2,
        ));

        $stats = array();
        foreach($classes as $class){


            //Fetch full Bootcamp/Class data for this:
            $bs = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bs[0]['this_class'];


            //Fetch all the students of these classes, and see where they are at:
            $class['students'] = $this->Db_model->ru_fetch(array(
                'ru.ru_status >='   => 4, //Initiated or higher as long as bootcamp is running!
                'ru.ru_r_id'	 => $class['r_id'],
            ));

            $stats[$class['r_id']] = 0;
            foreach($class['students'] as $admission){

                //Fetch all their submissions so far:
                $us_data = $this->Db_model->e_fetch(array(
                    'e_inbound_c_id' => 33, //Completion Report
                    'e_inbound_u_id' => $admission['u_id'], //by this Student
                    'e_r_id' => $class['r_id'], //For this Class
                    'e_replaced_e_id' => 0, //Data has not been replaced
                    'e_status !=' => -3, //Should not be rejected
                ), 1000, array(), 'e_outbound_c_id');


                //Go through and see where it breaks down:
                $found_incomplete_step = false;
                $total_hours_done = 0;
                $ru_cache__current_task = 1;
                $total_steps = 0;
                $done_steps = 0;

                //Find the Step that is after the very last Step done
                //Note that some Steps could be done, but then rejected by the instructor...
                foreach($bs[0]['c__child_intents'] as $task){
                    if($task['c_status']==1){
                        $total_steps++;
                        //Has the student done this?
                        if(!array_key_exists($task['c_id'],$us_data)){

                            if(!$found_incomplete_step){
                                //The student is not done with this Task, so here is were they're at:
                                $ru_cache__current_task = $task['cr_outbound_rank'];
                                $found_incomplete_step = true;
                            }

                        } else {

                            //Addup the total hours based on the Action Plan
                            $total_hours_done += $us_data[$task['c_id']]['e_time_estimate'];
                            $found_incomplete_step = false; //Reset this
                            $done_steps++;

                        }
                    }
                }

                //Calculate the total progress:
                $ru_cache__completion_rate = number_format(($total_hours_done/$bs[0]['c__estimated_hours']),3);

                if($done_steps==$total_steps){
                    //They have done all Steps
                    $ru_cache__current_task = ($class['r__total_tasks']+1);
                }

                //Do we need to update?
                if(!($admission['ru_cache__current_task']==$ru_cache__current_task) || !($admission['ru_cache__completion_rate']==$ru_cache__completion_rate)){

                    //Update DB:
                    $this->Db_model->ru_update( $admission['ru_id'] , array(
                        'ru_cache__completion_rate' => $ru_cache__completion_rate,
                        'ru_cache__current_task' => $ru_cache__current_task,
                    ));

                    //Increase counter:
                    $stats[$class['r_id']]++;
                }
            }
        }

        echo_json($stats);
    }

    function sync_class_completion_rates(){

        $running_classes = $this->Db_model->r_fetch(array(
            'r_status' => 3, //Only running classes
        ));

        foreach($running_classes as $class) {

            $qualified_students = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id' => $class['r_id'],
                'ru.ru_status >=' => 6,
            ));

            $completed_students = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id' => $class['r_id'],
                'ru.ru_status' => 7,
            ));

            //Update Class:
            $this->Db_model->r_update( $class['r_id'], array(
                'r_cache__completion_rate' => ( count($qualified_students)>0 ? number_format((count($completed_students) / count($qualified_students)), 3) : 0 ),
            ));
        }

        echo count($running_classes).' adjusted';
    }


}