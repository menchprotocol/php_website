
<style>
    a { text-decoration: none; }
</style>
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
    echo '<h2><a href="/i/i_go/'.$is[0]['i__id'].'">'.$is[0]['i__title'].'</h2>';


    $column_sources = $this->X_model->fetch(array(
        'x__up IN (' . join(',', ( isset($_GET['e__id']) && strlen($_GET['e__id']) ? array($_GET['e__id'], 13861) : array(13861)) ) . ')' => null, //PUBLIC
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






    //Return UI:
    $body_content = '';
    $all_emails = array();
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
            $body_content .= '<tr style="'.( !fmod($count,2) ? 'background-color:#FFFFFF;' : '' ).'">';
        }


        //Member
        $completion_rate = $this->X_model->completion_progress($x['e__id'], $is[0]);

        if(!isset($_GET['csv'])){
            $body_content .= '<td><a href="/@'.$x['e__id'].'" style="font-weight:bold;">'.$x['e__title'].'</a></td>';
            $body_content .= '<td>'.$completion_rate['completion_percentage'].'%</td>';
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

            $message_clean = ( count($fetch_data) ? ( strlen($fetch_data[0]['x__message']) ? ( $e['e__id']==3288 ? '<a href="mailto:'.$fetch_data[0]['x__message'].'?subject='.$is[0]['i__title'].'" title="'.$fetch_data[0]['x__message'].'" data-toggle="tooltip" data-placement="top">✉️</a>' : view_x__message($fetch_data[0]['x__message'], $fetch_data[0]['x__type'])  ) : '✅' ) : '' );

            if(count($fetch_data) &&  strlen($fetch_data[0]['x__message']) && $e['e__id']==3288){
                array_push($all_emails, $fetch_data[0]['x__message']);
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
                $body_content .= '<td>'.( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? '<span title="'.$discoveries[0]['x__message'].'" data-toggle="tooltip" data-placement="top">📝</span>' : '✅' )  : '').'</td>';

                if(count($discoveries)){
                    if(!isset($count_totals['i'][$i['i__id']])){
                        $count_totals['i'][$i['i__id']] = 0;
                    }
                    $count_totals['i'][$i['i__id']] += ( strlen($discoveries[0]['x__message'])>0 && in_array(e_x__type($discoveries[0]['x__message']), $this->config->item('n___26111')) ? preg_replace("/[^0-9.]/", '', $discoveries[0]['x__message']) : 1 );
                }

            } else {
                $body_content .= ( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? $discoveries[0]['x__message'] : '✅' )  : '&nbsp;').",";
            }
        }


        if(!isset($_GET['csv'])){
            //$body_content .= '<td>'.date("Y-m-d H:i:s", strtotime($x['x__time'])).'</td>';
            $body_content .= '</tr>';
        } else {
            //$body_content .= date("Y-m-d H:i:s", strtotime($x['x__time']))."\n";
        }


    }

    if(!isset($_GET['csv'])){

        echo '<table style="font-size:0.8em; width:100%;">';

        echo '<tr style="font-weight:bold; vertical-align: baseline;">';
        echo '<td style="width:200px;">'.($count+1).' MEMBERS</td>';
        echo '<td style="width:50px;">DONE</td>';
        foreach($column_sources as $e){
            echo '<td><a href="/@'.$e['e__id'].'" style="writing-mode: tb-rl; white-space: nowrap;">'.$e['e__title'].'<span style="height:50px; display:inline-block; text-align: right;">'.( isset($count_totals['e'][$e['e__id']]) ? $count_totals['e'][$e['e__id']] : '0' ).'</span></a>'.view_cover(12274,$e['e__cover']).'</td>';
        }
        foreach($column_ideas as $i){
            $has_limits = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 4983, //References
                'x__right' => $i['i__id'],
                'x__up' => 26189,
            ), array(), 1);
            echo '<td><a href="/i/i_go/'.$i['i__id'].'" style="writing-mode: tb-rl; white-space: nowrap;">'.$i['i__title'].'<span style="height:50px; display:inline-block; text-align: right;">'.( isset($count_totals['i'][$i['i__id']]) ? $count_totals['i'][$i['i__id']] : '0' ).(count($has_limits) && is_numeric($has_limits[0]['x__message']) && intval($has_limits[0]['x__message'])>0 ? '/'.$has_limits[0]['x__message'] : '').'</span></a>'.view_cover(12273,$i['i__cover']).'</td>';
        }
        //echo '<td>STARTED</td>';
        echo '</tr>';
        echo $body_content;
        echo '</table>';

        echo '<div style="padding: 34px 0 8px;">Copy & Paste to email all '.($count+1).' members:</div>';
        echo '<textarea class="mono-space" style="background-color:#FFFFFF; color:#222222 !important; padding:20px; font-size:0.8em; height:377px; width: 100%; border-radius: 10px;">'.join(', ',$all_emails).'</textarea>';

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