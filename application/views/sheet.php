<?php

if(!isset($_GET['i__hashtag'])){
    die('Missing Idea ID i__hashtag');
}

//Sheet
$e___11035 = $this->config->item('e___11035'); //Encyclopedia

$recursive_i_ids = array();
$is_with_action_es = array();
$es_added = array();
$count = 0;
$body_content = '';
$count_totals = array(
    'e' => array(),
    'i' => array(),
);


//Generate list & settings:
$list_settings = list_settings($_GET['i__hashtag']);

echo '<h1>' . view__i_title($list_settings['i']) . '</h1>';
echo '<div class="center-frame hide-subline maxwidth hideIfEmpty remove_first_line">' . view__i__links($list_settings['i'], ( isset($player_e['e__id']) ? $player_e['e__id'] : 0 )) . '</div>';

foreach($list_settings['query_string_filtered'] as $x){

    $body_content .= '<tr class="body_tr">';

    //IDEAS
    $i_content = '';
    $this_quantity = 1;
    foreach($list_settings['column_i'] as $i_var){

        $discoveries = $this->Mench_ledger->fetch(array(
            'link_left' => $i_var['i__id'],
            'link_player' => $x['e__id'],
            'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'link_void' => 0, //Not Void
        ), array(), 1);

        $i_content .= '<td title="'.$x['e__title'].' x '.view__i_title($i_var, true).'">';

        if(count($discoveries)){

            if($this_quantity<2 && intval($discoveries[0]['link_number'])>=2){
                $this_quantity = $discoveries[0]['link_number'];
            }

            $set_link_text = '';
            foreach($this->Mench_ledger->fetch(array(
                'link_void' => 0, //Not Void
                'link_type' => 33532, //Private Reply
                'link_left' => $i_var['i__id'],
                'link_player' => $x['e__id'],
            ), array('link_right'), 0, 1, array('link_id' => 'DESC')) as $response){
                $set_link_text = $response['i__message'];
            }

            if($set_link_text){

                $i_content .= ( isset($_GET['expand']) ? '<p data-placement="top">'.$set_link_text.'</p>' : '<span title="'.$set_link_text.' ['.$discoveries[0]['link_time'].']">ℹ️️</span>'  );

            } elseif(strlen($discoveries[0]['link_text']) > 0){

                $i_content .= ( isset($_GET['expand']) ? '<p data-placement="top" title="'.$discoveries[0]['link_text'].'">'.$discoveries[0]['link_text'].'</p>' : '<span title="'.view__i_title($i_var, true).': '.$discoveries[0]['link_text'].' ['.$discoveries[0]['link_time'].']">ℹ️️</span>'  );

            } else {
                $i_content .= '<span title="'.view__i_title($i_var, true).' ['.$discoveries[0]['link_time'].']">✔️</span>';
            }

        }

        $i_content .= '</td>';


        if(count($discoveries) && (!count($i_var['must_follow']) || count($i_var['must_follow'])!=count($this->Mench_ledger->fetch(array(
                    'link_down' => $x['e__id'],
                    'link_up IN (' . join(',', $i_var['must_follow']) . ')' => null,
                    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'link_void' => 0, //Not Void
                ))))){
            if(!isset($count_totals['i'][$i_var['i__id']])){
                $count_totals['i'][$i_var['i__id']] = 0;
            }
            $count_totals['i'][$i_var['i__id']]++;
        }

    }

    $this_quantity = $this_quantity-1;



    $body_content .= '<td style="padding-top: 2px;"><span class="icon-block-xs">'.view__cover($x['e__cover'], true).'</span><a href="'.view__memory(42903,42902).$x['e__handle'].'" style="font-weight:bold;">'.$x['e__title'].'</a>'.( $this_quantity > 0 ? ' +'.$this_quantity : '' ).'</td>';



    //SOURCES
    foreach($list_settings['column_e'] as $e){

        $require_writing = count($this->Mench_ledger->fetch(array(
            'link_up IN (' . join(',', $this->config->item('n___43510')) . ')' => null, //Require Written Answers
            'link_down' => $e['e__id'],
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        )));

        $fetch_data = $this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_down' => $x['e__id'],
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_up' => $e['e__id'],
        ));

        $message_clean = '';
        if(count($fetch_data)){
            if(strlen($fetch_data[0]['link_text'])){
                if(filter_var($fetch_data[0]['link_text'], FILTER_VALIDATE_URL)){
                    //Sheet Click to Expand
                    $message_clean = '<a href="'.$fetch_data[0]['link_text'].'" target="_blank" title="Open in a New Window">'.view__cover($e['e__cover'], '🔗️', ' ').'</a>';
                } elseif(!isset($_GET['expand']) && in_array($e['e__id'], $this->config->item('n___40945'))){
                    //Sheet Click to Expand
                    $message_clean = '<span class="click_2_see_'.$e['e__id'].'_'.$fetch_data[0]['link_id'].'"><a href="javascript:void(0);" onclick="$(\'.click_2_see_'.$e['e__id'].'_'.$fetch_data[0]['link_id'].'\').toggleClass(\'hidden\')" title="'.$fetch_data[0]['link_text'].' [Click to Expand]">'.view__cover($e['e__cover'], '✔️', ' ').'</a></span><span class="click_2_see_'.$e['e__id'].'_'.$fetch_data[0]['link_id'].' hidden">'.$fetch_data[0]['link_text'].'</span>';
                } elseif(isset($_GET['expand']) || $require_writing){
                    $message_clean = $fetch_data[0]['link_text'];
                } else {
                    $message_clean = '<span title="'.$fetch_data[0]['link_text'].'">'.view__cover($e['e__cover'], '✔️', ' ').'</span>';
                }
            } else {
                $message_clean = '<span class="icon-block-xs">'.view__cover($e['e__cover'], '✔️', ' ').'</span>';
            }
        }


        if($e['e__id']==44328){
            //Fetch primary filter:
            foreach($this->Mench_ledger->fetch(array(
                'link_right' => $focus_i['i__id'],
                'link_type IN (' . join(',', $this->config->item('n___44344')) . ')' => null, //Idea Filter Additions
                'link_void' => 0, //Not Void
            ), array('link_left'), 1) as $target_i){
                //See History for this user:
                $message_clean = '<a href="'.view__app_link(44328).'/'.$target_i['i__hashtag'].'@'.$x['e__handle'].'" target="_blank" title="'.$e___11035[44328]['m__title'].'"><span class="icon-block-sm">'.$e___11035[44328]['m__cover'].'</span></a>';
            }
        }

        $body_content .= '<td title="'.$x['e__title'].' x '.$e['e__title'].'" class="'.( superpower_unlocked(10939) && !in_array($e['e__id'], $this->config->item('n___37695')) ? 'editable link_player_'.$e['e__id'].'_'.$x['e__id'] : '' ).'" i__id="0" e__id="'.$e['e__id'].'" link_player="'.$x['e__id'].'" require_writing="'.( $require_writing ? 1 : 0 ).'" link_id="'.$x['link_id'].'"><div class="limit_height">'.$message_clean.'</div></td>';

        if(strlen($message_clean)>0){

            if(!isset($count_totals['e'][$e['e__id']])){
                $count_totals['e'][$e['e__id']] = 0;
            }

            $count_totals['e'][$e['e__id']] = $count_totals['e'][$e['e__id']] + ( count($this->Mench_ledger->fetch(array(
                    'link_void' => 0, //Not Void
                    'link_down' => $e['e__id'],
                    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'link_up IN (' . join(',', $this->config->item('n___39609')) . ')' => null, //ADDUP NUMBER
                ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['link_text'])) : 1 );
        }
    }


    $body_content .= $i_content;

    $body_content .= '</tr>';
    $count++;

}


