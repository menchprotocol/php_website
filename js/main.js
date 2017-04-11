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

//Main search:
var index,client;

$( document ).ready(function() {
	
	//By default we do not load until user goes to search box:
	algolia_loaded = 0;
	$( "#mainsearch" ).focus(function() {
		if(!algolia_loaded){
			//Prevent second time loading:
			algolia_loaded = 1;
			//Assign to global variables:
			window.client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
			window.index = client.initIndex('nodes');
		}
	});
	
	
	$('#mainsearch').autocomplete({ hint: false }, [{
	    source: function(q, cb) {
	      index.search(q, { hitsPerPage: 7 }, function(error, content) {
	        if (error) {
	          cb([]);
	          return;
	        }
	        
	        cb(content.hits, content);
	      });
	    },
	    displayKey: 'value',
	    templates: {
	      suggestion: function(suggestion) {
	         return parents(parseInt(suggestion._highlightResult.grandpa_id.value)) + suggestion._highlightResult.value.value;
	      }
	    }
	}]).focus(function() {
		pop_search_open();
		
		//Handle window resize on focus:
		$( window ).resize(function() { pop_search_open(); });
	}).focusout(function() {
		//default width:
		$( ".search-block" ).css('width','120px');
	}).on('autocomplete:selected', function(event, suggestion, dataset) {
		window.location.replace("/"+suggestion._highlightResult.node_id.value);
	});
});



//The hover of link settings
$( ".node_details" ).hover(function() {
	$( this ).addClass('show_child');
}).mouseleave(function() {
	$( this ).removeClass('show_child');
});

//For any tooltips on any page
$(function () {
	$('[data-toggle="tooltip"]').tooltip();
});







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

