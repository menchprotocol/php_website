//US Foundation Google Analytics:
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-92774608-1', 'auto');
ga('send', 'pageview');



function search(term){
	//Docs: https://www.algolia.com/doc/api-client/javascript/getting-started/#quick-start
	var client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
	var index = client.initIndex('nodes');
	index.search(term, function(err, content) {
		$('.searchresults').html('<ul></ul>');
		for (var key in content.hits) {
			if (content.hits.hasOwnProperty(key)) {
				//Show in UI:
				$('.searchresults ul').append('<li>'+content.hits[key].name+'</li>');
			}
		}
	});
}

$( ".node_details" ).hover(function() {
	$( this ).addClass('show_child');
}).mouseleave(function() {
	$( this ).removeClass('show_child');
});


$("#MainSearch").on('change keydown paste input', function(){
	search($("#MainSearch").val());
});


$(function () {
	$('[data-toggle="tooltip"]').tooltip();
});


function pop_search_open(){
	 var win_width = $(window).width();
	 if(win_width>720){win_width=720;}
	 $( ".search-block" ).css('width',(win_width-110)+'px');
}

$( "#MainSearch" ).focus(function() {
	pop_search_open();
	//Switch results page.
	$( ".nonesearch" ).hide();
	$( ".searchresults" ).fadeIn().html('<span>Start typing...</span>');
}).focusout(function() {
	//default width:
	 $( ".search-block" ).css('width','153px');
	 
	//Switch results page.
	$( ".nonesearch" ).fadeIn();
	$( ".searchresults" ).hide();
});
$( window ).resize(function() {
	pop_search_open();
});

$.fn.editable.defaults.mode = 'inline';
$(document).ready(function() {
	//TODO: load editable if the user is admin:
    $('.editable').editable({
        escape: true,
        showbuttons: false,
        inputclass: 'edit_text_area',
        url: '/api/edit_value_string'
    });

    $('.editable').on('shown', function(e, editable) {
        //Set proper height for this box:
        $('.edit_text_area').css({"height":$(this).height()+"px"});
    });

    
    $('.editable').on('save', function(e, params) {
        //This would update the main ID to the newly inserted row
        if(params.response.parent==2){
            //This is a hashtag change, requires a page reload:
        	window.location = "/"+params.response.value_string;
        } else {
        	$(this).editable('option', 'pk', params.response.new_id);
        }
    });

    
    $( ".hashtagAddChild" ).focus(function() {
    	if($(this).val().length==0){
    		$(this).val('#');
    	}
    });

    $( ".hashtagAddChild" ).focusout(function() {
    	if($(this).val()=='#'){
    		$(this).val('');
    	}
    });
    

	//Give the screen a nice Fade in effect:
	//$('#main_container').hide().fadeIn();
	
	//Render auto complete inputs, if any:
	initiate_nodesearch_autocomplete(".hashtagAddChild");
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


/*
 * 
Editable text image creator: http://www.text2image.com/html5_canvas.html 
*/

//A function for getCursorPosition()
(function ($, undefined) {
    $.fn.getCursorPosition = function() {
        var el = $(this).get(0);
        var pos = 0;
        if('selectionStart' in el) {
            pos = el.selectionStart;
        } else if('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
})(jQuery);







function extract_hashtag(inputstring,handler){
	var hshtgs = (inputstring.match(/#/g) || []).length; //Total number of hashtags found in the string
	var arr = inputstring.split('#');
	var pos = handler.getCursorPosition();
	
	return inputstring;
}



function initiate_nodesearch_autocomplete(object_selector){
	//Loop through all the auto complete fields on the page:
	$(object_selector).each(function() {
		var handler = $(object_selector);
		handler.easyAutocomplete({
			url: function(phrase) {
				return "/api/autocomplete/?parentscope="+handler.attr('parentscope')+"keyword="+extract_hashtag(phrase,handler);
			},
			getValue: function(element) {
				return element.value_string.replace(/(<([^>]+)>)/ig,"").trim();
			},
			requestDelay: 0, //Milliseconds
			highlightPhrase: true,
			/*placeholder: "Search for pattern ID",*/
			template: {
				type: "custom",
				method: function(value, item) {
					if(item.parent==2){
						return '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> #'+item.value_string;
					} else if(item.parent==7){
						return item.value_string;
					}
				}
			},
			list: {
				onChooseEvent: function() {
					var object_id = handler.getSelectedItemData().id;
					var object_id = handler.getSelectedItemData().id;
					if(object_id>0){
						switch (handler.attr('performaction')) {
						 case "add_parent":
						 case "add_child":
							window.location = "/api/quick_link/?performaction="+handler.attr('performaction')+"&currentnode="+handler.attr('currentnode')+"&hashtagId="+object_id;
							break;
						  default:
							//For now, default is to insert ID in the input field
							//alert( '"' + handler.getSelectedItemData().hashtag.replace(/(<([^>]+)>)/ig,"").trim() + '" selected with ID ' + object_id);
							//TODO: Later update to a fancier UI that shows name, and with click enables ID change
							handler.val(object_id);
						}
					}
				},
				match: {
					enabled: true
				},
				sort: {
					enabled: true
				}
			}
		});
	});
}

