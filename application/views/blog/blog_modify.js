/*
*
* Functions related to modifying blogs
* and managing blog notes.
*
* */

var match_search_loaded = 0; //Keeps track of when we load the match search

$(document).ready(function () {


    //Lookout for completion mark changes:
    $('.dynamic_update').change(function() {
        alert('changed');
    });

    if($('#new_blog_title').val()==js_en_all_6201[4736]['m_name']){
        $('#new_blog_title').val('').focus();
    }

    autosize($('#new_blog_title'));

    $('#new_blog_title').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (e.ctrlKey && code == 13) {
            in_save_title();
        } else if (code == 13) {
            e.preventDefault();
        }
    }).focus(function() {
        //Clear default title
        if ($('#new_blog_title').val().toUpperCase() == js_en_all_6201[4736]['m_name']) {
            $('#new_blog_title').val('');
            $('#blog_title_save').addClass('hidden');
        }
    });


    $('#expand_blogs .expand_all').click(function (e) {
        $(".list-is-children .blogs_sortable").each(function () {
            ms_toggle($(this).attr('in-link-id'), 1);
        });
    });

    //Load top/bottom blog searches:
    in_load_search(".blogadder-level-2-parent",1, 'q');
    in_load_search(".blogadder-level-2-child",0, 'w');

    //Expand selections:
    prep_search_pad();

    //Load Sortable:
    in_sort_load(in_loaded_id);

    //Watch the expand/close all buttons:
    $('#expand_blogs .expand_all').click(function (e) {
        $(".list-is-children .blogs_sortable").each(function () {
            ms_toggle($(this).attr('in-link-id'), 1);
        });
    });
    $('#expand_blogs .close_all').click(function (e) {
        $(".list-is-children .blogs_sortable").each(function () {
            ms_toggle($(this).attr('in-link-id'), 0);
        });
    });


    //Loop through all new note inboxes:
    $(".new-note").each(function () {

        var focus_ln_type_player_id = parseInt($(this).attr('note-type-id'));

        //Initiate @ search for all note text areas:
        in_message_inline_en_search($(this));

        //Watch for focus:
        $(this).focus(function() {
            $( '#notes_control_'+focus_ln_type_player_id ).removeClass('hidden');
        }).keyup(function() {
            $( '#notes_control_'+focus_ln_type_player_id ).removeClass('hidden');
        });

        autosize($(this));

        //Activate sorting:
        in_notes_sort_load(focus_ln_type_player_id);

        var showFiles = function (files) {
            $('.box' + focus_ln_type_player_id).find('label').text(files.length > 1 ? ($('.box' + focus_ln_type_player_id).find('input[type="file"]').attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
        };

        $('.box' + focus_ln_type_player_id).find('input[type="file"]').on('drop', function (e) {
            droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
            showFiles(droppedFiles);
        });

        $('.box' + focus_ln_type_player_id).find('input[type="file"]').on('change', function (e) {
            showFiles(e.target.files);
        });

        //Watch for message creation:
        $('#ln_content' + focus_ln_type_player_id).keydown(function (e) {
            if (e.ctrlKey && e.keyCode == 13) {
                in_note_add(focus_ln_type_player_id);
            }
        });

        //Watchout for file uplods:
        $('.box' + focus_ln_type_player_id).find('input[type="file"]').change(function () {
            in_note_create_upload(droppedFiles, 'file', focus_ln_type_player_id);
        });


        //Should we auto start?
        if (isAdvancedUpload) {

            $('.box' + focus_ln_type_player_id).addClass('has-advanced-upload');
            var droppedFiles = false;

            $('.box' + focus_ln_type_player_id).on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
            })
                .on('dragover dragenter', function () {
                    $('.add_note_' + focus_ln_type_player_id).addClass('is-working');
                })
                .on('dragleave dragend drop', function () {
                    $('.add_note_' + focus_ln_type_player_id).removeClass('is-working');
                })
                .on('drop', function (e) {
                    droppedFiles = e.originalEvent.dataTransfer.files;
                    e.preventDefault();
                    in_note_create_upload(droppedFiles, 'drop', focus_ln_type_player_id);
                });
        }

    });

});

