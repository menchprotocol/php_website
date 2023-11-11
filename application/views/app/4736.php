<?php

//Migrate to new

$count = 0;
foreach($this->I_model->fetch(array(
    'i__id > 0' => null,
), 0, 0, array('i__id' => 'ASC')) as $loaded_i){
    $count++;
}

echo '['.$count.' COUNT]';