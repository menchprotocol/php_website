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


//Load direction:
var is_outbound = true;
function change_direction(){
	if(is_outbound){
		is_outbound = false; //change direction
		$('#dir_handle').removeClass('label-primary').addClass('label-default');
		$('#dir_name').html('INBOUND');
	} else {
		is_outbound = true; //change direction
		$('#dir_handle').removeClass('label-default').addClass('label-primary');
		$('#dir_name').html('OUTBOUND');
	}
}

//Loadup Algolia:
client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
algolia_index = client.initIndex('challenges');


function checkLoginState(){
	//Also called when user clicks on FB Login Button
	FB.getLoginStatus(function(response) {
		if(response.status=='connected'){
			//We're in! Redirect and log in the user:
			$.ajax({
		        type: "POST",
		        url: "/user/login",
		        data:{ response:response },
		        success: function(data){
		        	//Refresh page:
		        	window.location = "/marketplace";
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
		        		        url: "/user/logout", //Removes their session variables
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

function update_showdown(target,text){
	//First detect manuall conversions like YouTube Embed:
	//Start core convertor:
	var converter = new showdown.Converter({ 
		'simplifiedAutoLink': true,
		'strikethrough': true,
		'tables': true,
		'parseImgDimension': true,
		'headerLevelStart': '3',
	});
	//Convert and return:
	target.html(converter.makeHtml(text)).fadeIn();
}

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

function save_c(){
	//Save the object and its overview:
	if(!$('#save_c_objective').val().length){
		alert('Objective is required.');
		return false;
	}
	
	//Show spinner:
	$('#save_c_results').html('<span><img src="/img/loader.gif" /></span>').hide().fadeIn();
	
	$.post("/marketplace/challenge_modify", {
		save_c_id:$('#save_c_id').val(),
		save_c_objective:$('#save_c_objective').val(),
		save_c_description:$('#save_c_description').val(),
	}, function(data) {
		//Update UI to confirm with user:
		$('#save_c_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('#save_c_results').fadeOut();
	    }, 5000);
    });	
}


function new_challenge(c_objective){
	if(is_outbound){
		alert('OUT NEW');
	} else {
		alert('IN NEW');
	}
}

//Triggered when clicked on the toggle direction
function link_challenge(new_link_id){
	current_link_id = $('#save_c_id').val();
	if(is_outbound){
		alert('OUT LINK'+new_link_id);
	} else {
		alert('IN LINK'+new_link_id);
	}
}

function echo_dir(){
	if(is_outbound){
		return '<span class="label dirlabel label-primary">OUTBOUND <i class="fa fa-chevron-right" aria-hidden="true"></i></span>';
	} else {
		return '<span class="label dirlabel label-default">INBOUND <i class="fa fa-chevron-right" aria-hidden="true"></i></span>';
	}
}

function load_sortable(direction){
	var thelist = document.getElementById("list-"+direction);
	var sort = Sortable.create( thelist , {
		  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
		  onUpdate: function (evt/**Event*/){
			    //Set processing status:
			    $( "#list-"+direction+" .srt-"+direction ).hide().fadeIn().html(' <img src="/img/loader.gif" />');
			  
			    //Fetch new sort:
			    var new_sort = [];
				var sort_rank = 0;
				$( "#list-"+direction+" a" ).each(function() {
					sort_rank++;
					new_sort[sort_rank] = $( this ).attr('data-link-id');
				});
				
				//Update backend:
				$.post("/marketplace/update_sort", {save_c_id:$('#save_c_id').val(), new_sort:new_sort, sort_direction:direction}, function(data) {
					//Update UI to confirm with user:
					$( "#list-"+direction+" .srt-"+direction ).html(data).hide().fadeIn();
					
					//Disapper in a while:
					setTimeout(function() {
				        $("#list-"+direction+" .srt-"+direction).fadeOut();
				    }, 3000);
				});
		  }
	});
	//sort.destroy();
}

function delete_c(grandpa_id,c_id,c_title){
	//Double check:
	var r = confirm("Delete Challenge: "+c_title+"?");
	if (r == true) {
	    //Redirect to delete:
		window.location = "/marketplace/delete_c/"+grandpa_id+"/"+c_id;
	}
}


function cr_delete(cr_id,cr_title){
	
	//Stop href:
	var current_href = $('#cr_'+cr_id).attr("href");
	$('#cr_'+cr_id).attr("href", "#");
	
	//Double check:
	var r = confirm("Delete Dependency: "+cr_title+"?");
	if (r == true) {
	    //Delete and remove:
		$.post("/marketplace/cr_delete", {cr_id:cr_id}, function(data) {
			//Update UI to confirm with user:
			
			$( "#cr_"+cr_id ).html(data);
			
			//Disapper in a while:
			setTimeout(function() {
				$( "#cr_"+cr_id ).fadeOut();
		    }, 3000);
		});
	} else {
		//Put link back in:
		setTimeout(function() {
			$('#cr_'+cr_id).attr("href", "#").attr("href", current_href);
	    }, 1000);
	}
}

$(document).ready(function() {
	
	$('#save_c_description').bind('input propertychange', function() {
		update_showdown($('.showdown'),this.value);
	});
	$('#save_c_objective').bind('input propertychange', function() {
		update_showdown($('.c_objective'),this.value);
	});

	
	//Navbar landing page?
	if(!$(".navbar").hasClass("no-adj")){
		adj();
	  	$(window).scroll(function() {
	  		adj();
	  	});
	}
	
	//Showdowns?
	if ( $( ".showdown" ).length ) {
		update_showdown($('.showdown'),$('.showdown').text());
	}
	
	//Load tooltips:
	$(function () {
		  $('[data-toggle="tooltip"]').addClass('').tooltip();
	});
	
	
	//Load Sortable, IF ADMIN:
	if(u_status>=2){
		if($('#list-outbound').length){
			$('#list-outbound a').prepend('<i class="fa fa-sort" aria-hidden="true" style="padding-right:10px;"></i>').append(' <span class="srt-outbound"></span>');
			load_sortable('outbound');
		}
		if($('#list-inbound').length){
			$('#list-inbound a' ).prepend('<i class="fa fa-sort" aria-hidden="true" style="padding-right:10px;"></i>').append(' <span class="srt-inbound"></span>');
			load_sortable('inbound');
		}
	}
		

	
	//Load Algolia:
	$( "#addnode" ).on('autocomplete:selected', function(event, suggestion, dataset) {
		
		link_challenge(suggestion.c_id);
		
	}).autocomplete({ hint: false, keyboardShortcuts: ['a'] }, [{
	    source: function(q, cb) {
		      algolia_index.search(q, { hitsPerPage: 7 }, function(error, content) {
		        if (error) {
		          cb([]);
		          return;
		        }
		        
		        cb(content.hits, content);
		      });
		    },
		    displayKey: function(suggestion) { return "" },
		    templates: {
		      suggestion: function(suggestion) {
		         return '<span class="suggest-prefix"><i class="fa fa-link" aria-hidden="true"></i> Link to</span> '+ suggestion._highlightResult.c_objective.value + ' ' + echo_dir();
		      },
		      header: function(data) {
		    	  if(!data.isEmpty){
		    		  return '<a href="javascript:new_challenge(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><i class="fa fa-plus" aria-hidden="true"></i> Create</span> "'+data.query+'" '+echo_dir()+'</a>';
		    	  }
		      },
		      empty: function(data) {
	    		  	  return '<a href="javascript:new_challenge(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><i class="fa fa-plus" aria-hidden="true"></i> Create</span> "'+data.query+'" '+echo_dir()+'</a>';
		      },
		    }
	}]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
        	new_challenge('outbound',$( "#addnode" ).val());
            return true;
        }
    });
});