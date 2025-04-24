<?php

//Called when the paypal payment is complete:
if(isset($_POST['payment_status']) && isset($_POST['item_number'])){

    $item_numbers = array();
    $completion_status = array();
    $is_good = false;

    //Log New Payment:
    $item_parts = explode(' ', $_POST['item_number']);

    $item_numbers['idea_target'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[0])) : false ));
    $item_numbers['idea_destination'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[1])) : trim($item_parts[0]) ));
    $item_numbers['player_wesbite'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 2 : 1 )])));
    $item_numbers['player_player'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 3 : 2 )])));

    //Fetch Objects based on handles:
    $player_sessions = $this->Players->read(array(
        'LOWER(playerhandle)' => $item_numbers['player_player'],
    ));
    $website_es = $this->Players->read(array(
        'LOWER(playerhandle)' => $item_numbers['player_wesbite'],
    ));
    $next_is = $this->Ideas->read(array(
        'LOWER(ideahashtag)' => $item_numbers['idea_destination'],
    ));
    $target_is = ($item_numbers['idea_target'] ? $this->Ideas->read(array(
        'LOWER(ideahashtag)' => $item_numbers['idea_target'],
    )) : false);


    if(count($player_sessions) && count($next_is)) {

        $is_pending = ($_POST['payment_status']=='Pending');
        $is_good = true;

        //Is the payment amount greater than zero?
        if(doubleval(( strlen($_POST['payment_gross']) ? $_POST['payment_gross'] : $_POST['mc_gross'])) > 0){

            //Paid:
            $chainplayertype = ( $is_pending ? 35572 /* Pending Payment */ : 26595 );

            //Log Payment:
            $completion_status = $this->Chains->idea_discovered($chainplayertype, $player_sessions[0]['playerid'], ( isset($target_is[0]['ideaid']) ? $target_is[0]['ideaid'] : 0 ), $next_is[0], array(), array(
                'chainnumber' => intval($_POST['quantity']),
                'chaintext' => $_POST,
            ));

        } else {

            $chainplayertype = ( $is_pending ? 39597 /* Pending Refund */ : 31967 );

            //Find issued tickets:
            $original_payment = $this->Chains->read(array(
                'chainplayertype' => 26595,
                'chainplayercreator' => $player_sessions[0]['playerid'],
                'chainidealeft' => $next_is[0]['ideaid'],
            ));

            //Log Refund:
            $completion_status = $this->Chains->idea_discovered($chainplayertype, $player_sessions[0]['playerid'], ( isset($target_is[0]['ideaid']) ? $target_is[0]['ideaid'] : 0 ), $next_is[0], array(), array(
                'chainnumber' => (-1 * ( isset($original_payment[0]['chainnumber']) ? $original_payment[0]['chainnumber'] : 1 )),
                'chaintext' => $_POST,
                'chainplayerdomain' => ( isset($original_payment[0]['chainplayerdomain']) && $original_payment[0]['chainplayerdomain']>0 ? $original_payment[0]['chainplayerdomain'] : 0 ),
            ));

        }
    }
} else {
    echo 'No data from Paypal detected';
}
