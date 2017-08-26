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
	//Fetch needed vars:
	pid = $('#save_c_id').val();
	c_id = $('#c_id').val();
	var direction = ( is_outbound ? 'outbound' : 'inbound' );
	
	//Set processing status:
    $( "#list-"+direction ).append('<a href="#" id="temp" class="list-group-item"><img src="/img/loader.gif" /> Adding... </a>');
	
    //Empty Input:
	$( "#addnode" ).val("").focus();
	
	//Update backend:
	$.post("/marketplace/challenge_create", {c_id:c_id, pid:pid, c_objective:c_objective, direction:direction}, function(data) {
		//Update UI to confirm with user:
		$( "#temp" ).remove();
		$( "#list-"+direction ).append(data);
		
		//Resort:
		load_sortable(direction);
	});
}


//Triggered when clicked on the toggle direction
function link_challenge(target_id){
	//Fetch needed vars:
	pid = $('#save_c_id').val();
	c_id = $('#c_id').val();
	var direction = ( is_outbound ? 'outbound' : 'inbound' );
	
	//Set processing status:
    $( "#list-"+direction ).append('<a href="#" id="temp" class="list-group-item"><img src="/img/loader.gif" /> Adding... </a>');
	
    //Empty Input:
	$( "#addnode" ).val("").focus();
	
	//Update backend:
	$.post("/marketplace/challenge_link", {c_id:c_id, pid:pid, target_id:target_id, direction:direction}, function(data) {
		//Update UI to confirm with user:
		$( "#temp" ).remove();
		$( "#list-"+direction ).append(data);
		
		//Resort:
		load_sortable(direction);
	});
}


function load_message_sorting(){
	var thelist = document.getElementById("message-sorting");
	var sort_msg = Sortable.create( thelist , {
		  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
		  onUpdate: function (evt/**Event*/){
			    //Set processing status:
			    $( ".edit-updates" ).html('<img src="/img/loader.gif" />');
			  
			    //Fetch new sort:
			    var new_sort = [];
				var sort_rank = 0;
				$( "#message-sorting>div" ).each(function() {
					sort_rank++;
					new_sort[sort_rank] = $( this ).attr('iid');
				});
				
				//Update backend:
				$.post("/marketplace/update_msg_sort", {new_sort:new_sort}, function(data) {
					//Update UI to confirm with user:
					$( ".edit-updates" ).html(data);
					
					//Disapper in a while:
					setTimeout(function() {
				        $(".edit-updates>span").fadeOut();
				    }, 3000);
				});
		  }
	});
}


function load_sortable(direction){
	if(direction=='inbound'){return false;}
	var thelist = document.getElementById("list-"+direction);
	var sort = Sortable.create( thelist , {
		  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
		  onUpdate: function (evt/**Event*/){
			    //Set processing status:
			    $( "#list-"+direction+" .srt-"+direction ).html(' <img src="/img/loader.gif" />');
			  
			    //Fetch new sort:
			    var new_sort = [];
				var sort_rank = 0;
				$( "#list-"+direction+">a" ).each(function() {
					sort_rank++;
					new_sort[sort_rank] = $( this ).attr('data-link-id');
				});
				
				//Update backend:
				$.post("/marketplace/update_sort", {save_c_id:$('#save_c_id').val(), new_sort:new_sort, sort_direction:direction}, function(data) {
					//Update UI to confirm with user:
					$( "#list-"+direction+" .srt-"+direction ).html(data);
					
					//Disapper in a while:
					setTimeout(function() {
				        $("#list-"+direction+" .srt-"+direction+">span").fadeOut();
				    }, 3000);
				});
		  }
	});
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
				$( "#cr_"+cr_id ).fadeOut().remove();
		    }, 3000);
		});
	} else {
		//Put link back in:
		setTimeout(function() {
			$('#cr_'+cr_id).attr("href", "#").attr("href", current_href);
	    }, 1000);
	}
}

function update_i_showdown(){
	$( "#message-sorting>div" ).each(function( index ) {
		var i_id = $( this ).attr('iid');
		update_showdown( $("#ul-nav-"+i_id+" .showdown") , $("#ul-nav-"+i_id+" textarea").val() );
	});
}
function update_c_overview_showdown(){
	update_showdown( $("#main_desc") , $("#main_desc").text() );
}



function msg_create(){
	
	//Fetch needed vars:
	var i_message = $('#i_message').val();
	var pid = $('#save_c_id').val();
	
	if(i_message.length<1 || pid<1){
		return false;
	}
	
	//Set processing status:
    $( "#message-sorting" ).append('<div id="temp"><div><img src="/img/loader.gif" /> Adding... </div></div>');
	
    //Empty Input:
	$( "#i_message" ).val("").focus();
	
	//Update backend:
	$.post("/marketplace/msg_create", {pid:pid, i_message:i_message}, function(data) {
		//Update UI to confirm with user:
		$( "#temp" ).remove();
		$( "#message-sorting" ).append(data);
		
		//Update showdown:
		update_i_showdown();
		
		//Resort:
		load_message_sorting();
	});
}

