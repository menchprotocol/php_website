//Define some global variables:
var has_unsaved_changes = false; //Tracks user/post modal edits
var focus_group = 0;


if (!js_pl_id || !js_userids___43512.includes(js_pl_id)) {
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
        clarity("set", "user_user", js_pl_user);
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
        $('#modal31912 .fa_search a').attr('href', 'https://fontawesome.com/search?q=' + encodeURIComponent(split_cover_2arr2[0]) + '&o=r');
        $('#modal31912 .save_usercover,  #modal31912 .fa_search').removeClass('hidden');
    } else {
        $('#modal31912 .save_usercover, #modal31912 .fa_search').addClass('hidden');
    }
}

function watch_cover() {
    $('#modal31912 .save_usercover').change(function () {

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


function chain_preview(apply_id, s__id) {

    //Select first:
    var first_id = $('#modal' + apply_id + ' .mass_action_toggle option:first').val();
    $('.mass_action_item').addClass('hidden');
    $('.mass_id_' + first_id).removeClass('hidden');
    $('#modal' + apply_id + ' .mass_action_toggle').val(first_id);
    $('#modal' + apply_id + ' input[name="s__id"]').val(s__id);
    $('#modal' + apply_id).modal('show');
    $('[data-toggle="tooltip"]').tooltip();

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

    $('.user_text_finder').on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('@' + suggestion.s__user);

    }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

        source: function (q, cb) {
            index_algolia.search(q, {
                filters: 's__type=12274' + search_and_filter,
                hitsPerPage: js_users___6404[31112]['m__message'],
            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        displayKey: function (suggestion) {
            return '@' + suggestion.s__user;
        },
        templates: {
            suggestion: function (suggestion) {
                return search_js_line(suggestion, '@');
            },
            empty: function (data) {
                return '<div class="main__title"><i class="far fa-exclamation-circle"></i> No Users Found</div>';
            },
        }

    }]);

    $('.i_text_finder').on('autocomplete:selected', function (event, suggestion, dataset) {

        $(this).val('#' + suggestion.s__user);

    }).autocomplete({hint: false, autoselect: false, minLength: 2}, [{

        source: function (q, cb) {
            index_algolia.search(q, {
                filters: 's__type=12273' + search_and_filter,
                hitsPerPage: js_users___6404[31112]['m__message'],
            }, function (error, content) {
                if (error) {
                    cb([]);
                    return;
                }
                cb(content.hits, content);
            });
        },
        displayKey: function (suggestion) {
            return '#' + suggestion.s__user;
        },
        templates: {
            suggestion: function (suggestion) {
                return search_js_line(suggestion, '#');
            },
            empty: function (data) {
                return '<div class="main__title"><i class="far fa-exclamation-circle"></i> No Posts Found</div>';
            },
        }
    }]);

}


function search_title(suggestion) {
    var title = (suggestion._highlightResult && suggestion._highlightResult.s__title.value ? suggestion._highlightResult.s__title.value : suggestion.s__title);
    var max_limit = 89;
    return htmlentitiesjs(title.length >= max_limit ? title.substring(0, max_limit) + ' ' : title);
}


function search_js_line(suggestion, default_user = '@') {
    if (suggestion.s__type == 12273) {
        return '<span class="grey">' + default_user + suggestion.s__user + '</span>&nbsp;<span class="main__title">' + search_title(suggestion) + '</span>';
    } else if (suggestion.s__type == 12274) {
        return '<span class="icon-block-xs">' + view_cover_js(suggestion.s__cover) + '</span><span class="grey">' + default_user + suggestion.s__user + '</span>&nbsp;<span class="main__title">' + search_title(suggestion) + '</span>';
    }
}

function user_load_finder(chainusertype) {
    console.log(chainusertype + " user_load_finder()");
    //Load Search:
    var icons_listed = [];
    $('.new-list-' + chainusertype + ' .add-input').keypress(function (e) {
        icons_listed = [];
        var code = (e.keyCode ? e.keyCode : e.which);
        if ((code == 13) || (e.ctrlKey && code == 13)) {
            user_create(chainusertype, 0);
            return true;
        }
    });
}

function search_js_cover(chainusertype, suggestion, action_id) {

    if (!js_userids___26010.includes(chainusertype)) {
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
    if (chainusertype == 26011) {
        //Mini Coin
        var search_only_app = $("#website_finder").val().charAt(0) == '-';
        var target_url = (search_only_app ? suggestion.s__url.replace('/@', '/') : suggestion.s__url);
        return '<div title="ID ' + suggestion.s__id + '" class="card_cover mini-cover card-' + suggestion.s__type + ' ' + (search_only_app ? ' card-6287 ' : '') + ' card-id-' + suggestion.s__id + ' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="' + target_url + '" class="black-background-obs cover-chain coinType' + suggestion.s__type + '" ' + background_image + '><div class="cover-btn">' + icon_image + '</div></a></div><div class="cover-content"><div class="inner-content"><a href="' + target_url + '" class="main__title">' + '<span class="main__title">' + suggestion.s__title + '</span>' + '</a></div></div></div>';
    } else if (chainusertype == 26013) {
        //Chain User
        return '<div title="ID ' + suggestion.s__id + '" class="card_cover mini-cover card-' + suggestion.s__type + ' card-id-' + suggestion.s__id + ' col-4 col-md-2 col-sm-3 no-padding"><div class="cover-wrapper"><a href="javascript:void(0);" onclick="user_create(' + action_id + ', ' + suggestion.s__id + ')" class="black-background-obs cover-chain coinType' + suggestion.s__type + '" ' + background_image + '><div class="cover-btn">' + icon_image + '</div></a></div><div class="cover-content"><div class="inner-content"><a href="javascript:void(0);" onclick="user_create(' + action_id + ', ' + suggestion.s__id + ')" class="main__title">' + suggestion.s__title + '</a></div></div></div>';
    }

}

function search_mini_js(s__cover, s__title) {
    return '<span class="block-cover" title="' + s__title + '">' + view_cover_js(s__cover) + '</span>';
}


function toggle_headline(chainusertype) {

    var chainuseroutput = 0;
    var chainpostoutput = 0;
    var focus__node = parseInt($('#focus__node').val());
    if (focus__node == 12273) {
        chainpostoutput = parseInt($('#focus__id').val());
    } else if (focus__node == 12274) {
        chainuseroutput = parseInt($('#focus__id').val());
    }

    if ($('.headline_title_' + chainusertype + ' .icon_26008').hasClass('hidden')) {

        //Currently open, must now be closed:
        var action_id = 26008; //Close
        $('.headline_title_' + chainusertype + ' .icon_26008').removeClass('hidden');
        $('.headline_title_' + chainusertype + ' .icon_26007').addClass('hidden');
        $('.headline_body_' + chainusertype).addClass('hidden');

        if (chainusertype == 31777) {
            $('.navigate_12273').removeClass('active');
        }

    } else {

        //Close all other opens:
        $('.headlinebody').addClass('hidden');
        $('.headline_titles .icon_26007').addClass('hidden');
        $('.headline_titles .icon_26008').removeClass('hidden');

        //Currently closed, must now be opened
        var action_id = 26007; //Open
        $('.headline_title_' + chainusertype + ' .icon_26007').removeClass('hidden');
        $('.headline_title_' + chainusertype + ' .icon_26008').addClass('hidden');
        $('.headline_body_' + chainusertype).removeClass('hidden');

        if (chainusertype == 31777) {
            $('.navigate_12273').addClass('active');
        }

        //Scroll To:
        $('html, body').animate({
            scrollTop: $('.headline_body_' + chainusertype).offset().top
        }, 13);

    }

}



function user_sort_load(chainusertype) {

    load_cards();
    load_card_clickers();

    console.log('Tring to load User Sort for @' + chainusertype);

    var sort_item_count = parseInt($('.headline_body_' + chainusertype).attr('read-counter'));

    if (!js_userids___13911.includes(chainusertype)) {
        //Does not support sorting:
        console.log(chainusertype + ' is not sortable');
        return false;
    } else if (sort_item_count < 1 || sort_item_count > parseInt(js_users___6404[11064]['m__message'])) {
        return false;
    }

    setTimeout(function () {
        var theobject = document.getElementById("list-in-" + chainusertype);
        if (!theobject) {
            //due to duplicate posts belonging in this post:
            console.log('No object');
            return false;
        }

        //Show sort icon:
        console.log('Completed Loading Sorting for @' + chainusertype)
        $('.sortuser_frame').removeClass('hidden');

        var sort = Sortable.create(theobject, {
            animation: 144, // ms, animation speed moving items when sorting, `0` � without animation
            draggable: "#list-in-" + chainusertype + " .sort_draggable", // Specifies which items inside the element should be sortable
            source: "#list-in-" + chainusertype + " .sortuser_grab", // Restricts sort start click/touch to the specified element
            onUpdate: function (evt/**Event*/) {
                user_sort_save(chainusertype);
            }
        });
    }, 377);

}


window.onpopstate = function (event) {
    load_post_menu(null, false);
};

function load_post_menu(load_post = null, is_first_load = true) {
    if (load_post) {
        toggle_menu(load_post, is_first_load);
    } else if (document.location.hash) {
        var post = document.location.hash.substr(1);
        if (post && post.length > 0) {
            toggle_menu(post, is_first_load);
        }
    }
}


var loading_in_progress = false;
var pills_loading = null;
var loaded_pills = [];

function toggle_menu(chainusertype_hash, is_first_load) {

    console.log('Toggle Pill: ' + chainusertype_hash);

    if (pills_loading && !loaded_pills.includes(chainusertype_hash)) {
        console.log('Cant load new tab while current one loading');
        return false;
    } else if (loading_in_progress) {
        console.log('Tab is loading');
        return false;
    }

    console.log('Toggle Pill Active: ' + chainusertype_hash);

    if ($('.user_nav_' + chainusertype_hash).attr('chainusertype') && $('.user_nav_' + chainusertype_hash).attr('chainusertype').length) {
        chainusertype = parseInt($('.user_nav_' + chainusertype_hash).attr('chainusertype'));
    } else {
        console.log('ERROR: #' + chainusertype_hash + ' is not a valid menu.');
        return false;
    }

    loading_in_progress = true;

    if (!loaded_pills.includes(chainusertype_hash)) {
        pills_loading = chainusertype_hash;
    }

    var chainuseroutput = 0;
    var chainpostoutput = 0;
    var focus__node = parseInt($('#focus__node').val());

    if (focus__node == 12273) {
        chainpostoutput = parseInt($('#focus__id').val());
    } else if (focus__node == 12274) {
        chainuseroutput = parseInt($('#focus__id').val());
    }

    //Toggle view
    $('.xtypetitle').addClass('hidden');
    $('.nav_sub').addClass('hidden');
    $('.nav_sub_' + chainusertype).removeClass('hidden');
    $('.xtypetitle_' + chainusertype).removeClass('hidden');

    //Currently closed, must now be opened:
    var action_id = 26007; //Open

    //Hide all elements
    $('.nav-chain').removeClass('active');
    $('.headlinebody').addClass('hidden');
    $('.thepill' + chainusertype + ' .nav-chain').addClass('active');
    $('.headline_body_' + chainusertype).removeClass('hidden');

    //Set focus tab:
    console.log('focus_group Updated from ' + focus_group + ' to ' + chainusertype);
    focus_group = chainusertype;
    if (!is_first_load && (!window.location.hash || window.location.hash != $('.thepill' + chainusertype + ' .nav-chain').attr('href'))) {
        window.location.hash = $('.thepill' + chainusertype + ' .nav-chain').attr('href');
    }

    //Do we need to load data via ajax?
    if (loaded_pills.includes(chainusertype_hash)) {
        console.log('Not active Tab');
        loading_in_progress = false;
        return false;
    }


    $('.headline_body_' + chainusertype + ' .tab_content').html('<div class="center" style="padding-top: 13px;"><i class="fas fa-yin-yang fa-spin"></i></div>');

    var focus__node = parseInt($('#focus__node').val());
    console.log('Tab loading from @' + focus__node + ' for @' + chainusertype);

    if (focus__node == 12273) {

        var loading_url = "/controller/post_list";
        var loading_data = {
            focus__node: focus__node,
            chainusertype: chainusertype,
            counter: $('.headline_body_' + chainusertype).attr('read-counter'),
            postid: parseInt($('#focus__id').val()),
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        };

    } else if (focus__node == 12274) {

        var loading_url = "/controller/user_list";
        var loading_data = {
            focus__node: focus__node,
            chainusertype: chainusertype,
            counter: $('.headline_body_' + chainusertype).attr('read-counter'),
            userid: parseInt($('#focus__id').val()),
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
        $('.headline_body_' + chainusertype + ' .tab_content').html(data);

        loaded_pills.push(chainusertype_hash);

        load_card_clickers();
        initiate_algolia();
        load_editor();
        x_set_start_text();
        set_autosize($('.x_set_class_text'));
        load_cards();

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

            //TODO Fix Sorting
            if (js_userids___11028.includes(chainusertype) || (focus__node == 12273 && ( chainusertype==13550 || chainusertype==31777 ))) {
                user_sort_load(chainusertype);
            }

            setup_popover();
            pills_loading = null;

        }, 233);

        loading_in_progress = false;

    });

}


function post_copy(postid, do_recursive) {

    //Go ahead and delete:
    $.post("/controller/post_copy", {
        postid: postid,
        do_recursive: do_recursive,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {
            js_redirect(js_users___42903[33286]['m__message'] + data.post_createpost);
        } else {
            alert('ERROR:' + data.message);
        }
    });
}

function user_title(userid) {
    //Load Instant Fields:
    var return_string = '';
    if ($('.usertitle_' + userid + ':first').text().length) {
        return_string = $('.usertitle_' + userid + ':first').text();
    } else if ($('.usertitle_' + userid + ':first').val().length) {
        return_string = $('.usertitle_' + userid + ':first').val();
    }
    return return_string;
}

function user_bio(userid) {
    //Load Instant Fields:
    var return_string = '';
    if ($('.userbio_' + userid + ':first').text().length) {
        return_string = $('.userbio_' + userid + ':first').text();
    } else if ($('.userbio_' + userid + ':first').val().length) {
        return_string = $('.userbio_' + userid + ':first').val();
    }
    return return_string;
}

function user_copy(userid) {

    var copy_user_title = prompt("New Username:", user_title(userid));
    if (!copy_user_title.length) {
        alert('You must enter a title to copy.');
        return false;
    }

    //Go ahead and delete:
    $.post("/controller/user_copy", {
        userid: userid,
        copy_user_title: copy_user_title,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {
            js_redirect(js_users___42903[42902]['m__message'] + data.user_createuser);
        } else {
            alert('ERROR:' + data.message);
        }
    });
}


function js_randomize_text(userid) {
    var messages = js_users___12687[userid]['m__message'].split("\n");
    if (messages.length == 1) {
        //Return message:
        return messages[0];
    } else {
        //Choose Random:
        return messages[Math.floor(Math.random() * messages.length)];
    }
}


function loadtab(chainusertype, tab_data_id) {

    //Hide all tabs:
    $('.tab-group-' + chainusertype).addClass('hidden');
    $('.tab-nav-' + chainusertype).removeClass('active');

    //Show this tab:
    $('.tab-group-' + chainusertype + '.tab-data-' + tab_data_id).removeClass('hidden');
    $('.tab-nav-' + chainusertype + '.tab-head-' + tab_data_id).addClass('active');

}


var init_in_process = 0;

function chain_delete(chainid, chainusertype, posthashtag = null) {

    if (init_in_process == chainid) {
        return false;
    }
    init_in_process = chainid;

    var r = confirm("Are you Sure You Want to Unchain" + (posthashtag ? ' #' + posthashtag : '') + "?");
    if (!(r == true)) {
        return false;
    }

    //Save changes:
    $.post("/controller/chain_delete", {
        chainid: chainid,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
        posthashtag: posthashtag, //Always append to AJAX Calls
    }, function (data) {
        //Update UI to confirm with member:
        if (!data.status) {
            //There was some sort of an error returned!
            alert(data.message);
        } else {
            chain_counter(chainusertype, -1);
            $(".cover_x_" + chainid).fadeOut();
            setTimeout(function () {
                $(".cover_x_" + chainid).remove();
            }, 610);
        }
    });

    return false;
}


function updatusercover(new_cover, changed = true) {
    $('#modal31912 .save_usercover').val(new_cover);
    update_cover_main(new_cover, '.preview_cover');
    watch_cover_change(new_cover);
    if (changed) {
        has_unsaved_changes = true;
    }
}

function image_cover(cover_preview, cover_apply, new_title) {
    return '<a href="javascript:void(0);" onclick="updatusercover(\'' + cover_apply + '\')">' + search_mini_js(cover_preview, new_title) + '</a>';
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

function user_cover(chainusertype, userid, counter, first_segment) {

    if ($('.coinsuser_' + userid + '_' + chainusertype).html().length) {
        //Already loaded:
        return false;
    }

    $('.coinsuser_' + userid + '_' + chainusertype).html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/controller/user_cover", {
        chainusertype: chainusertype,
        userid: userid,
        counter: counter,
        first_segment: first_segment,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('.coinsuser_' + userid + '_' + chainusertype).html(data);
    });

}

