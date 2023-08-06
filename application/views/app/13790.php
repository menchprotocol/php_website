<?php

foreach(array('i__id','e__id','exclude_e','include_e','custom_grid') as $input){
    if(!isset($_GET[$input])){
        $_GET[$input] = '';
    }
}


$e___6287 = $this->config->item('e___6287'); //APP
$e___4737 = $this->config->item('e___4737'); //Idea Types

$underdot_class = ( !isset($_GET['expand']) ? ' class="underdot" ' : '' );
$has_grid = isset($_GET['custom_grid']) && intval($_GET['custom_grid']);
$column_sources = array();
$column_ideas = array();


//Fetch Main Idea:
if(strlen($_GET['i__id'])){


    $recursive_i_ids = array();
    $is_with_action_es = array();
    $es_added = array();

    if(!$has_grid){
        foreach($this->I_model->fetch(array(
            'i__id IN (' . $_GET['i__id'] . ')' => null, //SOURCE LINKS
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

                    if(!$has_grid){
                        foreach($this->X_model->fetch(array(
                            'x__right' => $this_i['i__id'],
                            'x__type IN (' . join(',', $this->config->item('n___31023')) . ')' => null, //Idea Source Action Links
                            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                        ), array('x__up'), 0) as $this_e){
                            if(!in_array($this_e['e__id'], $es_added) && (!strlen($_GET['include_e']) || !in_array($this_e['e__id'], explode(',',$_GET['include_e'])))){
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
                    if(!strlen($_GET['custom_grid']) && !in_array($this_i['i__id'], $is_with_action_es) && isset($_GET['all_ideas'])){
                        array_push($column_ideas, $this_i);
                    }
                }
            }
            echo '</div>';

        }
    }


    echo '<div style="padding: 10px;"><a href="javascript:void(0);" onclick="$(\'.filter_box\').toggleClass(\'hidden\')"><i class="fad fa-filter"></i> Toggle Filters</a> | <a href="/-26582?i__id='.$_GET['i__id'].'&e__id='.$_GET['e__id'].'&include_e='.$_GET['include_e'].'&exclude_e='.$_GET['exclude_e'].'">'.$e___6287[26582]['m__cover'].' '.$e___6287[26582]['m__title'].'</a></div>';

    echo '<form action="" method="GET" class="filter_box hidden" style="padding: 10px">';
    echo '<table class="table table-sm maxout filter_table"><tr>';

    //ANY IDEA
    echo '<td><div>';
    echo '<span class="mini-header">Discovered Idea(s):</span>';
    echo '<input type="text" name="i__id" placeholder="id1,id2" value="' . $_GET['i__id'] . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">Belongs to Source(s):</span><input type="text" name="e__id" placeholder="id1,id2" value="' . $_GET['e__id'] . '" class="form-control border"></td>';

    echo '</tr><tr>';

    echo '<td><div>';
    echo '<span class="mini-header">Includes Following Source(s):</span>';
    echo '<input type="text" name="include_e" placeholder="id1,id2" value="' . $_GET['include_e'] . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">Excludes Following Source(s):</span><input type="text" name="exclude_e" placeholder="id1,id2" value="' . $_GET['exclude_e'] . '" class="form-control border"></td>';

    echo '</tr><tr>';

    echo '<td><div>';
    echo '<span class="mini-header">List X-Axis Source(s):</span>';
    echo '<input type="text" name="custom_grid" placeholder="id1,id2" value="' . $_GET['custom_grid'] . '" class="form-control border">';
    echo '</div></td>';

    echo '<td>&nbsp;</td>';

    echo '</tr><tr>';

    echo '<td class="standard-bg"><input type="submit" class="btn btn-default" value="Apply" /></td>';
    echo '<td class="standard-bg">'.view_dropdown(27264, ( isset($_GET['expand']) ? 27266 : 27265 )).'</td>';

    echo '</tr></table>';

    echo '</form>';






    if($has_grid){

        $column_sources = $this->X_model->fetch(array(
            'x__up' => $_GET['custom_grid'], //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__down'), 0, 0, array('x__weight' => 'ASC', 'e__title' => 'ASC'));

        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__up' => $_GET['custom_grid'], //ACTIVE
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
        'x__left IN (' . join(',', array($_GET['i__id'])) . ')' => null, //IDEA LINKS
    ), array('x__creator'), 0, 0, array('x__time' => 'DESC')) as $x){

        if(in_array($x['e__id'], $unique_users_count)){
            continue;
        }

        if(isset($_GET['include_e']) && intval($_GET['include_e']) && count($this->X_model->fetch(array(
                'x__up IN (' . $_GET['include_e'] . ')' => null, //All of these
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ))) != count(explode(',',$_GET['include_e']))){
            continue;
        }
        if(isset($_GET['exclude_e']) && intval($_GET['exclude_e']) && count($this->X_model->fetch(array(
                'x__up IN (' . $_GET['exclude_e'] . ')' => null, //All of these
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
            continue;
        }

        if(isset($_GET['include_i']) && intval($_GET['include_i']) && count($this->X_model->fetch(array(
                'x__left IN (' . $_GET['include_i'] . ')' => null, //All of these
                'x__creator' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ))) != count(explode(',',$_GET['include_i']))){
            continue;
        }
        if(isset($_GET['exclude_i']) && intval($_GET['exclude_i']) && count($this->X_model->fetch(array(
                'x__left IN (' . $_GET['exclude_i'] . ')' => null, //All of these
                'x__creator' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
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
        foreach($column_ideas as $i){

            $discoveries = $this->X_model->fetch(array(
                'x__left' => $i['i__id'],
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
                        if(substr_count(strtolower($i['i__title']),$t.'x')==1){
                            $this_quantity = $t;
                            break;
                        }
                    }
                }

                if($i['i__id']==15736){
                    $name = $discoveries[0]['x__message'];
                }
            }

            $idea_content .= '<td >'.( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? ( isset($_GET['expand']) ? '<p title="'.$i['i__title'].': '.$discoveries[0]['x__message'].'" data-placement="top" '.$underdot_class.'>'.convertURLs($discoveries[0]['x__message']).'</p>' : '<span title="'.$i['i__title'].'" '.$underdot_class.'>✔️</span>'  ) : '<span title="'.$i['i__title'].'">✔️</span>' )  : '').'</td>';

            if(count($discoveries)){
                if(!isset($count_totals['i'][$i['i__id']])){
                    $count_totals['i'][$i['i__id']] = 0;
                }
                $count_totals['i'][$i['i__id']]++;
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

            $message_clean = ( count($fetch_data) ? ( strlen($fetch_data[0]['x__message']) ? ( isset($_GET['expand']) || in_array($e['e__id'], $this->config->item('n___37694')) || $input_modal ? preview_x__message($fetch_data[0]['x__message'], $fetch_data[0]['x__type']) : '<span '.$underdot_class.' title="'.$fetch_data[0]['x__message'].'">'.view_cover($e['e__cover'], '✔️', ' ').'</span>' ) : '<span class="icon-block-xxs">'.view_cover($e['e__cover'], '✔️', ' ').'</span>' ) : '' );


            $body_content .= '<td class="'.( superpower_active(28714, true) && !in_array($e['e__id'], $this->config->item('n___37695')) ? 'editable x__creator_'.$e['e__id'].'_'.$x['e__id'] : '' ).'" i__id="0" e__id="'.$e['e__id'].'" x__creator="'.$x['e__id'].'" input_modal="'.( $input_modal ? 1 : 0 ).'" x__id="'.$x['x__id'].'">'.$message_clean.'</td>';

            if(strlen($message_clean)>0){

                if(!isset($count_totals['e'][$e['e__id']])){
                    $count_totals['e'][$e['e__id']] = 0;
                }

                $count_totals['e'][$e['e__id']] = $count_totals['e'][$e['e__id']] + ( count($this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__down' => $e['e__id'],
                        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__up IN (' . join(',', $this->config->item('n___39609')) . ')' => null, //ADDUP NUMBER
                    ))) ? doubleval($x['x__message']) : 1 );
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
    $e___6287 = $this->config->item('e___6287'); //APP


    echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

    echo '<tr style="font-weight:bold; vertical-align: baseline;">';
    echo '<th id="th_primary" style="width:200px;">'.( isset($_GET['include_i']) || isset($_GET['include_e']) ? '<a href="/-13790?i__id='.$_GET['i__id'].'&e__id='.$_GET['e__id'].'&custom_grid='.$_GET['custom_grid'].'"><u><div class="filter_box hidden">REMOVE FILTERS <i class="fas fa-filter"></i></u></a></div><br />' : '' ).$count.' MEMBERS</th>';
    foreach($column_sources as $e){
        array_push($table_sortable, '#th_e_'.$e['e__id']);
        echo '<th id="th_e_'.$e['e__id'].'"><a class="icon-block-xxs" href="/@'.$e['e__id'].'" target="_blank" title="Open in New Window">'.view_cover($e['e__cover'], '✔️', ' ').'</a><span class="vertical_col"><a class="filter_box hidden" href="/-13790?i__id='.$_GET['i__id'].'&e__id='.$_GET['e__id'].'&custom_grid='.$_GET['custom_grid'].'&include_e='.$e['e__id'].'&include_i='.( isset($_GET['include_i']) ? $_GET['include_i'] : '' ).'">'.( isset($_GET['include_e']) && $_GET['include_e']==$e['e__id'] ? '<i class="fas fa-filter"></i>' : '<i class="fal fa-filter"></i>' ).'</a><a class="filter_box hidden" href="/-26582?e__id='.$e['e__id'].'" target="_blank" title="'.$e___6287[26582]['m__title'].'">'.$e___6287[26582]['m__cover'].'</a><span class="col_stat">'.( isset($count_totals['e'][$e['e__id']]) ? $count_totals['e'][$e['e__id']] : '0' ).'</span><i class="fas fa-sort"></i>'.$e['e__title'].'</span></th>';
    }
    foreach($column_ideas as $i){

        $has_limits = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__right' => $i['i__id'],
            'x__up' => 26189,
        ), array(), 1);
        $current_x = ( isset($count_totals['i'][$i['i__id']]) ? $count_totals['i'][$i['i__id']] : 0 );
        $max_limit = (count($has_limits) && is_numeric($has_limits[0]['x__message']) && intval($has_limits[0]['x__message'])>0 ? intval($has_limits[0]['x__message']) : 0 );

        array_push($table_sortable, '#th_i_'.$i['i__id']);

        echo '<th id="th_i_'.$i['i__id'].'"><a class="icon-block-xxs" href="/~'.$i['i__id'].'" target="_blank" title="Open in New Window">'.$e___4737[$i['i__type']]['m__cover'].'</a><span class="vertical_col"><a href="/-13790?i__id='.$_GET['i__id'].'&e__id='.$_GET['e__id'].'&custom_grid='.$_GET['custom_grid'].'&include_i='.$i['i__id'].'&include_e='.( isset($_GET['include_e']) ? $_GET['include_e'] : '' ).'">'.( isset($_GET['include_i']) && $_GET['include_i']==$i['i__id'] ? '<i class="fas fa-filter"></i>' : '<i class="fal fa-filter"></i>' ).'</a><a href="/-26582?i__id='.$i['i__id'].'" target="_blank" title="'.$e___6287[26582]['m__title'].'">'.$e___6287[26582]['m__cover'].'</a><span class="col_stat '.( $max_limit ? ( $current_x>=$max_limit ? 'isgreen'  : ( ($current_x/$max_limit)>=0.5 ? 'isgold' : 'isred' ) ) : '' ).'">'.$current_x.( $max_limit ? '/'.$max_limit : '').'</span><i class="fas fa-sort"></i>'.( strlen($i['x__message']) ? $i['x__message'] : $i['i__title'] ).'</span></th>';

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
            height:55px;
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