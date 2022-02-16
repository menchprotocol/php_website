<?php

$is = $this->I_model->fetch(array(
    'i__id' => get_domain_setting(14002),
));


//IDEA TITLE
echo '<h1>' . $is[0]['i__title'] . '</h1>';


//MESSAGES
echo '<div class="center-frame">';
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $is[0]['i__id'],
), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {
    echo $this->X_model->message_view( $x['x__message'], true);
}
echo '</div>';



echo view_coins();


//Info Boxes:
echo view_info_box();



//FEATURED IDEAS
$counter = 0;
$visible_ui = '';
$topic_id = intval(get_domain_setting(14877));
if($topic_id){
    //Go through Featured Categories:
    foreach($this->config->item('e___'.$topic_id) as $e__id => $m) {

        $query_filters = array(
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PRIVATE
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up' => $e__id,
        );
        $query = $this->X_model->fetch($query_filters, array('x__right'), view_memory(6404,13206), 0, array('i__spectrum' => 'DESC'));
        if(!count($query)){
            continue;
        }

        $ui = '<div class="row justify-content margin-top-down-half">';
        foreach($query as $i){
            $ui .= view_i(14877, 0, null, $i);
        }
        $query2 = $this->X_model->fetch($query_filters, array('x__right'), 1, 0, array(), 'COUNT(x__id) as totals');
        $ui .= '</div>';


        $visible_ui .= view_headline($e__id, null, $m, $ui, !$counter);
        $counter++;
    }
}
echo $visible_ui;




//SOCIAL FOOTER
echo view_social();


echo '<p style="font-size: 0.8em; text-align: center;"><a href="https://drive.google.com/file/d/1GiQAtYzmJjmaUPxrrwPBXr44t9FRrWlAPFEe-917__iWcI2xR07U3_N88jyk5K7ophvKRk3AQHhXRs_q/view?usp=sharing" target="_blank"><u>Us Humans Foundation</u></a> | 2017 - '.date('Y').'</p>';

?>