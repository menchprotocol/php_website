<?php
$udata = $this->session->userdata('user');
if(isset($orphan_cs)){
    $c['c_id'] = 0;
}
?>

<input type="hidden" id="c_id" value="<?= $c['c_id'] ?>" />
<style> .breadcrumb li { display:block; } </style>
<script>

    //Set global variables:
    var is_compact = (is_mobile() || $(window).width()<767);

    function echo_hours(dbl_hour){
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
            $('.fixed-box').addClass('phone-2nd');
            $('.iphone-x').addClass('iphone-2nd');

        } else {
            //Make editing frames Sticky for scrolling longer lists
            $(".main-panel").scroll(function() {
                var top_position = $(this).scrollTop();
                clearTimeout($.data(this, 'scrollTimer'));
                $.data(this, 'scrollTimer', setTimeout(function() {
                    $("#iphonex").css('top',(top_position-0)); //PX also set in style.css for initial load
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
                    load_c_messages(hash_parts[1]);
                } else if(hash_parts[0]=='modify'){
                    load_c_modify(hash_parts[1],hash_parts[2]);
                }
            }
        }


        //Watch the expand/close all buttons:
        $('#task_view .expand_all').click(function (e) {
            $( ".list-is-outbound .is_level2_sortable" ).each(function() {
                ms_toggle($( this ).attr('data-link-id'),1);
            });
        });
        $('#task_view .close_all').click(function (e) {
            $( ".list-is-outbound .is_level2_sortable" ).each(function() {
                ms_toggle($( this ).attr('data-link-id'),0);
            });
        });

        //Load Sortable:
        load_c_sort($("#c_id").val(),"2");


        //Activate sorting for Steps:
        if($('.step-group').length){

            $( ".step-group" ).each(function() {

                var intent_id = $( this ).attr('intent-id');

                //Load sorting:
                load_c_sort(intent_id,"3");

                //Load time:
                $('.t_estimate_'+intent_id).text(echo_hours($('.t_estimate_'+intent_id+':first').attr('tree-hours')));

            });

            if($('.is_level3_sortable').length){
                //Goo through all Steps:
                $( ".is_level3_sortable" ).each(function() {
                    var intent_id = $(this).attr('intent-id');
                    if(intent_id){
                        //Load time:
                        $('.t_estimate_'+intent_id).text(echo_hours($('.t_estimate_'+intent_id+':first').attr('tree-hours')));
                    }
                });
            }
        }


        $( "#dir_handle" ).click(function() {
            new_intent(<?= $c['c_id'] ?>, 2);
        });


        //Load Algolia:
        $(".intentadder-level-2").on('autocomplete:selected', function(event, suggestion, dataset) {

            new_intent($(this).attr('intent-id'), 2, suggestion.c_id);

        }).autocomplete({ hint: false, keyboardShortcuts: ['a'] }, [{

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
                        return '<a href="javascript:new_intent(\''+$(".intentadder-level-2").attr('intent-id')+'\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-hashtag"></i> '+data.query+'</a>';
                    }
                },
                empty: function (data) {
                    return '<a href="javascript:new_intent(\''+$(".intentadder-level-2").attr('intent-id')+'\',2)" class="suggestion"><span><i class="fas fa-plus-circle"></i> Create </span> <i class="fas fa-hashtag"></i> '+data.query+'</a>';
                },
            }
        }]).keypress(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if ((code == 13) || (e.ctrlKey && code == 13)) {
                return new_intent($(this).attr('intent-id'),2);
            }
        });

        load_level3_search();

    });

    function load_level3_search(){

        $(".intentadder-level-3").on('autocomplete:selected', function(event, suggestion, dataset) {

            new_intent($(this).attr('intent-id'), 3, suggestion.c_id);

        }).autocomplete({ hint: false, keyboardShortcuts: ['a'] }, [{

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
                        return '<a href="javascript:new_intent(\''+$(".intentadder-level-3").attr('intent-id')+'\',3)" class="suggestion"><span><i class="fas fa-plus-circle"></i></span> '+data.query+'</a>';
                    }
                },
            }
        }]).keypress(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if ((code == 13) || (e.ctrlKey && code == 13)) {
                return new_intent($(this).attr('intent-id'),3);
            }
        });

    }


    function c_sort(c_id,level){

        if(level==2){
            var s_element = "list-c-<?= $c['c_id'] ?>";
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

                //Is the Outbound rank correct? Check DB value:
                var db_rank = parseInt($('.c_outcome_'+c_id).attr('outbound-rank'));

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
            c_id = parseInt($('#c_id').val());
        }

        //It might be zero for lists that have jsut been emptied
        if(sort_rank>0 && c_id){
            //Update backend:
            $.post("/intents/c_sort", { c_id:c_id, new_sort:new_sort }, function(data) {
                //Update UI to confirm with user:
                if(!data.status){
                    //There was some sort of an error returned!
                    alert('ERROR: '+data.message);
                }
            });
        }
    }


    function load_c_sort(c_id,level){

        if(level==2){
            var s_element = "list-c-<?= $c['c_id'] ?>";
            var s_draggable = ".is_level2_sortable";
        } else if(level==3){
            var s_element = "list-cr-"+$('.intent_line_'+c_id).attr('data-link-id');
            var s_draggable = ".is_level3_sortable";
        } else {
            //Should not happen!
            return false;
        }


        var theobject = document.getElementById(s_element);
        var settings = {
            animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
            draggable: s_draggable, // Specifies which items inside the element should be sortable
            handle: ".fa-bars", // Restricts sort start click/touch to the specified element
            onUpdate: function (evt/**Event*/){
                c_sort(c_id,level);
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
                        var intent_count = parseInt($('.outbound-counter-'+inputs.c_id+':first').text());

                        if(!(step_hours==0)){
                            //Remove from old one:
                            var from_hours_new = parseFloat($('.t_estimate_'+inputs.from_c_id+':first').attr('tree-hours'))-step_hours;
                            $('.t_estimate_'+inputs.from_c_id).attr('tree-hours',from_hours_new).text(echo_hours(from_hours_new));
                            $('.outbound-counter-'+inputs.from_c_id).text( parseInt($('.outbound-counter-'+inputs.from_c_id+':first').text()) - intent_count );

                            //Add to new:
                            var to_hours_new = parseFloat($('.t_estimate_'+inputs.to_c_id+':first').attr('tree-hours'))+step_hours;
                            $('.t_estimate_'+inputs.to_c_id).attr('tree-hours',to_hours_new).text(echo_hours(to_hours_new));
                            $('.outbound-counter-'+inputs.to_c_id).text( parseInt($('.outbound-counter-'+inputs.to_c_id+':first').text()) + intent_count );
                        }

                        //Update sorting for both lists:
                        c_sort(inputs.from_c_id,"3");
                        c_sort(inputs.to_c_id,"3");

                    }
                });
            };
        }
        var sort = Sortable.create( theobject , settings );
    }




    function load_c_messages(c_id){

        //Make the frame visible:
        $("#iphonex").removeClass('hidden').hide().fadeIn();
        $('#modifybox').addClass('hidden');
        var handler = $( "#iphone-screen" );

        //Define the top menu that would not change:
        $('#iphonex').attr('intent-id',c_id);

        //Define standard phone header:
        var top_menu = '<div class="ix-top">\n' +
            '<span class="ix-top-left" data-toggle="tooltip" title="PST Time" data-placement="bottom"><?= date("H:i") ?></span>\n' +
            '<span class="ix-top-right">\n' +
            '<i class="fas fa-wifi"></i>\n' +
            '<i class="fas fa-battery-full"></i>\n' +
            '</span>\n' +
            '</div>';

        //Show tem loader:
        handler.html('<div style="text-align:center; padding-top:89px; padding-bottom:89px;"><img src="/img/round_load.gif" class="loader" /></div>');

        //We might need to scroll:
        if(is_compact){
            $('.main-panel').animate({
                scrollTop:9999
            }, 150);
        }

        //Load the frame:
        $.post("/intents/load_c_messages", {

            c_id:c_id,

        }, function(data) {

            //Empty Inputs Fields if success:
            handler.html(top_menu+data);

            //SHow inner tooltips:
            $('[data-toggle="tooltip"]').tooltip();

        });
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
                .text(echo_hours(new_tree_hours));

            if(!apply_to_tree){
                $('.t_estimate_'+c_id).attr('intent-hours',new_hours).text(echo_hours(new_tree_hours));
            }
        }


        //Adjust inbound counters, if any:
        if(!(intent_deficit_count==0)){
            //See how many inbounds we have:
            $('.inb-counter').each(function(){
                $(this).text( parseInt($(this).text()) + intent_deficit_count );
            });
        }

        if(level>=2){

            //Adjust the parent level hours:
            var c_inbound_id = parseInt($('.intent_line_'+c_id).attr('parent-intent-id'));
            var c_inbound_tree_hours = parseFloat($('.t_estimate_'+c_inbound_id+':first').attr('tree-hours'));
            var new_c_inbound_tree_hours = c_inbound_tree_hours + intent_deficit_hours;

            if(!(intent_deficit_count==0)){
                $('.outbound-counter-'+c_inbound_id).text( parseInt($('.outbound-counter-'+c_inbound_id+':first').text()) + intent_deficit_count );
            }

            if(!(intent_deficit_hours==0)){
                //Update Hours (Either level 1 or 2):
                $('.t_estimate_'+c_inbound_id)
                    .attr('tree-hours', new_c_inbound_tree_hours)
                    .text(echo_hours(new_c_inbound_tree_hours));
            }

            if(level==3){
                //Adjust top level intent as well:
                var top_c_id = parseInt($('.intent_line_'+c_inbound_id).attr('parent-intent-id'));
                var top_c_tree_hours = parseFloat($('.t_estimate_'+top_c_id+':first').attr('tree-hours'));
                var new_top_c_tree_hours = top_c_tree_hours + intent_deficit_hours;


                if(!(intent_deficit_count==0)){
                    $('.outbound-counter-'+top_c_id).text( parseInt($('.outbound-counter-'+top_c_id+':first').text()) + intent_deficit_count );
                }

                if(!(intent_deficit_hours==0)){
                    //Update Hours:
                    $('.t_estimate_'+top_c_id)
                        .attr('tree-hours', new_top_c_tree_hours)
                        .text(echo_hours(new_top_c_tree_hours));
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

        var c_inbound_id = parseInt($('#cr_'+cr_id).attr('parent-intent-id'));
        var level = parseInt($('#cr_'+cr_id).attr('intent-level')); //Either 2 or 3 (Cannot unlink level 1)
        var r = confirm("Unlink \""+$('.c_outcome_input').val()+"\"?\n(Intent will remain accessible)");

        if (r == true) {
            //Load parent intents:
            $.post("/intents/c_unlink", {c_id:c_id, cr_id:cr_id} , function(data) {
                if(data.status){

                    //Adjust hours:
                    adjust_js_ui(c_id, level, 0, data.adjusted_c_count, 1);

                    //Remove from UI:
                    $('#cr_' + cr_id).html('<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>');

                    //Disapper in a while:
                    setTimeout(function () {
                        //Hide the editor & saving results:
                        $('#cr_' + cr_id).fadeOut();
                        setTimeout(function () {
                            //Hide the editor & saving results:
                            $('#cr_' + cr_id).remove();
                            //Hide editing box:
                            $('#modifybox').addClass('hidden');

                            //Resort all Tasks to illustrate changes on UI:
                            c_sort(c_inbound_id,level);

                        }, 377);
                    }, 1597);

                } else {
                    alert('ERROR: '+data.message);
                }
            });
        } else {
            return false;
        }
    }


    function load_c_modify(c_id, cr_id){

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

        $('.c_outcome_input').val($(".c_outcome_"+c_id+":first").text());
        $('#c_time_estimate').val(Math.round(intent_hours*60));
        $('#c_cost_estimate').val(parseFloat($('.c_outcome_'+c_id).attr('c_cost_estimate')));

        $("input[name=c_is_any][value='"+$('.c_outcome_'+c_id).attr('c_is_any')+"']").prop("checked",true);
        document.getElementById("c_require_url_to_complete").checked = parseInt($('.c_outcome_'+c_id).attr('c_require_url_to_complete'));
        document.getElementById("c_require_notes_to_complete").checked = parseInt($('.c_outcome_'+c_id).attr('c_require_notes_to_complete'));

        //Are the tree hours greater than the intent hours?
        if(tree_hours>intent_hours){
            //Yes, show remaining tree hours:
            $('#child-hours').html('<i class="fas fa-clock"></i> '+echo_hours(tree_hours-intent_hours)+' in <i class="fas fa-sitemap"></i> sub-tree');
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
        $("#modifybox").removeClass('hidden').hide().fadeIn();
        $('#iphonex').addClass('hidden');

        //We might need to scroll:
        if(is_compact){
            $('.main-panel').animate({
                scrollTop:9999
            }, 150);
        }

    }


    function save_c_modify(){

        //Validate that we have all we need:
        if($('#modifybox').hasClass('hidden') || !parseInt($('#modifybox').attr('intent-id'))) {
            //Oops, this should not happen!
            return false;
        }

        //Prepare data to be modified for this intent:
        var modify_data = {
            c_id:parseInt($('#modifybox').attr('intent-id')),
            level:parseInt($('#modifybox').attr('level')),
            c_outcome:$('.c_outcome_input').val(),
            c_time_estimate:parseFloat(parseInt($('#c_time_estimate').val())/60),
            c_cost_estimate:parseFloat($('#c_cost_estimate').val()),
            c_require_url_to_complete:( document.getElementById('c_require_url_to_complete').checked ? 1 : 0),
            c_require_notes_to_complete:( document.getElementById('c_require_notes_to_complete').checked ? 1 : 0),
            c_is_any:parseInt($('input[name=c_is_any]:checked').val()),
        };

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

                //Adjust UI Icons:
                if(modify_data['c_is_any']){
                    $('.c_is_any_icon'+modify_data['c_id']).addClass('fa-code-merge').removeClass('fa-sitemap');
                } else {
                    $('.c_is_any_icon'+modify_data['c_id']).removeClass('fa-code-merge').addClass('fa-sitemap');
                }

                //Adjust hours:
                adjust_js_ui(modify_data['c_id'], modify_data['level'], modify_data['c_time_estimate']);

                //Update UI to confirm with user:
                $('.save_intent_changes').html(data.message).hide().fadeIn();

                //Disapper in a while:
                setTimeout(function() {
                    //Hide the editor & saving results:
                    $('.save_intent_changes').hide();
                }, 1000);

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



    function new_intent(c_id,next_level,link_c_id=0){

        //If link_c_id>0 this means we're only linking
        //Set variables mostly based on level:

        if(next_level==2){
            var sort_handler = ".is_level2_sortable";
            var sort_list_id = "list-c-"+$('#c_id').val();
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
                load_c_sort(c_id,next_level);

                //Remove potential grey class:
                $('.tree-badge-'+c_id).removeClass('grey');


                if(next_level==2){

                    //Adjust the Task count:
                    c_sort(0,2);

                    //Re-adjust sorting for inner Steps:
                    load_c_sort(data.c_id,3);

                    //Load search again:
                    load_level3_search();

                } else {
                    //Adjust Step sorting:
                    c_sort(c_id,next_level);
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

</script>






<div class="row">
    <div class="col-xs-6 cols">
        <?php


        if(isset($orphan_cs)){

            echo '<div id="bootcamp-objective" class="list-group">';
            foreach($orphan_cs as $oc){
                echo echo_c($oc,1);
            }
            echo '</div>';

        } else {

            if(in_array($c['c_id'],$this->config->item('universal_intents'))) {
                //This is the "Get to know how Mench Personal Assistant works" tree
                //which is recommended to all new students who have not subscribed to it
                //Let the admin know about this:
                //echo '<div class="alert alert-info" role="alert" style="margin-top: 0;"><i class="fas fa-globe"></i> This is a universal intent that is automatically recommended to students</div>';
            }

            if(in_array($c['c_id'],$this->config->item('onhold_intents'))) {
                //This is the "Get to know how Mench Personal Assistant works" tree
                //which is recommended to all new students who have not subscribed to it
                //Let the admin know about this:
                echo '<div class="alert alert-info" role="alert" style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> This intent is on-hold & not accessible to students</div>';
            }

            echo '<h5 class="badge badge-h"><i class="fas fa-sign-in-alt"></i> <span class="li-inbound-count inbound-counter-'.$c['c_id'].'">'.count($c__inbounds).'</span> Ins</h5>';

            if(count($c__inbounds)>0){
                echo '<div class="list-group list-level-2">';
                foreach($c__inbounds as $sub_intent){
                    echo echo_c($sub_intent, 2, 0, true);
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-info" role="alert" style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> No inbound intents linked yet</div>';
            }



            echo '<h5 class="badge badge-h"><i class="fas fa-hashtag"></i> Intent</h5>';
            echo '<div id="bootcamp-objective" class="list-group">';
                echo echo_c($c,1);
            echo '</div>';








            //Expand/Contract buttons
            echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-sign-out-alt rotate90"></i> <span class="li-outbound-count outbound-counter-'.$c['c_id'].'">'.$c['c__tree_all_count'].'</span> Outs</h5>';
            echo '<div id="task_view" style="padding-left:8px; display: inline-block;">';
            echo '<i class="fas fa-plus-square expand_all" style="font-size: 1.2em;"></i> &nbsp;';
            echo '<i class="fas fa-minus-square close_all" style="font-size: 1.2em;"></i>';
            echo '</div>';
            if($orphan_c_count>0){
                echo '<div style="padding-left:8px; display: inline-block;"><a href="/intents/orphan">'.$orphan_c_count.' Orphans &raquo;</a></div>';
            }


            echo '<div id="list-c-'.$c['c_id'].'" class="list-group list-is-outbound list-level-2">';
            foreach($c['c__child_intents'] as $sub_intent){
                echo echo_c($sub_intent, 2, $c['c_id']);
            }
            ?>
            <div class="list-group-item list_input grey-block">
                <div class="input-group">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control intentadder-level-2 algolia_search bottom-add"  maxlength="70" intent-id="<?= $c['c_id'] ?>" id="addintent-c-<?= $c['c_id'] ?>" placeholder="Add #Intent"></div>
                    <span class="input-group-addon" style="padding-right:8px;">
                                        <span id="dir_handle" data-toggle="tooltip" title="or press ENTER ;)" data-placement="top" class="badge badge-primary pull-right" style="cursor:pointer; margin: 1px 3px 0 6px;">
                                            <div><i class="fas fa-plus"></i></div>
                                        </span>
                                    </span>
                </div>
            </div>
            <?php
            echo '</div>';

        }
        ?>

    </div>


    <div class="col-xs-6 cols" id="iphonecol">


        <div id="modifybox" class="fixed-box hidden" intent-id="0" intent-link-id="0" level="0">

            <h5 class="badge badge-h"><i class="fas fa-cog"></i> Modify Intent</h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;"><a href="javascript:void(0)" onclick="$('#modifybox').addClass('hidden')"><i class="fas fa-times-circle"></i></a></div>

            <div class="grey-box">
                <div>
                    <div class="title"><h4><i class="fas fa-bullseye-arrow"></i> Target Outcome <span id="hb_598" class="help_button" intent-id="598"></span></h4></div>
                    <div class="help_body maxout" id="content_598"></div>

                    <div class="form-group label-floating is-empty">
                        <div class="input-group border">
                            <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;">To</span>
                            <input style="padding-left:0;" type="text" id="c_outcome" maxlength="70" value="" class="form-control c_outcome_input algolia_search">
                        </div>
                    </div>
                </div>


                <div style="margin-top:20px;">
                    <div class="title"><h4><i class="fas fa-shield-check"></i> Completion Settings</h4></div>
                    <div class="form-group label-floating is-empty">

                        <div class="radio" style="display:inline-block; border-bottom:1px dotted #999; margin-right:10px; margin-top: 0 !important;" data-toggle="tooltip" title="Intent is completed when ALL outbound intents are marked as complete" data-placement="right">
                            <label>
                                <input type="radio" name="c_is_any" value="0" />
                                <i class="fas fa-sitemap"></i> All Outs
                            </label>
                        </div>
                        <div class="radio" style="display: inline-block; border-bottom:1px dotted #999; margin-top: 0 !important;" data-toggle="tooltip" title="Intent is completed when ANY outbound intent is marked as complete" data-placement="right">
                            <label>
                                <input type="radio" name="c_is_any" value="1" />
                                <i class="fas fa-code-merge"></i> Any Out
                            </label>
                        </div>

                    </div>

                    <div class="form-group label-floating is-empty">
                        <div class="checkbox is_task">
                            <label style="display: block; font-size: 0.9em !important; margin-left:8px;"><input type="checkbox" id="c_require_notes_to_complete" /><i class="fas fa-pencil"></i> Require a written response</label>
                            <label style="display: block; font-size: 0.9em !important; margin-left:8px;"><input type="checkbox" id="c_require_url_to_complete" /><i class="fas fa-link"></i> Require URL in response</label>
                        </div>
                    </div>
                </div>


                <div style="margin-top:20px;">
                    <div class="title"><h4><i class="fas fa-box-check"></i> Completion Resources</h4></div>

                    <div class="form-group label-floating is-empty" style="max-width:150px;">
                        <div class="input-group border">
                            <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;"><i class="fas fa-clock"></i></span>
                            <input style="padding-left:0;" type="number" step="1" min="0" max="300" id="c_time_estimate" value="" class="form-control">
                            <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;">Minutes</span>
                        </div>
                    </div>
                    <div id="child-hours" style="margin-left:6px;"></div>

                    <div class="form-group label-floating is-empty" style="max-width:150px;">
                        <div class="input-group border">
                            <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;"><i class="fas fa-usd-circle"></i></span>
                            <input style="padding-left:0;" type="number" step="0.01" min="0" max="5000" id="c_cost_estimate" value="" class="form-control">
                            <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;">USD</span>
                        </div>
                    </div>
                </div>



                <table width="100%" style="margin-top:10px;">
                    <tr>
                        <td class="save-td"><a href="javascript:save_c_modify();" class="btn btn-primary">Save</a></td>
                        <td><span class="save_intent_changes"></span></td>
                        <td style="width:80px; text-align:right;">

                            <div><a href="javascript:c_unlink();" class="unlink-intent" data-toggle="tooltip" title="Only remove intent link while NOT deleting the intent itself" data-placement="left" style="text-decoration:none;"><i class="fas fa-unlink"></i> Unlink</a></div>

                            <?php if(array_key_exists(1281, $udata['u__inbounds'])){ ?>
                                <div><a href="javascript:c_delete();" data-toggle="tooltip" title="Delete intent AND remove all its links, messages & references" data-placement="left" style="text-decoration:none;"><i class="fas fa-trash-alt"></i> Delete</a></div>
                            <?php } ?>

                        </td>
                    </tr>
                </table>
            </div>

        </div>





        <div class="marvel-device iphone-x hidden" id="iphonex" intent-id="">
            <div style="font-size: 22px; margin: -5px 0 -20px 0; top: 0; right: 0px; position: absolute; z-index:9999999;"><a href="javascript:void(0)" onclick="$('#iphonex').addClass('hidden');$('#iphone-screen').html('');"><i class="fas fa-times"></i></a></div>
            <div class="notch">
                <div class="camera"></div>
                <div class="speaker"></div>
            </div>
            <div class="top-bar"></div>
            <div class="sleep"></div>
            <div class="bottom-bar"></div>
            <div class="volume"></div>
            <div class="overflow">
                <div class="shadow shadow--tr"></div>
                <div class="shadow shadow--tl"></div>
                <div class="shadow shadow--br"></div>
                <div class="shadow shadow--bl"></div>
            </div>
            <div class="inner-shadow"></div>
            <div class="screen" id="iphone-screen">
            </div>
        </div>


    </div>
</div>