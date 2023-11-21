

// url Async requesting function
function images_api_getasync(api_id, query, callback) {
    // create the request object
    var xmlHttp = new XMLHttpRequest();

    // set the state change callback to capture when the response comes in
    xmlHttp.onreadystatechange = function()
    {
        if (xmlHttp.readyState==4 && xmlHttp.status==200)
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


function mass_apply_preview(apply_id, card__id){

    //Select first:
    var first_id = $('#modal'+apply_id+' .mass_action_toggle option:first').val();
    $('.mass_action_item').addClass('hidden');
    $('.mass_id_' + first_id ).removeClass('hidden');
    $('#modal'+apply_id+' .mass_action_toggle').val(first_id);
    $('#modal'+apply_id+' input[name="card__id"]').val(card__id);
    $('#modal'+apply_id).modal('show');

    //Load Ppeview:
    $('#modal'+apply_id+' .mass_apply_preview').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading...');
    $.post("/x/mass_apply_preview", {
        apply_id: apply_id,
        card__id: card__id
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

            $(this).val('@' + suggestion.s__id + ' ' + suggestion.s__title);

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
                return '@' + suggestion.s__id + ' ' + suggestion.s__title;
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

            $(this).val('#' + suggestion.s__id + ' ' + suggestion.s__title);

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
                return '#' + suggestion.s__id + ' ' + suggestion.s__title;
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
    return '<span class="icon-block">'+ view_cover_js(suggestion.s__cover) +'</span><span class="main__title">' + view_s__title(suggestion) + '</span><span class="grey">&nbsp;' + ( suggestion.s__type==12273 ? '/' : '@' ) + suggestion.s__id + '</span>';
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
        return '<div class="card_cover contrast_bg mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="'+suggestion.s__url+'" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="'+suggestion.s__url+'" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==26012){
        //Link Idea
        return '<div class="card_cover contrast_bg mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="i__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="i__add('+action_id+', '+suggestion.s__id+')" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
    } else if(x__type==26013){
        //Link Source
        return '<div class="card_cover contrast_bg mini-cover coin-'+suggestion.s__type+' coin-id-'+suggestion.s__id+' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="black-background-obs cover-link coinType'+suggestion.s__type+'" '+background_image+'><div class="cover-btn">'+icon_image+'</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e__add('+action_id+', '+suggestion.s__id+')" class="main__title">'+suggestion.s__title+'</a></div></div></div>';
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

        if (x__type==12211){
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

        if (x__type==12211){
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

    console.log(x__type+' PILL TOGGLED');

    focus_card = x__type;
    var x__down = 0;
    var x__right = 0;
    var focus_card = fetch_int_val('#focus_card');

    if(focus_card==12273){
        x__right = fetch_int_val('#focus_id');
    } else if (focus_card==12274){
        x__down = fetch_int_val('#focus_id');
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
        //js_redirect('#'+x__type);

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



function i_copy(i__id, do_template){
    //Go ahead and delete:
    $.post("/i/i_copy", {
        i__id:i__id,
        do_template:do_template
    }, function (data) {
        if(data.status){
            js_redirect('/~'+data.new_i__id);
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
            js_redirect('/@'+data.new_e__id);
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

    if(js_n___14686.includes(x__type)){
        $(e_loader).insertBefore(e_list);
    } else {
        $(e_loader).insertAfter(e_list);
    }
    $.post("/x/view_load_page", {
        focus_card: fetch_int_val('#focus_card'),
        focus_id: fetch_int_val('#focus_id'),
        x__type: x__type,
        current_page: current_page[x__type],
    }, function (data) {
        $('.load-more').remove();
        if(data.length){

            if(js_n___14686.includes(x__type)){
                //Upwards link:
                $(e_list).prepend(data);
                //$('html, body').scrollTop(top_element.offset().top - 55);
            } else {
                $(e_list).append(data);
            }
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
function init_remove(){
    $(".x_remove").click(function(event) {

        event.preventDefault();

        var i__id = $(this).attr('i__id');
        var x__id = $(this).attr('x__id');

        if(init_in_process==(x__id + i__id)){
            return false;
        }
        init_in_process = (x__id + i__id);
        var r = confirm("Remove idea #"+i__id+"?");
        if (r==true) {
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
    return false;
    return $.post("/x/x_create", add_fields);
}

function load_platform_stats(){
    $.post("/x/load_platform_stats", {}, function (data) {
        data.forEach(function(item) {
            if($(".card_count_"+item.sub_id+":first").text()!=item.sub_counter){
                $(".card_count_"+item.sub_id).text(item.sub_counter).hide().fadeIn().hide().fadeIn();
            }
        });
    });
}

function update__cover(new_cover){
    $('#card__cover').val( new_cover );
    update_cover_main(new_cover, '.demo_cover');
    //Save and close:
    source_edit_save();
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
        ajaxData.append('edit_e__id', $('#edit_e__id').val());

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


var idea_changed = false;
function edit_idea(i__id){

    $('#modal_i__id').val(i__id);
    $('.note_error_4736').html('');
    $('.input__4736').val($('.i__message_text_'+i__id).text()).focus();
    $('#modal31911').modal('show');
    setTimeout(function () {
        set_autosize($('.input__4736'));
    }, 237);

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

    $(".card_e_click, .card_click_i").unbind();

    $( ".card_e_click" ).click(function(e) {
        if($(e.target).closest('a, .btn, textarea, .x__message, .cover_wrapper12273, .ignore-click').length < 1){
            js_redirect('/@'+$(this).attr('e__id'));
        }
    });

    $('.card_click_i').click(function(e) {
        if($(e.target).closest('a, .btn, textarea, .x__message, .cover_wrapper12273, .ignore-click').length < 1){
            js_redirect('/~'+$(this).attr('i__id'));
        }
    });

}

var algolia_index = false;
$(document).ready(function () {

    $("#modal31911").on("hide.bs.modal", function (e) {
        var r = confirm("Your changes are unsaved! Close this window?");
        if (r==true) {
            console.log('continue...');
        } else {
            e.preventDefault();
            return false;
        }
    });

    //Watchout for file uplods:
    $('.coverUpload').find('input[type="file"]').change(function () {
        cover_upload(droppedFiles, 'file');
    });

    if(window.location.hash && 0) {
        var the_hash = window.location.hash.substring(1);
        if(!(the_hash==$('.nav-link.active').attr('x__type')) && isNormalInteger(the_hash)){
            toggle_pills(the_hash);
        }
    }

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



    if ($(".list-covers")[0]){
        //Update COINS every 3 seconds:
        $(function () {
            setInterval(load_platform_stats, js_e___6404[33292]['m__message']);
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
    $('#card__cover').keyup(function() {
        update_cover_main($(this).val(), '.demo_cover');
    });

    init_remove();

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
                    filters: ' _tags:z_14988 OR _tags:z_14038 OR _tags:z_14986 OR _tags:z_20425 OR _tags:z_20426 OR _tags:z_20427 OR _tags:has_image '+ search_and_filter,
                    hitsPerPage: js_e___6404[31113]['m__message'],
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
                    return '<div class="main__title"><i class="fas fa-exclamation-circle"></i> Nothing Found</div>';
                },
            }
        }]);

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
                            if(!superpower_js_12701){
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

function view_cover_js(cover_code){
    if(cover_code && cover_code.length){
        if(validURL(cover_code)){
            return '<img src="'+cover_code+'" />';
        } else if(cover_code.indexOf('fa-')>=0) {
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


function x_message_load(x__id) {

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
                $('#x__message').focus();
            }, 144);

        } else {

            alert(data.message);

        }
    });
}

function edit_source(e__id){

    $('#modal31912').modal('show');
    $('#search_cover').val('').focus();
    $("#upload_results, #icon_suggestions, #img_results_icons, #img_results_emojis, #img_results_tenor, #img_results_unsplash, #img_results_local").html('');
    $('#card__title, #card__cover').val('LOADING...');
    $('#modal31912 .black-background-obs').removeClass('isSelected').removeClass('coinType12274').addClass('coinType12274');

    $.post("/e/edit_source", {
        e__id: e__id
    }, function (data) {

        if (data.status) {

            $('#edit_e__id').val(e__id);
            $('#card__title').val(data.card__title);
            $('#card__cover').val(data.card__cover).focus();
            update_cover_main(data.card__cover, '.demo_cover');

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


function load_search(focus_card, x__type){
    if(x__type==12273 || x__type==11019 || (focus_card==12274 && x__type==6255)){
        i_load_search(x__type);
    } else if(x__type==12274 || x__type==11030 || (focus_card==12273 && x__type==6255)) {
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
                    $('.new-list-'+x__type+' .algolia_pad_search').prepend('<div class="card_cover contrast_bg mini-cover coin-12274 coin-id-0 col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="e__add('+x__type+', 0)" class="black-background-obs cover-link coinType12274"><div class="cover-btn"><i class="fas fa-circle-plus zq12273"></i></div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="e__add('+x__type+', 0)" class="main__title">'+data.query+'</a></div></div></div>');
                }
            },
            empty: function (data) {
                return '';
            }
        }
    }]);

}


function source_edit_save(){

    $.post("/e/source_edit_save", {
        edit_e__id: $('#edit_e__id').val(),
        card__title: $('#card__title').val(),
        card__cover: $('#card__cover').val()
    }, function (data) {

        if (data.status) {

            //Update Icon/Title on Page:
            $('#modal31912').modal('hide');

            //Update Title:
            update_text_name(6197, $('#edit_e__id').val(), $('#card__title').val());

            //Update Mini Icon:
            update_cover_mini($('#card__cover').val(), '.mini_6197_'+$('#edit_e__id').val());


            //Update Main Icons:
            update_cover_main($('#card__cover').val(), '.card___12274_'+$('#edit_e__id').val());

        } else {

            //Ooops there was an error!
            alert(data.message);

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
            if(auto_load){ // && js_n___14686.includes(x__type)
                window.scrollTo({
                    top: ($('.main_item').offset().top - 59),
                    behavior: 'instant',
                });
            }
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
            if(auto_load){ // && js_n___14686.includes(x__type)
                window.scrollTo({
                    top: ($('.main_item').offset().top - 59),
                    behavior: 'instant',
                });
            }
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
        x_type_preview_load();
        init_remove();
        x_set_start_text();
        set_autosize($('.x_set_class_text'));

        setTimeout(function () {
            load_covers();
            $('[data-toggle="tooltip"]').tooltip();
        }, 2584);


        $(function () {
            var $win = $(window);
            $win.scroll(function () {

                if(js_n___14686.includes(x__type)) {
                    //Upwards loading from top:
                    if(parseInt($win.scrollTop()) <= 377){
                        view_load_page(x__type);
                    }
                } else {
                    //Download loading from bottom:
                    if (parseInt($(document).height() - ($win.height() + $win.scrollTop())) <= 377) {
                        view_load_page(x__type);
                    }
                }

            });
        });

        if((x__type==12273 || x__type==11019) || (focus_card==12274 && x__type==6255)){
            setTimeout(function () {
                sort_i_load(x__type);
            }, 2584);
        } else if((x__type==12274 || x__type==11030) || (focus_card==12273 && x__type==6255)) {
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
    var sort_i_grabr = ".card_cover";
    var input_field = $('.new-list-'+x__type+' .add-input');
    var input__4736 = input_field.val();


    //We either need the idea name (to create a new idea) or the link_i__id>0 to create an IDEA transaction:
    if (!link_i__id && input__4736.length < 1) {
        alert('Missing Idea Title');
        input_field.focus();
        return false;
    }

    //Set processing status:
    input_field.addClass('dynamic_saving');
    add_to_list(x__type, sort_i_grabr, '<div id="tempLoader" class="col-6 col-md-4 no-padding show_all_ideas"><div class="cover-wrapper"><div class="black-background-obs cover-link"><div class="cover-btn"><i class="far fa-yin-yang fa-spin"></i></div></div></div></div>');
    
    //Update backend:
    $.post("/i/i__add", {
        x__type: x__type,
        focus_card: fetch_int_val('#focus_card'),
        focus_id: fetch_int_val('#focus_id'),
        input__4736: input__4736,
        link_i__id: link_i__id
    }, function (data) {

        //Delete loader:
        $("#tempLoader").remove();
        input_field.removeClass('dynamic_saving').prop("disabled", false).focus();
        i_is_adding = false;

        if (data.status) {

            x_type_counter(x__type, 1);

            sort_i_load(x__type);

            //Add new
            add_to_list(x__type, sort_i_grabr, data.new_i_html);

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
                add_to_list(x__type, '.coinface-12274', data.e_new_echo);

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




function x_message_save(new_x__message = null) {

    //Prepare data to be modified for this idea:
    var modify_data = {
        x__id: $('#modal13571 .modal_x__id').val(),
        x__message: ( new_x__message ? new_x__message : $('#x__message').val() ),
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
            $('#modal13571 .save_results').html('<span class="zq6255 main__title"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</span>').hide().fadeIn();

        }

    });

}


function click_has_class(target_el, target_class){
    //Aggregare followings:
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

    var r = confirm("Unlink this source?");
    if (r==true) {
        $.post("/e/e_remove", {

            x__id: x__id,

        }, function (data) {
            if (data.status) {

                x_type_counter(x__type, -1);
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
            $('#x__history_preview').html(data.x__history_preview);


            $('[data-toggle="tooltip"]').tooltip();

        } else {

            //Show Error:
            $('#x__type_preview').html('<b class="zq6255">' + data.message+'</b>');

        }

    });

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


function add_to_list(x__type, sort_i_grabr, html_content) {

    //See if we previously have a list in place?
    if ($("#list-in-" + x__type + " " + sort_i_grabr).length > 0) {
        if(!js_n___14686.includes(x__type)){
            //Downwards add to start"
            $("#list-in-" + x__type + " " + sort_i_grabr + ":first").before(html_content);
        } else {
            //Upwards adds to end:
            $("#list-in-" + x__type + " " + sort_i_grabr + ":last").after(html_content);
        }
    } else {
        //Raw list, add before input filed:
        $("#list-in-" + x__type).prepend(html_content);
    }


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




function image_api_search(){
    x_create({
        x__creator: js_pl_id,
        x__type: 14576, //MODAL VIEWED
        x__up: 14073,
        x__right: fetch_int_val('#focus_id'),
    });
    $('#modal14073').modal('show');
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
    var current_value = $('.input__4736').val();
    $('#modal14073').modal('hide');
    $('.input__4736').val(( current_value.length ? current_value+"\n\n" : '' ) + image_url + '?e__title='+encodeURI(image_title));
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
    var handler = ".text__"+cache_e__id+"_" + e__id;
    $(handler).text(e__title).attr('old-value', e__title); //.val(e__title)
    set_autosize($(handler));
}

function x_set_text(this_grabr){

    var modify_data = {
        s__id: parseInt($(this_grabr).attr('s__id')),
        cache_e__id: parseInt($(this_grabr).attr('cache_e__id')),
        input__4736: $(this_grabr).val().trim()
    };

    //See if anything changes:
    if( $(this_grabr).attr('old-value')==modify_data['input__4736'] ){
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
            update_text_name(modify_data['cache_e__id'], modify_data['s__id'], modify_data['input__4736']);

        }

        setTimeout(function () {
            //Restore background:
            $(handler).removeClass('dynamic_saving').prop("disabled", false);
        }, 233);

    });
}




function x_type_counter(x__type, adjustment_count){
    var current_total_count = parseInt($('.headline_body_' + x__type).attr('read-counter')) + adjustment_count;
    $('.xtypecounter'+x__type).text(current_total_count);
}




function text_search(obj) {
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
                    return ' @' + suggestion.s__id + ' ';
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
                    return ' #' + suggestion.s__id + ' ';
                }
            },
        ]);
    }
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

function e_radio(focus_id, selected_e__id, enable_mulitiselect){

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
        selected_e__id: selected_e__id,
        enable_mulitiselect: enable_mulitiselect,
        was_previously_selected: was_previously_selected,
        member__id_override: $('#member__id_override').val(),
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
            $('.save_email').html('<b class="zq6255 main__title"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_email').html(js_e___11035[14424]['m__cover'] + ' ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_email').html('');
            }, 2584);

        }
    });

}



function e_phone(){

    //Show spinner:
    $('.save_phone').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_shuffle_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_phone", {
        e_phone: $('#e_phone').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_phone').html('<b class="zq6255 main__title"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_phone').html(js_e___11035[14424]['m__cover'] + ' ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_phone').html('');
            }, 2584);

        }
    });

}

function e_fullname(){

    //Show spinner:
    $('.save_name').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_shuffle_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_fullname", {
        e_fullname: $('#e_fullname').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_name').html('<b class="zq6255 main__title"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_name').html(js_e___11035[14424]['m__cover'] + ' ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_name').html('');
            }, 2584);

        }
    });

}



function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 0;
}



function final_logout(){
    var r = confirm("FINAL WARNING: You are about to permanently lose access to your anonymous account since you have not yet added your email. Are you sure you want to continue?");
    if (r==true) {
        //Redirect:
        js_redirect('/-7291');
    } else {
        return false;
    }
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
                //Update source status:
                $('.card___12274_'+o__id+' .cover-link').removeClass('card_access_'+selected_e__id).addClass('card_access_'+new_e__id);
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

        } else {

            //Reset to default:
            var current_class = $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn span').attr('class');
            $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="'+current_class+'">'+data_object[current_selected]['m__cover']+'</span>' + ( show_full_name ? data_object[current_selected]['m__title'] : '' ));

            //Show error:
            alert(data.message);

        }
    });
}






var message_saving = false; //Prevent double saving
function i_edit_save(){

    if(message_saving){
        return false;
    }

    message_saving = true;
    var i__id = $('#modal_i__id').val();
    var message_text = $('.input__4736').val().trim();
    $.post("/i/i_edit_save", {
        i__id:i__id,
        input__4736: message_text, //Idea Message
        input__32337: $('.input__32337').val().trim() //Idea Hashtag
    }, function (data) {

        if (!data.status) {

            //Show Errors:
            $(".note_error_4736").html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span> Idea Not Saved:<br />'+data.message);

        } else {

            $('#modal31911').modal('hide');

            //Reset errors:
            $(".note_error_4736").html('');

            //Update Idea Message:
            $('.i__message_text_'+i__id).text(message_text);
            $('.i__message_html_'+i__id).html(data.message_html);
            $(".card___12273_"+i__id).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233); //Flash idea

            //Tooltips:
            $('[data-toggle="tooltip"]').tooltip();

            //Load Images:
            message_saving = false;

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
                    js_redirect('/~' + focus_id);
                } else if(focus_card==12274){
                    js_redirect('/@' + focus_id);
                }

            }
        });
    }
}



