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

	  MessengerExtensions.getContext('1782431902047009', 
    	  function success(result){
      		  $('#me3').html('Succes3');
    	  },
    	  function error(result){
    		  $('#me3').html('Fail3');
    	  }
    	);


		
    // the Messenger Extensions JS SDK is done loading 
	  MessengerExtensions.getUserID(function success(uids) {
	  	// User ID was successfully obtained. 
	      	var psid = uids.psid;
	      	$('#me2').html(psid);
	    }, function error(err, errorMessage) {      
	  	// Error handling code
	    	$('#me2').html('Fail');
	    });    
  };
</script>


<script>

function close_webview(){
	window.location = 'https://www.messenger.com/closeWindow/?display_text=Closing....';
}
</script>

<a href="javascript:close_webview();">close_webview</a>
<br /><br />

This is it:
<div id="me"></div>
<div id="me2"></div>
<div id="me3"></div>
