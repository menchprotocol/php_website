<style>
    .list-border{
        border: 1px solid #000;
        border-radius: 13px;
        overflow: hidden;
        margin: 0 5px;
    }
    .list-border .list-group-item{
        border-bottom: 1px solid #000;
    }
    .list-border .list-group-item:last-of-type{
        border-bottom: 0 !important;
    }
</style>

<?php

function build_item($e__id, $i__id, $s__title, $s__cover, $link, $desc = null, $small_text = null){

    return '<a href="/-27970?e__id='.$e__id.'&i__id='.$i__id.'&go_to='.urlencode($link).'" class="list-group-item list-group-item-action flex-column align-items-start">
    <div class="d-flex justify-content-between">
      <h3 class="mb-1"><b><span class="icon-block-lg" style="margin-right: 5px;">'.view_cover(($e__id>0 ? 12274 : 12273),$s__cover).'</span>'.$s__title.'</b></h3>
      <small>&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>&nbsp;&nbsp;</small>
    </div>
    '.( strlen($desc) ? '<p class="mb-1" style="padding: 8px 3px 8px 57px;">'.$desc.'</p>' : '' ) .'
    '.( strlen($small_text) ? '<small>'.$small_text.'</small>' : '' ) .'
    
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
        foreach(view_coins_e(12273, $header['e__id'], 1) as $ref_i){
            if(i_is_startable($ref_i)){

                //Does it have any featured tags?
                $small_text = null;
                foreach($this->X_model->fetch(array(
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                    'x__up IN (' . join(',', $this->config->item('n___27980')) . ')' => null, //Link Tree Featured Tags
                    'x__right' => $ref_i['i__id'],
                ), array('x__up'), 0, 0) as $key_references){
                    $small_text .= '<div class="key-ref"><span class="icon-block">'.view_cover(12274,$key_references['e__cover']).'</span>'.$key_references['e__title'].'</div>';
                }

                //Print list:
                $list_body .= build_item(0,$ref_i['i__id'], $ref_i['i__title'], $ref_i['i__cover'], '/'.$ref_i['i__id'] ,$ref_i['x__message'], $small_text);
            }
        }

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
                $list_body .= build_item($list_e['e__id'],0, $list_e['e__title'], $list_e['e__cover'], $list_e['x__message']);

            } else {

                //Search for URL:
                foreach($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___4537')) . ')' => null, //SOURCE LINK URLS
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                    'x__down' => $list_e['e__id'],
                ), array('x__up'), 0, 0, array('e__spectrum' => 'DESC')) as $url){
                    $list_body .= build_item($list_e['e__id'],0, $list_e['e__title'], $list_e['e__cover'], $url['x__message'], $list_e['x__message']);
                }

            }
        }

        if($list_body){
            //Add this to the UI:
            $ui .= '<div class="css__title grey" style="padding: 10px;"><span class="icon-block">'.view_cover(12274,$header['e__cover']).'</span>'.$header['e__title'].'</div>';
            $ui .= '<div class="list-group list-border">';
            $ui .= $list_body;
            $ui .= '</div>';
            $ui .= '<div class="doclear" style="padding-bottom: 45px;">&nbsp;</div>';
        }

    }

    echo $ui;
}





if(isset($_GET['i__id'])){
    $is = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
    ));

    //IDEA TITLE
    echo '<h1 style="padding-top: 21px;">' . $is[0]['i__title'] . '</h1>';


    //MESSAGES
    echo '<div>';
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $is[0]['i__id'],
    ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $count => $x) {
        echo $this->X_model->message_view( $x['x__message'], true);
    }
    echo '</div>';
}