function msg_delete(i_id){
	//Double check:
	var r = confirm("Delete Message?");
	if (r == true) {
	    //Delete and remove:
		$.post("/marketplace/i_delete", {i_id:i_id}, function(data) {
			//Update UI to confirm with user:
			
			$("#ul-nav-"+i_id).html('<div>'+data+'</div>');
			
			//Disapper in a while:
			setTimeout(function() {
				$("#ul-nav-"+i_id).fadeOut().remove();
		    }, 3000);
		});
	}
}

function msg_start_edit(i_id){
	
	//Start editing:
	$("#ul-nav-"+i_id+" .edit-off").hide();
	$("#ul-nav-"+i_id+" .edit-on").fadeIn().css("display","inline-block");
	$("#ul-nav-"+i_id+">div").css('width','100%');
	
	//Watch typing:
	$(document).keyup(function(e) {
		//Live Update the UI:
		update_showdown( $("#ul-nav-"+i_id+" .showdown") , $("#ul-nav-"+i_id+" textarea").val() );
		
		//Watch for action keys:
		if (e.ctrlKey && e.keyCode === 13){
			msg_save_edit(i_id);
		} else if (e.keyCode === 27) {
			msg_cancel_edit(i_id);
		}
	});
}

function msg_cancel_edit(i_id,success=0){
	//Revert editing:
	$("#ul-nav-"+i_id+" .edit-off").fadeIn().css("display","inline-block");
	$("#ul-nav-"+i_id+" .edit-on").hide();
	$("#ul-nav-"+i_id+">div").css('width','inherit');
	
	if(!success){
		//Revert text changes to original:
		var original = $("#ul-nav-"+i_id+" .original").text(); //Original content
		$("#ul-nav-"+i_id+" textarea").val(original); //Revert Textarea
		update_showdown( $("#ul-nav-"+i_id+" .showdown") , original ); //Revert UI
	}
}


function msg_save_edit(i_id){
	//Make sure there is some value:
	var i_message = $("#ul-nav-"+i_id+" textarea").val();
	if(i_message.length<1){
		alert('Message is required. Try again.');
		return false;
	}
	
	//Revert View:
	msg_cancel_edit(i_id,1);
	
	//Show loader:
	$("#ul-nav-"+i_id+" .edit-updates").html('<div><img src="/img/loader.gif" /></div>');
	
	//Update message:
	$.post("/marketplace/i_edit", {i_id:i_id, i_message:i_message}, function(data) {
		//Update UI to confirm with user:
		$("#ul-nav-"+i_id+" .edit-updates").html('<div>'+data+'</div>');
		
		//Update original:
		$("#ul-nav-"+i_id+" .original").text(i_message);
		
		//Disapper in a while:
		setTimeout(function() {
			$("#ul-nav-"+i_id+" .edit-updates>div").fadeOut();
	    }, 3000);
	});
}



$(document).ready(function() {
	
	$('#save_c_description').bind('input propertychange', function() {
		update_showdown($('#main_desc'),this.value);
	});
	$('#save_c_objective').bind('input propertychange', function() {
		update_showdown($('.c_objective'),this.value);
	});
	
	
	$('#i_message').keydown(function (e) {
		  if (e.ctrlKey && e.keyCode == 13) {
			  msg_create();
		  }
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
		update_i_showdown();
		update_c_overview_showdown();
	}
	
	//Load tooltips:
	$(function () {
		  $('[data-toggle="tooltip"]').addClass('').tooltip();
	});
	
	//Load Sortable, IF ADMIN:
	if(u_status>=2){
		if($('#list-outbound').length){
			load_sortable('outbound');
		}
		if($('#list-inbound').length){
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
		         return '<span class="suggest-prefix"><i class="fa fa-link" aria-hidden="true"></i> Link to</span> '+ suggestion._highlightResult.c_objective.value;
		      },
		      header: function(data) {
		    	  if(!data.isEmpty){
		    		  return '<a href="javascript:new_challenge(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><i class="fa fa-plus" aria-hidden="true"></i> Create</span> "'+data.query+'"'+'</a>';
		    	  }
		      },
		      empty: function(data) {
	    		  	  return '<a href="javascript:new_challenge(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><i class="fa fa-plus" aria-hidden="true"></i> Create</span> "'+data.query+'"'+'</a>';
		      },
		    }
	}]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
        	new_challenge($( "#addnode" ).val());
            return true;
        }
    });
});