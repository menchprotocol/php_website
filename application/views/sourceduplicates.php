<?php

//SOURCE LIST DUPLICATES

if(isset($_GET['sourcehandle'])){

    //Find Chain Content Duplicates for this Source:
    $main_index = array();
    $duplicates_found = array();
    foreach($this->Chains->read(array(
        'LOWER(sourcehandle)' => strtolower($_GET['sourcehandle']),
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainsourceup'), 0) as $x) {
        $chainvalue_md5 = substr(md5($x['chainvalue']), 0, 16);
        if(!isset($main_index[$chainvalue_md5])){
            $main_index[$chainvalue_md5] = array();
        } else {
            //Found Duplicate!
            if(!isset($duplicates_found[$chainvalue_md5])){
                $duplicates_found[$chainvalue_md5] = $main_index[$chainvalue_md5];
            }
            array_push($duplicates_found[$chainvalue_md5], $x['chainsourcedown']);
        }

        array_push($main_index[$chainvalue_md5], $x['chainsourcedown']);

    }

    echo 'Here are the '.count($duplicates_found).' duplicates found:<hr />';
    print_r($duplicates_found);

} elseif(!isset($_GET['search_by_name'])){

    echo '<p>Either enter ?sourceid= in URL to search specific Source Follower Message Duplicates (Finding duplicate emails for example) or <a href="'.view_app_chain(7268).'?search_by_name=1"><b>Find Duplicate Sources by Name</b></a></p>.';

} else {

    //Find by name:
    $q = $this->db->query('select en1.* from  cachesources en1 where (select count(*) from  cachesources en2 where en2.sourcevalue = en1.sourcevalue ORDER BY en1.sourcevalue ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;

        foreach($duplicates as $en) {

            if ($prev_title != $en['sourcevalue']) {
                echo '<hr />';
                $prev_title = $en['sourcevalue'];
            }

            echo '<a href="'.view_memory(42903,42902) . $en['sourcehandle'] . '"><b>' . $en['sourcevalue'] . '</b></a> @' . $en['sourceid'] . '<br />';
        }

    } else {
        echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';
    }

}

