<?php

//Called when the paypal payment is complete:
if(isset($_POST['payment_status']) && isset($_POST['item_number'])){

    $item_numbers = array();
    $completion_status = array();
    $is_good = false;

    //Log New Payment:
    $item_parts = explode(' ', $_POST['item_number']);

    $item_numbers['hashtag_target'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[0])) : false ));
    $item_numbers['hashtag_destination'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[1])) : trim($item_parts[0]) ));
    $item_numbers['handle_wesbite'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 2 : 1 )])));
    $item_numbers['handle_handle'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 3 : 2 )])));

    //Fetch Objects based on handles:
    $handle_sessions = $this->Handles->read(array(
        'LOWER(handleterm)' => $item_numbers['handle_handle'],
    ));
    $website_es = $this->Handles->read(array(
        'LOWER(handleterm)' => $item_numbers['handle_wesbite'],
    ));
    $next_is = $this->Hashtags->read(array(
        'LOWER(hashtagterm)' => $item_numbers['hashtag_destination'],
    ));
    $target_is = ($item_numbers['hashtag_target'] ? $this->Hashtags->read(array(
        'LOWER(hashtagterm)' => $item_numbers['hashtag_target'],
    )) : false);


    if(count($handle_sessions) && count($next_is)) {

        $is_pending = ($_POST['payment_status']=='Pending');
        $is_good = true;

        //Is the payment amount greater than zero?
        if(doubleval(( strlen($_POST['payment_gross']) ? $_POST['payment_gross'] : $_POST['mc_gross'])) > 0){

            //Paid:
            $chainhandletype = ( $is_pending ? 35572 /* Pending Payment */ : 26595 );

            //Log Payment:
            $completion_status = $this->Chains->hashtag_discovered($chainhandletype, $handle_sessions[0]['handleid'], ( isset($target_is[0]['hashtagid']) ? $target_is[0]['hashtagid'] : 0 ), $next_is[0], array(), array(
                'chainkey' => intval($_POST['quantity']),
                'chainvalue' => $_POST,
            ));

        } else {

            $chainhandletype = ( $is_pending ? 39597 /* Pending Refund */ : 31967 );

            //Find issued tickets:
            $original_payment = $this->Chains->read(array(
                'chainhandletype' => 26595,
                'chainhandlecreator' => $handle_sessions[0]['handleid'],
                'chainhashtaginput' => $next_is[0]['hashtagid'],
            ));

            //Log Refund:
            $completion_status = $this->Chains->hashtag_discovered($chainhandletype, $handle_sessions[0]['handleid'], ( isset($target_is[0]['hashtagid']) ? $target_is[0]['hashtagid'] : 0 ), $next_is[0], array(), array(
                'chainkey' => (-1 * ( isset($original_payment[0]['chainkey']) ? $original_payment[0]['chainkey'] : 1 )),
                'chainvalue' => $_POST,
                'chainhandledomain' => ( isset($original_payment[0]['chainhandledomain']) && $original_payment[0]['chainhandledomain']>0 ? $original_payment[0]['chainhandledomain'] : 0 ),
            ));

        }
    }
} else {
    echo 'No data from Paypal detected';
}
