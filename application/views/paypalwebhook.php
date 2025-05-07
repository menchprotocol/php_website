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
    $item_numbers['source_wesbite'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 2 : 1 )])));
    $item_numbers['source_source'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 3 : 2 )])));

    //Fetch Objects based on handles:
    $source_sessions = $this->Sources->read(array(
        'LOWER(sourcehandle)' => $item_numbers['source_source'],
    ));
    $website_es = $this->Sources->read(array(
        'LOWER(sourcehandle)' => $item_numbers['source_wesbite'],
    ));
    $next_is = $this->Ideas->read(array(
        'LOWER(ideahashtag)' => $item_numbers['idea_destination'],
    ));
    $target_is = ($item_numbers['idea_target'] ? $this->Ideas->read(array(
        'LOWER(ideahashtag)' => $item_numbers['idea_target'],
    )) : false);


    if(count($source_sessions) && count($next_is)) {

        $is_pending = ($_POST['payment_status']=='Pending');
        $is_good = true;

        //Is the payment amount greater than zero?
        if(doubleval(( strlen($_POST['payment_gross']) ? $_POST['payment_gross'] : $_POST['mc_gross'])) > 0){

            //Paid:
            $chainsourcetype = ( $is_pending ? 35572 /* Pending Payment */ : 26595 );

            //Log Payment:
            $completion_status = $this->Chains->idea_discovered($chainsourcetype, $source_sessions[0]['sourceid'], ( isset($target_is[0]['ideaid']) ? $target_is[0]['ideaid'] : 0 ), $next_is[0], array(), array(
                'chainkey' => intval($_POST['quantity']),
                'chainvalue' => $_POST,
            ));

        } else {

            $chainsourcetype = ( $is_pending ? 39597 /* Pending Refund */ : 31967 );

            //Find issued tickets:
            $original_payment = $this->Chains->read(array(
                'chainsourcetype' => 26595,
                'chainsourcecreator' => $source_sessions[0]['sourceid'],
                'chainidealeft' => $next_is[0]['ideaid'],
            ));

            //Log Refund:
            $completion_status = $this->Chains->idea_discovered($chainsourcetype, $source_sessions[0]['sourceid'], ( isset($target_is[0]['ideaid']) ? $target_is[0]['ideaid'] : 0 ), $next_is[0], array(), array(
                'chainkey' => (-1 * ( isset($original_payment[0]['chainkey']) ? $original_payment[0]['chainkey'] : 1 )),
                'chainvalue' => $_POST,
                'chainsourcedomain' => ( isset($original_payment[0]['chainsourcedomain']) && $original_payment[0]['chainsourcedomain']>0 ? $original_payment[0]['chainsourcedomain'] : 0 ),
            ));

        }
    }
} else {
    echo 'No data from Paypal detected';
}
