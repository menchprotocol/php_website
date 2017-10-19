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
	    alert('done loading');
  };
</script>


This is it:
<div id="me"></div>
<div id="me2"></div>
