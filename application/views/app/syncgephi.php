<?php

/*
 *
 * Populates the nodes and edges table for
 * Gephi https://gephi.org network visualizer
 *
 * */


//Empty both tables:
$this->db->query("TRUNCATE TABLE public.gephi_edges CONTINUE IDENTITY RESTRICT;");
$this->db->query("TRUNCATE TABLE public.gephi_nodes CONTINUE IDENTITY RESTRICT;");

//Load IDEA LINKS:
$e___4593 = $this->config->item('e___4593');

//To make sure Idea/source IDs are unique:
$id_prefix = array(
    12273 => 100,
    12274 => 200,
);

//Add Ideas:
$is = $this->Idea_cache->fetch(array(
    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
));
foreach($is as $in){

    //Add Idea node:
    $this->db->insert('gephi_nodes', array(
        'id' => $id_prefix[12273].$in['i__id'],
        'label' => $in['i__message'],
        'size' => 1,
        'node_type' => 1, //Idea
        'node_status' => $in['i__type'],
    ));

    //Fetch Next Ideas:
    foreach($this->Mench_ledger->fetch(array(
        'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        'LinkType IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //IDEA LINKS
        'LinkLeft' => $in['i__id'],
    ), array('LinkRight'), 0, 0) as $next_i){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix[12273].$next_i['LinkLeft'],
            'target' => $id_prefix[12273].$next_i['LinkRight'],
            'label' => $e___4593[$next_i['LinkType']]['m__title'], //TODO maybe give visibility to condition here?
            'weight' => 1,
            'edge_type' => $next_i['LinkType'],
            'edge_status' => $next_i['LinkPrivacy'],
        ));

    }
}


//Transfer sources:
$es = $this->Source_cache->fetch(array(
    'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
));
foreach($es as $en){

    //Transfer source node:
    $this->db->insert('gephi_nodes', array(
        'id' => $id_prefix[12274].$en['e__id'],
        'label' => $en['e__title'],
        'size' => 1,
        'node_type' => 2, //Member
        'node_status' => $en['e__privacy'],
    ));

    //Fetch followers:
    foreach($this->Mench_ledger->fetch(array(
        'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'LinkUp' => $en['e__id'],
    ), array('LinkDown'), 0, 0) as $e_down){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix[12274].$e_down['LinkUp'],
            'target' => $id_prefix[12274].$e_down['LinkDown'],
            'label' => $e___4593[$e_down['LinkType']]['m__title'].': '.$e_down['LinkText'],
            'weight' => 1,
            'edge_type' => $e_down['LinkType'],
            'edge_status' => $e_down['LinkPrivacy'],
        ));

    }
}

echo count($is).' ideas & '.count($es).' sources synced.';