<?php

/*
 *
 * A function that would run through all
 * object metadata variables and delete
 * all variables that are not indexed
 * as part of Variables Names
 *
 *
 * */


$var_index = var_index();
$stats = array(
    'invalid' => array(),
    'valid' => array(),
);

foreach($this->config->item('e___7277') as $e__id => $m) {

    if(!array_key_exists($e__id, $var_index)) {
        //Metadata must be a valid variable:
        continue;
    }

    //Cleanup Metadata Variables:
    if($e__id==6159){
        $query = $this->I_model->fetch(array());
        $object__type = 4535;
        $object__id_key = 'i__id';
    } elseif($e__id==6172){
        $query = $this->E_model->fetch(array());
        $object__type = 4536;
        $object__id_key = 'e__id';
    }

    foreach($query as $item) {
        if(strlen($item[$var_index[$e__id]])){
            //Has Metadata Set:
            foreach(unserialize($item[$var_index[$e__id]]) as $variable_name => $value){
                if(!in_array($variable_name, $var_index)){

                    //Invalid variable, Delete this:
                    update_metadata($object__type, $item[$object__id_key], array(
                        $variable_name => null,
                    ));

                    //Add to index:
                    if(!array_key_exists($variable_name, $stats['invalid'])) {
                        $stats['invalid'][$variable_name] = 0;
                    }
                    //Now Increment:
                    $stats['invalid'][$variable_name]++;

                } else {

                    //Valid:
                    if(!array_key_exists($variable_name, $stats['valid'])) {
                        $stats['valid'][$variable_name] = 0;
                    }
                    //Now Increment:
                    $stats['valid'][$variable_name]++;

                }
            }
        }
    }
}


if(count($stats['invalid']) > 0){
    //Did we have anything to delete? Report with system bug:
    $this->X_model->create(array(
        'x__message' => 'cron__7277() removed '.count($stats['invalid']).' unknown variables from metadatas. To prevent this from happening, register the variables via Variables Names @6212',
        'x__type' => 4246, //Platform Bug Reports
        'x__metadata' => $stats,
    ));
}

view_json($stats);
