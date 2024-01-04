

//Define some global variables:
var has_unsaved_changes = false; //Tracks source/idea modal edits



//Full Story
if(js_pl_id > 1 && js_e___30849[website_id]['m__message'].length>1){ //Any user other than Shervin

    console.log('Activated Recording for Org '+js_e___30849[website_id]['m__message'])
    window['_fs_debug'] = false;
    window['_fs_host'] = 'fullstory.com';
    window['_fs_script'] = 'edge.fullstory.com/s/fs.js';
    window['_fs_org'] = js_e___30849[website_id]['m__message'];
    window['_fs_namespace'] = 'FS';
    (function(m,n,e,t,l,o,g,y){
        if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
        g=m[e]=function(a,b,s){g.q?g.q.push([a,b,s]):g._api(a,b,s);};g.q=[];
        o=n.createElement(t);o.async=1;o.crossOrigin='anonymous';o.src='https://'+_fs_script;
        y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
        g.identify=function(i,v,s){g(l,{uid:i},s);if(v)g(l,v,s)};g.setUserVars=function(v,s){g(l,v,s)};g.event=function(i,v,s){g('event',{n:i,p:v},s)};
        g.anonymize=function(){g.identify(!!0)};
        g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
        g.log = function(a,b){g("log",[a,b])};
        g.consent=function(a){g("consent",!arguments.length||a)};
        g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
        g.clearUserCookie=function(){};
        g._w={};y='XMLHttpRequest';g._w[y]=m[y];y='fetch';g._w[y]=m[y];
        if(m[y])m[y]=function(){return g._w[y].apply(this,arguments)};
        g._v="1.2.0";
    })(window,document,window['_fs_namespace'],'script','user');

    if(js_pl_id>0){
        //https://help.fullstory.com/hc/en-us/articles/360020623294-FS-setUserVars-Recording-custom-user-data
        FS.identify(js_pl_id, {
            displayName: js_pl_name,
            uid: js_pl_id,
            profileURL: base_url+'/@'+js_pl_handle
        });
    }


}


jQuery.fn.sortElements = (function(){

    var sort = [].sort;

    return function(comparator, getSortable) {

        getSortable = getSortable || function(){return this;};

        var placements = this.map(function(){

            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,

                // Since the element itself will change position, we have
                // to have some way of storing it's original position in
                // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function() {

                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }

                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);

            };

        });

        return sort.call(this, comparator).each(function(i){
            placements[i].call(getSortable.call(this));
        });

    };

})();

function htmlentitiesjs(rawStr){
    return rawStr.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
        return '&#'+i.charCodeAt(0)+';';
    });
}


function mass_apply_preview(apply_id, s__id){

    //Select first:
    var first_id = $('#modal'+apply_id+' .mass_action_toggle option:first').val();
    $('.mass_action_item').addClass('hidden');
    $('.mass_id_' + first_id ).removeClass('hidden');
    $('#modal'+apply_id+' .mass_action_toggle').val(first_id);
    $('#modal'+apply_id+' input[name="s__id"]').val(s__id);
    $('#modal'+apply_id).modal('show');

    //Load Ppeview:
    $('#modal'+apply_id+' .mass_apply_preview').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading...');
    $.post("/x/mass_apply_preview", {
        apply_id: apply_id,
        s__id: s__id
    }, function (data) {
        $('#modal'+apply_id+' .mass_apply_preview').html(data);
    });

}


function load_editor(){

    $('.mass_action_toggle').change(function () {
        $('.mass_action_item').addClass('hidden');
        $('.mass_id_' + $(this).val() ).removeClass('hidden');
    });

    if(parseInt(js_e___6404[12678]['m__message'])){

        $('.e_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            console.log('Yoooo');
            console.log(suggestion);
            $(this).val('@' + suggestion.s__handle);

        }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 's__type=12274' + search_and_filter,
                    hitsPerPage: js_e___6404[31112]['m__message'],
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            displayKey: function (suggestion) {
                return '@' + suggestion.s__handle;
            },
            templates: {
                suggestion: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                empty: function (data) {
                    return '<div class="main__title"><i class="fas fa-exclamation-circle"></i> No Sources Found</div>';
                },
            }

        }]);

        $('.i_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('#' + suggestion.s__handle);

        }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 's__type=12273' + search_and_filter,
                    hitsPerPage: js_e___6404[31112]['m__message'],
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            displayKey: function (suggestion) {
                return '#' + suggestion.s__handle;
            },
            templates: {
                suggestion: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                empty: function (data) {
                    return '<div class="main__title"><i class="fas fa-exclamation-circle"></i> No Ideas Found</div>';
                },
            }
        }]);

    }
}


function view_s__title(suggestion){
    return htmlentitiesjs( suggestion._highlightResult && suggestion._highlightResult.s__title.value ? suggestion._highlightResult.s__title.value : suggestion.s__title );
}


function view_s_js_line(suggestion){
    return '<span class="icon-block">'+ view_cover_js(suggestion.s__cover) +'</span><span class="main__title">' + view_s__title(suggestion) + '</span><span class="grey">&nbsp;' + ( suggestion.s__type==12273 ? '/' : '@' ) + suggestion.s__handle + '</span>';
}

