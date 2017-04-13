//US Foundation Google Analytics:
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-92774608-1', 'auto');
ga('send', 'pageview');


function parents(grandpa_id){
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
	var parent_id = $("#parent_node_id").val();

	$( '<a href="/7" class="list-group-item context-menu-one"><span class="badge">1 <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>'+node_name+' <span class="link_count"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>7</span></a>' ).insertBefore( ".list_input" );
	$( "#addnode" ).focus().val("");

}
function link_node(child_id){
	var parent_id = $("#parent_node_id").val();
	//Give user the option to enter value:
	//var value = prompt("Enter optional value for this new link:", "");
	
	$( "#addnode" ).focus().val("");
	$( '<a href="/7" class="list-group-item context-menu-one"><span class="badge">1 <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>@Shervin<span class="sp"> </span>Enayati <span class="link_count"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>7</span></a>' ).insertBefore( ".list_input" );
	
	
	//alert(parent_id+' to '+child_id+' val: '+value);
}

function load_algolia(){
	window.client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
	window.index = client.initIndex('nodes');
}

//Main search:
var index,client;
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
	
	
	//Initiate Sortable
	if(parseInt($( "#is_moderator" ).val())){
		$( "#sortableChild" ).sortable({
			items: ".child-node",
			// handle: ".sort-handle", //Maybe later, for now its making the UI too busy!
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
				$.post("/api/update_sort", {node_id:$("#node_id").val(), new_sort:new_sort}, function(data) {
					//Update UI to confirm with user:
					$( "#sortconf" ).html(data);
					
					//Disapper in a while:
					setTimeout(function() {
				        $("#sortconf>span").fadeOut()
				    }, 3000);
			    });			
				
			}
	    });
	}
	
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
		    		  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Create</span> '+parents(parseInt($('#parent_id').val()))+data.query+'</a>';
		    	  }
		      },
		      empty: function(data) {
	    		  	  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="suggest-prefix"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Create</span> '+parents(parseInt($('#parent_id').val()))+data.query+'</a>';
		      },
		    }
		}]);
	
	

	
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
});


//Set some global values for editing:
var main_val, parent_val, parent_html;

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
	
	//Yellow bg & visible metadata:
	$('#link'+id).addClass('edit_mode').addClass('show_child_edit');	
	//Create the Cancel href:
	var cancel_href = 'javascript:discard_link_edit(' + key + ',' + id + ');';
	//Repurpose original edit link:
	$('#link'+id+" .edit_link").attr('href',cancel_href);
	//Add buttons:
	//TODO: Migrate this logic to node level so we can protect any node with a link to !DeleteProtected
	if(parents(id)){
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
	//Set value and focus:
	$('#link'+id+" .node_h1 textarea").focus().val(main_val);
	//Make parent link editable only if:
	if(key==0 && !parents(id)){
		
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
function discard_link_edit(key,id){
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
	if(key==0 && !parents(id)){
		$('#link'+id+" .node_top_node").html(parent_html);
	}
	
	//Also close possible delete warnings:
	cancel_delete_link(key,id);
}

function delete_link(key,id){
	//TODO Implement more stats on what would be deleted!
	$('#link'+id+" .a_delete").attr('href','javascript:cancel_delete_link(' + key + ',' + id + ');');
	//First fetch total number of children as this makes a difference:
	var count_children = parseInt($('#children_count').val());
	//TODO: The descriptions here can be improved to be more clear
	if(key==0 && count_children>0){
		var del_box = '<b style="color:#fe3c3c">You are about to delete this entire node:</b><br /><ul>'
			+'<li>Option 1: Make <b>'+parent_val+'</b> parent of all '+count_children+' children: <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', 2)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Delete</a></li>'
			+'<li>Option 2: Delete all '+count_children+' children & grandchildren: <a href="javascript:delete_link_confirmed(' + key + ',' + id + ', 3)"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>Nuclear</a></li>'
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
	
}





/*
var list = document.getElementById("child-nodes");
var sortable_active = 1;
Sortable.create( list , {
  animation: 300,
  handle: ".sort_handle",
  onUpdate: function (evt){
	  //Loop through the current child patterns to determine current list order:
	  var patterns_sort = [];
	  $('#child-patterns a').each(function( index ) {
		  patterns_sort.push( $(this).attr('node-id') );
	  });
	  //Send the data for database updating:
	  $.post( "/api/update_sort/"+$('#current-node-id').val() , { sort: patterns_sort }).done(function( data ) {
		  console.log( data );
	  });
	}
});
*/

