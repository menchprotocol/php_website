<?php

//Construct filters based on GET variables:
$filters = array();
$joined_by = array();

//We have a special OR filter when combined with any_source__id & any_idea__id
$any_in_en_set = ( ( isset($_GET['any_source__id']) && $_GET['any_source__id'] > 0 ) || ( isset($_GET['any_idea__id']) && $_GET['any_idea__id'] > 0 ) );
$parent_tr_filter = ( isset($_GET['read__reference']) && $_GET['read__reference'] > 0 ? ' OR read__reference = '.$_GET['read__reference'].' ' : false );



//Apply filters:
if(isset($_GET['idea__status']) && strlen($_GET['idea__status']) > 0){
    if(isset($_GET['read__type']) && $_GET['read__type']==4250){ //IDEA created
        //Filter idea status based on
        $joined_by = array('idea_next');

        if (substr_count($_GET['idea__status'], ',') > 0) {
            //This is multiple:
            $filters['( idea__status IN (' . $_GET['idea__status'] . '))'] = null;
        } else {
            $filters['idea__status'] = intval($_GET['idea__status']);
        }
    } else {
        unset($_GET['idea__status']);
    }
}



if(isset($_GET['idea__type']) && strlen($_GET['idea__type']) > 0){
    if(isset($_GET['read__type']) && $_GET['read__type']==4250){ //IDEA created
        //Filter idea status based on
        $joined_by = array('idea_next');
        if (substr_count($_GET['idea__type'], ',') > 0) {
            //This is multiple:
            $filters['( idea__type IN (' . $_GET['idea__type'] . '))'] = null;
        } else {
            $filters['idea__type'] = intval($_GET['idea__type']);
        }
    } else {
        unset($_GET['idea__type']);
    }
}

if(isset($_GET['source__status']) && strlen($_GET['source__status']) > 0){
    if(isset($_GET['read__type']) && $_GET['read__type']==4251){ //SOURCE Created

        //Filter idea status based on
        $joined_by = array('source_portfolio');

        if (substr_count($_GET['source__status'], ',') > 0) {
            //This is multiple:
            $filters['( source__status IN (' . $_GET['source__status'] . '))'] = null;
        } else {
            $filters['source__status'] = intval($_GET['source__status']);
        }
    } else {
        unset($_GET['source__status']);
    }
}

if(isset($_GET['read__status']) && strlen($_GET['read__status']) > 0){
    if (substr_count($_GET['read__status'], ',') > 0) {
        //This is multiple:
        $filters['( read__status IN (' . $_GET['read__status'] . '))'] = null;
    } else {
        $filters['read__status'] = intval($_GET['read__status']);
    }
}

if(isset($_GET['read__source']) && strlen($_GET['read__source']) > 0){
    if (substr_count($_GET['read__source'], ',') > 0) {
        //This is multiple:
        $filters['( read__source IN (' . $_GET['read__source'] . '))'] = null;
    } elseif (intval($_GET['read__source']) > 0) {
        $filters['read__source'] = $_GET['read__source'];
    }
}


if(isset($_GET['read__up']) && strlen($_GET['read__up']) > 0){
    if (substr_count($_GET['read__up'], ',') > 0) {
        //This is multiple:
        $filters['( read__up IN (' . $_GET['read__up'] . '))'] = null;
    } elseif (intval($_GET['read__up']) > 0) {
        $filters['read__up'] = $_GET['read__up'];
    }
}

if(isset($_GET['read__down']) && strlen($_GET['read__down']) > 0){
    if (substr_count($_GET['read__down'], ',') > 0) {
        //This is multiple:
        $filters['( read__down IN (' . $_GET['read__down'] . '))'] = null;
    } elseif (intval($_GET['read__down']) > 0) {
        $filters['read__down'] = $_GET['read__down'];
    }
}

if(isset($_GET['read__left']) && strlen($_GET['read__left']) > 0){
    if (substr_count($_GET['read__left'], ',') > 0) {
        //This is multiple:
        $filters['( read__left IN (' . $_GET['read__left'] . '))'] = null;
    } elseif (intval($_GET['read__left']) > 0) {
        $filters['read__left'] = $_GET['read__left'];
    }
}

