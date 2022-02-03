

//Google Analytics
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'UA-92774608-1');




// url Async requesting function
function images_api_getasync(api_id, query, callback) {
    // create the request object
    var xmlHttp = new XMLHttpRequest();

    // set the state change callback to capture when the response comes in
    xmlHttp.onreadystatechange = function()
    {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
    {
        callback(xmlHttp.responseText);
    }
    }

        // open as a GET call, pass in the url and set async = True
        xmlHttp.open("GET", js_e___6404[api_id]['m__message'] + query, true);

        // call send with no params as they were passed in on the url string
        xmlHttp.send(null);

        return;
}



function tenor_search_cover(responsetext) {
    // parse the json response
    var response_objects = JSON.parse(responsetext);
    response_objects["results"].forEach(function(item) {
        if(!($('#img_results_tenor').html().indexOf(item["media"][0]["nanogif"]["url"]) > -1)){
            $("#img_results_tenor").append(image_cover(item["media"][0]["nanogif"]["url"], item["media"][0]["gif"]["url"], item["h1_title"].replace("'",'')));
        }
    });
}
function unsplash_search_cover(responsetext) {
    var response_objects = JSON.parse(responsetext);
    console.log(response_objects);
    response_objects["results"].forEach(function(item) {
        if(!($('#img_results_unsplash').html().indexOf(item["urls"]["thumb"]) > -1)){
            var title = item["description"] + ' ' + item["alt_description"];
            $("#img_results_unsplash").append(image_cover(item["urls"]["thumb"], item["urls"]["regular"], title.replace("'",'')));
        }
    });
}

function tenor_search_box(responsetext) {
    // parse the json response
    var response_objects = JSON.parse(responsetext);
    response_objects["results"].forEach(function(item) {
        if(!($('.new_images').html().indexOf(item["media"][0]["gif"]["url"]) > -1)) {
            $(".new_images").append("<div class=\"gif-col col-xl-2 col-lg-3 col-4\"><a href=\"javascript:void(0);\" onclick=\"images_add('" + item["media"][0]["gif"]["url"] + "','" + item["h1_title"].replace("'", '') + "')\"><img src='" + item["media"][0]["tinygif"]["url"] + "' alt='" + item["h1_title"].replace("'", '') + "' /></a></div>");
        }
    });
}
function unsplash_search_box(responsetext) {
    // parse the json response
    var response_objects = JSON.parse(responsetext);
    response_objects["results"].forEach(function(item) {
        if(!($('.new_images').html().indexOf(item["urls"]["thumb"]) > -1)) {
            var title = item["description"] + ' ' + item["alt_description"];
            $(".new_images").append("<div class=\"gif-col col-xl-2 col-lg-3 col-4\"><a href=\"javascript:void(0);\" onclick=\"images_add('" + item["urls"]["regular"] + "','" + title.replace("'", '') + "')\"><img src='" + item["urls"]["thumb"] + "' alt='" + title.replace("'", '') + "' /></a></div>");
        }
    });
}

function video_play(){
    $('iframe.yt-video').attr('src', $('iframe.yt-video').attr('src')+'&autoplay=1' );
    $('.video-frame').toggleClass('hidden');
}


//Full Story
if(js_pl_id > 1){ //Any user other than Shervin

    /*

    <!-- Microsoft Clarify -->
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "59riunqvfm");

    $(document).ready(function () {
        clarity("set", "member__id", js_pl_id+"");
        clarity("set", "member__title", js_pl_name+"");
    });

    <!-- Hotjar Tracking Code for My site -->
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:2721962,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    */

    window['_fs_debug'] = false;
    window['_fs_host'] = 'fullstory.com';
    window['_fs_script'] = 'edge.fullstory.com/s/fs.js';
    window['_fs_org'] = 'QMKCQ';
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
            profileURL: base_url+'/@'+js_pl_id
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


function apply_all_load(apply_id, coin__id){

    x_create({
        x__source: js_pl_id,
        x__type: 14576, //MODAL VIEWED
        x__up: apply_id,
        x__down: ( coin__type==4997 ? coin__id : 0 ),
        x__right: ( coin__type==12589 ? coin__id : 0 ),
    });

    //Select first:
    var first_id = $('#modal'+apply_id+' .mass_action_toggle option:first').val();
    $('.mass_action_item').addClass('hidden');
    $('.mass_id_' + first_id ).removeClass('hidden');
    $('#modal'+apply_id+' .mass_action_toggle').val(first_id);
    $('#modal'+apply_id+' input[name="coin__id"]').val(coin__id);
    $('#modal'+apply_id).modal('show');

    //Load Ppeview:
    $('#modal'+apply_id+' .apply_preview').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading...');
    $.post("/x/apply_preview", {
        apply_id: apply_id,
        coin__id: coin__id
    }, function (data) {
        $('#modal'+apply_id+' .apply_preview').html(data);
    });

}


function load_editor(){

    $('.mass_action_toggle').change(function () {
        $('.mass_action_item').addClass('hidden');
        $('.mass_id_' + $(this).val() ).removeClass('hidden');
    });

    if(parseInt(js_e___6404[12678]['m__message'])){
        $('.e_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('@' + suggestion.s__id + ' ' + suggestion.s__title);

        }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 's__type=12274',
                    hitsPerPage: 8,
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            displayKey: function (suggestion) {
                return '@' + suggestion.s__id + ' ' + suggestion.s__title;
            },
            templates: {
                suggestion: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                empty: function (data) {
                    return '<div class="css__title"><i class="fas fa-exclamation-circle"></i> No Sources Found</div>';
                },
            }
        }]);

        $('.i_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('#' + suggestion.s__id + ' ' + suggestion.s__title);

        }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 's__type=12273',
                    hitsPerPage: 8,
                }, function (error, content) {
                    if (error) {
                        cb([]);
                        return;
                    }
                    cb(content.hits, content);
                });
            },
            displayKey: function (suggestion) {
                return '#' + suggestion.s__id + ' ' + suggestion.s__title;
            },
            templates: {
                suggestion: function (suggestion) {
                    return view_s_js_line(suggestion);
                },
                empty: function (data) {
                    return '<div class="css__title"><i class="fas fa-exclamation-circle"></i> No Ideas Found</div>';
                },
            }
        }]);

    }
}


function view_s__title(suggestion){
    return htmlentitiesjs( suggestion._highlightResult && suggestion._highlightResult.s__title.value ? suggestion._highlightResult.s__title.value : suggestion.s__title );
}


function view_s_js_line(suggestion){
    return '<span class="icon-block">'+ view_cover_js(suggestion.s__type, suggestion.s__cover) +'</span><span class="css__title">' + view_s__title(suggestion) + '</span><span class="grey">&nbsp;' + ( suggestion.s__type==12273 ? '/' : '@' ) + suggestion.s__id + '</span>';
}

function view_s_js_coin(x__type, suggestion, action_id){

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
            icon_image = view_cover_js(suggestion.s__type, suggestion.s__cover);
        }
    }

    //Return appropriate UI:
    if(x__type==26011){
        //Mini Coin
        return '<div class="coin_cover mini-coin coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-md-2 col-sm-3 col-4 col-xl-1 no-padding"><div class="cover-wrapper"><a href="'+suggestion.s__url+'" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="'+suggestion.s__url+'" class="css__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==26012){
        //Link Idea
        return '<div class="coin_cover mini-coin coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-md-2 col-sm-3 col-4 col-xl-1 no-padding"><div class="cover-wrapper"><div class="coin-cover coin-cover-right">'+js_e___11035[26012]['m__cover']+'</div><a href="javascript:void(0);" onclick="i__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="'+suggestion.s__url+'" target="_blank" class="css__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==26013){
        //Link Source
        return '<div class="coin_cover mini-coin coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-md-2 col-sm-3 col-4 col-xl-1 no-padding"><div class="cover-wrapper"><div class="coin-cover coin-cover-right">'+js_e___11035[26013]['m__cover']+'</div><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="'+suggestion.s__url+'" target="_blank" class="css__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==7551){
        //1x Source
        return '<div class="coin_cover mini-coin coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-md-2 col-sm-3 col-4 col-xl-1 no-padding"><div class="cover-wrapper"><div class="coin-cover coin-cover-right">'+js_e___11035[7551]['m__cover']+'</div><a href="javascript:void(0);" onclick="e_add_only_7551('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="'+suggestion.s__url+'" target="_blank" class="css__title">'+suggestion.s__title+'</a></div></div></div>';
    }

}
function view_s_mini_js(s__type,s__cover,s__title){
    return '<span class="block-icon" title="'+s__title+'">'+ view_cover_js(s__type, s__cover) +'</span>';
}


function current_id(){
    return ( $('#focus__id').length ? parseInt($('#focus__id').val()) : 0 );
}

