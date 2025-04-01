<?php

boost_power();

$link_count = 0;
$link_full_duplicate = 0;
$previous_x = array();

echo '<table width="100%" border="1px">';
foreach($this->Mench_ledger->fetch(array(
    'link_id >' => '0',
    'link_type IN (4983,4230,4251,7545)' => null,
), array(), 0, 0, array(
    'link_type' => 'ASC',
    'link_up' => 'ASC',
    'link_down' => 'ASC',
    'link_left' => 'ASC',
    'link_right' => 'ASC',
    'LENGTH(link_text)' => 'DESC',
    'link_id' => 'DESC',
)) as $x){

    //echo $x['link_id']."<hr />";

    $link_count++;
    if(count($previous_x) && $previous_x['link_type']==$x['link_type']){
        //Check duplicate with previous link:
        if(($previous_x['link_left']>0 || $previous_x['link_right']>0) && $previous_x['link_left']==$x['link_left'] && $previous_x['link_right']==$x['link_right'] && (($previous_x['link_text']==$x['link_text']) || !strlen($previous_x['link_text']) || !strlen($x['link_text']))){

            echo '<tr><td>ID '.$previous_x['link_id'].'</td><td>TP '.$previous_x['link_type'].'</td><td>PL '.$previous_x['link_player'].'</td><td>LF '.$previous_x['link_left'].'</td><td>RT '.$previous_x['link_right'].'</td><td> </td><td> </td><td>'.$previous_x['link_text'].'</td></tr>';
            echo '<tr style="background-color: #EFEFEF;"><td>ID '.$x['link_id'].'</td><td>TP '.$x['link_type'].'</td><td>PL '.$x['link_player'].'</td><td>LF '.$x['link_left'].'</td><td>RT '.$x['link_right'].'</td><td> </td><td> </td><td>'.$x['link_text'].'</td></tr>';

            //$this->db->query("DELETE FROM mench_ledger WHERE link_id=".$x['link_id'].";");

            //What about content?
            $link_full_duplicate++;

        } elseif(($previous_x['link_up']>0 || $previous_x['link_down']>0) && $previous_x['link_up']==$x['link_up'] && $previous_x['link_down']==$x['link_down'] && (($previous_x['link_text']==$x['link_text']) || !strlen($previous_x['link_text']) || !strlen($x['link_text']))){

            echo '<tr><td>ID '.$previous_x['link_id'].'</td><td>TP '.$previous_x['link_type'].'</td><td>PL '.$previous_x['link_player'].'</td><td> </td><td> </td><td>UP '.$previous_x['link_up'].'</td><td>DW '.$previous_x['link_down'].'</td><td>'.$previous_x['link_text'].'</td></tr>';
            echo '<tr style="background-color: #EFEFEF;"><td>ID '.$x['link_id'].'</td><td>TP '.$x['link_type'].'</td><td>PL '.$x['link_player'].'</td><td> </td><td> </td><td>UP '.$x['link_up'].'</td><td>DW '.$x['link_down'].'</td><td>'.$x['link_text'].'</td></tr>';

            //$this->db->query("DELETE FROM mench_ledger WHERE link_id=".$x['link_id'].";");

            //What about content?
            $link_full_duplicate++;

        }
    }

    $previous_x = $x;
}
echo '</table>';

echo $link_full_duplicate.' duplicate out of '.$link_count.' TOTAL';