function post_cover(chainusertype, postid, counter, first_segment, current_e) {

    if ($('.coins_post_' + postid + '_' + chainusertype).html().length) {
        //Already loaded:
        return false;
    }

    $('.coins_post_' + postid + '_' + chainusertype).html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/controller/post_cover", {
        chainusertype: chainusertype,
        postid: postid,
        counter: counter,
        first_segment: first_segment,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('.coins_post_' + postid + '_' + chainusertype).html(data);
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
    $(".loaduser_cards, .load_post_cards").unbind();

    $(".loaduser_cards").click(function (event) {
        user_cover($(this).attr('load_chainusertype'), $(this).attr('load_userid'), $(this).attr('load_counter'), $(this).attr('load_first_segment'));
    });
    $(".load_post_cards").click(function (event) {
        post_cover($(this).attr('load_chainusertype'), $(this).attr('load_postid'), $(this).attr('load_counter'), $(this).attr('load_first_segment'));
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
    var ignore_clicks = 'a, .btn, textarea, .chainvalue, .cover_wrapper12273, .ignore-click, .focus-cover, .ref_user, .this_selector';
    $(".card_click").click(function (e) {
        if ($(e.target).closest(ignore_clicks).length < 1 && $(this).attr('href').length) {
            js_redirect($(this).attr('href'));
        }
    });

    //For Discovery only:
    if (typeof focus_post_types !== 'undefined' && focus_post_types.length>0) {

        var is_single_choice = ( focus_post_types.includes(6684) );

        if ($(".this_selector").length == 1) {
            //Auto select if only 1 choice is available:
            $('.this_selector i').removeClass('far').removeClass('fa-square').addClass('fas').addClass('fa-square-check');
        }

        $(".this_selector").click(function (e) {
            if ($('.this_selector_' + $(this).attr('selection_postid') + ' i').hasClass('fa-square-check')) {

                //Already selected, so unselect:
                $('.this_selector_' + $(this).attr('selection_postid') + ' i').removeClass('fas').removeClass('fa-square-check').addClass('far').addClass('fa-square');

            } else {

                //Not selected, so Select now:
                if (is_single_choice) {

                    console.log('Single Choice');

                    //Unselect the previously selected:
                    $('.this_selector:not(.this_selector_' + $(this).attr('selection_postid') + ') i.fa-square-check').each(function () {
                        $(this).removeClass('fas').removeClass('fa-square-check').addClass('far').addClass('fa-square');
                    });
                    //Go Next:
                    if (!$('.input_ui_' + $(this).attr('selection_postid'))[0]) {
                        //Since there is no input for this single select, we can instantly go next:
                        setTimeout(function () {
                            post_discovered(0);
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

                if ($('.input_ui_' + $(this).attr('selection_postid'))[0]) {
                    $('.input_ui_' + $(this).attr('selection_postid') + ' .x_write').focus();
                }
                $('.this_selector_' + $(this).attr('selection_postid') + ' i').removeClass('far').removeClass('fa-square').addClass('fas').addClass('fa-square-check');

            }
        });
    }
}


var busy_processing = false;

function sale_increment(increment, postid, max_allowed, min_allowed, unit_total, unit_fee) {

    var current_quentity = parseInt($('.input_ui_' + postid + ' .current_count').text());
    var new_quantity = current_quentity + increment;

    if (new_quantity < min_allowed || new_quantity > max_allowed) {
        return false;
    } else if (busy_processing) {
        return false;
    }

    if (new_quantity > min_allowed) {
        $(".sale_controller_" + postid + " .sale_down>i").removeClass('hidden');
    } else {
        $(".sale_controller_" + postid + " .sale_down>i").addClass('hidden');
    }
    if (new_quantity < max_allowed) {
        $(".sale_controller_" + postid + " .sale_up>i").removeClass('hidden');
    } else {
        $(".sale_controller_" + postid + " .sale_up>i").addClass('hidden');
    }

    busy_processing = true;


    var handling_total = (unit_fee * new_quantity);
    var new_total = (unit_total * new_quantity);

    //Update UI:
    $(".input_ui_" + postid + " .postweight").val(new_quantity);
    $(".input_ui_" + postid + " .current_count").text(new_quantity);
    $(".input_ui_" + postid + " .paypal_handling").val(handling_total);

    invoice_update(); //to show new numbers

    busy_processing = false;

}


function invoice_update() {

    var total_count = 0;
    var total_price = 0;
    var total_currency = '';

    $(".sale_controller").each(function () {

        var item_postid = parseInt($(this).attr('postid'));
        var item_post_title = $('.cache_frame_' + item_postid + ' .first_line').text();
        var current_count = parseFloat($('.input_ui_' + item_postid + ' .current_count').text());
        var current_price = parseFloat($(this).attr('unitprice'));
        var current_currency = $(this).attr('unitcurrency');

        total_count += current_count;
        total_price += (current_count * current_price);
        total_currency = current_currency;
    });


    //Update UI:
    $('.discovered_btn').html('Create Invoice: <span title="" class="small_font inline-block">' + total_price.toLocaleString('en-US', {
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

    return false; //TODO remove later when fixed?

    if (interval) {
        clearInterval(interval);
    }

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover({
        html: true,
        //title: '<a class="close" href="javascript:void(0);" style="display: block;">Close</a>',
        content: function (inner_content) {
            $.post("/controller/chain_popover", {
                user_string: inner_content.innerText,
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

function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        const sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}

var generating_suggestions = false;
function post_suggestions() {
    if(generating_suggestions){
        return false;
    }
    generating_suggestions = true;

    $(".frame_3449936").html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/controller/post_suggestions", {
        postid: $('#modal31911 .save_postid').val(),
        save_posthashtag: $('#modal31911 .save_posthashtag').val().trim(),
        save_postmessage: $('#modal31911 .save_postmessage').val().trim(),
        save_postfootnote: $('#modal31911 .save_postfootnote').val().trim(),
    }, function (data) {

        $(".frame_3449936").html(' ');
        generating_suggestions = false;
        var loop_through = ['4737','42179','6287'];
        for (var x = 0; x < loop_through.length; x++) {
            $('ul.suggest_menu li.item__'+loop_through[x]).addClass('hidden');
            if(data.suggest_data[loop_through[x]].length){
                $('ul.suggest_menu li.item__'+loop_through[x]+'.grey').removeClass('hidden');
                for (var i = 0; i < data.suggest_data[loop_through[x]].length; i++) {
                    $('ul.suggest_menu li.item__'+data.suggest_data[loop_through[x]][i]).removeClass('hidden');
                }
            }
        }

        //Update Discovery Preview:
        $('.preview_postdiscover').html(data.post_index.postdiscover);

    });

}

// Function to check if string is alphanumeric
function isAlphanumeric(str) {
    return /^[a-zA-Z0-9]*$/.test(str);
}

// Function to strip non-alphanumeric characters
function stripNonAlphanumeric(str) {
    return str.replace(/[^a-zA-Z0-9]/g, '');
}

var index_algolia = false;
$(document).ready(function () {

    //Look for power editor updates:
    x_set_start_text();

    setup_popover();

    watch_cover();


    //Activate post suggestions
    $(".save_postmessage, .save_postfootnote").keyup(function(e) {
        var code = e.keyCode ? e.keyCode : e.which;
        if (code == 13) {  // Enter keycode
            post_suggestions();
        }
    });
    $(".save_posthashtag").keyup(function(e) {
        post_suggestions();
    });

    // Handle keypress event
    $('.save_posthashtag').on('keypress', function(e) {
        // Get the key pressed
        let char = String.fromCharCode(e.which);

        // Allow only alphanumeric characters
        if (!isAlphanumeric(char)) {
            e.preventDefault();
        }
    });

    // Handle paste event
    $('.save_posthashtag').on('paste', function(e) {
        // Get pasted data
        let pastedData = (e.originalEvent || e).clipboardData.getData('text/plain');

        // Strip non-alphanumeric characters from pasted data
        let cleanedData = stripNonAlphanumeric(pastedData);

        // Prevent default paste and insert cleaned data
        e.preventDefault();
        let cursorPosition = this.selectionStart;
        let currentValue = $(this).val();
        let newValue = currentValue.substring(0, cursorPosition) + cleanedData + currentValue.substring(cursorPosition);
        $(this).val(newValue);
    });

    // Handle input event to clean any non-alphanumeric characters
    $('.save_posthashtag').on('input', function() {
        let value = $(this).val();
        // Replace any non-alphanumeric characters
        if (!isAlphanumeric(value)) {
            $(this).val(stripNonAlphanumeric(value));
        }
    });

    //Only for post page but still:
    set_autosize($('.usertitle_' + parseInt($('#focus__id').val())));

    $(document).on('keydown', function (e) {
        // You may replace `c` with whatever key you want
        if (e.ctrlKey) {
            if (String.fromCharCode(e.which).toLowerCase() === 'i') {
                //Add Post
                post_edit();
            } else if (String.fromCharCode(e.which).toLowerCase() === 's') {
                //Add User:
                user_editor(0, 0);
            } else if (String.fromCharCode(e.which).toLowerCase() === 'f' && search_enabled()) {
                //Finder:
                toggle_finder();
            }
        }
    });


    load_card_clickers();
    load_cards();


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
    $('#modal31912 .save_usercover').keyup(function () {
        update_cover_main($(this).val(), '.preview_cover');
    });

    set_autosize($('#sugg_note'));
    set_autosize($('.texttype_lg'));

    $('.trigger_modal').click(function (e) {
        var chainusertype = parseInt($(this).attr('chainusertype'));
        $('#modal' + chainusertype).modal('show');
        $('[data-toggle="tooltip"]').tooltip();
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
                match: /(^|\s)\.@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' .@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s),@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' ,@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s);@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' ;@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s):@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' :@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\+@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' +@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)-@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' -@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)~@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '~@');
                },
                replace: function (suggestion) {
                    return ' ~@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)!@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '!@');
                },
                replace: function (suggestion) {
                    return ' !@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\?@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' ?@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\*@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' *@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\x@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, 'x@');
                },
                replace: function (suggestion) {
                    return ' x@' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\\@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '\@');
                },
                replace: function (suggestion) {
                    return ' \@' + suggestion.s__user + ' ';
                }
            },
        ]);


        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)=#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '=#');
                },
                replace: function (suggestion) {
                    return ' =#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)!#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '!#');
                },
                replace: function (suggestion) {
                    return ' !#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\+#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '+#');
                },
                replace: function (suggestion) {
                    return ' +#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\x#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, 'x#');
                },
                replace: function (suggestion) {
                    return ' x#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\?#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '?#');
                },
                replace: function (suggestion) {
                    return ' ?#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)-#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '-#');
                },
                replace: function (suggestion) {
                    return ' -#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s);#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, ';#');
                },
                replace: function (suggestion) {
                    return ' ;#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s):#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, ':#');
                },
                replace: function (suggestion) {
                    return ' :#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)\.#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, '.#');
                },
                replace: function (suggestion) {
                    return ' .#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s),#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return search_js_line(suggestion, ',#');
                },
                replace: function (suggestion) {
                    return ' ,#' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)@(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' @' + suggestion.s__user + ' ';
                }
            },
        ]);

        $('.algolia__e').textcomplete([
            {
                match: /(^|\s)#(\w*(?:\s*\w*))$/,
                search: function (q, callback) {
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31112]['m__message'],
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
                    return ' #' + suggestion.s__user + ' ';
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

                //Hide Any open model:
                $('.modal').modal('hide');

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
                            //Can view limited Users:
                            if (search_filters.length > 0) {
                                search_filters += ' AND ';
                            }
                            search_filters += ' ( _tags:public_index OR _tags:z_' + js_pl_id + ' ) ';
                        }

                    } else {

                        //Guest can search posts only by default as they start typing;
                        if (search_filters.length > 0) {
                            search_filters += ' AND ';
                        }
                        search_filters += ' _tags:public_index ';

                    }

                    //Append filters:
                    index_algolia.search(q, {
                        hitsPerPage: js_users___6404[31113]['m__message'],
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


function display_media(mediaframe_id, uploader_id, postid) {
    $(".ui_postdiscover_" + postid + " .media_display").each(function () {
        $('#' + mediaframe_id).append('<div id="' + $(this).attr('id') + '" class="media_item" media_typeid="" playback_code="" userid="0"  usercover=""></div>');
        cloudinary_presource_view(uploader_id, $(this).attr('id'), $(this).attr('media_typeid'), $(this).attr('playback_code'), $(this).attr('usercover'), $(this).attr('username'), $(this).attr('userid'));
    });
    sort_media(mediaframe_id);
}

function generate_string_id(length) {
    var result           = '';
    var characters       = 'abcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function post_edit(postid = 0, chainid = 0, next_postid = 0) {

    var chainusertype = 0;
    var focus_post_id = (parseInt($('#focus__node').val()) == 12273 ? parseInt($('#focus__id').val()) : 0);
    $("#modal31911 .save_results").html('');

    //Reset Fields:
    has_unsaved_changes = false;
    $('#modal31911 .media_frame').html('');
    $("#modal31911 .dynamic_item").attr('d__id', '').attr('d_chainid', '');
    $("#modal31911 .dynamic_item input").attr('placeholder', '').val('');
    $('#modal31911 .created_postid').val(0);
    $("#modal31911 .unsaved_warning").val('');
    $("#modal31911 .save_frame").addClass('hidden');
    $('#modal31911 .save_postid').val(postid);
    $('#modal31911 .save_chainid').val(chainid);
    $("#modal31911 .save_posthashtag").val('');

    //Are we adding an post for a target action tab?
    console.log('i Modal loaded for ' + focus_group);
    if (focus_post_id && focus_group > 0 && !next_postid && !postid && !chainid) {
        //Next post group:
        next_postid = focus_post_id;
    }

    if (!postid && !next_postid && focus_post_id) {
        next_postid = focus_post_id;
    }


    //Assign updates:
    var insert_message = '';
    $('#modal31911 .next_postid').val(next_postid);
    //$('#modal31911 .hash_group').addClass('hidden'); //Hide post
    //load_post_dynamic(postid, chainid, true);

    if(postid>0){

        insert_message = $('.ui_postmessage_' + postid).text();

    } else {

        //New idea:
        $("#modal31911 .save_posthashtag").val(generate_string_id(10));

        if (next_postid && $('.ui_posthashtag_' + next_postid).length) {
            //Append to textarea:
            insert_message = '#'+$('.ui_posthashtag_'+next_postid).val()+' ';
        } else if (!next_postid) {
            //See where we are at and append anything needed to the post:
            var focus__node = parseInt($('#focus__node').val());
            if (focus__node == 12273) {
                insert_message = '#'+$('#focus_user').val()+' ';
            } else if (focus__node == 12274 && parseInt($('#focus__id').val()) != js_pl_id) {
                insert_message = '@' + $('#focus_user').val() + ' ';
            }
        }
    }


    if (insert_message.length) {
        $("#modal31911 .save_postmessage").val(insert_message);
    }

    if($('.ui_posthashtag_'+postid).text().length){
        $("#modal31911 .save_posthashtag").val($('.ui_posthashtag_'+postid).text());
    }

    $('#modal31911').modal('show');
    $('[data-toggle="tooltip"]').tooltip();

    setTimeout(function () {
        //Adjust sizes:
        set_autosize($('#modal31911 .save_postmessage'));
        set_autosize($('#modal31911 .save_postfootnote'));
        set_autosize($('#modal31911 .save_chainvalue'));
    }, 233);

    setTimeout(function () {
        //Focus on writing a message:
        $('#modal31911 .save_postmessage').focus();
    }, 611);

}

function load_post_dynamic(postid, chainid, initial_loading) {

    $(".dynamic_item").addClass('hidden'); //Hide all current items
    var created_postid = 0;

    $.post("/controller/post_edit", {
        postid: postid,
        chainid: chainid,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {

        if (data.status) {

            if (!postid && data.created_postid > 0) {
                console.log('NEW POST #' + data.created_postid + ' has been created');
                created_postid = data.created_postid;
                $('#modal31911 .created_postid').val(created_postid);
                postid = created_postid;
            }

            if (initial_loading) {

                //Initiate Post  Uploader:
                load_cloudinary(13572, postid, ['#' + postid], '.uploader_13572', '#modal31911');

                //Track unsaved changes to prevent unwated modal closure:
                $("#modal31911 .unsaved_warning").change(function () {
                    has_unsaved_changes = true;
                });

            }

            var current_header = null;

            //Dynamic Input Fields:
            for (let i = 1; i <= js_users___6404[42206]['m__message']; i++) {

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


                var is_locked = js_userids___32145.includes(parseInt(data.return_inputs[index_i]["d__id"]));
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
    return created_postid;
}


var i_saving = false; //Prevent double saving
function post_update() {

    if (i_saving) {
        console.log('Post updating aborted');
        return false;
    }

    i_saving = true;
    $(".post_update").html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');
    $("#modal31911 .save_results").html('');

    var current_postid = parseInt($('#modal31911 .save_postid').val());
    var created_postid = parseInt($('#modal31911 .created_postid').val());
    var save_postid = (current_postid > 0 ? current_postid : created_postid);
    console.log('Post updating begins #' + current_postid);

    //TODO Preview Media
    var modify_data = {
        focus__node: parseInt($('#focus__node').val()),
        focus__id: parseInt($('#focus__id').val()),
        save_postid: save_postid,
        save_chainid: $('#modal31911 .save_chainid').val(),
        next_postid: $('#modal31911 .next_postid').val(),
        save_discoverymode: $('.s__12273_' + save_postid).attr('discovery_mode'),
        focus_group: focus_group,
        save_posthashtag: $('#modal31911 .save_posthashtag').val().trim(),
        save_postmessage: $('#modal31911 .save_postmessage').val().trim(),
        save_postfootnote: $('#modal31911 .save_postfootnote').val().trim(),
        save_chainvalue: $('#modal31911 .save_chainvalue').val().trim(),
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    };

    //Append Dynamic Data:
    for (let i = 1; i <= js_users___6404[42206]['m__message']; i++) {
        if ($('#modal31911 .dynamic_' + i).attr('d__id').length) {
            modify_data['save_dynamic_' + i] = $('#modal31911 .dynamic_' + i).attr('d_chainid').trim() + 'EXPLODETERMABC' + $('#modal31911 .dynamic_' + i).attr('d__id').trim() + 'EXPLODETERMABC' + $('#modal31911 .save_dynamic_' + i).val().trim();
        } else {
            //Should be the end of variables:
            break;
        }
    }

    $.post("/controller/post_update", modify_data, function (data) {

        //Load Images:
        i_saving = false;
        $(".post_update").html('SAVE');

        if (!data.status) {

            //Show Errors:
            $("#modal31911 .save_results").html('<span class="icon-block"><i class="far fa-exclamation-circle"></i></span> Error: ' + data.message);

        } else {

            if (data.redirect_post) {
                //Give option to open the post:
                $(".i_footer_note").removeClass('hidden');
                $(".i_footer_note a").attr('href', data.redirect_post);
                setTimeout(function () {
                    $(".i_footer_note").addClass('hidden');
                }, 6765);
            }

            //Update User & Href chains if needed:
            var old_user = $(".ui_posthashtag_" + modify_data['save_postid'] + ':first').text();
            var new_user = modify_data['save_posthashtag'];
            var on_focus__post = parseInt($('#focus__node').val()) == 12273 && modify_data['save_postid'] == parseInt($('#focus__id').val());

            //Update User & Href chains if needed:
            /*
            if (old_user != new_user) {
                if (on_focus__post) {
                    //Refresh page since focus item user changed:
                    js_redirect(js_users___42903[33286]['m__message'] + new_user);
                } else {
                    //Update Post & Chain:
                    $('.s__12273_' + modify_data['save_postid']).attr('posthashtag', new_user);
                    $(".ui_posthashtag_" + modify_data['save_postid']).text(new_user).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233).fadeOut(233).fadeIn(233); //Flash
                }
            }
            */

            //Reset errors:
            has_unsaved_changes = false;
            $('#modal31911').modal('hide');

            //Update Post Message:
            $('.ui_postmessage_' + modify_data['save_postid']).text(modify_data['save_postmessage']);

            //Insert post into the page if new:
            console.log('START INSERTING');
            if (!current_postid && created_postid > 0 && focus_group > 0) {

                $("#list-in-" + focus_group).append(data.return_postdiscover_full);

                chain_counter(focus_group, 1);

            } else {

                //Update Cache otherwise:
                $('.ui_postdiscover_' + modify_data['save_postid']).html(data.return_postdiscover_chains);

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
        source: ".media_item", // Restricts sort start click/touch to the specified element
        onUpdate: function (evt/**Event*/) {
            //Nothing we need to do since the order will be grabbed upon submission
            //Just mark as unsaved again to make sure it saves:
            has_unsaved_changes = true;
        }
    });
}

var media_cache = []; //Stores the json data for successfully uploaded media files
function load_cloudinary(uploader_id, s__id, uploader_tags = [], loading_button = null, loading_modal = null, loading_inline_container = null) {

    console.log('Initiating Uploader @' + uploader_id + ' with tags ' + uploader_tags.join(' & '));

    if (js_users___42363[uploader_id] == undefined) {
        console.log('Unknown Uploader @' + uploader_id + ' Missing in @42363');
        return false;
    }

    media_cache[uploader_id] = [];
    //Fetch global defaults:
    var default_max_file_count = parseFloat(js_users___6404[42382]['m__message']);

    var global_tags = ['@' + uploader_id, '@' + website_id, '@' + js_pl_id];
    var allow_videos = js_users___42390[uploader_id] !== undefined;
    var allow_imgaes = js_users___42389[uploader_id] !== undefined;
    var allow_audio = js_users___42644[uploader_id] !== undefined;

    if (!allow_videos && !allow_imgaes && !allow_audio) {
        //Assume all are allowed:
        allow_audio = true;
        allow_videos = true;
        allow_imgaes = true;
    }

    //Initiate CLoudiary for cover:
    var max_file_count = (js_users___42382[uploader_id] !== undefined && parseFloat(js_users___42382[uploader_id]['m__message']) > 0 && parseFloat(js_users___42382[uploader_id]['m__message']) < default_max_file_count ? parseFloat(js_users___42382[uploader_id]['m__message']) : default_max_file_count);

    var enable_crop = (js_users___42386[uploader_id] !== undefined);
    var force_crop = (js_users___42387[uploader_id] !== undefined);


    var clientAllowedFormats = [];
    if (allow_videos) {
        clientAllowedFormats = clientAllowedFormats.concat(js_users___42641[4258]['m__message'].split(' '));
    }
    if (allow_imgaes) {
        clientAllowedFormats = clientAllowedFormats.concat(js_users___42641[4260]['m__message'].split(' '));
    }
    if (allow_audio) {
        clientAllowedFormats = clientAllowedFormats.concat(js_users___42641[4259]['m__message'].split(' '));
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
        croppingAspectRatio: (js_users___42388[uploader_id] !== undefined && parseFloat(js_users___42388[uploader_id]['m__message']) > 0 ? parseFloat(js_users___42388[uploader_id]['m__message']) : null),

        minImageWidth: (js_users___42407[uploader_id] !== undefined && parseInt(js_users___42407[uploader_id]['m__message']) > 0 ? parseInt(js_users___42407[uploader_id]['m__message']) : null),
        maxImageWidth: (js_users___42408[uploader_id] !== undefined && parseInt(js_users___42408[uploader_id]['m__message']) > 0 ? parseInt(js_users___42408[uploader_id]['m__message']) : null),
        minImageHeight: (js_users___42409[uploader_id] !== undefined && parseInt(js_users___42409[uploader_id]['m__message']) > 0 ? parseInt(js_users___42409[uploader_id]['m__message']) : null),
        maxImageHeight: (js_users___42410[uploader_id] !== undefined && parseInt(js_users___42410[uploader_id]['m__message']) > 0 ? parseInt(js_users___42410[uploader_id]['m__message']) : null),

        validateMaxWidthHeight: (js_users___42411[uploader_id] !== undefined),
        croppingValidateDimensions: (js_users___42412[uploader_id] !== undefined),

        inlineContainer: loading_inline_container,

        //Fixed variables:
        cloudName: 'menchcloud',
        uploadPreset: 'mench_uploader',
        showPoweredBy: false,
        autoMinimize: true,
        theme: 'minimal',
        tags: global_tags.concat(uploader_tags),
        users: ['local', 'url', 'image_search', 'camera', 'unsplash'], //, 'google_drive', 'dropbox'
        defaultUser: 'local',
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
                userBg: "#FFFFFF"
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
            }
            //Log error
            console.log('ERROR');
            console.log(result);

        } else if (result.event === "upload-added") {

            //Add Pending Loader
            console.log(result.event);
            console.log(result);

            //Append loaders:
            if (uploader_id == 42359) {

                //User Cover Uploader:
                updatusercover('fas fa-yin-yang fa-spin');

            } else if (uploader_id == 13572) {

                //Posttor Uploader
                has_unsaved_changes = true;
                $('#media_editor_frame').append('<div id="' + result.info.id + '" class="media_item" media_typeid="" playback_code="" userid="0"  usercover=""><span><i class="fas fa-yin-yang fa-spin"></i></span></div>');

            } else if (uploader_id == 43004) {

                //Discovery Uploader
                $('#media_outer_' + s__id).append('<div id="' + result.info.id + '" class="media_item" media_typeid="" playback_code="" userid="0"  usercover=""><span><i class="fas fa-yin-yang fa-spin"></i></span></div>');

            }

        } else if (result.event === "success") {

            console.log(result.event);
            console.log(result);

            //Create a new user for this  media:


            //Add uploaded media:
            if (uploader_id == 42359) {

                //User Cover Uploader:
                updatusercover('https://res.cloudinary.com/menchcloud/image/upload/c_crop,g_custom/' + result.info.path);

            } else if (uploader_id == 13572 || uploader_id == 43004) {

                //Post Uploader
                var playback_code = '';
                var media_typeid = 0;
                var media_typename = '';
                if (result.info.format && result.info.format.length > 0) {
                    if (js_users___42641[4258]['m__message'].split(' ').includes(result.info.format) && result.info.resource_type == 'video') {
                        //Video
                        media_typeid = 4258;
                        media_typename = 'Video';
                        playback_code = result.info.public_id;
                    } else if (js_users___42641[4259]['m__message'].split(' ').includes(result.info.format) && result.info.is_audio) {
                        //Audio
                        media_typeid = 4259;
                        media_typename = 'Audio';
                        playback_code = result.info.secure_url;
                    } else if (js_users___42641[4260]['m__message'].split(' ').includes(result.info.format) && result.info.resource_type == 'image') {
                        //Image
                        media_typeid = 4260;
                        media_typename = 'Image';
                        playback_code = (result.info.thumbnail_url ? result.info.thumbnail_url.replaceAll('c_limit,h_60,w_90', 'w_1597,h_1597,c_fit') : result.info.secure_url);
                    }
                }

                //Append this to the main User:
                if (media_typeid) {

                    cloudinary_presource_view(uploader_id, result.info.id, media_typeid, playback_code, (result.info.thumbnail_url ? result.info.thumbnail_url.replaceAll('c_limit,h_60,w_90', 'c_fill,h_377,w_377') : null), ( result.info.original_filename ? media_typename + ' ' + result.info.original_filename.replaceAll('_', ' ').replaceAll('-', ' ').replaceAll('  ', ' ').replaceAll('  ', ' ').replaceAll('  ', ' ') : media_typename + ' File'));

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
        console.log(loading_button+' YES LOADING / '+( widget ? 'YESWIDGET' : 'NOWIDGET'));
        //Attach to widget:
        $(loading_button).click(function (e) {
            widget.open();
        });
    } else {
        console.log(loading_button+' NOT LOADING / '+( widget ? 'YESWIDGET' : 'NOWIDGET'));
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


function play_video(public_id) {
    var cld = cloudinary.videoPlayer('video_user_' + public_id, {cloudName: 'menchcloud'});
    cld.source(public_id);
}

function cloudinary_presource_view(uploader_id, info_id, media_typeid, playback_code, usercover, username, userid = 0) {

    //Update meta variables:
    $('#' + info_id).attr('media_typeid', media_typeid).attr('playback_code', playback_code).attr('userid', userid).attr('usercover', usercover);

    if (media_typeid == 4258) {

        //Video
        $('#' + info_id).html('<input type="text" value="' + username + '" placeholder="User Title" class="hidden_superpower__10939" /><span title="Video"><i class="far fa-play-circle" aria-hidden="true"></i></span><img src="' + usercover + '" />');
        //<video id="video_user_'+playback_code+'" controls class="cld-video-user vjs-fade-out cld-fluid cld-video-user-skin-light" poster="'+usercover+'"></video>
        //play_video(playback_code);

    } else if (media_typeid == 4260) {

        //Image
        $('#' + info_id).html('<input type="text" value="' + username + '" placeholder="User Title" class="hidden_superpower__10939" /><img src="' + usercover + '" />');

    } else if (media_typeid == 4259) {

        //Audio
        $('#' + info_id).html('<input type="text" value="' + username + '" placeholder="User Title" class="hidden_superpower__10939" /><span title="Audio"><i class="far fa-volume-up" aria-hidden="true"></i></span><audio controls src="' + playback_code + '"></audio>');

    } else {

        //Unsupported file, should not happen since we limited file extensions to those we know:
        alert('Upload Error: Uploaded File ' + username + ' is not a valid Video, Image or Audio file.');

    }


}


function user_editor(userid = 0, chainid = 0, bar_title = null, chainvalue = null) {

    $('#modal31912').modal('show');
    $('[data-toggle="tooltip"]').tooltip();

    //Reset Fields:
    has_unsaved_changes = false;

    $("#modal31912 .unsaved_warning").val('');

    $('#modal31912 .save_results').html('');
    $("#modal31912 .save_frame").addClass('hidden');
    $("#modal31912 .dynamic_item").attr('d__id', '').attr('d_chainid', '');
    $("#modal31912 .dynamic_item").attr('placeholder', '').val('');

    //User resets:
    $('#search_cover').val('');
    $(".cover_history_button").addClass('hidden');
    $('#modal31912 .black-background-obs').removeClass('isSelected');

    //Load Instant Fields:
    var current_title = user_title(userid);
    var current_bio = user_bio(userid);
    var current_cover = $('.ui_usercover_' + userid + ':first').attr('raw_cover');

    $('#modal31912 .save_userid').val(userid);
    $('#modal31912 .save_chainid').val(chainid);
    $('#modal31912 .save_userhandle').val($('.ui_userhandle_' + userid + ':first').text().replace('@',''));
    $('#modal31912 .save_username').val(current_title);
    $('#modal31912 .save_userbio').val(current_bio);
    

    $('#modal31912 .random_animal').html('<i class="' + random_animal(true) + '"></i>');
    updatusercover(current_cover, false);

    if (chainid) {
        $('#modal31912 .save_chainvalue').val($('.ui_chainvalue_' + chainid).text());
        $('#modal31912 .save_frame').removeClass('hidden');
        setTimeout(function () {
            set_autosize($('#modal31912 .save_chainvalue'));
        }, 377);
    }
    setTimeout(function () {
        set_autosize($('#modal31912 .save_username'));
        set_autosize($('#modal31912 .save_userbio'));
    }, 377);


    $.post("/controller/user_editor", {
        userid: userid,
        chainid: chainid,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {

        if (data.status) {

            //Initiate User Cover Uploader:
            load_cloudinary(42359, userid, ['@' + userid], '.uploader_42359', '#modal31912');

            //Dynamic Input Fields:
            var index_post_content = 0;
            var current_header = null;

            for (let i = 1; i <= js_users___6404[42206]['m__message']; i++) {

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
                    index_post_content++;
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

                var is_locked = js_userids___32145.includes(parseInt(data.return_inputs[index_i]["d__id"]));
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

                    if (chainid && ((parseInt($('#focus__node').val()) == 12274 && data.return_inputs[index_i]["d__id"] == parseInt($('#focus__id').val())) || data.return_inputs[index_i]["d__id"] == userid)) {
                        //Hide message textarea since this is already loaded in the dynamic inputs:
                        //$("#modal31912 .save_chainvalue").val('IGNORE_INPUT');
                        //$("#modal31912 .save_frame").addClass('hidden');
                    }
                }
            }

            //Add a second save button at the bottom if we have too much data:
            if (index_post_content > 5) {
                $("#modal31912 .modal-footer").html('<button type="button" class="btn btn-default user_save_edit post_button" onclick="user_save_edit()">SAVE</button>');
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

function user_save_edit() {

    if (e_saving) {
        return false;
    }

    e_saving = true;
    $(".user_save_edit").html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');
    $("#modal31912 .save_results").html('');

    var modify_data = {
        save_userid: $('#modal31912 .save_userid').val(),
        save_username: $('#modal31912 .save_username').val().trim(),
        save_userbio: $('#modal31912 .save_userbio').val().trim(),
        save_usercover: $('#modal31912 .save_usercover').val().trim(),
        save_userhandle: $('#modal31912 .save_userhandle').val().trim(),
        save_chainid: $('#modal31912 .save_chainid').val(),
        save_chainvalue: $('#modal31912 .save_chainvalue').val().trim(),
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    };

    //Append Dynamic Data:
    for (let i = 1; i <= js_users___6404[42206]['m__message']; i++) {
        if ($('#modal31912 .dynamic_' + i).attr('d__id').length) {
            modify_data['save_dynamic_' + i] = $('#modal31912 .dynamic_' + i).attr('d_chainid').trim() + 'EXPLODETERMABC' + $('#modal31912 .dynamic_' + i).attr('d__id').trim() + 'EXPLODETERMABC' + $('#modal31912 .save_dynamic_' + i).val().trim();
        } else {
            //Should be the end of variables:
            break;
        }
    }

    $.post("/controller/user_save_edit", modify_data, function (data) {

        e_saving = false;
        $(".user_save_edit").html('SAVE');

        if (!data.status) {

            //Show Errors:
            $("#modal31912 .save_results").html('<span class="icon-block"><i class="far fa-exclamation-circle"></i></span> Error: ' + data.message);

        } else {

            //Update User & Href chains if needed:
            var old_user = $(".ui_userhandle_" + modify_data['save_userid'] + ':first').text();
            var new_user = modify_data['save_userhandle'];
            if (old_user != new_user) {
                if (parseInt($('#focus__node').val()) == 12274 && modify_data['save_userid'] == parseInt($('#focus__id').val())) {
                    //Refresh page since focus item user changed:
                    return js_redirect(js_users___42903[42902]['m__message'] + new_user);
                } else {
                    //Make adjustments to current page:
                    $('.s__12274_' + modify_data['save_userid']).attr('userhandle', new_user);
                    $('.ui_userhandle_' + modify_data['save_userid']).text(new_user);
                    $(".user_hrefuser_" + modify_data['save_userid']).attr('href', $(".user_hrefuser_" + modify_data['save_userid'] + ':first').attr('href').replaceAll(old_user, new_user));
                }
            }

            //Update Title:
            update_text_name(6197, modify_data['save_userid'], modify_data['save_username']);
            update_text_name(3423966, modify_data['save_userid'], modify_data['save_userbio']);

            //Update Raw Cover:
            $('.ui_usercover_' + modify_data['save_userid'] + ':first').attr('raw_cover', modify_data['save_usercover']);

            //Update Main Cover:
            update_cover_main(modify_data['save_usercover'], '.s__12274_' + modify_data['save_userid']);

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
            if (parseInt($('#focus__node').val()) == 12274 && parseInt($('#focus__id').val()) == modify_data['save_userid']) {
                //Refresh page since User edited their own profile:
                js_redirect(js_users___42903[42902]['m__message'] + $('#focus_user').val());
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
    var has_more_to_load = (current_total_count > parseInt(js_users___6404[11064]['m__message']) * current_page[focus_group]);

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
        chainusertype: focus_group,
        current_page: current_page[focus_group],
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        $('.load-more').remove();
        if (data.length) {
            $('#list-in-' + focus_group).append(data);
            x_set_start_text();
            load_card_clickers();
            load_cards();
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


//Adds OR chains Users to Users
var user_is_adding = false;

function user_create(chainusertype, user_current_id) {

    if (user_is_adding) {
        return false;
    }

    //if user_current_id>0 it means we're adding an existing User, in which case user_new_string should be null
    //If user_current_id=0 it means we are creating a new User and then adding it, in which case user_new_string is required
    user_is_adding = true;

    var input = $('.new-list-' + chainusertype + ' .add-input');

    var original_photo = $('.mini-cover.card-12274.card-id-' + user_current_id + ' .cover-btn').html();
    $('.mini-cover.card-12274.card-id-' + user_current_id + ' .cover-btn').html('<i class="fas fa-yin-yang fa-spin"></i>');
    var user_new_string = null;
    if (user_current_id == 0) {
        user_new_string = input.val();
        if (user_new_string.length < 1) {
            alert('Missing User name or URL, try again');
            input.focus();
            return false;
        }
    }

    //Add via Ajax:
    $.post("/controller/user_create", {

        focus__node: parseInt($('#focus__node').val()),
        chainusertype: chainusertype,
        focus__id: parseInt($('#focus__id').val()),
        user_current_id: user_current_id,
        user_new_string: user_new_string,
        js_request_uri: js_request_uri, //Always append to AJAX Calls

    }, function (data) {

        user_is_adding = false;

        if (data.status) {

            //Raw input to make it ready for next URL:
            //input.focus();

            //Add new object to list:
            chain_counter(chainusertype, 1);

            //See if we previously have a list in place?
            if ($("#list-in-" + chainusertype + " .card-12274").length > 0) {
                //Downwards add to start"
                $("#list-in-" + chainusertype + " .card-12274:first").before(data.user_new_echo);
            } else {
                //Raw list, add before input filed:
                $("#list-in-" + chainusertype).prepend(data.user_new_echo);
            }

            //Allow inline editing if enabled:
            x_set_start_text();

            setTimeout(function () {
                setup_popover();
                user_sort_load(chainusertype);
            }, 987);

            //Hide Coin:
            $('.mini-cover.card-12274.card-id-' + user_current_id).fadeOut();

        } else {
            //We had an error:
            alert(data.message);
        }

    });
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
            user_text_update(this);
            e.preventDefault();
        }
    }).change(function () {
        user_text_update(this);
    });
}

function update_text_name(cache_userid, userid, username) {
    var target_element = ".text__" + cache_userid + "_" + userid;
    $(target_element).text(username).attr('old-value', username); //.val(username)
    set_autosize($(target_element));
}


var setting_text = false;

function user_text_update(this_grabr) {

    if (setting_text) {
        return false;
    }

    setting_text = true;
    var modify_data = {
        userid: parseInt($(this_grabr).attr('userid')),
        cache_userid: parseInt($(this_grabr).attr('cache_userid')),
        post_createtext: $(this_grabr).val().trim(),
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    };

    //See if anything changes:
    if ($(this_grabr).attr('old-value') == modify_data['post_createtext']) {
        //Nothing changed:
        return false;
    }

    //Grey background to indicate saving
    var target_element = '.text__' + modify_data['cache_userid'] + '_' + modify_data['s__id'];
    $.post("/controller/user_text_update", modify_data, function (data) {

        if (!data.status) {

            //Reset to original value:
            $(target_element).val(data.original_val);

            //Show error:
            alert(data.message);

        } else {

            //If Updating Text, Updating Corresponding Fields:
            update_text_name(modify_data['cache_userid'], modify_data['s__id'], modify_data['post_createtext']);

        }

        setting_text = false;

    });
}


function chain_counter(chainusertype, adjustment_count) {
    $('.xtypecounter' + chainusertype).text((parseInt($('.headline_body_' + chainusertype).attr('read-counter')) + adjustment_count));
}


function search_enabled() {
    return universal_search_enabled && parseInt(js_users___6404[12678]['m__message']);
}



function set_autosize(theobject) {
    autosize(theobject);
    setTimeout(function () {
        autosize.update(theobject);
    }, 13);
}



var current_focus = 0;

function remove_ui_class(item, index) {
    var the_class = 'custom_ui_' + current_focus + '_' + item;
    $('body').removeClass(the_class);
}

function user_select_apply(focus__id, selected_userid, enable_mulitiselect, down_userid, right_postid) {

    //Any warning needed?
    if (js_userids___31780.includes(selected_userid) && !confirm(js_users___31780[selected_userid]['m__message'])) {
        return false;
    }

    var field_required = js_userids___28239.includes(focus__id);
    var was_previously_selected = ($('.radio-' + focus__id + ' .item-' + selected_userid).hasClass('active') ? 1 : 0);

    //Save the rest of the content:
    if (!enable_mulitiselect && field_required && was_previously_selected) {
        //Nothing to do here:
        return false;
    }

    //Updating Customizable Theme?
    if (js_userids___13890.includes(focus__id)) {
        current_focus = focus__id;
        $('body').removeClass('custom_ui_' + focus__id + '_');
        window['js_userids___' + focus__id].forEach(remove_ui_class); //Removes all Classes
        $('body').addClass('custom_ui_' + focus__id + '_' + selected_userid);
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-' + focus__id + ' .item-' + selected_userid + ' .change-results';
    var initial_icon = $(notify_el).html();
    $(notify_el).html('<i class="fas fa-yin-yang fa-spin"></i>');


    if (!enable_mulitiselect) {
        //Clear all selections:
        $('.radio-' + focus__id + ' .list-group-item').removeClass('active');
        $('.radio-' + focus__id + ' .checked_icon').remove();
    }

    //Enable currently selected:
    if ((enable_mulitiselect || !field_required) && was_previously_selected) {
        $('.radio-' + focus__id + ' .item-' + selected_userid).removeClass('active');
        $('.radio-' + focus__id + ' .item-' + selected_userid + ' .checked_icon').remove();
    } else {
        $('.radio-' + focus__id + ' .item-' + selected_userid).addClass('active');
        $('.radio-' + focus__id + ' .item-' + selected_userid + ' .inner_headline').after('<span class="icon-block-sm checked_icon"><i class="far fa-check"></i></span>');
    }

    $.post("/controller/user_select_apply", {
        focus__id: focus__id,
        down_userid: down_userid,
        right_postid: right_postid,
        selected_userid: selected_userid,
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


function update_form_select(element_id, user_createid, initial_loading, show_title) {
    console.log('update_form_select: ' + element_id + '/' + user_createid);

    //Toggles UI for FORM Selector
    $('.dropd_form_' + element_id + ' .dropdown-item').removeClass('active');
    $('.dropd_form_' + element_id + ' .optiond_' + user_createid).addClass('active');
    $('.dropd_form_' + element_id).attr('selected_value', user_createid);
    if (show_title) {
        $('.dropd_form_' + element_id + ' .current_content').html($('.dropd_form_' + element_id + ' .content_' + user_createid).html());
    } else {
        //Just replace icon:
        $('.dropd_form_' + element_id + ' .current_content span').html($('.dropd_form_' + element_id + ' .content_' + user_createid + ' span').html());
    }
}

function ui_instant_select(element_id, user_createid, o__id, chainid, show_full_name) {

    //Update x:
    console.log('UI instant .dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .btn' + user_createid);
    var data_object = eval('js_users___' + element_id);
    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .btn').html('<span class="icon-block-sm">' + data_object[user_createid]['m__cover'] + '</span>' + (show_full_name ? data_object[user_createid]['m__name'] : ''));

    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .drop_item_instant_' + element_id + '_' + o__id + '_' + chainid).removeClass('active');
    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .optiond_' + user_createid + '_' + o__id + '_' + chainid).addClass('active');

    var selected_userid = $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid).attr('selected_value');
    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid).attr('selected_value', user_createid);


    var main_object_type = 0;
    var main_object_update = false;

    if (main_object_type > 0 && main_object_update) {
        $('.s__' + main_object_type + '_' + o__id).attr(main_object_update, user_createid);
    }

}

function post_delete(postid) {

    var migrateuser = prompt("Are you sure you want to permanently delete this post?\nYou can reference #anotherPost to migrate to or leave blank to delete permanently", "#");
    if (migrateuser === null) {
        return false;
    }

    $.post("/controller/post_delete", {
        focus__id: parseInt($('#focus__id').val()),
        postid: postid,
        migrateuser: migrateuser,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {

            if (data.delete_redirect && data.delete_redirect.length > 0) {

                //Go to main post page:
                js_redirect(data.delete_redirect);

            } else if (data.delete_element && data.delete_element.length > 0) {

                //Go to main post page:
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


function user_delete(userid) {

    var migrateuser = prompt("Are you sure you want to permanently delete this User?\nYou can reference @anotherUser to migrate to or leave blank to delete permanently", "@");
    if (migrateuser === null) {
        return false;
    }

    $.post("/controller/user_delete", {
        focus__id: parseInt($('#focus__id').val()),
        userid: userid,
        migrateuser: migrateuser,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {

            if (data.delete_redirect && data.delete_redirect.length > 0) {

                //Go to main post page:
                js_redirect(data.delete_redirect);

            } else if (data.delete_element && data.delete_element.length > 0) {

                //Go to main post page:
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


function selector(element_id, user_createid, o__id = 0, chainid = 0, show_full_name = false) {

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

    console.log('Attempt to update dropdown @' + element_id + ' to @' + user_createid);

    user_createid = parseInt(user_createid);

    //Deleting Anything?
    var main_object_type = 0;
    var main_object_update = false;
    var migrateuser = null;

    //Show Loading
    var data_object = eval('js_users___' + element_id);
    if (!data_object[user_createid]) {
        alert('Invalid element ID: ' + element_id + '/' + user_createid + '/' + o__id + '/' + chainid + '/' + show_full_name);
        return false;
    }
    $('.dropd_instant_' + element_id + '_' + o__id + '_' + chainid + ' .btn').html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');

    $.post("/controller/user_select", {
        focus__id: parseInt($('#focus__id').val()),
        o__id: o__id,
        element_id: element_id,
        user_createid: user_createid,
        migrateuser: migrateuser,
        chainid: chainid,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {

            //Update on page:
            ui_instant_select(element_id, user_createid, o__id, chainid, show_full_name);

            if (data.delete_redirect && data.delete_redirect.length > 0) {

                //Go to main post page:
                js_redirect(data.delete_redirect);

            } else if (data.delete_element && data.delete_element.length > 0) {

                //Go to main post page:
                setTimeout(function () {
                    //Restore background:
                    $(data.delete_element).fadeOut();
                    setTimeout(function () {
                        //Restore background:
                        $(data.delete_element).remove();
                    }, 55);
                }, 377);

            }

            if (data.auto_open_post_modal) {
                //We need to show post modal:
                post_edit(o__id, $('.s__12273_' + o__id).attr('chainid'));
            }

        } else {

            //Show error:
            alert(data.message);

        }
    });
}


function user_sort_save(chainusertype) {

    var new_chainkey = [];
    var sort_rank = 0;

    $("#list-in-" + chainusertype + " .card-12274").each(function () {
        //Fetch variables for this post:
        var userid = parseInt($(this).attr('userid'));
        var chainid = parseInt($(this).attr('chainid'));

        sort_rank++;

        //Store in DB:
        new_chainkey[sort_rank] = chainid;
    });

    //It might be zero for lists that have jsut been emptied
    if (sort_rank > 0) {
        //Update backend:
        $.post("/controller/user_sort_save", {
            userid: parseInt($('#focus__id').val()),
            chainusertype: chainusertype,
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
        var focus_user = $('#focus_user').val();

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
                    //Posts
                    js_redirect(js_users___42903[33286]['m__message'] + focus_user);
                } else if (focus__node == 12274) {
                    //Users
                    js_redirect(js_users___42903[42902]['m__message'] + focus_user);
                }

            }
        });
    }
}


function chain_clicked(postid) {
    $(".chain_click_" + postid).addClass('was_clicked');
}


var next_processing = false;

function post_discovered(do_skip) {

    if (next_processing) {
        return false;
    }
    next_processing = true;

    var selection_postid = [];

    if ($(".chain_click")[0] && !$(".was_clicked")[0]) {
        next_processing = false;
        alert('Click on the URL to open it in a new window before you continue.');
        return false;
    }

    const filteredArray = array1.filter(value => array2.includes(value));

    for (var i = 0; i < js_userids___7712.length; i++) {
        if(focus_post_types.includes(js_userids___7712[i])){
            //Choose
            $(".this_selector").each(function () {
                var selection_postid_this = parseInt($(this).attr('selection_postid'));
                if ($('.this_selector_' + selection_postid_this + ' i').hasClass('fa-square-check') || $(".this_selector").length == 1) {
                    selection_postid.push(selection_postid_this);
                }
            });
            break;
        }
    }

    //Compile all next posts, if any:
    var next_post_data = []; //Aggregate the data for all children
    $("#list-in-12840 .edge-cover").each(function () {
        next_post_data.push({
            postid: parseInt($(this).attr('postid')),
            post_createtext: ($('.s__12273_' + $(this).attr('postid') + ' .x_write').val() ? $('.s__12273_' + $(this).attr('postid') + ' .x_write').val() : null),
            postweight: ($('.input_ui_' + $(this).attr('postid') + ' .postweight').val() ? $('.input_ui_' + $(this).attr('postid') + ' .postweight').val() : 0),
        });
    });

    //Payment Error?
    if (focus_post_types.includes(26560) && !$(".tickets_issued")[0]) {
        //Ticket not yet issued!
        alert('Pay Now via Paypal before going next.');
        next_processing = false;
        return false;
    } else if ( focus_post_types.includes(43758) ) {

        //Invoice Process, make sure something is in the cart:
        var invoice_items = {};
        var total_count = 0;
        var total_price = 0;
        var currency_code = '';

        $(".sale_controller").each(function (i, e) {

            currency_code = $(this).attr('unitcurrency');
            var item_postid = parseInt($(this).attr('postid'));
            var item_title = $('.cache_frame_' + item_postid + ' .first_line').text();
            var quantity = parseFloat($('.input_ui_' + item_postid + ' .current_count').text());

            if (quantity > 0) {
                var this_item = {
                    postid: item_postid,
                    name: item_title,
                    description: $('.cache_frame_' + item_postid).text().replace(item_title, ''),
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
            var original_html = $('.discovered_btn').html();
            $('.discovered_btn').html('<span class="icon-block" style="margin:5px 0 -5px;"><i class="fas fa-yin-yang fa-spin"></i></span>');

            //Submit to go next:
            $.post("/invoice", {
                target_posthashtag: $('#target_posthashtag').val(),
                target_postid: parseInt($('#target_postid').val()),
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
                    $('.discovered_btn').html(original_html);
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
    var original_html = $('.discovered_btn').html();
    $('.discovered_btn').html('<span class="icon-block" style="margin:5px 0 -5px;"><i class="fas fa-yin-yang fa-spin"></i></span>');

    //Submit to go next:
    $.post("/controller/post_discovered", {
        target_posthashtag: $('#target_posthashtag').val(),
        target_postid: parseInt($('#target_postid').val()),
        user_submitted_data: {
            postid: parseInt($('#focus__id').val()),
            post_createtext: ($('.focus-cover .x_write').val() ? $('.focus-cover .x_write').val() : null),
            postweight: ($('.input_ui_' + parseInt($('#focus__id').val()) + ' .postweight').val() ? $('.input_ui_' + parseInt($('#focus__id').val()) + ' .postweight').val() : 0),
        },
        do_skip: do_skip,
        selection_postid: selection_postid,
        next_post_data: next_post_data,
        js_request_uri: js_request_uri, //Always append to AJAX Calls
    }, function (data) {
        if (data.status) {
            //Go to redirect message:
            js_redirect(data.next__url);
        } else {
            next_processing = false;
            //Show error:
            $('.discovered_btn').html(original_html);
            alert(data.message);
        }
    });

}
