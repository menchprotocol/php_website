<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Email_model extends CI_Model {
	
	var $CLIENT;
	
	function __construct() {
		parent::__construct();
		
		//Loadup amazon SES:
		@require_once( 'application/libraries/aws/aws-autoloader.php' );
		$this->CLIENT = new Aws\Ses\SesClient([
		    'version' 	    => 'latest',
		    'region'  	    => 'us-west-2',
		    'credentials'   => $this->config->item('aws_credentials'),
		]);
	}


	function email_intent($b_id, $c_id, $udata){

	    //Send out the title and all the messages of this intent to the user:

        //Fetch this intent:
        $tree = $this->Db_model->c_fetch(array(
            'c.c_id' => $c_id,
        ) , 1 , array('i') /* Append messages to the return */ );


        //Count active Messages:
        $html_message = null;
        if(count($tree)==1 && isset($tree[0]['c__messages']) && count($tree[0]['c__messages'])>0 && isset($udata['u_id']) && isset($udata['u_email'])){
            foreach($tree[0]['c__messages'] as $i){
                if($i['i_status']==1){
                    //Grow Message:
                    $html_message .= echo_i(array_merge( $i , array(
                        'e_recipient_u_id' => $udata['u_id'],
                        'e_b_id' => $b_id,
                        'i_c_id' => $c_id,
                    )), $udata['u_fname'], false );
                }
            }
        }

        if($html_message){

            //Send email:
            $sent_status = $this->send_single_email(array($udata['u_email']),$tree[0]['c_objective'],$html_message);

            //Log engagement once:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => 0, //System initiates these types of emails from this function
                'e_recipient_u_id' => $udata['u_id'], //The user that updated the account
                'e_message' => $tree[0]['c_objective'],
                'e_json' => json_encode(array(
                    'udata' => $udata,
                    'html' => $html_message,
                    'tree' => $tree[0],
                )),
                'e_type_id' => 28, //Email message sent
                'e_c_id' => $c_id,
                'e_b_id' => $b_id,
            ));

            //Return positive:
            return true;

        } else {

            //Log error:
            $this->Db_model->e_create(array(
                'e_message' => 'Failed to generate Email content for Email_model->email_intent() function',
                'e_type_id' => 8, //Platform Error
                'e_initiator_u_id' => 0, //System initiates these types of emails from this function
                'e_recipient_u_id' => $udata['u_id'], //The user that updated the account
                'e_json' => json_encode(array(
                    'udata' => $udata,
                    'tree' => ( isset($tree[0]) ? $tree[0] : null ),
                )),
                'e_c_id' => $c_id,
                'e_b_id' => $b_id,
            ));

            return false;

        }
    }

	//This function is used by other template functions above...
	function send_single_email($to_array,$subject,$html_message){
	    if(is_dev()){
	        return true;
	    } else {
	        return $this->CLIENT->sendEmail(array(
	            // Source is required
	            'Source' => 'support@mench.co',
	            // Destination is required
	            'Destination' => array(
	                'ToAddresses' => $to_array,
	                'CcAddresses' => array(),
	                'BccAddresses' => array(),
	            ),
	            // Message is required
	            'Message' => array(
	                // Subject is required
	                'Subject' => array(
	                    // Data is required
	                    'Data' => $subject,
	                    'Charset' => 'UTF-8',
	                ),
	                // Body is required
	                'Body' => array(
	                    'Text' => array(
	                        // Data is required
	                        'Data' => strip_tags($html_message),
	                        'Charset' => 'UTF-8',
	                    ),
	                    'Html' => array(
	                        // Data is required
	                        'Data' => $html_message,
	                        'Charset' => 'UTF-8',
	                    ),
	                ),
	            ),
	            'ReplyToAddresses' => array('support@mench.co'),
	            'ReturnPath' => 'support@mench.co',
	        ));
	    }
	}
}