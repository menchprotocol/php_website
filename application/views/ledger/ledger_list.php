<?php

//Construct filters based on GET variables:
$filters = array();
$join_by = array();

//We have a special OR filter when combined with any_en_id & any_in_id
$any_in_en_set = ( ( isset($_GET['any_en_id']) && $_GET['any_en_id'] > 0 ) || ( isset($_GET['any_in_id']) && $_GET['any_in_id'] > 0 ) );
$parent_tr_filter = ( isset($_GET['ln_parent_transaction_id']) && $_GET['ln_parent_transaction_id'] > 0 ? ' OR ln_parent_transaction_id = '.$_GET['ln_parent_transaction_id'].' ' : false );



//Apply filters:
if(isset($_GET['in_status_source_id']) && strlen($_GET['in_status_source_id']) > 0){
    if(isset($_GET['ln_type_source_id']) && $_GET['ln_type_source_id']==4250){ //TREE created
        //Filter tree status based on
        $join_by = array('in_child');

        if (substr_count($_GET['in_status_source_id'], ',') > 0) {
            //This is multiple:
            $filters['( in_status_source_id IN (' . $_GET['in_status_source_id'] . '))'] = null;
        } else {
            $filters['in_status_source_id'] = intval($_GET['in_status_source_id']);
        }
    } else {
        unset($_GET['in_status_source_id']);
    }
}



if(isset($_GET['in_type_source_id']) && strlen($_GET['in_type_source_id']) > 0){
    if(isset($_GET['ln_type_source_id']) && $_GET['ln_type_source_id']==4250){ //TREE created
        //Filter tree status based on
        $join_by = array('in_child');
        if (substr_count($_GET['in_type_source_id'], ',') > 0) {
            //This is multiple:
            $filters['( in_type_source_id IN (' . $_GET['in_type_source_id'] . '))'] = null;
        } else {
            $filters['in_type_source_id'] = intval($_GET['in_type_source_id']);
        }
    } else {
        unset($_GET['in_type_source_id']);
    }
}

if(isset($_GET['en_status_source_id']) && strlen($_GET['en_status_source_id']) > 0){
    if(isset($_GET['ln_type_source_id']) && $_GET['ln_type_source_id']==4251){ //SOURCE Created

        //Filter tree status based on
        $join_by = array('en_child');

        if (substr_count($_GET['en_status_source_id'], ',') > 0) {
            //This is multiple:
            $filters['( en_status_source_id IN (' . $_GET['en_status_source_id'] . '))'] = null;
        } else {
            $filters['en_status_source_id'] = intval($_GET['en_status_source_id']);
        }
    } else {
        unset($_GET['en_status_source_id']);
    }
}

