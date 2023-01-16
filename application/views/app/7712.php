<?php

$all_x = 0;
$all_children = 0;
$updated = 0;

foreach($this->I_model->fetch(array(
    'i__type IN (' . join(',', $this->config->item('n___7712')) . ')' => null,
), 0, 0, array('i__id' => 'DESC')) as $count => $in) {

    echo '<div>'.($count+1).')'.view_cache(7712 /* OR Ideas */, $in['i__type']).'<a href="/~'.$in['i__id'].'">'.view_i_title($in).'</a></div>';

    echo '<ul style="list-style: decimal;">';
    //Fetch all children for this OR:
    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => $in['i__id'],
    ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $child_or){

        $x_coins = $this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__left' => $child_or['i__id'],
        ), array(), 1, 0, array(), 'COUNT(x__id) as totals');


        $all_children++;

        echo '<li>';
        echo '<a href="/~'.$child_or['i__id'].'">'.view_i_title($child_or).'</a>';
        echo ( $x_coins[0]['totals'] > 0 ? ' <span class="zq6255 css__title"><i class="fas fa-circle zq6255"></i> '.$x_coins[0]['totals'].'</span>' : '' );
        echo '</li>';

    }
    echo '</ul>';
    echo '<hr />';
}

echo 'DISCOVERY: '.( $updated > 0 ? ' ('.$updated.' updated)' : '' ).' across '.$all_children.' answers';

