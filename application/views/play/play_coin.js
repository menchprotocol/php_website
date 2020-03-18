


//Define file upload variables:
var upload_control = $(".inputfile");
var $input = $('.drag-box').find('input[type="file"]'),
    $label = $('.drag-box').find('label'),
    showFiles = function (files) {
        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
    };

$(document).ready(function () {

    //Load player search for mass update function:
    $('.en_quick_search').on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('@' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name);

    }).autocomplete({hint: false, minLength: 2}, [{

        source: function (q, cb) {
            algolia_index.search(q, {
                filters: 'alg_obj_is_in=0', //Search players
                hitsPerPage: 5,
            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        displayKey: function (suggestion) {
            return '@' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name;
        },
        templates: {
            suggestion: function (suggestion) {
                return echo_js_suggestion(suggestion);
            },
            empty: function (data) {
                return '<div class="not-found"><i class="fad fa-exclamation-triangle"></i> No players found</div>';
            },
        }
    }]);

    //Keep an eye for icon change:
    $('#en_icon').keyup(function() {
        update_demo_icon();
    });

    //Lookout for idea link related changes:
    $('#ln_status_play_id').change(function () {
        if (parseInt($('#ln_status_play_id').find(":selected").val()) == 6173 /* Link Removed */ ) {
            //About to delete? Notify them:
            $('.notify_unlink_en').removeClass('hidden');
        } else {
            $('.notify_unlink_en').addClass('hidden');
        }
    });

    $('#set_mass_action').change(function () {
        mass_action_ui();
    });

    $('#en_status_play_id').change(function () {

        if (parseInt($('#en_status_play_id').find(":selected").val()) == 6178 /* Player Removed */) {

            //Notify Trainer:
            $('.notify_en_remove').removeClass('hidden');
            $('.player_remove_stats').html('<i class="far fa-yin-yang fa-spin"></i>');

            //About to delete... Fetch total links:
            $.post("/play/en_count_to_be_removed_links", { en_id: parseInt($('#modifybox').attr('player-id')) }, function (data) {

                if(data.status){
                    $('.player_remove_stats').html('<b>'+data.en_link_count+'</b>');
                    $('#en_link_count').val(data.en_link_count); //This would require a confirmation upon saving...
                }

            });

        } else {

            $('.notify_en_remove').addClass('hidden');
            $('.player_remove_stats').html('');
            $('#en_link_count').val('0');

        }
    });

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


    //Loadup various search bars:
    en_load_search("#new-parent", 1, 'q');
    en_load_search("#new-children", 0, 'w');


    //Watchout for file uplods:
    $('.drag-box').find('input[type="file"]').change(function () {
        en_save_file_upload(droppedFiles, 'file');
    });

    //Should we auto start?
    if (isAdvancedUpload) {

        $('.drag-box').addClass('has-advanced-upload');
        var droppedFiles = false;

        $('.drag-box').on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
        })
            .on('dragover dragenter', function () {
                $('.en-has-tr').addClass('is-working');
            })
            .on('dragleave dragend drop', function () {
                $('.en-has-tr').removeClass('is-working');
            })
            .on('drop', function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                e.preventDefault();
                en_save_file_upload(droppedFiles, 'drop');
            });
    }





    //Watchout for content change
    var textInput = document.getElementById('ln_content');

    //Init a timeout variable to be used below
    var timeout = null;

    //Listen for keystroke events
    textInput.onkeyup = function (e) {

        //Instantly update count:
        ln_content_word_count('#ln_content','#charln_contentNum');

        // Clear the timeout if it has already been set.
        // This will prevent the previous step from executing
        // if it has been less than <MILLISECONDS>
        clearTimeout(timeout);

        // Make a new timeout set to go off in 800ms
        timeout = setTimeout(function () {
            //update type:
            en_ln_type_preview();
        }, 610);
    };



});





function en_load_search(element_focus, is_en_parent, shortcut) {

    $(element_focus + ' .new-player-input')

        .focus(function() {
        $(element_focus + ' .algolia_pad_search' ).removeClass('hidden');
    }).focusout(function() {
        $(element_focus + ' .algolia_pad_search' ).addClass('hidden');
    })

        .on('autocomplete:selected', function (event, suggestion, dataset) {

        en_add_or_link(suggestion.alg_obj_id, is_en_parent);

    }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [shortcut]}, [{

        source: function (q, cb) {
            algolia_index.search(q, {
                filters: 'alg_obj_is_in=0',
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
                //If clicked, would trigger the autocomplete:selected above which will trigger the en_add_or_link() function
                return echo_js_suggestion(suggestion);
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:en_add_or_link(0,'+is_en_parent+')" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus play"></i></span><b>' + data.query.toUpperCase() + '</b></a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:en_add_or_link(0,'+is_en_parent+')" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus play"></i></span><b>' + data.query.toUpperCase() + '</b></a>';
            },
        }
    }]).keypress(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            en_add_or_link(0, is_en_parent);
            return true;
        }

    });
}



