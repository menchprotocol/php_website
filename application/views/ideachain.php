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

//We have a special OR filter when combined with handlehandle & hashtaghashtag
$input_e = (isset($_GET['handlehandle']) && strlen($_GET['handlehandle']) > 0);
$focus_e = false;
$input_i = (isset($_GET['hashtaghashtag']) && strlen($_GET['hashtaghashtag']) > 0);
$focus_i = false;

if ($input_e) {
    foreach ($this->Handles->read(array(
        'LOWER(handlehandle)' => strtolower($_GET['handlehandle']),
    )) as $handle_found) {
        $focus_e = $handle_found;
        $_GET['handlehandle'] = $handle_found['handlehandle'];
    }
    if (!$focus_e) {
        //Invalid input!
        $input_e = false;
    }
}

if ($input_i) {
    foreach ($this->Hashtags->read(array(
        'LOWER(hashtaghashtag)' => strtolower($_GET['hashtaghashtag']),
    )) as $hashtag_found) {
        $focus_i = $hashtag_found;
        $_GET['hashtaghashtag'] = $hashtag_found['hashtaghashtag'];
    }
    if (!$focus_i) {
        //Invalid input!
        $input_i = false;
    }
}

$any_hashtaghandle_set = $input_i || $input_e;


if (isset($_GET['chainhandlecreator']) && strlen($_GET['chainhandlecreator']) > 0) {
    if (substr_count($_GET['chainhandlecreator'], ',') > 0) {
        //This is multiple:
        $query_filters['( chainhandlecreator IN (' . $_GET['chainhandlecreator'] . '))'] = null;
    } elseif (intval($_GET['chainhandlecreator']) > 0) {
        $query_filters['chainhandlecreator'] = $_GET['chainhandlecreator'];
    }
}


if (isset($_GET['chainhandleinput']) && strlen($_GET['chainhandleinput']) > 0) {
    if (substr_count($_GET['chainhandleinput'], ',') > 0) {
        //This is multiple:
        $query_filters['( chainhandleinput IN (' . $_GET['chainhandleinput'] . '))'] = null;
    } elseif (intval($_GET['chainhandleinput']) > 0) {
        $query_filters['chainhandleinput'] = $_GET['chainhandleinput'];
    }
}

if (isset($_GET['chainhandleoutput']) && strlen($_GET['chainhandleoutput']) > 0) {
    if (substr_count($_GET['chainhandleoutput'], ',') > 0) {
        //This is multiple:
        $query_filters['( chainhandleoutput IN (' . $_GET['chainhandleoutput'] . '))'] = null;
    } elseif (intval($_GET['chainhandleoutput']) > 0) {
        $query_filters['chainhandleoutput'] = $_GET['chainhandleoutput'];
    }
}

if (isset($_GET['chainhashtaginput']) && strlen($_GET['chainhashtaginput']) > 0) {
    if (substr_count($_GET['chainhashtaginput'], ',') > 0) {
        //This is multiple:
        $query_filters['( chainhashtaginput IN (' . $_GET['chainhashtaginput'] . '))'] = null;
    } elseif (intval($_GET['chainhashtaginput']) > 0) {
        $query_filters['chainhashtaginput'] = $_GET['chainhashtaginput'];
    }
}

if (isset($_GET['chainhashtagoutput']) && strlen($_GET['chainhashtagoutput']) > 0) {
    if (substr_count($_GET['chainhashtagoutput'], ',') > 0) {
        //This is multiple:
        $query_filters['( chainhashtagoutput IN (' . $_GET['chainhashtagoutput'] . '))'] = null;
    } elseif (intval($_GET['chainhashtagoutput']) > 0) {
        $query_filters['chainhashtagoutput'] = $_GET['chainhashtagoutput'];
    }
}

if (isset($_GET['chainid']) && strlen($_GET['chainid']) > 0) {
    if (substr_count($_GET['chainid'], ',') > 0) {
        //This is multiple:
        $query_filters['( chainid IN (' . $_GET['chainid'] . '))'] = null;
    } elseif (intval($_GET['chainid']) > 0) {
        $query_filters['chainid'] = $_GET['chainid'];
    }
}

