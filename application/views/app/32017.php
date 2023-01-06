<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])) {

    //List this members discoveries so they can choose:
    echo '<div>Enter e__id to begin...</div><br />';

} else {

    //Fetch All Tickets of Source:
    $all_ticket_count = 0;
    $all_ticket_transactions = 0;
    $paid_ticket_types = 0;
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        'x__up' => $_GET['e__id'], //Time Starts
    ), array('x__right')) as $ticket_type){

        //Count Tickets:
        $ticket_count = 0;
        $ticket_transactions = 0;
        $ticket_holder_ui = '';


        $ticket_holder_ui .= '<h3>'.$ticket_type['i__title'].'</h3>';
        $ticket_holder_ui .= '<table class="table table-sm table-striped stats-table mini-stats-table" style="margin-bottom: 34px;">';
        $ticket_holder_ui .= '<tr style="font-weight: bold;">';
        $ticket_holder_ui .= '<th>#</th>';
        $ticket_holder_ui .= '<th>Member</th>';
        $ticket_holder_ui .= '<th style="width: 100px;">Tickets</th>';
        $ticket_holder_ui .= '<th style="width: 100px;">Emailed</th>';
        $ticket_holder_ui .= '<th style="width: 100px;">Checkin</th>';
        $ticket_holder_ui .= '</tr>';

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32014')) . ')' => null, //Ticket Discoveries
            'x__left' => $ticket_type['i__id'],
        ), array('x__source'), 0) as $x){

            $x__metadata = unserialize($x['x__metadata']);
            $this_count = ( (isset($x__metadata['quantity']) && $x__metadata['quantity'] >= 2) ? $x__metadata['quantity'] : 1 );
            $ticket_count += $this_count;
            $ticket_transactions++;
            $x__source = 1;
            $qr_link = 'https://'.get_domain('m__message', $x__source).'/-26560?x__id='.$x['x__id'].'&x__source='.$x['x__source'];

            $ticket_checked_in = $this->X_model->fetch(array(
                'x__reference' => $x['x__id'],
                'x__type' => 32016,
            ), array('x__up'));

            $ticket_holder_ui .= '<tr>';
            $ticket_holder_ui .= '<th>'.$ticket_transactions.'</th>';
            $ticket_holder_ui .= '<th><a href="/@'.$x['e__id'].'"><u>'.$x['e__title'].'</u></a></th>';
            $ticket_holder_ui .= '<td><a href=""><u>'.$this_count.'</u></a></td>';
            $ticket_holder_ui .= '<td></td>';
            $ticket_holder_ui .= '<td>'.( count($ticket_checked_in) ? '<a href="/@'.$ticket_checked_in[0]['e__id'].'" title="Checked-In by '.$ticket_checked_in[0]['e__title'].' about ' . view_time_difference(strtotime($ticket_checked_in[0]['x__time'])) . ' Ago at '.substr($ticket_checked_in[0]['x__time'], 0, 19).' PST">'.view_cover(12274, $ticket_checked_in[0]['e__cover'], true).'</a>' : '' ).'</td>';
            $ticket_holder_ui .= '</tr>';

            if(isset($_GET['send'])){
                $this->X_model->send_dm($x['e__id'], 'Your Atlas Camp NYE eTicket(s) QR Code',
                    'To get your ticket(s) at the door simply open your QR code that will be scanned by our greeting team:'.
                    "\n\n".$qr_link."\n\n".
                    'Anyone with your QR code can check-in and receive your ticket(s).'."\n"
                );
            }

        }

        $ticket_holder_ui .= '<tr style="font-weight: bold;">';
        $ticket_holder_ui .= '<th></th>';
        $ticket_holder_ui .= '<th>Totals</th>';
        $ticket_holder_ui .= '<th>'.$ticket_count.'</th>';
        $ticket_holder_ui .= '<th></th>';
        $ticket_holder_ui .= '<th></th>';
        $ticket_holder_ui .= '</tr>';

        $ticket_holder_ui .= '</table>';

        $all_ticket_count += $ticket_count;
        $all_ticket_transactions += $ticket_transactions;

        if($ticket_transactions>0){
            echo $ticket_holder_ui;
        }

    }

    echo '<hr />';

    echo $all_ticket_count.' Tickets sold in '.$all_ticket_transactions.' Transactions';


}