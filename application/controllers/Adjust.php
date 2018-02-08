<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjust extends CI_Controller {

    //This controller is for functions that do mass adjustments on the DB

    function __construct() {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
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