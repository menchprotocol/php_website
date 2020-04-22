


//Define file upload variables:
var upload_control = $(".inputfile");
var $input = $('.drag-box').find('input[type="file"]'),
    $label = $('.drag-box').find('label'),
    showFiles = function (files) {
        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
    };

$(document).ready(function () {

    //Setup auto focus:
    $('#openEn6197').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#en_setting_name').val();
        setTimeout(function() { $('#en_setting_name').focus().val('').val(original_val); }, 144);
    });

    $('#openEn3288').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#en_email').val();
        setTimeout(function() { $('#en_email').focus().val('').val(original_val); }, 144);
    });

    $('#openEn3286').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#input_password').val();
        setTimeout(function() { $('#input_password').focus().val('').val(original_val); }, 144);
    });



    $('#new-parent').focus(function() {
        $('#new-parent .pad_expand').removeClass('hidden');
    }).focusout(function() {
        $('#new-parent .pad_expand').addClass('hidden');
    });

    $('#new-children').focus(function() {
        $('#new-children .pad_expand').removeClass('hidden');
    }).focusout(function() {
        $('#new-children .pad_expand').addClass('hidden');
    });



    //Load search for mass update function:
    load_editor();

    //Keep an eye for icon change:
    $('#en_icon').keyup(function() {
        update_demo_icon();
    });

    //Lookout for idea link related changes:
    $('#ln_status_source_id').change(function () {
        if (parseInt($('#ln_status_source_id').find(":selected").val()) == 6173 /* Link Deleted */ ) {
            //About to delete? Notify them:
            $('.notify_unlink_en').removeClass('hidden');
        } else {
            $('.notify_unlink_en').addClass('hidden');
        }
    });

    $('#en_status_source_id').change(function () {

        if (parseInt($('#en_status_source_id').find(":selected").val()) == 6178 /* Player Deleted */) {

            //Notify Player:
            $('.notify_en_delete').removeClass('hidden');
            $('.source_delete_stats').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

            //About to delete... Fetch total links:
            $.post("/source/en_count_delete_links", { en_id: parseInt($('#modifybox').attr('source-id')) }, function (data) {

                if(data.status){
                    $('.source_delete_stats').html('<b>'+data.en_link_count+'</b>');
                    $('#en_link_count').val(data.en_link_count); //This would require a confirmation upon saving...
                }

            });

        } else {

            $('.notify_en_delete').addClass('hidden');
            $('.source_delete_stats').html('');
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

    en_ln_type_preview_load();

});





function en_load_search(element_focus, is_en_parent, shortcut) {

    $(element_focus + ' .add-input').focus(function() {

        $(element_focus + ' .algolia_pad_search' ).removeClass('hidden');

    }).focusout(function() {

        $(element_focus + ' .algolia_pad_search' ).addClass('hidden');

    }).keypress(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            en_add_or_link(0, is_en_parent);
            return true;
        }

    });

    if(parseInt(js_en_all_6404[12678]['m_desc'])){

            $(element_focus + ' .add-input').on('autocomplete:selected', function (event, suggestion, dataset) {

                en_add_or_link(suggestion.alg_obj_id, is_en_parent);

            }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [( is_en_parent ? 'q' : 'a' )]}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'alg_obj_type_id=4536',
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
                    return echo_search_result(suggestion);
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:en_add_or_link(0,'+is_en_parent+')" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:en_add_or_link(0,'+is_en_parent+')" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                },
            }
        }]);
    }
}





function account_toggle_superpower(superpower_id){

    superpower_id = parseInt(superpower_id);

    var superpower_icon = $('.superpower-frame-'+superpower_id).html();
    $('.superpower-frame-'+superpower_id).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Save session variable to save the state of advance setting:
    $.post("/source/account_toggle_superpower/"+superpower_id, {}, function (data) {

        //Change top menu icon:
        $('.superpower-frame-'+superpower_id).html(superpower_icon);

        if(!data.status){

            alert('Alert: ' + data.message);

        } else {

            //Toggle UI elements:
            $('.superpower-'+superpower_id).toggleClass('hidden');

            //Change top menu icon:
            $('.superpower-frame-'+superpower_id).toggleClass('active');

            //TOGGLE:
            var index = js_session_superpowers_assigned.indexOf(superpower_id);
            if (index > -1) {
                //Delete it:
                js_session_superpowers_assigned.splice(index, 1);
            } else {
                //Not there, add it:
                js_session_superpowers_assigned.push(superpower_id);
            }
        }
    });

}



