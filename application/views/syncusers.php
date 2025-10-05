<?php

$max_load = 9999999;
boost_power();
$mentions = $this->config->item('users___13550');
$ideas = $this->config->item('users___4486');
$count = 0;


$missing = array(1720491, 2107851, 1738391, 2107409, 2108190, 1731975, 2109028, 2109913, 2106554, 1720726, 2110780, 1722073, 2034834, 2111638, 1701086, 1699799, 1726562, 1715635, 2115733, 1991586, 2118295, 1827387, 1830456, 1830334, 1928295, 1928338, 1931066, 2099955, 1731432, 1731224, 1725968, 1675983, 1725289, 1730771, 1717247, 1721163, 1722084, 1720475, 1723178, 1724956, 1730249, 1743346, 2099456, 2105452, 1716594, 1717720, 1716429, 1740030, 2100705, 2100228, 2098839, 2099123, 1719872, 1721454, 1991829, 2105175, 1740064, 2103012, 1717509, 2119027, 2118872, 1715915, 1742061, 2118504, 2118249, 2118228, 1701952, 1725979, 1743360, 1705285, 1722323, 1685094, 1726682, 1717089, 1674320, 1730480, 1679446, 1717235, 1690459, 1725529, 2038134, 1720909, 1674951, 1687226, 1727962, 1702420, 1691764, 2115585, 1671805, 1672642, 1674365, 1687803, 1735363, 2113506, 2111589, 1729849, 1729796, 1718098, 2103682, 2103188, 2101422, 2101083, 1738597, 1013215, 39686, 1673926, 1677534, 1682139, 1683380, 1683806, 1686405, 1688688, 1688869, 1689776, 1691349, 1693706, 1695044, 1696126, 1681390, 1699389, 1700298, 1710489, 1710932, 1714781, 1715539, 1716144, 1716993, 1717040, 1718309, 1718620, 1718892, 1718948, 1719135, 1719146, 1720261, 1721714, 1723090, 1724389, 1724691, 1725196, 1725540, 1725893, 1726211, 1726437, 1726572, 1727340, 1728933, 1730015, 1731242, 1733620, 1733920, 1734166, 1735139, 1735569, 2049443, 1991913, 1779463, 1929763, 1844151, 1834811, 1834728, 1830219, 1829115, 1739251, 1735484, 1735126, 1734775, 1731339, 1727387, 1726508, 1725832, 1725758, 1724915, 1724902, 1723856, 1723126, 1720846, 1720502, 1717078, 1715317, 1696642, 1706545, 1703811, 1701903, 1697265, 1691207, 1695477, 1693758, 1688380, 1684269, 1681854, 1677916, 26326, 28365, 27820, 29036, 1527181, 1172764, 38592, 26469, 32519, 31123, 14602, 14681, 28764, 33970, 30104, 27997, 26308, 43979, 44332, 43136, 43873, 43957, 43556, 43348, 35666, 120, 628, 674, 676, 685, 686, 690, 705, 1017, 1024, 1032, 1071, 1079, 1092, 1093, 1254, 2701, 3202, 3435, 4546, 4585, 4761, 6104, 6117, 6254, 6265, 7519, 7645, 7693, 7698, 7699, 7700, 7714, 7716, 7793, 10581, 10885, 11957, 12108, 12118, 12190, 12323, 12329, 12332, 12353, 12354, 12362, 12380, 12455, 12504, 12505, 12748, 12836, 12900, 12992, 13013, 13320, 13448, 13517, 13546, 13547, 13565, 13576, 13642, 13643, 13646, 13653, 13654, 13661, 13662, 13666, 13667, 13675, 13676, 13678, 13691, 13696, 13705, 13774, 13780, 13821, 13858, 13891, 13893, 13957, 14029, 14030, 14045, 14046, 14052, 14056, 14057, 14058, 14392, 14441, 14442, 14443, 14444, 14445, 14446, 14447, 14448, 14449, 14458, 14522, 14523, 14535, 14536, 14539, 14545, 14560, 14561, 14567, 14568, 14578, 14650, 14725, 20412, 20414, 20419, 26002, 26036, 26070, 26140, 26169, 26205, 26208, 26263, 26267, 26294, 26400, 26662, 26756, 26760, 26800, 26933, 27013, 27234, 27299, 27424, 27998, 28193, 28194, 28564, 28620, 28655, 28677, 28680, 28701, 28704, 28705, 28706, 28711, 28712, 28720, 28721, 28823, 28927, 29113, 29153, 29237, 29384, 29446, 29590, 30355, 30449, 30761, 30803, 30888, 30889, 30897, 30936, 30960, 30961, 31046, 31047, 31116, 31133, 31134, 31187, 31763, 32062, 32509, 32552, 33681, 34864, 35188, 38704, 38764, 40897, 40938, 40939, 42249, 27045, 36476, 30938, 27497, 29569, 30119);


