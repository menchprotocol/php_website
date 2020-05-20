


//Define file upload variables:
var upload_control = $(".inputfile");
var $input = $('.drag-box').find('input[type="file"]'),
    $label = $('.drag-box').find('label'),
    showFiles = function (files) {
        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
    };

$(document).ready(function () {

    //Source Loader:
    var portfolio_count = parseInt($('#new_portfolio').attr('current-count'));
    if(portfolio_count>0 && portfolio_count<parseInt(js_sources__6404[13005]['m_desc'])){
        en_sort_portfolio_load();
    }

    //Lookout for textinput updates
    view_input_text_update_start();

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

    $('#new_portfolio').focus(function() {
        $('#new_portfolio .pad_expand').removeClass('hidden');
    }).focusout(function() {
        $('#new_portfolio .pad_expand').addClass('hidden');
    });



    //Load search for mass update function:
    load_editor();

    //Keep an eye for icon change:
    $('#source__icon').keyup(function() {
        update_demo_icon();
    });

    //Lookout for idea link related changes:
    $('#read__status').change(function () {
        if (parseInt($('#read__status').find(":selected").val()) == 6173 /* DELETED */ ) {
            //About to delete? Notify them:
            $('.notify_unlink_en').removeClass('hidden');
        } else {
            $('.notify_unlink_en').addClass('hidden');
        }
    });

    $('#source__status').change(function () {

        if (parseInt($('#source__status').find(":selected").val()) == 6178 /* Player Deleted */) {

            //Notify Player:
            $('.notify_en_delete').removeClass('hidden');
            $('.source_delete_stats').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

            //About to delete... Fetch total links:
            $.post("/source/source_count_deletion", { source__id: parseInt($('#modifybox').attr('source-id')) }, function (data) {

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
    en_load_search("#new_portfolio", 0, 'w');


    //Watchout for file uplods:
    $('.drag-box').find('input[type="file"]').change(function () {
        source_upload_file(droppedFiles, 'file');
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
                source_upload_file(droppedFiles, 'drop');
            });
    }

    read_preview_type_load();

});




function en_load_search(element_focus, is_en_parent, shortcut) {

    $(element_focus + ' .add-input').focus(function() {

        $(element_focus + ' .algolia_pad_search' ).removeClass('hidden');

    }).focusout(function() {

        $(element_focus + ' .algolia_pad_search' ).addClass('hidden');

    }).keypress(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            source__add(0, is_en_parent);
            return true;
        }

    });

    if(parseInt(js_sources__6404[12678]['m_desc'])){

            $(element_focus + ' .add-input').on('autocomplete:selected', function (event, suggestion, dataset) {

                source__add(suggestion.object__id, is_en_parent);

            }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [( is_en_parent ? 'q' : 'a' )]}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'object__type=4536',
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
                    //If clicked, would trigger the autocomplete:selected above which will trigger the source__add() function
                    return view_search_result(suggestion);
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:source__add(0,'+is_en_parent+')" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:source__add(0,'+is_en_parent+')" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                },
            }
        }]);
    }
}


