<?php

$all_x = 0;
$all_children = 0;
$updated = 0;
$user_e = superpower_assigned();

foreach($this->I_model->fetch(array(
    'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    'i__type IN (' . join(',', $this->config->item('n___7712')) . ')' => null,
), 0, 0, array('i__id' => 'DESC')) as $count => $in) {

    echo '<div>'.($count+1).')'.view_cache(6193 /* OR Ideas */, $in['i__type']).'<a href="/i/i_go/'.$in['i__id'].'">'.view_i_title($in).'</a></div>';

    echo '<ul style="list-style: decimal;">';
    //Fetch all children for this OR:
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => $in['i__id'],
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $child_or){

        $x_coins = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
            'x__left' => $child_or['i__id'],
        ), array(), 1, 0, array(), 'COUNT(x__id) as totals');


        $all_children++;

        echo '<li>';
        echo view_cache(7585, $child_or['i__type']);
        echo '<a href="/i/i_go/'.$child_or['i__id'].'">'.view_i_title($child_or).'</a>';
        echo ( $x_coins[0]['totals'] > 0 ? ' <span class="discover montserrat"><i class="fas fa-circle discover"></i> '.$x_coins[0]['totals'].'</span>' : '' );
        echo '</li>';

    }
    echo '</ul>';
    echo '<hr />';
}

echo 'DISCOVER: '.( $updated > 0 ? ' ('.$updated.' updated)' : '' ).' across '.$all_children.' answers';

