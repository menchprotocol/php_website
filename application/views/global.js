

//Define some global variables:
var has_unsaved_changes = false; //Tracks source/idea modal edits
var focus_x__group = 0;


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


function x_mass_apply_preview(apply_id, s__id){

    //Select first:
    var first_id = $('#modal'+apply_id+' .mass_action_toggle option:first').val();
    $('.mass_action_item').addClass('hidden');
    $('.mass_id_' + first_id ).removeClass('hidden');
    $('#modal'+apply_id+' .mass_action_toggle').val(first_id);
    $('#modal'+apply_id+' input[name="s__id"]').val(s__id);
    $('#modal'+apply_id).modal('show');

    //Load Ppeview:
    $('#modal'+apply_id+' .x_mass_apply_preview').html('<span class="icon-block-xx"><i class="far fa-yin-yang fa-spin"></i></span>Loading');
    $.post("/ajax/x_mass_apply_preview", {
        apply_id: apply_id,
        s__id: s__id
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

    $('.i_text_finder').on('autocomplete:selected', function (event, suggestion, dataset) {

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


function view_s__title(suggestion){
    var title = ( suggestion._highlightResult && suggestion._highlightResult.s__title.value ? suggestion._highlightResult.s__title.value : suggestion.s__title );
    var max_limit = 89;
    return htmlentitiesjs( title.length>=max_limit ? title.substring(0,max_limit)+'...' : title );
}


function view_s_js_line(suggestion){
    if(suggestion.s__type==12273){
        return '<span class="grey">#' + suggestion.s__handle + '</span>&nbsp;<span class="main__title">' + view_s__title(suggestion) + '</span>';
    } else if(suggestion.s__type==12274){
        return '<span class="icon-block-xx">'+ view_cover_js(suggestion.s__cover) +'</span><span class="grey">@' + suggestion.s__handle + '</span>&nbsp;<span class="main__title">' + view_s__title(suggestion) + '</span>';
    }
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
        return '<div title="ID '+suggestion.s__id+'" class="card_cover mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="'+suggestion.s__url+'" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="'+suggestion.s__url+'" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==26013){
        //Link Source
        return '<div title="ID '+suggestion.s__id+'" class="card_cover mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    }

}
function view_s_mini_js(s__cover,s__title){
    return '<span class="block-cover" title="'+s__title+'">'+ view_cover_js(s__cover) +'</span>';
}


function fetch_int_val(object_name){
    return ( $(object_name).length ? parseInt($(object_name).val()) : 0 );
}

function toggle_headline(x__type){

    var x__follower = 0;
    var x__next = 0;
    var focus_card = fetch_int_val('#focus_card');
    if(focus_card==12273){
        x__next = fetch_int_val('#focus_id');
    } else if (focus_card==12274){
        x__follower = fetch_int_val('#focus_id');
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

    //Log Transaction:
    x_create({
        x__creator: js_pl_id,
        x__type: action_id,
        x__following: x__type,
        x__follower: x__follower,
        x__next: x__next,
    });
}


function sort_e_load(x__type) {

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

function load_hashtag_menu(){
    if(document.location.hash){
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

function toggle_pills(x__type_hash){

    if($('.handle_nav_'+x__type_hash).attr('x__type') && $('.handle_nav_'+x__type_hash).attr('x__type').length){
        x__type = parseInt($('.handle_nav_'+x__type_hash).attr('x__type'));
    } else {
        console.log('ERROR: #'+x__type_hash+' is not a valid menu.');
        return false;
    }

    var x__follower = 0;
    var x__next = 0;
    var focus_card = fetch_int_val('#focus_card');

    if(focus_card==12273){
        x__next = fetch_int_val('#focus_id');
    } else if (focus_card==12274){
        x__follower = fetch_int_val('#focus_id');
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
        if( !$('.headline_body_' + x__type + ' .tab_content').html().length ){

            $('.headline_body_' + x__type + ' .tab_content').html('<div class="center" style="padding-top: 13px;"><i class="far fa-yin-yang fa-spin"></i></div>');

            var focus_card = fetch_int_val('#focus_card');
            console.log('Tab loading from @'+focus_card+' for @'+x__type);

            if(focus_card==12273){

                var loading_url = "/ajax/i_view_body";
                var loading_data = {
                    focus_card:focus_card,
                    x__type:x__type,
                    counter:$('.headline_body_' + x__type).attr('read-counter'),
                    i__id:fetch_int_val('#focus_id')
                };

            } else if(focus_card==12274){

                var loading_url = "/ajax/e_view_body";
                var loading_data = {
                    focus_card:focus_card,
                    x__type:x__type,
                    counter:$('.headline_body_'+x__type).attr('read-counter'),
                    e__id:fetch_int_val('#focus_id')
                };

            } else {

                //Whaaaat is this?
                console.log('ERROR: Unknown Tab!');
                return false;

            }

            //Load data:
            $.post(loading_url, loading_data, function (data) {

                //Add data to the page:
                $('.headline_body_' + x__type + ' .tab_content').html(data);


                $('[data-toggle="tooltip"]').tooltip();
                load_card_clickers();
                initiate_algolia();
                load_editor();
                x_set_start_text();
                set_autosize($('.x_set_class_text'));

                setTimeout(function () {
                    load_covers();
                    $('[data-toggle="tooltip"]').tooltip();
                }, 233);


                $(function () {
                    var $win = $(window);
                    $win.scroll(function () {
                        //Download loading from bottom:
                        if (parseInt($(document).height() - ($win.height() + $win.scrollTop())) < 377) {
                            x_view_load_page();
                        }
                    });
                });

                console.log('Detecting Sort loading needs for @'+x__type+'...');
                if(js_n___11020.includes(x__type) || (focus_card==12274 && (js_n___42261.includes(x__type) || js_n___42284.includes(x__type)))){
                    setTimeout(function () {
                        i_sort_load(x__type);
                    }, 233);
                } else if(js_n___11028.includes(x__type) || (focus_card==12273 && (js_n___42261.includes(x__type) || js_n___42284.includes(x__type)))) {
                    setTimeout(function () {
                        sort_e_load(x__type);
                    }, 233);
                }

                load_covers();

            });

        }

        //Log Transaction:
        x_create({
            x__creator: js_pl_id,
            x__type: action_id,
            x__following: x__type,
            x__follower: x__follower,
            x__next: x__next,
        });

    }


}



function i_copy(i__id, do_recursive){

    //Go ahead and delete:
    $.post("/ajax/i_copy", {
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
    }, function (data) {
        if(data.status){
            js_redirect('/@'+data.new_e__handle);
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
    return $.post("/ajax/x_create", add_fields);
}



function toggle__cover(new_cover){
    if(new_cover.slice(0, 2)=='fa'){
        //This is font awesome, show:
        $('#modal31912 .save_e__cover').removeClass('hidden');
    } else {
        $('#modal31912 .save_e__cover').addClass('hidden');
    }
}

function update__cover(new_cover, changed = true){
    $('#modal31912 .save_e__cover').val( new_cover );
    update_cover_main(new_cover, '.demo_cover');
    toggle__cover(new_cover);
    if(changed){
        has_unsaved_changes = true;
    }
}
function image_cover(cover_preview, cover_apply, new_title){
    return '<a href="javascript:void(0);" onclick="update__cover(\''+cover_apply+'\')">' + view_s_mini_js(cover_preview, new_title) + '</a>';
}



function initiate_algolia(){
    $(".algolia_finder").focus(function () {
        if(!algolia_index && search_enabled()){
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

    $('.coins_e_'+e__id+'_'+x__type).html('<span class="icon-block-xx"><i class="far fa-yin-yang fa-spin"></i></span>');

    $.post("/ajax/e_load_cover", {
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

    $('.coins_i_'+i__id+'_'+x__type).html('<span class="icon-block-xx"><i class="far fa-yin-yang fa-spin"></i></span>');

    $.post("/ajax/i_load_cover", {
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
        $('#top_finder').focus();

        setTimeout(function () {
            //One more time to make sure it also works in mobile:
            $('#top_finder').focus();
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

    $(".card_click_e, .card_click_i, .card_click_x").unbind();
    var ignore_clicks = 'a, .btn, textarea, .x__message, .cover_wrapper12273, .ignore-click, .focus-cover';

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

    $('.card_click_x').click(function(e) {
        if($(e.target).closest(ignore_clicks).length < 1){
            js_redirect('/'+$(this).attr('i__hashtag'));
        }
    });

}


function random_animal(basic_style = false){
    var styles = ['fas','fad','fal','fat','fas','fad','fal','fat','fas','fad','fal','fat','fas fa-sharp','fal fa-sharp','fat fa-sharp'];
    var colors = [' ',' zq12273',' zq12274',' zq6255'];
    var animals = ['fa-hippo','fa-otter','fa-sheep','fa-rabbit','fa-pig','fa-dog','fa-elephant','fa-deer','fa-cow','fa-alicorn','fa-rabbit','fa-monkey','fa-cat','fa-cat-space','fa-fish','fa-dragon','fa-whale','fa-turtle','fa-snake','fa-spider','fa-lobster','fa-duck','fa-dove','fa-crow','fa-dinosaur','fa-bee','fa-horse','fa-raccoon','fa-pegasus','fa-bat','fa-deer','fa-badger-honey','fa-squirrel','fa-ram','fa-dolphin','fa-bird','fa-crab','fa-worm','fa-kiwi-bird','fa-shrimp','fa-duck','fa-teddy-bear','fa-t-rex'];
    return animals[Math.floor(Math.random()*animals.length)] + ' ' + ( basic_style ? ' fas ' : styles[Math.floor(Math.random()*styles.length)] + ' ' + colors[Math.floor(Math.random()*colors.length)] );
}

var algolia_index = false;
$(document).ready(function () {

    load_hashtag_menu();

    $('#modal31912 .save_e__cover').change(function () {
        toggle__cover($(this).val());
    });

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

    $('#top_finder').keyup(function() {
        if(!$(this).val().length){
            $("#container_finder .row").html(''); //Reset results view
        }
    });

    //For the S shortcut to load search:
    $("#top_finder").focus(function() {
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
        x_create({
            x__creator: js_pl_id,
            x__type: 14576, //MODAL VIEWED
            x__following: x__type,
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
                toggle_finder();
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


    if(!search_enabled()){
        console.log("Search engine is disabled!");
        return false;
    }

    var icons_listed = [];

    //TOP SEARCH
    $("#top_finder").autocomplete({minLength: 1, autoselect: false, keyboardShortcuts: ['s']}, [
        {
            source: function (q, cb) {

                icons_listed = [];

                //Members can filter search with first word:
                var search_only_e = $("#top_finder").val().charAt(0)=='@';
                var search_only_in = $("#top_finder").val().charAt(0)=='#';
                var search_only_app = $("#top_finder").val().charAt(0)=='-';
                $("#container_finder .row").html(''); //Reset results view


                //Do not search if specific command ONLY:
                if (( search_only_in || search_only_e || search_only_app ) && !isNaN($("#top_finder").val().substr(1)) ) {

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
                        $("#container_finder .row").append(view_s_js_cover(26011, suggestion, 0));
                    }
                    return false;
                },
                empty: function (data) {
                    $("#container_finder .row").html('<div class="main__title margin-top-down-half"><span class="icon-block"><i class="fal fa-exclamation-circle"></i></span>No results found</div>');
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
        return '<i class="fas fa-circle zq12274"></i>';
    }
}

function update_cover_mini(cover_code, target_css){
    //Update:
    $(target_css).html(view_cover_js(cover_code));
}



function load_finder(focus_card, x__type){
    if(js_n___11028.includes(x__type) || (focus_card==12273 && (js_n___42261.includes(x__type) || js_n___42284.includes(x__type)))) {
        e_load_finder(x__type);
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

    if(!search_enabled()){
        console.log("Search engine is disabled!");
        return false;
    }

    $('.new-list-'+x__type + ' .add-input').keyup(function () {

        //Clear if no input:
        if(!$(this).val().length){
            $('.new-list-'+x__type+' .algolia_pad_finder').html('');
        }
        icons_listed = [];

    }).autocomplete({hint: false, autoselect: false, minLength: 1}, [{

        source: function (q, cb) {

            $('.new-list-'+x__type+' .algolia_pad_finder').html('');
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
                    $('.new-list-'+x__type+' .algolia_pad_finder').append(view_s_js_cover(26013, suggestion, x__type));
                }
            },
            header: function (data) {
                if(data.query && data.query.length){
                    $('.new-list-'+x__type+' .algolia_pad_finder').prepend('<div class="card_cover mini-cover coin-12274 coin-id-0 col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="e__add('+x__type+', 0)" class="black-background-obs cover-link coinType12274"><div class="cover-btn"></div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e__add('+x__type+', 0)" class="main__title">'+data.query+'</a></div></div></div>');
                }
            },
            empty: function (data) {
                return '';
            }
        }
    }]);

}

function i_editor_switch(link_x__type = 0, next_i__id = 0, previous_i__id = 0, do_checks = 0){
    console.log('SWITCHING TO '+link_x__type+'/'+next_i__id+'/'+previous_i__id+'/'+do_checks+'/'+$('#modal31911 .save_i__message').val()+'/'+parseInt($('#modal31911 .created_i__id').val()));

    if(!next_i__id && !previous_i__id && !link_x__type && !do_checks){
        var r = confirm("Are you sure you want to unlink this idea?");
        if (!(r==true)) {
            return false;
        }
    }
    //Will switch the nature/direction of the link:
    return i_editor_load(0, 0, link_x__type, next_i__id, previous_i__id, do_checks, $('#modal31911 .save_i__message').val(), parseInt($('#modal31911 .created_i__id').val()));
}

function i_editor_load(i__id = 0, x__id = 0, link_x__type = 0, next_i__id = 0, previous_i__id = 0, do_checks = 1, load_message = '', passon_i__id = 0){


    $(".idea_direction, .idea_unlink, .input___4486").addClass('hidden');
    var focus_i_id = ( fetch_int_val('#focus_card')==12273 ? fetch_int_val('#focus_id') : 0 );

    if(!passon_i__id){

        //Reset Fields:
        has_unsaved_changes = false;
        $('#modal31911 .media_frame').html('');
        $("#modal31911 .dynamic_item").attr('d__id','').attr('d_x__id','');
        $("#modal31911 .dynamic_item input").attr('placeholder', '').val('');
        $('#modal31911 .created_i__id').val(0);
        $("#modal31911 .unsaved_warning").val('');
        $("#modal31911 .dynamic_item, #modal31911 .save_x__frame").addClass('hidden');
        $("#modal31911 .dynamic_editing_loading").removeClass('hidden');
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

    if(next_i__id && js_n___4486.includes(link_x__type)){
        i__id = 0;
        x__id = 0;
        $("#modal31911 .idea_list_next").html($('.creator_frame_'+next_i__id).html() + '<div class="idea_response">' + $('.ui_i__cache_'+next_i__id).html() + '</div>');
        $('.idea_direction').removeClass('hidden').attr('onclick','i_editor_switch('+link_x__type+',0,'+next_i__id+',1)');
        $('.idea_unlink, .input___4486').removeClass('hidden');
        if(!passon_i__id){
            update_form_select(4486, link_x__type, 1, true);
        }
    } else if(previous_i__id && js_n___4486.includes(link_x__type)){
        i__id = 0;
        x__id = 0;
        $("#modal31911 .idea_list_previous").html($('.creator_frame_'+previous_i__id).html() + '<div class="idea_response">' + $('.ui_i__cache_'+previous_i__id).html() + '</div>');
        $('.idea_direction').removeClass('hidden').attr('onclick','i_editor_switch('+link_x__type+','+previous_i__id+',0,1)');
        $('.idea_unlink, .input___4486').removeClass('hidden');
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

        //Load Current Media:
        $(".ui_i__cache_"+i__id+" .media_display").each(function () {
            $('.media_frame').append('<div id="'+$(this).attr('id')+'" class="media_item" media_e__id="" playback_code="" e__id="0"  e__cover=""></div>');
            cloudinary_preview_source(13572, $(this).attr('id'), $(this).attr('media_e__id'), $(this).attr('playback_code'), $(this).attr('e__cover'), $(this).attr('e__title'), $(this).attr('e__id'));
        });
        sort_media();

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
            var focus_card = fetch_int_val('#focus_card');
            if(focus_card==12273){
                //insert_message = '#'+$('#focus_handle').val()+' ';
            } else if (focus_card==12274 && fetch_int_val('#focus_id')!=js_pl_id){
                insert_message = '@'+$('#focus_handle').val()+' ';
            }
        }

        if(insert_message.length){
            $("#modal31911 .save_i__message").val(insert_message);
        }

    }

    if(x__id){
        $('#modal31911 .save_x__id').val(x__id);

        //Idea<>Idea links do not have an interaction message
        if(fetch_int_val('#focus_card')!=12273 || ($('.ui_x__message_'+x__id+':first') && $('.ui_x__message_'+x__id+':first').text().length>0)){
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
    }, 987);

}

function load_i_dynamic(i__id, x__id, current_i__type, initial_loading){

    var created_i__id = 0;

    $.post("/ajax/i_editor_load", {
        i__id: i__id,
        x__id: x__id,
        current_i__type: current_i__type,
    }, function (data) {

        $("#modal31911 .dynamic_editing_loading").addClass('hidden');

        if (data.status) {

            if(!i__id && data.created_i__id>0){
                console.log('NEW IDEA #'+data.created_i__id+' has been created');
                created_i__id = data.created_i__id;
                $('#modal31911 .created_i__id').val(created_i__id);
                i__id = created_i__id;
            }

            if(initial_loading){

                //Initiate Idea  Uploader:
                load_cloudinary(13572, ['#'+i__id], '.uploader_13572', '#modal31911');

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

                    if(x__id && fetch_int_val('#focus_card')==12274 && data.return_inputs[index_i]["d__id"]==fetch_int_val('#focus_id')){
                        //Hide message textarea since this is already loaded in the dynamic inputs:
                        $("#modal31911 .save_x__message").val('IGNORE_INPUT');
                        $("#modal31911 .save_x__frame").addClass('hidden');
                    }
                }

            }

            setTimeout(function () {

                $('[data-toggle="tooltip"]').tooltip();

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
    $(".i_editor_save").html('<span class="icon-block-xx"><i class="far fa-yin-yang fa-spin"></i></span>');
    $("#modal31911 .save_results").html('');

    var current_i__id = parseInt($('#modal31911 .save_i__id').val());
    var created_i__id = parseInt($('#modal31911 .created_i__id').val());
    var uploader_id = 13572; //THis is for idea modal

    var modify_data = {
        focus_card:         fetch_int_val('#focus_card'),
        focus_id:           fetch_int_val('#focus_id'),
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
        save_media:         [],
    };

    //Append Media:
    //modify_data['media_uploads']
    var sorted_media = [];
    var sort_rank = 0;
    var media_uploaded = true;
    $("#media_frame .media_item").each(function () {
        var current_e_id = parseInt($(this).attr('e__id'));

        if(current_e_id > 0){

            //Already there...
            modify_data['save_media'][sort_rank] = {
                media_e__id:  parseInt($(this).attr('media_e__id')),
                playback_code: $(this).attr('playback_code'),
                e__id:        current_e_id,
                e__cover:     $(this).attr('e__cover'),
                e__title:     $('#'+$(this).attr('id')+' input').val(),
            }
            sort_rank++;

        } else if(media_cache[uploader_id][$(this).attr('id')]){

            //Fetch variables for this media:
            modify_data['save_media'][sort_rank] = {
                media_e__id:  parseInt($(this).attr('media_e__id')),
                playback_code: $(this).attr('playback_code'),
                e__id:        current_e_id,
                e__cover:     $(this).attr('e__cover'),
                e__title:     $('#'+$(this).attr('id')+' input').val(),
                media_cache:  media_cache[uploader_id][$(this).attr('id')],
            }
            sort_rank++;

        } else {
            //This media is missing, upload is not complete:
            media_uploaded = false;
        }
    });
    if(!media_uploaded){
        i_saving = false;
        $(".i_editor_save").html('SAVE');
        $("#modal31911 .save_results").html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Error: Media has not yet uploaded, please wait until upload is complete...');
        return false;
    }


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
            $("#modal31911 .save_results").html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Error: '+data.message);

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
            var on_focus_idea = fetch_int_val('#focus_card')==12273 && modify_data['save_i__id']==fetch_int_val('#focus_id');

            //Update Idea Type:
            $('.s__12273_'+modify_data['save_i__id']).attr('i__type', modify_data['save_i__type']);
            ui_instant_select(4737, modify_data['save_i__type'], modify_data['save_i__id'], modify_data['save_x__id'], true);

            //Update Idea Privacy:
            $('.s__12273_'+modify_data['save_i__id']).attr('i__privacy', modify_data['save_i__privacy']);
            ui_instant_select(31004, modify_data['save_i__privacy'], modify_data['save_i__id'], modify_data['save_x__id'], true);

            //Update Handle & Href links if needed:
            if(old_handle!=new_handle){
                if(on_focus_idea){
                    //Refresh page since focus item handle changed:
                    js_redirect('/~'+new_handle);
                } else {
                    //Update Hashtag & Link:
                    $('.s__12273_'+modify_data['save_i__id']).attr('i__hashtag', new_handle);
                    $(".ui_i__hashtag_"+modify_data['save_i__id']).text(new_handle).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233); //Flash
                    $(".handle_href_i_"+modify_data['save_i__id']).attr('href', $(".handle_href_i_"+modify_data['save_i__id']+':first').attr('href').replaceAll(old_handle, new_handle));
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

                console.log('START ADD '+modify_data['save_x__type']+' & x GROUP: '+focus_x__group);

                adjust_counter(focus_x__group, 1);

                $("#list-in-" + focus_x__group).append(data.return_i__cache_full);

                i_sort_load(focus_x__group);

            } else {
                //Update Cache otherwise:
                $('.ui_i__cache_'+modify_data['save_i__id']).html(data.return_i__cache_links);
            }

            //Show more if on focus idea:
            if(on_focus_idea){
                show_more(modify_data['save_i__id']);
            }

            if(modify_data['save_x__id'] && modify_data['save_x__message']!='IGNORE_INPUT'){
                $('.ui_x__message_'+modify_data['save_x__id']).text(modify_data['save_x__message']);
            }

            //Tooltips:
            setTimeout(function () {
                $('[data-toggle="tooltip"]').tooltip();
            }, 987);

        }
    });
}

function sort_media(){
    var sort = Sortable.create(document.getElementById("media_frame"), {
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
function load_cloudinary(uploader_id, uploader_tags = [], loading_button = null, loading_modal = null, loading_inline_container = null){

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
            sort_media();

        } else if (result.event === "upload-added") {

            //Add Pending Loader
            console.log(result.event);
            console.log(result);

            //Append loaders:
            if(uploader_id==42359){

                //Source Cover Uploader:
                update__cover('far fa-yin-yang fa-spin');

            } else if(uploader_id==13572){

                //Discovery Uploader
                has_unsaved_changes = true;
                $('.media_frame').append('<div id="'+result.info.id+'" class="media_item" media_e__id="" playback_code="" e__id="0"  e__cover=""><span><i class="far fa-yin-yang fa-spin"></i></span></div>');

            } else if(uploader_id==12117){

                //Discovery Uploader

            }


        } else if (result.event === "success") {

            console.log(result.event);
            console.log(result);

            //Add uploaded media:
            if(uploader_id==42359){

                //Source Cover Uploader:
                update__cover('https://res.cloudinary.com/menchcloud/image/upload/c_crop,g_custom/' + result.info.path);

            } else if(uploader_id==13572){

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
                    console.log('ERORRRRRRRR: Missing Media Type');

                }

            } else if(uploader_id==12117){
                //Discovery Uploader

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
        $('#'+info_id).html('<input type="text" value="'+e__title+'" placeholder="Source Title" class="superpower__13422" /><span title="'+js_e___42294[media_e__id]['m__title']+'">'+js_e___42294[media_e__id]['m__cover']+'</span><img src="'+e__cover+'" /><a href="javascript:void(0)" onclick="delete_media(\''+uploader_id+'\',\''+info_id+'\')"><i class="fas fa-xmark"></i></a>');
        //<video id="video_player_'+playback_code+'" controls class="cld-video-player vjs-fade-out cld-fluid cld-video-player-skin-light" poster="'+e__cover+'"></video>
        //play_video(playback_code);

    } else if(media_e__id == 4260){

        //Image
        $('#'+info_id).html('<input type="text" value="'+e__title+'" placeholder="Source Title" class="superpower__13422" /><img src="'+e__cover+'" /><a href="javascript:void(0)" onclick="delete_media(\''+uploader_id+'\',\''+info_id+'\')"><i class="fas fa-xmark"></i></a>');

    } else if(media_e__id == 4259){

        //Audio
        $('#'+info_id).html('<input type="text" value="'+e__title+'" placeholder="Source Title" class="superpower__13422" /><span title="'+js_e___42294[media_e__id]['m__title']+'">'+js_e___42294[media_e__id]['m__cover']+'</span><audio controls src="'+playback_code+'"></audio><a href="javascript:void(0)" onclick="delete_media(\''+uploader_id+'\',\''+info_id+'\')"><i class="fas fa-xmark"></i></a>');

    } else {

        //Unsupported file, should not happen since we limited file extensions to those we know:
        alert('Upload Error: Uploaded File '+e__title+' is not a valid Video, Image or Audio file.');
        delete_media(uploader_id, info_id, true, true);

    }



}


function e_editor_load(e__id = 0, x__id = 0){

    //Activate Modal:
    $('#modal31912').modal('show');

    //Reset Fields:
    has_unsaved_changes = false;

    $("#modal31912 .unsaved_warning").val('');

    $('#modal31912 .save_results').html('');
    $("#modal31912 .dynamic_item, #modal31912 .save_x__frame").addClass('hidden');
    $("#modal31912 .dynamic_editing_loading").removeClass('hidden');
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
    $('#modal31912 .fa_search a').attr('href','https://fontawesome.com/search?q='+encodeURIComponent(current_title)+'&o=r&s=solid');

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
        x__id: x__id
    }, function (data) {

        $("#modal31912 .dynamic_editing_loading").addClass('hidden');

        if (data.status) {

            //Initiate Source Cover Uploader:
            load_cloudinary(42359, ['@'+e__id], '.uploader_42359', '#modal31912');

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

                    if(x__id && ( (fetch_int_val('#focus_card')==12274 && data.return_inputs[index_i]["d__id"]==fetch_int_val('#focus_id')) || data.return_inputs[index_i]["d__id"]==e__id )){
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
                $('[data-toggle="tooltip"]').tooltip();
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
    $(".e_editor_save").html('<span class="icon-block-xx"><i class="far fa-yin-yang fa-spin"></i></span>');
    $("#modal31912 .save_results").html('');

    var modify_data = {
        save_e__id:         $('#modal31912 .save_e__id').val(),
        save_e__title:      $('#modal31912 .save_e__title').val().trim(),
        save_e__cover:      $('#modal31912 .save_e__cover').val().trim(),
        save_e__handle:     $('#modal31912 .save_e__handle').val().trim(),
        save_x__id:         $('#modal31912 .save_x__id').val(),
        save_x__message:    $('#modal31912 .save_x__message').val().trim(),
        save_e__privacy:    $('.dropd_form_6177').attr('selected_value').trim(),
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
            $("#modal31912 .save_results").html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Error: '+data.message);

        } else {

            //Update Handle & Href links if needed:
            var old_handle = $(".ui_e__handle_"+modify_data['save_e__id']+':first').text();
            var new_handle = modify_data['save_e__handle'];
            if(old_handle!=new_handle){
                if(fetch_int_val('#focus_card')==12274 && modify_data['save_e__id']==fetch_int_val('#focus_id')){
                    //Refresh page since focus item handle changed:
                    return js_redirect('/@'+new_handle);
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
            $('[data-toggle="tooltip"]').tooltip();
            setTimeout(function () {
                $('[data-toggle="tooltip"]').tooltip();
            }, 987);

            has_unsaved_changes = false;
            $('#modal31912').modal('hide');

            //Do we need to refresh the page?
            if(fetch_int_val('#focus_card')==12274 && fetch_int_val('#focus_id')==modify_data['save_e__id']){
                //Refresh page since source edited their own profile:
                js_redirect('/@'+$('#focus_handle').val());
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
    var has_more_to_load = ( current_total_count > parseInt(fetch_int_val('#page_limit')) * current_page[focus_x__group] );

    if(!has_more_to_load){
        return false;
    } else if(busy_loading){
        return false;
    }
    busy_loading = true;


    current_page[focus_x__group]++; //Now we can increment current page
    $('<div class="load-more"><span class="icon-block-xx"><i class="far fa-yin-yang fa-spin"></i></span>Loading More</div>').insertAfter('#list-in-'+focus_x__group);
    $.post("/ajax/x_view_load_page", {
        focus_card: fetch_int_val('#focus_card'),
        focus_id: fetch_int_val('#focus_id'),
        x__type: focus_x__group,
        current_page: current_page[focus_x__group],
    }, function (data) {
        $('.load-more').remove();
        if(data.length){
            $('#list-in-'+focus_x__type).append(data);
            x_set_start_text();
            load_card_clickers();
            $('[data-toggle="tooltip"]').tooltip();
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
    $.post("/ajax/e__add", {

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
                adjust_counter(x__type, 1);

                //See if we previously have a list in place?
                if ($("#list-in-" + x__type + " .coinface-12274").length > 0) {
                    //Downwards add to start"
                    $("#list-in-" + x__type + " .coinface-12274:first").before(data.e_new_echo);
                } else {
                    //Raw list, add before input filed:
                    $("#list-in-" + x__type).prepend(data.e_new_echo);
                }

                //Tooltips:
                $('[data-toggle="tooltip"]').tooltip();

                //Allow inline editing if enabled:
                x_set_start_text();

                setTimeout(function () {
                    sort_e_load(x__type);
                    load_covers();
                }, 987);

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
        $.post("/ajax/e_delete", {

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




function x_link_toggle(x__type, i__id){

    $('.btn_toggle_'+x__type).toggleClass('hidden');
    var x__id = parseInt($('.btn_control_'+x__type).attr('current_x_id'));

    if(!x__id){
        //Add:
        $.post("/ajax/x_link_toggle", {
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
        $.post("/ajax/x_remove", {
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
        new_i__message: $(this_grabr).val().trim()
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
        if($("#list-in-"+x__type+" .sort_draggable").length>=parseInt(fetch_int_val('#page_limit'))){
            console.log(x__type+' has '+$("#list-in-"+x__type+" .sort_draggable").length+' items which is more than the page limit of '+fetch_int_val('#page_limit'));
            return false;
        }

        $('.sort_i_frame').removeClass('hidden');
        console.log(x__type+' sorting load success');

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
                    $.post("/ajax/i_sort_load", { new_x_order:new_x_order, x__type:x__type }, function (data) {
                        //Update UI to confirm with member:
                        if (!data.status) {
                            //There was some sort of an error returned!
                            alert(data.message);
                        }
                    });
                }
            }
        });
    }, 987);

}









var current_focus = 0;
function remove_ui_class(item, index) {
    var the_class = 'custom_ui_'+current_focus+'_'+item;
    $('body').removeClass(the_class);
}

function e_select_apply(focus_id, selected_e__id, enable_mulitiselect, down_e__id, right_i__id){

    //Any warning needed?
    if(js_n___31780.includes(selected_e__id) && !confirm(js_e___31780[selected_e__id]['m__message'])){
        return false;
    }

    var field_required = js_n___42174.includes(focus_id);
    var was_previously_selected = ( $('.radio-'+focus_id+' .item-'+selected_e__id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && field_required && was_previously_selected){
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
    if((enable_mulitiselect || !field_required) && was_previously_selected){
        $('.radio-'+focus_id+' .item-'+selected_e__id).removeClass('active');
    } else {
        $('.radio-'+focus_id+' .item-'+selected_e__id).addClass('active');
    }

    $.post("/ajax/e_select_apply", {
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
        has_unsaved_changes = true;
        if(element_id==4737){
            //Changing idea type would re-load dynamic fields based on type:
            console.log('Reloading: '+element_id+' with value: '+' NEW ID '+new_e__id+' / '+$('#modal31911 .created_i__id').val());
            load_i_dynamic($('#modal31911 .created_i__id').val(), 0, new_e__id, false);
        }
    }
}

function ui_instant_select(element_id, new_e__id, o__id, x__id, show_full_name){

    //Update x:
    console.log('UI instant .dropd_instant_'+element_id+'_'+o__id+'_'+x__id+' .btn' + new_e__id);
    var data_object = eval('js_e___'+element_id);
    $('.dropd_instant_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block">'+data_object[new_e__id]['m__cover']+'</span>' + ( show_full_name ? data_object[new_e__id]['m__title'] : '' ));

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
    $('.dropd_instant_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block-xs"><i class="far fa-yin-yang fa-spin"></i></span>');

    $.post("/ajax/x_update_instant_select", {
        focus_id:fetch_int_val('#focus_id'),
        o__id: o__id,
        element_id: element_id,
        new_e__id: new_e__id,
        migrate_s__handle: migrate_s__handle,
        x__id: x__id
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








function e_reset_discoveries(e__id){
    //Confirm First:
    var r = confirm("DANGER WARNING!!! You are about to delete your ENTIRE discovery history. This action cannot be undone and you will lose all your discovery coins.");
    if (r==true) {
        $('.e_reset_discoveries').html('<span class="icon-block-xx"><i class="far fa-yin-yang fa-spin"></i></span><b class="main__title">REMOVING ALL</b>');

        //Redirect:
        js_redirect('/ajax/e_reset_discoveries/'+e__id);
    } else {
        return false;
    }
}


function e_sort_save(x__type) {

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
        $.post("/ajax/e_sort_save", {e__id: fetch_int_val('#focus_id'), x__type:x__type, new_x__weight: new_x__weight}, function (data) {
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

        var focus_card = fetch_int_val('#focus_card');
        var focus_id = fetch_int_val('#focus_id');
        var focus_handle = fetch_int_val('#focus_handle');


        //Update via call:
        $.post("/ajax/x_reset_sorting", {
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

