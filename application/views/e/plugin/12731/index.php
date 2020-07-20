<?php

//IDEA LIST INVALID TITLES

$active_i = $this->I_model->fetch(array(
    'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
));

//Give an overview:
echo '<p>When the validation criteria change within the i__title_validate() function, this page lists all the ideas that no longer have a valid outcome.</p>';


//List the matching search:
echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
echo '<td style="text-align: left;">#</td>';
echo '<td style="text-align: left;">Invalid Outcome</td>';
echo '</tr>';

$invalid_outcomes = 0;
foreach($active_i as $count=>$in){

    $i__title_validation = i__title_validate($in['i__title']);

    if(!$i__title_validation['status']){

        $invalid_outcomes++;

        //Update idea:
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$invalid_outcomes.'</td>';
        echo '<td style="text-align: left;">'.view_cache(4737 /* Idea Status */, $in['i__status'], true, 'right').' <a href="/i/i_go/'.$in['i__id'].'">'.view_i_title($in).'</a></td>';
        echo '</tr>';

    }

}
echo '</table>';

