<?php
//Fetch the sprint units from config:
$sprint_units = $this->config->item('sprint_units');
$udata = $this->session->userdata('user');
?>
<style> .breadcrumb li { display:block; } </style>
<script>

//Count text area characters:
function countChar(val) {
    var len = val.value.length;
    if (len > 600) {
      val.value = val.value.substring(0, 600);
    } else {
      $('#charNum').text(600 - len);
    }
}

  
$(document).ready(function() {

	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
      	//Open specific menu with a 100ms delay to fix TOP NAV bug
    	setTimeout(function() {
    		$('.tab-pane, #topnav > li').removeClass('active');
    		$('#'+hash+', #nav_'+hash).addClass('active');
	    }, 100);
    }
	
	//Load Sortable:
	load_intent_sort();
	load_message_sorting();

	//Watch for message creation drop down change:
	$("#i_media_type").change(function() {
		if($( this ).val()=='text'){
			$('#i_message, #i_message_counter').show();
		    $('#i_url').attr('placeholder','Optional reference URL');
		} else {
			$('#i_message').hide().val('');
			$('#i_message_counter').hide();
			$('#i_url').attr('placeholder',$( this ).val()+' URL');
		}
	});
	
	//Watch for message creation:
	$('#i_message, #i_url').keydown(function (e) {
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
     		c_todo_bible:( c_todo_bible_quill.getLength()>1 ? $('#c_todo_bible .ql-editor').html() : "" ),
     		c_status:$('#c_status').val(),
     		c_time_estimate:$('#c_time_estimate').val(),
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
	
	//Set processing status:
    $( "#message-sorting" ).append('<div id="temp"><div><img src="/img/round_load.gif" class="loader" /></div></div>');
	
	//Update backend:
	$.post("/process/media_create", {
		
		b_id:$('#b_id').val(),
		pid:$('#pid').val(),
		i_media_type:$('#i_media_type').val(),
		i_message:$('#i_message').val(),
		i_url:$('#i_url').val(),
		i_dispatch_minutes:$('#i_dispatch_minutes').val(),
		
	}, function(data) {
		
		//Update UI to confirm with user:
		$( "#temp" ).remove();
		$( "#message-sorting" ).append(data);

		//Empty Inputs Fields:
		$( "#i_url, #i_message" ).val("");

		//Reset Focus:
		if($("#i_media_type").val()=='text'){
			$("#i_message").focus();
		} else {
			$("#i_url").focus();
		}
		
		//Resort:
		load_message_sorting();

		//Hide any errors:
		setTimeout(function() {
	        $(".i_error").fadeOut();
	    }, 3000);
		
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




<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $bootcamp['c_id'] ?>" />
<input type="hidden" id="pid" value="<?= $intent['c_id'] ?>" />
<input type="hidden" id="next_level" value="<?= $level+1 ?>" />




<?php if($level>1){ ?>
<ul id="topnav" class="nav nav-pills nav-pills-primary">
  <?php if($level<=2){ ?>
  <li id="nav_list" class="active"><a href="#list" data-toggle="tab" onclick="update_hash('list')"><i class="fa fa-check-square" aria-hidden="true"></i> Tasks</a></li>
  <?php } ?>
  <li id="nav_details" class="<?= ($level>2 ? 'active' : '') ?>"><a href="#details" data-toggle="tab" onclick="update_hash('details')"><i class="fa fa-info-circle" aria-hidden="true"></i> Details</a></li>
  <li id="nav_tips"><a href="#tips" data-toggle="tab" onclick="update_hash('tips')"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Tips</a></li>
</ul>
<?php } ?>


<div class="tab-content tab-space">
	
	<?php if($level<=2){ ?>
    <div class="tab-pane <?= ($level>2 ? 'hidden' : 'active') ?>" id="list">
    	<?php
    	if($level==1){
    	    ?>
            <p class="maxout" style="margin-top:-30px;">Action Plan is the curriculum for action-driven insights to help students succeed:</p>
        	<ul class="maxout">
    			<li>Define <b><?= $sprint_units[$bootcamp['b_sprint_unit']]['name'] ?> Action Plans</b> with more-or-less equal execution times.</li>
    			<li>Each <?= $bootcamp['b_sprint_unit'] ?> can have its own <b>Task List</b> to further break-down instructions.</li>
    			<li>You can easily add, remove and sort your Action Plan at any time.</li>
    		</ul>
    		<?php
        } elseif($level==2){
            echo '<p class="maxout">Define the tasks necessary to accomplish this '.$bootcamp['b_sprint_unit'].'\'s Primary Goal:</p>';
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
        			<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control autosearch" id="addnode" placeholder="+ <?= ($level==1 ? 'New Action Plan' : 'New Task') ?>"></div>
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
        
        <?php if($level==1){ ?>
        <p style="font-size:0.9em;">Note: The Bootcamp's <b><i class="fa fa-hourglass-end" aria-hidden="true"></i> Action Plan Frequency</b> is set to <b><?= $sprint_units[$bootcamp['b_sprint_unit']]['name'] ?></b>. Modify in <a href="/console/<?= $bootcamp['b_id'] ?>/settings#settings"><u>Settings <i class="fa fa-angle-right" aria-hidden="true"></i></u></a></p>
        <?php } ?>
    </div>
    <?php } ?>
    
    
    
    
    <div class="tab-pane <?= ($level>2 ? 'active' : '') ?>" id="details">
    
    
    	<div class="title"><h4><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Primary Goal</h4></div>
    	<ul>
            <li>Set a goal that is both "Specific" and "Measurable".</li>
            <li>Also used as the title.</li>
		</ul>
        <div class="form-group label-floating is-empty">
            <input type="text" id="c_objective" value="<?= $intent['c_objective'] ?>" class="form-control border">			
        </div>
        
        
        
        <div class="title" style="margin-top:25px;"><h4><i class="fa fa-binoculars" aria-hidden="true"></i> Overview</h4></div>
        <ul class="maxout">
			<?php if($level==2){ ?>
			<li>Instructions on how to execute this <?= $bootcamp['b_sprint_unit'] ?>'s Action Plan.</li>
			<li><?= $sprint_units[$bootcamp['b_sprint_unit']]['name'] ?> Overviews are publicly displayed on the landing page under the "Action Plan" section to help students learn more about this bootcamp. Students get it again at the start of each <?= $bootcamp['b_sprint_unit'] ?>.</li>
			<?php } elseif($level>2){ ?>
			<li>Instructions on how to execute this task.</li>
			<li>Overviews are private & only shared with students at the start of each <?= $bootcamp['b_sprint_unit'] ?>.</li>
			<?php } ?>
		</ul>
        <div id="c_todo_overview"><?= $intent['c_todo_overview'] ?></div>
        <script> var c_todo_overview_quill = new Quill('#c_todo_overview', setting_full); </script>
        
        
        <div style="display:<?= ( $udata['u_status']>=4 ? 'block' : 'none' ) ?>;">
            <div class="title" style="margin-top:25px;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Status</h4></div>
            <ul class="maxout">
    			<li>Default status is <?= status_bible('c',1) ?>.</li>
    			<li>To prevent this from being shown to students set status to <?= status_bible('c',0) ?>.</li>
    		</ul>
            <?php echo_status_dropdown('c','c_status',$intent['c_status']); ?>
        </div>
        
        
        
        <!-- TODO Remove soon -->
        <div style="display:<?= (strlen($intent['c_todo_bible'])>0 ? 'block' : 'none') ?>;">
        <div class="title"><h4>Homework (Being Removed Soon...)</h4></div>
		<p>Instructions for the students to execute towards this goal. Action Plans also include goal-related facts and reference to other content (Videos, Blog Posts, Udemy, images, etc...). Action Plans are "drip-fed" to students meaning they are unlocked with each <?= $sprint_units[$bootcamp['b_sprint_unit']]['name']?> goal.</p>
    	<div id="c_todo_bible"><?= $intent['c_todo_bible'] ?></div>
        <script> var c_todo_bible_quill = new Quill('#c_todo_bible', setting_full); </script>
        </div>
        
        
        
        <div class="title" style="margin-top:25px;"><h4><i class="fa fa-clock-o"></i> Time Estimate</h4></div>
        <ul class="maxout">
			<li>The estimated time to read/execute this task.</li>
			<li>Don't consider sub-tasks as they have their own time estimate.</li>
			<li>Break down tasks into smaller tasks for estimates more than 13 hours.</li>
		</ul>
        <select class="form-control input-mini border" id="c_time_estimate">
        	<?php 
        	$times = $this->config->item('c_time_options');
        	foreach($times as $time){
        	    echo '<option value="'.$time.'" '.( $intent['c_time_estimate']==$time ? 'selected="selected"' : '' ).'>~'.echo_hours($time).'</option>';
        	}
        	?>
        </select>
        
        
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_c();" class="btn btn-primary">Save</a></td><td><span class="save_c_results"></span></td></tr></table>
		
    </div>
    
    
    
    
    
    
    <div class="tab-pane" id="tips">
    	<?php 
    	$i_media_type_names = $this->config->item('i_media_type_names');
    	$i_dispatch_minutes = $this->config->item('i_dispatch_minutes');
    	?>
    	<p class="maxout">Tips are messages sent to students via Facebook Messenger:</p>
    	<ul class="maxout">
			<li>Each tip focuses on a single point or concept.</li>
			<li>Tips communicate facts & best-practices on how to take action.</li>
			<li>Use <b><?= strip_tags($i_media_type_names['text']) ?></b> tips to reference links, Youtube, etc...</li>
			<li>You can use <b>{first_name}</b> in <b><?= strip_tags($i_media_type_names['text']) ?></b> tips for further personalization.</li>
			<li>Tips are private & only shared with students during each <?= $bootcamp['b_sprint_unit'] ?>.</li>
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
        		echo '<div class="add-msg">';
        		echo '<select class="form-control" id="i_media_type" style="width:150px;">';
            		foreach($i_media_type_names as $key=>$name){
            		    echo '<option value="'.$key.'">'.strip_tags($name).'</option>';
            		}
        		echo '</select>';
        		echo '<textarea maxlength="600" onkeyup="countChar(this)" class="form-control" style="height:120px;" id="i_message" placeholder="Plain text Message. Do not include URLs or HTML code."></textarea>';
        		echo '<div id="i_message_counter" style="margin:-15px 0 10px 0; font-size:0.8em;"><span id="charNum">600</span>/600 Remaining.</div>';
        		echo '<input type="url" class="form-control" id="i_url" placeholder="Optional reference URL" />';
        		echo '<div>';
        		
        		  echo '<select class="form-control" id="i_dispatch_minutes">';
        		  foreach($i_dispatch_minutes[$bootcamp['b_sprint_unit']] as $key=>$name){
        		      echo '<option value="'.$key.'">'.strip_tags($name).'</option>';
        		  }
        		  echo '</select>';
        		  
        		echo '</div>';
        		echo '<a href="javascript:msg_create();" class="btn btn-primary" data-toggle="tooltip" title="Ctrl + Enter ;)" style="margin-top:0;">ADD TIP</a>';
        		echo '</div>';
    		echo '</div>';
        echo '</div>';
        ?>
        
    </div>
    
</div>