if(isset($_GET['ln_status_source_id']) && strlen($_GET['ln_status_source_id']) > 0){
    if (substr_count($_GET['ln_status_source_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_status_source_id IN (' . $_GET['ln_status_source_id'] . '))'] = null;
    } else {
        $filters['ln_status_source_id'] = intval($_GET['ln_status_source_id']);
    }
}

if(isset($_GET['ln_creator_source_id']) && strlen($_GET['ln_creator_source_id']) > 0){
    if (substr_count($_GET['ln_creator_source_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_creator_source_id IN (' . $_GET['ln_creator_source_id'] . '))'] = null;
    } elseif (intval($_GET['ln_creator_source_id']) > 0) {
        $filters['ln_creator_source_id'] = $_GET['ln_creator_source_id'];
    }
}


if(isset($_GET['ln_parent_source_id']) && strlen($_GET['ln_parent_source_id']) > 0){
    if (substr_count($_GET['ln_parent_source_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_parent_source_id IN (' . $_GET['ln_parent_source_id'] . '))'] = null;
    } elseif (intval($_GET['ln_parent_source_id']) > 0) {
        $filters['ln_parent_source_id'] = $_GET['ln_parent_source_id'];
    }
}

if(isset($_GET['ln_child_source_id']) && strlen($_GET['ln_child_source_id']) > 0){
    if (substr_count($_GET['ln_child_source_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_child_source_id IN (' . $_GET['ln_child_source_id'] . '))'] = null;
    } elseif (intval($_GET['ln_child_source_id']) > 0) {
        $filters['ln_child_source_id'] = $_GET['ln_child_source_id'];
    }
}

if(isset($_GET['ln_previous_tree_id']) && strlen($_GET['ln_previous_tree_id']) > 0){
    if (substr_count($_GET['ln_previous_tree_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_previous_tree_id IN (' . $_GET['ln_previous_tree_id'] . '))'] = null;
    } elseif (intval($_GET['ln_previous_tree_id']) > 0) {
        $filters['ln_previous_tree_id'] = $_GET['ln_previous_tree_id'];
    }
}

if(isset($_GET['ln_next_tree_id']) && strlen($_GET['ln_next_tree_id']) > 0){
    if (substr_count($_GET['ln_next_tree_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_next_tree_id IN (' . $_GET['ln_next_tree_id'] . '))'] = null;
    } elseif (intval($_GET['ln_next_tree_id']) > 0) {
        $filters['ln_next_tree_id'] = $_GET['ln_next_tree_id'];
    }
}

if(isset($_GET['ln_parent_transaction_id']) && strlen($_GET['ln_parent_transaction_id']) > 0 && !$any_in_en_set){
    if (substr_count($_GET['ln_parent_transaction_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_parent_transaction_id IN (' . $_GET['ln_parent_transaction_id'] . '))'] = null;
    } elseif (intval($_GET['ln_parent_transaction_id']) > 0) {
        $filters['ln_parent_transaction_id'] = $_GET['ln_parent_transaction_id'];
    }
}

if(isset($_GET['ln_id']) && strlen($_GET['ln_id']) > 0){
    if (substr_count($_GET['ln_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_id IN (' . $_GET['ln_id'] . '))'] = null;
    } elseif (intval($_GET['ln_id']) > 0) {
        $filters['ln_id'] = $_GET['ln_id'];
    }
}

if(isset($_GET['any_en_id']) && strlen($_GET['any_en_id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_en_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_child_source_id IN (' . $_GET['any_en_id'] . ') OR ln_parent_source_id IN (' . $_GET['any_en_id'] . ') OR ln_creator_source_id IN (' . $_GET['any_en_id'] . ') ' . $parent_tr_filter . ' )'] = null;
    } elseif (intval($_GET['any_en_id']) > 0) {
        $filters['( ln_child_source_id = ' . $_GET['any_en_id'] . ' OR ln_parent_source_id = ' . $_GET['any_en_id'] . ' OR ln_creator_source_id = ' . $_GET['any_en_id'] . $parent_tr_filter . ' )'] = null;
    }
}

if(isset($_GET['any_in_id']) && strlen($_GET['any_in_id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_in_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_next_tree_id IN (' . $_GET['any_in_id'] . ') OR ln_previous_tree_id IN (' . $_GET['any_in_id'] . ') ' . $parent_tr_filter . ' )'] = null;
    } elseif (intval($_GET['any_in_id']) > 0) {
        $filters['( ln_next_tree_id = ' . $_GET['any_in_id'] . ' OR ln_previous_tree_id = ' . $_GET['any_in_id'] . $parent_tr_filter . ')'] = null;
    }
}

if(isset($_GET['any_ln_id']) && strlen($_GET['any_ln_id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_ln_id'], ',') > 0) {
        //This is multiple:
        $filters['( ln_id IN (' . $_GET['any_ln_id'] . ') OR ln_parent_transaction_id IN (' . $_GET['any_ln_id'] . '))'] = null;
    } elseif (intval($_GET['any_ln_id']) > 0) {
        $filters['( ln_id = ' . $_GET['any_ln_id'] . ' OR ln_parent_transaction_id = ' . $_GET['any_ln_id'] . ')'] = null;
    }
}

if(isset($_GET['ln_content_search']) && strlen($_GET['ln_content_search']) > 0){
    $filters['LOWER(ln_content) LIKE'] = '%'.$_GET['ln_content_search'].'%';
}


if(isset($_GET['start_range']) && is_valid_date($_GET['start_range'])){
    $filters['ln_timestamp >='] = $_GET['start_range'].( strlen($_GET['start_range']) <= 10 ? ' 00:00:00' : '' );
}
if(isset($_GET['end_range']) && is_valid_date($_GET['end_range'])){
    $filters['ln_timestamp <='] = $_GET['end_range'].( strlen($_GET['end_range']) <= 10 ? ' 23:59:59' : '' );
}








//Fetch unique link types recorded so far:
$ini_filter = array();
foreach($filters as $key => $value){
    if(!includes_any($key, array('in_status_source_id', 'in_type_source_id', 'en_status_source_id'))){
        $ini_filter[$key] = $value;
    }
}



//Make sure its a valid type considering other filters:
if(isset($_GET['ln_type_source_id'])){

    if (substr_count($_GET['ln_type_source_id'], ',') > 0) {
        //This is multiple:
        $filters['ln_type_source_id IN (' . $_GET['ln_type_source_id'] . ')'] = null;
    } elseif (intval($_GET['ln_type_source_id']) > 0) {
        $filters['ln_type_source_id'] = intval($_GET['ln_type_source_id']);
    }

}

$has_filters = ( count($_GET) > 0 );

$en_all_2738 = $this->config->item('en_all_2738');
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH  NAVIGATION

?>

<script>
    var link_filters = '<?= serialize(count($filters) > 0 ? $filters : array()) ?>';
    var link_join_by = '<?= serialize(count($join_by) > 0 ? $join_by : array()) ?>';
    var ln_content_search = '<?= ( isset($_GET['ln_content_search']) && strlen($_GET['ln_content_search']) > 0 ? $_GET['ln_content_search'] : '' ) ?>';
    var ln_content_replace = '<?= ( isset($_GET['ln_content_replace']) && strlen($_GET['ln_content_replace']) > 0 ? $_GET['ln_content_replace'] : '' ) ?>';
</script>
<script src="/application/views/ledger/ledger_list.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>

<?php

echo '<div class="container">';

    echo '<h1 class="'.extract_icon_color($en_all_11035[4341]['m_icon']).' inline-block"><span class="icon-block">'.$en_all_11035[4341]['m_icon'].'</span>'.$en_all_11035[4341]['m_name'].'</h1>';

    echo '<div class="inline-block '.superpower_active(10988).'" style="padding-left:7px;"><i class="far fa-filter"></i><a href="javascript:void();" onclick="$(\'.show-filter\').toggleClass(\'hidden\');" class="montserrat">FILTER</a></div>';


    echo '<div class="inline-box show-filter '.( $has_filters && 0 ? '' : 'hidden' ).'">';
    echo '<form action="" method="GET">';







    echo '<table class="table table-sm maxout"><tr>';

    //ANY TREE
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">ANY TREE:</span>';
    echo '<input type="text" name="any_in_id" value="' . ((isset($_GET['any_in_id'])) ? $_GET['any_in_id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">TREE PREVIOUS:</span><input type="text" name="ln_previous_tree_id" value="' . ((isset($_GET['ln_previous_tree_id'])) ? $_GET['ln_previous_tree_id'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">TREE NEXT:</span><input type="text" name="ln_next_tree_id" value="' . ((isset($_GET['ln_next_tree_id'])) ? $_GET['ln_next_tree_id'] : '') . '" class="form-control border"></td>';

    echo '</tr></table>';







    echo '<table class="table table-sm maxout"><tr>';

    //ANY SOURCE
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">ANY SOURCE:</span>';
    echo '<input type="text" name="any_en_id" value="' . ((isset($_GET['any_en_id'])) ? $_GET['any_en_id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">SOURCE CREATOR:</span><input type="text" name="ln_creator_source_id" value="' . ((isset($_GET['ln_creator_source_id'])) ? $_GET['ln_creator_source_id'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">SOURCE PROFILE:</span><input type="text" name="ln_parent_source_id" value="' . ((isset($_GET['ln_parent_source_id'])) ? $_GET['ln_parent_source_id'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">SOURCE PORTFOLIO:</span><input type="text" name="ln_child_source_id" value="' . ((isset($_GET['ln_child_source_id'])) ? $_GET['ln_child_source_id'] : '') . '" class="form-control border"></td>';

    echo '</tr></table>';





    echo '<table class="table table-sm maxout"><tr>';

    //ANY READ
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">ANY READ:</span>';
    echo '<input type="text" name="any_ln_id" value="' . ((isset($_GET['any_ln_id'])) ? $_GET['any_ln_id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">READ:</span><input type="text" name="ln_id" value="' . ((isset($_GET['ln_id'])) ? $_GET['ln_id'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">PARENT READ:</span><input type="text" name="ln_parent_transaction_id" value="' . ((isset($_GET['ln_parent_transaction_id'])) ? $_GET['ln_parent_transaction_id'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">READ STATUS:</span><input type="text" name="ln_status_source_id" value="' . ((isset($_GET['ln_status_source_id'])) ? $_GET['ln_status_source_id'] : '') . '" class="form-control border"></td>';

    echo '</tr></table>';






    echo '<table class="table table-sm maxout"><tr>';


    //Search
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">READ CONTENT SEARCH:</span>';
    echo '<input type="text" name="ln_content_search" value="' . ((isset($_GET['ln_content_search'])) ? $_GET['ln_content_search'] : '') . '" class="form-control border">';
    echo '</div></td>';

    if(isset($_GET['ln_content_search']) && strlen($_GET['ln_content_search']) > 0){
        //Give Option to Replace:
        echo '<td class="' . superpower_active(10985) . '"><div style="padding-right:5px;">';
        echo '<span class="mini-header">READ CONTENT REPLACE:</span>';
        echo '<input type="text" name="ln_content_replace" value="' . ((isset($_GET['ln_content_replace'])) ? $_GET['ln_content_replace'] : '') . '" class="form-control border">';
        echo '</div></td>';
    }



    //READ Type Filter Groups
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
    echo '<span class="mini-header">READ TYPE:</span>';

    if(isset($_GET['ln_type_source_id']) && substr_count($_GET['ln_type_source_id'], ',')>0){

        //We have multiple predefined link types, so we must use a text input:
        echo '<input type="text" name="ln_type_source_id" value="' . $_GET['ln_type_source_id'] . '" class="form-control border">';

    } else {

        echo '<select class="form-control border" name="ln_type_source_id" id="ln_type_source_id" class="border" style="width: 100% !important;">';

        if(isset($_GET['ln_creator_source_id'])) {

            //Fetch details for this user:
            $all_link_count = 0;
            $select_ui = '';
            foreach ($this->READ_model->ln_fetch($ini_filter, array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(ln_type_source_id) as total_count, en_name, ln_type_source_id', 'ln_type_source_id, en_name') as $ln) {
                //Echo drop down:
                $select_ui .= '<option value="' . $ln['ln_type_source_id'] . '" ' . ((isset($_GET['ln_type_source_id']) && $_GET['ln_type_source_id'] == $ln['ln_type_source_id']) ? 'selected="selected"' : '') . '>' . $ln['en_name'] . ' ('  . number_format($ln['total_count'], 0) . ')</option>';
                $all_link_count += $ln['total_count'];
            }

            //Now that we know the total show:
            echo '<option value="0">All ('  . number_format($all_link_count, 0) . ')</option>';
            echo $select_ui;

        } else {

            //Load all fast:
            echo '<option value="0">All READ Types</option>';
            foreach($this->config->item('en_all_4593') /* READ Types */ as $en_id => $m){
                //Echo drop down:
                echo '<option value="' . $en_id . '" ' . ((isset($_GET['ln_type_source_id']) && $_GET['ln_type_source_id'] == $en_id) ? 'selected="selected"' : '') . '>' . $m['m_name'] . '</option>';
            }

        }

        echo '</select>';


    }

    echo '</div>';

    //Optional TREE/SOURCE status filter ONLY IF READ Type = Create New TREE/SOURCE

echo '<div class="filter-statuses filter-in-status hidden"><span class="mini-header">TREE Status(es)</span><input type="text" name="in_status_source_id" value="' . ((isset($_GET['in_status_source_id'])) ? $_GET['in_status_source_id'] : '') . '" class="form-control border"></div>';

    echo '<div class="filter-statuses filter-in-status hidden"><span class="mini-header">TREE Type(s)</span><input type="text" name="in_type_source_id" value="' . ((isset($_GET['in_type_source_id'])) ? $_GET['in_type_source_id'] : '') . '" class="form-control border"></div>';

    echo '<div class="filter-statuses filter-en-status hidden"><span class="mini-header">SOURCE Status(es)</span><input type="text" name="en_status_source_id" value="' . ((isset($_GET['en_status_source_id'])) ? $_GET['en_status_source_id'] : '') . '" class="form-control border"></div>';

    echo '</td>';

    echo '</tr></table>';




    echo '</tr></table>';




    echo '<input type="submit" class="btn btn-read" value="Apply" />';

    if($has_filters){
        echo ' &nbsp;<a href="/ledger" style="font-size: 0.8em;">Remove Filters</a>';
    }

    echo '</form>';
    echo '</div>';


    //AJAX Would load content here:
    echo '<div id="link_page_1"></div>';
echo '</div>';


?>