function toggle_headline(x__type){

    var x__down = 0;
    var x__right = 0;
    var current_type = ( $('#focus__type').length ? parseInt($('#focus__type').val()) : 0 );
    if(current_type==12273){
        x__right = current_id();
    } else if (current_type==12274){
        x__down = current_id();
    }

    if($('.headline_title_' + x__type+' .icon_26008').hasClass('hidden')){
        //Currently open, must now be closed:
        var action_id = 26008; //Close
        $('.headline_title_' + x__type+ ' .icon_26008').removeClass('hidden');
        $('.headline_title_' + x__type+ ' .icon_26007').addClass('hidden');
        $('.headline_body_' + x__type).addClass('hidden');
    } else {
        //Currently closed, must now be opened:
        var action_id = 26007; //Open
        $('.headline_title_' + x__type+ ' .icon_26007').removeClass('hidden');
        $('.headline_title_' + x__type+ ' .icon_26008').addClass('hidden');
        $('.headline_body_' + x__type).removeClass('hidden');
    }

    //Log Transaction:
    x_create({
        x__source: js_pl_id,
        x__type: action_id,
        x__up: x__type,
        x__down: x__down,
        x__right: x__right,
    });
}


function e_sort_load(x__type) {

    var sort_item_count = parseInt($('.new-list-'+x__type).attr('current-count'));
    if(!js_n___13911.includes(x__type)){
        //Does not support sorting:
        return false;
    } else if(sort_item_count<1 || sort_item_count>=parseInt(js_e___6404[13005]['m__message'])){
        return false;
    }

    var theobject = document.getElementById("list-in-"+x__type);
    if (!theobject) {
        //due to duplicate ideas belonging in this idea:
        return false;
    }

    //Show sort icon:
    $('.sort_e, .sort_reset').removeClass('hidden');

    var sort = Sortable.create(theobject, {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".coinface-12274", // Specifies which items inside the element should be sortable
        handle: ".sort_e", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            e_sort_save(x__type);
        }
    });
}


function toggle_pills(x__type){

    var x__down = 0;
    var x__right = 0;
    var current_type = ( $('#focus__type').length ? parseInt($('#focus__type').val()) : 0 );


    if(current_type==12273){
        x__right = current_id();
    } else if (current_type==12274){
        x__down = current_id();
    }

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
        window.location.hash = '#'+x__type;

        //Do we need to load data via ajax?
        if( !$('.headline_body_' + x__type).html().length ){

            $('.headline_body_' + x__type).html('<div class="center"><i class="far fa-yin-yang fa-spin"></i></div>');

            //Nothing loaded, we need to load:
            if (current_type==12273){
                $.post("/i/i_view_body_i", {
                    x__type:x__type,
                    counter:$('.headline_body_' + x__type).attr('item-counter'),
                    i__id:current_id()
                }, function (data) {
                    $('.headline_body_' + x__type).html(data);
                    load_tab(x__type);
                });
            } else if (current_type==12274){
                $.post("/e/e_view_body_e", {
                    x__type:x__type,
                    counter:$('.headline_body_' + x__type).attr('item-counter'),
                    e__id:current_id()
                }, function (data) {
                    $('.headline_body_' + x__type).html(data);
                    load_tab(x__type);
                });
            }
        }
    }

    //Log Transaction:
    x_create({
        x__source: js_pl_id,
        x__type: action_id,
        x__up: x__type,
        x__down: x__down,
        x__right: x__right,
    });
}

function load_tab(x__type){

    initiate_algolia();
    load_coins();
    e_e_only_search_7551();
    i_note_activate();
    load_editor();
    x_type_preview_load();
    init_remove();

    e_load_search(x__type);
    i_load_search(x__type);
    e_sort_load(x__type);
    x_sort_load(x__type);
}

function i_reset_discoveries(i__id){
    //Confirm First:
    var r = confirm("You are about to delete all discoveries made on this idea?");
    if (r != true) {
        return false;
    }

    //Go ahead and delete:
    $('.i_reset_discoveries_'+i__id).fadeOut();
    $.post("/x/i_reset_discoveries", {
        i__id:i__id
    }, function (data) {
        //Update UI to confirm with member:
        alert(data.message);
    });

}


