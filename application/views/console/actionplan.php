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
}

//Count text area characters:
function countChar(val) {

	//Update count:
    var len = val.value.length;
    if (len > 600) {
        val.value = val.value.substring(0, 600);
    } else {
        $('#charNum').text(600 - len);
    }

    //Passon data to detect URLs:
    $.post("/process/detect_url", { text:val.value } , function(data) {
 		//Update data
 		if(data=='clear_url_preview'){
 			$('#url_preview').html("");
 		} else if(data.length>0){
 			$('#url_preview').html(data);
     	}
	});
}




  
$(document).ready(function() {

/*
 * parallelUploads:1,
	uploadMultiple:false,
	previewTemplate:null,
	hiddenInputContainer:true,
	createImageThumbnails:false,
	clickable:false,
 */

 
    <?php if($has_tree){ ?>
    function update_tree_input(){
    	var current_count = $("#list-outbound").children().length;
    	$('#addnode').attr("placeholder", "<?= $core_objects['level_'.$level]['o_name'] ?> #"+(current_count+1)+" Objective (Specific & Measurable)");
    	return current_count;
    }
    
    var current_subtree = update_tree_input();
    $('#list-outbound').bind("DOMSubtreeModified",function(){
		if($("#list-outbound").children().length!=current_subtree){
			//List has been adjusted, change the placeholder:
			current_subtree = update_tree_input();
		}
    });
    <?php } ?>



	
	//Initiat3e Dropzone:
	$("#dropform").dropzone({
		url: "/process/message_attach",
		maxFilesize: 1, // MB
		accept: function(file, done) {
			alert(file.name+" was Uploaded");
			//done("Naha, you don't.");
		}
	});
	
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
		focu_hash(window.location.hash);
    }
	
	//Load Sortable:
	load_intent_sort();
	load_message_sorting();
	
	//Watch for message creation:
	$('#i_message').keydown(function (e) {
		if (e.ctrlKey && e.keyCode == 13) {
			msg_create();
		}
	});


	$( "#addnode" ).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
        	new_intent($( "#addnode" ).val());
            return true;
        }
    });

    
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
 		pid:$('#pid').val(),
 		c_objective:$('#c_objective').val(),
 		c_todo_overview:( c_todo_overview_quill.getLength()>1 ? $('#c_todo_overview .ql-editor').html() : "" ),
 		c_status:$('#c_status').val(),
 		c_time_estimate:$('#c_time_estimate').val(),
 		c_is_last:(document.getElementById('c_is_last').checked ? 't' : 'f'),
 	};
 	
 	//Show spinner:
 	$('.save_c_results').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
 	
 	$.post("/process/intent_edit", postData , function(data) {
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
     $( "#list-outbound").append('<a href="#" id="temp" class="list-group-item"><img src="/img/round_load.gif" class="loader" /> Adding... </a>');
 	
     //Empty Input:
 	$( "#addnode" ).val("").focus();
 	
 	//Update backend:
 	$.post("/process/intent_create", {b_id:b_id, c_id:c_id, pid:pid, c_objective:c_objective, next_level:next_level}, function(data) {
 		//Update UI to confirm with user:
 		$( "#temp" ).remove();
 		$( "#list-outbound" ).append(data);
 		
 		//Resort:
 		load_intent_sort();
 		
 		//Tooltips:
 		$('[data-toggle="tooltip"]').addClass('').tooltip();
 	});
}

function link_lintent(target_id){
 	//Fetch needed vars:
 	var pid = $('#pid').val();
 	var b_id = $('#b_id').val();
 	var next_level = $( "#next_level" ).val();
 	
 	//Set processing status:
     $( "#list-outbound" ).append('<a href="#" id="temp" class="list-group-item"><img src="/img/round_load.gif" class="loader" /> Adding... </a>');
 	
     //Empty Input:
 	$( "#addnode" ).val("").focus();
 	
 	//Update backend:
 	$.post("/process/intent_link", {b_id:b_id, pid:pid, target_id:target_id, next_level:next_level}, function(data) {
 		//Update UI to confirm with user:
 		$( "#temp" ).remove();
 		$( "#list-outbound" ).append(data);
 		
 		//Resort:
 		load_intent_sort();
 		
 		//Tooltips:
 		$('[data-toggle="tooltip"]').addClass('').tooltip();
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
 	$.post("/process/intents_sort", {pid:$('#pid').val(), b_id:$('#b_id').val(), new_sort:new_sort}, function(data) {
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
 		  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
 		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
 		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
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
 		$.post("/process/intent_unlink", {cr_id:cr_id, b_id:b_id}, function(data) {
 			//Update UI to confirm with user:
 			$( "#cr_"+cr_id ).html(data);			
 			
 			setTimeout(function() {
 				//Disapper:
 				$( "#cr_"+cr_id ).fadeOut().remove();
 				
 				//Update sort:
 				intents_sort();
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
		  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
		  handle: ".fa-sort", // Restricts sort start click/touch to the specified element
		  draggable: ".is_sortable", // Specifies which items inside the element should be sortable
		  onUpdate: function (evt/**Event*/){
			    //Set processing status:
			    $( ".edit-updates" ).html('<img src="/img/round_load.gif" class="loader" />');
			  
			    //Fetch new sort:
			    var new_sort = [];
				var sort_rank = 0;
				$( "#message-sorting>div" ).each(function() {
					sort_rank++;
					new_sort[sort_rank] = $( this ).attr('iid');
				});
				
				//Update backend:
				$.post("/process/messages_sort", {new_sort:new_sort, b_id:$('#b_id').val(), pid:$('#pid').val()}, function(data) {
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

function msg_create(){
	
	//Set processing status:
    $( "#message-sorting" ).append('<div id="temp"><div><img src="/img/round_load.gif" class="loader" /></div></div>');
	
	//Update backend:
	$.post("/process/message_create", {
		
		b_id:$('#b_id').val(),
		pid:$('#pid').val(),
		i_message:$('#i_message').val(),
		i_dispatch_minutes:$('#i_dispatch_minutes').val(),
		
	}, function(data) {
		
		//Update UI to confirm with user:
		$( "#temp" ).remove();
		$( "#message-sorting" ).append(data);

		//Empty Inputs Fields:
		$( "#i_message" ).val("");
		countChar(document.getElementById('i_message'));

		//Reset Focus:
		$("#i_message").focus();
		
		//Resort:
		load_message_sorting();

		//Hide any errors:
		setTimeout(function() {
	        $(".i_error").fadeOut();
	    }, 3000);
		
	});
}



function message_delete(i_id){
	//Double check:
	var r = confirm("Delete Message?");
	if (r == true) {
	    //Delete and remove:
		$.post("/process/message_delete", {i_id:i_id, pid:$('#pid').val()}, function(data) {
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
	
	if(!success){
		//Revert text changes to original:
		var original = $("#ul-nav-"+i_id+" .original").text(); //Original content
		$("#ul-nav-"+i_id+" textarea").val(original); //Revert Textarea
	}
}

function message_save_updates(i_id){
	//Make sure there is some value:
	var i_message = $("#ul-nav-"+i_id+" textarea").val();
	if(i_message.length<1){
		alert('Message is required. Try again.');
		return false;
	}
	
	//Revert View:
	msg_cancel_edit(i_id,1);
	
	//Show loader:
	$("#ul-nav-"+i_id+" .edit-updates").html('<div><img src="/img/round_load.gif" class="loader" /></div>');
	
	//Update message:
	$.post("/process/message_update", {i_id:i_id, i_message:i_message, pid:$('#pid').val()}, function(data) {
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

</script>


<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $bootcamp['c_id'] ?>" />
<input type="hidden" id="pid" value="<?= $intent['c_id'] ?>" />
<input type="hidden" id="next_level" value="<?= $level+1 ?>" />



<ul id="topnav" class="nav nav-pills nav-pills-primary">
  <?php if($has_tree){ ?>
  <li id="nav_list" class="active"><a href="#list" data-toggle="tab" onclick="update_hash('list')"><?= $core_objects['level_'.$level]['o_icon'].' '.$core_objects['level_'.$level]['o_names'] ?></a></li>
  <?php } ?>
  <li id="nav_messages" class="<?= ( !$has_tree ? 'active' : '') ?>"><a href="#messages" data-toggle="tab" onclick="update_hash('messages')"><i class="fa fa-commenting" aria-hidden="true"></i> Messages</a></li>
  <li id="nav_details"><a href="#details" data-toggle="tab" onclick="update_hash('details')"><i class="fa fa-pencil" aria-hidden="true"></i> Details</a></li>
</ul>


<div class="tab-content tab-space">
	
	
	
	<div class="tab-pane <?= ( $has_tree ? 'active' : 'hidden') ?>" id="list">
    	<?php
    	if($level==1){
    	    ?>
        	<ul class="maxout">
        		<li><b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b> is a collection of <b><?= $core_objects['level_1']['o_icon'] ?> Milestones</b> that step-by-step help students accomplish the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamp Objective</b>.</li>
    			<li>The <b><i class="fa fa-hourglass-end" aria-hidden="true"></i> Milestone Submission Frequency</b> is set to <b><?= $sprint_units[$bootcamp['b_sprint_unit']]['name'] ?></b> in <a href="/console/<?= $bootcamp['b_id'] ?>/settings/#settings"><u><i class="material-icons">settings</i>Settings</u></a>.</li>
    			<li>Students must mark milestones as complete every <?= $bootcamp['b_sprint_unit'] ?> using <a href="#" data-toggle="modal" data-target="#MenchBotModal"><i class="fa fa-commenting" aria-hidden="true"></i> MenchBot</a>.</li>
    			<li>To keep students focused, <b><?= $core_objects['level_1']['o_icon'] ?> Milestones</b> are unlocked one <?= $bootcamp['b_sprint_unit'] ?> at a time.</li>
    			<li>Each <b><?= $core_objects['level_1']['o_icon'].' '.$sprint_units[$bootcamp['b_sprint_unit']]['name'] ?> Milestone</b> can have a number of &nbsp;<b><i class="fa fa-check-square" aria-hidden="true"></i> Tasks</b> for further breakdown.</li>
    			<!-- <li><b><?= ucwords($bootcamp['b_sprint_unit']) ?>-Off Milestones</b> are milestones with 0 tasks assigned to them.</li> -->
    			<li>You can easily add, remove and sort <b><?= $core_objects['level_1']['o_icon'] ?> Milestones</b> at any time.</li>
    		</ul>
    		<?php
        } elseif($level==2){ 
            ?>
        	<ul class="maxout">
    			<li>Each <b><?= $core_objects['level_1']['o_icon'] ?> Milestone</b> is broken down into <b><?= $core_objects['level_2']['o_icon'] ?> Tasks</b>.</li>
    			<li>Students must complete all <b><?= $core_objects['level_2']['o_icon'] ?> Tasks</b> in order to complete a <b><?= $core_objects['level_1']['o_icon'] ?> Milestone</b>.</li>
    			<li>You can easily add, remove and sort <b><?= $core_objects['level_2']['o_icon'] ?> Tasks</b> at any time.</li>
    		</ul>
            <?php
        }
        
        //Print current sub-intents:
        echo '<div id="list-outbound" class="list-group">';
        foreach($intent['c__child_intents'] as $sub_intent){
            echo echo_cr($bootcamp['b_id'],$sub_intent,'outbound',($level+1),$bootcamp['b_sprint_unit']);
        }
        echo '</div>';
        
        //Show add button:
        ?>
        <div class="list-group">
        	<div class="list-group-item list_input">
        		<div class="input-group">
        			<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control autosearch" id="addnode" placeholder=""></div>
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
        </div>
    </div>
    
    
    
    
    
    
    <div class="tab-pane <?= ( !$has_tree ? 'active' : '') ?>" id="messages">
    	<p class="maxout"></p>
    	<ul class="maxout">
			<li>Facts or best-practices helping students accomplish the <?= strtolower($core_objects['level_'.($level-1)]['o_name']) ?> objecetive.</li>
			<li>Messages are delivered to students 1 milestone at a time using <a href="#" data-toggle="modal" data-target="#MenchBotModal"><i class="fa fa-commenting" aria-hidden="true"></i> MenchBot</a>.</li>
			<li>Each message can be a message, URL or a media file.</li>
		</ul>
    	<?php 
		echo '<div id="message-sorting" class="list-group list-messages" style="margin-bottom:0;">';
		foreach($i_messages as $i){
		    echo_message($i);
		}
		echo '</div>';
		
		
		
		//TODO do_edits		
		echo '<div class="list-group list-messages">';
    		echo '<div class="list-group-item">';
    		
    		  echo '<div class="add-msg" style="background-color: #FFF; border: 1px solid #CCC;">';
    		  echo '<form id="dropform">'; //Used for dropping files
    		  
    		  
    		    echo '<textarea onkeyup="countChar(this)" class="form-control" style="min-height:110px; resize:vertical;" id="i_message" placeholder="Write Message, Paste URL or Drop a File..."></textarea>';
    		
        		echo '<div id="i_message_counter" style="margin:-15px 0 10px 0; font-size:0.8em;"><span id="charNum">600</span>/600 Remaining.<a href="javascript:add_first_name();" style="float:right; font-weight:bold;" data-toggle="tooltip" title="Replaced with student\'s First Name for a more personal message." data-placement="left"><i class="fa fa-plus-square" aria-hidden="true"></i> {first_name}</a></div>';
        		
        		echo '<div id="url_preview"></div>';
        		
        		echo '<ul style="list-style:none;">';
        		  /*
            		echo '<li class="pull-left" style="padding:2px 5px 0 0;">';
            		echo '<div class="fallback" style="display:inline-block;"><input type="file" name="file" /></div>';
            		echo '</li>';
            		*/
        		
            		echo '<li class="pull-right"><a href="javascript:msg_create();" data-toggle="tooltip" title="or press CTRL+ENTER ;)" data-placement="top" class="btn btn-primary" style="margin-top:0;"><i class="fa fa-plus" aria-hidden="true"></i></a></li>';
            		echo '<li class="pull-right" style="padding:2px 5px 0 0;">';
            		  echo echo_status_dropdown('i','i_status',1,array(-1),'dropup');
            		echo '</li>';
            		echo '<li class="pull-right" style="padding:2px 5px 0 0;">';
            		echo echo_status_dropdown('idm','i_dispatch_minutes',0,array(),'dropup');
            		echo '</li>';
            		
        		echo '</ul>';
        		
        		
              echo '</form>';
        	  echo '</div>';
        		
            echo '</div>';
        echo '</div>';
        ?>
        
    </div>
    
    
    
    
    
    <div class="tab-pane" id="details">
        
        <?php $this->load->view('console/inputs/c_objective' , array(
            'level' => $level,
            'c_objective' => $intent['c_objective'],
        )); ?>
        
        
        <?php /* TODO Remove soon with the instroduction of Messages V4 */ ?>
        <div style="display:<?= ( in_array($udata['u_id'],array(1,2)) && strlen($intent['c_todo_overview'])>0 ? 'block' : 'none' ) ?>;">
        	<br />
            <div class="title"><h4><i class="fa fa-binoculars" aria-hidden="true"></i> <?= $core_objects['level_'.($level-1)]['o_name'] ?> Overview</h4></div>
            <ul class="maxout">
            	<?php if($level==1){ ?>
            	<li>Provide an overview of how your bootcamp plans to accomplish its <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Objective</b>.</li>
            	<li><?= $core_objects['level_'.($level-1)]['o_name'] ?> overviews are published on the landing page below the title.</li>
            	<?php } elseif($level==2){ ?>
            	<li>Provide an overview of <b>how</b> this milestone builds towards the <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Objective</b> and <b>what</b> will students be doing for this milestone.</li>
            	<li><?= $core_objects['level_'.($level-1)]['o_name'] ?> overviews are published in the landing page's Milestone section.</li>
            	<?php } elseif($level>=3){ ?>
            	<li>Give more context on how to execute this <?= strtolower($core_objects['level_'.($level-1)]['o_name']) ?>.</li>
            	<li><?= $core_objects['level_'.($level-1)]['o_name'] ?> overview provides instructions on how to execute this task.</li>
            	<li><?= $core_objects['level_'.($level-1)]['o_name'] ?> overviews are private & "drip-fed" to students during the bootcamp.</li>
            	<?php } ?>
            </ul>
            <div id="c_todo_overview"><?= ( isset($intent['c_todo_overview']) ? $intent['c_todo_overview'] : '' ) ?></div>
            <script> var c_todo_overview_quill = new Quill('#c_todo_overview', setting_full); </script>
        </div>
        
        
        
        <div style="display:<?= ( $level==2 ?'block':'none' ) ?>;">
    		<div class="title" style="margin-top:15px;"><h4><i class="fa fa-coffee" aria-hidden="true"></i> Break Milestone</h4></div>
            <ul>
            	<li>Break Milestones give some time off in between Milestones.</li>
            	<li>They also help students who are falling behind to catchup.</li>
            	<li>Break Milestones cannot have any <b><i class="fa fa-check-square" aria-hidden="true"></i> Tasks</b>.</li>
            	<li>Give Each Break Milestone a Relevant Title like "<?= ($bootcamp['b_sprint_unit']=='day' ? 'Weekend Break' : 'Midterm Break') ?>".</li>
            </ul>
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
        <div style="display:<?= (($level>=3 || $intent['c_time_estimate']>0)?'block':'none') ?>;">
            <div class="title" style="margin-top:25px; display:<?= ($level>=3?'block':'none') ?>;"><h4><i class="fa fa-clock-o"></i> Time Estimate</h4></div>
            <ul class="maxout">
    			<li>The estimated time for the <b>average</b> student to read & execute this task.</li>
    			<li>Correlates to this task's complexity and defines its <a href="https://support.mench.co/hc/en-us/articles/115002372531" target="_blank"><u>completion point <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
    			<li>IF you estimate more than <?= $times[(count($times)-1)] ?> hours then break task down into multiple tasks.</li>
    		</ul>
            <select class="form-control input-mini border" id="c_time_estimate">
            	<?php 
            	foreach($times as $time){
            	    echo '<option value="'.$time.'" '.( $intent['c_time_estimate']==$time ? 'selected="selected"' : '' ).'>~'.echo_hours($time).' = '.round($time*60).' On-Time Points OR '.floor($time*60*0.5).' Late Point'.(round($time*60)==1?'':'s').'</option>';
            	}
            	?>
            </select>
        </div>
        
        
        <div style="display:<?= ( $udata['u_status']>999 /*Disabled for now!*/ ? 'block' : 'none' ) ?>;">
            <div class="title" style="margin-top:25px;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Status</h4></div>
            <ul class="maxout">
    			<li>Default status is <?= status_bible('c',1) ?>.</li>
    			<li>To prevent this from being shown to students set status to <?= status_bible('c',0) ?>.</li>
    		</ul>
            <?php echo_status_dropdown('c','c_status',$intent['c_status']); ?>
        </div>
        
        
        
       
        
        <br />
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_c();" class="btn btn-primary">Save</a></td><td><span class="save_c_results"></span></td></tr></table>
		
    </div>
    
    
    
     
   
</div>

