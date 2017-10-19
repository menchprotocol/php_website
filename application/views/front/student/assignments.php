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
	//Get User ID:
    MessengerExtensions.getUserID(function success(uids) {
    	// User ID was successfully obtained.
      	var psid = uids.psid;
      	$("#page_content").html(psid);
    }, function error(err, errorMessage) {
    	$("#page_content").html('Unknown error while loading content.');
    });

};

//Optionally you can close webview like this:
function close_webview(){
	window.location = 'https://www.messenger.com/closeWindow/?display_text=Closing....';
}
</script>


<h2>Assignments</h2>

<div id="page_content"></div>
