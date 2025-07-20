<?php

//HANDLE LIST DUPLICATES

if(isset($_GET['handlehandle'])){

    //Find Chain Content Duplicates for this Handle:
    $main_index = array();
    $duplicates_found = array();
    foreach($this->Chains->read(array(
        'LOWER(handlehandle)' => strtolower($_GET['handlehandle']),
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array('chainhandleinput'), 0) as $x) {
        $chainvalue_md5 = substr(md5($x['chainvalue']), 0, 16);
        if(!isset($main_index[$chainvalue_md5])){
            $main_index[$chainvalue_md5] = array();
        } else {
            //Found Duplicate!
            if(!isset($duplicates_found[$chainvalue_md5])){
                $duplicates_found[$chainvalue_md5] = $main_index[$chainvalue_md5];
            }
            array_push($duplicates_found[$chainvalue_md5], $x['chainhandleoutput']);
        }

        array_push($main_index[$chainvalue_md5], $x['chainhandleoutput']);

    }

    echo 'Here are the '.count($duplicates_found).' duplicates found:<hr />';
    print_r($duplicates_found);

} elseif(!isset($_GET['search_by_name'])){

    echo '<p>Either enter ?handleid= in URL to search specific Handle Follower Message Duplicates (Finding duplicate emails for example) or <a href="'.view_app_chain(7268).'?search_by_name=1"><b>Find Duplicate Handles by Name</b></a></p>.';

} else {

    //Find by name:
    $q = $this->db->query('select en1.* from  ideachainhandles en1 where (select count(*) from  ideachainhandles en2 where en2.handlevalue = en1.handlevalue ORDER BY en1.handlevalue ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;

        foreach($duplicates as $en) {

            if ($prev_title != $en['handlevalue']) {
                echo '<hr />';
                $prev_title = $en['handlevalue'];
            }

            echo '<a href="'.view_memory(42903,42902) . $en['handlehandle'] . '"><b>' . $en['handlevalue'] . '</b></a> @' . $en['handleid'] . '<br />';
        }

    } else {
        echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';
    }

}

