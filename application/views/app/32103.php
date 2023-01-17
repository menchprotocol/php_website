<?php

$i__id = ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 );

$is = $this->I_model->fetch(array(
    'i__id' => $i__id,
));
if(!count($is)){
    die('Invalid Idea ID');
}

echo '<h2>' . $is[0]['i__title'] . '</h2>';

$preg_query = $this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
    'x__right' => $i__id,
    'x__up' => 32103,
));

if(count($preg_query)){

    $responses = 0;
    $updated = 0;
    $removed = 0;

    echo '<p>Applying against ['.$preg_query[0]['x__message'].'] results in:</p>';

    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 6144, //Written Response
        'x__left' => $i__id,
    ), array(), 0) as $x) {
        $responses++;
        $new_form = preg_replace($preg_query[0]['x__message'], "", $x['x__message'] );
        $links_updated = 0;
        $links_removed = 0;
        if($new_form != $x['x__message']) {

            if(strlen($new_form)){
                $updated++;
                if(isset($_GET['update'])){
                    $this->X_model->update($x['x__id'], array(
                        'x__message' => $new_form,
                    ));
                    //Also update follower link?
                    foreach($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type' => 7545, //Profile Add
                        'x__right' => $i__id,
                    ), array('x__up')) as $x_tag){
                        foreach($this->X_model->fetch(array(
                            'x__up' => $x_tag['e__id'],
                            'x__down' => $x['x__creator'],
                            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        ), array(), 0) as $follow_appended) {
                            $links_updated++;
                            $this->X_model->update($follow_appended['x__id'], array(
                                'x__message' => $new_form,
                                'x__type' => e_x__type($new_form),
                            ));
                        }
                    }
                }
                echo 'Updated! ';
            } else {
                $removed++;
                if(isset($_GET['update'])){
                    $this->X_model->update($x['x__id'], array(
                        'x__privacy' => 6173,
                    ));
                    //Also update follower link?
                    foreach($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type' => 7545, //Profile Add
                        'x__right' => $i__id,
                    ), array('x__up')) as $x_tag){
                        foreach($this->X_model->fetch(array(
                            'x__up' => $x_tag['e__id'],
                            'x__down' => $x['x__creator'],
                            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        ), array(), 0) as $follow_appended) {
                            $links_removed++;
                            $this->X_model->update($follow_appended['x__id'], array(
                                'x__privacy' => 6173,
                            ));
                        }
                    }
                }
                echo 'Removed! ';
            }

            echo '['.$x['x__message'].'] transforms to ['.$new_form.']<hr />';
        }
    }

    echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

} else {

    echo 'Preg remove not set for this idea';

}