if(isset($_GET['read__right']) && strlen($_GET['read__right']) > 0){
    if (substr_count($_GET['read__right'], ',') > 0) {
        //This is multiple:
        $filters['( read__right IN (' . $_GET['read__right'] . '))'] = null;
    } elseif (intval($_GET['read__right']) > 0) {
        $filters['read__right'] = $_GET['read__right'];
    }
}

if(isset($_GET['read__reference']) && strlen($_GET['read__reference']) > 0 && !$any_in_en_set){
    if (substr_count($_GET['read__reference'], ',') > 0) {
        //This is multiple:
        $filters['( read__reference IN (' . $_GET['read__reference'] . '))'] = null;
    } elseif (intval($_GET['read__reference']) > 0) {
        $filters['read__reference'] = $_GET['read__reference'];
    }
}

if(isset($_GET['read__id']) && strlen($_GET['read__id']) > 0){
    if (substr_count($_GET['read__id'], ',') > 0) {
        //This is multiple:
        $filters['( read__id IN (' . $_GET['read__id'] . '))'] = null;
    } elseif (intval($_GET['read__id']) > 0) {
        $filters['read__id'] = $_GET['read__id'];
    }
}

if(isset($_GET['any_source__id']) && strlen($_GET['any_source__id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_source__id'], ',') > 0) {
        //This is multiple:
        $filters['( read__down IN (' . $_GET['any_source__id'] . ') OR read__up IN (' . $_GET['any_source__id'] . ') OR read__source IN (' . $_GET['any_source__id'] . ') ' . $parent_tr_filter . ' )'] = null;
    } elseif (intval($_GET['any_source__id']) > 0) {
        $filters['( read__down = ' . $_GET['any_source__id'] . ' OR read__up = ' . $_GET['any_source__id'] . ' OR read__source = ' . $_GET['any_source__id'] . $parent_tr_filter . ' )'] = null;
    }
}

if(isset($_GET['any_idea__id']) && strlen($_GET['any_idea__id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_idea__id'], ',') > 0) {
        //This is multiple:
        $filters['( read__right IN (' . $_GET['any_idea__id'] . ') OR read__left IN (' . $_GET['any_idea__id'] . ') ' . $parent_tr_filter . ' )'] = null;
    } elseif (intval($_GET['any_idea__id']) > 0) {
        $filters['( read__right = ' . $_GET['any_idea__id'] . ' OR read__left = ' . $_GET['any_idea__id'] . $parent_tr_filter . ')'] = null;
    }
}

if(isset($_GET['any_read__id']) && strlen($_GET['any_read__id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_read__id'], ',') > 0) {
        //This is multiple:
        $filters['( read__id IN (' . $_GET['any_read__id'] . ') OR read__reference IN (' . $_GET['any_read__id'] . '))'] = null;
    } elseif (intval($_GET['any_read__id']) > 0) {
        $filters['( read__id = ' . $_GET['any_read__id'] . ' OR read__reference = ' . $_GET['any_read__id'] . ')'] = null;
    }
}

if(isset($_GET['read__message_search']) && strlen($_GET['read__message_search']) > 0){
    $filters['LOWER(read__message) LIKE'] = '%'.$_GET['read__message_search'].'%';
}


if(isset($_GET['start_range']) && is_valid_date($_GET['start_range'])){
    $filters['read__time >='] = $_GET['start_range'].( strlen($_GET['start_range']) <= 10 ? ' 00:00:00' : '' );
}
if(isset($_GET['end_range']) && is_valid_date($_GET['end_range'])){
    $filters['read__time <='] = $_GET['end_range'].( strlen($_GET['end_range']) <= 10 ? ' 23:59:59' : '' );
}








//Fetch unique link types recorded so far:
$ini_filter = array();
foreach($filters as $key => $value){
    if(!includes_any($key, array('idea__status', 'idea__type', 'source__status'))){
        $ini_filter[$key] = $value;
    }
}



