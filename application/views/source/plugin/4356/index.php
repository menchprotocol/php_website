<?php

//Update Idea Read Time:
$in_id = ( isset($_GET['in_id']) ? intval($_GET['in_id']) : 0 );
$total_time = 0;
$total_scanned = 0;
$total_updated = 0;
$en_all_12822 = $this->config->item('en_all_12822');
$en_all_12955 = $this->config->item('en_all_12955'); //Idea Type Completion Time
$filters = array();
if($in_id > 0){
    $filters['in_id'] = $in_id;
} else {
    $filters['in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')'] = null; //PUBLIC
}

foreach($this->IDEA_model->in_fetch($filters) as $in){


    //First see if manually updated:
    if(count($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id' => 10650,
            'ln_next_idea_id' => $in_id,
        ))) && $in['in_time_seconds']!=config_var(12176)){
        //Yes, so we ignore:
        if($in_id){
            //Show details:
            echo $in_id.' Will be ignored since it was manually updated<hr />';
        }
        continue;
    }


    //Start by counting the title:
    $total_scanned++;
    $estimated_time = 0;


    //Idea Type Has Time?
    if(array_key_exists($in['in_type_source_id'], $en_all_12955)){
        //Yes, add Extra Time:
        $extra_time = intval($en_all_12955[$in['in_type_source_id']]['m_desc']);
        $estimated_time += $extra_time;
        if($in_id){
            //Show details:
            echo $extra_time.' Seconds For being '.$en_all_12955[$in['in_type_source_id']]['m_name'].'<hr />';
        }
    }


    //Then count the title of next ideas:
    foreach($this->LEDGER_model->ln_fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'ln_previous_idea_id' => $in['in_id'],
    ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $in__next){
        $this_time = words_to_seconds($in__next['in_title']);
        $estimated_time += $this_time;
        if($in_id){
            //Show details:
            echo $this_time.' Seconds NEXT: '.$in__next['in_title'].'<hr />';
        }
    }


    //Fetch All Messages for this:
    foreach($this->LEDGER_model->ln_fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        'ln_type_source_id' => 4231, //IDEA NOTES Messages
        'ln_next_idea_id' => $in['in_id'],
    ), array(), 0, 0, array('ln_order' => 'ASC')) as $message){

        //Count text in this message:
        $this_time = words_to_seconds(trim(str_replace('@' . $message['ln_profile_source_id'],'', $message['ln_content'])));
        $estimated_time += $this_time;
        if($in_id){
            //Show details:
            echo $this_time.' Seconds MESSAGE: '.$message['ln_content'].'<hr />';
        }


        //Any source references?
        if($message['ln_profile_source_id'] > 0){

            //Yes, see
            //Source Profile
            foreach($this->LEDGER_model->ln_fetch(array(
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12822')) . ')' => null, //SOURCE LINK MESSAGE DISPLAY
                'ln_portfolio_source_id' => $message['ln_profile_source_id'],
            ), array('en_profile'), 0, 0, array('en_id' => 'ASC' /* Hack to get Text first */)) as $parent_en) {

                if($parent_en['ln_type_source_id'] == 4257 /* EMBED */){

                    //See if we have a Start/End time:
                    $string_references = extract_source_references($message['ln_content'], true);
                    if($string_references['ref_time_found']){
                        $start_time = $string_references['ref_time_start'];
                        $end_time = $string_references['ref_time_end'];
                        $this_time = $end_time - $start_time;
                    } else {
                        $this_time = 90;
                    }

                } elseif($parent_en['ln_type_source_id'] == 4255 /* TEXT */){

                    //Count Words:
                    $this_time = words_to_seconds($parent_en['ln_content']);

                } elseif($parent_en['ln_type_source_id'] == 4259 /* AUDIO */){

                    $this_time = 60;

                } elseif($parent_en['ln_type_source_id'] == 4258 /* VIDEO */){

                    $this_time = 90;

                } else {

                    $this_time = 15;

                }

                $estimated_time += $this_time;
                if($in_id){
                    //Show details:
                    echo '&nbsp;&nbsp;'.$this_time.' Seconds MESSAGE SOURCE ['.$en_all_12822[$parent_en['ln_type_source_id']]['m_name'].']: '.$parent_en['ln_content'].'<hr />';
                }
            }
        }
    }

    $estimated_time = round($estimated_time);
    if($in_id){
        //Show details:
        echo $estimated_time.' SECONDS TOTAL<hr />';
    }

    //Update if necessary:
    if($estimated_time != $in['in_time_seconds']){
        $this->IDEA_model->in_update($in['in_id'], array(
            'in_time_seconds' => $estimated_time,
        ));
        $total_updated++;
    }

    $total_time += $estimated_time;
}

//Return results:
echo $total_updated.'/'.$total_scanned.' Ideas Updated with new estimated times totalling '.round(($total_time/3600), 1).' Hours.';