//US Foundation Google Analytics:
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-92774608-1', 'auto');
ga('send', 'pageview');

function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function parents(grandpa_id){
	grandpa_id = parseInt(grandpa_id);
	//A PHP version of this function is in us_helper.php
	switch(grandpa_id) {
    case 1:
    	return '@';
        break;
    case 3:
    	return '#';
        break;
    case 4:
    	return '?';
        break;
    case 43:
    	return '!';
        break;
    default:
    	return null;
	}
}

//Expands search input when focused
function pop_search_open(){
	 var win_width = $(window).width();
	 if(win_width>720){win_width=720;}
	 $( ".search-block" ).css('width',(win_width-125)+'px');
}


var algolia_index,client,algolia_loaded;
algolia_loaded = 0;
function load_algolia(index_name='nodes'){
	if(algolia_loaded){
		return false;
	}
	algolia_loaded = 1; //Do not load again.
	client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
	algolia_index = client.initIndex(index_name);
}



function editHeightControl(){
	if(/<[a-z][\s\S]*>/i.test($(".node_details textarea").val())){
		//This means this text contains HTML, show smaller font:
		$(".node_details textarea").addClass('codefont');
	}
	
	$('.node_details').on( 'change keyup keydown paste cut', 'textarea', function (){
	    $(this).height(0).height(this.scrollHeight);
	}).find( 'textarea' ).change();
}



$( document ).ready(function() {
	
	//The hover of link settings
	$( ".node_details" ).hover(function() {
		$( this ).addClass('show_child');
	}).mouseleave(function() {
		$( this ).removeClass('show_child');
	});
	
	//Loaded by default for easier use:
	$('[data-toggle="tooltip"]').tooltip();
	
	//Prevent Node creation form submission
	$("#addnodeform").submit(function(e){
        e.preventDefault();
    });
	
	//By default we do not load until user goes to search box:
	$( ".autosearch" ).focus(function() {
		load_algolia();
	});
	
	//Header search specific functions for UI and autocomplete result selection:
	$( "#mainsearch" ).on('autocomplete:selected', function(event, suggestion, dataset) {
		location.replace("/"+suggestion.node_id+'?from=search');
	}).focus(function() {
		pop_search_open();
		//Handle window resize on focus:
		$( window ).resize(function() { pop_search_open(); });
	}).focusout(function() {
		//default width:
		$( ".search-block" ).css('width','120px');
	}).autocomplete({ hint: false, keyboardShortcuts: ['s'] }, [{
	    source: function(q, cb) {
	      algolia_index.search(q, { hitsPerPage: 7 }, function(error, content) {
	        if (error) {
	          cb([]);
	          return;
	        }
	        cb(content.hits, content);
	      });
	    },
	    /*displayKey: 'value',*/
	    displayKey: function(suggestion) { return "" },
	    templates: {
	      suggestion: function(suggestion) {
	         return parents(parseInt(suggestion.grandpa_id)) + suggestion._highlightResult.value.value;
	      },
	    }
	}]);
	
	//Initiate Sortable for moderators and for certain parents
	//TODO move logic to #SortableNodes
	if(user_data['is_mod']){
		//New Node search box call to action: 
		$( "#addnode" ).on('autocomplete:selected', function(event, suggestion, dataset) {
			//Link nodes together:
			link_node(suggestion.node_id, (parents(parseInt(suggestion.grandpa_id))+suggestion.value) );
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
			         return '<span class="suggest-prefix"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Link to</span> '+parents(parseInt(suggestion.grandpa_id)) + suggestion._highlightResult.value.value;
			      },
			      header: function(data) {
			    	  if(!data.isEmpty){
			    		  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</span> '+parents(node[0]['grandpa_id'])+data.query+' <span class="boldbadge badge pink-bg-light" style="float:none; display:inline-block; margin-bottom:5px;">DIRECT OUT<span class="glyphicon glyphicon-arrow-up rotate45" aria-hidden="true"></span></span> <span class="grey">from '+node[0]['sign']+node[0]['value'].replace(/\s/g, '')+'</span></a>';
			    	  }
			      },
			      empty: function(data) {
		    		  	  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Create</span> '+parents(node[0]['grandpa_id'])+data.query+'</a>';
			      },
			    }
		}]).keypress(function (e) {
	        var code = (e.keyCode ? e.keyCode : e.which);
	        if (code == 13) {
	        	create_node($( "#addnode" ).val());
	            return true;
	        }
	    });
	}
});