function account_toggle_all(is_enabled){
    //Turn all superpowers on/off:
    $(".btn-superpower").each(function () {
        if ((is_enabled && !$(this).hasClass('active')) || (!is_enabled && $(this).hasClass('active'))) {
            account_toggle_superpower(parseInt($(this).attr('en-id')));
        }
    });
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

            alert(data.message);

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
function source__add(en_existing_id, is_parent) {

    //if en_existing_id>0 it means we're linking to an existing source, in which case en_new_string should be null
    //If en_existing_id=0 it means we are creating a new source and then linking it, in which case en_new_string is required

    if (is_parent) {
        var input = $('#new-parent .add-input');
        var list_id = 'list-parent';
    } else {
        var input = $('#new_portfolio .add-input');
        var list_id = 'source__portfolio';
    }

    var en_new_string = null;
    if (en_existing_id == 0) {
        en_new_string = input.val();
        if (en_new_string.length < 1) {
            alert('Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }


    //Add via Ajax:
    $.post("/source/source__add", {

        source__id: en_focus_id,
        en_existing_id: en_existing_id,
        en_new_string: en_new_string,
        is_parent: (is_parent ? 1 : 0),

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);

        if (data.status) {

            //Raw input to make it reads for next URL:
            input.focus();

            //Add new object to list:
            add_to_list(list_id, '.en-item', data.en_new_echo);

            //Allow inline editing if enabled:
            view_input_text_update_start();

            en_sort_portfolio_load();

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert(data.message);
        }

    });
}


function en_filter_status(new_val) {
    //Delete active class:
    $('.en-status-filter').removeClass('active');
    //We do have a filter:
    en_focus_filter = parseInt(new_val);
    $('.en-status-' + new_val).addClass('active');
    source_load_page(0, 1);
}

function source__title_word_count() {
    var len = $('#source__title').val().length;
    if (len > js_sources__6404[6197]['m_desc']) {
        $('#charEnNum').addClass('overload').text(len);
    } else {
        $('#charEnNum').removeClass('overload').text(len);
    }
}



function source_load_page(page, load_new_filter) {

    if (load_new_filter) {
        //Replace load more with spinner:
        var append_div = $('#new_portfolio').html();
        //The padding-bottom would delete the scrolling effect on the left side!
        $('#source__portfolio').html('<span class="load-more" style="padding-bottom:500px;"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    }

    $.post("/source/source_load_page", {
        page: page,
        parent_source__id: en_focus_id,
        en_focus_filter: en_focus_filter,
    }, function (data) {

        //Appending to existing content:
        $('.load-more').remove();

        if (load_new_filter) {
            $('#source__portfolio').html(data + '<div id="new_portfolio" class="list-group-item itemsource grey-input">' + append_div + '</div>').hide().fadeIn();
            //Reset search engine:
            en_load_search("#new_portfolio", 0, 'w');
        } else {
            //Update UI to confirm with user:
            $(data).insertBefore('#new_portfolio');
        }

        view_input_text_update_start();

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}


function update_demo_icon(){
    //Update demo icon based on icon input value:
    $('.icon-demo').html(($('#source__icon').val().length > 0 ? $('#source__icon').val() : js_sources__2738[4536]['m_icon'] ));
}

function en_modify_load(source__id, read__id) {

    //Make sure inputs are valid:
    if (!$('.en___' + source__id).length) {
        alert('Invalid Source ID');
        return false;
    }

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();

    //Update variables:
    $('#modifybox').attr('source-link-id', read__id);
    $('#modifybox').attr('source-id', source__id);

    //Cannot be deleted OR Unpublished as this would not load, so delete them:
    $('.notify_en_delete, .notify_unlink_en').addClass('hidden');

    //Set opacity:
    delete_all_saved();
    $(".saved_en_"+source__id).addClass('en_saved');

    //Might be in an INPUT or a DIV based on active superpowers:
    var en_full_name = $(".text__6197_" + source__id + ":first").val();
    if(!en_full_name.length){
        en_full_name = $(".text__6197_" + source__id + ":first").text();
    }
    $('#source__title').val(en_full_name.toUpperCase()).focus();
    $('.edit-header').html('<i class="fas fa-pen-square"></i> ' + en_full_name);
    $('#source__status').val($(".en___" + source__id + ":first").attr('en-status'));
    $('.save_source_changes').html('');
    $('.source_delete_stats').html('');

    if (parseInt($('.en__icon_' + source__id).attr('en-is-set')) > 0) {
        $('#source__icon').val($('.en__icon_' + source__id).html());
    } else {
        //Clear out input:
        $('#source__icon').val('');
    }

    source__title_word_count();
    update_demo_icon();

    //Only show unlink button if not level 1
    if (parseInt(read__id) > 0) {

        $('#read__status').val($(".en___" + source__id + ":first").attr('ln-status'));
        $('#en_link_count').val('0');


        //Make the UI link and the ideas in the edit box:
        $('.unlink-source, .en-has-tr').removeClass('hidden');

        //Assign value:
        $('#read__message').val($(".read__message_val_" + read__id + ":first").text());

        //Also update type:
        read_preview_type();

    } else {

        //Hide the section and clear it:
        $('.unlink-source, .en-has-tr').addClass('hidden');

    }
}

function source_link_form_lock(){
    $('#read__message').prop("disabled", true).css('background-color','#999999');

    $('.btn-save').addClass('grey').attr('href', '#').html('<span class="icon-block">i class="far fa-yin-yang fa-spin"></i></span>Uploading');

}

function source_link_form_unlock(result){

    //What was the result?
    if (!result.status) {
        alert(result.message);
    }

    //Unlock either way:
    $('#read__message').prop("disabled", false).css('background-color','#FFF');

    $('.btn-save').removeClass('grey').attr('href', 'javascript:source_update();').html('Save');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();

    //Replace the upload form to reset:
    upload_control.replaceWith( upload_control = upload_control.clone( true ) );
}


function source_upload_file(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.drag-box').hasClass('is-uploading')) {
        return false;
    }

    var current_value = $('#read__message').val();
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
            url: '/source/source_upload_file',
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
                    $('#read__message').val( data.cdn_url );

                    //Also update type:
                    read_preview_type();
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


function source_sort_save() {

    var new_read__sorts = [];
    var sort_rank = 0;

    $("#source__portfolio .en-item").each(function () {
        //Fetch variables for this idea:
        var source__id = parseInt($(this).attr('source-id'));
        var read__id = parseInt($(this).attr('read__id'));

        sort_rank++;

        //Store in DB:
        new_read__sorts[sort_rank] = read__id;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0) {
        //Update backend:
        $.post("/source/source_sort_save", {source__id: en_focus_id, new_read__sorts: new_read__sorts}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}

function source_sort_reset(){

    $('.sort_reset').html('<i class="far fa-yin-yang fa-spin"></i>');

    //Update via call:
    $.post("/source/source_sort_reset", {
        source__id: en_focus_id
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            alert(data.message);

        } else {

            //Refresh page:
            window.location = '/source/' + en_focus_id;

        }
    });

}

function en_sort_portfolio_load() {

    var element_key = null;
    var theobject = document.getElementById("source__portfolio");
    if (!theobject) {
        //due to duplicate ideas belonging in this idea:
        return false;
    }

    //Show sort icon:
    $('.fa-bars, .sort_reset').removeClass('hidden');

    var sort = Sortable.create(theobject, {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".en-item", // Specifies which items inside the element should be sortable
        handle: ".fa-bars", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            source_sort_save();
        }
    });
}

function source_update() {

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
        source__id: parseInt($('#modifybox').attr('source-id')),
        source__title: $('#source__title').val().toUpperCase(),
        source__icon: $('#source__icon').val(),
        source__status: $('#source__status').val(), //The new status (might not have changed too)
        en_merge: $('#en_merge').val(),
        //Link data:
        read__id: parseInt($('#modifybox').attr('source-link-id')),
        read__message: $('#read__message').val(),
        read__status: $('#read__status').val(),
    };

    //Show spinner:
    $('.save_source_changes').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_platform_message(12695) +  '').hide().fadeIn();


    $.post("/source/source_update", modify_data, function (data) {

        if (data.status) {

            if(data.delete_from_ui){

                //need to delete this source:
                //Idea has been either deleted OR Unpublished:
                if (data.delete_redirect_url) {

                    //move up 1 level as this was the focus idea:
                    window.location = data.delete_redirect_url;

                } else {

                    //Reset opacity:
                    delete_all_saved();

                    //Delete from UI:
                    $('.tr_' + modify_data['read__id']).html('<span><span class="icon-block"><i class="fas fa-trash-alt"></i></span>Deleted</span>').fadeOut();

                    //Disappear in a while:
                    setTimeout(function () {

                        //Hide the editor & saving results:
                        $('.tr_' + modify_data['read__id']).remove();

                        //Hide editing box:
                        $('#modifybox').addClass('hidden');

                    }, 610);

                }

            } else {

                //Reflect changed:
                //Might be in an INPUT or a DIV based on active superpowers:
                update_text_name(6197, modify_data['source__id'], modify_data['source__title']);


                //Player Status:
                $(".en___" + modify_data['source__id']).attr('en-status', modify_data['source__status']);
                $('.source__status_' + modify_data['source__id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_sources__6177[modify_data['source__status']]["m_name"] + ': ' + js_sources__6177[modify_data['source__status']]["m_desc"] + '">' + js_sources__6177[modify_data['source__status']]["m_icon"] + '</span>');


                //Player Icon:
                var icon_is_set = ( modify_data['source__icon'].length > 0 ? 1 : 0 );
                if(!icon_is_set){
                    //Set source default icon:
                    modify_data['source__icon'] = js_sources__2738[4536]['m_icon'];
                }
                $('.en__icon_' + modify_data['source__id']).attr('en-is-set' , icon_is_set );
                $('.en_ui_icon_' + modify_data['source__id']).html(modify_data['source__icon']);
                $('.en_child_icon_' + modify_data['source__id']).html(modify_data['source__icon']);


                //Did we have ideas to update?
                if (modify_data['read__id'] > 0) {

                    //Yes, update the ideas:
                    $(".read__message_" + modify_data['read__id']).html(data.read__message);
                    $(".read__message_val_" + modify_data['read__id']).text(data.read__message_final);

                    //Did the content get modified? (Likely for a domain URL):
                    if(!(data.read__message_final==modify_data['read__message'])){
                        $("#read__message").val(data.read__message_final).hide().fadeIn('slow');
                    }


                    //Link Icon:
                    $('.read_type_' + modify_data['read__id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_sources__4592[data.js_read__type]["m_name"] + ': ' + js_sources__4592[data.js_read__type]["m_desc"] + '">' + js_sources__4592[data.js_read__type]["m_icon"] + '</span>');

                    //Read Status:
                    $(".en___" + modify_data['source__id']).attr('ln-status', modify_data['read__status'])
                    $('.read__status_' + modify_data['read__id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_sources__6186[modify_data['read__status']]["m_name"] + ': ' + js_sources__6186[modify_data['read__status']]["m_desc"] + '">' + js_sources__6186[modify_data['read__status']]["m_icon"] + '</span>');

                }

                //Update source timestamp:
                $('.save_source_changes').html(data.message);

                //Reload Tooltip again:
                $('[data-toggle="tooltip"]').tooltip();
            }

        } else {
            //Ooops there was an error!
            $('.save_source_changes').html('<span class="read montserrat"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</span>').hide().fadeIn();
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
            alert(data.message);

        } else {

            //Delete message:
            $('.en_ui_icon_'+js_pl_id).html(data.new_avatar);

        }
    });

}


function account_update_radio(parent_source__id, selected_source__id, enable_mulitiselect){

    var was_previously_selected = ( $('.radio-'+parent_source__id+' .item-'+selected_source__id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_previously_selected){
        //Nothing to do here:
        return false;
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+parent_source__id+' .item-'+selected_source__id+' .change-results';
    $(notify_el).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+parent_source__id+' .list-group-item').removeClass('active');
    }

    //Enable currently selected:
    if(enable_mulitiselect && was_previously_selected){
        $('.radio-'+parent_source__id+' .item-'+selected_source__id).removeClass('active');
    } else {
        $('.radio-'+parent_source__id+' .item-'+selected_source__id).addClass('active');
    }

    $.post("/source/account_update_radio", {
        parent_source__id: parent_source__id,
        selected_source__id: selected_source__id,
        enable_mulitiselect: enable_mulitiselect,
        was_previously_selected: was_previously_selected,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $(notify_el).html('<b class="read montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>');

        } else {

            //Delete message:
            $(notify_el).html('');

        }
    });


}


function account_update_email(){

    //Show spinner:
    $('.save_email').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_platform_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/source/account_update_email", {
        en_email: $('#en_email').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_email').html('<b class="read montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

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
    $('.save_password').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_platform_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/source/account_update_password", {
        input_password: $('#input_password').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_password').html('<b class="read montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

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

