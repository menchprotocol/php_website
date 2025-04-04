<?php

$filters = array(
    'linkplayertype IN (' . join(',', $this->config->item('playerids___42252')) . ')' => null, //Plain Link
    'linkplayerup' => 28199,
);

//Give it some extra time in case they are in Paypal making the payment
$buffer_time = 300;

if(isset($_GET['ideahashtag']) && strlen($_GET['ideahashtag'])){
    foreach($this->Ideas->read(array(
        'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
    )) as $i){
        $filters['linkidearight'] = $i['ideaid'];
        $buffer_time = 0;
    }
}

$links_deleted = 0;
$counter = 0;

//Go through all expire seconds ideas:
foreach($this->Links->read($filters, array('linkidearight'), 0) as $expires){

    //Now go through everyone who discovered this selection:
    foreach($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___7704')) . ')' => null, //Discovery Expansions
        'linkidealeft' => $expires['ideaid'],
    ), array('linkplayercreator'), 0) as $x_progress){

        //Now see if the answer is completed:
        $answer_completed = $this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___31777')) . ')' => null, //DISCOVERIES
            'linkidealeft' => $x_progress['linkidearight'],
            'linkplayercreator' => $x_progress['playerid'],
        ));
        $seconds_left = intval( intval( $expires['linktext']) + $buffer_time - (time() - strtotime($x_progress['linktime'])));

        if(!count($answer_completed) && intval( $expires['linktext'])>0 && $seconds_left <= 0){

            //Answer not yet completed and no time left, delete response:
            $deleted = false;
            foreach($this->Links->read(array(
                            'linkplayertype IN (' . join(',', $this->config->item('playerids___31777')) . ')' => null, //DISCOVERIES
                'linkidealeft' => $expires['ideaid'],
                'linkplayercreator' => $x_progress['playerid'],
            ), array(), 0) as $delete){

                $deleted = true;
                $this->Links->delete($delete['linkid'], $player_e['playerid']); //Time Expired

            }

            if($deleted){
                $links_deleted++;
                echo '<div style="padding-left: 21px;">'.$links_deleted.') <a href="'.view_memory(42903,42902).$x_progress['playerhandle'].'">'.$x_progress['playertext'].'</a>: '.$x_progress['linktime'].' ? '.$x_progress['linktext'].' / <a href="'.view_app_link(12722).'?linkid=' . $x_progress['linkid'] . '">'.$x_progress['linkid'].' / Answer: '.count($answer_completed).'</a> '.( !count($answer_completed) ? ( $seconds_left <= 0 ? ' DELETE ' : '['.$seconds_left.'] SEcs left' ) : '' ).' ('.intval( $expires['linktext']) .'+'. $buffer_time .'-'. time() .'-'. strtotime($x_progress['linktime'] ).' = '.$seconds_left.')</div>';
            }


        }

        //Now see if they have responded and completed the answer to this question:
        $counter++;

    }

}

echo '<div style="text-align: center">'.$links_deleted.'/'.$counter.' ideas expired.</div>';

if(isset($filters['linkidearight'])){
    foreach($this->Ideas->read(array('ideaid' => $filters['linkidearight'])) as $i){
        //We were deleting a single item, redirect back:
        js_php_redirect(timelimit . phpview_memory(42903, 33286) . $i['ideahashtag'], 0);
    }
}