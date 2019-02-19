//Loadup algolia when any related field is focused on:
var algolia_loaded = false;

//Define tip style:
var tips_button = '<span class="badge tip-badge"><i class="fas fa-info-circle"></i></span>';

//For the drag and drop file uploader:
var isAdvancedUpload = function () {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

function fn___in_matrix_tips(in_id) {

    //See if this tip needs to be loaded:
    if (!$("div#content_" + in_id).html().length) {

        //Show loader:
        $("div#content_" + in_id).html('<i class="fas fa-spinner fa-spin"></i>');

        //Let's check to see if this user has already seen this:
        $.post("/intents/fn___in_matrix_tips", {in_id: in_id}, function (data) {
            //Let's see what we got:
            if (data.status) {
                //Load the content:
                $("div#content_" + in_id).html('<div class="row"><div class="col-xs-6"><a href="javascript:fn___close_tip(' + in_id + ')">' + tips_button + '</a></div><div class="col-xs-6" style="text-align:right;"><a href="javascript:fn___close_tip(' + in_id + ')"><i class="fas fa-times"></i></a></div></div>'); //Show the same button at top for UX
                $("div#content_" + in_id).append(data.tip_messages);

                //Reload tooldip:
                $('[data-toggle="tooltip"]').tooltip();
            } else {
                //Show error:
                alert('ERROR: ' + data.message);
            }
        });
    }

    //Expand the tip:
    $('#hb_' + in_id).hide();
    $("div#content_" + in_id).fadeIn();
}

function fn___add_to_list(sort_list_id, sort_handler, html_content) {
    //See if we already have a list in place?
    if ($("#" + sort_list_id + " " + sort_handler).length > 0) {
        //yes we do! add this:
        $("#" + sort_list_id + " " + sort_handler + ":last").after(html_content);
    } else {
        //Empty list, add before input filed:
        $("#" + sort_list_id).prepend(html_content);
    }
}

function fn___close_tip(in_id) {
    $("div#content_" + in_id).hide();
    $('#hb_' + in_id).fadeIn('slow');
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

function fn___ms_toggle(tr_id, new_state) {

    if (new_state < 0) {
        //Detect new state:
        new_state = ($('.cr-class-' + tr_id).hasClass('hidden') ? 1 : 0);
    }

    if (new_state) {
        //open:
        $('.cr-class-' + tr_id).removeClass('hidden');
        $('#handle-' + tr_id).removeClass('fa-plus-circle').addClass('fa-minus-circle');
    } else {
        //Close:
        $('.cr-class-' + tr_id).addClass('hidden');
        $('#handle-' + tr_id).removeClass('fa-minus-circle').addClass('fa-plus-circle');
    }
}

function fn___load_help(in_id) {
    //Loads the help button:
    $('#hb_' + in_id).html('<a class="tipbtn" href="javascript:fn___in_matrix_tips(' + in_id + ')">' + tips_button + '</a>');
}

function fn___load_js_algolia() {
    $(".algolia_search").focus(function () {
        //Loadup Algolia once:
        if (!algolia_loaded) {
            algolia_loaded = true;
            client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
            algolia_index = client.initIndex('alg_index');
        }
    });
}

//Function to load all help messages throughout the matrix:
$(document).ready(function () {

    fn___load_js_algolia();

    $(".bottom-add").focus(function () {
        //Give more space at the bottom to see search results:
        if (!$(".dash").hasClass('dash-expand')) {
            $(".dash").addClass('dash-expand');
            //$('.main-panel').animate({ scrollTop:9999 }, 150);
        }
    });

    $("#matrix_search").on('autocomplete:selected', function (event, suggestion, dataset) {

        if (parseInt(suggestion.alg_obj_is_in)==1) {
            window.location = "/intents/" + suggestion.alg_obj_id;
        } else {
            window.location = "/entities/" + suggestion.alg_obj_id;
        }

    }).autocomplete({hint: false, minLength: 3, autoselect: true, keyboardShortcuts: ['s']}, [
        {
            source: function (q, cb) {

                //Append filters:
                algolia_index.search(q, {
                    hitsPerPage: 14,
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
            }
        }
    ]);


    if ($("span.help_button")[0]) {
        var loaded_messages = [];
        var in_id = 0;
        $("span.help_button").each(function () {
            in_id = parseInt($(this).attr('intent-id'));
            if (in_id > 0 && $("div#content_" + in_id)[0] && !(jQuery.inArray(in_id, loaded_messages) != -1)) {
                //Its valid as all elements match! Let's continue:
                loaded_messages.push(in_id);
                //Load the Tip icon so they can access the tip if they like:
                fn___load_help(in_id);
            }
        });
    }


    $('#topnav li a').click(function (event) {
        event.preventDefault();
        var hash = $(this).attr('href').replace('#', '');
        window.location.hash = hash;
        fn___adjust_hash(hash);
    });

});

function fn___adjust_hash(hash) {
    if (hash.length > 0 && $('#tab' + hash).length && !$('#tab' + hash).hasClass("hidden")) {
        //Adjust Header:
        $('#topnav>li').removeClass('active');
        $('#nav_' + hash).addClass('active');
        //Adjust Tab:
        $('.tab-pane').removeClass('active');
        $('#tab' + hash).addClass('active');
    }
}
