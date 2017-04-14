//US Foundation Google Analytics:
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-92774608-1', 'auto');
ga('send', 'pageview');


function parents(grandpa_id){
	grandpa_id = parseInt(grandpa_id);
	//A PHP version of this function is in us_helper.php
	switch(grandpa_id) {
    case 1:
    	return '@';
        break;
    case 2:
    	return '&';
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


function create_node(node_name){
	// node[0]['node_id'] (parent node)

	$( '<a href="/7" class="list-group-item context-menu-one"><span class="badge">1 <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>'+node_name+' <span class="link_count"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>7</span></a>' ).insertBefore( ".list_input" );
	$( "#addnode" ).focus().val("");

}


function link_node(child_id){
	child_id = parseInt(child_id);
	// node[0]['node_id'] (parent node)
	
	//Give user the option to enter value:
	//var value = prompt("Enter optional value for this new link:", "");
	
	$( "#addnode" ).focus().val("");
	$( '<a href="/7" class="list-group-item context-menu-one"><span class="badge">1 <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>@Shervin<span class="sp"> </span>Enayati <span class="link_count"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>7</span></a>' ).insertBefore( ".list_input" );
	
	
	//alert(parent_id+' to '+child_id+' val: '+value);
}



var index,client;
function load_algolia(index_name='nodes'){
	window.client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
	window.index = client.initIndex(index_name);
}




$( document ).ready(function() {
	
	//The hover of link settings
	$( ".node_details" ).hover(function() {
		$( this ).addClass('show_child');
	}).mouseleave(function() {
		$( this ).removeClass('show_child');
	});

	$('[data-toggle="tooltip"]').tooltip();
	
	//Prevent Node creation form submission
	$("#addnodeform").submit(function(e){
        e.preventDefault();
    });
	
	
	
	//By default we do not load until user goes to search box:
	algolia_loaded = 0;
	$( ".autosearch" ).focus(function() {
		if(!algolia_loaded){
			//Prevent second time loading:
			algolia_loaded = 1;
			//Assign to global variables:
			load_algolia();
		}
	});
	
	//Header search specific functions for UI and autocomplete result selection:
	$( "#mainsearch" ).on('autocomplete:selected', function(event, suggestion, dataset) {
		window.location.replace("/"+suggestion._highlightResult.node_id.value);
	}).focus(function() {
		pop_search_open();
		//Handle window resize on focus:
		$( window ).resize(function() { pop_search_open(); });
	}).focusout(function() {
		//default width:
		$( ".search-block" ).css('width','120px');
	}).autocomplete({ hint: false, keyboardShortcuts: ['s'] }, [{
	    source: function(q, cb) {
	      index.search(q, { hitsPerPage: 7 }, function(error, content) {
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
			//Ajax processing to link two nodes together
			link_node(suggestion._highlightResult.node_id.value);
		}).autocomplete({ hint: false, keyboardShortcuts: ['a'] }, [{
		    source: function(q, cb) {
			      index.search(q, { hitsPerPage: 7 }, function(error, content) {
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
			    		  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Create</span> '+parents(node[0]['grandpa_id'])+data.query+'</a>';
			    	  }
			      },
			      empty: function(data) {
		    		  	  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Create</span> '+parents(node[0]['grandpa_id'])+data.query+'</a>';
			      },
			    }
		}]);
		
		
		$( "#sortableChild" ).sortable({
			items: ".child-node",
			handle: ".sort-handle", //We have sorting for a #Goals only
			update: function( event, ui ) {
				//Set processing status:
				$( "#sortconf" ).html('<span class="saving"><img src="/img/loader.gif" /> Saving...</span>');
				//Fetch new sort:
				var new_sort = [];
				var sort_rank = 0;
				$( ".child-node" ).each(function() {
					//We would later use this to update DB:
					new_sort[sort_rank] = $( this ).attr('node-id');
					sort_rank++;
				});
				
				//Update backend:
				$.post("/api/update_sort", {node_id:node[0]['node_id'], new_sort:new_sort}, function(data) {
					//Update UI to confirm with user:
					$( "#sortconf" ).html(data);
					
					//Disapper in a while:
					setTimeout(function() {
				        $("#sortconf>span").fadeOut();
				    }, 3000);
			    });
			}
	    });
	}
});


//Set some global values for editing:
var main_val, parent_val, parent_html, key_global, id_global;

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
	main_val = $('#link'+id+" .node_h1").text();
	parent_val = $('#link'+id+" .node_top_node a").text();
	parent_html = $('#link'+id+" .node_top_node").html();
	key_global = key;
	id_global = id;
	
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
	
	//Wire the enter key on the textarea to save
	$('#link'+id+" .node_h1 textarea").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
        	save_link_updated(key,id);
            return true;
        }
    });
	
	//Set value and focus:
	$('#link'+id+" .node_h1 textarea").focus().val(main_val);
	//Make parent link editable only if:
	if(key==0 && !parents(node[0]['node_id'])){
		
		$('#link'+id+' .node_top_node').html('<input type="text" id="editparent" class="autosearch" value="'+parent_val+'" />');
		
		//Loadup search engine if not already:
		if(!algolia_loaded){
			//Prevent second time loading:
			algolia_loaded = 1;
			//Assign to global variables:
			load_algolia();
		}
		
		//Enable the edit auto search:
		$( '#link'+id+' #editparent' ).on('autocomplete:selected', function(event, suggestion, dataset) {
			//Set the value in a hidden field:
			$( '#link'+id ).attr('new-parent-id' , suggestion.node_id);
			//No more editing:
			$( '#link'+id+" #editparent" ).remove();
			//Show placeholder until real update happens upon submission:
			$( '#link'+id+" .node_top_node").html( '<a href="/'+suggestion.node_id+'">'+parents(parseInt(suggestion.grandpa_id)) + suggestion.value.replace(/\W/g, '')+'</a> <span class="edit_warning not_saved">(Not saved yet)</span>' );
		}).autocomplete({ hint: false, keyboardShortcuts: ['p'] }, [{
		    source: function(q, cb) {
		      index.search(q, { hitsPerPage: 7 }, function(error, content) {
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

//Wire the enter key on the textarea to save
$(document).keyup(function(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
    if (code==27) {
    	//In case its being edited:
    	discard_link_edit(key_global, id_global);
    	//In case the focus is on these inputs:
    	$( "#addnode" ).blur();
    	$( "#mainsearch" ).blur();
    }
});

function discard_link_edit(key,id,keep_parent=false){
	//Exit edit mode:
	$('#link'+id).attr('edit-mode','0');
	
	//Revert the link editing back to default:
	$('#link'+id).removeClass('edit_mode').removeClass('show_child_edit');
	
	//Repurpose original edit link:
	$('#link'+id+" .edit_link").attr('href','javascript:edit_link(' + key + ',' + id + ');');
	//Remove buttons:
	$('#link'+id+" .action_buttons").remove();
	
	//Reset input fields back to defualt values:
	$('#link'+id+" .node_h1").html(main_val);
	if(key==0 && !parents(node[0]['node_id']) && !keep_parent){
		$('#link'+id+" .node_top_node").html(parent_html);
	}
	
	//Also close possible delete warnings:
	cancel_delete_link(key,id);
}

function delete_link(key,id){
	//TODO Implement more stats on what would be deleted!
	$('#link'+id+" .a_delete").attr('href','javascript:cancel_delete_link(' + key + ',' + id + ');');
	//TODO: The descriptions here can be improved to be more clear
	if(key==0 && child_count>0){
		var del_box = '<b style="color:#fe3c3c">You are about to delete this entire node:</b><br /><ul>'
			+'<li>Option 1: Make <b>'+parent_val+'</b> parent of all '+child_count+' children: <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', 2)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete</a></li>'
			+'<li>Option 2: Delete all '+child_count+' children & grandchildren: <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', 3)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Nuclear</a></li>'
			+ '</ul>';
	} else {
		var del_box = '<b>Confirm:</b> '
			+'<a href="javascript:delete_link_confirmed(' + key + ',' + id + ', 1)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete</a>';
	}
	
	$('#link'+id+' .node_stats').append('<div id="delete_confirm">' + del_box + ' or <a href="javascript:cancel_delete_link(' + key + ',' + id + ')" style="color:#999;"><u>cancel</u></a></div>');
}
function cancel_delete_link(key,id){
	$('#link'+id+" .a_delete").attr('href','javascript:delete_link(' + key + ',' + id + ');');
	$('#link'+id+' .node_stats #delete_confirm').remove();
}
function delete_link_confirmed(key,id,type){
	/*
	 * "type" index:
	 * 
	 * 1 is for single/simple parent-link delete
	 * 2 is for node delete + assign children to parent
	 * 3 is for node delete + delete all children (Nuclear)
	 * 
	 * */
	discard_link_edit(key,id);
	
}


function save_link_updated(key,id){
	
	//Define variables:
	var new_value = $( '#link'+id+' .node_h1 textarea' ).val();
	var new_parent_id = parseInt($( '#link'+id ).attr('new-parent-id')); //This is optional!
	
	//Exit edit mode:
	discard_link_edit(key,id,(new_parent_id>0));
	if(new_parent_id>0){
		$('#link'+id+' .not_saved').remove(); //For the parent
	}
	
	//Update main values:
	$('#link'+id+" .node_h1").html(new_value);
	
	//Show processing in UI:
	$('#link'+id+" .hover>div").append('<span class="action_buttons saving"><img src="/img/loader.gif" /> Saving...</span>');

	//Prepare data for processing:
	var data = {
		key:key,
		id:id,
		new_parent_id:new_parent_id,
		new_value:new_value,
	};
	
	//Update backend:
	$.post("/api/update_link", data, function(data) {
		//Update UI to confirm with user:
		$('#link'+id+" .hover>div").html(data);
		
		//Disapper in a while:
		setTimeout(function() {
			$('#link'+id+" .hover>div>span").fadeOut();
	    }, 3000);
    });
	
	
	
}

