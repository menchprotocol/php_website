<?php

//Construct filters based on GET variables:
$query_filters = array();
$joined_by = array();

//We have a special OR filter when combined with e__handle & i__hashtag
$input_e = ( isset($_GET['e__handle']) && strlen($_GET['e__handle']) > 0 );
$focus_e = false;
$input_i = ( isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag']) > 0 );
$focus_i = false;

if($input_e){
    foreach($this->Source_cache->fetch(array(
        'LOWER(e__handle)' => strtolower($_GET['e__handle']),
    )) as $e_found){
        $focus_e = $e_found;
        $_GET['e__handle'] = $e_found['e__handle'];
    }
    if(!$focus_e){
        //Invalid input!
        $input_e = false;
    }
}

if($input_i){
    foreach($this->Idea_cache->fetch(array(
        'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
    )) as $i_found){
        $focus_i = $i_found;
        $_GET['i__hashtag'] = $i_found['i__hashtag'];
    }
    if(!$focus_i){
        //Invalid input!
        $input_i = false;
    }
}

$any_i_e_set = $input_i || $input_e;
$followings_tr_filter = ( isset($_GET['link_reference']) && $_GET['link_reference'] > 0 ? ' OR link_reference = '.$_GET['link_reference'].' ' : false );


if(isset($_GET['link_privacy']) && strlen($_GET['link_privacy']) > 0){
    if (substr_count($_GET['link_privacy'], ',') > 0) {
        //This is multiple:
        $query_filters['( link_privacy IN (' . $_GET['link_privacy'] . '))'] = null;
    } else {
        $query_filters['link_privacy'] = intval($_GET['link_privacy']);
    }
}

if(isset($_GET['link_player']) && strlen($_GET['link_player']) > 0){
    if (substr_count($_GET['link_player'], ',') > 0) {
        //This is multiple:
        $query_filters['( link_player IN (' . $_GET['link_player'] . '))'] = null;
    } elseif (intval($_GET['link_player']) > 0) {
        $query_filters['link_player'] = $_GET['link_player'];
    }
}


if(isset($_GET['link_up']) && strlen($_GET['link_up']) > 0){
    if (substr_count($_GET['link_up'], ',') > 0) {
        //This is multiple:
        $query_filters['( link_up IN (' . $_GET['link_up'] . '))'] = null;
    } elseif (intval($_GET['link_up']) > 0) {
        $query_filters['link_up'] = $_GET['link_up'];
    }
}

if(isset($_GET['link_down']) && strlen($_GET['link_down']) > 0){
    if (substr_count($_GET['link_down'], ',') > 0) {
        //This is multiple:
        $query_filters['( link_down IN (' . $_GET['link_down'] . '))'] = null;
    } elseif (intval($_GET['link_down']) > 0) {
        $query_filters['link_down'] = $_GET['link_down'];
    }
}

if(isset($_GET['link_left']) && strlen($_GET['link_left']) > 0){
    if (substr_count($_GET['link_left'], ',') > 0) {
        //This is multiple:
        $query_filters['( link_left IN (' . $_GET['link_left'] . '))'] = null;
    } elseif (intval($_GET['link_left']) > 0) {
        $query_filters['link_left'] = $_GET['link_left'];
    }
}

if(isset($_GET['link_right']) && strlen($_GET['link_right']) > 0){
    if (substr_count($_GET['link_right'], ',') > 0) {
        //This is multiple:
        $query_filters['( link_right IN (' . $_GET['link_right'] . '))'] = null;
    } elseif (intval($_GET['link_right']) > 0) {
        $query_filters['link_right'] = $_GET['link_right'];
    }
}

if(isset($_GET['link_reference']) && strlen($_GET['link_reference']) > 0 && !$any_i_e_set){
    if (substr_count($_GET['link_reference'], ',') > 0) {
        //This is multiple:
        $query_filters['( link_reference IN (' . $_GET['link_reference'] . '))'] = null;
    } elseif (intval($_GET['link_reference']) > 0) {
        $query_filters['link_reference'] = $_GET['link_reference'];
    }
}

if(isset($_GET['link_id']) && strlen($_GET['link_id']) > 0){
    if (substr_count($_GET['link_id'], ',') > 0) {
        //This is multiple:
        $query_filters['( link_id IN (' . $_GET['link_id'] . '))'] = null;
    } elseif (intval($_GET['link_id']) > 0) {
        $query_filters['link_id'] = $_GET['link_id'];
    }
}

if($input_e){
    //We need to look for both following/follower
    $query_filters['( link_down = ' . $focus_e['e__id'] . ' OR link_up = ' . $focus_e['e__id'] . ' OR link_player = ' . $focus_e['e__id'] . $followings_tr_filter . ' )'] = null;
}


if($input_i){
    //We need to look for both following/follower
    $query_filters['( link_right = ' . $focus_i['i__id'] . ' OR link_left = ' . $focus_i['i__id'] . $followings_tr_filter . ')'] = null;

}

if(isset($_GET['any_link_id']) && strlen($_GET['any_link_id']) > 0){
    //We need to look for both following/follower
    if (substr_count($_GET['any_link_id'], ',') > 0) {
        //This is multiple:
        $query_filters['( link_id IN (' . $_GET['any_link_id'] . ') OR link_reference IN (' . $_GET['any_link_id'] . '))'] = null;
    } elseif (intval($_GET['any_link_id']) > 0) {
        $query_filters['( link_id = ' . $_GET['any_link_id'] . ' OR link_reference = ' . $_GET['any_link_id'] . ')'] = null;
    }
}

if(isset($_GET['link_text_find']) && strlen($_GET['link_text_find']) > 0){
    $query_filters['LOWER(link_text) LIKE'] = '%'.$_GET['link_text_find'].'%';
}


if(isset($_GET['start_range']) && is_valid_date($_GET['start_range'])){
    $query_filters['link_time >='] = $_GET['start_range'].( strlen($_GET['start_range']) <= 10 ? ' 00:00:00' : '' );
}
if(isset($_GET['end_range']) && is_valid_date($_GET['end_range'])){
    $query_filters['link_time <='] = $_GET['end_range'].( strlen($_GET['end_range']) <= 10 ? ' 23:59:59' : '' );
}








//Fetch unique transaction types recorded so far:
$ini_filter = array();
foreach($query_filters as $key => $value){
    if(!includes_any($key, array('i__type', 'e__privacy'))){
        $ini_filter[$key] = $value;
    }
}



//Make sure its a valid type considering other filters:
if(isset($_GET['link_type'])){

    if (substr_count($_GET['link_type'], ',') > 0) {
        //This is multiple:
        $query_filters['link_type IN (' . $_GET['link_type'] . ')'] = null;
    } elseif (intval($_GET['link_type']) > 0) {
        $query_filters['link_type'] = intval($_GET['link_type']);
    }

}

$has_filters = ( count($_GET) > 0 );

$e___11035 = $this->config->item('e___11035'); //Encyclopedia

?>

<script>

    var x_filters = '<?= serialize(count($query_filters) > 0 ? $query_filters : array()) ?>';
    var x_joined_by = '<?= serialize(count($joined_by) > 0 ? $joined_by : array()) ?>';
    var link_text_find = '<?= ( isset($_GET['link_text_find']) && strlen($_GET['link_text_find']) > 0 ? $_GET['link_text_find'] : '' ) ?>';
    var link_text_replace = '<?= ( isset($_GET['link_text_replace']) && strlen($_GET['link_text_replace']) > 0 ? $_GET['link_text_replace'] : '' ) ?>';

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
            link_text_find:link_text_find,
            link_text_replace:link_text_replace,
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
echo '<input type="text" name="i__hashtag" value="' . ( $input_i ? $_GET['i__hashtag'] : '' ) . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">IDEA PREVIOUS:</span><input type="text" name="link_left" value="' . ((isset($_GET['link_left'])) ? $_GET['link_left'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">IDEA NEXT:</span><input type="text" name="link_right" value="' . ((isset($_GET['link_right'])) ? $_GET['link_right'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';







echo '<table class="table table-sm maxout"><tr>';

//ANY SOURCE
echo '<td><div>';
echo '<span class="mini-header">ANY SOURCE:</span>';
echo '<input type="text" name="e__handle" value="' . ( $input_e ? $_GET['e__handle'] : '' ) . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">SOURCE CREATOR:</span><input type="text" name="link_player" value="' . ((isset($_GET['link_player'])) ? $_GET['link_player'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">SOURCE PROFILE:</span><input type="text" name="link_up" value="' . ((isset($_GET['link_up'])) ? $_GET['link_up'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">SOURCE followers:</span><input type="text" name="link_down" value="' . ((isset($_GET['link_down'])) ? $_GET['link_down'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';





echo '<table class="table table-sm maxout"><tr>';

//ANY DISCOVERY
echo '<td><div>';
echo '<span class="mini-header">ANY TRANSACTION:</span>';
echo '<input type="text" name="any_link_id" value="' . ((isset($_GET['any_link_id'])) ? $_GET['any_link_id'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">TRANSACTION ID:</span><input type="text" name="link_id" value="' . ((isset($_GET['link_id'])) ? $_GET['link_id'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">PARENT TRANSACTION:</span><input type="text" name="link_reference" value="' . ((isset($_GET['link_reference'])) ? $_GET['link_reference'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">Interaction Privacy:</span><input type="text" name="link_privacy" value="' . ((isset($_GET['link_privacy'])) ? $_GET['link_privacy'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';






echo '<table class="table table-sm maxout"><tr>';


//Search
echo '<td><div>';
echo '<span class="mini-header">TRANSACTION MESSAGE SEARCH:</span>';
echo '<input type="text" name="link_text_find" value="' . ((isset($_GET['link_text_find'])) ? $_GET['link_text_find'] : '') . '" class="form-control border">';
echo '</div></td>';

if(isset($_GET['link_text_find']) && strlen($_GET['link_text_find']) > 0 && superpower_unlocked(12701)){
    //Give Option to Replace:
    echo '<td><div>';
    echo '<span class="mini-header">TRANSACTION MESSAGE REPLACE:</span>';
    echo '<input type="text" name="link_text_replace" value="' . ((isset($_GET['link_text_replace'])) ? $_GET['link_text_replace'] : '') . '" class="form-control border">';
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

if(isset($_GET['link_type']) && substr_count($_GET['link_type'], ',')>0){

    //We have multiple predefined transaction types, so we must use a text input:
    echo '<input type="text" name="link_type" value="' . $_GET['link_type'] . '" class="form-control border">';

} else {

    echo '<select class="form-control border" name="link_type" id="link_type" class="border" style="width: 100% !important;">';

    if(isset($_GET['link_player'])) {

        //Fetch details for this member:
        $all_x_count = 0;
        $select_ui = '';
        foreach($this->Mench_ledger->fetch($ini_filter, array('link_type'), 0, 0, sort__e(), 'COUNT(link_type) as total_count, e__title, link_type', 'link_type, e__title') as $x) {
            //Echo drop down:
            $select_ui .= '<option value="' . $x['link_type'] . '" ' . ((isset($_GET['link_type']) && $_GET['link_type']==$x['link_type']) ? 'selected="selected"' : '') . '>' . $x['e__title'] . ' ('  . number_format($x['total_count'], 0) . ')</option>';
            $all_x_count += $x['total_count'];
        }

        //Now that we know the total show:
        echo '<option value="0">All ('  . number_format($all_x_count, 0) . ')</option>';
        echo $select_ui;

    } else {

        //Load all fast:
        echo '<option value="0">ALL TRANSACTION TYPES</option>';
        foreach($this->config->item('e___4593') /* DISCOVERY Types */ as $e__id => $m){
            //Echo drop down:
            echo '<option value="' . $e__id . '" ' . ((isset($_GET['link_type']) && $_GET['link_type']==$e__id) ? 'selected="selected"' : '') . '>' . $m['m__title'] . '</option>';
        }

    }

    echo '</select>';


}

echo '</div>';

//Optional IDEA/Source Privacy filter ONLY IF DISCOVERY Type = Create New IDEA/SOURCE

echo '<div class="filter-statuses filter-in-status hidden"><span class="mini-header">Source Reference(es)</span><input type="text" name="i__type" value="' . ((isset($_GET['i__type'])) ? $_GET['i__type'] : '') . '" class="form-control border"></div>';

echo '<div class="filter-statuses e_privacy_filter hidden"><span class="mini-header">Source Privacy(es)</span><input type="text" name="e__privacy" value="' . ((isset($_GET['e__privacy'])) ? $_GET['e__privacy'] : '') . '" class="form-control border"></div>';

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
