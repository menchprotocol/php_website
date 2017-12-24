<?php
//Fetch the sprint units from config:
$sprint_units = $this->config->item('sprint_units');
$core_objects = $this->config->item('core_objects');
$message_max = $this->config->item('message_max');
$intent_statuses = status_bible('c');
$udata = $this->session->userdata('user');
?>
<style> .breadcrumb li { display:block; } </style>
<script>

//This functions updates the input placeholders to refect the next item to be added:
function update_tree_input(){
    //First update the number of milestones in main input field:
    $('#addnode').attr("placeholder", $('#current_units').text()+" #"+($("#list-outbound").children().length)+" Outcome (Specific & Measurable)");

    //Now go through each task list and see whatsupp:
    if($('.task-group').length){
        $( ".task-group" ).each(function() {
            var node_id = $( this ).attr('node-id');
            $('#addnode'+node_id).attr("placeholder", "Task #"+($("#list-outbound-"+node_id).children().length-1)+" Outcome (Specific & Measurable)");
        });
    }
}

function format_hours(dbl_hour){
    dbl_hour = parseFloat(dbl_hour);
    if(dbl_hour<=0){
        return '0';
    } else if(dbl_hour<1){
        //Show this in minutes:
        return Math.round((dbl_hour*60)) + "m";
    } else {
        //Show in rounded hours:
        return ( (dbl_hour % 1 == 0) ? "" : "~" ) + Math.round((dbl_hour)) + "h";
    }
}


