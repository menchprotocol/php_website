<?php

if(!isset($_GET['i__hashtag'])){
    die('Missing Idea ID i__hashtag');
}

//Sheet
$e___6287 = $this->config->item('e___6287'); //APP
$e___4737 = $this->config->item('e___4737'); //Source References
$e___6177 = $this->config->item('e___6177'); //Source Privacy
$e___31004 = $this->config->item('e___31004'); //Idea Status

$underdot_class = ( !isset($_GET['expand']) ? ' class="underdot" ' : '' );
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

echo 'Filter:';

foreach($list_settings['query_string_filtered'] as $x){

    $body_content .= '<tr class="body_tr">';

    //IDEAS
    $i_content = '';
    $this_quantity = 1;
    $name = '';
    foreach($list_settings['column_i'] as $i_var){

        $discoveries = $this->Mench_ledger->fetch(array(
            'LinkLeft' => $i_var['i__id'],
            'LinkPlayer' => $x['e__id'],
            'LinkType IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 1);

        if(count($discoveries)){

            $LinkMetadata = @unserialize($discoveries[0]['LinkMetadata']);
            if(isset($LinkMetadata['quantity']) && $LinkMetadata['quantity'] >= 2){
                $this_quantity = $LinkMetadata['quantity'];
            }

            if($this_quantity<2 && intval($discoveries[0]['LinkNumber'])>=2){
                $this_quantity = $discoveries[0]['LinkNumber'];
            }


        }

        $i_content .= '<td title="'.$x['e__title'].' x '.view__i_title($i_var, true).'">'.( count($discoveries) ? ( strlen($discoveries[0]['LinkText']) > 0 ? ( isset($_GET['expand']) ? '<p title="'.view__i_title($i_var, true).': '.$discoveries[0]['LinkText'].'" data-placement="top" '.$underdot_class.'>'.$discoveries[0]['LinkText'].'</p>' : '<span title="'.view__i_title($i_var, true).': '.$discoveries[0]['LinkText'].' ['.$discoveries[0]['LinkTime'].']" '.$underdot_class.'>✔️</span>'  ) : '<span title="'.view__i_title($i_var, true).' ['.$discoveries[0]['LinkTime'].']">✔️</span>' ).( $discoveries[0]['LinkType']==26595 && $LinkMetadata['mc_gross']!=0 && strlen($LinkMetadata['txn_id'])>0 ? '<a href="https://www.paypal.com/activity/payment/'.$LinkMetadata['txn_id'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="View Paypal Transaction"><i class="fab fa-paypal" style="font-size:1em !important;"></i></a> ' : '' )  : '').'</td>';


        if(count($discoveries) && (!count($i_var['must_follow']) || count($i_var['must_follow'])!=count($this->Mench_ledger->fetch(array(
                    'LinkDown' => $x['e__id'],
                    'LinkUp IN (' . join(',', $i_var['must_follow']) . ')' => null,
                    'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ))))){
            if(!isset($count_totals['i'][$i_var['i__id']])){
                $count_totals['i'][$i_var['i__id']] = 0;
            }
            $count_totals['i'][$i_var['i__id']]++;
        }

    }

    $this_quantity = $this_quantity-1;




    $plus_info = ' '.( $this_quantity > 0 ? '+'.$this_quantity : '' );

    $body_content .= '<td style="padding-top: 2px;"><span class="icon-block-xs">'.view__cover($x['e__cover'], true).'</span><a href="'.view__memory(42903,42902).$x['e__handle'].'" style="font-weight:bold;">'.$x['e__title'].'</a>'.$name.$plus_info.'</td>';



    //SOURCES
    foreach($list_settings['column_e'] as $e){

        $require_writing = count($this->Mench_ledger->fetch(array(
            'LinkUp IN (' . join(',', $this->config->item('n___43510')) . ')' => null, //Require Written Answers
            'LinkDown' => $e['e__id'],
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        )));

        $fetch_data = $this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'LinkDown' => $x['e__id'],
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkUp' => $e['e__id'],
        ));

        $message_clean = '';
        if(count($fetch_data)){
            if(strlen($fetch_data[0]['LinkText'])){
                if(filter_var($fetch_data[0]['LinkText'], FILTER_VALIDATE_URL)){
                    //Sheet Click to Expand
                    $message_clean = '<a '.$underdot_class.' href="'.$fetch_data[0]['LinkText'].'" target="_blank" title="Open in a New Window">'.view__cover($e['e__cover'], '🔗️', ' ').'</a>';
                } elseif(!isset($_GET['expand']) && in_array($e['e__id'], $this->config->item('n___40945'))){
                    //Sheet Click to Expand
                    $message_clean = '<span class="click_2_see_'.$e['e__id'].'_'.$fetch_data[0]['LinkId'].'"><a href="javascript:void(0);" onclick="$(\'.click_2_see_'.$e['e__id'].'_'.$fetch_data[0]['LinkId'].'\').toggleClass(\'hidden\')" '.$underdot_class.' title="'.$fetch_data[0]['LinkText'].' [Click to Expand]">'.view__cover($e['e__cover'], '✔️', ' ').'</a></span><span class="click_2_see_'.$e['e__id'].'_'.$fetch_data[0]['LinkId'].' hidden">'.$fetch_data[0]['LinkText'].'</span>';
                } elseif(isset($_GET['expand']) || $require_writing){
                    $message_clean = $fetch_data[0]['LinkText'];
                } else {
                    $message_clean = '<span '.$underdot_class.' title="'.$fetch_data[0]['LinkText'].'">'.view__cover($e['e__cover'], '✔️', ' ').'</span>';
                }
            } else {
                $message_clean = '<span class="icon-block-xs">'.view__cover($e['e__cover'], '✔️', ' ').'</span>';
            }
        }


        $body_content .= '<td title="'.$x['e__title'].' x '.$e['e__title'].'" class="'.( superpower_unlocked(10939) && !in_array($e['e__id'], $this->config->item('n___37695')) ? 'editable LinkPlayer_'.$e['e__id'].'_'.$x['e__id'] : '' ).'" i__id="0" e__id="'.$e['e__id'].'" LinkPlayer="'.$x['e__id'].'" require_writing="'.( $require_writing ? 1 : 0 ).'" LinkId="'.$x['LinkId'].'"><div class="limit_height">'.$message_clean.'</div></td>';

        if(strlen($message_clean)>0){

            if(!isset($count_totals['e'][$e['e__id']])){
                $count_totals['e'][$e['e__id']] = 0;
            }

            $count_totals['e'][$e['e__id']] = $count_totals['e'][$e['e__id']] + ( count($this->Mench_ledger->fetch(array(
                    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'LinkDown' => $e['e__id'],
                    'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'LinkUp IN (' . join(',', $this->config->item('n___39609')) . ')' => null, //ADDUP NUMBER
                ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['LinkText'])) : 1 );
        }
    }


    $body_content .= $i_content;

    $body_content .= '</tr>';
    $count++;

}


$table_sortable = array('#th_primary','#th_done');
echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

echo '<tr style="font-weight:bold; vertical-align: baseline;">';
echo '<th id="th_primary" style="width:200px;">'.$count.' Sources</th>';
foreach($list_settings['column_e'] as $e){
    array_push($table_sortable, '#th_e_'.$e['e__id']);
    echo '<th id="th_e_'.$e['e__id'].'"><div><span class="icon-block-xs">'.$e___6177[$e['e__privacy']]['m__cover'].'</span></div><a class="icon-block-xs" href="'.view__memory(42903,42902).$e['e__handle'].'" target="_blank" title="Open in New Window">'.view__cover($e['e__cover'], '✔️', ' ').'</a><span class="vertical_col"><span class="col_stat">'.( isset($count_totals['e'][$e['e__id']]) ? str_replace('.00','',number_format($count_totals['e'][$e['e__id']], 2)) : '0' ).'</span><i class="far fa-sort"></i>'.$e['e__title'].'</span></th>';
}
foreach($list_settings['column_i'] as $i_var){

    $max_available = $this->Mench_ledger->fetch(array(
        'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'LinkType IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
        'LinkRight' => $i_var['i__id'],
        'LinkUp' => 26189,
    ), array(), 1);
    $current_x = ( isset($count_totals['i'][$i_var['i__id']]) ? $count_totals['i'][$i_var['i__id']] : 0 );
    $max_limit = (count($max_available) && is_numeric($max_available[0]['LinkText']) && intval($max_available[0]['LinkText'])>0 ? intval($max_available[0]['LinkText']) : 0 );

    array_push($table_sortable, '#th_i_'.$i_var['i__id']);

    echo '<th id="th_i_'.$i_var['i__id'].'"><div></div><a class="icon-block-xs" href="'.view__memory(42903,33286).$i_var['i__hashtag'].'" target="_blank" title="Open in New Window">'.$e___4737[$i_var['i__type']]['m__cover'].'</a><span class="vertical_col"><span class="col_stat '.( $max_limit ? ( $current_x>=$max_limit ? ''  : ( ($current_x/$max_limit)>=0.5 ? 'isgold' : 'isred' ) ) : '' ).'">'.$current_x.( $max_limit ? '/'.$max_limit : '').'</span><i class="far fa-sort"></i>'.( strlen($i_var['LinkText']) ? $i_var['LinkText'] : view__i_title($i_var, true) ).'</span></th>';

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

                //return e_editor_load(e__id = 0, LinkId = 0, $(this).attr('title'), $('.LinkPlayer_' + $(this).attr('e__id') + '_' + $(this).attr('LinkPlayer')).text());

                written_answer = prompt($(this).attr('title') + ":", $('.LinkPlayer_' + $(this).attr('e__id') + '_' + $(this).attr('LinkPlayer')).text());
                if(written_answer == null){
                    return false;
                }
            }

            var modify_data = {
                i__id: $(this).attr('i__id'),
                e__id: $(this).attr('e__id'),
                LinkPlayer: $(this).attr('LinkPlayer'),
                LinkId: $(this).attr('LinkId'),
                require_writing: require_writing,
                written_answer: written_answer,
                js_request_uri: js_request_uri, //Always append to AJAX Calls
            };

            $('.LinkPlayer_' + modify_data['e__id'] + '_' + modify_data['LinkPlayer']).html('<i class="fas fa-yin-yang fa-spin"></i>');

            //Check email and validate:
            $.post("/app/e_toggle_e", modify_data, function (data) {

                if (data.status) {

                    //Update source id IF existed previously:
                    $('.LinkPlayer_' + modify_data['e__id'] + '_' + modify_data['LinkPlayer']).html(data.message);

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
