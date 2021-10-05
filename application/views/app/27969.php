<?php

function build_item($e, $link, $desc){

    return '<a href="/-27970?e__id='.$e['e__id'].'&go_to='.urlencode($link).'" class="list-group-item list-group-item-action flex-column align-items-start">
    <div class="d-flex justify-content-between">
      <h3 class="mb-1"><b><span class="icon-block-lg">'.view_cover(12274,$e['e__cover']).'</span>'.$e['e__title'].'</b></h3>
      <small>&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>&nbsp;&nbsp;</small>
    </div>
    '.( strlen($desc) ? '<p class="mb-1" style="padding: 8px 3px 8px 52px;">'.$desc.'</p>' : '' ) .'
  </a>';

}

if(isset($_GET['e__id'])){

    $ui = '';

    foreach($this->E_model->fetch(array(
        'e__id IN (' . $_GET['e__id'] . ')' => null,
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    )) as $header){

        //Fetch all links for this link list
        $list_body = '';
        foreach($this->X_model->fetch(array(
            'x__up' => $header['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC')) as $list_e){

            //Make sure this has a valid URL:
            if(substr($list_e['x__message'], 0, 2)=='//'){

                //URL override in link message:
                $list_body .= build_item($list_e, $list_e['x__message']);

            } else {

                //Search for URL:
                foreach($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___4537')) . ')' => null, //SOURCE LINK URLS
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                    'x__down' => $list_e['e__id'],
                ), array('x__up'), 0, 0, array('e__spectrum' => 'DESC')) as $url){
                    $list_body .= build_item($list_e, $url['x__message'], $list_e['x__message']);
                }

            }
        }

        if($list_body){
            //Add this to the UI:
            $ui .= '<div class="css__title x-info grey"><span class="icon-block-lg">'.view_cover(12274,$header['e__cover']).'</span>'.$header['e__title'].'</div>';
            $ui .= '<div class="list-group" style="margin-bottom: 34px; border: 1px solid #000; border-radius: 13px; overflow: hidden;">';
            $ui .= $list_body;
            $ui .= '</div>';
        }
    }

    echo $ui;

} else {

    echo 'Enter source ID to begin';

}