//Make sure its a valid type considering other filters:
if(isset($_GET['read__type'])){

    if (substr_count($_GET['read__type'], ',') > 0) {
        //This is multiple:
        $filters['read__type IN (' . $_GET['read__type'] . ')'] = null;
    } elseif (intval($_GET['read__type']) > 0) {
        $filters['read__type'] = intval($_GET['read__type']);
    }

}

$has_filters = ( count($_GET) > 0 );

$sources__2738 = $this->config->item('sources__2738');
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION

?>

<script>
    var link_filters = '<?= serialize(count($filters) > 0 ? $filters : array()) ?>';
    var link_joined_by = '<?= serialize(count($joined_by) > 0 ? $joined_by : array()) ?>';
    var read__message_search = '<?= ( isset($_GET['read__message_search']) && strlen($_GET['read__message_search']) > 0 ? $_GET['read__message_search'] : '' ) ?>';
    var read__message_replace = '<?= ( isset($_GET['read__message_replace']) && strlen($_GET['read__message_replace']) > 0 ? $_GET['read__message_replace'] : '' ) ?>';
</script>
<script src="/application/views/read/read_interactions.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>

<?php

echo '<div class="container">';

    echo '<h1 class="'.extract_icon_color($sources__11035[4341]['m_icon']).' inline-block"><span class="icon-block">'.$sources__11035[4341]['m_icon'].'</span>'.$sources__11035[4341]['m_name'].'</h1>';

    echo '<div class="inline-block '.superpower_active(12701).'" style="padding-left:7px;"><span class="icon-block">'.$sources__11035[12707]['m_icon'].'</span><a href="javascript:void();" onclick="$(\'.show-filter\').toggleClass(\'hidden\');" class="montserrat">'.$sources__11035[12707]['m_name'].'</a></div>';


    echo '<div class="inline-box show-filter '.( $has_filters && 0 ? '' : 'hidden' ).'">';
    echo '<form action="" method="GET">';







    echo '<table class="table table-sm maxout"><tr>';

    //ANY IDEA
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">ANY IDEA:</span>';
    echo '<input type="text" name="any_idea__id" value="' . ((isset($_GET['any_idea__id'])) ? $_GET['any_idea__id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">IDEA PREVIOUS:</span><input type="text" name="read__left" value="' . ((isset($_GET['read__left'])) ? $_GET['read__left'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">IDEA NEXT:</span><input type="text" name="read__right" value="' . ((isset($_GET['read__right'])) ? $_GET['read__right'] : '') . '" class="form-control border"></td>';

    echo '</tr></table>';







    echo '<table class="table table-sm maxout"><tr>';

    //ANY SOURCE
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">ANY SOURCE:</span>';
    echo '<input type="text" name="any_source__id" value="' . ((isset($_GET['any_source__id'])) ? $_GET['any_source__id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">SOURCE CREATOR:</span><input type="text" name="read__source" value="' . ((isset($_GET['read__source'])) ? $_GET['read__source'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">SOURCE PROFILE:</span><input type="text" name="read__up" value="' . ((isset($_GET['read__up'])) ? $_GET['read__up'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">SOURCE PORTFOLIO:</span><input type="text" name="read__down" value="' . ((isset($_GET['read__down'])) ? $_GET['read__down'] : '') . '" class="form-control border"></td>';

    echo '</tr></table>';





    echo '<table class="table table-sm maxout"><tr>';

    //ANY READ
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">ANY READ:</span>';
    echo '<input type="text" name="any_read__id" value="' . ((isset($_GET['any_read__id'])) ? $_GET['any_read__id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">READ ID:</span><input type="text" name="read__id" value="' . ((isset($_GET['read__id'])) ? $_GET['read__id'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">PARENT READ:</span><input type="text" name="read__reference" value="' . ((isset($_GET['read__reference'])) ? $_GET['read__reference'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">READ STATUS:</span><input type="text" name="read__status" value="' . ((isset($_GET['read__status'])) ? $_GET['read__status'] : '') . '" class="form-control border"></td>';

    echo '</tr></table>';






    echo '<table class="table table-sm maxout"><tr>';


    //Search
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">READ MESSAGE SEARCH:</span>';
    echo '<input type="text" name="read__message_search" value="' . ((isset($_GET['read__message_search'])) ? $_GET['read__message_search'] : '') . '" class="form-control border">';
    echo '</div></td>';

    if(isset($_GET['read__message_search']) && strlen($_GET['read__message_search']) > 0){
        //Give Option to Replace:
        echo '<td class="' . superpower_active(12705) . '"><div style="padding-right:5px;">';
        echo '<span class="mini-header">READ MESSAGE REPLACE:</span>';
        echo '<input type="text" name="read__message_replace" value="' . ((isset($_GET['read__message_replace'])) ? $_GET['read__message_replace'] : '') . '" class="form-control border">';
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

    if(isset($_GET['read__type']) && substr_count($_GET['read__type'], ',')>0){

        //We have multiple predefined link types, so we must use a text input:
        echo '<input type="text" name="read__type" value="' . $_GET['read__type'] . '" class="form-control border">';

    } else {

        echo '<select class="form-control border" name="read__type" id="read__type" class="border" style="width: 100% !important;">';

        if(isset($_GET['read__source'])) {

            //Fetch details for this user:
            $all_link_count = 0;
            $select_ui = '';
            foreach($this->READ_model->fetch($ini_filter, array('en_type'), 0, 0, array('source__title' => 'ASC'), 'COUNT(read__type) as total_count, source__title, read__type', 'read__type, source__title') as $ln) {
                //Echo drop down:
                $select_ui .= '<option value="' . $ln['read__type'] . '" ' . ((isset($_GET['read__type']) && $_GET['read__type'] == $ln['read__type']) ? 'selected="selected"' : '') . '>' . $ln['source__title'] . ' ('  . number_format($ln['total_count'], 0) . ')</option>';
                $all_link_count += $ln['total_count'];
            }

            //Now that we know the total show:
            echo '<option value="0">All ('  . number_format($all_link_count, 0) . ')</option>';
            echo $select_ui;

        } else {

            //Load all fast:
            echo '<option value="0">ALL READ TYPES</option>';
            foreach($this->config->item('sources__4593') /* READ Types */ as $source__id => $m){
                //Echo drop down:
                echo '<option value="' . $source__id . '" ' . ((isset($_GET['read__type']) && $_GET['read__type'] == $source__id) ? 'selected="selected"' : '') . '>' . $m['m_name'] . '</option>';
            }

        }

        echo '</select>';


    }

    echo '</div>';

    //Optional IDEA/SOURCE status filter ONLY IF READ Type = Create New IDEA/SOURCE

echo '<div class="filter-statuses filter-in-status hidden"><span class="mini-header">IDEA Status(es)</span><input type="text" name="idea__status" value="' . ((isset($_GET['idea__status'])) ? $_GET['idea__status'] : '') . '" class="form-control border"></div>';

    echo '<div class="filter-statuses filter-in-status hidden"><span class="mini-header">IDEA Type(s)</span><input type="text" name="idea__type" value="' . ((isset($_GET['idea__type'])) ? $_GET['idea__type'] : '') . '" class="form-control border"></div>';

    echo '<div class="filter-statuses filter-en-status hidden"><span class="mini-header">SOURCE Status(es)</span><input type="text" name="source__status" value="' . ((isset($_GET['source__status'])) ? $_GET['source__status'] : '') . '" class="form-control border"></div>';

    echo '</td>';

    echo '</tr></table>';




    echo '</tr></table>';




    echo '<input type="submit" class="btn btn-read" value="Apply" />';

    if($has_filters){
        echo ' &nbsp;<a href="/read/interactions" style="font-size: 0.8em;">Remove Filters</a>';
    }

    echo '</form>';
    echo '</div>';


    //AJAX Would load content here:
    echo '<div id="link_page_1"></div>';
echo '</div>';
