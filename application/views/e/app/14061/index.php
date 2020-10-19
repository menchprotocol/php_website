<?php

$custom_query = $this->X_model->fetch(array(
    "x__type IN (4983,7545,13865,4231,13970,13971,10573,4601,12419,12896) AND x__status=6176 AND x__message LIKE '%:%' AND x__message LIKE '%@%'" => null,
), array(), 0);

//Update format of video slicing
echo '<div>Found '.count($custom_query).' Transactions:</div>';
echo '<div class="list-group list-grey">';
foreach($custom_query as $x){
    echo view_x($x);
    $parts = explode(':',$x['x__message']);
    if(count($parts)==3){
        echo '<div class="idea montserrat">MATCH</div>';
    } else {
        echo '<div class="discover montserrat">MISMATCH</div>';
    }
}
echo '</div>';