function mass_action_ui(){
    $('.mass_action_item').addClass('hidden');
    $('#mass_id_' + $('#set_mass_action').val() ).removeClass('hidden');
}




//Adds OR links players to players
function en_add_or_link(en_existing_id, is_parent) {

    //if en_existing_id>0 it means we're linking to an existing player, in which case en_new_string should be null
    //If en_existing_id=0 it means we are creating a new player and then linking it, in which case en_new_string is required

    if (is_parent) {
        var input = $('#new-parent .new-player-input');
        var list_id = 'list-parent';
        var counter_class = '.counter-11030';
    } else {
        var input = $('#new-children .new-player-input');
        var list_id = 'list-children';
        var counter_class = '.counter-11029';
    }

    var en_new_string = null;
    if (en_existing_id == 0) {
        en_new_string = input.val();
        if (en_new_string.length < 1) {
            alert('Note: Missing player name or URL, try again');
            input.focus();
            return false;
        }
    }


    //Add via Ajax:
    $.post("/play/en_add_or_link", {

        en_id: en_focus_id,
        en_existing_id: en_existing_id,
        en_new_string: en_new_string,
        is_parent: (is_parent ? 1 : 0),

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);

        if (data.status) {

            //Raw input to make it ready for next URL:
            input.focus();

            //Add new object to list:
            add_to_list(list_id, '.en-item', data.en_new_echo);

            //Adjust counters:
            $(counter_class).text((parseInt($(counter_class + ':first').text()) + 1));
            $('.count-u-status-' + data.en_new_status).text((parseInt($('.count-u-status-' + data.en_new_status).text()) + 1));

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert('Note: ' + data.message);
        }

    });
}


function en_filter_status(new_val) {
    //Remove active class:
    $('.u-status-filter').removeClass('active');
    //We do have a filter:
    en_focus_filter = parseInt(new_val);
    $('.u-status-' + new_val).addClass('active');
    en_load_next_page(0, 1);
}

function en_name_word_count() {
    var len = $('#en_name').val().length;
    if (len > js_en_all_6404[11072]['m_desc']) {
        $('#charEnNum').addClass('overload').text(len);
    } else {
        $('#charEnNum').removeClass('overload').text(len);
    }
}



