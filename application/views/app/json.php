<?php

//Turns an @start #idea into JSON output
if(!isset($_GET['i__hashtag'])){
    die('Missing Idea ID i__hashtag');
}

//Is this user logged in?
$player_e = superpower_unlocked();


//Is this a starting point idea?
if(!count($this->Mench_ledger->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__player' => $x__player,
    'x__type' => 4235, //Get started
    'x__next' => $i['i__id'],
    'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
)))){
    die('Missing Idea ID i__hashtag');
}



$discoveries = $this->Mench_ledger->fetch(array(
    'x__previous' => $i_var['i__id'],
    'x__player' => $x['e__id'],
    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
), array(), 1);