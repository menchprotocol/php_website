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
$en_all_4593 = $this->config->item('en_all_4593');

//To make sure Idea/source IDs are unique:
$id_prefix = array(
    'in' => 100,
    'en' => 200,
);

//Size of nodes:
$node_size = array(
    'in' => 3,
    'en' => 2,
    'msg' => 1,
);

//Add Ideas:
$ins = $this->IDEA_model->fetch(array(
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
));
foreach($ins as $in){

    //Prep metadata:
    $in_metadata = ( strlen($in['in_metadata']) > 0 ? unserialize($in['in_metadata']) : array());

    //Add Idea node:
    $this->db->insert('gephi_nodes', array(
        'id' => $id_prefix['in'].$in['in_id'],
        'label' => $in['in_title'],
        //'size' => ( isset($in_metadata['in__metadata_max_seconds']) ? round(($in_metadata['in__metadata_max_seconds']/3600),0) : 0 ), //Max time
        'size' => $node_size['in'],
        'node_type' => 1, //Idea
        'node_status' => $in['in_status_source_id'],
    ));

    //Fetch children:
    foreach($this->TRANSACTION_model->fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
        'ln_previous_idea_id' => $in['in_id'],
    ), array('in_next'), 0, 0) as $child_in){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix['in'].$child_in['ln_previous_idea_id'],
            'target' => $id_prefix['in'].$child_in['ln_next_idea_id'],
            'label' => $en_all_4593[$child_in['ln_type_source_id']]['m_name'], //TODO maybe give visibility to condition here?
            'weight' => 1,
            'edge_type_en_id' => $child_in['ln_type_source_id'],
            'edge_status' => $child_in['ln_status_source_id'],
        ));

    }
}


//Add sources:
$ens = $this->SOURCE_model->fetch(array(
    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
));
foreach($ens as $en){

    //Add source node:
    $this->db->insert('gephi_nodes', array(
        'id' => $id_prefix['en'].$en['en_id'],
        'label' => $en['en_name'],
        'size' => $node_size['en'] ,
        'node_type' => 2, //Player
        'node_status' => $en['en_status_source_id'],
    ));

    //Fetch children:
    foreach($this->TRANSACTION_model->fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
        'ln_profile_source_id' => $en['en_id'],
    ), array('en_portfolio'), 0, 0) as $en_child){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix['en'].$en_child['ln_profile_source_id'],
            'target' => $id_prefix['en'].$en_child['ln_portfolio_source_id'],
            'label' => $en_all_4593[$en_child['ln_type_source_id']]['m_name'].': '.$en_child['ln_content'],
            'weight' => 1,
            'edge_type_en_id' => $en_child['ln_type_source_id'],
            'edge_status' => $en_child['ln_status_source_id'],
        ));

    }
}

//Add messages:
$messages = $this->TRANSACTION_model->fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //IDEA NOTES
), array('in_next'), 0, 0);
foreach($messages as $message) {

    //Add message node:
    $this->db->insert('gephi_nodes', array(
        'id' => $message['ln_id'],
        'label' => $en_all_4593[$message['ln_type_source_id']]['m_name'] . ': ' . $message['ln_content'],
        'size' => $node_size['msg'],
        'node_type' => $message['ln_type_source_id'], //Message type
        'node_status' => $message['ln_status_source_id'],
    ));

    //Add child idea link:
    $this->db->insert('gephi_edges', array(
        'source' => $message['ln_id'],
        'target' => $id_prefix['in'].$message['ln_next_idea_id'],
        'label' => 'Child Idea',
        'weight' => 1,
    ));

    //Add parent idea link?
    if ($message['ln_previous_idea_id'] > 0) {
        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix['in'].$message['ln_previous_idea_id'],
            'target' => $message['ln_id'],
            'label' => 'Parent Idea',
            'weight' => 1,
        ));
    }

    //Add parent source link?
    if ($message['ln_profile_source_id'] > 0) {
        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix['en'].$message['ln_profile_source_id'],
            'target' => $message['ln_id'],
            'label' => 'Parent Source',
            'weight' => 1,
        ));
    }

}

echo count($ins).' ideas & '.count($ens).' sources & '.count($messages).' messages synced.';