<?php

/*
 *
 * Populates the nodes and edges table for
 * Gephi https://gephi.org network visualizer
 *
 * */


//Empty both tables:
$this->db->query("TRUNCATE TABLE public.gephilinks CONTINUE IDENTITY RESTRICT;");
$this->db->query("TRUNCATE TABLE public.gephinodes CONTINUE IDENTITY RESTRICT;");

//Load IDEA LINKS:
$players___4593 = $this->config->item('players___4593');

//To make sure Idea/Player IDs are unique:
$id_prefix = array(
    12273 => 100,
    12274 => 200,
);

//Add Ideas:
$is = $this->Ideas->read(array());
foreach ($is as $in) {

    //Add Idea node:
    $this->db->insert('gephinodes', array(
        'id' => $id_prefix[12273] . $in['ideaid'],
        'label' => $in['ideatext'],
        'size' => 1,
        'node_type' => 1, //Idea
    ));

    //Fetch Next Ideas:
    foreach ($this->Links->read(array(
        'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //IDEA LINKS
        'chainidealeft' => $in['ideaid'],
    ), array('chainidearight'), 0, 0) as $next_i) {

        $this->db->insert('gephilinks', array(
            'source' => $id_prefix[12273] . $next_i['chainidealeft'],
            'target' => $id_prefix[12273] . $next_i['chainidearight'],
            'label' => $players___4593[$next_i['chainplayertype']]['m__title'], //TODO maybe give visibility to condition here?
            'weight' => 1,
            'edge_type' => $next_i['chainplayertype'],
        ));

    }
}


//Transfer Players:
$es = $this->Players->read(array());
foreach ($es as $en) {

    //Transfer Player node:
    $this->db->insert('gephinodes', array(
        'id' => $id_prefix[12274] . $en['playerid'],
        'label' => $en['playertext'],
        'size' => 1,
        'node_type' => 2, //Member
    ));

    //Fetch followers:
    foreach ($this->Links->read(array(
        'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
        'chainplayerup' => $en['playerid'],
    ), array('chainplayerdown'), 0, 0) as $player_down) {

        $this->db->insert('gephilinks', array(
            'source' => $id_prefix[12274] . $player_down['chainplayerup'],
            'target' => $id_prefix[12274] . $player_down['chainplayerdown'],
            'label' => $players___4593[$player_down['chainplayertype']]['m__title'] . ': ' . $player_down['chaintext'],
            'weight' => 1,
            'edge_type' => $player_down['chainplayertype'],
        ));

    }
}

echo count($is) . ' ideas & ' . count($es) . ' Players synced.';