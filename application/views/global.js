

//Microsoft Clarity:
/*
(function(c,l,a,r,i,t,y){
    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
})(window, document, "clarity", "script", "59riunqvfm");
*/

//Google Analytics
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'UA-92774608-1');


//Full Story
if(js_pl_id > 1){ //Any user other than Shervin
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

    if(parseInt(js_e___6404[12678]['m__message'])){
        $('.e_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('@' + suggestion.s__id + ' ' + suggestion.s__title);

        }).autocomplete({hint: false, minLength: 2}, [{

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
                    return view_s_js(suggestion);
                },
                empty: function (data) {
                    return '<div class="not-found css__title"><i class="fas fa-exclamation-circle"></i> No Sources Found</div>';
                },
            }
        }]);

        $('.i_text_search').on('autocomplete:selected', function (event, suggestion, dataset) {

            $(this).val('#' + suggestion.s__id + ' ' + suggestion.s__title);

        }).autocomplete({hint: false, minLength: 2}, [{

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
                    return view_s_js(suggestion);
                },
                empty: function (data) {
                    return '<div class="not-found css__title"><i class="fas fa-exclamation-circle"></i> No Ideas Found</div>';
                },
            }
        }]);

    }
}


function view_s__title(suggestion){
    return htmlentitiesjs( suggestion._highlightResult && suggestion._highlightResult.s__title.value ? suggestion._highlightResult.s__title.value : suggestion.s__title );
}


function view_s_js(suggestion){
    return '<span class="icon-block">'+ view_cover_js(suggestion.s__type, suggestion.s__cover) +'</span><span class="css__title">' + view_s__title(suggestion) + '</span><span class="grey">&nbsp;' + ( suggestion.s__type==12273 ? '/' : '@' ) + suggestion.s__id + '</span>';
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

    //If it has any:
    autosize.update($(".tab-data-" + tab_data_id + " textarea"));
    $(".tab-data-" + tab_data_id + " .power_editor").focus();

    //Focus on potential input field if any:
    //$('.input_note_'+tab_data_id).focus();

}

function lazy_load(){
    //Lazyload photos:
    var lazyLoadInstance = new LazyLoad({
        elements_selector: "img.lazyimage"
    });
}


function init_remove(){
    $(".x_remove").click(function(event) {

        event.preventDefault();

        var i__id = $(this).attr('i__id');
        var x__id = $(this).attr('x__id');
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


function i_note_poweredit_has_changed(note_type_id){
    var text_editor = $('.input_note_'+note_type_id).val().trim();
    var text_preview = $('#current_text_'+note_type_id).text().trim();
    return  text_editor != text_preview;
}

function i_note_poweredit_has_text(note_type_id){
    return $('.input_note_'+note_type_id).val().trim().length > 0;
}

function revert_poweredit(){
    if($('.tab-data-14420').hasClass('hidden') && !i_note_poweredit_has_changed(4231) && i_note_poweredit_has_text(4231)){
        loadtab(14418, 14420); //Load Preview tab
    }
}

function x_create(add_fields){
    return $.post("/x/x_create", add_fields);
}

function fallbackCopyTextToClipboard(text) {
    var textArea = document.createElement("textarea");
    textArea.value = text;

    // Avoid scrolling to bottom
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        var successful = document.execCommand('copy');
        var msg = successful ? 'successful' : 'unsuccessful';
        console.log('Fallback: Copying text command was ' + msg);
    } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
    }

    document.body.removeChild(textArea);
}
function copyTextToClipboard(text) {

    if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text);
        return;
    }

    navigator.clipboard.writeText(text).then(function() {
        console.log('Async: Copying to clipboard was successful!');

        var affirm_text = '✅ COPIED';
        if($('.was_copied').text() != affirm_text){

            $('.was_copied').text(affirm_text);

            //Save Once:
            x_create({
                x__source: js_pl_id,
                x__type: 14732, //COPIED
                x__message: text,
            });

        }
        $('.was_copied').hide().fadeIn();


    }, function(err) {
        console.error('Async Error: Could not copy text: ', err);
        x_create({
            x__source: js_pl_id,
            x__type: 4246, //BUG
            x__message: 'Async Error: Could not copy text: ' + text,
        });
    });
}

