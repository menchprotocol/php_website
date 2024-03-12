<?php

if(!$this->X_model->i_has_started($player_e['e__id'], $target_i['i__hashtag'])) {
    return redirect_message(view_memory(42903,33286).$target_i['i__hashtag']);
}

//Go to Next Idea:
$next_i__hashtag = $this->X_model->find_next($player_e['e__id'], $target_i['i__hashtag'], $focus_i);
if($next_i__hashtag){

    return redirect_message(view_memory(42903,30795).$target_i['i__hashtag'].'/'.$next_i__hashtag );

} else {

    //Mark as Complete
    $this->X_model->create(array(
        'x__player' => $player_e['e__id'],
        'x__type' => 14730, //COMPLETED 100%
        'x__previous' => $target_i['i__id'],
        'x__next' => $focus_i['i__id'],
    ));

    return redirect_message(view_memory(42903,33286).$target_i['i__hashtag']);

}
