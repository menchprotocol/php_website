<?php

$_GET['i__id'] = 19120; //Tech House 2023 New Years Celebration

echo '<div class="list-group">';
foreach(view_coins_i(11019, $_GET['i__id'], 1, false) as $referral_i){
    echo '<a href="/~'.$referral_i['i__id'].'" class="list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">'.$referral_i['i__title'].'</h5>
      <small></small>
    </div>
    <p class="mb-1"><span class="icon-block"><i class="fas fa-dollar"></i></span> 42.1 | <span class="icon-block"><i class="fas fa-eye"></i></span> '.view_coins_i(6255, $referral_i['i__id'], 0, false).'</p>
  </a>';
}

echo '</div>';