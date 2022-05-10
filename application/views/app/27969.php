<?php

//Set default loading:
if(!isset($_GET['e__id']) && get_domain_setting(27972)>0){
    $_GET['e__id'] = get_domain_setting(27972);
}
//Set default loading:
if(!isset($_GET['i__id']) && get_domain_setting(14002) > 0){
    $_GET['i__id'] = get_domain_setting(14002);
}

if(isset($_GET['i__id'])){
    $is = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
    ));

    //IDEA TITLE
    echo '<h1 style="padding-top: 21px;"><span class="halfbg">' . $is[0]['i__title'] . '</span></h1>';

    //MESSAGES
    echo '<div class="center-frame">';
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $is[0]['i__id'],
    ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {

        $msg = $this->X_model->message_view( $x['x__message'], true);

        if(substr_count($msg, '//www.youtube.com/embed/')==1){
            //YouTube video link
            echo '<div class="video-frame vid-padding" style="text-align: center;"><a href="javascript:void(0)" onclick="video_play()"><i class="fad fa-play-circle" style="color: #FFF; font-size:8em !important;"></i></a></div>';
            echo '<div class="video-frame hidden">'.$msg.'</div>';
        } else {
            echo $msg;
        }
    }
    echo '</div>';

}


if(isset($_GET['e__id'])){

    $ui = '';
    foreach($this->X_model->fetch(array(
        'x__up' => $_GET['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
    ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC')) as $header){

        $list_body = '';

        //Any Startable Referenced Ideas?
        foreach($this->X_model->fetch(array(
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up' => $header['e__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC', 'i__spectrum' => 'DESC')) as $ref_i){

            //Fetch Messages:
            $messages = ( strlen($ref_i['x__message']) ? '<div class="msg"><span>' . nl2br($ref_i['x__message']) . '</span></div>' : '');
            /*
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
                'x__type' => 4231, //IDEA NOTES Messages
                'x__right' => $ref_i['i__id'],
            ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {
                $messages .= $this->X_model->message_view( $x['x__message'], true, array(), 0, true);
            }
            */


            //Print list:
            $list_body .= view_item(0,$ref_i['i__id'], $ref_i['i__title'], $ref_i['i__cover'], '/'.$ref_i['i__id'] ,$messages);
        }

        //Any child sources?
        foreach($this->X_model->fetch(array(
            'x__up' => $header['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
        ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC')) as $list_e){


            //Make sure this has a valid URL:
            if(substr($list_e['x__message'], 0, 1)=='/'){

                //URL override in link message:
                $list_body .= view_item($list_e['e__id'],0, $list_e['e__title'], $list_e['e__cover'], $list_e['x__message'], null);

            } else {

                //Search for URL:
                foreach($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___4537')) . ')' => null, //SOURCE LINK URLS
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
                    'x__down' => $list_e['e__id'],
                ), array('x__up'), 0, 0, array('e__spectrum' => 'DESC')) as $url){
                    $list_body .= view_item($list_e['e__id'],0, $list_e['e__title'], $list_e['e__cover'], $url['x__message'], ( strlen($list_e['x__message']) ? '<div class="msg"><span>' . nl2br($list_e['x__message']) . '</span></div>' : '' ));
                }

            }
        }

        if($list_body){
            //Add this to the UI:
            $ui .= '<h4 style="padding-top: 21px;"><span class="halfbg">' .$header['e__title'] . '</span></h4>';
            $ui .= '<div class="list-group list-border">';
            $ui .= $list_body;
            $ui .= '</div>';
            $ui .= '<div class="doclear" style="padding-bottom: 34px;">&nbsp;</div>';
        }

    }

    echo $ui;
}