function show_save_button(){

    //Detect changes in blog title to show the save button:
    if($('#new_blog_title').val() == $('#current_blog_title').val() || $('#new_blog_title').val().length < 1 || $('#new_blog_title').val().toUpperCase() == js_en_all_6201[4736]['m_name']){
        //Nothing changed, so nothing to save:
        $('#blog_title_save').addClass('hidden');
    } else {
        //Something changed, show save button:
        $('#blog_title_save').removeClass('hidden');

    }
}


function in_update_dropdown(element_id, new_en_id, ln_id){

    /*
    *
    * WARNING:
    *
    * element_id Must be listed as children of:
    *
    * MEMORY CACHE @4527
    * JS MEMORY CACHE @11054
    *
    *
    * */

    var current_selected = parseInt($('.dropi_'+element_id+'_'+ln_id+'.active').attr('new-en-id'));
    new_en_id = parseInt(new_en_id);
    if(current_selected == new_en_id){
        //Nothing changed:
        return false;
    }

    //Are we deleting a status?
    var is_delete = (element_id==4737 && !(new_en_id in js_en_all_7356));
    if(is_delete){
        //Seems to be deleting, confirm:
        var r = confirm("Are you sure you want to archive this blog?");
        if (r == false) {
            return false;
        }
    }

    //Show Loading...
    var data_object = eval('js_en_all_'+element_id);
    $('.dropd_'+element_id+'_'+ln_id+' .btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">'+( ln_id>0 ? '' : 'SAVING...' )+'</b>');

    $.post("/blog/in_update_dropdown", {

        in_id: in_loaded_id,
        ln_id: ln_id,
        element_id: element_id,
        new_en_id: new_en_id

    }, function (data) {
        if (data.status) {

            //Toggle Settings View based on link type:
            if(element_id==4486){
                $('.in__tr_'+ln_id+' .link_marks').addClass('hidden');
                $('.in__tr_'+ln_id+' .settings_' + new_en_id).removeClass('hidden');
            }

            //Update on page:
            $('.dropd_'+element_id+'_'+ln_id+' .btn').html('<span class="icon-block">'+data_object[new_en_id]['m_icon']+'</span>' + ( ln_id>0 ? '' : data_object[new_en_id]['m_name'] ));
            $('.dropd_'+element_id+'_'+ln_id+' .dropi_' + element_id + '_' + ln_id).removeClass('active');
            $('.dropd_'+element_id+'_'+ln_id+' .optiond_' + new_en_id+ '_' + ln_id).addClass('active');

            if(is_delete){
                //Go to main blog page:
                window.location = '/blog';
            }

        } else {

            //Reset to default:
            $('.dropd_'+element_id+'_'+ln_id+' .btn').html('<span class="icon-block">'+data_object[current_selected]['m_icon']+'</span>' + ( ln_id>0 ? '' : data_object[current_selected]['m_name'] ));

            //Show error:
            alert('ERROR: ' + data.message);

        }
    });
}



function in_save_title(){
    //Fetch Blog Data to load modify widget:
    $('.title_update_status').html('<b class="montserrat"><i class="far fa-yin-yang fa-spin"></i> SAVING...</b>').hide().fadeIn();


    $.post("/blog/in_save_title", {
        in_id: in_loaded_id,
        in_title: $('#new_blog_title').val(),
    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.title_update_status').html(data.message);

            setTimeout(function () {
                $('#current_blog_title, #new_blog_title').val(data.in_cleaned_outcome);
                $('#blog_title_save').addClass('hidden');
                $('.title_update_status').html('');
            }, 1597);

        } else {
            //Show error:
            $('.title_update_status').html('<b class="montserrat ispink">ERROR: '+data.message+'</b>').hide().fadeIn();

        }
    });
}


