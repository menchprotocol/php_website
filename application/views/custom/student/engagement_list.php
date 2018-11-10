<?php

//Fetch data:
$engagements = $this->Db_model->e_fetch(array(
    '(e_outbound_u_id = '.$u_id.' OR e_inbound_u_id = '.$u_id.')' => null,
), (is_dev() ? 20 : 100));

//Show this data:
//Fetch objects
echo '<div class="list-group">';
foreach($engagements as $e){
    echo echo_e($e);
}
echo '</div>';
?>