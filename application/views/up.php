<?php

//Displays all the up handles of a given handle recursively

echo '<h1>'.$focus_e['handlename'].'</h1>';

$total_parents = handle_up($focus_e['handleid']);
$current_total = count($total_parents);
$current_i = 0;
echo '<div class="row justify-content">';
foreach ($this->Handles->read(array(
    'handleid IN (' . join(',', $total_parents) . ')' => null,
)) as $e) {
    echo '<div>'.($current_total-$current_i).') <span class="icon-block">'.view_cover($e['handlecover']).'</span><span class="main__title">'.$e['handlename'].'</span><span class="grey">@'.$e['handleterm'].'</span></div>';
    $current_i++;
}
echo '</div>';
