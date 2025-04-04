<?php

if(!isset($_GET['ideahashtag'])){
    die('Missing Idea ID ideahashtag');
}

//Sheet
$players___6287 = $this->config->item('players___6287'); //APP
$players___4737 = $this->config->item('players___4737'); //Player References

$underdot_class = ( !isset($_GET['expand']) ? ' class="underdot" ' : '' );
$recursive_idea_ids = array();
$is_with_action_es = array();
$es_added = array();
$count = 0;
$body_content = '';
$count_totals = array(
    'e' => array(),
    'i' => array(),
);


//Generate list & settings:
$idea_settings = idea_settings($_GET['ideahashtag']);

echo '<h1>' . view_idea_title($idea_settings['i']) . '</h1>';
echo '<div class="center-frame hide-subline maxwidth hideIfEmpty remove_first_line">' . view_idea_links($idea_settings['i'], ( isset($player_e['playerid']) ? $player_e['playerid'] : 0 )) . '</div>';

echo 'Filter:';

foreach($idea_settings['query_string_filtered'] as $x){

    $body_content .= '<tr class="body_tr">';

    //IDEAS
    $idea_content = '';
    $this_quantity = 1;
    $name = '';
    foreach($idea_settings['idea_column'] as $idea_var){

        $discoveries = $this->Links->read(array(
            'linkidealeft' => $idea_var['ideaid'],
            'linkplayercreator' => $x['playerid'],
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                ), array(), 1);

        if(count($discoveries)){

            if($this_quantity<2 && intval($discoveries[0]['linknumber'])>=2){
                $this_quantity = $discoveries[0]['linknumber'];
            }


        }

        $idea_content .= '<td title="'.$x['playertext'].' x '.view_idea_title($idea_var, true).'">'.( count($discoveries) ? ( strlen($discoveries[0]['linktext']) > 0 ? ( isset($_GET['expand']) ? '<p title="'.view_idea_title($idea_var, true).': '.$discoveries[0]['linktext'].'" data-placement="top" '.$underdot_class.'>'.$discoveries[0]['linktext'].'</p>' : '<span title="'.view_idea_title($idea_var, true).': '.$discoveries[0]['linktext'].' ['.$discoveries[0]['linktime'].']" '.$underdot_class.'>✔️</span>'  ) : '<span title="'.view_idea_title($idea_var, true).' ['.$discoveries[0]['linktime'].']">✔️</span>' )  : '').'</td>';

        if(count($discoveries) && (!count($idea_var['must_follow']) || count($idea_var['must_follow'])!=count($this->Links->read(array(
                    'linkplayerdown' => $x['playerid'],
                    'linkplayerup IN (' . join(',', $idea_var['must_follow']) . ')' => null,
                    'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                                ))))){
            if(!isset($count_totals['i'][$idea_var['ideaid']])){
                $count_totals['i'][$idea_var['ideaid']] = 0;
            }
            $count_totals['i'][$idea_var['ideaid']]++;
        }

    }

    $this_quantity = $this_quantity-1;




    $plus_info = ' '.( $this_quantity > 0 ? '+'.$this_quantity : '' );

    $body_content .= '<td style="padding-top: 2px;"><span class="icon-block-xs">'.view_cover($x['playercover'], true).'</span><a href="'.view_memory(42903,42902).$x['playerhandle'].'" style="font-weight:bold;">'.$x['playertext'].'</a>'.$name.$plus_info.'</td>';



    //SOURCES
    foreach($idea_settings['player_column'] as $e){

        $require_writing = count($this->Links->read(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___43510')) . ')' => null, //Require Written Answers
            'linkplayerdown' => $e['playerid'],
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                )));

        $fetch_data = $this->Links->read(array(
                    'linkplayerdown' => $x['playerid'],
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
            'linkplayerup' => $e['playerid'],
        ));

        $message_clean = '';
        if(count($fetch_data)){
            if(strlen($fetch_data[0]['linktext'])){
                if(filter_var($fetch_data[0]['linktext'], FILTER_VALIDATE_URL)){
                    //Sheet Click to Expand
                    $message_clean = '<a '.$underdot_class.' href="'.$fetch_data[0]['linktext'].'" target="_blank" title="Open in a New Window">'.view_cover($e['playercover'], '🔗️', ' ').'</a>';
                } elseif(!isset($_GET['expand']) && in_array($e['playerid'], $this->config->item('playerids___40945'))){
                    //Sheet Click to Expand
                    $message_clean = '<span class="click_2_see_'.$e['playerid'].'_'.$fetch_data[0]['linkid'].'"><a href="javascript:void(0);" onclick="$(\'.click_2_see_'.$e['playerid'].'_'.$fetch_data[0]['linkid'].'\').toggleClass(\'hidden\')" '.$underdot_class.' title="'.$fetch_data[0]['linktext'].' [Click to Expand]">'.view_cover($e['playercover'], '✔️', ' ').'</a></span><span class="click_2_see_'.$e['playerid'].'_'.$fetch_data[0]['linkid'].' hidden">'.$fetch_data[0]['linktext'].'</span>';
                } elseif(isset($_GET['expand']) || $require_writing){
                    $message_clean = $fetch_data[0]['linktext'];
                } else {
                    $message_clean = '<span '.$underdot_class.' title="'.$fetch_data[0]['linktext'].'">'.view_cover($e['playercover'], '✔️', ' ').'</span>';
                }
            } else {
                $message_clean = '<span class="icon-block-xs">'.view_cover($e['playercover'], '✔️', ' ').'</span>';
            }
        }


        $body_content .= '<td title="'.$x['playertext'].' x '.$e['playertext'].'" class="'.( superpower_unlocked(10939) && !in_array($e['playerid'], $this->config->item('playerids___37695')) ? 'editable linkplayercreator_'.$e['playerid'].'_'.$x['playerid'] : '' ).'" ideaid="0" playerid="'.$e['playerid'].'" linkplayercreator="'.$x['playerid'].'" require_writing="'.( $require_writing ? 1 : 0 ).'" linkid="'.$x['linkid'].'"><div class="limit_height">'.$message_clean.'</div></td>';

        if(strlen($message_clean)>0){

            if(!isset($count_totals['e'][$e['playerid']])){
                $count_totals['e'][$e['playerid']] = 0;
            }

            $count_totals['e'][$e['playerid']] = $count_totals['e'][$e['playerid']] + ( count($this->Links->read(array(
                                    'linkplayerdown' => $e['playerid'],
                    'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                    'linkplayerup IN (' . join(',', $this->config->item('playerids___39609')) . ')' => null, //ADDUP NUMBER
                ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['linktext'])) : 1 );
        }
    }


    $body_content .= $idea_content;

    $body_content .= '</tr>';
    $count++;

}


$table_sortable = array('#th_primary','#th_done');
echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

echo '<tr style="font-weight:bold; vertical-align: baseline;">';
echo '<th id="th_primary" style="width:200px;">'.$count.' Players</th>';
foreach($idea_settings['player_column'] as $e){
    array_push($table_sortable, '#thplayer_'.$e['playerid']);
    echo '<th id="thplayer_'.$e['playerid'].'"><a class="icon-block-xs" href="'.view_memory(42903,42902).$e['playerhandle'].'" target="_blank" title="Open in New Window">'.view_cover($e['playercover'], '✔️', ' ').'</a><span class="vertical_col"><span class="col_stat">'.( isset($count_totals['e'][$e['playerid']]) ? str_replace('.00','',number_format($count_totals['e'][$e['playerid']], 2)) : '0' ).'</span><i class="far fa-sort"></i>'.$e['playertext'].'</span></th>';
}
foreach($idea_settings['idea_column'] as $idea_var){

    $max_available = $this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkidearight' => $idea_var['ideaid'],
        'linkplayerup' => 26189,
    ), array(), 1);
    $current_x = ( isset($count_totals['i'][$idea_var['ideaid']]) ? $count_totals['i'][$idea_var['ideaid']] : 0 );
    $max_limit = (count($max_available) && is_numeric($max_available[0]['linktext']) && intval($max_available[0]['linktext'])>0 ? intval($max_available[0]['linktext']) : 0 );

    array_push($table_sortable, '#th_idea_'.$idea_var['ideaid']);

    echo '<th id="th_idea_'.$idea_var['ideaid'].'"><div></div><a class="icon-block-xs" href="'.view_memory(42903,33286).$idea_var['ideahashtag'].'" target="_blank" title="Open in New Window">'.$players___4737[$idea_var['ideatype']]['m__cover'].'</a><span class="vertical_col"><span class="col_stat '.( $max_limit ? ( $current_x>=$max_limit ? ''  : ( ($current_x/$max_limit)>=0.5 ? 'isgold' : 'isred' ) ) : '' ).'">'.$current_x.( $max_limit ? '/'.$max_limit : '').'</span><i class="far fa-sort"></i>'.( strlen($idea_var['linktext']) ? $idea_var['linktext'] : view_idea_title($idea_var, true) ).'</span></th>';

}
echo '</tr>';
echo $body_content;
echo '</table>';

?>



<style>

    <?php if(!isset($_GET['expand'])){ echo ' #sortable_table td{ max-width: 89px !important; max-height: 89px !important; overflow: scroll; } '; } else { echo ' #sortable_table td{ font-size:1em !important; } '; } ?>


    <?php if(count($idea_settings['list_config'][34513])){ echo ' .container{ margin-left: 8px; max-width: calc(100% - 16px) !important; } '; } ?>

    .mini-header,
    #sortable_table td>p{
        display: block;
        max-width: 144px !important;
        max-height: 179px !important;
        overflow: scroll;
    }

    #sortable_table a {
        text-decoration: underline !important;
    }

    .maxwidth {
        max-width: 1200px !important;
    }

    /* CSS Adjustments for Printing View */
    #sortable_table .table-striped tr:nth-of-type(odd) td {
        background-color: #FFFFFF !important;
        -webkit-print-color-adjust:exact;
    }
    #sortable_table .table-striped td {
        border-bottom: 1px dotted #000000 !important;
        font-size: 1.15em;
    }
    .fa-filter, .fa-sort{
        font-size: 1.01em !important;
        margin-bottom: 3px;
    }
    #sortable_table th{
        cursor: ns-resize !important;
    }
    #sortable_table th, #sortable_table td{
        border: 1px solid #000000 !important;
    }

    #sortable_table th:hover, #sortable_table th:active{
        background-color: #FFF;
    }

    #sortable_table .body_tr:hover {
        background-color: #CCC;
    }
    #sortable_table .body_tr .editable:hover {
        background-color: #FFD961;
        cursor: pointer;
    }

    .vertical_col {
        writing-mode: tb-rl;
        white-space: nowrap;
        display: block;
        padding-bottom: 8px;
    }
    .col_stat{
        height:71px;
        display:inline-block;
        text-align: left;
        width: 8px;
    }


