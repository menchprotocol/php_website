<?php

//IDEA LIST INVALID TITLES

$active_ins = $this->IDEA_model->fetch(array(
    'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
));

//Give an overview:
echo '<p>When the validation criteria change within the idea__title_validate() function, this page lists all the ideas that no longer have a valid outcome.</p>';


//List the matching search:
echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
echo '<td style="text-align: left;">#</td>';
echo '<td style="text-align: left;">Invalid Outcome</td>';
echo '</tr>';

$invalid_outcomes = 0;
foreach($active_ins as $count=>$in){

    $idea__title_validation = idea__title_validate($in['idea__title']);

    if(!$idea__title_validation['status']){

        $invalid_outcomes++;

        //Update idea:
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$invalid_outcomes.'</td>';
        echo '<td style="text-align: left;">'.view_cache('sources__4737' /* Idea Status */, $in['idea__status'], true, 'right').' <a href="/idea/go/'.$in['idea__id'].'">'.view_idea__title($in).'</a></td>';
        echo '</tr>';

    }

}
echo '</table>';

