<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjust extends CI_Controller {

    //This controller is for functions that do mass adjustments on the DB

    function __construct() {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function duplicate_fb_users(){

        //Fetch all users:
        $users = $this->Db_model->u_fetch(array(
            'u_fb_id >' => 0,
        ));

        foreach($users as $user){

            //See if we have other users with the same FB id:
            $duplicate_users = $this->Db_model->u_fetch(array(
                'u_id !=' => $user['u_id'],
                'u_fb_id' => $user['u_fb_id'],
            ));

            if(count($duplicate_users)>0){
                //See whats happening with their Admissions:
                echo 'FB Duplicate '.$user['u_id'].') '.$user['u_fname'].' '.$user['u_lname'].': '.count($duplicate_users).'<br />';
            }

            //See if we have other users with the same FB id:
            $duplicate_users2 = $this->Db_model->u_fetch(array(
                'u_id !=' => $user['u_id'],
                'u_fname' => $user['u_fname'],
                'u_lname' => $user['u_lname'],
            ));

            if(count($duplicate_users2)>0){
                //See whats happening with their Admissions:
                echo 'Name Duplicate '.$user['u_id'].') '.$user['u_fname'].' '.$user['u_lname'].': '.count($duplicate_users2).'<br />';
            }


        }
    }

    function clone_bootcamp($b_id1,$b_id2_c_id){
        //Create a replicate of a Bootcamp:
        $bootcamps = $this->Db_model->remix_bootcamps(array(
            'b.b_id' => $b_id1,
        ));
        if(count($bootcamps)<1){
            return false;
        }

        //Start with Milestone & Tasks & Messages:
        foreach($bootcamps[0]['c__child_intents'] as $milestone){

            //Create intent:
            $new_intent = $this->Db_model->c_create(array(
                'c_creator_id' => 1,
                'c_objective' => trim($milestone['c_objective']),
                'c_time_estimate' => '0',
            ));

            //Create Link:
            $relation = $this->Db_model->cr_create(array(
                'cr_creator_id' => 1,
                'cr_inbound_id'  => $b_id2_c_id,
                'cr_outbound_id' => $new_intent['c_id'],
                'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
                        'cr_status >=' => 1,
                        'c_status >=' => 1,
                        'cr_inbound_id' => $b_id2_c_id,
                    )),
            ));

            foreach($milestone['c__child_intents'] as $task){

                //Create intent:
                $new_intent2 = $this->Db_model->c_create(array(
                    'c_creator_id' => 1,
                    'c_objective' => trim($task['c_objective']),
                    'c_time_estimate' => '0.05', //3 min default task
                ));

                //Create Link:
                $relation = $this->Db_model->cr_create(array(
                    'cr_creator_id' => 1,
                    'cr_inbound_id'  => $bootcamps[0]['b_c_id'],
                    'cr_outbound_id' => $new_intent2['c_id'],
                    'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
                            'cr_status >=' => 1,
                            'c_status >=' => 1,
                            'cr_inbound_id' => $bootcamps[0]['b_c_id'],
                        )),
                ));

            }

        }
    }


    function sync_student_progress(){

        //Go through all admissions for running classes and updates the student positions in those classes:
        $classes = $this->Db_model->r_fetch(array(
            'r.r_status >=' => 2,
        ));

        $stats = array();
        foreach($classes as $class){


            //Fetch full Bootcamp/Class data for this:
            $bootcamps = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bootcamps[0]['this_class'];


            //Fetch all the students of these classes, and see where they are at:
            $class['students'] = $this->Db_model->ru_fetch(array(
                'ru.ru_status >='   => 4, //Initiated or higher as long as bootcamp is running!
                'ru.ru_r_id'	 => $class['r_id'],
            ));

            $stats[$class['r_id']] = 0;
            foreach($class['students'] as $admission){

                //Fetch all their submissions so far:
                $us_data = $this->Db_model->us_fetch(array(
                    'us_student_id' => $admission['u_id'],
                    'us_r_id' => $class['r_id'],
                    'us_status' => 1,
                ));


                //Go through and see where it breaks down:
                $found_incomplete_task = false;
                $total_hours_done = 0;
                $ru_cache__current_milestone = 1;
                $ru_cache__current_task = 1;
                $total_tasks = 0;
                $done_tasks = 0;

                //The goal is to find the task that is after the very last task done
                //Note that some Tasks could be done, but then rejected by the instructor...
                foreach($bootcamps[0]['c__child_intents'] as $milestone){
                    if($milestone['c_status']==1){
                        foreach($milestone['c__child_intents'] as $task){
                            if($task['c_status']==1){
                                $total_tasks++;
                                //Has the student done this?
                                if(!array_key_exists($task['c_id'],$us_data) || !($us_data[$task['c_id']]['us_status']==1)){

                                    if(!$found_incomplete_task){
                                        //The student is not done with this task, so here is were they're at:
                                        $ru_cache__current_milestone = $milestone['cr_outbound_rank'];
                                        $ru_cache__current_task = $task['cr_outbound_rank'];
                                        $found_incomplete_task = true;
                                    }

                                } else {

                                    //Addup the total hours based on the Action Plan
                                    $total_hours_done += $us_data[$task['c_id']]['us_time_estimate'];
                                    $found_incomplete_task = false; //Reset this
                                    $done_tasks++;

                                }
                            }
                        }
                    }
                }

                //Calculate the total progress:
                $ru_cache__completion_rate = number_format(($total_hours_done/$bootcamps[0]['c__estimated_hours']),3);

                if($done_tasks==$total_tasks){
                    //They have done all Tasks
                    $ru_cache__current_milestone = ($class['r__total_milestones']+1);
                    $ru_cache__current_task = 1;
                }

                //Do we need to update?
                if(!($admission['ru_cache__current_milestone']==$ru_cache__current_milestone) || !($admission['ru_cache__current_task']==$ru_cache__current_task) || !($admission['ru_cache__completion_rate']==$ru_cache__completion_rate)){

                    //Update DB:
                    $this->Db_model->ru_update( $admission['ru_id'] , array(
                        'ru_cache__completion_rate' => $ru_cache__completion_rate,
                        'ru_cache__current_task' => $ru_cache__current_task,
                        'ru_cache__current_milestone' => $ru_cache__current_milestone,
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
                'ru.ru_status >' => 5,
                'u.u_fb_id >' => 0,
            ));

            $completed_students = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id' => $class['r_id'],
                'ru.ru_status' => 7,
                'u.u_fb_id >' => 0,
            ));

            //Update Class:
            $this->Db_model->r_update( $class['r_id'], array(
                'r_cache__completion_rate' => ( count($qualified_students)>0 ? number_format((count($completed_students) / count($qualified_students)), 3) : 0 ),
            ));
        }

        echo count($running_classes).' adjusted';
    }

    function bootcamp_editing(){
        $bootcamps = $this->Db_model->remix_bootcamps(array(
            'b_status >' => 0,
        ));

        //Now lets see which ones have descriptions:
        foreach($bootcamps as $bootcamp){
            $found = 0;
            foreach($bootcamp['c__child_intents'] as $milestone) {
                if($milestone['c_status']>=0){
                    foreach($milestone['c__child_intents'] as $task) {
                        if($task['c_status']>=0){
                            //Do something here...
                            /*
                            $this->Db_model->c_update( $task['c_id'] , array(
                                'c_complete_instructions' => null,
                            ));
                            $found++;
                            */
                        }
                    }
                }
            }
            if($found>0){
                echo '<hr />';
            }
        }
    }

    function profile(){
        echo_json($this->Facebook_model->fetch_profile('381488558920384','1670125439677259'));
    }



}