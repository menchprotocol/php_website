<script>
(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.com/en_US/messenger.Extensions.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'Messenger'));

//the Messenger Extensions JS SDK is done loading:
window.extAsyncInit = function() {


	//Update backend:
 	$.post("/my/...", {psid:'1443101719058431'}, function(data) {
 		//Update UI to confirm with user:
 		$( "#page_content").html(data);
 	});


 	
	/*
	//Get User ID:
    MessengerExtensions.getUserID(function success(uids) {
    	// User ID was successfully obtained.
      	var psid = uids.psid;
      	$("#page_content").html(psid);
    }, function error(err, errorMessage) {
    	$("#page_content").html('<div class="alert alert-danger" role="alert">ERROR: Access allowed via Facebook Messenger only.</div>');
    });
	*/
};
</script>



<h2>Classmates</h2>
<div id="page_content"><div style="text-align:center;"><img src="/img/round_yellow_load.gif" class="loader" /></div></div>
