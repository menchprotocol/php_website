function en_load_search(focus_element, is_en_parent) {


    $(focus_element + ' .new-input').focus(function() {
        $(focus_element + ' .algolia_search_pad' ).removeClass('hidden');
    }).focusout(function() {
        $(focus_element + ' .algolia_search_pad' ).addClass('hidden');
    }).on('autocomplete:selected', function (event, suggestion, dataset) {

        en_add_or_link(suggestion.alg_obj_id, is_en_parent);

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

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
                return echo_js_suggestion(suggestion, 0);
            },
            header: function (data) {
                if (!data.isEmpty) {
                    return '<a href="javascript:en_add_or_link(0,'+is_en_parent+')" class="suggestion"><span><i class="fal fa-plus-circle add-plus"></i></span> <b>' + data.query + '</b></a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:en_add_or_link(0,'+is_en_parent+')" class="suggestion"><span><i class="fal fa-plus-circle add-plus"></i></span> <b>' + data.query + '</b></a>';
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


//Define file upload variables:
var upload_control = $(".inputfile");
var $input = $('.drag-box').find('input[type="file"]'),
    $label = $('.drag-box').find('label'),
    showFiles = function (files) {
        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
    };





function mass_action_ui(){
    $('.mass_action_item').addClass('hidden');
    $('#mass_id_' + $('#set_mass_action').val() ).removeClass('hidden');
}


$(document).ready(function () {

    //Load entity search for mass update function:
    $('.en_quick_search').on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('@' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name);

    }).autocomplete({hint: false, minLength: 3, keyboardShortcuts: ['a']}, [{

        source: function (q, cb) {
            algolia_index.search(q, {
                filters: 'alg_obj_is_in=0', //Search entities
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
                return echo_js_suggestion(suggestion, 0);
            },
            empty: function (data) {
                return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No entities found</div>';
            },
        }
    }]);



    //Lookout for intent link related changes:
    $('#tr_status').change(function () {
        if (parseInt($('#tr_status').find(":selected").val()) < 0) {
            //About to delete? Notify them:
            $('.notify_unlink_en').removeClass('hidden');
        } else {
            $('.notify_unlink_en').addClass('hidden');
        }
    });

    $('#set_mass_action').change(function () {
        mass_action_ui();
    });

    $('#en_status').change(function () {

        if (parseInt($('#en_status').find(":selected").val()) < 0) {

            //Notify admin:
            $('.notify_en_remove').removeClass('hidden');
            $('.entity_remove_stats').html('<i class="fas fa-spinner fa-spin"></i>');

            //About to delete... Fetch total links:
            $.post("/entities/en_count_to_be_removed_links", { en_id: parseInt($('#modifybox').attr('entity-id')) }, function (data) {

                if(data.status){
                    $('.entity_remove_stats').html('<b>'+data.en_link_count+'</b>');
                    $('#en_link_count').val(data.en_link_count); //This would require a confirmation upon saving...
                }

            });

        } else {

            $('.notify_en_remove').addClass('hidden');
            $('.entity_remove_stats').html('');
            $('#en_link_count').val('0');

        }
    });

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


    //Loadup various search bars:
    en_load_search("#new-parent", 1);
    en_load_search("#new-children", 0);


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



    //Do we need to auto load anything?
    if (window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        var hash_parts = hash.split("-");
        if (hash_parts.length >= 2) {
            //Fetch level if available:
            if (hash_parts[0] == 'entitymessages') {
                en_load_messages( hash_parts[1]);
            } else if (hash_parts[0] == 'loadmodify') {
                en_modify_load(hash_parts[1], hash_parts[2]);
            } else if (hash_parts[0] == 'loadenactionplans') {
                en_actionplans(hash_parts[1]);
            } else if (hash_parts[0] == 'status') {
                //Update status:
                en_filter_status(hash_parts[1]);
            }
        }
    }




    //Watchout for content change
    var textInput = document.getElementById('tr_content');

    //Init a timeout variable to be used below
    var timeout = null;

    //Listen for keystroke events
    textInput.onkeyup = function (e) {

        //Instantly update count:
        tr_content_word_count('#tr_content','#chartr_contentNum');

        // Clear the timeout if it has already been set.
        // This will prevent the previous step from executing
        // if it has been less than <MILLISECONDS>
        clearTimeout(timeout);

        // Make a new timeout set to go off in 800ms
        timeout = setTimeout(function () {
            //update type:
            en_tr_type_preview();
        }, 610);
    };



});


function en_actionplans(en_id){

    if(parseInt($('.actionplans_en_'+en_id).attr('ap-count')) < 1){
        alert('Entity not added any intents to their Action Plan yet');
        return false;
    }

}


//Adds OR links entities to entities
function en_add_or_link(en_existing_id, is_parent) {

    //if en_existing_id>0 it means we're linking to an existing entity, in which case en_new_string should be null
    //If en_existing_id=0 it means we are creating a new entity and then linking it, in which case en_new_string is required

    if (is_parent) {
        var input = $('#new-parent .new-input');
        var list_id = 'list-parent';
        var counter_class = '.li-parent-count';
    } else {
        var input = $('#new-children .new-input');
        var list_id = 'list-children';
        var counter_class = '.li-children-count';
    }


    var en_new_string = null;
    if (en_existing_id == 0) {
        en_new_string = input.val();
        if (en_new_string.length < 1) {
            alert('ERROR: Missing entity name or URL, try again');
            input.focus();
            return false;
        }
    }


    //Add via Ajax:
    $.post("/entities/en_add_or_link", {

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
            alert('Error: ' + data.message);
        }

    });
}


function en_filter_status(new_val) {
    if (new_val >= -1 || new_val <= 3) {
        //Remove active class:
        $('.u-status-filter').removeClass('btn-secondary');
        //We do have a filter:
        en_focus_filter = parseInt(new_val);
        $('.u-status-' + new_val).addClass('btn-secondary');
        en_load_next_page(0, 1);
    } else {
        alert('Invalid new status');
        return false;
    }
}

function en_name_word_count() {
    var len = $('#en_name').val().length;
    if (len > en_name_max_length) {
        $('#charNameNum').addClass('overload').text(len);
    } else {
        $('#charNameNum').removeClass('overload').text(len);
    }
}



function en_load_next_page(page, load_new_filter = 0) {

    if (load_new_filter) {
        //Replace load more with spinner:
        var append_div = $('#new-children').html();
        //The padding-bottom would remove the scrolling effect on the left side!
        $('#list-children').html('<span class="load-more" style="padding-bottom:500px;"><i class="fas fa-spinner fa-spin"></i></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><i class="fas fa-spinner fa-spin"></i></span>').hide().fadeIn();
    }

    $.post("/entities/en_load_next_page", {
        page: page,
        parent_en_id: en_focus_id,
        en_focus_filter: en_focus_filter,
    }, function (data) {

        //Appending to existing content:
        $('.load-more').remove();

        if (load_new_filter) {
            $('#list-children').html(data + '<div id="new-children" class="list-group-item list_input grey-input">' + append_div + '</div>').hide().fadeIn();
            //Reset search engine:
            en_load_search("#new-children", 0);
        } else {
            //Update UI to confirm with user:
            $(data).insertBefore('#new-children');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}


function en_tr_type_preview() {

    /*
     * Updates the type of link based on the link content
     *
     * */

    $('#en_link_type_id').html('<i class="fas fa-spinner fa-spin"></i> Loading...');


    //Fetch Intent Data to load modify widget:
    $.post("/entities/en_tr_type_preview", {
        tr_content: $('#tr_content').val(),
        tr_id: parseInt($('#modifybox').attr('entity-link-id')),
    }, function (data) {
        //All good, let's load the data into the Modify Widget...
        $('#en_link_type_id').html((data.status ? data.html_ui : 'Error: ' + data.message));

        if(data.status && data.en_link_preview.length > 0){
            $('#en_link_preview').html(data.en_link_preview);
        } else {
            $('#en_link_preview').html('');
        }

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();
    });
}



function en_modify_load(en_id, tr_id) {

    //Make sure inputs are valid:
    if (!$('.en___' + en_id).length) {
        alert('Error: Invalid Entity ID');
        return false;
    }

    //Update variables:
    $('#modifybox').attr('entity-link-id', tr_id);
    $('#modifybox').attr('entity-id', en_id);

    //Cannot be removed OR unlinked as this would not load, so remove them:
    $('.notify_en_remove, .notify_unlink_en').addClass('hidden');


    var en_full_name = $(".en_name_" + en_id + ":first").text();
    $('#en_name').val(en_full_name);
    $('.edit-header').html('<i class="fas fa-cog"></i> ' + en_full_name);
    $('#en_status').val($(".en___" + en_id + ":first").attr('entity-status'));
    $('#tr_status').val($(".en___" + en_id + ":first").attr('tr-status'));
    $('.save_entity_changes').html('');
    $('.entity_remove_stats').html('');
    $('#en_link_count').val('0');


    if (parseInt($('.en__icon_' + en_id).attr('en-is-set')) > 0) {
        $('.icon-demo').html($('.en__icon_' + en_id).html());
        $('#en_icon').val($('.en__icon_' + en_id).html());
    } else {
        //Clear out input:
        $('.icon-demo').html('<i class="fas fa-at grey-at"></i>');
        $('#en_icon').val('');
    }

    en_name_word_count();

    //Only show unlink button if not level 1
    if (parseInt(tr_id) > 0) {

        //Make the UI link and the notes in the edit box:
        $('.unlink-entity, .en-has-tr').removeClass('hidden');
        $('.en-no-tr').addClass('hidden');

        //Assign value:
        $('#tr_content').val($(".tr_content_val_" + tr_id + ":first").text());

        //Update count:
        tr_content_word_count('#tr_content','#chartr_contentNum');
        //Also update type:
        en_tr_type_preview();

    } else {

        //Hide the section and clear it:
        $('.unlink-entity, .en-has-tr').addClass('hidden');
        $('.en-no-tr').removeClass('hidden');

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

function entity_link_form_lock(){
    $('#tr_content').prop("disabled", true).css('background-color','#CCC');

    $('.btn-save').addClass('grey').attr('href', '#').html('<i class="fas fa-spinner fa-spin"></i> Uploading');

}

function entity_link_form_unlock(result){

    //What was the result?
    if (!result.status) {
        alert('ERROR: ' + result.message);
    }

    //Unlock either way:
    $('#tr_content').prop("disabled", false).css('background-color','#FFF');

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

    var current_value = $('#tr_content').val();
    if(current_value.length > 0){
        //There is something in the input field, notify the user:
        var r = confirm("Current transaction content [" + current_value + "] will be removed. Continue?");
        if (r == false) {
            return false;
        }
    }


    if (isAdvancedUpload) {

        //Lock message:
        entity_link_form_lock();

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
            url: '/entities/en_save_file_upload',
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
                    $('#tr_content').val( data.new__url );

                    //Update count:
                    tr_content_word_count('#tr_content','#chartr_contentNum');
                    //Also update type:
                    en_tr_type_preview();
                }

                //Unlock form:
                entity_link_form_unlock(data);

            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                entity_link_form_unlock(result);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}


function en_modify_save() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('entity-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Are we about to remove an entity with a lot of links?
    var link_count= parseInt($('#en_link_count').val());
    var action_verb = ( $('#en_merge').val().length > 0 ? 'merge' : 'remove' );
    var confirm_string = action_verb + " " + link_count;
    if(link_count >= 3){
        //Yes, confirm before doing so:
        var confirm_removal = prompt("You are about to remove this entity and "+action_verb+" all its "+link_count+" links. Type \""+confirm_string+"\" to confirm and "+action_verb+" entity with all its links.", "");

        if (!(confirm_removal == confirm_string)) {
            //Abandon process:
            alert('Entity will not be '+action_verb+'d.');
            return false;
        }
    }

    //Prepare data to be modified for this intent:
    var modify_data = {
        en_focus_id: en_focus_id, //Determines if we need to change location upon removing...
        en_id: parseInt($('#modifybox').attr('entity-id')),
        en_name: $('#en_name').val(),
        en_icon: $('#en_icon').val(),
        en_status: $('#en_status').val(), //The new status (might not have changed too)
        en_merge: $('#en_merge').val(),
        //Transaction data:
        tr_id: parseInt($('#modifybox').attr('entity-link-id')),
        tr_content: $('#tr_content').val(),
        tr_status: $('#tr_status').val(),
    };

    //Show spinner:
    $('.save_entity_changes').html('<span><i class="fas fa-spinner fa-spin"></i></span> Saving...').hide().fadeIn();


    $.post("/entities/en_modify_save", modify_data, function (data) {

        if (data.status) {

            if(data.remove_from_ui){

                //need to remove this entity:
                //Intent has been either removed OR unlinked:
                if (data.remove_redirect_url) {

                    //move up 1 level as this was the focus intent:
                    window.location = data.remove_redirect_url;

                } else {

                    //Remove Hash:
                    window.location.hash = '#';

                    //Remove from UI:
                    $('.tr_' + modify_data['tr_id']).html('<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>').fadeOut();

                    //Disappear in a while:
                    setTimeout(function () {

                        //Hide the editor & saving results:
                        $('.tr_' + modify_data['tr_id']).remove();

                        //Hide editing box:
                        $('#modifybox').addClass('hidden');

                    }, 610);

                }

            } else {

                //Reflect changed:
                //Update variables:
                $(".en_name_" + modify_data['en_id']).text(modify_data['en_name']);


                //Always update 2x Entity icons:
                $('.en_ui_icon_' + modify_data['en_id']).html(modify_data['en_icon']);
                $('.en_status_' + modify_data['en_id']).html('<span data-toggle="tooltip" data-placement="right" title="' + object_js_statuses['en_status'][modify_data['en_status']]["s_name"] + ': ' + object_js_statuses['en_status'][modify_data['en_status']]["s_desc"] + '">' + object_js_statuses['en_status'][modify_data['en_status']]["s_icon"] + '</span>');


                //Update other instances of the icon:
                var icon_is_set = ( modify_data['en_icon'].length > 0 ? 1 : 0 );
                $('.en__icon_' + modify_data['en_id']).attr('en-is-set' , icon_is_set );

                if(!icon_is_set){
                    //Set entity default icon:
                    modify_data['en_icon'] = '<i class="fas fa-at grey-at"></i>';
                    $('.en_child_icon_' + modify_data['en_id']).addClass('hidden');
                } else {
                    $('.en_child_icon_' + modify_data['en_id']).removeClass('hidden').html(modify_data['en_icon']);
                }
                $('.icon-demo').html(modify_data['en_icon']);



                //Did we have notes to update?
                if (modify_data['tr_id'] > 0) {

                    //Yes, update the notes:
                    $(".tr_content_" + modify_data['tr_id']).html(data.tr_content);
                    $(".tr_content_val_" + modify_data['tr_id']).text(data.tr_content_final);

                    //Did the content get modified? (Likely for a domain URL):
                    if(!(data.tr_content_final==modify_data['tr_content'])){
                        $("#tr_content").val(data.tr_content_final).hide().fadeIn('slow');
                    }


                    //Update 2x icons:
                    $('.tr_type_' + modify_data['tr_id']).html('<span data-toggle="tooltip" data-placement="right" title="' + en_all_4592[data.js_tr_type_entity_id]["m_name"] + ': ' + en_all_4592[data.js_tr_type_entity_id]["m_desc"] + '">' + en_all_4592[data.js_tr_type_entity_id]["m_icon"] + '</span>');

                    //Update status icon:
                    $('.tr_status_' + modify_data['tr_id']).html('<span class="tr_status_val" data-toggle="tooltip" data-placement="right" title="' + object_js_statuses['tr_status'][modify_data['tr_status']]["s_name"] + ': ' + object_js_statuses['tr_status'][modify_data['tr_status']]["s_desc"] + '">' + object_js_statuses['tr_status'][modify_data['tr_status']]["s_icon"] + '</span>');

                }

                if (modify_data['en_icon'].length > 0) {
                    $('.en_ui_icon_' + modify_data['en_id']).html(modify_data['en_icon']);
                    $('.en_child_icon_' + modify_data['en_id']).html(modify_data['en_icon']);
                } else {
                    //hide that section
                    $('.en_ui_icon_' + modify_data['en_id']).html('<i class="fas fa-at grey-at"></i>');
                    $('.en_child_icon_' + modify_data['en_id']).html('');
                }


                //Update entity timestamp:
                $('.save_entity_changes').html(data.message);

                //Reload Tooltip again:
                $('[data-toggle="tooltip"]').tooltip();
            }

        } else {
            //Ooops there was an error!
            $('.save_entity_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();
        }

    });

}



function en_load_messages(en_id) {

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#message-frame").removeClass('hidden').hide().fadeIn().attr('entity-id', en_id);
    $("#message-frame h4").text($(".en_name_" + en_id + ":first").text());

    var handler = $("#loaded-messages");

    //Show tem loader:
    handler.html('<div style="text-align:center; padding:10px 0 50px;"><i class="fas fa-spinner fa-spin"></i></div>');

    //We might need to scroll:
    if (is_compact) {
        $('.main-panel').animate({
            scrollTop: 9999
        }, 150);
    }

    //Load the frame:
    $.post("/entities/en_load_messages/"+en_id, {}, function (data) {
        //Raw Inputs Fields if success:
        handler.html(data);

        //Show inner tooltips:
        $('[data-toggle="tooltip"]').tooltip();

    });

}