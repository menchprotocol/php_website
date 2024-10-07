

//Define some global variables:
var has_unsaved_changes = false; //Tracks source/idea modal edits
var focus_x__group = 0;




if(!js_pl_id || !js_n___43512.includes(js_pl_id)){
    //Microsoft Clarity=
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        //Append custom variables:
        clarity("set", "website_id", website_id);
        clarity("set", "website_uri", js_request_uri);
        clarity("set", "user_id", js_pl_id);
        clarity("set", "user_name", js_pl_name);
        clarity("set", "user_handle", js_pl_handle);
    })(window, document, "clarity", "script", "59riunqvfm");
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

function clean_font_awesome_paste(new_cover){
    if(new_cover.includes('<i class="fa-')){
        //Extract font awesome code:
        var split_cover_arr = new_cover.split('<i class="fa-');
        var split_cover_arr2 = split_cover_arr[1].split('"');
        new_cover = ( split_cover_arr2[0].length ? 'fa-'+split_cover_arr2[0] : new_cover );
    }
    return new_cover;
}

function watch_cover_change(new_cover){
    if(new_cover.substr(0, 2)=='fa' && new_cover.includes('fa-')){
        //Update font awesome:
        var split_cover_2arr = new_cover.split('fa-');
        var split_cover_2arr2 = split_cover_2arr[1].split(' ');
        $('#modal31912 .fa_search a').attr('href','https://fontawesome.com/search?q='+encodeURIComponent(split_cover_2arr2[0])+'&o=r&s=solid&f=classic%2Cbrands');
        $('#modal31912 .save_e__cover,  #modal31912 .fa_search').removeClass('hidden');
        console.log('updated');
    } else {
        $('#modal31912 .save_e__cover, #modal31912 .fa_search').addClass('hidden');
    }
    //Reactivate:
    //activate_cover_watch();
}

function activate_cover_watch(){
    $('#modal31912 .save_e__cover').change(function () {

        console.log('change detexted:'+$(this).val());
        watch_cover_change($(this).val());

    }).on('paste', function (e) {
        e.preventDefault();
        var text;
        var clp = (e.originalEvent || e).clipboardData;
        if (clp === undefined || clp === null) {
            text = window.clipboardData.getData("text") || "";
            if (text !== "") {
                text = clean_font_awesome_paste(text);
                if (window.getSelection) {
                    var newNode = document.createElement("span");
                    newNode.innerHTML = text;
                    window.getSelection().getRangeAt(0).insertNode(newNode);
                } else {
                    document.selection.createRange().pasteHTML(text);
                }
            }
        } else {
            text = clp.getData('text/plain') || "";
            if (text !== "") {
                text = clean_font_awesome_paste(text);
                document.execCommand('insertText', false, text);
            }
        }
        watch_cover_change(text);
    });

}



function gather_media(target_el, uploader_id){

    //Append Media:
    var sort_rank = 0;
    var upload_completed = true;
    var error_message = null;
    var uploaded_media = [];
    $(target_el).each(function () {

        var current_e_id = parseInt($(this).attr('e__id'));

        if(current_e_id > 0){

            //Already there...
            uploaded_media[sort_rank] = {
                media_e__id:  parseInt($(this).attr('media_e__id')),
                playback_code: $(this).attr('playback_code'),
                e__id:        current_e_id,
                e__cover:     $(this).attr('e__cover'),
                e__title:     $('#'+$(this).attr('id')+' input').val(),
            }
            sort_rank++;

        } else if(media_cache[uploader_id][$(this).attr('id')]){

            //Fetch variables for this media:
            uploaded_media[sort_rank] = {
                media_e__id:  parseInt($(this).attr('media_e__id')),
                playback_code: $(this).attr('playback_code'),
                e__id:        0,
                e__cover:     $(this).attr('e__cover'),
                e__title:     $('#'+$(this).attr('id')+' input').val(),
                media_cache:  media_cache[uploader_id][$(this).attr('id')],
            }
            sort_rank++;

        } else {

            //This media is missing, upload is not yet complete:
            upload_completed = false;
            error_message = 'Media has not yet uploaded, please wait until upload is complete...';

        }
    });

    return {
        upload_completed: upload_completed,
        error_message: error_message,
        uploaded_media: uploaded_media,
    };
}




function x_mass_apply_preview(apply_id, s__id){

    //Select first:
    var first_id = $('#modal'+apply_id+' .mass_action_toggle option:first').val();
    $('.mass_action_item').addClass('hidden');
    $('.mass_id_' + first_id ).removeClass('hidden');
    $('#modal'+apply_id+' .mass_action_toggle').val(first_id);
    $('#modal'+apply_id+' input[name="s__id"]').val(s__id);
    $('#modal'+apply_id).modal('show');

    //Load Ppeview:
    $('#modal'+apply_id+' .x_mass_apply_preview').html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Loading');
    $.post("/ajax/x_mass_apply_preview", {
        apply_id: apply_id,
        s__id: s__id,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('#modal'+apply_id+' .x_mass_apply_preview').html(data);
    });

}


function load_editor(){

    $('.mass_action_toggle').change(function () {
        $('.mass_action_item').addClass('hidden');
        $('.mass_id_' + $(this).val() ).removeClass('hidden');
    });

    if(!search_enabled()){
        console.log("Search engine is disabled!");
        return false;
    }

    $('.e_text_finder').on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('@' + suggestion.s__handle);

    }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

        source: function (q, cb) {
            index_algolia.search(q, {
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
                return '<div class="main__title"><i class="far fa-exclamation-circle"></i> No Sources Found</div>';
            },
        }

    }]);

    $('.i_text_finder').on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('#' + suggestion.s__handle);

    }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

        source: function (q, cb) {
            index_algolia.search(q, {
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
                return '<div class="main__title"><i class="far fa-exclamation-circle"></i> No Ideas Found</div>';
            },
        }
    }]);

}


function view_s__title(suggestion){
    var title = ( suggestion._highlightResult && suggestion._highlightResult.s__title.value ? suggestion._highlightResult.s__title.value : suggestion.s__title );
    var max_limit = 89;
    return htmlentitiesjs( title.length>=max_limit ? title.substring(0,max_limit)+'...' : title );
}


function view_s_js_line(suggestion, default_handle = '@'){
    if(suggestion.s__type==12273){
        return '<span class="grey">#' + suggestion.s__handle + '</span>&nbsp;<span class="main__title">' + view_s__title(suggestion) + '</span>';
    } else if(suggestion.s__type==12274){
        return '<span class="icon-block-xs">'+ view_cover_js(suggestion.s__cover) +'</span><span class="grey">' + default_handle + suggestion.s__handle + '</span>&nbsp;<span class="main__title">' + view_s__title(suggestion) + '</span>';
    }
}

function e_load_finder(x__type) {
    console.log(x__type + " e_load_finder()");
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
}

function i_load_finder(x__type) {
    console.log(x__type + " i_load_finder()");
    //Load Search:
    var icons_listed = [];
    $('.new-list-'+x__type + ' .add-input').keypress(function (e) {
        icons_listed = [];
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code==13) || (e.ctrlKey && code==13)) {
            i__add(x__type, 0);
            return true;
        }
    });
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
        var search_only_app = $("#website_finder").val().charAt(0)=='-';
        var target_url = ( search_only_app ? suggestion.s__url.replace('/@','/') : suggestion.s__url );
        return '<div title="ID '+suggestion.s__id+'" class="card_cover mini-cover card-'+suggestion.s__type+' '+( search_only_app ? ' card-6287 ' : '' )+' card-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="'+target_url+'" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="'+target_url+'" class="main__title">'+(suggestion.s__cache.length ? suggestion.s__cache : '<span class="main__title">'+suggestion.s__title+'</span>' )+'</a></div></div></div>';
    } else if(x__type==26013){
        //Link Source
        return '<div title="ID '+suggestion.s__id+'" class="card_cover mini-cover card-'+suggestion.s__type+' card-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    }

}
function view_s_mini_js(s__cover,s__title){
    return '<span class="block-cover" title="'+s__title+'">'+ view_cover_js(s__cover) +'</span>';
}