function view_s_js_cover(x__type, suggestion, action_id){

    if(!js_n___26010.includes(x__type)){
        alert('Missing type in JS UI');
        return false;
    }

    var background_image = '';
    var icon_image = '';

    if(suggestion.s__cover && suggestion.s__cover.length){
        if(validURL(suggestion.s__cover)){
            background_image = 'style="background-image:url(\''+suggestion.s__cover+'\')"';
        } else {
            icon_image = view_cover_js(suggestion.s__cover);
        }
    }

    //Return appropriate UI:
    if(x__type==26011){
        //Mini Coin
        return '<div title="ID '+suggestion.s__id+'" class="card_cover contrast_bg mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="'+suggestion.s__url+'" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="'+suggestion.s__url+'" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==26012){
        //Link Idea
        return '<div title="ID '+suggestion.s__id+'" class="card_cover contrast_bg mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="i__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="i__add('+action_id+', '+suggestion.s__id+')" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==26013){
        //Link Source
        return '<div title="ID '+suggestion.s__id+'" class="card_cover contrast_bg mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    }

}
function view_s_mini_js(s__cover,s__title){
    return '<span class="block-icon" title="'+s__title+'">'+ view_cover_js(s__cover) +'</span>';
}


function fetch_int_val(object_name){
    return ( $(object_name).length ? parseInt($(object_name).val()) : 0 );
}

function toggle_headline(x__type){

    var x__down = 0;
    var x__right = 0;
    var focus_card = fetch_int_val('#focus_card');
    if(focus_card==12273){
        x__right = fetch_int_val('#focus_id');
    } else if (focus_card==12274){
        x__down = fetch_int_val('#focus_id');
    }

    if($('.headline_title_' + x__type+' .icon_26008').hasClass('hidden')){

        //Currently open, must now be closed:
        var action_id = 26008; //Close
        $('.headline_title_' + x__type+ ' .icon_26008').removeClass('hidden');
        $('.headline_title_' + x__type+ ' .icon_26007').addClass('hidden');
        $('.headline_body_' + x__type).addClass('hidden');

        if (x__type==6255){
            $('.navigate_12273').removeClass('active');
        }

    } else {

        //Close all other opens:
        $('.headlinebody').addClass('hidden');
        $('.headline_titles .icon_26007').addClass('hidden');
        $('.headline_titles .icon_26008').removeClass('hidden');

        //Currently closed, must now be opened...
        var action_id = 26007; //Open
        $('.headline_title_' + x__type+ ' .icon_26007').removeClass('hidden');
        $('.headline_title_' + x__type+ ' .icon_26008').addClass('hidden');
        $('.headline_body_' + x__type).removeClass('hidden');

        if (x__type==6255){
            $('.navigate_12273').addClass('active');
        }

        //Scroll To:
        $('html, body').animate({
            scrollTop: $('.headline_body_' + x__type).offset().top
        }, 13);

    }

    //Log Transaction:
    x_create({
        x__creator: js_pl_id,
        x__type: action_id,
        x__up: x__type,
        x__down: x__down,
        x__right: x__right,
    });
}


function sort_e_load(x__type) {

    var sort_item_count = parseInt($('.headline_body_' + x__type).attr('read-counter'));
    console.log('Started Source Sorting for @'+x__type+' Counting: '+sort_item_count)

    if(!js_n___13911.includes(x__type)){
        //Does not support sorting:
        console.log('Not sortable')
        return false;
    } else if(sort_item_count<1 || sort_item_count>parseInt(js_e___6404[11064]['m__message'])){
        console.log('Not countable')
        return false;
    }

    setTimeout(function () {
        var theobject = document.getElementById("list-in-"+x__type);
        if (!theobject) {
            //due to duplicate ideas belonging in this idea:
            console.log('No object')
            return false;
        }

        //Show sort icon:
        console.log('Completed Loading Sorting for @'+x__type)
        $('.sort_e_grab').removeClass('hidden');

        var sort = Sortable.create(theobject, {
            animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
            draggable: ".coinface-12274", // Specifies which items inside the element should be sortable
            handle: ".sort_e_grab", // Restricts sort start click/touch to the specified element
            onUpdate: function (evt/**Event*/) {
                sort_e_save(x__type);
            }
        });
    }, 377);

}


function toggle_pills(x__type){

    focus_card = x__type;
    var x__down = 0;
    var x__right = 0;
    var focus_card = fetch_int_val('#focus_card');

    if(focus_card==12273){
        x__right = fetch_int_val('#focus_id');
    } else if (focus_card==12274){
        x__down = fetch_int_val('#focus_id');
    }

    //Toggle view
    $('.xtypetitle').addClass('hidden');
    $('.xtypetitle_'+x__type).removeClass('hidden');


    if($('.thepill' + x__type+' .nav-link').hasClass('active')){

        var action_id = 26008; //Close

        //Hide all elements
        $('.nav-link').removeClass('active');
        $('.headlinebody').addClass('hidden');

    } else {

        //Currently closed, must now be opened:
        var action_id = 26007; //Open

        //Hide all elements
        $('.nav-link').removeClass('active');
        $('.headlinebody').addClass('hidden');
        $('.thepill' + x__type+ ' .nav-link').addClass('active');
        $('.headline_body_' + x__type).removeClass('hidden');

        //Do we need to load data via ajax?
        if( !$('.headline_body_' + x__type + ' .tab_content').html().length ){
            $('.headline_body_' + x__type + ' .tab_content').html('<div class="center" style="padding-top: 13px;"><i class="far fa-yin-yang fa-spin"></i></div>');
            load_tab(x__type, false);
        }
    }

    //Log Transaction:
    x_create({
        x__creator: js_pl_id,
        x__type: action_id,
        x__up: x__type,
        x__down: x__down,
        x__right: x__right,
    });
}



function i_copy(i__id, do_recursive){
    //Go ahead and delete:
    $.post("/i/i_copy", {
        i__id:i__id,
        do_recursive:do_recursive
    }, function (data) {
        if(data.status){
            js_redirect('/~'+data.new_i__hashtag);
        } else {
            alert('ERROR:' + data.message);
        }
    });
}

function e_copy(e__id){
    //Go ahead and delete:
    $.post("/e/e_copy", {
        e__id:e__id
    }, function (data) {
        if(data.status){
            js_redirect('/@'+data.new_e__handle);
        } else {
            alert('ERROR:' + data.message);
        }
    });
}



var busy_loading = [];
var current_page = [];
function view_load_page(x__type) {

    if(busy_loading[x__type] && parseInt(busy_loading[x__type])>0){
        return false;
    }
    busy_loading[x__type] = 1;

    if(!current_page[x__type]){
        current_page[x__type] = 1;
    }

    var current_total_count = parseInt($('.headline_body_' + x__type).attr('read-counter')); //Total of that item
    var has_more_to_load = ( current_total_count > parseInt(fetch_int_val('#page_limit')) * current_page[x__type] );
    var e_list = '#list-in-'+x__type;
    var current_top_x__id = $( e_list + ' .card_cover ' ).first().attr('x__id');
    var top_element = $('.cover_x_'+current_top_x__id);
    var e_loader = '<div class="load-more"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading More...</div>';
    console.log(x__type+' PAGE #'+current_page[x__type]+' TOP X__ID ID '+current_top_x__id);

    if(!has_more_to_load){
        console.log('DONE LOADING: '+x__type+' PAGE #'+current_page[x__type]+' TOP X__ID ID '+current_top_x__id);
        return false;
    } else {
        console.log(x__type+' PAGE #'+current_page[x__type]+' TOP X__ID ID '+current_top_x__id);
    }


    current_page[x__type]++; //Now we can increment current page

    $(e_loader).insertAfter(e_list);
    $.post("/x/view_load_page", {
        focus_card: fetch_int_val('#focus_card'),
        focus_id: fetch_int_val('#focus_id'),
        x__type: x__type,
        current_page: current_page[x__type],
    }, function (data) {
        $('.load-more').remove();
        if(data.length){

            $(e_list).append(data);
            x_set_start_text();
            load_card_clickers();
            $('[data-toggle="tooltip"]').tooltip();

            if(current_page<=1){
                window.scrollTo({
                    top: (top_element.offset().top - 59),
                    behavior: 'instant',
                });
            }

        }
        busy_loading[x__type] = 0;
    });


}




function js_view_shuffle_message(e__id){
    var messages = js_e___12687[e__id]['m__message'].split("\n");
    if(messages.length==1){
        //Return message:
        return messages[0];
    } else {
        //Choose Random:
        return messages[Math.floor(Math.random()*messages.length)];
    }
}


function loadtab(x__type, tab_data_id){

    //Hide all tabs:
    $('.tab-group-'+x__type).addClass('hidden');
    $('.tab-nav-'+x__type).removeClass('active');

    //Show this tab:
    $('.tab-group-'+x__type+'.tab-data-'+tab_data_id).removeClass('hidden');
    $('.tab-nav-'+x__type+'.tab-head-'+tab_data_id).addClass('active');

}


var init_in_process = 0;
function x_remove(x__id, x__type, i__hashtag){

    if(init_in_process==x__id){
        return false;
    }
    init_in_process = x__id;

    var r = confirm("Remove idea #"+i__hashtag+"?");
    if (!(r==true)) {
        return false;
    }

    //Save changes:
    $.post("/x/x_remove", {
        x__id:x__id
    }, function (data) {
        //Update UI to confirm with member:
        if (!data.status) {

            //There was some sort of an error returned!
            alert(data.message);

        } else {

            adjust_counter(x__type, -1);

            //REMOVE BOOKMARK from UI:
            $('.cover_x_'+x__id).fadeOut();

            setTimeout(function () {

                //Delete from body:
                $('.cover_x_'+x__id).remove();

            }, 233);
        }
    });

    return false;
}


function x_create(add_fields){
    return false;
    return $.post("/x/x_create", add_fields);
}


function update__cover(new_cover){
    $('#modal31912 .save_e__cover').val( new_cover );
    update_cover_main(new_cover, '.demo_cover');
    has_unsaved_changes = true;
}
function image_cover(cover_preview, cover_apply, new_title){
    return '<a href="#preview_cover" onclick="update__cover(\''+cover_apply+'\')">' + view_s_mini_js(cover_preview, new_title) + '</a>';
}


function cover_upload(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.coverUpload').hasClass('dynamic_saving')) {
        return false;
    }

    $('#upload_results').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="main__title">UPLOADING...</span>');

    if (isAdvancedUpload) {

        var ajaxData = new FormData($('.coverUpload').get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $('.coverUpload').find('input[type="file"]').attr('name');
                if (typeof thename==typeof undefined || thename==false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('save_e__id', $('#modal31912 .save_e__id').val());

        $.ajax({
            url: '/x/cover_upload',
            type: $('.coverUpload').attr('method'),
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.coverUpload').removeClass('dynamic_saving');
            },
            success: function (data) {
                //Render new file:
                if(data.status){
                    $('#upload_results').html('');
                    update__cover(data.cdn_url);
                } else {
                    //Show error:
                    $('#upload_results').html(data.message);
                }
            },
            error: function (data) {
                //Show Error:
                $('#upload_results').html(data.responseText);
            }
        });
    } else {
        // ajax for legacy browsers
    }

}


function initiate_algolia(){
    $(".algolia_search").focus(function () {
        if(!algolia_index && parseInt(js_e___6404[12678]['m__message'])){
            //Loadup Algolia once:
            client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
            algolia_index = client.initIndex('alg_index');
        }
    });
}

function e_load_cover(x__type, e__id, counter, first_segment){

    if($('.coins_e_'+e__id+'_'+x__type).html().length){
        //Already loaded:
       return false;
    }

    $('.coins_e_'+e__id+'_'+x__type).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    $.post("/e/e_load_cover", {
        x__type:x__type,
        e__id:e__id,
        counter:counter,
        first_segment:first_segment,
    }, function (data) {
        $('.coins_e_'+e__id+'_'+x__type).html(data);
    });

}

function i_load_cover(x__type, i__id, counter, first_segment, current_e){

    if($('.coins_i_'+i__id+'_'+x__type).html().length){
        //Already loaded:
        return false;
    }

    $('.coins_i_'+i__id+'_'+x__type).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    $.post("/i/i_load_cover", {
        x__type:x__type,
        i__id:i__id,
        counter:counter,
        first_segment:first_segment,
    }, function (data) {
        $('.coins_i_'+i__id+'_'+x__type).html(data);
    });

}


//Main navigation
var search_on = false;
function toggle_search(){

    $('.left_nav').addClass('hidden');
    $('.icon_search').toggleClass('hidden');

    if(search_on){

        //Turn OFF
        search_on = false; //Reverse
        $('.max_width').removeClass('search_bar');
        $('.top_nav, #container_content').removeClass('hidden');
        $('.nav_search, #container_search').addClass('hidden');

    } else {

        //Turn ON
        search_on = true; //Reverse
        $('.max_width').addClass('search_bar');
        $('.top_nav, #container_content').addClass('hidden');
        $('.nav_search, #container_search').removeClass('hidden');
        $("#container_search .row").html(''); //Reset results view
        $('#top_search').focus();

        setTimeout(function () {
            //One more time to make sure it also works in mobile:
            $('#top_search').focus();
        }, 55);


    }
}


function load_covers(){
    $(".load_e_covers, .load_i_covers").unbind();

    $(".load_e_covers").click(function(event) {
        e_load_cover($(this).attr('load_x__type'),$(this).attr('load_e__id'),$(this).attr('load_counter'),$(this).attr('load_first_segment'));
    });
    $(".load_i_covers").click(function(event) {
        i_load_cover($(this).attr('load_x__type'),$(this).attr('load_i__id'),$(this).attr('load_counter'),$(this).attr('load_first_segment'));
    });
}

function js_redirect(url, timer = 0){
    if(timer > 0){
        setTimeout(function () {
            window.location = url;
        }, timer);
    } else{
        window.location = url;
    }
    return false;
}

function load_card_clickers(){

    $(".card_click_e, .card_click_i").unbind();
    var ignore_clicks = 'a, .btn, textarea, .x__message, .cover_wrapper12273, .ignore-click';

    $( ".card_click_e" ).click(function(e) {
        if($(e.target).closest(ignore_clicks).length < 1){
            js_redirect('/@'+$(this).attr('e__handle'));
        }
    });

    $('.card_click_i').click(function(e) {
        if($(e.target).closest(ignore_clicks).length < 1){
            js_redirect('/~'+$(this).attr('i__hashtag'));
        }
    });

}

var algolia_index = false;
$(document).ready(function () {

    $('.card_click_x').click(function(e) {
        js_redirect('/'+$(this).attr('i__hashtag'));
    });

    //Watchout for file uplods:
    $('.coverUpload').find('input[type="file"]').change(function () {
        cover_upload(droppedFiles, 'file');
    });

    load_covers();

    //Should we auto start?
    if (isAdvancedUpload) {
        var droppedFiles = false;
        $('.coverUpload').on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
        })
            .on('dragover dragenter', function () {
                $('.coverUploader').addClass('dynamic_saving');
            })
            .on('dragleave dragend drop', function () {
                $('.coverUploader').removeClass('dynamic_saving');
            })
            .on('drop', function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                e.preventDefault();
                cover_upload(droppedFiles, 'drop');
            });
    }

    //Lookout for textinput updates
    x_set_start_text();

    $('#top_search').keyup(function() {
        if(!$(this).val().length){
            $("#container_search .row").html(''); //Reset results view
        }
    });

    //For the S shortcut to load search:
    $("#top_search").focus(function() {
        if(!search_on){
            toggle_search();
        }
    });

    //Keep an eye for icon change:
    $('#modal31912 .save_e__cover').keyup(function() {
        update_cover_main($(this).val(), '.demo_cover');
    });

    set_autosize($('#sugg_note'));
    set_autosize($('.texttype__lg'));

    $('.trigger_modal').click(function (e) {
        var x__type = parseInt($(this).attr('x__type'));
        $('#modal'+x__type).modal('show');
        x_create({
            x__creator: js_pl_id,
            x__type: 14576, //MODAL VIEWED
            x__up: x__type,
        });
    });


    $("#modal31911, #modal31912").on("hide.bs.modal", function (e) {
        if(has_unsaved_changes){
            var r = confirm("Changes are unsaved! Close this window? Cancel to stay here:");
            if (!(r==true)) {
                e.preventDefault();
                return false;
            }
        }
    });


    //Load Algolia on Focus:
    initiate_algolia();


    //General ESC cancel
    $(document).keyup(function (e) {
        //Watch for action keys:
        if (e.keyCode === 27) { //ESC

            if(search_on){
                toggle_search();
            }

        }
    });


    //Load tooltips:
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });


    //Prevent search submit:
    $('#searchFrontForm').on('submit', function(e) {
        e.preventDefault();
        return false;
    });


    if(parseInt(js_e___6404[12678]['m__message'])){

        var icons_listed = [];

        //TOP SEARCH
        $("#top_search").autocomplete({minLength: 1, autoselect: false, keyboardShortcuts: ['s']}, [
            {
                source: function (q, cb) {

                    icons_listed = [];

                    //Members can filter search with first word:
                    var search_only_e = $("#top_search").val().charAt(0)=='@';
                    var search_only_in = $("#top_search").val().charAt(0)=='#';
                    var search_only_app = $("#top_search").val().charAt(0)=='-';
                    $("#container_search .row").html(''); //Reset results view


                    //Do not search if specific command ONLY:
                    if (( search_only_in || search_only_e || search_only_app ) && !isNaN($("#top_search").val().substr(1)) ) {

                        cb([]);
                        return;

                    } else {

                        //Now determine the filters we need to apply:
                        var search_filters = '';

                        if(search_only_in){
                            search_filters += ' s__type=12273';
                        } else if(search_only_e){
                            search_filters += ' s__type=12274';
                        } else if(search_only_app){
                            search_filters += ' s__type=6287';
                        }

                        if(js_pl_id > 0){

                            //For Members:
                            if(!js_session_superpowers_unlocked.includes(12701)){
                                //Can view limited sources:
                                if(search_filters.length>0){
                                    search_filters += ' AND ';
                                }
                                search_filters += ' ( _tags:publicly_searchable OR _tags:z_' + js_pl_id + ' ) ';
                            }

                        } else {

                            //Guest can search ideas only by default as they start typing;
                            if(search_filters.length>0){
                                search_filters += ' AND ';
                            }
                            search_filters += ' _tags:publicly_searchable ';

                        }

                        //Append filters:
                        algolia_index.search(q, {
                            hitsPerPage: js_e___6404[31113]['m__message'],
                            filters:search_filters,
                        }, function (error, content) {
                            if (error) {
                                cb([]);
                                return;
                            }
                            cb(content.hits, content);
                        });
                    }

                },
                templates: {
                    suggestion: function (suggestion) {
                        var item_key = suggestion.s__type+'_'+suggestion.s__id;
                        if(!icons_listed.includes(item_key)) {
                            icons_listed.push(item_key);
                            $("#container_search .row").append(view_s_js_cover(26011, suggestion, 0));
                        }
                        return false;
                    },
                    empty: function (data) {
                        $("#container_search .row").html('<div class="main__title margin-top-down-half"><span class="icon-block"><i class="fal fa-exclamation-circle"></i></span>No results found</div>');
                    },
                }
            }
        ]);
    }
});





