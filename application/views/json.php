<?php

//view_json($this->Menchledger->tree_full_history($focus_i, $focus_e['playerid']));

$count = 0;
$duplicate = 0;
$previous = false;

echo '<table>';
foreach($this->Menchledger->fetch(array(
    'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
), array(), 0, 0, array(
    'linkcreator' => 'ASC',
    'linktype' => 'ASC',
    'linkright' => 'ASC',
    'linkleft' => 'ASC',
    'linktext' => 'ASC',
    'linkid' => 'DESC',
)) as $discover){

    $count++;
    if($previous && $previous['linktype']==$discover['linktype'] && $previous['linkcreator']==$discover['linkcreator'] && $previous['linkright']==$discover['linkright'] && $previous['linkleft']==$discover['linkleft'] && $previous['linktext']==$discover['linktext']){
        $duplicate++;
        echo '<tr><td>'.$previous['linktype'].'</td><td>'.$previous['linktime'].'</td><td>'.$previous['linkcreator'].'</td><td>'.$previous['linkright'].'</td><td>'.$previous['linkleft'].'</td><td>'.$previous['linktext'].'</td><td>'.$previous['linktype'].'</td><td>'.$previous['linktype'].'</td></tr>';
        echo '<tr style="background-color: #CCC;"><td>'.$discover['linktype'].'</td><td>'.$discover['linktime'].'</td><td>'.$discover['linkcreator'].'</td><td>'.$discover['linkright'].'</td><td>'.$discover['linkleft'].'</td><td>'.$discover['linktext'].'</td><td>'.$discover['linktype'].'</td><td>'.$discover['linktype'].'</td></tr>';

        $this->db->query("DELETE FROM menchledger WHERE linkid=".$discover['linkid'].";");

    }

    $previous = $discover;
}

echo '</table>';
echo $duplicate.'/'.$count.' are duplicate';