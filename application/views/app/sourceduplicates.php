<?php

//SOURCE LIST DUPLICATES

if(isset($_GET['e__handle'])){

    //Find Link Content Duplicates for this Source:
    $main_index = array();
    $duplicates_found = array();
    foreach($this->X_model->fetch(array(
        'LOWER(e__handle)' => strtolower($_GET['e__handle']),
        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array('x__following'), 0) as $x) {
        $x__message_md5 = substr(md5($x['x__message']), 0, 16);
        if(!isset($main_index[$x__message_md5])){
            $main_index[$x__message_md5] = array();
        } else {
            //Found Duplicate!
            if(!isset($duplicates_found[$x__message_md5])){
                $duplicates_found[$x__message_md5] = $main_index[$x__message_md5];
            }
            array_push($duplicates_found[$x__message_md5], $x['x__follower']);
        }

        array_push($main_index[$x__message_md5], $x['x__follower']);

    }

    echo 'Here are the '.count($duplicates_found).' duplicates found:<hr />';
    print_r($duplicates_found);

} elseif(!isset($_GET['search_by_name'])){

    echo '<p>Either enter ?e__id= in URL to search specific source Follower Message Duplicates (Finding duplicate emails for example) or <a href="'.view_app_link(7268).'?search_by_name=1"><b>Find Duplicate Sources by Name</b></a></p>.';

} else {

    //Find by name:
    $q = $this->db->query('select en1.* from cache_sources en1 where (select count(*) from cache_sources en2 where en2.e__title = en1.e__title AND en2.e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')) > 1 AND en1.e__privacy IN (' . join(',', $this->config->item('n___7358')) . ') ORDER BY en1.e__title ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        $e___6177 = $this->config->item('e___6177'); //Source Privacy

        foreach($duplicates as $en) {

            if ($prev_title != $en['e__title']) {
                echo '<hr />';
                $prev_title = $en['e__title'];
            }

            echo '<span data-toggle="tooltip" data-placement="right" title="'.$e___6177[$en['e__privacy']]['m__title'].': '.$e___6177[$en['e__privacy']]['m__message'].'">' . $e___6177[$en['e__privacy']]['m__cover'] . '</span> <a href="'.view_memory(42903,42902) . $en['e__handle'] . '"><b>' . $en['e__title'] . '</b></a> @' . $en['e__id'] . '<br />';
        }

    } else {
        echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';
    }

}

