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
$sources__4593 = $this->config->item('sources__4593');

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
    'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
));
foreach($ins as $in){

    //Prep metadata:
    $idea__metadata = ( strlen($in['idea__metadata']) > 0 ? unserialize($in['idea__metadata']) : array());

    //Add Idea node:
    $this->db->insert('gephi_nodes', array(
        'id' => $id_prefix['in'].$in['idea__id'],
        'label' => $in['idea__title'],
        //'size' => ( isset($idea__metadata['idea__metadata_max_seconds']) ? round(($idea__metadata['idea__metadata_max_seconds']/3600),0) : 0 ), //Max time
        'size' => $node_size['in'],
        'node_type' => 1, //Idea
        'node_status' => $in['idea__status'],
    ));

    //Fetch children:
    foreach($this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
        'read__left' => $in['idea__id'],
    ), array('idea_next'), 0, 0) as $child_in){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix['in'].$child_in['read__left'],
            'target' => $id_prefix['in'].$child_in['read__right'],
            'label' => $sources__4593[$child_in['read__type']]['m_name'], //TODO maybe give visibility to condition here?
            'weight' => 1,
            'edge_type' => $child_in['read__type'],
            'edge_status' => $child_in['read__status'],
        ));

    }
}


//Add sources:
$ens = $this->SOURCE_model->fetch(array(
    'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
));
foreach($ens as $en){

    //Add source node:
    $this->db->insert('gephi_nodes', array(
        'id' => $id_prefix['en'].$en['source__id'],
        'label' => $en['source__title'],
        'size' => $node_size['en'] ,
        'node_type' => 2, //Player
        'node_status' => $en['source__status'],
    ));

    //Fetch children:
    foreach($this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'read__up' => $en['source__id'],
    ), array('source_portfolio'), 0, 0) as $source_child){

        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix['en'].$source_child['read__up'],
            'target' => $id_prefix['en'].$source_child['read__down'],
            'label' => $sources__4593[$source_child['read__type']]['m_name'].': '.$source_child['read__message'],
            'weight' => 1,
            'edge_type' => $source_child['read__type'],
            'edge_status' => $source_child['read__status'],
        ));

    }
}

//Add messages:
$messages = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
    'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
    'read__type IN (' . join(',', $this->config->item('sources_id_4485')) . ')' => null, //IDEA NOTES
), array('idea_next'), 0, 0);
foreach($messages as $message) {

    //Add message node:
    $this->db->insert('gephi_nodes', array(
        'id' => $message['read__id'],
        'label' => $sources__4593[$message['read__type']]['m_name'] . ': ' . $message['read__message'],
        'size' => $node_size['msg'],
        'node_type' => $message['read__type'], //Message type
        'node_status' => $message['read__status'],
    ));

    //Add child idea link:
    $this->db->insert('gephi_edges', array(
        'source' => $message['read__id'],
        'target' => $id_prefix['in'].$message['read__right'],
        'label' => 'Child Idea',
        'weight' => 1,
    ));

    //Add parent idea link?
    if ($message['read__left'] > 0) {
        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix['in'].$message['read__left'],
            'target' => $message['read__id'],
            'label' => 'Parent Idea',
            'weight' => 1,
        ));
    }

    //Add parent source link?
    if ($message['read__up'] > 0) {
        $this->db->insert('gephi_edges', array(
            'source' => $id_prefix['en'].$message['read__up'],
            'target' => $message['read__id'],
            'label' => 'Parent Source',
            'weight' => 1,
        ));
    }

}

echo count($ins).' ideas & '.count($ens).' sources & '.count($messages).' messages synced.';