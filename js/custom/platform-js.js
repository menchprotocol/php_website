
//For the drag and drop file uploader:
var isAdvancedUpload = function () {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();


var blur_opacity = '0.21';

function modify_cancel(){
    $('.fixed-box').addClass('hidden');
    reset_opacity();
    $("input").blur();
    if(history.pushState) {
        history.pushState(null, null, '#');
    } else {
        location.hash = '#';
    }
}


//Function to load all help messages throughout the platform:
$(document).ready(function () {

    //Watch typing:
    $(document).keyup(function (e) {
        //Watch for action keys:
        if (e.keyCode === 27) {
            modify_cancel();
        }
    });

    load_js_algolia();

    $("#platform_search").on('autocomplete:selected', function (event, suggestion, dataset) {

        if (parseInt(suggestion.alg_obj_is_in)==1) {
            window.location = "/intents/" + suggestion.alg_obj_id;
        } else {
            window.location = "/entities/" + suggestion.alg_obj_id;
        }

    }).autocomplete({hint: false, minLength: 2, autoselect: true, keyboardShortcuts: ['s']}, [
        {
            source: function (q, cb) {

                //Append filters:
                algolia_index.search(q, {
                    hitsPerPage: 14,
                    filters: 'alg_obj_is_in ' + ( $("#platform_search").val().charAt(0)=='#' ? '=1' : ( $("#platform_search").val().charAt(0)=='@' ? '=0' : '>=0' ) ),
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
                    return echo_js_suggestion(suggestion, 1);
                },
                header: function (data) {
                    if($("#platform_search").val().charAt(0)=='#' || $("#platform_search").val().charAt(0)=='@'){
                        return '<a href="javascript:add_search_item()" class="suggestion"><i class="fal fa-plus-circle" style="margin: 0 5px;"></i> Create ' + data.query + '</a>';
                    }
                },
                empty: function (data) {


                    if(validURL(data.query)){

                        //Do a call to PHP to fetch canonical URL and see if that exists:
                        $.post("/entities/en_fetch_canonical_url", { search_url:data.query }, function (searchdata) {
                            if(searchdata.status && searchdata.url_already_existed){
                                //URL was detected via PHP, update the search results:
                                $('.add-source-suggest').remove();
                                $('.not-found').html('<a href="/entities/'+searchdata.algolia_object.alg_obj_id+'" class="suggestion">' + echo_js_suggestion(searchdata.algolia_object, 1)+'</a>');
                            }
                        });

                        //We did not find the URL, offer them option to add it:
                        return '<a href="/entities/add_source_wizard?url='+ encodeURI(data.query) +'" class="suggestion add-source-suggest"><i class="fal fa-plus-circle" style="margin: 0 5px;"></i> Add Source Wizard</a>'
                            + '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> URL not found</div>';

                    } else if($("#platform_search").val().charAt(0)=='#'){
                        return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No intents found</div>';
                    } else if($("#platform_search").val().charAt(0)=='@'){
                        return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No entities found</div>';
                    } else {
                        return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No intents/entities found</div>';
                    }
                },
            }
        }
    ]);



    //Load Algolia for link replacement search
    $(".in_quick_search").on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('#'+suggestion.alg_obj_id+' '+suggestion.alg_obj_name);

    }).autocomplete({hint: false, minLength: 2, keyboardShortcuts: ['a']}, [{

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
                return echo_js_suggestion(suggestion, 0);
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


function reset_opacity(){
    $('.opacity_fadeout').css('opacity', '1');
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


function js_is_or(in_type_entity_id, return_id){
    //The equivalent of the PHP function is_or()
    var is__or = !(js_en_ids_6193.indexOf(parseInt(in_type_entity_id)) === -1);
    return ( return_id ? ( is__or ? 6193 /* OR */ : 6192 /* AND */ ) : ( is__or ? 1 : 0 ) );
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

function load_edit(handler){
    var position = $(handler).offset();
    $('.edit-box').css('top', position.top).css('left', position.left).removeClass('hidden');
}

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


function toggle_advance(basic_toggle){

    //Toggle UI elements:
    $('.advance-ui').toggleClass('hidden');

    if(basic_toggle){
        //Only Make instant UI changes:
        return true;
    }

    //If an iframe is loaded, also apply logic to iframe UI:
    if($('#ajax_messaging_iframe').attr('src') && $('#ajax_messaging_iframe').attr('src').length > 0){
        document.getElementById('ajax_messaging_iframe').contentWindow.toggle_advance(1);
    }

    //Change top menu icon:
    $('.advance-icon').toggleClass('fal').toggleClass('fas');

    //Save session variable to save the state of advance setting:
    $.post("/links/toggle_advance", {}, function (data) {
        if(!data.status){
            alert('Error: ' + data.message);
        }
    });

}


function ln_content_word_count(el_textarea, el_counter) {
    var len = $(el_textarea).val().length;
    if (len > ln_content_max_length) {
        $(el_counter).addClass('overload').text(len);
    } else {
        $(el_counter).removeClass('overload').text(len);
    }
}

function add_search_item(){

    //Lock search bar:
    $('#platform_search').prop("disabled", true);

    //Attemps to create a new intent OR entity based on the value in the search box
    $.post("/links/add_search_item", { raw_string: $("#platform_search").val() }, function (data) {

        if(!data.status){

            //We had some error:
            $('#platform_search').prop("disabled", false);
            alert('ERROR: ' + data.message);

        } else {

            //All good, redirect to newly added intent/entity:
            window.location = data.new_item_url;

        }
    });
}