$(document).ready(function() {


    //Deletion warning to Tasks & Milestone drop down:
    $('#c_status_2').change(function() {
        if(parseInt($(this).val())<0){
            //Delete has been selected!
            $('#delete_warning').html('<span style="color:#FF0000;"><i class="fa fa-trash" aria-hidden="true"></i> You are about to permanently delete this milestone, its tasks and all related messages. You may want to move tasks to other milestones before deleting this milestone.</span>');
        } else {
            $('#delete_warning').html('');
        }
    });
    $('#c_status_3').change(function() {
        if(parseInt($(this).val())<0){
            //Delete has been selected!
            $('#delete_warning').html('<span style="color:#FF0000;"><i class="fa fa-trash" aria-hidden="true"></i> You are about to permanently delete this task and all its messages.</span>');
        } else {
            $('#delete_warning').html('');
        }
    });


    //Enforce Alphanumeric for URL Key:
    $('#b_url_key').keypress(function (e) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });


    //Make iPhone X Sticky for scrolling longer lists
    $(".main-panel").scroll(function() {
        var top_position = $(this).scrollTop();
        clearTimeout($.data(this, 'scrollTimer'));
        $.data(this, 'scrollTimer', setTimeout(function() {
            $("#iphonex").css('top',(top_position-70)); //PX also set in style.css for initial load
            $("#modifybox").css('top',(top_position-0)); //PX also set in style.css for initial load
        }, 34));
    });


    //addnode
    if(window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if(hash_parts.length>=2){
            var level_id = parseInt($('.maplevel'+hash_parts[1]).attr('level-id'));
            if(level_id>0){
                //Fetch level if available:
                if(hash_parts[0]=='messages'){
                    load_iphone(hash_parts[1],level_id);
                } else if(hash_parts[0]=='modify'){
                    load_modify(hash_parts[1],level_id);
                }
            }
        }
    }

    //Update bootcamp houts:
    $('.hours_level_1').text(format_hours($('.hours_level_1').attr('current-hours')));

    //Loadup Milestone numbering based on duratioan extenstions:
    intents_sort(0,2);

    //Activate sorting for Tasks:
    if($('.task-group').length){
        $( ".task-group" ).each(function() {

            var node_id = $( this ).attr('node-id');

            //Load sorting:
            load_intent_sort(node_id,"3");

            //Load time:
            $('#t_estimate_'+node_id).text(format_hours($('#t_estimate_'+node_id).attr('current-hours')));

        });

        if($('.is_task_sortable').length){
            //Goo through all Tasks:
            $( ".is_task_sortable" ).each(function() {
                var node_id = $(this).attr('node-id');
                if(node_id){
                    //Load time:
                    $('#t_estimate_'+node_id).text(format_hours($('#t_estimate_'+node_id).attr('current-hours')));
                }
            });
        }

    }

    //Update counters on load:
    update_tree_input();
    //Also update every time DOM changes
    //TODO This is probably a heavy process, but I hacked it for now until we can improve later...
    $('#list-outbound').bind("DOMSubtreeModified",function(){
        update_tree_input();
    });


	//Load Sortable:
	load_intent_sort($("#pid").val(),"2");





    //Watch the expand/close all buttons for Milestones:
    $('#milestone_view .expand_all').click(function (e) {
        $( "#list-outbound>.is_sortable" ).each(function() {
            ms_toggle($( this ).attr('node-id'),1);
        });
    });
    $('#milestone_view .close_all').click(function (e) {
        $( "#list-outbound>.is_sortable" ).each(function() {
            ms_toggle($( this ).attr('node-id'),0);
        });
    });


	//Add new Milestone:
    $('#dir_handle').click(function (e) {
        new_intent($('#pid').val(),2);
    });
    $( "#addnode" ).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return new_intent($('#pid').val(),2);
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







function add_to_list(sort_list_id,sort_handler,html_content){
    //See if we already have a list in place?
    if($( "#"+sort_list_id+" "+sort_handler).length>0){
        //yes we do! add this:
        $( "#"+sort_list_id+" "+sort_handler+":last").after(html_content);
    } else {
        //Empty list, add before input filed:
        $( "#"+sort_list_id).prepend(html_content);
    }
}

//$(\'.menu-cont-'.$intent['c_id'].', #list-outbound-'.$intent['c_id'].'\').toggle();
//id="'.$intent['c_id'].'" class="fa "
function ms_toggle(c_id,new_state=null){
    if(new_state === null){
        //Detect new state:
        new_state = ( $('#list-outbound-'+c_id).hasClass('hidden') ? 1 : 0 );
    }

    if(new_state){
        //open:
        $('#list-outbound-'+c_id).removeClass('hidden');
        $('#handle-'+c_id).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
    } else {
        //Close:
        $('#list-outbound-'+c_id).addClass('hidden');
        $('#handle-'+c_id).removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
    }
}

function new_intent(pid,next_level){

    //Set variables mostly based on level:
    if(next_level==2){
        var input_field = $('#addnode');
        var sort_list_id = "list-outbound";
        var sort_handler = ".is_sortable";
    } else if(next_level==3){
        var input_field = $('#addnode'+pid);
        var sort_list_id = "list-outbound-"+pid;
        var sort_handler = ".is_task_sortable";
    }

    var b_id = $('#b_id').val();
    var intent_name = input_field.val();


 	if(intent_name.length<1){
 		alert('Error: Missing Outcome. Try Again...');
        input_field.focus();
 		return false;
 	}

 	//Set processing status:
    add_to_list(sort_list_id,sort_handler,'<div id="temp'+next_level+'" class="list-group-item"><img src="/img/round_load.gif" class="loader" /> Adding... </div>');

     //Empty Input:
    input_field.val("").focus();

 	//Update backend:
 	$.post("/api_v1/intent_create", {b_id:b_id, pid:pid, c_objective:intent_name, next_level:next_level}, function(data) {
 		//Update UI to confirm with user:
 		$( "#temp"+next_level ).remove();

 		//Add new
        add_to_list(sort_list_id,sort_handler,data);

 		//Resort:
 		load_intent_sort(pid,next_level);

 		if(next_level==2){
 		    //Adjust the Milestone count:
            intents_sort(0,2);
        }

 		//Tooltips:
 		$('[data-toggle="tooltip"]').tooltip();
 	});

 	//Prevent form submission:
    event.preventDefault();
 	return false;
}



/*
function link_lintent(target_id){
    //TODO Update based on new_intent() changes when implementing search
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

        //TODO Resort

 		//Tooltips:
 		$('[data-toggle="tooltip"]').tooltip();
 	});
}
*/



function intents_sort(c_id,level){

    if(level==2){
        var s_element = "list-outbound";
        var s_draggable = ".is_sortable";
    } else if(level==3){
        var s_element = "list-outbound-"+c_id;
        var s_draggable = ".is_task_sortable";
    } else {
        //Should not happen!
        return false;
    }

    //Fetch new sort:
    var new_sort = [];
 	var sort_rank = 0;

 	$( "#"+s_element+" "+s_draggable ).each(function() {
        //Make sure this is NOT the dummy drag in box
 	    if(!$(this).hasClass('dropin-box')){

 	        //Fetch variables for this intent:
            var pid = parseInt($(this).attr('node-id'));
            var cr_id = parseInt($( this ).attr('data-link-id'));
            var status = parseInt($('.c_objective_'+pid).attr('current-status'));
            var increments = ( level==2 ? parseInt($('.c_objective_'+pid).attr('current-duration')) : 1 ); //The default for all nodes
            var prefix = ( level==2 ? '<i class="fa fa-flag" aria-hidden="true"></i> <span class="b_sprint_unit">'+$('#current_units').text()+'</span>' : '<i class="fa fa-check-square-o" aria-hidden="true"></i>' ); //The default for all nodes

            if(status>=1){

                //Remove potential line throughs:
                $('#t_estimate_'+pid).removeClass('crossout');

                sort_rank++;
                new_sort[sort_rank] = cr_id;

                //Update sort handler:
                $( "#cr_"+cr_id+" .inline-level-"+level ).html( prefix + ' #' + ( !(level==2) || increments<=1 ? sort_rank : sort_rank+'-'+(sort_rank + increments - 1)) );

                //Did we have an extended Milestone? Add the extra time now so it does not impact the base ranking number:
                if(increments>1){
                    sort_rank = sort_rank + increments - 1;
                }

            } else {

                //Add line through:
                $('#t_estimate_'+pid).addClass('crossout');

                //Give relative position:
                new_sort[sort_rank] = cr_id;
                $( "#cr_"+cr_id+" .inline-level-"+level ).html('<b><i class="fa fa-pencil-square"></i> DRAFTING</b>');
            }
        }
 	});

 	//It might be zero for lists that have jsut been emptied
 	if(sort_rank>0 && c_id){
        //Update backend:
        $.post("/api_v1/intents_sort", { pid:c_id, b_id:$('#b_id').val(), new_sort:new_sort }, function(data) {
            //Update UI to confirm with user:
            if(!data.status){
                //There was some sort of an error returned!
                alert('ERROR: '+data.message);
            }
        });
    }
}


function load_intent_sort(pid,level){

    if(level==2){
        var s_element = "list-outbound";
        var s_draggable = ".is_sortable";
    } else if(level==3){
        var s_element = "list-outbound-"+pid;
        var s_draggable = ".is_task_sortable";
    } else {
        //Should not happen!
        return false;
    }


	var theobject = document.getElementById(s_element);
	var settings = {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: s_draggable, // Specifies which items inside the element should be sortable
        handle: ".fa-bars", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/){
            intents_sort(pid,level);
        }
    };

	//Enable between list moves:
	if(level=="3"){
        settings['group'] = "tasklists";
        settings['ghostClass'] = "drop-task-here";
        settings['onAdd'] = function (evt) {
            //Define variables:
            var inputs = {
                cr_id:evt.item.attributes[1].nodeValue,
                c_id:evt.item.attributes[2].nodeValue,
                b_id:$('#b_id').val(),
                from_c_id:evt.from.attributes[2].value,
                to_c_id:evt.to.attributes[2].value,
            };
            //Update:
            $.post("/api_v1/migrate_task", inputs, function(data) {
                //Update sorts in both lists:
                if(!data.status){

                    //There was some sort of an error returned!
                    alert('ERROR: '+data.message);

                } else {

                    //All good as expected!
                    //Moved the parent pointer:
                    $('.maplevel'+inputs.c_id).attr('parent-node-id',inputs.to_c_id);

                    //Determine core variables for hour move calculations:
                    var task_hours = parseFloat($('#t_estimate_'+inputs.c_id).attr('current-hours'));
                    var task_status = parseInt($('.c_objective_'+inputs.c_id).attr('current-status'));
                    var from_milestone_status = parseInt($('.c_objective_'+inputs.from_c_id).attr('current-status'));
                    var to_milestone_status = parseInt($('.c_objective_'+inputs.to_c_id).attr('current-status'));

                    if(!(task_hours==0) && task_status>0){

                        //Remove from old one:
                        var from_hours_new = parseFloat($('#t_estimate_'+inputs.from_c_id).attr('current-hours'))-task_hours;
                        $('#t_estimate_'+inputs.from_c_id).attr('current-hours',from_hours_new).text(format_hours(from_hours_new));

                        //Add to new:
                        var to_hours_new = parseFloat($('#t_estimate_'+inputs.to_c_id).attr('current-hours'))+task_hours;
                        $('#t_estimate_'+inputs.to_c_id).attr('current-hours',to_hours_new).text(format_hours(to_hours_new));

                        //Adjust Bootcamp hours if necessary:
                        if(!(from_milestone_status==to_milestone_status)){
                            //Yes we need to adjust as the statuses of these milestones are different:
                            var current_hours_bootcamp = parseFloat($('.hours_level_1').attr('current-hours'));
                            //Determine what to do:
                            var new_bootcamp_hours = current_hours_bootcamp + ( ( from_milestone_status>to_milestone_status ? -1 : 1 ) * task_hours );
                            $('.hours_level_1').attr('current-hours',new_bootcamp_hours).text(format_hours(new_bootcamp_hours));
                        }
                    }

                    //Update sorting for both lists:
                    intents_sort(inputs.from_c_id,"3");
                    intents_sort(inputs.to_c_id,"3");

                }
            });
        };
    }
 	var sort = Sortable.create( theobject , settings );
}






