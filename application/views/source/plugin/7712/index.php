<?php

$all_reads = 0;
$all_children = 0;
$updated = 0;
$session_source = superpower_assigned();

foreach($this->IDEA_model->fetch(array(
    'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
    'idea__type IN (' . join(',', $this->config->item('sources_id_7712')) . ')' => null,
), 0, 0, array('idea__id' => 'DESC')) as $count => $in) {

    echo '<div>'.($count+1).')<span class="icon-block">'.view_cache('sources__6193' /* OR Ideas */, $in['idea__type']).'</span><a href="/!'.$in['idea__id'].'">'.view_idea__title($in).'</a></div>';

    echo '<ul style="list-style: decimal;">';
    //Fetch all children for this OR:
    foreach($this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'read__left' => $in['idea__id'],
    ), array('read__right'), 0, 0, array('read__sort' => 'ASC')) as $child_or){

        $read_coins = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null,
            'read__left' => $child_or['idea__id'],
        ), array(), 1, 0, array(), 'COUNT(read__id) as totals');


        $all_children++;

        echo '<li>';
        echo '<span class="icon-block">'.view_cache('sources__7585', $child_or['idea__type']).'</span>';
        echo '<a href="/!'.$child_or['idea__id'].'">'.view_idea__title($child_or).'</a>';
        echo ( $read_coins[0]['totals'] > 0 ? ' <span class="read montserrat"><i class="fas fa-circle read"></i> '.$read_coins[0]['totals'].'</span>' : '' );
        echo '</li>';

    }
    echo '</ul>';
    echo '<hr />';
}

echo 'READ: '.( $updated > 0 ? ' ('.$updated.' updated)' : '' ).' across '.$all_children.' answers';