function in_unlink(in_id, ln_id){
    var r = confirm("Unlink ["+$('.in_title_'+in_id).text()+"]?");
    if (r == true) {

        //Fetch Blog Data to load modify widget:
        $.post("/blog/in_unlink", {
            in_id: in_id,
            ln_id: ln_id,
        }, function (data) {
            if (data.status) {
                in_ui_remove(in_id,ln_id);
            }
        });
    }
}

function in_ui_remove(in_id,ln_id){

    //Fetch parent blog before removing element from DOM:
    var parent_in_id = parseInt($('.blog_line_' + in_id).attr('parent-blog-id'));

    //Remove from UI:
    $('.in__tr_' + ln_id).html('<span style="color:#070707;"><i class="fas fa-trash-alt"></i></span>');

    //Hide the editor & saving results:
    $('.in__tr_' + ln_id).fadeOut();

    //Disappear in a while:
    setTimeout(function () {

        //Hide the editor & saving results:
        $('.in__tr_' + ln_id).remove();

        //Hide editing box:
        $('#modifybox').addClass('hidden');

        //Re-sort sibling blogs:
        in_sort_save(parent_in_id);

    }, 610);

}

function in_modify_save() {

    //Validate that we have all we need:
    if ($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('blog-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Prepare BLOGS (in case we move a BLOG here):
    var in_id = parseInt($('#modifybox').attr('blog-id'));
    var top_level_ins = [ in_id ];
    $(".level2_in").each(function () {
        top_level_ins.push(parseInt($(this).attr('blog-id')));
    });


    //Prepare data to be modified for this blog:
    var modify_data = {
        in_id: in_id,
        in_title: $('#in_title').val(),
        in_status_player_id: parseInt($('#in_status_player_id').val()),
        in_type_player_id: parseInt($('#in_type_player_id').val()),
        in_read_time: ( $('#in_read_time').val().length > 0 ? parseInt($('#in_read_time').val()) : 0 ),
        is_parent: ( $('.blog_line_' + in_id).hasClass('parent-blog') ? 1 : 0 ),

        //Link variables:
        ln_id: parseInt($('#modifybox').attr('blog-tr-id')), //Will be zero for Level 1 blog!
        top_level_ins: top_level_ins,
        ln_type_player_id: null,
        tr__conditional_score_min: null,
        tr__conditional_score_max: null,
        tr__assessment_points: null,
    };


    //Do we have the blog Link?
    if (modify_data['ln_id'] > 0) {

        modify_data['ln_status_player_id'] = parseInt($('#ln_status_player_id').val());
        modify_data['ln_type_player_id'] = parseInt($('#ln_type_player_id').val());

        if(modify_data['ln_type_player_id'] == 4229){
            //Conditional Step Links
            //Condition score range:
            modify_data['tr__conditional_score_min'] = $('#tr__conditional_score_min').val();
            modify_data['tr__conditional_score_max'] = $('#tr__conditional_score_max').val();
        } else if(modify_data['ln_type_player_id'] == 4228){
            //Fixed link awarded points:
            modify_data['tr__assessment_points'] = $('#tr__assessment_points').val();
        }
    }



    //Save the rest of the content:
    $.post("/blog/in_modify_save", modify_data, function (data) {

        if (!data.status) {


        } else {

            //Has the blog/blog-link been removed? Either way, we need to hide this row:
            if (data.remove_from_ui) {

                //Remove from UI:
                in_ui_remove(modify_data['in_id'], modify_data['ln_id']);

            } else {

                //Blog has not been updated:

                //Did the Link update?
                if (modify_data['ln_id'] > 0) {

                    $('.ln_type_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_4486[modify_data['ln_type_player_id']]['m_name'] + ': '+ js_en_all_4486[modify_data['ln_type_player_id']]['m_desc'] + '">'+ js_en_all_4486[modify_data['ln_type_player_id']]['m_icon'] +'</span>');

                    $('.ln_status_player_id_' + modify_data['ln_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_6186[modify_data['ln_status_player_id']]['m_name'] + ': '+ js_en_all_6186[modify_data['ln_status_player_id']]['m_desc'] + '">'+ js_en_all_6186[modify_data['ln_status_player_id']]['m_icon'] +'</span>');

                    //Update Assessment
                    $(".in_assessment_" + modify_data['ln_id']).html(( modify_data['ln_type_player_id']==4228 ? ( modify_data['tr__assessment_points'] != 0 ? ( modify_data['tr__assessment_points'] > 0 ? '+' : '' ) + modify_data['tr__assessment_points'] : '' ) : modify_data['tr__conditional_score_min'] + ( modify_data['tr__conditional_score_min']==modify_data['tr__conditional_score_max'] ? '' : '-' + modify_data['tr__conditional_score_max'] ) + '%' ));

                }


                //Update UI components...

                //Always update 3x Blog icons...

                $('.in_parent_type_' + modify_data['in_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_7585[modify_data['in_type_player_id']]['m_name'] + ': '+ js_en_all_7585[modify_data['in_type_player_id']]['m_desc'] + '">'+ js_en_all_7585[modify_data['in_type_player_id']]['m_icon'] +'</span>');

                //Also update possible child icons:
                $('.in_child_icon_' + modify_data['in_id']).html(js_en_all_7585[modify_data['in_type_player_id']]['m_icon']);


                $('.in_status_player_id_' + modify_data['in_id']).html('<span data-toggle="tooltip" data-placement="right" title="'+ js_en_all_4737[modify_data['in_status_player_id']]['m_name'] + ': '+ js_en_all_4737[modify_data['in_status_player_id']]['m_desc'] + '">'+ js_en_all_4737[modify_data['in_status_player_id']]['m_icon'] +'</span>');




                //Did the outcome change?
                if(data.formatted_in_title){
                    //yes, update it:
                    $(".in_title_" + modify_data['in_id']).html(data.formatted_in_title);

                    //Set title:
                    $('.edit-header').html('<i class="fas fa-cog"></i> ' + modify_data['in_title']);

                    //Also update possible child icons:
                    $('.in_child_icon_' + modify_data['in_id']).attr('data-original-title', modify_data['in_title']);
                }


                //Should we try to check unlockable completions?
                if(data.ins_unlocked_completions_count > 0){
                    //We did complete/unlock some blogs, inform trainer and refresh:
                    alert('Publishing this blog has just unlocked '+data.steps_unlocked_completions_count+' steps across '+data.ins_unlocked_completions_count+' blogs. Page will be refreshed to reflect changes.');
                    window.location = "/blog/" + in_loaded_id;
                }

            }


        }
    });

}








/*
*
* BLOG NOTES
*
* */

function in_note_insert_string(focus_ln_type_player_id, add_string) {
    $('#ln_content' + focus_ln_type_player_id).insertAtCaret(add_string);
    in_new_note_count(focus_ln_type_player_id);
}


//Count text area characters:
function in_new_note_count(focus_ln_type_player_id) {

    //Update count:
    var len = $('#ln_content' + focus_ln_type_player_id).val().length;
    if (len > js_en_all_6404[11073]['m_desc']) {
        $('#charNum' + focus_ln_type_player_id).addClass('overload').text(len);
    } else {
        $('#charNum' + focus_ln_type_player_id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_en_all_6404[11073]['m_desc'] * js_en_all_6404[12088]['m_desc'] )){
        $('#blogNoteNewCount' + focus_ln_type_player_id).removeClass('hidden');
    } else {
        $('#blogNoteNewCount' + focus_ln_type_player_id).addClass('hidden');
    }

}

function in_edit_note_count(ln_id) {
    //See if this is a valid text message editing:
    if (!($('#charEditingNum' + ln_id).length)) {
        return false;
    }
    //Update count:
    var len = $('#message_body_' + ln_id).val().length;
    if (len > js_en_all_6404[11073]['m_desc']) {
        $('#charEditingNum' + ln_id).addClass('overload').text(len);
    } else {
        $('#charEditingNum' + ln_id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_en_all_6404[11073]['m_desc'] * js_en_all_6404[12088]['m_desc'] )){
        $('#blogNoteCount' + ln_id).removeClass('hidden');
    } else {
        $('#blogNoteCount' + ln_id).addClass('hidden');
    }
}


function in_message_inline_en_search(obj) {

    //Loadup algolia if not already:
    load_js_algolia();

    obj.textcomplete([
        {
            match: /(^|\s)@(\w*(?:\s*\w*))$/,
            search: function (query, callback) {
                algolia_index.search(query, {
                    hitsPerPage: 5,
                    filters: 'alg_obj_is_in=0',
                })
                    .then(function searchSuccess(content) {
                        if (content.query === query) {
                            callback(content.hits);
                        }
                    })
                    .catch(function searchFailure(err) {
                        console.error(err);
                    });
            },
            template: function (hit) {
                // Returns the highlighted version of the name attribute
                return '<span class="inline34">@' + hit.alg_obj_id + '</span> ' + hit._highlightResult.alg_obj_name.value;
            },
            replace: function (hit) {
                return ' @' + hit.alg_obj_id + ' ';
            }
        },
        {
            match: /(^|\s)#(\w*(?:\s*\w*))$/,
            search: function (query, callback) {
                algolia_index.search(query, {
                    hitsPerPage: 5,
                    filters: 'alg_obj_is_in=1',
                })
                    .then(function searchSuccess(content) {
                        if (content.query === query) {
                            callback(content.hits);
                        }
                    })
                    .catch(function searchFailure(err) {
                        console.error(err);
                    });
            },
            template: function (hit) {
                // Returns the highlighted version of the name attribute
                return '<span class="inline34">#' + hit.alg_obj_id + '</span> ' + hit._highlightResult.alg_obj_name.value;
            },
            replace: function (hit) {
                return ' #' + hit.alg_obj_id + ' ';
            }
        },
    ]);
}



function in_notes_sort_apply(focus_ln_type_player_id) {

    var new_ln_orders = [];
    var sort_rank = 0;
    var this_ln_id = 0;

    $(".msg_en_type_" + focus_ln_type_player_id).each(function () {
        this_ln_id = parseInt($(this).attr('tr-id'));
        if (this_ln_id > 0) {
            sort_rank++;
            new_ln_orders[sort_rank] = this_ln_id;
        }
    });

    //Update backend if any:
    if(sort_rank > 0){
        $.post("/blog/in_notes_sort", {new_ln_orders: new_ln_orders}, function (data) {
            //Only show message if there was an error:
            if (!data.status) {
                //Show error:
                alert('ERROR: ' + data.message);
            }
        });
    }
}

function in_notes_sort_load(focus_ln_type_player_id) {

    var inner_content = null;

    var sort_msg = Sortable.create( document.getElementById("in_notes_list_" + focus_ln_type_player_id) , {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        handle: ".blog_note_sorting", // Restricts sort start click/touch to the specified element
        draggable: ".blogs_sortable", // Specifies which items inside the element should be sortable
        onUpdate: function (evt/**Event*/) {
            //Apply new sort:
            in_notes_sort_apply(focus_ln_type_player_id);
        },
        //The next two functions resolve a Bug with sorting iframes like YouTube embeds while also making the UI more informative
        onChoose: function (evt/**Event*/) {
            //See if this is a YouTube or Vimeo iFrame that needs to be temporarily removed:
            var ln_id = $(evt.item).attr('tr-id');
            if ($('#ul-nav-' + ln_id).find('.video-sorting').length !== 0) {
                inner_content = $('#msgbody_' + ln_id).html();
                $('#msgbody_' + ln_id).css('height', $('#msgbody_' + ln_id).height()).html('<i class="fas fa-sort"></i> Drag up/down to sort video');
            } else {
                inner_content = null;
            }
        },
        onEnd: function (evt/**Event*/) {
            if (inner_content) {
                var ln_id = $(evt.item).attr('tr-id');
                $('#msgbody_' + ln_id).html(inner_content);
            }
        }
    });

}

function in_note_modify_start(ln_id) {

    //Start editing:
    $("#ul-nav-" + ln_id).addClass('in-editing');
    $("#ul-nav-" + ln_id + " .edit-off").addClass('hidden');
    $("#ul-nav-" + ln_id + " .edit-on").removeClass('hidden');
    $("#ul-nav-" + ln_id + ">div").css('width', '100%');

    //Set focus to end of text:
    var textinput = $("#ul-nav-" + ln_id + " textarea");
    var data = textinput.val();
    textinput.focus().val('').val(data);
    autosize(textinput); //Adjust height

    //Initiate search:
    in_message_inline_en_search(textinput);

    //Try to initiate the editor, which only applies to text messages:
    in_edit_note_count(ln_id);

}

function in_note_modify_cancel(ln_id) {
    //Revert editing:
    $("#ul-nav-" + ln_id).removeClass('in-editing');
    $("#ul-nav-" + ln_id + " .edit-off").removeClass('hidden');
    $("#ul-nav-" + ln_id + " .edit-on").addClass('hidden');
    $("#ul-nav-" + ln_id + ">div").css('width', 'inherit');
}

function in_note_modify_save(ln_id, focus_ln_type_player_id) {

    //Show loader:
    $("#ul-nav-" + ln_id + " .edit-updates").html('<div><i class="far fa-yin-yang fa-spin"></i></div>');

    //Revert View:
    in_note_modify_cancel(ln_id);


    var modify_data = {
        ln_id: parseInt(ln_id),
        message_ln_status_player_id: parseInt($("#message_status_" + ln_id).val()),
        in_id: parseInt(in_loaded_id),
        ln_content: $("#ul-nav-" + ln_id + " textarea").val(),
    };

    //Update message:
    $.post("/blog/in_note_modify_save", modify_data, function (data) {

        if (data.status) {

            //Did we remove this message?
            if(data.remove_from_ui){

                //Yes, message was removed, adjust accordingly:
                $("#ul-nav-" + ln_id).html('<div>' + data.message + '</div>');

                //Disapper in a while:
                setTimeout(function ()
                {
                    $("#ul-nav-" + ln_id).fadeOut();

                    setTimeout(function () {

                        //Remove first:
                        $("#ul-nav-" + ln_id).remove();

                        //Adjust sort for this message type:
                        in_notes_sort_apply(focus_ln_type_player_id);

                    }, 610);
                }, 610);

            } else {

                //Nope, message was just edited...

                //Update text message:
                $("#ul-nav-" + ln_id + " .text_message").html(data.message);

                //Update message status:
                $("#ul-nav-" + ln_id + " .message_status").html(data.message_new_status_icon);

                //Show success here
                $("#ul-nav-" + ln_id + " .edit-updates").html('<b>' + data.success_icon + '</b>');

            }

        } else {
            //Oops, some sort of an error, lets
            $("#ul-nav-" + ln_id + " .edit-updates").html('<b style="color:#FF0000 !important; line-height: 110% !important;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</b>');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Disapper in a while:
        setTimeout(function () {
            $("#ul-nav-" + ln_id + " .edit-updates>b").fadeOut();
        }, 4181);
    });
}



function in_message_form_lock(focus_ln_type_player_id) {
    $('.save_note_' + focus_ln_type_player_id).html('<span class="icon-block-lg"><i class="far fa-yin-yang fa-spin"></i></span>').attr('href', '#');
    $('.add_note_' + focus_ln_type_player_id).addClass('is-working');
    $('#ln_content' + focus_ln_type_player_id).prop("disabled", true);
    $('.remove_loading').hide();
}


function in_message_form_unlock(result, focus_ln_type_player_id) {

    //Update UI to unlock:
    $('.save_note_' + focus_ln_type_player_id).html('SAVE').attr('href', 'javascript:in_note_add('+focus_ln_type_player_id+');');
    $('.add_note_' + focus_ln_type_player_id).removeClass('is-working');
    $("#ln_content" + focus_ln_type_player_id).prop("disabled", false).focus();
    $('.remove_loading').fadeIn();
    $( '#notes_control_'+focus_ln_type_player_id ).addClass('hidden');

    //What was the result?
    if (result.status) {

        //Append data:
        $(result.message).insertBefore( ".add_note_" + focus_ln_type_player_id );

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Hide any errors:
        setTimeout(function () {
            $(".note_error_"+focus_ln_type_player_id).fadeOut();
        }, 4181);

    } else {

        $(".note_error_"+focus_ln_type_player_id).html(result.message);

    }
}

function in_note_create_upload(droppedFiles, uploadType, focus_ln_type_player_id) {

    //Prevent multiple concurrent uploads:
    if ($('.box' + focus_ln_type_player_id).hasClass('is-uploading')) {
        return false;
    }

    if (isAdvancedUpload) {

        //Lock message:
        in_message_form_lock(focus_ln_type_player_id);

        var ajaxData = new FormData($('.box' + focus_ln_type_player_id).get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $('.box' + focus_ln_type_player_id).find('input[type="file"]').attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('in_id', in_loaded_id);
        ajaxData.append('focus_ln_type_player_id', focus_ln_type_player_id);

        $.ajax({
            url: '/blog/in_note_create_upload',
            type: $('.box' + focus_ln_type_player_id).attr('method'),
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.box' + focus_ln_type_player_id).removeClass('is-uploading');
            },
            success: function (data) {

                in_message_form_unlock(data, focus_ln_type_player_id);

                //Adjust icon again:
                $('.file_label_' + focus_ln_type_player_id).html('<span class="icon-block en-icon"><i class="far fa-paperclip"></i></span>');

            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                in_message_form_unlock(result, focus_ln_type_player_id);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}

function in_note_add(focus_ln_type_player_id) {

    //Lock message:
    in_message_form_lock(focus_ln_type_player_id);

    //Update backend:
    $.post("/blog/in_note_create_text", {

        in_id: in_loaded_id, //Synonymous
        ln_content: $('#ln_content' + focus_ln_type_player_id).val(),
        focus_ln_type_player_id: focus_ln_type_player_id,

    }, function (data) {

        //Raw Inputs Fields if success:
        if (data.status) {

            //Reset input field:
            $("#ln_content" + focus_ln_type_player_id).val("");
            in_new_note_count(focus_ln_type_player_id);

        }

        //Unlock field:
        in_message_form_unlock(data, focus_ln_type_player_id);

    });

}






























function prep_search_pad(){

    //All level 2s:
    $('.blogadder-level-2-parent').focus(function() {
        $('.in_pad_top' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_top' ).addClass('hidden');
    });

    $('.blogadder-level-2-child').focus(function() {
        $('.in_pad_bottom' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_bottom' ).addClass('hidden');
    });

}

function in_load_search(focus_element, is_in_parent, shortcut) {

    //Loads the blog search bar only once for the add blog inputs
    if($(focus_element).hasClass('search-bar-loaded')){
        //Already loaded:
        return false;
    }


    //Not yet loaded, continue with loading it:
    $(focus_element).addClass('search-bar-loaded').on('autocomplete:selected', function (event, suggestion, dataset) {

        in_link_or_create($(this).attr('blog-id'), is_in_parent, suggestion.alg_obj_id);

    }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [shortcut]}, [{

        source: function (q, cb) {

            if($(focus_element).val().charAt(0)=='#'){
                cb([]);
                return;
            } else {
                algolia_index.search(q, {

                    filters: ' alg_obj_is_in=1 AND ( _tags:alg_is_published_featured ' + ( js_pl_id > 0 ? 'OR _tags:alg_author_' + js_pl_id : '' ) + ' ) ',
                    hitsPerPage: 7,

                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            }

        },
        displayKey: function (suggestion) {
            return ""
        },
        templates: {
            suggestion: function (suggestion) {
                return echo_js_suggestion(suggestion);
            },
            header: function (data) {
                if (!($(focus_element).val().charAt(0)=='#') && !data.isEmpty) {
                    return '<a href="javascript:in_link_or_create(' + parseInt($(focus_element).attr('blog-id')) + ','+is_in_parent+',0)" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle yellow add-plus"></i></span><b>' + data.query + '</b></a>';
                }
            },
            empty: function (data) {
                if($(focus_element).val().charAt(0)=='#'){
                    return '<a href="javascript:in_link_or_create(' + parseInt($(focus_element).attr('blog-id')) + ','+is_in_parent+',0)" class="suggestion"><span class="icon-block-sm"><i class="fas fa-link"></i></span>Link to <b>' + data.query + '</b></a>';
                } else {
                    return '<a href="javascript:in_link_or_create(' + parseInt($(focus_element).attr('blog-id')) + ','+is_in_parent+',0)" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle yellow add-plus"></i></span><b>' + data.query + '</b></a>';
                }
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return in_link_or_create($(this).attr('blog-id'), is_in_parent, 0);
        }
    });

}


function in_sort_save(in_id) {

    var s_element = "list-in-" + in_loaded_id + '-0';
    var s_draggable = ".blogs_sortable";
    var new_ln_orders = [];
    var sort_rank = 0;

    $("#" + s_element + " " + s_draggable).each(function () {
        //Fetch variables for this blog:
        var in_id = parseInt($(this).attr('blog-id'));
        var ln_id = parseInt($(this).attr('in-link-id'));

        sort_rank++;

        //Store in DB:
        new_ln_orders[sort_rank] = ln_id;
    });


    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0 && in_id) {
        //Update backend:
        $.post("/blog/in_sort_save", {in_id: in_id, new_ln_orders: new_ln_orders}, function (data) {
            //Update UI to confirm with user:
            if (!data.status) {
                //There was some sort of an error returned!
                alert('ERROR: ' + data.message);
            }
        });
    }
}


function in_sort_load(in_id) {


    var element_key = null;
    var s_element = "list-in-" + in_loaded_id + '-0';
    var s_draggable = ".blogs_sortable";
    var theobject = document.getElementById(s_element);
    if (!theobject) {
        //due to duplicate blogs belonging in this tree:
        //TODO Fix later to support duplicate blogs
        return false;
    }

    var sort = Sortable.create(theobject, {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: s_draggable, // Specifies which items inside the element should be sortable
        handle: ".enable-sorting", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            in_sort_save(in_id);
        }
    });
}



function in_link_or_create(in_linked_id, is_parent, in_link_child_id) {

    /*
     *
     * Either creates a BLOG link between in_linked_id & in_link_child_id
     * OR will create a new blog based on input text and then link it
     * to in_linked_id (In this case in_link_child_id=0)
     *
     * */


    var sort_handler = ".blogs_sortable";
    var sort_list_id = "list-in-" + in_loaded_id + '-' + is_parent;
    var input_field = $('#addblog-c-' + in_linked_id + '-' + is_parent);
    var blog_name = input_field.val();


    if( blog_name.charAt(0)=='#'){
        if(isNaN(blog_name.substr(1))){
            alert('Error: Use numbers only. Example: #1234');
            return false;
        } else {
            //Update the references:
            in_link_child_id = parseInt(blog_name.substr(1));
            blog_name = in_link_child_id; //As if we were just linking
        }
    }




    //We either need the blog name (to create a new blog) or the in_link_child_id>0 to create a BLOG link:
    if (!in_link_child_id && blog_name.length < 1) {
        alert('Error: Enter something');
        input_field.focus();
        return false;
    }

    //Set processing status:
    add_to_list(sort_list_id, sort_handler, '<div id="tempLoader" class="list-group-item itemblog"><i class="far fa-yin-yang fa-spin"></i> Adding... </div>');

    //Update backend:
    $.post("/blog/in_link_or_create", {
        in_linked_id: in_linked_id,
        is_parent:is_parent,
        in_title: blog_name,
        in_link_child_id: in_link_child_id
    }, function (data) {

        //Remove loader:
        $("#tempLoader").remove();

        if (data.status) {

            //Add new
            add_to_list(sort_list_id, sort_handler, data.in_child_html);

            //Reload sorting to enable sorting for the newly added blog:
            in_sort_load(in_linked_id);

            //Expand selections:
            prep_search_pad();

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        } else {
            //Show errors:
            alert('ERROR: ' + data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}