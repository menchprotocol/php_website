<?php

//Update Idea Read Time:
$idea__id = ( isset($_GET['idea__id']) ? intval($_GET['idea__id']) : 0 );
$total_time = 0;
$total_scanned = 0;
$total_updated = 0;
$sources__12822 = $this->config->item('sources__12822');
$sources__12955 = $this->config->item('sources__12955'); //Idea Type Completion Time
$filters = array();
if($idea__id > 0){
    $filters['idea__id'] = $idea__id;
} else {
    $filters['idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')'] = null; //PUBLIC
}

foreach($this->IDEA_model->fetch($filters) as $in){


    //First see if manually updated:
    if(count($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type' => 10650,
            'read__right' => $idea__id,
        ))) && $in['idea__duration']!=config_var(12176)){
        //Yes, so we ignore:
        if($idea__id){
            //Show details:
            echo $idea__id.' Will be ignored since it was manually updated<hr />';
        }
        continue;
    }


    //Start by counting the title:
    $total_scanned++;
    $estimated_time = 0;


    //Idea Type Has Time?
    if(array_key_exists($in['idea__type'], $sources__12955)){
        //Yes, add Extra Time:
        $extra_time = intval($sources__12955[$in['idea__type']]['m_desc']);
        $estimated_time += $extra_time;
        if($idea__id){
            //Show details:
            echo $extra_time.' Seconds For being '.$sources__12955[$in['idea__type']]['m_name'].'<hr />';
        }
    }


    //Then count the title of next ideas:
    foreach($this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'read__left' => $in['idea__id'],
    ), array('read__right'), 0, 0, array('read__sort' => 'ASC')) as $ideas_next){
        $this_time = words_to_seconds($ideas_next['idea__title']);
        $estimated_time += $this_time;
        if($idea__id){
            //Show details:
            echo $this_time.' Seconds NEXT: '.$ideas_next['idea__title'].'<hr />';
        }
    }


    //Fetch All Messages for this:
    foreach($this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'read__type' => 4231, //IDEA NOTES Messages
        'read__right' => $in['idea__id'],
    ), array(), 0, 0, array('read__sort' => 'ASC')) as $message){

        //Count text in this message:
        $this_time = words_to_seconds(trim(str_replace('@' . $message['read__up'],'', $message['read__message'])));
        $estimated_time += $this_time;
        if($idea__id){
            //Show details:
            echo $this_time.' Seconds MESSAGE: '.$message['read__message'].'<hr />';
        }


        //Any source references?
        if($message['read__up'] > 0){

            //Yes, see
            //Source Profile
            foreach($this->READ_model->fetch(array(
                'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',', $this->config->item('sources_id_12822')) . ')' => null, //SOURCE LINK MESSAGE DISPLAY
                'read__down' => $message['read__up'],
            ), array('read__up'), 0, 0, array('source__id' => 'ASC' /* Hack to get Text first */)) as $source_profile) {

                if($source_profile['read__type'] == 4257 /* EMBED */){

                    //See if we have a Start/End time:
                    $string_references = extract_source_references($message['read__message']);
                    if($string_references['ref_time_found']){
                        $start_time = $string_references['ref_time_start'];
                        $end_time = $string_references['ref_time_end'];
                        $this_time = $end_time - $start_time;
                    } else {
                        $this_time = 90;
                    }

                } elseif($source_profile['read__type'] == 4255 /* TEXT */){

                    //Count Words:
                    $this_time = words_to_seconds($source_profile['read__message']);

                } elseif($source_profile['read__type'] == 4259 /* AUDIO */){

                    $this_time = 60;

                } elseif($source_profile['read__type'] == 4258 /* VIDEO */){

                    $this_time = 90;

                } else {

                    $this_time = 15;

                }

                $estimated_time += $this_time;
                if($idea__id){
                    //Show details:
                    echo '&nbsp;&nbsp;'.$this_time.' Seconds MESSAGE SOURCE ['.$sources__12822[$source_profile['read__type']]['m_name'].']: '.$source_profile['read__message'].'<hr />';
                }
            }
        }
    }

    $estimated_time = round($estimated_time);
    if($idea__id){
        //Show details:
        echo $estimated_time.' SECONDS TOTAL<hr />';
    }

    //Update if necessary:
    if($estimated_time != $in['idea__duration']){
        $this->IDEA_model->update($in['idea__id'], array(
            'idea__duration' => $estimated_time,
        ));
        $total_updated++;
    }

    $total_time += $estimated_time;
}

//Return results:
echo $total_updated.'/'.$total_scanned.' Ideas Updated with new estimated times totalling '.round(($total_time/3600), 1).' Hours.';