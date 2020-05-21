

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
            profileURL: base_url+'source/'+js_pl_id
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

    if(parseInt(js_sources__6404[12678]['m_desc'])){
        $('.source_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('@' + suggestion.object__id + ' ' + suggestion.object__title);

        }).autocomplete({hint: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'object__type=4536',
                    hitsPerPage: 5,
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

        $('.idea_quick_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('#' + suggestion.object__id + ' ' + suggestion.object__title);

        }).autocomplete({hint: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'object__type=4535 AND ( _tags:is_featured ' + ( js_pl_id > 0 ? 'OR _tags:alg_source_' + js_pl_id : '' ) + ')',
                    hitsPerPage: 5,
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


function js_extract_icon_color(source__icon){

    //NOTE: Has a twin PHP function

    if(source__icon.includes('read')){
        return ' read ';
    } else if(source__icon.includes( 'idea')){
        return ' idea ';
    } else if(source__icon.includes('source') || !source__icon.length){
        return ' source ';
    } else {
        return '';
    }
}

function view_search_result(algolia_object){

    //Determine object type:
    var is_idea = (parseInt(algolia_object.object__type)==4535);
    var is_public = ( parseInt(algolia_object.object__status) in ( is_idea ? js_sources__7355 : js_sources__7357 ));
    var obj_icon = ( is_idea ? '<i class="fas fa-circle idea"></i>' : algolia_object.object__icon );
    var obj_full_name = ( algolia_object._highlightResult && algolia_object._highlightResult.object__title.value ? algolia_object._highlightResult.object__title.value : algolia_object.object__title );

    return '<span class="icon-block-sm">'+ obj_icon +'</span>' + ( is_public ? '' : '<span class="icon-block-sm"><i class="far fa-spinner fa-spin"></i></span>' ) + '<span class="montserrat '+ ( !is_idea ? js_extract_icon_color(obj_icon) : '' ) +'">' + obj_full_name + '</span>'; //htmlentitiesjs()

}


function js_view_platform_message(source__id){
    var messages = js_sources__12687[source__id]['m_desc'].split(" | ");
    if(messages.length == 1){
        //Return message:
        return messages[0];
    } else {
        //Choose Random:
        return messages[Math.floor(Math.random()*messages.length)];
    }
}


function loadtab(read__type, tab_data_id, note_idea__id, owner_source__id){

    //Hide all tabs:
    $('.tab-group-'+read__type).addClass('hidden');
    $('.tab-nav-'+read__type).removeClass('active');

    //Show this tab:
    $('.tab-group-'+read__type+'.tab-data-'+tab_data_id).removeClass('hidden');
    $('.tab-nav-'+read__type+'.tab-head-'+tab_data_id).addClass('active');

    //Focus on potential input field if any:
    $('#read__message'+tab_data_id).focus();

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
    load_intercom();

    if(js_pl_id > 1){
        //For any logged in player except shervin:
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
        if(!algolia_index && parseInt(js_sources__6404[12678]['m_desc'])){
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


    if(parseInt(js_sources__6404[12678]['m_desc'])){

        $("#mench_search").on('autocomplete:selected', function (event, suggestion, dataset) {

            $('#mench_search').prop("disabled", true).val('Loading...').css('background-color','#f0f0f0').css('font-size','0.8em');

            window.location = suggestion.object__url;

        }).autocomplete({minLength: 1, autoselect: true, keyboardShortcuts: ['s']}, [
            {
                source: function (q, cb) {

                    //Players can filter search with first word:
                    var search_only_source = $("#mench_search").val().charAt(0) == '@';
                    var search_only_in = $("#mench_search").val().charAt(0) == '#';

                    //Do not search if specific command ONLY:
                    if (( search_only_in || search_only_source ) && !isNaN($("#mench_search").val().substr(1)) ) {

                        cb([]);
                        return;

                    } else {

                        //Now determine the filters we need to apply:
                        var search_filters = '';

                        if(js_pl_id > 0){

                            //For Players:
                            if(search_only_source || search_only_in){

                                if(search_only_source && js_session_superpowers_assigned.includes(12701)){

                                    //Can view ALL Players:
                                    search_filters += 'object__type=4536 ';

                                } else {

                                    //Can view limited sources:
                                    search_filters += 'object__type='+( search_only_in ? 4535 : 4536 )+' AND ( _tags:is_featured OR _tags:alg_source_' + js_pl_id + ') ';
                                }

                            } else {

                                if(js_session_superpowers_assigned.includes(12701)){

                                    //no filter

                                } else {

                                    //Can view limited sources:
                                    search_filters += ' ( _tags:is_featured OR _tags:alg_source_' + js_pl_id + ' ) ';

                                }

                            }

                        } else {

                            //For Guests:
                            if(search_only_source || search_only_in){

                                //Guest can search sources only with a starting @ sign
                                search_filters += '(object__type='+( search_only_in ? 4535 : 4536 )+' AND _tags:is_featured)';

                            } else {

                                //Guest can search ideas only by default as they start typing;
                                search_filters += '(object__type=4535 AND _tags:is_featured)';

                            }

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

                            return source_fetch_canonical(data.query, false);

                        } else if($("#mench_search").val().charAt(0)=='#' || $("#mench_search").val().charAt(0)=='@'){

                            //See what follows the @/# sign to determine if we should create OR redirect:
                            var search_body = $("#mench_search").val().substr(1);
                            if(!isNaN(search_body)){
                                //Valid Integer, Give option to go there:
                                return '<a href="' + ( $("#mench_search").val().charAt(0)=='#' ? '/idea/go/' : '/source/' ) + search_body + '" class="suggestion"><span class="icon-block-sm"><i class="far fa-level-up rotate90" style="margin: 0 5px;"></i></span>Go to ' + data.query
                            }

                        }
                    },
                    empty: function (data) {
                        if(validURL(data.query)){
                            return source_fetch_canonical(data.query, true);
                        } else if($("#mench_search").val().charAt(0)=='#'){
                            if(isNaN($("#mench_search").val().substr(1))){
                                return '<div class="not-found montserrat"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No IDEA found</div>';
                            }
                        } else if($("#mench_search").val().charAt(0)=='@'){
                            if(isNaN($("#mench_search").val().substr(1))) {
                                return '<div class="not-found montserrat"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No SOURCE found</div>';
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



function read_preview_type_load(){

    //Watchout for content change
    var textInput = document.getElementById('read__message');

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
            read_preview_type();
        }, 610);
    };

}




function read_preview_type() {

    /*
     * Updates the type of link based on the link content
     *
     * */

    $('#read__type_preview').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Fetch Idea Data to load modify widget:
    $.post("/read/read_preview_type", {
        read__message: $('#read__message').val(),
        read__id: ( $( "#modifybox" ).length ? parseInt($('#modifybox').attr('source-link-id')) : 0 ),
    }, function (data) {

        //All good, let's load the data into the Modify Widget...
        $('#read__type_preview').html((data.status ? data.html_ui : '<b class="read">' + data.message+'</b>'));

        if(data.status && data.source_link_preview.length > 0){
            $('#source_link_preview').html(data.source_link_preview);
        } else {
            $('#source_link_preview').html('');
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

    $('.primary_nav').addClass('hidden');
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



function read_toggle_saved(idea__id){
    $('.share-this').removeClass('hidden');
    $('.toggle_saved').toggleClass('hidden');
    $.post("/read/read_toggle_saved", {
        idea__id:idea__id,
    }, function (data) {
        if (!data.status) {
            alert(data.message);
            $('.toggle_saved').toggleClass('hidden');

        }
    });
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

function source_fetch_canonical(query_string, not_found){

    //Do a call to PHP to fetch canonical URL and see if that exists:
    $.post("/source/source_fetch_canonical", { search_url:query_string }, function (searchdata) {
        if(searchdata.status && searchdata.url_previously_existed){
            //URL was detected via PHP, update the search results:
            $('.add-source-suggest').remove();
            $('.not-found').html('<a href="/source/'+searchdata.algolia_object.object__id+'" class="suggestion">' + view_search_result(searchdata.algolia_object)+'</a>');
        }
    });

    //We did not find the URL:
    return ( not_found ? '<div class="not-found montserrat"><i class="fas fa-exclamation-circle"></i> URL not found</div>' : '');
}


function delete_all_saved(){
    $('.object_saved').removeClass('source_saved');
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

function ms_toggle(read__id, new_state) {

    if (new_state < 0) {
        //Detect new state:
        new_state = ($('.link-class--' + read__id).hasClass('hidden') ? 1 : 0);
    }

    if (new_state) {
        //open:
        $('.link-class--' + read__id).removeClass('hidden');
        $('#handle-' + read__id).removeClass('fa-plus-circle').addClass('fa-minus-circle');
    } else {
        //Close:
        $('.link-class--' + read__id).addClass('hidden');
        $('#handle-' + read__id).removeClass('fa-minus-circle').addClass('fa-plus-circle');
    }
}





function idea_load_search(element_focus, is_idea_previous, shortcut, is_add_mode) {

    //Idea Search
    $(element_focus).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            if(is_add_mode=='link_in') {
                return idea_add($(this).attr('idea-id'), is_idea_previous, 0);
            } else if(is_add_mode=='link_my_in') {
                return idea_create();
            }
            e.preventDefault();
        }
    });

    if(!parseInt(js_sources__6404[12678]['m_desc'])){
        //Previously loaded:
        return false;
    }

    //Not yet loaded, continue with loading it:
    $(element_focus).on('autocomplete:selected', function (event, suggestion, dataset) {

        if(is_add_mode=='link_in'){
            idea_add($(this).attr('idea-id'), is_idea_previous, suggestion.object__id);
        } else {
            //Go to idea:
            window.location = suggestion.object__url;
            return true;
        }
    }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [( is_idea_previous ? 'q' : 'a' )]}, [{

        source: function (q, cb) {

            if($(element_focus).val().charAt(0)=='#'){
                cb([]);
                return;
            } else {
                algolia_index.search(q, {

                    filters: 'object__type=4535 AND ( _tags:is_featured ' + ( js_pl_id > 0 ? 'OR _tags:alg_source_' + js_pl_id : '' ) + ')',
                    hitsPerPage:( is_add_mode=='link_in' ? 7 : 10 ),

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
                    return '<a href="javascript:idea_add(' + parseInt($(element_focus).attr('idea-id')) + ','+is_idea_previous+',0)" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle idea add-plus"></i></span><b>' + data.query + '</b></a>';
                } else if(is_add_mode=='link_my_in'){
                    return '<a href="javascript:idea_create()" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle idea add-plus"></i></span><b>' + data.query + '</b></a>';
                }
            },
            empty: function (data) {
                if(is_add_mode=='link_in'){
                    if($(element_focus).val().charAt(0)=='#'){
                        return '<a href="javascript:idea_add(' + parseInt($(element_focus).attr('idea-id')) + ','+is_idea_previous+',0)" class="suggestion"><span class="icon-block-sm"><i class="fas fa-link"></i></span>Link to <b>' + data.query + '</b></a>';
                    } else {
                        return '<a href="javascript:idea_add(' + parseInt($(element_focus).attr('idea-id')) + ','+is_idea_previous+',0)" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle idea add-plus"></i></span><b>' + data.query + '</b></a>';
                    }
                }
            },
        }
    }]);

}



function view_input_text_update_start(){
    $('.view_input_text_update').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            view_input_text_update(this);
            e.preventDefault();
        }
    }).change(function() {
        view_input_text_update(this);
    });
}

function view_input_text_count(cache_source__id, object__id) {

    //Count text area characters:

    //Update count:
    var len = $('.text__'+cache_source__id+'_'+object__id).val().length;
    if (len > js_sources__6404[cache_source__id]['m_desc']) {
        $('#current_count_'+cache_source__id+'_'+object__id).addClass('overload').text(len);
    } else {
        $('#current_count_'+cache_source__id+'_'+object__id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_sources__6404[cache_source__id]['m_desc'] * js_sources__6404[12088]['m_desc'] )){
        $('.title_counter_'+cache_source__id+'_'+object__id).removeClass('hidden');
    } else {
        $('.title_counter_'+cache_source__id+'_'+object__id).addClass('hidden');
    }

}

function update_text_name(cache_source__id, source__id, source__title){
    if(cache_source__id==6197){
        source__title = source__title.toUpperCase();
    }
    $(".text__"+cache_source__id+"_" + source__id).val(source__title).text(source__title).attr('old-value', source__title);
}

function view_input_text_update(this_handler){

    var modify_data = {
        object__id: parseInt($(this_handler).attr('object__id')),
        cache_source__id: parseInt($(this_handler).attr('cache_source__id')),
        field_value: $(this_handler).val().trim()
    };

    //See if anything changes:
    if( $(this_handler).attr('old-value') == modify_data['field_value'] ){
        //Nothing changed:
        return false;
    }

    //Grey background to indicate saving...
    var handler = '.text__'+modify_data['cache_source__id']+'_'+modify_data['object__id'];
    $(handler).addClass('dynamic_saving');

    $.post("/read/view_input_text_update", modify_data, function (data) {

        if (!data.status) {

            //Reset to original value:
            $(handler).val(data.original_val);

            //Show error:
            alert(data.message);

        } else {

            //If Updating Text, Updating Corresponding Fields:
            update_text_name(modify_data['cache_source__id'], modify_data['object__id'], modify_data['field_value']);

        }

        setTimeout(function () {
            //Restore background:
            $(handler).removeClass('dynamic_saving');
        }, 233);

    });
}

