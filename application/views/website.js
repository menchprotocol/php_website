//Define some global variables:
var has_unsaved_changes = false; //Tracks source/idea modal edits
var focus_group = 0;


if (!js_pl_id || !js_sourceids___43512.includes(js_pl_id)) {
    //Microsoft Clarity=
    (function (c, l, a, r, i, t, y) {
        c[a] = c[a] || function () {
            (c[a].q = c[a].q || []).push(arguments)
        };
        t = l.createElement(r);
        t.async = 1;
        t.src = "https://www.clarity.ms/tag/" + i;
        y = l.getElementsByTagName(r)[0];
        y.parentNode.insertBefore(t, y);
        //Append custom variables:
        clarity("set", "website_id", website_id);
        clarity("set", "website_uri", js_request_uri);
        clarity("set", "user_id", js_pl_id);
        clarity("set", "user_name", js_pl_name);
        clarity("set", "user_handle", js_pl_handle);
    })(window, document, "clarity", "script", "59riunqvfm");
}


jQuery.fn.sortElements = (function () {

    var sort = [].sort;

    return function (comparator, getSortable) {

        getSortable = getSortable || function () {
            return this;
        };

        var placements = this.map(function () {

            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,

                // Since the element itself will change position, we have
                // to have some way of storing it's original position in
                // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function () {

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

        return sort.call(this, comparator).each(function (i) {
            placements[i].call(getSortable.call(this));
        });

    };

})();

function htmlentitiesjs(rawStr) {
    return rawStr.replace(/[\u00A0-\u9999<>\&]/gim, function (i) {
        return '&#' + i.charCodeAt(0) + ';';
    });
}

function clean_font_awesome_paste(new_cover) {
    if (new_cover.includes('<i class="fa-')) {
        //Extract font awesome code:
        var split_cover_arr = new_cover.split('<i class="fa-');
        var split_cover_arr2 = split_cover_arr[1].split('"');
        new_cover = (split_cover_arr2[0].length ? 'fa-' + split_cover_arr2[0] : new_cover);
    }
    return new_cover;
}

function watch_cover_change(new_cover) {
    if (new_cover.substr(0, 2) == 'fa' && new_cover.includes('fa-')) {
        //Update font awesome:
        var split_cover_2arr = new_cover.split('fa-');
        var split_cover_2arr2 = split_cover_2arr[1].split(' ');
        $('#modal31912 .fa_search a').attr('href', 'https://fontawesome.com/search?q=' + encodeURIComponent(split_cover_2arr2[0]) + '&o=r&s=solid&f=classic%2Cbrands');
        $('#modal31912 .save_sourcecover,  #modal31912 .fa_search').removeClass('hidden');
        console.log('updated');
    } else {
        $('#modal31912 .save_sourcecover, #modal31912 .fa_search').addClass('hidden');
    }
}

function watch_cover() {
    $('#modal31912 .save_sourcecover').change(function () {

        console.log('change detexted:' + $(this).val());
        watch_cover_change($(this).val());

    }).on('paste', function (e) {
        e.preventDefault();
        var text;
        var clp = (e.originalEvent || e).clipboardData;
        if (clp === undefined || clp === null) {
            text = window.clipboardData.getData("text") || "";
            if (text !== "") {
                text = clean_font_awesome_paste(text);
                if (window.getSelection) {
                    var newNode = document.createElement("span");
                    newNode.innerHTML = text;
                    window.getSelection().getRangeAt(0).insertNode(newNode);
                } else {
                    document.selection.createRange().pasteHTML(text);
                }
            }
        } else {
            text = clp.getData('text/plain') || "";
            if (text !== "") {
                text = clean_font_awesome_paste(text);
                document.execCommand('insertText', false, text);
            }
        }
        watch_cover_change(text);
    });

}


function gather_media(target_el, uploader_id) {

    //Append Media:
    var sort_rank = 0;
    var upload_completed = true;
    var error_message = null;
    var uploaded_media = [];
    $(target_el).each(function () {

        var currentsource_id = parseInt($(this).attr('sourceid'));

        if (currentsource_id > 0) {

            //Already there...
            uploaded_media[sort_rank] = {
                media_sourceid: parseInt($(this).attr('media_sourceid')),
                playback_code: $(this).attr('playback_code'),
                sourceid: currentsource_id,
                sourcecover: $(this).attr('sourcecover'),
                sourcevalue: $('#' + $(this).attr('id') + ' input').val(),
            }
            sort_rank++;

        } else if (media_cache[uploader_id][$(this).attr('id')]) {

            //Fetch variables for this media:
            uploaded_media[sort_rank] = {
                media_sourceid: parseInt($(this).attr('media_sourceid')),
                playback_code: $(this).attr('playback_code'),
                sourceid: 0,
                sourcecover: $(this).attr('sourcecover'),
                sourcevalue: $('#' + $(this).attr('id') + ' input').val(),
                media_cache: media_cache[uploader_id][$(this).attr('id')],
            }
            sort_rank++;

        } else {

            //This media is missing, upload is not yet complete:
            upload_completed = false;
            error_message = 'Media has not yet uploaded, please wait until upload is complete...';

        }
    });

    return {
        upload_completed: upload_completed,
        error_message: error_message,
        uploaded_media: uploaded_media,
    };
}


function chain_preview(apply_id, s__id) {

    //Select first:
    var first_id = $('#modal' + apply_id + ' .mass_action_toggle option:first').val();
    $('.mass_action_item').addClass('hidden');
    $('.mass_id_' + first_id).removeClass('hidden');
    $('#modal' + apply_id + ' .mass_action_toggle').val(first_id);
    $('#modal' + apply_id + ' input[name="s__id"]').val(s__id);
    $('#modal' + apply_id).modal('show');

    //Load Ppeview:
    $('#modal' + apply_id + ' .chain_preview').html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Loading');
    $.post("/controller/chain_preview", {
        apply_id: apply_id,
        s__id: s__id,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('#modal' + apply_id + ' .chain_preview').html(data);
    });

}


function load_editor() {

    $('.mass_action_toggle').change(function () {
        $('.mass_action_item').addClass('hidden');
        $('.mass_id_' + $(this).val()).removeClass('hidden');
    });

    if (!search_enabled()) {
        console.log("Search engine is disabled!");
        return false;
    }

    $('.source_text_finder').on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('@' + suggestion.s__handle);

    }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

        source: function (q, cb) {
            index_algolia.search(q, {
                filters: 's__type=12274' + search_and_filter,
                hitsPerPage: js_sources___6404[31112]['m__message'],
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
                return search_js_line(suggestion, '@');
            },
            empty: function (data) {
                return '<div class="main__title"><i class="far fa-exclamation-circle"></i> No Sources Found</div>';
            },
        }

    }]);

    $('.i_text_finder').on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('#' + suggestion.s__handle);

    }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

        source: function (q, cb) {
            index_algolia.search(q, {
                filters: 's__type=12273' + search_and_filter,
                hitsPerPage: js_sources___6404[31112]['m__message'],
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
                return search_js_line(suggestion, '#');
            },
            empty: function (data) {
                return '<div class="main__title"><i class="far fa-exclamation-circle"></i> No Ideas Found</div>';
            },
        }
    }]);

}


function search_title(suggestion) {
    var title = (suggestion._highlightResult && suggestion._highlightResult.s__title.value ? suggestion._highlightResult.s__title.value : suggestion.s__title);
    var max_limit = 89;
    return htmlentitiesjs(title.length >= max_limit ? title.substring(0, max_limit) + '...' : title);
}


function search_js_line(suggestion, default_handle = '@') {
    if (suggestion.s__type == 12273) {
        return '<span class="grey">' + default_handle + suggestion.s__handle + '</span>&nbsp;<span class="main__title">' + search_title(suggestion) + '</span>';
    } else if (suggestion.s__type == 12274) {
        return '<span class="icon-block-xs">' + view_cover_js(suggestion.s__cover) + '</span><span class="grey">' + default_handle + suggestion.s__handle + '</span>&nbsp;<span class="main__title">' + search_title(suggestion) + '</span>';
    }
}

function source_load_finder(chainsourcetype) {
    console.log(chainsourcetype + " source_load_finder()");
    //Load Search:
    var icons_listed = [];
    $('.new-list-' + chainsourcetype + ' .add-input').keypress(function (e) {
        icons_listed = [];
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            source_create(chainsourcetype, 0);
            return true;
        }
    });
}

function idea_load_search(chainsourcetype) {
    //Load Search:
    var icons_listed = [];
    $('.new-list-' + chainsourcetype + ' .add-input').keypress(function (e) {
        icons_listed = [];
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            idea_create(chainsourcetype, 0);
            return true;
        }
    });
}

function search_js_cover(chainsourcetype, suggestion, action_id) {

    if (!js_sourceids___26010.includes(chainsourcetype)) {
        alert('Missing type in JS UI');
        return false;
    }

    var background_image = '';
    var icon_image = '';

    if (suggestion.s__cover && suggestion.s__cover.length) {
        if (validURL(suggestion.s__cover)) {
            background_image = 'style="background-image:url(\'' + suggestion.s__cover + '\')"';
        } else {
            icon_image = view_cover_js(suggestion.s__cover);
        }
    }

    //Return appropriate UI:
    if (chainsourcetype == 26011) {
        //Mini Coin
        var search_only_app = $("#website_finder").val().charAt(0) == '-';
        var target_url = (search_only_app ? suggestion.s__url.replace('/@', '/') : suggestion.s__url);
        return '<div title="ID ' + suggestion.s__id + '" class="card_cover mini-cover card-' + suggestion.s__type + ' ' + (search_only_app ? ' card-6287 ' : '') + ' card-id-' + suggestion.s__id + ' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="' + target_url + '" class="black-background-obs cover-chain coinType' + suggestion.s__type + '" ' + background_image + '><div class="cover-btn">' + icon_image + '</div></a></div><div class="cover-content"><div class="inner-content"><a href="' + target_url + '" class="main__title">' + (suggestion.s__cache.length ? suggestion.s__cache : '<span class="main__title">' + suggestion.s__title + '</span>') + '</a></div></div></div>';
    } else if (chainsourcetype == 26013) {
        //Chain Source
        return '<div title="ID ' + suggestion.s__id + '" class="card_cover mini-cover card-' + suggestion.s__type + ' card-id-' + suggestion.s__id + ' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="source_create(' + action_id + ', ' + suggestion.s__id + ')" class="black-background-obs cover-chain coinType' + suggestion.s__type + '" ' + background_image + '><div class="cover-btn">' + icon_image + '</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="source_create(' + action_id + ', ' + suggestion.s__id + ')" class="main__title">' + suggestion.s__title + '</a></div></div></div>';
    }

}

function search_mini_js(s__cover, s__title) {
    return '<span class="block-cover" title="' + s__title + '">' + view_cover_js(s__cover) + '</span>';
}


function toggle_headline(chainsourcetype) {

    var chainsourcedown = 0;
    var chainidearight = 0;
    var focus__node = parseInt($('#focus__node').val());
    if (focus__node == 12273) {
        chainidearight = parseInt($('#focus__id').val());
    } else if (focus__node == 12274) {
        chainsourcedown = parseInt($('#focus__id').val());
    }

    if ($('.headline_title_' + chainsourcetype + ' .icon_26008').hasClass('hidden')) {

        //Currently open, must now be closed:
        var action_id = 26008; //Close
        $('.headline_title_' + chainsourcetype + ' .icon_26008').removeClass('hidden');
        $('.headline_title_' + chainsourcetype + ' .icon_26007').addClass('hidden');
        $('.headline_body_' + chainsourcetype).addClass('hidden');

        if (chainsourcetype == 6255) {
            $('.navigate_12273').removeClass('active');
        }

    } else {

        //Close all other opens:
        $('.headlinebody').addClass('hidden');
        $('.headline_titles .icon_26007').addClass('hidden');
        $('.headline_titles .icon_26008').removeClass('hidden');

        //Currently closed, must now be opened
        var action_id = 26007; //Open
        $('.headline_title_' + chainsourcetype + ' .icon_26007').removeClass('hidden');
        $('.headline_title_' + chainsourcetype + ' .icon_26008').addClass('hidden');
        $('.headline_body_' + chainsourcetype).removeClass('hidden');

        if (chainsourcetype == 6255) {
            $('.navigate_12273').addClass('active');
        }

        //Scroll To:
        $('html, body').animate({
            scrollTop: $('.headline_body_' + chainsourcetype).offset().top
        }, 13);

    }

}


function source_sort_load(chainsourcetype) {

    load_cards();

    console.log('Tring to load Source Sort for @' + chainsourcetype);

    var sort_item_count = parseInt($('.headline_body_' + chainsourcetype).attr('read-counter'));

    if (!js_sourceids___13911.includes(chainsourcetype)) {
        //Does not support sorting:
        console.log(chainsourcetype + ' is not sortable');
        return false;
    } else if (sort_item_count < 1 || sort_item_count > parseInt(js_sources___6404[11064]['m__message'])) {
        return false;
    }

    setTimeout(function () {
        var theobject = document.getElementById("list-in-" + chainsourcetype);
        if (!theobject) {
            //due to duplicate ideas belonging in this idea:
            console.log('No object');
            return false;
        }

        //Show sort icon:
        console.log('Completed Loading Sorting for @' + chainsourcetype)
        $('.sortsource_frame').removeClass('hidden');

        var sort = Sortable.create(theobject, {
            animation: 144, // ms, animation speed moving items when sorting, `0` � without animation
            draggable: "#list-in-" + chainsourcetype + " .sort_draggable", // Specifies which items inside the element should be sortable
            handle: "#list-in-" + chainsourcetype + " .sortsource_grab", // Restricts sort start click/touch to the specified element
            onUpdate: function (evt/**Event*/) {
                source_sort_save(chainsourcetype);
            }
        });
    }, 377);

}