if ($input_e) {
    //We need to look for both following/follower
    $query_filters['( chainhandleoutput = ' . $focus_e['handleid'] . ' OR chainhandleinput = ' . $focus_e['handleid'] . ' OR chainhandlecreator = ' . $focus_e['handleid'] . ' )'] = null;
}


if ($input_i) {
    //We need to look for both following/follower
    $query_filters['( chainhashtagoutput = ' . $focus_i['hashtagid'] . ' OR chainhashtaginput = ' . $focus_i['hashtagid'] . ')'] = null;

}

if (isset($_GET['any_chainid']) && strlen($_GET['any_chainid']) > 0) {
    //We need to look for both following/follower
    if (substr_count($_GET['any_chainid'], ',') > 0) {
        //This is multiple:
        $query_filters['chainid IN (' . $_GET['any_chainid'] . ')'] = null;
    } elseif (intval($_GET['any_chainid']) > 0) {
        $query_filters['chainid'] = $_GET['any_chainid'];
    }
}

if (isset($_GET['chainvalue_find']) && strlen($_GET['chainvalue_find']) > 0) {
    $query_filters['LOWER(chainvalue) LIKE'] = '%' . $_GET['chainvalue_find'] . '%';
}

if (isset($_GET['chainvoid']) && is_numeric($_GET['chainvoid'])) {
    if ($_GET['chainvoid'] == 1) {
        $query_filters['chainvoid >'] = 0;
    } else {
        $query_filters['chainvoid'] = $_GET['chainvoid'];
    }
}


if (isset($_GET['start_range']) && string_is_date($_GET['start_range'])) {
    $query_filters['chaintime >='] = $_GET['start_range'] . (strlen($_GET['start_range']) <= 10 ? ' 00:00:00' : '');
}
if (isset($_GET['end_range']) && string_is_date($_GET['end_range'])) {
    $query_filters['chaintime <='] = $_GET['end_range'] . (strlen($_GET['end_range']) <= 10 ? ' 23:59:59' : '');
}


//Fetch unique Chain types recorded so far:
$ini_filter = array();
foreach ($query_filters as $key => $value) {
    $ini_filter[$key] = $value;
}

$query_filters['chainvoid >='] = 0; //Any Chain


//Make sure its a valid type considering other filters:
if (isset($_GET['chainhandletype'])) {

    if (substr_count($_GET['chainhandletype'], ',') > 0) {
        //This is multiple:
        $query_filters['chainhandletype IN (' . $_GET['chainhandletype'] . ')'] = null;
    } elseif (intval($_GET['chainhandletype']) > 0) {
        $query_filters['chainhandletype'] = intval($_GET['chainhandletype']);
    }

}

$has_filters = (count($_GET) > 0);

$handles___11035 = $this->config->item('handles___11035'); //Encyclopedia

?>

<script>

    var $win = $(window);
    var x_filters = '<?= serialize(count($query_filters) > 0 ? $query_filters : array()) ?>';
    var x_joined_by = '<?= serialize(count($joined_by) > 0 ? $joined_by : array()) ?>';
    var chainvalue_find = '<?= (isset($_GET['chainvalue_find']) && strlen($_GET['chainvalue_find']) > 0 ? $_GET['chainvalue_find'] : '') ?>';
    var chainvalue_replace = '<?= (isset($_GET['chainvalue_replace']) && strlen($_GET['chainvalue_replace']) > 0 ? $_GET['chainvalue_replace'] : '') ?>';
    var has_more_chains = 1; //We always assume this?
    var loading_in_progress = false;
    var current_page = 0;

    function chain_load() {

        if (!has_more_chains || loading_in_progress) {
            return false;
        }

        loading_in_progress = true;
        current_page++;
        console.log('Now loading page ' + current_page);

        //Show spinner:
        $('.load_message').removeClass('hidden');
        $('.random_message').text(js_randomize_text(12694));

        //Load report based on input fields:
        $.post("/controller/chain_load", {
            x_filters: x_filters,
            x_joined_by: x_joined_by,
            chainvalue_find: chainvalue_find,
            chainvalue_replace: chainvalue_replace,
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
                $('#table_ideachain tr:last').after(data.message);
                if (data.overall_stats.length) {
                    $('.overall_stats').html(data.overall_stats);
                }
                has_more_chains = data.has_more_chains;
                setup_popover();
                load_at_bottom(); //Load more?
            }
        });

    }

    function load_at_bottom(){
        if (parseInt($(document).height() - ($win.height() + $win.scrollTop())) < 377) {
            chain_load();
        }
    }

    $(document).ready(function () {

        //Load first page of Chains:
        chain_load();

        $(function () {
            $win.scroll(function () {
                load_at_bottom();
            });
        });

    });


