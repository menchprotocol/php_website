<?php

if (isset($_GET['hashtagterm'])) {
    foreach ($this->Hashtags->read(array(
        'LOWER(hashtagterm)' => strtolower($_GET['hashtagterm']),
    )) as $i) {

        echo '<h2>' . view_hashtag_title($i) . '</h2>';

        $preg_query = $this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandleinput' => 26611,
        ));

        if (count($preg_query)) {

            $responses = 0;
            $failed = 0;

            echo '<p>Mismatches against [' . $preg_query[0]['chainvalue'] . '] are:</p>';

            foreach ($this->Chains->read(array(
                'chainhandletype' => 6144, //Written Response
                'chainhashtaginput' => $i['hashtagid'],
            ), array(), 0) as $x) {
                $responses++;
                if (!preg_match($preg_query[0]['chainvalue'], $x['chainvalue'])) {
                    $failed++;
                    if (isset($_GET['delete'])) {
                        $this->Chains->delete($x['chainid']);
                        echo 'Deleted! ';
                    } else {
                        echo 'Set ?delete=1? ';
                    }
                    echo $x['chainvalue'] . '<hr />';
                }
            }

            echo $failed . '/' . $responses . ' FAILED!<hr /><hr /><hr />';

        } else {

            echo 'Preg match not set for this hashtag';

        }
    }
}