window.onpopstate = function (event) {
    load_hashtag_menu(null, false);
};

function load_hashtag_menu(load_hashtag = null, is_first_load = true) {
    if (load_hashtag) {
        toggle_pills(load_hashtag, is_first_load);
    } else if (document.location.hash) {
        var hashtag = document.location.hash.substr(1);
        if (hashtag && hashtag.length > 0) {
            toggle_pills(hashtag, is_first_load);
        }
    }
}


var loading_in_progress = false;
var pills_loading = null;
var loaded_pills = [];

function toggle_pills(chainsourcetype_hash, is_first_load) {

    console.log('Toggle Pill: ' + chainsourcetype_hash);

    if (pills_loading && !loaded_pills.includes(chainsourcetype_hash)) {
        return false;
    } else if (loading_in_progress) {
        return false;
    }

    console.log('Toggle Pill Active: ' + chainsourcetype_hash);

    if ($('.handle_nav_' + chainsourcetype_hash).attr('chainsourcetype') && $('.handle_nav_' + chainsourcetype_hash).attr('chainsourcetype').length) {
        chainsourcetype = parseInt($('.handle_nav_' + chainsourcetype_hash).attr('chainsourcetype'));
    } else {
        console.log('ERROR: #' + chainsourcetype_hash + ' is not a valid menu.');
        return false;
    }

    loading_in_progress = true;

    if (!loaded_pills.includes(chainsourcetype_hash)) {
        pills_loading = chainsourcetype_hash;
    }

    var chainsourcedown = 0;
    var chainidearight = 0;
    var focus__node = parseInt($('#focus__node').val());

    if (focus__node == 12273) {
        chainidearight = parseInt($('#focus__id').val());
    } else if (focus__node == 12274) {
        chainsourcedown = parseInt($('#focus__id').val());
    }

    //Toggle view
    $('.xtypetitle').addClass('hidden');
    $('.xtypetitle_' + chainsourcetype).removeClass('hidden');


    if (!$('.thepill' + chainsourcetype + ' .nav-chain').hasClass('active')) {

        //Currently closed, must now be opened:
        var action_id = 26007; //Open

        //Hide all elements
        $('.nav-chain').removeClass('active');
        $('.headlinebody').addClass('hidden');
        $('.thepill' + chainsourcetype + ' .nav-chain').addClass('active');
        $('.headline_body_' + chainsourcetype).removeClass('hidden');

        //Set focus tab:
        console.log('focus_group Updated from ' + focus_group + ' to ' + chainsourcetype);
        focus_group = chainsourcetype;
        if (!is_first_load && (!window.location.hash || window.location.hash != $('.thepill' + chainsourcetype + ' .nav-chain').attr('href'))) {
            window.location.hash = $('.thepill' + chainsourcetype + ' .nav-chain').attr('href');
        }

        //Do we need to load data via ajax?
        if (!loaded_pills.includes(chainsourcetype_hash)) {

            $('.headline_body_' + chainsourcetype + ' .tab_content').html('<div class="center" style="padding-top: 13px;"><i class="fas fa-yin-yang fa-spin"></i></div>');

            var focus__node = parseInt($('#focus__node').val());
            console.log('Tab loading from @' + focus__node + ' for @' + chainsourcetype);

            if (focus__node == 12273) {

                var loading_url = "/controller/idea_list";
                var loading_data = {
                    focus__node: focus__node,
                    chainsourcetype: chainsourcetype,
                    counter: $('.headline_body_' + chainsourcetype).attr('read-counter'),
                    ideaid: parseInt($('#focus__id').val()),
                    js_request_uri: js_request_uri, //Always append to AJAX Calls
                };

            } else if (focus__node == 12274) {

                var loading_url = "/controller/source_list";
                var loading_data = {
                    focus__node: focus__node,
                    chainsourcetype: chainsourcetype,
                    counter: $('.headline_body_' + chainsourcetype).attr('read-counter'),
                    sourceid: parseInt($('#focus__id').val()),
                    js_request_uri: js_request_uri, //Always append to AJAX Calls
                };

            } else {

                //Whaaaat is this?
                console.log('ERROR: Unknown Tab!');
                loading_in_progress = false;
                return false;

            }

            //Load data:
            $.post(loading_url, loading_data, function (data) {

                //Add data to the page:
                $('.headline_body_' + chainsourcetype + ' .tab_content').html(data);

                loaded_pills.push(chainsourcetype_hash);

                load_card_clickers();
                initiate_algolia();
                load_editor();
                x_set_start_text();
                set_autosize($('.x_set_class_text'));

                $(function () {
                    var $win = $(window);
                    $win.scroll(function () {
                        //Download loading from bottom:
                        if (parseInt($(document).height() - ($win.height() + $win.scrollTop())) < 377) {
                            chain_page_load();
                        }
                    });
                });

                setTimeout(function () {

                    if (js_sourceids___11020.includes(chainsourcetype) || (focus__node == 12274 && (js_sourceids___42261.includes(chainsourcetype) || js_sourceids___42284.includes(chainsourcetype)))) {
                        idea_sort_load(chainsourcetype);
                    } else if (js_sourceids___11028.includes(chainsourcetype) || (focus__node == 12273 && (js_sourceids___42261.includes(chainsourcetype) || js_sourceids___42284.includes(chainsourcetype)))) {
                        source_sort_load(chainsourcetype);
                    }

                    setup_popover();

                    pills_loading = null;

                }, 233);

                loading_in_progress = false;

            });

        } else {
            loading_in_progress = false;
        }

    } else {
        loading_in_progress = false;
    }
}


function idea_copy(ideaid, do_recursive) {

    //Go ahead and delete:
    $.post("/controller/idea_copy", {
        ideaid: ideaid,
        do_recursive: do_recursive,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {
            js_redirect(js_sources___42903[33286]['m__message'] + data.idea_createhashtag);
        } else {
            alert('ERROR:' + data.message);
        }
    });
}

function source_title(sourceid) {
    //Load Instant Fields:
    var return_title = '';
    if ($('.text__6197_' + sourceid + ':first').text().length) {
        return_title = $('.text__6197_' + sourceid + ':first').text();
    } else if ($('.text__6197_' + sourceid + ':first').val().length) {
        return_title = $('.text__6197_' + sourceid + ':first').val();
    }
    return return_title;
}

function source_copy(sourceid) {

    var copy_source_title = prompt("What would be the title of the new Source?", source_title(sourceid));
    if (!copy_source_title.length) {
        alert('You must enter a title to copy.');
        return false;
    }

    //Go ahead and delete:
    $.post("/controller/source_copy", {
        sourceid: sourceid,
        copy_source_title: copy_source_title,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {
            js_redirect(js_sources___42903[42902]['m__message'] + data.source_createhandle);
        } else {
            alert('ERROR:' + data.message);
        }
    });
}


function js_randomize_text(sourceid) {
    var messages = js_sources___12687[sourceid]['m__message'].split("\n");
    if (messages.length == 1) {
        //Return message:
        return messages[0];
    } else {
        //Choose Random:
        return messages[Math.floor(Math.random() * messages.length)];
    }
}


function loadtab(chainsourcetype, tab_data_id) {

    //Hide all tabs:
    $('.tab-group-' + chainsourcetype).addClass('hidden');
    $('.tab-nav-' + chainsourcetype).removeClass('active');

    //Show this tab:
    $('.tab-group-' + chainsourcetype + '.tab-data-' + tab_data_id).removeClass('hidden');
    $('.tab-nav-' + chainsourcetype + '.tab-head-' + tab_data_id).addClass('active');

}


var init_in_process = 0;

function chain_delete(chainid, chainsourcetype, ideahashtag = null) {

    if (init_in_process == chainid) {
        return false;
    }
    init_in_process = chainid;

    var r = confirm("Are you Sure You Want to Unchain" + (ideahashtag ? ' #' + ideahashtag : '') + "?");
    if (!(r == true)) {
        return false;
    }

    //Save changes:
    $.post("/controller/chain_delete", {
        chainid: chainid,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
        ideahashtag: ideahashtag, //Always append to AJAX Calls
    }, function (data) {
        //Update UI to confirm with member:
        if (!data.status) {
            //There was some sort of an error returned!
            alert(data.message);
        } else {
            chain_counter(chainsourcetype, -1);
            $(".cover_x_" + chainid).fadeOut();
            setTimeout(function () {
                $(".cover_x_" + chainid).remove();
            }, 610);
        }
    });

    return false;
}


function updatsourcecover(new_cover, changed = true) {
    $('#modal31912 .save_sourcecover').val(new_cover);
    update_cover_main(new_cover, '.demo_cover');
    watch_cover_change(new_cover);
    if (changed) {
        has_unsaved_changes = true;
    }
}

function image_cover(cover_preview, cover_apply, new_title) {
    return '<a href="javascript:void(0);" onclick="updatsourcecover(\'' + cover_apply + '\')">' + search_mini_js(cover_preview, new_title) + '</a>';
}


function initiate_algolia() {
    $(".algolia_finder").focus(function () {
        if (!index_algolia && search_enabled()) {
            //Loadup Algolia once:
            client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
            index_algolia = client.initIndex('alg_index');
        }
    });
}

function source_cover(chainsourcetype, sourceid, counter, first_segment) {

    if ($('.coinssource_' + sourceid + '_' + chainsourcetype).html().length) {
        //Already loaded:
        return false;
    }

    $('.coinssource_' + sourceid + '_' + chainsourcetype).html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/controller/source_cover", {
        chainsourcetype: chainsourcetype,
        sourceid: sourceid,
        counter: counter,
        first_segment: first_segment,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('.coinssource_' + sourceid + '_' + chainsourcetype).html(data);
    });

}

function idea_cover(chainsourcetype, ideaid, counter, first_segment, current_e) {

    if ($('.coins_idea_' + ideaid + '_' + chainsourcetype).html().length) {
        //Already loaded:
        return false;
    }

    $('.coins_idea_' + ideaid + '_' + chainsourcetype).html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/controller/idea_cover", {
        chainsourcetype: chainsourcetype,
        ideaid: ideaid,
        counter: counter,
        first_segment: first_segment,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('.coins_idea_' + ideaid + '_' + chainsourcetype).html(data);
    });

}


//Main navigation
var search_on = false;

function toggle_finder() {

    $('.left_nav').addClass('hidden');
    $('.icon_finder').toggleClass('hidden');

    if (search_on) {

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
        $('#website_finder').focus();

        setTimeout(function () {
            //One more time to make sure it also works in mobile:
            $('#website_finder').focus();
        }, 55);


    }
}


function load_cards() {
    $(".loadsource_cards, .load_idea_cards").unbind();

    $(".loadsource_cards").click(function (event) {
        source_cover($(this).attr('load_chainsourcetype'), $(this).attr('load_sourceid'), $(this).attr('load_counter'), $(this).attr('load_first_segment'));
    });
    $(".load_idea_cards").click(function (event) {
        idea_cover($(this).attr('load_chainsourcetype'), $(this).attr('load_ideaid'), $(this).attr('load_counter'), $(this).attr('load_first_segment'));
    });
}

function js_redirect(url, timer = 0) {
    if (timer > 0) {
        setTimeout(function () {
            window.location = url;
        }, timer);
    } else {
        window.location = url;
    }
    return false;
}


function load_card_clickers() {

    $(".card_click").unbind();
    var ignore_clicks = 'a, .btn, textarea, .chainvalue, .cover_wrapper12273, .ignore-click, .focus-cover, .ref_source, .this_selector';
    $(".card_click").click(function (e) {
        if ($(e.target).closest(ignore_clicks).length < 1 && $(this).attr('href').length) {
            js_redirect($(this).attr('href'));
        }
    });

    /*
    $( ".is_locked" ).click(function(e) {
        if($(e.target).closest(ignore_clicks).length < 1){
            alert('Note: Scroll down & click the black next button to continue...');
        }
    });
    */

    //For Discovery only:
    if (typeof focus_ideatype !== 'undefined' && focus_ideatype > 0) {

        var is_single_choice = (focus_ideatype == 6684);

        if ($(".this_selector").length == 1) {
            //Auto select if only 1 choice is available:
            $('.this_selector i').removeClass('far').removeClass('fa-square').addClass('fas').addClass('fa-square-check');
        }

        $(".this_selector").click(function (e) {
            if ($('.this_selector_' + $(this).attr('selection_ideaid') + ' i').hasClass('fa-square-check')) {

                //Already selected, so unselect:
                $('.this_selector_' + $(this).attr('selection_ideaid') + ' i').removeClass('fas').removeClass('fa-square-check').addClass('far').addClass('fa-square');

            } else {

                //Not selected, so Select now:
                if (is_single_choice) {

                    console.log('Single Choice');

                    //Unselect the previously selected:
                    $('.this_selector:not(.this_selector_' + $(this).attr('selection_ideaid') + ') i.fa-square-check').each(function () {
                        $(this).removeClass('fas').removeClass('fa-square-check').addClass('far').addClass('fa-square');
                    });
                    //Go Next:
                    if (!$('.input_ui_' + $(this).attr('selection_ideaid'))[0]) {
                        //Since there is no input for this single select, we can instantly go next:
                        setTimeout(function () {
                            idea_discovered(0);
                        }, 89);
                    } else {
                        //Make button visible if hidden:
                        $(".fixed-bottom").removeClass('hidden');
                    }
                } else {
                    console.log('NOT Single Choice');
                    //Make button visible if hidden:
                    $(".fixed-bottom").removeClass('hidden');
                }

                if ($('.input_ui_' + $(this).attr('selection_ideaid'))[0]) {
                    $('.input_ui_' + $(this).attr('selection_ideaid') + ' .x_write').focus();
                }
                $('.this_selector_' + $(this).attr('selection_ideaid') + ' i').removeClass('far').removeClass('fa-square').addClass('fas').addClass('fa-square-check');

            }
        });
    }
}


