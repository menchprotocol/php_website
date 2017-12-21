<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_chat_v1 extends CI_Controller{

    /*
     * Used by the Chat Widget developed by Leonard
     *
     * */

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    function update_admission_status(){

        //Change user status:
        if(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
        } elseif(!isset($_POST['initiator_u_id']) || intval($_POST['initiator_u_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Instructor ID',
            ));
        } elseif(!isset($_POST['recipient_u_id']) || intval($_POST['recipient_u_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Student ID',
            ));
        } elseif(!isset($_POST['r_id']) || intval($_POST['r_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Class ID',
            ));
        } elseif(!isset($_POST['ru_status']) || intval($_POST['ru_status'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Admission Status',
            ));
        } elseif(!isset($_POST['auth_hash']) || !(md5( $_POST['recipient_u_id'] . $_POST['r_id'] . $_POST['ru_status'] . '7H6hgtgtfii87' ) == $_POST['auth_hash'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Auth Hash',
            ));
        } else {

            //All seemed good so far, lets do a bit more validation...
            //Fetch instructor/Bootcamp:
            $fetch_instructors = $this->Db_model->ba_fetch(array(
                'ba.ba_b_id' => intval($_POST['b_id']),
                'ba.ba_u_id' => intval($_POST['initiator_u_id']),
                'ba.ba_status >=' => 0,
                'u.u_status >=' => 1,
            ));

            //Fetch Student:
            $admissions = $this->Db_model->remix_admissions(array(
                'ru.ru_u_id'	=> intval($_POST['recipient_u_id']),
                'r.r_b_id'	    => intval($_POST['b_id']),
                'r.r_id'	    => intval($_POST['r_id']),
            ));

            //Validate Student ID:
            if(!(count($fetch_instructors)==1)){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Instructor Not Assigned to Bootcamp',
                ));
            } elseif(count($admissions)<1){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Student Not Enrolled in Bootcamp',
                ));
            } elseif(count($admissions)>1){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Student Enrolled On Multiple Bootcamps',
                ));
            } elseif(!in_array($admissions[0]['ru_status'],array(2,4))){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Admission status can only be changed if original status is '.trim(strip_tags(status_bible('ru',2))).' or '.trim(strip_tags(status_bible('ru',4))),
                ));
            } elseif($admissions[0]['ru_status']==2 && !in_array($_POST['ru_status'],array(-1,4))){
                echo_json(array(
                    'status' => 0,
                    'message' => 'You can only change the status of a new student to '.trim(strip_tags(status_bible('ru',-1))).' or '.trim(strip_tags(status_bible('ru',4))),
                ));
            } elseif($admissions[0]['ru_status']==2 && $_POST['ru_status']==-1 && (!isset($_POST['status_change_note']) || strlen($_POST['status_change_note'])<50)){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Rejecting the application of a new student requires a descriptive note at-least 50 characters long.',
                ));
            } elseif($admissions[0]['ru_status']==4 && !in_array($_POST['ru_status'],array(-3,7))){
                echo_json(array(
                    'status' => 0,
                    'message' => 'You can only change the status of an admitted student to '.trim(strip_tags(status_bible('ru',-3))).' or '.trim(strip_tags(status_bible('ru',7))),
                ));
            } elseif($admissions[0]['ru_status']==4 && (!isset($_POST['status_change_note']) || strlen($_POST['status_change_note'])<50)){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Changing the status of an admitted student requires a descriptive note at-least 50 characters long.',
                ));
            } else {

                //Proceed to Change Status:
                $this->Db_model->ru_update( $admissions[0]['ru_id'] , array(
                    'ru_status' => intval($_POST['ru_status']),
                ));

                //Log Status change engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => intval($_POST['initiator_u_id']),
                    'e_recipient_u_id' => intval($_POST['recipient_u_id']),
                    'e_message' => ( isset($_POST['status_change_note']) ? trim($_POST['status_change_note']) : '' ), //Notes by the instructor
                    'e_json' => json_encode($_POST),
                    'e_type_id' => 000, //TODO Admission Status Change
                    'e_b_id' => $admissions[0]['b_id'],
                    'e_r_id' => $admissions[0]['r_id'],
                ));

                //Show success:
                echo_json(array(
                    'status' => 1,
                    'message' => 'Status Updated Successfully',
                ));
            }
        }
    }


    function send_message(){

        //Used for the Chat Widget API to send outbound messages:
        //Auth user and check required variables:
        if(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
        } elseif(!isset($_POST['initiator_u_id']) || intval($_POST['initiator_u_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Sender/Instructor ID',
            ));
        } elseif(!isset($_POST['recipient_u_id']) || intval($_POST['recipient_u_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Receiver/Student ID',
            ));
        } elseif(!isset($_POST['message_type']) || !in_array($_POST['message_type'],array('text','audio','video','image','file'))){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Message Type',
            ));
        } elseif(!isset($_POST['auth_hash']) || !(md5( $_POST['initiator_u_id'] . $_POST['recipient_u_id'] . $_POST['message_type'] . '7H6hgtgtfii87' ) == $_POST['auth_hash'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Auth Hash',
            ));
        } elseif($_POST['message_type']=='text' && (!isset($_POST['text_payload']) || strlen($_POST['text_payload'])<1)){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Text Payload',
            ));
        } elseif(in_array($_POST['message_type'],array('audio','video','image','file')) && !isset($_POST['attach_url'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Attachment URL',
            ));
        } else {

            //Fetch instructor/Bootcamp:
            $fetch_instructors = $this->Db_model->ba_fetch(array(
                'ba.ba_b_id' => intval($_POST['b_id']),
                'ba.ba_u_id' => intval($_POST['initiator_u_id']),
                'ba.ba_status >=' => 0,
                'u.u_status >=' => 1,
            ));

            //Fetch Student:
            $admissions = $this->Db_model->remix_admissions(array(
                'ru.ru_u_id'	=> intval($_POST['recipient_u_id']),
                'r.r_b_id'	    => intval($_POST['b_id']),
            ));

            //Validate Student ID:
            if(!(count($fetch_instructors)==1)){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Instructor Not Assigned to Bootcamp',
                ));
            } elseif(count($admissions)<1){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Student Not Enrolled in Bootcamp',
                ));
            } elseif(count($admissions)>1){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Student Enrolled On Multiple Bootcamps',
                ));
            } elseif(strlen($admissions[0]['u_fb_id'])<5){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Student Not Activated Messenger Yet',
                ));
            } else {

                //Proceed to Send Message:
                if($_POST['message_type']=='text'){
                    //Create Engagement message to be saved:
                    $e_message = $_POST['text_payload'];
                    $fb_message = array(
                        'text' => $_POST['text_payload'],
                        'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
                    );
                } else {
                    //Create Engagement message to be saved:
                    $e_message = '/attach '.$_POST['message_type'].':'.trim($_POST['attach_url']);
                    $fb_message = array(
                        'attachment' => array(
                            'type' => $_POST['message_type'],
                            'payload' => array(
                                'url' => $_POST['attach_url'],
                            ),
                        ),
                        'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
                    );
                }

                //Send Message:
                $this->Facebook_model->batch_messages( '381488558920384', $admissions[0]['u_fb_id'] , array($fb_message), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/ );

                //Log Engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => intval($_POST['initiator_u_id']),
                    'e_recipient_u_id' => intval($_POST['recipient_u_id']),
                    'e_message' => $e_message,
                    'e_json' => json_encode($_POST),
                    'e_type_id' => 7, //Outbound Message
                    'e_b_id' => $admissions[0]['b_id'],
                    'e_r_id' => $admissions[0]['r_id'],
                ));

                //Show success:
                echo_json(array(
                    'status' => 1,
                    'message' => 'Message sent',
                ));
            }
        }
    }




}