function update_cover_main(cover_code, target_css){

    //Set Default:
    $(target_css+' .cover-link').css('background-image','');
    $(target_css+' .cover-btn').html('');

    //Update:
    if(validURL(cover_code)){
        $(target_css+' .cover-link').css('background-image','url(\''+cover_code+'\')');
    } else if(cover_code && cover_code.indexOf('fa-')>=0) {
        $(target_css+' .cover-btn').html('<i class="'+cover_code+'"></i>');
    } else if(cover_code && cover_code.length > 0) {
        $(target_css+' .cover-btn').text(cover_code);
    }
}

function view_cover_js(cover_code){
    if(cover_code && cover_code.length){
        if(validURL(cover_code)){
            return '<img src="'+cover_code+'" />';
        } else if(cover_code && cover_code.indexOf('fa-')>=0) {
            return '<i class="'+cover_code+'"></i>';
        } else {
            return cover_code;
        }
    } else {
        return '<i class="fas fa-circle zq12274"></i>';
    }
}

function update_cover_mini(cover_code, target_css){
    //Update:
    $(target_css).html(view_cover_js(cover_code));
}



function load_search(focus_card, x__type){
    if(js_n___11020.includes(x__type) || (focus_card==12274 && x__type==6255)){
        i_load_search(x__type);
    } else if(js_n___11028.includes(x__type) || (focus_card==12273 && x__type==6255)) {
        e_load_search(x__type);
    }
}


