<?php

if(isset($_GET['i__hashtag'])){
    foreach($this->Idea_cache->fetch(array(
        'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
    )) as $i){

        echo '<h2>' . view__i_title($i) . '</h2>';

        $preg_query = $this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
            'link_right' => $i['i__id'],
            'link_up' => 26611,
        ));

        if(count($preg_query)){

            $responses = 0;
            $failed = 0;

            echo '<p>Mismatches against ['.$preg_query[0]['link_text'].'] are:</p>';

            foreach($this->Mench_ledger->fetch(array(
                'link_void' => 0, //Not Void
                'link_type' => 6144, //Written Response
                'link_left' => $i['i__id'],
            ), array(), 0) as $x) {
                $responses++;
                if(!preg_match($preg_query[0]['link_text'], $x['link_text'])) {
                    $failed++;
                    if(isset($_GET['delete'])){
                        $this->Mench_ledger->update($x['link_id'], array());
                        echo 'Deleted! ';
                    } else {
                        echo 'Set ?delete=1? ';
                    }
                    echo $x['link_text'].'<hr />';
                }
            }

            echo $failed.'/'.$responses.' FAILED!<hr /><hr /><hr />';

        } else {

            echo 'Preg match not set for this idea';

        }
    }
}


