
//For the drag and drop file uploader:
var isAdvancedUpload = function () {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();


//In milli seconds:
var fadeout_frequency = 5000;
var fadeout_speed = 21;
var updating_basic_stats = false;
var searchbar_loaded = 0;
function load_searchbar(){
    if(searchbar_loaded){
        return false;
    }

    searchbar_loaded = 1; //Indicate as loaded
    $('.search-toggle').toggleClass('hidden');
    $('.algolia_search').focus();
}


$(document).ready(function () {

    //Update stats on load:
    update_basic_stats();

    //Continue updating basic stats:
    setInterval(update_basic_stats, fadeout_frequency);


    $("#mench_search").focus(function() {
        if(!searchbar_loaded){
            load_searchbar();
        }
    });

    //Watch typing:
    $(document).keyup(function (e) {
        //Watch for action keys:
        if (e.keyCode === 27) { //ESC
            modify_cancel();
        }
    });

    $("#platform_search").on('autocomplete:selected', function (event, suggestion, dataset) {

        if (parseInt(suggestion.alg_obj_is_in)==1) {
            window.location = "/blog/" + suggestion.alg_obj_id;
        } else {
            window.location = "/play/" + suggestion.alg_obj_id;
        }

    }).autocomplete({hint: false, minLength: 1, autoselect: true, keyboardShortcuts: ['s']}, [
        {
            source: function (q, cb) {
                //Do not search if specific command:
                if (($("#platform_search").val().charAt(0) == '#' || $("#platform_search").val().charAt(0) == '@') && !isNaN($("#platform_search").val().substr(1))) {
                    cb([]);
                    return;
                } else {

                    //Append filters:
                    algolia_index.search(q, {
                        hitsPerPage: 14,
                        filters:' alg_obj_is_in' + ($("#platform_search").val().charAt(0) == '#' ? '=1' : ($("#platform_search").val().charAt(0) == '@' ? '=0' : '>=0')),
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
                    return echo_js_suggestion(suggestion, 1, 0);
                },
                header: function (data) {
                    if(validURL(data.query)){
                        return en_fetch_canonical_url(data.query, false);
                    } else if($("#platform_search").val().charAt(0)=='#' || $("#platform_search").val().charAt(0)=='@'){
                        //See what follows the @/# sign to determine if we should create OR redirect:
                        var search_body = $("#platform_search").val().substr(1);
                        if(isNaN(search_body)){
                            //NOT a valid number, give option to create:
                            return '<a href="javascript:add_search_item()" class="suggestion"><i class="fas fa-plus-circle" style="margin: 0 5px;"></i> Create ' + data.query + '</a>';
                        } else {
                            //Valid Integer, Give option to go there:
                            return '<a href="/' + ( $("#platform_search").val().charAt(0)=='#' ? 'intents' : 'entities' ) + '/' + search_body + '" class="suggestion"><i class="far fa-level-up rotate90" style="margin: 0 5px;"></i> Go to ' + data.query
                        }
                    }
                },
                empty: function (data) {
                    if(validURL(data.query)){
                        return en_fetch_canonical_url(data.query, true);
                    } else if($("#platform_search").val().charAt(0)=='#'){
                        if(isNaN($("#platform_search").val().substr(1))){
                            return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No BLOG found</div>';
                        }
                    } else if($("#platform_search").val().charAt(0)=='@'){
                        if(isNaN($("#platform_search").val().substr(1))) {
                            return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No PLAY found</div>';
                        }
                    } else {
                        return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No BLOG/PLAY found</div>';
                    }
                },
            }
        }
    ]);



    $('#searchForm').on('submit', function(e) {
        //Only redirect if matching criteria:
        if(($("#platform_search").val().charAt(0)=='#' || $("#platform_search").val().charAt(0)=='@') && !isNaN($("#platform_search").val().substr(1))){
            window.location = '/' + ( $("#platform_search").val().charAt(0)=='#' ? 'intents' : 'entities' ) + '/' + $("#platform_search").val().substr(1);
        } else {
            alert('No search results found');
            e.preventDefault();
        }
        return false;
    });


    //Load Algolia for link replacement search
    $(".in_quick_search").on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('#'+suggestion.alg_obj_id+' '+suggestion.alg_obj_name);

    }).autocomplete({hint: false, minLength: 2}, [{

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
                return echo_js_suggestion(suggestion, 0, 0);
            },
            empty: function (data) {
                return 'No intents found';
            },
        }

    }]);



    $('#topnav li a').click(function (event) {
        event.preventDefault();
        var hash = $(this).attr('href').replace('#', '');
        window.location.hash = hash;

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


//Update page count stats & refresh them visually once they change:
var update_basic_stats = function() {
    //your jQuery ajax code

    if(updating_basic_stats){
        return false;
    }

    //Now we're updating:
    updating_basic_stats = true;

    //Fetch latest stats:
    $.post("/play/update_counters", {}, function (data) {

        if(data.intents.current_count != $('.blog .current_count').html()){
            $('.blog .current_count').html(data.intents.current_count).fadeOut(fadeout_speed).fadeIn(fadeout_speed);
        }
        if(data.entities.current_count != $('.play .current_count').html()){
            $('.play .current_count').html(data.entities.current_count).fadeOut(fadeout_speed).fadeIn(fadeout_speed);
        }
        if(data.links.current_count != $('.read .current_count').html()){
            $('.read .current_count').html(data.links.current_count).fadeOut(fadeout_speed).fadeIn(fadeout_speed);
        }

        updating_basic_stats = false;
    });

};


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
    $.post("/play/en_fetch_canonical_url", { search_url:query_string }, function (searchdata) {
        if(searchdata.status && searchdata.url_already_existed){
            //URL was detected via PHP, update the search results:
            $('.add-source-suggest').remove();
            $('.not-found').html('<a href="/play/'+searchdata.algolia_object.alg_obj_id+'" class="suggestion">' + echo_js_suggestion(searchdata.algolia_object, 1, 0)+'</a>');
        }
    });

    //We did not find the URL, offer them option to add it:
    return '<a href="/play/add_source_wizard?url='+ encodeURI(query_string) +'" class="suggestion add-source-suggest"><i class="fas fa-plus-circle" style="margin: 0 5px;"></i> Add Source Wizard</a>'
        + ( not_found ? '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> URL not found</div>' : '');
}


function remove_all_highlights(){
    $('.object_highlight').removeClass('in_highlight').removeClass('en_highlight');
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
    $.post("/read/toggle_superpower/"+superpower_id, {}, function (data) {

        //Change top menu icon:
        $('.superpower-frame-'+superpower_id).html(superpower_icon);

        if(!data.status){

            alert('Error: ' + data.message);

        } else {

            //Toggle UI elements:
            $('.superpower-'+superpower_id).toggleClass('hidden');

            //Change top menu icon:
            $('.superpower-frame-'+superpower_id).toggleClass('active');

            //TOGGLE:
            var index = js_active_superpowers.indexOf(superpower_id);
            if (index > -1) {
                //Remove it:
                js_active_superpowers.splice(index, 1);
            } else {
                //Not there, add it:
                js_active_superpowers.push(superpower_id);
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

function add_search_item(){

    //Lock search bar:
    $('#platform_search').prop("disabled", true);

    //Attemps to create a new intent OR entity based on the value in the search box
    $.post("/read/add_search_item", { raw_string: $("#platform_search").val() }, function (data) {

        if(data.status){

            setTimeout(function () {
                //All good, redirect to newly added intent/entity:
                window.location = data.new_item_url;
            }, 377);

        } else {

            //We had some error:
            $('#platform_search').prop("disabled", false);
            alert('ERROR: ' + data.message);

        }
    });
}

