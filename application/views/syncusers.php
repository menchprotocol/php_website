<?php

boost_power();

/*

$total = 0;
$update = 0;
foreach($this->Chains->read(array(
    'chainuserinput' => 42628,
    'LENGTH(chainvalue)>0' => null,
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
), array('chainuseroutput'), 0) as $x){
    $total++;
    if(!strlen($x['userbio'])){
        $update++;
    }
}
echo $update.'/'.$total.' Bios Updated.<hr />';

*/

//USER
$stats = array(
    'count' => 0,

    //Cache users
    'cache_all' => 0,
    'cache_notonchain' => 0,
    'cache_addedtochain' => 0,

    //Validate 5+2 fields:
    'unique_chainusercreator' => array(),
    'unique_chainusercreator_missing' => array(),
    'unique_chainuserdomain' => array(),
    'unique_chainuserdomain_missing' => array(),
    'unique_chainusertype' => array(),
    'unique_chainusertype_missing' => array(),
    'unique_chainuserinput' => array(),
    'unique_chainuserinput_missing' => array(),
    'unique_chainuseroutput' => array(),
    'unique_chainuseroutput_missing' => array(),
    'unique_chainpostinput' => array(),
    'unique_chainpostinput_missing' => array(),
    'unique_chainpostonput' => array(),
    'unique_chainpostonput_missing' => array(),

    //On Chain users
    'users_all' => 0,
    'users_delete' => 0,
    'users_orphan' => 0,
    'users_void' => 0,
    'users_bio' => 0,
    'users_creaetor_not_found' => 0,
    'users_void_cachevalid' => 0,
    'users_valid_cachevoid' => 0,
    'cachevalid_chainvoid' => 0,

    'message' => '',
);


//First start with cache and see what might be missing:
foreach($this->Users->read(array(
    'userid >' => 0,
), 0) as $user){

    $stats['cache_all']++;

    if(!count($this->Chains->read(array(
        'chainvoid >=' => 0,
        'chainusertype' => 12274,
        'chainuserinput' => $user['userid'],
    ), array(), 1))){

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

        if($new_x['chainid'] > 0){

            $stats['cache_addedtochain']++;
            $stats['message'] .= "@".$user['userhandle']." Added to Chain\n";

            //Edit ID
            if(!count($this->Chains->read(array(
                'chainvoid >=' => 0,
                'chainid' => $new_x['chainid'],
            ), array(), 1))){
                $this->db->query("UPDATE ideachains SET chainid = " . $user['userid'] . " WHERE chainid = " . $new_x['chainid'] . ";");
            }
        }
    }
}


//Validate Chain Sources:
$anything = array();
$chains = 0;
$valid = 0;
$nochain = 0;
$nocache = 0;
$noanything = 0;

foreach($this->Chains->read(array(
    'chainvoid' => 0,
), array(), 0) as $x){

    $chains++;

    if(!in_array(intval($x['chainusercreator']), $stats['unique_chainusercreator'])){

        array_push($stats['unique_chainusercreator'], intval($x['chainusercreator']));

        $anything_count = count($this->Chains->read(array(
            'chainid !=' => $x['chainid'],
            '(chainusercreator='.$x['chainusercreator'].' OR chainuserinput='.$x['chainusercreator'].' OR chainuseroutput='.$x['chainusercreator'].')' => null,
        ), array(), 0));

        $onchain = count($this->Chains->read(array(
            'chainusertype' => 12274,
            'chainuserinput' => $x['chainusercreator'],
        ), array(), 1));

        $oncache = count($this->Users->read(array(
            'userid' => $x['chainusercreator'],
        )));

        if(!$anything_count) {
            array_push($anything, intval($x['chainusercreator']));
            $noanything++;
        } elseif(!$onchain || !$oncache) {
            echo '@'.$x['chainusercreator'].' ['.$anything_count.']<br />';
        }

        //Validate:
        if(!$onchain) {
            $nochain++;
            if(!in_array(intval($x['chainusercreator']), $anything)){
                array_push($stats['unique_chainusercreator_missing'], intval($x['chainusercreator']));
            }
        }

        if(!$oncache){
            $nocache++;
            if(!in_array(intval($x['chainusercreator']), $anything) && !in_array(intval($x['chainusercreator']), $stats['unique_chainusercreator_missing'])){
                array_push($stats['unique_chainusercreator_missing'], intval($x['chainusercreator']));
            }
        }

        if(!in_array(intval($x['chainusercreator']), $anything) && !in_array(intval($x['chainusercreator']), $stats['unique_chainusercreator_missing'])){
            $valid++;
        }

    }








}


die();

//Translator
$table = '<table class="table table-sm table-striped stats-table mini-stats-table" border="1">';

