<?php

if(!isset($_GET['i__id']) || !$_GET['i__id']){

    echo '<form method="GET" action="">';

    echo '<div class="form-group" style="max-width:550px; margin:1px 0 10px; display: inline-block;">
                    <div class="input-group border">
                        <span class="input-group-addon addon-lean addon-grey" style="color:#000000; font-weight: 300;">Start #</span>
                        <input style="padding-left:3px; min-width:56px;" type="number" name="i__id" value="'.( isset($_GET['i__id']) ? $_GET['i__id'] : '' ).'" class="form-control">
                        
                        <br />
                        <span class="input-group-addon addon-lean addon-grey" style="color:#000000; font-weight: 300;">Idea Tree #</span>
                        <input style="padding-left:3px; min-width:56px;" type="number" name="i__tree_id" value="'.( isset($_GET['i__tree_id']) ? $_GET['i__tree_id'] : '' ).'" class="form-control">
                        
                        <br />
                        <span class="input-group-addon addon-lean addon-grey" style="color:#000000; font-weight: 300;">Sources of </span>
                        <input style="padding-left:3px; min-width:56px;" type="number" name="e_sources_id" value="'.( isset($_GET['e_sources_id']) ? $_GET['e_sources_id'] : '' ).'" class="form-control">

                    </div>
                </div>
                <input type="submit" class="btn btn-idea" value="Go" style="display: inline-block; margin-top: -41px;" />';
    echo '</form>';

} else {

    //Fetch Main Idea:
    $is = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
    ));
    if(!count($is)){
        die('Invalid Idea ID');
    }



    $column_ideas = array();
    $column_sources = array();
    if(isset($_GET['e_sources_id']) && strlen($_GET['e_sources_id'])){
        $column_sources = $this->X_model->fetch(array(
            'x__up' => $_GET['e_sources_id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        ), array('x__down'), 0);
    }
    if(isset($_GET['i__tree_id']) && strlen($_GET['i__tree_id'])){
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $_GET['i__tree_id'],
        ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $x){
            array_push($column_ideas, $x);
        }
    }



    echo '<table style="width:'.( ( count($column_ideas) * 200 ) + ( count($column_sources) * 200 ) + 280  ).'px;">';

    echo '<tr style="font-weight:bold;">';
    echo '<td style="width:30px;">#</td>';
    echo '<td style="width:200px;">USER</td>';
    echo '<td style="width:50px;">DONE</td>';
    foreach($column_sources as $e){
        echo '<td style="width:200px;"><a href="/@'.$e['e__id'].'">'.$e['e__title'].'</a></td>';
    }
    foreach($column_ideas as $i){
        echo '<td style="width:200px;"><a href="/i/i_go/'.$i['i__id'].'">'.$i['i__title'].'</a></td>';
    }
    echo '</tr>';





    //Return UI:
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
        'x__left' => $_GET['i__id'],
    ), array('x__source'), 0, 0, array('x__id' => 'DESC')) as $count => $x){

        echo '<tr>';

        //User
        $completion_rate = $this->X_model->completion_progress($x['e__id'], $is[0]);
        echo '<td>'.($count+1).'</td>';
        echo '<td><a href="/@'.$x['e__id'].'" style="font-weight:bold;">'.$x['e__title'].'</a></td>';
        echo '<td>'.$completion_rate['completion_percentage'].'%</td>';

        //SOURCES
        foreach($column_sources as $e){
            $fetch_data = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => $e['e__id'],
            ));
            echo '<td>'.( count($fetch_data) ? ( strlen($fetch_data[0]['x__message']) > 0 ? $fetch_data[0]['x__message'] : '✅' ) : '❌' ).'</td>';
        }

        //IDEAS
        foreach($column_ideas as $i){
            $discovery = $this->X_model->fetch(array(
                'x__left' => $i['i__id'],
                'x__source' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 1);
            echo '<td>'.( count($discovery) ? ( strlen($discovery[0]['x__message']) > 0 ? $discovery[0]['x__message'] : '✅' )  : '❌').'</td>';
        }

        echo '</tr>';

    }
    echo '</table>';

}