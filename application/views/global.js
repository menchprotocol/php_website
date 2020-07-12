

function load_intercom(){
    window.intercomSettings = {
        app_id: "e7livfc1",
        custom_launcher_selector:'.icon_12899',
        name: js_pl_name, // Full name
    };
    (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/e7livfc1';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
}

function load_fullstory(){
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



function mass_action_ui(){
    $('.mass_action_item').addClass('hidden');
    $('#mass_id_' + $('#set_mass_action').val() ).removeClass('hidden');
}

function htmlentitiesjs(rawStr){
    return rawStr.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
        return '&#'+i.charCodeAt(0)+';';
    });
}

function load_editor(){

    $('#set_mass_action').change(function () {
        mass_action_ui();
    });

    if(parseInt(js_e___6404[12678]['m_desc'])){
        $('.e_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('@' + suggestion.object__id + ' ' + suggestion.object__title);

        }).autocomplete({hint: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'object__type=12274',
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
                return '@' + suggestion.object__id + ' ' + suggestion.object__title;
            },
            templates: {
                suggestion: function (suggestion) {
                    return view_search_result(suggestion);
                },
                empty: function (data) {
                    return '<div class="not-found montserrat"><i class="fas fa-exclamation-circle"></i> No Sources Found</div>';
                },
            }
        }]);

        $('.i_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('#' + suggestion.object__id + ' ' + suggestion.object__title);

        }).autocomplete({hint: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'object__type=12273',
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
                return '#' + suggestion.object__id + ' ' + suggestion.object__title;
            },
            templates: {
                suggestion: function (suggestion) {
                    return view_search_result(suggestion);
                },
                empty: function (data) {
                    return '<div class="not-found montserrat"><i class="fas fa-exclamation-circle"></i> No Ideas Found</div>';
                },
            }
        }]);

    }
}


function js_extract_icon_color(e__icon){

    //NOTE: Has a twin PHP function

    if(e__icon.includes('discover')){
        return ' discover ';
    } else if(e__icon.includes( 'idea')){
        return ' idea ';
    } else if(e__icon.includes('source') || !e__icon.length){
        return ' source ';
    } else {
        return '';
    }
}

function view_search_result(algolia_object){

    //Determine object type:
    var is_i = (parseInt(algolia_object.object__type)==12273);
    var is_public = ( parseInt(algolia_object.object__status) in ( is_i ? js_e___7355 : js_e___7357 ));
    var obj_icon = ( is_i ? '<i class="fas fa-circle idea"></i>' : algolia_object.object__icon );
    var obj_full_name = ( algolia_object._highlightResult && algolia_object._highlightResult.object__title.value ? algolia_object._highlightResult.object__title.value : algolia_object.object__title );

    return '<span class="icon-block">'+ obj_icon +'</span><span class="montserrat '+ ( !is_i ? js_extract_icon_color(obj_icon) : '' ) +'">' + obj_full_name + '</span>' + ( is_public ? '' : '<span class="icon-block"><i class="far fa-spinner fa-spin"></i></span>' ); //htmlentitiesjs()

}


function js_view_platform_message(e__id){
    var messages = js_e___12687[e__id]['m_desc'].split(" | ");
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

    //Focus on potential input field if any:
    $('.input_note_'+tab_data_id).focus();

}

function lazy_load(){
    //Lazyload photos:
    var lazyLoadInstance = new LazyLoad({
        elements_selector: "img.lazyimage"
    });
}

