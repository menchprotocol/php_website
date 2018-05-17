

$(document).ready(function() {

    //Add new Task:
    $('#dir_handle').click(function (e) {
        new_intent($('#pid').val(),2);
    });
    $( "#addintent" ).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return new_intent($('#pid').val(),2);
        }
    });

    //Deletion warning to Steps & Task drop down:
    $('#c_status_2').change(function() {
        if(parseInt($(this).val())<0){
            //Delete has been selected!
            $('#delete_warning').html('<span style="color:#FF0000;"><i class="fas fa-trash-alt"></i> You are about to permanently delete this Task, its Steps and all related messages.</span>');
        } else {
            $('#delete_warning').html('');
        }
    });
    $('#c_status_3').change(function() {
        if(parseInt($(this).val())<0){
            //Delete has been selected!
            $('#delete_warning').html('<span style="color:#FF0000;"><i class="fas fa-trash-alt"></i> You are about to permanently delete this Step and all its messages.</span>');
        } else {
            $('#delete_warning').html('');
        }
    });


    //Loadup Task numbering based on duration extensions:
    c_sort(0,2);

    //Activate sorting for Steps:
    if($('.step-group').length){

        $( ".step-group" ).each(function() {

            var intent_id = $( this ).attr('intent-id');

            //Load sorting:
            load_intent_sort(intent_id,"3");

            //Load time:
            $('#t_estimate_'+intent_id).text(echo_hours($('#t_estimate_'+intent_id).attr('current-hours')));

        });

        if($('.is_step_sortable').length){
            //Goo through all Steps:
            $( ".is_step_sortable" ).each(function() {
                var intent_id = $(this).attr('intent-id');
                if(intent_id){
                    //Load time:
                    $('#t_estimate_'+intent_id).text(echo_hours($('#t_estimate_'+intent_id).attr('current-hours')));
                }
            });
        }

    }

});



function tree_message(c_id,u_id){

    //Show loading:
    $('#simulate_'+c_id).attr('href','#').html('<span><img src="/img/round_load.gif" style="width:16px; height:16px; margin-top:-2px;" class="loader" /></span>');

    //Disapper in a while:
    setTimeout(function() {
        //Hide the editor & saving results:
        $.post("/api_v1/i_dispatch", {
            c_id:c_id,
            depth:1,
            b_id:$('#b_id').val(),
            u_id:u_id,
        }, function(data) {
            //Show success:
            $('#simulate_'+c_id).html(data);
        });
    }, 334);

}



function new_intent(pid,next_level){

    //Set variables mostly based on level:
    if(next_level==2){
        var input_field = $('#addintent');
        var sort_list_id = "list-outbound";
        var sort_handler = ".is_sortable";
    } else if(next_level==3){
        var input_field = $('#addintent'+pid);
        var sort_list_id = "list-outbound-"+pid;
        var sort_handler = ".is_step_sortable";
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
    $.post("/api_v1/c_new", {b_id:b_id, pid:pid, c_outcome:intent_name, next_level:next_level}, function(data) {

        //Update UI to confirm with user:
        $( "#temp"+next_level ).remove();

        //Add new
        add_to_list(sort_list_id,sort_handler,data.html);

        //Re-adjust sorting:
        load_intent_sort(pid,next_level);

        if(next_level==2){

            //Adjust the Task count:
            c_sort(0,2);

            //Re-adjust sorting for inner Steps:
            load_intent_sort(data.c_id,3);

        } else {
            //Adjust Step sorting:
            c_sort(pid,next_level);
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Add the new time:
        var step_deficit = 0.05; //3 minutes is the default for new Tasks/Steps
        var current_b_hours = parseFloat($('.hours_level_1').attr('current-hours'));
        var current_task_hours = parseFloat($('#t_estimate_'+pid).attr('current-hours'));
        var current_task_status = parseInt($('.c_outcome_'+pid).attr('current-status'));

        //Update Miletsone:
        $('#t_estimate_'+pid).attr('current-hours',(current_task_hours + step_deficit)).text(echo_hours((current_task_hours + step_deficit)));

        //Only update Bootcamp if Task is active:
        if(current_task_status>0){
            $('.hours_level_1').attr('current-hours',(current_b_hours + step_deficit)).text(echo_hours((current_b_hours + step_deficit)));
        }

    });

    //Prevent form submission:
    event.preventDefault();
    return false;
}

