<?php

$delete_missing = true;
$stats = array(
    //Cache users
    'users_oncache' => 0,
    'users_oncache_synced' => 0,
    'users_oncache_notonchain' => 0,
    'users_oncache_chainadded' => 0,

    'users_oncache_hashtags' => 0,
    'users_oncache_hashtags_duplicate' => 0,
    'users_oncache_hashtags_numeric' => 0,
    'users_oncache_hashtags_numeric_validid' => 0,

    //On Chain users
    'chain_all' => 0,
    'users_onchain' => 0,
    'users_chain_deleted' => 0,

    //Validate user references on chain
    'count_users' => 0,
    'count_users_missing' => 0,

    'message' => '',
    'unique_users' => array(),
    'unique_users_missing' => array(),
);



//Sync handles:
$users_unique_hashtags = array();
foreach ($this->Users->read(array(
    'userid >' => 0,
), $_GET['limit'], 0, array('userid' => 'ASC')) as $user) {

    echo '@'.$user['userhandle'].' '.$user['userid'];
    if (in_array(strtolower($user['userhandle']), $users_unique_hashtags)) {
        //Remove:
        echo ' [DUPLICATE]';
        $stats['users_oncache_hashtags_duplicate']++;
        //$this->db->query("DELETE FROM ideachains WHERE (chainuserinput = " . $user['userid'] . " OR chainuseroutput = " . $user['userid'] . ");");
        //$this->db->query("DELETE FROM users WHERE userid = " . $user['userid'] . ";");
    } else {
        if(is_numeric($user['userhandle'])) {
            $stats['users_oncache_hashtags_numeric']++;
            if(count($this->Posts->read(array(
                'userid' => $user['userhandle'],
            ), 1))){
                $stats['users_oncache_hashtags_numeric_validid']++;
            }
        }
        array_push($users_unique_hashtags, strtolower($user['userhandle']));
        $stats['users_oncache_hashtags']++;
    }
    echo "\n";
}
view_json($stats);
die();


//First start with cache and see what might be missing:
foreach ($this->Users->read(array(
    'userid >' => 0,
), 0) as $user) {

    $stats['users_oncache']++;
    $cache_chains = $this->Chains->read(array(
        'chainusertype' => 12274,
        'chainuserinput' => $user['userid'],
    ), array(), 1);

    if (!count($cache_chains)) {

        $stats['users_oncache_notonchain']++;

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

            $stats['users_oncache_chainadded']++;

            $stats['message'] .= "@" . $user['userhandle'] . " Added to Chain\n";

            //Edit ID
            if (!count($this->Chains->read(array(
                'chainvoid >=' => 0,
                'chainid' => $user['userid'],
            ), array(), 1))) {
                $this->db->query("UPDATE ideachains SET chainid = " . $user['userid'] . " WHERE chainid = " . $new_x['chainid'] . ";");
            }
        }

    } else {

        $update_cache = array();
        if ($cache_chains[0]['chainusercreator'] != $user['usercreator']) {
            $update_cache['usercreator'] = $cache_chains[0]['chainusercreator'];
        }

        //Update if there is anything:
        if (count($update_cache) && $this->Users->update($user['userid'], $update_cache)) {
            $stats['users_oncache_synced']++;
        }

    }
}


//Scan all users on chain:
foreach ($this->Chains->read(array(
    'chainvoid' => 0,
), array(), 0) as $x) {

    $stats['chain_all']++;

    //User itself:
    if ($x['chainusertype'] == 12274 && $x['chainuserinput'] > 0) {
        $stats['users_onchain']++;
        array_push($stats['unique_users'], intval($x['chainuserinput']));
        $user_validate = user_validate($x['chainuserinput'], $x['chainid'], $delete_missing);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
            $stats['users_chain_deleted'] += $user_validate['chain_deletes'];
        }
    }

    //5x User references on chain:
    if ($x['chainuserdomain'] > 0 && !in_array(intval($x['chainuserdomain']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainuserdomain']));
        $user_validate = user_validate($x['chainuserdomain'], $x['chainid'], $delete_missing);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
            $stats['users_chain_deleted'] += $user_validate['chain_deletes'];
        }
    }
    if ($x['chainusertype'] > 0 && !in_array(intval($x['chainusertype']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainusertype']));
        $user_validate = user_validate($x['chainusertype'], $x['chainid'], $delete_missing);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
            $stats['users_chain_deleted'] += $user_validate['chain_deletes'];
        }
    }
    if ($x['chainusercreator'] > 0 && !in_array(intval($x['chainusercreator']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainusercreator']));
        $user_validate = user_validate($x['chainusercreator'], $x['chainid'], $delete_missing);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
            $stats['users_chain_deleted'] += $user_validate['chain_deletes'];
        }
    }
    if ($x['chainuserinput'] > 0 && !in_array(intval($x['chainuserinput']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainuserinput']));
        $user_validate = user_validate($x['chainuserinput'], $x['chainid'], $delete_missing);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
            $stats['users_chain_deleted'] += $user_validate['chain_deletes'];
        }
    }
    if ($x['chainuseroutput'] > 0 && !in_array(intval($x['chainuseroutput']), $stats['unique_users'])) {
        array_push($stats['unique_users'], intval($x['chainuseroutput']));
        $user_validate = user_validate($x['chainuseroutput'], $x['chainid'], $delete_missing);
        if (!$user_validate['status']) {
            array_push($stats['unique_users_missing'], $user_validate);
            $stats['users_chain_deleted'] += $user_validate['chain_deletes'];
        }
    }
}


$stats['count_users'] = count($stats['unique_users']);
$stats['count_users_missing'] = count($stats['unique_users_missing']);
unset($stats['unique_users']);


view_json($stats);