//Set some global values for editing:
var main_val, parent_val, parent_html, key_global, id_global, original_parent_val;

function edit_link(key,id){
	//TODO: CSS tweaks so when edit button is hit, no change in position is noticed acrossed popular browsers
	//Start by closing all open edits.
	//We do this to encourage focus on a single task at a time.
	$( ".node_details" ).each(function( index ) {
		//Only close if its open:
		if($( this ).attr('edit-mode')=='1'){
			discard_link_edit($( this ).attr('data-link-index'),$( this ).attr('data-link-id'));
		}
	});
	
	//Enter edit mode for this link:
	$('#link'+id).attr('edit-mode','1');
	
	//Set variables:
	//Get main value from core node:
	for(var i in node) {
	    if(node[i]['id']==id){
	    	//Does not consider PHP templating in load_node.php
	    	main_val = node[i]['value'];
	    	break;
	    }
	}	
	
	parent_val = $.trim($('#link'+id+" .parentTopLink .anchor").text());
	parent_html = $('#link'+id+" .node_top_node").html();
	key_global = key;
	id_global = id;
	original_parent_val = $('#tl'+id).text();
	
	//Hide potential followupContent:
	$('.followupContent').hide();
	
	//Yellow bg & visible metadata:
	$('#link'+id).addClass('edit_mode').addClass('show_child_edit');	
	//Create the Cancel href:
	var cancel_href = 'javascript:discard_link_edit(' + key + ',' + id + ');';
	//Repurpose original edit link:
	$('#link'+id+" .edit_link").attr('href',cancel_href);
	//Add buttons:
	//TODO: Migrate this logic to node level so we can protect any node with a link to !DeleteProtected
	if(parents(node[0]['node_id']) && key==0){
		var delete_button = '<span><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> !DeleteProtected</span>';		
	} else {
		var delete_button = '<a class="a_delete" href="javascript:delete_link(' + key + ',' + id + ');"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete</a>';
	}
	//Additional fixed buttons:
	var save_button = '<a class="btn btn-primary btn-sm a_save" href="javascript:save_link_updated(' + key + ',' + id + ');" role="button">Save</a>';
	var cancel_button = '<a class="a_cancel" href="'+cancel_href+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</a>';
	//Create action buttons:
	$('#link'+id+" .hover>div").append('<div class="action_buttons">'+save_button+cancel_button+delete_button+'</div">');
	
	//Make primary value editable:
	var height = (  $('#link'+id+" .node_h1").height() <24 ? 24 : $('#link'+id+" .node_h1").height() );
	$('#link'+id+" .node_h1").html('<textarea style="height:'+height+'px; font-weight:'+( key==0 ? 'bold' : 'normal')+';"></textarea>');
	
	//Update parent only if this is a direct link:
	if(parseInt($( '#link'+id ).attr('is-direct'))){
		$('#link'+id+" .node_h1 textarea").bind('input propertychange', function() {
			$('#tl'+id).text($('#link'+id+" .node_h1 textarea").val());
		});
	}
		
	
	//Wire the enter key on the textarea to save
	$('#link'+id+" .node_h1 textarea").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        //Ctrl+Enter to save:
        if (e.ctrlKey && code>=10 && code<=13) {
        	save_link_updated(key,id);
            return true;
        }
    });
	
	//Set value and focus:
	$('#link'+id+" .node_h1 textarea").focus().val(main_val);
	//Adjust textarea height:
	editHeightControl();
	
	
	//Make parent link editable only if:
	if(key==0 && !parents(node[0]['node_id'])){
		
		$('#link'+id+' .node_top_node').html('<input type="text" id="editparent" class="autosearch" value="'+parent_val+'" />');
		
		//Loadup search engine if not already:
		load_algolia();
		
		//Enable the edit auto search:
		$( '#link'+id+' #editparent' ).on('autocomplete:selected', function(event, suggestion, dataset) {
			//Set the value in a hidden field:
			$( '#link'+id ).attr('new-parent-id' , suggestion.node_id);
			//No more editing:
			$( '#link'+id+" #editparent" ).remove();
			//Show placeholder until real update happens upon submission:
			$( '#link'+id+" .node_top_node").html( '<a href="/'+suggestion.node_id+'">'+parents(parseInt(suggestion.grandpa_id)) + suggestion.value.replace(/\W/g, '')+'</a> <span class="edit_warning not_saved">(Not saved yet)</span>' );
		}).autocomplete({ hint: false }, [{
		    source: function(q, cb) {
		      algolia_index.search(q, { hitsPerPage: 7 }, function(error, content) {
		        if (error) {
		          cb([]);
		          return;
		        }
		        cb(content.hits, content);
		      });
		    },
		    /*displayKey: 'value',*/
		    displayKey: function(suggestion) { return "" },
		    templates: {
		      suggestion: function(suggestion) {
		         return parents(parseInt(suggestion.grandpa_id)) + suggestion._highlightResult.value.value;
		      },
		    }
		}]);
	}
}

