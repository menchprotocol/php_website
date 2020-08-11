


//Define file upload variables:
var upload_control = $(".inputfile");
var $input = $('.drag-box').find('input[type="file"]'),
    $label = $('.drag-box').find('label'),
    showFiles = function (files) {
        $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
    };

$(document).ready(function () {

    //Listen for keystroke events
    $( "#input__13433" ).change(function() {
        e_13428();
    });

    //Load Idea Search
    i_load_search("#newIdeaTitle",0, 'a', 'x_my_in');

    if(e_focus_id==js_pl_id){

        //User loaded their own source:
        //Watch for Discovery removal click:
        $('.x_remove').on('click', function(e) {

            var i__id = $(this).attr('i__id');
            var x__type = parseInt($(this).attr('x__type'));
            var r = confirm("Unlink "+$('.text__4736_'+i__id+':first').text()+"?");
            if (r == true) {
                //Save changes:
                $.post("/x/x_remove", { x__type:x__type, i__id:i__id }, function (data) {
                    //Update UI to confirm with user:
                    if (!data.status) {

                        //There was some sort of an error returned!
                        alert(data.message);

                    } else {

                        //REMOVE BOOKMARK from UI:
                        $('.i_class_'+x__type+'_'+i__id).fadeOut();

                        setTimeout(function () {

                            //Delete from body:
                            $('.i_class_'+x__type+'_'+i__id).remove();

                        }, 233);
                    }
                });
            }

            return false;

        });
    }

    //Source Loader:
    var portfolio_count = parseInt($('#new_11029').attr('current-count'));
    if(portfolio_count>0 && portfolio_count<parseInt(js_e___6404[13005]['m_message'])){
        e_sort_portfolio_load();
    }

    autosize($('.texttype__lg.text__6197_'+e_focus_id));

    $("#input__6197, #e__title").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
        }
    }).click(function(event) {
        event.preventDefault();
    });

    //Update Profile counters to account for sources that user may not be able to see due to missing permissions...
    $('.en-type-counter-11030').text($('#list_11030 .en-item').not(".hidden").length);


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



    $('#new_11030').focus(function() {
        $('#new_11030 .pad_expand').removeClass('hidden');
    }).focusout(function() {
        $('#new_11030 .pad_expand').addClass('hidden');
    });

    $('#new_11029').focus(function() {
        $('#new_11029 .pad_expand').removeClass('hidden');
    }).focusout(function() {
        $('#new_11029 .pad_expand').addClass('hidden');
    });



    //Load search for mass update function:
    load_editor();

    //Keep an eye for icon change:
    $('#e__icon').keyup(function() {
        update_demo_icon();
    });

    //Lookout for idea transaction related changes:
    $('#x__status').change(function () {
        if (parseInt($('#x__status').find(":selected").val()) == 6173 /* DELETED */ ) {
            //About to delete? Notify them:
            $('.notify_unx_e').removeClass('hidden');
        } else {
            $('.notify_unx_e').addClass('hidden');
        }
    });

    $('#e__status').change(function () {

        if (parseInt($('#e__status').find(":selected").val()) == 6178 /* User Deleted */) {

            //Notify User:
            $('.notify_e_delete').removeClass('hidden');
            $('.e_delete_stats').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

            //About to delete... Fetch total transactions:
            $.post("/e/e_count_deletion", { e__id: parseInt($('#modal13571 .modal_e__id').val()) }, function (data) {

                if(data.status){
                    $('.e_delete_stats').html('<b>'+data.e_x_count+'</b>');
                    $('#e_x_count').val(data.e_x_count); //This would require a confirmation upon saving...
                }

            });

        } else {

            $('.notify_e_delete').addClass('hidden');
            $('.e_delete_stats').html('');
            $('#e_x_count').val(0);

        }
    });


    //SEARCH
    e_load_search("#new_11030", 1, 'q');
    e_load_search("#new_11029", 0, 'w');


    //UPLOAD
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
                $('.e_has_link').addClass('is-working');
            })
            .on('dragleave dragend drop', function () {
                $('.e_has_link').removeClass('is-working');
            })
            .on('drop', function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                e.preventDefault();
                e_upload_file(droppedFiles, 'drop');
            });

    }

    x_type_preview_load();

});