function toggle_headline(x__type){

    var x__follower = 0;
    var x__next = 0;
    var focus__node = parseInt($('#focus__node').val());
    if(focus__node==12273){
        x__next = parseInt($('#focus__id').val());
    } else if (focus__node==12274){
        x__follower = parseInt($('#focus__id').val());
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

        //Currently closed, must now be opened
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

}


function e_sort_load(x__type) {

    load_covers();

    console.log('Tring to load Source Sort for @'+x__type);

    var sort_item_count = parseInt($('.headline_body_' + x__type).attr('read-counter'));

    if(!js_n___13911.includes(x__type)){
        //Does not support sorting:
        console.log(x__type+' is not sortable');
        return false;
    } else if(sort_item_count<1 || sort_item_count>parseInt(js_e___6404[11064]['m__message'])){
        return false;
    }

    setTimeout(function () {
        var theobject = document.getElementById("list-in-"+x__type);
        if (!theobject) {
            //due to duplicate ideas belonging in this idea:
            console.log('No object');
            return false;
        }

        //Show sort icon:
        console.log('Completed Loading Sorting for @'+x__type)
        $('.sort_e_frame').removeClass('hidden');

        var sort = Sortable.create(theobject, {
            animation: 144, // ms, animation speed moving items when sorting, `0` � without animation
            draggable: "#list-in-"+x__type+" .sort_draggable", // Specifies which items inside the element should be sortable
            handle: "#list-in-"+x__type+" .sort_e_grab", // Restricts sort start click/touch to the specified element
            onUpdate: function (evt/**Event*/) {
                e_sort_save(x__type);
            }
        });
    }, 377);

}



window.onpopstate = function(event) {
    load_hashtag_menu();
};

function load_hashtag_menu(load_hashtag = null){
    if(load_hashtag){
        toggle_pills(load_hashtag);
    } else if(document.location.hash){
        var hashtag = document.location.hash.substr(1);
        if(hashtag && hashtag.length>0){
            toggle_pills(hashtag);
        }
    }
}


function set_hashtag_if_empty(x__type_hash){
    //Will only set the hashtag if not already set
    //This prevents the default tab to override a specific hashtag load request...
    if(!window.location.hash || !$('.handle_nav_'+document.location.hash.substr(1)).attr('x__type')) {
        window.location.hash = '#'+x__type_hash;
    }
}






var loading_in_progress = false;
var pills_loading = null;
var loaded_pills = [];
function toggle_pills(x__type_hash){

    console.log('Toggle Pill: '+x__type_hash);

    if(pills_loading && !loaded_pills.includes(x__type_hash)){
        return false;
    } else if (loading_in_progress){
        return false;
    }

    console.log('Toggle Pill Active: '+x__type_hash);

    if($('.handle_nav_'+x__type_hash).attr('x__type') && $('.handle_nav_'+x__type_hash).attr('x__type').length){
        x__type = parseInt($('.handle_nav_'+x__type_hash).attr('x__type'));
    } else {
        console.log('ERROR: #'+x__type_hash+' is not a valid menu.');
        return false;
    }

    loading_in_progress = true;

    if(!loaded_pills.includes(x__type_hash)){
        pills_loading = x__type_hash;
    }

    var x__follower = 0;
    var x__next = 0;
    var focus__node = parseInt($('#focus__node').val());

    if(focus__node==12273){
        x__next = parseInt($('#focus__id').val());
    } else if (focus__node==12274){
        x__follower = parseInt($('#focus__id').val());
    }

    //Toggle view
    $('.xtypetitle').addClass('hidden');
    $('.xtypetitle_'+x__type).removeClass('hidden');


    if(!$('.thepill' + x__type+' .nav-link').hasClass('active')){

        //Currently closed, must now be opened:
        var action_id = 26007; //Open

        //Hide all elements
        $('.nav-link').removeClass('active');
        $('.headlinebody').addClass('hidden');
        $('.thepill' + x__type+ ' .nav-link').addClass('active');
        $('.headline_body_' + x__type).removeClass('hidden');

        //Set focus tab:
        console.log('focus_x__group Updated from '+focus_x__group+' to '+x__type);
        focus_x__group = x__type;
        if(!window.location.hash || window.location.hash!=$('.thepill' + x__type+' .nav-link').attr('href')) {
            window.location.hash = $('.thepill' + x__type+' .nav-link').attr('href');
        }

        //Do we need to load data via ajax?
        if( !loaded_pills.includes(x__type_hash) ){

            $('.headline_body_' + x__type + ' .tab_content').html('<div class="center" style="padding-top: 13px;"><i class="fas fa-yin-yang fa-spin"></i></div>');

            var focus__node = parseInt($('#focus__node').val());
            console.log('Tab loading from @'+focus__node+' for @'+x__type);

            if(focus__node==12273){

                var loading_url = "/ajax/i_view_body";
                var loading_data = {
                    focus__node:focus__node,
                    x__type:x__type,
                    counter:$('.headline_body_' + x__type).attr('read-counter'),
                    i__id:parseInt($('#focus__id').val()),
                    js_request_uri: js_request_uri, //Always append to AJAX Calls
                };

            } else if(focus__node==12274){

                var loading_url = "/ajax/e_view_body";
                var loading_data = {
                    focus__node:focus__node,
                    x__type:x__type,
                    counter:$('.headline_body_'+x__type).attr('read-counter'),
                    e__id:parseInt($('#focus__id').val()),
                    js_request_uri: js_request_uri, //Always append to AJAX Calls
                };

            } else {

                //Whaaaat is this?
                console.log('ERROR: Unknown Tab!');
                loading_in_progress = false;
                return false;

            }

            //Load data:
            $.post(loading_url, loading_data, function (data) {

                //Add data to the page:
                $('.headline_body_' + x__type + ' .tab_content').html(data);

                loaded_pills.push(x__type_hash);

                load_card_clickers();
                initiate_algolia();
                load_editor();
                x_set_start_text();
                set_autosize($('.x_set_class_text'));

                $(function () {
                    var $win = $(window);
                    $win.scroll(function () {
                        //Download loading from bottom:
                        if (parseInt($(document).height() - ($win.height() + $win.scrollTop())) < 377) {
                            x_view_load_page();
                        }
                    });
                });

                setTimeout(function () {

                    if(js_n___11020.includes(x__type) || (focus__node==12274 && (js_n___42261.includes(x__type) || js_n___42284.includes(x__type)))){
                        i_sort_load(x__type);
                    } else if(js_n___11028.includes(x__type) || (focus__node==12273 && (js_n___42261.includes(x__type) || js_n___42284.includes(x__type)))) {
                        e_sort_load(x__type);
                    }

                    activate_popover();

                    pills_loading = null;

                }, 233);

                loading_in_progress = false;

            });

        } else {
            loading_in_progress = false;
        }

    } else {
        loading_in_progress = false;
    }
}



function i_copy(i__id, do_recursive){

    //Go ahead and delete:
    $.post("/ajax/i_copy", {
        i__id:i__id,
        do_recursive:do_recursive,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if(data.status){
            js_redirect(js_e___42903[33286]['m__message']+data.new_i__hashtag);
        } else {
            alert('ERROR:' + data.message);
        }
    });
}

function source_title(e__id){
    //Load Instant Fields:
    var return_title = '';
    if($('.text__6197_'+e__id+':first').text().length){
        return_title = $('.text__6197_'+e__id+':first').text();
    } else if($('.text__6197_'+e__id+':first').val().length){
        return_title = $('.text__6197_'+e__id+':first').val();
    }
    return return_title;
}

function e_copy(e__id){

    var copy_source_title = prompt("What would be the title of the new source?", source_title(e__id));
    if (!copy_source_title.length) {
        alert('You must enter a title to copy.');
        return false;
    }

    //Go ahead and delete:
    $.post("/ajax/e_copy", {
        e__id:e__id,
        copy_source_title:copy_source_title,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if(data.status){
            js_redirect(js_e___42903[42902]['m__message']+data.new_e__handle);
        } else {
            alert('ERROR:' + data.message);
        }
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
    $.post("/ajax/x_remove", {
        x__id:x__id,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
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




function update__cover(new_cover, changed = true){
    $('#modal31912 .save_e__cover').val( new_cover );
    update_cover_main(new_cover, '.demo_cover');
    watch_cover_change(new_cover);
    if(changed){
        has_unsaved_changes = true;
    }
}
function image_cover(cover_preview, cover_apply, new_title){
    return '<a href="javascript:void(0);" onclick="update__cover(\''+cover_apply+'\')">' + view_s_mini_js(cover_preview, new_title) + '</a>';
}



function initiate_algolia(){
    $(".algolia_finder").focus(function () {
        if(!index_algolia && search_enabled()){
            //Loadup Algolia once:
            client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
            index_algolia = client.initIndex('alg_index');
        }
    });
}

function e_load_cover(x__type, e__id, counter, first_segment){

    if($('.coins_e_'+e__id+'_'+x__type).html().length){
        //Already loaded:
       return false;
    }

    $('.coins_e_'+e__id+'_'+x__type).html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/ajax/e_load_cover", {
        x__type:x__type,
        e__id:e__id,
        counter:counter,
        first_segment:first_segment,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('.coins_e_'+e__id+'_'+x__type).html(data);
    });

}

function i_load_cover(x__type, i__id, counter, first_segment, current_e){

    if($('.coins_i_'+i__id+'_'+x__type).html().length){
        //Already loaded:
        return false;
    }

    $('.coins_i_'+i__id+'_'+x__type).html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/ajax/i_load_cover", {
        x__type:x__type,
        i__id:i__id,
        counter:counter,
        first_segment:first_segment,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('.coins_i_'+i__id+'_'+x__type).html(data);
    });

}


//Main navigation
var search_on = false;
function toggle_finder(){

    $('.left_nav').addClass('hidden');
    $('.icon_finder').toggleClass('hidden');

    if(search_on){

        //Turn OFF
        search_on = false; //Reverse
        $('.max_width').removeClass('search_bar');
        $('.logo_frame, .container_content').removeClass('hidden');
        $('.nav_finder, #container_finder').addClass('hidden');

    } else {

        //Turn ON
        search_on = true; //Reverse
        $('.max_width').addClass('search_bar');
        $('.logo_frame, .container_content').addClass('hidden');
        $('.nav_finder, #container_finder').removeClass('hidden');
        $("#container_finder .row").html(''); //Reset results view
        $('#website_finder').focus();

        setTimeout(function () {
            //One more time to make sure it also works in mobile:
            $('#website_finder').focus();
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

function add_media(result_info){

}


function load_card_clickers(){

    $(".card_click").unbind();
    var ignore_clicks = 'a, .btn, textarea, .x__message, .cover_wrapper12273, .ignore-click, .focus-cover, .ref_source, .this_selector';
    $( ".card_click" ).click(function(e) {
        if($(e.target).closest(ignore_clicks).length < 1 && $(this).attr('href').length){
            js_redirect($(this).attr('href'));
        }
    });

    if(typeof focus_i__type !== 'undefined' && focus_i__type>0){
        var is_single_choice = ( focus_i__type==6684 );
        $(".this_selector").click(function (e) {
            console.log('CLICKED');

            if($('.this_selector_'+$(this).attr('selection_i__id')+' i').hasClass('fa-square-check')){

                console.log('Already Selected');

                //Already selected, so unselect:
                $('.this_selector_'+$(this).attr('selection_i__id')+' i').removeClass('fas').removeClass('fa-square-check').addClass('far').addClass('fa-square');

            } else {

                //Not selected, so Select now:
                if(is_single_choice){

                    console.log('Single Choice');

                    //Unselect the previously selected:
                    $('.this_selector:not(.this_selector_'+$(this).attr('selection_i__id')+') i.fa-square-check').each(function () {
                        $(this).removeClass('fas').removeClass('fa-square-check').addClass('far').addClass('fa-square');
                    });
                    //Go Next:
                    if(!$('.input_ui_'+$(this).attr('selection_i__id'))[0]){
                        //Since there is no input for this single select, we can instantly go next:
                        setTimeout(function () {
                            go_next(0);
                        }, 89);
                    } else {
                        //Make button visible if hidden:
                        $(".fixed-bottom").removeClass('hidden');
                    }
                } else {
                    console.log('NOT Single Choice');
                    //Make button visible if hidden:
                    $(".fixed-bottom").removeClass('hidden');
                }

                if($('.input_ui_'+$(this).attr('selection_i__id'))[0]){
                    $('.input_ui_'+$(this).attr('selection_i__id')+' .x_write').focus();
                }
                $('.this_selector_'+$(this).attr('selection_i__id')+' i').removeClass('far').removeClass('fa-square').addClass('fas').addClass('fa-square-check');

            }
        });
    }
}



var busy_processing = false;
function sale_increment(increment, i__id, max_allowed, min_allowed, unit_total, unit_fee){

    var new_quantity = parseInt($('.input_ui_'+i__id+' .current_sales').text()) + increment;

    if(new_quantity<1){
        //Invalid new quantity
        return false;
    } else if (new_quantity<min_allowed){
        if(min_allowed>1){
            alert('Error: Minimum Allowed is '+min_allowed);
        }
        return false;
    } else if (new_quantity>max_allowed){
        alert('Error: Maximum Allowed is '+max_allowed);
        return false;
    } else if(busy_processing){
        return false;
    }

    busy_processing = true;


    var handling_total = ( unit_fee * new_quantity );
    var new_total = ( unit_total * new_quantity );

    //Update UI:
    $(".input_ui_"+i__id+" .i__quantity").val(new_quantity);
    $(".input_ui_"+i__id+" .paypal_handling").val(handling_total);
    $(".input_ui_"+i__id+" .current_sales").text(new_quantity);
    $(".input_ui_"+i__id+" .total_ui").text(new_total.toFixed(2));

    busy_processing = false;

}


function random_animal(basic_style = false){
    var animals = ['fa-hippo','fa-otter','fa-sheep','fa-rabbit','fa-pig','fa-dog','fa-elephant','fa-deer','fa-cow','fa-alicorn','fa-rabbit','fa-monkey','fa-cat','fa-cat-space','fa-fish','fa-dragon','fa-whale','fa-turtle','fa-snake','fa-spider','fa-lobster','fa-duck','fa-dove','fa-crow','fa-dinosaur','fa-bee','fa-horse','fa-raccoon','fa-pegasus','fa-bat','fa-deer','fa-badger-honey','fa-squirrel','fa-ram','fa-dolphin','fa-bird','fa-crab','fa-worm','fa-kiwi-bird','fa-shrimp','fa-duck','fa-teddy-bear','fa-t-rex'];
    return 'far '+animals[Math.floor(Math.random()*animals.length)];
}

var interval = null;
function activate_popover(){

    if(interval){
        clearInterval(interval);
    }


    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover({
        html: true,
        //title: '<a class="close" href="javascript:void(0);" style="display: block;">Close</a>',
        content: function (inner_content) {
            $.post("/ajax/load_popover", {
                handle_string:inner_content.innerText,
                js_request_uri: js_request_uri, //Always append to AJAX Calls
            }, function (data) {
                $('.popover-body').html(data);
                load_covers();
                x_set_start_text();
                load_card_clickers();
            });
            return '<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>';
        }
    });

    $(document).click(function (e) {
        if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });
    /*
    $('body').on('click', function (e) {
        if ($(e.target).data('toggle') !== 'popover' && $(e.target).parents('[data-toggle="popover"]').length === 0
            && $(e.target).parents('.popover.in').length === 0) {
            (($('[data-toggle="popover"]').popover('hide').data('bs.popover') || {}).inState || {}).click = false;
        }
    });
    */
    $('[data-toggle="popover"]').on('click', function (e) {
        e.preventDefault();
        $('[data-toggle="popover"]').not(this).popover('hide');
    });
}



var index_algolia = false;
$(document).ready(function () {

    //Look for power editor updates:
    $('.x_set_class_text').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code==13) {
            x_set_text(this);
            e.preventDefault();
        }
    }).change(function() {
        x_set_text(this);
    });

    activate_popover();

    activate_cover_watch();

    //Only for idea page but still:
    set_autosize($('.text__6197_'+parseInt($('#focus__id').val())));

    $(document).on('keydown', function ( e ) {
        // You may replace `c` with whatever key you want
        if (e.ctrlKey) {
            if(String.fromCharCode(e.which).toLowerCase() === 'i'){
                //Add Idea
                i_editor_load();
            } else if(String.fromCharCode(e.which).toLowerCase() === 's'){
                //Add Source:
                e_editor_load(0,0);
            } else if(String.fromCharCode(e.which).toLowerCase() === 'f' && search_enabled()){
                //Finder:
                toggle_finder();
            }
        }
    });


    load_card_clickers();

    setTimeout(function () {
        load_covers();
    }, 987);

    //Lookout for textinput updates
    x_set_start_text();

    $('#website_finder').keyup(function() {
        if(!$(this).val().length){
            $("#container_finder .row").html(''); //Reset results view
        }
    });

    //For the S shortcut to load search:
    $("#website_finder").focus(function() {
        if(!search_on){
            toggle_finder();
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
                toggle_finder();
            }

        }
    });

    //Search that also has insert module:
    if(search_enabled()){

        $('.algolia__i').textcomplete([
            {
                match: /(^|\s)#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
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
                    return ' #' + suggestion.s__handle + ' ';
                }
            },
            {
                match: /(^|\s)!#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
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
                    return ' !#' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
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
                    return ' @' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__ce').textcomplete([
            {
                match: /(^|\s)\/(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_e___6404[31112]['m__message'],
                        filters: 's__type=12274 AND _tags:z_4997', //Source Commands
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
                    return view_s_js_line(suggestion, '/');
                },
                replace: function (suggestion) {
                    return '/' + suggestion.s__handle + ' @';
                }
            },
        ]);

        $('.algolia__ci').textcomplete([
            {
                match: /(^|\s)\/(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_e___6404[31112]['m__message'],
                        filters: 's__type=12274 AND _tags:z_12589', //Idea Commands
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
                    return view_s_js_line(suggestion, '/');
                },
                replace: function (suggestion) {
                    return '/' + suggestion.s__handle + ' @';
                }
            },
        ]);
    }


    activate_popover();


    //Prevent search submit:
    $('#searchFrontForm').on('submit', function(e) {
        e.preventDefault();
        return false;
    });


    if(!search_enabled()){
        console.log("Search engine is disabled!");
        return false;
    }

    var icons_listed = [];

    //TOP SEARCH
    $("#website_finder").autocomplete({minLength: 1, autoselect: false, keyboardShortcuts: ['s']}, [
        {
            source: function (q, cb) {

                icons_listed = [];

                //Members can filter search with first word:
                var search_only_e = $("#website_finder").val().charAt(0)=='@';
                var search_only_in = $("#website_finder").val().charAt(0)=='#';
                var search_only_app = $("#website_finder").val().charAt(0)=='-';
                $("#container_finder .row").html(''); //Reset results view


                //Do not search if specific command ONLY:
                if (( search_only_in || search_only_e || search_only_app ) && !isNaN($("#website_finder").val().substr(1)) ) {

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
                        search_filters += ' s__type=12274 AND _tags:z_6287 ';
                    }

                    if(js_pl_id > 0){

                        //For Members:
                        if(!js_session_superpowers_unlocked.includes(12701)){
                            //Can view limited sources:
                            if(search_filters.length>0){
                                search_filters += ' AND ';
                            }
                            search_filters += ' ( _tags:public_index OR _tags:z_' + js_pl_id + ' ) ';
                        }

                    } else {

                        //Guest can search ideas only by default as they start typing;
                        if(search_filters.length>0){
                            search_filters += ' AND ';
                        }
                        search_filters += ' _tags:public_index ';

                    }

                    //Append filters:
                    index_algolia.search(q, {
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
                        $("#container_finder .row").append(view_s_js_cover(26011, suggestion, 0));
                    }
                    return false;
                },
                empty: function (data) {
                    $("#container_finder .row").html('<div class="main__title margin-top-down-half"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No results found</div>');
                },
            }
        }
    ]);

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
        return '<i class="far fa-circle"></i>';
    }
}

function update_cover_mini(cover_code, target_css){
    //Update:
    $(target_css).html(view_cover_js(cover_code));
}



function i_editor_switch(link_x__type = 0, next_i__id = 0, previous_i__id = 0, do_checks = 0){
    console.log('SWITCHING TO '+link_x__type+'/'+next_i__id+'/'+previous_i__id+'/'+do_checks+'/'+$('#modal31911 .save_i__message').val()+'/'+parseInt($('#modal31911 .created_i__id').val()));

    if(!next_i__id && !previous_i__id && !link_x__type && !do_checks){
        /*
        var r = confirm("Are you sure you want to unlink this idea?");
        if (!(r==true)) {
            return false;
        }
        */
    }
    //Will switch the nature/direction of the link:
    return i_editor_load(0, 0, link_x__type, next_i__id, previous_i__id, do_checks, $('#modal31911 .save_i__message').val(), parseInt($('#modal31911 .created_i__id').val()));
}

function display_media(mediaframe_id, uploader_id, i__id){
    console.log('display_media: '+mediaframe_id+'/'+uploader_id+'/'+i__id);
    $(".ui_i__cache_"+i__id+" .media_display").each(function () {
        $('#'+mediaframe_id).append('<div id="'+$(this).attr('id')+'" class="media_item" media_e__id="" playback_code="" e__id="0"  e__cover=""></div>');
        cloudinary_preview_source(uploader_id, $(this).attr('id'), $(this).attr('media_e__id'), $(this).attr('playback_code'), $(this).attr('e__cover'), $(this).attr('e__title'), $(this).attr('e__id'));
    });
    sort_media(mediaframe_id);
}

function i_editor_load(i__id = 0, x__id = 0, link_x__type = 0, next_i__id = 0, previous_i__id = 0, do_checks = 1, load_message = '', passon_i__id = 0){


    $(".idea_link_direction, .idea_link_unlink, .idea_link_type").addClass('hidden');
    var focus_i_id = ( parseInt($('#focus__node').val())==12273 ? parseInt($('#focus__id').val()) : 0 );
    $("#modal31911 .save_results").html('');

    if(!passon_i__id){

        //Reset Fields:
        has_unsaved_changes = false;
        $('#modal31911 .media_frame').html('');
        $("#modal31911 .dynamic_item").attr('d__id','').attr('d_x__id','');
        $("#modal31911 .dynamic_item input").attr('placeholder', '').val('');
        $('#modal31911 .created_i__id').val(0);
        $("#modal31911 .unsaved_warning").val('');
        $("#modal31911 .save_x__frame").addClass('hidden');
        $('#modal31911 .save_i__id, #modal31911 .save_x__id').val(0);

        //Are we adding an idea for a target action tab?
        console.log('i Modal loaded for '+focus_x__group);
        if(focus_i_id && do_checks && focus_x__group>0 && !next_i__id && !previous_i__id && !i__id && !x__id && !link_x__type){
            if(js_n___42265.includes(focus_x__group) || !js_session_superpowers_unlocked.includes(10939)){
                //Next idea group:
                next_i__id = focus_i_id;
                link_x__type = ( js_session_superpowers_unlocked.includes(10939) ? 4228 : 30901); //Sequence or Comment
            } else if(js_n___42380.includes(focus_x__group)) {
                //Previous idea group:
                previous_i__id = focus_i_id;
                link_x__type = ( js_session_superpowers_unlocked.includes(10939) ? 4228 : 30901); //Sequence or Comment
            }
        }


        if(!link_x__type && do_checks && !i__id && !next_i__id && !previous_i__id && focus_i_id){
            console.log('MATCH');
            next_i__id = focus_i_id;
            link_x__type = ( js_session_superpowers_unlocked.includes(10939) ? 4228 : 30901);
        }
    }




    //Load Link addition info, if any:
    $("#modal31911 .idea_list_next").html('');
    $("#modal31911 .idea_list_previous").html('');

    var is_next = next_i__id && js_n___4486.includes(link_x__type);
    var is_prev = previous_i__id && js_n___4486.includes(link_x__type);

    if(is_next || is_prev){

        i__id = 0;
        x__id = 0;
        var force_next_simplify = is_prev && !js_session_superpowers_unlocked.includes(42817);

        if(is_next || force_next_simplify){

            if(force_next_simplify){
                next_i__id = previous_i__id;
                previous_i__id = 0;
            }

            //Generate content:
            $("#modal31911 .idea_list_next").html('<div class="creator_box"></div>');
            $('.creator_frame_'+next_i__id+' .creator_headline>a').each(function () {
                $("#modal31911 .idea_list_next .creator_box").append('<div class="creator_headline">'+$(this).html()+'</div>');
            });
            $("#modal31911 .idea_list_next").append('<div class="idea_response">' + $('.ui_i__cache_'+next_i__id).html() + '</div>');

            //Adjust Link:
            $('.idea_link_direction').removeClass('hidden').attr('onclick','i_editor_switch('+link_x__type+',0,'+next_i__id+',1)');

        } else if(is_prev){

            //Generate content:
            $("#modal31911 .idea_list_previous").html('<div class="creator_box"></div>');
            $('.creator_frame_'+previous_i__id+' .creator_headline>a').each(function () {
                $("#modal31911 .idea_list_previous .creator_box").append('<div class="creator_headline">'+$(this).html()+'</div>');
            });
            $("#modal31911 .idea_list_previous").append('<div class="idea_response">' + $('.ui_i__cache_'+previous_i__id).html() + '</div>');

            //Adjust Link:
            $('.idea_link_direction').removeClass('hidden').attr('onclick','i_editor_switch('+link_x__type+','+previous_i__id+',0,1)');

        }

        $('.idea_link_unlink, .idea_link_type').removeClass('hidden');
        if(!passon_i__id){
            update_form_select(4486, link_x__type, 1, true);
        }
    }


    //Assign updates:
    $('#modal31911 .next_i__id').val(next_i__id);
    $('#modal31911 .previous_i__id').val(previous_i__id);


    if(i__id){

        var current_i__type = $('.s__12273_'+i__id).attr('i__type');
        var current_i__privacy = $('.s__12273_'+i__id).attr('i__privacy');

        //Editig an existing idea:
        $('#modal31911 .save_i__id').val(i__id);
        $('#modal31911 .hash_group').removeClass('hidden');
        $('#modal31911 .save_i__hashtag').val($('.ui_i__hashtag_'+i__id+':first').text());
        $('#modal31911 .save_i__message').val($('.ui_i__message_'+i__id+':first').text());

        //Display Current Media:
        display_media('media_editor_frame', 13572, i__id);

    } else if(passon_i__id) {

        $("#modal31911 .save_i__message").val(load_message);

    } else {

        //See the default passed to the form:
        var current_i__type = 6677;
        var current_i__privacy = 31005;

        //Hide hashtag:
        $('#modal31911 .hash_group').addClass('hidden');

        //See where we are at and append anything needed to the idea:
        var insert_message = '';
        if(!next_i__id && !previous_i__id){
            var focus__node = parseInt($('#focus__node').val());
            if(focus__node==12273){
                //insert_message = '#'+$('#focus_handle').val()+' ';
            } else if (focus__node==12274 && parseInt($('#focus__id').val())!=js_pl_id){
                insert_message = '@'+$('#focus_handle').val()+' ';
            }
        }

        if(insert_text.length && !insert_message.length){
            insert_message = insert_text;
        }

        if(insert_message.length){
            $("#modal31911 .save_i__message").val(insert_message);
        }

    }

    if(x__id){
        $('#modal31911 .save_x__id').val(x__id);

        //Idea<>Idea links do not have an interaction message
        if(parseInt($('#focus__node').val())!=12273 || ($('.ui_x__message_'+x__id+':first') && $('.ui_x__message_'+x__id+':first').text().length>0)){
            $('#modal31911 .save_x__message').val($('.ui_x__message_'+x__id+':first').text());
            $('#modal31911 .save_x__frame').removeClass('hidden');
        }
    }


    if(!passon_i__id){

        //Idea Privacy:
        update_form_select(31004, current_i__privacy, 1, true);

        //Idea Type:
        update_form_select(4737, current_i__type, 1, true);

        //Activate Modal:
        $('#modal31911').modal('show');

        setTimeout(function () {
            //Adjust sizes:
            set_autosize($('#modal31911 .save_i__message'));
            set_autosize($('#modal31911 .save_x__message'));
        }, 233);

        var created_i__id = load_i_dynamic(i__id, x__id, current_i__type, true);

    }

    setTimeout(function () {
        //Focus on writing a message:
        $('#modal31911 .save_i__message').focus();
    }, 611);

}

function load_i_dynamic(i__id, x__id, current_i__type, initial_loading){

    $(".dynamic_item").addClass('hidden'); //Hide all current items...
    $(".dynamic_editing_loading").removeClass('hidden');
    var created_i__id = 0;

    $.post("/ajax/i_editor_load", {
        i__id: i__id,
        x__id: x__id,
        current_i__type: current_i__type,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {

        $(".dynamic_editing_loading").addClass('hidden');

        if (data.status) {

            if(!i__id && data.created_i__id>0){
                console.log('NEW IDEA #'+data.created_i__id+' has been created');
                created_i__id = data.created_i__id;
                $('#modal31911 .created_i__id').val(created_i__id);
                i__id = created_i__id;
            }

            if(initial_loading){

                //Initiate Idea  Uploader:
                load_cloudinary(13572, i__id, ['#'+i__id], '.uploader_13572', '#modal31911');

                //Track unsaved changes to prevent unwated modal closure:
                $("#modal31911 .unsaved_warning").change(function() {
                    has_unsaved_changes = true;
                });

            }

            var current_header = null;

            //Dynamic Input Fields:
            for(let i=1;i<=js_e___6404[42206]['m__message'];i++) {

                var index_i = i-1;

                if(data.return_inputs[index_i] == undefined){
                    data.return_inputs[index_i] = [];
                    data.return_inputs[index_i]["d__id"] = 0;
                    data.return_inputs[index_i]["d_x__id"] = 0;
                    data.return_inputs[index_i]["d__html"] = '';
                    data.return_inputs[index_i]["d__value"] = '';
                    data.return_inputs[index_i]["d__type_name"] = '';
                    data.return_inputs[index_i]["d__placeholder"] = '';
                    $("#modal31911 .dynamic_"+i).addClass('hidden');
                } else {
                    $("#modal31911 .dynamic_"+i).removeClass('hidden');
                }

                //Append profile header if changed:
                if(!current_header || current_header!=data.return_inputs[index_i]["d__profile_header"]){
                    current_header = data.return_inputs[index_i]["d__profile_header"];
                } else {
                    //Neutralize it:
                    data.return_inputs[index_i]["d__profile_header"] = '';
                }


                var is_locked = js_n___32145.includes(parseInt(data.return_inputs[index_i]["d__id"]));
                if(is_locked && !data.return_inputs[index_i]["d__value"].length){
                    //Hide since its locked without a value:
                    $("#modal31911 .dynamic_"+i+" .inner_dynamic").addClass('hidden');
                } else {
                    $("#modal31911 .dynamic_"+i+" .inner_dynamic").removeClass('hidden');
                }

                $("#modal31911 .dynamic_"+i+" .radio_frame").remove();
                $("#modal31911 .dynamic_"+i).attr('d__id',data.return_inputs[index_i]["d__id"]).attr('d_x__id',data.return_inputs[index_i]["d_x__id"]);

                if(data.return_inputs[index_i]["d__is_radio"]){
                    $("#modal31911 .dynamic_"+i).prepend( '<div class="radio_frame hideIfEmpty">' + data.return_inputs[index_i]["d__profile_header"] + data.return_inputs[index_i]["d__html"] + '</div>' );
                    $("#modal31911 .dynamic_"+i+" .text_content").addClass('hidden');
                } else {
                    $("#modal31911 .dynamic_"+i).prepend( '<div class="radio_frame hideIfEmpty">' + data.return_inputs[index_i]["d__profile_header"] + '</div>' );
                    $("#modal31911 .dynamic_"+i+" .text_content").removeClass('hidden');
                    $("#modal31911 .dynamic_"+i+" h3").html(data.return_inputs[index_i]["d__html"]);
                    $("#modal31911 .dynamic_"+i+" input").attr('placeholder',data.return_inputs[index_i]["d__placeholder"]).attr('type',data.return_inputs[index_i]["d__type_name"]).val(data.return_inputs[index_i]["d__value"]).prop('disabled', is_locked);

                    if(x__id && parseInt($('#focus__node').val())==12274 && data.return_inputs[index_i]["d__id"]==parseInt($('#focus__id').val())){
                        //Hide message textarea since this is already loaded in the dynamic inputs:
                        $("#modal31911 .save_x__message").val('IGNORE_INPUT');
                        $("#modal31911 .save_x__frame").addClass('hidden');
                    }
                }

            }

            setTimeout(function () {

                activate_popover();

            }, 377);

        } else if (data.message) {

            //Should not have an issue loading
            alert('ERROR:' + data.message);

        }
    });
    return created_i__id;
}



var i_saving = false; //Prevent double saving
function i_editor_save(){

    if(i_saving){
        return false;
    }

    i_saving = true;
    $(".i_editor_save").html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');
    $("#modal31911 .save_results").html('');

    var current_i__id = parseInt($('#modal31911 .save_i__id').val());
    var created_i__id = parseInt($('#modal31911 .created_i__id').val());

    //Fetch Media
    var gather_media_result = gather_media('#modal31911 .media_frame .media_item', 13572);
    if(!gather_media_result['upload_completed']){
        i_saving = false;
        $(".i_editor_save").html('SAVE');
        $("#modal31911 .save_results").html('<span class="icon-block"><i class="far fa-exclamation-circle"></i></span> Error: '+gather_media_result['error_message']);
        return false;
    }

    var modify_data = {
        focus__node:         parseInt($('#focus__node').val()),
        focus__id:           parseInt($('#focus__id').val()),
        save_i__id:         ( current_i__id>0 ? current_i__id : created_i__id ),
        save_x__id:         $('#modal31911 .save_x__id').val(),
        next_i__id:         $('#modal31911 .next_i__id').val(),
        previous_i__id:     $('#modal31911 .previous_i__id').val(),
        save_x__type:       $('.dropd_form_4486').attr('selected_value').trim(), //The final link type as selected by user if they have the superpower
        focus_x__group:     focus_x__group,
        save_x__message:    $('#modal31911 .save_x__message').val().trim(),
        save_i__message:    $('#modal31911 .save_i__message').val().trim(),
        save_i__hashtag:    $('#modal31911 .save_i__hashtag').val().trim(),
        save_i__type:       $('.dropd_form_4737').attr('selected_value').trim(),
        save_i__privacy:    $('.dropd_form_31004').attr('selected_value').trim(),
        uploaded_media:     gather_media_result['uploaded_media'],
        js_request_uri:     js_request_uri, //Always append to AJAX Calls
    };

    //Append Dynamic Data:
    for(let i=1;i<=js_e___6404[42206]['m__message'];i++) {
        if($('#modal31911 .dynamic_'+i).attr('d__id').length){
            modify_data['save_dynamic_'+i] = $('#modal31911 .dynamic_'+i).attr('d_x__id').trim() + '____' + $('#modal31911 .dynamic_'+i).attr('d__id').trim() + '____' + $('#modal31911 .save_dynamic_'+i).val().trim();
        } else {
            //Should be the end of variables:
            break;
        }
    }

    $.post("/ajax/i_editor_save", modify_data, function (data) {

        //Load Images:
        i_saving = false;
        $(".i_editor_save").html('SAVE');

        if (!data.status) {

            //Show Errors:
            $("#modal31911 .save_results").html('<span class="icon-block"><i class="far fa-exclamation-circle"></i></span> Error: '+data.message);

        } else {

            console.log(data.message);

            if(data.redirect_idea){
                //Give option to open the post:
                $(".i_footer_note").removeClass('hidden');
                $(".i_footer_note a").attr('href', data.redirect_idea);
                setTimeout(function () {
                    $(".i_footer_note").addClass('hidden');
                }, 6765);
            }

            //Update Handle & Href links if needed:
            var old_handle = $(".ui_i__hashtag_"+modify_data['save_i__id']+':first').text();
            var new_handle = modify_data['save_i__hashtag'];
            var on_focus__idea = parseInt($('#focus__node').val())==12273 && modify_data['save_i__id']==parseInt($('#focus__id').val());

            //Update Idea Type:
            $('.s__12273_'+modify_data['save_i__id']).attr('i__type', modify_data['save_i__type']);
            ui_instant_select(4737, modify_data['save_i__type'], modify_data['save_i__id'], modify_data['save_x__id'], false);

            //Update Idea Privacy:
            $('.s__12273_'+modify_data['save_i__id']).attr('i__privacy', modify_data['save_i__privacy']);
            ui_instant_select(31004, modify_data['save_i__privacy'], modify_data['save_i__id'], modify_data['save_x__id'], false);

            //Update Handle & Href links if needed:
            if(old_handle!=new_handle){
                if(on_focus__idea){
                    //Refresh page since focus item handle changed:
                    js_redirect(js_e___42903[33286]['m__message']+new_handle);
                } else {
                    //Update Hashtag & Link:
                    $('.s__12273_'+modify_data['save_i__id']).attr('i__hashtag', new_handle);
                    $(".ui_i__hashtag_"+modify_data['save_i__id']).text(new_handle).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233); //Flash
                }
            }

            //Reset errors:
            has_unsaved_changes = false;
            $('#modal31911').modal('hide');

            //Update Idea Message:
            $('.ui_i__message_'+modify_data['save_i__id']).text(modify_data['save_i__message']);

            //Insert idea into the page if new:
            console.log('START INSERTING');
            if(!current_i__id && created_i__id>0 && focus_x__group>0){

                console.log('ADD NEW '+modify_data['save_x__type']+' & x GROUP: '+focus_x__group);

                $("#list-in-" + focus_x__group).append(data.return_i__cache_full);

                adjust_counter(focus_x__group, 1);

                setTimeout(function () {
                    i_sort_load(focus_x__group);
                }, 987);

            } else {

                console.log('UPDATE  '+modify_data['save_x__type']+' & x GROUP: '+focus_x__group);

                //Update Cache otherwise:
                $('.ui_i__cache_'+modify_data['save_i__id']).html(data.return_i__cache_links);

            }

            //Show more if on focus idea:
            if(on_focus__idea){
                show_more(modify_data['save_i__id']);
            }

            if(modify_data['save_x__id'] && modify_data['save_x__message']!='IGNORE_INPUT'){
                $('.ui_x__message_'+modify_data['save_x__id']).text(modify_data['save_x__message']);
            }

            //Tooltips:
            setTimeout(function () {
                activate_popover();
            }, 987);

        }
    });
}

function sort_media(sort_id){
    var sort = Sortable.create(document.getElementById(sort_id), {
        animation: 144, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".media_item", // Specifies which items inside the element should be sortable
        handle: ".media_item", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            //Nothing we need to do since the order will be grabbed upon submission...
            //Just mark as unsaved again to make sure it saves:
            has_unsaved_changes = true;
        }
    });
}

var media_cache = []; //Stores the json data for successfully uploaded media files
function load_cloudinary(uploader_id, s__id, uploader_tags = [], loading_button = null, loading_modal = null, loading_inline_container = null){

    console.log('Initiating Uploader @'+uploader_id+' with tags '+uploader_tags.join(' & '));

    if(js_e___42363[uploader_id]==undefined){
        console.log('Unknown Uploader @'+uploader_id+' Missing in @42363');
        return false;
    }

    media_cache[uploader_id] = [];
    //Fetch global defaults:
    var default_max_file_count = parseFloat(js_e___6404[42382]['m__message']);

    var global_tags = ['@'+uploader_id, '@'+website_id, '@'+js_pl_id];
    var allow_videos = js_e___42390[uploader_id]!==undefined;
    var allow_imgaes = js_e___42389[uploader_id]!==undefined;
    var allow_audio = js_e___42644[uploader_id]!==undefined;

    if(!allow_videos && !allow_imgaes && !allow_audio){
        //Assume all are allowed:
        allow_audio = true;
        allow_videos = true;
        allow_imgaes = true;
    }

    //Initiate CLoudiary for cover:
    var max_file_count = ( js_e___42382[uploader_id]!==undefined && parseFloat(js_e___42382[uploader_id]['m__message'])>0 && parseFloat(js_e___42382[uploader_id]['m__message'])<default_max_file_count ? parseFloat(js_e___42382[uploader_id]['m__message']) : default_max_file_count );

    var enable_crop = ( js_e___42386[uploader_id]!==undefined );
    var force_crop = ( js_e___42387[uploader_id]!==undefined );


    var clientAllowedFormats = [];
    if(allow_videos){
        clientAllowedFormats = clientAllowedFormats.concat(js_e___42641[4258]['m__message'].split(' '));
    }
    if(allow_imgaes){
        clientAllowedFormats = clientAllowedFormats.concat(js_e___42641[4260]['m__message'].split(' '));
    }
    if(allow_audio){
        clientAllowedFormats = clientAllowedFormats.concat(js_e___42641[4259]['m__message'].split(' '));
    }

    var widget_setting = {

        multiple: ( max_file_count>1 ),
        max_files: max_file_count,
        maxFileSize: ( 2000 * 1000000 ),
        maxVideoFileSize: ( 2000 * 1000000 ),
        maxImageFileSize: ( 20 * 1000000 ),
        maxRawFileSize: ( 20 * 1000000 ),
        maxChunkSize: ( 100 * 1000000 ),

        clientAllowedFormats: clientAllowedFormats,
        cropping: enable_crop,
        showSkipCropButton: !force_crop,
        croppingShowBackButton: !force_crop,
        croppingAspectRatio: ( js_e___42388[uploader_id]!==undefined && parseFloat(js_e___42388[uploader_id]['m__message'])>0 ? parseFloat(js_e___42388[uploader_id]['m__message']) : null ),

        minImageWidth: ( js_e___42407[uploader_id]!==undefined && parseInt(js_e___42407[uploader_id]['m__message'])>0 ? parseInt(js_e___42407[uploader_id]['m__message']) : null ),
        maxImageWidth: ( js_e___42408[uploader_id]!==undefined && parseInt(js_e___42408[uploader_id]['m__message'])>0 ? parseInt(js_e___42408[uploader_id]['m__message']) : null ),
        minImageHeight: ( js_e___42409[uploader_id]!==undefined && parseInt(js_e___42409[uploader_id]['m__message'])>0 ? parseInt(js_e___42409[uploader_id]['m__message']) : null ),
        maxImageHeight: ( js_e___42410[uploader_id]!==undefined && parseInt(js_e___42410[uploader_id]['m__message'])>0 ? parseInt(js_e___42410[uploader_id]['m__message']) : null ),

        validateMaxWidthHeight: ( js_e___42411[uploader_id]!==undefined ),
        croppingValidateDimensions: ( js_e___42412[uploader_id]!==undefined ),

        inlineContainer: loading_inline_container,

        //Fixed variables:
        cloudName: 'menchcloud',
        uploadPreset: 'mench_uploader',
        showPoweredBy: false,
        autoMinimize: true,
        theme: 'minimal',
        tags: global_tags.concat(uploader_tags),
        sources: [ 'local', 'url', 'image_search', 'camera', 'unsplash'], //, 'google_drive', 'dropbox'
        defaultSource: 'local',
        styles:{
            palette: {
                window: "#FFFFFF",
                windowBorder: "#999999",
                tabIcon: "#000000",
                menuIcons: "#000000",
                textDark: "#000000",
                textLight: "#FFFFFF",
                link:  "#000000",
                action:  "#000000",
                inactiveTabIcon: "#999999",
                error: "#FC1B44",
                inProgress: "#000000",
                complete: "#000000",
                sourceBg: "#FFFFFF"
            },
            frame: {
                background: "#999999"
            }
        }
    };


    console.log(widget_setting);
    var widget = cloudinary.createUploadWidget(widget_setting, (error, result) => {

        if(error || !result){

            //Remove from screen if any:

            //Show error if any:
            if(result.failed && result.status && result.status.length>0){
                alert('ERROR for File ['+result.info.name+']: '+result.status);
                delete_media(uploader_id, result.info.id, true, true);
            }
            //Log error
            console.log('ERROR');
            console.log(result);


        } else if (result.event === "queues-start") {

            //Enable Sorting:
            if(uploader_id==13572){

                //Ideator Uploader
                sort_media('media_editor_frame');

            } else if(uploader_id==43004){

                //Discovery Uploader
                sort_media('media_outer_'+s__id);

            }

        } else if (result.event === "upload-added") {

            //Add Pending Loader
            console.log(result.event);
            console.log(result);

            //Append loaders:
            if(uploader_id==42359){

                //Source Cover Uploader:
                update__cover('fas fa-yin-yang fa-spin');

            } else if(uploader_id==13572){

                //Ideator Uploader
                has_unsaved_changes = true;
                $('#media_editor_frame').append('<div id="'+result.info.id+'" class="media_item" media_e__id="" playback_code="" e__id="0"  e__cover=""><span><i class="fas fa-yin-yang fa-spin"></i></span></div>');

            } else if(uploader_id==43004){

                //Discovery Uploader
                $('#media_outer_'+s__id).append('<div id="'+result.info.id+'" class="media_item" media_e__id="" playback_code="" e__id="0"  e__cover=""><span><i class="fas fa-yin-yang fa-spin"></i></span></div>');

            }

        } else if (result.event === "success") {

            console.log(result.event);
            console.log(result);

            //Add uploaded media:
            if(uploader_id==42359){

                //Source Cover Uploader:
                update__cover('https://res.cloudinary.com/menchcloud/image/upload/c_crop,g_custom/' + result.info.path);

            } else if(uploader_id==13572 || uploader_id==43004){

                //Idea Uploader
                var playback_code = '';
                var media_e__id = 0;
                if(result.info.format && result.info.format.length>0){
                    if(js_e___42641[4259]['m__message'].split(' ').includes(result.info.format) && result.info.is_audio){
                        //Audio
                        media_e__id = 4259;
                        playback_code = result.info.secure_url;
                    } else if(js_e___42641[4260]['m__message'].split(' ').includes(result.info.format) && result.info.resource_type=='image'){
                        //Image
                        media_e__id = 4260;
                        playback_code = ( result.info.thumbnail_url ? result.info.thumbnail_url.replaceAll('c_limit,h_60,w_90','w_1597,h_1597,c_fit') : result.info.secure_url );
                    } else if(js_e___42641[4258]['m__message'].split(' ').includes(result.info.format) && result.info.resource_type=='video'){
                        //Video
                        media_e__id = 4258;
                        playback_code = result.info.public_id;
                    }
                }

                //Append this to the main source:
                if(media_e__id) {

                    cloudinary_preview_source(uploader_id, result.info.id, media_e__id, playback_code, ( result.info.thumbnail_url ? result.info.thumbnail_url.replaceAll('c_limit,h_60,w_90','c_fill,h_377,w_377') : null ), ( result.info.original_filename ? js_e___42294[media_e__id]['m__title']+' '+result.info.original_filename.replaceAll('_',' ').replaceAll('-',' ').replaceAll('  ',' ').replaceAll('  ',' ').replaceAll('  ',' ') : js_e___42294[media_e__id]['m__title']+' File' ));

                    media_cache[uploader_id][result.info.id] = result.info;
                    console.log('MEDIA CACHE:');
                    console.log(media_cache);

                } else {

                    //Log error
                    console.log('ERROR: Missing Media Type');

                }

            }

        }

    });

    if(!loading_inline_container && loading_button && widget){
        //Attach to widget:
        $(loading_button).click(function (e) {
            widget.open();
        });
    }

    if(loading_modal && widget){
        //Attach to widget:
        $(loading_modal).on('hidden.bs.modal', function () {
            widget.destroy({ removeThumbnails: true })
                .then(() => {
                    console.log('Destroying Uploader @'+uploader_id);
                });
        });
    }

}


var confirm_removal_once_done = false;
function delete_media(uploader_id, info_id, remove_cache = true, skip_check = false){
    if(!skip_check && !confirm_removal_once_done){
        //Confirm removal once:
        var r = confirm("Are you sure you want to delete this?");
        if (!(r==true)) {
            return false;
        }
        confirm_removal_once_done = true; //Dont ask again
        has_unsaved_changes = true;
    }
    $('#'+info_id).remove();
    if(remove_cache && media_cache[uploader_id][info_id]){
        delete media_cache[uploader_id][info_id];
    }
}

function play_video(public_id){
    var cld = cloudinary.videoPlayer('video_player_'+public_id,{ cloudName: 'menchcloud' });
    cld.source(public_id);
}

function cloudinary_preview_source(uploader_id, info_id, media_e__id, playback_code, e__cover, e__title, e__id = 0){

    //Update meta variables:
    $('#'+info_id).attr('media_e__id',media_e__id).attr('playback_code',playback_code).attr('e__id',e__id).attr('e__cover',e__cover);

    if(media_e__id == 4258){

        //Video
        $('#'+info_id).html('<input type="text" value="'+e__title+'" placeholder="Source Title" class="hidden_superpower__13422" /><span title="'+js_e___42294[media_e__id]['m__title']+'">'+js_e___42294[media_e__id]['m__cover']+'</span><img src="'+e__cover+'" /><a href="javascript:void(0)" onclick="delete_media(\''+uploader_id+'\',\''+info_id+'\')"><i class="far fa-xmark"></i></a>');
        //<video id="video_player_'+playback_code+'" controls class="cld-video-player vjs-fade-out cld-fluid cld-video-player-skin-light" poster="'+e__cover+'"></video>
        //play_video(playback_code);

    } else if(media_e__id == 4260){

        //Image
        $('#'+info_id).html('<input type="text" value="'+e__title+'" placeholder="Source Title" class="hidden_superpower__13422" /><img src="'+e__cover+'" /><a href="javascript:void(0)" onclick="delete_media(\''+uploader_id+'\',\''+info_id+'\')"><i class="far fa-xmark"></i></a>');

    } else if(media_e__id == 4259){

        //Audio
        $('#'+info_id).html('<input type="text" value="'+e__title+'" placeholder="Source Title" class="hidden_superpower__13422" /><span title="'+js_e___42294[media_e__id]['m__title']+'">'+js_e___42294[media_e__id]['m__cover']+'</span><audio controls src="'+playback_code+'"></audio><a href="javascript:void(0)" onclick="delete_media(\''+uploader_id+'\',\''+info_id+'\')"><i class="far fa-xmark"></i></a>');

    } else {

        //Unsupported file, should not happen since we limited file extensions to those we know:
        alert('Upload Error: Uploaded File '+e__title+' is not a valid Video, Image or Audio file.');
        delete_media(uploader_id, info_id, true, true);

    }



}


function e_editor_load(e__id = 0, x__id = 0, bar_title = null, x__message = null){

    //Activate Modal:
    $('#modal31912').modal('show');

    //Reset Fields:
    has_unsaved_changes = false;

    $("#modal31912 .unsaved_warning").val('');

    $('#modal31912 .save_results').html('');
    $("#modal31912 .save_x__frame").addClass('hidden');
    $("#modal31912 .dynamic_item").attr('d__id','').attr('d_x__id','');
    $("#modal31912 .dynamic_item").attr('placeholder', '').val('');

    //Source resets:
    $('#search_cover').val('');
    $(".cover_history_content").html('');
    $(".cover_history_button").addClass('hidden');
    $('#modal31912 .black-background-obs').removeClass('isSelected');

    //Load Instant Fields:
    var current_title = source_title(e__id);
    var current_cover = $('.ui_e__cover_'+e__id+':first').attr('raw_cover');
    var current_privacy = $('.s__12274_'+e__id).attr('e__privacy');

    $('#modal31912 .save_e__id').val(e__id);
    $('#modal31912 .save_x__id').val(x__id);
    $('#modal31912 .save_e__handle').val($('.ui_e__handle_'+e__id+':first').text());
    $('#modal31912 .save_e__title').val(current_title);

    //Source Privacy:
    update_form_select(6177, current_privacy, 1, true);


    $('#modal31912 .random_animal').html('<i class="'+random_animal(true)+'"></i>');
    update__cover(current_cover, false);


    if(x__id){
        $('#modal31912 .save_x__message').val($('.ui_x__message_'+x__id).text());
        $('#modal31912 .save_x__frame').removeClass('hidden');
        setTimeout(function () {
            set_autosize($('#modal31912 .save_x__message'));
        }, 377);
    }
    setTimeout(function () {
        set_autosize($('#modal31912 .save_e__title'));
    }, 377);



    $.post("/ajax/e_editor_load", {
        e__id: e__id,
        x__id: x__id,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {

        if (data.status) {

            //Initiate Source Cover Uploader:
            load_cloudinary(42359, e__id, ['@'+e__id], '.uploader_42359', '#modal31912');

            //Dynamic Input Fields:
            var index_i_content = 0;
            var current_header = null;

            for(let i=1;i<=js_e___6404[42206]['m__message'];i++) {

                var index_i = i-1;
                if(data.return_inputs[index_i] == undefined){
                    data.return_inputs[index_i] = [];
                    data.return_inputs[index_i]["d__id"] = 0;
                    data.return_inputs[index_i]["d_x__id"] = 0;
                    data.return_inputs[index_i]["d__html"] = '';
                    data.return_inputs[index_i]["d__value"] = '';
                    data.return_inputs[index_i]["d__type_name"] = '';
                    data.return_inputs[index_i]["d__placeholder"] = '';
                    $("#modal31912 .dynamic_"+i).addClass('hidden');
                } else {
                    index_i_content++;
                    $("#modal31912 .dynamic_"+i).removeClass('hidden');
                }

                //Append profile header if changed:
                if(!current_header || current_header!=data.return_inputs[index_i]["d__profile_header"]){
                    current_header = data.return_inputs[index_i]["d__profile_header"];
                } else {
                    //Neutralize it:
                    data.return_inputs[index_i]["d__profile_header"] = '';
                }

                $("#modal31912 .dynamic_"+i+" .radio_frame").remove();
                $("#modal31912 .dynamic_"+i).attr('d__id',data.return_inputs[index_i]["d__id"]).attr('d_x__id',data.return_inputs[index_i]["d_x__id"]);

                var is_locked = js_n___32145.includes(parseInt(data.return_inputs[index_i]["d__id"]));
                if(is_locked && !data.return_inputs[index_i]["d__value"].length){
                    //Hide since its locked without a value:
                    $("#modal31912 .dynamic_"+i+" .inner_dynamic").addClass('hidden');
                } else {
                    $("#modal31912 .dynamic_"+i+" .inner_dynamic").removeClass('hidden');
                }

                if(data.return_inputs[index_i]["d__is_radio"]){
                    $("#modal31912 .dynamic_"+i).prepend( '<div class="radio_frame hideIfEmpty">' + data.return_inputs[index_i]["d__profile_header"] + data.return_inputs[index_i]["d__html"] + '</div>' );
                    $("#modal31912 .dynamic_"+i+" .text_content").addClass('hidden');
                } else {
                    $("#modal31912 .dynamic_"+i).prepend( '<div class="radio_frame hideIfEmpty">' + data.return_inputs[index_i]["d__profile_header"] + '</div>' );
                    $("#modal31912 .dynamic_"+i+" .text_content").removeClass('hidden');
                    $("#modal31912 .dynamic_"+i+" h3").html(data.return_inputs[index_i]["d__html"]);
                    $("#modal31912 .dynamic_"+i+" input").attr('placeholder',data.return_inputs[index_i]["d__placeholder"]).attr('type',data.return_inputs[index_i]["d__type_name"]).val(data.return_inputs[index_i]["d__value"]).prop('disabled', is_locked);

                    if(x__id && ( (parseInt($('#focus__node').val())==12274 && data.return_inputs[index_i]["d__id"]==parseInt($('#focus__id').val())) || data.return_inputs[index_i]["d__id"]==e__id )){
                        //Hide message textarea since this is already loaded in the dynamic inputs:
                        $("#modal31912 .save_x__message").val('IGNORE_INPUT');
                        $("#modal31912 .save_x__frame").addClass('hidden');
                    }
                }
            }

            //Add a second save button at the bottom if we have too much data:
            if(index_i_content > 5){
                $("#modal31912 .modal-footer").html('<button type="button" class="btn btn-default e_editor_save post_button" onclick="e_editor_save()">SAVE</button>');
            } else {
                $("#modal31912 .modal-footer").html('');
            }

            //Any Source suggestions to auto load?
            if(data.cover_history_content.length){
                $(".cover_history_button").removeClass('hidden');
                data.cover_history_content.forEach(function(item) {
                    $(".cover_history_content").append(image_cover(item.cover_preview, item.cover_apply, item.new_title));
                });
                $(".cover_history_content").append('<div class="doclear">&nbsp;</div>');
            }

            setTimeout(function () {
                activate_popover();
            }, 987);

        } else {

            //Should not have an issue loading
            alert('ERROR:' + data.message);

        }

    });

    //Track unsaved changes to prevent unwated modal closure:
    $("#modal31912 .unsaved_warning").change(function() {
        has_unsaved_changes = true;
    });

}

e_saving = false;
function e_editor_save(){

    if(e_saving){
        return false;
    }

    e_saving = true;
    $(".e_editor_save").html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');
    $("#modal31912 .save_results").html('');

    var modify_data = {
        save_e__id:         $('#modal31912 .save_e__id').val(),
        save_e__title:      $('#modal31912 .save_e__title').val().trim(),
        save_e__cover:      $('#modal31912 .save_e__cover').val().trim(),
        save_e__handle:     $('#modal31912 .save_e__handle').val().trim(),
        save_x__id:         $('#modal31912 .save_x__id').val(),
        save_x__message:    $('#modal31912 .save_x__message').val().trim(),
        save_e__privacy:    $('.dropd_form_6177').attr('selected_value').trim(),
        js_request_uri:     js_request_uri, //Always append to AJAX Calls
    };

    //Append Dynamic Data:
    for(let i=1;i<=js_e___6404[42206]['m__message'];i++) {
        if($('#modal31912 .dynamic_'+i).attr('d__id').length){
            modify_data['save_dynamic_'+i] = $('#modal31912 .dynamic_'+i).attr('d_x__id').trim() + '____' + $('#modal31912 .dynamic_'+i).attr('d__id').trim() + '____' + $('#modal31912 .save_dynamic_'+i).val().trim();
        } else {
            //Should be the end of variables:
            break;
        }
    }

    $.post("/ajax/e_editor_save", modify_data, function (data) {

        e_saving = false;
        $(".e_editor_save").html('SAVE');

        if (!data.status) {

            //Show Errors:
            $("#modal31912 .save_results").html('<span class="icon-block"><i class="far fa-exclamation-circle"></i></span> Error: '+data.message);

        } else {

            //Update Handle & Href links if needed:
            var old_handle = $(".ui_e__handle_"+modify_data['save_e__id']+':first').text();
            var new_handle = modify_data['save_e__handle'];
            if(old_handle!=new_handle){
                if(parseInt($('#focus__node').val())==12274 && modify_data['save_e__id']==parseInt($('#focus__id').val())){
                    //Refresh page since focus item handle changed:
                    return js_redirect(js_e___42903[42902]['m__message']+new_handle);
                } else {
                    //Make adjustments to current page:
                    $('.s__12274_'+modify_data['save_e__id']).attr('e__handle', new_handle);
                    $('.ui_e__handle_'+modify_data['save_e__id']).text(new_handle);
                    $(".handle_href_e_"+modify_data['save_e__id']).attr('href', $(".handle_href_e_"+modify_data['save_e__id']+':first').attr('href').replaceAll(old_handle, new_handle));
                }
            }

            //Update Title:
            update_text_name(6197, modify_data['save_e__id'], modify_data['save_e__title']);

            //Update Privacy:
            $('.s__12274_'+modify_data['save_e__id']).attr('e__privacy', modify_data['save_e__privacy']);
            ui_instant_select(6177, modify_data['save_e__privacy'], modify_data['save_e__id'], modify_data['save_x__id'], false);

            //Update Raw Cover:
            $('.ui_e__cover_'+modify_data['save_e__id']+':first').attr('raw_cover', modify_data['save_e__cover']);

            //Update Main Cover:
            update_cover_main(modify_data['save_e__cover'], '.s__12274_'+modify_data['save_e__id']);

            if( modify_data['save_x__id'] && modify_data['save_x__message']!='IGNORE_INPUT'){
                $('.ui_x__message_'+ modify_data['save_x__id']).text(modify_data['save_x__message']);
            }

            //Tooltips:
            activate_popover();
            setTimeout(function () {
                activate_popover();
            }, 987);

            has_unsaved_changes = false;
            $('#modal31912').modal('hide');

            //Do we need to refresh the page?
            if(parseInt($('#focus__node').val())==12274 && parseInt($('#focus__id').val())==modify_data['save_e__id']){
                //Refresh page since source edited their own profile:
                js_redirect(js_e___42903[42902]['m__message']+$('#focus_handle').val());
            }

        }

    });

}
















var busy_loading = false;
var current_page = [];
function x_view_load_page() {

    if(!focus_x__group){
        return false;
    }

    if(current_page[focus_x__group] == undefined){
        current_page[focus_x__group] = 1;
    }

    var current_total_count = parseInt($('.headline_body_' + focus_x__group).attr('read-counter')); //Total of that item
    var has_more_to_load = ( current_total_count > parseInt(js_e___6404[11064]['m__message']) * current_page[focus_x__group] );

    if(!has_more_to_load){
        return false;
    } else if(busy_loading){
        return false;
    }
    busy_loading = true;


    current_page[focus_x__group]++; //Now we can increment current page
    $('<div class="load-more"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Loading More</div>').insertAfter('#list-in-'+focus_x__group);
    $.post("/ajax/x_view_load_page", {
        focus__node: parseInt($('#focus__node').val()),
        focus__id: parseInt($('#focus__id').val()),
        x__type: focus_x__group,
        current_page: current_page[focus_x__group],
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('.load-more').remove();
        if(data.length){
            $('#list-in-'+focus_x__group).append(data);
            x_set_start_text();
            load_card_clickers();
            activate_popover();
        }
        busy_loading = false;
    });


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

    var original_photo = $('.mini-cover.card-12274.card-id-'+e_existing_id+' .cover-btn').html();
    $('.mini-cover.card-12274.card-id-'+e_existing_id+' .cover-btn').html('<i class="fas fa-yin-yang fa-spin"></i>');
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
    $.post("/ajax/e__add", {

        focus__node: parseInt($('#focus__node').val()),
        x__type: x__type,
        focus__id: parseInt($('#focus__id').val()),
        e_existing_id: e_existing_id,
        e_new_string: e_new_string,
        js_request_uri: js_request_uri, //Always append to AJAX Calls

    }, function (data) {

        e_is_adding = false;

        if (data.status) {

            if(data.e_already_linked){
                var r = confirm("This is already linked here! Are you sure you want to double link it?");
                if (r==true) {
                    data.e_already_linked = false;
                } else {
                    $('.mini-cover.card-12274.card-id-'+e_existing_id+' .cover-btn').html(original_photo);
                }
            }

            if(!data.e_already_linked){

                //Raw input to make it ready for next URL:
                //input.focus();

                //Add new object to list:
                adjust_counter(x__type, 1);

                //See if we previously have a list in place?
                if ($("#list-in-" + x__type + " .card-12274").length > 0) {
                    //Downwards add to start"
                    $("#list-in-" + x__type + " .card-12274:first").before(data.e_new_echo);
                } else {
                    //Raw list, add before input filed:
                    $("#list-in-" + x__type).prepend(data.e_new_echo);
                }

                //Allow inline editing if enabled:
                x_set_start_text();

                setTimeout(function () {
                    activate_popover();
                    e_sort_load(x__type);
                }, 987);

                //Hide Coin:
                $('.mini-cover.card-12274.card-id-'+e_existing_id).fadeOut();
            }

        } else {
            //We had an error:
            alert(data.message);
        }

    });
}



var i_is_adding = false;
function i__add(x__type, link_i__id) {

    alert('not up yet');
    return false;

    /*
     *
     * Either creates an IDEA transaction between focus_id & link_i__id
     * OR will create a new idea based on input text and then transaction it
     * to #focus_id (In this case link_i__id=0)
     *
     * */

    if(i_is_adding){
        return false;
    }

    //Remove results:
    i_is_adding = true;
    var sort_i_grabr = ".card_cover";
    var input_field = $('.new-list-'+x__type+' .add-input');
    var new_i__message = input_field.val();


    //We either need the idea name (to create a new idea) or the link_i__id>0 to create an IDEA transaction:
    if (!link_i__id && new_i__message.length < 1) {
        alert('Missing Idea');
        input_field.focus();
        return false;
    }

    //Set processing status:
    input_field.addClass('dynamic_saving');
    add_to_list(x__type, sort_i_grabr, '<div id="tempLoader" class="col-6 col-md-4 no-padding show_all_i"><div class="cover-wrapper"><div class="black-background-obs cover-link"><div class="cover-btn"><i class="fas fa-yin-yang fa-spin"></i></div></div></div></div>', 0);

    //Update backend:
    $.post("/ajax/i__add", {
        x__type: x__type,
        focus__node: parseInt($('#focus__node').val()),
        focus__id: parseInt($('#focus__id').val()),
        new_i__message: new_i__message,
        link_i__id: link_i__id
    }, function (data) {

        //Delete loader:
        $("#tempLoader").remove();
        input_field.removeClass('dynamic_saving').prop("disabled", false).focus();
        i_is_adding = false;

        if (data.status) {

            //Add new
            add_to_list(x__type, sort_i_grabr, data.new_i_html, 1);

            //Lookout for textinput updates
            x_set_start_text();
            load_covers();
            set_autosize($('.texttype__lg'));

            //Hide Coin:
            $('.mini-cover.card-12273.card-id-'+link_i__id).fadeOut();

        } else {
            //Show errors:
            alert(data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}


function e_delete(x__id, x__type) {

    var r = confirm("Unlink this source?");
    if (r==true) {
        $.post("/ajax/e_delete", {

            x__id: x__id,
            js_request_uri: js_request_uri, //Always append to AJAX Calls

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




function x_link_toggle(x__type, i__id){

    $('.btn_toggle_'+x__type).toggleClass('hidden');
    var x__id = parseInt($('.btn_control_'+x__type).attr('current_x_id'));

    if(!x__id){
        //Add:
        $.post("/ajax/x_link_toggle", {
            x__type:x__type,
            i__id:i__id,
            target_i__id:$('#target_i__id').val(),
            js_request_uri: js_request_uri, //Always append to AJAX Calls
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
        $.post("/ajax/x_remove", {
            x__id:x__id,
            js_request_uri: js_request_uri, //Always append to AJAX Calls
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
        new_i__message: $(this_grabr).val().trim(),
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    };

    //See if anything changes:
    if( $(this_grabr).attr('old-value')==modify_data['new_i__message'] ){
        //Nothing changed:
        return false;
    }

    //Grey background to indicate saving
    var target_element = '.text__'+modify_data['cache_e__id']+'_'+modify_data['s__id'];
    $.post("/ajax/x_set_text", modify_data, function (data) {

        if (!data.status) {

            //Reset to original value:
            $(target_element).val(data.original_val);

            //Show error:
            alert(data.message);

        } else {

            //If Updating Text, Updating Corresponding Fields:
            update_text_name(modify_data['cache_e__id'], modify_data['s__id'], modify_data['new_i__message']);

        }

    });
}




function adjust_counter(x__type, adjustment_count){
    $('.xtypecounter'+x__type).text((parseInt($('.headline_body_' + x__type).attr('read-counter')) + adjustment_count));
}


function search_enabled(){
    return universal_search_enabled && parseInt(js_e___6404[12678]['m__message']);
}


function show_more(i__id){
    $('.cache_frame_'+i__id+' .line, .cache_frame_'+i__id+' .inner_line').removeClass('hidden');
    $('.cache_frame_'+i__id+' .show_more_line').addClass('hidden');
}

function set_autosize(theobject){
    autosize(theobject);
    setTimeout(function () {
        autosize.update(theobject);
    }, 13);
}



function i_sort_load(x__type){

    load_covers();

    console.log('Tring to load Idea Sort for @'+x__type);
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

        //Make sure beow minimum sorting requirement:
        if($("#list-in-"+x__type+" .sort_draggable").length>=parseInt(js_e___6404[11064]['m__message'])){
            console.log(x__type+' has '+$("#list-in-"+x__type+" .sort_draggable").length+' items which is more than the page limit of '+js_e___6404[11064]['m__message']);
            return false;
        } else if($("#list-in-"+x__type+" .sort_draggable").length<2){
            console.log('Less than 2 items to sort '+x__type);
            return false;
        } else {

            console.log(x__type+' sorting load success');
            $('.sort_i_frame').removeClass('hidden');

            //Load sorter:
            var sort = Sortable.create(theobject, {
                animation: 144, // ms, animation speed moving items when sorting, `0` � without animation
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
                        $.post("/ajax/i_sort_load", {
                            new_x_order:new_x_order,
                            x__type:x__type,
                            js_request_uri: js_request_uri, //Always append to AJAX Calls
                        }, function (data) {
                            //Update UI to confirm with member:
                            if (!data.status) {
                                //There was some sort of an error returned!
                                alert(data.message);
                            }
                        });
                    }
                }
            });
        }
    }, 1500);

}




var current_focus = 0;
function remove_ui_class(item, index) {
    var the_class = 'custom_ui_'+current_focus+'_'+item;
    $('body').removeClass(the_class);
}

function e_select_apply(focus__id, selected_e__id, enable_mulitiselect, down_e__id, right_i__id){

    //Any warning needed?
    if(js_n___31780.includes(selected_e__id) && !confirm(js_e___31780[selected_e__id]['m__message'])){
        return false;
    }

    var field_required = js_n___28239.includes(focus__id);
    var was_previously_selected = ( $('.radio-'+focus__id+' .item-'+selected_e__id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && field_required && was_previously_selected){
        //Nothing to do here:
        return false;
    }

    //Updating Customizable Theme?
    if(js_n___13890.includes(focus__id)){
        current_focus = focus__id;
        $('body').removeClass('custom_ui_'+focus__id+'_');
        window['js_n___'+focus__id].forEach(remove_ui_class); //Removes all Classes
        $('body').addClass('custom_ui_'+focus__id+'_'+selected_e__id);
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+focus__id+' .item-'+selected_e__id+' .change-results';
    var initial_icon = $(notify_el).html();
    $(notify_el).html('<i class="fas fa-yin-yang fa-spin"></i>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+focus__id+' .list-group-item').removeClass('active');
        $('.radio-'+focus__id+' .checked_icon').remove();
    }

    //Enable currently selected:
    if((enable_mulitiselect || !field_required) && was_previously_selected){
        $('.radio-'+focus__id+' .item-'+selected_e__id).removeClass('active');
        $('.radio-'+focus__id+' .item-'+selected_e__id+' .checked_icon').remove();
    } else {
        $('.radio-'+focus__id+' .item-'+selected_e__id).addClass('active');
        $('.radio-'+focus__id+' .item-'+selected_e__id+' .inner_headline').after('<span class="icon-block-sm checked_icon"><i class="far fa-check"></i></span>');
    }

    $.post("/ajax/e_select_apply", {
        focus__id: focus__id,
        down_e__id: down_e__id,
        right_i__id: right_i__id,
        selected_e__id: selected_e__id,
        enable_mulitiselect: enable_mulitiselect,
        was_previously_selected: was_previously_selected,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {

        $(notify_el).html(initial_icon);
        activate_popover();

        if (!data.status) {
            alert(data.message);
        } else {
            console.log(data.message);
        }

    });


}


function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 0;
}


function update_form_select(element_id, new_e__id, initial_loading, show_title){
    console.log('update_form_select: '+element_id+'/'+new_e__id);

    //Toggles UI for FORM Selector
    $('.dropd_form_' + element_id + ' .dropdown-item').removeClass('active');
    $('.dropd_form_' + element_id + ' .optiond_'+new_e__id).addClass('active');
    $('.dropd_form_' + element_id).attr('selected_value', new_e__id);
    if(show_title){
        $('.dropd_form_' + element_id + ' .current_content').html($('.dropd_form_' + element_id + ' .content_'+new_e__id).html());
    } else {
        //Just replace icon:
        $('.dropd_form_' + element_id + ' .current_content span').html($('.dropd_form_' + element_id + ' .content_'+new_e__id+' span').html());
    }
    if(!initial_loading){
        if(element_id==4737){
            //Changing idea type would re-load dynamic fields based on type:
            has_unsaved_changes = true;
            console.log('Reloading: '+element_id+' with value: '+' NEW ID '+new_e__id+' / '+$('#modal31911 .created_i__id').val());
            load_i_dynamic($('#modal31911 .created_i__id').val(), 0, new_e__id, false);
        }
    }
}

function ui_instant_select(element_id, new_e__id, o__id, x__id, show_full_name){

    //Update x:
    console.log('UI instant .dropd_instant_'+element_id+'_'+o__id+'_'+x__id+' .btn' + new_e__id);
    var data_object = eval('js_e___'+element_id);
    $('.dropd_instant_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block-sm">'+data_object[new_e__id]['m__cover']+'</span>' + ( show_full_name ? data_object[new_e__id]['m__title'] : '' ));

    $('.dropd_instant_'+element_id+'_'+o__id+'_'+x__id+' .drop_item_instant_' + element_id +'_'+o__id+ '_' + x__id).removeClass('active');
    $('.dropd_instant_'+element_id+'_'+o__id+'_'+x__id+' .optiond_' + new_e__id+'_'+o__id+ '_' + x__id).addClass('active');

    var selected_e__id = $('.dropd_instant_'+element_id+'_'+o__id+'_'+x__id).attr('selected_value');
    $('.dropd_instant_'+element_id+'_'+o__id+'_'+x__id).attr('selected_value' , new_e__id);


    var main_object_type = 0;
    var main_object_update = false;

    if(element_id==6177){
        //Source access:
        $('.s__12274_'+o__id).attr('e__privacy', new_e__id);
        main_object_type = 12274;
        main_object_update = 'e__privacy';
    } else if(element_id==4737){
        //Idea Type:
        $('.s__12273_'+o__id).attr('i__type', new_e__id);
        main_object_type = 12273;
        main_object_update = 'i__type';
    } else if(element_id==31004){
        //Idea Privacy:
        main_object_type = 12273;
        main_object_update = 'i__privacy';
    }

    if(main_object_type>0 && main_object_update){
        $('.s__'+main_object_type+'_'+o__id).attr(main_object_update, new_e__id);
    }

}

function x_update_instant_select(element_id, new_e__id, o__id = 0, x__id = 0, show_full_name = false){

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


    if($('.dropmenu_instant_'+element_id).length && !o__id){
        o__id = $('.dropmenu_instant_'+element_id+':first').attr('o__id');
        x__id = $('.dropmenu_instant_'+element_id+':first').attr('x__id');
    }

    console.log('Attempt to update dropdown @'+element_id+' to @'+new_e__id);

    new_e__id = parseInt(new_e__id);

    //Deleting Anything?
    var main_object_type = 0;
    var main_object_update = false;
    var migrate_s__handle = null;
    if(element_id==31004 && !(new_e__id in js_e___31871)){

        //Deleting Idea:
        main_object_type = 12273;
        main_object_update = 'i__privacy';

        if(js_session_superpowers_unlocked.includes(10939)){
            var migrate_s__handle = prompt("Are you sure you want to permanently delete this idea?\nYou can reference #anotherIdea to migrate to or leave blank to delete permanently...", "#");
            if(migrate_s__handle === null){
                return false;
            }
        } else {
            //Confirm deletion:
            var r = confirm("Are you sure you want to permanently delete this idea?");
            if (!(r==true)) {
                return false;
            }
        }

    } else if(element_id==6177 && !(new_e__id in js_e___7358)){

        //Deleting Source:
        main_object_type = 12274;
        main_object_update = 'e__privacy';

        if(js_session_superpowers_unlocked.includes(10939)){
            var migrate_s__handle = prompt("Are you sure you want to permanently delete this source?\nYou can reference @anotherSource to migrate to or leave blank to delete permanently...", "@");
            if(migrate_s__handle === null){
                return false;
            }
        } else {
            //Confirm deletion:
            var r = confirm("Are you sure you want to permanently delete this source?");
            if (!(r==true)) {
                return false;
            }
        }

    } else if(element_id==4737 && !(new_e__id in js_e___7358)){

        main_object_type = 12273;
        main_object_update = 'i__type';

    }



    //Show Loading
    var data_object = eval('js_e___'+element_id);
    if(!data_object[new_e__id]){
        alert('Invalid element ID: '+element_id +'/'+ new_e__id +'/'+ o__id +'/'+ x__id +'/'+ show_full_name);
        return false;
    }
    $('.dropd_instant_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/ajax/x_update_instant_select", {
        focus__id:parseInt($('#focus__id').val()),
        o__id: o__id,
        element_id: element_id,
        new_e__id: new_e__id,
        migrate_s__handle: migrate_s__handle,
        x__id: x__id,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {

            //Update on page:
            ui_instant_select(element_id, new_e__id, o__id, x__id, show_full_name);

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

            if( data.auto_open_i_editor_modal ){
                //We need to show idea modal:
                i_editor_load(o__id, $('.s__12273_'+o__id).attr('x__id'));
            }

        } else {

            //Show error:
            alert(data.message);

        }
    });
}








function e_sort_save(x__type) {

    var new_x__weight = [];
    var sort_rank = 0;

    $("#list-in-"+x__type+" .card-12274").each(function () {
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
        $.post("/ajax/e_sort_save", {
            e__id: parseInt($('#focus__id').val()),
            x__type:x__type,
            new_x__weight: new_x__weight,
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {
            //Update UI to confirm with member:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}



function x_reset_sorting(){
    var r = confirm("Reset sorting?");
    if (r==true) {

        var focus__node = parseInt($('#focus__node').val());
        var focus__id = parseInt($('#focus__id').val());
        var focus_handle = $('#focus_handle').val();

        //Update via call:
        $.post("/ajax/x_reset_sorting", {
            focus__node: focus__node,
            focus__id: focus__id,
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {

            if (!data.status) {

                //Ooops there was an error!
                alert(data.message);

            } else {

                //Refresh page:
                if(focus__node==12273){
                    //Ideation
                    js_redirect(js_e___42903[33286]['m__message'] + focus_handle);
                } else if(focus__node==12274){
                    //Sourcing
                    js_redirect(js_e___42903[42902]['m__message'] + focus_handle);
                }

            }
        });
    }
}








function go_next(do_skip){

    var selection_i__id = [];

    if (js_n___7712.includes(focus_i__type)){
        //Choose
        $(".this_selector").each(function () {
            var selection_i__id_this = parseInt($(this).attr('selection_i__id'));
            if ($('.this_selector_'+selection_i__id_this+' i').hasClass('fa-square-check') || $(".this_selector").length==1 ) {
                selection_i__id.push(selection_i__id_this);
            }
        });
    }

    //Compile all next ideas, if any:
    var next_i_data = []; //Aggregate the data for all children
    $("#list-in-12840 .edge-cover").each(function () {

        //Fetch Media
        var gather_media_result = gather_media('.media_frame_'+$(this).attr('i__id')+' .media_item', 43004);
        if(!gather_media_result['upload_completed']){
            alert('MEDIA ERROR: '+gather_media_result['error_message']);
            return false;
        }

        next_i_data.push({
            i__id: parseInt($(this).attr('i__id')),
            i__text: ( $('.s__12273_'+$(this).attr('i__id')+' .x_write').val() ? $('.s__12273_'+$(this).attr('i__id')+' .x_write').val() : null ),
            i__quantity: ( $('.input_ui_'+$(this).attr('i__id')+' .i__quantity').val() ? $('.input_ui_'+$(this).attr('i__id')+' .i__quantity').val() : 0 ),
            uploaded_media: gather_media_result['uploaded_media'],
        });

    });


    var gather_media_result = gather_media('.media_frame_'+$('#focus__id').val()+' .media_item', 43004);
    if(!gather_media_result['upload_completed']){
        alert('MEDIA ERROR: '+gather_media_result['error_message']);
        return false;
    }

    //Payment Error?
    if (js_n___41055.includes(focus_i__type) && !$(".tickets_issued")[0]){
        //Ticket not yet issued!
        alert('Pay Now via Paypal before going next.');
        return false;
    }


    //Load:
    var original_html = $('.go_next_btn').html();
    $('.go_next_btn').html('<span class="icon-block" style="margin:5px 0 -5px;"><i class="fas fa-yin-yang fa-spin"></i></span>');

    //Submit to go next:
    $.post("/ajax/go_next", {
        target_i__hashtag: $('#target_i__hashtag').val(),
        target_i__id: parseInt($('#target_i__id').val()),
        focus_i_data: {
            i__id: parseInt($('#focus__id').val()),
            i__text: ( $('.focus-cover .x_write').val() ? $('.focus-cover .x_write').val() : null ),
            i__quantity: ( $('.input_ui_'+parseInt($('#focus__id').val())+' .i__quantity').val() ? $('.input_ui_'+parseInt($('#focus__id').val())+' .i__quantity').val() : 0 ),
            uploaded_media: gather_media_result['uploaded_media'],
        },
        do_skip: do_skip,
        selection_i__id: selection_i__id,
        next_i_data: next_i_data,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {
            //Go to redirect message:
            js_redirect(data.next__url);
        } else {
            //Show error:
            $('.go_next_btn').html(original_html);
            alert(data.message);
        }
    });

}


