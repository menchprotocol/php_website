<style>
    .container {
        margin-left: 8px;
        max-width: calc(100% - 16px) !important;
    }
    td{
        overflow: hidden;
    }
</style>
<?php

//Construct filters based on GET variables:
$query_filters = array();
$joined_by = array();

//We have a special OR filter when combined with playerhandle & ideahashtag
$input_e = (isset($_GET['playerhandle']) && strlen($_GET['playerhandle']) > 0);
$focus_e = false;
$input_i = (isset($_GET['ideahashtag']) && strlen($_GET['ideahashtag']) > 0);
$focus_i = false;

if ($input_e) {
    foreach ($this->Players->read(array(
        'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
    )) as $player_found) {
        $focus_e = $player_found;
        $_GET['playerhandle'] = $player_found['playerhandle'];
    }
    if (!$focus_e) {
        //Invalid input!
        $input_e = false;
    }
}

if ($input_i) {
    foreach ($this->Ideas->read(array(
        'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
    )) as $idea_found) {
        $focus_i = $idea_found;
        $_GET['ideahashtag'] = $idea_found['ideahashtag'];
    }
    if (!$focus_i) {
        //Invalid input!
        $input_i = false;
    }
}

$any_ideaplayer_set = $input_i || $input_e;


if (isset($_GET['linkplayercreator']) && strlen($_GET['linkplayercreator']) > 0) {
    if (substr_count($_GET['linkplayercreator'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkplayercreator IN (' . $_GET['linkplayercreator'] . '))'] = null;
    } elseif (intval($_GET['linkplayercreator']) > 0) {
        $query_filters['linkplayercreator'] = $_GET['linkplayercreator'];
    }
}


if (isset($_GET['linkplayerup']) && strlen($_GET['linkplayerup']) > 0) {
    if (substr_count($_GET['linkplayerup'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkplayerup IN (' . $_GET['linkplayerup'] . '))'] = null;
    } elseif (intval($_GET['linkplayerup']) > 0) {
        $query_filters['linkplayerup'] = $_GET['linkplayerup'];
    }
}

if (isset($_GET['linkplayerdown']) && strlen($_GET['linkplayerdown']) > 0) {
    if (substr_count($_GET['linkplayerdown'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkplayerdown IN (' . $_GET['linkplayerdown'] . '))'] = null;
    } elseif (intval($_GET['linkplayerdown']) > 0) {
        $query_filters['linkplayerdown'] = $_GET['linkplayerdown'];
    }
}

if (isset($_GET['linkidealeft']) && strlen($_GET['linkidealeft']) > 0) {
    if (substr_count($_GET['linkidealeft'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkidealeft IN (' . $_GET['linkidealeft'] . '))'] = null;
    } elseif (intval($_GET['linkidealeft']) > 0) {
        $query_filters['linkidealeft'] = $_GET['linkidealeft'];
    }
}

if (isset($_GET['linkidearight']) && strlen($_GET['linkidearight']) > 0) {
    if (substr_count($_GET['linkidearight'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkidearight IN (' . $_GET['linkidearight'] . '))'] = null;
    } elseif (intval($_GET['linkidearight']) > 0) {
        $query_filters['linkidearight'] = $_GET['linkidearight'];
    }
}

if (isset($_GET['linkid']) && strlen($_GET['linkid']) > 0) {
    if (substr_count($_GET['linkid'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkid IN (' . $_GET['linkid'] . '))'] = null;
    } elseif (intval($_GET['linkid']) > 0) {
        $query_filters['linkid'] = $_GET['linkid'];
    }
}

if ($input_e) {
    //We need to look for both following/follower
    $query_filters['( linkplayerdown = ' . $focus_e['playerid'] . ' OR linkplayerup = ' . $focus_e['playerid'] . ' OR linkplayercreator = ' . $focus_e['playerid'] . ' )'] = null;
}


if ($input_i) {
    //We need to look for both following/follower
    $query_filters['( linkidearight = ' . $focus_i['ideaid'] . ' OR linkidealeft = ' . $focus_i['ideaid'] . ')'] = null;

}

if (isset($_GET['any_linkid']) && strlen($_GET['any_linkid']) > 0) {
    //We need to look for both following/follower
    if (substr_count($_GET['any_linkid'], ',') > 0) {
        //This is multiple:
        $query_filters['linkid IN (' . $_GET['any_linkid'] . ')'] = null;
    } elseif (intval($_GET['any_linkid']) > 0) {
        $query_filters['linkid'] = $_GET['any_linkid'];
    }
}

if (isset($_GET['linktext_find']) && strlen($_GET['linktext_find']) > 0) {
    $query_filters['LOWER(linktext) LIKE'] = '%' . $_GET['linktext_find'] . '%';
}

if (isset($_GET['linkvoid']) && is_numeric($_GET['linkvoid'])) {
    if ($_GET['linkvoid'] == 1) {
        $query_filters['linkvoid >'] = 0;
    } else {
        $query_filters['linkvoid'] = $_GET['linkvoid'];
    }
}


if (isset($_GET['start_range']) && string_is_date($_GET['start_range'])) {
    $query_filters['linktime >='] = $_GET['start_range'] . (strlen($_GET['start_range']) <= 10 ? ' 00:00:00' : '');
}
if (isset($_GET['end_range']) && string_is_date($_GET['end_range'])) {
    $query_filters['linktime <='] = $_GET['end_range'] . (strlen($_GET['end_range']) <= 10 ? ' 23:59:59' : '');
}


//Fetch unique Link types recorded so far:
$ini_filter = array();
foreach ($query_filters as $key => $value) {
    $ini_filter[$key] = $value;
}

$query_filters['linkvoid >='] = 0;


//Make sure its a valid type considering other filters:
if (isset($_GET['linkplayertype'])) {

    if (substr_count($_GET['linkplayertype'], ',') > 0) {
        //This is multiple:
        $query_filters['linkplayertype IN (' . $_GET['linkplayertype'] . ')'] = null;
    } elseif (intval($_GET['linkplayertype']) > 0) {
        $query_filters['linkplayertype'] = intval($_GET['linkplayertype']);
    }

}

$has_filters = (count($_GET) > 0);

$players___11035 = $this->config->item('players___11035'); //Encyclopedia

?>

<script>

    var $win = $(window);
    var x_filters = '<?= serialize(count($query_filters) > 0 ? $query_filters : array()) ?>';
    var x_joined_by = '<?= serialize(count($joined_by) > 0 ? $joined_by : array()) ?>';
    var linktext_find = '<?= (isset($_GET['linktext_find']) && strlen($_GET['linktext_find']) > 0 ? $_GET['linktext_find'] : '') ?>';
    var linktext_replace = '<?= (isset($_GET['linktext_replace']) && strlen($_GET['linktext_replace']) > 0 ? $_GET['linktext_replace'] : '') ?>';
    var has_more_links = 1; //We always assume this?
    var loading_in_progress = false;
    var current_page = 0;

    function link_load() {

        if (!has_more_links || loading_in_progress) {
            return false;
        }

        loading_in_progress = true;
        current_page++;
        console.log('Now loading page ' + current_page);

        //Show spinner:
        $('.load_message').removeClass('hidden');
        $('.random_message').text(js_randomize_text(12694));

        //Load report based on input fields:
        $.post("/controller/link_load", {
            x_filters: x_filters,
            x_joined_by: x_joined_by,
            linktext_find: linktext_find,
            linktext_replace: linktext_replace,
            current_page: current_page,
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {
            loading_in_progress = false;
            $('.load_message').addClass('hidden');
            if (!data.status) {
                //Show Error:
                alert(data.message);
            } else {
                //Load Report:
                $('#table_menchledger tr:last').after(data.message);
                if (data.overall_stats.length) {
                    $('.overall_stats').html(data.overall_stats);
                }
                has_more_links = data.has_more_links;
                setup_popover();
                load_at_bottom(); //Load more?
            }
        });

    }

    function load_at_bottom(){
        if (parseInt($(document).height() - ($win.height() + $win.scrollTop())) < 377) {
            link_load();
        }
    }

    $(document).ready(function () {

        //Load first page of Links:
        link_load();

        $(function () {
            $win.scroll(function () {
                load_at_bottom();
            });
        });

    });


</script>

<?php

if (superpower_unlocked(12701)) {
    echo '<div class="inline-block margin-top-down" style="padding-left:7px;"><span class="icon-block">' . $players___11035[12707]['m__cover'] . '</span><a href="javascript:void();" onclick="$(\'.show-filter\').toggleClass(\'hidden\');" class="main__title">' . $players___11035[12707]['m__title'] . '</a></div>';
}


echo '<div class="show-filter ' . ($has_filters && 0 ? '' : 'hidden') . '">';
echo '<form action="" method="GET">';


echo '<table class="table table-sm maxout"><tr>';

//ANY IDEA
echo '<td><div>';
echo '<span class="mini-header">ANY IDEA:</span>';
echo '<input type="text" name="ideahashtag" value="' . ($input_i ? $_GET['ideahashtag'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">IDEA PREVIOUS:</span><input type="text" name="linkidealeft" value="' . ((isset($_GET['linkidealeft'])) ? $_GET['linkidealeft'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">IDEA NEXT:</span><input type="text" name="linkidearight" value="' . ((isset($_GET['linkidearight'])) ? $_GET['linkidearight'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';


echo '<table class="table table-sm maxout"><tr>';

//ANY SOURCE
echo '<td><div>';
echo '<span class="mini-header">ANY SOURCE:</span>';
echo '<input type="text" name="playerhandle" value="' . ($input_e ? $_GET['playerhandle'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">SOURCE CREATOR:</span><input type="text" name="linkplayercreator" value="' . ((isset($_GET['linkplayercreator'])) ? $_GET['linkplayercreator'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">SOURCE PROFILE:</span><input type="text" name="linkplayerup" value="' . ((isset($_GET['linkplayerup'])) ? $_GET['linkplayerup'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">SOURCE followers:</span><input type="text" name="linkplayerdown" value="' . ((isset($_GET['linkplayerdown'])) ? $_GET['linkplayerdown'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';


echo '<table class="table table-sm maxout"><tr>';

//ANY DISCOVERY
echo '<td><div>';
echo '<span class="mini-header">ANY Link:</span>';
echo '<input type="text" name="any_linkid" value="' . ((isset($_GET['any_linkid'])) ? $_GET['any_linkid'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">Link ID:</span><input type="text" name="linkid" value="' . ((isset($_GET['linkid'])) ? $_GET['linkid'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';


echo '<table class="table table-sm maxout"><tr>';


//Search
echo '<td><div>';
echo '<span class="mini-header">Link MESSAGE SEARCH:</span>';
echo '<input type="text" name="linktext_find" value="' . ((isset($_GET['linktext_find'])) ? $_GET['linktext_find'] : '') . '" class="form-control border">';
echo '</div></td>';

if (isset($_GET['linktext_find']) && strlen($_GET['linktext_find']) > 0 && superpower_unlocked(12701)) {
    //Give Option to Replace:
    echo '<td><div>';
    echo '<span class="mini-header">Link MESSAGE REPLACE:</span>';
    echo '<input type="text" name="linktext_replace" value="' . ((isset($_GET['linktext_replace'])) ? $_GET['linktext_replace'] : '') . '" class="form-control border">';
    echo '</div></td>';
}


//DISCOVERY Type Filter Groups
echo '<td></td>';


//Filters UI:
echo '<table class="table table-sm maxout"><tr>';

echo '<td valign="top" style="vertical-align: top;"><div>';
echo '<span class="mini-header">START DATE:</span>';
echo '<input type="date" class="form-control border" name="start_range" value="' . (isset($_GET['start_range']) ? $_GET['start_range'] : '') . '">';
echo '</div></td>';

echo '<td valign="top" style="vertical-align: top;"><div>';
echo '<span class="mini-header">END DATE:</span>';
echo '<input type="date" class="form-control border" name="end_range" value="' . (isset($_GET['end_range']) ? $_GET['end_range'] : '') . '">';
echo '</div></td>';


echo '<td>';
echo '<div>';
echo '<span class="mini-header">Link TYPE:</span>';

if (isset($_GET['linkplayertype']) && substr_count($_GET['linkplayertype'], ',') > 0) {

    //We have multiple predefined Link types, so we must use a text input:
    echo '<input type="text" name="linkplayertype" value="' . $_GET['linkplayertype'] . '" class="form-control border">';

} else {

    echo '<select class="form-control border" name="linkplayertype" id="linkplayertype" class="border" style="width: 100% !important;">';

    if (isset($_GET['linkplayercreator'])) {

        //Fetch details for this member:
        $all_x_count = 0;
        $select_ui = '';
        foreach ($this->Links->read($ini_filter, array('linkplayertype'), 0, 0, player_sort(), 'COUNT(linkplayertype) as total_count, playertext, linkplayertype', 'linkplayertype, playertext') as $x) {
            //Echo drop down:
            $select_ui .= '<option value="' . $x['linkplayertype'] . '" ' . ((isset($_GET['linkplayertype']) && $_GET['linkplayertype'] == $x['linkplayertype']) ? 'selected="selected"' : '') . '>' . $x['playertext'] . ' (' . number_format($x['total_count'], 0) . ')</option>';
            $all_x_count += $x['total_count'];
        }

        //Now that we know the total show:
        echo '<option value="0">All (' . number_format($all_x_count, 0) . ')</option>';
        echo $select_ui;

    } else {

        //Load all fast:
        echo '<option value="0">ALL Link TYPES</option>';
        foreach ($this->config->item('players___4593') /* DISCOVERY Types */ as $playerid => $m) {
            //Echo drop down:
            echo '<option value="' . $playerid . '" ' . ((isset($_GET['linkplayertype']) && $_GET['linkplayertype'] == $playerid) ? 'selected="selected"' : '') . '>' . $m['m__title'] . '</option>';
        }

    }

    echo '</select>';


}

echo '</div>';

echo '</td>';

echo '</tr></table>';


echo '</tr></table>';


echo '<input type="submit" class="btn" value="Apply" />';

if ($has_filters) {
    echo ' &nbsp;<a href="' . view_app_link(4341) . '" style="font-size: 0.8em;">Remove Filters</a>';
}

echo '</form>';
echo '</div>';

//AJAX Would load content here:
echo '<div class="overall_stats"></div>';
echo '<table id="table_menchledger" class="table table-sm table-striped image-mini" style="font-size: 0.7em;">'; //table-layout: fixed;
echo '<tr style="font-weight:bold; vertical-align: baseline;">';
foreach ($this->config->item('players___4341') as $linkplayertype => $m) {
    echo '<th class="main__title">' . $m['m__title'] . '</th>';
}
echo '</tr>';
echo '</table>';

echo '<div class="main__title center hidden load_message"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span><span class="random_message"></span></div>';
