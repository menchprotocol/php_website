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
foreach($this->X_model->fetch(array(
    'x__up' => 6232, //Variables Names
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
    'LENGTH(x__message) > 0' => null,
), array('x__down'), 0) as $var_name){
    array_push($valid_variables, $var_name['x__message']);
}

//Now let's start the cleanup process...
$invalid_variables = array();

//Idea Metadata
foreach($this->I_model->fetch(array()) as $in){

    if(strlen($in['i__metadata']) < 1){
        continue;
    }

    foreach(unserialize($in['i__metadata']) as $key => $value){
        if(!in_array($key, $valid_variables)){
            //Delete this:
            update_metadata(4535, $in['i__id'], array(
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
foreach($this->E_model->fetch(array()) as $en){

    if(strlen($en['e__metadata']) < 1){
        continue;
    }

    foreach(unserialize($en['e__metadata']) as $key => $value){
        if(!in_array($key, $valid_variables)){
            //Delete this:
            update_metadata(4536, $en['e__id'], array(
                $key => null,
            ));

            //Add to index:
            if(!in_array($key, $invalid_variables)){
                array_push($invalid_variables, $key);
            }
        }
    }

}

$x__metadata = array(
    'invalid' => $invalid_variables,
    'valid' => $valid_variables,
);

if(count($invalid_variables) > 0){
    //Did we have anything to delete? Report with system bug:
    $this->X_model->create(array(
        'x__message' => 'cron__7277() deleted '.count($invalid_variables).' unknown variables from metadatas. To prevent this from happening, register the variables via Variables Names @6232',
        'x__type' => 4246, //Platform Bug Reports
        'x__up' => 6232, //Variables Names
        'x__metadata' => $x__metadata,
    ));
}

view_json($x__metadata);