function view_load_page_i(x__type, page, load_new_filter) {

    if (load_new_filter) {
        //Replace load more with spinner:
        var append_div = $('.new-list-'+x__type).html();
        //The padding-bottom would delete the scrolling effect on the left side!
        $('#list-in-'+x__type).html('<span class="load-more" style="padding-bottom:500px;"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    }

    $.post("/i/view_load_page_i", {
        x__type: x__type,
        page: page,
        focus__id: current_id(),
    }, function (data) {

        //Appending to existing content:
        $('.load-more').remove();

        if (load_new_filter) {

            $('#list-in-'+x__type).html(data + '<div class="new-list-'+x__type+' list-group-item no-side-padding grey-input">' + append_div + '</div>').hide().fadeIn();
            //Reset search engine:
            e_load_search(x__type);

        } else {
            //Update UI to confirm with member:
            $('#list-in-'+x__type).append(data);
        }

        lazy_load();

        x_set_start_text();

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}





function view_load_page_e(x__type, page, load_new_filter) {

    if (load_new_filter) {
        //Replace load more with spinner:
        var append_div = $('.new-list-'+x__type).html();
        //The padding-bottom would delete the scrolling effect on the left side!
        $('#list-in-'+x__type).html('<span class="load-more" style="padding-bottom:500px;"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    } else {
        //Replace load more with spinner:
        $('.load-more').html('<span class="load-more"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></span>').hide().fadeIn();
    }

    $.post("/e/view_load_page_e", {
        x__type: x__type,
        page: page,
        focus__id: current_id(),
    }, function (data) {

        //Appending to existing content:
        $('.load-more').remove();

        if (load_new_filter) {

            $('#list-in-'+x__type).html(data + '<div class="new-list-'+x__type+' list-group-item no-side-padding grey-input">' + append_div + '</div>').hide().fadeIn();
            //Reset search engine:
            e_load_search(x__type);

        } else {
            //Update UI to confirm with member:
            $('#list-in-'+x__type).append(data);
        }

        lazy_load();

        x_set_start_text();

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}




function js_view_shuffle_message(e__id){
    var messages = js_e___12687[e__id]['m__message'].split("\n");
    if(messages.length == 1){
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

function lazy_load(){
    //Lazyload photos:
    var lazyLoadInstance = new LazyLoad({
        elements_selector: "img.lazyimage"
    });
}





var init_in_process = 0;
function init_remove(){
    $(".x_remove").click(function(event) {

        event.preventDefault();

        var i__id = $(this).attr('i__id');
        var x__id = $(this).attr('x__id');

        if(init_in_process==(x__id + i__id)){
            return false;
        }
        init_in_process = (x__id + i__id);
        var r = confirm("Remove "+$('.text__4736_'+i__id+':first').text()+"?");
        if (r == true) {
            //Save changes:
            $.post("/x/x_remove", {
                x__id:x__id
            }, function (data) {
                //Update UI to confirm with member:
                if (!data.status) {

                    //There was some sort of an error returned!
                    alert(data.message);

                } else {

                    //REMOVE BOOKMARK from UI:
                    $('.cover_x_'+x__id).fadeOut();

                    setTimeout(function () {

                        //Delete from body:
                        $('.cover_x_'+x__id).remove();

                    }, 233);
                }
            });
        }
    });
}



function x_create(add_fields){
    return $.post("/x/x_create", add_fields);
}

function load_coin_count(){
    $.post("/x/load_coin_count", {}, function (data) {
        if($(".coin_count_x:first").text()!=data.count__x){
            $(".coin_count_x").text(data.count__x).hide().fadeIn().hide().fadeIn();
        }
        if($(".coin_count_12273:first").text()!=data.count__12273){
            $(".coin_count_12273").text(data.count__12273).hide().fadeIn().hide().fadeIn();
        }
        if($(".coin_count_12274:first").text()!=data.count__12274){
            $(".coin_count_12274").text(data.count__12274).hide().fadeIn().hide().fadeIn();
        }
        if($(".coin_count_6255:first").text()!=data.count__6255){
            $(".coin_count_6255").text(data.count__6255).hide().fadeIn().hide().fadeIn();
        }
    });
}

function update__cover(new_cover){
    $('#coin__cover').val( new_cover );
    update_cover_main(new_cover, '.demo_cover');
}
function image_cover(cover_preview, cover_apply, new_title){
    return '<a href="#preview_cover" onclick="update__cover(\''+cover_apply+'\')">' + view_s_mini_js(12274, cover_preview, new_title) + '</a>';
}


function cover_upload(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.coverUpload').hasClass('dynamic_saving')) {
        return false;
    }

    $('#upload_results').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="css__title">UPLOADING...</span>');

    if (isAdvancedUpload) {

        var ajaxData = new FormData($('.coverUpload').get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $('.coverUpload').find('input[type="file"]').attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('coin__type', $('#coin__type').val());
        ajaxData.append('coin__id', $('#coin__id').val());

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

function e_load_coin(x__type, e__id, counter, first_segment){

    if($('.coins_e_'+e__id+'_'+x__type).html().length){
        //Already loaded:
       return false;
    }

    $('.coins_e_'+e__id+'_'+x__type).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    $.post("/e/e_load_coin", {
        x__type:x__type,
        e__id:e__id,
        counter:counter,
        first_segment:first_segment,
    }, function (data) {
        $('.coins_e_'+e__id+'_'+x__type).html(data);
    });

}

function i_load_coin(x__type, i__id, counter, first_segment, current_e){

    if($('.coins_i_'+i__id+'_'+x__type).html().length){
        //Already loaded:
        return false;
    }

    $('.coins_i_'+i__id+'_'+x__type).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    $.post("/i/i_load_coin", {
        x__type:x__type,
        i__id:i__id,
        counter:counter,
        first_segment:first_segment,
    }, function (data) {
        $('.coins_i_'+i__id+'_'+x__type).html(data);
    });

}

function load_message_27963(i__id){

    //Grab and load data:
    $('.input_note_4231').val(''); //Reset until loaded
    $('.note_error_4231').html('');
    $('.top_message_box').removeClass('hidden');
    $('#modal_i__id').val(i__id);

    $.post("/i/load_message_27963", {
        i__id:i__id,
    }, function (data) {
        if(data.status){
            $('.input_note_4231').val(data.message.trim()).focus();
            set_autosize($('.input_note_4231'));
            setTimeout(function () {
                autosize.update($(".input_note_4231"));
            }, 233);
        } else {
            $('.note_error_4231').html(data.message);
        }
    });

}

function load_coins(){
    $(".load_e_coins").click(function(event) {
        e_load_coin($(this).attr('load_x__type'),$(this).attr('load_e__id'),$(this).attr('load_counter'),$(this).attr('load_first_segment'));
    });
    $(".load_i_coins").click(function(event) {
        i_load_coin($(this).attr('load_x__type'),$(this).attr('load_i__id'),$(this).attr('load_counter'),$(this).attr('load_first_segment'));
    });
}

var algolia_index = false;
$(document).ready(function () {

    //Watchout for file uplods:
    $('.coverUpload').find('input[type="file"]').change(function () {
        cover_upload(droppedFiles, 'file');
    });

    if(window.location.hash) {
        var the_hash = window.location.hash.substring(1);
        if(!(the_hash == $('.nav-link.active').attr('x__type')) && isNormalInteger(the_hash)){
            toggle_pills(the_hash);
        }
    }

    load_coins();

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



    if ($(".list-coins")[0]){
        //Update COINS every 3 seconds:
        $(function () {
            setInterval(load_coin_count, js_e___6404[14874]['m__message']);
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
    $('#coin__cover').keyup(function() {
        update_cover_main($(this).val(), '.demo_cover');
    });

    init_remove();
    lazy_load();
    set_autosize($('#sugg_note'));
    set_autosize($('.texttype__lg'));

    $('.trigger_modal').click(function (e) {
        var x__type = parseInt($(this).attr('x__type'));
        $('#modal'+x__type).modal('show');
        x_create({
            x__source: js_pl_id,
            x__type: 14576, //MODAL VIEWED
            x__up: x__type,
        });
        //Log Viewed Transaction
        if(x__type==14393){
            //Current
            $('.current_url').text(window.location.href);
        } else if(x__type==6287){
            //Load App Modal:
            $.post("/app/load_index", {}, function (data) {
                $('#modal6287 .modal-body').html(data.status ? data.load_index : data.message );
            });
        }
    });


    $('#topnav li a').click(function (e) {

        e.preventDefault();
        var hash = $(this).attr('href').replace('#', '');

        if (hash.length > 0 && $('#tab' + hash).length && !($('#tab' + hash).hasClass("hidden"))) {
            //Adjust Header:
            $('#topnav>li').removeClass('active');
            $('#nav_' + hash).addClass('active');
            //Adjust Tab:
            $('.tab-pane').removeClass('active');
            $('#tab' + hash).addClass('active');
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

        //COVER SEARCH
        $('.cover_query').autocomplete({hint: false, autoselect: false, minLength: 1}, [{

            source: function (q, cb) {

                if(validURL(q)){
                    //Must be an image URL:
                    update__cover(q);
                    return true;
                }

                $("#upload_results, #icon_suggestions, #img_results_icons, #img_results_emojis, #img_results_tenor, #img_results_unsplash, #img_results_local").html('');

                //Tenor:
                images_api_getasync(25986, q, tenor_search_cover);

                //Unsplash:
                images_api_getasync(18139, q, unsplash_search_cover);


                icons_listed = [];
                algolia_index.search(q, {
                    filters: ' _tags:alg_e_14988 OR _tags:alg_e_14038 OR _tags:alg_e_14986 OR _tags:alg_e_20425 OR _tags:alg_e_20426 OR _tags:alg_e_20427 OR _tags:has_image ',
                    hitsPerPage: 300,
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
                    //Make sure not already returned:
                    if(!icons_listed.includes(suggestion.s__cover)) {
                        icons_listed.push(suggestion.s__cover);
                        if(validURL(suggestion.s__cover)){
                            $("#img_results_local").append(image_cover(suggestion.s__cover, suggestion.s__cover, suggestion.s__title));
                        } else if (suggestion.s__cover.includes("fa")) {
                            $("#img_results_icons").append(image_cover(suggestion.s__cover, suggestion.s__cover, suggestion.s__title));
                        } else {
                            $("#img_results_emojis").append(image_cover(suggestion.s__cover, suggestion.s__cover, suggestion.s__title));
                        }
                    }
                    return false;
                },
                empty: function (data) {
                    //Nothing found:
                    return '<div class="css__title"><i class="fas fa-exclamation-circle"></i> Nothing Found</div>';
                },
            }
        }]);

        //TOP SEARCH
        $("#top_search").autocomplete({minLength: 1, autoselect: false, keyboardShortcuts: ['s']}, [
            {
                source: function (q, cb) {

                    //Members can filter search with first word:
                    var search_only_e = $("#top_search").val().charAt(0) == '@';
                    var search_only_in = $("#top_search").val().charAt(0) == '#';
                    $("#container_search .row").html(''); //Reset results view

                    //Do not search if specific command ONLY:
                    if (( search_only_in || search_only_e ) && !isNaN($("#top_search").val().substr(1)) ) {

                        cb([]);
                        return;

                    } else {

                        //Now determine the filters we need to apply:
                        var search_filters = '';

                        if(search_only_e || search_only_in){
                            search_filters += ' s__type='+( search_only_in ? 12273 : 12274 );
                        }

                        if(js_pl_id > 0){

                            //For Members:
                            if(!superpower_js_12701){
                                //Can view limited sources:
                                if(search_filters.length>0){
                                    search_filters += ' AND ';
                                }
                                search_filters += ' ( _tags:is_featured OR _tags:alg_e_' + js_pl_id + ' ) ';
                            }

                        } else {

                            //Guest can search ideas only by default as they start typing;
                            if(search_filters.length>0){
                                search_filters += ' AND ';
                            }
                            search_filters += ' _tags:is_featured ';

                        }

                        //Append filters:
                        algolia_index.search(q, {
                            hitsPerPage: 55,
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
                        $("#container_search .row").append(view_s_js_coin(26011, suggestion, 0));
                        return false;
                    },
                    empty: function (data) {
                        $("#container_search .row").html('<div class="css__title margin-top-down-half"><span class="icon-block"><i class="fal fa-exclamation-circle"></i></span>No results found</div>');
                    },
                }
            }
        ]);
    }
});



function x_type_preview_load(){

    //Watchout for content change
    var textInput = document.getElementById('x__message');
    if(!textInput){
        return false;
    }

    //Init a timeout variable to be used below
    var timeout = null;

    //Listen for keystroke events
    textInput.onkeyup = function (e) {

        // Clear the timeout if it has previously been set.
        // This will prevent the previous step from executing
        // if it has been less than <MILLISECONDS>
        clearTimeout(timeout);

        // Make a new timeout set to go off in 800ms
        timeout = setTimeout(function () {
            //update type:
            x_type_preview();
        }, 610);
    };

}



function update_cover_main(cover_code, target_css){

    //Set Default:
    $(target_css+' .cover-link').css('background-image','');
    $(target_css+' .cover-btn').html('');

    //Update:
    if(validURL(cover_code)){
        $(target_css+' .cover-link').css('background-image','url(\''+cover_code+'\')');
    } else if(cover_code.indexOf('fa-')>=0) {
        $(target_css+' .cover-btn').html('<i class="'+cover_code+'"></i>');
    } else if(cover_code.length > 0) {
        $(target_css+' .cover-btn').text(cover_code);
    }
}

function view_cover_js(coin__type, cover_code){
    if(cover_code && cover_code.length){
        if(validURL(cover_code)){
            return '<img src="'+cover_code+'" />';
        } else if(cover_code.indexOf('fa-')>=0) {
            return '<i class="'+cover_code+'"></i>';
        } else {
            return cover_code;
        }
    } else {
        return '<i class="fas fa-circle zq'+coin__type+'"></i>';
        //return '<img src="/img/'+coin__type+'.png" />';
    }
}

function update_cover_mini(coin__type, cover_code, target_css){
    //Update:
    $(target_css).html(view_cover_js(coin__type, cover_code));
}


function x_message_load(x__id) {

    x_create({
        x__source: js_pl_id,
        x__type: 14576, //MODAL VIEWED
        x__up: 13571,
        x__reference: x__id,
    });

    //Load current Source:
    $.post("/x/x_message_load", {

        x__id: x__id,

    }, function (data) {

        if (data.status) {

            $('#modal13571').modal('show');

            //Update variables:
            $('#modal13571 .modal_x__id').val(x__id);
            $('#modal13571 .save_results').html('');
            $('#x__message').val(data.x__message);
            x_type_preview();
            setTimeout(function () {
                set_autosize($('#x__message'));
                autosize.update($("#x__message"));
                $('#x__message').focus();
            }, 144);

        } else {

            alert(data.message);

        }
    });
}

function coin__load(coin__type, coin__id){

    x_create({
        x__source: js_pl_id,
        x__type: 14576, //MODAL VIEWED
        x__up: 14937,
        x__down: ( coin__type==12274 ? coin__id : 0 ),
        x__right: ( coin__type==12273 ? coin__id : 0 ),
    });

    $('#modal14937').modal('show');
    $('#search_cover').val('').focus();
    $("#upload_results, #icon_suggestions, #img_results_icons, #img_results_emojis, #img_results_tenor, #img_results_unsplash, #img_results_local").html('');
    $('#coin__title, #coin__cover').val('LOADING...');
    $('#modal14937 .black-background-obs').removeClass('coinType12273').removeClass('coinType12274').addClass('coinType'+coin__type);

    $.post("/e/coin__load", {
        coin__type: coin__type,
        coin__id: coin__id
    }, function (data) {

        if (data.status) {

            $('#coin__type').val(coin__type);
            $('#coin__id').val(coin__id);
            $('#coin__title').val(data.coin__title);
            $('#coin__cover').val(data.coin__cover);
            update_cover_main(data.coin__cover, '.demo_cover');

            //Any suggestions to auto load?
            if(data.icon_suggestions.length){
                data.icon_suggestions.forEach(function(item) {
                    $("#icon_suggestions").append(image_cover(item.cover_preview, item.cover_apply, item.new_title));
                });
            }

        } else {

            //Ooops there was an error!
            alert(data.message);

        }

    });

}






function i_load_search(x__type) {

    if(!parseInt(js_e___6404[12678]['m__message'])){
        alert('Search is currently disabled');
        return false;
    } else if(!js_n___14685.includes(x__type)){
        return false;
    }


    $('.new-list-'+x__type+' .add-input').keypress(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            e.preventDefault();
            return i__add(x__type, 0);
        }

    }).keyup(function () {

        //Clear if no input:
        if(!$(this).val().length){
            $('.new-list-'+x__type+' .algolia_pad_search').html('');
        }

    }).autocomplete({hint: false, autoselect: false, minLength: 1}, [{
        source: function (q, cb) {

            $('.new-list-'+x__type+' .algolia_pad_search').html('');

            algolia_index.search(q, {

                filters: ' s__type=12273 ' + ( superpower_js_12701 ? '' : ' AND ( _tags:is_featured ' + ( js_pl_id > 0 ? 'OR _tags:alg_e_' + js_pl_id : '' ) + ') ' ),
                hitsPerPage:34,

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
                $('.new-list-'+x__type+' .algolia_pad_search').append(view_s_js_coin(26012, suggestion, x__type));
            },
            header: function (data) {
                if(data.query && data.query.length){
                    $('.new-list-'+x__type+' .algolia_pad_search').prepend('<div class="coin_cover mini-coin coin-12273 coin-id-0 col-md-2 col-sm-3 col-4 col-xl-1 no-padding"><div class="cover-wrapper"><div class="coin-cover coin-cover-right"><i class="fas fa-plus-circle zq12273"></i></div><a href="javascript:void(0);" onclick="i__add('+x__type+', 0)" class="black-background-obs cover-link coinType12273"><div class="cover-btn"></div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="i__add('+x__type+', 0)" class="css__title">'+data.query+'</a></div></div></div>');
                }
            },
            empty: function (data) {
                return '';
            },
        }
    }]);

}

function e_load_search(x__type) {

    //Search Enabled?
    if(!parseInt(js_e___6404[12678]['m__message'])){
        return false;
    }

    //Valid Source Creation Type?
    if(!js_n___14055.includes(x__type)){
        return false;
    }

    //Load Search:
    $('.new-list-'+x__type + ' .add-input').keypress(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            if(superpower_js_13422){
                e__add(x__type, 0);
            }
            return true;
        }

    }).keyup(function () {

        //Clear if no input:
        if(!$(this).val().length){
            $('.new-list-'+x__type+' .algolia_pad_search').html('');
        }

    }).autocomplete({hint: false, autoselect: false, minLength: 1}, [{
        source: function (q, cb) {

            $('.new-list-'+x__type+' .algolia_pad_search').html('');

            algolia_index.search(q, {
                filters: 's__type=12274' + ( superpower_js_13422 ? '' : ' AND ( _tags:alg_e_13897 ) ' ), /* Nonfiction Content */
                hitsPerPage:34,
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
                $('.new-list-'+x__type+' .algolia_pad_search').append(view_s_js_coin(26013, suggestion, x__type));
            },
            header: function (data) {
                if(data.query && data.query.length){
                    $('.new-list-'+x__type+' .algolia_pad_search').prepend('<div class="coin_cover mini-coin coin-12274 coin-id-0 col-md-2 col-sm-3 col-4 col-xl-1 no-padding"><div class="cover-wrapper"><div class="coin-cover coin-cover-right"><i class="fas fa-plus-circle zq12274"></i></div><a href="javascript:void(0);" onclick="e__add('+x__type+', 0)" class="black-background-obs cover-link coinType12274"><div class="cover-btn"></div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e_add('+x__type+', 0)" class="css__title">'+data.query+'</a></div></div></div>');
                }
            },
            empty: function (data) {
                return '';
            }
        }
    }]);
}


function coin__save(){

    $.post("/x/coin__save", {
        coin__type: $('#coin__type').val(),
        coin__id: $('#coin__id').val(),
        coin__title: $('#coin__title').val(),
        coin__cover: $('#coin__cover').val()
    }, function (data) {

        if (data.status) {

            //Update Icon/Title on Page:
            $('#modal14937').modal('hide');

            //Update Title:
            if($('#coin__type').val()==12273){
                var text_field = 4736;
            } else if($('#coin__type').val()==12274){
                var text_field = 6197;
            }
            update_text_name(text_field, $('#coin__id').val(), $('#coin__title').val());

            //Update Mini Icon:
            update_cover_mini($('#coin__type').val(), $('#coin__cover').val(), '.mini_'+text_field+'_'+$('#coin__id').val());


            //Update Main Icons:
            update_cover_main($('#coin__cover').val(), '.coin___'+$('#coin__type').val()+'_'+$('#coin__id').val());

        } else {

            //Ooops there was an error!
            alert(data.message);

        }

    });

}


var i_is_adding = false;
function i__add(x__type, link_i__id) {

    /*
     *
     * Either creates an IDEA transaction between focus__id & link_i__id
     * OR will create a new idea based on input text and then transaction it
     * to current_id() (In this case link_i__id=0)
     *
     * */

    if(i_is_adding){
        return false;
    }

    //Remove results:
    $('.mini-coin.coin-12273.coin-id-'+link_i__id+' .cover-btn').html('<i class="far fa-yin-yang fa-spin"></i>');
    i_is_adding = true;
    var sort_handler = ".coin_cover";
    var input_field = $('.new-list-'+x__type+' .add-input');
    var i__title = input_field.val();


    //We either need the idea name (to create a new idea) or the link_i__id>0 to create an IDEA transaction:
    if (!link_i__id && i__title.length < 1) {
        alert('Missing Idea Title');
        input_field.focus();
        return false;
    }

    //Set processing status:
    input_field.addClass('dynamic_saving');
    add_to_list("list-in-" + x__type, sort_handler, '<div id="tempLoader" class="col-md-4 col-6 col-xl-2 col-lg-3 no-padding show_all_ideas"><div class="cover-wrapper"><div class="black-background-obs cover-link"><div class="cover-btn"><i class="far fa-yin-yang fa-spin"></i></div></div></div></div>');


    //Update backend:
    $.post("/i/i__add", {
        x__type: x__type,
        focus__id: current_id(),
        i__title: i__title,
        link_i__id: link_i__id
    }, function (data) {

        //Delete loader:
        $("#tempLoader").remove();
        input_field.removeClass('dynamic_saving').prop("disabled", false).focus();
        i_is_adding = false;

        if (data.status) {

            if(x__type==13542){
                //Next Ideas map to ideas so increment counter:
                i_note_counter(12273, +1);
            }

            x_sort_load(x__type);

            //Add new
            add_to_list("list-in-" + x__type, sort_handler, data.new_i_html);

            //Lookout for textinput updates
            x_set_start_text();
            set_autosize($('.texttype__lg'));

            //Hide Coin:
            $('.mini-coin.coin-12273.coin-id-'+link_i__id).fadeOut();

        } else {
            //Show errors:
            alert(data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

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

    $('.mini-coin.coin-12274.coin-id-'+e_existing_id+' .cover-btn').html('<i class="far fa-yin-yang fa-spin"></i>');

    var input = $('.new-list-'+x__type+' .add-input');

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

        x__type: x__type,
        focus__id: current_id(),
        e_existing_id: e_existing_id,
        e_new_string: e_new_string,

    }, function (data) {

        e_is_adding = false;

        if (data.status) {

            if(data.e_already_linked){
                alert('Note: This is already linked here! Make sure this double linking is intentional.');
            }

            //Raw input to make it ready for next URL:
            input.focus();

            //Add new object to list:
            add_to_list('list-in-'+x__type, '.coinface-12274', data.e_new_echo);

            //Allow inline editing if enabled:
            x_set_start_text();

            e_sort_load(x__type);
            load_coins();

            //Hide Coin:
            $('.mini-coin.coin-12274.coin-id-'+e_existing_id).fadeOut();

        } else {
            //We had an error:
            alert(data.message);
        }

    });
}



function e_add_only_7551(x__type, e_existing_id) {


    //if e_existing_id>0 it means we're adding an existing source, in which case e_new_string should be null
    //If e_existing_id=0 it means we are creating a new source and then adding it, in which case e_new_string is required

    var e_new_string = null;
    var input = $('.e-i-'+x__type+' .add-input');

    if (e_existing_id == 0) {

        e_new_string = input.val();
        if (e_new_string.length < 1) {
            alert('Missing source name or URL, try again');
            input.focus();
            return false;
        }
    }

    $('.mini-coin.coin-12274.coin-id-'+e_existing_id+' .cover-btn').html('<i class="far fa-yin-yang fa-spin"></i>');

    //Add via Ajax:
    $.post("/e/e_add_only_7551", {

        i__id: current_id(),
        x__type: x__type,
        e_existing_id: e_existing_id,
        e_new_string: e_new_string,

    }, function (data) {

        if (data.status) {

            i_note_counter(x__type, +1);

            //Raw input to make it ready for next URL:
            input.focus();

            //Add new object to list:
            add_to_list('list-in-'+x__type, '.coinface-12274', data.e_new_echo);

            //Hide Coin:
            $('.mini-coin.coin-12274.coin-id-'+e_existing_id).fadeOut();

        } else {
            //We had an error:
            alert(data.message);
        }

    });

    return true;

}



function x_message_save() {

    //Prepare data to be modified for this idea:
    var modify_data = {
        x__id: $('#modal13571 .modal_x__id').val(),
        x__message: $('#x__message').val(),
    };

    //Show spinner:
    $('#modal13571 .save_results').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_shuffle_message(12695) +  '').hide().fadeIn();


    $.post("/x/x_message_save", modify_data, function (data) {

        if (data.status) {

            $('#modal13571').modal('hide');

            //Yes, update the ideas:
            $(".x__message_" + modify_data['x__id']).html(data.x__message);

            //Did the content get modified? (Likely for a domain URL):
            if(!(data.x__message_final==modify_data['x__message'])){
                $('#x__message').val(data.x__message_final).hide().fadeIn('slow');
            }

        } else {

            //Ooops there was an error!
            $('#modal13571 .save_results').html('<span class="zq6255 css__title"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</span>').hide().fadeIn();

        }

    });

}


function click_has_class(target_el, target_class){
    //Aggregare parents:
    var class_found = false;
    if(target_el.is(target_class)){
        class_found = true;
    }
    if(!class_found){
        target_el.parentsUntil( "body" ).each(function () {
            if(!class_found && $(this).is(target_class)){
                class_found = true;
            }
        });
    }
    return class_found;
}

function e_remove(x__id, x__type) {

    var r = confirm("Remove this source?");
    if (r == true) {
        $.post("/e/e_remove", {

            x__id: x__id,

        }, function (data) {
            if (data.status) {

                i_note_counter(x__type, -1);
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


function x_type_preview() {

    //Shows the transaction type based on the transaction message
    $('#x__type_preview').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Fetch Idea Data to load modify widget:
    $.post("/x/x_type_preview", {
        x__message: $('#x__message').val(),
        x__id: $('#modal13571 .modal_x__id').val(),
    }, function (data) {

        if(data.status){

            $('#x__type_preview').html(data.x__type_preview);
            $('#x__message_preview').html(data.x__message_preview);
            lazy_load();
            $('[data-toggle="tooltip"]').tooltip();

        } else {

            //Show Error:
            $('#x__type_preview').html('<b class="zq6255">' + data.message+'</b>');

        }

    });

}


function x_suggestion(){

    //Make sure all inputs are completed:
    var sugg_type = parseInt($('input[name="sugg_type"]:checked').val());
    var sugg_note = $("#sugg_note").val();

    if(sugg_type < 1){
        alert('You must select suggestion type to continue.');
        $("#sugg_type").focus();
        return false;
    } else if(sugg_note.length < 1){
        alert('You must write suggestion note to continue.');
        $("#sugg_note").focus();
        return false;
    } else if(js_pl_id < 1){
        alert('You must be logged-in to continue.');
        return false;
    }

    //All good, submit:
    $.post("/x/x_suggestion", {
        js_pl_id:js_pl_id,
        sugg_type:sugg_type,
        sugg_note:sugg_note,
        sugg_url:window.location.href,
    }, function (data) {

        //Inform Member:
        alert('You ROCK! We will review your suggestion and get back to you if necessary.');

        //Close Modal:
        $('#modal14393').modal('hide');
        $("#sugg_note").val('');
        $("#sugg_type").val('0');

    });
}


//For the drag and drop file uploader:
var isAdvancedUpload = function () {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

//Main navigation
var search_on = false;
function toggle_search(){

    $('.left_nav').addClass('hidden');
    $('.search_icon').toggleClass('hidden');

    if(search_on){

        //Search OFF
        search_on = false; //Reverse
        $('.top_nav, #container_content').removeClass('hidden');
        $('.search_nav, #container_search').addClass('hidden');

    } else {

        //Search ON
        search_on = true; //Reverse
        $('.top_nav, #container_content').addClass('hidden');
        $('.search_nav, #container_search').removeClass('hidden');
        $("#container_search .row").html(''); //Reset results view
        $('#top_search').focus();

        setTimeout(function () {
            //One more time to make sure it also works in mobile:
            $('#top_search').focus();
        }, 144);

    }
}


function x_save(i__id){

    $('.toggle_saved').toggleClass('hidden');
    var x__id = parseInt($('.save_controller').attr('current_x_id'));

    if(!x__id){
        //Add:
        $.post("/x/x_save", {
            i__id:i__id,
            top_i__id:$('#top_i__id').val(),
        }, function (data) {
            if (!data.status) {
                alert(data.message);
                $('.toggle_saved').toggleClass('hidden');
            } else {
                //Update new link ID:
                $('.save_controller').attr('current_x_id', data.x__id);
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
                $('.toggle_saved').toggleClass('hidden');
            } else {
                //Update new link ID:
                $('.save_controller').attr('current_x_id', 0);
            }
        });
    }
}



function validURL(str) {
    return str && str.length && str.substring(0, 4)=='http';
}


function add_to_list(sort_list_id, sort_handler, html_content) {

    //See if we previously have a list in place?
    if ($("#" + sort_list_id + " " + sort_handler).length > 0) {
        if(0){
            //Add to start (disabled)
            $("#" + sort_list_id + " " + sort_handler + ":first").before(html_content);
        } else {
            $("#" + sort_list_id + " " + sort_handler + ":last").after(html_content);
        }
    } else {
        //Raw list, add before input filed:
        $("#" + sort_list_id).prepend(html_content);
    }

    lazy_load();
    init_remove();

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
            } else if (this.selectionStart || this.selectionStart == '0') {
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




function images_modal(x__type){
    x_create({
        x__source: js_pl_id,
        x__type: 14576, //MODAL VIEWED
        x__up: 14073,
        x__right: current_id(),
    });
    $('#modal14073').modal('show');
    $('#modal_x__type').val(x__type);
    $('.new_images').html('');
    $('.images_query').val('');
    setTimeout(function () {
        $('.images_query').focus();
    }, 610);
}

Math.fmod = function (a,b) { return Number((a - (Math.floor(a / b) * b)).toPrecision(8)); };


var current_q = '';
function images_search(q){
    if(q==current_q){
        return false;
    }
    current_q = q;
    $('.new_images').html('');
    images_api_getasync(25986, q, tenor_search_box);
    images_api_getasync(18139, q, unsplash_search_box);
}

function images_add(image_url, image_title){

    var x__type = $('#modal_x__type').val();
    var current_value = $('.input_note_' + x__type).val();
    $('#modal14073').modal('hide');
    $('.input_note_' + x__type).val(( current_value.length ? current_value+"\n\n" : '' ) + image_url + '?e__title='+encodeURI(image_title));

}


function x_set_start_text(){
    $('.x_set_class_text').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            x_set_text(this);
            e.preventDefault();
        }
    }).change(function() {
        x_set_text(this);
    });
}

function update_text_name(cache_e__id, e__id, e__title){
    if(cache_e__id==6197){
        e__title = e__title;
    }
    $(".text__"+cache_e__id+"_" + e__id).val(e__title).text(e__title).attr('old-value', e__title);
}

function x_set_text(this_handler){

    var modify_data = {
        s__id: parseInt($(this_handler).attr('s__id')),
        cache_e__id: parseInt($(this_handler).attr('cache_e__id')),
        field_value: $(this_handler).val().trim()
    };

    //See if anything changes:
    if( $(this_handler).attr('old-value') == modify_data['field_value'] ){
        //Nothing changed:
        return false;
    }

    //Grey background to indicate saving...
    var handler = '.text__'+modify_data['cache_e__id']+'_'+modify_data['s__id'];
    $(handler).addClass('dynamic_saving').prop("disabled", true);

    $.post("/x/x_set_text", modify_data, function (data) {

        if (!data.status) {

            //Reset to original value:
            $(handler).val(data.original_val);

            //Show error:
            alert(data.message);

        } else {

            //If Updating Text, Updating Corresponding Fields:
            update_text_name(modify_data['cache_e__id'], modify_data['s__id'], modify_data['field_value']);

        }

        setTimeout(function () {
            //Restore background:
            $(handler).removeClass('dynamic_saving').prop("disabled", false);
        }, 233);

    });
}




/*
*
* IDEA NOTES
*
* */
function i_note_activate(){
    //Loop through all new idea inboxes:
    $(".new-note").each(function () {

        var x__type = parseInt($(this).attr('x__type'));

        //Initiate @ search for all idea text areas:
        i_note_e_search($(this));

        set_autosize($(this));

        //Activate sorting:
        i_notes_sort_load(x__type);

        var showFiles = function (files) {
            if(typeof files[0] !== 'undefined'){
                $('.box' + x__type).find('label').text(files.length > 1 ? ($('.box' + x__type).find('input[type="file"]').attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
            }
        };

        $('.box' + x__type).find('input[type="file"]').on('drop', function (e) {
            droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
            showFiles(droppedFiles);
        });

        $('.box' + x__type).find('input[type="file"]').on('change', function (e) {
            showFiles(e.target.files);
        });

        //Watch for message creation:
        $('.regular_editor').keydown(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (e.ctrlKey && code== 13) {
                i_note_add_text($(this).attr('x__type'));
            }
        });

        //Watchout for file uplods:
        $('.box' + x__type).find('input[type="file"]').change(function () {
            i_note_add_file(droppedFiles, 'file', x__type);
        });


        //Should we auto start?
        if (isAdvancedUpload) {

            var droppedFiles = false;

            $('.box' + x__type).on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $('.power-editor-' + x__type+', .tab-data-'+ x__type).addClass('dynamic_saving');
            })
                /*
            .on('dragover dragenter', function () {
                $('.power-editor-' + x__type+', .tab-data-'+ x__type).addClass('dynamic_saving');
            })
            .on('dragleave dragend drop', function () {
                $('.power-editor-' + x__type+', .tab-data-'+ x__type).removeClass('dynamic_saving');
            })
                */
            .on('drop', function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                e.preventDefault();
                i_note_add_file(droppedFiles, 'drop', x__type);
                $('.power-editor-' + x__type+', .tab-data-'+ x__type).removeClass('dynamic_saving');
            });
        }

    });
}

function i_note_counter(x__type, adjustment_count){
    var current_count = parseInt( $('.xtypecounter'+x__type).text().length ? $('.xtypecounter'+x__type).text() : 0 );
    var new_count = current_count + adjustment_count;
    $('.xtypecounter'+x__type).text(new_count);
}

function i_note_count_new(x__type) {

    //Update count:
    var len = $('.input_note_' + x__type).val().length;
    if (len > js_e___6404[4485]['m__message']) {
        $('#charNum' + x__type).addClass('overload').text(len);
    } else {
        $('#charNum' + x__type).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_e___6404[4485]['m__message'] * js_e___6404[12088]['m__message'] )){
        $('#ideaNoteNewCount' + x__type).removeClass('hidden');
    } else {
        $('#ideaNoteNewCount' + x__type).addClass('hidden');
    }

}

function count_13574(x__id) {
    //See if this is a valid text message editing:
    if (!($('#charEditingNum' + x__id).length)) {
        return false;
    }
    //Update count:
    var len = $('#message_body_' + x__id).val().length;
    if (len > js_e___6404[4485]['m__message']) {
        $('#charEditingNum' + x__id).addClass('overload').text(len);
    } else {
        $('#charEditingNum' + x__id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_e___6404[4485]['m__message'] * js_e___6404[12088]['m__message'] )){
        $('#NoteCounter' + x__id).removeClass('hidden');
    } else {
        $('#NoteCounter' + x__id).addClass('hidden');
    }
}




function i_note_e_search(obj) {

    if(parseInt(js_e___6404[12678]['m__message'])){
        obj.textcomplete([
            {
                match: /(^|\s)@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    algolia_index.search(q, {
                        hitsPerPage: 8,
                        filters: 's__type=12274',
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
                    setTimeout(function () {
                        autosize.update(obj);
                    }, 233);
                    return ' @' + suggestion.s__id + ' ';
                }
            },
        ]);
    }
}

function i_note_sort_apply(x__type) {

    var new_x__spectrums = [];
    var sort_rank = 0;
    var this_x__id = 0;

    $(".msg_e_type_" + x__type).each(function () {
        this_x__id = parseInt($(this).attr('x__id'));
        if (this_x__id > 0) {
            sort_rank++;
            new_x__spectrums[sort_rank] = this_x__id;
        }
    });

    //Update backend if any:
    if(sort_rank > 0){
        $.post("/i/i_note_sort", {new_x__spectrums: new_x__spectrums}, function (data) {
            //Only show message if there was an error:
            if (!data.status) {
                //Show error:
                alert(data.message);
            }
        });
    }
}

function i_notes_sort_load(x__type) {


    var sotrable_div = document.getElementById("i_notes_list_" + x__type);
    if(!sotrable_div){
        return false;
    }
    var inner_content = null;

    var sort_msg = Sortable.create( sotrable_div , {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        handle: ".i_note_sorting", // Restricts sort start click/touch to the specified element
        draggable: ".note_sortable", // Specifies which items inside the element should be sortable
        onUpdate: function (evt/**Event*/) {
            //Apply new sort:
            i_note_sort_apply(x__type);
        },
        //The next two functions resolve a Bug with sorting iframes like YouTube embeds while also making the UI more informative
        onChoose: function (evt/**Event*/) {
            //See if this is a YouTube or Vimeo iFrame that needs to be temporarily deleted:
            var x__id = $(evt.item).attr('x__id');
            if ($('#ul-nav-' + x__id).find('.video-sorting').length !== 0) {
                inner_content = $('#msgbody_' + x__id).html();
                $('#msgbody_' + x__id).css('height', $('#msgbody_' + x__id).height()).html('SORT VIDEO UP/DOWN');
            } else {
                inner_content = null;
            }
        },
        onEnd: function (evt/**Event*/) {
            if (inner_content) {
                var x__id = $(evt.item).attr('x__id');
                $('#msgbody_' + x__id).html(inner_content);
            }
        }
    });

}

function load_i_note_editor(x__id) {

    //Start editing:
    $("#ul-nav-" + x__id).addClass('in-editing');
    $("#ul-nav-" + x__id + " .edit-off").addClass('hidden');
    $("#ul-nav-" + x__id + " .edit-on").removeClass('hidden');
    $("#ul-nav-" + x__id + ">div").css('width', '100%');

    //Set focus to end of text:
    var textinput = $("#ul-nav-" + x__id + " textarea");
    var data = textinput.val();
    textinput.focus().val('').val(data);
    autosize.update(textinput);


    //Initiate search:
    i_note_e_search(textinput);

    //Try to initiate the editor, which only applies to text messages:
    count_13574(x__id);

}

function cancel_13574(x__id) {
    //Revert editing:
    $("#ul-nav-" + x__id).removeClass('in-editing');
    $("#ul-nav-" + x__id + " .edit-off").removeClass('hidden');
    $("#ul-nav-" + x__id + " .edit-on").addClass('hidden');
    $("#ul-nav-" + x__id + ">div").css('width', 'inherit');
}

function i_note_update_text(x__id, x__type) {

    //Revert View:
    cancel_13574(x__id);

    //Clear Message:
    $("#ul-nav-" + x__id + " .edit-updates").html('');

    var modify_data = {
        x__id: parseInt(x__id),
        i__id: parseInt(current_id()),
        x__message: $("#ul-nav-" + x__id + " textarea").val(),
    };

    //Update message:
    $.post("/i/i_note_update_text", modify_data, function (data) {

        if (data.status) {

            //Update text message:
            $("#ul-nav-" + x__id + " .text_message").html(data.message);

            lazy_load();

        } else {

            //ERROR
            $("#ul-nav-" + x__id + " .edit-updates").html('<b class="zq6255 css__title"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>');

        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

    });

}


function i_remove_note(x__id, x__type){

    var r = confirm("Remove this note?");
    if (r == true) {
        //REMOVE NOTE
        $.post("/i/i_remove_note", { x__id: parseInt(x__id) }, function (data) {
            if (data.status) {

                i_note_counter(x__type, -1);

                $("#ul-nav-" + x__id).fadeOut();

                setTimeout(function () {
                    $("#ul-nav-" + x__id).remove();
                }, 610);

            } else {

                alert(data.message);

            }
        });
    }
}


function i_note_add_file(droppedFiles, uploadType, x__type) {

    //Prevent multiple concurrent uploads:
    if ($('.box' + x__type).hasClass('dynamic_saving') || !isAdvancedUpload) {
        return false;
    }

    var ajaxData = new FormData($('.box' + x__type).get(0));
    if (droppedFiles) {
        $.each(droppedFiles, function (i, file) {
            var thename = $('.box' + x__type).find('input[type="file"]').attr('name');
            if (typeof thename == typeof undefined || thename == false) {
                var thename = 'drop';
            }
            ajaxData.append(uploadType, file);
        });
    }

    ajaxData.append('upload_type', uploadType);
    ajaxData.append('i__id', current_id());
    ajaxData.append('x__type', x__type);

    $.ajax({
        url: '/i/i_note_add_file',
        type: $('.box' + x__type).attr('method'),
        data: ajaxData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        complete: function () {
            $('.box' + x__type).removeClass('dynamic_saving');
        },
        success: function (data) {

            if(!data.status){

                alert('ERROR: '+data.message);

            } else {

                if(js_n___14311.includes(x__type)){
                    //Power Editor:
                    var current_value = $('.input_note_' + x__type).val();
                    $('.input_note_' + x__type).val(( current_value.length ? current_value+"\n\n" : '' ) + data.new_source);
                } else {
                    //Regular Editor:
                    i_note_counter(x__type, +1);
                }

                //Adjust icon again:
                $('.file_label_' + x__type).html('<span class="icon-block">'+js_e___11035[13572]['m__cover']+'</span>');
            }

            if(js_n___14311.includes(x__type)){
                save_message_27963();
            } else {
                i_note_end_adding(data, x__type);
            }

        },
        error: function (data) {
            var result = [];
            result.status = 0;
            result.message = data.responseText;
            i_note_end_adding(result, x__type);
        }
    });

}


var currentlu_adding = false;
function i_note_add_text(x__type) {

    if(currentlu_adding){
        return false;
    }
    currentlu_adding = true;

    //Update backend:
    $.post("/i/i_note_add_text", {

        i__id: current_id(), //Synonymous
        x__message: $('.input_note_' + x__type).val(),
        x__type: x__type,

    }, function (data) {

        //Raw Inputs Fields if success:
        if (data.status) {

            //Reset input field:
            $('.input_note_' + x__type).val("");
            set_autosize($('.input_note_' + x__type));

            i_note_count_new(x__type);
            i_note_counter(x__type, +1);

        }

        //Unlock field:
        i_note_end_adding(data, x__type);

        //Done adding:
        currentlu_adding = false;

    });

}

function append_value(theobject, thevalue){
    var current_value = theobject.val();
    theobject.val( current_value + thevalue ).focus();
}

function set_autosize(theobject){
    autosize(theobject);
    setTimeout(function () {
        autosize.update(theobject);
    }, 13);
}




function x_sort_load(x__type){

    if(!js_n___4603.includes(x__type)){
        console.log(x__type+' is not sortable');
        return false;
    }

    var theobject = document.getElementById("list-in-" + x__type);
    if (!theobject) {
        //due to duplicate ideas belonging in this idea:
        console.log(x__type+' failed to find sortable object');
        return false;
    }

    //Load sorter:
    var sort = Sortable.create(theobject, {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: "#list-in-"+x__type+" .cover_sort", // Specifies which items inside the element should be sortable
        handle: "#list-in-"+x__type+" .x_sort", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {

            var sort_rank = 0;
            var new_x_order = [];
            $("#list-in-"+x__type+" .cover_sort").each(function () {
                var x__id = parseInt($(this).attr('x__id'));
                if(x__id > 0){
                    sort_rank++;
                    new_x_order[sort_rank] = x__id;
                }
            });

            //Update order:
            if(sort_rank > 0){
                $.post("/x/x_sort_load", { new_x_order:new_x_order, x__type:x__type }, function (data) {
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






function account_toggle_all(is_enabled){
    //Turn all superpowers on/off:
    $(".btn-superpower").each(function () {
        if ((is_enabled && !($(this).hasClass('active'))) || (!is_enabled && $(this).hasClass('active'))) {
            e_toggle_superpower(parseInt($(this).attr('en-id')));
        }
    });
}



function e_toggle_superpower(superpower_id){

    superpower_id = parseInt(superpower_id);

    var notify_el = '.superpower-frame-'+superpower_id+' .main-icon';
    var initial_icon = $(notify_el).html();
    $(notify_el).html('<i class="far fa-yin-yang fa-spin"></i>');

    //Save session variable to save the state of advance setting:
    $.post("/e/e_toggle_superpower/"+superpower_id, {}, function (data) {

        //Change top menu icon:
        $(notify_el).html(initial_icon);

        if(!data.status){

            alert(data.message);

        } else {

            //Toggle UI elements:
            $('.superpower-'+superpower_id).toggleClass('hidden');

            //Change top menu icon:
            $('.superpower-frame-'+superpower_id).toggleClass('active');

            //TOGGLE:
            var index = js_session_superpowers_activated.indexOf(superpower_id);
            if (index > -1) {
                //Delete it:
                js_session_superpowers_activated.splice(index, 1);
            } else {
                //Not there, add it:
                js_session_superpowers_activated.push(superpower_id);
            }
        }
    });

}




var current_focus = 0;

function remove_ui_class(item, index) {
    var the_class = 'custom_ui_'+current_focus+'_'+item;
    $('body').removeClass(the_class);
}

function e_radio(focus__id, selected_e__id, enable_mulitiselect){

    var was_previously_selected = ( $('.radio-'+focus__id+' .item-'+selected_e__id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_previously_selected){
        //Nothing to do here:
        return false;
    }

    //Updating Font?
    if(js_n___13890.includes(focus__id)){
        current_focus = focus__id;
        $('body').removeClass('custom_ui_'+focus__id+'_');
        window['js_n___'+focus__id].forEach(remove_ui_class); //Removes all Classes
        $('body').addClass('custom_ui_'+focus__id+'_'+selected_e__id);
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+focus__id+' .item-'+selected_e__id+' .change-results';
    var initial_icon = $(notify_el).html();
    $(notify_el).html('<i class="far fa-yin-yang fa-spin"></i>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+focus__id+' .list-group-item').removeClass('active');
    }

    //Enable currently selected:
    if(enable_mulitiselect && was_previously_selected){
        $('.radio-'+focus__id+' .item-'+selected_e__id).removeClass('active');
    } else {
        $('.radio-'+focus__id+' .item-'+selected_e__id).addClass('active');
    }

    $.post("/e/e_radio", {
        focus__id: focus__id,
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


function e_email(){

    //Show spinner:
    $('.save_email').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_shuffle_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_email", {
        e_email: $('#e_email').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_email').html('<b class="zq6255 css__title"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_email').html(js_e___11035[14424]['m__cover'] + ' ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_email').html('');
            }, 1597);

        }
    });

}


function e_password(){

    //Show spinner:
    $('.save_password').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_shuffle_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_password", {
        input_password: $('#input_password').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_password').html('<b class="zq6255 css__title"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_password').html(js_e___11035[14424]['m__cover'] + ' ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_password').html('');
            }, 1597);

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
    * element_id Must be listed as children of:
    *
    * MEMORY CACHE @4527
    * JS MEMORY CACHE @11054
    *
    *
    * */

    var current_selected = parseInt($('.dropi_'+element_id+'_'+o__id+'_'+x__id+'.active').attr('new-en-id'));
    new_e__id = parseInt(new_e__id);
    if(current_selected == new_e__id){
        //Nothing changed:
        return false;
    }



    //Deleting Anything?
    var migrate_i__id = 0;
    if(element_id==4737 && !(new_e__id in js_e___7356)){

        //Deleting Idea:
        var confirm_removal = prompt("Are you sure you want to delete this idea?\nEnter 0 to unlink OR enter Idea ID to migrate links.", "0");
        if (!isNormalInteger(confirm_removal)) {
            return false;
        }
        migrate_i__id = confirm_removal;

    } else if(element_id==6177 && !(new_e__id in js_e___7358)){

        //Deleting Source:
        var confirm_removal = prompt("Are you sure you want to delete this source?\nEnter 0 to unlink OR enter source ID to migrate links.", "0");
        if (!isNormalInteger(confirm_removal)) {
            return false;
        }
        migrate_i__id = confirm_removal;

    }




    //Show Loading...
    var data_object = eval('js_e___'+element_id);
    if(!data_object[new_e__id]){
        alert('Invalid element ID');
        return false;
    }
    $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="css__title">'+ ( show_full_name ? 'SAVING...' : '' ) +'</b>');

    $.post("/x/update_dropdown", {
        focus__id:current_id(),
        o__id: o__id,
        element_id: element_id,
        new_e__id: new_e__id,
        migrate_i__id: migrate_i__id,
        x__id: x__id
    }, function (data) {
        if (data.status) {

            //Update on page:
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block">'+data_object[new_e__id]['m__cover']+'</span>' + ( show_full_name ? data_object[new_e__id]['m__title'] : '' ));

            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .dropi_' + element_id +'_'+o__id+ '_' + x__id).removeClass('active');
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .optiond_' + new_e__id+'_'+o__id+ '_' + x__id).addClass('active');

            $('.dropd_'+element_id+'_'+o__id+'_'+x__id).attr('selected-val' , new_e__id);

            if( data.deletion_redirect && data.deletion_redirect.length > 0 ){
                //Go to main idea page:
                window.location = data.deletion_redirect;
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

        } else {

            //Reset to default:
            var current_class = $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn span').attr('class');
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="'+current_class+'">'+data_object[current_selected]['m__cover']+'</span>' + ( show_full_name ? data_object[current_selected]['m__title'] : '' ));

            //Show error:
            alert(data.message);

        }
    });
}


var nav_toggeled = false;
function toggle_left_menu() {

    if($('.sidebar').hasClass('hidden')){
        if(!nav_toggeled){
            nav_toggeled = true;
            $.post("/e/toggle_left_menu", {}, function (data) {
                $('.sidebar').html(data);
            });
        }
        $('.sidebar').removeClass('hidden');
    } else {
        $('.sidebar').addClass('hidden');
    }
}







var message_saving = false; //Prevent double saving
function save_message_27963(){

    if(message_saving){
        return false;
    }

    message_saving = true;
    var x__type = 4231;
    var i__id = $('#modal_i__id').val();
    var input_textarea = '.input_note_'+x__type;

    $.post("/i/save_message_27963", {
        i__id:i__id,
        field_value: $('.input_note_4231').val().trim()
    }, function (data) {

        if (!data.status) {

            //Show Errors:
            $(".note_error_"+x__type).html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Message not saved because:<br />'+data.message);

        } else {

            $('.top_message_box').addClass('hidden');

            //Reset errors:
            $(".note_error_"+x__type).html('');

            //Update DISCOVERY:
            $('.messages_4231_'+i__id).html(data.message);

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Load Images:
            lazy_load();

            message_saving = false;

        }
    });
}



function e_reset_discoveries(e__id){
    //Confirm First:
    var r = confirm("DANGER WARNING!!! You are about to delete your ENTIRE discovery history. This action cannot be undone and you will lose all your discovery coins.");
    if (r == true) {
        $('.e_reset_discoveries').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="css__title">REMOVING ALL...</b>');

        //Redirect:
        window.location = '/x/e_reset_discoveries/'+e__id;
    } else {
        return false;
    }
}




function e_x_form_lock(){
    $('#x__message').prop("disabled", true);

    $('.btn-save').addClass('grey').attr('href', '#').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Uploading');

}

function e_x_form_unlock(result){

    //What was the result?
    if (!result.status) {
        alert(result.message);
    }

    //Unlock either way:
    $('#x__message').prop("disabled", false);

    $('.btn-save').removeClass('grey').attr('href', 'javascript:x_message_save();').html('Save');

    //Tooltips:
    $('[data-toggle="tooltip"]').tooltip();

    //Replace the upload form to reset:
    upload_control.replaceWith( upload_control = upload_control.clone( true ) );
}


function e_sort_save(x__type) {

    var new_x__spectrums = [];
    var sort_rank = 0;

    $("#list-in-"+x__type+" .coinface-12274").each(function () {
        //Fetch variables for this idea:
        var e__id = parseInt($(this).attr('e__id'));
        var x__id = parseInt($(this).attr('x__id'));

        sort_rank++;

        //Store in DB:
        new_x__spectrums[sort_rank] = x__id;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0) {
        //Update backend:
        $.post("/e/e_sort_save", {e__id: current_id(), x__type:x__type, new_x__spectrums: new_x__spectrums}, function (data) {
            //Update UI to confirm with member:
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
            e__id: current_id()
        }, function (data) {

            if (!data.status) {

                //Ooops there was an error!
                alert(data.message);

            } else {

                //Refresh page:
                window.location = '/@' + current_id();

            }
        });
    }
}



function e_e_only_search_7551() {

    if(!js_pl_id){
        return false;
    }

    $(".e-only-7551").each(function () {
        var element_focus = ".e-i-"+$(this).attr('x__type');

        var base_creator_url = '/e/create/'+current_id()+'/?content_title=';

        $(element_focus + ' .add-input').keypress(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if ((code == 13) || (e.ctrlKey && code == 13)) {
                return e_add_only_7551($(this).attr('x__type'), 0);
            }
        });


        if(parseInt(js_e___6404[12678]['m__message'])){

            $(element_focus + ' .add-input').keyup(function () {

                //Clear if no input:
                if(!$(this).val().length){
                    $('.e-i-'+$(this).attr('x__type')+' .algolia_pad_search').html('');
                }

            }).autocomplete({hint: false, autoselect: false, minLength: 1}, [{

                source: function (q, cb) {

                    $('.e-i-'+$(this).attr('x__type')+' .algolia_pad_search').html('');

                    algolia_index.search(q, {
                        filters: 's__type=12274',
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
                        $('.e-i-'+$(this).attr('x__type')+' .algolia_pad_search').append(view_s_js_coin(7551, suggestion, $(this).attr('x__type')));
                        return false;
                    },
                    header: function (data) {
                        return false;
                    },
                    empty: function (data) {
                        return false;
                    },
                }
            }]);
        }
    });
}



function go_next(){

    //Click Requirement?
    if(parseInt($('#must_click').val()) && $(".should-click")[0] && !parseInt($('#click_count').val())){
        alert('Click on the link ['+$('.should-click:first').text()+'] before going next.');
        return false;
    }

    var go_next_url = $('#go_next_url').val();

    //Attempts to go next if no submissions:
    if(focus_i__type==6683) {

        //TEXT RESPONSE:
        return x_reply(go_next_url);

    } else if (js_n___7712.includes(focus_i__type) && $('.list-answers .answer-item').length){

        //SELECT ONE/SOME
        return x_select(go_next_url);

    } else if (focus_i__type==7637 && !($('.file_saving_result').html().length) ) {

        //Must upload file first:
        alert('You must upload file before going next.');

    } else if(go_next_url && go_next_url.length > 0){

        //Go Next:
        $('.go-next').html(( js_pl_id > 0 ? '<i class="fas fa-check-circle"></i>' : '<i class="far fa-yin-yang fa-spin"></i>' ));
        window.location = go_next_url;

    }
}

var is_toggling = false;
function toggle_answer(i__id){

    if(is_toggling){
        return false;
    }
    is_toggling = true;

    //Allow answer to be saved/updated:
    var i__type = parseInt($('.list-answers').attr('i__type'));

    //Clear all if single selection:
    var is_single_selection = (i__type == 6684);
    if(is_single_selection){
        //Single Selection, clear all:
        $('.answer-item').removeClass('coinType12273');
    }

    //Is setected?
    if($('.x_select_'+i__id).hasClass('coinType12273')){

        //Previously Selected, delete selection:
        if(i__type == 7231 || i__type == 14861){
            //Multi Selection
            $('.x_select_'+i__id).removeClass('coinType12273');
        }

        is_toggling = false;

    } else {

        //Not selected, select now:
        $('.x_select_'+i__id).addClass('coinType12273');

        if(is_single_selection){
            //Auto submit answer:
            go_next();
        } else {
            //Flash call to action:
            $(".main-next").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            is_toggling = false;
        }
    }

}


function x_upload(droppedFiles, uploadType) {

    //Prevent multiple concurrent uploads:
    if ($('.boxUpload').hasClass('dynamic_saving')) {
        return false;
    }

    $('.file_saving_result').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="css__title">UPLOADING...</span>');

    if (isAdvancedUpload) {

        var ajaxData = new FormData($('.boxUpload').get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $('.boxUpload').find('input[type="file"]').attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('i__id', current_id());
        ajaxData.append('top_i__id', $('#top_i__id').val());

        $.ajax({
            url: '/x/x_upload',
            type: $('.boxUpload').attr('method'),
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.boxUpload').removeClass('dynamic_saving');
            },
            success: function (data) {
                //Render new file:
                $('.file_saving_result').html(data.message);
                $('.go_next_upload').removeClass('hidden');
                lazy_load();
            },
            error: function (data) {
                //Show Error:
                $('.file_saving_result').html(data.responseText);
            }
        });
    } else {
        // ajax for legacy browsers
    }

}


function x_reply_save(go_next_url){
    $.post("/x/x_reply", {
        i__id:current_id(),
        top_i__id:$('#top_i__id').val(),
        x_reply:$('#x_reply').val(),
    }, function (data) {
        if (data.status) {
            //Go to redirect message:
            $('.go-next').html('<i class="fas fa-check-circle"></i>');
            window.location = go_next_url;
        } else {
            //Show error:
            alert(data.message);
        }
    });
}

function x_reply(go_next_url){

    x_reply_save(go_next_url);

    /*

    if($('#x_reply').hasClass('phone_verify_4783') && js_pl_id==1){

        console.log('Phone verification initiated');

        const data = new URLSearchParams();
        data.append("phone", $('#x_reply').val());

        fetch("https://intl-tel-input-8586.twil.io/lookup", {
            method: "POST",
            body: data,
        })
            .then((response) => response.json())
            .then((json) => {
                if (!json.success) {
                    console.log(json.error);
                    alert('Error: Phone number ['+$('#x_reply').val()+'] is not valid, please try again. Example: 7788826962');
                    return false;
                } else {
                    console.log(response);
                    //x_reply_save(go_next_url);
                }
            })
            .catch((err) => {
                alert("Something went wrong: ${err}");
                return false;
            });

    } else {
        x_reply_save(go_next_url);
    }
    */

}

function x_select(go_next_url){

    //Check
    var selection_i__id = [];
    $(".answer-item").each(function () {
        var selection_i__id_this = parseInt($(this).attr('selection_i__id'));
        if ($('.x_select_'+selection_i__id_this).hasClass('coinType12273')) {
            selection_i__id.push(selection_i__id_this);
        }
    });


    //Show Loading:
    $.post("/x/x_select", {
        focus_i__type:focus_i__type,
        focus__id:current_id(),
        top_i__id:$('#top_i__id').val(),
        selection_i__id:selection_i__id,
    }, function (data) {
        if (data.status) {
            //Go to redirect message:
            $('.go-next').html('<i class="fas fa-check-circle"></i>');
            window.location = go_next_url;
        } else {
            //Show error:
            alert(data.message);
        }
    });
}

