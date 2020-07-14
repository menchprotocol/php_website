


//Define file upload variables:
var upload_control = $(".inputfile");
var $input = $('.drag-box').find('input[type="file"]'),
    $label = $('.drag-box').find('label'),
    showFiles = function (files) {
        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
    };

$(document).ready(function () {

    //Source Loader:
    var portfolio_count = BigInt($('#new_portfolio').attr('current-count'));
    if(portfolio_count>0 && portfolio_count<BigInt(js_e___6404[13005]['m_desc'])){
        e_sort_portfolio_load();
    }

    //Lookout for textinput updates
    x_set_text_start();

    //Setup auto focus:
    $('#openEn6197').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#e_setting_name').val();
        setTimeout(function() { $('#e_setting_name').focus().val('').val(original_val); }, 144);
    });

    $('#openEn3288').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#e_email').val();
        setTimeout(function() { $('#e_email').focus().val('').val(original_val); }, 144);
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
    $('#e__icon').keyup(function() {
        update_demo_icon();
    });

    //Lookout for idea transaction related changes:
    $('#x__status').change(function () {
        if (BigInt($('#x__status').find(":selected").val()) == 6173 /* DELETED */ ) {
            //About to delete? Notify them:
            $('.notify_unx_e').removeClass('hidden');
        } else {
            $('.notify_unx_e').addClass('hidden');
        }
    });

    $('#e__status').change(function () {

        if (BigInt($('#e__status').find(":selected").val()) == 6178 /* Miner Deleted */) {

            //Notify Miner:
            $('.notify_e_delete').removeClass('hidden');
            $('.e_delete_stats').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

            //About to delete... Fetch total transactions:
            $.post("/e/e_count_deletion", { e__id: BigInt($('#modifybox').attr('e-id')) }, function (data) {

                if(data.status){
                    $('.e_delete_stats').html('<b>'+data.e_x_count+'</b>');
                    $('#e_x_count').val(data.e_x_count); //This would require a confirmation upon saving...
                }

            });

        } else {

            $('.notify_e_delete').addClass('hidden');
            $('.e_delete_stats').html('');
            $('#e_x_count').val('0');

        }
    });

    //Adjust height of the messaging windows:
    $('.grey-box').css('max-height', (BigInt($(window).height()) - 130) + 'px');

    //Make editing frames Sticky for scrolling longer lists
    $(".main-panel").scroll(function () {
        var top_position = $(this).scrollTop();
        clearTimeout($.data(this, 'scrollTimer'));
        $.data(this, 'scrollTimer', setTimeout(function () {
            $(".fixed-box").css('top', (top_position - 0)); //PX also set in style.css for initial load
        }, 34));
    });


    //Loadup various search bars:
    e_load_search("#new-parent", 1, 'q');
    e_load_search("#new_portfolio", 0, 'w');


    //Watchout for file uplods:
    $('.drag-box').find('input[type="file"]').change(function () {
        e_upload_file(droppedFiles, 'file');
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
                e_upload_file(droppedFiles, 'drop');
            });
    }

    x_type_preview_load();

});




function e_load_search(element_focus, is_e_parent, shortcut) {

    $(element_focus + ' .add-input').focus(function() {

        $(element_focus + ' .algolia_pad_search' ).removeClass('hidden');

    }).focusout(function() {

        $(element_focus + ' .algolia_pad_search' ).addClass('hidden');

    }).keypress(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            e__add(0, is_e_parent);
            return true;
        }

    });

    if(BigInt(js_e___6404[12678]['m_desc'])){

            $(element_focus + ' .add-input').on('autocomplete:selected', function (event, suggestion, dataset) {

                e__add(suggestion.object__id, is_e_parent);

            }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [( is_e_parent ? 'q' : 'a' )]}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'object__type=12274',
                    hitsPerPage: 21,
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
                    //If clicked, would trigger the autocomplete:selected above which will trigger the e__add() function
                    return view_search_result(suggestion);
                },
                header: function (data) {
                    if (!data.isEmpty) {
                        return '<a href="javascript:e__add(0,'+is_e_parent+')" class="suggestion montserrat"><span class="icon-block"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:e__add(0,'+is_e_parent+')" class="suggestion montserrat"><span class="icon-block"><i class="fas fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                },
            }
        }]);
    }
}


function account_toggle_all(is_enabled){
    //Turn all superpowers on/off:
    $(".btn-superpower").each(function () {
        if ((is_enabled && !$(this).hasClass('active')) || (!is_enabled && $(this).hasClass('active'))) {
            e_toggle_superpower(BigInt($(this).attr('en-id')));
        }
    });
}