$table_sortable = array('#th_primary');
echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

echo '<tr style="font-weight:bold; vertical-align: baseline;">';
echo '<th id="th_primary" style="width:200px;">'.$count.' Sources</th>';

foreach($list_settings['column_e'] as $e){
    array_push($table_sortable, '#th_e_'.$e['e__id']);
    echo '<th id="th_e_'.$e['e__id'].'"><a class="icon-block-xs" href="'.view__memory(42903,42902).$e['e__handle'].'" target="_blank" title="Open in New Window">'.( isset($count_totals['e'][$e['e__id']]) ? str_replace('.00','',number_format($count_totals['e'][$e['e__id']], 2)) : '0' ).'</a><span class="vertical_col">'.view__cover($e['e__cover'], '✔️', ' ').$e['e__title'].'</span></th>';
}

foreach($list_settings['column_i'] as $i_var){

    $max_available = $this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'link_right' => $i_var['i__id'],
        'link_up' => 26189,
    ), array(), 1);
    $current_x = ( isset($count_totals['i'][$i_var['i__id']]) ? $count_totals['i'][$i_var['i__id']] : 0 );
    $max_limit = (count($max_available) && is_numeric($max_available[0]['link_text']) && intval($max_available[0]['link_text'])>0 ? intval($max_available[0]['link_text']) : 0 );

    array_push($table_sortable, '#th_i_'.$i_var['i__id']);

    echo '<th id="th_i_'.$i_var['i__id'].'"><a class="icon-block-xs" href="'.view__memory(42903,33286).$i_var['i__hashtag'].'" target="_blank" title="Open in New Window" '.( $max_limit ? ( $current_x>=$max_limit ? ''  : ( ($current_x/$max_limit)>=0.5 ? 'isgold' : 'isred' ) ) : '' ).'">'.$current_x.( $max_limit ? '/'.$max_limit : '').'</a><span class="vertical_col">'.( strlen($i_var['link_text']) ? $i_var['link_text'] : view__i_title($i_var, true) ).'</span></th>';

}
echo '</tr>';
echo $body_content;
echo '</table>';

