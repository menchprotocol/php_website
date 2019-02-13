<?php

$primary_filters = array(
    'any_en_id' => 'Any Entity IDs',
    'any_in_id' => 'Any Intent IDs',
    'any_tr_id' => 'Any Transaction IDs',
    'tr_type_en_id' => 'Link Types',
);

$advanced_filters = array(
    'tr_miner_en_id' => 'Entity Miner IDs',
    'tr_en_parent_id' => 'Entity Parent IDs',
    'tr_en_child_id' => 'Entity Child IDs',
    'tr_in_parent_id' => 'Intent Parent IDs',
    'tr_in_child_id' => 'Intent Child IDs',
    'tr_tr_id' => 'Transaction Parent IDs',
    'tr_id' => 'Transaction IDs',
    'tr_coins_min' => 'Minimum Coins',
    'tr_coins_max' => 'Maximum Coins',
    'in_status' => 'Intent Statuses',
    'en_status' => 'Entity Statuses',
    'tr_status' => 'Transaction Statuses',
);

//Construct filters based on GET variables:
$filters = array();
$join_by = array();

//We have a special OR filter when combined with any_en_id & any_in_id
$any_in_en_set = ( ( isset($_GET['any_en_id']) && $_GET['any_en_id'] > 0 ) || ( isset($_GET['any_in_id']) && $_GET['any_in_id'] > 0 ) );
$parent_tr_filter = ( isset($_GET['tr_tr_id']) && $_GET['tr_tr_id'] > 0 ? ' OR tr_tr_id = '.$_GET['tr_tr_id'].' ' : false );

