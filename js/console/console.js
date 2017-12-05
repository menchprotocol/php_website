//Loadup Algolia:
client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
algolia_index = client.initIndex('bootcamps');

//To update fancy dropdown which is usually used for STATUS updates:
function update_dropdown(name,intvalue,count){
	//Update hidden field with value:
	$('#'+name).val(intvalue);
	//Update dropdown UI:
	$('#ui_'+name).html( $('#'+name+'_'+count).html() + '<b class="caret"></b>' );
	//Reload tooldip:
	$('[data-toggle="tooltip"]').tooltip();
}

//Define tip style:
var tips_button = '<span class="badge tip-badge"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';

function open_tip(intent_id){
	
	//See if this tip needs to be loaded:
	if(!$("div#content_"+intent_id).html().length){
		
		//Show loader:
		$("div#content_"+intent_id).html('<img src="/img/round_load.gif" class="loader" />');
		
		//Let's check to see if this user has already seen this:
		$.post("/api_v1/load_tip", { intent_id:intent_id } , function(data) {
			//Let's see what we got:
			if(data.success){
				//Load the content:
				$("div#content_"+data.intent_id).html('<div class="row"><div class="col-xs-6">'+tips_button+'</div><div class="col-xs-6" style="text-align:right;"><a href="javascript:close_tip('+data.intent_id+')" data-toggle="tooltip" title="Will minimize temporarily and would re-open next time your load this page." data-placement="left"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>'); //Show the same button at top for UX
				$("div#content_"+data.intent_id).append(data.help_content);
				
				//Reload tooldip:
				$('[data-toggle="tooltip"]').tooltip();
			}
	    });
	}
	
	//Expand the tip:
	$('#hb_'+intent_id).hide();
	$("div#content_"+intent_id).fadeIn();
}

function close_tip(intent_id){
	$("div#content_"+intent_id).hide();
	$('#hb_'+intent_id).fadeIn('slow');
}


//Function to load all help messages throughout the console:
$(document).ready(function() {	
	//Each Message includes two elements on the page:
	//<span class="help_button" intent-id="597"></span> Containing the button for the Help message that is always accessible by user even after they clicked the "Got It" button
	//<div id="content_597"></div> Where the actual message would be loaded on the page, which is usally close to the help button
	if($("span.help_button")[0]){
		var loaded_messages = [];
		var intent_id = 0;
		$( "span.help_button" ).each(function() {
			intent_id = parseInt($( this ).attr('intent-id'));
			if(intent_id>0 && $("div#content_"+intent_id)[0] && !(jQuery.inArray(intent_id,loaded_messages)!=-1)){
				//Its valid as all elements match! Let's continue:
				loaded_messages.push(intent_id);
				//Load the Tip icon so they can access the tip if they like:
				$('#hb_'+intent_id).html('<a class="tipbtn" href="javascript:open_tip('+intent_id+')">'+tips_button+'</a>'); //Load the button
			}
		});
	}
});

function mark_read(){
	$('#msgnotif').attr('href','#').css('color','#AAA');
	//Log Read engagement:
	$.post("/api_v1/mark_read", { botkey:'381488558920384' } , function(data) {
		//Update UI to confirm with user:
		if(data.length){
			$('#msgnotif').fadeOut();
		}
    });
}

function switch_to(hashtag_name){
	$('#topnav a[href="#'+hashtag_name+'"]').tab('show');
}

//To keep state of the horizontal menu using URL hashtags:
function focu_hash(the_hash){
	var hash = the_hash.substring(1); //Puts hash in variable, and removes the # character
  	//Open specific menu with a 100ms delay to fix TOP NAV bug
	//Detect if this Exists:
	if($('#'+hash+'.tab-pane').attr('class').indexOf("hidden")<=0){
		$('.tab-pane, #topnav > li').removeClass('active');
		$('#'+hash+'.tab-pane, #nav_'+hash).addClass('active');
	}
}

function update_hash(hash){
	window.location.hash = hash;
	if(!(typeof e === 'undefined')){
		e.preventDefault();
	}
}


function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

