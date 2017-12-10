<?php
//Fetch the sprint units from config:
$sprint_units = $this->config->item('sprint_units');
$core_objects = $this->config->item('core_objects');
$udata = $this->session->userdata('user');
$has_tree = ($level<=2 && $intent['c_is_last']=='f');
?>
<style> .breadcrumb li { display:block; } </style>
<script>

function add_first_name(){
    $('#i_message').val($('#i_message').val()+' {first_name}');
    changeMessage();
}

//Count text area characters:
function changeMessage() {

	//Update count:
    var len = $('#i_message').val().length;
    if (len > 420) {
    	$('#charNum').addClass('overload').text(len);
    } else {
        $('#charNum').removeClass('overload').text(len);
    }

    //Passon data to detect URLs:
    /*
    $.post("/api_v1/detect_url", { text:val.value } , function(data) {
 		//Update data
 		if(data=='clear_url_preview'){
 			$('#url_preview').html("");
 		} else if(data.length>0){
 			$('#url_preview').html(data);
     	}
	});
	*/
}




  
$(document).ready(function() {

 
    <?php if($has_tree){ ?>
    
    function update_tree_input(){
    	var current_count = $("#list-outbound").children().length-1;
    	$('#addnode').attr("placeholder", "<?= ( $level==1 ? ucwords($bootcamp['b_sprint_unit']) : $core_objects['level_'.$level]['o_name'] ) ?> #"+(current_count+1)+" Objective (Specific & Measurable)");
    	return current_count;
    }
    
    var current_subtree = update_tree_input();
    $('#list-outbound').bind("DOMSubtreeModified",function(){
		if(($("#list-outbound").children().length+1) != current_subtree){
			//List has been adjusted, change the placeholder:
			current_subtree = update_tree_input();
		}
    });
    <?php } ?>
	
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
		focu_hash(window.location.hash);
    }
	
	//Load Sortable:
	load_intent_sort();
	load_message_sorting();

	//Load Nice sort for iPhone X
	$(".ix-msg").mCustomScrollbar({theme:"dark"});
	
	//Watch for message creation:
	$('#i_message').keydown(function (e) {
		if (e.ctrlKey && e.keyCode == 13) {
			msg_create();
		}
	});

	//Add new intent:
	$( "#addnode" ).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
        	new_intent($( "#addnode" ).val());
            return true;
        }
    });






	$('.box').find('input[type="file"]').change(function (){
		save_attachment(droppedFiles,'file');
    });
    
    if (isAdvancedUpload) {

      $('.box').addClass('has-advanced-upload');
      var droppedFiles = false;
    
      $('.box').on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
      })
      .on('dragover dragenter', function() {
        $('.add-msg').addClass('is-working');
      })
      .on('dragleave dragend drop', function() {
        $('.add-msg').removeClass('is-working');
      })
      .on('drop', function(e) {
        droppedFiles = e.originalEvent.dataTransfer.files;
        e.preventDefault();
        save_attachment(droppedFiles,'drop');
      });

    }

    
    
	//Load Algolia:
	/*
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
		         return '<span class="suggest-prefix"><i class="fa fa-eye" aria-hidden="true"></i> Link to</span> '+ suggestion._highlightResult.c_objective.value;
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
    */
});

/*
 * 
 * Intent functions 
 * 
 */


function save_c(){
 	
 	//JS Check for the required fields:
 	if(!$('#c_objective').val().length){
 		alert('ERROR: Primary Objective is required.');
 		return false;
 	} else if(!$('#pid').val().length){
 		alert('ERROR: Missing pid.');
 		return false;
 	} else if(!$('#b_id').val().length){
 		alert('ERROR: Missing b_id.');
 		return false;
 	}
 	
 	
 	var postData = {
		b_id:$('#b_id').val(),
 		b_sprint_unit:$('input[name=b_sprint_unit]:checked').val(),
 		pid:$('#pid').val(),
 		c_objective:$('#c_objective').val(),
 		c_status:$('#c_status').val(),
 		c_time_estimate:$('#c_time_estimate').val(),
 		c_is_last:(document.getElementById('c_is_last').checked ? 't' : 'f'),
 	};
 	
 	//Show spinner:
 	$('.save_c_results').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
 	
 	$.post("/api_v1/intent_edit", postData , function(data) {
 		//Update UI to confirm with user:
 		$('.save_c_results').html(data).hide().fadeIn();
 		
 		//Disapper in a while:
 		setTimeout(function() {
 			$('.save_c_results').fadeOut();
 	    }, 10000);
     });
}


