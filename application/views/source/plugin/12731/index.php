<?php

//IDEA LIST INVALID TITLES

$active_ins = $this->IDEA_model->fetch(array(
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
));

//Give an overview:
echo '<p>When the validation criteria change within the in_title_validate() function, this page lists all the ideas that no longer have a valid outcome.</p>';


//List the matching search:
echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
echo '<td style="text-align: left;">#</td>';
echo '<td style="text-align: left;">Invalid Outcome</td>';
echo '</tr>';

$invalid_outcomes = 0;
foreach($active_ins as $count=>$in){

    $in_title_validation = in_title_validate($in['in_title']);

    if(!$in_title_validation['status']){

        $invalid_outcomes++;

        //Update idea:
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$invalid_outcomes.'</td>';
        echo '<td style="text-align: left;">'.echo_en_cache('en_all_4737' /* Idea Status */, $in['in_status_source_id'], true, 'right').' <a href="/idea/go/'.$in['in_id'].'">'.echo_in_title($in).'</a></td>';
        echo '</tr>';

    }

}
echo '</table>';

