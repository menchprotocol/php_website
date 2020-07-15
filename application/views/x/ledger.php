<?php

//Construct filters based on GET variables:
$filters = array();
$joined_by = array();

//We have a special OR filter when combined with any_e__id & any_i__id
$any_i_e_set = ( ( isset($_GET['any_e__id']) && $_GET['any_e__id'] > 0 ) || ( isset($_GET['any_i__id']) && $_GET['any_i__id'] > 0 ) );
$parent_tr_filter = ( isset($_GET['x__reference']) && $_GET['x__reference'] > 0 ? ' OR x__reference = '.$_GET['x__reference'].' ' : false );



//Apply filters:
if(isset($_GET['i__status']) && strlen($_GET['i__status']) > 0){
    if(isset($_GET['x__type']) && $_GET['x__type']==4250){ //IDEA created
        //Filter idea status based on
        $joined_by = array('x__right');

        if (substr_count($_GET['i__status'], ',') > 0) {
            //This is multiple:
            $filters['( i__status IN (' . $_GET['i__status'] . '))'] = null;
        } else {
            $filters['i__status'] = intval($_GET['i__status']);
        }
    } else {
        unset($_GET['i__status']);
    }
}



if(isset($_GET['i__type']) && strlen($_GET['i__type']) > 0){
    if(isset($_GET['x__type']) && $_GET['x__type']==4250){ //IDEA created
        //Filter idea status based on
        $joined_by = array('x__right');
        if (substr_count($_GET['i__type'], ',') > 0) {
            //This is multiple:
            $filters['( i__type IN (' . $_GET['i__type'] . '))'] = null;
        } else {
            $filters['i__type'] = intval($_GET['i__type']);
        }
    } else {
        unset($_GET['i__type']);
    }
}

if(isset($_GET['e__status']) && strlen($_GET['e__status']) > 0){
    if(isset($_GET['x__type']) && $_GET['x__type']==4251){ //SOURCE Created

        //Filter idea status based on
        $joined_by = array('x__down');

        if (substr_count($_GET['e__status'], ',') > 0) {
            //This is multiple:
            $filters['( e__status IN (' . $_GET['e__status'] . '))'] = null;
        } else {
            $filters['e__status'] = intval($_GET['e__status']);
        }
    } else {
        unset($_GET['e__status']);
    }
}

if(isset($_GET['x__status']) && strlen($_GET['x__status']) > 0){
    if (substr_count($_GET['x__status'], ',') > 0) {
        //This is multiple:
        $filters['( x__status IN (' . $_GET['x__status'] . '))'] = null;
    } else {
        $filters['x__status'] = intval($_GET['x__status']);
    }
}

if(isset($_GET['x__source']) && strlen($_GET['x__source']) > 0){
    if (substr_count($_GET['x__source'], ',') > 0) {
        //This is multiple:
        $filters['( x__source IN (' . $_GET['x__source'] . '))'] = null;
    } elseif (intval($_GET['x__source']) > 0) {
        $filters['x__source'] = $_GET['x__source'];
    }
}


if(isset($_GET['x__up']) && strlen($_GET['x__up']) > 0){
    if (substr_count($_GET['x__up'], ',') > 0) {
        //This is multiple:
        $filters['( x__up IN (' . $_GET['x__up'] . '))'] = null;
    } elseif (intval($_GET['x__up']) > 0) {
        $filters['x__up'] = $_GET['x__up'];
    }
}

if(isset($_GET['x__down']) && strlen($_GET['x__down']) > 0){
    if (substr_count($_GET['x__down'], ',') > 0) {
        //This is multiple:
        $filters['( x__down IN (' . $_GET['x__down'] . '))'] = null;
    } elseif (intval($_GET['x__down']) > 0) {
        $filters['x__down'] = $_GET['x__down'];
    }
}

if(isset($_GET['x__left']) && strlen($_GET['x__left']) > 0){
    if (substr_count($_GET['x__left'], ',') > 0) {
        //This is multiple:
        $filters['( x__left IN (' . $_GET['x__left'] . '))'] = null;
    } elseif (intval($_GET['x__left']) > 0) {
        $filters['x__left'] = $_GET['x__left'];
    }
}

