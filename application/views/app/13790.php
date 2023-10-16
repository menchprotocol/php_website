<?php

$e___6287 = $this->config->item('e___6287'); //APP
$e___4737 = $this->config->item('e___4737'); //Idea Types
$e___6177 = $this->config->item('e___6177'); //Source Status
$e___31004 = $this->config->item('e___31004'); //Idea Status
$e___40787 = $this->config->item('e___40787'); //Sheet Link Types

$underdot_class = ( !isset($_GET['expand']) ? ' class="underdot" ' : '' );
$column_sources = array();
$column_ideas = array();


//Compile key settings for this sheet:
foreach($e___40787 as $x__type => $m) {
    $list_settings['list_config'][intval($x__type)] = array(); //Assume no links for this type
}
foreach($this->X_model->fetch(array(
    'x__right' => $i['i__id'],
    'x__type IN (' . join(',', $this->config->item('n___40787')) . ')' => null, //Sheets
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__access IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
), array('x__up'), 0) as $setting_link){
    array_push($list_settings['list_config'][intval($setting_link['x__type'])], intval($setting_link['x__up']));
}
foreach($this->X_model->fetch(array(
    'x__left' => $i['i__id'],
    'x__type IN (' . join(',', $this->config->item('n___40787')) . ')' => null, //Sheets
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
), array('x__right'), 0) as $setting_link){
    array_push($list_settings['list_config'][intval($setting_link['x__type'])], intval($setting_link['x__left']));
}

$list_settings['list_config'][34513] = 0;
foreach($list_settings['list_config'][34513] as $first_frame){
    $list_settings['list_config'][34513] = $first_frame;
    break;
}


