<?php

//Update Idea Transaction Time:
$i__id = ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 );
$total_time = 0;
$total_scanned = 0;
$total_updated = 0;
$e___12822 = $this->config->item('e___12822');
$e___12955 = $this->config->item('e___12955'); //Idea Type Completion Time
$filters = array();
if($i__id > 0){
    $filters['i__id'] = $i__id;
} else {
    $filters['i__status IN (' . join(',', $this->config->item('n___7355')) . ')'] = null; //PUBLIC
}

foreach($this->I_model->fetch($filters) as $in){


    //First see if manually updated:
    if(count($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 10650,
            'x__right' => $i__id,
        ))) && $in['i__duration']!=config_var(12176)){
        //Yes, so we ignore:
        if($i__id){
            //Show details:
            echo $i__id.' Will be ignored since it was manually updated<hr />';
        }
        continue;
    }


    //Start by counting the title:
    $total_scanned++;
    $estimated_time = 0;


    //Idea Type Has Time?
    if(array_key_exists($in['i__type'], $e___12955)){
        //Yes, add Extra Time:
        $extra_time = intval($e___12955[$in['i__type']]['m_message']);
        $estimated_time += $extra_time;
        if($i__id){
            //Show details:
            echo $extra_time.' Seconds For being '.$e___12955[$in['i__type']]['m_title'].'<hr />';
        }
    }


    //Then count the title of next ideas:
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__left' => $in['i__id'],
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $is_next){
        $this_time = words_to_seconds($is_next['i__title']);
        $estimated_time += $this_time;
        if($i__id){
            //Show details:
            echo $this_time.' Seconds NEXT: '.$is_next['i__title'].'<hr />';
        }
    }


    //Fetch All Messages for this:
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $in['i__id'],
    ), array(), 0, 0, array('x__sort' => 'ASC')) as $message){

        //Count text in this message:
        $this_time = words_to_seconds(trim(str_replace('@' . $message['x__up'],'', $message['x__message'])));
        $estimated_time += $this_time;
        if($i__id){
            //Show details:
            echo $this_time.' Seconds MESSAGE: '.$message['x__message'].'<hr />';
        }


        //Any source references?
        if($message['x__up'] > 0){

            //Yes, see
            //Source Profile
            foreach($this->X_model->fetch(array(
                'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12822')) . ')' => null, //SOURCE LINK MESSAGE DISPLAY
                'x__down' => $message['x__up'],
            ), array('x__up'), 0, 0, array('e__id' => 'ASC' /* Hack to get Text first */)) as $e_profile) {

                if($e_profile['x__type'] == 4257 /* EMBED */){

                    //See if we have a Start/End time:
                    $string_references = extract_e_references($message['x__message']);
                    if($string_references['ref_time_found']){
                        $start_time = $string_references['ref_time_start'];
                        $end_time = $string_references['ref_time_end'];
                        $this_time = $end_time - $start_time;
                    } else {
                        $this_time = 90;
                    }

                } elseif($e_profile['x__type'] == 4255 /* TEXT */){

                    //Count Words:
                    $this_time = words_to_seconds($e_profile['x__message']);

                } elseif($e_profile['x__type'] == 4259 /* AUDIO */){

                    $this_time = 60;

                } elseif($e_profile['x__type'] == 4258 /* VIDEO */){

                    $this_time = 90;

                } else {

                    $this_time = 15;

                }

                $estimated_time += ( true ? 3 : $this_time ); //TEMPORARY OVERRISE TODO REMOVE LATER
                if($i__id){
                    //Show details:
                    echo '&nbsp;&nbsp;'.$this_time.' Seconds MESSAGE SOURCE ['.$e___12822[$e_profile['x__type']]['m_title'].']: '.$e_profile['x__message'].'<hr />';
                }
            }
        }
    }

    $estimated_time = round($estimated_time);
    if($i__id){
        //Show details:
        echo $estimated_time.' SECONDS TOTAL<hr />';
    }

    //Update if necessary:
    if($estimated_time != $in['i__duration']){
        $this->I_model->update($in['i__id'], array(
            'i__duration' => $estimated_time,
        ));
        $total_updated++;
    }

    $total_time += $estimated_time;
}

//Return results:
echo $total_updated.' of '.$total_scanned.' Ideas Updated with new estimated times totalling '.round(($total_time/3600), 1).' Hours.';