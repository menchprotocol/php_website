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
algolia_index = client.initIndex('bootcamps');


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

function trigger_link_watch(link_id,prepend_url){
	
	if($( "#"+link_id ).val().length>0){
		$( "#ph_"+link_id ).html('<a href="'+prepend_url+$( "#"+link_id ).val()+'" class="link-view" target="_blank">Test <i class="fa fa-external-link" aria-hidden="true"></i></a>');
    } else {
    	$( "#ph_"+link_id ).html('');
    }
	
	$( "#"+link_id ).bind('change keyup', function () {
		if($( "#"+link_id ).val().length>0){
			$( "#ph_"+link_id ).html('<a href="'+prepend_url+$( "#"+link_id ).val()+'" class="link-view" target="_blank">Test <i class="fa fa-external-link" aria-hidden="true"></i></a>');
        } else {
        	$( "#ph_"+link_id ).html('');
        }
	});
}

function update_account(){
	
	if(!$('#u_fname').val().length){
		alert('Missing first name.');
		return false;
	} else if(!$('#u_lname').val().length){
		alert('Missing last name.');
		return false;
	} else if(!$('#u_email').val().length){
		alert('Missing email.');
		return false;
	} else if(!$('#u_image_url').val().length){
		alert('Missing profile picture url.');
		return false;
	} else if(!$('#u_gender').val().length){
		alert('Missing gender.');
		return false;
	} else if(!$('#u_country_code').val().length){
		alert('Missing country.');
		return false;
	} else if(!$('#u_timezone').val().length){
		alert('Missing time zone.');
		return false;
	} else if(!$('#u_language').val().length){
		alert('Missing language.');
		return false;
	}
	
	//Show spinner:
	$('.update_u_results').html('<span><img src="/img/loader.gif" /></span>').hide().fadeIn();
	
	$.post("/process/account_update", {
		
		u_id:$('#u_id').val(),
		u_fname:$('#u_fname').val(),
		u_lname:$('#u_lname').val(),
		u_email:$('#u_email').val(),
		u_phone:$('#u_phone').val(),
		u_image_url:$('#u_image_url').val(),
		u_gender:$('#u_gender').val(),
		u_country_code:$('#u_country_code').val(),
		u_current_city:$('#u_current_city').val(),
		u_timezone:$('#u_timezone').val(),
		u_language:$('#u_language').val(),
		u_bio:$('#u_bio').val(),
		u_tangible_experience:$('#u_tangible_experience').val(),
		
		u_password_current:$('#u_password_current').val(),
		u_password_new:$('#u_password_new').val(),
		
		u_website_url:$('#u_website_url').val(),
		u_linkedin_username:$('#u_linkedin_username').val(),
		u_github_username:$('#u_github_username').val(),
		u_twitter_username:$('#u_twitter_username').val(),
		u_youtube_username:$('#u_youtube_username').val(),
		u_fb_username:$('#u_fb_username').val(),
		u_instagram_username:$('#u_instagram_username').val(),
		u_quora_username:$('#u_quora_username').val(),
		u_stackoverflow_username:$('#u_stackoverflow_username').val(),
		u_skype_username:$('#u_skype_username').val(),
		u_medium_username:$('#u_medium_username').val(),
		u_dribbble_username:$('#u_dribbble_username').val(),
		
	} , function(data) {
		//Update UI to confirm with user:
		$('.update_u_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('.update_u_results').fadeOut();
	    }, 10000);
    });
}

//Bootcamp admin management features
function ba_add(){
	alert('feature under development');
}
function ba_open_modify(){
	alert('feature under development');
}
function ba_initiate_revoke(){
	alert('feature under development');
}
  

function save_c(){
	
	//JS Check for the required fields:
	if(!$('#c_objective').val().length){
		alert('ERROR: Primary Objective is required.');
		return false;
	} else if(!$('#pid').val().length){
		alert('ERROR: Missing pid.');
		return false;
	}
	
	var postData = {
		pid:$('#pid').val(),
		c_objective:$('#c_objective').val(),
		c_todo_overview:$('#c_todo_overview').val(),
		c_prerequisites:$('#c_prerequisites').val(),
		c_todo_bible:$('#c_todo_bible').val(),
		c_time_estimate:$('#c_time_estimate').val(),
	};
	
	//Show spinner:
	$('#save_c_results').html('<span><img src="/img/loader.gif" /></span>').hide().fadeIn();
	
	$.post("/process/intent_edit", postData , function(data) {
		//Update UI to confirm with user:
		$('#save_c_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('#save_c_results').fadeOut();
	    }, 10000);
    });
}


function bootcamp_create(){
	//Show processing:
	$( "#new_bootcam_result" ).html('<img src="/img/loader.gif" /> Processing...').hide().fadeIn();
	
	//Send for processing:
	$.post("/process/bootcamp_create", {c_primary_objective:$('#c_primary_objective').val()}, function(data) {
		//Append data to view:
		$( "#new_bootcam_result" ).html(data).hide().fadeIn();
	});
}

