//Loadup algolia when any related field is focused on:
var algolia_loaded = false;


//Google Analytics:
(function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
        (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o),
        m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
ga('create', 'UA-92774608-1', 'auto');
ga('send', 'pageview');


function js_ln_create(new_ln_data){
    return $.post("/exchange/js_ln_create", new_ln_data, function (data) {
        return data;
    });
}


function echo_js_suggestion(alg_obj){

    //Determine object type:
    var obj_type = ( parseInt(alg_obj.alg_obj_is_in)==1 ? 'in' : 'en' );

    if(obj_type=='in'){
        var focus_field = js_en_all_4737;
    } else {
        var focus_field = js_en_all_6177;
    }

    var obj_full_name = ( alg_obj._highlightResult && alg_obj._highlightResult.alg_obj_name.value ? alg_obj._highlightResult.alg_obj_name.value : alg_obj.alg_obj_name );

    return '<span class="icon-block">' + alg_obj.alg_obj_icon + '</span>' + obj_full_name;
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
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
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

function load_js_algolia() {
    $(".algolia_search").focus(function () {
        //Loadup Algolia once:
        if (!algolia_loaded) {
            algolia_loaded = true;
            client = algoliasearch('49OCX1ZXLJ', 'ca3cf5f541daee514976bc49f8399716');
            algolia_index = client.initIndex('alg_index');
        }
    });
}

function loadtab(tab_group_id, tab_data_id){

    //Hide all tabs:
    $('.tab-group-'+tab_group_id).addClass('hidden');
    $('.tab-nav-'+tab_group_id).removeClass('active');

    //Show this tab:
    $('.tab-group-'+tab_group_id+'.tab-data-'+tab_data_id).removeClass('hidden');
    $('.tab-nav-'+tab_group_id+'.tab-head-'+tab_data_id).addClass('active');

    //Load data:

}


$(document).ready(function () {


    load_js_algolia();


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
            window.location = "/exchange/" + suggestion.alg_obj_id;
        } else {
            window.location = "/players/" + suggestion.alg_obj_id;
        }

    }).autocomplete({minLength: 1, autoselect: true, keyboardShortcuts: ['s']}, [
        {
            source: function (q, cb) {
                //Do not search if specific command:
                if (($("#mench_search").val().charAt(0) == '#' || $("#mench_search").val().charAt(0) == '@') && !isNaN($("#mench_search").val().substr(1))) {
                    cb([]);
                    return;
                } else {

                    //Append filters:
                    algolia_index.search(q, {
                        hitsPerPage: 20,
                        filters:' alg_obj_is_in' + ($("#mench_search").val().charAt(0) == '#' ? '=1' : ($("#mench_search").val().charAt(0) == '@' ? '=0' : '>=0')) + ( js_assigned_superpowers_en_ids.includes(10989 /* PEGASUS */) ? '' : ' AND _tags:alg_is_published_featured'),
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
                            return '<a href="/' + ( $("#mench_search").val().charAt(0)=='#' ? 'intents' : 'entities' ) + '/' + search_body + '" class="suggestion"><i class="far fa-level-up rotate90" style="margin: 0 5px;"></i> Go to ' + data.query
                        }

                    }
                },
                empty: function (data) {
                    if(validURL(data.query)){
                        return en_fetch_canonical_url(data.query, true);
                    } else if($("#mench_search").val().charAt(0)=='#'){
                        if(isNaN($("#mench_search").val().substr(1))){
                            return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No BLOG found</div>';
                        }
                    } else if($("#mench_search").val().charAt(0)=='@'){
                        if(isNaN($("#mench_search").val().substr(1))) {
                            return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No PLAY found</div>';
                        }
                    } else {
                        return '<div class="not-found"><i class="fas fa-exclamation-triangle"></i> No results found</div>';
                    }
                },
            }
        }
    ]);



});