function i_load_search(x__type) {

    console.log(x__type + " i_load_search()");

    $('.new-list-'+x__type+' .add-input').keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code==13) || (e.ctrlKey && code==13)) {
            e.preventDefault();
            return i__add(x__type, 0);
        }
    });

    if(!parseInt(js_e___6404[12678]['m__message'])){
        console.log("Search engine is disabled!");
        return false;
    }

    //Load Saerch:
    $('.new-list-'+x__type+' .add-input').keyup(function () {

        //Clear if no input:
        if(!$(this).val().length){
            $('.new-list-'+x__type+' .algolia_pad_search').html('');
        }

    }).autocomplete({hint: false, autoselect: false, minLength: 1}, [{
        source: function (q, cb) {

            $('.new-list-'+x__type+' .algolia_pad_search').html('');

            algolia_index.search(q, {

                filters: 's__type=12273' + search_and_filter,
                hitsPerPage: js_e___6404[31112]['m__message'],

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
                $('.new-list-'+x__type+' .algolia_pad_search').append(view_s_js_cover(26012, suggestion, x__type));
            },
            header: function (data) {
                if(data.query && data.query.length){
                    $('.new-list-'+x__type+' .algolia_pad_search').prepend('<div class="card_cover contrast_bg mini-cover coin-12273 coin-id-0 col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="i__add('+x__type+', 0)" class="black-background-obs cover-link isSelected"><div class="cover-btn"></div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="i__add('+x__type+', 0)" class="main__title">'+data.query+'</a></div></div></div>');
                }
            },
            empty: function (data) {
                return '';
            },
        }
    }]);
}

function e_load_search(x__type) {

    console.log(x__type + " e_load_search()");

    //Load Search:
    var icons_listed = [];
    $('.new-list-'+x__type + ' .add-input').keypress(function (e) {
        icons_listed = [];
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code==13) || (e.ctrlKey && code==13)) {
            e__add(x__type, 0);
            return true;
        }
    });

    if(!parseInt(js_e___6404[12678]['m__message'])){
        console.log("Search engine is disabled!");
    }

    $('.new-list-'+x__type + ' .add-input').keyup(function () {

        //Clear if no input:
        if(!$(this).val().length){
            $('.new-list-'+x__type+' .algolia_pad_search').html('');
        }
        icons_listed = [];

    }).autocomplete({hint: false, autoselect: false, minLength: 1}, [{

        source: function (q, cb) {

            $('.new-list-'+x__type+' .algolia_pad_search').html('');

            algolia_index.search(q, {
                filters: 's__type=12274' + search_and_filter,
                hitsPerPage: js_e___6404[31112]['m__message'],
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
                var item_key = suggestion.s__type+'_'+suggestion.s__id;
                if(!icons_listed.includes(item_key)) {
                    icons_listed.push(item_key);
                    $('.new-list-'+x__type+' .algolia_pad_search').append(view_s_js_cover(26013, suggestion, x__type));
                }
            },
            header: function (data) {
                if(data.query && data.query.length){
                    $('.new-list-'+x__type+' .algolia_pad_search').prepend('<div class="card_cover contrast_bg mini-cover coin-12274 coin-id-0 col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="e__add('+x__type+', 0)" class="black-background-obs cover-link coinType12274"><div class="cover-btn"></div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e__add('+x__type+', 0)" class="main__title">'+data.query+'</a></div></div></div>');
                }
            },
            empty: function (data) {
                return '';
            }
        }
    }]);

}








