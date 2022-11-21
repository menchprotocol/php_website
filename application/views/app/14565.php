<?php

$domain_background = get_domain_setting(28621);


//Primary Idea:
if(!isset($_GET['i__id']) && strlen(get_domain_setting(14002))){
    $_GET['i__id'] = get_domain_setting(14002);
}

if(isset($_GET['i__id'])){
    
    $is = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
    ));

    if(count($is)){

        //TITLE
        echo '<h1 class="maxwidth" style="margin: 89px auto 387px !important;">' . $is[0]['i__title'] . '</h1>';

        //MESSAGES
        echo '<div class="center-frame hide-subline maxwidth">';
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $is[0]['i__id'],
        ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {

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
    }

}











//Sitemap:
if(!isset($_GET['e__id']) && strlen(get_domain_setting(27972))){
    $_GET['e__id'] = get_domain_setting(27972);
}
if(isset($_GET['e__id'])){

    $ui = '';
    foreach($this->X_model->fetch(array(
        'x__up' => $_GET['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
    ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC')) as $header){

        $list_body = '';

        //Any Startable Referenced Ideas?
        foreach($this->X_model->fetch(array(
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up' => $header['e__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC', 'i__spectrum' => 'DESC')) as $ref_i){

            //Fetch Messages:
            $messages = ( strlen($ref_i['x__message']) ? '<div class="msg"><span>' . nl2br($ref_i['x__message']) . '</span></div>' : '');
            /*
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 4231, //IDEA NOTES Messages
                'x__right' => $ref_i['i__id'],
            ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {
                $messages .= $this->X_model->message_view( $x['x__message'], true, array(), 0, true);
            }
            */

            $is_flat_list = count($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__up' => 30378, //Flat List Ideas
                'x__right' => $ref_i['i__id'],
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )));


            //Print list:
            $list_body .= view_item(0,$ref_i['i__id'], $ref_i['i__title'], null, ($is_flat_list ? '/:' : '/' ).$ref_i['i__id'] ,$messages);
        }

        /*
        //Any child sources?
        foreach($this->X_model->fetch(array(
            'x__up' => $header['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC')) as $list_e){


            //Make sure this has a valid URL:
            if(substr($list_e['x__message'], 0, 1)=='/'){

                //URL override in link message:
                $list_body .= view_item($list_e['e__id'],0, $list_e['e__title'], $list_e['e__cover'], $list_e['x__message'], null);

            } else {

                //Search for URL/Emails:
                foreach($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                    'x__down' => $list_e['e__id'],
                ), array('x__up'), 0, 0, array('e__spectrum' => 'DESC')) as $url){
                    if(filter_var($url['x__message'], FILTER_VALIDATE_EMAIL)){
                        $link = 'mailto:'.$url['x__message'];
                    } elseif(filter_var($url['x__message'], FILTER_VALIDATE_URL) || substr($url['x__message'], 0, 1)=='/'){
                        $link = $url['x__message'];
                    } else {
                        continue;
                    }
                    $list_body .= view_item($list_e['e__id'],0, $list_e['e__title'], $list_e['e__cover'], $link, ( strlen($list_e['x__message']) ? '<div class="msg"><span>' . nl2br($list_e['x__message']) . '</span></div>' : '' ));
                }

            }
        }
        */

        //Add this to the UI:
        if($list_body){
            $ui .= '<h4 style="padding-top: 34px; text-align:center;"><span class="halfbg" style="padding: 5px;"><span class="icon-block-xxs">'.view_cover(12273,$header['e__cover'], '✔️', ' ').'</span> ' .$header['e__title'] . ':</span></h4>';
            $ui .= '<div class="list-group list-border glossy-bg maxwidth">';
            $ui .= $list_body;
            $ui .= '</div>';
            $ui .= '<div class="doclear" style="padding-bottom: 55px;">&nbsp;</div>';
        }





    }

    echo $ui;

}









//Info Box(es):
$domain_info_boxes = get_domain_setting(14903);
if($domain_info_boxes){

    foreach($this->X_model->fetch(array(
        'x__up' => $domain_info_boxes,
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC')) as $info_box) {

        //Does it have valid children?
        $info_item = null;
        foreach($this->X_model->fetch(array(
            'x__up' => $info_box['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC')) as $info_element) {
            $info_item .= '<div class="col-12 col-sm-6 col-md-4 halfbg">';
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
            echo '<h2 class="info-head">'.$info_box['e__title'].'</h2>';
            echo '<div class="row justify-content" style="margin-bottom: 89px;">'.$info_item.'</div>';
        }

    }
}







$faq_i__id = get_domain_setting(30422);
if($faq_i__id){

    $is_faq = $this->I_model->fetch(array(
        'i__id' => $faq_i__id,
    ));

    echo '<div class="container-center">';

    //IDEA TITLE
    echo '<h2 class="info-head">' . $is_faq[0]['i__title'] . '</h2>';

    //MESSAGES
    foreach ($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $faq_i__id,
    ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
        echo $this->X_model->message_view($x['x__message'], true);
    }

    echo '<br /><br />';
    echo '<div class="halfbg">';

    //1 Level of Next Ideas:
    foreach ($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => $faq_i__id,
    ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $i) {

        echo '<h3 style="margin:13px 0; padding-left:5px; font-size:1.3em;"><a href="javascript:void(0);" onclick="$(\'.i_msg_'.$i['i__id'].'\').toggleClass(\'hidden\');" class="inner-content doblock css__title">' . $i['i__title'] . '</a></h3>';

        //MESSAGES
        echo '<div style="border-bottom: 1px solid #000;">';
        echo '<div class="i_msg_'.$i['i__id'].' hidden" style="padding:5px 5px 13px 21px;">';
        foreach ($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $i['i__id'],
        ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
            echo $this->X_model->message_view($x['x__message'], true);
        }
        echo '</div>';
        echo '</div>';

    }

    echo '</div>';
    echo '</div>';
}



//Featured Topics
/*
$topic_id = intval(get_domain_setting(27972));
if($topic_id && is_array($this->config->item('e___'.$topic_id))){
    $counter = 0;
    $visible_ui = '';
    //Go through Featured Categories:
    foreach($this->config->item('e___'.$topic_id) as $e__id => $m) {

        $query_filters = array(
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up' => $e__id,
        );
        $query = $this->X_model->fetch($query_filters, array('x__right'), view_memory(6404,13206), 0, array('i__spectrum' => 'DESC'));
        if(!count($query)){
            continue;
        }

        $ui = '<div class="row justify-content margin-top-down-half">';
        foreach($query as $i){
            $ui .= view_i(27972, 0, null, $i);
        }
        $query2 = $this->X_model->fetch($query_filters, array('x__right'), 1, 0, array(), 'COUNT(x__id) as totals');
        $ui .= '</div>';


        $visible_ui .= view_headline($e__id, null, $m, $ui, !$counter);
        $counter++;
    }
    echo $visible_ui;
}


*/


echo '<br /><br />';

//SOCIAL FOOTER
$social_id = intval(get_domain_setting(14904));
$domain_phone =  get_domain_setting(28615);
$domain_email =  get_domain_setting(28614);
$e___14925 = $this->config->item('e___14925'); //Domain Setting
if($social_id && is_array($this->config->item('e___'.$social_id))){

    echo '<div class="social-footer">';
    echo '<ul class="social-ul '.( $domain_background ? ' halfbg ' : '' ).'">';

    foreach($this->config->item('e___'.$social_id) as $e__id => $m) {
        echo '<li><a href="/-14904?e__id='.$e__id.'" title="'.$m['m__title'].'" data-toggle="tooltip" data-placement="top">'.$m['m__cover'].'</a></li>';
    }

    if($domain_phone){
        echo '<li><a href="tel:'.preg_replace("/[^0-9]/", "", $domain_phone).'" data-toggle="tooltip" data-placement="top" title="'.$e___14925[28615]['m__title'].'">'.$e___14925[28615]['m__cover'].'</a></li>';
    }

    if($domain_email){
        echo '<li><a href="mailto:'.$domain_email.'" title="'.$e___14925[28614]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___14925[28614]['m__cover'].'</a></li>';
    }

    echo '</ul>';
    echo '</div>';

    /*
    echo '<h4 style="padding-top: 34px; text-align:center;"><span class="halfbg" style="padding: 5px;"><span class="icon-block-xxs">'.$e___14925[14904]['m__cover'].'</span> ' .$e___14925[14904]['m__title'] . ':</span></h4>';
    echo '<div class="list-group list-border glossy-bg maxwidth">';

    foreach($this->config->item('e___'.$social_id) as $e__id => $m) {
        echo view_item(0,0, $m['m__title'], $m['m__cover'], '/-14904?e__id='.$e__id , $m['m__message'], true);
    }
    if($domain_phone){
        echo view_item(0,0, $domain_phone, $e___14925[28615]['m__cover'], 'tel:'.preg_replace("/[^0-9]/", "", $domain_phone) , $m['m__message'], true);
    }
    if($domain_email){
        echo view_item(0,0, $domain_email, $e___14925[28614]['m__cover'], 'mailto:'.$domain_email, $m['m__message'], true);
    }

    echo '</div>';
    echo '<div class="doclear" style="padding-bottom: 55px;">&nbsp;</div>';
    */

} elseif($domain_phone || $domain_email) {

    echo '<ul class="social-footer '.( $domain_background ? ' halfbg ' : '' ).'">';
    if($domain_phone){
        echo '<li><a href="tel:'.preg_replace("/[^0-9]/", "", $domain_phone).'" data-toggle="tooltip" data-placement="top" title="'.$e___14925[28615]['m__title'].'">'.$e___14925[28615]['m__cover'].' '.$domain_phone.'</a></li>';
    }

    if($domain_email){
        echo '<li><a href="mailto:'.$domain_email.'" title="'.$e___14925[28614]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___14925[28614]['m__cover'].' '.$domain_email.'</a></li>';
    }
    echo '</ul>';

}


if($domain_background){
    echo '<div class="doclear" style="padding-bottom:987px;">&nbsp;</div>';
}



?>