function e_toggle_superpower(superpower_id){

    superpower_id = BigInt(superpower_id);

    var superpower_icon = $('.superpower-frame-'+superpower_id).html();
    $('.superpower-frame-'+superpower_id).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Save session variable to save the state of advance setting:
    $.post("/e/e_toggle_superpower/"+superpower_id, {}, function (data) {

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



//Adds OR transactions sources to sources
function e__add(e_existing_id, is_parent) {

    //if e_existing_id>0 it means we're adding an existing source, in which case e_new_string should be null
    //If e_existing_id=0 it means we are creating a new source and then adding it, in which case e_new_string is required

    if (is_parent) {
        var input = $('#new-parent .add-input');
        var list_id = 'list-parent';
    } else {
        var input = $('#new_portfolio .add-input');
        var list_id = 'e__portfolio';
    }

    var e_new_string = null;
    if (e_existing_id == 0) {
        e_new_string = input.val();
        if (e_new_string.length < 1) {
            alert('Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }


    //Add via Ajax:
    $.post("/e/e__add", {

        e__id: e_focus_id,
        e_existing_id: e_existing_id,
        e_new_string: e_new_string,
        is_parent: (is_parent ? 1 : 0),

    }, function (data) {

        //Release lock:
        input.prop('disabled', false);

        if (data.status) {

            //Raw input to make it discovers for next URL:
            input.focus();

            //Add new object to list:
            add_to_list(list_id, '.en-item', data.e_new_echo);

            //Allow inline editing if enabled:
            x_set_text_start();

            e_sort_portfolio_load();

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //We had an error:
            alert(data.message);
        }

    });
}


function e_filter_status(new_val) {
    //Delete active class:
    $('.en-status-filter').removeClass('active');
    //We do have a filter:
    e_focus_filter = BigInt(new_val);
    $('.en-status-' + new_val).addClass('active');
    e_load_page(0, 1);
}

function e__title_word_count() {
    var len = $('#e__title').val().length;
    if (len > js_e___6404[6197]['m_desc']) {
        $('#charEnNum').addClass('overload').text(len);
    } else {
        $('#charEnNum').removeClass('overload').text(len);
    }
}



function e_load_page(page, load_new_filter) {

    if (load_new_filter) {
        //Replace load more with spinner:
        var append_div = $('#new_portfolio').html();
        //The padding-bottom would delete the scrolling effect on the left side!
        $('#e__portfolio').html('<span class="load-more" style="padding-bottom:500px;"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    }

    $.post("/e/e_load_page", {
        page: page,
        parent_e__id: e_focus_id,
        e_focus_filter: e_focus_filter,
    }, function (data) {

        //Appending to existing content:
        $('.load-more').remove();

        if (load_new_filter) {
            $('#e__portfolio').html(data + '<div id="new_portfolio" class="list-group-item no-side-padding itemsource grey-input">' + append_div + '</div>').hide().fadeIn();
            //Reset search engine:
            e_load_search("#new_portfolio", 0, 'w');
        } else {
            //Update UI to confirm with miner:
            $(data).insertBefore('#new_portfolio');
        }

        x_set_text_start();

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}


function update_demo_icon(){
    //Update demo icon based on icon input value:
    $('.icon-demo').html(($('#e__icon').val().length > 0 ? $('#e__icon').val() : js_e___12467[12274]['m_icon'] ));
}

function e_modify_load(e__id, x__id) {

    //Make sure inputs are valid:
    if (!$('.e__id_' + e__id).length) {
        alert('Invalid Source ID');
        return false;
    }

    //Make the frame visible:
    $('.fixed-box').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();

    //Update variables:
    $('#modifybox').attr('e-x-id', x__id);
    $('#modifybox').attr('e-id', e__id);

    //Cannot be deleted OR Unpublished as this would not load, so delete them:
    $('.notify_e_delete, .notify_unx_e').addClass('hidden');

    //Set opacity:
    delete_all_saved();
    $(".saved_e_"+e__id).addClass('e_saved');

    //Might be in an INPUT or a DIV based on active superpowers:
    var e_full_name = $(".text__6197_" + e__id + ":first").val();
    if(!e_full_name.length){
        e_full_name = $(".text__6197_" + e__id + ":first").text();
    }
    $('#e__title').val(e_full_name.toUpperCase()).focus();
    $('.edit-header').html('<i class="fas fa-pen-square"></i> ' + e_full_name);
    $('#e__status').val($(".e__id_" + e__id + ":first").attr('en-status'));
    $('.save_e_changes').html('');
    $('.e_delete_stats').html('');

    if (BigInt($('.e__icon_' + e__id).attr('en-is-set')) > 0) {
        $('#e__icon').val($('.e__icon_' + e__id).html());
    } else {
        //Clear out input:
        $('#e__icon').val('');
    }

    e__title_word_count();
    update_demo_icon();

    //Only show remove button if not level 1
    if (BigInt(x__id) > 0) {

        $('#x__status').val($(".e__id_" + e__id + ":first").attr('x-status'));
        $('#e_x_count').val('0');


        //Make the UI transaction and the ideas in the edit box:
        $('.remove-e, .en-has-tr').removeClass('hidden');

        //Assign value:
        $('#x__message').val($(".x__message_val_" + x__id + ":first").text());

        //Also update type:
        x_type_preview();

    } else {

        //Hide the section and clear it:
        $('.remove-e, .en-has-tr').addClass('hidden');

    }
}

function e_x_form_lock(){
    $('#x__message').prop("disabled", true).css('background-color','#999999');

    $('.btn-save').addClass('grey').attr('href', '#').html('<span class="icon-block">i class="far fa-yin-yang fa-spin"></i></span>Uploading');

}

function e_x_form_unlock(result){

    //What was the result?
    if (!result.status) {
        alert(result.message);
    }

    //Unlock either way:
    $('#x__message').prop("disabled", false).css('background-color','#FFF');

    $('.btn-save').removeClass('grey').attr('href', 'javascript:e_update();').html('Save');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();

    //Replace the upload form to reset:
    upload_control.replaceWith( upload_control = upload_control.clone( true ) );
}


function e_upload_file(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.drag-box').hasClass('is-uploading')) {
        return false;
    }

    var current_value = $('#x__message').val();
    if(current_value.length > 0){
        //There is something in the input field, notify the miner:
        var r = confirm("Current transaction content [" + current_value + "] will be deleted. Continue?");
        if (r == false) {
            return false;
        }
    }


    if (isAdvancedUpload) {

        //Lock message:
        e_x_form_lock();

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
            url: '/e/e_upload_file',
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
                    $('#x__message').val( data.cdn_url );

                    //Also update type:
                    x_type_preview();
                }

                //Unlock form:
                e_x_form_unlock(data);

            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                e_x_form_unlock(result);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}


function e_sort_save() {

    var new_x__sorts = [];
    var sort_rank = 0;

    $("#e__portfolio .en-item").each(function () {
        //Fetch variables for this idea:
        var e__id = BigInt($(this).attr('e-id'));
        var x__id = BigInt($(this).attr('x__id'));

        sort_rank++;

        //Store in DB:
        new_x__sorts[sort_rank] = x__id;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0) {
        //Update backend:
        $.post("/e/e_sort_save", {e__id: e_focus_id, new_x__sorts: new_x__sorts}, function (data) {
            //Update UI to confirm with miner:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}

function e_sort_reset(){
    var r = confirm("Reset all Portfolio Source orders & sort alphabetically?");
    if (r == true) {
        $('.sort_reset').html('<i class="far fa-yin-yang fa-spin"></i>');

        //Update via call:
        $.post("/e/e_sort_reset", {
            e__id: e_focus_id
        }, function (data) {

            if (!data.status) {

                //Ooops there was an error!
                alert(data.message);

            } else {

                //Refresh page:
                window.location = '/@' + e_focus_id;

            }
        });
    }
}

function e_sort_portfolio_load() {

    var element_key = null;
    var theobject = document.getElementById("e__portfolio");
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
            e_sort_save();
        }
    });
}

function e_update() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !BigInt($('#modifybox').attr('e-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Are we about to delete an source with a lot of transactions?
    var x_count= BigInt($('#e_x_count').val());
    if(x_count >= 3){
        //Yes, confirm before doing so:
        var confirm_removal = prompt("Delete source & "+( $('#e_merge').val().length > 0 ? 'merge' : 'remove' )+" "+x_count+" transactions?! Type \"delete\" to confirm.", "");

        if (!(confirm_removal == 'delete')) {
            //Abandon process:
            alert('Source will not be deleted.');
            return false;
        }
    }

    //Prepare data to be modified for this idea:
    var modify_data = {
        e_focus_id: e_focus_id, //Determines if we need to change location upon removing...
        e__id: BigInt($('#modifybox').attr('e-id')),
        e__title: $('#e__title').val().toUpperCase(),
        e__icon: $('#e__icon').val(),
        e__status: $('#e__status').val(), //The new status (might not have changed too)
        e_merge: $('#e_merge').val(),
        //Transaction data:
        x__id: BigInt($('#modifybox').attr('e-x-id')),
        x__message: $('#x__message').val(),
        x__status: $('#x__status').val(),
    };

    //Show spinner:
    $('.save_e_changes').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_platform_message(12695) +  '').hide().fadeIn();


    $.post("/e/e_update", modify_data, function (data) {

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
                    $('.tr_' + modify_data['x__id']).html('<span><span class="icon-block"><i class="fas fa-trash-alt"></i></span>Deleted</span>').fadeOut();

                    //Disappear in a while:
                    setTimeout(function () {

                        //Hide the editor & saving results:
                        $('.tr_' + modify_data['x__id']).remove();

                        //Hide editing box:
                        $('#modifybox').addClass('hidden');

                    }, 610);

                }

            } else {

                //Reflect changed:
                //Might be in an INPUT or a DIV based on active superpowers:
                update_text_name(6197, modify_data['e__id'], modify_data['e__title']);


                //Miner Status:
                $(".e__id_" + modify_data['e__id']).attr('en-status', modify_data['e__status']);
                $('.e__status_' + modify_data['e__id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_e___6177[modify_data['e__status']]["m_name"] + ': ' + js_e___6177[modify_data['e__status']]["m_desc"] + '">' + js_e___6177[modify_data['e__status']]["m_icon"] + '</span>');


                //Miner Icon:
                var icon_is_set = ( modify_data['e__icon'].length > 0 ? 1 : 0 );
                if(!icon_is_set){
                    //Set source default icon:
                    modify_data['e__icon'] = js_e___12467[12274]['m_icon'];
                }
                $('.e__icon_' + modify_data['e__id']).attr('en-is-set' , icon_is_set );
                $('.e_ui_icon_' + modify_data['e__id']).html(modify_data['e__icon']);
                $('.e_child_icon_' + modify_data['e__id']).html(modify_data['e__icon']);


                //Did we have ideas to update?
                if (modify_data['x__id'] > 0) {

                    //Yes, update the ideas:
                    $(".x__message_" + modify_data['x__id']).html(data.x__message);
                    $(".x__message_val_" + modify_data['x__id']).text(data.x__message_final);

                    //Did the content get modified? (Likely for a domain URL):
                    if(!(data.x__message_final==modify_data['x__message'])){
                        $("#x__message").val(data.x__message_final).hide().fadeIn('slow');
                    }

                    //Transaction Status:
                    $(".e__id_" + modify_data['e__id']).attr('x-status', modify_data['x__status'])
                    $('.x__status_' + modify_data['x__id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_e___6186[modify_data['x__status']]["m_name"] + ': ' + js_e___6186[modify_data['x__status']]["m_desc"] + '">' + js_e___6186[modify_data['x__status']]["m_icon"] + '</span>');

                }

                //Update source timestamp:
                $('.save_e_changes').html(data.message);

                //Reload Tooltip again:
                $('[data-toggle="tooltip"]').tooltip();
            }

        } else {
            //Ooops there was an error!
            $('.save_e_changes').html('<span class="discover montserrat"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</span>').hide().fadeIn();
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
    e_update_avatar(type_css, null);

}

function e_update_avatar(type_css, icon_css){

    //Detect current icon type:
    if(!icon_css){
        icon_css = $('.avatar-item.active').attr('icon-css');
    } else {
        //Set Proper Focus:
        $('.avatar-item').removeClass('active');
        $('.avatar-item.avatar-name-'+icon_css).addClass('active');
    }

    $('.e_ui_icon_'+js_pl_id).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Update via call:
    $.post("/e/e_update_avatar", {
        type_css: type_css,
        icon_css: icon_css,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            alert(data.message);

        } else {

            //Delete message:
            $('.e_ui_icon_'+js_pl_id).html(data.new_avatar);

        }
    });

}


function e_update_radio(parent_e__id, selected_e__id, enable_mulitiselect){

    var was_previously_selected = ( $('.radio-'+parent_e__id+' .item-'+selected_e__id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_previously_selected){
        //Nothing to do here:
        return false;
    }

    //Updating Font?
    if(parent_e__id==13491){
        html_13491(selected_e__id);
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+parent_e__id+' .item-'+selected_e__id+' .change-results';
    $(notify_el).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+parent_e__id+' .list-group-item').removeClass('active');
    }

    //Enable currently selected:
    if(enable_mulitiselect && was_previously_selected){
        $('.radio-'+parent_e__id+' .item-'+selected_e__id).removeClass('active');
    } else {
        $('.radio-'+parent_e__id+' .item-'+selected_e__id).addClass('active');
    }

    $.post("/e/e_update_radio", {
        parent_e__id: parent_e__id,
        selected_e__id: selected_e__id,
        enable_mulitiselect: enable_mulitiselect,
        was_previously_selected: was_previously_selected,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $(notify_el).html('<b class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>');

        } else {

            //Delete message:
            $(notify_el).html('');

        }
    });


}


function e_update_email(){

    //Show spinner:
    $('.save_email').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_platform_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_update_email", {
        e_email: $('#e_email').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_email').html('<b class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

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


function e_update_password(){

    //Show spinner:
    $('.save_password').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_platform_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_update_password", {
        input_password: $('#input_password').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_password').html('<b class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

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

