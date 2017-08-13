//US Foundation Google Analytics:
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-92774608-1', 'auto');
ga('send', 'pageview');


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
    	
    	//Look for logout click:
	    $( "#logoutbutton" ).click(function() {
        	
	    	//lets log them out from Facebook:
	    	FB.getLoginStatus(function(response) {
	        	if(response.status=='connected'){
	        		//Logout on Facebook:
	        		FB.logout(function(response2) {
	        			//Delay the logout & redirect to allow facebook to log them out
	        			//If not, the user logs back in!
	        			setTimeout(function() {
	        				$.ajax({
		        		        type: "POST",
		        		        url: "/logout", //Removes their session variables
		        		        success: function(data){
		        		        	//Go to Home Page:
		        			    	window.location = "/";
		        		        }
		        		    });
	        			}, 1000);
	        			alert('Logout successful. See you soon ;)');
	    	    	});
	        	}
	        });
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