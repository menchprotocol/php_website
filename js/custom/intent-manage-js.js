//Set global variables:
var is_compact = (is_mobile() || $(window).width()<767);


function c_js_hours(dbl_hour){
    dbl_hour = parseFloat(dbl_hour);
    if(dbl_hour<=0){
        return '0';
    } else if(dbl_hour<1){
        //Show this in minutes:
        return Math.round((dbl_hour*60)) + "m";
    } else {
        //Show in rounded hours:
        return Math.round((dbl_hour)) + "h";
    }
}

$(document).ready(function() {

    if(is_compact){

        //Adjust columns:
        $('.cols').removeClass('col-xs-6').addClass('col-sm-6');
        $('.fixed-box').addClass('release-fixture');

    } else {

        //Adjust height of the messaging windows:
        $('.grey-box').css('max-height', (parseInt($( window ).height())-130)+'px');

        //Make editing frames Sticky for scrolling longer lists
        $(".main-panel").scroll(function() {
            var top_position = $(this).scrollTop();
            clearTimeout($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function() {
                $("#modifybox").css('top',(top_position-0)); //PX also set in style.css for initial load
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
                i_load_modify(hash_parts[1]);
            } else if(hash_parts[0]=='modify'){
                c_load_modify(hash_parts[1],hash_parts[2]);
            } else if(hash_parts[0]=='estats'){
                estats_load(hash_parts[1]);
            } else if(hash_parts[0]=='kcache'){
                c_load_engagements(hash_parts[1]);
            }
        }
    }


    //Watch the expand/close all buttons:
    $('#task_view .expand_all').click(function (e) {
        $( ".list-is-children .is_level2_sortable" ).each(function() {
            ms_toggle($( this ).attr('data-link-id'),1);
        });
    });
    $('#task_view .close_all').click(function (e) {
        $( ".list-is-children .is_level2_sortable" ).each(function() {
            ms_toggle($( this ).attr('data-link-id'),0);
        });
    });

    //Load Sortable:
    c_load_sort(c_top_id,2);


    $('input[type=radio][name=c_is_any]').change(function() {
        c_adjust_isany_ui();
    });


    //Activate sorting for Steps:
    if($('.step-group').length){

        $( ".step-group" ).each(function() {

            var intent_id = parseInt($( this ).attr('intent-id'));

            //Load sorting:
            c_load_sort(intent_id,3);

            //Load time:
            $('.t_estimate_'+intent_id).text(c_js_hours($('.t_estimate_'+intent_id+':first').attr('tree-hours')));

        });

        if($('.is_level3_sortable').length){
            //Goo through all Steps:
            $( ".is_level3_sortable" ).each(function() {
                var intent_id = $(this).attr('intent-id');
                if(intent_id){
                    //Load time:
                    $('.t_estimate_'+intent_id).text(c_js_hours($('.t_estimate_'+intent_id+':first').attr('tree-hours')));
                }
            });
        }
    }


    $( "#dir_handle" ).click(function() {
        c_js_new(c_top_id, 2);
    });


    //Load Algolia:
    $(".intentadder-level-2").on('autocomplete:selected', function(event, suggestion, dataset) {

        c_js_new($(this).attr('intent-id'), 2, suggestion.c_id);

    }).autocomplete({ hint: false, minLength: 3, keyboardShortcuts: ['a'] }, [{

        source: function(q, cb){
            algolia_c_index.search(q, {
                hitsPerPage: 7,
            }, function(error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        displayKey: function(suggestion) { return "" },
        templates: {
            suggestion: function(suggestion) {
                var fancy_hours = fancy_time(suggestion);
                return '<span class="suggest-prefix"><i class="fas fa-hashtag"></i></span> '+ suggestion._highlightResult.c_outcome.value + ( fancy_hours ? '<span class="search-info">'+( parseFloat(suggestion.c__count)>1 ? ' <i class="fas fa-sitemap"></i> ' + suggestion.c__count : '' ) + ' <i class="fas fa-clock"></i> '+ fancy_hours+'</span>' : '');
            },
            header: function(data) {
                if(!data.isEmpty){
                    return '<a href="javascript:c_js_new(\''+$(".intentadder-level-2").attr('intent-id')+'\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-hashtag"></i> '+data.query+'</a>';
                }
            },
            empty: function (data) {
                return '<a href="javascript:c_js_new(\''+$(".intentadder-level-2").attr('intent-id')+'\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-hashtag"></i> '+data.query+'</a>';
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return c_js_new($(this).attr('intent-id'),2);
        }
    });

    c_load_level3_search();

});

function c_adjust_isany_ui(){
    if($('#c_is_any_0').is(':checked')){
        //Unlock settings:
        $('.completion-settings').removeClass('hidden');
    } else {
        //Any is selected, lock the completion settings as its not allowed for ANY Branches:
        $('#c_require_notes_to_complete').prop('checked', false);
        $('#c_require_url_to_complete').prop('checked', false);
        $('.completion-settings').addClass('hidden');
    }
}

function c_load_level3_search(){

    $(".intentadder-level-3").on('autocomplete:selected', function(event, suggestion, dataset) {

        c_js_new($(this).attr('intent-id'), 3, suggestion.c_id);

    }).autocomplete({ hint: false, minLength: 3, keyboardShortcuts: ['a'] }, [{

        source: function(q, cb){
            algolia_c_index.search(q, {
                hitsPerPage: 7,
            }, function(error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        displayKey: function(suggestion) { return "" },
        templates: {
            suggestion: function(suggestion) {
                var fancy_hours = fancy_time(suggestion);
                return '<span class="suggest-prefix"><i class="fas fa-hashtag"></i></span> '+ suggestion._highlightResult.c_outcome.value + ( fancy_hours ? '<span class="search-info">'+( parseInt(suggestion.c__tree_all_count)>1 ? ' <i class="'+( parseInt(suggestion.c_is_any) ? 'fas fa-code-merge' : 'fas fa-sitemap' )+'"></i> ' + parseInt(suggestion.c__tree_all_count) : '' ) + ' <i class="fas fa-clock"></i> '+ fancy_hours+'</span>' : '');
            },
            header: function(data) {
                if(!data.isEmpty){
                    return '<a href="javascript:c_js_new(\''+$(".intentadder-level-3").attr('intent-id')+'\',3)" class="suggestion"><span><i class="fas fa-plus-circle"></i></span> '+data.query+'</a>';
                }
            },
        }
    }]).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            return c_js_new($(this).attr('intent-id'),3);
        }
    });

}


function c_save_sort(c_id,level){

    if(level==2){
        var s_element = "list-c-"+c_top_id;
        var s_draggable = ".is_level2_sortable";
    } else if(level==3){
        var s_element = "list-cr-"+$('.intent_line_'+c_id).attr('data-link-id');
        var s_draggable = ".is_level3_sortable";
    } else {
        //Should not happen!
        return false;
    }

    //Fetch new sort:
    var new_sort = [];
    var sort_rank = 0;
    var is_properly_sorted = true; //Assume good unless proven otherwise


    $( "#"+s_element+" "+s_draggable ).each(function() {
        //Make sure this is NOT the dummy drag in box
        if(!$(this).hasClass('dropin-box')){

            //Fetch variables for this intent:
            var c_id = parseInt($(this).attr('intent-id'));
            var cr_id = parseInt($( this ).attr('data-link-id'));

            sort_rank++;

            //Store in DB:
            new_sort[sort_rank] = cr_id;

            //Is the Child rank correct? Check DB value:
            var db_rank = parseInt($('.c_outcome_'+c_id).attr('children-rank'));

            if(level==2 && !(db_rank==sort_rank) && !c_id){
                is_properly_sorted = false;
                console.log('Intent #'+c_id+' detected out of sync.');
            }

            //Update sort handler:
            $( "#cr_"+cr_id+" .inline-level-"+level ).html('#' + sort_rank);
        }
    });


    if(level==2 && !is_properly_sorted && !c_id){
        //Sorting issue detected on Task load:
        c_id = parseInt(c_top_id);
    }

    //It might be zero for lists that have jsut been emptied
    if(sort_rank>0 && c_id){
        //Update backend:
        $.post("/intents/c_save_sort", { c_id:c_id, new_sort:new_sort }, function(data) {
            //Update UI to confirm with user:
            if(!data.status){
                //There was some sort of an error returned!
                alert('ERROR: '+data.message);
            }
        });
    }
}


function c_load_sort(c_id,level){

    if(level==2){
        var element_key = null;
        var s_element = "list-c-"+c_top_id;
        var s_draggable = ".is_level2_sortable";
    } else if(level==3){
        var element_key = '.intent_line_'+c_id;
        var s_element = "list-cr-"+$(element_key).attr('data-link-id');
        var s_draggable = ".is_level3_sortable";
    } else {
        //Should not happen!
        return false;
    }

    var theobject = document.getElementById(s_element);

    if(!theobject){
        //Likely due to duplicate intents belonging in this tree!

        //Show general error:
        $('#outs_error').html( "<div class=\"alert alert-danger\"><i class=\"fas fa-exclamation-triangle\"></i> Error: Detected duplicate intents! Fix & refresh.</div>" );

        //Show specific error:
        if(element_key){
            $( "<div class=\"act-error\"><i class=\"fas fa-exclamation-triangle\"></i> Error: Duplicate intent! Only keep 1 & refresh.</div>" ).prependTo( element_key );
        }

        return false;
    }

    var settings = {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: s_draggable, // Specifies which items inside the element should be sortable
        handle: ".fa-bars", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/){
            c_save_sort(c_id,level);
        }
    };


    //Enable moving level 3 intents between level 2 intents:
    if(level=="3"){

        settings['group'] = "steplists";
        settings['ghostClass'] = "drop-step-here";
        settings['onAdd'] = function (evt) {
            //Define variables:
            var inputs = {
                cr_id:evt.item.attributes[1].nodeValue,
                c_id:evt.item.attributes[2].nodeValue,
                from_c_id:evt.from.attributes[2].value,
                to_c_id:evt.to.attributes[2].value,
            };
            //Update:
            $.post("/intents/c_move_c", inputs, function(data) {
                //Update sorts in both lists:
                if(!data.status){

                    //There was some sort of an error returned!
                    alert('ERROR: '+data.message);

                } else {

                    //All good as expected!
                    //Moved the parent pointer:
                    $('.intent_line_'+inputs.c_id).attr('parent-intent-id',inputs.to_c_id);

                    //Determine core variables for hour move calculations:
                    var step_hours = parseFloat($('.t_estimate_'+inputs.c_id+':first').attr('tree-hours'));
                    var intent_count = parseInt($('.children-counter-'+inputs.c_id+':first').text());

                    if(!(step_hours==0)){
                        //Remove from old one:
                        var from_hours_new = parseFloat($('.t_estimate_'+inputs.from_c_id+':first').attr('tree-hours'))-step_hours;
                        $('.t_estimate_'+inputs.from_c_id).attr('tree-hours',from_hours_new).text(c_js_hours(from_hours_new));
                        $('.children-counter-'+inputs.from_c_id).text( parseInt($('.children-counter-'+inputs.from_c_id+':first').text()) - intent_count );

                        //Add to new:
                        var to_hours_new = parseFloat($('.t_estimate_'+inputs.to_c_id+':first').attr('tree-hours'))+step_hours;
                        $('.t_estimate_'+inputs.to_c_id).attr('tree-hours',to_hours_new).text(c_js_hours(to_hours_new));
                        $('.children-counter-'+inputs.to_c_id).text( parseInt($('.children-counter-'+inputs.to_c_id+':first').text()) + intent_count );
                    }

                    //Update sorting for both lists:
                    c_save_sort(inputs.from_c_id,3);
                    c_save_sort(inputs.to_c_id,3);

                }
            });
        };
    }

    var sort = Sortable.create( theobject , settings );
}


function i_load_modify(c_id){
    //Start loading:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $('#load_w_frame, .frame-loader').removeClass('hidden').hide().fadeIn();
    //Set title:
    $('#w_title').html('<i class="fas fa-comment-dots"></i> '+$('.c_outcome_'+c_id+':first').text());

    //Load content via a URL:
    $('.frame-loader').addClass('hidden');
    $('.ajax-frame').attr('src','/intents/i_load_modify/'+c_id).removeClass('hidden').css('margin-top','0');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();
}


function estats_load(c_id){
    //Start loading:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $('#load_w_frame, .frame-loader').removeClass('hidden').hide().fadeIn();

    //Set title:
    $('#w_title').html('<i class="fas fa-exchnge"></i> '+$('.c_outcome_'+c_id+':first').text());

    //Load content via a URL:
    $('.frame-loader').addClass('hidden');
    $('.ajax-frame').attr('src','/intents/estats_load/'+c_id).removeClass('hidden').css('margin-top','0');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();
}

function c_load_engagements(c_id){
    //Start loading:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $('#load_w_frame, .frame-loader').removeClass('hidden').hide().fadeIn();
    //Set title:
    $('#w_title').html('<i class="fas fa-flag"></i> '+$('.c_outcome_'+c_id+':first').text());

    //Load content via a URL:
    $('.frame-loader').addClass('hidden');
    $('.ajax-frame').attr('src','/intents/c_load_engagements/'+c_id).removeClass('hidden').css('margin-top','0');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();
}



function adjust_js_ui(c_id, level, new_hours, intent_deficit_count=0, apply_to_tree=0, skip_intent_adjustments=0){

    intent_deficit_count = parseInt(intent_deficit_count);
    var intent_hours = parseFloat($('.t_estimate_'+c_id+':first').attr('intent-hours'));
    var tree_hours = parseFloat($('.t_estimate_'+c_id+':first').attr('tree-hours'));
    var intent_deficit_hours = new_hours - ( skip_intent_adjustments ? 0 : ( apply_to_tree ? tree_hours : intent_hours ) );

    if(intent_deficit_hours==0 && intent_deficit_count==0){
        //Nothing changed, so we need to do nothing either!
        return false;
    }

    //Adjust same level hours:
    if(!skip_intent_adjustments){
        var new_tree_hours = tree_hours + intent_deficit_hours;
        $('.t_estimate_'+c_id)
            .attr('tree-hours', new_tree_hours)
            .text(c_js_hours(new_tree_hours));

        if(!apply_to_tree){
            $('.t_estimate_'+c_id).attr('intent-hours',new_hours).text(c_js_hours(new_tree_hours));
        }
    }


    //Adjust parent counters, if any:
    if(!(intent_deficit_count==0)){
        //See how many parents we have:
        $('.inb-counter').each(function(){
            $(this).text( parseInt($(this).text()) + intent_deficit_count );
        });
    }

    if(level>=2){

        //Adjust the parent level hours:
        var c_parent_id = parseInt($('.intent_line_'+c_id).attr('parent-intent-id'));
        var c_parent_tree_hours = parseFloat($('.t_estimate_'+c_parent_id+':first').attr('tree-hours'));
        var new_c_parent_tree_hours = c_parent_tree_hours + intent_deficit_hours;

        if(!(intent_deficit_count==0)){
            $('.children-counter-'+c_parent_id).text( parseInt($('.children-counter-'+c_parent_id+':first').text()) + intent_deficit_count );
        }

        if(!(intent_deficit_hours==0)){
            //Update Hours (Either level 1 or 2):
            $('.t_estimate_'+c_parent_id)
                .attr('tree-hours', new_c_parent_tree_hours)
                .text(c_js_hours(new_c_parent_tree_hours));
        }

        if(level==3){
            //Adjust top level intent as well:
            var top_c_id = parseInt($('.intent_line_'+c_parent_id).attr('parent-intent-id'));
            var top_c_tree_hours = parseFloat($('.t_estimate_'+top_c_id+':first').attr('tree-hours'));
            var new_top_c_tree_hours = top_c_tree_hours + intent_deficit_hours;


            if(!(intent_deficit_count==0)){
                $('.children-counter-'+top_c_id).text( parseInt($('.children-counter-'+top_c_id+':first').text()) + intent_deficit_count );
            }

            if(!(intent_deficit_hours==0)){
                //Update Hours:
                $('.t_estimate_'+top_c_id)
                    .attr('tree-hours', new_top_c_tree_hours)
                    .text(c_js_hours(new_top_c_tree_hours));
            }
        }
    }
}


function c_unlink(){

    var cr_id = ( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('intent-link-id')) );
    var c_id = ( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('intent-id')) );

    if(!c_id || !cr_id){
        alert('Error: No Intent has been loaded.');
        return false;
    }

    var c_parent_id = parseInt($('#cr_'+cr_id).attr('parent-intent-id'));
    var level = parseInt($('#cr_'+cr_id).attr('intent-level')); //Either 2 or 3 (Cannot unlink level 1)
    var r = confirm("Unlink \""+$('#c_outcome').val()+"\"?\n(Intent will remain accessible)");

    if (r == true) {
        //Load parent intents:
        $.post("/intents/c_unlink", {c_id:c_id, cr_id:cr_id} , function(data) {
            if(data.status){

                //Adjust hours:
                adjust_js_ui(c_id, level, 0, data.adjusted_c_count, 1);

                //Remove from UI:
                $('#cr_' + cr_id).html('<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>');

                //Disapper in a while:
                //Hide the editor & saving results:
                $('#cr_' + cr_id).fadeOut();
                setTimeout(function () {
                    //Hide the editor & saving results:
                    $('#cr_' + cr_id).remove();
                    //Hide editing box:
                    $('#modifybox').addClass('hidden');

                    //Resort all Tasks to illustrate changes on UI:
                    c_save_sort(c_parent_id,level);
                }, 377);

            } else {
                alert('ERROR: '+data.message);
            }
        });
    } else {
        return false;
    }
}



function c_outcome_word_count() {
    var len = $('#c_outcome').val().length;
    if (len>c_outcome_max) {
        $('#charNameNum').addClass('overload').text(len);
    } else {
        $('#charNameNum').removeClass('overload').text(len);
    }
}


function c_load_modify(c_id, cr_id){

    //Make sure inputs are valid:
    if(!$('.t_estimate_'+c_id+':first').length){
        return false;
    }

    var level = ( cr_id==0 ? 1 : parseInt($('#cr_'+cr_id).attr('intent-level')) ); //Either 1, 2 or 3

    //Update variables:
    $('#modifybox').attr('intent-link-id',cr_id);
    $('#modifybox').attr('intent-id',c_id);
    $('#modifybox').attr('level',level);


    //Set variables:
    var intent_hours = parseFloat($('.t_estimate_'+c_id+':first').attr('intent-hours'));
    var tree_hours = $('.t_estimate_'+c_id+':first').attr('tree-hours');

    $('#c_outcome').val($(".c_outcome_"+c_id+":first").text());
    c_outcome_word_count();

    $('#c_status').val($('.c_outcome_'+c_id).attr('c_status'));
    $('#c_points').val($('.c_outcome_'+c_id).attr('c_points'));
    $('#c_trigger_statements').val($('.c_outcome_'+c_id).attr('c_trigger_statements'));
    $('#c_time_estimate').val(Math.round(intent_hours*60));
    $('#c_cost_estimate').val(parseFloat($('.c_outcome_'+c_id).attr('c_cost_estimate')));

    $("input[name=c_is_any][value='"+$('.c_outcome_'+c_id).attr('c_is_any')+"']").prop("checked",true);
    document.getElementById("c_require_url_to_complete").checked = parseInt($('.c_outcome_'+c_id).attr('c_require_url_to_complete'));
    document.getElementById("c_require_notes_to_complete").checked = parseInt($('.c_outcome_'+c_id).attr('c_require_notes_to_complete'));
    document.getElementById("apply_recurively").checked = false; //Always remove this so the user can choose

    c_adjust_isany_ui();

    //Are the tree hours greater than the intent hours?
    if(tree_hours>intent_hours){
        //Yes, show remaining tree hours:
        $('#child-hours').html('<i class="fas fa-clock"></i> '+c_js_hours(tree_hours-intent_hours)+' in <i class="fas fa-sitemap"></i> sub-tree');
    } else {
        //Nope, clear this field:
        $('#child-hours').html('');
    }


    //Only show unlink button if not level 1
    if(level==1){
        $('.unlink-intent').addClass('hidden');
    } else {
        $('.unlink-intent').removeClass('hidden');
    }


    //Make the frame visible:
    $('.fixed-box, .ajax-frame').addClass('hidden');
    $("#modifybox").removeClass('hidden').hide().fadeIn();

    //We might need to scroll:
    if(is_compact){
        $('.main-panel').animate({
            scrollTop:9999
        }, 150);
    }

}


function c_save_modify(){

    //Validate that we have all we need:
    if($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('intent-id'))) {
        //Oops, this should not happen!
        return false;
    }

    //Prepare data to be modified for this intent:
    var modify_data = {
        c_id:parseInt($('#modifybox').attr('intent-id')),
        level:parseInt($('#modifybox').attr('level')),
        c_outcome:$('#c_outcome').val(),
        c_time_estimate:parseFloat(parseInt($('#c_time_estimate').val())/60),
        c_cost_estimate:parseFloat($('#c_cost_estimate').val()),
        c_require_url_to_complete:( document.getElementById('c_require_url_to_complete').checked ? 1 : 0),
        c_require_notes_to_complete:( document.getElementById('c_require_notes_to_complete').checked ? 1 : 0),
        c_is_any:parseInt($('input[name=c_is_any]:checked').val()),
        apply_recurively:( document.getElementById('apply_recurively').checked ? 1 : 0),
        c_status:parseInt($('#c_status').val()),
        c_points:parseInt($('#c_points').val()),
        c_trigger_statements:$('#c_trigger_statements').val().replace(/\"/g, ""), //Remove double quotes
    };

    //Take a snapshot of the current status:
    var original_c_status = parseInt($('.c_outcome_'+modify_data['c_id']).attr('c_status'));

    //Show spinner:
    $('.save_intent_changes').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/intents/c_save_settings", modify_data , function(data) {

        if(data.status){

            //Update variables:
            $(".c_outcome_"+modify_data['c_id']).html(modify_data['c_outcome']);
            $('.c_outcome_'+modify_data['c_id']).attr('c_require_url_to_complete'  , modify_data['c_require_url_to_complete']);
            $('.c_outcome_'+modify_data['c_id']).attr('c_require_notes_to_complete', modify_data['c_require_notes_to_complete']);
            $('.c_outcome_'+modify_data['c_id']).attr('c_is_any'                   , modify_data['c_is_any']);
            $('.c_outcome_'+modify_data['c_id']).attr('c_cost_estimate'            , modify_data['c_cost_estimate']);

            $('.c_outcome_'+modify_data['c_id']).attr('c_status'                   , modify_data['c_status']);
            $('.c_outcome_'+modify_data['c_id']).attr('c_points'                   , modify_data['c_points']);
            $('.c_outcome_'+modify_data['c_id']).attr('c_trigger_statements'       , modify_data['c_trigger_statements']);

            //Adjust UI Icons:
            if(modify_data['c_is_any']){
                $('.c_is_any_icon'+modify_data['c_id']).addClass('fa-code-merge').removeClass('fa-sitemap');
            } else {
                $('.c_is_any_icon'+modify_data['c_id']).removeClass('fa-code-merge').addClass('fa-sitemap');
            }

            //has status updated? If so update the UI:
            if(original_c_status!=modify_data['c_status']){
                //Update status:
                $('.c_status_'+modify_data['c_id']).html(data.status_ui);
            }

            //Update trigger statements:
            if($('.c_trigger_statements_'+modify_data['c_id']).length){
                //This is the top intent that's loaded, update expanded trigger UI:
                $(".c_trigger_statements_"+modify_data['c_id']).html(nl2br(modify_data['c_trigger_statements']));
            } else {
                //This is a level 2+ intent, let's update the tooltip UI:
                if(modify_data['c_trigger_statements'].length>0){
                    $(".c_outcome_"+modify_data['c_id']).addClass('has-desc').attr('data-toggle', 'tooltip').attr('data-original-title', modify_data['c_trigger_statements']);
                } else {
                    $(".c_outcome_"+modify_data['c_id']).removeClass('has-desc').attr('data-toggle', '').attr('data-original-title', '');
                }
            }


            //Update other UI elements:
            $(".ui_c_points_"+modify_data['c_id']).html((modify_data['c_points']>0 ? '<i class="fas fa-weight" style="margin-right: 2px;"></i>'+modify_data['c_points'] : ''));
            $(".ui_c_require_notes_to_complete_"+modify_data['c_id']).html((modify_data['c_require_notes_to_complete']>0 ? '<i class="fas fa-pencil"></i>' : ''));
            $(".ui_c_require_url_to_complete_"+modify_data['c_id']).html((modify_data['c_require_url_to_complete']>0 ? '<i class="fas fa-link"></i>' : ''));
            $(".ui_c_cost_estimate_"+modify_data['c_id']).html((modify_data['c_cost_estimate']>0 ? '<i class="fas fa-usd-circle" style="margin-right: 2px;"></i>'+modify_data['c_cost_estimate'] : ''));


            //Adjust hours if needed:
            adjust_js_ui(modify_data['c_id'], modify_data['level'], modify_data['c_time_estimate']);

            //Update UI to confirm with user:
            $('.save_intent_changes').html(data.message).hide().fadeIn();

            //Reload Tooltip again:
            $('[data-toggle="tooltip"]').tooltip();

            //What's the final action?
            setTimeout(function() {
                if(modify_data['apply_recurively'] && data.children_updated>0){
                    //Refresh page soon to show new status for children:
                    window.location = "/intents/"+c_top_id;
                } else {
                    //Hide the editor & saving results:
                    $('.save_intent_changes').hide();
                }
            }, 610);

        } else {
            //Ooops there was an error!
            $('.save_intent_changes').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> '+data.message+'</span>').hide().fadeIn();
        }
    });

}


function c_delete(){
    var r = confirm("Are you sure you want to PERMANENTLY delete this intent and all its associated Links, Messages, etc...?");
    if (!(r == true)) {
        return false;
    }
    var c_id = ( $('#modifybox').hasClass('hidden') ? 0 : parseInt($('#modifybox').attr('intent-id')) );
    window.location = "/intents/hard_delete/"+c_id;
}



function c_js_new(c_id,next_level,link_c_id=0){

    //If link_c_id>0 this means we're only linking
    //Set variables mostly based on level:

    if(next_level==2){
        var sort_handler = ".is_level2_sortable";
        var sort_list_id = "list-c-"+c_top_id;
        var input_field = $('#addintent-c-'+c_id);
    } else if(next_level==3){
        var sort_handler = ".is_level3_sortable";
        var sort_list_id = "list-cr-"+$('.intent_line_'+c_id).attr('data-link-id');
        var input_field = $('#addintent-cr-'+$('.intent_line_'+c_id).attr('data-link-id'));
    } else {
        alert('Invalid next_level value ['+next_level+']');
        return false;
    }


    var intent_name = input_field.val();

    if(!link_c_id && intent_name.length<1){
        alert('Error: Missing Intent. Try Again...');
        input_field.focus();
        return false;
    }

    //Set processing status:
    add_to_list(sort_list_id, sort_handler, '<div id="temp'+next_level+'" class="list-group-item"><img src="/img/round_load.gif" class="loader" /> Adding... </div>');

    //Update backend:
    $.post("/intents/c_new", {c_id:c_id, c_outcome:intent_name, next_level:next_level, link_c_id:link_c_id}, function(data) {

        //Remove loader:
        $( "#temp"+next_level ).remove();

        if(data.status){

            //Add new
            add_to_list(sort_list_id,sort_handler,data.html);

            //Re-adjust sorting:
            c_load_sort(c_id,next_level);

            //Remove potential grey class:
            $('.tree-badge-'+c_id).removeClass('grey');


            if(next_level==2){

                //Adjust the Task count:
                c_save_sort(0,2);

                //Re-adjust sorting for inner Steps:
                c_load_sort(data.c_id,3);

                //Load search again:
                c_load_level3_search();

            } else {
                //Adjust Step sorting:
                c_save_sort(c_id,next_level);
            }

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Adjust time:
            adjust_js_ui(data.c_id, next_level, data.c__tree_max_hours, data.adjusted_c_count, 0, 1);

        } else {
            //Show errors:
            alert('ERROR: '+data.message);
        }

        //Empty Input:
        input_field.focus();

    });

    //Prevent form submission:
    return false;
}