function save_settings(){
	alert('Saving settings not yet wired in. Will be done by end of this week.');
}



function r_process_create(){
	//Show processing:
	$( "#new_cohort_result" ).html('<img src="/img/loader.gif" /> Processing...').hide().fadeIn();
	
	//Send for processing:
	$.post("/process/cohort_create", {
		
		r_b_id:$('#r_b_id').val(), 
		r_start_date:$('#r_start_date').val(),
		
	}, function(data) {
		//Append data to view:
		$( "#new_cohort_result" ).html(data).hide().fadeIn();
	});
}

function save_r(){
	//Show spinner:
	$('#save_r_results').html('<span><img src="/img/loader.gif" /></span>').hide().fadeIn();
	
	//Save Scheduling iFrame content:
	document.getElementById('weekschedule').contentWindow.save_hours();
	
	//Save the rest of the content:
	$.post("/process/cohort_edit", {
		
		r_start_date:$('#r_start_date').val(), 
		r_usd_price:$('#r_usd_price').val(),
		r_id:$('#r_id').val(),
		r_min_students:$('#r_min_students').val(),
		r_max_students:$('#r_max_students').val(),
		r_closed_dates:$('#r_closed_dates').val(),
		r_status:$('#r_status').val(),
		
	} , function(data) {
		//Update UI to confirm with user:
		$('#save_r_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('#save_r_results').fadeOut();
	    }, 10000);
    });
}


function new_intent(c_objective){
	
	if(c_objective.length<1){
		alert('Missing name. Try again.');
		$('#addnode').focus();
		return false;
	}
	//Fetch needed vars:
	pid = $('#pid').val();
	c_id = $('#c_id').val();
	b_id = $('#b_id').val();
	var direction = ( is_outbound ? 'outbound' : 'inbound' );
	var next_level = $( "#next_level" ).val();
	
	//Set processing status:
    $( "#list-"+direction ).append('<a href="#" id="temp" class="list-group-item"><img src="/img/loader.gif" /> Adding... </a>');
	
    //Empty Input:
	$( "#addnode" ).val("").focus();
	
	//Update backend:
	$.post("/process/intent_create", {b_id:b_id, c_id:c_id, pid:pid, c_objective:c_objective, direction:direction, next_level:next_level}, function(data) {
		//Update UI to confirm with user:
		$( "#temp" ).remove();
		$( "#list-"+direction ).append(data);
		
		//Resort:
		if(direction=='outbound'){
			load_sortable(direction);
		}
		
		
		//Tooltips:
		$('[data-toggle="tooltip"]').addClass('').tooltip();
	});
}

//To update the dropdown:
function update_dropdown(name,intvalue,count){
	//Update hidden field with value:
	$('#'+name).val(intvalue);
	//Update dropdown UI:
	$('#ui_'+name).html( $('#'+name+'_'+count).html() + '<b class="caret"></b>' );
	//Reload tooldip:
	$('[data-toggle="tooltip"]').addClass('').tooltip();
}

