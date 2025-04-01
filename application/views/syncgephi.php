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
$players___4593 = $this->config->item('players___4593');

//To make sure Idea/Player IDs are unique:
$id_prefix = array(
    12273 => 100,
    12274 => 200,
);

//Add Ideas:
$is = $this->Cacheideas->fetch(array(
));
foreach($is as $in){

    //Add Idea node:
    $this->db->insert('gephi_nodes', array(
        'id' => $id_prefix[12273].$in['ideaid'],
        'label' => $in['ideatext'],
        'size' => 1,
        'node_type' => 1, //Idea
    ));

    //Fetch Next Ideas:
    foreach($this->Menchledger->fetch(array(
        'linktype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //IDEA LINKS
        'linkleft' => $in['ideaid'],
    ), array('linkright'), 0, 0) as $next_i){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix[12273].$next_i['linkleft'],
            'target' => $id_prefix[12273].$next_i['linkright'],
            'label' => $players___4593[$next_i['linktype']]['m__title'], //TODO maybe give visibility to condition here?
            'weight' => 1,
            'edge_type' => $next_i['linktype'],
        ));

    }
}


//Transfer Players:
$es = $this->Cacheplayers->fetch(array(
));
foreach($es as $en){

    //Transfer Player node:
    $this->db->insert('gephi_nodes', array(
        'id' => $id_prefix[12274].$en['playerid'],
        'label' => $en['playertext'],
        'size' => 1,
        'node_type' => 2, //Member
    ));

    //Fetch followers:
    foreach($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
        'linkup' => $en['playerid'],
    ), array('linkdown'), 0, 0) as $player_down){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix[12274].$player_down['linkup'],
            'target' => $id_prefix[12274].$player_down['linkdown'],
            'label' => $players___4593[$player_down['linktype']]['m__title'].': '.$player_down['linktext'],
            'weight' => 1,
            'edge_type' => $player_down['linktype'],
        ));

    }
}

echo count($is).' ideas & '.count($es).' Players synced.';