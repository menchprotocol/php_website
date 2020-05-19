

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

    if(parseInt(js_en_all_6404[12678]['m_desc'])){
        $('.en_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('@' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name);

        }).autocomplete({hint: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'alg_obj_type_id=4536',
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
                return '@' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name;
            },
            templates: {
                suggestion: function (suggestion) {
                    return echo_search_result(suggestion);
                },
                empty: function (data) {
                    return '<div class="not-found montserrat"><i class="fas fa-exclamation-circle"></i> No Sources Found</div>';
                },
            }
        }]);

        $('.in_quick_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('#' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name);

        }).autocomplete({hint: false, minLength: 2}, [{

            source: function (q, cb) {
                algolia_index.search(q, {
                    filters: 'alg_obj_type_id=4535 AND ( _tags:is_featured ' + ( js_pl_id > 0 ? 'OR _tags:alg_source_' + js_pl_id : '' ) + ')',
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
                return '#' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name;
            },
            templates: {
                suggestion: function (suggestion) {
                    return echo_search_result(suggestion);
                },
                empty: function (data) {
                    return '<div class="not-found montserrat"><i class="fas fa-exclamation-circle"></i> No Ideas Found</div>';
                },
            }
        }]);
    }
}


function js_extract_icon_color(en_icon){

    //NOTE: Has a twin PHP function

    if(en_icon.includes('read')){
        return ' read ';
    } else if(en_icon.includes( 'idea')){
        return ' idea ';
    } else if(en_icon.includes('source') || !en_icon.length){
        return ' source ';
    } else {
        return '';
    }
}

function echo_search_result(alg_obj){

    //Determine object type:
    var is_idea = (parseInt(alg_obj.alg_obj_type_id)==4535);
    var is_public = ( parseInt(alg_obj.alg_obj_status) in ( is_idea ? js_en_all_7355 : js_en_all_7357 ));
    var obj_icon = ( is_idea ? '<i class="fas fa-circle '+( js_session_superpowers_assigned.includes(10939) ? 'idea' : 'read' )+'"></i>' : alg_obj.alg_obj_icon );
    var obj_full_name = ( alg_obj._highlightResult && alg_obj._highlightResult.alg_obj_name.value ? alg_obj._highlightResult.alg_obj_name.value : alg_obj.alg_obj_name );

    return '<span class="icon-block-sm">'+ obj_icon +'</span>' + ( is_public ? '' : '<span class="icon-block-sm"><i class="far fa-spinner fa-spin"></i></span>' ) + '<span class="'+ ( !is_idea ? js_extract_icon_color(obj_icon) : '' ) +'">' + obj_full_name + '</span>'; //htmlentitiesjs()

}


function js_echo_platform_message(en_id){
    var messages = js_en_all_12687[en_id]['m_desc'].split(" | ");
    if(messages.length == 1){
        //Return message:
        return messages[0];
    } else {
        //Choose Random:
        return messages[Math.floor(Math.random()*messages.length)];
    }
}