var algolia_index = false;
$(document).ready(function () {

    //For the S shortcut to load search:
    $("#mench_search").focus(function() {
        if(!search_is_on){
            toggle_search();
        }
    });

    lazy_load();
    //load_intercom();

    if(js_pl_id > 1){
        //For any logged in miner except shervin:
        load_fullstory();
    }

    $('#topnav li a').click(function (e) {

        e.preventDefault();
        var hash = $(this).attr('href').replace('#', '');

        if (hash.length > 0 && $('#tab' + hash).length && !$('#tab' + hash).hasClass("hidden")) {
            //Adjust Header:
            $('#topnav>li').removeClass('active');
            $('#nav_' + hash).addClass('active');
            //Adjust Tab:
            $('.tab-pane').removeClass('active');
            $('#tab' + hash).addClass('active');
        }
    });


    //Load Algolia on Focus:
    $(".algolia_search").focus(function () {
        if(!algolia_index && parseInt(js_e___6404[12678]['m_desc'])){
            //Loadup Algolia once:
            client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
            algolia_index = client.initIndex('alg_index');
        }
    });


    //General ESC cancel
    $(document).keyup(function (e) {
        //Watch for action keys:
        if (e.keyCode === 27) { //ESC
            modify_cancel();

            if(search_is_on){
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


    if(parseInt(js_e___6404[12678]['m_desc'])){

        $("#mench_search").on('autocomplete:selected', function (event, suggestion, dataset) {

            $('#mench_search').prop("disabled", true).val('Loading...').css('background-color','#f0f0f0').css('font-size','0.8em');

            window.location = suggestion.object__url;

        }).autocomplete({minLength: 1, autoselect: true, keyboardShortcuts: ['s']}, [
            {
                source: function (q, cb) {

                    //Miners can filter search with first word:
                    var search_only_e = $("#mench_search").val().charAt(0) == '@';
                    var search_only_in = $("#mench_search").val().charAt(0) == '#';

                    //Do not search if specific command ONLY:
                    if (( search_only_in || search_only_e ) && !isNaN($("#mench_search").val().substr(1)) ) {

                        cb([]);
                        return;

                    } else {

                        //Now determine the filters we need to apply:
                        var search_filters = '';

                        if(search_only_e || search_only_in){
                            search_filters += ' object__type='+( search_only_in ? 12273 : 12274 );
                        }

                        if(js_pl_id > 0){

                            //For Miners:
                            if(!js_session_superpowers_assigned.includes(12701)){
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
                            hitsPerPage: 34,
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
                displayKey: function(suggestion) {
                    return ""
                },
                templates: {
                    suggestion: function (suggestion) {
                        return view_search_result(suggestion);
                    },
                    header: function (data) {
                        if(validURL(data.query)){

                            return e_fetch_canonical(data.query, false);

                        } else if($("#mench_search").val().charAt(0)=='#' || $("#mench_search").val().charAt(0)=='@'){

                            //See what follows the @/# sign to determine if we should create OR redirect:
                            var search_body = $("#mench_search").val().substr(1);
                            if(!isNaN(search_body)){
                                //Valid Integer, Give option to go there:
                                return '<a href="' + ( $("#mench_search").val().charAt(0)=='#' ? '/i/i_go/' : '/@' ) + search_body + '" class="suggestion montserrat"><span class="icon-block"><i class="far fa-level-up rotate90" style="margin: 0 5px;"></i></span>Go to ' + data.query
                            }

                        }
                    },
                    empty: function (data) {
                        if(validURL(data.query)){
                            return e_fetch_canonical(data.query, true);
                        } else if($("#mench_search").val().charAt(0)=='#'){
                            if(isNaN($("#mench_search").val().substr(1))){
                                return '<div class="not-found montserrat"><span class="icon-block-xs"><i class="fas fa-exclamation-circle"></i></span>No IDEA found</div>';
                            }
                        } else if($("#mench_search").val().charAt(0)=='@'){
                            if(isNaN($("#mench_search").val().substr(1))) {
                                return '<div class="not-found montserrat"><span class="icon-block-xs"><i class="fas fa-exclamation-circle"></i></span>No SOURCE found</div>';
                            }
                        } else {
                            return '<div class="not-found suggestion montserrat"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No results found</div>';
                        }
                    },
                }
            }
        ]);
    }
});



function x_type_preview_load(){

    //Watchout for content change
    var textInput = document.getElementById('x__message');

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




function x_type_preview() {

    /*
     * Updates the type of link based on the link content
     *
     * */

    $('#x__type_preview').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Fetch Idea Data to load modify widget:
    $.post("/x/x_type_preview", {
        x__message: $('#x__message').val(),
        x__id: ( $( "#modifybox" ).length ? parseInt($('#modifybox').attr('e-x-id')) : 0 ),
    }, function (data) {

        //All good, let's load the data into the Modify Widget...
        $('#x__type_preview').html((data.status ? data.html_ui : '<b class="discover">' + data.message+'</b>'));

        if(data.status && data.e_link_preview.length > 0){
            $('#e_link_preview').html(data.e_link_preview);
        } else {
            $('#e_link_preview').html('');
        }

        //Reload Tooltip again:
        $('[data-toggle="tooltip"]').tooltip();

    });

}




//For the drag and drop file uploader:
var isAdvancedUpload = function () {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

//Main navigation
var search_is_on = false;
function toggle_search(){

    $('.left_nav').addClass('hidden');
    $('.search_icon').toggleClass('hidden');

    if(search_is_on){

        //Switch to Menu:
        search_is_on = false; //Reverse
        $('.mench_nav').removeClass('hidden');

    } else {

        //Turn Search On:
        search_is_on = true; //Reverse
        $('.search_nav').removeClass('hidden');
        $('#searchFrontForm input').focus();

    }
}


function i_save(i__id){
    $('.toggle_saved').toggleClass('hidden');
    $.post("/x/i_save", {
        i__id:i__id,
    }, function (data) {
        if (!data.status) {
            alert(data.message);
            $('.toggle_saved').toggleClass('hidden');
        } else if (data.is_first_save) {
            //To keep miners informed of what just happened:
            alert(data.first_save_message);
        }
    });
}

function html_13491(font_size_e__id){
    //Update Font:
    $('body').attr("id", "font_size_"+font_size_e__id);
    $('.font_items').removeClass('active');
    $('.font_item_'+font_size_e__id).addClass('active');
}


function modify_cancel(){
    $('.fixed-box').addClass('hidden');
    delete_all_saved();
    $("input").blur();
    if(history.pushState) {
        history.pushState(null, null, '#');
    } else {
        location.hash = '#';
    }
}

function e_fetch_canonical(query_string, not_found){

    //Do a call to PHP to fetch canonical URL and see if that exists:
    $.post("/e/e_fetch_canonical", { search_url:query_string }, function (searchdata) {
        if(searchdata.status && searchdata.url_previously_existed){
            //URL was detected via PHP, update the search results:
            $('.add-e-suggest').remove();
            $('.not-found').html('<a href="/@'+searchdata.algolia_object.object__id+'" class="suggestion montserrat">' + view_search_result(searchdata.algolia_object)+'</a>');
        }
    });

    //We did not find the URL:
    return ( not_found ? '<div class="not-found montserrat"><i class="fas fa-exclamation-circle"></i> URL not found</div>' : '');
}


function delete_all_saved(){
    $('.object_saved').removeClass('e_saved');
}

function validURL(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[\@\=-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(str);
}


function add_to_list(sort_list_id, sort_handler, html_content) {

    //See if we previously have a list in place?
    if ($("#" + sort_list_id + " " + sort_handler).length > 0) {
        //yes we do! add this:
        $("#" + sort_list_id + " " + sort_handler + ":last").after(html_content);
    } else {
        //Raw list, add before input filed:
        $("#" + sort_list_id).prepend(html_content);
    }

    lazy_load();
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




function i_load_search(element_focus, is_i_previous, shortcut, is_add_mode) {

    //Idea Search
    $(element_focus).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            if(is_add_mode=='link_in') {
                return i_add($(this).attr('i-id'), is_i_previous, 0);
            } else if(is_add_mode=='link_my_in') {
                return i_create();
            }
            e.preventDefault();
        }
    });

    if(!parseInt(js_e___6404[12678]['m_desc'])){
        //Previously loaded:
        return false;
    }

    //Not yet loaded, continue with loading it:
    $(element_focus).on('autocomplete:selected', function (event, suggestion, dataset) {

        if(is_add_mode=='link_in'){
            i_add($(this).attr('i-id'), is_i_previous, suggestion.object__id);
        } else {
            //Go to idea:
            window.location = suggestion.object__url;
            return true;
        }
    }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [( is_i_previous ? 'q' : 'a' )]}, [{

        source: function (q, cb) {

            if($(element_focus).val().charAt(0)=='#'){
                cb([]);
                return;
            } else {
                algolia_index.search(q, {

                    filters: ' object__type=12273 ' + ( js_session_superpowers_assigned.includes(12701) ? '' : ' AND ( _tags:is_featured ' + ( js_pl_id > 0 ? 'OR _tags:alg_e_' + js_pl_id : '' ) + ') ' ),
                    hitsPerPage:21,

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
                return view_search_result(suggestion);
            },
            header: function (data) {
                if (is_add_mode=='link_in' && !($(element_focus).val().charAt(0)=='#') && !data.isEmpty) {
                    return '<a href="javascript:i_add(' + parseInt($(element_focus).attr('i-id')) + ','+is_i_previous+',0)" class="suggestion montserrat"><span class="icon-block"><i class="fas fa-plus-circle idea add-plus"></i></span><b>' + data.query + '</b></a>';
                } else if(is_add_mode=='link_my_in'){
                    return '<a href="javascript:i_create()" class="suggestion montserrat"><span class="icon-block"><i class="fas fa-plus-circle idea add-plus"></i></span><b>' + data.query + '</b></a>';
                }
            },
            empty: function (data) {
                if(is_add_mode=='link_in'){
                    if($(element_focus).val().charAt(0)=='#'){
                        return '<a href="javascript:i_add(' + parseInt($(element_focus).attr('i-id')) + ','+is_i_previous+',0)" class="suggestion montserrat"><span class="icon-block"><i class="fas fa-link"></i></span>Link to <b>' + data.query + '</b></a>';
                    } else {
                        return '<a href="javascript:i_add(' + parseInt($(element_focus).attr('i-id')) + ','+is_i_previous+',0)" class="suggestion montserrat"><span class="icon-block"><i class="fas fa-plus-circle idea add-plus"></i></span><b>' + data.query + '</b></a>';
                    }
                }
            },
        }
    }]);

}



function x_set_text_start(){
    $('.x_set_text').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            x_set_text(this);
            e.preventDefault();
        }
    }).change(function() {
        x_set_text(this);
    });
}

function view_input_text_count(cache_e__id, object__id) {

    //Count text area characters:

    //Update count:
    var len = $('.text__'+cache_e__id+'_'+object__id).val().length;
    if (len > js_e___6404[cache_e__id]['m_desc']) {
        $('#current_count_'+cache_e__id+'_'+object__id).addClass('overload').text(len);
    } else {
        $('#current_count_'+cache_e__id+'_'+object__id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_e___6404[cache_e__id]['m_desc'] * js_e___6404[12088]['m_desc'] )){
        $('.title_counter_'+cache_e__id+'_'+object__id).removeClass('hidden');
    } else {
        $('.title_counter_'+cache_e__id+'_'+object__id).addClass('hidden');
    }

}

function update_text_name(cache_e__id, e__id, e__title){
    if(cache_e__id==6197){
        e__title = e__title.toUpperCase();
    }
    $(".text__"+cache_e__id+"_" + e__id).val(e__title).text(e__title).attr('old-value', e__title);
}

function x_set_text(this_handler){

    var modify_data = {
        object__id: parseInt($(this_handler).attr('object__id')),
        cache_e__id: parseInt($(this_handler).attr('cache_e__id')),
        field_value: $(this_handler).val().trim()
    };

    //See if anything changes:
    if( $(this_handler).attr('old-value') == modify_data['field_value'] ){
        //Nothing changed:
        return false;
    }

    //Grey background to indicate saving...
    var handler = '.text__'+modify_data['cache_e__id']+'_'+modify_data['object__id'];
    $(handler).addClass('dynamic_saving');

    $.post("/x/x_set_text", modify_data, function (data) {

        if (!data.status) {

            //Reset to original value:
            $(handler).val(data.original_val);

            //Show error:
            alert(data.message);

        } else {

            //If Updating Text, Updating Corresponding Fields:
            update_text_name(modify_data['cache_e__id'], modify_data['object__id'], modify_data['field_value']);

        }

        setTimeout(function () {
            //Restore background:
            $(handler).removeClass('dynamic_saving');
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

        var note_type_id = parseInt($(this).attr('note-type-id'));

        //Initiate @ search for all idea text areas:
        i_note_e_search($(this));

        autosize($(this));

        //Activate sorting:
        i_note_sort_load(note_type_id);

        var showFiles = function (files) {
            if(typeof files[0] !== 'undefined'){
                $('.box' + note_type_id).find('label').text(files.length > 1 ? ($('.box' + note_type_id).find('input[type="file"]').attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
            }
        };

        $('.box' + note_type_id).find('input[type="file"]').on('drop', function (e) {
            droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
            showFiles(droppedFiles);
        });

        $('.box' + note_type_id).find('input[type="file"]').on('change', function (e) {
            showFiles(e.target.files);
        });

        //Watch for message creation:
        $('#x__message' + note_type_id).keydown(function (e) {
            if (e.ctrlKey && e.keyCode == 13) {
                i_note_text(note_type_id);
            }
        });

        //Watchout for file uplods:
        $('.box' + note_type_id).find('input[type="file"]').change(function () {
            i_note_file(droppedFiles, 'file', note_type_id);
        });


        //Should we auto start?
        if (isAdvancedUpload) {

            $('.box' + note_type_id).addClass('has-advanced-upload');
            var droppedFiles = false;

            $('.box' + note_type_id).on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
            })
                .on('dragover dragenter', function () {
                    $('.add_notes_' + note_type_id).addClass('is-working');
                })
                .on('dragleave dragend drop', function () {
                    $('.add_notes_' + note_type_id).removeClass('is-working');
                })
                .on('drop', function (e) {
                    droppedFiles = e.originalEvent.dataTransfer.files;
                    e.preventDefault();
                    i_note_file(droppedFiles, 'drop', note_type_id);
                });
        }

    });
}

function i_note_counter(note_type_id, adjustment_count){
    var current_count = parseInt($('.en-type-counter-'+note_type_id).text());
    var new_count = current_count + adjustment_count;
    $('.en-type-counter-'+note_type_id).text(new_count);
}

function i_note_count_new(note_type_id) {

    //Update count:
    var len = $('#x__message' + note_type_id).val().length;
    if (len > js_e___6404[4485]['m_desc']) {
        $('#charNum' + note_type_id).addClass('overload').text(len);
    } else {
        $('#charNum' + note_type_id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_e___6404[4485]['m_desc'] * js_e___6404[12088]['m_desc'] )){
        $('#ideaNoteNewCount' + note_type_id).removeClass('hidden');
    } else {
        $('#ideaNoteNewCount' + note_type_id).addClass('hidden');
    }

}

function i_note_edit_count(x__id) {
    //See if this is a valid text message editing:
    if (!($('#charEditingNum' + x__id).length)) {
        return false;
    }
    //Update count:
    var len = $('#message_body_' + x__id).val().length;
    if (len > js_e___6404[4485]['m_desc']) {
        $('#charEditingNum' + x__id).addClass('overload').text(len);
    } else {
        $('#charEditingNum' + x__id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_e___6404[4485]['m_desc'] * js_e___6404[12088]['m_desc'] )){
        $('#ideaNoteCount' + x__id).removeClass('hidden');
    } else {
        $('#ideaNoteCount' + x__id).addClass('hidden');
    }
}

function i_note_e_search(obj) {

    if(parseInt(js_e___6404[12678]['m_desc'])){
        obj.textcomplete([
            {
                match: /(^|\s)@(\w*(?:\s*\w*))$/,
                search: function (query, callback) {
                    algolia_index.search(query, {
                        hitsPerPage: 8,
                        filters: 'object__type=12274',
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
                template: function (suggestion) {
                    return '<div style="padding: 3px 0;">' + view_search_result(suggestion) + '</div>';
                },
                replace: function (suggestion) {
                    return ' @' + suggestion.object__id + ' ';
                }
            },
        ]);
    }
}

function i_note_sort_apply(note_type_id) {

    var new_x__sorts = [];
    var sort_rank = 0;
    var this_x__id = 0;

    $(".msg_e_type_" + note_type_id).each(function () {
        this_x__id = parseInt($(this).attr('x__id'));
        if (this_x__id > 0) {
            sort_rank++;
            new_x__sorts[sort_rank] = this_x__id;
        }
    });

    //Update backend if any:
    if(sort_rank > 0){
        $.post("/i/i_note_sort", {new_x__sorts: new_x__sorts}, function (data) {
            //Only show message if there was an error:
            if (!data.status) {
                //Show error:
                alert(data.message);
            }
        });
    }
}

function i_note_sort_load(note_type_id) {

    var inner_content = null;

    var sort_msg = Sortable.create( document.getElementById("i_notes_list_" + note_type_id) , {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        handle: ".i_note_sorting", // Restricts sort start click/touch to the specified element
        draggable: ".note_sortable", // Specifies which items inside the element should be sortable
        onUpdate: function (evt/**Event*/) {
            //Apply new sort:
            i_note_sort_apply(note_type_id);
        },
        //The next two functions resolve a Bug with sorting iframes like YouTube embeds while also making the UI more informative
        onChoose: function (evt/**Event*/) {
            //See if this is a YouTube or Vimeo iFrame that needs to be temporarily deleted:
            var x__id = $(evt.item).attr('x__id');
            if ($('#ul-nav-' + x__id).find('.video-sorting').length !== 0) {
                inner_content = $('#msgbody_' + x__id).html();
                $('#msgbody_' + x__id).css('height', $('#msgbody_' + x__id).height()).html('<i class="fas fa-bars"></i> SORT VIDEO');
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

function i_note_edit_start(x__id) {

    //Start editing:
    $("#ul-nav-" + x__id).addClass('in-editing');
    $("#ul-nav-" + x__id + " .edit-off").addClass('hidden');
    $("#ul-nav-" + x__id + " .edit-on").removeClass('hidden');
    $("#ul-nav-" + x__id + ">div").css('width', '100%');

    //Set focus to end of text:
    var textinput = $("#ul-nav-" + x__id + " textarea");
    var data = textinput.val();
    textinput.focus().val('').val(data);
    autosize(textinput); //Adjust height

    //Initiate search:
    i_note_e_search(textinput);

    //Try to initiate the editor, which only applies to text messages:
    i_note_edit_count(x__id);

}

function i_note_edit_cancel(x__id) {
    //Revert editing:
    $("#ul-nav-" + x__id).removeClass('in-editing');
    $("#ul-nav-" + x__id + " .edit-off").removeClass('hidden');
    $("#ul-nav-" + x__id + " .edit-on").addClass('hidden');
    $("#ul-nav-" + x__id + ">div").css('width', 'inherit');
}

function i_note_edit(x__id, note_type_id) {

    //Show loader:
    $("#ul-nav-" + x__id + " .edit-updates").html('<div><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></div>');

    //Revert View:
    i_note_edit_cancel(x__id);


    var modify_data = {
        x__id: parseInt(x__id),
        message_x__status: parseInt($("#message_status_" + x__id).val()),
        i__id: parseInt(focus_i__id),
        x__message: $("#ul-nav-" + x__id + " textarea").val(),
    };

    //Update message:
    $.post("/i/i_note_edit", modify_data, function (data) {

        if (data.status) {

            //Did we delete this message?
            if(data.delete_from_ui){

                i_note_counter(note_type_id, -1);

                //Yes, message was deleted, adjust accordingly:
                $("#ul-nav-" + x__id).html('<div>' + data.message + '</div>');

                //Disapper in a while:
                setTimeout(function ()
                {
                    $("#ul-nav-" + x__id).fadeOut();

                    setTimeout(function () {

                        //Delete first:
                        $("#ul-nav-" + x__id).remove();

                        //Adjust sort for this message type:
                        i_note_sort_apply(note_type_id);

                    }, 610);
                }, 610);

            } else {

                //IDEA NOTE EDITED...

                //Update text message:
                $("#ul-nav-" + x__id + " .text_message").html(data.message);

                //Update message status:
                $("#ul-nav-" + x__id + " .message_status").html(data.message_new_status_icon);

                //Show success here
                $("#ul-nav-" + x__id + " .edit-updates").html('<b>' + data.success_icon + '</b>');

                lazy_load();

            }

        } else {
            //Oops, some sort of an error, lets
            $("#ul-nav-" + x__id + " .edit-updates").html('<b class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Disapper in a while:
        setTimeout(function () {
            $("#ul-nav-" + x__id + " .edit-updates>b").fadeOut();
        }, 4181);

    });

}

function i_note_start_adding(note_type_id) {
    $('.save_notes_' + note_type_id).html('<span class="icon-block-lg"><i class="far fa-yin-yang fa-spin"></i></span>').attr('href', '#');
    $('.add_notes_' + note_type_id).addClass('is-working');
    $('.no_notes_' + note_type_id).remove();
    $('#x__message' + note_type_id).prop("disabled", true);
    $('.remove_loading').hide();
}

function i_note_end_adding(result, note_type_id) {

    //Update UI to unlock:
    $('.save_notes_' + note_type_id).html('<i class="fas fa-plus"></i>').attr('href', 'javascript:i_note_text('+note_type_id+');');
    $('.add_notes_' + note_type_id).removeClass('is-working');
    $("#x__message" + note_type_id).prop("disabled", false).focus();
    $('.remove_loading').fadeIn();

    //What was the result?
    if (result.status) {

        //Append data:
        $(result.message).insertBefore( ".add_notes_" + note_type_id );

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Load Images:
        lazy_load();

        //Hide any errors:
        setTimeout(function () {
            $(".note_error_"+note_type_id).fadeOut();
        }, 4181);

    } else {

        $(".note_error_"+note_type_id).html('<span class="discover">'+result.message+'</span>');

    }
}

function i_note_file(droppedFiles, uploadType, note_type_id) {

    //Prevent multiple concurrent uploads:
    if ($('.box' + note_type_id).hasClass('is-uploading')) {
        return false;
    }

    if (isAdvancedUpload) {

        //Lock message:
        i_note_start_adding(note_type_id);

        var ajaxData = new FormData($('.box' + note_type_id).get(0));
        if (droppedFiles) {
            $.each(droppedFiles, function (i, file) {
                var thename = $('.box' + note_type_id).find('input[type="file"]').attr('name');
                if (typeof thename == typeof undefined || thename == false) {
                    var thename = 'drop';
                }
                ajaxData.append(uploadType, file);
            });
        }

        ajaxData.append('upload_type', uploadType);
        ajaxData.append('i__id', focus_i__id);
        ajaxData.append('note_type_id', note_type_id);

        $.ajax({
            url: '/i/i_note_file',
            type: $('.box' + note_type_id).attr('method'),
            data: ajaxData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function () {
                $('.box' + note_type_id).removeClass('is-uploading');
            },
            success: function (data) {

                i_note_counter(note_type_id, +1);
                i_note_end_adding(data, note_type_id);

                //Adjust icon again:
                $('.file_label_' + note_type_id).html('<span class="icon-block"><i class="far fa-paperclip"></i></span>');

            },
            error: function (data) {
                var result = [];
                result.status = 0;
                result.message = data.responseText;
                i_note_end_adding(result, note_type_id);
            }
        });
    } else {
        // ajax for legacy browsers
    }
}

function i_note_text(note_type_id) {

    //Lock message:
    i_note_start_adding(note_type_id);

    //Update backend:
    $.post("/i/i_note_text", {

        i__id: focus_i__id, //Synonymous
        x__message: $('#x__message' + note_type_id).val(),
        note_type_id: note_type_id,

    }, function (data) {

        //Raw Inputs Fields if success:
        if (data.status) {

            //Reset input field:
            $("#x__message" + note_type_id).val("");
            autosize.update($("#x__message" + note_type_id));

            i_note_count_new(note_type_id);
            i_note_counter(note_type_id, +1);

        }

        //Unlock field:
        i_note_end_adding(data, note_type_id);

    });

}


function x_remove(x__type){

    //Watch for Discovery removal click:
    $('.x_remove').on('click', function(e) {

        var i__id = $(this).attr('i__id');
        var r = confirm("Remove "+$('.text__4736_'+i__id).text()+"?");
        if (r == true) {
            //Save changes:
            $.post("/x/x_remove", { x__type:x__type, i__id:i__id }, function (data) {
                //Update UI to confirm with miner:
                if (!data.status) {

                    //There was some sort of an error returned!
                    alert(data.message);

                } else {

                    //REMOVE BOOKMARK from UI:
                    $('#i_cover_'+i__id).fadeOut();

                    setTimeout(function () {

                        //Delete from body:
                        $('#i_cover_'+i__id).remove();

                    }, 233);
                }
            });
        }

        return false;

    });

}

function x_sort_load(x__type){
    //Load sorter:
    var sort = Sortable.create(document.getElementById('i_covers'), {
        animation: 150, // ms, animation speed moving items when sorting, `0` � without animation
        draggable: ".home_sort", // Specifies which items inside the element should be sortable
        handle: ".x-sorter", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            x_sort(x__type);
        }
    });
}


function x_sort(x__type) {

    var sort_rank = 0;
    var new_x_order = [];
    $("#i_covers .home_sort").each(function () {
        var link_id = parseInt($(this).attr('sort-x-id'));
        if(link_id > 0){
            sort_rank++;
            new_x_order[sort_rank] = link_id;
        }
    });

    //Update order:
    if(sort_rank > 0){
        $.post("/x/x_sort", { new_x_order:new_x_order, x__type:x__type }, function (data) {
            //Update UI to confirm with miner:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }

}