var busy_processing = false;

function sale_increment(increment, ideaid, max_allowed, min_allowed, unit_total, unit_fee) {

    var current_quentity = parseInt($('.input_ui_' + ideaid + ' .current_count').text());
    var new_quantity = current_quentity + increment;

    if (new_quantity < min_allowed || new_quantity > max_allowed) {
        return false;
    } else if (busy_processing) {
        return false;
    }

    if (new_quantity > min_allowed) {
        $(".sale_controller_" + ideaid + " .sale_down>i").removeClass('hidden');
    } else {
        $(".sale_controller_" + ideaid + " .sale_down>i").addClass('hidden');
    }
    if (new_quantity < max_allowed) {
        $(".sale_controller_" + ideaid + " .sale_up>i").removeClass('hidden');
    } else {
        $(".sale_controller_" + ideaid + " .sale_up>i").addClass('hidden');
    }

    busy_processing = true;


    var handling_total = (unit_fee * new_quantity);
    var new_total = (unit_total * new_quantity);

    //Update UI:
    $(".input_ui_" + ideaid + " .ideakey").val(new_quantity);
    $(".input_ui_" + ideaid + " .current_count").text(new_quantity);
    $(".input_ui_" + ideaid + " .paypal_handling").val(handling_total);

    invoice_update(); //to show new numbers

    busy_processing = false;

}


function invoice_update() {

    var total_count = 0;
    var total_price = 0;
    var total_currency = '';

    $(".sale_controller").each(function () {

        var item_ideaid = parseInt($(this).attr('ideaid'));
        var item_idea_title = $('.cache_frame_' + item_ideaid + ' .first_line').text();
        var current_count = parseFloat($('.input_ui_' + item_ideaid + ' .current_count').text());
        var current_price = parseFloat($(this).attr('unitprice'));
        var current_currency = $(this).attr('unitcurrency');

        total_count += current_count;
        total_price += (current_count * current_price);
        total_currency = current_currency;
    });


    //Update UI:
    $('.idea_discovered_btn').html('Create Invoice: <span title="" class="small_font inline-block">' + total_currency + ' ' + total_price.toLocaleString('en-US', {
        style: 'currency',
        currency: total_currency,
    }) + ' [' + total_count + ']</span>');
    $(".btn.post_button").fadeOut(55).fadeIn(55).fadeOut(55).fadeIn(55);


}


function random_animal(basic_style = false) {
    var animals = ['fa-hippo', 'fa-otter', 'fa-sheep', 'fa-rabbit', 'fa-pig', 'fa-dog', 'fa-elephant', 'fa-deer', 'fa-cow', 'fa-alicorn', 'fa-rabbit', 'fa-monkey', 'fa-cat', 'fa-cat-space', 'fa-fish', 'fa-dragon', 'fa-whale', 'fa-turtle', 'fa-snake', 'fa-spider', 'fa-lobster', 'fa-duck', 'fa-dove', 'fa-crow', 'fa-dinosaur', 'fa-bee', 'fa-horse', 'fa-raccoon', 'fa-pegasus', 'fa-bat', 'fa-deer', 'fa-badger-honey', 'fa-squirrel', 'fa-ram', 'fa-dolphin', 'fa-bird', 'fa-crab', 'fa-worm', 'fa-kiwi-bird', 'fa-shrimp', 'fa-duck', 'fa-teddy-bear', 'fa-t-rex'];
    return 'far ' + animals[Math.floor(Math.random() * animals.length)];
}

var interval = null;

function setup_popover() {

    if (interval) {
        clearInterval(interval);
    }

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover({
        html: true,
        //title: '<a class="close" href="javascript:void(0);" style="display: block;">Close</a>',
        content: function (inner_content) {
            $.post("/controller/chain_popover", {
                handle_string: inner_content.innerText,
                js_request_uri: js_request_uri, //Always append to AJAX Calls
            }, function (data) {
                $('.popover-body').html(data);
                load_cards();
                load_card_clickers();
            });
            return '<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>';
        }
    });

    $(document).click(function (e) {
        if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });
    /*
    $('body').on('click', function (e) {
        if ($(e.target).data('toggle') !== 'popover' && $(e.target).parents('[data-toggle="popover"]').length === 0
            && $(e.target).parents('.popover.in').length === 0) {
            (($('[data-toggle="popover"]').popover('hide').data('bs.popover') || {}).inState || {}).click = false;
        }
    });
    */
    $('[data-toggle="popover"]').on('click', function (e) {
        e.preventDefault();
        $('[data-toggle="popover"]').not(this).popover('hide');
    });
}


var index_algolia = false;
$(document).ready(function () {

    //Look for power editor updates:
    x_set_start_text();

    setup_popover();

    watch_cover();

    //Only for idea page but still:
    set_autosize($('.text__6197_' + parseInt($('#focus__id').val())));

    $(document).on('keydown', function (e) {
        // You may replace `c` with whatever key you want
        if (e.ctrlKey) {
            if (String.fromCharCode(e.which).toLowerCase() === 'i') {
                //Add Idea
                idea_editor();
            } else if (String.fromCharCode(e.which).toLowerCase() === 's') {
                //Add Source:
                source_editor(0, 0);
            } else if (String.fromCharCode(e.which).toLowerCase() === 'f' && search_enabled()) {
                //Finder:
                toggle_finder();
            }
        }
    });


    load_card_clickers();

    setTimeout(function () {
        load_cards();
    }, 987);

    //Lookout for textinput updates
    x_set_start_text();

    $('#website_finder').keyup(function () {
        if (!$(this).val().length) {
            $("#container_finder .row").html(''); //Reset results view
        }
    });

    //For the S shortcut to load search:
    $("#website_finder").focus(function () {
        if (!search_on) {
            toggle_finder();
        }
    });

    //Keep an eye for icon change:
    $('#modal31912 .save_sourcecover').keyup(function () {
        update_cover_main($(this).val(), '.demo_cover');
    });

    set_autosize($('#sugg_note'));
    set_autosize($('.texttype_lg'));

    $('.trigger_modal').click(function (e) {
        var chainsourcetype = parseInt($(this).attr('chainsourcetype'));
        $('#modal' + chainsourcetype).modal('show');
    });


    $("#modal31911, #modal31912").on("hide.bs.modal", function (e) {
        if (has_unsaved_changes) {
            var r = confirm("Changes are unsaved! Close this window? Cancel to stay here:");
            if (!(r == true)) {
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

            if (search_on) {
                toggle_finder();
            }

        }
    });

    //Search that also has insert module:
    if (search_enabled()) {


        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\/(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
                        filters: 's__type=12274 AND _tags:z_6287 ' + search_and_filter,
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
                    return search_js_line(suggestion, '/');
                },
                replace: function (suggestion) {
                    return ' /' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\.@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '.@');
                },
                replace: function (suggestion) {
                    return ' .@' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s),@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, ',@');
                },
                replace: function (suggestion) {
                    return ' ,@' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s);@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, ';@');
                },
                replace: function (suggestion) {
                    return ' ;@' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s):@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, ':@');
                },
                replace: function (suggestion) {
                    return ' :@' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\+@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '+@');
                },
                replace: function (suggestion) {
                    return ' +@' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)-@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '-@');
                },
                replace: function (suggestion) {
                    return ' -@' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\?@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '?@');
                },
                replace: function (suggestion) {
                    return ' ?@' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\*@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '*@');
                },
                replace: function (suggestion) {
                    return ' *@' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\|@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '|@');
                },
                replace: function (suggestion) {
                    return '|@' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '@');
                },
                replace: function (suggestion) {
                    return ' @' + suggestion.s__handle + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '#');
                },
                replace: function (suggestion) {
                    return ' #' + suggestion.s__handle + ' ';
                }
            },
        ]);








    }


    setup_popover();


    //Prevent search submit:
    $('#searchFrontForm').on('submit', function (e) {
        e.preventDefault();
        return false;
    });


    if (!search_enabled()) {
        console.log("Search engine is disabled!");
        return false;
    }

    var icons_listed = [];

    //TOP SEARCH
    $("#website_finder").autocomplete({minLength: 1, autoselect: false, keyboardShortcuts: ['s']}, [
        {
            source: function (q, cb) {

                icons_listed = [];

                //Members can filter search with first word:
                var search_only_e = $("#website_finder").val().charAt(0) == '@';
                var search_only_in = $("#website_finder").val().charAt(0) == '#';
                var search_only_app = $("#website_finder").val().charAt(0) == '-';
                $("#container_finder .row").html(''); //Reset results view


                //Do not search if specific command ONLY:
                if ((search_only_in || search_only_e || search_only_app) && !isNaN($("#website_finder").val().substr(1))) {

                    cb([]);
                    return;

                } else {

                    //Now determine the filters we need to apply:
                    var search_filters = '';

                    if (search_only_in) {
                        search_filters += ' s__type=12273';
                    } else if (search_only_e) {
                        search_filters += ' s__type=12274';
                    } else if (search_only_app) {
                        search_filters += ' s__type=12274 AND _tags:z_6287 ';
                    }

                    if (js_pl_id > 0) {

                        //For Members:
                        if (!js_session_superpowers_unlocked.includes(12701)) {
                            //Can view limited Sources:
                            if (search_filters.length > 0) {
                                search_filters += ' AND ';
                            }
                            search_filters += ' ( _tags:public_index OR _tags:z_' + js_pl_id + ' ) ';
                        }

                    } else {

                        //Guest can search ideas only by default as they start typing;
                        if (search_filters.length > 0) {
                            search_filters += ' AND ';
                        }
                        search_filters += ' _tags:public_index ';

                    }

                    //Append filters:
                    index_algolia.search(q, {
                        hitsPerPage: js_sources___6404[31113]['m__message'],
                        filters: search_filters,
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
                    var item_key = suggestion.s__type + '_' + suggestion.s__id;
                    if (!icons_listed.includes(item_key)) {
                        icons_listed.push(item_key);
                        $("#container_finder .row").append(search_js_cover(26011, suggestion, 0));
                    }
                    return false;
                },
                empty: function (data) {
                    $("#container_finder .row").html('<div class="main__title margin-top-down-half"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No results found</div>');
                },
            }
        }
    ]);

});


function update_cover_main(cover_code, target_css) {

    //Set Default:
    $(target_css + ' .cover-chain').css('background-image', '');
    $(target_css + ' .cover-btn').html('');

    //Update:
    if (validURL(cover_code)) {
        $(target_css + ' .cover-chain').css('background-image', 'url(\'' + cover_code + '\')');
    } else if (cover_code && cover_code.indexOf('fa-') >= 0) {
        $(target_css + ' .cover-btn').html('<i class="' + cover_code + '"></i>');
    } else if (cover_code && cover_code.length > 0) {
        $(target_css + ' .cover-btn').text(cover_code);
    }
}

function view_cover_js(cover_code) {
    if (cover_code && cover_code.length) {
        if (validURL(cover_code)) {
            return '<img src="' + cover_code + '" />';
        } else if (cover_code && cover_code.indexOf('fa-') >= 0) {
            return '<i class="' + cover_code + '"></i>';
        } else {
            return cover_code;
        }
    } else {
        return '<i class="far fa-circle"></i>';
    }
}

function update_cover_mini(cover_code, target_css) {
    //Update:
    $(target_css).html(view_cover_js(cover_code));
}


function idea_direction_switch(chainsourcetype = 0, next_ideaid = 0, previous_ideaid = 0, do_checks = 0) {

    console.log('SWITCHING TO ' + chainsourcetype + '/' + next_ideaid + '/' + previous_ideaid + '/' + do_checks + '/' + $('#modal31911 .save_ideavalue').val() + '/' + parseInt($('#modal31911 .created_ideaid').val()));

    /*
    if(!next_ideaid && !previous_ideaid && !chainsourcetype && !do_checks){
        var r = confirm("Are you sure you want to unchain this idea?");
        if (!(r==true)) {
            return false;
        }
    }
    */

    //Will switch the nature/direction of the chain:
    return idea_editor(0, 0, chainsourcetype, next_ideaid, previous_ideaid, do_checks, $('#modal31911 .save_ideavalue').val(), parseInt($('#modal31911 .created_ideaid').val()));
}

