<?php

//die('retired for now'); //No longer needed

$custom_query = $this->X_model->fetch(array(
    "x__type IN (10679) AND x__status=6176 AND x_id>=1001502720" => null,
), array(), 0);

//Update format of video slicing
echo '<div>Found '.count($custom_query).' Transactions:</div>';
echo '<div class="list-group list-grey">';
foreach($custom_query as $x){
    echo view_x($x);
    continue;
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
        $this->X_model->update($x['x__id'], array(
            'x__message' => $new_message,
        ), 1, 10679);

        echo '<div class="idea montserrat">REPLACED WITH: '.$new_message.'</div>';

    } else {
        echo '<div class="discover montserrat">MISMATCH</div>';
    }
}
echo '</div>';