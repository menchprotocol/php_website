<?php

//Migrate to new

$count = 0;
$message_included = 0;
foreach($this->I_model->fetch(array(
    'i__id > 0' => null,
), 0, 0, array('i__id' => 'ASC')) as $loaded_i){
    $count++;

    //Messages?
    $messages = $this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $loaded_i['i__id'],
    ), array(), 0, 0, array('x__weight' => 'ASC'));

    $message_included = $message_included + ( count($messages) ? 1 : 0 );

    $message_simple = '';
    foreach($messages as $x) {
        $message_simple .= $x['x__message']."\n";
    }

}

echo '['.$count.' COUNT]<br />';
echo '['.$message_included.' Messaged]';