</style>
<script>

    $(document).ready(function () {

        $('.editable').click(function (e) {

            var require_writing = parseInt($(this).attr('require_writing'));
            var written_answer = '';
            if(require_writing){

                //return e_editor_load(playerid = 0, linkid = 0, $(this).attr('title'), $('.linkplayercreator_' + $(this).attr('playerid') + '_' + $(this).attr('linkplayercreator')).text());

                written_answer = prompt($(this).attr('title') + ":", $('.linkplayercreator_' + $(this).attr('playerid') + '_' + $(this).attr('linkplayercreator')).text());
                if(written_answer == null){
                    return false;
                }
            }

            var modify_data = {
                ideaid: $(this).attr('ideaid'),
                playerid: $(this).attr('playerid'),
                linkplayercreator: $(this).attr('linkplayercreator'),
                linkid: $(this).attr('linkid'),
                require_writing: require_writing,
                written_answer: written_answer,
                js_request_uri: js_request_uri, //Always append to AJAX Calls
            };

            $('.linkplayercreator_' + modify_data['playerid'] + '_' + modify_data['linkplayercreator']).html('<i class="fas fa-yin-yang fa-spin"></i>');

            //Check email and validate:
            $.post("/controller/e_toggle_player", modify_data, function (data) {

                if (data.status) {

                    //Update Player id IF existed previously:
                    $('.linkplayercreator_' + modify_data['playerid'] + '_' + modify_data['linkplayercreator']).html(data.message);

                } else {
                    alert('ERROR:' + data.message);
                }
            });

        });

        var table = $('#sortable_table');
        $('<?= join(', ', $table_sortable) ?>')
            .each(function(){

                var th = $(this),
                    thIndex = th.index(),
                    inverse = false;

                th.click(function(){

                    table.find('td').filter(function(){

                        return $(this).index() === thIndex;

                    }).sortElements(function(a, b){

                        return $.text([a]) < $.text([b]) ?
                            inverse ? -1 : 1
                            : inverse ? 1 : -1;

                    }, function(){

                        return this.parentNode;

                    });

                    inverse = !inverse;

                });

            });
    });
</script>
