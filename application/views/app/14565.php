<?php

$is = $this->I_model->fetch(array(
    'i__id' => get_domain_setting(14002),
));

//IDEA TITLE
echo '<h1>' . $is[0]['i__title'] . '</h1>';


//MESSAGES
echo '<div style="padding-bottom: 21px;">';
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $is[0]['i__id'],
), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {
    echo $this->X_model->message_view( $x['x__message'], true);
}
echo '</div>';


//FEATURED IDEAS
echo view_i_featured();


//Info Boxes:
//echo view_info_box();


//SOCIAL FOOTER
$social_id = intval(get_domain_setting(14904));
if($social_id){
    echo '<ul class="social-footer">';
    foreach($CI->config->item('e___'.$social_id) as $e__id => $m) {
        echo '<li><a href="/-14904?e__id='.$e__id.'" title="'.$m['m__title'].'" data-toggle="tooltip" data-placement="top">'.$m['m__cover'].'</a></li>';
    }
    echo '</ul>';
}

?>