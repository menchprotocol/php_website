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

        //Dummy test data:
        /*
        $_POST = array(
            'b_id' => 1,
            'initiator_u_id' => 2,
            'recipient_u_ids' => array(1),
            'ru_status' => 7,
        );
        $_POST['auth_hash'] = md5( $_POST['initiator_u_id'] . $_POST['ru_status'] . '7H6hgtgtfii87' );
        */

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
        } elseif(!isset($_POST['recipient_u_ids']) || !is_array($_POST['recipient_u_ids']) || count($_POST['recipient_u_ids'])<1){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Student ID Array',
            ));
        } elseif(!isset($_POST['ru_status']) || intval($_POST['ru_status'])<-3){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Admission Status',
            ));
        } elseif(!isset($_POST['auth_hash']) || !(md5( $_POST['initiator_u_id'] . $_POST['ru_status'] . '7H6hgtgtfii87' ) == $_POST['auth_hash'])){
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

            if(count($fetch_instructors)<1){
                //Maybe they are a super admin?
                $users = $this->Db_model->u_fetch(array(
                    'u_id' => intval($_POST['initiator_u_id']),
                ));
            }

            if(!(count($fetch_instructors)==1) && (!isset($users[0]) || $users[0]['u_status']<=2)){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Instructor Not Assigned to Bootcamp',
                ));
            } else {

                //Fetch Student:
                $unified_current_ru_status = null;
                $error_array = null;
                $admission_array = array(); //To store all admission data...

                foreach($_POST['recipient_u_ids'] as $u_id){

                    //Fetch the admission for this student:
                    $admissions = $this->Db_model->remix_admissions(array(
                        'ru.ru_u_id'	=> intval($u_id),
                        'r.r_b_id'	    => intval($_POST['b_id']),
                    ));

                    if(count($admissions)<1){
                        $error_array = array(
                            'status' => 0,
                            'message' => $admissions[0]['u_fname'].' '.$admissions[0]['u_lname'].' Not Enrolled in this Bootcamp',
                        );
                        break;
                    } elseif(count($admissions)>1){
                        $error_array = array(
                            'status' => 0,
                            'message' => $admissions[0]['u_fname'].' '.$admissions[0]['u_lname'].' Enrolled On Multiple Bootcamps',
                        );
                        break;
                    } elseif(!in_array($admissions[0]['ru_status'],array(2,4))){
                        $error_array = array(
                            'status' => 0,
                            'message' => $admissions[0]['u_fname'].' '.$admissions[0]['u_lname'].' Admission status can only be changed if original status is ['.trim(strip_tags(status_bible('ru',2))).'] or ['.trim(strip_tags(status_bible('ru',4))).']',
                        );
                        break;
                    } elseif($admissions[0]['ru_status']==4 && count($_POST['recipient_u_ids'])>1){
                        $error_array = array(
                            'status' => 0,
                            'message' => 'Setting status to ['.trim(strip_tags(status_bible('ru',intval($_POST['ru_status'])))).'] requires select only 1 student at a time with a unique note.',
                        );
                        break;
                    } elseif($unified_current_ru_status && !($unified_current_ru_status==$admissions[0]['ru_status'])){
                        //Ooops, this status if different from the previous student! This cannot happen.
                        $error_array = array(
                            'status' => 0,
                            'message' => 'Selecting multiple students requires having the same status of either ['.trim(strip_tags(status_bible('ru',2))).'] or ['.trim(strip_tags(status_bible('ru',4))).']',
                        );
                        break;
                    } elseif($admissions[0]['ru_status']==2 && !in_array($_POST['ru_status'],array(-1,4))){
                        $error_array = array(
                            'status' => 0,
                            'message' => $admissions[0]['u_fname'].' '.$admissions[0]['u_lname'].' is a new student. Status can only set to ['.trim(strip_tags(status_bible('ru',-1))).'] or ['.trim(strip_tags(status_bible('ru',4))).']',
                        );
                        break;
                    } elseif($admissions[0]['ru_status']==4 && !in_array($_POST['ru_status'],array(-3,7))){
                        $error_array = array(
                            'status' => 0,
                            'message' => $admissions[0]['u_fname'].' '.$admissions[0]['u_lname'].' is an admitted student. Status can only set to ['.trim(strip_tags(status_bible('ru',-3))).'] or ['.trim(strip_tags(status_bible('ru',7))).']',
                        );
                        break;
                    } else {

                        //Now check if multiple students and if all their IDs match!
                        if(!$unified_current_ru_status){
                            $unified_current_ru_status = $admissions[0]['ru_status'];
                        }

                        //Append admission data:
                        array_push($admission_array,$admissions[0]);

                    }
                }

                //Validate Student ID:
                if($error_array && is_array($error_array)) {

                    //Oooops, we had some sort of an error!
                    echo_json($error_array);

                } else {

                    //Proceed to Change Status for all students:
                    foreach($admission_array as $admission){

                        //Change status:
                        $this->Db_model->ru_update( $admission['ru_id'] , array(
                            'ru_status' => intval($_POST['ru_status']),
                        ));


                        //Log Status change engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => intval($_POST['initiator_u_id']),
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_message' => 'Student status changes from ['.trim(strip_tags(status_bible('ru',$unified_current_ru_status))).'] to ['.trim(strip_tags(status_bible('ru',intval($_POST['ru_status'])))).']', //Notes by the instructor
                            'e_json' => json_encode(array(
                                'post' => $_POST,
                                'admission' => $admission,
                            )),
                            'e_type_id' => 51, //Admission Status Change
                            'e_b_id' => $admission['b_id'],
                            'e_r_id' => $admission['r_id'],
                        ));


                        //DO we need to communicate?
                        if(intval($_POST['ru_status']) == 4){

                            //Send the email to their application:
                            $this->load->model('Email_model');
                            $email_sent = $this->Email_model->email_intent($admission['b_id'],2698,$admission);

                        } elseif (intval($_POST['ru_status']) == -1){

                            //Send the email to their application:
                            $this->load->model('Email_model');
                            $email_sent = $this->Email_model->email_intent($admission['b_id'],2799,$admission);

                        } elseif (intval($_POST['ru_status']) == 7){

                            //Send the email to their application:
                            $this->load->model('Email_model');
                            $email_sent = $this->Email_model->email_intent($admission['b_id'],2800,$admission);

                        } elseif (intval($_POST['ru_status']) == -3){

                            //Send the email to their application:
                            $this->load->model('Email_model');
                            $email_sent = $this->Email_model->email_intent($admission['b_id'],2801,$admission);

                        }

                    }

                    //Show success:
                    echo_json(array(
                        'status' => 1,
                        'message' => 'Status successfully updated for '.( count($admission_array)==1 ? $admission_array[0]['u_fname'].' '.$admission_array[0]['u_lname'] : count($admission_array).' students' ),
                    ));

                }
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

            if(count($fetch_instructors)<1){
                //Maybe they are a super admin?
                $users = $this->Db_model->u_fetch(array(
                    'u_id' => intval($_POST['initiator_u_id']),
                ));
            }

            //Fetch Student:
            $admissions = $this->Db_model->remix_admissions(array(
                'ru.ru_u_id'	=> intval($_POST['recipient_u_id']),
                'r.r_b_id'	    => intval($_POST['b_id']),
            ));

            //Validate Student ID:
            if(!(count($fetch_instructors)==1) && (!isset($users[0]) || $users[0]['u_status']<=2)){
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
                $this->Facebook_model->batch_messages( '381488558920384', $admissions[0]['u_fb_id'], array($fb_message) );

                //Log Engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => intval($_POST['initiator_u_id']),
                    'e_recipient_u_id' => intval($_POST['recipient_u_id']),
                    'e_message' => $e_message,
                    'e_json' => json_encode(array(
                        'post' => $_POST,
                        'fb_msg' => $fb_message,
                    )),
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
