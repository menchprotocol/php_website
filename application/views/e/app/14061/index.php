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
    if(count($parts)==3 && second_calc($parts[1])>=0 && second_calc($parts[2])>0){

        if($parts[1]>=60){
            echo '<div class="discover montserrat">1 NEED ADJUSTMENT!!!!!!!!</div>';
            $x['x__message'] = str_replace(':'. $parts[1],':'.floor($parts[1]/60) .'.'. ( fmod($parts[1],60)<10 ? '0' : '' ) . fmod($parts[1],60),$x['x__message']);
        }
        if($parts[2]>=60){
            echo '<div class="discover montserrat">2 NEED ADJUSTMENT!!!!!!!!</div>';
            $x['x__message'] = str_replace(':'. $parts[2],':'.floor($parts[2]/60) .'.'. ( fmod($parts[2],60)<10 ? '0' : '' ).fmod($parts[2],60),$x['x__message']);
        }

        $new_message = str_replace('.',':',str_replace(':','|',$x['x__message']));
        echo '<div class="idea montserrat">REPLACE WITH: '.$new_message.'</div>';


    } else if(count($parts)==4) {
        echo '<div class="discover montserrat">4match</div>';
    } else {
        echo '<div class="discover montserrat">MISMATCH</div>';
    }
}
echo '</div>';