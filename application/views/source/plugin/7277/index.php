<?php

/*
 *
 * A function that would run through all
 * object metadata variables and delete
 * all variables that are not indexed
 * as part of Variables Names source @6232
 *
 *
 * */


//Fetch all valid variable names:
$valid_variables = array();
foreach($this->READ_model->fetch(array(
    'read__up' => 6232, //Variables Names
    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
    'LENGTH(read__message) > 0' => null,
), array('read__down'), 0) as $var_name){
    array_push($valid_variables, $var_name['read__message']);
}

//Now let's start the cleanup process...
$invalid_variables = array();

//Idea Metadata
foreach($this->IDEA_model->fetch(array()) as $in){

    if(strlen($in['idea__metadata']) < 1){
        continue;
    }

    foreach(unserialize($in['idea__metadata']) as $key => $value){
        if(!in_array($key, $valid_variables)){
            //Delete this:
            update_metadata(4535, $in['idea__id'], array(
                $key => null,
            ));

            //Add to index:
            if(!in_array($key, $invalid_variables)){
                array_push($invalid_variables, $key);
            }
        }
    }

}

//Player Metadata
foreach($this->SOURCE_model->fetch(array()) as $en){

    if(strlen($en['source__metadata']) < 1){
        continue;
    }

    foreach(unserialize($en['source__metadata']) as $key => $value){
        if(!in_array($key, $valid_variables)){
            //Delete this:
            update_metadata(4536, $en['source__id'], array(
                $key => null,
            ));

            //Add to index:
            if(!in_array($key, $invalid_variables)){
                array_push($invalid_variables, $key);
            }
        }
    }

}

$read__metadata = array(
    'invalid' => $invalid_variables,
    'valid' => $valid_variables,
);

if(count($invalid_variables) > 0){
    //Did we have anything to delete? Report with system bug:
    $this->READ_model->create(array(
        'read__message' => 'cron__7277() deleted '.count($invalid_variables).' unknown variables from idea/source metadatas. To prevent this from happening, register the variables via Variables Names @6232',
        'read__type' => 4246, //Platform Bug Reports
        'read__up' => 6232, //Variables Names
        'read__metadata' => $read__metadata,
    ));
}

view_json($read__metadata);