function reset_6415(){

    $('.reset_6415').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">REMOVING ALL...</b>');

    //Redirect:
    window.location = '/x/x_clear_coins';

}



function e_13428(){

    if(!$('#input__13433').val().length){
        return false;
    }

    //Whe the URL is changed this tries to update the title & nonfiction source type

    $('#modal13428 .save_results').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');
    $('#input__6197').val('LOADING...');
    $("#input__3000").val(0);

    //Fetch Idea Data to load modify widget:
    $.post("/e/e_13428", {
        input__13433: $('#input__13433').val(),
        e__id: $('#modal13428 .modal_e__id').val(),
    }, function (data) {

        //Update Error Section
        $("#modal13428 .save_results").html((data.status ? '' : '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>'+data.message+'</div>'));

        $('#input__6197').val(data.input__6197);
        $("#input__3000").val(data.input__3000);

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();

    });

}

var is_adding = false;
function save_13428(){

    if(is_adding){
        $('#save_btn').hide().fadeIn();
        return false;
    }

    //Show Modal:
    $('#modal13428 .save_results').html('');
    $('#save_btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');
    is_adding = true;
    var is_editing = parseInt($('#modal13428 .modal_e__id').val()) > 0;
    if(is_editing){
        $( ".e__id_"+$('#modal13428 .modal_e__id').val() ).after('<div class="list-group-item update-new"><i class="far fa-yin-yang fa-spin"></i></div>');
        setTimeout(function () {
            $( ".e__id_"+$('#modal13428 .modal_e__id').val() ).remove();
        }, 21);
    }

    //Load current Source:
    $.post("/e/save_13428", {

        e__id: $('#modal13428 .modal_e__id').val(),
        x__id: $('#modal13428 .modal_x__id').val(),
        input__13433: $('#input__13433').val(),
        input__6197: $('#input__6197').val().toUpperCase(),
        input__3000: $('#input__3000 option:selected').val(),

    }, function (data) {

        is_adding = false;
        $('#save_btn').html('SAVE');
        $("#modal13428 .save_results").html((data.status ? '' : '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>'+data.message+'</div>'));

        if (data.status) {

            $('#modal13428').modal('hide');

            if( is_editing && parseInt($('#modal13428 .modal_e__id').val()) != e_focus_id ){

                //Editing Update the view:
                $( ".update-new" ).after(data.e_new_echo);
                setTimeout(function () {
                    $( ".update-new" ).remove();
                }, 21);

            } else {

                //Add New Source:
                add_to_list('list_e', '.en-item', data.e_new_echo);

            }

        }
    });
}




//Load Nonfiction Source Wizard:
function load_13428(e__id, new_string){

    //Reset Values:
    $("#load_13428 .save_results").html('');
    $('#modal13428 .modal_e__id').val(e__id);
    $('#modal13428 .modal_x__id').val(0);
    $('#input__13433').val(''); //URL
    $('#input__6197').val(''); //TITLE
    $('#input__3000').val(0); //TITLE
    $('#modal13428').modal('show');

    //Load Data:
    if(e__id > 0){

        //Load current Source:
        $.post("/e/load_13428", {

            e__id: e__id,

        }, function (data) {

            if (data.status) {

                $('#modal13428 .modal_x__id').val(data.modal_x__id);
                $('#input__13433').val(data.input__13433);
                $('#input__6197').val(data.input__6197);
                $('#input__3000').val(data.input__3000);

            } else {

                $("#modal13428 .save_results").html('<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>'+data.message+'</div>');

            }

        });

    } else if(new_string.length > 0) {

        //Load New Source
        if(validURL(new_string)) {

            $('#input__13433').val(new_string);

            //Try to fetch more data:
            e_13428();

        } else {

            //If not URL then it's Title:
            $('#input__6197').val(new_string);

        }
    }

    //Adjust text input size
    autosize($('#input__6197'));

}


