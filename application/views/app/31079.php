<?php

$_GET['i__id'] = 19120; //Tech House 2023 New Years Celebration

$is = $this->I_model->fetch(array(
    'i__id' => $_GET['i__id'],
));

if(count($is)){



    $plays = view_coins_i(6255, $is[0]['i__id'], 0, false);
    $x_count = $this->X_model->fetch(array(
        'x__right' => $is[0]['i__id'],
        'x__type' => 7610, //MEMBER VIEWED DISCOVERY
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    ), array(), 0, 0, array(), 'COUNT(x__id) as total_count');
    $income = 0;
    $tickets = 0;

    echo '<div class="list-group" style="margin-bottom: 34px; border-bottom: 1px solid #CCC; padding-bottom: 21px;">';
    echo '<div class="list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">'.$is[0]['i__title'].'</h5>
      <small><a href="/~'.$is[0]['i__id'].'" style="color: #999999;">/'.$is[0]['i__id'].'</a></small>
    </div>
    <p class="mb-1">'.
        '<span class="data-block"><span class="icon-block-xs"><i class="fal fa-eye"></i></span>'.$x_count[0]['total_count'].'</span>'.
        '<span class="data-block"><span class="icon-block-xs"><i class="fal fa-play"></i></span>'.$plays.'</span>'.
        '<span class="data-block"><span class="icon-block-xs"><i class="fal fa-ticket"></i></span>'.$tickets.'</span>'.
        '<span class="data-block"><span class="icon-block-xs"><i class="fal fa-dollar-sign"></i></span>'.$income.'</span>'.
        '</p>
  </div>';

    echo '</div>';

    echo '<div class="list-group">';
    foreach(view_coins_i(11019, $_GET['i__id'], 1, false) as $referral_i){
        $plays = view_coins_i(6255, $referral_i['i__id'], 0, false);
        $x_count = $this->X_model->fetch(array(
            'x__right' => $referral_i['i__id'],
            'x__type' => 7610, //MEMBER VIEWED DISCOVERY
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0, 0, array(), 'COUNT(x__id) as total_count');
        $income = 0;
        $tickets = 0;

        echo '<div class="list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">'.$referral_i['i__title'].'</h5>
      <small><a href="/~'.$is[0]['i__id'].'" style="color: #999999;">/'.$referral_i['i__id'].'</a></small>
    </div>
    <p class="mb-1">'.
            '<span class="data-block"><span class="icon-block-xs"><i class="fal fa-eye"></i></span>'.$x_count[0]['total_count'].'</span>'.
            '<span class="data-block"><span class="icon-block-xs"><i class="fal fa-play"></i></span>'.$plays.'</span>'.
            '<span class="data-block"><span class="icon-block-xs"><i class="fal fa-ticket"></i></span>'.$tickets.'</span>'.
            '<span class="data-block"><span class="icon-block-xs"><i class="fal fa-dollar-sign"></i></span>'.$income.'</span>'.
            '</p>
  </div>';
    }

    echo '</div>';

}


