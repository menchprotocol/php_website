
function u_load_child_search(){

    $("#new-outbound .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {

        ur_add(suggestion.u_id,0,0);

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_u_index.search(q, {
                hitsPerPage: 7,
            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        templates: {
            suggestion: function (suggestion) {
                //If clicked, would trigger the autocomplete:selected above which will trigger the ur_add() function
                return '<span><i class="fas fa-at"></i></span> ' + suggestion.u_full_name;
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:ur_add(0,'+add_u_id+',0)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as '+add_u_name+']</a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:ur_add(0,'+add_u_id+',0)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create</span> <i class="fas fa-at"></i> ' + data.query + ' [as '+add_u_name+']</a>';
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            ur_add(0,add_u_id);
            return true;
        }
    });
}


$(document).ready(function () {

    if(is_compact){

        //Adjust columns:
        $('.cols').removeClass('col-xs-6').addClass('col-sm-6');
        $('.fixed-box').addClass('release-fixture');
        $('.dash').css('margin-bottom', '0px'); //For iframe to show better

    } else {

        //Adjust height of the messaging windows:
        $('.grey-box').css('max-height', (parseInt($( window ).height())-130)+'px');

        //Make editing frames Sticky for scrolling longer lists
        $(".main-panel").scroll(function() {
            var top_position = $(this).scrollTop();
            clearTimeout($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function() {
                $(".fixed-box").css('top',(top_position-0)); //PX also set in style.css for initial load
            }, 34));
        });
    }


    //Do we need to auto load anything?
    if(window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if(hash_parts.length>=2){
            //Fetch level if available:
            if(hash_parts[0]=='messages'){
                u_load_messages(hash_parts[1]);
            } else if(hash_parts[0]=='modify'){
                u_load_modify(hash_parts[1],hash_parts[2]);
            } else if(hash_parts[0]=='status'){
                //Update status:
                u_load_filter_status(hash_parts[1]);
            } else if(hash_parts[0]=='wengagements'){
                load_u_engagements(hash_parts[1]);
            }
        }
    }




    //Watch for URL adding:
    $('#add_url_input').keydown(function (event) {
        if ((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey) {
            x_add();
            event.preventDefault();
            return false;
        }
    });


    //Loadup various search bars:
    u_load_child_search();



    $("#new-inbound .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {
        ur_add(suggestion.u_id,0,1);
    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{
        source: function (q, cb) {
            algolia_u_index.search(q, {
                hitsPerPage: 7,
            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        templates: {
            suggestion: function (suggestion) {
                //If clicked, would trigger the autocomplete:selected above which will trigger the ur_add() function
                return '<span><i class="fas fa-at"></i></span> ' + suggestion.u_full_name;
            },
            header: function (data) {
                //1326 suggests both People AND Organizations as new inbound entities:
                if (!data.isEmpty && entity_u_type==1326) {
                    return '<a href="javascript:ur_add(0,1278,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as People]</a><a href="javascript:ur_add(0,2750,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as Organization]</a>';
                }
            },
            empty: function (data) {
                if(entity_u_type==1326){
                    return '<a href="javascript:ur_add(0,1278,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as People]</a><a href="javascript:ur_add(0,2750,1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as Organization]</a>';
                }
            },
        }
    }]);



});

//Adds OR links authors and content for entities
function ur_add(new_u_id,secondary_parent_u_id=0, is_inbound) {

    //if new_u_id>0 it means we're linking to an existing entity, in which case new_u_input should be null
    //If new_u_id=0 it means we are creating a new entity and then linking it, in which case new_u_input is required

    if(is_inbound){
        var input = $('#new-inbound .new-input');
        var btn = $('#new-inbound .new-btn');
        var list_id = 'list-inbound';
        var counter_class = '.li-inbound-count';
    } else {
        var input = $('#new-outbound .new-input');
        var btn = $('#new-outbound .new-btn');
        var list_id = 'list-outbound';
        var counter_class = '.li-outbound-count';
    }


    var new_u_input = null;
    if (new_u_id==0) {
        new_u_input = input.val();
        if(new_u_input.length<1){
            alert('ERROR: Missing entity name or URL, try again');
            input.focus();
            return false;
        }
    }


    //Adjust UI to indicating loading...
    var current_href = btn.attr('href');
    input.prop('disabled', true); //Empty input
    btn.attr('href', 'javascript:void(0);').html('<i class="fas fa-spinner fa-spin"></i>');


    //Add via Ajax:
    $.post("/entities/link_entities", {

        u_id:top_u_id,
        new_u_id: new_u_id,
        new_u_input: new_u_input,
        is_inbound:( is_inbound ? 1 : 0 ),
        can_edit:can_u_edit,
        secondary_parent_u_id:secondary_parent_u_id,

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);
        btn.attr('href', current_href).html('ADD');

        if (data.status) {

            //Empty input to make it ready for next URL:
            input.val('').focus();

            //Add new object to list:
            add_to_list(list_id, '.u-item', data.new_u);

            //Adjust counters:
            $(counter_class).text((parseInt($(counter_class+':first').text())+1));
            $('.count-u-status-'+data.new_u_status).text((parseInt($('.count-u-status-'+data.new_u_status).text())+1));

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert('Error: ' + data.message);
        }

    });
}


function u_save_status(u_id, new_u_status){

    //Indicate loading:
    $('.u-status-bar-'+u_id).html('<img src="/img/round_load.gif" class="loader" />');

    //Will update the status of u_id to new status
    $.post("/entities/u_save_status", {

        u_id:u_id,
        new_u_status: new_u_status,

    }, function (data) {
        if (data.status) {

            //Show data:
            $('.u-status-bar-'+u_id).html(data.message);

            //Adjust counters:
            $('.count-u-status-'+new_u_status).text((parseInt($('.count-u-status-'+new_u_status).text())+1));
            $('.count-u-status-'+data.old_status).text((parseInt($('.count-u-status-'+data.old_status).text())-1));
            //TODO maybe the new counter element does not exist! Handle this case later...

            if(u_status_filter>=0 && !(new_u_status==u_status_filter)){
                //We have the filter on and it does not match the new status, so hide this:
                setTimeout(function () {
                    $('#u_'+u_id).fadeOut();
                }, 377);
            } else {
                //Update status:
                $('#u_'+u_id).attr('entity-status',new_u_status);
            }

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            $('.u-status-bar-'+u_id).html('<span style="color:#FF0000;">Error: '+data.message+'</span>');
        }
    });


}

function u_load_filter_status(new_val){
    if(new_val==-1 || new_val==0 || new_val==1 || new_val==2) {
        //Remove active class:
        $('.u-status-filter').removeClass('btn-secondary');
        //We do have a filter:
        u_status_filter = parseInt(new_val);
        $('.u-status-'+new_val).addClass('btn-secondary');
        u_load_next_page(0,1);
    } else {
        alert('Invalid new status');
        return false;
    }
}



//Count text area characters:
function u_bio_counter() {
    var len = $('#u_bio').val().length;
    if (len>message_max) {
        $('#charNum').addClass('overload').text(len);
    } else {
        $('#charNum').removeClass('overload').text(len);
    }
}

function u_full_name_word_count() {
    var len = $('#u_full_name').val().length;
    if (len>u_full_name_max) {
        $('#charNameNum').addClass('overload').text(len);
    } else {
        $('#charNameNum').removeClass('overload').text(len);
    }
}


function x_cover_set(x_id) {
    //Set loader:
    $('#x_' + x_id + ' .add-cover').addClass('hidden').after('<span class="badge badge-secondary grey cover-load"><i class="fas fa-spinner fa-spin"></i></span>');

    //Add cover photo:
    $.post("/urls/set_cover", {
        x_id: x_id,
        can_edit:can_u_edit,
    }, function (data) {

        if (data.status) {

            $('.current-cover').remove(); //Remove Current cover icon
            $('#entity-box .profile-icon2').remove();
            $('#x_' + x_id + ' .cover-load').html(data.message);

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert('Error: ' + data.message);
            $('#x_' + x_id + ' .cover-load').remove();
            $('#x_' + x_id + ' .add-cover').removeClass('hidden')
        }

    });

}


function x_add() {

    if ($('#add_url_input').val().length < 1) {
        //Empty field!
        alert('Error: Input field is empty. Paste a URL and then click "Add"');
        $('#add_url_input').focus();
        return false;
    }

    //Let's try adding:
    $('#add_url_input').prop('disabled', true); //Empty input
    $('#add_url_btn').attr('href', 'javascript:void(0);').html('<i class="fas fa-spinner fa-spin"></i>');


    $.post("/urls/add_url", {

        x_outbound_u_id:top_u_id,
        x_url: $('#add_url_input').val(),
        can_edit:can_u_edit,

    }, function (data) {

        //Release lock:
        $('#add_url_input').prop('disabled', false);
        $('#add_url_btn').attr('href', 'javascript:x_add();').html('ADD');
        $('.no-b-div-1').remove(); //This MIGHT be there if there was no URLs previously

        if (data.status) {

            //Empty input to make it ready for next URL:
            $('#add_url_input').val('');

            //Add new object to list:
            add_to_list('list-urls', '.url-item', data.new_x);

            //Increase counter:
            $('.li-urls-count').text((parseInt($('.li-urls-count').text())+1));

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Do we need to set this as the cover photo?
            if(data.set_cover_x_id>0){
                setTimeout(function () {
                    x_cover_set(data.set_cover_x_id);
                }, 377);
            }

        } else {
            //We had an error:
            alert('Error: ' + data.message);
        }

    });

}

function x_delete(x_id) {

    var r = confirm("Delete Reference?");
    if (r == false) {
        return false;
    }

    //Show loader to delete:
    $('#x_' + x_id).html('<img src="/img/round_load.gif" class="loader" /> Deleting... ');

    //Delete"
    $.post("/urls/delete_url", {

        x_id: x_id,
        can_edit:can_u_edit,

    }, function (data) {

        if (data.status) {

            $('#x_' + x_id).html(data.message);

            //Decrease counter:
            $('.li-urls-count').text((parseInt($('.li-urls-count').text())-1));

            //Remove the who bar:
            setTimeout(function () {
                $('#x_' + x_id).fadeOut();
            }, 377);

        } else {
            //We had an error:
            $('#x_' + x_id).html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> Error: ' + data.message + '</span>');
        }

    });

}

function u_load_next_page(page,load_new_filter=0) {

    if(load_new_filter){
        //Replace load more with spinner:
        var append_div = $('#new-outbound').html();
        //The padding-bottom would remove the scrolling effect on the left side!
        $('#list-outbound').html('<span class="load-more" style="padding-bottom:500px;"><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
    }

    $.post("/entities/u_load_next_page", {
        can_edit:can_u_edit,
        page:page,
        inbound_u_id:top_u_id,
        u_status_filter:u_status_filter,
    }, function(data) {

        //Appending to existing content:
        $('.load-more').remove();

        if(load_new_filter){
            $('#list-outbound').html( data + '<div id="new-outbound" class="list-group-item list_input grey-input">'+append_div+'</div>' ).hide().fadeIn();
            //Reset search engine:
            u_load_child_search();
        } else {
            //Update UI to confirm with user:
            $(data).insertBefore('#new-outbound');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}

function ur_unlink(){

    var ur_id = ( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('entity-link-id')) );
    var u_level1_name = $('.top_entity .u_full_name').text();
    var u_level2_name = $('.ur_'+ur_id+' .u_full_name').text();
    var direction = ( parseInt($('.ur_'+ur_id).attr('is-inbound'))==1 ? 'inbound' : 'outbound' );
    var counter_class = '.li-'+direction+'-count';
    var current_status = parseInt($('.ur_'+ur_id).attr('entity-status'));

    //Confirm that they want to do this:
    var r = confirm("Unlink ["+u_level2_name+"] from ["+u_level1_name+"]?");
    if (!(r == true)) {
        return false;
    }


    //Show loader:
    $('.ur_'+ur_id).html('<img src="/img/round_load.gif" class="loader" style="width:24px !important; height:24px !important;" /> Unlinking...').hide().fadeIn();

    //Save the rest of the content:
    $.post("/entities/unlink_entities", {

        ur_id:ur_id,

    } , function(data) {

        //OK, what happened?
        if(data.status){

            //Update UI to confirm with user:
            $('.ur_'+ur_id).fadeOut();

            //Update counter:
            $(counter_class).text((parseInt($(counter_class+':first').text())-1));
            $('.count-u-status-'+current_status).text((parseInt($('.count-u-status-'+current_status).text())-1));

        } else {
            //There was an error, show to user:
            $('.ur_'+ur_id).html('<b style="color:#FF0000 !important;">Error: '+data.message+'</b>');
        }

    });
}

function u_delete(){

    //Confirm with user:
    var u_id = ( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('entity-id')) );
    var direction = ( parseInt($('#u_'+u_id).attr('is-inbound'))==1 ? 'inbound' : 'outbound' );
    var counter_class = '.li-'+direction+'-count';
    var current_status = parseInt($('#u_'+u_id).attr('entity-status'));
    var u_level2_name = $('#u_'+u_id+' .u_full_name').text();

    var r = confirm("Are you sure you want to PERMANENTLY delete ["+u_level2_name+"] and all its associated URLs, Messages, etc...?");
    if (!(r == true)){
        return false;
    }

    if(u_id==0){
        return false;
    }

    //Save the rest of the content:
    $.get("/entities/hard_delete/"+u_id, function(data) {
        $('#u_'+u_id).fadeOut();

        //Update counter:
        $(counter_class).text((parseInt($(counter_class+':first').text())-1));
        $('.count-u-status-'+current_status).text((parseInt($('.count-u-status-'+current_status).text())-1));
    });
}


function u_load_modify(u_id, ur_id){

    //Make sure inputs are valid:
    if(!$('.u__'+u_id).length){
        return false;
    }

    //Update variables:
    $('.save_entity_changes').html('');
    $('#modifybox').attr('entity-link-id',ur_id);
    $('#modifybox').attr('entity-id',u_id);

    $('#u_full_name').val($(".u_full_name_"+u_id+":first").text());
    $('#u_bio').val($(".u__"+u_id+":first").attr('entity-bio'));
    u_bio_counter();
    u_full_name_word_count();

    //Update password reset UI:
    $('#u_email').val($(".u__"+u_id+":first").attr('entity-email'));
    $('.changepass').hide();
    $('.changepass_a').show().text(( parseInt($(".u__"+u_id+":first").attr('has-password')) && $(".u__"+u_id+":first").attr('entity-email').length>0 ? 'Update Login Credentials' : 'Setup Login Credentials'));

    //Only show unlink button if not level 1
    if(parseInt(ur_id)>0){
        $('.unlink-entity').removeClass('hidden');
    } else {
        $('.unlink-entity').addClass('hidden');
    }

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();

    //We might need to scroll:
    if(is_compact){
        $('.main-panel').animate({
            scrollTop:9999
        }, 150);
    }
}


function u_save_modify(){

    //Validate that we have all we need:
    if($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('entity-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Prepare data to be modified for this intent:
    var modify_data = {
        u_id:parseInt($('#modifybox').attr('entity-id')),
        u_full_name:$('#u_full_name').val(),
        u_bio:$('#u_bio').val(),
        u_email:$('#u_email').val(),
        u_password_new:$('#u_password_new').val(),
    };

    //Show spinner:
    $('.save_entity_changes').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();


    $.post("/entities/u_save_settings", modify_data, function(data) {

        if(data.status){

            //Update variables:
            $(".u_full_name_"+modify_data['u_id']).text(modify_data['u_full_name']);
            $(".u__"+modify_data['u_id']).attr('entity-bio', modify_data['u_bio']);
            $(".u__"+modify_data['u_id']).attr('entity-email', modify_data['u_email']);
            $(".u__"+modify_data['u_id']).attr('has-password', ( modify_data['u_password_new'].length>0 ? 1 : 0 ));
            if($('.u_bio_'+modify_data['u_id']).length){
                //This is the top entity that's loaded, simply update:
                $(".u_bio_"+modify_data['u_id']).html(nl2br(modify_data['u_bio']));
            } else {
                //This is a level 2 item, let's update the UI accordingly:
                if(modify_data['u_bio'].length>0){
                    $(".u_full_name_"+modify_data['u_id']).addClass('has-desc').attr('data-toggle', 'tooltip').attr('data-original-title', modify_data['u_bio']);
                } else {
                    $(".u_full_name_"+modify_data['u_id']).removeClass('has-desc').attr('data-toggle', '').attr('data-original-title', '');
                }
            }

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

            //Update UI to confirm with user:
            $('.save_entity_changes').html(data.message).hide().fadeIn();

            //Disapper in a while:
            setTimeout(function() {
                //Hide the editor & saving results:
                $('.save_entity_changes').hide();
            }, 1000);

        } else {
            //Ooops there was an error!
            $('.save_entity_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> '+data.message+'</span>').hide().fadeIn();
        }

    });

}


function u_load_messages(u_id){

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#message-frame").removeClass('hidden').hide().fadeIn().attr('entity-id',u_id);
    $("#message-frame h4").text($(".u_full_name_"+u_id+":first").text());

    var handler = $( "#loaded-messages" );

    //Show tem loader:
    handler.html('<div style="text-align:center; padding:10px 0 50px;"><img src="/img/round_load.gif" class="loader" /></div>');

    //We might need to scroll:
    if(is_compact){
        $('.main-panel').animate({
            scrollTop:9999
        }, 150);
    }

    //Load the frame:
    $.post("/entities/load_messages", { u_id:u_id }, function(data) {
        //Empty Inputs Fields if success:
        handler.html(data);

        //Show inner tooltips:
        $('[data-toggle="tooltip"]').tooltip();

    });

}