
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


//Facebook Messenger Auto-Login
if(js_pl_id < 1){

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.com/en_US/messenger.Extensions.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'Messenger'));

    //the Messenger Extensions JS SDK is done loading:
    window.extAsyncInit = function () {
        //Get context:
        MessengerExtensions.getContext(js_en_all_6404[11076]['m_desc'],
            function success(thread_context) {
                //user ID was successfully authenticated
                var psid = thread_context.psid;
                var signed_request = thread_context.signed_request;
                //Fetch Page:
                $.post("/source/singin_check_psid/" + psid + "?sr=" + signed_request, {}, function (data) {
                    if(data.status){
                        //All good, refresh this page:
                        location.reload();
                    }
                });
            },
            function error(err) {

            }
        );
    };

}


//JS READ Creator:
function js_ln_create(new_ln_data){
    return $.post("/read/js_ln_create", new_ln_data, function (data) {
        return data;
    });
}

function toggle_read(){

    $('.read_topics').toggleClass('hidden');

    $([document.documentElement, document.body]).animate({
        scrollTop: $("#readScroll").offset().top
    }, 500);

}

function load_editor(){

    $('#set_mass_action').change(function () {
        mass_action_ui();
    });

    $('.en_quick_search').on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('@' + suggestion.alg_obj_id + ' ' + suggestion.alg_obj_name);

    }).autocomplete({hint: false, minLength: 2}, [{

        source: function (q, cb) {
            algolia_index.search(q, {
                filters: 'alg_obj_is_in=0',
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
                return echo_js_suggestion(suggestion);
            },
            empty: function (data) {
                return '<div class="not-found"><i class="fad fa-exclamation-triangle"></i> No sources found</div>';
            },
        }
    }]);
}