function load_iphone(c_id, level){

    var messages_focus_pid = ( $('#iphonex').hasClass('hidden') ? 0 : parseInt($('#iphonex').attr('node-id')) );

    //Check to see if its open or close:
    if(messages_focus_pid==c_id){

        //close and return
        //$('#iphonex').addClass('hidden');
        $('#iphonex').hide().fadeIn();
        return false;

    } else {

        //Make the frame visible:
        $("#iphonex").removeClass('hidden').hide().fadeIn();
        $('#modifybox').addClass('hidden');
        var handler = $( "#iphone-screen" );

        //Define the top menu that would not change:
        $('#iphonex').attr('node-id',c_id);

        //Define standard phone header:
        var top_menu = '<div class="ix-top">\n' +
            '<span class="ix-top-left" data-toggle="tooltip" title="PST Time" data-placement="bottom"><?= date("H:i") ?></span>\n' +
            '<span class="ix-top-right">\n' +
            '<i class="fa fa-wifi" aria-hidden="true"></i>\n' +
            '<i class="fa fa-battery-full" aria-hidden="true"></i>\n' +
            '</span>\n' +
            '</div>';

        //Show tem loader:
        handler.html('<div style="text-align:center; padding-top:89px; padding-bottom:89px;"><img src="/img/round_load.gif" class="loader" /></div>');

        //Load the frame:
        $.post("/api_v1/load_iphone", {

            b_id:$('#b_id').val(),
            c_id:c_id,
            level:level,

        }, function(data) {

            //Empty Inputs Fields if success:
            handler.html(top_menu+data);

            //SHow inner tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        });
    }
}



function load_modify(c_id, level){

    //$('.levelz, #modifybox').removeClass('hidden');
    var modify_focus_pid = ( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('node-id')) );
    var modify_focus_level = ( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('level')) );

    //Do we already have this loaded? Then we should close it:
    if(modify_focus_pid==c_id){

        //$('#modifybox').addClass('hidden');
        $('#modifybox').hide().fadeIn();
        return false;

    } else {

        //Loadup variables for Milestones & Tasks:
        if(level==2){

            $('#c_objective2 .c_objective_input').val($(".c_objective_"+c_id).html());
            //Fetch current duration
            $('#modifybox #c_duration_multiplier').val($('.c_objective_'+c_id).attr('current-duration'));
            //Fetch current status
            $('#modifybox #c_status_2').val($('.c_objective_'+c_id).attr('current-status'));

        } else if(level==3){

            //Fetch current time:
            $('#c_objective3 .c_objective_input').val($(".c_objective_"+c_id).html());
            $('.timer_3').val($('#t_estimate_'+c_id).attr('current-hours'));
            //Fetch current status
            $('#modifybox #c_status_3').val($('.c_objective_'+c_id).attr('current-status'));

        }

        //Make the frame visible:
        $("#modifybox").removeClass('hidden').hide().fadeIn();
        $('#iphonex').addClass('hidden');

        //Reset potential delete message:
        $('#delete_warning').html('');

        //Show the right elements based on level:
        $('.levelz').addClass('hidden');
        $('.level'+level).removeClass('hidden');

        //Update variables:
        $('#modifybox').attr('node-id',c_id);
        $('#modifybox').attr('level',level);

    }
}