function copyToClipboard(elem) {
  // create hidden text element, if it doesn't already exist
  var targetId = "_hiddenCopyText_";
  var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
  var origSelectionStart, origSelectionEnd;
  if (isInput) {
      // can just use the original source element for the selection and copy
      target = elem;
      origSelectionStart = elem.selectionStart;
      origSelectionEnd = elem.selectionEnd;
  } else {
      // must use a temporary form element for the selection and copy
      target = document.getElementById(targetId);
      if (!target) {
          var target = document.createElement("textarea");
          target.style.position = "absolute";
          target.style.left = "-9999px";
          target.style.top = "0";
          target.id = targetId;
          document.body.appendChild(target);
      }
      target.textContent = elem.textContent;
  }
  // select the content
  var currentFocus = document.activeElement;
  target.focus();
  target.setSelectionRange(0, target.value.length);
  
  // copy the selection
  var succeed;
  try {
  	  succeed = document.execCommand("copy");
  } catch(e) {
      succeed = false;
  }
  // restore original focus
  if (currentFocus && typeof currentFocus.focus === "function") {
      currentFocus.focus();
  }
  
  if (isInput) {
      // restore prior selection
      elem.setSelectionRange(origSelectionStart, origSelectionEnd);
  } else {
      // clear temporary content
      target.textContent = "";
  }
  return succeed;
}

/* ******************************** */
/* ******************************** */
/* Simple List Management Functions */
/* ******************************** */
/* ******************************** */

function initiate_list(group_id,placeholder,prefix,current_items){

	//Is the ID on the page?
	if(!($('#'+group_id).length)){
		return false;
	}

	//Add the add line:
	$('#'+group_id).html('<div class="list-group-item list_input">'+
			'<div class="input-group">'+
			'<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control listerin" placeholder="'+placeholder+'"></div>'+
			'<span class="input-group-addon" style="padding-right:0;">'+
			'<span class="pull-right"><span class="badge badge-primary" style="cursor:pointer;"><i class="fa fa-plus" aria-hidden="true"></i></span></span>'+
			'</span>'+
			'</div>'+
			'</div>');
	
	//Initiate sort:
	var theobject = document.getElementById(group_id);
 	var sort = Sortable.create( theobject , {
 		  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
 		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
 		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
 		  onUpdate: function (evt/**Event*/){
 			  do_item_resort(group_id);
 		  }
 	});

 	//Add initial items:
	if(current_items.length>0){
		$.each(current_items, function( index, value ) {
    		add_item(group_id,prefix,value);
    	});
	}    	

 	//Also watch for the enter key:
 	$('#'+group_id+' input[type=text]').keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
        	add_item(group_id,prefix,null);
            return true;
        }
    });
    
    //And watch for the Add button click:
	$('#'+group_id+'>div .badge-primary').click(function (e) {
		add_item(group_id,prefix,null);
    });
}

function do_item_resort(group_id){
	//Fetch new sort:
 	var sort_rank = 0;
 	$( '#'+group_id+'>li' ).each(function() {
 		sort_rank++;
 		//Update sort handler:
 		var current_handler = $( this ).find( '.inline-level' ).html();
 		var handler_parts = current_handler.split("#");
 		$( this ).find( '.inline-level' ).html(handler_parts[0]+'#'+sort_rank);
 	});
}

function confirm_remove(element){
	var group_id = element.parent().parent().parent().attr('id');
	var r = confirm("Remove this item?");
	if (r == true) {
		element.parent().parent().remove();
		do_item_resort(group_id);
	}
}

function add_item(group_id,prefix,current_value){
	if($('#'+group_id+' input[type=text]').val().length>0 || (current_value && current_value.length>0)){
		var next_item = $( '#'+group_id+'>li' ).length + 1;
		var do_focus = false;
		if(!current_value || current_value.length<1){
			current_value = $('#'+group_id+' input[type=text]').val();
			do_focus = true;
		}
		$('#'+group_id+'>div').before( '<li class="list-group-item is_sortable">'+
				'<span class="pull-right"><i class="fa fa-sort"></i> &nbsp;<i class="fa fa-trash" onclick="confirm_remove($(this));"></i></span>'+
				'<span class="inline-level">'+prefix+' #'+next_item+'</span><span class="theitem">'+current_value+'</span>'+
				'</li>');
		
		//Reset input field and re-focus only if manually added:
		if(do_focus){
			$('#'+group_id+' input[type=text]').val('').focus();
		}
		
	} else {
		alert('Error: field is empty!');
	}
}

function fetch_submit(group_id){
	//Prepares the input values to be sent via AJAX for processing:
	var final_array = [];
	$( '#'+group_id+'>li' ).each(function() {
 		final_array.push($( this ).find( '.theitem' ).text());
 	});
 	return final_array;
}