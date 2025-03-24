<?php

foreach($this->Idea_cache->fetch(array(
    'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
)) as $i){

    echo '<h2>' . view__i_title($i) . '</h2>';

    $preg_query = $this->Mench_ledger->fetch(array(
        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'link_right' => $i['i__id'],
        'link_up' => 32103,
    ));


    //See apply to sources:
    $apply_to = array();
    foreach($this->Mench_ledger->fetch(array(
        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'link_type' => 7545, //Following Add
        'link_right' => $i['i__id'],
    ), array('link_up')) as $this_tag){
        array_push($apply_to, intval($this_tag['link_up']));
    }


    if(count($preg_query)){



        if(isset($_GET['e__handle'])){

            $responses = 0;
            $updated = 0;
            $removed = 0;

            echo '<p>SOURCES Applying against ['.$preg_query[0]['link_text'].'] results in:</p>';

            foreach($this->Source_cache->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            )) as $e){
                foreach($this->Mench_ledger->fetch(array(
                    'link_up' => $e['e__id'],
                    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array('link_down'), 0) as $x) {

                    $responses++;
                    $new_form = preg_replace($preg_query[0]['link_text'], "", $x['link_text'] );
                    $links_updated = 0;
                    $links_removed = 0;
                    if(strlen($new_form) != strlen($x['link_text'])) {

                        if(strlen($new_form)){

                            $updated++;
                            if(isset($_GET['update'])){

                                $this->Mench_ledger->update($x['link_id'], array(
                                    'link_text' => $new_form,
                                ));

                                foreach($apply_to as $apply_e__id){
                                    foreach($this->Mench_ledger->fetch(array(
                                        'link_up' => $apply_e__id,
                                        'link_down' => $x['link_player'],
                                        'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                    ), array(), 0) as $follow_appended) {
                                        $links_updated++;
                                        $this->Mench_ledger->update($follow_appended['link_id'], array(
                                            'link_text' => $new_form,
                                        ));
                                    }
                                }

                                echo 'Updated! ';
                            }

                        } else {

                            $removed++;
                            if(isset($_GET['update'])){

                                $this->Mench_ledger->update($x['link_id'], array(
                                    'link_privacy' => 6173,
                                ));

                                //Also update follower link?
                                foreach($apply_to as $apply_e__id){
                                    foreach($this->Mench_ledger->fetch(array(
                                        'link_up' => $apply_e__id,
                                        'link_down' => $x['link_player'],
                                        'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                    ), array(), 0) as $follow_appended) {
                                        $links_removed++;
                                        $this->Mench_ledger->update($follow_appended['link_id'], array(
                                            'link_privacy' => 6173,
                                        ));
                                    }
                                }
                                echo 'Removed! ';
                            }
                        }

                        echo 'Source ID '.$x['link_player'].' ['.$x['link_text'].'] transforms to ['.$new_form.']<hr />';
                    }
                }
            }


            echo 'SOURCES '.$updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

        }


        $responses = 0;
        $updated = 0;
        $removed = 0;

        echo '<p>Applying against ['.$preg_query[0]['link_text'].'] results in:</p>';

        foreach($this->Mench_ledger->fetch(array(
            'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'LENGTH(link_text)>0' => null,
            'link_left' => $i['i__id'],
        ), array(), 0) as $x) {
            $responses++;
            $new_form = preg_replace($preg_query[0]['link_text'], "", $x['link_text'] );
            $links_updated = 0;
            $links_removed = 0;
            if(strlen($new_form) != strlen($x['link_text'])) {

                if(strlen($new_form)){

                    $updated++;
                    if(isset($_GET['update'])){

                        $this->Mench_ledger->update($x['link_id'], array(
                            'link_text' => $new_form,
                        ));

                        foreach($apply_to as $apply_e__id){
                            foreach($this->Mench_ledger->fetch(array(
                                'link_up' => $apply_e__id,
                                'link_down' => $x['link_player'],
                                'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            ), array(), 0) as $follow_appended) {
                                $links_updated++;
                                $this->Mench_ledger->update($follow_appended['link_id'], array(
                                    'link_text' => $new_form,
                                ));
                            }
                        }
                        echo 'Updated! ';
                    }

                } else {

                    $removed++;
                    if(isset($_GET['update'])){

                        $this->Mench_ledger->update($x['link_id'], array(
                            'link_privacy' => 6173,
                        ));

                        //Also update follower link?
                        foreach($apply_to as $apply_e__id){
                            foreach($this->Mench_ledger->fetch(array(
                                'link_up' => $apply_e__id,
                                'link_down' => $x['link_player'],
                                'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            ), array(), 0) as $follow_appended) {
                                $links_removed++;
                                $this->Mench_ledger->update($follow_appended['link_id'], array(
                                    'link_privacy' => 6173,
                                ));
                            }
                        }
                        echo 'Removed! ';
                    }
                }

                echo 'Source ID '.$x['link_player'].' ['.$x['link_text'].'] transforms to ['.$new_form.']<hr />';
            }
        }

        echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

    } else {

        echo 'Preg remove not set for this idea';

    }
}
