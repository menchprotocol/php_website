<?php

if(!isset($_GET['i__id']) || !$_GET['i__id']){

    echo '<form method="GET" action="">';

    echo '<div class="form-group" style="max-width:550px; margin:1px 0 10px; display: inline-block;">
                    <div class="input-group border">
                        <span class="input-group-addon addon-lean addon-grey" style="color:#222222; font-weight: 300;">Start #</span>
                        <input style="padding-left:3px; min-width:56px;" type="number" name="i__id" value="'.( isset($_GET['i__id']) ? $_GET['i__id'] : '' ).'" class="form-control">
                        
                        <br />
                        <span class="input-group-addon addon-lean addon-grey" style="color:#222222; font-weight: 300;">Idea Tree #</span>
                        <input style="padding-left:3px; min-width:56px;" type="number" name="i__tree_id" value="'.( isset($_GET['i__tree_id']) ? $_GET['i__tree_id'] : '' ).'" class="form-control">
                        
                        <br />
                        <span class="input-group-addon addon-lean addon-grey" style="color:#222222; font-weight: 300;">Sources of </span>
                        <input style="padding-left:3px; min-width:56px;" type="number" name="e__id" value="'.( isset($_GET['e__id']) ? $_GET['e__id'] : '' ).'" class="form-control">

                    </div>
                </div>
                <input type="submit" class="btn btn-12273" value="Go" style="display: inline-block; margin-top: -41px;" />';
    echo '</form>';

} else {

    //Fetch Main Idea:
    $is = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
    ));
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
    $filtered_count = 0;
    $all_emails = array();
    $skip_filter = array();
    $count_totals = array(
        'e' => array(),
        'i' => array(),
    );
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
        'x__left' => $_GET['i__id'],
    ), array('x__source'), 0, 0, array('x__time' => 'ASC')) as $count => $x){

        if(!isset($_GET['csv'])){
            $body_content .= '<tr class="tr__'.$x['e__id'].'">';
        }


        //Member
        $completion_rate = $this->X_model->completion_progress($x['e__id'], $is[0]);

        if(!isset($_GET['csv'])){
            $body_content .= '<td><a href="/@'.$x['e__id'].'" style="font-weight:bold;"><u>'.$x['e__title'].'</u></a></td>';
            $body_content .= '<td>'.( $completion_rate['completion_percentage']>=100 ? '<span title="Completed 100%" data-toggle="tooltip" data-placement="top">✅</span>' : str_pad($completion_rate['completion_percentage'], 2, '0', STR_PAD_LEFT).'%' ).'</td>';
        } else {
            $body_content .= $x['e__title'].",".$completion_rate['completion_percentage'].'%'.",";
        }



        //SOURCES
        foreach($column_sources as $e){

            $fetch_data = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => $e['e__id'],
            ));

            $message_clean = ( count($fetch_data) ? ( strlen($fetch_data[0]['x__message']) ? view_x__message($fetch_data[0]['x__message'], $fetch_data[0]['x__type']) : view_cover(12273,$e['e__cover']) ) : '' );

            if(isset($_GET['e_filter']) && $_GET['e_filter']==$e['e__id'] && count($fetch_data)){
                array_push($skip_filter, $x['e__id']);
            }

            if(!isset($_GET['csv'])){
                $body_content .= '<td>'.$message_clean.'</td>';

                if(strlen($message_clean)>0){
                    if(!isset($count_totals['e'][$e['e__id']])){
                        $count_totals['e'][$e['e__id']] = 0;
                    }
                    $count_totals['e'][$e['e__id']] += ( in_array(e_x__type($fetch_data[0]['x__message']), $this->config->item('n___26111')) ? preg_replace("/[^0-9.]/", '', $fetch_data[0]['x__message']) : 1 );
                }
            } else {
                $body_content .= $message_clean.",";
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
            if(!isset($_GET['csv'])){

                if(isset($_GET['i_filter']) && $_GET['i_filter']==$i['i__id'] && !count($discoveries)){
                    array_push($skip_filter, $x['e__id']);
                }

                $body_content .= '<td>'.( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? '<span title="'.$i['i__title'].': '.$discoveries[0]['x__message'].'" data-toggle="tooltip" data-placement="top" class="underdot">'.view_cover(12273,$i['i__cover']).'</span>' : '<span title="'.$i['i__title'].'" data-toggle="tooltip" data-placement="top">'.view_cover(12273,$i['i__cover']) ).'</span>'  : '').'</td>';

                if(count($discoveries)){
                    if(!isset($count_totals['i'][$i['i__id']])){
                        $count_totals['i'][$i['i__id']] = 0;
                    }
                    $count_totals['i'][$i['i__id']] += ( strlen($discoveries[0]['x__message'])>0 && in_array(e_x__type($discoveries[0]['x__message']), $this->config->item('n___26111')) ? preg_replace("/[^0-9.]/", '', $discoveries[0]['x__message']) : 1 );
                }

            } else {
                $body_content .= ( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? $discoveries[0]['x__message'] : view_cover(12273,$i['i__cover']) )  : '&nbsp;').",";
            }
        }

        $e_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $x['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
        ));
        if(count($e_emails) && !in_array($x['e__id'], $skip_filter)){
            array_push($all_emails, $e_emails[0]['x__message']);
        }
        if(in_array($x['e__id'], $skip_filter)){
            $body_content = str_replace('tr__'.$x['e__id'],'hidden',$body_content);
        } else {
            $filtered_count++;
        }

        if(!isset($_GET['csv'])){
            //$body_content .= '<td>'.date("Y-m-d H:i:s", strtotime($x['x__time'])).'</td>';
            $body_content .= '</tr>';
        } else {
            //$body_content .= date("Y-m-d H:i:s", strtotime($x['x__time']))."\n";
        }


    }

    if(!isset($_GET['csv'])){

        $table_sortable = array('#th_members','#th_done');

        echo '<h2><a href="/i/i_go/'.$is[0]['i__id'].'">'.$is[0]['i__title'].'</a></h2>';
        echo '<table style="font-size:0.8em;" id="registry_table" class="table table-sm table-striped">';

        echo '<tr style="font-weight:bold; vertical-align: baseline;">';
        echo '<th id="th_members" style="width:200px;">'.( isset($_GET['i_filter']) || isset($_GET['e_filter']) ? '<a href="/-13790?i__id='.$_GET['i__id'].'&i__tree_id='.$_GET['i__tree_id'].'&e__id='.$_GET['e__id'].'"><u>REMOVE FILTERS <i class="fas fa-filter"></i></u></a><br /><br />' : '' ).($count+1).' MEMBERS</th>';
        echo '<th id="th_done">&nbsp;</th>';
        foreach($column_sources as $e){
            array_push($table_sortable, '#th_e_'.$e['e__id']);
            echo '<th id="th_e_'.$e['e__id'].'">'.view_cover(12274,$e['e__cover']).'<span class="vertical_col"><a href="/-13790?i__id='.$_GET['i__id'].'&i__tree_id='.$_GET['i__tree_id'].'&e__id='.$_GET['e__id'].'&e_filter='.$e['e__id'].'&i_filter='.( isset($_GET['i_filter']) ? $_GET['i_filter'] : '' ).'">'.( isset($_GET['e_filter']) && $_GET['e_filter']==$e['e__id'] ? '<i class="fas fa-filter"></i>' : '<i class="fal fa-filter"></i>' ).'</a><span class="col_stat">'.( isset($count_totals['e'][$e['e__id']]) ? $count_totals['e'][$e['e__id']] : '0' ).'</span><i class="fas fa-sort"></i>'.$e['e__title'].'</span></th>';
        }
        foreach($column_ideas as $i){
            $has_limits = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 4983, //References
                'x__right' => $i['i__id'],
                'x__up' => 26189,
            ), array(), 1);
            array_push($table_sortable, '#th_i_'.$i['i__id']);
            echo '<th id="th_i_'.$i['i__id'].'" class="icon-block-img">'.view_cover(12273,$i['i__cover']).'<span class="vertical_col"><a href="/-13790?i__id='.$_GET['i__id'].'&i__tree_id='.$_GET['i__tree_id'].'&e__id='.$_GET['e__id'].'&i_filter='.$i['i__id'].'&e_filter='.( isset($_GET['e_filter']) ? $_GET['e_filter'] : '' ).'">'.( isset($_GET['i_filter']) && $_GET['i_filter']==$i['i__id'] ? '<i class="fas fa-filter"></i>' : '<i class="fal fa-filter"></i>' ).'</a><span class="col_stat">'.( isset($count_totals['i'][$i['i__id']]) ? $count_totals['i'][$i['i__id']] : '0' ).(count($has_limits) && is_numeric($has_limits[0]['x__message']) && intval($has_limits[0]['x__message'])>0 ? '/'.$has_limits[0]['x__message'] : '').'</span><i class="fas fa-sort"></i>'.$i['i__title'].'</span></th>';
        }
        //echo '<th>STARTED</th>';
        echo '</tr>';
        echo $body_content;
        echo '</table>';

        echo '<div style="padding: 34px 0 8px;"><a href="https://mail.google.com/mail/u/0/?fs=1&tf=cm&to='.join(',',$all_emails).'&subject='.$is[0]['i__title'].'" target="_blank">Email '.$filtered_count.' members:</div>';
        echo '<textarea class="mono-space" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:377px; width: 100%; border-radius: 10px;">'.join(', ',$all_emails).'</textarea>';

    } else {

        echo 'MEMBER,DONE,';
        foreach($column_sources as $e){
            echo $e['e__title'].',';
        }
        foreach($column_ideas as $i){
            echo $i['i__title'].',';
        }
        //echo 'STARTED'."\n";
        //echo $body_content;

    }

}

?>


<style>
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
        var table = $('#registry_table');
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