function editor_load_i(i__id, x__id, link_i__id = 0){

    //Reset Fields:
    has_unsaved_changes = false;
    $("#modal31911 .unsaved_warning").val('');
    $('#modal31911 .save_results, #modal31911 .dynamic_editing_radio').html('');
    $("#modal31911 .dynamic_item, #modal31911 .save_x__message").addClass('hidden');
    $("#modal31911 .dynamic_editing_loading").removeClass('hidden');
    $('#modal31911 .save_i__id, #modal31911 .save_x__id').val(0);
    $("#modal31911 .dynamic_item").attr('placeholder', '').val('').attr('d__id','');

    //Load Instant Fields:
    if(link_i__id){
        $("#modal31911 .show_id").text('Link to '+link_i__id);
        $('#modal31911 .link_i__id').val(i__id);
    }
    if(i__id){
        $('#modal31911 .save_i__id').val(i__id);
        $("#modal31911 .show_id").text('ID '+i__id);
        $('#modal31911 .save_i__hashtag').val($('.ui_i__hashtag_'+i__id).text());
        $('#modal31911 .save_i__message').val($('.ui_i__message_'+i__id).text()).focus();
    }
    if(x__id){
        $('#modal31911 .save_x__id').val(x__id);
        //$('#modal31911 .save_x__message').val($('.ui_x__message_'+x__id).text()).removeClass('hidden');
    }

    //Activate Modal:
    $('#modal31911').modal('show');

    activate_handle_search($('#modal31911 .save_i__message'));

    setTimeout(function () {
        set_autosize($('#modal31911 .save_i__message'));
        set_autosize($('#modal31911 .save_x__message'));
    }, 377);

    if(i__id){
        //Load dynamic data:
        $.post("/i/editor_load_i", {
            i__id: i__id,
            x__id: x__id,
        }, function (data) {

            $("#modal31911 .dynamic_editing_loading").addClass('hidden');

            if (data.status) {

                var field_counter = 0;

                //Dynamic Input Fields:
                for (var i=0, item; item = data.return_inputs[i]; i++) {
                    field_counter++;
                    $("#modal31911 .dynamic_"+field_counter+" h3").html(item["d__title"]);
                    $("#modal31911 .dynamic_"+field_counter).removeClass('hidden');
                    $("#modal31911 .dynamic_"+field_counter+" input").attr('placeholder',item["d__placeholder"]).val(item["d__value"]);
                }

                //Dynamic Radio fields (if any):
                $("#modal31911 .dynamic_editing_radio").html(data.return_radios);

                $('[data-toggle="tooltip"]').tooltip();

            } else {

                //Should not have an issue loading...
                alert('ERROR:' + data.message);

            }
        });
    } else {
        $("#modal31911 .dynamic_editing_loading").addClass('hidden');
    }

    //Track unsaved changes to prevent unwated modal closure:
    $("#modal31911 .unsaved_warning").change(function() {
        has_unsaved_changes = true;
    });

}


var i_saving = false; //Prevent double saving
function editor_save_i(){

    if(i_saving){
        return false;
    } else {
        i_saving = true;
    }

    var modify_data = {
        save_i__id:         $('#modal31911 .save_i__id').val(),
        save_x__id:         $('#modal31911 .save_x__id').val(),
        save_x__message:    $('#modal31911 .save_x__message').val().trim(),
        save_i__message:    $('#modal31911 .save_i__message').val().trim(),
        save_i__hashtag:    $('#modal31911 .save_i__hashtag').val().trim(),
    };

    //Append Dynamic Data:
    for(let i=1;i<=js_e___6404[42206]['m__message'];i++) {
        if($('#modal31911 .save_dynamic_'+i).attr('d__id').length){
            modify_data['save_dynamic_'+i] = $('#modal31911 .save_dynamic_'+i).attr('d__id').trim() + '____' + $('#modal31911 .save_dynamic_'+i).val().trim();
        }
    }

    $.post("/i/editor_save_i", modify_data, function (data) {

        if (!data.status) {

            //Show Errors:
            $("#modal31911 .save_results").html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Error:<br />'+data.message);

        } else {

            //Reset errors:
            $("#modal31911 .save_results").html('');
            has_unsaved_changes = false;
            $('#modal31911').modal('hide');

            //Update Idea Message:
            $('.ui_i__message_'+modify_data['save_i__id']).text(modify_data['save_i__message']);

            //Update Hashtag:
            $(".ui_i__hashtag_"+modify_data['save_i__id']).text(modify_data['save_i__hashtag']).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233); //Flash

            $('.ui_i__cache_'+modify_data['save_i__id']).html(( parseInt($('.ui_i__cache_'+modify_data['save_i__id']).attr('show_cache_links')) ? data.return_i__cache_links : data.return_i__cache ));
            console.log(data.message); //To check what happened...

            if(x__id){
                $('.ui_x__message_'+x__id).text(modify_data['save_x__message']);
            }

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Load Images:
            i_saving = false;

        }
    });
}





function editor_load_e(e__id, x__id){

    //Reset Fields:
    has_unsaved_changes = false;

    $("#modal31912 .unsaved_warning").val('');

    $('#modal31912 .save_results, #modal31912 .dynamic_editing_radio').html('');
    $("#modal31912 .dynamic_item, #modal31912 .save_x__message").addClass('hidden');
    $("#modal31912 .dynamic_editing_loading").removeClass('hidden');
    $("#modal31912 .dynamic_item").attr('placeholder', '').val('').attr('d__id','');

    //Source resets:
    $('#search_cover').val('');
    $("#upload_results, #previous_used_covers").html('');
    $('#modal31912 .black-background-obs').removeClass('isSelected');

    //Load Instant Fields:
    $('#modal31912 .save_e__id').val(e__id);
    $('#modal31912 .save_x__id').val(x__id);
    $("#modal31912 .show_id").text('ID '+e__id);
    $('#modal31912 .save_e__handle').val($('.ui_e__handle_'+e__id).text());

    $('#modal31912 .save_e__title').val($('.text__6197_'+e__id).val());
    var current_cover = $('.ui_e__cover_'+e__id).attr('raw_cover');
    $('#modal31912 .save_e__cover').val(current_cover).focus();
    update_cover_main(current_cover, '.demo_cover');

    if(x__id){
        $('#modal31912 .save_x__message').val($('.ui_x__message_'+x__id).text()).removeClass('hidden');
        setTimeout(function () {
            set_autosize($('#modal31912 .save_x__message'));
        }, 377);
    }

    //Activate Modal:
    $('#modal31912').modal('show');


    $.post("/e/editor_load_e", {
        e__id: e__id,
        x__id: x__id
    }, function (data) {

        $("#modal31912 .dynamic_editing_loading").addClass('hidden');

        if (data.status) {

            var field_counter = 0;

            //Dynamic Input Fields:
            for (var i=0, item; item = data.return_inputs[i]; i++) {
                field_counter++;
                $("#modal31912 .dynamic_"+field_counter+" h3").html(item["d__title"]);
                $("#modal31912 .dynamic_"+field_counter).removeClass('hidden');
                $("#modal31912 .dynamic_"+field_counter+" input").attr('placeholder',item["d__placeholder"]).val(item["d__value"]).attr('d__id',item["d__id"]);
            }

            //Dynamic Radio fields (if any):
            $("#modal31912 .dynamic_editing_radio").html(data.return_radios);

            $('[data-toggle="tooltip"]').tooltip();

            //Any Source suggestions to auto load?
            if(data.previous_used_covers.length){
                data.previous_used_covers.forEach(function(item) {
                    $("#previous_used_covers").append(image_cover(item.cover_preview, item.cover_apply, item.new_title));
                });
            }

        } else {

            //Should not have an issue loading...
            alert('ERROR:' + data.message);

        }

    });

    //Track unsaved changes to prevent unwated modal closure:
    $("#modal31912 .unsaved_warning").change(function() {
        has_unsaved_changes = true;
    });

}

e_saving = false;
function editor_save_e(){

    if(e_saving){
        return false;
    } else {
        e_saving = true;
    }

    var modify_data = {
        save_e__id:         $('#modal31912 .save_e__id').val(),
        save_e__title:      $('#modal31912 .save_e__title').val().trim(),
        save_e__cover:      $('#modal31912 .save_e__cover').val().trim(),
        save_e__handle:     $('#modal31912 .save_e__handle').val().trim(),
        save_x__id:         $('#modal31912 .save_x__id').val(),
        save_x__message:    $('#modal31912 .save_x__message').val().trim(),
    };

    //Append Dynamic Data:
    for(let i=1;i<=js_e___6404[42206]['m__message'];i++) {
        if($('#modal31912 .save_dynamic_'+i).attr('d__id').length){
            modify_data['save_dynamic_'+i] = $('#modal31912 .save_dynamic_'+i).attr('d__id').trim() + '____' + $('#modal31912 .save_dynamic_'+i).val().trim();
        }
    }

    $.post("/e/editor_save_e", modify_data, function (data) {

        if (!data.status) {

            //Show Errors:
            $("#modal31912 .save_results").html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Error:<br />'+data.message);

        } else {

            //Reset errors:
            $("#modal31912 .save_results").html('');

            //Update Title:
            update_text_name(6197, modify_data['save_e__id'], modify_data['save_e__title']);

            //Update Handle:
            $('.ui_e__handle_'+modify_data['save_e__id']).text(modify_data['save_e__handle']);

            //Update Mini Icon:
            update_cover_mini(modify_data['save_e__cover'], '.mini_6197_'+modify_data['save_e__id']);

            //Update Main Icons:
            update_cover_main(modify_data['save_e__cover'], '.s__12274_'+modify_data['save_e__id']);

            console.log(data.message); //To check what happened...

            if( modify_data['save_x__id'] ){
                $('.ui_x__message_'+ modify_data['save_x__id'] ).text(modify_data['save_x__message']);
            }

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            e_saving = false;
            has_unsaved_changes = false;
            $('#modal31912').modal('hide');

        }

    });

}



















