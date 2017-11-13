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
	$('[data-toggle="tooltip"]').addClass('').tooltip();
}

//Quill Text editor variables:
var setting_full = {
	modules: {
		syntax: true,
		toolbar: [
		  [{ 'header': [1, 2, false] }],
		  ['bold', 'underline' ],
		  ['link', 'blockquote', 'code-block', { 'list': 'ordered'}, { 'list': 'bullet' }], //'image', 
		]
	},
	theme: 'snow'
};

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
			'<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control" style="margin-top:3px;" placeholder="'+placeholder+'"></div>'+
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