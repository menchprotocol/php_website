<?php

if(isset($_GET['e__id'])){

}

foreach($this->I_model->fetch(array(
    'e__id IN (' . $_GET['e__id'] . ')' => null,
    'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
)) as $header){



}