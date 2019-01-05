<?php

//Display filters:
echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-chart-bar"></i> Platform Stats</h5>';


//Load core Mench Objects:
$en_all_4534 = $this->config->item('en_all_4534');

echo '<div class="row">';
foreach (fn___echo_status() as $object_id => $statuses) {


    //Define object type and run count query:
    if($object_id=='in_status'){

        $obj_en_id = 4535; //Intents
        $objects_count = $this->Database_model->fn___in_fetch(array(), array(), 0, 0, array(), 'in_status, COUNT(in_id) as totals', 'in_status');

    } elseif($object_id=='en_status'){

        $obj_en_id = 4536; //Entities
        $objects_count = $this->Database_model->fn___en_fetch(array(), array('skip_en__parents'), 0, 0, array(), 'en_status, COUNT(en_id) as totals', 'en_status');

    } elseif($object_id=='tr_status'){

        $obj_en_id = 4341; //Ledger
        $objects_count = $this->Database_model->fn___tr_fetch(array(), array(), 0, 0, array(), 'tr_status, COUNT(tr_id) as totals', 'tr_status');

    } else {

        //Unsupported
        continue;

    }


    //Start section:
    echo '<div class="col-sm-4">';
    echo '<table class="table table-condensed table-striped stats-table" style="max-width:350px;">';


    //Object Header:
    echo '<tr>';
    echo '<td colspan="2" style="text-align: left;"><h3 style="margin:0 5px; padding: 0; font-weight:bold; font-size: 1.4em;">'.$en_all_4534[$obj_en_id]['m_icon'].' '.$en_all_4534[$obj_en_id]['m_name'].'</h3></td>';
    echo '</tr>';


    //Object Stats grouped by Status:
    $this_totals = 0;
    foreach ($statuses as $status_num => $status) {

        $count = 0;
        foreach($objects_count as $oc){
            if($oc[$object_id]==$status_num){
                $count = intval($oc['totals']);
                break;
            }
        }

        //Display this status count:
        echo '<tr>';
        echo '<td style="text-align: left;"><span data-toggle="tooltip" title="'.$object_id.'='.$status_num.' in the '.$en_all_4534[$obj_en_id]['m_name'].' database table" data-placement="top" style="width:34px; display: inline-block; text-align: center;">[<span class="underdot">'.$status_num.'</span>]</span>'.fn___echo_status($object_id, $status_num, false, 'top').'</td>';
        echo '<td style="text-align: right;">'.number_format($count,0).'</td>';
        echo '</tr>';

        //Increase total counter:
        $this_totals += $count;
    }


    //Object Total count:
    echo '<tr>';
    echo '<td style="text-align: right;">Totals:&nbsp;</td>';
    echo '<td style="text-align: right;"><b>'.number_format($this_totals,0).'</b></td>';
    echo '</tr>';


    //End Section:
    echo '</table>';
    echo '</div>';

}

echo '</div>';
?>