<?php

//Construct filters based on GET variables:
$query_filters = array();
$joined_by = array();

//We have a special OR filter when combined with playerhandle & ideahashtag
$input_e = ( isset($_GET['playerhandle']) && strlen($_GET['playerhandle']) > 0 );
$focus_e = false;
$input_i = ( isset($_GET['ideahashtag']) && strlen($_GET['ideahashtag']) > 0 );
$focus_i = false;

if($input_e){
    foreach($this->Source_cache->fetch(array(
        'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
    )) as $e_found){
        $focus_e = $e_found;
        $_GET['playerhandle'] = $e_found['playerhandle'];
    }
    if(!$focus_e){
        //Invalid input!
        $input_e = false;
    }
}

if($input_i){
    foreach($this->Idea_cache->fetch(array(
        'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
    )) as $i_found){
        $focus_i = $i_found;
        $_GET['ideahashtag'] = $i_found['ideahashtag'];
    }
    if(!$focus_i){
        //Invalid input!
        $input_i = false;
    }
}

$any_i_e_set = $input_i || $input_e;


if(isset($_GET['linkplayer']) && strlen($_GET['linkplayer']) > 0){
    if (substr_count($_GET['linkplayer'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkplayer IN (' . $_GET['linkplayer'] . '))'] = null;
    } elseif (intval($_GET['linkplayer']) > 0) {
        $query_filters['linkplayer'] = $_GET['linkplayer'];
    }
}


if(isset($_GET['linkup']) && strlen($_GET['linkup']) > 0){
    if (substr_count($_GET['linkup'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkup IN (' . $_GET['linkup'] . '))'] = null;
    } elseif (intval($_GET['linkup']) > 0) {
        $query_filters['linkup'] = $_GET['linkup'];
    }
}

if(isset($_GET['linkdown']) && strlen($_GET['linkdown']) > 0){
    if (substr_count($_GET['linkdown'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkdown IN (' . $_GET['linkdown'] . '))'] = null;
    } elseif (intval($_GET['linkdown']) > 0) {
        $query_filters['linkdown'] = $_GET['linkdown'];
    }
}

if(isset($_GET['linkleft']) && strlen($_GET['linkleft']) > 0){
    if (substr_count($_GET['linkleft'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkleft IN (' . $_GET['linkleft'] . '))'] = null;
    } elseif (intval($_GET['linkleft']) > 0) {
        $query_filters['linkleft'] = $_GET['linkleft'];
    }
}

if(isset($_GET['linkright']) && strlen($_GET['linkright']) > 0){
    if (substr_count($_GET['linkright'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkright IN (' . $_GET['linkright'] . '))'] = null;
    } elseif (intval($_GET['linkright']) > 0) {
        $query_filters['linkright'] = $_GET['linkright'];
    }
}

if(isset($_GET['linkid']) && strlen($_GET['linkid']) > 0){
    if (substr_count($_GET['linkid'], ',') > 0) {
        //This is multiple:
        $query_filters['( linkid IN (' . $_GET['linkid'] . '))'] = null;
    } elseif (intval($_GET['linkid']) > 0) {
        $query_filters['linkid'] = $_GET['linkid'];
    }
}

if($input_e){
    //We need to look for both following/follower
    $query_filters['( linkdown = ' . $focus_e['playerid'] . ' OR linkup = ' . $focus_e['playerid'] . ' OR linkplayer = ' . $focus_e['playerid'] . ' )'] = null;
}


if($input_i){
    //We need to look for both following/follower
    $query_filters['( linkright = ' . $focus_i['ideaid'] . ' OR linkleft = ' . $focus_i['ideaid'] . ')'] = null;

}

if(isset($_GET['any_linkid']) && strlen($_GET['any_linkid']) > 0){
    //We need to look for both following/follower
    if (substr_count($_GET['any_linkid'], ',') > 0) {
        //This is multiple:
        $query_filters['linkid IN (' . $_GET['any_linkid'] . ')'] = null;
    } elseif (intval($_GET['any_linkid']) > 0) {
        $query_filters['linkid'] = $_GET['any_linkid'];
    }
}

if(isset($_GET['linktext_find']) && strlen($_GET['linktext_find']) > 0){
    $query_filters['LOWER(linktext) LIKE'] = '%'.$_GET['linktext_find'].'%';
}


if(isset($_GET['start_range']) && is_valid_date($_GET['start_range'])){
    $query_filters['linktime >='] = $_GET['start_range'].( strlen($_GET['start_range']) <= 10 ? ' 00:00:00' : '' );
}
if(isset($_GET['end_range']) && is_valid_date($_GET['end_range'])){
    $query_filters['linktime <='] = $_GET['end_range'].( strlen($_GET['end_range']) <= 10 ? ' 23:59:59' : '' );
}








//Fetch unique transaction types recorded so far:
$ini_filter = array();
foreach($query_filters as $key => $value){
    $ini_filter[$key] = $value;
}



//Make sure its a valid type considering other filters:
if(isset($_GET['linktype'])){

    if (substr_count($_GET['linktype'], ',') > 0) {
        //This is multiple:
        $query_filters['linktype IN (' . $_GET['linktype'] . ')'] = null;
    } elseif (intval($_GET['linktype']) > 0) {
        $query_filters['linktype'] = intval($_GET['linktype']);
    }

}

$has_filters = ( count($_GET) > 0 );

$e___11035 = $this->config->item('e___11035'); //Encyclopedia

?>

<script>

    var x_filters = '<?= serialize(count($query_filters) > 0 ? $query_filters : array()) ?>';
    var x_joined_by = '<?= serialize(count($joined_by) > 0 ? $joined_by : array()) ?>';
    var linktext_find = '<?= ( isset($_GET['linktext_find']) && strlen($_GET['linktext_find']) > 0 ? $_GET['linktext_find'] : '' ) ?>';
    var linktext_replace = '<?= ( isset($_GET['linktext_replace']) && strlen($_GET['linktext_replace']) > 0 ? $_GET['linktext_replace'] : '' ) ?>';

    $(document).ready(function () {

        //Load first page of transactions:
        x_4341(x_filters, x_joined_by, 1);

    });


    function x_4341(x_filters, x_joined_by, page_num){

        //Show spinner:
        $('#x_page_'+page_num).html('<div class="main__title center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>' + js_view__shuffle_message(12694) +  '</div>').hide().fadeIn();

        //Load report based on input fields:
        $.post("/app/x_4341", {
            x_filters: x_filters,
            x_joined_by: x_joined_by,
            linktext_find:linktext_find,
            linktext_replace:linktext_replace,
            page_num: page_num,
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {
            if (!data.status) {
                //Show Error:
                $('#x_page_'+page_num).html(data.message);
            } else {
                //Load Report:
                $('#x_page_'+page_num).html(data.message);
                activate_popover();
            }
        });

    }

</script>

<?php

if(superpower_unlocked(12701)){
    echo '<div class="inline-block margin-top-down" style="padding-left:7px;"><span class="icon-block">'.$e___11035[12707]['m__cover'].'</span><a href="javascript:void();" onclick="$(\'.show-filter\').toggleClass(\'hidden\');" class="main__title">'.$e___11035[12707]['m__title'].'</a></div>';
}


echo '<div class="show-filter '.( $has_filters && 0 ? '' : 'hidden' ).'">';
echo '<form action="" method="GET">';







echo '<table class="table table-sm maxout"><tr>';

//ANY IDEA
echo '<td><div>';
echo '<span class="mini-header">ANY IDEA:</span>';
echo '<input type="text" name="ideahashtag" value="' . ( $input_i ? $_GET['ideahashtag'] : '' ) . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">IDEA PREVIOUS:</span><input type="text" name="linkleft" value="' . ((isset($_GET['linkleft'])) ? $_GET['linkleft'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">IDEA NEXT:</span><input type="text" name="linkright" value="' . ((isset($_GET['linkright'])) ? $_GET['linkright'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';







echo '<table class="table table-sm maxout"><tr>';

//ANY SOURCE
echo '<td><div>';
echo '<span class="mini-header">ANY SOURCE:</span>';
echo '<input type="text" name="playerhandle" value="' . ( $input_e ? $_GET['playerhandle'] : '' ) . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">SOURCE CREATOR:</span><input type="text" name="linkplayer" value="' . ((isset($_GET['linkplayer'])) ? $_GET['linkplayer'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">SOURCE PROFILE:</span><input type="text" name="linkup" value="' . ((isset($_GET['linkup'])) ? $_GET['linkup'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">SOURCE followers:</span><input type="text" name="linkdown" value="' . ((isset($_GET['linkdown'])) ? $_GET['linkdown'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';





echo '<table class="table table-sm maxout"><tr>';

//ANY DISCOVERY
echo '<td><div>';
echo '<span class="mini-header">ANY TRANSACTION:</span>';
echo '<input type="text" name="any_linkid" value="' . ((isset($_GET['any_linkid'])) ? $_GET['any_linkid'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">TRANSACTION ID:</span><input type="text" name="linkid" value="' . ((isset($_GET['linkid'])) ? $_GET['linkid'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';






echo '<table class="table table-sm maxout"><tr>';


//Search
echo '<td><div>';
echo '<span class="mini-header">TRANSACTION MESSAGE SEARCH:</span>';
echo '<input type="text" name="linktext_find" value="' . ((isset($_GET['linktext_find'])) ? $_GET['linktext_find'] : '') . '" class="form-control border">';
echo '</div></td>';

if(isset($_GET['linktext_find']) && strlen($_GET['linktext_find']) > 0 && superpower_unlocked(12701)){
    //Give Option to Replace:
    echo '<td><div>';
    echo '<span class="mini-header">TRANSACTION MESSAGE REPLACE:</span>';
    echo '<input type="text" name="linktext_replace" value="' . ((isset($_GET['linktext_replace'])) ? $_GET['linktext_replace'] : '') . '" class="form-control border">';
    echo '</div></td>';
}



//DISCOVERY Type Filter Groups
echo '<td></td>';




//Filters UI:
echo '<table class="table table-sm maxout"><tr>';

echo '<td valign="top" style="vertical-align: top;"><div>';
echo '<span class="mini-header">START DATE:</span>';
echo '<input type="date" class="form-control border" name="start_range" value="'.( isset($_GET['start_range']) ? $_GET['start_range'] : '' ).'">';
echo '</div></td>';

echo '<td valign="top" style="vertical-align: top;"><div>';
echo '<span class="mini-header">END DATE:</span>';
echo '<input type="date" class="form-control border" name="end_range" value="'.( isset($_GET['end_range']) ? $_GET['end_range'] : '' ).'">';
echo '</div></td>';



echo '<td>';
echo '<div>';
echo '<span class="mini-header">TRANSACTION TYPE:</span>';

if(isset($_GET['linktype']) && substr_count($_GET['linktype'], ',')>0){

    //We have multiple predefined transaction types, so we must use a text input:
    echo '<input type="text" name="linktype" value="' . $_GET['linktype'] . '" class="form-control border">';

} else {

    echo '<select class="form-control border" name="linktype" id="linktype" class="border" style="width: 100% !important;">';

    if(isset($_GET['linkplayer'])) {

        //Fetch details for this member:
        $all_x_count = 0;
        $select_ui = '';
        foreach($this->Mench_ledger->fetch($ini_filter, array('linktype'), 0, 0, sort__e(), 'COUNT(linktype) as total_count, playertext, linktype', 'linktype, playertext') as $x) {
            //Echo drop down:
            $select_ui .= '<option value="' . $x['linktype'] . '" ' . ((isset($_GET['linktype']) && $_GET['linktype']==$x['linktype']) ? 'selected="selected"' : '') . '>' . $x['playertext'] . ' ('  . number_format($x['total_count'], 0) . ')</option>';
            $all_x_count += $x['total_count'];
        }

        //Now that we know the total show:
        echo '<option value="0">All ('  . number_format($all_x_count, 0) . ')</option>';
        echo $select_ui;

    } else {

        //Load all fast:
        echo '<option value="0">ALL TRANSACTION TYPES</option>';
        foreach($this->config->item('e___4593') /* DISCOVERY Types */ as $playerid => $m){
            //Echo drop down:
            echo '<option value="' . $playerid . '" ' . ((isset($_GET['linktype']) && $_GET['linktype']==$playerid) ? 'selected="selected"' : '') . '>' . $m['m__title'] . '</option>';
        }

    }

    echo '</select>';


}

echo '</div>';

echo '</td>';

echo '</tr></table>';




echo '</tr></table>';




echo '<input type="submit" class="btn" value="Apply" />';

if($has_filters){
    echo ' &nbsp;<a href="'.view__app_link(4341).'" style="font-size: 0.8em;">Remove Filters</a>';
}

echo '</form>';
echo '</div>';


//AJAX Would load content here:
echo '<div id="x_page_1"></div>';
