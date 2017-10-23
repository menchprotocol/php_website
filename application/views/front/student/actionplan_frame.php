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
	
	<?php if(is_dev()){ ?>
	
	var psid = '1443101719058431';
	$.post("/my/display_actionplan/"+psid+"/<?= $b_id ?>/<?= $c_id ?>", {}, function(data) {
 		//Update UI to confirm with user:
 		$( "#page_content").html(data).append('.');
 	});
 	
	<?php } else { ?>
	
	//Get User ID:
    MessengerExtensions.getUserID(function success(uids) {
    	//User ID was successfully obtained.
      	var psid = uids.psid;
        //Fetch Page:
     	$.post("/my/display_actionplan/"+psid+"/<?= $b_id ?>/<?= $c_id ?>", {}, function(data) {
     		//Update UI to confirm with user:
     		$( "#page_content").html(data);
     	});
     	
    }, function error(err, errorMessage) {
    	$("#page_content").html('<div class="alert alert-danger" role="alert">ERROR: You Must Access Using Facebook Messenger.</div>');
    });
    
    <?php } ?>
    
};

//Optionally you can close webview like this:
function close_webview(){
	window.location = 'https://www.messenger.com/closeWindow/?display_text=Closing....';
}
</script>

<div id="page_content"><div style="text-align:center;"><img src="/img/round_yellow_load.gif" class="loader" /></div></div>