function save_modify(){

    //Define shared data for all 3 levels:
    var modify_data = {
        b_id:$('#b_id').val(),
        pid:( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('node-id')) ),
        level:( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('level')) ),
    };

    if(!modify_data['pid'] || !modify_data['level']){

        //Oops, this should not happen!
        return false;

    } else {

        //Now append more based on levels and take action:
        if(modify_data['level']==1){

            modify_data['c_objective'] = $('#c_objective1 .c_objective_input').val();
            modify_data['b_sprint_unit'] = $('input[name=b_sprint_unit]:checked').val();
            modify_data['b_url_key'] = $('#b_url_key').val();
            modify_data['b_status'] = $('#b_status').val();

        } else if(modify_data['level']==2){

            modify_data['c_objective'] = $('#c_objective2 .c_objective_input').val();
            modify_data['c_duration_multiplier'] = $('#c_duration_multiplier').val();
            modify_data['c_status'] = $('#c_status_2').val();

        } else if(modify_data['level']>=3){

            modify_data['c_objective'] = $('#c_objective3 .c_objective_input').val();
            modify_data['c_time_estimate'] = $('#c_time_estimate').val();
            modify_data['c_status'] = $('#c_status_3').val();

        }

        //Show spinner:
        $('.save_setting_results').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();


        //Save the rest of the content:
        $.post("/api_v1/save_modify", modify_data , function(data) {
            if(data.status){

                //Always update title:
                $(".c_objective_"+modify_data['pid']).html(modify_data['c_objective']);

                //Update page variables:
                if(modify_data['level']==1){

                    //Also adjust top left title:
                    $("#top-left-title").html(modify_data['c_objective']);

                    //Update status:
                    $('#status_holder').html($('.b_status_'+modify_data['b_status']).html());
                    $('#status_holder [data-toggle="tooltip"]').tooltip();

                    if(modify_data['b_sprint_unit']=='day'){
                        $(".b_sprint_unit").text('Day');
                        $(".b_sprint_unit2").text('Daily');
                    } else {
                        $(".b_sprint_unit").text('Week');
                        $(".b_sprint_unit2").text('Weekly');
                    }

                    //URL Update:
                    $(".landing_page_url").attr("href", "/"+modify_data['b_url_key']);
                    $(".url_anchor").text(modify_data['b_url_key']);

                    update_tree_input(); //Updates in text box for milestones...

                } else if(modify_data['level']==2){




                    //Update duration?
                    var current_duration = parseInt($('.c_objective_'+modify_data['pid']).attr('current-duration'));
                    if(!(current_duration==modify_data['c_duration_multiplier'])){
                        //Needs to update:
                        $('.c_objective_'+modify_data['pid']).attr('current-duration',modify_data['c_duration_multiplier']);

                        //Resort all Milestones to illustrate changes on UI:
                        intents_sort(0,2);
                    }


                    //Update status?
                    var current_status = parseInt($('.c_objective_'+modify_data['pid']).attr('current-status'));
                    if(!(current_status==modify_data['c_status'])) {
                        //Needs to update:
                        $('.c_objective_' + modify_data['pid']).attr('current-status', modify_data['c_status']);

                        var current_hours_milestone = parseFloat($('#t_estimate_' + modify_data['pid']).attr('current-hours'));
                        var current_hours_bootcamp = parseFloat($('.hours_level_1').attr('current-hours'));

                        //Does this need to be removed from the totals?
                        if (current_status == 1 && modify_data['c_status'] <= 0 && current_hours_milestone > 0) {
                            //We need to remove initial hours from the totals:
                            $('.hours_level_1').attr('current-hours', (current_hours_bootcamp - current_hours_milestone)).text(format_hours(current_hours_bootcamp - current_hours_milestone));
                        } else if (current_status <= 0 && modify_data['c_status'] > 0 && current_hours_milestone > 0) {
                            //We need to add the hours to the total:
                            $('.hours_level_1').attr('current-hours', (current_hours_bootcamp + current_hours_milestone)).text(format_hours(current_hours_bootcamp + current_hours_milestone));
                        }

                        //Has this been deleted?
                        if (modify_data['c_status'] < 0) {
                            //Yes! Remove from UI:
                            $('.node_line_' + modify_data['pid']).html('<span style="color:#222;"><i class="fa fa-trash" aria-hidden="true"></i> Deleted</span>');
                            //Disapper in a while:
                            setTimeout(function () {
                                //Hide the editor & saving results:
                                $('.node_line_' + modify_data['pid']).fadeOut();
                                setTimeout(function () {
                                    //Hide the editor & saving results:
                                    $('.node_line_' + modify_data['pid']).remove();

                                    //Hide editing box:
                                    $('#modifybox').addClass('hidden');

                                    //Resort all Milestones to illustrate changes on UI:
                                    intents_sort(0, 2);
                                }, 377);
                            }, 1597);
                        } else {
                            //Resort all Milestones to illustrate changes on UI:
                            intents_sort(0, 2);
                        }
                    }

                } else if(modify_data['level']>=3){

                    //Update time?
                    var current_hours_task = parseFloat($('#t_estimate_'+modify_data['pid']).attr('current-hours'));
                    var task_deficit = modify_data['c_time_estimate'] - current_hours_task;
                    var parent_c_id = parseInt($('.maplevel'+modify_data['pid']).attr('parent-node-id'));


                    //Update status?
                    var current_status = parseInt($('.c_objective_'+modify_data['pid']).attr('current-status'));
                    if(!(current_status==modify_data['c_status'])){
                        //Needs to update:
                        $('.c_objective_'+modify_data['pid']).attr('current-status',modify_data['c_status']);

                        if(current_status==1 && modify_data['c_status']<=0){
                            //We need to remove initial hours from the totals:
                            task_deficit = -(current_hours_task);
                        } else if(current_status<=0 && modify_data['c_status']==1){
                            //We need to remove initial hours from the totals:
                            task_deficit = +(modify_data['c_time_estimate']);
                        }

                        //Has this been deleted?
                        if(modify_data['c_status']<0){
                            //Yes! Remove from UI:
                            $('.node_line_'+modify_data['pid']).html('<span style="color:#222;"><i class="fa fa-trash" aria-hidden="true"></i> Deleted</span>');
                            //Disapper in a while:
                            setTimeout(function() {
                                //Hide the editor & saving results:
                                $('.node_line_'+modify_data['pid']).fadeOut();
                                setTimeout(function() {
                                    //Hide the editor & saving results:
                                    $('.node_line_'+modify_data['pid']).remove();

                                    //Hide editing box:
                                    $('#modifybox').addClass('hidden');

                                    //Resort all Milestones to illustrate changes on UI:
                                    intents_sort(parent_c_id,3);
                                }, 377);
                            }, 1597);
                        } else {
                            //Resort all Milestones to illustrate changes on UI:
                            intents_sort(parent_c_id,3);
                        }
                    }

                    if(!(task_deficit==0)){

                        //Adjust 3 levels of hours:
                        var current_hours_bootcamp = parseFloat($('.hours_level_1').attr('current-hours'));
                        var current_hours_milestone = parseFloat($('#t_estimate_'+parent_c_id).attr('current-hours'));
                        var current_milestone_status = parseInt($('.c_objective_'+parent_c_id).attr('current-status'));


                        //Update milestone if task is active:
                        if(( modify_data['c_status']>0 || !(current_status==modify_data['c_status']) )){
                            //Update Miletsone:
                            $('#t_estimate_'+parent_c_id).attr('current-hours',(current_hours_milestone + task_deficit)).text(format_hours((current_hours_milestone + task_deficit)));
                            //Only update Bootcamp if Milestone+Task are Active:
                            if(current_milestone_status>0){
                                $('.hours_level_1').attr('current-hours',(current_hours_bootcamp + task_deficit)).text(format_hours((current_hours_bootcamp + task_deficit)));
                            }
                        }
                        //Always update the task:
                        $('#t_estimate_'+modify_data['pid']).attr('current-hours',modify_data['c_time_estimate']).text(format_hours(modify_data['c_time_estimate']));
                    }
                }

                //Update UI to confirm with user:
                $('.save_setting_results').html(data.message).hide().fadeIn();

                //Disapper in a while:
                setTimeout(function() {
                    //Hide the editor & saving results:
                    $('.save_setting_results').hide();
                }, 1000);

            } else {
                //Ooops there was an error!
                $('.save_setting_results').html('<span style="color:#FF0000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+data.message+'</span>').hide().fadeIn();
            }
        });
    }
}


