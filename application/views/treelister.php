<?php

if(!isset($_GET['ideahashtag'])){
    die('Missing Idea ID ideahashtag');
}


//Generate list & settings:
$idea_settings = idea_settings($_GET['ideahashtag'], true);
echo '<h1 class="no-print">' . view_idea_title($idea_settings['i']) . '</h1>';


if(!count($idea_settings['list_config'][34513])){
    die('Missing Pin Link @34513');
}


foreach($this->Menchledger->fetch(array(
    'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
    'linkplayerup IN (' . join(',', $idea_settings['list_config'][34513]) . ')' => null, //Active Writes
), array('linkidearight'), 0, 0, array('linknumber' => 'ASC')) as $link_i){

    $idea_settings = idea_settings($link_i['ideahashtag'], true);
    if(!count($idea_settings['query_string_filtered'])){
        continue;
    }

    echo '<div class="this_frame">';
    echo '<h3 style="margin-top: 55px;"><a href="'.view_memory(42903,33286).$link_i['ideahashtag'].'">'.view_idea_title($link_i).'</a> ['.count($idea_settings['query_string_filtered']).' Total]</h3>';
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';
    echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
    foreach($idea_settings['query_string_filtered'] as $count => $x){
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