if(isset($_GET['x__right']) && strlen($_GET['x__right']) > 0){
    if (substr_count($_GET['x__right'], ',') > 0) {
        //This is multiple:
        $filters['( x__right IN (' . $_GET['x__right'] . '))'] = null;
    } elseif (intval($_GET['x__right']) > 0) {
        $filters['x__right'] = $_GET['x__right'];
    }
}

if(isset($_GET['x__reference']) && strlen($_GET['x__reference']) > 0 && !$any_i_e_set){
    if (substr_count($_GET['x__reference'], ',') > 0) {
        //This is multiple:
        $filters['( x__reference IN (' . $_GET['x__reference'] . '))'] = null;
    } elseif (intval($_GET['x__reference']) > 0) {
        $filters['x__reference'] = $_GET['x__reference'];
    }
}

if(isset($_GET['x__id']) && strlen($_GET['x__id']) > 0){
    if (substr_count($_GET['x__id'], ',') > 0) {
        //This is multiple:
        $filters['( x__id IN (' . $_GET['x__id'] . '))'] = null;
    } elseif (intval($_GET['x__id']) > 0) {
        $filters['x__id'] = $_GET['x__id'];
    }
}

if(isset($_GET['any_e__id']) && strlen($_GET['any_e__id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_e__id'], ',') > 0) {
        //This is multiple:
        $filters['( x__down IN (' . $_GET['any_e__id'] . ') OR x__up IN (' . $_GET['any_e__id'] . ') OR x__source IN (' . $_GET['any_e__id'] . ') ' . $parent_tr_filter . ' )'] = null;
    } elseif (intval($_GET['any_e__id']) > 0) {
        $filters['( x__down = ' . $_GET['any_e__id'] . ' OR x__up = ' . $_GET['any_e__id'] . ' OR x__source = ' . $_GET['any_e__id'] . $parent_tr_filter . ' )'] = null;
    }
}

if(isset($_GET['any_i__id']) && strlen($_GET['any_i__id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_i__id'], ',') > 0) {
        //This is multiple:
        $filters['( x__right IN (' . $_GET['any_i__id'] . ') OR x__left IN (' . $_GET['any_i__id'] . ') ' . $parent_tr_filter . ' )'] = null;
    } elseif (intval($_GET['any_i__id']) > 0) {
        $filters['( x__right = ' . $_GET['any_i__id'] . ' OR x__left = ' . $_GET['any_i__id'] . $parent_tr_filter . ')'] = null;
    }
}

if(isset($_GET['any_x__id']) && strlen($_GET['any_x__id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_x__id'], ',') > 0) {
        //This is multiple:
        $filters['( x__id IN (' . $_GET['any_x__id'] . ') OR x__reference IN (' . $_GET['any_x__id'] . '))'] = null;
    } elseif (intval($_GET['any_x__id']) > 0) {
        $filters['( x__id = ' . $_GET['any_x__id'] . ' OR x__reference = ' . $_GET['any_x__id'] . ')'] = null;
    }
}

if(isset($_GET['x__message_search']) && strlen($_GET['x__message_search']) > 0){
    $filters['LOWER(x__message) LIKE'] = '%'.$_GET['x__message_search'].'%';
}


if(isset($_GET['start_range']) && is_valid_date($_GET['start_range'])){
    $filters['x__time >='] = $_GET['start_range'].( strlen($_GET['start_range']) <= 10 ? ' 00:00:00' : '' );
}
if(isset($_GET['end_range']) && is_valid_date($_GET['end_range'])){
    $filters['x__time <='] = $_GET['end_range'].( strlen($_GET['end_range']) <= 10 ? ' 23:59:59' : '' );
}








//Fetch unique transaction types recorded so far:
$ini_filter = array();
foreach($filters as $key => $value){
    if(!includes_any($key, array('i__status', 'i__type', 'e__status'))){
        $ini_filter[$key] = $value;
    }
}



//Make sure its a valid type considering other filters:
if(isset($_GET['x__type'])){

    if (substr_count($_GET['x__type'], ',') > 0) {
        //This is multiple:
        $filters['x__type IN (' . $_GET['x__type'] . ')'] = null;
    } elseif (intval($_GET['x__type']) > 0) {
        $filters['x__type'] = intval($_GET['x__type']);
    }

}

$has_filters = ( count($_GET) > 0 );

