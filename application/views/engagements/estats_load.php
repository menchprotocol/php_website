<?php

$engagements = $this->Db_model->li_fetch(array(
    '(li_in_parent_id='.$c_id.' OR li_in_child_id='.$c_id.')' => null,
), (is_dev() ? 20 : 100));

//Fetch objects
echo '<div class="list-group list-grey">';
foreach($engagements as $e){
    echo echo_e($e);
}
echo '</div>';

?>