function load_tab(x__type, auto_load){

    var focus_card = fetch_int_val('#focus_card');
    console.log('Tab loading... from @'+focus_card+' for @'+x__type);

    if(focus_card==12273){

        $.post("/i/view_body_i", {
            focus_card:focus_card,
            x__type:x__type,
            counter:$('.headline_body_' + x__type).attr('read-counter'),
            i__id:fetch_int_val('#focus_id')
        }, function (data) {
            $('.headline_body_' + x__type + ' .tab_content').html(data);
        });

    } else if(focus_card==12274){

        //Load the tab:
        $.post("/e/view_body_e", {
            focus_card:focus_card,
            x__type:x__type,
            counter:$('.headline_body_'+x__type).attr('read-counter'),
            e__id:fetch_int_val('#focus_id')
        }, function (data) {
            $('.headline_body_'+x__type + ' .tab_content').html(data);
        });

    } else {

        //Whaaaat is this?
        console.log('ERROR: Unknown Tab!');
        return false;

    }

    //Give some extra loding time so the content loads on page:
    setTimeout(function () {

        $('[data-toggle="tooltip"]').tooltip();
        load_card_clickers();
        initiate_algolia();
        load_editor();
        x_set_start_text();
        set_autosize($('.x_set_class_text'));

        setTimeout(function () {
            load_covers();
            $('[data-toggle="tooltip"]').tooltip();
        }, 2584);


        $(function () {
            var $win = $(window);
            $win.scroll(function () {
                //Download loading from bottom:
                if (parseInt($(document).height() - ($win.height() + $win.scrollTop())) <= 377) {
                    view_load_page(x__type);
                }
            });
        });

        if(js_n___11020.includes(x__type) || (focus_card==12274 && x__type==6255)){
            setTimeout(function () {
                sort_i_load(x__type);
            }, 2584);
        } else if(js_n___11028.includes(x__type) || (focus_card==12273 && x__type==6255)) {
            setTimeout(function () {
                sort_e_load(x__type);
            }, 2584);
        }

        load_covers();

    }, 2584);



}


