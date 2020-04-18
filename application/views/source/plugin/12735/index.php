<?php

$stats = array(
    'ideas' => 0,
    'source_missing' => 0,
    'note_deleted' => 0,
    'is_deleted' => 0,
    'creator_missing' => 0,
    'creator_fixed' => 0,
    'source_duplicate' => 0,
);

//FInd and delete duplicate sources:
foreach($this->IDEA_model->in_fetch() as $in) {

    $stats['ideas']++;

    $is_deleted = !in_array($in['in_status_source_id'], $this->config->item('en_ids_7356'));

    //Scan sources:
    $in_sources = $this->READ_model->ln_fetch(array(
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
        'ln_next_idea_id' => $in['in_id'],
    ));
    $in_creators = $this->READ_model->ln_fetch(array(
        'ln_type_source_id' => 4250, //New Idea Created
        'ln_next_idea_id' => $in['in_id'],
    ));
    $in_notes = $this->READ_model->ln_fetch(array( //Idea Links
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Idea Notes
        'ln_next_idea_id' => $in['in_id'],
    ), array(), 0);

    if(!count($in_creators)) {
        $stats['creator_missing']++;
        $this->READ_model->ln_create(array(
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_next_idea_id' => $in['in_id'],
            'ln_content' => $in['in_title'],
            'ln_type_source_id' => 4250, //New Idea Created
        ));
    }


    if(!$is_deleted && !count($in_sources)){

        //Missing SOURCE

        $stats['source_missing']++;
        $creator_id = ( count($in_creators) ? $in_creators[0]['ln_creator_source_id'] : $session_en['en_id'] );
        $this->READ_model->ln_create(array(
            'ln_type_source_id' => 4983, //IDEA COIN
            'ln_creator_source_id' => $creator_id,
            'ln_parent_source_id' => $creator_id,
            'ln_content' => '@'.$creator_id,
            'ln_next_idea_id' => $in['in_id'],
        ));

    } elseif($is_deleted && count($in_notes)){

        //Extra SOURCES
        foreach($in_notes as $in_note){
            //Delete this link:
            $stats['note_deleted'] += $this->READ_model->ln_update($in_note['ln_id'], array(
                'ln_status_source_id' => 6173, //Link Deleted
            ), $session_en['en_id'], 10686 /* Idea Link Unlinked */);
        }

    } elseif(count($in_sources) >= 2){

        //See if duplicates:
        $found_duplicate = false;
        $sources = array();
        foreach($in_sources as $in_source){
            if(!in_array($in_source['ln_parent_source_id'], $sources)){
                array_push($sources, $in_source['ln_parent_source_id']);
            } else {
                $found_duplicate = true;
                break;
            }
        }

        if($found_duplicate){
            $stats['source_duplicate']++;
        }

    }
}

echo nl2br(print_r($stats, true));