var saving_i = false;
function i_create(){

    if(saving_i){
        alert('Idea currently being saved, Be patient...');
        return false;
    } else {
        saving_i = true;
    }

    //Lockdown:
    $('#newIdeaTitle').prop('disabled', true);
    $('#tempLoader').remove();

    //Set processing status:
    add_to_list('myIdeas', '.itemidealist', '<div id="tempLoader" class="list-group-item no-side-padding montserrat"><span class="icon-block"><i class="fas fa-yin-yang fa-spin idea"></i></span>Saving Idea...</div>');

    //Process this:
    $.post("/i/i_create", {

        e_focus_id: e_focus_id,
        newIdeaTitle: $('#newIdeaTitle').val(),

    }, function (data) {
        if (data.status) {

            //Redirect:
            $('#tempLoader').html(data.message);
            window.location = '/~' + data.i__id;

        } else {

            //Unlock:
            $('#tempLoader').html('<span class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</span>');
            $('#newIdeaTitle').prop('disabled', false).focus();

        }

        saving_i = false;
    });

}


function e_load_search(element_focus, is_e_parent, shortcut) {

    //Load Search:
    $(element_focus + ' .add-input').focus(function() {

        $(element_focus + ' .algolia_pad_search' ).removeClass('hidden');

    }).focusout(function() {

        $(element_focus + ' .algolia_pad_search' ).addClass('hidden');

    }).keypress(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            if(validURL($(this).val()) || !js_session_superpowers_assigned.includes(13422)){
                load_13428(0, $(this).val());
            } else {
                e__add(0, is_e_parent);
            }
            return true;
        }

    });

    if(parseInt(js_e___6404[12678]['m_message'])){

        $(element_focus + ' .add-input').on('autocomplete:selected', function (event, suggestion, dataset) {

            e__add(suggestion.object__id, is_e_parent);

        }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [( is_e_parent ? 'q' : 'a' )]}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'object__type=12274' + ( js_session_superpowers_assigned.includes(13422) ? '' : ' AND ( _tags:is_nonfiction ) ' ),
                    hitsPerPage: ( validURL(q) ? 1 : 21 ),
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
                    return view_search_result(suggestion);
                },
                header: function (data) {
                    if (!data.isEmpty) {

                        return (js_session_superpowers_assigned.includes(13422) ? '<a href="javascript:e__add(0,'+is_e_parent+')" class="suggestion">' + '<span class="icon-block"><i class="far fa-plus-circle add-plus source"></i></span>' + '<b class="source montserrat">' + data.query.toUpperCase() + '</b>' + '</a>' : '') + '<a href="javascript:load_13428(0, \''+data.query+'\')" class="suggestion">' + '<span class="icon-block">'+js_e___11035[13428]['m_icon']+'</span><b class="source montserrat">LAUNCH ' + js_e___11035[13428]['m_title'] + ':</b></a>';

                    }
                },
                empty: function (data) {
                    return '<a href="javascript:e__add(0,'+is_e_parent+')" class="suggestion montserrat"><span class="icon-block"><i class="far fa-plus-circle add-plus source"></i></span><b class="source">' + data.query.toUpperCase() + '</b></a>';
                },
            }
        }]);
    }
}


function account_toggle_all(is_enabled){
    //Turn all superpowers on/off:
    $(".btn-superpower").each(function () {
        if ((is_enabled && !$(this).hasClass('active')) || (!is_enabled && $(this).hasClass('active'))) {
            e_toggle_superpower(parseInt($(this).attr('en-id')));
        }
    });
}



function e_toggle_superpower(superpower_id){

    superpower_id = parseInt(superpower_id);

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
        var input = $('#new_11030 .add-input');
        var list_id = 'list_11030';
    } else {
        var input = $('#new_11029 .add-input');
        var list_id = 'list_e';
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
    $('.e_filter_status').removeClass('active');
    //We do have a filter:
    e_focus_filter = parseInt(new_val);
    $('.en_status_' + new_val).addClass('active');
    e_load_page(0, 1);
}