//view__json($this->Mench_ledger->tree_full_history($focus_i, $focus_e['e__id']));
if(0){
if(1){
    $count = 0;
    $found = 0;
    foreach($this->Source_cache->fetch(array(
        'e__id >' => 0,
    ), 0, 0, array('e__id' => 'ASC')) as $e){

        $count++;
        //echo $count.') @'.$e['e__handle'].' @'.$e['e__id'].'<hr />';

        $creators = $this->Mench_ledger->fetch(array(
            'link_down' => $e['e__id'],
            'link_type IN (4230,4251)' => null, //Idea References
            'link_void' => 0, //Not Void
        ));

        $this->db->insert('menchledger', array(
            'linkplayer' => ( isset($creators[0]['link_player']) ? $creators[0]['link_player'] : $e['e__id'] ),
            'linktime' => ( isset($creators[0]['link_time']) ? $creators[0]['link_time'] : date("Y-m-d H:i:s", time()) ),
            'linkdomain' => ( isset($creators[0]['link_domain']) ? $creators[0]['link_domain'] : 0 ),
            'linkup' => 32338, //Player Handle
            'linktext' => $e['e__handle'],
            'linkdown' => $e['e__id'],
            'linktype' => 4230, //Follow
        ));

        if(strlen($e['e__cover'])){
            $this->db->insert('menchledger', array(
                'linkplayer' => ( isset($creators[0]['link_player']) ? $creators[0]['link_player'] : $e['e__id'] ),
                'linktime' => ( isset($creators[0]['link_time']) ? $creators[0]['link_time'] : date("Y-m-d H:i:s", time()) ),
                'linkdomain' => ( isset($creators[0]['link_domain']) ? $creators[0]['link_domain'] : 0 ),
                'linkup' => 6198, //Player Cover
                'linktext' => $e['e__cover'],
                'linkdown' => $e['e__id'],
                'linktype' => 4230, //Follow
            ));
        }




        if(0){
            //Lets log:
            $this->db->insert('menchledger', array(
                'linkid' => $e['e__id'],
                'linkplayer' => ( isset($creators[0]['link_player']) ? $creators[0]['link_player'] : $e['e__id'] ),
                'linktime' => ( isset($creators[0]['link_time']) ? $creators[0]['link_time'] : date("Y-m-d H:i:s", time()) ),
                'linkdomain' => ( isset($creators[0]['link_domain']) ? $creators[0]['link_domain'] : 0 ),
                'linktext' => $e['e__title'],
                'linktype' => 4251, //New Source Created
            ));

            //Add to cache:
            $this->db->insert('cacheplayers', array(
                'playerid' => $e['e__id'],
                'playerexternal' => intval($e['e__external']),
                'playernumber' => intval($e['e__weight']),
                'playerhandle' => $e['e__handle'],
                'playercover' => $e['e__cover'],
                'playertext' => $e['e__title'],
            ));
        }

    }
    echo $found.' Updated Found';

}




$count = 0;
$found = 0;
foreach($this->Idea_cache->fetch(array(
    'i__id >' => 0,
), 0) as $i){

    $count++;
    //echo $count.') #'.$i['i__id']."<hr />";

    $creators = $this->Mench_ledger->fetch(array(
        'link_right' => $i['i__id'],
        'link_type' => 4250, //Idea References
        'link_void' => 0, //Not Void
    ));

    //Lets log:
    $new_i_id = intval($i['i__id'])+100000;

    $this->db->insert('menchledger', array(
        'linkplayer' => ( isset($creators[0]['link_player']) ? $creators[0]['link_player'] : $e['e__id'] ),
        'linktime' => ( isset($creators[0]['link_time']) ? $creators[0]['link_time'] : date("Y-m-d H:i:s", time()) ),
        'linkdomain' => ( isset($creators[0]['link_domain']) ? $creators[0]['link_domain'] : 0 ),
        'linkup' => 32337, //Idea Hashtag
        'linkright' => $new_i_id,
        'linktext' => $i['i__hashtag'],
        'linktype' => 4983, //CO-author
    ));

    continue;

    $this->db->insert('menchledger', array(
        'linkid' => $new_i_id,
        'linkplayer' => ( isset($creators[0]['link_player']) ? $creators[0]['link_player'] : 1 ),
        'linktime' => ( isset($creators[0]['link_time']) ? $creators[0]['link_time'] : date("Y-m-d H:i:s", time()) ),
        'linkdomain' => ( isset($creators[0]['link_domain']) ? $creators[0]['link_domain'] : 0 ),
        'linktext' => $i['i__message'],
        'linktype' => 4250,
    ));

    //Add to cache:
    $this->db->insert('cacheideas', array(
        'ideaid' => $new_i_id,
        'ideaexternal' => intval($i['i__external']),
        'ideanumber' => intval($i['i__weight']),
        'ideahashtag' => $i['i__hashtag'],
        'ideatext' => $i['i__message'],
        'ideacache' => $i['i__cache'],
    ));


}
echo $found.'/'.$count.' Found';

}