</script>

<?php



echo '<div class="show-filter ' . ($has_filters && 0 ? '' : 'hidden') . '">';
echo '<form action="" method="GET">';


echo '<table class="table table-sm maxout" style="vertical-align: top;"><tr>';

//ANY HASHTAG
echo '<td><div>';
echo '<span class="mini-header">ANY HASHTAG:</span>';
echo '<input type="text" name="hashtaghashtag" value="' . ($input_i ? $_GET['hashtaghashtag'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">HASHTAG PREVIOUS:</span><input type="text" name="chainhashtaginput" value="' . ((isset($_GET['chainhashtaginput'])) ? $_GET['chainhashtaginput'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">HASHTAG NEXT:</span><input type="text" name="chainhashtagoutput" value="' . ((isset($_GET['chainhashtagoutput'])) ? $_GET['chainhashtagoutput'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';


echo '<table class="table table-sm maxout"><tr>';

//ANY HANDLE
echo '<td><div>';
echo '<span class="mini-header">ANY HANDLE:</span>';
echo '<input type="text" name="handlehandle" value="' . ($input_e ? $_GET['handlehandle'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">HANDLE CREATOR:</span><input type="text" name="chainhandlecreator" value="' . ((isset($_GET['chainhandlecreator'])) ? $_GET['chainhandlecreator'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">HANDLE PROFILE:</span><input type="text" name="chainhandleinput" value="' . ((isset($_GET['chainhandleinput'])) ? $_GET['chainhandleinput'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">HANDLE followers:</span><input type="text" name="chainhandleoutput" value="' . ((isset($_GET['chainhandleoutput'])) ? $_GET['chainhandleoutput'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';


echo '<table class="table table-sm maxout"><tr>';

//ANY DISCOVERY
echo '<td><div>';
echo '<span class="mini-header">ANY Chain:</span>';
echo '<input type="text" name="any_chainid" value="' . ((isset($_GET['any_chainid'])) ? $_GET['any_chainid'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">Chain ID:</span><input type="text" name="chainid" value="' . ((isset($_GET['chainid'])) ? $_GET['chainid'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';


echo '<table class="table table-sm maxout"><tr>';


//Search
echo '<td><div>';
echo '<span class="mini-header">Chain MESSAGE SEARCH:</span>';
echo '<input type="text" name="chainvalue_find" value="' . ((isset($_GET['chainvalue_find'])) ? $_GET['chainvalue_find'] : '') . '" class="form-control border">';
echo '</div></td>';

if (isset($_GET['chainvalue_find']) && strlen($_GET['chainvalue_find']) > 0 && handle_session(12701)) {
    //Give Option to Replace:
    echo '<td><div>';
    echo '<span class="mini-header">Chain MESSAGE REPLACE:</span>';
    echo '<input type="text" name="chainvalue_replace" value="' . ((isset($_GET['chainvalue_replace'])) ? $_GET['chainvalue_replace'] : '') . '" class="form-control border">';
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
echo '<span class="mini-header">Chain TYPE:</span>';

if (isset($_GET['chainhandletype']) && substr_count($_GET['chainhandletype'], ',') > 0) {

    //We have multiple predefined Chain types, so we must use a text input:
    echo '<input type="text" name="chainhandletype" value="' . $_GET['chainhandletype'] . '" class="form-control border">';

} else {

    echo '<select class="form-control border" name="chainhandletype" id="chainhandletype" class="border" style="width: 100% !important;">';

    if (isset($_GET['chainhandlecreator'])) {

        //Fetch details for this member:
        $all_x_count = 0;
        $select_ui = '';
        foreach ($this->Chains->read($ini_filter, array('chainhandletype'), 0, 0, handle_sort(), 'COUNT(chainhandletype) as total_count, handlevalue, chainhandletype', 'chainhandletype, handlevalue') as $x) {
            //Echo drop down:
            $select_ui .= '<option value="' . $x['chainhandletype'] . '" ' . ((isset($_GET['chainhandletype']) && $_GET['chainhandletype'] == $x['chainhandletype']) ? 'selected="selected"' : '') . '>' . $x['handlevalue'] . ' (' . number_format($x['total_count'], 0) . ')</option>';
            $all_x_count += $x['total_count'];
        }

        //Now that we know the total show:
        echo '<option value="0">All (' . number_format($all_x_count, 0) . ')</option>';
        echo $select_ui;

    } else {

        //Load all fast:
        echo '<option value="0">ALL Chain TYPES</option>';
        foreach ($this->config->item('handles___4593') /* DISCOVERY Types */ as $handleid => $m) {
            //Echo drop down:
            echo '<option value="' . $handleid . '" ' . ((isset($_GET['chainhandletype']) && $_GET['chainhandletype'] == $handleid) ? 'selected="selected"' : '') . '>' . $m['m__title'] . '</option>';
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
    echo ' &nbsp;<a href="' . view_app_chain(4341) . '" style="font-size: 0.8em;">Remove Filters</a>';
}

echo '</form>';
echo '</div>';

//AJAX Would load content here:
echo '<div class="overall_stats"></div>';

echo '<div class="filter_right grey">'.(handle_session(12701) ? '<span class="icon-block-xs">' . $handles___11035[12707]['m__cover'] . '</span><a href="javascript:void();" onclick="$(\'.show-filter\').toggleClass(\'hidden\');" class="main__title">' . $handles___11035[12707]['m__title'] . '</a>' : '').'</div>';


//Table Header
$row1 = '<tr style="font-weight:bold; vertical-align: baseline; border-top: 3px solid #999999; border-bottom: 0px solid #FFFFFF !important;">';
$row2 = '<tr style="font-weight:bold; vertical-align: baseline; border-top: 0px solid #FFFFFF !important; border-bottom: 3px solid #999999;">';
foreach ($this->config->item('handles___4341') as $chainhandletype => $m) {
    if($chainhandletype==4362 || in_array($chainhandletype, $this->config->item('handleids___6160'))){
        //Handle Cover:
        $column_value = '<th class="main__title" style="width:25px !important;"><a style="width:25px !important; overflow:hidden; display: block;" href="/@'.$m['m__handle'].'" title="' . $m['m__title'] . '" data-toggle="tooltip" data-placement="top" class="icon-block-sm">' . $m['m__cover'] . '</a></th>';
    } else {
        //Else:
        $column_value = '<th class="main__title" style=";"><a href="/@'.$m['m__handle'].'">' . $m['m__title'] . '</a></th>';
    }
    if(in_array($chainhandletype, $this->config->item('handleids___1579727'))) {
        //Second row:
        $row2 .= $column_value;
    } else {
        $row1 .= $column_value;
    }
}
$row1 .= '</tr>';
$row2 .= '</tr>';
echo '<table id="table_ideachain" class="table table-sm image-mini" style="font-size: 0.8em;">'.$row1.$row2.'</table>';

//Table Data
echo '<div class="main__title center hidden load_message"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span><span class="random_message"></span></div>';
