<?php

//SOURCE LIST DUPLICATES

if(isset($_GET['playerhandle'])){

    //Find Chain Content Duplicates for this Player:
    $main_index = array();
    $duplicates_found = array();
    foreach($this->Chains->read(array(
        'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
        'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainplayerup'), 0) as $x) {
        $chaintext_md5 = substr(md5($x['chaintext']), 0, 16);
        if(!isset($main_index[$chaintext_md5])){
            $main_index[$chaintext_md5] = array();
        } else {
            //Found Duplicate!
            if(!isset($duplicates_found[$chaintext_md5])){
                $duplicates_found[$chaintext_md5] = $main_index[$chaintext_md5];
            }
            array_push($duplicates_found[$chaintext_md5], $x['chainplayerdown']);
        }

        array_push($main_index[$chaintext_md5], $x['chainplayerdown']);

    }

    echo 'Here are the '.count($duplicates_found).' duplicates found:<hr />';
    print_r($duplicates_found);

} elseif(!isset($_GET['search_by_name'])){

    echo '<p>Either enter ?playerid= in URL to search specific Player Follower Message Duplicates (Finding duplicate emails for example) or <a href="'.view_app_chain(7268).'?search_by_name=1"><b>Find Duplicate Players by Name</b></a></p>.';

} else {

    //Find by name:
    $q = $this->db->query('select en1.* from  cacheplayers en1 where (select count(*) from  cacheplayers en2 where en2.playertext = en1.playertext ORDER BY en1.playertext ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;

        foreach($duplicates as $en) {

            if ($prev_title != $en['playertext']) {
                echo '<hr />';
                $prev_title = $en['playertext'];
            }

            echo '<a href="'.view_memory(42903,42902) . $en['playerhandle'] . '"><b>' . $en['playertext'] . '</b></a> @' . $en['playerid'] . '<br />';
        }

    } else {
        echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';
    }

}

