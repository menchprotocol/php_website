<?php

//$focus_i__hashtag

//Adds Idea to the Members read

$player_e = superpower_unlocked();
$e___11035 = $this->config->item('e___11035'); //Encyclopedia

//valid idea?
$is = $this->I_model->fetch(array(
    'LOWER(i__hashtag)' => strtolower($focus_i__hashtag),
    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
));
if(!count($is)){
    return redirect_message(view_memory(42903,14565), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Idea #'.$focus_i__hashtag.' is not active</div>');
}

//Check to see if added to read for logged-in members:
if(!$player_e){
    return redirect_message(view_app_link(4269).view_memory(42903,33286).$focus_i__hashtag);
}

//Add this Idea to their read If not there:
$next_i__hashtag = $focus_i__hashtag;

if(!$this->X_model->i_has_started($player_e['e__id'], $is[0]['i__hashtag'])){

    //is available?
    $i_is_discoverable = i_is_discoverable($is[0]);
    if(!$i_is_discoverable['status']){
        return redirect_message(view_memory(42903,33286).$i_is_discoverable['return_i__hashtag'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$i_is_discoverable['message'].'</div>');
    }

    //Is startable?
    if(!count($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
        'x__next' => $is[0]['i__id'],
        'x__following' => 4235,
    )))){
        return redirect_message(view_memory(42903,33286).$focus_i__hashtag, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>This idea is not startable.</div>');
    }

    //Add Starting Point:
    $this->X_model->create(array(
        'x__player' => $player_e['e__id'],
        'x__type' => 4235, //Get started
        'x__next' => $is[0]['i__id'],
        'x__previous' => $is[0]['i__id'],
    ));

    //Mark as complete:
    $this->X_model->x_read_only_complete($player_e['e__id'], $is[0]['i__id'], $is[0]);

    //Now return next idea:
    $next_i__hashtag = $this->X_model->find_next($player_e['e__id'], $is[0]['i__hashtag'], $is[0]);
    if(!$next_i__hashtag){
        //Failed to add to read:
        return redirect_message(home_url());
    }
}

//Go to this newly added idea:
return redirect_message(view_memory(42903,30795).$focus_i__hashtag.'/'.$next_i__hashtag);