var lastToggleId = 0;
//Wire the enter key on the textarea to save
$(document).keyup(function(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
    if (code==27) {
    	//In case its being edited:
    	if(id_global>0){
    		discard_link_edit(key_global, id_global);
    	} else if(lastToggleId>0){
    		//Close last open:
    		toggleValue(lastToggleId);
    	}   	
    	
    	//In case the focus is on these inputs:
    	$( "#addnode" ).blur().val("");
    	$( "#mainsearch" ).blur().val("");
    }
});

function toggleValue(link_id){
	if(lastToggleId!=link_id){
		lastToggleId = link_id;
	} else {
		lastToggleId = 0;
	}
	
	$('#linkval'+link_id).toggle();
	
	if($( '.gh'+link_id ).hasClass( "glyphicon-triangle-bottom" )){
		$( '#link'+link_id+' .parentLink' ).removeClass('zoom-out').addClass('zoom-in');
		$( '.gh'+link_id ).removeClass('glyphicon-triangle-bottom');
		$( '.gh'+link_id ).addClass('glyphicon-triangle-right');
	} else if($( '.gh'+link_id ).hasClass( "glyphicon-triangle-right" )){
		$( '#link'+link_id+' .parentLink' ).removeClass('zoom-in').addClass('zoom-out');
		$( '.gh'+link_id ).removeClass('glyphicon-triangle-right');
		$( '.gh'+link_id ).addClass('glyphicon-triangle-bottom');
	}
}

function discard_link_edit(key,id,keep_parent=false,origin='default'){
	//Reset global variables:
	key_global = 0;
	id_global = 0;
	
	//Exit edit mode:
	$('#link'+id).attr('edit-mode','0');
	
	//Revert the link editing back to default:
	$('#link'+id).removeClass('edit_mode').removeClass('show_child_edit');
	
	//Repurpose original edit link:
	$('#link'+id+" .edit_link").attr('href','javascript:edit_link(' + key + ',' + id + ');');
	//Remove buttons:
	$('#link'+id+" .action_buttons").remove();
	
	//Show potential followupContent:
	$('.followupContent').fadeIn();
	
	if(origin!='save'){
		//Resent parent value
		$('#tl'+id).text(original_parent_val);
	}
	
	//Reset input fields back to defualt values:
	$('#link'+id+" .node_h1").html(nl2br(main_val));
	if(key==0 && !parents(node[0]['node_id']) && !keep_parent){
		$('#link'+id+" .node_top_node").html(parent_html);
	}
	
	//Also close possible delete warnings:
	cancel_delete_link(key,id);
}

