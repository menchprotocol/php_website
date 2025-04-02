<?php

if(isset($_GET['ideahashtag'])){
    foreach($this->Nodeideas->fetch(array(
        'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
    )) as $i){

        echo '<h2>' . view_idea_title($i) . '</h2>';

        $preg_query = $this->Ledger->fetch(array(
                    'linktype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkright' => $i['ideaid'],
            'linkup' => 26611,
        ));

        if(count($preg_query)){

            $responses = 0;
            $failed = 0;

            echo '<p>Mismatches against ['.$preg_query[0]['linktext'].'] are:</p>';

            foreach($this->Ledger->fetch(array(
                            'linktype' => 6144, //Written Response
                'linkleft' => $i['ideaid'],
            ), array(), 0) as $x) {
                $responses++;
                if(!preg_match($preg_query[0]['linktext'], $x['linktext'])) {
                    $failed++;
                    if(isset($_GET['delete'])){
                        $this->Ledger->update($x['linkid'], array());
                        echo 'Deleted! ';
                    } else {
                        echo 'Set ?delete=1? ';
                    }
                    echo $x['linktext'].'<hr />';
                }
            }

            echo $failed.'/'.$responses.' FAILED!<hr /><hr /><hr />';

        } else {

            echo 'Preg match not set for this idea';

        }
    }
}


