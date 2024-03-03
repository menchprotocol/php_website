<?php

//die('not wired yet');
//$target_i__hashtag, $focus_i__hashtag

if(!$this->X_model->i_has_started($player_e['e__id'], $target_i__hashtag)) {
    return redirect_message(view_memory(42903,33286).$target_i__hashtag);
}

//Go to Next Idea:
$next_i__hashtag = $this->X_model->find_next($player_e['e__id'], $is[0]['i__hashtag'], $is[0]);
if($next_i__hashtag){

    return redirect_message(view_memory(42903,30795).$target_i__hashtag.'/'.$next_i__hashtag );

} else {

    //Mark as Complete
    $this->X_model->create(array(
        'x__player' => $player_e['e__id'],
        'x__type' => 14730, //COMPLETED 100%
        'x__next' => $is[0]['i__id'],
        //TODO Maybe log additional details like total ideas, time, etc
    ));

    return redirect_message(view_memory(42903,33286).$target_i__hashtag);

    //TODO Go to Rating or Checkout App since the entire tree is discovered

}
