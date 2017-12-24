<?php
/**
 * Created by PhpStorm.
 * User: shervinenayati
 * Date: 2017-12-11
 * Time: 12:11 AM
 */

//Fetch Messages based on c_id:
$message_max = $this->config->item('message_max');
$core_objects = $this->config->item('core_objects');
$i_statuses = status_bible('i', null);
$i_messages = $this->Db_model->i_fetch(array(
    'i_c_id' => $c_id,
    'i_status >=' => 0, //Published in any form. This may need more logic
    'i_status <' => 4, //But not private notes if any
));

//Fetch intent details:
$intents = $this->Db_model->c_fetch(array(
    'c.c_id' => $c_id,
));

if(!isset($intents[0])){
    die('Invalid input id.');
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





    $(document).ready(function() {
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

        //Load sorting:
        load_message_sorting();


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



    function load_message_sorting(){
        var theobject = document.getElementById("message-sorting"+c_id);
        var sort_msg = Sortable.create( theobject , {
            animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
            handle: ".fa-bars", // Restricts sort start click/touch to the specified element
            draggable: ".is_sortable", // Specifies which items inside the element should be sortable
            onUpdate: function (evt/**Event*/){
                //Set processing status:
                //$( ".edit-updates" ).html('<img src="/img/round_load.gif" class="loader" />');

                //Fetch new sort:
                var new_sort = [];
                var sort_rank = 0;
                $( "#message-sorting"+c_id+">div" ).each(function() {
                    sort_rank++;
                    new_sort[sort_rank] = $( this ).attr('iid');
                });

                //Update backend:
                $.post("/api_v1/messages_sort", {new_sort:new_sort, b_id:$('#b_id').val(), pid:c_id}, function(data) {
                    //Update UI to confirm with user:
                    //$( ".edit-updates" ).html(data);

                    //Disapper in a while:
                    setTimeout(function() {
                        //$(".edit-updates>span").fadeOut();
                    }, 1000);
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
            $.post("/api_v1/message_delete", {i_id:i_id, pid:c_id}, function(data) {
                //Update UI to confirm with user:

                $("#ul-nav-"+i_id).html('<div>'+data+'</div>');

                //Adjust counter by one:
                message_count--;
                window.parent.document.getElementById("messages-counter-"+c_id).innerHTML = message_count;

                //Disapper in a while:
                setTimeout(function() {
                    $("#ul-nav-"+i_id).fadeOut();
                    setTimeout(function() {
                        $("#ul-nav-"+i_id).remove();
                    }, 377);
                }, 377);
            });
        }
    }

    function msg_start_edit(i_id){

        //Start editing:
        $("#ul-nav-"+i_id).addClass('in-editing');
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
        $("#ul-nav-"+i_id).removeClass('in-editing');
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
            pid:c_id,
            level:level,
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















    function message_form_lock(){
        $('#add_message'+c_id).html('<span><img src="/img/round_yellow_load.gif" class="loader" /></span>');
        $('#message_status'+c_id).html('');


        $('.add-msg'+c_id).addClass('is-working');
        $('#i_message'+c_id).prop("disabled", true);
        $('.remove_loading').hide();
        $('#add_message'+c_id).attr('href','#');
    }


    function message_form_unlock(result){

        //Update UI to unlock:
        $('#add_message'+c_id).html('ADD');
        $('.add-msg'+c_id).removeClass('is-working');
        $('#i_message'+c_id).prop("disabled", false);
        $('.remove_loading').fadeIn();
        $('#add_message'+c_id).attr('href','javascript:msg_create();');

        //Remove possible "No message" info box:
        if($('.no-messages'+c_id).length){
            $('.no-messages'+c_id).hide();
        }

        //Reset Focus:
        $("#i_message"+c_id).focus();


        //What was the result?
        if(result.status){

            //Append data:
            $( "#message-sorting"+c_id ).append(result.message);

            //Resort:
            load_message_sorting();

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Hide any errors:
            setTimeout(function() {
                $(".i_error").fadeOut();
            }, 3000);

        } else {

            $('#message_status'+c_id).html('<b style="color:#FF0000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '+result.message+'</b>');

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
            ajaxData.append( 'i_status', $('#i_status'+c_id).val() );
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

                    //Adjust counter by one:
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
        if($('#i_message'+c_id).val().length>0){
            //Lock message:
            message_form_lock();

            //Update backend:
            $.post("/api_v1/message_create", {

                b_id:$('#b_id').val(),
                pid:c_id, //Synonymous
                i_message:$('#i_message'+c_id).val(),
                i_status:$('#i_status'+c_id).val(),
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
    }
</script>


<div class="ix-msg" id="intent_messages<?= $c_id ?>">

    <div class="ix-tip"><div style="font-size:1.3em;"><?= $core_objects['level_'.($level-1)]['o_icon'].' '.$intents[0]['c_objective'] ?></div>Messages are automatically sent to students during the <?= ( $level>1 ? 'milestone' : 'bootcamp' ) ?>.</div>
    <?php
    if($level>=1 && $level<=2){
        //echo '<div class="ix-tip">'.status_bible('i',3, false, 'bottom',1).' messages are also displayed on the landing page.</div>';
    }

    //Show relevant tips:
    if($level==1){
        //itip(604);
    } elseif($level==2){
        //itip(605);
    } elseif($level==3){
        //itip(608);
    }


    if(count($i_messages)>0){
        echo '<div id="message-sorting'.$c_id.'" class="list-group list-messages">';
        foreach($i_messages as $i){
            echo echo_message($i,$level);
        }
        echo '</div>';
    } else {
        echo '<div class="ix-tip no-messages'.$c_id.'" style="background-color: #FEDD1B; color:#222;">No messages added yet!</div>';
        //Now show empty shell
        echo '<div id="message-sorting'.$c_id.'" class="list-group list-messages">';
        echo '</div>';
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

    echo '<ul style="list-style:none;">';

    echo '<li class="pull-left" style="margin-left:-28px; padding-left: 0; margin-top: 4px;"><span class="message_status" id="message_status'.$c_id.'"><span class="when_to">Message Dispatch Time:</span></span></li>';
    echo '<li class="pull-right"><a href="javascript:msg_create();" id="add_message'.$c_id.'" data-toggle="tooltip" title="or press CTRL+ENTER ;)" data-placement="top" class="btn btn-primary" style="margin-top: 2px; padding: 5px 8px; margin-right:46px;">ADD</a></li>';

    echo '<li class="pull-right remove_loading" style="padding:2px 5px 0 0;">';
    echo echo_status_dropdown('i','i_status'.$c_id,1,array(-1,4),'dropup',$level,1);
    echo '</li>';

    echo '</ul>';

    echo '<div id="url_preview"></div>';

    echo '</form>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
    ?>
</div>