function tree_message(c_id,u_id){

    //Show loading:
    $('#simulate_'+c_id).attr('href','#');
    $('#simulate_'+c_id).html('<span><img src="/img/round_load.gif" style="width:24px;" class="loader" /></span>');

    $.post("/api_v1/simulate_milestone", {
        c_id:c_id,
        depth:1,
        b_id:$('#b_id').val(),
        u_id:u_id,
    }, function(data) {
        //Show success:
        $('#simulate_'+c_id).html(data);
    });
}

</script>



<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />
<input type="hidden" id="pid" value="<?= $intent['c_id'] ?>" />
<div id="current_units" class="b_sprint_unit2 hidden"><?= ucwords($bootcamp['b_sprint_unit']) ?></div>


<div class="row">
	<div class="col-xs-6">


        <div class="help_body below_h" id="content_592"></div>
		<?php

        //Show relevant tips:
        /*
        if($level==1){
            itip(599);
        } elseif($level==2){
            itip(602);
        }
        */
        echo '<div id="bootcamp-objective" class="list-group">';
            echo echo_cr($bootcamp['b_id'],$bootcamp,'outbound',$level,$bootcamp['b_sprint_unit']);
        echo '</div>';

        /*
        ?>

        <ul id="topnav" class="nav nav-pills nav-pills-primary">
          <li id="nav_input" class="active"><a href="#input" data-toggle="tab" onclick="update_hash('input')"><i class="fa fa-sign-in" aria-hidden="true"></i> Input</a></li>
          <li id="nav_process"><a href="#process" data-toggle="tab" onclick="update_hash('process')"><i class="fa fa-refresh" aria-hidden="true"></i> Process</a></li>
          <li id="nav_output "><a href="#output" data-toggle="tab" onclick="update_hash('output')"><i class="fa fa-sign-out" aria-hidden="true"></i> Output</a></li>

            <li id="nav_milestones" class="active"><a href="#milestones" data-toggle="tab" onclick="update_hash('milestones')"><i class="fa fa-flag" aria-hidden="true"></i> Milestones</a></li>
            <li id="nav_audience"><a href="#audience" data-toggle="tab" onclick="update_hash('audience')"><i class="fa fa-bullseye" aria-hidden="true"></i> Audience</a></li>
            <li id="nav_prerequisites"><a href="#prerequisites" data-toggle="tab" onclick="update_hash('prerequisites')"><i class="fa fa-check-square-o" aria-hidden="true"></i> Prerequisites</a></li>
            <li id="nav_questions"><a href="#questions" data-toggle="tab" onclick="update_hash('questions')"><i class="fa fa-question-circle" aria-hidden="true"></i> Questions</a></li>
            <li id="nav_prizes"><a href="#prizes" data-toggle="tab" onclick="update_hash('prizes')"><i class="fa fa-trophy" aria-hidden="true"></i> Prizes</a></li>
        </ul>


        <div class="tab-content tab-space">

            <div class="tab-pane" id="entry">

            </div>
            <div class="tab-pane active" id="milestones">

            </div>
            <div class="tab-pane" id="outcome">

            </div>
        </div>


        <?php
        */

        //Milestone Expand/Contract all if more than 2
        if(count($intent['c__child_intents'])>2){
            echo '<div id="milestone_view">';
            echo '<i class="fa fa-plus-square expand_all" aria-hidden="true"></i> &nbsp;';
            echo '<i class="fa fa-minus-square close_all" aria-hidden="true"></i>';
            echo '</div>';
        }
        //Milestones List:
        echo '<div id="list-outbound" class="list-group">';

            foreach($intent['c__child_intents'] as $key=>$sub_intent){
                echo echo_cr($bootcamp['b_id'],$sub_intent,'outbound',($level+1),$bootcamp['b_sprint_unit'],$bootcamp['b_id']);
            }
            ?>
            <div class="list-group-item list_input">
        		<div class="input-group">
        			<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control autosearch" maxlength="<?= $core_objects['c']['maxlength'] ?>" id="addnode" placeholder=""></div>
        			<span class="input-group-addon" style="padding-right:8px;">
        				<span id="dir_handle" data-toggle="tooltip" title="or press ENTER ;)" data-placement="top" class="badge badge-primary pull-right" style="cursor:pointer; margin: 1px 3px 0 6px;">
        					<div><i class="fa fa-plus"></i></div>
        				</span>
        			</span>
        		</div>
        	</div>
        	<?php
        echo '</div>';
        ?>

	</div>
	<div class="col-xs-6" id="iphonecol">

        <div id="modifybox" class="hidden" node-id="0" level="0">

            <div style="text-align:right; font-size: 22px; margin: -5px 0 -20px 0;"><a href="javascript:$('#modifybox').addClass('hidden')"><i class="fa fa-times" aria-hidden="true"></i></a></div>

            <div id="c_objective1" class="levelz level1 hidden">
                <?php $this->load->view('console/inputs/c_objective' , array(
                    'c_objective' => $bootcamp['c_objective'],
                    'level' => 1,
                )); ?>
            </div>
            <div id="c_objective2" class="levelz level2 hidden">
                <?php $this->load->view('console/inputs/c_objective' , array(
                    'c_objective' => null,
                    'level' => 2,
                )); ?>
            </div>
            <div id="c_objective3" class="levelz level3 hidden">
                <?php $this->load->view('console/inputs/c_objective' , array(
                    'c_objective' => null,
                    'level' => 3,
                )); ?>
            </div>


            <div class="levelz level1 hidden">
                <div class="title" style="margin-top:40px;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Bootcamp Status <span id="hb_627" class="help_button" intent-id="627"></span></h4></div>
                <div class="help_body maxout" id="content_627"></div>
                <?= echo_status_dropdown('b','b_status',$bootcamp['b_status']); ?>
                <div style="clear:both; margin:0; padding:0;"></div>
            </div>


            <div class="levelz level1 hidden" style="margin-top:15px;">
                <?php $this->load->view('console/inputs/b_sprint_unit' , array('b_sprint_unit'=>$bootcamp['b_sprint_unit']) ); ?>
            </div>


            <div class="levelz level1 hidden" style="margin-top:30px;">
                <div class="title"><h4><i class="fa fa-link" aria-hidden="true"></i> Landing Page URL <span id="hb_725" class="help_button" intent-id="725"></span></h4></div>
                <div class="help_body maxout" id="content_725"></div>
                <div class="form-group label-floating is-empty">
                    <div class="input-group border">
                        <span class="input-group-addon addon-lean" style="color:#222; font-weight: 300;">https://mench.co/</span>
                        <input type="text" id="b_url_key" style="margin:0 !important; font-size:18px !important; padding-left:0;" value="<?= $bootcamp['b_url_key'] ?>" maxlength="30" class="form-control" />
                    </div>
                </div>
            </div>










            <div class="levelz level2 hidden" style="margin-top:30px;">
                <div class="title"><h4><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Extend Milestone <span id="hb_601" class="help_button" intent-id="601"></span></h4></div>
                <div class="help_body maxout" id="content_601"></div>
                <div class="form-group label-floating is-empty">
                    <select class="form-control input-mini border" id="c_duration_multiplier" style="display: inline;">
                        <?php
                        $extend_options = array(1,2,3);
                        foreach($extend_options as $eo){
                            echo '<option value="'.$eo.'" '.( $intent['c_time_estimate']==$eo ? 'selected="selected"' : '' ).'>'.$eo.'</option>';
                        }
                        ?>
                    </select> <span><span class="b_sprint_unit"><?= ucwords($bootcamp['b_sprint_unit']) ?></span><span class="pr_s"></span></span>
                </div>
            </div>



            <div class="levelz level3 hidden" style="margin-top:30px;">
                <?php $times = $this->config->item('c_time_options'); ?>
                <div class="title"><h4><i class="fa fa-clock-o"></i> Time Estimate <span id="hb_609" class="help_button" intent-id="609"></span></h4></div>
                <div class="help_body maxout" id="content_609"></div>
                <select class="form-control input-mini border timer_3" id="c_time_estimate">
                    <?php
                    foreach($times as $time){
                        echo '<option value="'.$time.'" '.( $intent['c_time_estimate']==$time ? 'selected="selected"' : '' ).'>~'.echo_hours($time).' = '.round($time*60).' On-Time Points OR '.floor($time*60*0.5).' Late Point'.(round($time*60)==1?'':'s').'</option>';
                    }
                    ?>
                </select>
            </div>




            <div class="levelz level2 hidden" style="margin-top:30px;">
                <div class="title"><h4><i class="fa fa-circle" aria-hidden="true"></i> Milestone Status</h4></div>
                <div class="form-group label-floating is-empty">
                    <select class="form-control input-mini border" id="c_status_2">
                        <?php
                        foreach($intent_statuses as $status_id=>$status){
                            echo '<option value="'.$status_id.'">'.$status['s_name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div class="levelz level3 hidden" style="margin-top:30px;">
                <div class="title"><h4><i class="fa fa-circle" aria-hidden="true"></i> Task Status</h4></div>
                <div class="form-group label-floating is-empty">
                    <select class="form-control input-mini border" id="c_status_3">
                        <?php
                        foreach($intent_statuses as $status_id=>$status){
                            echo '<option value="'.$status_id.'">'.$status['s_name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div id="delete_warning"></div>

            <table width="100%" style="margin-top:15px;"><tr><td class="save-td"><a href="javascript:save_modify();" class="btn btn-primary">Save</a></td><td><span class="save_setting_results"></span></td></tr></table>
        </div>


        <div class="marvel-device iphone-x hidden" id="iphonex" node-id="">
            <div style="font-size: 22px; margin: -5px 0 -20px 0; top: 0; right: 0px; position: absolute; z-index:9999999;"><a href="javascript:$('#iphonex').addClass('hidden')"><i class="fa fa-times" aria-hidden="true"></i></a></div>
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
            <div class="screen" id="iphone-screen">
            </div>

        </div>


    </div>
</div>