<?php

if(isset($_GET['i__hashtag'])){
    foreach($this->Idea_cache->fetch(array(
        'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
    )) as $i){

        echo '<h2>' . view__i_title($i) . '</h2>';

        $preg_query = $this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'LinkType IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
            'LinkRight' => $i['i__id'],
            'LinkUp' => 26611,
        ));

        if(count($preg_query)){

            $responses = 0;
            $failed = 0;

            echo '<p>Mismatches against ['.$preg_query[0]['LinkText'].'] are:</p>';

            foreach($this->Mench_ledger->fetch(array(
                'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'LinkType' => 6144, //Written Response
                'LinkLeft' => $i['i__id'],
            ), array(), 0) as $x) {
                $responses++;
                if(!preg_match($preg_query[0]['LinkText'], $x['LinkText'])) {
                    $failed++;
                    if(isset($_GET['delete'])){
                        $this->Mench_ledger->update($x['LinkId'], array(
                            'LinkPrivacy' => 6173,
                        ));
                        echo 'Deleted! ';
                    } else {
                        echo 'Set ?delete=1? ';
                    }
                    echo $x['LinkText'].'<hr />';
                }
            }

            echo $failed.'/'.$responses.' FAILED!<hr /><hr /><hr />';

        } else {

            echo 'Preg match not set for this idea';

        }
    }
}


