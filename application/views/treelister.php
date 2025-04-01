<?php

if(!isset($_GET['ideahashtag'])){
    die('Missing Idea ID ideahashtag');
}


//Generate list & settings:
$list_settings = list_settings($_GET['ideahashtag'], true);
echo '<h1 class="no-print">' . view__idea_title($list_settings['i']) . '</h1>';


if(!count($list_settings['list_config'][34513])){
    die('Missing Pin Link @34513');
}


foreach($this->Menchledger->fetch(array(
    'linktype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
    'linkup IN (' . join(',', $list_settings['list_config'][34513]) . ')' => null, //Active Writes
), array('linkright'), 0, 0, array('linknumber' => 'ASC')) as $link_i){

    $list_settings = list_settings($link_i['ideahashtag'], true);
    if(!count($list_settings['query_string_filtered'])){
        continue;
    }

    echo '<div class="this_frame">';
    echo '<h3 style="margin-top: 55px;"><a href="'.view__memory(42903,33286).$link_i['ideahashtag'].'">'.view__idea_title($link_i).'</a> ['.count($list_settings['query_string_filtered']).' Total]</h3>';
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';
    echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
    foreach($list_settings['query_string_filtered'] as $count => $x){
        echo '<td><div class="this_name">'.$x['extension_name'].'</div></td>';
        if(fmod($count,3)==2){
            echo '</tr><tr class="panel-title down-border" style="font-weight:bold !important;">';
        }
    }
    echo '</tr>';
    echo '</table>';
    echo '</div>';

}

?>

<style>
    .this_name { padding: 8px !important; font-size:1.3em; }
    .this_frame {
        page-break-inside: avoid;
    }
</style>
