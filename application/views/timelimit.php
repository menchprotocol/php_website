<?php

$filters = array(
    'linkvoid' => 0, //Not Void
    'linktype IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
    'linkup' => 28199,
);

//Give it some extra time in case they are in Paypal making the payment
$buffer_time = 300;

if(isset($_GET['ideahashtag']) && strlen($_GET['ideahashtag'])){
    foreach($this->Idea_cache->fetch(array(
        'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
    )) as $i){
        $filters['linkright'] = $i['ideaid'];
        $buffer_time = 0;
    }
}

$links_deleted = 0;
$counter = 0;

//Go through all expire seconds ideas:
foreach($this->Mench_ledger->fetch($filters, array('linkright'), 0) as $expires){

    //Now go through everyone who discovered this selection:
    foreach($this->Mench_ledger->fetch(array(
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansions
        'linkleft' => $expires['ideaid'],
    ), array('linkplayer'), 0) as $x_progress){

        //Now see if the answer is completed:
        $answer_completed = $this->Mench_ledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //DISCOVERIES
            'linkleft' => $x_progress['linkright'],
            'linkplayer' => $x_progress['playerid'],
        ));
        $seconds_left = intval( intval( $expires['linktext']) + $buffer_time - (time() - strtotime($x_progress['linktime'])));

        if(!count($answer_completed) && intval( $expires['linktext'])>0 && $seconds_left <= 0){

            //Answer not yet completed and no time left, delete response:
            $deleted = false;
            foreach($this->Mench_ledger->fetch(array(
                'linkvoid' => 0, //Not Void
                'linktype IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //DISCOVERIES
                'linkleft' => $expires['ideaid'],
                'linkplayer' => $x_progress['playerid'],
            ), array(), 0) as $delete){

                $deleted = true;
                $this->Mench_ledger->update($delete['linkid'], array(), $player_e['playerid']); //Time Expired

            }

            if($deleted){
                $links_deleted++;
                echo '<div style="padding-left: 21px;">'.$links_deleted.') <a href="'.view__memory(42903,42902).$x_progress['playerhandle'].'">'.$x_progress['playertext'].'</a>: '.$x_progress['linktime'].' ? '.$x_progress['linktext'].' / <a href="'.view__app_link(12722).'?linkid=' . $x_progress['linkid'] . '">'.$x_progress['linkid'].' / Answer: '.count($answer_completed).'</a> '.( !count($answer_completed) ? ( $seconds_left <= 0 ? ' DELETE ' : '['.$seconds_left.'] SEcs left' ) : '' ).' ('.intval( $expires['linktext']) .'+'. $buffer_time .'-'. time() .'-'. strtotime($x_progress['linktime'] ).' = '.$seconds_left.')</div>';
            }


        }

        //Now see if they have responded and completed the answer to this question:
        $counter++;

    }

}

echo '<div style="text-align: center">'.$links_deleted.'/'.$counter.' ideas expired.</div>';

if(isset($filters['linkright'])){
    foreach($this->Idea_cache->fetch(array('ideaid' => $filters['linkright'])) as $i){
        //We were deleting a single item, redirect back:
        js_php_redirect(timelimit . phpview__memory(42903, 33286) . $i['ideahashtag'], 0);
    }
}