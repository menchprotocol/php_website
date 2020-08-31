<?php

if(!isset($_GET['i__id']) || !$_GET['i__id']){

    echo '<form method="GET" action="">';

    echo '<div class="form-group" style="max-width:550px; margin:1px 0 10px; display: inline-block;">
                    <div class="input-group border">
                        <span class="input-group-addon addon-lean addon-grey" style="color:#000000; font-weight: 300;">Completed #</span>
                        <input style="padding-left:3px; min-width:56px;" type="number" placeholder="7766" name="i__id" id="i__id" value="'.( isset($_GET['i__id']) ? $_GET['i__id'] : '' ).'" class="form-control">
                        <span class="input-group-addon addon-lean addon-grey" style="color:#000000; font-weight: 300;">& Chart Columns for #</span>

                        <input style="padding-left:3px; min-width:56px;" type="text" placeholder="123,345,456" name="i__list_ids" id="i__list_ids" value="'.( isset($_GET['i__list_ids']) ? $_GET['i__list_ids'] : '' ).'" class="form-control">

                    </div>
                </div>
                <input type="submit" class="btn btn-idea" value="Go" style="display: inline-block; margin-top: -41px;" />';
    echo '</form>';

} else {

    $ideas = array();
    $list_ids = ( isset($_GET['i__list_ids']) && strlen($_GET['i__list_ids']) ? explode(',', $_GET['i__list_ids']) : array() );



    echo '<table style="width:'.( ( count($list_ids) * 200 ) + 620  ).'px;">';
    echo '<tr style="font-weight:bold;">';
    echo '<td style="width:20px;">#</td>';
    echo '<td style="width:200px;">USER</td>';
    echo '<td style="width:200px;">EMAIL</td>';
    echo '<td style="width:200px;">PHONE</td>';
    foreach($list_ids as $list_id){

        $is = $this->I_model->fetch(array(
            'i__id' => $list_id,
            'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));

        if(count($is)){
            $ideas[$list_id] = $is[0];
        }

        echo '<td style="width:200px;">'.( count($is) ? '<a href="/~'.$ideas[$list_id]['i__id'].'" class="montserrat">'.$ideas[$list_id]['i__title'].'</a>' : '#'.$list_id.' INVALID' ).'</td>';

    }
    echo '</tr>';



    //Return UI:
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
        'x__left' => $_GET['i__id'],
    ), array('x__source'), config_var(11064), 0, array('x__id' => 'DESC')) as $count => $x){

        echo '<tr>';

        //User
        echo '<td>'.($count+1).'</td>';
        echo '<td><a href="/@'.$x['e__id'].'" style="font-weight:bold;">'.$x['e__title'].'</a></td>';

        //Fetch Phone & Email:
        $u_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $x['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288,
        ));
        $u_phones = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $x['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 4783,
        ));
        echo '<td>'.( count($u_emails) ? $u_emails[0]['x__message'] : '---' ).'</td>';
        echo '<td>'.( count($u_phones) ? $u_phones[0]['x__message'] : '---' ).'</td>';


        //List Idea Responses:
        foreach($list_ids as $list_id){
            //Fetch Response:
            $discovery = array();
            if(isset($ideas[$list_id])){
                $discovery = $this->X_model->fetch(array(
                    'x__left' => $ideas[$list_id]['i__id'],
                    'x__source' => $x['e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1);
            }

            echo '<td>'.( count($discovery) ? '✅ '.$discovery[0]['x__message']  : '❌').'</td>';
        }

        echo '</tr>';

    }
    echo '</table>';

}