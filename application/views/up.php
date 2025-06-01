<?php

echo '<h1>'.$focus_e['sourcevalue'].'</h1>';


$deleted_all = 0;
foreach ($this->Chains->read(array(
    'chainsourceup' => 4430,
    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
), array('chainsourcedown'), 0) as $e) {

    $deleted = 0;
    foreach($this->Chains->read(array(
        'chainsourceup' => $e['sourceid'],
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
    ), array('chainsourcedown'), 0) as $child){
        $this->Chains->delete($child['chainid']);
        $deleted++;
    }

    $deleted_all += $deleted;
    if($deleted>0){
        echo '<div>'.$deleted.') <span class="icon-block">'.view_cover($e['sourcecover']).'</span><span class="main__title">'.$e['sourcevalue'].'</span><span class="grey">@'.$e['sourcehandle'].'.</span></div>';
    }

}

echo $deleted_all;

/*
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
*/