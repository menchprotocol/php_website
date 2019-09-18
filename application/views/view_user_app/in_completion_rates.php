<?php

//Generate completion stats:
$en_all_7596 = $this->config->item('en_all_7596'); //Intent Level
$course_details = '';
$total_enrolled = 0;
$total_completed = 0;
foreach($this->Intents_model->in_fetch(array(
    'in_level_entity_id' => 7598, //Tree
    'in_status_entity_id' => 6184, //Published
), array(), 0, 0, array('in_outcome' => 'ASC' )) as $in_published_tree){

    //Count Enrolled Users:
    $enrolled_users = $this->Links_model->ln_fetch(array(
        'ln_parent_intent_id' => $in_published_tree['in_id'],
        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

    if($enrolled_users[0]['totals'] < 1){
        continue;
    }

    //Determine the final common step to determine completion rate:
    $in_metadata = unserialize($in_published_tree['in_metadata']);
    $common_steps = array_flatten($in_metadata['in__metadata_common_steps']);
    $completed_users = $this->Links_model->ln_fetch(array(
        'ln_parent_intent_id' => end($common_steps),
        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

    $total_enrolled += $enrolled_users[0]['totals'];
    $total_completed += $completed_users[0]['totals'];
    $completion_rate = $completed_users[0]['totals']/$enrolled_users[0]['totals']*100;

    $course_details .= '<tr class="panel-title down-border">';
    $course_details .= '<td style="text-align: left;"><a href="/'.$in_published_tree['in_id'].'">'.echo_in_outcome($in_published_tree['in_outcome']).'</a></td>';
    $course_details .= '<td style="text-align: left;">'.number_format($enrolled_users[0]['totals'], 0).'</td>';
    $course_details .= '<td style="text-align: left;">'.number_format($completion_rate, ( $completion_rate<100 && $completion_rate>0 ? 1 : 0 )).'%</td>';
    $course_details .= '</tr>';

}


echo '<h1 style="margin-bottom:30px;" id="title-parent">'.$en_all_7596[7598]['m_name'].' Completion Rates</h1>';

echo '<p style="margin:25px 0 25px;">Mench is an open platform on a mission to streamline how we learn. Here are our live completion stats:</p>';


echo '<table class="table table-condensed table-striped stats-table mini-stats-table" style="font-size: 0.8em;">';

echo '<tr class="panel-title down-border">';
echo '<td style="text-align: left;">Mench '.$en_all_7596[7598]['m_name'].'</td>';
echo '<td style="text-align: left;">Enrolled</td>';
echo '<td style="text-align: left;">Completed</td>';
echo '</tr>';

echo '<tr class="panel-title down-border copy-btn-done">';
echo '<td style="text-align: left;">All '.$en_all_7596[7598]['m_name'].'s</td>';
echo '<td style="text-align: left;">'.number_format($total_enrolled, 0).'</td>';
echo '<td style="text-align: left;">'.number_format(($total_completed/$total_enrolled*100), 1).'%</td>';
echo '</tr>';

echo $course_details;

echo '</table>';

?>
