<?php

//IDEA LIST INVALID TITLES

$active_i = $this->I_model->fetch(array(
    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
));

//Give an overview:
echo '<p>When the validation criteria change within the validate_i__message() function, this page lists all the ideas that no longer have a valid outcome.</p>';


//List the matching search:
echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
echo '<td style="text-align: left;">#</td>';
echo '<td style="text-align: left;">Invalid Outcome</td>';
echo '</tr>';

$invalid_outcomes = 0;
foreach($active_i as $count=>$in){

    $validate_i__message = validate_i__message($in['i__message']);

    if(!$validate_i__message['status']){

        $invalid_outcomes++;

        //Update idea:
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$invalid_outcomes.'</td>';
        echo '<td style="text-align: left;">'.view_cache(4737 /* Idea Status */, $in['i__type'], true, 'right').' <a href="/~'.$in['i__hashtag'].'">'.view_i_title($in).'</a></td>';
        echo '</tr>';

    }

}
echo '</table>';

