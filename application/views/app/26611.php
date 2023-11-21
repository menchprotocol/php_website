<?php

$i__id = ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 );

$is = $this->I_model->fetch(array(
    'i__id' => $i__id,
));
if(!count($is)){
    die('Invalid Idea ID');
}

echo '<h2>' . view_i_title($is[0]) . '</h2>';

$preg_query = $this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
    'x__right' => $i__id,
    'x__up' => 26611,
));

if(count($preg_query)){

    $responses = 0;
    $failed = 0;

    echo '<p>Mismatches against ['.$preg_query[0]['x__message'].'] are:</p>';

    foreach($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 6144, //Written Response
        'x__left' => $i__id,
    ), array(), 0) as $x) {
        $responses++;
        if(!preg_match($preg_query[0]['x__message'], $x['x__message'])) {
            $failed++;
            if(isset($_GET['delete'])){
                $this->X_model->update($x['x__id'], array(
                    'x__access' => 6173,
                ));
                echo 'Deleted! ';
            } else {
                echo 'Set ?delete=1? ';
            }
            echo $x['x__message'].'<hr />';
        }
    }

    echo $failed.'/'.$responses.' FAILED!<hr /><hr /><hr />';

} else {

    echo 'Preg match not set for this idea';

}



