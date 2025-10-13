<?php

//List all interactions types and their counts:
$users___11035 = $this->config->item('users___11035'); //Encyclopedia
$table_sortable = array('#th_primary','#th_count','#th_total','#th_points','#th_perfect');
$total_count = 0;
$total_access = array();
$total_interactions = 0;
$total_points = 0;
$table_body = '';

//Count total first:
$totals_count = $this->Chains->read(array(), array(), 0, 0, array(), 'COUNT(chainid) as totals');
$pad_length = strlen($totals_count[0]['totals']);

foreach($this->config->item('users___4593') as $chainusertype => $m) {

    $total_count++;

    $table_body .= '<tr class="panel-title down-border" style="font-weight:bold !important;">';
    $table_body .= '<td style="text-align: left; font-family: monospace, monospace;">'.str_pad($total_count, 3, '0', STR_PAD_LEFT).'</td>';
    $table_body .= '<td style="text-align: left; width:21px; text-align: center">'.$m['m__cover'].'</td>';
    $table_body .= '<td style="text-align: left;"><a href="'.view_memory(42903,42902).$m['m__handle'].'">'.$m['m__name'].'</a></td>';
    $table_body .= '<td style="text-align: left;">'.$chainusertype.'</td>';

    //List all statuses:
    $listuser_count = $this->Chains->read(array(
        'chainusertype' => $chainusertype,
    ), array(), 0, 0, array(), 'COUNT(chainid) as totals');
    $interactions_this = $listuser_count[0]['totals'];
    $total_interactions += $interactions_this;
    $table_body .= '<td style="text-align: left; font-family: monospace, monospace;">'.str_pad($interactions_this, $pad_length, '0', STR_PAD_LEFT).'</td>';
    $table_body .= '<th style="text-align: left; font-family: monospace, monospace;">'.str_pad(number_format(($interactions_this/$totals_count[0]['totals']*100), 3), 6, '0', STR_PAD_LEFT).'%</th>';

    $table_body .= '</tr>';

}


echo '<table class="table table-sm table-striped stats-table mini-stats-table" id="sortable_table">';
echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';

echo '<th style="text-align: left;" id="th_count">#</th>';
echo '<th style="text-align: left;">&nbsp;</th>'; //Cover
echo '<th style="text-align: left;" id="th_primary">Interaction Type</th>';
echo '<th style="text-align: left;">ID</th>'; //ID

//List all statuses:

echo '<th style="text-align: left;" id="th_total">Total Interactions</th>';
echo '<th style="text-align: left;" id="th_perfect">%</th>';

//Points Total
echo '<th style="text-align: left;" id="th_points"></th>';

echo '</tr>';


echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';

echo '<th style="text-align: left;">&nbsp;</th>';
echo '<th style="text-align: left;">&nbsp;</th>'; //Cover
echo '<th style="text-align: left;"><b>TOTALS</b></th>';
echo '<th style="text-align: left;" id="th_count">&nbsp;</th>'; //ID


echo '<th style="text-align: left; font-family: monospace, monospace;">'.str_pad($total_interactions, $pad_length, '0', STR_PAD_LEFT).'</th>';
echo '<th style="text-align: left; font-family: monospace, monospace;">'.number_format(($total_interactions/$totals_count[0]['totals']*100), 2).'%</th>';

//Points Total
echo '<th style="text-align: left;  font-family: monospace, monospace;">'.str_pad($total_points, $pad_length, '0', STR_PAD_LEFT).'</th>';

echo '</tr>';

echo $table_body;

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