function new_intent(c_objective){
 	
 	if(c_objective.length<1){
 		alert('Error: Missing Objective. Try Again...');
 		$('#addnode').focus();
 		return false;
 	}
 	//Fetch needed vars:
 	var pid = $('#pid').val();
 	var c_id = $('#c_id').val();
 	var b_id = $('#b_id').val();
 	var next_level = $( "#next_level" ).val();
 	
 	//Set processing status:
     $( "#list-outbound>div").before('<a href="#" id="temp" class="list-group-item"><img src="/img/round_load.gif" class="loader" /> Adding... </a>');
 	
     //Empty Input:
 	$( "#addnode" ).val("").focus();
 	
 	//Update backend:
 	$.post("/api_v1/intent_create", {b_id:b_id, c_id:c_id, pid:pid, c_objective:c_objective, next_level:next_level}, function(data) {
 		//Update UI to confirm with user:
 		$( "#temp" ).remove();

 		//Add new
 		$('#list-outbound>div').before(data);
 		
 		//Resort:
 		load_intent_sort();
 		
 		//Tooltips:
 		$('[data-toggle="tooltip"]').tooltip();
 	});
}

function link_lintent(target_id){
 	//Fetch needed vars:
 	var pid = $('#pid').val();
 	var b_id = $('#b_id').val();
 	var next_level = $( "#next_level" ).val();
 	
 	//Set processing status:
     $( "#list-outbound>div" ).before('<a href="#" id="temp" class="list-group-item"><img src="/img/round_load.gif" class="loader" /> Adding... </a>');
 	
     //Empty Input:
 	$( "#addnode" ).val("").focus();
 	
 	//Update backend:
 	$.post("/api_v1/intent_link", {b_id:b_id, pid:pid, target_id:target_id, next_level:next_level}, function(data) {
 		//Update UI to confirm with user:
 		$( "#temp" ).remove();

 		//Add new
 		$('#list-outbound>div').before(data);
 		
 		//Resort:
 		load_intent_sort();
 		
 		//Tooltips:
 		$('[data-toggle="tooltip"]').tooltip();
 	});
}



function intents_sort(){
 	//This function sorts the OUTBOUND intents
    $( "#list-outbound .srt-outbound").html(' <img src="/img/round_load.gif" class="loader" />');
   
    //Fetch new sort:
    var new_sort = [];
 	var sort_rank = 0;
 	$( "#list-outbound>a" ).each(function() {
 		sort_rank++;
 		var cr_id = $( this ).attr('data-link-id');
 		new_sort[sort_rank] = cr_id;
 		
 		//Update sort handler:
 		var current_handler = $( "#cr_"+cr_id+" .inline-level" ).html();
 		var handler_parts = current_handler.split("#");
 		$( "#cr_"+cr_id+" .inline-level" ).html(handler_parts[0]+'#'+sort_rank);
 	});
 	
 	//Update backend:
 	$.post("/api_v1/intents_sort", {pid:$('#pid').val(), b_id:$('#b_id').val(), new_sort:new_sort}, function(data) {
 		//Update UI to confirm with user:
 		$( "#list-outbound .srt-outbound").html(data);
 		
 		//Disapper in a while:
 		setTimeout(function() {
 	        $("#list-outbound .srt-outbound>span").fadeOut();
 	    }, 3000);
 	});
}


function load_intent_sort(){
	if(!($('#list-outbound').length)){
		return false;
	}
	var theobject = document.getElementById("list-outbound");
 	var sort = Sortable.create( theobject , {
 		  animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
 		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
 		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
 		  onUpdate: function (evt/**Event*/){
 			  intents_sort();
 		  }
 	});
}


 
function intent_unlink(cr_id,cr_title){
 	//Stop href:
 	var current_href = $('#cr_'+cr_id).attr("href");
 	var b_id = $('#b_id').val();
 	$('#cr_'+cr_id).attr("href", "#");
 	
 	//Double check:
 	var r = confirm("Remove "+cr_title+"?");
 	if (r == true) {
 	    //Delete and remove:
 		$.post("/api_v1/intent_unlink", {cr_id:cr_id, b_id:b_id}, function(data) {
 			//Update UI to confirm with user:
 			$( "#cr_"+cr_id ).html(data);			
 			
 			setTimeout(function() {
 				//Disapper:
 				$( "#cr_"+cr_id ).fadeOut().remove();
 				
 				//Update sort:
 				intents_sort();

 				//Update count:
 				update_tree_input();
 		    }, 1000);
 		});
 	} else {
 		//Put link back in:
 		setTimeout(function() {
 			$('#cr_'+cr_id).attr("href", "#").attr("href", current_href);
 	    }, 1000);
 	}
}


 