function display_media(mediaframe_id, uploader_id, ideaid) {
    console.log('display_media: ' + mediaframe_id + '/' + uploader_id + '/' + ideaid);
    $(".ui_ideacache_" + ideaid + " .media_display").each(function () {
        $('#' + mediaframe_id).append('<div id="' + $(this).attr('id') + '" class="media_item" media_sourceid="" playback_code="" sourceid="0"  sourcecover=""></div>');
        cloudinary_presource_view(uploader_id, $(this).attr('id'), $(this).attr('media_sourceid'), $(this).attr('playback_code'), $(this).attr('sourcecover'), $(this).attr('sourcevalue'), $(this).attr('sourceid'));
    });
    sort_media(mediaframe_id);
}

function idea_editor(ideaid = 0, chainid = 0, chainsourcetype = 0, next_ideaid = 0, previous_ideaid = 0, do_checks = 1, load_message = '', passon_ideaid = 0) {


    $(".chain_idea_unchain, .idea_chainsourcetype").addClass('hidden');
    var focus_idea_id = (parseInt($('#focus__node').val()) == 12273 ? parseInt($('#focus__id').val()) : 0);
    $("#modal31911 .save_results").html('');

    if (!passon_ideaid) {

        //Reset Fields:
        has_unsaved_changes = false;
        $('#modal31911 .media_frame').html('');
        $("#modal31911 .dynamic_item").attr('d__id', '').attr('d_chainid', '');
        $("#modal31911 .dynamic_item input").attr('placeholder', '').val('');
        $('#modal31911 .created_ideaid').val(0);
        $("#modal31911 .unsaved_warning").val('');
        $("#modal31911 .save_frame").addClass('hidden');
        $('#modal31911 .save_ideaid, #modal31911 .save_chainid').val(0);

        //Are we adding an idea for a target action tab?
        console.log('i Modal loaded for ' + focus_group);
        if (focus_idea_id && do_checks && focus_group > 0 && !next_ideaid && !previous_ideaid && !ideaid && !chainid && !chainsourcetype) {
            if (js_sourceids___42265.includes(focus_group) || !js_session_superpowers_unlocked.includes(10939)) {
                //Next idea group:
                next_ideaid = focus_idea_id;
                chainsourcetype = (js_session_superpowers_unlocked.includes(10939) ? 4228 : 30901); //Sequence or Comment
            } else if (js_sourceids___42380.includes(focus_group)) {
                //Previous idea group:
                previous_ideaid = focus_idea_id;
                chainsourcetype = (js_session_superpowers_unlocked.includes(10939) ? 4228 : 30901); //Sequence or Comment
            }
        }


        if (!chainsourcetype && do_checks && !ideaid && !next_ideaid && !previous_ideaid && focus_idea_id) {
            console.log('MATCH');
            next_ideaid = focus_idea_id;
            chainsourcetype = (js_session_superpowers_unlocked.includes(10939) ? 4228 : 30901);
        }
    }


    //Load Chain addition info, if any:
    $("#modal31911 .idea_list_next").html('');
    $("#modal31911 .idea_list_previous").html('');

    var is_next = next_ideaid && js_sourceids___4486.includes(chainsourcetype) && $('.ui_ideacache_' + next_ideaid).length;
    var is_prev = previous_ideaid && js_sourceids___4486.includes(chainsourcetype) && $('.ui_ideacache_' + previous_ideaid).length;

    if (is_next || is_prev) {

        ideaid = 0;
        chainid = 0;
        var force_next_simplify = is_prev && !js_session_superpowers_unlocked.includes(42817);

        if (is_next || force_next_simplify) {

            if (force_next_simplify) {
                next_ideaid = previous_ideaid;
                previous_ideaid = 0;
            }

            //Generate content:
            $("#modal31911 .idea_list_next").html('<div class="creator_box"></div>');
            $('.creator_frame_' + next_ideaid + ' .creator_headline>a').each(function () {
                $("#modal31911 .idea_list_next .creator_box").append('<div class="creator_headline">' + $(this).html() + '</div>');
            });
            $("#modal31911 .idea_list_next").append('<div class="idea_response">' + $('.ui_ideacache_' + next_ideaid).html() + '</div>');

        } else if (is_prev) {

            //Generate content:
            $("#modal31911 .idea_list_previous").html('<div class="creator_box"></div>');
            $('.creator_frame_' + previous_ideaid + ' .creator_headline>a').each(function () {
                $("#modal31911 .idea_list_previous .creator_box").append('<div class="creator_headline">' + $(this).html() + '</div>');
            });
            $("#modal31911 .idea_list_previous").append('<div class="idea_response">' + $('.ui_ideacache_' + previous_ideaid).html() + '</div>');

        }

        $('.chain_idea_unchain, .idea_chainsourcetype').removeClass('hidden');
        if (!passon_ideaid) {
            update_form_select(4486, chainsourcetype, 1, false);
        }
    }

    //Assign updates:
    $('#modal31911 .next_ideaid').val(next_ideaid);
    $('#modal31911 .previous_ideaid').val(previous_ideaid);


    if (ideaid) {

        var current_ideatype = $('.s__12273_' + ideaid).attr('ideatype');

        //Editig an existing idea:
        $('#modal31911 .save_ideaid').val(ideaid);
        $('#modal31911 .hash_group').removeClass('hidden');
        $('#modal31911 .save_ideahashtag').val($('.ui_ideahashtag_' + ideaid + ':first').text());
        $('#modal31911 .save_ideavalue').val($('.ui_ideavalue_' + ideaid + ':first').text());

        //Display Current Media:
        display_media('media_editor_frame', 13572, ideaid);

    } else if (passon_ideaid) {

        $("#modal31911 .save_ideavalue").val(load_message);

    } else {

        //See the default passed to the form:
        var current_ideatype = 6677;

        //Hide hashtag:
        $('#modal31911 .hash_group').addClass('hidden');

        //See where we are at and append anything needed to the idea:
        var insert_message = '';
        if (!next_ideaid && !previous_ideaid) {
            var focus__node = parseInt($('#focus__node').val());
            if (focus__node == 12273) {
                //insert_message = '#'+$('#focus_handle').val()+' ';
            } else if (focus__node == 12274 && parseInt($('#focus__id').val()) != js_pl_id) {
                insert_message = '@' + $('#focus_handle').val() + ' ';
            }
        }

        if (insert_text.length && !insert_message.length) {
            insert_message = insert_text;
        }

        if (insert_message.length) {
            $("#modal31911 .save_ideavalue").val(insert_message);
        }

    }

    if (chainid) {
        $('#modal31911 .save_chainid').val(chainid);

        //Idea<>Ideas chains do not have an interaction message
        if (parseInt($('#focus__node').val()) != 12273 || ($('.ui_chainvalue_' + chainid + ':first') && $('.ui_chainvalue_' + chainid + ':first').text().length > 0)) {
            $('#modal31911 .save_chainvalue').val($('.ui_chainvalue_' + chainid + ':first').text());
            $('#modal31911 .save_frame').removeClass('hidden');
        }
    }


    if (!passon_ideaid) {

        //Source Reference:
        update_form_select(4737, current_ideatype, 1, false);

        $('#modal31911').modal('show');

        setTimeout(function () {
            //Adjust sizes:
            set_autosize($('#modal31911 .save_ideavalue'));
            set_autosize($('#modal31911 .save_chainvalue'));
        }, 233);

        var created_ideaid = load_idea_dynamic(ideaid, chainid, current_ideatype, true);

    }

    setTimeout(function () {
        //Focus on writing a message:
        $('#modal31911 .save_ideavalue').focus();
    }, 611);

}

function load_idea_dynamic(ideaid, chainid, current_ideatype, initial_loading) {

    $(".dynamic_item").addClass('hidden'); //Hide all current items...
    $(".dynamic_editing_loading").removeClass('hidden');
    var created_ideaid = 0;

    $.post("/controller/idea_editor", {
        ideaid: ideaid,
        chainid: chainid,
        current_ideatype: current_ideatype,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {

        $(".dynamic_editing_loading").addClass('hidden');

        if (data.status) {

            if (!ideaid && data.created_ideaid > 0) {
                console.log('NEW IDEA #' + data.created_ideaid + ' has been created');
                created_ideaid = data.created_ideaid;
                $('#modal31911 .created_ideaid').val(created_ideaid);
                ideaid = created_ideaid;
            }

            if (initial_loading) {

                //Initiate Idea  Uploader:
                load_cloudinary(13572, ideaid, ['#' + ideaid], '.uploader_13572', '#modal31911');

                //Track unsaved changes to prevent unwated modal closure:
                $("#modal31911 .unsaved_warning").change(function () {
                    has_unsaved_changes = true;
                });

            }

            var current_header = null;

            //Dynamic Input Fields:
            for (let i = 1; i <= js_sources___6404[42206]['m__message']; i++) {

                var index_i = i - 1;

                if (data.return_inputs[index_i] == undefined) {
                    data.return_inputs[index_i] = [];
                    data.return_inputs[index_i]["d__id"] = 0;
                    data.return_inputs[index_i]["d_chainid"] = 0;
                    data.return_inputs[index_i]["d__html"] = '';
                    data.return_inputs[index_i]["d__value"] = '';
                    data.return_inputs[index_i]["d__type_name"] = '';
                    data.return_inputs[index_i]["d__placeholder"] = '';
                    $("#modal31911 .dynamic_" + i).addClass('hidden');
                } else {
                    $("#modal31911 .dynamic_" + i).removeClass('hidden');
                }

                //Append profile header if changed:
                if (!current_header || current_header != data.return_inputs[index_i]["d__profile_header"]) {
                    current_header = data.return_inputs[index_i]["d__profile_header"];
                } else {
                    //Neutralize it:
                    data.return_inputs[index_i]["d__profile_header"] = '';
                }


                var is_locked = js_sourceids___32145.includes(parseInt(data.return_inputs[index_i]["d__id"]));
                if (is_locked && !data.return_inputs[index_i]["d__value"].length) {
                    //Hide since its locked without a value:
                    $("#modal31911 .dynamic_" + i + " .inner_dynamic").addClass('hidden');
                } else {
                    $("#modal31911 .dynamic_" + i + " .inner_dynamic").removeClass('hidden');
                }

                $("#modal31911 .dynamic_" + i + " .radio_frame").remove();
                $("#modal31911 .dynamic_" + i).attr('d__id', data.return_inputs[index_i]["d__id"]).attr('d_chainid', data.return_inputs[index_i]["d_chainid"]);

                if (data.return_inputs[index_i]["d__is_radio"]) {
                    $("#modal31911 .dynamic_" + i).prepend('<div class="radio_frame hideIfEmpty">' + data.return_inputs[index_i]["d__profile_header"] + data.return_inputs[index_i]["d__html"] + '</div>');
                    $("#modal31911 .dynamic_" + i + " .text_content").addClass('hidden');
                } else {
                    $("#modal31911 .dynamic_" + i).prepend('<div class="radio_frame hideIfEmpty">' + data.return_inputs[index_i]["d__profile_header"] + '</div>');
                    $("#modal31911 .dynamic_" + i + " .text_content").removeClass('hidden');
                    $("#modal31911 .dynamic_" + i + " h3").html(data.return_inputs[index_i]["d__html"]);
                    $("#modal31911 .dynamic_" + i + " input").attr('placeholder', data.return_inputs[index_i]["d__placeholder"]).attr('type', data.return_inputs[index_i]["d__type_name"]).val(data.return_inputs[index_i]["d__value"]).prop('disabled', is_locked);

                    if (chainid && parseInt($('#focus__node').val()) == 12274 && data.return_inputs[index_i]["d__id"] == parseInt($('#focus__id').val())) {
                        //Hide message textarea since this is already loaded in the dynamic inputs:
                        //$("#modal31911 .save_chainvalue").val('IGNORE_INPUT');
                        //$("#modal31911 .save_frame").addClass('hidden');
                    }
                }

            }

            setTimeout(function () {

                setup_popover();

            }, 377);

        } else if (data.message) {

            //Should not have an issue loading
            alert('ERROR:' + data.message);

        }
    });
    return created_ideaid;
}


var i_saving = false; //Prevent double saving
function idea_update() {

    if (i_saving) {
        console.log('Idea updating aborted');
        return false;
    }

    i_saving = true;
    $(".idea_update").html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');
    $("#modal31911 .save_results").html('');

    var current_ideaid = parseInt($('#modal31911 .save_ideaid').val());
    var created_ideaid = parseInt($('#modal31911 .created_ideaid').val());
    console.log('Idea updating begins #' + current_ideaid);

    //Fetch Media
    var gather_media_result = gather_media('#modal31911 .media_frame .media_item', 13572);
    if (!gather_media_result['upload_completed']) {
        i_saving = false;
        $(".idea_update").html('SAVE');
        $("#modal31911 .save_results").html('<span class="icon-block"><i class="far fa-exclamation-circle"></i></span> Error: ' + gather_media_result['error_message']);
        return false;
    }

    var modify_data = {
        focus__node: parseInt($('#focus__node').val()),
        focus__id: parseInt($('#focus__id').val()),
        save_ideaid: (current_ideaid > 0 ? current_ideaid : created_ideaid),
        save_chainid: $('#modal31911 .save_chainid').val(),
        next_ideaid: $('#modal31911 .next_ideaid').val(),
        previous_ideaid: $('#modal31911 .previous_ideaid').val(),
        save_chainsourcetype: $('.dropd_form_4486').attr('selected_value').trim(), //The final chain type as selected by user if they have the superpower
        focus_group: focus_group,
        save_chainvalue: $('#modal31911 .save_chainvalue').val().trim(),
        save_ideavalue: $('#modal31911 .save_ideavalue').val().trim(),
        save_ideahashtag: $('#modal31911 .save_ideahashtag').val().trim(),
        save_ideatype: $('.dropd_form_4737').attr('selected_value').trim(),
        uploaded_media: gather_media_result['uploaded_media'],
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    };

    //Append Dynamic Data:
    for (let i = 1; i <= js_sources___6404[42206]['m__message']; i++) {
        if ($('#modal31911 .dynamic_' + i).attr('d__id').length) {
            modify_data['save_dynamic_' + i] = $('#modal31911 .dynamic_' + i).attr('d_chainid').trim() + 'EXPLODETERMABC' + $('#modal31911 .dynamic_' + i).attr('d__id').trim() + 'EXPLODETERMABC' + $('#modal31911 .save_dynamic_' + i).val().trim();
        } else {
            //Should be the end of variables:
            break;
        }
    }

    $.post("/controller/idea_update", modify_data, function (data) {

        //Load Images:
        i_saving = false;
        $(".idea_update").html('SAVE');

        if (!data.status) {

            //Show Errors:
            $("#modal31911 .save_results").html('<span class="icon-block"><i class="far fa-exclamation-circle"></i></span> Error: ' + data.message);

        } else {

            if (data.redirect_idea) {
                //Give option to open the post:
                $(".i_footer_note").removeClass('hidden');
                $(".i_footer_note a").attr('href', data.redirect_idea);
                setTimeout(function () {
                    $(".i_footer_note").addClass('hidden');
                }, 6765);
            }

            //Update Handle & Href chains if needed:
            var old_handle = $(".ui_ideahashtag_" + modify_data['save_ideaid'] + ':first').text();
            var new_handle = modify_data['save_ideahashtag'];
            var on_focus__idea = parseInt($('#focus__node').val()) == 12273 && modify_data['save_ideaid'] == parseInt($('#focus__id').val());

            //Update Source Reference:
            $('.s__12273_' + modify_data['save_ideaid']).attr('ideatype', modify_data['save_ideatype']);
            ui_instant_select(4737, modify_data['save_ideatype'], modify_data['save_ideaid'], modify_data['save_chainid'], false);

            //Update Handle & Href chains if needed:
            if (old_handle != new_handle) {
                if (on_focus__idea) {
                    //Refresh page since focus item handle changed:
                    js_redirect(js_sources___42903[33286]['m__message'] + new_handle);
                } else {
                    //Update Hashtag & Chain:
                    $('.s__12273_' + modify_data['save_ideaid']).attr('ideahashtag', new_handle);
                    $(".ui_ideahashtag_" + modify_data['save_ideaid']).text(new_handle).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233); //Flash
                }
            }

            //Reset errors:
            has_unsaved_changes = false;
            $('#modal31911').modal('hide');

            //Update Idea Message:
            $('.ui_ideavalue_' + modify_data['save_ideaid']).text(modify_data['save_ideavalue']);

            //Insert idea into the page if new:
            console.log('START INSERTING');
            if (!current_ideaid && created_ideaid > 0 && focus_group > 0) {

                console.log('ADD NEW ' + modify_data['save_chainsourcetype'] + ' & x GROUP: ' + focus_group);

                $("#list-in-" + focus_group).append(data.return_ideacache_full);

                chain_counter(focus_group, 1);

                setTimeout(function () {
                    idea_sort_load(focus_group);
                }, 987);

            } else {

                console.log('UPDATE  ' + modify_data['save_chainsourcetype'] + ' & x GROUP: ' + focus_group);

                //Update Cache otherwise:
                $('.ui_ideacache_' + modify_data['save_ideaid']).html(data.return_ideacache_chains);

            }

            //Show more if on focus idea:
            if (on_focus__idea) {
                show_more(modify_data['save_ideaid']);
            }

            if (modify_data['save_chainid'] && modify_data['save_chainvalue'] != 'IGNORE_INPUT') {
                $('.ui_chainvalue_' + modify_data['save_chainid']).text(modify_data['save_chainvalue']);
            }

            //Tooltips:
            setTimeout(function () {
                setup_popover();
            }, 987);

        }
    });
}

