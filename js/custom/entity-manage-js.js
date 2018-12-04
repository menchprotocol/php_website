function u_load_child_search() {

    $("#new-children .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {

        ur_add(suggestion.u_id, 0, 0);

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
                    return '<a href="javascript:ur_add(0,' + top_u_id + ',0)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as ' + top_u_full_name + ']</a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:ur_add(0,' + top_u_id + ',0)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create</span> <i class="fas fa-at"></i> ' + data.query + ' [as ' + top_u_full_name + ']</a>';
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            ur_add(0, top_u_id);
            return true;
        }
    });
}


$(document).ready(function () {

    if (is_compact) {

        //Adjust columns:
        $('.cols').removeClass('col-xs-6').addClass('col-sm-6');
        $('.fixed-box').addClass('release-fixture');
        $('.dash').css('margin-bottom', '0px'); //For iframe to show better

    } else {

        //Adjust height of the messaging windows:
        $('.grey-box').css('max-height', (parseInt($(window).height()) - 130) + 'px');

        //Make editing frames Sticky for scrolling longer lists
        $(".main-panel").scroll(function () {
            var top_position = $(this).scrollTop();
            clearTimeout($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function () {
                $(".fixed-box").css('top', (top_position - 0)); //PX also set in style.css for initial load
            }, 34));
        });
    }


    //Do we need to auto load anything?
    if (window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if (hash_parts.length >= 2) {
            //Fetch level if available:
            if (hash_parts[0] == 'loadmessages') {
                u_load_messages(hash_parts[1]);
            } else if (hash_parts[0] == 'loadmodify') {
                u_load_modify(hash_parts[1], hash_parts[2]);
            } else if (hash_parts[0] == 'status') {
                //Update status:
                u_load_filter_status(hash_parts[1]);
            } else if (hash_parts[0] == 'wengagements') {
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


    $("#new-parent .new-input").on('autocomplete:selected', function (event, suggestion, dataset) {
        ur_add(suggestion.u_id, 0, 1);
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
                    return '<a href="javascript:ur_add(0,' + top_u_id + ',1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as ' + top_u_full_name + ']</a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:ur_add(0,' + top_u_id + ',1)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-at"></i> ' + data.query + ' [as ' + top_u_full_name + ']</a>';
            },
        }
    }]);


});

//Adds OR links authors and content for entities
function ur_add(new_u_id, secondary_parent_u_id=0, is_parent) {

    //if new_u_id>0 it means we're linking to an existing entity, in which case new_u_input should be null
    //If new_u_id=0 it means we are creating a new entity and then linking it, in which case new_u_input is required

    if (is_parent) {
        var input = $('#new-parent .new-input');
        var btn = $('#new-parent .new-btn');
        var list_id = 'list-parent';
        var counter_class = '.li-parent-count';
    } else {
        var input = $('#new-children .new-input');
        var btn = $('#new-children .new-btn');
        var list_id = 'list-children';
        var counter_class = '.li-children-count';
    }


    var new_u_input = null;
    if (new_u_id == 0) {
        new_u_input = input.val();
        if (new_u_input.length < 1) {
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

        u_id: top_u_id,
        new_u_id: new_u_id,
        new_u_input: new_u_input,
        is_parent: (is_parent ? 1 : 0),
        secondary_parent_u_id: secondary_parent_u_id,

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);
        btn.attr('href', current_href).html('ADD');

        if (data.status) {

            //Empty input to make it ready for next URL:
            input.focus();

            //Add new object to list:
            add_to_list(list_id, '.u-item', data.new_u);

            //Adjust counters:
            $(counter_class).text((parseInt($(counter_class + ':first').text()) + 1));
            $('.count-u-status-' + data.new_u_status).text((parseInt($('.count-u-status-' + data.new_u_status).text()) + 1));

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert('Error: ' + data.message);
        }

    });
}


function u_load_filter_status(new_val) {
    if (new_val == -1 || new_val == 0 || new_val == 1 || new_val == 2) {
        //Remove active class:
        $('.u-status-filter').removeClass('btn-secondary');
        //We do have a filter:
        u_status_filter = parseInt(new_val);
        $('.u-status-' + new_val).addClass('btn-secondary');
        u_load_next_page(0, 1);
    } else {
        alert('Invalid new status');
        return false;
    }
}


