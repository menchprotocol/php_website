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
$is = $this->I_model->fetch(array(
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
    foreach($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //IDEA LINKS
        'x__previous' => $in['i__id'],
    ), array('x__next'), 0, 0) as $next_i){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix[12273].$next_i['x__previous'],
            'target' => $id_prefix[12273].$next_i['x__next'],
            'label' => $e___4593[$next_i['x__type']]['m__title'], //TODO maybe give visibility to condition here?
            'weight' => 1,
            'edge_type' => $next_i['x__type'],
            'edge_status' => $next_i['x__privacy'],
        ));

    }
}


//Transfer sources:
$es = $this->E_model->fetch(array(
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
    foreach($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__following' => $en['e__id'],
    ), array('x__follower'), 0, 0) as $e_down){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix[12274].$e_down['x__following'],
            'target' => $id_prefix[12274].$e_down['x__follower'],
            'label' => $e___4593[$e_down['x__type']]['m__title'].': '.$e_down['x__message'],
            'weight' => 1,
            'edge_type' => $e_down['x__type'],
            'edge_status' => $e_down['x__privacy'],
        ));

    }
}

echo count($is).' ideas & '.count($es).' sources synced.';