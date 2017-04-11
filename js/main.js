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
    default:
    	return '!';
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
			window.client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
			window.index = client.initIndex('nodes');
		}
	});
	
	
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
		         return '<span class="glyphicon glyphicon-link" aria-hidden="true"></span> '+parents(parseInt(suggestion._highlightResult.grandpa_id.value)) + suggestion._highlightResult.value.value;
		      },
		      header: function(data) {
		    	  if(!data.isEmpty){
		    		  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> "'+data.query+'" <span style="color:#999;">(Create Node)</span></a>';
		    	  }
		      },
		      empty: function(data) {
	    		  	  return '<a href="javascript:create_node(\''+data.query+'\')" class="add_node"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> "'+data.query+'" <span style="color:#999;">(Create Node)</span></a>';
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
	         return parents(parseInt(suggestion._highlightResult.grandpa_id.value)) + suggestion._highlightResult.value.value;
	      },
	    }
	}]);
});



function edit_link(key,id){
	//Yellow bg & visible metadata:
	$('#link'+id).addClass('edit_mode').addClass('show_child_edit');
	//Create the Cancel href:
	var cancel_href = 'javascript:discard_link_edit(' + key + ',' + id + ');';
	//Repurpose original edit link:
	$('#link'+id+" .edit_link").attr('href',cancel_href);
	//Add buttons:
	$('#link'+id+" .hover")
		.append('<div class="action_buttons"><a class="btn btn-primary btn-sm a_save" href="#" role="button">Save Intelligence</a><a class="btn btn-primary btn-sm a_cancel" href="'+cancel_href+'" role="button">Cancel</a><a class="a_delete" href="javascript:delete_link(' + key + ',' + id + ');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</a></div">');

	
}
function discard_link_edit(key,id){
	//Revert the link editing back to default:
	$('#link'+id).removeClass('edit_mode').removeClass('show_child_edit');
	//Repurpose original edit link:
	$('#link'+id+" .edit_link").attr('href','javascript:edit_link(' + key + ',' + id + ');');
	//Remove buttons:
	$('#link'+id+" .action_buttons").remove();
	
}

function delete_link(key,id){
	//TODO
	if(confirm('Are you really sure you want to delete this link?')){
		alert('deleted');
	} else {
		//Exit edit mode, assuming nothing else has been changed:
		discard_link_edit(key,id);
	}
	
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