function en_load_next_page(page, load_new_filter) {

    if (load_new_filter) {
        //Replace load more with spinner:
        var append_div = $('#new-children').html();
        //The padding-bottom would remove the scrolling effect on the left side!
        $('#list-children').html('<span class="load-more" style="padding-bottom:500px;"><i class="far fa-yin-yang fa-spin"></i></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><i class="far fa-yin-yang fa-spin"></i></span>').hide().fadeIn();
    }

    $.post("/play/en_load_next_page", {
        page: page,
        parent_en_id: en_focus_id,
        en_focus_filter: en_focus_filter,
    }, function (data) {

        //Appending to existing content:
        $('.load-more').remove();

        if (load_new_filter) {
            $('#list-children').html(data + '<div id="new-children" class="list-group-item itemplay grey-input">' + append_div + '</div>').hide().fadeIn();
            //Reset search engine:
            en_load_search("#new-children", 0, 'w');
        } else {
            //Update UI to confirm with user:
            $(data).insertBefore('#new-children');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}


function en_ln_type_preview() {

    /*
     * Updates the type of link based on the link content
     *
     * */

    $('#en_type_link_id').html('<i class="far fa-yin-yang fa-spin"></i>');


    //Fetch Idea Data to load modify widget:
    $.post("/play/en_ln_type_preview", {
        ln_content: $('#ln_content').val(),
        ln_id: parseInt($('#modifybox').attr('player-link-id')),
    }, function (data) {
        //All good, let's load the data into the Modify Widget...
        $('#en_type_link_id').html((data.status ? data.html_ui : 'Note: ' + data.message));

        if(data.status && data.en_link_preview.length > 0){
            $('#en_link_preview').html(data.en_link_preview);
        } else {
            $('#en_link_preview').html('');
        }

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();
    });
}


function update_demo_icon(){
    //Update demo icon based on icon input value:
    $('.icon-demo').html(($('#en_icon').val().length > 0 ? $('#en_icon').val() : js_en_all_2738[4536]['m_icon'] ));
}

function en_modify_load(en_id, ln_id) {

    //Make sure inputs are valid:
    if (!$('.en___' + en_id).length) {
        alert('Note: Invalid Player ID');
        return false;
    }

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();

    //Update variables:
    $('#modifybox').attr('player-link-id', ln_id);
    $('#modifybox').attr('player-id', en_id);

    //Cannot be removed OR unlinked as this would not load, so remove them:
    $('.notify_en_remove, .notify_unlink_en').addClass('hidden');

    //Set opacity:
    remove_all_highlights();
    $(".highlight_en_"+en_id).addClass('en_highlight');


    var en_full_name = $(".en_name_" + en_id + ":first").text();
    $('#en_name').val(en_full_name.toUpperCase()).focus();
    $('.edit-header').html('<i class="fas fa-cog"></i> ' + en_full_name);
    $('#en_status_play_id').val($(".en___" + en_id + ":first").attr('en-status'));
    $('.save_player_changes').html('');
    $('.player_remove_stats').html('');

    if (parseInt($('.en__icon_' + en_id).attr('en-is-set')) > 0) {
        $('#en_icon').val($('.en__icon_' + en_id).html());
    } else {
        //Clear out input:
        $('#en_icon').val('');
    }

    en_name_word_count();
    update_demo_icon();

    //Only show unlink button if not level 1
    if (parseInt(ln_id) > 0) {

        $('#ln_status_play_id').val($(".en___" + en_id + ":first").attr('ln-status'));
        $('#en_link_count').val('0');


        //Make the UI link and the notes in the edit box:
        $('.unlink-player, .en-has-tr').removeClass('hidden');

        //Assign value:
        $('#ln_content').val($(".ln_content_val_" + ln_id + ":first").text());

        //Update count:
        ln_content_word_count('#ln_content','#charln_contentNum');
        //Also update type:
        en_ln_type_preview();

    } else {

        //Hide the section and clear it:
        $('.unlink-player, .en-has-tr').addClass('hidden');

    }
}

function player_link_form_lock(){
    $('#ln_content').prop("disabled", true).css('background-color','#AAAAAA');

    $('.btn-save').addClass('grey').attr('href', '#').html('<i class="far fa-yin-yang fa-spin"></i> Uploading');

}

function player_link_form_unlock(result){

    //What was the result?
    if (!result.status) {
        alert('Note: ' + result.message);
    }

    //Unlock either way:
    $('#ln_content').prop("disabled", false).css('background-color','#FFF');

    $('.btn-save').removeClass('grey').attr('href', 'javascript:en_modify_save();').html('Save');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();

    //Replace the upload form to reset:
    upload_control.replaceWith( upload_control = upload_control.clone( true ) );
}


function en_save_file_upload(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.drag-box').hasClass('is-uploading')) {
        return false;
    }

    var current_value = $('#ln_content').val();
    if(current_value.length > 0){
        //There is something in the input field, notify the user:
        var r = confirm("Current link content [" + current_value + "] will be removed. Continue?");
        if (r == false) {
            return false;
        }
    }


    if (isAdvancedUpload) {

        //Lock message:
        player_link_form_lock();

        var ajaxData = new FormData($('.drag-box').get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $input.attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);

        $.ajax({
            url: '/play/en_save_file_upload',
            type: 'post',
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.drag-box').removeClass('is-uploading');
            },
            success: function (data) {

                if(data.status){

                    //Add URL to input:
                    $('#ln_content').val( data.cdn_url );

                    //Update count:
                    ln_content_word_count('#ln_content','#charln_contentNum');

                    //Also update type:
                    en_ln_type_preview();
                }

                //Unlock form:
                player_link_form_unlock(data);

            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                player_link_form_unlock(result);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}


function en_modify_save() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('player-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Are we about to remove an player with a lot of links?
    var link_count= parseInt($('#en_link_count').val());
    var action_verb = ( $('#en_merge').val().length > 0 ? 'merge' : 'remove' );
    var confirm_string = action_verb + " " + link_count;
    if(link_count >= 3){
        //Yes, confirm before doing so:
        var confirm_removal = prompt("You are about to remove this player and "+action_verb+" all its "+link_count+" links. Type \""+confirm_string+"\" to confirm and "+action_verb+" player with all its links.", "");

        if (!(confirm_removal == confirm_string)) {
            //Abandon process:
            alert('Player will not be '+action_verb+'d.');
            return false;
        }
    }

    //Prepare data to be modified for this idea:
    var modify_data = {
        en_focus_id: en_focus_id, //Determines if we need to change location upon removing...
        en_id: parseInt($('#modifybox').attr('player-id')),
        en_name: $('#en_name').val().toUpperCase(),
        en_icon: $('#en_icon').val(),
        en_status_play_id: $('#en_status_play_id').val(), //The new status (might not have changed too)
        en_merge: $('#en_merge').val(),
        //Link data:
        ln_id: parseInt($('#modifybox').attr('player-link-id')),
        ln_content: $('#ln_content').val(),
        ln_status_play_id: $('#ln_status_play_id').val(),
    };

    //Show spinner:
    $('.save_player_changes').html('<span><i class="far fa-yin-yang fa-spin"></i></span> ' + echo_saving_notify() +  '').hide().fadeIn();


    $.post("/play/en_modify_save", modify_data, function (data) {

        if (data.status) {

            if(data.remove_from_ui){

                //need to remove this player:
                //Idea has been either removed OR unlinked:
                if (data.remove_redirect_url) {

                    //move up 1 level as this was the focus idea:
                    window.location = data.remove_redirect_url;

                } else {

                    //Reset opacity:
                    remove_all_highlights();

                    //Remove from UI:
                    $('.tr_' + modify_data['ln_id']).html('<span style="color:#000000;"><i class="fas fa-trash-alt"></i> Removed</span>').fadeOut();

                    //Disappear in a while:
                    setTimeout(function () {

                        //Hide the editor & saving results:
                        $('.tr_' + modify_data['ln_id']).remove();

                        //Hide editing box:
                        $('#modifybox').addClass('hidden');

                    }, 610);

                }

            } else {

                //Reflect changed:
                //Update variables:
                $(".en_name_" + modify_data['en_id']).text(modify_data['en_name']);


                //Player Status:
                $(".en___" + modify_data['en_id']).attr('en-status', modify_data['en_status_play_id']);
                $('.en_status_play_id_' + modify_data['en_id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_en_all_6177[modify_data['en_status_play_id']]["m_name"] + ': ' + js_en_all_6177[modify_data['en_status_play_id']]["m_desc"] + '">' + js_en_all_6177[modify_data['en_status_play_id']]["m_icon"] + '</span>');


                //Player Icon:
                var icon_is_set = ( modify_data['en_icon'].length > 0 ? 1 : 0 );
                if(!icon_is_set){
                    //Set player default icon:
                    modify_data['en_icon'] = js_en_all_2738[4536]['m_icon'];
                }
                $('.en__icon_' + modify_data['en_id']).attr('en-is-set' , icon_is_set );
                $('.en_ui_icon_' + modify_data['en_id']).html(modify_data['en_icon']);
                $('.en_child_icon_' + modify_data['en_id']).html(modify_data['en_icon']);


                //Did we have notes to update?
                if (modify_data['ln_id'] > 0) {

                    //Yes, update the notes:
                    $(".ln_content_" + modify_data['ln_id']).html(data.ln_content);
                    $(".ln_content_val_" + modify_data['ln_id']).text(data.ln_content_final);

                    //Did the content get modified? (Likely for a domain URL):
                    if(!(data.ln_content_final==modify_data['ln_content'])){
                        $("#ln_content").val(data.ln_content_final).hide().fadeIn('slow');
                    }


                    //Link Icon:
                    $('.ln_type_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="' + en_all_4592[data.js_ln_type_play_id]["m_name"] + ': ' + en_all_4592[data.js_ln_type_play_id]["m_desc"] + '">' + en_all_4592[data.js_ln_type_play_id]["m_icon"] + '</span>');

                    //Link Status:
                    $(".en___" + modify_data['en_id']).attr('ln-status', modify_data['ln_status_play_id'])
                    $('.ln_status_play_id_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_en_all_6186[modify_data['ln_status_play_id']]["m_name"] + ': ' + js_en_all_6186[modify_data['ln_status_play_id']]["m_desc"] + '">' + js_en_all_6186[modify_data['ln_status_play_id']]["m_icon"] + '</span>');

                }


                //Update player timestamp:
                $('.save_player_changes').html(data.message);

                //Reload Tooltip again:
                $('[data-toggle="tooltip"]').tooltip();
            }

        } else {
            //Ooops there was an error!
            $('.save_player_changes').html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();
        }

    });

}
