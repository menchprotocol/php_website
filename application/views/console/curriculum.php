<script>
$(document).ready(function() {
	//Load Sortable:
	load_intent_sort();

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
		         return '<span class="suggest-prefix"><i class="fa fa-link" aria-hidden="true"></i> Link to</span> '+ suggestion._highlightResult.c_objective.value;
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
     		c_prerequisites:( c_prerequisites_quill.getLength()>1 ? $('#c_prerequisites .ql-editor').html() : "" ),
     		c_todo_bible:( c_todo_bible_quill.getLength()>1 ? $('#c_todo_bible .ql-editor').html() : "" ),
     		c_time_estimate:$('#c_time_estimate').val(),
 	};
 	
 	//Show spinner:
 	$('#save_c_results').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
 	
 	$.post("/process/intent_edit", postData , function(data) {
 		//Update UI to confirm with user:
 		$('#save_c_results').html(data).hide().fadeIn();
 		
 		//Disapper in a while:
 		setTimeout(function() {
 			$('#save_c_results').fadeOut();
 	    }, 10000);
     });
}


function new_intent(c_objective){
 	
 	if(c_objective.length<1){
 		alert('Missing name. Try again.');
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
 		var current_handler = $( "#cr_"+cr_id+" .inline-level" ).text();
 		var handler_parts = current_handler.split("#");
 		$( "#cr_"+cr_id+" .inline-level" ).text(handler_parts[0]+'#'+sort_rank);
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
				$.post("/process/media_sort", {new_sort:new_sort}, function(data) {
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
	
	//Fetch needed vars:
	var i_message = $('#i_message').val();
	var pid = $('#pid').val();
	
	if(i_message.length<1 || pid<1){
		return false;
	}
	
	//Set processing status:
    $( "#message-sorting" ).append('<div id="temp"><div><img src="/img/round_load.gif" class="loader" /> Adding... </div></div>');
	
    //Empty Input:
	$( "#i_message" ).val("").focus();
	
	//Update backend:
	$.post("/process/media_create", {pid:pid, i_message:i_message}, function(data) {
		//Update UI to confirm with user:
		$( "#temp" ).remove();
		$( "#message-sorting" ).append(data);
		
		//Resort:
		load_message_sorting();
	});
}



function media_delete(i_id){
	//Double check:
	var r = confirm("Delete Message?");
	if (r == true) {
	    //Delete and remove:
		$.post("/process/media_delete", {i_id:i_id}, function(data) {
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
			msg_save_edit(i_id);
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

function msg_save_edit(i_id){
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
	$.post("/process/media_edit", {i_id:i_id, i_message:i_message}, function(data) {
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




<?php $level_names = $this->config->item('level_names'); ?>
<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $bootcamp['c_id'] ?>" />
<input type="hidden" id="pid" value="<?= $intent['c_id'] ?>" />
<input type="hidden" id="next_level" value="<?= $level+1 ?>" />





<ul class="nav nav-pills nav-pills-primary">
  <li class="active"><a href="#pill1" data-toggle="tab"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Goal</a></li>
  <li><a href="#pill2" data-toggle="tab" class="<?= ( strlen($intent['c_todo_overview'])>0 ? '' : 'is_empty') ?>"><i class="fa fa-binoculars" aria-hidden="true"></i> Overview</a></li>
  <li><a href="#pill3" data-toggle="tab" class="<?= ( strlen($intent['c_prerequisites'])>0 ? '' : 'is_empty') ?>"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Prerequisites</a></li>
  <?php if($level>1){ ?>
  <li><a href="#pill4" data-toggle="tab" class="<?= ( strlen($intent['c_todo_bible'])>0 && $intent['c_time_estimate']>0 ? '' : 'is_empty') ?>"><i class="fa fa-book" aria-hidden="true"></i> Homework</a></li>
  <?php } ?>
</ul>


<div class="tab-content tab-space">

    <div class="tab-pane active" id="pill1">
    	<p>Define a <b>smart</b> goal: specific, measurable, achievable, relevant & trackable.</p>
        <div class="form-group label-floating is-empty">
            <input type="text" id="c_objective" value="<?= $intent['c_objective'] ?>" class="form-control border">
			
			<?php if($level==1 && 0){ ?>
			<div class="alert alert-warning" role="alert"><div><b>REMINDER:</b></div>The primary bootcamp objective sets the guideline for the Tuition Reimbursement Guarantee included in all Mench bootcamps. It basically means that if students execute the entire curriculum and fail to achieve this primary objective, they would get their tuition fully reimbursed.</div>                            
			<?php } ?>
			
        </div>
    </div>
    
    
    <div class="tab-pane" id="pill2">
    
		<p>An overview of what to be expected:</p>
        <div id="c_todo_overview"><?= $intent['c_todo_overview'] ?></div>
        <script> var c_todo_overview_quill = new Quill('#c_todo_overview', setting_full); </script>
        
    </div>
    
    
    <div class="tab-pane" id="pill3">
		<p>An optional list of requirements that students must meet <b>before</b> starting to execute towards this goal.</p>
    	<div id="c_prerequisites"><?= $intent['c_prerequisites'] ?></div>
        <script> var c_prerequisites_quill = new Quill('#c_prerequisites', setting_listo); </script>

        <?php if($level>1){ ?>
			<div class="alert alert-warning" role="alert"><div><b>WARNING:</b></div>Students cannot see <?= strtolower($level_names[$level]) ?> prerequisites until after they have enrolled. So if you are adding any critical prerequisites that need students attention, make sure to also specify them in the <a href="/console/<?= $bootcamp['b_id'] ?>/curriculum">curriculum prerequisites</a> section so students can make an informed decision when considering enrollment.</div>                            
		<?php } ?>
    </div>
    
    
    <div class="tab-pane" id="pill4">
    	<p>The homework is detailed instructions on <b>how to execute</b> towards the goal. It's shared with students on the Monday of the weekly sprint to keep students focused by only paying attention to what they need to do each week.</p>
    	
    	
    	<div id="c_todo_bible"><?= $intent['c_todo_bible'] ?></div>
        <script> var c_todo_bible_quill = new Quill('#c_todo_bible', setting_full); </script>
        
        
        <div class="title"><h4><i class="fa fa-clock-o"></i> Estimated Time</h4></div>
        <p>An estimate of how long it takes to complete this homework which includes watching/reading all videos/article and doing the required work. For time estimates longer than 13 hours you are required to break the goal down into smaller goals to reduce complexity.</p>
        <select class="form-control input-mini border" id="c_time_estimate">
        	<?php 
        	$times = $this->config->item('c_time_options');
        	foreach($times as $time){
        	    echo '<option value="'.$time.'" '.( $intent['c_time_estimate']==$time ? 'selected="selected"' : '' ).'>~'.echo_hours($time).'</option>';
        	}
        	?>
        </select>
    </div>
    
</div>

<table width="100%"><tr><td class="save-td"><a href="javascript:save_c();" class="btn btn-primary">Save</a></td><td><span id="save_c_results"></span></td></tr></table>


<?php

if($level<3){
    
    if($level==1){
        ?>
        <h3>Weekly Sprints</h3>
        <p class="maxout">Students will accomplish this bootcamp's primary goal with <b>weekly sprints</b>. Each week would focused on a specific sub-goal that helps them get closer to achieving the bootcamp goal. A few notes:</p>
        <ul class="maxout">
			<li>First consider how many hours students should spent per week on this bootcamp? Is this a 5 hours/week bootcamp or 60 hours/week?</li>
			<li>Consider how many weeks is required to accomplish the primary bootcamp goal? This helps you with the breakdown process.</li>
			<li>Design weekly sprints that are more/less equal in workload.</li>
			<li>You can easily add, remove and sort your weekly sprints below.</li>
		</ul>
		<br />
		<?php
    } elseif($level==2){
        echo '<h3>Week Tasks</h3>';
        echo '<p class="maxout">Break down the week goal into smaller tasks to give students step-by-step checklist for the week:</p>';
    }
    
    //Print current sub-intents:
    echo '<div id="list-outbound" class="list-group">';
    foreach($intent['c__child_intents'] as $sub_intent){
        echo echo_cr($bootcamp['b_id'],$sub_intent,'outbound',($level+1));
    }
    echo '</div>';
    
    //Show add button:
    ?>
    <div class="list-group">
    	<div class="list-group-item list_input">
    		<div class="input-group">
    			<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control autosearch" id="addnode" placeholder="+ <?= ($level==1 ? 'Weekly Sprint' : 'Week Tasks') ?> Goal"></div>
    			<span class="input-group-addon" style="padding-right:0;">
    				<span id="dir_handle" class="label label-primary pull-right" style="cursor:pointer;" onclick="new_intent($('#addnode').val());">
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

<?php } ?>




<?php
/*
 * i Messaging features / Disabled for now...
 * Note: Model calls are commented out form Controller as well
 * 
echo '<p>'.$this->lang->line('i_desc').'</p>';
echo '<div id="message-sorting" class="list-group list-messages" style="margin-bottom:0;">';
	foreach($i_messages as $i){
		echo_message($i);
	}
echo '</div>';


//TODO do_edits
echo '<div class="list-group list-messages">';
	echo '<div class="list-group-item">';
		echo '<div class="add-msg">';
		echo '<textarea id="i_message" placeholder="+ Add Media"></textarea>';
		echo '<ul class="msg-nav">';
			echo '<li><a href="javascript:msg_create();" data-toggle="tooltip" title="Ctrl + Enter ;)"><i class="fa fa-plus"></i> Add</a></li>';
		echo '</ul>';
		echo '</div>';
	echo '</div>';
echo '</div>';
*/
?>