//First remove cache items not found on chain:
foreach($this->Users->read(array(
    'userid >' => 0,
)) as $e){
    if(!count($this->Chains->read(array(
        'chainusertype' => 12274,
        'chainuserinput' => $e['userid'],
    )))){
        //$this->db->query("DELETE FROM users WHERE userid = " . $e['userid'] . ";");
        $stats['cachevalid_chainvoid']++;
    }
}

foreach ($this->Chains->read(array(
    'chainusertype' => 12274,
), array(), 0, 0, array('chainid' => 'ASC')) as $x) {

    $es_cache = $this->Users->read(array(
        'userid' => $x['chainuserinput'],
    ));
    if(!count($es_cache)){
        $es_cache = $this->Users->read(array(
            'userid' => $x['chainuserinput'],
        ));
    }
    $es = $this->Users->read(array(
        'userid' => $x['chainusercreator'],
    ));

    $stats['users_all']++;
    if ($x['chainvoid'] > 0) {
        $stats['users_void']++;
    } elseif (!count($es)) {

        $stats['users_creaetor_not_found']++;

        //Update to Shervin:
        $x['chainusercreator'] = 1;
        $this->db->query("UPDATE ideachains SET chainusercreator=".$x['chainusercreator']." WHERE chainid = " . $x['chainid'] . ";");
        if(count($es_cache)){
            $this->db->query("UPDATE users SET usercreator=".$x['chainusercreator']." WHERE userid = " . $es_cache[0]['userid'] . ";");
        }
        $es = $this->Users->read(array(
            'userid' => $x['chainusercreator'],
        ));

    }
    if ($x['chainvoid'] > 0 && count($es_cache)) {
        $stats['users_void_cachevalid']++;
    }
    if (!count($es_cache)) {
        //$this->db->query("DELETE FROM ideachains WHERE chainid = " . $x['chainid'] . ";");
        $stats['users_valid_cachevoid']++;
    }

    //Orphan?
    $total_links = count($this->Chains->read(array(
        'chainid !=' => $x['chainid'],
        '(chainuserdomain='.$x['chainuserinput'].' OR chainusertype='.$x['chainuserinput'].' OR chainusercreator='.$x['chainuserinput'].' OR chainuserinput='.$x['chainuserinput'].' OR chainuseroutput='.$x['chainuserinput'].')' => null,
    )));

    //Fetch from Cache table:
    if (count($es_cache)) {
        foreach ($this->Chains->read(array(
            'LENGTH(chainvalue) > 0' => null,
            'chainuserinput' => 11035,
            'chainuseroutput' => $x['chainuserinput'],
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array(), 0, 0) as $social_chain) {
            if(strlen($social_chain['chainvalue'])>0 && !strlen($es_cache[0]['userbio'])){
                $stats['users_bio']++;
                $this->Users->update($es_cache[0]['userid'], array(
                    'userbio' => trim($social_chain['chainvalue']),
                ));
            }
        }
        $userbio = '@' . $es_cache[0]['userhandle'] . "\n" . $es_cache[0]['username'] . "\n" . $es_cache[0]['usercover'];
    } else {
        $userbio = '@???' . $x['chainvalue'] . "\n" . $x['chainvalue'] . "\nfar fa-user";
    }

    //Append Bio if any



    $delete = !$total_links || $x['chainvoid'] > 0;
    if ($delete) {
        //$this->db->query("DELETE FROM ideachains WHERE chainid = " . $x['chainid'] . ";");
        $stats['users_delete']++;
    }

    if(!$total_links){
        $stats['users_orphan']++;
    }

    $table .= '<tr>';
    $table .= '<td>' . $x['chainid'] . '<br />V' . $x['chainvoid'] . '/' . $stats['users_all'] . '/' .
        ($delete ? '[DELETED USER]' : '') .
        ( !$total_links ? '[ORPHAN]' : '') .
        ($x['chainvoid'] > 0 ? '[VOID]' : '') .
        (!count($es) ? '[users_creaetor_not_found]' : '') .
        (!count($es_cache) ? '[users_valid_cachevoid]' : '') .
        '</td>';
    $table .= '<td>T@' . $x['chainusertype'] . '<br />C@' . $x['chainusercreator'] . '<br />@' . $x['chainuserinput'] . '</td>';
    $table .= '<td><div style="max-width:233px;">' . nl2br(trim(htmlentities($userbio))) . '</div></td>';
    $table .= '</tr>';
}


$table .= '</table>';

print_r($stats);

echo $table;
echo '<style> 

    img { max-width: 100% !important; } 
    
    .container {
        max-width: calc(100% - 16px) !important;
    }
        
</style>';