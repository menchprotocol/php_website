<?php

if(!isset($_GET['posthashtag'])){
    die('Missing Post ID posthashtag');
}


//Generate list & settings:
$post_settings = post_settings($_GET['posthashtag'], true);
echo '<h1 class="no-print">' . view_post_title($post_settings['i']) . '</h1>';


if(!isset($post_settings['list_config'][34513]) || !count($post_settings['list_config'][34513])){
    die('Missing Pin @34513');
}


foreach($this->Ideachains->read(array(
    'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
    'chainuserinput IN (' . join(',', $post_settings['list_config'][34513]) . ')' => null, //Active Writes
), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC')) as $chain_i){

    $post_settings = post_settings($chain_i['posthashtag'], true);
    if(!count($post_settings['query_string_filtered'])){
        continue;
    }

    echo '<div class="this_frame">';
    echo '<h3 style="margin-top: 55px;"><a href="'.view_memory(42903,33286).$chain_i['posthashtag'].'">'.view_post_title($chain_i).'</a> ['.count($post_settings['query_string_filtered']).' Total]</h3>';
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';
    echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
    foreach($post_settings['query_string_filtered'] as $count => $x){
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
