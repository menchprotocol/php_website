<?php

if (isset($_GET['posthashtag'])) {
    foreach ($this->Posts->read(array(
        'LOWER(posthashtag)' => strtolower($_GET['posthashtag']),
    )) as $i) {

        echo '<h2>' . view_post_title($i) . '</h2>';

        $preg_query = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput' => 26611,
        ));

        if (count($preg_query)) {

            $responses = 0;
            $failed = 0;

            echo '<p>Mismatches against [' . $preg_query[0]['chainvalue'] . '] are:</p>';

            foreach ($this->Chains->read(array(
                'chainusertype' => 4559,
                'chainpostinput' => $i['postid'],
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

            echo 'Preg match not set for this post';

        }
    }
}