//Fetch Main Idea:
if(count($list_settings['list_config'][40791])){


    $recursive_i_ids = array();
    $is_with_action_es = array();
    $es_added = array();

    if($list_settings['list_config'][34513]){

        foreach($this->I_model->fetch(array(
            'i__id IN (' . join(',', $list_settings['list_config'][40791]) . ')' => null, //SOURCE LINKS
        ), 0, 0, array('i__id' => 'ASC')) as $loaded_i) {
            echo '<h2><span class="icon-block" title="'.$e___40787[40791]['m__title'].'">'.$e___40787[40791]['m__cover'].'</span><a href="/~' . $loaded_i['i__id'] . '"><u>' . $loaded_i['i__title'] . '</u></a></h2>';
        }

        foreach($this->E_model->fetch(array(
            'e__id' => $list_settings['list_config'][34513],
        )) as $grid){
            echo '<h3><a href="/@' . $grid['e__id'] . '"><span class="icon-block">'.view_cover($grid['e__cover'], true). '</span><u>' . $grid['e__title'] . '</u></a></h3>';
        }

    } else {

        foreach($this->I_model->fetch(array(
            'i__id IN (' . join(',', $list_settings['list_config'][40791]) . ')' => null, //SOURCE LINKS
        ), 0, 0, array('i__id' => 'ASC')) as $loaded_i){

            $all_ids = $this->I_model->recursive_down_ids($loaded_i, 'ALL');
            $or_ids = $this->I_model->recursive_down_ids($loaded_i, 'OR');

            echo '<h2><a href="/~'.$loaded_i['i__id'].'">'.$loaded_i['i__title'].'</a> (<a href="javascript:void(0);" onclick="$(\'.idea_list\').toggleClass(\'hidden\');">'.count($all_ids).' IDEAS</a>)</h2>';
            $recursive_i_ids = array_merge($recursive_i_ids, $all_ids);

            echo '<div class="hidden idea_list">';
            echo '<div>'.count($all_ids).' Total Ideas:</div>';
            $count = 0;
            foreach($all_ids as $recursive_down_id){
                foreach($this->I_model->fetch(array(
                    'i__id' => $recursive_down_id,
                ), 0, 0, array('i__id' => 'ASC')) as $this_i){
                    $count++;
                    echo '<p>'.$count.') <a href="/~'.$this_i['i__id'].'">'.$this_i['i__title'].'</a></p>';

                    if(!$list_settings['list_config'][34513]){
                        foreach($this->X_model->fetch(array(
                            'x__right' => $this_i['i__id'],
                            'x__type IN (' . join(',', $this->config->item('n___31023')) . ')' => null, //Idea Source Action Links
                            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                        ), array('x__up'), 0) as $this_e){
                            if(!in_array($this_e['e__id'], $es_added) && (!count($list_settings['list_config'][27984]) || !in_array($this_e['e__id'], $list_settings['list_config'][27984]))){
                                array_push($column_sources, $this_e);
                                array_push($es_added, $this_e['e__id']);
                            }
                            array_push($is_with_action_es, $this_i['i__id']);
                        }
                    }
                }
            }

            echo '<div>'.count($or_ids).' OR Ideas (Responses vary per user)</div>';
            $count = 0;
            foreach($or_ids as $recursive_down_id){
                foreach($this->I_model->fetch(array(
                    'i__id' => $recursive_down_id,
                ), 0, 0, array('i__id' => 'ASC')) as $this_i){
                    $count++;
                    echo '<p>'.$count.') <a href="/~'.$this_i['i__id'].'">'.$this_i['i__title'].'</a></p>';
                    if(!$list_settings['list_config'][34513] && !in_array($this_i['i__id'], $is_with_action_es) && isset($_GET['all_ideas'])){
                        array_push($column_ideas, $this_i);
                    }
                }
            }
            echo '</div>';

        }
    }


    echo '<div style="padding: 10px;"><a href="/-26582?i__id='.join(',', $list_settings['list_config'][40791]).'&e__id='.join(',', $list_settings['list_config'][34513]).'&include_e='.join(',', $list_settings['list_config'][27984]).'&exclude_e='.join(',', $list_settings['list_config'][26600]).'">'.$e___6287[26582]['m__cover'].' '.$e___6287[26582]['m__title'].'</a> | <a href="/-40355?i__id='.join(',', $list_settings['list_config'][40791]).'&include_e='.join(',', $list_settings['list_config'][27984]).'&exclude_e='.join(',', $list_settings['list_config'][26600]).'&custom_grid='.$list_settings['list_config'][34513].'">'.$e___6287[40355]['m__cover'].' '.$e___6287[40355]['m__title'].'</a></div>';





    if($list_settings['list_config'][34513]){

        $column_sources = $this->X_model->fetch(array(
            'x__up' => $list_settings['list_config'][34513], //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__down'), 0, 0, array('x__weight' => 'ASC', 'e__title' => 'ASC'));

        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__up' => $list_settings['list_config'][34513], //ACTIVE
            'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC', 'i__title' => 'ASC')) as $link_i){
            array_push($column_ideas, $link_i);
        }

    }







    //Return UI:
    $body_content = '';
    $count_totals = array(
        'e' => array(),
        'i' => array(),
    );
    $unique_users_count = array();
    $count = 0;

    foreach($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
        'x__left IN (' . join(',', $list_settings['list_config'][40791]) . ')' => null, //IDEA LINKS
    ), array('x__creator'), 0, 0, array('x__time' => 'DESC')) as $x){

        if(in_array($x['e__id'], $unique_users_count)){
            continue;
        }

        if(count($list_settings['list_config'][27984])>0 && count($list_settings['list_config'][27984])!=count($this->X_model->fetch(array(
                'x__up IN (' . join(',', $list_settings['list_config'][27984]) . ')' => null, //All of these
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
            //Must be included in ALL Sources, since not lets continue:
            continue;
        }
        if(count($list_settings['list_config'][26600]) && count($this->X_model->fetch(array(
                'x__up IN (' . join(',', $list_settings['list_config'][26600]) . ')' => null, //All of these
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
            //Must follow NONE of these sources:
            continue;
        }

        if(count($list_settings['list_config'][40793]) && count($this->X_model->fetch(array(
                'x__left IN (' .join(',', $list_settings['list_config'][40793]) . ')' => null, //All of these
                'x__creator' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
            //They have discovered at-least one, so skip this:
            continue;
        }


        array_push($unique_users_count, $x['e__id']);
        $body_content .= '<tr class="body_tr">';

        $this_top = $this->I_model->fetch(array(
            'i__id' => $x['x__left'],
        ));

        //Member
        $tree_progress = $this->X_model->tree_progress($x['e__id'], $this_top[0]);
        $perfect_point = str_pad($tree_progress['fixed_completed_percentage'], 3, '0', STR_PAD_LEFT);
        $perfect_point = ( $perfect_point>100 ? 100 : $perfect_point );




        //IDEAS
        $idea_content = '';
        $this_quantity = 1;
        $name = '';
        foreach($column_ideas as $i2){

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
        foreach($column_sources as $e){

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

            $message_clean = ( count($fetch_data) ? ( strlen($fetch_data[0]['x__message']) ? ( isset($_GET['expand']) || $input_modal || in_array($e['e__id'], $this->config->item('n___37694')) ? preview_x__message($fetch_data[0]['x__message'], $fetch_data[0]['x__type']) : '<span '.$underdot_class.' title="'.$fetch_data[0]['x__message'].'">'.view_cover($e['e__cover'], '✔️', ' ').'</span>' ) : '<span class="icon-block-xxs">'.view_cover($e['e__cover'], '✔️', ' ').'</span>' ) : '' );


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



        $e_emails = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $x['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
        ));

        $body_content .= '</tr>';
        $count++;

    }


    $table_sortable = array('#th_primary','#th_done');
    echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

    echo '<tr style="font-weight:bold; vertical-align: baseline;">';
    echo '<th id="th_primary" style="width:200px;">'.$count.' Sources</th>';
    foreach($column_sources as $e){
        array_push($table_sortable, '#th_e_'.$e['e__id']);
        echo '<th id="th_e_'.$e['e__id'].'"><div><span class="icon-block-xxs">'.$e___6177[$e['e__access']]['m__cover'].'</span></div><a class="icon-block-xxs" href="/@'.$e['e__id'].'" target="_blank" title="Open in New Window">'.view_cover($e['e__cover'], '✔️', ' ').'</a><span class="vertical_col"><span class="col_stat">'.( isset($count_totals['e'][$e['e__id']]) ? str_replace('.00','',number_format($count_totals['e'][$e['e__id']], 2)) : '0' ).'</span><i class="fas fa-sort"></i>'.$e['e__title'].'</span></th>';
    }
    foreach($column_ideas as $i2){

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
    //echo '<th>STARTED</th>';
    echo '</tr>';
    echo $body_content;
    echo '</table>';


    ?>

    <style>

        <?php if(!isset($_GET['expand'])){ echo ' #sortable_table td{ max-width: 89px !important; max-height: 89px !important; overflow: scroll; } '; } else { echo ' #sortable_table td{ font-size:1em !important; } '; } ?>

        .container{
            margin-left: 8px;
            max-width: calc(100% - 16px) !important;
        }
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
        .fixed-top{
            background-color: transparent !important;
        }
        .top_nav{
            display:none !important;
        }
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

    <?php

} else {

    echo 'Missing Idea ID';

}