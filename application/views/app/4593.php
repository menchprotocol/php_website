<?php

//List all interactions types and their counts:
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$table_sortable = array('#th_primary','#th_count','#th_total','#th_points');

echo '<table class="table table-sm table-striped stats-table mini-stats-table" id="sortable_table">';
echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';

echo '<td style="text-align: left;" id="th_count">#</td>';
echo '<td style="text-align: left;" id="th_primary">Interaction Type</td>';

//List all statuses:
foreach($this->config->item('e___6186') as $x__type1 => $m1) {
    array_push($table_sortable, '#th_e_'.$x__type1);
    echo '<td style="text-align: left;" id="th_e_'.$x__type1.'">'.$m1['m__cover'].' '.$m1['m__title'].'</td>';
}

echo '<td style="text-align: left;" id="th_total">Total Interactions</td>';

//Points Total
echo '<td style="text-align: left;" id="th_points">'.$e___11035[42225]['m__cover'].' '.$e___11035[42225]['m__title'].'</td>';

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
        ), array('x__down'), 0, 0, array(), 'COUNT(x__id) as totals');

        echo '<td style="text-align: left;">'.number_format($list_e_count[0]['totals'], 0).'</td>';
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
    ), array(), 0, 0, array(), 'SUM(x__points) as totals');
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

?>

<style>
    .container{ margin-left: 8px; max-width: calc(100% - 16px) !important; }
</style>

<script>

    $(document).ready(function () {
        var table = $('#sortable_table');
        $('<?= join(', ', $table_sortable) ?>')
            .each(function(){

                var th = $(this),
                    thIndex = th.index(),
                    inverse = false;

                th.click(function(){

                    table.find('td').filter(function(){

                        return $(this).index() === thIndex;

                    }).sortElements(function(a, b){

                        return $.text([a]) < $.text([b]) ?
                            inverse ? -1 : 1
                            : inverse ? 1 : -1;

                    }, function(){

                        return this.parentNode;

                    });

                    inverse = !inverse;

                });

            });
    });
</script>
