<?php

//List all interactions types and their counts:
$e___11035 = $this->config->item('e___11035'); //NAVIGATION

echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';

echo '<td style="text-align: left;">#</td>';
echo '<td style="text-align: left;">Interaction Type</td>';

//List all statuses:
foreach($this->config->item('e___6186') as $x__type1 => $m1) {
    echo '<td style="text-align: left;">'.$m1['m__cover'].' '.$m1['m__title'].'</td>';
}

echo '<td style="text-align: left;">Total Interactions</td>';

//Points Total
echo '<td style="text-align: left;">'.$e___11035[42225]['m__cover'].' '.$e___11035[42225]['m__title'].'</td>';

echo '</tr>';


$total_count = 0;
$total_access = array();
$total_interactions = 0;
$total_points = 0;
foreach($this->config->item('e___4593') as $x__type => $m) {

    $total_count++;

    echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';

    echo '<td style="text-align: left;">'.$total_count.'</td>';
    echo '<td style="text-align: left;"><a href="/@'.$x__type.'">'.$m['m__cover'].' '.$m['m__title'].'</a></td>';

    //List all statuses:
    $interactions_this = 0;
    foreach($this->config->item('e___6186') as $x__type1 => $m1) {

        $list_e_count = $this->X_model->fetch(array(
            'x__type' => $x__type,
            'x__privacy' => $x__type1,
        ), array('x__down'), 0, 0, array(), 'COUNT(e__id) as totals');

        echo '<td style="text-align: left;">'.$list_e_count[0]['totals'].'</td>';
        if($list_e_count[0]['totals'] > 0){
            if(!isset($total_access[$m1['m__handle']])){
                $total_access[$m1['m__handle']] = 0;
            }
            $total_access[$m1['m__handle']] += $list_e_count[0]['totals'];
            $interactions_this += $list_e_count[0]['totals'];
        }
    }

    echo '<td style="text-align: left;">'.number_format($interactions_this, 0).'</td>';
    $total_interactions += $interactions_this;

    //Points Total
    $points = $this->X_model->fetch(array(
        'x__type' => $x__type,
    ), array(), 0, 0, array(), 'SUM(e__id) as totals');
    echo '<td style="text-align: left;">'.number_format($points[0]['totals'], 0).'</td>';
    $total_points += $points[0]['totals'];

    echo '</tr>';

}


echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';

echo '<td style="text-align: left;">&nbsp;</td>';
echo '<td style="text-align: left;"><b>TOTALS</b></td>';

//List all statuses:
foreach($this->config->item('e___6186') as $x__type1 => $m1) {
    echo '<td style="text-align: left;">'.number_format($total_access[$m1['m__handle']], 0).'</td>';
}

echo '<td style="text-align: left;">'.number_format($total_interactions, 0).'</td>';

//Points Total
echo '<td style="text-align: left;">'.number_format($total_points, 0).'</td>';

echo '</tr>';

echo '</table>';