function e__title_word_count() {
    var len = $('#e__title').val().length;
    if (len > js_e___6404[6197]['m_message']) {
        $('#charEnNum').addClass('overload').text(len);
    } else {
        $('#charEnNum').removeClass('overload').text(len);
    }
}



function e_load_page(page, load_new_filter) {

    if (load_new_filter) {
        //Replace load more with spinner:
        var append_div = $('#new_11029').html();
        //The padding-bottom would delete the scrolling effect on the left side!
        $('#list_e').html('<span class="load-more" style="padding-bottom:500px;"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
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
            $('#list_e').html(data + '<div id="new_11029" class="list-group-item no-side-padding itemsource grey-input">' + append_div + '</div>').hide().fadeIn();
            //Reset search engine:
            e_load_search("#new_11029", 0, 'w');
        } else {
            //Update UI to confirm with user:
            $(data).insertBefore('#new_11029');
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

function load_13571(e__id, x__id) {

    $("#modal13571 .save_results").html('');
    $('#modal13571').modal('show');
    $('.notify_e_delete, .notify_unx_e').addClass('hidden'); //Cannot be deleted OR Unpublished as this would not load, so delete them

    //Load current Source:
    $.post("/e/load_13571", {

        e__id: e__id,
        x__id: x__id,

    }, function (data) {

        if (data.status) {

            //Update variables:
            $('#modal13571 .modal_x__id').val(x__id);
            $('#modal13571 .modal_e__id').val(e__id);

            $('#modal13571 .save_results').html('');
            $('.e_delete_stats').html('');


            $('#e__title').val(data.e__title).focus();
            $('#e__status').val(data.e__status);
            $('#e__icon').val(data.e__icon);

            autosize($('#e__title'));
            e__title_word_count();
            update_demo_icon();

            if (x__id > 0) {

                $('#x__status').val(data.x__status);
                $('#x__message').val(data.x__message);
                $('#e_x_count').val(0);
                $('.remove-e, .e_has_link').removeClass('hidden');
                autosize($('#x__message'));
                x_type_preview();

            } else {

                //Hide the section and clear it:
                $('.remove-e, .e_has_link').addClass('hidden');

            }

        } else {

            $("#modal13571 .save_results").html('<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>'+data.message+'</div>');

        }
    });
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

    $('.btn-save').removeClass('grey').attr('href', 'javascript:save_13571();').html('Save');

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
        //There is something in the input field, notify the user:
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

    $("#list_e .en-item").each(function () {
        //Fetch variables for this idea:
        var e__id = parseInt($(this).attr('e__id'));
        var x__id = parseInt($(this).attr('x__id'));

        sort_rank++;

        //Store in DB:
        new_x__sorts[sort_rank] = x__id;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0) {
        //Update backend:
        $.post("/e/e_sort_save", {e__id: e_focus_id, new_x__sorts: new_x__sorts}, function (data) {
            //Update UI to confirm with user:
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
    var theobject = document.getElementById("list_e");
    if (!theobject) {
        //due to duplicate ideas belonging in this idea:
        return false;
    }

    //Show sort icon:
    $('.fa-sort, .sort_reset').removeClass('hidden');

    var sort = Sortable.create(theobject, {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".en-item", // Specifies which items inside the element should be sortable
        handle: ".fa-sort", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            e_sort_save();
        }
    });
}

function save_13571() {

    //Are we about to delete an source with a lot of transactions?
    var x_count= parseInt($('#e_x_count').val());
    var do_13527 = 0;
    if(x_count >= 1){
        //Yes, confirm before doing so:
        var confirm_removal = prompt("Delete & unlink "+x_count+" links?! Type \"delete\" to confirm.", "");
        do_13527 = ( confirm_removal=='destroy' ? 1 : 0 );

        if (!(confirm_removal == 'delete') && !do_13527) {
            //Abandon process:
            alert('Source will not be deleted.');
            return false;
        }
    }

    //Prepare data to be modified for this idea:
    var modify_data = {
        e_focus_id: e_focus_id, //Determines if we need to change location upon removing...
        do_13527:do_13527,
        e__id: $('#modal13571 .modal_e__id').val(),
        e__title: $('#e__title').val().toUpperCase(),
        e__icon: $('#e__icon').val(),
        e__status: $('#e__status').val(), //The new status (might not have changed too)
        //Transaction data:
        x__id: $('#modal13571 .modal_x__id').val(),
        x__message: $('#x__message').val(),
        x__status: $('#x__status').val(),
    };

    //Show spinner:
    $('#modal13571 .save_results').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_12687(12695) +  '').hide().fadeIn();


    $.post("/e/save_13571", modify_data, function (data) {

        if (data.status) {

            $('#modal13571').modal('hide');

            if(data.delete_from_ui){

                //need to delete this source:
                //Idea has been either deleted OR Unpublished:
                if (data.delete_redirect_url) {

                    //move up 1 level as this was the focus idea:
                    window.location = data.delete_redirect_url;

                } else {

                    //Delete from UI:
                    $('.tr_' + modify_data['x__id']).html('<span><span class="icon-block"><i class="fas fa-trash-alt"></i></span>Deleted</span>').fadeOut();

                    //Disappear in a while:
                    setTimeout(function () {

                        //Hide the editor & saving results:
                        $('.tr_' + modify_data['x__id']).remove();

                    }, 610);

                }

            } else {

                //Reflect changed:
                //Might be in an INPUT or a DIV based on active superpowers:
                update_text_name(6197, modify_data['e__id'], modify_data['e__title']);


                //User Status:
                $('.e__status_' + modify_data['e__id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_e___6177[modify_data['e__status']]["m_title"] + ': ' + js_e___6177[modify_data['e__status']]["m_message"] + '">' + js_e___6177[modify_data['e__status']]["m_icon"] + '</span>');


                //User Icon:
                var icon_set = ( modify_data['e__icon'].length > 0 ? 1 : 0 );
                if(!icon_set){
                    //Set source default icon:
                    modify_data['e__icon'] = js_e___12467[12274]['m_icon'];
                }
                $('.e_ui_icon_' + modify_data['e__id']).html(modify_data['e__icon']);
                $('.e_child_icon_' + modify_data['e__id']).html(modify_data['e__icon']);


                //Did we have ideas to update?
                if (modify_data['x__id'] > 0) {

                    //Yes, update the ideas:
                    $(".x__message_" + modify_data['x__id']).html(data.x__message);

                    //Did the content get modified? (Likely for a domain URL):
                    if(!(data.x__message_final==modify_data['x__message'])){
                        $('#x__message').val(data.x__message_final).hide().fadeIn('slow');
                    }

                    //Transaction Status:
                    $('.x__status_' + modify_data['x__id']).html('<span data-toggle="tooltip" data-placement="right" title="' + js_e___6186[modify_data['x__status']]["m_title"] + ': ' + js_e___6186[modify_data['x__status']]["m_message"] + '">' + js_e___6186[modify_data['x__status']]["m_icon"] + '</span>');

                }

                //Update source timestamp:
                $('#modal13571 .save_results').html(data.message);

                //Reload Tooltip again:
                $('[data-toggle="tooltip"]').tooltip();
            }

        } else {
            //Ooops there was an error!
            $('#modal13571 .save_results').html('<span class="discover montserrat"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</span>').hide().fadeIn();
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
    e_avatar(type_css, null);

}

function e_avatar(type_css, icon_css){

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
    $.post("/e/e_avatar", {
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


function e_radio(parent_e__id, selected_e__id, enable_mulitiselect){

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

    $.post("/e/e_radio", {
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


function e_email(){

    //Show spinner:
    $('.save_email').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_12687(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_email", {
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


function e_password(){

    //Show spinner:
    $('.save_password').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_12687(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_password", {
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