foreach (array_merge($primary_filters, $advanced_filters) as $key => $value) {
    if (isset($_GET[$key]) && strlen($_GET[$key]) > 0) {

        if ($key == 'in_status') {

            if(isset($_GET['tr_type_en_id']) && $_GET['tr_type_en_id']==4250){ //Intent created
                //Filter intent status based on
                $join_by = array('in_child');


                if (substr_count($_GET[$key], ',') > 0) {
                    //This is multiple IDs:
                    $filters['( in_status IN (' . $_GET[$key] . '))'] = null;
                } else {
                    $filters['in_status'] = intval($_GET[$key]);
                }
            } else {
                unset($_GET[$key]);
            }

        } elseif ($key == 'en_status') {

            if(isset($_GET['tr_type_en_id']) && $_GET['tr_type_en_id']==4251){ //Entity Created

                //Filter intent status based on
                $join_by = array('en_child');

                if (substr_count($_GET[$key], ',') > 0) {
                    //This is multiple IDs:
                    $filters['( en_status IN (' . $_GET[$key] . '))'] = null;
                } else {
                    $filters['en_status'] = intval($_GET[$key]);
                }
            } else {
                unset($_GET[$key]);
            }

        } elseif ($key == 'tr_status') {


            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_status IN (' . $_GET[$key] . '))'] = null;
            } else {
                $filters['tr_status'] = intval($_GET[$key]);
            }

        } elseif ($key == 'tr_miner_en_id') {
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_miner_en_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['tr_miner_en_id'] = $_GET[$key];
            }

        } elseif ($key == 'tr_coins_min' && doubleval($_GET[$key]) > 0) {

            $filters['tr_coins >='] = doubleval($_GET[$key]);

        } elseif ($key == 'tr_coins_max' && doubleval($_GET[$key]) > 0) {

            $filters['tr_coins <='] = doubleval($_GET[$key]);

        } elseif ($key == 'tr_en_parent_id') {
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_en_parent_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['tr_en_parent_id'] = $_GET[$key];
            }
        } elseif ($key == 'tr_en_child_id') {
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_en_child_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['tr_en_child_id'] = $_GET[$key];
            }
        } elseif ($key == 'tr_in_parent_id') {
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_in_parent_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['tr_in_parent_id'] = $_GET[$key];
            }
        } elseif ($key == 'tr_in_child_id') {
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_in_child_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['tr_in_child_id'] = $_GET[$key];
            }
        } elseif ($key == 'tr_tr_id' && !$any_in_en_set) {
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_tr_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['tr_tr_id'] = $_GET[$key];
            }
        } elseif ($key == 'tr_id') {
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['tr_id'] = $_GET[$key];
            }
        } elseif ($key == 'any_en_id') {

            //We need to look for both parent/child
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_en_child_id IN (' . $_GET[$key] . ') OR tr_en_parent_id IN (' . $_GET[$key] . ') OR tr_miner_en_id IN (' . $_GET[$key] . ') ' . $parent_tr_filter . ' )'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['( tr_en_child_id = ' . $_GET[$key] . ' OR tr_en_parent_id = ' . $_GET[$key] . ' OR tr_miner_en_id = ' . $_GET[$key] . $parent_tr_filter . ' )'] = null;
            }
        } elseif ($key == 'any_in_id') {
            //We need to look for both parent/child
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_in_child_id IN (' . $_GET[$key] . ') OR tr_in_parent_id IN (' . $_GET[$key] . ') ' . $parent_tr_filter . ' )'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['( tr_in_child_id = ' . $_GET[$key] . ' OR tr_in_parent_id = ' . $_GET[$key] . $parent_tr_filter . ')'] = null;
            }
        } elseif ($key == 'any_tr_id') {
            //We need to look for both parent/child
            if (substr_count($_GET[$key], ',') > 0) {
                //This is multiple IDs:
                $filters['( tr_id IN (' . $_GET[$key] . ') OR tr_tr_id IN (' . $_GET[$key] . '))'] = null;
            } elseif (intval($_GET[$key]) > 0) {
                $filters['( tr_id = ' . $_GET[$key] . ' OR tr_tr_id = ' . $_GET[$key] . ')'] = null;
            }
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


//Fetch unique transaction types recorded so far:
$ini_filter = $filters;
unset($ini_filter['in_status']);
unset($ini_filter['en_status']);
$all_engs = $this->Database_model->fn___tr_fetch($ini_filter, array('en_type'), 0, 0, array('en_name' => 'DESC'), 'COUNT(tr_type_en_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, tr_type_en_id', 'tr_type_en_id, en_name');

//Makre sure its a valid type considering other filters:
if(isset($_GET['tr_type_en_id'])){

    $found = false;
    foreach ($all_engs as $tr) {
        if($_GET['tr_type_en_id'] == $tr['tr_type_en_id']){
            $found = true;
            break;
        }
    }

    if(!$found){
        unset($_GET['tr_type_en_id']);
    } else {
        //Assign filter:
        $filters['tr_type_en_id'] = intval($_GET['tr_type_en_id']);
    }

}

//Fetch transactions:
$trs = $this->Database_model->fn___tr_fetch($filters, $join_by, (fn___is_dev() ? 50 : 200));
$trs_count = $this->Database_model->fn___tr_fetch($filters, $join_by, 0, 0, array(), 'COUNT(tr_id) as trs_count, SUM(tr_coins) as coins_sum');





//Display filters:
echo '<h5 class="badge badge-h"><i class="fas fa-filter"></i> Filters</h5>';



echo '<form action="" method="GET">';

if(count($advanced_filters) > 0){
    //button to show:
    echo '<a href="javascript:void();" onclick="$(\'.advance-filter\').removeClass(\'hidden\');$(this).hide();"><i class="fal fa-plus-circle"></i> Advance Filters</a>';
}

//Draw advance filters:
echo '<table class="table table-condensed advance-filter hidden"><tr>';
foreach ($advanced_filters as $key => $value) {
    echo '<td><input type="text" name="' . $key . '" placeholder="' . $value . '" data-toggle="tooltip" data-placement="top" title="' . $value . '" value="' . ((isset($_GET[$key])) ? $_GET[$key] : '') . '" class="form-control border"></td>';
}
echo '</tr></table>';


echo '<table class="table table-condensed"><tr>';

//Give Date Limiters:
echo '<td><div style="padding-right:5px;">';

echo '<input type="date" class="form-control border" name="start_range" data-toggle="tooltip" data-placement="top" title="Transaction start range (Including this date)" value="'.( isset($_GET['start_range']) ? $_GET['start_range'] : '' ).'">';
echo '</div></td>';

echo '<td valign="top"><div style="padding-right:5px;">';
echo '<input type="date" class="form-control border" name="end_range" data-toggle="tooltip" data-placement="top" title="Transaction end range (Including this date)" value="'.( isset($_GET['end_range']) ? $_GET['end_range'] : '' ).'">';
echo '</div></td>';

$all_transaction_count = 0;
$all_coins = 0;
foreach ($primary_filters as $key => $value) {
    echo '<td><div style="padding-right:5px;">';
    if ($key == 'tr_type_en_id') {

        //Give option to select:
        $select_ui = '';
        foreach ($all_engs as $tr) {

            //Echo drop down:
            $select_ui .= '<option value="' . $tr['tr_type_en_id'] . '" ' . ((isset($_GET['tr_type_en_id']) && $_GET['tr_type_en_id'] == $tr['tr_type_en_id']) ? 'selected="selected"' : '') . '>' . $tr['en_name'] . ' ('  . fn___echo_number($tr['trs_count']) . ( $tr['coins_sum'] > 0 ? ', '.fn___echo_number($tr['coins_sum']).' Coins' : '' ) . ')</option>';
            $all_transaction_count += $tr['trs_count'];
            $all_coins += $tr['coins_sum'];
        }

        //Echo Transaction filters:
        echo '<select class="form-control border" name="tr_type_en_id" class="border" data-toggle="tooltip" data-placement="top" title="Transaction Types" style="width:160px;">';
        echo '<option value="0">All Transaction Types ('  . fn___echo_number($all_transaction_count) . ( $all_coins > 0 ? ', '.number_format($all_coins, 0).' Coins' : '' ) . ')</option>';
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


echo '<div class="row">';
    echo '<div class="col-md-7">';

        //Display Transactions:
        echo '<h3 style="margin-bottom:7px;"><i class="fas fa-atlas"></i> '.count($trs).' Recent transactions</h3>';

        echo '<div style="margin: -10px 0 6px 38px; color:#999;">';
            echo '<span data-toggle="tooltip" data-placement="top" title="Ledger transaction ID" style="min-width:80px; display: inline-block;">'.number_format($trs_count[0]['trs_count'] , 0).' Total Transactions</span>';
            echo ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Ledger transaction ID" style="min-width:80px; display: inline-block;"><i class="fal fa-coins"></i> '.number_format($trs_count[0]['coins_sum'], 0).' Coins</span>';
        echo '</div>';



        if(count($trs)>0){
            echo '<div class="list-group list-grey">';
            foreach ($trs as $tr) {
                echo fn___echo_tr_row($tr);
            }
            echo '</div>';
        } else {
            //Show no transaction warning:
            echo '<div class="alert alert-info" role="alert" style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> No Transactions found with the selected filters. Modify filters and try again.</div>';
        }

    echo '</div>';

    echo '<div class="col-md-5">';
    echo '</div>';
echo '</div>';


?>