/*
 * 
 * Message functions 
 * 
 */


function load_message_sorting(){
	var theobject = document.getElementById("message-sorting");
	var sort_msg = Sortable.create( theobject , {
		  animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
		  onUpdate: function (evt/**Event*/){
			    //Set processing status:
			    //$( ".edit-updates" ).html('<img src="/img/round_load.gif" class="loader" />');
			  
			    //Fetch new sort:
			    var new_sort = [];
				var sort_rank = 0;
				$( "#message-sorting>div" ).each(function() {
					sort_rank++;
					new_sort[sort_rank] = $( this ).attr('iid');
				});
				
				//Update backend:
				$.post("/api_v1/messages_sort", {new_sort:new_sort, b_id:$('#b_id').val(), pid:$('#pid').val()}, function(data) {
					//Update UI to confirm with user:
					//$( ".edit-updates" ).html(data);
					
					//Disapper in a while:
					setTimeout(function() {
				        //$(".edit-updates>span").fadeOut();
				    }, 3000);
				});
		  }
	});
}





function message_delete(i_id){
	//Double check:
	var r = confirm("Delete Message?");
	if (r == true) {
		//Show processing:
		$("#ul-nav-"+i_id).html('<div><img src="/img/round_load.gif" class="loader" /> Deleting...</div>');
		
	    //Delete and remove:
		$.post("/api_v1/message_delete", {i_id:i_id, pid:$('#pid').val()}, function(data) {
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
	$("#ul-nav-"+i_id+" textarea").focus();

	//Watch typing:
	$(document).keyup(function(e) {		
		//Watch for action keys:
		if (e.ctrlKey && e.keyCode === 13){
			message_save_updates(i_id);
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
}

function message_save_updates(i_id){
	
	//Show loader:
	$("#ul-nav-"+i_id+" .edit-updates").html('<div><img src="/img/round_load.gif" class="loader" /></div>');

	//Revert View:
	msg_cancel_edit(i_id,1);
	
	//Update message:
	$.post("/api_v1/message_update", {
		
		i_id:i_id,
		i_message:$("#ul-nav-"+i_id+" textarea").val(),
		i_status:$("#i_status_"+i_id).val(),
		pid:$('#pid').val(),
		level:($('#next_level').val()-1),
		i_media_type:$("#ul-nav-"+i_id+" .i_media_type").val(),
		
	}, function(data) {
		
		if(data.status){
			
			//All good, lets show new text:
			if($("#ul-nav-"+i_id+" .i_media_type").val()=='text'){
				$("#ul-nav-"+i_id+" .text_message").html(data.message);
			}
			//Update new status:
			$("#ul-nav-"+i_id+" .the_status").html(data.new_status);
			//Update new uploader:
			$("#ul-nav-"+i_id+" .i_uploader").html(data.new_uploader);
			//Show success here
			$("#ul-nav-"+i_id+" .edit-updates").html('<b>'+data.success_icon+'</b>');
		} else {
			//Oops, some sort of an error, lets
			$("#ul-nav-"+i_id+" .edit-updates").html('<b style="color:#FF0000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+data.message+'</b>');
		}

		//Tooltips:
 		$('[data-toggle="tooltip"]').tooltip();

		//Disapper in a while:
		setTimeout(function() {
			$("#ul-nav-"+i_id+" .edit-updates>b").fadeOut();
	    }, 3000);
	});
}









var isAdvancedUpload = function() {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

var $input    = $('.box').find('input[type="file"]'),
$label    = $('.box').find('label'),
showFiles = function(files) {
  $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
};

//...

$input.on('drop', function(e) {
	alert('dropped');
droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
showFiles( droppedFiles );
});

//...

$input.on('change', function(e) {
showFiles(e.target.files);
});









function message_form_lock(){
	$('#add_message').html('<span><img src="/img/round_yellow_load.gif" class="loader" /></span>');
	$('#message_status').html('');
	
	
	$('.add-msg').addClass('is-working');
	$('#i_message').prop("disabled", true);
	$('.remove_loading').hide();
	$('#add_message').attr('href','#');
}


function message_form_unlock(result){
    
	//Update UI to unlock:
	$('#add_message').html('ADD');
	$('.add-msg').removeClass('is-working');
	$('#i_message').prop("disabled", false);
	$('.remove_loading').fadeIn();
	$('#add_message').attr('href','javascript:msg_create();');

	//Remove possible "No message" info box:
	if($('.no-messages').length){
		$('.no-messages').hide();
	}
	
	//Reset Focus:
	$("#i_message").focus();
	
	
	//What was the result?
	if(result.status){
		
		//Append data:
		$( "#message-sorting" ).append(result.message);

		//Resort:
		load_message_sorting();

		//Tooltips:
 		$('[data-toggle="tooltip"]').tooltip();

		//Hide any errors:
		setTimeout(function() {
	        $(".i_error").fadeOut();
	    }, 3000);
	    
	} else {
		
		$('#message_status').html('<b style="color:#FF0000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+result.message+'</b>');
		
	}
}

function save_attachment(droppedFiles,uploadType){
	
	if ($('.box').hasClass('is-uploading')) { return false; }
      
    if (isAdvancedUpload) {

    	//Lock message:
        message_form_lock();

        var ajaxData = new FormData($('.box').get(0));
  	    if (droppedFiles) {
  	      $.each( droppedFiles, function(i, file) {
  	  	      var thename = $input.attr('name');
  	  	      if (typeof thename == typeof undefined || thename == false) {
  	  		     var thename = 'drop';
  	  	      }
  	          ajaxData.append( uploadType , file );
  	      });
  	    }
  	    
        ajaxData.append( 'upload_type', uploadType );
        ajaxData.append( 'i_status', $('#i_status').val() );
        ajaxData.append( 'level', ($('#next_level').val()-1) );
        ajaxData.append( 'pid', $('#pid').val() );
        ajaxData.append( 'b_id', $('#b_id').val() );
        
        
  	  $.ajax({
  	    url: '/api_v1/message_attachment',
  	    type: $('.box').attr('method'),
  	    data: ajaxData,
  	    dataType: 'json',
  	    cache: false,
  	    contentType: false,
  	    processData: false,
  	    complete: function() {
  	        $('.box').removeClass('is-uploading');
  	    },
  	    success: function(data) {
  	    	message_form_unlock(data);
  	    },
  	    error: function(data) {
  	    	var result = [];
  	    	result.status = 0;
  	  	 	result.message = data.responseText;
  	    	message_form_unlock(result);
  	    }
  	  });
    } else {
      // ajax for legacy browsers
    }
}


function msg_create(){

	//Lock message:
    message_form_lock();
	
	//Update backend:
	$.post("/api_v1/message_create", {
		
		b_id:$('#b_id').val(),
		pid:$('#pid').val(),
		i_message:$('#i_message').val(),
		i_status:$('#i_status').val(),
		level:($('#next_level').val()-1),
		
	}, function(data) {

		//Empty Inputs Fields if success:
		if(data.status){
			$( "#i_message" ).val("");
    		changeMessage();
		}

		//Unlock field:
		message_form_unlock(data);
		
	});
}

</script>



<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $bootcamp['c_id'] ?>" />
<input type="hidden" id="pid" value="<?= $intent['c_id'] ?>" />
<input type="hidden" id="next_level" value="<?= $level+1 ?>" />

<div class="help_body below_h maxout" id="content_592"></div>



<div class="row">
	<div class="col-md-6">
		
		<?php
    	
        	//Show relevant tips:
        	/*
        	if($level==1){
        	    itip(599);
        	} elseif($level==2){
        	    itip(602);
        	}
    	    */
		
		$task_lists = array();
        echo '<div id="list-outbound" class="list-group">';
        
            foreach($intent['c__child_intents'] as $key=>$sub_intent){
                echo echo_cr($bootcamp['b_id'],$sub_intent,'outbound',($level+1),$bootcamp['b_sprint_unit']);
                
                //Any tasks?
                if(count($sub_intent['c__child_intents'])>0){
                    $list_id = 'list-outbound-'.$key;
                    array_push($task_lists,$list_id); //Keep track to echo JS sorting
                    echo '<div id="'.$list_id.'" class="list-group">';
                    foreach($sub_intent['c__child_intents'] as $sub_intent2){
                        echo echo_cr($bootcamp['b_id'],$sub_intent2,'outbound',($level+2),$bootcamp['b_sprint_unit'],$key);
                    }
                    echo '</div>';
                }
            }
            ?>
            <div class="list-group-item list_input">
        		<div class="input-group">
        			<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control autosearch" maxlength="<?= $core_objects['c']['maxlength'] ?>" id="addnode" placeholder=""></div>
        			<span class="input-group-addon" style="padding-right:0;">
        				<span id="dir_handle" data-toggle="tooltip" title="or press ENTER ;)" data-placement="top" class="badge badge-primary pull-right" style="cursor:pointer; margin: 1px 3px 0 6px;" onclick="new_intent($('#addnode').val());">
        					<div><span id="dir_name" class="dir-sign">OUTBOUND</span> <i class="fa fa-plus"></i></div>
        					<div class="togglebutton" style="margin-top:5px; display:none;">
        		            	<label>
        		                	<input type="checkbox" onclick="change_direction()" />
        		            	</label>
                    		</div>
        				</span>
        			</span>
        		</div>
        	</div>
        	<?php
        echo '</div>';
        
        foreach($task_lists as $tl){
            ?>
            <script type="text/javascript">
            var theobject = document.getElementById("list-outbound");
         	var sort = Sortable.create( theobject , {
         		  animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
         		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
         		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
         		  onUpdate: function (evt/**Event*/){
         			  intents_sort();
         		  }
         	});
            </script>
            <?php
        }
        ?>
		
	</div>
	<div class="col-md-6">
		<div class="marvel-device iphone-x">
            <div class="notch">
                <div class="camera"></div>
                <div class="speaker"></div>
            </div>
            <div class="top-bar"></div>
            <div class="sleep"></div>
            <div class="bottom-bar"></div>
            <div class="volume"></div>
            <div class="overflow">
                <div class="shadow shadow--tr"></div>
                <div class="shadow shadow--tl"></div>
                <div class="shadow shadow--br"></div>
                <div class="shadow shadow--bl"></div>
            </div>
            <div class="inner-shadow"></div>
            <div class="screen">
                <div class="ix-top">
                	<span class="ix-top-left" data-toggle="tooltip" title="PST Time" data-placement="bottom"><?= date("H:i") ?></span>
                	<span class="ix-top-right">
                		<i class="fa fa-wifi" aria-hidden="true"></i>
                		<i class="fa fa-battery-full" aria-hidden="true"></i>
                	</span>
                </div>
                
            	<div class="ix-msg">
            		<div class="ix-tip">Messages are automatically sent to students during the milestone.</div>
                    <?php 
                	//Show relevant tips:
                	if($level==1){
                	    //itip(604);
                	} elseif($level==2){
                	    //itip(605);
                	} elseif($level==3){
                	    //itip(608);
                	}
                	
                	
                	if(count($i_messages)>0){
                	    echo '<div id="message-sorting" class="list-group list-messages">';
                	    foreach($i_messages as $i){
                	        echo echo_message($i,$level);
                	    }
                	    echo '</div>';
                	} else {
                	    echo '<div class="ix-tip no-messages">No messages added yet!</div>';
                	    //Now show empty shell
                	    echo '<div id="message-sorting" class="list-group list-messages">';
                	    echo '</div>';
                	}
                    	
                	
                    ?>
                </div>
                
                <div class="ix-kyb">
                	<?php 
                	echo '<div class="list-group list-messages">';
                	echo '<div class="list-group-item">';
                	
                	echo '<div class="add-msg" style="border-radius:0 !important; margin-top: 2px;">';
                	echo '<form class="box" method="post" enctype="multipart/form-data">'; //Used for dropping files
                	
                	echo '<textarea onkeyup="changeMessage()" class="form-control msg msgin" id="i_message" placeholder="Write Message, Paste URL or Drop a File..."></textarea>';
                	
                	echo '<div id="i_message_counter" style="margin:0 0 1px 0; font-size:0.8em;">';
                	//File counter:
                	echo '<span id="charNum">0</span>/420';
                	//{first_name}
                	echo '<a href="javascript:add_first_name();" class="textarea_buttons remove_loading" style="float:right;" data-toggle="tooltip" title="Replaced with student\'s First Name for a more personal message." data-placement="left"><i class="fa fa-id-card-o" aria-hidden="true"></i> {first_name}</a>';
                	//Choose a file:
                	$file_limit_mb = $this->config->item('file_limit_mb');
                	echo '<div style="float:right; display:inline-block; margin-right:8px;" class="remove_loading"><input class="box__file inputfile" type="file" name="file" id="file" /><label class="textarea_buttons" for="file" data-toggle="tooltip" title="Upload Video, Audio, Images or PDFs up to '.$file_limit_mb.' MB." data-placement="top"><i class="fa fa-picture-o" aria-hidden="true"></i> Upload File</label></div>';
                	echo '</div>';
                	
                	echo '<ul style="list-style:none;">';
                	
                	echo '<li class="pull-left" style="margin-left:-28px; padding-left: 0; margin-top: 4px;"><span id="message_status"></span></li>';
                	echo '<li class="pull-right"><a href="javascript:msg_create();" id="add_message" data-toggle="tooltip" title="or press CTRL+ENTER ;)" data-placement="top" class="btn btn-primary" style="margin-top: 2px; padding: 5px 8px; margin-right:25px;">ADD</a></li>';
                	
                	echo '<li class="pull-right remove_loading" style="padding:2px 5px 0 0;">';
                	echo echo_status_dropdown('i','i_status',($level==1?3:1),($level==1?array(-1,4):($level==3?array(-1,3,4):array(-1,4))),'dropup',$level,1);
                	echo '</li>';
                	
                	echo '</ul>';
                	
                	echo '<div id="url_preview"></div>';
                	
                	echo '</form>';
                	echo '</div>';
                	
                	echo '</div>';
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>

	</div>
</div>


<div class="tab-content tab-space">
	
	<div class="tab-pane <?= ( $has_tree ? 'active' : 'hidden') ?>" id="list">
    
    </div>
    
    
    
    
    
    <div class="tab-pane" id="details">
        
        <?php $this->load->view('console/inputs/c_objective' , array(
            'level' => $level,
            'c_objective' => $intent['c_objective'],
        )); ?>        
        
        
        <div style="display:<?= ( $level==1 ?'block':'none' ) ?>; margin-top:30px;">
    		<?php $this->load->view('console/inputs/b_sprint_unit' , array('b_sprint_unit'=>$bootcamp['b_sprint_unit']) ); ?>
        </div>
        
        
        <div style="display:<?= ( $level==2 ?'block':'none' ) ?>; margin-top:30px;">
    		<div class="title" style="margin-top:15px;"><h4><i class="fa fa-coffee" aria-hidden="true"></i> Break Milestone <span id="hb_601" class="help_button" intent-id="601"></span></h4></div>
            <div class="help_body maxout" id="content_601"></div>
            <div class="form-group label-floating is-empty">
            	<div class="checkbox">
                	<label>
                		<?php if($intent['c_is_last']=='t'){ ?>
                		<input type="checkbox" id="c_is_last" checked /> Is Break Milestone
                		<?php } elseif(count($intent['c__child_intents'])>0){ ?>
                		<input type="checkbox" id="c_is_last" /> Is Break Milestone <b><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Your Must Delete All Tasks First</b>
                		<?php } else { ?>
                		<input type="checkbox" id="c_is_last" /> Is Break Milestone
                		<?php } ?>
                	</label>
                </div>
            </div>
        </div>
    		
       
        <?php $times = $this->config->item('c_time_options'); ?>
        <div style="display:<?= (($level>=3 || $intent['c_time_estimate']>0)?'block':'none') ?>; margin-top:30px;">
            <div class="title" style="margin-top:25px; display:<?= ($level>=3?'block':'none') ?>;"><h4><i class="fa fa-clock-o"></i> Time Estimate <span id="hb_609" class="help_button" intent-id="609"></span></h4></div>
            <div class="help_body maxout" id="content_609"></div>
            <select class="form-control input-mini border" id="c_time_estimate">
            	<?php 
            	foreach($times as $time){
            	    echo '<option value="'.$time.'" '.( $intent['c_time_estimate']==$time ? 'selected="selected"' : '' ).'>~'.echo_hours($time).' = '.round($time*60).' On-Time Points OR '.floor($time*60*0.5).' Late Point'.(round($time*60)==1?'':'s').'</option>';
            	}
            	?>
            </select>
        </div>
        
        
        <div style="display:<?= ( $udata['u_status']>999 /*Disabled for now!*/ ? 'block' : 'none' ) ?>; margin-top:30px;">
            <div class="title" style="margin-top:25px;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Status</h4></div>
            <ul class="maxout">
    			<li>Default status is <?= status_bible('c',1) ?>.</li>
    			<li>To prevent this from being shown to students set status to <?= status_bible('c',0) ?>.</li>
    		</ul>
            <?= echo_status_dropdown('c','c_status',$intent['c_status']); ?>
        </div>
        
       
        
        <br />
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_c();" class="btn btn-primary">Save</a></td><td><span class="save_c_results"></span></td></tr></table>
		
    </div>
</div>
