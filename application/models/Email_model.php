<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Email_model extends CI_Model {
	
	var $CLIENT;
	
	function __construct() {
		parent::__construct();
		
		//Loadup amazon SES:
		require( 'application/libraries/aws/aws-autoloader.php' );
		$this->CLIENT = new Aws\Ses\SesClient([
		    'version' 	    => 'latest',
		    'region'  	    => 'us-west-2',
		    'credentials'   => $this->config->item('aws_credentials'),
		]);
	}
	
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