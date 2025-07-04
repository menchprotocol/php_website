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

//Load HASHTAG CHAINS:
$handles___4593 = $this->config->item('handles___4593');

//To make sure Hashtag/Handle IDs are unique:
$id_prefix = array(
    12273 => 100,
    12274 => 200,
);

//Add Hashtags:
$is = $this->Hashtags->read(array());
foreach ($is as $in) {

    //Add Hashtag node:
    $this->db->insert('gephinodes', array(
        'id' => $id_prefix[12273] . $in['hashtagid'],
        'label' => $in['hashtagvalue'],
        'size' => 1,
        'node_type' => 1, //Hashtag
    ));

    //Fetch Next Hashtags:
    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
        'chainhashtaginput' => $in['hashtagid'],
    ), array('chainhashtagoutput'), 0, 0) as $next_i) {

        $this->db->insert('gephichains', array(
            'handle' => $id_prefix[12273] . $next_i['chainhashtaginput'],
            'target' => $id_prefix[12273] . $next_i['chainhashtagoutput'],
            'label' => $handles___4593[$next_i['chainhandletype']]['m__title'], //TODO maybe give visibility to condition here?
            'weight' => 1,
            'edge_type' => $next_i['chainhandletype'],
        ));

    }
}


//Transfer Handles:
$es = $this->Handles->read(array());
foreach ($es as $en) {

    //Transfer Handle node:
    $this->db->insert('gephinodes', array(
        'id' => $id_prefix[12274] . $en['handleid'],
        'label' => $en['handlevalue'],
        'size' => 1,
        'node_type' => 2, //Member
    ));

    //Fetch followers:
    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        'chainhandleinput' => $en['handleid'],
    ), array('chainhandleoutput'), 0, 0) as $handle_down) {

        $this->db->insert('gephichains', array(
            'handle' => $id_prefix[12274] . $handle_down['chainhandleinput'],
            'target' => $id_prefix[12274] . $handle_down['chainhandleoutput'],
            'label' => $handles___4593[$handle_down['chainhandletype']]['m__title'] . ': ' . $handle_down['chainvalue'],
            'weight' => 1,
            'edge_type' => $handle_down['chainhandletype'],
        ));

    }
}

echo count($is) . ' hashtags & ' . count($es) . ' Handles synced.';