<?php

$all_discoveries = 0;
$all_children = 0;
$updated = 0;
$session_source = superpower_assigned();

foreach($this->MAP_model->fetch(array(
    'i__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
    'i__type IN (' . join(',', $this->config->item('sources_id_7712')) . ')' => null,
), 0, 0, array('i__id' => 'DESC')) as $count => $in) {

    echo '<div>'.($count+1).')<span class="icon-block">'.view_cache('sources__6193' /* OR Ideas */, $in['i__type']).'</span><a href="/map/i_go/'.$in['i__id'].'">'.view_i_title($in).'</a></div>';

    echo '<ul style="list-style: decimal;">';
    //Fetch all children for this OR:
    foreach($this->DISCOVER_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        'i__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => $in['i__id'],
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $child_or){

        $x_coins = $this->DISCOVER_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null,
            'x__left' => $child_or['i__id'],
        ), array(), 1, 0, array(), 'COUNT(x__id) as totals');


        $all_children++;

        echo '<li>';
        echo '<span class="icon-block">'.view_cache('sources__7585', $child_or['i__type']).'</span>';
        echo '<a href="/map/i_go/'.$child_or['i__id'].'">'.view_i_title($child_or).'</a>';
        echo ( $x_coins[0]['totals'] > 0 ? ' <span class="discover montserrat"><i class="fas fa-circle discover"></i> '.$x_coins[0]['totals'].'</span>' : '' );
        echo '</li>';

    }
    echo '</ul>';
    echo '<hr />';
}

echo 'DISCOVER: '.( $updated > 0 ? ' ('.$updated.' updated)' : '' ).' across '.$all_children.' answers';

