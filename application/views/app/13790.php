<?php

if(!isset($_GET['i__id'])){
    die('Missing Idea ID i__id');
}

//Sheet
$e___6287 = $this->config->item('e___6287'); //APP
$e___4737 = $this->config->item('e___4737'); //Idea Types
$e___6177 = $this->config->item('e___6177'); //Source Status
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
$list_settings = list_settings($_GET['i__id']);
echo '<h1>' . view_i_title($list_settings['i']) . '</h1>';

foreach($list_settings['query_string'] as $x){

    $body_content .= '<tr class="body_tr">';

    //IDEAS
    $idea_content = '';
    $this_quantity = 1;
    $name = '';
    foreach($list_settings['column_ideas'] as $i2){

        $discoveries = $this->X_model->fetch(array(
            'x__left' => $i2['i__id'],
            'x__creator' => $x['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 1);

        if(count($discoveries)){

            $x__metadata = unserialize($discoveries[0]['x__metadata']);
            if(isset($x__metadata['quantity']) && $x__metadata['quantity'] >= 2){
                $this_quantity = $x__metadata['quantity'];
            }

            if($this_quantity<2){
                for($t=20;$t>=2;$t--){
                    if(substr_count(strtolower($i2['i__title']),$t.'x')==1){
                        $this_quantity = $t;
                        break;
                    }
                }
            }

            if($i2['i__id']==15736){
                $name = $discoveries[0]['x__message'];
            }
        }

        $idea_content .= '<td>'.( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? ( isset($_GET['expand']) ? '<p title="'.$i2['i__title'].': '.$discoveries[0]['x__message'].'" data-placement="top" '.$underdot_class.'>'.convertURLs($discoveries[0]['x__message']).'</p>' : '<span title="'.$i2['i__title'].': '.$discoveries[0]['x__message'].' ['.$discoveries[0]['x__time'].']" '.$underdot_class.'>✔️</span>'  ) : '<span title="'.$i2['i__title'].' ['.$discoveries[0]['x__time'].']">✔️</span>' )  : '').'</td>';

        if(count($discoveries)){
            if(!isset($count_totals['i'][$i2['i__id']])){
                $count_totals['i'][$i2['i__id']] = 0;
            }
            $count_totals['i'][$i2['i__id']]++;
        }

    }

    $this_quantity = $this_quantity-1;




    $plus_info = ' '.( $this_quantity > 0 ? '+'.$this_quantity : '' );

    $body_content .= '<td style="padding-top: 2px;"><span class="icon-block-xxs">'.view_cover($x['e__cover'], true).'</span><a href="/@'.$x['e__id'].'" style="font-weight:bold;">'.$x['e__title'].'</a>'.$name.$plus_info.'</td>';



    //SOURCES
    foreach($list_settings['column_sources'] as $e){

        $input_modal = count($this->X_model->fetch(array(
            'x__up IN (' . join(',', $this->config->item('n___37707')) . ')' => null, //SOURCE LINKS
            'x__down' => $e['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        )));

        $fetch_data = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $x['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__up' => $e['e__id'],
        ));


        $message_clean = '';
        if(count($fetch_data)){
            if(strlen($fetch_data[0]['x__message'])){
                if(!isset($_GET['expand']) && in_array($e['e__id'], $this->config->item('n___40945'))){
                    //Sheet Click to Expand
                    $message_clean = '<span class="click_2_see_'.$e['e__id'].'_'.$fetch_data[0]['x__id'].'"><a href="javascript:void(0);" onclick="$(\'.click_2_see_'.$e['e__id'].'_'.$fetch_data[0]['x__id'].'\').toggleClass(\'hidden\')" '.$underdot_class.' title="'.$fetch_data[0]['x__message'].' [Click to Expand]">'.view_cover($e['e__cover'], '✔️', ' ').'</a></span><span class="click_2_see_'.$e['e__id'].'_'.$fetch_data[0]['x__id'].' hidden">'.preview_x__message($fetch_data[0]['x__message'], $fetch_data[0]['x__type']).'</span>';
                } elseif(isset($_GET['expand']) || $input_modal || in_array($e['e__id'], $this->config->item('n___37694'))){
                    $message_clean = preview_x__message($fetch_data[0]['x__message'], $fetch_data[0]['x__type']);
                } else {
                    $message_clean = '<span '.$underdot_class.' title="'.$fetch_data[0]['x__message'].'">'.view_cover($e['e__cover'], '✔️', ' ').'</span>';
                }
            } else {
                $message_clean = '<span class="icon-block-xxs">'.view_cover($e['e__cover'], '✔️', ' ').'</span>';
            }
        }


        $body_content .= '<td class="'.( superpower_active(28714, true) && !in_array($e['e__id'], $this->config->item('n___37695')) ? 'editable x__creator_'.$e['e__id'].'_'.$x['e__id'] : '' ).'" i__id="0" e__id="'.$e['e__id'].'" x__creator="'.$x['e__id'].'" input_modal="'.( $input_modal ? 1 : 0 ).'" x__id="'.$x['x__id'].'"><div class="limit_height">'.$message_clean.'</div></td>';

        if(strlen($message_clean)>0){

            if(!isset($count_totals['e'][$e['e__id']])){
                $count_totals['e'][$e['e__id']] = 0;
            }

            $count_totals['e'][$e['e__id']] = $count_totals['e'][$e['e__id']] + ( count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__down' => $e['e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__up IN (' . join(',', $this->config->item('n___39609')) . ')' => null, //ADDUP NUMBER
                ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['x__message'])) : 1 );
        }
    }


    $body_content .= $idea_content;

    $body_content .= '</tr>';
    $count++;

}


$table_sortable = array('#th_primary','#th_done');
echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

echo '<tr style="font-weight:bold; vertical-align: baseline;">';
echo '<th id="th_primary" style="width:200px;">'.$count.' Sources</th>';
foreach($list_settings['column_sources'] as $e){
    array_push($table_sortable, '#th_e_'.$e['e__id']);
    echo '<th id="th_e_'.$e['e__id'].'"><div><span class="icon-block-xxs">'.$e___6177[$e['e__access']]['m__cover'].'</span></div><a class="icon-block-xxs" href="/@'.$e['e__id'].'" target="_blank" title="Open in New Window">'.view_cover($e['e__cover'], '✔️', ' ').'</a><span class="vertical_col"><span class="col_stat">'.( isset($count_totals['e'][$e['e__id']]) ? str_replace('.00','',number_format($count_totals['e'][$e['e__id']], 2)) : '0' ).'</span><i class="fas fa-sort"></i>'.$e['e__title'].'</span></th>';
}
foreach($list_settings['column_ideas'] as $i2){

    $max_available = $this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
        'x__right' => $i2['i__id'],
        'x__up' => 26189,
    ), array(), 1);
    $current_x = ( isset($count_totals['i'][$i2['i__id']]) ? $count_totals['i'][$i2['i__id']] : 0 );
    $max_limit = (count($max_available) && is_numeric($max_available[0]['x__message']) && intval($max_available[0]['x__message'])>0 ? intval($max_available[0]['x__message']) : 0 );

    array_push($table_sortable, '#th_i_'.$i2['i__id']);

    echo '<th id="th_i_'.$i2['i__id'].'"><div><span class="icon-block-xxs">'.$e___31004[$i2['i__access']]['m__cover'].'</span></div><a class="icon-block-xxs" href="/~'.$i2['i__id'].'" target="_blank" title="Open in New Window">'.$e___4737[$i2['i__type']]['m__cover'].'</a><span class="vertical_col"><span class="col_stat '.( $max_limit ? ( $current_x>=$max_limit ? 'isgreen'  : ( ($current_x/$max_limit)>=0.5 ? 'isgold' : 'isred' ) ) : '' ).'">'.$current_x.( $max_limit ? '/'.$max_limit : '').'</span><i class="fas fa-sort"></i>'.( strlen($i2['x__message']) ? $i2['x__message'] : $i2['i__title'] ).'</span></th>';

}
echo '</tr>';
echo $body_content;
echo '</table>';

?>



<style>

    <?php if(!isset($_GET['expand'])){ echo ' #sortable_table td{ max-width: 89px !important; max-height: 89px !important; overflow: scroll; } '; } else { echo ' #sortable_table td{ font-size:1em !important; } '; } ?>


    <?php if($list_settings['list_config'][34513]>0){ echo ' .container{ margin-left: 8px; max-width: calc(100% - 16px) !important; } '; } ?>

    .mini-header,
    #sortable_table td>p{
        display: block;
        max-width: 144px !important;
        max-height: 179px !important;
        overflow: scroll;
    }

    td a {
        text-decoration: underline !important;
    }

    /* CSS Adjustments for Printing View */
    #sortable_table .table-striped tr:nth-of-type(odd) td {
        background-color: #FFFFFF !important;
        -webkit-print-color-adjust:exact;
    }
    #sortable_table .table-striped td {
        border-bottom: 1px dotted #FFFFFF !important;
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
        border: 1px solid #000 !important;
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

            var input_modal = parseInt($(this).attr('input_modal'));
            var modal_value = '';
            if(input_modal){
                modal_value = prompt("Enter value:", $('.x__creator_' + $(this).attr('e__id') + '_' + $(this).attr('x__creator')).text());
            }

            var modify_data = {
                i__id: $(this).attr('i__id'),
                e__id: $(this).attr('e__id'),
                x__creator: $(this).attr('x__creator'),
                x__id: $(this).attr('x__id'),
                input_modal: input_modal,
                modal_value: modal_value,
            };

            $('.x__creator_' + modify_data['e__id'] + '_' + modify_data['x__creator']).html('<i class="far fa-yin-yang fa-spin"></i>');

            //Check email and validate:
            $.post("/e/e_toggle_e", modify_data, function (data) {

                if (data.status) {

                    //Update source id IF existed previously:
                    $('.x__creator_' + modify_data['e__id'] + '_' + modify_data['x__creator']).html(data.message);

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