function load_leaderboard(){
    //Show loading icon:
    $('#load_leaderboard').html('<div class="alert montserrat source" style="background-color: #FFF;"><span class="icon-block"><i class="far fa-yin-yang fa-spin source"></i></span>LOADING...</div>');
    $('.top-sources').addClass('hidden');

    $.post("/source/load_leaderboard/", { }, function (data) {
        $('#load_leaderboard').html(data);
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function go_to_read(in_id){
    //Is It published?
    if( parseInt($('.dropd_4737_'+in_id+'_0').attr('selected-val')) in js_en_all_7355 ){

        //Yes, go to read:
        window.location = '/'+in_id;

    } else {

        //No, give them option:
        var r = confirm("You can only read this blog once its published. Navigate to reading list?");
        if (r == true) {
            window.location = '/read';
        }

    }
}

function echo_js_suggestion(alg_obj){

    //Determine object type:
    var obj_type = ( parseInt(alg_obj.alg_obj_is_in) ? 'blog' : 'source' );
    var is_published = ( parseInt(alg_obj.alg_obj_status) in ( parseInt(alg_obj.alg_obj_is_in) ? js_en_all_7355 : js_en_all_7357 ));
    var obj_icon = ( parseInt(alg_obj.alg_obj_is_in) ? '<i class="fas fa-circle '+( js_session_superpowers_assigned.includes(10939) ? 'blog' : 'read' )+'"></i>' : alg_obj.alg_obj_icon );
    var obj_full_name = ( alg_obj._highlightResult && alg_obj._highlightResult.alg_obj_name.value ? alg_obj._highlightResult.alg_obj_name.value : alg_obj.alg_obj_name );

    return '<span class="icon-block-sm">'+ obj_icon +'</span>' + ( is_published ? '' : '<span class="icon-block-sm"><i class="far fa-spinner fa-spin"></i></span>' ) + obj_full_name;
}


function turn_off() {
    $('.dash').html('<span><i class="far fa-yin-yang fa-spin"></i></span> ' + echo_loading_notify());
}

function nl2br(str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function adj() {
    var scroll = $(window).scrollTop();
    //>=, not <=
    if (scroll >= 15) {
        //clearHeader, not clearheader - caps H
        $(".navbar").removeClass("navbar-transparent");
    } else {
        $(".navbar").addClass("navbar-transparent");
    }
}

function processAjaxData(response, urlPath) {
    document.getElementById("content").innerHTML = response.html;
    document.title = response.pageTitle;
    window.history.pushState({"html": response.html, "pageTitle": response.pageTitle}, "", urlPath);
}

function is_mobile() {
    var isMobile = false; //initiate as false
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|blog|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
        isMobile = true;
    }
    if (isMobile) {
        $('#mobile-no').show();
    }
    return isMobile;
}

function ordinal_suffix_of(i) {
    var j = i % 10,
        k = i % 100;
    if (j == 1 && k != 11) {
        return i + "st";
    }
    if (j == 2 && k != 12) {
        return i + "nd";
    }
    if (j == 3 && k != 13) {
        return i + "rd";
    }
    return i + "th";
}

function echo_loading_notify(){
    return random_loading_message[Math.floor(Math.random()*random_loading_message.length)];
}
function echo_saving_notify(){
    return random_saving_message[Math.floor(Math.random()*random_saving_message.length)];
}

function read_in_history(tab_group_id, note_in_id, owner_en_id, last_loaded_ln_id){

    var load_class = '.tab-data-'+tab_group_id+' .dynamic-reads';
    $(load_class).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="montserrat">LOADING...</b>');

    //Yes, we need to load dynamically:
    $.post("/read/read_in_history/"+tab_group_id+"/"+note_in_id+"/"+owner_en_id+"/"+last_loaded_ln_id, { }, function (data) {
        if (data.status) {
            $(load_class).html(data.message);
        } else {
            $(load_class).html('<b style="color:#FF0000 !important; line-height: 110% !important;"><i class="fad fa-exclamation-triangle"></i> Note: ' + data.message + '</b>');
        }

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();
    });

}

function loadtab(tab_group_id, tab_data_id, note_in_id, owner_en_id){

    //Hide all tabs:
    $('.tab-group-'+tab_group_id).addClass('hidden');
    $('.tab-nav-'+tab_group_id).removeClass('active');

    //Show this tab:
    $('.tab-group-'+tab_group_id+'.tab-data-'+tab_data_id).removeClass('hidden');
    $('.tab-nav-'+tab_group_id+'.tab-head-'+tab_data_id).addClass('active');

    //Need to dynamically load data?
    if($('.tab-data-'+tab_data_id).find('div.dynamic-reads').length > 0){
        //Load First Page:
        read_in_history(tab_data_id, note_in_id, owner_en_id, 0);
    } else {
        //Do we need to focus on input field?
        $('#ln_content'+tab_data_id).focus();
    }

}

var algolia_index = false;
$(document).ready(function () {

    //Sync all SOURCE column widths:
    $('td.source').css('width', $('#MENCHmenu td.source').width() + 'px !important');

    //Load Algolia on Focus:
    $(".algolia_search").focus(function () {
        if(!algolia_index){
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
        }
    });


    //Navbar landing page?
    if (!$(".navbar").hasClass("no-adj")) {
        adj();
        $(window).scroll(function () {
            adj();
        });
    }

    //Load tooltips:
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });


    //Prevent search submit:
    $('#searchFrontForm').on('submit', function(e) {
        e.preventDefault();
        return false;
    });



    $("#mench_search").on('autocomplete:selected', function (event, suggestion, dataset) {

        $('#mench_search').prop("disabled", true).val('Loading...').css('background-color','#f4f5f7').css('font-size','0.8em');

        if (parseInt(suggestion.alg_obj_is_in)==1) {
            window.location = "/" + ( js_session_superpowers_assigned.includes(10939) ? 'blog/' : '' ) + suggestion.alg_obj_id;
        } else {
            window.location = "/source/" + suggestion.alg_obj_id;
        }

    }).autocomplete({minLength: 1, autoselect: true, keyboardShortcuts: ['s']}, [
        {
            source: function (q, cb) {

                //Players can filter search with first word:
                var search_only_source = $("#mench_search").val().charAt(0) == '@';
                var search_only_blog = $("#mench_search").val().charAt(0) == '#';

                //Do not search if specific command ONLY:
                if (( search_only_blog || search_only_source ) && !isNaN($("#mench_search").val().substr(1)) ) {

                    cb([]);
                    return;

                } else {

                    //Now determine the filters we need to apply:
                    var search_filters = '';

                    if(js_pl_id > 0){

                        //For Players:
                        if(search_only_source || search_only_blog){

                            if(search_only_source && js_session_superpowers_assigned.includes(10967)){

                                //Can view ALL Players:
                                search_filters += ' ( alg_obj_is_in = 0 ) ';

                            } else {

                                //Can view limited sources:
                                search_filters += ' ( alg_obj_is_in = '+( search_only_blog ? '1' : '0' )+' AND ( _tags:is_featured OR _tags:alg_author_' + js_pl_id + ' )) ';
                            }

                        } else {

                            if(js_session_superpowers_assigned.includes(10967)){

                                //no filter

                            } else {

                                //Can view limited sources:
                                search_filters += ' ( _tags:is_featured OR _tags:alg_author_' + js_pl_id + ' ) ';

                            }

                        }

                    } else {

                        //For Guests:
                        if(search_only_source || search_only_blog){

                            //Guest can search sources only with a starting @ sign
                            search_filters += ' ( alg_obj_is_in = '+( search_only_blog ? '1' : '0' )+' AND _tags:is_featured ) ';

                        } else {

                            //Guest can search blogs only by default as they start typing;
                            search_filters += ' ( alg_obj_is_in = 1 AND _tags:is_featured ) ';

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
                    return echo_js_suggestion(suggestion);
                },
                header: function (data) {
                    if(validURL(data.query)){

                        return en_fetch_canonical_url(data.query, false);

                    } else if($("#mench_search").val().charAt(0)=='#' || $("#mench_search").val().charAt(0)=='@'){

                        //See what follows the @/# sign to determine if we should create OR redirect:
                        var search_body = $("#mench_search").val().substr(1);
                        if(!isNaN(search_body)){
                            //Valid Integer, Give option to go there:
                            return '<a href="' + ( $("#mench_search").val().charAt(0)=='#' ? '/' : '/source/' ) + search_body + '" class="suggestion"><span class="icon-block-sm"><i class="far fa-level-up rotate90" style="margin: 0 5px;"></i></span>Go to ' + data.query
                        }

                    }
                },
                empty: function (data) {
                    if(validURL(data.query)){
                        return en_fetch_canonical_url(data.query, true);
                    } else if($("#mench_search").val().charAt(0)=='#'){
                        if(isNaN($("#mench_search").val().substr(1))){
                            return '<div class="not-found"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>No BLOG found</div>';
                        }
                    } else if($("#mench_search").val().charAt(0)=='@'){
                        if(isNaN($("#mench_search").val().substr(1))) {
                            return '<div class="not-found"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>No SOURCE found</div>';
                        }
                    } else {
                        return '<div class="not-found suggestion"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>No results found</div>';
                    }
                },
            }
        }
    ]);



});



//For the drag and drop file uploader:
var isAdvancedUpload = function () {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

//Main navigation
var default_nav = 'mench_nav';
var current_nav = default_nav;
function toggle_nav(load_tab){

    if(load_tab=='search_nav'){
        $('.block-logo').toggleClass('hidden');
    }

    if(current_nav==load_tab){
        load_tab = default_nav;
    }
    current_nav = load_tab;

    $('.main_nav').addClass('hidden');
    $('.'+load_tab).removeClass('hidden');
}


function toggle_search(){
    toggle_nav('search_nav');
    $('#mench_search').focus();
    $('.search_icon').toggleClass('hidden');
}


$(document).ready(function () {


    //For the S shortcut to load search:
    $("#mench_search").focus(function() {
        if(current_nav!='search_nav'){
            toggle_nav('search_nav');
        }
    });



    //Load Algolia for link replacement search
    $(".in_quick_search").on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('#'+suggestion.alg_obj_id+' '+suggestion.alg_obj_name);

    }).autocomplete({hint: false, minLength: 1}, [{

        source: function (q, cb) {
            algolia_index.search(q, {
                filters: 'alg_obj_is_in=1',
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
            return '#'+suggestion.alg_obj_id+' '+suggestion.alg_obj_name;
        },
        templates: {
            suggestion: function (suggestion) {
                return echo_js_suggestion(suggestion);
            },
            empty: function (data) {
                return 'No blogs found';
            },
        }

    }]);



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

});


function add_feedback(){
    alert('This feature will be released soon.');
}

function modify_cancel(){
    $('.fixed-box').addClass('hidden');
    remove_all_highlights();
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
        if(searchdata.status && searchdata.url_already_existed){
            //URL was detected via PHP, update the search results:
            $('.add-source-suggest').remove();
            $('.not-found').html('<a href="/source/'+searchdata.algolia_object.alg_obj_id+'" class="suggestion">' + echo_js_suggestion(searchdata.algolia_object)+'</a>');
        }
    });

    //We did not find the URL:
    return ( not_found ? '<div class="not-found"><i class="fad fa-exclamation-triangle"></i> URL not found</div>' : '');
}


function remove_all_highlights(){
    $('.object_highlight').removeClass('en_highlight');
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
    //See if we already have a list in place?
    if ($("#" + sort_list_id + " " + sort_handler).length > 0) {
        //yes we do! add this:
        $("#" + sort_list_id + " " + sort_handler + ":last").after(html_content);
    } else {
        //Raw list, add before input filed:
        $("#" + sort_list_id).prepend(html_content);
    }
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


function toggle_superpower(superpower_id){

    superpower_id = parseInt(superpower_id);

    var superpower_icon = $('.superpower-frame-'+superpower_id).html();
    $('.superpower-frame-'+superpower_id).html('<i class="far fa-yin-yang fa-spin"></i>');

    //Save session variable to save the state of advance setting:
    $.post("/source/toggle_superpower/"+superpower_id, {}, function (data) {

        //Change top menu icon:
        $('.superpower-frame-'+superpower_id).html(superpower_icon);

        if(!data.status){

            alert('Note: ' + data.message);

        } else {

            //Toggle UI elements:
            $('.superpower-'+superpower_id).toggleClass('hidden');

            //Change top menu icon:
            $('.superpower-frame-'+superpower_id).toggleClass('active');

            //TOGGLE:
            var index = js_session_superpowers_assigned.indexOf(superpower_id);
            if (index > -1) {
                //Remove it:
                js_session_superpowers_assigned.splice(index, 1);
            } else {
                //Not there, add it:
                js_session_superpowers_assigned.push(superpower_id);
            }
        }
    });

}


function ln_content_word_count(el_textarea, el_counter) {
    var len = $(el_textarea).val().length;
    if (len > js_en_all_6404[11073]['m_desc']) {
        $(el_counter).addClass('overload').text(len);
    } else {
        $(el_counter).removeClass('overload').text(len);
    }
}