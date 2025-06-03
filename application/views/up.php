<?php

//Displays all the up sources of a given source recursively

echo '<h1>'.$focus_e['sourcevalue'].'</h1>';

$total_parents = source_up($focus_e['sourceid']);
$current_total = count($total_parents);
$current_i = 0;
echo '<div class="row justify-content">';
foreach ($this->Sources->read(array(
    'sourceid IN (' . join(',', $total_parents) . ')' => null,
)) as $e) {
    echo '<div>'.($current_total-$current_i).') <span class="icon-block">'.view_cover($e['sourcecover']).'</span><span class="main__title">'.$e['sourcevalue'].'</span><span class="grey">@'.$e['sourcehandle'].'</span></div>';
    $current_i++;
}
echo '</div>';
