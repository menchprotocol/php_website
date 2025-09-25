<?php

//Called when the paypal payment is complete:
if(isset($_POST['payment_status']) && isset($_POST['item_number'])){

    $item_numbers = array();
    $completion_status = array();

    //Log New Payment:
    $item_parts = explode(' ', $_POST['item_number']);

    $item_numbers['post_target'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[0])) : false ));
    $item_numbers['post_destination'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[1])) : trim($item_parts[0]) ));
    $item_numbers['user_wesbite'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 2 : 1 )])));
    $item_numbers['user_user'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 3 : 2 )])));

    //Fetch Objects based on users:
    $user_sessions = $this->Users->read(array(
        'LOWER(userhandle)' => strtolower($item_numbers['user_user']),
    ));
    $website_es = $this->Users->read(array(
        'LOWER(userhandle)' => strtolower($item_numbers['user_wesbite']),
    ));
    $next_is = $this->Posts->read(array(
        'LOWER(posthashtag)' => strtolower($item_numbers['post_destination']),
    ));
    $target_is = ($item_numbers['post_target'] ? $this->Posts->read(array(
        'LOWER(posthashtag)' => strtolower($item_numbers['post_target']),
    )) : false);


    if(count($user_sessions) && count($next_is) && $_POST['payment_status']!='Pending') {

        //Is the payment amount greater than zero?
        if(doubleval(( strlen($_POST['payment_gross']) ? $_POST['payment_gross'] : $_POST['mc_gross'])) > 0){

            //Log Payment:
            $completion_status = $this->Ideachains->post_discovered(26595, $user_sessions[0]['userid'], ( isset($target_is[0]['postid']) ? $target_is[0]['postid'] : 0 ), $next_is[0], array(), array(
                'chainkey' => intval($_POST['quantity']),
                'chainvalue' => $_POST,
            ));

        } else {

            //Find Payment:
            foreach($this->Ideachains->read(array(
                'chainusertype' => 26595,
                'chainusercreator' => $user_sessions[0]['userid'],
                'chainpostinput' => $next_is[0]['postid'],
            )) as $paid){
                //Delete payment since its been refunded:
                $this->Ideachains->delete($paid['chainid'], $user_sessions[0]['userid']);
            }

        }
    }

} else {
    echo 'No data from Paypal detected';
}