?>



<style>

    <?php if(!isset($_GET['expand'])){ echo ' #sortable_table td{ max-width: 89px !important; max-height: 89px !important; overflow: scroll; } '; } else { echo ' #sortable_table td{ font-size:1em !important; } '; } ?>


    <?php if(count($list_settings['list_config'][34513])){ echo ' .container{ margin-left: 8px; max-width: calc(100% - 16px) !important; } '; } ?>

    .mini-header,
    #sortable_table td>p{
        display: block;
        max-width: 144px !important;
        max-height: 179px !important;
        overflow: scroll;
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
    .fa-filter173{
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

                //return e_editor_load(e__id = 0, link_id = 0, $(this).attr('title'), $('.link_player_' + $(this).attr('e__id') + '_' + $(this).attr('link_player')).text());

                written_answer = prompt($(this).attr('title') + ":", $('.link_player_' + $(this).attr('e__id') + '_' + $(this).attr('link_player')).text());
                if(written_answer == null){
                    return false;
                }
            }

            var modify_data = {
                i__id: $(this).attr('i__id'),
                e__id: $(this).attr('e__id'),
                link_player: $(this).attr('link_player'),
                link_id: $(this).attr('link_id'),
                require_writing: require_writing,
                written_answer: written_answer,
                js_request_uri: js_request_uri, //Always append to AJAX Calls
            };

            $('.link_player_' + modify_data['e__id'] + '_' + modify_data['link_player']).html('<i class="fas fa-yin-yang fa-spin"></i>');

            //Check email and validate:
            $.post("/app/e_toggle_e", modify_data, function (data) {

                if (data.status) {

                    //Update source id IF existed previously:
                    $('.link_player_' + modify_data['e__id'] + '_' + modify_data['link_player']).html(data.message);

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
