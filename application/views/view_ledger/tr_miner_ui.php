<?php

$tr_filters = array(
    'tr_en_type_id' => 'Link Types',
    'tr_id' => 'Transaction ID',
    'e_en_id' => 'Entity ID',
    'tr_in_child_id' => 'Intent ID',
);

$match_columns = array();
foreach ($tr_filters as $key => $value) {
    if (isset($_GET[$key])) {
        if ($key == 'e_en_id') {
            //We need to look for both inititors and recipients:
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $match_columns['( tr_en_child_id IN (' . $_GET[$key] . ') OR tr_en_parent_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $match_columns['( tr_en_child_id = ' . $_GET[$key] . ' OR tr_en_parent_id = ' . $_GET[$key] . ')'] = null;
            }
        } else {
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $match_columns[$key . ' IN (' . $_GET[$key] . ')'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $match_columns[$key] = intval($_GET[$key]);
            }
        }
    }
}

//Fetch transactions with possible filters:
$trs = $this->Database_model->tr_fetch($match_columns, array('en_type'), (fn___is_dev() ? 20 : 100));

?>

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
//Display filters:
echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-atlas"></i> Platform Transactions</h5>';


echo '<form action="" method="GET">';
echo '<table class="table table-condensed"><tr>';
foreach ($tr_filters as $key => $value) {
    echo '<td><div style="padding-right:5px;">';
    if ($key == 'tr_en_type_id') { //We have a list to show:

        //Fetch all unique transaction types that have been logged on the ledger so far:
        $all_engs = $this->Database_model->tr_fetch(array(
            'tr_status >=' => 0,
            'en_status >=' => 0,
        ), array('en_type'), 0, 0, array('trs_count' => 'DESC'), 'COUNT(tr_en_type_id) as trs_count, en_name, tr_en_type_id', 'tr_en_type_id, en_name');

        echo '<select name="' . $key . '" class="border" style="width:160px;">';
        echo '<option value="0">' . $value . '</option>';
        foreach ($all_engs as $tr) {
            echo '<option value="' . $tr['in_id'] . '" ' . ((isset($_GET[$key]) && $_GET[$key] == $tr['in_id']) ? 'selected="selected"' : '') . '>' . $tr['en_name'] . ' ('  . $tr['trs_count'] . ')</option>';
        }
        echo '</select>';

    } else {
        //show text input
        echo '<input type="text" name="' . $key . '" placeholder="' . $value . '" value="' . ((isset($_GET[$key])) ? $_GET[$key] : '') . '" class="form-control border">';
    }
    echo '</div></td>';
}
echo '<td><input type="submit" class="btn btn-sm btn-primary" value="Apply" /></td>';
echo '</tr></table>';
echo '</form>';


//Fetch objects
echo '<div class="list-group list-grey maxout">';
foreach ($trs as $e) {
    echo fn___echo_tr($e);
}
echo '</div>';

?>