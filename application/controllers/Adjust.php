<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjust extends CI_Controller {

    //This controller is for functions that do mass adjustments on the DB

    function __construct() {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function ru_current_milestone(){
        //Go through all admissions for running classes:
        $classes = $this->Db_model->r_fetch(array(
            'r.r_status' => 2,
        ));

        $class_all = array();
        $stats = array();

        foreach($classes as $class){
            //Fetch all the students of these classes, and see where they are at:

            //Fetch full Bootcamp/Class data for this:
            $bootcamps = $this->Db_model->c_full_fetch(array(
                'b.b_id' => $class['r_b_id'],
            ));

            //Now override $class with the more complete version:
            $class = filter($bootcamps[0]['c__classes'],'r_id',$class['r_id']);

            //Fetch all students:
            $class['students'] = $this->Db_model->ru_fetch(array(
                'ru.ru_status'   => 4, //Initiated or higher as long as bootcamp is running!
                'ru.ru_r_id'	 => $class['r_id'],
            ));

            //Now see where each student is at:
            $stats[$class['r_id']] = 0;
            foreach($class['students'] as $admission){

                //Fetch all their submissions so far:
                $us_data = $this->Db_model->us_fetch(array(
                    'us_student_id' => $admission['u_id'],
                    'us_r_id' => $class['r_id'],
                    'us_status' => 1,
                ));

                //Go through and see where it breaks down:
                $stop = false;
                foreach($bootcamps[0]['c__child_intents'] as $milestone){
                    if($milestone['c_status']>0){
                        foreach($milestone['c__child_intents'] as $task){
                            if($task['c_status']>0){
                                //Has the student done this?
                                if(!array_key_exists($task['c_id'],$us_data)){
                                    //Nopes, not found, this is where the student is at!
                                    if(!($milestone['cr_outbound_rank']==$admission['ru_current_milestone'])){
                                        //We need to update:
                                        $this->Db_model->ru_update( $admission['ru_id'] , array(
                                            'ru_current_milestone' => $milestone['cr_outbound_rank'],
                                        ));
                                        //Increase counter:
                                        $stats[$class['r_id']]++;
                                    }
                                    $stop = true;
                                    break;
                                }
                            }
                        }
                    }
                    if($stop){
                        break;
                    }
                }



            }

            //array_push($class_all,$class);
        }

        echo_json($stats);
    }

    function bootcamp_editing(){
        $bootcamps = $this->Db_model->c_full_fetch(array(
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

    function lazaro(){
        echo_json(tree_message(896, 0, '381488558920384', 422, 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 68, 104));
        echo_json(tree_message(896, 0, '381488558920384', 416, 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 68, 104));
    }

    function profile(){
        echo_json($this->Facebook_model->fetch_profile('381488558920384','1670125439677259'));
    }



}