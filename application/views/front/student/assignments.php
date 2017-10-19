assignments

<script>
    MessengerExtensions.getUserID(function success(uids) {
  	// User ID was successfully obtained. 
      	var psid = uids.psid;
  		$('#me').html(psid);
    }, function error(err, errorMessage) {      
  	// Error handling code
    });    
</script>

<div id="me"></div>