<?php

$filters = array(
    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'LinkType IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
    'LinkUp' => 28199,
);

//Give it some extra time in case they are in Paypal making the payment
$buffer_time = 300;

if(isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag'])){
    foreach($this->Idea_cache->fetch(array(
        'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
    )) as $i){
        $filters['LinkRight'] = $i['i__id'];
        $buffer_time = 0;
    }
}

$links_deleted = 0;
$counter = 0;

//Go through all expire seconds ideas:
foreach($this->Mench_ledger->fetch($filters, array('LinkRight'), 0) as $expires){

    //Now go through everyone who discovered this selection:
    foreach($this->Mench_ledger->fetch(array(
        'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'LinkType IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansions
        'LinkLeft' => $expires['i__id'],
    ), array('LinkPlayer'), 0) as $x_progress){

        //Now see if the answer is completed:
        $answer_completed = $this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'LinkType IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'LinkLeft' => $x_progress['LinkRight'],
            'LinkPlayer' => $x_progress['e__id'],
        ));
        $seconds_left = intval( intval( $expires['LinkText']) + $buffer_time - (time() - strtotime($x_progress['LinkTime'])));

        if(!count($answer_completed) && intval( $expires['LinkText'])>0 && $seconds_left <= 0){

            //Answer not yet completed and no time left, delete response:
            $deleted = false;
            foreach($this->Mench_ledger->fetch(array(
                'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'LinkType IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'LinkLeft' => $expires['i__id'],
                'LinkPlayer' => $x_progress['e__id'],
            ), array(), 0) as $delete){

                $deleted = true;
                $this->Mench_ledger->update($delete['LinkId'], array(
                    'LinkPrivacy' => 6173, //Transaction Deleted
                ), $player_e['e__id'], 29085); //Time Expired

            }

            if($deleted){
                $links_deleted++;
                echo '<div style="padding-left: 21px;">'.$links_deleted.') <a href="'.view__memory(42903,42902).$x_progress['e__handle'].'">'.$x_progress['e__title'].'</a>: '.$x_progress['LinkTime'].' ? '.$x_progress['LinkText'].' / <a href="'.view__app_link(12722).'?LinkId=' . $x_progress['LinkId'] . '">'.$x_progress['LinkId'].' / Answer: '.count($answer_completed).'</a> '.( !count($answer_completed) ? ( $seconds_left <= 0 ? ' DELETE ' : '['.$seconds_left.'] SEcs left' ) : '' ).' ('.intval( $expires['LinkText']) .'+'. $buffer_time .'-'. time() .'-'. strtotime($x_progress['LinkTime'] ).' = '.$seconds_left.')</div>';
            }


        }

        //Now see if they have responded and completed the answer to this question:
        $counter++;

    }

}

echo '<div style="text-align: center">'.$links_deleted.'/'.$counter.' ideas expired.</div>';

if(isset($filters['LinkRight'])){
    foreach($this->Idea_cache->fetch(array('i__id' => $filters['LinkRight'])) as $i){
        //We were deleting a single item, redirect back:
        js_php_redirect(view__memory(42903,33286).$i['i__hashtag'], 0);
    }
}