var i_is_adding = false;
function i__add(x__type, link_i__id) {

    /*
     *
     * Either creates an IDEA transaction between focus_id & link_i__id
     * OR will create a new idea based on input text and then transaction it
     * to fetch_int_val('#focus_id') (In this case link_i__id=0)
     *
     * */

    if(i_is_adding){
        return false;
    }

    //Remove results:
    $('.mini-cover.coin-12273.coin-id-'+link_i__id+' .cover-btn').html('<i class="far fa-yin-yang fa-spin"></i>');
    i_is_adding = true;
    var sort_i_grab = ".card_cover";
    var input_field = $('.new-list-'+x__type+' .add-input');
    var new_i__message = input_field.val();


    //We either need the idea name (to create a new idea) or the link_i__id>0 to create an IDEA transaction:
    if (!link_i__id && new_i__message.length < 1) {
        alert('Missing Idea Title');
        input_field.focus();
        return false;
    }

    //Set processing status:
    input_field.addClass('dynamic_saving');
    add_to_list(x__type, sort_i_grab, '<div id="tempLoader" class="col-6 col-md-4 no-padding show_all_i"><div class="cover-wrapper"><div class="black-background-obs cover-link"><div class="cover-btn"><i class="far fa-yin-yang fa-spin"></i></div></div></div></div>', 0);

    //Update backend:
    $.post("/i/i__add", {
        x__type: x__type,
        focus_card: fetch_int_val('#focus_card'),
        focus_id: fetch_int_val('#focus_id'),
        new_i__message: new_i__message,
        link_i__id: link_i__id
    }, function (data) {

        //Delete loader:
        $("#tempLoader").remove();
        input_field.removeClass('dynamic_saving').prop("disabled", false).focus();
        i_is_adding = false;

        if (data.status) {

            sort_i_load(x__type);

            //Add new
            add_to_list(x__type, sort_i_grab, data.new_i_html, 1);

            //Lookout for textinput updates
            x_set_start_text();
            load_covers();
            set_autosize($('.texttype__lg'));

            //Hide Coin:
            $('.mini-cover.coin-12273.coin-id-'+link_i__id).fadeOut();

        } else {
            //Show errors:
            alert(data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}

function toggle_max_view(css_class){

    //Toggle main class:
    $('.'+css_class).toggleClass('hidden');

    if($( ".fixed-top" ).hasClass( "maxcontain" )){
        //Minimize:
        $('.maxcontain').addClass('container').removeClass('maxcontain');
    } else {
        //Maximize:
        $('.container').addClass('maxcontain').removeClass('container');
    }

}


//Adds OR transactions sources to sources
var e_is_adding = false;
function e__add(x__type, e_existing_id) {

    if(e_is_adding){
        return false;
    }

    //if e_existing_id>0 it means we're adding an existing source, in which case e_new_string should be null
    //If e_existing_id=0 it means we are creating a new source and then adding it, in which case e_new_string is required
    e_is_adding = true;

    var input = $('.new-list-'+x__type+' .add-input');

    var original_photo = $('.mini-cover.coin-12274.coin-id-'+e_existing_id+' .cover-btn').html();
    $('.mini-cover.coin-12274.coin-id-'+e_existing_id+' .cover-btn').html('<i class="far fa-yin-yang fa-spin"></i>');
    var e_new_string = null;
    if (e_existing_id==0) {
        e_new_string = input.val();
        if (e_new_string.length < 1) {
            alert('Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }

    //Add via Ajax:
    $.post("/e/e__add", {

        focus_card: fetch_int_val('#focus_card'),
        x__type: x__type,
        focus_id: fetch_int_val('#focus_id'),
        e_existing_id: e_existing_id,
        e_new_string: e_new_string,

    }, function (data) {

        e_is_adding = false;

        if (data.status) {

            if(data.e_already_linked){
                var r = confirm("This is already linked here! Are you sure you want to double link it?");
                if (r==true) {
                    data.e_already_linked = false;
                } else {
                    $('.mini-cover.coin-12274.coin-id-'+e_existing_id+' .cover-btn').html(original_photo);
                }
            }

            if(!data.e_already_linked){

                //Raw input to make it ready for next URL:
                //input.focus();

                //Add new object to list:
                add_to_list(x__type, '.coinface-12274', data.e_new_echo, 1);

                //Allow inline editing if enabled:
                x_set_start_text();

                sort_e_load(x__type);
                load_covers();

                //Hide Coin:
                $('.mini-cover.coin-12274.coin-id-'+e_existing_id).fadeOut();
            }

        } else {
            //We had an error:
            alert(data.message);
        }

    });
}



function e_delete(x__id, x__type) {

    var r = confirm("Unlink this source?");
    if (r==true) {
        $.post("/e/e_delete", {

            x__id: x__id,

        }, function (data) {
            if (data.status) {

                adjust_counter(x__type, -1);
                $(".cover_x_" + x__id).fadeOut();
                setTimeout(function () {
                    $(".cover_x_" + x__id).remove();
                }, 610);

            } else {
                //We had an error:
                alert(data.message);
            }
        });
    }
}




//For the drag and drop file uploader:
var isAdvancedUpload = function () {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

function x_link_toggle(x__type, i__id){

    $('.btn_toggle_'+x__type).toggleClass('hidden');
    var x__id = parseInt($('.btn_control_'+x__type).attr('current_x_id'));

    if(!x__id){
        //Add:
        $.post("/x/x_link_toggle", {
            x__type:x__type,
            i__id:i__id,
            top_i__id:$('#top_i__id').val(),
        }, function (data) {
            if (!data.status) {
                alert(data.message);
                $('.btn_toggle_'+x__type).toggleClass('hidden');
            } else {
                //Update new link ID:
                $('.btn_control_'+x__type).attr('current_x_id', data.x__id);
            }
        });
    } else {
        //REMOVE
        $.post("/x/x_remove", {
            x__id:x__id
        }, function (data) {
            //Update UI to confirm with member:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
                $('.btn_toggle_'+x__type).toggleClass('hidden');
            } else {
                //Update new link ID:
                $('.btn_control_'+x__type).attr('current_x_id', 0);
            }
        });
    }
}



function validURL(str) {
    return str && str.length && str.substring(0, 4)=='http';
}


function add_to_list(x__type, sort_i_grab, html_content, increment) {

    adjust_counter(x__type, increment);

    //See if we previously have a list in place?
    if ($("#list-in-" + x__type + " " + sort_i_grab).length > 0) {
        //Downwards add to start"
        $("#list-in-" + x__type + " " + sort_i_grab + ":first").before(html_content);
    } else {
        //Raw list, add before input filed:
        $("#list-in-" + x__type).prepend(html_content);
    }


    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();

}

jQuery.fn.extend({
    insertAtCaret: function (myValue) {
        return this.each(function (i) {
            if (document.selection) {
                //For browsers like Internet Explorer
                this.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            } else if (this.selectionStart || this.selectionStart=='0') {
                //For browsers like Firefox and Webkit based
                var startPos = this.selectionStart;
                var endPos = this.selectionEnd;
                var scrollTop = this.scrollTop;
                this.value = this.value.substring(0, startPos) + myValue + this.value.substring(endPos, this.value.length);
                this.focus();
                this.selectionStart = startPos + myValue.length;
                this.selectionEnd = startPos + myValue.length;
                this.scrollTop = scrollTop;
            } else {
                this.value += myValue;
                this.focus();
            }
        })
    }
});





Math.fmod = function (a,b) { return Number((a - (Math.floor(a / b) * b)).toPrecision(8)); };

function images_add(image_url, image_title){
    var current_value = $('.new_i__message').val();
    $('#modal14073').modal('hide');
    $('.new_i__message').val(( current_value.length ? current_value+"\n\n" : '' ) + image_url + '?e__title='+encodeURI(image_title));
}


function x_set_start_text(){
    $('.x_set_class_text').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code==13) {
            x_set_text(this);
            e.preventDefault();
        }
    }).change(function() {
        x_set_text(this);
    });
}

function update_text_name(cache_e__id, e__id, e__title){
    var target_element = ".text__"+cache_e__id+"_" + e__id;
    $(target_element).text(e__title).attr('old-value', e__title); //.val(e__title)
    set_autosize($(target_element));
}

function x_set_text(this_grabr){

    var modify_data = {
        s__id: parseInt($(this_grabr).attr('s__id')),
        cache_e__id: parseInt($(this_grabr).attr('cache_e__id')),
        new_i__message: $(this_grabr).val().trim()
    };

    //See if anything changes:
    if( $(this_grabr).attr('old-value')==modify_data['new_i__message'] ){
        //Nothing changed:
        return false;
    }

    //Grey background to indicate saving...
    var target_element = '.text__'+modify_data['cache_e__id']+'_'+modify_data['s__id'];
    $(target_element).addClass('dynamic_saving').prop("disabled", true);

    $.post("/x/x_set_text", modify_data, function (data) {

        if (!data.status) {

            //Reset to original value:
            $(target_element).val(data.original_val);

            //Show error:
            alert(data.message);

        } else {

            //If Updating Text, Updating Corresponding Fields:
            update_text_name(modify_data['cache_e__id'], modify_data['s__id'], modify_data['new_i__message']);

        }

        setTimeout(function () {
            //Restore background:
            $(target_element).removeClass('dynamic_saving').prop("disabled", false);
        }, 233);

    });
}




function adjust_counter(x__type, adjustment_count){
    var current_total_count = parseInt($('.headline_body_' + x__type).attr('read-counter')) + adjustment_count;
    $('.xtypecounter'+x__type).text(current_total_count);

}




function activate_handle_search(obj) {
    if(parseInt(js_e___6404[12678]['m__message'])){
        obj.textcomplete([
            {
                match: /(^|\s)@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    algolia_index.search(q, {
                        hitsPerPage: js_e___6404[31112]['m__message'],
                        filters: 's__type=12274' + search_and_filter,
                    })
                        .then(function searchSuccess(content) {
                            if (content.query === q) {
                                callback(content.hits);
                            }
                        })
                        .catch(function searchFailure(err) {
                            console.error(err);
                        });
                },
                template: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                replace: function (suggestion) {
                    set_autosize(obj);
                    return ' @' + suggestion.s__handle + ' ';
                }
            },
            {
                match: /(^|\s)#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    algolia_index.search(q, {
                        hitsPerPage: js_e___6404[31112]['m__message'],
                        filters: 's__type=12273' + search_and_filter,
                    })
                        .then(function searchSuccess(content) {
                            if (content.query === q) {
                                callback(content.hits);
                            }
                        })
                        .catch(function searchFailure(err) {
                            console.error(err);
                        });
                },
                template: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                replace: function (suggestion) {
                    set_autosize(obj);
                    return ' #' + suggestion.s__handle + ' ';
                }
            },
        ]);
    }
}


function set_autosize(theobject){
    autosize(theobject);
    setTimeout(function () {
        autosize.update(theobject);
    }, 13);
}



var sorting_loaded = []; // more efficient than new Array()

function sort_i_load(x__type){

    if(!js_n___4603.includes(x__type)){
        console.log(x__type+' is not sortable');
        return false;
    }

    setTimeout(function () {
        var theobject = document.getElementById("list-in-" + x__type);
        if (!theobject) {
            //due to duplicate ideas belonging in this idea:
            console.log(x__type+' failed to find sortable object');
            return false;
        }

        if(sorting_loaded.indexOf(x__type) >= 0){
            console.log(x__type+' already loaded');
            return false;
        }

        //Make sure beow minimum sorting requirement:
        if($("#list-in-"+x__type+" .sort_draggable").length>=parseInt(fetch_int_val('#page_limit'))){
            return false;
        }

        $('.sort_i_grab').removeClass('hidden');
        console.log(x__type+' sorting load success');
        sorting_loaded.push(x__type);

        //Load sorter:
        var sort = Sortable.create(theobject, {
            animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
            draggable: "#list-in-"+x__type+" .sort_draggable", // Specifies which items inside the element should be sortable
            handle: "#list-in-"+x__type+" .sort_i_grab", // Restricts sort start click/touch to the specified element
            onUpdate: function (evt/**Event*/) {

                var sort_rank = 0;
                var new_x_order = [];
                $("#list-in-"+x__type+" .sort_draggable").each(function () {
                    var x__id = parseInt($(this).attr('x__id'));
                    if(x__id > 0){
                        sort_rank++;
                        new_x_order[sort_rank] = x__id;
                    }
                });

                //Update order:
                if(sort_rank > 0){
                    $.post("/x/sort_i_load", { new_x_order:new_x_order, x__type:x__type }, function (data) {
                        //Update UI to confirm with member:
                        if (!data.status) {
                            //There was some sort of an error returned!
                            alert(data.message);
                        }
                    });
                }
            }
        });
    }, 377);

}









