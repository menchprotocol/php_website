<?php

/*
 *
 * Populates the nodes and edges table for
 * Gephi https://gephi.org network visualizer
 *
 * */


//Empty both tables:
$this->db->query("TRUNCATE TABLE public.links CONTINUE IDENTITY RESTRICT;");
$this->db->query("TRUNCATE TABLE public.nodes CONTINUE IDENTITY RESTRICT;");

//Load POST CHAINS:
$users___4593 = $this->config->item('users___4593');

//To make sure Post/User IDs are unique:
$id_prefix = array(
    12273 => 100,
    12274 => 200,
);

//Add Posts:
$is = $this->Posts->read(array());
foreach ($is as $in) {

    //Add Post node:
    $this->db->insert('nodes', array(
        'id' => $id_prefix[12273] . $in['postid'],
        'label' => $in['posttext'],
        'size' => 1,
        'node_type' => 1, //Post
    ));

    //Fetch Next Posts:
    foreach ($this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
        'chainpostinput' => $in['postid'],
    ), array('chainpostoutput'), 0, 0) as $next_i) {

        $this->db->insert('links', array(
            'source' => $id_prefix[12273] . $next_i['chainpostinput'],
            'target' => $id_prefix[12273] . $next_i['chainpostoutput'],
            'label' => $users___4593[$next_i['chainusertype']]['m__title'], //TODO maybe give visibility to condition here?
            'weight' => 1,
            'edge_type' => $next_i['chainusertype'],
        ));

    }
}


//Transfer Users:
$es = $this->Users->read(array());
foreach ($es as $en) {

    //Transfer User node:
    $this->db->insert('nodes', array(
        'id' => $id_prefix[12274] . $en['userid'],
        'label' => $en['username'],
        'size' => 1,
        'node_type' => 2, //Member
    ));

    //Fetch followers:
    foreach ($this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        'chainuserinput' => $en['userid'],
    ), array('chainuseroutput'), 0, 0) as $user_down) {

        $this->db->insert('links', array(
            'source' => $id_prefix[12274] . $user_down['chainuserinput'],
            'target' => $id_prefix[12274] . $user_down['chainuseroutput'],
            'label' => $users___4593[$user_down['chainusertype']]['m__title'] . ': ' . $user_down['chainvalue'],
            'weight' => 1,
            'edge_type' => $user_down['chainusertype'],
        ));

    }
}

echo count($is) . ' posts & ' . count($es) . ' Users synced.';