<?php

if(!isset($_GET['hashtagterm'])){
    die('Missing Hashtag ID hashtagterm');
}


//Generate list & settings:
$hashtag_settings = hashtag_settings($_GET['hashtagterm'], true);
echo '<h1 class="no-print">' . view_hashtag_title($hashtag_settings['i']) . '</h1>';


if(!isset($hashtag_settings['list_config'][34513]) || !count($hashtag_settings['list_config'][34513])){
    die('Missing Pin @34513');
}


foreach($this->Chains->read(array(
    'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
    'chainhandleinput IN (' . join(',', $hashtag_settings['list_config'][34513]) . ')' => null, //Active Writes
), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC')) as $chain_i){

    $hashtag_settings = hashtag_settings($chain_i['hashtagterm'], true);
    if(!count($hashtag_settings['query_string_filtered'])){
        continue;
    }

    echo '<div class="this_frame">';
    echo '<h3 style="margin-top: 55px;"><a href="'.view_memory(42903,33286).$chain_i['hashtagterm'].'">'.view_hashtag_title($chain_i).'</a> ['.count($hashtag_settings['query_string_filtered']).' Total]</h3>';
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';
    echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
    foreach($hashtag_settings['query_string_filtered'] as $count => $x){
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