function load_coin_count(){
    $.post("/x/load_coin_count", {}, function (data) {
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

var algolia_index = false;
$(document).ready(function () {


    if ($(".list-coins")[0]){
        //Update mench coins every 3 seconds:
        $(function () {
            setInterval(load_coin_count, js_e___6404[14874]['m__message']);
        });
    }

    //Lookout for textinput updates
    x_set_start_text();


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
    watch_for_coin_cover_clicks();

    $('.trigger_modal').click(function (e) {
        var note_type_id = parseInt($(this).attr('note_type_id'));
        $('#modal'+note_type_id).modal('show');
        x_create({
            x__source: js_pl_id,
            x__type: 14576, //MODAL VIEWED
            x__up: note_type_id,
        });
        //Log Viewed Transaction
        if(note_type_id==14393){
            //Current
            $('.current_url').text(window.location.href);
        } else if(note_type_id==6287){
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
    $(".algolia_search").focus(function () {
        if(!algolia_index && parseInt(js_e___6404[12678]['m__message'])){
            //Loadup Algolia once:
            client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
            algolia_index = client.initIndex('alg_index');
        }
    });


    //General ESC cancel
    $(document).keyup(function (e) {
        //Watch for action keys:
        if (e.keyCode === 27) { //ESC

            if(search_on){
                toggle_search();
            }

            revert_poweredit();

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

        $("#top_search").on('autocomplete:selected', function (event, suggestion, dataset) {

            $('#top_search').val('Loading...');

            window.location = suggestion.s__url;

        }).autocomplete({minLength: 1, autoselect: true, keyboardShortcuts: ['s']}, [
            {
                source: function (q, cb) {

                    //Members can filter search with first word:
                    var search_only_e = $("#top_search").val().charAt(0) == '@';
                    var search_only_in = $("#top_search").val().charAt(0) == '#';

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
                        return view_s_js(suggestion);
                    },
                    header: function (data) {
                        if(validURL(data.query)){

                            return e_fetch_canonical(data.query, false);

                        } else if($("#top_search").val().charAt(0)=='#' || $("#top_search").val().charAt(0)=='@'){

                            //See what follows the @/# sign to determine if we should create OR redirect:
                            var search_body = $("#top_search").val().substr(1);
                            if(!isNaN(search_body)){
                                //Valid Integer, Give option to go there:
                                return '<a href="' + ( $("#top_search").val().charAt(0)=='#' ? '/i/i_go/' : '/@' ) + search_body + '" class="suggestion css__title"><span class="icon-block"><i class="far fa-level-up rotate90" style="margin: 0 5px;"></i></span>Go to ' + data.query
                            }

                        }
                    },
                    footer: function (data) {
                        //return '<div class="suggestion" style="text-align: right;">Search Powered by Algolia<span class="icon-block"><i class="fab fa-algolia" style="margin: 0 5px;"></i></span></div>';
                    },
                    empty: function (data) {
                        if(validURL(data.query)){
                            return e_fetch_canonical(data.query, true);
                        } else if($("#top_search").val().charAt(0)=='#'){
                            if(isNaN($("#top_search").val().substr(1))){
                                return '<div class="not-found css__title"><span class="icon-block-xs"><i class="fas fa-exclamation-circle"></i></span>No IDEA found</div>';
                            }
                        } else if($("#top_search").val().charAt(0)=='@'){
                            if(isNaN($("#top_search").val().substr(1))) {
                                return '<div class="not-found css__title"><span class="icon-block-xs"><i class="fas fa-exclamation-circle"></i></span>No SOURCE found</div>';
                            }
                        } else {
                            return '<div class="not-found suggestion css__title"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No results found</div>';
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
        return '<img src="/img/'+coin__type+'.png" />';
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
            set_autosize($('#x__message'));
            x_type_preview();
            setTimeout(function () {
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
    $('#coin__title').val('LOADING...');
    $('#coin__cover').val('LOADING...');

    $.post("/e/coin__load", {
        coin__type: coin__type,
        coin__id: coin__id
    }, function (data) {

        if (data.status) {

            $('#coin__type').val(coin__type);
            $('#coin__id').val(coin__id);
            $('#coin__title').val(data.coin__title);
            $('#coin__cover').val(data.coin__cover);
            update_cover_main($('#coin__cover').val(), '.demo_cover');

        } else {

            //Ooops there was an error!
            alert(data.message);

        }

    });

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

function e_remove(x__id, note_type_id) {

    var r = confirm("Remove this source?");
    if (r == true) {
        $.post("/e/e_remove", {

            x__id: x__id,

        }, function (data) {
            if (data.status) {

                i_note_counter(note_type_id, -1);
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


function e_nuclear_delete(e__id, note_type_id) {

    var confirm_removal = prompt("Nuclear Delete Source and all its related transactions? This cannot be undone! Type \"nuclear\" to confirm.", "");
    if (!(confirm_removal=='nuclear')) {

        //Abandon process:
        alert('Source will not be deleted.');

    } else {

        //Delete Source
        $.post("/e/e_nuclear_delete", {
            e__id: e__id,
        }, function (data) {
            if (data.status) {

                console.log(data.message);
                i_note_counter(note_type_id, -1);
                $(".coin___12274_" + e__id).fadeOut();
                setTimeout(function () {
                    $(".coin___12274_" + e__id).remove();
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

        //Switch to Menu:
        search_on = false; //Reverse
        $('.top_nav').removeClass('hidden');

    } else {

        //Turn Search On:
        search_on = true; //Reverse
        $('.search_nav').removeClass('hidden');

        //Focus:
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


function e_fetch_canonical(query_string, not_found){

    //Do a call to PHP to fetch canonical URL and see if that exists:
    $.post("/e/e_fetch_canonical", { search_url:query_string }, function (searchdata) {
        if(searchdata.status && searchdata.url_previously_existed){
            //URL was detected via PHP, update the search results:
            $('.add-e-suggest').remove();
            $('.not-found').html('<a href="/@'+searchdata.suggestion.s__id+'" class="suggestion css__title">' + view_s_js(searchdata.suggestion)+'</a>');
        }
    });

    //We did not find the URL:
    return ( not_found ? '<div class="not-found css__title"><i class="fas fa-exclamation-circle"></i> URL not found</div>' : '');
}


function validURL(str) {
    return str && str.length && str.substring(0, 4)=='http';
}


function add_to_list(sort_list_id, sort_handler, html_content, add_to_start) {

    //See if we previously have a list in place?
    if ($("#" + sort_list_id + " " + sort_handler).length > 0) {
        if(add_to_start){
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




function i_load_search(x__type) {

    if(!parseInt(js_e___6404[12678]['m__message'])){
        alert('Search is currently disabled');
        return false;
    } else if(!js_n___14685.includes(x__type)){
        alert('Idea Type not supported to be added');
        return false;
    }


    $('.new-list-'+x__type+' .add-input').focus(function() {

        $('.new-list-'+x__type+' .algolia_pad_search').removeClass('hidden');

    }).focusout(function() {

        $('.new-list-'+x__type+' .algolia_pad_search').addClass('hidden');

    }).keypress(function (e) {

        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            e.preventDefault();
            return i_add(x__type, 0, $('#focus__id').val());
        }

    }).on('autocomplete:selected', function (event, suggestion, dataset) {

        i_add(x__type, suggestion.s__id);

    }).autocomplete({hint: false, minLength: 1, keyboardShortcuts: [js_e___14685[x__type]['m__message']]}, [{
        source: function (q, cb) {

            algolia_index.search(q, {

                filters: ' s__type=12273 ' + ( superpower_js_12701 ? '' : ' AND ( _tags:is_featured ' + ( js_pl_id > 0 ? 'OR _tags:alg_e_' + js_pl_id : '' ) + ') ' ),
                hitsPerPage:21,

            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });

        },
        displayKey: function (suggestion) {
            return ""
        },
        templates: {
            suggestion: function (suggestion) {
                return view_s_js(suggestion);
            },
            header: function (data) {
                return '<a href="javascript:void(0);" onclick="i_add('+x__type+',0)" class="suggestion css__title"><span class="icon-block"><i class="fas fa-plus-circle zq12273 add-plus"></i></span><b>Create "' + data.query + '"</b></a>';
            },
            empty: function (data) {
                return '';
            },
        }
    }]);

}

function images_modal(x__type){
    x_create({
        x__source: js_pl_id,
        x__type: 14576, //MODAL VIEWED
        x__up: 14073,
        x__right: $('#focus__id').val(),
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



function cover_search(){
    q = encodeURI($('.cover_query').val());
}

var current_q = '';
function images_search(){

    q = encodeURI($('.images_query').val());

    if(q==current_q){
        return false;
    }

    current_q = q;
    var x__type = $('#modal_x__type').val();
    $('.new_images').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>').hide().fadeIn();
    $.get({
        url: js_e___6404[6293]['m__message']+current_q,
        success: function(result) {
            var data = result.data;
            var output = "";
            var counter = 0;
            for (var index in data){
                counter++;
                var gifObject = data[index];
                output += "<div class=\"gif-col col-4\"><a href=\"javascript:void(0);\" onclick=\"images_add("+x__type+",'"+gifObject.id+"','"+gifObject.title.replace("'",'')+"')\"><img src='/img/logos/"+base_source+".svg' alt='GIF' class='lazyimage' data-src='https://media"+parseInt(Math.fmod(counter, 5))+".giphy.com/media/"+gifObject.id+"/200w.gif' /></a></div>";
                if(!Math.fmod(counter, 3)){
                    //output += "</div><div class=\"row\">";
                }
            }

            //Did we find anything?
            if(output.length){
                output = "<div style=\"margin:5px 0;\">Tap the GIF you want to add:</div><div class=\"row\">"+output+"</div>";
            } else {
                //No results found:
                output = "<div style=\"margin:5px 0;\">No GIFs found</div>";
            }
            $(".new_images").html(output);
            lazy_load();
        },
        error: function(error) {
            console.log(error);
        }
    });

}

function images_add(note_type_id, giphy_id, giphy_title){

    var current_value = $('.input_note_' + note_type_id).val();
    $('#modal14073').modal('hide');
    $('.input_note_' + note_type_id).val(( current_value.length ? current_value+"\n\n" : '' ) + 'https://media.giphy.com/media/'+giphy_id+'/giphy.gif?e__title='+encodeURI(giphy_title));

    //Save or Submit:
    if(js_n___14311.includes(note_type_id)){
        //Power Editor:
        i_note_poweredit_save(note_type_id);
    } else {
        //Regular Editor:
        i_note_add_text(note_type_id);
    }
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

        var note_type_id = parseInt($(this).attr('note_type_id'));

        //Initiate @ search for all idea text areas:
        i_note_e_search($(this));

        set_autosize($(this));

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
        $('.regular_editor').keydown(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (e.ctrlKey && code== 13) {
                i_note_add_text($(this).attr('note_type_id'));
            }
        });

        //Watchout for file uplods:
        $('.box' + note_type_id).find('input[type="file"]').change(function () {
            i_note_add_file(droppedFiles, 'file', note_type_id);
        });


        //Should we auto start?
        if (isAdvancedUpload) {

            $('.box' + note_type_id).addClass('has-advanced-upload');
            var droppedFiles = false;

            $('.box' + note_type_id).on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $('.power-editor-' + note_type_id+', .tab-data-'+ note_type_id).addClass('dynamic_saving');
            })
                /*
            .on('dragover dragenter', function () {
                $('.power-editor-' + note_type_id+', .tab-data-'+ note_type_id).addClass('dynamic_saving');
            })
            .on('dragleave dragend drop', function () {
                $('.power-editor-' + note_type_id+', .tab-data-'+ note_type_id).removeClass('dynamic_saving');
            })
                */
            .on('drop', function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                e.preventDefault();
                i_note_add_file(droppedFiles, 'drop', note_type_id);
                $('.power-editor-' + note_type_id+', .tab-data-'+ note_type_id).removeClass('dynamic_saving');
            });
        }

    });
}

function i_note_counter(note_type_id, adjustment_count){
    var current_count = parseInt( $('.en-type-counter-'+note_type_id).text().length ? $('.en-type-counter-'+note_type_id).text() : 0 );
    var new_count = current_count + adjustment_count;
    $('.en-type-counter-'+note_type_id).text(new_count);
}

function i_note_count_new(note_type_id) {

    //Update count:
    var len = $('.input_note_' + note_type_id).val().length;
    if (len > js_e___6404[4485]['m__message']) {
        $('#charNum' + note_type_id).addClass('overload').text(len);
    } else {
        $('#charNum' + note_type_id).removeClass('overload').text(len);
    }

    //Only show counter if getting close to limit:
    if(len > ( js_e___6404[4485]['m__message'] * js_e___6404[12088]['m__message'] )){
        $('#ideaNoteNewCount' + note_type_id).removeClass('hidden');
    } else {
        $('#ideaNoteNewCount' + note_type_id).addClass('hidden');
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
                search: function (query, callback) {
                    algolia_index.search(query, {
                        hitsPerPage: 8,
                        filters: 's__type=12274',
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
                    return view_s_js(suggestion);
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

function i_note_sort_apply(note_type_id) {

    var new_x__spectrums = [];
    var sort_rank = 0;
    var this_x__id = 0;

    $(".msg_e_type_" + note_type_id).each(function () {
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

function i_note_sort_load(note_type_id) {


    var sotrable_div = document.getElementById("i_notes_list_" + note_type_id);
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
            i_note_sort_apply(note_type_id);
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

function i_note_update_text(x__id, note_type_id) {

    //Revert View:
    cancel_13574(x__id);

    //Clear Message:
    $("#ul-nav-" + x__id + " .edit-updates").html('');

    var modify_data = {
        x__id: parseInt(x__id),
        i__id: parseInt($('#focus__id').val()),
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


function i_remove_note(x__id, note_type_id){

    var r = confirm("Remove this note?");
    if (r == true) {
        //REMOVE NOTE
        $.post("/i/i_remove_note", { x__id: parseInt(x__id) }, function (data) {
            if (data.status) {

                i_note_counter(note_type_id, -1);

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

function i_note_start_adding(note_type_id) {
    $('.save_notes_' + note_type_id).html('<i class="far fa-yin-yang fa-spin"></i>').attr('href', '#');
    $('.no_notes_' + note_type_id).remove();
    $('.remove_loading').hide();
}


function i_note_end_adding(result, note_type_id) {

    //Update UI to unlock:
    $('.save_notes_' + note_type_id).html(js_e___11035[14421]['m__cover']).attr('href', 'javascript:i_note_add_text('+note_type_id+');');
    $('.input_note_' + note_type_id).removeClass('dynamic_saving').focus();
    $('.remove_loading').fadeIn();

    //What was the result?
    if (result.status) {

        //Append data:
        $(result.message).insertBefore( ".add_notes_" + note_type_id );

        //Tooltips:
        $('[data-toggle="tooltip"]').tooltip();

        //Load Images:
        lazy_load();

        watch_for_coin_cover_clicks();

        //Hide any errors:
        $(".note_error_"+note_type_id).html('');

    } else {

        $(".note_error_"+note_type_id).html('<span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>'+result.message);

    }
}

function i_note_add_file(droppedFiles, uploadType, note_type_id) {

    //Prevent multiple concurrent uploads:
    if ($('.box' + note_type_id).hasClass('dynamic_saving') || !isAdvancedUpload) {
        return false;
    }

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
    ajaxData.append('i__id', $('#focus__id').val());
    ajaxData.append('note_type_id', note_type_id);

    $.ajax({
        url: '/i/i_note_add_file',
        type: $('.box' + note_type_id).attr('method'),
        data: ajaxData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        complete: function () {
            $('.box' + note_type_id).removeClass('dynamic_saving');
        },
        success: function (data) {

            if(!data.status){

                alert('ERROR: '+data.message);

            } else {

                if(js_n___14311.includes(note_type_id)){
                    //Power Editor:
                    var current_value = $('.input_note_' + note_type_id).val();
                    $('.input_note_' + note_type_id).val(( current_value.length ? current_value+"\n\n" : '' ) + data.new_source);
                } else {
                    //Regular Editor:
                    i_note_counter(note_type_id, +1);
                }

                //Adjust icon again:
                $('.file_label_' + note_type_id).html('<span class="icon-block">'+js_e___11035[13572]['m__cover']+'</span>');
            }

            if(js_n___14311.includes(note_type_id)){
                i_note_poweredit_save(note_type_id);
            } else {
                i_note_end_adding(data, note_type_id);
            }

        },
        error: function (data) {
            var result = [];
            result.status = 0;
            result.message = data.responseText;
            i_note_end_adding(result, note_type_id);
        }
    });

}

function watch_for_coin_cover_clicks(){
    //Watchout for source clicks if they have the superpower:
    $('.trigger_coincover_edit').click(function (e) {
        coin__load(parseInt($(this).attr('coin__type')), parseInt($(this).attr('coin__id')));
    });
}

var currentlu_adding = false;
function i_note_add_text(note_type_id) {

    if(currentlu_adding){
        return false;
    }
    currentlu_adding = true;

    //Lock message:
    i_note_start_adding(note_type_id);

    //Update backend:
    $.post("/i/i_note_add_text", {

        i__id: $('#focus__id').val(), //Synonymous
        x__message: $('.input_note_' + note_type_id).val(),
        note_type_id: note_type_id,

    }, function (data) {

        //Raw Inputs Fields if success:
        if (data.status) {

            //Reset input field:
            $('.input_note_' + note_type_id).val("");
            set_autosize($('.input_note_' + note_type_id));

            i_note_count_new(note_type_id);
            i_note_counter(note_type_id, +1);

        }

        //Unlock field:
        i_note_end_adding(data, note_type_id);

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

    var element_key = null;
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
                var x_id = parseInt($(this).attr('x__id'));
                if(x_id > 0){
                    sort_rank++;
                    new_x_order[sort_rank] = x_id;
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

function e_radio(parent_e__id, selected_e__id, enable_mulitiselect){

    var was_previously_selected = ( $('.radio-'+parent_e__id+' .item-'+selected_e__id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_previously_selected){
        //Nothing to do here:
        return false;
    }

    //Updating Font?
    if(js_n___13890.includes(parent_e__id)){
        current_focus = parent_e__id;
        $('body').removeClass('custom_ui_'+parent_e__id+'_');
        window['js_n___'+parent_e__id].forEach(remove_ui_class); //Removes all Classes
        $('body').addClass('custom_ui_'+parent_e__id+'_'+selected_e__id);
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+parent_e__id+' .item-'+selected_e__id+' .change-results';
    var initial_icon = $(notify_el).html();
    $(notify_el).html('<i class="far fa-yin-yang fa-spin"></i>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+parent_e__id+' .list-group-item').removeClass('active');
    }

    //Enable currently selected:
    if(enable_mulitiselect && was_previously_selected){
        $('.radio-'+parent_e__id+' .item-'+selected_e__id).removeClass('active');
    } else {
        $('.radio-'+parent_e__id+' .item-'+selected_e__id).addClass('active');
    }

    $.post("/e/e_radio", {
        parent_e__id: parent_e__id,
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
    if(element_id==4737 && !(new_e__id in js_e___7356)){
        //Deleting Idea:
        var r = confirm("Are you sure you want to delete this idea and unlink it from all other coins?");
        if (r == false) {
            return false;
        }
    } else if(element_id==6177 && !(new_e__id in js_e___7358)){
        //Deleting Source:
        var r = confirm("Are you sure you want to delete this source and unlink it from all other coins?");
        if (r == false) {
            return false;
        }
    }




    //Show Loading...
    var data_object = eval('js_e___'+element_id);
    if(!data_object[new_e__id]){
        alert('Invalid element ID');
        return false;
    }
    $('.dropd_'+element_id+'_'+o__id+'_'+x__id+' .btn').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><b class="css__title">'+ ( show_full_name ? 'SAVING...' : '' ) +'</b>');

    $.post("/x/update_dropdown", {
        focus__id:$('#focus__id').val(),
        o__id: o__id,
        element_id: element_id,
        new_e__id: new_e__id,
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

            if(element_id==4486){
                $('.cover_x_'+x__id+' .x_marks').addClass('hidden');
                $('.cover_x_'+x__id+' .account_' + new_e__id).removeClass('hidden');
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



var i_is_adding = false;
function i_add(x__type, link_i__id) {

    /*
     *
     * Either creates an IDEA transaction between focus__id & link_i__id
     * OR will create a new idea based on input text and then transaction it
     * to $('#focus__id').val() (In this case link_i__id=0)
     *
     * */

    if(i_is_adding){
        return false;
    }

    i_is_adding = true;
    var sort_handler = ".coin_cover";
    var sort_list_id = "list-in-" + x__type;
    var input_field = $('.new-list-'+x__type+' .add-input');
    var i__title = input_field.val();


    //We either need the idea name (to create a new idea) or the link_i__id>0 to create an IDEA transaction:
    if (!link_i__id && i__title.length < 1) {
        alert('Missing Idea Title');
        input_field.focus();
        return false;
    }

    //Set processing status:
    input_field.addClass('dynamic_saving').prop("disabled", true);
    add_to_list(sort_list_id, sort_handler, '<div id="tempLoader" class="col-md-4 col-6 no-padding show_all_ideas"><div class="cover-wrapper"><div class="black-background cover-link"><div class="cover-btn"><i class="far fa-yin-yang fa-spin"></i></div></div></div></div>', js_n___14686.includes(x__type));


    //Update backend:
    $.post("/i/i_add", {
        x__type: x__type,
        focus__id: $('#focus__id').val(),
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
            add_to_list(sort_list_id, sort_handler, data.new_i_html, js_n___14686.includes(x__type));

            //Lookout for textinput updates
            x_set_start_text();
            set_autosize($('.texttype__lg'));

        } else {
            //Show errors:
            alert(data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}
