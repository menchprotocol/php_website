//Google Analytics:
//NOTE: GA is also inlcuded in /application/views/front/shared/p_header.php in case any adjustments needed to be made!
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-92774608-1', 'auto');
ga('send', 'pageview');

//Facebook SDK for JavaScript 
window.fbAsyncInit = function() {
  FB.init({
    appId            : '1782431902047009', //MenchBot
    autoLogAppEvents : true,
    xfbml            : true,
    version          : 'v2.11'
  });
};
(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "https://connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));


//This is necessary for the Facebook Messenger Chat button to work!
$( document ).ready(function() {
	if($('.bg-glow').length){
		setInterval(function(){
	    	$('.bg-glow').toggleClass('glow');
	    }, 500);
	}
});


//Drip:
/*
var _dcq = _dcq || [];
var _dcs = _dcs || {};
_dcs.account = '3399358';
(function() {
    var dc = document.createElement('script');
    dc.type = 'text/javascript'; dc.async = true;
    dc.src = '//tag.getdrip.com/3399358.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(dc, s);
})();
*/



//Zendesk:
//NOTE: If you enable this, make sure to enable to auto fills for user name by searching for "zE( funct"
//window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(e){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var e=this.createElement("script");n&&(this.domain=n),e.id="js-iframe-async",e.src="https://assets.zendesk.com/embeddable_framework/main.js",this.t=+new Date,this.zendeskHost="mench.zendesk.com",this.zEQueue=a,this.body.appendChild(e)},o.write('<body onload="document._l();">'),o.close()}();



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
	
	//Navbar landing page?
	if(!$(".navbar").hasClass("no-adj")){
		adj();
	  	$(window).scroll(function() {
	  		adj();
	  	});
	}
	
	//Load tooltips:
	$(function () {
		  $('[data-toggle="tooltip"]').addClass('').tooltip();
	});
});