function toggleSort(doEnable,sortType){
	
	if(sortType=='child'){
		if(doEnable && $('#sortIsOn').hasClass('sortTypechild')){
			return false;
		}
		var main_class = 'is_children';
		var colorClass = 'pink';
		var oppositeIsOn = $('#sortIsOn').hasClass('sortTypeparent');
	} else if(sortType=='parent') {
		if(doEnable && $('#sortIsOn').hasClass('sortTypeparent')){
			return false;
		}
		var main_class = 'is_parents';
		var colorClass = 'blue';
		var oppositeIsOn = $('#sortIsOn').hasClass('sortTypechild');
	}
	
	
		
	
	
	
	if(doEnable){
		
		if(oppositeIsOn){
			//We need to do some adjustments:
			$('#sortIsOn').remove();
			$('.'+(sortType=='child' ? 'is_parents' : 'is_children' )+' .glyphicon-sort').remove();
		}
		
		//Enable sort:
		$('#secondNav').append('<li role="presentation" id="sortIsOn" class="li_setting pull-right disabled sortType'+sortType+'"><a href="javascript:void(0)" class="disabled '+colorClass+'-bg"><span class="glyphicon glyphicon glyphicon-sort sort-handle" aria-hidden="true"></span> Sort On</a></li>');
		$('.'+main_class+' .sortconf').before('<span class="glyphicon glyphicon glyphicon-sort '+colorClass+'" aria-hidden="true"></span>');
		$('.parentTopLink>.glyphicon-sort').remove(); //Removes the sort for top node as that is not sortable!
		
		$( ".list-group" ).sortable({
			items: ".child-node",
			handle: ".list-group-item-heading", //The entire href line. .sort-handle is the icon.
			update: function( event, ui ) {
				//Set processing status:
				$( ".sortconf" ).html('<span class="saving"><img src="/img/loader.gif" /></span>');
				//Fetch new sort:
				var new_sort = [];
				var sort_rank = 0;
				$( ".child-node" ).each(function() {
					//We would later use this to update DB:
					if($( this ).attr('data-link-index')>0){ //This excludes TOP link from getting into sort, as it should remain TOP.
						new_sort[sort_rank] = $( this ).attr('data-link-id');
						sort_rank++;
					}
				});
				
				//Update backend:
				$.post("/api/update_sort", {node_id:node[0]['node_id'], new_sort:new_sort, sortType:sortType}, function(data) {
					//Update UI to confirm with user:
					$( ".sortconf" ).html(data);
					
					//Disapper in a while:
					setTimeout(function() {
				        $(".sortconf>span").fadeOut();
				    }, 3000);
			    });
			}
	    });
		
	} else {
		//Disable sort:
		$('#sortIsOn').remove();
		$('.'+main_class+' .glyphicon-sort').remove();
		$( ".list-group" ).sortable( "destroy" );
	}
}


function nav2nd(focus_nav){
	$('.nav-pills li').removeClass('active');
	$('.nav-pills .li_'+focus_nav).addClass('active');
	
	if(focus_nav=='all'){
		$(".node_details").show();
	} else {
		$(".node_details").hide();
		$(".is_top, .is_"+focus_nav).show();
	}
	
	//Enable sortable for child/parents:
	if(user_data['is_mod']){
		if(focus_nav=='children'){
			//toggleSort(0,'parent');
			toggleSort(1,'child');
		} else if(focus_nav=='parents'){
			toggleSort(1,'parent');
			//toggleSort(0,'child');
		} else {
			toggleSort(0,'parent');
			toggleSort(0,'child');
		}
	}
}

