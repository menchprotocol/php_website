<?php

//USER
$stats = array(
    //Cache users
    'cache_all' => 0,
    'cache_notonchain' => 0,
    'cache_addedtochain' => 0,

    //On Chain users
    'chain_all' => 0,
    'users_onchain' => 0,
    'users_bio' => 0,

    //Validate 5+2 fields users & posts:
    'count_users' => 0,
    'count_users_missing' => 0,

    'message' => '',
    'unique_users' => array(),
    'unique_users_missing' => array(),

);


//First start with cache and see what might be missing:
foreach ($this->Users->read(array(
    'userid >' => 0,
), 0) as $user) {

    $stats['cache_all']++;

    if (!count($this->Chains->read(array(
        'chainvoid >=' => 0,
        'chainusertype' => 12274,
        'chainuserinput' => $user['userid'],
    ), array(), 1))) {

        $stats['cache_notonchain']++;

        $new_x = $this->Chains->create(array(
            'chainusertype' => 12274,
            'chainusercreator' => $user['userid'],
            'chainuserinput' => $user['userid'],
            'chainvalue' => "@" . $user['userhandle']
                . "\n" . $user['username']
                . "\n" . $user['usercover']
                . "\n" . $user['userbio']
        ));

        if ($new_x['chainid'] > 0) {

            $stats['cache_addedtochain']++;
            $stats['message'] .= "@" . $user['userhandle'] . " Added to Chain\n";

            //Edit ID
            if (!count($this->Chains->read(array(
                'chainvoid >=' => 0,
                'chainid' => $new_x['chainid'],
            ), array(), 1))) {
                $this->db->query("UPDATE ideachains SET chainid = " . $user['userid'] . " WHERE chainid = " . $new_x['chainid'] . ";");
            }
        }
    }
}


//Scan all users on chain:
foreach ($this->Chains->read(array(
    'chainvoid' => 0,
), array(), 0) as $x) {

    $stats['chain_all']++;

    //User itself:
    if($x['chainusertype'] == 12274 && $x['chainuserinput'] > 0) {
        $stats['users_onchain']++;
        array_push($stats['unique_users'], intval($x['chainuserinput']));
        $user_validate = user_validate($x['chainuserinput'], $x['chainid']);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
        }
    }

    //5x User references on chain:
    if ($x['chainuserdomain']>0 && !in_array(intval($x['chainuserdomain']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainuserdomain']));
        $user_validate = user_validate($x['chainuserdomain'], $x['chainid']);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
        }
    }
    if ($x['chainusertype']>0 && !in_array(intval($x['chainusertype']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainusertype']));
        $user_validate = user_validate($x['chainusertype'], $x['chainid']);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
        }
    }
    if ($x['chainusercreator']>0 && !in_array(intval($x['chainusercreator']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainusercreator']));
        $user_validate = user_validate($x['chainusercreator'], $x['chainid']);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
        }
    }
    if ($x['chainuserinput']>0 && !in_array(intval($x['chainuserinput']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainuserinput']));
        $user_validate = user_validate($x['chainuserinput'], $x['chainid']);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
        }
    }
    if ($x['chainuseroutput']>0 && !in_array(intval($x['chainuseroutput']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainuseroutput']));
        $user_validate = user_validate($x['chainuseroutput'], $x['chainid']);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
        }
    }
}




//Sync bio once:
foreach($this->Chains->read(array(
    'chainuserinput IN (' . join(',', array(42628, 11035)) . ')' => null, //USER CHAINS
    'LENGTH(chainvalue)>0' => null,
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
), array('chainuseroutput'), 0) as $x){
    if(!strlen($x['userbio']) && strlen(trim($x['chainvalue']))){
        $stats['users_bio']++;
        $this->Users->update($x['userid'], array(
            'userbio' => $x['userbio'],
        ), $x['userid']);
    }
}


$stats['count_users'] = count($stats['unique_users']);
$stats['count_users_missing'] = count($stats['unique_users_missing']);
unset($stats['unique_users']);


view_json($stats);