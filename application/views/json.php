<?php

boost_power();

$link_count = 0;

echo '<table width="100%" border="1px">';
foreach($this->Mench_ledger->fetch(array(
    'link_type !=' => 4250,
), array(), 1, 0, array(
    'link_id' => 'ASC',
)) as $x){

    $new_x = $this->db->insert('menchledger', array(
        'linkplayer' => $x['link_player'],
        'linktime' => $x['link_time'],
        'linkdomain' => $x['link_domain'],
        'linkup' => intval($x['link_up']),
        'linkdown' => intval($x['link_down']),
        'linkleft' => ( $x['link_left']>0 ? 100000+$x['link_left'] : 0 ),
        'linkright' => ( $x['link_right']>0 ? 100000+$x['link_right'] : 0 ),
        'linknumber' => $x['link_number'],
        'linktext' => $x['link_text'],
        'linktype' => $x['link_type'],
        'linkvoid' => 0,
    ));

    if(isset($new_x['linkid'])){
        $this->db->query("DELETE FROM mench_ledge WHERE link_left=".$x['link_id'].";");
        $link_success++;

    }
    $link_count++;


}
echo '</table>';

echo $link_success.'/'.$link_count.' SUCCESS';




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
