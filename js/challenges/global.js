(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function checkLoginState(){
	FB.getLoginStatus(function(response) {
		if(response.status=='connected'){
			//We're in! Redirect and log in the user:
			$.ajax({
		        type: "POST",
		        url: "/login_auth",
		        data:{ response:response }, 
		        success: function(data){
		        	//Refresh page:
					window.location.reload();
		        }
		    });
		}
    });
}


window.fbAsyncInit = function() {
	
	//Initiate:
    FB.init({
      appId      : '1782431902047009',
      cookie     : true,
      xfbml      : true,
      version    : 'v2.8'
    });
    
    //Log page view:
    FB.AppEvents.logPageView();
    
    //Check FB status:
    if($("#isloggedin").length==0){
    	
    	//See if user is already logged in, and refresh if so:
    	checkLoginState();
    	
    } else {
    	
    	//Look for logout:
	    $( "#logoutbutton" ).click(function() {
        	
	    	//lets log them out from Facebook:
	    	FB.getLoginStatus(function(response) {
	        	if(response.status=='connected'){
	        		FB.logout(function(response) {
	    	    		// user is now logged out
	    	    	});
	        	}
	        });	    	
	    	
	    	//Logout from us:
	    	$.ajax({
		        type: "POST",
		        url: "/logout", //Removes their session variables
		    });
	    	
	    	//Notify them:
	    	alert('Logout successful. See you soon ;)');
	    	
	    	//Go to Home Page:
	    	window.location = "/";
	    	
	    });
	    
    }
};

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
	if(!$(".navbar").hasClass("no-adj")){
		adj();
	  	$(window).scroll(function() {
	  		adj();
	  	});
	}
});