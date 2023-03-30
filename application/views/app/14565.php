<?php



//TITLE
$website_id = website_setting(0);
$expanded_space = in_array($website_id , $this->config->item('n___31025'));
$double_contact = in_array($website_id , $this->config->item('n___31029'));
if(in_array($website_id, $this->config->item('n___30984'))){
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'home_black_font\'); }); </script> ';
}





$primary_i = array();
$secondary_i_list = array();
foreach($this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 10573, //Watching
    'x__up' => $website_id,
), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $this_i){
    if(!count($primary_i)){
        $primary_i = $this_i;
    } else {
        //Add to secondary ideas:
        array_push($secondary_i_list, $this_i);
    }
}

//Secondary Ideas:
foreach($this->X_model->fetch(array(
    'x__up' => $website_id,
    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
    'x__type !=' => 10573, //Not Watching
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
), array('x__right'), 0, 0, array('x__weight' => 'ASC', 'i__weight' => 'DESC')) as $this_i){
    array_push($secondary_i_list, $this_i);
}

if(!count($primary_i)){
    //Try to assign a secondary ID as primary for now...
    foreach($secondary_i_list as $sec_i){
        $primary_i = $sec_i;
        break;
    }
}


echo '<h1 class="maxwidth" style="margin: '.( $expanded_space ? '144px auto 377px' : '89px auto 233px' ).' !important;">' . $primary_i['i__title'] . '</h1>';


//Start darker background:
echo '<div class="halfbg narrow-bar slim_flat">';


//MESSAGES
echo '<div class="center-frame hide-subline maxwidth">';
foreach($this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $primary_i['i__id'],
), array(), 0, 0, array('x__weight' => 'ASC')) as $count => $x) {

    $msg = $this->X_model->message_view( $x['x__message'], true);

    if(0 && substr_count($msg, '//www.youtube.com/embed/')==1){
        //YouTube video link
        echo '<div class="video-frame vid-padding" style="text-align: center;"><a href="javascript:void(0)" onclick="video_play()"><i class="fad fa-play-circle" style="font-size:8em !important;"></i></a></div>';
        echo '<div class="video-frame hidden">'.$msg.'</div>';
    } else {
        echo $msg;
    }

}
echo '</div>';




$minimal_home = intval(website_setting(32463));

//SOCIAL FOOTER
$domain_phone =  website_setting(28615);
$domain_email =  website_setting(28614);
$e___14925 = $this->config->item('e___14925'); //Domain Setting

$contact_us = '';
if(!$minimal_home && ($domain_phone || $domain_email)) {

    $contact_us .= '<ul class="social-footer">';
    if($domain_phone){
        $contact_us .= '<li><a href="tel:'.preg_replace("/[^0-9]/", "", $domain_phone).'" data-toggle="tooltip" data-placement="top" title="'.$e___14925[28615]['m__title'].'">'.$e___14925[28615]['m__cover'].' '.$domain_phone.'</a></li>';
    }

    if($domain_email){
        $contact_us .= '<li><a href="mailto:'.$domain_email.'" title="'.$e___14925[28614]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___14925[28614]['m__cover'].' '.$domain_email.'</a></li>';
    }
    $contact_us .= '</ul>';

}

if($double_contact && !$minimal_home){
    echo '<div style="padding: 20px 0;">';
    echo $contact_us;
    echo '</div>';
}







//Any Info Boxes?
foreach($this->E_model->scissor_e($website_id, 14903) as $e_item) {
    //Any Followers?
    $info_item = null;
    foreach($this->X_model->fetch(array(
        'x__up' => $e_item['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__access IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
    ), array('x__down'), 0, 0, array('x__weight' => 'ASC')) as $info_element) {
        $info_item .= '<div class="col-12 col-md-4">';
        $info_item .= '<div class="info_box">';
        if(filter_var($info_element['e__cover'], FILTER_VALIDATE_URL)){
            $info_item .= '<div class="info_box_cover">'.'<div class="center-cropped" style="background-image: url(\''.$info_element['e__cover'].'\');"></div>'.'</div>';
            $info_item .= '<div class="info_box_title css__title">'.$info_element['e__title'].'</div>';
        } else {
            $info_item .= '<div class="info_box_cover">'.view_cover(12274, $info_element['e__cover']).'</div>';
            $info_item .= '<div class="info_box_title css__title">'.$info_element['e__title'].'</div>';
        }
        if(strlen($info_element['x__message'])){
            $info_item .= '<div class="info_box_message">'.$info_element['x__message'].'</div>';
        }
        $info_item .= '</div>';
        $info_item .= '</div>';
    }

    if($info_item){
        echo '<h2 class="info-head">'.$e_item['e__title'].'</h2>';
        echo '<div class="row justify-content" style="margin-bottom: 89px;">'.$info_item.'</div>';
    }

}



//List Relevant Ideas in order:
$secondary_ideas = '';
foreach($secondary_i_list as $ref_i){
    $messages = ( strlen($ref_i['x__message']) ? '<div class="msg"><span>' . nl2br($ref_i['x__message']) . '</span></div>' : '');
    $secondary_ideas .= view_item(0,$ref_i['i__id'], $ref_i['i__title'], null, '/'.$ref_i['i__id'] ,$messages);
}
if(strlen($secondary_ideas)){
    echo '<div class="list-group list-border glossy-bg maxwidth">';
    echo $secondary_ideas;
    echo '</div>';
    echo '<div class="doclear" style="padding-bottom: 55px;">&nbsp;</div>';
}









//Social UI and contact us
$social_ui = '';
foreach($this->E_model->scissor_e($website_id, 14904) as $social_box) {
    $social_ui .= '<li><a href="/-14904?e__id='.$social_box['e__id'].'" title="'.$social_box['e__title'].'" data-toggle="tooltip" data-placement="top">'.view_cover(12274, $social_box['e__cover'], true).'</a></li>';
}
if($social_ui){
    echo '<div class="social-footer">';
    echo '<ul class="social-ul">';
    echo $social_ui;
    if($domain_phone){
        echo '<li><a href="tel:'.preg_replace("/[^0-9]/", "", $domain_phone).'" data-toggle="tooltip" data-placement="top" title="'.$e___14925[28615]['m__title'].'">'.$e___14925[28615]['m__cover'].'</a></li>';
    }
    if($domain_email){
        echo '<li><a href="mailto:'.$domain_email.'" title="'.$e___14925[28614]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___14925[28614]['m__cover'].'</a></li>';
    }
    echo '</ul>';
    echo '</div>';
} elseif(!$minimal_home) {
    echo $contact_us;
}

echo '</div>';







echo '<div class="bottom_spacer">&nbsp;</div>';

?>

<script type="text/javascript">
    $(window).scroll(function() {
        if($(window).scrollTop()  > 110) {
            $('.fixed-top').removeClass('top-header-position');
        } else {
            $('.fixed-top').addClass('top-header-position');
        }
    });
</script>
