<?php

foreach($this->Ideas->read(array(
    'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
)) as $i){

    echo '<h2>' . view_i_title($i) . '</h2>';

    $preg_query = $this->Ledger->read(array(
            'x__type IN (' . njoin(42991) . ')' => null, //Active Writes
        'x__next' => $i['i__id'],
        'x__following' => 32103,
    ));


    //See apply to sources:
    $apply_to = array();
    foreach($this->Ledger->read(array(
            'x__type' => 7545, //Following Add
        'x__next' => $i['i__id'],
    ), array('x__following')) as $x_tag){
        array_push($apply_to, intval($x_tag['x__following']));
    }


    if(count($preg_query)){



        if(isset($_GET['e__handle'])){

            $responses = 0;
            $updated = 0;
            $removed = 0;

            echo '<p>SOURCES Applying against ['.$preg_query[0]['x__message'].'] results in:</p>';

            foreach($this->Sources->read(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            )) as $e){
                foreach($this->Ledger->read(array(
                    'x__following' => $e['e__id'],
                    'x__type IN (' . njoin(32292) . ')' => null, //SOURCE LINKS
                        ), array('x__follower'), 0) as $x) {

                    $responses++;
                    $new_form = preg_replace($preg_query[0]['x__message'], "", $x['x__message'] );
                    $links_updated = 0;
                    $links_removed = 0;
                    if(strlen($new_form) != strlen($x['x__message'])) {

                        if(strlen($new_form)){

                            $updated++;
                            if(isset($_GET['update'])){

                                $this->Ledger->edit($x['x__id'], array(
                                    'x__message' => $new_form,
                                ));

                                foreach($apply_to as $apply_e__id){
                                    foreach($this->Ledger->read(array(
                                        'x__following' => $apply_e__id,
                                        'x__follower' => $x['x__player'],
                                        'x__type IN (' . njoin(32292) . ')' => null, //SOURCE LINKS
                                                    ), array(), 0) as $follow_appended) {
                                        $links_updated++;
                                        $this->Ledger->edit($follow_appended['x__id'], array(
                                            'x__message' => $new_form,
                                        ));
                                    }
                                }

                                echo 'Updated! ';
                            }

                        } else {

                            $removed++;
                            if(isset($_GET['update'])){

                                $this->Ledger->edit($x['x__id'], array(
                                    'x__privacy' => 6173,
                                ));

                                //Also update follower link?
                                foreach($apply_to as $apply_e__id){
                                    foreach($this->Ledger->read(array(
                                        'x__following' => $apply_e__id,
                                        'x__follower' => $x['x__player'],
                                        'x__type IN (' . njoin(32292) . ')' => null, //SOURCE LINKS
                                                    ), array(), 0) as $follow_appended) {
                                        $links_removed++;
                                        $this->Ledger->edit($follow_appended['x__id'], array(
                                            'x__privacy' => 6173,
                                        ));
                                    }
                                }
                                echo 'Removed! ';
                            }
                        }

                        echo 'Source ID '.$x['x__player'].' ['.$x['x__message'].'] transforms to ['.$new_form.']<hr />';
                    }
                }
            }


            echo 'SOURCES '.$updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

        }


        $responses = 0;
        $updated = 0;
        $removed = 0;

        echo '<p>Applying against ['.$preg_query[0]['x__message'].'] results in:</p>';

        foreach($this->Ledger->read(array(
            'x__type IN (' . njoin(6255) . ')' => null, //DISCOVERIES
            'LENGTH(x__message)>0' => null,
            'x__previous' => $i['i__id'],
        ), array(), 0) as $x) {
            $responses++;
            $new_form = preg_replace($preg_query[0]['x__message'], "", $x['x__message'] );
            $links_updated = 0;
            $links_removed = 0;
            if(strlen($new_form) != strlen($x['x__message'])) {

                if(strlen($new_form)){

                    $updated++;
                    if(isset($_GET['update'])){

                        $this->Ledger->edit($x['x__id'], array(
                            'x__message' => $new_form,
                        ));

                        foreach($apply_to as $apply_e__id){
                            foreach($this->Ledger->read(array(
                                'x__following' => $apply_e__id,
                                'x__follower' => $x['x__player'],
                                'x__type IN (' . njoin(32292) . ')' => null, //SOURCE LINKS
                                    ), array(), 0) as $follow_appended) {
                                $links_updated++;
                                $this->Ledger->edit($follow_appended['x__id'], array(
                                    'x__message' => $new_form,
                                ));
                            }
                        }
                        echo 'Updated! ';
                    }

                } else {

                    $removed++;
                    if(isset($_GET['update'])){

                        $this->Ledger->edit($x['x__id'], array(
                            'x__privacy' => 6173,
                        ));

                        //Also update follower link?
                        foreach($apply_to as $apply_e__id){
                            foreach($this->Ledger->read(array(
                                'x__following' => $apply_e__id,
                                'x__follower' => $x['x__player'],
                                'x__type IN (' . njoin(32292) . ')' => null, //SOURCE LINKS
                                    ), array(), 0) as $follow_appended) {
                                $links_removed++;
                                $this->Ledger->edit($follow_appended['x__id'], array(
                                    'x__privacy' => 6173,
                                ));
                            }
                        }
                        echo 'Removed! ';
                    }
                }

                echo 'Source ID '.$x['x__player'].' ['.$x['x__message'].'] transforms to ['.$new_form.']<hr />';
            }
        }

        echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

    } else {

        echo 'Preg remove not set for this idea';

    }
}
