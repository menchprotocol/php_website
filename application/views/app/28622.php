<?php

if(isset($_GET['e__id']) && intval($_GET['e__id'])) {

    //Fetch deleted links:
    echo '<div class="list-group list-grey">';
    foreach($this->X_model->fetch(array(
        'x__up=' . $_GET['e__id'] . ' OR x__down='.$_GET['e__id'] => null, //PUBLIC
    ), array(), 0, 0, array('x__id' => 'DESC')) as $x){
        echo view_x($x);
    }
    echo '</div>';


} else {
    echo 'Define e__id to get started...';
}