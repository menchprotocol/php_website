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
	
	
	
	
	function contact_us($user_name,$user_email,$user_message){
			    
	    $message1 = 'A new message has been received: <br /><br />Name: '.$user_name.'<br />Email: '.$user_email.'<br /> Message: '.$user_message;
	    $result1 = $this->CLIENT->sendEmail(array(
	        // Source is required
	        'Source' => 'support@mench.co',
	        // Destination is required
	        'Destination' => array(
	            'ToAddresses' => array('shervin@mench.co','miguel@mench.co'),
	            'CcAddresses' => array(),
	            'BccAddresses' => array(),
	        ),
	        // Message is required
	        'Message' => array(
	            // Subject is required
	            'Subject' => array(
	                // Data is required
	                'Data' => 'New Message - mench.co',
	                'Charset' => 'UTF-8',
	            ),
	            // Body is required
	            'Body' => array(
	                'Text' => array(
	                    // Data is required
	                    'Data' => strip_tags($message1),
	                    'Charset' => 'UTF-8',
	                ),
	                'Html' => array(
	                    // Data is required
	                    'Data' => $message1,
	                    'Charset' => 'UTF-8',
	                ),
	            ),
	        ),
	        'ReplyToAddresses' => array('shervin@mench.co','miguel@mench.co'),
	        'ReturnPath' => 'support@mench.co',
	    ));
	    
	    
	    
	    $message2 = 'Hi '.$user_name.', <br /><br />This is a confirmation that we have received your message and will get back to your shortly. <br /><br />Team Mench.';
	    $result2 = $this->CLIENT->sendEmail(array(
	        // Source is required
	        'Source' => 'support@mench.co',
	        // Destination is required
	        'Destination' => array(
	            'ToAddresses' => array($user_email),
	            'CcAddresses' => array(),
	            'BccAddresses' => array(),
	        ),
	        // Message is required
	        'Message' => array(
	            // Subject is required
	            'Subject' => array(
	                // Data is required
	                'Data' => 'Message Confirmation - mench.co',
	                'Charset' => 'UTF-8',
	            ),
	            // Body is required
	            'Body' => array(
	                'Text' => array(
	                    // Data is required
	                    'Data' => strip_tags($message2),
	                    'Charset' => 'UTF-8',
	                ),
	                'Html' => array(
	                    // Data is required
	                    'Data' => $message2,
	                    'Charset' => 'UTF-8',
	                ),
	            ),
	        ),
	        'ReplyToAddresses' => array('support@mench.co'),
	        'ReturnPath' => 'support@mench.co',
	    ));
	    
	    return ( $result1 && $result2 );
	}
	
	
}