function u_icon_word_count() {
    var len = $('#u_icon').val().length;
    if (len > en_name_max) {
        $('#charu_iconNum').addClass('overload').text(len);
    } else {
        $('#charu_iconNum').removeClass('overload').text(len);
    }
}

function u_full_name_word_count() {
    var len = $('#u_full_name').val().length;
    if (len > en_name_max) {
        $('#charNameNum').addClass('overload').text(len);
    } else {
        $('#charNameNum').removeClass('overload').text(len);
    }
}

function tr_content_word_count() {
    var len = $('#tr_content').val().length;
    if (len > tr_content_max) {
        $('#chartr_contentNum').addClass('overload').text(len);
    } else {
        $('#chartr_contentNum').removeClass('overload').text(len);
    }
}


function u_load_next_page(page, load_new_filter = 0) {

    if (load_new_filter) {
        //Replace load more with spinner:
        var append_div = $('#new-children').html();
        //The padding-bottom would remove the scrolling effect on the left side!
        $('#list-children').html('<span class="load-more" style="padding-bottom:500px;"><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
    }

    $.post("/entities/u_load_next_page", {
        page: page,
        parent_u_id: top_u_id,
        u_status_filter: u_status_filter,
    }, function (data) {

        //Appending to existing content:
        $('.load-more').remove();

        if (load_new_filter) {
            $('#list-children').html(data + '<div id="new-children" class="list-group-item list_input grey-input">' + append_div + '</div>').hide().fadeIn();
            //Reset search engine:
            u_load_child_search();
        } else {
            //Update UI to confirm with user:
            $(data).insertBefore('#new-children');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}

function ur_unlink() {

    var tr_id = ($('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('entity-link-id')));
    var u_level1_name = $('.top_entity .u_full_name').text();
    var u_level2_name = $('.ur_' + tr_id + ' .u_full_name').text();
    var direction = (parseInt($('.ur_' + tr_id).attr('is-parent')) == 1 ? 'parent' : 'children');
    var counter_class = '.li-' + direction + '-count';
    var current_status = parseInt($('.ur_' + tr_id).attr('entity-status'));

    //Confirm that they want to do this:
    var r = confirm("Unlink [" + u_level2_name + "] from [" + u_level1_name + "]?");
    if (!(r == true)) {
        return false;
    }


    //Show loader:
    $('.ur_' + tr_id).html('<img src="/img/round_load.gif" class="loader" style="width:24px !important; height:24px !important;" /> Unlinking...').hide().fadeIn();

    //Save the rest of the content:
    $.post("/entities/unlink_entities", {

        tr_id: tr_id,

    }, function (data) {

        //OK, what happened?
        if (data.status) {

            //Update UI to confirm with user:
            $('.ur_' + tr_id).fadeOut();
            $('#modifybox').addClass('hidden');

            //Update counter:
            $(counter_class).text((parseInt($(counter_class + ':first').text()) - 1));
            $('.count-u-status-' + current_status).text((parseInt($('.count-u-status-' + current_status).text()) - 1));

        } else {
            //There was an error, show to user:
            $('.ur_' + tr_id).html('<b style="color:#FF0000 !important;">Error: ' + data.message + '</b>');
        }

    });
}


function u_load_modify(u_id, tr_id) {

    //Make sure inputs are valid:
    if (!$('.u__' + u_id).length) {
        return false;
    }

    //Update variables:
    $('.save_entity_changes').html('');
    $('#modifybox').attr('entity-link-id', tr_id);
    $('#modifybox').attr('entity-id', u_id);


    $('#u_full_name').val($(".u_full_name_" + u_id + ":first").text());
    $('#u_status').val($(".u__" + u_id + ":first").attr('entity-status'));
    $('#u_icon').val($(".u_icon_val_" + u_id + ":first").html().replace('\\', ''));

    u_full_name_word_count();
    u_icon_word_count();

    //Update password reset UI:
    $('#u_email').val($(".u__" + u_id + ":first").attr('entity-email'));

    //Only show unlink button if not level 1
    if (parseInt(tr_id) > 0) {

        //Make the UI link and the notes in the edit box:
        $('.unlink-entity, .li_component').removeClass('hidden');

        //Assign value:
        $('#tr_content').val($(".tr_content_val_" + tr_id + ":first").text());

        //Update count:
        tr_content_word_count();

    } else {

        //Hide the section and clear it:
        $('.unlink-entity, .li_component').addClass('hidden');

    }

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();

    //We might need to scroll:
    if (is_compact) {
        $('.main-panel').animate({
            scrollTop: 9999
        }, 150);
    }
}


function u_save_modify() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('entity-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Prepare data to be modified for this intent:
    var modify_data = {
        tr_id: parseInt($('#modifybox').attr('entity-link-id')),
        tr_content: $('#tr_content').val(),
        u_id: parseInt($('#modifybox').attr('entity-id')),
        u_full_name: $('#u_full_name').val(),
        u_status: $('#u_status').val(), //The new status (might not have changed too)
        u_icon: $('#u_icon').val(),
    };

    //Take a snapshot of the status:
    var original_u_status = parseInt($('.u__' + modify_data['u_id']).attr('entity-status'));

    //Show spinner:
    $('.save_entity_changes').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();


    $.post("/entities/u_save_settings", modify_data, function (data) {

        if (data.status) {

            //Update variables:
            $(".u_full_name_" + modify_data['u_id']).text(modify_data['u_full_name']);
            $(".u_icon_val_" + modify_data['u_id']).html(modify_data['u_icon']);

            //Did we have notes to update?
            if (modify_data['tr_id'] > 0) {
                //Yes, update the notes:
                $(".ur__notes_" + modify_data['tr_id']).html(data.ur__notes);
                $(".tr_content_val_" + modify_data['tr_id']).text(modify_data['tr_content']);
            }

            if (modify_data['u_icon'].length > 0) {
                $('.u_icon_ui_' + modify_data['u_id']).removeClass('hidden').html('&nbsp;[' + modify_data['u_icon'] + ']');
                $('.u_icon_child_' + modify_data['u_id']).html(modify_data['u_icon']);
            } else {
                //hide that section
                $('.u_icon_ui_' + modify_data['u_id']).addClass('hidden');
                $('.u_icon_child_' + modify_data['u_id']).html('');
            }

            //has status updated? If so update the UI:
            if (original_u_status != modify_data['u_status']) {

                //Update status:
                $('.u_status_' + modify_data['u_id']).html(data.status_u_ui);

                //Adjust counters for the filtering system as that also will change:
                $('.count-u-status-' + modify_data['u_status']).text((parseInt($('.count-u-status-' + modify_data['u_status']).text()) + 1));
                $('.count-u-status-' + original_u_status).text((parseInt($('.count-u-status-' + original_u_status).text()) - 1));
                //TODO maybe the new counter element does not exist and we need to create it! Handle this case later...

                if (u_status_filter >= 0 && !(modify_data['u_status'] == u_status_filter)) {
                    //We have the filter on and it does not match the new status, so hide this:
                    setTimeout(function () {
                        $('.u__' + modify_data['u_id']).fadeOut();
                    }, 377);
                } else {
                    //Update status:
                    $('.u__' + modify_data['u_id']).attr('entity-status', modify_data['u_status']);
                }

            }

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

            //Update UI to confirm with user:
            $('.save_entity_changes').html(data.message).hide().fadeIn();

            //Disapper in a while:
            setTimeout(function () {
                //Hide the editor & saving results:
                $('.save_entity_changes').hide();
            }, 377);

        } else {
            //Ooops there was an error!
            $('.save_entity_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();
        }

    });

}


function u_load_messages(u_id) {

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#message-frame").removeClass('hidden').hide().fadeIn().attr('entity-id', u_id);
    $("#message-frame h4").text($(".u_full_name_" + u_id + ":first").text());

    var handler = $("#loaded-messages");

    //Show tem loader:
    handler.html('<div style="text-align:center; padding:10px 0 50px;"><img src="/img/round_load.gif" class="loader" /></div>');

    //We might need to scroll:
    if (is_compact) {
        $('.main-panel').animate({
            scrollTop: 9999
        }, 150);
    }

    //Load the frame:
    $.post("/entities/load_messages", {u_id: u_id}, function (data) {
        //Empty Inputs Fields if success:
        handler.html(data);

        //Show inner tooltips:
        $('[data-toggle="tooltip"]').tooltip();

    });

}