<?php

if(!isset($_GET['i__hashtag'])){
    die('Missing Idea ID i__hashtag');
}


//Generate list & settings:
$list_settings = list_settings($_GET['i__hashtag'], true);
echo '<h1 class="no-print">' . view_i_title($list_settings['i']) . '</h1>';


if(!count($list_settings['list_config'][34513])){
    die('Missing Pin Link @34513');
}


foreach($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
    'x__following IN (' . join(',', $list_settings['list_config'][34513]) . ')' => null, //Active Writes
    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
), array('x__next'), 0, 0, array('x__weight' => 'ASC')) as $link_i){

    $sub_list_settings = list_settings($link_i['i__hashtag'], true);
    if(!count($sub_list_settings['query_string_filtered'])){
        continue;
    }

    echo '<div class="this_frame">';
    echo '<h3 style="margin-top: 55px;"><a href="'.view_memory(42903,33286).$link_i['i__hashtag'].'">'.view_i_title($link_i).'</a></h3>';
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';
    foreach($sub_list_settings['query_string_filtered'] as $x){
        echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
        echo '<td><div class="this_name">'.$x['extension_name'].'</div></td>';
        echo '<td>&nbsp;</td>';
        echo '</tr>';
    }
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
