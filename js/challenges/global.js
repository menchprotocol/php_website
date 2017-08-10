window.fbAsyncInit = function() {
    FB.init({
      appId      : '1782431902047009',
      cookie     : true,
      xfbml      : true,
      version    : 'v2.8'
    });
    FB.AppEvents.logPageView();   
    
    /*
    FB.getLoginStatus(function(response) {
        alert(response);
    });
    */
};
(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function adj(){
		var scroll = $(window).scrollTop();
	     //>=, not <=
	    if (scroll >= 15) {
	        //clearHeader, not clearheader - caps H
	    	$(".navbar").removeClass("navbar-transparent");
	    } else {
	    	$(".navbar").addClass("navbar-transparent");
	    }
}


$(document).ready(function() {
  	adj();
  	$(window).scroll(function() {
  		adj();
  	});
});