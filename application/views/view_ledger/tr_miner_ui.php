<style>
    table, tr, td, th {
        text-align: left !important;
        font-size: 14px;
        cursor: default !important;
        line-height: 120% !important;
    }

    th {
        font-weight: bold !important;
    }

    td {
        padding: 5px 0 !important;
    }
</style>

<?php

$tr_filters = array(
    'tr_en_id' => 'Entity IDs',
    'tr_in_id' => 'Intent IDs',
    'tr_id' => 'Transaction IDs',
    'tr_en_type_id' => 'Link Types',
);

//Construct filters based on GET variables:
$filters = array();
foreach ($tr_filters as $key => $value) {
    if (isset($_GET[$key])) {
        if ($key == 'tr_en_id') {
            //We need to look for both parent/child
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_en_child_id IN (' . $_GET[$key] . ') OR tr_en_parent_id IN (' . $_GET[$key] . ') OR tr_en_credit_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['( tr_en_child_id = ' . $_GET[$key] . ' OR tr_en_parent_id = ' . $_GET[$key] . ' OR tr_en_credit_id = ' . $_GET[$key] . ')'] = null;
            }
        } elseif ($key == 'tr_in_id') {
            //We need to look for both parent/child
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_in_child_id IN (' . $_GET[$key] . ') OR tr_in_parent_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['( tr_in_child_id = ' . $_GET[$key] . ' OR tr_in_parent_id = ' . $_GET[$key] . ')'] = null;
            }
        } elseif ($key == 'tr_id') {
            //We need to look for both parent/child
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_id IN (' . $_GET[$key] . ') OR tr_tr_parent_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['( tr_id = ' . $_GET[$key] . ' OR tr_tr_parent_id = ' . $_GET[$key] . ')'] = null;
            }
        } elseif ($key == 'tr_en_type_id' && $_GET[$key] > 0) {
            $filters[$key] = intval($_GET[$key]);
        }
    }
}

//Also apply possible time range filters:
if(isset($_GET['start_range']) && fn___isDate($_GET['start_range'])){
    $filters['tr_timestamp >='] = $_GET['start_range'].' 00:00:00';
}
if(isset($_GET['end_range']) && fn___isDate($_GET['end_range'])){
    $filters['tr_timestamp <='] = $_GET['end_range'].' 23:59:59';
}

//Fetch transactions:
$trs = $this->Database_model->tr_fetch($filters, array('en_type'), 100);





//Display filters:
echo '<h5 class="badge badge-h"><i class="fas fa-filter"></i> Filters</h5>';

echo '<form action="" method="GET">';
echo '<table class="table table-condensed"><tr>';

//Give Date Limiters:
echo '<td><div style="padding-right:5px;">';
echo '<input type="date" class="form-control border" name="start_range" data-toggle="tooltip" data-placement="top" title="Transaction start range (Including this date)" value="'.( isset($_GET['start_range']) ? $_GET['start_range'] : '' ).'">';
echo '</div></td>';

echo '<td><div style="padding-right:5px;">';
echo '<input type="date" class="form-control border" name="end_range" data-toggle="tooltip" data-placement="top" title="Transaction end range (Including this date)" value="'.( isset($_GET['end_range']) ? $_GET['end_range'] : '' ).'">';
echo '</div></td>';

$all_transaction_count = 0;
foreach ($tr_filters as $key => $value) {
    echo '<td><div style="padding-right:5px;">';
    if ($key == 'tr_en_type_id') {

        //Fetch unique transaction types recorded so far:
        unset($filters['tr_en_type_id']); //So we show all transaction types
        $all_engs = $this->Database_model->tr_fetch($filters, array('en_type'), 0, 0, array('trs_count' => 'DESC'), 'COUNT(tr_en_type_id) as trs_count, en_name, tr_en_type_id', 'tr_en_type_id, en_name');

        //Give option to select:
        $select_ui = '';
        foreach ($all_engs as $tr) {
            $select_ui .= '<option value="' . $tr['tr_en_type_id'] . '" ' . ((isset($_GET[$key]) && $_GET[$key] == $tr['tr_en_type_id']) ? 'selected="selected"' : '') . '>' . $tr['en_name'] . ' ('  . fn___echo_number($tr['trs_count']) . ')</option>';
            $all_transaction_count += $tr['trs_count'];
        }

        //Echo Transaction filters:
        echo '<select class="form-control border" name="' . $key . '" class="border" data-toggle="tooltip" data-placement="top" title="Transaction Types" style="width:160px;">';
        echo '<option value="0">All Transactions ('  . fn___echo_number($all_transaction_count) . ')</option>';
        echo $select_ui;
        echo '</select>';

    } else {
        //show text input
        echo '<input type="text" name="' . $key . '" data-toggle="tooltip" data-placement="top" title="Filter by '.$value.' (Add multiple w/ comma: 1,2,3)" placeholder="' . $value . '" value="' . ((isset($_GET[$key])) ? $_GET[$key] : '') . '" class="form-control border">';
    }
    echo '</div></td>';
}
echo '<td><input type="submit" class="btn btn-sm btn-primary" value="Apply" /></td>';
echo '</tr></table>';
echo '</form>';


//Display Transactions:
echo '<h5 class="badge badge-h"><i class="fas fa-atlas"></i> '.count($trs).'/'.fn___echo_number($all_transaction_count).' Transactions</h5>';
if(count($trs)>0){
    echo '<div class="list-group list-grey maxout">';
    foreach ($trs as $tr) {
        echo fn___echo_tr_row($tr);
    }
    echo '</div>';
} else {
    //Show no transaction warning:
    echo '<div class="alert alert-info" role="alert" style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> No Transactions found with the selected filters. Modify filters and try again.</div>';
}


?>