function delete_link(key,id){
	
	//TODO Implement more stats on what would be deleted!
	$('#link'+id+" .a_delete").attr('href','javascript:cancel_delete_link(' + key + ',' + id + ');');
	
	//TODO: The descriptions here can be improved to be more clear
	if(key==0){
		if(child_count>0){
			var del_box = '<b style="color:#fe3c3c">You are about to delete this entire node:</b><br /><ul style="list-style:decimal; margin-left:-20px;">'
				+'<li>Move children to <span id="setdelparentcontainer" node-id="'+node[0]['parent_id']+'"><input type="text" id="setdeleteparent" class="autosearch" value="'+parent_val+'" /></span>: <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', -3)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete</a></li>'
				+'<li>Delete '+child_count+' children & all their grandchildren: <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', -4)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Nuclear</a></li>'
				+ '</ul>';
		} else {
			var del_box = '<b style="color:#fe3c3c">You are about to delete this entire node:</b><br /><b>Confirm:</b> <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', -2)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete</a>';
		}
	} else {
		var del_box = '<b>Confirm:</b> '
			+'<a href="javascript:delete_link_confirmed(' + key + ',' + id + ', -1)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete</a>';
	}
	
	$('#link'+id+' .node_stats').append('<div id="delete_confirm">' + del_box + ' or <a href="javascript:cancel_delete_link(' + key + ',' + id + ')" style="color:#999;"><u>cancel</u></a></div>');


	if(key==0 && child_count>0){
		//Loadup search engine if not already:
		load_algolia();
		
		//Enable searching for a new parent:
		$( '#link'+id+' #setdeleteparent' ).on('autocomplete:selected', function(event, suggestion, dataset) {
			//Set new id:
			$( '#link'+id+' #setdelparentcontainer' ).attr('node-id' , suggestion.node_id);
			
			//Set HTML without any further editing options:
			$( '#link'+id+' #setdelparentcontainer' ).html(parents(parseInt(suggestion.grandpa_id)) + suggestion.value.replace(/\W/g, ''));
			
		}).autocomplete({ hint: false }, [{
		    source: function(q, cb) {
		      algolia_index.search(q, { hitsPerPage: 7 }, function(error, content) {
		        if (error) {
		          cb([]);
		          return;
		        }
		        cb(content.hits, content);
		      });
		    },
		    /*displayKey: 'value',*/
		    displayKey: function(suggestion) { return "" },
		    templates: {
		      suggestion: function(suggestion) {
		         return parents(parseInt(suggestion.grandpa_id)) + suggestion._highlightResult.value.value;
		      },
		    }
		}]);
		
		//Adjust CSS:
		$( '#link'+id+' .algolia-autocomplete' ).attr('style','position: relative; display:inline; direction: ltr;');
	}

}
function cancel_delete_link(key,id){
	$('#link'+id+" .a_delete").attr('href','javascript:delete_link(' + key + ',' + id + ');');
	$('#link'+id+' .node_stats #delete_confirm').remove();
}
function delete_link_confirmed(key,id,type){
	/*
	 * See helper function action_type_descriptions() for "type" index
	 * 
	 * */
	

	//Prepare data for processing:
	var input_data = {
		id:id,
		node_id:parseInt(node[0]['node_id']),
		parent_id: parseInt(( type==-3 ? $( '#link'+id+' #setdelparentcontainer' ).attr('node-id') : node[0]['parent_id'] )),
		type:type,
		node_name:node[0]['sign']+node[0]['value'],
		child_count:child_count,
	};
		
	//Show processing:
	$('#link'+id).html('<span class="saving"><img src="/img/loader.gif" /> Deleting...</span>');
	
	//Update backend:
	$.post("/api/delete", input_data, function(data) {
		if(type==-1){
			//Update UI to confirm with user:
			$('#link'+id).html(data);
			//Disapper in a while:
			setTimeout(function() {
				$('#link'+id).fadeOut();
		    }, 3000);
		} else {
			//Redirect to parent node as the entire node has been deleted:
			location.replace("/"+input_data['parent_id']+'?from='+node[0]['node_id']);
		}
    });	
}


function save_link_updated(key,id){
	//Define variables:
	var new_value = $( '#link'+id+' .node_h1 textarea' ).val();
	var new_parent_id = parseInt($( '#link'+id ).attr('new-parent-id')); //This is optional!
	
	//Exit edit mode:
	discard_link_edit(key,id,(new_parent_id>0),'save');
	if(new_parent_id>0){
		$('#link'+id+' .not_saved').remove(); //For the parent
	}
	
	//Update main values:
	$('#link'+id+" .node_h1").html(nl2br(new_value));
	
	//Show processing in UI:
	$('#link'+id+" .hover>div").append('<span class="action_buttons saving"><img src="/img/loader.gif" /> Saving...</span>');

	//Prepare data for processing:
	var input_data = {
		key:key,
		id:id,
		new_parent_id:new_parent_id,
		new_value:new_value,
	};
	
	//Update backend:
	$.post("/api/update_link", input_data, function(data) {
		//Update UI to confirm with user:
		$('#link'+id+" .hover>div").html(data);
		
		//Disapper in a while:
		setTimeout(function() {
			$('#link'+id+" .hover>div>span").fadeOut();
	    }, 3000);
    });
}



