<?php

/*
 *
 * A function that would run through all
 * object metadata variables and delete
 * all variables that are not indexed
 * as part of Variables Names source @6232
 *
 * https://mench.com/source/6232
 *
 *
 * */


//Fetch all valid variable names:
$valid_variables = array();
foreach($this->LEDGER_model->ln_fetch(array(
    'ln_profile_source_id' => 6232, //Variables Names
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
    'LENGTH(ln_content) > 0' => null,
), array('en_portfolio'), 0) as $var_name){
    array_push($valid_variables, $var_name['ln_content']);
}

//Now let's start the cleanup process...
$invalid_variables = array();

//Idea Metadata
foreach($this->IDEA_model->in_fetch(array()) as $in){

    if(strlen($in['in_metadata']) < 1){
        continue;
    }

    foreach(unserialize($in['in_metadata']) as $key => $value){
        if(!in_array($key, $valid_variables)){
            //Delete this:
            update_metadata('in', $in['in_id'], array(
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
foreach($this->SOURCE_model->en_fetch(array()) as $en){

    if(strlen($en['en_metadata']) < 1){
        continue;
    }

    foreach(unserialize($en['en_metadata']) as $key => $value){
        if(!in_array($key, $valid_variables)){
            //Delete this:
            update_metadata('en', $en['en_id'], array(
                $key => null,
            ));

            //Add to index:
            if(!in_array($key, $invalid_variables)){
                array_push($invalid_variables, $key);
            }
        }
    }

}

$ln_metadata = array(
    'invalid' => $invalid_variables,
    'valid' => $valid_variables,
);

if(count($invalid_variables) > 0){
    //Did we have anything to delete? Report with system bug:
    $this->LEDGER_model->ln_create(array(
        'ln_content' => 'cron__7277() deleted '.count($invalid_variables).' unknown variables from idea/source metadatas. To prevent this from happening, register the variables via Variables Names @6232',
        'ln_type_source_id' => 4246, //Platform Bug Reports
        'ln_profile_source_id' => 6232, //Variables Names
        'ln_metadata' => $ln_metadata,
    ));
}

echo_json($ln_metadata);
