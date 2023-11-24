<?php



$e__id = ( isset($_GET['e__id']) ? intval($_GET['e__id']) : 0 );
$i__id = ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 );

if($i__id > 0){

    $is = $this->I_model->fetch(array(
        'i__id' => $i__id,
    ));
    if(!count($is)){
        die('Invalid Idea ID');
    }

    echo '<h2>' . view_title($is[0]) . '</h2>';

    $preg_query = $this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
        'x__right' => $i__id,
        'x__up' => 32103,
    ));


    //See apply to sources:
    $apply_to = array();
    foreach($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 7545, //Following Add
        'x__right' => $i__id,
    ), array('x__up')) as $x_tag){
        array_push($apply_to, intval($x_tag['x__up']));
    }


    if(count($preg_query)){



        if($e__id > 0){

            $responses = 0;
            $updated = 0;
            $removed = 0;

            echo '<p>SOURCES Applying against ['.$preg_query[0]['x__message'].'] results in:</p>';

            foreach($this->X_model->fetch(array(
                'x__up' => $e__id,
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array('x__down'), 0) as $x) {

                $responses++;
                $new_form = preg_replace($preg_query[0]['x__message'], "", $x['x__message'] );
                $links_updated = 0;
                $links_removed = 0;
                if(strlen($new_form) != strlen($x['x__message'])) {

                    if(strlen($new_form)){

                        $updated++;
                        if(isset($_GET['update'])){

                            $this->X_model->update($x['x__id'], array(
                                'x__message' => $new_form,
                            ));

                            foreach($apply_to as $apply_e__id){
                                foreach($this->X_model->fetch(array(
                                    'x__up' => $apply_e__id,
                                    'x__down' => $x['x__creator'],
                                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                ), array(), 0) as $follow_appended) {
                                    $links_updated++;
                                    $this->X_model->update($follow_appended['x__id'], array(
                                        'x__message' => $new_form,
                                    ));
                                }
                            }

                            echo 'Updated! ';
                        }

                    } else {

                        $removed++;
                        if(isset($_GET['update'])){

                            $this->X_model->update($x['x__id'], array(
                                'x__access' => 6173,
                            ));

                            //Also update follower link?
                            foreach($apply_to as $apply_e__id){
                                foreach($this->X_model->fetch(array(
                                    'x__up' => $apply_e__id,
                                    'x__down' => $x['x__creator'],
                                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                ), array(), 0) as $follow_appended) {
                                    $links_removed++;
                                    $this->X_model->update($follow_appended['x__id'], array(
                                        'x__access' => 6173,
                                    ));
                                }
                            }
                            echo 'Removed! ';
                        }
                    }

                    echo '<a href="/@'.$x['x__creator'].'">@'.$x['x__creator'].'</a> ['.$x['x__message'].'] transforms to ['.$new_form.']<hr />';
                }
            }

            echo 'SOURCES '.$updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

        }


        $responses = 0;
        $updated = 0;
        $removed = 0;

        echo '<p>Applying against ['.$preg_query[0]['x__message'].'] results in:</p>';

        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'LENGTH(x__message)>0' => null,
            'x__left' => $i__id,
        ), array(), 0) as $x) {
            $responses++;
            $new_form = preg_replace($preg_query[0]['x__message'], "", $x['x__message'] );
            $links_updated = 0;
            $links_removed = 0;
            if(strlen($new_form) != strlen($x['x__message'])) {

                if(strlen($new_form)){

                    $updated++;
                    if(isset($_GET['update'])){

                        $this->X_model->update($x['x__id'], array(
                            'x__message' => $new_form,
                        ));

                        foreach($apply_to as $apply_e__id){
                            foreach($this->X_model->fetch(array(
                                'x__up' => $apply_e__id,
                                'x__down' => $x['x__creator'],
                                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            ), array(), 0) as $follow_appended) {
                                $links_updated++;
                                $this->X_model->update($follow_appended['x__id'], array(
                                    'x__message' => $new_form,
                                ));
                            }
                        }
                        echo 'Updated! ';
                    }

                } else {

                    $removed++;
                    if(isset($_GET['update'])){

                        $this->X_model->update($x['x__id'], array(
                            'x__access' => 6173,
                        ));

                        //Also update follower link?
                        foreach($apply_to as $apply_e__id){
                            foreach($this->X_model->fetch(array(
                                'x__up' => $apply_e__id,
                                'x__down' => $x['x__creator'],
                                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            ), array(), 0) as $follow_appended) {
                                $links_removed++;
                                $this->X_model->update($follow_appended['x__id'], array(
                                    'x__access' => 6173,
                                ));
                            }
                        }
                        echo 'Removed! ';
                    }
                }

                echo '<a href="/@'.$x['x__creator'].'">@'.$x['x__creator'].'</a> ['.$x['x__message'].'] transforms to ['.$new_form.']<hr />';
            }
        }

        echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

    } else {

        echo 'Preg remove not set for this idea';

    }

} else {
    echo 'Enter Idea or Source ID';
}
