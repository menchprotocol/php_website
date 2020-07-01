<?php

$scanned = 0;
$skipped = 0;
$fixed = 0;

foreach($this->X_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('e___n_4592')) . ')' => null, //SOURCE LINKS
), array(), 0) as $e_link){

    if(filter_var($e_link['x__message'], FILTER_VALIDATE_URL)){
        //SKIP URLS:
        $skipped++;
        continue;
    }

    $scanned++;
    $detected_x_type = x_detect_type($e_link['x__message']);
    if ($detected_x_type['status']){
        if(!($detected_x_type['x__type'] == $e_link['x__type'])){
            $fixed++;
            $this->X_model->update($e_link['x__id'], array(
                'x__type' => $detected_x_type['x__type'],
            ));
        }
    } else {
        echo 'ERROR for Link ID '.$e_link['x__id'].': '.$detected_x_type['message'].'<hr />';
    }

}

echo $fixed.'/'.$scanned.' Links Fixed & '.$skipped.' Skipped.';