//Triggered when clicked on the toggle direction
function link_lintent(target_id){
	//Fetch needed vars:
	pid = $('#pid').val();
	c_id = $('#c_id').val();
	b_id = $('#b_id').val();
	var direction = ( is_outbound ? 'outbound' : 'inbound' );
	var next_level = $( "#next_level" ).val();
	
	//Set processing status:
    $( "#list-"+direction ).append('<a href="#" id="temp" class="list-group-item"><img src="/img/loader.gif" /> Adding... </a>');
	
    //Empty Input:
	$( "#addnode" ).val("").focus();
	
	//Update backend:
	$.post("/process/intent_link", {b_id:b_id, c_id:c_id, pid:pid, target_id:target_id, direction:direction, next_level:next_level}, function(data) {
		//Update UI to confirm with user:
		$( "#temp" ).remove();
		$( "#list-"+direction ).append(data);
		
		//Resort:
		if(direction=='outbound'){
			load_sortable(direction);
		}
		
		//Tooltips:
		$('[data-toggle="tooltip"]').addClass('').tooltip();
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
				$.post("/process/media_sort", {new_sort:new_sort}, function(data) {
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


function intents_sort(direction){
	//Set processing status:
    $( "#list-"+direction+" .srt-"+direction ).html(' <img src="/img/loader.gif" />');
  
    //Fetch new sort:
    var new_sort = [];
	var sort_rank = 0;
	$( "#list-"+direction+">a" ).each(function() {
		sort_rank++;
		var cr_id = $( this ).attr('data-link-id');
		new_sort[sort_rank] = cr_id;
		
		//Update sort handler:
		var current_handler = $( "#cr_"+cr_id+" .inline-level" ).text();
		var handler_parts = current_handler.split("#");
		$( "#cr_"+cr_id+" .inline-level" ).text(handler_parts[0]+'#'+sort_rank);
	});
	
	//Update backend:
	$.post("/process/intents_sort", {c_id:$('#c_id').val(), new_sort:new_sort, sort_direction:direction}, function(data) {
		//Update UI to confirm with user:
		$( "#list-"+direction+" .srt-"+direction ).html(data);
		
		//Disapper in a while:
		setTimeout(function() {
	        $("#list-"+direction+" .srt-"+direction+">span").fadeOut();
	    }, 3000);
	});
}


function load_sortable(direction){
	
	//We do not support inbound sorting for now...
	if(direction=='inbound'){return false;}
	
	var thelist = document.getElementById("list-"+direction);
	var sort = Sortable.create( thelist , {
		  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
		  onUpdate: function (evt/**Event*/){
			  intents_sort(direction);
		  }
	});
}

function toggleview(object_key){
	
	if($('#'+object_key+' .pointer').hasClass('fa-caret-right')){
		
		//Opening an item!
		//Make sure all other items are closed:
		$('.pointer').removeClass('fa-caret-down').addClass('fa-caret-right');
		$('.toggleview').hide();
		//Now show this item:
		$('#'+object_key+' .pointer').removeClass('fa-caret-right').addClass('fa-caret-down');
		update_showdown($('.'+object_key),$('.'+object_key).html());
		
	} else if($('#'+object_key+' .pointer').hasClass('fa-caret-down')){
		//Close this specific item:
		$('#'+object_key+' .pointer').removeClass('fa-caret-down').addClass('fa-caret-right');
		$('.'+object_key).hide();
	}
	
	
	
	
}


function intent_unlink(cr_id,cr_title){
	//Stop href:
	var current_href = $('#cr_'+cr_id).attr("href");
	$('#cr_'+cr_id).attr("href", "#");
	
	//Double check:
	var r = confirm("Unlink "+cr_title+"?");
	if (r == true) {
	    //Delete and remove:
		$.post("/process/intent_unlink", {cr_id:cr_id}, function(data) {
			//Update UI to confirm with user:
			$( "#cr_"+cr_id ).html(data);			
			
			setTimeout(function() {
				//Disapper:
				$( "#cr_"+cr_id ).fadeOut().remove();
				
				//Update sort:
				intents_sort('outbound');
		    }, 1000);
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
	var pid = $('#pid').val();
	
	if(i_message.length<1 || pid<1){
		return false;
	}
	
	//Set processing status:
    $( "#message-sorting" ).append('<div id="temp"><div><img src="/img/loader.gif" /> Adding... </div></div>');
	
    //Empty Input:
	$( "#i_message" ).val("").focus();
	
	//Update backend:
	$.post("/process/media_create", {pid:pid, i_message:i_message}, function(data) {
		//Update UI to confirm with user:
		$( "#temp" ).remove();
		$( "#message-sorting" ).append(data);
		
		//Update showdown:
		update_i_showdown();
		
		//Resort:
		load_message_sorting();
	});
}

function media_delete(i_id){
	//Double check:
	var r = confirm("Delete Message?");
	if (r == true) {
	    //Delete and remove:
		$.post("/process/media_delete", {i_id:i_id}, function(data) {
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
	$.post("/process/media_edit", {i_id:i_id, i_message:i_message}, function(data) {
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

function contact_us(){
	
	//Show loader:
	$("#contact_results").html('<div><img src="/img/loader.gif" /></div>');
	
	//Update message:
	$.post("/process/contact_us", {
		your_name:$('#your_name').val(), 
		your_email:$('#your_email').val(), 
		your_message:$('#your_message').val()
	}, function(data) {
		
		if (data.indexOf('Error') <= -1)  {
			//No error was detected, empty fields:
			$('#your_name').val('');
			$('#your_email').val('');
			$('#your_message').val('');
		}
		
		//Update UI to confirm with user:
		$("#contact_results").html(data).hide().fadeIn();
		
	});
}

$(document).ready(function() {
	
	//Start date picker:
	$( function() {
	    $( "#r_start_date" ).datepicker({
	    	minDate : 2,
	    	beforeShowDay: function(date){
	    		  var day = date.getDay(); 
	    		  return [day == 1,""];
	    	},
		});
	});
	
	//Adjust #accordion after open/close to proper view point:
	$('#accordion').on('shown.bs.collapse', function (e) {
		if (typeof $('[name=' + e.target.id +']').offset() !== 'undefined') {
			$('html,body').animate({
				scrollTop: $('[name=' + e.target.id +']').offset().top - 40
			}, 150);			
		}
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
	
	//Load Sortable:
	if($('#list-outbound').length){
		load_sortable('outbound');
	}
	

	
	//Load Algolia:
	$( "#addnode" ).on('autocomplete:selected', function(event, suggestion, dataset) {
		
		link_lintent(suggestion.c_id);
		
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
		    		  return '<a href="javascript:new_intent(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><i class="fa fa-plus" aria-hidden="true"></i> Create</span> "'+data.query+'"'+'</a>';
		    	  }
		      },
		      empty: function(data) {
	    		  	  return '<a href="javascript:new_intent(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><i class="fa fa-plus" aria-hidden="true"></i> Create</span> "'+data.query+'"'+'</a>';
		      },
		    }
	}]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
        	new_intent($( "#addnode" ).val());
            return true;
        }
    });
});