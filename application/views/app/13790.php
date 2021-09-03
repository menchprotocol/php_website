<?php

if(!isset($_GET['i__id']) || !$_GET['i__id']){

    echo '<form method="GET" action="">';

    echo '<div class="form-group" style="max-width:550px; margin:1px 0 10px; display: inline-block;">
                    <div class="input-group border">
                        <span class="input-group-addon addon-lean addon-grey" style="color:#222222; font-weight: 300;">Start #</span>
                        <input style="padding-left:3px; min-width:56px;" type="text" name="i__id" value="'.( isset($_GET['i__id']) ? $_GET['i__id'] : '' ).'" class="form-control">
                        
                        <br />
                        <span class="input-group-addon addon-lean addon-grey" style="color:#222222; font-weight: 300;">Idea Tree #</span>
                        <input style="padding-left:3px; min-width:56px;" type="number" name="i__tree_id" value="'.( isset($_GET['i__tree_id']) ? $_GET['i__tree_id'] : '' ).'" class="form-control">
                       
                        <br />
                        <span class="input-group-addon addon-lean addon-grey" style="color:#222222; font-weight: 300;">Idea Tree #</span>
                       
                        <br />
                        <span class="input-group-addon addon-lean addon-grey" style="color:#222222; font-weight: 300;">Sources of </span>
                        <input style="padding-left:3px; min-width:56px;" type="number" name="e__id" value="'.( isset($_GET['e__id']) ? $_GET['e__id'] : '' ).'" class="form-control">

                    </div>
                </div>
                <input type="submit" class="btn btn-12273" value="Go" style="display: inline-block; margin-top: -41px;" />';
    echo '</form>';

} else {

    $underdot_class = ( !isset($_GET['expand']) ? ' class="underdot" ' : '' );

    //Fetch Main Idea:
    $e___6287 = $this->config->item('e___6287'); //APP
    $is = $this->I_model->fetch(array(
        'i__id IN (' . $_GET['i__id'] . ')' => null, //SOURCE LINKS
    ), 0, 0, array('i__id' => 'ASC'));
    if(!count($is)){
        die('Invalid Idea ID');
    }

    $column_sources = array();
    if(isset($_GET['e__id']) && strlen($_GET['e__id'])){
        $column_sources = $this->X_model->fetch(array(
            'x__up' => $_GET['e__id'], //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC'));
    }

    $column_ideas = array();

    if(isset($_GET['i__tree_id']) && strlen($_GET['i__tree_id'])){
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $_GET['i__tree_id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $x){
            array_push($column_ideas, $x);
        }
    }




    //Return UI:
    $body_content = '';
    $count_totals = array(
        'e' => array(),
        'i' => array(),
    );
    $unique_users = array();
    $count = 0;

    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
        'x__left IN (' . join(',', array($_GET['i__id'])) . ')' => null, //IDEA LINKS
    ), array('x__source'), 0, 0, array('x__time' => 'DESC')) as $x){

        if(in_array($x['e__id'], $unique_users)){
            continue;
        }

        if(isset($_GET['include_e']) && intval($_GET['include_e']) && count($this->X_model->fetch(array(
                'x__up IN (' . $_GET['include_e'] . ')' => null, //All of these
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ))) != count(explode(',',$_GET['include_e']))){
            continue;
        }
        if(isset($_GET['exclude_e']) && intval($_GET['exclude_e']) && count($this->X_model->fetch(array(
                'x__up IN (' . $_GET['exclude_e'] . ')' => null, //All of these
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
            continue;
        }

        if(isset($_GET['include_i']) && intval($_GET['include_i']) && count($this->X_model->fetch(array(
                'x__left IN (' . $_GET['include_i'] . ')' => null, //All of these
                'x__source' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ))) != count(explode(',',$_GET['include_i']))){
            continue;
        }
        if(isset($_GET['exclude_i']) && intval($_GET['exclude_i']) && count($this->X_model->fetch(array(
                'x__left IN (' . $_GET['exclude_i'] . ')' => null, //All of these
                'x__source' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
            continue;
        }


        array_push($unique_users, $x['e__id']);
        $body_content .= '<tr>';

        $this_top = $this->I_model->fetch(array(
            'i__id' => $x['x__left'],
        ));

        //Member
        $completion_rate = $this->X_model->completion_progress($x['e__id'], $this_top[0]);

        $body_content .= '<td><a href="/@'.$x['e__id'].'" style="font-weight:bold;"><u>'.$x['e__title'].'</u></a></td>';
        $body_content .= '<td>'.str_pad($completion_rate['completion_percentage'], 2, '0', STR_PAD_LEFT).'%</td>';



        //SOURCES
        foreach($column_sources as $e){

            $fetch_data = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => $e['e__id'],
            ));

            $message_clean = ( count($fetch_data) ? ( strlen($fetch_data[0]['x__message']) ? ( isset($_GET['expand']) ? view_cover(12273,$e['e__cover'], '✔️').' '.view_x__message($fetch_data[0]['x__message'], $fetch_data[0]['x__type']) : '<span '.$underdot_class.' title="'.$fetch_data[0]['x__message'].'">'.view_cover(12273,$e['e__cover'], '✔️').'</span>' ) : view_cover(12273,$e['e__cover'], '✔️') ) : '' );

            $body_content .= '<td>'.$message_clean.'</td>';

            if(strlen($message_clean)>0){
                if(!isset($count_totals['e'][$e['e__id']])){
                    $count_totals['e'][$e['e__id']] = 0;
                }
                //$count_totals['e'][$e['e__id']] += ( in_array(e_x__type($fetch_data[0]['x__message']), $this->config->item('n___26111')) ? preg_replace("/[^0-9.]/", '', $fetch_data[0]['x__message']) : 1 );
                $count_totals['e'][$e['e__id']]++;
            }
        }

        //IDEAS
        foreach($column_ideas as $i){

            $discoveries = $this->X_model->fetch(array(
                'x__left' => $i['i__id'],
                'x__source' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 1);

            $body_content .= '<td>'.( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? ( isset($_GET['expand']) || substr_count($i['i__title'], 'Full Name')  ? '<span title="'.$i['i__title'].': '.$discoveries[0]['x__message'].'" data-placement="top" '.$underdot_class.'>'.$discoveries[0]['x__message'].'</span>' : '<span title="'.$i['i__title'].': '.$discoveries[0]['x__message'].'" data-placement="top" '.$underdot_class.'>'.view_cover(12273,$i['i__cover'], '✔️').'</span>'  ) : '<span title="'.$i['i__title'].'" data-placement="top">'.view_cover(12273,$i['i__cover'], '✔️') ).'</span>'  : '').'</td>';

            if(count($discoveries)){
                if(!isset($count_totals['i'][$i['i__id']])){
                    $count_totals['i'][$i['i__id']] = 0;
                }
                //$count_totals['i'][$i['i__id']] += ( strlen($discoveries[0]['x__message'])>0 && in_array(e_x__type($discoveries[0]['x__message']), $this->config->item('n___26111')) ? preg_replace("/[^0-9.]/", '', $discoveries[0]['x__message']) : 1 );
                $count_totals['i'][$i['i__id']]++;
            }

        }

        $e_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $x['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
        ));

        $body_content .= '</tr>';
        $count++;

    }


    $table_sortable = array('#th_primary','#th_done');
    $e___6287 = $this->config->item('e___6287'); //APP

    //echo '<h1>'.$e___6287[13790]['m__title'].'</h1>';


    echo view_input_dropdown(27264, ( isset($_GET['expand']) ? 27266 : 27265 ));

    foreach($is as $loaded_i){
        echo '<h2><a href="/i/i_go/'.$loaded_i['i__id'].'"><a class="icon-block-img" href="/~'.$loaded_i['i__id'].'" target="_blank" title="Open in New Window">'.view_cover(12273,$loaded_i['i__cover']).'</a> '.$loaded_i['i__title'].'</a> <a href="/-26582?i__id='.$_GET['i__id'].'" target="_blank" title="'.$e___6287[26582]['m__title'].'">'.$e___6287[26582]['m__cover'].'</a></h2>';
    }

    echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

    echo '<tr style="font-weight:bold; vertical-align: baseline;">';
    echo '<th id="th_primary" style="width:200px;">'.( isset($_GET['include_i']) || isset($_GET['include_e']) ? '<a href="/-13790?i__id='.$_GET['i__id'].'&i__tree_id='.$_GET['i__tree_id'].'&e__id='.$_GET['e__id'].'"><u>REMOVE FILTERS <i class="fas fa-filter"></i></u></a><br /><br />' : '' ).$count.' MEMBERS</th>';
    echo '<th id="th_done">Done</th>';
    foreach($column_sources as $e){
        array_push($table_sortable, '#th_e_'.$e['e__id']);
        echo '<th id="th_e_'.$e['e__id'].'"><a class="icon-block-img" href="/@'.$e['e__id'].'" target="_blank" title="Open in New Window">'.view_cover(12274,$e['e__cover']).'</a><span class="vertical_col"><a href="/-13790?i__id='.$_GET['i__id'].'&i__tree_id='.$_GET['i__tree_id'].'&e__id='.$_GET['e__id'].'&include_e='.$e['e__id'].'&include_i='.( isset($_GET['include_i']) ? $_GET['include_i'] : '' ).'">'.( isset($_GET['include_e']) && $_GET['include_e']==$e['e__id'] ? '<i class="fas fa-filter"></i>' : '<i class="fal fa-filter"></i>' ).'</a><a href="/-26582?e__id='.$e['e__id'].'" target="_blank" title="'.$e___6287[26582]['m__title'].'">'.$e___6287[26582]['m__cover'].'</a><span class="col_stat">'.( isset($count_totals['e'][$e['e__id']]) ? $count_totals['e'][$e['e__id']] : '0' ).'</span><i class="fas fa-sort"></i>'.$e['e__title'].'</span></th>';
    }
    foreach($column_ideas as $i){

        $has_limits = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4983, //References
            'x__right' => $i['i__id'],
            'x__up' => 26189,
        ), array(), 1);
        $current_x = ( isset($count_totals['i'][$i['i__id']]) ? $count_totals['i'][$i['i__id']] : 0 );
        $max_limit = (count($has_limits) && is_numeric($has_limits[0]['x__message']) && intval($has_limits[0]['x__message'])>0 ? intval($has_limits[0]['x__message']) : 0 );

        array_push($table_sortable, '#th_i_'.$i['i__id']);

        echo '<th id="th_i_'.$i['i__id'].'"><a class="icon-block-img" href="/~'.$i['i__id'].'" target="_blank" title="Open in New Window">'.view_cover(12273,$i['i__cover']).'</a><span class="vertical_col"><a href="/-13790?i__id='.$_GET['i__id'].'&i__tree_id='.$_GET['i__tree_id'].'&e__id='.$_GET['e__id'].'&include_i='.$i['i__id'].'&include_e='.( isset($_GET['include_e']) ? $_GET['include_e'] : '' ).'">'.( isset($_GET['include_i']) && $_GET['include_i']==$i['i__id'] ? '<i class="fas fa-filter"></i>' : '<i class="fal fa-filter"></i>' ).'</a><a href="/-26582?i__id='.$i['i__id'].'" target="_blank" title="'.$e___6287[26582]['m__title'].'">'.$e___6287[26582]['m__cover'].'</a><span class="col_stat '.( $max_limit ? ( $current_x>=$max_limit ? 'isgreen'  : ( ($current_x/$max_limit)>=0.5 ? 'isorange' : 'isred' ) ) : '' ).'">'.$current_x.( $max_limit ? '/'.$max_limit : '').'</span><i class="fas fa-sort"></i>'.$i['i__title'].'</span></th>';

    }
    //echo '<th>STARTED</th>';
    echo '</tr>';
    echo $body_content;
    echo '</table>';

}

?>


<style>

    <?php if(!isset($_GET['expand'])){ echo ' td{ max-width: 89px !important; max-height: 89px !important; overflow: scroll; } '; } else { echo ' td{ font-size:1em !important; } '; } ?>

    td>span{
        display: block;
        max-width: 144px !important;
        max-height: 144px !important;
        overflow: scroll;
    }

    /* CSS Adjustments for Printing View */
    .fixed-top{
        background-color: transparent !important;
    }
    .top_nav{
        display:none !important;
    }
    .table-striped tr:nth-of-type(odd) td {
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust:exact;
    }
    .table-striped td {
        border-bottom: 1px dotted #f0f0f0 !important;
        font-size: 1.3em;
    }
    .fa-filter, .fa-sort{
        font-size: 1.1em !important;
        margin-bottom: 3px;
    }
    th{
        cursor: ns-resize !important;
        border: 0 !important;
    }


    th:hover, th:active{
        background-color: #FFF;
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