function sort_media(sort_id) {
    var sort = Sortable.create(document.getElementById(sort_id), {
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
function load_cloudinary(uploader_id, s__id, uploader_tags = [], loading_button = null, loading_modal = null, loading_inline_container = null) {

    console.log('Initiating Uploader @' + uploader_id + ' with tags ' + uploader_tags.join(' & '));

    if (js_sources___42363[uploader_id] == undefined) {
        console.log('Unknown Uploader @' + uploader_id + ' Missing in @42363');
        return false;
    }

    media_cache[uploader_id] = [];
    //Fetch global defaults:
    var default_max_file_count = parseFloat(js_sources___6404[42382]['m__message']);

    var global_tags = ['@' + uploader_id, '@' + website_id, '@' + js_pl_id];
    var allow_videos = js_sources___42390[uploader_id] !== undefined;
    var allow_imgaes = js_sources___42389[uploader_id] !== undefined;
    var allow_audio = js_sources___42644[uploader_id] !== undefined;

    if (!allow_videos && !allow_imgaes && !allow_audio) {
        //Assume all are allowed:
        allow_audio = true;
        allow_videos = true;
        allow_imgaes = true;
    }

    //Initiate CLoudiary for cover:
    var max_file_count = (js_sources___42382[uploader_id] !== undefined && parseFloat(js_sources___42382[uploader_id]['m__message']) > 0 && parseFloat(js_sources___42382[uploader_id]['m__message']) < default_max_file_count ? parseFloat(js_sources___42382[uploader_id]['m__message']) : default_max_file_count);

    var enable_crop = (js_sources___42386[uploader_id] !== undefined);
    var force_crop = (js_sources___42387[uploader_id] !== undefined);


    var clientAllowedFormats = [];
    if (allow_videos) {
        clientAllowedFormats = clientAllowedFormats.concat(js_sources___42641[4258]['m__message'].split(' '));
    }
    if (allow_imgaes) {
        clientAllowedFormats = clientAllowedFormats.concat(js_sources___42641[4260]['m__message'].split(' '));
    }
    if (allow_audio) {
        clientAllowedFormats = clientAllowedFormats.concat(js_sources___42641[4259]['m__message'].split(' '));
    }

    var widget_setting = {

        multiple: (max_file_count > 1),
        max_files: max_file_count,
        maxFileSize: (2000 * 1000000),
        maxVideoFileSize: (2000 * 1000000),
        maxImageFileSize: (20 * 1000000),
        maxRawFileSize: (20 * 1000000),
        maxChunkSize: (100 * 1000000),

        clientAllowedFormats: clientAllowedFormats,
        cropping: enable_crop,
        showSkipCropButton: !force_crop,
        croppingShowBackButton: !force_crop,
        croppingAspectRatio: (js_sources___42388[uploader_id] !== undefined && parseFloat(js_sources___42388[uploader_id]['m__message']) > 0 ? parseFloat(js_sources___42388[uploader_id]['m__message']) : null),

        minImageWidth: (js_sources___42407[uploader_id] !== undefined && parseInt(js_sources___42407[uploader_id]['m__message']) > 0 ? parseInt(js_sources___42407[uploader_id]['m__message']) : null),
        maxImageWidth: (js_sources___42408[uploader_id] !== undefined && parseInt(js_sources___42408[uploader_id]['m__message']) > 0 ? parseInt(js_sources___42408[uploader_id]['m__message']) : null),
        minImageHeight: (js_sources___42409[uploader_id] !== undefined && parseInt(js_sources___42409[uploader_id]['m__message']) > 0 ? parseInt(js_sources___42409[uploader_id]['m__message']) : null),
        maxImageHeight: (js_sources___42410[uploader_id] !== undefined && parseInt(js_sources___42410[uploader_id]['m__message']) > 0 ? parseInt(js_sources___42410[uploader_id]['m__message']) : null),

        validateMaxWidthHeight: (js_sources___42411[uploader_id] !== undefined),
        croppingValidateDimensions: (js_sources___42412[uploader_id] !== undefined),

        inlineContainer: loading_inline_container,

        //Fixed variables:
        cloudName: 'menchcloud',
        uploadPreset: 'mench_uploader',
        showPoweredBy: false,
        autoMinimize: true,
        theme: 'minimal',
        tags: global_tags.concat(uploader_tags),
        sources: ['local', 'url', 'image_search', 'camera', 'unsplash'], //, 'google_drive', 'dropbox'
        defaultSource: 'local',
        styles: {
            palette: {
                window: "#FFFFFF",
                windowBorder: "#999999",
                tabIcon: "#000000",
                menuIcons: "#000000",
                textDark: "#000000",
                textLight: "#FFFFFF",
                chain: "#000000",
                action: "#000000",
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

        if (error || !result) {

            //Remove from screen if any:

            //Show error if any:
            if (result.failed && result.status && result.status.length > 0) {
                alert('ERROR for File [' + result.info.name + ']: ' + result.status);
                delete_media(uploader_id, result.info.id, true);
            }
            //Log error
            console.log('ERROR');
            console.log(result);


        } else if (result.event === "queues-start") {

            //Enable Sorting:
            if (uploader_id == 13572) {

                //Ideator Uploader
                sort_media('media_editor_frame');

            } else if (uploader_id == 43004) {

                //Discovery Uploader
                sort_media('media_outer_' + s__id);

            }

        } else if (result.event === "upload-added") {

            //Add Pending Loader
            console.log(result.event);
            console.log(result);

            //Append loaders:
            if (uploader_id == 42359) {

                //Source Cover Uploader:
                updatsourcecover('fas fa-yin-yang fa-spin');

            } else if (uploader_id == 13572) {

                //Ideator Uploader
                has_unsaved_changes = true;
                $('#media_editor_frame').append('<div id="' + result.info.id + '" class="media_item" media_sourceid="" playback_code="" sourceid="0"  sourcecover=""><span><i class="fas fa-yin-yang fa-spin"></i></span></div>');

            } else if (uploader_id == 43004) {

                //Discovery Uploader
                $('#media_outer_' + s__id).append('<div id="' + result.info.id + '" class="media_item" media_sourceid="" playback_code="" sourceid="0"  sourcecover=""><span><i class="fas fa-yin-yang fa-spin"></i></span></div>');

            }

        } else if (result.event === "success") {

            console.log(result.event);
            console.log(result);

            //Add uploaded media:
            if (uploader_id == 42359) {

                //Source Cover Uploader:
                updatsourcecover('https://res.cloudinary.com/menchcloud/image/upload/c_crop,g_custom/' + result.info.path);

            } else if (uploader_id == 13572 || uploader_id == 43004) {

                //Idea Uploader
                var playback_code = '';
                var media_sourceid = 0;
                if (result.info.format && result.info.format.length > 0) {
                    if (js_sources___42641[4259]['m__message'].split(' ').includes(result.info.format) && result.info.is_audio) {
                        //Audio
                        media_sourceid = 4259;
                        playback_code = result.info.secure_url;
                    } else if (js_sources___42641[4260]['m__message'].split(' ').includes(result.info.format) && result.info.resource_type == 'image') {
                        //Image
                        media_sourceid = 4260;
                        playback_code = (result.info.thumbnail_url ? result.info.thumbnail_url.replaceAll('c_limit,h_60,w_90', 'w_1597,h_1597,c_fit') : result.info.secure_url);
                    } else if (js_sources___42641[4258]['m__message'].split(' ').includes(result.info.format) && result.info.resource_type == 'video') {
                        //Video
                        media_sourceid = 4258;
                        playback_code = result.info.public_id;
                    }
                }

                //Append this to the main Source:
                if (media_sourceid) {

                    cloudinary_presource_view(uploader_id, result.info.id, media_sourceid, playback_code, (result.info.thumbnail_url ? result.info.thumbnail_url.replaceAll('c_limit,h_60,w_90', 'c_fill,h_377,w_377') : null), (result.info.original_filename ? js_sources___42294[media_sourceid]['m__title'] + ' ' + result.info.original_filename.replaceAll('_', ' ').replaceAll('-', ' ').replaceAll('  ', ' ').replaceAll('  ', ' ').replaceAll('  ', ' ') : js_sources___42294[media_sourceid]['m__title'] + ' File'));

                    media_cache[uploader_id][result.info.id] = result.info;
                    console.log(media_cache);

                } else {

                    //Log error
                    console.log('ERROR: Missing Media Type');

                }

            }

        }

    });

    if (!loading_inline_container && loading_button && widget) {
        //Attach to widget:
        $(loading_button).click(function (e) {
            widget.open();
        });
    }

    if (loading_modal && widget) {
        //Attach to widget:
        $(loading_modal).on('hidden.bs.modal', function () {
            widget.destroy({removeThumbnails: true})
                .then(() => {
                    console.log('Destroying Uploader @' + uploader_id);
                });
        });
    }

}


var confirm_removal_once_done = false;

function delete_media(uploader_id, info_id, skip_check = false) {

    if (!skip_check && !confirm_removal_once_done) {
        //Confirm removal once:
        var r = confirm("Are you sure you want to delete this?");
        if (!(r == true)) {
            return false;
        }
        confirm_removal_once_done = true; //Dont ask again
        has_unsaved_changes = true;
    }

    $('#' + info_id).remove();
    $('.media_frame #' + info_id).remove();

    if (media_cache[uploader_id][info_id]) {
        delete media_cache[uploader_id][info_id];
    }

}

function play_video(public_id) {
    var cld = cloudinary.videoPlayer('video_source_' + public_id, {cloudName: 'menchcloud'});
    cld.source(public_id);
}

function cloudinary_presource_view(uploader_id, info_id, media_sourceid, playback_code, sourcecover, sourcevalue, sourceid = 0) {

    //Update meta variables:
    $('#' + info_id).attr('media_sourceid', media_sourceid).attr('playback_code', playback_code).attr('sourceid', sourceid).attr('sourcecover', sourcecover);

    if (media_sourceid == 4258) {

        //Video
        $('#' + info_id).html('<input type="text" value="' + sourcevalue + '" placeholder="Source Title" class="hidden_superpower__10939" /><span title="' + js_sources___42294[media_sourceid]['m__title'] + '">' + js_sources___42294[media_sourceid]['m__cover'] + '</span><img src="' + sourcecover + '" /><a href="javascript:void(0)" onclick="delete_media(\'' + uploader_id + '\',\'' + info_id + '\')"><i class="far fa-xmark"></i></a>');
        //<video id="video_source_'+playback_code+'" controls class="cld-video-source vjs-fade-out cld-fluid cld-video-source-skin-light" poster="'+sourcecover+'"></video>
        //play_video(playback_code);

    } else if (media_sourceid == 4260) {

        //Image
        $('#' + info_id).html('<input type="text" value="' + sourcevalue + '" placeholder="Source Title" class="hidden_superpower__10939" /><img src="' + sourcecover + '" /><a href="javascript:void(0)" onclick="delete_media(\'' + uploader_id + '\',\'' + info_id + '\')"><i class="far fa-xmark"></i></a>');

    } else if (media_sourceid == 4259) {

        //Audio
        $('#' + info_id).html('<input type="text" value="' + sourcevalue + '" placeholder="Source Title" class="hidden_superpower__10939" /><span title="' + js_sources___42294[media_sourceid]['m__title'] + '">' + js_sources___42294[media_sourceid]['m__cover'] + '</span><audio controls src="' + playback_code + '"></audio><a href="javascript:void(0)" onclick="delete_media(\'' + uploader_id + '\',\'' + info_id + '\')"><i class="far fa-xmark"></i></a>');

    } else {

        //Unsupported file, should not happen since we limited file extensions to those we know:
        alert('Upload Error: Uploaded File ' + sourcevalue + ' is not a valid Video, Image or Audio file.');
        delete_media(uploader_id, info_id, true);

    }


}


function source_editor(sourceid = 0, chainid = 0, bar_title = null, chainvalue = null) {

    $('#modal31912').modal('show');

    //Reset Fields:
    has_unsaved_changes = false;

    $("#modal31912 .unsaved_warning").val('');

    $('#modal31912 .save_results').html('');
    $("#modal31912 .save_frame").addClass('hidden');
    $("#modal31912 .dynamic_item").attr('d__id', '').attr('d_chainid', '');
    $("#modal31912 .dynamic_item").attr('placeholder', '').val('');

    //Source resets:
    $('#search_cover').val('');
    $(".cover_history_button").addClass('hidden');
    $('#modal31912 .black-background-obs').removeClass('isSelected');

    //Load Instant Fields:
    var current_title = source_title(sourceid);
    var current_cover = $('.ui_sourcecover_' + sourceid + ':first').attr('raw_cover');

    $('#modal31912 .save_sourceid').val(sourceid);
    $('#modal31912 .save_chainid').val(chainid);
    $('#modal31912 .save_sourcehandle').val($('.ui_sourcehandle_' + sourceid + ':first').text());
    $('#modal31912 .save_sourcevalue').val(current_title);


    $('#modal31912 .random_animal').html('<i class="' + random_animal(true) + '"></i>');
    updatsourcecover(current_cover, false);


    if (chainid) {
        $('#modal31912 .save_chainvalue').val($('.ui_chainvalue_' + chainid).text());
        $('#modal31912 .save_frame').removeClass('hidden');
        setTimeout(function () {
            set_autosize($('#modal31912 .save_chainvalue'));
        }, 377);
    }
    setTimeout(function () {
        set_autosize($('#modal31912 .save_sourcevalue'));
    }, 377);


    $.post("/controller/source_editor", {
        sourceid: sourceid,
        chainid: chainid,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {

        if (data.status) {

            //Initiate Source Cover Uploader:
            load_cloudinary(42359, sourceid, ['@' + sourceid], '.uploader_42359', '#modal31912');

            //Dynamic Input Fields:
            var index_idea_content = 0;
            var current_header = null;

            for (let i = 1; i <= js_sources___6404[42206]['m__message']; i++) {

                var index_i = i - 1;
                if (data.return_inputs[index_i] == undefined) {
                    data.return_inputs[index_i] = [];
                    data.return_inputs[index_i]["d__id"] = 0;
                    data.return_inputs[index_i]["d_chainid"] = 0;
                    data.return_inputs[index_i]["d__html"] = '';
                    data.return_inputs[index_i]["d__value"] = '';
                    data.return_inputs[index_i]["d__type_name"] = '';
                    data.return_inputs[index_i]["d__placeholder"] = '';
                    $("#modal31912 .dynamic_" + i).addClass('hidden');
                } else {
                    index_idea_content++;
                    $("#modal31912 .dynamic_" + i).removeClass('hidden');
                }

                //Append profile header if changed:
                if (!current_header || current_header != data.return_inputs[index_i]["d__profile_header"]) {
                    current_header = data.return_inputs[index_i]["d__profile_header"];
                } else {
                    //Neutralize it:
                    data.return_inputs[index_i]["d__profile_header"] = '';
                }

                $("#modal31912 .dynamic_" + i + " .radio_frame").remove();
                $("#modal31912 .dynamic_" + i).attr('d__id', data.return_inputs[index_i]["d__id"]).attr('d_chainid', data.return_inputs[index_i]["d_chainid"]);

                var is_locked = js_sourceids___32145.includes(parseInt(data.return_inputs[index_i]["d__id"]));
                if (is_locked && !data.return_inputs[index_i]["d__value"].length) {
                    //Hide since its locked without a value:
                    $("#modal31912 .dynamic_" + i + " .inner_dynamic").addClass('hidden');
                } else {
                    $("#modal31912 .dynamic_" + i + " .inner_dynamic").removeClass('hidden');
                }

                if (data.return_inputs[index_i]["d__is_radio"]) {
                    $("#modal31912 .dynamic_" + i).prepend('<div class="radio_frame hideIfEmpty">' + data.return_inputs[index_i]["d__profile_header"] + data.return_inputs[index_i]["d__html"] + '</div>');
                    $("#modal31912 .dynamic_" + i + " .text_content").addClass('hidden');
                } else {
                    $("#modal31912 .dynamic_" + i).prepend('<div class="radio_frame hideIfEmpty">' + data.return_inputs[index_i]["d__profile_header"] + '</div>');
                    $("#modal31912 .dynamic_" + i + " .text_content").removeClass('hidden');
                    $("#modal31912 .dynamic_" + i + " h3").html(data.return_inputs[index_i]["d__html"]);
                    $("#modal31912 .dynamic_" + i + " input").attr('placeholder', data.return_inputs[index_i]["d__placeholder"]).attr('type', data.return_inputs[index_i]["d__type_name"]).val(data.return_inputs[index_i]["d__value"]).prop('disabled', is_locked);

                    if (chainid && ((parseInt($('#focus__node').val()) == 12274 && data.return_inputs[index_i]["d__id"] == parseInt($('#focus__id').val())) || data.return_inputs[index_i]["d__id"] == sourceid)) {
                        //Hide message textarea since this is already loaded in the dynamic inputs:
                        //$("#modal31912 .save_chainvalue").val('IGNORE_INPUT');
                        //$("#modal31912 .save_frame").addClass('hidden');
                    }
                }
            }

            //Add a second save button at the bottom if we have too much data:
            if (index_idea_content > 5) {
                $("#modal31912 .modal-footer").html('<button type="button" class="btn btn-default source_save_edit post_button" onclick="source_save_edit()">SAVE</button>');
            } else {
                $("#modal31912 .modal-footer").html('');
            }

            setTimeout(function () {
                setup_popover();
            }, 987);

        } else {

            //Should not have an issue loading
            alert('ERROR:' + data.message);

        }

    });

    //Track unsaved changes to prevent unwated modal closure:
    $("#modal31912 .unsaved_warning").change(function () {
        has_unsaved_changes = true;
    });

}

e_saving = false;

function source_save_edit() {

    if (e_saving) {
        return false;
    }

    e_saving = true;
    $(".source_save_edit").html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');
    $("#modal31912 .save_results").html('');

    var modify_data = {
        save_sourceid: $('#modal31912 .save_sourceid').val(),
        save_sourcevalue: $('#modal31912 .save_sourcevalue').val().trim(),
        save_sourcecover: $('#modal31912 .save_sourcecover').val().trim(),
        save_sourcehandle: $('#modal31912 .save_sourcehandle').val().trim(),
        save_chainid: $('#modal31912 .save_chainid').val(),
        save_chainvalue: $('#modal31912 .save_chainvalue').val().trim(),
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    };

    //Append Dynamic Data:
    for (let i = 1; i <= js_sources___6404[42206]['m__message']; i++) {
        if ($('#modal31912 .dynamic_' + i).attr('d__id').length) {
            modify_data['save_dynamic_' + i] = $('#modal31912 .dynamic_' + i).attr('d_chainid').trim() + 'EXPLODETERMABC' + $('#modal31912 .dynamic_' + i).attr('d__id').trim() + 'EXPLODETERMABC' + $('#modal31912 .save_dynamic_' + i).val().trim();
        } else {
            //Should be the end of variables:
            break;
        }
    }

    $.post("/controller/source_save_edit", modify_data, function (data) {

        e_saving = false;
        $(".source_save_edit").html('SAVE');

        if (!data.status) {

            //Show Errors:
            $("#modal31912 .save_results").html('<span class="icon-block"><i class="far fa-exclamation-circle"></i></span> Error: ' + data.message);

        } else {

            //Update Handle & Href chains if needed:
            var old_handle = $(".ui_sourcehandle_" + modify_data['save_sourceid'] + ':first').text();
            var new_handle = modify_data['save_sourcehandle'];
            if (old_handle != new_handle) {
                if (parseInt($('#focus__node').val()) == 12274 && modify_data['save_sourceid'] == parseInt($('#focus__id').val())) {
                    //Refresh page since focus item handle changed:
                    return js_redirect(js_sources___42903[42902]['m__message'] + new_handle);
                } else {
                    //Make adjustments to current page:
                    $('.s__12274_' + modify_data['save_sourceid']).attr('sourcehandle', new_handle);
                    $('.ui_sourcehandle_' + modify_data['save_sourceid']).text(new_handle);
                    $(".handle_hrefsource_" + modify_data['save_sourceid']).attr('href', $(".handle_hrefsource_" + modify_data['save_sourceid'] + ':first').attr('href').replaceAll(old_handle, new_handle));
                }
            }

            //Update Title:
            update_text_name(6197, modify_data['save_sourceid'], modify_data['save_sourcevalue']);

            //Update Raw Cover:
            $('.ui_sourcecover_' + modify_data['save_sourceid'] + ':first').attr('raw_cover', modify_data['save_sourcecover']);

            //Update Main Cover:
            update_cover_main(modify_data['save_sourcecover'], '.s__12274_' + modify_data['save_sourceid']);

            if (modify_data['save_chainid'] && modify_data['save_chainvalue'] != 'IGNORE_INPUT') {
                $('.ui_chainvalue_' + modify_data['save_chainid']).text(modify_data['save_chainvalue']);
            }

            //Tooltips:
            setup_popover();
            setTimeout(function () {
                setup_popover();
            }, 987);

            has_unsaved_changes = false;
            $('#modal31912').modal('hide');

            //Do we need to refresh the page?
            if (parseInt($('#focus__node').val()) == 12274 && parseInt($('#focus__id').val()) == modify_data['save_sourceid']) {
                //Refresh page since Source edited their own profile:
                js_redirect(js_sources___42903[42902]['m__message'] + $('#focus_handle').val());
            }

        }

    });

}


var busy_loading = false;
var current_page = [];

function chain_page_load() {

    if (!focus_group) {
        return false;
    }

    if (current_page[focus_group] == undefined) {
        current_page[focus_group] = 1;
    }

    var current_total_count = parseInt($('.headline_body_' + focus_group).attr('read-counter')); //Total of that item
    var has_more_to_load = (current_total_count > parseInt(js_sources___6404[11064]['m__message']) * current_page[focus_group]);

    if (!has_more_to_load) {
        return false;
    } else if (busy_loading) {
        return false;
    }
    busy_loading = true;


    current_page[focus_group]++; //Now we can increment current page
    $('<div class="load-more"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Loading More</div>').insertAfter('#list-in-' + focus_group);
    $.post("/controller/chain_page_load", {
        focus__node: parseInt($('#focus__node').val()),
        focus__id: parseInt($('#focus__id').val()),
        chainsourcetype: focus_group,
        current_page: current_page[focus_group],
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('.load-more').remove();
        if (data.length) {
            $('#list-in-' + focus_group).append(data);
            x_set_start_text();
            load_card_clickers();
            setup_popover();
        }
        busy_loading = false;
    });


}


function toggle_max_view(css_class) {

    //Toggle main class:
    $('.' + css_class).toggleClass('hidden');

    if ($(".fixed-top").hasClass("maxcontain")) {
        //Minimize:
        $('.maxcontain').addClass('container').removeClass('maxcontain');
    } else {
        //Maximize:
        $('.container').addClass('maxcontain').removeClass('container');
    }

}


//Adds OR chains Sources to Sources
var source_is_adding = false;

function source_create(chainsourcetype, source_current_id) {

    if (source_is_adding) {
        return false;
    }

    //if source_current_id>0 it means we're adding an existing Source, in which case source_new_string should be null
    //If source_current_id=0 it means we are creating a new Source and then adding it, in which case source_new_string is required
    source_is_adding = true;

    var input = $('.new-list-' + chainsourcetype + ' .add-input');

    var original_photo = $('.mini-cover.card-12274.card-id-' + source_current_id + ' .cover-btn').html();
    $('.mini-cover.card-12274.card-id-' + source_current_id + ' .cover-btn').html('<i class="fas fa-yin-yang fa-spin"></i>');
    var source_new_string = null;
    if (source_current_id == 0) {
        source_new_string = input.val();
        if (source_new_string.length < 1) {
            alert('Missing Source name or URL, try again');
            input.focus();
            return false;
        }
    }

    //Add via Ajax:
    $.post("/controller/source_create", {

        focus__node: parseInt($('#focus__node').val()),
        chainsourcetype: chainsourcetype,
        focus__id: parseInt($('#focus__id').val()),
        source_current_id: source_current_id,
        source_new_string: source_new_string,
        js_request_uri: js_request_uri, //Always append to AJAX Calls

    }, function (data) {

        source_is_adding = false;

        if (data.status) {

            //Raw input to make it ready for next URL:
            //input.focus();

            //Add new object to list:
            chain_counter(chainsourcetype, 1);

            //See if we previously have a list in place?
            if ($("#list-in-" + chainsourcetype + " .card-12274").length > 0) {
                //Downwards add to start"
                $("#list-in-" + chainsourcetype + " .card-12274:first").before(data.source_new_echo);
            } else {
                //Raw list, add before input filed:
                $("#list-in-" + chainsourcetype).prepend(data.source_new_echo);
            }

            //Allow inline editing if enabled:
            x_set_start_text();

            setTimeout(function () {
                setup_popover();
                source_sort_load(chainsourcetype);
            }, 987);

            //Hide Coin:
            $('.mini-cover.card-12274.card-id-' + source_current_id).fadeOut();

        } else {
            //We had an error:
            alert(data.message);
        }

    });
}


var i_is_adding = false;

function idea_create(chainsourcetype, chain_ideaid) {

    alert('not up yet');
    return false;

    /*
     *
     * Either creates an IDEA chain between focus_id & chain_ideaid
     * OR will create a new idea based on input text and then chain it
     * to #focus_id (In this case chain_ideaid=0)
     *
     * */

    if (i_is_adding) {
        return false;
    }

    //Remove results:
    i_is_adding = true;
    var sort_idea_grabr = ".card_cover";
    var input_field = $('.new-list-' + chainsourcetype + ' .add-input');
    var idea_createtext = input_field.val();


    //We either need the idea name (to create a new idea) or the chain_ideaid>0 to create an IDEA chain:
    if (!chain_ideaid && idea_createtext.length < 1) {
        alert('Missing Idea');
        input_field.focus();
        return false;
    }

    //Set processing status:
    input_field.addClass('dynamic_saving');
    add_to_list(chainsourcetype, sort_idea_grabr, '<div id="tempLoader" class="col-6 col-md-4 no-padding show_all_i"><div class="cover-wrapper"><div class="black-background-obs cover-chain"><div class="cover-btn"><i class="fas fa-yin-yang fa-spin"></i></div></div></div></div>', 0);

    //Update backend:
    $.post("/controller/idea_create", {
        chainsourcetype: chainsourcetype,
        focus__node: parseInt($('#focus__node').val()),
        focus__id: parseInt($('#focus__id').val()),
        idea_createtext: idea_createtext,
        chain_ideaid: chain_ideaid
    }, function (data) {

        //Delete loader:
        $("#tempLoader").remove();
        input_field.removeClass('dynamic_saving').prop("disabled", false).focus();
        i_is_adding = false;

        if (data.status) {

            //Add new
            add_to_list(chainsourcetype, sort_idea_grabr, data.idea_create_html, 1);

            //Lookout for textinput updates
            x_set_start_text();
            load_cards();
            set_autosize($('.texttype_lg'));

            //Hide Coin:
            $('.mini-cover.card-12273.card-id-' + chain_ideaid).fadeOut();

        } else {
            //Show errors:
            alert(data.message);
        }

    });

    //Return false to prevent <form> submission:
    return false;

}


function validURL(str) {
    return str && str.length && str.substring(0, 4) == 'http';
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


function x_set_start_text() {
    $('.x_set_class_text').keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            source_text_update(this);
            e.preventDefault();
        }
    }).change(function () {
        source_text_update(this);
    });
}

function update_text_name(cache_sourceid, sourceid, sourcevalue) {
    var target_element = ".text__" + cache_sourceid + "_" + sourceid;
    $(target_element).text(sourcevalue).attr('old-value', sourcevalue); //.val(sourcevalue)
    set_autosize($(target_element));
}


var setting_text = false;

function source_text_update(this_grabr) {

    if (setting_text) {
        return false;
    }

    setting_text = true;
    var modify_data = {
        sourceid: parseInt($(this_grabr).attr('sourceid')),
        cache_sourceid: parseInt($(this_grabr).attr('cache_sourceid')),
        idea_createtext: $(this_grabr).val().trim(),
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    };

    //See if anything changes:
    if ($(this_grabr).attr('old-value') == modify_data['idea_createtext']) {
        //Nothing changed:
        return false;
    }

    //Grey background to indicate saving
    var target_element = '.text__' + modify_data['cache_sourceid'] + '_' + modify_data['s__id'];
    $.post("/controller/source_text_update", modify_data, function (data) {

        if (!data.status) {

            //Reset to original value:
            $(target_element).val(data.original_val);

            //Show error:
            alert(data.message);

        } else {

            //If Updating Text, Updating Corresponding Fields:
            update_text_name(modify_data['cache_sourceid'], modify_data['s__id'], modify_data['idea_createtext']);

        }

        setting_text = false;

    });
}


function chain_counter(chainsourcetype, adjustment_count) {
    $('.xtypecounter' + chainsourcetype).text((parseInt($('.headline_body_' + chainsourcetype).attr('read-counter')) + adjustment_count));
}


function search_enabled() {
    return universal_search_enabled && parseInt(js_sources___6404[12678]['m__message']);
}


function show_more(ideaid) {
    console.log('SHOW MORE #' + ideaid);
    $('.cache_frame_' + ideaid + ' .line, .cache_frame_' + ideaid + ' .inner_line').removeClass('hidden');
    $('.cache_frame_' + ideaid + ' .show_more_line').addClass('hidden');
}

function set_autosize(theobject) {
    autosize(theobject);
    setTimeout(function () {
        autosize.update(theobject);
    }, 13);
}


function idea_sort_load(chainsourcetype) {

    load_cards();

    console.log('Tring to load Idea Sort for @' + chainsourcetype);
    if (!js_sourceids___4603.includes(chainsourcetype)) {
        console.log(chainsourcetype + ' is not sortable');
        return false;
    }

    setTimeout(function () {

        var theobject = document.getElementById("list-in-" + chainsourcetype);
        if (!theobject) {
            //due to duplicate ideas belonging in this idea:
            console.log(chainsourcetype + ' failed to find sortable object');
            return false;
        }

        //Make sure beow minimum sorting requirement:
        if ($("#list-in-" + chainsourcetype + " .sort_draggable").length >= parseInt(js_sources___6404[11064]['m__message'])) {
            console.log(chainsourcetype + ' has ' + $("#list-in-" + chainsourcetype + " .sort_draggable").length + ' items which is more than the page limit of ' + js_sources___6404[11064]['m__message']);
            return false;
        } else if ($("#list-in-" + chainsourcetype + " .sort_draggable").length < 2) {
            console.log('Less than 2 items to sort ' + chainsourcetype);
            return false;
        } else {

            console.log(chainsourcetype + ' sorting load success');
            $('.sort_idea_frame').removeClass('hidden');

            //Load sorter:
            var sort = Sortable.create(theobject, {
                animation: 144, // ms, animation speed moving items when sorting, `0` � without animation
                draggable: "#list-in-" + chainsourcetype + " .sort_draggable", // Specifies which items inside the element should be sortable
                handle: "#list-in-" + chainsourcetype + " .sort_idea_grab", // Restricts sort start click/touch to the specified element
                onUpdate: function (evt/**Event*/) {

                    var sort_rank = 0;
                    var new_x_order = [];
                    $("#list-in-" + chainsourcetype + " .sort_draggable").each(function () {
                        var chainid = parseInt($(this).attr('chainid'));
                        if (chainid > 0) {
                            sort_rank++;
                            new_x_order[sort_rank] = chainid;
                        }
                    });

                    //Update order:
                    if (sort_rank > 0) {
                        $.post("/controller/idea_sort_load", {
                            new_x_order: new_x_order,
                            chainsourcetype: chainsourcetype,
                            js_request_uri: js_request_uri, //Always append to AJAX Calls
                        }, function (data) {
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
    }, 1500);

}


var current_focus = 0;

function remove_ui_class(item, index) {
    var the_class = 'custom_ui_' + current_focus + '_' + item;
    $('body').removeClass(the_class);
}

function source_select_apply(focus__id, selected_sourceid, enable_mulitiselect, down_sourceid, right_ideaid) {

    //Any warning needed?
    if (js_sourceids___31780.includes(selected_sourceid) && !confirm(js_sources___31780[selected_sourceid]['m__message'])) {
        return false;
    }

    var field_required = js_sourceids___28239.includes(focus__id);
    var was_previously_selected = ($('.radio-' + focus__id + ' .item-' + selected_sourceid).hasClass('active') ? 1 : 0);

    //Save the rest of the content:
    if (!enable_mulitiselect && field_required && was_previously_selected) {
        //Nothing to do here:
        return false;
    }

    //Updating Customizable Theme?
    if (js_sourceids___13890.includes(focus__id)) {
        current_focus = focus__id;
        $('body').removeClass('custom_ui_' + focus__id + '_');
        window['js_sourceids___' + focus__id].forEach(remove_ui_class); //Removes all Classes
        $('body').addClass('custom_ui_' + focus__id + '_' + selected_sourceid);
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-' + focus__id + ' .item-' + selected_sourceid + ' .change-results';
    var initial_icon = $(notify_el).html();
    $(notify_el).html('<i class="fas fa-yin-yang fa-spin"></i>');


    if (!enable_mulitiselect) {
        //Clear all selections:
        $('.radio-' + focus__id + ' .list-group-item').removeClass('active');
        $('.radio-' + focus__id + ' .checked_icon').remove();
    }

    //Enable currently selected:
    if ((enable_mulitiselect || !field_required) && was_previously_selected) {
        $('.radio-' + focus__id + ' .item-' + selected_sourceid).removeClass('active');
        $('.radio-' + focus__id + ' .item-' + selected_sourceid + ' .checked_icon').remove();
    } else {
        $('.radio-' + focus__id + ' .item-' + selected_sourceid).addClass('active');
        $('.radio-' + focus__id + ' .item-' + selected_sourceid + ' .inner_headline').after('<span class="icon-block-sm checked_icon"><i class="far fa-check"></i></span>');
    }

    $.post("/controller/source_select_apply", {
        focus__id: focus__id,
        down_sourceid: down_sourceid,
        right_ideaid: right_ideaid,
        selected_sourceid: selected_sourceid,
        enable_mulitiselect: enable_mulitiselect,
        was_previously_selected: was_previously_selected,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {

        $(notify_el).html(initial_icon);
        setup_popover();

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


function update_form_select(element_id, source_createid, initial_loading, show_title) {
    console.log('update_form_select: ' + element_id + '/' + source_createid);

    //Toggles UI for FORM Selector
    $('.dropd_form_' + element_id + ' .dropdown-item').removeClass('active');
    $('.dropd_form_' + element_id + ' .optiond_' + source_createid).addClass('active');
    $('.dropd_form_' + element_id).attr('selected_value', source_createid);
    if (show_title) {
        $('.dropd_form_' + element_id + ' .current_content').html($('.dropd_form_' + element_id + ' .content_' + source_createid).html());
    } else {
        //Just replace icon:
        $('.dropd_form_' + element_id + ' .current_content span').html($('.dropd_form_' + element_id + ' .content_' + source_createid + ' span').html());
    }
    if (!initial_loading) {
        if (element_id == 4737) {
            //Changing Source Reference would re-load dynamic fields based on type:
            has_unsaved_changes = true;
            console.log('Reloading: ' + element_id + ' with value: ' + ' NEW ID ' + source_createid + ' / ' + $('#modal31911 .created_ideaid').val());
            load_idea_dynamic($('#modal31911 .created_ideaid').val(), 0, source_createid, false);
            //Add handle to text:
            insertText($(".save_ideavalue"), '@' + source_createid);
        }
    }
}

function ui_instant_select(element_id, source_createid, o__id, chainid, show_full_name) {

    //Update x:
    console.log('UI instant .dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .btn' + source_createid);
    var data_object = eval('js_sources___' + element_id);
    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .btn').html('<span class="icon-block-sm">' + data_object[source_createid]['m__cover'] + '</span>' + (show_full_name ? data_object[source_createid]['m__title'] : ''));

    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .drop_item_instant_' + element_id + '_' + o__id + '_' + chainid).removeClass('active');
    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .optiond_' + source_createid + '_' + o__id + '_' + chainid).addClass('active');

    var selected_sourceid = $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid).attr('selected_value');
    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid).attr('selected_value', source_createid);


    var main_object_type = 0;
    var main_object_update = false;

    if (element_id == 4737) {
        //Source Reference:
        $('.s__12273_' + o__id).attr('ideatype', source_createid);
        main_object_type = 12273;
        main_object_update = 'ideatype';
    }

    if (main_object_type > 0 && main_object_update) {
        $('.s__' + main_object_type + '_' + o__id).attr(main_object_update, source_createid);
    }

}

function idea_delete(ideaid) {

    var migratehandle = prompt("Are you sure you want to permanently delete this idea?\nYou can reference #anotherIdea to migrate to or leave blank to delete permanently...", "#");
    if (migratehandle === null) {
        return false;
    }

    $.post("/controller/idea_delete", {
        focus__id: parseInt($('#focus__id').val()),
        ideaid: ideaid,
        migratehandle: migratehandle,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {

            if (data.delete_redirect && data.delete_redirect.length > 0) {

                //Go to main idea page:
                js_redirect(data.delete_redirect);

            } else if (data.delete_element && data.delete_element.length > 0) {

                //Go to main idea page:
                setTimeout(function () {
                    //Restore background:
                    $(data.delete_element).fadeOut();
                    setTimeout(function () {
                        //Restore background:
                        $(data.delete_element).remove();
                    }, 55);
                }, 377);

            }

        } else {

            //Show error:
            alert(data.message);

        }
    });

}


function source_delete(sourceid) {

    var migratehandle = prompt("Are you sure you want to permanently delete this Source?\nYou can reference @anotherSource to migrate to or leave blank to delete permanently...", "@");
    if (migratehandle === null) {
        return false;
    }

    $.post("/controller/source_delete", {
        focus__id: parseInt($('#focus__id').val()),
        sourceid: sourceid,
        migratehandle: migratehandle,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {

            if (data.delete_redirect && data.delete_redirect.length > 0) {

                //Go to main idea page:
                js_redirect(data.delete_redirect);

            } else if (data.delete_element && data.delete_element.length > 0) {

                //Go to main idea page:
                setTimeout(function () {
                    //Restore background:
                    $(data.delete_element).fadeOut();
                    setTimeout(function () {
                        //Restore background:
                        $(data.delete_element).remove();
                    }, 55);
                }, 377);

            }

        } else {

            //Show error:
            alert(data.message);

        }
    });

}


function selector(element_id, source_createid, o__id = 0, chainid = 0, show_full_name = false) {

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


    if ($('.dropmenu_instant_' + element_id).length && !o__id) {
        o__id = $('.dropmenu_instant_' + element_id + ':first').attr('o__id');
        chainid = $('.dropmenu_instant_' + element_id + ':first').attr('chainid');
    }

    console.log('Attempt to update dropdown @' + element_id + ' to @' + source_createid);

    source_createid = parseInt(source_createid);

    //Deleting Anything?
    var main_object_type = 0;
    var main_object_update = false;
    var migratehandle = null;

    //Show Loading
    var data_object = eval('js_sources___' + element_id);
    if (!data_object[source_createid]) {
        alert('Invalid element ID: ' + element_id + '/' + source_createid + '/' + o__id + '/' + chainid + '/' + show_full_name);
        return false;
    }
    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .btn').html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/controller/source_select", {
        focus__id: parseInt($('#focus__id').val()),
        o__id: o__id,
        element_id: element_id,
        source_createid: source_createid,
        migratehandle: migratehandle,
        chainid: chainid,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {

            //Update on page:
            ui_instant_select(element_id, source_createid, o__id, chainid, show_full_name);

            if (data.delete_redirect && data.delete_redirect.length > 0) {

                //Go to main idea page:
                js_redirect(data.delete_redirect);

            } else if (data.delete_element && data.delete_element.length > 0) {

                //Go to main idea page:
                setTimeout(function () {
                    //Restore background:
                    $(data.delete_element).fadeOut();
                    setTimeout(function () {
                        //Restore background:
                        $(data.delete_element).remove();
                    }, 55);
                }, 377);

            }

            if (data.auto_open_idea_modal) {
                //We need to show idea modal:
                idea_editor(o__id, $('.s__12273_' + o__id).attr('chainid'));
            }

        } else {

            //Show error:
            alert(data.message);

        }
    });
}


function source_sort_save(chainsourcetype) {

    var new_chainkey = [];
    var sort_rank = 0;

    $("#list-in-" + chainsourcetype + " .card-12274").each(function () {
        //Fetch variables for this idea:
        var sourceid = parseInt($(this).attr('sourceid'));
        var chainid = parseInt($(this).attr('chainid'));

        sort_rank++;

        //Store in DB:
        new_chainkey[sort_rank] = chainid;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0) {
        //Update backend:
        $.post("/controller/source_sort_save", {
            sourceid: parseInt($('#focus__id').val()),
            chainsourcetype: chainsourcetype,
            new_chainkey: new_chainkey,
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {
            //Update UI to confirm with member:
            if (!data.status) {
                //There was some sort of an error returned!
                alert(data.message);
            }
        });
    }
}


function chain_sort_reset() {
    var r = confirm("Reset sorting?");
    if (r == true) {

        var focus__node = parseInt($('#focus__node').val());
        var focus__id = parseInt($('#focus__id').val());
        var focus_handle = $('#focus_handle').val();

        //Update via call:
        $.post("/controller/chain_sort_reset", {
            focus__node: focus__node,
            focus__id: focus__id,
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {

            if (!data.status) {

                //Ooops there was an error!
                alert(data.message);

            } else {

                //Refresh page:
                if (focus__node == 12273) {
                    //Ideation
                    js_redirect(js_sources___42903[33286]['m__message'] + focus_handle);
                } else if (focus__node == 12274) {
                    //Sourcing
                    js_redirect(js_sources___42903[42902]['m__message'] + focus_handle);
                }

            }
        });
    }
}


function chain_clicked(ideaid) {
    $(".chain_click_" + ideaid).addClass('was_clicked');
}


var next_processing = false;

function idea_discovered(do_skip) {

    if (next_processing) {
        return false;
    }
    next_processing = true;

    var selection_ideaid = [];

    if ($(".chain_click")[0] && !$(".was_clicked")[0]) {
        next_processing = false;
        alert('Click on the URL to open it in a new window before you continue.');
        return false;
    }

    if (js_sourceids___7712.includes(focus_ideatype)) {
        //Choose
        $(".this_selector").each(function () {
            var selection_ideaid_this = parseInt($(this).attr('selection_ideaid'));
            if ($('.this_selector_' + selection_ideaid_this + ' i').hasClass('fa-square-check') || $(".this_selector").length == 1) {
                selection_ideaid.push(selection_ideaid_this);
            }
        });
    }

    //Compile all next ideas, if any:
    var next_idea_data = []; //Aggregate the data for all children
    $("#list-in-12840 .edge-cover").each(function () {

        //Fetch Media
        var gather_media_result = gather_media('.media_frame_' + $(this).attr('ideaid') + ' .media_item', 43004);
        if (!gather_media_result['upload_completed']) {
            next_processing = false;
            alert('MEDIA ERROR: ' + gather_media_result['error_message']);
            return false;
        }

        next_idea_data.push({
            ideaid: parseInt($(this).attr('ideaid')),
            idea_createtext: ($('.s__12273_' + $(this).attr('ideaid') + ' .x_write').val() ? $('.s__12273_' + $(this).attr('ideaid') + ' .x_write').val() : null),
            ideakey: ($('.input_ui_' + $(this).attr('ideaid') + ' .ideakey').val() ? $('.input_ui_' + $(this).attr('ideaid') + ' .ideakey').val() : 0),
            uploaded_media: gather_media_result['uploaded_media'],
        });

    });


    var gather_media_result = gather_media('.media_frame_' + $('#focus__id').val() + ' .media_item', 43004);
    if (!gather_media_result['upload_completed']) {
        next_processing = false;
        alert('MEDIA ERROR: ' + gather_media_result['error_message']);
        return false;
    }

    //Payment Error?
    if (focus_ideatype == 26560 && !$(".tickets_issued")[0]) {
        //Ticket not yet issued!
        alert('Pay Now via Paypal before going next.');
        next_processing = false;
        return false;
    } else if (focus_ideatype == 43758) {

        //Invoice Process, make sure something is in the cart:
        var invoice_items = {};
        var total_count = 0;
        var total_price = 0;
        var currency_code = '';

        $(".sale_controller").each(function (i, e) {

            currency_code = $(this).attr('unitcurrency');
            var item_ideaid = parseInt($(this).attr('ideaid'));
            var item_title = $('.cache_frame_' + item_ideaid + ' .first_line').text();
            var quantity = parseFloat($('.input_ui_' + item_ideaid + ' .current_count').text());

            if (quantity > 0) {
                var this_item = {
                    ideaid: item_ideaid,
                    name: item_title,
                    description: $('.cache_frame_' + item_ideaid).text().replace(item_title, ''),
                    quantity: quantity,
                    currency_code: currency_code,
                    currency_value: parseFloat($(this).attr('unitprice')),
                    unit_of_measure: 'QUANTITY',
                };
                invoice_items[i] = this_item;
                total_count += this_item.quantity;
                total_price += (this_item.quantity * this_item.currency_value);
            }
        });

        if (total_count > 0) {

            //Load:
            var original_html = $('.idea_discovered_btn').html();
            $('.idea_discovered_btn').html('<span class="icon-block" style="margin:5px 0 -5px;"><i class="fas fa-yin-yang fa-spin"></i></span>');

            //Submit to go next:
            $.post("/invoice", {
                target_ideahashtag: $('#target_ideahashtag').val(),
                target_ideaid: parseInt($('#target_ideaid').val()),
                focus__id: parseInt($('#focus__id').val()),
                invoice_items: invoice_items,
                currency_code: currency_code,
                total_price: total_price,
                do_skip: do_skip,
                js_request_uri: js_request_uri, //Always append to AJAX Calls
            }, function (data) {
                if (data.status) {
                    //Go to redirect message:
                    alert(data.message);
                    js_redirect(data.next__url);
                } else {
                    //Show error:
                    $('.idea_discovered_btn').html(original_html);
                    alert(data.message);
                    next_processing = false;
                }
            });

        } else {
            //No items added, give an error:
            alert('Must add some items to create an invoice');
        }

        return false;
    }


    //Load:
    var original_html = $('.idea_discovered_btn').html();
    $('.idea_discovered_btn').html('<span class="icon-block" style="margin:5px 0 -5px;"><i class="fas fa-yin-yang fa-spin"></i></span>');

    //Submit to go next:
    $.post("/controller/idea_discovered", {
        target_ideahashtag: $('#target_ideahashtag').val(),
        target_ideaid: parseInt($('#target_ideaid').val()),
        source_submitted_data: {
            ideaid: parseInt($('#focus__id').val()),
            idea_createtext: ($('.focus-cover .x_write').val() ? $('.focus-cover .x_write').val() : null),
            ideakey: ($('.input_ui_' + parseInt($('#focus__id').val()) + ' .ideakey').val() ? $('.input_ui_' + parseInt($('#focus__id').val()) + ' .ideakey').val() : 0),
            uploaded_media: gather_media_result['uploaded_media'],
        },
        do_skip: do_skip,
        selection_ideaid: selection_ideaid,
        next_idea_data: next_idea_data,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {
            //Go to redirect message:
            js_redirect(data.next__url);
        } else {
            next_processing = false;
            //Show error:
            $('.idea_discovered_btn').html(original_html);
            alert(data.message);
        }
    });

}