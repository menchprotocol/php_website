<?php

//Called when the paypal payment is complete:
if(isset($_POST['payment_status']) && isset($_POST['item_number'])){

    $item_numbers = array();
    $completion_status = array();

    //Log New Payment:
    $item_parts = explode(' ', $_POST['item_number']);

    $item_numbers['hashtag_target'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[0])) : false ));
    $item_numbers['hashtag_destination'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[1])) : trim($item_parts[0]) ));
    $item_numbers['handle_wesbite'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 2 : 1 )])));
    $item_numbers['handle_handle'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 3 : 2 )])));

    //Fetch Objects based on handles:
    $handle_sessions = $this->Handles->read(array(
        'LOWER(handleterm)' => strtolower($item_numbers['handle_handle']),
    ));
    $website_es = $this->Handles->read(array(
        'LOWER(handleterm)' => strtolower($item_numbers['handle_wesbite']),
    ));
    $next_is = $this->Hashtags->read(array(
        'LOWER(hashtagterm)' => strtolower($item_numbers['hashtag_destination']),
    ));
    $target_is = ($item_numbers['hashtag_target'] ? $this->Hashtags->read(array(
        'LOWER(hashtagterm)' => strtolower($item_numbers['hashtag_target']),
    )) : false);


    if(count($handle_sessions) && count($next_is) && $_POST['payment_status']!='Pending') {

        //Is the payment amount greater than zero?
        if(doubleval(( strlen($_POST['payment_gross']) ? $_POST['payment_gross'] : $_POST['mc_gross'])) > 0){

            //Log Payment:
            $completion_status = $this->Chains->hashtag_discovered(26595, $handle_sessions[0]['handleid'], ( isset($target_is[0]['hashtagid']) ? $target_is[0]['hashtagid'] : 0 ), $next_is[0], array(), array(
                'chainkey' => intval($_POST['quantity']),
                'chainvalue' => $_POST,
            ));

        } else {

            //Find Payment:
            foreach($this->Chains->read(array(
                'chainhandletype' => 26595,
                'chainhandlecreator' => $handle_sessions[0]['handleid'],
                'chainhashtaginput' => $next_is[0]['hashtagid'],
            )) as $paid){
                //Delete payment since its been refunded:
                $this->Chains->delete($paid['chainid'], $handle_sessions[0]['handleid']);
            }

        }
    }

} else {
    echo 'No data from Paypal detected';
}
