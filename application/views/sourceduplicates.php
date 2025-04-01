<?php

//SOURCE LIST DUPLICATES

if(isset($_GET['playerhandle'])){

    //Find Link Content Duplicates for this Source:
    $main_index = array();
    $duplicates_found = array();
    foreach($this->Menchledger->fetch(array(
        'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
        'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'linkvoid' => 0, //Not Void
    ), array('linkup'), 0) as $x) {
        $linktext_md5 = substr(md5($x['linktext']), 0, 16);
        if(!isset($main_index[$linktext_md5])){
            $main_index[$linktext_md5] = array();
        } else {
            //Found Duplicate!
            if(!isset($duplicates_found[$linktext_md5])){
                $duplicates_found[$linktext_md5] = $main_index[$linktext_md5];
            }
            array_push($duplicates_found[$linktext_md5], $x['linkdown']);
        }

        array_push($main_index[$linktext_md5], $x['linkdown']);

    }

    echo 'Here are the '.count($duplicates_found).' duplicates found:<hr />';
    print_r($duplicates_found);

} elseif(!isset($_GET['search_by_name'])){

    echo '<p>Either enter ?playerid= in URL to search specific source Follower Message Duplicates (Finding duplicate emails for example) or <a href="'.view__app_link(7268).'?search_by_name=1"><b>Find Duplicate Sources by Name</b></a></p>.';

} else {

    //Find by name:
    $q = $this->db->query('select en1.* from cacheplayers en1 where (select count(*) from cacheplayers en2 where en2.playertext = en1.playertext ORDER BY en1.playertext ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;

        foreach($duplicates as $en) {

            if ($prev_title != $en['playertext']) {
                echo '<hr />';
                $prev_title = $en['playertext'];
            }

            echo '<a href="'.view__memory(42903,42902) . $en['playerhandle'] . '"><b>' . $en['playertext'] . '</b></a> @' . $en['playerid'] . '<br />';
        }

    } else {
        echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';
    }

}