//Adds OR links sources to sources
function en_add_or_link(en_existing_id, is_parent) {

    //if en_existing_id>0 it means we're linking to an existing source, in which case en_new_string should be null
    //If en_existing_id=0 it means we are creating a new source and then linking it, in which case en_new_string is required

    if (is_parent) {
        var input = $('#new-parent .add-input');
        var list_id = 'list-parent';
    } else {
        var input = $('#new-children .add-input');
        var list_id = 'list-children';
    }

    var en_new_string = null;
    if (en_existing_id == 0) {
        en_new_string = input.val();
        if (en_new_string.length < 1) {
            alert('Alert: Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }


    //Add via Ajax:
    $.post("/source/en_add_or_link", {

        en_id: en_focus_id,
        en_existing_id: en_existing_id,
        en_new_string: en_new_string,
        is_parent: (is_parent ? 1 : 0),

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);

        if (data.status) {

            //Raw input to make it discovery for next URL:
            input.focus();

            //Add new object to list:
            add_to_list(list_id, '.en-item', data.en_new_echo);

            //Adjust counters:
            $('.count-en-status-' + data.en_new_status).text((parseInt($('.count-en-status-' + data.en_new_status).text()) + 1));

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert('Alert: ' + data.message);
        }

    });
}


function en_filter_status(new_val) {
    //Delete active class:
    $('.en-status-filter').removeClass('active');
    //We do have a filter:
    en_focus_filter = parseInt(new_val);
    $('.en-status-' + new_val).addClass('active');
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
        //The padding-bottom would delete the scrolling effect on the left side!
        $('#list-children').html('<span class="load-more" style="padding-bottom:500px;"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    }

    $.post("/source/en_load_next_page", {
        page: page,
        parent_en_id: en_focus_id,
        en_focus_filter: en_focus_filter,
    }, function (data) {

        //Appending to existing content:
        $('.load-more').remove();

        if (load_new_filter) {
            $('#list-children').html(data + '<div id="new-children" class="list-group-item itemsource grey-input">' + append_div + '</div>').hide().fadeIn();
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


function update_demo_icon(){
    //Update demo icon based on icon input value:
    $('.icon-demo').html(($('#en_icon').val().length > 0 ? $('#en_icon').val() : js_en_all_2738[4536]['m_icon'] ));
}

function en_modify_load(en_id, ln_id) {

    //Make sure inputs are valid:
    if (!$('.en___' + en_id).length) {
        alert('Alert: Invalid Source ID');
        return false;
    }

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();

    //Update variables:
    $('#modifybox').attr('source-link-id', ln_id);
    $('#modifybox').attr('source-id', en_id);

    //Cannot be deleted OR unlinked as this would not load, so delete them:
    $('.notify_en_delete, .notify_unlink_en').addClass('hidden');

    //Set opacity:
    delete_all_highlights();
    $(".highlight_en_"+en_id).addClass('en_highlight');


    var en_full_name = $(".en_name_full_" + en_id + ":first").text();
    $('#en_name').val(en_full_name.toUpperCase()).focus();
    $('.edit-header').html('<i class="fas fa-cog"></i> ' + en_full_name);
    $('#en_status_source_id').val($(".en___" + en_id + ":first").attr('en-status'));
    $('.save_source_changes').html('');
    $('.source_delete_stats').html('');

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

        $('#ln_status_source_id').val($(".en___" + en_id + ":first").attr('ln-status'));
        $('#en_link_count').val('0');


        //Make the UI link and the ideas in the edit box:
        $('.unlink-source, .en-has-tr').removeClass('hidden');

        //Assign value:
        $('#ln_content').val($(".ln_content_val_" + ln_id + ":first").text());

        //Also update type:
        en_ln_type_preview();

    } else {

        //Hide the section and clear it:
        $('.unlink-source, .en-has-tr').addClass('hidden');

    }
}

function source_link_form_lock(){
    $('#ln_content').prop("disabled", true).css('background-color','#999999');

    $('.btn-save').addClass('grey').attr('href', '#').html('<span class="icon-block">i class="far fa-yin-yang fa-spin"></i></span>Uploading');

}

function source_link_form_unlock(result){

    //What was the result?
    if (!result.status) {
        alert('Alert: ' + result.message);
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
        var r = confirm("Current link content [" + current_value + "] will be deleted. Continue?");
        if (r == false) {
            return false;
        }
    }


    if (isAdvancedUpload) {

        //Lock message:
        source_link_form_lock();

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
            url: '/source/en_save_file_upload',
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

                    //Also update type:
                    en_ln_type_preview();
                }

                //Unlock form:
                source_link_form_unlock(data);

            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                source_link_form_unlock(result);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}


function en_modify_save() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('source-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Are we about to delete an source with a lot of links?
    var link_count= parseInt($('#en_link_count').val());
    if(link_count >= 3){
        //Yes, confirm before doing so:
        var confirm_removal = prompt("Delete source & "+( $('#en_merge').val().length > 0 ? 'merge' : 'unlink' )+" "+link_count+" links?! Type \"delete\" to confirm.", "");

        if (!(confirm_removal == 'delete')) {
            //Abandon process:
            alert('Source will not be deleted.');
            return false;
        }
    }

    //Prepare data to be modified for this idea:
    var modify_data = {
        en_focus_id: en_focus_id, //Determines if we need to change location upon removing...
        en_id: parseInt($('#modifybox').attr('source-id')),
        en_name: $('#en_name').val().toUpperCase(),
        en_icon: $('#en_icon').val(),
        en_status_source_id: $('#en_status_source_id').val(), //The new status (might not have changed too)
        en_merge: $('#en_merge').val(),
        //Link data:
        ln_id: parseInt($('#modifybox').attr('source-link-id')),
        ln_content: $('#ln_content').val(),
        ln_status_source_id: $('#ln_status_source_id').val(),
    };

    //Show spinner:
    $('.save_source_changes').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_echo_platform_message(12695) +  '').hide().fadeIn();


    $.post("/source/en_modify_save", modify_data, function (data) {

        if (data.status) {

            if(data.delete_from_ui){

                //need to delete this source:
                //Idea has been either deleted OR unlinked:
                if (data.delete_redirect_url) {

                    //move up 1 level as this was the focus idea:
                    window.location = data.delete_redirect_url;

                } else {

                    //Reset opacity:
                    delete_all_highlights();

                    //Delete from UI:
                    $('.tr_' + modify_data['ln_id']).html('<span style="color:#000000;"><i class="fas fa-trash-alt"></i> Deleted</span>').fadeOut();

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
                $(".en_name_full_" + modify_data['en_id']).text(modify_data['en_name']);


                //Player Status:
                $(".en___" + modify_data['en_id']).attr('en-status', modify_data['en_status_source_id']);
                $('.en_status_source_id_' + modify_data['en_id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_en_all_6177[modify_data['en_status_source_id']]["m_name"] + ': ' + js_en_all_6177[modify_data['en_status_source_id']]["m_desc"] + '">' + js_en_all_6177[modify_data['en_status_source_id']]["m_icon"] + '</span>');


                //Player Icon:
                var icon_is_set = ( modify_data['en_icon'].length > 0 ? 1 : 0 );
                if(!icon_is_set){
                    //Set source default icon:
                    modify_data['en_icon'] = js_en_all_2738[4536]['m_icon'];
                }
                $('.en__icon_' + modify_data['en_id']).attr('en-is-set' , icon_is_set );
                $('.en_ui_icon_' + modify_data['en_id']).html(modify_data['en_icon']);
                $('.en_child_icon_' + modify_data['en_id']).html(modify_data['en_icon']);


                //Did we have ideas to update?
                if (modify_data['ln_id'] > 0) {

                    //Yes, update the ideas:
                    $(".ln_content_" + modify_data['ln_id']).html(data.ln_content);
                    $(".ln_content_val_" + modify_data['ln_id']).text(data.ln_content_final);

                    //Did the content get modified? (Likely for a domain URL):
                    if(!(data.ln_content_final==modify_data['ln_content'])){
                        $("#ln_content").val(data.ln_content_final).hide().fadeIn('slow');
                    }


                    //Link Icon:
                    $('.ln_type_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_en_all_4592[data.js_ln_type_source_id]["m_name"] + ': ' + js_en_all_4592[data.js_ln_type_source_id]["m_desc"] + '">' + js_en_all_4592[data.js_ln_type_source_id]["m_icon"] + '</span>');

                    //Transaction Status:
                    $(".en___" + modify_data['en_id']).attr('ln-status', modify_data['ln_status_source_id'])
                    $('.ln_status_source_id_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_en_all_6186[modify_data['ln_status_source_id']]["m_name"] + ': ' + js_en_all_6186[modify_data['ln_status_source_id']]["m_desc"] + '">' + js_en_all_6186[modify_data['ln_status_source_id']]["m_icon"] + '</span>');

                }


                //Update source timestamp:
                $('.save_source_changes').html(data.message);

                //Reload Tooltip again:
                $('[data-toggle="tooltip"]').tooltip();
            }

        } else {
            //Ooops there was an error!
            $('.save_source_changes').html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();
        }

    });

}




function account_update_avatar_type(type_css){

    //Find active avatar:
    var selected_avatar = $('.avatar-item.active i').attr('class').split(' ');

    //Adjust menu:
    $('.avatar-type-group .btn').removeClass('active');
    $('.avatar-type-group .btn-'+type_css).addClass('active');


    //Show correct avatars:
    $('.avatar-item').addClass('hidden').removeClass('active');
    $('.avatar-type-'+type_css).removeClass('hidden');

    //Update Selection:
    $('.avatar-type-'+type_css+'.avatar-name-'+selected_avatar[1]).addClass('active');

    //Update Icon:
    account_update_avatar_icon(type_css, null);

}

function account_update_avatar_icon(type_css, icon_css){

    //Detect current icon type:
    if(!icon_css){
        icon_css = $('.avatar-item.active').attr('icon-css');
    } else {
        //Set Proper Focus:
        $('.avatar-item').removeClass('active');
        $('.avatar-item.avatar-name-'+icon_css).addClass('active');
    }

    $('.en_ui_icon_'+js_pl_id).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Update via call:
    $.post("/source/account_update_avatar_icon", {
        type_css: type_css,
        icon_css: icon_css,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            alert('Alert: ' + data.message);

        } else {

            //Delete message:
            $('.en_ui_icon_'+js_pl_id).html(data.new_avatar);

        }
    });

}


function account_update_radio(parent_en_id, selected_en_id, enable_mulitiselect){

    var was_previously_selected = ( $('.radio-'+parent_en_id+' .item-'+selected_en_id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_previously_selected){
        //Nothing to do here:
        return false;
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+parent_en_id+' .item-'+selected_en_id+' .change-results';
    $(notify_el).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+parent_en_id+' .list-group-item').removeClass('active');
    }

    //Enable currently selected:
    if(enable_mulitiselect && was_previously_selected){
        $('.radio-'+parent_en_id+' .item-'+selected_en_id).removeClass('active');
    } else {
        $('.radio-'+parent_en_id+' .item-'+selected_en_id).addClass('active');
    }

    $.post("/source/account_update_radio", {
        parent_en_id: parent_en_id,
        selected_en_id: selected_en_id,
        enable_mulitiselect: enable_mulitiselect,
        was_previously_selected: was_previously_selected,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $(notify_el).html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>');

        } else {

            //Delete message:
            $(notify_el).html('');

        }
    });


}

function account_update_name(){

    //Show spinner:
    $('.save_full_name').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_echo_platform_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    var en_name_new = $('#en_setting_name').val().toUpperCase();
    $.post("/source/account_update_name", {
        en_name: en_name_new,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_full_name').html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_full_name').html('<i class="fas fa-check-circle"></i> ' + data.message + '</span>').hide().fadeIn();

            //Update name on page:
            $('.en_name_first_'+js_pl_id).text(data.first__name);
            $('.en_name_full_'+js_pl_id).text(en_name_new);

            //Disappear in a while:
            setTimeout(function () {

                $('.save_full_name').html('');

            }, 1597);

        }
    });

}

function account_update_email(){

    //Show spinner:
    $('.save_email').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_echo_platform_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/source/account_update_email", {
        en_email: $('#en_email').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_email').html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_email').html('<i class="fas fa-check-circle"></i> ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_email').html('');
            }, 1597);

        }
    });

}


function account_update_password(){

    //Show spinner:
    $('.save_password').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_echo_platform_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/source/account_update_password", {
        input_password: $('#input_password').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_password').html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_password').html('<i class="fas fa-check-circle"></i> ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_password').html('');
            }, 1597);

        }
    });

}

