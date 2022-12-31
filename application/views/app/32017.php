<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])) {

    //List this members discoveries so they can choose:
    echo '<div>Enter e__id to begin...</div><br />';

} else {


    //Fetch All Tickets of Source:
    $paid_ticket_types = 0;
    $ticket_ticket_x_ids = array();
    $ticket_type_ids = array();
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        'x__up' => $_GET['e__id'], //Time Starts
    ), array('x__right')) as $ticket_type){

        //Count Tickets:
        $found_ticket = false;
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32014')) . ')' => null, //Ticket Discoveries
            'x__left' => $ticket_type['i__id'],
        ), array(), 0) as $x){
            $found_ticket = true;
            array_push($ticket_ticket_x_ids, $x['x__id']);
        }

        if($found_ticket){
            array_push($ticket_type_ids, $ticket_type['i__id']);
            echo '<h3>'.$ticket_type['i__title'].'</h3>';
        }

    }

    echo '<hr />';

    echo count($ticket_ticket_x_ids).' Paid Tickets total';




    /*
     *
     * $this->X_model->send_dm($watcher['x__up'], $es_discoverer[0]['e__title'].' Discovered: '.$i['i__title'],
                                //Message Body:
                                $i['i__title'].':'."\n".'https://'.$domain_url.'/~'.$i['i__id']."\n\n".
                                ( strlen($add_fields['x__message']) ? $add_fields['x__message']."\n\n" : '' ).
                                $es_discoverer[0]['e__title'].':'."\n".'https://'.$domain_url.'/@'.$es_discoverer[0]['e__id']."\n\n".
                                $u_list_name.
                                $u_list_phone
                            );
     * */


}