var current_focus = 0;
function remove_ui_class(item, index) {
    var the_class = 'custom_ui_'+current_focus+'_'+item;
    $('body').removeClass(the_class);
}

function e_radio(focus_id, selected_e__id, enable_mulitiselect, down_e__id, right_i__id){

    //Any warning needed?
    if(js_n___31780.includes(selected_e__id) && !confirm(js_e___31780[selected_e__id]['m__message'])){
        return false;
    }

    var was_previously_selected = ( $('.radio-'+focus_id+' .item-'+selected_e__id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_previously_selected){
        //Nothing to do here:
        return false;
    }

    //Updating Customizable Theme?
    if(js_n___13890.includes(focus_id)){
        current_focus = focus_id;
        $('body').removeClass('custom_ui_'+focus_id+'_');
        window['js_n___'+focus_id].forEach(remove_ui_class); //Removes all Classes
        $('body').addClass('custom_ui_'+focus_id+'_'+selected_e__id);
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+focus_id+' .item-'+selected_e__id+' .change-results';
    var initial_icon = $(notify_el).html();
    $(notify_el).html('<i class="far fa-yin-yang fa-spin"></i>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+focus_id+' .list-group-item').removeClass('active');
    }

    //Enable currently selected:
    if(enable_mulitiselect && was_previously_selected){
        $('.radio-'+focus_id+' .item-'+selected_e__id).removeClass('active');
    } else {
        $('.radio-'+focus_id+' .item-'+selected_e__id).addClass('active');
    }

    $.post("/e/e_radio", {
        focus_id: focus_id,
        down_e__id: down_e__id,
        right_i__id: right_i__id,
        selected_e__id: selected_e__id,
        enable_mulitiselect: enable_mulitiselect,
        was_previously_selected: was_previously_selected,
    }, function (data) {

        $(notify_el).html(initial_icon);

        if (!data.status) {
            alert(data.message);
        }

    });


}


function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 0;
}


function update_dropdown(element_id, new_e__id, o__id, x__id, show_full_name){

    /*
    *
    * WARNING:
    *
    * element_id Must be listed as followers of:
    *
    * MEMORY CACHE @4527
    * JS MEMORY CACHE @11054
    *
    *
    * */

    var current_selected = parseInt($('.dropi_'+element_id+'_'+o__id+'_'+x__id+'.active').attr('current-selected'));
    new_e__id = parseInt(new_e__id);
    if(current_selected==new_e__id){
        //Nothing changed:
        return false;
    }



    //Deleting Anything?
    var migrate_s__id = 0;
    if(element_id==31004 && !(new_e__id in js_e___31871)){

        //Deleting Idea:
        var confirm_removal = prompt("Are you sure you want to delete this idea?\nEnter 0 to unlink OR enter Idea ID to migrate links.", "0");
        if (!isNormalInteger(confirm_removal)) {
            return false;
        }
        migrate_s__id = confirm_removal;

    } else if(element_id==6177 && !(new_e__id in js_e___7358)){

        //Deleting Source:
        var confirm_removal = prompt("Are you sure you want to delete this source?\nEnter 0 to unlink OR enter source ID to migrate links.", "0");
        if (!isNormalInteger(confirm_removal)) {
            return false;
        }
        migrate_s__id = confirm_removal;

    }



    //Show Loading...
    var data_object = eval('js_e___'+element_id);
    if(!data_object[new_e__id]){
        alert('Invalid element ID: '+element_id +'/'+ new_e__id +'/'+ o__id +'/'+ x__id +'/'+ show_full_name);
        return false;
    }
    $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="main__title">'+ ( show_full_name ? 'SAVING...' : '' ) +'</b>');

    $.post("/x/update_dropdown", {
        focus_id:fetch_int_val('#focus_id'),
        o__id: o__id,
        element_id: element_id,
        new_e__id: new_e__id,
        migrate_s__id: migrate_s__id,
        x__id: x__id
    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block">'+data_object[new_e__id]['m__cover']+'</span>' + ( show_full_name ? data_object[new_e__id]['m__title'] : '' ));

            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .dropi_' + element_id +'_'+o__id+ '_' + x__id).removeClass('active');
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .optiond_' + new_e__id+'_'+o__id+ '_' + x__id).addClass('active');

            var selected_e__id = $('.dropd_'+element_id+'_'+o__id+'_'+x__id).attr('selected-val');
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id).attr('selected-val' , new_e__id);

            if(element_id==6177){
                //Update source access:
                $('.s__12274_'+o__id+' .cover-link').removeClass('card_access_'+selected_e__id).addClass('card_access_'+new_e__id);
            }

            if( data.deletion_redirect && data.deletion_redirect.length > 0 ){

                //Go to main idea page:
                js_redirect(data.deletion_redirect);

            } else if( data.delete_element && data.delete_element.length > 0 ){

                //Go to main idea page:
                setTimeout(function () {
                    //Restore background:
                    $( data.delete_element ).fadeOut();

                    setTimeout(function () {
                        //Restore background:
                        $( data.delete_element ).remove();
                    }, 55);

                }, 377);

            }

            if( data.trigger_i_save_modal ){
                //We need to show idea modal:
                editor_load_i(o__id, $('.s__12273_'+o__id).attr('x__id'));
            }

        } else {

            //Reset to default:
            var current_class = $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn span').attr('class');
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="'+current_class+'">'+data_object[current_selected]['m__cover']+'</span>' + ( show_full_name ? data_object[current_selected]['m__title'] : '' ));

            //Show error:
            alert(data.message);

        }
    });
}








function e_reset_discoveries(e__id){
    //Confirm First:
    var r = confirm("DANGER WARNING!!! You are about to delete your ENTIRE discovery history. This action cannot be undone and you will lose all your discovery coins.");
    if (r==true) {
        $('.e_reset_discoveries').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="main__title">REMOVING ALL...</b>');

        //Redirect:
        js_redirect('/x/e_reset_discoveries/'+e__id);
    } else {
        return false;
    }
}


function sort_e_save(x__type) {

    var new_x__weight = [];
    var sort_rank = 0;

    $("#list-in-"+x__type+" .coinface-12274").each(function () {
        //Fetch variables for this idea:
        var e__id = parseInt($(this).attr('e__id'));
        var x__id = parseInt($(this).attr('x__id'));

        sort_rank++;

        //Store in DB:
        new_x__weight[sort_rank] = x__id;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0) {
        //Update backend:
        $.post("/e/sort_e_save", {e__id: fetch_int_val('#focus_id'), x__type:x__type, new_x__weight: new_x__weight}, function (data) {
            //Update UI to confirm with member:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}

function sort_alphabetical(){
    var r = confirm("Reset sorting alphabetically?");
    if (r==true) {

        var focus_card = fetch_int_val('#focus_card');
        var focus_id = fetch_int_val('#focus_id');
        var focus_handle = fetch_int_val('#focus_handle');


        //Update via call:
        $.post("/x/sort_alphabetical", {
            focus_card: focus_card,
            focus_id: focus_id
        }, function (data) {

            if (!data.status) {

                //Ooops there was an error!
                alert(data.message);

            } else {

                //Refresh page:
                if(focus_card==12273){
                    js_redirect('/~' + focus_handle);
                } else if(focus_card==12274){
                    js_redirect('/@' + focus_handle);
                }

            }
        });
    }
}






