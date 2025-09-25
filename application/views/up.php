<?php

//Displays all the up users of a given user recursively

echo '<h1>'.$focus_e['username'].'</h1>';

$total_parents = user_up($focus_e['userid']);
$current_total = count($total_parents);
$current_i = 0;
echo '<div class="row justify-content">';
foreach ($this->Users->read(array(
    'userid IN (' . join(',', $total_parents) . ')' => null,
)) as $e) {
    echo '<div>'.($current_total-$current_i).') <span class="icon-block">'.view_cover($e['usercover']).'</span><span class="main__title">'.$e['username'].'</span><span class="grey">@'.$e['userhandle'].'</span></div>';
    $current_i++;
}
echo '</div>';
