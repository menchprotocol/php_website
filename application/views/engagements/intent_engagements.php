<?php

$k_outs = $this->Db_model->tr_fetch(array(
    'tr_in_child_id' => $in_id, //Active subscriptions only
    'tr_status >=' => 0, //Real completion [You can remove this to see all submissions with all statuses]
    //We are fetching with any tr_status just to see what is available/possible from here
), array('w', 'w_u', 'cr', 'cr_c_parent'), array('tr_status' => 'ASC'), 0);

//Fetch objects
$current_status = -999; //This would keep going higher as we print each heather...
echo '<div class="list-group list-grey">';
foreach ($k_outs as $k) {
    if ($k['tr_status'] > $current_status) {
        //Print header:
        echo '<h3 style="margin-top:15px;">' . echo_status('tr_status', $k['tr_status']) . '</h3>';
        //Update pointer:
        $current_status = $k['tr_status'];
    }
    echo echo_k_matrix($k);
}
echo '</div>';

?>
