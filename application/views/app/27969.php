<?php

function build_item($e__id, $i__id, $s__title, $s__cover, $link, $desc = null){

    return '<a href="/-27970?e__id='.$e__id.'&i__id='.$i__id.'&go_to='.urlencode($link).'" class="list-group-item list-group-item-action flex-column align-items-start">
    <div class="d-flex justify-content-between">
      <h4 class="css__title"><b><span class="icon-block-lg title-left">'.view_cover(($e__id>0 ? 12274 : 12273),$s__cover).'</span><span class="title-right">'.$s__title.'</span></b></h4>
      <small style="padding: 17px 3px 0 0;"><i class="far fa-chevron-right"></i></small>
    </div>
    '.( strlen($desc) ? '<p>'.$desc.'</p>' : '' ) .'
    
  </a>';

}

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
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
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
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PRIVATE
    ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC')) as $header){

        $list_body = '';

        //Any Startable Referenced Ideas?
        foreach(view_coins_e(12273, $header['e__id'], 1) as $ref_i){

            //Fetch Messages:
            $messages = ( strlen($ref_i['x__message']) ? '<div class="msg"><span>' . nl2br($ref_i['x__message']) . '</span></div>' : '');
            /*
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
                'x__type' => 4231, //IDEA NOTES Messages
                'x__right' => $ref_i['i__id'],
            ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {
                $messages .= $this->X_model->message_view( $x['x__message'], true, array(), 0, true);
            }
            */


            //Print list:
            $list_body .= build_item(0,$ref_i['i__id'], $ref_i['i__title'], $ref_i['i__cover'], '/'.$ref_i['i__id'] ,$messages);
        }

        //Any child sources?
        foreach($this->X_model->fetch(array(
            'x__up' => $header['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PRIVATE
        ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC')) as $list_e){


            //Make sure this has a valid URL:
            if(substr($list_e['x__message'], 0, 1)=='/'){

                //URL override in link message:
                $list_body .= build_item($list_e['e__id'],0, $list_e['e__title'], $list_e['e__cover'], $list_e['x__message'], null);

            } else {

                //Search for URL:
                foreach($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___4537')) . ')' => null, //SOURCE LINK URLS
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PRIVATE
                    'x__down' => $list_e['e__id'],
                ), array('x__up'), 0, 0, array('e__spectrum' => 'DESC')) as $url){
                    $list_body .= build_item($list_e['e__id'],0, $list_e['e__title'], $list_e['e__cover'], $url['x__message'], ( strlen($list_e['x__message']) ? '<div class="msg"><span>' . nl2br($list_e['x__message']) . '</span></div>' : '' ));
                }

            }
        }

        if($list_body){
            //Add this to the UI:
            $ui .= '<div class="mid-title grey">'.view_cover(12274,$header['e__cover']).'&nbsp;'.$header['e__title'].'</div>';
            $ui .= '<div class="list-group list-border">';
            $ui .= $list_body;
            $ui .= '</div>';
            $ui .= '<div class="doclear" style="padding-bottom: 34px;">&nbsp;</div>';
        }

    }

    echo $ui;
}

