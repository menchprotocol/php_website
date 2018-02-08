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

    function lazaro(){
        echo_json(tree_message(896, 0, '381488558920384', 422, 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 68, 104));
        echo_json(tree_message(896, 0, '381488558920384', 416, 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 68, 104));
    }

    function profile(){
        echo_json($this->Facebook_model->fetch_profile('381488558920384','1670125439677259'));
    }



}