$found = 0;
foreach($this->Users->read(array(
    'userid IN (' . join(',', $missing) . ')' => null,
), 0) as $user){
    $found++;
    echo '@'.$user['userhandle'].' ('.$user['userid'].') ';
}

echo '<hr />NEW:: '.$found.'/'.count($missing).'/'.count($this->Users->read(array(
        'userid > 0' => null,
    ), 0)).' found in db2';
die();

$creators = array();
$missing = array();
$chains = 0;
$valid = 0;
$nochain = 0;
$nocache = 0;
foreach($this->Chains->read(array(
    'chainvoid' => 0,
), array(), 0) as $x){

    $chains++;

    if(in_array(intval($x['chainusercreator']), $creators)){
       continue;
    }

    array_push($creators, intval($x['chainusercreator']));

    //Validate:
    if(!count($this->Chains->read(array(
        'chainusertype' => 12274,
        'chainuserinput' => $x['chainusercreator'],
    ), array(), 1))) {

        $nochain++;
        array_push($missing, intval($x['chainusercreator']));

    }

    if(!count($this->Users->read(array(
        'userid' => $x['chainusercreator'],
    )))){

        $nocache++;
        if(!in_array(intval($x['chainusercreator']), $missing)){
            array_push($missing, intval($x['chainusercreator']));
        }

    } else {

        $valid++;

    }

}

echo count($creators).' Unique creators in '.$chains.' chains: '.$nochain.' nochain,'.$nocache.' nocache & '.$valid.' valid<hr />'.join( ', ', $missing);

die();

//Translator
$table = '<table class="table table-sm table-striped stats-table mini-stats-table" border="1">';

//USER
$stats = array(
    'users_all' => 0,
    'users_delete' => 0,
    'users_orphan' => 0,
    'users_void' => 0,
    'users_bio' => 0,
    'users_creaetor_not_found' => 0,
    'users_void_cachevalid' => 0,
    'users_valid_cachevoid' => 0,
    'cachevalid_chainvoid' => 0,
);

//First remove cache items not found on chain:
foreach($this->Users->read(array(
    'userid >' => 0,
)) as $e){
    if(!count($this->Chains->read(array(
        'chainusertype' => 12274,
        'chainuserinput' => $e['userid'],
    )))){
        $this->db->query("DELETE FROM users WHERE userid = " . $e['userid'] . ";");
        $stats['cachevalid_chainvoid']++;
    }
}

foreach ($this->Chains->read(array(
    'chainusertype' => 12274,
), array(), $max_load, 0, array('chainid' => 'ASC')) as $x) {

    $count++;
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
        $this->db->query("DELETE FROM ideachains WHERE chainid = " . $x['chainid'] . ";");
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
        $this->db->query("DELETE FROM ideachains WHERE chainid = " . $x['chainid'] . ";");
        $stats['users_delete']++;
    }

    if(!$total_links){
        $stats['users_orphan']++;
    }

    $table .= '<tr>';
    $table .= '<td>' . $x['chainid'] . '<br />V' . $x['chainvoid'] . '/' . $count . '/' .
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

if(isset($stats)){
    print_r($stats);
}

echo $table;
echo '<style> 

    img { max-width: 100% !important; } 
    
    .container {
        max-width: calc(100% - 16px) !important;
    }
        
</style>';