function create_node(node_name){
	if(node_name.length<1){
		return false;
	}
	
	//Show loader:
	$( '<div class="list-group-item loading-node"><img src="/img/loader.gif" /> Saving...</div>' ).insertBefore( ".list_input" );
	
	//Prepare data for processing:
	child_count += 1;
	
	var input_data = {
		grandpa_id:node[0]['grandpa_id'],
		parent_id:node[0]['node_id'],
		value:node_name,
		ui_rank:child_count,
	};
	
	//Create node:
	$.post("/api/create_node", input_data, function(data) {
		//Update UI to confirm with user:
		
		$( ".loading-node" ).remove();
		$( data.message ).insertBefore( ".list_input" );
		
		//Expand current node:
		node = data.node;
		
		//Empty search value and focus on it:
		$( "#addnode" ).focus().val("");
    });
}




function link_node(child_node_id,new_name){
	
	child_node_id = parseInt(child_node_id);
	if(child_node_id<1){
		return false;
	}
	
	if($('#linkNodeModal').length>0) {
		$('#linkNodeModal').remove();
	}
	
	$('#linkNodeModal').remove();
	$('body').append('<div class="modal fade" id="linkNodeModal" tabindex="-1" role="dialog">'
  +'<div class="modal-dialog" role="document">'
    +'<div class="modal-content">'
      +'<div class="modal-body">'
            
      +'<div class="form-group">'
      +'<label for="exampleInputEmail1">Optional Value</label>'
      +'<textarea id="newVal" tabindex="1" class="form-control" rows="3"></textarea>'
      +'</div>'
      
      +'<div class="form-group">'
	      + '<div><label for="exampleInputEmail1">Who is Parent?</label></div>'
	      + '<label class="radio">'
	      + '<input type="radio" tabindex="2" name="parentNodeName" id="originalValue" checked="checked"> '+parents(node[0]['grandpa_id'])+node[0]['value']
	      + '</label>'
	      + '<label class="radio">'
	      + '<input type="radio" tabindex="3" name="parentNodeName" id="childValue"> '+new_name
	      + '</label>'
	  +'</div>'
        
      +'</div>'
      +'<div class="modal-footer">'
        +'<button type="button" id="modalCancel" class="btn btn-default" data-dismiss="modal">Cancel</button>'
        +'<button type="button" tabindex="4" id="modalSubmit" class="btn btn-primary"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Link</button>'
      +'</div>'
    +'</div><!-- /.modal-content -->'
  +'</div><!-- /.modal-dialog -->'
+'</div><!-- /.modal -->');
	
	$('#linkNodeModal').modal('show').on('shown.bs.modal', function () {
		$("#newVal").focus(); //Focus on the optional value field
		
		//wire in key code for saving:
		$('#newVal').keypress(function (e) {
	        var code = (e.keyCode ? e.keyCode : e.which);
	        //Ctrl+Enter to save:
	        if (e.ctrlKey && code>=10 && code<=13) {
	        	$( "#modalSubmit" ).click();
	        }
	    });
	});
	
	
	//Setup listeners:
	$( "#modalCancel" ).click(function() {
		$( "#addnode" ).val("").focus();
	});
	
	$( "#modalSubmit" ).click(function() {
		
		//Prepare data for processing:
		child_count += 1;
		
		var input_data = {
			parent_id: parseInt( $('#originalValue').is(":checked") ? node[0]['node_id'] : child_node_id ),
			child_node_id: parseInt( $('#originalValue').is(":checked") ? child_node_id : node[0]['node_id'] ),
			normal_parenting: ( $('#originalValue').is(":checked") ? 1 : 0 ),
			value: $("#newVal").val(),
			ui_rank:child_count,
		};
		
		//Show loader:
		//TODO Improvement: We can position this at the botton of current parent Nodes, instead of at the bottom of child Nodes
		$( '<div class="list-group-item loading-node"><img src="/img/loader.gif" /> Saving...</div>' ).insertBefore( ".list_input" );
		$('#linkNodeModal').modal('hide');
		
		//Create node:
		$.post("/api/link_node", input_data, function(data) {
			//Update UI to confirm with user:
			$( ".loading-node" ).remove();
			$( data.message ).insertBefore( ".list_input" );
			
			//Expand current node:
			node = data.node;
			console.log('lets see');
			console.log(node);
	    });
	});
}