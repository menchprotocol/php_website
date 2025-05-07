<?php

$filters = array(
    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42252')) . ')' => null, //Plain Chain
    'chainsourceup' => 28199,
);

//Give it some extra time in case they are in Paypal making the payment
$buffer_time = 300;

if(isset($_GET['ideahashtag']) && strlen($_GET['ideahashtag'])){
    foreach($this->Ideas->read(array(
        'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
    )) as $i){
        $filters['chainidearight'] = $i['ideaid'];
        $buffer_time = 0;
    }
}

$chains_deleted = 0;
$counter = 0;

//Go through all expire seconds ideas:
foreach($this->Chains->read($filters, array('chainidearight'), 0) as $expires){

    //Now go through everyone who idea_discovered this selection:
    foreach($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___7704')) . ')' => null, //Discovery Expansions
        'chainidealeft' => $expires['ideaid'],
    ), array('chainsourcecreator'), 0) as $x_progress){

        //Now see if the answer is completed:
        $answer_completed = $this->Chains->read(array(
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31777')) . ')' => null, //DISCOVERIES
            'chainidealeft' => $x_progress['chainidearight'],
            'chainsourcecreator' => $x_progress['sourceid'],
        ));
        $seconds_left = intval( intval( $expires['chainvalue']) + $buffer_time - (time() - strtotime($x_progress['chaintime'])));

        if(!count($answer_completed) && intval( $expires['chainvalue'])>0 && $seconds_left <= 0){

            //Answer not yet completed and no time left, delete response:
            $deleted = false;
            foreach($this->Chains->read(array(
                            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31777')) . ')' => null, //DISCOVERIES
                'chainidealeft' => $expires['ideaid'],
                'chainsourcecreator' => $x_progress['sourceid'],
            ), array(), 0) as $delete){

                $deleted = true;
                $this->Chains->delete($delete['chainid'], $source_session['sourceid']); //Time Expired

            }

            if($deleted){
                $chains_deleted++;
                echo '<div style="padding-left: 21px;">'.$chains_deleted.') <a href="'.view_memory(42903,42902).$x_progress['sourcehandle'].'">'.$x_progress['sourcevalue'].'</a>: '.$x_progress['chaintime'].' ? '.$x_progress['chainvalue'].' / <a href="'.view_app_chain(12722).'?chainid=' . $x_progress['chainid'] . '">'.$x_progress['chainid'].' / Answer: '.count($answer_completed).'</a> '.( !count($answer_completed) ? ( $seconds_left <= 0 ? ' DELETE ' : '['.$seconds_left.'] SEcs left' ) : '' ).' ('.intval( $expires['chainvalue']) .'+'. $buffer_time .'-'. time() .'-'. strtotime($x_progress['chaintime'] ).' = '.$seconds_left.')</div>';
            }


        }

        //Now see if they have responded and completed the answer to this question:
        $counter++;

    }

}

echo '<div style="text-align: center">'.$chains_deleted.'/'.$counter.' ideas expired.</div>';

if(isset($filters['chainidearight'])){
    foreach($this->Ideas->read(array('ideaid' => $filters['chainidearight'])) as $i){
        //We were deleting a single item, redirect back:
        js_php_redirect(timelimit . phpview_memory(42903, 33286) . $i['ideahashtag'], 0);
    }
}