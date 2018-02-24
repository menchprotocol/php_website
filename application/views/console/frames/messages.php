<?php

//Fetch Messages based on c_id:
$message_max = $this->config->item('message_max');
$core_objects = $this->config->item('core_objects');
$i_statuses = status_bible('i', null);
$udata = $this->session->userdata('user');
$drip_enabled = ($level>1);
$i_messages = $this->Db_model->i_fetch(array(
    'i_c_id' => $c_id,
    'i_status >' => 0, //Published in any form
));

//Fetch intent details:
$intents = $this->Db_model->c_fetch(array(
    'c.c_id' => $c_id,
));

//Fetch Bootcamp:
$bootcamps = $this->Db_model->b_fetch(array(
    'b.b_id' => $b_id,
));

if(!isset($intents[0])){
    //This should never happen:
    die('Invalid input id.');
} elseif(count($bootcamps)<1){
    //This should never happen:
    die('Invalid Bootcamp id.');
}
?>

<script>

    //Set core variables:
    var c_id = <?= $c_id ?>;
    var level = <?= $level ?>;
    var max_length = <?= $message_max ?>;
    var message_count = <?= count($i_messages) ?>;
    //Sync the message count now:
    window.parent.document.getElementById("messages-counter-"+c_id).innerHTML = message_count;

    function add_first_name(){
        $('#i_message'+c_id).insertAtCaret(' {first_name}');
        changeMessage();
    }


    //Count text area characters:
    function changeMessage() {
        //Update count:
        var len = $('#i_message'+c_id).val().length;
        if (len > max_length) {
            $('#charNum'+c_id).addClass('overload').text(len);
        } else {
            $('#charNum'+c_id).removeClass('overload').text(len);
        }
    }

    function changeMessageEditing(i_id){
        //See if this is a valid text message editing:
        if(!($('#charNumEditing'+i_id).length)){
            return false;
        }
        //Update count:
        var len = $('#message_body_'+i_id).val().length;
        if (len > max_length) {
            $('#charNumEditing'+i_id).addClass('overload').text(len);
        } else {
            $('#charNumEditing'+i_id).removeClass('overload').text(len);
        }
    }

    var isAdvancedUpload = function() {
        var div = document.createElement('div');
        return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
    }();

    var $input    = $('.box'+c_id).find('input[type="file"]'),
        $label    = $('.box'+c_id).find('label'),
        showFiles = function(files) {
            $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
        };

    //...

    $input.on('drop', function(e) {
        droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
        showFiles( droppedFiles );
    });

    //...

    $input.on('change', function(e) {
        showFiles(e.target.files);
    });



    function load_message_type(i_status){

        //Change Nav header:
        $('.iphone-nav-tabs li').removeClass('active');
        $('.nav_'+i_status).addClass('active');

        //Change the master status:
        $('#i_status_focus').val(i_status);

        //Adjust UI for Messages:
        $('.all_msg').addClass('hidden');
        $('.msg_'+i_status).removeClass('hidden');

        //Load sorting:
        load_message_sorting();
    }


    $(document).ready(function(){
        //Load Nice sort for iPhone X
        new SimpleBar(document.getElementById('intent_messages'+c_id), {
            // option1: value1,
            // option2: value2
        });

        //Watch for message creation:
        $('#i_message'+c_id).keydown(function (e) {
            if (e.ctrlKey && e.keyCode == 13) {
                msg_create();
            }
        });

        var loading_i_status;
        loading_i_status = 1; // We start off with ON-START messages
        if(window.location.hash) {
            var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
            var hash_parts = hash.split("-");
            if(hash_parts.length==3){
                //Seems right, lets assign:
                loading_i_status = hash_parts[2];
            }
        }

        //Function to control clicks on iPhone Message type header:
        load_message_type(loading_i_status);


        $('.iphone-nav-tabs a').click(function (e) {
            //Detect new tab:
            var parts = $(this).attr('href').split("-");
            var i_status = parts[2];

            //Load new message body into view:
            load_message_type(i_status);
        });


        //Watchout for file uplods:
        $('.box'+c_id).find('input[type="file"]').change(function (){
            save_attachment(droppedFiles,'file');
        });



        //Should we auto start?
        if (isAdvancedUpload) {

            $('.box'+c_id).addClass('has-advanced-upload');
            var droppedFiles = false;

            $('.box'+c_id).on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
            })
                .on('dragover dragenter', function() {
                    $('.add-msg'+c_id).addClass('is-working');
                })
                .on('dragleave dragend drop', function() {
                    $('.add-msg'+c_id).removeClass('is-working');
                })
                .on('drop', function(e) {
                    droppedFiles = e.originalEvent.dataTransfer.files;
                    e.preventDefault();
                    save_attachment(droppedFiles,'drop');
                });
        }
    });


    function apply_message_sorting(i_status){
        var new_sort = [];
        var sort_rank = 0;
        var this_iid = 0;
        $( "#message-sorting"+c_id+">div.msg_"+i_status ).each(function() {
            this_iid = parseInt($( this ).attr('iid'));
            if(this_iid>0){
                sort_rank++;
                new_sort[sort_rank] = this_iid;
            }
        });

        //Update backend:
        $.post("/api_v1/messages_sort", {new_sort:new_sort, b_id:$('#b_id').val(), pid:c_id}, function(data) {
            if(!data.status){
                //Show error:
                alert('ERROR: '+data.message);
            }
        });
    }

    function load_message_sorting(){
        var theobject = document.getElementById("message-sorting"+c_id);
        var inner_content = null;
        var sort_msg = Sortable.create( theobject , {
            animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
            handle: ".fa-bars", // Restricts sort start click/touch to the specified element
            draggable: ".is_sortable", // Specifies which items inside the element should be sortable
            onUpdate: function (evt/**Event*/){
                //Apply new sort:
                var i_status = $('#i_status_focus').val();
                apply_message_sorting(i_status);
            },
            //The next two functions resolve a Bug with sorting iframes like YouTube embeds while also making the UI more informative
            onChoose: function (evt/**Event*/){
                //See if this is a YouTube or Vimeo iFrame that needs to be temporarily removed:
                var iid = $(evt.item).attr('iid');
                if($('#ul-nav-'+iid).find('.video-sorting').length!==0){
                    inner_content = $('#msg_body_'+iid).html();
                    $('#msg_body_'+iid).css('height',$('#msg_body_'+iid).height()).html('<i class="fa fa-sort" aria-hidden="true"></i> Drag up/down to sort video');
                } else {
                    inner_content = null;
                }
            },
            onEnd: function (evt/**Event*/){
                if(inner_content){
                    var iid = $(evt.item).attr('iid');
                    $('#msg_body_'+iid).html(inner_content);
                }
            }
        });
    }




    function message_delete(i_id){
        //Double check:
        var r = confirm("Delete Message?");
        if (r == true) {
            //Show processing:
            var original_message = $("#ul-nav-"+i_id).html();
            $("#ul-nav-"+i_id).html('<div><img src="/img/round_load.gif" class="loader" /> Deleting...</div>');

            //Delete and remove:
            $.post("/api_v1/message_delete", {i_id:i_id, pid:c_id}, function(data) {

                //Update UI to confirm with user:
                if(!data.status){

                    //Show error:
                    alert('ERROR: '+data.message);

                    //Put original message back:
                    $("#ul-nav-"+i_id).html(original_message);

                    //Tooltips:
                    $('[data-toggle="tooltip"]').tooltip();

                } else {

                    $("#ul-nav-"+i_id).html('<div>'+data.message+'</div>');

                    //Adjust counter by one:
                    message_count--;
                    window.parent.document.getElementById("messages-counter-"+c_id).innerHTML = message_count;

                    //Disapper in a while:
                    setTimeout(function() {
                        $("#ul-nav-"+i_id).fadeOut();
                        setTimeout(function() {
                            //Remove first:
                            $("#ul-nav-"+i_id).remove();

                            //Adjust sort:
                            apply_message_sorting($('#i_status_focus').val());
                        }, 377);
                    }, 377);

                }
            });
        }
    }

    function msg_start_edit(i_id,initial_i_status){
        //Start editing:
        $("#ul-nav-"+i_id).addClass('in-editing');
        $("#ul-nav-"+i_id+" .edit-off").addClass('hidden');
        $("#ul-nav-"+i_id+" .edit-on").removeClass('hidden');
        $("#ul-nav-"+i_id+">div").css('width','100%');
        $("#ul-nav-"+i_id+" textarea").focus();

        //Try to initiate the editor, which only applies to text messages:
        changeMessageEditing(i_id);

        //Watch typing:
        $(document).keyup(function(e) {
            //Watch for action keys:
            if (e.ctrlKey && e.keyCode === 13){
                message_save_updates(i_id,initial_i_status);
            } else if (e.keyCode === 27) {
                msg_cancel_edit(i_id);
            }
        });
    }

    function msg_cancel_edit(i_id,success=0){
        //Revert editing:
        $("#ul-nav-"+i_id).removeClass('in-editing');
        $("#ul-nav-"+i_id+" .edit-off").removeClass('hidden');
        $("#ul-nav-"+i_id+" .edit-on").addClass('hidden');
        $("#ul-nav-"+i_id+">div").css('width','inherit');
    }

    function message_save_updates(i_id,initial_i_status){

        //Show loader:
        $("#ul-nav-"+i_id+" .edit-updates").html('<div><img src="/img/round_load.gif" class="loader" /></div>');

        //Revert View:
        msg_cancel_edit(i_id,1);

        //Detect new status, and a potential change:
        var new_i_status = $("#i_status_"+i_id).val();

        //Update message:
        $.post("/api_v1/message_update", {

            i_id:i_id,
            i_message:$("#ul-nav-"+i_id+" textarea").val(),
            initial_i_status:initial_i_status,
            i_status:new_i_status,
            pid:c_id,
            level:level,
            i_media_type:$("#ul-nav-"+i_id+" .i_media_type").val(),

        }, function(data) {

            if(data.status){

                //All good, lets show new text:
                if($("#ul-nav-"+i_id+" .i_media_type").val()=='text'){
                    $("#ul-nav-"+i_id+" .text_message").html(data.message);
                }

                //Did the status change?
                if(!(new_i_status==initial_i_status)){
                    //Update new status:
                    $("#ul-nav-"+i_id+" .the_status").html(data.new_status);

                    //Switch message over to its section and inform the user:
                    $("#ul-nav-"+i_id).removeClass('msg_'+initial_i_status).addClass('msg_'+new_i_status)

                    //Note that we don't need to sort the new list as the new item would be added to its end

                    //Remove possible "No message" info box:
                    if($('.no-messages'+c_id+'_'+new_i_status).length){
                        $('.no-messages'+c_id+'_'+new_i_status).hide();
                    }

                    setTimeout(function() {

                        //Now move over to the new status tab:
                        load_message_type(new_i_status);

                        //Move item to last:
                        $( "#message-sorting"+c_id ).append($("#ul-nav-"+i_id));

                        //Sort original list as 1 message has been removed from it:
                        apply_message_sorting(initial_i_status);

                    }, 500);

                }

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
            }, 5000);
        });
    }














    var button_value = null;
    function message_form_lock(){
        var i_status = $('#i_status_focus').val();
        button_value = $('#add_message_'+i_status+'_'+c_id).html();
        $('#add_message_'+i_status+'_'+c_id).html('<span><img src="/img/round_yellow_load.gif" class="loader" /></span>');
        $('#add_message_'+i_status+'_'+c_id).attr('href','#');

        $('.add-msg'+c_id).addClass('is-working');
        $('#i_message'+c_id).prop("disabled", true);
        $('.remove_loading').hide();
    }


    function message_form_unlock(result){
        var i_status = $('#i_status_focus').val();

        //Update UI to unlock:
        $('.add-msg'+c_id).removeClass('is-working');
        $('.remove_loading').fadeIn();

        $('#add_message_'+i_status+'_'+c_id).html(button_value);
        $('#add_message_'+i_status+'_'+c_id).attr('href','javascript:msg_create();');

        //Remove possible "No message" info box:
        if($('.no-messages'+c_id+'_'+i_status).length){
            $('.no-messages'+c_id+'_'+i_status).hide();
        }

        //Reset Focus:
        $("#i_message"+c_id).prop("disabled", false).focus();

        //What was the result?
        if(result.status){

            //Append data:
            $( "#message-sorting"+c_id ).append(result.message);

            //Resort/Re-adjust:
            load_message_type(i_status);

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Hide any errors:
            setTimeout(function() {
                $(".i_error").fadeOut();
            }, 3000);

        } else {

            alert('ERROR: '+result.message);

        }
    }

    function save_attachment(droppedFiles,uploadType){

        if ($('.box'+c_id).hasClass('is-uploading')) { return false; }

        if (isAdvancedUpload) {

            //Lock message:
            message_form_lock();

            var ajaxData = new FormData($('.box'+c_id).get(0));
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
            ajaxData.append( 'i_status', $('#i_status_focus').val() );
            ajaxData.append( 'level', level );
            ajaxData.append( 'pid', c_id );
            ajaxData.append( 'b_id', $('#b_id').val() );

            $.ajax({
                url: '/api_v1/message_attachment',
                type: $('.box'+c_id).attr('method'),
                data: ajaxData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                complete: function() {
                    $('.box'+c_id).removeClass('is-uploading');
                },
                success: function(data) {
                    message_form_unlock(data);

                    //Adjust Action Plan counter by one:
                    message_count++;
                    window.parent.document.getElementById("messages-counter-"+c_id).innerHTML = message_count;
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

        if($('#i_message'+c_id).val().length==0){
            alert('ERROR: Enter a message');
            return false;
        }

        //Lock message:
        message_form_lock();

        //Update backend:
        $.post("/api_v1/message_create", {

            b_id:$('#b_id').val(),
            pid:c_id, //Synonymous
            i_message:$('#i_message'+c_id).val(),
            i_status:$('#i_status_focus').val(),
            level:level,

        }, function(data) {

            //Empty Inputs Fields if success:
            if(data.status){

                //Adjust counter by one:
                message_count++;
                window.parent.document.getElementById("messages-counter-"+c_id).innerHTML = message_count;

                //Reset input field:
                $( "#i_message"+c_id ).val("");
                changeMessage();
            }

            //Unlock field:
            message_form_unlock(data);

        });
    }
</script>



<div class="iphone-title"><?= $core_objects['level_'.($level-1)]['o_icon'].' '.$core_objects['level_'.($level-1)]['o_name'].': '.$intents[0]['c_objective'] ?></div>

<ul class="nav nav-tabs iphone-nav-tabs">
    <li role="presentation" class="nav_1 active"><a href="#messages-<?= $c_id ?>-1"><?= status_bible('i',1, false, null) ?></a></li>
    <li role="presentation" class="nav_3"><a href="#messages-<?= $c_id ?>-3"><?= status_bible('i',3, false, null) ?></a></li>
    <?php if($drip_enabled){ ?>
    <li role="presentation" class="nav_2"><a href="#messages-<?= $c_id ?>-2"><?= status_bible('i',2, false, null) ?></a></li>
    <?php } ?>
</ul>

<input type="hidden" id="i_status_focus" value="1" />

<div class="ix-msg" style="height:380px; padding-top:7px;" id="intent_messages<?= $c_id ?>">

    <?php

    //Give more information on each message type:
    $i_desc = status_bible('i');
    echo '<div class="ix-tip all_msg msg_1"><i class="fa fa-info-circle" aria-hidden="true"></i> '.str_replace('item',$core_objects['level_'.($level-1)]['o_name'],$i_desc[1]['s_desc']).'.</div>';
    echo '<div class="ix-tip all_msg msg_2 hidden"><i class="fa fa-info-circle" aria-hidden="true"></i> '.str_replace('item',$core_objects['level_'.($level-1)]['o_name'],$i_desc[2]['s_desc']).'.</div>';
    echo '<div class="ix-tip all_msg msg_3 hidden"><i class="fa fa-info-circle" aria-hidden="true"></i> '.str_replace('item',$core_objects['level_'.($level-1)]['o_name'],$i_desc[3]['s_desc']).'.</div>';

    $message_count_1 = 0;
    $message_count_2 = 0;
    $message_count_3 = 0;

    if(count($i_messages)>0){
        echo '<div id="message-sorting'.$c_id.'" class="list-group list-messages">';
        foreach($i_messages as $i){
            echo echo_message(array_merge($i,array(
                'e_b_id'=>$b_id,
                'e_recipient_u_id'=>$udata['u_id'],
            )),$level );
            //Increase counter:
            ${'message_count_'.$i['i_status']}++;
        }
        echo '</div>';
    } else {
        //Now show empty shell
        echo '<div id="message-sorting'.$c_id.'" class="list-group list-messages"></div>';
    }

    //Show no Message errors:
    if($message_count_1==0){
        echo '<div class="ix-tip no-messages'.$c_id.'_1 all_msg msg_1"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No '.status_bible('i',1, false, null).' Messages added yet</div>';
    }
    if($message_count_2==0){
        echo '<div class="ix-tip no-messages'.$c_id.'_2 all_msg msg_2 hidden"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No '.status_bible('i',2, false, null).' Messages added yet</div>';
    }
    if($message_count_3==0){
        echo '<div class="ix-tip no-messages'.$c_id.'_3 all_msg msg_3 hidden"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No '.status_bible('i',3, false, null).' Messages added yet</div>';
    }

    ?>
</div>

<div class="ix-kyb">
    <?php
    echo '<div class="list-group list-messages">';
    echo '<div class="list-group-item">';

    echo '<div class="add-msg add-msg'.$c_id.'" style="border-radius:0 !important; margin-top: 2px;">';
    echo '<form class="box box'.$c_id.'" method="post" enctype="multipart/form-data">'; //Used for dropping files

    echo '<textarea onkeyup="changeMessage()" class="form-control msg msgin" style="min-height:80px; box-shadow: none; resize: none;" id="i_message'.$c_id.'" placeholder="Write Message, Drop a File or Paste URL"></textarea>';

    echo '<div id="i_message_counter" style="margin:0 0 1px 0; font-size:0.8em;">';
    //File counter:
    echo '<span id="charNum'.$c_id.'">0</span>/'.$message_max;

    if($level>1){
        //{first_name}
        echo '<a href="javascript:add_first_name();" class="textarea_buttons remove_loading" style="float:right;" data-toggle="tooltip" title="Replaced with student\'s First Name for a more personal message." data-placement="left"><i class="fa fa-id-card-o" aria-hidden="true"></i> {first_name}</a>';
    }

    //Choose a file:
    $file_limit_mb = $this->config->item('file_limit_mb');
    echo '<div style="float:right; display:inline-block; margin-right:8px;" class="remove_loading"><input class="box__file inputfile" type="file" name="file" id="file" /><label class="textarea_buttons" for="file" data-toggle="tooltip" title="Upload Video, Audio, Images or PDFs up to '.$file_limit_mb.' MB." data-placement="top"><i class="fa fa-picture-o" aria-hidden="true"></i> Upload File</label></div>';
    echo '</div>';


    echo '<div class="iphone-add-btn all_msg msg_1"><a href="javascript:msg_create();" id="add_message_1_'.$c_id.'" data-toggle="tooltip" title="or hit CTRL+ENTER ;)" data-placement="top" class="btn btn-primary">ADD '.status_bible('i',1, false, null).' &nbsp; MESSAGE</a></div>';
    echo '<div class="iphone-add-btn all_msg msg_2 hidden"><a href="javascript:msg_create();" id="add_message_2_'.$c_id.'" data-toggle="tooltip" title="or hit CTRL+ENTER ;)" data-placement="top" class="btn btn-primary">ADD '.status_bible('i',2, false, null).' &nbsp; MESSAGE</a></div>';
    echo '<div class="iphone-add-btn all_msg msg_3 hidden"><a href="javascript:msg_create();" id="add_message_3_'.$c_id.'" data-toggle="tooltip" title="or hit CTRL+ENTER ;)" data-placement="top" class="btn btn-primary">ADD '.status_bible('i',3, false, null).' &nbsp; MESSAGE</a></div>';



    echo '</form>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
    ?>
</div>
