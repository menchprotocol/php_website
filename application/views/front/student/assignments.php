assignments

<script>

$( document ).ready(function() {
	$('#me2').html('Check');


	//Facebook Kit
	(function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);


	     MessengerExtensions.getUserID(function success(uids) {
       	// User ID was successfully obtained. 
           	var psid = uids.psid;
       		$('#me').html(psid);
         }, function error(err, errorMessage) {      
       	// Error handling code
         	$('#me').html(err+':'+errorMessage);
         });

         
	}(document, 'script', 'facebook-jssdk'));

	
    	
});


    
</script>

This is it:
<div id="me"></div>
<div id="me2"></div>
