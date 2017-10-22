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
 	$.post("/my/fetch_assignments", {psid:'1443101719058431'}, function(data) {
 		//Update UI to confirm with user:
 		$( "#page_content").html(data);
 	});


 	//Temp:
 	$( "#page_content").html('<div class="alert alert-danger" role="alert">You are not enrolled in any bootcamps. Get started today by <a href="/bootcamps"><u>Browsing Bootcamps</u> <i class="fa fa-chevron-right" aria-hidden="true"></i></a></div>');
 	
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

//Optionally you can close webview like this:
function close_webview(){
	window.location = 'https://www.messenger.com/closeWindow/?display_text=Closing....';
}
</script>



<h2>Leaderboard</h2>
<div id="page_content"><div style="text-align:center;"><img src="/img/round_yellow_load.gif" class="loader" /></div></div>