$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION

?>

<script>
    var x_filters = '<?= serialize(count($filters) > 0 ? $filters : array()) ?>';
    var x_joined_by = '<?= serialize(count($joined_by) > 0 ? $joined_by : array()) ?>';
    var x__message_search = '<?= ( isset($_GET['x__message_search']) && strlen($_GET['x__message_search']) > 0 ? $_GET['x__message_search'] : '' ) ?>';
    var x__message_replace = '<?= ( isset($_GET['x__message_replace']) && strlen($_GET['x__message_replace']) > 0 ? $_GET['x__message_replace'] : '' ) ?>';
</script>
<script src="/application/views/x/ledger.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>

<?php

echo '<div class="container">';

    echo '<h1 class="'.extract_icon_color($e___11035[4341]['m_icon']).' inline-block"><span class="icon-block">'.$e___11035[4341]['m_icon'].'</span>'.$e___11035[4341]['m_name'].'</h1>';

    echo '<div class="inline-block margin-top-down '.superpower_active(12701).'" style="padding-left:7px;"><span class="icon-block">'.$e___11035[12707]['m_icon'].'</span><a href="javascript:void();" onclick="$(\'.show-filter\').toggleClass(\'hidden\');" class="montserrat">'.$e___11035[12707]['m_name'].'</a></div>';


    echo '<div class="inline-box show-filter '.( $has_filters && 0 ? '' : 'hidden' ).'">';
    echo '<form action="" method="GET">';







    echo '<table class="table table-sm maxout"><tr>';

    //ANY IDEA
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">ANY IDEA:</span>';
    echo '<input type="text" name="any_i__id" value="' . ((isset($_GET['any_i__id'])) ? $_GET['any_i__id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">IDEA PREVIOUS:</span><input type="text" name="x__left" value="' . ((isset($_GET['x__left'])) ? $_GET['x__left'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">IDEA NEXT:</span><input type="text" name="x__right" value="' . ((isset($_GET['x__right'])) ? $_GET['x__right'] : '') . '" class="form-control border"></td>';

    echo '</tr></table>';







    echo '<table class="table table-sm maxout"><tr>';

    //ANY SOURCE
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">ANY SOURCE:</span>';
    echo '<input type="text" name="any_e__id" value="' . ((isset($_GET['any_e__id'])) ? $_GET['any_e__id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">SOURCE CREATOR:</span><input type="text" name="x__source" value="' . ((isset($_GET['x__source'])) ? $_GET['x__source'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">SOURCE PROFILE:</span><input type="text" name="x__up" value="' . ((isset($_GET['x__up'])) ? $_GET['x__up'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">SOURCE PORTFOLIO:</span><input type="text" name="x__down" value="' . ((isset($_GET['x__down'])) ? $_GET['x__down'] : '') . '" class="form-control border"></td>';

    echo '</tr></table>';





    echo '<table class="table table-sm maxout"><tr>';

    //ANY DISCOVER
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">ANY TRANSACTION:</span>';
    echo '<input type="text" name="any_x__id" value="' . ((isset($_GET['any_x__id'])) ? $_GET['any_x__id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">TRANSACTION ID:</span><input type="text" name="x__id" value="' . ((isset($_GET['x__id'])) ? $_GET['x__id'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">PARENT TRANSACTION:</span><input type="text" name="x__reference" value="' . ((isset($_GET['x__reference'])) ? $_GET['x__reference'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">TRANSACTION STATUS:</span><input type="text" name="x__status" value="' . ((isset($_GET['x__status'])) ? $_GET['x__status'] : '') . '" class="form-control border"></td>';

    echo '</tr></table>';






    echo '<table class="table table-sm maxout"><tr>';


    //Search
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">TRANSACTION MESSAGE SEARCH:</span>';
    echo '<input type="text" name="x__message_search" value="' . ((isset($_GET['x__message_search'])) ? $_GET['x__message_search'] : '') . '" class="form-control border">';
    echo '</div></td>';

    if(isset($_GET['x__message_search']) && strlen($_GET['x__message_search']) > 0){
        //Give Option to Replace:
        echo '<td class="' . superpower_active(12705) . '"><div style="padding-right:5px;">';
        echo '<span class="mini-header">TRANSACTION MESSAGE REPLACE:</span>';
        echo '<input type="text" name="x__message_replace" value="' . ((isset($_GET['x__message_replace'])) ? $_GET['x__message_replace'] : '') . '" class="form-control border">';
        echo '</div></td>';
    }



    //DISCOVER Type Filter Groups
    echo '<td></td>';




//Filters UI:
echo '<table class="table table-sm maxout"><tr>';

echo '<td valign="top" style="vertical-align: top;"><div style="padding-right:5px;">';
echo '<span class="mini-header">START DATE:</span>';
echo '<input type="date" class="form-control border" name="start_range" value="'.( isset($_GET['start_range']) ? $_GET['start_range'] : '' ).'">';
echo '</div></td>';

echo '<td valign="top" style="vertical-align: top;"><div style="padding-right:5px;">';
echo '<span class="mini-header">END DATE:</span>';
echo '<input type="date" class="form-control border" name="end_range" value="'.( isset($_GET['end_range']) ? $_GET['end_range'] : '' ).'">';
echo '</div></td>';



    echo '<td>';
    echo '<div>';
    echo '<span class="mini-header">TRANSACTION TYPE:</span>';

    if(isset($_GET['x__type']) && substr_count($_GET['x__type'], ',')>0){

        //We have multiple predefined transaction types, so we must use a text input:
        echo '<input type="text" name="x__type" value="' . $_GET['x__type'] . '" class="form-control border">';

    } else {

        echo '<select class="form-control border" name="x__type" id="x__type" class="border" style="width: 100% !important;">';

        if(isset($_GET['x__source'])) {

            //Fetch details for this miner:
            $all_x_count = 0;
            $select_ui = '';
            foreach($this->X_model->fetch($ini_filter, array('x__type'), 0, 0, array('e__title' => 'ASC'), 'COUNT(x__type) as total_count, e__title, x__type', 'x__type, e__title') as $x) {
                //Echo drop down:
                $select_ui .= '<option value="' . $x['x__type'] . '" ' . ((isset($_GET['x__type']) && $_GET['x__type'] == $x['x__type']) ? 'selected="selected"' : '') . '>' . $x['e__title'] . ' ('  . number_format($x['total_count'], 0) . ')</option>';
                $all_x_count += $x['total_count'];
            }

            //Now that we know the total show:
            echo '<option value="0">All ('  . number_format($all_x_count, 0) . ')</option>';
            echo $select_ui;

        } else {

            //Load all fast:
            echo '<option value="0">ALL TRANSACTION TYPES</option>';
            foreach($this->config->item('e___4593') /* DISCOVER Types */ as $e__id => $m){
                //Echo drop down:
                echo '<option value="' . $e__id . '" ' . ((isset($_GET['x__type']) && $_GET['x__type'] == $e__id) ? 'selected="selected"' : '') . '>' . $m['m_name'] . '</option>';
            }

        }

        echo '</select>';


    }

    echo '</div>';

    //Optional IDEA/SOURCE status filter ONLY IF DISCOVER Type = Create New IDEA/SOURCE

echo '<div class="filter-statuses filter-in-status hidden"><span class="mini-header">IDEA Status(es)</span><input type="text" name="i__status" value="' . ((isset($_GET['i__status'])) ? $_GET['i__status'] : '') . '" class="form-control border"></div>';

    echo '<div class="filter-statuses filter-in-status hidden"><span class="mini-header">IDEA Type(s)</span><input type="text" name="i__type" value="' . ((isset($_GET['i__type'])) ? $_GET['i__type'] : '') . '" class="form-control border"></div>';

    echo '<div class="filter-statuses filter-en-status hidden"><span class="mini-header">SOURCE Status(es)</span><input type="text" name="e__status" value="' . ((isset($_GET['e__status'])) ? $_GET['e__status'] : '') . '" class="form-control border"></div>';

    echo '</td>';

    echo '</tr></table>';




    echo '</tr></table>';




    echo '<input type="submit" class="btn btn-x" value="Apply" />';

    if($has_filters){
        echo ' &nbsp;<a href="/ledger" style="font-size: 0.8em;">Remove Filters</a>';
    }

    echo '</form>';
    echo '</div>';


    //AJAX Would load content here:
    echo '<div id="x_page_1"></div>';
echo '</div>';
