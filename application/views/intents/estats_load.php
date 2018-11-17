<?php

$engagements = $this->Db_model->e_fetch(array(
    '(e_parent_c_id='.$c_id.' OR e_child_c_id='.$c_id.')' => null,
), (is_dev() ? 20 : 100));

//Fetch objects
echo '<div class="list-group list-grey">';
foreach($engagements as $e){
    echo echo_e($e);
}
echo '</div>';

?>