function loadtab(ln_type_source_id, tab_data_id, note_in_id, owner_en_id){

    //Hide all tabs:
    $('.tab-group-'+ln_type_source_id).addClass('hidden');
    $('.tab-nav-'+ln_type_source_id).removeClass('active');

    //Show this tab:
    $('.tab-group-'+ln_type_source_id+'.tab-data-'+tab_data_id).removeClass('hidden');
    $('.tab-nav-'+ln_type_source_id+'.tab-head-'+tab_data_id).addClass('active');

    //Focus on potential input field if any:
    $('#ln_content'+tab_data_id).focus();

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
        if(!algolia_index && parseInt(js_en_all_6404[12678]['m_desc'])){
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


    if(parseInt(js_en_all_6404[12678]['m_desc'])){

        $("#mench_search").on('autocomplete:selected', function (event, suggestion, dataset) {

            $('#mench_search').prop("disabled", true).val('Loading...').css('background-color','#f0f0f0').css('font-size','0.8em');

            window.location = suggestion.alg_obj_url;

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
                                    search_filters += 'alg_obj_type_id=4536 ';

                                } else {

                                    //Can view limited sources:
                                    search_filters += 'alg_obj_type_id='+( search_only_in ? 4535 : 4536 )+' AND ( _tags:is_featured OR _tags:alg_source_' + js_pl_id + ') ';
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
                                search_filters += '(alg_obj_type_id='+( search_only_in ? 4535 : 4536 )+' AND _tags:is_featured)';

                            } else {

                                //Guest can search ideas only by default as they start typing;
                                search_filters += '(alg_obj_type_id=4535 AND _tags:is_featured)';

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
                        return echo_search_result(suggestion);
                    },
                    header: function (data) {
                        if(validURL(data.query)){

                            return en_fetch_canonical_url(data.query, false);

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
                            return en_fetch_canonical_url(data.query, true);
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



function en_ln_type_preview_load(){

    //Watchout for content change
    var textInput = document.getElementById('ln_content');

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
            en_ln_type_preview();
        }, 610);
    };

}




function en_ln_type_preview() {

    /*
     * Updates the type of link based on the link content
     *
     * */

    $('#en_type_link_id').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');


    //Fetch Idea Data to load modify widget:
    $.post("/source/en_ln_type_preview", {
        ln_content: $('#ln_content').val(),
        ln_id: ( $( "#modifybox" ).length ? parseInt($('#modifybox').attr('source-link-id')) : 0 ),
    }, function (data) {

        //All good, let's load the data into the Modify Widget...
        $('#en_type_link_id').html((data.status ? data.html_ui : '<b class="read">' + data.message+'</b>'));

        if(data.status && data.en_link_preview.length > 0){
            $('#en_link_preview').html(data.en_link_preview);
        } else {
            $('#en_link_preview').html('');
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

    $('.main_nav').addClass('hidden');
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



function read_toggle_saved(in_id){
    $('.share-this').removeClass('hidden');
    $('.toggle_saved').toggleClass('hidden');
    $.post("/read/read_toggle_saved", {
        in_id:in_id,
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

function en_fetch_canonical_url(query_string, not_found){

    //Do a call to PHP to fetch canonical URL and see if that exists:
    $.post("/source/en_fetch_canonical_url", { search_url:query_string }, function (searchdata) {
        if(searchdata.status && searchdata.url_previously_existed){
            //URL was detected via PHP, update the search results:
            $('.add-source-suggest').remove();
            $('.not-found').html('<a href="/source/'+searchdata.algolia_object.alg_obj_id+'" class="suggestion">' + echo_search_result(searchdata.algolia_object)+'</a>');
        }
    });

    //We did not find the URL:
    return ( not_found ? '<div class="not-found montserrat"><i class="fas fa-exclamation-circle"></i> URL not found</div>' : '');
}


function delete_all_saved(){
    $('.object_saved').removeClass('en_saved');
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

function ms_toggle(ln_id, new_state) {

    if (new_state < 0) {
        //Detect new state:
        new_state = ($('.link-class--' + ln_id).hasClass('hidden') ? 1 : 0);
    }

    if (new_state) {
        //open:
        $('.link-class--' + ln_id).removeClass('hidden');
        $('#handle-' + ln_id).removeClass('fa-plus-circle').addClass('fa-minus-circle');
    } else {
        //Close:
        $('.link-class--' + ln_id).addClass('hidden');
        $('#handle-' + ln_id).removeClass('fa-minus-circle').addClass('fa-plus-circle');
    }
}





function in_load_search(element_focus, is_in_parent, shortcut, is_add_mode) {

    //Idea Search
    $(element_focus).keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            if(is_add_mode=='link_in') {
                return in_link_or_create($(this).attr('idea-id'), is_in_parent, 0);
            } else if(is_add_mode=='link_my_in') {
                return in_create();
            }
            e.preventDefault();
        }
    });

    if(!parseInt(js_en_all_6404[12678]['m_desc'])){
        //Previously loaded:
        return false;
    }

    //Not yet loaded, continue with loading it:
    $(element_focus).on('autocomplete:selected', function (event, suggestion, dataset) {

        if(is_add_mode=='link_in'){
            in_link_or_create($(this).attr('idea-id'), is_in_parent, suggestion.alg_obj_id);
        } else {
            //Go to idea:
            window.location = suggestion.alg_obj_url;
            return true;
        }
    }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [( is_in_parent ? 'q' : 'a' )]}, [{

        source: function (q, cb) {

            if($(element_focus).val().charAt(0)=='#'){
                cb([]);
                return;
            } else {
                algolia_index.search(q, {

                    filters: 'alg_obj_type_id=4535 AND ( _tags:is_featured ' + ( js_pl_id > 0 ? 'OR _tags:alg_source_' + js_pl_id : '' ) + ')',
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
                return echo_search_result(suggestion);
            },
            header: function (data) {
                if (is_add_mode=='link_in' && !($(element_focus).val().charAt(0)=='#') && !data.isEmpty) {
                    return '<a href="javascript:in_link_or_create(' + parseInt($(element_focus).attr('idea-id')) + ','+is_in_parent+',0)" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle idea add-plus"></i></span><b>' + data.query + '</b></a>';
                } else if(is_add_mode=='link_my_in'){
                    return '<a href="javascript:in_create()" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle idea add-plus"></i></span><b>' + data.query + '</b></a>';
                }
            },
            empty: function (data) {
                if(is_add_mode=='link_in'){
                    if($(element_focus).val().charAt(0)=='#'){
                        return '<a href="javascript:in_link_or_create(' + parseInt($(element_focus).attr('idea-id')) + ','+is_in_parent+',0)" class="suggestion"><span class="icon-block-sm"><i class="fas fa-link"></i></span>Link to <b>' + data.query + '</b></a>';
                    } else {
                        return '<a href="javascript:in_link_or_create(' + parseInt($(element_focus).attr('idea-id')) + ','+is_in_parent+',0)" class="suggestion"><span class="icon-block-sm"><i class="fas fa-plus-circle idea add-plus"></i></span><b>' + data.query + '</b></a>';
                    }
                }
            },
        }
    }]);

}



function echo_input_text_update_start(){
    $('.echo_input_text_update').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            echo_input_text_update(this);
            e.preventDefault();
        }
    }).change(function() {
        echo_input_text_update(this);
    });
}

function echo_input_text_count(cache_en_id, object_id) {

    //Count text area characters:

    //Update count:
    var len = $('.text__'+cache_en_id+'_'+object_id).val().length;
    if (len > js_en_all_6404[cache_en_id]['m_desc']) {
        $('#current_count_'+cache_en_id+'_'+object_id).addClass('overload').text(len);
    } else {
        $('#current_count_'+cache_en_id+'_'+object_id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_en_all_6404[cache_en_id]['m_desc'] * js_en_all_6404[12088]['m_desc'] )){
        $('.title_counter_'+cache_en_id+'_'+object_id).removeClass('hidden');
    } else {
        $('.title_counter_'+cache_en_id+'_'+object_id).addClass('hidden');
    }

}

function update_text_name(cache_en_id, en_id, en_name){
    if(cache_en_id==6197){
        en_name = en_name.toUpperCase();
    }
    $(".text__"+cache_en_id+"_" + en_id).val(en_name).text(en_name).attr('old-value', en_name);
}

function echo_input_text_update(this_handler){

    var modify_data = {
        object_id: parseInt($(this_handler).attr('object_id')),
        cache_en_id: parseInt($(this_handler).attr('cache_en_id')),
        field_value: $(this_handler).val().trim()
    };

    //See if anything changes:
    if( $(this_handler).attr('old-value') == modify_data['field_value'] ){
        //Nothing changed:
        return false;
    }

    //Grey background to indicate saving...
    var handler = '.text__'+modify_data['cache_en_id']+'_'+modify_data['object_id'];
    $(handler).addClass('dynamic_saving');

    $.post("/ledger/echo_input_text_update", modify_data, function (data) {

        if (!data.status) {

            //Reset to original value:
            $(handler).val(data.original_val);

            //Show error:
            alert(data.message);

        } else {

            //If Updating Text, Updating Corresponding Fields:
            update_text_name(modify_data['cache_en_id'], modify_data['object_id'], modify_data['field_value']);

        }

        setTimeout(function () {
            //Restore background:
            $(handler).removeClass('dynamic_saving');
        }, 233);

    });
}

