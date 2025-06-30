<?php

/*
 *
 * Populates the nodes and edges table for
 * Gephi https://gephi.org network visualizer
 *
 * */


//Empty both tables:
$this->db->query("TRUNCATE TABLE public.gephichains CONTINUE IDENTITY RESTRICT;");
$this->db->query("TRUNCATE TABLE public.gephinodes CONTINUE IDENTITY RESTRICT;");

//Load IDEA CHAINS:
$sources___4593 = $this->config->item('sources___4593');

//To make sure Idea/Source IDs are unique:
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
        'label' => $in['ideavalue'],
        'size' => 1,
        'node_type' => 1, //Idea
    ));

    //Fetch Next Ideas:
    foreach ($this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42345')) . ')' => null, //Active Sequence
        'chainidealeft' => $in['ideaid'],
    ), array('chainidearight'), 0, 0) as $next_i) {

        $this->db->insert('gephichains', array(
            'source' => $id_prefix[12273] . $next_i['chainidealeft'],
            'target' => $id_prefix[12273] . $next_i['chainidearight'],
            'label' => $sources___4593[$next_i['chainsourcetype']]['m__title'], //TODO maybe give visibility to condition here?
            'weight' => 1,
            'edge_type' => $next_i['chainsourcetype'],
        ));

    }
}


//Transfer Sources:
$es = $this->Sources->read(array());
foreach ($es as $en) {

    //Transfer Source node:
    $this->db->insert('gephinodes', array(
        'id' => $id_prefix[12274] . $en['sourceid'],
        'label' => $en['sourcevalue'],
        'size' => 1,
        'node_type' => 2, //Member
    ));

    //Fetch followers:
    foreach ($this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        'chainsourceup' => $en['sourceid'],
    ), array('chainsourcedown'), 0, 0) as $source_down) {

        $this->db->insert('gephichains', array(
            'source' => $id_prefix[12274] . $source_down['chainsourceup'],
            'target' => $id_prefix[12274] . $source_down['chainsourcedown'],
            'label' => $sources___4593[$source_down['chainsourcetype']]['m__title'] . ': ' . $source_down['chainvalue'],
            'weight' => 1,
            'edge_type' => $source_down['chainsourcetype'],
        ));

    }
}

echo count($is) . ' ideas & ' . count($es) . ' Sources synced.';