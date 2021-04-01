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
                        <input style="padding-left:3px; min-width:56px;" type="number" name="e_sources_id" value="'.( isset($_GET['e_sources_id']) ? $_GET['e_sources_id'] : '' ).'" class="form-control">

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




    $column_sources = $this->X_model->fetch(array(
        'x__up IN (' . join(',', ( isset($_GET['e_sources_id']) && strlen($_GET['e_sources_id']) ? array($_GET['e_sources_id'], 13861) : array(13861)) ) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
    ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC'));


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



    if(!isset($_GET['csv'])){
        echo '<table style="width:'.( ( count($column_ideas) * 200 ) + ( count($column_sources) * 200 ) + 480  ).'px;">';

        echo '<tr style="font-weight:bold;">';
        echo '<td style="width:200px;">MEMBER</td>';
        echo '<td style="width:50px;">DONE</td>';
        foreach($column_sources as $e){
            echo '<td style="width:200px;"><a href="/@'.$e['e__id'].'">'.$e['e__title'].'</a></td>';
        }
        foreach($column_ideas as $i){
            echo '<td style="width:200px;"><a href="/i/i_go/'.$i['i__id'].'">'.$i['i__title'].'</a></td>';
        }
        echo '<td style="width:200px;">STARTED</td>';
        echo '</tr>';
    }



    //Return UI:
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
        'x__left' => $_GET['i__id'],
    ), array('x__source'), 0, 0, array('x__time' => 'ASC')) as $count => $x){

        echo '<tr>';

        //Member
        $completion_rate = $this->X_model->completion_progress($x['e__id'], $is[0]);

        if(!isset($_GET['csv'])){
            echo '<td><a href="/@'.$x['e__id'].'" style="font-weight:bold;">'.$x['e__title'].'</a></td>';
            echo '<td>'.$completion_rate['completion_percentage'].'%</td>';
        } else {
            echo '<a href="/@'.$x['e__id'].'" style="font-weight:bold;">'.$x['e__title'].'</a>'."\t".$completion_rate['completion_percentage'].'%'."\t";
        }



        //SOURCES
        foreach($column_sources as $e){
            $fetch_data = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => $e['e__id'],
            ));
            if(!isset($_GET['csv'])){
                echo '<td>'.( count($fetch_data) ? ( strlen($fetch_data[0]['x__message']) > 0 ? $fetch_data[0]['x__message'] : '✅' ) : '' ).'</td>';
            } else {
                echo ( count($fetch_data) ? ( strlen($fetch_data[0]['x__message']) > 0 ? $fetch_data[0]['x__message'] : '✅' ) : '' )."\t";
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
                echo '<td>'.( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? $discoveries[0]['x__message'] : '✅' )  : '').'</td>';
            } else {
                echo ( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? $discoveries[0]['x__message'] : '✅' )  : '')."\t";
            }
        }

        if(!isset($_GET['csv'])){
            echo '<td>'.date("Y-m-d H:i:s", strtotime($x['x__time'])).'</td>';
            echo '</tr>';
        } else {
            echo date("Y-m-d H:i:s", strtotime($x['x__time']));
        }


    }

    if(!isset($_GET['csv'])){
        echo '</table>';
    }

}