//Google Analytics:
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-92774608-1', 'auto');
ga('send', 'pageview');



//Hotjar:
(function(h,o,t,j,a,r){
    h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
    h._hjSettings={hjid:751796,hjsv:6};
    a=o.getElementsByTagName('head')[0];
    r=o.createElement('script');r.async=1;
    r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
    a.appendChild(r);
})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');


//Facebook SDK for JavaScript:
window.fbAsyncInit = function(){

    FB.init({
        appId            : '1782431902047009', //Mench
        autoLogAppEvents : true,
        xfbml            : true,
        version          : 'v2.10'
    });

    //This would only be included via console_header.php which is for the Console:
    if(!$(".fb-customerchat").length) {
        FB.getLoginStatus(function(response) {

            if (response.status === 'connected' || response.status === 'not_authorized') {

                //User is logged into Facebook, show FB Chat:
                $('body').prepend('<div class="fb-customerchat" minimized="true" greeting_dialog_display="fade" theme_color="#3C4858" page_id="381488558920384"></div>');

                //Re-initiate to show Chat:
                FB.init({
                    appId            : '1782431902047009', //Mench
                    autoLogAppEvents : true,
                    xfbml            : true,
                    version          : 'v2.10'
                });

            } else {

                //Zendesk chat:
                /*<![CDATA[*/window.zE||(function(e,t,s){var n=window.zE=window.zEmbed=function(){n._.push(arguments)}, a=n.s=e.createElement(t),r=e.getElementsByTagName(t)[0];n.set=function(e){ n.set._.push(e)},n._=[],n.set._=[],a.async=true,a.setAttribute("charset","utf-8"), a.src="https://static.zdassets.com/ekr/asset_composer.js?key="+s, n.t=+new Date,a.type="text/javascript",r.parentNode.insertBefore(a,r)})(document,"script","cf7fffe0-e256-4eab-a00c-09ad223affd7");/*]]>*/

            }
        });
    }
};


(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
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

function processAjaxData(response, urlPath){
    document.getElementById("content").innerHTML = response.html;
    document.title = response.pageTitle;
    window.history.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath);
}

function c_tree_menu(c_id,hash_key){

    //Show loading:
    $('#menu_content').html('<span><img src="/img/round_load.gif" style="width:16px; height:16px; margin-top:-2px;" class="loader" /></span>');
    $.post("/api_v1/c_tree_menu", {
        c_id:c_id,
        hash_key:hash_key,
    }, function(data) {
        //Show success:
        $('#menu_content').html(data);
    });
}

function toggle_hidden_class(class_name){
    $('.'+class_name).each(function(){
        if($(this).hasClass('hidden')){
            $(this).removeClass('hidden');
        } else {
            $(this).addClass('hidden');
        }
    });
}

$(document).ready(function() {

    //This is necessary (!) for the Facebook Messenger Chat button to work:
    /*
    if($('.bg-glow').length){
        setInterval(function(){
            $('.bg-glow').toggleClass('glow');
        }, 500);
    }
    */

	//Navbar landing page?
	if(!$(".navbar").hasClass("no-adj")){
		adj();
	  	$(window).scroll(function() {
	  		adj();
	  	});
	}

	//Load tooltips:
	$(function () {
		  $('[data-toggle="tooltip"]').tooltip();
	});

});
