<?php

$all_x = 0;
$all_down = 0;
$updated = 0;

foreach($this->I_model->fetch(array(
    'i__type IN (' . join(',', $this->config->item('n___7712')) . ')' => null,
), 0, 0, array('i__id' => 'DESC')) as $count => $in) {

    echo '<div>'.($count+1).')'.view_cache(7712 /* OR Ideas */, $in['i__type']).'<a href="/'.$in['i__hashtag'].'">'.view_i_title($in).'</a></div>';

    echo '<ul style="list-style: decimal;">';
    //Fetch all followers for this OR:
    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
        'x__previous' => $in['i__id'],
    ), array('x__next'), 0, 0, array('x__weight' => 'ASC')) as $down_or){

        $x_covers = $this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__previous' => $down_or['i__id'],
        ), array(), 1, 0, array(), 'COUNT(x__id) as totals');


        $all_down++;

        echo '<li>';
        echo '<a href="/'.$down_or['i__hashtag'].'">'.view_i_title($down_or).'</a>';
        echo ( $x_covers[0]['totals'] > 0 ? ' <span class="zq6255 main__title"><i class="fas fa-circle zq6255"></i> '.$x_covers[0]['totals'].'</span>' : '' );
        echo '</li>';

    }
    echo '</ul>';
    echo '<hr />';
}

echo 'DISCOVERY: '.( $updated > 0 ? ' ('.$updated.' updated)' : '' ).' across '.$all_down.' answers';

