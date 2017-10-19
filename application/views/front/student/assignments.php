assignments


<script>
(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.com/en_US/messenger.Extensions.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'Messenger'));
</script>      


<script>
  window.extAsyncInit = function() {
    // the Messenger Extensions JS SDK is done loading 
	  MessengerExtensions.getUserID(function success(uids) {
	  	// User ID was successfully obtained. 
	      	var psid = uids.psid;
	      	$('#me2').html(psid);
	    }, function error(err, errorMessage) {      
	  	// Error handling code
	    });    
  };
</script>


<script>

function close(){
	window.location = 'https://www.messenger.com/closeWindow/?display_text=Closing....';
}
</script>

<a href="javascript:close();">Close</a>

This is it:
<div id="me"></div>
<div id="me2"></div>
