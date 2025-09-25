<?php

//USER LIST DUPLICATES

if(isset($_GET['userhandle'])){

    //Find Chain Content Duplicates for this User:
    $main_index = array();
    $duplicates_found = array();
    foreach($this->Chains->read(array(
        'LOWER(userhandle)' => strtolower($_GET['userhandle']),
        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array('chainuserinput'), 0) as $x) {
        $chainvalue_md5 = substr(md5($x['chainvalue']), 0, 16);
        if(!isset($main_index[$chainvalue_md5])){
            $main_index[$chainvalue_md5] = array();
        } else {
            //Found Duplicate!
            if(!isset($duplicates_found[$chainvalue_md5])){
                $duplicates_found[$chainvalue_md5] = $main_index[$chainvalue_md5];
            }
            array_push($duplicates_found[$chainvalue_md5], $x['chainuseroutput']);
        }

        array_push($main_index[$chainvalue_md5], $x['chainuseroutput']);

    }

    echo 'Here are the '.count($duplicates_found).' duplicates found:<hr />';
    print_r($duplicates_found);

} elseif(!isset($_GET['search_by_name'])){

    echo '<p>Either enter ?userid= in URL to search specific User Follower Message Duplicates (Finding duplicate emails for example) or <a href="'.view_app_chain(7268).'?search_by_name=1"><b>Find Duplicate Users by Name</b></a></p>.';

} else {

    //Find by name:
    $q = $this->db->query('select en1.* from  users en1 where (select count(*) from  users en2 where en2.username = en1.username ORDER BY en1.username ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;

        foreach($duplicates as $en) {

            if ($prev_title != $en['username']) {
                echo '<hr />';
                $prev_title = $en['username'];
            }

            echo '<a href="'.view_memory(42903,42902) . $en['userhandle'] . '"><b>' . $en['username'] . '</b></a> @' . $en['userid'] . '<br />';
        }

    } else {
        echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';
    }

}

