<?php

//SOURCE LIST DUPLICATES

if(isset($_GET['e__id'])){

    //Find Link Content DUplicates for this Source:
    $main_index = array();
    $duplicates_found = array();
    foreach($this->X_model->fetch(array(
        'x__up' => $_GET['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array(), 0) as $x) {
        $x__message_md5 = substr(md5($x['x__message']), 0, 16);
        if(!isset($main_index[$x__message_md5])){
            $main_index[$x__message_md5] = array();
        } else {
            //Found Duplicate!
            if(!isset($duplicates_found[$x__message_md5])){
                $duplicates_found[$x__message_md5] = $main_index[$x__message_md5];
            }
            array_push($duplicates_found[$x__message_md5], $x['x__down']);
        }

        array_push($main_index[$x__message_md5], $x['x__down']);

    }

    if(isset($_GET['auto_merge']) && $_GET['e__id']==3288){//THis is for email only for now...
        foreach($duplicates_found as $x__message_md5 => $e__ids){
            $lowest_e_id = 9999999999;
            foreach($e__ids as $e__id){
                if($e__id < $lowest_e_id){
                    $lowest_e_id = $e__id;
                }
            }

            foreach($e__ids as $this_e__id){
                if($this_e__id != $lowest_e_id){
                    array_push($duplicates_found[$x__message_md5], '@'.$this_e__id.' Merges Into @'.$lowest_e_id);
                    //array_push($duplicates_found[$x__message_md5], $this->X_model->update_dropdown($this_e__id,$this_e__id,6177,6178,$lowest_e_id)); break;
                }
            }



        }
    }


    echo 'Here are the '.count($duplicates_found).' duplicates found:<hr />';
    print_r($duplicates_found);

} elseif(!isset($_GET['search_by_name'])){

    echo '<p>Either enter ?e__id= in URL to search specific source Follower Message Duplicates (Finding duplicate emails for example) or <a href="/-7268?search_by_name=1"><b>Find Duplicate Sources by Name</b></a></p>.';

} else {

    //Find by name:
    $q = $this->db->query('select en1.* from table__e en1 where (select count(*) from table__e en2 where en2.e__title = en1.e__title AND en2.e__type IN (' . join(',', $this->config->item('n___7358')) . ')) > 1 AND en1.e__type IN (' . join(',', $this->config->item('n___7358')) . ') ORDER BY en1.e__title ASC');
    $duplicates = $q->result_array();

    if(count($duplicates) > 0){

        $prev_title = null;
        $e___6177 = $this->config->item('e___6177'); //Source Status

        foreach($duplicates as $en) {

            if ($prev_title != $en['e__title']) {
                echo '<hr />';
                $prev_title = $en['e__title'];
            }

            echo '<span data-toggle="tooltip" data-placement="right" title="'.$e___6177[$en['e__type']]['m__title'].': '.$e___6177[$en['e__type']]['m__message'].'">' . $e___6177[$en['e__type']]['m__cover'] . '</span> <a href="/@' . $en['e__id'] . '"><b>' . $en['e__title'] . '</b></a> @' . $en['e__id'] . '<br />';
        }

    } else {
        echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No duplicates found!';
    }

}

