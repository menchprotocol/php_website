<?php

$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
$sources__12467 = $this->config->item('sources__12467'); //MENCH COINS
$load_max = config_var(11064);
$top_ideators = array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
    ideator_filter() => null,
);
$top_experts = array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
    ' EXISTS (SELECT 1 FROM mench_interactions WHERE source__id=read__down AND read__up IN (' . join(',', $this->config->item('sources_id_12864')) . ') AND read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7359')) /* PUBLIC */.')) ' => null,
);
$top_content = array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
    ' EXISTS (SELECT 1 FROM mench_interactions WHERE source__id=read__down AND read__up IN (' . join(',', $this->config->item('sources_id_3000')) . ') AND read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7359')) /* PUBLIC */.')) ' => null,
);
$top_readers = array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null, //READ COIN
    ' EXISTS (SELECT 1 FROM mench_interactions WHERE source__id=read__source AND read__up=4430 AND read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7359')) /* PUBLIC */.')) ' => null,
);


/*
if(1){ //Weekly

    //Week always starts on Monday:
    if(date('D') === 'Mon'){
        //Today is Monday:
        $start_date = date("Y-m-d");
    } else {
        $start_date = date("Y-m-d", strtotime('previous monday'));
    }
    $top_ideators['read__time >='] = $start_date.' 00:00:00'; //From beginning of the day
}
*/
?>
<div class="container">

    <?php


    //My Sources:
    if($session_source){

        echo '<div class="read-topic"><span class="icon-block">'.$sources__11035[12205]['m_icon'].'</span>'.$sources__11035[12205]['m_name'].'</div>';

        echo '<div class="list-group" style="margin-bottom:34px;">';
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12274')) . ')' => null, //SOURCE COIN
            'read__source' => $session_source['source__id'],
            'source__id' => $session_source['source__id'],
        ), array('read__down')) as $my_source){
            echo view_source($my_source);
        }
        echo '</div>';

    }


    //Top Ideators
    echo view_source_list(13202, $this->READ_model->fetch($top_ideators, array('read__up'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(read__id) as totals, source__id, source__title, source__icon, source__metadata, source__status, source__weight', 'source__id, source__title, source__icon, source__metadata, source__status, source__weight'));


    //Top Experts
    echo view_source_list(13205, $this->READ_model->fetch($top_experts, array('read__up'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(read__id) as totals, source__id, source__title, source__icon, source__metadata, source__status, source__weight', 'source__id, source__title, source__icon, source__metadata, source__status, source__weight'));


    //Top Content
    echo view_source_list(13203, $this->READ_model->fetch($top_content, array('read__up'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(read__id) as totals, source__id, source__title, source__icon, source__metadata, source__status, source__weight', 'source__id, source__title, source__icon, source__metadata, source__status, source__weight'));


    //Top Readers
    echo view_source_list(13204, $this->READ_model->fetch($top_readers, array('read__source'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(read__id) as totals, source__id, source__title, source__icon, source__metadata, source__status, source__weight', 'source__id, source__title, source__icon, source__metadata, source__status, source__weight'));



    ?>
</div>