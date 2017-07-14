<?php
$pid = '614_766'; //Learn More Plugin + brainplugins.com @Entity
$active_bots = $this->config->item('active_bots');
?>
<script>
  // Learn more: https://developers.facebook.com/docs/messenger-platform/plugin-reference/send-to-messenger
  window.fbAsyncInit = function() {
    FB.init({
      appId: "<?= $active_bots[0]['fb_app_id'] ?>",
      xfbml: true,
      version: "v2.6"
    });
    
    FB.Event.subscribe('send_to_messenger', function(e) {
    	if(e.event=='rendered'){
        	//Log this:
        	$('#initial_buttom').hide();
        } else if(e.event=='clicked'){
        	//Show loading:
        	
        } else if(e.event=='opt_in'){
        	//Redirect to messenger:
        	window.location.href = "<?= $active_bots[0]['bot_ref_url'].$pid ?>";
        }
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) { return; }
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>

<h1 class="boldi">Tansform Your Life</h1>
<p class="boldi">Close the gab between where you are today, and where you want to be.</p>
<p class="boldi">Get Started on Messenger:</p>

<div class="boldi messenger"><div class="fb-send-to-messenger" 
  messenger_app_id="<?= $active_bots[0]['fb_app_id'] ?>" 
  page_id="<?= $active_bots[0]['fb_page_id'] ?>" 
  data-ref="<?= $pid ?>" 
  color="blue" 
  size="xlarge"></div><a href="<?= $active_bots[0]['bot_ref_url'].$pid ?>" id="initial_buttom"><img src="/img/sendtomessenger.PNG" /></a></div>
